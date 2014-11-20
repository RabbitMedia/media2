<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 作品ページコントローラ
 */
class Product extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		// ロード
		$this->load->Library('LogicVideoManage');
		$this->load->helper('url');
		$this->app_ini = parse_ini_file(APPPATH.'resource/ini/app.ini', true);
	}

	/**
	 * 作品ページ
	 */
	public function index($master_id = 0)
	{
		// master_idがなければ404
		if (!$master_id)
		{
			show_404();
		}

		$data = array();

		// 作品ページ詳細を取得する
		$data['product'] = $this->logicvideomanage->get_details($master_id);

		// リコメンド作品を取得する
		$data['recommend_products'] = $this->logicvideomanage->get_recommend($data['product']['master_id'], $data['product']['categories'], $data['product']['label_id']);

		// パンくずにリストに使用するカテゴリーを選択
		foreach ($data['product']['categories'] as $key => $category)
		{
			// カテゴリーがあれば最初のカテゴリーを使用する
			if ($category['id'] != '0')
			{
				$data['breadcrumb']['category_flag'] = true;
				$data['breadcrumb']['id'] = $category['id'];
				$data['breadcrumb']['name'] = $category['name'];
			}
			// カテゴリーがなければレーベルをパンくずに使用する
			else
			{
				$data['breadcrumb']['category_flag'] = false;
				$data['breadcrumb']['id'] = $data['product']['label_id'];
				$data['breadcrumb']['name'] = $data['product']['label_name'];
			}

			break;
		}

		// 代理店IDとバナーIDを取得する
		$data['agent_id'] = $this->app_ini['common']['agent_id'];
		$data['banner_id'] = $this->app_ini['common']['banner_id'];

		$this->load->view('product', $data);
	}
}