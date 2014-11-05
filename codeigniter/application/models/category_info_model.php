<?php

/**
 * カテゴリー情報モデル
 */
class Category_info_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->table_name = 'category_info';
	}

	/**
	 * カテゴリーIDによるレコード取得
	 */
	public function get_by_category_id($category_id)
	{
		// select
		$this->db->select('name');
		// where
		$this->db->where('category_id', $category_id);

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
	 * カテゴリー名によるレコード取得
	 */
	public function get_by_name($name)
	{
		// select
		$this->db->select('category_id');
		// where
		$this->db->where('name', $name);

		// クエリの実行
		$query = $this->db->get($this->table_name);
		// 該当するレコードがある場合は結果を返す
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			return $row['category_id'];
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
	public function delete($category_id)
	{
		// set
		$this->db->set('delete_time', date("Y-m-d H:i:s"));
		// where
		$this->db->where('category_id', $category_id);

		// クエリの実行
		$this->db->update($this->table_name);

		// 処理された行数を返す
		return $this->db->affected_rows();
	}
}