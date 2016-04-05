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
            <div class="col-md-8 col-sm-8 left">
            	<div class="col-md-1"><label for="search_company" class="search-labels">Search</label></div><div class="col-md-10 no-padding-right"><input id="search_company" name="company_name2" for="companies2" 
                        data-autocomplete="companies" data-label="company_name" data-value="entity_id"  
                        data-action="<?php echo site_url("/autocomplete/") ?>" class="form-control autocomplete"  type="text" placeholder="Search Companies"/></div><div class="col-md-1 no-padding-left"><button class="btn btn-go" id="btn_go_company">Go</button></div>
            	<div class="clear10"></div>
            	<div class="col-md-1"><label for="sectors_name" class="search-labels">Sector</label></div><div class="col-md-11 ">
                    <div class="styled-select">
                    <select id="sectors_name" class="form-control">
                     <?php foreach($sector_list as $sec){
                            echo '<option value="'.$sec->sector.'">'.$sec->sector.'</option>';
                     }?>
                    </select>
                    </div>
                </div>
                <div class="clear10"></div>
                <div class="col-md-2"><label for="revenuse" class="search-labels">Revenues</label></div>
                    <div class="col-md-10 revenue-boxes">
									<div class="radio radio-default">
                                        <input type="radio" value="all" id="search_for_revenue_all" name="search_for_revenue" checked="checked">
                                        <label for="search_for_revenue_all">All</label>
                                    </div>
                                    <div class="col-md-2 no-padding">
                                        <div class="radio radio-default">
                                            <input type="radio" value="selected" id="search_for_revenue_selected" name="search_for_revenue">
                                            <label for="search_for_revenue_selected">Selected</label>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                    	<div class="checkbox checkbox-default left">
                                            <input type="checkbox" value="Between1And10M" id="less10m" name="search_revenue">
                                            <label for="less10m">< $10M</label>
                                        </div>
                                        <div class="checkbox checkbox-default left">
                                            <input type="checkbox" value="Between10MAnd50M" id="10mto50" name="search_revenue">
                                            <label for="10mto50">$10-50 M</label>
                                        </div>
                                        <div class="checkbox checkbox-default left">
                                            <input type="checkbox" value="Between50MAnd100M" id="50mto100" name="search_revenue">
                                            <label for="50mto100">$50-100 M</label>
                                        </div>
                                        <div class="checkbox checkbox-default left">
                                            <input type="checkbox" value="Between100MAnd200M" id="100mto200" name="search_revenue">
                                            <label for="100mto200">$100-200 M</label>
                                        </div>
                                        <div class="checkbox checkbox-default left">
                                            <input type="checkbox" value="Between200MAnd500M" id="200mto500" name="search_revenue">
                                            <label for="200mto500">$200-500 M</label>
                                        </div>
                                        <div class="checkbox checkbox-default left">
                                            <input type="checkbox" value="Between500MAnd1B" id="500mto1000" name="search_revenue">
                                            <label for="500mto1000">$500M-1B</label>
                                        </div>
                                        <div class="checkbox checkbox-default left">
                                            <input type="checkbox" value="Between1BAnd5B" id="1bto5" name="search_revenue">
                                            <label for="1bto5">$1-5 B</label>
                                        </div>
                                        <div class="checkbox checkbox-default left">
                                            <input type="checkbox" value="Between5BAnd10B" id="5bto10" name="search_revenue">
                                            <label for="5bto10">$5-10 B</label>
                                        </div>
                                        <div class="checkbox checkbox-default left">
                                            <input type="checkbox" value="Between10Band50B" id="10bto50" name="search_revenue">
                                            <label for="10bto50">$10-50 B</label>
                                        </div>
                                        <div class="checkbox checkbox-default left">
                                            <input type="checkbox" value="Between50BAnd100B" id="50bto100" name="search_revenue">
                                            <label for="50bto100">$50-100 B</label>
                                        </div>
                                        <div class="checkbox checkbox-default left">
                                            <input type="checkbox" value="GreaterThan100B" id="great100b" name="search_revenue">
                                            <label for="great100b">> $100B</label>
                                        </div>
                                    </div>
                    </div>
                <div class="clear10"></div>
                <div id="treemap" class="col-md-12 no-padding text-center"></div>
            </div>
            <div id="container_form" class="col-md-4 col-sm-4 right p-l-25">
              <div class="companies_by_sector">
                <div class="title">
                  Companies - Sector/Industry
                </div>
                <div>
                  <ul id="companies_tree">
                    
                  </ul> 
                  <a class="clear_all" id="uncheck_all_companies" for="c1" href="#">Clear All</a>
                </div>
              </div>
              <div class="add_company">
                <div class="title">
                  Select Companies
                </div>
                <div>
                  <input name="company_name2" for="companies2" 
                        data-autocomplete="companies" data-label="company_name" data-value="entity_id"  
                        data-action="<?php echo site_url("/autocomplete/") ?>" class="form-control autocomplete"  type="text" placeholder="Search Companies">
                  <input name="company_value2" for="companies2" type="hidden"/>
                  <a class="add_to_select" for="companies2" href="javascript:">+add</a>

                </div>
                <div>
                  <select name="companies2" multiple >
                  </select>
                </div>


                <a class="clear_all" for="c1" id="empty_companies_list" href="javascript:">Clear All</a>
                <a class="select_all p-l-25" id="delete_companies" href="javascript:">Delete</a>
              </div>
              <div class="companies_list_name name_container">
                <input name="list_name" clear="c1" type="text" class="form-control" required="required"  placeholder="Type name to save the list of companies"/>
              </div>
              <br/>
              <button class="btn btn-success btn-cons save_list left" for="companies2" obj="companies" type="button" style="margin-bottom:0;">Save List</button>
              <?php if($user->is_root) { ?>
              <div class="checkbox check-default left" style="padding:8px 0 0 10px;">
                <input type="checkbox" value="1" id="companies_public_list">
                <label for="companies_public_list">Available for Everyone?</label>
              </div>
              <?php } ?>
              <span style="clear:both; display:block;">Or</span> 
              <button class="btn btn-success btn-cons use_list" for="companies2" obj="companies" type="button">Use List Without Saving</button>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>