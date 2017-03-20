<?php

class Upload extends CI_Controller {
    
    private $fileValidation = Array(
        'upload_path' => './uploads/',
        'allowed_types' => 'csv',
        'max_size' => 10000
    );

    public function __construct()
    {
            parent::__construct();
            $this->load->helper(array('form', 'url', 'download'));
            $this->load->model('bank_model');
            $this->load->model('skip_lines_model');
            $this->load->model('map_model');
            $this->load->model('payee_model');
            $this->load->model('payee_rules_model');
            $this->load->library('financial_report_converter');
    }

    public function index()
    {
        $data['banksList'] = $this->bank_model->getBanksList1();
        $this->load->view('upload_form', $data);
    }

    public function do_upload()
    {
        $result = "Bank, Account, Date, Amount, Currency, Description \n";
        $this->load->library('upload', $this->fileValidation);

        $banksList = $this->bank_model->getBanksList();
        foreach ($banksList as $bankId => $bankName) {
            if ($this->upload->do_upload($bankId))
            {
                $uploadData = array('upload_data' => $this->upload->data());
                $soucefile = $uploadData['upload_data']['file_name'];
                $fileurl = base_url().'uploads/'.$soucefile;
                $bankInfo = $this->bank_model->getBankInfo($bankId);
                $result .= $this->financial_report_converter->convert(
                        $fileurl, 
                        $bankName, 
                        $bankInfo['delimiter'], 
                        $bankInfo['defaultCurrency'], 
                        $bankInfo['currencyPattern'], 
                        $bankInfo['skipLines'],
                        $bankInfo['map'],
                        $bankInfo['dateFormat']);
            }
        }
        force_download("result.csv", $result);
    }
    public function do_upload1()
    {
        $result = "Bank, Account, Date, Amount, Currency, Description, Payee, Category \n";
        $this->load->library('upload', $this->fileValidation);

        $banksList = $this->bank_model->getBanksList1();
        foreach ($banksList as $bank) {
            if ($this->upload->do_upload($bank['id']))
            {
                $uploadData = array('upload_data' => $this->upload->data());
                $soucefile = $uploadData['upload_data']['file_name'];
                $fileurl = base_url().'uploads/'.$soucefile;
                $skipLines = $this->skip_lines_model->getBankSkipLinesList($bank['id']);
                $map = $this->map_model->getBankMap($bank['id']);
                $payees = $this->payee_rules_model->getBankPayeesList($bank['id']);
                $results_arr = $this->financial_report_converter->convert(
                        $fileurl, 
                        $bank['name'], 
                        $bank['delimiter'], 
                        $bank['default_currency'], 
                        $bank['currency_pattern'], 
                        $skipLines,
                        $map,
                        $bank['date_format'],
                        $payees
                        );
                $result .= $results_arr[0];
                $this->payee_model->createNewPayees($results_arr[1]);
            }
        }
        force_download("result.csv", $result);
    }
}
?>