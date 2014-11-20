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
		$this->load->Library('LogicRanking');
		$this->load->Library('LogicVideoManage');
		$this->app_ini = parse_ini_file(APPPATH.'resource/ini/app.ini', true);
	}

	/**
	 * トップページ
	 */
	public function index()
	{
		$data = array();

		// 全作品数を取得する
		$data['total_count'] = $total_count = $this->logicvideomanage->get_total_count();
		// 取得すべきマスターIDの範囲
		$from_master_id = $total_count - $this->app_ini['top']['latest_disp_num'] + 1;
		$to_master_id = $from_master_id + $this->app_ini['top']['latest_disp_num'] - 1;
		//新着作品を取得する
		$data['latest_products'] = $this->logicvideomanage->get_by_range($from_master_id, $to_master_id);

		// ランキングを取得する(最新)
		$ranking_products = $this->logicranking->get();
		// 何位まで表示するかiniから取得して作品を取得する
		$data['ranking_products'] = array_slice($ranking_products, 0, $this->app_ini['top']['rank_disp_num']);

		// ピックアップを取得する
		$data['pickup_products'] = $this->logicvideomanage->get_pickup();

		$this->load->view('top', $data);
	}
}