<?php 
    include('../includes/connection.php');
    $time_td = array('00');
    $default_time_count = 7;

    $Registration_ID = (isset($_POST['Registration_ID'])) ? $_POST['Registration_ID'] : 0;

    $get_data = mysqli_query($conn,"SELECT * FROM tbl_icu_form_two WHERE Registration_ID = '$Registration_ID'");

    if(mysqli_num_rows($get_data) > 0){
        while($fetch_data = my  sqli_fetch_assoc($get_data)){
            $Employee_ID = $fetch_data['employee_id'];
            $date = $fetch_data['date'];
            $spontaneous_toString = explode(',',$fetch_data['spontaneous_toString']);
            $to_speech_toString = explode(',',$fetch_data['to_speech_toString']);
            $to_pain_toString = explode(',',$fetch_data['to_pain_toString']);
            $nill_toString = explode(',',$fetch_data['nill_toString']);
            $oriented_toString = explode(',',$fetch_data['oriented_toString']);
            $confused_toString = explode(',',$fetch_data['confused_toString']);
            $inappropriate_toString = explode(',',$fetch_data['inappropriate_toString']);
            $incomprehensive_sound_toString = explode(',',$fetch_data['incomprehensive_sound_toString']);
            $nill_tube_toString = explode(',',$fetch_data['nill_tube_toString']);
            $localizes_toString = explode(',',$fetch_data['localizes_toString']);
            $abdonormal_flexion_toString = explode(',',$fetch_data['abdonormal_flexion_toString']);
            $extension_abnormal_toString = explode(',',$fetch_data['extension_abnormal_toString']);
            $nil_toString = explode(',',$fetch_data['nil_toString']);
            $scale_total_toString = explode(',',$fetch_data['scale_total_toString']);
            $r_arm_toString = explode(',',$fetch_data['r_arm_toString']);
            $l_arm_toString = explode(',',$fetch_data['l_arm_toString']);
            $r_leg_toString = explode(',',$fetch_data['r_leg_toString']);
            $l_leg_toString = explode(',',$fetch_data['l_leg_toString']);
            $right_pupil_toString = explode(',',$fetch_data['right_pupil_toString']);
            $left_pupil_toString = explode(',',$fetch_data['left_pupil_toString']);
            $left_reaction_toString = explode(',',$fetch_data['left_reaction_toString']);
            $air_entry_toString = explode(',',$fetch_data['air_entry_toString']);
            $therapy_toString = explode(',',$fetch_data['therapy_toString']);
            $ventilator_toString = explode(',',$fetch_data['ventilator_toString']);
            $pressure_support_toString = explode(',',$fetch_data['pressure_support_toString']);
            $rr_set_to_String = explode(',',$fetch_data['rr_set_to_String']);
            $rr_pt_to_String = explode(',',$fetch_data['rr_pt_to_String']);
            $peak_inspiratory_pressure_to_String = explode(',',$fetch_data['peak_inspiratory_pressure_to_String']);
            $minute_volume_to_String = explode(',',$fetch_data['minute_volume_to_String']);
            $tidal_volume_pt_to_String = explode(',',$fetch_data['tidal_volume_pt_to_String']);
            $eep_cpap_to_String = explode(',',$fetch_data['eep_cpap_to_String']);
            $ratio_to_String = explode(',',$fetch_data['ratio_to_String']);
            $ett_mark_to_String = explode(',',$fetch_data['ett_mark_to_String']);
            $position_to_String = explode(',',$fetch_data['position_to_String']);
            $PH_to_String = explode(',',$fetch_data['PH_to_String']);
            $mode_to_String = explode(',',$fetch_data['mode_to_String']);
            $PCO2_to_String = explode(',',$fetch_data['PCO2_to_String']);
            $HCO3_to_String = explode(',',$fetch_data['HCO3_to_String']);
            $K_to_String = explode(',',$fetch_data['K_to_String']);
            $Na_to_String = explode(',',$fetch_data['Na_to_String']);
            $Mg2_to_String = explode(',',$fetch_data['Mg2_to_String']);
            $CI_to_String = explode(',',$fetch_data['CI_to_String']);
            $PO_4_to_String = explode(',',$fetch_data['PO_4_to_String']);
            $SAT_to_String = explode(',',$fetch_data['SAT_to_String']);
            $Blood_Sugar_to_String = explode(',',$fetch_data['Blood_Sugar_to_String']);
            $Suction_Secretion_to_String = explode(',',$fetch_data['Suction_Secretion_to_String']);
            $Comments_to_String = explode(',',$fetch_data['Comments_to_String']);
            $Chest_Physiotherapy_to_String = explode(',',$fetch_data['Chest_Physiotherapy_to_String']);
            $spasm = explode(',',$fetch_data['spasm']);

            $obeys_to_string = explode(',',$fetch_data['obeys_to_string']);
            $withdraw_to_string = explode(',',$fetch_data['withdraw_to_string']);

            $infusion_name_to_String = explode(',',$fetch_data['infusion_name_to_String']);
            $infusion_inputs_to_String = explode(',',$fetch_data['infusion_inputs_to_String']);
            $tidal_volume_set_to_string = explode(',',$fetch_data['tidal_volume_set_to_string']);
            $right_reaction_to_string = explode(',',$fetch_data['right_reaction_to_string']);
        }

        $select_employee_name = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Employee_ID' ";
        $select_employee_name = mysqli_query($conn,$select_employee_name);
        while($employee_row = mysqli_fetch_array($select_employee_name)):
            $get_employee_name = $employee_row['Employee_Name'];
        endwhile;
    
        

?>

<style>
    #tabe tr td{
        padding: 5px;
    }
