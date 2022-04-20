

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

           </table>
                          <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
     B:  KNEE ARTHOSCOPY ASSESSMENT
    </div>
  
           
                <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                        <tr>
                  
                                <td width="13%">
                                    <b>Reason Causes</b>  
                        </td>
                                <td width="13%">
                                    <b>Checked Stable</b>  
                        </td>
                                <td width="13%">
                                    <b>Brace</b>  
                        </td>
                                <td width="13%">
                                    <b>Raymed</b>  
                        </td>
                                <td width="13%">
                                    <b>Wool Crepe</b>  
                        </td>
                        <td width="10%">
                             <b> sq</b>
                                     </td>
                                     
                                     
                        <td width="10%">
                             <b> irq</b>
                        </td>
                     
                        </tr>
                        <tr>
                                                    <td width="10%">
                             <b>  SH</b>
                        </td>
                        <td width="10%">
                             <b>  Knee flex</b>
                        </td>
                        <td width="10%">
                             <b>SLR</b>
                        </td>
                        <td width="10%">
                             <b> Any lag</b>
                        </td>
                        <td width="10%">
                             <b>Addition comments</b>
                        </td>
                        <td width="10%">
                             <b>Mobile independently with no aids</b>
                        </td>
                        <td width="10%">
                             <b>Mobile safety with EC </b>
                        </td>
                        <td width="10%">
                             <b> Sticks </b>
                        </td>
                        <td width="10%">
                             <b>Zimmer frame  </b>
                        </td>
                        <td width="10%">
                             <b>Relevant leaflet supplied </b>
                        </td>
           
                        </tr>
                        <tr>
                                <td width="10%">
                             <b>Relevant leaflet supplied (yes)  </b>
                        </td>
                        <td width="10%">
                             <b>Relevant leaflet supplied (No)  </b>
                        </td>
                        <td width="10%">
                             <b>Relevant leaflet supplied (NA)  </b>
                        </td>
                        <td width="10%">
                             <b>Follow up: (yes)  </b>
                        </td>
                        <td width="10%">
                             <b>Follow up: (No)  </b>
                        </td>
                        <td width="10%">
                             <b>Follow up: (NA)  </b>
                        </td>
                        <td width="10%">
                             <b>Urgent </b>
                        </td>
                        <td width="10%">
                             <b> Priority  </b>
                        </td>
                        <td width="10%">
                             <b>Routine  </b>
                        </td>
                        <td width="10%">
                             <b>Outcome discussed with patient(Yes) </b>
                        </td>
                        <td width="10%">
                             <b>Outcome discussed with patient(No)</b>
                        </td>
                        </tr>
                     <?php
          $sql_patient_details=mysqli_query($conn,"SELECT * FROM tbl_knee_arthoscopy_save WHERE Registration_ID='$Registration_ID'");
            if($rows = mysqli_fetch_assoc($sql_patient_details)){
                
                          //$Employee_ID = $rows['Employee_ID'];
                          
                          //$Employee_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
             ?>
                              <tr>
                         <td width="13%">
                                   <?php echo $rows['treatment_id']; ?>  
                        </td>
                         <td width="10%">
                                    <?php echo $rows['interests_id']; ?>  
                        </td>
                         <td width="10%">
                                   <?php echo $rows['operation_findings']; ?> 
                        </td>
                         <td width="8%">
                                    <?php echo $rows['pmh_dh']; ?> 
                        </td>
                         <td width="8%">
                                    <?php echo $rows['infact']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['altered']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['reason_causes']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['checked_stable']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['brace']; ?>  
                        </td>
                         <td width="13%">
                                   <?php echo $rows['raymed']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['wool_crepe']; ?> 
                        </td>
                       
                                     <td width="10%">
                             <?php echo $rows['sq']; ?> 
                        </td>
                         <td width="10%">
                       <td width="13%">
                                   <?php echo $rows['knee_flex']; ?>  
                        </td>
                         <td width="10%">
                                    <?php echo $rows['slr']; ?>  
                        </td>
                         <td width="10%">
                                   <?php echo $rows['any_lag']; ?> 
                        </td>
                         <td width="8%">
                                    <?php echo $rows['addition_comments']; ?> 
                        </td>                <?php echo $rows['irq']; ?>
                        </td>
                        </tr>
                        
                         <tr>
                
                         <td width="8%">
                                    <?php echo $rows['mobile_aids']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['mobile_safety']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['sticks']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['zimmer_frame']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['relevant_supplied']; ?>  
                        </td>
                         <td width="13%">
                                   <?php echo $rows['yes']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['no']; ?> 
                        </td>
                       
                             <td width="10%">
                             <?php echo $rows['NA']; ?> 
                        </td>
                         <td width="10%">
                              <?php echo $rows['yes_follow']; ?>
                        </td>
                             <td width="10%">
                             <?php echo $rows['no_follow']; ?> 
                        </td>
                         <td width="10%">
                              <?php echo $rows['na_follow']; ?>
                        </td>
                             <td width="10%">
                             <?php echo $rows['ugent']; ?> 
                        </td>
                         <td width="10%">
                              <?php echo $rows['priority']; ?>
                        </td>
                             <td width="10%">
                             <?php echo $rows['routine']; ?> 
                        </td>
                         <td width="10%">
                              <?php echo $rows['yes_routine']; ?>
                        </td>
                             <td width="10%">
                             <?php echo $rows['no_routine']; ?> 
                        </td>
                         <td width="10%">
                              <?php echo $rows['date_time_transaction']; ?>
                        </td>
                        </tr>
                              <?php
           }
             ?>  
           </table>
           <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
     B:  Respiratory Assessment
    </div>
                <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                        <tr>
                                      <td width="13%">
                                    <b>Treatment</b>  
                        </td>
                                <td width="13%">
                                    <b>Interests</b>  
                        </td>
                                <td width="13%">
                                    <b>medical diagnosis plan</b>  
                        </td>
                                <td width="13%">
                                    <b>Patient History</b>  
                        </td>
                                <td width="13%">
                                    <b>Home O2 Nebulisers Inhalers</b>  
                        </td>
                                <td width="13%">
                                    <b>Sputum Specimen sent (Yes)</b>  
                        </td>
                                <td width="13%">
                                    <b>Sputum Specimen sent (No)</b>  
                        </td>
                                <td width="13%">
                                    <b>Sputum Specimen sent (NA)</b>  
                        </td>
                           <td width="10%">
                             <b> Chest X-Ray</b>
                                     </td>
                        <td width="10%">
                             <b> A.B.G'S Infact:</b>
                        </td>
                        <td width="10%">
                             <b> PH:</b>
                        </td>
                        <td width="10%">
                             <b> PCO2:</b>
                        </td>
                        <td width="10%">
                             <b> PO2:</b>
                        </td>
                        <td width="10%">
                             <b> HCO3:</b>
                        </td>
                        <td width="10%">
                             <b> BE:</b>
                        </td>
                        <td width="10%">
                             <b> SaO2:</b>
                        </td>
                        <td width="10%">
                             <b> %O:</b>
                        </td>
                        <td width="10%">
                             <b> Via:</b>
                        </td>
                        <td width="10%">
                             <b> Patient Perception:</b>
                        </td>
                        <td width="10%">
                             <b> Observation:</b>
                        </td>
                        <td width="10%">
                             <b> B.P:</b>
                        </td>
                        <td width="10%">
                             <b> H.R:</b>
                        </td>
                        <td width="10%">
                             <b>Temp:</b>
                        </td>
                        <td width="10%">
                             <b> SPO2:</b>
                        </td>
                        <td width="10%">
                             <b> FIO2:</b>
                        </td>
                        <td width="10%">
                             <b> RR:</b>
                        </td>
                        <td width="10%">
                             <b> Cough yes:</b>
                        </td>
                        <td width="10%">
                             <b> Cough No:</b>
                        </td>
                        <td width="10%">
                             <b> Cough colour:</b>
                        </td>
                        <td width="10%">
                             <b> Cough Amount:</b>
                        </td>
                        <td width="10%">
                             <b> Cough Type:</b>
                        </td>
                        
                                <td width="10%">
                             <b> Productive Yes:</b>
                        </td>
                        
                        <td width="10%">
                             <b> Productive No:</b>
                        </td>
                        <td width="10%">
                             <b> Productive colour:</b>
                        </td>
                        <td width="10%">
                             <b> Productive Amount:</b>
                        </td>
                        <td width="10%">
                             <b> Productive Type:</b>
                        </td>
                        <td width="10%">
                             <b> Objective
                                   Auscultation
                                 Palpation:</b>
                        </td>
                        <td width="10%">
                             <b> Problem List:</b>
                        </td>
                        <td width="10%">
                             <b> Treatment Plan Goals:</b>
                        </td>
                        <td width="10%">
                             <b> Timescale:</b>
                        </td>
                
                     
                        </tr>
         <?php
        
            $sql_patient_details=mysqli_query($conn,"SELECT * FROM  tbl_respiratory_assessment WHERE Registration_ID='$Registration_ID'");
            while($rows = mysqli_fetch_assoc($sql_patient_details)){
                
                          $Employee_ID = $rows['Employee_ID'];
                          
                          $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
             ?>
                              <tr>
                         <td width="13%">
                                    <?php echo $rows['treatment_consent']; ?> 
                        </td>
                         <td width="13%">
                                   <?php echo $rows['interests_consent']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['medical_diagnosis']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['medical_clerking']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['inhalers_home']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['specimen_no']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['specimen_yes']; ?> 
                        </td>
                         <td width="13%">
                                   <?php echo $rows['specimen_na']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['chest_x_ray']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['invesiigations']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['fromDate']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['PH']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['PCO2']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['HCO3']; ?> 
                        </td>
                         <td width="13%">
                                   <?php echo $rows['BE']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['SaO2']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['O_percent']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Via']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['PH_2']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['PCO2_2']; ?> 
                        </td>
                         <td width="13%">
                                   <?php echo $rows['HCO3_2']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['BE_2']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['SaO2_2']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['O_percent_2']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Via_2']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Subjective_Markers']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Observation']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['B_P']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['H_R']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Temp']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['SPO2_new']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['FIO2_new']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['RR_new']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['yes_Cough']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['No_Productive']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['color_Productive']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['amount_Productive']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['type_Productive']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Problem_List']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Treatment_Plan_Goals']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Timescale']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['date_time_transaction']; ?>  
                        </td>
                        <td width="10%">
                             <?php echo $Employee_Name; ?> 
                        </td>
                       
                        </tr>
               <?php
            }
             ?>    
           </table>
                          <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
            B:  elderly_care_assessment
           </div>
               
           <?php
        
            $sql_patient_details=mysqli_query($conn,"SELECT * FROM  tbl_respiratory_assessment WHERE Registration_ID='$Registration_ID'");
            while($rows = mysqli_fetch_assoc($sql_patient_details)){
                
                          $Employee_ID = $rows['Employee_ID'];
                          
                          $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
            }
                          
                          ?>
                    <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%" id='colum-addition'>
                                        <tr>
                                      <td width="13%">
                                    <b>Treatment</b>  
                        </td>
                                <td width="13%">
                                    <b>Interests</b>  
                        </td>
                  
                                <td width="13%">
                                    <b>Patient History</b>  
                        </td>
                                <td width="13%">
                                    <b>Home O2 Nebulisers Inhalers</b>  
                        </td>
                                <td width="13%">
                                    <b>Sputum Specimen sent (Yes)</b>  
                        </td>
                                <td width="13%">
                                    <b>Sputum Specimen sent (No)</b>  
                        </td>
                                <td width="13%">
                                    <b>Sputum Specimen sent (NA)</b>  
                        </td>
                           <td width="10%">
                             <b> Chest X-Ray</b>
                                     </td>
                      <td width="10%">
                             <b> Cognition Infact</b>
                        </td>
                         <td width="10%">
                             <b> Speech Infact</b>
                        </td>
                         <td width="10%">
                             <b> Vision Infact</b>
                        </td>
                         <td width="10%">
                             <b> Vision Hearing</b>
                        </td>
                      <td width="10%">
                             <b> Cognition Impared</b>
                        </td>
                         <td width="10%">
                             <b> Speech Impared</b>
                        </td>
                         <td width="10%">
                             <b> Vision Impared</b>
                        </td>
                         <td width="10%">
                             <b> Vision Impared</b>
                        </td>
                         <td width="10%">
                             <b> Contraindications
                           precautions allergies</b>
                        </td>
                         <td width="10%">
                             <b> Respiratory</b>
                        </td>
                         <td width="10%">
                             <b>Haemodynamics</b>
                        </td>
                         <td width="10%">
                             <b> Relevant Clinical Investigations</b>
                        </td>
                         <td width="10%">
                             <b> Relevant Clinical Investigations</b>
                        </td>
                         <td width="10%">
                             <b> Relevant Clinical Investigations</b>
                        </td>
                                 <td width="10%">
                             <b>Respiratory Screening</b>                  
                        </td>
                         <td width="10%">
                             <b> upper functional</b>
                        </td>
                           <td width="10%">
                             <b> upper impaired</b>
                        </td>
                         <td width="10%">
                             <b> Defomity text</b>
                        </td>
                         <td width="10%">
                             <b> Power</b>
                        </td>
                        
                                       <td width="13%">
                                    <b>lower functional</b>  
                        </td>
                                <td width="13%">
                                    <b>lower impaired</b>  
                        </td>
                                <td width="13%">
                                    <b>Defomity text</b>  
                        </td>
                          <td width="10%">
                             <b> power</b>
                        </td>
                         <td width="10%">
                             <b> Neck Functional</b>
                        </td>
                         <td width="10%">
                             <b> Neck impared</b>
                        </td>
                         <td width="10%">
                             <b> Neck Dizziness</b>
                        </td>
                         <td width="10%">
                             <b> Trunk Funcional</b>
                        </td>
                
                     
                        </tr>
                               <?php
        
                              $sql_patient_details=mysqli_query($conn,"SELECT * FROM tbl_treatment_delivery td,tbl_machine_setup_delivery my  WHERE td.setup_devery_ID=my.setup_devery_ID AND my.Registration_ID='$Registration_ID'");
                            while($rows = mysqli_fetch_assoc($sql_patient_details)){
                                
                                  $Employee_ID = $rows['Employee_ID'];
                          
                                  $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
                                  
                                     $Date_field = $rows['Date_field'];
                                     
                                     $date_function = date("Y-m-d",$Date_field);
                                ?>
                            <tr>
                                <td><center><?php echo $rows['wedge_date_time']; ?></center></td>
                                <td><center><?php echo $rows['Dose_per_Fraction1']; ?></center></td>
                                <td><center><?php echo $rows['Time1']; ?></center></td>
                                <td><center><?php echo $rows['Cummutive_Dose1']; ?></center></td>
                                <td><center><?php echo $rows['Dose_per_Fraction2']; ?></center></td>
                                <td><center><?php echo $rows['Time2']; ?></center></td>
                                <td><center><?php echo $rows['Cummutive_Dose2']; ?></center></td>
                                <td><center><?php echo $rows['Dose_per_Fraction3']; ?></center></td>
                                <td><center><?php echo $rows['Time3']; ?></center></td>
                                <td><center><?php echo $rows['Cummutive_Dose3']; ?></center></td>
                                <td><center><?php echo $rows['Dose_per_Fraction4']; ?></center></td>
                                <td><center><?php echo $rows['Time4']; ?></center></td>
                                <td><center><?php echo $rows['Cummutive_Dose4']; ?></center></td>
                                <td><center><?php echo $Employee_Name; ?></center></td>
                                <td><center><?php echo $rows['wedge_date_time']; ?></center></td>
                               
                            </tr>
                                </td>
                            </tr>
                          <!--<input type='hidden' id='rowCount' value='1'>-->
                            <?php
                                 }
                             ?>  
                    </table>
         
    </div>