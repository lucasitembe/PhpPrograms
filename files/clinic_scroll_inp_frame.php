



<!--kunta code for ajax qry-->

<?php


$deceased_reasons= "";
if(isset($_POST['save_death_reason_btn'])){
   $deceased_reasons=$_POST['deceased_reasons'];
   $sql_insert_d_course_result=mysqli_query($conn,"INSERT INTO tbl_deceased_reasons(deceased_reasons) VALUES('$deceased_reasons')") or die(mysqli_error($conn));

   if($sql_insert_d_course_result){
   ?>
    <script>
        alert("Course of death saved successfully test arsenal");
    </script> 
       <?php   
   } else
       {
     ?>
    <script>
        alert("Fail  save caurse of death");
    </script>
       <?php   
   }
}
 $get_icd_9_or_10_result=mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='Icd_10OrIcd_9'") or die(mysqli_error($conn));
    if(mysqli_num_rows($get_icd_9_or_10_result)>0){
        $configvalue_icd10_9=mysqli_fetch_assoc($get_icd_9_or_10_result)['configvalue'];
    }
    
    
?>
<!--above kuntacode new-->

<div id="tabsgg">

    <div id="observation">
        <h3 style="margin-left: 100px">Clinical History</h3>
        <center>
            <table width=70% style='border: 0px;'>
                <tr>
                    <td width='15%' style="text-align:right;">Clinical History</td>
                    <td>
                        <textarea required="required"  onCopy="return false" onPaste="return false" onCut="return false" onDrop="blur();return false;" style='width: 100%;resize: none;padding-left:5px;' id='clinical_history' <?php echo $auto_save_option ?> name='clinical_history'> <?php echo $clinical_history; ?></textarea>
                        <?php echo $clinical_history_round; ?>

                    </td>
                </tr>  </table>
        </center>
    </div>
    <div id="observation">
        <h3 style="margin-left: 100px">Findings</h3>
        <center>
            <table width=70% style='border: 0px;'>
                <tr>
                    <td width='15%' style="text-align:right;">Findings</td>
                    <td>
                        <textarea required="required" style='width: 100%;resize: none;padding-left:5px;' id='Findings' <?php echo $auto_save_option ?> name='Findings' onCopy="return false" onPaste="return false" onCut="return false"  onDrop="blur();return false;"> <?php echo $Find; ?></textarea>
                        <?php echo $Findings; ?>

                    </td>
                </tr>  </table>
        </center>
    </div>
