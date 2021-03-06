<?php

/**
 * レーベルリストモデル
 */
class Label_list_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->table_name = 'label_list';
	}

	/**
	 * レーベルIDによるレコード取得
	 */
	public function get_by_label_id($label_id)
	{
		// select
		$this->db->select('label_name');
		// where
		$this->db->where('label_id', $label_id);

		// クエリの実行
		$query = $this->db->get($this->table_name);
		// 該当するレコードがある場合は結果を配列で返す
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			return $row['label_name'];
		}
		else
		{
			return null;
		}
	}

	/**
	 * レーベル名によるレコード取得
	 */
	public function get_by_label_name($label_name)
	{
		// select
		$this->db->select('label_id');
		// where
		$this->db->where('label_name', $label_name);

		// クエリの実行
		$query = $this->db->get($this->table_name);
		// 該当するレコードがある場合は結果を返す
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			return $row['label_id'];
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
	public function delete($label_id)
	{
		// set
		$this->db->set('delete_time', date("Y-m-d H:i:s"));
		// where
		$this->db->where('label_id', $label_id);

		// クエリの実行
		$this->db->update($this->table_name);

		// 処理された行数を返す
		return $this->db->affected_rows();
	}
}