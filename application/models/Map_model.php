<?php

class Map_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }
    
    public function getBankMap($id) {
        $query = $this->db->get_where('maps', array('bank_id' => $id));       
        $mapFromTable = $query->result_array();

        $map = Array (
                'Account' => $mapFromTable[0]['account'],
                'Date' => $mapFromTable[0]['date'],
                'Amount' => Array (
                    'Plus' => $mapFromTable[0]['amount_plus'], 
                    'Minus' => $mapFromTable[0]['amount_minus'],
                    ),
                'Description' => $mapFromTable[0]['description'],
                'Currency' => $mapFromTable[0]['currency'],
                'Payee' => $mapFromTable[0]['payee']
            );
        
        return $map;
    }
}