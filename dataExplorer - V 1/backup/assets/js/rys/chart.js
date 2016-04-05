// gonna get a popup window for each card, but that's ok

try {
    var log = log4javascript.getLogger("main");
    var appender = new log4javascript.PopUpAppender();
    log.addAppender(appender);
}
catch (err) {
    //nop
}

active_chart = '';

/* efj 15-12-10 these have been moved to card_data
years = [];
quarters = [];
*/

active_segments = 'year';
active_calc = $('#calc_type').val();
active_report = $('#reportType').val();
segments_switch = 0;
active_peer = $('#includeAllPeers').val();

plotbands = [{ // Dangerous
            from: -100,
            to: 1.8,
            color: 'rgba(0,0, 0, 0.7)',
            label: {
              text: 'Dangerous',
              style: {
                color: '#606060'
              }
            }
          }, { // On Alert
            from: 1.8,
            to: 3,
            color: 'rgba(0,0,0, 0.5)',
            label: {
              text: 'On Alert',
              style: {
                color: '#606060'
              }
            }
          }, { // Safe
            from: 3,
            to: 100,
            color: 'rgba(0,0,0,0.3)',
            label: {
              text: 'Safe',
              style: {
                color: '#606060'
              }
            }
          }];

/* efj 15-12-10 no longer used due to async problems with displaying charts
$.ajax({
  url :  site_url + 'card/get_all_periods',
  type:"GET",
  dataType: 'json',
  error : function(){
      log.error('ajax get_all_periods failed');
    years = ['2009', '2010', '2011', '2012', '2013', '2014', '2015'];
    quarters = ['2009Q1', '2009Q2', '2009Q3', '2009Q4',
                '2010Q1', '2010Q2', '2010Q3', '2010Q4',
                '2011Q1', '2011Q2', '2011Q3', '2011Q4',
                '2012Q1', '2012Q2', '2012Q3', '2012Q4',
                '2013Q1', '2013Q2', '2013Q3', '2013Q4',
                '2014Q1', '2014Q2', '2014Q3', '2014Q4',
                '2015Q1', '2015Q2', '2015Q3'];
  },
  success : function(data) {
      log.debug('ajax get_all_periods succeeded');
    for(var i = 0; i < data.length; i++)
    {
      if(data[i].indexOf('Q') == -1)
        years.push(data[i]);
      else
        quarters.push(data[i]);
    }
  }
});
*/

$(function() {

    $('body').on('click', 'button#year', function(){
      $(this).addClass('disabled');
      $('button#quarter').removeClass('disabled');
      active_segments = 'year';
      buildChart(active_chart);
    });
    $('body').on('click', 'button#quarter', function(){
      $(this).addClass('disabled');
      $('button#year').removeClass('disabled');
      active_segments = 'quarter';
      buildChart(active_chart);
    });
    $('#layout-condensed-toggle').click(function(){
      $(window).resize();
    });
    $('body').on('change', 'input#disable_toggle', function(){
      if($('input#disable_toggle').is(':checked')){
        $('#period_buttons').addClass('disabled');
        $('input#hide_toggle').val('1');
        if($('input.ios').prop('checked')) {
          active_segments = 'year';
          segments_switch.toggle();
          buildChart(active_chart);
        }
      }
      else{
        $('#period_buttons').removeClass('disabled');
        $('input#hide_toggle').val('0');
      }
    });

    $('body').on('click', 'ul.companies_list a', function(e){
		//alert('there');
        e.preventDefault();
		
        var active_a = $('ul.companies_list li a.company.active');
        if(active_a.parent().is( "strong" ) ) 
            active_a.unwrap();
        $('ul.companies_list li a.company').removeClass('active');

        var message;
        var myRegExp =/^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;
        var urlToValidate = $(this).attr('href');
        if (myRegExp.test(urlToValidate)){
            window.open($(this).attr('href'));
            return false;
        }

        $(this).addClass('active').wrap('<strong></strong>');
        $(this).focus();
        $('#company_text').text(cut_string($(this).text(), 20));
        if($('input#active_company').val()!=$(this).attr('data'))
        {
            $('input#active_company').val($(this).attr('data'));
            buildChart(active_chart);
        }
    });
    $('body').on('click', 'ul.kps_list a', function(e){
		//alert('there');
        e.preventDefault();
		
        var active_a = $('ul.kps_list li a.kps.active');
        if(active_a.parent().is( "strong" ) ) 
            active_a.unwrap();
        $('ul.kps_list li a.kps').removeClass('active');

        $(this).addClass('active').wrap('<strong></strong>');
        $(this).focus();
        $('#kps_text').text(cut_string($(this).text(), 20));
        if($('input#active_kps').val()!=$(this).attr('data'))
        {
            $('input#active_kps').val($(this).attr('data'));
            buildChart(active_chart);
        }
    });
	$('body').on('click', 'ul.xaxes_list a', function(e){
		//alert('there');
        e.preventDefault();
		
        var active_a = $('ul.xaxes_list li a.xaxes.active');
        if(active_a.parent().is( "strong" ) ) 
            active_a.unwrap();
        $('ul.xaxes_list li a.xaxes').removeClass('active');

        $(this).addClass('active').wrap('<strong></strong>');
        $(this).focus();
        $('#xaxes_text').text(cut_string($(this).text(), 20));
        if($('input#active_xaxes').val()!=$(this).attr('data'))
        {
            $('input#active_xaxes').val($(this).attr('data'));
            buildChart(active_chart);
        }
    });
	$('body').on('click', 'ul.yaxes_list a', function(e){
		//alert('there');
        e.preventDefault();
		
        var active_a = $('ul.yaxes_list li a.yaxes.active');
        if(active_a.parent().is( "strong" ) ) 
            active_a.unwrap();
        $('ul.yaxes_list li a.yaxes').removeClass('active');

        $(this).addClass('active').wrap('<strong></strong>');
        $(this).focus();
        $('#yaxes_text').text(cut_string($(this).text(), 20));
        if($('input#active_yaxes').val()!=$(this).attr('data'))
        {
            $('input#active_yaxes').val($(this).attr('data'));
            buildChart(active_chart);
        }
    });
    $('body').on('click', '#kpi_options ul.dropdown-menu', function(e){
        e.stopPropagation();
    });
    $('body').on('click change', '#kpi_options ul.dropdown-menu input[type="checkbox"]', function(e){
      e.stopPropagation();
      if($(this).is(':checked')) {
        var type = $(this).val();
        if(type == 'column')
          var other_checkbox = $(this).parent().next().find('input[type="checkbox"]');
        if(type == 'line')
          var other_checkbox = $(this).parent().prev().find('input[type="checkbox"]');
        if(other_checkbox.is(':checked'))
          other_checkbox.prop('checked', false);
      }
      update_kpis_types();
	});
});

function update_kpis_types() {
  var line_kpis = '';
  var column_kpis = '';
  $('#kpi_options table tbody tr').each(function(i) {
    var checked_elemt = $(this).find('input:checked');
    if(checked_elemt.val()) {
      var type = checked_elemt.val();
      var kpi = checked_elemt.attr('name').replace('option_', '');
      if(type == 'line')
        line_kpis += kpi+',';
      if(type == 'column')
        column_kpis += kpi+',';
    }
  });
  //alert(line_kpis+ ' ---- ' + column_kpis);
  $('input#line_kpis').val(line_kpis);
  $('input#column_kpis').val(column_kpis);
}

function update_kpi_options_checkboxes() {
  $('#kpi_options table input[type="checkbox"]').prop('checked', false);
  var line_kpis = $('input#line_kpis').val();
  var column_kpis = $('input#column_kpis').val();

  //we already have the inputs filled
  if(line_kpis != '' || column_kpis != '') {
    var line_kpis_array = line_kpis.split(',');
    var column_kpis_array = column_kpis.split(',');
    for(var i = 0; i < line_kpis_array.length; i++) {
      if(line_kpis_array[i])
        $('#kpi_options table input[value="line"][name="option_'+line_kpis_array[i]+'"]').prop('checked', true);
    }
    for(var i = 0; i < column_kpis_array.length; i++) {
      if(column_kpis_array[i])
        $('#kpi_options table input[value="column"][name="option_'+column_kpis_array[i]+'"]').prop('checked', true);
    }
  }
  //inputs are empty - we fill them
  else {
    var n = $('#kpi_options table tbody tr').length, i = 0;
    $('#kpi_options table tbody tr').each(function(i) {
      if(i < n/2) 
        $(this).find('input[value="column"]').prop('checked', true);
      else
        $(this).find('input[value="line"]').prop('checked', true);
    });
    update_kpis_types();
  }
}

function get_kpi_type(kpi){
  var line_kpis = $('input#line_kpis').val();
  var column_kpis = $('input#column_kpis').val();
  var r = false;
  if(line_kpis.indexOf(kpi) != -1)
    r = 'line';
  if(column_kpis.indexOf(kpi) != -1)
    r = 'column';
  return r;
}

