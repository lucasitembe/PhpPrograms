

<div id="tabsd">
    <?php
   
    if (isset($_SESSION['hospitalConsultaioninfo']['enable_pat_medic_hist']) && $_SESSION['hospitalConsultaioninfo']['enable_pat_medic_hist'] == '1') {
        $qrHistry = mysqli_query($conn,'SELECT famscohist,pastobshist,pastpaedhist,pastmedhist,pastdenthist,pastsurghist FROM tbl_patient_histrory WHERE 	registration_id="' . $_GET['Registration_ID'] . '"') or die(mysqli_error($conn));
        $num_saved_pat_histr = mysqli_num_rows($qrHistry);
        $phry = mysqli_fetch_array($qrHistry);
        $famscohist = $phry['famscohist'];
        $pastobshist = $phry['pastobshist'];
        $pastpaedhist = $phry['pastpaedhist'];
        $pastmedhist = $phry['pastmedhist'];
        $pastdenthist = $phry['pastdenthist'];
        $pastsurghist = $phry['pastsurghist'];

        echo '<div id="patienthistory">
                        <h3 >Patient History</h3>
                            <table width="100%" style="border: 0px;">
                                <tr>
                                    <td width="15%" >
                                      Family and Social History
                                    </td>
                                    <td>
                                       <textarea style="resize:none;padding-left:5px;" required="required" id="famscohist"  name="famscohist">' . $famscohist . '</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%" >
                                      Past Obsertricgy History
                                    </td>
                                    <td>
                                       <textarea style="resize:none;padding-left:5px;" required="required" id="pastobshist"  name="pastobshist">' . $pastobshist . '</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%" >
                                      Past Paediatric History
                                    </td>
                                    <td>
                                       <textarea style="resize:none;padding-left:5px;" required="required" id="pastpaedhist"  name="pastpaedhist">' . $pastpaedhist . '</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%" >
                                      Past Medical History
                                    </td>
                                    <td>
                                       <textarea style="resize:none;padding-left:5px;" required="required" id="pastmedhist"  name="pastmedhist">' . $pastmedhist . '</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%" >
                                      Past Dental History
                                    </td>
                                    <td>
                                       <textarea style="resize:none;padding-left:5px;" required="required" id="pastdenthist"  name="pastdenthist">' . $pastdenthist . '</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%" >
                                      Past Surgical History
                                    </td>
                                    <td>
                                       <textarea style="resize:none;padding-left:5px;" required="required" id="pastsurghist"  name="pastsurghist">' . $pastsurghist . '</textarea>
                                    </td>
                                </tr>
                            </table>
                            <center><input type="button" onclick="savePatientHist()" class="art-button-green" value="Save Patient History"></center>
                           </div>';
    }
    
    //kuntacode paste from mfoyYY forceadmit query 
//    $deceased_reasons="";
//if(isset($_POST['save_death_reason_btn'])){
//   $deceased_reasons=$_POST['deceased_reasons'];
//    
//   $sql_insert_d_course_result=mysqli_query($conn,"INSERT INTO tbl_deceased_reasons(deceased_reasons) VALUES('$deceased_reasons')") or die(mysqli_error($conn));
//
//   if($sql_insert_d_course_result){
//   ?>
    <script>
//        alert("Course of death saved successfully");
    </script>
       <?php    
//   }else{
//     ?>
    <script>
//        alert("Fail  save caurse of death");
    </script>
      <?php   
