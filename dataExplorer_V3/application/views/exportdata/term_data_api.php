<table class="table table-striped table-bordered table-hover display" id="DataTable"  >
    <?php
    $ratio_arr = array();
  
    $r = 0;
    $table="";
    if (sizeof($resut) > 0) {
        $termsvalue = explode(",", $terms);
        $termcount = count($termsvalue);
        
        $table.='<thead><tr><th>Company</th>';
                $table.='<th>Ticker</th>';
                $table.='<th>Reporting Period</th>';
                
                foreach ($termsvalue as $val) {
                    $kpi = get_kpi_by_termId($val);
                    foreach ($kpi as $kpiname) {
                        if (strtolower($kpiname->type) == "ratio") {
                            $ratio_arr[$r] = "yes";
                            
                            $table.='<th>'.$kpiname->name . "(%)".'</th>';
                        
                        } else {
                            if (strtolower($kpiname->name) == "revenues") {
                                $ratio_arr[$r] = "rev";
                                
                                $table.='<th>'.$kpiname->name.'</th>';
                            } else {
                                $ratio_arr[$r] = "no";
                               
                                $table.='<th>'.$kpiname->name.'</th>';
                            
                            }
                        }
                        $r++;
                    }
                }
               
            $table.='</tr></thead>';
    $table.='</tbody>';
    
    $firstrow = 0;
    $old_comp = "";
    $FQ = "";
    $FY = "";
    $ii = $termcount;

    foreach ($narr AS $k => $v) {

        $comp = get_company_by_entityId($k);
       
        $years = $v;
        foreach ($years AS $quarter => $q) {
            foreach ($q as $quarter_arr => $q1) {
               

                $table.='<tr>';
                    $table.='<td>'.@$comp[0]->company_name.'</td>';
                    $table.='<td>'.@$comp[0]->stock_symbol.'</td>';
                     if ($quarter_arr != "FY") { 
                        $table.='<td>'.@$quarter . $quarter_arr.'</td>';
                    } else { 
                        $table.='<td>'.@$quarter.'</td>';
                   
                    }
                    for ($s = 0; $s < $termcount; $s++) {
                        $rstr = "";
                        $termId = $term_array[$s];
                        $tVal = $narr[$k][$quarter][$quarter_arr][$termId];
                        if ($tVal > 0) {
                            if (@$ratio_arr[$s] == "yes") {
                                $rstr = "%";
                                
                                $table.='<td>'.$tVal . $rstr.'</td>';
                            
                            } else {
                                $decimal = 0;
                                if (strpos($tVal, ".") == true) {
                                    $decimal = 2;
                                }
                               
                                $table.='<td>'.number_format((double) $tVal, $decimal, ".", ",").'</td>';
                         }
                    } else {
                       
                            $table.='<td>NA</td>';
                    }
                }
                
                $table.='</tr>';

                
            }
        }
    }
    
    $table.='</tbody>';
    $table.='</table>';
    echo $table;
} else {
    echo "No Result(s) match or found";
}