<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'abstract_model.php';

class List_companies_model extends Abstract_model {

    public function __construct() {
        parent::__construct();
        $this->table = 'list_companies';
    }

    protected function set_object($data = array()) {
        foreach ($data as $index => $value) {
            if (strcmp($index, 'id') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'name') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'companies') == 0)
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

    /* Method : Get Companies 
     * 
     * 
     */

    public function get_companies() {

        $companies = array();
        $user = $this->session->userdata('user');
        $userid = $user->id;
        $this->db->where('user', $userid);
        $this->db->or_where('public', 1);
        $this->db->order_by('name ASC');
        $query = $this->db->get('list_companies');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $companies[] = ($row);
            }
        }
        return $companies;
    }

    public function is_name_exist($ListName) {
        return $this->db->where('name', $ListName)->get('list_companies')->result();
    }

    public function update_list_company($values, $ListName) {
        $this->db->set('companies', $values);
        $this->db->where('name', $ListName);

        $update_tbl = $this->db->update('list_companies');
        if ($update_tbl)
            return true;
        else
            return false;
    }

    public function insert_company_list($ins_array) {
        $ins_tbl = $this->db->insert('list_companies', $ins_array);
        if ($ins_tbl)
            return 1;
        else
            return 0;
    }

    /* Method : Get Sectors 
     * 
     * 
     */

    function getCompanySectors($group_by = "") {

        if ($group_by != "") {
            return $get_sector = $this->db->group_by($group_by)->get('company')->result();
        } else {
            return $get_sector = $this->db->get('company')->result();
        }
    }

    public function get_companies_list() {

        $user = $this->session->userdata('user');
       $userid = $user->id;
        $this->db->where('user', $userid);
        $this->db->or_where('public', 1);
        $this->db->order_by('name ASC');
        $query = $this->db->get('list_companies');

        return $query->result();
    }

    function get_companies_with_order($order_by = "") {
        if ($order_by != "") {
            return $this->db->order_by("name")->get('list_companies')->result();
        } else {
            return $this->db->get('list_companies')->result();
        }
    }

}
