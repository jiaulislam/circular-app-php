<?php
session_start();
include_once 'db/db_connect_oracle.php';
if ( ! isset($_SESSION['user']))
{
	header("Location: index.php");
}
$user_c = $_SESSION['user'];
$query = "select * from plil.syusrmas where USERCODE='$user_c'";
$stid = OCIParse($conn, $query);
OCIExecute($stid);
while ($row = oci_fetch_array($stid))
{
	$user_co = $row[0];
	$user_name = $row[1];
	$pro_co = $row[29];
}

?>
<html>
<head>
   <style>
        * {
            font-family: 'Roboto', sans-serif;          
        }       

    </style>
       <script src="js/bootstraps.min.js"></script>
       <link href="css/bootstraps.min.css" rel="stylesheet">
       <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
       <link rel="stylesheet" href="css/custom.css">
</head>

<body style="padding-top: 6px;" onload='setDate()'>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col py-2">
            <div class="card">
                <div class="card-header bg-primary">
                    <h6 class="text-center text-white"><?php echo "Welcome,  <b>" . $user_name . "</b>"; ?></h6>
                </div>
                <div class="card-footer d-flex">
                    <button type="button" class="btn btn-danger flex-fill"
                            onClick="location.href='logout.php?logout'">
                        SIGN OUT
                    </button>
                </div>
            </div>
        </div>
    </div>
