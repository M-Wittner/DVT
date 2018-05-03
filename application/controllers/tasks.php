<?php
header("X-Frame-Options: SOMEORIGIN");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tasks extends CI_Controller {
	public function __construct() {

	parent::__construct();
	$this->load->helper(['form','url']);
	$this->load->library(['email']);
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
//		$this->db->select('email');
//		$userData->email = $this->db->get_where('users', ['id'=>$data->user->userId])->result()[0]->email;

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
		if($task->reviewed == "0"){
			$task->reviewed = false;
		} else {
			$task->reviewed = true;
		}
		$task->station = $this->db->get_where('work_stations', ['work_station'=>$task->station])->result();
		$task->type = $this->db->get_where('task_types', ['task_type'=>$task->type])->result();
		$task->comments = $this->db->get_where('tasks_comments_view', ['task_id'=>$id])->result();
		echo json_encode($task);
	}
	
	public function edit(){
		$data = json_decode(file_get_contents('php://input'));
		$station = $data->station[0]->id;
		$type = $data->type[0]->id;
		$title = $data->title;
		$description = $data->description;
		$task = [
			'station_id'=>$station,
			'type_id'=>$type,
			'title'=>$title,
			'description'=>$description,
		];
		$this->db->set($task);
		$this->db->where('id', $data->id);
		$res = $this->db->update('tasks');
		echo json_encode($res);
	}
	
	public function delete(){
		$id = json_decode(file_get_contents('php://input'));
		$this->db->where('id', $id);
		$res = $this->db->delete('tasks');
		
		echo json_encode($res);
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
	public function reviewUpdate(){
		$data = json_decode(file_get_contents('php://input'));
		$id = $data->id;
		$reviewed = $data->reviewed;
		$this->db->where('id', $id);
		$res = $this->db->update('tasks', ['reviewed'=>$reviewed]);
		echo json_encode($res);
	}
	
	public function statusUpdate(){
		$data = json_decode(file_get_contents('php://input'));
		$id = $data->id;
		$status = $data->status;
		$this->db->where('id', $id);
		if($data->status == 3){
			$res = $this->db->update('tasks', ['status_id'=>$status, 'active'=>false]);	
		}else{
			$res = $this->db->update('tasks', ['status_id'=>$status]);
		}
		
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
		
		$to = $data->user;
		$from = $data->sender;
		$task = $data->task;
		$site = $data->site;
		$text = substr(strip_tags($task->description), 0, 100);
//		echo json_encode($text);
//		die();
		$this->db->select('email');
		$to->email = $this->db->get_where('users', ['id'=>$to->id])->result()[0]->email;
		
		$this->db->select('email');
		$from->email = $this->db->get_where('users', ['id'=>$from->userId])->result()[0]->email;
		
		$this->db->where('id', $task->id);
		$res = $this->db->update('tasks', ['assigned_to'=>$to->id, 'approved'=>true, 'reviewed'=>true]);
		
		if($res = true){
			$this->db->select(['assigned']);
			$status = $this->db->get_where('tasks_view',['id'=>$task->id])->result()[0]->assigned;
			
//			//SEND EMAIL
			$this->email->from("DVT - WEB");
//			$this->email->from($sender->email, $sender->username);
			$this->email->to($to->email);
			$this->email->subject("You've got a new task!");
			$this->email->message("A new task was created by ".$task->creator." and has been assigned to you by ".$from->username.
														"\n Field of operation: ".$task->station[0]->work_station.
														"\n Task type: ".$task->type[0]->task_type." \n".
														"Title: ".$task->title."\n".
														"Description:"."\n\n".
														$text."... \n".
														"You can view it in the following link: \n".
													 	$site."/tasks/".$task->id
													 );
			$this->email->send();
			
		} else {
			$status = false;
		}
		echo $status;
	}
	
	public function taskReview(){
		$data = json_decode(file_get_contents('php://input'));
//		echo json_encode($data);
//		die();
		$from = $data->user;
		$task = $data->task;
		$site = $data->site;
		
		$this->db->where('id', $task->id);
		$res = $this->db->update('tasks', ['assigned_to'=>33, 'reviewed'=>false]);
		
		$this->db->select('email');
		$creatorEmail = $this->db->get_where("users", ['username'=>$task->creator])->result()[0]->email;
		
		if($res = true){
			//SEND EMAIL
			$this->email->from("DVT - WEB");
			$this->email->from($from->email, $from->username);
			$this->email->to('ykeren@qti.qualcomm.com');
			$this->email->cc($from->email);
			$this->email->subject($from->username." is asking you to review his task");
			$this->email->message($from->username." finished '".$task->title."' task, and asks for you confirmation! \n".
														"You can view it in the following link: \n".
													 	$site."/index.htm#/tasks/".$task->id
													 );
			$this->email->send();
			echo "Completed";
		} else{
			echo "Faliure!";
		}
	}
	
	public function taskDone(){
		$data = json_decode(file_get_contents('php://input'));
//		echo json_encode($data);
//		die();
		$from = $data->user;
		$task = $data->task;
		$site = $data->site;
		
		$this->db->where('id', $task->id);
		$res = $this->db->update('tasks', ['status_id'=>3, 'reviewed'=>true, 'active'=>false]);
		
		$this->db->select('email');
		$creatorEmail = $this->db->get_where("users", ['username'=>$task->creator])->result()[0]->email;
		
		if($res = true){
			//SEND EMAIL
			$this->email->from("DVT - WEB");
			$this->email->from($from->email, $from->username);
			$this->email->to($creatorEmail);
//			$this->email->cc("ykeren@qti.qualcomm.com");
			$this->email->subject($from->username." has completed a task you requested");
			$this->email->message($from->username." marked '".$task->title."' task as 'Completed'! \n".
														"You can view it in the following link: \n".
													 	$site."/index.htm#/tasks/".$task->id
													 );
			$this->email->send();
			echo "Completed";
		} else{
			echo "Faliure!";
		}
	}
	
	public function newComment(){
		$data = json_decode(file_get_contents('php://input'));
		$comment = $data->comment;
		$task = $this->db->get_where('tasks', ['id'=>$comment->task_id])->result()[0];
		$this->db->select('email, id, username');
		$from = $this->db->get_where("users", ['id'=>$comment->user_id])->result()[0];
		$site = $data->site;
		
//		echo json_encode($from);
		$this->db->select('email');
		$creatorEmail = $this->db->get_where("users", ['id'=>$task->creator_id])->result()[0]->email;
		$res = $this->db->insert('task_comments', $comment);
		
//		die();
		if($res = true){
			//SEND EMAIL
			$this->email->from("DVT - WEB");
			$this->email->from($from->email, $from->username);
			$this->email->to([$creatorEmail, "ykeren@qti.qualcomm.com"]);
			$this->email->cc($from->email);
			$this->email->subject($from->username." commented on a task you requested");
			$this->email->message(
														"You can view it in the following link: \n".
													 	$site."/index.htm#/tasks/".$task->id
													 );
			$this->email->send();
			echo "Completed";
		} else{
			echo "Faliure!";
		}
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