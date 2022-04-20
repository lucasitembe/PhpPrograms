<?php
    include("./includes/header.php");
    include("./includes/connection.php");

    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Patient_Record_Works'])) {
            if ($_SESSION['userinfo']['Patient_Record_Works'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        } else {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if (isset($_GET['Registration_ID'])) {
        $Registration_ID = $_GET['Registration_ID'];
    } else {
        $Registration_ID = 0;
    }
?>
<a href="patientfile_scroll.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>" class="art-button-green">PATIENT FILE SCROLL VIEW</a>
<a href="procedurerecords.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&ProcedureRecords=ProcedureRecordsThisPage" class="art-button-green">PROCEDURE RECORDS</a>
<a href="Patientfile_Record_Detail.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&PatientFile=PatientFileThisForm" class="art-button-green">BACK</a>

<?php
//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
//    select patient information
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID
                                      from tbl_patient_registration pr, tbl_sponsor sp
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = $row['Patient_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Claim_Number_Status = $row['Claim_Number_Status'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            // echo $Ward."  ".$District."  ".$Ward; exit;
        }

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days";
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Guarantor_Name = '';
        $Claim_Number_Status = '';
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
        $Email_Address = '';
        $Occupation = '';
        $Employee_Vote_Number = '';
        $Emergence_Contact_Name = '';
        $Emergence_Contact_Number = '';
        $Company = '';
        $Employee_ID = '';
        $Registration_Date_And_Time = '';
        $age = 0;
    }
} else {
    $Registration_ID = '';
    $Old_Registration_Number = '';
    $Title = '';
    $Sponsor_ID = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Region = '';
    $District = '';
    $Ward = '';
    $Guarantor_Name = '';
    $Claim_Number_Status = '';
    $Member_Number = '';
    $Member_Card_Expire_Date = '';
    $Phone_Number = '';
    $Email_Address = '';
    $Occupation = '';
    $Employee_Vote_Number = '';
    $Emergence_Contact_Name = '';
    $Emergence_Contact_Number = '';
    $Company = '';
    $Employee_ID = '';
    $Registration_Date_And_Time = '';
    $age = 0;
}
?>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        /*background-color:#eeeeee;
            cursor:pointer;*/
    }
</style>
<br/><br/>
<fieldset>
    <legend align="right"><b>SURGERY RECORDS</b></legend>
    <table width="100%">
        <tr>
            <td width="12%" style="text-align: right;">Patient Name</td>
            <td width="17%"><input type="text" readonly="readonly" value="<?php echo ucwords(strtolower($Patient_Name)); ?>"></td>
            <td width="7%" style="text-align: right;">Age</td>
            <td width="17%"><input type="text" readonly="readonly" value="<?php echo $age; ?>"></td>
            <td width="8%" style="text-align: right;">Gender</td>
            <td><input type="text" readonly="readonly" value="<?php echo $Gender; ?>"></td>
            <td width="12%" style="text-align: right;">Sponsor Name</td>
            <td width="17%"><input type="text" readonly="readonly" value="<?php echo strtoupper($Guarantor_Name); ?>"></td>
        </tr>
    </table>
