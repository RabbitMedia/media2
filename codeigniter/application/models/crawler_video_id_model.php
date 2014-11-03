<?php

/**
 * クローラー動画IDモデル
 */
class Crawler_video_id_model extends CI_Model
{
	const TYPE_XVIDEOS = 1;

	function __construct()
	{
		parent::__construct();
		$this->table_name = 'crawler_video_id';
	}

	/**
	 * レコード取得
	 */
	public function get($crawler_master_id)
	{
		// select
		$this->db->select('type, video_url_id');
		// where
		$this->db->where('crawler_master_id', $crawler_master_id);

		// クエリの実行
		$query = $this->db->get($this->table_name);
		// 該当するレコードがある場合は結果を配列で返す
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return null;
		}
	}

	/**
	 * 動画タイプと動画URLIDによるレコード取得
	 */
	public function get_by_url($type, $video_url_id)
	{
		// select
		$this->db->select('crawler_master_id');
		// where
		$this->db->where('type', $type);
		$this->db->where('video_url_id', $video_url_id);
		$this->db->where('delete_time', null);

		// クエリの実行
		$query = $this->db->get($this->table_name);
		// 該当するレコードがある場合は結果を配列で返す
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			return $row['crawler_master_id'];
		}
		else
		{
			return null;
		}
	}

	/**
	 * レコード挿入
	 */
	public function insert($data)
	{
		// 作成日時と更新日時をセットする
		$data['create_time'] = $data['update_time'] = date('Y-m-d H:i:s');

		// INSERT IGNORE クエリの生成
		$insert_query = $this->db->insert_string($this->table_name, $data);
		$insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);

		// クエリの実行
		$this->db->query($insert_query);

		return $this->db->affected_rows();
	}

	/**
	 * レコード削除
	 */
	public function delete($crawler_master_id)
	{
		// set
		$this->db->set('delete_time', date("Y-m-d H:i:s"));
		// where
		$this->db->where('crawler_master_id', $crawler_master_id);

		// クエリの実行
		$this->db->update($this->table_name);

		// 処理された行数を返す
		return $this->db->affected_rows();
	}
}