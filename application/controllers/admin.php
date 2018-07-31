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
		$postData = json_decode(file_get_contents('php://input'));
		$chipType = $postData->chip;
		$chip = array(
			'board'=>$postData->board,
			'chip_sn'=>$postData->sn,
			'chip_process_abb'=>$postData->corner,
			'package'=>'Flip_Chip',
			'model'=>'OCA6425',
		);
		switch($chipType){
			case 'TalynA 1':
				$chip['revision'] = 'A0';
				$chip['chip_type_id'] = 1;
				break;
			case 'TalynA 2':
				$chip['revision'] = 'A0';
				$chip['chip_type_id'] = 3;
				break;
			case 'TalynM 1':
				$chip['revision'] = 'B0';
				$chip['chip_type_id'] = 2;
			case 'TalynM 2':
				$chip['revision'] = 'B0';
				$chip['chip_type_id'] = 4;
				break;
		}
//		die(var_dump($chip));
		if(isset($chip['chip_sn'])){
			$this->other_db= $this->load->database('main', TRUE);
			$insertStatus = $this->other_db->insert('chips', $chip);
			if($insertStatus == true){
				echo 'success';
			}
		}else {
			echo 'No Chip Was Inserted!';
		}
		
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
	public function operations(){
		$this->db->order_by('date_time', 'DESC');
		$this->other_db = $this->load->database('main', TRUE);
		$chipList = $this->other_db->get('operation_view')->result();
		echo json_encode($chipList);
	}	
	public function testList(){
		$chipList = $this->db->get('params_test_names');
		echo json_encode($chipList->result());
	}
	public function stationList(){
		$chipList = $this->db->get('params_stations');
		echo json_encode($chipList->result());
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
		$this->db->order_by('id', 'DESC');
		$plans = $this->db->get('plans')->result();
		
		foreach ($plans as $plan){
			$plan->tests = $this->db->get_where('tests_view_new', array('plan_id'=>$plan->id))->result();
			foreach ($plan->tests as $test) {
				$test->chips = $this->db->get_where('test_chips_view', array('test_id'=>$test->id))->result();
			}
		}
		echo json_encode($plans);
	}
	
	public function TCP(){
		$data =json_decode(file_get_contents('php://input'));
		$host = '10.18.134.163';
		$port = 5000;
		
		$fp = fsockopen($host,$port, $errno, $errstr, 30);
			if (!$fp) {
				echo "$errstr ($errno)<br />\n";
			} else {
				fwrite($fp, "You message");
				while (!feof($fp)) {
					echo fgets($fp, 128);
				}
				fclose($fp);
			}
	}
}
?>