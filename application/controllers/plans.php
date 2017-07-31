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
		
//		$this->fetchdata();
//		$data['main_content'] = 'Plans/index';
//		$this->load->view('users', $this->data);
		$result = $this->plan_model->Plans();
		echo json_encode($result);	
	}

	function All() {
		$plans = $this->plan_model->Plans();
		echo json_encode($plans);
	}
	
	function Create() {
		// fetching data
		$postData = json_decode(file_get_contents('php://input'));
		die(print_r($postData));
		// plan data
		$planData = $postData->plan;
		$plan = array(
			'title'=>$planData->title
		);
		$testData = $postData->test;
		foreach ($testData as $i => $test){
			$stationsData = $testData[$i]->station;
			$nameData = $testData[$i]->name;
			$chipsData = $testData[$i]->chips;
			$tempsData = $testData[$i]->temp;
			$channelsData = $testData[$i]->channel;
			$anthenasData = $testData[$i]->anthena;
		};
		$tempsArr = $this->plan_model->get_temps($tempsData);
		$chipsArr = $this->plan_model->get_chips($chipsData);
		$channelsArr= $this->plan_model->get_channels($channelsData);
		$anthenasArr = $this->plan_model->get_anthenas($anthenasData);
		$stationsArr = $this->plan_model->get_station($stationsData);
		$nameArr = $this->plan_model->get_name($nameData);
		
		// inserting plan
		$insertPlan = $this->plan_model->add_plan($plan);
		if($insertPlan){
			//getting inserted plan id
			$planId = $this->plan_model->get_id($insertPlan);
			if($planId) {
				$test = array(
					'lineup'=>$testData[$i]->lineup,
					'station'=>$stationsArr,
					'name'=>$nameArr,
					'pin_from'=>$testData[$i]->pinFrom,
					'pin_to'=>$testData[$i]->pinTo,
					'pin_step'=>$testData[$i]->pinStep,
					'pin_additional'=>$testData[$i]->pinAdd,
					'mcs'=>$testData[$i]->mcs,
					'voltage'=>$testData[$i]->voltage,
					'notes'=>$testData[$i]->notes,
					'plan_id'=>$planId
				);
				$insertTest = $this->plan_model->add_test($test);
				if($insertTest){
					$testId = $this->plan_model->get_id($insertTest);
					if($testId) {
						foreach ($tempsArr as $tempRes){
							$temps = array(
								'temp' => $tempRes,
								'test_id'=> $testId
							);
							$insertTemps = $this->plan_model->add_temps($temps);
						};
						if($insertTemps){
							foreach($channelsArr as $channelRes){
								$channels = array(
									'channel' => $channelRes,
									'test_id' => $testId
								);
								$insertChannels = $this->plan_model->add_channels($channels);
							};
							if($insertChannels){
								foreach($anthenasArr as $anthenaRes){
									$anthenas = array(
										'anthena' => $anthenaRes,
										'test_id' => $testId
									);
									$insertAnthenas = $this->plan_model->add_anthenas($anthenas);
								};
								if($insertAnthenas){
									foreach($chipsArr as $chipRes){
										$chips = array(
											'chip' => $chipRes,
											'test_id' => $testId
										);
//										print_r($chips);
										$insertChips = $this->plan_model->add_chips($chips);
										echo 'success!';
									};
								} else {
									echo 'no anthenas inserted';
								}
							} else {
								echo 'no channels inserted';
							}
							
						} else {
							echo 'no temps inserted';
						}
					} else{
						echo 'no test id obtained';
					}
				} else {
					echo 'inset test failure';
				}
			} else {
				echo "no plan ID obtained";
			}
		} else {
			echo 'failure';
		};
		
	}
	
	function Show() {
		$this->db->where('id', $name);
		return $q->result();
	}
	
}