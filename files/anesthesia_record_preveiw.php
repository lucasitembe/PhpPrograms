    <?php
        /*+++++++++++++++++++ Designed and implimented 
        by         Eng. Meshack moscow Since 2019-11-13++++++++++++++++++++++++++*/
        session_start();
        include("./includes/connection.php");

        if(isset($_SESSION['userinfo']['Employee_Name'])){
            $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
        }else{
            $Employee_Name = '';
        }
        
        $anasthesia_record_id = $_GET['anasthesia_record_id'];
        if(isset($_GET['Registration_ID'])){
            $Registration_ID=$_GET['Registration_ID'];
         }else{
            $Registration_ID=""; 
         }
         $anasthesia_record_id =$_GET['anasthesia_record_id'];
         $created_at = $_GET['anasthesia_created_at'];
         $pdetails = "SELECT Patient_Name,Gender,pr.Registration_ID,pr.Region,pr.District,pr.Country,TIMESTAMPDIFF(YEAR,DATE(Date_Of_Birth),CURDATE()) AS age,Guarantor_Name FROM tbl_patient_registration pr JOIN tbl_sponsor sp ON sp.Sponsor_ID=pr.Sponsor_ID  WHERE Registration_ID = '$Registration_ID'";
            $pdetails_results = mysqli_query($conn,$pdetails) or die(mysqli_error($conn));
            while ($pdetail = mysqli_fetch_assoc($pdetails_results)) {
                $Patient_Name = $pdetail['Patient_Name'];
                $Registration_ID = $pdetail['Registration_ID']; 
                $Gender = $pdetail['Gender'];
                $age = $pdetail['age'];
                $Region = $pdetail['Region'];
                $District = $pdetail['District'];
                $Country = $pdetail['Country'];
                $Guarantor_Name = $pdetail['Guarantor_Name'];
            }

         $htm ='<table align="center" width="100%">
                    <tr><td style="text-align:center" colspan="6"><img src="./branchBanner/branchBanner.png"></td></tr>
                    <tr>
                    <td style="text-align: right;"><b>Name:</b></td>
                    <td width="30%">' . $Patient_Name . '</td>
                    <td style="text-align: right;" width="20%"><b>Gender:</b></td>
                    <td>' . $Gender . '</td>
                    <td style="text-align: right;"><b>Reg #:</b></td>
                    <td  width="15%">' . $Registration_ID . '</td>
                <tr>
                    <td style="text-align: right;"><b>Sponsor:</b></td>
                    <td>' . $Guarantor_Name . '</td>
                    <td style="text-align: right;"><b>Country:</b></td>
                    <td>' . $Country . '</td>
                    <td style="text-align: right;"><b>Date:</b></td>
                    <td colspan="3">' . $created_at. '</td>
                </tr>
                <tr>
                    <td style="text-align: right;"><b>Age:</b></td>
                    <td>' . $age . ' years</td>
                    <td style="text-align: right;" ><b>Region:</b></td>
                    <td>' . $Region . '</td>
                    <td style="text-align: right;"><b>District:</b></b></td>
                    <td>' . $District . '</td>
                </tr>
                </table>';
         

                
           
        
        $htm .='<h4 align="center">ANESTHESIA RECORD CHART</h4>
                <table style="border: 1px solid; width:100%;">
                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid;"><strong> DIAGNOSIS:</strong></td>';
                $diagnosis = mysqli_query($conn, "SELECT ts.disease_ID ,  disease_name FROM tbl_anasthesia_diagnosis ts , tbl_disease te  WHERE ts.disease_ID = te.disease_ID AND anasthesia_record_id = '$anasthesia_record_id' AND Registration_ID = '$Registration_ID'  ") or die(mysqli_error($conn));
                    if((mysqli_num_rows($diagnosis))>0){
                        $diagnos ="";
                        while($diagnosis_row = mysqli_fetch_assoc($diagnosis)){
                            $diagnos .= $diagnosis_row['disease_name'].",";
                            
                        }$htm .='
                                <td style="border: 1px solid;">'. $diagnos .'</td>                                
                            ';
                    }else{
                        $htm .='
                        <td>-</td>                        
               '; 
                    }
             
               $htm .='
                        <td style="border: 1px solid;"><strong>PROPOSED PROCEDURE </strong></td>                        
               ';
               $procedure = mysqli_query($conn, "SELECT ts.Item_ID ,  Product_Name FROM tbl_anasthesia_procedure ts , tbl_items te  WHERE ts.Item_ID = te.Item_ID AND anasthesia_record_id = '$anasthesia_record_id' AND Registration_ID = '$Registration_ID'  ") or die(mysqli_error($conn));
                    if((mysqli_num_rows($procedure))>0){
                        $Procedures="";
                        while($procedure_row = mysqli_fetch_assoc($procedure)){
                            $Procedures .= $procedure_row['Product_Name'].",";              
    
                        }$htm .='<td> '.$Procedures. '</td> ';
                     
                    }else{
                        $htm .='<td>-</td> '; 

                        }
            $htm .='</tr>
                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid;"><strong> SURGEON: </strong></td>                       
               ';
               $surgeon = mysqli_query($conn, "SELECT ts.Surgeon_ID ,  Employee_Name FROM tbl_anasthesia_surgeon ts , tbl_employee te  WHERE ts.Surgeon_ID = te.Employee_ID AND anasthesia_record_id = '$anasthesia_record_id' AND Registration_ID = '$Registration_ID'  ") or die(mysqli_error($conn));
                    if((mysqli_num_rows($surgeon))>0){
                        $name = "";
                        while($surgeon_row = mysqli_fetch_assoc($surgeon)){
                            $name .= $surgeon_row['Employee_Name'].",";
                            
                        }$htm .='
                                <td style="border: 1px solid;">'. $name .'</td>                                
                            ';
                    }else{
                        $htm .='
                        <td>-</td>                        
               '; 
                    }
                    $htm .='
                        <td style="border: 1px solid;"><strong> ANESTHETIST: </strong></td>';
                $anesthetist = mysqli_query($conn, "SELECT ts.Anasthetist_ID ,  Employee_Name FROM tbl_anasthesia_anesthetist ts , tbl_employee te  WHERE ts.Anasthetist_ID = te.Employee_ID AND anasthesia_record_id = '$anasthesia_record_id' AND Registration_ID = '$Registration_ID'  ") or die(mysqli_error($conn));
                    if((mysqli_num_rows($anesthetist))>0){
                        $names = "";
                        while($anesthetist_row = mysqli_fetch_assoc($anesthetist)){
                            $names .= $anesthetist_row['Employee_Name'].",";
                            
                        }$htm .='
                                <td style="border: 1px solid;">'. $names .'</td>                                
                            ';
                    }else{
                        $htm .='
                        <td>-</td>                        
               '; 
                    }
                    $htm .='   </tr>
                        <tr style="border: 1px solid;">
                            <td style="border: 1px solid;"><strong>ASSISTANT ANESTHETISTS:</strong></td> ';
                            $anesthetist_assist = mysqli_query($conn, "SELECT ts.Assist_anesthetist_ID ,  Employee_Name FROM tbl_anasthesia_assist_anasthetist ts , tbl_employee te  WHERE ts.Assist_anesthetist_ID = te.Employee_ID AND anasthesia_record_id = '$anasthesia_record_id' AND Registration_ID = '$Registration_ID'  ") or die(mysqli_error($conn));
                                if((mysqli_num_rows($anesthetist_assist))>0){
                                        $name_assist = "";
                                    while($anesthetist_assist_row = mysqli_fetch_assoc($anesthetist_assist)){
                                        $name_assist .= $anesthetist_assist_row['Employee_Name'].",";
                                        
                                    }$htm .='
                                            <td style="border: 1px solid;">'. $name_assist .'</td>                                
                                        ';
                                }else{
                                    $htm .='
                                    <td></td>                        
                        '; 
                    }

               $combined = mysqli_query($conn, "SELECT * FROM anasthesia_combined_assessment WHERE  anasthesia_record_id = '$anasthesia_record_id' AND Registration_ID = '$Registration_ID'  ") or die(mysqli_error($conn));
               if((mysqli_num_rows($combined))>0){
                   while($combined_rows = mysqli_fetch_assoc($combined)){
                       $consent_signed = $combined_rows['consent_signed'];
                       $significant_history = $combined_rows['significant_history'];
                       $Cardiac_angina = $combined_rows['Cardiac_angina'];
                       $Cardiac_valvular_disease = $combined_rows['Cardiac_valvular_disease'];
                       $Cardiac_arrhythias = $combined_rows['Cardiac_arrhythias'];
                       $Cardiac_heart_failure = $combined_rows['Cardiac_heart_failure'];
                       $Cardiac_ph_vascular_disease = $combined_rows['Cardiac_ph_vascular_disease'];
                       $Cardiac_htn = $combined_rows['Cardiac_htn'];
                       $Cardiac_other_details = $combined_rows['Cardiac_other_details'];
                        $Past_history = $combined_rows['past_history'];
                        $family_history = $combined_rows['family_history'];
                        $allergies = $combined_rows['allergies'];
                        $nutritional_status  =$combined_rows['nutritional_status'];
                        $social_history = $combined_rows['social_history'];
                        $consertform= $combined_rows['consertform'];
               
          $htm .="     <td style='border: 1px solid;'><strong>CONSENT SIGNED:</strong></td>
               <td>               
                   $consent_signed";
                   $htm .="
                   <a href='attachment/$consertform' target='blank'>View attachment</a>
               </td>           
               
           </tr>";
           $htm.='
       </table>
       <strong>PREOPERATIVE ASSESSMENT:(This is to be discussed with: Mothere/Father/Gurdian/Parent)</strong>
       <table style="border:1px solid; width: 100%">        
       <tr >
           <td>
               <label><strong>Associated History</strong></label>               
           </td>
           <td > '. $significant_history .'</td>
       </tr>
       <tr>
            <td> <b>Past History</b></td>
            <td>'.$Past_history.'</td>
       </tr>
       <tr>
            <td><b>Social History</b></td>
            <td>'.$social_history.'</td>
       </tr>
       <tr>
            <td><b>Nutritional status</b></td>
            <td>'.$nutritional_status.'</td>       
       </tr>
       <tr>
            <td><b>Allergies</b></td>
            <td>'.$allergies.'</td>
       </tr>
       
       </table>
       <table style="border:1px solid;">
       <tr >
           <td><strong>Cardiac disease: Angina</strong>
               '. $Cardiac_angina .'
           </td>
               <td><strong> Valvular Disease: </strong>
                   '. $Cardiac_valvular_disease .'
               </td>
               <td><strong> Arrhythmias </strong>
                   '. $Cardiac_arrhythias .'
               </td>
               <td><strong>Heart failure </strong>
                   '. $Cardiac_heart_failure .'
               </td>
               <td><strong> Peripheral vascular disease: </strong>
                   ' .$Cardiac_ph_vascular_disease . '
               </td>
               <td colspan="3"><strong>HTN </strong>
                   ' .$Cardiac_htn .'
               </td>
           </tr>
           <tr class="border-less">
               <td><strong>Other Details</strong></td>
               <td colspan="7"> 
                   <span >
                       ' . $Cardiac_other_details .'
                   </span>
               </td>
              
           </tr>';
            }}else{
                $htm .='     <td style="border: 1px solid;"><strong>CONSENT SIGNED:</strong></td>
                <td>               
                    
                </td>           
                
            </tr>
        </table>
        <strong>PREOPERATIVE ASSESSMENT:</strong>(This is to be discussed with: Mothere/Father/Gurdian/Parent)
        <table style="border:1px solid; width: 100%">        
        <tr >
            <td>
                <label><strong>Significant Story</strong></label>               
            </td>
            <td > </td>
        </tr>
        </table>
        <table style="border:1px solid;">
        <tr >
            <td><strong>Cardiac disease: Angina</strong>
                
            </td>
                <td><strong> Valvular Disease: </strong>
                    
                </td>
                <td><strong> Arrhythmias </strong>
                    
                </td>
                <td><strong>Heart failure </strong>
                    
                </td>
                <td><strong> Peripheral vascular disease: </strong>
                    
                </td>
                <td colspan="3"><strong>HTN </strong>
                    
                </td>
            </tr>
            <tr class="border-less">
                <td><strong>Other Details</strong></td>
                <td colspan="7"> 
                    <span >
                        
                    </span>
                </td>
               
            </tr>';
            }
       $htm .='</table>';
       $pulmonary = mysqli_query($conn, "SELECT * FROM tbl_pulmonary_disease WHERE  anasthesia_record_id = '$anasthesia_record_id' AND Registration_ID = '$Registration_ID'  ") or die(mysqli_error($conn));
            if((mysqli_num_rows($pulmonary))>0){
                while($pulmonary_row = mysqli_fetch_assoc($pulmonary)){
                    $Asthma = $pulmonary_row['asthma'];
                    $copd = $pulmonary_row['copd']; 
                    $smoking = $pulmonary_row['smoking']; 
                    $recent_urti = $pulmonary_row['recent_urti']; 
                    $pulmonary_details = $pulmonary_row['pulmonary_details'];  
                     

    $htm .='
            <table style="border: 1px solid; width: 100%;">
            <tr>
            <td><strong> Pulmonary disease:</strong></td>
            <td><strong> Asthma:</strong>
                <span>
                    '. $Asthma .'
                </span>
                
            </td>
            <td><strong>COPD:</strong>
                <span>
                    '. $copd .'
                </span>
                
            </td>
            <td><strong> Smoking</strong>
                <span>
                    ' .$smoking . '
                </span>
                
            </td>
            <td><strong>Recent URTI </strong>
                <span>
                    ' . $recent_urti . '
                </span>
                
            </td>
        </tr>
            <tr  >
                <td><strong>Other Details</strong></td>
                <td colspan="2"> 
                    <span >
                        ' .$pulmonary_details.'
                    </span>
                </td>
            </tr>
            </table>';
                }}else{
                    $htm .='<table style="border: 1px solid; width: 100%;">
                    <tr>
                    <td><strong> Pulmonary disease:</strong></td>
                    <td><strong> Asthma:</strong>
                        <span>
                            
                        </span>
                        
                    </td>
                    <td><strong>COPD:</strong>
                        <span>
                           
                        </span>
                        
                    </td>
                    <td><strong> Smoking</strong>
                        <span>
                           
                        </span>
                        
                    </td>
                    <td><strong>Recent URTI </strong>
                        <span>
                            
                        </span>
                        
                    </td>
                </tr>
                    <tr  >
                        <td><strong>Other Details</strong></td>
                        <td colspan="2"> 
                            <span >
                              
                            </span>
                        </td>
                    </tr>
                    </table>';
                }

    

        $metabolic = mysqli_query($conn, "SELECT * FROM tbl_metabolic_disease WHERE  anasthesia_record_id = '$anasthesia_record_id' AND Registration_ID = '$Registration_ID'  ") or die(mysqli_error($conn));
                if((mysqli_num_rows($metabolic))>0){
                    while($metabolic_row = mysqli_fetch_assoc($metabolic)){
                        $diabetes = $metabolic_row['diabetes_mellitus'];
                        $pregnancy = $metabolic_row['pregnancy'];
                        $gestation_week = $metabolic_row['gestation_week'];
                        $metabolic_details = $metabolic_row['metabolic_details'];
                        
                        $htm .='
                        <table style="border: 1px solid; width:100%">
                            <tr>
                            <td><strong>Diabetes Mellitus:</strong>
                                <span>
                                    '. $diabetes .'
                                </span>
                                
                            </td>
                            <td><strong>Pregnancy:</strong>
                                <span>
                                ' .$pregnancy. '
                                </span>
                                
                            </td>
                            <td>
                                <span class="form-inline">
                                    <label for=""><strong>Gestation weeks</strong></label>
                                    ' .$gestation_week. '
                                </span>
                                
                            </td>
                            
                        </tr>
                        <tr class="border-less">style="border:1px solid; width:100%;"
                            <td><strong>Other Details</strong></td>
                            <td colspan="2  "> 
                                <span >
                                    ' .$metabolic_details. '
                                </span>
                            </td>
                        </tr>
                    </table>';
                    }
                }else{
        $htm .='<table style="border: 1px solid; width:100%">
                    <tr>
                        <td><strong>Diabetes Mellitus:</strong>
                            <span>
                                
                            </span>
                            
                        </td>
                        <td><strong>Pregnancy:</strong>
                            <span>
                        
                            </span>
                            
                        </td>
                        <td>
                            <span class="form-inline">
                                <label for=""><strong>Gestation weeks</strong></label>
                                
                            </span>
                            
                        </td>                    
                    </tr>
                    <tr class="border-less">style="border:1px solid; width:100%;"
                        <td><strong>Other Details</strong></td>
                        <td colspan="2  "> 
                            <span >
                                
                            </span>
                        </td>
                    </tr>
                </table>';
                }
                

               $gastrointestinal = mysqli_query($conn, "SELECT * FROM tbl_anasthesial_gastrointestinal_disease WHERE  anasthesia_record_id = '$anasthesia_record_id' AND Registration_ID = '$Registration_ID'  ") or die(mysqli_error($conn));
                    if((mysqli_num_rows($gastrointestinal))>0){
                        while($gastrointestinal_row = mysqli_fetch_assoc($gastrointestinal)){
                            $liver_desease = $gastrointestinal_row['liver_desease'];
                            $alcohol_consumption = $gastrointestinal_row['alcohol_consumption'];
                            $gastrointestinal_details = $gastrointestinal_row['gastrointestinal_details'];
                            $htm .='
                            <table style="border:1px solid; width:100%;">
                                <tr>
                                    <td><strong> Liver Disease:</strong>
                                        <span>
                                            ' .$liver_desease. '
                                        </span>                                
                                    </td>
                                    <td><strong> Alcohol consumption:</strong>
                                        <span>
                                            ' .$alcohol_consumption .'
                                        </span>                                
                                    </td>                            
                                </tr>
                                <tr class="border-less">
                                    <td><strong> Other Details </strong></td>
                                    <td colspan="2  "> 
                                        <span >
                                            '. $gastrointestinal_details .'
                                        </span>
                                    </td>                            
                                </tr>
                            </table>
                            ';
                        }
                    }

               $renal_disease = mysqli_query($conn, "SELECT * FROM tbl_anasthesia_renalto_medication WHERE  anasthesia_record_id = '$anasthesia_record_id' AND Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
               if((mysqli_num_rows($renal_disease))>0){
                   while($renal_disease_row = mysqli_fetch_assoc($renal_disease)){
                       $renal = $renal_disease_row['renal_disease'];
                       $renal_other_details = $renal_disease_row['renal_other_details'];
                       $cns_desease = $renal_disease_row['cns_desease'];
                       $musculoskeletal_diseases = $renal_disease_row['musculoskeletal_diseases'];
                       $cns_musculoskeletal_diseases = $renal_disease_row['cns_musculoskeletal_diseases'];
                       $clotting_disorders = $renal_disease_row['clotting_disorders'];
                       $clotting_disorders_details = $renal_disease_row['clotting_disorders_details'];
                       $significant_family_history = $renal_disease_row['significant_family_history'];
                       $drug_reaction = $renal_disease_row['drug_reaction'];
                       $drug_reaction_yes = $renal_disease_row['drug_reaction_yes'];
                       $surgical_hisory = $renal_disease_row['surgical_hisory'];
                       $surgical_history_ifyes = $renal_disease_row['surgical_history_ifyes'];
                       $current_medication = $renal_disease_row['current_medication'];
                       $htm .='
                       <table style="border: 1px solid; width:100%">
                            <tr>
                            <td><strong>Renal disease:</strong>
                                <span>
                                    ' .$renal. '
                                </span>
                                
                            </td>
                            <td><strong> Details:</strong>
                                <span>
                                    ' .$renal_other_details .'
                                </span>
                                
                            </td>
                            </tr>
                        </table>
                   <table style="border: 1px solid; width: 100%;" >
                   <tr>
                       <td><strong> CNS diseases: </strong>
                       <span>
                           ' . $cns_desease.'
                       </span>
                       </td>
                       <td > <strong> Musculoskelatal diseases:</strong>
                           <span >
                               '. $musculoskeletal_diseases .'
                           </span>
                       </td> 
                    </tr>                       
                   
                   <tr>
                       <td>
                           <span>
                              <strong>Details:</strong>
                           </span>
                           <span>
                               '. $cns_musculoskeletal_diseases.'
                           </span>
                       </td>
                   </tr>
                   </table>
                   <table style="border: 1px solid; width:100%;">
                   <tr >
                       <td><strong> Clotting Disorders:</strong>
                           <span>
                               ' .$clotting_disorders. '
                           </span>
                           
                       </td>
                       <td><strong> Details:</strong>
                           <span>
                               ' .$clotting_disorders_details .'
                           </span>
                           
                       </td>
                       
                   </tr></table>
                    <table style="border:1px solid; width:100%;">
                        <tr>
                            <td>
                            <span>
                                <strong>Significant Family history:</strong>
                            </span> 
                            <span>' .$significant_family_history. '
                            </span>
                            </td>
                        </tr>
                    </table>
                    <table  style="border:1px solid; width:100%;">
                    <tr>
                        <td>
                            <span>
                                <strong>Drug reaction/Allergy:</strong>
                            </span>
                            <span>
                                ' .$drug_reaction. '
                            </span>
                            <span>
                                ' .$drug_reaction_yes. '
                            </span>
                        </td>
                    </tr> 
                    <tr>
                        <td>
                            <span>
                                <strong>Previous Anesthetic/ Surgical history:</strong>
                            </span>
                            <span>
                                ' .$surgical_hisory. '
                            </span>
                            <span>
                                '. $surgical_history_ifyes .'
                            </span>
                        </td>
                    </tr> 
                    </table>
                    <table style="border: 1px solid; width:100%;">
                        <tr>
                            <td>
                                <span><strong>Current medications:</strong></span>
                                <span>
                                    ' .$current_medication. '
                                </span>
                            </td>
                            
                        </tr>
                    </table>';
                   }
               }
               $pediatric = mysqli_query($conn, "SELECT * FROM tbl_anasthesia_pediatric WHERE  anasthesia_record_id = '$anasthesia_record_id' AND Registration_ID = '$Registration_ID'  ") or die(mysqli_error($conn));
               if((mysqli_num_rows($pediatric))>0){
                   while($pediatric_row = mysqli_fetch_assoc($pediatric)){
                       $delivery = $pediatric_row['pediaric_derivery_term'];
                       $week = $pediatric_row['gestation_week'];
                       $resuscitation = $pediatric_row['resuscitation_done'];
                       $more_details = $pediatric_row['pediatric_details'];

                       $htm .='
                            <table style="border: 1px solid; width:100%;">
                                <tr>
                                    <td>
                                        <span><strong>Pediatric: Delivery at term:</strong>
                                        </span>
                                        <span>
                                        ' .$delivery. '
                                        </span>
                                    </td>
                                    <td>
                                        <span> '. $week .'</span>
                                        <span><strong> weeks gestation.</strong></span> 
                                    </td>
                                    <td>
                                        <span><strong>Resuscition done:</strong></span>
                                        <span> ' .$resuscitation . '</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span><strong> More details:</strong></span>
                                        <span> ' .$more_details . '</span>
                                    </td>
                                </tr>
                            </table>
                       ';
                   }
               }
               $exam = mysqli_query($conn, "SELECT * FROM tbl_anasthesia_genaral_examination WHERE  anasthesia_record_id = '$anasthesia_record_id' AND Registration_ID = '$Registration_ID'  ") or die(mysqli_error($conn));
                if((mysqli_num_rows($exam))>0){
                    while($exam_row = mysqli_fetch_assoc($exam)){
                        $patient_state = $exam_row['patient_state'];
                        $bp = $exam_row['bp'];
                        $hr_pr = $exam_row['hr_pr'];
                        $temp = $exam_row['temp'];
                        $wt = $exam_row['wt'];
                        $ht = $exam_row['ht'];
                        $bmi = $exam_row['bmi'];
                        $rbg = $exam_row['rbg'];
                        $mouth_opening = $exam_row['mouth_opening'];
                        $micrognathia = $exam_row['micrognathia'];
                        $neck_extension = $exam_row['neck_extension'];
                        $thyromental_distance = $exam_row['thyromental_distance'];
                        $mallampati = $exam_row['mallampati'];
                        $teeth = $exam_row['teeth'];
                        $cvs = $exam_row['cvs'];
                        $lungs = $exam_row['lungs'];
                        $other_systems = $exam_row['other_systems'];
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
                        $htm .='<br><br><br>
                            <table style="border: 1px solid; width:100%;">
                                <tr>
                                    <td colspan="5">
                                        <span>
                                            <strong>General examination:</strong>
                                        </span>
                                        <span>
                                            <strong>Patients State:</strong>
                                        </span>
                                        <span><u>' .$patient_state. '</u></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5">
                                        <span><strong>BP:</strong></span>
                                        <span>
                                            <u>' .$bp. '</u>
                                        </span>
                                    
                                        <span><strong>HR/PR:</strong></span>
                                        <span>
                                            <u>' .$hr_pr. '</u>
                                        </span>
                                    
                                        <span><strong>Temp:</strong></span>
                                        <span>
                                            <u>' .$temp. '</u>
                                        </span>
                                    
                                        <span><strong>Wt:</strong></span>
                                        <span>
                                            <u>' .$wt. '</u>
                                        </span>
                                        <span><small>Kg</small></span>
                                    
                                        <span><strong>Ht:</strong></span>
                                        <span>
                                            <u>' .$ht. '</u>
                                        </span>
                                        <span><small>cm</small></span>
                                    
                                        <span><strong>BMI:</strong></span>
                                        <span>
                                            <u>' .$bmi. '</u>
                                        </span>
                                    
                                        <span><strong>RBG:</strong></span>
                                        <span>
                                            <u>' .$rbg. '</u>
                                        </span>
                                        <span><small>Mmol/l</small></span>
                                    </td>                                    
                                </tr>
                                <tr>
                                    <td colspan="5">
                                        <strong>HEENT:</strong>
                                    
                                        <span><strong>Mouth Opening:</strong></span>
                                        <span><u>' . $mouth_opening .'</u></span>
                                    
                                        <span>
                                            <strong>Micrognathia</strong>
                                        </span>
                                        <span>
                                            <u>' .$micrognathia. '</u>
                                        </span>
                                    
                                        <span>
                                            <strong>Neck extension:</strong>
                                        </span>
                                        <span>
                                            <u>' . $neck_extension . '</u>
                                        </span>
                                        <span><strong>Thyromental</strong></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <span><strong>Distance:</strong><span>
                                        <span><u>' .$thyromental_distance. '</u></span>
                                    
                                        
                                        <span><strong>MALLAMPATI:</strong><span>
                                        <span><u>' .$mallampati. '</u></span>
                                    
                                        
                                        <span><strong>Teeth:</strong><span>
                                        <span><u>' .$teeth. '</u></span>
                                    </td>
                                    <td>
                                    <label >ASA Physical Status:  </label> 
                                    I<input type="radio" style="display: inline; width:auto;" name="ASA_physical_status" '. $checked_1.' value="1">
                                    II<input type="radio" style="display: inline; width:auto;" name="ASA_physical_status" '. $checked_2.' value="2">
                                    III<input type="radio" style="display: inline; width:auto;" name="ASA_physical_status" '. $checked_3.' value="3">
                                    IV<input type="radio" style="display: inline; width:auto;" name="ASA_physical_status" '. $checked_4.' value="4">
                                    V<input type="radio" style="display: inline; width:auto;" name="ASA_physical_status" '. $checked_5.' value="5">
                                    VI<input type="radio" style="display: inline; width:auto;" name="ASA_physical_status" value="6" '. $checked_6.'>
            
                                    E<input type="checkbox" style="display: inline; width:auto;" name="ASA_physical_status" ' .$checked_E.' value="E">
                                
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <span><strong>CVS:</strong></span>
                                        <span>
                                            <u>' .$cvs . '</u>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span><strong>LUNGS:</strong></span>
                                        <span>
                                            ' .$lungs . '
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span><strong>Other Systems:</strong></span>
                                        <span>
                                            ' .$other_systems . '
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        ';
                    }
                }else{
                    $htm .='<table style="border: 1px solid; width:100%;">
                    <tr>
                        <td >
                           No data for General examination.
                        </td>
                    </tr>
                </table>';
                }
                $investigation = mysqli_query($conn, "SELECT * FROM tbl_anasthesia_investigation WHERE  anasthesia_record_id = '$anasthesia_record_id' AND Registration_ID = '$Registration_ID'  ") or die(mysqli_error($conn));
                if((mysqli_num_rows($investigation))>0){
                    while($investigation_row = mysqli_fetch_assoc($investigation)){
                        $blood_group = $investigation_row['blood_group'];
                        $biochemistry_ca = $investigation_row['biochemistry_ca'];
                        $biochemistry_cr = $investigation_row['biochemistry_cr'];
                        $biochemistry_urea = $investigation_row['biochemistry_urea'];
                        $biochemistry_gl = $investigation_row['biochemistry_gl'];
                        $biochemistry_k = $investigation_row['biochemistry_k'];
                        $biochemistry_na = $investigation_row['biochemistry_na'];
                        $biochemistry_cl = $investigation_row['biochemistry_cl'];
                        $fbp_hb = $investigation_row['fbp_hb'];
                        $fbp_hct = $investigation_row['fbp_h$fbp_hct'];
                        $fbp_platelets = $investigation_row['fbp_platelets'];
                        $fbp_wbc = $investigation_row['fbp_wbc'];
                        $inr_pt = $investigation_row['inr_pt'];
                        $inr_ptt = $investigation_row['inr_ptt'];
                        $inr_bleeding_time = $investigation_row['inr_bleeding_time'];
                        $inr_fibrinogen = $investigation_row['inr_fibrinogen'];
                        $other_hormones = $investigation_row['other_hormones'];
                        $blood_gas_sao2 =$investigation_row['blood_gas_sao2'];
                        $blood_gas_be =$investigation_row['blood_gas_be'];
                        $blood_gas_bic =$investigation_row['blood_gas_bic'];
                        $blood_gas_pco2 =$investigation_row['blood_gas_pco2'];
                        $blood_gas_ph =$investigation_row['blood_gas_ph'];
                        $cxr_findings =$investigation_row['cxr_findings'];
                        $ecg_findings =$investigation_row['ecg_findings'];
                        $echo_findings =$investigation_row['echo_findings'];
                        $ct_scan_findings =$investigation_row['ct_scan_findings'];
                        $blood_gas_FiO2 = $investigation_row['blood_gas_FiO2'];
            $htm .='
                <table style="border: 1px solid; width: 100%;">
                    <tr>
                        <td colspan="7">
                            <span><strong>Investigation:</strong></span>
                            <span><strong>Blood Group:</strong></span>
                            <span>'.$blood_group.'</span>
                        </td>
                       
                    </tr>
                    <tr>
                        <td><span><strong>Biochemistry</strong></span></td>
                        <td>                            
                            <span><strong>Ca</strong></span>
                            <span>'.$biochemistry_ca.'</span>
                        </td>
                        <td>                            
                            <span><strong>Cr.</strong></span>
                            <span>'.$biochemistry_cr.'</span>
                        </td>
                        <td>                            
                            <span><strong>UREA/BUN</strong></span>
                            <span>'.$biochemistry_urea.'</span>
                        </td>
                        <td>                            
                            <span><strong>GL</strong></span>
                            <span>'.$biochemistry_gl.'</span>
                        </td>
                        <td>                            
                            <span><strong>K</strong></span>
                            <span>'.$biochemistry_k.'</span>
                        </td>
                        <td>                            
                            <span><strong>Na</strong></span>
                            <span>'.$biochemistry_na.'</span>
                        </td>
                        <td>                            
                            <span><strong>CL</strong></span>
                            <span>'.$biochemistry_cl.'</span>
                        </td>
                    </tr>
                    <tr>
                        <td><span><strong>Blood count FBP</strong></span></td>
                        <td>                            
                            <span><strong>Hb</strong></span>
                            <span>'.$fbp_hb.'</span>
                        </td>
                        <td>                            
                            <span><strong>HCT.</strong></span>
                            <span>'.$fbp_hct.'</span>
                        </td>
                        <td>                            
                            <span><strong>Platelets</strong></span>
                            <span>'.$fbp_platelets.'</span>
                        </td>
                        <td>                            
                            <span><strong>WBC</strong></span>
                            <span>'.$fbp_wbc.'</span>
                        </td>
                    </tr>
                    <tr>
                        <td><span><strong>Clotting Profile: INR</strong></span></td>
                        <td>                            
                            <span><strong>PT</strong></span>
                            <span>'.$inr_pt.'</span>
                        </td>
                        <td>                            
                            <span><strong>PTT</strong></span>
                            <span>'.$inr_ptt.'</span>
                        </td>
                        <td>                            
                            <span><strong>Fibrinogen</strong></span>
                            <span>'.$inr_fibrinogen.'</span>
                        </td>
                        <td>                            
                            <span><strong>Bleeding time</strong></span>
                            <span>'.$inr_bleeding_time.'</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong> Serum electrolyte <strong></td>
                        <td>                            
                            <span><strong>Ca</strong></span>
                            <span> '.$biochemistry_ca.'</span>
                        </td>
                        <td>                            
                            <span><strong>K</strong></span>
                            <span>'.$biochemistry_k.'</span>
                        </td>
                        <td>                            
                            <span><strong>Na</strong></span>
                            <span>'.$biochemistry_na.'</span>
                        </td>
                        <td colspan="4">                            
                            <span><strong>CL</strong></span>
                            <span>'.$biochemistry_cl.'</span>
                        </td>
                    </tr>
                    <tr>
                        <td><span><strong>Blood Gas: </strong></span></td>
                        <td>                            
                            <span><strong>FiO2</strong></span>
                            <span> '.$blood_gas_FiO2.'</span>
                        </td>
                        <td>                            
                            <span><strong>PH</strong></span>
                            <span> '.$blood_gas_ph.'</span>
                        </td>
                        <td>                            
                            <span><strong>PO2</strong></span>
                            <span> '.$blood_gas_sao2.'</span>
                        </td>
                        <td>                            
                            <span><strong>BE</strong></span>
                            <span> '.$blood_gas_be.'</span>
                        </td>
                        <td>                            
                            <span><strong>HCO3</strong></span>
                            <span> '.$blood_gas_bic.'</span>
                        </td>
                        <td colspan="2">                            
                            <span><strong>PCO2</strong></span>
                            <span>'.$blood_gas_pco2.'</span>
                        </td>
                        
                    </tr>
                    <tr>
                        <td>                            
                            <span><strong>LFT</strong></span>
                            
                        </td>
                        <td colspan="7">
                            <span>'.$lft.'</span>
                        </td>
                    </tr>
                    <tr>
                        <td>                            
                            <span><strong>Other hormones</strong></span>
                            
                        </td>
                        <td colspan="7">
                            <span>'.$other_hormones.'</span>
                        </td>
                    </tr>
                    <tr>
                        <td><span><strong>Blood Gas: FiO2</strong></span></td>
                        <td>                            
                            <span><strong>SaO2</strong></span>
                            <span>'.$blood_gas_sao2.'</span>
                        </td>
                        <td>                            
                            <span><strong>BE</strong></span>
                            <span>'.$blood_gas_be.'</span>
                        </td>
                        <td>                            
                            <span><strong>Bic</strong></span>
                            <span>'.$blood_gas_bic.'</span>
                        </td>
                        <td>                            
                            <span><strong>PCO2</strong></span>
                            <span>'.$blood_gas_pco2.'</span>
                        </td>
                        <td>                            
                            <span><strong>PH</strong></span>
                            <span>'.$blood_gas_ph.'</span>
                        </td>
                    </tr>
                    <tr>
                        <td>                            
                            <span><strong>CXR findings</strong></span>
                            
                        </td>
                        <td colspan="7">
                            <span>'.$cxr_findings.'</span>
                        </td>
                    </tr>
                    <tr>
                        <td>                            
                            <span><strong>ECG Findings</strong></span>
                            
                        </td>
                        <td colspan="7">
                            <span>'.$ecg_findings.'</span>
                        </td>
                    </tr>
                    <tr>
                        <td>                            
                            <span><strong>ECHO findings</strong></span>
                            
                        </td>
                        <td colspan="7">
                            <span>'.$echo_findings.'</span>
                        </td>
                    </tr>
                    <tr>
                        <td>                            
                            <span><strong>CT scan findings</strong></span>
                            
                        </td>
                        <td colspan="7">
                            <span>'.$ct_scan_findings.'</span>
                        </td>
                    </tr>
                </table>
            ';
        }}else{
            $htm .='<table style="border: 1px solid; width:100%; color:red;">
                        <tr>
                            <td >
                            No any record for Investigation.
                            </td>
                        </tr>
                    </table>';
                    }
        $htm .='';
        $for = mysqli_query($conn,  "SELECT fasting_for, medication_at_night, medication_morning, orders_standby_blood, nurse_name_id, Employee_Name, dispensing_time, anticipated_anesthetic_risks , proposed_anasthesia,anesthesiologist_opinion ,anesthesiologist_nameby_id,orders_standby_blood, employee_signature   FROM tbl_anasthesia_premedication nm, tbl_employee ad WHERE nm.nurse_name_id =ad.Employee_ID  AND  anasthesia_record_id = '$anasthesia_record_id' AND Registration_ID = '$Registration_ID'   ") or die(mysqli_error($conn));

         if((mysqli_num_rows($for))>0){
            while($premedication_row = mysqli_fetch_assoc($for)){
                $fasting_for = $premedication_row['fasting_for'];
                $night = $premedication_row['medication_at_night'];
                $morning = $premedication_row['medication_morning'];               
                $blood = $premedication_row['orders_standby_blood'];
                $nurse_name = $premedication_row['Employee_Name'];
                $orders_standby_blood =$premedication_row['orders_standby_blood'];
                $time = $$premedication_row['dispensing_time'];
                $anticipated = $premedication_row['anticipated_anesthetic_risks'];
                $proposed_anasthesia = $premedication_row['proposed_anasthesia'];
                $anesthesiologist_opinion = $premedication_row['anesthesiologist_opinion'];
                $anestheologistname =$premedication_row['anesthesiologist_nameby_id'];
                $employee_signature = $premedication_rows['employee_signature'];
                   if($employee_signature==""||$employee_signature==null){
                    $signature="________________________";
                }else{
                    $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
                }
               
                
            $htm .='
            <table style="border: 1px solid; width:100%;">
            <thead>
                <tr>
                    <th colspan="8">PREMEDICATION / ANESTHESIA ORDERS:</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="4">
                        <span><strong>Fasting For:</strong>                             
                        </span>
                        <span>'. $fasting_for .'</span>
                    </td>
                   
            </tr>
            <tr>
                <td colspan="4">
                    <span>
                        <strong>Medication at night:</strong>                         
                    </span>
                    <span>'. $night .'</span>
                </td>            
            </tr>
            <tr>
                <td colspan="4">
                    <span>
                        <strong>Medication in the morging: <strong>
                        
                    </span>
                    <span>'. $morning .'</span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                        <span>
                            <strong>Other Orders/Standby Blood: </strong>                            
                        </span>
                        <span>'. $blood .'</span>
                    </td>
                    
            </tr>
            
            </table>
            <table style="border:1px solid; width:100%;">
            <tr>
                <td colspan="4">
                    <span>
                        <strong>Anticipated anesthetic risks:</strong>                        
                    </span>
                    <span>'. $anticipated .'</span>
                </td>
            </tr><tr>
                <td colspan="5">
                    <span><strong>Proposed anesthesia:</strong></span>
                    <span>
                        '. $proposed_anasthesia .'
                    </span>
                </td>
            </tr>
            </table>';
            }}else{
                $htm .='<table style="border: 1px solid; width:100%; color:red;">
                <tr>
                    <td >
                    No any record for premedication / Anesthesia orders:.
                    </td>
                </tr>
            </table>';
            }
            $htm .'<table style="border:1px solid; width:100%;">       
        <tr>
            <th colspan="6" width="100%" ><b>INTRAOP RECORD </b></th>
        </tr>';
        $Select_intraop_record=mysqli_query($conn, "SELECT * FROM tbl_intraop_record WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
               
        if(mysqli_num_rows($Select_intraop_record)>0){ 
            while($intraoprows = mysqli_fetch_assoc($Select_intraop_record)){

            
                $IV_sites = $intraoprows['IV_sites'];
                $Cental_line = $intraoprows['Cental_line'];
                $Nasal = $intraoprows['Nasal'];
                $Ett_type = $intraoprows['Ett_type'];
                $Ett_size = $intraoprows['Ett_size'];
                $Mask = $intraoprows['Mask'];
                $LMA = $intraoprows['Mask'];
                $RR = $intraoprows['RR'];
                $TV = $intraoprows['TV'];
                $PEEP =$intraoprows['PEEP'];
                $Press = $intraoprows['Press'];
                $I_E = $intraoprows['I_E'];
                $htm .='<table class="table">
                <tr>
                <th colspan="4" width="100%" ><b>INTRAOP RECORD </b></th>
            </tr>
                <tr>
                    <td colspan="4" width="100%" style="background: #dedede;"><b>General Anesthesia:</b></td>
                </tr>
                <tr>
                    <td width="50%" colspan="2"><b>Has patient fasted: </b> '.$intraoprows['patient_fasted'].' <br/>
                        <b>Type Of Surgery</b> : '.$intraoprows['type_of_surgery'].'
                    </td>
                    <td width="50%" colspan="2">
                    <b> IV Sites: </b> '.$IV_sites.' <br/>
                    <b>Central Line: </b>   '.$Cental_line.'
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><b>Induction: </b> '.$intraoprows['induction'].'
                    </td>
                    <td colspan=""><b>Intubation: </b> '.$intraoprows['intubation'].'                           
                    </td>
                    <td colspan=""><b>Comments: </b> '.$intraoprows['Comments'].'                          
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><b>Circuit #: </b>
                    '.$Nasal.' 
                    <b> ETT Type:</b> '. $Ett_type.'  
                    <b>Size :</b>'.$Ett_size.'  
                    
                    </td>
                    <td colspan=""><b>Airway: </b>
                    <b> Mask :</b> '.$Mask.'
                    <b> LMA : </b>'. $LMA .'>                            
                    </td>
                    <td colspan=""><b>Circuit: </b> '.$intraoprows['Circuit'].'                       
                    </td>
                </tr>
                <tr>
                    <td colspan="4"><b>Ventilation: </b> '.$intraoprows['Ventilation'].'
                        <b> RR : </b> '.$RR.'" 
                       <b> TV :</b> '.$TV.' 
                       <b> Press :</b> : '.$Press.' 
                       <b> PEEP :</b> '.$PEEP.' 
                       <b> I:E  :</b> '.$I_E.'                       
                    </td>
                </tr>
                <tr>
                    <td colspan="4"><b>Anesth-Maintainance: </b> '.$intraoprows['Maintainance'].'
                       
                       <b> Other : </b>' .$Other_anasth.'   
                    </td>
                </tr>
                <tr>
                    <td colspan="4" width="100%" style="background: #dedede;"><b>Local/Regional:</b></td>
                </tr>
                <tr>
                    <td><b>Type: </b> '.$Type.'   </td>
                    <td><b>Agent: </b> '.$Agent.' </td> 
                    Conc: </b> '.$Conc.'  </td>
                    <td>  
                   <b> Amount: </b> '.$Amount.' 
                   <b> Position: </b> '.$Position.'  </td>
                    <td>  
                     
                   <b> Comments: </b> '.$Comments.'   </td>
                </tr>
               
            </table>';
            }
        }else{
            $htm .='<table style="border: 1px solid; width:100%; color:red;">
                <tr>
                    <td >
                    No any data for Intra OP record.
                    </td>
                </tr>
            </table>';
        }
          $htm .="</table>";
            $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
            $row = mysqli_query($conn,  "SELECT   Employee_Name, anasthesia_employee_id,  employee_signature, anasthesia_created_at   FROM tbl_anasthesia_record_chart nm, tbl_employee ad WHERE nm.anasthesia_employee_id =ad.Employee_ID  AND  anasthesia_record_id = '$anasthesia_record_id' AND Registration_ID = '$Registration_ID'   ") or die(mysqli_error($conn));

            if((mysqli_num_rows($row))>0){
               while($premedication_rows = mysqli_fetch_assoc($row)){
                   
                   $Reviewed_by = $premedication_rows['Employee_Name'];
                   $Created_at =$premedication_rows['anasthesia_created_at'];
                   $employee_signature = $premedication_rows['employee_signature'];
                   if($employee_signature==""||$employee_signature==null){
                    $signature="________________________";
                }else{
                    $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
                }
            $htm .='<table style="border:1px solid; width:100%;"> <tr >
                   <td  >
                      <span><strong> REVIEWED BY </strong></span>
                      <span><u>'. $Reviewed_by .'</u></span>
                   </td>
                   <td >
                      <span><strong>SIGNATURE</strong></span>
                       <span>' .$signature . '<span>                               
                   </td>
                   <td >
                       <span><strong>DATE</strong></span>
                       <span>' . $Created_at .'</span>
                   </td>
                  
               </tr></table>';
               }}
               $htm.='<table style="border:1px solid; width:100%;">
                ';
                $select_cannulation= mysqli_query($conn, "SELECT * FROM tbl_cannulation_technic_intubation ti WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));

                if((mysqli_num_rows($select_cannulation))>0){ 
                    while($cannulation_rw= mysqli_fetch_assoc($select_cannulation)){ 
                        
                        $size = $cannulation_rw['size'];
                        $Dept  =$cannulation_rw['Dept'];
                        $htm.='<tr>
                        <th colspan="2">
                        Techinic: '.$cannulation_rw['technic'].'
                </th>
                <th>
                Respiration: '.$cannulation_rw['Respiration'].' 
                
                    </th>
                    </tr>
                    
                    <tr>
                    <th  id="th">INTUBATION</th>
                        <td>'.$cannulation_rw['intubation'].'

                </td>
                <td><label for="">View Grade:  </label>: '.$cannulation_rw['view_grade'].'
               
        </td>
        <td> <b>ETT</b> 
             <span>
                <label for="">Size:  </label>
                '. $size.'          </span>
            <span>
                <label for="">Depth:  </label>
                '. $Dept.'
            </span>
            
        </td>                    
    </tr>';
                    }
                }
                $htm .='</table>';
                $select_nerve_block_procedure = mysqli_query($conn, "SELECT * FROM tbl_anaesthesia_nerve_block_procedure bp WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
            
                if((mysqli_num_rows($select_nerve_block_procedure))>0){
                    while($nerve_block_rw = mysqli_fetch_assoc($select_nerve_block_procedure)){   
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
                    
                    $needle_type_length = $nerve_block_rw['needle_type_length'];
                    $type_of_block =  $nerve_block_rw['type_of_block'];
                    $pre_medication_given= $nerve_block_rw['pre_medication_given'];
                    $time =$nerve_block_rw['time'];
                    $Simulator_amplitude= $nerve_block_rw['Simulator_amplitude'];
                    $local_anaesthesia_type= $nerve_block_rw['local_anaesthesia_type'];
                    $time_block_performed =$nerve_block_rw['time_block_performed']; 
                    $time_into_theatre =$nerve_block_rw['time_into_theatre'];
                    $time_to_post_op_ward = $nerve_block_rw['time_to_post_op_ward'];

               $htm.=' 
               <h4><b><center><u> Regional anaesthesia Procedure Note: </u></center></b></h4><br>
                    <label for="">Performed By: </label>
                    <u>'. $Employee_Name .'</u>
                
                
                    <label for="">Type of block: </label>
                    '. $type_of_block.'
                
            <table  width="100%">
                <tr>
                    <td  style="background: #dedede;"><b>Safety check:</b></td>
                </tr>
                <tr>
                    <td>
                        <table width="100%">
                            
                            <tr >
                            <td>Consent Confirmed: 
                              
                                    
                            <b>
                            '.$nerve_block_rw['consent_confirmed'].'
                               
                            </b>
                                
                            </td>
                            <td>Correct patient & surgical site confirmed:
                            
                                <b>
                                '.$nerve_block_rw['surgical_site'].'
                                   
                                </b>
                            </td>
                            <td>Mornitoring used
                            <b>
                            '.$nerve_block_rw['Mornitoring_used'].'
                               
                            </b>
                                
                            </td>
                            
                            <td>20% Liquid emulsion available : 
                            '.$nerve_block_rw['Liquid_emulsion_available'].'
                               
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
                    <table  width="100%">
                        <tr>
                            <td>
                                <span>
                                    <b>Needle type and length </b>
                                    '.$needle_type_length.'
                                </span>
                            </td>
                            <td >
                                <span>
                                    <b>Pre-medication Given </b>
                                    '.$pre_medication_given.'
                                </span>
                            </td>
                            <td>
                                <span>
                                    <b>Time: </b>
                                    '. $time.'
                                </span>
                            </td>
                            <td >Simulator
                                '.$nerve_block_rw['Simulator'].'
                                    
                            </td>
                            <td  >
                                <span>
                                    <b>Simulator Amplitude: </b>
                                    '.$Simulator_amplitude.'
                                    (Duration = 0.1ms; Frequency = 1Hz)
                                </span>
                            </td>
                            
                        <tr>
                        <tr>
                            <td>
                                <span>
                                    <b>Local anaesthesia type, %: </b>
                                    '.$local_anaesthesia_type.'
                                    
                                </span>
                                <span>
                                mls
                                </span>

                            </td>
                            <td >Paresthesia / Pain on Injection:

                                <b>
                                '.$nerve_block_rw['Paresthesia_Pain_on_Injection'].'
                                   
                                </b>
                            </td>
                            
                            <td colspan="2">
                            Injection pressure: 
                                <b>
                                '.$nerve_block_rw['Injection_pressure'].'
                                </b>

                            </td> 
                                                       
                        </tr>
                        <tr>
                            <td >Utrasound used ? : '.$nerve_block_rw['Utrasound_used'].'
                            
                            </td>
                            <td>
                                <span>
                                    <b>Time block performed</b>
                                   '. $time_block_performed.'
                                </span>
                            </td>
                            <td colspan="2">
                                <span>
                                    <b>Time into theatre</b>
                                    '.$time_into_theatre.'
                                </span>
                            </td>
                            <td colspan="2">
                                <span>
                                    <b>Time to post-op ward</b>
                                    '.$time_to_post_op_ward.'
                                </span>
                            </td>
                           
                        </tr>
                    </table>
                </td>
            </tr>
         </table>
         ';
                }
        }else{
            $htm .='<table style="border: 1px solid; width:100%; color:red;">
                <tr>
                    <td >
                    No any record for Regional anaesthesia Procedure Note.
                    </td>
                </tr>
            </table>';
        }
                $htm.='
                <table style="border:1px solid; width:100%;" >
            <tr>
                <th style="text-align:center;"> PREMEDICATION  </th>
                <th style="text-align:center;">  INDUCTION </th>
                <th style="text-align:center;">MAINTENANCE Drugs </th>
            </tr>
            <tr >
                <td width="40%">
                    <table class="table" >
                        <tr>
                            <td>#</td>
                            <td style="width:60%;">Drugs</td>
                            <td>Dose</td>
                            <td>Time</td>
                        </tr>
                        <tbody id="premedicationDrugs">';
                        $select_premedication = mysqli_query($conn, "SELECT Employee_ID, Premedication_ID, Product_Name, Dose, time FROM  tbl_anaesthesia_premedicaton ap, tbl_items i WHERE i.Item_ID=ap.Item_ID AND  Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id' ") or die(mysqli_error($conn));
                        $num=0;
                        if(mysqli_num_rows($select_premedication)>0){
                            while($row = mysqli_fetch_assoc($select_premedication)){
                                $Employee_ID  = $row['Employee_ID'];
                                $Premedication_ID = $row['Premedication_ID'];
                                $Product_Name = $row['Product_Name'];
                                $Dose = $row['Dose'];
                                $time = $row['time'];
                                $num++;
                                $htm.= "<tr><td>$num</td>";
                                $htm.= "<td>$Product_Name</td><td><input type='text' id='dose_$Premedication_ID' placeholder='Enter Dose' value='$Dose' >
                                        </td><td><input type='text' id='time_$Premedication_ID' value='$time' placeholder='Enter time' onkeyup='update_premedication_time($Premedication_ID)'></td>
                                        </tr>";
            
                            }
                        }
                        $htm.=' </tbody>

                    </table>
                </td>
                <td width="30%">
                    <table class="table">
                        <tr>
                            <td>#</td>
                            <td>Drugs</td>
                            <td>Dose</td>
                            <td>Time</td>
                            
                        </tr>
                        <tbody id="inductionDrugs">';
                        $select_induction = mysqli_query($conn, "SELECT Employee_ID, Induction_ID, Product_Name, Dose, time FROM  tbl_anaesthesia_induction ap, tbl_items i WHERE i.Item_ID=ap.Item_ID AND  Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id' ") or die(mysqli_error($conn));
                        $num=0;
                          if(mysqli_num_rows($select_induction)>0){
                              while($row = mysqli_fetch_assoc($select_induction)){
                                          $Employee_ID  = $row['Employee_ID'];
                                          $Induction_ID = $row['Induction_ID'];
                                          $Product_Name = $row['Product_Name'];
                                          $Dose = $row['Dose'];
                                          $time = $row['time'];
                                          $num++;
                                          $htm.= "<tr><td>$num</td>";
                                          $htm.= "<td>$Product_Name</td>
                                              <td><input type='text' id='Induction_dose_$Induction_ID' placeholder='Enter Dose' value='$Dose' onkeyup='update_induction_dose($Induction_ID)'></td>
                                              <td><input type='text' id='Induction_time_$Induction_ID' value='$time' placeholder='Enter time' onkeyup='update_induction_time($Induction_ID)'></td>
                                              
                                          </tr>";
              
                              }
                          }
                      $htm.='  </tbody>
                    </table>
                </td>
                <td width="30%">
                    <table class="table">
                        <tr>     
                            <th>#</th>                       
                            <th>Drugs</th>
                            <th>Dose</th>
                            <th>Time</th>
                        </tr>
                        <tbody id="maintananceDrugs">';
                        $select_maintanance = mysqli_query($conn, "SELECT Employee_ID, Maintanance_ID, Product_Name, Dose, time FROM  tbl_anaesthesia_maintanance ap, tbl_items i WHERE i.Item_ID=ap.Item_ID AND  Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'  ") or die(mysqli_error($conn));
                        $num=0;
                        if(mysqli_num_rows($select_maintanance)>0){
                            while($row = mysqli_fetch_assoc($select_maintanance)){
                                $Employee_ID  = $row['Employee_ID'];
                                $Maintanance_ID = $row['Maintanance_ID'];
                                $Product_Name = $row['Product_Name'];
                                $Dose = $row['Dose'];
                                $time = $row['time'];
                                $num++;
                                $htm.="<tr><td>$num</td>";
                                $htm.="<td>$Product_Name</td><td><input type='text' id='maintainance_dose_$Maintanance_ID' placeholder='Enter Dose' value='$Dose' onkeyup='update_maintanance_dose($Maintanance_ID)'></td><td><input type='text' id='maintainance_time_$Maintanance_ID' value='$time' placeholder='Enter time' onkeyup='update_maintanance_time($Maintanance_ID)'></td></tr>";
                    
                            }
                        }

                       $htm.=' </tbody>
                    </table>
                </td>
            </tr>
        </table>';
                $htm.=' <table style="border:1px solid; width:100%;">
                <tr>
                    <td width="100%"  >VENT_</td>
                </tr>
                <tr>
                    <td>
                        <table width="100%">
                            <tr>
                                <th>#</th>
                                <th>Mode</th>
                                <th>VT</th>
                                <th>I:E</th>
                                <th>F1O2</th>
                                <th>RR</th>
                                <th>Pressure control PC</th>
                                <th>Peep</th>
                                <th>Pressure limit</th>
                            </tr>';
                $select_vent = mysqli_query($conn, "SELECT * FROM tbl_anaesthesia_vent av WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
                            $num=1;
                             if((mysqli_num_rows($select_vent))>0){ while($vent_rw= mysqli_fetch_assoc($select_vent)){
                                 
                              
                                 $htm.='
                            <tr>
                                <td>'.$num.'</td>
                                <td> '. $vent_rw['Mode'].' </td>
                                <td> '. $vent_rw['V_T_I_E'].' </td>
                                <td> '.$vent_rw['Air_02'].' </td>
                                <td> '. $vent_rw['F1o2_RR'].' </td>
                                <td> '.$vent_rw['RR'].' </td>
                                <td> '. $vent_rw['Pressure_control_pc'].' </td>
                                <td> '. $vent_rw['peep'].' </td>
                                <td> '. $vent_rw['Pressure_limit'].' </td>
                            </tr>';
                            $num++;
                        }}
                        $htm.=' </table>
                        </td>
                       
                    </tr>
                </table>';
                $htm.='
                <h4>ANAESTHESIA VITALS GRAPHS</h4>
                <table width="100%" >
                  <thead>
                    <tr><th>#:</th>
                        <th>SPO2</th>
                        <th>ETCO2</th>
                        <th>ECG</th>
                        <th>Temp</th>
                        <th>Fluids/BT</th>
                        <th>MAC</th>                
                        
                    </tr>
                  </thead>
                  <tbody id="table_vital_maintanance_body">';
                  
                  $select_maintanance_vital = mysqli_query($conn, "SELECT * FROM tbl_anaesthesia_maintanance_vital av WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
                  $sn=1;
                  if((mysqli_num_rows($select_maintanance_vital))>0){
                       while($Vitals_meaintanance_rw= mysqli_fetch_assoc($select_maintanance_vital)){
                         $htm.=" <tr><td>".$sn ."</td>
                          <td>". $Vitals_meaintanance_rw['SPO2'] ."</td>
                          <td>". $Vitals_meaintanance_rw['ETCO2'] ."</td>
                          <td>". $Vitals_meaintanance_rw['ECG'] ."</td>
                          <td> ". $Vitals_meaintanance_rw['Temp'] ."</td>
                          <td> ". $Vitals_meaintanance_rw['Fluids_bt'] ."</td>
                          <td> ". $Vitals_meaintanance_rw['MAC'] ."</td>
                          
                      </tr>";
                      $sn++;
                       }
                  }
                 $htm.='  </bdody>
              </table>
                
        
            <table style="border:1px solid; width:100%;" >
                <tr>
                    <th style="text-align:center;">BP </th>
                    <th style="text-align:center;">HR </th>
                    <th style="text-align:center;">MAP </th>
                </tr>
                <tr >
                <td width="40%">
                    <table  style="width:100%;background: #dedede;">
                        <tr>
                            <th width="5%">#</th>
                            <th width="70%">BP</th>
                            <th width="20%">Time (minutes)</th>
                        </tr>
                        <tbody>';
                        $result=mysqli_query($conn,"SELECT fy,fx, fz FROM tbl_anaesthesia_bp_readings WHERE Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'") or mysqli_error($conn); 
                            $nn=1;
                            if (($num = mysqli_num_rows($result)) > 0) {        
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $fx = $row['fx'];
                                    $fz = $row['fz'];  
                                    $fy= $row['fy'];
                                    $htm.="<tr>
                                        <td>$nn</td>
                                        <td>$fy / $fz</td>
                                        <td>$fx</td>
                                    </tr>";
                                    $nn++;
                                }   
                            }
                        $htm.='  </tbody>
                        </table>
                    </td>
                    <td width="30%">
                        <table  style="width:100%;background: #dcdcde;">
                            <tr>     
                                <th width="5">#</th>                       
                                <th style="width:70%;">HR</th>
                                <th width="25">Time (min)</th>
                            </tr>
                            <tbody id="hr">';
                            $result=mysqli_query($conn,"SELECT sx,sy FROM tbl_anaesthesia_hr_readings WHERE Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'") or mysqli_error($conn); 
                            $nn=1;
                            if (($num = mysqli_num_rows($result)) > 0) {        
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $sx = $row['sx'];
                                    $sy = $row['sy'];   
                                    $htm.="<tr>
                                        <td>$nn</td>
                                        <td>$sx</td>
                                        <td>$sy</td>
                                    </tr>";
                                    $nn++;
                                }   
                            }
                            $htm.=' </tbody>

                            </table>
                        </td>
                        <td width="30%">
                            <table  style=" width:100%; background: #dcdcde;">
                                <tr>
                                    <th width="5">#</th>
                                    <th style="width:70%;">MAP</th>
                                    <th width="25">Time (Min)</th>
                                    
                                </tr>
                                <tbody id="inductionDrugs">';
                                $result=mysqli_query($conn,"SELECT zx,zy FROM tbl_anaesthesia_map_readings WHERE Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'") or mysqli_error($conn); 
                            $nn=1;
                            if (($num = mysqli_num_rows($result)) > 0) {        
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $zx = $row['zx'];
                                    $zy = $row['zy'];   
                                    $htm.="<tr>
                                        <td>$nn</td>
                                        <td>$zy</td>
                                        <td>$zx</td>
                                    </tr>";
                                    $nn++;
                                }   
                            }
                        $htm.='</tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <table style="border:1px solid; width:100%;">
            <h4>END OF ANAESTHESIA</h4>';
            $select_end_of_anaesthesia=mysqli_query($conn, "SELECT * FROM tbl_end_of_anaesthesia WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
            if(mysqli_num_rows($select_end_of_anaesthesia)>0){ while($end_row = mysqli_fetch_assoc($select_end_of_anaesthesia)){ 
              $htm.='  <tr>
                    <td><b for="">Duration of anaesthesia:</b>
                       '. $end_row['duration_of_anaesthesia'].' 
                       <b for="">Duration of operation: </b>
                        '. $end_row['duration_of_operation'].'
                    </td>
                    
                <tr>
                <td> 
                <b for="">Blood loss:</b>
                        '. $end_row['blood_loss'].'
                        <b for="">Total input:</b>
                       '. $end_row['total_input'].' 
                    </td>
                    <td><b for="">Total output</b>
                        '. $end_row['total_output'].'
                        <b for="">Fluid balance</b>
                       '. $end_row['fluid_balance'].'</td>
                </tr>
                <tr>
                <td>
                    <b>Anaethesia starting time : </b> '. $end_row['starting_time'].'
                    <b> Anaethesia finishing time: </b>'. $end_row['finishing_time'].'
                  </td>
                  
                  <td>
                    <b> OP starting time : </b>'. $end_row['opstarting_time'].'
                    <b>OP finishing time : </b>'. $end_row['opfinishing_time'].'
                </td>
                
            </tr>
                <tr>
                    <td colspan="2"><b for="">Anaesthesia notes:</b>
                        '. $end_row['Anaesthesia_notes'].'
                    </td>
                    </tr>
                    <tr>
                    <td colspan="2"><b for="">Comments:</b>
                       '. $end_row['Comments'].'
                    </td>
                    </tr>
                  ';
            }}
            $htm.='</table>';
          
                                
                $select_nerve_block_outcomes= mysqli_query($conn, "SELECT * FROM tbl_never_block_outcome bo WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));

                        if((mysqli_num_rows($select_nerve_block_outcomes))>0){while($outcome_rw = mysqli_fetch_assoc($select_nerve_block_outcomes)){
               
                   $htm.=' <td width="50%">
                   
                        <table width="100%">
                            <tr>
                                <th colspan="2" id="th" > <u><h4><b>Regional anaesthesia Outcome</h4></b></u></th>
                            </tr>
                            <tr>
                                <td>Block set-up at 30 minutes:
                                <b>'.$$outcome_rw['block_set_up_at_30min'].'</b>
                               
                                </td>
                                <td>Sedation needed for operation:
                                    <b>'.$outcome_rw['sedation_need_for_operation'].'</b>
                                   
                                </td>
                            </tr>
                            <tr>
                                <td>Plan for GA for operation:'. $outcome_rw['plan_for_ga_for_op'].'
                                    
                                   
                                </td>
                                <td>Conversation to GA for operation:
                                   <b>'.$outcome_rw['conversation_to_ga_for_op'].'</b>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <span>
                                        <label for="">Complication:</label>
                                        '. $outcome_rw['Complication'] .'
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
                                        '. $outcome_rw['Anaethetic_coplication_treatment'].'
                                    </span>
                                </td>                                
                            </tr>';
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
                                $htm.='
                                </tr>
                                <tr>
                                <td>
                                    <b>Name:</b> <u>'. $Employee_Name.'</u> <b> Signature:</b> '. $signature.' <b>  Date:</b>  '. $outcome_rw['created_at'].'
                                </td>
                        </table>
                    </td></tr>';
                     }}
               $htm.=' </tr>
            </table>';
            $row = mysqli_query($conn,  "SELECT   Employee_Name,  anesthesiologist_opinion ,anesthesiologist_nameby_id, employee_signature, nm.created_at   FROM tbl_anasthesia_premedication nm, tbl_employee ad WHERE nm.anesthesiologist_nameby_id =ad.Employee_ID  AND  anasthesia_record_id = '$anasthesia_record_id' AND Registration_ID = '$Registration_ID'   ") or die(mysqli_error($conn));
            if((mysqli_num_rows($row))>0){
               while($premedication_ro = mysqli_fetch_assoc($row)){
                   
                   $anesthesiologist_opinion = $premedication_ro['anesthesiologist_opinion'];
                   $anestheologistname =$premedication_ro['Employee_Name'];
                   $employee_signature = $premedication_ro['employee_signature'];
                   $created_at = $premedication_ro['created_at'];
                   if($employee_signature==""||$employee_signature==null){
                    $signature="________________________";
                }else{
                    $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
                }

    $htm .=' <table style="border:1px solid; width:100%;"><tr >
                <td >
                   <span><strong>ANESTHESIOLOGIST OPINION/ COMMENTS:</strong></span>
                   <span>'. $anesthesiologist_opinion .'</span>
                </td>
                
            </tr>
            <tr>
                <td colspan="2">
                    <span><strong>ANESTHESIOLOGISTS NAME</strong></span>
                    <span><u>' .$anestheologistname . '</u><span>                               
                </td>
            <td>
                <span><strong>Signature</strong></span>
                <span>' . $signature .'</span>
            </td>
            <td>
                <span><strong>DATE</strong></span>
                <span>' . $created_at .'</span>
            </td>
            </tr></table>
            ';
               }}else{
$htm .='<table style="border:1px solid; width:100%;">
            <tr >
                <td >
                   <span><strong>ANESTHESIOLOGIST OPINION/ COMMENTS:</strong></span>
                   <span></span>
                </td>                
            </tr>
            <tr>
                <td colspan="2">
                    <span><strong>ANESTHESIOLOGISTS NAME</strong></span>
                    <span><u></u><span>                               
                </td>
                <td>
                    <span><strong>Signature</strong></span>
                    <span></span>
                </td>
                <td>
                    <span><strong>DATE</strong></span>
                    <span></span>
                </td>
            </tr>            
        </table>
            ';  
               }
               $htm .="<h4>RECOVERY DATA</h4>";
               $select_arrival_data = mysqli_query($conn, "SELECT * FROM tbl_anaesthesia_recovery_data WHERE  anasthesia_record_id='$anasthesia_record_id' ") or die(mysqli_error($conn));

        while($arrival_rw = mysqli_fetch_assoc($select_arrival_data)){
            $Arrival_date = $arrival_rw['Arrival_date'];
            $Time_in = $arrival_rw['Time_in'];
            $o2by = $arrival_rw['o2by'];
            $Airway = $arrival_rw['Airway'];
            $condition_state = $arrival_rw['condition_state'];
            $ventilated = explode(',', $arrival_rw['ventilated']);
            $ventilateddata = explode(',', $arrival_rw['ventilateddata']);
            $Ettsize = explode(',', $arrival_rw['Ettsize']);
            $ETTs  = $arrival_rw['ETTs'];

            if($ETTs=='Nasal'){
                $Nasal="checked='checked'";
            }else if($ETTs=='Oral'){
                $Oral="checked='checked'";
            }
            if($o2by=='Facemask'){
                $Facemask="checked='checked'";
            }else if($o2by=='Nasalcannula'){
                $Nasalcannula="checked='checked'";
            }
            if($Airway=='Extubated'){
                $Extubated="checked='checked'";
            }else if($Airway=='Intubeted'){
                $Intubeted="checked='checked'";
            }

            if($condition_state=='Awake'){
                $Awake="checked='checked'";
            }else if($condition_state=='Rousable'){
                $Rousable="checked='checked'";
            }else if($condition_state=='Unconscious'){
                $Unconscious="checked='checked'";
            }else if($condition_state=='InPain'){
                $InPain="checked='checked'";
            }else if($condition_state=='Restless'){
                $Restless="checked='checked'";
            }else if($condition_state=='Calm'){
                $Calm="checked='checked'";
            }
            if($ventilated=='Manual'){
                $Manual="checked='checked'";
            }else if($ventilated=='Mechanical'){
                $Mechanical="checked='checked'";
            }
        }

        $select_discharge_data = mysqli_query($conn, "SELECT * FROM tbl_anaesthesia_recovery_discharge WHERE  anasthesia_record_id='$anasthesia_record_id' ") or die(mysqli_error($conn));
        while($dischage_rw = mysqli_fetch_assoc($select_discharge_data)){
            $others_meds = $dischage_rw['others_meds'];
            $Analgesia = $dischage_rw['Analgesia_state'];
            $vitalsondischarge = explode(',', $dischage_rw['vitalsondischarge']);
            $dataondischarge = explode(',', $dischage_rw['dataondischarge']);
        }

        $htm.='<table style="border:1px solid; width:100%;">
        <tbody>
            <tbody>

                <tr>
                    <td>                            
                        <span style="display:inline;"><b>Arrival Date:</b>
                            '. $Arrival_date.'
                        </span>
                    </td>
                    <td>                            
                        <span style="display:inline;"><b>Time IN:</b>
                            '. $Time_in.'
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><b>Condition: </b>
                        <span style="display:inline;">
                           '.$condition_state.'
                        </span>
                    </td>
                    <td><b>Airway: </b>
                        <span style="display:inline;">
                            '.$Airway.'
                            <b>   ETT:</b>
                           '.$ETTs.'
                           <b>   Size </b>'.$Ettsize[0].'
                           <b>   Tracheostomy :</b>'.$Tracheostomy.'
                           <b>   Size </b>'. $Ettsize[1].'
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><b>Ventilated:</b>
                        <span style="display:inline;">
                           '.$Ventilated.'
                           <b>  TV </b>'. $ventilateddata[0].'
                           <b>  RR </b>'. $ventilateddata[1].'
                           <b>  Paw </b>'. $ventilateddata[2].'
                           <b>  PEEP </b>'. $ventilateddata[3].'
                        </span>
                    </td>
                    <td>
                       <b> <b>Urinary Catheter:</b>
                        <span style="display:inline;">
                        <b>   Drain </b>'.$ventilateddata[4].'
                        <b>  Site </b>'.$ventilateddata[5].'
                        </span>
                    </td>
                </tr>
                <tr>
                    
                </tr>
                <tr>
                    <td><b>VITALS ON ARRIVAL:</b>
                        <span style="display:inline;">
                        <b>    BP </b>'. $ventilateddata[6].'
                        <b>   HR </b>'. $ventilateddata[7].'
                        <b>   RR </b>'. $ventilateddata[8].'
                        <b>    T </b>'. $ventilateddata[9].'
                        <b>   SPO2 </b>'. $ventilateddata[10].'

                        </span>
                    </td>
                    <td> <b>Position: </b>
                        <span style="display:inline;">
                            
                        <b>   IV lines: Size</b>'. $Ettsize[2].'
                        <b>  Location : </b>'.$Ettsize[3].'
                        <b>   Size :</b>'. $Ettsize[4].'
                        <b>    Location : </b>'.$Ettsize[5].'
                        </span>
                    </td>
                </tr>

            </tbody>
        </tbody>
    </table>
    <table width="100%" >
            <thead>
                <tr>
                    <th>#</th>
                    <th>SPO2</th>
                    <th>ETCO2</th>
                    <th>FIO2</th>
                    <th>Temp</th>
                    <th>DATE</th>
                </tr>
            </thead>
            <tbody >';
            $select_maintanance_vitals = mysqli_query($conn, "SELECT * FROM tbl_recovery_maintanance_vital av WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
            $nnnn=1;
            if((mysqli_num_rows($select_maintanance_vitals))>0){
                 while($dt_rws= mysqli_fetch_assoc($select_maintanance_vitals)){
                     
                     $htm."
                    <tr>
                    <td>".$nnnn."</td>
                    <td> ". $dt_rws['SPO2']." </td>
                    <td> ". $dt_rws['ETCO2']."</td>
                    <td> ". $dt_rws['FIO2']." </td>
                    <td> ". $dt_rws['Temp']." </td>
                    <td>".$dt_rws['created_at']."</td>
                </tr>";
                $nnnn++;
                 }
            }
           $htm.' </bdody>
        </table>';
   $htm.'
<table style="border:1px solid; width:100%;" >
    <tr>
        <th style="text-align:center;">BP </th>
        <th style="text-align:center;">HR </th>
        <th style="text-align:center;">MAP </th>
    </tr>
    <tr>
        <td width="40%">
            <table  style="width:100%;background: #dedede;">
                <tr>
                    <th width="5%">#</th>
                    <th width="70%">BP</th>
                    <th width="20%">Time (minutes)</th>
                </tr>
                <tbody>';
            $result=mysqli_query($conn,"SELECT fy,fx, fz FROM tbl_recovery_bp_readings WHERE Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'") or mysqli_error($conn); 
                $nn=1;
                if (($num = mysqli_num_rows($result)) > 0) {        
                    while ($row = mysqli_fetch_assoc($result)) {
                        $fx = $row['fx'];
                        $fz = $row['fz'];  
                        $fy= $row['fy'];
                        $htm.="<tr>
                            <td>$nn</td>
                            <td>$fy/$fz</td>
                            <td>$fx</td>
                        </tr>";
                        $nn++;
                    }   
                }
            $htm.='  </tbody>
            </table>
        </td>
        <td width="30%">
            <table  style="width:100%;background: #dedede;">
                <tr>     
                <th width="5">#</th>                       
                <th style="width:70%;">HR</th>
                <th width="25">Time (min)</th>
            </tr>
            <tbody >';
                $result=mysqli_query($conn,"SELECT sx,sy FROM tbl_recovery_hr_readings WHERE Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'") or mysqli_error($conn); 
                $nn=1;
                if (($num = mysqli_num_rows($result)) > 0) {        
                    while ($row = mysqli_fetch_assoc($result)) {
                        $sx = $row['sx'];
                        $sy = $row['sy'];   
                        $htm.="<tr>
                            <td>$nn</td>
                            <td>$sx</td>
                            <td>$sy</td>
                        </tr>";
                        $nn++;
                    }   
                }
                $htm.=' </tbody>

            </table>
        </td>
        <td width="30%">
            <table  style=" width:100%; background: #dedede;">
                <tr>
                    <th width="5">#</th>
                    <th style="width:70%;">MAP</th>
                    <th width="25">Time (Min)</th>
                    
                </tr>
                <tbody id="inductionDrugs">';
                    $result=mysqli_query($conn,"SELECT zx,zy FROM tbl_recovery_map_readings WHERE Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'") or mysqli_error($conn); 
                $nn=1;
                if (($num = mysqli_num_rows($result)) > 0) {        
                    while ($row = mysqli_fetch_assoc($result)) {
                        $zx = $row['zx'];
                        $zy = $row['zy'];   
                        $htm.="<tr>
                            <td>$nn</td>
                            <td>$zy</td>
                            <td>$zx</td>
                        </tr>";
                        $nn++;
                    }   
                }
            $htm.='</tbody>
            </table>
        </td>
    </tr>
</table>';
$htm.='
        <table class="table" border="0">
            <tr>
                <td width="60%"><b>ANALGESIA :</b>
                    <span style="display:inline;">
                        '. $Analgesia.'
                    </span>
                </td>
                <td><b>OTHER MEDS</b>
                    <span style="display:inline;">
                        '. $others_meds.'
                    </span>
                </td>
            </tr>
            <tr>
                <td><b><b>IV FLUIDS :</b>
                    <span style="display:inline;">
'. $dataondischarge[0].'
                    </span>
                </td>
                <td><b>BLOOD PRODUCTs:</b>
                    <span style="display:inline;">
'. $dataondischarge[1].'
                    </span>
                </td>
            </tr>
            <tr>
                <td><b>Discharge Time: </b>
                    <span style="display:inline;">
                    '. $dataondischarge[2].'
                    </span>
                </td>
                <td><b>Condition :</b>
                    <span style="display:inline;">
    '. $dataondischarge[3].'
                    </span>
                </td>
            </tr>
            <tr>
                <td><b>Discharge To: :</b>
                    <span style="display:inline;">
                        '. $dataondischarge[4].'
                    </span>
                </td>
                <td> <b>Discharged By:</b>
                    <span style="display:inline;">
                        '. $Employee_name.'
                    </span>
                </td>
            </tr>
            <tr>
                <td><b>Vitals On Discharge:</b>
                    <span style="display:inline;" >
                    <b> BP :</b> '. $vitalsondischarge[0].'
                    <b> HR :</b>'.$vitalsondischarge[1].'
                    <b>  RR :</b>'. $vitalsondischarge[2].'
                    <b>   T :</b>'. $vitalsondischarge[3].'
                    <b>   SPO2:</b> '. $vitalsondischarge[4].'
                    </span>
                </td>
                
            </tr>
        </table>
        <table width="100%">
        <thead>
                <tr><th colspan="5">Alderate Score</th></tr>
              <tr>
                <td>Date and time</td>                            
                <td>Activity Able to move,<br/>voluntarily or on command</td>
                <td>Breathing</td>
                <td>Consciousness</td>
                <td>Circulation (BP)</td>
                <td>SPO₂</td>
                <td>Aldrete Score</td>
              </tr>
        </thead>
        <tbody id="adlarate_score_body">';
        $selectscore = mysqli_query($conn, "SELECT * FROM tbl_recovery_alderate_score WHERE anasthesia_record_id='$anasthesia_record_id' AND Registration_ID='$Registration_ID' order by created_at DESC ") or die(mysqli_error($conn));
        $score_test_num = mysqli_num_rows($selectscore);
        if(mysqli_num_rows($selectscore)>0){
            while($rw = mysqli_fetch_assoc($selectscore)){
                $Activity = $rw['Activity'];
                $Breathing = $rw['Breathing'];
                $Consciousness = $rw['Consciousness'];
                $Circulation = $rw['Circulation'];
                $SPOair = $rw['SPOair'];
                $Totolscore = $Activity + $Breathing +$Consciousness +$Circulation+$SPOair;
                if($Totolscore >= 9){
                    $aldratescore = "<h4 style='color:green;'><b>Normal Discharge score is $Totolscore </b></h4>";
                }
                if($Totolscore < 9){
                    $aldratescore = "<h5 style='color:red; font-size:15px;'><b>Transfer to ICU alderate score is $Totolscore </b></h5>";
                }
                $created_at = $rw['created_at'];
                if($Activity =='2'){
                    $Activity = "4 extrimities";
                }else if($Activity =='1'){
                    $Activity = "2 extrimities";
                }else if($Activity=='0'){
                    $Activity = "No any activity";
                }
                if($Breathing =='2'){
                    $Breathing = "Able to breath deeply and cough freely";
                }else if($Breathing =='1'){
                    $Breathing = "Dyspnea, Shallow or limited breathing";
                }else if($Breathing=='0'){
                    $Breathing = "Apnea";
                }
                if($Consciousness =='2'){
                    $Consciousness = "Fully awake";
                }else if($Consciousness =='1'){
                    $Consciousness = "Arousable on calling";
                }else if($Consciousness=='0'){
                    $Consciousness = "Unresponsive";
                }
                if($Circulation =='2'){
                    $Circulation = "+-20% of pre-anaesthesia leval";
                }else if($Circulation =='1'){
                    $Circulation = "+-20%-  49% of pre-anaesthesia leval";
                }else if($Circulation=='0'){
                    $Circulation = "+-50% of pre-anaesthesia leval";
                }
                if($SPOair =='2'){
                    $SPOair = "Maintains Spo₂ > 92% in ambiant air";
                }else if($SPOair =='1'){
                    $SPOair = "Maintains Spo₂ > 90% with O₂";
                }else if($SPOair=='0'){
                    $SPOair = "Maintains Spo₂ < 90% with O₂";
                }
                $htm.= "<tr>
                        <td>$created_at</td>
                        <td>$Activity</td>
                        <td>$Breathing</td>
                        <td>$Consciousness</td>
                        <td>$Circulation</td>
                        <td>$SPOair</td>
                        <td>$aldratescore </td>
                    </tr>";
            }
        }else{
            $htm.= "<tr><td colspan='5'>No result found</td></tr>";
        } 
        $htm.='</tbody>
        </table>
        ';
        include("./MPDF/mpdf.php");
        $mpdf=new mPDF('c','A4','','', 15,15,20,23,15,20, 'P'); 
        $mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
        $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list designed by Eng. Meshack
        // LOAD a stylesheet
        $stylesheet = file_get_contents('mpdfstyletables.css');
        $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

        $mpdf->WriteHTML($htm,2);

        $mpdf->Output('Anaethesiaform#'.$Registration_ID.'.pdf','I');
        exit;
    ?>