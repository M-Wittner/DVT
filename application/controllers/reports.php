<?php
//header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller {
	public function __construct() {

	parent::__construct();
	$this->load->helper(array('form','url'));
	$this->load->library(array('session'));
	$this->load->database('');
	$this->load->model('reportModel');
    }

	function index() {
		
//		$this->fetchdata();
//		$data['main_content'] = 'Reports/index';
//		$this->load->view('users', $this->data);
		$result = $this->reportModel->Reports();
		echo json_encode($result);	
	}

	function All() {
		$reports = $this->reportModel->Reports();
		echo json_encode($reports);
	}
	
	function Create() {
		$data['main_content'] = 'Reports/create';
		$this->load->view('includes/template', $data);
	}
	
	function Show() {
		$this->db->where('id', $name);
		return $q->result();
	}
	
}

//class Report extends CI_Controller {
//public function app() {
//	$this->fetchdata();
//	$this->load->view("app", $this->data);
//}
//public function add()
//{
//    $request= json_decode(file_get_contents('php://input'), TRUE);
//    $data1=$this->ektreemodel->insert_form($request);
//     $this->fetchdata();   
//}
//public function fetchdata()
//{
//    // $data['fetchdata']=$this->ektreemodel->get_users();
//    // $this->load->view('fetchangulardata',$data);
//     $result=$this->db->get('reports')->result();
//     $arr_data=array();
//     $i=0;
//     foreach($result as $row)
//     {
//         $arr_data[$i]['report_id']=$row->report_id;
//         $arr_data[$i]['fname']=$row->fname;
//         $arr_data[$i]['lname']=$row->lname;
//         $arr_data[$i]['emailid']=$row->emailid;
//       $i++;  
//     }
//    
//     echo json_encode($arr_data);
//     
// 
//}
//}