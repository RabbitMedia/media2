<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 女優で探すページコントローラ
 */
class Actress extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		// ロード
		$this->load->Library('LogicActress');
		$this->load->Library('LogicVideoManage');
		$this->load->Library('LogicUserAgent');
		$this->load->Library('pagination');
		$this->load->helper('form');
		$this->app_ini = parse_ini_file(APPPATH.'resource/ini/app.ini', true);
	}

	/**
	 * 女優別作品ページ
	 */
	public function index($actress_id = 0, $page = 1)
	{
		$data = array();

		// 女優名を取得する
		$actress_name = $this->logicactress->get_actress_name($actress_id);
		// 存在しない場合は404
		if (!$actress_name)
		{
			show_404();
		}

		// 指定女優IDと女優名
		$data['current_actress'] = array(
			'id'	=> $actress_id,
			'name'	=> $actress_name,
			);

		// 指定女優IDの動画リストを取得する
		$products = $this->logicvideomanage->get_by_actress($actress_id);

		// 動画総数
		$data['total_count'] = count($products);

		// ページネーション
		$config['base_url'] = '/actress/'.$actress_id.'/';
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

		$this->load->view('actress', $data);
	}

	/**
	 * 女優50音順一覧ページ
	 */
	public function order($order_group = 1)
	{
		$data = array();

		// ボタンに表示する文言をiniから取得する
		$data['order_group_btn'] = $this->app_ini['actress_list']['order_group_btn'];
		// 現在のorder_group
		$data['current_order_group'] = $order_group;

		// 指定order_groupの女優リストを取得する
		$actresses = $this->logicactress->get_by_order($order_group - 1);
		// 女優リストが異常であれば404
		if (!$actresses)
		{
			show_404();
		}
		else
		{
			$data['actresses'] = $actresses;
		}

		$this->load->view('actress_list', $data);
	}
}