</style>

<div class="text-center my-2">
    Done By : <b><?=$get_employee_name?></b> On : <b><?=$date?></b>
</div>

<table id="table" class="table table-sm table-striped table-bordered">

    <thead class="table-light">
        <tr>
            <th colspan="2" width="10%">Glassgow Coma Scale (GCS)</th>
            <?php for($i=0;$i < 24;$i++) { ?>
                <th style="text-align: center;" width="3.75%"><?=$default_time_count++?>:00</th>
                <?php if($default_time_count == 24) { $default_time_count = 0;} } ?>
        </tr>
    </thead>


    <tr>
        <td rowspan="4">Eye Opening</td>
        <td width="7%">4.Spontaneous</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$spontaneous_toString[$i]?></td>
        <?php  } ?>
    </tr>

    <tr>
        <td>3.To Speech</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$to_speech_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td>2.To Pain</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$to_pain_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td>1.Nil</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$nill_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td rowspan="5">Best Verbal Response</td>
        <td>5. Oriented</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$oriented_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td>4. Confused</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$confused_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td>3. Inappropriate Words</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$inappropriate_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td>2. Incomprehensive Sound</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$incomprehensive_sound_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td>1. Nil/Tube</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$nill_tube_toString[$i]?></td>
        <?php } ?>
    </tr>


    <!-- not yet -->
    <tr>
        <td rowspan="6">Best Motor Response</td>
        <td>6. Obeys</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$obeys_to_string[$i]?></td>
        <?php } ?>
    </tr>
    <!-- not yet -->

    <tr>
        <td>5. Localizes</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$localizes_toString[$i]?></td>
        <?php } ?>
    </tr>

    <!-- not yet -->
    <tr>
        <td>4. Withdraw</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$withdraw_to_string[$i]?></td>
        <?php } ?>
    </tr>
    <!-- not yet -->

    <tr>
        <td>3. Abdonormal Flexion</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$abdonormal_flexion_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td>2. Extension(Abnormal)</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$extension_abnormal_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td>1. Nil</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$nil_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td>Glassgow Coma</td>
        <td>Scale Total</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$scale_total_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td rowspan="4">
            Ability To Move
            <br>
            S - Strong
            <br>
            M - Moderate
            <br>
            W - Weak
            <br>
            A - Absent
        </td>
        <td>R. Arm</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$r_arm_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td>L. Arm</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$l_arm_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td>R. Leg</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$r_leg_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td>L. Leg</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$l_leg_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td rowspan="2">Pupil Size</td>
        <td>Right</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$right_pupil_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td>Left</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$left_pupil_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td rowspan="2">
            Reaction
            <br>
            · Brisk
            <br>
            · Sluggisk
            <br>
            · Fixed
        </td>
        <!-- not yet -->
        <td>Right</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$right_reaction_to_string[$i]?></td>
        <?php } ?>
        <!-- not yet -->
    </tr>

    <tr>
        <td>Left</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$left_reaction_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">Air Entry</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$air_entry_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">02 Therapy /FIO2</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$therapy_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">Ventilator</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$ventilator_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">Pressure Support</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$pressure_support_toString[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">RR SET</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$rr_set_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">RR Pt</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$rr_pt_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr> 
        <td colspan="2">Peak Insipiratory Pressure(PIP)</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$peak_inspiratory_pressure_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">Minute Volume</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$minute_volume_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">Tidal Volume Set </td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$tidal_volume_set_to_string[$i]?></td>
        <?php } ?>
    </tr>


    <tr>
        <td colspan="2">Tidal Volume Pt </td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$tidal_volume_pt_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">PEEP/CPAP</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$eep_cpap_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">I.E RATIO</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$ratio_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">ETT Mark</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$ett_mark_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">POSITION</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$position_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">Spasm</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$spasm[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">PH</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$PH_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">PCO2</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$PCO2_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">PK+</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$K_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">Na++</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$Na_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">Mg2+</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$Mg2_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">CI-1</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$CI_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">HCO-3</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$HCO3_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">PO++4</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$PO_4_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">O2 SAT</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$SAT_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">Blood Sugar</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$Blood_Sugar_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">Suction/Secretion</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$Suction_Secretion_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">Comments</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$Comments_to_String[$i]?></td>
        <?php } ?>
    </tr>

    <tr>
        <td colspan="2">Chest Physiotherapy</td>
        <?php for($i=0;$i < 24;$i++) { ?>
            <td><?=$Chest_Physiotherapy_to_String[$i]?></td>
        <?php } ?>
    </tr>
