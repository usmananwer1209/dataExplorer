<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'abstract_model.php';
class Common_model extends Abstract_model {

    public function __construct() {
        parent::__construct();
    }
     protected function set_object($data = array()) {}
     
    public function getDetail($table, $where=""){
        if($where !=""){
            $this->db->where($where);
        }
        return $this->db->get($table)->result();
        
    }
    
      public function getSingleDetail($table, $where=""){
        if($where !=""){
            $this->db->where($where);
        }
        $this->db->limit(1);
        return $this->db->get($table)->result();
        
    }
    
    public function get_industry_list($table, $value){
       return $this->db->like('industry', $value)->group_by("sic")->order_by("sic", "asc")->get($table)->result();
    }
    public function get_sector_list($table, $value, $group_by, $order_by)
    {
        return $this->db->like('sector', $value)->group_by($group_by)->order_by($order_by, "asc")->get($table)->result();
    }
}
