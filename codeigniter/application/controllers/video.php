<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 動画ページコントローラ
 */
class Video extends CI_Controller
{
	public function index($master_id = 0)
	{
		// ライブラリのロード
		$this->load->Library('LogicVideoManage');
		$this->load->helper('url');

		$data = array();

		// master_idがなければ404
		if (!$master_id)
		{
			show_404();
		}

		// カテゴリーリスト
		$category_csv = AppCsvLoader::load('category.csv');
		$categories = array();
		foreach ($category_csv as $key => $value)
		{
			if ($value['display_flag'])
			{
				$category['name'] = $value['name'];
				$category['id'] = $value['id'];
				$categories[] = $category;
			}
		}
		$data['categories'] = $categories;

		// 動画ページ詳細を取得する
		$data['video'] = $this->logicvideomanage->get_details($master_id);

		// 指定カテゴリーからアクセスの場合パンくずを指定カテゴリーにする
		$referer = $this->input->server('HTTP_REFERER');
		if (strpos($referer, site_url('category')) !== false)
		{
			$data['referer_flag'] = true;
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

		$this->load->view('video', $data);
	}
}