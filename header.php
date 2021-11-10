<?php
session_start();
include_once 'db/db_connect_oracle.php';
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}
$user_c = $_SESSION['user'];
$query = "select * from plil.syusrmas where USERCODE='$user_c'";
$stid = OCIParse($conn, $query);
OCIExecute($stid);
while ($row = oci_fetch_array($stid)) {
    $user_co = $row[0];
    $user_name = $row[1];
    $pro_co = $row[29];
}

?>
<html>
<head>
    <style>
        body {
            padding-top: 70px;
            /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
        }

        .btn-fa,
        .btn-fa:active,
        .btn-fa:visited,
        .btn-fa:focus {
            background-color: #8064A2;
            border-color: #8064A2;
            color: #FFFFFF;
        }

        .btn-fa:hover {
            background: #553181;
            color: #FFFFFF;
        }

        .btn-divc,
        .btn-divc:active,
        .btn-divc:visited,
        .btn-divc:focus {
            background-color: #964B00;
            border-color: #8064A2;
            color: #FFFFFF;
        }

        .btn-divc:hover {
            background: #723b03;
            color: #FFFFFF;
        }

        .btn-all,
        .btn-all:active,
        .btn-all:visited,
        .btn-all:focus {
            background-color: #36454F;
            border-color: #8064A2;
            color: #FFFFFF;
        }

        .btn-all:hover {
            background: #000;
            color: #FFFFFF;
        }
    </style>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-grid.css" rel="stylesheet">
    <link href="css/bootstrap-grid.min.css" rel="stylesheet">
    <link href="css/bootstrap-reboot.css" rel="stylesheet">
    <link href="css/bootstrap-reboot.min.css" rel="stylesheet">
    <script type="text/javascript" src="js/jquery-latest.js"></script>
    <script type="text/javascript" src="js/__jquery.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
</head>

<body style="padding-top: 6px;">
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col py-2">
            <div class="card">
                <div class="card-header">
                    <h6 class="text-center"><?php echo "Welcome,  <em><b>" . $user_name ."</b></em>" ;?></h6>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-danger btn-block"
                            onClick="location.href='logout.php?logout'">
                        Log Out
                    </button>
                </div>
            </div>
        </div>
    </div>
