<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LogicVideoManage
{
	protected $CI;

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('video_master_model');
		$this->CI->load->model('video_id_model');
		$this->CI->load->model('video_category_model');
		$this->CI->load->model('crawler_video_master_model');
		$this->CI->load->model('crawler_video_id_model');
		$this->CI->load->model('crawler_video_title_model');
		$this->CI->load->library('LogicEmbed');
	}

	/**
	 * 最新のトップページ動画リストを取得する
	 */
	public function get_top_list()
	{
		// 動画配列
		$videos = array();

		// カテゴリーcsvロード
		$category_csv = AppCsvLoader::load('category.csv');

		// 動画マスター情報を取得する
		$videos = $this->CI->video_master_model->get();

		// 動画がなければそのまま返す
		if (!$videos)
		{
			return $videos;
		}

		// 動画マスター情報をもとに詳細情報を取得する
		foreach ($videos as $id => $video)
		{
			// カテゴリーを取得する
			$results = $this->CI->video_category_model->get_by_id($video['master_id']);
			if (!empty($results))
			{
				foreach ($results as $r_key => $r_value)
				{
					// カテゴリーcsvからカテゴリー名を取得してセット
					foreach ($category_csv as $c_key => $c_value)
					{
						if ($c_value['id'] == $r_value['category'])
						{
							$videos[$id]['category'][$r_key]['id'] = $r_value['category'];
							$videos[$id]['category'][$r_key]['name'] = $c_value['name'];
						}
					}
				}
			}

			// 日付の形式を変更する
			$videos[$id]['create_time'] = date('Y年n月j日', strtotime($video['create_time']));
		}

		return array_reverse($videos);
	}

	/**
	 * 動画ページ詳細を取得する
	 */
	public function get_details($master_id)
	{
		// 動画配列
		$videos = array();

		// カテゴリーcsvロード
		$category_csv = AppCsvLoader::load('category.csv');

		// 動画マスター情報を取得する
		$videos = $this->CI->video_master_model->get_by_id($master_id);

		// 動画がなければそのまま返す
		if (!$videos)
		{
			return $videos;
		}

		// 動画マスター情報をもとに詳細情報を取得する
		foreach ($videos as $v_key => $v_value)
		{
			// 各種情報を入れ直す
			$video['master_id'] = $v_value['master_id'];
			$video['title'] = $v_value['title'];
			$video['thumbnail_url'] = $v_value['thumbnail_url'];
			$video['duration'] = $v_value['duration'];

			// カテゴリーを取得する
			$results = $this->CI->video_category_model->get_by_id($master_id);
			if (!empty($results))
			{
				foreach ($results as $r_key => $r_value)
				{
					// カテゴリーcsvからカテゴリー名を取得してセット
					foreach ($category_csv as $c_key => $c_value)
					{
						if ($c_value['id'] == $r_value['category'])
						{
							$video['category'][$r_key]['id'] = $r_value['category'];
							$video['category'][$r_key]['name'] = $c_value['name'];
						}
					}
				}
			}

			// 動画IDを取得する
			$results = $this->CI->video_id_model->get_by_id($master_id);
			if (!empty($results))
			{
				foreach ($results as $r_key => $r_value)
				{
					$video['type'][$r_key] = $r_value['type'];
					$video['video_url_id'][$r_key] = $r_value['video_url_id'];

					// 埋め込みタグを取得する
					$video['embed_tag'][$r_key] = $this->CI->logicembed->get($r_value['type'], $r_value['video_url_id']);
				}
			}

			// 日付の形式を変更する
			$video['create_time'] = date('Y年n月j日', strtotime($v_value['create_time']));
		}

		return $video;
	}

	/**
	 * 指定カテゴリーの動画リストを取得する
	 */
	public function get_category_list($category_id)
	{
		// 動画配列
		$videos = array();

		// カテゴリーcsvロード
		$category_csv = AppCsvLoader::load('category.csv');

		// 動画カテゴリーによるレコード取得
		$videos = $this->CI->video_category_model->get_by_category($category_id);

		// 動画がなければそのまま返す
		if (!$videos)
		{
			return $videos;
		}

		// 動画カテゴリー情報をもとに詳細情報を取得する
		foreach ($videos as $id => $video)
		{
			// 動画マスター情報を取得する
			$video_master = $this->CI->video_master_model->get_by_id($video['master_id']);

			foreach ($video_master as $m_key => $m_value)
			{
				// カテゴリーを取得する
				$results = $this->CI->video_category_model->get_by_id($video['master_id']);
				foreach ($results as $r_key => $r_value)
				{
					// カテゴリーcsvからカテゴリー名を取得してセット
					foreach ($category_csv as $c_key => $c_value)
					{
						if ($c_value['id'] == $r_value['category'])
						{
							$videos[$id]['category'][$r_key]['id'] = $r_value['category'];
							$videos[$id]['category'][$r_key]['name'] = $c_value['name'];
						}
					}
				}

				// 各種情報を入れ直す
				$videos[$id]['title'] = $m_value['title'];
				$videos[$id]['thumbnail_url'] = $m_value['thumbnail_url'];
				$videos[$id]['duration'] = $m_value['duration'];

				// 日付の形式を変更する
				$videos[$id]['create_time'] = date('Y年n月j日', strtotime($m_value['create_time']));
			}

			// 指定カテゴリーが含まれていなければ加える(指定カテゴリーが非表示フラグの動画)
			if (!$video['display_flag'])
			{
				// カテゴリーcsvからカテゴリー名を取得してセット
				foreach ($category_csv as $c_key => $c_value)
				{
					if ($c_value['id'] == $category_id)
					{
						$videos[$id]['category'][$r_key+1]['id'] = $category_id;
						$videos[$id]['category'][$r_key+1]['name'] = $c_value['name'];
					}
				}
			}
		}

		return array_reverse($videos);
	}

	/**
	 * 動画をアップする
	 */
	public function upload($item)
	{
		// トランザクション begin
		$this->CI->db->trans_begin();

		// video_masterに登録する
		$data = array(
			'title'			=> html_escape($item['title']),
			'thumbnail_url'	=> $item['thumbnail'],
			'duration'		=> $item['duration'],
			);
		$master_id = $this->CI->video_master_model->insert($data);

		// master_idが正常でなければinsertに失敗している
		if (!$master_id)
		{
			// トランザクション rollback
			$this->CI->db->trans_rollback();
			return null;
		}
		else
		{
			// video_idに登録する
			$results = array();
			foreach ($item['type'] as $key => $type)
			{
				$data = array(
					'master_id'		=> $master_id,
					'type'			=> $type,
					'video_url_id'	=> $item['video_url_id'][$key],
					);
				$results[] = $this->CI->video_id_model->insert($data);
			}
			// 返り値チェック
			foreach ($results as $result)
			{
				if (!$result)
				{
					// トランザクション rollback
					$this->CI->db->trans_rollback();
					return null;
				}
			}

			// video_categoryにメインカテゴリーを登録する
			$results = array();
			foreach ($item['main_category'] as $category)
			{
				$data = array(
					'master_id'		=> $master_id,
					'category'		=> $category,
					'display_flag'	=> video_category_model::DISPLAY_ON,
					);
				$results[] = $this->CI->video_category_model->insert($data);
			}
			// 返り値チェック
			foreach ($results as $result)
			{
				if (!$result)
				{
					// トランザクション rollback
					$this->CI->db->trans_rollback();
					return null;
				}
			}

			// video_categoryにサブカテゴリーを登録する
			$results = array();
			foreach ($item['sub_category'] as $category)
			{
				$data = array(
					'master_id'		=> $master_id,
					'category'		=> $category,
					'display_flag'	=> video_category_model::DISPLAY_OFF,
					);
				$results[] = $this->CI->video_category_model->insert($data);
			}
			// 返り値チェック
			foreach ($results as $result)
			{
				if (!$result)
				{
					// トランザクション rollback
					$this->CI->db->trans_rollback();
					return null;
				}
			}

			// クロールド動画を削除する
			$result = $this->delete_crawled_videos($item['crawler_master_id'], false);
			// 返り値チェック
			if (!$result)
			{
				// トランザクション rollback
				$this->CI->db->trans_rollback();
				return null;
			}
		}

		// トランザクション commit
		$this->CI->db->trans_commit();

		return $master_id;
	}

	/**
	 * 動画を削除する(クロールド動画)
	 */
	public function delete_crawled_videos($crawler_master_id, $transaction_flag = false)
	{
		// トランザクション start
		if ($transaction_flag)
		{
			$this->CI->db->trans_start();
		}

		// crawler_video_masterを削除する
		$result = $this->CI->crawler_video_master_model->delete($crawler_master_id);
		// 返り値チェック
		if (!$result)
		{
			// トランザクション rollback
			if ($transaction_flag)
			{
				$this->CI->db->trans_rollback();
			}
			return null;
		}

		// crawler_video_idを削除する
		$result = $this->CI->crawler_video_id_model->delete($crawler_master_id);
		// 返り値チェック
		if (!$result)
		{
			// トランザクション rollback
			if ($transaction_flag)
			{
				$this->CI->db->trans_rollback();
			}
			return null;
		}

		// crawler_video_titleを削除する
		$result = $this->CI->crawler_video_title_model->delete($crawler_master_id);
		// 返り値チェック
		if (!$result)
		{
			// トランザクション rollback
			if ($transaction_flag)
			{
				$this->CI->db->trans_rollback();
			}
			return null;
		}

		// トランザクション complete
		if ($transaction_flag)
		{
			$this->CI->db->trans_complete();
		}

		return $crawler_master_id;
	}
}