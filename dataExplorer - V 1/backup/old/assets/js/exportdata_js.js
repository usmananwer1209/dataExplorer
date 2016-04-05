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
	var i=1;
	var chkdvalue		 = [];
	var filter			= [];
	if(table=='company'){
	filter.push(document.getElementById('company_name').value);
	filter.push(document.getElementById('sectorList').value);
	filter.push(document.getElementById('industryList').value);
	filter.push(document.getElementById('sicList').value);
	while(document.getElementById("chkAmt"+i)){
		if(document.getElementById("chkAmt"+i).checked==true)
		chkdvalue.push(document.getElementById("chkAmt"+i).value);
		i++;}}
	else if(table=='kpi'){
		filter.push(document.getElementById('KPIname').value);
		if(document.getElementById('decision_category').checked==true)
		rdGroup= 'decision_category';
		if(document.getElementById('financial_category').checked==true)
		rdGroup= 'financial_category';
	}
	$.post('exportdata_c',{'Work':'ListOfDropdown','table':table,'chkdvalue':chkdvalue,'filter':filter,'rdGroup':rdGroup},function(data){	
	if(data!='')document.getElementById(divid).innerHTML=data;
	else alert('Data Not Avalaible!');}
);
	}
// Select All/Selected
function SelectAllSelected(){
	var i=1;
while(document.getElementById("chkAmt"+i)){//alert(document.getElementById("chkAmt"+i));
	document.getElementById("chkAmt"+i).checked=false;
	i++;
	}}
// Select/Unselect companies by group
function selectBox(Id,selectId){
	var Id_p = Id.split("_");
	var arrSize = Id_p.length;
	var chkStatus = document.getElementById(Id).checked;
// If Main Category Called and checked
	if(arrSize==2 && chkStatus==true)
	{   	var i=0;
		while(document.getElementById(Id+"_"+i)){
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
	document.getElementById('txt'+selectId).innerHTML+"<input type='hidden' id='txt"+document.getElementById(Id+"_"+i).value+"' value='"+Id+"_"+i+"'>";
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
	}
else{
	var ListName = 'compListName';
	var table    = 'list_companies';
	var status   = 'CompStatus';
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
	{ alert(data); });	}
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
  var CompListName	= document.getElementById('SelectedComp');
  var KpiListName	 = document.getElementById('SelectedKpi');
  var Anual		   = 0;
  if(document.getElementById('Anual').checked==true)	{Anual		   = 1;}
  var Quarterly	   = 0;
  if(document.getElementById('Quarterly').checked==true){Quarterly		   = 1;}
  var valuesComp	  = [];
  var valuesKpi	   = [];
 
  for (var i=0; i<CompListName.length; i++){
		valuesComp[i] =CompListName.options[i].value;}
  for (var i=0; i<KpiListName.length; i++){
		valuesKpi[i]  =KpiListName.options[i].value;}
 if(valuesComp.length <=0 && valuesKpi.length <=0){alert('Companies or KPIs not selected.'); return false;}
$.post('exportdata_c',{'Work':'getTable','valuesComp':valuesComp,'valuesKpi':valuesKpi,'Anual':Anual,'Quarterly':Quarterly,'Range':Range},
	function(data)
	{ document.getElementById('DataTable').innerHTML=data; });	}
// CSV
function getCSV(){
	var table = document.getElementById('DataTable').innerHTML;
	
	var data      = table.replace(/,/g,'')
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
						
		var mylink = document.createElement('a');
		mylink.download = 'testing.csv';
		mylink.href	 = "data:application/csv," + escape(data);
		mylink.click();
						 	}
