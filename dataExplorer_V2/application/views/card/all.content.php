   <div class="row" >
        <div class="col-md-12">
        <div class="row">


            <div class="page-title browse_circle_title">

                <?php
                if (!empty($circle)) {
                    echo "<h3><span class='semi-bold'>$circle->name</span></h3>";
                } else {
                    ?><h3><span class="semi-bold">All Circles</span></h3>
                <?php } ?>
            </div>


            <?php
            //*
            //*/
            ?>

<div id="filters_isotope">
  <button data-name="viewed" type="button" class="btn btn-white btn-cons ">
    <i class="fa "></i>Viewed
  </button>
                <button data-name="creation_time" type="button" class="btn btn-white btn-cons active">
    <i class="fa fa-sort-amount-desc"></i>Date Created
  </button>
  <button data-name="autor" type="button" class="btn btn-white btn-cons ">
                    <i class="fa "></i>Author
  </button>
  <button data-name="description" type="button" class="btn btn-white btn-cons ">
    <i class="fa "></i>Name
  </button>
  
</div>
<input type="hidden" id="site_url" value="<?php echo site_url();?>">
<input type="hidden" id="circle_id" value="<?php echo $this->uri->segment(3);?>">
        <ul id="cards_isotope"  class="isotope2 transition">
        <div id="overlay_div" class="overlay2" style="display:none"><img src="<?php echo site_url('assets/img/AjaxLoader2.gif');?>" /></div>
                <?php
        foreach ($cards as &$obj) {
          $obj = (object) $obj;
          ?>
          
            <li class="cell element-item transition"
                data-id="<?php echo $obj->id; ?>"
                        data-name="<?php echo strip_tags($obj->name); ?>"
                        data-description="<?php echo strip_tags($obj->name); ?>"
                data-period="<?php echo $obj->period; ?>"
                data-kpi="<?php echo $obj->kpi; ?>"
                data-order="<?php echo $obj->order; ?>"
                data-autor="<?php echo $obj->autor; ?>"
                data-creation_time="<?php echo $obj->creation_time; ?>"
                data-viewed="<?php echo $obj->viewed; ?>"
                data-count-start="<?php echo count($cards);?>"
                >

                        <div class="m-l-10 flip_card" data-card-id="<?php echo $obj->id; ?>">

                            <a href="<?php echo site_url('card/view/' . $obj->id); ?>" > 
            <div class="tiles white cards text-center pagination-centered <?php echo $obj->type; ?>">
            </div>
                            </a>
            <div class="tiles gray p-t-5 p-b-5  m-b-20" >
                <p class="text-center text-white semi-bold  small-text"> 
                    <a class="white" href="<?php echo site_url('card/view/'.$obj->id);?>">
                        <?php 
                         $title = cut_string($obj->name, 25);
                         if ($obj->disqus_post_count > 0) {
                            $leftB = ' [';
                            $rightB = ']';
                            $comments = $obj->disqus_post_count;
                            echo $title.$leftB.$comments.$rightB;
                         }
                         else
                         {
                            echo $title;
                         }
                       ?>
                </p>
                    </a>
                <?php
                    $card_privacy = ($obj->public == 0)? 'private':'public' ;
                    $circles = "";
                    $info = "";
                    $info .= "Viewed: ".$obj->viewed."<br/>";
                    $info .= "Privacy: ".$card_privacy."<br/>";
                    $info .= "Autor: ".$obj->autor."<br/>";
                    $info .= "Date Created: ".$obj->creation_time."<br/>";

                                    if (!empty($obj->circles)) {
                  $num_circle = count($obj->circles);
                  $i = 0;
                  foreach ($obj->circles as $circle) {
                    if($i < 3)
                      $circles .= cut_string($circle['name'],20)."<br/>";
                    if($i > 3){
                      $circles .= "...";
                      break;
                      }
                    $i++;
                  }
                                    } else {
                  $circles = "Not shared with any circle";
                }
                ?>
                                    <a class="fa fa-retweet flipper" 
                                       data-card-id="<?php echo $obj->id; ?>"
                                       title="flip card" href="#"></a>
                    <a title="<?php echo $circles;?>" class="fa fa-circle-o tooltip-toggle tooltip-right" data-toggle="tooltip" data-placement="bottom"></a>
                    <?php 
                      if($obj->public)
                        echo '<i  class="fa fa-unlock tooltip-toggle tooltip-right" title="Public Card" data-toggle="tooltip" data-placement="bottom" style="margin-right: 20px;"></i>';
                    ?>
                </p>
            </div>

                            <div class="m-l-10 card_flipped" style="display: none">
                                <div class="flip_card"
                                   data-card-id="<?php echo $obj->id; ?>"
                                   data-card-flipped="no"
                                   title="flip card" href="#">
                                    <div class="white text-left flip_div pagination-centered <?php echo $obj->type; ?>">
                                        <p><strong>Author : </strong> <?php echo $obj->autor; ?><br/></p>
                                        <p><strong>Name : </strong> <?php echo $obj->name; ?><br/></p>
                                        <p><strong>Date Created : </strong> <?php echo $obj->creation_time; ?><br/></p>
                                        <p><strong># Viewed : </strong> <?php echo $obj->viewed; ?> </p>
                                    </div>
                                    <div class="tiles gray p-t-5 p-b-5  m-b-20">
                                        <p class="text-center text-white semi-bold  small-text"> 
                                            <?php echo $obj->name; ?>
                                            <a class="fa fa-retweet flipper" 
                                               data-card-id="<?php echo $obj->id; ?>"
                                               title="flip card" href="#"></a>
                                                <!--<a title="<?php //echo $info;   ?>" class="fa fa-info-circle tooltip-toggle tooltip-left" data-toggle="tooltip" data-placement="bottom"></a>-->
                                            <a title="<?php echo $circles; ?>" class="fa fa-circle-o tooltip-toggle tooltip-right" data-toggle="tooltip" data-placement="bottom"></a>                             

                   <!--<a title="<?php //echo $info;   ?>" class="fa fa-info-circle tooltip-toggle tooltip-left" data-toggle="tooltip" data-placement="bottom"></a>-->
                                        </p>    
                                    </div>
                                </div>
                            </div>
                        </div>


            </li>

        <?php } ?>
      </ul>
       <div class="col-md-12 text-center" id="loader_img" style="display:none">
      <img src="<?php echo site_url('assets/img/AjaxLoader2.gif');?>" />
      <div class="clear10"></div>		
      </div>
      <?php if(count($cards)< $total_result_count){?>
      <div class="col-md-12 text-center" id="load_more_btn" style="display:none">
      			<button type="button" class="btn btn-success btn-large font20 border-radious-none" onclick="load_more_allcards();">
                  <i class="fa fa-spinner"></i> Load More
                </button>
      </div>
      <?php }?>
	<div class="clear30"></div>
        </div>
   </div>
</div>
<input type="hidden" id="total_result_count" value="<?php echo $total_result_count; ?>">
<input type="hidden" id="page_content_count" value="<?php echo PAGE_CONTET_COUNT; ?>">