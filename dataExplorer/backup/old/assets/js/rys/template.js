function edit_template(id, action){
		$.ajax({
			url : site_url + 'template/get_template',
			data: {id:id},
			type:"POST",
			success : function(data){
				if (data != "ko"){
					var json = jQuery.parseJSON(data);
						var id = json['id'];
						var name = json['name'];
						var description = json['description'];
						var kpis = json['kpis'];
						var public = json['public'];
						var user = json['user'];
						kpis = kpis.replace(/"/g, ""); 
						var array_kpis = kpis.split(',');
						$('#list_description').val(description);
						$('input[name="list_name"]').val(name);
						$('#template_id').val(id);
						if(public == 1){
							$('#kpis_public_list').attr('checked',true);
						}
						$( ".kpi_checkbox" ).each(function( index ) {
							if($.inArray($(this).val(), array_kpis)>=0){
						  		$( this ).attr('checked',true);
								update_checkboxes($(this).parents('ul.sec_lvl'), true, 'kpi_checkbox', 'cat_checkbox');
								$(this).parents('ul.sec_lvl').removeClass('collapsed').addClass('expanded').show();
								$(this).parents('ul.sec_lvl').prev('.tree_element').children('a').removeClass('expand').addClass('collapse');
								$(this).parents('ul.sec_lvl').prev('.tree_element').children('a').children('i').removeClass('fa-plus-square').addClass('fa-minus-square');
							}
						});
						//console.log(array_kpis);
						$(array_kpis).each(function(index,value) {
                            var required_position = index;
							var current_position = $('select[name="kpis2"] option[value="'+value+'"]').index();
							var $op = $('select[name="kpis2"] option[value="'+value+'"]');
							if(current_position < required_position){
								while (current_position < required_position) {
										$op.last().next().after($op);
										current_position++;
								}
							}else if(current_position > required_position){
								while (current_position > required_position) {
										$op.first().prev().before($op);
										current_position--;
								}
							}
                        });
						$('#builder_mode').val(1);
						$('#builder_mode_id').val(id);
						$('#builder_mode_action').val(action);
				}
			}
    	});
		if(action == 'hide'){
			$('#kpi_list_builder .save_list').hide();
			$('.cat_checkbox').attr('disabled',true);
			$('.kpi_checkbox').attr('disabled',true);
			$('.ui-autocomplete-input[name="kpi_name2"]').attr('disabled',true);
			$('#kpis_last_name').attr('disabled',true);
			$('#list_description').attr('disabled',true);
			$('#kpis_public_list').attr('disabled',true);
			$('.add_kpi .clear_all').hide();
			$('.add_kpi .select_all').hide();
			$('.add_kpi .move_down').hide();
			$('.add_kpi .move_up').hide();
			$('.kpis_by_sector #uncheck_all_kpis').hide();
			$('.add_kpi .add_to_select').hide();
			$('.company_name_container').hide();
		}
}
function clone_template(id){
	$('#clone_template_id').val(id);
	$.ajax({
			url : site_url + 'template/clone_template_name',
			data: {id:id},
			type:"POST",
			success : function(data){
				if (data != "ko"){
					  $("#clone_template_name").html(data);
				}else{
					$('#createCloneModal').modal('hide');
				}
			}
    	});
	
}
function clone_template_confirm(){
	var id = $('#clone_template_id').val();
	$.ajax({
			url : site_url + 'template/clone_template',
			data: {id:id},
			type:"POST",
			success : function(data){
				if (data != "ko"){
					  message = '<div class="alert alert-success">Clone have been created Successfully.</div>';
					  $('#parks ul').html(data);
					  $('#parks ul li').css("display","block");
					  $('#parks ul li').css("opacity","1");
					  $('#parks').mixitup('remix');
					  $("#messages").html(message);
					  $('#createCloneModal').modal('hide');
				}
			}
    	});
}
$('#kpi_list_builder').on('hidden.bs.modal', function () {
  	$('#empty_kpis_list').click();
	$('#kpis_last_name[name="list_name"]').val('');
	$('#list_description').val('');
	$('ul.sec_lvl').removeClass('expanded').addClass('collapsed').hide();
	$('.tree_element').children('a').removeClass('collapse').addClass('expand');
	$('.tree_element').children('a').children('i').removeClass('fa-minus-square').addClass('fa-plus-square');
	$('#kpi_list_builder .save_list').show();
	$('.company_name_container').show();
	$('.cat_checkbox').attr('disabled',false);
	$('.kpi_checkbox').attr('disabled',false);
	$('.ui-autocomplete-input[name="kpi_name2"]').attr('disabled',false);
	$('#kpis_last_name').attr('disabled',false);
	$('#list_description').attr('disabled',false);
	$('#kpis_public_list').attr('disabled',false);
	$('.add_kpi .clear_all').show();
	$('.add_kpi .select_all').show();
	$('.add_kpi .move_down').show();
	$('.add_kpi .move_up').show();
	$('.kpis_by_sector #uncheck_all_kpis').show();
	$('.add_kpi .add_to_select').show();
});
$('#add_new_template').click(function(){
	$('#builder_mode').val(0);
});
