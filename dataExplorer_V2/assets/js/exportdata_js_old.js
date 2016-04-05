var urlSrc = "url('assets/img/loader.gif') center center no-repeat";
//Get Data of lists With Company name
function ListAsComp(){
	var CompName = document.getElementById('company_name').value;
	$.post('exportdata_c',{'Work':'ListAsComp','tbl':'company','CompName':CompName},
	function(data)
	{
	var lists = data.split("#@#");
	document.getElementById('sectorList').value=lists[0]; 
	document.getElementById('industryList').value=lists[1]; 
	document.getElementById('sicList').value=lists[2];
	});
	}
// Get Lists of sector/industry/sic
function selectlist(Work){
	if(Work	 =='SectorList'){ 		var atbValue = document.getElementById('sectorList').value; }
	else if(Work=='IndustryList'){ var atbValue = document.getElementById('industryList').value; }
	else if(Work=='SICList'){ 	  var atbValue = document.getElementById('sicList').value; }
	$.post('exportdata_c',{'Work':Work,'tbl':'company','atbValue':atbValue},
	function(data)
	{
	var lists = data.split("#@#");
	if(Work=='SectorList'){ 
	document.getElementById('industryList').innerHTML=lists[0]; 
	document.getElementById('sicList').innerHTML=lists[1]; 
	}
	else if(Work=='IndustryList'){ 
	document.getElementById('sicList').innerHTML=lists[0];
	}});
	}
// Get Dropdown List 
function ListOfDropdown(table,divid,rdGroup){
		document.getElementById(divid).style.background=urlSrc;
		document.getElementById(divid).innerHTML=''
	var i=1;
	var chkdvalue		 = [];
	var filter			= [];
	if(table=='company'){
	var 	SelectedList ='SelectedComp';
	filter.push(document.getElementById('sectorList').value);
	filter.push(document.getElementById('industryList').value);
	filter.push(document.getElementById('sicList').value);
	while(document.getElementById("chkAmt"+i)){
		if(document.getElementById("chkAmt"+i).checked==true)
		chkdvalue.push(document.getElementById("chkAmt"+i).value);
		i++;}}
	else if(table=='kpi'){
	var 	SelectedList ='SelectedKpi';
		if(document.getElementById('decision_category').checked==true)
		rdGroup= 'decision_category';
		if(document.getElementById('financial_category').checked==true)
		rdGroup= 'financial_category';
	}
	$.post('exportdata_c',{'Work':'ListOfDropdown','table':table,'chkdvalue':chkdvalue,'filter':filter,'rdGroup':rdGroup},function(data){	
	if(data!=''){

		var arrData = data.split("#@#");
		document.getElementById(divid).innerHTML=arrData[0];
		document.getElementById(divid).style.backgroundImage='';

		selectBox(arrData[1],SelectedList);
	}
	else alert('Data Not Avalaible!');}
);
	}
