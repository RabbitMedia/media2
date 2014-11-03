<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 管理画面ロジック
 */
class LogicDashboard
{
	protected $CI;

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('dashboard_model');
	}

	/**
	 * アカウントチェック
	 */
	public function check_account($username = null, $password = null)
	{
		// 有効フラグ
		$valid_flag = false;

		// 有効なアカウントを取得する
		$result = $this->CI->dashboard_model->get_valid_account($username, md5($password));

		if (!is_null($result))
		{
			$valid_flag = true;
		}

		return $valid_flag;
	}
}