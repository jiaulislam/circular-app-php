<?php 
error_reporting(0);
include 'header.php';
	$query_oth = "select count(*) from plil.syrights where PROGNAME='hr_business_rpt_all' and USERCODE='$user_co'";				
	$stid_oth = OCIParse($conn, $query_oth);
	  OCIExecute($stid_oth);
	while($row_oth=oci_fetch_array($stid_oth))
        { 
		$oth_cou = $row_oth[0];
		}
?>




<?php 
//echo $oth_cou;
if ($oth_cou=1){?>
<form  method="get">

<table width="369">
  <tr>
    <td width="216">
    
<div class="row" style="height: 40px;">
    <div>
        <div class="form-group">
            <select type="text" name="fmon" class="form-control" style="width:102px;">
	<?php for ($month = 1; $month <= 12; $month++) { 
	 $asd =('2013-'.(strlen($month)==1 ? '0'.$month : $month).'-15');
	 ?>
	<option value="<?php echo strlen($month)==1 ? '0'.$month : $month; ?>"><?php echo date_format(date_create(".$asd."),"F"); ?></option>
	<?php } ?>
</select>
        </div>
    </div>
    <div>
        <div class="form-group">
<select type="text" name="fyear" class="form-control" style="width:81px;">
  <?php for ($year = date('Y'); $year > date('Y')-2; $year--) { ?>
	<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
	<?php } ?>
</select>
        </div>
    </div>
</div>

<div class="row" style="height: 40px;">
    <div>
        <div class="form-group">
            <select type="text" name="tomon" class="form-control" style="width:102px;">
	
	<?php for ($month = 1; $month <= 12; $month++) { 
	 $asd =('2013-'.(strlen($month)==1 ? '0'.$month : $month).'-15');
	 ?>
	<option value="<?php echo strlen($month)==1 ? '0'.$month : $month; ?>"><?php echo date_format(date_create(".$asd."),"F"); ?></option>
	<?php } ?>
</select>
        </div>
    </div>
    <div>
        <div class="form-group">
<select type="text" name="toyear" class="form-control" style="width:81px;">

  <?php for ($year = date('Y'); $year > date('Y')-2; $year--) { ?>
	<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
	<?php } ?>
</select>
        </div>
    </div>
</div>
    </td>
    <td width="66">
    <button type="submit" class="btn btn-success btn-lg btn-block" style=" height:80px;">Submit</button>

    </td>
        <td width="71">
    <button type="button" class="btn btn-primary btn-lg btn-block" onclick="location.href='home.php'" style=" height:80px;">Home</button>

    </td>
    <td></td>
  </tr>
</table>
</div>



</form>
<?php 

if(isset($_GET["fmon"])&&isset($_GET["fyear"])&&isset($_GET["tomon"])&&isset($_GET["toyear"])) {
$f_mon= $_GET["fyear"].$_GET["fmon"];  
$t_mon= $_GET["toyear"].$_GET["tomon"];  
$collection_info_query = "SELECT '<strong>'||A.NAME||'</strong></br> POS: '||A.DESIG||'</br> Code: '||A.CODE as namedeg,TO_CHAR(TO_DATE(B.BMON,'YYYYMM'),'Mon-YYYY') as month,round(SUM(NVL(B.FYEAR,0))) AS FYEAR,round(SUM(NVL(B.SING0_5,0))) AS SING05,round(SUM(NVL(B.SING6_ABOVE,0))) AS SING6ABOVE,round(SUM(NVL(B.SING,0))) AS SING,
round(SUM(NVL(B.FYEAR,0)+NVL(B.SING0_5*.15,0)+NVL(B.SING6_ABOVE*.20,0))) AS CREDIT_FY,
round(SUM(NVL(B.FYEAR,0)+NVL(B.SING0_5,0)+NVL(B.SING6_ABOVE,0))) AS GROSS_FY,
round(SUM(NVL(B.RENEWAL,0))) AS RENEWAL,
round(SUM(NVL(B.FYEAR,0)+NVL(B.SING,0)+NVL(B.RENEWAL,0))) AS TOTAL,A.CODE
FROM IT.HR_STAFF_PROJECT A,IT.HR_STAFF_BUSINESS B 
WHERE A.CODE=B.CODE and b.bmon BETWEEN '$f_mon' AND '$t_mon' and (a.prj_code='$pro_co' or '$pro_co' is null) and upper(a.status) = 'A'
GROUP BY A.PRJ_CODE,DECODE(A.PRJ_CODE,'01','IPL-POLASH','02','IEB','03','TAKAFUL','04','IDPS','05','PBPIB','08','IPL-BOKUL','99','NOT MENTIONED') ,
A.CODE,A.NAME,A.DESIG,B.BMON order by  A.CODE,b.bmon asc";				
$collection_info_stid = OCIParse($conn, $collection_info_query);
OCIExecute($collection_info_stid);
?>
 <div class="table-responsive">  
  <div align="center">
    <h4>HR Staff Business Statment</h4>
<h6><?php 

 echo "Closing Month : ".(date('F', mktime(0, 0, 0, $_GET["fmon"], 10))).", ".($_GET["fyear"])." To ".(date('F', mktime(0, 0, 0, $_GET["tomon"], 10))).", ".($_GET["toyear"]); // March

// echo "Closing Month : ".date("m",strtotime($_GET["tomon"]));
//echo substr($f_mon,4);
//echo date("d F Y h:i:s A", strtotime("2011-06-01 11:15 PM")) . "\n";
?></h6>  
<h6> 
<?php
$query_att = "select PROJECT from ipl.project where PRJ_CODE='$pro_co'";				
	$stid_att = OCIParse($conn, $query_att);
	  OCIExecute($stid_att);
	while($row_att=oci_fetch_array($stid_att))
        { 
		echo "Project Name : ".$row_att[0];
		}

?>
</h6>     
</div>

<style>
tr > td:last-of-type {
display: none !important;   
} 

</style>          


    <?php 
	while($row4=oci_fetch_array($collection_info_stid))
        {
	
				  
				 $input_arr[] = array(
					'name'		=> $row4[0],
					'month' 	=> $row4[1],
					'fyear'    	=> $row4[2],
					'sing05'    => $row4[3],
					'sing60abv' => $row4[4],
					'totsing'   => $row4[5],
					'totcred'   => $row4[6],
					'totgross'  => $row4[7],
					'ren'    	=> $row4[8],
					'totprem'   => $row4[9],
					'dco'  		=> $row4[10]
					
				);
		}
		//echo findWhere($input_arr, array('dco' => "0013"));
		//echo array_search("0013", array_column($input_arr, 'dco'));
		$sum = 0;

foreach($input_arr as $item) {
	$sum += $item['sing05'];
	echo $sum."</br>"; // output 5 
}
echo $sum; // output 5 
?> 


</div>

<?php }?>
	<?php }
	else {
		echo "you are not permitted to view this report";
		}
?>
<?php include 'footer.php';?>
