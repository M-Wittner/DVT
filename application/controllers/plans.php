<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plans extends CI_Controller {
	public function __construct() {

	parent::__construct();
	$this->load->helper(array('form','url'));
//	$this->load->library(array('session'));
	$this->load->database('');
	$this->load->model('plan_model');
    }

	function index() {

		$result = $this->plan_model->Plans();
		echo json_encode($result);	
	}
	
	function Create() {
		// fetching data
		$postData = json_decode(file_get_contents('php://input'));
		// plan data
		$planData = $postData->plan;
		$plan = array(
			'user_id'=>$planData->userId,
			'user_username'=>$planData->username
		);
		$testsObj = $postData->test;
		if(sizeof($postData->test) > 0){
			$insertPlan = $this->plan_model->add_plan($plan);
			if($insertPlan){
				$planId = $this->plan_model->get_id($insertPlan);
				foreach($testsObj as $i => $testArr){
					//---R station test---
					if($testArr->station[0] == 'R-CB1' || $testArr->station[0] == 'R-CB2'){
						$chipsArr = $testArr->chips;
						$tempsArr = $testArr->temp;
						$channelsArr = $testArr->channel;
						$antennasArr = $testArr->antenna;
						if($testArr->calc == true){
							$time = $testArr->ants*$testArr->lineups*$testArr->seconds*$testArr->pins;
						} else {
							$time = null;
						}
						if(isset($testArr->pinAdd)){
							$pinAdd = $testArr->pinAdd;
						} else{
							$pinAdd = null;
						}
						$test = array(
							'priority'=>$testArr->priority[0],
							'lineup'=>$testArr->lineup,
							'station'=>$testArr->station[0],
							'name'=>$testArr->name[0]->test_name,
							'pin_from'=>$testArr->pinFrom,
							'pin_to'=>$testArr->pinTo,
							'pin_step'=>$testArr->pinStep,
							'pin_additional'=>$pinAdd,
							'mcs'=>$testArr->mcs,
							'voltage'=>$testArr->voltage,
							'notes'=>$testArr->notes,
							'seconds'=>$time,
							'plan_id'=>$planId
						);
//						die(var_dump($time));
						$insertTest = $this->plan_model->add_test($test);
						$testId = $this->plan_model->tests_id($insertTest);
	//					print_r($test);
	//					print_r($testId);
						foreach($chipsArr as $result){
							$chip = array(
								'chip'=> $result->Serial_Number,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$this->plan_model->add_chips($chip);
						};
						foreach($tempsArr as $result){
							$temp = array(
								'temp'=>$result,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$this->plan_model->add_temps($temp);
		//					print_r($temp);
						};
						foreach($channelsArr as $result){
							$channel = array(
								'channel'=>$result,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$this->plan_model->add_channels($channel);
		//					print_r($channel);
						};
						foreach($antennasArr as $result){
							$antenna = array(
								'antenna'=>$result,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$this->plan_model->add_antennas($antenna);
		//					print_r($antenna);
						};
						//---M station test---
					} else if($testArr->station[0] == 'M-CB1' || $testArr->station[0] == 'M-CB2'){
						$chipsArr = $testArr->chips;
						$tempsArr = $testArr->temp;
						$xifsArr = $testArr->xif;
						$channelsArr = $testArr->channel;
						$test = array(
							'priority'=>$testArr->priority[0],
							'lineup'=>$testArr->lineup,
							'station'=>$testArr->station[0],
							'name'=>$testArr->name[0]->test_name,
							'voltage'=>$testArr->voltage,
							'notes'=>$testArr->notes,
							'plan_id'=>$planId
						);
						$insertTest = $this->plan_model->add_test($test);
						$testId = $this->plan_model->tests_id($insertTest);
	//					print_r($test);
	//					print_r($testId);
						foreach($chipsArr as $result){
							$chip = array(
								'chip'=>$result->Serial_Number,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$insertChip = $this->plan_model->add_chips($chip);
							$chipId = $this->db->insert_id($insertChip);
							foreach($xifsArr as $xifRes){
								$xif = array(
									'chip_id'=>$chipId,
									'xif'=>$xifRes,
									'plan_id'=>$planId,
									'test_id'=>$testId
								);
								$this->plan_model->add_xifs($xif);
			//					print_r($xif);
							};
						};
						foreach($tempsArr as $result){
							$temp = array(
								'temp'=>$result,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$this->plan_model->add_temps($temp);
		//					print_r($temp);
						};
						foreach($channelsArr as $result){
							$channel = array(
								'channel'=>$result,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$this->plan_model->add_channels($channel);
		//					print_r($channel);
						};
					} else {
						echo 'not R or M station';
					}
			};
			echo 'success';
			} else {
				echo 'No plan inserted!';
			}	
		} else {
			echo 'No Test Detected!';
		}
		
	}
	
	function Show() {
		$id = json_decode(file_get_contents('php://input'));
		$result = $this->plan_model->get_plan($id);
		echo json_encode($result);
		
	}
	
	function remove(){
		$id = json_decode(file_get_contents('php://input'));
		$result = $this->plan_model->delete_plan($id);
		return $result;
	}
	
	function edit(){
		$postData = json_decode(file_get_contents('php://input'));
		$plan = $this->db->get_where('plans', array('id'=> $postData->planId))->result();
		$test = $this->db->get_where('tests', array('id'=>$postData->testId))->result();
		$chips = $this->db->get_where('test_chips', array('test_id'=>$postData->testId))->result();
		$temps = $this->db->get_where('test_temps', array('test_id'=>$postData->testId))->result();
		$channels = $this->db->get_where('test_channels', array('test_id'=>$postData->testId))->result();
		$antennas = $this->db->get_where('test_antennas', array('test_id'=>$postData->testId))->result();
		$result = array(
			'plan'=>$plan,
			'test'=>$test,
			'chips'=>$chips,
			'temps'=>$temps,
			'channels'=>$channels,
			'antennas'=>$antennas
		);
		echo json_encode($result);
	}
	
	function newcomment(){
		$postData = json_decode(file_get_contents('php://input'));
//		die(var_dump($postData));
		$comment = $this->plan_model->add_comment($postData);
		echo json_encode($comment);
	}
	
	function showcomments(){
		$id = json_decode(file_get_contents('php://input'));
		$comments = $this->plan_model->get_comments($id);
		echo json_encode($comments);
	}
	
	function update(){
		$postData = json_decode(file_get_contents('php://input'));
		$plan = $this->plan_model->update_plan($postData);
		if($plan){
			echo 'success';
		} else {
			echo 'Plan Was not updated';
		}
	}
	
	function chipstatus(){
		$status = json_decode(file_get_contents('php://input'));
//		die(var_dump($status));
		$updateStatus = $this->plan_model->update_chip_status($status);
		echo json_encode($updateStatus);
	}
	function xifstatus(){
		$status = json_decode(file_get_contents('php://input'));
//		die(var_dump($status));
		$updateStatus = $this->plan_model->update_xif_status($status);
		echo json_encode($updateStatus);
	}
}