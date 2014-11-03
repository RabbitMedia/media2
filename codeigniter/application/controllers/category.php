<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 各種カテゴリーページコントローラ
 */
class Category extends CI_Controller
{
	public function index($category_id = 0, $page = 1)
	{
		// 各種ライブラリのロード
		$this->load->Library('LogicVideoManage');
		$this->load->Library('pagination');

		$data = array();

		// category_idがなければ404
		if (!$category_id)
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

			// 指定カテゴリー名を取得
			if ($value['id'] == $category_id)
			{
				$data['current_category_id'] = $category_id;
				$data['current_category_name'] = $value['name'];
			}
		}
		$data['categories'] = $categories;

		// 指定カテゴリーの動画リストを取得する
		$videos = $this->logicvideomanage->get_category_list($category_id);

		// 動画総数
		$data['total_count'] = count($videos);

		// ページネーション
		$config['base_url'] = '/category/'.$category_id.'/';
		$config['total_rows'] = $data['total_count'];
		$config['per_page'] = 20;
		$config['use_page_numbers'] = true;
		$config['uri_segment'] = 3;
		$config['num_links'] = 2;
		$config['first_link'] = false;
		$config['last_link'] = false;
		$config['full_tag_open'] = '';
		$config['full_tag_close'] = '';
		$config['prev_link'] = '前へ';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '次へ';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		// ページ0を指定されたときの対策
		$page = (!$page) ? 1 : $page;

		// 該当ページに表示する動画を取得する(mysqlのlimitとphpのarray_sliceではどっちが速いかは未検証)
		$data['videos'] = array();
		if ($videos)
		{
			$data['videos'] = array_slice($videos, (($page - 1) * $config['per_page']), $config['per_page']);
		}

		// SEO link rel="prev", "next" セット用
		$data['page'] = $page;
		if (isset($videos[($page * $config['per_page'])]))
		{
			$data['page_next_flag'] = true;
		}
		else
		{
			$data['page_next_flag'] = false;
		}

		$this->load->view('category', $data);
	}
}