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
			'title'=>$planData->title,
			'user_id'=>$planData->userId,
			'user_username'=>$planData->username
		);
		$testsObj = $postData->test;
		if(sizeof($postData->test) > 0){
			$insertPlan = $this->plan_model->add_plan($plan);
			if($insertPlan){
				$planId = $this->plan_model->get_id($insertPlan);
				foreach($testsObj as $i => $testArr){
					$chipsArr = $testArr->chips;
					$tempsArr = $testArr->temp;
					$channelsArr = $testArr->channel;
					$anthenasArr = $testArr->anthena;
					$test = array(
						'lineup'=>$testArr->lineup,
						'station'=>$testArr->station[0],
						'name'=>$testArr->name[0],
						'pin_from'=>$testArr->pinFrom,
						'pin_to'=>$testArr->pinTo,
						'pin_step'=>$testArr->pinStep,
						'pin_additional'=>$testArr->pinAdd,
						'mcs'=>$testArr->mcs,
						'voltage'=>$testArr->voltage,
						'notes'=>$testArr->notes,
						'plan_id'=>$planId
					);
					$insertTest = $this->plan_model->add_test($test);
					$testId = $this->plan_model->tests_id($insertTest);
	//				print_r($test);
					foreach($chipsArr as $result){
						$chip = array(
							'chip'=> $result,
							'test_id'=>$testId
						);
						$this->plan_model->add_chips($chip);
	//					print_r($chip);
					};
					foreach($tempsArr as $result){
						$temp = array(
							'temp'=>$result,
							'test_id'=>$testId
						);
						$this->plan_model->add_temps($temp);
	//					print_r($temp);
					};
					foreach($channelsArr as $result){
						$channel = array(
							'channel'=>$result,
							'test_id'=>$testId
						);
						$this->plan_model->add_channels($channel);
	//					print_r($channel);
					};
					foreach($anthenasArr as $result){
						$anthena = array(
							'anthena'=>$result,
							'test_id'=>$testId
						);
						$this->plan_model->add_anthenas($anthena);
	//					print_r($anthena);
					};
					
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
//		die(var_dump($result));
		echo json_encode($result);
		
	}
	
	function remove(){
		$id = json_decode(file_get_contents('php://input'));
		$result = $this->plan_model->delete_plan($id);
		return $result;
	}
	
	function edit(){
		$postData = json_decode(file_get_contents('php://input'));
		$result = $this->plan_model->get_test($postData);
		echo json_encode($result);
	}
	
	function newcomment(){
		$postData = json_decode(file_get_contents('php://input'));
		$comment = $this->plan_model->add_comment($postData);
		echo json_encode($comment);
	}
	
	function showcomments(){
		$id = json_decode(file_get_contents('php://input'));
		$comments = $this->plan_model->get_comments($id);
		echo json_encode($comments);
	}
}