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
		

		
		$testsId = array();
		foreach($testsIdArr as $i => $result){
			$testsId[$i] = $result->id;
		}
//		die(var_dump($tests));
		foreach($testsId as $key => $id){
			if($tests[$key]->station == 'M-CB1' || $tests[$key]->station == 'M-CB2') {
				$xifRes = $this->db->get_where('test_xifs', array('test_id'=>$id))->result();
				foreach($xifRes as $i => $value){
					$xif[$i] = $value->xif;
				}
				$tests[$key]->xifs = $xif;

			} else if($tests[$key]->station == 'R-CB1' || $tests[$key]->station == 'R-CB2'){
				$ant = $this->db->get_where('test_antennas', array('test_id'=>$id))->result();
				foreach($ant as $i => $value){
					$antenna[$i] = $value->antenna;
				}
				$tests[$key]->antennas = $antenna;	
			}
			$ch = $this->db->get_where('test_channels', array('test_id'=>$id))->result();
				foreach($ch as $i => $value){
					$channel[$i] = $value->channel;
				}
			$tests[$key]->channels = $channel;
			
			$chip = $this->db->get_where('test_chips', array('test_id'=>$id))->result();
//			foreach($chip as $i => $value){
//				$chip[$i] = $value->chip;
//				
//			}
			
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
	function add_xifs($xifs)
    {
        $insertStatus = $this->db->insert('test_xifs', $xifs);
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
		$chipsArr = $data->chips;
		$tempsArr = $data->temps;
		$channelsArr = $data->channels;
		$antennasArr = $data->antennas;
		$planId = $data->test->plan_id;
		$testId = $data->test->id;
		$newChips = array();
		$chips = $this->db->get_where('test_chips', array('test_id'=>$testId))->result();
//		die(var_dump($chips));
		$test = array(
			'lineup'=>$data->test->lineup,
			'station'=>$data->test->station,
			'name'=>$data->test->name,
			'pin_from'=>$data->test->pin_from,
			'pin_to'=>$data->test->pin_to,
			'pin_step'=>$data->test->pin_step,
			'pin_additional'=>$data->test->pin_additional,
			'mcs'=>$data->test->mcs,
			'voltage'=>$data->test->voltage,
			'notes'=>$data->test->notes,
//				'plan_id'=>$data->plan_id
		);
		die(var_dump($chipsArr));
		$this->db->where('id', $testId);
		$testStatus = $this->db->update('tests', $test);
		foreach($chipsArr as $result){
			$chip = array(
				'chip'=>$result,
//				'plan_id'=>$planId,
//				'test_id'=>$testId
			);
			array_push($newChips, $chip);
		}
//		die(var_dump($chips));
		$this->db->where(array('plan_id'=>$planId, 'test_id'=>$testId));
		$insertStatus = $this->db->replace('test_chips', $newChips);
		foreach($tempsArr as $result){
//			var_dump($result);
			$temp = array(
				'temp'=> $result,
				'plan_id'=>$planId,
				'test_id'=>$testId
			);
			$insertStatus = $this->db->replace('test_temps', $temp);
		}
		die();
		foreach($channelsArr as $result){
			$channel = array(
				'channel'=> $result,
				'plan_id'=>$planId,
				'test_id'=>$testId
			);
			$insertStatus = $this->db->replace('test_channels', $channel);
		}
		foreach($antennasArr as $result){
			$antenna = array(
				'antenna'=> $result,
				'test_id'=>$testId,
				'plan_id'=>$planId
			);
			$insertStatus = $this->db->replace('test_antennas', $antenna);
		}
		return $insertStatus;
	}
	
	function update_chip_status($result){
		$runs = $result->chip->running;
		$complete = $result->chip->completed;
		$error = $result->chip->error;
//		die(var_dump($result));
		if($runs == false && $complete == false && $error == false){
			$this->db->where(array('chip'=>$result->chip->chip, 'plan_id'=>$result->planId, 'test_id'=>$result->testId));
			$insertStatus = $this->db->update('test_chips', array('running'=>true, 'completed'=>false, 'error'=>false));
		} else if($runs == true && $complete == false && $error == false){
			$this->db->where(array('chip'=>$result->chip->chip, 'plan_id'=>$result->planId, 'test_id'=>$result->testId));
			$insertStatus = $this->db->update('test_chips', array('running'=>false, 'completed'=>true, 'error'=>false));
		} else if($runs == false && $complete == true && $error == false){
			$this->db->where(array('chip'=>$result->chip->chip, 'plan_id'=>$result->planId, 'test_id'=>$result->testId));
			$insertStatus = $this->db->update('test_chips', array('running'=>false, 'completed'=>false ,'error'=>true));
		} else if($runs == false && $complete == false && $error == true){
			$this->db->where(array('chip'=>$result->chip->chip, 'plan_id'=>$result->planId, 'test_id'=>$result->testId));
			$insertStatus = $this->db->update('test_chips', array('running'=>false, 'completed'=>false, 'error'=>false));
		} else{
			echo 'nothing';
		}
		return $result;
	}
}
