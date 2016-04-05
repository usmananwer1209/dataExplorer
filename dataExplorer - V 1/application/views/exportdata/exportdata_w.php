<?php
error_reporting(0);
// Ajax Call to get Lists with sector
if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='SectorList'){}
// Ajax Call to get Lists with sector
else if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='IndustryList'){}
// Ajax Call to get dropdown list 
else if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='ListOfDropdown'){
	echo $results;
}
// Ajax Call to get Detail
if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='getDetail'){}
// Ajax Call to Save List in DataBase
else if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='DataSave'){
echo $result;	
}
// Ajax Call to Show List 
else if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='ShowList'){}
// Ajax Call to Get Table
else if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='getTable'){
echo $results;
}
// Ajax range
else if(isset($_REQUEST['Work']) and $_REQUEST['Work']=='range'){
echo $results;
	
}

?>
