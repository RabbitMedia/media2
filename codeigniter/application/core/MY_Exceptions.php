<?php

class MY_Exceptions extends CI_Exceptions
{
	/**
	 * 意図的にshow_404()するとapplication/errors配下のerror_404.phpを見に行ってしまうため
	 * オーバーライドしてapplication/views配下に統一するようにする
	 */
	public function show_404($page = '', $log_error = true)
	{
		$CI =& get_instance();

		if ($log_error)
		{
			log_message('error', '404 Page Not Found --> '.$page);
		}

		$data = array();

		// エラーページに何か渡すこともできる

		$CI->load->view('error/error_404', $data);
		echo $CI->output->get_output();
		exit;
	}
}