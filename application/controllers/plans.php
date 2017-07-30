<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Content-Length, X-Requested-With");
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plans extends CI_Controller {
	public function __construct() {

	parent::__construct();
	$this->load->helper(array('form','url'));
	$this->load->library(array('session'));
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
		$postData = json_decode(file_get_contents('php://input'));
		
		$planData = $postData->plan;
		$plan = array(
			'title'=>$planData->title,
		);
		$testData = $postData->test;
		foreach ($testData as $i => $test){
			$stationsData = $testData[$i]->station;
			$stations = $this->plan_model->get_station($stationsData);
			$nameData = $testData[$i]->name;
			$name = $this->plan_model->get_name($nameData);
			$chipsData = $testData[$i]->chips;
			$chips = $this->plan_model->get_chips($chipsData);
			$tempsData = $testData[$i]->temp;
			$temps = $this->plan_model->get_temps($tempsData);
			$channelsData = $testData[$i]->channel;
			$channels = $this->plan_model->get_channels($channelsData);
			$anthenasData = $testData[$i]->anthena;
			$anthenas = $this->plan_model->get_anthenas($anthenasData);
//			die(var_dump($data));
			$test = array(
			'lineup'=>$testData[$i]->lineup,
			'station'=>$stations, // <- array of stations!
			'name'=>$name,
//			'chips'=>$chips,
			'pin_from'=>$testData[$i]->pinFrom,
			'pin_to'=>$testData[$i]->pinTo,
			'pin_step'=>$testData[$i]->pinStep,
			'pin_additional'=>$testData[$i]->pinAdd,
			'mcs'=>$testData[$i]->mcs,
//			'temp'=>$temps,
//			'channel'=>$channels,
//			'anthena'=>$anthenas,
		);
			print_r($plan);
			print_r($test);
			print_r($chips);
			print_r($anthenas);
			print_r($channels);
		};
		die();
		$insertPlan = $this->plan_model->insert_plan($plan);
//		$insertId = $this->plan_model->get_id($insertPlan);
		if($insertPlan){
			echo "success";
			print_r('id: '.$insertPlan);
//			print_r($insertId);
		} else {
			echo 'failure';
		};
//		die(var_dump($test, $plan));
//		if(is_object($test) && is_object($plan)){
////			$pData = $this->db->insert('plans', $plan);
//			$tData = $this->db->insert('tests', $test);
//		} else {
//			die(var_dump($test));
//		};
		
	}
	
	function Show() {
		$this->db->where('id', $name);
		return $q->result();
	}
	
}