//   }
//}
     $get_icd_9_or_10_result=mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='Icd_10OrIcd_9'") or die(mysqli_error($conn));
    if(mysqli_num_rows($get_icd_9_or_10_result)>0){
        $configvalue_icd10_9=mysqli_fetch_assoc($get_icd_9_or_10_result)['configvalue'];
    }
    //end of query confvalue

    

    ?>
    <div id="complain">
    <h4 align="center"><strong style="color:red;">Please Take and Write Comprehesive Medical Notes</strong></h4>
        <h3 style="margin-left: 100px" >Complain</h3>
        <center>
            <table width=70% style='border: 0px;'>
                <tr>
                    <td width='15%' style="text-align:right;" >
                        <!--<div style="margin:10px auto auto">-->  
                        Main Complain
                        <!--<div style="color:#4194D6" onclick="showOthersDoctorsStaff('Main_Complain')" class="otherdoclinks">Previous Doctor's Notes</div>-->
                    </td>
                    <td colspan="6">
                        <table width="100%">
                            <tr>
                                <td colspan="3">
                                    <textarea style='resize:none;padding-left:5px;height: 40px' required="required" id='maincomplain' <?php echo $auto_save_option ?>  name='maincomplain'><?php echo $maincomplain; ?></textarea>
                                    <?php echo $clinical_main_complain; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Complain</td>
                                <td>Duration</td>
                                <td width='50px'>Action</td>
                            </tr>
                            <?php 
                                //select previous main complain
                                $sql_select_main_complain_detail_result=mysqli_query($conn,"SELECT main_complain,duration FROM tbl_main_complain WHERE consultation_ID='$consultation_ID' AND consultant_id='$employee_ID'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_main_complain_detail_result)>0){
                                    while($previous_mai_complain_rows=mysqli_fetch_assoc($sql_select_main_complain_detail_result)){
                                        $main_complain=$previous_mai_complain_rows['main_complain'];
                                        $duration=$previous_mai_complain_rows['duration'];
                                        ?>
                                         <tr>
                                            <td>
                                                <textarea style='resize:none;padding-left:5px;height: 40px' required="required" id='maincomplain' name='maincomplain_incrmnt[]'><?= $main_complain ?></textarea>

                                            </td>
                                            <td style='width:20%'>
                                                <textarea placeholder="Duration" class="duration_text"  name='maincomplain_duration_incrmnt[]' style="text-align: center;resize:none;padding-left:5px;height: 40px"><?= $duration ?></textarea>
                                            </td>
                                        </tr>
                                         <?php
                                    }
                                }
                            ?>
                            <tr>
                                <td>
                                    <textarea style='resize:none;padding-left:5px;height: 40px' required="required" id='maincomplain' name='maincomplain_incrmnt[]'></textarea>
                                    
                                </td>
                                <td style='width:20%'>
                                    <textarea placeholder="Duration" class="duration_text"  name='maincomplain_duration_incrmnt[]' style="text-align: center;resize:none;padding-left:5px;height: 40px"></textarea>
                                </td>
                                <td>
                                    <input type="button" class="art-button-green" value="ADD" onclick="add_main_complain_row_area()"/>
                                </td>
                            </tr>
                            <tbody id="main_complain_add_tbl_body"></tbody>
                        </table>
                    </td>

                </tr>
                <tr><td style="text-align:right;">History Of Present Illness</td>
                 
                    <td colspan="6">
                        <table width="100%">
                            <tr>
                                <td>
                                    Complain
                                </td>
                                <td>
                                    Duration
                                </td>
                                <td>
                                    Onset
                                </td>
                                <td>
                                    Periodicity
                                </td>
                                <td>
                                    Aggravating Factor
                                </td>
                                <td>
                                    Relieving Factor
                                </td>
                                <td>Associated with</td>
                                <td>Action</td>
                            </tr>
                            <?php 
                             
                                $sql_select_previous_hpi_history_result=mysqli_query($conn,"SELECT complain,duration,onset,periodicity,aggrevating_factor,relieving_factor,associated_with FROM tbl_history_of_present_illiness WHERE consultation_ID='$consultation_ID' AND consultant_id='$employee_ID'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_previous_hpi_history_result)>0){
                                    while($hpi_rows=mysqli_fetch_assoc($sql_select_previous_hpi_history_result)){
                                        $complain=$hpi_rows['complain'];
                                        $duration=$hpi_rows['duration'];
                                        $onset=$hpi_rows['onset'];
                                        $periodicity=$hpi_rows['periodicity'];
                                        $aggrevating_factor=$hpi_rows['aggrevating_factor'];
                                        $relieving_factor=$hpi_rows['relieving_factor'];
                                        $associated_with=$hpi_rows['associated_with'];

                                       
                                        ?>
                                         <tr>
                                            <td>
                                                <input type="text"  name='hpi_complain[]'value='<?= $complain ?>' placeholder="Complain"/>
                                            </td>
                                            <td>
                                                <input type="text" name='hpi_duration[]'value='<?= $duration ?>' placeholder="Duration"/>
                                            </td>
                                            <td>
                                                <input type="text" name='hpi_onset[]'value='<?= $onset ?>' placeholder="Onset"/>
                                            </td>
                                            <td>
                                                <input type="text" name='hpi_periodicity[]'value='<?= $periodicity ?>' placeholder="Periodicity"/>
                                            </td>
                                            <td>
                                                <input type="text" name='hpi_aggravating_factor[]' value='<?= $aggrevating_factor ?>' placeholder="Aggravating Factor"/>
                                            </td>
                                            <td>
                                                <input type="text" name='hpi_relieving_factor[]'value='<?= $relieving_factor ?>' placeholder="Relieving Factor"/>
                                            </td>
                                            <td><input type="text" name='hpi_associated_with[]'value='<?= $associated_with ?>' placeholder="Associated with"></td>
                                        </tr>   
                                        <?php
                                    }
                                }
                                else{
                                    echo "<tr><td colspan='8'>No History</td></tr>";
                                }
                                
                            ?>
                            <tr>
                                <td>
                                    <input type="text"  name='hpi_complain[]' placeholder="Complain"/>
                                </td>
                                <td>
                                    <input type="text" name='hpi_duration[]' placeholder="Duration"/>
                                </td>
                                <td>
                                    <input type="text" name='hpi_onset[]' placeholder="Onset"/>
                                </td>
                                <td>
                                    <input type="text" name='hpi_periodicity[]' placeholder="Periodicity"/>
                                </td>
                                <td>
                                    <input type="text" name='hpi_aggravating_factor[]'  placeholder="Aggravating Factor"/>
                                </td>
                                <td>
                                    <input type="text" name='hpi_relieving_factor[]' placeholder="Relieving Factor"/>
                                </td>
                                <td><input type="text" name='hpi_associated_with[]' placeholder="Associated with"></td>
                                <td><input type="button" class="art-button-green" onclick='add_hpi_row()' value="ADD"></td>
                            </tr>
                            <tbody id="hpi_tbl_body"></tbody>
                            <tr>
                                </td>
                                <td colspan="8">
                                    <textarea style='resize: none;padding-left:5px;' id='history_present_illness' name='history_present_illness' <?php echo $auto_save_option ?>><?php echo $history_present_illness; ?></textarea>
                                        <?php echo $history_present_illness2; ?>
                            </tr>
                            
                        </table>
                    </td>
                    
                </tr>
                  <tr>
                    <td>
                        <b style="color:red">Type Of Patient Case</b>
                    </td>
                    <td>
                        <select class="" name='Type_of_patient_case' id='Type_of_patient_case' required='required'>
                           <?php if (!strtolower($Type_of_patient_case) == 'continue_case'){ ?>
                              <option <?php if (strtolower($Type_of_patient_case) == 'new_case') echo 'selected'; ?> value="new_case">New Case</option>
                           <?php } ?>
                            <option <?php if (strtolower($Type_of_patient_case) == 'continue_case') echo 'selected'; ?> value="continue_case">Continues Case</option>
                        </select>
                    </td>
                    <td style="text-align:right;">Course Injuries</td>
                    <td>
                        <select name="course_of_injuries" style='padding-left:5px;'>
                            <option selected="selected" value="None">None</option>>
                            <?php echo $opt; ?>
                        </select>
                        <!--<input type='text' id='firstsymptom_date' name='firstsymptom_date' style='width: 150px;padding-left:5px;' value='<?php echo $firstsymptom_date; ?>'>-->
                    </td>
                    <td>To Come Again</td>
                    <td>
                        <select name="to_come_again_reason">
                            <option value=""></option>
                            <?php 

                    
                            $sql_selct_rank_result=mysqli_query($conn,"SELECT to_come_again_reason,to_come_again_id FROM tbl_to_come_again_reason") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_selct_rank_result)>0){
                                while($rank_rows=mysqli_fetch_assoc($sql_selct_rank_result)){
                                   $to_come_again_reason=$rank_rows['to_come_again_reason'];
                                    $to_come_again_id=$rank_rows['to_come_again_id'];
                                    echo "<option>$to_come_again_reason</option>";
                            }}
                            ?>
                        </select>
                    </td>
                    <td  style="text-align: center;">
                        <?php
                        //check status
                        $slct = mysqli_query($conn,"select Spectacle_ID from tbl_spectacles where Registration_ID = '$Registration_ID' and Spectacle_Status = 'pending' and Patient_Type = 'Outpatient'") or die(mysqli_error($conn));
                        $nm = mysqli_num_rows($slct);
                        ?>
                        <input type="checkbox" name="Optical_Option" id="Optical_Option" class="art-button-green" value="REQUIRE SPECTACLE" <?php if ($nm > 0) {
                            echo "checked='checked'";
                        } ?> onclick="Pequire_Spectacle(<?php echo $Registration_ID; ?>)">
                        <label for="Optical_Option"><b>REQUIRE SPECTACLE</b></label>
                    </td>
                </tr>
                <tr class="hide"><td style="text-align:right;">First Date Of Symptoms</td><td colspan="6"><input type='text' <?php echo $is_date_chooser; ?> name='firstsymptom_date'  id="firstsymptom_date" <?php echo $auto_save_option ?> style='padding-left:5px;' value='<?php echo $firstsymptom_date; ?>'><input type="hidden" id="currDates" value="">
                         <?php echo $firstsymptom_date2; ?>
                    </td>
                   
                </tr>
                <tr><td style="text-align:right;">Review Of Other Systems</td><td colspan="6"><textarea style='resize:none;padding-left:5px;' <?php echo $auto_save_option ?> id='review_of_other_systems' name='review_of_other_systems'><?php echo $review_of_other_systems; ?></textarea>
                         <?php echo  $review_of_other_systems2; ?>
                    </td></tr>
            
                
              
              <tr> 
                    <td style="text-align:right;" >Past Medical History</td>
                    <td colspan="6">
                        <textarea style='resize:none;padding-left:5px;' name="past_medical_history" id="past_medical_history" <?php echo $auto_save_option ?> ><?= $past_medical_history ?></textarea>
                        <?php echo  $past_medical_history2; ?>
                    </td>
                </tr>
                <tr> 
                    <td style="text-align:right;" >Family and Social History</td>
                    <td colspan="6">
                        <textarea style='resize:none;padding-left:5px;' name="family_social_history" id="family_social_history" <?php echo $auto_save_option ?> ><?= $family_social_history ?></textarea>
                        <?php echo  $family_social_history2; ?>
                    </td>
                </tr>
                <tr> 
                    <td style="text-align:right;" >Gynocological History</td>
                    <td colspan="6">
                        <textarea style='resize:none;padding-left:5px;' name="Gynocological_history" id="Gynocological_history" <?php echo $auto_save_option ?> ><?= $Gynocological_history ?></textarea>
                        <?php echo  $Gynocological_history2; ?>
                    </td>
                </tr>
                <tr>
                </tr>

            </table>

        </center>
    </div>
    <div id="observation"><div style="display: ruby-base;">
       <h3 style="margin-left: 100px;margin-right:4.2em">Physical Examinations</h3>
       <a href='#' class='art-button-green' id='btn_optical' onclick="open_optical_dialog(<?php echo $Registration_ID;?>)">EYE OPD FORM</a>
       </div>
        <center>
            <table width=70% style='border: 0px;'>
                <tr>
                    <td width='15%' style="text-align:right;">General Examination Observation</td>
                    <td>
                        <textarea style='width: 100%;resize:none;padding-left:5px;' <?php echo $auto_save_option ?> id='general_observation' name='general_observation'><?php echo $general_observation; ?></textarea>
                         <?php echo  $general_observation2; ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;">
                        Local Examination
                    </td>
                    <td>
                        <textarea style='width: 100%;resize:none;padding-left:5px;' id="local_examination"name="local_examination" <?php echo $auto_save_option ?>><?= $local_examination ?></textarea>
                        <?php echo $local_examination2; ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;">Systemic Examination Observation</td>
                    <!--<td><input type='text' id='systemic_observation' name='systemic_observation' value='<?php // echo $systemic_observation;         ?>'>
                    </td>
                    -->

                    <td>
                        <textarea style='width: 100%;resize:none;padding-left:5px;' <?php echo $auto_save_option ?> id='systemic_observation' name='systemic_observation'><?php echo $systemic_observation; ?></textarea>
                         <?php echo $systemic_observation2; ?>
                    </td>
                </tr>
                
            </table>
        </center>
    </div>