// Select All/Selected
function SelectAllSelected(){
	var i=1;
while(document.getElementById("chkAmt"+i)){//alert(document.getElementById("chkAmt"+i));
	document.getElementById("chkAmt"+i).checked=false;
	i++;
	}
		ListOfDropdown('company','ListOfDropdown');
}
// Select/Unselect companies by group
function selectBox(Id,selectId){
	var Id_p = Id.split("_");
	var arrSize = Id_p.length;
	var chkStatus = document.getElementById(Id).checked;
// If Main Category Called and checked
	if(arrSize==2 && chkStatus==true)
	{   	var i=0;
		while(document.getElementById(Id+"_"+i)){
	if(checkinList(document.getElementById(Id+"_"+i).value,selectId)==false){alert('Already Exist');}
	else{
		document.getElementById(Id+"_"+i).checked=chkStatus;
		if(selectId=='SelectedKpi')
		{document.getElementById(selectId).innerHTML=document.getElementById(selectId).innerHTML+"<option value="+document.getElementById(Id+"_"+i).value+" onclick='getDetail("+document.getElementById(Id+"_"+i).value+");'>"+document.getElementById("lbl"+Id+"_"+i).textContent+"</option>";
		var count = document.getElementById('CountKpi').textContent;
		document.getElementById('CountKpi').textContent = parseInt(count)+1;
		}
		else {document.getElementById(selectId).innerHTML=document.getElementById(selectId).innerHTML+"<option value="+document.getElementById(Id+"_"+i).value+" >"+document.getElementById("lbl"+Id+"_"+i).textContent+"</option>";
		var count = document.getElementById('CountComp').textContent;
		document.getElementById('CountComp').textContent = parseInt(count)+1;
		}
	document.getElementById('txt'+selectId).innerHTML = 
	document.getElementById('txt'+selectId).innerHTML+"<input type='hidden' id='txt"+document.getElementById(Id+"_"+i).value+"' value='"+Id+"_"+i+"'>";}
			i++;
		}
	}
// If Main Category Called and un-checked
	else if(arrSize==2 && chkStatus==false)
	{   
	var i=0;
		while(document.getElementById(Id+"_"+i)){
		document.getElementById(Id+"_"+i).checked=chkStatus;
		RemovefromList(document.getElementById(Id+"_"+i).value,selectId);
		i++;
		}
	}
// If Sub Category Called and checked
	else if(arrSize==3 && chkStatus==true)
	{ 
	  
	if(document.getElementById(Id_p[0]+"_"+Id_p[1])){
	if(checkinList(document.getElementById(Id).value,selectId)==false){alert('Already Exist'); return false;}
	document.getElementById(Id_p[0]+"_"+Id_p[1]).checked=chkStatus;
	if(selectId=='SelectedKpi')
	{
	document.getElementById(selectId).innerHTML = document.getElementById(selectId).innerHTML+"<option  value="+document.getElementById(Id).value+" onclick='getDetail("+document.getElementById(Id).value+");'>"+document.getElementById("lbl"+Id).textContent+"</option> ";
		var count = document.getElementById('CountKpi').textContent;
		document.getElementById('CountKpi').textContent = parseInt(count)+1;
	}
	else{document.getElementById(selectId).innerHTML = document.getElementById(selectId).innerHTML+"<option  value="+document.getElementById(Id).value+" >"+document.getElementById("lbl"+Id).textContent+"</option> ";
		var count = document.getElementById('CountComp').textContent;
		document.getElementById('CountComp').textContent = parseInt(count)+1;
}
	document.getElementById('txt'+selectId).innerHTML = document.getElementById('txt'+selectId).innerHTML+"<input type='hidden' id='txt"+document.getElementById(Id).value+"' value='"+Id+"'>";
		i++;
		}}
// If Sub Category Called and un-checked
	else if(arrSize==3 && chkStatus==false)
	{   
		if(document.getElementById(Id)){
		RemovefromList(document.getElementById(Id).value,selectId);
			i++;
	}	}}
// Check in List
function checkinList(Value,selectId){
var selectobject=document.getElementById(selectId);
  for (var i=0; i<selectobject.length; i++){
  if (selectobject.options[i].value == Value ){
	  return false;
  }}
  }
// remove selected data with check boxes
function RemovefromList(Value,selectId){
var selectobject=document.getElementById(selectId);
  for (var i=0; i<selectobject.length; i++){
  if (selectobject.options[i].value == Value ){
     selectobject.remove(i);
if(selectId=='SelectedKpi')
	{
		var count = document.getElementById('CountKpi').textContent;
		document.getElementById('CountKpi').textContent = parseInt(count)-1;
	}
	else{
		var count = document.getElementById('CountComp').textContent;
		document.getElementById('CountComp').textContent = parseInt(count)-1;
	}}  }}
// remove selected data with remove button
function RemoveSelected(selectId){
var selectobject=document.getElementById(selectId);
var count =selectobject.length;
  for (var i=0; i<count; i++){
	var x = document.getElementById(selectId);
if(document.getElementById("txt"+selectobject.options[x.selectedIndex].value)){
	var optunslct = document.getElementById("txt"+selectobject.options[x.selectedIndex].value).value;
	document.getElementById(optunslct).checked=false;
if(selectId=='SelectedKpi')
	{
		var count = document.getElementById('CountKpi').textContent;
		document.getElementById('CountKpi').textContent = parseInt(count)-1;
	}
	else{
		var count = document.getElementById('CountComp').textContent;
		document.getElementById('CountComp').textContent = parseInt(count)-1;
	}
} 	x.remove(x.selectedIndex);

  }}
