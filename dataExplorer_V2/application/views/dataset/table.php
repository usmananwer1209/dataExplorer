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
        <?php foreach($terms as $term){?>
            <tr id="<?php echo $template->id.'_'.$year.'_'.$term->term_id;?>" onclick="edit_it('<?php echo $template->id.'_'.$year.'_'.$term->term_id;?>','<?php echo str_replace("'","&acute;",$term->description);?>');">
                <td>
                   <?php echo $term->name;?>
                </td>
                <td class="edit-able">
                    <span></span>
                    <input type="text" name="Q1<?php echo $year.'_'.$term->term_id;?>" class="form-control auto" style="display:none">
                </td>
                <td class="edit-able">
                    <span></span>
                    <input type="text" name="Q2<?php echo $year.'_'.$term->term_id;?>" class="form-control auto" style="display:none">
                </td>
                <td class="edit-able">
                    <span></span>
                    <input type="text" name="Q3<?php echo $year.'_'.$term->term_id;?>" class="form-control auto" style="display:none">
                </td>
                <td class="edit-able">
                    <span></span>
                    <input type="text" name="Q4<?php echo $year.'_'.$term->term_id;?>" class="form-control auto" style="display:none">
                </td>
                <td class="edit-able">
                    <span></span>
                    <input type="text" name="FY<?php echo $year.'_'.$term->term_id;?>" class="form-control auto" style="display:none">
                </td>
            </tr>
           <?php }?>
        </tbody>
</table>