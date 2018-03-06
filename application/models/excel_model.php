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
		$this->db->select('channel');
		$channels = array_column($this->db->get_where('channels', ['active'=>true])->result(), 'channel');
		foreach($rows as $rowNum => $rowData){
			$rowNum += 2;
//		CHECK IF CH IS VALID
			$res = array_search($rowData, $channels);
			if($res === false){
				echo 'on cell '.$xl_idx.$rowNum." channel isn't valid in ".$sheet." sheet";
				die();
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
		$temps = $test->temps;
		$channels = $test->channels;
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
