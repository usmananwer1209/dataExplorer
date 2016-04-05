$('#sector').on('change',function(){
		var sector = $('#sector').val();
		var industry_name = $('#industry_name').val();
		var site_url = $('#site_url').val();
		var action = site_url + 'company/get_industry_by_sector';
		var mode = $('#op').val();
		var industry_list ='<option value="0">Select Industry</option>';
		$.ajax( {
          url :  action,
          data: {
                  'sector' : sector
                },
          type:"POST",
          success : function(data) {
			  var json = jQuery.parseJSON(data);
			  	$.each(json, function( index, value ) {
					industry_list += '<option value="'+value.industry+'">'+value.industry+'</option>';
				});
				$('#industry').html(industry_list);
				$('#industry').removeAttr('disabled');
				$('#industry option[value="'+industry_name+'"]').prop('selected',true);
				if(mode == 'edit_'){
					$("#industry").change(); 
				}
		  }
      });
});
$('#industry').on('change',function(){
		var industry = $('#industry').val();
		var site_url = $('#site_url').val();
		var sic_name = $('#sic_name').val();
		var action = site_url + 'company/get_sic_by_industry';
		var mode = $('#op').val();
		var sic_list ='<option value="0">Select SIC</option>';
		$.ajax( {
          url :  action,
          data: {
                  'industry' : industry
                },
          type:"POST",
          success : function(data) {
			  var json = jQuery.parseJSON(data);
			  	$.each(json, function( index, value ) {
					sic_list += '<option value="'+value.sic+'" data-value="'+value.sic_code+'">'+value.sic_code+' - '+value.sic+'</option>';
				});
				$('#sic').html(sic_list);
				$('#sic').removeAttr('disabled');
				$('#sic option[value="'+sic_name+'"]').prop('selected',true);
		  }
      });
});
$(document).ready(function() {
	var mode = $('#op').val();
	if(mode == 'edit_'){
		$("#sector").change(); 
	}
	var $sfield = $('input.autocomplete').autocomplete({
			source: function(request, response){
				var obj =    this.element.attr("data-autocomplete");
				var action = this.element.attr("data-action");
				var label = this.element.attr("data-label");
				var value = this.element.attr("data-value");
				var user_ids = '';
				var guest_ids = '';
				$('input[name="users[]').each(function(i, obj) {
					if(i==0){
						user_ids = $(obj).val();
					}else{
						user_ids += ','+$(obj).val();
					}
				});
				$('input[name="guests[]').each(function(i, obj) {
					if(i==0){
						guest_ids = $(obj).val();
					}else{
						guest_ids += ','+$(obj).val();
					}
				});
				var url = action +'/'+ obj;
				$.post(url, {
					data:request.term,
					user_ids:user_ids,
					guest_ids:guest_ids
				}, function(data){
					response($.map(data, function(results) {
						return {
							label:results[label],
							value: results[value],
							descr: results['email']
						};
					}));
				}, "json");  
			},
			focus: function(event, ui) {
				event.preventDefault();
				$(this).val(ui.item.label);
			},
			select: function(event, ui) {
				event.preventDefault();
				var hidden_name = $(this).attr("for");
				var hidden = $('[type=hidden][for="'+hidden_name+'"]');
				$(this).val(ui.item.label);
				$(this).attr('data-desc', ui.item.descr);
				$(hidden).val(ui.item.value);
			},
			minLength: 2,
			autofocus: false
	});
});
$('#add_user').on('click',function(){
		var name = $('.autocomplete[name="user"]').val();
		var email = $('.autocomplete[name="user"]').attr('data-desc');
		var id = $('.autocomplete[name="user"]').prev('.ui-helper-hidden-accessible').html();
		if(name != '' && email !='' && id!=''){
			var div_class = 'user'+id;
			var html_to_add = '<div class="'+div_class+'" class="col-md-12 no-padding">'+
									'<div class="col-md-3">'+
										'<input type="hidden" name="users[]"  value="'+id+'">'+name+
										' <a href="javascript:void(0)" data-toggle="modal" data-target="#removeUserModal" onclick="remove_user(\''+div_class+'\');" class="fa fa-minus-circle"></a>'+
									'</div>'+
									'<div class="col-md-6">'+email+'</div>'+
									'<div class="col-md-3">'+
										'<div class="checkbox check-success">'+
											'<input type="checkbox" name="enable_load_data'+id+'" value="1" id="enable_load_data'+id+'">'+
											'<label for="enable_load_data'+id+'"></label>'+
										'</div>'+
									'</div>'+
									'<div class="clear"></div>'+
							   '</div>';
			$('#users_of_company').append(html_to_add);
			$('.autocomplete[name="user"]').val('');
			$('.autocomplete[name="user"]').attr('data-desc','');
			$('.autocomplete[name="user"]').prev('.ui-helper-hidden-accessible').html('');
		}
});
$('#add_guest').on('click',function(){
		var name = $('.autocomplete[name="guest"]').val();
		var email = $('.autocomplete[name="guest"]').attr('data-desc');
		var id = $('.autocomplete[name="guest"]').prev('.ui-helper-hidden-accessible').html();
		if(name != '' && email !='' && id!=''){
			var div_class = 'guest'+id;
			var html_to_add = '<div class="'+div_class+'" class="col-md-12 no-padding">'+
									'<div class="col-md-3">'+
										'<input type="hidden" name="guests[]"  value="'+id+'">'+name+
										' <a href="javascript:void(0)" data-toggle="modal" data-target="#removeUserModal" onclick="remove_user(\''+div_class+'\');" class="fa fa-minus-circle"></a>'+
									'</div>'+
									'<div class="col-md-9">'+email+'</div>'+
									'<div class="clear"></div>'+
							   '</div>';
			$('#guests_of_company').append(html_to_add);
			$('.autocomplete[name="guest"]').val('');
			$('.autocomplete[name="guest"]').attr('data-desc','');
			$('.autocomplete[name="guest"]').prev('.ui-helper-hidden-accessible').html('');
		}
});
function remove_user(div_class){
	$('#user_to_remove').val(div_class);
}
function remove_user_confirm(){
	var div_class  = $('#user_to_remove').val();
	$('.'+div_class).html('');
	$('#removeUserModal').modal('hide');
}
$('#sic').on('change',function(){
		var sic_code = $('#sic option:selected').attr('data-value');
		$('#sic_code').val(sic_code);
});
$('.add-company-form').on('submit',function(){
	var sector = $('#sector').val();
	var industry = $('#industry').val();
	var name = $('#name').val();
	var sic = $('#sic').val();
	var state = $('#state').val();
	if(sector==0){
		$('#message_div .alert-danger').html('Please Select Sector!');
		$('#message_div').show();
		return false;
	}else if(industry==0){
		$('#message_div .alert-danger').html('Please Select Industry!');
		$('#message_div').show();
		return false;
	}else if(name==''){
		$('#message_div .alert-danger').html('Please Enter Name!');
		$('#message_div').show();
		return false;
	}else if(state==0){
		$('#message_div .alert-danger').html('Please Select State!');
		$('#message_div').show();
		return false;
	}else if(sic==0){
		$('#message_div .alert-danger').html('Please Select SIC!');
		$('#message_div').show();
		return false;
	}else{
		return true;
	}
});