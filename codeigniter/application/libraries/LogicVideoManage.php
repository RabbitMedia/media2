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
		$this->CI->load->model('product_thumbnail_model');
		$this->CI->load->model('product_category_model');
		$this->CI->load->model('actress_list_model');
		$this->CI->load->model('label_list_model');
		$this->app_ini = parse_ini_file(APPPATH.'resource/ini/app.ini', true);
	}

	/**
	 * マスターID範囲指定で作品を取得する(新着順)
	 */
	public function get_by_range($from_master_id, $to_master_id)
	{
		// 作品配列
		$products = array();

		// 作品マスター情報を取得する
		$products = $this->CI->product_master_model->get_by_master_id_range($from_master_id, $to_master_id);

		// 作品がなければそのまま返す
		if (!$products)
		{
			return $products;
		}

		// 作品マスター情報をもとに詳細情報を取得する
		foreach ($products as $id => $product)
		{
			// メインサムネイルURLをセットする
			$product_id_url = str_replace('-', '/', $product['product_id']);
			$products[$id]['main_thumbnail_url'] = str_replace('%PRODUCT_ID%', $product_id_url, $this->app_ini['url']['main_thumbnail']);

			// 日付の形式を変更する
			$products[$id]['create_time'] = date('Y年n月j日', strtotime($product['create_time']));
		}

		return array_reverse($products);
	}

	/**
	 * 複数マスターIDを指定して作品を取得する
	 */
	public function get_by_array($master_id_array)
	{
		// 作品配列
		$products = array();

		// 作品マスター情報を取得する
		$products = $this->CI->product_master_model->get_by_master_id_array($master_id_array);

		// 作品がなければそのまま返す
		if (!$products)
		{
			return $products;
		}

		// 作品マスター情報をもとに詳細情報を取得する
		foreach ($products as $id => $product)
		{
			// メインサムネイルURLをセットする
			$product_id_url = str_replace('-', '/', $product['product_id']);
			$products[$id]['main_thumbnail_url'] = str_replace('%PRODUCT_ID%', $product_id_url, $this->app_ini['url']['main_thumbnail']);

			// 日付の形式を変更する
			$products[$id]['create_time'] = date('Y年n月j日', strtotime($product['create_time']));
		}

		return $products;
	}

	/**
	 * 作品詳細を取得する
	 */
	public function get_details($master_id)
	{
		// 作品配列
		$products = array();

		// 動画マスター情報を取得する
		$products = $this->CI->product_master_model->get_by_master_id($master_id);

		// 動画がなければそのまま返す
		if (!$products)
		{
			return $products;
		}

		// 作品マスター情報をもとに詳細情報を取得する
		foreach ($products as $p_key => $value)
		{
			// 各種情報を入れ直す
			$product['master_id'] = $value['master_id'];
			$product['product_id'] = $value['product_id'];
			$product['title'] = $value['title'];
			$product['label_id'] = $value['label_id'];

			// アフィリエイトリンクをセットする
			if (preg_match('/(?<=duga\.jp\/).*/', $value['product_url'], $matches))
			{
				$path = $matches[0];
				$search = array('%PATH%', '%AGENT_ID%', '%BANNER_ID%');
				$replace = array($path, $this->app_ini['common']['agent_id'], $this->app_ini['common']['banner_id']);
				$product['affiliate_link'] = str_replace($search, $replace, $this->app_ini['url']['affiliate_link']);
			}

			// 作品本文を取得する
			$product['text'] = $this->CI->product_text_model->get_by_master_id($master_id);

			// 女優IDを取得する
			$product_actresses = $this->CI->product_actress_model->get_by_master_id($master_id);
			if (!empty($product_actresses))
			{
				foreach ($product_actresses as $pa_key => $product_actress)
				{
					// 女優名を取得する
					$product_actress['actress_name'] = $this->CI->actress_list_model->get_by_actress_id($product_actress['actress_id']);
					// 女優IDと女優名をセットする
					if ($product_actress['actress_name'])
					{
						$product['actress'][$pa_key]['id'] = $product_actress['actress_id'];
						$product['actress'][$pa_key]['name'] = $product_actress['actress_name'];
					}
				}
			}

			// カテゴリーを取得する
			$categories = $this->CI->product_category_model->get_by_master_id($master_id);

			if (!empty($categories))
			{
				foreach ($categories as $c_key => $category)
				{
					// カテゴリーIDとカテゴリー名をセットする
					$product['categories'][] = array(
						'id'	=> $category['category_id'],
						'name'	=> $this->app_ini['category']['name'][(int)$category['category_id'] - 1],
						);
				}
			}
			else
			{
				// カテゴリーIDとカテゴリー名をセットする
				$product['categories'][] = array(
					'id'	=> '0',
					'name'	=> 'なし',
					);
			}

			// レーベル名を取得する
			$product['label_name'] = $this->CI->label_list_model->get_by_label_id($value['label_id']);

			// サブサムネイルを取得する
			$product['sub_thumbnail_url'] = $this->CI->product_thumbnail_model->get_by_master_id($master_id);

			// 日付の形式を変更する
			$product['create_time'] = date('Y年n月j日', strtotime($value['create_time']));
		}

		return $product;
	}

	/**
	 * 指定カテゴリーIDの全作品を取得する(新着順)
	 */
	public function get_by_category($category_id)
	{
		// 作品配列
		$products = array();

		// 作品マスター情報を取得する
		$master_ids = $this->CI->product_category_model->get_by_category_id($category_id);
		// マスターIDが異常であればそのまま返す
		if (!$master_ids)
		{
			return $products;
		}

		foreach ($master_ids as $key => $value)
		{
			$master_id_array[] = $value['master_id'];
		}

		$products = $this->get_by_array($master_id_array);

		// 作品がなければそのまま返す
		if (!$products)
		{
			return $products;
		}

		return array_reverse($products);
	}

	/**
	 * 指定女優IDの全作品を取得する(新着順)
	 */
	public function get_by_actress($actress_id)
	{
		// 作品配列
		$products = array();

		// 作品マスター情報を取得する
		$master_ids = $this->CI->product_actress_model->get_by_actress_id($actress_id);

		foreach ($master_ids as $key => $value)
		{
			$master_id_array[] = $value['master_id'];
		}

		$products = $this->get_by_array($master_id_array);

		// 作品がなければそのまま返す
		if (!$products)
		{
			return $products;
		}

		return array_reverse($products);
	}

	/**
	 * 指定レーベルIDの全作品を取得する(新着順)
	 */
	public function get_by_label($label_id)
	{
		// 作品配列
		$products = array();

		// 作品マスター情報を取得する
		$products = $this->CI->product_master_model->get_by_label_id($label_id);

		// 作品がなければそのまま返す
		if (!$products)
		{
			return $products;
		}

		// 作品マスター情報をもとに詳細情報を取得する
		foreach ($products as $id => $product)
		{
			// メインサムネイルURLをセットする
			$product_id_url = str_replace('-', '/', $product['product_id']);
			$products[$id]['main_thumbnail_url'] = str_replace('%PRODUCT_ID%', $product_id_url, $this->app_ini['url']['main_thumbnail']);

			// 日付の形式を変更する
			$products[$id]['create_time'] = date('Y年n月j日', strtotime($product['create_time']));
		}

		return array_reverse($products);
	}

	/**
	 * ピックアップを取得する(トップページ)
	 */
	public function get_pickup()
	{
		// 作品配列
		$products = array();
		$pickup_products = array();

		// ピックアップで使用するカテゴリーIDをiniから取得する
		$category_id = $this->app_ini['top']['pickup_category'];
		// 複数指定の場合は配列に直す
		$category_id = (strpos($category_id, ',') !== false) ? explode(',', $category_id) : $category_id;

		// 複数指定の場合はランダムに1つだけ抽選する
		if (is_array($category_id))
		{
			shuffle($category_id);
			$category_id = $category_id[0];
		}

		// 指定カテゴリーIDの全作品を取得する(新着順)
		$products = $this->get_by_category($category_id);

		// 表示する件数をiniから取得してランダムに抽選する
		$product_keys = array_rand($products, $this->app_ini['top']['pickup_disp_num']);
		foreach ($product_keys as $key => $product_key)
		{
			$pickup_products[] = $products[$product_key];
		}

		return $pickup_products;
	}

	/**
	 * リコメンドを取得する
	 */
	public function get_recommend($master_id, $categories, $label_id)
	{
		// 作品配列
		$products = array();

		// まずは同一カテゴリーからの抽選を試みる
		$category_ids = array();
		foreach ($categories as $key => $category)
		{
			// 有効なカテゴリーがあるか確認
			if ($category['id'] != '0')
			{
				$category_ids[] = $category['id'];
			}
		}

		// 有効なカテゴリーがある場合
		if ($category_ids)
		{
			// 有効なカテゴリーが複数ある場合のためシャッフルする
			shuffle($category_ids);
			for ($i=0; $i < count($category_ids); $i++)
			{ 
				// 指定カテゴリーIDの全作品を取得する(新着順)
				$products += $this->get_by_category($category_ids[$i]);

				// 自らのマスターIDが含まれている場合は除外する
				foreach ($products as $key => $product)
				{
					if ($product['master_id'] == $master_id)
					{
						unset($products[$key]);
					}
				}

				// ini記載の表示件数よりも多ければランダム抽選して返す
				if (count($products) >= $this->app_ini['product']['recommend_disp_num'])
				{
					shuffle($products);
					return array_slice($products, 0, $this->app_ini['product']['recommend_disp_num']);
				}
			}
		}

		// 有効なカテゴリーが無かったもしくは同一カテゴリーでは表示件数を満たせなかった場合
		// 必要な残り件数
		$remaining_num = $this->app_ini['product']['recommend_disp_num'] - count($products);
		$remaining_products = array();

		// 次に同一レーベルからの抽選を試みる
		$remaining_products = $this->get_by_label($label_id);

		// 自らのマスターIDが含まれている場合は除外する
		foreach ($remaining_products as $key => $product)
		{
			if ($product['master_id'] == $master_id)
			{
				unset($remaining_products[$key]);
			}
		}

		// 必要な残り件数よりも多ければランダム抽選して返す
		if (count($remaining_products) >= $remaining_num)
		{
			shuffle($remaining_products);
			return array_merge($products, array_slice($remaining_products, 0, $remaining_num));
		}
		else
		{
			$products = array_merge($products, $remaining_products);
		}

		// 同一レーベルでは表示件数を満たせなかった場合
		// 必要な残り件数
		$remaining_num = $this->app_ini['product']['recommend_disp_num'] - count($products);

		// 最後に全作品から新着順で穴を埋め合わせる
		$total_count = $this->get_total_count();
		// 取得すべきマスターIDの範囲
		$from_master_id = $total_count - $remaining_num + 1;
		$to_master_id = $from_master_id + $remaining_num - 1;
		$remaining_products = $this->get_by_range($from_master_id, $to_master_id);

		return array_merge($products, $remaining_products);
	}

	/**
	 * 全作品数を取得する
	 */
	public function get_total_count()
	{
		// 全作品数を取得する
		$total_count = $this->CI->product_master_model->get_total_count();

		return $total_count;
	}
}