<div class="row">
  <div class="col-md-12">
    <div class="row">
      <div id="filters_isotope2">
        <button data-name="name" type="button" class="btn btn-white btn-cons ">
          <i class="fa "></i>Name
        </button>
        <button data-name="creation_time" type="button" class="btn btn-white btn-cons active">
          <i class="fa fa-sort-amount-desc"></i>Date Created
        </button>
      </div>
	<input type="hidden" id="site_url" value="<?php echo site_url();?>">
      <ul id="cards_isotope2"  class="isotope2 transition">
      	<div id="overlay_div" class="overlay2" style="display:none"><img src="<?php echo site_url('assets/img/AjaxLoader2.gif');?>" /></div>
        <!---------------------------------------  ADD Storboard --------------------------------------- -->
        <li class="cell element-item transition stamp"
                data-id="0"
                data-description="0"
                data-type="0"
                data-creation_time="0"
                data-public="0"
                data-period="0"
                >
        <a href="<?php echo site_url("storyboard/add"); ?>">
          <div class="m-l-10 ">
            <div class="tiles white cards add-storyboards text-center pagination-centered">
              <span class="black">+ Add </span>
              <span class="green"> Storyboard</span>
            </div>
            <div class="tiles gray  p-t-5 p-b-5  m-b-20">
              <p class="text-center text-white semi-bold  small-text">New</p>
            </div>
          </div>
        </a>
        <input type="hidden" name="rec_count_start" id="rec_count_start" value="<?php echo count($objs);?>">
        </li>

      <?php
        foreach ($objs as $obj) {
          $info = "";
          $info .= "Date Created:".$obj->creation_time."<br/>";
          ?>
          <li class="cell element-item transition"
              data-id="<?php echo $obj->id; ?>"
              data-name="<?php echo strip_tags($obj->title); ?>"
              data-description="<?php echo strip_tags($obj->description); ?>"
              data-creation_time="<?php echo $obj->creation_time; ?>"
              data-public="<?php echo ($obj->public)?'public':'private'; ?>"

              data-type=""
              data-period="">
			<a class="fa fa-external-link-square"  
              id="view-sb"
              title="view Storyboard" href="<?php echo site_url("storyboard/view/".$obj->id);?>" ></a>
            <a class="fa <?php echo ($obj->public)?'fa-unlock':'fa-unlock-alt'; ?>"  
              id="publish-sb"
              data-toggle="modal" 
              data-object="storyboard"
              data-modal-id="#publishModal"
              data-sb-id="<?php echo $obj->id;?>"
              data-sb-public="<?php echo $obj->public; ?>"
              title="publish Storyboard" ></a>
            <a class="fa fa-bullhorn" 
              id="share-sb"
              data-toggle="modal" 
              data-object="storyboard"
              data-modal-id="#shareModal<?php echo $obj->id;?>"
              data-sb-id="<?php echo $obj->id;?>"
              title="share with..."></a>
            <a class="fa fa-minus-square" 
              id="delete-sb"
              data-toggle="modal" 
              data-object="storyboard"
              data-modal-id="#removeModal"
              data-sb-id="<?php echo $obj->id;?>"
              title="delete Storyboard"></a>
            <a class="fa fa-retweet flipper"
              data-card-id="<?php echo $obj->id; ?>"
              title="flip Storyboard" href="#"></a>

            <div class="m-l-10 flip_sb" data-card-id="<?php echo $obj->id; ?>">
              <a href="<?php echo site_url('storyboard/edit/' . $obj->id); ?>"> 
                <div class="tiles white cards text-center pagination-centered" style="position:relative;">
                  <?php 
                    echo '<img src="'.img_url('empty_template_start_'.$obj->start_end_template.'.png').'" style="width:100%; height:100%;" />';
                    if($obj->start_end_template == 1){
                      if(!empty($obj->start_image))
                       echo '<img src="'.$obj->start_image.'" style="width:90px; height:80px; position:absolute; position:absolute; top:27px; left:1px" />';
                      if(!empty($obj->title))
                        echo '<span style="position:absolute; position:absolute; top:56px; left:100px; font-family:verdana; font-size:12px; line-height:15px; color:#fff; text-align:center; width:190px; display:inline-block;">'.$obj->title.'</span>';
                      if(!empty($obj->description))
                        echo '<div style="position:absolute; position:absolute; top:114px; left:130px; font-family:verdana; font-size:9px; line-height:12px; color:#666; text-align:left; width:156px; height:75px; overflow:hidden; display:inline-block;">'.strip_tags($obj->description).'</div>';
                    } 
                    if($obj->start_end_template == 2){
                      if(!empty($obj->start_image))
                       echo '<img src="'.$obj->start_image.'" style="width:135px; height:135px; border-radius:50%; position:absolute; top:45px; left:15px" />';
                      if(!empty($obj->title))
                        echo '<span style="position:absolute; position:absolute; top:12px; left:10px; font-family:verdana; font-size:12px; line-height:15px; color:#fff; text-align:left; width:290px; display:inline-block;">'.$obj->title.'</span>';
                      if(!empty($obj->description))
                        echo '<div style="position:absolute; position:absolute; top:50px; left:168px; font-family:verdana; font-size:9px; line-height:12px; color:#fff; text-align:left; width:128px; height:120px; overflow:hidden; display:inline-block;">'.strip_tags($obj->description).'</div>';
                    } 
                    if($obj->start_end_template == 3){
                      if(!empty($obj->title))
                        echo '<span style="position:absolute; position:absolute; top:22px; left:104px; font-family:verdana; font-size:12px; line-height:15px; color:#fff; text-align:left; width:190px; display:inline-block;">'.$obj->title.'</span>';
                      if(!empty($obj->description))
                        echo '<div style="position:absolute; position:absolute; top:56px; left:134px; font-family:verdana; font-size:9px; line-height:12px; color:#fff; text-align:left; width:156px; height:110px; overflow:hidden; display:inline-block;">'.strip_tags($obj->description).'</div>';
                    } 

                  ?>
                </div>
              </a>
              <div class="tiles gray p-t-5 p-b-5  m-b-20">
                <p class="text-center text-white semi-bold  small-text"> 
                  <?php echo cut_string($obj->title, 25);?>
                </p>
              </div>

              <div class="m-l-10 card_flipped" style="display: none">
                <a class="flip_sb"
                   data-card-id="<?php echo $obj->id; ?>"
                   data-card-flipped="no"
                   title="flip storyboard" href="#">
                  <div class="white text-left flip_div pagination-centered">
                    <p><strong>Author : </strong> <?php echo $obj->author; ?><br/></p>
                    <p><strong>Title : </strong> <?php echo $obj->title; ?><br/></p>
                    <p><strong>Date Created : </strong> <?php echo $obj->creation_time; ?><br/></p>
                    <p><strong># Viewed : </strong> <?php echo $obj->viewed; ?><br/></p>
                  </div>
                  <div class="tiles gray p-t-5 p-b-5  m-b-20">
                    <p class="text-center text-white semi-bold  small-text"> 
                      <?php echo $obj->title; ?>
                    </p>    
                  </div>
                </a>
              </div>
            </div>
          </li>

        
<div class="modal shareModal fade notif_modals" id="shareModal<?php echo $obj->id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4>Share your storyboard</h4>
          </div>
          <div class="modal-body">
            <div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
            <div class="alert alert-error hide">The operation could not be completed.</div>
            <p>Share this with :
              <i class="fa-li fa fa-spinner fa-spin loading hide"></i>
            </p>
            <select class="select_circles" multiple="multiple">
              <?php foreach ($my_circles as $circle) {  ?>
                <option
                  <?php echo (sb_shared($obj->id,$circle['id']))? 'selected="selected"': '' ?>
                value="<?php echo $circle['id'];?>"><?php echo $circle['name'];?></option>
              <?php } ?>
            </select>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary ajax_submit">Share</button>
          </div>
      </div>
  </div>
</div>
        <?php } ?>
      </ul>
      <div class="col-md-12 text-center" id="loader_img" style="display:none">
      <img src="<?php echo site_url('assets/img/AjaxLoader2.gif');?>" />
      <div class="clear10"></div>		
      </div>
      <?php if(count($objs)== $total_result_count){?>
      <div class="col-md-12 text-center" id="load_more_btn" style="display:none">
      			<button type="button" class="btn btn-success btn-large font20 border-radious-none" onclick="load_more_mystoryboard();">
                  <i class="fa fa-spinner"></i> Load More
                </button>
      </div>
      
      <?php }?>
	<div class="clear30"></div>

    </div>


  </div>
</div>



<div class="modal fade notif_modals" id="publishModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4></h4>
            </div>
            <div class="modal-body">
              <div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
              <div class="alert alert-error hide">The operation could not be completed.</div>
              <p>Are you sure you want make this storyboard 
                  <span class="public_status">private</span> ?
                <i class="fa-li fa fa-spinner fa-spin loading hide"></i>
              </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary ajax_submit">submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade notif_modals" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4></h4>
            </div>
            <div class="modal-body">
              <div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
              <div class="alert alert-error hide">The operation could not be completed.</div>
              <p>Are you sure you want to remove this storyboard ?
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
<input type="hidden" id="total_result_count" value="<?php echo $total_result_count; ?>">
<input type="hidden" id="page_content_count" value="<?php echo PAGE_CONTET_COUNT; ?>">