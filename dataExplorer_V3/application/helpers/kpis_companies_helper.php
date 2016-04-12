<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('get_kpi_name')){
  function get_kpi_name($id){
		$CI = get_instance();
		$CI->load->model('kpis_model');
		$obj = $CI->kpis_model->get_by_id($id);
		$name = $obj->name;
		return $name;
  }
  function get_kpi($id){
    $CI = get_instance();
    $CI->load->model('kpis_model');
    $obj = $CI->kpis_model->get_by_id($id);
    return $obj;
  }
  
   function get_kpi_by_termId($val) {
         $CI = get_instance();
        $CI->load->model('list_kpis_model');
        $obj = $CI->list_kpis_model->get_kpi_by_term_id($val);
        return $obj;
    }
    
    function get_company_by_entityId($k){
        
         $CI = get_instance();
        $CI->load->model('companies_model');
        $obj = $CI->companies_model->get_company_by_entity_id($k);
        return $obj;
    }
}


