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
    <center><legend align="center"><b>MOBILITY ASSESSMENT FORM</b><br><?php echo $Patient_Name ?><br><?php echo $Gender ?><br><?php echo $Guarantor_Name ?><br><?php echo $Registration_ID ?></legend></center>
                <div class="row" style="background-color: white; margin:0% 0% 0% 5%;width:95%">
                <div class="col-md-2"></div>
            <div class="box box-primary">
                <div class="box-header">
                    <center> <h4>MOBILITY ASSESSMENT</h4></center>  
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
                                     <input type="checkbox" id="consent_treatement" value="yes"> 
                                     <p>Patient has capacity therefore consent received for assessment and discussed treatment</p>
                                     </div>
                                     <div class="form-inline">
                                    <input type="checkbox" id="consent_interest" value="yes">
                                    <p>Patient does not has capacity therefore acted in patients best interests</p>
                                    </div>
                                   
                                    
                        </td>
                        </tr>
                        <tr>
                                <td width="13%">
                                    <b>Medical Dignosis/<br>
                                        Medical Treatment Plan
                                        
                                    </b>  
                  
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="treatement_plan">
                                
                           

                        </td>
                        </tr>
                        <tr>
                                <td width="13%">
                                        <b>Patient History/<br>
                                        Subject Assessment/<br>
                                        Social History
                                    </b>  
                  
                        </td>
                                <td width="30%">
                                   
                                    <p>(HPC,PMH,DH,SH,carers,family help,stairs,care manager,walking aids)</p>
                                    <div class="form-inline">
                                        <input type="checkbox" name="consent" id="history_assessment" value="yes">
                                    <p>Note has been taken of medical clerking dated</p>
                                    </div>
                                    
                                    

                        </td>
                        </tr>
                        <tr>
                                <td width="13%">
                                    <b>Objective Assessment</b>  
                  
                        </td>
                                <td width="30%" height="10">
                                   
                                       <input class="form-control"  name="story" rows="2" cols="33" id="objective_assessment">
                                
                                       

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
                                <input type="checkbox" id="consent_cognition"  value="Infact cognition">
                                <b>Infact</b>
                            </td>
                            <td>
                                <input type="checkbox" id="consent_speech" value="infact speech">
                               <b>Infact</b>
                            </td>
                            <td>
                                <input type="checkbox" id="consent_vision" value="infact vision">
                               <b>Infact</b>
                            </td>
                            <td>
                                <input type="checkbox" id="consent_hearing" value="infact hearing">
                                <b>Infact</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" id="consent_cognition"  value="Impared cognition">
                                <b>Impared</b><br>
                                Acute<br>
                                Chronic<br>
                                Both.
                            </td>
                            <td>
                                <input type="checkbox" id="consent_speech" value="Impared speech">
                               <b>Impared</b><br>
                                 Dysphasia<br>
                                Dysarthria<br>
                                Other.
                            </td>
                            </td>
                            <td>
                                <input type="checkbox" id="consent_vision" value="Impared vision">
                                <b>Impared</b><br>
                                  Glasses<br>
                                  Blind<br>
                                  Hemianopia or other.
                            </td>
                            <td>
                                <input type="checkbox" id="consent_hearing" value="Impared hearing">
                                <b>Impared</b><br>
                                  Hearing aid<br>
                                  In use<br>
                                  Bilateral.
                            </td>
                        </tr>
                       
                        <tr >
                            <td colspan="4">
                                   <div class="form-inline">
                                        <input type="checkbox" name="consent" id="medical_clerking" value="yes">
                                    <p>Note has been taken of medical clerking dated</p>
                                    </div>
                            </td>
                            
                        </tr>
                             <tr>
                                <td colspan="2">
                                    <b>Patient perception/<br>
                                        Patient expectation<br>
                                        Subjective markers
                                        
                                    </b>  
                  
                        </td>
                                <td colspan="3">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="patient_perception">
                                
                            

                        </td>
                        </tr>
                             <tr>
                                <td colspan="2">
                                    <b>Respiratory Screening<br>      
                                    </b>  
                  
                        </td>
                                <td colspan="3">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="respiratory_screening">
                                
                            

                        </td>
                        </tr>
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
                                  Functional<input type="checkbox" name="consent" id="upper_functional" value="functional"><br>
                                  Impaired<input type="checkbox" name="consent" id="upper_impaired" value="Impared"><br>
                                
                  
                        </td>
                                <td colspan="2">
                                    <input class="form-control" name="story" rows="2" cols="33" id="Defomity_text">
                                
                            

                        </td>
                                <td>
                                         <input class="form-control"  name="story" rows="2" cols="33" id="power">
                                
                            

                        </td>
                        </tr>
                             <tr>
                                <td>
                                    
                                <b>Lower limbs</b><br>
                                  Functional<input type="checkbox" name="consent" id="lower_functional" value="Functional"><br>
                                  Impaired<input type="checkbox" name="consent" id="lower_impaired" value="Impared"><br>
                                
                  
                        </td>
                                <td colspan="2">
                                    <input class="form-control"  name="story" rows="2" cols="33" id="Defomity_text2">
                                
                            

                        </td>
                                <td>
                                         <input class="form-control"  name="story" rows="2" cols="33"  id="power2">
                                
                            

                        </td>
                        </tr>
                             <tr>
                                <td>
                                    
                                <b>Neck limbs</b><br>
                                  Functional<input type="checkbox" name="consent" id="neck_functional" value="Functional"><br>
                                  Impaired<input type="checkbox" name="consent" id="neck_impared" value="Impared"><br>
                                  Dizziness<input type="checkbox" name="consent" id="neck_dizziness" value="Dizziness"><br>
                                
                  
                        </td>
                                <td colspan="2">

                                    
                                <b>Trunk limbs</b><br>
                                  Functional<input type="checkbox" name="consent" id="trunk_funcional" value="Functional"><br>
                                  Impaired<input type="checkbox" name="consent" id="trunk_impared" value="Impared"><br>
                                  Kyphosis<input type="checkbox" name="consent" id="trunk_kyphosis" value="Kyphosis"><br>
                   
                        </td>
                                <td>
                                    <b>Feet footwear</b><br>
                                         <input class="form-control"  name="story" rows="2" cols="33" id="feet_footwear">
                                
                            

                        </td>
                        </tr>
                                 <tr>
                                <td colspan="2">
                                    <b>Plan/Analgesia<br>      
                                    </b>  
                  
                        </td>
                                <td colspan="3">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="plan_analgesia">
                                
                            

                        </td>
                        </tr>
                               <tr>
                                <td>
                                    
                  
                        </td>
                                <td colspan="2">
                               <b> U/L  </b> 

                        </td>
                                <td>
                                    <b>L/L</b>

                        </td>
                        </tr>
                               <tr>
                                <td>
                                    Sensation /<br>
                                    proprioception<br>
                                    Coordination
                  
                        </td>
                                <td colspan="2">
                                       <input class="form-control"  name="story" rows="2" cols="33" id="ul">
                                
                            

                        </td>
                                <td>
                                             <input class="form-control"  name="story" rows="2" cols="33" id="ll">
                                
                            

                        </td>
                        </tr>
                        
                                       <tr>
                                <td colspan="2">
                                    <b>Function,gait,balance,transfers,stairs<br>
                                        (include normal levels and current deficits)
                                    </b>  
                  
                        </td>
                                <td colspan="3">
                                   
                                    POMA<input type="checkbox" name="consent" id="poma" value="POMA">EMS<input type="checkbox" name="consent" id="ems" value="EMS">OTHERS<input type="checkbox" name="consent" id="others" value="others">
                                    

                        </td>
                        </tr>
                                 <tr>
                                <td>
                                    
                                    <b>Problems</b> 
                        </td>
                                <td colspan="2">
                               <b> Treatment Plan Goals </b> 

                        </td>
                                <td>
                                    <b> TImescale</b>

                        </td>
                        </tr>
                                 <tr>
                                <td>
                                    
                           <input class="form-control"  name="story" rows="2" cols="33" id="problems">
                                
                            
                        </td>
                                <td colspan="2">
                            <input class="form-control" name="story" rows="2" cols="33" id="plan_goals">
                                
                            

                        </td>
                                <td>
                             <input class="form-control"  name="story" rows="2" cols="33" id="timescale">
                                
                            

                        </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <input type="button" id="consent_interest" class="btn btn-primary" value="save information" style="float:right" onclick="mobility_assessment_save()">
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

