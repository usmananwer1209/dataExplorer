<?php
// Ajax Call to get Lists with sector
if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='SectorList'){
$table    = $_REQUEST['tbl'];
$value    = $_REQUEST['atbValue'];
?><option value="">All</option><?php
$get_query = $this->db->like('sector',$value)->group_by("industry")->order_by("industry", "asc")->get($table)->result();
foreach ($get_query as $name) { ?>
<option value="<?=$name->industry?>"><?=$name->industry?></option>
<?php } ?>
#@#
<option value="">All</option>
<?php
$get_query = $this->db->like('sector',$value)->group_by("sic")->order_by("sic", "asc")->get($table)->result();
foreach ($get_query as $name) { ?>
<option value="<?=$name->sic?>"><?=$name->sic?></option>
<?php 
} }
// Ajax Call to get Lists with sector
else if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='IndustryList'){
$table    = $_REQUEST['tbl'];
$value    = $_REQUEST['atbValue'];
?><option value="">All</option><?php
$get_query = $this->db->like('industry',$value)->group_by("sic")->order_by("sic", "asc")->get($table)->result();
foreach ($get_query as $name) { ?>
<option value="<?=$name->sic?>"><?=$name->sic?></option>
<?php 
} }
// Ajax Call to get dropdown list 
else if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='ListOfDropdown'){

$table = $_REQUEST['table'];
$slcdvalueId='';
$selectedValue='';
if($table=='company'){
	$order_group  = 'sic';
	$Id		   = 'entity_id';
	$name 		 = 'company_name';
	$idValue	  = 'chkcomp';
	$SelectedList ='SelectedComp';
	
	if(isset($_REQUEST['chkdvalue']) && $_REQUEST['chkdvalue']<>""){
		$chkdvalue = $_REQUEST['chkdvalue'];
		$this->db->where_in('revenues_tier', $chkdvalue);	
	}

	if( $_REQUEST['industry_name']=='' and isset($_REQUEST['filter'])){
		$filter=$_REQUEST['filter'];
		$this->db->like('sector',$filter[0]);
		$this->db->like('industry',$filter[1]);
		$this->db->like('sic',$filter[2]);
	}elseif(isset($_REQUEST['industry_name']) and empty($filter) ){
		$this->db->like('industry',$_REQUEST['industry_name']);
	}elseif(isset($_REQUEST['industry_name']) and !empty($filter)){
		$filter=$_REQUEST['filter'];
		$this->db->like('sector',$filter[0]);
		$this->db->like('industry',$filter[1]);
		$this->db->like('sic',$filter[2]);
	}
		
}else if($table=='kpi'){
	$order_group  = $_REQUEST['rdGroup'];
	$Id		   = 'term_id';
	$name 		 = 'name';
	$idValue	  = 'chkkpi';
	$SelectedList ='SelectedKpi';
}

$get_outer = $this->db->group_by($order_group)->order_by($order_group, "asc")->get($table)->result();
//print_r($this->db->last_query());

$index=0;
foreach ($get_outer as $outer) 
{
if(isset($chkdvalue) && $chkdvalue<>""){
    $this->db->where_in('revenues_tier', $chkdvalue);}
 if(isset($filter) and $filter<>'' and $table=='kpi'){
	if(isset($filter[0]))$this->db->like('name',$filter[0]);
}
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
?>
<div id="accordion-first" class="clearfix">
<div class="accordion" id="accordion2">
<div class="accordion-group">
<div class="accordion-heading">
<a class="accordion-toggle <?=$collaps?>" id="#accordion<?=$idValue.$index?>" data-toggle="collapse" data-parent="#accordion<?=$idValue.$index?>" href="#collapse<?=$idValue.$index?>">
<em class="icon-fixed-width fa fa-plus"></em>
</a>
<div class="checkbox-inline check-warning blue-custom-padd-1">
<input onchange="selectBox(this.id,'<?=$SelectedList?>');" <?=$checked?> type="checkbox" id="<?=$idValue?>_<?=$index?>">
<label for="<?=$idValue?>_<?=$index?>"><?=$outer->$order_group?></label>
</div></div>
<div id="collapse<?=$idValue.$index?>" class="accordion-body collapse <?=$collapsinner;?>">
<div class="col-md-12">
<div class="accordion-inner accordion-custom-padd" id="ListOfDropdown">
<?php
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
	?>
    <div class="form-group blue-form-group-1">
        <div class="checkbox-inline">
            <input onchange="selectBox(this.id,'<?=$SelectedList?>');" <?=$checked?> class="checkboxinner_<?=$idValue?>_<?=$index?>" type="checkbox" id="<?=$idValue?>_<?=$index?>_<?=$index_inner?>" value="<?=$inner->$Id?>">
            <label for="<?=$idValue?>_<?=$index?>_<?=$index_inner?>" id="lbl<?=$idValue?>_<?=$index?>_<?=$index_inner?>"><?=$inner->$name?>
              
            </label>
        </div>
    </div>
<? 
$index_inner++;
} 
$index++;?>
</div></div></div></div>
<!-- Dont Remove this Div Plzzzzz --><div style="display:block;"></div></div></div> 
<?php } echo '#@#'.$slcdvalueId;}
// Ajax Call to get Detail
if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='getDetail'){
$table    = $_REQUEST['tbl'];
$Id    = $_REQUEST['Id'];
$get_detail = $this->db->where('term_id',$Id )->get($table)->result();
foreach ($get_detail as $detail)
 echo  $detail->description;}
