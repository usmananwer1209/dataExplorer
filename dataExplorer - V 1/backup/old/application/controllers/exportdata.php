<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$folder = dirname(dirname(__FILE__));
require_once $folder . "/helpers/curl.php";
require_once "abstract_controller.php";

class Exportdata extends abstract_controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->benchmark->mark('Home:index_start');

        $current = '/home/';
        $data = $this -> security($current);
        if ($data && !empty($data)) {

            $this->benchmark->mark('Home:index load_model_start');

            $this->benchmark->mark('Home:index load_model_end');
            log_message('debug', "load home model " . $this->benchmark->elapsed_time('Home:index load_model_start', 'Home:index load_model_end'));
     
            $data[''] = '';

            $this->load->view('general/header', $data);
            $this->load->view('exportdata/page', $data);
            $this->load->view('general/footer');
        } else
            redirect('login');

        $this->benchmark->mark('Home:index_end');

        log_message('debug', "load home took " . $this->benchmark->elapsed_time('Home:index_start', 'Home:index_end'));
    }

}