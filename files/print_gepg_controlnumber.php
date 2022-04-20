<?php

include("./includes/connection.php");
$Registration_ID = $_GET['RI'];
$controlNumber = $_GET['cnumber'];


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


$Date_Time='N/A';

 //echo $select_patient_tests;exit;

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
                    <td colspan="3">' . date('d, M Y',  strtotime($Date_Time)) . '</td>
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
                        <td style="text-align:center" colspan="6"><br/><b>Control Number:</b> ' . $controlNumber . '</td>
                </tr>
            </table><br/>';

$Today_Date = mysqli_query($conn,"select now() as today");

while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}

$pdf .= "<table style='width:100%'  border='0'   class='nobordertable'>
          <tr>
           <td></td>
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