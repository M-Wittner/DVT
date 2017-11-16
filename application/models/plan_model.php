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
//		$this->db->group_by('station');
		$tests =$this->db->get_where('tests', array('plan_id'=>$id))->result();
		foreach($tests as $key => $res){
			if($res->station == 'M-CB1' || $res->station == 'M-CB2') {
				$xifRes = $this->db->get_where('test_xifs', array('test_id'=>$res->id))->result();
				$res->xifs = $xifRes;

			} else if($res->station == 'R-CB1' || $res->station == 'R-CB2'){
				$ant = $this->db->get_where('test_antennas', array('test_id'=>$res->id))->result();
				foreach($ant as $i => $value){
					$antenna[$i] = $value->antenna;
				}
				$res->antennas = $antenna;	
			}
			
			$ch = $this->db->get_where('test_channels', array('test_id'=>$res->id))->result();
//				foreach($ch as $i => $value){
//					$channel[$i] = $value->channel;
////					var_dump($channel[$i]);
//				}
			$res->channels = $ch;
//			var_dump($ch);
			
			$chip = $this->db->get_where('test_chips', array('test_id'=>$res->id))->result();
			$i = 0;
			foreach($chip as $value){
				if($value->completed == true){
					$i++;
//					print_r('yayy!!!!');
				}
			}
//			print_r('chip!!!!!!!!!');
//			var_dump(count($chip));
			if($i == count($chip)){
				$res->status = 'Completed';
			} elseif($i < count($chip)){
				$res->status = 'In Progress';
			}
//			die();
			
			$res->chips = $chip;
			$temp = $this->db->get_where('test_temps', array('test_id'=>$res->id))->result();
			foreach($temp as $i => $value){
				$temp[$i] = $value->temp;
			}
			$res->temps = $temp;
			$res->stations = $this->db->get_where('params_stations', array('station'=>$res->station))->result();
			
			$res->comments = $this->db->get_where('test_comments', array('test_id'=>$res->id))->result();
//			print_r($chip);
		}
