<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Content-Length, X-Requested-With");
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chips extends CI_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->helper(array('form','url'));
        $this->load->library(array('session', 'form_validation', 'email'));
        $this->load->database('');
    }
	
	public function all() {
		$data = $this->db->get('chips');
		$result = $data->result();
		echo json_encode($result);
	}
	
	public function create(){
//		$postData = json_decode(file_get_contents('php://input'));
//		$chipData = $postData->chip;
//		$chip = array(
//			'serial_num'=>$chipData->chipSN
//		);
//		die(print_r($chip));
//		$data = $this->db->insert('chips', $chip);
//		if ($data) {
//			die(var_dump($chip));
//			echo 'success';
//		} else {
//			die(var_dump($chip));
//		}
	}
}
?>
