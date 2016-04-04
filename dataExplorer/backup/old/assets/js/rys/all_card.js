$(document).ready(function(e) {
	setTimeout(
    function() {
      $('#load_more_btn').show();
    }, 3000);
});
var start = $('#page_content_count').val();
var record_count = $('#page_content_count').val();
var limit = $('#page_content_count').val();
var total_result_count = $('#total_result_count').val();
var page_content_count = parseInt($('#page_content_count').val());
function load_more_allcards(){
	$('#loader_img').show();
	var site_url = $('input#site_url').val();
	var circle_id = $('input#circle_id').val();
    var action = 'card/load_more_circle_cards/'+circle_id;
	var sort_by = $('#filters_isotope .active').attr('data-name');
	var search_val = $("#search_input") .val();
	cards_isotope_sort = sort_by;
	if($('#filters_isotope .active .fa').hasClass('fa-sort-amount-desc')){
		var sort_order = 'DESC';
		cards_isotope_sortAscending = false;
	}else if($('#filters_isotope .active .fa').hasClass('fa-sort-amount-asc')){
		var sort_order = 'ASC';
		cards_isotope_sortAscending = true;
	}
	else{
		var sort_order = 'DESC';
		cards_isotope_sortAscending = false;
	}
	 $.ajax( {
          url :  site_url + action,
          data: {
                  'sort_by' : sort_by,
                  'sort_order' : sort_order,
				  'start' : start,
				  'title' : search_val,
                  'limit' : limit,
				  'type' : 'append'
                },
          type:"POST",
          success : function(data) {
			  if(data != 'ko'){
				  start +=page_content_count;
				  record_count = record_count+page_content_count;
			  $('#cards_isotope').isotope('destroy');
			  $('#cards_isotope').append( data );
			  $('#cards_isotope').isotope({
					itemSelector: '.element-item',
					transitionDuration: '1s',
					sortAscending: cards_isotope_sortAscending,
					sortBy: cards_isotope_sort ,
					layoutMode: 'masonry',
					getSortData: sort_fct,
					filter: function(){
						var is_ok  = true;
						var creation_time = $(this).attr("data-creation_time");
						var autor = $(this).attr("data-autor");
						//var description = $(this).attr("data-description");
						var name = $(this).attr("data-name");
			
						var search_val = $("#search_input") .val();
						var _creation_time = (creation_time.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						var _autor = (autor.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						var _name = (name.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						//var _description = (description.indexOf(search_val) > -1)?true:false;
						return _creation_time || _autor || _name; //|| _description;
						}
        		});
				$('html, body').animate({
					scrollTop: $(".first_of_new:last").offset().top
				}, 2000);
				total_result_count = $(".element-item:last").attr('data-display-count');
				if(record_count<total_result_count){
					$('#load_more_btn').show();
				}else{
					$('#load_more_btn').hide();
				}
				$(".shareModal select").select2();
			  }else{
				  $('#load_more_btn').hide();
			  }
			  $('#loader_img').hide(); 
		  }
      });
}
function sorted_data_load(){
	$('#overlay_div').show();
	var site_url = $('input#site_url').val();
    var circle_id = $('input#circle_id').val();
    var action = 'card/load_more_circle_cards/'+circle_id;
	var sort_by = $('#filters_isotope .active').attr('data-name');
	var search_val = $("#search_input") .val();
	cards_isotope_sort = sort_by;
	if($('#filters_isotope .active .fa').hasClass('fa-sort-amount-desc')){
		var sort_order = 'DESC';
		cards_isotope_sortAsc = true;
	}else if($('#filters_isotope .active .fa').hasClass('fa-sort-amount-asc')){
		var sort_order = 'ASC';
		cards_isotope_sortAsc = false;
	}
	else{
		var sort_order = 'DESC';
		cards_isotope_sortAsc = true;
	}
	 $.ajax( {
          url :  site_url + action,
          data: {
                  'sort_by' : sort_by,
                  'sort_order' : sort_order,
				  'start' : 0,
				  'title' : search_val,
                  'limit' : record_count,
				  'type' : 'overwrite'
                },
          type:"POST",
          success : function(data) {
			  if(data != 'ko'){
			  $('#cards_isotope').isotope('destroy');
			  $('#cards_isotope').html( data );
			  $('#cards_isotope').isotope({
					itemSelector: '.element-item',
					transitionDuration: '1s',
					sortAscending: cards_isotope_sortAsc,
					sortBy: cards_isotope_sort ,
					layoutMode: 'masonry',
					getSortData: sort_fct,
					filter: function(){
						var is_ok  = true;
						var creation_time = $(this).attr("data-creation_time");
						var autor = $(this).attr("data-autor");
						//var description = $(this).attr("data-description");
						var name = $(this).attr("data-name");
			
						var search_val = $("#search_input") .val();
						var _creation_time = (creation_time.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						var _autor = (autor.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						var _name = (name.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						//var _description = (description.indexOf(search_val) > -1)?true:false;
						return _creation_time || _autor || _name; //|| _description;
						}
        	});
			start = parseInt($('.element-item:last').attr('data-count-start'));
			total_result_count = $(".element-item:last").attr('data-display-count');
				if(record_count<total_result_count){
					$('#load_more_btn').show();
				}else{
					$('#load_more_btn').hide();
				}
				$(".shareModal select").select2();
				cards_isotope();
			}else{
				  $('#load_more_btn').hide();
			}
			$('#overlay_div').hide();
		  }
      });
}