<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Content-Length, X-Requested-With");
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Params extends CI_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->helper(array('form','url'));
        $this->load->library(array('session', 'form_validation', 'email'));
        $this->load->database('');
    }
	
	public function chips() {
		$data = $this->db->get('params_chips');
		$result = $data->result();
		echo json_encode($result);
	}
	
	public function tests(){
		$data = $this->db->get('params_test_names');
		$result = $data->result();
		echo json_encode($result);
	}
}
?>
