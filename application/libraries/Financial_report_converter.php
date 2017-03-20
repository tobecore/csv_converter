<?php

class Financial_report_converter {

    function __construct() {
        
    }
    
    public function convert($fileurl, $bank, $delimiter, $currency, $currencyPattern, $skipLines, $map, $dateFormat, $payees) {
             
        print_r($payees);
        $result_string = ""; // string that will contain result csv
        $currencyMatch = ""; // temporary variable for currency
        $payeesList = Array();
        $csv_arr = $this->csvToArray($fileurl); // convert csv file to array and fix if needed
        $skipPattern = $this->prepareSkipPattern($skipLines); // make regex pattern from stop words aka skip lines
                       
        foreach ($csv_arr as $line) {
            if (!empty($currencyPattern)) {
                if (preg_match($currencyPattern, $line, $currencyMatch)) {
                    $currency = $currencyMatch[1]; //text that matched the first captured parenthesized subpattern
                    continue;
                }
            }
            if (!empty($skipPattern)) {
                if (preg_match($skipPattern, $line)) {
                    continue;//skip lines if skip pattern found
                }
            }
            $clearedcsv_arr = $this->clearCommas(str_getcsv($line, $delimiter));
            $resultTransaction_arr = $this->mapFields($clearedcsv_arr, $currency, $bank, $map, $payees, $dateFormat);
            if (!empty($resultTransaction_arr)) {
                $payeesList[] = $resultTransaction_arr['Payee'];
                $result_string .= implode(",", $resultTransaction_arr);
                $result_string .= "\n";
            }
        }
        return Array($result_string, $payeesList); 
    }
    
    protected function csvToArray($fileurl) { //convert csv file to Array and fix an extra line break
        $handle = fopen($fileurl, "r");
        $result_arr = Array();
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                
                if (substr($line, 0,1) == ",") {
                    $result_arr[count($result_arr)-1] .= $line;
                    $result_arr[count($result_arr)-1] = str_replace("\n", "", $result_arr[count($result_arr)-1]);
                    $result_arr[count($result_arr)-1] = str_replace("\r", "", $result_arr[count($result_arr)-1]);
                } else {
                    $result_arr[] = $line;
                }
            }
        } else {
            // error opening the file.
        } 
        return $result_arr;
    }
    
    
    protected function clearCommas ($originalTransaction_arr){
        
        foreach ($originalTransaction_arr as &$value) {
            $value = str_replace('"', " ", $value);
            $value = str_replace(',', '.', $value);
        }
        
        return $originalTransaction_arr;
    }
    
    protected function dateToFormat($date, $formatType = "classic") {
        $result = str_replace(Array('/','-'), ".", $date);
        $result = preg_replace('/\(\d*\.\d*\.\d*\)/', '', $result);
        
        switch ($formatType) {
            case "american":
                $result = $this->dateAmerianToClassic($result);
                break;

            default:
                break;
        }
        return $result;
    }
    
    private function dateAmerianToClassic($date) {
        $_arr = explode(".", $date);
        $result_arr = Array ($_arr[1], $_arr[0], $_arr[2]);
        $result = implode(".", $result_arr);
        return $result;
    }
    
    protected function amountPlusToFormat($amount) {
        $result = str_replace("+", "", $amount);
        return $result;
    }
    
    protected function amountMinusToFormat($amount) {
        if (substr($amount, 0, 1) != '-') {
            $result = '-' . $amount;
        } else {
            $result = $amount;
        }
        return $result;
    }
    
    protected function accountToFormat($account) {
        $result = preg_replace('/\( .* \)/', '', $account);
        return $result;
    }
    
    protected function prepareSkipPattern($skipLines) {
        if (!empty($skipLines)) {
            foreach ($skipLines as $pattern) {
                $grouped_patterns[] = "(" . $pattern . ")";
            }
            $skipPattern = implode($grouped_patterns, "|");
            $skipPattern = "/" . $skipPattern . "/";
            return $skipPattern;
        }
    }
    
    protected function mapFields($originalTransaction_arr, $currency, $bank, $map, $payees, $dateFormat = "classic") {
        $resultTransaction_arr = Array();
        if(!isset($originalTransaction_arr[1]) || !isset($originalTransaction_arr[2])) {
            return $resultTransaction_arr;
        }  
        
        $resultTransaction_arr['Bank'] = $bank;
        $resultTransaction_arr['Account'] = $this->accountToFormat($originalTransaction_arr[$map['Account']]);
        $resultTransaction_arr['Date'] = $this->dateToFormat($originalTransaction_arr[$map['Date']], $dateFormat);        
        
        if ($originalTransaction_arr[$map['Amount']['Minus']] == " " || $originalTransaction_arr[$map['Amount']['Minus']] == "") {
            $resultTransaction_arr['Amount'] = $this->amountPlusToFormat($originalTransaction_arr[$map['Amount']['Plus']]);
        } else {
            if ($map['Amount']['Minus'] == $map['Amount']['Plus']) {
                $resultTransaction_arr['Amount'] = $originalTransaction_arr[$map['Amount']['Plus']];
            } else {
                $resultTransaction_arr['Amount'] = $this->amountMinusToFormat($originalTransaction_arr[$map['Amount']['Minus']]);
            }
        }
        
        if (isset($map['Currency'])) {
            $resultTransaction_arr['Currency'] = $originalTransaction_arr[$map['Currency']];
        } else {
            $resultTransaction_arr['Currency'] = $currency;
        }
        
        $resultTransaction_arr['Description'] = $originalTransaction_arr[$map['Description']];
        
        if (isset($map['Payee'])) {
            $resultTransaction_arr['Payee'] = $originalTransaction_arr[$map['Payee']];
        } else {
            $resultTransaction_arr['Payee'] = '';
        }
        if (empty($resultTransaction_arr['Payee'])) {
            foreach ($payees as $payee) {
                $matchedPattern = '';
                if (preg_match($payee['regexp'], $resultTransaction_arr['Description'], $matchedPattern)) {
                    if (empty($payee['payee'])) {
                        $resultTransaction_arr['Payee'] = $matchedPattern[1];
                    } else {
                        $resultTransaction_arr['Payee'] = $payee['payee'];
                    }
                }
            }
        }
        
        $resultTransaction_arr['Category'] = '';
        return $resultTransaction_arr;
    }
}