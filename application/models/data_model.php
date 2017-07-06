<?php

class Data_model extends CI_Model {
//	function getAll() {
//		$q = $this->db->query('SELECT * FROM data');
//		if($q->num_rows() > 0) {
//			foreach($q->result() as $row) {
//				$data[] = $row;
//			}
//			return $data;
//		}
//	}
//	function getAll() {
//		$q = $this->db->get('data');
//		if($q->num_rows() > 0) {
//			foreach ($q->result() as $row) {
//				$data[] = $row;
//			}
//			return $data;
//		}
//	}
//	function getAll() {
//		$this->db->select('title, author');
//		$this->db->from('data');
//		$this->db->where('id', 2);
//		$q = $this->db->get('data');
//		if($q->num_rows() > 0) {
//			foreach ($q->result() as $row) {
//				$data[] = $row;
//			}
//			return $data;
//		}
//	}
	function getAll() {
		$sql = "SELECT title, author, contents FROM data WHERE id = ? AND author = ?";
		$q = $this->db->query($sql, array(1, 'matan wittner'));
		if($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}
}