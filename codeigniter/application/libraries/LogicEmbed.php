<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LogicEmbed
{
	protected $CI;

	const XVIDEOS_VIDEO_EMBED = '<iframe src="http://flashservice.xvideos.com/embedframe/%video_id%" frameborder=0 width=0 height=0 scrolling=no></iframe>';	// XVIDEOSの埋め込みタグ

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('crawler_video_id_model');
	}

	/**
	 * 指定動画IDの埋め込みタグを取得する
	 */
	public function get($type, $video_id)
	{
		// 埋め込みタグ
		$embed_tag = null;

		if ($type == crawler_video_id_model::TYPE_XVIDEOS)
		{
			$embed_tag = str_replace('%video_id%', $video_id, self::XVIDEOS_VIDEO_EMBED);
		}

		return $embed_tag;
	}
}