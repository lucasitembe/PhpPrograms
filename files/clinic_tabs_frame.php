<div id="tabs">
    <ul >
        <?php
        if (isset($_SESSION['hospitalConsultaioninfo']['enable_pat_medic_hist']) && $_SESSION['hospitalConsultaioninfo']['enable_pat_medic_hist'] == '1') {
            echo ' <li><h3 ><a href="#patienthistory" style="font-size: small">Patient History</a></h3></li>';
        }
        ?>
        <li><h3 ><a href="#complain" style='font-size: small'>Complain</a></h3></li>
        <li><h3><a href="#observation" style='font-size: small'>Physical Examinations</a></h3></li>

        <?php
//commented this 
//if($Patient_Type){ echo "Physical Examinations";}else{ echo "Physical Examinations";}
        ?>

        <li><h3><a href="#diagnosis" style='font-size: small'>Diagnosis</a></h3></li>
        <li><h3><a href="#investigation" style='font-size: small'>Investigation & Results</a></h3></li>

        <li><h3><a href="#treatment" style='font-size: small'>Treatment</a></h3></li>
        <li><h3><a href="#remarks" style='font-size: small'>Remarks</a></h3></li>
    </ul>
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
                            <table width="100%" style="border: 0px;">
                                <tr>
                                    <td width="15%" style="text-align:right;">
                                      Family and Social History
                                    </td>
                                    <td>
                                       <textarea style="resize:none;padding-left:5px;" required="required" id="famscohist"  name="famscohist">' . $famscohist . '</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%" style="text-align:right;">
                                      Past Obsertricgy History
                                    </td>
                                    <td>
                                       <textarea style="resize:none;padding-left:5px;" required="required" id="pastobshist"  name="pastobshist">' . $pastobshist . '</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%" style="text-align:right;">
                                      Past Paediatric History
                                    </td>
                                    <td>
                                       <textarea style="resize:none;padding-left:5px;" required="required" id="pastpaedhist"  name="pastpaedhist">' . $pastpaedhist . '</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%" style="text-align:right;">
                                      Past Medical History
                                    </td>
                                    <td>
                                       <textarea style="resize:none;padding-left:5px;" required="required" id="pastmedhist"  name="pastmedhist">' . $pastmedhist . '</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%" style="text-align:right;">
                                      Past Dental History
                                    </td>
                                    <td>
                                       <textarea style="resize:none;padding-left:5px;" required="required" id="pastdenthist"  name="pastdenthist">' . $pastdenthist . '</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%" style="text-align:right;">
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
    ?>
    <div id="complain">
        <table width=100% style='border: 0px;'>
            <tr>
                <td width='15%' style="text-align:right;">
                    <!--<div style="margin:10px auto auto">-->  
                    Main Complain
                    <!--<div style="color:#4194D6" onclick="showOthersDoctorsStaff('Main_Complain')" class="otherdoclinks">Previous Doctor's Notes</div>-->
                    </div>
                </td>
                <td colspan="3"><textarea style='resize:none;padding-left:5px;' required="required" id='maincomplain' <?php echo $auto_save_option ?>  name='maincomplain'><?php echo $maincomplain; ?></textarea></td>

            </tr>
            <tr><td style="text-align:right;">First Date Of Symptoms</td><td colspan="3"><input type='text' <?php echo $is_date_chooser; ?> name='firstsymptom_date'  <?php echo $auto_save_option ?> style='padding-left:5px;' value='<?php echo $firstsymptom_date; ?>'><input type="hidden" id="currDates" value=""></td></tr>
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
                    <select name="course_of_injuries" style='width: 150px;padding-left:5px;'>
                        <option selected="selected" value="None">None</option>>
                        <?php echo $opt; ?>
                    </select>
                    <!--<input type='text' id='firstsymptom_date' name='firstsymptom_date' style='width: 150px;padding-left:5px;' value='<?php echo $firstsymptom_date; ?>'>-->
                </td>
            </tr>

            <tr><td style="text-align:right;">History Of Present Illness</td><td colspan="3"><textarea style='resize: none;padding-left:5px;' id='history_present_illness' name='history_present_illness' <?php echo $auto_save_option ?>><?php echo $history_present_illness; ?></textarea></td></tr>

        </table>
    </div>
    <div id="observation">
        <table width=100% style='border: 0px;'>
            <tr>
                <td width='15%' style="text-align:right;">General Examination Observation</td>
                <td>
                    <textarea style='width: 100%;resize:none;padding-left:5px;' <?php echo $auto_save_option ?> id='general_observation' name='general_observation'><?php echo $general_observation; ?></textarea>
                </td>
            </tr>
            <tr>
                <td style="text-align:right;">Systemic Examination Observation</td>
                <!--<td><input type='text' id='systemic_observation' name='systemic_observation' value='<?php // echo $systemic_observation;          ?>'>
                </td>
                -->

                <td>
                    <textarea style='width: 100%;resize:none;padding-left:5px;' <?php echo $auto_save_option ?> id='systemic_observation' name='systemic_observation'><?php echo $systemic_observation; ?></textarea>
                </td>
            </tr>
            <tr><td style="text-align:right;">Review Of Other Systems</td><td><textarea style='resize:none;padding-left:5px;' <?php echo $auto_save_option ?> id='review_of_other_systems' name='review_of_other_systems'><?php echo $review_of_other_systems; ?></textarea></td></tr>

        </table>
    </div>
    <div id="diagnosis">
        <table width=100% style='border: 0px;'>
            <tr><td style="text-align:right;width:15% ">Provisional Diagnosis</td><td><input style='width:88%;'  type='text' readonly='readonly'  class='provisional_diagnosis' id="provisional_diagnosis_comm" name='provisional_diagnosis' value='<?php echo $provisional_diagnosis; ?>'>
                    <input type='button'  name='select_provisional_diagnosis' value='Select' class='art-button-green' onclick='getDiseaseFinal("provisional_diagnosis")'></a></td></tr>

            <tr><td style="text-align:right;">Differential Diagnosis</td><td><input style='width:88%;' type='text' readonly='readonly' id='diferential_diagnosis' class='diferential_diagnosis' name='diferential_diagnosis' value='<?php echo $diferential_diagnosis; ?>'>
                    <input type='button'  name='select_provisional_diagnosis' value='Select' class='art-button-green' onclick='getDiseaseFinal("diferential_diagnosis")'></a></td></tr>


            <tr><td style="text-align:right;"><b>Final Diagnosis </b></td><td><input style='width: 88%;' type='text' readonly='readonly' id='diagnosis' class='final_diagnosis' name='diagnosis' value='<?php echo $diagnosis; ?>'>
                    <input type="button"  name="select_provisional_diagnosis" value="Select"  class="art-button-green" onclick="getDiseaseFinal('diagnosis')"></td></tr>
        </table>
    </div>
    <div id="investigation">
        <table width=100% style='border: 0px;'>
            <tr><td style="text-align:right;">Laboratory</td><td><textarea style='width:88%;resize:none;padding-left:5px;' readonly='readonly' id='laboratory' name='laboratory'><?php echo $Laboratory; ?></textarea>

                    <input type='button'  id='select_Laboratory' name='select_Laboratory'  value='Select'  class='art-button confirmGetItem' 
                           <?php if ($provisional_diagnosis == '' || $provisional_diagnosis == null) { ?> onclick = "confirmDiagnosis('Laboratory')" <?php } else { ?> onclick="getItem('Laboratory')" <?php } ?> ></a></td></tr>
            <tr><td width='15%' style="text-align:right;">Comments For Laboratory</td><td><input type='text' <?php echo $auto_save_option ?> id='Comment_For_Laboratory' name='Comment_For_Laboratory' value='<?php echo $Comment_For_Laboratory; ?>'></td></tr>
            <tr><td style="text-align:right;">Radiology</td><td><textarea style='width: 88%;resize: none;' readonly='readonly' type='text' id='provisional_diagnosis' class='Radiology' name='provisional_diagnosis'><?php echo $Radiology; ?></textarea>
                    <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="getItem('Radiology')"></a></td></tr>
            <tr><td width='15%' style="text-align:right;">Comments For Radiology</td><td><input type='text' <?php echo $auto_save_option ?> id='Comment_For_Radiology' name='Comment_For_Radiology' value='<?php echo $Comment_For_Radiology; ?>'></td></tr>
            <tr><td width='15%' style="text-align:right;">Doctor's Investigation Comments</td><td><textarea style='resize: none;' <?php echo $auto_save_option ?> id='investigation_comments' name='investigation_comments'><?php echo $investigation_comments; ?></textarea></td></tr>
        </table>
    </div>
    <div id="treatment">
        <table width=100% style='border: 0px;'>
            <tr><td style="text-align:right;">Pharmacy</td><td><textarea style='width: 88%;resize: none;' readonly='readonly' id='provisional_diagnosis' class='Treatment' name='provisional_diagnosis'><?php echo $Pharmacy; ?></textarea>
                    <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="getItem('Pharmacy')"></a></td></tr>

            <tr><td style="text-align:right;">Procedure</td><td><textarea style='width: 88%;resize: none;' type='text' readonly='readonly' class='Procedure' id='provisional_diagnosis' name='provisional_diagnosis'><?php echo $Procedure; ?></textarea>
                    <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="getItem('Procedure')"></td></tr>

            <tr><td width='15%' style="text-align:right;">Procedure Comments</td><td><textarea <?php echo $auto_save_option ?>  style='width: 78%;resize: none;' rows='2' id='ProcedureComments' name='Comments_For_Procedure'><?php echo $Comments_For_Procedure; ?></textarea>
                    <input type="button" value="PERFORM PROCEDURE" class="art-button-green" onclick="Perform_Procedure()">
                    <!--<a href="proceduredocotorpatientinfo.php?Session=Outpatient&sectionpatnt=doctor_with_patnt&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&Registration_id=<?php echo $Registration_ID; ?>&ProcedureWorks=ProcedureWorksThisPage" class="art-button-green" >PERFORM PROCEDURE </a>-->

                </td>
            </tr>      
            <tr><td style="text-align:right;">Surgery</td><td><textarea style='width: 88%;resize: none;' type='text' readonly='readonly' class='Surgery'  id='provisional_diagnosis' name='provisional_diagnosis'><?php echo $Surgery; ?></textarea>
                    <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="getItem('Surgery')"></a></td></tr>
            <tr><td width='15%' style="text-align:right;">Sugery Comments</td>
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
                    ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' target="_parent" class="art-button-green">PERFORM SUGERY(Post Operative Report)</a>
                </td>

            </tr>
        </table>
    </div>
    <div id="remarks">
        <table width=100% style='border: 0px;'>
            <tr>
                <td width='15%' style="text-align:right;">Patient Type</td>
                <td>
                    <table width="100%">
                        <tr>
                            <td>
                                <select name="ToBeAdmitted" onChange="ShowReason(this.value);"  id="ToBeAdmitted">
                                    <option value='no' <?php if ($ToBe_Admitted == 'no') echo "selected='selected'"; ?>>OUTPATIENT</option>
                                    <option value='yes' <?php if ($ToBe_Admitted == 'yes') echo "selected='selected'"; ?> >INPATIENT</option>
                                </select>
                            </td>
                            <td width="30%" style="text-align: center;">
                                <?php
                                //check status
                                $slct = mysqli_query($conn,"select Spectacle_ID from tbl_spectacles where Registration_ID = '$Registration_ID' and Spectacle_Status = 'pending' and Patient_Type = 'Outpatient'") or die(mysqli_error($conn));
                                $nm = mysqli_num_rows($slct);
                                ?>
                                <input type="checkbox" name="Optical_Option" id="Optical_Option" class="art-button-green" value="REQUIRE SPECTACLE" <?php
                                if ($nm > 0) {
                                    echo "checked='checked'";
                                }
                                ?> onclick="Pequire_Spectacle(<?php echo $Registration_ID; ?>)">
                                <label for="Optical_Option"><b>REQUIRE SPECTACLE</b></label>
                            </td>
                        </tr>
                        <tr style="display: none" id="show_ward">
                                 <td>
                                     <select name="Ward_suggested" id="Ward_suggested" onchange="ward_nature(this.value)" required="required" style="width: 150px;padding-left:5px;">
                                        <option selected='selected'></option>
                                    <?php
                                    $Select_Department = mysqli_query($conn,"SELECT * FROM tbl_hospital_ward");
                                    while ($row = mysqli_fetch_array($Select_Department)) {
                                        $Ward_Name = $row['Hospital_Ward_Name'];
                                        $Hospital_Ward_ID = $row['Hospital_Ward_ID'];
                                        $Number_of_Beds = $row['Number_of_Beds'];

                                        $count_beds = "SELECT COUNT(*) as beds FROM tbl_admission WHERE Hospital_Ward_ID = $Hospital_Ward_ID AND Admission_Status = 'Admitted'";
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
                <td style="">Others</td><td><textarea style='width: 88%;resize: none;' type='text' readonly='readonly' class='otherconstype'  ><?php echo $Others ?></textarea>
                    <input type='button'  value='Select'  class='art-button-green' onclick="getItem('Others')"></a></td>
            </tr>
            <tr>
                <td width='15%' style="text-align:right;">Remarks</td>
                <td>
                    <textarea style='resize: none;' <?php echo $auto_save_option ?> id='remark' name='remarks'><?php echo $remarks; ?></textarea>
                </td>
            </tr>
        </table>
    </div>
    <span id="" ></span>
</div>