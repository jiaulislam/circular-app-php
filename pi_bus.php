<?php include 'header.php';


	$query_oth = "select count(*) from plil.syrights where PROGNAME='project_wise_business' and USERCODE='$user_co'";				
	$stid_oth = OCIParse($conn, $query_oth);
	  OCIExecute($stid_oth);
	while($row_oth=oci_fetch_array($stid_oth))
        { 
		$oth_cou = $row_oth[0];
		}
?>
<script type="text/javascript" id="js">$(document).ready(function() {
	// call the tablesorter plugin
	$("table").tablesorter({
		// sort on the first column and third column, order asc
		sortList: [[1,1]]
	});
}); </script>

<style>
.table td, .table th {
    padding: 05 px;
    vertical-align: top;
    border-top: 1px solid #e9ecef;
	font-size:78%;
}
</style>

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
  </tr>
</table>
</div>
</form>

<?php 

if(isset($_GET["fmon"])&&isset($_GET["fyear"])&&isset($_GET["tomon"])&&isset($_GET["toyear"])) {
$f_mon= $_GET["fyear"].$_GET["fmon"];  
$t_mon= $_GET["toyear"].$_GET["tomon"]; 
$collection_info_query = "select DECODE(PRJ_CODE,'08','IPL-BOKUL','01','IPL-POLASH','20','TAKAFUL-EKHLAS','04','IDPS','17','IPL-KRISHNACHURA','09','METRO','24','PRAGATIBIMA','05','PRAGATIBIMA',
'Group Life','Group Life','Health Insurance','Health Insurance','GP TONIC','GP TONIC','SNV PILOT PROJECT','SNV PILOT PROJECT','OTHERS','ADC_OTHERS','OTHERS') prj_code,
sum(nvl(new_business,0)),sum(nvl(deferred,0)),sum(nvl(sing,0)),sum(nvl(grp_business,0)),sum(nvl(renewal,0)),sum(nvl(grp_buss,0))from
IPL.PROJECT_BUSINESS_CREDIT_WEV a  WHERE a.bmon between '$f_mon' AND '$t_mon' and A.PRJ_CODE IS NOT NULL and prj_code not in('A1','A2')
group by DECODE(PRJ_CODE,'08','IPL-BOKUL','01','IPL-POLASH','20','TAKAFUL-EKHLAS','04','IDPS','17','IPL-KRISHNACHURA','09','METRO','24','PRAGATIBIMA','05','PRAGATIBIMA',
'Group Life','Group Life','Health Insurance','Health Insurance','GP TONIC','GP TONIC','SNV PILOT PROJECT','SNV PILOT PROJECT','OTHERS','ADC_OTHERS','OTHERS')
ORDER BY DECODE(PRJ_CODE,'IPL-POLASH',1,'IPL-BOKUL',2,'TAKAFUL-EKHLAS',3,'PRAGATIBIMA',4,'METRO',5,'IPL-KRISHNACHURA',6,'IDPS',7,'OTHERS',8,'Group Life',9,'Health Insurance',10,'GP TONIC',11,'SNV PILOT PROJECT','12',13)";				
$collection_info_stid = OCIParse($conn, $collection_info_query);
OCIExecute($collection_info_stid);
?>

 <div class="table-responsive">  
 <div align="center">
    <h4>Project Wise Premium Received</h4>
<h6><?php 

 echo "Closing Month : ".(date('F', mktime(0, 0, 0, $_GET["fmon"], 10))).", ".($_GET["fyear"])." To ".(date('F', mktime(0, 0, 0, $_GET["tomon"], 10))).", ".($_GET["toyear"]); // March

// echo "Closing Month : ".date("m",strtotime($_GET["tomon"]));
//echo substr($f_mon,4);
//echo date("d F Y h:i:s A", strtotime("2011-06-01 11:15 PM")) . "\n";
?></h6>       
</div>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Project</th>
        <th align="right">New Business</th>
        <th align="right">Deferred</th>
        <th align="right">Single</th>
        <th align="right">Group Business</th>
        <th align="right">First Year</th>
        <th align="right">Renewal</th>
        <th align="right">Group</th>
        <th align="right">Total</th>
      </tr>
    </thead>
    <tbody>
    <?php 
	while($row4=oci_fetch_array($collection_info_stid))
                                            {
                                              ?> 
                                               <tr>
                                            <td style="width:10 px;"><?php echo $row4[0]; ?></td>
                                            <td align="right"><?php echo $row4[1]; ?></td>
                                            <td align="right"><?php echo round ($row4[2]); ?></td> 
                                            <td align="right"><?php echo round ($row4[3]); ?></td>
                                            <td align="right"><?php echo round ($row4[4]); ?></td>
                                            <td align="right"><?php echo round ($row4[1]+$row4[2]+$row4[3]+$row4[4]);?></td> 
                                            <td align="right"><?php echo round ($row4[5]); ?></td>
                                            <td align="right"><?php echo round ($row4[6]); ?></td>
                                            <td align="right"><?php echo round ($row4[1]+$row4[2]+$row4[3]+$row4[4]+$row4[5]+$row4[6]); ?></td>

 
                                            </tr>
                                            <?php  } ?> 
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