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
	
	public function structs(){
		$data = $this->db->get('test_struct_view')->result();
		echo json_encode($data);
	}
	
	public function workStations(){
		$data = $this->db->query('SELECT * FROM dvt_60g.work_stations')->result();
		echo json_encode($data);
	}
	
	public function testTypes(){
		$data = $this->db->query('SELECT * FROM dvt_60g.test_types where `visible` = 1')->result();
		echo json_encode($data);
	}
	
	public function allChips(){
		$data = $this->db->query('Select * from dvt_60g.chips where chip_type_id > 0 order by chip_id ASC')->result();
		echo json_encode($data);
	}
	
	public function allParams(){
		$data = $this->db->get('test_params_new')->result();
		echo json_encode($data);
	}
	
	public function chipsM() {
		$this->db->where_in('chip_type_id', [2, 4]);
		$data = $this->db->get('chip_view');
		$result = $data->result();
		echo json_encode($result);
	}
	public function chipsR() {
		$this->db->where_in('chip_type_id', [1,3]);
		$data = $this->db->get('chip_view');
		$result = $data->result();
		echo json_encode($result);
	}
	public function chipsMR() {
		$this->db->where_in('chip_type_id', [1, 2, 3, 4]);
		$data = $this->db->get('chip_view');
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
	
	public function lineupParams(){
		$data = new stdClass();
		
		$this->db->where_in('id', [1,2,3]);
		$data->stations = $this->db->get('work_stations')->result_array();
		
		$this->db->not_like('lineup_type','RX');
		$this->db->not_like('lineup_type','General');		
		$data->types = $this->db->get('lineup_types')->result_array();
		
		$data->chipTypes = $this->db->get('chip_types')->result_array();
		
		$this->db->where(['lineup_type'=>'1', 'parameter_type'=>'3']);
		$data->aLoParams = $this->db->get('lineup_params')->result_array();
		
		$this->db->where('lineup_type', '1');
		$this->db->where_in('parameter_type', [1,4]);
		$data->aTxParams = $this->db->get('lineup_params')->result_array();
		
		$this->db->where('lineup_type', '1');
		$this->db->where_in('parameter_type', [2,4]);
		$data->aRxParams = $this->db->get('lineup_params')->result_array();
		
//		$xifs = ["XIF_0","XIF_1","XIF_2","XIF_3","XIF_4","XIF_5","XIF_6","XIF_7"];
		$this->db->where(['lineup_type'=>'8', 'chip_type'=>'2', 'parameter_name !='=>'MCS']);
//		$this->db->where_not_in('parameter_name', ['MCS']);
		$data->mGeneralParams = $this->db->get('lineup_gen_params')->result_array();
		
		$this->db->where(['chip_type'=>'2', 'lineup_type'=>'1']);
		$data->mTxParams = $this->db->get('lineup_gen_params')->result_array();
		
		$this->db->where(['chip_type'=>'2', 'lineup_type'=>'1']);
		$data->mTxFwParams = $this->db->get('lineup_gen_params')->result_array();
		
		$this->db->where(['lineup_type'=>'2', 'parameter_type'=>'2']);
		$data->mRxParams = $this->db->get('lineup_params')->result_array();
		
		$data->directions = [['id'=>1, 'direction'=>'TX'],['id'=>2, 'direction'=>'RX']];
		
		echo json_encode($data);
	}
}
?>
