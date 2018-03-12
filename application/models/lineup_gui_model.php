<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class lineup_gui_model extends CI_Model {
	    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
				$this->load->model(['excel_model']);
    }
	
	public function talynA($lineup, $spreadsheet){
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$sheetNames = ['Typical', 'LO_Lineup'];
		$spreadsheet->removeSheetByIndex(0);
		foreach($sheetNames as $sheetName){
			$workSheet = $spreadsheet->createSheet();
			$workSheet->setTitle($sheetName);
		}
		//Config Typical Sheet
		$typical = $spreadsheet->getSheetByName('Typical');
		$this->initTypical($lineup, $typical);
		$this->ConfigTypical($lineup, $typical);
		//Config LO Sheet
		$loLineup = $spreadsheet->getSheetByName('LO_Lineup');
		$this->initLoLineup($lineup, $loLineup);
		$this->configLoLineup($lineup, $loLineup);

//		$writer->save("blabla.xlsx");
		$path = "lineups/".$lineup->title.".xlsx";
		$writer->save($path);
		return $path;
	}
	
	public function talynM($lineup){
	}
	public function boardFile($lineup){
	}
	public function boardFileFW($lineup){
	}
		
	public function configTemps($tempsAdd, $temps){
		$tempsAdd = explode(',', $tempsAdd);
		foreach ($tempsAdd as $temp){
			array_push($temps, (int) $temp);
		}	
		asort($temps);
		return $temps;
	}
	
	public function configLO($array, $spreadsheet){
		$params = array();
		foreach($array as $param => $value){
			array_push($params, $param);
		}
		return $params;
	}
	
	public function initTypical($lineup, $typical){
		$params = array();
		foreach ($lineup->typical_params as $param => $value){
			if($param != "note"){
				array_push($params, $param);
			}
		}
		$params[sizeof($params)+1] = "note";
		$typical->getCell('A1')
			->setValue('Temp');
		$typical->getCell('B1')
			->setValue('V');
		$typical->getCell('C1')
			->setValue('Ch');
		$typical->fromArray($params, null, 'D1');
	}
	
	public function configTypical($lineup, $typical){
		$i = 2;
		foreach ($lineup->temps as $temp){
			foreach($lineup->channels as $channel){
				$typical->getCell('A'.$i)
					->setValue($temp);			
				$typical->getCell('B'.$i)
					->setValue($lineup->voltage);
				$typical->getCell('C'.$i)
					->setValue($channel);
				$typical->fromArray((array)$lineup->typical_params, null, 'D'.$i);
				$i++;
			}
		}
	}
	
	public function initLoLineup($lineup, $loLineup){
		$params = array();
		foreach ($lineup->aLo_params as $param => $value){
			array_push($params, $param);
		}
		$loLineup->fromArray($params, null, 'A1');
	}
	
	public function configLoLineup($lineup, $loLineup){
		$i = 2;
		$loLineup->fromArray((array)$lineup->aLo_params, null, 'A2');
	}
}
