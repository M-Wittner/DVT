<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class plan_model extends CI_Model {
	function Plans() {
		$q = $this->db->get('plans');
		return $q->result();
	}
	
	 function insert_plan($plan)
    {
        $insertStatus = $this->db->insert('plans', $plan);
		return $insertStatus;
    }
	
	function get_id($insertStatus){
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

    public function Update($plan)
    {
        if(is_object($plan)){
            $this->db->where('id', $plan->id);
            return $this->db->update('plans', $plan);
        }
    }

    public function Delete($plan)
    {
        if(isset($plan->id) && !is_null($plan->id))
        {
            $this->db->where('id', $plan->id);
            return $this->db->delete('plans');
        }
    }

    public function Create($plan)
    {
        if(is_object($plan))
        {
            $this->db->insert('plans', $plan);
        }
    }
}
