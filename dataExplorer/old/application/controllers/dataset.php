<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once "abstract_controller.php";

class Dataset extends abstract_controller {

	public function __construct(){
    	parent::__construct();
			$this->load->model('dataset_model', 'dataset');
			$this->load->model('template_model', 'template');
        }

	public function index($message = "", $is_error = false){
		$user = $this->session->userdata('user');
		if(!empty($user)){
			$this->all();
			}
		else
			redirect('login');
		}

	public function edit($id="",$message = ""){
		$current = '/dataset/edit/'.$id;
		$data = $this->security($current);
		$data['obj'] = $this->dataset->get_by_id($id);
		if($data && !empty($data) && $data['obj']->user == $this->user_id()){
			$data['title'] = 'Edit Dataset';
			$array = array();
				foreach($data['user_company'] as $key => $value){
					if($key==0){
						$array = array($value->company);
					}else{
						array_push($array,$value->company);
					}
				}
			if($message!="")
				$data['message'] = $message;
				$data['years_list'] = $this->dataset->get_years_list();
				
				$where = array('is_template'=>1);
				$objs = $this->template->list_records($where);
				$objs2 = array();
				foreach ($objs as $obj) {
						$user_record = $this->users->get_by_id($obj->user);
						if($user_record->is_root == 1 || in_array($obj->company , $array)){
							$obj->terms = count(explode(",", $obj->kpis));
							if($user_record->is_root == 1){
								$obj->list_icon = 'fa-globe';
							}else{
								$obj->list_icon = 'fa-users';
							}
							$obj = (object)$obj;
							$objs2[] = $obj;
						}
				}
				$data['templates'] = $objs2;
				$user_record = $this->users->get_by_id($data['obj']->user);
				$data['obj']->created_by = $user_record->first_name.' '.$user_record->last_name;
				$template 	= $this->template->get_by_id($data['obj']->template);
				$data['obj']->template_name = $template->name;
				$data['obj']->terms = count(explode(",", $template->kpis))-1;
				$objs3 	= $this->dataset->get_dataset_years_record($id);
				$objs4 = array();
				foreach ($objs3 as $obj) {
						$kpi_detail = $this->dataset->get_kpi_detail($obj->kpi);
						$obj->kpi_name = $kpi_detail->name;
						$obj->kpi_description = $kpi_detail->description;
						$obj = (object)$obj;
						$objs4[] = $obj;
				}
				$data['kpis'] = $objs4;
				$data['op'] = "edit_";
			$this->load->view('general/header', $data);
			$this->load->view('dataset/dataset',$data);
			$this->load->view('general/footer');
			}else{
				redirect('authorization');
			}
		}

    public function delete($dataset_id) {
        try {
            $this->dataset->delete_list(array("id" => $dataset_id));
			$this->dataset->delete_dataset_years_list($dataset_id);
            echo "ok";
        } catch (Exception $e) {
            echo "ko";
        }
    }
	public function add(){
		$current = '/dataset/add';
		$data = $this->security($current);
		if($data && !empty($data)){
			$data['title'] = 'Add new dataset';
			$array = array();
				foreach($data['user_company'] as $key => $value){
					if($key==0){
						$array = array($value->company);
					}else{
						array_push($array,$value->company);
					}
				}
			$data['op'] = "add_";
			$data['years_list'] = $this->dataset->get_years_list();
			$where = array('is_template'=>1);
			$objs = $this->template->list_records($where);
			
            $objs2 = array();
			foreach ($objs as $obj) {
					$user_record = $this->users->get_by_id($obj->user);
					if($user_record->is_root == 1 || in_array($obj->company , $array)){
						$obj->terms = count(explode(",", $obj->kpis));
						if($user_record->is_root == 1){
							$obj->list_icon = 'fa-globe';
						}else{
							$obj->list_icon = 'fa-users';
						}
						$obj = (object)$obj;
						$objs2[] = $obj;
					}
			}
			$data['templates'] = $objs2;
			
			$this->load->view('general/header', $data);
			$this->load->view('dataset/dataset',$data);
			$this->load->view('general/footer');
			}
		}
	
	public function all($user_id="", $stauts = ""){
		$user = (object)$this->session->userdata('user');
		$current = '/dataset/all/';
		$data = $this->security($current);
		if($data && !empty($data)){
			$this->load->model('private_company_model', 'private_company');
				$data['title'] = 'List Data Sets';
				$array = array();
				foreach($data['user_company'] as $key => $value){
					if($key==0){
						$array = array($value->company);
					}else{
						array_push($array,$value->company);
					}
				}
				$objs = $this->dataset->list_records();
				$objs2 = array();
				foreach ($objs as $obj) {
					if(in_array($obj->company , $array)){
						$user_record = $this->users->get_by_id($obj->user);
						$template 	= $this->template->get_by_id($obj->template);
						$obj->template_name = $template->name;
						$company 	= $this->private_company->get_by_id($obj->company);
						$obj->company_name = $company->company_name;
						$obj->terms = count(explode(",", $template->kpis))-1;
						$obj->created_by = $user_record->first_name.' '.$user_record->last_name;
						$obj = (object)$obj;
						$objs2[] = $obj;
					}
				}
				$data['objs'] = $objs2;
            	$data['active_search'] = true;
				$this->load->view('general/header', $data);
				$this->load->view('dataset/all',$data);
				$this->load->view('general/footer');
			}
		}

