<?php
header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->helper(array('form','url'));
        $this->load->database('');
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
		
		if(isset($chip)){
			$insertStatus = $this->db->insert('params_chips', $chip);
			echo json_encode($insertStatus);
			print_r($chip);
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
}
?>
