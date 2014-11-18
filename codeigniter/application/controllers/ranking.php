<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ランキングページコントローラ
 */
class Ranking extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		// ロード
		$this->load->Library('LogicRanking');
		$this->load->Library('LogicUserAgent');
		$this->load->Library('pagination');
	}

	/**
	 * トップページ
	 */
	public function index($page = 1)
	{
		$data = array();

		// ランキングを取得する(最新)
		$products = $this->logicranking->get();

		$data['products'] = $products;

		// ページネーション
		$config['base_url'] = '/ranking';
		$config['total_rows'] = count($products);
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

		// ユーザーエージェントを判定する
		$data['is_mobile'] = $this->logicuseragent->get_is_mobile();

		// 該当ページに表示する作品を取得する(mysqlのlimitとphpのarray_sliceではどっちが速いかは未検証)
		$data['products'] = array_slice($products, (($page - 1) * $config['per_page']), $config['per_page']);
		// 動画が存在しない場合は404
		if (!$data['products'])
		{
			show_404();
		}

		// 表示するランク
		foreach ($data['products'] as $key => $value)
		{
			$data['ranks'][] = ($page - 1) * $config['per_page'] + ($key + 1);
		}

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

		$this->load->view('ranking', $data);
	}
}