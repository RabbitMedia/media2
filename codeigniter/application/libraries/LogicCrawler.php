<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LogicCrawler
{
	protected $CI;

	function __construct()
	{
		$this->CI =& get_instance();

		// ロード
		$this->CI->load->model('product_master_model');
		$this->CI->load->model('product_text_model');
		$this->CI->load->model('product_actress_model');
		$this->CI->load->model('product_thumbnail_model');
		$this->CI->load->model('actress_list_model');
		$this->CI->load->model('label_list_model');
		$this->CI->load->model('ranking_model');
		$this->CI->load->model('ranking_control_model');
		$this->CI->load->model('product_category_model');
		$this->app_ini = parse_ini_file(APPPATH.'resource/ini/app.ini', true);
	}

	/**
	 * 作品情報を取得する
	 */
	public function get_products()
	{
		// 指定URLからcsvを取得する(初回は全期間分、2回目以降は当月分のみ)
		$csv = ($this->app_ini['flag']['initial_crawl']) ?
		file_get_contents($this->app_ini['url']['csv_all']) :
		file_get_contents(str_replace('%YYYYmm%', date('Ym'), $this->app_ini['url']['csv_month']));

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
			$master_id = $this->CI->product_master_model->get_by_product_id($value['商品ID']);
			// 作品IDが登録されていない作品（新作）を対象とする
			if (!$master_id)
			{
				// 新作情報配列に入れていく
				$products[$cnt]['product_id']	= $value['商品ID'];
				$products[$cnt]['title']		= $value['タイトル'];
				$products[$cnt]['product_url']	= $value['商品URL'];
				$products[$cnt]['label_name']	= $value['レーベル名'];
				$products[$cnt]['text']			= $value['紹介文'];
				$products[$cnt]['actresses']	= explode(",", $value['出演者']);
				$products[$cnt]['release_date']	= $value['公開開始日'];

				// サブサムネイルを取得する
				$products[$cnt]['sub_thumbnails'] = $this->_get_sub_thumbnails($value['商品ID']);

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

			// レーベル名が無ければ「その他」に統一
			$product['label_name'] = (!$product['label_name']) ? 'その他' : $product['label_name'];
			$label_id = $this->CI->label_list_model->get_by_label_name($product['label_name']);

			// 該当レーベルがあればレーベルIDを配列に入れる
			if ($label_id)
			{
				$product['label_id'] = $label_id;
			}
			// 該当レーベルがなければ新しく追加する
			else
			{
				// label_listに登録する
				$data = array(
					'label_name'	=> $product['label_name'],
					);
				$label_id = $this->CI->label_list_model->insert($data);

				// 新しく追加されたレーベルIDを配列に入れる
				$product['label_id'] = $label_id;
			}

			// product_masterに登録する
			$data = array(
				'product_id'	=> $product['product_id'],
				'title'			=> $product['title'],
				'product_url'	=> $product['product_url'],
				'label_id'		=> $product['label_id'],
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

			// actress_listから女優IDを取得
			foreach ($product['actresses'] as $key => $actress_name)
			{
				// 女優名が無ければ「その他」に統一
				$actress_name = (!$actress_name) ? 'その他' : $actress_name;
				$actress_id = $this->CI->actress_list_model->get_by_actress_name($actress_name);

				// 該当女優があれば女優IDを配列に入れる
				if ($actress_id)
				{
					$product['actress_id'][$key] = $actress_id;
				}
				// 該当女優がなければ新しく追加する
				else
				{
					// actress_listに登録する
					$data = array(
						'actress_name'	=> $actress_name,
						);
					$actress_id = $this->CI->actress_list_model->insert($data);

					// 新しく追加された女優IDを配列に入れる
					$product['actress_id'][$key] = $actress_id;
				}
			}

			// product_actressに登録する
			$results = array();
			foreach ($product['actress_id'] as $key => $actress_id)
			{
				$data = array(
					'master_id'		=> $master_id,
					'actress_id'	=> $actress_id,
					);
				$results[] = $this->CI->product_actress_model->insert($data);
			}

			// product_actress登録結果フラグ
			$product_actress_insert_result = true;

			// resultsが正常でなければinsertに失敗している
			foreach ($results as $key => $result)
			{
				if (!$result)
				{
					// product_actress登録結果フラグ
					$product_actress_insert_result = false;

					// ログ
					log_message('error', '[logiccrawler->set_products product_actress_model->insert ERROR]');
					log_message('error', print_r($data, true));
				}
			}
			// insertに失敗している場合はrollback
			if (!$product_actress_insert_result)
			{
				// トランザクション rollback
				$this->CI->db->trans_rollback();

				continue;
			}

			// product_thumbnailに登録する
			$results = array();
			foreach ($product['sub_thumbnails'] as $key => $thumbnail_url)
			{
				$data = array(
					'master_id'		=> $master_id,
					'thumbnail_url'	=> $thumbnail_url,
					);
				$results[] = $this->CI->product_thumbnail_model->insert($data);
			}

			// product_thumbnail登録結果フラグ
			$product_thumbnail_insert_result = true;

			// resultsが正常でなければinsertに失敗している
			foreach ($results as $key => $result)
			{
				if (!$result)
				{
					// product_thumbnail登録結果フラグ
					$product_thumbnail_insert_result = false;

					// ログ
					log_message('error', '[logiccrawler->set_products product_thumbnail_model->insert ERROR]');
					log_message('error', print_r($data, true));
				}
			}
			// insertに失敗している場合はrollback
			if (!$product_thumbnail_insert_result)
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
	 * ランキングを取得する
	 */
	public function get_ranking()
	{
		// ランキングページURLからhtmlを取得する
		$html = file_get_contents($this->app_ini['url']['ranking']);

		// ページの取得に失敗したらfalseを返す
		if (!$html)
		{
			return false;
		}

		// ランキング配列
		$products = array();
		// ランキング登録カウント
		$cnt = 0;

		// 改行コードを削除する
		$html = preg_replace('/(\n|\r)/', '', $html);

		// ランキング情報を正規表現で抽出する
		if (preg_match_all('/(?<=contentslistrank).*?(?=scoped)/', $html, $elements))
		{
			foreach ($elements[0] as $element)
			{
				// 作品IDを抽出する
				$product_id = null;
				if (preg_match('/(?<=pid=").*?(?=")/', $element, $matches))
				{
					// 作品IDによるレコード取得
					$master_id = $this->CI->product_master_model->get_by_product_id($matches[0]);
					// 作品IDが登録されていない場合はランキング対象から外す
					if (!$master_id)
					{
						continue;
					}

					$products[$cnt]['master_id'] = $master_id;
				}

				// 前週のランキングを抽出する
				$prev_rank = '-';
				if (preg_match('/(?<=rankprev">前週).*?(?=位)/', $element, $matches))
				{
					$products[$cnt]['prev_rank'] = $matches[0];
				}

				$cnt++;
			}
		}

		return $products;
	}

	/**
	 * ランキングを登録する
	 */
	public function set_ranking($products)
	{
		// トランザクション begin
		$this->CI->db->trans_begin();

		// 現在のランキングIDを取得する
		$ranking_id = $this->CI->ranking_control_model->get();

		// ランキングIDが存在すればインクリメントして更新する
		if ($ranking_id)
		{
			(int)$ranking_id++;
			$this->CI->ranking_control_model->update($ranking_id);
		}
		// ランキングIDが存在しなければ初回レコードを挿入する
		else
		{
			$data['ranking_id'] = $ranking_id = 1;
			$this->CI->ranking_control_model->insert($data);
		}

		// rankingに登録する
		$data = array();
		$results = array();
		foreach ($products as $key => $product)
		{
			$data = array(
				'ranking_id'	=> $ranking_id,
				'master_id'		=> $product['master_id'],
				'prev_rank'		=> $product['prev_rank'],
				);
			$results[] = $this->CI->ranking_model->insert($data);
		}

		// ranking登録結果フラグ
		$ranking_insert_result = true;

		// resultsが正常でなければinsertに失敗している
		foreach ($results as $key => $result)
		{
			if (!$result)
			{
				// product_actress登録結果フラグ
				$ranking_insert_result = false;

				// ログ
				log_message('error', '[logiccrawler->set_ranking ranking_model->insert ERROR]');
				log_message('error', print_r($data, true));
			}
		}

		// insertに失敗している場合はrollback
		if (!$ranking_insert_result)
		{
			// トランザクション rollback
			$this->CI->db->trans_rollback();
		}
		else
		{
			// トランザクション commit
			$this->CI->db->trans_commit();
		}
	}

	/**
	 * カテゴリー情報を取得する
	 */
	public function get_category()
	{
		// カテゴリー配列
		$products = array();

		// カテゴリーを指定する
		foreach ($this->app_ini['category']['name'] as $key => $category_name)
		{
			// カテゴリーID
			$category_id = $key + 1;

			// ページ数を上限100ページとする
			for ($page=0; $page<100; $page++)
			{ 
				// 指定カテゴリーページURLからhtmlを取得する
				$search = array('%CATEGORY_NAME%', '%PAGE%');
				$replace = array(urlencode(mb_convert_encoding($category_name, 'SJIS', 'ASCII,JIS,UTF-8,EUC-JP,SJIS')), $page);
				$html = file_get_contents(str_replace($search, $replace, $this->app_ini['url']['category']));

				// ページの取得に失敗したらfalseを返す
				if (!$html)
				{
					return false;
				}

				// 改行コードを削除する
				$html = preg_replace('/(\n|\r)/', '', $html);

				// ランキング情報を正規表現で抽出する
				if (preg_match_all('/(?<=contentslist).*?(?=scoped)/', $html, $elements))
				{
					foreach ($elements[0] as $element)
					{
						// 作品IDを抽出する
						$product_id = null;
						if (preg_match('/(?<=pid=").*?(?=")/', $element, $matches))
						{
							// 作品IDによるレコード取得
							$master_id = $this->CI->product_master_model->get_by_product_id($matches[0]);
							// 作品IDが登録されていない場合は対象から外す
							if (!$master_id)
							{
								continue;
							}

							// マスターIDとカテゴリーIDによってカテゴリーを取得し
							$category_exist = $this->CI->product_category_model->check_category_exist($master_id, $category_id);
							// カテゴリーが登録されている場合は対象から外す
							if ($category_exist)
							{
								continue;
							}

							$products[] = array(
								'master_id'		=> $master_id,
								'category_id'	=> $category_id,
								);
						}
					}
				}
				else
				{
					break;
				}
			}
		}

		return $products;
	}

	/**
	 * カテゴリー情報を登録する
	 */
	public function set_category($products)
	{
		// トランザクション begin
		$this->CI->db->trans_begin();

		// insert_batch用の配列を生成する
		$data = array();
		$now = date('Y-m-d H:i:s');
		foreach ($products as $key => $product)
		{
			$data[] = array(
				'master_id'		=> $product['master_id'],
				'category_id'	=> $product['category_id'],
				'create_time'	=> $now,
				'update_time'	=> $now,
				);
		}

		// product_categoryに登録する
		$result = $this->CI->product_category_model->insert_batch($data);

		// resultが正常でなければinsertに失敗している
		if (!$result)
		{
			// トランザクション rollback
			$this->CI->db->trans_rollback();

			// ログ
			log_message('error', '[logiccrawler->set_category product_category_model->insert_batch ERROR]');
			log_message('error', print_r($data, true));
		}
		else
		{
			// トランザクション commit
			$this->CI->db->trans_commit();
		}
	}

	/**
	 * 女優情報を取得する
	 */
	public function get_actress()
	{
		// 50音を指定する
		foreach ($this->app_ini['actress_list']['order'] as $key => $order)
		{
			// 女優配列
			$actresses = array();

			// 指定女優一覧ページURLからhtmlを取得する
			$html = @file_get_contents(str_replace('%ORDER%', $order, $this->app_ini['url']['actress_list']));

			// ページの取得に失敗したら次のorderに進む
			if (!$html)
			{
				continue;
			}

			// 改行コードを削除する
			$html = preg_replace('/(\n|\r)/', '', $html);

			// ランキング情報を正規表現で抽出する
			if (preg_match_all('/(?<=actresslist).*?(?=<\/div)/', $html, $elements))
			{
				foreach ($elements[0] as $element)
				{
					// 女優名を抽出する
					if (preg_match('/(?<=alt=").*?(?=")/', $element, $matches))
					{
						$actresses[] = $matches[0];
					}
				}
			}
			else
			{
				continue;
			}

			// 女優名を抽出できなければ次のorderに進む
			if (!$actresses)
			{
				continue;
			}

			// csvを生成する
			$csv = 'name'."\n";
			foreach ($actresses as $key => $actress)
			{
				$csv .= "\"".$actress."\"\n";
			}

			// csvを出力する
			file_put_contents(APPPATH.'resource/csv/actress/'.$order.'.csv', $csv);
		}
	}

	/**
	 * レーベル情報を取得する
	 */
	public function get_label()
	{
		// 50音を指定する
		foreach ($this->app_ini['label_list']['order'] as $key => $order)
		{
			// レーベル配列
			$labels = array();
			// レーベルパス配列
			$label_paths = array();

			// 'あ'だけ特殊なURLのため調整する
			if ($key == 0)
			{
				$url = str_replace('%ORDER%.html', '', $this->app_ini['url']['label_list']);
			}
			else
			{
				$url = str_replace('%ORDER%', $order, $this->app_ini['url']['label_list']);
			}

			// 指定レーベル一覧ページURLからhtmlを取得する
			$html = @file_get_contents($url);

			// ページの取得に失敗したら次のorderに進む
			if (!$html)
			{
				continue;
			}

			// 改行コードを削除する
			$html = preg_replace('/(\n|\r)/', '', $html);

			// ランキング情報を正規表現で抽出する
			if (preg_match_all('/(?<=float:left;"><a href=").*?(?=<\/div><\/div>)/', $html, $elements))
			{
				foreach ($elements[0] as $element)
				{
					// レーベル名を抽出する
					if (preg_match('/(?<=<b>).*?(?=<\/b>)/', $element, $matches))
					{
						$labels[] = $matches[0];
					}

					// レーベルパスを抽出する
					if (preg_match('/(?<=ppv\/).*?(?=\/)/', $element, $matches))
					{
						$label_paths[] = $matches[0];
					}

					// // レーベル説明を抽出する
					// if (preg_match('/(?<=<br>).*/', $element, $matches))
					// {
					// 	$label_descs[] = $matches[0];
					// }
				}
			}
			else
			{
				continue;
			}

			// レーベル名もしくはレーベルパスを抽出できなければ次のorderに進む
			if (!$labels || !$label_paths)
			{
				continue;
			}

			// csvを生成する
			$csv = 'name,path'."\n";
			foreach ($labels as $key => $actress)
			{
				$csv .= "\"".$actress."\",\"".$label_paths[$key]."\"\n";
			}

			// csvを出力する
			file_put_contents(APPPATH.'resource/csv/label/'.$order.'.csv', $csv);
		}
	}
}