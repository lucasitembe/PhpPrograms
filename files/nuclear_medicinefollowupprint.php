<?php
include("./includes/connection.php");
session_start();
if(isset($_SESSION['userinfo']['Employee_Name'])){
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
}else{
    $Employee_Name = '';
} 

if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
} else {
    $Patient_Payment_ID = 0;
}

if(isset($_GET['Assessment_ID'])){
    $Assessment_ID = $_GET['Assessment_ID'];
}else{
    $Assessment_ID = 0;
}
// die($Assessment_ID);
if (isset($_GET['Payment_Item_Cache_List_ID'])) {
    $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
} else {
    $Payment_Item_Cache_List_ID = 0;
}

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $Surgery = $new_Date;
} 
//get patient details
$select = mysqli_query($conn,"SELECT Patient_Name, Gender, Date_Of_Birth, Guarantor_Name, Member_Number from  tbl_patient_registration pr, tbl_sponsor sp, tbl_nm_assessmentform nm where  pr.Sponsor_ID = sp.Sponsor_ID and  pr.Registration_ID = nm.Registration_ID AND Assessment_ID='$Assessment_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if($num > 0){
    while ($data = mysqli_fetch_array($select)) {
        $Patient_Name = $data['Patient_Name'];
        $Gender = $data['Gender'];
        $Date_Of_Birth = $data['Date_Of_Birth'];
        $Guarantor_Name = $data['Guarantor_Name'];
        $Member_Number = $data['Member_Number'];
    }
}

$date1 = new DateTime($Today);
$date2 = new DateTime($Date_Of_Birth);
$diff = $date1 -> diff($date2);
$Age = $diff->y." Years, ";
$Age .= $diff->m." Months, ";
$Age .= $diff->d." Days";

$htm ='<table align="center" width="100%">
<tr><td style="text-align:center" colspan="6"><img src="./branchBanner/branchBanner.png"></td></tr>
</table>
<b><h4 align="center">RADIO - ACTIVE IODINE TREATMENT FOLLOW UP FORM</h4>
</b>
<table width="100%" style="border: 1px solid black;"> 
<tr><td  width="9%" style="text-align: right;"><b>Patient Name</b></td>
<td>'. $Patient_Name.' </td>
<td width="9%" style="text-align: right;"><b>Sponsor Name</b></td>
<td>'. $Guarantor_Name.' </td>
<td style="text-align: right;"><b>Gender</b> </td>
<td>'. $Gender.' </td>
<td style="text-align: right;"><b>Age</b></td>
<td>'. $Age.' </td>
</tr>

</table>
';
$htm.='<legend align="center"><b>RADIO - ACTIVE IODINE TREATMENT FOLLOW UP FORM</b></legend>
        <table  style="width:100%; border: 1px solid black;">
            <thead style="border: 1px solid black;">
                <tr>
                    
                    <th>SN</th>
                    <th>Date</th>
                    <th>T3</th>
                    <th>T4</th>
                    <th>THS</th>
                    <th>99TCO4 uptake(0.75-4%)</th>
                    <th>BP</th>
                    <th>Pulse</th>
                    <th>Weight (Kg)</th>
                    <th>Reflexes</th>
                    <th>Medication</th>
                    <th>Plan</th>
                    <th>Visit</th>
                </tr>
            </thead>
            <tbody id="followuptreatment">';
    $sn=1;       
$selectfllowup = mysqli_query($conn, "SELECT * FROM tbl_nm_followuptreatment WHERE Assessment_ID='$Assessment_ID'") or die(mysqli_error($conn));
while($row = mysqli_fetch_assoc($selectfllowup)){
    $followuptreatment = explode(',', $row['followuptreatment']);
    $created_at = $row['created_at'];
    $Assessment_ID = $row['Assessment_ID'];
    // die($created_at);
    $htm.= "<tr>
    <td>$sn</td>
    <td>$created_at</td>
    <td>".$followuptreatment[0]."</td>
    <td>".$followuptreatment[1]."</td>
    <td>".$followuptreatment[2]."</td>
    <td>".$followuptreatment[3]."</td>
    <td>".$followuptreatment[4]."</td>
    <td>".$followuptreatment[5]."</td>
    <td>".$followuptreatment[6]."</td>
    <td>".$followuptreatment[7]."</td>
    <td>".$followuptreatment[8]."</td>
    <td>".$followuptreatment[9]."</td>
    <td>".$followuptreatment[10]."</td>
</tr>

";
$sn++;
}
    $htm.='   
    </tbody>
</table>';

include("./MPDF/mpdf.php");
$mpdf=new mPDF('c','A4','','', 15,15,20,23,15,20, 'P'); 
$mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($htm,2);

$mpdf->Output('mpdf.pdf','I');
exit;
?>