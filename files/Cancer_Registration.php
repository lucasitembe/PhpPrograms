<style>
    .otherdoclinks:hover{
        text-decoration:underline;
        color: #000000; 
        cursor:pointer; 
    }
</style>
<?php
include("./includes/header.php");
include("./includes/connection.php");
include './includes/cleaninput.php';
// session_start();

//        echo '<pre>';
//        print_r($_SESSION['hospitalConsultaioninfo']);exit;
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    $Patient_Payment_Item_List_ID = 0;
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
?>
<?php

    if(isset($_GET['Registration_ID'])){
    $Registration_ID=$_GET['Registration_ID'];
}
    if(isset($_GET['from_consulted'])){
    $from_consulted=$_GET['from_consulted'];
}
    if(isset($_GET['Patient_Payment_ID'])){
  $Patient_Payment_ID=$_GET['Patient_Payment_ID'];
}
    if(isset($_GET['Patient_Payment_Item_List_ID'])){
     $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
    
}
?>
 <a href='Cancer_Patient.php?Registration_ID=<?= $Registration_ID ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?= $from_consulted ?>' class='art-button-green'>
       BACK
    </a>
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
             <?php
//                  $sql_select_data_of_cancer = mysqli_query($conn,"SELECT FROM tbl_cancer_registration re, ");
//              
//              ?>
                <table>
                     <tr>
                <td style="width:10%;text-align:right " ><b>HIV Sero-Status:</b></td>
                <td colspan="2" width="40%">SN<input type='checkbox' name='SN' id='SN' value='SN'>SP on ART<input type='checkbox' name='SP_on _ART' id='SP_on_ART' value='SP on ART'>SP not on ART<input type='checkbox' name='SP_not_on_ART' id='SP_not_on_ART' value='SP_not_on_ART'>Unknown<input type='checkbox' name='Unknown3' id='Unknown3' value='Unknown'><div align="right"><b>Date diagnosis HIV:</b></div></td>
               
               <td style="width:10%;text-align:right " ><input type='text' style="width:100%;" name='Date_diagnosis_HIV' id='Date_diagnosis_HIV' class="date" placeholder="Date"></td>
            </tr>
            </table>
                <table>
                     <tr>
                <td style="width:10%;text-align:right " ><b>Chronic disease at diagnosis specify:</b></td>
                <!--<td colspan="2" width="40%">SN<input type='checkbox' name='incidence' id='incidence'>SP on ART<input type='checkbox' name='incidence' id='incidence'>SP not on ART<input type='checkbox' name='incidence' id='incidence'>Unknown<input type='checkbox' name='incidence' id='incidence'><div align="right"><b>Date diagnosis HIV:</b></div></td>-->
               
               <td style="width:10%;text-align:left; " ><input type='text' style="width:100%;" name='Chronic_disease' id='Chronic_disease'></td>
            </tr>
            </table>
            <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
     B:  TUMOUR
    </div>
       <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
            <tr>
                <td style="width:10%;text-align:right "><b>Date of Incidence:</b></td><td colspan="" width="20%"><input type='text' style="width:100%;" name='Date_of_Incidence' id='Date_of_Incidence' class="date" placeholder="Date"></td>
                <td style="width:10%;text-align:right "><b>Onset of symptoms date:</b></td><td colspan="" ><input type='text' style="width:100%;" name='symptoms_date' id='symptoms_date' class="date" placeholder="Date"></td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right " ><b>Basis of Diagnosis:</b></td>
                <td colspan="2">Histology<input type='checkbox' name='Histology' id='Histology' value='Histology'>Clinical Only<input type='checkbox' name='Clinical_Only' id='Clinical_Only' value='Clinical_Only'>Cytology/Hematology<input type='checkbox' name='Cytology_Hematology' id='Cytology_Hematology' value='Cytology_Hematology'>FNAC<input type='checkbox' name='FNAC' id='FNAC' value='FNAC'>Clinical-Investigation(x-ray/CTscan etc)<input type='checkbox' name='Clinical_Investigation' id='Clinical_Investigation' value='Clinical_Investigation'><div align="right"><b>Other:</b></div></td>
               
               <td style="width:10%;text-align:right " ><input type='text' style="width:100%;" name='Other' id='Other'></td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right " ><b>For breast cancer:</b></td>
                <td colspan="3"><b>ER Status</b>0.Negative<input type='checkbox' name='ER_Status_Negative' id='ER_Status_Negative' value='Negative'>1.Positive<input type='checkbox' name='ER_Status_Positive' id='ER_Status_Positive' value='Positive'><b>PR Status</b>0.Negative<input type='checkbox' name='PR_Status_Negative' id='PR_Status_Negative' value='Negative'>1.Positive<input type='checkbox' name='PR_Status_Positive' id='PR_Status_Positive' value='Positive'><b>Her Status</b>0.Negative<input type='checkbox' name='Her_Status_Negative' id='incidence' value='Negative'>1.Positive<input type='checkbox' name='Her_Status_Positive' id='incidence' value='Positive'></td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right "><b>Primary site/Diagnosis Code</b></td><td  width="30%"><input type='text' style="width:100%;" name='Primary_site' id='Primary_site'></td>
                <td style="width:10%;text-align:right "><b>[C80.9 if unknown origin site] Secondary Site/Diagnosis code:</b></td><td colspan=""><input type='text' style="width:100%;" name='Secondary_Site' id='Secondary_Site'></td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right "><b>Morphology</b></td><td colspan=""><input type='text' style="width:100%;" name='Morphology' id='Morphology'></td>
                <td style="width:10%;text-align:right "><b>M-code:</b></td><td colspan=""><input type='text' style="width:100%;" name='M_code' id='M_code'>(8000/3 if morphology unspecified)</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right "><b>Stage:T:</b></td><td colspan=""><input type='text'  name='Stage_T' id='Stage_T'></td>
                <td colspan=""><b>N:</b><input type='text'  name='Stage_N' id='Stage_N'><b>Gleason score:</b><input type='text'  name='Gleason_score' id='Gleason_score'></td>
                <td colspan="">M:<input type='text'  name='Stage_M' id='Stage_M'><b>Baseline PSA:</b><input type='text'  name='Baseline_PSA' id='Baseline_PSA'></td>
