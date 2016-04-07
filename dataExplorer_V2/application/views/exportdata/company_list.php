<?php if($is_order_group_null == "No"){?>

<div id="accordion-first" class="clearfix">
	<div class="accordion" id="accordion2">
	<div class="accordion-group">
	<div class="accordion-heading">
	<a class="accordion-toggle <?php echo $collaps . $isactive ?>" id="accordion<?php echo  $idValue . $index ?>" data-toggle="collapse" data-parent="#accordion<?php echo  $idValue . $index ?>" href="#collapse<?php echo  $idValue . $index ?>">
	<em class="icon-fixed-width fa <?php echo  $fa_em_icon_class ?> "></em>
	</a>
	<div class="checkbox-inline check-warning blue-custom-padd-1">
	<input onchange="selectBox(this.id,'<?php echo  $SelectedList ?>')" <?php echo  $checked ?> type="checkbox" id="<?php echo $idValue . '_' . $index ?>">
	<label for="<?php echo  $idValue . '_' . $index ?>"><?php echo $outer_order_group ?></label>
	</div></div>
	<div id="collapse<?php echo  $idValue . $index ?>" class="accordion-body collapse <?php echo  $collapsinner ?>">
	<div class="col-md-12">
	<div class="accordion-inner accordion-custom-padd" id="ListOfDropdown">
<?php 
                $index_inner = 0;
                foreach ($get_inner as $inner) {
                    $checked = '';
                    $fa_em_icon_class = 'fa-plus';
                    $isactive = ' ';
                    if ($selectedValue == $inner->$name) {
                        $checked = 'checked';
                        $collapsouter = '';
                        $collapsinner = 'in';
                        $fa_em_icon_class = 'fa-minus';
                        $isactive = ' active';
                        //}
                        //if($selectedValue==$inner->$name){ $checked='checked';
                        $slcdvalueId = $idValue . "_" . $index . "_" . $index_inner;
                    }
?>
                  <div class="form-group blue-form-group-1">
			<div class="checkbox-inline">
				<input onchange="selectBox(this.id,'<?php echo  $SelectedList ?>');" <?php echo  $checked ?> class="checkboxinner_<?php echo  $idValue . '_' . $index ?>" type="checkbox" id="<?php echo $idValue . '_' . $index . '_' . $index_inner ?>" value="<?php echo  $inner->$Id ?>">
				<label for="<?php echo  $idValue . '_' . $index . '_' . $index_inner ?>" id="lbl<?php echo  $idValue . '_' . $index . '_' . $index_inner ?>"><?php echo  $inner->$name ?>
				</label>
			</div>
		</div>
            <?php
                    $index_inner++;
                }
         
                ?>
            </div></div></div></div>
        <div style="display:block;"></div></div></div>
        
<?php } else {?>
        
        
        <div class="form-group blue-form-group-1">
			<div class="checkbox-inline">
				<input onchange="selectedboxflat(this.id);" <?php echo $checked ?> type="checkbox" id="<?php echo  $idValue . '_' . $index ?>" value="<?php echo  $outer_term_id ?>">
                                <label for="<?php echo  $idValue . '_' . $index ?>" id="lbl<?php echo  $idValue . '_' . $index ?>"><?php echo  $outer_term_id ?></label>
			</div>
		</div>

              </div></div></div></div>
            <!-- Dont Remove this Div Plzzzzz -->
                <div style="display:block;"></div></div></div>
<?php }?>