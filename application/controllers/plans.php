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
//		die(var_dump($postData));
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
					if(isset($testArr->notes)){
						$notes = $testArr->notes;
					} else {
						$notes = null;
					}
//			------------- R station test -------------
					if($testArr->station[0]->station == 'R-CB1' || $testArr->station[0]->station == 'R-CB2'){
						$chipsArr = $testArr->chips;
						$tempsArr = $testArr->temp;
						$channelsArr = $testArr->channel;
						$antennasArr = $testArr->antenna;
//						die(var_dump($testArr));
						if(isset($testArr->calc)){
							$time = $testArr->calc->lineups*$testArr->calc->seconds*$testArr->calc->pins*$testArr->calc->ants*$testArr->calc->temps*$testArr->calc->channels;
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
							'station'=>$testArr->station[0]->station,
							'name'=>$testArr->name[0]->test_name,
							'pin_from'=>$testArr->pinFrom,
							'pin_to'=>$testArr->pinTo,
							'pin_step'=>$testArr->pinStep,
							'pin_additional'=>$pinAdd,
							'mcs'=>$testArr->mcs,
							'voltage'=>$testArr->voltage,
							'notes'=>$notes,
							'seconds'=>$time,
							'plan_id'=>$planId
						);
						$insertTest = $this->plan_model->add_test($test);
						$testId = $this->plan_model->tests_id($insertTest);
						foreach($chipsArr as $result){
							$chip = array(
								'chip'=> $result->serial_number,
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
						};
						foreach($channelsArr as $result){
							$channel = array(
								'channel'=>$result,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$this->plan_model->add_channels($channel);
						};
						foreach($antennasArr as $result){
							$antenna = array(
								'antenna'=>$result,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$this->plan_model->add_antennas($antenna);
						};
//			------------- M station test -------------
					} else if($testArr->station[0]->station == 'M-CB1' || $testArr->station[0]->station == 'M-CB2' || $testArr->station[0]->station == 'Calibration'){
						$chipsArr = $testArr->chips;
						$tempsArr = $testArr->temp;
						$xifsArr = $testArr->xif;
						$channelsArr = $testArr->channel;
						$test = array(
							'priority'=>$testArr->priority[0],
							'lineup'=>$testArr->lineup,
							'station'=>$testArr->station[0]->station,
							'name'=>$testArr->name[0]->test_name,
							'voltage'=>$testArr->voltage,
							'notes'=>$notes,
							'plan_id'=>$planId
						);
						if(isset($testArr->calc)){
							$time = $testArr->calc->lineups*$testArr->calc->seconds*$testArr->calc->xifs*$testArr->calc->sweeps*$testArr->calc->temps*$testArr->calc->channels;
						} else {
							$time = null;
						}
						if($testArr->station[0]->station == 'Calibration'){
							if(isset($testArr->miniC)){
								$test['mini_circuits'] = $testArr->miniC;
							}else{ 
								$test['mini_circuits'] = false;
							}
							$test['mcs'] = $testArr->mcs;
						}
						$insertTest = $this->plan_model->add_test($test);
						$testId = $this->plan_model->tests_id($insertTest);
						foreach($chipsArr as $result){
							$chip = array(
								'chip'=>$result->serial_number,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$insertChip = $this->plan_model->add_chips($chip);
							$chipId = $this->db->insert_id($insertChip);
							foreach($xifsArr as $xifRes){
								$xif = array(
									'chip_id'=>$chipId,
									'chip'=>$chip['chip'],
									'xif'=>$xifRes,
									'plan_id'=>$planId,
									'test_id'=>$testId
								);
								$this->plan_model->add_xifs($xif);
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
//							print_r($channel);
						};
//						die();
//------------ PTAT/ABS/Vgb+TEMP and TalynM+A station test -------------
					} elseif($testArr->station[0]->station == 'PTAT/ABS/Vgb+TEMP'|| $testArr->station[0]->station == 'TalynM+A') {
							$chipsArr = $testArr->chips;
							$test = array(
								'priority'=>$testArr->priority[0],
								'lineup'=>'/',
								'station'=>$testArr->station[0]->station,
								'name'=>$testArr->name[0]->test_name,
								'notes'=>$notes,
								'plan_id'=>$planId
							);
							$insertTest = $this->plan_model->add_test($test);
							$testId = $this->plan_model->tests_id($insertTest);
							foreach($chipsArr as $result){
								$chip = array(
									'chip'=>$result->serial_number,
									'plan_id'=>$planId,
									'test_id'=>$testId
								);
								$insertChip = $this->plan_model->add_chips($chip);
							}
					} else {
						echo 'not valid station';
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
	
	function removePlan(){
		$id = json_decode(file_get_contents('php://input'));
		$result = $this->plan_model->delete_plan($id);
		return $result;
	}
	
	function edit(){
		$id = json_decode(file_get_contents('php://input'));
		$result = $this->plan_model->edit_test($id);
		echo json_encode($result);
	}
	
	function newcomment(){
		$id = json_decode(file_get_contents('php://input'));
		$test = $this->db->get_where('tests', array('id'=>$id->testId))->result();
		$chips = $this->db->get_where('test_chips', array('test_id'=>$id->testId))->result();
		$result = array(
			'test'=>$test,
			'chips'=>$chips
		);
		echo json_encode($result);
	}
	
	function addcomment(){
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
	
	function getcomment(){
		$data = json_decode(file_get_contents('php://input'));
			$res = array(
				'comment' => $this->db->get_where('test_comments', array('id'=>$data->commentId))->result(),
				'test' => $this->db->get_where('tests', array('id'=>$data->testId))->result(),
				'chips' => $this->db->get_where('test_chips', array('test_id'=>$data->testId))->result()
			);
		echo json_encode($res);
	}
	
	function editcomment(){
		$comment = json_decode(file_get_contents('php://input'));
		if($comment->severity == 'Minor'){
			$this->db->where('id', $comment->id);
			$comment = array(
				'plan_id'=>$comment->plan_id,
				'test_id'=>$comment->test_id,
				'severity'=>$comment->severity,
				'station'=>$comment->station,
				'test_name'=>$comment->test_name,
				'chip'=>$comment->chip,
				'details'=>$comment->details
			);
			$insertStatus = $this->db->update('test_comments', $comment);
			print_r($insertStatus);
		} elseif($comment->severity == 'Major'){
			$this->db->where('id', $comment->id);
			$comment = array(
				'plan_id'=>$comment->plan_id,
				'test_id'=>$comment->test_id,
				'severity'=>$comment->severity,
				'station'=>$comment->station,
				'details'=>$comment->details
			);
			$insertStatus = $this->db->update('test_comments', $comment);
			print_r($insertStatus);
		}else{
			echo 'failed';
		};
//		echo json_encode($comment->severity);
	}
	
	function update(){
		$postData = json_decode(file_get_contents('php://input'));
		$plan = $this->plan_model->update_test($postData);
		if($plan){
			echo 'success';
		} else {
			echo 'Test Was not updated';
		}
	}
	
	function planCheck(){
		$data = json_decode(file_get_contents('php://input'));
		$updateStatus = $this->plan_model->update_plan_status($data);
		echo json_encode($updateStatus);
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
	
	function addtests(){
		// fetching data
		$postData = json_decode(file_get_contents('php://input'));
//		die(var_dump($postData));
		// plan data
		$planData = $postData->plan;
		$plan = array(
			'user_id'=>$planData->userId,
			'user_username'=>$planData->username,
			'id'=>$planData->id
		);
		$testsObj = $postData->test;
//		die(var_dump($testsObj));
		if(sizeof($postData->test) > 0){
			$planId = $planData->id;
			if(isset($planId)){
				foreach($testsObj as $i => $testArr){
					if(isset($testArr->notes)){
						$notes = $testArr->notes;
					} else {
						$notes = null;
					}
//			------------- R station test -------------
					if($testArr->station[0]->station == 'R-CB1' || $testArr->station[0]->station == 'R-CB2'){
						$chipsArr = $testArr->chips;
						$tempsArr = $testArr->temp;
						$channelsArr = $testArr->channel;
						$antennasArr = $testArr->antenna;
//						die(var_dump($testArr));
						if(isset($testArr->calc)){
							$time = $testArr->calc->ants*$testArr->calc->lineups*$testArr->calc->seconds*$testArr->calc->pins;
						} else {
							$time = null;
						}
						if(isset($testArr->pinAdd)){
							$pinAdd = $testArr->pinAdd;
						} else{
							$pinAdd = null;
						}
						if(isset($testArr->voltage)){
							$voltage = $testArr->voltage;
						} else{
							$voltage = null;
						}
						$test = array(
							'priority'=>$testArr->priority[0],
							'lineup'=>$testArr->lineup,
							'station'=>$testArr->station[0]->station,
							'name'=>$testArr->name[0]->test_name,
							'pin_from'=>$testArr->pinFrom,
							'pin_to'=>$testArr->pinTo,
							'pin_step'=>$testArr->pinStep,
							'pin_additional'=>$pinAdd,
							'mcs'=>$testArr->mcs,
							'voltage'=>$voltage,
							'notes'=>$notes,
							'seconds'=>$time,
							'plan_id'=>$planId
						);
						$insertTest = $this->plan_model->add_test($test);
						$testId = $this->plan_model->tests_id($insertTest);
						foreach($chipsArr as $result){
							$chip = array(
								'chip'=> $result->serial_number,
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
						};
						foreach($channelsArr as $result){
							$channel = array(
								'channel'=>$result,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$this->plan_model->add_channels($channel);
						};
						foreach($antennasArr as $result){
							$antenna = array(
								'antenna'=>$result,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$this->plan_model->add_antennas($antenna);
						};
//			------------- M station test -------------
					} else if($testArr->station[0]->station == 'M-CB1' || $testArr->station[0]->station == 'M-CB2' || $testArr->station[0]->station == 'Calibration'){
						$chipsArr = $testArr->chips;
						$tempsArr = $testArr->temp;
						$xifsArr = $testArr->xif;
						$channelsArr = $testArr->channel;
						$test = array(
							'priority'=>$testArr->priority[0],
							'lineup'=>$testArr->lineup,
							'station'=>$testArr->station[0]->station,
							'name'=>$testArr->name[0]->test_name,
							'voltage'=>$testArr->voltage,
							'notes'=>$notes,
							'plan_id'=>$planId
						);
						if($testArr->station[0]->station == 'Calibration'){
							if(isset($testArr->miniC)){
								$test['mini_circuits'] = $testArr->miniC;
							}else{ 
								$test['mini_circuits'] = false;
							}
							$test['mcs'] = $testArr->mcs;
						}
						$insertTest = $this->plan_model->add_test($test);
						$testId = $this->plan_model->tests_id($insertTest);
						foreach($chipsArr as $result){
							$chip = array(
								'chip'=>$result->serial_number,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$insertChip = $this->plan_model->add_chips($chip);
							$chipId = $this->db->insert_id($insertChip);
							foreach($xifsArr as $xifRes){
								$xif = array(
									'chip_id'=>$chipId,
									'chip'=>$chip['chip'],
									'xif'=>$xifRes,
									'plan_id'=>$planId,
									'test_id'=>$testId
								);
								$this->plan_model->add_xifs($xif);
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
//------------ PTAT/ABS/Vgb+TEMP and TalynM+A station test -------------
					} elseif($testArr->station[0]->station == 'PTAT/ABS/Vgb+TEMP'|| $testArr->station[0]->station == 'TalynM+A') {
							$chipsArr = $testArr->chips;
							$test = array(
								'priority'=>$testArr->priority[0],
								'lineup'=>'/',
								'station'=>$testArr->station[0]->station,
								'name'=>$testArr->name[0]->test_name,
								'notes'=>$notes,
								'plan_id'=>$planId
							);
							$insertTest = $this->plan_model->add_test($test);
							$testId = $this->plan_model->tests_id($insertTest);
							foreach($chipsArr as $result){
								$chip = array(
									'chip'=>$result->serial_number,
									'plan_id'=>$planId,
									'test_id'=>$testId
								);
								$insertChip = $this->plan_model->add_chips($chip);
							}
					} else {
						echo 'not R or M station';
					}
			};
			echo 'success';
			} else {
				echo 'No plan inserted!';
			}	
		} 
		else {
			echo 'No Test Detected!';
		}
	}
	
	function removeTest(){
		$id = json_decode(file_get_contents('php://input'));
		$result = $this->plan_model->delete_test($id);
		if ($result){
			echo 'success';
		} else{
			return $result;
		}
	}	
	function removeComment(){
		$id = json_decode(file_get_contents('php://input'));
		$result = $this->plan_model->delete_comment($id);
		return $result;
	}
}
