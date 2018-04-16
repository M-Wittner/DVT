<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class plan_model extends CI_Model {
	    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
//	function Plans() {
//		$q = $this->db->get('plans')->result();
//		return $q;
//	}
	
	function delete_plan($id){
		$q = $this->db->query("DELETE FROM `plans` WHERE id = ?", $id);
		return;
	}
	
	function get_plan($id) {
		$q = $this->db->get_where('plans', array('id'=> $id))->result();
		foreach ($q as $plan){
			$plan->tests =$this->db->get_where('tests', array('plan_id'=>$id))->result();
			foreach($plan->tests as $test){
				if($test->station == 'M-CB1' || $test->station == 'M-CB2') {
					$xifRes = $this->db->get_where('test_xifs', array('test_id'=>$test->id))->result();
					$test->xifs = $xifRes;

				} else if($test->station == 'R-CB1' || $test->station == 'R-CB2'){
					$ant = $this->db->get_where('test_antennas', array('test_id'=>$test->id))->result();
					$antenna = array();
					foreach($ant as $value){
						array_push($antenna, $value->antenna);
					}
					$test->antennas = $antenna;	
				}

				$ch = $this->db->get_where('test_channels', array('test_id'=>$test->id))->result();
	//				foreach($ch as $i => $value){
	//					$channel[$i] = $value->channel;
	////					var_dump($channel[$i]);
	//				}
				$test->channels = $ch;
	//			var_dump($ch);

				$chips = $this->db->get_where('test_chips', array('test_id'=>$test->id))->result();
				$c = 0;
				$e = 0;
				$r = 0;
				foreach($chips as $value){
					if($value->completed == true){
						$c++;
					}elseif($value->error == true){
						$e++;
					}elseif($value->running == true){
						$r++;
					}
					if($value->results_path == null){
						if($test->station == 'R-CB1' || $test->station == 'R-CB2') {
							$path = "\\\\filer4\\fileserver\Projects\dvt\Results\\test_results\TalynA\TalynA_YA591-H511_Flip_Chip_QCA6425_B0_".$value->serial_number;
//							print_r($path);
							$this->db->where('id', $value->id);
							$this->db->set('results_path', $path);
							$this->db->update('test_chips');
						} else if($test->station == 'M-CB1' || $test->station == 'M-CB2'){
							$path = "\\\\filer4\\fileserver\Projects\dvt\Results\test_results\TalynM\TalynM_YA591-H2_Flip_Chip_QCA6425_A0_".$value->serial_number;
							$this->db->where('id', $value->id);
							$this->db->set('results_path', $path);
							$this->db->update('test_chips');
						}
					}
				}
				$test->chips = $chips;
//				$test->mChips = $mChips;
				$this->db->order_by('temp', 'ASC');
				$temp = $this->db->get_where('test_temps', array('test_id'=>$test->id))->result();
//				foreach($temp as $i => $value){
//					$temp[$i] = $value->temp;
//				}
				$test->temps = $temp;
				$test->station = $this->db->get_where('params_stations', array('station'=>$test->station))->result();

				$test->comments = $this->db->get_where('test_comments', array('test_id'=>$test->id))->result();
			}	
		}
		return $plan;
	}
	
	function edit_test($id) {
//		die(var_dump($id));
//		$plan = $this->db->get_where('plans', array('id'=> $id->planId))->result();
		$test = $this->db->get_where('tests', array('id'=>$id->testId))->result();
		if(!isset($test[0])){
			$test = $this->db->get_where('tests_view_new',array('id'=>$id->testId))->result()[0];
			$test->station = $this->db->get_where('params_stations', ['id'=>$test->station_id])->result();
			$test->name = $this->db->get_where('params_test_names', ['id'=>$test->test_name_id])->result();
			$test->flag = 1;
			$chips = $this->db->get_where('test_chips_view', ['test_id'=>$id->testId])->result();
//			echo json_encode($chips);
//			die();
			$test->chips = array();
			foreach($chips as $key=>$chip){
				switch($test->station_id){
					case 1:
					case 2:
						$data = array(
							'pair_id'=>$chip->id,
							'chip_id'=>$chip->chip_r_id,
							'chip_sn'=>$chip->chip_r_sn,
							'chip_process_abb'=>$chip->corner_r,
							'chip_type_id'=>$chip->chip_r_type_id
						);
						break;
					case 3:
					case 4:
						$data = array(
							'pair_id'=>$chip->id,
							'chip_id'=>$chip->chip_m_id,
							'chip_sn'=>$chip->chip_m_sn,
							'chip_process_abb'=>$chip->corner_m,
							'chip_type_id'=>$chip->chip_m_type_id
						);
						break;
					case 5:
						$data = new stdClass();
						$data->chip_r[$key] = array(
							'pair_id'=>$chip->id,
							'chip_sn'=>$chip->chip_r_sn,
							'chip_process_abb'=>$chip->corner_r,
							'chip_type_id'=>$chip->chip_r_type_id,
							'pair_id'=>$chip->pair_id,
						);
						$data->chip_m[$key] = array(
							'pair_id'=>$chip->id,
							'chip_sn'=>$chip->chip_m_sn,
							'chip_process_abb'=>$chip->corner_m,
							'chip_type_id'=>$chip->chip_m_type_id,
							'pair_id'=>$chip->pair_id,
						);
						break;
					}
					array_push($test->chips, $data);
				}

			$params = $this->db->get_where('test_params_view', ['test_id'=>$test->id])->result();
			foreach($params as $param){
				$name = $param->param_name;
				if(!isset($test->$name)){
					$test->$name = array();
				}
				array_push($test->$name, $param->param_value);
			}
		} else{
			$test = $test[0];
			$test->flag = 0;
			$test->name = $this->db->get_where('params_test_names', array('id'=>$test->test_name_id, 'station'=>$test->station[0]))->result();
			$test->station = $this->db->get_where('params_stations', array('id'=>$test->station_id))->result();
//			if($test->station[0]->station == 'M-CB1' || $test->station[0]->station == 'M-CB2') {
//				$xifRes = $this->db->get_where('test_xifs', array('test_id'=>$id->testId))->result();
//				$test->xifs = $xifRes;
//
//			} else 
				if($test->station[0]->station == 'R-CB1' || $test->station[0]->station == 'R-CB2'){
				$test->antennas = array();
				$ant = $this->db->get_where('test_antennas', array('test_id'=>$id->testId))->result();
				if(isset($ant)){
					foreach($ant as $value){
						array_push($test->antennas, $value->antenna);
						}
				} else{
					$ant = null;
				}
			}
			$test->channels = array();
			if($test->station[0]->station !='TalynM+A'){
				$this->db->select('channel');
				$ch = $this->db->get_where('test_channels', array('test_id'=>$id->testId))->result();
				if(isset($ch)){
					foreach($ch as $value){
							array_push($test->channels, $value->channel);
						}
				}else{
					$ch = null;
				}
			}

			$test->chips = $this->db->get_where('test_chips', array('test_id'=>$id->testId))->result();

			$temp = $this->db->get_where('test_temps', array('test_id'=>$id->testId))->result();
			foreach($temp as $i => $value){
				$temp[$i] = $value->temp;
			}
			$test->temps = $temp;
		}
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
//		echo json_encode($data);
//		die();
		if(isset($data->comment->chip[0]->pair_id)){
			$pairId = $data->comment->chip[0]->pair_id;
		}else{
			$pairId = null;
		}
		$comment = array(
			'test_id'=>$data->id->testId,
			'user_id'=>$data->comment->userId,
			'severity'=>$data->comment->severity,
			'config_id'=>$pairId,
			'comment'=>$data->comment->details,
		);
		$status = $this->db->insert('test_comments_new', $comment);
		return $status;
	}
	
	function get_comments($id){
		$comments = $this->db->get_where('test_comments', array('plan_id'=>$id))->result();
		return $comments;
	}
	
	function config_params($test){
		$test_params = $this->db->get('test_params')->result();
		$paramsArr = array();
		foreach($test_params as $param){
			$paramsArr[$param->param_name] = $param->param_id;
		}
		$params = array();
		foreach($test as $param=>$values){
			$d = array_key_exists($param, $paramsArr);
			if($d == true){
				if(is_array($values)){
					foreach($values as $value){
						$data = array(
							'plan_id'=>$test->plan_id,
							'test_id'=>$test->id,
							'param_id'=>$paramsArr[$param],
							'param_value_id'=>$value,
						);
						array_push($params, $data);
					}
				}else{
					$data = array(
						'plan_id'=>$test->plan_id,
						'test_id'=>$test->id,
						'param_id'=>$paramsArr[$param],
						'param_value_id'=>$values,
					);
					array_push($params, $data);
				}
			}
		} 
		return $params;
	}
	
	function update_test($test){
		$this->db->delete('test_chips_new', ['test_id'=>$test->id, 'plan_id'=>$test->plan_id]);
		$chips = array();
		switch($test->station[0]->id){
			//R STATIONS
			case 1:
			case 2:
				$updatedTest = array(
					'priority'=>$test->priority[0],
					'work_station_id'=>$test->station[0]->id,
					'test_name_id'=>$test->name[0]->id,
					'a_lineup'=>$test->a_lineup,
					'notes'=>$test->notes,
				);
				foreach($test->chips as $chip){
					$data = array(
						'plan_id'=>$test->plan_id,
						'test_id'=>$test->id,
						'chip_r_type_id'=>$chip->chip_type_id,
						'chip_r_id'=>$chip->chip_id,
					);
					array_push($chips, $data);
				}
				break;
			//M STATIONS
			case 3:
			case 4:
				$updatedTest = array(
					'priority'=>$test->priority[0],
					'work_station_id'=>$test->station[0]->id,
					'test_name_id'=>$test->name[0]->id,
					'm_lineup'=>$test->m_lineup,
					'notes'=>$test->notes,
				);
				foreach($test->chips as $chip){
					$data = array(
						'plan_id'=>$test->plan_id,
						'test_id'=>$test->id,
						'chip_m_type_id'=>$chip->chip_type_id,
						'chip_m_id'=>$chip->chip_id,
					);
					array_push($chips, $data);
				}
				break;
			//TalynM+A STATION
			case 5:
				$updatedTest = array(
					'priority'=>$test->priority[0],
					'work_station_id'=>$test->station[0]->id,
					'test_name_id'=>$test->name[0]->id,
					'a_lineup'=>$test->a_lineup,
					'm_lineup'=>$test->m_lineup,
					'notes'=>$test->notes,
				);
				foreach($test->chips as $key=>$chip){
					$data = array(
						'plan_id'=>$test->plan_id,
						'test_id'=>$test->id,
						'chip_r_type_id'=>$chip->chip_r[0]->chip_type_id,
						'chip_r_id'=>$chip->chip_r[0]->chip_id,
						'chip_m_type_id'=>$chip->chip_m[0]->chip_type_id,
						'chip_m_id'=>$chip->chip_m[0]->chip_id,
						'pair_id'=>$key+1,
					);
					array_push($chips, $data);
				}
				break;
		}
//		echo json_encode($chips);
//		die();
		$status = new stdClass();
		
		$this->db->set($updatedTest);
		$this->db->where('id', $test->id);
		$status->test = $this->db->update('tests_new');
		
		$status->chips = $this->db->insert_batch('test_chips_new', $chips);
		
		$params = $this->config_params($test);
		$this->db->delete('test_config', ['test_id'=>$test->id, 'plan_id'=>$test->plan_id]);
		$status->params = $this->db->insert_batch('test_config', $params);
		
		return $status;
	}
	
	function update_test_old($data){
		$testObj = $data->test;

		if(isset($testObj->notes)){
			$notes = $testObj->notes;
		} else {
			$notes = null;
		}
		if($testObj->station[0]->station == "R-CB1" || $testObj->station[0]->station == "R-CB2"){
			
			$test = array(
				'priority'=>$testObj->priority[0],
				'lineup'=>$testObj->a_lineup,
				'work_station_id'=>$testObj->station[0]->station,
				'test_name_id'=>$testObj->name[0]->id,
				'notes'=>$notes,
			);
			
			// Update antennas
			$this->db->where(array('test_id'=>$testObj->id));
			$this->db->delete('test_antennas');
			foreach($testObj->antennas as $i => $antennaRes){
				$antenna = array(
					'test_id'=>$testObj->id,
					'plan_id'=>$testObj->plan_id,
					'antenna'=>$antennaRes
					);
//				var_dump($antennaRes);
				$this->db->replace('test_antennas', $antenna);
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
//			die(var_dump($testObj->channel));
			foreach($testObj->channels as $i => $channelRes){
//				die(var_dump($channelRes));
				$channel = array(
					'test_id'=>$testObj->id,
					'plan_id'=>$testObj->plan_id,
					'channel'=>$channelRes
					);
//				var_dump($channelRes);
				$this->db->replace('test_channels', $channel);
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
			if($testObj->name[0]->test_name != 'Temp-Calibration' || $testObj->name[0]->test_name != 'PTAT Calibration'){
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
	//			die(var_dump($testObj->channel));
				foreach($testObj->channels as $i => $channelRes){
	//				die(var_dump($channelRes));
					$channel = array(
						'test_id'=>$testObj->id,
						'plan_id'=>$testObj->plan_id,
						'channel'=>$channelRes
						);
	//				var_dump($channelRes);
					$this->db->replace('test_channels', $channel);
				}					
			}
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
		} else{
			echo 'Station not operetional right now';
			die();
		}
		// Update chips
		$this->db->where(array('test_id'=>$testObj->id,'plan_id'=>$testObj->plan_id,));
		$this->db->delete('test_chips');
		foreach($testObj->chips as $i => $chipRes){
//			die(var_dump($chipRes));
			$chip = array(
				'test_id'=>$testObj->id,
				'plan_id'=>$testObj->plan_id,
				'serial_number'=>$chipRes->serial_number,
				'chip'=>$chipRes->chip,
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
					foreach($testObj->xifs as $xifRes){
//						var_dump($xifRes);
//						die();
						$xif = array(
							'test_id'=>$testObj->id,
							'plan_id'=>$testObj->plan_id,
							'chip_id'=>$chipRes->id,
							'chip'=>$chipRes->serial_number,
							'xif'=>$xifRes->xif
							);
						$this->db->replace('test_xifs', $xif);
						}
//					die();
				}
			}
		}
//		if($testObj->station[0]->station != 'TalynM+A'){
//		}
//		var_dump($test);
//		die();
		$this->db->where(array('id'=>$testObj->id,'plan_id'=>$testObj->plan_id));
		$res = $this->db->update('tests', $test);
		return $res;
		
	}
	
	function update_chip_status($result){
//		echo json_encode($result);
//		die();
		if(!isset($result->chip->status_id)){
//------------------------------	Old Format	------------------------------
			$runs = $result->chip->running;
			$complete = $result->chip->completed;
			$error = $result->chip->error;
			if($runs == false && $complete == false && $error == false){
				$this->db->where(array('serial_number'=>$result->chip->serial_number, 'plan_id'=>$result->planId, 'test_id'=>$result->testId));
				$insertStatus = $this->db->update('test_chips', array('running'=>true, 'completed'=>false, 'error'=>false, 'update_by'=>$result->user->username));
			} else if($runs == true && $complete == false && $error == false){
				$this->db->where(array('serial_number'=>$result->chip->serial_number, 'plan_id'=>$result->planId, 'test_id'=>$result->testId));
				$insertStatus = $this->db->update('test_chips', array('running'=>false, 'completed'=>true, 'error'=>false, 'update_by'=>$result->user->username));
			} else if($runs == false && $complete == true && $error == false){
				$this->db->where(array('serial_number'=>$result->chip->serial_number, 'plan_id'=>$result->planId, 'test_id'=>$result->testId));
				$insertStatus = $this->db->update('test_chips', array('running'=>false, 'completed'=>false ,'error'=>true, 'update_by'=>$result->user->username));
			} else if($runs == false && $complete == false && $error == true){
				$this->db->where(array('serial_number'=>$result->chip->serial_number, 'plan_id'=>$result->planId, 'test_id'=>$result->testId));
				$insertStatus = $this->db->update('test_chips', array('running'=>false, 'completed'=>false, 'error'=>false, 'update_by'=>$result->user->username));
			} else{
				echo 'nothing';
			}
		}else{
//------------------------------	New Format	------------------------------
			$statusId = $result->chip->status_id;
			if($statusId < 4){
				$statusId++;
				$userId = $result->user->id;
			}else{
				$statusId = 1;
				$userId = NULL;
			}
			$this->db->where('id', $result->chip->id);
			$this->db->update('test_chips_new', ['status_id'=>$statusId, 'updated_by'=>$userId]);
			$result->chip->status_id = $statusId;
			$this->db->select('status');
			$result->chip->status = $this->db->get_where('operation_status', ['id'=>$statusId])->result()[0]->status;
		}
		return $result;
	}
	
	function update_temp_status($result){
		$runs = $result->temp->running;
		$complete = $result->temp->completed;
		$error = $result->temp->error;
//		die(var_dump($result)); 
		if($runs == false && $complete == false && $error == false){
			$this->db->where(array('temp'=>$result->temp->temp, 'plan_id'=>$result->planId, 'test_id'=>$result->testId));
			$insertStatus = $this->db->update('test_temps', array('running'=>true, 'completed'=>false, 'error'=>false));
		} else if($runs == true && $complete == false && $error == false){
			$this->db->where(array('temp'=>$result->temp->temp, 'plan_id'=>$result->planId, 'test_id'=>$result->testId));
			$insertStatus = $this->db->update('test_temps', array('running'=>false, 'completed'=>true, 'error'=>false));
		} else if($runs == false && $complete == true && $error == false){
			$this->db->where(array('temp'=>$result->temp->temp, 'plan_id'=>$result->planId, 'test_id'=>$result->testId));
			$insertStatus = $this->db->update('test_temps', array('running'=>false, 'completed'=>false ,'error'=>true));
		} else if($runs == false && $complete == false && $error == true){
			$this->db->where(array('temp'=>$result->temp->temp, 'plan_id'=>$result->planId, 'test_id'=>$result->testId));
			$insertStatus = $this->db->update('test_temps', array('running'=>false, 'completed'=>false, 'error'=>false));
		} else{
			echo 'nothing';
		}
		return $result;
	}
	
	function update_hotcold_status($chip){

		$this->db->where(['id'=>$chip->id]);
//		--------------- Update Hot ---------------
		if($chip->hotCold == 'hot'){
			if($chip->hot == false){
				$status = $this->db->update('test_chips_new', ['hot'=>true]);
			} elseif($chip->hot == true){
				$status = $this->db->update('test_chips_new', ['hot'=>false]);
			} else{
				$status = 'hot error';
			}
//		--------------- Update Cold --------------
		} elseif($chip->hotCold == 'cold'){
			if($chip->cold == false){
				$status = $this->db->update('test_chips_new', ['cold'=>true]);
			} elseif($chip->hot = true){
				$status = $this->db->update('test_chips_new', ['cold'=>false]);
			} else{
				$status = 'cold error';
			}
		} else {
			$status = 'Hot Cold error';
		}
		return $status;
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
		
		return;
	}
	
	function format_edit($test){
//		echo json_encode($test);
//		die();
		$test->params = new stdClass();
		$test->params->channel = $test->channel;
		switch($test->station_id){
			case 1:
			case 2:
				if(isset($test->a_lineup)){
			$test->lineup = $test->a_lineup;
			if(isset($test->pin_from)){
				$test->params->pin_from = $test->pin_from[0];
				$test->params->pin_to = $test->pin_to[0];
				if(isset($test->pin_step)){
					$test->params->pin_step = $test->pin_step[0];
				}
				if(isset($test->pin_additional)){
					$test->params->pin_additional = $test->pin_additional[0];
				}
			}elseif(isset($test->lo_pin_from)){
				$test->params->lo_pin_from = $test->lo_pin_from[0];
				$test->params->lo_pin_to = $test->lo_pin_to[0];
				$test->params->lo_pin_step = $test->lo_pin_step[0];
				if(isset($test->lo_pin_additional)){
					$test->params->lo_pin_additional = $test->lo_pin_additional[0];
				}
			}
			$test->params->temp_r = $test->temp_r;
//			$test->params->channel = $test->channel;
			$test->params->antenna = $test->antenna;
			$test->params->mcs = $test->mcs[0];
//			$test->params->voltage = $test->voltage[0];
		}
				break;
			case 3:
			case 4:
				$test->lineup = $test->m_lineup;
				$test->params->temp_m = $test->temp_m;
				
//				$test->params->voltage = $test->voltage[0];
				break;
			case 5:
				$test->lineup = $test->a_lineup;
				$test->params->temp_r = $test->temp_r;
				$test->params->temp_m = $test->temp_m;
				$test->params->mcs = $test->mcs;
				$params = ['num_ant', 'antenna', 'sector', 'active_ants'];
				foreach ($params as $name){
					if(isset($test->$name)){
						$test->params->$name = $test->$name;
					}
				}
		}
		
		return $test;
	}
	
	function calc_pinAdd($data){
		$array = explode(",", $data);
		if(sizeof($array) > 0) {
			return sizeof($array);	
		} else {
			return 0;
		}
		
	}
	
	function calc_pins($pinFrom, $pinTo, $pinStep, $pinAddNum){
		$pinFrom = abs(floatval($pinFrom));
		$pinTo = abs(floatval($pinTo));
		$pinStep = abs(floatval($pinStep));
		if(!isset($pinAddNum)){
			$pinAddNum = 0;	
		}
//		die(var_dump($pinFrom, $pinTo, $pinStep, $pinAddNum));
		if($pinFrom > $pinTo){
			$pins = ((($pinFrom - $pinTo) + $pinAddNum) / $pinStep) + 1;
		} elseif($pinFrom < $pinTo){
			$pins = ((($pinTo - $pinFrom) + $pinAddNum) / $pinStep) + 1;
		}
		
		if(isset($pins)){
			return $pins;
		} else{
			$pins = 1;
		}
		
	}
	
	function calc_runtime($variables){
		if(!isset($variables->pins)){
			$pins = 1;
		}
		if(!isset($variables->loPins)){
			$loPins = 1;
		}
		
		$this->db->select('iteration_time');
		$iteration_time = floatval($this->db->get_where('params_test_iteration', ['station'=>$variables->station, 'test_name'=>$variables->test_name])->result()[0]->iteration_time);
		if(!isset($iteration_time)){
			echo 'error';
			exit;
		}
//		var_dump($iteration_time);
		$pins = 1;
		$loPins = 1;
		if($variables->station == 'R-CB1' || $variables->station == 'R-CB2'){
			$antsXIFS = $variables->antennas;
			$pins = $variables->pins;
			$loPins = $variables->loPins;
		} elseif($variables->station == 'M-CB1' || $variables->station == 'M-CB1'){
			$antsXIFS = $variables->xifs;
		} else{
			$antsXIFS = 1;
		}
		
		$estimate_time = $variables->temps * $variables->channels * $antsXIFS * $pins * $loPins * $iteration_time;
		
		$form_time = gmdate("H:i:s", $estimate_time);
//		var_dump($form_time);
//		die();
		return $form_time;
	}
}