function buildChart(type){
  $('#reporting_period_flip').remove();
  segments = active_segments;
  $('#company_selector').remove();
  $('.companies').remove();
  $('#kpi_options').remove();

    var card_data = get_card_data();

    if(segments != 'quarter') {
        segments = 'year';
    }

    active_chart = type;

    if(type == 'column'){
    $('.add_card').block({ message: null });

        log.debug('buildChart: column: ajax url: ' + site_url + 'card/get_data_json_all_periods/'+segments);
        $.ajax( {
        url :  site_url + 'card/get_data_json_all_periods/'+segments,
        data: card_data,
        type:"GET",
        dataType: 'json',
        error : function(){
            $('.add_card').unblock();
        },
        success : function(data) {
				/////////////////// Get details /////////////////////
			 if (data != null) {
                 log.debug('buildChart: column: ajax success response: ' + JSON.stringify(data, null, 3));
                 initialize_column(data, segments, card_data);
             }
        }
    });
  }
  else if(type == 'line'){
    $('.add_card').block({ message: null });

    $.ajax( {
        url :  site_url + 'card/get_data_json_all_periods/'+segments,
        data: card_data,
        type:"GET",
        dataType: 'json',
        error : function(){
            $('.add_card').unblock();
        },
        success : function(data) {

            if (data != null) {
                initialize_line(data, segments, card_data);
            }
        }
    });
  }
    else if(type == 'area'){
    $('.add_card').block({ message: null });

    $.ajax( {
        url :  site_url + 'card/get_data_json_all_periods/'+segments,
        data: card_data,
        type:"GET",
        dataType: 'json',
        error : function(){
            $('.add_card').unblock();
        },
        success : function(data) {
            if (data != null) {
                initialize_area(data, segments, card_data);
            }
        }
    });
  }
    else if (type == 'combo'){
    $('.add_card').block({ message: null });
    $.ajax( {
        url :  site_url + 'card/get_symbols_json_by_post',
        data: card_data,
        type:"GET",
        dataType: 'json',
        error : function(){
            $('.add_card').unblock();
        },
        success : function(data) {
            if (data != null) {
                initialize_combo(data, segments, card_data);
            }
        }
    });
  }
  else if(type == 'combo_new'){
	 
	//console.log(card_data);
    $('.add_card').block({ message: null }); 
    $.ajax( {
        url :  site_url + 'card/get_data_json_all_periods_combo/'+segments,
        data: card_data,
        type:"GET",
        dataType: 'json',
        error : function(){
            $('.add_card').unblock();
        },
        success : function(data) {
            if (data != null) {
                initialize_combo_new(data, segments, card_data);
            }
        }
    });
  }
  else if(type == 'trend'){
    $('.add_card').block({ message: null });

    $.ajax( {
        url :  site_url + 'card/get_data_json_all_periods_combo/'+segments,
        data: card_data,
        type:"GET",
        dataType: 'json',
        error : function(){
            $('.add_card').unblock();
        },
        success : function(data) {
            if (data != null) {
                initialize_trend(data, segments, card_data);
            }
        }
    });
  }
  else if(type == 'range'){
    $('.add_card').block({ message: null });

    $.ajax( {url :  site_url + 'card/get_data_json_all_periods_combo/'+segments,
        data: card_data,
        type:"GET",
        dataType: 'json',
        error : function(){
            $('.add_card').unblock();
        },
        success : function(data) {
            if (data != null) {
                initialize_range(data, segments, card_data);
            }
        }
    });  
  }
  else if(type == 'common'){
	$('#data_points_div').prepend('<p id="reporting_period_flip"><strong>reporting period:</strong> '+card_data.period+'</p>');
    $('.add_card').block({ message: null }); 
    $.ajax( {
        url :  site_url + 'card/get_data_json_all_periods_combo/'+segments,
        data: card_data,
        type:"GET",
        dataType: 'json',
        error : function(){
            $('.add_card').unblock();
        },
        success : function(data) {
            if (data != null) {
                initialize_common(data, segments, card_data);
            }
        }
    });  
  }
  else if(type == 'scatter'){
	$('#data_points_div').prepend('<p id="reporting_period_flip"><strong>reporting period:</strong> '+card_data.period+'</p>');
    $('.add_card').block({ message: null }); 

    $.ajax( {
        url :  site_url + 'card/get_data_json_all_periods_combo/'+segments,
        data: card_data,
        type:"GET",
        dataType: 'json',
        error : function(){
            $('.add_card').unblock();
        },
        success : function(data) {

            if (data != null) {
                initialize_scatter(data, segments, card_data);
            }
        }
    });        
  }
}

