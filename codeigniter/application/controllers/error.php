<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * エラーページコントローラ
 */
class Error extends CI_Controller
{
	// 404
	public function error_404()
	{
		$data = array();

		// HTTPステータスコードをセットする
		$this->output->set_status_header('404');

		// エラーページに何か渡すこともできる

		$this->load->view('error/error_404', $data);
	}
}