<div id="optical_inpatient_section"></div>
     <!-- Show previous diagnosis for this consultation by Muga -->
     <div id="diagnosis">
        <h3 style="margin-left: 100px">Diagnosis</h3>
        <center>
            <table width=70% style='border: 0px;'>

                <tr><td style="text-align:right;width:15% ">Provisional Diagnosis</td><td><input style='width: 89%;display:inline' type='text' class='provisional_diagnosis' readonly='readonly' id='provisional_diagnosis' name='provisional_diagnosis' value='<?= $provisional_diagnosis1; ?>'>
                        <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select' class='art-button-green' onclick='getDiseaseFinal("provisional_diagnosis")'></a>
                        <?php echo $provisional_diagnosis; ?></td></tr>
                <tr><td style="text-align:right;width:15% ">Differential Diagnosis</td><td><input style='width: 89%;display:inline' class='diferential_diagnosis' type='text' readonly='readonly' id='diferential_diagnosis' name='diferential_diagnosis' value='<?= $diferential_diagnosis1 ?>'>
                        <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis'  value='Select' class='art-button-green' onclick='getDiseaseFinal("diferential_diagnosis")'></a><?php echo $diferential_diagnosis; ?></td></tr>
                <tr><td style="text-align:right;width:15% "><b>Final Diagnosis </b></td><td><input style='width: 89%;' type='text' readonly='readonly' id='diagnosis' class='final_diagnosis' name='diagnosis' value='<?= $diagnosis1 ?>'>
                        <input type="button"  name="select_provisional_diagnosis" value="Select"  class="art-button-green" onclick="getDiseaseFinal('diagnosis')">
                        <?php echo $diagnosis; ?>
                    </td></tr>
            </table>
        </center>
    </div>
    <!-- End of show previous diagnosis -->
   
    <div id="investigation">
        <h3 style="margin-left: 100px">Investigation & Results</h3>
        <center>
            <table width=70% style='border: 0px;'>
                <tr>
                    <td style="text-align:right;">Laboratory</td>
                    <td>
                        <textarea style='width: 89%;display:inline' readonly='readonly' id='laboratory' name='laboratory'><?php echo $Labory; ?></textarea>
                        <input type='button' class='art-button confirmGetItem' id='select_Laboratory' name='select_Laboratory'  value='Select' <?php if ($provisional_diagnosis == '' || $provisional_diagnosis == null) { ?> onclick = "confirmDiagnosis('Laboratory')" <?php } else { ?> onclick="getItem('Laboratory')" <?php } ?> >
                        <?php echo $Laboratory; ?>
                    </td>
                </tr>
                <tr>
                    <td width='15%' style="text-align:right;">Comments For Laboratory</td>
                    <td>
                        <input type='text' id='Comment_For_Laboratory' name='Comment_For_Laboratory' <?php echo $auto_save_option ?> value='<?php echo $Comment_For_Lab; ?>'>
                        <?php echo $Comment_For_Laboratory; ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;">Radiology</td>
                    <td><textarea style='width: 89%;display:inline' readonly='readonly' type='text' id='provisional_diagnosis' class='Radiology' name='Radiology'><?php echo $Rady; ?></textarea>
                        <input type='button' class='art-button-green' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select' onclick="getItem('Radiology')">
                        <?php echo $Radiology; ?>
                    </td>
                </tr>
                <tr>
                    <td width='15%' style="text-align:right;">Comments For Radiology</td>
                    <td>
                        <input type='text' id='Comment_For_Radiology' <?php echo $auto_save_option ?> name='Comment_For_Radiology' value='<?php echo $Comment_For_Rad; ?>'>
                        <?php echo $Comment_For_Radiology; ?>
                    </td>
                </tr>
                <tr>
                    <td width='15%' style="text-align:right;">Doctor's Investigation Comments</td>
                    <td>
                        <textarea style='resize: none;' id='investigation_comments' <?php echo $auto_save_option ?> name='investigation_comments'><?php echo $investigation_comm; ?></textarea>
                        <?php echo $investigation_comments; ?>
                    </td>
                </tr>
            </table>
        </center>
    </div>
    <div id="diagnosis_treatment">
        <div style="display: ruby-base;">
       <h3 style="margin-left: 100px;margin-right:9.2em">Treatment</h3>
              <a href='#' class='art-button-green' id='btn_optical' onclick="examination_of_operated_eye_dialog(<?php echo $Registration_ID;?>)">EXAMINATION OF THE OPERATED EYE</a>
       </div>
        <center>
            <table width=70% style='border: 0px;'>
               <tr><td style="text-align:right;">Pharmacy</td><td><textarea style='width: 84%;resize: none;' readonly='readonly' id='provisional_diagnosis' class='Treatment' name='Procedure'><?php echo $Pharcy; ?></textarea>
                       <div style="width: 15%;" class="pull-right"> <input type='button'  id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="confirm_final_diagnosis('Pharmacy')">
                        <br/>
                        <input type="button" value="Medication Updates" class="btn btn-danger btn-sm " style="background:#d33724" onclick="open_list_of_prescribed_medicine_dialog()">
                        <br/>
                        <!-- style="color:#fff; font-size:15px; background: #06832A; border-radius: 7px; padding: 10px;" -->
                        <input type="button" class="btn btn-success btn-sm " onclick="show_medication_history(<?php echo $Registration_ID; ?>)"    value="Medication History">
                       </div>
                        <div style="width:80%"><?php echo $Pharmacy; ?></div>
                        
                    </td>
                </tr>
                <!-- Nuclear medicine MSK CODE -->
                <tr><td style="text-align:right;">Nuclear Medicine</td><td><textarea style='width: 88%;resize: none;' type='text' readonly='readonly' class='Nuclearmedicine' id='provisional_diagnosis' name='Nuclearmedicine'><?php echo $Nuclearmedicines; ?></textarea>
                        <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="confirm_final_diagnosis('Nuclearmedicine')">
                        <?php echo $Nuclearmedicine; ?>
                    </td>
                </tr>
                <tr><td width='15%' style="text-align:right;">Comments For Nuclear Medicine</td><td><input type='text' <?php echo $auto_save_option ?> id='Nuclearmedicinecomments' name='Nuclearmedicinecomments' value='<?php echo $Nuclearmedicinecomments2; ?>'><?php echo $Nuclearmedicinecomments; ?></td></tr>
                 <!-- Nuclear medicine MSK CODE -->
                <tr><td style="text-align:right;">Procedure</td><td><textarea style='width: 88%;resize: none;' type='text' readonly='readonly' class='Procedure' id='provisional_diagnosis' name='Procedure'><?php echo $Procr; ?></textarea>
                        <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="confirm_final_diagnosis('Procedure')">
                        <?php echo $Procedure; ?>
                    </td>
                </tr>

                <tr><td width='15%' style="text-align:right;">Procedure Comments</td><td><input type='text' <?php echo $auto_save_option ?> id='ProcedureComments' name='Comment_For_Procedure' style='width: 83%;' value='<?php echo $Comment_For_Proc; ?>'>
                        <?php echo $Comment_For_Procedure; ?>
                        <input type="button" value="PERFORM PROCEDURE" class="art-button-green" onclick="Perform_Procedure()">
                         <!--<a href="proceduredocotorpatientinfo.php?Session=Inpatient&sectionpatnt=doctor_with_patnt&Registration_id=<?php echo $Registration_ID; ?>&consultation_ID=<?php echo $consultation_ID; ?>&ProcedureWorks=ProcedureWorksThisPage" class="art-button-green">PERFORM PROCEDURE--></td></tr> 

                <tr><td style="text-align:right;">Surgery</td><td><textarea style='width: 88%;resize: none;' type='text' readonly='readonly' class='Surgery'  id='provisional_diagnosis' name='Procedure'><?php echo $Surgry; ?></textarea>
                        <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="confirm_final_diagnosis('Surgery')">
                        <?php echo $Surgery; ?>
                    </td>
                </tr>
                <tr><td width='15%' style="text-align:right;">Sugery Comments</td>
                    <td>
                        <input style='width: 67%;' <?php echo $auto_save_option ?> type='text' id='SugeryComments' name='Comment_For_Surgery' value='<?php echo $Comment_For_Surg; ?>'>
                        <?php echo $Comment_For_Surgery; ?>
                        <a href='performsurgery.php?Section=Inpatient&consultation_ID=<?php echo $consultation_ID; ?>&Registration_ID=<?php echo $Registration_ID; ?>&PerformSurgery=PerformSurgeryThisPage' target="_parent" class="art-button-green">PERFORM SURGERY(Post Operative Report)</a>
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
                    <td width='15%' style="text-align:right;">Remarks</td>
                    <td>
                        <textarea class="disch_req" style='resize: none;' <?php echo $auto_save_option ?> id='remarks_plan' name='remarks'><?php echo $remk; ?></textarea>
                        <?php echo $remarks; ?>
                    </td>
                </tr>
                <tr>
                    <td width='15%' style="text-align:right;">Discharge diagnosis</td>
                    <td colspan="2" id="dischargeDiagnosis">
                    </td>
                </tr>
                <tr>
                    <td width='15%' style="text-align:right;">Patient Status</td>
                    <td>
                        <table width="100%">
                            <tr>
                                <td>
                                    <select id='dischargedPatient' name="dischargedPatient" style='width:40%;padding:4px;text-align: left;'>
                                        <option></option>
                                        <option>Continue</option>
                                        <option>Discharge</option>
                                    </select>
                                    
                                </td>
                                <?php 
                                    $Admision_ID=0;
                                    $select_inpatient=mysqli_query($conn,"SELECT Admision_ID FROM tbl_admission WHERE Registration_ID='$Registration_ID' AND Admission_Status IN ('Admitted','pending') ORDER BY Admision_ID DESC") or die(mysqli_error($conn));
                                    if(mysqli_num_rows($select_inpatient)>0){
                                        $Admision_ID=mysqli_fetch_array($select_inpatient)['Admision_ID'];
                                    }
                                    
                                   ?>
                               <td id="dis_reasons" style="display:none">
                                    <select onchange="check_if_dead_reason(this,'<?= $Registration_ID ?>','<?= $Admision_ID ?>')" id='Discharge_Reason_ID' name='Discharge_Reason_ID' required='required' style='width:31%'>
                                    <option value=""></option>
                                        <?php
                                        $select_discharge_reason = "SELECT * FROM tbl_discharge_reason";
                                        $reslt = mysqli_query($conn,$select_discharge_reason);
                                        while ($output = mysqli_fetch_assoc($reslt)) {
                                            ?>
                                            <option  value='<?php echo $output['Discharge_Reason_ID']; ?>'><?php echo $output['Discharge_Reason']; ?></option>    
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td width="30%" style="text-align: center;">
                                    <?php
                                        //check status
                                        $slct = mysqli_query($conn,"select Spectacle_ID from tbl_spectacles where Registration_ID = '$Registration_ID' and Spectacle_Status = 'pending' and Patient_Type = 'Inpatient'") or die(mysqli_error($conn));
                                        $nm = mysqli_num_rows($slct);
                                    ?>
                                    <input type="checkbox" name="Optical_Option" id="Optical_Option" class="art-button-green" value="REQUIRE SPECTACLE" <?php if ($nm > 0) {
                                        echo "checked='checked'";
                                    } ?> onclick="Pequire_Spectacle(<?php echo $Registration_ID; ?>)">
                                    <label for="Optical_Option"><b>REQUIRE SPECTACLE</b></label>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><input type="button" onclick="associated_doctor_dialogy()" value="ASSOCIATED DOCTORS" class="art-button-green pull-right"/></td>
                </tr>
                <tr>
                    <td style="text-align:right;">Others</td><td><textarea style='width: 88%;resize: none;' type='text' readonly='readonly' class='otherconstype'  ><?php echo $Othrs; ?></textarea>
                        <input type='button'  value='Select'  class='art-button-green' onclick="getItem('Others')">
                         <?php echo $Others ?>
                    </td>
                </tr>
            </table>
        </center>
    </div>
</div>

<div id="store_death_discharged_info" style="display:none">
    <table class="table">
            <tr>
                <td style="width:50%">
                    <input   type="text" readonly="readonly" id="death_date_time" placeholder="Enter Death Time"/>
                </td>
            </tr>
            
            <tr>
                <td style="height:230px!important;overflow: scroll">
                    <table class="table table-condensed" style="width:100%!important">
                        <tr>
                            <td>
                                <table style="width: 100%">
                                    <td>
                                        <input type="text"id="disease_name" onkeyup="search_disease_c_death(this.value),clear_other_input('disease_code')" placeholder="----Search Disease Name----" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" id="disease_code" onkeyup="search_disease_c_death(this.value),clear_other_input('disease_name')" placeholder="----Search Disease Code----" class="form-control">
                                    </td>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=""><b>Select Disease Caused Death</b></td>
                        </tr>
                        <tbody id="disease_suffred_table_selection">
                        <?php
                            $deceased_diseases=mysqli_query($conn,"SELECT * FROM tbl_disease WHERE disease_version='$configvalue_icd10_9' LIMIT 5");
                            while($row=mysqli_fetch_assoc($deceased_diseases)){
                                extract($row);
                                                    $disease_id="{$disease_ID}";
                            echo "<tr><td><label style='font-weight:normal'><input type='checkbox' onclick='add_death_reason(\"$disease_id\")' value='{$disease_name}'>{$disease_name} ~~<b>{$disease_code}</b></label></td></tr>";
                            }
			            ?>
                        </tbody>
                    </table>
                </td>	
                <td colspan="2">
                    <table class="table">
                        <tr>
                            <td colspan="4"><b>Disease Suffered From/ Leave Blank for others</b>
                            </td>
                        </tr>
                        <tr>
                            <td><b>S/No.</b></td>
                            <td><b>Disease name</b></td>
                            <td><b>Disease code</b></td>
                            <td><b>Remove</b></td>
                        </tr>
                        <tbody id="disease_suffred_table">
                            
                        </tbody>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    Select cause of Death
                </td>
                <td>
                    <select id="course_of_death" style="width:100%!important;">
                        <?php 
                            $sql_select_course_of_death_result=mysqli_query($conn,"SELECT * FROM tbl_deceased_reasons GROUP BY deceased_reasons") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_course_of_death_result)>0){
                                while($d_course_rows=mysqli_fetch_assoc($sql_select_course_of_death_result)){
                                    $deceased_reasons2=$d_course_rows['deceased_reasons'];
                                    
                                            if($deceased_reasons==$deceased_reasons2){
                                                $selected_course="selected='selected'";
                                            }else{
                                                $selected_course=" ";
                                            }
                                    echo "<option value='$deceased_reasons2' $selected_course>$deceased_reasons2</option>";
                                }
                            }
                         ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    Select Doctor Confirm Death
                     <?php 
                 $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
                ?>
                </td>
                <td colspan="2" >
                    <select id="Doctor_confirm_death_name" style="width:100%!important">
                        <option value=""></option>
                         <?php 
                           
                            $sql_select_doctors_result=mysqli_query($conn,"SELECT Employee_Name,Employee_ID FROM tbl_employee WHERE Employee_Type='Doctor' AND Account_Status = 'active'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_doctors_result)>0){
                                while ($doctors_rows=mysqli_fetch_assoc($sql_select_doctors_result)){
                                    $doctor_cd_name=$doctors_rows['Employee_Name'];
                                    $doctor_cd_id=$doctors_rows['Employee_ID'];
                                    $selected="";
                                   if($Employee_ID==$doctor_cd_id)
                                   {
                                      $selected="selected='selected'";
                                   }
                                    echo "<option value='$doctor_cd_name' $selected>$doctor_cd_name</option>";
                                   
                                }
                            }
                          ?>
                    </select>
                </td>
                
            </tr>
            <tr>
                <td colspan="3">
                    <input type="text" id="Discharge_Reason_txt" hidden="hidden">
                    <input type="button" value="Comfirm Dearth" class="art-button-green pull-right" onclick="close_this_dialog()">
                </td>
            </tr>
        </table>
    </div>

    <div id="store_normal_discharged_info" style="display: none;">
        <table class="table">
           
            <tr>
                <td style="height:230px!important;overflow: scroll">
                    <table class="table table-condensed" style="width:100%!important">
                        <tr>
                            <td>
                                <table style="width: 100%">
                                    <td>
                                        <input type="text"id="disease_name" onkeyup="search_disease_normal(this.value),clear_other_input('disease_code')" placeholder="----Search Disease Name----" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" id="disease_code" onkeyup="search_disease_normal(this.value),clear_other_input('disease_name')" placeholder="----Search Disease Code----" class="form-control">
                                    </td>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=""><b>Select discharge diagnosis </b></td>
                        </tr>
                        <tbody id="disease_normal_table_selection">
                        <?php
                            $dischargestatus ='alive';
                            $deceased_diseases=mysqli_query($conn,"SELECT * FROM tbl_disease WHERE disease_version='$configvalue_icd10_9' LIMIT 5");
                            while($row=mysqli_fetch_assoc($deceased_diseases)){
                                extract($row);
                                $disease_id="{$disease_ID}";
                            echo "<tr><td><label style='font-weight:normal'><input type='checkbox' onclick='add_normal_reason(\"$disease_id\", \"$dischargestatus\")' value='{$disease_name}'>{$disease_name} ~~<b>{$disease_code}</b></label></td></tr>";
                            }
			            ?>
                        </tbody>
                    </table>
                </td>	
                <td colspan="2">
                    <table class="table">
                        <tr>
                            <td colspan="4"><b>Patient Suffered From</b>
                            </td>
                        </tr>
                        <tr>
                            <td><b>S/No.</b></td>
                            <td><b>Disease name</b></td>
                            <td><b>Disease code</b></td>
                            <td><b>Remove</b></td>
                        </tr>
                        <tbody id="disease_table_normal">
                            
                        </tbody>
                        </tr>
                    </table>
                </td>
            </tr>
           
            <tr>
                <td colspan="3">
                    <input type="button" value="Comfirm Discharge Diagnosis" class="art-button-green pull-right" onclick="close_this_discharge_dialog()">
                </td>
            </tr>
        </table>
    </div>
