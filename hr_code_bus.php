<?php include 'header.php';?>
<style>
.table td, .table th {
    padding: 05 px;
    vertical-align: top;
    border-top: 1px solid #e9ecef;
	font-size:78%;
}
</style>
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
       <input type="text" name="c_num" placeholder="Code Number" class="form-control" style="width: 180px;" required>
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

$n_tot=0;
$s_tot=0;
$no_tot=0;
$d_tot=0;
$r_tot=0;
$rn_tot=0;
?>






<?php
$collection_info_query = "select to_char(to_date(bmon,'yyyymm'),'Mon,yy') as month, SUM(NVL(NEW_BUSINESS,0)) AS NEW_BUSINESS,SUM(NVL(SING,0)) AS SING,SUM(NVL(NEW_POL,0)) AS NEW_NOP,
SUM(NVL(DEFERRED,0)) AS DEFERRED, SUM(NVL(RENEWAL,0)) AS RENEWAL,SUM(NVL(REN_POL,0)) AS REN_NOP
from IPL.ORG_CODE_BUSINESS_WEB
where '$c_num' in(rtrim(oc1),rtrim(oc2),rtrim(oc3),rtrim(oc4),rtrim(oc5),rtrim(oc6),rtrim(oc7),rtrim(oc8),rtrim(oc9),rtrim(oc10),RTRIM(OC11))
and LTRIM(RTRIM(bmon)) between '$f_mon' and '$t_mon'
group by bmon,to_char(to_date(bmon,'yyyymm'),'Mon,yy')
order by bmon,to_char(to_date(bmon,'yyyymm'),'Mon,yy')";				
$collection_info_stid = OCIParse($conn, $collection_info_query);
OCIExecute($collection_info_stid);
?>
 <div class="table-responsive">  
           
 <div align="center">
    <h4>Business Statement of Current Designation</h4>
<h6><?php 

 echo "Closing Month : ".(date('F', mktime(0, 0, 0, $_GET["fmon"], 10))).", ".($_GET["fyear"])." To ".(date('F', mktime(0, 0, 0, $_GET["tomon"], 10))).", ".($_GET["toyear"]); // March

// echo "Closing Month : ".date("m",strtotime($_GET["tomon"]));
//echo substr($f_mon,4);
//echo date("d F Y h:i:s A", strtotime("2011-06-01 11:15 PM")) . "\n";
?></h6>    
<h6> 
<?php
$query_att = "select CODE,DSGN,NAME from ipl.organiser_ipl  where CODE='$c_num'";				
	$stid_att = OCIParse($conn, $query_att);
	  OCIExecute($stid_att);
	while($row_att=oci_fetch_array($stid_att))
        { 
		
		if($row_att[1]='10'){
			$pos="FA";
			}
		if($row_att[1]='09'){
			$pos="UM";
			}
				if($row_att[1]='08'){
			$pos="BM";
			}
		if($row_att[1]='07'){
			$pos="BC";
			}
		if($row_att[1]='06'){
			$pos="DC";
			}
		if($row_att[1]='05'){
			$pos="RC";
			}
			
		if($row_att[1]='04'){
			$pos="DIVC";
			}
		echo $row_att[2]."-".$row_att[0]."-".$pos;
		}

?>
</h6>   
</div>
  <table class="table">
    <thead>
      <tr>
        <th>Month</th>
        <th>New Business</th>
        <th>Sing</th>
        <th>New NOP</th>
        <th>Deferred</th>
       <th>First  Year</th>
       <th>Renweal</th>
       <th>Ren No</th>
       <th> TOTAL</th>
      </tr>
    </thead>
    <tbody>
    <?php 
	while($row4=oci_fetch_array($collection_info_stid))
                                            {
                                              ?> 
                                            <tr>
                                            <td><?php echo $row4[0]; ?></td>
                                            <td><?php echo $row4[1]; $n_tot+=$row4[1]; ?></td>
                                            <td><?php echo $row4[2]; $s_tot+=$row4[2];?></td> 
                                            <td><?php echo $row4[3]; $no_tot+=$row4[3];?></td>
                                            <td><?php echo $row4[4]; $d_tot+=$row4[4];?></td>
                                            <td><?php echo $row4[1]+$row4[2]+$row4[4];?></td>
                                            <td><?php echo $row4[5]; $r_tot+=$row4[5];?></td>
                                            <td><?php echo $row4[6]; $rn_tot+=$row4[6];?></td>
                                            <td><?php echo $row4[1]+$row4[2]+$row4[4]+$row4[5]; ?></td>
                                            </tr>
                                            <?php  } ?> 
                                            <tr>
                                            <td><strong>Total</strong></td>
                                            <td><strong><?php echo $n_tot;?></strong></td>
                                            <td><strong><?php echo $s_tot;?></strong></td> 
                                            <td><strong><?php echo $no_tot;?></strong></td>
                                            <td><strong><?php echo $d_tot;?></strong></td>
                                            <td><strong><?php echo $n_tot+$s_tot+$d_tot;?></strong></td>
                                            <td><strong><?php echo $r_tot;?></strong></td>
                                            <td><strong><?php echo $rn_tot;?></strong></td>
                                            <td><strong><?php echo $n_tot+$s_tot+$d_tot+$r_tot;?></strong></td>
                                            </tr>
    </tbody>
  </table>
</div>






<?php
}
?>




<?php include 'footer.php';?>
