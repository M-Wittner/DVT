<?php
header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header('Content-Type: application/json');
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
    function register(){
			$request = json_decode(file_get_contents('php://input'));
			$user = array(
				'username'=>$request->username,
				'password'=>md5($request->password),
				'email'=>$request->email,
				'fname'=>$request->fname,
				'lname'=>$request->lname
			);
			$userExist = $this->db->get_where('users', ['username'=>$request->username])->result()[0];
			if(isset($userExist)){
				echo 'This username already exists!';
				die();
			}

			$data = $this->user_model->insertUser($user);
			if($data) {
				echo "OK";
			} else {
				echo "failure";
			}
    }
    
    function verify($hash=NULL)
    {
        if ($this->user_model->verifyEmailID($hash))
        {
            $this->session->set_flashdata('verify_msg','<div class="alert alert-success text-center">Your Email Address is successfully verified! Please login to access your account!</div>');
            redirect('register/register');
        }
        else
        {
            $this->session->set_flashdata('verify_msg','<div class="alert alert-danger text-center">Sorry! There is error verifying your Email Address!</div>');
            redirect('register/register');
        }
    }
}
?>
