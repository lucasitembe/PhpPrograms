<?php
include("includes/connection.php");
include("radical_treatment_functions.php");

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$Radiotherapy_ID = (isset($_GET['Radiotherapy_ID'])) ? $_GET['Radiotherapy_ID'] : 0;
$Registration_ID = (isset($_GET['Registration_ID'])) ? $_GET['Registration_ID'] : '';
// $thisDate = (isset($_GET['thisDate'])) ? $_GET['thisDate'] : 0;

$Patients = json_decode(getPatientInfomations($conn,$Registration_ID),true);
$response = json_decode(getDoctorRequests($conn,$Radiotherapy_ID,$Registration_ID),true);

$display = '';
    $Treatment_Date = mysqli_query($conn, "SELECT DATE(Date_Time) AS Consultation_Dates, Employee_Name AS Constant_Doctor FROM tbl_radiotherapy_requests rr, tbl_employee em WHERE Radiotherapy_ID = '$Radiotherapy_ID' AND em.Employee_ID = rr.Employee_ID");
        while($drts = mysqli_fetch_assoc($Treatment_Date)){
            $Consultation_Dates = $drts['Consultation_Dates'];
            $Constant_Doctor = $drts['Constant_Doctor'];
        }

    $thisDate = date('l jS, F Y', strtotime($Consultation_Dates)) . '';


        $Inserted_By = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Insertion_Employee'"))['Employee_Name'];
        $Calculated_By = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$calculation_Employee'"))['Employee_Name'];
        $Treatedted_By = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Treated_By'"))['Employee_Name'];

?>
<!-- <a href="brachytherapy_parameter_patientlist.php?BrachtherapyThis=Brachytherapy" class='art-button-green'>BACK</a> -->

