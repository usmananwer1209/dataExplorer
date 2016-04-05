<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'abstract_model.php';

class Private_company_model extends Abstract_model{
	public function __construct(){
		parent::__construct();
		$this->table = 'private_company';
		}

	protected function set_object($data = array()){
		foreach($data as $index => $value){
			if(strcmp($index, 'company_name') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'sector') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'industry') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'sic') == 0)
					$this->db->set($index, $value);
			elseif(strcmp($index, 'state') == 0)
					$this->db->set($index, $value);
			elseif(strcmp($index, 'sic_code') == 0)
					$this->db->set($index, $value);
		}
	}
	public function state_list(){
		$this->db->select('state')->from('company')->group_by("state");
		return $this->db->get()->result();
	}
	public function sector_list(){
		$this->db->select('sector')->from('company')->group_by("sector");
		return $this->db->get()->result();
	}
	public function get_industry_by_sector(){
		$sector = $this->input->post('sector');
		$this->db->select('industry')->from('company')->where('sector',$sector)->group_by("industry");
		return $this->db->get()->result();
	}
	public function get_sic_by_industry(){
		$industry = $this->input->post('industry');
		$this->db->select('sic')->select('sic_code')->from('company')->where('industry',$industry)->group_by("sic");
		return $this->db->get()->result();
	}
	public function get_users_listing(){
		$data = $this->input->post('data');
		$user_ids = $this->input->post('user_ids');
		$guest_ids = $this->input->post('guest_ids');
		if($user_ids == ''){
			$user_ids = 0;
		}
		if($guest_ids == ''){
			$guest_ids = 0;
		}
		$result = $this->db->query('SELECT id, email, CONCAT(first_name, " ",last_name) as user_name FROM user WHERE (LOWER(first_name) LIKE "%'.strtolower($data).'%" OR LOWER(last_name) LIKE "%'.strtolower($data).'%" ) AND id NOT IN('.$user_ids.','.$guest_ids.')');
		return $result->result();
	}
	public function list_records_search($user_id = '', $like = array(), $nb = 0, $start = 0, $order_by = array()) {
		if($user_id != ''){
			$where = 'id IN (SELECT company FROM user_company WHERE user = "'.$user_id.'")';
		}
		$from = $this->db->select('id as entity_id, company_name, industry, sector, sic, sic_code, company_name as stock_symbol')->from($this->table);
		if(!empty($where))
			$from->where($where);
		if(!empty($like))
			$from->or_like($like);
		if($nb != 0)
			$this->db->limit($nb, $start);
		if(!empty($order_by[0]) && !empty($order_by[1]))
			$this->db->order_by($order_by[0], $order_by[1]);
		
		return $this->db->get()->result();
	}
	public function get_by_id_search($id){
		$query = $this->db->select('id as entity_id, company_name, industry, state, sector, sic, sic_code, company_name as stock_symbol, sic as cik')->from($this->table)->where('id',$id);
		
		if($result = $query->get()->result_array())
			return (object)$result[0];
		else 
			return null;
	}
	public function update_company($csv){
		foreach($csv as $c){
			$this->db->set('revenues', str_replace('.00','', $c['revenues']));
			$this->db->set('revenues_tier', $c['revenuesTier']);
			$this->db->set('total_assets', str_replace('.00','', $c['totalAssets']));
			$this->db->set('total_assets_tier', $c['totalAssetsTier']);
			$this->db->where('entity_id',$c['entityId']);
			$this->db->update('company');
		}
	}
	public function save($data){
		if(!empty($data)){
			if($this->has_id($data))
				$obj = $this->get_by_object($data);	
			if(!empty($obj))
				return $this->edit($data);		
			else
				return  $this->add($data);
		}
	}
	public function add($data){
		$id = $this->get_new_id();
		$this->db->set('id', $id);
		$this->set_object((array)$data);
		$this->db->insert($this->table);
		return (object)$this->get_by_id($id);
	}
	public function get_new_id(){
		$from = $this->db->select('id')->from($this->table);
		$this->db->limit(1);
		$this->db->order_by('id', 'DESC');
		$row  = $this->db->get()->row();
		$new_id = (int)str_replace(PRIVATE_COMPANY_INDICATOR,'',$row->id)+1;
		return PRIVATE_COMPANY_INDICATOR.$new_id;
	}
}