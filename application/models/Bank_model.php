<?php

class Bank_model extends CI_Model {

    private $banksList = Array(
        'MTB' => Array (
            'name' => 'Meridian Trade Bank',
            'defaultCurrency' => 'USD',
            'delimiter' => ',',
            'currencyPattern' => '/Начальный остаток: .*\d+\.\d{2} (\w{3})/',
            'skipLines' => Array (
                'ОТЧЁТ ПО СЧЁТУ:'
            ),
            'map' => Array (
                'Account' => 1,
                'Date' => 0,
                'Amount' => Array (
                    'Plus' => 4, 
                    'Minus' => 3
                    ),
                'Description' => 2
            )
        ),
        'WF' => Array (
            'name' => 'Wells Fargo',
            'defaultCurrency' => 'USD',
            'delimiter' => ',',
            'currencyPattern' => '', 
            'dateFormat' => 'american',
            'skipLines' => Array (),
            'map' => Array (
                'Account' => 3,
                'Date' => 0,
                'Amount' => Array (
                    'Plus' => 1, 
                    'Minus' => 1
                    ),
                'Description' => 4
            )
        ),
        'BBB' => Array (
            'name' => 'Ballin Business Bank',
            'defaultCurrency' => 'EUR',
            'delimiter' => ';',
            'currencyPattern' => '', 
            'skipLines' => Array (
                'J..k perioodi alguses;;;;;',
                'Deebetk.ive;;;;;',
                'Kreeditk.ive;;;;;',
                'J..k perioodi l.pus;;;;;'
            ),
            'map' => Array (
                'Account' => 0,
                'Date' => 1,
                'Amount' => Array (
                    'Plus' => 2, 
                    'Minus' => 2
                    ),
                'Description' => 6,
                'Currency' => 3 // if currency is located in each line - you can specify the number of line here
            )
        ),
        'BOC' => Array (
            'name' => 'Bank Of Cyprus', // name of the Bank. Uses in result scv 
            'defaultCurrency' => 'USD', // if currency is not specified
            'delimiter' => ',', //
            'currencyPattern' => '/^Валюта счета:,(\w{3})/', //currency must be grouped by "()" signs
            'dateFormat' => 'classic', // classic (dd/mm/yyyy) or american (mm/dd/yyyy)
            'skipLines' => Array ( // Lines, the programm should just skip
                '^Период:',
                '^(Номер счета:,)\d+,',
                '^(Название счета:)',
                '^(Тип счета:)',
                '^(Банковский  Референс Номер,)'
            ),
            'map' => Array ( // numbers of columns in original csv file
                'Account' => 0,
                'Date' => 1,
                'Amount' => Array (
                    'Plus' => 5, 
                    'Minus' => 6
                    ),
                'Description' => 3
            )
        )
    );

    public function __construct()
    {
            $this->load->database();
    }

    public function getBanksList() {
        $result = Array();
        foreach ($this->banksList as $bankID => $bankProperties) {
            $result[$bankID] = $bankProperties['name'];
        }
        return $result;
    }
    
    public function getBanksList1() {
        $query = $this->db->get('banks');
        return $query->result_array();
    }

    public function getBankInfo($id) {
        return $this->banksList[$id];
    }
    
    public function getBankFullInfo($id) {
        $query = $this->db->get('banks', array('id' => $id), 1);
        $result = $query->result_array();
        return $result[0];
    }
}