function initialize_column(data, segments, card_data) {
    log.debug('start initialize_column for card ' + card_data['id'] + ' with segments ' + JSON.stringify(segments, null, 3));

    $('.add_card').unblock();
    $('#reporting_period_edit').hide();
    $('#view_card_reporting_period').hide();
    $('#company_selector').remove();
    $('#kps_selector').remove();
    $('#calc_buttons').remove();
    $('#report_buttons').remove();
    $('#comp_buttons').remove();
    $('#yaxes_selector').remove();
    $('#xaxes_selector').remove();

    if($('#period_buttons').length == 0) {
        var checked = '';
        if(active_segments == 'quarter')
            checked = ' checked="checked"';

        //$('<div id="period_buttons"><button class="btn btn-success btn-cons" type="button" id="year">Annual Data</button> <button class="btn btn-success btn-cons" type="button" id="quarter">Quarterly Data</button></div>').insertAfter($('#filters'));
        $('<div id="period_buttons"><span class="ios_label">Annual</span> <div class="slide-primary"><input type="checkbox" name="switch" class="ios" '+checked+'/></div> <span class="ios_label">Quarterly</span></div>').insertAfter($('#filters'));

        if($('#save_card').length) {
            var checked = '';
            if($('input#hide_toggle').val() == 1){
                checked = 'checked="checked"';
                $('#period_buttons').addClass('disabled');
            }
            $('<div id="disable_toggle_container" class="checkbox check-primary checkbox"><input id="disable_toggle" type="checkbox" class="checkbox" value="0" '+checked+'><label for="disable_toggle">Disable Quarterly Toggle</label></div>').appendTo('#period_buttons');
        }
        else{
            if($('input#hide_toggle').val() == 1)
                $('#period_buttons').hide();
        }

        //$('#period_buttons #year, #period_buttons #quarter').removeClass('disabled');
        //$('#period_buttons #'+segments).addClass('disabled');
        var Switch = require('ios7-switch'), checkbox = document.querySelector('.ios');
        segments_switch = new Switch(checkbox);
        if(active_segments == 'quarter')
            segments_switch.toggle();
        segments_switch.el.addEventListener('click', function(e){
            e.preventDefault();
            segments_switch.toggle();
            if($('input.ios').prop('checked'))
                active_segments = 'quarter';
            else
                active_segments = 'year';
            buildChart('column');
        }, false);
    }

    $('.control.chart').css('display', 'block');

    if(segments == 'quarter')
        var periods = card_data.quarters;
    else
        var periods = card_data.years;

    log.debug('initialize_column before loop periods: ' + periods + " card_data.kpi: " + card_data.kpi + ' card_data[kpi]: ' + card_data['kpi']);

    var displayed_data = [];
    for(var i = 0; i < data.length; i++)
    {
        var obj = {};
        obj.name = data[i].company_name;
        obj.data = [];
        for(var j = 0; j < periods.length; j++)
        {
            if(data[i][periods[j]][card_data.kpi])
                obj.data.push(parseFloat(data[i][periods[j]][card_data.kpi]));
            else
                obj.data.push(0);
        }
        displayed_data.push(obj);
    }

    //console.log(displayed_data);
    //console.log(periods);
    //console.log(card_data);

    createColumnChart(periods, displayed_data, card_data);
    update_datatable(periods, displayed_data, card_data);

    /////////////////////////////////////////////////////
    //console.log(getCardDetails(card_data)); return false;
    log.debug('end initialize_column for card ' + card_data['id']);
}
function initialize_line(data, segments, card_data) {
    log.debug('start initialize_line for card ' + card_data['id']);

    $('.add_card').unblock();
    $('#reporting_period_edit').hide();
    $('#view_card_reporting_period').hide();
    $('#company_selector').remove();
    $('#kps_selector').remove();
    $('#calc_buttons').remove();
    $('#report_buttons').remove();
    $('#comp_buttons').remove();
    $('#yaxes_selector').remove();
    $('#xaxes_selector').remove();

    if($('#period_buttons').length == 0) {
        var checked = '';
        if(active_segments == 'quarter')
            checked = ' checked="checked"';

        $('<div id="period_buttons"><span class="ios_label">Annual</span> <div class="slide-primary"><input type="checkbox" name="switch" class="ios" '+checked+'/></div> <span class="ios_label">Quarterly</span></div>').insertAfter($('#filters'));

        if($('#save_card').length) {
            var checked = '';
            if($('input#hide_toggle').val() == 1) {
                checked = 'checked="checked"';
                $('#period_buttons').addClass('disabled');
            }
            $('<div id="disable_toggle_container" class="checkbox check-primary checkbox"><input id="disable_toggle" type="checkbox" class="checkbox" value="0" '+checked+'><label for="disable_toggle">Disable Quarterly Toggle</label></div>').appendTo('#period_buttons');
        }
        else{
            if($('input#hide_toggle').val() == 1)
                $('#period_buttons').hide();
        }

        var Switch = require('ios7-switch'), checkbox = document.querySelector('.ios');
        segments_switch = new Switch(checkbox);
        if(active_segments == 'quarter')
            segments_switch.toggle();
        segments_switch.el.addEventListener('click', function(e){
            e.preventDefault();
            segments_switch.toggle();
            if($('input.ios').prop('checked'))
                active_segments = 'quarter';
            else
                active_segments = 'year';
            buildChart('line');
        }, false);
    }

    $('.control.chart').css('display', 'block');

    if(segments == 'quarter')
        var periods = card_data.quarters;
    else
        var periods = card_data.years;

    var displayed_data = [];
    for(var i = 0; i < data.length; i++)
    {
        var obj = {};
        obj.name = data[i].company_name;
        obj.data = [];
        for(var j = 0; j < periods.length; j++)
        {
            if(data[i][periods[j]][card_data.kpi])
                obj.data.push(parseFloat(data[i][periods[j]][card_data.kpi]));
            else
                obj.data.push(0);
        }
        displayed_data.push(obj);
    }

    createLineChart(periods, displayed_data, card_data);
    update_datatable(periods, displayed_data, card_data);
    log.debug('end initialize_line for card ' + card_data['id']);
}
function initialize_area(data, segments, card_data) {
    log.debug('start initialize_area for card ' + card_data['id']);

    $('.add_card').unblock();
    $('#reporting_period_edit').hide();
    $('#view_card_reporting_period').hide();
    $('#company_selector').remove();
    $('#kps_selector').remove();
    $('#calc_buttons').remove();
    $('#report_buttons').remove();
    $('#comp_buttons').remove();
    $('#yaxes_selector').remove();
    $('#xaxes_selector').remove();

    if($('#period_buttons').length == 0) {
        var checked = '';
        if(active_segments == 'quarter')
            checked = ' checked="checked"';

        $('<div id="period_buttons"><span class="ios_label">Annual</span> <div class="slide-primary"><input type="checkbox" name="switch" class="ios" '+checked+'/></div> <span class="ios_label">Quarterly</span></div>').insertAfter($('#filters'));

        if($('#save_card').length) {
            var checked = '';
            if($('input#hide_toggle').val() == 1){
                checked = 'checked="checked"';
                $('#period_buttons').addClass('disabled');
            }
            $('<div id="disable_toggle_container" class="checkbox check-primary checkbox"><input id="disable_toggle" type="checkbox" class="checkbox" value="0" '+checked+'><label for="disable_toggle">Disable Quarterly Toggle</label></div>').appendTo('#period_buttons');
        }
        else{
            if($('input#hide_toggle').val() == 1)
                $('#period_buttons').hide();
        }


        var Switch = require('ios7-switch'), checkbox = document.querySelector('.ios');
        segments_switch = new Switch(checkbox);
        if(active_segments == 'quarter')
            segments_switch.toggle();
        segments_switch.el.addEventListener('click', function(e){
            e.preventDefault();
            segments_switch.toggle();
            if($('input.ios').prop('checked'))
                active_segments = 'quarter';
            else
                active_segments = 'year';
            buildChart('area');
        }, false);
    }

    $('.control.chart').css('display', 'block');

    if(segments == 'quarter')
        var periods = card_data.quarters;
    else
        var periods = card_data.years;

    var displayed_data = [];
    for(var i = 0; i < data.length; i++)
    {
        var obj = {};
        obj.name = data[i].company_name;
        obj.data = [];
        for(var j = 0; j < periods.length; j++)
        {
            if(data[i][periods[j]][card_data.kpi])
                obj.data.push(parseFloat(data[i][periods[j]][card_data.kpi]));
            else
                obj.data.push(0);
        }
        displayed_data.push(obj);
    }

    createAreaChart(periods, displayed_data, card_data);
    update_datatable(periods, displayed_data, card_data);
    log.debug('end initialize_area for card ' + card_data['id']);
}
function initialize_combo(data, segments, card_data) {
    log.debug('start initialize_combo for card ' + card_data['id']);
    $('.add_card').unblock();
    if(data.length)
    {
        $('#filters').hide();
        $('#reporting_period_edit').hide();
        $('#view_card_reporting_period').hide();
        $('#company_selector').remove();
        $('#kps_selector').remove();
        $('#calc_buttons').remove();
        $('#report_buttons').remove();
        $('#comp_buttons').remove();
        $('#yaxes_selector').remove();
        $('#xaxes_selector').remove();

        $('.control.chart').css('display', 'block');

        $(function () {
            new TradingView.MediumWidget({
                "container_id": "chart_container",
                "symbols": data,
                "gridLineColor": "#E9E9EA",
                "fontColor": "#83888D",
                "underLineColor": "#F0F0F0",
                "timeAxisBackgroundColor": "#E9EDF2",
                "trendLineColor": "#FF7965",
                "width": "100%",
                "height": "500px"
            });
        });
    }
    log.debug('end initialize_combo for card ' + card_data['id']);

}
function initialize_combo_new(data, segments, card_data) {
    log.debug('start initialize_combo_new for card ' + card_data['id'] + ' with segments ' + JSON.stringify(segments, null, 3));
    //$('#reporting_period').remove();
    $('#company_selector').remove();
    $('#kps_selector').remove();
    $('#calc_buttons').remove();
    $('#report_buttons').remove();
    $('#comp_buttons').remove();
    $('#yaxes_selector').remove();
    $('#xaxes_selector').remove();

    $('.add_card').unblock();
    $('#reporting_period_edit').hide();
    $('#view_card_reporting_period').hide();
    $('#filters').hide();

    if($('#period_buttons').length == 0) {
        var checked = '';
        if(active_segments == 'quarter')
            checked = ' checked="checked"';

        $('<div id="period_buttons"><span class="ios_label">Annual</span> <div class="slide-primary"><input type="checkbox" name="switch" class="ios" '+checked+'/></div> <span class="ios_label">Quarterly</span></div>').insertAfter($('#filters'));

        if($('#save_card').length) {
            var checked = '';
            if($('input#hide_toggle').val() == 1){
                checked = 'checked="checked"';
                $('#period_buttons').addClass('disabled');
            }
            $('<div id="disable_toggle_container" class="checkbox check-primary checkbox"><input id="disable_toggle" type="checkbox" class="checkbox" value="0" '+checked+'><label for="disable_toggle">Disable Quarterly Toggle</label></div>').appendTo('#period_buttons');
        }
        else{
            if($('input#hide_toggle').val() == 1)
                $('#period_buttons').hide();
        }


        var Switch = require('ios7-switch'), checkbox = document.querySelector('.ios');
        segments_switch = new Switch(checkbox);
        if(active_segments == 'quarter')
            segments_switch.toggle();
        segments_switch.el.addEventListener('click', function(e){
            e.preventDefault();
            segments_switch.toggle();
            if($('input.ios').prop('checked'))
                active_segments = 'quarter';
            else
                active_segments = 'year';
            buildChart('combo_new');
        }, false);
    }

    $('.control.chart').css('display', 'block');

    if(segments == 'quarter')
        var periods = card_data.quarters;
    else
        var periods = card_data.years;

    var companies_list = data['companies_list'];
    var list_markup = '';
    var active_company;
    if(companies_list.length > 0) {
        list_markup += '<div class="btn-group companies" id="company_selector"><a class="btn btn-primary dropdown-toggle btn-demo-space" data-toggle="dropdown" href="#" > <span id="company_text"></span> <span class="caret"></span> </a> <ul class="dropdown-menu companies_list">';
        for(var i = 0; i < companies_list.length; i++) {
            var active = (card_data.active_company == companies_list[i].entity_id)?"active":"";
            var pre = (card_data.active_company == companies_list[i].entity_id)?"<strong>":'';
            var suf = (card_data.active_company == companies_list[i].entity_id)?"</strong>":'';
            var selected = '';
            if (active == "active")
                selected = 'selected="selected"';


            if(card_data.active_company == companies_list[i].entity_id)
                active_company = companies_list[i];
            list_markup += '<li>'+pre+'<a href="#" class="company '+active+'" '+selected+' data-company-id="'+companies_list[i].entity_id+'" data="'+companies_list[i].entity_id+'" >'+companies_list[i].company_name+'</a>'+suf+'</li>';


        }
        list_markup += '</ul></div>';

        $(list_markup).insertAfter('#filters');

        $('#company_selector span#company_text').text(cut_string(active_company.company_name, 20));
    }

    var yaxis = [];
    var values = data['data'].data;
    var n = values.length;

    if($('#save_card').length) {
        var markup = '<div class="btn-group" id="kpi_options"><a class="btn btn-primary dropdown-toggle btn-demo-space" data-toggle="dropdown" href="#" >KPI Options<span class="caret"></span> </a> <ul class="dropdown-menu"><li><table style="margin:10px; width:240px;"><thead><tr><th></th><th style="width:40px;"><img src="'+$('html head base').attr('href')+'assets/img/column_icon.png" title="column" width="36" /></th><th  style="width:40px;"><img src="'+$('html head base').attr('href')+'assets/img/line_icon.png" title="line" width="36" /></th></tr></thead><tbody>';
        for(var i = 0; i < n; i++) {
            markup += '<tr><td>'+$('ul.kpis_select li a[data="'+values[i]['kpi']+'"]').text()+'</td><td style="text-align:center;"><input type="checkbox" value="column" name="option_'+values[i]['kpi']+'"></td><td style="text-align:center;"><input type="checkbox" value="line" name="option_'+values[i]['kpi']+'"></td></tr>';
        }
        markup += '</tbody></table></li></ul></div>';
        $(markup).insertAfter('#period_buttons');
        update_kpi_options_checkboxes();
    }

    //y axis
    for(var i = 0; i < 2; i++) {
        var y = {
            labels: {
                formatter: function() {
                    return format_number(this.value);
                }
            },
            title: {
                text: ''
            },
            opposite: (i == 0)? false : true,
        };
        yaxis.push(y);
    }

    //data

    var displayed_data = [];
    for(var i = 0; i < n; i++) {
        var type = get_kpi_type(values[i]['kpi']);
        log.debug('initialize_combo_new: get_kpi_type for ' + values[i]['kpi'] + ' is ' + JSON.stringify(type, null, 3));

        if(type != false) {
            if(type == 'line')
                type = "spline";
            var obj = {};
            obj.name = $('ul.kpis_select li a[data="'+values[i]['kpi']+'"]').text();
            obj.type = type;
            obj.yAxis = (type == 'column')? 0 : 1;
            obj.data = [];
            for(var j = 0; j < periods.length; j++)
            {
                if(values[i]['vals'][j])
                    obj.data.push(parseFloat(values[i]['vals'][j]));
                else
                    obj.data.push(0);
            }
            if(type == 'column')
                displayed_data.unshift(obj);
            else
                displayed_data.push(obj);
        }
    }
    createComboChart(periods, yaxis, displayed_data, card_data);
    update_datatable_combo(periods, displayed_data, card_data);
    log.debug('end initialize_combo_new for card ' + card_data['id']);
}
function initialize_trend(data, segments, card_data) {
    log.debug('start initialize_trend for card ' + card_data['id']);
    //$('#reporting_period').remove();
    $('#company_selector').remove();
    $('#kps_selector').remove();
    $('#calc_buttons').remove();
    $('#report_buttons').remove();
    $('#comp_buttons').remove();
    $('#yaxes_selector').remove();
    $('#xaxes_selector').remove();
    $('#report_buttons').remove();

    $('#active_xaxes').val(data['active_xaxes']);
    $('#active_yaxes').val(data['active_yaxes']);
    $('#active_kps').val(data['active_kpi']);
    $('input#active_company').val(data['active_company']);
    $('#calc_type').val(data['calc_type']);
    $('#reportType').val(data['reportType']);
    $('#includeAllPeers').val(data['includePeer']);

    $('.add_card').unblock();
    $('#reporting_period_edit').hide();
    $('#view_card_reporting_period').hide();
    $('#filters').hide();
    if($('#period_buttons').length == 0) {
        var checked = '';
        if(active_segments == 'quarter')
            checked = ' checked="checked"';

        $('<div id="period_buttons"><span class="ios_label">Annual</span> <div class="slide-primary"><input type="checkbox" name="switch" class="ios" '+checked+'/></div> <span class="ios_label">Quarterly</span></div>').insertAfter($('#filters'));

        if($('#save_card').length) {
            var checked = '';
            if($('input#hide_toggle').val() == 1){
                checked = 'checked="checked"';
                $('#period_buttons').addClass('disabled');
            }
            $('<div id="disable_toggle_container" class="checkbox check-primary checkbox"><input id="disable_toggle" type="checkbox" class="checkbox" value="0" '+checked+'><label for="disable_toggle">Disable Quarterly Toggle</label></div>').appendTo('#period_buttons');
        }
        else{
            if($('input#hide_toggle').val() == 1)
                $('#period_buttons').hide();
        }


        var Switch = require('ios7-switch'), checkbox = document.querySelector('.ios');
        segments_switch = new Switch(checkbox);
        if(active_segments == 'quarter')
            segments_switch.toggle();
        segments_switch.el.addEventListener('click', function(e){
            e.preventDefault();
            segments_switch.toggle();
            if($('input.ios').prop('checked'))
                active_segments = 'quarter';
            else
                active_segments = 'year';
            buildChart('trend');
        }, false);
    }

    $('.control.chart').css('display', 'block');

    if(segments == 'quarter')
        var periods = card_data.quarters;
    else
        var periods = card_data.years;

    var companies_list = data['companies_list'];
    var list_markup = '';
    var active_company;
    if(companies_list.length > 0) {
        list_markup += '<div class="btn-group companies" id="company_selector"><a class="btn btn-primary dropdown-toggle btn-demo-space" data-toggle="dropdown" href="#" > <span id="company_text"></span> <span class="caret"></span> </a> <ul class="dropdown-menu companies_list">';
        for(var i = 0; i < companies_list.length; i++) {
            var active = (card_data.active_company == companies_list[i].entity_id)?"active":"";
            var pre = (card_data.active_company == companies_list[i].entity_id)?"<strong>":'';
            var suf = (card_data.active_company == companies_list[i].entity_id)?"</strong>":'';
            var selected = '';
            if (active == "active")
                selected = 'selected="selected"';

            if(card_data.active_company == companies_list[i].entity_id)
                active_company = companies_list[i];
            list_markup += '<li>'+pre+'<a href="#" class="company '+active+'" '+selected+' data-company-id="'+companies_list[i].entity_id+'" data="'+companies_list[i].entity_id+'" >'+companies_list[i].company_name+'</a>'+suf+'</li>';
        }
        list_markup += '</ul></div>';

        $(list_markup).insertAfter('#filters');
        $('#company_selector span#company_text').text(cut_string(active_company.company_name, 20));
    }
    var list_markup = '';
    var kps_list = data['kps_list'];
    var active_kpi = data['active_kpi'];

    if(kps_list.length > 0) {
        list_markup += '&nbsp;<div class="btn-group kps" id="kps_selector"><a class="btn btn-primary dropdown-toggle btn-demo-space" data-toggle="dropdown" href="#" > <span id="kps_text"></span> <span class="caret"></span> </a> <ul class="dropdown-menu kps_list">';
        for(var i = 0; i < kps_list.length; i++) {
            var active = (active_kpi == kps_list[i].term_id)?"active":"";
            var pre = (active_kpi == kps_list[i].term_id)?"<strong>":'';
            var suf = (active_kpi == kps_list[i].term_id)?"</strong>":'';
            var selected = '';
            if (active == "active")
                selected = 'selected="selected"';

            if(active_kpi == kps_list[i].term_id)
                active_kpi = kps_list[i];
            list_markup += '<li>'+pre+'<a href="#" class="kps '+active+'" '+selected+' data-kps-id="'+kps_list[i].term_id+'" data="'+kps_list[i].term_id+'" >'+kps_list[i].name+'</a>'+suf+'</li>';

        }
        list_markup += '</ul></div>';

        $(list_markup).insertAfter('#company_selector');
        $('#kps_selector span#kps_text').text(cut_string(active_kpi.name, 20));
    }
    var checked = '';
    var calc_type = data['calc_type'];

    if(calc_type == 'Average')
        checked = ' checked="checked"';

    $('<div id="calc_buttons"><span class="ios_label">Median</span> <div class="slide-primary"><input type="checkbox" name="switch" class="ios2" '+checked+'/></div> <span class="ios_label">Average</span></div>').insertAfter($('#period_buttons'));


    var Switch = require('ios7-switch'), checkbox = document.querySelector('.ios2');
    calc_switch = new Switch(checkbox);
    if(active_calc == 'Average')
        calc_switch.toggle();
    calc_switch.el.addEventListener('click', function(e){
        e.preventDefault();
        calc_switch.toggle();
        if($('input.ios2').prop('checked'))
            active_calc = 'Average';
        else
            active_calc = 'Median';

        $('#calc_type').val(active_calc);
        buildChart('trend');
    }, false);

    createTrendChart(data['data'],data['active_kpi_data_type']);
    update_benchmark_datatable(data, 'trend');
    log.debug('end initialize_trend for card ' + card_data['id']);
}
function initialize_range(data, segments, card_data) {
    log.debug('start initialize_range for card ' + card_data['id']);
    //$('#reporting_period').remove();
    $('#company_selector').remove();
    $('#kps_selector').remove();
    $('#calc_buttons').remove();
    $('#report_buttons').remove();
    $('#comp_buttons').remove();
    $('#yaxes_selector').remove();
    $('#xaxes_selector').remove();

    $('#active_xaxes').val(data['active_xaxes']);
    $('#active_yaxes').val(data['active_yaxes']);
    $('#active_kps').val(data['active_kpi']);
    $('#active_company').val(data['active_company']);
    $('#calc_type').val(data['calc_type']);
    $('#reportType').val(data['reportType']);
    $('#includeAllPeers').val(data['includePeer']);

    $('.add_card').unblock();
    $('#reporting_period_edit').hide();
    $('#view_card_reporting_period').hide();
    $('#filters').hide();
    if($('#period_buttons').length == 0) {
        var checked = '';
        if(active_segments == 'quarter')
            checked = ' checked="checked"';

        $('<div id="period_buttons"><span class="ios_label">Annual</span> <div class="slide-primary"><input type="checkbox" name="switch" class="ios" '+checked+'/></div> <span class="ios_label">Quarterly</span></div>').insertAfter($('#filters'));

        if($('#save_card').length) {
            var checked = '';
            if($('input#hide_toggle').val() == 1){
                checked = 'checked="checked"';
                $('#period_buttons').addClass('disabled');
            }
            $('<div id="disable_toggle_container" class="checkbox check-primary checkbox"><input id="disable_toggle" type="checkbox" class="checkbox" value="0" '+checked+'><label for="disable_toggle">Disable Quarterly Toggle</label></div>').appendTo('#period_buttons');
        }
        else{
            if($('input#hide_toggle').val() == 1)
                $('#period_buttons').hide();
        }


        var Switch = require('ios7-switch'), checkbox = document.querySelector('.ios');
        segments_switch = new Switch(checkbox);
        if(active_segments == 'quarter')
            segments_switch.toggle();
        segments_switch.el.addEventListener('click', function(e){
            e.preventDefault();
            segments_switch.toggle();
            if($('input.ios').prop('checked'))
                active_segments = 'quarter';
            else
                active_segments = 'year';
            buildChart('range');
        }, false);
    }

    $('.control.chart').css('display', 'block');

    if(segments == 'quarter')
        var periods = card_data.quarters;
    else
        var periods = card_data.years;

    var companies_list = data['companies_list'];
    var list_markup = '';
    var active_company;
    if(companies_list.length > 0) {
        list_markup += '<div class="btn-group companies" id="company_selector"><a class="btn btn-primary dropdown-toggle btn-demo-space" data-toggle="dropdown" href="#" > <span id="company_text"></span> <span class="caret"></span> </a> <ul class="dropdown-menu companies_list">';
        for(var i = 0; i < companies_list.length; i++) {
            var active = (card_data.active_company == companies_list[i].entity_id)?"active":"";
            var pre = (card_data.active_company == companies_list[i].entity_id)?"<strong>":'';
            var suf = (card_data.active_company == companies_list[i].entity_id)?"</strong>":'';
            var selected = '';
            if (active == "active")
                selected = 'selected="selected"';

            if(card_data.active_company == companies_list[i].entity_id)
                active_company = companies_list[i];
            list_markup += '<li>'+pre+'<a href="#" class="company '+active+'" '+selected+' data-company-id="'+companies_list[i].entity_id+'" data="'+companies_list[i].entity_id+'" >'+companies_list[i].company_name+'</a>'+suf+'</li>';


        }
        list_markup += '</ul></div>';

        $(list_markup).insertAfter('#filters');
        $('#company_selector span#company_text').text(cut_string(active_company.company_name, 20));
    }
    var list_markup = '';
    var kps_list = data['kps_list'];
    var active_kpi = data['active_kpi'];

    if(kps_list.length > 0) {
        list_markup += '&nbsp;<div class="btn-group kps" id="kps_selector"><a class="btn btn-primary dropdown-toggle btn-demo-space" data-toggle="dropdown" href="#" > <span id="kps_text"></span> <span class="caret"></span> </a> <ul class="dropdown-menu kps_list">';
        for(var i = 0; i < kps_list.length; i++) {
            var active = (active_kpi == kps_list[i].term_id)?"active":"";
            var pre = (active_kpi == kps_list[i].term_id)?"<strong>":'';
            var suf = (active_kpi == kps_list[i].term_id)?"</strong>":'';
            var selected = '';
            if (active == "active")
                selected = 'selected="selected"';

            if(active_kpi == kps_list[i].term_id)
                active_kpi = kps_list[i];
            list_markup += '<li>'+pre+'<a href="#" class="kps '+active+'" '+selected+' data-kps-id="'+kps_list[i].term_id+'" data="'+kps_list[i].term_id+'" >'+kps_list[i].name+'</a>'+suf+'</li>';

        }
        list_markup += '</ul></div>';

        $(list_markup).insertAfter('#company_selector');
        $('#kps_selector span#kps_text').text(cut_string(active_kpi.name, 20));
    }
    createRangeChart(data['data'],data['active_kpi_data_type']);
    update_benchmark_datatable(data, 'range');
    log.debug('end initialize_range for card ' + card_data['id']);
}
function initialize_common(data, segments, card_data) {
    log.debug('start initialize_common for card ' + card_data['id']);
    //$('#reporting_period').remove();
    $('#company_selector').remove();
    $('.add_card').unblock();
    $('#calc_buttons').remove();
    $('#report_buttons').remove();
    $('#comp_buttons').remove();
    $('#yaxes_selector').remove();
    $('#xaxes_selector').remove();

    $('#active_xaxes').val(data['active_xaxes']);
    $('#active_yaxes').val(data['active_yaxes']);
    $('#active_kps').val(data['active_kpi']);
    $('#active_company').val(data['active_company']);
    $('#calc_type').val(data['calc_type']);
    $('#reportType').val(data['reportType']);
    $('#includeAllPeers').val(data['includePeer']);

    //$('#reporting_period_edit').hide();
    //$('#view_card_reporting_period').hide();
    $('#filters').hide();
    var companies_list = data['companies_list'];
    var list_markup = '';
    var active_company;
    if(companies_list.length > 0) {
        list_markup += '<div class="btn-group companies" id="company_selector"><a class="btn btn-primary dropdown-toggle btn-demo-space" data-toggle="dropdown" href="#" > <span id="company_text"></span> <span class="caret"></span> </a> <ul class="dropdown-menu companies_list">';
        for(var i = 0; i < companies_list.length; i++) {
            var active = (card_data.active_company == companies_list[i].entity_id)?"active":"";
            var pre = (card_data.active_company == companies_list[i].entity_id)?"<strong>":'';
            var suf = (card_data.active_company == companies_list[i].entity_id)?"</strong>":'';
            var selected = '';
            if (active == "active")
                selected = 'selected="selected"';


            if(card_data.active_company == companies_list[i].entity_id)
                active_company = companies_list[i];
            list_markup += '<li>'+pre+'<a href="#" class="company '+active+'" '+selected+' data-company-id="'+companies_list[i].entity_id+'" data="'+companies_list[i].entity_id+'" >'+companies_list[i].company_name+'</a>'+suf+'</li>';


        }
        list_markup += '</ul></div>';
        $(list_markup).insertAfter('#filters');
        $('#company_selector span#company_text').text(cut_string(active_company.company_name, 20));
    }


    var checked = '';
    var reportType = data['reportType'];

    if(reportType == 'BalanceSheet')
        checked = ' checked="checked"';

    $('<div id="report_buttons"><span class="ios_label for_break">Common Size <br> Income Statement</span> <div class="slide-primary button_margin"><input type="checkbox" name="switch" class="ios" '+checked+'/></div> <span class="ios_label for_break">Common Size <br> Balance Sheet</span></div>').insertAfter($('#company_selector'));


    var Switch = require('ios7-switch'), checkbox = document.querySelector('.ios');
    report_switch = new Switch(checkbox);
    if(active_report == 'BalanceSheet')
        report_switch.toggle();
    report_switch.el.addEventListener('click', function(e){
        e.preventDefault();
        report_switch.toggle();
        if($('input.ios').prop('checked'))
            active_report = 'BalanceSheet';
        else
            active_report = 'IncomeStatement';

        $('#reportType').val(active_report);
        buildChart('common');
    }, false);

    var checked = '';
    var calc_type = data['calc_type'];
    if(calc_type == 'Average')
        checked = ' checked="checked"';

    $('<div id="calc_buttons"><span class="ios_label">Median</span> <div class="slide-primary"><input type="checkbox" name="switch" class="ios2" '+checked+'/></div> <span class="ios_label">Average</span></div>').insertAfter($('#report_buttons'));


    var Switch = require('ios7-switch'), checkbox = document.querySelector('.ios2');
    calc_switch = new Switch(checkbox);
    if(active_calc == 'Average')
        calc_switch.toggle();
    calc_switch.el.addEventListener('click', function(e){
        e.preventDefault();
        calc_switch.toggle();
        if($('input.ios2').prop('checked'))
            active_calc = 'Average';
        else
            active_calc = 'Median';

        $('#calc_type').val(active_calc);
        buildChart(active_chart);
    }, false);
    $('.control.chart').css('display', 'block');
    createCommonChart(data['data'],data['active_kpi_data_type']);
    update_benchmark_datatable(data, 'common');
    log.debug('end initialize_common for card ' + card_data['id']);
}
function initialize_scatter(data, segments, card_data) {
    log.debug('start initialize_scatter for card ' + card_data['id']);
    //$('#reporting_period').remove();
    $('#company_selector').remove();
    $('.add_card').unblock();
    $('#calc_buttons').remove();
    $('#report_buttons').remove();
    $('#comp_buttons').remove();
    $('#yaxes_selector').remove();
    $('#xaxes_selector').remove();

    $('#active_xaxes').val(data['active_xaxes']);
    $('#active_yaxes').val(data['active_yaxes']);
    $('#active_kps').val(data['active_kpi']);
    $('#active_company').val(data['active_company']);
    $('#calc_type').val(data['calc_type']);
    $('#reportType').val(data['reportType']);
    $('#includeAllPeers').val(data['includePeer']);

    //$('#reporting_period_edit').hide();
    //$('#view_card_reporting_period').hide();
    $('#filters').hide();
    var companies_list = data['companies_list'];
    var list_markup = '';
    var active_company;
    if(companies_list.length > 0) {
        list_markup += '<div class="btn-group companies" id="company_selector"><a class="btn btn-primary dropdown-toggle btn-demo-space" data-toggle="dropdown" href="#" > <span id="company_text"></span> <span class="caret"></span> </a> <ul class="dropdown-menu companies_list">';
        for(var i = 0; i < companies_list.length; i++) {
            var active = (card_data.active_company == companies_list[i].entity_id)?"active":"";
            var pre = (card_data.active_company == companies_list[i].entity_id)?"<strong>":'';
            var suf = (card_data.active_company == companies_list[i].entity_id)?"</strong>":'';
            var selected = '';
            if (active == "active")
                selected = 'selected="selected"';

            if(card_data.active_company == companies_list[i].entity_id)
                active_company = companies_list[i];
            list_markup += '<li>'+pre+'<a href="#" class="company '+active+'" '+selected+' data-company-id="'+companies_list[i].entity_id+'" data="'+companies_list[i].entity_id+'" >'+companies_list[i].company_name+'</a>'+suf+'</li>';


        }
        list_markup += '</ul></div>';
        $(list_markup).insertAfter('#filters');
        $('#company_selector span#company_text').text(cut_string(active_company.company_name, 20));
    }

    /* var checked = '';
     var includeAllPeers = data['includePeer'];
     if(includeAllPeers == true)
     checked = ' checked="checked"';

     $('<div id="comp_buttons"><span class="ios_label for_break">Selected<br>Companies</span> <div class="slide-primary button_margin"><input type="checkbox" name="switch" class="ios" '+checked+'/></div> <span class="ios_label for_break">All<br>Companies</span></div>').insertAfter($('#company_selector'));


     var Switch = require('ios7-switch'), checkbox = document.querySelector('.ios');
     peer_switch = new Switch(checkbox);
     if(active_peer == true)
     peer_switch.toggle();
     peer_switch.el.addEventListener('click', function(e){
     e.preventDefault();
     peer_switch.toggle();
     if($('input.ios').prop('checked'))
     active_peer = true;
     else
     active_peer = false;

     $('#includeAllPeers').val(active_peer);
     buildChart(active_chart);
     }, false);
     */

    $('.control.chart').css('display', 'block');

    var list_markup = '';
    var kps_list = data['kps_list'];
    var active_xaxes = data['active_xaxes'];

    if(kps_list.length > 0) {
        list_markup += '<div class="btn-group xaxes" id="xaxes_selector"><span class="pull-left xaxes-label">X axis</span><a class="btn btn-primary dropdown-toggle btn-demo-space" data-toggle="dropdown" href="#" > <span id="xaxes_text"></span> <span class="caret"></span> </a> <ul class="dropdown-menu xaxes_list">';
        for(var i = 0; i < kps_list.length; i++) {
            if(kps_list[i] != null){
                var active = (active_xaxes == kps_list[i].term_id)?"active":"";
                var pre = (active_xaxes == kps_list[i].term_id)?"<strong>":'';
                var suf = (active_xaxes == kps_list[i].term_id)?"</strong>":'';
                var selected = '';
                if (active == "active")
                    selected = 'selected="selected"';

                if(active_xaxes == kps_list[i].term_id)
                    active_xaxes = kps_list[i];

                list_markup += '<li>'+pre+'<a href="#" class="xaxes '+active+'" '+selected+' data-xaxes-id="'+kps_list[i].term_id+'" data="'+kps_list[i].term_id+'" >'+kps_list[i].name+'</a>'+suf+'</li>';
            }
        }
        list_markup += '</ul></div>';

        $(list_markup).insertAfter('#company_selector');
        $('#xaxes_selector span#xaxes_text').text(cut_string(active_xaxes.name, 20));

        var list_markup = '';
        var kps_list = data['kps_list'];
        var active_yaxes = data['active_yaxes'];

        list_markup += '<div class="btn-group yaxes" id="yaxes_selector"><span class="pull-left yaxes-label">Y axis</span><a class="btn btn-primary dropdown-toggle btn-demo-space" data-toggle="dropdown" href="#" > <span id="yaxes_text"></span> <span class="caret"></span> </a> <ul class="dropdown-menu yaxes_list">';
        for(var i = 0; i < kps_list.length; i++) {
            if(kps_list[i] != null){
                var active = (active_yaxes == kps_list[i].term_id)?"active":"";
                var pre = (active_yaxes == kps_list[i].term_id)?"<strong>":'';
                var suf = (active_yaxes == kps_list[i].term_id)?"</strong>":'';
                var selected = '';
                if (active == "active")
                    selected = 'selected="selected"';

                if(active_yaxes == kps_list[i].term_id)
                    active_yaxes = kps_list[i];
                list_markup += '<li>'+pre+'<a href="#" class="yaxes '+active+'" '+selected+' data-yaxes-id="'+kps_list[i].term_id+'" data="'+kps_list[i].term_id+'" >'+kps_list[i].name+'</a>'+suf+'</li>';
            }
        }
        list_markup += '</ul></div>';

        $(list_markup).insertAfter('#xaxes_selector');
        $('#yaxes_selector span#yaxes_text').text(cut_string(active_yaxes.name, 20));
    }
    createScatterChart(data['data'],data['active_xaxes_data_type'],data['active_yaxes_data_type']);
    update_benchmark_datatable(data, 'scatter');
    log.debug('end initialize_scatter for card ' + card_data['id']);
}

