<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class excel_model extends CI_Model {
	    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function get_init_params($spreadsheet){
//		$worksheet
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
	public function validate_v2($test, $param, $unique, $lineup){
		$errors = array();
		$Temps = (array) $test->sweeps->{'Temperatures'}->data;
		$temps = array();
		foreach($Temps as $temp){
			array_push($temps, $temp->value);
		}

		$Channels = array_column($test->sweeps->{'Channels'}->data, 'value');
		$channels = array();
		foreach($Channels as $channel){
			array_push($channels, $channel->value);
		}
		$name = strtolower($param);
//		die();
		if($name == 'temp'){
			foreach($temps as $temp){
//				var_dump($temp);
				$res = in_array($temp, $unique);
				if($res === false){
					$err = new stdClass();
					$err->msg =  $temp." C is not found in excel file!";
					$err->source =  $test->station[0]->name.', '.$test->testType[0]->test_name.', priority: '.$test->priority[0]->value.', '.basename($lineup);
					array_push($errors, $err);
				}
			}
		}
		if($name == 'ch'){
			foreach($channels as $ch){
				$res = in_array($ch, $unique);
				if($res === false){
					$err = new stdClass();
					$err->msg =  "Channel ".$ch." is not found in excel file!";
					$err->source =  $test->station[0]->name.', '.$test->testType[0]->test_name.', priority: '.$test->priority[0]->value.', '.basename($lineup);
					array_push($errors, $err);
				}
			}
		}
		return $errors;
	}
}
