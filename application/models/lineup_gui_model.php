<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class lineup_gui_model extends CI_Model {
	    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
				$this->load->model(['excel_model']);
    }
	
	public function rTalynA($lineup, $spreadsheet){
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$sheetNames = ['Typical'];
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
	
	public function rTalynM($lineup, $spreadsheet){
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
		$spreadsheet->getProperties()->setTitle($lineup->title);
		$csvSheet = $spreadsheet->getActiveSheet();
		$direction = $lineup->direction[0]->id;
		
//---------extract active and inactive xifs
		$this->getXIFs($lineup);
//---------CONFIG CSV
		$this->initCsv($lineup, $csvSheet);
		$this->configCsv($lineup, $csvSheet);
		$path = "lineups/".$lineup->title.".csv";
		$writer->save($path);
		return $path;
	}
	
	public function fsTalynA($lineup, $spreadsheet){
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$sheetNames = ['Typical'];
		$spreadsheet->removeSheetByIndex(0);
		foreach($sheetNames as $sheetName){
			$workSheet = $spreadsheet->createSheet();
			$workSheet->setTitle($sheetName);
		}
		$lineupType = $lineup->type[0]->lineup_type_id;
		$typical = $spreadsheet->getSheetByName('Typical');
		switch($lineupType){
			case 1:
				//Config Typical Sheet TX
				$this->initTypicalTx($lineup, $typical);
				$this->ConfigTypicalTx($lineup, $typical);
				break;
			case 2:
			case 3:
				//Config Typical Sheet TX brd
				$this->initTypicalTxBrd($lineup, $typical);
				$this->ConfigTypicalTxBrd($lineup, $typical);
				break;
			case 4:
				//Config LO Sheet
				$loLineup = $spreadsheet->getSheetByName('LO_Lineup');
				$this->initLoLineup($lineup, $loLineup);
				$this->configLoLineup($lineup, $loLineup);
				break;
		}
		

//		$writer->save("blabla.xlsx");
		$path = "lineups/".$lineup->title.".xlsx";
		$writer->save($path);
		return $path;
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
	
	public function getXIFs($lineup){
		$XIFsw = "00000000";
		$xifs = $lineup->xifs;
		foreach($xifs as $xif){
			$XIFsw = substr_replace($XIFsw, "1", substr($xif->xif, -1), 1);
		}
		$lineup->activeXIFs = $XIFsw;
		$xifOff = substr_count($XIFsw, "0");
		$lineup->inactiveXIFs = array();
		for($i = 0; $i< $xifOff; $i++){
			$int = strpos($XIFsw, "0", $i);
			array_push($lineup->inactiveXIFs, $int);
		}
	}
	
	public function initCsv($lineup, $sheet){
//		echo json_encode($lineup);
//		die();
		$params = ['Lineup_Index', 'Temp', 'Volt', 'ChipChannel','XIF_0','XIF_1','XIF_2','XIF_3','XIF_4','XIF_5','XIF_6','XIF_7', 'XIF_Matrix'];
		
		$mGeneral_params = $lineup->mGeneral_params;
		foreach($mGeneral_params as $param => $value){
			array_push($params, $param);
		}
		if($lineup->direction[0]->id == 1){
			array_push($params, 'XIFsw');
		}
		$typicalParams = $lineup->typical_params;
		foreach($typicalParams as $param => $value){
			array_push($params, $param);
		}
		$sheet->fromArray($params, null, 'A1');
//		echo json_encode($params);
//		die();
	}
	
	public function configCsv($lineup, $sheet){
//		echo json_encode($lineup);
//		die();
		$xifs = $lineup->xifs;
		$inactiveXIFs = "\"".$lineup->inactiveXIFs."\"";
		$row = 2;
//		$col = "A";
		$lineupIndex = 1;
		$xifsArr = $sheet->rangeToArray("E1:L1", null, false, false, true)[1];
		foreach($lineup->temps as $temp){
			foreach($lineup->channels as $channel){
				foreach($xifs as $k=>$xif){
					$sheet->setCellValue("A".$row, $lineupIndex);
					$lineupIndex++;
					$sheet->setCellValue("B".$row, $temp);
					$sheet->setCellValue("C".$row, $lineup->voltage);
					$sheet->setCellValue("D".$row, $channel);
					//XIF COLS
					for($col = "E"; $col <= "L"; $col++){
						if(in_array(substr($xifsArr[$col], -1), $inactiveXIFs)){
							$sheet->setCellValue($col.$row, 0);
						} else{
							$sheet->setCellValue($col.$row, 1);
						}
					}
					$sheet->setCellValue("M".$row, $xif->xif);
					$sheet->fromArray((array)$lineup->mGeneral_params, null, "N".$row);
					$col = "U";
					if($lineup->direction[0]->id == 1){
						$sheet->setCellValue($col.$row, $lineup->activeXIFs);
						$col++;
					}
					$sheet->fromArray((array)$lineup->typical_params, null, $col.$row);
					$row++;
				}
			}
		}
		
	}
	
	public function initTypicalTx($lineup, $typical){
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
	
	public function configTypicalTx($lineup, $typical){
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
