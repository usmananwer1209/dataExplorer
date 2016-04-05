<?php
$folder = dirname(dirname(__FILE__));
require_once $folder . "/commun/navbar.php";
?>

<div class="page-container row-fluid">
    <?php require_once $folder . "/commun/main-menu.php"; ?>

    <div class="page-content">
        <div class="clearfix"></div>

        <div class="content" style="padding-top:64px">

            <div class="row row-ida-new-page">	
                <div class="col-md-12">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs custom-nav-tabs-blue" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#companies" aria-controls="companies" role="tab" data-toggle="tab">
                                <i class="fa fa-building-o"></i>
                                Select Companies	
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#kpi" aria-controls="kpi" role="tab" data-toggle="tab">
                                <i class="fa fa-cog"></i>
                                Select KPIs
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#export" aria-controls="export" role="tab" data-toggle="tab">
                                <i class="fa fa-cloud-download"></i>
                                Export Data 
                            </a>
                        </li>
                    </ul>
                </div>	

                <div class="col-md-12 ">
                    <div class="tab-content custom-tab-content-new">
                        <!-- Select Companies (Tab 1) -->	
                        <div role="tabpanel" class="tab-pane fade in active" id="companies">
                            <div class="row">
                                <div class="col-md-8">	
                                    <div class="row custom-form-row-blue custom-form-control-blue">
                                        <label class="col-md-2 custom-blue-label" for="">Company:</label>
                                        <div class="col-md-6" style="position:relative;">
                                            <input name="company_name" id="company_name" for="companies" data-table="company" data-attribute="company_name" 
                                                   data-autocomplete="companies" data-label="company_name" data-value="entity_id"  
                                                   class="form-control autocomplete"  type="text" placeholder="Search Companies" />  


                                            <input name="company_value" for="companies" type="hidden"/>											
                                            <ul id="company_name_container" class="form-control autocomplete_c"></ul>
                                        </div>	
                                        <div class="col-md-4">	
                                            <input class="btn btn-block custom-btn-blue" onclick="ListOfDropdown('company', 'ListOfDropdown');"  value="Go to Peer Group" type="button">	
                                        </div>
                                    </div>	
                                    <div class="row custom-form-row-blue custom-form-control-blue">
                                        <label for="" class="col-md-2 control-label" style="">Sector:</label>
                                        <div class="col-md-10">
                                            <select  class="form-control custom-form-control" name="sectorList" id="sectorList" onchange="selectlist('SectorList');
                     ListOfDropdown('company', 'ListOfDropdown', 1)">
                                                <option value="">All</option>
                                                <?php
                                                foreach ($get_sector as $sector) {
                                                    ?>
                                                    <option value="<?= $sector->sector ?>"><?= $sector->sector ?></option>
                                                    <?php
                                                }
                                                ?>        
                                            </select>
                                        </div>
                                    </div>	
                                    <div class="row custom-form-row-blue custom-form-control-blue">
                                        <label for="" class="col-md-2 control-label" style="">Industry:</label>
                                        <div class="col-md-10">
                                            <select class="form-control custom-form-control" name="industryList" id="industryList" onchange="selectlist('IndustryList');
                                                                                                        ListOfDropdown('company', 'ListOfDropdown', 1)">
                                                <option value="">All</option>
                                                <?php
                                                $get_sector = $this->db->group_by("industry")->get('company')->result();
                                                foreach ($get_sector as $sector) {
                                                    ?>
                                                    <option value="<?= $sector->industry ?>"><?= $sector->industry ?></option>
                                                    <?php
                                                }
                                                ?>        
                                            </select>
                                        </div>
                                    </div>	
                                    <div class="row custom-form-row-blue custom-form-control-blue">
                                        <label for="" class="col-md-2 control-label" style="">SIC:</label>
                                        <div class="col-md-10">
                                            <select class="form-control custom-form-control" name="sicList" id="sicList" onchange="selectlist('SICList');
                                                                                                        ListOfDropdown('company', 'ListOfDropdown', 1)">
                                                <option value="">All</option>
                                                <?php
                                                $get_sector = $this->db->group_by("sic")->get('company')->result();
                                                foreach ($get_sector as $sector) {
                                                    ?>
                                                    <option value="<?= $sector->sic ?>"><?= $sector->sic ?></option>
                                                    <?php
                                                }
                                                ?>        
                                            </select>
                                        </div>
                                    </div>	
                                    <div class="row custom-form-row-blue custom-form-control-blue">
                                        <label for="" class="col-md-2 control-label" style="">Revenue:</label>
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="radio radio-success custom-radio-blue">
                                                        <div class="col-md-12 row">
                                                            <input type="radio" id="Instant" name="period" value="Instant" onclick="SelectAllSelected();" checked="checked">
                                                            <label for="Instant">All</label>
                                                        </div>
                                                        <div class="col-md-2 row">
                                                            <input type="radio" name="period" value="Duration"   id="rdSelected">
                                                            <label for="Duration">Selected</label>	
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-10 blue-custom-padd">	
                                                    <div class="custom-checkbox-col">
                                                        <div class="checkbox custom-checkbox-blue" >
                                                            <input class="checkboxx" type="checkbox" id="chkAmt1" name="chkAmt1" value="Between1And10M" onchange="selectLimts();
                                                                                                                                        ListOfDropdown('company', 'ListOfDropdown', 1)">
                                                            <label for="chkAmt1"> <10M </label>	
                                                        </div>
                                                    </div>	
                                                    <div class="custom-checkbox-col">
                                                        <div class="checkbox custom-checkbox-blue" >
                                                            <input class="checkboxx" type="checkbox" id="chkAmt2" name="chkAmt2" value="Between10MAnd50M" onchange="selectLimts();
                                                                                                                                        ListOfDropdown('company', 'ListOfDropdown', 1)" >
                                                            <label for="chkAmt2"> 10-50M </label>	
                                                        </div>
                                                    </div>
                                                    <div class="custom-checkbox-col">
                                                        <div class="checkbox custom-checkbox-blue" >
                                                            <input class="checkboxx" type="checkbox" id="chkAmt3" name="chkAmt3" value="Between50MAnd100M" onchange="selectLimts();
                                                                                                                                        ListOfDropdown('company', 'ListOfDropdown', 1)" >
                                                            <label for="chkAmt3"> 50-100M </label>	
                                                        </div>
                                                    </div>	
                                                    <div class="custom-checkbox-col">
                                                        <div class="checkbox custom-checkbox-blue" >
                                                            <input class="checkboxx" type="checkbox" id="chkAmt4" name="chkAmt4" value="Between100MAnd200M" onchange="selectLimts();
                                                                                                                                        ListOfDropdown('company', 'ListOfDropdown', 1)" >
                                                            <label for="chkAmt4"> 100-200M </label>	
                                                        </div>
                                                    </div>
                                                    <div class="custom-checkbox-col">
                                                        <div class="checkbox custom-checkbox-blue" >
                                                            <input class="checkboxx" type="checkbox" id="chkAmt5" name="chkAmt5" value="Between200MAnd500M" onchange="selectLimts();
                                                                                                                                        ListOfDropdown('company', 'ListOfDropdown', 1)" >
                                                            <label for="chkAmt5"> 200-500M </label>	
                                                        </div>
                                                    </div>	
                                                    <div class="custom-checkbox-col">
                                                        <div class="checkbox custom-checkbox-blue" >
                                                            <input class="checkboxx" type="checkbox" id="chkAmt6" name="chkAmt6" value="Between500MAnd1B" onchange="selectLimts();
                                                                                                                                        ListOfDropdown('company', 'ListOfDropdown', 1)" >
                                                            <label for="chkAmt6"> 500M-1B </label>	
                                                        </div>
                                                    </div>		
                                                    <div class="custom-checkbox-col">
                                                        <div class="checkbox custom-checkbox-blue" >
                                                            <input class="checkboxx" type="checkbox" id="chkAmt7" name="chkAmt7" value="Between1BAnd5B" onchange="selectLimts();
                                                                                                                                        ListOfDropdown('company', 'ListOfDropdown', 1)" >
                                                            <label for="chkAmt7"> 1-5B </label>	
                                                        </div>
                                                    </div>
                                                    <div class="custom-checkbox-col">
                                                        <div class="checkbox custom-checkbox-blue" >
                                                            <input class="checkboxx" type="checkbox" id="chkAmt8" name="chkAmt8" value="Between5BAnd10B" onchange="selectLimts();
                                                                                                                                        ListOfDropdown('company', 'ListOfDropdown', 1)" >
                                                            <label for="chkAmt8"> 5-10B </label>	
                                                        </div>
                                                    </div>	
                                                    <div class="custom-checkbox-col">
                                                        <div class="checkbox custom-checkbox-blue" >
                                                            <input class="checkboxx" type="checkbox" id="chkAmt9" name="chkAmt9" value="Between10Band50B" onchange="selectLimts();
                                                                                                                                        ListOfDropdown('company', 'ListOfDropdown', 1)" >
                                                            <label for="chkAmt9"> 10-50B </label>	
                                                        </div>
                                                    </div>
                                                    <div class="custom-checkbox-col">
                                                        <div class="checkbox custom-checkbox-blue" >
                                                            <input class="checkboxx" type="checkbox" id="chkAmt10" name="chkAmt10" value="Between50BAnd100B" onchange="selectLimts();
                                                                                                                                        ListOfDropdown('company', 'ListOfDropdown', 1)" >
                                                            <label for="chkAmt10"> 50-100B </label>	
                                                        </div>
                                                    </div>	
                                                    <div class="custom-checkbox-col">
                                                        <div class="checkbox custom-checkbox-blue" >
                                                            <input class="checkboxx" type="checkbox" id="chkAmt11" name="chkAmt11" value="GreaterThan100B" onchange="selectLimts();
                                                                                                                                        ListOfDropdown('company', 'ListOfDropdown', 1);" >
                                                            <label for="chkAmt11"> >100B </label>	
                                                        </div>
                                                    </div>		
                                                </div>	
                                            </div>
                                        </div>	
                                    </div>
                                    <div class="row custom-form-row-blue blue-border" id="ListOfDropdown" style="height:350px;overflow:auto;">
                                    </div>


                                </div>	
                                <div class="col-md-4">	
                                    <div class="row blue-green-padd-1">
                                        <h1 class="blue-right-hd-1">Selected Companies</h1>	
                                    </div>
                                    <div class="row blue-right-green-bg blue-green-padd-1">

                                        <div class="col-md-12">	
                                            <div class="row">
                                                <label style="" class="col-md-4 custom-control-label-blue" for="">List Name:</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control blue-form-control" id="compListName" name="compListName" placeholder="Enter List Name" >
                                                </div>	
                                            </div>	
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row blue-custom-row">
                                                <div class="blue-custom-box blue-custom-multi-select">
                                                    <select  multiple="multiple" name="SelectedComp" id="SelectedComp">
                                                    </select> <div id="txtSelectedComp"></div>

                                                </div>
                                            </div>
                                        </div>	

                                        <div class="col-md-12 blue-green-padd-2">
                                            <div class="row">
                                                <div class="col-md-6 blue-custom-col-1">	
                                                    <input class="btn btn-block custom-btn-blue" id="delete_selected_companies"  value="Remove Companies" type="button">

                                                    <input type="button" class="btn btn-block custom-btn-blue" value="Save List" onclick="model_confirmation('SelectedComp')" />
                                                    <div class="clearfix">&nbsp;</div>
                                                </div>
                                                <div class="col-md-6 blue-custom-col-1">	
                                                    <div class="form-group blue-form-group-1 blue-green-padd-3">
                                                        <div class="checkbox-inline check-info">
                                                            <?php
                                                            if ($user_is_root == 1) {
                                                                ?>
                                                                <input type="checkbox" id="CompStatus" name="CompStatus" >
                                                                <label for="CompStatus">Available to Everyone</label>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>	
                                                </div>	
                                            </div>
                                        </div>	
                                    </div>	

                                </div>	
                            </div>



                        </div>			

                        <!-- Select KPIs (Tab 2) -->
                        <div role="tabpanel" class="tab-pane fade" id="kpi">
                            <div class="row">
                                <div class="col-md-8">	
                                    <div class="row custom-form-row-blue">
                                        <label class="col-md-2 custom-blue-label" for="">KPI:</label>
                                        <div class="col-md-6" style="position:relative">
                                            <input type="text" placeholder="" id="KPIname" name="KPIname" data-table="kpi" data-attribute="name" class="form-control">
                                            <ul id="KPIname_container" class="form-control autocomplete_c"></ul>
                                        </div>	
                                        <div class="col-md-4">
                                            <input type="button" class="btn btn-block custom-btn-blue"  value="Show in Group" onclick="SelectCompKPI('KPIname');">	
                                        </div>
                                    </div>	
                                    <div class="row custom-form-row-blue custom-form-control-blue custom-form-control-3">
                                        <div class="col-md-12">
                                            <div class="radio radio-success">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <input type="radio" id="decision_category" name="period1" value="decision_category" checked="checked" onclick="ListOfDropdownKpi('kpi', 'ListKpiDropdown')">
                                                        <label for="decision_category">Group by Decision Category</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="radio" id="financial_category" name="period1" 
                                                               value="financial_category" onclick="ListOfDropdownKpi('kpi', 'ListKpiDropdown')">
                                                        <label for="financial_category">Group by Financial Statement</label>	
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="radio" id="flat_list" name="period1" value="flat_list" onclick="ListOfDropdownKpi('kpi', 'ListKpiDropdown')">
                                                        <label for="flat_list">Flat List</label>	
                                                    </div>	
                                                </div>
                                            </div>
                                        </div>	
                                    </div>

                                    <div class="row custom-form-row-blue blue-border" id="ListKpiDropdown" style="height:500px;overflow:auto;">



                                    </div>	

                                </div>	
                                <div class="col-md-4">	
                                    <div class="row blue-green-padd-1" >
                                        <h1 class="blue-right-hd-1">Selected KPIs</h1>	
                                    </div>
                                    <div class="row blue-right-green-bg blue-green-padd-1">

                                        <div class="col-md-12">	
                                            <div class="row">
                                                <label style="" class="col-md-4 control-label" for="">List Name:</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control blue-form-control" id="KpiListName" name="KpiListName" placeholder="Enter List Name" />
                                                </div>	
                                            </div>	
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row blue-custom-row-1 blue-custom-padd">
                                                <div class="blue-custom-box blue-custom-multi-select">
                                                    <select name="SelectedKpi" multiple="multiple" id="SelectedKpi">
                                                    </select> 
                                                    <div id="txtSelectedKpi" ></div>
                                                </div>	
                                            </div>
                                        </div>	

                                        <div class="col-md-12">
                                            <div class="row blue-custom-padd">
                                                <div class="blue-custom-box-2 blue-custom-multi-select">
                                                    <p id="getDetail">
                                                    </p>
                                                </div>	
                                            </div>
                                        </div>	

                                        <div class="col-md-12 blue-green-padd-2">
                                            <div class="row">
                                                <div class="col-md-6 blue-custom-col-1">	
                                                    <input  class="btn btn-block custom-btn-blue" type="button" id="delete_selected_kpis" value="Remove KPIs">	
                                                    <input type="button" class="btn btn-block custom-btn-blue" value="Save List" onclick="model_confirmation('SelectedKpi')">
                                                    <div class="clearfix">&nbsp;</div>
                                                </div>
                                                <div class="col-md-6 blue-custom-col-1">	
                                                    <div class="form-group blue-form-group-1 blue-green-padd-3">
                                                        <div class="checkbox-inline check-info">
                                                            <?php
                                                            if ($user_is_root == 1) {
                                                                ?>
                                                                <input type="checkbox" id="KpiStatus" name="KpiStatus" >
                                                                <label for="KpiStatus">Available to Everyone</label>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>	
                                                </div>	
                                            </div>
                                        </div>	
                                    </div>	

                                </div>	
                            </div>
                        </div>

                        <!-- Export Data (Tab 3) -->
                        <div role="tabpanel" class="tab-pane fade" id="export">
                            <div class="row">
                                <div class="col-md-9">	
                                    <div class="row custom-form-row-blue custom-form-control-blue">
                                        <label style="padding-top:5px;" class="col-md-3 control-label" for="">Company List Name:</label>
                                        <div class="col-md-9">
                                            <select class="form-control custom-form-control" name="CompNameList" id="CompNameList" onchange="ShowList('CompNameList')">
                                                <option value="">Select List</option>
                                                <?php
                                                foreach ($getcompanieslist as $company) {
                                                    ?>
                                                    <option value="<?= $company->id ?>"><?= $company->name ?></option>
                                                    <?php
                                                }
                                                ?>        							
                                            </select>

                                        </div>
                                    </div>	
                                    <div class="row custom-form-row-blue custom-form-control-blue">
                                        <label style="padding-top:5px;" class="col-md-3 control-label" for="">KPI List Name:</label>
                                        <div class="col-md-9">
                                            <select class="form-control custom-form-control" name="KpiNameList" id="KpiNameList" onchange="ShowList('KpiNameList')">
                                                <option value="">Select List</option>

                                                <?php
                                                foreach ($list_kpis as $kpi) {
                                                    ?>
                                                    <option value="<?= $kpi->id ?>"><?= $kpi->name ?></option>
                                                    <?php
                                                }
                                                ?>        
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row custom-form-row-blue custom-form-control-blue">
                                        <label style="padding-top:5px;" class="col-md-3 control-label" for="">Reporting Periods:</label>
                                        <div class="col-md-3">
                                            <div class="checkbox custom-checkbox-export">
                                                <input name="Anual" type="checkbox" id="Anual" value="Anual" checked="checked">
                                                <label for="Anual"> Annual </label>	
                                            </div>
                                            <div class="checkbox custom-checkbox-export">
                                                <input type="checkbox" value="Quarterly" name="Quarterly" id="Quarterly">
                                                <label for="Quarterly"> Quarterly </label>	
                                            </div>	
                                        </div>
                                        <div class="col-md-6">

                                            <input type="text" id="range_04" name="example_name" value="" />
                                        </div>	
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <lable class="cutom-green-btn">Companies Selected: <span id="CountComp" name="CountComp">0</span></lable>
                                    <lable class="cutom-green-btn">KPIs Selected: <span id="CountKpi" name="CountKpi">0</span></lable>
                                </div>
                            </div>

                            <div class="row custom-row-black">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-7 ">
                                            <p>Data Preview (<span id="showrecords">0</span> of <span id="datatablecount">0</span> Records)</p>	
                                        </div>	
                                        <div class="col-md-5">
                                            <input type="button" class="btn custom-sm-navy pull-left" onclick="getTable();" value="Refresh Data">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="" class="col-md-3">Set Name:</label>	
                                        <div class="col-md-6">
                                            <input type="text " class="form-control black-form-control"  placeholder="CSV Name here" name="CSVName" id="CSVName">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="button" class="btn custom-sm-navy custom-pull-right-1" onclick="getCSV();" id="" value="Export CSV">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-md-12">
                                    <div class="table-responsive dataexport_table" style="width:100%;overflow:auto;height:405px;" id="tabledata">
                                        <?php
// require_once $folder.'/explore/container.php';
                                        ?>
                                        <table class="table table-striped table-bordered table-hover display" id="DataTable"  style="overflow:scroll">

                                            <tbody>
                                                <tr>
                                                    <td id="Dataloading">No Record(s) Found.</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                    <div id="hiddentable" style="display:none"></div>
                                </div>		
                            </div>	
                            <span id="apiurl"></span>
                        </div>	

                    </div>	


                </div>	

            </div>	






        </div>



    </div>




    <div class="modal fade notif_modals" id="successfull_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Your Changes have been Successfully Submitted.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">close</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade notif_modals" id="confirmation_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to save changes to the list?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger removeSlide">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade notif_modals" id="error_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Operation failed!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">close</button>
                </div>
            </div>
        </div>
    </div>
