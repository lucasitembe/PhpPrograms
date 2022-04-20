<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_GET['Section']) && $_GET['Section'] == 'Doctor') {
        
    } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorLab') {
        
    } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorRad') {
        
    } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorsPerformancePateintSummary') {
        
    } else {
        if (isset($_SESSION['userinfo']['Patient_Record_Works'])) {
            if ($_SESSION['userinfo']['Patient_Record_Works'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        } else {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

//get section for back buttons
if (isset($_GET['section'])) {
    $section = $_GET['section'];
} else {
    $section = '';
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}
if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
} else {
    $Patient_Payment_ID = 0;
}
if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
} else {
    $Patient_Payment_Item_List_ID = 0;
}
if(isset($_GET['this_page_from'])){
   $this_page_from=$_GET['this_page_from'];
}else{
   $this_page_from=""; 
}
?>
<a href="patientfile_scroll.php?Registration_ID=<?php echo $_GET['Registration_ID'] ?>" class="art-button-green">PATIENT FILE SCROLL VIEW</a>
<input type="button" class="art-button-green" value="PREVIOUS PATIENT FILE" onclick="Preview_Previous_Patient_File(<?php echo $Registration_ID; ?>)">
<a href="surgeryrecords.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&SurgeryRecords=SurgeryRecordsThisPage" class="art-button-green">SURGERY RECORDS</a>
<a href="procedurerecords.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&ProcedureRecords=ProcedureRecordsThisPage" class="art-button-green">PROCEDURE RECORDS</a>

<a href="Cancer_patient_record.php?Registration_ID=<?php echo $Registration_ID; ?>&section=Patient&PatientFile=PatientFileThisForm&fromPatientFile=true&this_page_from=patient_record" class="art-button-green">BACK</a>

<!--<a href="all_patient_file_link_station.php?Registration_ID=<?=$Registration_ID ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&this_page_from=<?= $this_page_from ?>" class="art-button-green">BACK</a>-->

<!--
        /*if(isset($_GET['fromPatientFile'])){
            if($_GET['fromPatientFile']=='true'){
              echo "<a href='Patientfile_Record.php?section=Patient&PatientFileRecordThisPage=ThisPage' class='art-button-green'>BACK</a>";
  
            }
            
        }elseif (isset ($_GET['Patient_Payment_ID']) && ($_GET['Patient_Payment_Item_List_ID'])) {
            if($_GET['position']=='out'){
             echo "<a href='doctorspageoutpatientwork.php?Registration_ID=".$_GET['Registration_ID']."&Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage&position=out' class='art-button-green'>BACK</a>";
       
            }elseif ($_GET['position']=='in') {
              echo "<a href='clinicalnotes.php?Registration_ID=".$_GET['Registration_ID']."&Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage&position=in' class='art-button-green'>BACK</a>";
         
            }
            
        }elseif(isset ($_GET['Registration_ID']) && ($_GET['consultation_ID'])){
            if($_GET['position']=='out'){
                 echo "<a href='doctorspageinpatientwork.php?Registration_ID=".$_GET['Registration_ID']."&consultation_ID=".$_GET['consultation_ID']."&position=out' class='art-button-green'>BACK</a>";
            }else if($_GET['position']=='in'){
                  echo "<a href='inpatientclinicalnotes.php?Registration_ID=".$_GET['Registration_ID']."&consultation_ID=".$_GET['consultation_ID']."&item_ID=".$GET['item_ID']."&position=in' class='art-button-green'>BACK</a>";
  
            }
        
        }*/-->
<!--    }
}-->
<?php

if(isset($_GET['Registration_ID'])){
   $Registration_ID=$_GET['Registration_ID'];
}else{
   $Registration_ID=""; 
}
?>

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
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days, " . $diff->h . " Hours";
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
        border:1px solid #ccc;	
        border-collapse:collapse !important;
    }
    .button_pro{
        height:27px !important;
        color:white !important;
    }
</style>

<br/><br/>
<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
?>
<!-- get id, date, Billing Type,Folio number and type of chech in -->
<?php
if (isset($_GET['Registration_ID']) && isset($_GET['Patient_Payment_ID'])) {
    //select the current Patient_Payment_ID to use as a foreign key

    $qr = "select * from tbl_patient_payments pp
					    where pp.Patient_Payment_ID = " . $_GET['Patient_Payment_ID'] . "
					    and pp.registration_id = '$Registration_ID'";
    $sql_Select_Current_Patient = mysqli_query($conn,$qr);
    $row = @mysqli_fetch_array($sql_Select_Current_Patient);
    $Patient_Payment_ID = $row['Patient_Payment_ID'];
    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
    //$Check_In_Type = $row['Check_In_Type'];
    $Folio_Number = $row['Folio_Number'];
    $Claim_Form_Number = $row['Claim_Form_Number'];
    $Billing_Type = $row['Billing_Type'];
    //$Patient_Direction = $row['Patient_Direction'];
    //$Consultant = $row['Consultant'];
} else {
    $Patient_Payment_ID = '';
    $Payment_Date_And_Time = '';
    //$Check_In_Type = $row['Check_In_Type'];
    $Folio_Number = '';
    $Claim_Form_Number = '';
    $Billing_Type = '';
    //$Patient_Direction = '';
    //$Consultant ='';
}
?>
<!--Getting employee name -->
<?php
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Employee';
}
?>

<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<br>
    <fieldset style='background: #006400 !important;color: white'>
        
            <legend align="center" style="background-color:yellow;color:black;padding:5px;"> PHYSIOTHERAPY  PATIENT RECORDS <br>
            <?php echo ucwords(strtolower($Patient_Name)) . ", " . $Gender . ", " . $Guarantor_Name . ", (" . $age . ")"; ?></legend>
        
    
    </fieldset>
    <fieldset style='background: white; color:black'>
        <div id="radPatTest" style="width:100%;height:400px;oveflow-y:scroll;overflow-x:hidden">
            <center>
                <table width = '100%'>
                    <tr id='thead'>
                        <td style="width:5%;"><center><b>SN</b></center></td>
                        <td><center><b>DATE</b></center></td>
                        <td><center><b>DOCTORS REVIEW</b></center></td>
                    </tr>
                        <?php
                            $resultPat = mysqli_query($conn,"SELECT * FROM tbl_mobility_assessment_save WHERE Registration_ID = '$Registration_ID' GROUP BY date_time_transaction ORDER BY date_time_transaction DESC LIMIT 1") or die(mysqli_error($conn));

                            $i = 1;
                            while ($row = mysqli_fetch_array($resultPat)) {                                
                                $date_time_transaction=$row['date_time_transaction'];
                                $Registration_ID=$row['Registration_ID'];
                        ?>
        
                <tr>
                <td id="thead"><center></center></td>
                <td><center><?php echo $date_time_transaction; ?></center></td>
                <td>
                <center>
                <a href='physiotherapy_patient_review.php?Registration_ID=<?php echo $Registration_ID; ?>&date_and_time=<?php echo $date_and_time; ?>&section=Patient&PatientFile=PatientFileThisForm&fromPatientFile=true&this_page_from=patient_record'><button class='art-button button_pro'>Review</button></a> 
                </a>
                </center>
                </td>
                </tr>
                 <?php
            $i++;
        }
        ?>
    </table>
</center>
        </div>
    </fieldset>
<?php
include("./includes/footer.php");
?>