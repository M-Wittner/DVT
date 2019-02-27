<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class plan_model extends CI_Model {
	    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
				$this->load->model(['excel_model', 'valid_model']);
    }
	
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
	
	function get_test_v1($id) {
		$result = array();
		$error = new stdClass();
		$this->other_db = $this->load->database('main', TRUE);
		$test = $this->db->get_where('tests_view_v1', array('test_id'=>$id))->result(); // Get test by id
		if(!isset($test[0])){
			$error->msg = 'Test was not found';
			$error->source = 'Test #'.$id.' was not found!';
			$error->occurred = true;
			return $error;
		}else{
			$test = $test[0];
			$this->db->select('username, fname, lname');
			$username = $this->db->get_where('users', ['id'=>$test->user_id])->result();
			if(is_array($username) && isset($username[0])){
				$test->username = $username[0]->username;
				$test->user = $username[0]->fname.' '.$username[0]->lname;
			}
			$test->testType = $this->other_db->get_where('test_types',['type_idx'=>$test->test_type_id])->result();
			$test->station = $this->db->get_where('work_stations_view',['idx'=>$test->testType[0]->workstation_id])->result();
			$priority = $test->priority;
			$test->errors = $this->db->query("select log.* from dvt_60g.log
																				join dvt_60g.test_operation tp on log.test_id = tp.test_id 
																				where tp.plan_id = ".$id)->result();
			// ----- Get Sweeps of that test ---------
			$test->sweeps = array();
			$this->db->select('config_id, name, data_type, priority, test_type_id, tooltip');
			$this->db->order_by('priority asc','data_type desc');
			$struct = $this->db->get_where('test_struct_view', array('station_id'=>$test->station[0]->idx, 'test_type_id'=>$test->test_type_id))->result();
			// ----- Calc Test Progress ---------
			$test->chipErrors = array();
			$chipTypes = array_filter($struct, function($c){return $c->data_type > 100 ? $c->config_id : null;});
			foreach ($chipTypes as $type){
				$type->data = $this->db->get_where('test_configuration_data_view', array('test_id'=>$test->test_id, 'config_id'=>$type->config_id))->result();
				$type->num = count($type->data);
			}
			$counts = array_map(function($c){return $c->num;}, $chipTypes);
			$bigTypeId = $chipTypes[array_search(max($counts), $counts)]->config_id;
			$maxTests = max(1 ,max($counts));
			$totalProg = 0;
			// ----- Populate each sweep with data ---------
			foreach($struct as $sweep){
				$test->sweeps[$sweep->name] = new stdClass();
				if(isset($sweep->tooltip)){
					$test->sweeps[$sweep->name]->tooltip = $sweep->tooltip;
				}
				$data = $this->db->get_where('test_configuration_data_view', array('test_id'=>$test->test_id, 'config_id'=>$sweep->config_id))->result();
//				if($sweep->data_type == 60){
//					echo json_encode($sweep);
//					die();
//				}
				if(count($data) == 1 && (in_array($sweep->data_type, [33, 60]))){
					if($sweep->data_type == 60){ //Pin sweep
						$data[0]->value = explode(';', $data[0]->value);
						$data[0]->from = (int) $data[0]->value[0];
						$data[0]->step = (int) $data[0]->value[1];
						if(isset($data[0]->value[2])){
							$data[0]->to = (int) $data[0]->value[2];
						}
						if(isset($data[0]->value[3]) && $data[0]->value[3] != ""){
							$data[0]->ext = $data[0]->value[3];
						}
						for($i = 0; $i < 4; $i++){
							unset($data[0]->value[$i]);
						}
						$test->sweeps[$sweep->name]->data = $data[0];
					}else{ //Lineup sweep
						$test->sweeps[$sweep->name]->data = $data[0];
					}
				}else{
					if($sweep->data_type == 61){
						foreach($data as $dac_atten){
							$dac = $dac_atten->value>>8;
							$dig = $dac_atten->value&255;
							$dac_atten->display_name = "DAC:".$dac." Dig:".$dig;
						}
					}elseif($sweep->data_type > 100){
//						$chipStatus = array();
						foreach($data as $chip){
							$this->db->select('chip_sn, chip_process_abb');
							$chipData = $this->db->get_where('chip_view', ['chip_id'=>$chip->value])->result();
							$this->db->select('progress');
							$status = $this->db->get_where('chip_status_view', ['data_idx'=>$chip->data_idx])->result();
									die(json_encode($status));
							if(count($chipData) == 1){
								$chipData = $chipData[0];
								$chip->chip_sn = $chipData->chip_sn;
								$chip->chip_process_abb = $chipData->chip_process_abb;
								if(count($status) == 1){
									$statuses = $status[0];
									$chip->progress = $status->progress;
									if($chip->config_id == $bigTypeId){										
										$totalProg += (double)$chip->progress;
									}
									if(in_array($status->chip_status, [1,3])){ // status terminated or error
										array_push($test->chipErrors, $chip);
									}
//									array_push($chipStatus, $chip->progress);
								}
							}
						}
						$test->chipErrors = $this->db->get_where('chip_status_view', ['test_id'=>$test->test_id, 'chip_status'=>1, 'chip_status'=>3])->result();
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
			$test->progress = $totalProg / $maxTests;
			$chipErrors = count($test->chipErrors);
			$testErrors = count($test->errors);
			$status = ($test->progress == 0 && $chipErrors == 0) ? 4 : //IDLE
								$test->progress < 1 && $chipErrors == 0 ? 2 : //Running
								$test->progress == 1 && $chipErrors == 0 ? 0 : //Passed
								($chipErrors > 0 && $test->progress < 1) || ($testErrors > 0 && $test->progress == 0) ? 3 : -1;
//			$test->status = $status == 4 ? 'IDLE' : $status == 3 ? 'Error' : $status == 2 ? 'Running' : $status == 0 ? 'Passed' : '?';
			$this->db->set('progress', $test->progress);
//			$this->db->set('status', $status);
			$this->db->where('test_id', $test->test_id);
			$res = $this->db->update('test_v1');
//			die(json_encode($totalProg));
			return $test;	
		}
	}
	
	function calcProgNew($chips){
		$count = count($chips);
//		die(var_dump($count));
		$progress = 0;
		foreach($chips as $chip){
//			die(var_dump($chip));
			$progress = $progress + $chip->progress*100;
		}
//		die(print(round($progress/$count, 1)));
		return round($progress/$count, 1);
	}
	
	function calcProg($chips){
//		die(json_encode($chips));
		$count = count($chips);
		$progress = 0;
		foreach($chips as $chip){
			if(isset($chip->chip_status)){
				switch($chip->chip_status){
					case 0:
						$progress += 100;
						break;
					case 2:
						$progress += 50;
						break;
					default:
						$progress = $progress;
						break;
				}
			}else{
				$chipStatus = array(
					'data_idx'=>$chip->data_idx
				);
				$res = $this->db->insert('chip_status', $chipStatus);
			}
		}
		return round($progress/$count, 0, PHP_ROUND_HALF_UP);
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
//		$valid = $this->valid_model->validate_test($test, $result);
		$valid = null;
		if(!empty($valid)){
			foreach ($valid as $err){
				array_push($result, $err);
			}
		}else{
			if(!isset($test->notes)){
				$test->notes = null;
			}
//			$s = "ChipR Pin";
//			echo json_encode($test->sweeps->$s);
//			die();
			$error = new stdClass();
			$testBody = array(
				'plan_id'=>$test->plan_id,
//				'test_id'=>$test->test_id,
				'priority'=>$test->priority[0]->value,
				'test_type_id'=>$test->testType[0]->type_idx,
				'notes'=>$test->notes,
//				'user_id'=>$test->user->id,
			);
			$this->db->where('test_id', $test->test_id);
			$insertTest = $this->db->update('test_v1', $testBody);
//			$insertTest = true;
			if(!$insertTest){
				$error->msg = 'Test was not submitted';
				$error->source = $test->station[0]->name.', '.$test->testType[0]->test_name.', priority: '.$test->priority[0]->value;
				$error->occurred = true;
				array_push($result, $error);
			}else{
				$testId = $test->test_id;
				$resultsArray = array();
				$this->db->where('test_id',$testId);
				$this->db->delete('dvt_60g.test_configuration_data');
				foreach($test->sweeps as $sweepName => $sweepData){
					$error = new stdClass();
					switch($sweepData->data){ //-------------- 1st switch data (array/single)	--------------
						case is_array($sweepData->data): //--------------	Deal with generic array sweeps	--------------
//									var_dump($sweepName);
							switch($sweepData->data_type){ //-------------- 2nd switch data_types (array)	--------------
								case $sweepData->data_type > 100: //--------------	Chips	--------------
									$chipsStatus = array();
									$chips = array();
									$this->db->select('config_id');
									$configId = $this->db->get_where('dvt_60g.test_configurations', array('name'=>$sweepName))->result()[0]->config_id;
									foreach($sweepData->data as $sweep){
										unset($sweep->data_idx);
										$value = -1;
										if(isset($sweep->chip_id)){
											$value = $sweep->chip_id;
										}elseif(isset($sweep->value)){
											$value = $sweep->value;
										}
										$chip = array(
											'test_id'=>$testId,
											'config_id'=>$configId,
											'value'=>$value,
										);
										array_push($chips,$chip);
										array_push($chipsStatus,array('data_idx'=>null));
									}
									$insertSweep = $this->db->insert_batch('dvt_60g.test_configuration_data', $chips);
									$dataIdx = $this->db->insert_id($insertSweep);;
									for($i = 0; $i < $insertSweep; $i++){
										$chipsStatus[$i]['data_idx'] = $dataIdx + $i;
									}
									$insertSweep = $this->db->insert_batch('chip_status', $chipsStatus);
									break;
								default: //-------------- Generic sweeps	--------------
									foreach($sweepData->data as $sweep){
										$sweep->test_id = $testId;
										if(isset($sweep->display_name)){
											unset($sweep->display_name);
										}
										if(isset($sweep->data_idx)){
											unset($sweep->data_idx);
										}
									}
									if(isset($sweepData->ext)){
										if($sweepData->config_id == 19){
												$tempObj = new stdClass();
												$tempObj->test_id = $testId;
												$tempObj->config_id = $sweepData->config_id;
												$tempObj->value = $sweepData->ext;
												array_push($sweepData->data, $tempObj);
											}else{
												$extraData = explode(',', $sweepData->ext);
												foreach($extraData as $ext){
													$tempObj = new stdClass();
													$tempObj->test_id = $testId;
													$tempObj->config_id = $sweepData->config_id;
													$tempObj->value = $ext;
													array_push($sweepData->data, $tempObj);
												}
											}
										}		
									$insertSweep = $this->db->insert_batch('dvt_60g.test_configuration_data', $sweepData->data);
									break;
							} //-------------- End of 2nd switch data_types (array)	--------------
							if(!$insertSweep){
								$error->msg = $sweepName.' was not inserted';
								$error->source = $test->station[0]->name.', '.$test->testType[0]->test_name.', priority: '.$test->priority[0]->value;
								$error->occurred = true;
								array_push($result, $error);
							}
							break;
						default: //--------------	Deal with different sweeps	--------------
							$sweepData->data->test_id = $testId;
//									var_dump($sweepName);
							unset($sweepData->data->display_name);
							unset($sweepData->data->data_idx);
							switch($sweepData->data_type){ 
								case 33://Linueup
									unset($sweepData->data_type);
									$insertSweep = $this->db->insert('dvt_60g.test_configuration_data', $sweepData->data);
									break;
								case 61: //DAC_Atten file handle
									$path = $sweepData->path;
									$file = $sweepData->data;
									$handle = fopen($path.$_FILES['file']['name'], 'a') or die('Cannot open file:		'.$file);
									$values = $this->excel_model->dac_atten_file_handle($file, $path);
									break;
								case 60://Pin
								case 62://Temp Cycle
									unset($sweepData->data_type);
									if(!isset($sweepData->data->ext)){
										$sweepData->data->ext = '';
									}
									if(is_array($sweepData->data->ext)){
										$pin = $sweepData->data->from.';'.$sweepData->data->step.';'.$sweepData->data->to.';'.implode(',',$sweepData->data->ext);
									}else{
										$pin = $sweepData->data->from.';'.$sweepData->data->step.';'.$sweepData->data->to.';'.$sweepData->data->ext;
									}
									$this->db->set('config_id', $sweepData->config_id);
									$this->db->set('value', $pin);
									$this->db->set('test_id', $testId);
									$insertSweep = $this->db->insert('dvt_60g.test_configuration_data');
									break;
							} //--------------	End of switch data_types	--------------
							if(!isset($insertSweep) || !$insertSweep){
								$error->msg = $sweepName.' was not inserted';
								$error->source = $test->station[0]->name.', '.$test->testType[0]->test_name.', priority: '.$test->priority[0]->value;
								$error->occurred = true;
								array_push($result, $error);
							}
							break;
					} //-------------- End of 1st switch data (array/single)	--------------
				}
			}
		}
		if(empty($result)){
			$result = 'Test Updated successfully';
		}
		return $result;
	}
	
	function update_chip_status($data){
//		echo json_encode($data);
		$this->other_db = $this->load->database('main', TRUE);
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
			$this->other_db->select('data_idx');
			$res = $this->other_db->get_where('test_configuration_data', ['test_id'=>$chip->test_id, 'config_id'=>$chip->config_id])->result();
			if($res){
				$res = array_map(function($data){
					return $data->data_idx;
				}, $res);
				$this->db->select('chip_status, data_idx');
				$this->db->where_in('data_idx', $res);
				$res2 = $this->db->get('chip_status_view')->result();
				$prog = $this->calcProg($res2);
			}
			return array($key=>$status, 'user'=>$user->username, 'progress'=>$prog);
		}
	}
	
	function update_operation_status($data){
//		die(json_encode($data));
		$this->other_db = $this->load->database('main', TRUE);
		$status = $data->status_id;
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
		$this->other_db->set('test_status', $status);
		$this->other_db->where(['test_id'=>$data->operation_id]);
		$res = $this->other_db->update('test_operation');
		if($res){
			$status = $this->other_db->get_where('statuses', ['test_status'=>$status])->result()[0];
			return $status;
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
	
}
