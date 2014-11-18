<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * カテゴリーで探すページコントローラ
 */
class Category extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		// ロード
		$this->load->Library('LogicVideoManage');
		$this->load->Library('LogicUserAgent');
		$this->load->Library('pagination');
		$this->app_ini = parse_ini_file(APPPATH.'resource/ini/app.ini', true);
	}

	public function index($category_id = 0, $page = 1)
	{
		$data = array();

		// カテゴリーID指定がなければカテゴリーリストページを表示する
		if (!$category_id)
		{
			// カテゴリーリストをiniから取得する
			foreach ($this->app_ini['category']['name'] as $key => $category_name)
			{
				$data['categories'][] = array(
					'id'	=> $key + 1,
					'name'	=> $category_name,
					);
			}

			$this->load->view('category_list', $data);
			return;
		}

		// 指定カテゴリーの動画リストを取得する
		$products = $this->logicvideomanage->get_by_category($category_id);

		// 動画総数
		$data['total_count'] = count($products);

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

		// ユーザーエージェントを判定する
		$data['is_mobile'] = $this->logicuseragent->get_is_mobile();

		// ページ0を指定されたときの対策
		$page = (!$page) ? 1 : $page;

		// 該当ページに表示する動画を取得する(mysqlのlimitとphpのarray_sliceではどっちが速いかは未検証)
		$data['products'] = array();
		if ($products)
		{
			$data['products'] = array_slice($products, (($page - 1) * $config['per_page']), $config['per_page']);
		}

		// 該当ページに表示する作品が存在しない場合は404
		if (!$data['products'])
		{
			show_404();
		}

		// 指定カテゴリーIDとカテゴリー名
		$data['current_category'] = array(
			'id'	=> $category_id,
			'name'	=> $this->app_ini['category']['name'][$category_id - 1],
			);

		// SEO link rel="prev", "next" セット用
		$data['page'] = $page;
		if (isset($products[($page * $config['per_page'])]))
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