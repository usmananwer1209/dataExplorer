<table class="table table-striped table-bordered table-hover display" id="DataTable"  >
    <?php
    $ratio_arr = array();
  
    $r = 0;
    if (sizeof($resut) > 0) {
        $termsvalue = explode(",", $terms);
        $termcount = count($termsvalue);
        ?>
        <thead><tr><th>Company</th>
                <th>Ticker</th>
                <th>Reporting Period</th>
                <?php
                foreach ($termsvalue as $val) {
                    $kpi = get_kpi_by_termId($val);
                    foreach ($kpi as $kpiname) {
                        if (strtolower($kpiname->type) == "ratio") {
                            $ratio_arr[$r] = "yes";
                            ?>
                            <th><?php echo $kpiname->name . "(%)"; ?></th>
                        <?php
                        } else {
                            if (strtolower($kpiname->name) == "revenues") {
                                $ratio_arr[$r] = "rev";
                                ?>
                                <th><?php echo $kpiname->name ?></th>
                            <?php } else {
                                $ratio_arr[$r] = "no";
                                ?>
                                <th><?php echo $kpiname->name ?></th>
                            <?php
                            }
                        }
                        $r++;
                    }
                }
                ?>
            </tr></thead>
    </tbody>
    <?php
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
                ?>

                <tr>
                    <td><?php echo @$comp[0]->company_name ?></td>
                    <td><?php echo @$comp[0]->stock_symbol ?></td>
                    <?php if ($quarter_arr != "FY") { ?>
                        <td><?php echo @$quarter . $quarter_arr ?></td>
                    <?php } else { ?>
                        <td><?php echo @$quarter ?></td>
                    <?php
                    }
                    for ($s = 0; $s < $termcount; $s++) {
                        $rstr = "";
                        $termId = $term_array[$s];
                        $tVal = $narr[$k][$quarter][$quarter_arr][$termId] . "<br>";
                        if ($tVal > 0) {
                            if (@$ratio_arr[$s] == "yes") {
                                $rstr = "%";
                                ?>
                                <td> <?php echo $tVal . $rstr ?></td>
                            <?php
                            } else {
                                $decimal = 0;
                                if (strpos($tVal, ".") == true) {
                                    $decimal = 2;
                                }
                                ?>
                                <td> <?php echo number_format((double) $tVal, $decimal, ".", ",") ?></td>
                        <?php }
                    } else {
                        ?>
                            <td>NA</td>
                    <?php }
                }
                ?>
                </tr>

                <?php
            }
        }
    }
    ?>
    </tbody>
    </table>
    <?php
} else {
    echo "No Result(s) match or found";
}