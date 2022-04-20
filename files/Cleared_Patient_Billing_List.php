<?php
session_start();
include("./includes/connection.php");
echo "<link rel='stylesheet' href='fixHeader.css'>";

if (isset($_GET['Patient_Name'])) {
    $Patient_Name = mysqli_real_escape_string($conn,$_GET['Patient_Name']);
} else {
    $Patient_Name = '';
}

if (isset($_GET['Patient_Number'])) {
    $Patient_Number = mysqli_real_escape_string($conn,$_GET['Patient_Number']);
} else {
    $Patient_Number = '';
}

if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = mysqli_real_escape_string($conn,$_GET['Sponsor_ID']);
} else {
    $Sponsor_ID = 0;
}

if (isset($_GET['Hospital_Ward_ID'])) {
    $Hospital_Ward_ID = mysqli_real_escape_string($conn,$_GET['Hospital_Ward_ID']);
} else {
    $Hospital_Ward_ID = 0;
}

if (isset($_GET['Start_Date'])) {
    $Start_Date = mysqli_real_escape_string($conn,$_GET['Start_Date']);
} else {
    $Start_Date = '';
}

if (isset($_GET['End_Date'])) {
    $End_Date = mysqli_real_escape_string($conn,$_GET['End_Date']);
} else {
    $End_Date = '';
}


$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}

$can_revoke = $_SESSION['userinfo']['can_revk_bill_status'];

$Title = '<thead>
            <tr style="background-color: #ccc;">
                <td width="2%"><b>SN</b></td>
                <td><b>PATIENT NAME</b></td>
                <td width="7%"><b>PATIENT #</b></td>
                <td width="11%"><b>SPONSOR NAME</b></td>
                <td width="11%"><b>PATIENT AGE</b></td>
                <td width="7%"><b>GENDER</b></td>
                <td width="8%"><b>MEMBER NUMBER</b></td>
                <td width="10%"><b>CASH CLEARED BY</b></td>
                <td width="10%"><b>CREDIT CLEARED BY</b></td>
                <td><b>WARD</b></td>
                <td><b>DATE CLEARED</b></td>';

    if ($can_revoke == 'yes') {
        $Title .= '<td width="">&nbsp;</td>';
    }

$Title .= '     </tr>
            </thead>';
