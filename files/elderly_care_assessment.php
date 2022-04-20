<style>
    .otherdoclinks:hover{
        text-decoration:underline;
        color: #000000; 
        cursor:pointer; 
    }
    
     /* Style the form - display items horizontally */
.form-inline {
  display: flex;
  flex-flow: row wrap;
  align-items: center;
   padding: 0px 0px;
}

/*input class="form-control" {
    font-size: .8rem;
    letter-spacing: 1px;
    resize: none;
}
input class="form-control" {
    padding: 10px;
    line-height: 1.5;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-shadow: 1px 1px 1px #999;
}*/
</style>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link href="css/jquery-ui.css" rel="stylesheet" />
<script src="css/jquery.js"></script>
<script src="css/jquery-ui.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<!--<script src="media/js/jquery.js" type="text/javascript"></script>-->
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
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
    

?>
<?php

   $Employee_ID = $_SESSION['userinfo']['Employee_ID'];

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


   $Select_name = mysqli_query($conn,"SELECT Patient_Name,Gender,Sponsor_ID FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'");
   
      $Patient_Name ="";
      $Gender="";
      $Guarantor_Name="";
    while($row = mysqli_fetch_assoc($Select_name)){
          $Patient_Name =$row['Patient_Name'];
          $Gender =$row['Gender'];
          $Sponsor_ID =$row['Sponsor_ID'];
          
          $Guarantor_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'"))['Guarantor_Name'];
        
        
    }
?>
  <a href='physiotherapy_page_treatment.php?Registration_ID=<?= $Registration_ID ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?= $from_consulted ?>' class='art-button-green'>
       BACK
    </a>
