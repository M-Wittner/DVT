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
	$this->load->model(['plan_model', 'excel_model']);
    }

	function index() {
		$this->db->order_by('date', 'desc');
//		$this->db->limit('1');
		$plans = $this->db->get('plans')->result();
			foreach($plans as $plan){
				$this->db->where('plan_id', $plan->id);
				$plan->tests = $this->db->get('tests')->result();
				if(!count($plan->tests) > 0){
					$this->db->where('plan_id', $plan->id);
					$plan->tests = $this->db->get('tests_view_new')->result();
				}
				$testProgress = 0;
				foreach($plan->tests as $test){
					$testProgress += $test->progress;			
				}
				if(count($plan->tests) > 0){
					$plan->progress = round((($testProgress / count($plan->tests))), 2);
					$this->db->where('id', $plan->id);
					$this->db->set(['progress'=>$plan->progress]);
					$this->db->update('plans');
				}
			}
		echo json_encode($plans);	
	}
	
	function Create() {
		$test_params = $this->db->get('test_params')->result();
		$paramsArr = array();
		foreach($test_params as $param){
			$paramsArr[$param->param_name] = $param->param_id;
		}
		// fetching data
		$postData = json_decode(file_get_contents('php://input'));
		
		$planData = $postData->plan;
		$plan = array(
			'user_id'=>$planData->userId,
			'user_username'=>$planData->username
		);
		
		$testsObj = $postData->test;
		if(sizeof($postData->test) > 0){
			if(!isset($planData->id)){
				$insertPlan = $this->plan_model->add_plan($plan);
				$planId = $this->plan_model->get_id($insertPlan);
//				$planId = 750;
			}else{
				$planId = $planData->id;
			}
			if(isset($planId)){
//				$planId = 750;
				foreach($testsObj as $i => $testArr){
					if(isset($testArr->notes)){
						$notes = $testArr->notes;
					} else {
						$notes = null;
					}
					$chipsArr = $testArr->chips;
					if(isset($testArr->checkLineup)){
						if($testArr->checkLineup == true){
							$res = $this->check($testArr);
						}else{
							$res = 'Lineup is OK!';
						}
					}
					if($res != "Lineup is OK!"){
						echo json_encode($res);
						die();
					}
					if(!isset($testArr->params) && $testArr->station[0]->id != 7){
						$testArr->params = $this->plan_model->config_params($testArr);
						echo "Not valid yet";
						die();
					}
					//			------------- R station test -------------
						if($testArr->station[0]->station == 'R-CB1' || $testArr->station[0]->station == 'R-CB2'){
//							echo json_encode($testArr);
//							die();
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
									if(in_array($testArr->name[0]->test_name, ['TalynM - Temperature Calibration', 'TalynM - Temperature Calibration RX'])){
										$data = array(
											'plan_id'=>$planId,
											'test_id'=>$testId,
											'chip_m_type_id'=>$chip->chip_type_id,
											'chip_m_id'=>$chip->chip_id,
										);
									}elseif(in_array($testArr->name[0]->test_name, ['TalynA - Temperature Calibration'])){
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
//								echo json_encode($testArr);
//								die();
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
		$result = $this->plan_model->delete_plan($id);
		return $result;
	}
	
	function edit(){
		$id = json_decode(file_get_contents('php://input'));
		$result = $this->plan_model->edit_test($id);
		echo json_encode($result);
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
		}
		if($result->params && $result->chips && $result->test){
			echo 'success';
		} else {
			echo $result;
		}
//			echo json_encode($result);
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
		$result = $this->plan_model->delete_test($id);
		if ($result){
			echo 'success';
		} else{
			return $result;
		}
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
		$this->db->where('plan_id', $id);
		$tests = $this->db->get('tests')->result();
		if (!count($tests) > 0){
			$this->db->where('plan_id', $id);
			$tests = $this->db->get('tests_view_new')->result();
			foreach($tests as $test){
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
			}
		}else{
			foreach($tests as $test){
				$test->chips = $this->db->get_where('test_chips', array('plan_id'=>$id, 'test_id'=>$test->id))->result();
				$chips = $test->chips;
	//			die(var_dump($test->chips));
				$c = 0;
				$e = 0;
				$r = 0;
				foreach($chips as $chip){
					if($chip->completed == true){
						$c++;
					}elseif($chip->error == true){
						$e++;
					}elseif($chip->running == true){
						$r++;
					}
				}
				if($c == count($chips)){
					$test->status = 'Completed';
				} elseif($e > 0){
					$test->status = 'Error';
				}elseif($r> 0){
					$test->status = 'In Progress';
				}else{
					$test->status = 'IDLE';
				} 
			}	
		}
		echo json_encode($tests);
	}
	
	function copyTest(){
		$data = new stdClass();
		$data->testId = json_decode(file_get_contents('php://input'));
		$rawTest = $this->plan_model->edit_test($data);
		$test = $this->plan_model->format_edit($rawTest);
		echo json_encode($test);
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
//									$this->excel_model->is_ch_valid($value->rows, $value->excel_index, $sheetName);
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
