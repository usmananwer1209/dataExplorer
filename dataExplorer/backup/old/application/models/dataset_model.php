<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'abstract_model.php';

class Dataset_model extends Abstract_model{
	public function __construct(){
		parent::__construct();
		$this->table = 'dataset';
		}

	protected function set_object($data = array()){
		foreach($data as $index => $value){
			if(strcmp($index, 'title') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'sources') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'description') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'user') == 0)
					$this->db->set($index, $value);
			elseif(strcmp($index, 'creation_time') == 0)
					$this->db->set($index, $value);
			elseif(strcmp($index, 'template') == 0)
					$this->db->set($index, $value);
			elseif(strcmp($index, 'company') == 0)
					$this->db->set($index, $value);
		}
	}
	public function get_years_list(){
		return $this->db->get('years_list')->result();
	}
	public function get_term_list($ids){
		$ids = str_replace('"','',$ids);
		$ids = $ids.'0';
		$result = $this->db->query('SELECT term_id, name, description FROM kpi WHERE term_id IN('.$ids.')');
		return $result->result();
	}
	public function save_years_record($dataset){
		$this->delete_dataset_years_list($dataset);
		$yesrs 		= $this->input->post('year');
		$template 	= $this->input->post('template');
		$temp 		= $this->template->get_by_id($template);
		$kpis		= $this->get_term_list($temp->kpis);
		
		foreach($yesrs as $year){
			foreach($kpis as $kpi){
				$Q1 = $this->input->post('Q1'.$year.'_'.$kpi->term_id);
				$Q2 = $this->input->post('Q2'.$year.'_'.$kpi->term_id);
				$Q3 = $this->input->post('Q3'.$year.'_'.$kpi->term_id);
				$Q4 = $this->input->post('Q4'.$year.'_'.$kpi->term_id);
				$FY = $this->input->post('FY'.$year.'_'.$kpi->term_id);
				$k = $kpi->term_id;
				
				if($Q1 != ''){
					$this->db->set('q1', $Q1);
				}
				if($Q2 != ''){
					$this->db->set('q2', $Q2);
				}
				if($Q3 != ''){
					$this->db->set('q3', $Q3);
				}
				if($Q4 != ''){
					$this->db->set('q4', $Q4);
				}
				if($FY != ''){
					$this->db->set('fy', $FY);
				}
				$this->db->set('kpi', $k);
				$this->db->set('year', $year);
				$this->db->set('dataset', $dataset);
				$this->db->insert('dataset_years');
			}
		}	
	}
	public function delete_dataset_years_list($dataset){
		return $this->db->where('dataset',$dataset)->delete('dataset_years');
	}
	public function get_dataset_years_record($dataset){
		$this->db->select('*')->from('dataset_years');
		$this->db->where('dataset',$dataset);
		return $this->db->get()->result();
	}
	public function get_kpi_detail($id){
		$from = $this->db->select('name, description')->from('kpi');
		$from->where('term_id' ,$id);
		return $this->db->get()->row();
	}
}