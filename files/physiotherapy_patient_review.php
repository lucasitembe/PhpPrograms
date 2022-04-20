<?php
include("./includes/header.php");
include("./includes/connection.php");

if(isset($_GET['Registration_ID'])){
   $Registration_ID=$_GET['Registration_ID'];
}else{
   $Registration_ID=""; 
   
   
//get section for back buttons
if (isset($_GET['section'])) {
    $section = $_GET['section'];
} else {
    $section = '';
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}
if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
} else {
    $Patient_Payment_ID = 0;
}
if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
} else {
    $Patient_Payment_Item_List_ID = 0;
}
if(isset($_GET['this_page_from'])){
   $this_page_from=$_GET['this_page_from'];
}else{
   $this_page_from=""; 
}
}


if (isset($_GET['date_and_time'])) {
    $date_and_time = $_GET['date_and_time'];
} else {
    $date_and_time = 0;
}
?>
<a href="cancer_registration_record.php?Registration_ID=<?php echo $Registration_ID; ?>&section=Patient&PatientFile=PatientFileThisForm&fromPatientFile=true&this_page_from=patient_record" class="art-button-green">BACK</a>

          <?php
//    select patient information
if ($Registration_ID !="") {
//    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.Country,pr.Diseased,pr.District,pr.Ward,pr.Patient_Picture,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID,sp.Postal_Address,sp.Benefit_Limit
                                      from tbl_patient_registration pr, tbl_sponsor sp
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = $row['Patient_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Country = $row['Country'];
            $Patient_Picture = $row['Patient_Picture'];
            $Deseased = ucfirst(strtolower($row['Diseased']));
            $Sponsor_Postal_Address = $row['Postal_Address'];
            $Benefit_Limit = $row['Benefit_Limit'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Claim_Number_Status = $row['Claim_Number_Status'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            // echo $Ward."  ".$District."  ".$Ward; exit;
        }

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days, " . $diff->h . " Hours";
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Country = '';
        $Deseased = '';
        $Sponsor_Postal_Address = '';
        $Benefit_Limit = '';
        $Patient_Picture = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Guarantor_Name = '';
        $Claim_Number_Status = '';
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
        $Email_Address = '';
        $Occupation = '';
        $Employee_Vote_Number = '';
        $Emergence_Contact_Name = '';
        $Emergence_Contact_Number = '';
        $Company = '';
        $Employee_ID = '';
        $Registration_Date_And_Time = '';
        $age = 0;
    }
} else {
    $Registration_ID = '';
    $Old_Registration_Number = '';
    $Title = '';
    $Sponsor_ID = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Country = '';
    $Deseased = '';
    $Sponsor_Postal_Address = '';
    $Benefit_Limit = '';
    $Patient_Picture = '';
    $Region = '';
    $District = '';
    $Ward = '';
    $Guarantor_Name = '';
    $Claim_Number_Status = '';
    $Member_Number = '';
    $Member_Card_Expire_Date = '';
    $Phone_Number = '';
    $Email_Address = '';
    $Occupation = '';
    $Employee_Vote_Number = '';
    $Emergence_Contact_Name = '';
    $Emergence_Contact_Number = '';
    $Company = '';
    $Employee_ID = '';
    $Registration_Date_And_Time = '';
    $age = 0;
}
  ?> 
       <?php 
                               
                               $select_details_field = mysqli_query($conn,"SELECT name_position_1,name_position_2,name_position_3,name_position_4 FROM tbl_name_field_position WHERE Registration_ID='$Registration_ID'");
                               
                                                 $name_position_1 ="";
                                                 $name_position_2 ="";
                                                 $name_position_3 ="";
                                                 $name_position_4 ="";
                               while($row = mysqli_fetch_assoc($select_details_field)){
//                                                 echo "hapa hapa";
                                                 $name_position_1 =$row['name_position_1'];
                                                 $name_position_2 =$row['name_position_2'];
                                                 $name_position_3 =$row['name_position_3'];
                                                 $name_position_4 =$row['name_position_4'];
                                   
                               }
                                  
                               
                               ?>
<style>
    .button_pro{
        color:white;
        height:27px;
    }
</style>
<br/><br/><br/><fieldset style="width:99%;height:460px ;padding:5px;background-color:white;margin-top:20px;overflow-x:hidden;overflow-y:scroll">
    <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
     A:  PATIENT PROFILE
    </div>
    <div style="margin:2px;border:1px solid #000">
              <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
            <tr>
                <td style="width:10%;text-align:right "><b>Patient Name:</b></td><td colspan=""> <?php echo  $Patient_Name ?></td>
                <td style="width:10%;text-align:right "><b>Country:</b></td><td colspan=""><?php echo $Country  ?></td>
                <td style="width:10%;text-align:right "><b>Region:</b></td><td colspan=""><?php echo $Region  ?></td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right"><b>Registration #:</b></td><td><?php echo $Registration_ID  ?></td><td style="text-align:right"><b>Phone #:</b></td><td style=""><?php echo $Phone_Number ?></td><td style="text-align:right"><b>District:</b></td><td style=""><?php echo $District ?></td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right"><b>Date of Birth:</b></td><td style=""><?php echo date("j F, Y", strtotime($Date_Of_Birth)) ?></td><td style="text-align:right"><b>Gender:</b></td><td style=""><?php echo $Gender ?></td><td style="text-align:right"><b>Diseased:</b></td><td style=""><?php echo $Deseased ?></td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right" ><b>Insurance Details:</b></td><td colspan=""> <?php echo $Guarantor_Name . $sponsoDetails ?></td>
                <td style="width:10%;text-align:right" ><b>Consultation Date:</b></td><td colspan=""> <?php echo $Consultation_Date_And_Time ?></td>
                <td style="width:10%;text-align:right" ><b>Consultant :</b></td><td colspan=""> <?php echo $Employee_Title ?>  <?php echo ucfirst($Employee_Name) ?></td>
            </tr>
        </table>
     <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
     B:  MOBILITY ASSESSMENT
    </div>
           <?php
          $sql_patient_details=mysqli_query($conn,"SELECT * FROM tbl_mobility_assessment_save WHERE Registration_ID='$Registration_ID'");
            while($rows = mysqli_fetch_assoc($sql_patient_details)){
                
                          //$Employee_ID = $rows['Employee_ID'];
                          
                          //$Employee_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
             ?>
        <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                        <tr>
                                <td width="13%">
                                    <b>Consent</b>  
                        </td>
                                <td width="20%">
                                    <b>Medical Dignosis/
                               Medical Treatment Plan </b> 
                        </td>
                        <td width="6%">
                            <b>Patient History/
                            Subject Assessmentistory </b>
                        </td>
                         <td width="10%">
                             <b>Objective Assessment</b>                  
                        </td>
                         <td width="10%">
                             <b> Cognition</b>
                        </td>
                         <td width="10%">
                             <b> Speech</b>
                        </td>
                     
                        </tr>
                        
                        <tr>
                         <td width="13%">
                                    <?php  echo $rows['consent_treatement']; ?>  
                        </td>
                                <td width="20%">
                                   <?php echo $rows['consent_interest']; ?>
                        </td>
                        <td width="6%">
                           <?php echo $rows['treatement_plan']; ?>
                        </td>
                         <td width="10%">
                             <?php echo $rows['history_assessment']; ?>                 
                        </td>
                         <td width="10%">
                            <?php echo $rows['consent_cognition']; ?>
                        </td>
                         <td width="10%">
                             <?php echo $rows['consent_speech']; ?>
                        </td>
                       
                        </tr>
  
                        <tr>
                                
                               
                        <td width="6%">
                            <b>Patient expectation
                            Subjective markers</b>
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
                        </tr>
                              <tr>
                                  
                        
                                <td width="20%">
                                    <?php echo $rows['patient_perception']; ?>
                        </td>
                        <td width="6%">
                           <?php echo $rows['respiratory_screening']; ?>
                        </td>
                         <td width="10%">
                             <?php echo $rows['upper_functional']; ?>                
                        </td>
                         <td width="10%">
                             <?php echo $rows['upper_impaired']; ?> 
                        </td>
                          <td width="10%">
                            <?php echo  $rows['Defomity_text']; ?>
                        </td>
                         <td width="10%">
                            <?php echo $rows['power']; ?>
                        </td>
                        </tr>
                      
     
                        <tr>
                             
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
                              <tr>
                                   
                         <td width="13%">
                                   <?php echo $rows['Defomity_text2']; ?> 
                        </td>
                              <td width="10%">
                              <?php echo $rows['power2']; ?>
                        </td>
                         <td width="10%">
                              <?php echo $rows['neck_functional']; ?>
                        </td>
                         <td width="10%">
                              <?php echo $rows['neck_impared']; ?>
                        </td>
                         <td width="10%">
                              <?php echo $rows['neck_dizziness']; ?>
                        </td>
                         <td width="10%">
                              <?php echo $rows['trunk_funcional']; ?>
                        </td>
                        </tr>
                      
         
                    
                        <tr>
                                <td width="13%">
                                    <b>Trunk Kyphosis</b>  
                               </td>
                                <td width="13%">
                                    <b>Feet Footwear</b>  
                               </td>
                                <td width="13%">
                                    <b>Plan Analgesia</b>  
                               </td>
                                <td width="13%">
                                    <b>ul</b>  
                               </td>
                                <td width="13%">
                                    <b>ll</b>  
                               </td>
                                <td width="13%">
                                    <b>date_time_transaction</b>  
                               </td>
                           
                           
                     
                        </tr>
      
                              <tr>
                          
                         <td width="13%">
                                   <?php echo $rows['trunk_kyphosis']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['feet_footwear']; ?>  
                        </td>
                      
                         <td width="13%">
                                    <?php echo $rows['plan_analgesia']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['ul']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['ll']; ?>  
                        </td>
                         <td width="13%">
                                   <?php echo $rows['date_time_transaction']; ?>
                             </td>
                              </tr>
                              <tr>
                                           <td width="20%">
                                    <b> medical clerking</b> 
                        </td>
                            <td width="10%">
                             <b> Vision</b>
                        </td>
                                <td width="13%">
                                    <b>Hearing</b>  
                        </td>
                                <td width="13%">
                                    <b>lower functional</b>  
                        </td>
                                <td width="13%">
                                    <b>lower impaired</b>  
                        </td>
                                <td width="13%">
                                    <b>Trunk Impared</b>  
                               </td>
                              </tr>
                              <tr>
                                  <td width="13%">
                                    <?php echo $rows['medical_clerking']; ?>  
                        </td>
                                    <td width="10%">
                              <?php echo $rows['consent_vision']; ?>
                        </td>
                                  <td width="10%">
                             <?php echo $rows['consent_hearing']; ?>                
                        </td>
                         <td width="13%">
                                    <?php echo $rows['lower_functional']; ?> 
                        </td>
                         <td width="13%">
                                   <?php echo $rows['lower_impaired']; ?>  
                        </td>        
                      
                         <td width="13%">
                                    <?php echo $rows['trunk_impared']; ?>  
                        </td>
                              </tr>
                             
             <?php
            }
             ?>
                             
                    </table>
                          <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
     B:  KNEE ARTHOSCOPY ASSESSMENT
    </div>
                   <?php
                   
          $sql_patient_details=mysqli_query($conn,"SELECT * FROM tbl_knee_arthoscopy_save WHERE Registration_ID='$Registration_ID'");
            while($rows = mysqli_fetch_assoc($sql_patient_details)){
             ?>
        
                     <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                        <tr>
                                <td width="13%">
                                    <b>Treatment</b>  
                        </td>
                                <td width="13%">
                                    <b>Interests</b>  
                        </td>
                                <td width="13%">
                                    <b>Operation Findings</b>  
                        </td>
                                <td width="13%">
                                    <b>Pmh dh</b>  
                        </td>
                     
                                <td width="13%">
                                    <b>Infact</b>  
                        </td>
                                <td width="13%">
                                    <b>Altered</b>  
                        </td>
                        </tr>
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
                          </tr>
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
                                     
                       </tr>
                       <tr>
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
                       </tr>
                       
                       <tr>
                           <td width="10%">
                             <b> irq</b>
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
                       </tr>
                       <tr>
                            <td width="13%">
                                   <?php echo $rows['irq']; ?>  
                        </td>
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
                        </td>
                          <td width="8%">
                                    <?php echo $rows['mobile_aids']; ?> 
                        </td>
                           
                       </tr>
                       
                       <tr>
                           
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
           
                      
                                <td width="10%">
                             <b>Relevant leaflet supplied (yes)  </b>
                        </td>
                        <td width="10%">
                             <b>Relevant leaflet supplied (No)  </b>
                        </td>

                       </tr>
                       
                       <tr>
                           
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
                       </tr>
                       
                       <tr>
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
                       </tr>
                       <tr>
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
                       </tr>
                       <tr>
                         <td width="10%">
                             <b>Routine  </b>
                        </td>
                        <td width="10%">
                             <b>Outcome discussed with patient(Yes) </b>
                        </td>
                        <td width="10%">
                             <b>Outcome discussed with patient(No)</b>
                        </td>
                        <td width="10%">
                             <b>Date of consulted</b>
                        </td>
                       </tr>
                       <tr>
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
                         <?php
        $treatment_consent="";
        $sql_patient_details=mysqli_query($conn,"SELECT * FROM tbl_respiratory_assessment_data WHERE Registration_ID='$Registration_ID' ") or die(mysqli_error($conn));
           if($rows = mysqli_fetch_assoc($sql_patient_details)){
                                 $treatment_consent =$rows['treatment_consent'];  
//               
//                          $Employee_ID = $rows['Employee_ID'];
//                          
//                          $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
                 }
             ?> 
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
                </tr>
     
                <tr>
                    <td width="13%">
                                    <?php echo $treatment_consent; ?> 
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
                    
                </tr>
                <tr>
                 
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
                             <b> from date:</b>
                        </td>
                        <td width="10%">
                             <b> PH:</b>
                        </td>
                         <td width="10%">
                             <b> PCO2:</b>
                        </td>
                </tr>
                <tr>
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
                </tr>
                <tr>
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
                </tr>
                <tr>
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
                </tr>
                <tr>
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
                </tr>
                <tr>
                    
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
                    
                     <!--<td width="13%">-->
                         
<!--                                    <?php echo $rows['PH_2']; ?>  
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
                        </td>-->
                </tr>
                <tr>
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
                </tr>
                <tr>
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
                </tr>
                <tr>
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
                <tr>
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
                             <?php //echo $Employee_Name; ?> 
                        </td>
                </tr>
         </table>
                          <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
            B:  elderly_care_assessment
           </div>
                       <?php
        $sql_patient_details=mysqli_query($conn,"SELECT * FROM tbl_erdely_case_assessment WHERE Registration_ID='$Registration_ID' ") or die(mysqli_error($conn));
           if($rows = mysqli_fetch_assoc($sql_patient_details)){
                                 $treatment_consent =$rows['treatment_consent'];  
//               
//                          $Employee_ID = $rows['Employee_ID'];
//                          
//                          $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
                 }
             ?> 
        
        <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%" id='colum-addition'>
                                        <tr>
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
                                        </tr>
                                        <tr>
                        <td width="13%">
                                    <?php echo $rows['consent_treatment']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['consent_interest']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['patient_history']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['patient_problems']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['specimen_yes']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['specimen_no']; ?>  
                        </td>
                                        </tr>
                            <tr>
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

                            </tr>
                            <tr>
                                   <td width="13%">
                                    <?php echo $rows['specimen_na']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['chest_x_ray']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Cognition_infact']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Speech_infact']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Vision_infact']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Hearing_infact']; ?>  
                        </td>
                            </tr>
                            
                            <tr>
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
                             <b> Hearing Impared</b>
                        </td>
                         <td width="10%">
                             <b> Contraindications
                           precautions allergies</b>
                        </td>
                         <td width="10%">
                             <b> Respiratory</b>
                        </td>
                            </tr>
                            <tr>
                        <td width="13%">
                             <?php echo $rows['cognition_impared']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Speech_impared']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Vision_impared']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Hearing_impared']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['contraindications']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['respiratory']; ?>  
                        </td>
                            </tr>
                            <tr>
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
                            </tr>
                            <tr>
                                       <td width="13%">
                             <?php echo $rows['haemodynamics']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['relevant_clinical']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['relevant_clinical_2']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['relevant_clinical_3']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['relevant_clinical_3']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['upper_functional']; ?>  
                        </td>
                            </tr>
                            <tr>
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
                            </tr>
                            <tr>
                                                    <td width="13%">
                             <?php echo $rows['upper_impaired']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['defomity']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['power']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['lower_functional']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['lower_impaired']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['defomity']; ?>  
                        </td>
                            </tr>
                            <tr>
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
                            <tr>
                                                                 <td width="13%">
                             <?php echo $rows['power']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['defomity']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['upper_functional']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['lower_functional']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['trunk_functional']; ?>  
                        </td>
                            </tr>
                       </table>                  
     </fieldset>