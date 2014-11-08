<?php

/**
 * ランキングモデル
 */
class Ranking_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->table_name = 'ranking';
	}

	/**
	 * ランキングIDによるレコード取得
	 */
	public function get_by_ranking_id($ranking_id)
	{
		// select
		$this->db->select('product_id, prev_rank');
		// where
		$this->db->where('ranking_id', $ranking_id);

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
	 * レコード挿入
	 */
	public function insert($data)
	{
		// 作成日時と更新日時をセットする
		$data['create_time'] = $data['update_time'] = date('Y-m-d H:i:s');

		// クエリの実行
		$this->db->insert($this->table_name, $data);

		// 挿入したID番号を返す
		return $this->db->insert_id();
	}

	/**
	 * レコード削除
	 */
	public function delete($ranking_id)
	{
		// set
		$this->db->set('delete_time', date("Y-m-d H:i:s"));
		// where
		$this->db->where('ranking_id', $ranking_id);

		// クエリの実行
		$this->db->update($this->table_name);

		// 処理された行数を返す
		return $this->db->affected_rows();
	}
}