<!-- <input type="text" id="Registration_ID" hidden="hidden"> -->
<input type="text" id="Admision_ID" hidden="hidden">
<?php
    $sql="SELECT Patient_Name, Registration_ID FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'";
    $query=mysqli_query($conn,$sql);
    while($row=mysqli_fetch_assoc($query)){
        $Patient_Name=$row['Patient_Name'];
        $Registration_ID = $row['Registration_ID'];
    }
    ?>
<input type="hidden" id="Patient_Name" value="<?php echo $Patient_Name;?>">
<input type="hidden" id="consultation_ID" value="<?php echo $consultation_ID;?>">
<input type="hidden" id="Registration_ID" value="<?php echo $Registration_ID;?>">
<div id="Previous_History"></div>
<script src="js/JsFunctions.js"></script>

<script>

        function examination_of_operated_eye_dialog(Registration_ID){
            
            var consultation_ID=$("#consultation_ID").val();
            var Patient_Name=$("#Patient_Name").val();
            //alert(consultation_ID)
            $.ajax({
                type:'post',
                url: 'examination_of_operated_eye.php',
                data : {Registration_ID:Registration_ID,
                        consultation_ID:consultation_ID
                },
                success : function(data){
                    $('#optical_inpatient_section').dialog({
                        autoOpen:true,
                        width:'85%',
                        position: ['center',0],
                        title:'Patient Name :  '+Patient_Name,
                        modal:true
                       
                    });  
                    $('#optical_inpatient_section').html(data);
                    $('#optical_inpatient_section').dialog('data');
                }
            })
        }
</script>