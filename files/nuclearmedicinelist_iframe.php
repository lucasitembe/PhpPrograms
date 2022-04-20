<?php
include './includes/connection.php';
@session_start();
$pre_paid = $_SESSION['hospitalConsultaioninfo']['set_pre_paid'];
$Sub_Department_ID = $_SESSION['Radiology_Sub_Department_ID'];


$Date_From = '';
$Date_To = '';
$Sponsor = '';
$filter2 ="";
$section = "section=Procedure&";
echo '<table width =100% border=0 class="display" id="patientLabList">
            <thead>
                <tr>
                    <th><b>SN</b></th>
                    <th><b>STATUS</b></th>
                    <th style="width:22%;"><b>PATIENT</b></th>
                    <th><b>REG#</b></th>
                    <th style="width:2%;"><b>SPONSOR</b></th>
                    <th style="width:18%;"><b>AGE</b></th>
                    <th><b>GENDER</b></th>
                    <th style="width:14%;"><b>TRANS DATE</b></th>
                    <th><b>PHONE#</b></th>
		    <!--<th>ACTION</th>-->
                </tr>
            </thead>';
$Patient_Name = '';
$filter = "  AND DATE(Payment_Date_And_Time) = DATE(NOW())";

$SubCategory = 'All';

//if (isset($_GET['filterlabpatientdate']) && $_GET['filterlabpatientdate'] == true) {
    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    $SubCategory = filter_input(INPUT_GET, 'SubCategory');
    $Registration_ID = filter_input(INPUT_GET, 'Registration_ID');

    
    if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
        $filter = "  AND Payment_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
    }
    

    if (!empty($Sponsor) && $Sponsor != 'All'  ) {
        $filter .=" AND sp.Sponsor_ID='$Sponsor'";
    }

    if (!empty($Patient_Name) ) {
        $filter .= " AND pr.Patient_Name LIKE '%" . $_GET['Patient_Name'] . "%'";
    }
    if (!empty($Registration_ID) ) {
        $filter .= " AND pr.Registration_ID LIKE '%" . $_GET['Registration_ID'] . "%'";
    }

    
    
$result  = mysqli_query($conn,"SELECT pr.Patient_Name,pr.Gender,pr.Date_Of_Birth,pr.Phone_Number,pr.Registration_ID,pr.Sponsor_ID,pc.Payment_Cache_ID,pc.Billing_Type,pc.Payment_Date_And_Time,Transaction_Type FROM tbl_payment_cache as pc,tbl_patient_registration AS pr WHERE pc.Registration_ID=pr.Registration_ID $filter ORDER BY Payment_Cache_ID DESC LIMIT 200") or die(mysqli_error($conn)); 
  

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}

$statusPay = '';
$num_rows = mysqli_num_rows($result);


$temp = 1;

$Sponsor_ID ="";
$Sponsor_Name = "";
$Registration_ID = "";
$Patient_Name = "";
 $Gender ="";
