<?php
	@session_start();
	include("./includes/connection.php");
	$fromDate=mysqli_real_escape_string($conn,$_GET['fromDate']);
	$toDate=mysqli_real_escape_string($conn,$_GET['toDate']);
	$Sponsor_ID=mysqli_real_escape_string($conn,$_GET['Sponsor']);
	$Type_Of_visit=mysqli_real_escape_string($conn,$_GET['Type_Of_visit']);
	$Type_Of_patient=mysqli_real_escape_string($conn,$_GET['Type_Of_patient']);

	$agetype = mysqli_real_escape_string($conn, $_POST['agetype']);
	if(isset($_GET['ageFrom'])){
		$ageFrom = $_GET['ageFrom'];
	}else{
		$ageFrom = 0;
	}

	if(isset($_GET['ageTo'])){
		$ageTo = $_GET['ageTo'];
	}else{
		$ageTo = 0;
	}
	$filter =" AND TIMESTAMPDIFF($agetype ,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$ageFrom."' AND '".$ageTo."'";

	
	if($Sponsor_ID !='All'){
		$filter .=" AND pr.Sponsor_ID=$Sponsor_ID ";
		$Sponsor_Name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID=$Sponsor_ID"))['Guarantor_Name'];
	}else{
		$Sponsor_Name="All Sponsors";
	}

	if($Type_Of_patient!='all'){
		$filter2=" AND ci.Type_Of_Check_In='$Type_Of_patient' ";
	}else{
		$filter2="";
			
	}
	if($Type_Of_visit!='all'){
		$filter3=" AND ci.visit_type='$Type_Of_visit' ";
	}else{
		$filter3="";		
	}

$select_patients=mysqli_query($conn,"SELECT DISTINCT Visit_Date FROM tbl_check_in WHERE Check_In_Date_And_Time BETWEEN '$fromDate' AND '$toDate'") or die(mysqli_error($conn));
$htm  = "<table width ='100%' height = '30px'>";
$htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
$htm .= "<tr>";
$htm .= "<td style='text-align: center;'><h4> Patients Attendace List From ".$fromDate." To ".$toDate."</h4></td>";
$htm .= "</tr>";
$htm .= "<tr><td>Sponsor: {$Sponsor_Name}</td></tr>";
$htm .= "</table><br/>";

$htm .= "<table  width='100%' border='1' style='border-collapse: collapse;' cellpadding=5 cellspacing=10>";
$htm.= "<tr>";
$htm.= "<th>SN</th><th>Date</th><th>Male</th><th>Female</th><th>Total</th>";
$htm.= "</tr>";
$counter=1;
$total_male=0;
$total_female=0;
while ($row=mysqli_fetch_assoc($select_patients)) {
	$given_date=$row['Visit_Date'];
	$male_count=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(ci.Registration_ID) AS count FROM tbl_check_in ci, tbl_patient_registration pr WHERE pr.Registration_ID=ci.Registration_ID $filter2 AND pr.Gender='Male' $filter AND ci.Visit_Date ='$given_date' $filter3"))['count'];
	$female_count=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(ci.Registration_ID) AS count FROM tbl_check_in ci, tbl_patient_registration pr WHERE pr.Registration_ID=ci.Registration_ID $filter2 AND pr.Gender='Female' $filter AND ci.Visit_Date ='$given_date' $filter3"))['count'];

	$htm.= "<tr><td>{$counter}</td><td style='text-align:left;'>".$given_date.", ".date("l", strtotime($given_date))."</td><td style='text-align:center;'>".$male_count."</td><td style='text-align:center;'>".$female_count."</td><td style='text-align:right;'>".($male_count+$female_count)."</td></tr>";
	$counter++;
	$total_male+=$male_count;
	$total_female+=$female_count;
}
$htm.= "<tr><td colspan='2'><b>Total Attendance</b></td><td style='text-align:center;'><b>".number_format($total_male)."</b></td><td style='text-align:center;'><b>".number_format($total_female)."</b></td><td style='text-align:right;'><b>".number_format($total_male+$total_female)."</b></td></tr>";
$htm.= "</table>";
include("MPDF/mpdf.php");
    $mpdf = new mPDF('s', 'A4');
    $mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>
