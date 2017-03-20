<?php

class Payee_rules_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }
    
    public function getBankPayeesList($id) {
        $query = $this->db->get_where('payee_rules', array('bank_id' => $id));
        $result = Array();
        foreach ($query->result_array() as $line) {
            $result[] = Array(
                'regexp' => $line['regexp'],
                'payee' => $line['payee'],
                    );
        }
        return $result;
    }
}

