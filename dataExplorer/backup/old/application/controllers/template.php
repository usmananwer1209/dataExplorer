<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once "abstract_controller.php";

class Template extends abstract_controller {

	public function __construct(){
    	parent::__construct();
        	$this->load->model('template_model', 'template');
			$this->load->model('private_company_model', 'private_company');
        }

	public function index($message = "", $is_error = false){
		$user = $this->session->userdata('user');
		if(!empty($user)){
			$this->all();
			}
		else
			redirect('login');
	}
    public function delete($template_id) {
        try {
            $this->template->delete_list(array("id" => $template_id));
            echo "ok";
        } catch (Exception $e) {
            echo "ko";
        }
    }
	public function get_template(){
		try {
			$id = $this->input->post('id');
            echo json_encode($this->template->get_by_id($id));
        } catch (Exception $e) {
            echo "ko";
        }
	}
	public function all($user_id="", $stauts = ""){
		$user = (object)$this->session->userdata('user');
		$current = '/template/all/';
		$data = $this->security($current);
		if($data && !empty($data)){
			
				$data['title'] = 'List Templates';
				$array = array();
				foreach($data['user_company'] as $key => $value){
					if($key==0){
						$array = array($value->company);
					}else{
						array_push($array,$value->company);
					}
				}
				$where = array('is_template'=>1);
				$objs = $this->template->list_records($where);
            	$objs2 = array();
				foreach ($objs as $obj) {
					$user_record = $this->users->get_by_id($obj->user);
					if($user->is_root == 1 || $user_record->is_root == 1 || in_array($obj->company , $array)){
						$obj->terms = count(explode(",", $obj->kpis));
						$company_record = $this->private_company->get_by_id($obj->company);
						if($obj->company!=''){
							$obj->company = $company_record->company_name; 
						}
						if($user_record->is_root == 1){
							$obj->created_by = 'System Admin';
						}else{
							$obj->created_by = $user_record->first_name.' '.$user_record->last_name;
						}
						$obj = (object)$obj;
						$objs2[] = $obj;
					}
				}
				$data['objs'] = $objs2;
                $data['active_search'] = true;
				
				$this->load->view('general/header', $data);
				$this->load->view('template/all',$data);
				$this->load->view('general/footer');
			}
	}
	
	public function security($current) {
        $data = parent::security($current);
        if ($data && !empty($data)) {
            $this->load->model('companies_model', 'companies');
            $this->load->model('kpis_model', 'kpis');
            $sectors = $this->companies->get_sector();
            $decision_cats = $this->kpis->get_decision_cats();
            foreach ($decision_cats as $k => $d_c) {
                $decision_cats[$k]->kpis = $this->kpis->get_kpis_by_decision_cat($d_c->decision_category);
            }
            $data['desicion_cats'] = $decision_cats;
        }
        return $data;
    }
	public function save_list_kpis($id="") {
		$current = '/template/all/';
		$data = $this->security($current);
        try {
			$user = (object)$this->session->userdata('user');
            $obj = array();
            $name = $this->input->post('name');
			$description = $this->input->post('description');
			$company = $this->input->post('company');
            $objs = $this->input->post('objs');
            $public = $this->input->post('public');
			$id = $this->input->post('id');
            $obj['user'] = $this->user_id();
            if ($id != "")
                $obj['id'] = $id;
            if (!empty($name))
                $obj['name'] = $name;
			if (!empty($description))
                $obj['description'] = $description;
            if (!empty($objs) && strlen($objs) > 2)
                $obj['kpis'] = $objs;
			if (!empty($company))
                $obj['company'] = $company;
            if (!empty($public))
                $obj['public'] = $public;
            if(!empty($name) && !empty($obj['kpis'])) {
				$obj['is_template'] = 1;
              $obj = $this->template->save($obj);
			  $array = array();
				foreach($data['user_company'] as $key => $value){
					if($key==0){
						$array = array($value->company);
					}else{
						array_push($array,$value->company);
					}
				}
              $objs = $this->template->list_records(array('is_template'=>1));
            	$objs2 = array();
				foreach ($objs as $ob) {
					$user_record = $this->users->get_by_id($ob->user);
					if($user->is_root == 1 || $user_record->is_root == 1 || in_array($ob->company , $array)){
						$ob->terms = count(explode(",", $ob->kpis));
						$company_record = $this->private_company->get_by_id($ob->company);
						if($ob->company!=''){
							$ob->company = $company_record->company_name; 
						}
						if($user_record->is_root == 1){
							$ob->created_by = 'System Admin';
						}else{
							$ob->created_by = $user_record->first_name.' '.$user_record->last_name;
						}
						$ob = (object)$ob;
						$objs2[] = $ob;
					}
				}
				$data['objs'] = $objs2;
				$this->load->view('template/new_list',$data);
            }
            else
              echo "ko";
        } catch (Exception $e) {
            echo "ko";
        }
    }
	public function clone_template(){
		$current = '/template/all/';
		$data = $this->security($current);
		$id 		= $this->input->post('id');
        $template 	= $this->template->get_by_id($id);
		$obj = array();
			$user = (object)$this->session->userdata('user');
            $name 			=  $this->template->get_clone_name($template->name.'_COPY');
			$description 	=  $template->description;
			$company 	=  $template->company;
            $kpis 			= $template->kpis;
            $public 		= $template->public;
            $obj['user'] = $this->user_id();
			
            if (!empty($name))
                $obj['name'] = $name;
			if (!empty($description))
                $obj['description'] = $description;
            if (!empty($kpis))
                $obj['kpis'] = $kpis;
			if (!empty($company))
                $obj['company'] = $company;
            if (!empty($public))
                $obj['public'] = $public;
            if(!empty($name) && !empty($obj['kpis'])) {
				$obj['is_template'] = 1;
              $obj = $this->template->save($obj);
			  $array = array();
				foreach($data['user_company'] as $key => $value){
					if($key==0){
						$array = array($value->company);
					}else{
						array_push($array,$value->company);
					}
				}
              $objs = $this->template->list_records(array('is_template'=>1));
              $objs2 = array();
				foreach ($objs as $ob) {
					$user_record = $this->users->get_by_id($ob->user);
					if($user->is_root == 1 || $user_record->is_root == 1 || in_array($ob->company , $array)){
						$ob->terms = count(explode(",", $ob->kpis));
						$company_record = $this->private_company->get_by_id($ob->company);
						if($ob->company!=''){
							$ob->company = $company_record->company_name; 
						}
						if($user_record->is_root == 1){
							$ob->created_by = 'System Admin';
						}else{
							$ob->created_by = $user_record->first_name.' '.$user_record->last_name;
						}
						$ob = (object)$ob;
						$objs2[] = $ob;
					}
				}
				$data['objs'] = $objs2;
				$this->load->view('template/new_list',$data);
            }
            else
              echo "ko";
	}
	public function clone_template_name(){
		$id 		= $this->input->post('id');
        $template 	= $this->template->get_by_id($id);
		$name 		=  $this->template->get_clone_name($template->name.'_COPY');
         if(!empty($name)) {
			echo $name;	
         }
         else
           echo "ko";
	}
}