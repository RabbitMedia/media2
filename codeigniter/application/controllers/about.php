<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * アバウトページコントローラ
 */
class About extends CI_Controller
{
	// サイトについてページ
	public function aboutus()
	{
		$data = array();

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

		$this->load->view('about/about', $data);
	}

	// 広告掲載についてページ
	public function ad()
	{
		$data = array();
		
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

		$this->load->view('about/ad', $data);
	}

	// お問い合わせページ
	public function contact()
	{
		$data = array();
		
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

		$this->load->view('about/contact', $data);
	}
}