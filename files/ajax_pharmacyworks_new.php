<?php
include './includes/connection.php';

if (isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['Registration_ID']) && isset($_POST['Patient_Name'])) {
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];
  $Registration_ID = $_POST['Registration_ID'];
  $Patient_Name = $_POST['Patient_Name'];
  $Sub_Department_Name = $_POST['Sub_Department_Name'];

  $filter = "AND pc.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date'";
  if (!empty($Patient_Name)) {
    $filter .= "AND Patient_Name LIKE '%$Patient_Name%'";
  }
  if (!empty($Registration_ID)) {
    $filter .= "AND  pr.Registration_ID ='$Registration_ID'";
  }

  $pharmacy_sub_department_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Sub_Department_ID FROM tbl_sub_department WHERE Sub_Department_Name = '$Sub_Department_Name' LIMIT 1"))['Sub_Department_ID'];

  $sql_select_list_of_patient_sent_to_cashier_result = mysqli_query($conn, "SELECT pr.Registration_ID,pr.Patient_Name,pr.Member_Number,Billing_Type,pr.Date_Of_Birth,pr.Gender,pc.Payment_Date_And_Time,pc.Payment_Cache_ID,pc.Sponsor_ID FROM tbl_patient_registration pr,tbl_payment_cache pc WHERE pr.Registration_ID=pc.Registration_ID $filter  ORDER BY pc.Payment_Cache_ID DESC  limit 20") or die(mysqli_error($conn));

  if (mysqli_num_rows($sql_select_list_of_patient_sent_to_cashier_result) > 0) {
    $count_sn = 1;
    while ($patient_list_rows = mysqli_fetch_assoc($sql_select_list_of_patient_sent_to_cashier_result)) {
      $Registration_ID = $patient_list_rows['Registration_ID'];
      $Patient_Name = $patient_list_rows['Patient_Name'];
      $Date_Of_Birth = $patient_list_rows['Date_Of_Birth'];
      $Gender = $patient_list_rows['Gender'];
      $Member_Number = $patient_list_rows['Member_Number'];
      $Billing_Type = $patient_list_rows['Billing_Type'];
      $Sponsor_ID = $patient_list_rows['Sponsor_ID'];
      $Payment_Date_And_Time = $patient_list_rows['Payment_Date_And_Time'];
      $Payment_Cache_ID = $patient_list_rows['Payment_Cache_ID'];

      $change_color_style = "";

      $sql_select_card_and_mobile_payment_status_result = mysqli_query($conn, "SELECT transaction_status FROM tbl_card_and_mobile_payment_transaction WHERE Payment_Cache_ID='$Payment_Cache_ID'") or die(mysqli_error($conn));
      if (mysqli_num_rows($sql_select_card_and_mobile_payment_status_result) > 0) {
        $transaction_status = mysqli_fetch_assoc($sql_select_card_and_mobile_payment_status_result)['transaction_status'];
        if ($transaction_status == "pending") {
          $change_color_style = "style='color:#FFFFFF;background:green;font-weight:bold'";
        }
      }

      //filter only patient with active or approved item
      $sql_select_active_or_approved_item_result = mysqli_query($conn, "SELECT Status,Check_In_Type,Transaction_Type FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID' AND Status IN('active','approved','partial dispensed','paid','removed')  AND Check_In_Type IN('Pharmacy')") or die(mysqli_error($conn));
      if (mysqli_num_rows($sql_select_active_or_approved_item_result) <= 0) {
        continue;
      }
      $Medication_Status_rws = mysqli_fetch_assoc($sql_select_active_or_approved_item_result);
      $Medication_Status = $Medication_Status_rws['Status'];
      $Check_In_Type = $Medication_Status_rws['Check_In_Type'];
      $Transaction_Type = $Medication_Status_rws['Transaction_Type'];
      
      $status_style = "";
      if($Medication_Status == 'active') {
        $Medication_Status = 'Not Yet Approved';
      }else if($Medication_Status == 'removed'){
        $status_style = "style='background-color:#b56060;color:white'";
      }else{
        $status_style = "";
      }

      $sql_date_time = mysqli_query($conn, "SELECT now() as Date_Time ") or die(mysqli_error($conn));
      while ($date = mysqli_fetch_array($sql_date_time)) {
        $Today = $date['Date_Time'];
      }
      $date1 = new DateTime($Today);
      $date2 = new DateTime($Date_Of_Birth);
      $diff = $date1->diff($date2);
      $age = $diff->y . " Years, ";
      $age .= $diff->m . " Months, ";
      $age .= $diff->d . " Days";
      if ($Check_In_Type == "Others") {
        $change_color = "style='background:green;color:#FFFFFF'";
        $change_color2 = "color:#FFFFFF";
      } else {
        $change_color = "";
        $change_color2 = "";
      }
      //sql select payment sponsor
      $Guarantor_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'"))['Guarantor_Name'];
      echo "
                <tr class='rows_list' $change_color onclick='open_selected_patient($Payment_Cache_ID,$Registration_ID,\"$Transaction_Type\",\"$Check_In_Type\")'>
                        <td><center>$count_sn.</center></td>
                        <td {$status_style}>" . ucwords($Medication_Status) . "</td>
                        <td>".ucwords($Patient_Name)."</td>
                        <td>$Registration_ID</td>
                        <td>$age</td>
                        <td>$Gender</td>
                        <td>$Guarantor_Name</td>
                        <td>$Payment_Date_And_Time</td>
                        <td>$Member_Number</td>
                        <td>$Billing_Type</td>
                    </a>
                </tr>
                ";
      $count_sn++;
    }
  }
}
