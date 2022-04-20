<?php

include("./includes/connection.php");
$Registration_ID = $_GET['RI'];
$Item_ID = $_GET['II'];
$Patient_Payment_Item_List_ID = $_GET['PPILI'];



$Employee_Doctor = mysqli_fetch_assoc(mysqli_query($conn, "SELECT em.Employee_Name FROM tbl_employee em, tbl_item_list_cache ppi WHERE ppi.Payment_Item_Cache_List_ID = '$Patient_Payment_Item_List_ID' AND em.Employee_ID = ppi.Consultant_ID"))['Employee_Name'];

$pdetails = "SELECT Patient_Name,Gender,pr.Registration_ID,pr.Region,pr.District,pr.Country,TIMESTAMPDIFF(YEAR,DATE(Date_Of_Birth),CURDATE()) AS age,Guarantor_Name FROM tbl_patient_registration pr JOIN tbl_sponsor sp ON sp.Sponsor_ID=pr.Sponsor_ID  WHERE Registration_ID = '$Registration_ID'";
$pdetails_results = mysqli_query($conn,$pdetails) or die(mysqli_error($conn));
while ($pdetail = mysqli_fetch_assoc($pdetails_results)) {
    $Patient_Name = $pdetail['Patient_Name'];
    $Registration_ID = $pdetail['Registration_ID'];
    $Gender = $pdetail['Gender'];
    $age = $pdetail['age'];
    $Region = $pdetail['Region'];
    $District = $pdetail['District'];
    $Country = $pdetail['Country'];
    $Guarantor_Name = $pdetail['Guarantor_Name'];
}

$idetails = "SELECT Product_Name FROM tbl_items WHERE Item_ID = '$Item_ID'";
$idetails_results = mysqli_query($conn,$idetails) or die(mysqli_error($conn));
while ($idetail = mysqli_fetch_assoc($idetails_results)) {
    $Item_Name = $idetail['Product_Name'];
}

$sonodetails = "
	SELECT Employee_Name ,Employee_Title
		FROM 
		tbl_radiology_patient_tests rpt,
		tbl_employee em
		WHERE 
			rpt.Item_ID = '$Item_ID' AND Patient_Payment_Item_List_ID='" . $Patient_Payment_Item_List_ID . "' AND
			rpt.Registration_ID = '$Registration_ID' AND
			rpt.Sonographer_ID = em.Employee_ID
			";
$sonodetails_results = mysqli_query($conn,$sonodetails) or die(mysqli_error($conn));
while ($sonodetail = mysqli_fetch_assoc($sonodetails_results)) {
    $Sonographer = $sonodetail['Employee_Name'];
     $sonTtile=$sonodetail['Employee_Title'];
}

$radidetails = "
	SELECT Employee_Name,Employee_Title,Date_Time
		FROM 
		tbl_radiology_patient_tests rpt,
		tbl_employee em
		WHERE 
			rpt.Item_ID = '$Item_ID'  AND Patient_Payment_Item_List_ID='" . $Patient_Payment_Item_List_ID . "'  AND
			rpt.Registration_ID = '$Registration_ID' AND
			rpt.Radiologist_ID = em.Employee_ID
			";
$radidetails_results = mysqli_query($conn,$radidetails) or die(mysqli_error($conn));

$Date_Time='N/A';
while ($radidetail = mysqli_fetch_assoc($radidetails_results)) {
    $Radiologist = $radidetail['Employee_Name'];
    $radTtile=$radidetail['Employee_Title'];
    $Date_Time=$radidetail['Date_Time'];
}

