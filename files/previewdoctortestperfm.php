<style>
    .todialog{
        display: block;
        color: #2182B0;
    }
    .todialog:hover{
        cursor: pointer;
        text-decoration:underline 
    }
</style>
<?php
@session_start();
include("./includes/connection.php");

//$filter = "   AND Admission_Status='Admitted'  AND DATE(ad.Admission_Date_Time)=DATE(NOW())";

$Date_From = filter_input(INPUT_GET, 'Date_From');
$Date_To = filter_input(INPUT_GET, 'Date_To');
$testNameResults = filter_input(INPUT_GET, 'testNameResults');
$Sponsor = filter_input(INPUT_GET, 'Sponsor');
$employee_id= filter_input(INPUT_GET, 'employee_id');
$check_in_type= filter_input(INPUT_GET, 'check_in_type');

$filter = "   AND DATE(il.Transaction_Date_And_Time)=DATE(NOW())";

$daterange= date('d, M Y');
 

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND  il.Transaction_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "' ";
    $daterange= 'FROM '.date('d, M Y', strtotime($Date_From)).' TO '.date('d, M Y', strtotime($Date_To));
}

if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND sp.Sponsor_ID=$Sponsor";
}
//$data .= $ward;exit;
if (!empty($employee_id) && $employee_id != 'All') {
    $filter .= " AND il.Consultant_ID  = $employee_id";
}


if (!empty($testNameResults)) {
    $filter .="  AND il.Item_ID='$testNameResults'";
}

$testName= mysqli_fetch_assoc(mysqli_query($conn,"SELECT Product_Name FROM tbl_items WHERE Item_ID='$testNameResults'"))['Product_Name'];

//$data .= $filter;
//exit();

//GET BRANCH ID

$data= '<img src="branchBanner/branchBanner.png" width="100%" >';

$data .='<div style="padding:5px; width:99%;font-size:small;border:0px solid #000;text-align:center  ">
            <b align="center">
                DOCTOR\'S '. strtoupper($check_in_type).' TEST PERFORMANCE SUMMERY</b>
              <p>
                 <b align="center"> '.$daterange.'</b>
               </p>  
               <p>
                 <b align="center"> '.$testName.'</b>
               </p> 
            
        </div>';

$data .= '<center><table style="width:100%" border="1" id="doctosTestResult">';
$data .= "  <tr >
                <th style='text-align:left;width:5%;font-size:xx-small'>SN</th>
                <th style='text-align:left;font-size:xx-small'><b>DOCTORS</b></th>
                <th style='width:20%;font-size:xx-small'><b>NUMBER OF TESTS</b></th>
             </tr>";

$sql= "
        SELECT em.Employee_ID,em.Employee_Name FROM tbl_item_list_cache il 
            JOIN tbl_payment_cache pc ON il.Payment_Cache_ID=pc.Payment_Cache_ID
            JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
            JOIN tbl_sponsor AS sp ON sp.Sponsor_ID =pr.Sponsor_ID
            JOIN tbl_employee em ON em.Employee_ID=il.Consultant_ID
            WHERE Employee_Type='Doctor' AND Check_In_Type='$check_in_type' $filter  GROUP BY il.Consultant_ID
        ";

//$data .= $sql;exit;

$select_Filtered_Patients = mysqli_query($conn,$sql) or die(mysqli_error($conn));
$sn = 1;
while ($row = mysqli_fetch_array($select_Filtered_Patients)) {
    $Employee_ID=$row['Employee_ID'];
    $Employee_Name=$row['Employee_Name'];
    
    $currentEmployee="currEmployeeID=".$Employee_ID.'&'.$_SERVER['QUERY_STRING'];
    
    $sqlItems=  mysqli_query($conn,"
            SELECT count(item_ID) as Number_Of_Test FROM `tbl_item_list_cache` il 
            JOIN tbl_payment_cache pc ON il.Payment_Cache_ID=pc.Payment_Cache_ID
            JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
            JOIN tbl_sponsor AS sp ON sp.Sponsor_ID =pr.Sponsor_ID
            WHERE `Consultant_ID`='$Employee_ID' and 
            Check_In_Type='$check_in_type' $filter
            ") or die(mysqli_error($conn));
    $Number_Of_Test=  mysqli_fetch_assoc($sqlItems)['Number_Of_Test'];
    
    $location='';
    if($check_in_type=='Radiology'){
        $location='';
    }
     $data .= '<tr>';
     $data .= '<td style="font-size:xx-small">'.$sn++.'</td>';
     $data .= "<td style='font-size:xx-small'><span class='todialog' onclick='showDetails(\"".$currentEmployee."\",\"".$Employee_Name."\")'>".$Employee_Name."</span></td>";
     $data .= "<td style='text-align:center;font-size:xx-small'><span class='todialog' onclick='showDetails(\"".$currentEmployee."\")'>".number_format($Number_Of_Test)."</span></td>";
     $data .= '</tr>';
     
    ?>
    <?php
} $data .= "
   </table>
  </center>";


include("MPDF/mpdf.php");
$mpdf = new mPDF('', 'Letter');

$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);

$mpdf->Output();