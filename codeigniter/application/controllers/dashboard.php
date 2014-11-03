<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 管理画面コントローラ
 */
class Dashboard extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('pagination');
		$this->load->library('LogicDashboard');
		$this->load->library('LogicCrawler');
		$this->load->library('LogicThumbnail');
		$this->load->library('LogicEmbed');
		$this->load->library('LogicVideoManage');
		$this->load->helper('url');
		$this->load->helper('form');

		// ログインチェック
		if (($this->uri->uri_string() != 'dashboard/login') && !$this->session->userdata('is_login'))
		{
			redirect('dashboard/login');
		}
	}

	/**
	 * ログイン
	 */
	public function login()
	{
		$data = array();

		// ログインしている状態
		if ($this->session->userdata('is_login') == true)
		{
			redirect('dashboard/index');
		}

		// ログインエラーフラグ
		$data['error_flag'] = false;

		// POSTデータ
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		// バリデーションルール
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		// バリデーションチェック
		if ($this->form_validation->run() == true)
		{
			// アカウントチェック
			$valid_flag = $this->logicdashboard->check_account($username, $password);
			// アカウントが有効であればログイン
			if ($valid_flag)
			{
				$this->session->sess_destroy();
				$this->session->sess_create();
				$this->session->set_userdata(array('is_login' => true));
				$this->session->set_userdata(array('username' => $username));
				redirect('dashboard/index');
			}
			else
			{
				// ログインエラーフラグ
				$data['error_flag'] = true;
			}
		}

		// 最初のアクセスもしくはバリデーションエラーもしくはログインエラー
		$data['username'] = $username;
		$data['password'] = $password;

		$this->load->view('dashboard/login', $data);
	}

	/**
	 * ログアウト
	 */
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('dashboard/login');
	}

	/**
	 * 管理画面トップ
	 */
	public function index()
	{
		$data = array();

		// クローラーが集めてきた動画を取得する
		$videos = $this->logiccrawler->get_crawled_videos();
		// アップ待ち動画数
		$data['total_count'] = count($videos);

		$this->load->view('dashboard/index', $data);
	}

	/**
	 * 管理画面アップ待ち動画
	 */
	public function crawled_videos($page = 1)
	{
		$data = array();

		// クローラーが集めてきた動画を取得する
		$videos = $this->logiccrawler->get_crawled_videos();
		// アップ待ち動画数
		$data['total_count'] = count($videos);

		// 動画がなければ何もしない
		if ($data['total_count'] == 0)
		{
			$this->load->view('dashboard/crawled_videos_empty', $data);
			return;
		}

		// ページ数が動画数より多ければ調整する
		$page = ($page > $data['total_count']) ? $data['total_count'] : $page;
		$data['current_page'] = $page;

		// ページネーション
		$config['base_url'] = '/dashboard/crawled_videos/';
		$config['total_rows'] = $data['total_count'];
		$config['per_page'] = 1;
		$config['use_page_numbers'] = true;
		$config['uri_segment'] = 3;
		$config['num_links'] = 2;
		$config['first_link'] = false;
		$config['last_link'] = false;
		$config['full_tag_open'] = '';
		$config['full_tag_close'] = '';
		$config['prev_link'] = '前へ';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '次へ';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		// 該当ページに表示する動画を取得する(mysqlのlimitとphpのarray_sliceではどっちが速いかは未検証)
		$data['videos'] = array_slice($videos, (($page - 1) * $config['per_page']), $config['per_page']);

		foreach ($data['videos'] as $id => $video)
		{
			foreach ($video['type'] as $key => $type)
			{
				// サムネイルと埋め込みタグを取得する
				$data['videos'][$id]['thumbnail'][] = $this->logicthumbnail->get($type, $video['video_url_id'][$key]);
				$data['videos'][$id]['embed_tag'][] = $this->logicembed->get($type, $video['video_url_id'][$key]);
			}

			// クロール後の経過時間を取得する
			$data['videos'][$id]['create_time'] = date("Y年m月d日 H時i分s秒", strtotime($video['create_time']));
		}

		// カテゴリー
		$category_csv = AppCsvLoader::load('category.csv');
		foreach ($category_csv as $key => $value)
		{
			$category['name'] = $value['name'];
			$category['id'] = $value['id'];
			$categories[] = $category;
		}
		$data['categories'] = $categories;

		$this->load->view('dashboard/crawled_videos', $data);
	}

	/**
	 * 動画アップ
	 */
	public function upload()
	{
		// POSTデータ
		$item['crawler_master_id'] = $this->input->post('crawler_master_id');
		$item['title'] = $this->input->post('title');
		$item['thumbnail'] = $this->input->post('thumbnail');
		$item['duration'] = $this->input->post('duration');
		$item['type'] = $this->input->post('type');
		$item['video_url_id'] = $this->input->post('video_url_id');
		$item['main_category'] = $this->input->post('main_category');
		$item['sub_category'] = $this->input->post('sub_category');
		$item['current_page'] = $this->input->post('current_page');

		// POSTデータチェック
		if (empty($item['crawler_master_id']) || empty($item['title']) || empty($item['thumbnail']) || empty($item['duration'])
			|| empty($item['type']) || empty($item['video_url_id']) || empty($item['main_category'])
			|| empty($item['sub_category']) || empty($item['current_page']))
		{
			show_error('Invalid Post Data');
		}

		// 動画をアップする
		$result = $this->logicvideomanage->upload($item);

		// 返り値チェック
		if (empty($result))
		{
			show_error('Upload Failed');
		}

		// 管理画面アップ待ち動画へ戻る
		redirect('dashboard/crawled_videos/'.$item['current_page']);
	}

	/**
	 * 動画削除(クロールド動画)
	 */
	public function delete_crawled_videos()
	{
		// POSTデータ
		$item['crawler_master_id'] = $this->input->post('crawler_master_id');
		$item['current_page'] = $this->input->post('current_page');

		// POSTデータチェック
		if (empty($item['crawler_master_id']) || empty($item['current_page']))
		{
			show_error('Invalid Post Data');
		}

		// 動画を削除する(クロールド動画)
		$result = $this->logicvideomanage->delete_crawled_videos($item['crawler_master_id'], true);

		// 返り値チェック
		if (empty($result))
		{
			show_error('Delete Failed');
		}

		// 管理画面アップ待ち動画へ戻る
		redirect('dashboard/crawled_videos/'.$item['current_page']);
	}
}