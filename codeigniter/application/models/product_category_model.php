<?php

/**
 * 作品カテゴリーID情報モデル
 */
class Product_category_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->table_name = 'product_category';
	}

	/**
	 * マスターIDによるレコード取得
	 */
	public function get_by_master_id($master_id)
	{
		// select
		$this->db->select('category_id');
		// where
		$this->db->where('master_id', $master_id);

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
	 * カテゴリーIDによるレコード取得
	 */
	public function get_by_category_id($category_id)
	{
		// select
		$this->db->select('master_id');
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
	 * レコード挿入
	 */
	public function insert($data)
	{
		// 作成日時と更新日時をセットする
		$data['create_time'] = $data['update_time'] = date('Y-m-d H:i:s');

		// クエリの生成
		$insert_query = $this->db->insert_string($this->table_name, $data);

		// クエリの実行
		$this->db->query($insert_query);

		// 処理された行数を返す
		return $this->db->affected_rows();
	}

	/**
	 * レコード削除
	 */
	public function delete($master_id)
	{
		// set
		$this->db->set('delete_time', date("Y-m-d H:i:s"));
		// where
		$this->db->where('master_id', $master_id);

		// クエリの実行
		$this->db->update($this->table_name);

		// 処理された行数を返す
		return $this->db->affected_rows();
	}
}