<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
$this_page_from=$_GET['this_page_from'];
    if($this_page_from== 'patient_record'){

    }else{
        if (isset($_SESSION['userinfo'])) {
            if (isset($_SESSION['userinfo']['Dialysis_Works'])) {
                if ($_SESSION['userinfo']['Dialysis_Works'] != 'yes') {
                    header("Location: ./index.php?InvalidPrivilege=yes");
                } else {
                    @session_start();
                    if (!isset($_SESSION['Dialysis_Supervisor'])) {
                        header("Location: ./deptsupervisorauthentication.php?SessionCategory=Dialysis&InvalidSupervisorAuthentication=yes");
                    }
                }
            } else {
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        } else {
            @session_destroy();
            header("Location: ../index.php?InvalidPrivilege=yes");
        }
    }


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
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days.";
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
<?php
////get cache details
//if (isset($_GET['Payment_Cache_ID'])) {
//    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
//    $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
//
//    $select_receipt_details = "SELECT * FROM  tbl_item_list_cache ic,tbl_consultation c,tbl_payment_cache pc,
//				   tbl_patient_payment_item_list ppl,tbl_patient_payments pp
//				   WHERE pc.Payment_Cache_ID = $Payment_Cache_ID
//				   AND pc.Payment_Cache_ID = ic.Payment_Cache_ID
//				   AND c.consultation_ID = pc.consultation_ID
//				   AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID
//				   AND ppl.Patient_Payment_Item_List_ID = c.Patient_Payment_Item_List_ID
//				   AND 	ic.Check_In_Type='Procedure' LIMIT 1";
//    $receipt_result = mysqli_query($conn,$select_receipt_details);
//    while ($receipt_row = mysqli_fetch_assoc($receipt_result)) {
//        $Consultant = $receipt_row['Consultant'];
//        $Patient_Direction = $receipt_row['Patient_Direction'];
//        $Transaction_Date_And_Time = substr($receipt_row['Transaction_Date_And_Time'], 0, 10);
//        $Folio_Number = $receipt_row['Folio_Number'];
//        $Sponsor_ID = $receipt_row['Sponsor_ID'];
//        $Sponsor_Name = $receipt_row['Sponsor_Name'];
//        $Billing_Type = 'Outpatient Credit';
//        $branch_id = $_SESSION['userinfo']['Branch_ID'];
//        $Patient_Payment_ID = $receipt_row['Patient_Payment_ID'];
//        $Claim_Form_Number = $receipt_row['Claim_Form_Number'];
//    }
//}
?>
<?php
//on form submit add bill information to payment table for credit patient

?>
<?php
//select payment details
if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
        $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    } else {
        $select_payment_id = "(SELECT * FROM tbl_patient_payment_item_list
					WHERE Patient_Payment_ID=$Patient_Payment_ID)";
        $payment_results = mysqli_query($conn,$select_payment_id);
        $Patient_Payment_Item_List_ID = mysqli_fetch_assoc($payment_results)['Patient_Payment_Item_List_ID'];
    }

    $select_receipt_details = "SELECT * FROM tbl_patient_payment_item_list
				   WHERE Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'
				   ";
    $receipt_result = mysqli_query($conn,$select_receipt_details);
    while ($receipt_row = mysqli_fetch_assoc($receipt_result)) {
        $Consultant = $receipt_row['Consultant'];
        $Patient_Direction = $receipt_row['Patient_Direction'];
        $Transaction_Date_And_Time = substr($receipt_row['Transaction_Date_And_Time'], 0, 10);
    }
}