</table>

<br><br>

<table id="table-form" width=100% class="table table-sm table-striped table-bordered">
    <thead>
    <tr style="background-color:#eee">
        <th width="20%;">IV infusion</th>
        <th>0700</th>
        <th>0800</th>
        <th>0900</th>
        <th>1000</th>
        <th>1100</th>
        <th>1200</th>
        <th>1300</th>
        <th>1400</th>
        <th>1500</th>
        <th>1600</th>
        <th>1700</th>
        <th>1800</th>
        <th>1900</th>
        <th>2000</th>
        <th>2100</th>
        <th>2200</th>
        <th>2300</th>
        <th>0000</th>
        <th>0100</th>
        <th>0200</th>
        <th>0300</th>
        <th>0400</th>
        <th>0500</th>
        <th>0600</th>
    </tr>
    </thead>
    <tbody id="infusion_section">
        <?php 
            $add = 0;$final = 0;
            for($i = 0;$i < sizeof($infusion_name_to_String); $i++) { 
            $counter = sizeof($infusion_inputs_to_String);
        ?>
        <tr class="content-medication">
            <td width="20%;"><?=$infusion_name_to_String[$i]?></td>

            <?php for($j = 0;$j< 24; $j++){?>
            <td>
                <?=$infusion_inputs_to_String[$j + ($add + $final)] ?>
            </td>
            <?php } $add++; $final=23*$add; } ?>
        </tr>


    </tbody>
</table>

<?php 

}else{
    echo "
        <table width='100%'>
            <tr>
                <td style='color:red;padding:10px'><center>No Data Found</center></td>
            </tr>
        </table>
    ";
}

?>