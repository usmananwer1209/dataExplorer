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
if($table=='company'){
	$order_group  = 'sic';
	$Id		   = 'entity_id';
	$name 		 = 'company_name';
	$idValue	  ='chkcomp';
	$SelectedList ='SelectedComp';
	}
else if($table=='kpi'){
	$order_group  = $_REQUEST['rdGroup'];
	$Id		   = 'term_id';
	$name 		 = 'name';
	$idValue	  = 'chkkpi';
	$SelectedList ='SelectedKpi';
	}
if(isset($_REQUEST['chkdvalue']) && $_REQUEST['chkdvalue']<>""){
	$chkdvalue = $_REQUEST['chkdvalue'];
    $this->db->where_in('revenues_tier', $chkdvalue);}
if(isset($_REQUEST['filter']) and $_REQUEST['filter']<>'' and $table=='company'){
	$filter =$_REQUEST['filter'];
	if(isset($filter[0]))$this->db->like('company_name',$filter[0]);
	if(isset($filter[1]))$this->db->like('sector',$filter[1]);
	if(isset($filter[2]))$this->db->like('industry',$filter[2]);
	if(isset($filter[3]))$this->db->like('sic',$filter[3]);
	}
else if(isset($_REQUEST['filter']) and $_REQUEST['filter']<>'' and $table=='kpi'){
	$filter =$_REQUEST['filter'];
	if(isset($filter[0]))$this->db->like('name',$filter[0]);
}
$get_outer = $this->db->group_by($order_group)->order_by($order_group, "asc")->get($table)->result();
$index=0;
foreach ($get_outer as $outer) 
{?>
<div id="accordion-first" class="clearfix">
<div class="accordion" id="accordion2">
<div class="accordion-group">
<div class="accordion-heading">
<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion<?=$idValue.$index?>" href="#collapse<?=$idValue.$index?>">
<em class="icon-fixed-width fa fa-plus"></em>
</a>
<div class="checkbox-inline check-warning blue-custom-padd-1">
<input onchange="selectBox(this.id,'<?=$SelectedList?>');" type="checkbox" id="<?=$idValue?>_<?=$index?>">
<label for="<?=$idValue?>_<?=$index?>"><?=$outer->$order_group?></label>
</div></div>
<div id="collapse<?=$idValue.$index?>" class="accordion-body collapse">
<div class="col-md-12">
<div class="accordion-inner accordion-custom-padd" id="ListOfDropdown">
<?php
if(isset($chkdvalue) && $chkdvalue<>""){
    $this->db->where_in('revenues_tier', $chkdvalue);}
if(isset($filter) and $filter<>''and $table=='company'){
	if(isset($filter[0]))$this->db->like('company_name',$filter[0]);
	if(isset($filter[1]))$this->db->like('sector',$filter[1]);
	if(isset($filter[2]))$this->db->like('industry',$filter[2]);
	if(isset($filter[3]))$this->db->like('sic',$filter[3]);
	}
else if(isset($filter) and $filter<>'' and $table=='kpi'){
	if(isset($filter[0]))$this->db->like('name',$filter[0]);
}
$get_inner = $this->db->like($order_group,$outer->$order_group)->order_by($name, "asc")->get($table)->result();
$index_inner=0;
foreach ($get_inner as $inner) 
{?>
    <div class="form-group blue-form-group-1">
        <div class="checkbox-inline">
            <input onchange="selectBox(this.id,'<?=$SelectedList?>');" type="checkbox" id="<?=$idValue?>_<?=$index?>_<?=$index_inner?>" value="<?=$inner->$Id?>">
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
<?php }}
// Ajax Call to get Detail
else if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='getDetail'){
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
	    $this->db->set('companies', $values);}
elseif($table=='list_kpis'){
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
// Ajax Call to GetCSV 
else if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='getCSV'){
}
// Ajax Call to Get Table
else if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='getTable'){
$valuesComp    = $_REQUEST['valuesComp'];
$valuesKpi     = $_REQUEST['valuesKpi'];
$Anual         = $_REQUEST['Anual'];
$Quarterly     = $_REQUEST['Quarterly'];
$Range         = explode(";",$_REQUEST['Range']);
$get_detail = $this->db->where_in('entity_id',$valuesComp )->get('company')->result();
?>
<thead>
													<tr>
														<th>Company</th>
														<th>Ticker</th>
														<th>Reporting Period</th>
														<th>Revenues</th>
														<th>Net Income</th>
														<th>Gross Profit(Loss)</th>
														<th>Tax Rate</th>
														<th>Foreign Earnings</th>
														<th>Common Stocks</th>
													</tr>		
												</thead>
<?php
foreach($get_detail as $detail){
	?><tr>
        <td><?=$detail->company_name?></td>
        <td><?=$detail->stock_symbol?></td>
        <td><?=$Range[0]?></td>
        <td><?=$detail->revenues?></td>
        <td><?=$detail->total_assets?></td>
        <td><?=$detail->company_name?></td>
        <td><?=$detail->company_name?></td>
        <td><?=$detail->company_name?></td>
        <td><?=$detail->company_name?></td>	
													</tr>
    <?php
}
}
?>
