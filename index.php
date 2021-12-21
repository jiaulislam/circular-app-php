<?php
session_start();
include_once 'db/db_connect_oracle.php';
if (isset($_POST['btn-login']))
{
//	$user = $_POST['email'];
//	$pass = $_POST['pass'];
	print_r($_POST);
	$user = $_POST['email'];
	$pass = $_POST['pass'];
	$query = "select
((to_char((ascii(substr('$user',1,1)))*(ascii(substr('$pass',5,1))*5))||to_char((ascii(substr('$user',2,1))*2)*(ascii(substr('$pass',4,1))*4))||to_char((ascii(substr('$user',3,1))*3)*(ascii(substr('$pass',3,1))*3))||to_char((ascii(substr('$user',4,1))*4)*(ascii(substr('$pass',2,1))*2))||to_char((ascii(substr('$user',5,1))*5)*(ascii(substr('$pass',1,1))))||to_char((ascii(substr('$user',6,1))*1)*(ascii(substr('$pass',6,1))*1)))) as mulfin, 
 NVL(length((to_char((ascii(substr('$user',1,1)))*(ascii(substr('$pass',5,1))*5))||to_char((ascii(substr('$user',2,1))*2)*(ascii(substr('$pass',4,1))*4))||to_char((ascii(substr('$user',3,1))*3)*(ascii(substr('$pass',3,1))*3))||to_char((ascii(substr('$user',4,1))*4)*(ascii(substr('$pass',2,1))*2))||to_char((ascii(substr('$user',5,1))*5)*(ascii(substr('$pass',1,1))))||to_char((ascii(substr('$user',6,1))*1)*(ascii(substr('$pass',6,1))*1)))),0) as mullen
from dual";
	$stid = OCIParse($conn, $query);
	OCIExecute($stid);
	while ($row = oci_fetch_array($stid))
	{
		$mulfin = $row[0];
		$mullen = $row[1];

		if (fmod($mullen, 3) != 0)
		{
			$loopfor = (floor($mullen / 3)) + 1;
		} else
		{
			$loopfor = ($mullen / 2);
		}
		$x = 1;
		$i = 0;
		while ($x <= $loopfor)
		{
			$subs = substr(trim($mulfin), $i, 2);
			if ($subs < 48 || $subs > 57)
			{
				if ($subs > 90)
				{
					while ($subs >= 91)
					{
						$subs = $subs - 26;
					}
				}//if ($subs>90)

				if ($subs < 65)
				{
					while ($subs < 65)
					{
						$subs = $subs + 26;
					}
				}// elseif($subs<65)
			}//if (($subs<48)&&($subs>57))
			if ( ! isset($store)) $store = '';
			$store = $store . chr($subs);
			$i += 3;
			$x++;
		}

	}
	//echo $store;

	$query = "select * from plil.syusrmas where USERCODE='$user'";
	$stid = OCIParse($conn, $query);
	OCIExecute($stid);
	while ($row = oci_fetch_array($stid))
	{
		if ($row[2] == $store)
		{
			$_SESSION['user'] = $row[0];
			header("Location: home.php");
		} else
		{
			?>
            <script>alert('wrong details');</script>
			<?php
		}
	}
}
?>
<html>
<head>
    <script src="js/bootstraps.min.js"></script>
    <link href="css/bootstraps.min.css" rel="stylesheet">
</head>
<body>
<div class="container justify-content-center align-content-center">
    <div class="container align-content-center">
        <div class="row">
            <img src="icons/logo.jpg" class="img-fluid mx-auto w-auto">
        </div>
    </div>
    <p>&nbsp;</p>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col text-center">
                <form method="post">
                    <div class="card">
                        <div class="card-body">
                            <div class="input-group my-2">
                                <span class="input-group-text" id="usercode">
                                    <img src="icons/face_black_24dp.svg">
                                </span>
                                <input type="text" placeholder="User Code" class="form-control"
                                       aria-labelledby="usercode" aria-label="User Code" name="email" required/>

                            </div>
                            <div class="input-group">
                                <span class="input-group-text" id="passcode">
                                    <img src="icons/vpn_key_black_24dp.svg">
                                </span>

                                <input type="password" id="passcode" name="pass" placeholder="Password"
                                       class="form-control" aria-label="Password" aria-labelledby="password"
                                       required/>
                            </div>

                        </div>
                        <div class="card-footer">
                            <button type="submit" name="btn-login"
                                    class="mx-auto btn btn-primary btn-md d-flex justify-content-center align-content-between gap-1">
                                <i
                                        class="mr-1"><img src="icons/login_black_24dp.svg"></i><span>SIGN IN</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>