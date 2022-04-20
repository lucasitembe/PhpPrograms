<?php
include("./includes/connection.php");

    if(isset($_POST['cancer_id'])){
    $cancer_ID=$_POST['cancer_id'];
}

    if(isset($_POST['name_cancer'])){
    $name_cancer=$_POST['name_cancer'];
}
    if(isset($_POST['Registration_ID'])){
    $Registration_ID=$_POST['Registration_ID'];
}
?>
<style>
    .button_pro{
         color:white !important;
         height:27px !important;   
    }
</style>
<?php
             $sql_data_cancer = mysqli_query($conn,"SELECT weight,height,body_surface,diagnosis,weight_adjustment,allergies,dose_adjustment,stage,date_and_time FROM  tbl_cancer_patient_details WHERE cancer_type_id='$cancer_ID' AND Registration_ID='$Registration_ID'");
      
                              if(mysqli_num_rows($sql_data_cancer)>0){
                                  while($values = mysqli_fetch_assoc($sql_data_cancer)){
                                      
                                      $weight=$values['weight'];
                                      $height=$values['height'];
                                      $body_surface=$values['body_surface'];
                                      $diagnosis=$values['diagnosis'];
                                      $weight_adjustment=$values['weight_adjustment'];
                                      $allergies=$values['allergies'];
                                      $dose_adjustment=$values['dose_adjustment'];
                                      $stage=$values['stage'];
                                      $date_and_time=$values['date_and_time'];
       
   echo "<table class='table' style='background-color:white;'>
            
            <tr>
                <td>
                    <b><center>Weight(kg):</center></b> 
                </td>
                <td>
                    <input type='text' name='weight' id='weight' class='form-control' value='$weight'>
                </td>
                <td>
                    <b><center>Height(cm):</center></b> 
                </td>
                <td>
                    <input type='text' name='height' id='height' class='form-control' value='$height'>
                </td>
            </tr>
            <tr>
                <td>
                    <b><center>Body Surface(m2):</center></b> 
                </td>
                <td>
                    <input type='text' name='surface' id='surface' class='form-control' value='$body_surface'>
                </td>
                <td>
                    <b><center>Diagnosis:</center></b> 
                </td>
                <td>
                    <input type='text' name='diagnosis' id='diagnosis' class='form-control' value='$diagnosis'>
                </td>
            </tr>
            <tr>
                <td>
                    <b><center>Stage:</center></b> 
                </td>
                <td>
                    <input type='text' name='stage' id='stage' class='form-control' value='$stage'>
                </td>
                <td>
                    <b><center>Weight Adjustment:</center></b> 
                </td>
                <td>
                    <input type='radio' name='weight' value='Yes' id='Yes'>Yes
                    <input type='radio' name='weight' value='No' id='No'>No
                </td>
            </tr>
            <tr>
                <td>
                    <b><center>% of Dose Adjustment:</center></b> 
                </td>
                <td>
                    <input type='text' name='dosead'  id='dosead' class='form-control' value='$dose_adjustment'>
                </td>
                <td>
                    <b><center>Allergies:</center></b> 
                </td>
                <td>
                   <input type='text' name='allergies' id='allergies' class='form-control' value='$allergies'>
                </td>
            </tr>

                              </table>";}}

     echo   " <br>
            <div class='box box-primary' style='width:100%;'>
                        <div class='box-header'>
                            <div class='col-sm-12'><p id='sub_category_list_tittle' style='font-size:17px;text-align:center;font-weight: bold;'>$name_cancer</p></div>
                        </div>
                        
                        <div class='box-body'>
                            <table class='table'>
                                   <tr width='50px;'>
                     
                        <td width='45%'>
                            <div class='title' style='text-align:center;'><b>Adjuvant Therapy(for Stage III after surgery)</b></div>
                        </td>
                        <td>
                            <div class='title' style='text-align:center;'><b>Duration</b></div>
                        </td>
                    </tr>";
                    
                    
//               $sql_data_cancer_one=mysqli_query($conn,"SELECT adjuvant,duration FROM tbl_patient_adjuvant_duration WHERE cancer_type_id='$cancer_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
               
               $sql_select_data=mysqli_query($conn,"SELECT adjuvant,duration FROM tbl_patient_adjuvant_duration WHERE cancer_type_id='$cancer_ID' AND Registration_ID='$Registration_ID'");
                      
                                  while($valueo = mysqli_fetch_assoc($sql_select_data)){
                                      
                                      $adjuvant   =$valueo['adjuvant'];
                                      $duration   =$valueo['duration'];
                                      $patient_adjuvant_ID=$valueo['patient_adjuvant_ID'];
                                      echo "     <tr>
                        <td>
                          <input type='text' name='adjuvant[]' id='adjuvant[]' autocomplete='off' style='width:100%;display:inline;height:30px; text-align:center' value='$adjuvant'/>  
                        </td>
                        <td>
                           <input type='text' name='duration[]' id='duration[]' autocomplete='off' style='width:100%;display:inline;height:30px; text-align:center' value='$duration'/> 
                        </td>
                    </tr>";
                                  }
                              
                    
               
                     echo "<tr style='margin-top:30px;'>
                    <td>
                         </br>
                  <tr>
                <table class='table' style='background-color: white;margin:0% 0% 0% 5%;width:90%' >
                    <tr>
                      
                        <td width='50%'>
                            <div class='title' style='text-align:center;'><b>Physician to circle one in each column</b></div>
                        </td>
                    </tr>
                    <tr>
                       
                        <td width='50%'>
                            <table class='table' id='row-addition'>
                                <tr>
                                    <td>Volume</td>
                                    <td>Type</td>
                                    <td>Minutes</td>
                                </tr>";
                                
                                              
                    
//                      $sql_data_cancer_duration = mysqli_query($conn,"SELECT physician_volume,physician_type,physician_minutes,date_and_time,physician_ID FROM tbl_Physician WHERE cancer_type_id='$cancer_ID'");
//                       $sql_data_cancer_duration = mysqli_query($conn,"SELECT physician_volume,physician_type,physician_minutes FROM tbl_patient_physician WHERE cancer_type_id='$cancer_type_id' AND Registration_ID='$Registration_ID'");
                     
                        $mysql_data_details=mysqli_query($conn,"SELECT physician_volume,physician_type,physician_minutes FROM tbl_patient_physician WHERE cancer_type_id='$cancer_ID' AND Registration_ID='$Registration_ID'");
                           
                                    while($values = mysqli_fetch_assoc($mysql_data_details)){
                                      
                                      $physician_volume=$values['physician_volume'];
                                      $physician_minutes=$values['physician_minutes'];
                                      $physician_type=$values['physician_type'];
                                      echo "<tr>
                    
                        <td>
                          <input type='text' name='volume[]' id='volume[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center' value='$physician_volume'/>  
                        </td>
                        <td>
                           <input type='text' name='type[]' id='type[]' autocomplete='off'style='width:100%;display:inline;height:30px;text-align:center' value='$physician_type'/> 
                        </td>
                        <td>
                           <input type='text' name='minutes[]' id='minutes[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center' value='$physician_minutes'/> 
                        </td>
                    </tr> ";
                                  }
                             
                  
                 
                       echo "</table>
                        </td>
                    </tr>
               
                    </table>
                  </tr>
                    </td>
                </tr>
                
                                    <tr style='margin-top:30px;'>
              
                    <td>
                </br>
                <div class='title' style='text-align:center;'><b>Supportive Treatment</b></div>
                <table class='table' style='background-color: white;'>
                    <tr>
                    <th>SN</th>
                    <th width='50%'>Supportive treatment</th>
                    <th width='8%'>Dose(mg)</th>
                    <th width='6%'>Route</th>
                    <th  width='8%'>Administration Time</th>
                    <th width='8%'>Frequence</th>
                    <th  width='20%'>Medication Instructions and Indications</th>

                    </tr>";
                    
                                                                
                     $count=0;
//                      $sql_data_cancer_supportive = mysqli_query($conn,"SELECT supportive_treatment,Dose,Route,Administration_Time,Frequence,Medication_Instructions,treatment_ID FROM  tbl_supportive_treatment WHERE cancer_type_id='$cancer_ID'");
//                        $sql_data_cancer_supportive = mysqli_query($conn,"SELECT supportive_treatment,Dose,Route,Administration_Time,Frequence,Medication_Instructions,patient_supportive_ID FROM  tbl_patient_supportive_treatment WHERE cancer_type_id='$cancer_type_id' AND Registration_ID='$Registration_ID'");
                     $sql_select_details=mysqli_query($conn,"SELECT supportive_treatment,Dose,Route,Administration_Time,Frequence,Medication_Instructions FROM tbl_patient_supportive_treatment WHERE cancer_type_id='$cancer_ID' AND Registration_ID='$Registration_ID'");
                             
                                  while($valuesv = mysqli_fetch_assoc($sql_select_details)){
                                      $count++;
                                      $supportive_treatment=$valuesv['supportive_treatment'];
                                      $Dose=$valuesv['Dose'];
                                      $Medication_Instructions=$valuesv['Medication_Instructions'];
                                      $Route=$valuesv['Route'];
                                      $Administration_Time=$valuesv['Administration_Time'];
                                      $Frequence=$valuesv['Frequence'];
                                     
                                      echo "<tr><td>
                    <center>$count</center>  
                        </td>
                        <td>
                           <input type='text' name='item[]' id='item[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$supportive_treatment'/>
                        </td>
                        <td>
                             <input type='text' name='dose[]' id='dose[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$Dose'/>
                        </td>
                        <td>
                            <input type='text' name='route[]' id='route[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$Route'/>
                        </td>
                          <td>
                           <input type='text' name='admin[]' id='admin[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$Administration_Time'/>
                        </td>
                          <td>
                           <input type='text' name='frequence[]' id='frequence[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$Frequence'/>
                        </td>
                          <td>
                           <input type='text'  name='medication[]' id='medication[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$Medication_Instructions'/>
                        </td>
                        </tr>";
                                  }
                            
                      
           
               echo "</table>
                    </td>
                </tr>


               
                           <tr style='margin-top:30px;'>
              
                    <td>
                </br>
                <div class='title' style='text-align:center;'><b>Chemotherapy Drug</b></div>
                <table class='table' style='background-color: white;'>
                    <tr>
                    <th>SN</th>
                    <th width='50%'>Chemotherapy Drug</th>
                    <th width='8%'>Dose(mg)</th>
                    <th width='6%'>Volume(ml)</th>
                    <th  width='8%'>Route</th>
                    <th width='8%'>Admin Time</th>
                    <th  width='20%'>Frequency</th>
                    </tr>";
                    
                                                                
                     $count=0;
//                      $sql_data_cancer_drug = mysqli_query($conn,"SELECT Chemotherapy_Drug,Dose,Volume,Route,Admin_Time,Frequency,chemotherapy_ID FROM tbl_chemotherapy_drug WHERE cancer_type_id='$cancer_ID'");
//                      $sql_data_cancer_drug = mysqli_query($conn,"SELECT Chemotherapy_Drug,Dose,Volume,Route,Admin_Time,Frequency,patient_chemotherapy_ID FROM tbl_patient_chemotherapy_drug WHERE cancer_type_id='$cancer_type_id' AND Registration_ID='$Registration_ID'");
                     $details_data_patient=mysqli_query($conn,"SELECT Chemotherapy_Drug,Dose,Volume,Route,Admin_Time,Frequency FROM tbl_patient_chemotherapy_drug WHERE cancer_type_id='$cancer_ID' AND Registration_ID='$Registration_ID'");
                            
                                  while($values = mysqli_fetch_assoc($details_data_patient)){
                                      $count++;
                                      $Chemotherapy_Drug=$values['Chemotherapy_Drug'];
                                      $Dose=$values['Dose'];
                                      $Volume=$values['Volume'];
                                      $Route=$values['Route'];
                                      $Admin_Time=$values['Admin_Time'];
                                      $Frequency=$values['Frequency'];
                                      $chemotherapy_ID=$values['chemotherapy_ID'];
                                      echo "<tr><td>
                    <center>$count</center>  
                        </td>
                        <td>
                           <input type='text' name='item[]'  id='item[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$Chemotherapy_Drug'/>
                        </td>
                        <td>
                             <input type='text' name='dose[]' id='dose[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center;' value='$Dose'/>
                        </td>
                        <td>
                           <input type='text'  name='volume[]' id='volume[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center;' value='$Volume'/>
                        </td>
                        <td>
                            <input type='text' name='route[]' id='route[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center;' value='$Route'/>
                        </td>
                          <td>
                           <input type='text' name='admin[]' id='admin[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center;' value='$Admin_Time'/>
                        </td>
                          <td>
                           <input type='text' name='frequence[]'  id='frequence[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center;' value='$Frequency'/>
                        </td>
                 
                </tr> ";
                                  }
                           
                      
           
               echo "</table>
                    </td>
                </tr>
                
                            </table>
                        </div>
                    </div>";
               
       