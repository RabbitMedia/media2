<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * クローラーコントローラー
 */
class Crawler extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		// 直アクセス禁止
		if (!$this->input->is_cli_request())
		{
			exit;
		}

		$this->load->library('LogicCrawler');
	}

	/**
	 * 動画コンテンツを取得する
	 */
	public function get_videos()
	{
		// 実行開始時刻をログに出力する
		echo '開始: '.date("Y-m-d H:i:s")."\n";

		// 配列
		$contents = array();

		// 指定サイトから動画情報を取得する
		$contents[] = $this->logiccrawler->get_from_tengoku();
		$contents[] = $this->logiccrawler->get_from_nukist();

		// クローラーが集めてきた動画を登録する
		$this->logiccrawler->set_crawled_videos($contents);

		// 実行終了時刻をログに出力する
		echo '終了: '.date("Y-m-d H:i:s")."\n";
	}
}