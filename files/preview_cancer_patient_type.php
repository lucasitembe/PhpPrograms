<?php
@session_start();
include("./includes/connection.php");
	$Registration_ID = $_GET['Registration_ID'];
	$cancer_type_id = $_GET['cancer_type_id'];
	$Cancer_Name = $_GET['Cancer_Name'];


   ?>     
        
 <style>
    .button_pro{
         color:white !important;
         height:27px !important;   
    }
</style>
<?php


    $htm  = "<table width ='100%' height = '30px'>";
    $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align: center;'><h5> ".$Cancer_Name."</h5></td>";
    $htm .= "</tr>";
    $htm .= "</table><br/>";

       $sql_data_cancer = mysqli_query($conn,"SELECT weight,height,body_surface,diagnosis,weight_adjustment,allergies,dose_adjustment,stage,date_and_time FROM  tbl_cancer_patient_details WHERE cancer_type_id='$cancer_type_id' AND Registration_ID='$Registration_ID'");
      
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
                                 
   
  
     $htm .= "<table width=100% border=1 style='border-collapse: collapse;'>
            
            <tr>
                <td style='font-size:12px; font-weight:bold;'>
                    Weight(kg)
                </td>
                <td>
                    $weight
                </td>
                <td style='font-size:12px; font-weight:bold;'>
                    Height(cm)
                </td>
                <td>
                    $height
                </td>
            </tr>
            <tr>
                <td style='font-size:12px; font-weight:bold;'>
                    Body Surface(m2)
                </td>
                <td>
                    $body_surface 
                </td>
                <td style='font-size:12px; font-weight:bold;'>
                   Diagnosis
                </td>
                <td>
                    $diagnosis
                </td>
            </tr>
            <tr>
                <td style='font-size:12px; font-weight:bold;'>
                    Stage
                </td>
                <td>
                    $stage
                </td>
                <td style=' font-size:12px; font-weight:bold;'>
                    Weight Adjustment
                </td>
                <td >
                    $weight_adjustment
                
                </td>
            </tr>
            <tr>
                <td style='font-size:12px; font-weight:bold;'>
                    % of Dose Adjustment
                </td>
                <td>
                    $dose_adjustment
                </td>
                <td style='font-size:12px; font-weight:bold;'>
                    Allergies
                </td>
                <td>
                   $allergies
                </td>
            </tr>

                               ";}}
                              
                             
   
    $htm .= " 
            </table><br><br><table width=100% border=1 style='border-collapse: collapse;'>
            <tr>
              <td style='font-size:12px; font-weight:bold;'>
              Adjuvant Therapy(for Stage III after surgery)
                        </td>
                        <td style='font-size:12px; font-weight:bold;'>
                          Duration
                        </td>
                    </tr>";
                    
                    
                    
//                      $sql_data_cancer_one=mysqli_query($conn,"SELECT adjuvant,duration FROM tbl_patient_adjuvant_duration WHERE cancer_type_id='$cancer_type_id' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                        $sql_select_data=mysqli_query($conn,"SELECT adjuvant,duration FROM tbl_patient_adjuvant_duration WHERE cancer_type_id='$cancer_type_id' AND Registration_ID='$Registration_ID'");
                    
                                  while($valueo = mysqli_fetch_assoc($sql_select_data)){
                                      
                                      $adjuvant   =$valueo['adjuvant'];
                                      $duration   =$valueo['duration'];
                                      $patient_adjuvant_ID=$valueo['patient_adjuvant_ID'];
                                     $htm .= " <tr>
                        <td>
                          $adjuvant
                        </td>
                        <td>
                          $duration
                        </td>
                    </tr>";
                                  }
                              
                    
               
                    $htm .= "</table><br><br>
                <table width=100% border=1 style='border-collapse: collapse;'>
                    <tr>
                      
                        <td colspan='3' style='font-size:12px; font-weight:bold;'>
                          Physician to circle one in each column
                        </td>
                    </tr>
                    <tr>
                                    <td style='font-size:12px; font-weight:bold;'>Volume</td>
                                    <td style='font-size:12px; font-weight:bold;'>Type</td>
                                    <td style='font-size:12px; font-weight:bold;'>Minutes</td>

                                </tr>";
                                
                                              
                    