function createScatterChart(data, data_type_x, data_type_y){
    log.debug('start createScatterChart for card ' + card_data['id']);
	//var categories = JSON.parse(data.categories);
	var series = JSON.parse(data.series);
	var xaxes_name = data.xaxes_name;
	var yaxes_name = data.yaxes_name;
	var xaxes_symbel = data.xaxes_symbel;
	var yaxes_symbel = data.yaxes_symbel;
	
	var x_axes_title = data.xaxes_name.replace(data.xaxes_symbel,'');
	var y_axes_title = data.yaxes_name.replace(data.yaxes_symbel,'');
	//console.log(data);

    if (data.url_api) {
        $('#api_urls').html(data.url_api).show();
    }

	$(function () {
    $('.control.chart').highcharts({
        chart: {
            type: 'scatter',
            zoomType: 'xy'
        },
        title: {
            text: ''
        },
        
        xAxis: {
            
            title: {
                enabled: true,
                text: xaxes_name
            },
            startOnTick: true,
            endOnTick: true,
            showLastLabel: true,
            labels : 
            {
				formatter: function() {
					if(data_type_x == 'ratio'){
						return this.value*100+'%';
					}else if(data_type_x == 'monetary'){
						return '$'+numeral(this.value).format('0.00a');
					}else{
						return numeral(this.value).format('0.00a');
					}					
				}
			},
            
        },
        yAxis: {
            title: {
                text: yaxes_name
            },
			labels : 
            {
				formatter: function() {
					if(data_type_y == 'ratio'){
						return this.value*100+'%';
					}else if(data_type_y == 'monetary'){
						return '$'+numeral(this.value).format('0.00a');
					}else{
						return numeral(this.value).format('0.00a');
					}			
				}
			}
        },
		tooltip: {
            formatter: function () {
                var s = '<b>' + this.series.name + ' '  + '</b>';
					s += '<br/>' + this.key + '<br/>';
					if(x_axes_title != ''){
						if(data_type_x == 'monetary'){
							s += x_axes_title+': $'+numeral(this.x).format('0.00a');
						}else if(data_type_x == 'ratio'){
							s += x_axes_title+': '+numeral(this.x).format('0.00a')+'%';
						}else{
							s += x_axes_title+': '+numeral(this.x).format('0.00a');
						}
					}
					if(x_axes_title != '' && y_axes_title != ''){
						s += ', ';
					}
					if(y_axes_title != ''){
						if(data_type_y == 'monetary'){
							s += y_axes_title+': $'+numeral(this.y).format('0.00a');
						}else if(data_type_y == 'ratio'){
							s += y_axes_title+': '+numeral(this.y).format('0.00a')+'%';
						}else{
							s += y_axes_title+': '+numeral(this.y).format('0.00a');
						}
					}
                return s;
            },
            shared: true
        },
        plotOptions: {
            scatter: {
                marker: {
                    radius: 5,
                    states: {
                        hover: {
                            enabled: true,
                            lineColor: 'rgb(100,100,100)'
                        }
                    }
                },
                states: {
                    hover: {
                        marker: {
                            enabled: false
                        }
                    }
                },
                /*tooltip: {
                    headerFormat: '<b>{series.name}</b><br>',
                    pointFormat: '{point.name}<br/>{point.x} , {point.y}'
                }*/
            }
        },
        series: series
    });
});
    log.debug('end createScatterChart for card ' + card_data['id']);
}
function createCommonChart(data, data_type){
    log.debug('start createCommonChart for card ' + card_data['id']);
	var categories = JSON.parse(data.categories);
	var series = JSON.parse(data.series);
	var kpi_name = data.kpi_name;
    if (data.url_api) {
        $('#api_urls').html(data.url_api).show();
    }
	$(function () {
    $('.control.chart').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: categories
        },
        yAxis: [
            {
                gridLineWidth: 0,
            title: {
                text: 'Net Income as % of Revenues'
            },
            labels: {
			formatter: function() {
				return this.value + ' %';
			}
    	},
        }],
        legend: {
            shadow: false
        },
        tooltip: {
            shared: true,
			valueSuffix: '%'
            //pointFormat: '{point.y:.2f}%'
            
        },
        plotOptions: {
            column: {
                grouping: false,
                shadow: false,
                borderWidth: 0
            }
        },
        series: series
    });
});
    log.debug('end createCommonChart for card ' + card_data['id']);
}
function createRangeChart(data, data_type){
    log.debug('start createRangeChart for card ' + card_data['id']);
	var categories = JSON.parse(data.categories);
	var series = JSON.parse(data.series);
	var kpi_name = data.kpi_name;
    if (data.url_api) {
        $('#api_urls').html(data.url_api).show();
    }
	$(function () {

    $('.control.chart').highcharts({
		
        title: {
            text: ''
        },

        xAxis: {
            type: 'linear',
            categories: categories,
            labels: {
                formatter: function () {
                    return this.value;
                }
            }
        },

        yAxis: {
            gridLineWidth: 0,
            title: {
                text: kpi_name
            },
            labels: {
				formatter: function() {
					if(data_type == 'monetary'){
						return '$'+numeral(this.value).format('0.00a');
					}else if(data_type == 'ratio'){
						return this.value+ ' %';
					}else{
						return numeral(this.value).format('0.00a');
					}
				}
            }
        },

        tooltip: {
			formatter: function () {
                var s = '<b>' + this.x + ' '  + '</b>';

                var i = 1;
                $.each(this.points, function () {
                    if (i == 1)
                    {
						if(data_type == 'monetary'){
							s += '<br/>' + this.series.name + ': $' + numeral(this.y).format('0.00a');
						}else if(data_type == 'ratio'){
							s += '<br/>' + this.series.name + ': ' +
									Highcharts.numberFormat(this.y) + '%';
						}else{
							s += '<br/>' + this.series.name + ': ' +
									Highcharts.numberFormat(this.y);
						}
                    	i = i + 1;
                    }else if (i == 2)
                    {
						if(data_type == 'monetary'){
							s += '<br/>' + this.series.name + ': $' + numeral(this.point.low).format('0.00a')+ ' - $'+numeral(this.point.high).format('0.00a');
						}else if(data_type == 'ratio'){
							s += '<br/>' + this.series.name + ': ' +
									Highcharts.numberFormat(this.point.low) + '% - '+ Highcharts.numberFormat(this.point.high)+ '%';										
						}else{
							s += '<br/>' + this.series.name + ': ' +
									Highcharts.numberFormat(this.point.low) + ' - '+ Highcharts.numberFormat(this.point.high);
						}
						i = i + 1;
					}
                });
                return s;
            },
            crosshairs: true,
            shared: true,
            valueSuffix: ''
        },
        legend: {
            enabled: true
        },

        series: series
    });
});
    log.debug('end createRangeChart for card ' + card_data['id']);
}
function createTrendChart(data, data_type){
    log.debug('start createTrendChart for card ' + card_data['id']);
	console.log(data);
	var categories = JSON.parse(data.categories);
	var series = JSON.parse(data.series);
	var kpi_name = data.kpi_name;
    if (data.url_api) {
        $('#api_urls').html(data.url_api).show();
    }
	$(function () {
    $('.control.chart').highcharts({
        chart: {
            //type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: categories
			},
        yAxis: [{
            title: {
                text: kpi_name
            },
            labels: {
        formatter: function() {
			if(data_type == 'monetary'){
				return '$'+numeral(this.value).format('0.00a');
			}else if(data_type == 'ratio'){	
				return this.value + ' %';													
			}else{
				return numeral(this.value).format('0.00a');
			}
        }
    },
        }],
        legend: {
            shadow: false
        },
        tooltip: {
            formatter: function () {
                var s = '<b>' + this.x + ' '  + '</b>';

                var i = 1;
                $.each(this.points, function () {
                    
                    if (i <= 2)
                    {
					if(data_type == 'monetary'){
						s += '<br/>' + this.series.name + ': $' + numeral(this.y).format('0.00a');
					}else if(data_type == 'ratio'){	
						s += '<br/>' + this.series.name + ': ' +
							Highcharts.numberFormat(this.y, 2) + '%';													
					}else{
						s += '<br/>' + this.series.name + ': ' +
							Highcharts.numberFormat(this.y, 2);
					}
                    i = i + 1;
                    }
                });

                return s;
            },
            shared: true
        },
        plotOptions: {
            column: {
                grouping: false,
                shadow: false,
                borderWidth: 0
            }
        },
        series: series
    });
	});
    log.debug('end createTrendChart for card ' + card_data['id']);
}
function createColumnChart(periods, displayed_data, card_data){
    log.debug('start createColumnChart for card ' + card_data['id']);
    log.debug('createColumnChart displayed_data ' + JSON.stringify(displayed_data, null, 3));
    // ej
    $('#api_urls').hide();//card_data.url_api);

    $(function () {
    //console.log(displayed_data);
    var kpi_name = $('.kpis_select li a[data="'+card_data.kpi+'"]').attr('title'); 
    var yaxis = { title:{text:kpi_name} };
    if(card_data.kpi == '283050')
      yaxis = {
                title:{text:kpi_name},
                plotBands: plotbands,
              };
    if(card_data.kpi)
    //console.log(displayed_data);
    Highcharts.setOptions({
      lang: {
          numericSymbols: [ "k" , "M" , "B" , "T" , "P" , "E"]
        },
    });
    $('.control.chart').highcharts({
        chart: {
            type: 'column', zoomType: 'x'
        },
        title: {
            text: ' '
        },
        subtitle: {
            text: ' '
        },
        xAxis: {
            categories: periods
        },
        yAxis: yaxis,
        legend: {
              layout: 'vertical',
              align: 'right',
              verticalAlign: 'middle',
              borderWidth: 0
          },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: displayed_data,
		exporting: { enabled: true}
    });
  });
    log.debug('end createColumnChart for card ' + card_data['id']);
}

