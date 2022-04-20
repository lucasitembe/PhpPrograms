
<?php 
include("./includes/connection.php");
            //select patient details
            $patient_id = $_GET['patient_id'];
            $patient_name = $_GET['patient_name'];
            $instrunction_id = $_GET['instrunction_id'];

            $data.= '
            <center><img src="branchBanner/branchBanner.png" width="100%" ></center>
            <h3 style="text-align:center">Fluid Balance sheet of '.$patient_name.' for 24hours</h3>
            ';
                $select_patient = mysqli_query($conn,"SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID = '$patient_id'") or die(mysqli_error($conn));
                while($patient_row = mysqli_fetch_assoc($select_patient)){
                    $patient_name = $patient_row['Patient_Name'];
                }
    
                //die("SELECT Hospital_Ward_Name FROM tbl_admission as ad, tbl_hospital_ward as ward WHERE ad.Hospital_Ward_ID = ward.Hospital_Ward_ID AND Registration_ID = '$patient_id'");
    
                $select_patient_ward = mysqli_query($conn,"SELECT Hospital_Ward_Name FROM tbl_admission as ad, tbl_hospital_ward as ward WHERE ad.Hospital_Ward_ID = ward.Hospital_Ward_ID AND Registration_ID = '$patient_id'") or die(mysqli_error($conn));
                while($patient_ward_row = mysqli_fetch_assoc($select_patient_ward)){
                    $Hospital_Ward_Name = $patient_ward_row['Hospital_Ward_Name'];
                }
            $data.='
            <center><table class="tabel table-bordered" style="background-color:#fff;width:100%">
                <tr>
                    <th>Hosp. Reg Number:</th>
                    <td>'.$patient_id.'</td>
                    <th>Name:</th>
                    <td>'.$patient_name.'</td>
                    <th>Ward</th>
                    <td>'.$Hospital_Ward_Name.'</td>
                    <th>Date</th>
                    <td>'.date("Y-M-d").'</td>
                </tr>
            </table></center>
            <br>';

            $data.= '<table class="table table-bordered" style="width:100%; border:1px solid gray">
            <tr style="border:1px solid gray">
                <th style="border:1px solid gray" colspan="3">PRESCRIPTION</th>
                <th style="border:1px solid gray" colspan="5" >ORAL</th>
                <th style="border:1px solid gray" colspan="4">OTHER</th>
            </tr>
            <tr style="border:1px solid gray">
                <th style="border:1px solid gray" colspan="3"></th>
                <th style="border:1px solid gray" colspan="5">INTAKE</th>
                <th style="border:1px solid gray" colspan="4">OUTPUT</th>
            </tr>
            <tr style="border:1px solid gray">
                <th style="border:1px solid gray" rowspan="2">time</th>
                <th style="border:1px solid gray" colspan="2">Intravenous</th>
    
                <th style="border:1px solid gray" colspan="2">Intravenous</th>
                <th style="border:1px solid gray" colspan="2">Oral</th>
                <th style="border:1px solid gray" >Other</th>
    
                <th style="border:1px solid gray" >Urine</th>
                <th style="border:1px solid gray" >Gastr</th>
                <th style="border:1px solid gray" >Faeces</th>
                <th style="border:1px solid gray" >Other</th>
            </tr>
            <tr style="border:1px solid gray">
                <th style="border:1px solid gray" >Fluid type</th>
                <th style="border:1px solid gray" >ml</th>
    
                <th style="border:1px solid gray" >Fludi type</th>
                <th style="border:1px solid gray" >ml</th>
                <th style="border:1px solid gray" >fluid type</th>
                <th style="border:1px solid gray" >ml</th>
                <th style="border:1px solid gray" >ml</th>
    
                <th style="border:1px solid gray" >ml</th>
                <th style="border:1px solid gray">ml</th>
                <th style="border:1px solid gray" >ml</th>
                <th style="border:1px solid gray">ml</th>
            </tr>
            ';

                $selecy_data = mysqli_query($conn,"SELECT DATE(time_saved) as date_time FROM tbl_fluid_balance WHERE patient_id = '$patient_id' AND fluid_instruction_id = '$instrunction_id' GROUP BY DATE(time_saved) ORDER BY fluid_balance_id DESC") or die(mysqli_error($conn));
                while($date_row = mysqli_fetch_assoc($selecy_data)){
                 $date = $date_row['date_time'];
                $data.= '<tr><th colspan="12">Date : '. $date.'</th></tr>';
                $total_intake = 0;
                $total_output = 0;
                $total_fluid = 0;
                $select_fluid_sheet = mysqli_query($conn,"SELECT * FROM tbl_fluid_balance WHERE patient_id = '$patient_id' AND fluid_instruction_id = '$instrunction_id' AND DATE(time_saved) = '$date ' ") or die(mysqli_error($conn));
                //die("SELECT * FROM tbl_fluid_balance WHERE patient_id = '$patient_id'");
                 if((mysqli_num_rows($select_fluid_sheet))>0){
                     while($fluid_sheet_row = mysqli_fetch_assoc($select_fluid_sheet)){
                     $time = $fluid_sheet_row['time'];
                     $prescription_intervenous_fluid = $fluid_sheet_row['prescription_intervenous_fluid'];
                     $prescription_intervenous_fluid_amount = $fluid_sheet_row['prescription_intervenous_fluid_amount'];
                     $intake_intervenous_fluid = $fluid_sheet_row['intake_intervenous_fluid'];
                     $intake_intervenous_fluid_amount = $fluid_sheet_row['intake_intervenous_fluid_amount'];
                     $intake_oral_fluid = $fluid_sheet_row['intake_oral_fluid'];
                     $intake_oral_fluid_amount = $fluid_sheet_row['intake_oral_fluid_amount'];
                     $intake_other_amount = $fluid_sheet_row['intake_other_amount'];
                     $urine_amount = $fluid_sheet_row['urine_amount'];
                     $gastr_amount = $fluid_sheet_row['gastr_amount'];
                     $faeces = $fluid_sheet_row['faeces'];
                     $other = $fluid_sheet_row['other'];
                     $data.= '
                     <tr>
                        <td>'.$time.'</td>
                        <td>'.$prescription_intervenous_fluid.'</td>
                        <td>'.$prescription_intervenous_fluid_amount.'</td>
                        <td>'.$intake_intervenous_fluid.'</td>
                        <td>'.$intake_intervenous_fluid_amount.'</td>
                        <td>'.$intake_oral_fluid.'</td>
                        <td>'.$intake_oral_fluid_amount.'</td>
                        <td>'.$intake_other_amount.'</td>
                        <td>'.$urine_amount.'</td>
                        <td>'.$gastr_amount.'</td>
                        <td>'.$faeces.'</td>
                        <td>'.$other.'</td>
                    </tr>
                     ';
                     $total_intake = $total_intake + $intake_intervenous_fluid_amount + $intake_oral_fluid_amount + $intake_other_amount;
                     $total_output = $total_output + $urine_amount + $gastr_amount + $faeces + $other;
                     $total_fluid = $total_fluid + $prescription_intervenous_fluid_amount;
                     }
                     $data.= '
                     <tr style="background-color:gray">
                        <th colspan="3" >Total Fluid 24hr = '.$total_fluid.' mls</th>
                        <th colspan="5" >Total Intake 24hr = '.$total_intake.' ml</th>
                        <th colspan="4" >Total Output 24hr = '.$total_output.' ml</th>  
                     </tr>
                  ';
                }
            }
            $data.= '</table>';

            include("MPDF/mpdf.php");
            $mpdf = new mPDF('', 'A4');
            $mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
            $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
            // LOAD a stylesheet
            $stylesheet = file_get_contents('patient_file.css');
            $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
            $mpdf->WriteHTML($data, 2);

            $mpdf->Output('mpdf.pdf','I');


?>