<?php

class Payee_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }
    
    public function createNewPayees($payees) {
        print_r($payees);
        foreach ($payees as $payee_name) {
            if (!empty($payee_name)) {
                //$query = "REPLACE INTO payees SET Name = '$payee_name';";
                //$this->db->query($query);
            }
        }
        
    }
}