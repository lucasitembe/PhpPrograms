<?php

session_start();
include("./includes/connection.php");

if (isset($_SESSION['userinfo']['Quality_Assurance'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] != 'yes') {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

//get start date, end date & sponsor id

if (isset($_GET['Start_Date'])) {
    $Start_Date = $_GET['Start_Date'];
} else {
    $Start_Date = '';
}

if (isset($_GET['End_Date'])) {
    $End_Date = $_GET['End_Date'];
} else {
    $End_Date = '';
}

if (isset($_GET['attendence_month'])) {
    $attendence_month = $_GET['attendence_month'];
} else {
    $attendence_month = '';
}
if(!empty($attendence_month)){
    $attendence_month = $_GET['attendence_month'];
}else{
    $attendence_month='';
}
if (isset($_GET['Bill_Description'])) {
    $Bill_Description = $_GET['Bill_Description'];
} else {
    $Bill_Description = '';
}

if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = $_GET['Sponsor_ID'];
} else {
    $Sponsor_ID = 0;
}
if (isset($_GET['fail_n_sent_bill'])) {
    $fail_n_sent_bill= $_GET['fail_n_sent_bill'];
} else {
    $fail_n_sent_bill = '';
}
if (isset($_GET['call_status'])) {
    $call_status= $_GET['call_status'];
} else {
    $call_status = '';
}
if (isset($_GET['patient_status'])) {
    $patient_status= $_GET['patient_status'];
} else {
    $patient_status = '';
}
if (isset($_GET['search_value'])) {
    $search_value= $_GET['search_value'];
} else {
    $search_value = '';
}
if (isset($_GET['patient_details'])) {
    $patient_details= $_GET['patient_details'];
} else {
    $patient_details = '';
}
$can_edit_claim_bill = $_SESSION['userinfo']['can_edit_claim_bill'];

$filter_single_patient  = " ";
$search_for_number  = " ";
if (isset($_GET['patient_details'])) {
    if($_GET['search_value'] =="patient_name"){
      //$filter_single_patient = " AND pr.Patient_Name LIKE '%$patient_details%'";
		 $filter_single_patient = " AND pp.Registration_ID IN(SELECT pr.Registration_ID FROM tbl_patient_registration  pr WHERE pr.Patient_Name LIKE '%$patient_details%') ";
		
    }else if($_GET['search_value'] =="patient_number"){
      $filter_single_patient = " AND pp.Registration_ID = '$patient_details'";
     // $search_for_number = " AND pp.Registration_ID = '$patient_details'";
    }
}

//get employee ID
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}
$filter_fail_n_sent_bill = ' ';
if(isset($fail_n_sent_bill) && $fail_n_sent_bill != 'all'){
  $filter_fail_n_sent_bill = " and ".(($fail_n_sent_bill == 1)? ' e_bill_delivery_status = 1 ':'  e_bill_delivery_status = 0 ');
  //$filter_fail_n_sent_bill .=" and ".$filter_fail_n_sent_bill;
  //$filter_fail_n_sent_bill = " e_bill_delivery_status = 1 ";
  //$filter_fail_n_sent_bill = " e_bill_delivery_status = 0 ";
}
//generate bill
if ($Start_Date != '' && $Start_Date != null && $End_Date != '' && $End_Date != null && $Sponsor_ID != '' && $Sponsor_ID != null && $Employee_ID != 0 && $Employee_ID != null) {
    echo "<fieldset style='overflow-y: scroll; height: 320px;'>";
    echo '<center><table width = 100% border=0>';
    echo '<tr><td width=3%><b>SN</b></td><td width=5% style="text-align: left;"><b>Bill No</b></td>
    <td width=4%><b>Folio No.</b></td>
    <td width=5%><b>Sponsor</b></td>
    <td width=8% style="text-align: left;"><b>Patient Number </b></td>
    <td width=15% style="text-align: left;"><b>Patient Name </b></td>
    <td width=7% style="text-align: left;"><b>Phone Number </b></td>
    <td width=7% style="text-align: left;">Authorization No</td>
    <td width=6% style="text-align: left;"><b>Patient Type </b></td>
    <td width=7% style="text-align: left;"><b>Attendence Date</b></td>
    <td width=7% style="text-align: left;"><b>Created Date</b></td>
    <td width=7% style="text-align: right;"><b>Total Amount</b></td>
    
    <td width=9% style="text-align: center;"><b>Bill Status</b></td>
    <td width=9% style="text-align: center;"><b>Form 2A&B</b></td>
    <td width=6% style="text-align: center;"><b>Case Notes</b></td>
</tr>';

    //select all previous bills
    $sql_select = mysqli_query($conn,"SELECT bl.e_bill_delivery_status,bl.Bill_ID,bl.invoice_created, bl.Start_Date, bl.End_Date,bl.Sponsor_ID, sp.Guarantor_Name, bl.Bill_Date_And_Time from    tbl_bills bl, tbl_employee emp, tbl_sponsor sp, tbl_patient_payments pp where   emp.Employee_ID = bl.Employee_ID and     bl.Sponsor_ID = sp.Sponsor_ID and    DATE(bl.Bill_Date_And_Time) between '$Start_Date' and '$End_Date' $filter_fail_n_sent_bill and     bl.Sponsor_ID = '$Sponsor_ID' AND pp.Bill_ID = bl.Bill_ID 	  $filter_single_patient  	GROUP BY bl.Bill_ID     order by bl.Bill_ID asc") or die(mysqli_error($conn));
    $total_amount=0;
    $total_display=0;
    $count = 1;
    $sequence = true;
    $num = mysqli_num_rows($sql_select);
    if ($num > 0) {
        while ($row = mysqli_fetch_array($sql_select)) {
          $sequence = true;
            //get bill id to calculate grand total
            $Bill_ID = $row['Bill_ID'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $invoice_created = $row['invoice_created'];

            //find the attendence date 

            $patient_visit_date = mysqli_fetch_assoc(mysqli_query($conn,"SELECT visit_date FROM tbl_check_in WHERE Check_In_ID = (SELECT DISTINCT Check_In_ID FROM tbl_patient_payments WHERE Bill_ID = '$Bill_ID' GROUP BY Bill_ID)"))['visit_date'];
            $visitMonthName = mysqli_fetch_assoc(mysqli_query($conn,"SELECT MONTHNAME(visit_date) visitMonthName FROM tbl_check_in WHERE Check_In_ID = (SELECT DISTINCT Check_In_ID FROM tbl_patient_payments WHERE Bill_ID = '$Bill_ID' GROUP BY Bill_ID)"))['visitMonthName'];
            $AuthorizationNo = mysqli_fetch_assoc(mysqli_query($conn,"SELECT AuthorizationNo FROM tbl_check_in WHERE Check_In_ID = (SELECT DISTINCT Check_In_ID FROM tbl_patient_payments WHERE Bill_ID = '$Bill_ID')"))['AuthorizationNo'];
            // Hii tunaicomment kwa muda kutest new function
            // if((date('F', strtotime($patient_visit_date)) != $attendence_month) && !empty($attendence_month)){continue;}
            // End

            if(($visitMonthName != $attendence_month) && !empty($attendence_month)){continue;}

            //calculate bill grand total
            $get_Total = mysqli_query($conn,"SELECT sum((price - discount)*quantity) as Bill_Amount,pp.Folio_Number,pp.Patient_Bill_ID,pp.Registration_ID,pp.Billing_Type,pp.Check_In_ID,pr.Patient_Name,pr.Phone_Number from    tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_patient_registration pr where    pp.Patient_Payment_ID = ppl.Patient_Payment_ID and     pp.Registration_ID=pr.Registration_ID and ppl.Status<>'removed' and    pp.Bill_ID = '$Bill_ID'  ") or die(mysqli_error($conn));
			
			$FolioNo = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Folio_No FROM tbl_claim_folio WHERE Bill_ID = $Bill_ID"))['Folio_No'];

            $num_total = mysqli_num_rows($get_Total);
            if ($num_total > 0) {
                while ($data = mysqli_fetch_array($get_Total)) {
                    $Bill_Amount = $data['Bill_Amount'];
                    $Folio_Number = $data['Folio_Number'];
                    $Patient_Bill_ID = $data['Patient_Bill_ID'];
                    $Registration_ID = $data['Registration_ID'];
                    $Registration_ID= $data['Registration_ID'];
                    $Patient_Name= $data['Patient_Name'];
                    $Phone_Number= $data['Phone_Number'];
                    //$Billing_Type= $data['Billing_Type'];
                    $Check_In_ID= $data['Check_In_ID'];
					
                    $typecode = "";
					$Billing_Type = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Billing_Type FROM tbl_patient_payments WHERE Bill_ID = '$Bill_ID' AND Billing_Type ='Inpatient Credit' ORDER BY patient_payment_id DESC LIMIT 1"))['Billing_Type'];
                    $select111 = mysqli_query($conn,"SELECT cd.Admission_ID FROM `tbl_admission` ad, tbl_check_in_details cd WHERE cd.Admission_ID = ad.Admision_ID AND cd.Registration_ID = $Registration_ID AND cd.Check_In_ID = '$Check_In_ID'");
                    if(mysqli_num_rows($select111) > 0){
                        $Billing_Type ='Inpatient Credit';
                        $typecode = "IN";
                    }else{
                        $Billing_Type ='Outpatient Credit';
                        $typecode = "OUT";

                    }
					
                    if(($patient_status =='Outpatient' && $Billing_Type == 'Inpatient Credit') || ($patient_status =='Inpatient' && $Billing_Type == 'Outpatient Credit')){
                      $sequence = false;
                    }
                }
            } else {
                $Bill_Amount = 0;
                $Patient_Bill_ID = 0;
                $Folio_Number = 0;
                $Registration_ID = 0;
            }

            if($sequence == false){
              continue;
            }
            if ($row['e_bill_delivery_status'] == "1") {
                echo "<tr>";
                echo "<td>" . ($count++) . "</td>";
                echo "<td>" . $Bill_ID . "</td>";
                echo "<td>" . $FolioNo . "</td>";
                echo "<td>" . $row['Guarantor_Name'] . "</td>";
                echo "<td>" . $Registration_ID. "</td>";
                echo "<td>" . $Patient_Name . "</td>";
                echo "<td>" . $Phone_Number . "</td><td>$AuthorizationNo</td>";
                echo "<td>" . explode(' ',$Billing_Type)[0] . "</td>";
                echo "<td>" . $patient_visit_date . "</td>";
                echo "<td>" . $row['Bill_Date_And_Time'] . "</td>";
                echo "<td style='text-align: right;'>" . number_format($Bill_Amount) . "</td>";
                //echo "<td style='text-align: center;'><a href='#' class='art-button-green'>Preview Bill</a></td>";
                echo "<td style='text-align: center;background-color:white;color:green'><b>Sent</b></td>";
                echo "<td style='text-align: center;'><input type='button' class='art-button-green' style='text-align: center;' onclick='Preview_Details(".$Folio_Number.",".$Sponsor_ID.",".$Registration_ID.",".$Patient_Bill_ID .",".$Check_In_ID.",".$Bill_ID.")' value='PREVIEW'></td>";
                echo "<td style='text-align: center;'><input type='button' class='art-button-green' style='text-align: center;' onclick='Preview_Case_Notes(".$Registration_ID.",".$Check_In_ID.",".$Bill_ID.",\"".$typecode."\")' value='PREVIEW'></td>";
                echo "</tr>";

                if($invoice_created == 'no'){
                    $total_amount +=$Bill_Amount;
                }
                $total_display +=$Bill_Amount;
            } else {
                echo "<tr>";
                echo "<td>" . ($count++) . "</td>";
                if($can_edit_claim_bill=='yes'){
                echo "<td><a target='_blank' href='Edit_Failed_Approved_BIll.php?Bill_ID=".$Bill_ID."&Folio_Number=".$Folio_Number."&Sponsor_ID=".$Sponsor_ID."&Patient_Bill_ID=".$Patient_Bill_ID."&Check_In_ID=".$Check_In_ID."&Registration_ID=".$Registration_ID."'>" . $Bill_ID . "</a></td>";
                }else{
                    echo "<td>$Bill_ID</td>";
                }
                echo "<td>" . $FolioNo . "</td>";
                echo "<td>" . $row['Guarantor_Name'] . "</td>";
                echo "<td>" . $Registration_ID. "</td>";
                echo "<td>" . $Patient_Name . "</td>";
                echo "<td>" . $Phone_Number . "</td><td>$AuthorizationNo</td>";
                echo "<td>" . explode(' ',$Billing_Type)[0] . "</td>";
                echo "<td>" . $patient_visit_date . "</td>";
                echo "<td>" . $row['Bill_Date_And_Time'] . "</td>";
                echo "<td style='text-align: right;'>" . number_format($Bill_Amount) . "</td>";
                //echo "<td style='text-align: center;'><a href='#' class='art-button-green'>Preview Bill</a></td>";
                echo "<td id='td_" . $Bill_ID . "'  style='text-align: center;'><button onclick='resendBill(" . $Bill_ID . "," . $Sponsor_ID . "," . $Folio_Number . "," . $Patient_Bill_ID . "," . $Registration_ID . ",this);' class='art-button-green'>Send claim</button></td>";
                echo "<td style='text-align: center;'><input type='button' class='art-button-green' style='text-align: center;' onclick='Preview_Details(".$Folio_Number.",".$Sponsor_ID.",".$Registration_ID.",".$Patient_Bill_ID .",".$Check_In_ID.",".$Bill_ID.")' value='PREVIEW'></td>";
                echo "<td style='text-align: center;'><input type='button' class='art-button-green' style='text-align: center;' onclick='Preview_Case_Notes(".$Registration_ID.",".$Check_In_ID.",".$Bill_ID.",\"".$typecode."\")' value='PREVIEW'></td>";
                echo "</tr>";
                if($invoice_created == 'no'){
                    $total_amount +=$Bill_Amount;
                }
                $total_display +=$Bill_Amount;
            }
            if($invoice_created == 'no'){
                echo "<input type='hidden' name='bill_id' value='".$Bill_ID."'>";
            }
        }
    }
    echo '</table>';
}
?>
</fieldset>
<br>
<center><label>Total Amount:<b id="display_amount"> <?=number_format($total_display)?></b></label></center>
<input type='hidden' value='<?=$total_amount?>' id='total_amount'>

<center>
  <input type="button" name="excel_peport" value="Excel Report" class="art-button-green" onclick="Download_Excel_Report();">
  <input type="button" name="preview_bill" value="Preview Bill" class="art-button-green" onclick="Preview_Bills_Report();">
</center>
<!--input type='submit' name='btn_create_invoice' id='create_invoice' onclick='Create_Invoice();' class='art-button-green' value='Create Invoice' style='float:right;margin-top:10px;border:2px solid green;'-->

<?php
  if($call_status =='reload'){?>
<!--input type='submit' name='btn_print_invoice' class='print_invoice' onclick='Print_Invoice();' class='art-button-green' value='Print Invoice' style='float:right;margin-top:10px;border:2px solid green;display:none;'-->
<?php } ?>
