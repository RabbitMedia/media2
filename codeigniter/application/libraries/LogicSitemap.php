<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LogicSitemap
{
	protected $CI;

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('video_master_model');
	}

	/**
	 * サイトマップを生成する
	 */
	public function generate()
	{
		// サイトマップiniを取得する
		$sitemap_ini = parse_ini_file(APPPATH.'resource/ini/sitemap.ini');
		// サイトマップcsvを取得する
		$sitemap_csv = AppCsvLoader::load('sitemap.csv');
		// カテゴリーcsvを取得する
		$category_csv = AppCsvLoader::load('category.csv');
		// 有効な動画master_idと更新日時を取得する
		$master_ids = $this->CI->video_master_model->get_valid_master_id();

		// xml生成
		$xml = new DOMDocument('1.0', 'UTF-8');

		$xml->preserveWhiteSpace = false;
		$xml->formatOutput = true;

		$urlset = $xml->appendChild($xml->createElement("urlset"));
		$urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

		// url情報配列
		$urls = array();

		// トップページ
		$urls[] = array(
			'loc'        => $sitemap_ini['top_base_url'],
			'lastmod'    => date('Y-m-d'),
			'changefreq' => 'daily',
			'priority'   => $sitemap_ini['top_priority'],
			);

		// 動画ページ
		foreach ($master_ids as $key => $value)
		{
			$urls[] = array(
				'loc'      => str_replace('%master_id%', $value['master_id'], $sitemap_ini['video_base_url']),
				'lastmod'  => date('Y-m-d', strtotime($value['update_time'])),
				'priority' => $sitemap_ini['video_priority'],
				);
		}

		// カテゴリーページ
		foreach ($category_csv as $key => $value)
		{
			if ($value['display_flag'])
			{
				$urls[] = array(
					'loc'      => str_replace('%category_id%', $value['id'], $sitemap_ini['category_base_url']),
					'priority' => $sitemap_ini['category_priority'],
					);
			}
		}

		// 静的ページ
		foreach ($sitemap_csv as $key => $value)
		{
			if ($value['display_flag'])
			{
				$urls[] = array(
					'loc'      => $value['loc'],
					'priority' => $value['priority'],
					);
			}
		}

		// xml生成
		foreach ($urls as $params)
		{
			$url = $urlset->appendChild($xml->createElement('url'));

			foreach($params as $key => $value)
			{
				$url->appendChild($xml->createElement($key, $value));
			}
		}

		// 生成したサイトマップを保存する
		$xml->save('./sitemaps/sitemap.xml');
	}
}