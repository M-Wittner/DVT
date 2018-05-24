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
		foreach($rows as $rowNum => $rowData){
			$rowNum += 2;
//					CHECK IF ROWDATA IS A NUMBER
			if(is_numeric($rowData) && $rowData != -1){
				$num = pow(2, $range);
//			CHECK IF WITHIN RANGE
				if ($rowData > $num && $rowData >= 0){
					echo 'cell '.$xl_idx.$rowNum." value is not in range in ".$sheet." sheet";
					die();
				}
			} else {
					echo 'cell '.$xl_idx.$rowNum." value is not a number in ".$sheet." sheet";
					die();
			}
		}
	}
	public function check_exist($rows, $range, $xl_idx, $sheet){
		foreach($rows as $rowNum => $rowData){
			$rowNum += 2;
//			print_r($rowData."\n".$rowNum."\n".$xl_idx);
//		CHECK IF ROWDATA NOT SET AND NOT NULL
			if(!isset($rowData)){
				echo 'cell '.$xl_idx.$rowNum." value is not set in ".$sheet." sheet";
				die();
			} else {
				return true;
			}
		}
	}
	public function check_bool($rows, $range, $xl_idx, $sheet){
		foreach($rows as $rowNum => $rowData){
			$rowNum += 2;
//			print_r($rowData."\n".$rowNum."\n".$xl_idx);
//		CHECK IF ROWDATA NOT SET AND NOT NULL
			if($rowData != 0 && $rowData != 1){
				echo 'cell '.$xl_idx.$rowNum." value is not a boolean in ".$sheet." sheet";
				die();
			} else {
				return true;
			}
		}
	}
	
	public function is_ch_valid($rows, $xl_idx, $sheet){
//	public function is_ch_valid($rows, $xl_idx, $sheet){
//		$this->db->select('channel');
//		$channels = array_column($this->db->get_where('channels', ['active'=>true])->result(), 'channel');
//		foreach($rows as $rowNum => $rowData){
//			$rowNum += 2;
////		CHECK IF CH IS VALID
//			$res = array_search($rowData, $channels);
//			if($res === false){
//				echo 'on cell '.$xl_idx.$rowNum." channel isn't valid in ".$sheet." sheet";
//				die();
//			}
//		}
//		die(var_dump($xl_idx));
		$validChannels = ["1","2","3","4","5","9(1+2)","10(2+3)","11(3+4)","12(4+5)"];
		foreach($rows as $i => $val){
			$val = str_replace(' ', '',$val);
			$found = in_array($val, $validChannels);
			if(!$found){
				switch($val){
					case "":
						echo "Cell ".$xl_idx.($i+2)." is epmty";
						die();
						break;
					case 6:
						echo "Channel 6 in cell ".$xl_idx($i+2)." is not in usein ".$sheet." sheet";
						die();
						break;
					case "7":
						echo "Please change channel 7 in cell ".$xl_idx.($i+2)." to 9(1+2)in ".$sheet." sheet";
						die();
						break;
					case 8:
						echo "Please change channel 8 in cell ".$xl_idx.($i+2)." to 10(2+3)in ".$sheet." sheet";
						die();
						break;
					case 9:
						echo "Please change channel 9 in cell ".$xl_idx.($i+2)." to 11(3+4)in ".$sheet." sheet";
						die();
						break;
					case 10:
						echo "Please change channel 10 in cell ".$xl_idx.($i+2)." to 12(4+5)in ".$sheet." sheet";
						die();
						break;
					default:
						echo "Cell ".$xl_idx.($i+2)." (".$val.") is not a valid channel in ".$sheet." sheet";
						die();
						break;
				}
			}
		}
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
					echo $temp." C is not found in excel file!";
					die();
				}
			}
		}
		if($name == 'ch'){
			foreach($channels as $ch){
				$res = in_array($ch, $unique);
				if($res == false){
					echo "Channel ".$ch." is not found in excel file!";
					die();
				}
			}
		}
	}
}
