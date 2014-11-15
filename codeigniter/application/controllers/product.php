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

		// リファラーをパンくずに使用する
		$referer = $this->input->server('HTTP_REFERER');
		$data['referer_flag'] = true;

		// すべての動画ページからの遷移の場合
		if (strpos($referer, site_url('lists')) !== false)
		{
			$data['referer_lists_id'] = '1';
			if (preg_match('/(?<=lists\/)\d+/', $referer, $matches))
			{
				$data['referer_lists_id'] = $matches[0];
			}
		}
		elseif (strpos($referer, site_url('category')) !== false)
		{
			if (preg_match('/(?<=category\/)\d+/', $referer, $matches))
			{
				$data['referer_category_id'] = $matches[0];
				foreach ($category_csv as $key => $value)
				{
					if ($value['id'] == $data['referer_category_id'])
					{
						$data['referer_category_name'] = $value['name'];
					}
				}
			}
		}
		else
		{
			$data['referer_flag'] = false;
		}

		// 代理店IDとバナーIDを取得する
		$data['agent_id'] = $this->app_ini['common']['agent_id'];
		$data['banner_id'] = $this->app_ini['common']['banner_id'];

		$this->load->view('product', $data);
	}
}