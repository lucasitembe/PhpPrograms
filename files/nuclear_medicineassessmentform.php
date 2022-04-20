<?php
    // include("../includes/connection.php");
    include("./includes/connection.php");
    include("./includes/header.php");
    session_start();
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if (isset($_GET['Patient_Payment_ID'])) {
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    } else {
        $Patient_Payment_ID = 0;
    }

    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }

    if (isset($_GET['Item_List_ID'])) {
        $Payment_Item_Cache_List_ID = $_GET['Item_List_ID'];
    } else {
        $Payment_Item_Cache_List_ID = 0;
    }

     //get Payment_Cache_ID and consultation_id
     
     $select = mysqli_query($conn,"select pc.consultation_id, pc.Payment_Cache_ID,ilc.Doctor_Comment from 
     tbl_item_list_cache ilc, tbl_payment_cache pc where
     pc.Payment_Cache_ID = ilc.Payment_Cache_ID and 
     Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
$no = mysqli_num_rows($select);
if($no > 0){
    while ($data = mysqli_fetch_array($select)) {
        $Payment_Cache_ID = $data['Payment_Cache_ID'];
        $consultation_id = $data['consultation_id'];
        $consultation_id_to_use=$data['consultation_id'];
        $Doctor_Comment = $data['Doctor_Comment'];
    }
}else{
    $Payment_Cache_ID = 0;
    $consultation_id = 0;
}

//get procedure name
$select = mysqli_query($conn,"SELECT Product_Name, ilc.Status, ilc.Item_ID from tbl_items i, tbl_item_list_cache ilc where
     i.Item_ID = ilc.Item_ID and
     ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
$no = mysqli_num_rows($select);
if($no > 0){
while ($dt = mysqli_fetch_array($select)) {
    $Product_Name = $dt['Product_Name'];
    $Status = $dt['Status'];
    $Item_ID = $dt['Item_ID'];
}
}else{
$Product_Name = '';
$Status = '';
}


$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $Surgery = $new_Date;
} 
$select_item_template = mysqli_query($conn, "SELECT * FROM tbl_template_report_nm WHERE Item_ID='$Item_ID'") or die(mysqli_error($conn));
if(mysqli_num_rows($select_item_template)>0){
    while($rows = mysqli_fetch_assoc($select_item_template)){
        $findings = $rows['findings'];
        $procedure_done = $rows['procedure_done'];
        $protocal = $rows['protocal'];
        $functions = $rows['functions'];
        $phase = $rows['phase'];
        $Template_ID = $rows['Template_ID'];
    }
}
$Employee_Name = $_SESSION['userinfo']['Employee_Name'];

//get patient details
$select = mysqli_query($conn,"SELECT Patient_Name, Gender, Date_Of_Birth, Guarantor_Name, Member_Number from  tbl_patient_registration pr, tbl_sponsor sp where  pr.Sponsor_ID = sp.Sponsor_ID and  Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if($num > 0){
    while ($data = mysqli_fetch_array($select)) {
        $Patient_Name = $data['Patient_Name'];
        $Gender = $data['Gender'];
        $Date_Of_Birth = $data['Date_Of_Birth'];
        $Guarantor_Name = $data['Guarantor_Name'];
        $Member_Number = $data['Member_Number'];
    }
}else{
    $Patient_Name = '';
    $Gender = '';
    $Date_Of_Birth = '';
    $Guarantor_Name = '';
    $Member_Number = '';
}

$date1 = new DateTime($Today);
$date2 = new DateTime($Date_Of_Birth);
$diff = $date1 -> diff($date2);
$Age = $diff->y." Years, ";
$Age .= $diff->m." Months, ";
$Age .= $diff->d." Days";

//get procedure name
$select = mysqli_query($conn,"SELECT Product_Name, ilc.Status, ilc.Item_ID, Consultant, Transaction_Date_And_Time from tbl_items i, tbl_item_list_cache ilc where i.Item_ID = ilc.Item_ID  and ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
$no = mysqli_num_rows($select);
    if($no > 0){
        while ($dt = mysqli_fetch_array($select)) {
        $Product_Name = $dt['Product_Name'];
        $Status = $dt['Status'];
        $Item_ID = $dt['Item_ID'];
        $Consultant = $dt['Consultant'];
        $Transaction_Date_And_Time = $dt['Transaction_Date_And_Time'];
        }
    }else{
    $Product_Name = '';
    $Status = '';
    }
?>

<input type="button" value="THERAPY CHECKLIST" class="art-button-green" onclick="open_therapychecklist()">
<input type="button" value="RAIOT FOLLOW UP FORM" class="art-button-green" onclick="open_riotfollowupform()">
<a href="#" onclick="goBack()"class="art-button-green">BACK</a>

<script>
function goBack() {
    window.history.back();
}
</script>
<style>
   
</style>
<fieldset>
    <legend align="center"><b>RADIO - ACTIVE IODINE THERAPY FOR HYPERTHYROIDISM</b>
    <br>    <h5 align='center'> ASSESSMENT FORM</h5></legend>
    <table width="100%"> 
        <tr><td  width="9%" style="text-align: right;"><b>Patient Name</b></td>
        <td><input type="text" value="<?php echo $Patient_Name; ?>" readonly="readonly"></td>
        <td width="9%" style="text-align: right;"><b>Sponsor Name</b></td>
        <td><input type="text" value="<?php echo $Guarantor_Name; ?>" readonly="readonly"></td>
        <td style="text-align: right;"><b>Gender</b> </td>
        <td><input type="text" value="<?php echo $Gender; ?>" readonly="readonly"></td>
        <td style="text-align: right;"><b>Age</b></td>
        <td><input type="text" value="<?php echo $Age; ?>" readonly="readonly"></td>
        <td style="text-align:right;" ><b>Procedure Date </b></td>
        <td><input type="text" autocomplete="off" name="Procedure_Date" id="Procedure_Date" value="<?php echo $Surgery_Date; ?>" readonly="readonly"></td>
        </tr>
        
    </table>
     
    <table width="100%">        
        <tr>            
            <td width=40% style="text-align:right">
                
                <label for="">SCAN:</label>
                <span style="">
                    <input id='Procedure_Name'  name='Procedure_Name' required='required' style="display:inline; width: 80%;" readonly="readonly" value="<?php echo $Product_Name; ?>">
                </span>                
            </td>
            <td style="text-align:right;" width="30%">
                <label for="">Date Sent:</label>
                <span>
                    <input type="text" value="<?php echo $Transaction_Date_And_Time; ?>" style="display:inline; width: 70%;">
                </span>
            </td>
            <td width="30%">
                <label for="">Referring Doctor</label>
                <input type="text" readonly="readonly" style="display:inline; width: 70%;" value="<?php echo $Consultant; ?>">
            </td>
        </tr>    
    </table>   

</fieldset>
<fieldset id="fieldset">
    <legend align='center'></legend>
    <?php 
        $select_assessment = mysqli_query($conn, "SELECT * FROM tbl_nm_assessmentform WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
        if(mysqli_num_rows($select_assessment)>0){
            while($row = mysqli_fetch_assoc($select_assessment)){
                $clinicalhistory = explode(',',$row['clinicalhistory']);
                $Employee_ID = $row['Employee_ID'];
                $Assessment_ID = $row['Assessment_ID'];
                $therapychecklist = $row['therapychecklist'];
                $therapychecklist = explode(',', $row['therapychecklist']);
                if($therapychecklist[0]=='normalYes'){
                    $Normal = "checked='checked'";
                }else{
                    $Normal1 = "checked='checked'";
                }
                if($therapychecklist[1]=="Yes"){
                    $Proptosis="checked='checked'";
                }else{
                    $Proptosis1="checked='checked'";
                }
                $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID ='$Employee_ID'"))['Employee_Name'];
    ?>
    <table class="table">
        <tr>
            <td><h4>CLINICAL HISTORY AND EXAMINATION: </h4></td>
            
        </tr>
        <tr>
            <td>
                <label for="">Occupation</label>
                <input type="text"  style="display:inline; width: 80%;" value="<?php echo $clinicalhistory[0]; ?>" name="clinicalhistory[]">
            </td>
            <td>
                <label for="">Children at Home:</label>
                <input type="text"  style="display:inline; width: 80%;" value="<?php echo $clinicalhistory[1]; ?>" name="clinicalhistory[]">
            </td>
        </tr>
        <?php
            if($sex =="Female"){?>
                    <tr>
                        <td>
                            <label for="">Contraception Status</label>
                            <input type="text"  style="display:inline; width: 80%;" value="<?php echo $clinicalhistory[0]; ?>" name="clinicalhistory[]">
                        </td>
                        <td>
                            <label for="">Breast feeding status:</label>
                            <input type="text"  style="display:inline; width: 80%;" value="" name="clinicalhistory[]">
                        </td>
                    </tr>          
            <?php }
        ?>
        <tr>
            <td colspan="2">
                <label for="">Main Complaints:</label>
                <textarea  style="display:inline; width: 90%;" name="clinicalhistory[]" rows='1'><?php echo $clinicalhistory[2]; ?> </textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="">Previous medication history:</label>
                <textarea  style="display:inline; width: 85%;" name="clinicalhistory[]" rows='1'><?php echo $clinicalhistory[3]; ?> </textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="">Current medications and duration:</label>
                <textarea  style="display:inline; width: 85%;" name="clinicalhistory[]" rows='1'> <?php echo $clinicalhistory[4]; ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <label for="">Weight</label>
                <input type="text"  style="display:inline; width: 40%;" value="<?php echo $clinicalhistory[5]; ?>" name="clinicalhistory[]">
                <label for="">Height</label>
                <input type="text"  style="display:inline; width: 40%;" value="<?php echo $clinicalhistory[6]; ?>" name="clinicalhistory[]">
            </td>
            <td>
                <label for="">Blood Pressure:</label>
                <input type="text"  style="display:inline; width: 40%;" value="<?php echo $clinicalhistory[7]; ?>" name="clinicalhistory[]">
                <label for="">Pulse rate:</label>
                <input type="text"  style="display:inline; width: 40%;" value="<?php echo $clinicalhistory[8]; ?>" name="clinicalhistory[]">
            </td>
        </tr>
       
        <tr>
            <td>
                <label for="">Oedema</label>
                <input type="text"  style="display:inline; width: 40%;" value="<?php echo $clinicalhistory[9]; ?>" name="clinicalhistory[]">
                <label for="">Tremor</label>
                <input type="text"  style="display:inline; width: 40%;" value="<?php echo $clinicalhistory[10]; ?>" name="clinicalhistory[]">
            </td>
            <td>
                <label for="">Refrexes:</label>
                <input type="text"  style="display:inline; width: 40%;" value="<?php echo $clinicalhistory[11]; ?>" name="clinicalhistory[]">
                <label for="">Myopathy:</label>
                <input type="text"  style="display:inline; width: 40%;" value="<?php echo $clinicalhistory[12]; ?>" name="clinicalhistory[]">
            </td>
        </tr>
        <tr>
            <td>
                <label for="">Respiratory</label>
                <input type="text"  style="display:inline; width: 80%;" value="<?php echo $clinicalhistory[13]; ?>" name="clinicalhistory[]">

            </td>
            <td>
                <label for="">CVS:</label>
                <input type="text"  style="display:inline; width: 95%;" value="<?php echo $clinicalhistory[14]; ?>" name="clinicalhistory[]">
            </td>
        </tr>
        <tr>
            <td colspan='2'>Eye complints and signs: (mark/ or describe if applicable)</td>
        </tr>
        <tr>
            <td>
                <label for="">Normal</label>
                <span>
                        Yes  <input type="radio" <?= $Normal ?>  style="display:inline;" name="therapychecklist" value="Yes">
                        No <input type="radio" <?= $Normal1 ?>  style="display:inline;" name="therapychecklist" value="No">
                    </span>
                <label for="">Proptosis</label>
                <span>
                        Yes  <input type="radio" <?= $Proptosis ?>  style="display:inline;" id="therapychecklist" value="Yes">
                        No <input type="radio" <?= $Proptosis1 ?>  style="display:inline;" id="therapychecklist" value="No">
                        if No Explain <input type="text" name="clinicalhistory[]" style="display:inline; width: 30%;">
                    </span>
            </td>
            <td>
                <label for="">Colour perception:</label>
                <input type="text"  style="display:inline; width: 30%;" value="<?php echo $clinicalhistory[16]; ?>" name="clinicalhistory[]">
                <label for="">Dry eye/tearing:</label>
                <input type="text"  style="display:inline; width: 30%;" value="<?php echo $clinicalhistory[17]; ?>" name="clinicalhistory[]">
            </td>
        </tr>
        <tr> <td colspan='2'><h4>THYROID SCAN BLOOD RESULTS:<h4></td></tr>
        <tr>
            <td>
                <label for="">Tyroid size:</label>
                <input type="text"  style="display:inline; width: 70%;" value="<?php echo $clinicalhistory[18]; ?>" name="clinicalhistory[]"> 
            </td>
            <td>
                <label for="">Distribution of activity:</label>
                <input type="text"  style="display:inline; width: 40%;" value="<?php echo $clinicalhistory[19]; ?>" name="clinicalhistory[]">
                <label for="">Nodules:</label>
                <input type="text"  style="display:inline; width: 30%;" value="<?php echo $clinicalhistory[20]; ?>" name="clinicalhistory[]">
            </td>
        </tr>
        <tr>
            <td>
                <label for="">99TcO2 Uptake:</label>
                <input type="text"  style="display:inline; width: 30%;" value="<?php echo $clinicalhistory[21]; ?>" name="clinicalhistory[]">
                <label for="">Free T4:</label>
                <input type="text"  style="display:inline; width: 30%;" value="<?php echo $clinicalhistory[22]; ?>" name="clinicalhistory[]">
            </td>
            <td>
                <label for="">Free T3::</label>
                <input type="text"  style="display:inline; width: 30%;" value="<?php echo $clinicalhistory[22]; ?>" name="clinicalhistory[]">
                <label for="">TSH:</label>
                <input type="text"  style="display:inline; width: 30%;" value="<?php echo $clinicalhistory[23]; ?>" name="clinicalhistory[]">
            </td>
        </tr> 
    </table>

    <table class="table">
        <tr>
            <td><h4>PLAN OF MANAGEMENT: </h4></td>
            
        </tr>
        <tr>
            <td colspan=""><?= $therapychecklist[2] ?></td>
        </tr>
        <tr>        
            <td>
                <label for="">Carbimazole:</label>
                <input type="text"  style="display:inline; width: 40%;" value="<?php echo $clinicalhistory[24]; ?>" >
                <label for="">Beta blockers:</label>
                <input type="text"  style="display:inline; width: 30%;" value="<?php echo $clinicalhistory[25]; ?>" >
            </td>
           
        </tr>
        <tr>        
            <td>
                <label for="">Steroids:</label>
                <input type="text"  style="display:inline; width: 40%;" value="<?php echo $clinicalhistory[27]; ?>" >
                <label for="">Eye medications:</label>
                <input type="text"  style="display:inline; width: 30%;" value="<?php echo $clinicalhistory[28]; ?>" >
            </td>
            
        </tr>
        <tr>
        <td colspan="">Radioactive Iodine Therapy</td>
        </tr>
        <tr>
             <td>
                <label for="">Planned Date:</label>
                <input type="text"  style="display:inline; width: 50%;" value="<?php echo $clinicalhistory[26]; ?>" > 
                <label for="">Dose:</label>
                <input type="text"  style="display:inline; width: 40%;" value="<?php echo $clinicalhistory[29]; ?>" > 
            </td>
        </tr>
        
        <tr>        
            <td>
                <label for="">Others:</label>
                <textarea type="text"  style="display:inline; width: 40%;" rows="1"><?php echo $clinicalhistory[30]; ?> </textarea>               
            
                <label for="">Approved By:</label>
                <input type="text"  style="display:inline; width: 30%;" value="<?php echo $Employee_Name; ?>" > 
            </td>
        </tr>
    </table>
     <?php }
     }else{ ?>
        <table class="table">
        <tr>
            <td colspan="2"><h4>CLINICAL HISTORY AND EXAMINATION: </h4></td>
            
        </tr>
        <tr>
            <td>
                <label for="">Occupation</label>
                <input type="text"  style="display:inline; width: 80%;" value="" name="clinicalhistory[]">
            </td>
            <td>
                <label for="">Children at Home:</label>
                <input type="text"  style="display:inline; width: 80%;" value="" name="clinicalhistory[]">
            </td>
        </tr>
        <?php
            if($sex =="Female"){?>
                    <tr>
                        <td>
                            <label for="">Contraception Status</label>
                            <input type="text"  style="display:inline; width: 80%;" value="" name="clinicalhistory[]">
                        </td>
                        <td>
                            <label for="">Breast feeding status:</label>
                            <input type="text"  style="display:inline; width: 80%;" value="" name="clinicalhistory[]">
                        </td>
                    </tr>          
            <?php }
        ?>
        <tr>
            <td>
                <label for="">Main Complaints:</label>
                <textarea  style="display:inline; width: 70%;" name="clinicalhistory[]" id="maincomplain" rows='1'> </textarea>
            </td>
       
            <td >
                <label for="">Previous medication history:</label>
                <textarea  style="display:inline; width: 70%;" name="clinicalhistory[]" rows='1'> </textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="">Current medications and duration:</label>
                <textarea  style="display:inline; width: 85%;" name="clinicalhistory[]" rows='1'> </textarea>
            </td>
        </tr>
        <tr>
            <td>
                <label for="">Weight</label>
                <input type="text"  style="display:inline; width: 40%;" value="" name="clinicalhistory[]">
                <label for="">Height</label>
                <input type="text"  style="display:inline; width: 40%;" value="" name="clinicalhistory[]">
            </td>
            <td>
                <label for="">Blood Pressure:</label>
                <input type="text"  style="display:inline; width: 30%;" value="" name="clinicalhistory[]">
                <label for="">Pulse rate:</label>
                <input type="text"  style="display:inline; width: 30%;" value="" name="clinicalhistory[]">
            </td>
        </tr>
       
        <tr>
            <td>
                <label for="">Oedema</label>
                <input type="text"  style="display:inline; width: 40%;" value="" name="clinicalhistory[]">
                <label for="">Tremor</label>
                <input type="text"  style="display:inline; width: 40%;" value="" name="clinicalhistory[]">
            </td>
            <td>
                <label for="">Refrexes:</label>
                <input type="text"  style="display:inline; width: 40%;" value="" name="clinicalhistory[]">
                <label for="">Myopathy:</label>
                <input type="text"  style="display:inline; width: 40%;" value="" name="clinicalhistory[]">
            </td>
        </tr>
        <tr>
            <td>
                <label for="">Respiratory</label>
                <input type="text"  style="display:inline; width: 80%;" value="" name="clinicalhistory[]">

            </td>
            <td>
                <label for="">CVS:</label>
                <input type="text"  style="display:inline; width: 95%;" value="" name="clinicalhistory[]">
            </td>
        </tr>
        <tr>
            <td colspan='2'>Eye compaints and signs: (mark/ or describe if applicable)</td>
        </tr>
        <tr>
            <td>
                <label for="">Normal</label>
                <span>
                        Yes  <input type="radio"   style="display:inline;" id="therapychecklist" value="normalYes">
                        No <input type="radio"  style="display:inline;" id="therapychecklist" value="normalNo">
                    </span>
                <label for="">Proptosis</label>
                <span>
                        Yes  <input type="radio" name="Proptosis" style="display:inline;" id="therapychecklist" value="Yes">
                        No <input type="radio"  name="Proptosis" style="display:inline;" id="therapychecklist" value="No">
                        if No Explain <input type="text" name="therapychecklist[]" style="display:inline; width: 30%;">
                    </span>
            </td>
            <td>
                <label for="">Colour perception:</label>
                <input type="text"  style="display:inline; width: 30%;" value="" name="clinicalhistory[]">
                <label for="">Dry eye/tearing:</label>
                <input type="text"  style="display:inline; width: 30%;" value="" name="clinicalhistory[]">
            </td>
        </tr>
        <tr> <td colspan='2'><h4>THYROID SCAN & BLOOD RESULTS:<h4></td></tr>
        <tr>
            <td>
                <label for="">Tyroid size:</label>
                <input type="text"  style="display:inline; width: 70%;" value="" name="clinicalhistory[]"> 
            </td>
            <td>
                <label for="">Distribution of activity:</label>
                <input type="text"  style="display:inline; width: 40%;" value="" name="clinicalhistory[]">
                <label for="">Nodules:</label>
                <input type="text"  style="display:inline; width: 30%;" value="" name="clinicalhistory[]">
            </td>
        </tr>
        <tr>
            <td>
                <label for="">99mTcO4 Uptake:</label>
                <input type="text"  style="display:inline; width: 30%;" value="" name="clinicalhistory[]">
                <label for="">Free T4:</label>
                <input type="text"  style="display:inline; width: 30%;" value="" name="clinicalhistory[]">
            </td>
            <td>
                <label for="">Free T3::</label>
                <input type="text"  style="display:inline; width: 30%;" value="" name="clinicalhistory[]">
                <label for="">TSH:</label>
                <input type="text"  style="display:inline; width: 30%;" value="" name="clinicalhistory[]">
            </td>
        </tr>       
        
    </table>
    <table class="table">
        <tr>
            <td><h4>PLAN OF MANAGEMENT: </h4></td>
            
        </tr>
        <tr>
            <td colspan=""> 
                <input type="radio" value="Rai" name="Planmanagement" id="therapychecklist"> RAI 
                <input type="radio" value="cooldownrai" name="Planmanagement" id="therapychecklist"> Cool down then RAI  
                <input type="radio" value="medicaltherapy" name="Planmanagement" id="therapychecklist"> Medical therapy  <input type="radio" value="Surgery" name="Planmanagement" id="therapychecklist"> Surgey</td>
        </tr>
        <tr>        
            <td>
                <label for="">Carbimazole:</label>
                <input type="text"  style="display:inline; width: 40%;" value="" name="clinicalhistory[]">
                <label for="">Beta blockers:</label>
                <input type="text"  style="display:inline; width: 30%;" value="" name="clinicalhistory[]">
            </td>
        </tr>
        <tr>        
            <td>
                <label for="">Steroids:</label>
                <input type="text"  style="display:inline; width: 40%;" value="" name="clinicalhistory[]">
                <label for="">Eye medications:</label>
                <input type="text"  style="display:inline; width: 30%;" value="" name="clinicalhistory[]">
            </td>
            
        </tr>
        <tr>
        <td colspan="">Radioactive Iodine Therapy</td>
        </tr>
        <tr>
            <td>
                <label for="">Planned Date:</label>
                <input type="date"  style="display:inline; width: 40%;" value="" name="clinicalhistory[]"> 
                 <label for="">Dose:</label>
                <input type="text"  style="display:inline; width: 40%;" value="" name="clinicalhistory[]"> 
            </td>
          
        </tr>
        <tr>
        
        </tr>
        
        <tr>        
            <td>
                <label for="">Others:</label>
                <input type="text"  style="display:inline; width: 50%;" value="" name="clinicalhistory[]">
                
                <input type="button" value="SAVE" class="art-button-green pull-right" style="align:right; width: 15%;" onclick="save_assessmentform()">
            </td>
        
        </tr>
    </table>
           <?php 
            } 
            
            $select_managmentplan = mysqli_query($conn, "SELECT * FROM tbl_nm_managementplan WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
        if(mysqli_num_rows($select_managmentplan)>0){
            while($row = mysqli_fetch_assoc($select_managmentplan)){
                $managementplan = explode(',',$row['managementplan']);
            ?>
    
    <table class="table">
        <caption>RADIO-ACTIVE IODINE (I-131) THERAPY</caption>
        <tr>
            <td>
                <label for="">Pregnancy test</label>
                <input type="text"  style="display:inline; width: 80%;" value="<?php echo $managementplan[0]; ?>" name="managementplan[]">

            </td>
            <td>
                <label for="">DATE:</label>
                <input type="text"  class="form-control" style="display:inline; width: 60%;" value="<?php echo $managementplan[1]; ?>" name="managementplan[]">
            </td>
        </tr>
        <tr>
            <td>
                <label for="">Carbimazole:</label>
                <input type="text"  style="display:inline; width: 80%;" value="<?php echo $managementplan[2]; ?>" name="managementplan[]">

            </td>
            <td>
                <label for="">I-131 DOSE GIVEN:</label>
                <input type="text"  style="display:inline; width: 70%;" value="<?php echo $managementplan[3]; ?>" name="managementplan[]">
            </td>
        </tr>
        <tr>
            <td>
                <label for="">FOLLOW UP DATE</label>
                <input type="date"  style="display:inline; width: 80%;" value="<?php echo $managementplan[4]; ?>" name="managementplan[]">
            </td>
            <td>
                <label for="">Doctor:</label>
                <input type="text"  style="display:inline; width: 80%;" value="<?php echo $managementplan[5]; ?>" name="managementplan[]">
            </td>
        </tr>
        <tr>
            <td colspan='2'>
                <a href="nuclear_medicineassessmentformprint.php?Payment_Item_Cache_List_ID=<?=$Payment_Item_Cache_List_ID?>&Registration_ID=<?=$Registration_ID?>" class="art-button-green pull-right" style="align:right; width: 15%;" target="blank">PRINT</a>
                
            </td>
        </tr>
    </table>
    <?php
            }
        } else{      ?>
    
    <table class="table">
        <caption> <h4>RADIO-ACTIVE IODINE (I-131) THERAPY</h4></caption>
        <tr>
            <td>
                <label for="">Pregnancy test</label>
                <input type="text"  style="display:inline; width: 80%;" value="" name="managementplan[]">

            </td>
            <td>
                <label for="">DATE:</label>
                <input type="date"  class="form-control" style="display:inline; width: 60%;" value="" name="managementplan[]">
            </td>
        </tr>
        <tr>
            <td>
                <label for="">Carbimazole:</label>
                <input type="text"  style="display:inline; width: 80%;" value="" name="managementplan[]">

            </td>
            <td>
                <label for="">I-131 DOSE GIVEN:</label>
                <input type="text"  style="display:inline; width: 70%;" value="" name="managementplan[]">
            </td>
        </tr>
        <tr>
            <td>
                <label for="">FOLLOW UP DATE</label>
                <input type="date"  style="display:inline; width: 80%;" value="" name="managementplan[]">
            </td>
            <td>
                <label for="">Doctor:</label>
                <input type="text"  style="display:inline; width: 80%;" value="<?php echo $_SESSION['userinfo']['Employee_Name']; ?>" name="managementplan[]">
            </td>
        </tr>
        <tr>
            <td colspan='2'>
                <input type="button" value="SAVE" class="art-button-green pull-right" style="align:right; width: 15%;" onclick="save_assessmentform_plan()">
            </td>
        </tr>
    </table>
<?php
            }
    
    ?>
</fieldset>
<div id="checklistform"></div>
<?php
    include("../includes/footer.php");
?>
<script>
    function save_assessmentform(){
        var therapychecklist=[];
        $("#therapychecklist:checked").each(function() {
            therapychecklist.push($(this).val());
        });
        
        var clinicalhistory=[];
        var size = document.getElementsByName('clinicalhistory[]');
        for (var i = 0; i <size.length; i++) {
            var inp=size[i];
            clinicalhistory.push(inp.value);
        }
        var clinicalhistory = clinicalhistory.toString()
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        $.ajax({
            type:"POST",
            url:'Nm/item_report_setup.php',
            data:{clinicalhistory:clinicalhistory,therapychecklist:therapychecklist,Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID, Registration_ID:Registration_ID, assmentnsave:''},
            success:function(responce){
                alert(responce)
                location.reload();
            }
        })
    }
</script>

<script>
    function save_checklist_data(){
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        
        var Therapy=[];
        var size = document.getElementsByName('Therapy[]');
        for (var i = 0; i <size.length; i++) {
            var inp=size[i];
            Therapy.push(inp.value);
        }
        var therapychecklist=[];
        $("#therapychecklist:checked").each(function() {
            therapychecklist.push($(this).val());
        });

        var Therapy = Therapy.toString()
        var therapychecklist = therapychecklist.toString();
        // alert(therapychecklist); exit();
        $.ajax({
            type:"POST",
            url:'Nm/item_report_setup.php',
            data:{Therapy:Therapy,therapychecklist:therapychecklist, Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID, Registration_ID:Registration_ID, therapychecklistsave:''},
            success:function(responce){
                alert(responce);
                location.reload();
            }
        })
    }
    function  open_riotfollowupform(){
        var Assessment_ID = '<?php echo $Assessment_ID; ?>';
        if(Assessment_ID ==''){
            alert("Fill Assessment form first");
            exit()
            $("#maincomplain").css("border", "solid 2px red");
        }
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Name = '<?php echo $Patient_Name; ?>';
        var Id = '<?php echo $Registration_ID; ?>';
        var Age = '<?php echo $Age; ?>';
        $.ajax({
            type:'POST',
            url:'Nm/item_report_setup.php',
            data:{Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,Id:Id, followupform:''},
            success:function(data){
                $("#checklistform").dialog({
                    title: " NAME: "+Name+" | FILE NO: "+Id+" | AGE: "+Age,
                    width: '80%',
                    height: 650,
                    modal: true,
                })
                $("#checklistform").html(data);
                display_riotfollowup();
            }
        })
    }
    function display_riotfollowup(){
        var Assessment_ID = '<?php echo $Assessment_ID; ?>';
        if(Assessment_ID ==''){
            alert("Fill Assessment form first");
            exit()
        }
        $.ajax({
            type:"POST",
            url:'Nm/item_report_setup.php',
            data:{Assessment_ID:Assessment_ID, display_riotfollowup:''},
            success:function(responce){
               $("#followuptreatment").html(responce);
            }
        })
    }

    function savefollowuptreatment(){
       
        var Assessment_ID = '<?php echo $Assessment_ID; ?>';
       
        var followuptreatment=[];
        var size = document.getElementsByName('followuptreatment[]');
        for (var i = 0; i <size.length; i++) {
            var inp=size[i];
            followuptreatment.push(inp.value);
        }
        // stuff= ["uyuuyu", "76gyuhj***", "uiyghj", "56tyg", "juijjujh***"];
        // for(var i = 0; i < stuff.length; i++)
        // {
        //     stuff[i] = stuff[i].replace('***', '0');
        // }
        // console.log(stuff);
        // alert(sizeof(followuptreatment));exit();

        var followuptreatment = followuptreatment.toString()
        $.ajax({
            type:"POST",
            url:'Nm/item_report_setup.php',
            data:{followuptreatment:followuptreatment,Assessment_ID:Assessment_ID,  followupttreatmentsave:''},
            success:function(responce){
              //  alert(responce);
                display_riotfollowup();
            }
        })
    }
    function open_therapychecklist(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Name = '<?php echo $Patient_Name; ?>';
        var Id = '<?php echo $Registration_ID; ?>';
        var Age = '<?php echo $Age; ?>';
        $.ajax({
            type:'POST',
            url:'Nm/item_report_setup.php',
            data:{Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,Id:Id, therapychecklist:''},
            success:function(data){
                $("#checklistform").dialog({
                    title: "RADIO - ACTIVE IODINE THERAPY FORM HYPERTHYROIDISM | NAME: "+Name+" | FILE NO: "+Id+" | AGE: "+Age,
                    width: '80%',
                    height: 650,
                    modal: true,
                })
                $("#checklistform").html(data);
                
            }
        })
    }

    function open_assessmentform(){
        var Name = '<?php echo $Patient_Name; ?>';
        var Id = '<?php echo $Registration_ID; ?>';
        var Age = '<?php echo $Age; ?>';
        $.ajax({
            type:'POST',
            url:'Nm/item_report_setup.php',
            data:{assessmentform:''},
            success:function(data){
                $("#checklistform").dialog({
                    title: "DAY OF THERAPY CHECKLIST | NAME: "+Name+" | FILE NO: "+Id+" | AGE: "+Age,
                    width: '80%',
                    height: 650,
                    modal: true,
                })
                $("#checklistform").html(data);
                
            }
        })
    }
    function save_assessmentform_plan(){
        var Assessment_ID = '<?php echo $Assessment_ID; ?>';
        var managementplan=[];
        var size = document.getElementsByName('managementplan[]');
        for (var i = 0; i <size.length; i++) {
            var inp=size[i];
            managementplan.push(inp.value);
        }

        var managementplan = managementplan.toString()
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        // alert(managementplan); exit();
        $.ajax({
            type:"POST",
            url:'Nm/item_report_setup.php',
            data:{managementplan:managementplan,Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID, Registration_ID:Registration_ID,Assessment_ID:Assessment_ID, savemanagementplan:''},
            success:function(responce){
                alert(responce)
                location.reload()
            }
        })
    }

    $(document).ready(function () {
        
        $('#Date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#dateconsent').datetimepicker({value: '', step: 01});
        $('#dateconsent').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#dateconsent').datetimepicker({value: '', step: 01});
    });
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