$registration_number ="";
$Phone_Number ="";
$Payment_Date_And_Time= "";
$status ="";
$billing_Type ="";
$transaction_Type = "";
$payment_type = "";
$num ="";
echo $num;
if(mysqli_num_rows($result)>0){
   while($fetched_rows=mysqli_fetch_assoc($result)){
       $Patient_Payment_ID=$fetched_rows['Payment_Cache_ID'];
       $Payment_Cache_ID=$fetched_rows['Payment_Cache_ID'];
       $Registration_ID=$fetched_rows['Registration_ID'];
       $Sponsor_ID = $fetched_rows['Sponsor_ID'];
       $Patient_Name = $fetched_rows['Patient_Name'];
       $Gender = $fetched_rows['Gender'];
       $Payment_Date_And_Time =  $fetched_rows['Payment_Date_And_Time'];
       $Phone_Number =  $fetched_rows['Phone_Number']; 
       $Date_Of_Birth = $fetched_rows['Date_Of_Birth'];
       $billing_Type = strtolower($fetched_rows['Billing_Type']);
       
       $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
       $Sponsor_Name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'"))['Guarantor_Name'];
       $select_Filtered_Patients1=mysqli_query($conn,"SELECT il.payment_type,il.Status FROM tbl_item_list_cache il,tbl_items i WHERE il.Check_In_Type = 'Nuclearmedicine' AND il.Status IN ('active','paid') AND il.removing_status='no' AND  il.Payment_Cache_ID='$Payment_Cache_ID' AND i.Item_ID = il.Item_ID   LIMIT 1") or die(mysqli_error($conn));
       if(mysqli_num_rows($select_Filtered_Patients1)>0){
           while($row=mysqli_fetch_assoc($select_Filtered_Patients1)){
                    $status = strtolower($row['Status']);
                    $transaction_Type = strtolower($row['Transaction_Type']);
                    $payment_type = strtolower($row['payment_type']);
                    $displ = '';
          
       
    if (( $billing_Type == 'outpatient cash' && $status == 'active') || ($billing_Type == 'outpatient credit' && $status == 'active' && $transaction_Type == "cash")) {
        $statusPay = 'Not paid';
    } elseif (($billing_Type == 'inpatient cash' && $status == 'active') || ($billing_Type == 'inpatient credit' && $status == 'active' && $transaction_Type == "cash")) {

        if ($pre_paid == '1') {
            if($payment_type == 'post'){
                $statusPay = 'Bill';
            } else {
                $statusPay = 'Not paid';
            }
            
        } else {
            $statusPay = 'Bill';
        }
    } elseif (($billing_Type == 'outpatient cash' && $status == 'paid') || ($billing_Type == 'outpatient credit' && $status == 'paid' && $transaction_Type == "cash")) {
        $statusPay = 'Paid';
    } elseif (($billing_Type == 'inpatient cash' && $status == 'paid') || ($billing_Type == 'inpatient credit' && $status == 'paid' && $transaction_Type == "cash")) {
        $statusPay = 'Paid';
    } else {
        $statusPay = 'Bill';
    }
    
    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);

    $diff = $date1->diff($date2);

    $age = $diff->y . " Years, ";
    $age .= $diff->m . " Months, ";
    $age .= $diff->d . " Days";



    echo "<tr><td>" . $temp . "</td>
                                    
    <td><a class='viewDetails' href='nuclearmedicinepatientinfo.php?Registration_id=" . $Registration_ID . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor . "&Sub_Department_ID=" . $Sub_Department_ID . "&SubCategory=" . $SubCategory . " ' style='text-decoration: none;'>$statusPay</a></td>
    <td><a class='viewDetails' href='nuclearmedicinepatientinfo.php?Registration_id=" . $Registration_ID . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor . "&Sub_Department_ID=" . $Sub_Department_ID . "&SubCategory=" . $SubCategory . "' style='text-decoration: none;'>" . $Patient_Name . "</a></td>";
    echo "<td><a class='viewDetails'  href='nuclearmedicinepatientinfo.php?Registration_id=" .  $Registration_ID . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor . "&Sub_Department_ID=" . $Sub_Department_ID . "&SubCategory=" . $SubCategory . "' style='text-decoration: none;'>" . $Registration_ID . "</a></td>";
    echo "<td><a class='viewDetails' href='nuclearmedicinepatientinfo.php?Registration_id=" .   $Registration_ID . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor . "&Sub_Department_ID=" . $Sub_Department_ID . "&SubCategory=" . $SubCategory . "' style='text-decoration: none;'>" . $Sponsor_Name . "</a></td>";
    echo "<td><a class='viewDetails' href='nuclearmedicinepatientinfo.php?Registration_id=" .   $Registration_ID . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor . "&Sub_Department_ID=" . $Sub_Department_ID . "&SubCategory=" . $SubCategory . "' style='text-decoration: none;'><center>" . $age . "</center></a></td>";
    echo "<td><a class='viewDetails' href='nuclearmedicinepatientinfo.php?Registration_id=" .   $Registration_ID . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor . "&Sub_Department_ID=" . $Sub_Department_ID . "&SubCategory=" . $SubCategory . "' style='text-decoration: none;'>" . $Gender . "</a></td>";
    echo "<td><a class='viewDetails' href='nuclearmedicinepatientinfo.php?Registration_id=" .   $Registration_ID . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor . "&Sub_Department_ID=" . $Sub_Department_ID . "&SubCategory=" . $SubCategory . "' style='text-decoration: none;'>" . $Payment_Date_And_Time . "</a></td>";
    echo "<td><a class='viewDetails' href='nuclearmedicinepatientinfo.php?Registration_id=" .   $Registration_ID . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor . "&Sub_Department_ID=" . $Sub_Department_ID . "&SubCategory=" . $SubCategory . "' style='text-decoration: none;'>" . $Phone_Number . "</a></td>";
     $temp++;
}
}    
   }    
} 
//}
echo "</table>";





