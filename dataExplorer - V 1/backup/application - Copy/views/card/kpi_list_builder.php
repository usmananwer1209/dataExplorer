
<div id="kpi_list_builder" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">KPI List Builder for <?php echo $kpi_title; ?></h4>
            </div>
            <div class="modal-body grid simple">
                <div class="grid-body no-border">
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

                            <div class="row custom-form-row-blue blue-border" id="kpis_tree" style="height:500px;overflow:auto;">

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
                                        <div class="blue-custom-box blue-custom-multi-select " id="kpidown">
                                                 <!--<select name="SelectedKpi" multiple="multiple" id="SelectedKpi">-->
                                            <select name="kpis2" clear="c2" multiple >
                                            </select>															</select> 
                                            <div id="txtSelectedKpi" ></div>
                                        </div>	
                                    </div>
                                </div>	

                                <div class="col-md-12">
                                    <div class="row blue-custom-padd">
                                        <div class="blue-custom-box-2 blue-custom-multi-select">
                                            <div id="builder_kpi_description" style="min-height:50px; background:#E3EBF5; border-radius:8px; padding:8px 10px;">
                                            </div>
                                        </div>	
                                    </div>
                                </div>	

                                <div class="col-md-12 blue-green-padd-2">
                                    <div class="row">





                                        <div class="col-md-6 blue-custom-col-1">	
<!--                                            <input  class="btn btn-block custom-btn-blue" type="button" id="delete_selected_kpis" value="Remove KPIs">	-->
                                             <div class="kpis_list_name name_container">
                <input id="kpis_last_name" name="list_name" clear="c2" type="text" class="form-control" required="required"  placeholder="Type name to save the <?php echo $kpi_title;?>"/>
              </div>
                                            <input type="button" class="btn btn-block custom-btn-blue save_list" for="kpis2" obj="kpis" value="<?php if ($kpi_title == 'Card') {
    echo 'Save List in Card';
} else {
    echo 'Save Template';
} ?>" >
                                            <div class="clearfix">&nbsp;</div>
                                        </div>
                                        <div class="col-md-6 blue-custom-col-1">	
                                            <div class="form-group blue-form-group-1 blue-green-padd-3">
                                                <div class="checkbox-inline check-info">
                                                    <?php
                                                    if ($user->is_root == 1) {
                                                        ?>
                                                        <input type="checkbox" value="1" id="kpis_public_list">
                                                        <label for="kpis_public_list">Available for Everyone?</label>
                                                <?php
                                            }
                                            ?>
                                                </div>
                                            </div>	
                                            <?php if ($kpi_title == 'Card') { ?>
                                                <div style="float:right;">
                                                    <span>Or</span>&nbsp; &nbsp; 
                                                    <button class="btn btn-block custom-btn-blue use_list" for="kpis2" obj="kpis" type="button">Use List Without Saving</button>
                                                </div>
<?php } ?>
                                        </div>	
                                    </div>
                                </div>	
                            </div>	

                        </div>	
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