<!--                <td><b>Gleasson score:</b></td><td colspan=""><input type='text'  name='incidence' id='incidence'></td>
                <td><b>Baseline PSA:</b></td><td colspan=""><input type='text'  name='incidence' id='incidence'></td>-->
            </tr>
            <table>
                     <tr>
                <td style="width:10%;text-align:right " ><b>Risk classification:</b></td>
                <td colspan="2" width="40%">Metastasis:1.Yes<input type='radio' name='iMetastasis_Yes' id='Metastasis_Yes' value='Yes'>2.No<input type='radio' name='Metastasis_No' id='Metastasis_No' value='No'>Non-metastasis:1.Low risk<input type='checkbox' name='Non_metastasis_Low_risk' id='Non_metastasis_Low_risk' value='Low risk'>2.Intermediate risk<input type='checkbox' name='Non_metastasis_Intermediate_risk' id='Non_metastasis_Intermediate_risk' value='Intermediate_risk'>3.High risk<input type='checkbox' name='Non_metastasis_High_risk' id='Non_metastasis_High_risk' value='High risk'><div align="right"><b>Other Staging:</b></div></td>
               
               <td style="width:10%;text-align:right " ><input type='text' style="width:100%;" name='Other_Staging' id='Other_Staging'></td>
            </tr>
            </table>
           
        </table>
                     <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
     C:  TREATMENT
    </div>
          <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px"> 
               <td style="width:10%;text-align:right " ><b>Intuition:</b></td>
                <td colspan="">Curative<input type='checkbox' name='Curative' id='Curative' value='Curative'>Palliative<input type='checkbox' name='Palliative' id='Palliative' value='Palliative'>Unknown<input type='checkbox' name='Unknown_value' id='Unknown_value' value='Unknown'></td>
          </table>
    <div style="width:96%;font-size:larger;border:1px solid #000; margin-left:20px;  background:#ccc;" id="outpatient">
     TREATMENT
    </div>
           <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px"> 
               <tr>
               <td colspan=""><b>Surgery </b>1:<input type='checkbox' name='Surgery_Yes' id='Surgery_Yes' value='Yes'>Yes<input type='checkbox' name='Surgery_No' id='Surgery_No' value='No'>No   <b>Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:50%;display:inline' class="date" placeholder="Date" name='Surgery_Date' id='Surgery_Date'/></td>
               <td colspan=""><b>Radiotherapy </b>1:<input type='checkbox' name='Radiotherapy_Yes' id='Radiotherapy_Yes' value='Yes'>Yes<input type='checkbox' name='Radiotherapy_No' id='Radiotherapy_No' value='No'>No   <b>Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:45%;display:inline' class="date" placeholder="Date" name='Radiotherapy_Date' id='Radiotherapy_Date'/></td>
               <td colspan=""><b>Immunotherapy </b>1:<input type='checkbox' name='Immunotherapy_Yes' id='Immunotherapy_Yes' value='Yes'>Yes<input type='checkbox' name='Immunotherapy_No' id='Immunotherapy_No' value='No'>No   <b>Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:45%;display:inline' class="date" placeholder="Date" name='Immunotherapy_Date' id='Immunotherapy_Date'/></td>
               <!--<b>Date:</b><input type='text' name='incidence' id='incidence'>-->
               </tr>
               <tr>
               <td colspan=""><b>Targeted therapy </b>1:<input type='checkbox' name='Targeted_therapy_Yes' id='Targeted_therapy_Yes' value='Yes'>Yes<input type='checkbox' name='Targeted_therapy_No' id='Targeted_therapy_No' value='No'>No   <b>Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:40%;display:inline' class="date" placeholder="Date" name='Targeted_therapy_Date' id='Targeted_therapy_Date'/></td>
               <td colspan=""><b>Chemotherapy </b>1:<input type='checkbox' name='Chemotherapy_Yes' id='Chemotherapy_Yes' value='Yes'>Yes<input type='checkbox' name='Chemotherapy_No' id='Chemotherapy_No' value='No'>No   <b>Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:45%;display:inline' class="date" placeholder="Date" name='Chemotherapy_Date' id='Chemotherapy_Date'/></td>
               <td colspan=""><b>Hormone therapy</b>1:<input type='checkbox' name='Hormone_therapy_Yes' id='Hormone_therapy_Yes' value='Yes'>Yes<input type='checkbox' name='Hormone_therapy_No' id='Hormone_therapy_No' value='No'>No   <b>Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:45%;display:inline' class="date" placeholder="Date" name='Hormone_therapy_Date' id='Hormone_therapy_Date'/></td>
            
               <!--<b>Date:</b><input type='text' name='incidence' id='incidence'>-->
               </tr>
               <table>
               <tr>
                  <td style="width:10%;text-align:right" ><b>Chemo:</b></td>
                <td colspan="2" width="30%">Adjuvant<input type='checkbox' name='Adjuvant' id='Adjuvant' value='Adjuvant'>neoadjuvant<input type='checkbox' name='Adjuvant' id='neoadjuvant' value='Adjuvant'>chemo-radiation<input type='checkbox' name='chemo_radiation' id='chemo_radiation' value='chemo radiation'>Palliative-chemotherapy<input type='checkbox' name='Palliative_chemotherapy' id='Palliative_chemotherapy' value='Palliative chemotherapy'></td>
                <td style="width:10%;text-align:right " ><b>Other chemo:</b></td>
               <td style="width:10%;text-align:right " ><input type='text' style="width:100%;" name='Other_chemo' id='Other_chemo'></td>
               </tr>
                   
           </table>
           </table>
             <div style="width:96%;font-size:larger;border:1px solid #000; margin-left:20px;  background:#ccc;" id="outpatient">
     Therapy regimen:
    </div>
           <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                 <tr>
                <td style="width:10%;text-align:right "><b>A:  Treatment 1st Line:</b></td></td>
                <td style="width:10%;text-align:right "><b>Regimen:</b></td><td colspan="" ><input type='text' style="width:70%;" name='A_Regimen' id='A_Regimen'></td>
                <td colspan=""><b> Start Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:50%;display:inline' class="date" placeholder="Date" name='A_Regimen_Start_Date' id='A_Regimen_Start_Date'/></td>
                <td colspan=""><b> End Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:50%;display:inline' class="date" placeholder="Date" name='A_Regimen_End_Date' id='A_Regimen_End_Date'/></td>
            </tr>
               <table>
                     <tr>
                <td style="width:4%;text-align:right " ><b></b></td>
                <td colspan="2" width="30%">Complete remission<input type='checkbox' name='A_Complete_remission' id='A_Complete_remission' value='Complete remission'>Partial remission<input type='checkbox' name='A_Partial_remission' id='A_Partial_remission' value='Partial remission'>Stable disease<input type='checkbox' name='A_Stable_disease' id='A_Stable_disease' value='Stable disease'>Progression<input type='checkbox' name='A_Progression' id='A_Progression' value='Progression'><div align="right"><b>Other Condition:</b></div></td>
               
               <td style="width:10%;text-align:right " ><input type='text' style="width:100%;" name='A_Other_Condition' id='A_Other_Condition'></td>
            </tr>
            <table>
                 <tr>
                <td style="width:10%;text-align:right "><b>B:   Treatment 2st Line:</b></td></td>
                <td style="width:10%;text-align:right "><b>Regimen:</b></td><td colspan="" ><input type='text' style="width:70%;" name='B_Regimen' id='B_Regimen'></td>
                <td colspan=""><b> Start Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:50%;display:inline' class="date" placeholder="Date" name='B_Regimen_Start_Date' id='B_Regimen_Start_Date'/></td>
                <td colspan=""><b> End Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:50%;display:inline' class="date" placeholder="Date" name='B_Regimen_End_Date' id='B_Regimen_End_Date'/></td>
            </tr>
               <table>
                     <tr>
                <td style="width:4%;text-align:right " ><b></b></td>
                <td colspan="2" width="30%">Complete remission<input type='checkbox' name='B_Complete_remission' id='B_Complete_remission' value='Complete remission'>Partial remission<input type='checkbox' name='B_Partial_remission' id='B_Partial_remission' value='Partial remission'>Stable disease<input type='checkbox' name='B_Stable_disease' id='B_Stable_disease' value='Stable disease'>Progression<input type='checkbox' name='B_Progression' id='B_Progression' value='Progression'><div align="right"><b>Other Condition:</b></div></td>
               
               <td style="width:10%;text-align:right " ><input type='text' style="width:100%;" name='B_Other_Condition' id='B_Other_Condition'></td>
            </tr>
                   <tr>
                <td style="width:10%;text-align:right " ><b>C: Any other result:</b></td>
                <td colspan="3"><b>Treatment completed</b><input type='checkbox' name='Treatment_completed' id='Treatment_completed' value='Treatment completed'><b>Lost before treatment</b><input type='checkbox' name='Lost_before_treatment' id='Lost_before_treatment' value='Lost before treatment'><b>Lost during treatment</b><input type='checkbox' name='Lost_during_treatment' id='Lost_during_treatment' value='Lost during treatment'><b>Referred</b><input type='checkbox' name='Referred' id='Referred' value='Referred'>PCT<input type='checkbox' name='PCT' id='PCT' value='PCT'><b>Treatment ongoing</b><input type='checkbox' name='Treatment_ongoing' id='Treatment_ongoing' value='Treatment ongoing'></td>
            </tr>
                   <tr>
                <td style="width:10%;text-align:right " ><b>General progress of the disease:</b></td>
                <td colspan="3"><b>Cured</b><input type='checkbox' name='Cured' id='Cured' value='Cured'><b>Recurred</b><input type='checkbox' name='Recurred' id='Recurred' value='Recurred'><b>Unknown</b><input type='checkbox' name='Unknown2' id='Unknown2' value='Unknown'></td>
            </tr>
            </table>
           
               </table>
         
           </table> 
                   <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
     D:  SOURCE OF INFORMATION
    </div>
            <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                      <tr>
                <!--<td style="width:10%;text-align:right "><b>A:  Treatment 1st Line:</b></td></td>-->
                <td style="width:10%;text-align:right "><b>Institution:</b></td><td colspan="" ><input type='text' style="width:90%;" name='Institution' id='Institution'></td>
                <td colspan=""><b> Ward/Unit:</b> <input type="text" autocomplete="off" style='width:60%;display:inline' name='Ward_Unit' id='Ward_Unit' /></td>
                <td colspan=""><b> Lab number:</b> <input type="text" autocomplete="off" style='width:60%;display:inline' name='Lab_number' id='Lab_number'/></td>
            </tr>
                
            </table>
                           <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
     E:  ADVERSE EVENTS AND SUPPORTIVE CARE
    </div>
            <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                 <td colspan=""><b> Adverse events or complications after initiation of treatments:</b> <input type="text" autocomplete="off" style='width:40%;display:inline' name='Adverse_events' id='Adverse_events' /></td>
                <td colspan=""><b> supportive care on pain medication, anti-diarrhea or anti-nausea:</b> <input type="text" autocomplete="off" style='width:39%;display:inline' name='supportive care' id='supportive care'/></td>
            </table>
                                    <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
     F:  FOLLOWUP (Registrar)
    </div>
            <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                      
                 <tr>
                <td style="width:10%;text-align:right "><b>Total cycles received/administered:</b></td></td>
                <td style="width:10%;text-align:right "><b>1st Line:</b></td><td colspan="" ><input type='text' style="width:70%;" name='one_Line' id='one_Line'>cycles</td>
                <td colspan=""><b> 2nd Line:</b> <input type="text" autocomplete="off" style='width:50%;display:inline' name='two_Line' id='two_Line'/>cycles</td>
                <td colspan=""><b> 3rd Line:</b> <input type="text" autocomplete="off" style='width:50%;display:inline' name='three_Line' id='three_Line' />cycles</td>
            </tr>
            <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                 <td colspan="" width="50%"><b> Date at last Contact:</b> <input type="text" autocomplete="off" style='text-align: center;width:40%;display:inline' name='Date_at_last_Contact' id='Date_at_last_Contact'/></td>
                <td colspan=""><b> Status at last contact:</b> Alive<input type="checkbox" autocomplete="off" style='text-align: center;width:10%;display:inline' name='Alive' id='Alive' value='Alive'/>
              Dead<input type="checkbox" autocomplete="off" style='text-align: center;width:10%;display:inline' name='Dead' id='Dead' value='Dead'/>Unknown<input type="checkbox" autocomplete="off" style='text-align: center;width:10%;display:inline' name='Unknown_status' id='Unknown_status' value='Unknown_status'/></td>
            </table>
            <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
               
                <td colspan=""><b> Cause of death if known:</b> This cancer<input type="checkbox" autocomplete="off" style='text-align: center;width:10%;display:inline' name='This_cancer' id='This_cancer' value='This cancer'/>
              Trauma<input type="checkbox" autocomplete="off" style='text-align: center;width:10%;display:inline' name='Trauma' id='Trauma' value='Trauma'/>Unknown<input type="checkbox" autocomplete="off" style='width:10%;display:inline' name='Unknown_causes' id='Unknown_causes' value='Unknown_causes'/></td>
                  <td colspan="" width="50%"><b> Other:</b> <input type="text" autocomplete="off" style='width:40%;display:inline' name='Other_causes' id='Other_causes'/></td>
                
            </table>
            </table>
    </div>
                                    <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
                             <div align="right"> <button class="art-button-green button_pro" onclick='Save_Cancer_data()'>SAVE</button></div>
    </div>
    </fieldset>
 

