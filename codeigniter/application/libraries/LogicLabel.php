<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LogicLabel
{
	protected $CI;

	function __construct()
	{
		$this->CI =& get_instance();

		// ロード
		$this->CI->load->model('label_list_model');
		$this->app_ini = parse_ini_file(APPPATH.'resource/ini/app.ini', true);
	}

	/**
	 * 指定orderのレーベルリストを取得する
	 */
	public function get_by_order($order)
	{
		// orderが存在しない値であればfalseを返す
		if ($order < 0 || $order >= count($this->app_ini['label_list']['order']))
		{
			return false;
		}

		// レーベル配列
		$labels = array();

		// orderをiniから取得する
		$order = $this->app_ini['label_list']['order'][$order];

		// 指定orderのcsvを配列で取得する
		$label_csv_array = AppCsvLoader::load('label/'.$order.'.csv');

		foreach ($label_csv_array as $key => $label_csv)
		{
			// レーベル名を指定してlabel_listにレーベルIDが存在するか確認する
			$label_id = $this->CI->label_list_model->get_by_label_name($label_csv['name']);
			// レーベルIDが存在しない場合は対象外とする
			if (!$label_id)
			{
				continue;
			}

			// レーベルIDとレーベル名を配列に入れる
			$labels[] = array(
				'id'		=> $label_id,
				'name'		=> $label_csv['name'],
				'banner'	=> str_replace('%LABEL_PATH%', $label_csv['path'], $this->app_ini['url']['label_banner']),
				);
		}

		return $labels;
	}

	/**
	 * レーベル名を取得する
	 */
	public function get_label_name($label_id)
	{
		// レーベルIDを指定してレーベル名を取得する
		$label_name = $this->CI->label_list_model->get_by_label_id($label_id);

		return $label_name;
	}
}