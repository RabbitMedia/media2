<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LogicUserAgent
{
	protected $CI;

	function __construct()
	{
		$this->CI =& get_instance();
	}

	/**
	 * ユーザーエージェントを判定する
	 */
	public function get_is_mobile()
	{
		// ユーザーエージェントを取得する
		$user_agent = $this->CI->input->server('HTTP_USER_AGENT');

		// モバイル判定
		if ((strpos($user_agent, 'Android') !== false) && (strpos($user_agent, 'Mobile') !== false)
			|| (strpos($user_agent, 'iPhone') !== false) || (strpos($user_agent, 'iPod') !== false)
			|| (strpos($user_agent, 'Windows Phone') !== false))
		{
			$is_mobile = true;
		}
		else
		{
			$is_mobile = false;
		}

		return $is_mobile;
	}
}