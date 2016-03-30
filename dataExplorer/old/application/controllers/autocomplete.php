<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Autocomplete extends CI_Controller {

	public function __construct(){
    	parent::__construct();
		$this->load->model('users_model', 'users');
		$this->load->model('circles_model', 'circles');
		$this->load->model('user_circles_model', 'user_circles');
		$this->header_data = array();
		$this->page_data = array();
        }

	public function companies(){
		$data = $this->input->post('data');
	  	@$this->load->model('companies_model','companies');
		@$this->load->model('private_company_model', 'private_company');
		$like = array(
					"LOWER(company_name)"=>strtolower($data),
                                        "LOWER(stock_symbol)"=>strtolower($data)
				);
		$objs = @$this->companies->list_records(null,$like,25);
		$like = array(
					"LOWER(company_name)"=>strtolower($data)
				);
		$objs1 = $this->private_company->list_records_search($this->user_id(),$like,25);
		$combine_array = array_merge($objs,$objs1);
		print json_encode($combine_array);
	}

	public function kpis(){
		$data = $this->input->post('data');
	  	@$this->load->model('kpis_model','kpis');
		$like = array(
					"LOWER(name)"=>strtolower($data)
				);
		$objs = @$this->kpis->list_records(null,$like,50);
		print json_encode($objs);
	}
	public function user_id(){
		$user = $this->session->userdata('user');

		if (!empty($user))
			return $user->id;
	}
}

