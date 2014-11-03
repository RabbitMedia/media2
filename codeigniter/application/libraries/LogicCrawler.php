<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LogicCrawler {

	protected $CI;

	const CRAWL_PAGE_NUM			=	2;				// クロールするページ数

	const BASE_URL_TENGOKU			=	'http://www.tengokudouga.com/';
	const PAGE_URL_TENGOKU			=	'http://www.tengokudouga.com/?p=%page_num%';

	const BASE_URL_NUKIST			=	'http://www.nukistream.com/';
	const PAGE_URL_NUKIST			=	'http://www.nukistream.com/?p=%page_num%';

	function __construct()
	{
		$this->CI =& get_instance();
	}

	/**
	 * 指定サイトから動画情報を取得する
	 */
	public function get_from_tengoku()
	{
		// 動画配列
		$videos = array();
		// カウント
		$count = 0;

		// CRAWL_PAGE_NUMに定義されてるページ数分クロールする
		for ($i=1; $i <= self::CRAWL_PAGE_NUM; $i++)
		{ 
			// ページを取得
			$url = str_replace('%page_num%', $i, self::PAGE_URL_TENGOKU);
			$html = file_get_contents($url);
			// ページの取得に失敗したらfalseを返す
			if (!$html)
			{
				return false;
			}

			// 文字コードをSJISに変換
			// $html = mb_convert_encoding($html, 'SJIS', 'auto');
			// 改行コードを削除
			$html = preg_replace('/(\n|\r)/', '', $html);

			// 動画情報を正規表現で抽出
			if (preg_match_all('/(?<=cntInfo).*?(?=\<\/a>\<\/h2)/', $html, $elements))
			{
				foreach ($elements[0] as $element)
				{
					// PRコンテンツは除外
					if (strpos($element, '>PR<'))
					{
						continue;
					}
					else
					{
						// タイトルとコンテンツページURLを抽出
						if (preg_match('/(?<=<h2>).*/', $element, $matches))
						{
							// タイトルを抽出
							if (preg_match('/(?<=>).*/', $matches[0], $title))
							{
								$videos[$count]['title'] = $title[0];
							}
							// コンテンツページURLを抽出
							if (preg_match('/(?<=<a href=").*?(?=">.*)/', $matches[0], $contents_page_url))
							{
								// コンテンツページURLから動画IDを抽出
								$videos[$count]['video_url_id'] = $this->_get_xvideos_id(self::BASE_URL_TENGOKU.$contents_page_url[0]);
								// nullじゃなければタイプをセットする
								if (!is_null($videos[$count]['video_url_id']))
								{
									$videos[$count]['type'] = 1; // モデル名::TYPE_XVIDEOSみたいに書く
								}
							}
						}
						else
						{
							continue;
						}

						// 再生時間を抽出
						if (preg_match('/(?<=labelUpdate">).*?(?=<\/span)/', $element, $matches))
						{
							$videos[$count]['duration'] = null;

							// 時間を"h"で表現している場合
							if (strpos($matches[0], 'h'))
							{
								if (preg_match('/.*(?=h)/', $matches[0], $hours) && preg_match('/(?<=h ).*?(?=:)/', $matches[0], $minutes) && preg_match('/(?<=:).*/', $matches[0], $seconds))
								{
									// 時間は全て"分"に直していたが、mysqlのTIME型に入れるため変更
									// $videos[$count]['duration'] = (((int)$hours[0] * 60) + (int)$minutes[0]).':'.$seconds[0];
									$videos[$count]['duration'] = $hours[0].':'.$minutes[0].':'.$seconds[0];
								}
							}
							else
							{
								if (preg_match('/.*?(?=:)/', $matches[0], $minutes) && preg_match('/(?<=:).*/', $matches[0], $seconds))
								{
									// "分"表示で60分を超えるものは時間として変換する
									if ($minutes[0] >= 60)
									{
										$videos[$count]['duration'] = floor((int)$minutes[0] / 60).':'.((int)$minutes[0] % 60).':'.$seconds[0];
									}
									else
									{
										$videos[$count]['duration'] = '00:'.$minutes[0].':'.$seconds[0];
									}
								}
							}
						}

						// 情報が入っていればメディアをセットする
						if (isset($videos[$count]))
						{
							$videos[$count]['media'] = 1; // モデル名::MEDIA_TENGOKUみたいに書く
						}

						// カウントをインクリメント
						$count++;
					}
				}
			}
			else
			{
				return false;
			}
		}

		// 掲載古い順に入れ替える
		$reversed = array_reverse($videos);

		return $reversed;
	}

	/**
	 * 指定サイトから動画情報を取得する
	 */
	public function get_from_nukist()
	{
		// 動画配列
		$videos = array();
		// カウント
		$count = 0;

		// CRAWL_PAGE_NUMに定義されてるページ数分クロールする
		for ($i=1; $i <= self::CRAWL_PAGE_NUM; $i++)
		{ 
			// ページを取得
			$url = str_replace('%page_num%', $i, self::PAGE_URL_NUKIST);
			$html = file_get_contents($url);
			// ページの取得に失敗したらfalseを返す
			if (!$html)
			{
				return false;
			}

			// 文字コードをSJISに変換
			// $html = mb_convert_encoding($html, 'SJIS', 'auto');
			// 改行コードを削除
			$html = preg_replace('/(\n|\r)/', '', $html);

			// 動画情報を正規表現で抽出
			if (preg_match_all('/(?<=cntInfo).*?(?=\<\/a>\<\/h2)/', $html, $elements))
			{
				foreach ($elements[0] as $element)
				{
					// xvideosコンテンツ以外は除外
					if (!strpos($element, 'xv.png'))
					{
						continue;
					}
					else
					{
						// タイトルとコンテンツページURLを抽出
						if (preg_match('/(?<=<h2>).*/', $element, $matches))
						{
							// タイトルを抽出
							if (preg_match('/(?<=>).*/', $matches[0], $title))
							{
								$videos[$count]['title'] = $title[0];
							}
							// コンテンツページURLを抽出
							if (preg_match('/(?<=<a href=").*?(?=">.*)/', $matches[0], $contents_page_url))
							{
								// コンテンツページURLから動画IDを抽出
								$videos[$count]['video_url_id'] = $this->_get_xvideos_id(self::BASE_URL_NUKIST.$contents_page_url[0]);
								// nullじゃなければタイプをセットする
								if (!is_null($videos[$count]['video_url_id']))
								{
									$videos[$count]['type'] = 1; // モデル名::TYPE_XVIDEOSみたいに書く
								}
							}
						}
						else
						{
							continue;
						}

						// 再生時間を抽出
						if (preg_match('/(?<=labelUpdate">).*?(?=<\/span)/', $element, $matches))
						{
							$videos[$count]['duration'] = null;

							if (preg_match('/.*?(?=:)/', $matches[0], $minutes) && preg_match('/(?<=:).*/', $matches[0], $seconds))
							{
								// "分"表示で60分を超えるものは時間に変換する
								if ($minutes[0] >= 60)
								{
									$videos[$count]['duration'] = floor((int)$minutes[0] / 60).':'.((int)$minutes[0] % 60).':'.$seconds[0];
								}
								else
								{
									$videos[$count]['duration'] = '00:'.$minutes[0].':'.$seconds[0];
								}
							}
						}

						// 情報が入っていればメディアをセットする
						if (isset($videos[$count]))
						{
							$videos[$count]['media'] = 2; // モデル名::MEDIA_NUKISTみたいに書く
						}

						// カウントをインクリメント
						$count++;
					}
				}
			}
			else
			{
				return false;
			}
		}

		// 掲載古い順に入れ替える
		$reversed = array_reverse($videos);

		return $reversed;
	}

	/**
	 * クローラーが集めてきた動画を登録する
	 */
	public function set_crawled_videos($contents)
	{
		// ロード
		$this->CI->load->model('crawler_video_master_model');
		$this->CI->load->model('crawler_video_id_model');
		$this->CI->load->model('crawler_video_title_model');

		foreach ($contents as $c_key => $videos)
		{
			foreach ($videos as $v_key => $video)
			{
				// 次のクローラー動画マスターIDを取得する
				$next_crawler_master_id = $this->CI->crawler_video_master_model->get_next_crawler_master_id();

				// トランザクション begin
				$this->CI->db->trans_begin();

				// crawler_video_idに登録する
				$results = array();
				foreach ($video['video_url_id'] as $video_url_id)
				{
					$data = array(
						'crawler_master_id'	=> $next_crawler_master_id,
						'type'				=> $video['type'],
						'video_url_id'		=> $video_url_id,
						);
					$results[] = $this->CI->crawler_video_id_model->insert($data);
				}

				// crawler_video_idに登録成功フラグ
				$crawler_video_id_insert_flag = true;

				// resultsが正常でなければinsertに失敗している
				foreach ($results as $key => $result)
				{
					if (!$result)
					{
						// crawler_video_idに登録成功フラグ
						$crawler_video_id_insert_flag = false;

						// INSERT IGNORE により弾かれたのか確認する
						$exist_crawler_master_id = $this->CI->crawler_video_id_model->get_by_url($video['type'], $video['video_url_id'][$key]);

						// INSERT IGNORE により弾かれた場合はタイトルを登録する
						if ($exist_crawler_master_id)
						{
							// 同じタイトルが既に存在する場合は登録しない
							$exist_titles = $this->CI->crawler_video_title_model->get($exist_crawler_master_id);
							foreach ($exist_titles as $e_key => $value)
							{
								if ($value['title'] == $video['title'])
								{
									break 2;
								}
							}

							// crawler_video_titleに登録する
							$data = array(
								'crawler_master_id'	=> $exist_crawler_master_id,
								'media'				=> $video['media'],
								'title'				=> $video['title'],
								);
							$affected_rows = $this->CI->crawler_video_title_model->insert($data);

							// affected_rowsがなければinsertに失敗している
							if (!$affected_rows)
							{
								// トランザクション rollback
								$this->CI->db->trans_rollback();

								break;
							}
						}
						else
						{
							// トランザクション rollback
							$this->CI->db->trans_rollback();

							// ログを残す
							foreach ($video['video_url_id'] as $video_url_id)
							{
								log_message('error', '[set_crawled_videos crawler_video_id_model->insert ERROR] type:'.$video['type'].' video_url_id:'.$video_url_id);
							}

							break;
						}
					}
				}

				if (!$crawler_video_id_insert_flag)
				{
					// トランザクション commit
					$this->CI->db->trans_commit();

					continue;
				}

				// crawler_video_masterに登録する
				$data = array(
					'crawler_master_id'	=> $next_crawler_master_id,
					'duration'			=> $video['duration'],
					);
				$affected_rows = $this->CI->crawler_video_master_model->insert($data);

				// affected_rowsがなければinsertに失敗している
				if (!$affected_rows)
				{
					// トランザクション rollback
					$this->CI->db->trans_rollback();

					continue;
				}

				// crawler_video_titleに登録する
				$data = array(
					'crawler_master_id'	=> $next_crawler_master_id,
					'media'				=> $video['media'],
					'title'				=> $video['title'],
					);
				$affected_rows = $this->CI->crawler_video_title_model->insert($data);

				// affected_rowsがなければinsertに失敗している
				if (!$affected_rows)
				{
					// トランザクション rollback
					$this->CI->db->trans_rollback();

					continue;
				}

				// トランザクション commit
				$this->CI->db->trans_commit();
			}
		}
	}

	/**
	 * クローラーが集めてきた動画を取得する
	 */
	public function get_crawled_videos()
	{
		// ロード
		$this->CI->load->model('crawler_video_master_model');
		$this->CI->load->model('crawler_video_id_model');
		$this->CI->load->model('crawler_video_title_model');
		$media = parse_ini_file(APPPATH.'resource/ini/media.ini', true);

		// 動画配列
		$videos = array();

		// 動画マスター情報を取得する
		$videos = $this->CI->crawler_video_master_model->get();
		
		// 動画がなければ何もしない
		if (!$videos)
		{
			return $videos;
		}

		// 動画マスター情報をもとに詳細情報を取得する
		foreach ($videos as $id => $video)
		{
			// 動画タイプと動画IDを取得する
			$results = $this->CI->crawler_video_id_model->get($video['crawler_master_id']);
			if (!empty($results))
			{
				foreach ($results as $key => $value)
				{
					$videos[$id]['type'][$key] = $value['type'];
					$videos[$id]['video_url_id'][$key] = $value['video_url_id'];
				}
			}

			// 動画掲載メディアと動画タイトルを取得する
			$results = $this->CI->crawler_video_title_model->get($video['crawler_master_id']);
			if (!empty($results))
			{
				foreach ($results as $key => $value)
				{
					$videos[$id]['media'][$key] = $media[$value['media']]['name'];
					$videos[$id]['title'][$key] = $value['title'];
				}
			}
		}

		return $videos;
	}

	/**
	 * 指定サイトのコンテンツページからxvideosの動画IDを取得する
	 */
	private function _get_xvideos_id($url)
	{
		// 動画ID
		$video_id = null;

		// ページを取得
		$html = file_get_contents($url);
		// ページの取得に失敗したらnullを返す
		if (!$html)
		{
			return null;
		}

		// 改行コードを削除
		$html = preg_replace('/(\n|\r)/', '', $html);

		// 動画IDを抽出する
		if (preg_match_all('/(?<=embedframe\/).*?(?=")/', $html, $matches))
		{
			foreach ($matches[0] as $match)
			{
				$video_id[] = $match;
			}
		}

		return $video_id;
	}
}

/* End of file LogicThumbnail.php */