</fieldset>
<fieldset style='overflow-y: scroll; height: 370px; background-color: white;'>
<?php
    $select_surgery = mysqli_query($conn,"select pon.*, i.Product_Name, emp.Employee_Name 
                                    from tbl_post_operative_notes pon, tbl_items i, tbl_item_list_cache ilc, tbl_employee emp where
                                    pon.Registration_ID = '$Registration_ID' and emp.Employee_ID = pon.Employee_ID and
                                    ilc.Payment_Item_Cache_List_ID = pon.Payment_Item_Cache_List_ID and ilc.Status !='notsaved' AND
                                    i.Item_ID = ilc.Item_ID") or die(mysqli_error($conn));
        $num_surgery = mysqli_num_rows($select_surgery);
        if($num_surgery > 0){
            $temp = 0;
            while($dt = mysqli_fetch_array($select_surgery)){
                $Post_operative_ID = $dt['Post_operative_ID'];
?>

                <table width="100%">
                    <tr>
                        <td colspan="3" style="text-align: left; background-color: #eeeebb;"><b><?php echo ++$temp; ?>. SURGERY NAME&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<?php echo strtoupper($dt['Product_Name']); ?></b></td>
                        <td colspan="2" style="background-color: #eeeebb;"><b>SURGERY DATE : <?php echo @date("d F Y", strtotime($dt['Surgery_Date'])); ?></b></td>
                        <td style="text-align: left; background-color: #eeeebb;" colspan="2"><b>CONSULTANT&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<?php echo strtoupper($dt['Employee_Name']); ?></b></td>
                        <td style="background-color: #eeeebb;"><a href="previewpostoperativereport.php?Registration_ID=<?php echo $dt['Registration_ID']; ?>&Payment_Item_Cache_List_ID=<?php echo $dt['Payment_Item_Cache_List_ID']; ?>&PreviewPostOperativeReport=PreviewPostOperativeReportThisPage" style="text-decoration: none;" target="_blank"><button>Preview Report</button></a></td>
                    </tr>
                    <tr><td colspan="8" style="text-align: left;"><hr></td></tr>
                    <tr>
                        <!-- <td style="text-align: right;" width="9%">Surgery Date</td><td width="20%"><input type="text" value="<?php echo $dt['Surgery_Date']; ?>" readonly="readonly"></td> -->
                        <td width="10%" style="text-align: right;">Type Of Anesthetic</td><td width="10%"><input type="text" value="<?php echo $dt['Type_Of_Anesthetic']; ?>" readonly="readonly"></td>
                        <td style="text-align: right;">Incision</td><td width="22%"><input type="text" value="<?php echo $dt['Incision']; ?>" readonly="readonly"></td>
                        <td style="text-align: right;">Position</td><td><input type="text" value="<?php echo $dt['Position']; ?>" readonly="readonly"></td>
                    </tr>
                </table>
                <table width="100%">
                    <tr><td colspan="2" style="text-align: left; background-color: #eeeebb;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;COMMENTS</b></td></tr>
                    <tr><td colspan="2" style="text-align: left;"><hr></td></tr>
                <?php if($dt['Procedure_Description'] != null && $dt['Procedure_Description'] != ''){ ?>
                    <tr>
                        <td width="17%" style="text-align: right;">Procedure Description And Closure</td>
                        <td><textarea style="width: 100%; height: 30px;" readonly="readonly"><?php echo $dt['Procedure_Description']; ?></textarea></td>
                    </tr>
                <?php } ?>

                <?php if($dt['Identification_Of_Prosthesis'] != null && $dt['Identification_Of_Prosthesis'] != ''){ ?>
                    <tr>
                        <td width="17%" style="text-align: right;">Identification Of Prosthesis</td>
                        <td><textarea style="width: 100%; height: 30px;" readonly="readonly"><?php echo $dt['Identification_Of_Prosthesis']; ?></textarea></td>
                    </tr>
                <?php } ?>


                <?php if($dt['Estimated_Blood_loss'] != null && $dt['Estimated_Blood_loss'] != ''){ ?>
                    <tr>
                        <td width="17%" style="text-align: right;">Estimated Blood Loss</td>
                        <td><textarea style="width: 100%; height: 30px;" readonly="readonly"><?php echo $dt['Estimated_Blood_loss']; ?></textarea></td>
                    </tr>
                <?php } ?>


                <?php if($dt['Complications'] != null && $dt['Complications'] != ''){ ?>
                    <tr>
                        <td width="17%" style="text-align: right;">Problems / Complications</td>
                        <td><textarea style="width: 100%; height: 30px;" readonly="readonly"><?php echo $dt['Complications']; ?></textarea></td>
                    </tr>
                <?php } ?>


                <?php if($dt['Drains'] != null && $dt['Drains'] != ''){ ?>
                    <tr>
                        <td width="17%" style="text-align: right;">Drains</td>
                        <td><textarea style="width: 100%; height: 30px;" readonly="readonly"><?php echo $dt['Drains']; ?></textarea></td>
                    </tr>
                <?php } ?>


                <?php if($dt['Specimen_sent'] != null && $dt['Specimen_sent'] != ''){ ?>
                    <tr>
                        <td width="17%" style="text-align: right;">Specimen Sent</td>
                        <td><textarea style="width: 100%; height: 30px;" readonly="readonly"><?php echo $dt['Specimen_sent']; ?></textarea></td>
                    </tr>
                <?php } ?>


                <?php if($dt['Postoperative_Orders'] != null && $dt['Postoperative_Orders'] != ''){ ?>
                    <!-- <tr>
                        <td width="17%" style="text-align: right;">Postoperative Orders</td>
                        <td><textarea style="width: 100%; height: 30px;" readonly="readonly"><?php echo $dt['Postoperative_Orders']; ?></textarea></td>
                    </tr> -->
                <?php } ?>
                </table>
                
                <?php
                    $Number = 0;
                    $select_post = mysqli_query($conn,"select  d.disease_code, d.disease_name
                                            from tbl_post_operative_diagnosis pod, tbl_disease d where
                                            d.disease_ID = pod.disease_ID and
                                            pod.Post_operative_ID = '$Post_operative_ID' and
                                            pod.Diagnosis_Type = 'Preoperative Diagnosis'") or die(mysqli_error($conn));
                    $no = mysqli_num_rows($select_post);
                    if($no > 0){
                ?>
                    <table width="100%">
                        <tr><td colspan="3" style="text-align: left; background-color: #eeeebb;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PREOPERATIVE DIAGNOSIS (INDICATION)</b></td></tr>
                        <tr><td colspan="3" style="text-align: left;"><hr></td></tr>
                        <tr>
                            <td width='17%' style='text-align: right;'><b>DISEASE CODE</b></td><td width="2%"></td>
                            <td style="text-align: left;"><b>DISEASE NAME</b></td>
                        </tr>
                <?php
                        while ($data = mysqli_fetch_array($select_post)) {
                            echo "<tr>
                                        <td style='text-align: right;'>".strtoupper($data['disease_code'])."</td><td></td>
                                        <td style='text-align: left;'>".ucwords(strtolower($data['disease_name']))."</td>
                                    </tr>";
                        }
                        echo "</table>";
                    }
                ?>

                <?php
                    $Number = 0;
                    $select_post = mysqli_query($conn,"select  d.disease_code, d.disease_name
                                            from tbl_post_operative_diagnosis pod, tbl_disease d where
                                            d.disease_ID = pod.disease_ID and
                                            pod.Post_operative_ID = '$Post_operative_ID' and
                                            pod.Diagnosis_Type = 'Postoperative Diagnosis'") or die(mysqli_error($conn));
                    $no = mysqli_num_rows($select_post);
                    if($no > 0){
                ?>
                    <table width="100%">
                        <tr><td colspan="3" style="text-align: left; background-color: #eeeebb;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;POSTOPERATIVE DIAGNOSIS (FINDINGS)</b></td></tr>
                        <tr><td colspan="3" style="text-align: left;"><hr></td></tr>
                        <tr>
                            <td width='17%' style='text-align: right;'><b>DISEASE CODE</b></td><td width="2%"></td>
                            <td style="text-align: left;"><b>DISEASE NAME</b></td>
                        </tr>
                <?php
                        while ($data = mysqli_fetch_array($select_post)) {
                            echo "<tr>
                                        <td style='text-align: right;'>".strtoupper($data['disease_code'])."</td><td></td>
                                        <td style='text-align: left;'>".ucwords(strtolower($data['disease_name']))."</td>
                                    </tr>";
                        }
                        echo "</table>";
                    }
                ?>

                <?php
                    //get participants
                    $Surgeons = '';
                    $Assisitant_Surgeons = '';
                    $Nurses = '';
                    $Anaesthetics = '';

                    $select = mysqli_query($conn,"select pop.Employee_Type, emp.Employee_Name from 
                                            tbl_post_operative_participant pop, tbl_employee emp where
                                            emp.Employee_ID = pop.Employee_ID and
                                            pop.Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($select);
                    if($num > 0){

                        while ($data = mysqli_fetch_array($select)) {
                            if($data['Employee_Type'] == 'Surgeon'){
                                $Surgeons .= ucwords(strtolower($data['Employee_Name'])).'<br/>';
                            }else if($data['Employee_Type'] == 'Assistant Surgeon'){
                                $Assisitant_Surgeons .= ucwords(strtolower($data['Employee_Name'])).'<br/>';
                            }else if($data['Employee_Type'] == 'Nurse'){
                                $Nurses .= ucwords(strtolower($data['Employee_Name'])).'<br/>';
                            }else if($data['Employee_Type'] == 'Anaesthetics'){
                                $Anaesthetics .= ucwords(strtolower($data['Employee_Name'])).'<br/>';
                            }
                        }
                ?>
                        <table width="100%">
                            <tr><td colspan="3" style="text-align: left; background-color: #eeeebb;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PARTICIPANTS</b></td></tr>
                            <tr><td colspan="3" style="text-align: left;"><hr></td></tr>
                            <tr>
                                <td width='17%' style='text-align: right;'><b>TITLE NAME</b></td><td width="2%"></td>
                                <td style="text-align: left;"><b>PARTICIPANT NAME</b></td>
                            </tr>
                <?php
                        if($Surgeons != ''){
                            echo "<tr><td colspan='3' style='text-align: left;'><hr></td></tr>
                                    <tr>
                                        <td style='text-align: right;'><b>Surgeons</b></td><td></td>
                                        <td style='text-align: left;'>".$Surgeons."</td>
                                    </tr>";
                        }
                        if($Assisitant_Surgeons != ''){
                            echo "<tr><td colspan='3' style='text-align: left;'><hr></td></tr>
                                    <tr>
                                        <td style='text-align: right;'><b>Assistant Surgeons</b></td><td></td>
                                        <td style='text-align: left;'>".$Assisitant_Surgeons."</td>
                                    </tr>";
                        }
                        if($Nurses != ''){
                            echo "<tr><td colspan='3' style='text-align: left;'><hr></td></tr>
                                    <tr>
                                        <td style='text-align: right;'><b>Nurses</b></td><td></td>
                                        <td style='text-align: left;'>".$Nurses."</td>
                                    </tr>";
                        }
                        if($Anaesthetics != ''){
                            echo "<tr><td colspan='3' style='text-align: left;'><hr></td></tr>
                                    <tr>
                                        <td style='text-align: right;'><b>Anaesthetists</b></td><td></td>
                                        <td style='text-align: left;'>".$Anaesthetics."</td>
                                    </tr>";
                        }
                        echo '</table>';
                    }
                ?>

                <?php
                    //external participants
                    $select = mysqli_query($conn,"select * from tbl_post_operative_external_participant where Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($select);
                    if($num > 0){
                        while ($data = mysqli_fetch_array($select)) {
                            $External_Surgeons  = $data['External_Surgeons'];
                            $External_Assistant_Surgeon = $data['External_Assistant_Surgeon'];
                            $External_Scrub_Nurse = $data['External_Scrub_Nurse'];
                            $External_Anaesthetic = $data['External_Anaesthetic'];
                        }
                        if(($External_Surgeons != null && $External_Surgeons != '') || ($External_Assistant_Surgeon != null && $External_Assistant_Surgeon != '') || ($External_Scrub_Nurse != null && $External_Scrub_Nurse != '') || ($External_Anaesthetic != null && $External_Anaesthetic != '')){
                ?>

                        <table width="100%">
                            <tr><td colspan="3" style="text-align: left; background-color: #eeeebb;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EXTERNAL PARTICIPANTS</b></td></tr>
                            <tr><td colspan="3" style="text-align: left;"><hr></td></tr>
                            <tr>
                                <td width='17%' style='text-align: right;'><b>TITLE NAME</b></td><td width="2%"></td>
                                <td style="text-align: left;"><b>PARTICIPANTS</b></td>
                            </tr>
                <?php
                            if($External_Surgeons != '' && $External_Surgeons != null){
                                echo "<tr>
                                            <td style='text-align: right;'><b>External Surgeons</b></td><td></td>
                                            <td style='text-align: left;'>".$External_Surgeons."</td>
                                        </tr>";
                            }
                            if($External_Assistant_Surgeon != '' && $External_Assistant_Surgeon != null){
                                echo "<tr>
                                            <td style='text-align: right;'><b>External Assistant Surgeons</b></td><td></td>
                                            <td style='text-align: left;'>".$External_Assistant_Surgeon."</td>
                                        </tr>";
                            }
                            if($External_Scrub_Nurse != '' && $External_Scrub_Nurse != null){
                                echo "<tr>
                                            <td style='text-align: right;'><b>External Nurses</b></td><td></td>
                                            <td style='text-align: left;'>".$External_Scrub_Nurse."</td>
                                        </tr>";
                            }
                            if($External_Anaesthetic != '' && $External_Anaesthetic != null){
                                echo "<tr>
                                            <td style='text-align: right;'><b>External Anaesthetists</b></td><td></td>
                                            <td style='text-align: left;'>".$External_Anaesthetic."</td>
                                        </tr>";
                            }
                            echo '</table>';
                        }
                    }
                    echo "<center>--------xxxx--------&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--------xxxx--------&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--------xxxx--------&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--------xxxx--------&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--------xxxx--------&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--------xxxx--------&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--------xxxx--------<br/><br/><br/><br/></center>";
                }
            }
    ?>
</fieldset>
<?php
    include("./includes/footer.php");
?>