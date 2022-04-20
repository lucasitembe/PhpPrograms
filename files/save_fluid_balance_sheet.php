<?php 
include("./includes/connection.php");
    $prescription_fluid = $_POST['prescription_fluid'];
    $oral_fluid = $_POST['oral_fluid'];
    $oral_fluid_amount = $_POST['oral_fluid_amount'];
    $other_fluid = $_POST['other_fluid'];
    $other_amount = $_POST['other_amount'];
    $time = $_POST['time'];
    $prescription_intervenous_fluid = $_POST['prescription_intervenous_fluid'];
    $prescription_intervenous_fluid_amount = $_POST['prescription_intervenous_fluid_amount'];
    $intake_intervenous_fluid = $_POST['intake_intervenous_fluid'];
    $intake_intervenous_fluid_amount = $_POST['intake_intervenous_fluid_amount'];
    $intake_oral_fluid = $_POST['intake_oral_fluid'];
    $intake_oral_fluid_amount = $_POST['intake_oral_fluid_amount'];
    $intake_other_amount = $_POST['intake_other_amount'];
    $urine_amount = $_POST['urine_amount'];
    $gastr_amount = $_POST['gastr_amount'];
    $faeces = $_POST['faeces'];
    $other = $_POST['other'];
    $emp_id = $_POST['emp_id'];
    $patient_id = $_POST['patient_id'];
    $instruction_id = $_POST['instruction_id'];
    $patient_name = $_POST['patient_name'];
    $update_sheet = "
    INSERT INTO `tbl_fluid_balance`(`patient_id`, `fluid_instruction_id`, `nurse_id`, `prescription_fluid`, `oral_fluid`, 
    `oral_fluid_amount`, `other_fluid`, `other_amount`, `time`, `prescription_intervenous_fluid`, `prescription_intervenous_fluid_amount`, 
    `intake_intervenous_fluid`, `intake_intervenous_fluid_amount`, `intake_oral_fluid`, `intake_oral_fluid_amount`, `intake_other_amount`, 
    `urine_amount`, `gastr_amount`, `faeces`, `other`)
     VALUES ('$patient_id','$instruction_id','$emp_id','$prescription_fluid','$oral_fluid','$oral_fluid_amount','$other_fluid',
     '$other_amount','$time','$prescription_intervenous_fluid','$prescription_intervenous_fluid_amount','$intake_intervenous_fluid'
     ,'$intake_intervenous_fluid_amount','$intake_oral_fluid','$intake_oral_fluid_amount','$intake_other_amount','$urine_amount',
     '$gastr_amount','$faeces','$other')
    ";

    $update_done = mysqli_query($conn,$update_sheet) or die(mysqli_error($conn));
    if($update_done){
       
       echo '<table class="table table-bordered" style="background-color:#fff">
        <tr>
            <th colspan="3">PRESCRIPTION</th>
            <th colspan="5" >ORAL</th>
            <th colspan="4">OTHER</th>
        </tr>
        <tr>
            <th colspan="3"></th>
            <th colspan="5">INTAKE</th>
            <th colspan="4">OUTPUT</th>
        </tr>
        <tr>
            <th rowspan="2">time</th>
            <th colspan="2">Intravenous</th>

            <th colspan="2">Intravenous</th>
            <th colspan="2">Oral</th>
            <th>Other</th>

            <th>Urine</th>
            <th>Gastr</th>
            <th>Faeces</th>
            <th>Other</th>
        </tr>
        <tr>
            <th>Fluid type</th>
            <th>ml</th>

            <th>Fludi type</th>
            <th>ml</th>
            <th>fluid type</th>
            <th>ml</th>
            <th>ml</th>

            <th>ml</th>
            <th>ml</th>
            <th>ml</th>
            <th>ml</th>
        </tr>
        ';

            $selecy_data = mysqli_query($conn,"SELECT DATE(time_saved) as date_time FROM tbl_fluid_balance WHERE patient_id = '$patient_id' AND fluid_instruction_id = '$instruction_id' GROUP BY DATE(time_saved) ORDER BY fluid_balance_id DESC") or die(mysqli_error($conn));
            while($date_row = mysqli_fetch_assoc($selecy_data)){
                $date = $date_row['date_time'];
                echo '<tr><th colspan="12">Date : '. $date.'</th></tr>';
                $total_intake = 0;
                $total_output = 0;
                $total_fluid = 0;
                $select_fluid_sheet = mysqli_query($conn,"SELECT * FROM tbl_fluid_balance WHERE patient_id = '$patient_id' AND fluid_instruction_id = '$instruction_id' AND DATE(time_saved) = '$date ' ") or die(mysqli_error($conn));             //die("SELECT instrunction FROM tbl_fluid_balance WHERE patient_id = '$patient_id'");
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
                 echo '
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
                 echo '
                 <tr style="background-color:gray">
                 <th colspan="3" >Total Fluid 24hr = '.$total_fluid.' mls</th>
                   <th colspan="5" >Total Intake 24hr = '.$total_intake.' ml</th>
                   <th colspan="4" >Total Output 24hr = '.$total_output.' ml</th>  
                 </tr>
              ';
             }
            }
            echo '<tr> <td colspan="12"> <a target="_blank" href="print_balance_sheet_pdf.php?patient_id='.$patient_id .'&&patient_name='.$patient_name .'&&instrunction_id='.$instruction_id.'"><input type="button" class="art-button-green" value="Preview Pdf"></a> </td> </tr>
             '; 
    echo '</table>';


    }
?>