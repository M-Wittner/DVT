<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class valid_model extends CI_Model {
	    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function validate_plan($tests){
		$result = array();
//		die(var_dump('blaaaa'));
		foreach($tests as $test){
			$result = $this->validate_test($test, $result);
		}
		return $result;
	}
	
	public function validate_test($test, $result){
		if(isset($test->checkLineup) && $test->checkLineup == true){
			$res = $this->check_lineup($test);
			if($res != "Lineup is OK!"){
				$result = $res;
			}
		}
		$result = $this->test_sweep_exist($test, $result);
		return $result;
	}
	
	public function test_sweep_exist($test, $result){
		
		$error = new stdClass();
		if(!isset($test->priority[0]->value)){
			$value = new stdClass();
			$value->value = 'null';
			array_push($test->priority, $value);
			$error->msg = 'Priority was not selected';
			$error->source = $test->station[0]->name.', '.$test->testType[0]->test_name.', priority: '.$test->priority[0]->value;
			$error->occurred = true;
			array_push($result, $error);
//				break;
		}
		if(!isset($test->station[0]->name)){
			$test->station[0]->name = 'null';
			$error->msg = 'Station was not selected';
			$error->source = $test->station[0]->name.', '.$test->testType[0]->test_name.', priority: '.$test->priority[0]->value;
			$error->occurred = true;
			array_push($result, $error);
//				break;
		}
		if(!isset($test->testType[0]->type_idx)){
			$test->testType[0]->type_idx = 'null';
			$error->msg = 'Test was not selected';
			$error->source = $test->station[0]->name.', '.$test->testType[0]->test_name.', priority: '.$test->priority[0]->value;
			$error->occurred = true;
			array_push($result, $error);
//				break;
		}
		foreach($test->sweeps as $sweepName => $sweepData){
			$err = new stdClass();
			switch($sweepData){
				case is_array($sweepData->data): //--------------	Deal with generic sweeps	--------------
//				var_dump($sweepName);
//				var_dump($sweepData->data);
					if(!isset($sweepData->data) || empty($sweepData->data)){
						$err->msg = $sweepName.' were not selected';
						$err->source = $test->station[0]->name.', '.$test->testType[0]->test_name.', priority: '.$test->priority[0]->value;
						$err->occurred = true;
						array_push($result, $err);
					}
					break;
				default: //--------------	Deal with different sweeps	--------------
					switch($sweepData->data_type){
						case 33://Linueup
							if(!isset($sweepData->data->value)){
								$err->msg = $sweepName.' were not selected';
								$err->source = $test->station[0]->name.', '.$test->testType[0]->test_name.', priority: '.$test->priority[0]->value;
								$err->occurred = true;
								array_push($result, $err);
							}
							break;
						case 60://Pin
							if(!isset($sweepData->data->from) || !isset($sweepData->data->step) || !isset($sweepData->data->to)){
								$err->msg = $sweepName.' is missing';
								$err->source = $test->station[0]->name.', '.$test->testType[0]->test_name.', priority: '.$test->priority[0]->value;
								$err->occurred = true;
								array_push($result, $err);
							}
							break;
						default:
							$err->msg = $sweepName.' were not selected';
							$err->source = $test->station[0]->name.', '.$test->testType[0]->test_name.', priority: '.$test->priority[0]->value;
							$err->occurred = true;
							array_push($result, $err);
							break;
						}
					break;
				}
			}
		return $result;
	}
	
	public function check_num($rows, $range, $xl_idx, $sheet){
		$errors = array();
		foreach($rows as $rowNum => $rowData){
			$rowNum += 2;
//					CHECK IF ROWDATA IS A NUMBER
			if(is_numeric($rowData) && $rowData != -1){
				$num = pow(2, $range);
//			CHECK IF WITHIN RANGE
				if ($rowData > $num && $rowData >= 0){
					$str = 'Cell '.$xl_idx.$rowNum." value[".$rowData."] is not in range in ".$sheet." sheet";
					array_push($errors, $str);
				}
			} else {
					$str = 'Cell '.$xl_idx.$rowNum." value[".$rowData."] is not a number in ".$sheet." sheet";
					array_push($errors, $str);
			}
		}
		$numErrors = count($errors);
		if($numErrors > 2){
			// extract line from msg.
			$faultyLine = substr(explode(' value', $errors[$numErrors-1])[0], -2);
			$msg = "Line ".$faultyLine." has illigal characters, and might be out of table's range.";
//			var_dump($msg);
//			die();
			return $msg;
		}
		return $errors;
	}
	public function check_exist($rows, $range, $xl_idx, $sheet){
		$errors = array();
		foreach($rows as $rowNum => $rowData){
			$rowNum += 2;
//			array_push($errors, $rowData);
//		CHECK IF ROWDATA NOT SET AND NOT NULL
			if(!isset($rowData)){
				$str = 'Cell '.$xl_idx.$rowNum." value[".$rowData."] is not set in ".$sheet." sheet";
				array_push($errors, $str);
			} else {
				$errors = true;
				break;
			}
		}
		return $errors;
	}
	public function check_bool($rows, $range, $xl_idx, $sheet){
		$errors = array();
		foreach($rows as $rowNum => $rowData){
			$rowNum += 2;
//			print_r($rowData."\n".$rowNum."\n".$xl_idx);
//		CHECK IF ROWDATA NOT SET AND NOT NULL
			if($rowData != 0 && $rowData != 1){
				$str = 'Cell '.$xl_idx.$rowNum." value[".$rowData."] is not a boolean in ".$sheet." sheet";
				array_push($errors, $str);
			} else {
				$errors = true;
				break;
			}
		}
		return $errors;
	}
	
	public function is_ch_valid_old($rows, $xl_idx, $sheet){
		$errors = array();
		$this->db->select('channel');
		$channels = array_column($this->db->get_where('channels', ['active'=>true])->result_array(), 'channel');
		foreach($rows as $rowNum => $rowData){
			$rowNum += 2;
//		CHECK IF CH IS VALID
			$res = array_search($rowData, $channels);
			if($res === false){
				$str = 'on cell '.$xl_idx.$rowNum." channel isn't valid in ".$sheet." sheet";
				array_push($errors, $str);
			}
		}
		return $errors;
	}
	
	public function is_ch_valid($rows, $xl_idx, $sheet){
		$errors = array();
		$validChannels = ["1","2","3","4","5","9(1+2)","10(2+3)","11(3+4)","12(4+5)"];
		foreach($rows as $i => $val){
			$val = str_replace(' ', '',$val);
			$found = in_array($val, $validChannels);
			if(!$found){
				switch($val){
					case "":
						$str = "Cell ".$xl_idx.($i+2)." is epmty";
						array_push($errors, $str);
						break;
					case $val == 6 && strlen($val) <= 3:
						$str = "Cell: ".$xl_idx.($i+2)." - Channel ".$val." is not in use in ".$sheet." sheet";
						array_push($errors, $str);
						break;
					case $val == 7 && strlen($val) <= 3:
						$str = "Cell: ".$xl_idx.($i+2)." - Please change channel ".$val." --> to 9(1+2) in ".$sheet." sheet";
						array_push($errors, $str);
						break;
					case $val == 8 && strlen($val) <= 3:
						$str = "Cell: ".$xl_idx.($i+2)." - Please change channel ".$val." --> to 10(2+3) in ".$sheet." sheet";
						array_push($errors, $str);
						break;
					case $val == 9 && strlen($val) <= 3:
						$str = "Cell: ".$xl_idx.($i+2)." - Please change channel ".$val." --> to 11(3+4) in ".$sheet." sheet";
						array_push($errors, $str);
						break;
					case $val == 9 && strlen($val) > 3:
						$str = "Cell: ".$xl_idx.($i+2)." - Please change channel ".$val." --> to 9(1+2) in ".$sheet." sheet";
						array_push($errors, $str);
						break;	
					case $val == 10 && strlen($val) <= 3:
						$str = "Cell: ".$xl_idx.($i+2)." - Please change channel ".$val." --> to 12(4+5) in ".$sheet." sheet";
						array_push($errors, $str);
						break;
					case $val == 10 && strlen($val) > 3:
						$str = "Cell: ".$xl_idx.($i+2)." - Please change channel ".$val." --> to 10(2+3) in ".$sheet." sheet";
						array_push($errors, $str);
						break;
					case $val == 11:
//					case 11 && strlen($val) <= 3:
						$str = "Cell: ".$xl_idx.($i+2)." - Please change channel ".$val." --> to 11(3+4) in ".$sheet." sheet";
						array_push($errors, $str);
						break;
					case 12:
						$str = "Cell: ".$xl_idx.($i+2)." - Please change channel ".$val." --> to 12(4+5)in ".$sheet." sheet";
						array_push($errors, $str);
						break;
					default:
						$str = "Cell ".$xl_idx.($i+2)." [".$val."] is not a valid channel in ".$sheet." sheet";
						array_push($errors, $str);
						break;
				}
			}
		}
		return $errors;
	}
	public function ss_from_csv($lineup){
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
		$reader->setSheetIndex(0);
		$reader->setDelimiter(',');
		$spreadsheet = $reader->load($lineup);
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$path = "excelLineup.xlsx";
		$writer->save($path);
//		$reader2 = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
//		$xlsx = $reader->load("excelLineup.xlsx");
		return $path;
		
	}
	
	public function validate($test, $param, $unique){
		$errors = array();
		if(isset($test->params->temp_r)){
			$temps = $test->params->temp_r;
		} elseif(isset($test->params->temp_m)){
			$temps = $test->params->temp_m;
		}
		$channels = $test->params->channel;
		$name = strtolower($param);
//		var_dump($name);
		if($name == 'temp'){
			foreach($temps as $temp){
				$res = in_array($temp, $unique);
				if($res === false){
					$str =  $temp." C is not found in excel file!";
					array_push($errors, $str);
				}
			}
		}
		if($name == 'ch'){
			foreach($channels as $ch){
				$res = in_array($ch, $unique);
				if($res == false){
					$str =  "Channel ".$ch." is not found in excel file!";
					array_push($errors, $str);
				}
			}
		}
		return $errors;
	}
	
	public function check_lineup($test){
		$data = json_decode(file_get_contents('php://input'));
		$station = $test->station[0];
		$testType = $test->testType[0];
		$this->db->select('config_id, name, data_type');
		$struct = $this->db->get_where('test_struct_view', array('station_id'=>$station->idx, 'test_type_id'=>$testType->type_idx))->result_array();//get all sweeps for this testType
		//extracting lineups from the sweeps OBJ
		$dataTypes = array_column($struct, 'data_type');
//		$lineupIdx = array();
		$lineups = array();
		for($i = 0; $i < count($struct); $i++){
			if($dataTypes[$i] == '33'){
				$sweepName = $struct[$i]['name'];
//				echo json_encode($test->sweeps->$sweepName);
//				die();
				$lineup = (string)$test->sweeps->$sweepName->data->value;
				array_push($lineups, $lineup);
			}
		}
//		echo json_encode($lineups);
//		die();
		$errors = array();
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		if(in_array($station->name, ['R-CB1', 'R-CB2'])){
			$spreadsheet = $reader->load($lineups[0]);
			$expectedSheets = ['Typical', 'LO Lineup'];
			$sheets = $spreadsheet->getSheetNames();
			$localParams = ["temp", "v", "ch"];
		} elseif(in_array($station, ['M-CB1', 'M-CB2'])) {
			// CREATE SPREADSHEET FROM CSV
			$path = $this->excel_model->ss_from_csv($lineups[0]);
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
								$errors['Excel '.$param] = $this->excel_model->validate_v2($test, $param, $unique, $lineups[0]);
//								echo json_encode($param);
//								die();
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
				if(!isset($errors[$param][0])){
					unset($errors[$param]);
				}
			}
			if(!empty($errors)){
				$result = array();
				foreach($errors as $params){
					foreach($params as $error){
						$lineupError = new stdClass();;
						$lineupError->msg = $error->msg;
						$lineupError->source = $error->source;
						$lineupError->occurred = true;
						array_push($result, $lineupError);
					}
				}
				return $result;
			}else{
				return "Lineup is OK!";
			}
		}
	}
}
