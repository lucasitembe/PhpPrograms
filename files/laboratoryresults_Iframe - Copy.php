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

//Find the current date to filter check in list

$Today_Date = mysqli_query($conn,"select now() as today");
while($row = mysqli_fetch_array($Today_Date)){
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

echo '<center><table width =100%>';
echo '<tr id="thead">
        <td style="width:5%;"><b>SN</b></td>
	        <td><b>PATIENT NAME</b></td>
                <td><b>PATIENT NUMBER</b></td>
		            <td><b>SPONSOR</b></td>
			            <td><b>DATE OF BIRTH</b></td>
			                <td><b>GENDER</b></td>
				                <td><b>PHONE NUMBER</b></td>
				                    <td><b>MEMBER NUMBER</b></td></tr>';

$select_Filtered_Patients = mysqli_query($conn,"SELECT 'payment' AS Status_From,pr.Registration_ID,pr.Patient_Name,pr.Member_Number,pr.Gender,pr.Sponsor_ID,sp.Sponsor_ID,sp.Guarantor_Name,pr.Date_Of_Birth,pr.Phone_Number,ppr.Item_ID,ppr.Patient_Payment_ID AS Patient_Payment_ID
                                          FROM  tbl_patient_payment_results ppr
                                          JOIN tbl_patient_registration pr ON ppr.Patient_ID = pr.Registration_ID
                                          JOIN tbl_patient_payments pp ON ppr.Patient_Payment_ID = pp.Patient_Payment_ID
                                          JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                                          WHERE ppr.Patient_ID = pr.Registration_ID
                                          AND ppr.Patient_Payment_ID = pp.Patient_Payment_ID
                                          AND pr.Sponsor_ID = sp.Sponsor_ID GROUP BY Patient_Payment_ID
                                           UNION ALL

                                          SELECT 'cache' AS Status_From,pr.Registration_ID,pr.Patient_Name,pr.Member_Number,pr.Gender,pr.Sponsor_ID,sp.Sponsor_ID,sp.Guarantor_Name,pr.Date_Of_Birth,pr.Phone_Number,pcr.Item_ID,pcr.Payment_Cache_ID AS Patient_Payment_ID
                                          FROM  tbl_patient_cache_results pcr
                                          JOIN tbl_patient_registration pr ON pcr.Patient_ID = pr.Registration_ID
                                          JOIN tbl_payment_cache pc ON pcr.Payment_Cache_ID = pc.Payment_Cache_ID
                                          JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                                          WHERE pcr.Patient_ID = pr.Registration_ID
                                          AND pcr.Payment_Cache_ID = pc.Payment_Cache_ID
                                          AND pr.Sponsor_ID = sp.Sponsor_ID GROUP BY Patient_Payment_ID") or die(mysqli_error($conn));

while($row = mysqli_fetch_array($select_Filtered_Patients)){
    echo "<tr><td id='thead'>".$temp."</td><td><a href='laboratory_result_details.php?Registration_ID=".$row['Registration_ID']."&Status_From=".$row['Status_From']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
    echo "<td><a href='laboratory_result_details.php?Registration_ID=".$row['Registration_ID']."&Status_From=".$row['Status_From']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
    echo "<td><a href='laboratory_result_details.php?Registration_ID=".$row['Registration_ID']."&Status_From=".$row['Status_From']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
    echo "<td><a href='laboratory_result_details.php?Registration_ID=".$row['Registration_ID']."&Status_From=".$row['Status_From']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".date('jS F, Y',date(strtotime($row['Date_Of_Birth'])))."</a></td>";
    echo "<td><a href='laboratory_result_details.php?Registration_ID=".$row['Registration_ID']."&Status_From=".$row['Status_From']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
    echo "<td><a href='laboratory_result_details.php?Registration_ID=".$row['Registration_ID']."&Status_From=".$row['Status_From']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
    echo "<td><a href='laboratory_result_details.php?Registration_ID=".$row['Registration_ID']."&Status_From=".$row['Status_From']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
    $temp++;
}    echo "</tr>";
?></table></center>

