<?php
header("Access-Control-Allow-Origin: *");
header("X-Frame-Options: GOFORIT");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Robot extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper(array('form','url'));
		$this->load->database('');
		$this->load->model('robot_model');
    }
		
function index() {
		$this->db->order_by('date', 'desc');
		$plans = $this->db->get('robot_plans')->result();
			foreach($plans as $plan){
				$this->db->where('plan_id', $plan->id);
				$plan->tests = $this->db->get('robot_tests')->result();
				$testProgress = 0;
//				foreach($plan->tests as $test){
//					$testProgress = $testProgress + $test->progress;			
//				}
				if(count($plan->tests) > 0){
					$plan->progress = round((($testProgress / count($plan->tests))), 2);
					$this->db->where('id', $plan->id);
					$this->db->set('progress', $plan->progress);
					$this->db->update('robot_plans');
				}
			}
		echo json_encode($plans);	
	}
	
	function create(){
		$postData = json_decode(file_get_contents('php://input'));
		$plan = $postData->plan;
		$tests = $postData->test;
		if(sizeof($postData->test) > 0){
			$planData = array(
				'user_username'=>$plan->username,
				'user_id'=>$plan->userId
			);
//			die(var_dump($postData));
			$insertPlan = $this->db->insert('robot_plans', $planData);
			if($insertPlan){
				$planId = $this->db->insert_id($insertPlan);
				foreach($tests as $test){
//				die(var_dump($test));
//	    ----------------------- Robot Test ----------------------------
					$notes = null;
					$time = null;
					$pol2Flag = null;
					$used_sectors = null;
					$board_file = null;
				  if(isset($test->notes)){
						$notes = $test->notes;	
					}
					if(isset($test->calc)){
						$time = $test->calc->lineups*$test->calc->seconds*$test->calc->pins*$test->calc->ants*$test->calc->temps*$test->calc->channels;
					}
					if($test->test_name[0]->test_name == 'rx pattern scan' || $test->test_name[0]->test_name == 'Single-chain EIRP scan' || $test->test_name[0]->test_name == 'BRP scan'){
						$pol2Flag = $test->pol2_flag;
					}
					if(isset($test->used_sectors)){
						$used_sectors = $test->used_sectors;	
					}
					if(isset($test->board_file)){
						$board_file = $test->board_file;	
					}	
					$robotTest = array(
						'plan_id'=>$planId,
						'priority'=>$test->priority[0],
						'test_name'=>$test->test_name[0]->test_name,
						'module_placement'=>$test->module_placement,
						'matlab_file'=>$test->matlab_file,
						'rf_module'=>$test->rf_module[0]->module,
						'module_file'=>$test->module_file,
						'rf_cable'=>$test->rf_cable,
						'file_name'=>$test->file_name,
						'pol2_flag'=>$pol2Flag,
						'used_sectors'=>$used_sectors,
						'board_file'=>$board_file,
						'notes'=>$notes,
						'time'=>$time,
					);
					$insertRobotTest = $this->db->insert('robot_tests', $robotTest);
//	    -------------------- Robot Test End ---------------------------		
					if($insertRobotTest){
							$testId = $this->db->insert_id($insertRobotTest);
//	    -------------------- Robot Test Struct ------------------------	
							$test->struct->plan_id = $planId;
							$test->struct->test_id = $testId;
							$test->struct->gain_index = $test->struct->gain_index[0];
							if($robotTest['test_name'] == 'Sector EIRP scan' || $robotTest['test_name'] =='Single-chain EIRP scan'){
								$test->struct->channel = $test->struct->channel[0];	
							}
							$insertTestStruct = $this->db->insert('robot_tests_struct', $test->struct);
//	    -------------------- Robot Test Struct End---------------------	
							if(!$insertTestStruct){
								echo 'Failed To Insert Test Struct';
							}
					} else{
							echo 'Failed To Insert Test';		
					}
				}
				if($insertTestStruct){
					echo 'success';
				}
			}
		} else {
				echo 'No Tests Were Inserted';
		}
	}
	
	function show(){
		$id = json_decode(file_get_contents('php://input'));
		$plan = $this->db->get_where('robot_plans', array('id'=>$id))->result()[0];
		$plan->tests = $this->db->get_where('robot_tests', array('plan_id'=>$id))->result();
		foreach($plan->tests as $test){
			$test->struct = $this->db->get_where('robot_tests_struct', array('test_id'=>$test->id, 'plan_id'=>$id))->result()[0];
		}
		echo json_encode($plan);
	}
	
	function removePlan(){
		$id = json_decode(file_get_contents('php://input'));
		echo json_encode($id);
		$result = $this->db->query("DELETE FROM `robot_plans` WHERE id = ?", $id);
		return $result;
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
		$tests = $postData->tests;
		foreach($tests as $test){
//				die(var_dump($test));
//	    ----------------------- Robot Test ----------------------------
					$notes = null;
					$time = null;
					$pol2Flag = null;
					$used_sectors = null;
					$board_file = null;
				  if(isset($test->notes)){
						$notes = $test->notes;	
					}
					if(isset($test->calc)){
						$time = $test->calc->lineups*$test->calc->seconds*$test->calc->pins*$test->calc->ants*$test->calc->temps*$test->calc->channels;
					}
					if($test->test_name[0]->test_name == 'rx pattern scan' || $test->test_name[0]->test_name == 'Single-chain EIRP scan' || $test->test_name[0]->test_name == 'BRP scan'){
						$pol2Flag = $test->pol2_flag;
					}
					if(isset($test->used_sectors)){
						$used_sectors = $test->used_sectors;	
					}
					if(isset($test->board_file)){
						$board_file = $test->board_file;	
					}	
					$robotTest = array(
						'plan_id'=>$planData->id,
						'priority'=>$test->priority[0],
						'test_name'=>$test->test_name[0]->test_name,
						'module_placement'=>$test->module_placement,
						'matlab_file'=>$test->matlab_file,
						'rf_module'=>$test->rf_module[0]->module,
						'module_file'=>$test->module_file,
						'rf_cable'=>$test->rf_cable,
						'file_name'=>$test->file_name,
						'pol2_flag'=>$pol2Flag,
						'used_sectors'=>$used_sectors,
						'board_file'=>$board_file,
						'notes'=>$notes,
						'time'=>$time,
					);
					$insertRobotTest = $this->db->insert('robot_tests', $robotTest);
//	    -------------------- Robot Test End ---------------------------		
					if($insertRobotTest){
							$testId = $this->db->insert_id($insertRobotTest);
//	    -------------------- Robot Test Struct ------------------------	
							$test->struct->plan_id = $planData->id;
							$test->struct->test_id = $testId;
							$test->struct->gain_index = $test->struct->gain_index[0];
							if($robotTest['test_name'] == 'Sector EIRP scan' || $robotTest['test_name'] =='Single-chain EIRP scan'){
								$test->struct->channel = $test->struct->channel[0];	
							}
							$insertTestStruct = $this->db->insert('robot_tests_struct', $test->struct);
//	    -------------------- Robot Test Struct End---------------------	
							if(!$insertTestStruct){
								echo 'Failed To Insert Test Struct';
							}
					} else{
							echo 'Failed To Insert Test';		
					}
				}
				if($insertTestStruct){
					echo 'success';
				}
	}
	
	function removeTest(){
		$id = json_decode(file_get_contents('php://input'));
//		die(var_dump($id));
		$result = $this->db->query("DELETE FROM `robot_tests` WHERE id = ?", $id);
		if ($result){
			$structDel = $this->db->query("DELETE FROM `robot_tests_struct` WHERE `plan_id` = ?", $id);
			if($structDel){
				echo 'success';
			} else{
				return $structDel;
			}
		} else{
			return $result;
		}
	}
	
	function edit(){
		$postData = json_decode(file_get_contents('php://input'));
		
		$test = $this->db->get_where('robot_tests', ['id'=>$postData->testId])->result()[0];
//		die(var_dump($test));
		$test->test_name = $this->db->get_where('params_test_names', array('test_name'=>$test->test_name))->result();
		$test->struct = $this->db->get_where('robot_tests_struct', ['test_id'=>$postData->testId])->result()[0];
		echo json_encode($test);
	}
	
	function update(){
		$test = json_decode(file_get_contents('php://input'))->test;
		$test->test_name = $test->test_name[0]->test_name;
		$updateTest = $this->db->replace('robot_tests', $test);
		if($updateTest){
			$updateStruct = $this->db->replace('robot_tests_struct', $test->struct);
			if($updateStruct){
				echo 'success';
			} else {
				echo json_encode($updateStruct);
			}
		}else{
			echo json_encode($updateTest);
		}
	}
	
	function testStatus(){
		$postData = json_decode(file_get_contents('php://input'));
		$testId = $postData->testId;
		$status = $postData->status;
		
		switch($status){
			case "IDLE":
				$status = "In Progress";
				break;
			case "In Progress":
				$status = "Completed";
				break;
			case "Completed":
				$status = "Error";
				break;
			case "Error":
				$status = "IDLE";
				break;
		}
		
		$this->db->where(array('id'=>$testId));
		$updateStatus = $this->db->update('robot_tests', ['status'=>$status]);
		
	}
}