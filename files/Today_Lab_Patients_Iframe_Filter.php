<link rel="stylesheet" href="table.css" media="screen">
<?php
include("./includes/connection.php");
$temp = 1;

if(isset($_GET['Patient_Name'])){
    $Patient_Name = $_GET['Patient_Name'];
}else{
    $Patient_Name = '';
}

if(isset($_GET['Patient_Number'])){
    $Patient_Number = $_GET['Patient_Number'];
}else{
    $Patient_Number = '';
}
if(isset($_GET['Phone_Number'])){
    $Phone_Number = $_GET['Phone_Number'];
}else{
    $Phone_Number = '';
}

if(isset($_GET['Date_From'])){
    $Date_From = date('Y-m-d H:i:s',strtotime($_GET['Date_From']));
}else{
    $Date_From = '';
}

if(isset($_GET['Date_To'])){
    $Date_To = date('Y-m-d H:i:s',strtotime($_GET['Date_To']));
}else{
    $Date_To = '';
}

//Find the current date to filter check in list

$Today_Date = mysqli_query($conn,"select now() as today");
while($row = mysqli_fetch_array($Today_Date)){
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

echo '<center><table width =100%>';
echo '<tr id="thead"><td width = 5%><b>SN</b></td>
	    <td><b>PATIENT NAME</b></td>
                <td><b>PATIENT NUMBER</b></td>
		    <td><b>SPONSOR</b></td>
			<td><b>DATE OF BIRTH</b></td>
			    <td><b>GENDER</b></td>
				<td><b>REGION</b></td>
				<td><b>PHONE NUMBER</b></td>
                                <td><b>DIRECTED FROM</b></td>
				    <td><b>MEMBER NUMBER</b></td></tr>';
$Current_Date=date("Y-m-d");
$select_Filtered_Patients = mysqli_query($conn,"SELECT 'payment' AS Status_From,ppl.Consultant,r.Region_Name,ppr.Patient_ID,pr.Registration_ID,pr.Patient_Name,pr.Member_Number,pr.Gender,pr.Sponsor_ID,sp.Sponsor_ID,sp.Guarantor_Name,pr.Date_Of_Birth,pr.Phone_Number,ppr.Item_ID,ppr.Patient_Payment_ID
                                          FROM  tbl_patient_payment_results ppr
                                          JOIN tbl_patient_registration pr ON ppr.Patient_ID = pr.Registration_ID
                                          JOIN tbl_patient_payments pp ON ppr.Patient_Payment_ID = pp.Patient_Payment_ID
                                          JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                                          JOIN tbl_district d ON pr.District_ID=d.District_ID
                                          JOIN tbl_regions r ON r.Region_ID=d.Region_ID
                                          JOIN tbl_patient_payment_item_list ppl ON ppl.Patient_Payment_ID=pp.Patient_Payment_ID
                                          WHERE ppr.Patient_ID = pr.Registration_ID
                                          AND ppr.Patient_Payment_ID = pp.Patient_Payment_ID
                                          AND pr.Sponsor_ID = sp.Sponsor_ID
                                          AND pr.Patient_Name LIKE '%$Patient_Name%'
                                          AND ppr.Result_Datetime BETWEEN '$Date_From' AND '$Date_To'
                                           UNION ALL

                                          SELECT 'cache' AS Status_From,il.Consultant,r.Region_Name,pcr.Patient_ID,pr.Registration_ID,pr.Patient_Name,pr.Member_Number,pr.Gender,pr.Sponsor_ID,sp.Sponsor_ID,sp.Guarantor_Name,pr.Date_Of_Birth,pr.Phone_Number,pcr.Item_ID,pcr.Payment_Cache_ID
                                          FROM  tbl_patient_cache_results pcr
                                          JOIN tbl_patient_registration pr ON pcr.Patient_ID = pr.Registration_ID
                                          JOIN tbl_payment_cache pc ON pcr.Payment_Cache_ID = pc.Payment_Cache_ID
                                          JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                                          JOIN tbl_district d ON pr.District_ID=d.District_ID
                                          JOIN tbl_regions r ON r.Region_ID=d.Region_ID
                                          JOIN tbl_item_list_cache il ON il.Payment_Cache_ID=pc.Payment_Cache_ID
                                          WHERE pcr.Patient_ID = pr.Registration_ID
                                          AND pcr.Payment_Cache_ID = pc.Payment_Cache_ID
                                          AND pr.Sponsor_ID = sp.Sponsor_ID
                                           AND pr.Patient_Name LIKE '%$Patient_Name%'
                                           AND pcr.Result_Datetime BETWEEN '$Date_From' AND '$Date_To' GROUP BY pcr.Patient_ID
                                           
                                           ") or die(mysqli_error($conn));

while($row = mysqli_fetch_array($select_Filtered_Patients)){
    echo "<tr><td>".$temp."</td><td><a href='Today_Lab_Result_Details.php?Registration_ID=".$row['Registration_ID']."&Status_From=".$row['Status_From']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";
    echo "<td><a href='Today_Lab_Result_Details.php?Registration_ID=".$row['Registration_ID']."&Status_From=".$row['Status_From']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
    echo "<td><a href='Today_Lab_Result_Details.php?Registration_ID=".$row['Registration_ID']."&Status_From=".$row['Status_From']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
    echo "<td><a href='Today_Lab_Result_Details.php?Registration_ID=".$row['Registration_ID']."&Status_From=".$row['Status_From']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".date('jS F, Y',date(strtotime($row['Date_Of_Birth'])))."</a></td>";
    echo "<td><a href='Today_Lab_Result_Details.php?Registration_ID=".$row['Registration_ID']."&Status_From=".$row['Status_From']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
    echo "<td><a href='Today_Lab_Result_Details.php?Registration_ID=".$row['Registration_ID']."&Status_From=".$row['Status_From']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Region_Name']."</a></td>";
    echo "<td><a href='Today_Lab_Result_Details.php?Registration_ID=".$row['Registration_ID']."&Status_From=".$row['Status_From']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
    echo "<td><a href='Today_Lab_Result_Details.php?Registration_ID=".$row['Registration_ID']."&Status_From=".$row['Status_From']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Consultant']."</a></td>";
    echo "<td><a href='Today_Lab_Result_Details.php?Registration_ID=".$row['Registration_ID']."&Status_From=".$row['Status_From']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
    $temp++;
}    echo "</tr>";
?></table></center>

