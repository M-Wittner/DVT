<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Content-Length, X-Requested-With");
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chips extends CI_Controller {
	public function index() {
		$data = $this->db->get('chips');
		$result = $data->result();
		echo json_encode($result);
//			foreach ($result as $value) {
//				echo $value->serial_num;
//				echo '<br/>';
//				array_push($array, $value->serial_num);
//			}
//		var_dump($array);
	}
}
?>
