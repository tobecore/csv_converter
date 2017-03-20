<?php

class Skip_lines_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }
    
    public function getBankSkipLinesList($id) {
        $query = $this->db->get_where('skip_lines', array('bank_id' => $id));
        $result = Array();
        foreach ($query->result_array() as $line) {
            $result[] = $line['skip_line'];
        }
        return $result;
    }
}