if (isset($_GET['consultation_id'])) {
    $consultation_id = $_GET['consultation_id'];
}
?>
<script type="text/javascript">
//    function gotolink() {
//        var patientlist = document.getElementById('patientlist').value;
//        if (patientlist == 'Outpatient cash') {
//            document.location = "./dialysiscashpatientlist.php";
//        } else if (patientlist == 'Outpatient credit') {
//            document.location = "dialysiscreditpatientlist.php?DialysisCreditPatientlist=DialysisCreditPatientlistThispage";
//        } else if (patientlist == 'Inpatient cash') {
//            document.location = "#";
//        } else if (patientlist == 'Inpatient credit') {
//            document.location = "#";
//        } else if (patientlist == 'Patient from outside') {
//            document.location = "#";
//        } else {
//            alert("Choose Type Of Patients To View");
//        }
//    }
</script>
<?php
    $this_page_from=$_GET['this_page_from'];
    if($this_page_from== 'patient_record'){?>

         <a href="all_patient_file_link_station.php?Registration_ID=<?= $Registration_ID ?>&section=Patient&PatientFile=PatientFileThisForm&this_page_from=patient_record" class="art-button-green">Back</a>
    <?php }else{?>
        <a href="dialysispatientList.php" class="art-button-green">BACK</a>
    <?php }
?>




<center>
    <form action="#" method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data"> 
        <fieldset><legend align='right'><b>Dialysis Works</b></legend>
            <table width=100%>
                <tr>
                    <td><b>Patient Name</b></td><td><input type="text" name="" readonly='readonly' value='<?php
                        if (isset($Patient_Name)) {
                            echo $Patient_Name;
                        }
                        ?>' id=""></td>
                    <td><b>Visit Date</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php
                        if (isset($Transaction_Date_And_Time)) {
                            echo $Transaction_Date_And_Time;
                        }
                        ?>'></td>
                </tr>
                <tr>
                    <td><b>Patient Number</b></td><td><input type="text" name="" readonly='readonly' value='<?php
                        if (isset($Registration_ID)) {
                            echo $Registration_ID;
                        }
                        ?>' id="" ></td>
                    <td><b>Gender</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php
                        if (isset($Gender)) {
                            echo $Gender;
                        }
                        ?>' ></td></td>
                </tr>
                <tr>
                    <td><b>Sponsor</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php
                        if (isset($Guarantor_Name)) {
                            echo $Guarantor_Name;
                        }
                        ?>' ></td>
                    <td><b>Age</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php
                        if (isset($age)) {
                            echo $age;
                        }
                        ?>' ></td></td>
                </tr>
                <tr>
                    <td><b>Doctor</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php
                        if (isset($Consultant)) {
                            if ($Patient_Direction == 'Direct To Clinic') {
                                echo $_SESSION['userinfo']['Employee_Name'];
                            } else {
                                echo $Consultant;
                            }
                        }
                        ?>' ></td>
                </tr>
            </table>

    </form>
</fieldset>
<br><br>





