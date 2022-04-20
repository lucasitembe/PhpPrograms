<?php
    include("./includes/connection.php");
    if(isset($_GET['Payment_Cache_ID'])){
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    $payment_cache_ID = $Payment_Cache_ID;
    }else{
        $Payment_Cache_ID = 0;
    }
    if(isset($_GET['Consultation_Type'])){
        $Consultation_Type = $_GET['Consultation_Type'];
    }
    else{
        $Consultation_Type = 0;
    }
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }
    else{
        $Registration_ID = 0;
    }
    if(isset($_GET['Round_ID'])){
        $Round_ID = $_GET['Round_ID'];
    }
    
?>
    
<table width='100%'>
    <tr id='thead'>
    <td style='width:10%'><center><b>SN</b></center></td>
    <td><b>Disease</b></td>
    <td width='20%'><b>Code</b></td>
    <td align='center'><b>Action</b></td>
    </tr>
    <?php
      $get_icd_9_or_10_result=mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='Icd_10OrIcd_9'") or die(mysql_error);
    if(mysqli_num_rows($get_icd_9_or_10_result)>0){
        $configvalue_icd10_9=mysqli_fetch_assoc($get_icd_9_or_10_result)['configvalue'];
    }
    $qr = "SELECT * FROM tbl_ward_round_disease wd,tbl_disease d,tbl_ward_round wr
		    WHERE wd.Round_ID=wr.Round_ID
		    AND wd.Round_ID =$Round_ID
		    AND wd.disease_ID = d.disease_ID
		    AND wd.diagnosis_type ='$Consultation_Type'
		    AND wr.Registration_ID='$Registration_ID' AND disease_version='$configvalue_icd10_9'";
			//echo $qr;exit;
    $result = mysqli_query($conn,$qr) OR die(mysqli_error($conn));
    $i =1;
    while($row = mysqli_fetch_assoc($result)){
	$disease_ID = $row['disease_ID'];
        ?>
    <tr>
    <td><center><b><?php echo $i;?></b></center></td>
    <td><input type='text' value='<?php echo $row['disease_name'];?>' readonly style='width: 100%'> </td>
    <td><input type='text' value='<?php echo $row['disease_code'];?>' readonly style='width: 100%'></td>
    <td align='center' width='2%'style='background-color: #FFFFFF;'><input type='button' value='Remove' onclick="removediagnosis('<?php echo $row['Ward_Round_Disease_ID']?>','disease_<?php echo $disease_ID; ?>')"></td>
    </tr>
        <?php
        $i++;
    }
    ?>
</table>