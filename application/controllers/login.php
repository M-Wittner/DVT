<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
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
		var_dump($postData);
		$uresult = $this->user_model->get_user($user);
			if(count($uresult) > 0) {
				//set sesison
				$sessData = array(
					'login' => TRUE,
					'userName' => $uresult[0]->username,
					'userId' 	=> $uresult[0]->id
				);
				echo 'blaaa';
				die(var_dump($uresult));
				echo json_encode($sessData);
			} else {
				echo 'failure!!';
			}
	}
}
