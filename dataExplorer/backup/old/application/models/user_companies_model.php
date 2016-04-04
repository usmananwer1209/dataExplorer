<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'abstract_model.php';

class User_companies_model extends Abstract_model{

	public function __construct(){
		parent::__construct();
		$this->table = 'user_company';
        $this->ids_name = array('user', 'company');
		}

	protected function set_object($data = array()){
		foreach($data as $index => $value){
			if(strcmp($index, 'user') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'company') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'user_type') == 0)
				$this->db->set($index,  $value);
			}
	}
	public function save_member_user($id){
		$op = $this->input->post('op');
		if($op == "edit_"){
			$where = array(
					"user_type"=> 1,
					"company"=>$id);
			$this->delete_list($where);
		}
		if($this->input->post('users')){
			$users = $this->input->post('users');
			foreach($users as $user){
				$this->db->set('company', $id);
				$this->db->set('user_type', 1);
				$this->db->set('user',  $user);
				if($this->input->post('enable_load_data'.$user)){
					$this->db->set('enable_load_data', 1);
				}
				$this->db->insert($this->table);
			}
		}
	}
	public function list_users_company($user_type, $company, $enable_load_data=false, $user=false){
		$where = '';
		if($company !=''){
			$where = $where." AND c.id = '".$company."'";
		}
		if($enable_load_data){
			$where = $where." AND uc.enable_load_data = '".$enable_load_data."'";
		}
		if($user){
			$where = $where." AND uc.user = '".$user."'";
		}
		
		$result = $this->db->query('SELECT u.id, u.email, c.company_name, CONCAT(u.first_name, " ",u.last_name) as user_name, uc.enable_load_data ,uc.company FROM user as u, user_company as uc, private_company as c  WHERE c.id = uc.company AND uc.user = u.id AND uc.user_type= '.$user_type.' '.$where);
		return $result->result();
	}
	public function save_member_guest($id){
		$op = $this->input->post('op');
		if($op == "edit_"){
			$where = array(
					"user_type"=> 2,
					"company"=> $id);
			$this->delete_list($where);
		}
		if($this->input->post('guests')){
			$guests = $this->input->post('guests');
			foreach($guests as $guest){
				$this->db->set('company', $id);
				$this->db->set('user_type', 2);
				$this->db->set('user',  $guest);
				$this->db->insert($this->table);
			}
		}
	}
	public function count_list_users($user_type,$company){
		$where = array("user_type"=> $user_type, "company"=>$company);
		
		$this->db->where($where);
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
}