function createLineChart(periods, displayed_data, card_data){
    log.debug('start createLineChart for card ' + card_data['id']);
    // ej
    $('#api_urls').hide();//card_data.url_api);
  $(function () {
      //console.log(displayed_data);
      var kpi_name = $('.kpis_select li a[data="'+card_data.kpi+'"]').attr('title'); 
      var yaxis = {
              title: {
                  text: kpi_name
              },
              plotLines: [{
                  value: 0,
                  width: 1,
                  color: '#808080'
              }]
          };
      if(card_data.kpi == '283050')
        yaxis = {
                  title:{text:kpi_name},
                  plotLines: [{
                      value: 0,
                      width: 1,
                      color: '#808080'
                  }],
                  plotBands: plotbands,
                };
      //console.log(displayed_data);
      Highcharts.setOptions({
        lang: {
            numericSymbols: [ "k" , "M" , "B" , "T" , "P" , "E"]
          },
      });

      $(function () {
        $('.control.chart').highcharts({
          chart: {
            type: 'line', zoomType: 'x'
          },
          title: {
              text: '',
          },
          subtitle: {
              text: '',
          },
          xAxis: {
              categories: periods
          },
          yAxis: yaxis,
         /* tooltip: {
            formatter: function() {
                  return kpi_name+' in '+periods[this.point.x]+': <b>'+ format_number(this.point.y) +'</b>';
              }
          },*/
          legend: {
              layout: 'vertical',
              align: 'right',
              verticalAlign: 'middle',
              borderWidth: 0
          },
          series: displayed_data,
		  exporting: { enabled: true}
        });
      });

    });
    log.debug('end createLineChart for card ' + card_data['id']);
}