//		die(var_dump($tests));
//		$tests = (object) $tests;
		$plan = array(
			'plan'=>$q,
			'tests'=>$tests,
		);
		return $plan;
	}
	
	function edit_test($id) {
		$plan = $this->db->get_where('plans', array('id'=> $id->planId))->result();
		$test =$this->db->get_where('tests', array('plan_id'=>$id->planId, 'id'=>$id->testId))->result();
		
		$test = $test[0];
//		die(var_dump($test));
		if($test->station == 'M-CB1' || $test->station == 'M-CB2') {
			$xifRes = $this->db->get_where('test_xifs', array('test_id'=>$id->testId))->result();
			$test->xifs = $xifRes;

		} else if($test->station == 'R-CB1' || $test->station == 'R-CB2'){
			$ant = $this->db->get_where('test_antennas', array('test_id'=>$id->testId))->result();
			if(isset($ant)){
				foreach($ant as $i => $value){
					$antenna[$i] = $value->antenna;
					}
					$test->antennas = $ant;	
			} else{
				$ant = null;
			}
		}
		if($test->station !='TalynM+A'){
			$ch = $this->db->get_where('test_channels', array('test_id'=>$id->testId))->result();
//			die(var_dump($ch));
			if(isset($ch)){
				foreach($ch as $i => $value){
						$channel[$i] = $value->channel;
					}
				$test->channels = $ch;
			}else{
				$ch = null;
			}
		}

		$chip = $this->db->get_where('test_chips', array('test_id'=>$id->testId))->result();
		$test->chips = $chip;

		$temp = $this->db->get_where('test_temps', array('test_id'=>$id->testId))->result();
		foreach($temp as $i => $value){
			$temp[$i] = $value->temp;
		}
		$test->temps = $temp;

		return $test;
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
		$comments = $this_db->get_where('test_comments', array('test_id'=>$data->testId))->result();
		die(var_dump($comments));

		$test[0]->channels = $channels;
		$test[0]->chips = $chips;
		$test[0]->antennas = $antennas;
		$test[0]->temps = $temps;
		$test[0]->comments = $comments;

		$plan = array(
			'plan'=>$q,
			'test'=>$test,
		);
		return $plan;
	}
	
	function add_comment($data){
//		die(var_dump($data));
		if($data->comment->severity == 'Minor'){
			$comment = array(
				'plan_id'=>$data->id->planId,
				'test_id'=>$data->id->testId,
				'author'=>$data->comment->author,
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
				'author'=>$data->comment->author,
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
//		$planObj = $data->plan;
		$testObj = $data->test;
//		var_dump($testObj->antenna);
//		die();
		if(isset($testObj->notes)){
			$notes = $testObj->notes;
		} else {
			$notes = null;
		}
		if(isset($testObj->pinAdd)){
			$pinAdd = $testObj->pinAdd;
		} else {
			$pinAdd = null;
		}
		if($testObj->station[0]->station == "R-CB1" || $testObj->station[0]->station == "R-CB2"){
			$test = array(
				'plan_id'=>$testObj->plan_id,
				'priority'=>$testObj->priority[0],
				'lineup'=>$testObj->lineup,
				'station'=>$testObj->station[0]->station,
				'name'=>$testObj->name[0]->test_name,
				'pin_from'=>$testObj->pinFrom,
				'pin_to'=>$testObj->pinTo,
				'pin_step'=>$testObj->pinStep,
				'pin_additional'=>$pinAdd,
				'mcs'=>$testObj->mcs,
				'voltage'=>$testObj->voltage,
				'notes'=>$notes,
			);
			
			// Update antennas
			$this->db->where(array('test_id'=>$testObj->id));
			$this->db->delete('test_antennas');
			foreach($testObj->antenna as $i => $antennaRes){
				$antenna = array(
					'test_id'=>$testObj->id,
					'plan_id'=>$testObj->plan_id,
					'antenna'=>$antennaRes
					);
//				var_dump($antennaRes);
				$this->db->replace('test_antennas', $antenna);
			}
//			die();
		}elseif($testObj->station[0]->station == "M-CB1" || $testObj->station[0]->station == "M-CB2"){
			$test = array(
				'plan_id'=>$testObj->plan_id,
				'priority'=>$testObj->priority[0],
				'lineup'=>$testObj->lineup,
				'station'=>$testObj->station[0]->station,
				'name'=>$testObj->name[0]->test_name,
				'voltage'=>$testObj->voltage,
				'notes'=>$notes,
			);
//------------ PTAT/ABS/Vgb+TEMP and TalynM+A station test -------------
		} elseif($testObj->station[0]->station == 'PTAT/ABS/Vgb+TEMP'|| $testObj->station[0]->station == 'TalynM+A') {
			$test = array(
				'priority'=>$testObj->priority[0],
				'lineup'=>'/',
				'station'=>$testObj->station[0]->station,
				'name'=>$testObj->name[0]->test_name,
				'notes'=>$notes,
				'plan_id'=>$testObj->plan_id
			);
//			$chipsArr = $testObj->chips;
//			$insertTest = $this->plan_model->add_test($test);
//			foreach($chipsArr as $result){
//				$chip = array(
//					'chip'=>$result->serial_number,
//					'plan_id'=>$planId,
//					'test_id'=>$testId
//				);
//			$insertChip = $this->plan_model->add_chips($chip);
//			break;
//			}
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
			$insertStatus = $this->db->replace('test_chips', $chip);
			$insertId = $this->db->insert_id($insertStatus);
//			var_dump($insertId);
			if($testObj->station[0]->station == "M-CB1" || $testObj->station[0]->station == "M-CB2"){
				// Update xifs
				$this->db->where(array('test_id'=>$testObj->id,'plan_id'=>$testObj->plan_id,));
				$this->db->delete('test_xifs');
				foreach($testObj->chips as $i => $chipRes){
//					var_dump($testObj->xif);
					foreach($testObj->xif as $xifRes){
//						var_dump($xifRes);
//						die();
						$xif = array(
							'test_id'=>$testObj->id,
							'plan_id'=>$testObj->plan_id,
							'chip_id'=>$chipRes->id,
							'chip'=>$chipRes->serial_number,
							'xif'=>$xifRes
							);
						$this->db->replace('test_xifs', $xif);
						}
//					die();
				}
			}
		}
		if($testObj->station[0]->station != 'TalynM+A'){
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
//				die(var_dump($channelRes));
				$channel = array(
					'test_id'=>$testObj->id,
					'plan_id'=>$testObj->plan_id,
					'channel'=>$channelRes->channel
					);
				$this->db->replace('test_channels', $channel);
			}
		}
//		var_dump($test);
//		die();
		$this->db->where(array('id'=>$testObj->id,'plan_id'=>$testObj->plan_id));
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
		$id = $result->plan->id;
		$checked = $result->plan->checked;
		$user = $result->user;
		$this->db->where(array('id'=>$id));
//		die(print($checked));
		if($checked == '0'){
			$insertStatus = $this->db->update('plans', array('checked'=>true, 'checked_by'=>$user->username));
			return true;
		} else {
			$insertStatus = $this->db->update('plans', array('checked'=>false, 'checked_by'=>null));
			return false;
		}
	}
	
	function update_xif_status($result){
//		die(var_dump($result));
		$runs = $result->running;
		$complete = $result->completed;
		$error = $result->error;
		if($runs == false && $complete == false && $error == false){
			$this->db->where(array('id'=>$result->id));
			$insertStatus = $this->db->update('test_xifs', array('running'=>true, 'completed'=>false, 'error'=>false));
		} else if($runs == true && $complete == false && $error == false){
			$this->db->where(array('id'=>$result->id));
			$insertStatus = $this->db->update('test_xifs', array('running'=>false, 'completed'=>true, 'error'=>false));
		} else if($runs == false && $complete == true && $error == false){
			$this->db->where(array('id'=>$result->id));
			$insertStatus = $this->db->update('test_xifs', array('running'=>false, 'completed'=>false ,'error'=>true));
		} else if($runs == false && $complete == false && $error == true){
			$this->db->where(array('id'=>$result->id));
			$insertStatus = $this->db->update('test_xifs', array('running'=>false, 'completed'=>false, 'error'=>false));
		} else{
			echo 'nothing';
		}
		return $this->db->get_where('test_xifs', array('id'=>$result->id))->result();
	}
	
	function delete_test($id){
		$q = $this->db->query("DELETE FROM `tests` WHERE id = ?", $id);
		return;
	}	
	
	function delete_comment($id){
		$q = $this->db->query("DELETE FROM `test_comments` WHERE id = ?", $id);
		return;
	}
}
