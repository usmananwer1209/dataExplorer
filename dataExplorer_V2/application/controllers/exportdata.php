<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$folder = dirname(dirname(__FILE__));
require_once $folder . "/helpers/curl.php";
require_once "abstract_controller.php";

class Exportdata extends abstract_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('termResults_model', 'termresult');
        $this->load->model('common_model', 'common');
        $this->load->model('list_companies_model', 'company');
        $this->load->model('companies_model', 'company_model');
        $this->load->model('list_kpis_model', 'kpis');
        $this->load->model('kpis_model', 'kpis_model');
        $this->load->model('reporting_periods_model', 'reporting_periods');
    }

    public function index() {

        //$this->benchmark->mark('Home:index_start');
        $current = '/exportdata/';
        $data = $this->security($current);

        if ($data && !empty($data)) {
            //$this->benchmark->mark('Home:index load_model_start');
            //$this->benchmark->mark('Home:index load_model_end');
            //log_message('debug', "load home model " . $this->benchmark->elapsed_time('Home:index load_model_start', 'Home:index load_model_end'));
            //Getting Companies and KPIS
            $list_companies = $this->company->get_companies();
            $data['getcompanieslist'] = $list_companies;

            $data['get_sector'] = $this->company->getCompanySectors("sector");
            $data['get_industry'] = $this->company->getCompanySectors("industry");
            $data['get_sic'] = $this->company->getCompanySectors("sic");
            $list_kpis = $this->kpis->get_kpis();
            $data['list_kpis'] = $list_kpis;

            $getcompanieslist = $this->company->get_companies_with_order("name");
            $user = $this->session->userdata('user');
            $user_is_root = $user->is_root;
            $data['user_is_root'] = $user_is_root;

            $this->load->view('general/header', $data);
            $this->load->view('exportdata/page', $data);
            $this->load->view('general/footer', $data);
        } else
            redirect('login');

        //$this->benchmark->mark('Home:index_end');
        //log_message('debug', "load home took " . $this->benchmark->elapsed_time('Home:index_start', 'Home:index_end'));
    }

    public function get_term_results($id = "") {
        $result = "";
        $htmldata ="";
        if ($this->input->post('Work') == 'getTable') {
            $this->load->model('api_model', 'api');
            $entities = substr($this->input->post('entities'), 0, -1);
            $data['entities'] = $entities;
            $terms = substr($this->input->post('terms'), 0, -1);
            $data['terms'] = $terms;
            $Anual = $this->input->post('Anual');
            $Quarterly = $this->input->post('Quarterly');
            $periods = explode(';', $this->input->post('periods'));
            $Range = '';
            if (sizeof($periods) > 1) {
                $Range_st = $periods[0];
                $Range_end = $periods[sizeof($periods) - 1];
            } else {
                $Range_st = $periods[0];
                $Range_end = $periods[0];
            }
            if ($Anual == 1) {
                for ($i = $Range_st; $i <= $Range_end; $i++) {
                    $Range .=$i . ",";
                }
            }
            if ($Quarterly == 1) {
                for ($i = $Range_st; $i <= $Range_end; $i++) {
                    $Range .=$i . "Q1,";
                    $Range .=$i . "Q2,";
                    $Range .=$i . "Q3,";
                    $Range .=$i . "Q4,";
                }
            }
            $periods = substr($Range, 0, -1);
            $data['periods'] = $periods;
            $term_array = explode(",", $terms);

            $return_result = $this->termresult->get_termResultData($entities, $terms,  $periods, $term_array);
   
           
    
            $data['grid_data'] = $return_result['resut'];
            $data['resut'] = $return_result['resut'];
            $data['narr'] = $return_result['narr'];
            $data['term_array'] = $term_array;
               $htmldata = $this->load->view('exportdata/term_data_api', $data, true);
           
        }

        echo $htmldata;
        //$this->load->view('exportdata/exportdata_w',$data);
    }

    public function getcompanylist() {
        $results = '';
        $table = $this->input->post('table');
        $slcdvalueId = '';
        $selectedValue = '';
        $order_group = '';
        if ($table == 'company') {
            $order_group = 'sic';
            $Id = 'entity_id';
            $name = 'company_name';
            $idValue = 'chkcomp';
            $SelectedList = 'SelectedComp';
        } else if ($table == 'kpi') {
            $order_group = $this->input->post('rdGroup');
            $Id = 'term_id';
            $name = 'name';
            $idValue = 'chkkpi';
            $SelectedList = 'SelectedKpi';
            if ($order_group == "flat_list") {
                $order_group = "";
            }
        }

        if ($table == 'kpi') {
            if ($order_group == "") {
                $get_outer = $this->kpis_model->get_kpis($order_group, $Id, $name, $idValue, $SelectedList);
            } else {
                $get_outer = $this->kpis_model->get_grouped_kpis($order_group, $Id, $name, $idValue, $SelectedList);
            }
        } else {
            $get_outer = $this->company_model->get_filtered_companies($this->input->post(), $order_group, $Id, $name, $idValue, $SelectedList);
        }



        if (count($get_outer) < 1) {
            echo "No Result(s) found.";
            exit;
        }
        $index = 0;
        foreach ($get_outer as $outer) {


            if ($order_group != "" and ( $table == 'kpi' or $table == 'company')) {


                if ($table == "company") {
                    $get_inner = $this->company_model->get_inner_list($order_group, @$chkdvalue, $outer->$order_group, $name);
                } else {
                    $get_inner = $this->kpis_model->get_inner_list($order_group, @$chkdvalue, @$filter, $outer->$order_group, $name);
                }

                $checked = '';
                $collaps = 'collapsed';
                $collapsinner = '';
                $fa_em_icon_class = 'fa-plus';
                $isactive = ' ';


                foreach ($get_inner as $inner) {
                    if (isset($selectedValue) and $selectedValue == $inner->$name) {
                        $checked = 'checked';
                        $collapsouter = '';
                        $collapsinner = 'in';
                        $fa_em_icon_class = 'fa-minus';
                        $isactive = ' active';
                    }
                }

                $vdata['collaps'] = $collaps;
                $vdata['isactive'] = $isactive;
                $vdata['idValue'] = $idValue;
                $vdata['index'] = $index;
                $vdata['fa_em_icon_class'] = $fa_em_icon_class;
                $vdata['SelectedList'] = $SelectedList;
                $vdata['checked'] = $checked;
                $vdata['outer_order_group'] = $outer->$order_group;
                $vdata['collapsinner'] = $collapsinner;
                $vdata['get_inner'] = $get_inner;
                $vdata['inner_name'] = $inner->$name;
                $vdata['name'] = $name;
                $vdata['selectedValue'] = $selectedValue;
                $vdata['inner_Id'] = $inner->$Id;
                $vdata['Id'] = $Id;
                $vdata['is_order_group_null'] = "No";

                $results.= $this->load->view('exportdata/company_list', $vdata, true);

                $index++;
            } else {

                $checked = '';
                $collaps = 'collapsed';
                $collapsinner = '';


                $checked = '';
                if ($selectedValue == $outer->$name) {
                    $checked = 'checked';
                    $collapsouter = '';
                    $collapsinner = 'in';
                }
                $vdata['is_order_group_null'] = "Yes";
                $vdata['checked'] = $checked;
                $vdata['idValue'] = $idValue;
                $vdata['index'] = $index;
                $vdata['outer_term_id'] = $outer->term_id;

                $results .= $this->load->view('exportdata/company_list', $vdata, true);
                $index++;
            }
            //echo '#@#'.$slcdvalueId;
        }

        echo $results;
        //$this->load->view('exportdata/exportdata_w',$data);
    }

    public function getrange() {

        $query_min = $this->reporting_periods->get_min_period();
        foreach ($query_min as $min)
            ;
        $query_max = $this->reporting_periods->get_max_period();
        foreach ($query_max as $max)
            ;

        echo substr($min->reporting_period, 0, 4) . ";" . substr($max->reporting_period, 0, 4);

        //$this->load->view('exportdata/exportdata_w',$data);
    }

    public function changelist() {
        $id = $this->input->post('id');
        $result = "";
        $result_q = array();
        if ($id == 'CompNameList') {

            $result_q = $this->company->get_companies_list();
        } else if ($id == 'KpiNameList') {

            $result_q = $this->kpis->get_kpis_list();
        }


        foreach ($result_q as $row) {
            $result.="<option value=" . $row->id . ">" . $row->name . "</option>";
        }


        print_r($result);
    }

    public function datasave() {

        $table = $this->input->post('tbl');
        $ListName = $this->input->post('ListName');
        $values = $this->input->post('values');
        $status = $this->input->post('status');
        $user = $this->session->userdata('user');
        $userid = $user->id;
        $user_is_root = $user->is_root;
        $result = "";
        $this->db->set('name', $ListName);
        if ($table == 'list_companies') {


            $dataExist = $this->company->is_name_exist($ListName);


            if (isset($dataExist) and sizeof($dataExist) > 0) {

                $is_public = $dataExist[0]->public;
                $created_by_user = $dataExist[0]->user;

                if ($user_is_root == 1 && $is_public == 1) {
                    $update_list_companies = $this->company->update_list_company($values, $ListName);

                    if ($update_list_companies == true) {
                        $result = "<span class='alert alert-success'>Successfully Saved!</span>";
                    } else {
                        $result = "<span class='alert alert-error'>Operation failed!</span>";
                    }
                } elseif ($created_by_user == $userid) {
                    $update_list_companies = $this->company->update_list_company($values, $ListName);
                    if ($update_list_companies == true) {
                        $result = "<span class='alert alert-success'>Successfully Saved!</span>";
                    } else {
                        $result = "<span class='alert alert-error'>Operation failed!</span>";
                    }
                } else {
                    $result = "<span class='alert alert-error'>Operation failed! You are not autorized to update the public/private list.</span>";
                }
            } else {



                $db_ins = array(
                    'companies' => $values,
                    'public' => $status,
                    'user' => $userid
                );
                $insert_cmp = $this->company->insert_company_list($db_ins);
                if ($insert_cmp == 1) {
                    $result = "<span class='alert alert-success'>Successfully Saved!</span>";
                } else {
                    $result = "<span class='alert alert-error'>Operation failed!</span>";
                }
            }
        } elseif ($table == 'list_kpis') {

            $dataExist = $this->kpis->is_name_exist($ListName);

            if (isset($dataExist) and sizeof($dataExist) > 0) {

                $is_public = $dataExist[0]->public;
                $created_by_user = $dataExist[0]->user;

                if ($user_is_root == 1 && $is_public == 1) {
                    $update_list_companies = $this->kpis->update_kpis_list($values, $ListName);
                    if ($update_list_companies == true) {
                        $result = "<span class='alert alert-success'>Successfully Saved!</span>";
                    } else {
                        $result = "<span class='alert alert-error'>Operation failed!</span>";
                    }
                } elseif ($created_by_user == $userid) {
                    $update_list_companies = $this->kpis->update_kpis_list($values, $ListName);
                    if ($update_list_companies == true) {
                        $result = "<span class='alert alert-success'>Successfully Saved!</span>";
                    } else {
                        $result = "<span class='alert alert-error'>Operation failed!</span>";
                    }
                } else {
                    $result = "<span class='alert alert-error'>Operation failed! You are not autorized to update the public/private list.</span>";
                }
            } else {

                $db_ins = array(
                    'kpis' => $values,
                    'public' => $status,
                    'user' => $userid
                );
                $insert_cmp = $this->kpis->insert_kpis_list($db_ins);

                if ($insert_cmp == 1) {
                    $result = "<span class='alert alert-success'>Successfully Saved!</span>";
                } else {
                    $result = "<span class='alert alert-error'>Operation failed!</span>";
                }
            }
        }
        echo $result;
    }

    public function getdetails() {
        error_reporting(0);
        $table = $this->input->post('tbl');
        $Id = $this->input->post('Id');
        $where = "term_id = '" . $Id . "'";
        $get_detail = $this->common->getDetail($table, $where);

        foreach ($get_detail as $detail)
            $result = $detail->description;

        echo $result;
    }

    public function showlist() {
        $table = $this->input->post('tbl');
        $selectId = $this->input->post('selectId');
        if ($table == 'list_companies') {
            $atbValue = 'companies';
            $table2 = 'company';
            $atbId = 'entity_id';
            $atbName = 'company_name';
        } elseif ($table == 'list_kpis') {
            $atbValue = 'kpis';
            $table2 = 'kpi';
            $atbId = 'term_id';
            $atbName = 'name';
            $DFun = 'Yes';
        }
        $result = "";


        $where = "id = '" . $selectId . "'";
        $get_id = $this->common->getSingleDetail($table, $where);

        foreach ($get_id as $id)
            ;
        $idString = explode('"', $id->$atbValue);
        $idSingle = explode(",", $idString[1]);
        for ($i = 0; $i < sizeof($idSingle); $i++) {
            $where = $atbId." = '" . $idSingle[$i] . "'";
            $get_detail = $this->common->getSingleDetail($table2, $where);
            foreach ($get_detail as $detail) {

                $result.='<option value="' . $detail->$atbId . '"';
                if (isset($DFun)) {
                    $result.="onclick='getDetail(" . $detail->$atbId . ")'";
                }
                $result.='>' . $detail->$atbName . '</option>';
            }
        }
        echo $result;
    }

    public function IndustryList() {
        $table = $this->input->post('tbl');
        $value = $this->input->post('atbValue');
        $result = "";
        $result = '<option value="">All</option>';
        
        $get_query = $this->common->get_industry_list($table, $value);
        foreach ($get_query as $name) {
            $result.='<option value="' . $name->sic . '">' . $name->sic . '</option>';
        }
        echo $result;
    }

    public function SectorList() {
        $table = $this->input->post('tbl');
        $value = $this->input->post('atbValue');
        $result = "";

        $result = '<option value="">All</option>';
        $get_query = $this->common->get_sector_list($table, $value, 'industry', 'industry');
        foreach ($get_query as $name) {
            $result.='<option value="' . $name->industry . '">' . $name->industry . '</option>';
        }
        $result.="#@#";
        $result.='<option value="">All</option>';
        $get_query_sic =$this->common->get_sector_list($table, $value, 'sic', 'sic');
        foreach ($get_query_sic as $sic) {
            $result.='<option value="' . $sic->sic . '">' . $sic->sic . '</option>';
        }
        echo $result;
    }

    public function SICList() {
        
    }

    function array_sorting_decision_asc($term_rules) {


        function arr_sort_decision($a, $b) {
            if ((int) $a['FY'] == (int) $b['FY'])
                return 0;

            return ((int) $a['FY'] < (int) $b['FY']) ? -1 : 1;
        }

        usort($term_rules, "arr_sort_decision");

        $term_rules_arr = array();

        $term_rules_new = array();

        $checkDecisionCategory = (int) @$term_rules[0]['FY'];

        function arr_sort_name($a, $b) {
            if ((int) $a['entityId'] == (int) $b['entityId'])
                return 0;

            return ((int) $a['entityId'] < (int) $b['entityId']) ? -1 : 1;
        }

//echo "yess<pre>"; print_r($term_rules); exit;
        foreach ($term_rules as $t_r) {

            $decisionCategory = (int) $t_r['FY'];

            if ($checkDecisionCategory == $decisionCategory) {

                $term_rules_arr[] = $t_r;
            } else {

                $checkDecisionCategory = $t_r['FY'];

                usort($term_rules_arr, "arr_sort_name");

                $term_rules_new[] = $term_rules_arr;

//			$term_rules_arr = array();

                $term_rules_arr[] = $t_r;
            }
        }
//echo "yes<pre>"; print_r($term_rules_new); exit;
        return $term_rules_new;
    }

}
