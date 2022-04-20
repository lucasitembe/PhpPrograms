<link rel="stylesheet" href="table1.css" media="screen"> 
<?php
    include("./includes/connection.php");
    @session_start();
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
    if(isset($_GET['consultation_id'])){
        $consultation_id = $_GET['consultation_id'];
    }
    
      $employee_ID=$_SESSION['userinfo']['Employee_ID'];
?>
    
<table width='100%'>
    <tr id='thead'>
    <td width='2%'><center><b>SN</b></center></td>
    <td width='70%'><center><b>Disease</b></center></td>
    <td width='20%'><b><center>Code</center></b></td>
    <td width='8' align='center'><center><b>Action</b></center></td>
    </tr>
    <?php
    $get_icd_9_or_10_result=mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='Icd_10OrIcd_9'") or die(mysql_error);
    if(mysqli_num_rows($get_icd_9_or_10_result)>0){
        $configvalue_icd10_9=mysqli_fetch_assoc($get_icd_9_or_10_result)['configvalue'];
    }
    $qr = "SELECT * FROM tbl_disease_consultation dc,tbl_disease d,tbl_consultation co
		    WHERE dc.consultation_ID=co.consultation_ID
		    AND dc.consultation_ID =$consultation_id
		    AND dc.disease_ID = d.disease_ID
		    AND dc.diagnosis_type ='$Consultation_Type'
		    AND co.Registration_ID='$Registration_ID' AND disease_version='$configvalue_icd10_9'"
                . " AND dc.employee_ID='$employee_ID'";
    $result = mysqli_query($conn,$qr);
    $i =1;
    while($row = mysqli_fetch_assoc($result)){
	   $disease_ID = $row['disease_ID'];//$disease_ID
        ?>
    <tr>
    <td><center><b><?php echo $i;?></b></center></td>
    <td><input type='text' value='<?php echo $row['disease_name'];?>' readonly='readonly'  style='width: 100%'> </td>
    <td ><input type='text'  value='<?php echo $row['disease_code'];?>' readonly='readonly'  style='width: 100%'></td>
    <td align='center' width='2%'style='background-color: #FFFFFF;'><input type='button' value='Remove' onclick="removediagnosis('<?php echo $row['Disease_Consultation_ID']?>','<?php echo $disease_ID?>')"></td>
    </tr>
        <?php
        $i++;
    }
    ?>
</table>