<br/>
<fieldset>  
    <center><legend align="center"><b>ELDERLY CARE FORM</b><br><?php echo $Patient_Name ?><br><?php echo $Gender ?><br><?php echo $Guarantor_Name ?><br><?php echo $Registration_ID ?></legend></center>
                <div class="row" style="background-color: white; margin:0% 0% 0% 5%;width:95%">
                <div class="col-md-2"></div>
            <div class="box box-primary">
                <div class="box-header">
                    <center> <h4>ELDERLY CARE ASSESSMENT</h4></center>  
                </div>
               <!--<input type="text" id='search_value' onkeyup="search_item()"placeholder="Search item" class="form-control" style="width:90%;"/></span></caption>-->
                <div class="box-body" >
                    <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%">
                        <tr>
                                <td >
                                    <b>Consent</b>  
                  
                        </td>
                                <td>
                                      <div class="form-inline">
                                     <input type="checkbox" name="consent" id="consent_treatment" value="yes"> 
                                     <p>Patient has capacity therefore consent received for assessment and discussed treatment</p>
                                     </div>
                                     <div class="form-inline">
                                    <input type="checkbox" name="consent" id="consent_interest" value="yes">
                                    <p>Patient does not has capacity therefore acted in patients best interests</p>
                                    </div>
                                   
                                    
                        </td>
                        </tr>
                        <tr>
                                <td width="13%">
                                    <b>Patient History</b>  
                  
                        </td>
                                <td width="30%" height="10">
                                      <b>(HPC,PMH,DH,SH, Precautions,Allergies, Contra indications)</b> 
                                      <b> Note has been taken of medical clerking dated</b> 
                                    
                                     &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="consent" id="patient_history" value="yes">
                                      

                        </td>
                        </tr>
                               <tr>
                                <td width="13%">
                                    <b> Patient Perception of Problems  <br>
                                        
                                        
                                    </b>  
                  
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="patient_problems">
                                
                            

                        </td>
                        </tr>
                        <tr >
                                <td width="13%" colspan="5">
                                    <b>Sputum Specimen sent</b> 
                                    
                                     &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="consent" id="specimen_yes" id="yes">
                                <b>Yes</b>
                                
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="specimen_no" value="No">
                                <b>No</b>
                                
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="speciment_na" value="NA">
                                <b>NA</b>
                  
                        </td>
                        </tr>
                        
                                 <tr>
                                <td width="13%">
                                    <b>Chest X-Ray<br>
                                        
                                        
                                    </b>  
                  
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="chest_x_ray">
                                
                            

                        </td>
                        </tr>
                                 <tr>
                                     <td width="13%">
                                    <b>Other Invesiigations:<br>
                                        
                                        
                                    </b>  
                  
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="other_investigation">
                                
                            

                        </td>
                        </tr>
                      
                    </table>
                        
                    <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%">
                              <tr>
                                <td width="13%">
                                    <b>Patient Perception</b> <br>
                                    <b>Patient Exceptation</b> <br>
                                    <b>Subjective Markers</b> <br>
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="patient_subject">
                                
                            

                        </td>
                        </tr>
                        
                        
                    </table>
           <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%">
                        <tr>
                            <td>
                                <b>Cognition</b>
                                
                            </td>
                            <td>
                                <b>Speech</b>  
                            </td>
                            <td>
                                <b> Vision</b>
                               
                            </td>
                            <td>
                                <b> Hearing</b>
                               
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="consent" id="Cognition_infact" value="Infact congnition">
                                <b>Infact</b>
                            </td>
                            <td>
                                <input type="checkbox" name="consent" id="Speech_infact" value="Infact Speech">
                               <b>Infact</b>
                            </td>
                            <td>
                                <input type="checkbox" name="consent" id="Vision_infact" value="Infact Vision">
                               <b>Infact</b>
                            </td>
                            <td>
                                <input type="checkbox" name="consent" id="Hearing_infact" value="Infact Hearing">
                                <b>Infact</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="consent" id="cognition_impared" value="Impared cognition">
                                <b>Impared</b><br>
                                Acute<br>
                                Chronic<br>
                                Both.
                            </td>
                            <td>
                                <input type="checkbox" name="consent" id="Speech_impared" value="Impared speech">
                               <b>Impared</b><br>
                                 Dysphasia<br>
                                Dysarthria<br>
                                Other.
                            </td>
                            </td>
                            <td>
                                <input type="checkbox" name="consent" id="Vision_impared" value="Impared Vision">
                                <b>Impared</b><br>
                                  Glasses<br>
                                  Blind<br>
                                  Hemianopia or other.
                            </td>
                            <td>
                                <input type="checkbox" name="consent" id="Hearing_impared" value="Impared Hearing">
                                <b>Impared</b><br>
                                  Hearing aid<br>
                                  In use<br>
                                  Bilateral.
                            </td>
                        </tr>
                    
                             <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%">
                              <tr>
                                <td width="13%">
                                    <b>Contraindications </b> <br>
                                    <b>precautions allergies</b> <br>
                                   
                        </td>
                                <td width="30%" colspan='2'>
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="contraindications">
                                
                            

                        </td>
                        </tr>
                              <tr>
                                <td width="13%">
                                    <b>Respiratory</b> <br>
                                 
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="respiratory">
                                
                            

                        </td>
                        </tr>
                              <tr>
                                <td width="13%">
                                    <b> Haemodynamics</b> <br>
                               
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="haemodynamics">
                                
                            

                        </td>
                        </tr>
                              <tr>
                                <td width="13%">
                                    <b> Relevant Clinical Investigations</b> <br>
                             
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="relevant_clinical">
                                
                            

                        </td>
                        </tr>
                              <tr>
                                <td width="13%">
                                    <b> Relevant Clinical Investigations</b> <br>
                             
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="relevant_clinical_2">
                                
                            

                        </td>
                        </tr>
                              <tr>
                                <td width="13%">
                                    <b> Relevant Clinical Investigations</b> <br>
                             
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="relevant_clinical_3">
                                
                            

                        </td>
                        </tr>

                        
                    </table>
                        
                        
                        <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%">
                            
                            
                    
                        
                           <tr>
                                <td>
                                    <b> M/skeletal screening </b> 
                  
                        </td>
                                <td colspan="2">
                               <b> Range of movement /Defomity  </b> 

                        </td>
                                <td>
                                    <b>Power</b>

                        </td>
                        </tr>
                             <tr>
                                <td>
                                    
                                <b>Upper limbs</b><br>
                                  Functional<input type="checkbox" name="consent" id="upper_functional" value="Functional"><br>
                                  Impaired<input type="checkbox" name="consent" id="upper_impaired" value="Impared"><br>
                                
                  
                        </td>
                                <td colspan="2">
                                    <input class="form-control"  name="story" rows="2" cols="33" id="defomity">
                                
                            

                        </td>
                                <td>
                                         <input class="form-control"  name="story" rows="2" cols="33" id="power">
                                
                            

                        </td>
                        </tr>
                             <tr>
                                <td>
                                    
                                <b>Lower limbs</b><br>
                                  Functional<input type="checkbox" name="consent" id="lower_functional" value="Functional"><br>
                                  Impaired<input type="checkbox" name="consent" id="lower_impared" value="Impared"><br>
                                
                  
                        </td>
                                <td colspan="2">
                                    <input class="form-control"  name="story" rows="2" cols="33" id="defomity_2">
                                
                            

                        </td>
                                <td>
                                         <input class="form-control"  name="story" rows="2" cols="33" id="power_2">
                                
                            

                        </td>
                        </tr>
                             <tr>
                                <td>
                                    
                                <b>Lower limbs</b><br>
                                  Functional<input type="checkbox" name="consent" id="lower_functional_2" value="Functional"><br>
                                  Impaired<input type="checkbox" name="consent" id="lower_impared_2" value="Impaired"><br>
                                  Dizziness<input type="checkbox" name="consent" id="lower_dizziness" value="Dizziness"><br>
                                
                  
                        </td>
                                <td colspan="2">

                                    
                                <b>Trunk limbs</b><br>
                                  Functional<input type="checkbox" name="consent" id="trunk_functional" value="Functional"><br>
                                  Impaired<input type="checkbox" name="consent" id="trunk_impared" value="Impaired"><br>
                                  Kyphosis<input type="checkbox" name="consent" id="trunk_kyphosis" value="Kyphosis"><br>
                   
                        </td>
                                <td>
                                    <b>Feet footwear</b><br>
                                         <input class="form-control"  name="story" rows="2" cols="33" id="feet_footwear">
                                
                            

                        </td>
                        </tr>
                            </table>
                         <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%">
                            
                            
                    
                        
                           <tr>
                                <td>
                                    <b> Pain</b> 
                  
                        </td>
                
                        </tr>
                             <tr>
                                <td>
                                    

                        </td>
                                <td colspan="2">
                                                    <b>U L</b>

                        </td>
                                <td>
                                    <b>U L</b>

                        </td>
                        </tr>
                             <tr>
                                <td>
                                    
                                <b>Sensation</b><br>
                        </td>
                                <td colspan="2">
                                    <input class="form-control"  name="story" rows="2" cols="33" id="sensation_u_l">
                                
                            

                        </td>
                                <td>
                                         <input class="form-control"  name="story" rows="2" cols="33" id="sensation_l_l">
                                
                            

                        </td>
                        </tr>
                             <tr>
                                <td>
                                    
                                <b>proprioception</b><br>
                        </td>
                                <td colspan="2">
                                    <input class="form-control"  name="story" rows="2" cols="33" id="proprioception_ul">
                                
                            

                        </td>
                                <td>
                                         <input class="form-control" id="story" name="story" rows="2" cols="33" id="proprioception_ll">
                                
                            

                        </td>
                        </tr>
                                <td>
                                    
                                <b>Co-ordination</b><br>
                        </td>
                                <td colspan="2">
                                    <input class="form-control"  name="story" rows="2" cols="33" id="cordination_ul">
                                
                            

                        </td>
                                <td>
                                         <input class="form-control"  name="story" rows="2" cols="33" id="cordination_ll">
                                
                            

                        </td>
                        </tr>
                           
                         </table>
                        
                        <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%">
                             <tr>
                                <td width="13%">
                                    <b> Transfers Stairs  <br>
                                        
                                        
                                    </b>  
                  
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="transfers_stairs">
                                
                            

                        </td>
                        </tr>
                             <tr>
                                <td width="13%">
                                    <b> Functional Analysis  <br>
                                        
                                        
                                    </b>  
                  
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="functional_analysis">
                                
                            

                        </td>
                        </tr>
                             <tr>
                                <td width="13%">
                                    <b> Gait Analysis  <br>
                                        
                                        
                                    </b>  
                  
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="gait_analysis">
                                
                            

                        </td>
                        </tr>
                             <tr>
                                <td width="13%">
                                    <b> Balance  <br>
                                        
                                        
                                    </b>  
                  
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="balance">
                                
                            

                        </td>
                        </tr>
                             <tr>
                                <td width="13%">
                                    <b> outcome objective Measure  <br>
                                        
                                        
                                    </b>  
                  
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="outcome_objective">
                                
                            

                        </td>
                        </tr>
                               <tr>
                            <td colspan="4">
                                <input type="button" id="consent_interest" class="btn btn-primary" value="save information" style="float:right" onclick="save_elderly_care_assessment()">
                            </td>
                            
                        </tr>
                        </table>
                        
                                
                        
                </div><br/>
           
            </div>
                
            </div
              
        
