<?php 
include("./includes/connection.php"); 
$instrunction_id = $_POST['instrunction_id'];
$patient_id =$_POST['patient_id'];
$patient_name = $_POST['patient_name'];
echo '
<form action="" id="formId">
<input type="text" id="instrunction_id" style="border-radius:0px; display:none" value="'.$instrunction_id.'">
<table class="table table-bordered">
    <tr>
        <th colspan="3">PRESCRIPTION</th>
        <th colspan="5" >ORAL</th>
        <th colspan="4">OTHER</th>
    </tr>
    <tr>
        <td colspan="3"><lable>Fluid to be given<input type="text" id="prescription_fluid" style="border-radius:0px"></lable> </td>
        <td colspan="3"><lable>Type of fluid <input type="text" id="oral_fluid" style="border-radius:0px"></lable> </td>
        <td colspan="2"><lable>Amount <input type="text" id="oral_fluid_amount" style="border-radius:0px"></lable> </td>

        <td colspan="3"><lable>Type of fluid <input type="text" id="other_fluid" style="border-radius:0px"></lable> </td>
        <td colspan="2"><lable>Amount <input type="text" id="other_amount" style="border-radius:0px"></lable> </td>
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
    <tr>
        <td><select style="width:100%" name="" id="time">
        <option value="">timeee</option>
            <option value="8am">8am</option>
            <option value="9am">9am</option>
            <option value="10am">10am</option>
            <option value="11am">11am</option>
            <option value="noon">noon</option>
            <option value="1pm">1pm</option>
            <option value="2pm">2pm</option>
            <option value="3pm">3pm</option>
            <option value="4pm">4pm</option>
            <option value="5pm">5pm</option>
            <option value="6pm">6pm</option>
            <option value="7pm">7pm</option>
            <option value="8pm">8pm</option>
            <option value="9pm">9pm</option>
            <option value="10pm">10pm</option>
            <option value="11pm">11pm</option>
            <option value="mn">mn</option>
            <option value="1am">1am</option>
            <option value="2am">2am</option>
            <option value="3am">3am</option>
            <option value="4am">4am</option>
            <option value="5am">5am</option>
            <option value="6am">6am</option>
            <option value="7am">7am</option>
        </select> </td>
        <td><input type="text" id="prescription_intervenous_fluid" style="border-radius:0px"> 
            <input type="text" id="patient_name" value="<?php echo $patient_name?>" hidden>
        </td>
        <td><input type="text" id="prescription_intervenous_fluid_amount" style="border-radius:0px"> </td>
        <td><input type="text" id="intake_intervenous_fluid" style="border-radius:0px"> </td>
        <td><input type="text" id="intake_intervenous_fluid_amount" style="border-radius:0px"> </td>
        <td><input type="text" id="intake_oral_fluid" style="border-radius:0px"> </td>
        <td><input type="text" id="intake_oral_fluid_amount" style="border-radius:0px"> </td>
        <td><input type="text" id="intake_other_amount" style="border-radius:0px"> </td>
        <td><input type="text" id="urine_amount" style="border-radius:0px"> </td>
        <td><input type="text" id="gastr_amount" style="border-radius:0px"> </td>
        <td><input type="text" id="faeces" style="border-radius:0px"> </td>
        <td><input type="text" id="other" style="border-radius:0px"> </td>
    </tr>
    <tr><td colspan="11"><input class="art-button-green" onclick="save_fluid_balance_info('.$patient_id.','.$patient_id.')" type="button" value="Save"><td></tr>
</table>
</center>
</form>';

echo '<br>
<center><legend>Fluid Balance sheet of '.$patient_name.' for 24 hours</legend></center>
<div id="fluid_balance_sheet">
<table class="table table-bordered" style="background-color:#fff">
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
    </tr>';
       
        //die("SELECT DATE(time_saved) FROM tbl_fluid_balance WHERE patient_id = '$patient_id' AND fluid_instruction_id = '$instrunction_id' GROUP BY DATE(time_saved)");
        $selecy_data = mysqli_query($conn,"SELECT DATE(time_saved) as date_time FROM tbl_fluid_balance WHERE patient_id = '$patient_id' AND fluid_instruction_id = '$instrunction_id' GROUP BY DATE(time_saved) ORDER BY fluid_balance_id DESC") or die(mysqli_error($conn));
        while($date_row = mysqli_fetch_assoc($selecy_data)){
            $date = $date_row['date_time'];
            echo '<tr><th colspan="12">Date : '. $date.'</th></tr>';
            $total_intake = 0;
            $total_output = 0;
            $total_fluid = 0;
         $select_fluid_sheet = mysqli_query($conn,"SELECT * FROM tbl_fluid_balance WHERE patient_id = '$patient_id' AND fluid_instruction_id = '$instrunction_id' AND DATE(time_saved) = '$date ' ") or die(mysqli_error($conn));
         //die("SELECT instrunction FROM tbl_fluid_balance WHERE patient_id = '$patient_id'");
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
             $time_saved = $fluid_sheet_row['time_saved'];

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
                <tr style="background-color:gray; text-align:left">
                  <th colspan="3" >Total Fluid 24hr = '.$total_fluid.' mls</th>
                  <th colspan="5" >Total Intake 24hr = '.$total_intake.' ml</th>
                  <th colspan="4" >Total Output 24hr = '.$total_output.' ml</th>  
                </tr>
             ';
         }
        }
        echo '<tr> <td colspan="12"> <a target="_blank" href="print_balance_sheet_pdf.php?patient_id='.$patient_id .'&&patient_name='.$patient_name .'&&instrunction_id='.$instrunction_id .'"><input type="button" class="art-button-green" value="Preview Pdf"></a> </td> </tr>
         ';
echo '</table>
</div>';

?>