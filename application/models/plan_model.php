<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class plan_model extends CI_Model {
	function Plans() {
		$q = $this->db->get('plans');
		return $q->result();
	}
	
	 function insert_plan($plan)
    {
        $insertStatus = $this->db->insert('plans', $plan);
		return $insertStatus;
    }

    public function Update($plan)
    {
        if(is_object($plan)){
            $this->db->where('id', $plan->id);
            return $this->db->update('plans', $plan);
        }
    }

    public function Delete($plan)
    {
        if(isset($plan->id) && !is_null($plan->id))
        {
            $this->db->where('id', $plan->id);
            return $this->db->delete('plans');
        }
    }

    public function Create($plan)
    {
        if(is_object($plan))
        {
            $this->db->insert('plans', $plan);
        }
    }
}
