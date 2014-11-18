<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LogicActress
{
	protected $CI;

	function __construct()
	{
		$this->CI =& get_instance();

		// ロード
		$this->CI->load->model('actress_list_model');
		$this->app_ini = parse_ini_file(APPPATH.'resource/ini/app.ini', true);
	}

	/**
	 * 指定order_groupの女優リストを取得する
	 */
	public function get_by_order($order_group)
	{
		// order_groupが存在しない値であればfalseを返す
		if ($order_group < 0 || $order_group >= count($this->app_ini['actress_list']['order_group']))
		{
			return false;
		}

		// 女優配列
		$actresses = array();

		// order_groupからorderを配列で取得する
		$orders = $this->app_ini['actress_list']['order_group'][$order_group];
		$orders = explode(',', $orders);

		foreach ($orders as $key => $order)
		{
			// ファイルが存在しない場合は次のorderに進む
			if (!file_exists(APPPATH.'resource/csv/actress/'.$order.'.csv'))
			{
				continue;
			}

			// 指定orderのcsvを配列で取得する
			$actress_csv_array = AppCsvLoader::load('actress/'.$order.'.csv');

			foreach ($actress_csv_array as $key => $actress_csv)
			{
				// 女優名を指定してactress_listに女優IDが存在するか確認する
				$actress_id = $this->CI->actress_list_model->get_by_actress_name($actress_csv['name']);
				// 女優IDが存在しない場合は対象外とする
				if (!$actress_id)
				{
					continue;
				}

				// 女優IDと女優名を配列に入れる
				$actresses[] = array(
					'id'	=> $actress_id,
					'name'	=> $actress_csv['name'],
					);
			}
		}

		return $actresses;
	}

	/**
	 * 女優名を取得する
	 */
	public function get_actress_name($actress_id)
	{
		// 女優IDを指定して女優名を取得する
		$actress_name = $this->CI->actress_list_model->get_by_actress_id($actress_id);

		return $actress_name;
	}
}