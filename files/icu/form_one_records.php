
<?php 
    include '../includes/connection.php';

    # perfom counts inputs
    function perform_counts($value_count){
        for($input_counts = 0; $input_counts < 25 ;$input_counts ++){
            echo '<td>'.$value_count[$input_counts].'</td>';
        }
    }

    function perform_count($value_count,$condition){
        if($condition == "first"){
            for($input_counts = 0; $input_counts < 25 ;$input_counts ++){
                echo '<td>'.$value_count[$input_counts].'</td>';
            }
        }else{
            for($input_counts = 25; $input_counts < 50 ;$input_counts ++){
                echo '<td>'.$value_count[$input_counts].'</td>';
            }
        }
    }

    $var_counts = 7;
    
    # variables
    $form_one = $_POST['form_one'];
    $patient_registration_id = $_POST['patient_registration_id'];

    # Select Reecord Query
    $get_record_query = mysqli_query($conn,"SELECT * FROM tbl_icu_form_one WHERE registration_id = '$patient_registration_id'") or die(mysqli_error($conn));

    if(mysqli_num_rows($get_record_query) > 0){
        while($get_rows = mysqli_fetch_assoc($get_record_query)){
            $employee_id = $get_rows['employee_id'];
            $date = $get_rows['date'];
            $details = explode(',',$get_rows['details']);
            $sas_toString = explode(',',$get_rows['sas_toString']);
            $score = explode(',',$get_rows['score']);
            $blood_pressure_toString = explode(',',$get_rows['blood_pressure_toString']);
            $x_systolic_toString = explode(',',$get_rows['x_systolic_toString']);
            $one_180_toString = explode(',',$get_rows['one_180_toString']);
            $d_systolic_toString = explode(',',$get_rows['d_systolic_toString']);
            $one_160_toString = explode(',',$get_rows['one_160_toString']);
            $one_150_toString = explode(',',$get_rows['one_150_toString']);
            $one_140_toSting = explode(',',$get_rows['one_140_toSting']);
            $one_130_toString = explode(',',$get_rows['one_130_toString']);
            $one_120_toString = explode(',',$get_rows['one_120_toString']);
            $one_110_toString = explode(',',$get_rows['one_110_toString']);
            $one_100_toString = explode(',',$get_rows['one_100_toString']);
            $one_90_toString = explode(',',$get_rows['one_90_toString']);
            $one_80_toString = explode(',',$get_rows['one_80_toString']);
            $one_70_toString = explode(',',$get_rows['one_70_toString']);
            $one_60_toString = explode(',',$get_rows['one_60_toString']);
            $one_50_toString = explode(',',$get_rows['one_50_toString']);
            $one_40_toString = explode(',',$get_rows['one_40_toString']);
            $one_30_toString = explode(',',$get_rows['one_30_toString']);
            $Temprature = explode(',',$get_rows['Temprature']);
            $Periphery = explode(',',$get_rows['Periphery']);
            $core_blue = explode(',',$get_rows['core_blue']);
            $one_38 = explode(',',$get_rows['one_38']);
            $one_37 = explode(',',$get_rows['one_37']);
            $one_36 = explode(',',$get_rows['one_36']);
            $one_35 = explode(',',$get_rows['one_35']);
            $one_34 = explode(',',$get_rows['one_34']);
            $cvp = explode(',',$get_rows['cvp']);
            $heart_rate = explode(',',$get_rows['heart_rate']);
            $map = explode(',',$get_rows['map']);
            $rhythm = explode(',',$get_rows['rhythm']);
            $sp02 = explode(',',$get_rows['sp02']);
        }

        # get employee name
        $select_employee_name = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$employee_id' ";
        $select_employee_name = mysqli_query($conn,$select_employee_name);
        while($employee_row = mysqli_fetch_array($select_employee_name)):
            $get_employee_name = $employee_row['Employee_Name'];
        endwhile;

?>

<table width='100%' id="table_id">
    <tr style="background-color: #ccc;padding:5px">
        <td style="padding:5px;font-size:16px" colspan="5"><center>Nurse : <?=$get_employee_name?> On : <?=$date?></center></td>
    </tr>
</table>

<br>

<table width='100%' >
    <tr>
      <td style="padding: 8px;">
        <span>Date</span>
        <br>
        <input class="form-control" type="text" placeholder="Date" id='details' disabled value="<?=$details[0]?>">
      </td>

      <td>
        <span>Diagnosis</span>
        <br>
        <input class="form-control" readonly type="text" placeholder="Diagnosis" id='details' value="<?=$details[4]?>">
      </td>

      <td>
        <span>Working</span>
        <br>
        <input class="form-control" readonly type="text" placeholder="Working" id='details' value="<?=$details[1]?>">
      </td>

      <td>
        <span>Reason for icu admission</span>
        <br>
        <input class="form-control" disabled type="text" placeholder="Reason for icu Admission" id='details' value="<?=$details[2]?>">
      </td>

      <td>
        <span>PMH</span>
        <br>
        <input class="form-control" disabled type="text" placeholder="PMH" id='details' value="<?=$details[3]?>">
      </td>
    </tr>
</table>

<br>

<div id="space" style="padding: 0px;background-color: aliceblue;">
    <table id="tables" width='100%' class="table table-sm table-striped table-bordered">
      <tr>
        <td rowspan="60" width='10%'>
          0 <b>Unresponse</b> 
          <br><br>
          1 <b>Unresponse</b> 
          <br><br>
          2 <b>Response to touch or name</b> 
          <br><br>
          3 <b>Calm and cooperative</b> 
          <br><br>
          4 <b>Restless but cooperative</b> 
          <br><br>
          5 <b>Agigitate</b>
          <br><br>
          6 <b>Unresponse</b>

          <br><br><br>

          <h6>RYTHM KEY</h6>
          <br>
          SR - <b>Sinus rhythm</b>
          <br><br>
          SB - <b>Sinus Bradycardia</b>
          <br><br>
          ST - <b>Sinus Tachycardia</b>
          <br><br>
          SVT- <b>Supra Ventricular tacycardia</b>
          <br><br>
          SF - <b>Ventricular Fibrillation</b>
          <br><br>
          AT - <b>Asostole</b>
          <br><br>
          AT - <b>Arterial Fibrillation/ flutter</b>
        </td>
        <td width='10%'>Names</td>
        <?php for($i = 1;$i < 26;$i++) { ?>
          <td width='3.2%' style="text-align:center"><?=$var_counts;?>:00</td>
          <?php if($var_counts == 23) { $var_counts = -1 ; } ?>
        <?php $var_counts++; } ?>
      </tr>

      <tr>
          <td>SAS</td>
          <?php perform_count($sas_toString,"first")?>
      </tr>

      <tr>
        <td>Pain score(0-10)</td>
        <?php perform_count($score,"first")?>
      </tr>

      <tr>
        <td rowspan="2">Blood Pressure <span class="float-end">200</span></td>
        <?php perform_count($blood_pressure_toString,"first")?>
      </tr>

      <tr>
        <?php perform_count($blood_pressure_toString,"second")?>
      </tr>

      <tr>
        <td rowspan="2">X systolic <span class="float-end" ">190</span></td>
        <?php perform_count($x_systolic_toString,"first")?>
      </tr>

      <tr>
        <?php perform_count($x_systolic_toString,"second")?>
      </tr>

      <tr>
        <td rowspan="2"><span class="float-end">180</span></td>
        <?php perform_count($one_180_toString,"first")?>
      </tr>

      <tr>
        <?php perform_count($one_180_toString,"second")?>
      </tr>

      <tr>
        <td rowspan="2">D systolic <span class="float-end">170</span></td>
        <?php perform_counts($d_systolic_toString,"first")?>
      </tr>

      <tr>
        <?php perform_counts($d_systolic_toString,'second')?>
      </tr>

      <tr>
        <td rowspan="2"><span class="float-end">160</span></td>
        <?php perform_counts($one_160_toString,"first")?>
      </tr>

      <tr>
        <?php perform_counts($one_160_toString,"second")?>
      </tr>

      <tr>
        <td rowspan="2"><span class="float-end">150</span></td>
        <?php perform_count($one_150_toString,"first")?>
      </tr>

      <tr>
        <?php perform_count($one_150_toString,"second")?>
      </tr>

      <tr>
        <td rowspan="2"><span class="float-end">140</span></td>
        <?php perform_count($one_140_toSting,"first")?>
      </tr>

      <tr>
        <?php perform_count($one_140_toSting,"second")?>
      </tr>

      <tr>
        <td rowspan="2"><span class="float-end">130</span></td>
        <?php perform_counts($one_130_toString,'first')?>
      </tr>

      <tr>
        <?php perform_counts($one_130_toString,'second')?>
      </tr>

      <tr>
        <td rowspan="2"><span class="float-end">120</span></td>
        <?php perform_count($one_120_toString,'first')?>
      </tr>

      <tr>
        <?php perform_counts($one_120_toString,'second')?>
      </tr>

      <tr>
        <td rowspan="2"><span class="float-end">110</span></td>
        <?php perform_count($one_110_toString,'first')?>
      </tr>

      <tr>
        <?php perform_count($one_110_toString,'second')?>
      </tr>

      <tr>
        <td rowspan="2"><span class="float-end">100</span></td>
        <?php perform_counts($one_100_toString,'first')?>
      </tr>

      <tr>
        <?php perform_counts($one_100_toString,'second')?>
      </tr>

      <tr>
        <td rowspan="2"><span class="float-end">90</span></td>
        <?php perform_counts($one_90_toString,'first')?>
      </tr>

      <tr>
        <?php perform_counts($one_90_toString,'second')?>
      </tr>

      <tr>
        <td rowspan="2"><span class="float-end">80</span></td>
        <?php perform_counts($one_80_toString,'first')?>
      </tr>

      <tr>
        <?php perform_counts($one_80_toString,'second')?>
      </tr>

      <tr>
        <td rowspan="2"><span class="float-end">70</span></td>
        <?php perform_counts($one_70_toString,'first')?>
      </tr>

      <tr>
        <?php perform_counts($one_70_toString,'second')?>
      </tr>

      <tr>
        <td rowspan="2"><span class="float-end">60</span></td>
        <?php perform_count($one_60_toString,'first')?>
      </tr>

      <tr>
        <?php perform_count($one_60_toString,'second')?>
      </tr>

      <tr>
        <td rowspan="2"><span class="float-end">50</span></td>
        <?php perform_count($one_50_toString,"first")?>
      </tr>

      <tr>
        <?php perform_count($one_50_toString,"second")?>
      </tr>

      <tr>
        <td rowspan="2"><span class="float-end">40</span></td>
        <?php perform_count($one_40_toString,'first')?>
      </tr>

      <tr>
        <?php perform_count($one_40_toString,'second')?>
      </tr>

      <tr>
        <td rowspan="2">Temprature <span class="float-end">41</span></td>
        <?php perform_count($Temprature,'first')?>
      </tr>

      <tr>
        <?php perform_count($Temprature,'Temprature')?>
      </tr>

      <tr>
        <td rowspan="2">Periphery-Re dot  <span class="float-end">40</span></td>
        <?php perform_count($Periphery,'first')?>
      </tr>

      <tr>
        <?php perform_count($Periphery,'second')?>
      </tr>

      <tr>
        <td rowspan="2">Core - Blue dot  <span class="float-end">39</span></td>
        <?php perform_count($core_blue,'first')?>
      </tr>

      <tr>
        <?php perform_count($core_blue,'core_blue')?>
      </tr>

      <tr>
        <td rowspan="2"><span class="float-end">38</span></td>
        <?php perform_count($one_38,'first')?>
      </tr>

      <tr>
        <?php perform_count($one_38,'second')?>
      </tr>

      <tr>
        <td rowspan="2"><span class="float-end">37</span></td>
        <?php perform_count($one_37,'first')?>
      </tr>

      <tr>
        <?php perform_count($one_37,'second')?>
      </tr>

      <tr>
        <td rowspan="2"><span class="float-end">36</span></td>
        <?php perform_count($one_36,'first')?>
      </tr>

      <tr>
        <?php perform_count($one_36,'second')?>
      </tr>

      <tr>
        <td rowspan="2"><span class="float-end">35</span></td>
        <?php perform_counts($one_35,'first')?>
      </tr>

      <tr>
        <?php perform_counts($one_35,'second')?>
      </tr>

      <tr>
        <td rowspan="2"><span class="float-end">34</span></td>
        <?php perform_counts($one_34,'first')?>
      </tr>

      <tr>
        <?php perform_counts($one_34,'second')?>
      </tr>

      <tr>
        <td><span>cvp</span></td>
        <?php perform_count($cvp,'first')?>
      </tr>

      <tr>
        <td><span>Heart Rate</span></td>
        <?php perform_count($heart_rate,'first')?>
      </tr>

      <tr>
        <td><span>Rhythm</span></td>
        <?php perform_count($rhythm,'first')?>
      </tr>

      <tr>
        <td>MAP</td>
        <?php perform_count($map,'first')?>
      </tr>

      <tr>
        <td><span>SPO2</span></td>
        <?php perform_count($sp02,'first')?>
      </tr>

    </table>
</div>

<?php
    }else{
        echo "No Data Found";
    }
?>