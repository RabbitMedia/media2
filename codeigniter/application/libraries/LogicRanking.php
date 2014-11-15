<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LogicRanking
{
	protected $CI;

	function __construct()
	{
		$this->CI =& get_instance();

		// ロード
		$this->CI->load->model('product_master_model');
		$this->CI->load->model('ranking_control_model');
		$this->CI->load->model('ranking_model');
		$this->CI->load->Library('LogicVideoManage');
		$this->app_ini = parse_ini_file(APPPATH.'resource/ini/app.ini', true);
	}

	/**
	 * ランキングを取得する(最新)
	 */
	public function get()
	{
		// 作品配列
		$products = array();
		// ランキングデータ配列
		$ranking_datas = array();

		// 最新のランキングIDを取得する
		$ranking_id = $this->CI->ranking_control_model->get();
		// ランキングIDが異常であればfalseを返す
		if (!$ranking_id)
		{
			return false;
		}

		// ランキングIDを指定してランキングデータを取得する
		$ranking_datas = $this->CI->ranking_model->get_by_ranking_id($ranking_id);
		// ランキングデータが返ってこなければfalseを返す
		if (!$ranking_datas)
		{
			return false;
		}

		// マスターID配列を生成
		$master_id_array = array();
		foreach ($ranking_datas as $key => $value)
		{
			$master_id_array[] = $value['master_id'];
		}

		// 複数マスターIDを指定して作品を取得する	
		$products = $this->CI->logicvideomanage->get_by_array($master_id_array);

		// ランキングデータ順に作品を並び替える
		foreach ($ranking_datas as $r_key => $ranking_data)
		{
			foreach ($products as $p_key => $product)
			{
				if ($ranking_data['master_id'] == $product['master_id'])
				{
					$ranking_datas[$r_key] = $product;
					$ranking_datas[$r_key]['prev_rank'] = $ranking_data['prev_rank'];
					break;
				}
			}
		}

		return $ranking_datas;
	}
}