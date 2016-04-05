<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$folder = dirname(dirname(__FILE__));
require_once $folder . "/helpers/curl.php";
require_once "abstract_controller.php";

class Exportdata extends abstract_controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('dataexport_model', 'dataexport');

    }

    public function index() {

        //$this->benchmark->mark('Home:index_start');
        $current = '/exportdata/';
        $data = $this -> security($current);
		
	    if ($data && !empty($data)) {
             //$this->benchmark->mark('Home:index load_model_start');
			//$this->benchmark->mark('Home:index load_model_end');
            //log_message('debug', "load home model " . $this->benchmark->elapsed_time('Home:index load_model_start', 'Home:index load_model_end'));
     		
			//Getting Companies and KPIS
            $list_companies = $this->dataexport->get_companies();
            $data['getcompanieslist'] = $list_companies;
            $list_kpis = $this->dataexport->get_kpis();
            $data['list_kpis'] = $list_kpis;
			
			$getcompanieslist = $this->db->order_by("name")->get('list_companies')->result();
			
			$user = $this->session->userdata('user');
			$user_is_root=$user->is_root;
			$data['user_is_root']=$user_is_root;
			
            $this->load->view('general/header', $data);
            $this->load->view('exportdata/page', $data);
            $this->load->view('general/footer',$data);
        } else
            redirect('login');

        //$this->benchmark->mark('Home:index_end');

        //log_message('debug', "load home took " . $this->benchmark->elapsed_time('Home:index_start', 'Home:index_end'));
    }

}