<?php
header("X-Frame-Options: SOMEORIGIN");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plans extends CI_Controller {
	public function __construct() {

	parent::__construct();
	$this->load->helper(array('form','url'));
//	$this->load->library(array('session'));
	$this->load->database('');
	$this->load->model(['plan_model', 'excel_model']);
    }

	function index() {
		$this->db->order_by('date', 'desc');
		$plans = $this->db->get('plans')->result();
			foreach($plans as $plan){
				$this->db->where('plan_id', $plan->id);
				$plan->tests = $this->db->get('tests')->result();
				$testProgress = 0;
				foreach($plan->tests as $test){
					$testProgress = $testProgress + $test->progress;			
				}
				if(count($plan->tests) > 0){
					$plan->progress = round((($testProgress / count($plan->tests))), 2);
					$this->db->where('id', $plan->id);
					$this->db->set('progress', $plan->progress);
					$this->db->update('plans');
				}
			}
		echo json_encode($plans);	
	}
	
	function Create() {
		// fetching data
		$postData = json_decode(file_get_contents('php://input'));
//		die(var_dump($postData));
		$planData = $postData->plan;
		$plan = array(
			'user_id'=>$planData->userId,
			'user_username'=>$planData->username
		);
		$testsObj = $postData->test;
		if(sizeof($postData->test) > 0){
			$insertPlan = $this->plan_model->add_plan($plan);
//			$insertPlan = true;
			if($insertPlan){
				$planId = $this->plan_model->get_id($insertPlan);
//				$planId = 750;
				foreach($testsObj as $i => $testArr){
					if(isset($testArr->notes)){
						$notes = $testArr->notes;
					} else {
						$notes = null;
					}
					$chipsArr = $testArr->chips;
					$tempsArr = $testArr->temps;
					$channelsArr = $testArr->channels;
//					$res = $this->lineup($testArr);
//					die();
//			------------- R station test -------------
					if($testArr->station[0]->station == 'R-CB1' || $testArr->station[0]->station == 'R-CB2'){
						$antennasArr = $testArr->antennas;
//						var_dump($antennasArr);
//						$this->db->select('iteration_time');
//						$iteration = $this->db->get_where('params_test_iteration', ['station'=>$testArr->station[0]->station, 'test_name'=>$testArr->name[0]->test_name])->result()[0]->iteration_time;
//						if($iteration){
//							$time
//						}
						$pinFrom = null;
						$pinTo = null;
						$pinStep = null;
						$pinAdd = null;
						$pins = 1;
						$loPins = 1;
						$pinAddNum = 0;
						if(isset($testArr->pin_from) && isset($testArr->pin_step) && isset($testArr->pin_to)){
							$pinFrom = $testArr->pin_from;
							$pinTo = $testArr->pin_to;
							$pinStep = $testArr->pin_step;
//							$pins = $this->plan_model->calc_pins($pinFrom, $pinTo, $pinStep, $pinAddNum);
						}
						if(isset($testArr->pin_additional)){
								$pinAdd = $testArr->pin_additional;
							}
						
						$loPinFrom = null;
						$loPinTo = null;
						$loPinStep = null;
						$loPinAdd = null;
						if($testArr->name[0]->test_name == 'Tx EVM vs. LO Power' || $testArr->name[0]->test_name == 'Rx EVM vs. LO power'){
							$loPinFrom=$testArr->lo_pin_from;
							$loPinTo=$testArr->lo_pin_to;
							$loPinStep=$testArr->lo_pin_step;
							if(isset($testArr->lo_pin_additional)){
								$loPinAdd = $testArr->lo_pin_additional;
								$loPinAddNum = $this->plan_model->calc_pinAdd($loPinAdd);
							}
//							$loPins = $this->plan_model->calc_pins($loPinFrom, $loPinTo, $loPinStep, $loPinAddNum);
						}
						$variables = new stdClass();
						$variables->temps = sizeof($tempsArr);
						$variables->channels = sizeof($channelsArr);
						$variables->antennas = sizeof($antennasArr);
						$variables->pins = $pins;
						$variables->loPins = $loPins;
						$variables->station = $testArr->station[0]->station;
						$variables->test_name = $testArr->name[0]->test_name;
						
//						$estimate_runtime = $this->plan_model->calc_runtime($variables);
						
						if(isset($testArr->calc)){
							$time = $testArr->calc->lineups*$testArr->calc->seconds*$testArr->calc->pins*$testArr->calc->ants*$testArr->calc->temps*$testArr->calc->channels;
						} else {
							$time = null;
						}	
						
						if(isset($estimate_runtime)){
							$time = $estimate_runtime;
						}
						$test = array(
							'priority'=>$testArr->priority[0],
							'lineup'=>$testArr->lineup,
							'station'=>$testArr->station[0]->station,
							'name'=>$testArr->name[0]->test_name,
							'pin_from'=>$pinFrom,
							'pin_to'=>$pinTo,
							'pin_step'=>$pinStep,
							'pin_additional'=>$pinAdd,
							'lo_pin_from'=>$loPinFrom,
							'lo_pin_to'=>$loPinTo,
							'lo_pin_step'=>$loPinStep,
							'lo_pin_additional'=>$loPinAdd,
							'mcs'=>$testArr->mcs,
							'voltage'=>$testArr->voltage,
							'notes'=>$notes,
							'time'=>$time,
							'plan_id'=>$planId
						);
						$insertTest = $this->plan_model->add_test($test);
						$testId = $this->plan_model->tests_id($insertTest);
						foreach($chipsArr as $result){
//							$path = "\\\\filer4\\fileserver\Projects\dvt\Results\\test_results\\" .$result->chip. "\TalynA_YA591-H511_Flip_Chip_QCA6425_B0_".$result->serial_number;
							$chip = array(
								'serial_number'=> $result->serial_number,
								'chip'=>$result->chip,
//								'results_path'=>$path,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$this->plan_model->add_chips($chip);
						};
						foreach($tempsArr as $result){
							$temp = array(
								'temp'=>$result,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$this->plan_model->add_temps($temp);
						};
						foreach($channelsArr as $result){
//							var_dump($result);
							$channel = array(
								'channel'=>$result,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$this->plan_model->add_channels($channel);
						};
//						die();
						foreach($antennasArr as $result){
							$antenna = array(
								'antenna'=>$result,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$this->plan_model->add_antennas($antenna);
//							var_dump($result);
						};
////						die();
////			------------- M station test -------------
					} else if($testArr->station[0]->station == 'M-CB1' || $testArr->station[0]->station == 'M-CB2' || $testArr->station[0]->station == 'Calibration'){
						
						$variables = new stdClass();
						$variables->temps = sizeof($tempsArr);
						$variables->channels = sizeof($channelsArr);
						$variables->xifs = sizeof($testArr->xifs);
						$variables->station = $testArr->station[0]->station;
						$variables->test_name = $testArr->name[0]->test_name;
						
//						$estimate_runtime = $this->plan_model->calc_runtime($variables);
						if(isset($testArr->calc)){
							$time = $testArr->calc->lineups*$testArr->calc->seconds*$testArr->calc->pins*$testArr->calc->ants*$testArr->calc->temps*$testArr->calc->channels;
						} else {
							$time = null;
						}	
						
						if(isset($estimate_runtime)){
							$time = $estimate_runtime;
						}
						$test = array(
							'priority'=>$testArr->priority[0],
							'lineup'=>$testArr->lineup,
							'station'=>$testArr->station[0]->station,
							'name'=>$testArr->name[0]->test_name,
							'voltage'=>$testArr->voltage,
							'notes'=>$notes,
							'time'=>$time,
							'plan_id'=>$planId
						);
						if($testArr->name[0]->test_name == 'Temp-Calibration' || $testArr->name[0]->test_name == 'PTAT Calibration'){
							$chipsArr = $testArr->chips;
							$insertTest = $this->plan_model->add_test($test);
								$testId = $this->plan_model->tests_id($insertTest);
								foreach($chipsArr as $result){
//								$path = "\\\\filer4\\fileserver\Projects\dvt\Results\test_results\\" .$result->chip. "\TalynM_YA591-H2_Flip_Chip_QCA6425_A0_".$result->serial_number;
									$chip = array(
										'serial_number'=>$result->serial_number,
										'chip'=>$result->chip,
//									'results_path'=>$path,
										'plan_id'=>$planId,
										'test_id'=>$testId
									);
									$insertChip = $this->plan_model->add_chips($chip);
								}
							} else{
							$chipsArr = $testArr->chips;
							$tempsArr = $testArr->temps;
							$xifsArr = $testArr->xifs;
	//						var_dump($xifsArr);
							$channelsArr = $testArr->channels;
								if(isset($testArr->calc)){
									$time = $testArr->calc->lineups*$testArr->calc->seconds*$testArr->calc->xifs*$testArr->calc->sweeps*$testArr->calc->temps*$testArr->calc->channels;
								} else {
									$time = null;
								}
								if($testArr->station[0]->station == 'Calibration'){
									if(isset($testArr->miniC)){
										$test['mini_circuits'] = $testArr->miniC;
									}else{ 
										$test['mini_circuits'] = false;
									}
									$test['mcs'] = $testArr->mcs;
								}
								$insertTest = $this->plan_model->add_test($test);
								$testId = $this->plan_model->tests_id($insertTest);
								foreach($chipsArr as $result){
		//							$path = "\\\\filer4\\fileserver\Projects\dvt\Results\test_results\\" .$result->chip. "\TalynM_YA591-H2_Flip_Chip_QCA6425_A0_".$result->serial_number;
									$chip = array(
										'serial_number'=>$result->serial_number,
		//								'results_path'=>$path,
										'plan_id'=>$planId,
										'test_id'=>$testId
									);
									$insertChip = $this->plan_model->add_chips($chip);
									$chipId = $this->db->insert_id($insertChip);
									$xifs = array();
									foreach($xifsArr as $xifRes){
										$xif = array(
											'chip_id'=>$chipId,
											'chip'=>$chip['serial_number'],
											'xif'=>$xifRes->xif,
											'plan_id'=>$planId,
											'test_id'=>$testId
										);
										array_push($xifs, $xif);
									};
		//							var_dump($xifs);
		//							die();
									$this->db->insert_batch('test_xifs', $xifs);
								};
								foreach($tempsArr as $result){
									$temp = array(
										'temp'=>$result,
										'plan_id'=>$planId,
										'test_id'=>$testId
									);
									$this->plan_model->add_temps($temp);
				//					print_r($temp);
								};
								foreach($channelsArr as $result){
									$channel = array(
										'channel'=>$result,
										'plan_id'=>$planId,
										'test_id'=>$testId
									);
									$this->plan_model->add_channels($channel);
		//							print_r($channel);
							}
						};
//						die();
//------------ PTAT/ABS/Vgb+TEMP and TalynM+A station test -------------
					} elseif($testArr->station[0]->station == 'PTAT/ABS/Vgb+TEMP') {
							$chipsArr = $testArr->chips;
							$test = array(
								'priority'=>$testArr->priority[0],
								'lineup'=>null,
								'station'=>$testArr->station[0]->station,
								'name'=>$testArr->name[0]->test_name,
								'notes'=>$notes,
								'plan_id'=>$planId
							);
							$insertTest = $this->plan_model->add_test($test);
							$testId = $this->plan_model->tests_id($insertTest);
							foreach($chipsArr as $result){
								$chip = array(
									'chip'=>$result->serial_number,
									'chip'=>$result->chip,
									'plan_id'=>$planId,
									'test_id'=>$testId
								);
								$insertChip = $this->plan_model->add_chips($chip);
							}
					} elseif($testArr->station[0]->station == 'TalynM+A'){
//						die(var_dump($testArr));
							$aChipsArr = $testArr->chips;
							$mChipsArr = $testArr->mChips;
							$test = array(
								'priority'=>$testArr->priority[0],
								'lineup'=>$testArr->lineup,
								'm_lineup'=>$testArr->m_lineup,
								'station'=>$testArr->station[0]->station,
								'name'=>$testArr->name[0]->test_name,
								'notes'=>$notes,
								'plan_id'=>$planId
							);
							$insertTest = $this->plan_model->add_test($test);
							$testId = $this->plan_model->tests_id($insertTest);
							foreach($aChipsArr as $result){
								$chip = array(
									'serial_number'=>$result->serial_number,
									'chip'=>$result->chip,
									'plan_id'=>$planId,
									'test_id'=>$testId
								);
								$insertChip = $this->plan_model->add_chips($chip);
							}
							foreach($mChipsArr as $result){
								$chip = array(
									'serial_number'=>$result->serial_number,
									'chip'=>$result->chip,
									'plan_id'=>$planId,
									'test_id'=>$testId
								);
								$insertChip = $this->plan_model->add_chips($chip);
							}
					} else {
						echo 'not valid station';
					}
//					var_dump($test);
			};
//				die();
			echo 'success';
			} else {
				echo 'No plan inserted!';
			}	
		} else {
			echo 'No Test Detected!';
		}
	}
	
	function Show() {
		$id = json_decode(file_get_contents('php://input'));
		$result = $this->plan_model->get_plan($id);
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
		$test = $this->db->get_where('tests', array('id'=>$id->testId))->result();
		$chips = $this->db->get_where('test_chips', array('test_id'=>$id->testId))->result();
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
			$res = array(
				'comment' => $this->db->get_where('test_comments', array('id'=>$data->commentId))->result(),
				'test' => $this->db->get_where('tests', array('id'=>$data->testId))->result(),
				'chips' => $this->db->get_where('test_chips', array('test_id'=>$data->testId))->result()
			);
		echo json_encode($res);
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
		$postData = json_decode(file_get_contents('php://input'));
		$plan = $this->plan_model->update_test($postData);
		if($plan){
			echo 'success';
		} else {
			echo 'Test Was not updated';
		}
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
	
	function addtests(){
		// fetching data
		$postData = json_decode(file_get_contents('php://input'));
//		die(var_dump($postData));
		// plan data
		$planData = $postData->plan;
		$plan = array(
			'user_id'=>$planData->userId,
			'user_username'=>$planData->username,
			'id'=>$planData->id
		);
		$testsObj = $postData->test;
//		die(var_dump($testsObj));
		if(sizeof($postData->test) > 0){
			$planId = $planData->id;
			if(isset($planId)){
				foreach($testsObj as $i => $testArr){
					if(isset($testArr->notes)){
						$notes = $testArr->notes;
					} else {
						$notes = null;
					}
//			------------- R station test -------------
					if($testArr->station[0]->station == 'R-CB1' || $testArr->station[0]->station == 'R-CB2'){
//						var_dump($testArr);
//						die();
						$chipsArr = $testArr->chips;
						$tempsArr = $testArr->temps;
						$channelsArr = $testArr->channels;
						$antennasArr = $testArr->antennas;
//						die(var_dump($testArr));
//						if(isset($testArr->calc)){
//							$time = $testArr->calc->ants*$testArr->calc->lineups*$testArr->calc->seconds*$testArr->calc->pins;
//						} else {
							$time = null;
//						}
						$pinFrom = null;
						$pinTo = null;
						$pinStep = null;
						$pinAdd = null;
						$pins = 1;
						$loPins = 1;
						$pinAddNum = 0;
						if(isset($testArr->pin_from) && isset($testArr->pin_step) && isset($testArr->pin_to)){
							$pinFrom = $testArr->pin_from;
							$pinTo = $testArr->pin_to;
							$pinStep = $testArr->pin_step;
//							$pins = $this->plan_model->calc_pins($pinFrom, $pinTo, $pinStep, $pinAddNum);
						}
						if(isset($testArr->pin_additional)){
								$pinAdd = $testArr->pin_additional;
							}
						
						$loPinFrom = null;
						$loPinTo = null;
						$loPinStep = null;
						$loPinAdd = null;
						if(isset($testArr->lo_pin_from) && isset($testArr->lo_pin_step) && isset($testArr->lo_pin_to)){
							$loPinFrom=$testArr->lo_pin_from;
							$loPinTo=$testArr->lo_pin_to;
							$loPinStep=$testArr->lo_pin_step;
						}
						if(isset($testArr->lo_pin_additional)){
							$loPinAdd = $testArr->lo_pin_additional;
							$loPinAddNum = $this->plan_model->calc_pinAdd($loPinAdd);
						}
						$variables = new stdClass();
						$variables->temps = sizeof($tempsArr);
						$variables->channels = sizeof($channelsArr);
						$variables->antennas = sizeof($antennasArr);
						$variables->pins = $pins;
						$variables->loPins = $loPins;
						$variables->station = $testArr->station[0]->station;
						$variables->test_name = $testArr->name[0]->test_name;
						
//						$estimate_runtime = $this->plan_model->calc_runtime($variables);
						
						if(isset($testArr->calc)){
							$time = $testArr->calc->lineups*$testArr->calc->seconds*$testArr->calc->pins*$testArr->calc->ants*$testArr->calc->temps*$testArr->calc->channels;
						} else {
							$time = null;
						}	
						
						if(isset($estimate_runtime)){
							$time = $estimate_runtime;
						}
						$test = array(
							'priority'=>$testArr->priority[0],
							'lineup'=>$testArr->lineup,
							'station'=>$testArr->station[0]->station,
							'name'=>$testArr->name[0]->test_name,
							'pin_from'=>$pinFrom,
							'pin_to'=>$pinTo,
							'pin_step'=>$pinStep,
							'pin_additional'=>$pinAdd,
							'lo_pin_from'=>$loPinFrom,
							'lo_pin_to'=>$loPinTo,
							'lo_pin_step'=>$loPinStep,
							'lo_pin_additional'=>$loPinAdd,
							'mcs'=>$testArr->mcs,
							'voltage'=>$testArr->voltage,
							'notes'=>$notes,
							'time'=>$time,
							'plan_id'=>$planId
						);
						$insertTest = $this->plan_model->add_test($test);
						$testId = $this->plan_model->tests_id($insertTest);
						foreach($chipsArr as $result){
//							$path = "\\\\filer4\\fileserver\Projects\dvt\Results\\test_results\\" .$result->chip. "\TalynA_YA591-H511_Flip_Chip_QCA6425_B0_".$result->serial_number;
							$chip = array(
								'serial_number'=> $result->serial_number,
								'chip'=>$result->chip,
//								'results_path'=>$path,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$this->plan_model->add_chips($chip);
						};
						foreach($tempsArr as $result){
							$temp = array(
								'temp'=>$result,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$this->plan_model->add_temps($temp);
						};
						foreach($channelsArr as $result){
//							var_dump($result);
							$channel = array(
								'channel'=>$result,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$this->plan_model->add_channels($channel);
						};
//						die();
						foreach($antennasArr as $result){
							$antenna = array(
								'antenna'=>$result,
								'plan_id'=>$planId,
								'test_id'=>$testId
							);
							$this->plan_model->add_antennas($antenna);
//							var_dump($result);
						};
//			------------- M station test -------------
					} else if($testArr->station[0]->station == 'M-CB1' || $testArr->station[0]->station == 'M-CB2' || $testArr->station[0]->station == 'Calibration'){
						$test = array(
							'priority'=>$testArr->priority[0],
							'lineup'=>$testArr->lineup,
							'station'=>$testArr->station[0]->station,
							'name'=>$testArr->name[0]->test_name,
							'voltage'=>$testArr->voltage,
							'notes'=>$notes,
							'plan_id'=>$planId
						);
						if($testArr->name[0]->test_name == 'Temp-Calibration' || $testArr->name[0]->test_name == 'PTAT Calibration'){
							$chipsArr = $testArr->chips;
							$insertTest = $this->plan_model->add_test($test);
								$testId = $this->plan_model->tests_id($insertTest);
								foreach($chipsArr as $result){
//								$path = "\\\\filer4\\fileserver\Projects\dvt\Results\test_results\\" .$result->chip. "\TalynM_YA591-H2_Flip_Chip_QCA6425_A0_".$result->serial_number;
									$chip = array(
										'serial_number'=>$result->serial_number,
										'chip'=>$result->chip,
//									'results_path'=>$path,
										'plan_id'=>$planId,
										'test_id'=>$testId
									);
									$insertChip = $this->plan_model->add_chips($chip);
								}
							} else{
							$chipsArr = $testArr->chips;
							$tempsArr = $testArr->temps;
							$xifsArr = $testArr->xifs;
	//						var_dump($xifsArr);
							$channelsArr = $testArr->channels;
								if(isset($testArr->calc)){
									$time = $testArr->calc->lineups*$testArr->calc->seconds*$testArr->calc->xifs*$testArr->calc->sweeps*$testArr->calc->temps*$testArr->calc->channels;
								} else {
									$time = null;
								}
								if($testArr->station[0]->station == 'Calibration'){
									if(isset($testArr->miniC)){
										$test['mini_circuits'] = $testArr->miniC;
									}else{ 
										$test['mini_circuits'] = false;
									}
									$test['mcs'] = $testArr->mcs;
								}
								$insertTest = $this->plan_model->add_test($test);
								$testId = $this->plan_model->tests_id($insertTest);
								foreach($chipsArr as $result){
		//							$path = "\\\\filer4\\fileserver\Projects\dvt\Results\test_results\\" .$result->chip. "\TalynM_YA591-H2_Flip_Chip_QCA6425_A0_".$result->serial_number;
									$chip = array(
										'serial_number'=>$result->serial_number,
										'chip'=>$result->chip,
		//								'results_path'=>$path,
										'plan_id'=>$planId,
										'test_id'=>$testId
									);
									$insertChip = $this->plan_model->add_chips($chip);
									$chipId = $this->db->insert_id($insertChip);
									$xifs = array();
									foreach($xifsArr as $xifRes){
										$xif = array(
											'chip_id'=>$chipId,
											'chip'=>$chip['serial_number'],
											'xif'=>$xifRes->xif,
											'plan_id'=>$planId,
											'test_id'=>$testId
										);
										array_push($xifs, $xif);
									};
		//							var_dump($xifs);
		//							die();
									$this->db->insert_batch('test_xifs', $xifs);
								};
								foreach($tempsArr as $result){
									$temp = array(
										'temp'=>$result,
										'plan_id'=>$planId,
										'test_id'=>$testId
									);
									$this->plan_model->add_temps($temp);
				//					print_r($temp);
								};
								foreach($channelsArr as $result){
									$channel = array(
										'channel'=>$result,
										'plan_id'=>$planId,
										'test_id'=>$testId
									);
									$this->plan_model->add_channels($channel);
		//							print_r($channel);
							}
						};
//------------ PTAT/ABS/Vgb+TEMP and TalynM+A station test -------------
					} elseif($testArr->station[0]->station == 'PTAT/ABS/Vgb+TEMP'|| $testArr->station[0]->station == 'TalynM+A') {
							$chipsArr = $testArr->chips;
							$test = array(
								'priority'=>$testArr->priority[0],
								'lineup'=>'/',
								'station'=>$testArr->station[0]->station,
								'name'=>$testArr->name[0]->test_name,
								'notes'=>$notes,
								'plan_id'=>$planId
							);
							$insertTest = $this->plan_model->add_test($test);
							$testId = $this->plan_model->tests_id($insertTest);
							foreach($chipsArr as $result){
								$chip = array(
									'serial_number'=>$result->serial_number,
									'chip'=>$result->chip,
									'plan_id'=>$planId,
									'test_id'=>$testId
								);
								$insertChip = $this->plan_model->add_chips($chip);
							}
					} else {
						echo 'not R or M station';
					}
			};
			echo 'success';
			} else {
				echo 'No plan inserted!';
			}	
		} 
		else {
			echo 'No Test Detected!';
		}
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
	function removeComment(){
		$id = json_decode(file_get_contents('php://input'));
		$result = $this->plan_model->delete_comment($id);
		return $result;
	}
	
	function planStatus(){
		$id = json_decode(file_get_contents('php://input'));
		$this->db->where('plan_id', $id);
		$tests = $this->db->get('tests')->result();
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
		echo json_encode($tests);
	}
	
	function copyTest(){
		$id = json_decode(file_get_contents('php://input'));
		$test = $this->db->get_where('tests', array('id'=>$id))->result()[0];
		$test->name = $this->db->get_where('params_test_names', array('test_name'=>$test->name, 'station'=>$test->station[0]))->result();
		if($test->station == 'M-CB1' || $test->station == 'M-CB2') {
			$this->db->select('xif');
			$this->db->from('test_xifs');
			$this->db->where('test_id', $test->id);
			$xifRes = $this->db->get()->result();
			$test->xifs = array();
			if(isset($xifRes)){
				foreach($xifRes as $value){
					array_push($test->xifs, $value->xif);
				}
			} else {
				$xifRes = null;
			}

		} else if($test->station == 'R-CB1' || $test->station == 'R-CB2'){
			$test->antennas = array();
			$ant = $this->db->get_where('test_antennas', array('test_id'=>$test->id))->result();
			if(isset($ant)){
				foreach($ant as $value){
					array_push($test->antennas, $value->antenna);
					}
			} else{
				$ant = null;
			}
		}
		$test->channels = array();
		if($test->station !='TalynM+A'){
			$this->db->select('channel');
			$ch = $this->db->get_where('test_channels', array('test_id'=>$test->id))->result();
			if(isset($ch)){
				foreach($ch as $value){
						array_push($test->channels, $value->channel);
					}
			}else{
				$ch = null;
			}
		}

		$chip = $this->db->get_where('test_chips', array('test_id'=>$test->id))->result();
		$test->chips = $chip;
		$temp = $this->db->get_where('test_temps', array('test_id'=>$test->id))->result();
		foreach($temp as $i => $value){
			$temp[$i] = $value->temp;
		}
		$test->temps = $temp;
		$test->station = $this->db->get_where('params_stations', array('station'=>$test->station))->result();
		echo json_encode($test);
	}
	

		public function lineups(){
			$i = 0;
			$arr = [];
			while($i < 20){
				$num = rand(0, 100);
				switch($i){
					case 0:
						array_push($arr, $num);
						break;
					default:
						$avg = array_sum($arr)/count($arr);
						
				}
				array_push($arr, $num);
				$i++;
			}
			
			foreach ($arr as $index => $number){
				
			}
			echo json_encode($arr);
		}
	
		public function lineup($test){
//			die(print_r($test));
			$lineup = (string)$test->lineup;
			$station = $test->station[0]->station;

		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		if(in_array($station, ['R-CB1', 'R-CB2'])){
			$sheets = ['Typical', 'LO Lineup'];
			$spreadsheet = $reader->load($lineup);
		} elseif(in_array($station, ['M-CB1', 'M-CB2'])) {
			// CREATE SPREADSHEET FROM CSV
			$path = $this->excel_model->ss_from_csv($lineup);
			$spreadsheet = $reader->load($path);
			$sheets = $spreadsheet->getSheetNames();
		}
		$reader->setReadDataOnly(true);	
		if ($spreadsheet){
			foreach($sheets as $sheetName){
	// 		EXTRACTS  HIGHEST COL, HIGHEST ROW, FIRST ROW
				$currentSheet = $spreadsheet->getSheetByName($sheetName);

				$highestColumn = $currentSheet->getHighestColumn();
				$highestRow = $currentSheet->getHighestRow();
//				die(print($highestColumn));
				$firstRowRaw = $currentSheet->rangeToArray('A1:'.$highestColumn.'1', null, false, false, true)[1];
	//		GET PARAMS FOUND BOTH IN EXCEL AND DB (NOT: TEMP CH V)
				$this->db->where_in('parameter_name', $firstRowRaw);
				$match = $this->db->get('lineup_params')->result();

				$paramNameArr = array_column($match, 'parameter_name');
//				var_dump($firstRowRaw);
//				die();
				foreach($firstRowRaw as $index => $param){
//					var_dump($index."     index");
					$paramIdx = array_search($param, $paramNameArr);
					$data = new stdClass();
	//		INSERT TEMP CH V INTO SQL RESULT
					if($paramIdx == false){
							$data->parameter_name = $param;
							$data->parameter_id = -1;
							$data->parameter_range = -1;
							$data->excel_index = $index;
							array_push($match, $data);
					}else{
						
						$match[$paramIdx]->excel_index = $index;
					}
//					var_dump($param."    param");
				}
				die();
//				foreach ($match as $value){
//					print($value->excel_index."\n");
//				}

	//		INSERT EXCEL INDEX TO EACH PARAM
				foreach ($match as $value){

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
}