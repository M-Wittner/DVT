<?php
class User_model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
	//get the username & password from tbl_usrs
    function get_user($usr, $pwd) {
		$this->db->select('username, password');
//		$this->db->from('users');
		$this->db->where('username', $usr, 'password', md5($pwd));
		$q = $this->db->get('users');
		return $q->result();
    }

    //insert into user table
    function insertUser($data)
    {
       $this->db->insert('users', $data);
		return;
    }

//    //send verification email to user's email id
//    function sendEmail($to_email)
//    {
//        $from_email = 'team@mydomain.com'; //change this to yours
//        $subject = 'Verify Your Email Address';
//        $message = 'Dear User,<br /><br />Please click on the below activation link to verify your email address.<br /><br /> http://www.mydomain.com/user/verify/' . md5($to_email) . '<br /><br /><br />Thanks<br />Mydomain Team';
//        
//        //configure email settings
//        $config['protocol'] = 'smtp';
//        $config['smtp_host'] = 'ssl://smtp.mydomain.com'; //smtp host name
//        $config['smtp_port'] = '465'; //smtp port number
//        $config['smtp_user'] = $from_email;
//        $config['smtp_pass'] = '********'; //$from_email password
//        $config['mailtype'] = 'html';
//        $config['charset'] = 'iso-8859-1';
//        $config['wordwrap'] = TRUE;
//        $config['newline'] = "\r\n"; //use double quotes
//        $this->email->initialize($config);
//        
//        //send mail
//        $this->email->from($from_email, 'Mydomain');
//        $this->email->to($to_email);
//        $this->email->subject($subject);
//        $this->email->message($message);
//        return $this->email->send();
//    }
//    
//    //activate user account
//    function verifyEmailID($key)
//    {
//        $data = array('status' => 1);
//        $this->db->where('md5(email)', $key);
//        return $this->db->update('users', $data);
//    }
}
?>
