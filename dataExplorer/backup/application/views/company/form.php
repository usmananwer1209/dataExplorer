<?php
if ($op == "edit_") {
	$id = $obj -> id;
	$name = $obj -> company_name;
	$sector_name = $obj -> sector;
	$state_name = $obj -> state;
	$industry_name = $obj -> industry;
	$sic_name = $obj -> sic;
	$sic_code = $obj -> sic_code;
} else {
	$id = '';
	$name = '';
	$sector_name = '';
	$state_name = '';
	$industry_name = '';
	$sic_name = '';
	$sic_code = 0;
}
?>
	<div class="clearfix"></div>
  	<div class="content">
  		<ul class="breadcrumb">
  			<li>
  				<a href="<?php echo site_url('/home'); ?>">HOME</a>
  			</li>
  			<li><a href="#" class="active">Private Company</a> </li>
  		</ul>
  		<div class="page-title"> <a href="<?php echo site_url('/company'); ?>"><i class="icon-custom-left"></i></a>
  			<?php 
  			if($op == "edit_"){
  				?><h3>Edit Company:<span class="semi-bold"><?php echo $name; ?></span></h3>
          <?php }
		    else{
  				?><h3>Add<span class="semi-bold"> New Company</span></h3>
        <?php } ?>
  		</div>
        <input type="hidden" id="site_url" value="<?php echo site_url();?>">
        <form class="add-company-form form-horizontal" action="<?php echo site_url('/company/submit'); ?>" method="post">
        <input type="hidden" name="op" id="op" value="<?php echo $op;?>">
        <input type="hidden" name="id" id="id" value="<?php echo $id;?>">
        <input type="hidden" id="industry_name" value="<?php echo $industry_name;?>">
        <input type="hidden" id="sic_name" value="<?php echo $sic_name;?>">
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
                    			<div class="clear30"></div>
                    			<div class="form-group">
        							<div class="col-md-3">
        								<label class="control-label" for="name">Name</label>
                                    </div>
                                    <div class="col-md-9">
                                    	<input class="form-control" type="text" id="name" name="name" value="<?php echo $name;?>">
                                    </div>
        						</div>
                                <div class="form-group">
        							<div class="col-md-3">
        								<label class="control-label" for="state">State</label>
                                    </div>
                                    <div class="col-md-9">
                                    	<select class="form-control" id="state" name="state">
                                        <option value="0">Select State</option>
                                        <?php foreach($state as $st){
											if($st->state!=''){
											?>
                                        	<option value="<?php echo $st->state; ?>" <?php if($state_name == $st->state){ echo 'selected';}?>><?php echo $st->state; ?></option>
                                        <?php 
											}
										}?>
                                        </select>
                                    </div>
        						</div>
                                <div class="form-group">
        							<div class="col-md-3">
        								<label class="control-label" for="sector">Sector</label>
                                    </div>
                                    <div class="col-md-9">
                                    	<select class="form-control" id="sector" name="sector">
                                            <option value="0">Select Sector</option>
                                            <?php foreach($sector as $sec){?>
                                                <option value="<?php echo $sec->sector; ?>" <?php if($sector_name == $sec->sector){ echo 'selected';}?>><?php echo $sec->sector; ?></option>
                                            <?php }?>
                                        </select>
                                    </div>
        						</div>
                                <div class="form-group">
        							<div class="col-md-3">
        								<label class="control-label" for="industry">Industry</label>
                                    </div>
                                    <div class="col-md-9">
                                    	<select class="form-control" id="industry" name="industry" disabled="disabled">
                                        </select>
                                    </div>
        						</div>
                                <div class="form-group">
        							<div class="col-md-3">
        								<label class="control-label" for="name">SIC</label>
                                    </div>
                                    <div class="col-md-9">
                                    	<select class="form-control" id="sic" name="sic" disabled="disabled">
                                        </select>
                                        <input type="hidden" name="sic_code" id="sic_code" value="<?php echo $sic_code;?>">
                                    </div>
        						</div>
                                <div class="form-actions">  
    							<div class="pull-right">
    								<button class="btn btn-white btn-cons" type="reset">Cancel</button>
                    				<button class="btn btn-success btn-cons" type="submit"><i class="fa fa-check"></i> Save</button>
    							</div>
    						</div>
                                <div class="clear"></div>
                    </div>
            </div>
        </div>
  		<div class="row">
  			<div class="grid simple">
  				<div class="grid-title no-border">
  					<h4><b>Users of the company</b></h4>
  					<div class="tools"> 
  						<a class="collapse" href="javascript:;"></a> 
  					</div>
  				</div>
  				<div class="grid-body no-border">
                	<div class="col-md-12">
                    	<div class="col-md-3"><b>NAME</b></div><div class="col-md-6"><b>EMAIL</b></div><div class="col-md-3"><b>ENABLE LOAD DATA?</b></div>
                        <div class="clear10"></div>	
                    	<div id="users_of_company">
                        	<?php if(isset($users)){
									foreach($users as $use){
										$div_class = 'user'.$use->id;
										if($use->enable_load_data == 1){
											$checked = 'checked';
										}else{
											$checked = '';
										}
							echo '<div class="'.$div_class.'" class="col-md-12 no-padding">'.
									'<div class="col-md-3">'.
										'<input type="hidden" name="users[]"  value="'.$use->id.'">'.$use->user_name.
										' <a href="javascript:void(0)" data-toggle="modal" data-target="#removeUserModal" onclick="remove_user(\''.$div_class.'\');" class="fa fa-minus-circle"></a>'.
									'</div>'.
									'<div class="col-md-6">'.$use->email.'</div>'.
									'<div class="col-md-3">'.
									'<div class="checkbox check-success">'.
										'<input type="checkbox" name="enable_load_data'.$use->id.'" value="1" id="enable_load_data'.$use->id.'" '.$checked.'>'.
											'<label for="enable_load_data'.$use->id.'"></label>'.
										'</div>'.
									'</div>'.
									'<div class="clear"></div>'.
							   '</div>';
									}
							}?>
                        </div>
                        <div class="clear5"></div>
                        <div class="col-md-12 no-padding select-users-company">
                        	<div class="col-md-3 no-padding">
                            	<input class="form-control autocomplete" type="text" name="user" for="users" data-autocomplete="users" data-label="user_name" data-value="id" data-action="<?php echo site_url("/company/list_users");?>" placeholder="Search User">
                            </div>
                            <div class="col-md-9 no-padding">
                            <button class="btn btn-success pull-left" type="button" id="add_user"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="clear10"></div>
                    </div>
  				</div>
  			</div>
  		</div>
        <div class="row">
  			<div class="grid simple">
  				<div class="grid-title no-border">
  					<h4><b>Guests of the company</b></h4>
  					<div class="tools"> 
  						<a class="collapse" href="javascript:;"></a> 
  					</div>
  				</div>
  				<div class="grid-body no-border">
                	<div class="col-md-12">
                    	<div class="col-md-3"><b>NAME</b></div><div class="col-md-9"><b>EMAIL</b></div>
                        <div class="clear10"></div>
                    	<div id="guests_of_company">
                        	<?php if(isset($guests)){
                            			foreach($guests as $gue){
										$div_class = 'guest'.$gue->id;
							echo '<div class="'.$div_class.'" class="col-md-12 no-padding">'.
									'<div class="col-md-3">'.
										'<input type="hidden" name="guests[]"  value="'.$gue->id.'">'.$gue->user_name.
										' <a href="javascript:void(0)" data-toggle="modal" data-target="#removeUserModal" onclick="remove_user(\''.$div_class.'\');" class="fa fa-minus-circle"></a>'.
									'</div>'.
									'<div class="col-md-9">'.$gue->email.'</div>'.
									'<div class="clear"></div>'.
							   '</div>';
									}
                    		 }?>
                        </div>
                        <div class="clear5"></div>
                        <div class="col-md-12 no-padding select-users-company">
                            <div class="col-md-3 no-padding">
                            	<input class="form-control autocomplete" type="text" name="guest" for="users" data-autocomplete="users" data-label="user_name" data-value="id" data-action="<?php echo site_url("/company/list_users");?>" placeholder="Search User">
                            </div>
                            <div class="col-md-9 no-padding">
                            	<button class="btn btn-success pull-left" type="button" id="add_guest"><i class="fa fa-plus"></i></button>
                           	</div>
                        </div>
                        <div class="clear10"></div>
                    </div>
  				</div>
  			</div>
  		</div>
        </form>
  	</div>
<div class="modal fade notif_modals" id="removeUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
                <div class="alert alert-error hide">The operation could not be completed.</div>
                <p>Are you sure you want to remove this User?
                    <i class="fa-li fa fa-spinner fa-spin loading hide"></i>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="hidden" id="user_to_remove" value="">
                <button type="button" class="btn btn-danger" onclick="remove_user_confirm()">remove</button>
            </div>
        </div>
    </div>
</div>