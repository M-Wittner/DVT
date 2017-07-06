<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class reportModel extends CI_Model {
	function Reports() {
		$q = $this->db->get('reports');
		return $q->result();
	}

    public function Update($report)
    {
        if(is_object($report)){
            $this->db->where('id', $report->id);
            return $this->db->update('reports', $report);
        }
    }

    public function Delete($report)
    {
        if(isset($report->id) && !is_null($report->id))
        {
            $this->db->where('id', $report->id);
            return $this->db->delete('reports');
        }
    }

    public function Create($report)
    {
        if(is_object($report))
        {
            $this->db->insert('reports', $report);
        }
    }
}
