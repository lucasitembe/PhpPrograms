<?php
session_start();
    include("includes/connection.php");
    $Employee = $_SESSION['userinfo']['Employee_Name'];
    
   if (isset($_GET['action'])){
    $Like_Name='';
    $date_From=$_GET['date_From'];
    $date_To=$_GET['date_To'];
    $Sponsor_ID=$_GET['Sponsor_ID'];
    $Employee_ID=$_GET['Employee_ID'];
   if($_GET['action']=='filter') {
    $between_Date="AND Service_Date_And_Time BETWEEN '$date_From' AND '$date_To'";
  
    if($Sponsor_ID=='All'){
        $sponsor='';
        $Sponsor_Name='ALL';
    }  else {
       $sponsor="AND sp.Sponsor_ID='$Sponsor_ID'"; 
       $query=  mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'");
       $row=  mysqli_fetch_assoc($query);
       $Sponsor_Name=$row['Guarantor_Name'];
    }
    
    if($Employee_ID=='All'){
        $Employee_Name='';
        $Doactor='ALL';
    }else{
       $Employee_Name="AND il.Consultant_ID='$Employee_ID'";
       $query=  mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'");
       $row=  mysqli_fetch_assoc($query);
       $Doactor=$row['Employee_Name'];
    }
    
    $employee_search=$_GET['Patient_Name'];
    
    if($employee_search=='' || $employee_search=='NULL'){
      $Like_Name='';  
        
    }  else {
      $Like_Name="AND pr.Patient_Name LIKE '%$employee_search%'";  
    }

   }

}else{
    
  $sponsor=''; 
  $between_Date='';
  $Employee_Name='';
}

        $disp= "<table width ='100%' height = '30px'>
		<tr>
		    <td>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td>
		</tr>
		<tr>
		   <td style='text-align: center;'><b>DOCTOR'S  SURGERY SCHEDULE</b></td>
		</tr>
                <tr>
		   <td style='text-align: center;'><b>FROM ".$_GET['date_From']." TO ".$_GET['date_To']."</b></td>
		</tr>
                
                <tr>
		   <td style='text-align: center;'><b>SPONSOR: ".ucwords($Sponsor_Name)."</b></td>
		</tr>
                
                <tr>
		   <td style='text-align: center;'><b>DOCTOR: ".ucwords($Doactor)."</b></td>
		</tr>
                <tr>
                    <td style='text-align: center;'><hr></td>
                </tr>
            </table>";
    $disp.= '<center><table border="1" id="doctorperformancereportsummarised" class="display" style="width:100%;border-collapse: collapse;">';
    $disp.= "<thead>
                 <tr>
			    <th width=3% style='text-align:left'>SN</th>
                            <th style='text-align:left'>STATUS</th>
                            <th style='text-align:left'>DOCTOR'S NAME</th>
                            <th style='text-align: left;'>PATIENT NAME</th>
                            <th style='text-align: left;'>PATIENT #</th>
                            <th style='text-align: left;'>SPONSOR</th>
                            <th style='text-align: left;'>SURGERY NAME</th>
                            <th style='text-align: left;'>SURGERY DATE</th>
                            <th style='text-align: left;'>SURGERY DURATION</th>
                            <th style='text-align: left;'>TRANSACTION DATE</th>
                            <th style='text-align: left;'>LOCATION</th>
                            <th style='text-align: left;'>PATIENTS PHONE NUMBER</th>
		</tr>
            </thead>";



