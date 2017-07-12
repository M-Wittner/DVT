<?php
header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
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
//		die(var_dump($_POST));
//		die(var_dump(json_decode(file_get_contents('php://input'))));
//		$request = json_decode(file_get_contents('php://input'));
//		die(var_dump($request->'username');
//		$user = array(
//			'username'=>$request->username,
//			'fname'=>$request->fname,
//			'lname'=>$request->lname,
//			'email'=>$request->email,
//			'password'=>$request->password
//		);
        
//		$data = $this->user_model->insertUser($user);
//		if($data) {
//			echo "success";
//			echo $data;
//		} else {
//			echo "failure";
//		}
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