//                           $mysql_data_details=mysqli_query($conn,"SELECT physician_volume,physician_type,physician_minutes FROM tbl_patient_physician WHERE cancer_type_id='$cancer_type_id' AND Registration_ID='$Registration_ID'");
                           
                           $data_value_patient=mysqli_query($conn,"SELECT physician_volume,physician_type,physician_minutes FROM tbl_patient_physician  WHERE cancer_type_id='$cancer_type_id' AND Registration_ID='$Registration_ID'");
                                    while($values = mysqli_fetch_assoc($data_value_patient)){
                                      
                                      $physician_volume=$values['physician_volume'];
                                      $physician_minutes=$values['physician_minutes'];
                                      $physician_type=$values['physician_type'];
                                      
                                     $htm .= " <tr>
                    
                        <td>
                          $physician_volume 
                        </td>
                        <td>
                           $physician_type
                        </td>
                        <td>
                          $physician_minutes
                        </td>
                    </tr> ";}
//                   $htm .="</table>";
                            
                  
                 
                      $htm .="</table><br><br>
                      <table width=100% border=1 style='border-collapse: collapse;'>
                 <tr>   
                <td colspan='7' style='font-size:12px; font-weight:bold;' ><center>Supportive Treatment</center></td>
                 </tr>
                    <tr>
                    <td style='font-size:12px; font-weight:bold;'>SN</td>
                    <td style='font-size:12px; font-weight:bold;'>Supportive treatment</td>
                    <td style='font-size:12px; font-weight:bold;'>Dose(mg)</td>
                    <td style='font-size:12px; font-weight:bold;'>Route</td>
                    <td style='font-size:12px; font-weight:bold;'>Administration Time</td>
                    <td style='font-size:12px; font-weight:bold;'>Frequence</td>
                    <td style='font-size:12px; font-weight:bold;'>Medication Instructions and Indications</td>
                   
                    </tr>";
                    
                                                             
                     $count=0;
                              $sql_select_details=mysqli_query($conn,"SELECT supportive_treatment,Dose,Route,Administration_Time,Frequence,Medication_Instructions FROM tbl_patient_supportive_treatment WHERE cancer_type_id='$cancer_type_id' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                            
                                  while($valuesv = mysqli_fetch_assoc($sql_select_details)){
                                      $count++;
                                      $supportive_treatment=$valuesv['supportive_treatment'];
                                      $Dose=$valuesv['Dose'];
                                      $Medication_Instructions=$valuesv['Medication_Instructions'];
                                      $Route=$valuesv['Route'];
                                      $Administration_Time=$valuesv['Administration_Time'];
                                      $Frequence=$valuesv['Frequence'];
                                     $htm .= "<tr><td>
                    <center>$count</center>  
                        </td>
                        <td>
                           $supportive_treatment
                        </td>
                        <td>
                            $Dose
                        </td>
                        <td>
                            $Route
                        </td>
                          <td>
                           $Administration_Time
                        </td>
                          <td>
                           $Frequence
                        </td>
                          <td>
                           $Medication_Instructions
                        </td>
                        </tr>";
                              }
//                                   $htm .="</table>";

                      
               
               $htm .= "</table><br><br><table width=100% border=1 style='border-collapse: collapse;'>
                   
                           <tr><td colspan='7'><center>Chemotherapy Drug</center></td></tr>
                            <tr>
                                <td>SN</td>
                                <td>Chemotherapy Drug</td>
                                <td>Dose(mg)</td>
                                <td>Volume(ml)</td>
                                <td>Route</td>
                                <td>Admin Time</td>
                                <td>Frequency</td>
                            </tr>";
                    
                                                                
                     $count=0;
                      $sql_data_cancer_drug = mysqli_query($conn,"SELECT Chemotherapy_Drug,Dose,Volume,Route,Admin_Time,Frequency,patient_chemotherapy_ID FROM tbl_patient_chemotherapy_drug WHERE cancer_type_id=$cancer_type_id AND Registration_ID=$Registration_ID");
                            
                                  while($values = mysqli_fetch_assoc($sql_data_cancer_drug)){
                                      $count++;
                                      $Chemotherapy_Drug=$values['Chemotherapy_Drug'];
                                      $Dose=$values['Dose'];
                                      $Volume=$values['Volume'];
                                      $Route=$values['Route'];
                                      $Admin_Time=$values['Admin_Time'];
                                      $Frequency=$values['Frequency'];
                                      $chemotherapy_ID=$values['chemotherapy_ID'];
                                       $htm .= "<tr><td>
                   <center>$count</center>  
                       </td>
                       <td>
                         $Chemotherapy_Drug
                        </td>
                       <td>
                          $Dose
                       </td>
                      <td>
                          $Volume
                      </td>
                      <td>
                          $Route
                      </td>
                       <td>
                       $Admin_Time
                 </td>
                   <td>
                  $Frequency
              </td>
      </tr> ";
                                  }
                            
                      
           
             $htm .="</table>";
               
               
    include("MPDF/mpdf.php");
    $mpdf = new mPDF('s', 'A4');
    $mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($htm);
    $mpdf->Output(); 
    ?>