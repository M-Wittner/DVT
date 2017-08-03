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
//		die(print_r($postData));
		// plan data
		$planData = $postData->plan;
		$plan = array(
			'title'=>$planData->title
		);
		$testsObj = $postData->test;
		
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
		
	}
	
	function Show() {
		$postData = json_decode(file_get_contents('php://input'));
		die(var_dump($postData));
		$id = $postData;
		
		die(var_dump($postData));
		$result = $this->plan_model->get_plan($id[0]);
		echo json_encode($result);
		
	}
	
}