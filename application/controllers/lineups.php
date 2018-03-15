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
 		$title;
		foreach($lineups as $lineup){
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
							$res = $this->lineup_gui_model->fsTalynA($lineup, $spreadsheet);
							$title = $res;
							break;
						case 2:
							$res = $this->lineup_gui_model->fsTalynM($lineup, $spreadsheet);
							$title = $res;
							break;
					}
					break;
			}
				
		}
//		$data = file_get_contents($title);
//		$res = readfile($title);
//		force_download($title, $data);
		echo $title;
	}
}