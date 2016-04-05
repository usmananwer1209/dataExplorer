<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Exportdata_c extends CI_Controller {
    public function index($id="") {
            $this->load->view('exportdata/exportdata_w');
    }
}