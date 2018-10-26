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
	
	public function check_lineup(){
		$data = file_get_contents('php://input');
		$res = $this->check($data);
		echo json_encode($res);
	}
	
	public function check(){
		$data = json_decode(file_get_contents('php://input'));
		$test = $data->testType[0];
		$result = array();
		$lineup = (string)$data->path;
		$station = $data->station[0]->name;

		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$spreadsheet = $reader->load($lineup);
		// ----------------------	Is Reader Exists? and not M+A station	----------------------
		if($spreadsheet && !in_array($data->station[0]->idx, ['4', '10'])){
			$fileNamePortions = explode('\\', $lineup);
			$fileName = $fileNamePortions[count($fileNamePortions)-1]; // ---- Extract File's Name
			$expectedSheets = ['Typical', 'LO Lineup'];
			$sheets = $spreadsheet->getSheetNames();
			$localParams = ["temp", "v", "ch"];
			$reader->setReadDataOnly(true);
/*	 ----------------------	Iterate over sheets	----------------------	 */
			foreach($sheets as $sheetName){
				$error = new stdClass();
/*	 ----------------------	Is Sheet Exists?	----------------------	 */
				if(!in_array($sheetName, $sheets)){
					$error->msg = $sheetName." wasn't found! Might be misspelled";
					$error->source = $fileName;
					$result->occurred = true;
					array_push($result, $error);
				}
				$sheetId = $sheetName == 'Typical' ? 0 : 1;
				//EXTRACTS  HIGHEST COL, HIGHEST ROW, FIRST ROW
				$currentSheet = $spreadsheet->getSheetByName($sheetName);
				$highestColumn = $currentSheet->getHighestColumn();
				$highestRow = $currentSheet->getHighestRow();
				$firstRowRaw = $currentSheet->rangeToArray('A1:'.$highestColumn.'1', '-1', false, false, true)[1]; // Range, default value, calcValue?, fromatValue?, indexed array?
			
				//get lineup type based on test type_idx
				$this->db->select('config_id');
				$this->db->limit('1');
				$lineupType = (int) $this->db->get_where('test_struct_view', ['test_type_id'=>$test->type_idx, 'data_type'=>'33'])->result()[0]->config_id;
				//GET PARAMS FOUND BOTH IN EXCEL AND DB (NOT: TEMP CH V)
				$this->db->select('parameter_id, parameter_name, parameter_range');
				//$match - Lineup params array ^^
				$match = $this->db->get_where('lineup_params_view', ['config_id'=>$lineupType, 'sheet_id'=>$sheetId])->result();
				$paramNameArr = [];
				//GET PARAM NAMES FROM $MATCH INTO ARRAY
				foreach ($match as $obj){
					$value = $obj->parameter_name;
					array_push($paramNameArr, $value);
				}
/*	 ----------------------	Setup Local Variables	----------------------	 */
				foreach($firstRowRaw as $index => $param){
					$error = new stdClass();
					$trimSpace = " ";
					$trimParam = trim($param, $trimSpace); //trimmed param name
					$data = new stdClass();
					if($trimParam != $param){ // if param misspelled
						$error->msg = "The \"".$param."\" parameter in column ".$index." is invalid! It might contain a space!";
						$error->source = $fileName +", " +$sheetName + " sheet";
						$error->occurred = true;
						array_push($result, $error);
					}else{ // if param spelled correctly
						$paramIdx = array_search($param, $paramNameArr);
						if($paramIdx === false){ // if param hasn't found in $paramNameArr (local params)
							//INSERT TEMP CH V INTO SQL RESULT
							if(in_array(strtolower($param), $localParams)){ //if param found in local params
								$data->parameter_name = $param;
								$data->parameter_id = -1;
								$data->parameter_range = -1;
								$data->excel_index = $index;
								array_push($match, $data);	
							}
						}else{
							//INSERT EXCEL INDEX TO EACH PARAM
							$match[$paramIdx]->excel_index = $index;
						}
					}
				}
				// Sort callback function to sort $match array alphabetically
				$sort = function($a, $b){
					return strcmp($a->excel_index, $b->excel_index);
				};
				usort($match, $sort);
/*	----------------------	^^^  Setup Local Variables ^^^ 	---------------------- */
				
/******************************************************************************************/
/*	Iterating over column, for each column looping through rows														*/
/* each row checks for parameter range:																										*/
/* 0 = boolean, -1 = no range, null = NaN, default = integer, (if NaN no existance check)	*/
/* 																																												*/
/******************************************************************************************/
				
/*	----------------------	 Actuall Lineup Checking 	---------------------- */
				
			foreach ($match as $value){
				$name = strtolower($value->parameter_name);
				//GET THIS COLUMN DATA
				$col = $currentSheet->rangeToArray($value->excel_index.'2:'.$value->excel_index.$highestRow, -1, false, false, false); // Range, default value, calcValue?, fromatValue?, indexed array?
				$value->rows = array_column($col, 0);
				switch($value->parameter_range){
				//RANGE NULL: VALUE IS NOT A NUMBER AND OPTIONAL(NOTE COL)
					case null:
						break;
				// RANGE 0 IS BOOLEAN VALUE
					case 0:
						$res = $this->excel_model->check_exist($value->rows, $value->parameter_range, $value->excel_index, $sheetName);
						if($res = true){
								$result[$value->parameter_name] = $this->excel_model->check_bool($value->rows, $value->parameter_range, $value->excel_index, $sheetName);
							}else{
							$result[$value->parameter_name] = $res;
						}
						break;
				// RANGE -1 IS NO RANGE, CHECKS EXISTANCE
					case -1:
						$this->excel_model->check_exist($value->rows, $value->parameter_range, $value->excel_index, $sheetName);
							if(in_array($name, ['ch', 'chipchannel'])){
							// CHECK IF CH IS VALID
								$result[$value->parameter_name] = $this->excel_model->is_ch_valid($value->rows, $value->excel_index, $sheetName);
							}
						break;
				// RANGE IS NOT NULL AND NOT -1, CHECKS EXISTANCE AND IS NUMERIC
					default:
						$res = $this->excel_model->check_exist($value->rows, $value->parameter_range, $value->excel_index, $sheetName);
						if($res == true){
							$result[$value->parameter_name] = $this->excel_model->check_num($value->rows, $value->parameter_range, $value->excel_index, $sheetName);
						}else{
							$result[$value->parameter_name] = $res;
						}
					break;
				}
			}	
/*	----------------------	^^^  Actuall Lineup Checking ^^^ 	---------------------- */
			}
			$endRes = array();
			foreach($result as $param => $error){
				if(is_array($error) && isset($error[0])){
					$endRes[$param] = true;
				} else{
					$endRes[$param] = false;
				}
			}
			if(in_array(true, $endRes)){
				foreach($result as $param => $errors){
					if(!$endRes[$param]){
						unset($result[$param]);
					}
				}
				return $result;
			}else{
				return "Lineup is OK!";
			}
		}
	}
}