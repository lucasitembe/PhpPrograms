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
     B:  POSITION AND IMMOBILIZATION
    </div>
           <?php
          $sql_patient_details=mysqli_query($conn,"SELECT * FROM tbl_position_immobilization WHERE Registration_ID='$Registration_ID'");
            if($rows = mysqli_fetch_assoc($sql_patient_details)){
                
                          $Employee_ID = $rows['Employee_ID'];
                          
                          $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
             ?>
        <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                        <tr>
                                <td width="13%">
                                    <b>Body Position</b>  
                        </td>
                                <td width="20%">
                                    <b>Head Position</b> 
                        </td>
                        <td width="6%">
                            <b>Arm Position</b>
                        </td>
                         <td width="10%">
                             <b>Legs Position</b>                  
                        </td>
                         <td width="10%">
                             <b> Blocks</b>
                        </td>
                         <td width="10%">
                             <b> Done By</b>
                        </td>
                         <td width="10%">
                             <b> Date Consulted</b>
                        </td>
                        </tr>
                        <tr>
                         <td width="13%">
                                    <?php echo $rows['body_position']; ?>  
                        </td>
                                <td width="20%">
                                   <?php echo $rows['head_position']; ?>
                        </td>
                        <td width="6%">
                           <?php echo $rows['legs_position']; ?>
                        </td>
                         <td width="10%">
                             <?php echo $rows['blocks']; ?>                 
                        </td>
                         <td width="10%">
                            <?php echo $rows['blocks']; ?>
                        </td>
                         <td width="10%">
                             <?php echo $Employee_Name; ?>
                        </td>
                         <td width="10%">
                              <?php echo $rows['date_time']; ?>
                        </td>
                        </tr>
        </table>
          <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
     B:  PARAMETERS
    </div>
                <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                        <tr>
                                <td width="13%">
                                    <b>TECHNIQUE</b>  
                        </td>
                                <td width="20%">
                                    <b>SEPARATION</b> 
                        </td>
                        <td width="6%">
                            <b>DEPTH</b>
                        </td>
                         <td width="10%">
                             <b>Number of Site</b>                  
                        </td>
                         <td width="10%">
                             <b> Number of Fields</b>
                        </td>
                           <td width="10%">
                             <b> Done By</b>
                        </td>
                         <td width="10%">
                             <b> Date Consulted</b>
                        </td>
                        </tr>
                              <tr>
                         <td width="13%">
                                    <?php echo $rows['techniques']; ?>  
                        </td>
                                <td width="20%">
                                    <?php echo $rows['separation']; ?>
                        </td>
                        <td width="6%">
                           <?php echo $rows['depth']; ?>
                        </td>
                         <td width="10%">
                             <?php echo $rows['number_site']; ?>                
                        </td>
                         <td width="10%">
                             <?php echo $rows['number_field']; ?> 
                        </td>
                          <td width="10%">
                            <?php echo $Employee_Name; ?>
                        </td>
                         <td width="10%">
                            <?php echo $rows['date_time']; ?>
                        </td>
                        </tr>
                      
           </table>
                  <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
     B:  POSITION DEVICES
    </div>
                <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                        <tr>
                                <td width="13%">
                                    <b>DEVICES</b>  
                        </td>
                                <td width="13%">
                                    <b>NAME OF SITE</b>  
                        </td>
                                <td width="13%">
                                    <b>DIAGNOSIS</b>  
                        </td>
                          <td width="10%">
                             <b> Done By</b>
                        </td>
                         <td width="10%">
                             <b> Date Consulted</b>
                        </td>
                     
                        </tr>
                              <tr>
                         <td width="13%">
                                    <?php echo $rows['beam_devices']; ?> 
                        </td>
                         <td width="13%">
                                   <?php echo $rows['name_of_site']; ?>  
                        </td>
                         <td width="13%">
                                   <?php echo $rows['Diagnosis']; ?> 
                        </td>
                              <td width="10%">
                              <?php echo $Employee_Name; ?>
                        </td>
                         <td width="10%">
                              <?php echo $rows['date_time']; ?>
                        </td>
                        </tr>
                      
           </table>
           <?php
            }
             ?>
                       <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
     B: FIELD POSITION
    </div>
           
                <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                    
                        <tr>
                                <td width="13%">
                                    <b>FIELD</b>  
                               </td>
                                <td width="13%">
                                    <b>F.S. ON SKIN(cm)</b>  
                               </td>
                                <td width="13%">
                                    <b>F.S. ON TUMOUR(cm)</b>  
                               </td>
                                <td width="13%">
                                    <b>SSD/SAD(cm)</b>  
                               </td>
                                <td width="13%">
                                    <b>DEPTH ANT/POST (cm)</b>  
                               </td>
                                <td width="13%">
                                    <b>GANTRY ANGLE</b>  
                               </td>
                                <td width="13%">
                                    <b>COLL ANGLE</b>  
                               </td>
                                <td width="13%">
                                    <b>COUGH ANGLE</b>  
                               </td>
                                   <td width="10%">
                             <b> Done By</b>
                                     </td>
                             <td width="10%">
                             <b> Date Consulted</b>
                             </td>
                           
                     
                        </tr>
                         <?php
        
          $sql_patient_details=mysqli_query($conn,"SELECT * FROM tbl_position_immobilization psm,tbl_fields_position fld WHERE psm.Registration_ID='$Registration_ID' AND psm.position_immobilization_ID=fld.position_immobilization_ID");
            while($rows = mysqli_fetch_assoc($sql_patient_details)){
                
                          $Employee_ID = $rows['Employee_ID'];
                          
                          $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
                
                
             ?>
                              <tr>
                         <td width="13%">
                                    <?php echo $rows['field_name']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['f_s_on_skin']; ?>  
                        </td>
                         <td width="13%">
                                   <?php echo $rows['f_s_on_tumour']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['ssd_sad']; ?>  
                        </td>
                      
                         <td width="13%">
                                    <?php echo $rows['depth']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['gantry_angle']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['coll_angle']; ?>  
                        </td>
                         <td width="13%">
                                   <?php echo $rows['cough_angle']; ?>  
                        </td>
                                  <td width="10%">
                             <?php echo $Employee_Name; ?> 
                        </td>
                         <td width="10%">
                              <?php echo $rows['date_time']; ?>
                        </td>
                        </tr>
                   <?php
            }
             ?>    
           </table>
                          <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
     B:  FIELD SIDE
    </div>
           
                <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                        <tr>
                                <td width="13%">
                                    <b>Equivalent Square</b>  
                        </td>
                                <td width="13%">
                                    <b>cGY/Min in (SSD)</b>  
                        </td>
                                <td width="13%">
                                    <b>cGY/Min in (SAD)</b>  
                        </td>
                                <td width="13%">
                                    <b>PDD</b>  
                        </td>
                     
                                <td width="13%">
                                    <b>TAR</b>  
                        </td>
                                <td width="13%">
                                    <b>Couch Factor</b>  
                        </td>
                                <td width="13%">
                                    <b>Wedge Factor</b>  
                        </td>
                                <td width="13%">
                                    <b>Inhomogy Tray/BHF</b>  
                        </td>
                                <td width="13%">
                                    <b>Tumour Dose Rate</b>  
                        </td>
                                <td width="13%">
                                    <b>Dose per Fraction</b>  
                        </td>
                                <td width="13%">
                                    <b>Treatment Time Min/MU</b>  
                        </td>
                        <td width="10%">
                             <b> Done By</b>
                                     </td>
                        <td width="10%">
                             <b> Date Consulted</b>
                        </td>
                     
                        </tr>
                                 <?php
        
            $sql_patient_details=mysqli_query($conn,"SELECT * FROM tbl_calculation_parameter WHERE Registration_ID='$Registration_ID' ");
            while($rows = mysqli_fetch_assoc($sql_patient_details)){
                
                          $Employee_ID = $rows['Employee_ID'];
                          
                          $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
             ?>
                              <tr>
                         <td width="13%">
                                   <?php echo $rows['Eq_Square']; ?>  
                        </td>
                         <td width="10%">
                                    <?php echo $rows['cGY_SSD']; ?>  
                        </td>
                         <td width="10%">
                                   <?php echo $rows['cGY_SAD']; ?> 
                        </td>
                         <td width="8%">
                                    <?php echo $rows['PDD']; ?> 
                        </td>
                         <td width="8%">
                                    <?php echo $rows['TAR']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Couch_Factor']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Wedge_Factor']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Inhomogy_Tray']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Tumour_Dose']; ?>  
                        </td>
                         <td width="13%">
                                   <?php echo $rows['Dose_Fraction']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['Treatment_Time']; ?> 
                        </td>
                       
                                     <td width="10%">
                             <?php echo $Employee_Name; ?> 
                        </td>
                         <td width="10%">
                              <?php echo $rows['date_time']; ?>
                        </td>
                        </tr>
                              <?php
            }
             ?>  
           </table>
           <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
     B:  Machine Setup
    </div>
                <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                        <tr>
                                <td width="13%">
                                    <b>Unit</b>  
                        </td>
                                <td width="13%">
                                    <b>Wedge</b>  
                        </td>
                                <td width="13%">
                                    <b>Block</b>  
                        </td>
                                <td width="13%">
                                    <b>Dose per Fraction</b>  
                        </td>
                                <td width="13%">
                                    <b>Total tumour Dose</b>  
                        </td>
                                <td width="13%">
                                    <b>Number of Fraction</b>  
                        </td>
                           <td width="10%">
                             <b> Done By</b>
                                     </td>
                        <td width="10%">
                             <b> Date Consulted</b>
                        </td>
                     
                        </tr>
         <?php
        
            $sql_patient_details=mysqli_query($conn,"SELECT * FROM tbl_machine_setup_delivery WHERE Registration_ID='$Registration_ID'");
            while($rows = mysqli_fetch_assoc($sql_patient_details)){
                
                          $Employee_ID = $rows['Employee_ID'];
                          
                          $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
             ?>
                              <tr>
                         <td width="13%">
                                    <?php echo $rows['unit']; ?> 
                        </td>
                         <td width="13%">
                                   <?php echo $rows['wedge']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['block']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['dose_per_fraction']; ?> 
                        </td>
                         <td width="13%">
                                    <?php echo $rows['total_tomour_dose']; ?>  
                        </td>
                         <td width="13%">
                                    <?php echo $rows['number_of_fraction']; ?>  
                        </td>
                        <td width="10%">
                             <?php echo $Employee_Name; ?> 
                        </td>
                         <td width="10%">
                              <?php echo $rows['wedge_date_time']; ?>
                        </td>
                       
                        </tr>
               <?php
            }
             ?>    
           </table>
                          <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
            B:  TREATMENT DELIVERY
           </div>
               
        
                    <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%" id='colum-addition'>
                        <tr>
                            <td width="4%" rowspan="2">
                                    <b>Date</b>  

                        </td>
                        <td width="5%" colspan="3">
                        <center> <b><?php echo $name_position_1; ?> </b> </center>
                           
                        </td>
                        <td width="6%"  colspan="3">
                             <center> <b><?php echo $name_position_2; ?></b> </center>
                        </td>
                         <td width="6%" colspan="3">
                              <center> <b><?php echo $name_position_3; ?></b> </center>
                  
                        </td>
                         <td width="6%" colspan="3">
                             <center> <b><?php echo $name_position_4; ?></b> </center>
                        </td>
                        
                        </tr>
                     
                        
                            <tr>
                                <td>Dose per Fraction</td>
                                <td>Time</td>
                                <td>Cummutive Dose</td>
                                <td>Dose per Fraction</td>
                                <td>Time</td>
                                <td>Cummutive Dose</td>
                                <td>Dose per Fraction</td>
                                <td>Time</td>
                                <td>Cummutive Dose</td>
                                <td>Dose per Fraction</td>
                                <td>Time</td>
                                <td>Cummutive Dose</td>
                                     <td>
                                         <b> Done By</b> 
                                     </td>
                                 <td>
                                  Date Consulted
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
     </fieldset>