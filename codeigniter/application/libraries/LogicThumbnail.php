<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LogicThumbnail {

	protected $CI;

	const XVIDEOS_VIDEO_URL			=	'http://www.xvideos.com/video%video_id%/';	// XVIDEOSの動画URL
	const XVIDEOS_THUMBNAIL_NUM		=	30;											// XVIDEOSのサムネイル枚数

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('crawler_video_id_model');
	}

	/**
	 * 指定動画IDのサムネイルURLを取得する
	 */
	public function get($type, $video_id)
	{
		// サムネイル配列
		$thumbnails = array();

		if ($type == crawler_video_id_model::TYPE_XVIDEOS)
		{
			$thumbnails = $this->_get_from_xvideos($video_id);
		}

		return $thumbnails;
	}

	/**
	 * XVIDEOSから取得する
	 */
	private function _get_from_xvideos($video_id)
	{
		// サムネイル配列
		$thumbnails = array();

		// 指定動画IDのURL
		$url = str_replace('%video_id%', $video_id, self::XVIDEOS_VIDEO_URL);

		// 指定動画IDページを取得
		$html = file_get_contents($url);

		// サムネイルURLを正規表現で抽出
		if (preg_match('/(?<=bigthumb=).*?(?=\d{1,2}\.jpg)/', $html, $matches))
		{
			// 大きいサムネイルを小さいサムネイルに変更
			$match = str_replace('thumbslll', 'thumbs', $matches[0]);
		}
		else
		{
			return false;
		}

		// サムネイル配列にXVIDEOS_THUMBNAIL_NUM個数分のサムネイルURLを入れていく
		for ($i=1; $i <= self::XVIDEOS_THUMBNAIL_NUM; $i++)
		{ 
			$thumbnails[] = $match.$i.'.jpg';
		}

		return $thumbnails;
	}
}