<!--<div id="search_filters" class="pull-left">
	<input type="text" aria-controls="example" class="input-medium">
	<a id="Search" href="javascript:;">Search</a>
</div>-->
<div class="pull-right">
	<?php if($user->is_root == 1 || count($user_company)>0){?>
		<a href="<?php echo site_url('/dataset/add');?>">
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
<div id="parks" class="just dataset_listing">
	<!-- "TABLE" HEADER CONTAINING SORT BUTTONS (HIDDEN IN GRID MODE)-->
	<div class="list_header">
		<div class="meta name active desc" id="SortByName">
			Data set Name &nbsp; 
			<span class="sort anim150 asc active" data-sort="data-name" data-order="asc"></span>
			<span class="sort anim150 desc" data-sort="data-name" data-order="desc"></span>
		</div>
		<div class="meta created-by">
			Created By &nbsp; 
			<span class="sort anim150 asc" data-sort="data-created-by" data-order="asc"></span>
			<span class="sort anim150 desc" data-sort="data-created-by" data-order="desc"></span>
		</div>
        <div class="meta template">
			Template &nbsp; 
			<span class="sort anim150 asc" data-sort="data-template" data-order="asc"></span>
			<span class="sort anim150 desc" data-sort="data-template" data-order="desc"></span>
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
        <div class="meta last-upload">
			Last Upload &nbsp; 
			<span class="sort anim150 asc" data-sort="data-last-upload" data-order="asc"></span>
			<span class="sort anim150 desc" data-sort="data-last-upload" data-order="desc"></span>
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
	<li class="dataset_list mix" 
		data-name="<?php echo $obj->title; ?>" 
		data-created-by="<?php echo $obj->created_by; ?>"
        data-template="<?php echo $obj->template_name; ?>"
        data-company="<?php echo $obj->company_name; ?>"
        data-description="<?php echo $obj->description; ?>"
        data-last-upload="<?php echo $obj->modification_time; ?>"
        data-terms="<?php echo $obj->terms; ?>"
        data-dataset-id="<?php echo $obj->id; ?>"
		>
		<div class="meta name">
			<div class="titles">
				<h2>
                   <span><?php echo $obj->title ; ?></span>
					<?php if($obj->user == $user->id){ ?>
				        <a href="<?php echo site_url('/dataset/edit/'.$obj->id);?>">
          					<i class="fa fa-edit"></i>
        				</a>
                         <a class="fa fa-minus-circle"
                           id="delete-dataset"
                           action=<?php echo site_url('/dataset/remove/' . $obj->id); ?>
                           data-toggle="modal"
                           data-modal-id="#removeDatasetModal"
                           data-dataset-id="<?php echo $obj->id; ?>"
                           title="delete dataset"></a>

    				<?php } ?>
				</h2>
			</div>
		</div>
		<div class="meta created-by">
      		<b><?php echo $obj->created_by; ?></b>
		</div>
		<div class="meta template">
      		<b><?php echo $obj->template_name; ?></b>
		</div>
        <div class="meta company">
      		<b><?php echo $obj->company_name; ?></b>
		</div>
        <div class="meta description">
      		<b><?php echo $obj->description; ?></b>
		</div>
        <div class="meta last-upload">
      		<b><?php echo date('m/d/Y',strtotime($obj->modification_time)); ?></b>
		</div>
        <div class="meta terms">
      		<b><?php echo $obj->terms; ?></b>
		</div>
  	</li>
<?php } 
}
?>
	</ul>
</div>

<div class="modal fade notif_modals" id="removeDatasetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
                <div class="alert alert-error hide">The operation could not be completed.</div>
                <p>Are you sure you want to remove this Data Set ?
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