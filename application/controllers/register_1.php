<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends CI_Controller {

//public function __construct() {
//	parent::__construct();
//	$this->load->model('test');
//}
//	
public function index() {
    $data['main_content'] = 'signup_view';
	$this->load->view("includes/template", $data);
    } 
//
//function add()
//{
//    $request= json_decode(file_get_contents('php://input'), TRUE);
//    $data1=$this->test->insert_form($request);
//    if($data) {
//		echo "success";
//	}  else {
//		echo "failure";
//	}
//}
//function fetchdata()
//{
    // $data['fetchdata']=$this->ektreemodel->get_users();
    // $this->load->view('fetchangulardata',$data);
//     $result=$this->db->get('users')->result();
//     $arr_data=array();
//     $i=0;
//     foreach($result as $row)
//     {
//         $arr_data[$i]['id']=$row->user_id;
//         $arr_data[$i]['fname']=$row->ftname;
//         $arr_data[$i]['lname']=$row->lname;
//         $arr_data[$i]['emailid']=$row->emailid;
//       $i++;  
//     }
//    
//     echo json_encode($arr_data);
     

//}
}
?>