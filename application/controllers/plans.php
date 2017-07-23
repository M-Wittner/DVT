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
	$this->load->model('planModel');
    }

	function index() {
		
//		$this->fetchdata();
//		$data['main_content'] = 'Plans/index';
//		$this->load->view('users', $this->data);
		$result = $this->planModel->Plans();
		echo json_encode($result);	
	}

	function All() {
		$plans = $this->planModel->Plans();
		echo json_encode($plans);
	}
	
	function Create() {
		$postData = json_decode(file_get_contents('php://input'));
		
		$planData = $postData->plan;
		$plan = array(
			'title'=>$planData->title,
		);
		
		$testData = $postData->test;
//		die(var_dump($postData));
		$test = array(
			'lineup'=>$testData->lineup,
			'station'=>$testData->station,
			'name'=>$testData->name,
			'chips'=>$testData->chipSN,
			'pin_from'=>$testData->pinFrom,
			'pin_to'=>$testData->pinTo,
			'pin_step'=>$testData->pinStep,
			'pin_additional'=>$testData->pinAdd,
			'temp'=>$testData->temp,
			'channel'=>$testData->channel,
			'anthena'=>$testData->anthena,
		);
		$chipList = $testData->chipSN;
		$chips = new stdClass();
		foreach($chipList as $key=>$value){
			$chips->$key = $value;
		}
		die(var_dump($chips));
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