	public function submit(){
		$current = '/dataset/'.$this->input->post('op');
		$data = $this->security($current);
		if($data && !empty($data)){
            $obj = $this->prepare();
			$this->validation();
			if($this->form_validation->run()){
				$obj = $this->dataset->save($obj);
				$this->dataset->save_years_record($obj->id);
				$this->edit($obj->id,success_changes());
            } else {
				if($this->input->post('op') == "add_")
					$this->add();
				else 
					$this->all();
				}
			}
		else
			redirect('login');
		}
	private function validation(){
        $this->form_validation->set_rules('data_set_title', 'Name', 'trim|required|min_length[2]|max_length[40]|encode_php_tags|xss_clean');
		$this->form_validation->set_rules('data_set_description', 'Sector', 'trim|required|min_length[2]');
		$this->form_validation->set_rules('template', 'State', 'trim|required|min_length[1]');
		//$admin = $obj->admin;
		$this->form_validation->set_error_delimiters('<div class="alert alert-error">', '</div>');
	}
	private function prepare(){
		$obj = array();
		$v1 = $this->input->post('id');
        if (!empty($v1))
            $obj['id'] = $this->input->post('id');
		$v2 = $this->input->post('data_set_title');
        if (!empty($v2))
            $obj['title'] = $this->input->post('data_set_title');
		$v3 = $this->input->post('data_set_description');
        if (!empty($v3))
            $obj['description'] = $this->input->post('data_set_description');
		$v4 = $this->input->post('sources');
        if (!empty($v4))
            $obj['sources'] = $this->input->post('sources');
		$v5 = $this->input->post('template');
        if (!empty($v5))
            $obj['template'] = $this->input->post('template');
		$v6 = $this->input->post('creator');
        if (!empty($v6))
            $obj['user'] = $this->input->post('creator');
		$v7 = $this->input->post('creator');
        if (!empty($v7))
            $obj['company'] = $this->input->post('company');
		return (object)$obj;
	}
	public function get_dataset_table(){
		$year = $this->input->post('year');
		$template = $this->input->post('template');
		$data['year']	= $year;
		$data['template'] = $this->template->get_by_id($template);
		$data['terms']	= $this->dataset->get_term_list($data['template']->kpis);
		$this->load->view('dataset/table',$data);
	}
	public function upload_csv(){
		$status = "";
		$msg = "";
		$file_element_name = 'csv_to_import';
		
		 if ($status != "error")
		{
		  $uploader['file_name'] = 'csv_to_import.csv';
		  $uploader['overwrite'] = TRUE;
		  $uploader['upload_path'] = './data/dataset_csv/';
		  $uploader['allowed_types'] = 'text/plain|text/csv|csv';
		  $uploader['max_size'] = 5000;
		  $uploader['encrypt_name'] = FALSE;
		  $this->load->library('upload', $uploader);
		  
		  if (!$this->upload->do_upload($file_element_name)){
			$status = 'error';
			$msg = $this->upload->display_errors('', '');
		  }else{
		   $data = $this->upload->data();
		   $image_path = $data['full_path'];
		   if(file_exists($image_path)){
			  $status = "success";
			  $msg = "File successfully uploaded";
		  	}else{
			  $status = "error";
			  $msg = "Something went wrong when saving the file, please try again.";
			}
		 }
		 @unlink($_FILES[$file_element_name]);
		 }
		 echo json_encode(array('status' => $status, 'msg' => $msg));
	}
	public function read_csv(){
		$template = $this->input->post('template');
		$this->load->library('csvreader');
        $data['csv'] =   $this->csvreader->parse_file('./data/dataset_csv/csv_to_import.csv');
		
		$years = array();
		foreach($data['csv'][1] as $key=>$value){
			array_push($years,substr($key,0,4));
		}
		$years = array_unique($years);
		$data['years']	= $years;
		$data['template'] = $this->template->get_by_id($template);
		$data['terms']	= $this->dataset->get_term_list($data['template']->kpis);
		$this->load->view('dataset/import_table',$data);
	}
	public function export_csv(){
		$yesrs 		= $this->input->post('year');
		$template 	= $this->input->post('template');
		$temp 		= $this->template->get_by_id($template);
		$kpis		= $this->dataset->get_term_list($temp->kpis);
		$dataset_name = $this->input->post('data_set_title');
		$years_array = array('');
		$csv_array = array();
		foreach($yesrs as $year){
			array_push($years_array,$year.'Q1',$year.'Q2',$year.'Q3',$year.'Q4',$year.'FY');
		}
		array_push($csv_array,$years_array);
		foreach($kpis as $kpi){
			$k = str_replace(',','',$kpi->name);
			$kpis_array = array($k);
			foreach($yesrs as $year){
				$Q1 = str_replace(',','',$this->input->post('Q1'.$year.'_'.$kpi->term_id));
				$Q2 = str_replace(',','',$this->input->post('Q2'.$year.'_'.$kpi->term_id));
				$Q3 = str_replace(',','',$this->input->post('Q3'.$year.'_'.$kpi->term_id));
				$Q4 = str_replace(',','',$this->input->post('Q4'.$year.'_'.$kpi->term_id));
				$FY = str_replace(',','',$this->input->post('FY'.$year.'_'.$kpi->term_id));
				array_push($kpis_array,$Q1,$Q2,$Q3,$Q4,$FY);
			}
			array_push($csv_array,$kpis_array);
		}
		$FileName = $dataset_name . '.csv';
		$fp = fopen('./data/dataset_csv/'.$FileName, 'w');
    		foreach ($csv_array as $fields) {
    			fputcsv($fp, $fields);
			}
		fclose($fp);
		echo $FileName;
		$files = glob('./data/dataset_csv/*');
  		$now   = time();
		foreach ($files as $file){
			if (is_file($file)){
			  	if ($now - filemtime($file) >= 60*60*24){
					unlink($file);
				}
			}
		}
	}
}