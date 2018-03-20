<?php
header("X-Frame-Options: SOMEORIGIN");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {
	public function __construct() {

	parent::__construct();
	$this->load->helper(['form','url']);
	$this->load->library(['email']);
	$this->load->database('');
//	$this->load->model();
	}

	function index() {
		
	}
	
	public function myTasks(){
		$id = json_decode(file_get_contents('php://input'));
		$this->db->order_by('date', 'DESC');
		$tasks = $this->db->get_where('tasks_view', ['assigned_id'=>$id])->result();
		echo json_encode($tasks);
	}

}