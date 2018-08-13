<?php
header("X-Frame-Options: SOMEORIGIN");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lineups extends CI_Controller {
	public function __construct() {

	parent::__construct();
//	$this->load->library(array('session'));
	$this->load->helper('download');
	$this->load->database('');
	$this->load->model(['lineup_gui_model', 'excel_model']);
    }

	public function index() {
	}
	
	public function create(){
		$data = json_decode(file_get_contents('php://input'));
		$lineups = $data->lineups;
		$user = $data->user;
//----------------------	CREATING NEW SHEET ----------------------
		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$spreadsheet->getProperties()
			->setCreator($user->username);
		$writerXlsx = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$writerCsv = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
 		$path = "lineups/".$data->title;

		foreach($lineups as $iteration=>$lineup){
			$lineup->path = $path;
			if(isset($lineup->temp_add)){
				$lineup->temps = $this->lineup_gui_model->configTemps($lineup->temp_add, $lineup->temps);
			}
			$station = $lineup->station[0]->id;
			$lineupType = $lineup->type[0]->lineup_type_id;
			$chipType = $lineup->chip_type[0]->chip_type_id;	
			switch($station){
				case 1:
					break;
				case 2:
					break;
				case 3:
					switch($chipType){
						case 1:
							$res = $this->lineup_gui_model->fsTalynA($lineup, $spreadsheet, $iteration);
							break;
						case 2:
							$res = $this->lineup_gui_model->fsTalynM($lineup, $spreadsheet, $iteration);
							break;
					}
					break;
			}		
		}
		switch($res){
			case ".xlsx":
				$writerXlsx->save($path.$res);
				break;
			case ".csv":
				$writerCsv->save($path.$res);
				break;
		}
		echo $path.$res;
	}
	
//	public function check(){
//		$path = file_get_contents('php://input');
//		echo json_encode($data);
//	}
	
	public function check(){
		$data = json_decode(file_get_contents('php://input'));
//		echo json_encode($data);
//		die();
			$lineup = (string)$data->path;
			$station = $data->station[0]->name;

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
//					echo json_encode($paramIdx);
//					die();
					$data = new stdClass();
					if($paramIdx === false){
//					INSERT TEMP CH V INTO SQL RESULT
						if(in_array(strtolower($param), $localParams)){
							$data->parameter_name = $param;
							$data->parameter_id = -1;
							$data->parameter_range = -1;
							$data->excel_index = $index;
							array_push($match, $data);
//					// CHECK IF TEMPS AND CH FROM SITE ARE FOUND IN EXCEL FILE;
//							if(in_array(strtolower($param), ['temp', 'ch'])){
//								$colData = $currentSheet->rangeToArray($index.'2:'.$index.$highestRow, -1, false, false, false);
//								$data = array_column($colData, 0);
//								$unique = array_unique($data);
//								$this->excel_model->validate($test, $param, $unique);
//							}
							
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
					var_dump($value);
					die();
					$name = strtolower($value->parameter_name);
//							echo $name."\n";
	//  		GET THIS COLUMN DATA
					$col = $currentSheet->rangeToArray($value->excel_index.'2:'.$value->excel_index.$highestRow, -1, false, false, false);
					$value->rows = array_column($col, 0);
//					if($name == 'ch'){
//						die(var_dump($value));
//					}
				switch($value->parameter_range){
					// RANGE NULL: VALUE IS NOT A NUMBER AND OPTIONAL(NOTE COL)
						case null:
							break;
					// RANGE 0 IS BOOLEAN VALUE
						case 0:
							$res = $this->excel_model->check_exist($value->rows, $value->parameter_range, $value->excel_index, $sheetName);
							if($res = true){
									$this->excel_model->check_bool($value->rows, $value->parameter_range, $value->excel_index, $sheetName);
								}
							break;
					// RANGE -1 IS NO RANGE, CHECKS EXISTANCE
						case -1:
							$this->excel_model->check_exist($value->rows, $value->parameter_range, $value->excel_index, $sheetName);
								if(in_array($name, ['ch', 'chipchannel'])){
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