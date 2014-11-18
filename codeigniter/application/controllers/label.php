<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * レーベルで探すページコントローラ
 */
class Label extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		// ロード
		$this->load->Library('Logiclabel');
		$this->load->Library('LogicVideoManage');
		$this->load->Library('LogicUserAgent');
		$this->load->Library('pagination');
		$this->app_ini = parse_ini_file(APPPATH.'resource/ini/app.ini', true);
	}

	/**
	 * レーベル別作品ページ
	 */
	public function index($label_id = 0, $page = 1)
	{
		$data = array();

		// レーベル名を取得する
		$label_name = $this->logiclabel->get_label_name($label_id);
		// 存在しない場合は404
		if (!$label_name)
		{
			show_404();
		}

		// 指定レーベルIDとレーベル名
		$data['current_label'] = array(
			'id'	=> $label_id,
			'name'	=> $label_name,
			);

		// 指定レーベルIDの動画リストを取得する
		$products = $this->logicvideomanage->get_by_label($label_id);

		// 動画総数
		$data['total_count'] = count($products);

		// ページネーション
		$config['base_url'] = '/label/'.$label_id.'/';
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

		$this->load->view('label', $data);
	}

	/**
	 * レーベル50音順一覧ページ
	 */
	public function order($order = 1)
	{
		$data = array();

		// ボタンに表示する文言をiniから取得する
		$data['order_btn'] = $this->app_ini['label_list']['order_btn'];
		// 現在のorder
		$data['current_order'] = $order;

		// 指定orderのレーベルリストを取得する
		$labels = $this->logiclabel->get_by_order($order - 1);
		// レーベルリストが異常であれば404
		if (!$labels)
		{
			show_404();
		}
		else
		{
			$data['labels'] = $labels;
		}

		$this->load->view('label_list', $data);
	}
}