<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banks extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
	}

	public function _example_output($output = null)
	{
		$this->load->view('example.php',(array)$output);
	}
        
	public function banks_management()
	{
			$crud = new grocery_CRUD();

                        $crud->set_language("ukrainian");
			$crud->set_theme('datatables');
			$crud->set_table('banks');
			$crud->set_subject('Банк');
                        $crud->display_as('name','Назва банку');
                        
			$output = $crud->render();

			$this->_example_output($output);
	}
        
        public function maps_management()
	{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('maps');
			$crud->set_relation('bank_id','banks','name');
			$crud->display_as('bank_id','Bank');
			$crud->set_subject('Map');

			//$crud->required_fields('lastName');

			//$crud->set_field_upload('file_url','assets/uploads/files');

			$output = $crud->render();

			$this->_example_output($output);
	}
        
        public function skip_lines()
	{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('skip_lines');
			$crud->set_relation('bank_id','banks','name');
			$crud->display_as('bank_id','Bank');
			$crud->set_subject('Skip Line');

			//$crud->required_fields('lastName');

			//$crud->set_field_upload('file_url','assets/uploads/files');

			$output = $crud->render();

			$this->_example_output($output);
	}
        
        public function payee_rules()
	{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('payee_rules');
			$crud->set_relation('bank_id','banks','name');
			$crud->display_as('bank_id','Bank');
			$crud->set_subject('Payee rule');

			$output = $crud->render();

			$this->_example_output($output);
        }
        
        public function categories()
	{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('categories');
			$crud->set_relation('cash_flow_type_id','cash_flow_types','Name');
			$crud->display_as('cash_flow_type_id','Cash flow type');
			$crud->set_subject('Category');

			$output = $crud->render();

			$this->_example_output($output);
	}
        
        public function payees_categories()
	{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('payees_categories');
			$crud->set_relation('cash_flow_type_id','cash_flow_types','Name');
			$crud->display_as('cash_flow_type_id','Cash flow type');
			$crud->set_relation('category_id','categories','Name');
			$crud->display_as('category_id','Category');
			$crud->set_relation('payee_id','payees','Name');
			$crud->display_as('payee_id','Payee');
			$crud->unset_add();

			$output = $crud->render();

			$this->_example_output($output);
	}

}