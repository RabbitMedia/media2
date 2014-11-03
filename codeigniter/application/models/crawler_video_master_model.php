<?php

/**
 * クローラー動画マスターモデル
 */
class Crawler_video_master_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->table_name = 'crawler_video_master';
	}

	/**
	 * レコード取得
	 */
	public function get()
	{
		// select
		$this->db->select('crawler_master_id, duration, create_time');
		// where
		$this->db->where('delete_time', null);

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
	 * 次のクローラー動画マスターID取得
	 */
	public function get_next_crawler_master_id()
	{
		// select max
		$this->db->select_max('crawler_master_id');

		// クエリの実行
		$query = $this->db->get($this->table_name);
		// 該当するレコードがある場合は結果を返す
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			return (int)$row['crawler_master_id'] + 1;
		}
		// 該当レコードがない場合は最初のレコード
		else
		{
			return 1;
		}
	}

	/**
	 * レコード挿入
	 */
	public function insert($data)
	{
		// 作成日時と更新日時をセットする
		$data['create_time'] = $data['update_time'] = date('Y-m-d H:i:s');

		$insert_query = $this->db->insert_string($this->table_name, $data);

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