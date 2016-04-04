<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'abstract_model.php';

class Kpis_model extends Abstract_model{

	public function __construct(){
		parent::__construct();
		$this->table = 'kpi';
		$this->ids_name = array('term_id');
		$this->auto_increment = false;
		}
						  
	protected function set_object($data = array()){
		foreach($data as $index => $value){
			if(strcmp($index, 'term_id') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'name') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'description') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'decision_category') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'financial_category') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'type') == 0)
				$this->db->set($index,  $value);
			}
			
		}
	public function delete_all(){
		$sql = "delete from kpi";
		$query = $this->db->query($sql);		
	}

	public function get_financial_cats(){
		$this->db->select('financial_category,count(term_id) as count')->from($this->table)->group_by("financial_category");
		return $this->db->get()->result();
		}
	public function get_decision_cats(){
		$this->db->select('decision_category,count(term_id) as count')->from($this->table)->group_by("decision_category");
		return $this->db->get()->result();
		}
	public function get_kpis_by_financial_cat($fin_cat){
		$this->db->select('term_id, name, description')->from($this->table)->where(array("financial_category"=>$fin_cat))->order_by("name", "asc");
		return $this->db->get()->result();
		}
	public function get_kpis_by_decision_cat($dec_cat){
		$this->db->select('term_id, name, description')->from($this->table)->where(array("decision_category"=>$dec_cat))->order_by("name", "asc");
		return $this->db->get()->result();
		}

	public function sync()
	{
		$this->load->model('api_model', 'api');

		$result = $this->api->get_kpi_data();

		foreach ($result as $obj) {
			$data = array();
			if(empty($obj['termID']) && !empty($obj['termId']))
				$obj['termID'] = $obj['termId'];
			$data['term_id'] = (string)$obj['termID'];
			$data['name'] = (string)$obj['name'];
			if(!empty($obj['description']))
				$data['description'] = (string)$obj['description'];
			if(!empty($obj['type']))
				$data['type'] = (string)$obj['type'];
				
			$data['decision_category'] = (string)$obj['decisionCategory'];
			$data['financial_category'] = (string)$obj['financialCategory'];
			if(empty($data['decision_category']))
				$data['decision_category'] = "uncategorized";
			if(empty($data['financial_category']))
				$data['financial_category'] = "uncategorized";

			if (!empty($data['term_id']) && $data['term_id'] != 0 && !empty($data['name']))
				$this ->save($data);
		}

	}
	public function get_data_type($term_id){
		if($term_id != ''){
			$query = "SELECT type from kpi WHERE term_id = '".$term_id."'";
			$query = $this->db->query($query);
			$result = $query->row_array();
			if(count($result)>0){
				return $result['type'];
			}else{
				return '';
			}
		}else{
			return '';
		}
	}
}
