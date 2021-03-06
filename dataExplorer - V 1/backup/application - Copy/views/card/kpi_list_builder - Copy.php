
<div id="kpi_list_builder" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">KPI List Builder for <?php echo $kpi_title;?></h4>
      </div>
      <div class="modal-body grid simple">
        <div class="grid-body no-border">
          <div class="row">
            <div id="selects-containe" class="col-md-12 col-sm-12 p-l-25">
              <div class="kpis_by_sector">
                <div class="title">
                  KPI's
                </div>
                <div>

<div class="tabbable tabs-left">
    <ul id="tab-2" class="nav nav-tabs">
      <li class="active">
        <a href="#" id="decision_category">Decision Category</a>
      </li>
      <li class="">
        <a href="#" id="financial_category">Financial Statement</a>
      </li>
    </ul>
    <div class="tab-content">                
                  <ul id="kpis_tree">
                    <?php 
                      foreach ($desicion_cats as $k => $d_c) {
                        echo '<li class="row-fluid first_lvl">
                                <div class="tree_element checkbox check-danger">
                                  <a href="#" class="expand"><i class="fa fa-plus-square"></i></a>
                                  <input id="cat_'.$k.'" type="checkbox" class="cat_checkbox" value="'.$d_c->decision_category.'" id="'.$d_c->decision_category.'">
                                  <label for="cat_'.$k.'">'.$d_c->decision_category.'</label>
                                </div>';
                          echo '<ul class="sec_lvl collapsed">';
                                foreach ($d_c->kpis as $j => $kpi) {
                                  echo '<li class="row-fluid">
                                          <div class="tree_element checkbox check-default kpi">
                                            <input id="kpi_'.$kpi->term_id.'" type="checkbox" class="kpi_checkbox" value="'.$kpi->term_id.'" data-desc="'.$kpi->description.'">
                                            <label for="kpi_'.$kpi->term_id.'">'.$kpi->name.'</label>
                                          </div>
                                        </li>';
                                }
                          echo '</ul>';
                        echo '</li>';
                      }
                    ?>
                  </ul>
     </div>
  </div>                 
                  
                  <a class="clear_all  pull-right" id="uncheck_all_kpis" href="javascript:">Clear All</a>
                </div>
              </div>
              <div class="add_kpi">
                <div class="title">
                  Select KPI to include in <?php echo $kpi_title;?>
                </div>
                <div>
                  <input name="kpi_name2" for="kpis2" 
                        data-autocomplete="kpis"  data-label="name" data-value="term_id"  
                        data-action="<?php echo site_url("/autocomplete/") ?>" class="form-control autocomplete"  type="text" placeholder="Search kpis">
                  <input name="kpi_value2" for="kpis2" type="hidden"/>
                  <a class="add_to_select" for="kpis2" href="javascript:">+add</a>
                </div>
                <div>
                  <select name="kpis2" clear="c2" multiple >
                  </select>
                </div>
                <a class="clear_all" id="empty_kpis_list" href="javascript:">Clear All</a>
                <a class="select_all p-l-25" id="delete_kpis" href="javascript:">Delete</a>
                <a class="move_down p-l-25" id="move_kpi" direction="down" href="javascript:">Move Down</a>
                <a class="move_up p-l-25" id="move_kpi" direction="up" href="javascript:">Move Up</a>
              </div>

              <div id="builder_kpi_description" style="min-height:50px; background:#E3EBF5; border-radius:8px; padding:8px 10px;">
              </div>
			
              <div class="kpis_list_name name_container">
                <input id="kpis_last_name" name="list_name" clear="c2" type="text" class="form-control" required="required"  placeholder="Type name to save the <?php echo $kpi_title;?>"/>
              </div>
              <?php if($kpi_title == 'Template'){?>
              <div class="kpis_list_name name_container">
              <textarea class="form-control" name="list_description" id="list_description" required="required" placeholder="Type description to save the <?php echo $kpi_title;?>"></textarea>
              <input type="hidden" id="template_id">
              </div>
				  <?php if(count($user_company) > 0 && !$user->is_root){?>
                  <div class="kpis_list_name company_name_container">
                      <div class="col-md-2"><b>Company</b></div>
                      <div class="col-md-6">
                            <select name="company_name" id="company_name">
                                <?php foreach($user_company as $compan){?>
                                            <option value="<?php echo $compan->company?>"><?php echo $compan->company_name?></option>
                                <?php }?>
                            </select>
                      </div>
                      <div class="col-md-5"></div>
                  </div>
                  <?php }?>
              <?php }?>
              <div class="clear5"></div>
              <br/>

              <button class="btn btn-success btn-cons save_list left" for="kpis2"obj="kpis"type="button" style="margin-bottom:0;"><?php if($kpi_title == 'Card'){echo 'Save List in Card'; }else{ echo 'Save Template';}?></button>
              <?php if($user->is_root) { ?>
              <div class="checkbox check-default left" style="padding:8px 0 0 10px;">
                <input type="checkbox" value="1" id="kpis_public_list">
                <label for="kpis_public_list">Available for Everyone?</label>
              </div>
              <?php } ?>
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

