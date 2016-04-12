$(document).ready(function(e) {
	$("#template").msDropDown();
	$("#years").msDropDown();
	
    $('#template').change(function(){
		var template = $('#template').val();
		if(template!=0 && $('.years-buttons').length){
			$('#template_changeModal').modal('show');
		}else{
			load_area();
		}
	});
	$('#years').change(function(){
		load_area();
	});
	var op = $('#op').val();
	if(op == 'edit_'){
		$("input[name='quick_list[]'" ).each(function( index ) {
			var year = $(this).val();
			var table_id = '#'+$(this).attr('data-id');
			create_quick_link(table_id, year);
		});
	}
	$('.auto').autoNumeric('init', {vMin:'-9999999999999999', vMax: '9999999999999999', aPad: false});
	$('#template_changeModal #yes_button').click(function(e) {
		$('#tables_div').html(''); 
		$('#data_set_title').val('');
		$('#years_div').html(''); 
        load_area();
		$('#template_changeModal').modal('hide');
    });
	$('#removeSheetModal #yes_button_sheet').click(function(e) {
		var table_id = $('#table_to_remove').val();
		$(table_id).remove();
		$(table_id+'quick_button_cross').remove();
		$(table_id+'quick_button').remove();
		$(table_id+'quick_years').remove();
		$('#removeSheetModal').modal('hide');
		if(!$('.years-buttons').length){
			$('#description_box').hide();
			$('.data-set-submit-button').attr('disabled',true);
		}else{
			$('.years-buttons:eq(0)').click();
		}
	});
	$('#import').click(function() {
		var template = $('#template').val();
		if(template != 0){
			$('#csv_to_import').trigger('click');
		}else{
			$('#message_div .alert-danger').html('Please select template first');
			$('#message_div').show();
			$("html, body").animate({ scrollTop: "1px" });
		}
	});
	$('#export').click(function() {
		var template = $('#template').val();
		if(template != 0 && $('.years-buttons').length){
			var data_submit = $('.add-dataset-form').serialize();
			$.post( site_url + 'dataset/export_csv', data_submit ).done(function( data ) {
				window.open(site_url+'data/dataset_csv/'+data,'_blank');
			  });
		}else{
			$('#message_div .alert-danger').html('Please add data to export');
			$('#message_div').show();
			$("html, body").animate({ scrollTop: "1px" });
		}
	});
});
$(function() {
	$('#file_upload_form').on('change', 'input[type="file"]' ,function() {
		upload_csv();
	});
});
function upload_csv() {
        $.ajaxFileUpload({
            url             :site_url + 'dataset/upload_csv', 
            secureuri       :false,
            fileElementId   :'csv_to_import',
            dataType: 'JSON',
            success : function (data)
            {
               var obj = jQuery.parseJSON(data);                
                if(obj.status == 'success'){
					var template = $('#template').val();
					var template_name = $('#template option:selected').html();
					$.ajax({
						url : site_url + 'dataset/read_csv',
						data: {template:template},
						type:"POST",
						success : function(data){
							$('#tables_div').html(data); 
							$('#data_set_title').val(template_name);
							$('.auto').autoNumeric('init', {vMin:'-9999999999999999', vMax: '9999999999999999', aPad: false});
							$('.data-set-submit-button').removeAttr('disabled');
							$('#years_div').html('');
							$("input[name='quick_list[]'" ).each(function( index ) {
								var year = $(this).val();
								var table_id = '#'+$(this).attr('data-id');
								create_quick_link(table_id, year);
							});
							$('.years-buttons:eq(0)').click();
							$('#years option:eq(1)').attr('selected',true);
							$('#file_upload_form').html('<input type="file" name="csv_to_import" id="csv_to_import"/>');
						}
					});
				}else{
					console.log(obj);
				}
            }
        });
        return false;
}
function load_area(){
	var template = $('#template').val();
	var template_name = $('#template option:selected').html();
	var years = $('#years').val();
	var table_id = '#table'+template+'_'+years;
	if(template!=0 && years!=0 && !$(table_id).length){
		$.ajax({
			url : site_url + 'dataset/get_dataset_table',
			data: {template:template, year: years},
			type:"POST",
			success : function(data){
				if (data != "ko"){
					$('#tables_div table').hide();
					$('#tables_div').append(data); 
					$('#data_set_title').val(template_name);
					create_quick_link(table_id, years);
					$('.auto').autoNumeric('init', {vMin:'-9999999999999999', vMax: '9999999999999999', aPad: false});
					$('.data-set-submit-button').removeAttr('disabled');
				}
			}
    	});
		
	}else if($(table_id).length){
		$('#tables_div table').hide();
		$(table_id).show();
	}
}
function create_quick_link(table_id, years){
	var button_id = table_id.replace("#","");
	var html  = '<a id="'+button_id+'quick_button_cross" class="btn years-button-cross" onClick="quick_remove(\''+table_id+'\','+years+')" >×</a><a id="'+button_id+'quick_button" class="btn years-buttons" onClick="quick_action(\''+table_id+'\')">'+years+'</a><input id="'+button_id+'quick_years" type="hidden" name="year[]" value="'+years+'">'; 
	$('#years_div').append(html); 
}
function quick_action(table_id){
	$('#tables_div table').hide();
	$(table_id).show();
}
function edit_it(tr_id, description){
	set_value();
	$('#description_box').html(description).show();
	$('#'+tr_id).addClass('active');
	$('#'+tr_id+' span').hide();
	$('#'+tr_id+' input').show();
	//$('#'+td_id+' input').focus();
}
function set_value(){
	$( "tr.active .edit-able" ).each(function( index ) {
		$(this).children('span').html($(this).children('input').val()).show();
		$(this).children('input').hide();			
	});
	$( "tr.active").removeClass('active');
}
function quick_remove(table_id,year){
	$('#removeSheetModal').modal('show');
	$('#year_to_remove').html(year);
	$('#removeSheetModal #table_to_remove').val(table_id);
}
function this_form_submit(){
	var template = $('#template').val();
	var years = $('#years').val();
	var data_set_title = $('#data_set_title').val();
	var data_set_description = $('#data_set_description').val();
	if(data_set_description != '' && data_set_title!='' && template!=0 && years!=0 && $('.years-buttons').length){
		$('.add-dataset-form').submit();
	}else if(data_set_description == ''){
		$('#message_div .alert-danger').html('Please enter description');
		$('#message_div').show();
		$("html, body").animate({ scrollTop: "10px" });
	}
	else if(data_set_title == ''){
		$('#message_div .alert-danger').html('Please enter title');
		$('#message_div').show();
		$("html, body").animate({ scrollTop: "10px" });
	}
	else if(template == 0){
		$('#message_div .alert-danger').html('Please select template');
		$('#message_div').show();
		$("html, body").animate({ scrollTop: "10px" });
	}
	else if(years == 0){
		$('#message_div .alert-danger').html('Please select year');
		$('#message_div').show();
		$("html, body").animate({ scrollTop: "10px" });
	}
}
$(document).keypress(function(e) {
    if(e.which == 13) {
        set_value();
    }
});