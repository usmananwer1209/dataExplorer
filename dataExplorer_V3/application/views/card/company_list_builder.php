<div id="company_list_builder" class="modal fade">
  <div class="modal-dialog" style="width:80%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Company List Builder</h4>
      </div>
      <div class="modal-body grid simple">
        <div class="grid-body no-border">
	<div class="row">
									<div class="col-md-8">	
										<div class="row custom-form-row-blue custom-form-control-blue">
											<label class="col-md-2 custom-blue-label" for="">Company:</label>
											<div class="col-md-6" style="position:relative;">
 <input  name="company_name" id="company_name" for="companies" data-table="company" data-attribute="company_name" 
                data-autocomplete="companies" data-label="company_name" data-value="entity_id"  
                class="form-control autocomplete autocomplete_comp"  type="text" placeholder="Search Companies" />  
         
                
          <input name="company_value" for="companies" type="hidden"/>											
          <ul id="company_name_container" class="form-control autocomplete_c"></ul>
          </div>	
								<div class="col-md-4">	
                                               <input class="btn btn-block custom-btn-blue" onclick="ListOfDropdown('company','ListOfDropdown');"  value="Go to Peer Group" type="button">	
											</div>
										</div>	
<div class="row custom-form-row-blue custom-form-control-blue">
    <label for="" class="col-md-2 control-label" style="">Sector:</label>
    <div class="col-md-10">
        <select class="form-control custom-form-control" name="sectorList" id="sectorList" onchange="selectlist('SectorList');ListOfDropdown('company','ListOfDropdown',1)">
<option value="">All</option>
<?php
$get_sector = $this->db->group_by("sector")->get('company')->result();
foreach ($get_sector as $sector) {
	?>
    <option value="<?=$sector->sector?>"><?=$sector->sector?></option>
    <?php
	}
?>        
        </select>
    </div>
</div>	
										<div class="row custom-form-row-blue custom-form-control-blue">
											<label for="" class="col-md-2 control-label" style="">Industry:</label>
											<div class="col-md-10">
												<select class="form-control custom-form-control" name="industryList" id="industryList" onchange="selectlist('IndustryList');ListOfDropdown('company','ListOfDropdown',1)">
<option value="">All</option>
<?php
$get_sector = $this->db->group_by("industry")->get('company')->result();
foreach ($get_sector as $sector) {
	?>
    <option value="<?=$sector->industry?>"><?=$sector->industry?></option>
    <?php
	}
?>        
												</select>
											</div>
										</div>	
										<div class="row custom-form-row-blue custom-form-control-blue">
											<label for="" class="col-md-2 control-label" style="">SIC:</label>
											<div class="col-md-10">
												<select class="form-control custom-form-control" name="sicList" id="sicList" onchange="selectlist('SICList');ListOfDropdown('company','ListOfDropdown',1)">