function createAreaChart(periods, displayed_data, card_data){
    log.debug('start createAreaChart for card ' + card_data['id']);
  // ej
    $('#api_urls').hide();//.html(card_data.url_api);

    $(function () {
    var kpi_name = $('.kpis_select li a[data="'+card_data.kpi+'"]').attr('title'); 
    //console.log(displayed_data);
    Highcharts.setOptions({
      lang: {
          numericSymbols: [ "k" , "M" , "B" , "T" , "P" , "E"]
        },
    });
        $('.control.chart').highcharts({
            chart: {
                type: 'area', zoomType: 'x'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: periods,
                tickmarkPlacement: 'on',
                title: {
                    enabled: false
                }
            },
            yAxis: {
                title: {
                    text: kpi_name
                },
                labels: {
                    formatter: function() {
                        return format_number(this.value);
                    }
                }
            },
            tooltip: {
                shared: true,
                valueSuffix: ' ',
            },
            legend: {
              layout: 'vertical',
              align: 'right',
              verticalAlign: 'middle',
              borderWidth: 0
            },
            plotOptions: {
                area: {
                    stacking: 'normal',
                    lineColor: '#666666',
                    lineWidth: 1,
                    marker: {
                        lineWidth: 1,
                        lineColor: '#666666'
                    }
                }
            },
            series: displayed_data,
			exporting: { enabled: true}
        });
    });
    log.debug('end createAreaChart for card ' + card_data['id']);
}

