<?php

include("./includes/connection.php");
$Registration_ID = $_GET['RI'];
$Item_ID = $_GET['II'];
$Patient_Payment_Item_List_ID = $_GET['PPILI'];

$datastring = $_GET['datastring'];

$Registration_ID = $regid = $_GET['regid'];
$result = array();

$resultLocation = 'Outside';


if (isset($_GET['src']) && $_GET['src'] == 'inpat') { //src=inpat admID, consID
    $consID = $_GET['consID'];
    $getIDs = "SELECT Payment_Item_Cache_List_ID,ilc.Item_ID FROM tbl_item_list_cache ilc
           JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID 
           JOIN tbl_radiology_patient_tests rpt ON ilc.Payment_Item_Cache_List_ID=rpt.Patient_Payment_Item_List_ID
           WHERE pc.consultation_id ='" . $consID . "' and rpt.status='done'
          ";
} else {
    $ppilid = $_GET['ppilid'];
    $getIDs = "SELECT Payment_Item_Cache_List_ID,ilc.Item_ID FROM tbl_item_list_cache ilc
           JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID 
           JOIN  tbl_consultation c ON c.consultation_ID=pc.consultation_id 
           JOIN tbl_radiology_patient_tests rpt ON ilc.Payment_Item_Cache_List_ID=rpt.Patient_Payment_Item_List_ID
           WHERE c.Patient_Payment_Item_List_ID='" . $ppilid . "' and rpt.status='done'
          ";
}

$qrResults = mysqli_query($conn,$getIDs) or die(mysqli_error($conn));


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

$pdf = " <table width ='100%' height = '30px' class='nobordertable'>
		<tr>
		    <td>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td>
		</tr>
		<tr>
		    <td style='text-align: center;'><b>PATIENT RAD TEST RESULTS</b></td>
		</tr>
               
         </table>
        ";

$pdf.= '<table width="100%"  border="0"   class="nobordertable">
                <tr>
                    <td style="text-align: right;"><b>Name:</b></td>
                    <td width="30%">' . $Patient_Name . '</td>
                     
                    <td style="text-align: right;"><b>Reg #:</b></td>
                    <td  width="15%">' . $Registration_ID . '</td>
                    <td style="text-align: right;" width="20%"><b>Gender:</b></td>
                    <td>' . $Gender . '</td>
                </tr>
               
                <tr>
                    <td style="text-align: right;"><b>Age:</b></td>
                    <td>' . $age . ' years</td>
                    <td style="text-align: right;" ><b>Region:</b></td>
                    <td>' . $Region . '</td>
                    <td style="text-align: right;"><b>District:</b></b></td>
                    <td>' . $District . '</td>
                </tr>

            </table><br/>';

while ($rowIds = mysqli_fetch_array($qrResults)) {
    $Patient_Payment_Item_List_ID = $rowIds['Payment_Item_Cache_List_ID'];
    $Item_ID = $rowIds['Item_ID'];


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
        $sonTtile = $sonodetail['Employee_Title'];
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

    $Date_Time = 'N/A';
    while ($radidetail = mysqli_fetch_assoc($radidetails_results)) {
        $Radiologist = $radidetail['Employee_Name'];
        $radTtile = $radidetail['Employee_Title'];
        $Date_Time = $radidetail['Date_Time'];
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
                ORDER BY rp.Parameter_ID asc				
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
                ORDER BY rp.Parameter_ID asc	";
    }

    //echo $select_patient_tests;exit;

    $select_patient_tests_results = mysqli_query($conn,$select_patient_tests) or die(mysqli_error($conn));

    $pdf .= '<table width="100%"  border="0"   class="nobordertable">
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
        $pdf .= "<strong style = 'text-transform:uppercase;'>" . $parameter . "</strong><br />";
        $pdf .= $comment;
        $pdf .= "</td>";
        $pdf .= "</tr>";
    }
    $pdf .="<tr><td colspan = '2'><hr></td></tr>";
    $pdf .= "</table>";

//echo $pdf;exit;

    if (($sonTtile == $radTtile) && ( $Sonographer == $Radiologist)) {
        $radTitle = "" . ucfirst(strtolower($sonTtile)) . ": <strong>" . $Sonographer . "</strong><br/>";
    } else {
        $radTitle = "" . ucfirst(strtolower($sonTtile)) . ": <strong>" . $Sonographer . "</strong><br/>" . ucfirst(strtolower($radTtile)) . " : <strong>" . $Radiologist . "</strong><br/>";
    }

    $pdf .= "<table style='width:100%'  border='0'   class='nobordertable'>
          <tr>
           <td>$radTitle</td>
          </tr>
         </table><br/>
         <hr/>
         <br/><br/>
        ";
}

include("MPDF/mpdf.php");

$mpdf = new mPDF('', 'Letter');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($pdf, 2);

$mpdf->Output();
exit;
?>