<br>
<fieldset>
    <h3>RADIOTHERAPY CONSULTATION (<?= $thisDate ?>)</h3>
    <table class="table" style="background: #FFFFFF; width: 100%">
                <tr>
                    <th style='text-align: right;'>CONSULTED DOCTOR</th>
                    <td><?= $Constant_Doctor ?></td>
                    <th style='text-align: right;'>DIAGNOSED DISEASE</th>
                    <td colspan='6'> 
                    <?php foreach($response as $data) : ?>
                        <?= $data['disease_name']; ?> (<b><?= $data['disease_code']; ?></b>); 
                    <?php endforeach; ?>
                    </td>
                </tr>
            </table>
    <?php
    
    $select_details = mysqli_query($conn, "SELECT Treatment_Phase, Tumor_Dose, Number_of_Fraction, Dose_per_Fraction, name_of_site, Number_of_Fields FROM tbl_radiotherapy_phases WHERE Radiotherapy_ID = '$Radiotherapy_ID' AND Phase_Status <> 'active' ORDER BY Ordered_No ASC");
        if(mysqli_num_rows($select_details) > 0){
            while($row = mysqli_fetch_assoc($select_details)){
                $Treatment_Phase = $row['Treatment_Phase'];
                $Tumor_Dose = $row['Tumor_Dose'];
                $Number_of_Fraction = $row['Number_of_Fraction'];
                $Dose_per_Fraction = $row['Dose_per_Fraction'];
                $name_of_site = $row['name_of_site'];
                $Number_of_Fields = $row['Number_of_Fields'];
                $data = '';
                $display_all = '';
                $display_rad = '';
                

                $display .= "<div class='box box-primary' style='height: 770px;overflow-y: scroll;overflow-x: hidden'>
                <caption><b>".$Treatment_Phase."</b></caption>";
                $display .= '<table  class="table table-collapse table-striped " style="border-collapse: collapse;border:1px solid black;">';
                $display .= '<tr>
                                <td>Total Tumor Dosage</td>
                                <th>'.$Tumor_Dose.' Grays</th>
                                <td>Total Number of Fraction</td>
                                <th>'.$Number_of_Fraction.'</th>
                            </tr>';
                $display .= '<tr>
                                <td>Dose Per Fraction</td>
                                <th>'.$Dose_per_Fraction.' Grays</th>

                                <td>Name of Site</td>
                                <th>'.$name_of_site.'</th>
                            </tr>
                            <tr>
                                <td>Number of Fields</td>
                                <th>'.$Number_of_Fields.'</th>
                                <th colspan="2"></th>
                            </tr>';
                $display .='</table>';

                $select_data_simulation = mysqli_query($conn, "SELECT ps.field_ID, pm.Mask, pm.number_Mask, pm.techniques, pm.number_alphabet, pm.body_position, pm.legs_position, pm.blocks, pm.separation, pm.number_site, pm.number_field, cp.date_time,ps.field_name, ps.Skin_length, pm.Comment, em.Employee_Name as Calculator, Treatment_Time, Dose_Fraction, ps.f_s_on_skin,ps.Skin_length,ps.f_s_on_tumour,ps.ssd_sad,ps.depth,ps.gantry_angle,pm.head_position, ps.coll_angle,ps.cough_angle FROM tbl_fields_position ps,tbl_position_immobilization pm, tbl_calculation_parameter cp, tbl_employee em WHERE em.Employee_ID = cp.Employee_ID AND ps.position_immobilization_ID=pm.position_immobilization_ID AND pm.Radiotherapy_ID='$Radiotherapy_ID' AND cp.field_ID = ps.field_ID AND pm.number_phases = '$Treatment_Phase' GROUP BY pm.number_phases") or die(mysqli_error($conn));
                $nums = mysqli_num_rows($select_data_simulation);

                if($nums > 0){
                    while($dats = mysqli_fetch_assoc($select_data_simulation)){
                        $body_position = $dats['body_position'];
                        $legs_position = $dats['legs_position'];
                        $blocks = $dats['blocks'];
                        $number_site = $dats['number_site'];
                        $number_field = $dats['number_field'];
                        $Calculator = $dats['Calculator'];
                        $separation = $dats['separation'];
                        $date_time = $dats['date_time'];
                        $Comment = $dats['Comment'];
                        $field_ID = $dats['field_ID'];

                        $display .='<hr>
                                    <br>
                                    <h4>RADIOTHERAPY SIMULATION</h4>
                                    <table class="table" style="background-color: white;width:100%" id="colum-addition">
                                        <tr>
                                            <th style="text-align: right;">Body Position</td>
                                            <td>'.$body_position.'</td>
                                            <th style="text-align: right;">Leg Position</td>
                                            <td>'.$legs_position.'</td>
                                            <th style="text-align: right;">Blocks</td>
                                            <td>'.$blocks.'</td>
                                            <th style="text-align: right;">Separation</td>
                                            <td>'.$separation.'</td>
                                        </tr>
                                        <tr>
                                            <th style="text-align: right;">Number of Site</td>
                                            <td>'.$number_site.'</td>
                                            <th style="text-align: right;">Number of Fields</td>
                                            <td>'.$number_field.'</td>
                                            <th style="text-align: right;">Simulated By</td>
                                            <td>'.ucwords($Calculator).'</td>
                                            <th style="text-align: right;">Simulated At</td>
                                            <td>'.$date_time.'</td>
                                        </tr>
                                        <tr>
                                            <th style="text-align: right;">Simulation Comment</td>
                                            <td olspan="4">'.$Comment.'</td>
                                        </tr>
                                    </table>';
                    }

                }
                $select_data_simulation = mysqli_query($conn, "SELECT ps.field_ID, pm.Mask, pm.number_Mask, pm.techniques, pm.number_alphabet, pm.body_position, pm.legs_position, pm.blocks, pm.separation, pm.number_site, pm.number_field, cp.date_time,ps.field_name, ps.Skin_length, pm.Comment, em.Employee_Name as Calculator, Treatment_Time, Dose_Fraction, ps.f_s_on_skin,ps.Skin_length,ps.f_s_on_tumour,ps.ssd_sad,ps.depth,ps.gantry_angle,pm.head_position, ps.coll_angle,ps.cough_angle FROM tbl_fields_position ps,tbl_position_immobilization pm, tbl_calculation_parameter cp, tbl_employee em WHERE em.Employee_ID = ps.Employee_ID AND ps.position_immobilization_ID=pm.position_immobilization_ID AND pm.Radiotherapy_ID='$Radiotherapy_ID' AND cp.field_ID = ps.field_ID AND pm.number_phases = '$Treatment_Phase'") or die(mysqli_error($conn));
                $nums = mysqli_num_rows($select_data_simulation);
                if($nums > 0){
                    $display_rad .= '<div class="box-body" style="height: 470px;overflow-y: scroll;overflow-x: hidden">
                            <table class="table" style="background-color: white;width:100%" id="colum-addition">
                                <tr>';
                        $display_rad .= '<td width="6%" colspan="2">
                                        <b>Field</b> 
                                    </td>';
                        $taarifa = '</tr><tr style="background: #dedede; font-weight: bold;"><td>Date</td>';
                        $taarifa2 = '</tr><tr><td>'.$date_and_time.'</td>';
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
                                    $date_time = $row['date_time'];
                                    // $data = '';
                                    $taarifa_all2 = '';

                                    $Select_Dates = mysqli_query($conn, "SELECT Date_field FROM tbl_treatment_delivery WHERE Radiotherapy_ID = '$Radiotherapy_ID' AND number_phases = '$Treatment_Phase' GROUP BY Date_field ORDER BY Date_field ASC") or die(mysqli_error($conn));
                                    $numbers = mysqli_num_rows($Select_Dates);
                                    $fraction = $numbers;
                                    $Sn = 1;

                                    $taarifa_all = "<tr style='background: #dedede;'>
                                    <td style='width:6% !important;'>
                                        <b>FRACTION #</b>
                                    </td>
                                    <td width='8%'>
                                        <b>Date</b>
                                    </td>";
                                    
                                    $display_rad .='<td>
                                                    <h4>'.ucwords($field_name).'</h4>
                                                    <h5>Calculated By: <b>'.ucwords($Calculator).'</b></h5>
                                                    <h5>Calculated At: <b>'.$date_time.'</b></h5>
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
                                            
                                        $select_field = mysqli_query($conn, "SELECT field_name FROM tbl_treatment_delivery td, tbl_fields_position ps WHERE Treatment_Status = 'delivered' AND Radiotherapy_ID = '$Radiotherapy_ID' AND number_phases = '$Treatment_Phase' AND field_ID = setup_devery_ID GROUP BY field_name ORDER BY setup_devery_ID ASC") or die(mysqli_error($conn));
                                            if(mysqli_num_rows($select_field) > 0){
                                                while($dts = mysqli_fetch_assoc($select_field)){

                                                    $taarifa_all .='<td><b>Dose per Field</b></td>
                                                    <td><b>Time</b></td>
                                                    <td><b>Commulative Dose</b></td>';
                                                }
                                            }
                                            $Select_Date = mysqli_query($conn, "SELECT Date_field, em.Employee_Name FROM tbl_treatment_delivery td, tbl_employee em WHERE Radiotherapy_ID = '$Radiotherapy_ID' AND number_phases = '$Treatment_Phase' AND Treatment_Status = 'delivered' AND em.Employee_ID = td.Employee_ID GROUP BY Date_field ORDER BY Date_field ASC") or die(mysqli_error($conn));

                                            if(mysqli_num_rows($Select_Date) > 0){
                                                while($Dates = mysqli_fetch_assoc($Select_Date)){
                                                    $Date_field = $Dates['Date_field'];
                                                    $Employee_Name = $Dates['Employee_Name'];

                                                    $thisDate = date('jS, F Y (l)', strtotime($Date_field)) . '';

                                                    $taarifa_all2 .= "<tr><td style='background: #f2f4f4;'>".$Sn."</td>
                                                    <td style='background: #f2f4f4; width: 10%'>".$thisDate."<br/>Treated By: <b>".ucwords($Employee_Name)."</b></td>";


                                                        $select_previous = mysqli_query($conn, "SELECT Dose_per_Fraction1, Time1, Cummutive_Dose1 FROM tbl_treatment_delivery WHERE Radiotherapy_ID = '$Radiotherapy_ID' AND number_phases = '$Treatment_Phase' AND Treatment_Status = 'delivered' AND Date_field = '$Date_field' ORDER BY setup_devery_ID ASC") or die(mysqli_error($conn));
                                                        while($detials = mysqli_fetch_array($select_previous)){
                                                            $Dose_per_Fraction1 = $detials['Dose_per_Fraction1'];
                                                            $Time1 = $detials['Time1'];
                                                            $Cummutive_Dose1 = $detials['Cummutive_Dose1'];
                                                            // $Dose = $Dose_per_Fraction1
                                                            $taarifa_all2 .="<td>".$Dose_per_Fraction1."</td><td>".$Time1."</td><td style='background: #ebf5fb;'>".$Cummutive_Dose1."</td>";
                                                        }
                                                // $Data_given_all .= $Data_given;

                                                    $Sn++;
                                                }
                                            }
                                                
                                    }
                                    $display_all .=$display_rad;
                                    // $taarifa_all_g .= $taarifa_all;
                                    $data .= $display_all."</tr>".$taarifa_all."</tr>".$taarifa_all2."</tr>";
                                    
                                    
                                    
                                    $data .= "</table></div>";
                                    
                }else{
                $data .= "<center><span style='font-size: 22px; text-align: center; color: red;'>THIS PATIENT HAS NOT RECEIVED ANY TREATMENT FOR <b>".strtoupper($Treatment_Phase)."</b></span></center>";
                }
                
                $display .= $data;
                $display .= '</div>';
                
            }
        }
            echo $display;
        ?>