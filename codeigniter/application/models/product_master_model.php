<?php

/**
 * 作品マスター情報モデル
 */
class Product_master_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->table_name = 'product_master';
	}

	/**
	 * レコード取得
	 */
	public function get()
	{
		// select
		$this->db->select('master_id, product_id, title, product_url, label_id, create_time');
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
	 * マスターIDによるレコード取得
	 */
	public function get_by_master_id($master_id)
	{
		// select
		$this->db->select('master_id, product_id, title, product_url, label_id, create_time');
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
	 * マスターIDによるレコード取得(範囲)
	 */
	public function get_by_master_id_range($from_master_id, $to_master_id)
	{
		// select
		$this->db->select('master_id, product_id, title, product_url, label_id, create_time');
		// where
		$this->db->where('master_id >=', $from_master_id);
		$this->db->where('master_id <=', $to_master_id);

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
	 * マスターIDによるレコード取得(IN指定)
	 */
	public function get_by_master_id_array($master_id_array)
	{
		// select
		$this->db->select('master_id, product_id, title, product_url, label_id, create_time');
		// where
		$this->db->where_in('master_id', $master_id_array);

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
	 * 作品IDによるレコード取得
	 */
	public function get_by_product_id($product_id)
	{
		// select
		$this->db->select('master_id');
		// where
		$this->db->where('product_id', $product_id);

		// クエリの実行
		$query = $this->db->get($this->table_name);
		// 該当するレコードがある場合は結果を配列で返す
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			return $row['master_id'];
		}
		else
		{
			return null;
		}
	}

	/**
	 * レーベルIDによるレコード取得
	 */
	public function get_by_label_id($label_id)
	{
		// select
		$this->db->select('master_id, product_id, title, product_url, label_id, create_time');
		// where
		$this->db->where('label_id', $label_id);
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
	 * 全レコード件数取得
	 */
	public function get_total_count()
	{
		// select
		$this->db->select('count(master_id)');

		// クエリの実行
		$query = $this->db->get($this->table_name);
		// 該当するレコードがある場合は結果を配列で返す
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			return $row['count(master_id)'];
		}
		else
		{
			return null;
		}
	}

	/**
	 * レコード取得(サイトマップ用)
	 */
	public function get_valid_master_id()
	{
		// select
		$this->db->select('master_id, update_time');
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