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
		$this->db->order_by('date', 'DESC');
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
			'title'=>$taskData->title,
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
		if($task->active == "0"){
			$task->active = false;
		} else {
			$task->active = true;
		}
		$task->comments = $this->db->get_where('tasks_comments_view', ['task_id'=>$id])->result();
		echo json_encode($task);
	}
	public function active(){
		$data = json_decode(file_get_contents('php://input'));
		$id = $data->taskId;
		$active = !$data->active;
		$this->db->where('id', $id);
		$res = $this->db->update('tasks', ['active'=>$active]);
		
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
	public function assignedUpdate(){
		$data = json_decode(file_get_contents('php://input'));
		$id = $data->id;
		$userId = $data->userId;
		$this->db->where('id', $id);
		$res = $this->db->update('tasks', ['assigned_to'=>$userId]);
		
		if($res = true){
			$this->db->select(['assigned']);
			$status = $this->db->get_where('tasks_view',['id'=>$id])->result()[0]->assigned;
		} else {
			$status = false;
		}
		echo $status;
	}
	
	public function newComment(){
		$comment = json_decode(file_get_contents('php://input'));
		$res = $this->db->insert('task_comments', $comment);
		echo json_encode($res);
	}
	public function deleteComment(){
		$data = json_decode(file_get_contents('php://input'));
		$array = [
			'task_id'=>$data->taskId,
			'id'=>$data->commentId
		];
		$this->db->where($array);
		$res = $this->db->delete('task_comments');
		
		echo json_encode($res);
	}

}