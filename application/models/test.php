<?php
class Test extends CI_Model {
	public function insert_form($request) {
		$insertStatus=$this->db->insert('registration',array('firstName'=>$request['fname'],'lastname'=>$request['lname'],'emailid'=>$request['email'],'password'=>$request['password']));
    return $insertStatus;
	}
}