// Ajax Call to Save List in DataBase
else if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='DataSave'){
$table        = $_REQUEST['tbl'];
$ListName     = $_REQUEST['ListName'];
$values       = $_REQUEST['values'];
$status       = $_REQUEST['status'];

		$this->db->set('name', $ListName);
if($table=='list_companies'){
		$dataExist = $this->db->where('companies', $values)->get($table)->result();
		foreach($dataExist as $exist);
		if(isset($exist) and sizeof($exist)>0){echo 'Company List Already Exist! With List Name '.$exist->name; exit;}
	    $this->db->set('companies', $values);}
elseif($table=='list_kpis'){
		$dataExist = $this->db->where('kpis', $values)->get($table)->result();
		foreach($dataExist as $exist);
		if(isset($exist) and sizeof($exist)>0){echo 'KPI List Already Exist! With List Name '.$exist->name; exit;}
			    $this->db->set('kpis', $values);}
		$this->db->set('public', $status);
		if($this->db->insert($table)==1)
		{echo "Succsessfully Saved!";}
		else{echo "Operation failed!";}}
// Ajax Call to Show List 
else if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='ShowList'){
$table        = $_REQUEST['tbl'];
$selectId     = $_REQUEST['selectId'];
if($table=='list_companies'){ 
$atbValue	 = 'companies';
$table2       = 'company';
$atbId	  = 'entity_id';
$atbName	='company_name';
}
elseif($table=='list_kpis'){  
$atbValue	= 'kpis';
$table2     = 'kpi';
$atbId	  ='term_id';
$atbName	='name';
$DFun	   ='Yes';
}
$get_id = $this->db->where('id',$selectId)->limit(1)->get($table)->result();
foreach ($get_id as $id);
		$idString = explode('"',$id->$atbValue);
		$idSingle = explode(",",$idString[1]);
for($i=0;$i<sizeof($idSingle);$i++){
$get_detail = $this->db->where($atbId,$idSingle[$i])->limit(1)->get($table2)->result();
foreach ($get_detail as $detail){
?>
<option value="<?=$detail->$atbId;?>" <?php if(isset($DFun)){echo  "onclick='getDetail(".$detail->$atbId.")'";}?>><?=$detail->$atbName;?></option>
<?php
}}		}
// Ajax Call to Get Table
else if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='getTable'){
echo $results;
}
// Ajax range
else if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='range'){
$query_min = $this->db->limit(1)->order_by('reporting_period',"asc")->get('reporting_periods')->result();
foreach($query_min as $min);
$query_max = $this->db->limit(1)->order_by('reporting_period',"desc")->get('reporting_periods')->result();
foreach($query_max as $max);

echo substr($min->reporting_period,0,4).";".substr($max->reporting_period,0,4);}
// Ajax Change List of exportdata
else if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='changeList'){
$id = $_REQUEST['id'];
if($id=='CompNameList'){
$table = 'list_companies';	
	}
else if($id=='KpiNameList'){
$table = 'list_kpis';	
	}
$get_sector = $this->db->order_by("id","desc")->get($table)->result();
foreach ($get_sector as $sector) {
	?>
    <option value="<?=$sector->id?>"><?=$sector->name?></option>
    <?php
	}
       
	
	}
?>
