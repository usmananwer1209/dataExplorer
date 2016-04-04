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
        <!---------------------------------------  ADD CARDS --------------------------------------- -->
        <li class="cell element-item transition stamp"
                data-id="0"
                data-description="0"
                data-type="0"
                data-creation_time="0"
                data-public="0"
                data-period="0"
                >
        <a href="<?php echo site_url("card/add"); ?>">
          <div class="m-l-10 ">
            <div class="tiles white cards add-cards text-center pagination-centered">
                <span class="black">+ Add </span>
                <span class="green"> Card</span>
            </div>
            <div class="tiles gray  p-t-5 p-b-5  m-b-20">
                <p class="text-center text-white semi-bold  small-text"> 
                  New
                </p>
            </div>
          </div>
        </a>
        <input type="hidden" name="rec_count_start" id="rec_count_start" value="<?php echo count($objs);?>">
        </li>      
                <?php
                foreach ($objs as $obj) {

          $info = "";
          $info .= "Period:".$obj->period."<br/>";
          $info .= "Type:".$obj->type."<br/>";
          $info .= "Date Created:".$obj->creation_time."<br/>";
          ?>
          <li class="cell element-item transition"
                data-id="<?php echo $obj->id; ?>"
                        data-name="<?php echo strip_tags($obj->name); ?>"
                        data-description="<?php echo strip_tags($obj->name); ?>"
                data-type="<?php echo $obj->type; ?>"
                data-creation_time="<?php echo $obj->creation_time; ?>"
                data-public="<?php echo ($obj->public)?'public':'private'; ?>"
                data-period="<?php echo $obj->period; ?>"
                >

			<a class="fa fa-external-link-square"  
              id="view-card"
              title="view card" href="<?php echo site_url("card/view/".$obj->id);?>" ></a>
            <a class="fa <?php echo ($obj->public)?'fa-unlock':'fa-unlock-alt'; ?>"  
              id="publish-card"
              data-toggle="modal" 
              data-object="card"
              data-modal-id="#publishModal"
              data-card-id="<?php echo $obj->id;?>"
              data-card-public="<?php echo $obj->public; ?>"
              title="publish card" ></a>
          <a class="fa fa-bullhorn" 
              id="share-card"
              data-toggle="modal" 
              data-object="card"
              data-modal-id="#shareModal<?php echo $obj->id;?>"
              data-card-id="<?php echo $obj->id;?>"
              title="share with..."></a>
          <a class="fa fa-minus-square" 
              id="delete-card"
              data-toggle="modal" 
              data-object="card" 
              data-modal-id="#removeModal"
              data-card-id="<?php echo $obj->id;?>"
              title="delete card"></a>
                        <a class="fa fa-retweet flipper"
                           data-card-id="<?php echo $obj->id; ?>"
                           title="flip card" href="#"></a>

                        <div class="m-l-10 flip_card" data-card-id="<?php echo $obj->id; ?>">

                            <a href="<?php echo site_url('card/edit/' . $obj->id); ?>"> 
              <div class="tiles white cards text-center pagination-centered <?php echo $obj->type;?>">
              </div>

            <div class="tiles gray p-t-5 p-b-5  m-b-20">

                <p class="text-center text-white semi-bold  small-text"> 
                                        <?php echo cut_string($obj->name, 25);?>
                </p>
                                </div>
                                </a>

                            <div class="m-l-10 card_flipped" style="display: none">
                                <a class="flip_card"
                                   data-card-id="<?php echo $obj->id; ?>"
                                   data-card-flipped="no"
                                   title="flip card" href="#">
                                    <div class="white text-left flip_div pagination-centered <?php echo $obj->type; ?>">
                                        <p><strong>Author : </strong> <?php echo $obj->author; ?><br/></p>
                                        <p><strong>Name : </strong> <?php echo $obj->name; ?><br/></p>
                                        <p><strong>Date Created : </strong> <?php echo $obj->creation_time; ?><br/></p>
                                        <p><strong># Viewed : </strong> <?php echo $obj->viewed; ?><br/></p>

                                        <!-- <p><strong>Description : </strong> <?php //echo strip_tags((cut_string($obj->description, 150))); ?></p><br/> -->
                                    </div>
                                    <div class="tiles gray p-t-5 p-b-5  m-b-20">
                                        <p class="text-center text-white semi-bold  small-text"> 
                                            <?php echo $obj->name; ?>
                                        </p>    
                                    </div>
                                </a>
            </div>

          </div>


          </li>

        
<div class="modal shareModal fade notif_modals" id="shareModal<?php echo $obj->id;?>" data-id="<?php echo $obj->id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>Share your card</h4>
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
                    <?php echo (card_shared($obj->id,$circle['id']))? 'selected="selected"': '' ?>
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
       <?php if(count($objs) < $total_result_count){?>
      <div class="col-md-12 text-center" id="load_more_btn" style="display:none">
      			<button type="button" class="btn btn-success btn-large font20 border-radious-none" onclick="load_more_mycards();">
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
              <p>Are you sure you want make the card 
                  <span class="public_status">public</span> ?
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
              <div class="alert alert-error-number hide">This card is used in storyboards and could not be deleted </div>
              <p>Are you sure you want to remove the card ?
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