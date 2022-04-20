<?php

session_start();
include("./includes/connection.php");
$data = '';
if ($_GET['patient_id'] != '') {
    $Registration_ID = $_GET['patient_id'];
} else {
    $Registration_ID = '';
}

$data = "<table width ='100%' height = '30px'  border='0'   class='nobordertable'>
            <tr>
                <td colspan='4'>
                <img src='./branchBanner/branchBanner.png' width='100%'>
                </td>
            </tr>";
$query = mysqli_query($conn,"SELECT `Patient_Name`,`Registration_ID`,`Gender`,`Phone_Number` FROM `tbl_patient_registration` WHERE `Registration_ID`='$Registration_ID'") or die(mysqli_error($conn));
if(mysqli_num_rows($query)>0){
    while($rows = mysqli_fetch_assoc($query)){
        $Patient_Name = $rows['Patient_Name'];
        $Registration_ID = $rows['Registration_ID'];
        $Gender = $rows['Gender'];
        $Phone_Number = $rows['Phone_Number'];
        $data .= "<tr>
                    <td style='text-align: right;'>Patient Name: </td>
                    <td>$Patient_Name</td>
                    </tr>
                    <tr>
                    <td style='text-align: right;'>Reg No.: </td>
                    <td>$Registration_ID</td>
                    </tr>
                    <tr>
                    <td style='text-align: right;'>Gender: </td>
                    <td>$Gender</td>
                    </tr>
                    <tr>
                    <td style='text-align: right;'>Phone Number:</td>
                    <td>$Phone_Number</td>
                </tr>";
    }
}     

$data .= "<tr><td colspan='4'><hr style='width:100%;margin-top:-0.5%'/></td></tr>";


$select_nurse = mysqli_query($conn, "SELECT Nurse_ID, Nurse_DateTime, bmi, nurse_comment, Special_Condition, modeoftransprot, nurse_comment, accomaniedby, pastmedhist, current_medications, Allegies, em.Employee_Name AS Nurse FROM tbl_nurse n, tbl_employee em WHERE n.Registration_ID = '$Registration_ID' AND em.Employee_ID = n.Employee_ID ORDER BY Nurse_ID DESC LIMIT 4");

if(mysqli_num_rows($select_nurse)>0){
    while($nurse = mysqli_fetch_assoc($select_nurse)){
        $Nurse_ID = $nurse['Nurse_ID'];
        $bmi = $nurse['bmi'];
        $nurse_comment = $nurse['nurse_comment'];
        $Special_Condition = $nurse['Special_Condition'];
        $modeoftransprot = $nurse['modeoftransprot'];
        $nurse_comment = $nurse['nurse_comment'];
        $accomaniedby = $nurse['accomaniedby'];
        $pastmedhist = $nurse['pastmedhist'];
        $current_medications = $nurse['current_medications'];
        $Allegies = $nurse['Allegies'];
        $Nurse_served = $nurse['Nurse'];
        $Nurse_DateTime = $nurse['Nurse_DateTime'];

        //    $value = date("l jS \of F Y",strtotime($time_given1)); 
        $sql_select_item_sub_category_result=mysqli_query($conn,"SELECT v.Vital, nv.Vital_Value FROM tbl_vital v,tbl_nurse_vital nv WHERE v.Vital_ID = nv.Vital_ID AND nv.Nurse_ID = '$Nurse_ID'") or die(mysqli_error($conn));
    
        if(mysqli_num_rows($sql_select_item_sub_category_result)>0){
            $data .="<tr>
            <td style='text-align: right;'>Processed Date : <b>".$Nurse_DateTime."</b> <td style='text-align: left;'>Processed By : <b>".$Nurse_served."</b></td>
            <tr><td colspan='4'><hr style='width:100%'/></td>
    
        </tr>";
            while($rows = mysqli_fetch_assoc($sql_select_item_sub_category_result)){
                $vitalName = $rows['Vital'];
                $vitalValue = $rows['Vital_Value'];
                // $bmi= $rows['bmi'];
                // $Allegies= $rows['Allegies'];
                // $Special_Condition= $rows['Special_Condition'];
                // $modeoftransprot= $rows['modeoftransprot'];
                // $nurse_comment= $rows['nurse_comment'];
                // $accomaniedby= $rows['accomaniedby'];
                // $pastmedhist= $rows['pastmedhist'];
                // $current_medications= $rows['current_medications'];

                $data .= "<tr>
                            <td style='text-align: right;'>$vitalName:</td>
                            <td style='text-align: left;'>$vitalValue:</td>
                        </tr>";
                        
            }
                $data .="<tr><td style='text-align: right;'>BMI:</td><td style='text-align: left;'>$bmi:</td></tr>";
                $data .="<tr><td style='text-align: right;'>Allegies:</td><td style='text-align: left;'>$Allegies:</td></tr>";
                $data .="<tr><td style='text-align: right;'>Special Condition:</td><td style='text-align: left;'>$Special_Condition:</td></tr>";
                $data .="<tr><td style='text-align: right;'>Mode Of Transprot:</td><td style='text-align: left;'>$modeoftransprot:</td></tr>";
                $data .="<tr><td style='text-align: right;'>Nurse Comment:</td><td style='text-align: left;'>$nurse_comment:</td></tr>";
                $data .="<tr><td style='text-align: right;'>Accomanied By:</td><td style='text-align: left;'>$accomaniedby:</td></tr>";
                $data .="<tr><td style='text-align: right;'>Past Medical History:</td><td style='text-align: left;'>$pastmedhist:</td></tr>";
                $data .="<tr><td style='text-align: right;'>Current Medications:</td><td style='text-align: left;'>$current_medications:</td></tr>";
                $data .="<tr><td colspan='4'><hr style='width:100%'/></td></tr><br>";
        }
     }
}

$data .="</table>";

include("./MPDF/mpdf.php"); 
$mpdf = new mPDF('s', 'Letter');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$mpdf->SetFooter('Printed By ' . strtoupper($_SESSION['userinfo']['Employee_Name']) . '|Page {PAGENO} of {nb}|{DATE d-m-Y}');
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);

//$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
