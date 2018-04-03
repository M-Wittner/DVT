<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/x-www-form-urlencoded');
class Test_Model extends CI_Model{
	
	public $priority;
	public $station_id;
	public $testName_id;
	public $notes;
	public $plan_id;
	public $user_id;
	
	function __construct(){
		// Call the Model constructor
		parent::__construct();
	}
    
}
?>