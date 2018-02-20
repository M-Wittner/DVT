<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Filter implements PhpOffice\PhpSpreadsheet\Reader\IReadFilter {

	public function readCell($column, $row, $worksheetName = '') {
			
			if ($row < 3 && $column < 32) {
					return true;
			}
			return false;
	}
}
?>