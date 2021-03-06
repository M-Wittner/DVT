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
	
	public function fsTalynA($lineup, $spreadsheet, $iteration){
		$typical = $spreadsheet->getActiveSheet();
		$lineupType = $lineup->type[0]->lineup_type_id;
		switch($lineupType){
			case 1:
				//Config Typical Sheet TX
				$this->initTypicalTxA($lineup, $typical);
				$this->ConfigTypicalTxA($lineup, $typical);
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
				if($iteration == 0){
					$this->initLoLineup($lineup, $loLineup);
				}
				$this->configLoLineup($lineup, $loLineup);
				break;
			}
			$spreadsheet->getActiveSheet()->setTitle('Typical');
			return ".xlsx";
		}
		
		public function fsTalynM($lineup, $spreadsheet, $iteration){
			$typical = $spreadsheet->getActiveSheet();
			$lineupType = $lineup->type[0]->lineup_type_id;
			$this->getXIFs($lineup);
			switch($lineupType){
				case 1:
				case 2:
					//Config Typical Sheet TX
					if($iteration == 0){
						$this->initTypicalTxM($lineup, $typical, $iteration);
					}
					$this->ConfigTypicalTxM($lineup, $typical);
					break;
				case 3:
					//Config Typical Sheet TX
					if($iteration == 0){
						$this->initTypicalTxBrdFwM($lineup, $typical, $iteration);
					}
					$this->ConfigTypicalTxBrdFwM($lineup, $typical);
					break;
				case 4:
					//Config LO Sheet
					$loLineup = $spreadsheet->getSheetByName('LO_Lineup');
					if($iteration == 0){
						$this->initLoLineup($lineup, $loLineup);
					}
					$this->configLoLineup($lineup, $loLineup);
					break;
		}
			$spreadsheet->getActiveSheet()->setTitle('Typical');
		return ".xlsx";
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
		$xifOff = substr_count($XIFsw, "0")+1;
		$lineup->inactiveXIFs = array();
		for($i = 0; $i <= $xifOff; $i++){
			$int = strpos($XIFsw, "0", $i);
			if(!in_array($int, $lineup->inactiveXIFs) && $int !== false){
				array_push($lineup->inactiveXIFs, $int);
			}
		}
	}
	
	public function calcXifMatrix($xifs){
		$sum = 0;
		foreach($xifs as $xif){
			$sum = $sum + pow(2, $xif->xif);
		}
		return $sum;
	}
	
	public function initCsv($lineup, $sheet){
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
	
	public function initTypicalTxA($lineup, $typical){
		$params = array();
		foreach ($lineup->typical_params as $param => $value){
			if($param != "note"){
				array_push($params, $param);
			}
		}
		$params[sizeof($params)+1] = "Note";
		$typical->getCell('A1')
			->setValue('Temp');
		$typical->getCell('B1')
			->setValue('V');
		$typical->getCell('C1')
			->setValue('Ch');
		$typical->fromArray($params, null, 'D1');
	}
	
	public function configTypicalTxA($lineup, $typical){
		$i = $typical->getHighestRow() + 1;
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
	
	public function initTypicalTxBrd($lineup, $typical){
		$typical->getCell('A1')
			->setValue('Temp');
		$typical->getCell('B1')
			->setValue('V');
		$typical->getCell('C1')
			->setValue('Ch');
		$typical->getCell('D1')
			->setValue('gain_table_idx');
		$typical->getCell('E1')
			->setValue('Note');
		return $typical;
	}
	
	public function configTypicalTxBrd($lineup, $typical){
		$i = $typical->getHighestRow() + 1;
		foreach ($lineup->temps as $temp){
			foreach($lineup->channels as $channel){
				foreach($lineup->gain_table_idx as $idx){
					$typical->getCell('A'.$i)
						->setValue($temp);			
					$typical->getCell('B'.$i)
						->setValue($lineup->voltage);
					$typical->getCell('C'.$i)
						->setValue($channel);
					$typical->getCell('D'.$i)
						->setValue($idx);
					$typical->getCell('E'.$i)
						->setValue($lineup->note);
					$i++;
				}
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
		
	public function initTypicalTxBrdFwM($lineup, $typical, $iteration){
		$row;
		$col;
		switch($iteration){
			case 0:
				$row = 1;
				$col = 'A';
				break;
			case $iteration > 0:
				$row = $typical->getHighestRow();
				$col = $typical->getHighestColumn();
				break;
		}
//		$params = ['Temp', 'Volt', 'ChipChannel','XIF_0','XIF_1','XIF_2','XIF_3','XIF_4','XIF_5','XIF_6','XIF_7', 'XIF_Matrix'];
		$params = ['Temp', 'Volt', 'ChipChannel','MCS', 'XIF_Matrix'];
		
		$mGeneral_params = $lineup->mGeneral_params;
		foreach($mGeneral_params as $param => $value){
			array_push($params, $param);
		}
		if(isset($lineup->tx_gain_row)){
			array_push($params, 'tx_gain_row');
		}
		if(isset($lineup->typical_params)){	
			$typicalParams = $lineup->typical_params;
			foreach($typicalParams as $param => $value){
				array_push($params, $param);
			}
		}
		$typical->fromArray($params, null, "A1");
//		die(var_dump($params));
	}
	
	public function configTypicalTxBrdFwM($lineup, $typical){
		$lineupType = $lineup->type[0]->lineup_type_id;
		$i = $typical->getHighestRow() + 1;
		$xifs = $lineup->xifs;
		$inactiveXIFs = $lineup->inactiveXIFs;
		$xifsArr = $typical->rangeToArray("D1:K1", null, false, false, true)[1];
		$xifMatrix = $this->calcXifMatrix($xifs);
//		$xifsw = decbin($xifMatrix);
//		die(var_dump($xifMatrix));
		foreach ($lineup->temps as $temp){
			foreach($lineup->channels as $channel){
				if(((1 <= $channel && $channel <= 6) && ($mcs == 8 || $mcs == 12)) || ((7 <= $channel && $channel <= 10) && ($mcs == 9 || $mcs == 14))){
					foreach ($lineup->mcs as $mcs){
						foreach($lineup->xifs as $xif){
							foreach($lineup->tx_gain_row as $txRow){
								$col = "A";
								$typical->getCell($col.$i)
									->setValue($temp);
								$col++;
								$typical->getCell($col.$i)
									->setValue($lineup->voltage);
								$col++;
								$typical->getCell($col.$i)
									->setValue($channel);
								$col++;

		//						// XIF COLS
		//						for($col = "D"; $col <= "K"; $col++){
		//							if(in_array(substr($xifsArr[$col], -1), $inactiveXIFs)){
		//								$typical->setCellValue($col.$i, 0);
		//							} else{
		//								$typical->setCellValue($col.$i, 1);
		//							}
		//						}
								if((1 <= $channel && $channel <= 6)){
									$mcs = 12;
									$typical->getCell($col.$i)
										->setValue($mcs);
									$col++;
								}elseif(7 <= $channel && $channel <= 10){
									$mcs = 14;
									$typical->getCell($col.$i)
										->setValue($mcs);
									$col++;
								}
								$typical->getCell($col.$i)
									->setValue($xifMatrix);
								$col++;
								// GENERAL PARAMS
								$typical->fromArray((array)$lineup->mGeneral_params, null, $col.$i);
								$lastCol = chr(ord($col) + sizeof((array)$lineup->mGeneral_params));
								//TX GAIN ROW
								$typical->setCellValue($lastCol.$i, $txRow);
		//							$lastCol = chr(ord($col) + sizeof((array)$lineup->mGeneral_params) + 1);
								$lastCol = chr(ord($lastCol) + 1);
									$typical->getCell($lastCol.'1')
										->setValue("Note");
								if($lineupType == 3 && isset($lineup->note)){
									$typical->getCell($lastCol.$i)
										->setValue($lineup->note);
								}
								$i++;
							}
						}
					}
				}
			}
		}
	}

	public function initTypicalTxM($lineup, $typical, $iteration){
//		die(var_dump(in_array(8, $lineup->mcs)));
//		die(var_dump(in_array(array(8, 12), $lineup->mcs)));
		$row;
		$col;
		switch($iteration){
			case 0:
				$row = 1;
				$col = 'A';
				break;
			case $iteration > 0:
				$row = $typical->getHighestRow();
				$col = $typical->getHighestColumn();
				break;
		}
		//$params = ['Temp', 'Volt', 'ChipChannel','XIF_0','XIF_1','XIF_2','XIF_3','XIF_4','XIF_5','XIF_6','XIF_7', 'XIF_Matrix'];
		$params = ['Temp', 'Volt', 'ChipChannel', 'XIF_Matrix', 'MCS'];
		
		$mGeneral_params = $lineup->mGeneral_params;
		foreach($mGeneral_params as $param => $value){
			array_push($params, $param);
		}
		array_push($params, 'tx_gain_row');
		if(isset($lineup->Dac_fssel_val)){
			array_push($params, 'Dac_fssel_val');
		}
		if(isset($lineup->typical_params)){	
			$typicalParams = $lineup->typical_params;
			foreach($typicalParams as $param => $value){
				array_push($params, $param);
			}
		}
		$typical->fromArray($params, null, "A1");
	}
	
	public function configTypicalTxM($lineup, $typical){
		$lineupType = $lineup->type[0]->lineup_type_id;
		$i = $typical->getHighestRow() + 1;
		$xifs = $lineup->xifs;
		$inactiveXIFs = $lineup->inactiveXIFs;
		$xifsArr = $typical->rangeToArray("D1:K1", null, false, false, true)[1];
		$xifMatrix = $this->calcXifMatrix($xifs);
//		if(isset($lineup->Dac_fssel_val)){
//			$dacFssel= $lineup->Dac_fssel_val;
//		} else{
//			$txGainRows = 1;
//		}
		foreach ($lineup->temps as $temp){
			foreach($lineup->channels as $channel){
					foreach($lineup->mcs as $mcs){
					if(((1 <= $channel && $channel <= 6) && ($mcs == 8 || $mcs == 12)) || ((7 <= $channel && $channel <= 10) && ($mcs == 9 || $mcs == 14))){
						foreach($lineup->xifs as $xif){
							foreach($lineup->Dac_fssel_val as $value){
								$col = "A";
								$typical->getCell($col.$i)
									->setValue($temp);
								$col++;
								$typical->getCell($col.$i)
									->setValue($lineup->voltage);
								$col++;
								$typical->getCell($col.$i)
									->setValue($channel);
								$col++;

		//						// XIF COLS
		//						for($col = "D"; $col <= "K"; $col++){
		//							if(in_array(substr($xifsArr[$col], -1), $inactiveXIFs)){
		//								$typical->setCellValue($col.$i, 0);
		//							} else{
		//								$typical->setCellValue($col.$i, 1);
		//							}
		//						}
								$typical->getCell($col.$i)
									->setValue($xifMatrix);
								$col++;
								$typical->getCell($col.$i)
										->setValue($mcs);
								$col++;
//								if((1 <= $channel && $channel <= 6)){
//									if($mcs != 14 && $mcs != 8){
//										$typical->getCell($col.$i)
//											->setValue($mcs);
//										$col++;
//									}
//								}elseif(7 <= $channel && $channel <= 10){
//									if($mcs != 12 && $mcs != 9){
//										$typical->getCell($col.$i)
//											->setValue($mcs);
//										$col++;
//									}
//								}
								// GENERAL PARAMS
								$typical->fromArray((array)$lineup->mGeneral_params, null, $col.$i);
								$lastCol = chr(ord($col) + sizeof((array)$lineup->mGeneral_params));
								//TX GAIN ROW
								$typical->setCellValue($lastCol.$i, '17');
								$lastCol = chr(ord($col) + sizeof((array)$lineup->mGeneral_params) + 1);
								//Dac_fssel_val
								if(isset($lineup->Dac_fssel_val)){
									$typical->setCellValue($lastCol.$i, $value);
									$lastCol = chr(ord($col) + sizeof((array)$lineup->mGeneral_params) + 2);
								}
								// TYPICAL PARAMS
								if(isset($lineup->typical_params)){
									//GET LAST COL AFTER INSERT GENERAL PARAM BY ASCI CONVERTION.
									$typical->fromArray((array)$lineup->typical_params, null, $lastCol.$i);
								}
								if($lineupType == 3){
		//							$lastCol = chr(ord($lastCol) + 1);
									$typical->getCell($lastCol.'1')
										->setValue("Note");
									$typical->getCell($lastCol.$i)
										->setValue($lineup->note);
								}
								$i++;
							}
						}
					}
				}
			}
		}
	}
}
