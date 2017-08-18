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
			
			$ant = $this->db->get_where('test_antennas', array('test_id'=>$id))->result();
			foreach($ant as $i => $value){
				$antenna[$i] = $value->antenna;
			}
			$tests[$key]->antennas = $antenna;
			
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
	function add_antennas($antennas)
    {
        $insertStatus = $this->db->insert('test_antennas', $antennas);
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
	
	function get_antennas($antennas){
		$antenna = array();
		foreach($antennas as $i=> $a){
			array_push($antenna, $antennas[$i]);
		}
		return $antenna;
	}
	
	function update_chips($chips){
		var_dump($chips);
//		$this->db->where('id', $chips->id);
//		$insertStatus = $this->db->update('test_chips', $chips);
//		return $insertStatus;
	}
	
	function get_test($data){
		$q = $this->db->get_where('plans', array('id'=> $data->planId))->result();
		$test =$this->db->get_where('tests', array('plan_id'=>$data->planId, 'id'=>$data->testId))->result();
		$sql = "SELECT id FROM `tests` WHERE plan_id = ?";
		$query = $this->db->query($sql, $data->planId);
		$testsIdArr = $query->result();
		$channels = $this->db->get_where('test_channels', array('test_id'=>$data->testId))->result();
		$chips = $this->db->get_where('test_chips', array('test_id'=>$data->testId))->result();
		$temps = $this->db->get_where('test_temps', array('test_id'=>$data->testId))->result();
		$antennas = $this->db->get_where('test_antennas', array('test_id'=>$data->testId))->result();

		$test[0]->channels = $channels;
		$test[0]->chips = $chips;
		$test[0]->antennas = $antennas;
		$test[0]->temps = $temps;

		$plan = array(
			'plan'=>$q,
			'test'=>$test,
		);
		return $plan;
	}
	
	function add_comment($data){
		$comment = array(
			'plan_id'=>$data->id->planId,
			'test_id'=>$data->id->testId,
			'severity'=>$data->comment->severity,
			'station'=>$data->comment->station,
			'test_name'=>$data->comment->name,
			'chip'=>$data->comment->chip,
			'details'=>$data->comment->details
		);
		$insertStatus = $this->db->insert('test_comments', $comment);
		return $insertStatus;
	}
	
	function get_comments($id){
		$comments = $this->db->get_where('test_comments', array('plan_id'=>$id))->result();
		return $comments;
	}
	
	function update_plan($data){
		foreach ($data as $testRes){
			$id = $testRes->id;
			$chipsArr = $testRes->chips;
			$tempsArr = $testRes->temps;
			$channelsArr = $testRes->channel;
			$antennasArr = $testRes->antenna;
			$planId = $testRes->plan_id;
			$test = array(
				'lineup'=>$testRes->lineup,
				'station'=>$testRes->station[0],
				'name'=>$testRes->name[0],
				'pin_from'=>$testRes->pin_from,
				'pin_to'=>$testRes->pin_to,
				'pin_step'=>$testRes->pin_step,
				'pin_additional'=>$testRes->pin_additional,
				'mcs'=>$testRes->mcs,
				'voltage'=>$testRes->voltage,
				'notes'=>$testRes->notes,
//				'plan_id'=>$testRes->plan_id
			);
			$this->db->where('id', $id);
			$testStatus = $this->db->update('tests', $test);
			foreach($chipsArr as $result){
				$chipRes = $this->db->get_where('test_chips', array('chip'=>$result))->result();
				var_dump($chipRes);
//				$chip = array(
//					'chip'=> $chipRes->chip,
//					'test_id'=>$chipRes->test_id,
//					'id'=>$chipRes->id
//				);
//			$this->db->where('test_id', $id);
//			$testStatus = $this->db->update('test_chips', $chip);
			}
			die();
			foreach($tempsArr as $result){
				$temp = array(
					'chip'=> $result,
//					'test_id'=>$id
				);
//				$this->plan_model->add_chips($chip);
			}
			foreach($channelsArr as $result){
				$channel = array(
					'chip'=> $result,
//					'test_id'=>$id
				);
//				$this->plan_model->add_chips($chip);
			}
			foreach($antennasArr as $result){
				$antenna = array(
					'chip'=> $result,
//					'test_id'=>$id
				);
//				$this->plan_model->add_chips($chip);
			}
			echo 'wow';
			
		}
	}
}
