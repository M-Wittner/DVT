<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class plan_model extends CI_Model {
	    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
				$this->load->model(['excel_model', 'valid_model']);
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
//		$plan = $this->db->get_where('plans', array('id'=> $id->planId))->result();
		$test = $this->db->get_where('tests', array('id'=>$id->testId))->result();
//		die(var_dump($test));
		if(!isset($test[0]) || !isset($test->test_name_id)){
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
			echo json_encode($test);
			die();
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
	function get_test_v1($id) {
		$this->other_db = $this->load->database('main', TRUE);
		$test = $this->db->get_where('tests_view_v1', array('test_id'=>$id))->result()[0]; // Get test by id from test_view
//		var_dump($id);
//		die();
		$test->station = $this->other_db->get_where('work_stations',['idx'=>$test->station_id])->result();
		$test->testType = $this->other_db->get_where('test_types',['type_idx'=>$test->test_type_id])->result();
		$priority = $test->priority;
		$test->priority = array();
		$test->priority[0] = new stdClass();
		$test->priority[0]->value = $priority;
//		die(var_dump($test));
		// ----- Get Sweeps of that test ---------
		$test->sweeps = array();
		$this->db->select('config_id, name, data_type, priority, test_type_id');
		$this->db->order_by('priority asc','data_type desc');
		$struct = $this->db->get_where('test_struct_view', array('station_id'=>$test->station_id, 'test_type_id'=>$test->test_type_id))->result();
		// ----- Populate each sweep with data ---------
		foreach($struct as $sweep){
			$test->sweeps[$sweep->name] = new stdClass();
			$data = $this->db->get_where('test_configuration_data_view', array('test_id'=>$test->test_id, 'config_id'=>$sweep->config_id))->result();
			if(count($data) == 1 && (in_array($sweep->data_type, [33, 60]))){
				if($sweep->data_type == 60){ //Pin sweep
					$data[0]->value = explode(';', $data[0]->value);
					$data[0]->from = (int) $data[0]->value[0];
					$data[0]->step = (int) $data[0]->value[1];
					$data[0]->to = (int) $data[0]->value[2];
					if(isset($data[0]->value[3]) && $data[0]->value[3] != ""){
						$data[0]->ext = explode(',', (int) $data[0]->value[3]);
					}
					for($i = 0; $i < 4; $i++){
						unset($data[0]->value[$i]);
					}
					$test->sweeps[$sweep->name]->data = $data[0];
				}else{ //Lineup sweep
					$test->sweeps[$sweep->name]->data = $data[0];
				}
//						$test->sweeps[$sweep->name]->data = $data[0];
			}else{
				if($sweep->data_type == 61){
					foreach($data as $dac_atten){
						$dac = $dac_atten->value>>8;
						$dig = $dac_atten->value&255;
						$dac_atten->display_name = "Dac: ".$dac." Dig: ".$dig;
					}
				}elseif($sweep->data_type > 100){
					foreach($data as $chip){
						$this->db->select('chip_sn, chip_process_abb');
						$chipData = $this->db->get_where('chip_view', ['chip_id'=>$chip->value])->result();
						$statuses = $this->db->get_where('chip_status_view', ['data_idx'=>$chip->data_idx])->result();
						if(count($chipData) == 1){
							$chipData = $chipData[0];
							$chip->chip_sn = $chipData->chip_sn;
							$chip->chip_process_abb = $chipData->chip_process_abb;
							if(count($statuses) == 1){
								$statuses = $statuses[0];
								foreach($statuses as $key => $value){
									$chip->$key = $value;
								}
							}
						}
					}
				}
				foreach($data as $res){
					if(is_null($res->display_name)){
						$res->display_name = $res->value;
					}
				}
				//-------------- Generic sweeps	--------------
				$test->sweeps[$sweep->name]->data = $data;
			}
				$test->sweeps[$sweep->name]->data_type = $sweep->data_type;
				$test->sweeps[$sweep->name]->priority = $sweep->priority;
				$test->sweeps[$sweep->name]->test_type_id = $sweep->test_type_id;
		}
		return $test;	
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
	
	function add_comment_v1($data){
//		echo json_encode($data);
//		die();
		if($data->severity == 'Minor'){
			$comment = array(
				'test_id'=>$data->test_id,
				'user_id'=>$data->user_id,
				'severity'=>$data->severity,
				'config_id'=>$data->chip[0]->data_idx,
				'comment'=>$data->text,
			);
		}else{
			$comment = array(
				'test_id'=>$data->test_id,
				'user_id'=>$data->user_id,
				'severity'=>$data->severity,
				'comment'=>$data->text,
			);
		}
		$status = $this->db->insert('test_comments_new', $comment);
		if($status){
			$id = $this->db->insert_id($status);
			$returnComment  = $this->db->get_where('test_comments_v1_view', ['comment_id'=>$id])->result()[0];
			return $returnComment;
		}
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
	
	function update_test_v1($test){
		$result = array();
		$valid = $this->valid_model->validate_test($test, $result);
		if(!empty($valid)){
			foreach ($valid as $err){
				array_push($result, $err);
			}
		}else{
			if(!isset($test->notes)){
				$test->notes = null;
			}
			$error = new stdClass();
			$testBody = array(
//				'plan_id'=>$test->plan_id,
				'priority'=>$test->priority[0]->value,
				'test_type_id'=>$test->testType[0]->type_idx,
				'notes'=>$test->notes,
				'user_id'=>$test->user->userId,
			);
			$insertTest = $this->db->update('test_v1', $testBody);
			$insertTest = true;
//			die();
			if(!$insertTest){
				$error->msg = 'Test was not submitted';
				$error->source = $test->station[0]->name.', '.$test->testType[0]->test_name.', priority: '.$test->priority[0]->value;
				$error->occurred = true;
				array_push($result, $error);
			}else{
				$testId = $test->test_id;
				$resultsArray = array();
//				echo json_encode($test->sweeps);
//									die();
				$this->db->where('test_id',$testId);
				$this->db->delete('dvt_60g.test_configuration_data');
				foreach($test->sweeps as $sweepName => $sweepData){
					unset($sweepData->data->display_name);
					$error = new stdClass();
					switch($sweepData->data){
						case is_array($sweepData->data): //--------------	Deal with generic sweeps	--------------
//									var_dump($sweepName);
							switch($sweepData->data_type){
								case $sweepData->data_type > 100: //--------------	Deal with chip sweeps	--------------
									$chips = array();
									$this->db->select('config_id');
									$configIds = $this->db->get_where('dvt_60g.test_configurations', array('name'=>$sweepName))->result()[0];
									$configId = $configIds->config_id;
									foreach($sweepData->data as $sweep){
										if(isset($sweep->value)){
											$value = $sweep->value;
										} else if(isset($sweep->chip_id)){
											$value = $sweep->chip_id;
										}
										$chip = array(
											'test_id'=>$testId,
											'config_id'=>$configId,
											'value'=>$value,
										);
										array_push($chips,$chip);
									}
									$insertSweep = $this->db->insert_batch('dvt_60g.test_configuration_data', $chips);
									break;
								default:
									foreach($sweepData->data as $sweep){
										$sweep->test_id = $testId;
										unset($sweep->display_name);
									}
									array_push($resultsArray, $sweepData->data);
									$insertSweep = $this->db->insert_batch('dvt_60g.test_configuration_data', $sweepData->data, 'test_id');
									break;
							}
							if(!$insertSweep){
								$error->msg = $sweepName.' was not inserted';
								$error->source = $test->station[0]->name.', '.$test->testType[0]->test_name.', priority: '.$test->priority[0]->value;
								$error->occurred = true;
								array_push($result, $error);
							}
							break;
						default: //--------------	Deal with different sweeps	--------------
							$sweepData->data->test_id = $testId;
//							echo json_encode($sweepData);
							switch($sweepData->data_type){
								case 33://Linueup
									unset($sweepData->data_type);
									$insertSweep = $this->db->insert('dvt_60g.test_configuration_data', $sweepData->data);
									break;
								case 60://Pin
									unset($sweepData->data_type);
									if(!isset($sweepData->data->ext)){
										$sweepData->data->ext = '';
									} else {
										$sweepData->data->ext = implode(',',$sweepData->data->ext);
									}
									$pin = $sweepData->data->from.';'.$sweepData->data->step.';'.$sweepData->data->to.';'.$sweepData->data->ext;
									$this->db->set('config_id', $sweepData->config_id);
									$this->db->set('value', $pin);
									$this->db->set('test_id', $testId);
									$toUpdate = array(
										'config_id' => $sweepData->config_id,
										'value' => $pin,
										'test_id' => $testId
									);
									$insertSweep = $this->db->insert('dvt_60g.test_configuration_data', $toUpdate);
									break;
							}
							if(!isset($insertSweep) || !$insertSweep){
								$error->msg = $sweepName.' was not inserted';
								$error->source = $test->station[0]->name.', '.$test->testType[0]->test_name.', priority: '.$test->priority[0]->value;
								$error->occurred = true;
								array_push($result, $error);
							}
							break;
					}
				}
			}
		}
		return $result;
	}
	
	function update_chip_status($data){
//		echo json_encode($data->chip);
//		die();
		$chip = $data->chip;
		$user = $data->user;
		$key = 'chip_status';
		if(isset($chip->flag)){
			$key = $chip->flag.'_status';
		}else{
			$chip->flag = null;
		}
		switch($chip->flag){
			case 'hot':
				$status = $chip->hot_status;
				break;
			case 'cold':
				$status = $chip->cold_status;
				break;
			default:
				$status = $chip->chip_status;
				break;
		}
		switch($status){
			case 4:
				$status = 2;
				break;
			case 2:
				$status = 0;
				break;
			case 0:
				$status = 3;
				break;
			default:
				$status = 4;
				break;
		}
		$this->db->set($key, $status);
		$this->db->set('user_id', $user->id);
		$this->db->where(['data_idx'=>$chip->data_idx]);
		$res = $this->db->update('chip_status');
		if($res){
			return array($key=>$status, 'user'=>$user->username);
		}
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
