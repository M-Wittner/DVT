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
	public function testsRobot(){
		$this->db->where('station', 'Robot');
		$data = $this->db->get('params_test_names');
		$result = $data->result();
		echo json_encode($result);
	}
	public function modulesRobot(){
		$data = $this->db->get('params_modules_robot');
		$result = $data->result();
		echo json_encode($result);
	}
	public function stations(){
		$data = $this->db->get('params_stations');
		$result = $data->result();
		echo json_encode($result);
	}
	public function xifs(){
		$data = $this->db->get('params_xifs');
		$result = $data->result();
		echo json_encode($result);
	}
	public function iterations(){
		$this->db->order_by('station');
		$data = $this->db->get('params_test_iteration');
		$result = $data->result();
		echo json_encode($result);
	}
	public function autoUsers(){
		$this->db->select(['id', 'fname', 'lname', 'username']);
		$result = $this->db->get_where('users', ['group_id'=>7])->result_array();
		$data = new stdClass();
		$data->obj = $result;
		$data->arr = array_column($result, 'username');
		
		echo json_encode($data);
	}
	public function fields(){
		$result = $this->db->get('work_stations')->result_array();
		$data = new stdClass();
		$data->obj = $result;
		$data->arr = array_column($result, 'work_station');
		
		echo json_encode($data);
	}
	public function taskTypes(){
		$result = $this->db->get('task_types')->result_array();
		$data = new stdClass();
		$data->obj = $result;
		$data->arr = array_column($result, 'task_type');
		
		echo json_encode($data);
	}
	public function taskStatus(){
		$result = $this->db->get('task_status')->result_array();
		$data = new stdClass();
		$data->obj = $result;
		$data->arr = array_column($result, 'task_status');
		
		echo json_encode($data);
	}
	public function taskPriority(){
//		$this->db->select("task_priority As 'title'");
		$result = $this->db->get('task_priority')->result_array();
		$data = new stdClass();
		$data->obj = $result;
		$data->arr = array_column($result, 'task_priority');
		
		echo json_encode($data);
	}
}
?>
