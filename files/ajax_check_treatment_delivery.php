<?php
include("includes/connection.php");

$number_phases = $_GET['number_phases'];
$Radiotherapy_ID = $_GET['Radiotherapy_ID'];


// $Number_of_given_dosage = mysqli_query($conn, "SELECT COUNT(treatment_delivery_ID) AS Number_of_fraction FROM tbl_treatment_delivery  WHERE  Radiotherapy_ID = '$Radiotherapy_ID' AND number_phases = '$number_phases'");
$Select_Date = mysqli_query($conn, "SELECT Date_field, em.Employee_Name FROM tbl_treatment_delivery td, tbl_employee em WHERE Radiotherapy_ID = '$Radiotherapy_ID' AND number_phases = '$number_phases' AND Treatment_Status = 'delivered' AND em.Employee_ID = td.Employee_ID GROUP BY Date_field ORDER BY Date_field DESC") or die(mysqli_error($conn));
// while($idadi = mysqli_fetch_assoc($Number_of_given_dosage)){
//     $Number_of_fraction = $idadi['Number_of_fraction'];
// }
$Select_Details = mysqli_query($conn, "SELECT Number_of_Fraction AS Results FROM tbl_radiotherapy_phases WHERE Radiotherapy_ID = $Radiotherapy_ID AND Treatment_Phase = '$number_phases'") or die(mysqli_error($conn));
        while($row = mysqli_fetch_assoc($Select_Details)){
            $Results = $row['Results'];
        }

// if($Number_of_fraction <= $Results){
    $given_time = mysqli_num_rows($Select_Date);
    // echo $given_time. "+".$Results;
if($given_time < $Results){

        $select_data_simulation = mysqli_query($conn, "SELECT ps.field_ID, pm.Mask, pm.number_Mask, pm.techniques, pm.number_alphabet, ps.field_name, ps.Skin_length, em.Employee_Name as Calculator, Treatment_Time, Dose_Fraction, ps.f_s_on_skin,ps.Skin_length,ps.f_s_on_tumour,ps.ssd_sad,ps.depth,ps.gantry_angle,pm.head_position, ps.coll_angle,ps.cough_angle FROM tbl_fields_position ps,tbl_position_immobilization pm, tbl_calculation_parameter cp, tbl_employee em WHERE em.Employee_ID = cp.Employee_ID AND ps.position_immobilization_ID=pm.position_immobilization_ID AND pm.Radiotherapy_ID='$Radiotherapy_ID' AND cp.field_ID = ps.field_ID AND pm.number_phases = '$number_phases' AND pm.Radiotherapy_ID NOT IN(SELECT Radiotherapy_ID FROM tbl_treatment_delivery WHERE Radiotherapy_ID = '$Radiotherapy_ID' AND number_phases = '$number_phases' AND Date_field = CURDATE() AND Treatment_Status = 'delivered')") or die(mysqli_error($conn));

        $nums = mysqli_num_rows($select_data_simulation);
        if($nums > 0){
            echo '<div class="box-body" >
                    <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%" id="colum-addition">
                        <tr>';
                $display = '<td width="4%">
                                <b>Field</b> 
                            </td>';
                $taarifa = '</tr><tr style="background: #dedede; font-weight: bold;"><td>Date</td>';
                $taarifa2 = '</tr><tr><td>'.$date_and_time.'</td>';
                $Dose = '';
                $Dose_Fraction = '';
            while($row = mysqli_fetch_assoc($select_data_simulation)){
                            $field_name= $row['field_name'];
                            $f_s_on_skin= $row['f_s_on_skin'];
                            $Skin_length = $row['Skin_length'];
                            $f_s_on_tumour= $row['f_s_on_tumour'];
                            $ssd_sad= $row['ssd_sad'];
                            $depth= $row['depth'];
                            $gantry_angle= $row['gantry_angle'];
                            $coll_angle= $row['coll_angle'];
                            $cough_angle= $row['cough_angle'];
                            $Skin_length= $row['Skin_length'];
                            $techniques= $row['techniques'];
                            $field_ID = $row['field_ID'];
                            $Treatment_Time = $row['Treatment_Time'];
                            $Dose_Fraction = $row['Dose_Fraction'];
                            $Calculator = $row['Calculator'];
                            $head_position = $row['head_position'];
                            $Mask = $row['Mask'];
                            $number_alphabet = $row['number_alphabet'];
                            $number_Mask = $row['number_Mask'];
                            // $Mask = $row['Mask'];
                            $Dose = $Dose_Fraction/100;
                            $taarifa .='<td>Dose per Field</td>
                                        <td><b>Time</b></td>
                                        <td>Commulative Dose</td>';
                            $Commulative_dose = $Number_of_fraction + 1;
                            $taarifa2 .='<td><input type="text" value="'.$Dose.' Gray" id="Dose_per_Fraction'.$field_ID.'" disabled="disabled"></td>';
                            $taarifa2 .='<td><input type="text" value="'.$Treatment_Time.'" id="Treatment_Time'.$field_ID.'" disabled="disabled" style="font-weight: bold;"></td>';
                            $taarifa2 .='<td style="background: #ebf5fb;"><input type="text" id="Commulative_dose'.$field_ID.'" onkeyup="add_treatment('.$field_ID.')" ></td>';

                                
                            $display .='<td>
                                            <h4>'.ucwords($field_name).'</h4>
                                            <h5>Calculated By: <b>'.ucwords($Calculator).'</b></h5>
                                        </td>
                                        <td style="background: #ebf5fb;"><h5>
                                            <b>Field Size: </b>'.$f_s_on_skin.'X'.$Skin_length.' (cm)<br>
                                            <b>Depth: </b>'.$depth.' (cm)<br>
                                            <b>Technique: </b>'.$techniques.'</h5>
                                        </td>
                                        <td style="background: #ebf5fb;">
                                            <h5><b>Gantry Angle:</b> '.$gantry_angle.'; <b><br>Coll Angle: </b>'.$coll_angle.' (cm)<br>
                                            <b>Mask : </b>'.$Mask.' ( '.$number_Mask.')</br>
                                            <b>Head Rest: </b>'.$head_position.'('.$number_alphabet.')</b></h5>
                                        </td>';
                            }

                            $display_all .=$display;
                            $taarifa_all .= $taarifa;
                            $taarifa_all2 .= $taarifa2;

                            $data = $display_all."</tr><tr>".$taarifa_all."</tr><tr>".$taarifa_all2;
                            
        }else{
        $data = "<center><span style='font-size: 20px; text-align: center; color: red;'>THIS PATIENT HAS ALREADY RECEIVED TREATMENT FOR <b>".strtoupper($number_phases)."</b> TODAY</span></center>";
        }
    }else{
        $data = "<center><span style='font-size: 20px; text-align: center; color: red;'>THIS PATIENT HAS ALREADY COMPLETED TREATMENT FOR <b>".strtoupper($number_phases)."</b></span></center>";

            $Select_Details = mysqli_query($conn, "UPDATE tbl_radiotherapy_phases SET Phase_Status = 'Completed'  WHERE Radiotherapy_ID = $Radiotherapy_ID AND Treatment_Phase = '$number_phases'") or die(mysqli_error($conn));
    }
        echo $data;

mysqli_close($conn);
?>