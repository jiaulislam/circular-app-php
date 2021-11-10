<?php include 'header.php';
	$query_oth = "select count(*) from plil.syrights where PROGNAME='hr_business_rpt' and USERCODE='$user_co'";				
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
<div class="row" style="height: 40px;">
    <div>
        <div class="form-group"></div>
    </div>
    <div>
        <div class="form-group">
        <div class="form-group"> 
       <input type="text" name="c_num" placeholder="Code Number" class="form-control" style="width: 180px;">
        </div>
        </div>
    </div>
</div>
    </td>
    <td width="66">
    <button type="submit" class="btn btn-success btn-lg btn-block" style=" height:126px;">Submit</button>

    </td>
        <td width="71">
    <button type="button" class="btn btn-primary btn-lg btn-block" onclick="location.href='home.php'" style=" height:126px;">Home</button>

    </td>
  </tr>
</table>
</div>



</form>
<?php 

if(isset($_GET["fmon"])&&isset($_GET["fyear"])&&isset($_GET["tomon"])&&isset($_GET["toyear"])&&isset($_GET["c_num"])) {
$f_mon= $_GET["fyear"].$_GET["fmon"];  
$t_mon= $_GET["toyear"].$_GET["tomon"];
$c_num= $_GET["c_num"];


$nb_sum=0; 
$d_sum=0;
$s0_sum=0;
$s6_sum=0;
$ts_sum=0;
$tc_sum=0;
$tg_sum=0;
$rp_sum=0;
$to_sum=0;


?>




<?php
$collection_info_query ="
SELECT CODE,NAME,DESIG,round(SUM(NVL(FYEAR,0))) AS FYEAR,round(SUM(NVL(SING,0))) AS SING,round(SUM(NVL(SING05,0))) AS SING05,
round(SUM(NVL(SING6ABOVE,0))) AS SING6ABOVE,
round(SUM(NVL(CREDIT_FY,0))) AS CREDIT_FY,
round(SUM(NVL(GROSS_FY,0))) AS GROSS_FY,
round(SUM(NVL(RENEWAL,0))) AS RENEWAL,
round(SUM(NVL(TOTAL,0))) AS TOTAL from (
SELECT A.PRJ_CODE, B.BMON,TO_CHAR(TO_DATE(B.BMON,'YYYYMM'),'Mon') as month,
(SELECT PROJECT FROM IPL.PROJECT B WHERE A.PRJ_CODE=B.PRJ_CODE)AS PROJECT,
A.CODE,A.NAME,A.DESIG,round(SUM(NVL(B.FYEAR,0))) AS FYEAR,round(SUM(NVL(B.SING,0))) AS SING,round(SUM(NVL(B.SING0_5,0))) AS SING05,round(SUM(NVL(B.SING6_ABOVE,0))) AS SING6ABOVE,
round(SUM(NVL(B.FYEAR,0)+NVL(B.SING0_5*.15,0)+NVL(B.SING6_ABOVE*.20,0))) AS CREDIT_FY,
round(SUM(NVL(B.FYEAR,0)+NVL(B.SING0_5,0)+NVL(B.SING6_ABOVE,0))) AS GROSS_FY,
round(SUM(NVL(B.RENEWAL,0))) AS RENEWAL,
round(SUM(NVL(B.FYEAR,0)+NVL(B.SING,0)+NVL(B.RENEWAL,0))) AS TOTAL
FROM IT.HR_STAFF_PROJECT A,IT.HR_STAFF_BUSINESS B WHERE A.CODE=B.CODE and upper(a.status) = 'A'
and b.bmon BETWEEN '$f_mon' AND '$t_mon' and
(a.prj_code='$pro_co' or '$pro_co' is null) 
and (a.code='$c_num' OR '$c_num' IS NULL)
GROUP BY A.PRJ_CODE,DECODE(A.PRJ_CODE,'01','IPL-POLASH','02','IEB','03','TAKAFUL','04','IDPS','05','PBPIB','08','IPL-BOKUL','99','NOT MENTIONED') ,
A.CODE,A.NAME,A.DESIG,B.BMON
) 
group by PRJ_CODE,PROJECT, CODE,NAME,DESIG
";
/*
$collection_info_query ="select a.prj_code,a.PROJECT,a.dsgn_id,a.hr_layer,A.hr_code,A.hr_name,A.DESIG,
a.DEV_CODE,--case when a.DEV_CODE='9999999999' then 'Others' else a.DEV_CODE end dev_code,
case when a.dev_name='Others' then 'Others' else a.dev_name end dev_name,--a.dev_status,
round(SUM (NVL (new_business, 0)),0) AS new_business,round(SUM(NVL(a.SING,0)),0) AS SING,
round(SUM(NVL(a.SING0_5,0)),0) AS SING0_5,round(SUM(NVL(a.SING6_ABOVE,0)),0) AS SING6_ABOVE,
round(SUM(NVL(a.FYEAR,0)),0) fyear,
round(SUM(NVL(a.fy_premium,0)),0) AS fy_premium,
round(sum(nvl(a.Credit_FY,0)),0) AS Credit_FY,
round(sum(nvl(a.DEFERRED,0)),0) deferred,
round(SUM(NVL(a.RENEWAL,0)),0) AS RENEWAL,
round(SUM(NVL(total_premium,0)),0) AS total_premium
from
(
SELECT
A.PRJ_CODE,(SELECT PROJECT FROM IPL.PROJECT B WHERE A.PRJ_CODE=B.PRJ_CODE)AS PROJECT,
A.CODE hr_code,A.NAME hr_name,A.CODE||'-'||A.NAME hrcode_name,A.DESIG,
(select distinct d.id from ipl.DEV_DESIGNATION d where ltrim(rtrim(d.dsgn)) = ltrim(rtrim(a.DESIG))) dsgn_id,
decode(upper(a.PAY_STATUS),'B','7','8') hr_layer,
(case when round(SUM(NVL(B.FYEAR,0)+NVL(B.SING,0)),0) > 0 then B.ORG_HEAD else 'Others' end) DEV_CODE,
(case when round(SUM(NVL(B.FYEAR,0)+NVL(B.SING,0)),0) > 0 then (SELECT NAME FROM IPL.ORG_ALL o WHERE B.ORG_HEAD=o.CODE) else 'Others' end) DEV_NAME,
(SELECT upper(STATUS) FROM IPL.ORG_ALL o WHERE B.ORG_HEAD=o.CODE) AS dev_status,
round(SUM (NVL (new_business, 0)),0) AS new_business,round(SUM(NVL(B.SING,0)),0) AS SING,
round(SUM(NVL(B.SING0_5,0)),0) AS SING0_5,round(SUM(NVL(B.SING6_ABOVE,0)),0) AS SING6_ABOVE,
round(SUM(NVL(B.FYEAR,0)),0) fyear,round(SUM(NVL(B.FYEAR,0)+NVL(B.SING,0)),0) AS fy_premium,
round(sum(nvl(b.FYEAR,0)+NVL(b.SING0_5*.15,0)+NVL(b.SING6_ABOVE*.20,0)),0) AS Credit_FY,
round(sum(nvl(b.DEFERRED,0)),0) deferred,round(SUM(NVL(B.RENEWAL,0)),0) AS RENEWAL,
round(SUM(NVL(B.FYEAR,0)+NVL(B.SING,0)+NVL(B.RENEWAL,0)),0) AS total_premium
FROM IT.HR_STAFF_PROJECT A, IT.HR_STAFF_BUSINESS B
WHERE A.CODE=B.CODE and upper(a.status) = 'A'
and b.bmon BETWEEN '$f_mon' and '$t_mon' and
A.DESIG is not null and
(a.prj_code='$pro_co' or '$pro_co' is null)
and (a.code='$c_num' OR '$c_num' IS NULL)
GROUP BY A.PRJ_CODE,A.CODE,A.NAME,A.DESIG,B.ORG_HEAD,decode(upper(a.PAY_STATUS),'B','7','8')--,B.SCCODE
) A
group by a.prj_code,a.PROJECT,a.dsgn_id,a.hr_layer,A.hr_code,A.hr_name,A.DESIG,a.DEV_CODE,a.dev_name
order by a.dsgn_id,a.hr_code,a.DEV_CODE";

$collection_info_query = "SELECT B.ORG_HEAD,(SELECT NAME FROM IPL.ORG_ALL B WHERE B.ORG_HEAD=B.CODE)AS ORGHEAD_NAME,
round(SUM(NVL(B.FYEAR,0))) AS FYEAR,round(SUM(NVL(B.SING,0))) AS SING,round(SUM(NVL(B.RENEWAL,0))) AS RENEWAL
FROM IT.HR_STAFF_PROJECT A,IT.HR_STAFF_BUSINESS_WEV B WHERE A.CODE=B.CODE
and b.bmon BETWEEN '$f_mon' AND '$t_mon'
and a.code='$c_num'
and A.PRJ_CODE='$pro_co'
GROUP BY A.PRJ_CODE,A.CODE,A.NAME,A.DESIG,B.ORG_HEAD,B.SCCODE";	*/			
$collection_info_stid = OCIParse($conn, $collection_info_query);
OCIExecute($collection_info_stid);
?>
 <div class="table-responsive">  
       <div align="center">
    <h4>Project Wise HR Staff Business Summary</h4>
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

<h6> 
<?php
$query_att = "select * from IT.HR_STAFF_PROJECT a ,ipl.project b where a.CODE='$c_num' and b.PRJ_CODE='$pro_co'";				
	$stid_att = OCIParse($conn, $query_att);
	  OCIExecute($stid_att);
	while($row_att=oci_fetch_array($stid_att))
        { 
		echo $row_att[0]."  ".$row_att[1]."(".$row_att[2].")";
		}

?>
</h6>
</div>     

  <table class="table">
    <thead>
      <tr>
        <th>ORG. HEAD CODE</th>
        <th>ORG. HEAD NAME</th>
		<th>DESIG</th>
        <th>FIRST YEAR</th>
        <th>TOTAL SINGLE</th>
		<th>SINGLE 0-5</th>
        <th>SINGLE 6-ABOVE</th>
        <th>TOTAL CREDIT FY</th>
        <th>TOTAL GROSS FY</th>
        <th>RENEWAL</th>
        <th>TOTAL PREMIUM</th>

      </tr>
    </thead>
    <tbody>
    <?php 
	while($row4=oci_fetch_array($collection_info_stid))
                                            {
                                              ?> 
                                            <tr>
                                            <td><?php echo $row4[0]; ?></td>
                                            <td><?php echo $row4[1]; ?></td>
											<td><?php echo $row4[2]; ?></td>
                                            <td><div align="left"><?php echo $row4[3]; $nb_sum+=$row4[3];?></div></td> 
                                            <td><div align="left"><?php echo $row4[4]; $ts_sum+=$row4[4];?></div></td> 
                                            <td><div align="left"><?php echo $row4[5]; $s0_sum+=$row4[5];?></div></td>
                                            <td><div align="left"><?php echo $row4[6]; $s6_sum+=$row4[6];?></div></td> 
                                            <td><div align="left"><?php echo $row4[7]; $tc_sum+=$row4[7];?></div></td>
                                            <td><div align="left"><?php echo $row4[8]; $tg_sum+=$row4[8];?></div></td>
                                            <td><div align="left"><?php echo $row4[9]; $rp_sum+=$row4[9];?></div></td>
											<td><div align="left"><?php echo $row4[10]; $to_sum+=$row4[10];?></div></td>
                                            
                                            </tr>
                                            <?php  } ?> 
                                            <tr>
                                            <td></td>
											<td></td>
                                            <td><strong>Total</strong></td>
                                            <td><div align="left"><strong><?php echo $nb_sum;?></strong></div></td> 
                                            <td><div align="left"><strong><?php echo $ts_sum;?></strong></div></td> 
                                           <td><div align="left"><strong><?php echo $s0_sum;?></strong></div></td>
                                            <td><div align="left"><strong><?php echo $s6_sum;?></strong></div></td>
                                            <td><div align="left"><strong><?php echo $tc_sum;?></strong></div></td>
                                            <td><div align="left"><strong><?php echo $tg_sum;?></strong></div></td>
                                            <td><div align="left"><strong><?php echo $rp_sum;?></strong></div></td>  
                                            <td><div align="left"><strong><?php echo $to_sum;?></strong></div></td>  
                                            </tr>
    </tbody>
  </table>
</div>






<?php }?>
	<?php }
	else {
		echo "you are not permitted to view this report";
		}
?>
<?php include 'footer.php';?>