<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'abstract_model.php';

class List_kpis_model extends Abstract_model{
	public function __construct(){
		parent::__construct();
		$this->table = 'list_kpis';
		}
	protected function set_object($data = array()){
		foreach($data as $index => $value){
			if(strcmp($index, 'id') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'name') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'kpis') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'user') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'public') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'creation_time') == 0){
				if(strcmp($value, 'now') == 0)
					$this->db->set($index, 'NOW()', false);
				else
					$this->db->set($index, $value);
				}
			elseif(strcmp($index, 'modification_time') == 0){
				if(strcmp($value, 'now') == 0)
					$this->db->set($index, 'NOW()', false);
				else
					$this->db->set($index, $value);
			}

		}
	}
	public function get_list_kpis($user_id){
		$where = "(is_template = 0 AND (user = ".$user_id." OR public = 1)) OR (is_template = 1 AND (company IS NULL OR company IN (SELECT company FROM user_company WHERE user = ".$user_id.")))";
		
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
        	public function get_kpis(){
		
 		$kpis = array();
 		$user = $this->session->userdata('user');
                $userid = $user->id;
		$this->db->where('user', $userid);
		$this->db->or_where('public', 1);
		$this->db->order_by('name ASC');
		$query = $this->db->get('list_kpis');
		if ($query->num_rows() > 0){
			foreach ($query->result() as $row)
   			{
				$kpis[]=($row);
			}
		}
		return $kpis;
	}
}