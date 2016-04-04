var urlSrc = "url('assets/img/loader.gif') center 100px no-repeat";
var urlSrcTable = "url('assets/img/loader.gif'); background-position: center center; background-repeat: no-repeat;";
var topvaldefault = 30;
//Get Data of lists With Company name
function ListAsComp() {
    var CompName = $('#company_name').val();
    $.post('exportdata/get_term_results', {'Work': 'ListAsComp', 'tbl': 'company', 'CompName': CompName},
    function (data)
    {
        var lists = data.split("#@#");
                $('#sectorList').val() = lists[0];
                $('#industryList').val() = lists[1];
                $('#sicList').val() = lists[2];
    });
}
// Get Lists of sector/industry/sic
function selectlist(Work) {
    if (Work == 'SectorList') {
        $("#industryList").val("All");
        $("#sicList").val("All");
        var atbValue = $('#sectorList').val();
    }
    else if (Work == 'IndustryList') {
        $("#sicList").val("All");
        var atbValue = $('#industryList').val();
    }
    else if (Work == 'SICList') {
        var atbValue = $('#sicList').val();
    }
    $.post('exportdata/' + Work, {'Work': Work, 'tbl': 'company', 'atbValue': atbValue},
    function (data)
    {
        var lists = data.split("#@#");
        if (Work == 'SectorList') {
            $('#industryList').html(lists[0]);
            $('#sicList').html(lists[1]);
        }
        else if (Work == 'IndustryList') {
            $('#sicList').html(lists[0]);
        }
    });
}
// Get Dropdown Company List 
function ListOfDropdown(table, divid, clickaction) {

    $("#" + divid).html("");
    $("#" + divid).css("background", urlSrc);
    var i = 1;
    var chkdvalue = [];
    var filter = [];
    var company_name = "";
    var sector_name = "";
    var industry_name = "";
    var sic = "";
    if (table == 'company') {
        var SelectedList = 'SelectedComp';
        var entity = $('#company_name').val();
        filter.push($('#sectorList').val());
        filter.push($('#industryList').val());
        filter.push($('#sicList').val());
        if (entity == "" & clickaction != 1) {
            $("#" + divid).html("");
            $("#" + divid).css("background", "");
            return false;
        }

        if (clickaction == 1) {
            entity = "";
        }

        $.ajax({
            url: site_url + "card/company_search_tree_param/" + entity,
            error: function () {
            },
            success: function (result) {
                var json_result = $.parseJSON(result);
                company_name = json_result[0].company_name;
                sector_name = json_result[0].sector;
                industry_name = json_result[0].industry;
                sic = json_result[0].sic;
                if (entity == "") {
                    industry_name = "";
                    sector_name = "";
                    sic = "";
                }
                if (entity != "") {
                    if (sector_name != "") {
                        $("#sectorList").val(sector_name);
                        selectlist('SectorList');
                    }
                    if (industry_name != "") {
                        selectlist('IndustryList');
                        setTimeout(function () {
                            $("#industryList").val(industry_name);
                            selectlist('IndustryList');
                        }, 1500);
                    }
                    if (sic != "") {
                        selectlist('SICList');
                        setTimeout(function () {
                            $("#sicList").val(sic);
                        }, 2500);

                    }
                }

                $(".checkboxx").each(function (e) {
                    if ($(this).is(':checked')) {
                        chkdvalue.push($(this).val());
                    }
                });


                $.post('exportdata/getcompanylist', {'Work': 'ListOfDropdown', 'table': table, 'chkdvalue': chkdvalue, 'filter': filter, 'entity': entity, 'sic': sic}, function (data) {

                    if (data != '') {
                        $("#" + divid).css("background", '');
                        var arrData = data.split("#@#");
                        $("#" + divid).html(arrData[0]);

                        var atb = 'comp';
                        var ATB = 'Comp';
                        var LisId = '';
                        var objid = "company_name";


                        var KPIName = $("#" + objid).val();
                        var i = 0;
                        var j = 0;

                        while (document.getElementById("chk" + atb + "_" + i + "_" + j)) {
                            while (document.getElementById("chk" + atb + "_" + i + "_" + j)) {
                                if ($("#lblchk" + atb + "_" + i + "_" + j).html().trim() == KPIName) {
                                    $("#chk" + atb + "_" + i + "_" + j).prop('checked', true);
                                    selectBox("chk" + atb + "_" + i + "_" + j, 'Selected' + ATB);

                                    var topval = i * topvaldefault;
                                    $("#accordionchk" + atb + "" + i).addClass('active');
                                    $("#accordionchk" + atb + "" + i + " em").removeClass('fa fa-plus');
                                    $("#accordionchk" + atb + "" + i + " em").addClass('fa fa-minus');

                                    $("#accordionchk" + atb + "" + i).removeClass("collapsed");
                                    $("#accordionchk" + atb + "" + i).removeClass("accordion-toggle");
                                    $("#accordionchk" + atb + "" + i).addClass("accordion-toggle");
                                    $("#collapsechk" + atb + "" + i).addClass("in");
                                    setTimeout(function () {
                                        $("div.row").animate({scrollTop: topval});
                                    }, 1500);
                                    //var count = $("#CountComp").text();
                                    //$("#CountComp").text(parseInt(count) + 1) ;

                                }
                                j++;
                            }
                            j = 0;
                            i++;
                        }
                        /*alert(arrData[1]);
                         if (arrData[1]) {
                         selectBox(arrData[1], SelectedList);
                         }	*/
                    }
                    /*else
                     alert('Data Not Avalaible!');*/
                }
                );




            }
        });


    }

}
// Get Dropdown KPI List 
function ListOfDropdownKpi(table, divid, rdGroup) {
    $("#" + divid).html("");
    $("#" + divid).css("background", urlSrc);
    var i = 1;
    var chkdvalue = [];
    var filter = [];
    if (table == 'kpi') {
        var SelectedList = 'SelectedKpi';
        if ($('#decision_category').is(':checked'))
            rdGroup = 'decision_category';
        if ($('#financial_category').is(':checked'))
            rdGroup = 'financial_category';
        if ($('#flat_list').is(':checked'))
            rdGroup = 'flat_list';


        $.post('exportdata/getcompanylist', {'Work': 'ListOfDropdown', 'table': table, 'filter': filter, 'rdGroup': rdGroup}, function (data) {
            if (data != '') {
                $("#" + divid).css("background", '');
                var arrData = data.split("#@#");
                $("#" + divid).html(arrData[0]);

                var id = table;
                var atb = 'kpi';
                var ATB = 'Kpi';
                var objid = "KPIname";

                var KPIName = $("#" + objid).val();
                var i = 0;
                var j = 0;

                while (document.getElementById("chk" + atb + "_" + i + "_" + j)) {
                    while (document.getElementById("chk" + atb + "_" + i + "_" + j)) {
                        if ($("#lblchk" + atb + "_" + i + "_" + j).html().trim() == KPIName) {
                            $("#chk" + atb + "_" + i + "_" + j).prop('checked', true);
                            selectBox("chk" + atb + "_" + i + "_" + j, 'Selected' + ATB);

                            var topval = i * topvaldefault;
                            $("#accordionchk" + atb + "" + i).removeClass("collapsed");
                            $("#accordionchk" + atb + "" + i).removeClass("accordion-toggle");
                            $("#accordionchk" + atb + "" + i).addClass("accordion-toggle");
                            $("#collapsechk" + atb + "" + i).addClass("in");
                            setTimeout(function () {
                                $("div.row").animate({scrollTop: topval});
                            }, 1500);
                            //var count = $("#"+'CountKpi').text();
                            //$("#"+'CountKpi').text(parseInt(count) + 1);
                        }
                        j++;
                    }
                    j = 0;
                    i++;
                }
                /* if (arrData[1]) {
                 selectBox(arrData[1], SelectedList);
                 }*/

            }
            /*else
             alert('Data Not Avalaible!');*/
        }
        );

    }


}
// Select All/Selected
function SelectAllSelected() {
    var i = 1;
    while (document.getElementById("chkAmt" + i)) {//alert(document.getElementById("chkAmt"+i));
        document.getElementById("chkAmt" + i).checked = false;
        i++;
    }
    ListOfDropdown('company', 'ListOfDropdown', 1);
}
function selectedboxflat(Id) {

    if ($("#" + Id).is(':checked')) {
        $("#SelectedKpi").append("<option  data-desc='fe' value=" + $("#" + Id).val() + " onclick='getDetail(" + $("#" + Id).val() + ");'>" + $("#lbl" + Id).text() + "</option>");
        var count = $("#" + 'CountKpi').text();
        $("#" + 'CountKpi').text(parseInt(count) + 1);
    } else {
        var selectobject = $("#SelectedKpi");
        $("#SelectedKpi option[value=" + $("#" + Id).val() + "]").remove();

        var count = $("#" + 'CountKpi').text();
        $("#" + 'CountKpi').text(parseInt(count) - 1);

    }

}
// Select/Unselect companies by group
function selectBox(Id, selectId) {

    var Id_p = Id.split("_");
    var arrSize = Id_p.length;
    var chkStatus = false;
    if (Id) {
        var chkStatus = $("#" + Id).is(':checked');
    }
    //alert(arrSize);
    // If Main Category Called and checked
    if (arrSize == 2 && chkStatus == true)
    {

        var i = 0;

        while ($("#" + Id + "_" + i).attr('id')) {

            if (checkinList($("#" + Id + "_" + i).val(), selectId) == false) {

            }
            else {

                $("#" + Id + "_" + i).prop('checked', chkStatus);
                if (selectId == 'SelectedKpi')
                {
                    $("#" + selectId).append("<option data-desc='fu' value=" + $("#" + Id + "_" + i).val() + " onclick='getDetail(" + $("#" + Id + "_" + i).val() + ");'>" + $("#lbl" + Id + "_" + i).text() + "</option>");
                    var count = $("#" + 'CountKpi').text();
                    $("#" + 'CountKpi').text(parseInt(count) + 1);
                }
                else {
                    $("#" + selectId).append("<option  data-desc='f' value=" + $("#" + Id + "_" + i).val() + " >" + $("#lbl" + Id + "_" + i).text() + "</option>");
                    var count = $("#CountComp").text();
                    $("#CountComp").text(parseInt(count) + 1);
                }
                $("#txt" + selectId).append("<input type='hidden' id='txt" + $("#" + Id + "_" + i).val() + "' value='" + Id + "_" + i + "'>");
            }
            i++;
        }
    }
// If Main Category Called and un-checked
    else if (arrSize == 2 && chkStatus == false)
    {
        var i = 0;
        while ($("#" + Id + "_" + i).attr('id')) {
            $("#" + Id + "_" + i).prop('checked', chkStatus);
            RemovefromList($("#" + Id + "_" + i).val(), selectId);
            i++;
        }
    }
// If Sub Category Called and checked
    else if (arrSize == 3 && chkStatus == true)
    {

        if ($("#" + Id_p[0] + "_" + Id_p[1]).attr('id')) {
            if (checkinList($("#" + Id).val(), selectId) == false) {
                return false;
            }
            $("#" + Id_p[0] + "_" + Id_p[1]).prop('checked', chkStatus);
            if (selectId == 'SelectedKpi')
            {
                $("#" + selectId).append("<option data-desc='f' value=" + $("#" + Id).val() + " onclick='getDetail(" + $("#" + Id).val() + ");'>" + $("#lbl" + Id).text() + "</option>");
                var count = $("#" + 'CountKpi').text();
                $("#" + 'CountKpi').text(parseInt(count) + 1);
            }
            else {
                $("#" + selectId).append("<option data-desc='f' value=" + $("#" + Id).val() + " >" + $("#lbl" + Id).text() + "</option>");
                var count = $("#" + 'CountComp').text();
                $("#" + 'CountComp').text(parseInt(count) + 1);
            }
            $('#txt' + selectId).append("<input type='hidden' id='txt" + $("#" + Id).val() + "' value='" + Id + "'>");
            i++;
        }
    }
// If Sub Category Called and un-checked
    else if (arrSize == 3 && chkStatus == false)
    {
        if ($("#" + Id).attr('id')) {
            RemovefromList($("#" + Id).val(), selectId);
            i++;
        }

        var countcheck = 0;
        $(".checkboxinner_" + Id_p[0] + '_' + Id_p[1]).each(function () {
            var checkstatus = $(this).is(":checked");
            if (checkstatus) {
                countcheck++;
            }

        });

        if (countcheck == 0 && $("#" + Id_p[0] + '_' + Id_p[1]).is(":checked")) {
            $("#" + Id_p[0] + '_' + Id_p[1]).removeAttr('checked');
        }
    }
}
// Check in List
function checkinList(Value, selectId) {
    var selectobject = document.getElementById(selectId);
    for (var i = 0; i < selectobject.length; i++) {
        if (selectobject.options[i].value == Value) {
            return false;
        }
    }
}
// remove selected data with check boxes
function RemovefromList(Value, selectId) {
    var selectobject = document.getElementById(selectId);
    for (var i = 0; i < selectobject.length; i++) {
        if (selectobject.options[i].value == Value) {
            selectobject.remove(i);
            if (selectId == 'SelectedKpi')
            {
                var count = document.getElementById('CountKpi').textContent;
                document.getElementById('CountKpi').textContent = parseInt(count) - 1;
            }
            else {
                var count = document.getElementById('CountComp').textContent;
                document.getElementById('CountComp').textContent = parseInt(count) - 1;
            }
        }
    }
}