<div id="optical_section"></div>
    <div id="diagnosis">
        <h3 style="margin-left: 100px">Diagnosis</h3>
        <center>
            <table width=70% style='border: 0px;'>
                <tr><td style="text-align:right;width:15% ">Provisional Diagnosis</td><td><input style='width:88%;'  type='text' readonly='readonly'  class='provisional_diagnosis' id="provisional_diagnosis_comm" name='provisional_diagnosis' value='<?php echo $provisional_diagnosis; ?>'>
                      <input type='button'  name='select_provisional_diagnosis' value='Select' class='art-button-green' onclick='getDiseaseFinal("provisional_diagnosis")'> <?php echo $provisional_diagnosis2; ?> </td></tr>

                <tr><td style="text-align:right;">Differential Diagnosis</td><td><input style='width:88%;' type='text' readonly='readonly' id='diferential_diagnosis' class='diferential_diagnosis' name='diferential_diagnosis' value='<?php echo $diferential_diagnosis; ?>'>
                        <input type='button'  name='select_provisional_diagnosis' value='Select' class='art-button-green' onclick='getDiseaseFinal("diferential_diagnosis")'><?php echo $diferential_diagnosis2; ?> </td></tr>


                <tr><td style="text-align:right;"><b>Final Diagnosis </b></td><td><input style='width: 88%;' type='text' readonly='readonly' id='diagnosis' class='final_diagnosis' name='diagnosis' value='<?php echo $diagnosis; ?>'>
                        <input type="button"  name="select_provisional_diagnosis" value="Select"  class="art-button-green" onclick="getDiseaseFinal('diagnosis')"><?php echo $diagnosis2; ?> </td></tr>
                <tr> 
                    <td style="text-align:right;" >Doctor`s Plan / Suggestion</td>
                    <td colspan="6">
                        <textarea style='resize:none;padding-left:5px;' name="doctor_plan_suggestion" id="doctor_plan_suggestion" <?php echo $auto_save_option ?> ><?= $doctor_plan_suggestion ?></textarea>
                        <?php echo  $doctor_plan_suggestion2; ?>
                    </td>
                </tr>
            </table>
        </center>
    </div>
    <div id="investigation">
        <h3 style="margin-left: 100px">Investigation & Results</h3>
        <center>
            <table width=70% style='border: 0px;'>
                <tr><td style="text-align:right;">Laboratory</td><td><textarea style='width:88%;resize:none;padding-left:5px;' readonly='readonly' id='laboratory' name='laboratory'><?php echo $Laboratory; ?></textarea>

                        <input type='button'  id='select_Laboratory' name='select_Laboratory'  value='Select'  class='art-button confirmGetItem' 
                               <?php if ($provisional_diagnosis == '' || $provisional_diagnosis == null) { ?> onclick = "confirmDiagnosis('Laboratory')" <?php } else { ?> onclick="getItem('Laboratory')" <?php } ?> >
                               <button style='background: #06832A ; border-radius: 7px; display:none;'>
                                    <span  style="color:#fff; font-size:15px;" onclick="show_investigation_history(<?php echo $Registration_ID; ?>)">Investigation History</span>
                                </button>

                               <?php echo $Laboratory2; ?> </td></tr>
                <tr><td width='15%' style="text-align:right;">Comments For Laboratory</td><td><input type='text' <?php echo $auto_save_option ?> id='Comment_For_Laboratory' name='Comment_For_Laboratory' value='<?php echo $Comment_For_Laboratory; ?>'><?php echo $Comment_For_Laboratory2; ?></td></tr>
                <tr><td style="text-align:right;">Radiology</td><td><textarea style='width: 88%;resize: none;' readonly='readonly' type='text' id='provisional_diagnosis' class='Radiology' name='provisional_diagnosis'><?php echo $Radiology; ?></textarea>
                        <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="getItem('Radiology')">
                        <button style='background: #06832A ; border-radius: 7px; display:none;'>
                            <span  style="color:#fff; font-size:15px;" onclick="show_radiology_history(<?php echo $Registration_ID; ?>)">Radiology History</span>
                        </button>

                        <?php echo $Radiology2; ?></td></tr>
                <tr><td width='15%' style="text-align:right;">Comments For Radiology</td><td><input type='text' <?php echo $auto_save_option ?> id='Comment_For_Radiology' name='Comment_For_Radiology' value='<?php echo $Comment_For_Radiology; ?>'><?php echo $Comment_For_Radiology2; ?></td></tr>
                <tr><td width='15%' style="text-align:right;">Doctor's Investigation Comments</td><td><textarea style='resize: none;' <?php echo $auto_save_option ?> id='investigation_comments' name='investigation_comments'><?php echo $investigation_comments; ?></textarea><?php echo $investigation_comments2; ?></td></tr>
            </table>
        </center>
    </div>
    <div id="treatment">
        <h3 style="margin-left: 100px">Treatment</h3>
        <center>
            <table width=70% style='border: 0px;'>
                
                <!-- Meshack code Nuclear medicine consultation type -->
                <tr><td style="text-align:right;">Nuclear Medicine</td><td><textarea style='width: 88%;resize: none;' readonly='readonly' id='provisional_diagnosis' class='Nuclearmedicine' name='provisional_diagnosis'><?php echo $Nuclearmedicine; ?></textarea>
                    <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="confirm_final_diagnosis('Nuclearmedicine')"><?php echo $Nuclearmedicine2; ?> </td>
                </tr>
                <tr><td width='15%' style="text-align:right;">Comments For Nuclear Medicine</td><td><input type='text' <?php echo $auto_save_option ?> id='Nuclearmedicinecomments' name='Nuclearmedicinecomments' value='<?php echo $Nuclearmedicinecomments; ?>'><?php echo $Nuclearmedicinecomments2; ?></td></tr>
                 
                <!-- Meshack code Nuclear medicine consultation type -->

                <tr><td style="text-align:right;">Pharmacy</td><td><textarea style='width: 88%;resize: none;' readonly='readonly' id='provisional_diagnosis' class='Treatment' name='provisional_diagnosis'><?php echo $Pharmacy; ?></textarea>
                        <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="confirm_final_diagnosis('Pharmacy')">
                        <button style='background: #06832A ; border-radius: 7px;'>
                            <span  style="color:#fff; font-size:15px;" onclick="show_medication_history(<?php echo $Registration_ID; ?>)">    Medication History   </span>
                        </button>

                        <?php echo $Pharmacy2; ?> </td></tr>
                <tr><td style="text-align:right;">Procedure</td><td><textarea style='width: 88%;resize: none;' type='text' readonly='readonly' class='Procedure' id='provisional_diagnosis' name='provisional_diagnosis'><?php echo $Procedure; ?></textarea>
                        <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="confirm_final_diagnosis('Procedure')"><?php echo $Procedure2; ?></td></tr>

                <tr><td width='15%' style="text-align:right;">Procedure Comments</td><td><textarea <?php echo $auto_save_option ?>  style='width: 78%;resize: none;' rows='2' id='ProcedureComments' name='Comments_For_Procedure'><?php echo $Comments_For_Procedure; ?></textarea>
                        <input type="button" value="PERFORM PROCEDURE" class="art-button-green" onclick="Perform_Procedure()"> <?php echo $Comments_For_Procedure2; ?>
                        <!--<a href="proceduredocotorpatientinfo.php?Session=Outpatient&sectionpatnt=doctor_with_patnt&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&Registration_id=<?php echo $Registration_ID; ?>&ProcedureWorks=ProcedureWorksThisPage" class="art-button-green" >PERFORM PROCEDURE-->
                    </td>
                </tr>      
                <tr><td style="text-align:right;">Surgery</td><td><textarea style='width: 88%;resize: none;' type='text' readonly='readonly' class='Surgery'  id='provisional_diagnosis' name='provisional_diagnosis'><?php echo $Surgery; ?></textarea>
                        <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="confirm_final_diagnosis('Surgery')"><?php echo $Surgery2; ?></td></tr>
                <tr><td width='15%'style="text-align:right;" >Sugery Comments</td>
                    <td><textarea <?php echo $auto_save_option ?> style='width: 67%;resize: none;' rows='1' id='SugeryComments' name='Comments_For_Surgery'><?php echo $Comments_For_Surgery; ?></textarea>

                        <a href='performsurgery.php?consultation_ID=<?php echo $consultation_ID ?>&<?php
                        if ($Registration_ID != '') {
                            echo "Registration_ID=$Registration_ID&";
                        }
                        ?><?php
                        if (isset($_GET['Patient_Payment_ID'])) {
                            echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
                        }
                        if (isset($_GET['Patient_Payment_Item_List_ID'])) {
                            echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&From=doctor&";
                        }
                        ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' target="_parent" class="art-button-green">PERFORM SUGERY(Post Operative Report)
                            <?php echo $Comments_For_Surgery2; ?>
                    </td>

                </tr>
            </table>
        </center>
    </div>
    <div id="remarks">
        <h3 style="margin-left: 100px">Remarks</h3>
        <center>
            <table width=70% style='border: 0px;'>
                <tr>
                    <td width='15%' style="text-align:right;">Patient Type</td>
                    <td>
                        <table width="100%">
                            <tr>
                                <td>
                                    
                                    <select name="ToBeAdmitted" onChange="ShowReason(this.value);" id="ToBeAdmitted">
                                        <option value='no' <?php if ($ToBe_Admitted == 'no') echo "selected='selected'"; ?>>OUTPATIENT</option>
                                        <option value='yes' <?php if ($ToBe_Admitted == 'yes') echo "selected='selected'"; ?> >INPATIENT</option>
                                        <option onclick='opencourdeofDeathDialogy()' >DEATH</option>
                                    </select>
                                </td>
                             
                                
                            </tr>
                           
                            <tr style="display: none" id="show_ward">
                                <td colspan="2">
                                    <table class="table">
                                        <tr>
                                 <td>
                                    
                                     <select name="Ward_suggested" id="Ward_suggested" onchange="ward_nature(this.value)" required="required" style="width: 150px;padding-left:5px;">
                                        <option selected='selected'></option>
                                        
                                        
                                    <?php
                                    
                                      $sql_check_for_admission="SELECT ad.Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_admission ad INNER JOIN tbl_hospital_ward hw ON ad.Hospital_Ward_ID=hw.Hospital_Ward_ID WHERE ad.Registration_ID='$Registration_ID'";
                                    $sql_check_for_admission_result=mysqli_query($conn,$sql_check_for_admission) or die(mysqli_error($conn));
                                    if(mysqli_num_rows($sql_check_for_admission_result)>0){
                                        $wars_admitted_row=mysqli_fetch_assoc($sql_check_for_admission_result);
                                         $Hospital_Ward_ID=$wars_admitted_row['Hospital_Ward_ID'];
                                         $Hospital_Ward_Name=$wars_admitted_row['Hospital_Ward_Name'];
                                        echo "<option selected='selected' value='$Hospital_Ward_ID'>$Hospital_Ward_Name</option>";
                                    }
                                    
                                    $Select_Department = mysqli_query($conn,"SELECT Hospital_Ward_Name,Hospital_Ward_ID,Number_of_Beds FROM tbl_hospital_ward");
                                    while ($row = mysqli_fetch_array($Select_Department)) {
                                        $Ward_Name = $row['Hospital_Ward_Name'];
                                        $Hospital_Ward_ID = $row['Hospital_Ward_ID'];
                                        $Number_of_Beds = $row['Number_of_Beds'];

                                        $count_beds = "SELECT COUNT(Admision_ID) as beds FROM tbl_admission WHERE Hospital_Ward_ID = $Hospital_Ward_ID AND Admission_Status = 'Admitted'";
                                        $taken_beds = mysqli_query($conn,$count_beds) or die(mysqli_error($conn));
                                        while ($row = mysqli_fetch_assoc($taken_beds)) {
                                            $beds_accupied = $row['beds'];
                                            $available_beds = $Number_of_Beds - $beds_accupied;
                                        }
                                        ?>
                                        <option value='<?php echo $Hospital_Ward_ID; ?>'>
                                            <?php
                                            //echo $Ward_Name ." - ".$Number_of_Beds." | ". $available_beds." | ".$beds_accupied;
                                            echo $Ward_Name;
                                            ?>
                                        </option>
                                        <?php
                                    }

                                    
                                    ?>  
                                        
                                    </select>
									
									
									&nbsp;&nbsp;&nbsp;<span id='ward_warning' style='font-weight:bold;color:red;font-size:16px;display:none'>Please select ward!</span>
                                </td>
                                
                                <td>
                                    <input type="text" class="form-control" name="Kin_Name" placeholder="Enter Next of kin"><br/>
                                    <input type="text" class="form-control" name="Kin_Relationship" placeholder="Enter Kin Relationship"><br/>
                                    <input type="number" class="form-control" name="Kin_Phone" placeholder="Enter Kin Phone"><br/>
                                </td>
                                        </tr>
                                    </table> 
                                </td>
                            </tr>
                            
                        </table>
                    </td>
                </tr>
                
                <tr  width="100%" id="ToBeAdmittedReason" <?php if ($ToBe_Admitted == 'no' || $ToBe_Admitted == '') echo "style='display:none;'"; ?> >
                    <td style="text-align:right;">
                        Continuation Sheet
                    </td>
                    <td>
                        <textarea style='resize: none;' name="ToBeAdmittedReason"><?php if (isset($ToBe_Admitted_Reason)) echo $ToBe_Admitted_Reason; ?></textarea>

                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;">Others</td><td><textarea style='width: 88%;resize: none;' type='text' readonly='readonly' class='otherconstype'  ><?php echo $Others; ?></textarea>
                        <input type='button'  value='Select'  class='art-button-green' onclick="getItem('Others')"></a><?php echo $Others2 ?></td>
                </tr>

                <tr>
                    <td width='15%' style="text-align:right;" >Remarks</td>
                    <td>
                        <textarea style='resize: none;' <?php echo $auto_save_option ?> id='remark' name='remarks'><?php echo $remarks; ?></textarea>
                         <?php echo $remarks2; ?>
                    </td>
                </tr>
            </table>
        </center>
    </div>
    <span id="" ></span>
</div>
<!--start dialog codebook-->
<div id="store_death_discharged_info" style="display:none">
       
</div>
<input type="hidden" id="Patient_Payment_ID" value="<?= $_GET['Patient_Payment_ID']; ?>">
<input type="hidden" id="Patient_Payment_Item_List_ID" value="<?= $_GET['Patient_Payment_Item_List_ID']; ?>">
<input type="text" id="Registration_ID" value="<?= $_GET['Registration_ID'];?>" hidden="hidden">
<div id="add_death_course_dialogy" style="display:none">
    <!--<form action="" method="POST">-->
        <table class="table">
            <tr>
                       <td> Enter Course Of Death </td>
                <td><input type="text" name="deceased_reasons" id="deceased_reason_ID"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" value="SAVE" class="art-button-green pull-right" onclick="add_causes_of_death_reason()">
                </td>
            </tr>
        </table>
    <!--</form>-->
</div>
<input type="text" id="Registration_ID" value="<?= $_GET['Registration_ID'];?>" hidden="hidden">
<!--end dialog kuntacode for mobile js-->
<div id="Previous_History"></div>
<script src="js/JsFunctions.js"></script>
<script type="text/javascript">
    
    function save_main_complain_and_history(){
          var main=[];
          var mains = document.getElementsByName('main[]');
          for (var i = 0; i <mains.length; i++) {
            var inp=mains[i];
                main.push(inp.value);
            }
          var history=[];
          var historys = document.getElementsByName('history[]');
          for (var i = 0; i <historys.length; i++) {
            var inp=historys[i];
                history.push(inp.value);
            }
 
           $.ajax({
            type: 'POST',
            url: 'save_doctors_information.php',
            data: {main:main,history:history},
            success: function (result) {
//                  console.log(result);
                 alert(result);
            }, error:function(x,y,z){
                console.log(x+y+z);
            }
        });
        
    }
        
   
        // function refresh_death_reason(){
        //     var Registration_ID=$("#Registration_ID").val();
        //     $.ajax({
        //         type:'GET',
        //         url:'refresh_death_reason.php',
        //         data:{Registration_ID:Registration_ID},
        //         success:function (data){
        //             //console.log(data);
        //             $("#disease_suffred_table").html(data);
        //         },
        //         error:function (x,y,z){
        //             console.log(z);
        //         }
        //     });  
        // }
         //endi code 
         //
        // function close_this_dialog(){
        //     var Admision_ID=$("#Admision_ID").val();
        //     forceadmit(Admision_ID);
        //     $("#store_death_discharged_info").dialog("close");
        // }

         //to add button

        // function open_add_reason_dialogy(){        
        //     $("#add_death_course_dialogy").dialog({
        //         title: 'ADD COURSE OF DEATH',
        //         width: '50%',
        //         height: 200,
        //         modal: true,
        //     }); 
        // }   
     
        //     function add_death_reason(disease_ID){
        //        var Registration_ID=$("#Registration_ID").val();
        //        $.ajax({
        //            type:'GET',
        //            url:'add_death_reason_to_catch.php',
        //            data:{disease_ID:disease_ID,Registration_ID:Registration_ID},
        //            success:function (data){
        //                console.log(data);
        //                $("#disease_suffred_table").html(data);
        //                search_disease_c_death();
        //            },
        //            error:function (x,y,z){
        //                console.log(z);
        //            }
        //        }); 
        //     }
        
            
        //     function remove_added_death_disease(disease_death_ID,Registration_ID){
        //         $.ajax({
        //            type:'GET',
        //            url:'remove_death_reason_to_catch.php',
        //            data:{disease_death_ID:disease_death_ID,Registration_ID:Registration_ID},
        //            success:function (data){
        //                //console.log(data);
        //                $("#disease_suffred_table").html(data);
        //            },
        //            error:function (x,y,z){
        //                console.log(z);
        //            }
        //        }); 
        //     }   
        //     function search_disease_c_death(){
                
        //         var disease_code=$("#disease_code").val();
        //         var disease_name=$("#disease_name").val();
        //         var disease_version='<?= $configvalue_icd10_9 ?>';
        //        $.ajax({
        //            type:'GET',
        //            url:'search_disease_c_death.php',
        //            data:{disease_code:disease_code,disease_name:disease_name,disease_version:disease_version},
        //            success:function (data){
        //                console.log(data);
        //                $("#disease_suffred_table_selection").html(data);
        //            },
        //            error:function (x,y,z){
        //                console.log(z);
        //            }
        //        }); 
        //   }

   //add function ya kuadd vitu kwenye database ilikuaa inaadd reception discharge
    function validate_number(){
       var relative_phone_number=$("#relative_phone_number").val();
       var relative_phone_number=$("#relative_phone_number").val();
       var relative_phone_number = relative_phone_number.replace(/^\s+/, '').replace(/\s+$/, '');
       if(relative_phone_number=="")relative_phone_number=0;
       $("#relative_phone_number").val("0"+parseInt(relative_phone_number));
    }
    var relative_phone_number=$("#relative_phone_number").val();
        var relative_phone_number = relative_phone_number.replace(/^\s+/, '').replace(/\s+$/, '');
        if(patient_phone_number_==""){
            $("#relative_phone_number").css("border","2px solid red");
            $("#relative_phone_number").focus();
            validate++;
        }else{
           $("#relative_phone_number").css("border",""); 
        }
    </script>
    <script>
// optical function
       function open_optical_dialog(Registration_ID){
           var Patient_Payment_ID=$("#Patient_Payment_ID").val();
           var Patient_Payment_Item_List_ID=$("#Patient_Payment_Item_List_ID").val();
           var Patient_Name=$("#Patient_Name").val();
            $.ajax({
                type:'post',
                url: 'optical_section.php',
                data : {
                    Registration_ID:Registration_ID,
                    Patient_Payment_ID:Patient_Payment_ID,
                    Patient_Payment_Item_List_ID:Patient_Payment_Item_List_ID
                },
                success : function(data){
                    $('#optical_section').dialog({
                        autoOpen:true,
                        width:'85%',
                        position: ['center',0],
                        title:'Patient Name  :  '+Patient_Name,
                        modal:true
                       
                    });  
                    $('#optical_section').html(data);
                    $('#optical_section').dialog('data');
                }
            })
        }
   function numberOnly(myElement) {
        var reg = new RegExp('[^0-9]+$');
        var str = myElement.value;
        if (reg.test(str)) {
            if (!isNaN(parseInt(str))) {
                intval = parseInt(str);
            } else {
                intval = '';
            }
            myElement.value = intval;
        }
    }
   
  </script>
  
 
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    </script>
    
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script> 
<!--kuntalibrary-->


<script src="script.js"></script>
<script src="script.responsive.js"></script>

<script src="css/jquery-ui.js"></script>

 <script type="text/javascript">
    $(document).ready(function () {
 $('#course_of_death').select2();
 $('#emp_id').select2();
 $('#ward_id').select2();
 $('#Docto_confirm_death_name').select2();
    });
</script>



<!--//new-->

