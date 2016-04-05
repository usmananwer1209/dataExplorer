
<div id="kpi_list_builder" class="modal fade">
  <div class="modal-dialog" style="width:80%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">KPI List Builder for <?php echo $kpi_title;?></h4>
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
															<input type="radio" id="decision_category" name="period1" value="decision_category" checked="checked" onclick="ListOfDropdownKpi('kpi','ListKpiDropdown')">
															<label for="decision_category">Group by Decision Category</label>
														</div>
														<div class="col-md-4">
															<input type="radio" id="financial_category" name="period1" 
                                                            value="financial_category" onclick="ListOfDropdownKpi('kpi','ListKpiDropdown')">
															<label for="financial_category">Group by Financial Statement</label>	
														</div>
														<div class="col-md-4">
															<input type="radio" id="flat_list" name="period1" value="flat_list" onclick="ListOfDropdownKpi('kpi','ListKpiDropdown')">
															<label for="flat_list">Flat List</label>	
														</div>	
													</div>
												</div>
											</div>	
										</div>
										
										<div class="row custom-form-row-blue blue-border" id="ListKpiDropdown" style="height:460px;overflow:auto;">
											
											 
										
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
													  <input type="text" class="form-control blue-form-control savelistname" id="KpiListName" name="list_name" placeholder="Enter List Name" value=""/>
													</div>	
												</div>	
											</div>
											
											<div class="col-md-12">
												<div class="row blue-custom-row-1 blue-custom-padd">
													<div class="blue-custom-box blue-custom-multi-select">
														 <select name="kpis2" multiple="multiple" id="SelectedKpi">
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
														<input type="button" class="btn btn-block custom-btn-blue save_kpi_list" for="kpis2"obj="kpis" value="Save List" 
                                                        <div class="clearfix">&nbsp;</div>
													</div>
													<div class="col-md-6 blue-custom-col-1">	
														<div class="form-group blue-form-group-1 blue-green-padd-3">
															<div class="checkbox-inline check-info">
																<?php
                                                                	if($user->is_root){
																?>
                                                                        <input type="checkbox" id="KpiStatus" name="KpiStatus" >
                                                                        <label for="KpiStatus">Available to Everyone</label>
                                                                 <?php
																	}
																 ?>
                                                                          <?php if($kpi_title == 'Card'){?>
              <div style="float:right;">
                <span>Or</span>&nbsp; &nbsp; 
                <button class="btn btn-success btn-cons use_list" for="kpis2" obj="kpis" type="button">Use List Without Saving</button>
              </div>
			<?php }?>
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
  </div>
</div>

