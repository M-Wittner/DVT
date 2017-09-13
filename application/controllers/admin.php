<?php
header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->helper(array('form','url','download', 'file'));
        $this->load->database('');
		$this->load->dbutil();
    }
	
	public function addChip() {
		$postData = json_decode(file_get_contents('php://input'));
//		print_r($postData);
//		die();
		$chip = array(
			'chip'=>$postData->chip,
			'board'=>$postData->board,
			'package'=>$postData->package,
			'model'=>$postData->model,
			'revision'=>$postData->revision,
			'serial_number'=>$postData->sn,
			'corner'=>$postData->corner,
		);
		
		if(isset($chip['chip'])){
			$insertStatus = $this->db->insert('params_chips', $chip);
			if($insertStatus == true){
				$q = $this->db->get('params_chips');
				$delimiter = ",";
				$newline = "\r\n";
				$data = $this->dbutil->csv_from_result($q,$delimiter, $newline);
				force_download('CSV_Report.csv', $data);
			}
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
			} else {
				echo 'Not Yet';
				die();
			}
		
		$insertStatus = $this->db->insert('params_test_names', $test);
//		return $insertStatus;
		echo json_encode($insertStatus);
	}
	
	public function chipList(){
		$chipList = $this->db->get('params_chips');
		echo json_encode($chipList->result());
	}
	
	public function updateChipList(){
		$chipList = $this->db->get('params_chips');
		$delimiter = ",";
		$newline = "\r\n";
		$data = $this->dbutil->csv_from_result($chipList, $delimiter, $newline);
		force_download('Web_Chip_List.csv', $data, TRUE);
	}
}
?>