$result = mysqli_query($conn,"SELECT 'cache' as Status_From,Payment_Item_Cache_List_ID,Surgery_hour,Surgery_min,il.payment_type,pc.Billing_Type,il.Transaction_Type as transaction,pr.Patient_Name,pc.Sponsor_Name,pr.Date_Of_Birth,te.Employee_Name,Product_Name,il.Service_Date_And_Time,Procedure_Location,
                                           pr.Gender,pr.Phone_Number,pr.Registration_ID as registration_number,pc.Receipt_Date as Required_Date,
                                           pc.Payment_Cache_ID as payment_id,il.Status as Status,il.Consultant as Doctors_Name,il.Transaction_Date_And_Time as Transaction_Date_And_Time
                                            FROM tbl_item_list_cache as il
                                            JOIN tbl_items as i ON i.Item_ID = il.Item_ID 
                                            JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID 
                                            JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
                                            JOIN tbl_sponsor AS sp ON sp.Sponsor_ID =pr.Sponsor_ID
                                            JOIN tbl_employee te ON te.Employee_ID=il.Consultant_ID
                                            WHERE Check_In_Type ='Surgery' AND (il.Status='active' OR il.Status='paid') AND removing_status='No' $between_Date $sponsor $Employee_Name $Like_Name GROUP BY Payment_Item_Cache_List_ID ORDER BY Transaction_Date_And_Time DESC  LIMIT 100");

$sn=1;
while($select_doctor_row = mysqli_fetch_array($result)){
$disp.= "<tr><td>".$sn++."</td>";

$billing_Type = strtolower($select_doctor_row['Billing_Type']);
                     $status = strtolower($select_doctor_row['Status']);
                    $transaction_Type = strtolower($select_doctor_row['transaction']);
                    $payment_type = strtolower($select_doctor_row['payment_type']);

                    if (($billing_Type == 'outpatient cash' && $status == 'active' && $transaction_Type == "cash")) {
                        $tatus= 'Not paid';
                    } elseif (($billing_Type == 'outpatient cash' && $status == 'active' && $transaction_Type == "credit")) {
                        $tatus= 'Not Billed';
                
                    }  elseif (($billing_Type == 'outpatient credit' && $status == 'active' && $transaction_Type == "cash")) {
                        $tatus= 'Not paid';
                    } elseif (($billing_Type == 'inpatient cash' && $status == 'active') || ($billing_Type == 'inpatient credit' && $status == 'active' && $transaction_Type == "cash")) {

                        if ($pre_paid == '1') {
                            $tatus= 'Not paid';
                        } else {
                            if ($payment_type == 'pre'  && $status == 'active') {
                                   $tatus= 'Not paid';
                            } else {
                                $tatus= 'Not Billed';
                            }
                        }
                    } elseif (($billing_Type == 'outpatient cash' && $status == 'paid') || ($billing_Type == 'outpatient credit' && $status == 'paid' && $transaction_Type == "cash")) {
                        $tatus= 'Paid';
                    } elseif (($billing_Type == 'inpatient cash' && $status == 'paid') || ($billing_Type == 'inpatient credit' && $status == 'paid' && $transaction_Type == "cash")) {
                        $tatus= 'Paid';
                    } else {
                        if ($payment_type == 'pre') {
                            $tatus= 'Not paid';
                        } else {
                            $tatus= 'Not Billed';
                        }
                    }


$disp.= "<td>".$tatus."</td>";
$disp.= "<td style='text-align:left'>".$select_doctor_row['Employee_Name']."</td>";
$disp.= "<td style='text-align:left'>" . $select_doctor_row['Patient_Name'] . "</td>";
$disp.= "<td style='text-align:left'>" . $select_doctor_row['registration_number'] . "</td>";
$disp.= "<td style='text-align:left'>" . $select_doctor_row['Sponsor_Name'] . "</td>";
$disp.= "<td style='text-align:left'>" . $select_doctor_row['Product_Name'] . "</td>";
$disp.= "<td class='Date_Time' id='".$select_doctor_row['Payment_Item_Cache_List_ID']."' style='text-align:left'>". $select_doctor_row['Service_Date_And_Time'] . "</td>";
$disp.="<td style='text-align:left'>" . $select_doctor_row['Surgery_hour'] .'hrs :'.$select_doctor_row['Surgery_min']. "min</td>";
$disp.= "<td style='text-align:left'>" . $select_doctor_row['Transaction_Date_And_Time'] . "</td>";
$disp.= "<td style='text-align:left'>" . $select_doctor_row['Procedure_Location'] . "</td>";
$disp.= "<td style='text-align:left'>" . $select_doctor_row['Phone_Number'] . "</td>

</tr>";
}
$disp.= "</table>";
    include("MPDF/mpdf.php");
    $mpdf = new mPDF('c', 'A4-L');
    $mpdf->SetFooter('{PAGENO}/{nb}|  Printed By '.$Employee.'                   {DATE d-m-Y H:m:s}');
    $mpdf->WriteHTML($disp);
    $mpdf->Output();
    exit;
?>