<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
</style>

<?php
include("./includes/connection.php");
$Printed_by_Name = $_SESSION['userinfo']['Employee_Name'];
$Printed_by= ucwords(strtolower($Printed_by_Name));

if (isset($_GET['action'])){
    
    $start_date =  mysqli_real_escape_string($conn,$_GET['start_date']);
    $end_date = mysqli_real_escape_string($conn,$_GET['end_date']);
    $Sponsor = mysqli_real_escape_string($conn,$_GET['Sponsor_ID']);
//   $ward = mysqli_real_escape_string($conn,$_POST['Ward_id']);
    
    if($Sponsor=='All'){
       $sponsorName=''; 
       $Guarantor_Name='All';
    }  else {
       $sponsorName="AND tr.Sponsor_ID='$Sponsor'"; 
    }
    

}  else {
  
    $start_date='';
    $end_date='';
}

if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND sp.Sponsor_ID=$Sponsor";

    $rs = mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor'") or die(mysqli_error($conn));

    $Guarantor_Name = mysqli_fetch_assoc($rs)['Guarantor_Name'];
}

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
$temp=1;

$htm = '<table width ="100%" border="0" style="background-color:white;" class="nobordertable">
            <tr>
                <td style="text-align:center">
                    <img src="./branchBanner/branchBanner.png" width="100%" />
                </td>
            </tr>
            <tr>
                <td  style="text-align:center">
                    <b>PATIENTS WITH THEIR OWN MEDICINE</b>
                </td>
            </tr>';

if (!empty($betweenDate)) {
    $htm .= '
            <tr>
                <td  style="text-align:center">' . $betweenDate . '
                </td>
            </tr>';
}

$htm .='
        <tr>
            <td  style="text-align:center"><b>Sponsor: </b>' . $Guarantor_Name . '</td>
        </tr>
        </table>';

$htm.= "<table width='100%' id='nurse_medicine' class='fixTableHead'>";
$htm.= ""
 . "<thead><tr nobr='true' style='background-color: #ccc;'>";
$htm.= "<td widtd=5%> <b>SN </b></td>";
$htm.= "<td widtd=15%><b> Patient Name</b> </td>";
$htm.= "<td widtd=5%><b> Reg #</b> </td>";
$htm.= "<td widtd=15%><b> Medicine Name</b> </td>";
$htm.= "<td widtd=9%><b> Amount Given </b></td>";
$htm.= "<td widtd=9%><b> Time Given </b></td>";
$htm.= "<td widtd=9%><b>Nurse Remarks </b></td>";
$htm.= "<td widtd=7%><b> Discontinued?</b></td>";
$htm.= "<td widtd=9%><b> Discontinue Reason </b></td>";
$htm.= "<td widtd=17%><b> Given by </b></td></tr></thead><tbody style='font-size: 22px;'>";

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
    $htm.= "<td widtd=5% id='thead'>" . $temp . "</td>";
    $htm.= "<td widtd=15%>" . ucwords(strtolower($Patient_Name)) . "</td>";
    $htm.= "<td widtd=5%>" . $Registration_ID . "</td>";
    $htm.= "<td widtd=15%>" . $Product_Name . "</td>";
    $htm.= "<td widtd=9%>" . $Amount_Given . "</td>";
    $htm.= "<td widtd=9%>" . $Time_Given . "</td>";
    $htm.= "<td widtd=9%>" . ucwords($Nurse_Remarks) . "</td>";
    $htm.= "<td widtd=7%>" . ucwords($Discontinue_Status) . "</td>";
    $htm.= "<td widtd=9%>" . $Discontinue_Reason . "</td>";
    $htm.= "<td widtd=17%>" . ucwords(strtolower($Employee_Name)) . "</td>";
    $temp++;
}
$htm.= "</tbody></table></center>";


    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('c','A4','','', 15,15,20,23,15,20, 'P'); 
    $mpdf->SetFooter('Printed By '.$Printed_by.'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($htm,2);

    $mpdf->Output('mpdf.pdf','I');
    exit; 

mysqli_close($conn);

?>
