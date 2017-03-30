<?php

class Payee_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }
    
    public function insertUniquePayees($payees) {
        foreach ($payees as $payee) {
            $payees_arr[] = $payee["payeeName"];
        }
        $existingPayees = $this->getPayeesList();
        $payees_arr = array_unique($payees_arr);
        $absentElements = array_diff($payees_arr,$existingPayees);
        if (!empty($absentElements)) {
            foreach ($absentElements as &$element) {
                $element = '("'.$element.'")';
            }
            $newPayees_str = implode(",", $absentElements);
            $query = "INSERT INTO payees (Name) VALUES $newPayees_str;";
            $this->db->query($query);
        }
    }

    public function getPayeesList()
    {
        $existingPayees = $this->db->get('payees');
        $existingPayees = $existingPayees->result_array();
        foreach ($existingPayees as $payee) {
            $result[] = $payee["Name"];
        }
        $result[] = '';
        return $result;
    }
}