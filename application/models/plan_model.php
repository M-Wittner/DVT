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
				$tests[$key]->xifs = $xifRes;

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
		if($data->comment->severity == 'Minor'){
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
		} elseif($data->comment->severity == 'Major'){
			$comment = array(
				'plan_id'=>$data->id->planId,
				'test_id'=>$data->id->testId,
				'severity'=>$data->comment->severity,
				'station'=>$data->comment->station,
				'details'=>$data->comment->details
			);
			$insertStatus = $this->db->insert('test_comments', $comment);
			return $insertStatus;
		}else{
			echo 'failed';
		};
	}
	
	function get_comments($id){
		$comments = $this->db->get_where('test_comments', array('plan_id'=>$id))->result();
		return $comments;
	}
	
	function update_test($data){
		$planObj = $data->plan;
		$testObj = $data->test;
//		var_dump($testObj);
//		die();
		if($testObj->station[0] == "R-CB1" || $testObj->station[0] == "R-CB2"){
			$test = array(
				'plan_id'=>$testObj->plan_id,
				'priority'=>$testObj->priority[0],
				'lineup'=>$testObj->lineup,
				'station'=>$testObj->station[0],
				'name'=>$testObj->name[0]->test_name,
				'pin_from'=>$testObj->pinFrom,
				'pin_to'=>$testObj->pinTo,
				'pin_step'=>$testObj->pinStep,
				'pin_additional'=>$testObj->pinAdd,
				'mcs'=>$testObj->mcs,
				'voltage'=>$testObj->voltage,
				'notes'=>$testObj->notes,
			);
			
			// Update antennas
			$this->db->where(array('test_id'=>$testObj->id,'plan_id'=>$testObj->plan_id,));
			$this->db->delete('test_antennas');
			foreach($testObj->antennas as $i => $antennaRes){
				$antenna = array(
					'test_id'=>$testObj->id,
					'plan_id'=>$testObj->plan_id,
					'antenna'=>$antennaRes
					);
//				die(var_dump($antennaRes));
				$this->db->replace('test_antennas', $antenna);
			}
		}elseif($testObj->station[0] == "M-CB1" || $testObj->station[0] == "M-CB2"){
			$test = array(
				'plan_id'=>$testObj->plan_id,
				'priority'=>$testObj->priority[0],
				'lineup'=>$testObj->lineup,
				'station'=>$testObj->station[0],
				'name'=>$testObj->name[0]->test_name,
				'voltage'=>$testObj->voltage,
				'notes'=>$testObj->notes,
			);
			// Update xifs
			$this->db->where(array('test_id'=>$testObj->id,'plan_id'=>$testObj->plan_id,));
			$this->db->delete('test_xifs');
			foreach($testObj->xifs as $i => $xifRes){
				$xif = array(
					'test_id'=>$testObj->id,
					'plan_id'=>$testObj->plan_id,
					'xif'=>$xifRes
					);
				$this->db->replace('test_xifs', $xif);
			}
		} else{
			echo 'Station not operetional right now';
			die();
		}
		// Update chips
		$this->db->where(array('test_id'=>$testObj->id,'plan_id'=>$testObj->plan_id,));
		$this->db->delete('test_chips');
		foreach($testObj->chips as $i => $chipRes){
			$chip = array(
				'test_id'=>$testObj->id,
				'plan_id'=>$testObj->plan_id,
				'chip'=>$chipRes->serial_number
				);
			
			$this->db->replace('test_chips', $chip);
		}
		// Update temps
		$this->db->where(array('test_id'=>$testObj->id,'plan_id'=>$testObj->plan_id,));
		$this->db->delete('test_temps');
		foreach($testObj->temps as $i => $tempRes){
			$temp = array(
				'test_id'=>$testObj->id,
				'plan_id'=>$testObj->plan_id,
				'temp'=>$tempRes
				);
			$this->db->replace('test_temps', $temp);
		}
		// Update channels
		$this->db->where(array('test_id'=>$testObj->id,'plan_id'=>$testObj->plan_id,));
		$this->db->delete('test_channels');
		foreach($testObj->channels as $i => $channelRes){
			$channel = array(
				'test_id'=>$testObj->id,
				'plan_id'=>$testObj->plan_id,
				'channel'=>$channelRes
				);
			$this->db->replace('test_channels', $channel);
		}
//		var_dump($test);
//		die();
		$this->db->where(array('id'=>$testObj->id,'plan_id'=>$testObj->plan_id));
//		$res = $this->db->delete('tests');
		$res = $this->db->update('tests', $test);
		return $res;
		
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
	
	function update_plan_status($result){
		$id = $result->id;
		$checked = $result->checked;
		$this->db->where(array('id'=>$id));
		$insertStatus = $this->db->update('plans', array('checked'=>!$checked));
		
		return !$checked;
	}
	
	function update_xif_status($result){
//		die(var_dump($result));
		$runs = $result->xif->running;
		$complete = $result->xif->completed;
		$error = $result->xif->error;
		if($runs == false && $complete == false && $error == false){
			$this->db->where(array('chip_id'=>$result->xif->chip_id, 'plan_id'=>$result->planId, 'test_id'=>$result->testId, 'id'=>$result->xif->id));
			$insertStatus = $this->db->update('test_xifs', array('running'=>true, 'completed'=>false, 'error'=>false));
		} else if($runs == true && $complete == false && $error == false){
			$this->db->where(array('chip_id'=>$result->xif->chip_id, 'plan_id'=>$result->planId, 'test_id'=>$result->testId ,'id'=>$result->xif->id));
			$insertStatus = $this->db->update('test_xifs', array('running'=>false, 'completed'=>true, 'error'=>false));
		} else if($runs == false && $complete == true && $error == false){
			$this->db->where(array('chip_id'=>$result->xif->chip_id, 'plan_id'=>$result->planId, 'test_id'=>$result->testId ,'id'=>$result->xif->id));
			$insertStatus = $this->db->update('test_xifs', array('running'=>false, 'completed'=>false ,'error'=>true));
		} else if($runs == false && $complete == false && $error == true){
			$this->db->where(array('chip_id'=>$result->xif->chip_id, 'plan_id'=>$result->planId, 'test_id'=>$result->testId ,'id'=>$result->xif->id));
			$insertStatus = $this->db->update('test_xifs', array('running'=>false, 'completed'=>false, 'error'=>false));
		} else{
			echo 'nothing';
		}
		return $result;
	}
}
