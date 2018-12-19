<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper(array('url', 'html'));
		$this->load->library('session');
	}
	function index() {
		redirect('/index.htm');
	}
	function login() {
		$data = json_decode(file_get_contents('php://input'));
//		echo json_encode($data);
		$this->db->select('id, username, email, fname, lname, rank, group_id');
		$user = $this->db->get_where('users', ['username'=>$data->username, 'password'=>md5($data->password)])->result();

		if($user && count($user) == 1){
			$user = $user[0];
			echo json_encode($user);
		}else{
			echo json_encode(false);
		}
	}
	
	function currentUser(){
		$data = json_decode(file_get_contents('php://input'));
		echo json_encode($data);
	}
	
	function logout(){
		// destroy session
        $data = array('login' => '', 'username' => '', 'userid' => '');
        $this->session->unset_userdata($data);
        $this->session->sess_destroy();
		redirect('home/index');
	}
}
