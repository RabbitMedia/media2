<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * エラーページコントローラ
 */
class Error extends CI_Controller
{
	// 404
	public function error_404()
	{
		$data = array();

		// HTTPステータスコードをセットする
		$this->output->set_status_header('404');

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

		$this->load->view('error/error_404', $data);
	}
}