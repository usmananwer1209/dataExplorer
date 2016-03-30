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
function load_more_mystoryboard(){
	$('#loader_img').show();
	var site_url = $('input#site_url').val();
	var search_val = $("#search_input") .val();
    var action = 'storyboard/load_more_my_storyboards';
	var sort_by = $('#filters_isotope2 .active').attr('data-name');
	cards_isotope_sort2 = sort_by;
	if($('#filters_isotope2 .active .fa').hasClass('fa-sort-amount-desc')){
		var sort_order = 'DESC';
		cards_isotope_sortAscending2 = false;
	}else if($('#filters_isotope2 .active .fa').hasClass('fa-sort-amount-asc')){
		var sort_order = 'ASC';
		cards_isotope_sortAscending2 = true;
	}
	else{
		var sort_order = 'DESC';
		cards_isotope_sortAscending2 = false;
	}
	 $.ajax( {
          url :  site_url + action,
          data: {
                  'sort_by' : sort_by,
                  'sort_order' : sort_order,
				  'start' : start,
                  'limit' : limit,
				  'title' : search_val,
				  'type' : 'append'
                },
          type:"POST",
          success : function(data) {
			  if(data != 'ko'){
				start +=page_content_count;
				record_count = record_count+page_content_count;
			  $('#cards_isotope2').isotope('destroy');
			  $('#cards_isotope2').append( data );
			  $('#cards_isotope2').isotope({
					itemSelector: '.element-item',
					stamp: '.stamp',
					transitionDuration: '1s',
					sortAscending: cards_isotope_sortAscending2,
					sortBy: cards_isotope_sort2,
					layoutMode: 'masonry',
					getSortData: sort_fct2,
					filter: function(){
						var is_ok  = true;
						var card_new = $(this).attr("data-id");
						if(card_new == "0" || card_new == 0)
							return true
						var creation_time = $(this).attr("data-creation_time");
						var name = $(this).attr("data-name");
						//var description = $(this).attr("data-description");
						var public_ = $(this).attr("data-public");
						var period = $(this).attr("data-period");
			
						var card_new_  = (card_new_ == "0")?true:false;
						var search_val = $("#search_input") .val();
						var _creation_time = (creation_time.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						var _name = (name.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						//var _description = (description.indexOf(search_val) > -1)?true:false;
						var _public_ = (public_.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						var _period = (period.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						return _creation_time || _name  || _public_  || _period; //|| _description  ;
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
    var action = 'storyboard/load_more_my_storyboards';
	var sort_by = $('#filters_isotope2 .active').attr('data-name');
	var search_val = $("#search_input") .val();
	cards_isotope_sort2 = sort_by;
	if($('#filters_isotope2 .active .fa').hasClass('fa-sort-amount-desc')){
		var sort_order = 'DESC';
		cards_isotope_sortAsc2 = true;
	}else if($('#filters_isotope2 .active .fa').hasClass('fa-sort-amount-asc')){
		var sort_order = 'ASC';
		cards_isotope_sortAsc2 = false;
	}
	else{
		var sort_order = 'DESC';
		cards_isotope_sortAsc2 = true;
	}
	 $.ajax( {
          url :  site_url + action,
          data: {
                  'sort_by' : sort_by,
                  'sort_order' : sort_order,
				  'start' : 0,
                  'limit' : record_count,
				  'title' : search_val,
				  'type' : 'overwrite'
                },
          type:"POST",
          success : function(data) {
			  if(data != 'ko'){
			  $('#cards_isotope2').isotope('destroy');
			  $('#cards_isotope2').html( data );
			  $('#cards_isotope2').isotope({
					itemSelector: '.element-item',
					stamp: '.stamp',
					transitionDuration: '1s',
					sortAscending: cards_isotope_sortAsc2,
					sortBy: cards_isotope_sort2,
					layoutMode: 'masonry',
					getSortData: sort_fct2,
					filter: function(){
						var is_ok  = true;
						var card_new = $(this).attr("data-id");
						if(card_new == "0" || card_new == 0)
							return true
						var creation_time = $(this).attr("data-creation_time");
						var name = $(this).attr("data-name");
						//var description = $(this).attr("data-description");
						var public_ = $(this).attr("data-public");
						var period = $(this).attr("data-period");
			
						var card_new_  = (card_new_ == "0")?true:false;
						var search_val = $("#search_input") .val();
						var _creation_time = (creation_time.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						var _name = (name.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						//var _description = (description.indexOf(search_val) > -1)?true:false;
						var _public_ = (public_.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						var _period = (period.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						return _creation_time || _name  || _public_  || _period; //|| _description  ;
						}
			});
			start = parseInt($('#rec_count_start').val());
			total_result_count = $(".element-item:last").attr('data-display-count');
			if(record_count<total_result_count){
					$('#load_more_btn').show();
			}else{
					$('#load_more_btn').hide();
			}
			$(".shareModal select").select2();
			cards_isotope2();
		   }else{
				  $('#load_more_btn').hide();
		   }
		   $('#overlay_div').hide();
	  }
   });
}