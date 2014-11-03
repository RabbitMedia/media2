<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * サイトマップコントローラー
 */
class Sitemap extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		// 直アクセス禁止
		if (!$this->input->is_cli_request())
		{
			exit;
		}

		$this->load->library('LogicSitemap');
	}

	/**
	 * サイトマップを生成する
	 */
	public function generate()
	{
		// 実行開始時刻をログに出力する
		echo '開始: '.date("Y-m-d H:i:s")."\n";

		// サイトマップ生成
		$this->logicsitemap->generate();

		// 実行終了時刻をログに出力する
		echo '終了: '.date("Y-m-d H:i:s")."\n";
	}
}