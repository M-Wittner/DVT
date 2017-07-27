<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Content-Length, X-Requested-With");
//header('Content-Type: application/json');
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Register extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form','url'));
        $this->load->library(array('session', 'form_validation', 'email'));
        $this->load->database('');
        $this->load->model('user_model');
    }
    
	public function index()
    {
		$this->register();
    }
    function register()
    {
		$postData = json_decode(file_get_contents('php://input'));
		$userData = $postData->user;
		$user = array(
			'username'=>$userData->username,
			'password'=>md5($userData->password),
			'email'=>$userData->email,
			'fname'=>$userData->fname,
			'lname'=>$userData->lname,
		);
//		die(print_r($userData));
//		$cpassword =md5($userData->cpassword);
		$this->form_validation->set_data($user);
		$this->form_validation->set_rules('username', 'Userame', 'trim|required|alpha');
		$this->form_validation->set_rules('fname', 'First Name', 'trim|required|alpha|min_length[3]|max_length[30]');
		$this->form_validation->set_rules('lname', 'Last Name', 'trim|required|alpha');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|md5');
//		$this->form_validation->set_rules($cpassword, 'Confirm Password', 'trim|required|matches[password]|md5');
		die(print_r($user));
		if($this->form_validation->run() == FALSE) {
			// Failed
			echo "form Invalid";
//			die(print_r($user));
		} else {
			// Succeed
			$data = $this->user_model->insert_user($user);
//			$insertId = $this->user_model->get_id($data);
			if($data) {
				echo "success    ";
//				print_r("</br>".$data);
//				print_r($insertId);
			} else {
				echo "failure";
			}
		}

    }
}
?>