<fieldset style="height: 350px;overflow-y: scroll;overflow-x: hidden">
    <?php
    $attend_query=mysqli_query($conn,"SELECT * FROM tbl_dialysis_details WHERE Patient_reg='$Registration_ID' ORDER BY Attendance_Date DESC");
     $num_rows=  mysqli_num_rows($attend_query);
     if($num_rows>0){
         while ($row=  mysqli_fetch_assoc($attend_query)){
            $dialysis_details_ID = $row["dialysis_details_ID"];
            $Payment_Item_Cache_List_ID = $row["Payment_Item_Cache_List_ID"];
            $Patient_Payment_ID = $row["Patient_Payment_ID"];
            $consultation_id = $row["consultation_id"];
            $Attendance_Date_before = $row["Attendance_Date"];
            $Attendance_Date_after =explode(" ",$Attendance_Date_before );
            $Attendance_Date=$Attendance_Date_after[0];
            
            $inn_query=mysqli_query($conn,"SELECT * FROM `tbl_dialysis_doctor_notes` WHERE `dialysis_details_ID`='$dialysis_details_ID' group by dialysis_details_ID");
            if($this_page_from== 'patient_record'){
                if(empty(mysqli_num_rows($inn_query))){
                
                    echo '<center>
                        <div style="margin-top:10px">
                            <a href="dialysisclinicalnotes_patient_file.php?dialysis_details_ID='.$row['dialysis_details_ID'].'&Registration_ID='.$Registration_ID.'&Patient_Payment_ID='.$Patient_Payment_ID.'&consultation_id='.$consultation_id.'&Patient_Payment_Item_List_ID='.$Payment_Item_Cache_List_ID.'&NR=true&PatientBilling=PatientBillingThisForm&Attendance_Date='.$Attendance_Date.'&this_page_from=patient_record&dialysis_details_ID='.$dialysis_details_ID.'" class="art-button-green" >'.$Attendance_Date.'</a>
    
                            <a href="dialysis_general_report.php?dialysis_details_ID='.$row['dialysis_details_ID'].'&Registration_ID='.$Registration_ID.'&Patient_Payment_ID='.$Patient_Payment_ID.'&consultation_id='.$consultation_id.'&Patient_Payment_Item_List_ID='.$Payment_Item_Cache_List_ID.'&NR=true&PatientBilling=PatientBillingThisForm&Attendance_Date='.$Attendance_Date.'&dialysis_details_ID='.$dialysis_details_ID.'" class="art-button-green"  target="_blank">PREVIEW</a>
                        </div>
                    </center>';  
                 }else{
                    echo '<center>
                    <div style="margin-top:10px">
                        <a href="dialysisclinicalnotes_patient_file.php?dialysis_details_ID='.$row['dialysis_details_ID'].'&Registration_ID='.$Registration_ID.'&Patient_Payment_ID='.$Patient_Payment_ID.'&consultation_id='.$consultation_id.'&Patient_Payment_Item_List_ID='.$Payment_Item_Cache_List_ID.'&NR=true&PatientBilling=PatientBillingThisForm&Attendance_Date='.$Attendance_Date.'&this_page_from=dialysisclinicalnotes&dialysis_details_ID='.$dialysis_details_ID.'" class="art-button-green">'.$Attendance_Date.'
                        <a>
                        <a href="dialysis_general_report.php?dialysis_details_ID='.$row['dialysis_details_ID'].'&Registration_ID='.$Registration_ID.'&Patient_Payment_ID='.$Patient_Payment_ID.'&consultation_id='.$consultation_id.'&Patient_Payment_Item_List_ID='.$Payment_Item_Cache_List_ID.'&NR=true&PatientBilling=PatientBillingThisForm&Attendance_Date='.$Attendance_Date.'&dialysis_details_ID='.$dialysis_details_ID.'" class="art-button-green" target="_blank">PREVIEW
                        <a>
                    </div>
                    </center>';  
                 }
            }else{
                if(empty(mysqli_num_rows($inn_query))){
                    
                    echo '<center>
                        <div style="margin-top:10px">
                            <a href="dialysisclinicalnotes.php?dialysis_details_ID='.$row['dialysis_details_ID'].'&Registration_ID='.$Registration_ID.'&Patient_Payment_ID='.$Patient_Payment_ID.'&consultation_id='.$consultation_id.'&Patient_Payment_Item_List_ID='.$Payment_Item_Cache_List_ID.'&NR=true&PatientBilling=PatientBillingThisForm&Attendance_Date='.$Attendance_Date.'&dialysis_details_ID='.$dialysis_details_ID.'" class="art-button-green" >'.$Attendance_Date.'</a>
    
                            <a href="dialysis_general_report.php?dialysis_details_ID='.$row['dialysis_details_ID'].'&Registration_ID='.$Registration_ID.'&Patient_Payment_ID='.$Patient_Payment_ID.'&consultation_id='.$consultation_id.'&Patient_Payment_Item_List_ID='.$Payment_Item_Cache_List_ID.'&NR=true&PatientBilling=PatientBillingThisForm&Attendance_Date='.$Attendance_Date.'&dialysis_details_ID='.$dialysis_details_ID.'" class="art-button-green"  target="_blank">PREVIEW</a>
                        </div>
                    </center>';  
                 }else{
                    echo '<center>
                    <div style="margin-top:10px">
                        <a href="dialysisclinicalnotes.php?dialysis_details_ID='.$row['dialysis_details_ID'].'&Registration_ID='.$Registration_ID.'&Patient_Payment_ID='.$Patient_Payment_ID.'&consultation_id='.$consultation_id.'&Patient_Payment_Item_List_ID='.$Payment_Item_Cache_List_ID.'&NR=true&PatientBilling=PatientBillingThisForm&Attendance_Date='.$Attendance_Date.'&dialysis_details_ID='.$dialysis_details_ID.'" class="art-button-green">'.$Attendance_Date.'
                        <a>
                        <a href="dialysis_general_report.php?dialysis_details_ID='.$row['dialysis_details_ID'].'&Registration_ID='.$Registration_ID.'&Patient_Payment_ID='.$Patient_Payment_ID.'&consultation_id='.$consultation_id.'&Patient_Payment_Item_List_ID='.$Payment_Item_Cache_List_ID.'&NR=true&PatientBilling=PatientBillingThisForm&Attendance_Date='.$Attendance_Date.'&dialysis_details_ID='.$dialysis_details_ID.'" class="art-button-green" target="_blank">PREVIEW
                        <a>
                    </div>
                    </center>';  
                 }
            }
            
             
         }
  
     }  else {
         echo '<center><div style="font-size:15px;font-weight:bold" class="art-button-green">No record found for this patient</div></center>';  
}

