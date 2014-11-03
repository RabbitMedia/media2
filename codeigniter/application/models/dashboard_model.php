<?php

/**
 * 管理画面モデル
 */
class Dashboard_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->table_name = 'dashboard';
	}

	/**
	 * 有効なアカウントを取得する
	 */
	public function get_valid_account($username = null, $password = null)
	{
		// select
		$this->db->select('id');
		// where
		$this->db->where('username', $username);
		$this->db->where('password', $password);
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
}