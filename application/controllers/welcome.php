<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
public function users()
{
     $this->fetchdata();
     $this->load->view("users",$this->data);
     

}
public function add()
{
    $request= json_decode(file_get_contents('php://input'), TRUE);
    $data1=$this->ektreemodel->insert_form($request);
     $this->fetchdata();   
}
public function fetchdata()
{
    // $data['fetchdata']=$this->ektreemodel->get_users();
    // $this->load->view('fetchangulardata',$data);
     $result=$this->db->get('registration')->result();
     $arr_data=array();
     $i=0;
     foreach($result as $row)
     {
         $arr_data[$i]['user_id']=$row->user_id;
         $arr_data[$i]['firstname']=$row->firstname;
         $arr_data[$i]['lastname']=$row->lastname;
         $arr_data[$i]['emailid']=$row->emailid;
       $i++;  
     }
    
     echo json_encode($arr_data);
     

}
}
?>