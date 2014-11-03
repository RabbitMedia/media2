<?php

class MY_Exceptions extends CI_Exceptions
{
	public function show_404($page = '', $log_error = true)
	{
		$CI =& get_instance();

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

		if ($log_error)
		{
			log_message('error', '404 Page Not Found --> '.$page);
		}

		$CI->load->view('error/error_404', $data);
		echo $CI->output->get_output();
		exit;
	}
}