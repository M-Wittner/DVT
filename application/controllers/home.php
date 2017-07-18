<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper(array('url', 'html'));
		$this->load->library('session');
	}
	function index() {
		$this->login();
	}
	function login() {
		$postData = json_decode(file_get_contents('php://input'));
		$userData = $postData->user;
		$user = array(
			'username'=>$userData->username,
			'password'=>$userData->password,
		);
		$data = $this->db->insert('users', $user);
		if($data) {
			echo "success";
		} else {
			echo "failure";
		}
	}
	
	function logout(){
		// destroy session
        $data = array('login' => '', 'username' => '', 'userid' => '');
        $this->session->unset_userdata($data);
        $this->session->sess_destroy();
		redirect('home/index');
	}
}
