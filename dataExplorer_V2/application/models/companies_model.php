<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'abstract_model.php';

class Companies_model extends Abstract_model {

    public function __construct() {
        parent::__construct();
        $this->table = 'company';
        $this->ids_name = array('entity_id');
        $this->auto_increment = false;
    }

    protected function set_object($data = array()) {
        foreach ($data as $index => $value) {
            if (strcmp($index, 'entity_id') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'cik') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'company_name') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'industry') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'sector') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'sic') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'sic_code') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'state') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'stock_symbol') == 0)
                $this->db->set($index, $value);
        }
    }

    public function delete_all() {
        $sql = "delete from company";
        $query = $this->db->query($sql);
    }

    public function get_sector_by_name() {
        $sector_name = $this->uri->segment(3);
        $sector_name = str_replace('%20', ' ', $sector_name);
        $this->db->where('sector', $sector_name);
        $this->db->select('sector')->from($this->table)->group_by("sector");
        return $this->db->get()->result();
    }

    public function get_sector() {
        $this->db->select('sector')->from($this->table)->group_by("sector");
        return $this->db->get()->result();
    }

    public function get_sector_by_company_name() {
        $company_name = $this->uri->segment(3);
        $company_name = urldecode($company_name);
        $this->db->like('company_name', $company_name, 'both');
        $this->db->or_like('stock_symbol', $company_name, 'both');
        $this->db->select('entity_id, company_name, industry, sector, sic')->from($this->table)->group_by("sector")->order_by("company_name", "asc")->limit(1);
        $result = $this->db->get()->result();
        if (count($result) < 1) {
            $this->db->like('company_name', $company_name, 'both');
            $this->db->select('id as entity_id, company_name, industry, sector, sic')->from('private_company')->group_by("sector")->order_by("company_name", "asc")->limit(1);
            $result = $this->db->get()->result();
        }
        return $result;
    }

    public function get_sic($industry) {
        $this->db->select('sic')->from($this->table)->where(array("industry" => $industry))->group_by("sic");
        return $this->db->get()->result();
    }

    public function get_companies_from_sic($sic) {
        //$this->db->select('company_name, count(entity_id) as count')->from($this->table)->where(array("sic" => $sic));
        $this->db->select('company_name, (select 1 from dual) as count')->from($this->table)->where(array("sic" => $sic));
        return $this->db->get()->result();
    }

    public function get_industry_and_count($sector) {
        $i = 1;
        $where = '';
        if (isset($_GET['revenue_limit' . $i])) {
            while (isset($_GET['revenue_limit' . $i])) {
                $revenue_limit = $_GET['revenue_limit' . $i];
                if ($i == 1) {
                    $revenues = "'" . $revenue_limit . "'";
                } else {
                    $revenues = $revenues . ", '" . $revenue_limit . "'";
                }
                $i++;
            }
            $where = 'revenues_tier IN (' . $revenues . ')';
            $result = $this->db->query('SELECT c.industry,(SELECT count(p.id) FROM private_company as p WHERE p.sector = "' . $sector . '" AND p.industry=c.industry AND p.id IN (SELECT company FROM user_company WHERE user = "' . $this->user_id() . '"))+count(c.entity_id) as count FROM company as c  WHERE c.sector = "' . $sector . '" AND ' . $where . ' GROUP BY c.sector, c.industry')->result();
        } else {
            $result = $this->db->query('SELECT c.industry,(SELECT count(p.id) FROM private_company as p WHERE p.sector = "' . $sector . '" AND p.industry=c.industry AND p.id IN (SELECT company FROM user_company WHERE user = "' . $this->user_id() . '"))+count(c.entity_id) as count FROM company as c  WHERE c.sector = "' . $sector . '" GROUP BY c.sector, c.industry')->result();
        }
        return $result;
    }

    public function get_sics_and_count($industry) {
        return $this->db->query('SELECT sic_code, sic, count(\'SELECT p.id FROM private_company as p WHERE p.industry = "' . $industry . '"\') + count(*) as count FROM company  WHERE industry = "' . $industry . '" GROUP BY industry, sic')->result();
    }

    public function get_all_sics_and_count() {
        $this->db->select('sic_code, sic,count(entity_id) as count')->from($this->table)->group_by("industry")->group_by("sic");
        return $this->db->get()->result();
    }

    public function get_companies($industry) {
        $this->db->select('company_name')->from($this->table)->where(array("industry" => $industry));
        $result = $this->db->get()->result();
        $this->db->select('company_name')->from('private_company')->where(array("industry" => $industry));
        $result1 = $this->db->get()->result();
        return array_merge($result, $result1);
    }

    public function get_companies_by_sic_code($sic_code) {
        $i = 1;
        $where = '';
        if (isset($_GET['revenue_limit' . $i])) {
            while (isset($_GET['revenue_limit' . $i])) {
                $revenue_limit = $_GET['revenue_limit' . $i];
                if ($i == 1) {
                    $revenues = "'" . $revenue_limit . "'";
                } else {
                    $revenues = $revenues . ", '" . $revenue_limit . "'";
                }
                $i++;
            }
            $where = 'revenues_tier IN (' . $revenues . ')';
            $result = $this->db->query('SELECT entity_id, company_name FROM company  WHERE sic_code = "' . $sic_code . '" AND ' . $where . ' ORDER BY company_name ASC')->result();
        } else {
            $result = $this->db->query('SELECT entity_id, company_name FROM company  WHERE sic_code = "' . $sic_code . '" ORDER BY company_name ASC')->result();
        }
        $result1 = $this->db->query('SELECT p.id as entity_id, p.company_name FROM private_company as p WHERE sic_code = "' . $sic_code . '" AND p.id IN (SELECT company FROM user_company WHERE user = "' . $this->user_id() . '") ORDER BY company_name ASC')->result();

        return array_merge($result, $result1);
    }

    public function get_companies_by_ids($ids) {
        $where = '';
        if (!empty($ids) && is_array($ids)) {
            foreach ($ids as $id) {
                if ($where == '')
                    $where .= "`entity_id` = '" . $id . "'";
                else
                    $where .= " OR `entity_id` = '" . $id . "'";
            }
            $this->db->select('*')->from($this->table)->where($where);
            $result = $this->db->get()->result();
            $where = '';
            foreach ($ids as $id) {
                if ($where == '')
                    $where .= "`id` = '" . $id . "'";
                else
                    $where .= " OR `id` = '" . $id . "'";
            }
            $this->db->select('id as entity_id, company_name, industry, sector, sic, sic_code, company_name as stock_symbol')->from('private_company')->where($where);
            $result1 = $this->db->get()->result();
            return array_merge($result, $result1);
        } else
            return false;
    }

    public function user_id() {
        $user = $this->session->userdata('user');

        if (!empty($user))
            return $user->id;
    }

    public function sync() {
        $this->load->model('api_model', 'api');

        $result = $this->api->get_entity_data();

        foreach ($result as $obj) {
            $data = array();
            if (empty($obj['entityId']) && !empty($obj['entityID']))
                $obj['entityId'] = $obj['entityID'];
            $data['entity_id'] = $obj['entityId'];
            $data['cik'] = $obj['cik'];
            $data['company_name'] = $obj['companyName'];
            $data['industry'] = $obj['industry'];
            $data['sector'] = $obj['sector'];
            $data['sic'] = $obj['sic'];
            $data['sic_code'] = $obj['sicCode'];
            if (!empty($obj['state']))
                $data['state'] = $obj['state'];
            $data['stock_symbol'] = $obj['stockSymbol'];
            if (!empty($data['entity_id']) && $data['entity_id'] != 0 && !empty($data['company_name']))
                $this->save($data);
        }
    }

    /*
     * 
     * 
     */

    function get_company_by_entity_id($entity_id) {

        return $this->db->where('entity_id', $entity_id)->get('company')->result();
    }

    public function get_filtered_companies($post_data, $order_group, $Id, $name, $idValue,$SelectedList) {
       
        if (@$post_data['chkdvalue'] <> "") {
            $chkdvalue = $post_data['chkdvalue'];
            $this->db->where_in('revenues_tier', $chkdvalue);
        }

        if (@$post_data['sic'] == '' and @ $post_data['filter']) {
            $filter = @$post_data['filter'];
            if ($filter[0] != "" & $filter[1] != "" & $filter[2] != "") {
                //If sector, inductry and sic are not null
                $this->db->like('sector', $filter[0]);
                $this->db->like('industry', $filter[1]);
                $this->db->like('sic', $filter[2]);
            } elseif ($filter[0] == "" & $filter[1] == "" & $filter[2] != "") {
                //if only sic is not null
                $this->db->like('sic', $filter[2]);
            } elseif ($filter[0] == "" & $filter[1] != "" & $filter[2] != "") {
                //if sector null and industry and sic not null
                $this->db->like('industry', $filter[1]);
                $this->db->like('sic', $filter[2]);
            } elseif ($filter[0] == "" & $filter[1] != "" & $filter[2] == "") {
                //If sector & sic null and industry not null
                $this->db->like('industry', $filter[1]);
            } elseif ($filter[0] != "" & $filter[1] != "" & $filter[2] == "") {
                //if sector and industry not null and sic is null
                $this->db->like('sector', $filter[0]);
                $this->db->like('industry', $filter[1]);
            } elseif ($filter[0] != "" & $filter[1] == "" & $filter[2] != "") {
                //if industry null and sector and sic not null
                $this->db->like('sector', $filter[0]);
                $this->db->like('sic', $filter[2]);
            } elseif ($filter[0] != "" & $filter[1] == "" & $filter[2] == "") {
                //if sector not null and industry and sic null
                $this->db->like('sector', $filter[0]);
            } elseif ($filter[0] == "" & $filter[1] == "" & $filter[2] == "") {
                //if all are null
                echo " ";
                exit;
            }
            //$this->db->like('sic',$filter[2]);
        } elseif (@$post_data['sic'] and empty($filter)) {
            $this->db->like('sic', @$post_data['sic']);
        } elseif (@$post_data['sic'] and ! empty($filter)) {
            $filter = $post_data['filter'];
            $this->db->like('sector', $filter[0]);
            $this->db->like('industry', $filter[1]);
        }
    
        return $this->db->group_by($order_group)->order_by($order_group, "asc")->get('company')->result();
    }
    
    public function get_inner_list($order_group, $chkdvalue, $outer_order_group, $name){
         if (isset($chkdvalue) && $chkdvalue <> "") {
                $this->db->where_in('revenues_tier', $chkdvalue);
            }
            
          return  $get_inner = $this->db->like($order_group, $outer_order_group)->order_by($name, "asc")->get('company')->result();
        
    }

}
