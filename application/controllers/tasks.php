<?php
header("X-Frame-Options: SOMEORIGIN");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tasks extends CI_Controller {
	public function __construct() {

	parent::__construct();
	$this->load->helper(array('form','url'));
//	$this->load->library(array('session'));
	$this->load->database('');
//	$this->load->model();
	}

	function index() {
		$tasks = $this->db->get('tasks_view')->result();
		echo json_encode($tasks);
	}
	
	public function create(){
		$data = json_decode(file_get_contents('php://input'));
		$taskData = $data->task;
		$userData = $data->user;

		$task = [
			'station_id'=>$taskData->station[0]->id,
			'type_id'=>$taskData->type[0]->id,
			'creator_id'=>$userData->userId,
			'description'=>$taskData->description
		];
		
		$response = $this->db->insert('tasks', $task);
		
		echo json_encode($response);
	}
	
	public function view(){
		$id = json_decode(file_get_contents('php://input'));
		$task = $this->db->get_where('tasks_view', ['id'=>$id])->result()[0];
		if($task->approved == "0"){
			$task->approved = false;
		} else {
			$task->approved = true;
		}
		$task->comments = $this->db->get_where('task_comments_view', ['task_id'=>$id])->result();
		echo json_encode($task);
	}
	public function delete(){
		$id = json_decode(file_get_contents('php://input'));
		$this->db->where('id', $id);
		$res = $this->db->delete('tasks');
		
		echo json_encode($res);
	}
	
	public function approveUpdate(){
		$data = json_decode(file_get_contents('php://input'));
		$id = $data->id;
		$approved = $data->approved;
		$this->db->where('id', $id);
		$res = $this->db->update('tasks', ['approved'=>$approved]);
		echo json_encode($res);
	}
	
	public function statusUpdate(){
		$data = json_decode(file_get_contents('php://input'));
		$id = $data->id;
		$status = $data->status;
		$this->db->where('id', $id);
		$res = $this->db->update('tasks', ['status_id'=>$status]);
		
		if($res = true){
			$this->db->select('task_status');
			$status = $this->db->get_where('task_status',['id'=>$status])->result()[0]->task_status;
		} else {
			$status = false;
		}
		echo $status;
	}
	public function priorityUpdate(){
		$data = json_decode(file_get_contents('php://input'));
		$id = $data->id;
		$priority = $data->priority;
		$this->db->where('id', $id);
		$res = $this->db->update('tasks', ['priority_id'=>$priority]);
		
		if($res = true){
			$this->db->select('task_priority');
			$status = $this->db->get_where('task_priority',['id'=>$priority])->result()[0]->task_priority;
		} else {
			$status = false;
		}
		echo $status;
	}
	

}