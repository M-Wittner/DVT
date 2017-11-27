<?php
header("Access-Control-Allow-Origin: *");
header("X-Frame-Options: GOFORIT");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Robot extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper(array('form','url'));
		$this->load->database('');
		$this->load->model('plan_model');
    }
	
	function create(){
		$postData = json_decode(file_get_contents('php://input'));
		
		echo json_encode($postData);
	}
}