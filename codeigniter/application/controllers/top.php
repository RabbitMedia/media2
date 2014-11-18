<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * トップページコントローラ
 */
class Top extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		// ロード
		$this->load->Library('LogicVideoManage');
		$this->load->Library('pagination');
	}

	/**
	 * トップページ
	 */
	public function index()
	{
		$data = array();

		// 全作品を取得する(新着順)
		$products = $this->logicvideomanage->get();

		$data['products'] = $products;

		// 動画総数
		$data['total_count'] = count($products);

		// 該当ページに表示する動画を取得する(mysqlのlimitとphpのarray_sliceではどっちが速いかは未検証)
		// $data['products'] = array_slice($products, (($page - 1) * $config['per_page']), $config['per_page']);
		// 動画が存在しない場合は404
		if (!$data['products'])
		{
			show_404();
		}

		$this->load->view('top', $data);
	}
}