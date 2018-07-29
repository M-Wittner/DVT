<?php
header("X-Frame-Options: SOMEORIGIN");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plans extends CI_Controller {	
	public function __construct() {
	parent::__construct();
	$this->load->helper(['form','url']);
	$this->load->library(['email']);
	$this->load->database('');
	$this->load->model(['plan_model', 'excel_model', 'valid_model']);
    }

	function index() {
		$this->db->order_by('date', 'desc');
//		$this->db->limit('1');
		$plans = $this->db->get('plans_v1')->result();
			foreach($plans as $plan){
				$this->db->where('plan_id', $plan->id);
				$plan->tests = $this->db->get('tests_view_v1')->result();
//				if(!count($plan->tests) > 0){
//					$this->db->where('plan_id', $plan->id);
//					$plan->tests = $this->db->get('tests_view_new')->result();
//				}
//				$testProgress = 0;
//				foreach($plan->tests as $test){
//					$testProgress += $test->progress;			
//				}
//				if(count($plan->tests) > 0){
//					$plan->progress = round((($testProgress / count($plan->tests))), 2);
//					$this->db->where('id', $plan->id);
//					$this->db->set(['progress'=>$plan->progress]);
//					$this->db->update('plans');
//				}
			}
		echo json_encode($plans);	
	}
	
	function CreateNew(){
		$postData = json_decode(file_get_contents('php://input'));
		$result = array();
		$planData = $postData->plan;
		$plan = array(
			'user_id'=>$planData->userId,
		);	
		$tests = $postData->test;
//		echo json_encode($tests);
//		die();
		if(sizeof($tests) <= 0){
			$result->msg = 'No tests detected!';
			$result->occurred = true;
		}else{
			if(!isset($planData->id)){
				$insertPlan = $insertStatus = $this->db->insert('plans_v1', $plan);
				$planId = $this->db->insert_id($insertPlan);
			}else{
				$planId = $planData->id;
			}
			$valid = $this->valid_model->validate_plan($tests);
			if(!empty($valid)){
				foreach ($valid as $err){
					array_push($result, $err);
				}
			}else{
				foreach($tests as $test){
					if(!isset($test->notes)){
						$test->notes = null;
					}
					$error = new stdClass();
					$testBody = array(
						'plan_id'=>$planId,
						'priority'=>$test->priority[0]->value,
						'test_type_id'=>$test->testType[0]->type_idx,
						'notes'=>$test->notes,
						'user_id'=>$planData->userId,
					);
					$insertTest = $this->db->insert('test_v1', $testBody);
					if(!$insertTest){
						$error->msg = 'Test was not submitted';
						$error->source = $test->station[0]->name.', '.$test->testType[0]->test_name.', priority: '.$test->priority[0]->value;
						$error->occurred = true;
						array_push($result, $error);
					}else{
						$testId = $this->db->insert_id($insertTest);
						foreach($test->sweeps as $sweepName => $sweepData){
							$error = new stdClass();
							switch($sweepData->data){
								case is_array($sweepData->data): //--------------	Deal with generic sweeps	--------------
//									var_dump($sweepName);
									switch($sweepData->data_type){
										case $sweepData->data_type > 100:
											$chipsStatus = array();
											$chips = array();
											$this->db->select('config_id');
											$configId = $this->db->get_where('dvt_60g.test_configurations', array('name'=>$sweepName))->result()[0]->config_id;
											foreach($sweepData->data as $sweep){
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
											$dataIdx = $this->db->insert_id($insertSweep);
											for($i = 0; $i < $insertSweep; $i++){
												$chipsStatus[$i]['data_idx'] = $dataIdx - $insertSweep + $i;
//											echo json_encode($chipsStatus[$i]);
											}
											$insertSweep = $this->db->insert_batch('chip_status', $chipsStatus);
											break;
										default:
											foreach($sweepData->data as $sweep){
												$sweep->test_id = $testId;
												unset($sweep->display_name);
											}
//											var_dump($sweepData);
//											die();
											$insertSweep = $this->db->insert_batch('dvt_60g.test_configuration_data', $sweepData->data);
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
//									var_dump($sweepName);
									unset($sweepData->data->display_name);
									switch($sweepData->data_type){
										case 33://Linueup
											unset($sweepData->data_type);
											$insertSweep = $this->db->insert('dvt_60g.test_configuration_data', $sweepData->data);
											break;
										case 60://Pin
											unset($sweepData->data_type);
											if(!isset($sweepData->data->ext)){
												$sweepData->data->ext = '';
											}
//											echo json_encode($sweepData);
//											die();
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
			}
			if(empty($result)){
				$result = 'All tests were inserted successfully';
			}
		}
		echo json_encode($result);
	}
	
	function Create() {
		$test_params = $this->db->get('test_params')->result();
		$paramsArr = array();
		foreach($test_params as $param){
			$paramsArr[$param->param_name] = $param->param_id;
		}

		// fetching data
		$postData = json_decode(file_get_contents('php://input'));
		echo json_encode($postData);
							die();
		
		$planData = $postData->plan;
		$plan = array(
			'user_id'=>$planData->userId,
			'user_username'=>$planData->username
		);
		
		$tests = $postData->test;
		if(sizeof($postData->test) > 0){
			if(!isset($planData->id)){
				$insertPlan = $this->plan_model->add_plan($plan);
				$planId = $this->plan_model->get_id($insertPlan);
			}else{
				$planId = $planData->id;
			}
			if(isset($planId)){
//				$planId = 750;
				foreach($tests as $test){
					if(isset($test->notes)){
						$notes = $test->notes;
					} else {
						$notes = null;
					}

//					die();
					//			------------- R station test -------------
						if($testArr->station[0]->station == 'R-CB1' || $testArr->station[0]->station == 'R-CB2'){
	//						echo json_encode($testArr);
	//						die();
							if(isset($testArr->lineup)){
								$lineup = $testArr->lineup;;
							}else{
								$lineup = $testArr->a_lineup;
							}

							$test = new stdClass();
								$test->struct = array(
										'priority'=>$testArr->priority[0],
										'work_station_id'=>$testArr->station[0]->id,
										'test_name_id'=>$testArr->name[0]->id,
										'a_lineup'=>$lineup,
										'notes'=>$notes,
										'plan_id'=>$planId,
										'user_id'=>$planData->userId,
									);
								$insertTest = $this->db->insert('tests_new', $test->struct);
								$testId = $this->db->insert_id($insertTest);

								$chips = $testArr->chips;
								$chipPairs = array();
								foreach($chips as $chip){
									$data = array(
										'plan_id'=>$planId,
										'test_id'=>$testId,
										'chip_r_type_id'=>$chip->chip_type_id,
										'chip_r_id'=>$chip->chip_id,
									);
									$this->db->insert('test_chips_new', $data);
								}

								$test->params = array();
								$params = $test->params;
								foreach($testArr->params as $name=>$values){
									$params[$name] = array();
									if(is_array($values)){
										foreach($values as $value){
											$data = array(
												'plan_id'=>$planId,
												'test_id'=>$testId,
												'param_id'=>$paramsArr[$name],
												'param_value_id'=>$value,
											);
											array_push($params[$name], $data);
										}
									}else{
										$data = array(
											'plan_id'=>$planId,
											'test_id'=>$testId,
											'param_id'=>$paramsArr[$name],
											'param_value_id'=>$values,
										);
										array_push($params[$name], $data);
									}
								}
							foreach ($params as $param){
								$status = $this->db->insert_batch('test_config', $param);
							}

	////			------------- M station test -------------
						} else if($testArr->station[0]->station == 'M-CB1' || $testArr->station[0]->station == 'M-CB2' || $testArr->station[0]->station == 'Calibration'){
							$test = new stdClass();
							if(isset($testArr->lineup)){
								$lineup = $testArr->lineup;
							}else{
								$lineup = "-1";
							}
							$test->struct = array(
								'priority'=>$testArr->priority[0],
								'work_station_id'=>$testArr->station[0]->id,
								'test_name_id'=>$testArr->name[0]->id,
								'm_lineup'=>$lineup,
								'notes'=>$notes,
								'plan_id'=>$planId,
								'user_id'=>$planData->userId,
							);
							$insertTest = $this->db->insert('tests_new', $test->struct);
							$testId = $this->db->insert_id($insertTest);

							$chips = $testArr->chips;
							$chipPairs = array();
							foreach($chips as $chip){
								$data = array(
									'plan_id'=>$planId,
									'test_id'=>$testId,
									'chip_m_type_id'=>$chip->chip_type_id,
									'chip_m_id'=>$chip->chip_id,
								);
								$this->db->insert('test_chips_new', $data);
							}
							$test->params = array();
							$params = $test->params;
							foreach($testArr->params as $name=>$values){
								$params[$name] = array();
								if(is_array($values)){
									foreach($values as $value){
										$data = array(
											'plan_id'=>$planId,
											'test_id'=>$testId,
											'param_id'=>$paramsArr[$name],
											'param_value_id'=>$value,
										);
										array_push($params[$name], $data);
									}
								}else{
									$data = array(
										'plan_id'=>$planId,
										'test_id'=>$testId,
										'param_id'=>$paramsArr[$name],
										'param_value_id'=>$values,
									);
									array_push($params[$name], $data);
								}
							}
							foreach ($params as $param){
								$status = $this->db->insert_batch('test_config', $param);
							}
	//						die();
	//------------ PTAT/ABS/Vgb+TEMP station test -------------
						} elseif($testArr->station[0]->id == 7) {
//							die(var_dump($testArr));
								$chipsArr = $testArr->chips;
								$test = array(
									'priority'=>$testArr->priority[0],
									'work_station_id'=>$testArr->station[0]->id,
									'test_name_id'=>$testArr->name[0]->id,
									'notes'=>$notes,
									'plan_id'=>$planId,
									'user_id'=>$planData->userId,
								);
								$insertTest = $this->db->insert('tests_new', $test);
								$testId = $this->db->insert_id($insertTest);
							
								foreach($chipsArr as $chip){
									if($testArr->name[0]->test_name == 'TalynM - Temperature Calibration'){
										$data = array(
											'plan_id'=>$planId,
											'test_id'=>$testId,
											'chip_m_type_id'=>$chip->chip_type_id,
											'chip_m_id'=>$chip->chip_id,
										);
									}elseif($testArr->name[0]->test_name == 'TalynA - Temperature Calibration'){
										$data = array(
											'plan_id'=>$planId,
											'test_id'=>$testId,
											'chip_r_type_id'=>$chip->chip_type_id,
											'chip_r_id'=>$chip->chip_id,
										);
									}
									$this->db->insert('test_chips_new', $data);
								}
	//------------ TalynM+A station test -------------
						} elseif($testArr->station[0]->station == 'TalynM+A'){
								$test = new stdClass();
								$test->struct = array(
									'priority'=>$testArr->priority[0],
									'work_station_id'=>$testArr->station[0]->id,
									'test_name_id'=>$testArr->name[0]->id,
									'a_lineup'=>$testArr->lineup,
									'm_lineup'=>$testArr->m_lineup,
									'notes'=>$notes,
									'plan_id'=>$planId,
									'user_id'=>$planData->userId,
								);
							$insertTest = $this->db->insert('tests_new', $test->struct);
							$testId = $this->db->insert_id($insertTest);

							$chips = $testArr->chips;
							$chipPairs = array();
							foreach($chips as $key=>$chip){
								$pairData = array(
									'plan_id'=>$planId,
									'test_id'=>$testId,
									'chip_r_type_id'=>$chip->chip_r[0]->chip_type_id,
									'chip_r_id'=>$chip->chip_r[0]->chip_id,
									'chip_m_type_id'=>$chip->chip_m[0]->chip_type_id,
									'chip_m_id'=>$chip->chip_m[0]->chip_id,
									'pair_id'=>$key+1,
								);

								$this->db->insert('test_chips_new', $pairData);
							}
							$test->params = array();
							$params = $test->params;
							foreach($testArr->params as $name=>$values){
								$params[$name] = array();
								foreach($values as $value){
									if(in_array($name, ['temp_r', 'temp_m']) && $value == 'None'){
										$data = array(
											'plan_id'=>$planId,
											'test_id'=>$testId,
											'param_id'=>$paramsArr[$name],
											'param_value_id'=>-273,
										);
									}else{
											$data = array(
												'plan_id'=>$planId,
												'test_id'=>$testId,
												'param_id'=>$paramsArr[$name],
												'param_value_id'=>$value,
											);
										}
									array_push($params[$name], $data);
								}
							}
	//						echo json_encode($params);
	//						die();
						foreach ($params as $param){
							$status = $this->db->insert_batch('test_config', $param);
						}						
	//					echo $status."aaa";
					} else {
						echo 'not valid station';
					}
				}
				echo 'success';
			}else {
				echo 'No plan inserted!';
			}	
		} else {
			echo 'No Test Detected!';
		}
	}
	
	function show_v1(){
		$this->other_db = $this->load->database('main', TRUE);
		$id = json_decode(file_get_contents('php://input'));
		$plan = $this->db->get_where('plans_v1', array('id'=>$id))->result()[0];
		$this->db->select('test_id');
		$plan->tests = $this->db->get_where('test_v1', array('plan_id'=>$id))->result();
		foreach ($plan->tests as $i=>$test){
			$plan->tests[$i] = $this->plan_model->get_test_v1($test->test_id);
		}
		echo json_encode($plan);
	}
	
	function Show() {
		$id = json_decode(file_get_contents('php://input'));
		$result = new stdClass();
		$result->tests = $this->plan_model->get_plan($id);
//		$result->fs = $this->db->get_where('test_params_view', ['plan_id'=>$id])->result();
		$result->fs = $this->db->get_where('tests_view_new', ['plan_id'=>$id])->result();
		foreach ($result->fs as $test){
			$test->chips = $this->db->get_where('test_chips_view', ['test_id'=>$test->id])->result();
			$c = 0;
			$e = 0;
			$r = 0;
			foreach($test->chips as $chip){
				switch($chip->status_id){
					case 2:
						$r++;
						break;
					case 3:
						$c++;
						break;
					case 4:
						$e++;
						break;
				}
			}
			if($c == count($test->chips)){
					$test->status_id = 3;
				} elseif($e > 0){
					$test->status_id = 4;
				} elseif($r > 0 && $c < count($test->chips)){
					$test->status_id = 2 ;
				} else{
					$test->status_id = 1;
				} 
			if(count($test->chips) > 0){
				$progress = (($c + ($r/2)) / count($test->chips))*100;
			} else{
				$progress = 0;
			}
			$statusId = null;
			if($progress < 100 && ($progress > 0 || $e > 0)){
				if($e > 0){
					$statusId = 4;
				}else{
					$statusId = 2;
				}
			}elseif($progress == 100){
				$statusId = 3;
			}else{
				$statusId = 1;
			}
			$test->progress = $progress;
			$this->db->where('id', $test->id);
			$this->db->set(['status_id'=>$test->status_id, 'progress'=>$test->progress]);
			$this->db->update('tests_new');
			$test->status = $this->db->get_where('tests_view_new', ['id'=>$test->id])->result()[0]->status;
			$test->params = $this->db->get_where('test_params_view', ['test_id'=>$test->id])->result();
			$test->comments = $this->db->get_where('test_comments_view', ['test_id'=>$test->id])->result();
		}
		echo json_encode($result);
		
	}
	
	function removePlan(){
		$id = json_decode(file_get_contents('php://input'));
		$this->db->where('id', $id);
		$result = $this->db->delete('plans_v1');
		echo json_encode($result);
	}
	
	function get_test(){
		$id = json_decode(file_get_contents('php://input'));
		$result = $this->plan_model->get_test_v1($id->testId);
		echo json_encode($result);
	}
	
		function copyTest(){
		$testId = json_decode(file_get_contents('php://input'));
		$test = $this->plan_model->get_test_v1($testId);
		echo json_encode($test);
	}
	
	function newcomment(){
		$id = json_decode(file_get_contents('php://input'));
		$test = $this->db->get_where('tests_view_new', array('id'=>$id->testId))->result()[0];
		$rawChips = $this->db->get_where('test_chips_view', array('test_id'=>$id->testId))->result();
//		echo json_encode($rawChips);
//		die();
		$chips = array();
		foreach($rawChips as $key=>$chip){
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
					$data = array(
						'chip_r_id'=>$chip->chip_r_id,
						'chip_m_id'=>$chip->chip_m_id,
						'pair_id'=>$chip->id,
						'chip_r_sn'=>$chip->chip_r_sn,
						'corner_r'=>$chip->corner_r,
						'chip_r_type_id'=>$chip->chip_r_type_id,
						'chip_m_sn'=>$chip->chip_m_sn,
						'croner_m'=>$chip->corner_m,
						'chip_m_type_id'=>$chip->chip_m_type_id,
					);
					break;
				}
				array_push($chips, $data);
			}
		$result = array(
			'test'=>$test,
			'chips'=>$chips
		);
		echo json_encode($result);
	}
	
	function addcomment(){
		$postData = json_decode(file_get_contents('php://input'));
//		die(var_dump($postData));
		$comment = $this->plan_model->add_comment($postData);
		echo json_encode($comment);
	}
	
	function showcomments(){
		$id = json_decode(file_get_contents('php://input'));
		$comments = $this->plan_model->get_comments($id);
		echo json_encode($comments);
	}
	
	function getcomment(){
		$data = json_decode(file_get_contents('php://input'));
		$comment = $this->db->get_where('tests_comments_view', ['test_id'=>$data->testId])->result();
//			$res = array(
//				'comment' => $this->db->get_where('test_comments', array('id'=>$data->commentId))->result(),
//				'test' => $this->db->get_where('tests', array('id'=>$data->testId))->result(),
//				'chips' => $this->db->get_where('test_chips', array('test_id'=>$data->testId))->result()
//			);
		echo json_encode($comment);
	}
	
	function editcomment(){
		$comment = json_decode(file_get_contents('php://input'));
		if($comment->severity == 'Minor'){
			$this->db->where('id', $comment->id);
			$comment = array(
				'plan_id'=>$comment->plan_id,
				'test_id'=>$comment->test_id,
				'severity'=>$comment->severity,
				'station'=>$comment->station,
				'test_name'=>$comment->test_name,
				'chip'=>$comment->chip,
				'details'=>$comment->details
			);
			$insertStatus = $this->db->update('test_comments', $comment);
			print_r($insertStatus);
		} elseif($comment->severity == 'Major'){
			$this->db->where('id', $comment->id);
			$comment = array(
				'plan_id'=>$comment->plan_id,
				'test_id'=>$comment->test_id,
				'severity'=>$comment->severity,
				'station'=>$comment->station,
				'details'=>$comment->details
			);
			$insertStatus = $this->db->update('test_comments', $comment);
			print_r($insertStatus);
		}else{
			echo 'failed';
		};
//		echo json_encode($comment->severity);
	}
	
	function update(){
		$test = json_decode(file_get_contents('php://input'));
//		echo json_encode($test);
//		die();
		switch($test->flag){
			case 0:
				$result = $this->plan_model->update_test_old($test);
				break;
			case 1:
				$result = $this->plan_model->update_test($test);
				break;
			case 2:
				$result = $this->plan_model->update_test_v1($test);
				break;
		}
			echo json_encode($result);
	}
	
	function planCheck(){
		$data = json_decode(file_get_contents('php://input'));
		$updateStatus = $this->plan_model->update_plan_status($data);
		echo json_encode($updateStatus);
	}
	
	function chipstatus(){
		$status = json_decode(file_get_contents('php://input'));
//		die(var_dump($status));
		$updateStatus = $this->plan_model->update_chip_status($status);
		echo json_encode($updateStatus);
	}
	function tempstatus(){
		$status = json_decode(file_get_contents('php://input'));
//		die(var_dump($status));
		$updateStatus = $this->plan_model->update_temp_status($status);
		echo json_encode($updateStatus);
	}
	function hotcoldstatus(){
		$status = json_decode(file_get_contents('php://input'));
//		die(var_dump($status));
		$updateStatus = $this->plan_model->update_hotcold_status($status);
		echo json_encode($updateStatus);
	}	
	function xifstatus(){
		$status = json_decode(file_get_contents('php://input'));
//		die(var_dump($status));
		$updateStatus = $this->plan_model->update_xif_status($status);
		echo json_encode($updateStatus);
	}
	
	function removeTest(){
		$id = json_decode(file_get_contents('php://input'));
		$this->db->where('test_id', $id);
		$result = $this->db->delete('test_v1');
		echo json_encode($result);
	}
	function removeTestFS(){
		$id = json_decode(file_get_contents('php://input'));
		$result = $this->db->query("DELETE FROM `tests_new` WHERE id = ?", $id);
		if ($result){
			echo 'success';
		} else{
			return $result;
		}
	}	
	function removeComment(){
		$id = json_decode(file_get_contents('php://input'));
		$result = $this->db->query("DELETE FROM `test_comments_new` WHERE comment_id = ?", $id);
		return $result;
	}
	
	function planStatus(){
		$id = json_decode(file_get_contents('php://input'));
//		echo $id;
//		die();;
		$this->db->select('priority, name AS station, test_name');
		$this->db->where('plan_id', $id);
		$tests = $this->db->get('tests_view_v1')->result();
		echo json_encode($tests);
	}

	
		public function lineup($test){
//			die(print_r($test));
			$lineup = (string)$test->lineup;
			$station = $test->station[0]->station;

		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		if(in_array($station, ['R-CB1', 'R-CB2'])){
			$spreadsheet = $reader->load($lineup);
			$expectedSheets = ['Typical', 'LO Lineup'];
			$sheets = $spreadsheet->getSheetNames();
		} elseif(in_array($station, ['M-CB1', 'M-CB2'])) {
			// CREATE SPREADSHEET FROM CSV
			$path = $this->excel_model->ss_from_csv($lineup);
			$spreadsheet = $reader->load($path);
			$sheets = $spreadsheet->getSheetNames();
		}
		$reader->setReadDataOnly(true);	
		if ($spreadsheet){
			foreach($sheets as $sheetName){
			//CHECK FOR SHEET EXSITENCE
				if(!in_array($sheetName, $sheets)){
					echo $sheetName." wasn't found! Might be misspelled";
					die();
				}
	// 		EXTRACTS  HIGHEST COL, HIGHEST ROW, FIRST ROW
				$currentSheet = $spreadsheet->getSheetByName($sheetName);
				$highestColumn = $currentSheet->getHighestColumn();
				$highestRow = $currentSheet->getHighestRow();
				$firstRowRaw = $currentSheet->rangeToArray('A1:'.$highestColumn.'1', null, false, false, true)[1];
	//		GET PARAMS FOUND BOTH IN EXCEL AND DB (NOT: TEMP CH V)
				$this->db->where_in('parameter_name', $firstRowRaw);
				$this->db->group_by('parameter_name');
				$match = $this->db->get('lineup_params')->result();
				
				$paramNameArr = [];
				// GET PARAM NAMES FROM $MATCH INTO ARRAY
				foreach ($match as $obj){
					$value = $obj->parameter_name;
					array_push($paramNameArr, $value);
				}
				$localParams = ["temp", "v", "ch"];
				foreach($firstRowRaw as $index => $param){

					$trimSpace = " ";
					$trimParam = trim($param, $trimSpace);
					$paramIdx = array_search($param, $paramNameArr);

					$data = new stdClass();
					if($paramIdx === false){
//					INSERT TEMP CH V INTO SQL RESULT
						if(in_array(strtolower($param), $localParams)){
							$data->parameter_name = $param;
							$data->parameter_id = -1;
							$data->parameter_range = -1;
							$data->excel_index = $index;
							array_push($match, $data);
					// CHECK IF TEMPS AND CH FROM SITE ARE FOUND IN EXCEL FILE;
							if(in_array(strtolower($param), ['temp', 'ch'])){
								$colData = $currentSheet->rangeToArray($index.'2:'.$index.$highestRow, -1, false, false, false);
								$data = array_column($colData, 0);
								$unique = array_unique($data);
								$this->excel_model->validate($test, $param, $unique);
							}
							
						}elseif($trimParam != $param){
							echo "The \"".$param."\" parameter in column ".$index." is invalid! It might contain a space!";
							die();
						}
					}else{
						
						$match[$paramIdx]->excel_index = $index;
					}

				}

	//		INSERT EXCEL INDEX TO EACH PARAM
				foreach ($match as $value){
					$name = strtolower($value->parameter_name);

	//  		GET THIS COLUMN DATA
					$col = $currentSheet->rangeToArray($value->excel_index.'2:'.$value->excel_index.$highestRow, -1, false, false, false);
					$value->rows = array_column($col, 0);
					if($name == "ch"){
						foreach($col as $i => $val){
							$val[0] = str_replace(' ', '',$val[0]);
							
							//CHECK IF CHANNELS BY NEW FORMAT
							switch($val[0]){
//								case 1:
//								case 2:
//								case 3:
//								case 4:
//								case 5:
//									break;
//								case strlen($val[0]) > 1:
//									c
								case "6":
									echo "Channel 6 in cell ".$value->excel_index.($i+2)." is not in use";
									die();
									break;
								case "7":
									echo "Please change channel 7 in cell ".$value->excel_index.($i+2)." to 9(1+2)";
									die();
									break;
								case "8":
									echo "Please change channel 8 in cell ".$value->excel_index.($i+2)." to 10(2+3)";
									die();
									break;
								case "9":
									echo "Please change channel 9 in cell ".$value->excel_index.($i+2)." to 11(3+4)";
									die();
									break;
								case "10":
									echo "Please change channel 10 in cell ".$value->excel_index.($i+2)." to 12(4+5)";
									die();
									break;
							}
						}
					}
				switch($value->parameter_range){
					// RANGE NULL: VALUE IS NOT A NUMBER AND OPTIONAL(NOTE COL)
						case -1:	
							break;
					// RANGE 0 IS BOOLEAN VALUE
						case 0:
							$res = $this->excel_model->check_exist($value->rows, $value->parameter_range, $value->excel_index, $sheetName);
							if($res = true){
									$this->excel_model->check_bool($value->rows, $value->parameter_range, $value->excel_index, $sheetName);
								}
							break;
					// RANGE -1 IS NO RANGE, CHECKS EXISTANCE
						case null:
							$this->excel_model->check_exist($value->rows, $value->parameter_range, $value->excel_index, $sheetName);
								if(in_array($value->parameter_name, ['CH', 'ChipChannel'])){
								// CHECK IF CH IS VALID
									$this->excel_model->is_ch_valid($value->rows, $value->excel_index, $sheetName);
								}
							break;
					// RANGE IS NOT NULL AND NOT -1, CHECKS EXISTANCE AND IS NUMERIC
						default:
							$res = $this->excel_model->check_exist($value->rows, $value->parameter_range, $value->excel_index, $sheetName);
							if($res = true){
								$this->excel_model->check_num($value->rows, $value->parameter_range, $value->excel_index, $sheetName);
							}
							break;
					}
				}	
			}		
		}
	}
	
	public function check($test){
//		$data = json_decode(file_get_contents('php://input'));
//			die(print_r($test));
		$lineup = (string)$test->lineup;
		$station = $test->station[0]->station;
		$errors = array();

		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		if(in_array($station, ['R-CB1', 'R-CB2'])){
			$spreadsheet = $reader->load($lineup);
			$expectedSheets = ['Typical', 'LO Lineup'];
			$sheets = $spreadsheet->getSheetNames();
			$localParams = ["temp", "v", "ch"];
		} elseif(in_array($station, ['M-CB1', 'M-CB2'])) {
			// CREATE SPREADSHEET FROM CSV
			$path = $this->excel_model->ss_from_csv($lineup);
			$spreadsheet = $reader->load($path);
			$sheets = $spreadsheet->getSheetNames();
			$localParams = ["temp", "volt", "chipchannel"];
		}
		$reader->setReadDataOnly(true);	
		if ($spreadsheet){
			foreach($sheets as $sheetName){
			//CHECK FOR SHEET EXSITENCE
				if(!in_array($sheetName, $sheets)){
					echo $sheetName." wasn't found! Might be misspelled";
					die();
				}
	// 		EXTRACTS  HIGHEST COL, HIGHEST ROW, FIRST ROW
				$currentSheet = $spreadsheet->getSheetByName($sheetName);
				$highestColumn = $currentSheet->getHighestColumn();
				$highestRow = $currentSheet->getHighestRow();
				$firstRowRaw = $currentSheet->rangeToArray('A1:'.$highestColumn.'1', null, false, false, true)[1];
	//		GET PARAMS FOUND BOTH IN EXCEL AND DB (NOT: TEMP CH V)
				$this->db->where_in('parameter_name', $firstRowRaw);
				$this->db->group_by('parameter_name');
				$match = $this->db->get('lineup_params')->result();
				
				$paramNameArr = [];
				// GET PARAM NAMES FROM $MATCH INTO ARRAY
				foreach ($match as $obj){
					$value = $obj->parameter_name;
					array_push($paramNameArr, $value);
				}

				foreach($firstRowRaw as $index => $param){
					$trimSpace = " ";
					$trimParam = trim($param, $trimSpace);
					$paramIdx = array_search($param, $paramNameArr);

					$data = new stdClass();
					if($paramIdx === false){

//					INSERT TEMP CH V INTO SQL RESULT
						if(in_array(strtolower($param), $localParams)){
							$data->parameter_name = $param;
							$data->parameter_id = -1;
							$data->parameter_range = -1;
							$data->excel_index = $index;
							array_push($match, $data);
					// CHECK IF TEMPS AND CH FROM SITE ARE FOUND IN EXCEL FILE;
							if(in_array(strtolower($param), ['temp', 'ch'])){
								$colData = $currentSheet->rangeToArray($index.'2:'.$index.$highestRow, -1, false, false, false);
								$data = array_column($colData, 0);
								$unique = array_unique($data);
								$this->excel_model->validate($test, $param, $unique);
							}
							
						}elseif($trimParam != $param){
							echo "The \"".$param."\" parameter in column ".$index." is invalid! It might contain a space!";
							die();
						}
					}else{
						$match[$paramIdx]->excel_index = $index;
					}
				}
				
	//		INSERT EXCEL INDEX TO EACH PARAM
				foreach ($match as $value){
					$name = strtolower($value->parameter_name);
	//  		GET THIS COLUMN DATA
					$col = $currentSheet->rangeToArray($value->excel_index.'2:'.$value->excel_index.$highestRow, -1, false, false, false);
					$value->rows = array_column($col, 0);
				switch($value->parameter_range){
					// RANGE NULL: VALUE IS NOT A NUMBER AND OPTIONAL(NOTE COL)
						case null:
							break;
					// RANGE 0 IS BOOLEAN VALUE
						case 0:
							$res = $this->excel_model->check_exist($value->rows, $value->parameter_range, $value->excel_index, $sheetName);
							if($res == true){
									$errors[$value->parameter_name] = $this->excel_model->check_bool($value->rows, $value->parameter_range, $value->excel_index, $sheetName);
							}else{
								$errors[$value->parameter_name] = $res;
							}
							
							break;
					// RANGE -1 IS NO RANGE, CHECKS EXISTANCE
						case -1:
							$this->excel_model->check_exist($value->rows, $value->parameter_range, $value->excel_index, $sheetName);
							if(in_array($name, ['ch', 'chipchannel'])){
							// CHECK IF CH IS VALID
								$errors[$value->parameter_name] = $this->excel_model->is_ch_valid_old($value->rows, $value->excel_index, $sheetName);
							}
							break;
					// RANGE IS NOT NULL AND NOT -1, CHECKS EXISTANCE AND IS NUMERIC
						default:
							$res = $this->excel_model->check_exist($value->rows, $value->parameter_range, $value->excel_index, $sheetName);
							if($res == true){
								$errors[$value->parameter_name] = $this->excel_model->check_num($value->rows, $value->parameter_range, $value->excel_index, $sheetName);
							}else{
								$errors[$value->parameter_name] = $res;
							}
							break;
					}
				}
			}
			
			$endRes = array();
			foreach($errors as $param => $ers){
				if(isset($errors[$param][0])){
					array_push($endRes, true);
				} else{
					array_push($endRes, false);
				}
			}
			if(in_array(true, $endRes)){
				return $errors;
			}else{
				return "Lineup is OK!";
			}
		}
	}
	
	public function sendMail(){
		$data = json_decode(file_get_contents('php://input'));
//		echo json_encode($data);
//		die();
		$testPlans = $data->tests;
		$progress = $data->progress;
//		$data = file_get_contents('php://input');
		$this->email->from("DVT - WEB");
//		$this->email->from($sender->email, $sender->username);
		$this->email->to("c_matanw@qti.qualcomm.com");
//		$this->email->to("DVT-system-all@qti.qualcomm.com");
		$this->email->subject("New Plan");

		
		$details = array();
		
		$subject = "Daily Plan";
		$title = "Plan #".$data->id." Submitted by: ".$data->user_username;
//		$body = ;
		
		foreach($testPlans as $i=>$testPlan){
			$test = new stdClass();
			$test->id = $testPlan->id;
			$test->station = $testPlan->station[0]->station;
			$test->antennas = $testPlan->antennas;
			$test->channels = array_column($testPlan->channels, 'channel');
			$test->chips = array_column($testPlan->chips, 'chip_sn');
			$test->temps = array_column($testPlan->temps, 'temp');
			$test->progress = $testPlan->progress;
			$msg = "test #".$test->id." station: ".$test->station."\n";
			array_push($details, $msg);
		}
			$this->email->message(implode("\n",$details));
//			$this->email->message(join("\n",$details));
			$this->email->send();
//		echo json_encode($details);
	}
}
