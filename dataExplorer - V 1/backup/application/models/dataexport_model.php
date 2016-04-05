<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dataexport_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		
		}


	public function get_companies(){
		
 		$companies = array();
		$user = $this->session->userdata('user');
        $userid = $user->id;
		$this->db->where('user', $userid);
		$this->db->or_where('public', 1);
		$this->db->order_by('name ASC');
		$query = $this->db->get('list_companies');
		if ($query->num_rows() > 0){
			foreach ($query->result() as $row)
   			{
				$companies[]=($row);
			}
		}
 		return $companies;
	}

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