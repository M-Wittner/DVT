<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Content-Length, X-Requested-With");
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {
	public function __construct() {

	parent::__construct();
	$this->load->helper(array('form','url'));
	$this->load->library(array('session', 'form_validation', 'email'));
	$this->load->database('');
	$this->load->model('user_model');
    }
	function index() {
		$this->login();
	}
	
	function login() {
		$postData = json_decode(file_get_contents('php://input'));
		$userData = $postData->user;
		$user = array(
			'username'=>$userData->username,
			'password'=>md5($userData->password),
		);
		$this->form_validation->set_data($user);
		$this->form_validation->set_rules('username', 'Userame', 'trim|required|alpha|min_length[4]|max_length[10]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|md5');
		
		if($this->form_validation->run() == FALSE) {
			//validation fail
			echo "faild to login";
			
		} else {
			// check for user credentials(authenticate)
			$uresult = $this->user_model->get_user($user[0], $user[1]);
			if(count($uresult) > 0) {
				//set sesison
				$sessData = array(
					'login' => TRUE,
					'uname' => $uresult[0]->username,
					'uid' 	=> $uresult[0]->id
				);
				$this->session->set_userdata($sessData);
				redirect('#/reports');
				echo "suclog";
			} else {
				echo 'failure!!';
			}
		}
	}
}