// Chenge Selection of All/Selected
function selectLimts() {
    document.getElementById('rdSelected').checked = true;
}
//Get Detail of Selected KPI
function getDetail(Id) {
    $.post('exportdata/getdetails', {'Work': 'getDetail', 'tbl': 'kpi', 'Id': Id},
    function (data)
    {
       document.getElementById('getDetail').innerHTML = data;
    });
}

//COnfirmation Modal
function model_confirmation(selectId) {
    console.log(selectId);
    var selectId = selectId;
    $('#confirmation_modal button.removeSlide').attr('onclick', 'DataSave(\'' + selectId + '\')');
    $("#confirmation_modal").modal();
}
// List Data Save in Database
function DataSave(selectId) {
    $('#confirmation_modal button.removeSlide').removeAttr('onclick');
    $("#confirmation_modal").modal('hide');
    console.log(selectId);
    if (selectId == 'SelectedKpi') {
        var ListName = 'KpiListName';
        var table = 'list_kpis';
        var status = 'KpiStatus';
        var dataList = 'KpiNameList';
        var msgid = 'kpimsg';
    }
    else {
        var ListName = 'compListName';
        var table = 'list_companies';
        var status = 'CompStatus';
        var dataList = 'CompNameList';
        var msgid = 'compmsg';
    }
    if ($('#' + ListName).val() == '') {
        var msg = "List name required!";
        $('#error_modal p').html(msg);
        $("#error_modal").modal()
        return false;

    }


    ListName = $('#' + ListName).val();
    if ($('#' + status).is(":checked")) {
        status = 1;
    }
    else {
        status = 0;
    }
    var selectobject = $('#' + selectId);
    var values = '"';
    if (selectId == 'SelectedComp') {
        $('select[name="SelectedComp"] option').each(function (i) {
            values += $(this).val() + ",";
        });
    } else if (selectId == 'SelectedKpi') {
        $('select[name="SelectedComp"] option').each(function (i) {
            values += $(this).val() + ",";
        });
    }

    values += '"';
    $.post('exportdata/datasave', {'Work': 'DataSave', 'tbl': table, 'ListName': ListName, 'values': values, 'status': status},
    function (data)
    {
        //$("#"+msgid).addClass( "alert alert-success" );
        //$("#"+msgid).html(data);
        $('#successfull_modal p').html(data);
        $("#successfull_modal").modal();
        changeList(dataList, ListName);
    });
}
// List Data Show in List
function ShowList(selectId) {
    if (selectId == 'CompNameList') {
        var table = 'list_companies';
        var listId = 'SelectedComp';
        var CountId = 'CountComp';
        var listname = 'compListName';
    }
    else {
        var table = 'list_kpis';
        var listId = 'SelectedKpi';
        var CountId = 'CountKpi';
        var listname = 'KpiListName';

    }

    var selecttext = $("#" + selectId + " option:selected").text();
    selectId = $("#" + selectId).val();
    $("#" + listname).val(selecttext);
    $.post('exportdata/showlist', {'Work': 'ShowList', 'tbl': table, 'selectId': selectId},
    function (data)
    {
        $("#" + listId).html(data);
        $("#" + CountId).html($("#" + listId + " > option").length);
    });
}
// Refresh Table
function getTable() {
    var Range = $('#range_04').val();
    var CompListName = "";

    $("#SelectedComp > option").each(function (e) {
        CompListName += noformate($(this).val(), 6) + ",";
    });

    var KpiListName = "";

    $("#SelectedKpi > option").each(function (e) {
        KpiListName += $(this).val() + ",";
    });


    var Anual = 0;
    var Quarterly = 0;
    if ($('#Anual').is(':checked')) {
        Anual = 1;
    }
    if ($('#Quarterly').is(':checked')) {
        Quarterly = 1;
    }
    if (Anual == 0 && Quarterly == 0) {
        alert('Reporting Period(s) not selected.');
        return false;
    }
    else if (CompListName == '' || KpiListName == '') {
        alert('Companies or KPIs not selected.');
        return false;
    }
    else {
        $("#DataTable tbody").html("");
        var loadingdata = "<tr><td id=\"Dataloading\" height=\"100px\" width=\"100%\" style=\"background-image:" + urlSrcTable + "\"></td></tr>"
        $("#DataTable tbody").html(loadingdata);
        //$("#Dataloading").css("background", urlSrcTable);
    }
    $.post('exportdata/get_term_results', {'Work': 'getTable', 'entities': CompListName, 'terms': KpiListName, 'periods': Range, 'Anual': Anual, 'Quarterly': Quarterly},
    function (data)
    {
        var total = 0;
        var lists = data.split("#@#");
        $('#apiurl').html(lists[1]);
        $("#tabledata").html(lists[0]);
        $("#hiddentable").html(lists[0]);
        $('#DataTable').DataTable({
            "ordering": false,
            "info":false, 
            "paging":true, 
            "sInfo": "Data Preview ( _END_ of _TOTAL_ Records)",
            "searching": false,
            "pageLength": 100,
            "lengthMenu": [[100, -1], [100, "All"]],
            "initComplete": function(settings, json){ 
            var info = this.api().page.info();
            total= info.recordsTotal;
        }

        });
        $("#DataTable_length").html("");
        $("#DataTable_paginate").html("")
        var table = $('#DataTable').DataTable();

        var count_records = total; 
        if(count_records <100){
            $("#showrecords").html(count_records);
        } else { 
        $("#showrecords").html("100");}
    
 
        $("#datatablecount").html(total);
    });
}
// CSV
function getCSV() {
    var CSVName = $('#CSVName').val();
    if(CSVName ==""){CSVName ="noname";}
    var table = $('#hiddentable table').html();
    var data = table.replace(/,/g, '')
            .replace(/<thead>/g, '')
            .replace(/<\/thead>/g, '')
            .replace(/<tbody>/g, '')
            .replace(/<\/tbody>/g, '')
            .replace(/<tr>/g, '')
            .replace(/<\/tr>/g, '\r\n')
            .replace(/<th>/g, '')
            .replace(/<\/th>/g, ',')
            .replace(/<td>/g, '')
            .replace(/<\/td>/g, ',')
            .replace(/\t/g, '')
            .replace(/\n/g, '');

    var mylink = document.createElement('a');
    mylink.download = CSVName+".csv";
    mylink.href = "data:application/csv," + escape(data);
    mylink.click();
}
//NoFormate
function noformate(Num, len) {
    while (Num.length < len) {
        Num = '0' + Num;
    }
    return Num;
}
// GetRange
function range() {
    $.post('exportdata/getrange', {'Work': 'range'},
    function (data)
    {
        var ArrVal = data.split(";");

        $("#range_04").ionRangeSlider({
            type: "double",
            grid: true,
            min: ArrVal[0],
            max: ArrVal[1],
            from: ArrVal[0],
            to: (ArrVal[1])
        });
    });
}
// Select KPI
function SelectCompKPI(id) {

    if (id == 'company_name') {
        var atb = 'comp';
        var ATB = 'Comp';
        var LisId = '';

    } else if (id == 'KPIname') {
        var atb = 'kpi';
        var ATB = 'Kpi';
    }

    var KPIName = $("#" + id).val();
    var i = 0;
    var j = 0;
    var rdGroup = "";
    if ($('#flat_list').is(':checked'))
        rdGroup = 'flat_list';

    if (rdGroup == "flat_list" & id == 'KPIname') {
        while (document.getElementById("chk" + atb + "_" + i)) {
            if ($("#lblchk" + atb + "_" + i).html().trim() == KPIName) {
                $("#chk" + atb + "_" + i).prop('checked', true);
                selectedboxflat("chk" + atb + "_" + i);

                var topval = i * topvaldefault;
                $("#accordionchk" + atb + "" + i).addClass('active');
                $("#accordionchk" + atb + "" + i + " em").removeClass('fa fa-plus');
                $("#accordionchk" + atb + "" + i + " em").addClass('fa fa-minus');
                $("#accordionchk" + atb + "" + i).removeClass("collapsed");
                $("#accordionchk" + atb + "" + i).removeClass("accordion-toggle");
                $("#accordionchk" + atb + "" + i).addClass("accordion-toggle");
                $("#collapsechk" + atb + "" + i).addClass("in");
                setTimeout(function () {
                    $("div.row").animate({scrollTop: topval});
                }, 1500);
                /*if (id == 'KPIname') {
                 var count = $("#"+'CountKpi').text();
                 $("#"+'CountKpi').text(parseInt(count) + 1);
                 }*/
            }
            i++;
        }
    } else {

        while (document.getElementById("chk" + atb + "_" + i + "_" + j)) {
            while (document.getElementById("chk" + atb + "_" + i + "_" + j)) {
                if ($("#lblchk" + atb + "_" + i + "_" + j).html().trim() == KPIName) {
                    $("#chk" + atb + "_" + i + "_" + j).prop('checked', true);
                    selectBox("chk" + atb + "_" + i + "_" + j, 'Selected' + ATB);

                    var topval = i * topvaldefault;
                    $("#accordionchk" + atb + "" + i).addClass('active');
                    $("#accordionchk" + atb + "" + i + " em").removeClass('fa fa-plus');
                    $("#accordionchk" + atb + "" + i + " em").addClass('fa fa-minus');
                    $("#accordionchk" + atb + "" + i).removeClass("collapsed");
                    $("#accordionchk" + atb + "" + i).removeClass("accordion-toggle");
                    $("#accordionchk" + atb + "" + i).addClass("accordion-toggle");
                    $("#collapsechk" + atb + "" + i).addClass("in");
                    setTimeout(function () {
                        $("div.row").animate({scrollTop: topval});
                    }, 1500);
                    /*if (id == 'KPIname') {
                     var count = $("#"+'CountKpi').text();
                     $("#"+'CountKpi').text(parseInt(count) + 1);
                     }*/
                }
                j++;
            }
            j = 0;
            i++;
        }
        /*if (arrData[1]) {
         selectBox(arrData[1], SelectedList);
         }*/
    }
}
// Change List of Exportdata
function changeList(id, listname) {
    $.post('exportdata/changelist', {'Work': 'changeList', 'id': id}, function (data)
    {
        $("#" + id).html(data);
        $("#" + id + " option").filter(function () {
            return $(this).text() == listname;
        }).attr('selected', true);

        //$("#" + id).val(listname);
    });
}
//ajax autocomplete search
$(function () {
    $(document).on("keyup", ".autocomplete_comp, #company_name", function (d) {
       
        var lidata = "";
        var value = $(this).val();
        var table = $(this).attr("data-table");
        var attr = $(this).attr("data-attribute");
        $.post('autocomplete/companies', {'data': value}, function (data)
        {

            lidata = "";
            var jsondata = JSON.parse(data);
            $.each(jsondata, function (i, object) {
                lidata += "<li>" + object.company_name + "</li>";
            });
            $("#company_name_container").show().html(lidata);
        });
         d.preventDefault();
    });
    $(document).on("focusout", "#company_name", function (d) {
        $("#company_name_container").hide();
    });
    $(document).on("mouseover", "#company_name_container > li", function (d) {
        var text = $(this).html();
        $("#company_name").val(text);
    });
    //KPI autocomplete
    $(document).on("keyup", "#KPIname", function (d) {
        var lidata = "";
        var value = $(this).val();
        var table = $(this).attr("data-table");
        var attr = $(this).attr("data-attribute");
        $.post('autocomplete/kpis', {'data': value}, function (data)
        {
            lidata = "";
            var jsondata = JSON.parse(data);
            $.each(jsondata, function (i, object) {
                lidata += "<li>" + object.name + "</li>";
            });
            $("#KPIname_container").show().html(lidata);
        });
    });
    $(document).on("focusout", "#KPIname", function (d) {
        $("#KPIname_container").hide();
    });
    $(document).on("mouseover", "#KPIname_container > li", function (d) {
        var text = $(this).html();
        $("#KPIname").val(text);
    });

    //ListOfDropdown('company','ListOfDropdown');
    ListOfDropdownKpi('kpi', 'ListKpiDropdown');
    range();
});

//delete selected companies
selected = [];
$('body').on('click', '#delete_selected_companies', function () {
    $('select[name="SelectedComp"] option:selected').each(function (i) {
        var count = $("#CountComp").text();
        $("#CountComp").text(parseInt(count) - 1);
        selected[i] = $(this).val();
    });

    $('#ListOfDropdown input:checked').each(function (i) {
        if (selected.indexOf($(this).val()) != '-1') {
            $(this).prop('checked', false);

        }
    });

    $('select[name="SelectedComp"] option:selected').remove();
    selected = [];
    return false;
});


//delete selected companies
selected = [];
$('body').on('click', '#delete_selected_kpis', function () {
    $('select[name="SelectedKpi"] option:selected').each(function (i) {
        var count = $("#CountKpi").text();
        $("#CountKpi").text(parseInt(count) - 1);
        selected[i] = $(this).val();
    });

    $('#ListKpiDropdown input:checked').each(function (i) {
        if (selected.indexOf($(this).val()) != '-1') {
            $(this).prop('checked', false);
        }
    });

    $('select[name="SelectedKpi"] option:selected').remove();
    selected = [];
    $("#getDetail").html("");
    return false;
});