<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class plan_model extends CI_Model {
	    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function Plans() {
		$q = $this->db->get('plans');
		return $q->result();
	}
	
	function delete_plan($id){
		$q = $this->db->query("DELETE FROM `plans` WHERE id = ?", $id);
		return;
	}
	
	function get_plan($id) {
		$q = $this->db->get_where('plans', array('id'=> $id))->result();
		$tests =$this->db->get_where('tests', array('plan_id'=>$id))->result();
		$sql = "SELECT id FROM `tests` WHERE plan_id = ?";
		$query = $this->db->query($sql, $id);
		$testsIdArr = $query->result();
//		die(print_r($tests));

		
		$testsId = array();
		foreach($testsIdArr as $i => $result){
			$testsId[$i] = $result->id;
		}
		
		$arr = array();
		$testChannel = new stdClass();
		foreach($testsId as $key => $id){
			
			$ch = $this->db->get_where('test_channels', array('test_id'=>$id))->result();
			foreach($ch as $i => $value){
				$channel[$i] = $value->channel;
			}
			$tests[$key]->channels = $channel;
			
			$ant = $this->db->get_where('test_anthenas', array('test_id'=>$id))->result();
			foreach($ant as $i => $value){
				$anthena[$i] = $value->anthena;
			}
			$tests[$key]->anthenas = $anthena;
			
			$chip = $this->db->get_where('test_chips', array('test_id'=>$id))->result();
			foreach($chip as $i => $value){
				$chip[$i] = $value->chip;
			}
			$tests[$key]->chips = $chip;
			
			$temp = $this->db->get_where('test_temps', array('test_id'=>$id))->result();
			foreach($temp as $i => $value){
				$temp[$i] = $value->temp;
			}
			$tests[$key]->temps = $temp;
		}
//		$tests = (object) $tests;

		$plan = array(
			'plan'=>$q,
			'tests'=>$tests,
		);
		return $plan;
	}
	
	 function add_plan($plan)
    {
        $insertStatus = $this->db->insert('plans', $plan);
		return $insertStatus;
    }
	 function add_test($test)
    {
        $insertStatus = $this->db->insert('tests', $test);
		return $insertStatus;
    }
	function add_temps($temps)
    {
        $insertStatus = $this->db->insert('test_temps', $temps);
		return $insertStatus;
    }
	function add_channels($channels)
    {
        $insertStatus = $this->db->insert('test_channels', $channels);
		return $insertStatus;
    }
	function add_anthenas($anthenas)
    {
        $insertStatus = $this->db->insert('test_anthenas', $anthenas);
		return $insertStatus;
    }
	function add_chips($chips)
    {
        $insertStatus = $this->db->insert('test_chips', $chips);
		return $insertStatus;
    }
	
	function get_id($insertStatus){
		$insertId = $this->db->insert_id($insertStatus);
		return $insertId;
	}
	function tests_id($insertStatus){
		$insertId = $this->db->insert_id($insertStatus);
		return $insertId;
	}
	
	function get_station($stations) {
		foreach($stations as $i=> $station) {
			$station = $stations[$i];
			return $station;
		}
	}
	
	function get_name($name){
		foreach($name as $i=> $testName){
			$testName = $name[$i];
			return $testName;
		}
	}
	
	function get_chips($chips){
		$chipSN = array();
		foreach($chips as $i=>$sn){
			array_push($chipSN, $chips[$i]);
		};
		return $chipSN;
	}
	
	function get_temps($temps){
		$temp = array();
		foreach($temps as $i=> $t){
			array_push($temp, $temps[$i]);
		}
		return $temp;
	}
	
	function get_channels($channels){
		$channel = array();
		foreach($channels as $i=> $c){
			array_push($channel, $channels[$i]);
		}
		return $channel;
	}
	
	function get_anthenas($anthenas){
		$anthena = array();
		foreach($anthenas as $i=> $a){
			array_push($anthena, $anthenas[$i]);
		}
		return $anthena;
	}
	
	function get_test($planId, $testId){
		$test = $this->db->get_where('tests', $testId)->result();
		return $test;
	}
}
