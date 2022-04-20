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
<a href="surgeryrecords.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&SurgeryRecords=SurgeryRecordsThisPage" class="art-button-green">SURGERY RECORDS</a>
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
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<br/><br/>
<fieldset>
    <legend align="right"><b>PROCEDURE RECORDS</b></legend>
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
    <table width="100%">
        <tr>
            <td width="5%"><b>SN</b></td>
            <td><b>PROCEDURE NAME</b></td>
            <td width="12%"><b>PROCEDURE DATE</b></td>
            <td width="12%"><b>DOCTOR ORDERED</b></td>
            <td width="10%"><b>STATUS</b></td>
            <td width="12%"><b>PERFORMED BY</b></td>
            <td width="20%" colspan="3" style="text-align: center;"><b>REPORTS</b></td>
        </tr>
        <tr><td colspan="10"><hr></td></tr>
    <?php
        $temp = 0;
        $select = mysqli_query($conn,"select ilc.Service_Date_And_Time, ilc.ServedBy, ilc.Payment_Item_Cache_List_ID, i.Product_Name, ilc.Status, ilc.Doctor_Comment, ilc.remarks, emp.Employee_Name, ilc.ServedDateTime, ilc.ServedBy, ilc.Transaction_Date_And_Time 
                                from tbl_item_list_cache ilc, tbl_employee emp, tbl_items i, tbl_payment_cache pc where 
                                i.Item_ID = ilc.Item_ID and
                                pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                emp.Employee_ID = ilc.Consultant_ID and
                                ilc.Check_In_Type = 'Procedure' and
                                pc.Registration_ID = '$Registration_ID' and
                                ilc.Status <> 'notserved'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0){
            while($data = mysqli_fetch_array($select)){
                $Payment_Item_Cache_List_ID = $data['Payment_Item_Cache_List_ID'];
                //get performer
                $E_ID = $data['ServedBy'];
                $slct = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$E_ID'") or die(mysqli_error($conn));
                $nm = mysqli_num_rows($slct);
                if($nm > 0){
                    while($rw = mysqli_fetch_array($slct)){
                        $Performer = $rw['Employee_Name'];
                    }
                }else{
                    $Performer = '';
                }

                //get status
                if(strtolower($data['Status']) == 'active'){
                    $Status = 'Not Processed';
                }else if(strtolower($data['Status']) == 'served'){
                    $Status = 'Done';
                }else{
                    $Status = 'Not Processed';
                }
    ?>
                <tr>
                    <td><?php echo ++$temp; ?><b>.</b></td>
                    <td><?php echo $data['Product_Name']; ?></td>
                    <td><?php echo $data['Service_Date_And_Time']; ?></td>
                    <td><?php echo ucwords(strtolower($data['Employee_Name'])); ?></td>
                    <td><?php echo $Status; ?></td>
                    <td><?php echo ucwords(strtolower($Performer)); ?></td>
                    
    <?php
        $select_pro1 = mysqli_query($conn,"select Registration_ID from tbl_ogd_post_operative_notes where Status <> 'pending' and Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
        $no_pro1 = mysqli_num_rows($select_pro1);
        if($no_pro1 > 0){
    ?>
            <td width='7%'><a href="previewcolonoscopyreport.php?Registration_ID=<?php echo $Registration_ID; ?>&Payment_Item_Cache_List_ID=<?php echo $Payment_Item_Cache_List_ID; ?>&PreviewColonoscopyReport=PreviewColonoscopyReportThisPage" class="art-button-green" target="_blank">COLONOSCOPY</a></td>
    <?php
        }else{
            echo "<td width='7%' style='text-align: center;'></td>";
        }
    ?>
    <?php
        $select_pro2 = mysqli_query($conn,"select Registration_ID from tbl_git_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
        $no_pro2 = mysqli_num_rows($select_pro2);
        if($no_pro2 > 0){
    ?>
            <td width='7%'><a href="previewuppergitscopereport.php?Registration_ID=<?php echo $Registration_ID; ?>&Payment_Item_Cache_List_ID=<?php echo $Payment_Item_Cache_List_ID; ?>&PreviewUpperGitScopeReport=PreviewUpperGitScopeReportThisPage" class="art-button-green" target="_blank">UPPER GIT</a></td>
    <?php
        }else{
            echo "<td width='7%' style='text-align: center;'></td>";
        }
    ?>
    <?php
        $select_pro3 = mysqli_query($conn,"select Registration_ID from tbl_Bronchoscopy_notes where status = 'Saved' and Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
        $no_pro3 = mysqli_num_rows($select_pro3);
        if($no_pro3 > 0){
    ?>
            <td width='7%'><a href="previewBronchoscopyreport.php?Registration_ID=<?php echo $Registration_ID; ?>&Payment_Item_Cache_List_ID=<?php echo $Payment_Item_Cache_List_ID; ?>&PreviewColonoscopyReport=PreviewColonoscopyReportThisPage" class="art-button-green" target="_blank">BRONCHOSCOPY</a></td>
    <?php
        }else{
            echo "<td width='7%' style='text-align: center;'></td>";
        }
    ?>

    
    <?php
        $select_pro5 = mysqli_query($conn,"SELECT patient_id from tbl_paediatric_information where status = 'saved' and patient_item_cache_list_id = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
        $select_pro4 = mysqli_query($conn,"SELECT patient_id from tbl_clinical_information where status = 'saved' and Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
        $no_pro4 = mysqli_num_rows($select_pro4);
        $no_pro5 = mysqli_num_rows($select_pro5);
        if($no_pro5 > 0){
    ?>
            <td width='7%'><a href="echocardiogram_Paediatric_file.php?patient_id=<?php echo $Registration_ID; ?>&Payment_Item_Cache_List_ID=<?php echo $Payment_Item_Cache_List_ID; ?>&PreviewEchoCardiographyReport=PreviewEchoCardiographyReportThisPage" class="art-button-green" target="_blank">ECHOCARDIOGRAPHY</a></td>
    <?php
        }elseif($no_pro4 > 0){
            ?>
                    <td width='7%'><a href="echocardiogram_file.php?patient_id=<?php echo $Registration_ID; ?>&Payment_Item_Cache_List_ID=<?php echo $Payment_Item_Cache_List_ID; ?>&PreviewEchocardiographyReport=PreviewEchocardiographyReportThisPage" class="art-button-green" target="_blank">ECHOCARDIOGRAPHY</a></td>
            <?php
                }else{
            echo "<td width='7%' style='text-align: center;'></td>";
        }
    ?>

                    
                </tr>
    <?php
            }
        }
    ?>
    </table>
</fieldset>

<?php
    include("./includes/footer.php");
?>
