<?php
include("./includes/connection.php");

$Date_From = ''; //@$_POST['Date_From'];
$Date_To = ''; //@$_POST['Date_To'];

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}
if (isset($_GET['start_date'])) {
    $start_date = $_GET['start_date'];
}
if (isset($_GET['end_date'])) {
    $end_date = $_GET['end_date'];
}
?>
<br/><br/>


        <?php
        $htm = "<table width ='100%' class='nobordertable'>
        		    <tr><td style='text-align:center'>
        			<img src='./branchBanner/branchBanner.png' width='100%'>
        		    </td></tr>
        		    <tr><td style='text-align: center;font-size:20;'><span><b>PATIENT RESULT SUMMARY</b></span></td></tr>
                </table><br/>";
        $htm .= '<center> <table width="100%" style="background-color:#fff;">';
        //run the query to select all data from the database according to the branch id
        $select_doctor_query = "SELECT * FROM `tbl_patient_result_summary` WHERE `Patient_reg`='$Registration_ID ' and Attendance_Date between '$start_date' and  '$end_date' GROUP by `Attendance_Date` order by result_summary_ID ASC";
        $select_doctor_result = mysqli_query($conn,$select_doctor_query) or die(mysqli_error($conn));

        while ($select_doctor_row = mysqli_fetch_array($select_doctor_result)) {
            $htm .= '<tr><td style="font-weight:bold;text-align:center;font-size:18;" colspan="2">RESULT DATE: '.$select_doctor_row["Attendance_Date"].'</td></tr>';
          $htm .= '<tr><td style="font-weight:bold;font-size:16;">HEMATOLOGY</td></tr>
            <tr>
                <td style="font-size:15;margin-right:5px;" width="20%">WCC (3.5-9.0 X 10^9/L) </td>
                <td>'.select_doctor_row["wcc"].'</td>
            </tr>
            <tr>
                <td style="font-size:15;margin-right:5px;" width="20%">HB (12M - 16 g/L)</td>
                <td>'.$select_doctor_row["hb"].'</td>
            </tr>
            <tr>
                <td style="font-size:15;margin-right:5px;" width="20%">MCV (85-105 fL) </td>
                <td>'.$select_doctor_row["mcv"].'</td>
            </tr>
            <tr>
                <td style="font-size:15;margin-right:5px;" width="20%">Platelets (150 - 450 x 10^9/L)</td>
                <td>'.$select_doctor_row["platelets"].'</td>
            </tr>
            <tr>
                <td style="font-size:15;margin-right:5px;" width="20%">ESR ( <20 mmhr)</td>
                <td>'.$select_doctor_row["esr"].'</td>
          </tr>
          <tr><td style="font-weight:bold;font-size:16;">BIOCHEMISTRY</td></tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Na (136 - 148 mmol/L)</td>
            <td>'.$select_doctor_row["na"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">K (3.8 - 5.0 mml/L)</td>
            <td>'.$select_doctor_row["k"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">PRE Urea ( 2.1 - 7.1 mmol/L)</td>
            <td>'.$select_doctor_row["pre_urea"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">PRE Creatinine (60 - 120 mol/L</td>
            <td>'.$select_doctor_row["pre_creatinine"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">URR</td>
            <td>'.$select_doctor_row["urr"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">POST Urea (2.1 - 7.1 mmol/L)</td>
            <td>'.$select_doctor_row["post_urea"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">POST Creatinine (60 - 120 mmol/l)</td>
            <td>'.$select_doctor_row["post_creatinine"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Glucose (plasm) (3.5-6.5 mol/L)</td>
            <td>'.$select_doctor_row["glucose"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">AST (<35 U/L)/ ATL (<45 U/L)</td>
            <td>'.$select_doctor_row["ast"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">ALP (50 - 150 U/L) / GGT (<40 U/L)</td>
            <td>'.$select_doctor_row["alp"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Total Bill (<20 Umol/L)</td>
            <td>'.$select_doctor_row["total_bill"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Total Protein (60 - 80 g/l)</td>
            <td>'.$select_doctor_row["total_protein"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Albumin (35 - 55 g/l)</td>
            <td>'.$select_doctor_row["albumin"].'</td>
        </tr>
        <tr><td style="font-weight:bold;font-size:16;">OTHER</td></tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Calcium (* adjusted)</td>
            <td>'.$select_doctor_row["calcium"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Phosphate</td>
            <td>'.$select_doctor_row["phosphate"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">PTH</td>
            <td>'.$select_doctor_row["pth"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Ferritin</td>
            <td>'.$select_doctor_row["ferritin"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Iron</td>
            <td>'.$select_doctor_row["iron"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Transferrin saturation</td>
            <td>'.$select_doctor_row["transferrin_saturation"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Serology</td>
            <td>'.$select_doctor_row["serology"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">HIV Test</td>
            <td>'.$select_doctor_row["hiv_test"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Hepatitis B</td>
            <td>'.$select_doctor_row["hepatitis_b"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Hepatitis C</td>
            <td>'.$select_doctor_row["hepatitis_c"].'</td>
        </tr>
        <tr><td style="font-weight:bold;font-size:16;">ADEQUACY</td></tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">URR</td>
            <td>'.$select_doctor_row["urr2"].'</td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">KT/V</td>
            <td>'.$select_doctor_row["ktv"].'</td>
        </tr>';

        }
          $htm .= "</table>";
        include("MPDF/mpdf.php");

        $mpdf = new mPDF('s', 'A4-L');
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
        // LOAD a stylesheet
        $stylesheet = file_get_contents('patient_file.css');
        $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
        $mpdf->WriteHTML($htm, 2);
        $mpdf->Output();
        ?>
