<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'abstract_model.php';

class List_kpis_model extends Abstract_model {

    public function __construct() {
        parent::__construct();
        $this->table = 'list_kpis';
    }

    protected function set_object($data = array()) {
        foreach ($data as $index => $value) {
            if (strcmp($index, 'id') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'name') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'kpis') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'user') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'public') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'creation_time') == 0) {
                if (strcmp($value, 'now') == 0)
                    $this->db->set($index, 'NOW()', false);
                else
                    $this->db->set($index, $value);
            }
            elseif (strcmp($index, 'modification_time') == 0) {
                if (strcmp($value, 'now') == 0)
                    $this->db->set($index, 'NOW()', false);
                else
                    $this->db->set($index, $value);
            }
        }
    }

    public function get_list_kpis($user_id) {
        $where = "(is_template = 0 AND (user = " . $user_id . " OR public = 1)) OR (is_template = 1 AND (company IS NULL OR company IN (SELECT company FROM user_company WHERE user = " . $user_id . ")))";

        $from = $this->db->select('*')->from($this->table);
        $from->where($where);
        $this->db->order_by('name', 'DESC');
        return $this->db->get()->result();
    }

    /* Method : Get KPI s
     * 
     * 
     * 
     */

    public function get_kpis() {

        $kpis = array();
        $user = $this->session->userdata('user');
        $userid = $user->id;
        $this->db->where('user', $userid);
        $this->db->or_where('public', 1);
        $this->db->order_by('name ASC');
        $query = $this->db->get('list_kpis');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $kpis[] = ($row);
            }
        }
        return $kpis;
    }

     public function is_name_exist($ListName) {
        
       return $this->db->where('name', $ListName)->get('list_kpis')->result();
    }
    
    public function update_kpis_list($values, $ListName) {
        $this->db->set('kpis', $values);
        $this->db->where('name', $ListName);

        $update_tbl = $this->db->update('list_kpis');
        if ($update_tbl)
            return true;
        else
            return false;
    }
    
     public function insert_kpis_list($ins_array) {
        $ins_tbl = $this->db->insert('list_kpis', $ins_array);
        if ($ins_tbl)
            return 1;
        else
            return 0;
    }
    
    public function get_kpis_list() {

        $user = $this->session->userdata('user');
        $userid = $user->id;
        $this->db->where('user', $userid);
        $this->db->or_where('public', 1);
        $this->db->order_by('name ASC');
        $query = $this->db->get('list_kpis');
        
        return $query->result(); 
    }

    /* Method : Get Kpi by term Id
     * 
     * 
     */

    function get_kpi_by_term_id($val) {

        return $this->db->where('term_id', $val)->get('kpi')->result();
    }

}
