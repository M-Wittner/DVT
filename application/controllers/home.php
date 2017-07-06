<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper(array('url', 'html'));
		$this->load->library('session');
	}
	function index() {
		$this->load->view('home_view');
	}
	function logout(){
		// destroy session
        $data = array('login' => '', 'username' => '', 'userid' => '');
        $this->session->unset_userdata($data);
        $this->session->sess_destroy();
		redirect('home/index');
	}
}
