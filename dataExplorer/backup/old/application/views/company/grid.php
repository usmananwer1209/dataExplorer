<!--<div id="search_filters" class="pull-left">
	<input type="text" aria-controls="example" class="input-medium">
	<a id="Search" href="javascript:;">Search</a>
</div>-->
<div class="pull-right">
	<?php if($user->is_root == 1){?>
		<a href="<?php echo site_url('/company/add');?>">
			<button class="btn btn-success btn-cons" type="button">
	        	<i class="fa fa-check"></i>&nbsp;Add</button>	
	    </a>  
    <?php } ?>
</div>
<div class="clearfix"></div>
<?php if(isset($message)){?>
                        <div class="col-md-12">
                            <div class="clear10"></div>
                            <div class="alert alert-success"><?php echo $message;?></div>
                        </div>
<?php }?>
<div id="parks" class="just company_listing">
	<!-- "TABLE" HEADER CONTAINING SORT BUTTONS (HIDDEN IN GRID MODE)-->
	<div class="list_header">
		<div class="meta name active desc" id="SortByName">
			Company Name &nbsp; 
			<span class="sort anim150 asc active" data-sort="data-name" data-order="asc"></span>
			<span class="sort anim150 desc" data-sort="data-name" data-order="desc"></span>
		</div>
		<div class="meta sector">
			Sector &nbsp; 
			<span class="sort anim150 asc" data-sort="data-sector" data-order="asc"></span>
			<span class="sort anim150 desc" data-sort="data-sector" data-order="desc"></span>
		</div>
        <div class="meta industry">
			Industry &nbsp; 
			<span class="sort anim150 asc" data-sort="data-industry" data-order="asc"></span>
			<span class="sort anim150 desc" data-sort="data-industry" data-order="desc"></span>
		</div>
        <div class="meta sic">
			SIC &nbsp; 
			<span class="sort anim150 asc" data-sort="data-sic" data-order="asc"></span>
			<span class="sort anim150 desc" data-sort="data-sic" data-order="desc"></span>
		</div>
        <div class="meta state">
			State &nbsp; 
			<span class="sort anim150 asc" data-sort="data-state" data-order="asc"></span>
			<span class="sort anim150 desc" data-sort="data-state" data-order="desc"></span>
		</div>
        <div class="meta user">
			#User &nbsp; 
			<span class="sort anim150 asc" data-sort="data-user" data-order="asc"></span>
			<span class="sort anim150 desc" data-sort="data-user" data-order="desc"></span>
		</div>
        <div class="meta guest">
			#Guest &nbsp; 
			<span class="sort anim150 asc" data-sort="data-guest" data-order="asc"></span>
			<span class="sort anim150 desc" data-sort="data-guest" data-order="desc"></span>
		</div>
	</div>
	<ul>

<?php 
  $i = 0;
  foreach ($objs as $obj) {
  	?>
	<li class="company_list mix" 
		data-name="<?php echo $obj->company_name; ?>" 
		data-sector="<?php echo $obj->sector; ?>"
        data-industry="<?php echo $obj->industry; ?>"
        data-sic="<?php echo $obj->sic_code .' - '. $obj->sic; ?>"
        data-state="<?php echo $obj->state; ?>"
        data-user="<?php echo $obj->user_count; ?>"
        data-guest="<?php echo $obj->guest_count; ?>"
        data-company-id="<?php echo $obj->id; ?>"
		>

		<div class="meta name">
			<div class="titles">
				<h2>
                   <span><?php echo $obj->company_name ; ?></span>
					<?php if($user->is_root == 1){?>
				        <a href="<?php echo site_url('/company/edit/'.$obj->id);?>">
          					<i class="fa fa-edit"></i>
        				</a>
                         <a class="fa fa-minus-circle"
                           id="delete-company"
                           action=<?php echo site_url('/company/remove/' . $obj->id); ?>
                           data-toggle="modal"
                           data-modal-id="#removeCompanyModal"
                           data-company-id="<?php echo $obj->id; ?>"
                           title="delete company"></a>

    				<?php } ?>
				</h2>
			</div>
		</div>
		<div class="meta sector">
      		<b><?php echo $obj->sector; ?></b>
		</div>
		<div class="meta industry">
      		<b><?php echo $obj->industry; ?></b>
		</div>
        <div class="meta sic">
      		<b><?php echo $obj->sic_code .' - '. $obj->sic; ?></b>
		</div>
        <div class="meta state">
      		<b><?php echo $obj->state; ?></b>
		</div>
        <div class="meta user">
      		<b><?php echo $obj->user_count; ?></b>
		</div>
        <div class="meta guest">
      		<b><?php echo $obj->guest_count; ?></b>
		</div>
  	</li>
<?php } ?>
	</ul>
</div>

<div class="modal fade notif_modals" id="removeCompanyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
                <div class="alert alert-error hide">The operation could not be completed.</div>
                <p>Are you sure you want to remove this Company ?
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