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
	 * 作品情報を取得する
	 */
	public function get_products()
	{
		// デバッグ(ブラウザ表示文字化け回避)用
		// header("Content-Type: text/html; charset=UTF-8");

		// 実行開始時刻をログに出力する
		echo '開始: '.date("Y-m-d H:i:s")."\n";

		// 配列
		$products = array();

		// 作品情報を取得する
		$products = $this->logiccrawler->get_products();

		// 作品情報を取得できない場合は処理を終了する
		if (!$products)
		{
			// 実行終了時刻をログに出力する
			echo '終了: '.date("Y-m-d H:i:s")."\n";
			
			return;
		}

		// 作品情報を登録する
		$this->logiccrawler->set_products($products);

		// 実行終了時刻をログに出力する
		echo '終了: '.date("Y-m-d H:i:s")."\n";
	}

	/**
	 * ランキングを取得する
	 */
	public function get_ranking()
	{
		// デバッグ(ブラウザ表示文字化け回避)用
		// header("Content-Type: text/html; charset=UTF-8");

		// 実行開始時刻をログに出力する
		echo '開始: '.date("Y-m-d H:i:s")."\n";

		// 配列
		$products = array();

		// ランキングを取得する
		$products = $this->logiccrawler->get_ranking();

		// ランキングを取得できない場合は処理を終了する
		if (!$products)
		{
			// 実行終了時刻をログに出力する
			echo '終了: '.date("Y-m-d H:i:s")."\n";

			return;
		}

		// ランキングを登録する
		$this->logiccrawler->set_ranking($products);

		// 実行終了時刻をログに出力する
		echo '終了: '.date("Y-m-d H:i:s")."\n";
	}

	/**
	 * カテゴリー情報を取得する
	 */
	public function get_category()
	{
		// デバッグ(ブラウザ表示文字化け回避)用
		// header("Content-Type: text/html; charset=UTF-8");

		// 実行開始時刻をログに出力する
		echo '開始: '.date("Y-m-d H:i:s")."\n";

		// 配列
		$products = array();

		// カテゴリー情報を取得する
		$products = $this->logiccrawler->get_category();

		// カテゴリー情報を取得できない場合は処理を終了する
		if (!$products)
		{
			// 実行終了時刻をログに出力する
			echo '終了: '.date("Y-m-d H:i:s")."\n";

			return;
		}

		// カテゴリー情報を登録する
		$this->logiccrawler->set_category($products);

		// 実行終了時刻をログに出力する
		echo '終了: '.date("Y-m-d H:i:s")."\n";
	}

	/**
	 * 女優情報を取得する
	 */
	public function get_actress()
	{
		// デバッグ(ブラウザ表示文字化け回避)用
		// header("Content-Type: text/html; charset=UTF-8");

		// 実行開始時刻をログに出力する
		echo '開始: '.date("Y-m-d H:i:s")."\n";

		// 女優情報を取得してcsv出力する
		$this->logiccrawler->get_actress();

		// 実行終了時刻をログに出力する
		echo '終了: '.date("Y-m-d H:i:s")."\n";
	}
}