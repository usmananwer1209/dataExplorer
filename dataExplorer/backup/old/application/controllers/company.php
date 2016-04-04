<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once "abstract_controller.php";

class Company extends abstract_controller {

	public function __construct(){
    	parent::__construct();
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

	public function edit($id="",$message = ""){
		$current = '/company/edit/'.$id;
		$data = $this->security($current);
		if($data && !empty($data)){
			$data['title'] = 'Edit Company';
			if($message!="")
				$data['message'] = $message;
				$data['obj'] = $this->private_company->get_by_id($id);//TODO if is null
				$data['state'] = $this->private_company->state_list();
				$data['sector'] = $this->private_company->sector_list();
				$data['users'] = $this->user_companies->list_users_company(1,$id);
				$data['guests'] = $this->user_companies->list_users_company(2,$id);
				$data['op'] = "edit_";
			
			$this->load->view('general/header', $data);
			$this->load->view('company/company',$data);
			$this->load->view('general/footer');
			}
		}

    public function delete($company_id) {
        try {
            $this->user_companies->delete_list(array("company" => $company_id));
            $this->private_company->delete_list(array("id" => $company_id));
            echo "ok";
        } catch (Exception $e) {
            echo "ko";
        }
    }
	public function add(){
		$current = '/company/add';
		$data = $this->security($current);
		if($data && !empty($data)){
			$data['title'] = 'Add new company';
			$data['op'] = "add_";
			$data['state'] = $this->private_company->state_list();
			$data['sector'] = $this->private_company->sector_list();
			$this->load->view('general/header', $data);
			$this->load->view('company/company',$data);
			$this->load->view('general/footer');
			}
		}
	public function get_industry_by_sector(){
		$industory = $this->private_company->get_industry_by_sector();
		echo json_encode($industory);
	}
	public function get_sic_by_industry(){
		$sic = $this->private_company->get_sic_by_industry();
		echo json_encode($sic);
	}
	public function all($user_id="", $stauts = ""){
		$user = (object)$this->session->userdata('user');
		$current = '/company/all/';
		$data = $this->security($current);
		if($data && !empty($data)){
				$data['title'] = 'List Private Companies';
				$objs = $this->private_company->list_records();
            	
				$objs2 = array();
				foreach ($objs as $obj) {
					$obj->user_count = $this->user_companies->count_list_users(1,$obj->id);
					$obj->guest_count = $this->user_companies->count_list_users(2,$obj->id);
					$obj = (object)$obj;
					$objs2[] = $obj;
				}
				$data['objs'] = $objs2;
				
                $data['active_search'] = true;
				
				$this->load->view('general/header', $data);
				$this->load->view('company/all',$data);
				$this->load->view('general/footer');
			}
		}

	public function submit(){
		$current = '/company/'.$this->input->post('op');
		$data = $this->security($current);
		if($data && !empty($data)){
            $obj = $this->prepare();
			$this->validation();
			if($this->form_validation->run()){
				$obj = $this->private_company->save($obj);
				$this->user_companies->save_member_user($obj->id);
				$this->user_companies->save_member_guest($obj->id);
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
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|max_length[40]|encode_php_tags|xss_clean');
		$this->form_validation->set_rules('sector', 'Sector', 'trim|required|min_length[2]');
		$this->form_validation->set_rules('state', 'State', 'trim|required|min_length[2]');
		$this->form_validation->set_rules('industry', 'Industry', 'trim|required|min_length[2]');
		$this->form_validation->set_rules('sic', 'SIC', 'trim|required|min_length[2]');
		//$admin = $obj->admin;
		$this->form_validation->set_error_delimiters('<div class="alert alert-error">', '</div>');
	}
	private function prepare(){
		$obj = array();
		$v1 = $this->input->post('id');
        if (!empty($v1))
            $obj['id'] = $this->input->post('id');
		$v2 = $this->input->post('name');
        if (!empty($v2))
            $obj['company_name'] = $this->input->post('name');
		$v3 = $this->input->post('sector');
        if (!empty($v3))
            $obj['sector'] = $this->input->post('sector');
		$v4 = $this->input->post('industry');
        if (!empty($v4))
            $obj['industry'] = $this->input->post('industry');
		$v5 = $this->input->post('state');
        if (!empty($v5))
            $obj['state'] = $this->input->post('state');
		$v6 = $this->input->post('sic');
        if (!empty($v6))
            $obj['sic'] = $this->input->post('sic');
		$v7 = $this->input->post('sic_code');
        if (!empty($v7))
            $obj['sic_code'] = $this->input->post('sic_code');
		return (object)$obj;
	}
	public function list_users(){
		$objs = $this->private_company->get_users_listing();
		print json_encode($objs);
	}
	
	public function update_company(){
		/* Script will be useable when user wants to update company revenue, revenue tire, total assets, total assests tire */
		/* Simply replace the file in directory (company_csv) with same name and directly hit the url */
		$this->load->library('csvreader');
        $csv =   $this->csvreader->parse_file('./data/company_csv/EntityProperties.csv');
		$this->private_company->update_company($csv);
		echo 'upload done';
	}
}