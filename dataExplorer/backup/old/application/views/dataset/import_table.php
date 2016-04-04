<?php foreach($years as $year){ ?>
<input type="hidden" name="quick_list[]" value="<?php echo $year;?>" data-id="table<?php echo $template->id.'_'.$year;?>">
<table class="table table-bordered table-condensed table-hover table-striped dataset-table" id="table<?php echo $template->id.'_'.$year;?>">
		<thead>
			<tr>
                <th><?php echo $template->name;?></th>
                <th>Q1 <?php echo $year;?></th>
                <th>Q2 <?php echo $year;?></th>
                <th>Q3 <?php echo $year;?></th>
                <th>Q4 <?php echo $year;?></th>
                <th>FY <?php echo $year;?></th>
            </tr>
		</thead>
        <tbody>
        <?php 
			$i=1;
			foreach($terms as $term){?>
            <tr id="<?php echo $template->id.'_'.$year.'_'.$term->term_id;?>" onclick="edit_it('<?php echo $template->id.'_'.$year.'_'.$term->term_id;?>','<?php echo str_replace("'","&acute;",$term->description);?>');">
                <td>
                   <?php echo $term->name;?>
                </td>
                <td class="edit-able">
                    <span><?php if(@$csv[$i][$year.'Q1']){ echo number_format($csv[$i][$year.'Q1']); }?></span>
                    <input value="<?php if(@$csv[$i][$year.'Q1']){echo $csv[$i][$year.'Q1'];}?>" type="text" name="Q1<?php echo $year.'_'.$term->term_id;?>" class="form-control auto" style="display:none">
                </td>
                <td class="edit-able">
                    <span><?php if(@$csv[$i][$year.'Q2']){ echo number_format($csv[$i][$year.'Q2']);}?></span>
                    <input value="<?php if(@$csv[$i][$year.'Q2']){echo $csv[$i][$year.'Q2'];}?>" type="text" name="Q2<?php echo $year.'_'.$term->term_id;?>" class="form-control auto" style="display:none">
                </td>
                <td class="edit-able">
                    <span><?php if(@$csv[$i][$year.'Q3']){echo number_format($csv[$i][$year.'Q3']);}?></span>
                    <input value="<?php if(@$csv[$i][$year.'Q3']){ echo $csv[$i][$year.'Q3'];}?>" type="text" name="Q3<?php echo $year.'_'.$term->term_id;?>" class="form-control auto" style="display:none">
                </td>
                <td class="edit-able">
                    <span><?php if(@$csv[$i][$year.'Q4']){echo number_format($csv[$i][$year.'Q4']);}?></span>
                    <input value="<?php if(@$csv[$i][$year.'Q4']){ echo $csv[$i][$year.'Q4'];}?>" type="text" name="Q4<?php echo $year.'_'.$term->term_id;?>" class="form-control auto" style="display:none">
                </td>
                <td class="edit-able">
                    <span><?php if(@$csv[$i][$year.'FY']){echo number_format($csv[$i][$year.'FY']);}?></span>
                    <input value="<?php if(@$csv[$i][$year.'FY']){ echo $csv[$i][$year.'FY'];}?>" type="text" name="FY<?php echo $year.'_'.$term->term_id;?>" class="form-control auto" style="display:none">
                </td>
            </tr>
           <?php 
		   $i++;
		   }?>
        </tbody>
</table>
<?php }?>