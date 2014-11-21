<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * アバウトページコントローラ
 */
class About extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		// ロード
		$this->app_ini = parse_ini_file(APPPATH.'resource/ini/app.ini', true);
	}

	// サイトについてページ
	// public function aboutus()
	// {
	// 	$this->load->view('about/about');
	// }

	// よくある質問ページ
	public function faq()
	{
		$data = array();
		
		// 代理店IDとバナーIDを取得する
		$data['agent_id'] = $this->app_ini['common']['agent_id'];
		$data['banner_id'] = $this->app_ini['common']['banner_id'];

		$this->load->view('about/faq', $data);
	}

	// お問い合わせページ
	public function contact()
	{
		$this->load->view('about/contact');
	}
}