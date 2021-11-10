<?php
include_once 'db/db_connect_oracle.php';
$user='000474';
$pass='mahir1';
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
								
								echo $store;
?>