</fieldset>


    
<?php
    include("./includes/footer.php");
?>

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
<script>
    
    function save_elderly_care_assessment(){
        var consent_treatment = $('#consent_treatment').val();
        var consent_interest = $('#consent_interest').val();
        var patient_history = $('#patient_history').val();
        var patient_problems = $('#patient_problems').val();
        var specimen_yes = $('#specimen_yes').val();
        var specimen_no = $('#specimen_no').val();
        var specimen_na = $('#specimen_na').val();
        var chest_x_ray = $('#chest_x_ray').val();
        var other_investigation = $('#other_investigation').val();
        var patient_subject = $('#patient_subject').val();
        var Cognition_infact = $('#Cognition_infact').val();
        var Speech_infact = $('#Speech_infact').val();
        var Vision_infact = $('#Vision_infact').val();
        var Hearing_infact = $('#Hearing_infact').val();
        var cognition_impared = $('#cognition_impard').val();
        var Speech_impared = $('#Speech_impared').val();
        var Vision_impared = $('#Vision_impared').val();
        var Hearing_impared = $('#Hearing_impared').val();
        var contraindications = $('#contraindications').val();
        var respiratory = $('#respiratory').val();
        var haemodynamics = $('#haemodynamics').val();
        var relevant_clinical = $('#relevant_clinical').val();
        var relevant_clinical_2 = $('#relevant_clinical_2').val();
        var relevant_clinical_3 = $('#relevant_clinical_3').val();
        var upper_functional = $('#upper_functional').val();
        var upper_impaired = $('#upper_impaired').val();
        var lower_functional = $('#lower_functional').val();
        var lower_impaired = $('#lower_impaired').val();
        var lower_functional_2 = $('#lower_functional_2').val();
        var lower_impaired_2 = $('#lower_impaired_2').val();
        var defomity = $('#defomity').val();
        var power = $('#power').val();
        var defomity_2 = $('#defomity_2').val();
        var power_2 = $('#power_2').val();
        var lower_dizziness = $('#lower_dizziness').val();
        var trunk_functional = $('#trunk_functional').val();
        var trunk_impared = $('#trunk_impared').val();
        var trunk_kyphosis = $('#trunk_kyphosis').val();
        var feet_footwear = $('#feet_footwear').val();
        var sensation_u_l = $('#sensation_u_l').val();
        var sensation_l_l = $('#sensation_l_l').val();
        var proprioception_ul = $('#proprioception_ul').val();
        var proprioception_ll = $('#proprioception_ll').val();
        var cordination_ul = $('#cordination_ul').val();
        var cordination_ll = $('#cordination_ll').val();
        var transfers_stairs = $('#transfers_stairs').val();
        var functional_analysis = $('#functional_analysis').val();
        var gait_analysis = $('#gait_analysis').val();
        var balance = $('#balance').val();
        var outcome_objective = $('#outcome_objective').val();
        var Registration_ID = '<?= $Registration_ID; ?>';
        
        
            $.ajax({
         type:'POST', 
         url:'save_elderly_care_assessment.php',
//         data:'action=viewAppointment&appointment='+appointment+'&fromDate='+fromDate+'&toDate='+toDate+'&from_procedure='+from_procedure+'&clinic='+clinic+'&doctor='+doctor,
         data:{outcome_objective:outcome_objective,balance:balance,gait_analysis:gait_analysis,functional_analysis:functional_analysis,transfers_stairs:transfers_stairs,
             cordination_ll:cordination_ll,cordination_ul:cordination_ul,proprioception_ll:proprioception_ll,proprioception_ul:proprioception_ul,sensation_l_l:sensation_l_l,
            sensation_u_l:sensation_u_l,feet_footwear:feet_footwear,trunk_kyphosis:trunk_kyphosis,trunk_impared:trunk_impared,trunk_functional:trunk_functional,lower_dizziness:lower_dizziness,power_2:power_2,
           defomity_2:defomity_2,power:power,defomity:defomity,lower_impaired_2:lower_impaired_2,lower_functional_2:lower_functional_2,lower_impaired:lower_impaired,
          lower_functional:lower_functional,upper_impaired:upper_impaired,upper_functional:upper_functional,relevant_clinical_3:relevant_clinical_3,relevant_clinical_2:relevant_clinical_2,
           relevant_clinical:relevant_clinical,haemodynamics:haemodynamics,respiratory:respiratory,contraindications:contraindications,
           Hearing_impared:Hearing_impared,Vision_impared:Vision_impared,Speech_impared:Speech_impared,cognition_impared:cognition_impared,
           Hearing_infact:Hearing_infact,Vision_infact:Vision_infact,Speech_infact:Speech_infact,Cognition_infact:Cognition_infact,patient_subject:patient_subject,
           other_investigation:other_investigation,chest_x_ray:chest_x_ray,specimen_na:specimen_na,specimen_no:specimen_no,specimen_yes:specimen_yes,patient_problems:patient_problems,
            patient_history:patient_history,consent_interest:consent_interest,consent_treatment:consent_treatment,Registration_ID:Registration_ID},
         cache:false,
         success:function(html){
                    alert("saved successfully");
           }
     
         });
    }
//    $('#fromDate').datetimepicker({
//    dayOfWeekStart : 1,
//    lang:'en',
//    startDate:  'now'
//    });
//    $('#fromDate').datetimepicker({value:'',step:1});
//    
//    $('#toDate').datetimepicker({
//    dayOfWeekStart : 1,
//    lang:'en',
//    startDate:  'now'
//    });
//    $('#toDate').datetimepicker({value:'',step:1});
//    
//    $('#editappointdate').datetimepicker({
//    dayOfWeekStart : 1,
//    lang:'en',
//    startDate:  'now'
//    });
//    $('#editappointdate').datetimepicker({value:'',step:1});

</script>

