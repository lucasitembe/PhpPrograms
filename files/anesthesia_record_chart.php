<?php
                  /*+++++++++++++++++++ Designed and implimented 
        by         Eng. Meshack moscow MSK Since 2019-11-13++++++++++++++++++++++++++*/
                include("./includes/header.php");
                include("./includes/connection.php");
                if (!isset($_SESSION['userinfo'])) {
                    @session_destroy();
                    header("Location: ../index.php?InvalidPrivilege=yes");
                }
                if (isset($_GET['Registration_ID'])) {
                    $Registration_ID = $_GET['Registration_ID'];
                } else {
                    $Registration_ID = 0; 
                }

                if (isset($_GET['consultation_ID'])) {
                    $consultation_ID = $_GET['consultation_ID']; 
                } else {
                    $consultation_ID = 0;
                }
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                $Payment_Date_And_Time = '(SELECT NOW())';
                $Receipt_Date = Date('Y-m-d');
                $Transaction_status = 'pending';
                $Transaction_type = 'indirect cash';
                if ($payment_method == 'Cash') {
                    $Billing_Type = 'Inpatient Cash';
                } else {
                    $Billing_Type = 'Inpatient Credit';
                }
                $Folio_Number=0;
                $branch_id = $_SESSION['userinfo']['Branch_ID'];

                $sql_select_list_of_patient_sent_to_cashier_result=mysqli_query($conn,"SELECT pr.Phone_Number,pr.Registration_ID,pr.Patient_Name,pr.Date_Of_Birth,pr.Gender,pr.Sponsor_ID FROM tbl_patient_registration pr WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_list_of_patient_sent_to_cashier_result)>0){
                $count_sn=1;
                while($patient_list_rows=mysqli_fetch_assoc($sql_select_list_of_patient_sent_to_cashier_result)){
                $Registration_ID=$patient_list_rows['Registration_ID'];
                $Patient_Name=$patient_list_rows['Patient_Name'];
                $Date_Of_Birth=$patient_list_rows['Date_Of_Birth'];
                $Gender=$patient_list_rows['Gender'];
                $Sponsor_ID=$patient_list_rows['Sponsor_ID'];
                //         $Payment_Date_And_Time=$patient_list_rows['Payment_Date_And_Time'];
                //         $Payment_Cache_ID=$patient_list_rows['Payment_Cache_ID'];
                $PhoneNumber = $patient_list_rows['Phone_Number'];
                        
                //filter only patient with active or approved item
                //         $sql_select_active_or_approved_item_result=mysqli_query($conn,"SELECT Status FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID' AND Status IN('active','approved')") or die(mysqli_error());
                //         if(mysqli_num_rows($sql_select_active_or_approved_item_result)<=0){
                //             
                //         } 
                        $date1 = new DateTime($Today);
                $date2 = new DateTime($Date_Of_Birth);
                $diff = $date1 -> diff($date2);
                $age = $diff->y." Years, ";
                $age .= $diff->m." Months, ";
                $age .= $diff->d." Days";

                //sql select payment sponsor
                $Guarantor_Name_row=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name,payment_method FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'"));
                $Guarantor_Name=$Guarantor_Name_row['Guarantor_Name'];
                $payment_method=$Guarantor_Name_row['payment_method'];
                }
                }

                $anasthesia_record_id = mysqli_fetch_assoc(mysqli_query($conn,"SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress' ORDER BY anasthesia_record_id DESC LIMIT 1"))['anasthesia_record_id'];
               
                $select_pulmonary_desease_result = mysqli_query($conn, "SELECT * FROM tbl_pulmonary_disease pd WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));

                $select_metabolic_disease_result = mysqli_query($conn, "SELECT * FROM tbl_metabolic_disease md WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
                
                $select_gastrointestinal_result = mysqli_query($conn, "SELECT * FROM tbl_anasthesial_gastrointestinal_disease gd WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
                $select_renal_desease_result = mysqli_query($conn, "SELECT * FROM tbl_anasthesia_renalto_medication rm WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
                $select_pediatric_desease_result = mysqli_query($conn, "SELECT * FROM tbl_anasthesia_pediatric ap WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                $select_generaExam_result = mysqli_query($conn, "SELECT * FROM tbl_anasthesia_genaral_examination ge WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
               
                $select_premedication_result = mysqli_query($conn, "SELECT * FROM tbl_anasthesia_premedication ap WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));

                $select_combined_result = mysqli_query($conn, "SELECT * FROM anasthesia_combined_assessment ca WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
                // $select_asa_result = mysqli_query($conn, "SELECT * FROM tbl_anasthesia_asa_classfication ac WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                $select_investigation_result = mysqli_query($conn, "SELECT * FROM tbl_anasthesia_investigation ai WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
                $select_nerve_block_procedure = mysqli_query($conn, "SELECT * FROM tbl_anaesthesia_nerve_block_procedure bp WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
                $select_nerve_block_outcomes= mysqli_query($conn, "SELECT * FROM tbl_never_block_outcome bo WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
                // $select_vitals= mysqli_query($conn, "SELECT * FROM pre_post_vitals pv WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
                $select_cannulation= mysqli_query($conn, "SELECT * FROM tbl_cannulation_technic_intubation ti WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
                $select_vent = mysqli_query($conn, "SELECT * FROM tbl_anaesthesia_vent av WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' order by Vent_ID DESC ") or die(mysqli_error($conn));
                $select_end_of_anaesthesia=mysqli_query($conn, "SELECT * FROM tbl_end_of_anaesthesia WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
                $Select_intraop_record=mysqli_query($conn, "SELECT * FROM tbl_intraop_record WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
               
                
               ?>
                <style>
                    .pre_asses{
                        font-size: 10px;
                    }
                    .rows_list{ 
                        cursor: pointer; 
                    }
                    .rows_list:active{
                        color: #328CAF!important;
                        font-weight:normal!important;
                    }
                    .rows_list:hover{
                        color:#00416a;
                        background: #dedede;
                        font-weight:bold;
                    }
                    a{
                        text-decoration: none;
                    }
                    
                input[type="checkbox"]{
                    width: 25px; 
                    height: 25px;
                    cursor: pointer;
                    margin: 5px;
                    margin-right: 5px;
                }

                .pre_asses input[type="radio"]{
                    width: 15px; 
                    height: 15px;
                    cursor: pointer;
                    margin: 5px;
                    margin-right: 5px;
                }
                 input[type="radio"]{
                    width: 25px; 
                    height: 25px;
                    cursor: pointer;
                    margin: 5px;
                    margin-right: 5px;
                }

                /* #th{
                    background:#99cad1;
                } */
            </style>
                
                <a href="anesthesia_record_bydate.php?Registration_ID=<?= $Registration_ID ?>" class='art-button-green' style="width: 250px;">PREVIOUS ANAESTHESIA RECORD </a>
                <!--<?php //if(isset($_GET['NURSECOMMUNICATION'])){ ?>
                    <a href="nursecommunicationpage.php?Registration_ID=<?php echo $Registration_ID; ?>&Admision_ID=<?php echo $_GET['Admision_ID']; ?>&consultation_ID=<?php echo $_GET['consultation_ID']; ?>" class='art-button-green'>BACK</a>
                <?php // }else{?>
                    <a href="doctorspageinpatientwork.php?Registration_ID=<?php echo $Registration_ID; ?>&consultation_ID=<?php echo  $consultation_ID; ?>"     class='art-button-green' >
                        BACK
                    </a> -->
                <a href="#" onclick="history.go(-1)" class="art-button-green">BACK</a>    
                <script>
                    
                    function goBack(){
                        window.history.back();
                    }
                </script>
                    
                <?php //}
                    $sql_select_admission_information_result=mysqli_query($conn,"SELECT TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay,ad.Admission_Date_Time, hp.Hospital_Ward_Name, ad.Bed_Name FROM tbl_admission ad, tbl_hospital_ward hp WHERE ad.Hospital_Ward_ID = hp.Hospital_Ward_ID AND Registration_ID='$Registration_ID' AND Admission_status ='admitted'") or die(mysqli_error($conn));
                    if(mysqli_num_rows($sql_select_admission_information_result)>0){
                        while($admsn_dtl_rows= mysqli_fetch_assoc($sql_select_admission_information_result)){
                            $NoOfDay=$admsn_dtl_rows['NoOfDay'];
                            $Admission_Date_Time=$admsn_dtl_rows['Admission_Date_Time'];
                            $Hospital_Ward_Name=$admsn_dtl_rows['Hospital_Ward_Name'];
                            $Bed_Name=$admsn_dtl_rows['Bed_Name'];
                        }
                    }
                ?>
                
                <fieldset id="anasthesia_record_form" style="height:800px;overflow-y: scroll">
                <legend align="center" style='padding:10px; color:yellow; background-color:#2D8AAF; text-align:center'><b><u>ANESTHESIA RECORD CHART</u></b><br>
                <?= $Registration_ID ?> | <?= $Patient_Name ?> | <?= $Gender ?> | <?= $age ?><br>
                Admission : <?= $Admission_Date_Time ?> | Ward: <?= $Hospital_Ward_Name ?>.
                </legend>
    <table class="" style="width:100%">
        <tr>
            <td>DIAGNOSIS</td>
               
            <td>
                <span class="col-md-8">
                    <textarea id='Diagnosis' name='Diagnosis' placeholder="Diagnosis"></textarea>
                </span>
                <span class="col-md-4">
                    <input type="button" name="Diagnosis_List" id="Diagnosis_List" value="SELECT FROM LIST" class="art-button-green pull-right" onclick="open_diagnosis_dialog()">
                </span>
            </td>            
            <td>PROPOSED PROCEDURE</td>
                
            <td>
                <span class="col-md-8">
                    <textarea class='form-control'id="proposed_procedure"></textarea>
                </span>
                <span class="col-md-4">
                    <input type="button" name="proposed_procedure"  value="SELECT FROM LIST" class="art-button-green pull-right" onclick="ajax_procedure_dialog_open()">
                </span>
            </td>
        </tr>
        <tr>
            <td>SURGEON(S)</td>
            <td>
                <span class="col-md-8">
                        <textarea name="surgeon" id="surgeon"  class="form-control"></textarea>
                </span>
                <span class="col-md-4">
                        <input type="button" Value="SELECT FROM LIST" class="art-button-green pull-right" onclick="open_surgeon_dialog()">
                </span>
            </td>  
            <td>ASSISTANT SURGEON(S)</td>
            <td>
                <span class="col-md-8">
                        <textarea name="assistant_surgeon" id="assistant_surgeon"  class="form-control"></textarea>
                </span>
                <span class="col-md-4">
                        <input type="button" Value="SELECT FROM LIST" class="art-button-green pull-right" onclick="open_assistant_surgeon_dialog()">
                </span>
            </td>         
            
        </tr>
       
        <tr>
            <td>ANESTHETIST ('s) </td>
            <td>
                <span class="col-md-8">
                        <textarea name="surgeon"  id="anesthetist"  class="form-control"></textarea>
                </span>
                <span class="col-md-4">
                        <input type="button" Value="SELECT FROM LIST" class="art-button-green pull-right" onclick="open_anesthesia_anesthetist_dialog()">
                </span>
            </td>
            <td>ASSISTANT ANESTHETIST ('s) </td>
            <td>
                <span class="col-md-8">
                        <textarea name="assistant_anesthetist"  id="assistant_anesthetist"  class="form-control"></textarea>
                </span>
                <span class="col-md-4">
                        <input type="button" Value="SELECT FROM LIST" class="art-button-green pull-right" onclick="open_anesthesia_assist_anesthetist_dialog()">
                </span>
            </td>
                   
            
        </tr>
    </table>
    <form  method="">
        <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
        <input type="text" hidden  name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID?>" >

    <?php 
                if((mysqli_num_rows($select_combined_result))>0){
                    while($combined_row = mysqli_fetch_assoc($select_combined_result)){ 
                        $checked_yes = "";
                        $checked_no = "";
            ?>
    
    <table class="table">
    <tr>
        <th colspan="2" style="background: #dedede;" id="th">
            <h4>PREOPERATIVE ASSESSMENT:(This is to be discussed with: Relatives, Parent, Others)</h4>
        </th>
    </tr>
     
    <tr>
        <td>
            <span class="col-md-3">
                <label for=""><h4>Significant Medical History</h4></label>
            </span>
            <span class="col-md-9">
                <textarea name="significant_history" id="significant_history" rows="1"  class="form-control"><?php echo $combined_row['significant_history'];?></textarea>
            </span>
        </td>
        <td>
            <span class="col-md-3">
                <label for=""><h4>Past medications</h4></label>
            </span>
            <span class="col-md-9">
                <textarea name="Past_history" id="Past_history" rows="1"  class="form-control"><?php echo $combined_row['past_history'];?></textarea>
            </span>
        </td>
    </tr>
    <tr>
    <td>
            <span class="col-md-3">
                <label for=""><h4>Family History</h4></label>
            </span>
            <span class="col-md-9">
                <textarea name="family_history" id="family_history" rows="1"  class="form-control"><?php echo $combined_row['family_history'];?></textarea>
            </span>
        </td>
        <td>
            <span class="col-md-3">
                <label for=""><h4>Allergies</h4></label>
            </span>
            <span class="col-md-9">
                <textarea name="allergies" id="allergies"   class="form-control"><?php echo $combined_row['allergies'];?></textarea>
            </span>
        </td>
    </tr>
    <tr>
        <td>
            <span class="col-md-3">
                <label for=""><h4>Social History</h4></label>
            </span>
            <span class="col-md-9">
                <input name="social_history" id="social_history"   class="form-control" value="<?php echo $combined_row['social_history'];?>">
            </span>
        </td>
        <td>
            <span class="col-md-3">
                <label for=""><h4>Nutritional Status</h4></label>
            </span>
            <span class="col-md-9">
                <input name="nutritional_status" id="nutritional_status"   class="form-control" value="<?php echo $combined_row['nutritional_status'];?>">
            </span>
        </td>
    </tr>
    <tr>
        <td>
            <span class="col-md-3">
                <label for=""><h4>Past Anaesthesia History</h4></label>
            </span>
            <span class="col-md-9">
                <input name="past_anaesthesia_history" id="past_anaesthesia_history"   class="form-control" value="<?php echo $combined_row['past_anaesthesia_history'];?>">
            </span>
        </td>
        
    </tr>
    <tr>
    </tr>
    </table>
    <table class="table">
    <tr>
        <td class="pre_asses">  <b>Cardiac disease: Angina</b>
        <?php 
        $checked_yes="";
        $$checked_no="";
        if($combined_row['Cardiac_angina'] == "Yes"){
            $checked_yes="checked='checked'";
        }else if($combined_row['Cardiac_angina'] == "No"){
            $checked_no ="checked='checked'";
        }
        ?>
            <span>
                <label for="">Yes</label>
                <input type="radio" name="Cardiac_angina" value="YES" id="Cardiac_angina" <?php echo $checked_yes;?> >
            </span>
            <span>
                <label for="">No</label>
                <input type="radio" name="Cardiac_angina" value ="NO" id="Cardiac_angina_no" <?php echo  $checked_no; ?>>
            </span>
        </td>
            <td class="pre_asses"> <b>Valvular Disease:</b>
            <?php   
                    $checked_yes="";
                    $$checked_no="";
                if($combined_row['Cardiac_valvular_disease'] == "Yes"){
                    $checked_yes="checked='checked'";
                }else if($combined_row['Cardiac_valvular_disease'] == "No"){
                    $checked_no ="checked='checked'";
                }
            ?>
                <span>
                    <label for="">Yes</label>
                    <input type="radio" name="Cardiac_valvular_disease" id="Cardiac_valvular_disease" value="YES" <?php echo $checked_yes;?>>
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" name="Cardiac_valvular_disease"  value="NO" <?php echo $checked_no; ?>>
                </span>
            </td>
            <td class="pre_asses"> <b>Arrhythmias:</b>
            <?php 
            
                $checked_yes_arr="";
                $$checked_no_arr="";
                if($combined_row['Cardiac_arrhythias'] == "Yes"){
                    $checked_yes_arr="checked='checked'";
                }else if($combined_row['Cardiac_arrhythias'] == "No"){
                    $checked_no_arr ="checked='checked'";
                }
            ?>
                <span>
                    <label for="">Yes</label>
                    <input type="radio" name="Cardiac_arrhythias" id="Cardiac_arrhythias" value="YES" <?php echo $checked_yes_arr;?>>
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" name="Cardiac_arrhythias"  value="NO" <?php echo $checked_no_arr; ?>>
                </span>
            </td>
            <td class="pre_asses"> <b>Heart failure:</b>
                <?php $checked_yes_heart="";
                    $$checked_no_heart="";
                    if($combined_row['Cardiac_heart_failure'] == "Yes"){
                        $checked_yes_heart="checked='checked'";
                    }else if($combined_row['Cardiac_heart_failure'] == "No"){
                        $checked_no_heart ="checked='checked'";
                    }
                ?>
                <span>
                    <label for="">Yes</label>
                    <input type="radio" name="Cardiac_heart_failure" id="heart_failure_yes" value="YES" <?php echo $checked_yes_heart;?>>
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" name="Cardiac_heart_failure" id="heart_failure_no" value="NO" <?php echo $checked_no_heart;?>>
                </span>
            </td>
            <td colspan="2" class="pre_asses"> <b>Peripheral vascular disease:</b>
            <?php   $checked_yes_per="";
                    $$checked_no_per="";
                    if($combined_row['Cardiac_ph_vascular_disease'] == "Yes"){
                        $checked_yes_per="checked='checked'";
                    }else if($combined_row['Cardiac_ph_vascular_disease'] == "No"){
                        $checked_no_per ="checked='checked'";
                    }
                ?>
                <span>
                    <label for="">Yes</label>
                    <input type="radio" name="Cardiac_ph_vascular_disease" id="ph_vascular_disease_yes" value="YES" <?php echo $checked_yes_per;?>>
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" name="Cardiac_ph_vascular_disease" id="ph_vascular_disease_no"  value="NO" <?php echo $checked_no_per;?>>
                </span>
            </td>
            <td  class="pre_asses"> <b>HTN:</b>
            <?php $checked_yes_htn="";
                    $checked_no_htn="";
                    if($combined_row['Cardiac_htn'] == "Yes"){
                        $checked_yes_htn="checked='checked'";
                    }else if($combined_row['Cardiac_htn'] == "No"){
                        $checked_no_htn ="checked='checked'";
                    }
                ?>
                <span>
                    <label for="">Yes</label>
                    <input type="radio" name="Cardiac_htn" id="htn_yes" value="YES" <?php echo $checked_yes_htn;?>>
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" name="Cardiac_htn" id="htn_no" value="NO" <?php echo $checked_no_htn;?>>
                </span>
            </td>
        </tr>
        <tr class="border-less">
            <td><b>Other Details</b></td>
            <td colspan="4"> 
                <?php echo $combined_row['Cardiac_other_details'];?>
                
            </td>
            <td colspan="2" class="pre_asses"> 
                <?php 
                if($combined_row['consent_signed'] =="Yes"){
                    $checked_yes_consent ="checked ='checked'";
                }else if($combined_row['consent_signed'] =="No"){
                    $checked_no_consent ="checked = 'checked'";
                }

                $consertform = $combined_row['consertform'];
                ?>              
                <span class="col-md-6"> <b>CONSENT SIGNED:</b>
                    <label for="">Yes</label>
                     <input type="radio" id="consent_signed_yes"  style="display:inline;" name="consent_signed" value="YES" <?php echo $checked_yes_consent;?>>
                </span>
                   <?php if($consertform !=''){?> 
                        <span class='col-md-3 rows_list' >
                            <a href='<?php echo 'attachment/'.$consertform; ?>' target='blank'>View Consent form</a>
                        </span>   
                   <?php } ?>         
                <span class="col-md-3" >
                    <label for="">No</label>
                    <input type="radio" name="consent_signed" id="consent_signed_no"  style="display:inline;" value="NO" <?php echo $checked_no_consent;?>>
                </span>
            </td> 
        </tr>
        <?php }
                }else{?>
            <td></td>
           <td></td>           
            
        </tr>
    </table>
    <br>
    <table class="table">
    <tr>
        <th colspan="2" style="background: #dedede;" id="th">
            <h4>PREOPERATIVE ASSESSMENT:(This is to be discussed with: Mother/Father/Guardian/Patient)</h4>
        </th>
    </tr>
      <tr>
        <td>
            <span class="col-md-3">
                <label for=""><h4>Significant Medical History</h4></label>
            </span>
            <span class="col-md-9">
                <textarea name="significant_history" id="significant_history" rows="1"  class="form-control"></textarea>
            </span>
        </td>
        <td>
            <span class="col-md-3">
                <label for=""><h4>Past Medication</h4></label>
            </span>
            <span class="col-md-9">
                <textarea name="past_history" id="past_history" rows="1"  class="form-control"></textarea>
            </span>
        </td>
    </tr>
    <tr>
    <td>
            <span class="col-md-3">
                <label for=""><h4>Family History</h4></label>
            </span>
            <span class="col-md-9">
                <textarea name="family_history" id="family_history" rows="1"  class="form-control"></textarea>
            </span>
        </td>
        <td>
            <span class="col-md-3">
                <label for=""><h4>Allergies</h4></label>
            </span>
            <span class="col-md-9">
                <input name="allergies" id="allergies"   class="form-control">
            </span>
        </td>
    </tr>
    <tr>
        <td>
            <span class="col-md-3">
                <label for=""><h4>Social History</h4></label>
            </span>
            <span class="col-md-9">
                <input name="social_history" id="social_history"   class="form-control" value="">
            </span>
        </td>
        <td>
            <span class="col-md-3">
                <label for=""><h4>Nutritional Status</h4></label>
            </span>
            <span class="col-md-9">
                <input name="nutritional_status" id="nutritional_status"   class="form-control" value="">
            </span>
        </td>
    </tr>
    <tr>
        <td>
            <span class="col-md-3">
                <label for=""><h4>Past Anaesthesia History</h4></label>
            </span>
            <span class="col-md-9">
                <input name="past_anaesthesia_history" id="past_anaesthesia_history"   class="form-control" value="<?php echo $combined_row['past_anaesthesia_history'];?>">
            </span>
        </td>
        
    </tr>
    </table>
    <table class="table">
    <tr>
        <td class="pre_asses">Cardiac disease: Angina
            <span >
                <label for="">Yes</label>
                <input type="radio" name="Cardiac_angina" value="" id="Cardiac_angina_yes" >
            </span>
            <span>
                <label for="">No</label>
                <input type="radio" name="Cardiac_angina" value ="" id="Cardiac_angina_no">
            </span>
        </td>
            <td class="pre_asses">Valvular Disease:
                <span>
                    <label for="">Yes</label>
                    <input type="radio" name="Cardiac_valvular_disease" id="Cardiac_valvular_disease_yes" value="YES" >
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" name="Cardiac_valvular_disease" id="Cardiac_valvular_disease_no" value="NO" >
                </span>
            </td>
            <td class="pre_asses">Arrhythmias
                <span>
                    <label for="">Yes</label>
                    <input type="radio" name="Cardiac_arrhythias_yes" id="Cardiac_arrhythias_yes" value="YES">
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" name="Cardiac_arrhythias_yes"  value="NO" id='Cardiac_arrhythias_no'>
                </span>
            </td>
            <td class="pre_asses">Heart failure
                <span>
                    <label for="">Yes</label>
                    <input type="radio" name="Cardiac_heart_failure" id="Cardiac_heart_failure_yes" value="" >
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" name="Cardiac_heart_failure" id="Cardiac_heart_failure_no" value="" >
                </span>
            </td>
            <td class="pre_asses">Peripheral vascular disease:
                <span>
                    <label for="">Yes</label>
                    <input type="radio" name="Cardiac_ph_vascular_disease" id="Cardiac_ph_vascular_disease_yes" value="" >
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" name="Cardiac_ph_vascular_disease" id="Cardiac_ph_vascular_disease_no"  value="">
                </span>
            </td class="pre_asses">
            <td colspan="3" class="pre_asses">HTN
                <span>
                    <label for="">Yes</label>
                    <input type="radio" name="Cardiac_htn" id="Cardiac_htn_yes" value="YES">
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" name="Cardiac_htn" id="Cardiac_htn_no" value="NO">
                </span>
            </td>
            
        </tr>
        <tr class="border-less">
            <td colspan="3"> 
                <span >Other Details
                    <textarea name="Cardiac_other_details" id="Cardiac_other_details" style="display:inline; width:65%;"  rows="1" class="form-control"  ></textarea>
                </span>
            </td>
            <td colspan="2" class="pre_asses">  
                <span class="col-md-4"><b>CONSENT SIGNED: </b>
                    <label for="">Yes</label>
                     <input type="radio" id="consent_signed_yes"  style="display:inline;" name="consent_signed" value="YES" >
                     
                </span>    
                <span class="col-md-6" id='uploadfile' >
                    <label for="">Attach consent form</label>
                    <input type="file" name="" id="upload_consentform" style="display:inline;">
                </span>            
                <span class="col-md-2" >
                    
                    <label for="">No</label>
                    <input type="radio" name="consent_signed" style="display:inline;" id="consent_signed_no" value="NO" >
                </span>
            </td>
            <td>
                <button type="button" class="btn btn-primary" style="width: 250px;"  onclick="save_pre_anaesthetic_visits(<?php echo $Registration_ID; ?>)">Save</button>
            </td>
        </tr>
        <?php }?>
    </table>
    <hr>
    <br>
    </form>

    <form action="ajax_anasthesia_record_information.php" method="POST">
            <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
            <input type="text" hidden  name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID?>" >
            
            <table class="table">
            <tr><th colspan="6" id="th">Pulmonary disease: </th></tr>
            <?php if((mysqli_num_rows($select_pulmonary_desease_result))>0){ while($premonary_row= mysqli_fetch_assoc($select_pulmonary_desease_result)){ $checked_yes=""; $checked_no="";?>
            <tr>
                <td>Asthma:
                    <?php
                        if($premonary_row['asthma']=="Yes"){
                            $checked_yes= "checked = 'checked'";
                        }else if($premonary_row['asthma']=="No"){
                            $checked_no= "checked= 'checked'";
                        }
                    ?>
                    <span>
                        <label for="">Yes</label>
                        <input type="radio" name="asthma" value="YES" <?php echo $checked_yes;?>>
                    </span>
                    <span>
                        <label for="">No</label>
                        <input type="radio" name="asthma" value ="NO" <?php echo $checked_no;?> >
                    </span>
                </td>
                <td>COPD:
                    <?php
                        if($premonary_row['copd']=="Yes"){
                            $checked_yes= "checked = 'checked'";
                        }else  if($premonary_row['copd']=="No"){
                            $checked_no= "checked= 'checked'";
                        }
                    ?>
                    <span>
                        <label for="">Yes</label>
                        <input type="radio" name="copd"  value="YES" <?php echo $checked_yes;?> >
                    </span>
                    <span>
                        <label for="">No</label>
                        <input type="radio" name="copd"  value="NO" <?php echo $checked_no;?>  >
                    </span>
                </td>
                <td>Smoking:

                    <?php
                        if($premonary_row['smoking']=="Yes"){
                            $checked_yes= "checked = 'checked'";
                        }else if($premonary_row['smoking']=="No"){
                            $checked_no= "checked= 'checked'";
                        }
                    ?>
                    <span>
                        <label for="">Yes</label>
                        <input type="radio" name="smoking" value="YES" <?php echo $checked_yes;?> >
                    </span>
                    <span>
                        <label for="">No</label>
                        <input type="radio" name="smoking"  value="No" <?php echo $checked_no;?> >
                    </span>
                </td>
                <td>Recent URTI:
                    <?php
                        if($premonary_row['recent_urti']=="Yes"){
                            $checked_yes= "checked = 'checked'";
                        }else if($premonary_row['recent_urti']=="No"){
                            $checked_no= "checked= 'checked'";
                        }
                    ?>
                    <span>
                        <label for="">Yes</label>
                        <input type="radio" name="recent_urti"  value="YES" <?php echo $checked_yes;?>>
                    </span>
                    <span>
                        <label for="">No</label>
                        <input type="radio" name="recent_urti"  value="NO" <?php echo $checked_no;?> >
                    </span>
                </td>
                <td > Other Details
                    <span >
                        <textarea name="pulmonary_details"  rows="1"  class="form-control" style="display:inline; width:80%" ><?php echo $premonary_row['pulmonary_details']; ?></textarea>
                    </span>
                </td>
                <td><button class="btn btn-success pull-right" disabled name="pulmonary" onclick="pulmonary_disease()">DATA SAVED</button></td>
            </tr>
                
            <?php }}else{?>
                <tr>
                <td>Asthma:
                    <span>
                        <label for="">Yes</label>
                        <input type="radio" name="asthma" id="asthma_yes" value="YES"  >
                    </span>
                    <span>
                        <label for="">No</label>
                        <input type="radio" name="asthma" id="asthma_no" value ="NO"  >
                    </span>
                </td>
                <td>COPD:
                    <span>
                        <label for="">Yes</label>
                        <input type="radio" name="copd" id="copd_yes" value="YES" >
                    </span>
                    <span>
                        <label for="">No</label>
                        <input type="radio" name="copd" id="copd_no" value="NO" >
                    </span>
                </td>
                <td>Smoking
                    <span>
                        <label for="">Yes</label>
                        <input type="radio" name="smoking" id="smoking_yes" value="YES" >
                    </span>
                    <span>
                        <label for="">No</label>
                        <input type="radio" name="smoking"  id="smoking_nno" value="No" >
                    </span>
                </td>
                <td>Recent URTI
                    <span>
                        <label for="">Yes</label>
                        <input type="radio" name="recent_urti"  id="recent_urti_yes" value="YES" >
                    </span>
                    <span>
                        <label for="">No</label>
                        <input type="radio" name="recent_urti" id="recent_urti_no" value="NO">
                    </span>
                </td>
                <td > Other Details
                    <span >
                        <textarea name="pulmonary_details"  rows="1" id="pulmonary_details"  class="form-control" style="display:inline; width:80%" ></textarea>
                    </span>
                </td>
                <td><input type="button" class="art-button-green pull-right"  onclick="pulmonary_disease()" value="Save"></td>
            </tr>
                
            <?php } ?>
            </table>
    </form>
    <hr>
    
    <form action="ajax_anasthesia_record_information.php" method="POST">
            <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
            <input type="text" hidden  name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID?>" >
        <table class="table">
                <thead>
                    <tr><th colspan="5" id="th">Endocrine /Metabolic Diseases:</th></tr>
                </thead>
                <tbody>
                <?php 
                if((mysqli_num_rows($select_metabolic_disease_result))>0){while($metabolic_row = mysqli_fetch_assoc($select_metabolic_disease_result)){
                    $checked_yes=""; $checked_no="";
                ?>
                    <tr>
                    <td>Diabetes Mellitus:
                    <?php
                        if($metabolic_row['diabetes_mellitus'] =="Yes"){
                            $checked_yes= "checked = 'checked'";
                        }else{
                            $checked_no= "checked= 'checked'";
                        }
                    ?>
                        <span>
                            <label for="">Yes</label>
                            <input type="radio" name="diabetes_mellitus" value="YES"  <?php echo $checked_yes;?> >
                        </span>
                        <span>
                            <label for="">No</label>
                            <input type="radio" name="diabetes_mellitus" value ="NO" <?php echo $checked_no;?>  >
                        </span>
                    </td>
                    <td>Pregnancy:
                    <?php
                        if($metabolic_row['pregnancy']=="Yes"){
                            $checked_yes= "checked = 'checked'";
                        }else{
                            $checked_no= "checked= 'checked'";
                        }
                    ?>
                        <span>
                            <label for="">Yes</label>
                            <input type="radio" name="pregnancy"  value="YES"<?php echo $checked_yes;?>   >
                        </span>
                        <span>
                            <label for="">No</label>
                            <input type="radio" name="pregnancy"  value="NO" <?php echo $checked_no;?>  >
                        </span>
                    </td>
                    <td>
                        <span class="form-inline">
                            <label for="">Gestation weeks</label>
                            <input type="number" name="gestation_week" class="form-control form-inline" disabled value="<?php echo $metabolic_row['gestation_week'];?>"   >
                        </span>
                        
                    </td>
                    <td > Other Details
                        <span >
                            <textarea name="metabolic_details"  rows="1"  class="form-control" style="display:inline; width:60%" ><?php echo $metabolic_row['metabolic_details'];?>  </textarea>
                        </span>
                    </td>
                    <td><button class="btn btn-success pull-right" disabled name="endocrine_metabolic" width="250px;">DATA SAVED</button></td>
                </tr>
               
                <?php }}else{?>
                    <tr>
                    <td>Diabetes Mellitus:
                        <span>
                            <label for="">Yes</label>
                            <input type="radio" name="diabetes_mellitus" id="diabetes_mellitus_yes" value="YES"  )>
                        </span>
                        <span>
                            <label for="">No</label>
                            <input type="radio" name="diabetes_mellitus" id="diabetes_mellitus_no" value ="NO"  >
                        </span>
                    </td>
                    <td>Pregnancy:
                        <span>
                            <label for="">Yes</label>
                            <input type="radio" name="pregnancy" id="pregnancy_yes"  value="YES" >
                        </span>
                        <span>
                            <label for="">No</label>
                            <input type="radio" name="pregnancy"  id="pregnancy_no" value="NO" >
                        </span>
                    </td>
                    <td>
                        <span class="form-inline">
                            <label for="">Gestation weeks</label>
                            <input type="number" name="gestation_week" id="gestation_week" class="form-control form-inline"  >
                        </span>
                        
                    </td>
                    <td > Other Details
                        <span >
                            <textarea name="metabolic_details"  rows="1" id="metabolic_details"  class="form-control" style="display:inline; width:60%" ></textarea>
                        </span>
                    </td>
                    <td><input type="button" class="art-button-green pull-right" style="width: 250px;" onclick="save_endocrine_metabolic()"  value='Save'></td>
                </tr>

                <?php } ?>
            </tbody>
        </table>
    </form>
    <hr>
    <form action="ajax_anasthesia_record_information.php" method="POST" >
    <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
    <input type="text" hidden  name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID?>" >
    <table class="table">
        <thead>
            <tr><th colspan="4" id="th">Gastrointestinal Diseases:</th></tr>
        </thead>
        <tbody>
        <?php 
        if((mysqli_num_rows($select_gastrointestinal_result))>0){while($gastrointestinal_row= mysqli_fetch_assoc($select_gastrointestinal_result)){
            $liver = $gastrointestinal_row['liver_desease'];
            $alcohol = $gastrointestinal_row['alcohol_consumption'];
            $checked_yes="";
            $checked_no="";
        ?>
            <tr>
            <td>Liver Disease:
           <?php  
            
           if($liver=="Yes"){
               $checked_yes ="checked = 'checked'";
           }else{
               $checked_no = "checked ='checked'";
           }
           
           ?>
                <span>
                    <label for="">Yes</label>
                    <input type="radio" name="liver_desease" value="YES"  <?php echo $checked_yes;?> >
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" name="liver_desease" value ="NO" <?php echo $checked_no; ?> >
                </span>
            </td>
            <td>Alcohol consumption:
                <?php  
                
                if($alcohol=="Yes"){
                    $checked_yes ="checked = 'checked'";
                }else{
                    $checked_no = "checked ='checked'";
                }?>
                <span>
                    <label for="">Yes</label>
                    <input type="radio" name="alcohol_consumption"  value="YES" <?php echo $checked_yes; ?> >
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" name="alcohol_consumption"  value="NO" <?php echo $checked_no; ?> >
                </span>
            </td>
            <td >Other Details 
                <span >
                    <textarea name="gastrointestinal_details"  rows="1" disabled style="display:inline; width:80%"  class="form-control"  ><?php echo $gastrointestinal_row['gastrointestinal_details'];?></textarea>
                </span>
            </td>
            <td><button class="btn btn-success pull-right" disabled name="gastrointestinal">DATA SAVED</button></td>
        </tr> 
        
        <?php }}else{?>
            <tr>
            <td>Liver Disease:
                <span>
                    <label for="">Yes</label>
                    <input type="radio" name="liver_desease" id="liver_desease_yes" value="YES" >
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" name="liver_desease" id="liver_desease_no" value ="NO"  >
                </span>
            </td>
            <td>Alcohol consumption:
                <span>
                    <label for="">Yes</label>
                    <input type="radio" name="alcohol_consumption"  id="alcohol_consumption_yes" value="YES" >
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" name="alcohol_consumption" id="alcohol_consumption_no" value="NO" >
                </span>
            </td>
            <td> Other Details
                <span >
                    <textarea name="gastrointestinal_details" id="gastrointestinal_details" style="display:inline; width:80%"  rows="1"  class="form-control"  ></textarea>
                </span>
            </td>
            <td><input type="button" class="art-button-green pull-right" name="gastrointestinal" onclick="save_gastrointestinal()" style="width: 250px;" value="Save"></td>
        </tr>
        
        <?php } ?>
        </tbody>
    </table><hr>
    </form>
    
    <form action="ajax_anasthesia_record_information.php" method="POST" >
    <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
    <input type="text" hidden  name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID?>" >
        <table class="table" >
            <thead>                
                <tr><th colspan="5" id="th">Pediatric:</th></tr>                
            </thead>
            <tbody>
            <?php if((mysqli_num_rows($select_pediatric_desease_result))>0){
                while($pediatric_row = mysqli_fetch_assoc($select_pediatric_desease_result)){
                    $checked_yes = "";
                    $checked_no = "";
                ?>
                <tr>
                <td>Delivery a term:
                <?php  
                        if($pediatric_row['pediaric_derivery_term'] =="Yes"){
                            $checked_yes ="checked='checked'";
                        }else{
                            $checked_no = "checked='checked'";
                        }
                    ?>    
                    <span>
                        <label for="">Yes</label>
                        <input type="radio" name="pediaric_derivery_term" id="delivery_yes" value="YES" <?php echo $checked_yes; ?> >
                    </span>
                    <span>
                        <label for="">No</label>
                        <input type="radio" name="pediaric_derivery_term" id="derivery_no" value ="NO" <?php echo $checked_no; ?> >
                    </span>
                </td>
                <td>
                    <span class="form-inline">
                        <label for="">Gestation weeks</label>
                        <input type="number" name="gestation_week" id="gestation_week" disabled class="form-control form-inline" value="<?php echo $pediatric_row['gestation_week'];?>" >
                    </span>                
                </td>
                <td>Resuscitation done:
                <?php  
                        if($pediatric_row['resuscitation_done'] =="Yes"){
                            $checked_yes ="checked='checked'";
                        }else if($pediatric_row['resuscitation_done'] =="No"){
                            $checked_no = "checked='checked'";
                        }
                    ?>
                    <span>
                        <label for="">Yes</label>
                        <input type="radio" name="resuscitation_done" id="resuscitation_yes" value="YES" <?php $checked_yes; ?> >
                    </span>
                    <span>
                        <label for="">No</label>
                        <input type="radio" name="resuscitation_done" id="resuscitation_no" value="NO" <?php $checked_no; ?> >
                    </span>
                </td>
                <td > Other Details
                    <span >
                        <textarea name="pediatric_details"  rows="1"  class="form-control" id="pediatric_details" disabled><?php echo $pediatric_row['pediatric_details'];?></textarea>
                    </span>
                </td>
                <td><button class="btn btn-success pull-right" disabled name="pediatric" id="submit" >DATA SAVED</button></td>
                
            </tr>
            
                <?php }}else{?>
                    <tr>
                <td>Delivery a term:
                    <span>
                        <label for="">Yes</label>
                        <input type="radio" name="pediaric_derivery_term" id="pediaric_derivery_term_yes" value="YES"  >
                    </span>
                    <span>
                        <label for="">No</label>
                        <input type="radio" name="pediaric_derivery_term" id="pediaric_derivery_term_no" value ="NO"  >
                    </span>
                </td>
                <td>
                    <span class="form-inline">
                        <label for="">Gestation weeks</label>
                        <input type="number" name="gestation_week" id="gestation_weeks" class="form-control form-inline"  >
                    </span>                
                </td>
                <td>Resuscitation done:
                    <span>
                        <label for="">Yes</label>
                        <input type="radio" name="resuscitation_done" id="resuscitation_done_yes" value="YES" >
                    </span>
                    <span>
                        <label for="">No</label>
                        <input type="radio" name="resuscitation_done" id="resuscitation_done_no" value="NO" >
                    </span>
                </td>
                <td > 
                    <span >
                        <textarea name="pediatric_details"  rows="1"  class="form-control" id="pediatric_details" ></textarea>
                    </span>
                </td>
                <td><input type="button" class="art-button-green pull-right" style="width: 150px;"  onclick="save_pediatric()" id="submit" value="Save"></td>
                
            </tr>
            
                <?php }?>            
            </tbody>
        </table>
    </form>
     <br>
    <hr>
    <!-- 
    <form action="ajax_anasthesia_record_information.php" method="POST" >
    <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
    <input type="text" hidden  name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID?>" >
    <table class="table"> -->
    <?php 
        // if((mysqli_num_rows($select_renal_desease_result))>0){while($renal_row = mysqli_fetch_assoc($select_renal_desease_result)){
        //     $renal_disease = $renal_row['renal_disease'];
        //     $renal_details = $renal_row['other_details'];
        //     $cns_desease = $renal_row['cns_desease'];
        //     $musculoskeletal_diseases = $renal_row['musculoskeletal_diseases'];
        //     $checked_yes ="";
        //     $checked_no = "";

    ?>
        <!-- <tr>
            <td>Renal Disease:
            <?php  
            if($renal_disease =="YES"){
                $checked_yes ="checked='checked'";
            }else{
                $checked_no = "checked='checked'";
            }
            ?>
                <span>
                    <label for="">Yes</label>
                    <input type="radio" value="" name="renal_disease" <?php echo $checked_yes;?>>
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" value="" name="renal_disease" <?php echo $checked_no; ?>>
                </span>
            </td>
            <td > Other Details
                <span >
                    <textarea name="renal_other_details" id="other_details" rows="1"  class="form-control" required> <?php echo $renal_details;?></textarea>
                </span>
            </td>
        </tr>
        <tr class="border-less">
            
        </tr>
        <tr>
            <td>CNS diseases:
            <?php  
            if($cns_desease =="YES"){
                $checked_yes ="checked='checked'";
            }else{
                $checked_no = "checked='checked'";
            }
            ?>
                <span>
                    <label for="">Yes</label>
                    <input type="radio" value="YES" name="cns_desease"<?php echo $checked_yes;?>>
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" value="NO" name="cns_desease" <?php echo $checked_no;?>>
                </span>
            </td>
            <td>Musculoskeletal diseases:
            <?php  
            if($musculoskeletal_diseases =="YES"){
                $checked_yes ="checked='checked'";
            }else if ($musculoskeletal_diseases =="NO"){
                $checked_no = "checked='checked'";
            }
            ?>
                <span>
                    <label for="">Yes</label>
                    <input type="radio" value="YES" name="musculoskeletal_diseases" <?php echo $checked_yes;?>>
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" value="NO" name="musculoskeletal_diseases" <?php echo $checked_no;?>>
                </span>
            </td>
        </tr>
        <tr class="borderless">
        <td>Other Details</td>
        <td colspan="7"> 
            <span >
                <textarea name="cns_musculoskeletal_diseases" id="other_details" rows="1" required class="form-control" ><?php echo $renal_row['cns_musculoskeletal_diseases'];?></textarea>
            </span>
        </td>
    </tr>
    </table> -->
    <!-- <table class="table">
        <tr>
            <td>Clotting Disorders:
            <?php  
                if($renal_row['clotting_disorders'] =="YES"){
                    $checked_yes ="checked='checked'";
                }else if($renal_row['clotting_disorders'] =="NO"){
                    $checked_no = "checked='checked'";
                }
            ?>
                <span>
                    <label for="">Yes</label>
                    <input type="radio" value="YES" name="clotting_disorders" <?php echo $checked_yes;?>>
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" value="NO" name="clotting_disorders" <?php echo $checked_no;?>>
                </span>
            </td>
            </tr>
            <tr class="border-less">
            <td colspan=""> Other Details
                <span >
                    <textarea name="clotting_disorders_details" style="display:inline; width:70%" id="other_details" rows="1"  required class="form-control" ><?php echo $renal_row['clotting_disorders_details'];?></textarea>
                </span>
            </td>
        </tr>
        </table>
        <table class="table">
            <tr class="border-less">
                <td colspan="6"> Significant Family history
                    <span >
                        <textarea name="significant_family_history" style="display:inline; width:60%" id="other_details" required  rows="1" class="form-control" ><?php echo $renal_row['significant_family_history']; ?></textarea>
                    </span>
                </td>
            </tr>
        </table>     

            <table class="table">
                <tr>
                    <th>Drug reaction /Allergy:</th>
                    <?php  
                        $checked_unknown ="";
                        if($renal_row['drug_reaction'] =="YES"){
                            $checked_yes ="checked='checked'";
                        // }elseif($renal_row['drug_reaction']=="NO"){
                        //     $checked_no = "checked='checked'";
                        }else if($renal_row['drug_reaction'] =="NO"){
                            $checked_unknown = "checked='checked'";
                        }
                    ?>
                    <td>
                        <span>
                            <label for="">Yes</label>
                            <input type="radio" name="drug_reaction" id="drugyes"  value="YES" <?php echo $checked_yes; ?>>
                        </span>
                        <span>
                            <label for="">No</label>
                            <input type="radio" name="drug_reaction"  id="no" value="NO" <?php echo $checked_no?> >
                        </span>
                        <span id="unkown">
                            <label for="">Not Known</label>
                            <input type="radio" name="drug_reaction" id="unkown" <?php echo $checked_unknown;?>>
                        </span>
                        <span id="yesdetail">
                            <label for="">If Yes Details</label>
                            <input type="text" name="drug_reaction_yes" style="display:inline; width:40%"   class="form-control" <?php echo $renal_row['drug_reaction_yes'];?>>
                        </span>
                    </td>                          
                           
                    </tr>
                    <tr>
                        <th>Previous Anesthetic /Surgical history:</th>
                           <td colspan="2"> 
                           <?php  
                                if($renal_row['surgical_hisory'] =="YES"){
                                    $checked_yes ="checked='checked'";
                                }else if($renal_row['surgical_hisory'] =="NO"){
                                    $checked_no = "checked='checked'";
                                }
                            ?>                             
                                <span>
                                    <label for="">Yes</label>
                                    <input type="radio" name="surgical_hisory" id="history_yes"  value="YES" <?php $checked_yes;?>>
                                </span>
                                <span>
                                    <label for="">No</label>
                                    <input type="radio" name="surgical_hisory" id="histno"  value="NO" <?php echo $checked_no;?> >
                                </span>
                                <span id="histyes">
                                    <label>If yes Details</label>
                                    <input type="text" class="form-control" style="display:inline; width:40%" id="radio3"  name="surgical_history_ifyes" <?php echo $renal_row['surgical_history_ifyes'];?>>
                                </span>
                           </td>
                           
                    </tr> 

                </table>
                <table class="table">
                    <tr class="border-less">
                        <td> Current Medication:</td>
                        <td colspan="">
                            <span >
                                <textarea name="current_medication" id="other_details" required rows="1"  class="form-control" ><?php echo $renal_row['current_medication'];?></textarea>
                            </span>
                        </td>
                        <td><button type="submit" readonly class="bth btn-success pull-right" name="drug_surgical">DATA SAVED</button></td>
                    </tr>
                </table>
            </form>
                    -->
   
            <?php // }}else{?>
        <!-- <tr>
            <td>Renal Disease:
            
                <span>
                    <label for="">Yes</label>
                    <input type="radio" value="" name="renal_disease">
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" value="" name="renal_disease">
                </span>
            </td>
            <td colspan="2"> Other Details
                <span >
                    <textarea style="display:inline; width:90%" name="renal_other_details" id="other_details" rows="1"  class="form-control" required></textarea>
                </span>
            </td>
        </tr>        
        <tr>
            <td>CNS diseases:
                <span>
                    <label for="">Yes</label>
                    <input type="radio" value="YES" name="cns_desease">
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" value="NO" name="cns_desease">
                </span>
            </td>
            <td>Musculoskeletal diseases:
                <span>
                    <label for="">Yes</label>
                    <input type="radio" value="YES" name="musculoskeletal_diseases">
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" value="NO" name="musculoskeletal_diseases">
                </span>
            </td>
            <td colspan=""> Other Details
                <span >
                    <textarea style="display:inline; width:80%" name="cns_musculoskeletal_diseases" style="width:70;" id="other_details" rows="1" required class="form-control" ></textarea>
                </span>
            </td>
        </tr>
        

        
    </table>
    <table class="table">
        <tr>
            <td>Clotting Disorders:
                <span>
                    <label for="">Yes</label>
                    <input type="radio" value="YES" name="clotting_disorders">
                </span>
                <span>
                    <label for="">No</label>
                    <input type="radio" value="NO" name="clotting_disorders">
                </span>
            </td>
            <td colspan="" > Other Details
                <span >
                    <textarea style="display:inline; width:80%" name="clotting_disorders_details" id="other_details" rows="1"  required class="form-control" ></textarea>
                </span>
            </td>
        </tr>            
        </table>
        <table class="table">
            <tr class="border-less">
                <td colspan=""> Significant Family history
                    <span >
                        <textarea style="display:inline; width:80%" name="significant_family_history" id="other_details" required  rows="1" class="form-control" ></textarea>
                    </span>
                </td>
                <td colspan=""> Current Medication:
                    <span >
                        <textarea style="display:inline; width:80%" name="current_medication" id="other_details" required rows="1"  class="form-control" ></textarea>
                    </span>
                </td>
            </tr>
        </table>     

                <table class="table">
                    <tr>
                        <td>Drug reaction /Allergy:
                            <span>
                                <label for="">Yes</label>
                                <input type="radio" name="drug_reaction" id="drugyes"  value="YES" >
                            </span>
                            <span>
                                <label for="">No</label>
                                <input type="radio" name="drug_reaction"  id="no" value="NO" >
                            </span>
                            <span id="unkown">
                                <label for="">Not Known</label>
                                <input type="radio" name="drug_reaction" id="unkown" >
                            </span>
                            <span id="yesdetail">
                                <label for="">If Yes Details</label>
                                <input type="text" name="drug_reaction_yes" style="display:inline; width:40%"  class="form-control" >
                            </span>
                        </td>
                        <td colspan=""> Previous Anesthetic /Surgical history:                             
                            <span>
                                <label for="">Yes</label>
                                <input type="radio" name="surgical_hisory" id="history_yes"  value="YES" >
                            </span>
                            <span>
                                <label for="">No</label>
                                <input type="radio" name="surgical_hisory" id="histno"  value="NO" >
                            </span>
                            <span id="histyes">
                                <label>If yes Details</label>
                                <input type="text" class="form-control" id="radio3" style="display:inline; width:40%"  name="surgical_history_ifyes" >
                            </span>
                        </td>  
                    </tr>
                    
                </table>
                <table class="table">
                    <tr class="border-less">
                        <td><button type="submit" class="btn btn-primary pull-right" style="width: 250px;" name="drug_surgical">Save</button></td>
                    </tr>
                    <?php //}?>
                </table>
            </form>       -->
    <br>
    <hr>
    <form action="ajax_anasthesia_record_information.php" method="post">
    <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
    <input type="text" hidden  name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID?>" >
        <table class="table">
            <thead>
                <tr>
                    <th colspan="10" style="background: #dedede;" id="th"> <h4>General and Systemic Examination </h4> </th>
                </tr>
            </thead>
            <tbody>
            <?php if((mysqli_num_rows($select_generaExam_result))>0){
                while($exam_row = mysqli_fetch_assoc($select_generaExam_result)){
                $checked_yes = "";
                $checked_no = "";
                ?>
                <tr>
                <?php 
                    $checked_awake= "";
                    $checked_cooperative="";
                    $checked_Unconscious="";
                    $checked_Aggressive="";
                    $checked_health="";
                    $checked_weak="";
                    $checked_pale="";
                    $checked_Dyspnoea_YES="";
                    $checked_Dyspnoea_NO="";
                    $checked_LLEDEMA_YES="";
                    $checked_LLEDEMA_NO="";
                    $patient_states =explode(',', $exam_row['patient_state']);
                    foreach($patient_states as $patient_state){
                    if($patient_state =="Awake"){
                        $checked_awake ="checked='checked'";
                    }elseif($patient_state =="cooperative"){
                        $checked_cooperative ="checked='checked'";
                    }elseif($patient_state =="Unconscious"){
                        $checked_Unconscious ="checked='checked'";
                    }elseif($patient_state =="Aggressive"){
                        $checked_Aggressive ="checked='checked'";
                    }elseif($patient_state =="Health"){
                        $checked_Health ="checked='checked'";
                    }elseif($patient_state =="Weak"){
                        $checked_Weak ="checked='checked'";
                    }elseif($patient_state =="Pale"){
                        $checked_Pale ="checked='checked'";
                    }elseif($patient_state =="Not Pale"){
                        $checked_Pale ="checked='checked'";
                    }elseif($patient_state =="OBESE"){
                        $checked_obese ="checked='checked'";
                    }elseif($exam_row['Dyspnoea'] =="Dyspnoea_YES"){
                        $checked_Dyspnoea_YES ="checked='checked'";
                    }elseif($exam_row['LL_EDEMA'] =="LLEDEMA_YES"){
                        $checked_LLEDEMA_YES ="checked='checked'";
                    }elseif($exam_row['LL_EDEMA'] =="LLEDEMA_NO"){
                        $checked_LLEDEMA_NO ="checked='checked'";
                    }elseif($exam_row['Dyspnoea'] =="Dyspnoea_NO"){
                        $checked_Dyspnoea_NO ="checked='checked'";
                    }
                }
                ?>
                    <td colspan="4">Patient's state:
                        <span>
                            <label for="">Awake</label>
                            <input type="checkbox" name="patient_state" id="" value="Awake" <?php echo $checked_awake; ?> >
                        </span>
                        <span>
                            <label for="">Cooperative</label>
                            <input type="checkbox" name="patient_state" id="" value="cooperative" <?php echo $checked_cooperative; ?> >
                        </span>
                        <span>
                            <label for="">Unconsious</label>
                            <input type="checkbox" name="patient_state" id="" value="Unconscious" <?php echo $checked_Unconscious; ?> >
                        </span>
                        <span>
                            <label for="">Aggressive</label>
                            <input type="checkbox" name="patient_state" id="" value="Aggressive" <?php echo $checked_Aggressive; ?>>
                        </span>
                        <span>
                            <label for="">Health</label>
                            <input type="checkbox" name="patient_state" id="" value="Health" <?php echo $checked_Health; ?>>
                        </span>
                        <span>
                            <label for="">Weak</label>
                            <input type="checkbox" name="patient_state" id="" value="Weak" <?php echo $checked_Weak; ?>>
                        </span>
                        <span>
                            <label for="">Pale</label>
                            <input type="checkbox" name="patient_state" id="" value="Pale" <?php echo $checked_Pale; ?>>
                        </span>
			<span>
                            <label for="">Not Pale</label>
                            <input type="checkbox" name="patient_state" id="" value="Not Pale" <?php echo $checked_Pale; ?>>
                        </span>  
                        <span>
                            <label for="">Obese</label>
                            <input type="checkbox" name="patient_state" id="" value="OBESE" <?php echo $checked_obese; ?>>
                        </span>                        
                    </td>
                    <td> Dyspnoea 
                        <span>
                            <label for="">Yes</label>
                            <input type="radio" name="Dyspnoea" id="" value="Dyspnoea_YES" <?php echo $checked_Dyspnoea_YES; ?> >
                        </span>                      
                        <span>
                            <label for="">No</label>
                            <input type="radio" name="Dyspnoea" id=""  value="Dyspnoea_NO" <?php echo $checked_Dyspnoea_NO; ?>>
                        </span>                        
                    </td>
                    <td colspan="2"> LL Edema 
                        <span>
                            <label for="">Yes</label>
                            <input type="radio" name="LL_EDEMA" id="" value="LLEDEMA_YES" <?php echo $checked_LLEDEMA_YES; ?>>
                        </span>                      
                        <span>
                            <label for="">No</label>
                            <input type="radio" name="LL_EDEMA" id=""  value="LLEDEMA_NO" <?php echo $checked_LLEDEMA_NO; ?>>
                        </span>                        
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>
                            <label for="">BP</label>
                            <input type="text" name="bp" id="" style="display:inline; width:80%" value="<?php echo $exam_row['bp']; ?>" >
                        </span></td><td>
                        <span>
                            <label for="">HR/PR</label>
                            <input type="text" name="hr_pr" id="" style="display:inline; width:80%" value="<?php echo $exam_row['hr_pr']; ?>" >
                        </span></td><td>
                        <span>
                            <label for="">Temp</label>
                            <input type="text" name="temp" id="" style="display:inline; width:80%" value="<?php echo $exam_row['temp']; ?>" >
                        </span></td><td>
                        <span>
                            <label for="">Wt <small>(Kg)</small></label>
                            <input type="text" name="wt" id="" style="display:inline; width:80%" value="<?php echo $exam_row['wt']; ?>" >
                        </span></td><td>
                        <span>
                            <label for="">Ht <small>(cm)</small></label>
                            <input type="text" name="ht" id="" style="display:inline; width:80%" value="<?php echo $exam_row['ht']; ?>" >
                        </span></td><td>
                        <span>
                            <label for="">BMI</label>
                            <input type="text" name="bmi" id="" style="display:inline; width:80%" value="<?php echo $exam_row['bmi']; ?>">
                        </span></td><td>
                        <span>
                            <label for="">RBG <small>(Mmol/l)</small> </label>
                            <input type="text" name="rbg" id="" style="display:inline; width:70%" value="<?php echo $exam_row['rbg']; ?>">
                        </span>
                    </td>                        
                </tr>
                <tr>
                    <th>Airway Assessment </th>
                    <td colspan="1">
                        <span>
                            <label for="">Mouth Opening  </label>
                            <input type="text" name="mouth_opening" id="" style="display:inline; width:60%" value="<?php echo $exam_row['mouth_opening']; ?>">
                        </span>
                    </td>
                    <td>Micrognathia
                    <?php 
                        if($exam_row['micrognation']=="YES"){
                            $checked_yes ="checked='checked'";
                        }else{
                            $checked_no= "checked ='checked'";
                        }
                    ?>
                        <span>
                            <label for="">Yes </label>
                            <input type="radio" name="micrognathia" id="" value="YES" <?php echo $checked_yes; ?>>
                        </span>
                        <span>
                            <label for="">No </label>
                            <input type="radio" name="micrognathia" id="" value="NO" <?php echo $checked_no; ?>>
                        </span>
                    </td>
                    <td>
                        <span>
                            <label for="">Neck extension </label>
                            <input type="text" name="neck_extension" id="" style="display:inline; width:60%" value="<?php echo $exam_row['neck_extension']; ?>" >
                        </span>
                    </td>
                    <td colspan="3">Thyromental Distance
                    <?php 
                        if($exam_row['thyromental_distance']=="<6cm"){
                            $checked_yes ="checked='checked'";
                        }else{
                            $checked_no= "checked ='checked'";
                        }
                    ?>
                        <span>
                            <label for="">&lt; 6 cm </label>
                            <input type="radio" name="thyromental_distance" id="" value="<6cm" <?php echo $checked_yes;?>>
                        </span>
                        <span>
                            <label for="">&gt;6 cm </label>
                            <input type="radio" name="thyromental_distance" id="" value=">6cm" <?php echo $checked_no;?>>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">MALLAMPATI:
                    <?php 
                        $checked_1= "";
                        $checked_2="";
                        $checked_3="";
                        $checked_4="";
                        if($exam_row['mallampati']=="1"){
                            $checked_1 ="checked='checked'";
                        }elseif($exam_row['mallampati']=="2"){
                            $checked_2= "checked ='checked'";
                        }elseif($exam_row['mallampati']=="3"){
                            $checked_3= "checked ='checked'";
                        }elseif($exam_row['mallampati']=="4"){
                            $checked_4= "checked ='checked'";
                        }
                    ?>
                        <span>
                            <label for="">1 </label>
                            <input type="radio" name="mallampati" id="" value="1" <?php echo $checked_1;?>>
                        </span>
                        <span>
                            <label for="">2 </label>
                            <input type="radio" name="mallampati" id="" value="2" <?php echo $checked_2;?>>
                        </span>
                        <span>
                            <label for="">3 </label>
                            <input type="radio" name="mallampati" id="" value="3" <?php echo $checked_3;?>>
                        </span>
                        <span>
                            <label for="">4 </label>
                            <input type="radio" name="mallampati" id="" value="4" <?php echo $checked_4;?>>
                        </span>
                    </td>
                    <td colspan="2">Teeth:
                    <?php 
                        $checked_loose="";
                        $checked_implants="";
                        $checked_normal="";
                        $teeths = explode(',', $exam_row['teeth']);
                        foreach($teeths as $teeth){
                            if($teeth=="loose"){
                                $checked_loose="checked='checked'";
                            }elseif($teeth=="implants"){
                                $checked_implants="checked='checked'";
                            }elseif($teeth=="normal"){
                                $checked_normal="checked='checked'";
                            }
                        }
                    ?>
                        <span>
                            <label for="">loose </label>
                            <input type="checkbox" name="teeth" id="" value="loose" <?php echo $checked_loose;?>>
                        </span>
                        <span>
                            <label for="">implants </label>
                            <input type="checkbox" name="teeth" id="" value="implants" <?php echo $checked_implants;?>>
                        </span>
                        <span>
                            <label for="">normal </label>
                            <input type="checkbox" name="teeth" id="" value="normal" <?php echo $checked_normal;?>>
                        </span>
                    </td>
                    <td colspan="3">
                    <?php 
                        $checked_1="";
                        $checked_2="";
                        $checked_3="";
                        $checked_4="";
                        $checked_5="";
                        $checked_E="";
                        $checked_6="";

                        if($exam_row['ASA_physical_status']=="1"){
                            $checked_1="checked='checked'";
                        }elseif($exam_row['ASA_physical_status']=="2"){
                            $checked_2="checked='checked'";
                        }elseif($exam_row['ASA_physical_status']=="3"){
                            $checked_3="checked='checked'";
                        }elseif($exam_row['ASA_physical_status']=="4"){
                            $checked_4="checked='checked'";
                        }elseif($exam_row['ASA_physical_status']=="5"){
                            $checked_5="checked='checked'";
                        }elseif($exam_row['ASA_physical_status']=="E"){
                            $checked_E="checked='checked'";
                        }elseif($exam_row['ASA_physical_status']=="6"){
                            $checked_6="checked='checked'";
                        }
                        ?>
                        <label for="">ASA Physical Status:  </label> 
                        I<input type="radio" style="display: inline; width:auto;" name="ASA_physical_status" <?php echo $checked_1; ?> value="1">
                        II<input type="radio" style="display: inline; width:auto;" name="ASA_physical_status" <?php echo $checked_2; ?> value="2">
                        III<input type="radio" style="display: inline; width:auto;" name="ASA_physical_status" <?php echo $checked_3; ?> value="3">
                        IV<input type="radio" style="display: inline; width:auto;" name="ASA_physical_status" <?php echo $checked_4; ?> value="4">
                        V<input type="radio" style="display: inline; width:auto;" name="ASA_physical_status" <?php echo $checked_5; ?> value="5">
                        VI<input type="radio" style="display: inline; width:auto;" name="ASA_physical_status" value="6" <?php echo $checked_6; ?>>

                        E<input type="checkbox" style="display: inline; width:auto;" name="ASA_physical_status" <?php echo $checked_E; ?> value="E">
                    </td>
                </tr>
                <tr>
                    <!-- <td>
                        <label for="">Last Oral Intake</label>
                        <input type="text" style="display: inline; width:60%;" name="last_oral_intake" value="<?php echo $exam_row['last_oral_intake'];?>">
                    </td>                    
                    <td colspan="2">
                        <label for="">Anaesthetic Technique</label>
                        <input type="text" style="display: inline; width:60%;" name="Anaesthetic_technique" value="<?php echo $exam_row['Anaesthetic_technique'];?>">
                    </td> -->
                    
                </tr>
                <tr>
                    <td colspan="2">
                        <span>
                            <label for="">CVS: </label>
                            <textarea  name="cvs" id="" style="display:inline; width:90%" rows="1"><?php echo $exam_row['cvs'];?></textarea>
                        </span>
                    </td>
                    <td colspan="2">
                        <span>
                            <label for="">CNS: </label>
                            <textarea  name="lungs" id="" style="display:inline; width:90%" rows="1"><?php echo $exam_row['lungs'];?></textarea>
                        </span>
                    </td>
                    <td colspan="2">
                        <span>
                            <label for="">Other Systems: </label>
                            <textarea  name="other_systems" id="" style="display:inline; width:80%" rows="1"><?php echo $exam_row['other_systems'];?></textarea>
                        </span>
                    </td>
                    <td>
                    <button type="submit" class="btn btn-success pull-right" disabled name="general_exemination">DATA SAVED</button>
                    </td>
                </tr>
                <tr style="background: #dedede;">
                    <?php
                    $Employee_ID = $exam_row['Employee_ID'];
                    $select_employee = mysqli_query($conn, "SELECT Employee_Name, employee_signature FROM tbl_employee e WHERE e.Employee_ID='$Employee_ID' ") or die(mysqli_error($conn));
                    while($emp_rw = mysqli_fetch_assoc($select_employee)){
                        $Employee_Name = $emp_rw['Employee_Name']; 
                        $employee_signature = $emp_rw['employee_signature'];
                        if($employee_signature==""||$employee_signature==null){
                         $signature="________________________";
                     }else{
                         $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
                     }
                    }
                    ?>
                    <td colspan="3">
                        <span>
                            <label for="">Saved By</label>
                            <?php echo $Employee_Name; ?>
                        </span>
                    </td>
                    <td colspan="2">
                        <span>
                            <label for="">Signature: </label>
                            <?php echo $signature; ?>
                        </span>
                    </td>
                    <td colspan="2">
                        <span>
                            <label for="">Date</label>
                            <?php echo $exam_row['created_at']; ?>
                        </span>
                    </td>
                </tr>
                <?php }}else{?>
                    <tr>
                    <td colspan="4">Patient's state:
                        <span>
                            <label for="">Awake</label>
                            <input type="checkbox" name="patient_state[]" id="" value="Awake" >
                        </span>
                        <span>
                            <label for="">Cooperative</label>
                            <input type="checkbox" name="patient_state[]" id="" value="cooperative" >
                        </span>
                        <span>
                            <label for="">Unconsious</label>
                            <input type="checkbox" name="patient_state[]" id="" value="Unconscious" >
                        </span>
                        <span>
                            <label for="">Aggressive</label>
                            <input type="checkbox" name="patient_state[]" id="" value="Aggressive" >
                        </span>
                        <span>
                            <label for="">Health</label>
                            <input type="checkbox" name="patient_state[]" id="" value="Health" >
                        </span>
                        <span>
                            <label for="">Weak</label>
                            <input type="checkbox" name="patient_state[]" id="" value="Weak" >
                        </span>
                        <span>
                            <label for="">Pale</label>
                            <input type="checkbox" name="patient_state[]" id="" value="Pale" >
                        </span>
                        <span>
                            <label for="">Not Pale</label>
                            <input type="checkbox" name="patient_state[]" id="" value="Not Pale" >
                        </span>
                        <span>
                            <label for="">Obese</label>
                            <input type="checkbox" name="patient_state[]" id="" value="OBESE" >
                        </span>                         
                    </td>
                    <td> Dyspnoea 
                            <span>
                                <label for="">Yes</label>
                                <input type="radio" name="Dyspnoea" id="" value="YES" >
                            </span>                      
                           <span>
                                <label for="">No</label>
                                <input type="radio" name="Dyspnoea" id=""  value="NO">
                           </span>                        
                    </td>
                    <td colspan="2"> LL Edema 
                            <span>
                                <label for="">Yes</label>
                                <input type="radio" name="LL_EDEMA" id="" value="LLEDEMA_YES" >
                            </span>                      
                           <span>
                                <label for="">No</label>
                                <input type="radio" name="LL_EDEMA" id=""  value="LLEDEMA_NO">
                           </span>                        
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>
                            <label for="">BP</label>
                            <input type="text" style="display:inline; width:80%" name="bp" id=""  >
                        </span></td><td>
                        <span>
                            <label for="">HR/PR</label>
                            <input type="text" style="display:inline; width:80%" name="hr_pr" id=""  >
                        </span></td><td>
                        <span>
                            <label for="">Temp</label>
                            <input type="text" style="display:inline; width:80%" name="temp" id=""  >
                        </span></td><td>
                        <span>
                            <label for="">Wt <small>(Kg)</small></label>
                            <input type="text" style="display:inline; width:80%" name="wt" id="weight"  >
                        </span></td><td>
                        <span>
                            <label for="">Ht <small>(cm)</small></label>
                            <input type="text" style="display:inline; width:80%" name="ht" onkeyup="calculateBMI()" id="height"  >
                        </span></td><td>
                        <span>
                            <label for="">BMI</label>
                            <input type="text" style="display:inline; width:80%" name="bmi" id="bmi" >
                        </span></td><td>
                        <span>
                            <label for="">RBG <small>(Mmol/l)</small> </label>
                            <input type="text" style="display:inline; width:60%" name="rbg" id="" >
                        </span>
                    </td>                        
                </tr>
                <tr>
                    <th>Airway Assessment</th>
                    <td colspan="1">
                        <span>
                            <label for="">Mouth Opening  </label>
                            <input type="text" style="display:inline; width:60%" name="mouth_opening" id="" >
                        </span>
                    </td>
                    <td>Micrognathia
                        <span>
                            <label for="">Yes </label>
                            <input type="radio" name="micrognathia" id="" value="YES" >
                        </span>
                        <span>
                            <label for="">No </label>
                            <input type="radio" name="micrognathia" id="" value="NO">
                        </span>
                    </td>
                    <td>
                        <span>
                            <label for="">Neck extension </label>
                            <input type="text" style="display:inline; width:60%"  name="neck_extension" id="" >
                        </span>
                    </td>
                    <td colspan="3">Thyromental Distance
                        <span>
                            <label for="">&lt; 6 cm </label>
                            <input type="radio" name="thyromental_distance" id="" value="<6cm">
                        </span>
                        <span>
                            <label for="">&gt;6 cm </label>
                            <input type="radio" name="thyromental_distance" id="" value=">6cm">
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span>
                            <label for="">CVS: </label>
                            <textarea  name="cvs"  class="form-control" style="display:inline; width:90%" id="" rows="1"></textarea>
                        </span>
                    </td>
                    <td colspan="2">
                        <span>
                            <label for="">CNS: </label>
                            <textarea  name="lungs"  class="form-control" style="display:inline; width:90%" id="" rows="1"></textarea>
                        </span>
                    </td>
                    <td colspan="3">
                        <span>
                            <label for="">Other Systems: </label>
                            <textarea  name="other_systems" class="form-control" style="display:inline; width:80%" id="" rows="1"></textarea>
                        </span>
                    </td>
                    
                </tr>
                <tr>
                    <td colspan="2">MALLAMPATI:
                        <span>
                            <label for="">1 </label>
                            <input type="radio" name="mallampati" id="" value="1">
                        </span>
                        <span>
                            <label for="">2 </label>
                            <input type="radio" name="mallampati" id="" value="2">
                        </span>
                        <span>
                            <label for="">3 </label>
                            <input type="radio" name="mallampati" id="" value="3">
                        </span>
                        <span>
                            <label for="">4 </label>
                            <input type="radio" name="mallampati" id="" value="4">
                        </span>
                    </td>
                    <td colspan="2">Teeth:
                        <span>
                            <label for="">loose </label>
                            <input type="checkbox" name="teeth[]" id="" value="loose">
                        </span>
                        <span>
                            <label for="">implants </label>
                            <input type="checkbox" name="teeth[]" id="" value="implants">
                        </span>
                        <span>
                            <label for="">normal </label>
                            <input type="checkbox" name="teeth[]" id="" value="normal">
                        </span>
                    </td>
                    <td colspan="2">
                        <label for="">ASA Physical Status:  </label>
                        I<input type="radio" style="display: inline; width:auto;" name="ASA_physical_status" value="1">
                        II<input type="radio" style="display: inline; width:auto;" name="ASA_physical_status" value="2">
                        III<input type="radio" style="display: inline; width:auto;" name="ASA_physical_status" value="3">
                        IV<input type="radio" style="display: inline; width:auto;" name="ASA_physical_status" value="4">
                        V<input type="radio" style="display: inline; width:auto;" name="ASA_physical_status" value="5">
                        VI<input type="radio" style="display: inline; width:auto;" name="ASA_physical_status" value="6">

                        E<input type="checkbox" style="display: inline; width:auto;" name="ASA_physical_status" value="E">
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary pull-right" style="width: 250px;" name="general_exemination">Save</button>
                    </td>
                </tr>
                <!-- <tr>
                    <td>
                        <label for="">Last Oral Intake</label>
                        <input type="text" style="display: inline; width:60%;"  class="form-control" name="last_oral_intake">
                    </td>                    
                    <td colspan="2">
                        <label for="">Anaesthetic Technique</label>
                        <input type="text" style="display: inline; width:70%;"  class="form-control" name="Anaesthetic_technique">
                    </td>
                    <td colspan="2">
                        <label for="">Pre-Anaesthetic Orders</label>
                        <textarea  style="display: inline; width:60%;"  class="form-control" rows="1" name="pre_anaesthetic_orders"></textarea>
                    </td>
                </tr> -->
                
                <?php } ?>
            </tbody>
        </table>
    </form>
    <br>
    <hr>

    <form action="ajax_anasthesia_record_information.php" method="POST" >
        <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
        <input type="text" hidden  name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID?>" >
            <table class="table">
                <?php if((mysqli_num_rows($select_investigation_result))>0){
                    while($investigation_row = mysqli_fetch_assoc($select_investigation_result)){
                    ?>
                    <tr>
                        <th colspan="8" id="th">Laboratory Studies</th>
                    </tr>
                    
                    <tr >
                        
                        <td >
                            <span><strong>FBP</strong></span>
                        </td>
                        <td>
                            <span> <input type="text" name="fbp_fbp" class="form-control" style="display: inline; width:70%;" disabled value="<?php echo $investigation_row['fbp_fbp'];?>"></span>
                        
                        </td>
                        <td>                            
                            <span><strong>WBC</strong></span>
                            <span><input type="text" name="fbp_wbc" class="form-control" style="display: inline; width:70%;" disabled   value="<?php echo $investigation_row['fbp_wbc'];?>"></span>
                        </td>
                        <td>                            
                            <span><strong>Hb</strong></span>
                            <span><input type="text" name="fbp_hb" class="form-control" style="display: inline; width:70%;" disabled   value="<?php echo $investigation_row['fbp_hb'];?>"></span>
                        </td>
                        <td>                            
                            <span><strong>HCT.</strong></span>
                            <span><input type="text" name="fbp_hct" class="form-control" style="display: inline; width:70%;" disabled   value="<?php echo $investigation_row['fbp_hct'];?>"></span>
                        </td>
                        <td>                            
                            <span><strong>Platelets</strong></span>
                            <span><input type="text" name="fbp_platelets" class="form-control" style="display: inline; width:70%;" disabled value="<?php echo $investigation_row['fbp_platelets'];?>"></span>
                        </td>
                       
                        <td width="100" colspan="">
                        <span><strong>RFT</strong></span>
                           <?php echo $investigation_row['lft'];?>
                        </td>
                        <td colspan="">
                            <span class="form-inline"><strong>Blood Group:</strong></span>
                            <span class="form-inline">
                                <?php echo $investigation_row['blood_group'];?>                          
                            </span>
                        </td>
                        
                    </tr>
                    <tr>
                        <td>Clotting Profile:</td>
                        <td><span><strong> INR</strong></span>
                            <span><input type="text" name="clotting_profile_inr" class="form-control" style="display: inline; width:50%;" readonly=readonly  value="<?php echo $investigation_row['clotting_profile_inr']; ?>" ></span>
                        
                        </td>
                        <td>                            
                            <span><strong>PT</strong></span>
                            <span><input type="text" name="inr_pt" class="form-control" style="display: inline; width:70%;" disabled  value="<?php echo $investigation_row['inr_pt'];?>"></span>
                        </td>
                        <td>                            
                            <span><strong>PTT</strong></span>
                            <span><input type="text" name="inr_ptt" class="form-control" style="display: inline; width:70%;" disabled  value="<?php echo $investigation_row['inr_ptt'];?>"></span>
                        </td>
                        <td>                            
                            <span><strong>Fibrinogen</strong></span>
                            <span><input type="text" name="inr_fibrinogen" class="form-control" style="display: inline; width:70%;" disabled  value="<?php echo $investigation_row['inr_fibrinogen'];?>"></span>
                        </td>
                        <td>                            
                            <span><strong>Bleeding time</strong></span>
                            <span><input type="text" name="inr_bleeding_time" class="form-control" style="display: inline; width:60%;" disabled  value="<?php echo $investigation_row['inr_bleeding_time'];?>"></span>
                        </td>
                    </tr>
                    <tr>
                        <td><span><strong>Renal function Test</strong></span></td>
                        
                        <td>                            
                            <span><strong>Cr.</strong></span>
                            <span><input type="text" name="biochemistry_cr" class="form-control" style="display: inline; width:70%;" disabled   value="<?php echo $investigation_row['biochemistry_cr'];?>"></span>
                        </td>
                        <td>                            
                            <span><strong>UREA/BUN</strong></span>
                            <span><input type="text" name="biochemistry_urea" class="form-control" style="display: inline; width:70%;" disabled   value="<?php echo $investigation_row['biochemistry_urea'];?>"></span>
                        </td>
                        <!-- <td>                            
                            <span><strong>GL</strong></span>
                            <span><input type="text" name="biochemistry_gl" class="form-control" style="display: inline; width:70%;" disabled   value="<?php echo $investigation_row['biochemistry_gl'];?>"></span>
                        </td>  -->
                    </tr>
                    <tr>
                        <td><strong> Serum electrolyte <strong></td>
                        <td>                            
                            <span><strong>Ca</strong></span>
                            <span><input type="text" name="biochemistry_ca" class="form-control" style="display: inline; width:70%;" disabled   value="<?php echo $investigation_row['biochemistry_ca'];?>" ></span>
                        </td>
                        <td>                            
                            <span><strong>K</strong></span>
                            <span><input type="text" name="biochemistry_k" class="form-control" style="display: inline; width:70%;" disabled   value="<?php echo $investigation_row['biochemistry_k'];?>"></span>
                        </td>
                        <td>                            
                            <span><strong>Na</strong></span>
                            <span><input type="text" name="biochemistry_na" class="form-control" style="display: inline; width:70%;" disabled   value="<?php echo $investigation_row['biochemistry_na'];?>"></span>
                        </td>
                        <td colspan="4">                            
                            <span><strong>CL</strong></span>
                            <span><input type="text" name="biochemistry_cl" class="form-control" style="display: inline; width:70%;" disabled   value="<?php echo $investigation_row['biochemistry_cl'];?>"></span>
                        </td>
                    </tr>
                    <tr>
                        
                    </tr>
                    
                    <tr>
                        <td><span><strong>Blood Gas: </strong></span></td>
                        <td>                            
                            <span><strong>FiO₂</strong></span>
                            <span><input type="text" name="blood_gas_FiO2" class="form-control" style="display: inline; width:70%;" readonly=readonly   value="<?php echo $investigation_row['blood_gas_FiO2'];?>"></span>
                        </td>
                        <td>                            
                            <span><strong>PH</strong></span>
                            <span><input type="text" name="blood_gas_ph" class="form-control" style="display: inline; width:70%;" readonly=readonly   value="<?php echo $investigation_row['blood_gas_ph'];?>"></span>
                        </td>
                        <td>                            
                            <span><strong>PO₂</strong></span>
                            <span><input type="text" name="blood_gas_sao2" class="form-control" style="display: inline; width:70%;" readonly=readonly   value="<?php echo $investigation_row['blood_gas_sao2'];?>"></span>
                        </td>
                        <td>                            
                            <span><strong>BE</strong></span>
                            <span><input type="text" name="blood_gas_be" class="form-control" style="display: inline; width:70%;" readonly=readonly   value="<?php echo $investigation_row['blood_gas_be'];?>"></span>
                        </td>
                        <td>                            
                            <span><strong>HCO3</strong></span>
                            <span><input type="text" name="blood_gas_bic" class="form-control" style="display: inline; width:70%;" readonly=readonly   value="<?php echo $investigation_row['blood_gas_bic'];?>"></span>
                        </td>
                        <td colspan="2">                            
                            <span><strong>PCO₂</strong></span>
                            <span><input type="text" name="blood_gas_pco2" class="form-control" style="display: inline; width:70%;" readonly=readonly   value="<?php echo $investigation_row['blood_gas_pco2'];?>"></span>
                        </td>                        
                    </tr>
                    <tr>
                        <td >                            
                            <span><strong>Hormones</strong></span>
                        </td>
                        <td width="100" colspan="7">
                        <?php echo $investigation_row['other_hormones'];?>
                        </td>
                    </tr>
                    <tr>
                        <td  >                            
                            <span class="form-inline"><strong>CXR findings:</strong></span>
                        </td>
                        <td width="100" colspan="7">
                         <?php echo $investigation_row['cxr_findings'];?>
                        </td>
                        
                    </tr>
                    <tr>
                        <td >                            
                            <span class="form-inline"><strong>ECG Findings:</strong></span>
                        </td>
                        <td width="100" colspan="7">
                            <?php echo $investigation_row['ecg_findings'];?>
                        </td>
                    </tr>
                    <tr>
                        <td>                            
                            <span class="form-inline"><strong>ECHO findings:</strong></span>
                        </td>
                        <td width="100" colspan="7">
                                <?php echo $investigation_row['echo_findings'];?>
                        </td>
                    </tr>
                    <tr>
                        <td >                            
                            <span class="form-inline"><strong>CT scan findings:</strong></span>
                        </td>
                        <td width="100" colspan="3">
                            <?php echo $investigation_row['ct_scan_findings'];?>
                        </td>
                        <td width="100" colspan="">MRI:
                        </td>
                        <td><?= $investigation_row['MRI']; ?></td>
                    </tr>
                    <?php }}else{?>
                        <tr>
                            <th colspan="8" id="th"><h3>Laboratory Studies</h3></th>
                        </tr>
                        <tr>
                        
                                              
                    </tr>
                    <tr>
                        <td><span><strong>FBP</strong></span></td>
                        <td colspan="2">                            
                            <span> <input type="text" name="fbp_fbp" class="form-control" style="display: inline; width:100%;"></span>
                        </td>
                        <td>                            
                            <span><strong>WBC</strong></span>
                            <span><input type="text" name="fbp_wbc" class="form-control" style="display: inline; width:70%;"></span>
                        </td>
                        <td>                            
                            <span><strong>Hb</strong></span>
                            <span><input type="text" name="fbp_hb" class="form-control" style="display: inline; width:70%;"></span>
                        </td>
                        <td>                            
                            <span><strong>HCT.</strong></span>
                            <span><input type="text" name="fbp_hct" class="form-control" style="display: inline; width:50%;"></span>
                        </td>
                        <td>                            
                            <span><strong>Platelets</strong></span>
                            <span><input type="text" name="fbp_platelets" class="form-control" style="display: inline; width:60%;"></span>
                        </td>
                        <td>
                        <span class="form-inline"><strong>Blood Group:</strong></span>
                            <span class="form-inline">
                                <select name="blood_group" class="form-control" id="">
                                    <option value="">~~Select~~</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O">O</option>
                                </select>                            
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>Clotting Profile:</td>
                        <td><span><strong>INR</strong></span>
                       
                            <span><input type="text" name="clotting_profile_inr" class="form-control" style="display: inline; width:70%;"    ></span>
                        
                        </td>
                        
                        <td>                            
                            <span><strong>PT</strong></span>
                            <span><input type="text" name="inr_pt" class="form-control" style="display: inline; width:70%;"></span>
                        </td>
                        <td>                            
                            <span><strong>PTT</strong></span>
                            <span><input type="text" name="inr_ptt" class="form-control" style="display: inline; width:70%;"></span>
                        </td>
                        <td colspan="2">                            
                            <span><strong>Fibrinogen</strong></span>
                            <span><input type="text" name="inr_fibrinogen" class="form-control" style="display: inline; width:50%;"></span>
                        </td>
                        <td colspan='2'>                            
                            <span><strong>Bleeding time</strong></span>
                            <span><input type="text" name="inr_bleeding_time" class="form-control" style="display: inline; width:70%;"></span>
                        </td>
                        
                    </tr>
                    
                    <tr> 
                        <td ><span><strong>Renal function Test</strong></span></td>                       
                        
                        <td>                            
                            <span><strong>UREA/BUN</strong></span>
                            <span><input type="text" name="biochemistry_urea" class="form-control" style="display: inline; width:50%;"></span>
                        </td>
                        <td>                            
                            <span><strong>Cr.</strong></span>
                            <span><input type="text" name="biochemistry_cr" class="form-control" style="display: inline; width:70%;"></span>
                        </td>
                        <!-- <td>                            
                            <span><strong>GL</strong></span>
                            <span><input type="text" name="biochemistry_gl" class="form-control" style="display: inline; width:70%;"></span>
                        </td> -->
                        
                        <td><strong> Serum electrolyte <strong></td>
                        <td>                            
                            <span><strong>K</strong></span>
                            <span><input type="text" name="biochemistry_k" class="form-control" style="display: inline; width:70%;"></span>
                        </td>
                        <td>                            
                            
                            <span>Ca <input type="text" name="biochemistry_ca" class="form-control" style="display: inline; width:70%;"></span>
                        </td>                        
                        <td>                            
                            <span><strong>Na</strong></span>
                            <span><input type="text" name="biochemistry_na" class="form-control" style="display: inline; width:70%;"></span>
                        </td>
                        <td>                            
                            <span><strong>CL</strong></span>
                            <span><input type="text" name="biochemistry_cl" class="form-control" style="display: inline; width:70%;"></span>
                        </td>
                    </tr>
                   
                   <tr>
                    <td colspan="3">LFT
                            <input type="text" name="lft" style="display: inline; width:80%;"  class="form-control"/>
                        </td>
                   </tr>
                    
                    <tr>                        
                        
                        <td width="100" colspan="5">Hormones
                            <textarea type="text" name="other_hormones" rows="1" class="form-control" style="display: inline; width:70%;"></textarea>
                        </td>
                        <td width="100" colspan="2">CT scan findings:
                            <textarea type="text" name="ct_scan_findings" rows="1" class="form-control" style="display: inline; width:70%;"></textarea>
                        </td>
                        
                        <td width="100" colspan="2">MRI:
                            <textarea type="text" name="MRI" rows="1" class="form-control" style="display: inline; width:70%;"></textarea>
                        </td>
                    </tr>
                   
                    <tr>
                        <td><span><strong>Blood Gas: </strong></span></td>
                        <td>                            
                            <span><strong>FiO₂</strong></span>
                            <span><input type="text" name="blood_gas_FiO2" class="form-control" style="display: inline; width:70%;"  ></span>
                        </td>
                        <td>                            
                            <span><strong>PH</strong></span>
                            <span><input type="text" name="blood_gas_ph" class="form-control" style="display: inline; width:70%;"></span>
                        </td>
                        <td>                            
                            <span><strong>PO₂</strong></span>
                            <span><input type="text" name="blood_gas_sao2" class="form-control" style="display: inline; width:70%;"></span>
                        </td>
                        <td>                            
                            <span><strong>PCO₂</strong></span>
                            <span><input type="text" name="blood_gas_pco2" class="form-control" style="display: inline; width:70%;"></span>
                        </td>
                        <td>                            
                            <span><strong>HCO3</strong></span>
                            <span><input type="text" name="blood_gas_bic" class="form-control" style="display: inline; width:70%;"></span>
                        </td>
                        <td colspan='2'>                            
                            <span><strong>BE</strong></span>
                            <span><input type="text" name="blood_gas_be" class="form-control" style="display: inline; width:70%;"></span>
                        </td> 
                    </tr>
                    <tr>
                        
                        <td width="100" colspan="2">CXR findings:
                            <textarea type="text" name="cxr_findings" rows="1" class="form-control" style="display: inline; width:80%;"></textarea>
                        </td>
                        <td width="100" colspan="2">ECG Findings:
                            <textarea type="text" name="ecg_findings" rows="1" class="form-control" style="display: inline; width:80%;"></textarea>
                        </td>
                        <td width="100" colspan="2">ECHO findings:
                            <textarea type="text" name="echo_findings" rows="1" class="form-control" style="display: inline; width:70%;"></textarea>
                        </td>
                        
                        <td colspan="2">
                            <button class="btn btn-primary pull-right" style="width: 250px;" type="submit" name="Investigation">Save</button>
                        </td>
                    </tr>
                    
                    <?php } ?>
                    
            </table>
        </form>
        <hr>
    <form action="ajax_anasthesia_record_information.php" method="POST" >
    <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
    <input type="text" hidden  name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID?>" >
    
    <table class="table">
        <thead>
            <tr>
                <th colspan="8" id="th">PREMEDICATION / ANESTHESIA ORDERS:</th>
            </tr>
        </thead>
        <tbody>
        <?php if((mysqli_num_rows($select_premedication_result))>0){
            while($premedication_row = mysqli_fetch_assoc($select_premedication_result)){
                ?>
            <tr>
                <td colspan="2">
                        <span>
                            <label for="">Fasting From/For: </label>
                            <textarea   name="fasting_for" id="" rows="1"><?php echo $premedication_row['fasting_for'];?></textarea>
                        </span>
                    </td>
                    <td colspan="2">
                        <span>
                            <label for="">Medication at order: </label>
                            <textarea  name="medication_at_night" id="" rows="1"><?php echo $premedication_row['medication_at_night'];?></textarea>
                        </span>
                    </td>
                    <td colspan="2">
                        <span>
                            <label for="">Medication in the morging: </label>
                            <textarea  name="medication_morning" id="" rows="1"><?php echo $premedication_row['medication_morning'];?></textarea>
                        </span>
                    </td>
                    <td colspan="">
                        <span>
                            <label for="">Other Orders </label>
                            <textarea  name="orders_standby_blood" id="" rows="1"><?php echo $premedication_row['orders_standby_blood'];?></textarea>
                        </span>
                    </td>
                    <td colspan="">
                        <span>
                            <label for="">Blood Product </label>
                            <input type="text" class="form-control" name="blood_unit" value="<<?php echo $premedication_row['blood_unit'];?>>" id="" >
                        </span>
                    </td>
            </tr>
            <tr>
                <td colspan="4">
                    <span>
                        <label for="">Anticipated anesthetic risks:</label>
                        <textarea name="anticipated_anesthetic_risks" id="" readonly cols="2" class="form-control" ><?php echo $premedication_row['anticipated_anesthetic_risks'];?></textarea>
                    </span>
                </td>
                
                <td colspan="4">
                    <span>
                        <label for="">Proposed anesthesia:</label>
                        <textarea name="" id="" readonly cols="2" class="form-control" > <?php echo $premedication_row['proposed_anasthesia'];?></textarea>
                    </span>
                </td>
            </tr>
            
            <tr>
                <td colspan="">
                    <label for="">ANESTHESIOLOGIST OPINION/ COMMENTS:</label>
                </td>
               
                <td colspan="7">
                <textarea name="" id="" readonly cols="2" class="form-control" ><?php echo $premedication_row['anesthesiologist_opinion'];?></textarea>
                </td>
            </tr>
            <?php }}else{?>
                <tr>
                    <td colspan="2">
                        <span>
                            <label for="">Fasting From /For: </label>
                            <textarea   name="fasting_for" id="" rows="1"></textarea>
                        </span>
                    </td>
                    <td colspan="2">
                        <span>
                            <label for="">Medication at night: </label>
                            <textarea  name="medication_at_night" id="" rows="1"></textarea>
                        </span>
                    </td>
                    <td colspan="2">
                        <span>
                            <label for="">Medication in the morning: </label>
                            <textarea  name="medication_morning" id="" rows="1"></textarea>
                        </span>
                    </td>
                    <!-- <td colspan="">
                        <span>
                            <label for="">Other Orders: </label>
                            <textarea  name="orders_standby_blood" id="" rows="1"></textarea>
                        </span>
                    </td> -->
                    <td colspan="">
                        <span>
                            <label for="">Blood Products </label>
                            <input type="text" class="form-control" name="blood_unit" id="" >
                        </span>
                    </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span>
                        <label for="">Anticipated anesthetic risks:</label>
                        <textarea name="anticipated_anesthetic_risks" id="" rows="1" class="form-control" ></textarea>
                    </span>
                </td>
                <td colspan="2">
                        <label for="">Pre-Anaesthetic Orders</label>
                        <textarea  style="display: inline; width:60%;" rows="1" name="pre_anaesthetic_orders"><?php echo $exam_row['pre_anaesthetic_orders'];?></textarea>
                    </td>
                <td colspan="4">
                    <span>
                        <label for="">Proposed anesthesia technique:</label>
                        <textarea name="proposed_anasthesia" id="" rows="1" class="form-control" ></textarea>
                    </span>
                </td>
            </tr>
            
            <tr>
                <td colspan="5">
                    <label for="">ANESTHESIOLOGIST OPINION/ COMMENTS:</label>
                    <textarea type="text" class="form-control" rows="1" name="anesthesiologist_opinion"></textarea>
                </td>
                
                <td colspan="3">
                    <button type="submit" class="btn btn-primary pull-right" style="width: 300px;" name="premedication"> Save</button>
                </td>
            </tr>
            <?php }?>
           
        </tbody>
    </table>
</form>

    <hr>

    <br>

    <table class="table">       
        <tr>
            <th colspan="6" width="100%" style="background: #dedede;"><b>INTRAOP RECORD </b></th>
        </tr>
        <form action="ajax_anasthesia_record_information.php" method="POST" >
        <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
        <input type="text" hidden  name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID?>" >
        <input type="hidden" name="anasthesia_record_id" id="anasthesia_record_id" value="<?=$anasthesia_record_id; ?>">
        <tr>
            <td colspan="6">
            <?php if(mysqli_num_rows($Select_intraop_record)>0){ 
                    while($intraoprows = mysqli_fetch_assoc($Select_intraop_record)){

                        if($intraoprows['patient_fasted']=="Yes"){
                            $checked_yes= "checked = 'checked'";
                        }else if($intraoprows['patient_fasted']=="No"){
                            $checked_no= "checked= 'checked'";
                        }

                        if($intraoprows['type_of_surgery']=="Yes"){
                            $Elective= "checked = 'checked'";
                        }else if($intraoprows['type_of_surgery']=="No"){
                            $Emergence= "checked= 'checked'";
                        }
                        if($intraoprows['intubation']=="Yes"){
                            $Awake= "checked = 'checked'";
                        }else if($intraoprows['intubation']=="No"){
                            $Asleep= "checked= 'checked'";
                        }
                        if($intraoprows['induction']=="Yes"){
                            $IV= "checked = 'checked'";
                        }else if($intraoprows['induction']=="No"){
                            $inhalation= "checked= 'checked'";
                        }else if($intraoprows['induction']=="No"){
                            $RSI= "checked= 'checked'";
                        }

                        if($intraoprows['Comments']=="Yes"){
                            $Easy= "checked = 'checked'";
                        }else if($intraoprows['Comments']=="No"){
                            $Difficult= "checked= 'checked'";
                        }

                        if($intraoprows['Ventilation']=="Yes"){
                            $Easy= "checked = 'checked'";
                        }else if($intraoprows['Ventilation']=="No"){
                            $Difficult= "checked= 'checked'";
                        }

                        if($intraoprows['Airway']=="Yes"){
                            $Spont= "checked = 'checked'";
                        }else if($intraoprows['Airway']=="No"){
                            $Cont= "checked= 'checked'";
                        }

                        if($intraoprows['Maintainance']=="air"){
                            $air= "checked = 'checked'";
                        }else if($intraoprows['Maintainance']=="o2"){
                            $o2= "checked= 'checked'";
                        }else if($intraoprows['Maintainance']=="Heloth"){
                            $Heloth= "checked= 'checked'";
                        }else if($intraoprows['Maintainance']=="Isofl"){
                            $Isofl= "checked= 'checked'";
                        }else if($intraoprows['Maintainance']=="Sevofl"){
                            $Sevofl= "checked= 'checked'";
                        }
                        
                    ?>
                <table class="table">
                    <tr>
                        <td colspan="4" width="100%" style="background: #dedede;"><b>General Anesthesia:</b></td>
                    </tr>
                    <tr>
                        <td width="50%" colspan="2"><b>Has patient fasted: </b>
                            Yes <input type="radio" value="Yes" name='patient_fasted' <?= $checked_yes?> style="display:inline;" id='patient_fasted'>
                            No <input type="radio" value="No" name='patient_fasted' <?= $checked_no ?> style="display:inline;" id='patient_fasted'>
                            Elective Surgery <input type="radio" value="Elective" <?= $Elective ?> name='type_of_surgery' style="display:inline;" id='type_of surgery'>
                            Emergency Surgery <input type="radio" value="Emergence" <?= $Emergence ?> name='type_of_surgery' style="display:inline;" id='type_of surgery'>
                        </td>
                        <td width="50%" colspan="2">
                        IV Sites <input type="text" readonly  value="<?php echo $intraoprows['IV_sites']; ?>" name='IV_sites' style="display:inline; width:20% " class="form-control"   id='IV_sites'>
                        IV Sites <input type="text" readonly  value="<?php echo $intraoprows['IV_size']; ?>" name='IV_size' style="display:inline; width:20% " class="form-control"   id='IV_size'>
                        Central Line <input type="text" readonly  value="<?php echo $intraoprows['Cental_line']; ?>"  style="display:inline; width:20%"  class="form-control" id='Cental_line'>
                        Central Line size<input type="text" readonly  value="<?php echo $intraoprows['Cental_line_size']; ?>"  style="display:inline; width:20%"  class="form-control" id='Cental_line_size'>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Induction: </b>
                            IV<input type="radio" value="IV" name="induction" style="display:inline;" id="IV" <?= $IV?>>
                            Inhalation <input type="radio" value="inhalation" name="induction" style="display:inline;" id="inhalation" <?= $inhalation ?>>
                            RSI  <input type="radio" name="induction" value="RSI" style="display:inline;" id="RSI" <?= $RSI ?>>
                        </td>
                        <td colspan=""><b>Intubation: </b>
                            Awake<input type="radio" value="Aweke" name="intubation" style="display:inline;" id="Awake" <?= $Awake ?>>
                            Asleep <input type="radio" value="Asleep" name="intubation" style="display:inline;" id="Asleep" <?= $Asleep ?>>                            
                        </td>
                        <td colspan=""><b>Comments: </b>
                            Easy<input type="radio" value="Easy" name="Comments" <?= $Easy ?> style="display:inline;" id="Easy" >
                            Difficult <input type="radio" value="Difficult" name="Comments" <?= $Difficult ?> style="display:inline;" id="Difficult">                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><b> <input type="text" readonly  value="<?php echo $intraoprows['Nasal']; ?>" name='Nasal' style="display:none; width:20% " class="form-control"   id='IV_sites'>
                        ETT: Type: <input type="text" readonly  value="<?php echo $intraoprows['Ett_type']; ?>"  style="display:inline; width:30%"  class="form-control" name='Ett_type'>
                        Size <input type="text" readonly   value="<?php echo $intraoprows['Ett_size']; ?>" style="display:inline; width:20%"  class="form-control" name='Ett_size'>
                        
                        </td>
                        <td colspan=""><b>Airway: </b>
                            Mask<input type="radio" name="Airway"  style="display:inline;" id="" value="Mask" <?= $Mask?> >
                            LMA <input type="radio" name="Airway" style="display:inline;" id="" value="LMA" <?= $LMA ?>>                            
                        </td>
                        <td colspan=""><b>Circuit: </b>
                            Circle<input type="radio" name="Circuit" style="display:inline;" id="Circle" <?= $Circle ?>>
                            T-piece <input type="radio" name="Circuit" style="display:inline;" id="T_piece" <?= $T_piece ?>>                        
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"><b>Ventilation: </b>
                            Spont<input type="radio" id="" style="display:inline;" name="Ventilation" <?= $Spont ?> >
                            cont <input type="radio" id="" style="display:inline;" name="Ventilation" <?= $Cont?>> 
                            RR<input type="text" readonly  value="<?php echo $intraoprows['RR']; ?>" style="display:inline; width:15%;" name="RR" >
                            TV <input type="text" readonly  value="<?php echo $intraoprows['TV']; ?>" style="display:inline; width:15%;" name="TV"> 
                            Press <input type="text" readonly  value="<?php echo $intraoprows['Press']; ?>" style="display:inline; width:15%;" name="Press"> 
                            PEEP<input type="text" readonly  value="<?php echo $intraoprows['PEEP']; ?>" style="display:inline; width:15%;" name="PEEP" >
                            I:E <input type="text" readonly  value="<?php echo $intraoprows['I_E']; ?>" style="display:inline; width:15%;" name="I_E">                           
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"><b>Anesth-Maintainance: </b>
                            air<input type="checkbox" <?php echo  $intraoprows['air']; ?> value="air" style="display:inline;" name="Maintainance" >
                            O₂ <input type="checkbox" <?php echo $intraoprows['o2']; ?> value="o2" style="display:inline;" name="Maintainance"> 
                            Haloth<input type="checkbox" <?php echo $intraoprows['Heloth']; ?> value="heloth" style="display:inline; " name="Maintainance" >
                            Isofl <input type="checkbox" <?php echo  $intraoprows['Isolf']; ?> value="isolf" style="display:inline; " name="Maintainance"> 
                            Other <input type="text" readonly  name="" value="<?php echo $intraoprows['Other_anasth']; ?>" style="display:inline; width:70%;" name="Other_anasth">                         
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <b>IV Fluid</b>
                            <?php echo $intraoprows['IV_fluid']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" width="100%" style="background: #dedede;"><b>Local/Regional:</b></td>
                    </tr>
                    <tr>
                        <td>Type: <input type="text" readonly  value="<?php echo $intraoprows['Type']; ?>" name="" style="display:inline; width:60%;" name="Type">  </td>
                        <td>Agent: <input type="text" readonly  value="<?php echo $intraoprows['Agent']; ?>" name="" style="display:inline; width:20%;" name="Agent"> 
                        Conc: <input type="text" readonly  value="<?php echo $intraoprows['Conc']; ?>" name="" style="display:inline; width:20%;" name="Conc"> </td>
                        <td>  
                        Amount: <input type="text" readonly  value="<?php echo $intraoprows['Amount']; ?>" name="" style="display:inline; width:30%;" name="Amount">
                        Position: <input type="text" readonly  value="<?php echo $intraoprows['Position']; ?>" name="" style="display:inline; width:30%;" name="Position"> </td>
                        <td>  
                         
                        Comments: <input type="text" readonly  value="<?php echo $intraoprows['Comments']; ?>" name="" style="display:inline; width:70%;" name="Comments">  </td>
                    </tr>
                   
                </table>
                <?php }} else{?>
                    <table class="table">
                        <tr>
                            <td colspan="4" width="100%" style="background: #dedede;"><b>General Anesthesia:</b></td>
                        </tr>
                        <tr>
                            <td width="50%" colspan="2"><b>Has patient fasted: </b>
                                Yes <input type="radio" value="Yes" name='patient_fasted' style="display:inline;" id='patient_fasted'>
                                No <input type="radio" value="No" name='patient_fasted' style="display:inline;" id='patient_fasted'>
                                <input type="text" value="" name='Period_fasted' value" <?= $checked_no ?> " style="display:inline; width:20%" placeholder="Fasting Period" id='patient_fasted'>
                                Elective Surgery <input type="radio" value="Elective" name='type_of_surgery' style="display:inline;" id='type_of surgery'>
                                Emergency Surgery <input type="radio" value="Emergence" name='type_of_surgery' style="display:inline;" id='type_of surgery'>
                            </td>
                            <td width="50%" colspan="2">
                            IV Sites <input type="text" name='IV_sites' style="display:inline; width:15% " class="form-control"   id='IV_sites'>
                            IV Size <input type="text" name='IV_size' style="display:inline; width:15% " class="form-control"   id='IV_size'>
                            Central Line <input type="text" name="Cental_line" style="display:inline; width:15%"  class="form-control" id='Cental_line'>
                            Size <input type="text"  name="Cental_line_size" style="display:inline; width:15%"  class="form-control" id='Cental_line_size'>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Induction: </b>
                                IV<input type="checkbox" value="IV" name="induction" style="display:inline;" id="IV">
                                Inhalation <input type="checkbox" value="inhalation" name="induction" style="display:inline;" id="inhalation">
                                RSI  <input type="checkbox" name="induction" value="RSI" style="display:inline;" id="RSI">
                            </td>
                            <td colspan=""><b>Intubation: </b>
                                Awake<input type="radio" value="Aweke" name="intubation" style="display:inline;" id="Awake" >
                                Asleep <input type="radio" value="Asleep" name="intubation" style="display:inline;" id="Asleep">                            
                            </td>
                            <td colspan=""><b>Comments: </b>
                                Easy<input type="radio" value="Easy" name="Comments" style="display:inline;" id="Easy" >
                                Difficult <input type="radio" value="Difficult" name="Comments" style="display:inline;" id="Difficult">                            
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><b></b>
                            ETT: Type: <input type="text"  style="display:inline; width:30%"  class="form-control" name='Ett_type'>
                            Size <input type="text"  style="display:inline; width:20%"  class="form-control" name='Ett_size'>
                            
                            </td>
                            <td colspan=""><b>Airway: </b>
                                Mask<input type="radio" name="Airway"  style="display:inline;" id="" value="Mask" >
                                LMA <input type="radio" name="Airway" style="display:inline;" id="" value="LMA">                            
                            </td>
                            <td colspan=""><b>Circuit: </b>
                                Circle<input type="radio" name="Circuit" style="display:inline;" id="Circle">
                                T-piece <input type="radio" name="Circuit" style="display:inline;" id="T_piece">                        
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"><b>Ventilation: </b>
                                Spont<input type="radio" id="" style="display:inline;" name="Ventilation" value="Spont">
                                cont <input type="radio" id="" style="display:inline;" name="Ventilation" value="cont"> 
                                RR<input type="text" id="" style="display:inline; width:15%;" name="RR" >
                                TV <input type="text" id="" style="display:inline; width:15%;" name="TV"> 
                                Press <input type="text" id="" style="display:inline; width:15%;" name="Press"> 
                                PEEP<input type="text" id="" style="display:inline; width:15%;" name="PEEP" >
                                I:E <input type="text" id="" style="display:inline; width:15%;" name="I_E">                           
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"><b>Anesth-Maintainance: </b>
                                air<input type="checkbox" value="air" style="display:inline;" name="Maintainance" >
                                O₂ <input type="checkbox" value="o2" style="display:inline;" name="Maintainance"> 
                                Haloth<input type="checkbox" value="heloth" style="display:inline; " name="Maintainance" >
                                Isofl <input type="checkbox" value="isolf" style="display:inline; " name="Maintainance"> 
                                Sevofl <input type="checkbox" value="Sevofl" style="display:inline; " name="Maintainance">
                                Other <input type="text" name="" style="display:inline; width:60%;" name="Other_anasth">                         
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <b>IV Fluid</b>
                                <input type="text" id="IV_fluid" style="display:inline; width:85%;" name="IV_fluid">  
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" width="100%" style="background: #dedede;"><b>Local/Regional:</b></td>
                        </tr>
                        <tr>
                            <td>Type: <input type="text"  style="display:inline; width:60%;" name="Type">  </td>
                            <td>Agent: <input type="text"  style="display:inline; width:20%;" name="Agent"> 
                            Conc: <input type="text"  style="display:inline; width:20%;" name="Conc"> </td>
                            <td>  
                            Amount: <input type="text"  style="display:inline; width:30%;" name="Amount">
                            Position: <input type="text"  style="display:inline; width:30%;" name="Position"> </td>
                            <td>  
                            
                            Comments: <input type="text"  style="display:inline; width:70%;" name="Comments">  </td>
                        </tr>
                        <tr>
                            <td colspan="4" width="100%" style="background: #dedede;"><button type="submit" name="intraop_record" class="btn btn-primary pull-right" style="width:15%">Save</button></td>
                        </tr>
                    </table>
                <?php } ?>
            </td>
        </tr>
        </form>
        
    </table>

    <hr>
        <form action="ajax_anasthesia_record_information.php" method="POST">
            <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
            <input type="text" hidden  name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID?>" >
            <table class="table">
                <tr>
                    <th id="th" colspan=""> <b>TECHNIQUE</b> </th>
                    <th id="th"> <b>RESPIRATION</b> </th>
                </tr>
            <?php 
            if((mysqli_num_rows($select_cannulation))>0){ while($cannulation_rw= mysqli_fetch_assoc($select_cannulation)){ $checked_yes=""; $checked_no="";?>

                <tr>
                    <th >
                        <?php
                            $checked_GA="";
                            $checked_Regional="";
                            $checked_Local ="";
                            $checked_Sedation="";
                            if($cannulation_rw['technic']=="GA"){
                                $checked_GA= "checked = 'checked'";
                            }else if($cannulation_rw['technic']=="Regional"){
                                $checked_Regional= "checked= 'checked'";
                            }else if($cannulation_rw['technic']=="Local"){
                                $checked_Local ="checked='checked'";
                            }else if($cannulation_rw['technic']=="Sedation"){
                                $checked_Sedation ="checked='checked'";
                            }
                        ?>
                        <span>
                            <label for="">GA</label>
                            <input type="radio" name="technic"  value="GA" <?php echo $checked_GA;?>>
                        </span>
                        <span>
                            <label for="">Regional</label>
                            <input type="radio" name="technic"  value="Regional" <?php echo $checked_Regional;?>>
                        </span>
                        <span>
                            <label for="">Local</label>
                            <input type="radio" name="technic"  value="Local" <?php echo $checked_Local;?>>
                        </span>
                        <span>
                            <label for="">Sedation</label>
                            <input type="radio" name="technic"  value="Sedation" <?php echo $checked_Sedation;?>>
                        </span>
                    </th>
                    <th>
                    <?php
                            $checked_Spontaneous="";
                            $checked_Assisted="";
                            $checked_Controlled ="";
                            if($cannulation_rw['Respiration']=="Spontaneous"){
                                $checked_Spontaneous= "checked = 'checked'";
                            }else if($cannulation_rw['Respiration']=="Assisted"){
                                $checked_Assisted= "checked= 'checked'";
                            }else if($cannulation_rw['Respiration']=="Controlled"){
                                $checked_Controlled ="checked='checked'";
                            }
                        ?>
                        <span>
                                    <label for="">Spontaneous</label>
                                    <input type="radio" name="Respiration"  value="Spontaneous" <?php echo $checked_Spontaneous;?>>
                                </span>
                                <span>
                                    <label for="">Assisted</label>
                                    <input type="radio" name="Respiration"  value="Assisted" <?php echo $checked_Assisted;?>>
                                </span>
                                <span>
                                    <label for="">Controlled</label>
                                    <input type="radio" name="Respiration"  value="Controlled" <?php echo $checked_Controlled;?>>
                                </span>
                    </th>
                    <!-- <td> -->
                    <?php
                            $checked_Circle="";
                            $checked_Semicircle="";
                            $checked_Open ="";
                            if($cannulation_rw['B_circle']=="Circle"){
                                $checked_Circle= "checked = 'checked'";
                            }else if($cannulation_rw['B_circle']=="Semicircle"){
                                $checked_Semicircle= "checked= 'checked'";
                            }else if($cannulation_rw['B_circle']=="Open"){
                                $checked_Open ="checked='checked'";
                            }
                        ?>
                       
                </tr>
                <tr>
                    <th colspan="2" id="th">INTUBATION</th>
                </tr>
                <tr>
                    <td>
                        <?php
                            $checked_Oral="";
                            $checked_Nasal="";
                            $Trackeostomy ="";
                            $checked_LMA="";
                            $intubations =explode(',', $cannulation_rw['intubation']);
                           
                            foreach($intubations as $intubation){
                                if($intubation=="Oral"){
                                    $checked_Oral= "checked = 'checked'";
                                }else if($intubation=="Nasal"){
                                    $checked_Nasal= "checked= 'checked'";
                                }else if($intubation=="Trackeostomy"){
                                    $Trackeostomy ="checked='checked'";
                                }else if($intubation=="LMA"){
                                    $checked_LMA ="checked='checked'";
                                }
                            }
                        ?>
                        <span>
                            <label for="">Oral</label>
                            <input type="checkbox" name="intubation"  value="Oral" <?php echo $checked_Oral;?>>
                        </span>
                        <span>
                            <label for="">Nasal</label>
                            <input type="checkbox" name="intubation"  value="Nasal" <?php echo $checked_Nasal;?>>
                        </span>

                        <span>
                            <label for="">Tracheostomy</label>
                            <input type="checkbox" name="intubation"  value="Trackeostomy" <?= $Trackeostomy ?>>
                        </span>
                        Others : <?php echo  $cannulation_rw['others_intubation']; ?>
                    </td>
                    <td><label for="">View Grade:  </label> 
                        <?php
                            $checked_1="";
                            $checked_2="";
                            $checked_3 ="";
                            $checked_4="";
                            if($cannulation_rw['view_grade']=="1"){
                                $checked_1= "checked = 'checked'";
                            }else if($cannulation_rw['view_grade']=="2"){
                                $checked_2= "checked= 'checked'";
                            }else if($cannulation_rw['view_grade']=="3"){
                                $checked_3 ="checked='checked'";
                            }else if($cannulation_rw['view_grade']=="4"){
                                $checked_4 ="checked='checked'";
                            }
                        ?>
                        <span>
                            <label for="">1</label>
                            <input type="radio" name="view_grade"  value="1" <?php echo $checked_1;?>>
                        </span>
                        <span>
                            <label for="">2</label>
                            <input type="radio" name="view_grade"  value="2" <?php echo $checked_2;?>>
                        </span>
                        <span>
                            <label for="">3</label>
                            <input type="radio" name="view_grade"  value="3" <?php echo $checked_3;?>>
                        </span>
                        <span>
                            <label for="">4</label>
                            <input type="radio" name="view_grade"  value="4" <?php echo $checked_4;?>>
                        </span>
                    </td>
                    </tr>
                        <tr>
                    <td colspan='2'> <b>ETT</b> 
                         <span>
                            <label for="">Size:  </label>
                            <input type="text" style="display:inline; width:20%" class="form-control" name="size" id="" value="<?php echo $cannulation_rw['size']; ?>" >
                        </span>
                        <span>
                            <label for="">Depth:  </label>
                            <input type="text" style="display:inline; width:20%" class="form-control" name="Depth" id="" value="<?php echo $cannulation_rw['Depth']; ?>">
                        </span>
                        
                    </td>                    
                </tr>
                        <tr>
                        
                   <!-- <td>
                        <?php
                            $checked_L="";
                            $checked_R="";
                            $checked_YES ="";
                            $checked_NO="";
                            if($cannulation_rw['Cannulation_side']=="L"){
                                $checked_L= "checked = 'checked'";
                            }else if($cannulation_rw['Cannulation_side']=="R"){
                                $checked_R= "checked= 'checked'";
                            }
                            if($cannulation_rw['Cent_L']=="YES"){
                                $checked_YES ="checked='checked'";
                            }else if($cannulation_rw['Cent_L']=="NO"){
                                $checked_NO ="checked='checked'";
                            }
                        ?>
                        <span><b> Cannulation Side :  </b>
                             <label for=""> L</label>
                            <input type="radio" name="Cannulation_side"  <?php echo $checked_L; ?> value="L">
                        </span>
                        <span>
                            <label for="">R</label>
                            <input type="radio" name="Cannulation_side"  <?php echo $checked_R; ?> value="R">
                        </span>
                        <span><b>Central Line : </b>
                            <label for=""> Yes</label>
                            <input type="radio" name="Cent_L" id="Central_line" <?php echo $checked_YES; ?> value="YES">
                            <input type="text" name="Central_Line_yes_value" id="Central_Line_yes_value"  value="YES">

                        </span>
                        <span>
                            <label for="">No</label>
                            <input type="radio" name="Cent_L"  <?php echo $checked_NO; ?> value="NO">
                        </span> 
                    </td>
                    <td>-->
                    <?php
                            $checked_Arm="";
                            $checked_Wrist="";
                            $checked_Hand ="";
                            $checked_Leg="";
                            $checked_Foot="";
                            if($cannulation_rw['Cannulation_on']=="Arm"){
                                $checked_Arm= "checked = 'checked'";
                            }else if($cannulation_rw['Cannulation_on']=="Wrist"){
                                $checked_Wrist= "checked= 'checked'";
                            }else if($cannulation_rw['Cannulation_on']=="Hand"){
                                $checked_Hand ="checked='checked'";
                            }else if($cannulation_rw['Cannulation_on']=="Leg"){
                                $checked_Leg ="checked='checked'";
                            }else if($cannulation_rw['Cannulation_on']=="Foot"){
                                $checked_Foot ="checked='checked'";
                            }
                        ?>
                        <!-- <span>
                            <label for="">Arm</label>
                            <input type="radio" name="Cannulation_on" <?php echo $checked_Arm;?>  value="Arm" >
                        </span>
                        <span>
                            <label for="">Wrist</label>
                            <input type="radio" name="Cannulation_on" <?php echo $checked_Wrist;?>  value="Wrist">
                        </span>
                        <span>
                            <label for="">Hand</label>
                            <input type="radio" name="Cannulation_on" <?php echo $checked_Hand;?>  value="Hand">
                        </span>
                        <span>
                            <label for="">Leg</label>
                            <input type="radio" name="Cannulation_on" <?php echo $checked_Leg;?>  value="Leg">
                        </span> 
                        <span>
                            <label for="">Foot</label>
                            <input type="radio" name="Cannulation_on" <?php echo $checked_Foot;?>  value="Foot">
                        </span>                       
                    </td> -->
                </tr>
                
            <?php }}else{?>
                <tr>
                    <th>
                        <span>
                            <label for="">GA</label>
                            <input type="radio" name="technic"  value="GA" >
                        </span>
                        <span>
                            <label for="">Regional</label>
                            <input type="radio" name="technic"  value="Regional">
                        </span>
                        <span>
                            <label for="">Local</label>
                            <input type="radio" name="technic"  value="Local">
                        </span>
                        <span>
                            <label for="">Sedation</label>
                            <input type="radio" name="technic"  value="Sedation">
                        </span>
                    </th>
                    <td>
                        <span>
                                    <label for="">Spontaneous</label>
                                    <input type="radio" name="Respiration"  value="Spontaneous" >
                                </span>
                                <span>
                                    <label for="">Assisted</label>
                                    <input type="radio" name="Respiration"  value="Assisted">
                                </span>
                                <span>
                                    <label for="">Controlled</label>
                                    <input type="radio" name="Respiration"  value="Controlled">
                                </span>
                    </td>
                   
                </tr>
                <tr>
                    <th colspan="2" id="th">INTUBATION</th>
                </tr>
                <tr>
                    <td>
                        <span>
                            <label for="">Oral</label>
                            <input type="checkbox" name="intubation[]"  value="Oral" >
                        </span>
                        <span>
                            <label for="">Nasal</label>
                            <input type="checkbox" name="intubation[]"  value="Nasal">
                        </span>
                        <span>
                            <label for="">Tracheostomy</label>
                            <input type="checkbox" name="intubation[]"  value="Trackeostomy">
                        </span>
                        <span>
                            <label for="">Others</label>
                            <input type="text" name="others_intubation" style="width: 30%;"  value="">
                        </span>
                    </td>
                    <td><label for="">View Grade:  </label> 
                        <span>
                            <label for="">1</label>
                            <input type="radio" name="view_grade"  value="1" >
                        </span>
                        <span>
                            <label for="">2</label>
                            <input type="radio" name="view_grade"  value="2">
                        </span>
                        <span>
                            <label for="">3</label>
                            <input type="radio" name="view_grade"  value="3">
                        </span>
                        <span>
                            <label for="">4</label>
                            <input type="radio" name="view_grade"  value="4">
                        </span>
                    </td>
                    </tr>
               
                <tr>
                    <td colspan='2'><b>ETT</b> 
                         <span>
                            <label for="">Size:  </label>
                            <input type="text" style="display:inline; width:20%" class="form-control" name="size" id="" >
                        </span>
                        <span>
                            <label for="">Depth:  </label>
                            <input type="text" style="display:inline; width:20%" class="form-control" name="Depth" id="" >
                        </span>
                        <span>
                            <label for="">Cuffed:  </label>
                            <input type="radio" style="display:inline;"  name="Blade" id="" >
                        </span>
                        <span>
                            <label for="">Uncuffed:  </label>
                            <input type="radio" style="display:inline;"  name="Blade" id="" >
                        </span>
                    </td>                    
                </tr>
               
                <tr>
                    <td colspan="2" style="padding-left: 17%;">
                        <button type="submit" class="btn btn-primary pull-right" name="save_cannulation" style="width: 250px; " >Save</button>
                    </td>
                </tr>
            <?php } ?>
            </table>
    </form>
    <hr>

    <form action="ajax_anasthesia_record_information.php" method="post">
            <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
            <input type="text" hidden  name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID?>" >
            <h4><b><center><u> Regional anaesthesia Procedure Note: </u></center></b></h4><br>
            <?php 
                if((mysqli_num_rows($select_nerve_block_procedure))>0){while($nerve_block_rw = mysqli_fetch_assoc($select_nerve_block_procedure)){
                   
                ?>
                <!-- Narve procedure recorde for the surgery -->
                <?php
                    $Employee_ID = $nerve_block_rw['Employee_ID'];
                    $select_employee = mysqli_query($conn, "SELECT Employee_Name, employee_signature FROM tbl_employee e WHERE e.Employee_ID='$Employee_ID' ") or die(mysqli_error($conn));
                    while($emp_rw = mysqli_fetch_assoc($select_employee)){
                        $Employee_Name = $emp_rw['Employee_Name']; 
                        $employee_signature = $emp_rw['employee_signature'];
                        if($employee_signature==""||$employee_signature==null){
                         $signature="________________________";
                     }else{
                         $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
                     }
                    }
                    ?>
                <div class="row">
                <div class="col-md-6">
                    <label for="">Performed By: </label>
                    <u><?php echo $Employee_Name; ?></u>
                </div>
                <div class="col-md-6">
                    <label for="">Type of block: </label>
                    <input type="text" name="type_of_block" required style="display: inline; width:60%;" readonly value="<?php echo $nerve_block_rw['type_of_block'];?>">
                </div>
            </div>
            <table class="table">
                <tr>
                    <td  style="background: #dedede;"><b>Safety check:</b></td>
                </tr>
                <tr>
                    <td>
                        <table class="table" style="border: red;">
                            
                            <tr >
                            <td>Consent Confirmed:
                                <?php
                                    $checked_yes="";
                                    $checked_no="";
                                    if($nerve_block_rw['consent_confirmed']=="YES"){
                                        $checked_yes= "checked = 'checked'";
                                    }else{
                                        $checked_no= "checked= 'checked'";
                                    }
                                ?>
                                <span>
                                    <label for="">Yes</label>
                                    <input type="radio" name="consent_confirmed" value="YES"  disabled <?php echo $checked_yes; ?>>
                                </span>
                                <span>
                                    <label for="">No</label>
                                    <input type="radio" name="consent_confirmed" value ="NO" disabled <?php echo $checked_no; ?> >
                                </span>
                            </td>
                            <td>Correct patient & surgical site confirmed:
                            <?php
                                    $checked_yes="";
                                    $checked_no="";
                                    if($nerve_block_rw['surgical_site']=="YES"){
                                        $checked_yes= "checked = 'checked'";
                                    }else{
                                        $checked_no= "checked= 'checked'";
                                    }
                                ?>
                                <span>
                                    <label for="">Yes</label>
                                    <input type="radio" name="surgical_site"  value="YES" disabled <?php echo $checked_yes; ?> >
                                </span>
                                <span>
                                    <label for="">No</label>
                                    <input type="radio" name="surgical_site"  value="NO" disabled <?php echo $checked_no; ?>>
                                </span>
                            </td>
                            <td>Mornitoring used
                            <?php
                                    $checked_ox="";
                                    $checked_bp="";
                                    if($nerve_block_rw['Mornitoring_used']=="YES"){
                                        $checked_ox= "checked = 'checked'";
                                    }else{
                                        $checked_bp= "checked= 'checked'";
                                    }
                                ?>
                                <span>
                                    <label for="">Pulse Ox</label>
                                    <input type="radio" name="Mornitoring_used" value="Pulse Ox" disabled <?php echo $checked_ox; ?>>
                                </span>
                                <span>
                                    <label for="">EKG BP</label>
                                    <input type="radio" name="Mornitoring_used"  value="EKG BP" disabled <?php echo $checked_bp; ?>>
                                </span>
                                <span>
                                    <label for="">BP</label>
                                    <input type="radio" name="Mornitoring_used"  value="BP" disabled <?php echo $checked_bp; ?>>
                                </span>
                            </td>
                            <?php
                                    $checked_bp_yes="";
                                    $checked_bp_no="";
                                    if($nerve_block_rw['working_IV']=="YES"){
                                        $checked_bp_yes= "checked = 'checked'";
                                    }else if ($nerve_block_rw['working_IV']=="NO"){
                                        $checked_bp_no= "checked= 'checked'";
                                    }
                                ?>
                             
                            
                            <?php
                                    $checked_iodine="";
                                    $checked_spirits="";
                                    if($nerve_block_rw['Prep_type']=="Iodine"){
                                        $checked_iodine= "checked = 'checked'";
                                    }else{
                                        $checked_spirits= "checked= 'checked'";
                                    }
                                ?>
                                <span>
                                    <label for="">Iodine</label>
                                    <input type="radio" name="Prep_type" value="Iodine" disabled <?php echo $checked_iodine; ?> >
                                </span>
                                <span>
                                    <label for="">Spirit</label>
                                    <input type="radio" name="Prep_type"  value="Spirits" disabled <?php echo $checked_spirits; ?>>
                                </span>
                            </td>
                            <td>20% Liquid emulsion available.
                                <?php
                                    $checked_yes="";
                                    $checked_no="";
                                    if($nerve_block_rw['Liquid_emulsion_available']=="YES"){
                                        $checked_yes= "checked = 'checked'";
                                    }else{
                                        $checked_no= "checked= 'checked'";
                                    }
                                ?>
                                <span>
                                    <label for="">Yes</label>
                                    <input type="radio" name="Liquid_emulsion_available"  value="YES" disabled <?php echo $checked_yes; ?>>
                                </span>
                                <span>
                                    <label for="">No</label>
                                    <input type="radio" name="Liquid_emulsion_available"  value="NO" disabled <?php echo $checked_no; ?>>
                                </span>
                            </td>
                        </tr>
                        </table>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="background: #dedede;"><b>Procedure </b></td>
            </tr>
            <tr>
                <td>
                    <table class="table">
                        <tr>
                            <td width="10%">
                                <span>
                                    <label for="">Needle type </label>
                                    <input type="text" style="display:inline; width:30%"  name="needle_type_length" id="" readonly value="<?php echo $nerve_block_rw['needle_type_length'];?>">
                                </span>
                                <span>
                                    <label for="">Needle length </label>
                                    <input type="text" style="display:inline; width:30%"  name="needle_length" id="" readonly value="<?php echo $nerve_block_rw['needle_length'];?>">
                                </span>
                            </td>
                            <td width="15%">
                                <span>
                                    <label for="">Pre-medication Given </label>
                                    <input type="text" style="display:inline; width:50%"  name="pre_medication_given" id="" readonly value="<?php echo $nerve_block_rw['pre_medication_given'];?>">
                                </span>
                            </td>
                            <td width="10%">
                                <span>
                                    <label for="">Time: </label>
                                    <input type="text" style="display:inline; width:60%"  name="time" id="" readonly value="<?php echo $nerve_block_rw['time'];?>">
                                </span>
                            </td>
                            <td width="15%">Nerve Simulator
                            <?php
                                    $checked_yes="";
                                    $checked_no="";
                                    if($nerve_block_rw['Simulator']=="YES"){
                                        $checked_yes= "checked = 'checked'";
                                    }else{
                                        $checked_no= "checked= 'checked'";
                                    }
                                ?>
                                    <span>
                                        <label for="">Yes</label>
                                        <input type="radio" name="Simulator"  value="YES" <?php echo $checked_yes; ?> >
                                    </span>
                                    <span>
                                        <label for="">No</label>
                                        <input type="radio" name="Simulator"  value="NO" <?php echo $checked_no; ?>>
                                    </span>
                            </td>
                            <td  width="30%">
                                <span>
                                    <label for="">Simulator Amplitude: </label>
                                    <input type="text" style="display:inline; width:20%"  name="Simulator_amplitude" id="" readonly value="<?php echo $nerve_block_rw['Simulator_amplitude'];?>" >
                                    (Duration = 0.1ms; Frequency = 1Hz)
                                </span>
                            </td>
                            
                        <tr>
                        <tr>
                            <td colspan='2'>
                                <span>
                                    <label for="">Local anaesthesia type, %: </label>
                                    <input type="text" style="display:inline; width:10%"  name="local_anaesthesia_type" id="" readonly value="<?php echo $nerve_block_rw['local_anaesthesia_type'];?>">

                                    
                                </span>
                                <span>
                                mls<input type="text" style="display:inline; width:10%"  name="local_anaesthesia_type" id="" readonly value="<?php echo $nerve_block_rw['local_anaesthesia_type'];?>">
                                </span>

                            </td>
                            <td width="20%">Paresthesia / Pain on Injection
                            <?php
                                    $checked_yes="";
                                    $checked_no="";
                                    if($nerve_block_rw['Paresthesia_Pain_on_Injection']=="YES"){
                                        $checked_yes= "checked = 'checked'";
                                    }else{
                                        $checked_no= "checked= 'checked'";
                                    }
                                ?>
                                <span>
                                    <label for="">Yes</label>
                                    <input type="radio" name="Paresthesia_Pain_on_Injection"  value="YES" <?php echo $checked_yes; ?>>
                                </span>
                                <span>
                                    <label for="">No</label>
                                    <input type="radio" name="Paresthesia_Pain_on_Injection"  value="NO" <?php echo $checked_no; ?>>
                                </span>
                            </td>
                            <td colspan="2">Utrasound Used
                            <?php
                                    $checked_yes="";
                                    $checked_no="";
                                    if($nerve_block_rw['Utrasound_used']=="YES"){
                                        $checked_yes= "checked = 'checked'";
                                    }else{
                                        $checked_no= "checked= 'checked'";
                                    }
                                    $Nerver_stimulator_used_yes="";
                                    $Nerver_stimulator_used_no="";
                                    if($nerve_block_rw['Nerver_stimulator_used']=="YES"){
                                        $Nerver_stimulator_used_yes= "checked = 'checked'";
                                    }else{
                                        $Nerver_stimulator_used_no= "checked= 'checked'";
                                    }
                                ?>
                                <span>
                                    <label for="">Yes</label>
                                    <input type="radio" name="Utrasound_used"  value="YES" <?php echo $checked_yes; ?>>
                                </span>
                                <span>
                                    <label for="">No</label>
                                    <input type="radio" name="Utrasound_used"  value="NO" <?php echo $checked_no; ?>>
                                </span>
                                Nerve Stimulator Used?
                                <span>
                                    <label for="">Yes</label>
                                    <input type="radio" name="Nerver_stimulator_used"  value="YES" <?php echo $Nerver_stimulator_used_yes; ?>>
                                </span>
                                <span>
                                    <label for="">No</label>
                                    <input type="radio" name="Nerver_stimulator_used"  value="NO" <?php echo $Nerver_stimulator_used_no; ?>>
                                </span>
                            </td>
                            <?php
                                    $checked_blood="";
                                    $checked_no_blood="";
                                    if($nerve_block_rw['Aspiration']=="Blood"){
                                        $checked_blood= "checked = 'checked'";
                                    }else  if($nerve_block_rw['Aspiration']=="No_blood"){
                                        $checked_no_blood= "checked= 'checked'";
                                    }
                                ?>
                                <span>
                                    <label for="">Blood</label>
                                    <input type="radio" name="Aspiration"  value="Blood" <?php echo $checked_blood; ?>>
                                </span>
                                <span>
                                    <label for="">No Blood</label>
                                    <input type="radio" name="Aspiration"  value="No_blood" <?php echo $checked_no_blood; ?>>
                                </span>
                            </td> 
                            <?php
                                    $checked_easy="";
                                    $checked_hard="";
                                    if($nerve_block_rw['Injection_pressure']=="Easy"){
                                        $checked_easy= "checked = 'checked'";
                                    }else   if($nerve_block_rw['Injection_pressure']=="Hard"){
                                        $checked_hard= "checked= 'checked'";
                                    }
                                ?>
                                <span>
                                    <label for="">Easy</label>
                                    <input type="radio" name="Injection_pressure"  value="Easy"<?php echo $checked_easy; ?> >
                                </span>
                                <span>
                                    <label for="">Hard</label>
                                    <input type="radio" name="Injection_pressure"  value="Hard" <?php echo $checked_hard; ?>>
                                </span>
                            </td> 
                            <?php
                                    $checked_general="";
                                    $checked_spinal="";
                                    if($nerve_block_rw['Anaesthetic_plan']=="general"){
                                        $checked_general= "checked = 'checked'";
                                    }else if($nerve_block_rw['Anaesthetic_plan']=="spinal"){
                                        $checked_spinal= "checked= 'checked'";
                                    }else if($nerve_block_rw['Anaesthetic_plan']=="block"){
                                        $checked_block ="checked='checked'";
                                    }
                                ?>
                                <span>
                                    <label for="">general</label>
                                    <input type="radio" name="Anaesthetic_plan"  value="general" <?php echo $checked_general; ?>>
                                </span>
                                <span>
                                    <label for="">spinal</label>
                                    <input type="radio" name="Anaesthetic_plan"  value="spinal" <?php echo $checked_spinal; ?>>
                                </span>
                                <span>
                                    <label for="">block</label>
                                    <input type="radio" name="Anaesthetic_plan"  value="block" <?php echo $checked_block; ?>>+/- sedation
                                </span>
                            </td>                             
                        </tr>
                        <tr>
                            
                            <td>
                                <span>
                                    <label for="">Time block performed</label>
                                    <input type="text" style="display:inline; width:50%"  name="time_block_performed" id="" readonly value="<?php echo $nerve_block_rw['time_block_performed'];?>" >
                                </span>
                            </td>
                            <td colspan="2">
                                <span>
                                    <label for="">Time into theatre</label>
                                    <input type="text" style="display:inline; width:50%"  name="time_into_theatre" id="" readonly value="<?php echo $nerve_block_rw['time_into_theatre'];?>" >
                                </span>
                            </td>
                            <td colspan="2">
                                <span>
                                    <label for="">Time to post-op ward</label>
                                    <input type="text" style="display:inline; width:50%"  name="time_to_post_op_ward" id="" readonly value="<?php echo $nerve_block_rw['time_to_post_op_ward'];?>" >
                                </span>
                            </td>
                           
                        </tr>
                    </table>
                </td>
            </tr>
         </table>
                <?php }}else{?>
                <!-- Null nurve block procedure for that surgery -->
            <div class="row">
                <div class="col-md-6">
                    <label for="">Performed By: </label>
                    <u><?php echo $_SESSION['userinfo']['Employee_Name']; ?></u>
                </div>
                <div class="col-md-6">
                    <label for="">Type of block: </label>
                    <input type="text" name="type_of_block" required style="display: inline; width:60%;" >
                </div>
            </div>
            <table class="table">
                <tr>
                    <td>
                        <table class="table" style="border: red;">
                            <tr>
                                <td colspan="6" style="background: #dedede;"><b>Safety check:</b></td>
                            </tr>
                            <tr >
                            <td>Consent Confirmed:
                                <span>
                                    <label for="">Yes</label>
                                    <input type="radio" name="consent_confirmed" value="YES"  )>
                                </span>
                                <span>
                                    <label for="">No</label>
                                    <input type="radio" name="consent_confirmed" value ="NO"  >
                                </span>
                            </td>
                            <td>Correct patient & surgical site confirmed:
                                <span>
                                    <label for="">Yes</label>
                                    <input type="radio" name="surgical_site"  value="YES" >
                                </span>
                                <span>
                                    <label for="">No</label>
                                    <input type="radio" name="surgical_site"  value="NO" >
                                </span>
                            </td>
                            <td>Mornitoring used
                                <span>
                                    <label for="">Pulse Ox</label>
                                    <input type="radio" name="Mornitoring_used" value="Pulse Ox" >
                                </span>
                                <span>
                                    <label for="">ECG</label>
                                    <input type="radio" name="Mornitoring_used"  value="EKG" >
                                </span>
                                <span>
                                    <label for="">BP</label>
                                    <input type="radio" name="Mornitoring_used"  value="BP" >
                                </span>
                            </td>
                           
                            <td>Prep type
                                <span>
                                    <label for="">Iodine</label>
                                    <input type="radio" name="Prep_type" value="Iodine" >
                                </span>
                                <span>
                                    <label for="">Spirit</label>
                                    <input type="radio" name="Prep_type"  value="Spirits" >
                                </span>
                            </td>
                            <td>20% Liquid emulsion available.
                                <span>
                                    <label for="">Yes</label>
                                    <input type="radio" name="Liquid_emulsion_available"  value="YES" >
                                </span>
                                <span>
                                    <label for="">No</label>
                                    <input type="radio" name="Liquid_emulsion_available"  value="NO">
                                </span>
                            </td>
                        </tr>
                        </table>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="background: #dedede;"><b>Procedure </b></td>
            </tr>
            <tr>
                <td>
                    <table class="table">
                        <tr>
                            <td width="30%">
                                <span>
                                    <label for="">Needle type </label>
                                    <input type="text" style="display:inline; width:30%"  name="needle_type_length" id="" >
                                </span>
                                <span>
                                    <label for="">Needle  length </label>
                                    <input type="text" style="display:inline; width:30%"  name="needle_length" id="" >
                                </span>
                            </td>
                            <td width="15%">
                                <span>
                                    <label for="">Pre-medication Given </label>
                                    <input type="text" style="display:inline; width:50%"  name="pre_medication_given" id="" >
                                </span>
                            </td>
                            <td width="10%">
                                <span>
                                    <label for="">Time: </label>
                                    <input type="text" style="display:inline; width:60%"  name="time" id="" >
                                </span>
                            </td>
                            <td width="15%">Nerve Simulator
                                    <span>
                                        <label for="">Yes</label>
                                        <input type="radio" name="Simulator"  value="YES" >
                                    </span>
                                    <span>
                                        <label for="">No</label>
                                        <input type="radio" name="Simulator"  value="NO">
                                    </span>
                            </td>
                            <td>
                                <span>
                                    <label for="">Type of sedation: </label>
                                    <input type="text" style="display:inline; width:40%"  name="type_of_sedation" id="" >
                                    
                                </span>
                            </td>                           
                        <tr>
                        <tr>
                            <td><label for="">Local anaesthesia type </label>
                                <span>
                                    % 
                                    <input type="text" style="display:inline; width:15%; text-align:center; " placeholder="%"  name="local_anaesthesia_type" id="" >
                                </span>
                                <span>
                                mls<input type="text" style="display:inline; width:15%; text-align:center;" placeholder="mls"   name="local_anaesthesia_type_mls">
                                </span>
                            </td>
                            <td width="20%">Paresthesia / Pain on Injection
                                <span>
                                    <label for="">Yes</label>
                                    <input type="radio" name="Paresthesia_Pain_on_Injection"  value="YES" >
                                </span>
                                <span>
                                    <label for="">No</label>
                                    <input type="radio" name="Paresthesia_Pain_on_Injection"  value="NO">
                                </span>
                            </td>
                            
                            <td width="25%">Was Sedation Used?
                                <span>
                                    <label for="">Yes</label>
                                    <input type="radio" name="Anaesthetic_plan"  value="Yes" >
                                </span>
                                <span>
                                    <label for="">No</label>
                                    <input type="radio" name="Anaesthetic_plan"  value="No">
                                </span>
                                
                            </td>   
                            <td colspan="2">
                                <span>
                                    <label for="">Simulator Amplitude: </label>
                                    <input type="text" style="display:inline; width:20%"  name="Simulator_amplitude" id="" >
                                    (Duration = 0.1ms; Frequency = 1Hz)
                                </span>
                            </td> 
                                                  
                        </tr>
                        <tr>
                            <td width="20%">Utrasound Used
                                <span>
                                    <label for="">Yes</label>
                                    <input type="radio" name="Utrasound_used"  value="YES" >
                                </span>
                                <span>
                                    <label for="">No</label>
                                    <input type="radio" name="Utrasound_used"  value="NO">
                                </span>
                                Nerve Stimulator Used?
                                <span>
                                    <label for="">Yes</label>
                                    <input type="radio" name="Nerver_stimulator_used"  value="YES" >
                                </span>
                                <span>
                                    <label for="">No</label>
                                    <input type="radio" name="Nerver_stimulator_used"  value="NO">
                                </span>
                            </td>
                            <td>
                                <span>
                                    <label for="">Time block performed</label>
                                    <input type="text" style="display:inline; width:50%"  name="time_block_performed" id="" >
                                </span>
                            </td>
                            <td>
                                <span>
                                    <label for="">Time into theatre</label>
                                    <input type="text" style="display:inline; width:50%"  name="time_into_theatre" id="" >
                                </span>
                            </td>
                            <td>
                                <span>
                                    <label for="">Time to post-op ward</label>
                                    <input type="text" style="display:inline; width:50%"  name="time_to_post_op_ward" id="" >
                                </span>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary btn-xs pull-right btn-block" name="anaesthesia_nerve_block_procedure">Save</button>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
         </table>
            <?php } ?>
    </form>
    <hr>
   
    <form action="ajax_anasthesia_record_information.php" method="POST">
            <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
            <input type="text" hidden  name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID?>" >
            <table width="100%">
                <tr>
                    <?php 
                        if((mysqli_num_rows($select_nerve_block_outcomes))>0){while($outcome_rw = mysqli_fetch_assoc($select_nerve_block_outcomes)){
                    ?>
                    <td width="50%">
                   
                        <table class="table">
                            <tr>
                                <th colspan="2" id="th" > <u><h4><b>Regional anaesthesia Outcome</h4></b></u></th>
                            </tr>
                            <tr>
                                <td>Block set-up at 30 minutes:
                                <?php
                                    $checked_yes="";
                                    $checked_no="";
                                    if($outcome_rw['block_set_up_at_30min']=="YES"){
                                        $checked_yes= "checked = 'checked'";
                                    }else if($outcome_rw['block_set_up_at_30min']=="NO"){
                                        $checked_no= "checked= 'checked'";
                                    }
                                ?>
                                    <span>
                                        <label for="">Yes</label>
                                        <input type="radio" name="block_set_up_at_30min"  value="YES" <?php echo $checked_yes;?>>
                                    </span>
                                    <span>
                                        <label for="">No</label>
                                        <input type="radio" name="block_set_up_at_30min"  value="NO" <?php echo $checked_no;?>>
                                    </span>
                                </td>
                                <td>Sedation needed for operation:
                                    <?php
                                    $checked_yes="";
                                    $checked_no="";
                                    if($outcome_rw['sedation_need_for_operation']=="YES"){
                                        $checked_yes= "checked = 'checked'";
                                    }else if($outcome_rw['sedation_need_for_operation']=="NO"){
                                        $checked_no= "checked= 'checked'";
                                    }
                                ?>
                                    <span>
                                        <label for="">Yes</label>
                                        <input type="radio" name="sedation_need_for_operation"  value="YES" <?php echo $checked_yes; ?>>
                                    </span>
                                    <span>
                                        <label for="">No</label>
                                        <input type="radio" name="sedation_need_for_operation"  value="NO" <?php echo $checked_no; ?>>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Plan for GA for operation:
                                    <?php
                                        $checked_yes="";
                                        $checked_no="";
                                        if($outcome_rw['plan_for_ga_for_op']=="YES"){
                                            $checked_yes= "checked = 'checked'";
                                        }else if($outcome_rw['plan_for_ga_for_op']=="NO"){
                                            $checked_no= "checked= 'checked'";
                                        }
                                    ?>
                                    <span>
                                        <label for="">Yes</label>
                                        <input type="radio" name="plan_for_ga_for_op"  value="YES" <?php echo $checked_yes;?>>
                                    </span>
                                    <span>
                                        <label for="">No</label>
                                        <input type="radio" name="plan_for_ga_for_op"  value="NO" <?php echo $checked_no;?>>
                                    </span>
                                </td>
                                <td>Conversion to GA for operation:
                                    <?php
                                        $checked_yes="";
                                        $checked_no="";
                                        if($outcome_rw['conversation_to_ga_for_op']=="YES"){
                                            $checked_yes= "checked = 'checked'";
                                        }else if($outcome_rw['conversation_to_ga_for_op']=="NO"){
                                            $checked_no= "checked= 'checked'";
                                        }
                                    ?>
                                    <span>
                                        <label for="">Yes</label>
                                        <input type="radio" name="conversation_to_ga_for_op"  value="YES" <?php echo $checked_yes;?>>
                                    </span>
                                    <span>
                                        <label for="">No</label>
                                        <input type="radio" name="conversation_to_ga_for_op"  value="NO" <?php echo $checked_no; ?>>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <span>
                                        <label for="">Complication:</label>
                                        <textarea  style="display:inline; width:80%"  name="Complication" id="" rows="1" readonly><?php echo $outcome_rw['Complication']; ?></textarea>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table class="table">
                            <tr>
                                <th id="th"> <u><h4><b>Post Anaesthetic Visit:</h4></b></u></th>
                            </tr>
                            <tr>
                                <td>
                                    <span>
                                        <label for="">Anaesthetic Complication / Treatment</label>
                                        <textarea  style="display:inline; width:100%"  name="Anaethetic_coplication_treatment" id="" rows="2"readonly><?php echo $outcome_rw['Anaethetic_coplication_treatment']; ?></textarea>
                                    </span>
                                </td>                                
                            </tr>
                            <?php
                                $Employee_ID = $outcome_rw['Employee_ID'];
                                $select_employee = mysqli_query($conn, "SELECT Employee_Name, employee_signature FROM tbl_employee e WHERE e.Employee_ID='$Employee_ID' ") or die(mysqli_error($conn));
                                    while($emp_rw = mysqli_fetch_assoc($select_employee)){
                                        $Employee_Name = $emp_rw['Employee_Name']; 
                                        $employee_signature = $emp_rw['employee_signature'];
                                        if($employee_signature==""||$employee_signature==null){
                                            $signature="________________";
                                        }else{
                                            $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
                                        }
                                    }
                                ?>
                                <td>
                                    <b>Name:</b> <u><?php echo $Employee_Name; ?></u> <b> Signature:</b> <?php echo $signature; ?> <b>  Date:</b>  <?php echo $outcome_rw['created_at']; ?>
                                </td>
                        </table>
                    </td>
                    <?php }}else{ ?>
                        <td width="50%">                   
                            <table class="table">
                                <tr>
                                    <th colspan="2" style="text-align: center; background: #cecece;" id="th"> <u><h4><b>Regional anaesthesia Outcome</h4></b></u></th>
                                </tr>
                                <tr>
                                    <td>Block set-up at 30 minutes:
                                        <span>
                                            <label for="">Yes</label>
                                            <input type="radio" name="block_set_up_at_30min"  value="YES" >
                                        </span>
                                        <span>
                                            <label for="">No</label>
                                            <input type="radio" name="block_set_up_at_30min"  value="NO">
                                        </span>
                                    </td>
                                    <td>Sedation needed for operation:
                                        <span>
                                            <label for="">Yes</label>
                                            <input type="radio" name="sedation_need_for_operation"  value="YES" >
                                        </span>
                                        <span>
                                            <label for="">No</label>
                                            <input type="radio" name="sedation_need_for_operation"  value="NO">
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Plan for GA for operation:
                                        <span>
                                            <label for="">Yes</label>
                                            <input type="radio" name="plan_for_ga_for_op"  value="YES" >
                                        </span>
                                        <span>
                                            <label for="">No</label>
                                            <input type="radio" name="plan_for_ga_for_op"  value="NO">
                                        </span>
                                    </td>
                                    <td>Conversion to GA for operation:
                                        <span>
                                            <label for="">Yes</label>
                                            <input type="radio" name="conversation_to_ga_for_op"  value="YES" >
                                        </span>
                                        <span>
                                            <label for="">No</label>
                                            <input type="radio" name="conversation_to_ga_for_op"  value="NO">
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <span>
                                            <label for="">Complication:</label>
                                            <textarea  style="display:inline; width:80%"  name="Complication" id="" rows="1"></textarea>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table class="table">
                                <tr>
                                    <th style="text-align: center; background: #cecece;" id="th"> <u><h4><b>Post Anaesthetic Visit:</h4></b></u></th>
                                </tr>
                                <tr>
                                    <td>
                                        <span>
                                            <label for="">Anaesthetic Complication / Treatment</label>
                                            <textarea  style="display:inline; width:100%"  name="Anaethetic_coplication_treatment" id="" rows="2"></textarea>
                                        </span>
                                    </td>                                
                                </tr>
                                <tr>
                                    <td>
                                        <button type="submit" class="btn btn-primary pull-right" style="width: 250px;" name="never_block_outcomes">Save</button>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    <?php } ?>
                </tr>
            </table>
    </form>
    <hr>

    <form action="ajax_anasthesia_record_information.php" method="POST">
        <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
        <input type="text" hidden  name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID?>" >
        <table class="table" style='overflow-y: scroll; height: 150px;' >
            <tr>
                <th>
                   PREMEDICATION
                    
                        <button type="button" name="add_item" class="btn btn-primary " onclick="add_item_rw()" >Add  </button>
                   
                </th>
                <th >
                    INDUCTION
                    <button type="button" name="add_item" class="btn btn-primary " onclick="add_induction_rw()" >Add  </button>
                </th>
                <th>MAINTENANCE Drugs 
                    <button type="button" name="add_item" class="btn btn-primary " onclick="mantainance_drugs()" >Add  </button>
                </th>
            </tr>
            <tr >
                <td width="40%">
                    <table class="table" >
                        <tr>
                            <td>#</td>
                            <td>Drugs</td>
                            <td>Dose</td>
                            <td>Time</td>
                            <td>Action</td>
                        </tr>
                        <tbody id="premedicationDrugs">

                        </tbody>

                    </table>
                </td>
                <td width="30%">
                    <table class="table">
                        <tr>
                            <td>#</td>
                            <td>Drugs</td>
                            <td>Dose</td>
                            <td>Time</td>
                            <td>Action</td>
                        </tr>
                        <tbody id="inductionDrugs">

                        </tbody>
                    </table>
                </td>
                <td width="30%">
                    <table class="table">
                        <tr>     
                            <th>#</th>                       
                            <th>Drugs</th>
                            <th>Dose</th>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                        <tbody id="maintananceDrugs">

                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </form>
    <input type="text" hidden  id="Registration_ID" value="<?php echo $Registration_ID?>" >
    
    <div id="drugdialog"></div>
    <hr>
    <div style='overflow:scroll; height:250px;' >
    <!-- <div class="panel panel-body"> -->
    <form action="ajax_anasthesia_record_information.php" method="POST">
            <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
            <input type="text" hidden  name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID?>" >
            <table class="table">
                <tr>
                    <td width="100%" id='th' colspan=''>VENT</td>
                </tr>
                <tr>
                    <td>
                        <table class="table">
                            <tr>
                                <th width="3%">#</th>
                                <th>Mode</th>
                                <th>VT</th>
                                <th>I:E</th>
                                <th>F1O₂</th>
                                <th>RR</th>
                                <th>Pressure control PC</th>
                                <th>Peep</th>
                                <th>Pressure limit</th>
                                <th>Time</th>
                            </tr>
                            <tr><td></td>
                                <td> <input type="text" name="Mode" id='Mode' class="form-control" value=""> </td>
                                <td> <input type="text" name="V_1_I_E"  id="V_1_I_E" class="form-control" value=""> </td>
                                <td> <input type="text" name="Air_02" id="Air_02"  class="form-control" value=""> </td>
                                <td> <input type="text" name="F1o2_RR"  id="F1o2_RR"  class="form-control" value=""> </td>
                                <td> <input type="text" name="RR"  id="RR" class="form-control" value=""> </td>
                                <td> <input type="text" name="Pressure_control_pc" id="Pressure_control_pc"  class="form-control" value=""> </td>
                                <td> <input type="text" name="peep" id="peep"  class="form-control" value=""> </td>
                                <td> <input type="text" name="Pressure_limit" id="Pressure_limit"  class="form-control" value=""> </td>
                               
                                <td><input class="btn btn-info" type="button" name='vent' onclick="save_vent_now()" value="Save"></td>
                            </tr>
                             <?php $num=1; if((mysqli_num_rows($select_vent))>0){
                                  while($vent_rw= mysqli_fetch_assoc($select_vent)){
                                
                                 ?>
                            <tr><td><?= $num ?></td>
                                <td> <input type="text" name="Mode"  required='required' class="form-control" readonly value=" <?php echo $vent_rw['Mode']; ?>"> </td>
                                <td> <input type="text" name="V_T_I_E"  class="form-control" readonly value=" <?php echo $vent_rw['V_1_I_E']; ?>"> </td>
                                <td> <input type="text" name="Air_02"  class="form-control" readonly value=" <?php echo $vent_rw['Air_02']; ?>"> </td>
                                <td> <input type="text" name="F1o2_RR"  class="form-control" readonly value=" <?php echo $vent_rw['F1o2_RR']; ?>"> </td>
                                <td> <input type="text" name="RR"  class="form-control" readonly value="<?php echo $vent_rw['RR']; ?>"> </td>
                                <td> <input type="text" name="Pressure_control_pc" readonly class="form-control" value="<?php echo $vent_rw['Pressure_control_pc']; ?>"> </td>
                                <td> <input type="text" name="peep"  class="form-control" readonly value="<?php echo $vent_rw['peep']; ?>"> </td>
                                <td> <input type="text" name="Pressure_limit"  class="form-control" readonly value="<?php echo $vent_rw['Pressure_limit']; ?>"> </td>
                                <td><?= $vent_rw['created_at']; ?></td>
                            </tr>
                             <?php $num++; }}?>
                            
                        </table>
                    </td>
                   
                </tr>
            </table>
    </form>
    <!-- </div> -->
    </div>
    <hr>
    
    <?php 
       
        echo "<input type='text' value='$anasthesia_record_id' id='anasthesia_record_id' style='display:none;'>";

        echo $anasthesia_record_id.'===990';
    ?>
    <form action="ajax_anasthesia_record_information.php" method="POST" >
    <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
    <input type="text" hidden  name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID?>" >
    <table class="table">
        <?php if(mysqli_num_rows($select_end_of_anaesthesia)>0){ while($end_row = mysqli_fetch_assoc($select_end_of_anaesthesia)){ ?>
            <tr>
                <td><label for="">Duration of anaesthesia:</label>
                    <input type="text" style="display: inline; width:50%;" name="duration_of_anaesthesia"  value="<?php echo $end_row['duration_of_anaesthesia']; ?>" id="duration_of_anaesthesia"> </td>
                <td><label for="">Duration of operation: </label>
                    <input type="text" style="display: inline; width:50%;" name="duration_of_operation"  value="<?php echo $end_row['duration_of_operation']; ?>" id="duration_of_operation"> </td>
                <td><label for="">Blood loss:</label>
                    <input type="text" style="display: inline; width:50%;" name="blood_loss"  value="<?php echo $end_row['blood_loss']; ?>" id="blood_loss"> </td>
                <td><label for="">Total input:</label>
                    <input type="text" style="display: inline; width:50%;" name="total_input"  value="<?php echo $end_row['total_input']; ?>" id="total_input"> </td>
                <td><label for="">Total output</label>
                    <input type="text" style="display: inline; width:50%;" name="total_output"  value="<?php echo $end_row['total_output']; ?>" id="total_output"> </td>
                <td><label for="">Fluid balance</label>
                    <input type="text" style="display: inline; width:50%;" name="fluid_balance"  value="<?php echo $end_row['fluid_balance']; ?>" id="fluid_balance"> </td>
            </tr>
            <tr>
                <td colspan="2"><label for="">Anaesthesia notes:</label>
                    <textarea name="Anaesthesia_notes" style="display: inline; width:80%;" id="Anaesthesia_notes"  rows="1" class="form-control"><?php echo $end_row['Anaesthesia_notes']; ?></textarea>
                </td>
                <td colspan="2"><label for="">Comments:</label>
                    <textarea name="Comments" style="display: inline; width:80%;" id="Comments"  rows="1" class="form-control"><?php echo $end_row['Comments']; ?></textarea>
                </td>
                <td>
                    <input type="text" style="display: inline; width:40%;" name="starting_time"  value="<?php echo $end_row['starting_time']; ?>" placeholder="Starting time" id="starting_time">
                    <input type="text" style="display: inline; width:40%;" name="finishing_time"  value="<?php echo $end_row['finishing_time']; ?>" placeholder="Finishing time" id="finishing_time">
                    <input type="text" style="display: inline; width:40%;" name="starting_time"  value="<?php echo $end_row['opstarting_time']; ?>" placeholder="Starting time" id="starting_time">
                    <input type="text" style="display: inline; width:40%;" name="finishing_time"  value="<?php echo $end_row['opfinishing_time']; ?>" placeholder="Finishing time" id="finishing_time">
                </td>
                <td>
                    <button type="button" class="btn btn-primary pull-right" style="width: 250px;" disabled >SAVED</button>
                </td>
            </tr>
        <?php }}else{?>
            
            <tr>
                <td><label for="">Duration of anaesthesia:</label>
                    <input type="text" style="display: inline; width:50%;" name="duration_of_anaesthesia"  value="" id="duration_of_anaesthesia"> </td>
                <td><label for="">Duration of operation: </label>
                    <input type="text" style="display: inline; width:50%;" name="duration_of_operation"  value="" id="duration_of_operation"> </td>
                <td><label for="">Blood loss:</label>
                    <input type="text" style="display: inline; width:50%;" name="blood_loss"  value="" id="blood_loss"> </td>
                <td><label for="">Total input:</label>
                    <input type="text" style="display: inline; width:50%;" name="total_input"  value="" id="total_input"> </td>
                <td><label for="">Total output</label>
                    <input type="text" style="display: inline; width:50%;" name="total_output"  value="" id="total_output"> </td>
                <td><label for="">Fluid balance</label>
                    <input type="text" style="display: inline; width:50%;" name="fluid_balance"  value="" id="fluid_balance"> </td>
            </tr>
            <tr>
                <td colspan="2"><label for="">Anaesthesia notes:</label>
                    <textarea name="Anaesthesia_notes" style="display: inline; width:80%;" id="Anaesthesia_notes"  rows="1" class="form-control"></textarea>
                </td>
                <td colspan="2"><label for="">Comments:</label>
                    <textarea name="Comments" style="display: inline; width:80%;" id="Comments"  rows="1" class="form-control"></textarea>
                </td>
                <td>
                    <input type="text" style="display: inline; width:40%;" name="starting_time"  value="" class="timeaneOP" placeholder="Anaesthesia Starting time" id="starting_time">
                    <input type="text" style="display: inline; width:40%;" name="finishing_time"  value="" class="timeaneOP" placeholder="Anaesthesia Finishing time" id="finishing_time">
                    <input type="text" style="display: inline; width:40%;" name="starting_time"  value="" class="timeaneOP" placeholder="Operation Starting time" id="opstarting_time">
                    <input type="text" style="display: inline; width:40%;" name="finishing_time"  value="" class="timeaneOP" placeholder="Operation Finishing time" id="opfinishing_time">
                </td>
                <td>
                <button type="button" class="btn btn-primary pull-right" style="width: 250px;" name="end_of_anasthesia" onclick="durationof_op_anaesthesia()">Save</button>
                </td>
            </tr>
        <?php } ?>
    </table>
    </form>
    <table class="table">
        <tr>
            <td align=center>

            <a style="width: 250px;" href="anaesthesia_graph.php?Registration_ID=<?= $Registration_ID ?>&&anasthesia_record_id=<?php echo $anasthesia_record_id;?>" class="art-button-green">VITALS GRAPHs</a>
            </td>
            <td align=center>
                <a style="width: 250px;" href="anaesthesia_recovery_form.php?Registration_ID=<?= $Registration_ID ?>&&anasthesia_record_id=<?php echo $anasthesia_record_id;?>&consultation_ID=<?= $consultation_ID; ?>" class="art-button-green">RECOVERY FORM</a>
            </td>
            <td><a style="width: 250px;" href="Ajax_anaesthesia_to_icu.php?Registration_ID=<?= $Registration_ID ?>&&anasthesia_record_id=<?php echo $anasthesia_record_id;?>&&consultation_ID=<?php echo $consultation_ID; ?>" class="art-button-green" > Referral warrant To ICU</a></td>
        </tr>
    </table>
    <hr>
<div class="row col-md-12">
<p class="text-danger text-center">Patient is informed about available for his/her anesthesia and agreed. He/she is as well informed about the risks associated Anesthesia.</p>

</div>
</fieldset>

    
        
<div id="Anaesthesia_vitals"></div>
<?php
    include("./includes/footer.php");
?>
<div id="disease_div"></div>
<div id="surgeon_list"></div>
<div id="anasthetist_list"></div>
<div id="assist_anesthetist"></div>
<div id="investigation"></div> 
<div id="laboratory_imaging_div_icu"></div>
<div id="procedure_list"></div>
<div id="recoveryform"></div>
<div id="icuform"></div>

<!-- SCript of BMI calculate-->	
<script type='text/javascript'>
    function calculateBMI() {
        var Weight = document.getElementById('weight').value;
        var Height = document.getElementById('height').value;
        if (Weight != '' && Height != '') {
            if (Height != 0) {
                var bmi = (Weight * 10000) / (Height * Height);
                document.getElementById('bmi').value = bmi.toFixed(2);
            }
        }
    }
</script>
    <script>
      function save_vent_now(){
          var Registration_ID = '<?= $Registration_ID ?>';
          var anasthesia_record_id = '<?= $anasthesia_record_id ?>';
            var Mode=$("#Mode").val();
            var Pressure_limit=$("#Pressure_limit").val();
            var dV_1_I_E =$("#V_1_I_E").val();
            var Air_02 =$("#Air_02").val();
            var dRR = $("#RR").val();
            var Pressure_control_pc =$("#Pressure_control_pc").val();
            var peep =$("#peep").val();
            var F1o2_RR = $("#F1o2_RR").val();
            if(Mode=='' || Pressure_limit==''){
                alert("please fill data mode or pressure limit");exit;
            }
            $.ajax({
                type: 'POST',
                url: 'ajax_anasthesia_record_information.php',
                data: {anasthesia_record_id:anasthesia_record_id,Mode:Mode,Pressure_limit:Pressure_limit,V_1_I_E:dV_1_I_E,Air_02:Air_02,RR:dRR,peep:peep,F1o2_RR:F1o2_RR, Pressure_control_pc:Pressure_control_pc, Registration_ID:Registration_ID, vent:''},
                cache: false,
                success:function(responce){                  
                    // alert(responce);
                    location.reload(); 
                }
            });  
        } 
    </script>
<!-- End of script of BMI -->
    <script>
        function pulmonary_disease(){
            var Registration_ID='<?php echo $Registration_ID; ?>';
            var pulmonary_details =$("#pulmonary_details").val();
            var asthma ="";
            if($("#asthma_yes").is(":checked")){
                asthma ="Yes";
            }
            if($("#asthma_no").is(":checked")){
                asthma = "No";
            } 
            var copd ="";
            if($("#copd_yes").is(":checked")){
                copd ="Yes";
            }
            if($("#copd_no").is(":checked")){
                copd = "No";
            } 
            var smoking ="";
            if($("#smoking_yes").is(":checked")){
                smoking ="Yes";
            }
            if($("#smoking_no").is(":checked")){
                smoking = "No";
            } 
            var recent_urti ="";
            if($("#recent_urti_yes").is(":checked")){
                recent_urti ="Yes";
            }
            if($("#recent_urti_no").is(":checked")){
                recent_urti = "No";
            }
            var anasthesia_record_id = $("#anasthesia_record_id").val(); 
            $.ajax({
                type: 'POST',
                url: 'ajax_anasthesia_record_information.php',
                data: {anasthesia_record_id:anasthesia_record_id,recent_urti:recent_urti,smoking:smoking,copd:copd,asthma:asthma, pulmonary_details:pulmonary_details, Registration_ID:Registration_ID, pulmonary:''},
                cache: false,
                success:function(responce){                  
                    alert(responce);
                    location.reload(); 
                }
            });   
        }
        
        function save_endocrine_metabolic(){
            var Registration_ID='<?php echo $Registration_ID; ?>';
            var gestation_week =$("#gestation_week").val();
            var metabolic_details =$("#metabolic_details").val();
            var diabetes_mellitus ="";
            if($("#diabetes_mellitus_yes").is(":checked")){
                diabetes_mellitus ="Yes";
            }
            if($("#diabetes_mellitus_no").is(":checked")){
                diabetes_mellitus = "No";
            } 
            var pregnancy ="";
            if($("#pregnancy_yes").is(":checked")){
                pregnancy ="Yes";
            }
            if($("#pregnancy_no").is(":checked")){
                pregnancy = "No";
            } 
            
            var anasthesia_record_id = $("#anasthesia_record_id").val(); 
            $.ajax({
                type: 'POST',
                url: 'ajax_anasthesia_record_information.php',
                data: {anasthesia_record_id:anasthesia_record_id,metabolic_details:metabolic_details,pregnancy:pregnancy,diabetes_mellitus:diabetes_mellitus,diabetes_mellitus:diabetes_mellitus, gestation_week:gestation_week, Registration_ID:Registration_ID, endocrine_metabolic:''},
                cache: false,
                success:function(responce){                  
                    alert(responce);
                    location.reload(); 
                }
            });   
        }

        function save_gastrointestinal(){
            var Registration_ID='<?php echo $Registration_ID; ?>';
            var gastrointestinal_details =$("#gastrointestinal_details").val();
            var metabolic_details =$("#metabolic_details").val();
            var liver_desease ="";
            if($("#liver_desease_yes").is(":checked")){
                liver_desease ="Yes";
            }
            if($("#liver_desease_no").is(":checked")){
                liver_desease = "No";
            } 
            var alcohol_consumption ="";
            if($("#alcohol_consumption_yes").is(":checked")){
                alcohol_consumption ="Yes";
            }
            if($("#alcohol_consumption_no").is(":checked")){
                alcohol_consumption = "No";
            } 
            
            var anasthesia_record_id = $("#anasthesia_record_id").val(); 
            $.ajax({
                type: 'POST',
                url: 'ajax_anasthesia_record_information.php',
                data: {anasthesia_record_id:anasthesia_record_id,gastrointestinal_details:gastrointestinal_details,liver_desease:liver_desease,alcohol_consumption:alcohol_consumption, Registration_ID:Registration_ID, gastrointestinal:''},
                cache: false,
                success:function(responce){                  
                    alert(responce);
                    location.reload(); 
                }
            });   
        }

        function save_pediatric(){
            var Registration_ID='<?php echo $Registration_ID; ?>';
            var gastrointestinal_details =$("#gastrointestinal_details").val();
            var gestation_week =$("#gestation_weeks").val();
            var pediatric_details = $("#pediatric_details").val();
            var pediaric_derivery_term ="";
            if($("#pediaric_derivery_term_yes").is(":checked")){
                pediaric_derivery_term ="Yes";
            }
            if($("#pediaric_derivery_term_no").is(":checked")){
                pediaric_derivery_term = "No";
            } 
            var resuscitation_done ="";
            if($("#resuscitation_done_yes").is(":checked")){
                resuscitation_done ="Yes";
            }
            if($("#resuscitation_done_no").is(":checked")){
                resuscitation_done = "No";
            } 
            
            var anasthesia_record_id = $("#anasthesia_record_id").val(); 
            $.ajax({
                type: 'POST',
                url: 'ajax_anasthesia_record_information.php',
                data: {anasthesia_record_id:anasthesia_record_id,pediatric_details:pediatric_details, gastrointestinal_details:gastrointestinal_details,pediaric_derivery_term:pediaric_derivery_term,gestation_week:gestation_week, resuscitation_done:resuscitation_done, Registration_ID:Registration_ID, pediatric:''},
                cache: false,
                success:function(responce){                  
                    alert(responce);
                    location.reload(); 
                }
            });
        }
    </script>
<script>
    function  durationof_op_anaesthesia(){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        var starting_time = $("#starting_time").val();
        var finishing_time = $("#finishing_time").val();
        var opstarting_time  = $("#opstarting_time").val()
        var opfinishing_time = $("#opfinishing_time").val();
        var Comments = $("#Comments").val();
        var Anaesthesia_notes = $("#Anaesthesia_notes").val();
        var fluid_balance = $("#fluid_balance").val();
        var total_output = $("#total_output").val();
        var total_input = $("#total_input").val()
        var blood_loss = $("#blood_loss").val();
        var duration_of_operation = $("#duration_of_operation").val();
        var duration_of_anaesthesia = $("#duration_of_anaesthesia").val()
        var anasthesia_record_id = $("#anasthesia_record_id").val(); 
        $.ajax({
            type: 'POST',
            url: 'ajax_anasthesia_record_information.php',
            data: {anasthesia_record_id:anasthesia_record_id,opfinishing_time:opfinishing_time,duration_of_anaesthesia:duration_of_anaesthesia,duration_of_operation:duration_of_operation,blood_loss:blood_loss, total_input:total_input,total_output:total_output, fluid_balance:fluid_balance,Anaesthesia_notes:Anaesthesia_notes,Comments:Comments, starting_time:starting_time,finishing_time:finishing_time, opstarting_time:opstarting_time, Registration_ID:Registration_ID, end_of_anasthesia:''},
            cache: false,
            success:function(responce){                  
                
                location.reload(); 
            }
        });
    } 
    function anaesthesia_icu_form_dialog(){
        $.ajax({
            type:'POST',
            url:'Ajax_anaesthesia_to_icu.php',           
            data:{recovery_form_dialogy:''},
            success:function(responce){
                $("#icuform").dialog({
                    title: 'REFERRAL WARRANT INTO THE ICU',
                    width: 1200, 
                    height: 600, 
                    modal: true
                    });
                $("#icuform").html(responce);
                diaplay_icuform_data();
            }
        })
    }
    
    function anaesthesia_recovery_form_dialog(){
        $.ajax({
            type:'POST',
            url:'add_anaesthetic_item.php',           
            data:{recovery_form_dialogy:''},
            success:function(responce){
                $("#recoveryform").dialog({
                    title: 'ANAESTHESIA RECOVERY FORM',
                    width: 1200, 
                    height: 600, 
                    modal: true
                    });
                $("#recoveryform").html(responce);
                diaplay_recovery_data();
            }
        })
    }

    function recovery_form_save(){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        
        var Via_Epidural = $("#Via_Epidural").val();
        var Crystalide = $("#Crystalide").val();
        var Colloid = $("#Colloid").val();
        var Blood = $("#Blood").val();
        var Losses = $("#Losses").val();
        var Urine_Output = $("#Urine_Output").val();
        var Blood_Loss = $("#Blood_Loss").val();
        var Others = $("#Others").val();
        var OPIOIDs = $("#OPIOID").val();
        var Volatile_Agent = $("#Volatile_Agent").val();
        $.ajax({
            type: 'POST',
            url: 'add_anaesthetic_item.php',
            data: {Via_Epidural:Via_Epidural,Volatile_Agent:Volatile_Agent, Crystalide:Crystalide,Colloid:Colloid,Blood:Blood,Losses:Losses,Urine_Output:Urine_Output, Blood_Loss:Blood_Loss, Others:Others,   OPIOIDs:OPIOIDs, Registration_ID:Registration_ID, insert_recovery_data:''},
            cache: false,
            success: function (html){   
                $("#drugdialog").dialog('close');   
                $("#Via_Epidural").val('') 
                $("#Crystalide").val('');  
                $("#Colloid").val(''); 
                $("#Blood").val('');
                $("#Losses").val('');  
                $("#Blood_Loss").val('');
                $("#Urine_Output").val('');  
                $("#Others").val('');   
                $("#Volatile_Agent").val(''); 
                diaplay_recovery_data();
                
            }
        });
    }

    function diaplay_recovery_data(){
        var Registration_ID='<?php echo $Registration_ID; ?>'; 
        var anasthesia_record_id = $("#anasthesia_record_id").val();       
        $.ajax({
            type: 'POST',
            url: 'add_anaesthetic_item.php',
            data: {anasthesia_record_id:anasthesia_record_id, Registration_ID:Registration_ID, view_recovery_form:''},
            cache: false,
            success:function(responce){                  
                $('#recoveryformdata').html(responce);
            }
        });
    }

    $(document).ready(function(){
        $("#Central_Line_yes_value").hide();
        $("#Central_Line").on("click", function(){
            $("#Central_Line_yes_value").show();
        })
    })
    
    function mantainance_drugs(){
        $.ajax({
            type:'POST',
            url:'add_anaesthetic_item.php',           
            data:{add_maintanance:''},
            success:function(responce){
                $("#drugdialog").dialog({
                    title: 'ADD PHARMACY ITEMS',
                    width: 800, 
                    height: 600, 
                    modal: true
                    });
                $("#drugdialog").html(responce);
                diaplay_maintanance()
            }
        })
    }

    function diaplay_maintanance(){
            var Registration_ID = $("#Registration_ID").val();
            var anasthesia_record_id = '<?= $anasthesia_record_id ?>';
            $.ajax({
                type: 'POST',
                url: 'add_anaesthetic_item.php',
                data: {Registration_ID:Registration_ID,anasthesia_record_id:anasthesia_record_id, select_maintanance:''},
                cache: false,
                success: function (responce){                  
                    $('#maintananceDrugs').html(responce);
                }
            });
        }
        function add_maintanance(Item_ID){
            var Registration_ID = $("#Registration_ID").val();
          
            $.ajax({
                type: 'POST',
                url: 'add_anaesthetic_item.php',
                data: {Item_ID:Item_ID,Registration_ID:Registration_ID, insert_maintanance:''},
                cache: false,
                success: function (html){   
                    $("#drugdialog").dialog('close');               
                    diaplay_maintanance();
                    
                }
            });
        }
       
        function remove_maintanance(Maintanance_ID, Employee_ID){
            $.ajax({
                type: 'POST',
                url: 'add_anaesthetic_item.php',
                data: {Maintanance_ID:Maintanance_ID,Employee_ID:Employee_ID, removemaintanance:''},
                success: function (responce){                  
                    diaplay_maintanance();
                }
            });
        }
        function update_maintanance_time(Maintanance_ID){
            var time = $("#maintainance_time_"+Maintanance_ID).val();
            $.ajax({
                type: 'POST',
                url: 'add_anaesthetic_item.php',
                data: {Maintanance_ID:Maintanance_ID,time:time, updatetimemaintanance:''},
                success: function (responce){                  
                   
                }
            });
        }
        function update_maintanance_dose(Maintanance_ID){
            var dose = $("#maintainance_dose_"+Maintanance_ID).val();
            $.ajax({
                type: 'POST',
                url: 'add_anaesthetic_item.php',
                data: {Maintanance_ID:Maintanance_ID,dose:dose, updatedosemaintanance:''},
                success: function (responce){                  
                   
                }
            });
        }
        function search_maintanance_item(items){
            $.ajax({
                type: 'POST',
                url: 'add_anaesthetic_item.php',
                data: {items:items, search_maintanance_item:''},
                cache: false,
                success: function (html) {
                    console.log(html);
                    $('#Items_Fieldset').html(html);
                }
            });
        }
    
    // Endof maintainance drug
        function add_induction_rw(){
            $.ajax({
                type:'POST',
                url:'add_anaesthetic_item.php',           
                data:{add_induction:''},
                success:function(responce){
                    $("#drugdialog").dialog({
                        title: 'ADD PHARMACY ITEMS',
                        width: 800, 
                        height: 600, 
                        modal: true
                        });
                    $("#drugdialog").html(responce);

                }
            })
        }
        function diaplay_induction(){
            var Registration_ID = $("#Registration_ID").val();
            var anasthesia_record_id= '<?= $anasthesia_record_id ?>';
            $.ajax({
                type: 'POST',
                url: 'add_anaesthetic_item.php',
                data: {Registration_ID:Registration_ID, anasthesia_record_id:anasthesia_record_id, select_induction:''},
                cache: false,
                success: function (responce){                  
                    $('#inductionDrugs').html(responce);
                }
            });
        }
        function add_induction(Item_ID){
            var Registration_ID = $("#Registration_ID").val();
          
            $.ajax({
                type: 'POST',
                url: 'add_anaesthetic_item.php',
                data: {Item_ID:Item_ID,Registration_ID:Registration_ID, insert_induction:''},
                cache: false,
                success: function (html){   
                    $("#drugdialog").dialog('close');               
                    diaplay_induction();
                    
                }
            });
        }
       
        function remove_induction(Induction_ID, Employee_ID){
            $.ajax({
                type: 'POST',
                url: 'add_anaesthetic_item.php',
                data: {Induction_ID:Induction_ID,Employee_ID:Employee_ID, removeinduction:''},
                success: function (responce){                  
                    diaplay_induction();
                }
            });
        }
        function update_induction_time(Induction_ID){
            var time = $("#Induction_time_"+Induction_ID).val();
            $.ajax({
                type: 'POST',
                url: 'add_anaesthetic_item.php',
                data: {Induction_ID:Induction_ID,time:time, updatetimeinduction:''},
                success: function (responce){                  
                    
                }
            });
        }
        function update_induction_dose(Induction_ID){
            var dose = $("#Induction_dose_"+Induction_ID).val();
            $.ajax({
                type: 'POST',
                url: 'add_anaesthetic_item.php',
                data: {Induction_ID:Induction_ID,dose:dose, updatedoseinduction:''},
                success: function (responce){                  
                   
                }
            });
        }
        function search_induction_item(items){
            $.ajax({
                type: 'POST',
                url: 'add_anaesthetic_item.php',
                data: {items:items, search_item:''},
                cache: false,
                success: function (html) {
                    console.log(html);
                    $('#Items_Fieldset').html(html);
                }
            });
        }

        function add_item_rw(){
            $.ajax({
                type:'POST',
                url:'add_anaesthetic_item.php',           
                data:{add_item:''},
                success:function(responce){
                    $("#drugdialog").dialog({
                        title: 'ADD PHARMACY ITEMS',
                        width: 800, 
                        height: 600, 
                        modal: true
                        });
                    $("#drugdialog").html(responce);
                    diaplay_premedication();
                }
            })
        }

        function getItemsListFiltered(items){
           $.ajax({
                type: 'POST',
                url: 'add_anaesthetic_item.php',
                data: {items:items},
                cache: false,
                success: function (html) {
                    console.log(html);
                    $('#Items_Fieldset').html(html);
                }
            });
        }

        function add_premedication(Item_ID){
            var Registration_ID = $("#Registration_ID").val();
          
            $.ajax({
                type: 'POST',
                url: 'add_anaesthetic_item.php',
                data: {Item_ID:Item_ID,Registration_ID:Registration_ID, insert_premedication:''},
                cache: false,
                success: function (html){   
                    $("#drugdialog").dialog('close');               
                    diaplay_premedication();
                    
                }
            });
        }
        function diaplay_premedication(){
            var Registration_ID = $("#Registration_ID").val();
            var anasthesia_record_id ='<?= $anasthesia_record_id ?>';
            $.ajax({
                type: 'POST',
                url: 'add_anaesthetic_item.php',
                data: {Registration_ID:Registration_ID,anasthesia_record_id:anasthesia_record_id, select_premedication:''},
                cache: false,
                success: function (responce){                  
                    $('#premedicationDrugs').html(responce);
                }
            });
        }
        function remove_premedication(Premedication_ID, Employee_ID){
            $.ajax({
                type: 'POST',
                url: 'add_anaesthetic_item.php',
                data: {Premedication_ID:Premedication_ID,Employee_ID:Employee_ID, removepremedication:''},
                success: function (responce){                  
                    diaplay_premedication();
                }
            });
        }
        function update_premedication_time(Premedication_ID){
            var time = $("#time_"+Premedication_ID).val();
            $.ajax({
                type: 'POST',
                url: 'add_anaesthetic_item.php',
                data: {Premedication_ID:Premedication_ID,time:time, updatetimepremedication:''},
                success: function (responce){                  
                    
                }
            });
        }
        function update_premedication_dose(Premedication_ID){
            var dose = $("#dose_"+Premedication_ID).val();
            $.ajax({
                type: 'POST', 
                url: 'add_anaesthetic_item.php',
                data: {Premedication_ID:Premedication_ID,dose:dose, updatedosepremedication:''},
                success: function (responce){                  
                   
                }
            });
        }
    </script>
<script>
    function showdialogother(){
         $("#Add_Pharmacy_Items_other").dialog("open");
    }
    function  add_item(){
            $.ajax({
                type:'POST',
                url:'add_anaesthetic_item.php',
                data:{add_item:''},
                success:function(responce){
                    $("#Add_Pharmacy_Items_other").dialog({autoOpen: false, width: 600, height: 450, title: 'ADD PHARMACY ITEMS', modal: true});

                }
            })
        }
        $('#addrow_one').click(function () {
            var rowCount = parseInt($('#rowCount').val()) + 1;
            var newRow = "<tr class='addnewrow tr" + rowCount + "'><td><input name='volume[]' class='txtbox' type='text' class='duration' id='" + rowCount + " ' style='width:100%'></td><td><input name='type[]' class='txtbox' type='text' class='duration' id='" + rowCount + " ' style='width:100%'></td><td><input name='minutes[]' class='txtbox' type='text' class='duration' id='" + rowCount + " ' style='width:100%'></td><td><input type='button' class='remove' row_id='" + rowCount + "' value='x'></td></tr>";
            $('#row-addition').append(newRow);
            document.getElementById('rowCount').value = rowCount;
        });
    
    function save_pre_anaesthetic_visits(){
        var file_input= $("#upload_consentform").val();
        var fd = new FormData();
        var files = $('#upload_consentform')[0].files[0];
        fd.append('file',files);
        
        $.ajax({
            type:'POST',
            url:'Ajax_upload_picture.php',
            data:fd,
            processData: false,
            contentType: false,
            success:function(responce){
                //alert(responce); exit()
               //if(responce ==0){
                    save_pre_anaesthetic_visit(responce);
              // }else{
               //    alert(responce)
              // }
            }
        });
    }
    function save_pre_anaesthetic_visit(consertform){
        var Registration_ID = '<?= $Registration_ID ?>';
        var consent_signed ="";
        if($("#consent_signed_yes").is(":checked")){
                consent_signed = "Yes";
         }
        if($("#consent_signed_no").is(":checked")){
                consent_signed = "No";
        }
        var significant_history =$("#significant_history").val();
        var past_history = $("#past_history").val();
        var family_history = $("#family_history").val();
        var allergies = $("#allergies").val();
        var social_history = $("#social_history").val();
        var nutritional_status = $("#nutritional_status").val();
        var past_anaesthesia_history = $("#past_anaesthesia_history").val();
        var dental_status = $("#dental_status").val();
        var Cardiac_other_details =$("#Cardiac_other_details").val();
        var Cardiac_angina ="";
            if($("#Cardiac_angina_yes").is(":checked")){
                Cardiac_angina ="Yes";
            }
            if($("#Cardiac_angina_no").is(":checked")){
                Cardiac_angina = "No";
            }
        var Cardiac_valvular_disease ="";
            if($("#Cardiac_valvular_disease_yes").is(":checked")){
                Cardiac_valvular_disease ="Yes";
            }
            if($("#Cardiac_valvular_disease_no").is(":checked")){
                Cardiac_valvular_disease = "No";
            }
        var Cardiac_arrhythias ="";
            if($("#Cardiac_arrhythias_yes").is(":checked")){
                Cardiac_arrhythias ="Yes";
            }
            if($("#Cardiac_arrhythias_no").is(":checked")){
                Cardiac_arrhythias = "No";
            }
        var Cardiac_heart_failure ="";
            if($("#Cardiac_heart_failure_yes").is(":checked")){
                Cardiac_heart_failure ="Yes";
            }
            if($("#Cardiac_heart_failure_no").is(":checked")){
                Cardiac_heart_failure = "No";
            }
         
        var Cardiac_ph_vascular_disease ="";
            if($("#Cardiac_ph_vascular_disease_yes").is(":checked")){
                Cardiac_ph_vascular_disease ="Yes";
            }
            if($("#Cardiac_ph_vascular_disease_no").is(":checked")){
                Cardiac_ph_vascular_disease = "No";
            }
            
        var Cardiac_htn ="";
            if($("#Cardiac_htn_yes").is(":checked")){
                Cardiac_htn ="Yes";
            }
            if($("#Cardiac_htn_no").is(":checked")){
                Cardiac_htn = "No";
            }    
                  
           $.ajax({
               type:'POST',
               url:'ajax_anasthesia_record_information.php',
               data:{consertform:consertform,consent_signed:consent_signed, allergies:allergies, Registration_ID:Registration_ID, significant_history:significant_history, past_history:past_history, nutritional_status:nutritional_status,past_anaesthesia_history:past_anaesthesia_history, family_history:family_history, social_history:social_history,dental_status:dental_status,Cardiac_angina:Cardiac_angina,Cardiac_valvular_disease:Cardiac_valvular_disease, Cardiac_heart_failure:Cardiac_heart_failure, Cardiac_arrhythias:Cardiac_arrhythias,Cardiac_other_details:Cardiac_other_details, Cardiac_htn:Cardiac_htn, Cardiac_ph_vascular_disease:Cardiac_ph_vascular_disease, consent_signed_pre:''},
               success:function(responce){
                if(responce=='ok'){
                    location.reload(); 
                }else{
                    alert("Failed to save");
                }
                //    
               }
           })
    }

    //Start of dialog
    function open_diagnosis_dialog(){

        $.ajax({
            type:'POST',
            url:'ajax_anethesia_diseases.php',
            data:{open_dialog:'' },
            success:function(responce){
                $("#disease_div").dialog({
                    title: 'SELECT DIAGNOSIS',
                    width: '80%',
                    height: 550,
                    modal: true,
                });
                $("#disease_div").html(responce)
                search_disease()
                display_selected_anasthesia_diagnosis()
            }
        });
    }

    function search_disease(){
        var disease_name=$("#disease_name").val();
        var disease_code=$("#disease_code").val();
        $.ajax({
            type:'POST',
            url:'ajax_anethesia_diseases.php',
            data:{disease_name:disease_name,disease_code:disease_code, search_diseases:''},
            success:function(responce){
                $("#list_of_all_disease").html(responce);
            }
        });
    }

    function save_disease_anathesia(disease_ID){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        $.ajax({
            type:'POST',
            url:'ajax_anethesia_diseases.php',
            data:{disease_ID:disease_ID, Registration_ID:Registration_ID, Payment_Cache_ID:Payment_Cache_ID, save_diseases:''},
            success:function(responce){
                display_selected_anasthesia_diagnosis()
            }
        });
    }
    function display_selected_anasthesia_diagnosis(){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        $.ajax({ 
            type:'POST',
            url:'ajax_anethesia_diseases.php',
            data:{Registration_ID:Registration_ID, Payment_Cache_ID:Payment_Cache_ID, display_diagnosis:''  },
            success:function(responce){
                $("#list_of_selected_disease").html(responce)
            }
        });
    }
    function remove_anasthesia_disease(anasthesia_diagnosis_id){
        $.ajax({
            type:'POST',
            url:'ajax_anethesia_diseases.php',
            data:{anasthesia_diagnosis_id:anasthesia_diagnosis_id, remove_diagnosis:''},
            success:function(responce){
                display_selected_anasthesia_diagnosis()
            }
        });
    }


    function view_desease_selected(){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        $.ajax({
            type:'Post',
            url: 'ajax_anethesia_diseases.php',
            data:{Registration_ID:Registration_ID, Payment_Cache_ID:Payment_Cache_ID, view_disease_added:'' },
            success:function(responce){
                $("#Diagnosis").val(responce)
                $("#disease_div").dialog("close")
            }
        });
    }
    //onload data return view selected desease
    $(document).ready(function(){
        view_desease_selected()
        view_surgeons_selected()
        view_anesthetist_selected()
        view_assist_anesthetist_selected()
        view_procedure_selected()
        diaplay_premedication();
        diaplay_induction();
        diaplay_maintanance();
        view_assistant_surgeons_selected();
    });


    function open_surgeon_dialog(){
        $.ajax({
            type: 'POST',
            url: 'ajax_surgeons_anasthesia_list.php',
            data: {opsurgeon_dialog:''},
            success:function(responce){
                $("#surgeon_list").dialog({
                    title: 'SELECT SURGEON',
                    width: '80%',
                    height: 550,
                    modal: true,
                });
                $("#surgeon_list").html(responce)
                search_surgeon()
                display_selected_surgeon()
            }
        });
    }

    function search_surgeon(){
        var Employee_Name = $("#Employee_Name").val();
        $.ajax({
            type:'POST',
            url: 'ajax_surgeons_anasthesia_list.php',
            data:{Employee_Name:Employee_Name, search_surgeon:''},
            success:function(responce){
                $("#list_of_all_surgeon").html(responce)
            }
        });
    }

    function save_anasthesia_surgeon(Employee_ID){
       var Registration_ID='<?php echo $Registration_ID; ?>';
       var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        $.ajax({
            type:'POST',
            url:'ajax_surgeons_anasthesia_list.php',
            data: {Employee_ID:Employee_ID,Registration_ID:Registration_ID, Payment_Cache_ID:Payment_Cache_ID, save_surgeon:'' },
            success:function(responce){
                display_selected_surgeon()
            }
        });

    }
    function view_surgeons_selected(){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        $.ajax({
            type:'POST',
            url:'ajax_surgeons_anasthesia_list.php',
            data:{Registration_ID:Registration_ID, view_surgeon_selected:'' },
            success:function(responce){
                $("#surgeon").val(responce)
                $("#surgeon_list").dialog("close")            }
        });
    }

    function display_selected_surgeon(){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        $.ajax({
            type:'POST',
            url:'ajax_surgeons_anasthesia_list.php',
            data:{Registration_ID:Registration_ID, Payment_Cache_ID:Payment_Cache_ID, display_selected_surgen:'' },
            success:function(responce){
                console.log(responce)
                $("#list_of_selected_surgeon").html(responce)
            }
        });
    }
    function remove_anasthesia_surgeon(anesthesia_surgeon_id){
        $.ajax({
            type:'POST',
            url:'ajax_surgeons_anasthesia_list.php',
            data:{anesthesia_surgeon_id:anesthesia_surgeon_id, remove_surgeon:''},
            success:function(responce){
                display_selected_surgeon()
            }
        });
    }
    function open_assistant_surgeon_dialog(){
        $.ajax({
            type: 'POST',
            url: 'ajax_surgeons_anasthesia_list.php',
            data: {assistant_surgeon_dialog:''},
            success:function(responce){
                $("#surgeon_list").dialog({
                    title: 'SELECT SURGEON',
                    width: '80%',
                    height: 550,
                    modal: true,
                });
                $("#surgeon_list").html(responce)
                search_assistant_surgeon()
                display_selected_assistant_surgeon()
            }
        });
    }

    function search_assistant_surgeon(){
        var Employee_Name = $("#Employee_Name").val();
        $.ajax({
            type:'POST',
            url: 'ajax_surgeons_anasthesia_list.php',
            data:{Employee_Name:Employee_Name, search_assistant_surgeon:''},
            success:function(responce){
                $("#list_of_all_surgeon").html(responce)
            }
        });
    }

    function save_anasthesia_assistant_surgeon(Employee_ID){
        var Registration_ID='<?php echo $Registration_ID; ?>';
       var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        $.ajax({
            type:'POST',
            url:'ajax_surgeons_anasthesia_list.php',
            data: {Employee_ID:Employee_ID,Registration_ID:Registration_ID, Payment_Cache_ID:Payment_Cache_ID, save_assistant_surgeon:'' },
            success:function(responce){
                display_selected_assistant_surgeon()
            }
        });
    }

    function display_selected_assistant_surgeon(){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        var anasthesia_record_id='<?= $anasthesia_record_id ?>';
        $.ajax({
            type:'POST',
            url:'ajax_surgeons_anasthesia_list.php',
            data:{Registration_ID:Registration_ID, anasthesia_record_id:anasthesia_record_id, display_assistant_selected_surgen:'' },
            success:function(responce){
                console.log(responce)
                $("#list_of_selected_surgeon").html(responce)
            }
        });
    }

    function remove_anasthesia_assistant_surgeon(Assistant_ID){
        $.ajax({
            type:'POST',
            url:'ajax_surgeons_anasthesia_list.php',
            data:{Assistant_ID:Assistant_ID, remove_assistant_surgeon:''},
            success:function(responce){
                display_selected_assistant_surgeon()
            }
        });
    }

    function view_assistant_surgeons_selected(){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        $.ajax({
            type:'POST',
            url:'ajax_surgeons_anasthesia_list.php',
            data:{Registration_ID:Registration_ID, view_assistant_surgeon_selected:'' },
            success:function(responce){
                $("#assistant_surgeon").val(responce)
                $("#surgeon_list").dialog("close")            }
        });
    }

    // //To open anasthetist dialog list 
    function open_anesthesia_anesthetist_dialog(){
        $.ajax({
            type: 'POST',
            url: 'ajax_anasthetist_anasthesia.php',
            data: {dialog_anasthetist:''},
            success: function(responce){
                $("#anasthetist_list").dialog({
                    title: 'SELECT ANESTHETIST',
                    width: '80%',
                    height: 550,
                    modal: true,
                });
                $("#anasthetist_list").html(responce)
               search_anesthetist()
                display_selected_anesthetist()

            }
        });
    }

    function search_anesthetist(){
        var Employee_Name=$("#anesthetist_name").val();
        $.ajax({
            type:'POST',
            url:'ajax_anasthetist_anasthesia.php',
            data:{Employee_Name:Employee_Name, search_anesthetist:''},
            success:function(responce){
                $("#list_of_all_anesthetist").html(responce);
            }
        });
    }

    function save_anasthesia_anesthetist(Employee_ID){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        $.ajax({
            type:'POST',
            url:'ajax_anasthetist_anasthesia.php',
            data: {Employee_ID:Employee_ID,Registration_ID:Registration_ID, Payment_Cache_ID:Payment_Cache_ID, save_anesthetist:'' },
            success:function(responce){
                display_selected_anesthetist()
            }
        });
    }

    function view_anesthetist_selected(){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        $.ajax({
            type:'POST',
            url:'ajax_anasthetist_anasthesia.php',
            data:{Registration_ID:Registration_ID, Payment_Cache_ID:Payment_Cache_ID, view_anesthetist:''},
            success:function(responce){
                $("#anesthetist").val(responce)
                $("#anasthetist_list").dialog("close")
            }
        });
    }

    function display_selected_anesthetist(){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        $.ajax({
            type:'POST',
            url:'ajax_anasthetist_anasthesia.php',
            data:{Registration_ID:Registration_ID, display_anesthetist:''} ,
            success:function(responce){
                $("#list_of_selected_anesthetist").html(responce)
            }
        });
    }
    function remove_anasthesia_anesthetist(anesthesia_anesthetist_id){
        $.ajax({
            type:'POST',
            url:'ajax_anasthetist_anasthesia.php',
            data:{anesthesia_anesthetist_id:anesthesia_anesthetist_id, remove_anesthetist:''},
            success:function(responce){
                display_selected_anesthetist()
            }
        });
    }



    function open_anesthesia_assist_anesthetist_dialog(){
        $.ajax({
            type:'POST',
            url:'Ajax_anesthesia_assist_anesthetist.php',
            data:{dialog_assist_anesthetist:''},
            success:function(responce){
                $("#assist_anesthetist").dialog({
                    title: 'SELECT ASSISTANT ANESTHETIST',
                    width: '80%',
                    height: 550,
                    modal: true,
                });
                $("#assist_anesthetist").html(responce)
                search_assist_anesthetist()
            }
        });
    }

    function search_assist_anesthetist(){
        var Employee_Name=$("#assistant_name").val();
        $.ajax({
            type:'POST',
            url:'Ajax_anesthesia_assist_anesthetist.php',
            data:{Employee_Name:Employee_Name, search_assist:''},
            success:function(responce){
                $("#list_of_all_assistant").html(responce);
            }
        });
    }
    
    function save_anasthesia_assist_anesthetist(Employee_ID){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        $.ajax({
            type:'POST',
            url:'Ajax_anesthesia_assist_anesthetist.php',
            data: {Employee_ID:Employee_ID,Registration_ID:Registration_ID, Payment_Cache_ID:Payment_Cache_ID, save_assist_anesthetist:''},
            success:function(responce){
                display_selected_assist_anesthetist()
            }
        });
    }

    function view_assist_anesthetist_selected(){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        $.ajax({
            type:'POST',
            url:'Ajax_anesthesia_assist_anesthetist.php',
            data:{Registration_ID:Registration_ID, Payment_Cache_ID:Payment_Cache_ID, view_assist_anesthetist:''},
            success:function(responce){
                $("#assistant_anesthetist").val(responce)
                $("#assist_anesthetist").dialog("close")}
        });
    }

    function display_selected_assist_anesthetist(){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        $.ajax({
            type:'POST',
            url:'Ajax_anesthesia_assist_anesthetist.php',
            data:{Registration_ID:Registration_ID, Payment_Cache_ID:Payment_Cache_ID, display_selected:''},
            success:function(responce){
                $("#list_of_selected_assistant").html(responce)
            }
        });
    }

    function remove_anasthesia_assist_anesthetist(assist_anasthetist_id){
        $.ajax({
            type:'POST',
            url:'Ajax_anesthesia_assist_anesthetist.php',
            data:{assist_anasthetist_id:assist_anasthetist_id, remove_assist_anesthetist:''},
            success:function(responce){
                display_selected_assist_anesthetist()
            }
        });
    }
     // dialog for proposed procedure
function ajax_procedure_dialog_open(){
$.ajax({
    type:'POST',
    url:'ajax_anasthesia_procedure_dialog.php',
    data:{procedure_dialog:''},
    success:function(responce){                
        $("#procedure_list").dialog({
            title: 'PROPOSSED PROCEDURE',
            width: '85%',
            height: 600,
            modal: true,
        });
        $("#procedure_list").html(responce)
        ajax_search_procedure()
    }
});
}

function ajax_search_procedure(){
var Product_Name = $("#procedure_name").val();
$.ajax({
    type:'POST',
    url:'ajax_anasthesia_procedure_dialog.php',
    data:{Product_Name:Product_Name, search_procedure:''},
    success:function(responce){
        $("#list_of_all_procedure").html(responce);
    }
});
}
function save_anasthesia_procedure(Item_ID){
var Registration_ID='<?= $Registration_ID ?>';
var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
$.ajax({
    type: 'POST',
    url: 'ajax_anasthesia_procedure_dialog.php',
    data:{Registration_ID:Registration_ID, Payment_Cache_ID:Payment_Cache_ID,Item_ID:Item_ID, save_procedure:''},
    success:function(responce){
        display_selected_procedure()
    }
});
}
function  display_selected_procedure(){
var Registration_ID='<?php echo $Registration_ID; ?>';
var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
$.ajax({
    type:'POST',
    url:'ajax_anasthesia_procedure_dialog.php',
    data:{Registration_ID:Registration_ID, Payment_Cache_ID:Payment_Cache_ID, display_procedure:''  },
    success:function(responce){
        $("#list_of_selected_procedure").html(responce)
    }
});
}

function remove_anasthesia_procedure(Procedure_ID){
$.ajax({
    type:'POST',
    url:'ajax_anasthesia_procedure_dialog.php',
    data:{Procedure_ID:Procedure_ID, remove_procedure:''},
    success:function(responce){
        display_selected_procedure()
    }
});
}
function view_procedure_selected(){
var Registration_ID='<?php echo $Registration_ID; ?>';
var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
$.ajax({
    type:'POST',
    url:'ajax_anasthesia_procedure_dialog.php',
    data:{Registration_ID:Registration_ID, Payment_Cache_ID:Payment_Cache_ID,view_procedure:''},
    success:function(responce){
        $("#proposed_procedure").val(responce)
        $("#procedure_list").dialog("close")}
});
}
   
    $(document).ready(function(){ 
              
                $('select').select2();
                $("#yesdetails").hide();
                $("#yesdetail").hide();
                $("#drugyes").on("click", function(){
                    $("#yesdetail").show();
                    $("#yesdetails").show();
                });
                $("#no").on("click", function(){
                    $("#yesdetails").hide();
                    $("#yesdetail").hide();
                });
                $("#unkown").on("click", function(){
                    $("#yesdetails").hide();
                    $("#yesdetail").hide();
                });
               
                $("#histyes").hide();
                $("#histyesd").hide();
                $("#history_yes").on("click", function(){
                    $("#histyes").show();
                    $("#histyesd").show();
                });
                $("#histno").on("click", function(){
                    $("#histyes").hide();
                    $("#histyesd").hide();
                });
            });
           
         

</script>
 <script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>
    $('.ehms_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('.ehms_date').datetimepicker({value: '', step: 01});
    // $('#end_date').datetimepicker({
    //     dayOfWeekStart: 1,
    //     lang: 'en',
    //     //startDate:'now'
    // });
    // $('#end_date').datetimepicker({value: '', step: 01});
    </script>