function createComboChart(periods, yaxis, displayed_data, card_data){
    log.debug('start createComboChart for card ' + card_data['id']);
    // ej
    $('#api_urls').hide();//(card_data.url_api);
  $(function () {
    var kpi_name = $('.kpis_select li a[data="'+card_data.kpi+'"]').attr('title'); 
    //console.log(displayed_data);
    Highcharts.setOptions({
      lang: {
          numericSymbols: [ "k" , "M" , "B" , "T" , "P" , "E"]
        },
    });

    $('.control.chart').highcharts({
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: [{
          categories: periods,
          tickmarkPlacement: 'on',
          title: {
              enabled: false
          }
        }],
        yAxis: yaxis,
        tooltip: {
            shared: true
        },
        legend: {
            
        },
        series: displayed_data,
		exporting: { enabled: true}
    });
  });
    log.debug('end createComboChart for card ' + card_data['id']);
}

function update_datatable(periods, displayed_data, card_data){
    log.debug('start update_datatable for card ' + card_data['id']);
  if($('table#example2').length) {
    $("#example2").dataTable().fnDestroy();
    $('table#example2').empty();
    $('<thead><tr></tr></thead>').appendTo('table#example2');
    $('<th class="text-center">company Name</th>').appendTo('table#example2 thead tr');
    var row = '';
    for(var i=0; i < periods.length; i++) {
      row += '<th class="text-center">'+periods[i]+'</th>';
    }
    $(row).appendTo('table#example2 thead tr');

    $('<tbody></tbody>').appendTo('table#example2');

    row = '';
    for(var i = 0; i < displayed_data.length; i++) {
      var even = (i % 2) ? 'odd' : 'even';
      row += '<tr class="'+even+'"><td class="center">'+displayed_data[i].name+'</td>';
      for(var j = 0; j < periods.length; j++){
        var val = (displayed_data[i].data[j])? addCommas(displayed_data[i].data[j]) : 'N/A';
        row += '<td class="text-center">'+val+'</td>';
      }
      row += '</tr>';
    }
    $(row).appendTo('table#example2 tbody');
    var oTable = $('#example2').dataTable( {
     "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
     "aaSorting": [],
     "oLanguage": {
          "sLengthMenu": "_MENU_ ",
          "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
      }
    });
    $('#example2_wrapper .dataTables_filter input').addClass("input-medium ");
    $('#example2_wrapper .dataTables_length select').addClass("select2-wrapper span12");
    $(".select2-wrapper").select2({
        minimumResultsForSearch: -1
    });
    $('#example2').css('width','');
    /*console.log(periods);
    console.log(displayed_data);
    console.log(card_data);*/
  }
    log.debug('end update_datatable for card ' + card_data['id']);
}
function update_benchmark_datatable(data, chart){
    log.debug('start update_bechmark ');
  if(chart == 'trend'){
	var categories = JSON.parse(data['data'].categories);
	var series = JSON.parse(data['data'].series);
	//console.log(data['data']);
	if($('table#example2').length) {
	    $("#example2").dataTable().fnDestroy();
	    $('table#example2').empty();
	    $('<thead><tr></tr></thead>').appendTo('table#example2');
	    $('<th class="text-center">company Name</th>').appendTo('table#example2 thead tr');
	    var row = '';
	    for(var i=0; i < categories.length; i++) {
	      row += '<th class="text-center">'+categories[i]+'</th>';
	    }
	    $(row).appendTo('table#example2 thead tr');
	
	    $('<tbody></tbody>').appendTo('table#example2');
	    	row = '';
	        for(var i = 0; i < series.length-1; i++) {
	          var even = (i % 2) ? 'odd' : 'even';
	          row += '<tr class="'+even+'"><td class="center">'+series[i].name+'</td>';
	          for(var j = 0; j < series[i].data.length; j++){
	            var val = (series[i].data[j])? addCommas(series[i].data[j]) : 'N/A';
	            row += '<td class="text-center">'+val+'</td>';
	          }
	          row += '</tr>';
	        }
    		$(row).appendTo('table#example2 tbody');
		var oTable = $('#example2').dataTable( {
			 "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
			 "aaSorting": [],
			 "oLanguage": {
			      "sLengthMenu": "_MENU_ ",
			      "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
			  }
			});
			$('#example2_wrapper .dataTables_filter input').addClass("input-medium ");
			$('#example2_wrapper .dataTables_length select').addClass("select2-wrapper span12");
			$(".select2-wrapper").select2({
			    minimumResultsForSearch: -1
    		});
    		
  	}
  }
  if(chart == 'range'){
  	var categories = JSON.parse(data['data'].categories);
  	var series = JSON.parse(data['data'].series);
  	//console.log(data['data']);
  	if($('table#example2').length) {
  	    $("#example2").dataTable().fnDestroy();
  	    $('table#example2').empty();
  	    $('<thead><tr></tr></thead>').appendTo('table#example2');
  	    $('<th class="text-center">company Name</th>').appendTo('table#example2 thead tr');
  	    var row = '';
  	    for(var i=0; i < categories.length; i++) {
  	      row += '<th class="text-center">'+categories[i]+'</th>';
  	    }
  	    $(row).appendTo('table#example2 thead tr');
  	
  	    $('<tbody></tbody>').appendTo('table#example2');
  	    	row = '';
  	    	row2 = '';
  	        for(var i = 0; i < series.length; i++) {
  	          var even = (i % 2) ? 'odd' : 'even';
  	          if(i==0){
  	          	row += '<tr class="'+even+'"><td class="center">'+series[i].name+'</td>';
  	          }
  	          if(i==1){
  	          	row += '<tr class="'+even+'"><td class="center">'+series[i].name+' Low</td>';
  	          	row2 += '<tr class="odd"><td class="center">'+series[i].name+' High</td>';
  	          }
  	          for(var j = 0; j < series[i].data.length; j++){
  	          	if(i==0){	
  	            		var val = (series[i].data[j][1])? addCommas(series[i].data[j][1]) : 'N/A';
  	            		row += '<td class="text-center">'+val+'</td>';
  	            	}
  	            	if(i==1){
  	            		var val = (series[i].data[j][1])? addCommas(series[i].data[j][1]) : 'N/A';
  	            		row += '<td class="text-center">'+val+'</td>';
  	            		var val = (series[i].data[j][2])? addCommas(series[i].data[j][2]) : 'N/A';
  	            		row2 += '<td class="text-center">'+val+'</td>';
  	            	}
  	            
  	          }
  	          row += '</tr>';
  	          row2 += '</tr>';
  	        }
      		$(row+row2).appendTo('table#example2 tbody');
  		var oTable = $('#example2').dataTable( {
  			 "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
  			 "aaSorting": [],
  			 "oLanguage": {
  			      "sLengthMenu": "_MENU_ ",
  			      "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
  			  }
  			});
  			$('#example2_wrapper .dataTables_filter input').addClass("input-medium ");
  			$('#example2_wrapper .dataTables_length select').addClass("select2-wrapper span12");
  			$(".select2-wrapper").select2({
  			    minimumResultsForSearch: -1
      		});
      		
    	}
  }
  if(chart == 'common'){
    	var categories = JSON.parse(data['data'].categories);
		var series = JSON.parse(data['data'].series);
		//console.log(data['data']);
		if($('table#example2').length) {
		    $("#example2").dataTable().fnDestroy();
		    $('table#example2').empty();
		    $('<thead><tr></tr></thead>').appendTo('table#example2');
		    $('<th class="text-center">company Name</th>').appendTo('table#example2 thead tr');
		    var row = '';
		    for(var i=0; i < categories.length; i++) {
		      row += '<th class="text-center">'+categories[i]+'</th>';
		    }
		    $(row).appendTo('table#example2 thead tr');
		
		    $('<tbody></tbody>').appendTo('table#example2');
		    	row = '';
		        for(var i = 0; i < series.length; i++) {
		          var even = (i % 2) ? 'odd' : 'even';
		          row += '<tr class="'+even+'"><td class="center">'+series[i].name+'</td>';
		          for(var j = 0; j < series[i].data.length; j++){
		            var val = (series[i].data[j])? addCommas(series[i].data[j]) : 'N/A';
		            row += '<td class="text-center">'+val+'</td>';
		          }
		          row += '</tr>';
		        }
	    		$(row).appendTo('table#example2 tbody');
			var oTable = $('#example2').dataTable( {
				 "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
				 "aaSorting": [],
				 "oLanguage": {
				      "sLengthMenu": "_MENU_ ",
				      "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
				  }
				});
				$('#example2_wrapper .dataTables_filter input').addClass("input-medium ");
				$('#example2_wrapper .dataTables_length select').addClass("select2-wrapper span12");
				$(".select2-wrapper").select2({
				    minimumResultsForSearch: -1
	    		});
	    		
  	}
  }
  if(chart == 'scatter'){
      	var categories = JSON.parse(data['data'].categories);
  		var series = JSON.parse(data['data'].series);
  		if($('table#example2').length) {
  		    $("#example2").dataTable().fnDestroy();
  		    $('table#example2').empty();
			
  		    $('<thead><tr></tr></thead>').appendTo('table#example2');
			
  		    $('<th class="text-center">company Name</th>').appendTo('table#example2 thead tr');
  		    var row = '';
			var x_axes_title = data['data'].xaxes_name.replace(data['data'].xaxes_symbel,'');
			var y_axes_title = data['data'].yaxes_name.replace(data['data'].yaxes_symbel,'');
  		    row += '<th class="text-center">'+x_axes_title+'</th><th class="text-center">'+y_axes_title+'</th>';
				    
  		    $(row).appendTo('table#example2 thead tr');
  		    $('<tbody></tbody>').appendTo('table#example2');
			if(series[0].data.length>0){
  		    	row = '';
  		    for(var i = 0; i < series.length; i++) {
  		          if(series[i].name == 'Primary Company'){
  		          	row += '<tr class="'+even+'">';
					  for(var j = 0; j < series[i].data.length; j++){
					    var xval = (series[i].data[j].x)? addCommas(series[i].data[j].x) : 'N/A';
					    var yval = (series[i].data[j].y)? addCommas(series[i].data[j].y) : 'N/A';
					    row += '<td class="center">Primary Company '+series[i].data[j].name+'</td><td class="text-center">'+xval+'</td><td class="text-center">'+yval+'</td>';
					  }
  		          	row += '</tr>';
  		        }else{
					for(var j = 0; j < series[i].data.length; j++){
					var even = (j % 2) ? 'odd' : 'even';
					  row += '<tr class="'+even+'">';
						var xval = (series[i].data[j].x)? addCommas(series[i].data[j].x) : 'N/A';
						var yval = (series[i].data[j].y)? addCommas(series[i].data[j].y) : 'N/A';
						row += '<td class="center">'+series[i].data[j].name+'</td><td class="text-center">'+xval+'</td><td class="text-center">'+yval+'</td>';
					  row += '</tr>';
					 }
  		        }
  		    }
  	    		$(row).appendTo('table#example2 tbody');
  			var oTable = $('#example2').dataTable( {
  				 "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
  				 "aaSorting": [],
  				 "oLanguage": {
  				      "sLengthMenu": "_MENU_ ",
  				      "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
  				  }
  				});
  				$('#example2_wrapper .dataTables_filter input').addClass("input-medium ");
  				$('#example2_wrapper .dataTables_length select').addClass("select2-wrapper span12");
  				$(".select2-wrapper").select2({
  				    minimumResultsForSearch: -1
  	    		});
		}
    }
  }
    log.debug('end update_bechmark ');
}
function update_datatable_combo(periods, displayed_data, card_data){
    log.debug('start update_datatable_combo for card ' + card_data['id']);

  if($('table#example2').length) {
    $("#example2").dataTable().fnDestroy();
    $('table#example2').empty();
    $('h3 span#active_company').text($('#company_selector ul.companies_list li a[data="'+card_data.active_company+'"]').text());
    $('<thead><tr></tr></thead>').appendTo('table#example2');
    $('<th class="text-center">KPIs</th>').appendTo('table#example2 thead tr');
    var row = '';
    for(var i=0; i < periods.length; i++) {
      row += '<th class="text-center">'+periods[i]+'</th>';
    }
    $(row).appendTo('table#example2 thead tr');

    $('<tbody></tbody>').appendTo('table#example2');

    row = '';
    for(var i = 0; i < displayed_data.length; i++) {
      var even = (i % 2) ? 'odd' : 'even';
      row += '<tr class="'+even+'"><td class="center">'+displayed_data[i].name+'</td>';
      for(var j = 0; j < periods.length; j++){
        var val = (displayed_data[i].data[j])? addCommas(displayed_data[i].data[j]) : 'N/A';
        row += '<td class="text-center">'+val+'</td>';
      }
      row += '</tr>';
    }
    $(row).appendTo('table#example2 tbody');
    var oTable = $('#example2').dataTable( {    
     "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
     "aaSorting": [],
     "oLanguage": {
          "sLengthMenu": "_MENU_ ",
          "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
      }
    });
    $('#example2_wrapper .dataTables_filter input').addClass("input-medium ");
    $('#example2_wrapper .dataTables_length select').addClass("select2-wrapper span12");
    $(".select2-wrapper").select2({
        minimumResultsForSearch: -1
    });
    $('#example2').css('width','');
   
  }
    log.debug('end update_datatable_combo for card ' + card_data['id']);
}

function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    x2 = x2.substring(0, 3);
    if(x2 == '.00')
        x2 = '';
    return x1 + x2;
}

