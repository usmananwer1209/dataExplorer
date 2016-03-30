<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Exportdata_c extends CI_Controller {
	
	public function __construct() {
        parent::__construct();
      
    }
	
    public function index($id="") { 
		  $result="";
			if($this->input->post('Work')=='getTable'){
				$this->load->model('api_model', 'api');
				$entities=substr($this->input->post('entities'),0,-1);
				$data['entities']=$entities;
				$terms=substr($this->input->post('terms'),0,-1);
				$data['terms']=$terms;
				$Anual=$this->input->post('Anual');
				$Quarterly=$this->input->post('Quarterly');
				$periods = explode(';',$this->input->post('periods'));
				$Range='';
				if(sizeof($periods)>1){
					$Range_st =$periods[0];
					$Range_end=$periods[sizeof($periods)-1];
				}else{
					$Range_st =$periods[0];
					$Range_end=$periods[0];
					  }
				if($Anual==1)	{
					for($i = $Range_st; $i<= $Range_end;$i++){  $Range .=$i.",";  }
				}
				if($Quarterly==1){
					for($i = $Range_st; $i<= $Range_end;$i++){
						$Range .=$i."Q1,";
						$Range .=$i."Q2,";
						$Range .=$i."Q3,";
						$Range .=$i."Q4,";
					}
				}
				$periods= substr($Range,0,-1);
				$data['periods']=$periods;
				//$newreults=file_get_contents("http://data.idaciti.com:81/api/termResult/includeMissing/json?token=oepsy3b6&entity=$entities&term=$terms&includeAnnual=true&includeQuarterly=true");
$apiurl="http://test-data.idaciti.com/api/termResult/json?token=oepsy3b6&entities=$entities&terms=$terms&periods=$periods";
$newreults=file_get_contents("http://test-data.idaciti.com/api/termResult/json?token=oepsy3b6&entities=$entities&terms=$terms&periods=$periods");
			
				//print_r(json_decode($newreults));
		$resuts=json_decode($newreults, true);
                    //    echo "<pre>";print_r($resuts);exit;
               $resut=  $this->array_sorting_decision_asc($resuts);
			
              // echo "<pre>";print_r($resut);exit;
               $newresult  = array();
               
               foreach($resut as $res)
               {
                   foreach($res as $r)
                   {
                       $newresult[] = $r;
                   }    
               }    
               $resut =$newresult;

               echo "<pre>";print_r($newresult);exit;

               
//               $resut =$resut[0];
//    exit;
//print_r($resut);   
                              //   echo "<pre>";print_r($resut);exit;

				//exit;
				//$resut = $this->api->get_termResults_data($entities, $terms,  $periods);
				$data['grid_data']=$resut;
							   
				if(sizeof($resut)>0){
					$termsvalue = explode(",",$terms );
					$termcount=count($termsvalue);
						
						$results="<thead><tr><th>Company</th>
						<th>Ticker</th>
						<th>Reporting Period</th>";
						foreach($termsvalue as $val){
						$kpi = $this->db->where('term_id',$val)->get('kpi')->result();
						foreach($kpi as $kpiname);
							$results.="<th>$kpiname->name</th>";
						}	
						$results .="</tr></thead>";
						$firstrow=0;
						$old_comp="";
						$FQ ="";
						$FY ="";
						$ii=$termcount;
                                                for($i=0;$i<sizeof($resut);$i++){
						$detail = (array)$resut[$i];
					
						$comp = $this->db->where('entity_id',$detail['entityId'])->get('company')->result();
						if(in_array($detail['termId'],$termsvalue)){
										$ii=$ii-1;
						}
                                                
						foreach($comp as $compname);
						if($old_comp != $detail['entityId'] or $FQ != $detail['FQ'] or $FY != $detail['FY']){
							$old_comp = $detail['entityId'];
							$FQ = $detail['FQ'];
							$FY = $detail['FY'];
							
								if($firstrow==1){
									if(count($ii) > 0){
									
										for($j=0; $j<$ii;$j++){
											$results.= "<td>N/A</td>";
										}
									$results.= "<tr>";
									}
								}
								$firstrow=1;
								$ii=$termcount;
							
							   $results.= "<td>$compname->company_name</td><td>".$compname->stock_symbol."</td><td>".$detail['FY']; 
							   if($FQ!='FY'){
								   $results.=$FQ;
							   }
								 $results.="</td>";
								 
							}
							
								$results.="<td>".$detail['value']."</td>";
					}
							if(count($ii) > 0){
									for($j=1; $j < $ii; $j++){
										$results.="<td>N/A</td>";
									}
									$results.="</tr>";
								}
								$data['results']=$results;
				}else{
					$data['results']="No Result(s) match or found";
				}
							
	}
	
				    echo $data['results'];
					//$this->load->view('exportdata/exportdata_w',$data);
    }
	
	
	public function getcompanylist(){
	$results='';
	$table = $this->input->post('table');
	$slcdvalueId='';
	$selectedValue='';
	if($table=='company'){
		$order_group  = 'sic';
		$Id		   = 'entity_id';
		$name 		 = 'company_name';
		$idValue	  = 'chkcomp';
		$SelectedList ='SelectedComp';
		
		//if(isset($this->input->post('chkdvalue')) && $this->input->post('chkdvalue')<>""){
		if($this->input->post('chkdvalue')<>""){
			$chkdvalue = $this->input->post('chkdvalue');
			$this->db->where_in('revenues_tier', $chkdvalue);	
		}
	
		if( $this->input->post('industry_name')=='' and $this->input->post('filter')){
			$filter=$this->input->post('filter');
			$this->db->like('sector',$filter[0]);
			$this->db->like('industry',$filter[1]);
			$this->db->like('sic',$filter[2]);
		}elseif($this->input->post('industry_name') and empty($filter) ){
			$this->db->like('industry',$this->input->post('industry_name'));
		}elseif($this->input->post('industry_name') and !empty($filter)){
			$filter=$this->input->post('filter');
			$this->db->like('sector',$filter[0]);
			$this->db->like('industry',$filter[1]);
			$this->db->like('sic',$filter[2]);
		}
			
	}else if($table=='kpi'){
		$order_group=$this->input->post('rdGroup');
		$Id='term_id';
		$name='name';
		$idValue='chkkpi';
		$SelectedList='SelectedKpi';
		if($order_group=="flat_list"){
			$order_group="";

		}
	}
	if($order_group=="" & $table=='kpi' ){
		$get_outer = $this->db->order_by("name", "asc")->get($table)->result();
	}else{
		$get_outer = $this->db->group_by($order_group)->order_by($order_group, "asc")->get($table)->result();
	}
	
	$index=0;
	foreach ($get_outer as $outer) 
	{
	if(isset($chkdvalue) && $chkdvalue<>""){
		$this->db->where_in('revenues_tier', $chkdvalue);}
	 if(isset($filter) and $filter<>'' and $table=='kpi'){
		if(isset($filter[0]))$this->db->like('name',$filter[0]);
	}
	if($order_group!="" and ($table=='kpi' or $table=='company')  ){
		$get_inner = $this->db->like($order_group,$outer->$order_group)->order_by($name, "asc")->get($table)->result();
		$checked='';
		$collaps='collapsed';
		$collapsinner='';
	
	
	foreach ($get_inner as $inner) 
	{
		if(isset($selectedValue) and $selectedValue==$inner->$name){ 
			$checked='checked';
			$collapsouter='';
			$collapsinner='in';
		}
	}
	
	$results.='<div id="accordion-first" class="clearfix">
	<div class="accordion" id="accordion2">
	<div class="accordion-group">
	<div class="accordion-heading">
	<a class="accordion-toggle '.$collaps.'" id="#accordion'.$idValue.$index.'" data-toggle="collapse" data-parent="#accordion'.$idValue.$index.'" href="#collapse'.$idValue.$index.'">
	<em class="icon-fixed-width fa fa-plus"></em>
	</a>
	<div class="checkbox-inline check-warning blue-custom-padd-1">
	<input onchange="selectBox(this.id,\''.$SelectedList.'\');" '.$checked.' type="checkbox" id="'.$idValue.'_'.$index.'">
	<label for="'.$idValue.'_'.$index.'">'.$outer->$order_group.'</label>
	</div></div>
	<div id="collapse'.$idValue.$index.'" class="accordion-body collapse '.$collapsinner.'">
	<div class="col-md-12">
	<div class="accordion-inner accordion-custom-padd" id="ListOfDropdown">';
	
	$index_inner=0;
	foreach ($get_inner as $inner) 
	{
		$checked='';
		if($selectedValue==$inner->$name){ 
			$checked='checked';
			$collapsouter='';
			$collapsinner='in';
		//}
		//if($selectedValue==$inner->$name){ $checked='checked';
			$slcdvalueId = $idValue."_".$index."_".$index_inner; 
		}
	
		$results.='<div class="form-group blue-form-group-1">
			<div class="checkbox-inline">
				<input onchange="selectBox(this.id,\''.$SelectedList.'\');" '.$checked.' class="checkboxinner_'.$idValue.'_'.$index.'" type="checkbox" id="'.$idValue.'_'.$index.'_'.$index_inner.'" value="'.$inner->$Id.'">
				<label for="'.$idValue.'_'.$index.'_'.$index_inner.'" id="lbl'.$idValue.'_'.$index.'_'.$index_inner.'">'.$inner->$name.'
				</label>
			</div>
		</div>';
	$index_inner++;
	} 
	$index++;
	$results.='</div></div></div></div>
	<!-- Dont Remove this Div Plzzzzz --><div style="display:block;"></div></div></div>';
	 }else{
		 //Flat List Data goes here
		// $get_inner = $this->db->like($order_group,$outer->$order_group)->order_by($name, "asc")->get($table)->result();
			$checked='';
		$collaps='collapsed';
	$collapsinner='';
	//echo "<pre>";
	//print_r($outer);
	/*$results.='<div id="accordion-first" class="clearfix">
	<div class="accordion" id="accordion2">
	<div class="accordion-group">
	<div class="accordion-heading">
	<a class="accordion-toggle '.$collaps.'" id="#accordion'.$idValue.$index.'" data-toggle="collapse" data-parent="#accordion'.$idValue.$index.'" href="#collapse'.$idValue.$index.'">
	<em class="icon-fixed-width fa fa-plus"></em>
	</a>
	<div class="checkbox-inline check-warning blue-custom-padd-1">
	<input onchange="selectBox(this.id,\''.$SelectedList.'\');" '.$checked.' type="checkbox" id="'.$idValue.'_'.$index.'">
	<label for="'.$idValue.'_'.$index.'">'.$outer->$order_group.'</label>
	</div></div>
	<div id="collapse'.$idValue.$index.'" class="accordion-body collapse '.$collapsinner.'">
	<div class="col-md-12">
	<div class="accordion-inner accordion-custom-padd" id="ListOfDropdown">';
	*/
	
		$checked='';
		if($selectedValue==$outer->$name){ 
			$checked='checked';
			$collapsouter='';
			$collapsinner='in';
		//}
		//if($selectedValue==$inner->$name){ $checked='checked';
			//$slcdvalueId = $idValue."_".$index."_".$index_inner; 
		}
	
		$results.='<div class="form-group blue-form-group-1">
			<div class="checkbox-inline">
				<input onchange="selectedboxflat(this.id);" '.$checked.' type="checkbox" id="'.$idValue.'_'.$index.'" value="'.$outer->term_id.'">
	<label for="'.$idValue.'_'.$index.'" id="lbl'.$idValue.'_'.$index.'">'.$outer->name.'</label>
			</div>
		</div>';

	$index++;
	//$results.='</div></div></div></div>;
	$results.='<!-- Dont Remove this Div Plzzzzz --><div style="display:block;"></div></div></div>';
		 
	 }
	//echo '#@#'.$slcdvalueId;
		
	}
		
		echo $results;
		//$this->load->view('exportdata/exportdata_w',$data);
	}
	
	
	public function getrange(){
			
		$query_min = $this->db->limit(1)->order_by('reporting_period',"asc")->get('reporting_periods')->result();
		foreach($query_min as $min);
		$query_max = $this->db->limit(1)->order_by('reporting_period',"desc")->get('reporting_periods')->result();
		foreach($query_max as $max);
		
		echo substr($min->reporting_period,0,4).";".substr($max->reporting_period,0,4);

		//$this->load->view('exportdata/exportdata_w',$data);
		
	}


	public function changelist(){			
		$id = $this->input->post('id');
		$result="";
		if($id=='CompNameList'){
			$table = 'list_companies';	
		}else if($id=='KpiNameList'){
			$table = 'list_kpis';	
		}
		$get_sector = $this->db->order_by("id","desc")->get($table)->result();
		foreach ($get_sector as $sector) {
			$result.="<option value=".$sector->id.">".$sector->name."</option>";
		}
		print_r($result);
	}
	
	public function datasave(){
				
		$table=$this->input->post('tbl');
		$ListName=$this->input->post('ListName');
		$values=$this->input->post('values');
		$status=$this->input->post('status');

		$result="";
		$this->db->set('name', $ListName);
		if($table=='list_companies'){
			$dataExist = $this->db->where('name', $ListName)->get($table)->result();
			
			
			if(isset($dataExist) and sizeof($dataExist)>0){
				$result= 'Company List Name Already Exist!' ;
			}
			else{
					$this->db->set('companies', $values);
					$this->db->set('public', $status);
					if($this->db->insert($table)==1)
					{
						$result="Successfully Saved!";
					}else{
						$result="Operation failed!";
					}

			}
		}elseif($table=='list_kpis'){
			$dataExist = $this->db->where('name', $ListName)->get($table)->result();

			if(isset($dataExist) and sizeof($dataExist)>0){
					$result= 'KPI List Name Already Exist! '; 
			}
			else{
					$this->db->set('kpis', $values);
					$this->db->set('public', $status);
					if($this->db->insert($table)==1)
					{
						$result="Succsessfully Saved!";
					}else{
						$result="Operation failed!";
					}
			}	
					
		}			

		echo $result;
				
	}
	
	public function getdetails(){
		error_reporting(0);
		$table=$this->input->post('tbl');
		$Id=$this->input->post('Id');
		$get_detail=$this->db->where('term_id',$Id )->get($table)->result();
		foreach($get_detail as $detail)
		$result=$detail->description;
		
		echo $result;
	}
	
	public function showlist(){
		$table=$this->input->post('tbl');
		$selectId=$this->input->post('selectId');
		if($table=='list_companies'){ 
			$atbValue= 'companies';
			$table2= 'company';
			$atbId= 'entity_id';
			$atbName='company_name';
		}elseif($table=='list_kpis'){  
			$atbValue= 'kpis';
			$table2= 'kpi';
			$atbId='term_id';
			$atbName='name';
			$DFun='Yes';
		}
		$result="";
		$get_id = $this->db->where('id',$selectId)->limit(1)->get($table)->result();
		foreach ($get_id as $id);
			$idString = explode('"',$id->$atbValue);
			$idSingle = explode(",",$idString[1]);
			for($i=0;$i<sizeof($idSingle);$i++){
				$get_detail = $this->db->where($atbId,$idSingle[$i])->limit(1)->get($table2)->result();
				foreach ($get_detail as $detail){
				
					$result.='<option value="'.$detail->$atbId.'"';
					if(isset($DFun)){
						$result.="onclick='getDetail(".$detail->$atbId.")'";
					}
					$result.='>'.$detail->$atbName.'</option>';
				
				}
			}
		echo $result;		

	}
	public function IndustryList(){	
		$table=$this->input->post('tbl');
		$value=$this->input->post('atbValue');
		$result="";

        $result='<option value="">All</option>';
		
		$get_query = $this->db->like('industry',$value)->group_by("sic")->order_by("sic", "asc")->get($table)->result();
		foreach ($get_query as $name) {
			$result.='<option value="'.$name->sic.'">'.$name->sic.'</option>';
		}
		echo $result; 	

	}

	public function SectorList(){	
		$table=$this->input->post('tbl');
		$value=$this->input->post('atbValue');
		$result="";

        $result='<option value="">All</option>';
		$get_query = $this->db->like('sector',$value)->group_by("industry")->order_by("industry", "asc")->get($table)->result();
		foreach ($get_query as $name) { 
			$result.='<option value="'.$name->industry.'">'.$name->industry.'</option>';
		 } 
		echo $result.="#@#";
        $result.='<option value="">All</option>';
		$get_query = $this->db->like('sector',$value)->group_by("sic")->order_by("sic", "asc")->get($table)->result();
		foreach ($get_query as $name) { 
		$result.='<option value="'.$name->sic.'">'.$name->sic.'</option>';
		} 

		echo $result;
	}
	
	public function SICList(){
		
	}
        
        function array_sorting_decision_asc($term_rules)
{

   
	function arr_sort_decision($a, $b)
	{
		if ((int)$a['FY']==(int)$b['FY']) return 0;
	   
	   	return ((int)$a['FY']<(int)$b['FY'])?-1:1;
	}

	usort($term_rules,"arr_sort_decision");

	$term_rules_arr = array();

	$term_rules_new = array();

	$checkDecisionCategory = (int)$term_rules[0]['FY'];	

	function arr_sort_name($a, $b)
	{
		if ((int)$a['entityId']==(int)$b['entityId']) return 0;
	   
	   	return ((int)$a['entityId']<(int)$b['entityId'])?-1:1;
	}
//echo "yess<pre>"; print_r($term_rules); exit;
	foreach($term_rules as $t_r)
	{

		$decisionCategory = (int)$t_r['FY'];

		if($checkDecisionCategory == $decisionCategory)	
		{

			$term_rules_arr[] = $t_r;	

		}
		else
		{

			$checkDecisionCategory = $t_r['FY'];

			usort($term_rules_arr,"arr_sort_name");

			$term_rules_new[] = $term_rules_arr;	

//			$term_rules_arr = array();

			$term_rules_arr[] = $t_r;

		}	

	}	
//echo "yes<pre>"; print_r($term_rules_new); exit;
	return $term_rules_new;

} 

	
}