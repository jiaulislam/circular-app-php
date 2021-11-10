<?php
include_once("header.php");
$show_error_policy = 0;
if (isset($_GET['submit']) == '') {
    $submit = false;
    $policy_number = '';

} else {
    $submit = $_GET['submit'];
    $policy_number = $_GET['policy_number'];

}
?>
<body>
<div class="container">
    <div id="page-wrapper" style="margin-left: 0px;">
        <div class="row">
            <div class="col">
                <h3 style="color: #602e8e;margin-top: 20px;" class="page-header">Policy Information</h3>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <form method="get" role="search">
                        <input type="text" name="policy_number" placeholder="Insert Policy Number"
                               value="<?php echo $policy_number; ?>" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <button class="btn btn-success btn-block" type="submit" name="submit" value="submit">
                    Submit
                </button>

            </div>
            <div class="col">
                <button type="button" class="btn btn-primary btn-block" onclick="location.href='home.php'">Home
                </button>
            </div>
        </div>


            </form>
        </div>

        <?php


        if ($submit == true){
        // Oracle connect
        $conn = oci_connect('SHAMIM', 'bj23Hzs', 'PRAGATI');

        $policy_number = $_GET['policy_number'];


        $policy_info_query = "select policy,name,datcom,sex,(plan||'-'||term) plan,SUMASS,paymode,polopt,totprem,age,premno,sumrisk,totpaid,suspense,lprem,(oc1 || '/' ||oc2|| '/' ||oc3|| '/' ||oc4|| '/' ||oc5||'/'||oc6||'/'||oc7||'/'||oc8||'/'||oc9||'/'||oc10)as setup, pnextpay, ipl.maturity_date(datcom ,term), phone from ipl.policy where policy= '" . $policy_number . "'";
        $policy_info_stid = OCIParse($conn, $policy_info_query);
        OCIExecute($policy_info_stid);

        $policy_project_query = "select b.project from ipl.policy a, ipl.project b where a.PRJ_CODE=b.PRJ_CODE and a.policy='" . $policy_number . "'";
        $policy_project_stid = OCIParse($conn, $policy_project_query);
        OCIExecute($policy_project_stid);

        $nominee_info_query = "select NOMNAME,(NOMAGEYY) as age, rel_name from ipl.nominee, ipl.relation where relcode =  NOMRELCODE and policy = '" . $policy_number . "'";
        $nominee_info_stid = OCIParse($conn, $nominee_info_query);
        OCIExecute($nominee_info_stid);

        $collection_info_query = "select DISTINCT a.receipt,a.RECPTDT,a.DUEDT,a.TOTPREM,b.PRBMNO,b.PRBMDT,a.PNEXTPAY,b.amount from ipl.collection a, ipl.prbm b
                                     where a.receipt=b.receipt and a.policy = '" . $policy_number . "' order by b.PRBMDT ASC";
        $collection_info_stid = OCIParse($conn, $collection_info_query);
        OCIExecute($collection_info_stid);


        $show_error_policy = 0;

        while ($row = oci_fetch_array($policy_info_stid))
        {

        ?>


        <div class="panel panel-info">
            <div class="panel-heading">
                <b>General Information</b>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Project</th>
                            <th colspan="3"><?php
                                while ($row2 = oci_fetch_array($policy_project_stid)) { ?>
                                    <?php echo $row2[0]; ?>
                                <?php } ?></th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Policy No.</td>
                            <td><?php echo $row[0]; ?></td>
                            <td>Name of Policyholder</td>
                            <td><?php echo $row[1]; ?></td>
                        </tr>
                        <tr>
                            <td>Commencement Date</td>
                            <td><?php echo $row[2]; ?></td>
                            <td>Gender</td>
                            <td><?php if ($row[3] == 'F') {
                                    echo 'Female';
                                } else {
                                    echo 'Male';
                                } ?></td>
                        </tr>
                        <tr>
                            <td>Plan & Term</td>
                            <td><?php echo $row[4]; ?></td>
                            <td>Mobile No.</td>
                            <td><?php echo $row[18]; ?></td>
                        </tr>
                        <tr>
                            <td>Sum Assured</td>
                            <td><?php echo $row[5]; ?></td>
                            <td>Pay Mode</td>
                            <td><?php if ($row[6] == '0') {
                                    echo 'Single';
                                } elseif ($row[6] == '1') {
                                    echo 'Yearly';
                                } elseif ($row[6] == '2') {
                                    echo 'Half Yearly';
                                } elseif ($row[6] == '4') {
                                    echo 'Quaterly';
                                } elseif ($row[6] == '5') {
                                    echo 'Monthly';
                                }
                                ?></td>
                        </tr>
                        <tr>
                            <td>Install. Premium</td>
                            <td><?php echo $row[8]; ?></td>
                            <td>Tot. Prem. Paid</td>
                            <td><?php echo $row[12]; ?></td>
                        </tr>
                        <tr>
                            <td>Date of Maturity</td>
                            <td><?php echo $row[17]; ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Basic Premium</td>
                            <td><?php echo $row[14]; ?></td>
                            <td><strong>Next Due Date</strong></td>
                            <td><strong><?php echo $row[16]; ?></strong></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <?php ?>
        <div class="panel panel-success">
            <div class="panel-heading">
                <b>Nominee Details</b>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Nominee Name</th>
                            <th>Relation</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($row3 = oci_fetch_array($nominee_info_stid)) {
                            ?>
                            <tr>
                                <td><?php echo $row3[0]; ?></td>
                                <td><?php echo $row3[2]; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <div class="panel panel-danger">
            <div class="panel-heading">
                <b>Collection Details</b>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Receipt Name</th>
                            <th>Recept Date</th>
                            <th>Due Date</th>
                            <th>Install. Premium</th>
                            <th>PR/BM No.</th>
                            <th>PR/BM Date</th>
                            <th>Next Due Date</th>
                            <th>Premium Paid</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $total = 0.00;
                        while ($row4 = oci_fetch_array($collection_info_stid)) {
                            ?>
                            <tr>
                                <td><?php echo $row4[0]; ?></td>
                                <td><?php echo $row4[1]; ?></td>
                                <td><?php echo $row4[2]; ?></td>
                                <td><?php echo $row4[3]; ?></td>
                                <td><?php echo $row4[4]; ?></td>
                                <td><?php echo $row4[5]; ?></td>
                                <td><?php echo $row4[6]; ?></td>
                                <td><?php echo $row4[7]; ?></td>
                            </tr>
                            <?php $total = $total + $row4[7];
                        } ?>
                        <tr>
                            <td colspan="7" style="text-align: right;"><strong>Total Deposited Premium</strong></td>
                            <td><strong><?php echo $total;


                                    ?></strong></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
    </div>
    <?php
    $show_error_policy = 1;
    ?>
    <?php }
    } ?>
    <?php
    if ($show_error_policy == 0 && $submit == true) {
        ?>
        <div class="alert alert-danger fade in">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <strong>Sorry!</strong> Please insert your valid Policy Number 0r Insert your Correct Birth day
        </div>
    <?php } ?>

</div>

<!-- /.row -->
</div>
<!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<!-- Core Scripts - Include with every page -->
<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>

<!-- Page-Level Plugin Scripts - Dashboard -->
<script src="js/plugins/morris/raphael-2.1.0.min.js"></script>
<script src="js/plugins/morris/morris.js"></script>

<!-- SB Admin Scripts - Include with every page -->
<script src="js/sb-admin.js"></script>

<!-- Page-Level Demo Scripts - Dashboard - Use for reference -->
<script src="js/demo/dashboard-demo.js"></script>
<!-- Page-Level Plugin Scripts - Forms -->
<script type="text/javascript" src="js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="js/locales/bootstrap-datetimepicker.en.js" charset="UTF-8"></script>
<script type="text/javascript">
    $('.form_date').datetimepicker({
        language: 'fr',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });

</script>
</body>
<!---->
<!--</html>-->