// Chenge Selection of All/Selected
function selectLimts(){
	document.getElementById('rdSelected').checked=true;
	}
//Get Detail of Selected KPI
function getDetail(Id){
$.post('exportdata_c',{'Work':'getDetail','tbl':'kpi','Id':Id},
	function(data)
	{document.getElementById('getDetail').innerHTML=data; });	}
// List Data Save in Database
function DataSave(selectId){
if(selectId=='SelectedKpi'){
	var ListName = 'KpiListName';
	var table    = 'list_kpis';
	var status   = 'KpiStatus';
	var dataList = 'KpiNameList';
	}
else{
	var ListName = 'compListName';
	var table    = 'list_companies';
	var status   = 'CompStatus';
	var dataList = 'CompNameList';
	}
  if(document.getElementById(ListName).value==''){ alert('List name is empty!'); 
  document.getElementById(ListName).focus();return false;}  
  ListName = document.getElementById(ListName).value;
  if(document.getElementById(status).checked==false){status   = 0;}
  else{status   = 1;}
  var selectobject=document.getElementById(selectId);
  var values='"';
  for (var i=0; i<selectobject.length; i++){
		values +=selectobject.options[i].value+",";
}
     values+='"';
$.post('exportdata_c',{'Work':'DataSave','tbl':table,'ListName':ListName,'values':values,'status':status},
	function(data)
	{ alert(data);
	changeList(dataList);
	 });	}
// List Data Show in List
function ShowList(selectId){
if(selectId=='CompNameList'){ 
var table    = 'list_companies';
var listId   = 'SelectedComp';
var CountId = 'CountComp'; }
else{ 
var table    = 'list_kpis';
var listId   = 'SelectedKpi';
var CountId = 'CountKpi'; }
selectId = document.getElementById(selectId).value;
$.post('exportdata_c',{'Work':'ShowList','tbl':table,'selectId':selectId},
	function(data)
	{document.getElementById(listId).innerHTML=data;
	document.getElementById(CountId).textContent=document.getElementById(listId).length;
 });	}
// Refresh Table
function getTable(){
  var Range 		   = document.getElementById('range_04').value;
  var SelectedComp=document.getElementById('SelectedComp');
 var CompListName="";
  for (var i=0; i<SelectedComp.length; i++){
	  	CompListName += noformate(SelectedComp.options[i].value,'6')+",";
}

 var SelectedKpi=document.getElementById('SelectedKpi');
 var KpiListName="";
  for (var i=0; i<SelectedKpi.length; i++){
		KpiListName +=SelectedKpi.options[i].value+",";
}
var Anual=0;
var Quarterly=0;
  if(document.getElementById('Anual').checked==true)	{ Anual=1;	   }
  if(document.getElementById('Quarterly').checked==true){ Quarterly=1; }
 if(Anual==0 && Quarterly==0){alert('Anual/Quarterly Not Selected.'); return false;}
 else if(CompListName =='' || KpiListName==''){alert('Companies or KPIs not selected.'); return false;}
 else	{
	 document.getElementById('DataTable').style.backgroundImage=urlSrc;
		document.getElementById('DataTable').innerHTML=''}
$.post('exportdata_c',{'Work':'getTable','entities':CompListName,'terms':KpiListName,'periods':Range,'Anual':Anual,'Quarterly':Quarterly},
	function(data)
	{
		//alert(data);
			 document.getElementById('DataTable').style.backgroundImage='';
		document.getElementById('DataTable').innerHTML=data;
		 
		});	
		}