if (isset($_GET['previewnly'])) {
    $select_patient_tests = "
		SELECT *
			FROM 
			tbl_radiology_discription rd,
			tbl_radiology_parameter rp
				WHERE
				rp.Parameter_ID = rd.Parameter_ID AND
				rd.Item_ID = '$Item_ID' AND
				rd.Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID' AND
				rd.Registration_ID = '$Registration_ID'	
                GROUP BY rp.Parameter_Name ORDER BY rp.Parameter_ID asc				
	";
    
} else {

    $select_patient_tests = "
		SELECT *
			FROM 
			tbl_radiology_discription rd,
			tbl_radiology_parameter rp
				WHERE
				rp.Parameter_ID = rd.Parameter_ID AND
				rd.Item_ID = '$Item_ID' AND
				rd.Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID' AND
				rd.Registration_ID = '$Registration_ID'	
                GROUP BY rp.Parameter_Name ORDER BY rp.Parameter_ID asc	";
    
    
}

 //echo $select_patient_tests;exit;

$select_patient_tests_results = mysqli_query($conn,$select_patient_tests) or die(mysqli_error($conn));

$pdf = '<table width="100%"  border="0"   class="nobordertable">
				<tr >
					<td colspan="6">
					<img src="./branchBanner/branchBanner.png">
					</td>
				</tr>
                <tr>
                    <td style="text-align: right;" width="10%"><b>Name:</b></td>
                    <td width="30%">' . $Patient_Name . '</td>
                    <td style="text-align: right;" width="20%"><b>Gender:</b></td>
                    <td>' . $Gender . '</td>
                    <td style="text-align: right;"><b>Reg #:</b></td>
                    <td  width="15%">' . $Registration_ID . '</td>
                <tr>
                    <td style="text-align: right;"><b>Sponsor:</b></td>
                    <td>' . $Guarantor_Name . '</td>
                    <td style="text-align: right;"><b>Country:</b></td>
                    <td>' . $Country . '</td>
                    <td style="text-align: right;"><b>Date:</b></td>
                    <td colspan="3">' . date('d, M Y',  strtotime($Date_Time)). '</td>
                </tr>
                <tr>
                    <td style="text-align: right;"><b>Age:</b></td>
                    <td>' . $age . ' years</td>
                    <td style="text-align: right;" ><b>Region:</b></td>
                    <td>' . $Region . '</td>
                    <td style="text-align: right;"><b>District:</b></b></td>
                    <td>' . $District . '</td>
                </tr>
                <tr >
                        <td style="text-align:center" colspan="6"><br/><b>RADIOLOGY REPORT FOR:</b> ' . strtoupper($Item_Name) . '</td>
                </tr>
            </table><br/>';



$Today_Date = mysqli_query($conn,"select now() as today");

while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}


$pdf .= "<table width='100%'  border='0'   class='nobordertable'>";
while ($ptests = mysqli_fetch_assoc($select_patient_tests_results)) {
    
    $parameter = $ptests['Parameter_Name'];
    $comment = $ptests['comments'];
    $pdf .= "<tr>";
    $pdf .= "<td style = 'padding-bottom:20px;'>";
    $pdf .= "<strong style = 'text-transform:uppercase;'>" .$parameter  .  "</strong><br />";
    $pdf .= $comment;
    $pdf .= "</td>";
    $pdf .= "</tr>";
}
$pdf .="<tr><td colspan = '2'><hr></td></tr>";
$pdf .= "</table>";

//echo $pdf;exit;
if(!empty($Employee_Doctor)){
    $pdf .="<tr><td>Ordered Doctor : <strong>".ucwords($Employee_Doctor)."</strong></td></tr>";
}

if(($sonTtile==$radTtile) && ( $Sonographer==$Radiologist)){
    $radTitle="". ucfirst(strtolower($sonTtile)).": <strong>" . $Sonographer . "</strong><br/>"; 

}else{
   $radTitle="". ucfirst(strtolower($sonTtile)).": <strong>" . $Sonographer . "</strong><br/>". ucfirst(strtolower($radTtile))." : <strong>" . $Radiologist . "</strong><br/>"; 

}

$pdf .= "<table style='width:100%'  border='0'   class='nobordertable'>
          <tr>
           <td>$radTitle</td>
           <td style='text-align: right;'><strong>PRINTING DATE: </strong>" . $Today . "</td>
          </tr>
         </table>
        ";

include("MPDF/mpdf.php");

$mpdf = new mPDF('', 'Letter');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($pdf, 2);

$mpdf->Output();
exit;
?>