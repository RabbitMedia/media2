<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LogicVideoManage
{
	protected $CI;

	function __construct()
	{
		$this->CI =& get_instance();

		// ロード
		$this->CI->load->model('product_master_model');
		$this->CI->load->model('product_text_model');
		$this->CI->load->model('product_actress_model');
		$this->CI->load->model('actress_list_model');
		$this->CI->load->model('label_list_model');
	}

	/**
	 * 全作品を取得する(新着順)
	 */
	public function get()
	{
		// 作品配列
		$products = array();

		// 作品マスター情報を取得する
		$products = $this->CI->product_master_model->get();

		// 作品がなければそのまま返す
		if (!$products)
		{
			return $products;
		}

		// 作品マスター情報をもとに詳細情報を取得する
		foreach ($products as $id => $product)
		{
			// 女優IDを取得する
			$product_actresses = $this->CI->product_actress_model->get_by_master_id($product['master_id']);
			if (!empty($product_actresses))
			{
				foreach ($product_actresses as $key => $product_actress)
				{
					// 女優名を取得する
					$product_actress['actress_name'] = $this->CI->actress_list_model->get_by_actress_id($product_actress['actress_id']);
					// 女優IDと女優名をセットする
					if ($product_actress['actress_name'])
					{
						$products[$id]['actress'][$key]['id'] = $product_actress['actress_id'];
						$products[$id]['actress'][$key]['name'] = $product_actress['actress_name'];
					}
				}
			}

			// レーベル名を取得する
			$products[$id]['label_name'] = $this->CI->label_list_model->get_by_label_id($product['label_id']);

			// 日付の形式を変更する
			$products[$id]['create_time'] = date('Y年n月j日', strtotime($product['create_time']));
		}

		return array_reverse($products);
	}

	/**
	 * 作品詳細を取得する
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
	 * 指定カテゴリーの全作品を取得する(新着順)
	 */
	public function get_by_category($category_id)
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
}