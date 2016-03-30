<div class="pull-right">
	<?php if($user->is_root == 1 || count($user_company)>0){?>
		<a data-toggle="modal" data-target="#kpi_list_builder" href="javascript:">
			<button class="btn btn-success btn-cons" id="add_new_template" type="button">
	        	<i class="fa fa-check"></i>&nbsp;Add</button>	
	    </a>  
    <?php } ?>
</div>
<?php $kpi_title = 'Template'; ?>
<input  id="kpi_title" type="hidden" value="<?php echo $kpi_title;?>" />
<input  id="builder_mode" type="hidden" value="0"/>
<input  id="builder_mode_id" type="hidden" value=""/>
<input  id="builder_mode_action" type="hidden" value=""/>
<div class="clearfix"></div>
<div id="messages"></div>
<div id="parks" class="just template_listing">
	<!-- "TABLE" HEADER CONTAINING SORT BUTTONS (HIDDEN IN GRID MODE)-->
	<div class="list_header">
		<div class="meta name active desc" id="SortByName">
			Template Name &nbsp; 
			<span class="sort anim150 asc active" data-sort="data-name" data-order="asc"></span>
			<span class="sort anim150 desc" data-sort="data-name" data-order="desc"></span>
		</div>
		<div class="meta created-by">
			Created By &nbsp; 
			<span class="sort anim150 asc" data-sort="data-created-by" data-order="asc"></span>
			<span class="sort anim150 desc" data-sort="data-created-by" data-order="desc"></span>
		</div>
        <div class="meta company">
			Company &nbsp; 
			<span class="sort anim150 asc" data-sort="data-company" data-order="asc"></span>
			<span class="sort anim150 desc" data-sort="data-company" data-order="desc"></span>
		</div>
        <div class="meta description">
			Description &nbsp; 
			<span class="sort anim150 asc" data-sort="data-description" data-order="asc"></span>
			<span class="sort anim150 desc" data-sort="data-description" data-order="desc"></span>
		</div>
        <div class="meta terms">
			#Terms &nbsp; 
			<span class="sort anim150 asc" data-sort="data-terms" data-order="asc"></span>
			<span class="sort anim150 desc" data-sort="data-terms" data-order="desc"></span>
		</div>
	</div>
	<ul>

<?php 
if(isset($objs)){
  $i = 0;
  foreach ($objs as $obj) {
  	?>
	<li class="template_list mix" 
		data-name="<?php echo $obj->name; ?>" 
		data-created-by="<?php echo $obj->created_by; ?>"
        data-company="<?php echo $obj->company; ?>"
        data-description="<?php echo $obj->description; ?>"
        data-terms="<?php echo $obj->terms-1; ?>"
        data-template-id="<?php echo $obj->id; ?>"
		>

		<div class="meta name">
			<div class="titles">
				<h2>
                   <span><?php echo $obj->name ; ?></span>
				    <?php if($obj->user == $user->id){ ?>
                           <a data-toggle="modal" title="Edit Template" data-target="#kpi_list_builder" href="javascript:" onclick="edit_template(<?php echo $obj->id; ?>,'show')">
                                <i class="fa fa-edit"></i>
                          </a>
                    <?php }else{ ?>
                          <a data-toggle="modal" title="View Template" data-target="#kpi_list_builder" href="javascript:" onclick="edit_template(<?php echo $obj->id; ?>,'hide')">
                                <i class="fa fa-eye"></i>
                          </a>
                      <?php }?>
                        <?php if($user->is_root == 1 || $obj->user == $user->id){?>
                         <a class="fa fa-minus-circle"
                           id="delete-template"
                           action=<?php echo site_url('/template/remove/' . $obj->id); ?>
                           data-toggle="modal"
                           data-modal-id="#removeTemplateModal"
                           data-template-id="<?php echo $obj->id; ?>"
                           title="delete template"></a>
                        <?php } ?>
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#createCloneModal" onclick="clone_template(<?php echo $obj->id; ?>)" title="Clone Template" >
          					<i class="fa fa-external-link"></i>
        				</a>
				</h2>
			</div>
		</div>
		<div class="meta created-by">
      		<b><?php if($obj->created_by == 'System Admin'){ echo '<i class="fa fa-globe"></i> '; }else{ echo '<i class="fa fa-users"></i> '; }?><?php echo $obj->created_by; ?></b>
		</div>
		<div class="meta company">
      		<b><?php echo $obj->company; ?></b>
		</div>
        <div class="meta description">
      		<b><?php echo $obj->description; ?></b>
		</div>
        <div class="meta terms">
      		<b><?php echo $obj->terms-1; ?></b>
		</div>
        
  	</li>
<?php } }?>
	</ul>
</div>
<div class="modal fade notif_modals" id="removeTemplateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
                <div class="alert alert-error hide">The operation could not be completed.</div>
                <p>Are you sure you want to remove this Template ?
                    <i class="fa-li fa fa-spinner fa-spin loading hide"></i>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger ajax_submit">remove</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade notif_modals" id="createCloneModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
                <div class="alert alert-error hide">The operation could not be completed.</div>
                <p>Are you sure you want to clone the template? The new template will be named as <span id="clone_template_name"></span>
                    <i class="fa-li fa fa-spinner fa-spin loading hide"></i>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="hidden" id="clone_template_id" value="">
                <button type="button" class="btn btn-success" onclick="clone_template_confirm()">Create</button>
            </div>
        </div>
    </div>
</div>
<div class="add-template">
<?php require_once $folder."/card/kpi_list_builder.php";?>
</div>