<option value="">All</option>
<?php
$get_sector = $this->db->group_by("sic")->get('company')->result();
foreach ($get_sector as $sector) {
	?>
    <option value="<?=$sector->sic?>"><?=$sector->sic?></option>
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
																<input class="checkboxx" type="checkbox" id="chkAmt1" name="chkAmt1" value="Between1And10M" onchange="selectLimts();ListOfDropdown('company','ListOfDropdown',1)">
																<label for="chkAmt1"> <10M </label>	
															</div>
														</div>	
														<div class="custom-checkbox-col">
															<div class="checkbox custom-checkbox-blue" >
																<input class="checkboxx" type="checkbox" id="chkAmt2" name="chkAmt2" value="Between10MAnd50M" onchange="selectLimts();ListOfDropdown('company','ListOfDropdown',1)" >
																<label for="chkAmt2"> 10-50M </label>	
															</div>
														</div>
														<div class="custom-checkbox-col">
															<div class="checkbox custom-checkbox-blue" >
																<input class="checkboxx" type="checkbox" id="chkAmt3" name="chkAmt3" value="Between50MAnd100M" onchange="selectLimts();ListOfDropdown('company','ListOfDropdown',1)" >
																<label for="chkAmt3"> 50-100M </label>	
															</div>
														</div>	
														<div class="custom-checkbox-col">
															<div class="checkbox custom-checkbox-blue" >
																<input class="checkboxx" type="checkbox" id="chkAmt4" name="chkAmt4" value="Between100MAnd200M" onchange="selectLimts();ListOfDropdown('company','ListOfDropdown',1)" >
																<label for="chkAmt4"> 100-200M </label>	
															</div>
														</div>
														<div class="custom-checkbox-col">
															<div class="checkbox custom-checkbox-blue" >
																<input class="checkboxx" type="checkbox" id="chkAmt5" name="chkAmt5" value="Between200MAnd500M" onchange="selectLimts();ListOfDropdown('company','ListOfDropdown',1)" >
																<label for="chkAmt5"> 200-500M </label>	
															</div>
														</div>	
														<div class="custom-checkbox-col">
															<div class="checkbox custom-checkbox-blue" >
																<input class="checkboxx" type="checkbox" id="chkAmt6" name="chkAmt6" value="Between500MAnd1B" onchange="selectLimts();ListOfDropdown('company','ListOfDropdown',1)" >
																<label for="chkAmt6"> 500M-1B </label>	
															</div>
														</div>		
														<div class="custom-checkbox-col">
															<div class="checkbox custom-checkbox-blue" >
																<input class="checkboxx" type="checkbox" id="chkAmt7" name="chkAmt7" value="Between1BAnd5B" onchange="selectLimts();ListOfDropdown('company','ListOfDropdown',1)" >
																<label for="chkAmt7"> 1-5B </label>	
															</div>
														</div>
														<div class="custom-checkbox-col">
															<div class="checkbox custom-checkbox-blue" >
																<input class="checkboxx" type="checkbox" id="chkAmt8" name="chkAmt8" value="Between5BAnd10B" onchange="selectLimts();ListOfDropdown('company','ListOfDropdown',1)" >
																<label for="chkAmt8"> 5-10B </label>	
															</div>
														</div>	
														<div class="custom-checkbox-col">
															<div class="checkbox custom-checkbox-blue" >
																<input class="checkboxx" type="checkbox" id="chkAmt9" name="chkAmt9" value="Between10Band50B" onchange="selectLimts();ListOfDropdown('company','ListOfDropdown',1)" >
																<label for="chkAmt9"> 10-50B </label>	
															</div>
														</div>
														<div class="custom-checkbox-col">
															<div class="checkbox custom-checkbox-blue" >
																<input class="checkboxx" type="checkbox" id="chkAmt10" name="chkAmt10" value="Between50BAnd100B" onchange="selectLimts();ListOfDropdown('company','ListOfDropdown',1)" >
																<label for="chkAmt10"> 50-100B </label>	
															</div>
														</div>	
														<div class="custom-checkbox-col">
															<div class="checkbox custom-checkbox-blue" >
																<input class="checkboxx" type="checkbox" id="chkAmt11" name="chkAmt11" value="GreaterThan100B" onchange="selectLimts();ListOfDropdown('company','ListOfDropdown',1);" >
																<label for="chkAmt11"> >100B </label>	
															</div>
														</div>		
													</div>	
												</div>
											</div>	
										</div>
<div class="row custom-form-row-blue blue-border" id="ListOfDropdown" style="height:255px;overflow:auto;">
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
														<input type="text" class="form-control blue-form-control savelistname" id="CompanyListName" name="compListName" placeholder="Enter List Name" >
													</div>	
												</div>	
											</div>
    <div class="col-md-12">
        <div class="row blue-custom-row">
            <div class="blue-custom-box blue-custom-multi-select">
 <select  multiple="multiple" name="companies2" id="SelectedComp" class="selComp">
                </select> <div id="txtSelectedComp"></div>
													
            </div>
        </div>
    </div>	
											
											<div class="col-md-12 blue-green-padd-2">
												<div class="row">
													<div class="col-md-6 blue-custom-col-1">	
														<input class="btn btn-block custom-btn-blue" id="delete_selected_companies"  value="Remove Companies" type="button">
															
												      <input type="button" class="btn btn-block custom-btn-blue save_company_list" value="Save List" for="companies2" obj="companies"  />
													<div class="clearfix">&nbsp;</div>
                                                    </div>
													<div class="col-md-6 blue-custom-col-1">	
														<div class="form-group blue-form-group-1 blue-green-padd-3">
															<div class="checkbox-inline check-info">
                                                            	<?php
                                                                	if($user->is_root){
																?>
                                                                        <input type="checkbox" id="CompStatus" name="CompStatus" >
                                                                        <label for="CompStatus">Available to Everyone</label>
                                                                <?php
                                                                	}
																?>
                                                                         <span style="clear:both; display:block;">Or</span> 
              <button class="btn btn-success btn-cons use_kpi_list" for="companies2" obj="companies" type="button">Use List Without Saving</button>
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