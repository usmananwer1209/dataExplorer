<?php
if ($op == "edit_") {
	$id = $obj -> id;
	$name = $obj->title;
	$description = $obj->description;
	$template = $obj->template;
	$created_by  = $obj->created_by;
	$user_id = $obj->user;
	$company = $obj->company;
	$template_name = $obj->template_name;
	$terms  = $obj->terms;
	$year_first = $kpis[0]->year;
} else {
	$id = '';
	$name = '';
	$description = '';
	$template = 0;
	$created_by  = $user->first_name.' '.$user->last_name;
	$user_id = $user->id;
	$year_first = 0;
	$company = 0;
}
?>
	<div class="clearfix"></div>
  	<div class="content">
  		<ul class="breadcrumb">
  			<li>
  				<a href="<?php echo site_url('/home'); ?>">HOME</a>
  			</li>
  			<li><a href="#" class="active">Data Set</a> </li>
  		</ul>
  		<div class="page-title"> <a href="<?php echo site_url('/dataset'); ?>"><i class="icon-custom-left"></i></a>
  			<?php 
  			if($op == "edit_"){
  				?><h3>Edit Data Set:<span class="semi-bold"><?php echo $name; ?></span></h3>
          <?php }
		    else{
  				?><h3>Add<span class="semi-bold"> Data Set</span></h3>
        <?php } ?>
  		</div>
        <input type="hidden" id="site_url" value="<?php echo site_url();?>">
        <form class="add-dataset-form form-horizontal"  action="<?php echo site_url('/dataset/submit'); ?>" method="post">
            <input type="hidden" name="op" id="op" value="<?php echo $op;?>">
            <input type="hidden" name="id" id="id" value="<?php echo $id;?>">
            <div class="row">
                <div class="grid simple">
                        <div class="grid-body no-border">
                            <div class="col-md-12" id="message_div" style="display:none">
                                <div class="clear10"></div>
                                <div class="alert alert-danger">Error!</div>
                            </div>
                            <?php if(isset($message)){?>
                                <div class="col-md-12">
                                    <div class="clear10"></div>
                                    <div class="alert alert-success"><?php echo $message;?></div>
                                </div>
                            <?php }?>
                            <div class="clear10"></div>
                            <div class="col-md-1"><label class="control-label" for="template">Template:</label></div>
                            <div class="col-md-3">
                            	<select class="input-sm form-control inline" name="template" id="template">
                                	<option value="0">Select Template</option>
                                    <?php foreach($templates as $temp){?>
                                        <option value="<?php echo $temp->id;?>" <?php if($template==$temp->id){echo 'selected';}?> data-image="assets/img/icon/<?php echo $temp->list_icon;?>.png"><?php echo $temp->name;?></option>
                                    <?php }?>
                                </select>
                           	</div>
                            <div class="col-md-1 no-padding">
                            	<select class="input-sm form-control inline" id="years">
                                <option value="0">Years</option>
                                <?php foreach($years_list as $year){?>
                                    	<option value="<?php echo $year->year;?>" <?php if($year->year == $year_first ){ echo 'selected'; }?>><?php echo $year->year;?></option>
                                    <?php }?>
                                </select>
                           	</div>
                            <div class="col-md-6" id="years_div">
                            	
                           	</div>
                            <div class="col-md-1">
                            <a href="javascript:void(0)" id="import" title="Import CSV" class="pull-left fa fa-cloud-upload"></a>
                            <a href="javascript:void(0)" id="export" title="Export CSV" class="pull-right fa fa-cloud-download"></a>
                           	</div>
                            <div class="clear10"></div>
                            <div id="tables_div">
                            	<?php if($op == "edit_"){
										$year = 0;
										$first_check=0;
											foreach($kpis as $kp){
												if($year != $kp->year){
													$year = $kp->year;
													$second_check = 1;
								?>
                                <input type="hidden" name="quick_list[]" value="<?php echo $year;?>" data-id="table<?php echo $template.'_'.$year;?>">
                                <table class="table table-bordered table-condensed table-hover table-striped dataset-table" id="table<?php echo $template.'_'.$year;?>" <?php if($first_check == 0){ $first_check = 1; }else{ echo 'style="display:none"';}?>>
                                        <thead>
                                            <tr>
                                                <th><?php echo $name;?></th>
                                                <th>Q1 <?php echo $year;?></th>
                                                <th>Q2 <?php echo $year;?></th>
                                                <th>Q3 <?php echo $year;?></th>
                                                <th>Q4 <?php echo $year;?></th>
                                                <th>FY <?php echo $year;?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php }?>
                                        
                                            <tr id="<?php echo $template.'_'.$year.'_'.$kp->kpi;?>" onclick="edit_it('<?php echo $template.'_'.$year.'_'.$kp->kpi;?>','<?php echo str_replace("'","&acute;",$kp->kpi_description);?>');">
                                                <td>
                                                   <?php echo $kp->kpi_name;?>
                                                </td>
                                                
                                                <td class="edit-able">
                                                    <span><?php echo $kp->q1; ?></span>
                                                    <input type="text" name="Q1<?php echo $year.'_'.$kp->kpi;?>" class="form-control auto" style="display:none" value="<?php echo str_replace(',', '', $kp->q1); ?>">
                                                </td>
                                                
                                                <td class="edit-able">
                                                    <span><?php echo $kp->q2; ?></span>
                                                    <input type="text" name="Q2<?php echo $year.'_'.$kp->kpi;?>" class="form-control auto" style="display:none" value="<?php echo str_replace(',', '', $kp->q2); ?>">
                                                </td>
                                                
                                                <td class="edit-able">
                                                    <span><?php echo $kp->q3; ?></span>
                                                    <input type="text" name="Q3<?php echo $year.'_'.$kp->kpi;?>" class="form-control auto" style="display:none" value="<?php echo str_replace(',', '', $kp->q3); ?>">
                                                </td>
                                                
                                                <td class="edit-able">
                                                    <span><?php echo $kp->q4; ?></span><input type="text" name="Q4<?php echo $year.'_'.$kp->kpi;?>" class="form-control auto" style="display:none" value="<?php echo str_replace(',', '', $kp->q4); ?>">
                                                </td>
                                                <td class="edit-able">
                                                    <span><?php echo $kp->fy; ?></span><input type="text" name="FY<?php echo $year.'_'.$kp->kpi;?>" class="form-control auto" style="display:none" value="<?php echo str_replace(',', '', $kp->fy); ?>">
                                                </td>
                                            </tr>
                                            
                             <?php if($second_check == $terms){?>
                                        </tbody>
                                </table>
            					<?php 
							 		}
									$second_check ++;
								}
						}?>
                            </div>
                            <div class="col-md-2 no-padding"><button class="btn btn-success pull-left data-set-submit-button" <?php if($op != "edit_"){ ?>disabled="disabled"<?php }?> type="button" onclick="this_form_submit();">Submit Data Set</button><a href="javascript:void(0)" id="dataset_detail" class="pull-right fa fa-edit" data-toggle="modal" data-target="#dataset_detail_model"></a></div>
                            <div class="col-md-10">
                            	<div class="alert alert-info" id="description_box" style="display:none"></div>
                            </div>
                            <div class="clear10"></div>        
                      </div>
                </div>
            </div>
            <div class="modal fade notif_modals" id="dataset_detail_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4>Data Set Description</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
								<label>Source(s) of data</label>
								<input type="text" value="Manually entered" name="sources" class="form-control" disabled="disabled">
							</div>
                            <div class="form-group">
								<label>Author/Creator</label>
								<input type="text" value="<?php echo $created_by;?>" class="form-control" disabled="disabled">
                                <input type="hidden" name="creator" value="<?php echo $user_id;?>">
							</div>
                            <div class="form-group">
								<label>Company</label>
								<select name="company" id="company" class="form-control">
                                	<?php foreach($user_company as $com){?>
                                		<option value="<?php echo $com->company;?>" <?php if($company == $com->company){echo 'selected';}?>><?php echo $com->company_name;?></option>
                                    <?php }?>
                                </select>
							</div>
							<div class="form-group">
								<label>Title</label>
								<input type="text" placeholder="Title" class="form-control" id="data_set_title" name="data_set_title" value="<?php echo $name;?>">
							</div>
                            <div class="form-group">
								<label>Description</label>
								<textarea placeholder="Description" class="form-control" id="data_set_description" name="data_set_description"><?php echo $description;?></textarea>
							</div>
                            <div class="clear20"></div>
							<div class="form-group text-left">
								<a class="btn btn-success" href="javascript:void(0);" data-dismiss="modal" aria-hidden="true">Save Changes</a>
							</div>
                        </div>
                        
                    </div>
                </div>
            </div>
       </form>
</div>
<div class="modal fade notif_modals" id="removeSheetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4></h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove the data for <span id="year_to_remove"> </span>?
                    <i class="fa-li fa fa-spinner fa-spin loading hide"></i>
                </p>
                <input type="hidden" id="table_to_remove" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="yes_button_sheet" type="button" class="btn btn-danger ajax_submit">remove</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade notif_modals" id="template_changeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4></h4>
            </div>
            <div class="modal-body">
   	                <p>Are you sure you want to change the template it may delete your existing record?
                    <i class="fa-li fa fa-spinner fa-spin loading hide"></i>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button id="yes_button" type="button" class="btn btn-danger">Yes</button>
            </div>
        </div>
    </div>
</div>
<span style="display:none"><form enctype="multipart/form-data" id="file_upload_form"><input type="file" name="csv_to_import" id="csv_to_import"/></form></span>