<?php
include 'header.php';
$query_oth = "select USERCODE,GRUPCODE from plil.SYUSRMAS where USERCODE='$user_co'";
$stid_oth = OCIParse($conn, $query_oth);
OCIExecute($stid_oth);
while ($row_oth = oci_fetch_array($stid_oth)) {
    $oth_cou = $row_oth[1];
}

?>
<?php
if ($oth_cou != "DEVHR") {
    ?>
    <button type="button" class="btn btn-fa  btn-lg btn-block" onClick="location.href='pi_bus.php'">Project Wise
        Business
    </button>
<?php } ?>

<button type="button" class="btn btn-primary btn-lg btn-block" onClick="location.href='hr_pi_bus.php'">Project Wise HR
    Business
</button>

<button type="button" class="btn btn-info btn-lg btn-block" onClick="location.href='hr_staff_business_summary.php'">
    Project Wise HR Business (Summary)
</button>

<button type="button" class="btn-success btn-lg btn-block" onClick="location.href='hr_divc_bus.php'">HR Business
    (DIVC)
</button>

<button type="button" class="btn btn-info btn-lg btn-block" onClick="location.href='hr_code_bus.php'">Code Wise
    Business
</button>

<button type="button" class="btn btn-warning btn-lg btn-block" onClick="location.href='pol_info_pd.php'">Policy Ledger
</button>

<button type="button" class="btn btn-primary btn-lg btn-block" onClick="location.href='search_circular.php'">Circular Notice
</button>