// CSV
function getCSV(){
	var CSVName   	  		= document.getElementById('CSVName').value;
	var table     		  = document.getElementById('DataTable').innerHTML;
	var data      	 	   = table.replace(/,/g,'')
						 	.replace(/<thead>/g,'')
						 	.replace(/<\/thead>/g,'')
						 	.replace(/<tbody>/g,'')
						 	.replace(/<\/tbody>/g,'')
						 	.replace(/<tr>/g,'')
						 	.replace(/<\/tr>/g,'\r\n')
						 	.replace(/<th>/g,'')
						 	.replace(/<\/th>/g,',')
						 	.replace(/<td>/g,'')
						 	.replace(/<\/td>/g,',')
						 	.replace(/\t/g,'')
						 	.replace(/\n/g,'');
						
	var mylink    		 = document.createElement('a');
		mylink.download 	= ""+CSVName+'.csv';
		mylink.href	 	= "data:application/csv," + escape(data);
		mylink.click();
						 	}
//NoFormate
function noformate(Num,len){
	while(Num.length<len){
		Num = '0'+Num;
		}
	return Num;	}
// GetRange
function range(){
	$.post('exportdata_c',{'Work':'range'},
	function(data)
	{ 
	var ArrVal = data.split(";");

	$("#range_04").ionRangeSlider({
		
			type: "double",
			grid: true,
			min: ArrVal[0],
			max: ArrVal[1],
			from: ArrVal[0],
			to: (ArrVal[1]-1)
		}); 
	});}
// Select KPI
function SelectCompKPI(id){
	if(id=='company_name'){
		var atb = 'comp';
		var ATB = 'Comp';
		var LisId = '';
		}
	else if(id=='KPIname'){
		var atb = 'kpi';
		var ATB = 'Kpi';
		}
	var KPIName = $("#"+id).val();
	var i=0;var j=0;
//	if(document.getElementById(Id_p[0]+"_"+Id_p[1])){}
	while(document.getElementById("chk"+atb+"_"+i+"_"+j)){
	while(document.getElementById("chk"+atb+"_"+i+"_"+j)){
	if(document.getElementById("lblchk"+atb+"_"+i+"_"+j).innerHTML.trim()==KPIName){
	document.getElementById("chk"+atb+"_"+i+"_"+j).checked=true;
	selectBox("chk"+atb+"_"+i+"_"+j,'Selected'+ATB);
	document.getElementById("accordionchk"+atb+""+i).scrollHeight;
	}	
		j++;
	}
	j=0;
	i++;
		}
/*	var myDIV = document.getElementById('ListKpiDropdown');
	myDIV.scrollMaxX = myDIV.scrollHeight;
*/	}
// Change List of Exportdata
function changeList(id){
	$.post('exportdata_c',{'Work':'changeList','id':id},function(data)
		{
			
			$("#"+id).html(data);
		});	
	}
//ajax autocomplete search
$(function(){
	$(document).on("keyup","#company_name",function(d){
		var value = $(this).val();
		var table = $(this).attr("data-table");
		var attr = $(this).attr("data-attribute");
		$.post('exportdata_c',{'Work':'autoComplete','table':table,'attribute':attr,'value':value},function(data)
		{
			$("#company_name_container").show().html(data);
		});	
	});
	$(document).on("focusout","#company_name",function(d){	
		$("#company_name_container").hide();
	});
	$(document).on("mouseover","#company_name_container > li",function(d){
		var text = $(this).html();	
		$("#company_name").val(text);
	});	
	//KPI autocomplete
	$(document).on("keyup","#KPIname",function(d){
		var value = $(this).val();
		var table = $(this).attr("data-table");
		var attr = $(this).attr("data-attribute");
		$.post('exportdata_c',{'Work':'autoComplete','table':table,'attribute':attr,'value':value},function(data)
		{
			$("#KPIname_container").show().html(data);
		});	
	});
	$(document).on("focusout","#KPIname",function(d){	
		$("#KPIname_container").hide();
	});
	$(document).on("mouseover","#KPIname_container > li",function(d){
		var text = $(this).html();	
		$("#KPIname").val(text);
	});

	ListOfDropdown('company','ListOfDropdown');
	ListOfDropdown('kpi','ListKpiDropdown');
	range();
	});