?>
<legend style="background-color:#006400;color:white" align="right"><b>CLEARED BILLS ~ INPATIENTS LIST</b></legend>
<center><table width =100% border=0 class="fixTableHead">
        <?php
        $temp = 0;
        echo $Title;
        if ($Hospital_Ward_ID == 0) {
            if ($Sponsor_ID == 0) {
                if (isset($_GET['Patient_Name'])) {
                    $select = mysqli_query($conn,"SELECT ad.Cash_Clearer_ID,ad.Credit_Clearer_ID,ad.Admision_ID,pr.Patient_Name,Clearance_Date_Time, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name
		                                from tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw where
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
		                                pr.Patient_Name like '%$Patient_Name%' and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
		                                (ad.Admission_Status = 'Discharged' or ad.Discharge_Clearance_Status = 'cleared') and
                                		ad.Clearance_Date_Time between '$Start_Date' and '$End_Date' GROUP BY ad.Admision_ID ORDER BY ad.Admision_ID ASC LIMIT 50") or die(mysqli_error($conn));

                } else if (isset($_GET['Patient_Number']) && $Patient_Number != null && $Patient_Number != '') {
                    $select = mysqli_query($conn,"SELECT ad.Cash_Clearer_ID,ad.Credit_Clearer_ID,ad.Admision_ID,pr.Patient_Name, Clearance_Date_Time, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name
		                                from tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw where
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
		                                pr.Registration_ID = '$Patient_Number' and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
		                                (ad.Admission_Status = 'Discharged' or ad.Discharge_Clearance_Status = 'cleared') and
                                		ad.Clearance_Date_Time between '$Start_Date' and '$End_Date' GROUP BY ad.Admision_ID ORDER BY ad.Admision_ID ASC LIMIT 50") or die(mysqli_error($conn));
                                    
                } else {
                    $select = mysqli_query($conn,"SELECT ad.Cash_Clearer_ID,ad.Credit_Clearer_ID,ad.Admision_ID,pr.Patient_Name, Clearance_Date_Time, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name
		                                from tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw where
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
		                                (ad.Admission_Status = 'Discharged' or ad.Discharge_Clearance_Status = 'cleared') and
                                		ad.Clearance_Date_Time between '$Start_Date' and '$End_Date' GROUP BY ad.Admision_ID ORDER BY ad.Admision_ID ASC LIMIT 50") or die(mysqli_error($conn));
                }
            } else {
                if (isset($_GET['Patient_Name'])) {
                    $select = mysqli_query($conn,"SELECT ad.Cash_Clearer_ID,ad.Credit_Clearer_ID,ad.Admision_ID,pr.Patient_Name, Clearance_Date_Time, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name
		                                from tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw where
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
		                                pr.Sponsor_ID = '$Sponsor_ID' and
		                                pr.Patient_Name like '%$Patient_Name%' and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
		                                (ad.Admission_Status = 'Discharged' or ad.Discharge_Clearance_Status = 'cleared') and
                                		ad.Clearance_Date_Time between '$Start_Date' and '$End_Date' GROUP BY ad.Admision_ID ORDER BY ad.Admision_ID ASC LIMIT 50") or die(mysqli_error($conn));
                } else if (isset($_GET['Patient_Number']) && $Patient_Number != null && $Patient_Number != '') {
                    $select = mysqli_query($conn,"SELECT ad.Cash_Clearer_ID,ad.Credit_Clearer_ID,ad.Admision_ID,pr.Patient_Name, Clearance_Date_Time, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name
		                                from tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw where
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
		                                pr.Sponsor_ID = '$Sponsor_ID' and
		                                pr.Registration_ID = '$Patient_Number' and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
		                                (ad.Admission_Status = 'Discharged' or ad.Discharge_Clearance_Status = 'cleared') and
                                		ad.Clearance_Date_Time between '$Start_Date' and '$End_Date' GROUP BY ad.Admision_ID ORDER BY ad.Admision_ID ASC LIMIT 50") or die(mysqli_error($conn));
                } else {
                    $select = mysqli_query($conn,"SELECT ad.Cash_Clearer_ID,ad.Credit_Clearer_ID,ad.Admision_ID,pr.Patient_Name, Clearance_Date_Time, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name
		                                from tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw where
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
		                                pr.Sponsor_ID = '$Sponsor_ID' and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
		                                (ad.Admission_Status = 'Discharged' or ad.Discharge_Clearance_Status = 'cleared') and
                                		ad.Clearance_Date_Time between '$Start_Date' and '$End_Date' GROUP BY ad.Admision_ID ORDER BY ad.Admision_ID ASC LIMIT 50") or die(mysqli_error($conn));
                }
            }
        } else {
            if ($Sponsor_ID == 0) {
                if (isset($_GET['Patient_Name'])) {
                    $select = mysqli_query($conn,"SELECT ad.Cash_Clearer_ID,ad.Credit_Clearer_ID,ad.Admision_ID,pr.Patient_Name, Clearance_Date_Time, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name
		                                from tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw where
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
		                                pr.Patient_Name like '%$Patient_Name%' and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
                                		hw.Hospital_Ward_ID = '$Hospital_Ward_ID' and
		                                (ad.Admission_Status = 'Discharged' or ad.Discharge_Clearance_Status = 'cleared') and
                                		ad.Clearance_Date_Time between '$Start_Date' and '$End_Date' GROUP BY ad.Admision_ID ORDER BY ad.Admision_ID ASC LIMIT 50") or die(mysqli_error($conn));
                } else if (isset($_GET['Patient_Number']) && $Patient_Number != null && $Patient_Number != '') {
                    $select = mysqli_query($conn,"SELECT ad.Cash_Clearer_ID,ad.Credit_Clearer_ID,ad.Admision_ID,pr.Patient_Name, Clearance_Date_Time, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name
		                                from tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw where
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
		                                pr.Registration_ID = '$Patient_Number' and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
                                		hw.Hospital_Ward_ID = '$Hospital_Ward_ID' and
		                                (ad.Admission_Status = 'Discharged' or ad.Discharge_Clearance_Status = 'cleared') and
                                		ad.Clearance_Date_Time between '$Start_Date' and '$End_Date' GROUP BY ad.Admision_ID ORDER BY ad.Admision_ID ASC LIMIT 50") or die(mysqli_error($conn));
                } else {
                    $select = mysqli_query($conn,"SELECT ad.Cash_Clearer_ID,ad.Credit_Clearer_ID,ad.Admision_ID,pr.Patient_Name, Clearance_Date_Time, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name
		                                from tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw where
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
                                		hw.Hospital_Ward_ID = '$Hospital_Ward_ID' and
		                                (ad.Admission_Status = 'Discharged' or ad.Discharge_Clearance_Status = 'cleared') and
                                		ad.Clearance_Date_Time between '$Start_Date' and '$End_Date' GROUP BY ad.Admision_ID ORDER BY ad.Admision_ID ASC LIMIT 50") or die(mysqli_error($conn));
                }
            } else {
                if (isset($_GET['Patient_Name'])) {
                    $select = mysqli_query($conn,"SELECT ad.Cash_Clearer_ID,ad.Credit_Clearer_ID,ad.Admision_ID,pr.Patient_Name, Clearance_Date_Time, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name
		                                from tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw where
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
		                                pr.Sponsor_ID = '$Sponsor_ID' and
		                                pr.Patient_Name like '%$Patient_Name%' and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
                                		hw.Hospital_Ward_ID = '$Hospital_Ward_ID' and
		                                (ad.Admission_Status = 'Discharged' or ad.Discharge_Clearance_Status = 'cleared') and
                                		ad.Clearance_Date_Time between '$Start_Date' and '$End_Date' GROUP BY ad.Admision_ID ORDER BY ad.Admision_ID ASC LIMIT 50") or die(mysqli_error($conn));
                } else if (isset($_GET['Patient_Number']) && $Patient_Number != null && $Patient_Number != '') {
                    $select = mysqli_query($conn,"SELECT ad.Cash_Clearer_ID,ad.Credit_Clearer_ID,ad.Admision_ID,pr.Patient_Name, Clearance_Date_Time, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name
		                                from tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw where
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
		                                pr.Sponsor_ID = '$Sponsor_ID' and
		                                pr.Registration_ID = '$Patient_Number' and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
                                		hw.Hospital_Ward_ID = '$Hospital_Ward_ID' and
		                                (ad.Admission_Status = 'Discharged' or ad.Discharge_Clearance_Status = 'cleared') and
                                		ad.Clearance_Date_Time between '$Start_Date' and '$End_Date' GROUP BY ad.Admision_ID ORDER BY ad.Admision_ID ASC LIMIT 50") or die(mysqli_error($conn));
                } else {
                    $select = mysqli_query($conn,"SELECT ad.Cash_Clearer_ID,ad.Credit_Clearer_ID,ad.Admision_ID,pr.Patient_Name, Clearance_Date_Time, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name
		                                from tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw where
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
		                                pr.Sponsor_ID = '$Sponsor_ID' and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
                                		hw.Hospital_Ward_ID = '$Hospital_Ward_ID' and
		                                (ad.Admission_Status = 'Discharged' or ad.Discharge_Clearance_Status = 'cleared') and
                                		ad.Clearance_Date_Time between '$Start_Date' and '$End_Date' GROUP BY ad.Admision_ID ORDER BY ad.Admision_ID ASC LIMIT 50") or die(mysqli_error($conn));
                }
            }
        }

        $num = mysqli_num_rows($select);
        if ($num > 0) {
            while ($row = mysqli_fetch_array($select)) {
                 $Cash_Clearer_ID=$row['Cash_Clearer_ID'];
                    $Credit_Clearer_ID=$row['Credit_Clearer_ID'];
                    $Clearance_Date_Time=$row['Clearance_Date_Time'];
                    $sql_select_who_clear_bill_result=mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Credit_Clearer_ID'") or die(mysqli_error($conn));
                    if(mysqli_num_rows($sql_select_who_clear_bill_result)>0){
                        $clear_by_row=mysqli_fetch_assoc($sql_select_who_clear_bill_result);
                        $credit_cleared_by=$clear_by_row['Employee_Name'];
                    }else{
                        $credit_cleared_by="";
                    }
                    $sql_select_who_clear_bill_result2=mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Cash_Clearer_ID'") or die(mysqli_error($conn));
                    if(mysqli_num_rows($sql_select_who_clear_bill_result2)>0){
                        $clear_by_row2=mysqli_fetch_assoc($sql_select_who_clear_bill_result2);
                        $cash_cleared_by=$clear_by_row2['Employee_Name'];
                    }else{
                        $cash_cleared_by="";
                    }
                //calculate age
                $date1 = new DateTime($Today);
                $date2 = new DateTime($row['Date_Of_Birth']);
                $diff = $date1->diff($date2);
                $age = $diff->y . " Years, ";
                $age .= $diff->m . " Months, ";
                $age .= $diff->d . " Days";
                $check_in_id = $row['Check_In_ID'];
                $Registration_ID =$row['Registration_ID'];
                $delvrystatus = mysqli_fetch_assoc(mysqli_query($conn,"SELECT bl.e_bill_delivery_status FROM tbl_bills bl JOIN tbl_patient_payments pp ON bl.Bill_ID=pp.Bill_ID WHERE pp.Check_In_ID='$check_in_id' LIMIT 1"))['e_bill_delivery_status'];
                ?>
                <tr id="thead"><td style="width:5%;"><?php echo ++$temp; ?><b>.</b></td>
                    <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID =<?php echo $Registration_ID?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo ucwords(strtolower($row['Patient_Name'])); ?></a></td>
                    <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID =<?php echo $Registration_ID?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $row['Registration_ID']; ?></a></td>
                    <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID =<?php echo $Registration_ID?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $row['Guarantor_Name']; ?></a></td>
                    <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID =<?php echo $Registration_ID?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $age; ?></a></td>
                    <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID =<?php echo $Registration_ID?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $row['Gender']; ?></a></td>
                    <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID =<?php echo $Registration_ID?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $row['Member_Number']; ?></a></td>
                    <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID =<?php echo $Registration_ID?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $cash_cleared_by ?></a></td>
                    <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID =<?php echo $Registration_ID?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $credit_cleared_by?></a></td>
                    <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID =<?php echo $Registration_ID?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $row['Hospital_Ward_Name']; ?></a></td>
                    <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID =<?php echo $Registration_ID?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $Clearance_Date_Time; ?></a></td>
                   
                    <?php
                    if ($can_revoke == 'yes') {
                        if ($delvrystatus == "1") {
                            ?>
                            <td></td>
                        <?php } else {
                            ?>
                            <td><button  type="button" class="art-button-green" onclick="unclearbill('<?php echo $row['Admision_ID']; ?>')">UN CLEAR BILL</button></td>   
                            <?php
                        }
                    }
                    ?>
                </tr>
                <?php
                if (($temp % 31) == 0) {
                    // echo $Title;
                }
            }
        }
        echo "</table>";
        ?>