<script>
    
       function mobility_assessment_save(){
        
       var consent_treatement=$('#consent_treatement').val();
       var consent_interest=$('#consent_interest').val();
       var treatement_plan=$('#treatement_plan').val();
       var history_assessment=$('#history_assessment').val();
       var consent_cognition=$('#consent_cognition').val();
       var consent_speech =$('#consent_speech').val();
       var consent_vision=$('#consent_vision').val();
       var consent_hearing=$('#consent_hearing').val();
       var medical_clerking=$('#medical_clerking').val();
       var patient_perception=$('#patient_perception').val();
       var respiratory_screening=$('#respiratory_screening').val();
       var upper_functional=$('#upper_functional').val();
       var upper_impaired=$('#upper_impaired').val();
       var Defomity_text=$('#Defomity_text').val();
       var power=$('#power').val();
       var lower_functional=$('#lower_functional').val();
       var lower_impaired=$('#lower_impaired').val();
       var Defomity_text2=$('#Defomity_text2').val();
       var power2=$('#power2').val();
       var neck_functional=$('#neck_functional').val();
       var neck_impared=$('#neck_impared').val();
       var neck_dizziness=$('#neck_dizziness').val();
       var trunk_funcional=$('#trunk_funcional').val();
       var trunk_impared=$('#trunk_impared').val();
       var trunk_kyphosis=$('#trunk_kyphosis').val();
       var feet_footwear=$('#feet_footwear').val();
       var plan_analgesia=$('#plan_analgesia').val();
       var ul=$('#ul').val();
       var ll=$('#ll').val();
       var Registration_ID='<?= $Registration_ID ?>';
       

  
        $.ajax({
         type:'POST', 
         url:'save_mobility_assessment.php',
//         data:'action=viewAppointment&appointment='+appointment+'&fromDate='+fromDate+'&toDate='+toDate+'&from_procedure='+from_procedure+'&clinic='+clinic+'&doctor='+doctor,
         data:{consent_treatement:consent_treatement,ll:ll,ul:ul,plan_analgesia:plan_analgesia,feet_footwear:feet_footwear,trunk_kyphosis:trunk_kyphosis,trunk_impared:trunk_impared,trunk_funcional:trunk_funcional,neck_dizziness:neck_dizziness,neck_impared:neck_impared,
         neck_functional:neck_functional,power2:power2,Defomity_text2:Defomity_text2,lower_impaired:lower_impaired,lower_functional:lower_functional,power:power,Defomity_text:Defomity_text,upper_impaired:upper_impaired,upper_functional:upper_functional,
         respiratory_screening:respiratory_screening,patient_perception:patient_perception,medical_clerking:medical_clerking,consent_hearing:consent_hearing,consent_vision:consent_vision,consent_speech:consent_speech,
         consent_cognition:consent_cognition,history_assessment:history_assessment,treatement_plan:treatement_plan,consent_interest:consent_interest,Registration_ID:Registration_ID},
         cache:false,
         success:function(html){
             alert("Saved Successfully");
             //header("location: ../physiotherapy_patient_list.php?Registration_ID=&Patient_Payment_ID=&Patient_Payment_Item_List_ID=&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=");
       
           }
     
         });
        
    }

</script>