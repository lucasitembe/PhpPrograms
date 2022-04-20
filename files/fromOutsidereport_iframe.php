<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
</style>

<?php
include("./includes/connection.php");
echo "<link rel='stylesheet' href='fixHeader.css'>";
if (isset($_POST['action'])){
    
    $start_date =  mysqli_real_escape_string($conn,$_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn,$_POST['end_date']);
    $Sponsor = mysqli_real_escape_string($conn,$_POST['Sponsor_ID']);
//   $ward = mysqli_real_escape_string($conn,$_POST['Ward_id']);
    
    if($Sponsor=='All'){
       $sponsorName=''; 
    }  else {
       $sponsorName="AND tr.Sponsor_ID='$Sponsor'"; 
    }
    

}  else {
  
    $start_date='';
    $end_date='';
}

//exit();

$temp = 1;
if (isset($_GET['consultation_ID'])) {
    $consultation_ID = $_GET['consultation_ID'];
}

$filter = " em.Employee_ID = sg.Employee_ID AND it.Item_ID = sg.Item_ID AND sg.Registration_ID=tr.Registration_ID AND sg.consultation_ID=tc.consultation_ID AND DATE(sg.Time_Given)=DATE(NOW()) AND Medication_type='outside' ORDER BY sg.Time_Given DESC";

if (!empty($start_date) && !empty($end_date)) {
    $filter = "  em.Employee_ID = sg.Employee_ID AND it.Item_ID = sg.Item_ID AND sg.Registration_ID=tr.Registration_ID AND sg.consultation_ID=tc.consultation_ID AND sg.Time_Given BETWEEN '$start_date' AND '$end_date' AND Medication_type='outside' $sponsorName ORDER BY sg.Time_Given DESC";
}



if (empty($start_date) && empty($end_date)) {
    $betweenDate = "<br/><br/>Today " . date('Y-m-d');
} else {
    $betweenDate = "<br/><br/>FROM</b> " . $start_date . " <b>TO</b> " . $end_date;
}
$htm= "<table width='100%' id='nurse_medicine' class='fixTableHead'>";
$htm.= ""
 . "<thead><tr nobr='true' style='background-color: #ccc;'>";
$htm.= "<td widtd=5%> <b>SN </b></td>";
$htm.= "<td widtd=10%><b> Patient Name</b> </td>";
$htm.= "<td widtd=5%><b> Reg #</b> </td>";
$htm.= "<td widtd=30%><b> Medicine Name</b> </td>";
$htm.= "<td widtd=7%><b> Amount Given </b></td>";
$htm.= "<td widtd=7%><b> Time Given </b></td>";
$htm.= "<td widtd=7%><b>Nurse Remarks </b></td>";
$htm.= "<td widtd=7%><b> Discontinued?</b></td>";
$htm.= "<td widtd=7%><b> Discontinue Reason </b></td>";
$htm.= "<td widtd=15%><b> Given by </b></td></tr></thead>";

$htm.= "";


$select_services = "
	SELECT *
			FROM 
				tbl_inpatient_medicines_given sg,
				tbl_items it,
				tbl_employee em,
                                tbl_patient_registration tr,
                                tbl_consultation tc
					WHERE 
						$filter ";



$select_testing_record = mysqli_query($conn,$select_services) or die(mysqli_error($conn));
while ($service = mysqli_fetch_assoc($select_testing_record)) {
    $Patient_Name=$service['Patient_Name'];
    $Registration_ID=$service['Registration_ID'];
    $Product_Name = $service['Product_Name'];
    $Time_Given = $service['Time_Given'];
    $Amount_Given = $service['Amount_Given'];
    $Nurse_Remarks = $service['Nurse_Remarks'];
    $Discontinue_Status = $service['Discontinue_Status'];
    $Discontinue_Reason = $service['Discontinue_Reason'];
    $Employee_Name = $service['Employee_Name'];
    $htm.= "<tr>";
    $htm.= "<td id='thead' style='text-align: center;'>" . $temp . "</td>";
    $htm.= "<td>" . ucwords(strtolower($Patient_Name)) . "</td>";
    $htm.= "<td>" . $Registration_ID . "</td>";
    $htm.= "<td>" . $Product_Name . "</td>";
    $htm.= "<td style='text-align: center;'>" . $Amount_Given . "</td>";
    $htm.= "<td>" . $Time_Given . "</td>";
    $htm.= "<td style='text-align: center;'>" . ucwords($Nurse_Remarks) . "</td>";
    $htm.= "<td style='text-align: center;'>" . ucwords($Discontinue_Status) . "</td>";
    $htm.= "<td>" . $Discontinue_Reason . "</td>";
    $htm.= "<td>" . ucwords(strtolower($Employee_Name)) . "</td></tr>";
    
    $temp++;
}
$htm.= "</table></center>";
echo "
<script>
    $('#nurse_medicine').DataTable({
        'bJQueryUI': true
    });
</script>
<link rel='stylesheet' href='media/css/jquery.dataTables.css' media='screen'>
<link rel='stylesheet' href='media/themes/smoothness/dataTables.jqueryui.css' media='screen'>
<script src='media/js/jquery.js' type='text/javascript'></script>
<script src='media/js/jquery.dataTables.js' type='text/javascript'></script>
<script src='css/jquery-ui.js'></script>
";
echo $htm;

?>
