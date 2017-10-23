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
	
	public function chipsM() {
		$this->db->where('chip', 'TalynM');
		$data = $this->db->get('params_chips');
		$result = $data->result();
		echo json_encode($result);
	}
	public function chipsR() {
		$this->db->where('chip', 'TalynA');
		$data = $this->db->get('params_chips');
		$result = $data->result();
		echo json_encode($result);
	}
	public function chipsMR() {
		$data = $this->db->get('params_chips');
		$result = $data->result();
		echo json_encode($result);
	}
	
	public function testsM(){
		$this->db->where('station', 'M');
		$data = $this->db->get('params_test_names');
		$result = $data->result();
		echo json_encode($result);
	}	
	public function testsR(){
		$this->db->where('station', 'R');
		$data = $this->db->get('params_test_names');
		$result = $data->result();
		echo json_encode($result);
	}
	public function testsCal(){
		$this->db->where('station', 'Calibration');
		$data = $this->db->get('params_test_names');
		$result = $data->result();
		echo json_encode($result);
	}
	public function testsPTAT(){
		$this->db->where('station', 'PTAT/ABS/Vgb+TEMP');
		$data = $this->db->get('params_test_names');
		$result = $data->result();
		echo json_encode($result);
	}
	public function testsFS(){
		$this->db->where('station', 'TalynM+A');
		$data = $this->db->get('params_test_names');
		$result = $data->result();
		echo json_encode($result);
	}
	public function stations(){
		$data = $this->db->get('params_stations');
		$result = $data->result();
		echo json_encode($result);
	}
}
?>
