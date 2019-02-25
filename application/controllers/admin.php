<?php
header("X-Frame-Options: SOMEORIGIN");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Admin extends CI_Controller {
	
	public function __construct() {
			parent::__construct();
			$this->load->helper(array('form','url','download', 'file'));
			$this->load->library('Filter');
			$this->load->database('default');
			$this->load->model('excel_model');
    }
	
	public function addChip() {
		$chip = json_decode(file_get_contents('php://input'));
		$result = array();
		$error = new stdClass();
		if(count((array)$chip) == 7){
			foreach($chip as $key=>$value){
				if(is_array($value)){
					$chip->$key = $value[0];
				}elseif(isset($value->ext)){
					$chip->$key = $value->ext;
				}
				if(empty($chip->$key)){
					$error->msg = $key.' is missing!';
					$error->source = $chip;
					$error->occurred = true;
					array_push($result, $error);
				}
			}
		}else{
			$error->msg = 'Some of the fields are missing!';
			$error->source = $chip;
			$error->occurred = true;
			array_push($result, $error);
		}
		if(empty($result)){
			$this->other_db = $this->load->database('main', TRUE);

			$dupChip = $this->other_db->get_where('chips', ['chip_sn'=>$chip->chip_sn])->result();
			if(count($dupChip) > 0){
				$error->msg = 'There is already a chip with that Serial Number ('.$chip->chip_sn.')';
				$error->source = $dupChip;
				$error->occurred = true;
				array_push($result, $error);
			}else{ // ----------------- No Duplicate Chips! ----------------- //
				$this->other_db->select('chip_type_id');
				$id = $this->other_db->get_where('chip_types', ['chip_type_name'=>$chip->chip_type])->result()[0]->chip_type_id;
				$chip->chip_type_id = $id;
				unset($chip->chip_type);
				$insertStatus = $this->other_db->insert('chips', $chip);
		//		$insertStatus = true;
				if($insertStatus){
					$error->occurred = false;
					$error->source = $chip;
					$error->msg = 'Chip '.$chip->chip_sn.' was inserted successfully';
					array_push($result, $error);
				}
			}
		}
		die(json_encode($result));
		
	}
	public function addstation() {
		$postData = json_decode(file_get_contents('php://input'));
		$station = array(
			'station'=>$postData->station,
			'pc_name'=>$postData->pcName,
			'pc_wwc'=>$postData->pcWwc,
			'pc_chip'=>$postData->pcChip,
		);
		
		$insertStatus = $this->db->insert('params_stations', $station);
		if($insertStatus == true){
			echo 'success';
		}else {
			echo 'No Chip Was Inserted!';
		}
	}
		public function addTest() {
		$postData = json_decode(file_get_contents('php://input'));

			if($postData->station[0] === "R - Stations"){
				$test = array(
					'station'=>'R',
					'test_name'=>$postData->testName,
				);
			} elseif($postData->station[0] === "M - Stations"){
				$test = array(
					'station'=>'M',
					'test_name'=>$postData->testName,
				);
			} elseif($postData->station[0] === "Calibration"){
				$test = array(
					'station'=>$postData->station[0],
					'test_name'=>$postData->testName,
				);
			}elseif($postData->station[0] === "PTAT/ABS/Vgb+TEMP"){
				$test = array(
					'station'=>$postData->station[0],
					'test_name'=>$postData->testName,
				);
			}elseif($postData->station[0] === "TalynM+A"){
				$test = array(
					'station'=>$postData->station[0],
					'test_name'=>$postData->testName,
				);
			}elseif($postData->station[0] === "Robot"){
				$test = array(
					'station'=>$postData->station[0],
					'test_name'=>$postData->testName,
				);
			} else {
				echo 'Not Yet';
				die();
			}
		
		$insertStatus = $this->db->insert('params_test_names', $test);
//		return $insertStatus;
		echo json_encode($insertStatus);
	}
	
	public function chipList(){
		$this->db->order_by('chip_id', 'DESC');
		$chipList = $this->db->get('chip_view')->result();
		echo json_encode($chipList);
	}
	public function chipParams(){
		$data = new stdClass();
//		--------------- Objects ----------------
//		$data->boards = $this->db->query("SELECT distinct `board` FROM dvt_60g.chips")->result();
//		$data->packages = $this->db->query("SELECT distinct `package` FROM dvt_60g.chips")->result();
//		$data->models = $this->db->query("SELECT distinct `model` FROM dvt_60g.chips")->result();
//		$data->revisions = $this->db->query("SELECT distinct `revision` FROM dvt_60g.chips")->result();
//		$data->chip_process_abbs = $this->db->query("SELECT distinct `chip_process_abb` FROM dvt_60g.chips")->result();
//		$data->chip_types = $this->db->query("SELECT distinct `chip_type_name` FROM dvt_60g.chips c JOIN dvt_60g.chip_types as `ct` ON c.chip_type_id = ct.chip_type_id")->result();
//		--------------- Array ----------------
		$data->boards = array_map(function($e){return $e->board;}, $this->db->query("SELECT distinct `board` FROM dvt_60g.chips")->result());
		$data->packages = array_map(function($e){return $e->package;}, $this->db->query("SELECT distinct `package` FROM dvt_60g.chips")->result());
		$data->models = array_map(function($e){return $e->model;}, $this->db->query("SELECT distinct `model` FROM dvt_60g.chips")->result());
		$data->revisions = array_map(function($e){return $e->revision;}, $this->db->query("SELECT distinct `revision` FROM dvt_60g.chips")->result());
		$data->chip_process_abbs = array_map(function($e){return $e->chip_process_abb;}, $this->db->query("SELECT distinct `chip_process_abb` FROM dvt_60g.chips")->result());
		$data->chip_types = array_map(function($e){return $e->chip_type_name;}, $this->db->query("SELECT distinct `chip_type_name` FROM dvt_60g.chips c JOIN dvt_60g.chip_types as `ct` ON c.chip_type_id = ct.chip_type_id")->result());
		echo json_encode($data);
	}
	public function operations(){
		$data = $this->db->query("SELECT 
																top.test_id AS operation_id,
																top.date_time AS start_date,
																date_format(top.date_time, '%d/%m/%Y') AS date,
																date_format(top.date_time, '%H:%i') AS time,
																tt.type_idx AS test_type_id,
																t1.plan_id AS plan_id,
																t1.test_id AS test_id,
																tt.test_name AS test_name,
																ws.idx AS work_station_id,        
																ws.name AS work_station,
																top.chip_m_id AS chip_m_id,
																concat(ch_m.chip_sn, ' ', ch_m.chip_process_abb) AS chip_m_sn,
																top.chip_r_id AS chip_r_id,
																concat(ch_r.chip_sn, ' ', ch_r.chip_process_abb) AS chip_r_sn,
																CONCAT(u.fname, ' ', u.lname) AS user,
																top.test_status AS status_id,
																statuses.status AS status
															FROM
																(Select * from dvt_60g.test_operation WHERE (date_time between DATE_SUB(now(), interval 6 MONTH) and now())) as top
																JOIN dvt_60g.chips as `ch_r` ON `ch_r`.chip_id = `top`.chip_r_id
																JOIN dvt_60g.chips as `ch_m` ON `ch_m`.chip_id = `top`.chip_m_id
																JOIN dvt_60g.statuses ON top.test_status = statuses.test_status
																JOIN dvt_60g.work_stations as `ws` ON `ws`.name = `top`.work_station
																JOIN dvt_60g_web.test_v1 as `t1` ON `top`.plan_id = `t1`.test_id
																JOIN dvt_60g.test_types as `tt` ON `tt`.type_idx = `t1`.test_type_id
																JOIN dvt_60g_web.users as `u` ON `top`.operator = `u`.username
																order by start_date desc"
														)->result();
//		$this->db->select("")
		echo json_encode($data);
	}	
	
	public function testLog(){
		$testId = json_decode(file_get_contents('php://input'));
		$testLog = $this->db->query("select log.* from dvt_60g.log
											join dvt_60g.test_operation tp on 
											log.test_id = tp.test_id 
											where tp.plan_id = ".$testId)->result();
		
		echo json_encode($testLog);
	}
	public function testList(){
		$chipList = $this->db->get('params_test_names');
		echo json_encode($chipList->result());
	}
	public function stationList(){
		$this->db->where('name !=', 'Debug');
		$data = $this->db->get('work_stations_view')->result();
		echo json_encode($data);
	}
	
	function GetStationTests($stationID){
		$this->other_db = $this->load->database('main', TRUE);
		$res = $this->other_db->get_where('test_types', ['workstation_id'=>$stationID])->result();
//		$res = $this->db->get_where('test_struct_view', ['station_id'=>$stationID])->result();
		die(json_encode($res));
	}
	function GetTestData($testID){
		$res = $this->db->get_where('test_struct_view', ['test_type_id'=>$testID])->result();
		die(json_encode($res));
	}
	
	public function OperatorList(){
//		$data = $this->db->get('operator_view')->result();
		$data = $this->db->query("SELECT 
																	`dvt_60g_web`.`users`.`id` AS `id`,
																	CONCAT(`users`.`fname`, ' ', `users`.`lname`) AS `name`,
																	TRIM(TRAILING '@qti.qualcomm.com' FROM LCASE(`dvt_60g_web`.`users`.`email`)) AS `username`
															FROM
																	`dvt_60g_web`.`users`
															WHERE
																	(`dvt_60g_web`.`users`.`group_id` = 8)")->result();
		echo json_encode($data);
	}
	
	public function updateChipList(){
		$chipList = $this->db->get('params_chips');
		$delimiter = ",";
		$newline = "\r\n";
		$data = $this->dbutil->csv_from_result($chipList, $delimiter, $newline);
		force_download('Web_Chip_List.csv', $data, TRUE);
	} 	
	public function removeChip(){
		$chipId = json_decode(file_get_contents('php://input'));
		$this->db->where(array('id'=>$chipId));
		$status = $this->db->delete('params_chips');
		if($status) {
			echo 'success';
		} else{
			echo 'failure';
		}
	}				
	public function removeTest(){
		$test = json_decode(file_get_contents('php://input'));
		$this->db->where(array('id'=>$test->id));
		$status = $this->db->delete('params_test_names');
		if($status) {
			echo 'success';
		} else{
			echo 'failure';
		}
	}		
	public function removeStation(){
		$station = json_decode(file_get_contents('php://input'));
		$this->db->where(array('id'=>$station->id));
		$status = $this->db->delete('params_stations');
		if($status) {
			echo 'success';
		} else{
			echo 'failure';
		}
	}	
	public function editStation(){
		$station = json_decode(file_get_contents('php://input'));
		$this->db->where(array('station'=>$station->station));
		$stationRes = $this->db->get('params_stations')->result();
//		die(var_dump($stationRes));
		if($stationRes) {
			echo json_encode($stationRes);
		} else{
			echo 'failure';
		}
	}
	
	public function updateStation(){
		$station = json_decode(file_get_contents('php://input'));
		$this->db->where(array('station'=>$station->station));
		$status = $this->db->replace('params_stations', $station);
//		die(var_dump($station));
		if($status){
			echo 'success';
		} else {
			echo 'fulire';
		}
	}
	public function removeIteration(){
		$station = json_decode(file_get_contents('php://input'));
		$this->db->where(array('id'=>$station->id));
		$status = $this->db->delete('param_test_iteration');
		if($status) {
			echo 'success';
		} else{
			echo 'failure';
		}
	}	
	public function editIteration(){
		$data = json_decode(file_get_contents('php://input'));
//		die(var_dump($station));
		$this->db->where(array('id'=>$data->id));
		$result = $this->db->get('params_test_iteration')->result();
//		die(var_dump($stationRes));
		if($result) {
			echo json_encode($result);
		} else{
			echo 'failure';
		}
	}
	
	public function updateIteration(){
		$data = json_decode(file_get_contents('php://input'));
//		die(var_dump($data));
		$this->db->where(array('station'=>$data->id));
		$status = $this->db->replace('params_test_iteration', $data);
//		die(var_dump($station));
		if($status){
			echo 'success';
		} else {
			echo 'fulire';
		}
	}
		
	public function search(){
		$tests = array();
		$this->load->model('plan_model');
		$this->other_db= $this->load->database('main', TRUE);
		$this->db->select('id');
		$this->db->order_by('id', 'desc');
		$plans = $this->db->get('plans_v1_view')->result();
		foreach($plans as $i=>$plan){
//			$this->db->select('test_id');
			$this->db->order_by('plan_id', 'desc');
			$this->db->order_by('test_id', 'desc');
			$plan->tests = $this->db->get_where('tests_view_v1', array('plan_id'=>$plan->id))->result();
			foreach($plan->tests as $i=>$test){
				$this->other_db->where(array('test_id'=>$test->test_id));
				$this->other_db->where_in('config_id', ['11', '12']);
				$data = $this->other_db->get('test_configuration_data')->result();
				foreach($data as $chip){
						$this->db->select('chip_sn, chip_process_abb');
						$chipData = $this->db->get_where('chip_view', ['chip_id'=>$chip->value])->result();
						$statuses = $this->db->get_where('chip_status_view', ['data_idx'=>$chip->data_idx])->result();
						if(count($chipData) == 1){
							$chipData = $chipData[0];
							$chip->chip_sn = $chipData->chip_sn;
							$chip->chip_process_abb = $chipData->chip_process_abb;
							if(count($statuses) == 1){
								$statuses = $statuses[0];
								foreach($statuses as $key => $value){
									$chip->$key = $value;
								}
							}
						}
					}
				$test->chips = $data;
				array_push($tests, $test);
			}
		}
		echo json_encode($tests);
	}
	
	public function upload(){
//	 	$data = json_decode(file_get_contents('php://input'));
	 	$data = $_POST;
//		echo json_encode($data);
//		var_dump($data);
//		die();
		$target_dir = "uploads/";
     print_r($_FILES);
		$handle = fopen($target_dir.$_FILES['file']['name'], 'a') or die('Cannot open file:  '.$data);
//     $target_file = $target_dir . basename($_FILES["file"]["name"]);
	}
}
?>