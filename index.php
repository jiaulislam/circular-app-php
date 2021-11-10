<?php
session_start();
include_once 'db/db_connect_oracle.php';
if(isset($_POST['btn-login']))
{
//	$user = $_POST['email'];
//	$pass = $_POST['pass'];

$user=$_POST['email'];
$pass=$_POST['pass'];
$query = "select
((to_char((ascii(substr('$user',1,1)))*(ascii(substr('$pass',5,1))*5))||to_char((ascii(substr('$user',2,1))*2)*(ascii(substr('$pass',4,1))*4))||to_char((ascii(substr('$user',3,1))*3)*(ascii(substr('$pass',3,1))*3))||to_char((ascii(substr('$user',4,1))*4)*(ascii(substr('$pass',2,1))*2))||to_char((ascii(substr('$user',5,1))*5)*(ascii(substr('$pass',1,1))))||to_char((ascii(substr('$user',6,1))*1)*(ascii(substr('$pass',6,1))*1)))) as mulfin, 
 NVL(length((to_char((ascii(substr('$user',1,1)))*(ascii(substr('$pass',5,1))*5))||to_char((ascii(substr('$user',2,1))*2)*(ascii(substr('$pass',4,1))*4))||to_char((ascii(substr('$user',3,1))*3)*(ascii(substr('$pass',3,1))*3))||to_char((ascii(substr('$user',4,1))*4)*(ascii(substr('$pass',2,1))*2))||to_char((ascii(substr('$user',5,1))*5)*(ascii(substr('$pass',1,1))))||to_char((ascii(substr('$user',6,1))*1)*(ascii(substr('$pass',6,1))*1)))),0) as mullen
from dual";				
	$stid = OCIParse($conn, $query);
	  OCIExecute($stid);
	while($row=oci_fetch_array($stid)){ 
	$mulfin=$row[0];
	$mullen=$row[1];
	
	if (fmod($mullen,3)!=0) {
		$loopfor=(floor($mullen/3)) + 1;
		}
		else {
			$loopfor=($mullen/2);
			}
						$x = 1;
						$i = 0;
						while($x <= $loopfor) {
						$subs= substr(trim ($mulfin),$i,2);
						if ($subs < 48 || $subs > 57){
														if ($subs>90){
															while ($subs>=91){
														 $subs=$subs-26;
															}
														 }//if ($subs>90)
														 
														 if($subs<65){
														 while ($subs<65){
														 $subs=$subs+26;
														 }
														 }// elseif($subs<65)
						}//if (($subs<48)&&($subs>57))
						if (!isset($store)) $store ='';
						  $store=$store.chr($subs);
						  $i+=3;
						  $x++;
						} 
							 
								}
								//echo $store;

	$query = "select * from plil.syusrmas where USERCODE='$user'";				
	$stid = OCIParse($conn, $query);
	  OCIExecute($stid);
	while($row=oci_fetch_array($stid)){ 
	if($row[2]==$store){
		$_SESSION['user'] = $row[0];
		header("Location: home.php");
		}
	else{
		?>
        <script>alert('wrong details');</script>
        <?php
	}}}
?>
<html>
<head>	
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
<body>    
<div class="container justify-content-center align-content-center">
    <div class="container align-content-center">
        <div class="row">
            <img src="bus.png" class="img-responsive mx-auto d-block">
        </div>
    </div>
    <p>&nbsp;</p>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col text-center">
                <form method="post">
                    <input type="text" name="email" placeholder="User code" class="form-control my-3" required />
                    <input type="password" name="pass" placeholder="Your Password" class="form-control mb-3" required />
                    <button type="submit" name="btn-login" class="btn btn-primary btn-md btn-block">SIGN IN</button>
                </form>
            </div>
        </div>
    </div>

</div>

		
</body>
</html>
