<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 新着動画ページコントローラ
 */
class Lists extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		// ロード
		$this->load->Library('LogicVideoManage');
		$this->load->Library('pagination');
		$this->app_ini = parse_ini_file(APPPATH.'resource/ini/app.ini', true);
	}

	/**
	 * 新着動画ページ
	 */
	public function index($page = 1)
	{
		$data = array();

		// 全作品数を取得する
		$total_count = $this->logicvideomanage->get_total_count();
		$data['total_count'] = $total_count;

		// ページネーション
		$config['base_url'] = '/lists';
		$config['total_rows'] = $total_count;
		$config['per_page'] = 20;
		$config['use_page_numbers'] = true;
		$config['uri_segment'] = 2;
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

		// 取得すべきマスターIDの範囲
		$from_master_id = $total_count - ($page * $config['per_page']) + 1;
		$to_master_id = $from_master_id + $config['per_page'] - 1;

		// 該当ページに表示する作品を取得する
		$data['products'] = $this->logicvideomanage->get_by_range($from_master_id, $to_master_id);

		// 作品が存在しない場合は404
		if (!$data['products'])
		{
			show_404();
		}

		// SEO link rel="prev", "next" セット用
		$data['page'] = $page;
		if ($from_master_id > 1)
		{
			$data['page_next_flag'] = true;
		}
		else
		{
			$data['page_next_flag'] = false;
		}

		// メインサムネイルURLをセットする
		foreach ($data['products'] as $key => $product)
		{
			$product_id = str_replace('-', '/', $product['product_id']);
			$data['products'][$key]['main_thumbnail_url'] = str_replace('%PRODUCT_ID%', $product_id, $this->app_ini['url']['main_thumbnail']);
		}

		$this->load->view('lists', $data);
	}
}