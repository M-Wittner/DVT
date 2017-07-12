<?php

class Login extends CI_Controller {
	public function __construct() {

	parent::__construct();
	$this->load->helper(array('form','url'));
	$this->load->library(array('session', 'form_validation', 'email'));
	$this->load->database('');
	$this->load->model('user_model');
    }
	function index() {
		$request = json_decode(file_get_contents('php://input'), TRUE);
		echo $request;
		//form validation
		$this->form_validation->set_rules('username', 'username', 'trim|required');
		$this->form_validation->set_rules('password', 'password', 'trim|required');

		if($this->form_validation->run() == false) {
			//validation fail
			
		} else {
			// check for user credentials(authenticate)
			$uresult = $this->user_model->get_user($username, $password);
			if(count($uresult) > 0) {
				//set sesison
				$sessData = array(
					'login' => TRUE,
					'uname' => $uresult[0]->username,
					'uid' 	=> $uresult[0]->id
				);
				$this->session->set_userdata($sessData);
				redirect('reports/index');
			} else {
				$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Wrong Username or Password!</div>');
				redirect('login/index');
			}
		}

	}
}
