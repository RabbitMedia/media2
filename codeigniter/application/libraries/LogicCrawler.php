<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LogicCrawler
{
	protected $CI;

	// csvダウンロードURL
	// const CSV_DOWNLOAD_URL = 'http://duga.jp/productcsv/type=mp4/category=11/'; // 初回
	const CSV_DOWNLOAD_URL	= 'http://duga.jp/productcsv/type=mp4/category=11/openstt=%YYYYmm%01/';

	function __construct()
	{
		$this->CI =& get_instance();

		// ロード
		$this->CI->load->model('product_master_model');
		$this->CI->load->model('product_text_model');
		$this->CI->load->model('product_category_model');
		$this->CI->load->model('category_info_model');
	}

	/**
	 * 作品情報を取得する
	 */
	public function get_products()
	{
		// 指定URLからcsvを取得する(当月分)
		// $csv = file_get_contents(self::CSV_DOWNLOAD_URL); // 初回
		$csv = file_get_contents(str_replace('%YYYYmm%', date('Ym', strtotime("-1 month")), self::CSV_DOWNLOAD_URL));
		// 取得したcsvの文字コードをUTF-8に変更して保存する
		file_put_contents(APPPATH.'resource/csv/product.csv', mb_convert_encoding($csv, 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS'));

		// 保存したcsvを配列で取得する
		$product_csv = AppCsvLoader::load('product.csv');

		// 当月に新作が公開されていない場合はfalseを返す
		if (!$product_csv)
		{
			return false;
		}

		// 新作情報配列
		$products = array();
		// 公開日配列
		$release_date = array();
		// 新作登録カウント
		$cnt = 0;

		foreach ($product_csv as $key => $value)
		{
			// 作品IDによるレコード取得
			$result = $this->CI->product_master_model->get_by_product_id($value['商品ID']);
			// 作品IDが登録されていない作品（新作）を対象とする
			if (!$result)
			{
				// 新作情報配列に入れていく
				$products[$cnt]['product_id']	= $value['商品ID'];
				$products[$cnt]['title']		= $value['タイトル'];
				$products[$cnt]['product_url']	= $value['商品URL'];
				$products[$cnt]['text']			= $value['紹介文'];
				$products[$cnt]['category']		= explode(",", $value['出演者']);
				$products[$cnt]['release_date']	= $value['公開開始日'];

				// 公開日でソートするための準備
				$release_date[$cnt] = $value['公開開始日'];

				$cnt++;
			}
		}

		// 公開開始日でソートする(昇順)
		array_multisort($release_date, SORT_ASC, $products);

		return $products;
	}

	/**
	 * 作品情報を登録する
	 */
	public function set_products($products)
	{
		foreach ($products as $key => $product)
		{
			// トランザクション begin
			$this->CI->db->trans_begin();

			// product_masterに登録する
			$data = array(
				'product_id'	=> $product['product_id'],
				'title'			=> $product['title'],
				'product_url'	=> $product['product_url'],
				);
			$master_id = $this->CI->product_master_model->insert($data);

			// 正常なmaster_idが返ってこない場合はinsertに失敗している
			if (!$master_id)
			{
				// トランザクション rollback
				$this->CI->db->trans_rollback();

				// ログ
				log_message('error', '[logiccrawler->set_products product_master_model->insert ERROR]');
				log_message('error', print_r($data, true));

				continue;
			}

			// product_textに登録する
			$data = array(
				'master_id'	=> $master_id,
				'text'		=> $product['text'],
				);
			$affected_rows = $this->CI->product_text_model->insert($data);

			// affected_rowsがなければinsertに失敗している
			if (!$affected_rows)
			{
				// トランザクション rollback
				$this->CI->db->trans_rollback();

				// ログ
				log_message('error', '[logiccrawler->set_products product_text_model->insert ERROR]');
				log_message('error', print_r($data, true));

				continue;
			}

			// category_infoからカテゴリーIDを取得
			foreach ($product['category'] as $key => $category_name)
			{
				// カテゴリー名が無ければ「その他」に統一
				$category_name = (!$category_name) ? 'その他' : $category_name;
				$category_id = $this->CI->category_info_model->get_by_name($category_name);

				// 該当カテゴリーがあればカテゴリーIDを配列に入れる
				if ($category_id)
				{
					$product['category_id'][$key] = $category_id;
				}
				// 該当カテゴリーがなければ新しく追加する
				else
				{
					// category_infoに登録する
					$data = array(
						'name'	=> $category_name,
						);
					$category_id = $this->CI->category_info_model->insert($data);

					// 新しく追加されたカテゴリーIDを配列に入れる
					$product['category_id'][$key] = $category_id;
				}
			}

			// product_categoryに登録する
			$results = array();
			foreach ($product['category_id'] as $key => $category_id)
			{
				$data = array(
					'master_id'		=> $master_id,
					'category_id'	=> $category_id,
					);
				$results[] = $this->CI->product_category_model->insert($data);
			}

			// product_category登録結果フラグ
			$product_category_insert_result = true;

			// resultsが正常でなければinsertに失敗している
			foreach ($results as $key => $result)
			{
				if (!$result)
				{
					// product_category登録結果フラグ
					$product_category_insert_result = false;

					// ログ
					log_message('error', '[logiccrawler->set_products product_category_model->insert ERROR]');
					log_message('error', print_r($data, true));
				}
			}
			// insertに失敗している場合はrollback
			if (!$product_category_insert_result)
			{
				// トランザクション rollback
				$this->CI->db->trans_rollback();

				continue;
			}

			// トランザクション commit
			$this->CI->db->trans_commit();
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
}