<?php
    include("./includes/footer.php");
?>


<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#date').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
    });
    $('#date').datetimepicker({value: '', step: 1});
    $('#date_To').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
    });
    $('#date_To').datetimepicker({value: '', step: 1});
    
    
        function Save_Cancer_data() {
            var Registration_ID= <?php echo $Registration_ID; ?>;
            var  SP_on_ART=$('#SP_on_ART').val();
            var  SN=$('#SN').val();
            var  Unknown3=$('#Unknown3').val();
            var  SP_not_on_ART=$('#SP_not_on_ART').val();
            var  Date_diagnosis_HIV=$('#Date_diagnosis_HIV').val();
            var  Chronic_disease=$('#Chronic_disease').val();
            var  Date_of_Incidence=$('#Date_of_Incidence').val();
            var  symptoms_date=$('#symptoms_date').val();
            var  Histology=$('#Histology').val();
            var  Clinical_Only=$('#Clinical_Only').val();
            var  Cytology_Hematology=$('#Cytology_Hematology').val();
            var  FNAC=$('#FNAC').val();
            var  Clinical_Investigation=$('#Clinical_Investigation').val();
            var  Other=$('#Other').val();
            var  ER_Status_Negative=$('#ER_Status_Negative').val();
            var  ER_Status_Positive=$('#ER_Status_Positive').val();
            var  PR_Status_Negative=$('#PR_Status_Negative').val();
            var  PR_Status_Positive=$('#PR_Status_Positive').val();
            var  Her_Status_Negative=$('#Her_Status_Negative').val();
            var  Her_Status_Positive=$('#Her_Status_Positive').val();
            var  Primary_site=$('#Primary_site').val();
            var  Secondary_Site=$('#Secondary_Site').val();
            var  Morphology=$('#Morphology').val();
            var  M_code=$('#M_code').val();
            var  Stage_T=$('#Stage_T').val();
            var  Stage_N=$('#Stage_N').val();
            var  Stage_M=$('#Stage_M').val();
            var  Gleason_score=$('#Gleason_score').val();
            var  Baseline_PSA=$('#Baseline_PSA').val();
            var  Metastasis_Yes=$('#Metastasis_Yes').val();
            var  Metastasis_No=$('#Metastasis_No').val();
            var  Non_metastasis_Low_risk=$('#Non_metastasis_Low_risk').val();
            var  Non_metastasis_Intermediate_risk=$('#Non_metastasis_Intermediate_risk').val();
            var  Non_metastasis_High_risk=$('#Non_metastasis_High_risk').val();
            var  Other_Staging=$('#Other_Staging').val();
            var  A_Regimen=$('#A_Regimen').val();
            var  A_Regimen_Start_Date=$('#A_Regimen_Start_Date').val();
            var  A_Regimen_End_Date=$('#A_Regimen_End_Date').val();
            var  A_Complete_remission=$('#A_Complete_remission').val();
            var  A_Partial_remission=$('#A_Partial_remission').val();
            var  A_Stable_disease=$('#A_Stable_disease').val();
            var  A_Progression=$('#A_Progression').val();
            var  A_Other_Condition=$('#A_Other_Condition').val();
            var  B_Regimen=$('#B_Regimen').val();
            var  B_Regimen_Start_Date=$('#B_Regimen_Start_Date').val();
            var  B_Regimen_End_Date=$('#B_Regimen_End_Date').val();
            var  B_Complete_remission=$('#B_Complete_remission').val();
            var  B_Partial_remission=$('#B_Partial_remission').val();
            var  B_Stable_disease=$('#B_Stable_disease').val();
            var  B_Progression=$('#B_Progression').val();
            var  B_Other_Condition=$('#B_Other_Condition').val();
            var  Treatment_completed=$('#Treatment_completed').val();
            var  Lost_before_treatment=$('#Lost_before_treatment').val();
            var  Lost_during_treatment=$('#Lost_during_treatment').val();
            var Referred=$('#Referred').val();
            var  PCT=$('#PCT').val();
            var  Treatment_ongoing=$('#Treatment_ongoing').val();
            var  Cured=$('#Cured').val();
            var  Unknown2=$('#Unknown2').val();
            var  Institution=$('#Institution').val();
            var  Ward_Unit=$('#Ward_Unit').val();
            var  Lab_number=$('#Lab_number').val();
            var  Adverse_events=$('#Adverse_events').val();
            var  supportive_care=$('#supportive_care').val();
            var  one_Line=$('#one_Line').val();
            var  two_Line=$('#two_Line').val();
            var  three_Line=$('#three_Line').val();
            var  Date_at_last_Contact=$('#Date_at_last_Contact').val();
            var  Alive=$('#Alive').val();
            var  Dead=$('#Dead').val();
            var  Unknown_status=$('#Unknown_status').val();
            var  This_cancer=$('#This_cancer').val();
            var  Trauma=$('#Trauma').val();
            var  Unknown_causes=$('#Unknown_causes').val();
            var  Other_causes=$('#Other_causes').val();
            var  Curative=$('#Curative').val();
            var  Palliative=$('#Palliative').val();
            var  Unknown_value=$('#Unknown_value').val();
            var  Surgery_Yes=$('#Surgery_Yes').val();
            var  Surgery_No=$('#Surgery_No').val();
            var  Surgery_Date=$('#Surgery_Date').val();
            var  Radiotherapy_Yes=$('#Radiotherapy_Yes').val();
            var  Radiotherapy_No=$('#Radiotherapy_No').val();
            var  Radiotherapy_Date=$('#Radiotherapy_Date').val();
            var  Immunotherapy_Yes=$('#Immunotherapy_Yes').val();
            var  Immunotherapy_No=$('#Immunotherapy_No').val();
            var  Immunotherapy_Date=$('#Immunotherapy_Date').val();
            var  Targeted_therapy_Yes=$('#Targeted_therapy_Yes').val();
            var  Targeted_therapy_No=$('#Targeted_therapy_No').val();
            var  Targeted_therapy_Date=$('#Targeted_therapy_Date').val();
            var  Chemotherapy_Yes=$('#Chemotherapy_Yes').val();
            var  Chemotherapy_No=$('#Chemotherapy_No').val();
            var  Chemotherapy_Date=$('#Chemotherapy_Date').val();
            var  Hormone_therapy_Yes=$('#Hormone_therapy_Yes').val();
            var  Hormone_therapy_No=$('#Hormone_therapy_No').val();
            var  Hormone_therapy_Date=$('#Hormone_therapy_Date').val();
            var  Adjuvant=$('#Adjuvant').val();
            var  neoadjuvant=$('#neoadjuvant').val();
            var  chemo_radiation=$('#chemo_radiation').val();
            var  Palliative_chemotherapy=$('#Palliative_chemotherapy').val();
            var  Other_chemo=$('#Other_chemo').val();
         
           
            

    
        $.ajax({
            type: 'POST',
            url: "Insert_cancer_data.php",
            data: {Other_chemo:Other_chemo,Palliative_chemotherapy:Palliative_chemotherapy,chemo_radiation:chemo_radiation,neoadjuvant:neoadjuvant,Adjuvant:Adjuvant,Hormone_therapy_Date:Hormone_therapy_Date,Hormone_therapy_No:Hormone_therapy_No,Hormone_therapy_Yes:Hormone_therapy_Yes,Chemotherapy_Date:Chemotherapy_Date,Chemotherapy_No:Chemotherapy_No,Chemotherapy_Yes:Chemotherapy_Yes,Targeted_therapy_Date:Targeted_therapy_Date,
            Targeted_therapy_No:Targeted_therapy_No,Targeted_therapy_Yes:Targeted_therapy_Yes,Immunotherapy_Date:Immunotherapy_Date,Immunotherapy_No:Immunotherapy_No,
             Immunotherapy_Yes:Immunotherapy_Yes,Radiotherapy_Date:Radiotherapy_Date,Radiotherapy_No:Radiotherapy_No,Radiotherapy_Yes:Radiotherapy_Yes,Surgery_Date:Surgery_Date,
            Surgery_No:Surgery_No,Surgery_Yes:Surgery_Yes,Unknown_value:Unknown_value,Palliative:Palliative,Curative:Curative,Other_causes:Other_causes,Unknown_causes:Unknown_causes,
            Trauma:Trauma,This_cancer:This_cancer,Unknown_status:Unknown_status,Dead:Dead,Alive:Alive,Date_at_last_Contact:Date_at_last_Contact,
            three_Line:three_Line,one_Line:one_Line,two_Line:two_Line,supportive_care:supportive_care,Adverse_events:Adverse_events,Lab_number:Lab_number,Ward_Unit:Ward_Unit,
           Institution:Institution,Cured:Cured,Treatment_ongoing:Treatment_ongoing,PCT:PCT,Referred:Referred,Lost_during_treatment:Lost_during_treatment,
           Lost_before_treatment:Lost_before_treatment,Treatment_completed: Treatment_completed,B_Other_Condition:B_Other_Condition,B_Progression:B_Progression,B_Stable_disease:B_Stable_disease,
           B_Partial_remission:B_Partial_remission,B_Complete_remission:B_Complete_remission,B_Regimen_End_Date:B_Regimen_End_Date,
           B_Regimen_Start_Date:B_Regimen_Start_Date,B_Regimen:B_Regimen,A_Other_Condition:A_Other_Condition,A_Progression:A_Progression,A_Stable_disease:A_Stable_disease,
           A_Partial_remission:A_Partial_remission,A_Complete_remission:A_Complete_remission,A_Regimen_End_Date:A_Regimen_End_Date,
           A_Regimen_Start_Date:A_Regimen_Start_Date,A_Regimen:A_Regimen,Other_Staging:Other_Staging,Non_metastasis_High_risk:Non_metastasis_High_risk,Non_metastasis_Intermediate_risk:Non_metastasis_Intermediate_risk,
           Non_metastasis_Low_risk:Non_metastasis_Low_risk,Metastasis_No:Metastasis_No,Metastasis_Yes:Metastasis_Yes,Baseline_PSA:Baseline_PSA,Gleason_score:Gleason_score,Stage_M:Stage_M,
          Stage_N:Stage_N,Stage_T:Stage_T,Morphology:Morphology,Secondary_Site:Secondary_Site,Primary_site:Primary_site,Her_Status_Positive:Her_Status_Positive,Her_Status_Negative:Her_Status_Negative,
          PR_Status_Positive:PR_Status_Positive,PR_Status_Negative:PR_Status_Negative,ER_Status_Positive:ER_Status_Positive,ER_Status_Negative:ER_Status_Negative,Other:Other,Clinical_Investigation:Clinical_Investigation,
          FNAC:FNAC,Cytology_Hematology:Cytology_Hematology,Clinical_Only:Clinical_Only,Histology:Histology,symptoms_date:symptoms_date,Date_of_Incidence:Date_of_Incidence,Chronic_disease:Chronic_disease,Date_diagnosis_HIV:Date_diagnosis_HIV,
          SP_not_on_ART:SP_not_on_ART,Unknown3:Unknown3,SN:SN,SP_on_ART:SP_on_ART,Unknown2:Unknown2,Registration_ID:Registration_ID,M_code:M_code},
            success: function (html) {
                alert(html);
                alert("data saved sussfully");
               
            }, error: function (jqXHR, textStatus, errorThrown) {
                alert(textStatus);
            }
        });

    }
</script>