//
//    $attend_query=mysqli_query($conn,"SELECT * FROM tbl_dialysis_details WHERE Patient_reg='$Registration_ID' ORDER BY Attendance_Date DESC");
//     $num_rows=  mysqli_num_rows($attend_query);
//     if($num_rows>0){
//         while ($row=  mysqli_fetch_assoc($attend_query)){
//            $dialysis_details_ID = $row["dialysis_details_ID"];
//            $inn_query=mysqli_query($conn,"SELECT * FROM `tbl_dialysis_doctor_notes` WHERE `dialysis_details_ID`='$dialysis_details_ID' group by dialysis_details_ID");
//             if(empty(mysqli_num_rows($inn_query))){
//                echo '<center><div style="margin-top:10px"><a href="dialysisclinicalnotes_Date_Report.php?dialysis_details_ID='.$row['dialysis_details_ID'].'&Registration_ID='.$Registration_ID.'&Patient_Payment_ID='.$Patient_Payment_ID.'&consultation_id='.$consultation_id.'&Patient_Payment_Item_List_ID='.$Payment_Item_Cache_List_ID.'&NR=true&PatientBilling=PatientBillingThisForm" class="art-button-green" style="background: red">'.$Attendance_Date.'</a></div></center>';  
//             }else{
//                echo '<center><div style="margin-top:10px"><a href="dialysisclinicalnotes_Date_Report.php?dialysis_details_ID='.$row['dialysis_details_ID'].'&Registration_ID='.$Registration_ID.'&Patient_Payment_ID='.$Patient_Payment_ID.'&consultation_id='.$consultation_id.'&Patient_Payment_Item_List_ID='.$Payment_Item_Cache_List_ID.'&NR=true&PatientBilling=PatientBillingThisForm" class="art-button-green">'.$Attendance_Date.'</a></div></center>';  
//             }
//             
//         }
//  
//     }  else {
//         echo '<center><div style="font-size:15px;font-weight:bold" class="art-button-green">No record found for this patient</div></center>';  
//}
     
     
    ?>
    
</fieldset>
</center>
<link rel="stylesheet" href="css/normalize.css">
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="css/jquery.steps.css">
<script src="css/lib/modernizr-2.6.2.min.js"></script>
<script src="css/lib/jquery-1.9.1.min.js"></script>
<script src="css/lib/jquery.cookie-1.3.1.js"></script>
<script src="css/build/jquery.steps.js"></script>
<script src="css/jquery.form.js"></script>



<?php
include("./includes/footer.php");
?>

