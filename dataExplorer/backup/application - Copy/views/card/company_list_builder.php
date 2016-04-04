<style>
    li{list-style: none}
</style>
<div id="company_list_builder" class="modal fade">
    <div class="modal-dialog">
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
                                    <div class="col-md-10 no-padding-right"><input id="search_company" name="company_name2" for="companies2" 
                                                                                   data-autocomplete="companies" data-label="company_name" data-value="entity_id"  
                                                                                   data-action="<?php echo site_url("/autocomplete/") ?>" class="form-control autocomplete"  type="text" placeholder="Search Companies"/>
                                    </div>


                                    <input name="company_value" for="companies" type="hidden"/>											
                                    <ul id="company_name_container" class="form-control autocomplete_c"></ul>
                                </div>	
                                <div class="col-md-4">	
                                    <input class="btn btn-block custom-btn-blue" id="btn_go_company" value="Go to Peer Group" type="button">	
                                </div>
                            </div>	
                            <div class="row custom-form-row-blue custom-form-control-blue">
                                <label for="" class="col-md-2 control-label" style="">Sector:</label>
                                <div class="col-md-10">
                                  <select id="sectors_name" class="form-control">
                     <?php foreach($sector_list as $sec){
                            echo '<option value="'.$sec->sector.'">'.$sec->sector.'</option>';
                     }?>
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
                                                    <label for="rdSelected">Selected</label>	
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
                            <div>
                                <ul id="companies_tree" style="height:500px">

                                </ul> 
                                <!--<a class="clear_all" id="uncheck_all_companies" for="c1" href="#">Clear All</a>-->
                            </div>
                            <!--                            <div class="row custom-form-row-blue blue-border" id="ListOfDropdown" style="height:500px;overflow:auto;">
                                                        </div>-->


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
                                            <!--<input type="text" class="form-control blue-form-control" id="compListName" name="compListName" placeholder="Enter List Name" >-->
                                            <input name="company_name2" for="companies2" 
                                                   data-autocomplete="companies" data-label="company_name" data-value="entity_id"  
                                                   data-action="<?php echo site_url("/autocomplete/") ?>" class="form-control autocomplete"  type="text" placeholder="Search Companies">
                                            <input name="company_value2" for="companies2" type="hidden"/>
                                        </div>	
                                    </div>	
                                </div>
                                <div class="col-md-12">
                                    <div class="row blue-custom-row">
                                        <div >


                                            <select id="SelectedComp" name="companies2" multiple >
                                            </select>


                                            <div id="txtSelectedComp"></div>

                                        </div>
                                    </div>
                                </div>	

                                <div class="col-md-12 blue-green-padd-2">
                                    <div class="row">
                                        <div class="col-md-6 blue-custom-col-1">	
                                            <a class="btn btn-block custom-btn-blue clear_all" for="c1" id="empty_companies_list" href="javascript:">Clear All</a>
                                            <!--<a class="select_all p-l-25" id="delete_companies" href="javascript:">Delete</a>-->
                                                                        <!--<input class="btn btn-block custom-btn-blue" id="delete_selected_companies"  value="Remove Companies" type="button">-->
                                           
                                            <div class="companies_list_name name_container">
                                              <input name="list_name" clear="c1" type="text" class="form-control" required="required"  placeholder="Type name to save the list of companies"/>
                                     <!--<input type="button" class="btn btn-block custom-btn-blue" value="Save List" onclick="model_confirmation('SelectedComp')" />-->
                                                <button class="btn btn-success btn-cons save_list left" for="companies2" obj="companies" type="button" style="margin-bottom:0;">Save List</button>
                                            </div>

                                            <div class="clearfix">&nbsp;</div>
                                        </div>
                                        <div class="col-md-6 blue-custom-col-1">	
                                            <div class="form-group blue-form-group-1 blue-green-padd-3">
                                                <div class="checkbox-inline check-info">
                                                    <?php
                                                    if ($user->is_root == 1) {
                                                        ?>
                                                            <!--<input type="checkbox" id="CompStatus" name="CompStatus" >-->
                                                        <!--<label for="CompStatus">Available to Everyone</label>-->
                                                        <input type="checkbox" value="1" id="companies_public_list">
                                                        <label for="companies_public_list">Available for Everyone?</label>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>	
                                        </div>
                                         <span style="clear:both; display:block;">Or</span> 
              <button class="btn btn-success btn-cons use_list" for="companies2" obj="companies" type="button">Use List Without Saving</button>
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