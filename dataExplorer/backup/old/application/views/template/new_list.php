<?php 
$user = (object)$this->session->userdata('user');
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
                    <?php }?>
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