<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Termajaxdata_c extends CI_Controller {
    public function index($id="") {
            $this->load->view('term/termajaxdata_w');
    }
}