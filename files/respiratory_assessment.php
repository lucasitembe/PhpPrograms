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
    <center><legend align="center"><b>RESPIRATORY FORM</b><br><?php echo $Patient_Name ?><br><?php echo $Gender ?><br><?php echo $Guarantor_Name ?><br><?php echo $Registration_ID ?></legend></center>
                <div class="row" style="background-color: white; margin:0% 0% 0% 5%;width:95%">
                <div class="col-md-2"></div>
            <div class="box box-primary">
                <div class="box-header">
                    <center> <h4>RESPIRATORY ASSESSMENT</h4></center>  
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
                                     <input type="checkbox" name="consent" id="treatment_consent" value="yes"> 
                                     <p>Patient has capacity therefore consent received for assessment and discussed treatment</p>
                                     </div>
                                     <div class="form-inline">
                                    <input type="checkbox" name="consent" id="interests_consent" value="yes">
                                    <p>Patient does not has capacity therefore acted in patients best interests</p>
                                    </div>
                                   
                                    
                        </td>
                        </tr>
                        <tr>
                                <td width="13%">
                                    <b>medical diagnosis plan<br>
                                        
                                        
                                    </b>  
                  
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control" name="story" rows="2" cols="33" id="medical_diagnosis">
                                
                            

                        </td>
                        </tr>
                        <tr>
                                <td width="13%">
                                    <b>Patient History</b>  
                  
                        </td>
                                <td width="30%" height="10">
                                      <b>(HPC,PMH,DH,SH, Precautions,Allergies, Contra indications)</b> 
                                      <b> Note has been taken of medical clerking dated</b> 
                                    
                                     &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="consent" id="medical_clerking" value="yes">
                                      

                        </td>
                        </tr>
                               <tr>
                                <td width="13%">
                                    <b>Home O2 Nebulisers Inhalers<br>
                                        
                                        
                                    </b>  
                  
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control" name="story" rows="2" cols="33" id="inhalers_home">
                                
                            

                        </td>
                        </tr>
                        <tr >
                                <td width="13%" colspan="5">
                                    <b>Sputum Specimen sent</b> 
                                    
                                     &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="consent" id="specimen_yes" value="Yes">
                                <b>Yes</b>
                                
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="specimen_no" value="No">
                                <b>No</b>
                                
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="specimen_na" value="NA">
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
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="invesiigations">
                                
                            

                        </td>
                        </tr>
                      
                    </table>
                           <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%">
                        <tr>
                            <td>
                               A.B.G'S
                                <b>Infact</b>
                            </td>
                            <td>
                               
                                <b> PH</b><br>
                                7.35<br>
                                7.45<br>
                            </td>
                            <td>
                               
                                  <b> PCO2</b><br>
                                KPa<br>
                                4.5-6.0<br>
                            </td>
                            <td>
                              
                                <b> PO2</b><br>
                                KPa<br>
                                10.6<br>
                                13.6<br>
                            </td>
                       
                            <td>
                               
                                 <b> HCO3</b><br>
                                22-26<br>
                                mmls litre<br>
                               
                            </td>
                            <td>
                              
                               <b> BE</b><br>
                                0-+<br>
                                mmls litre<br>
                            </td>
                            </td>
                            <td>
                                
                                <b> SaO2</b><br>
                                94-98%<br>
                               
                            </td>
                            <td>
                              
                                 <b> %O</b><br>
                              
                            </td>
                            <td>
                              
                                 <b> Via</b><br>
                             
                            </td>
                        </tr>
                        <tr>
                            <td>
                                            <input type="text" id="fromDate" required="true" value="<?= $Start_Date ?>" name="fromDate" style="width: 200px">
                            </td>
                            <td>
                               
                                      <input class="form-control"  name="story" rows="2" cols="33" id="PH">
                                
                            
                            </td>
                            <td>
                               
                             <input class="form-control"  name="story" rows="2" cols="33" id="PCO2">
                                
                            
                            </td>
                            <td>
                              
                                    <input class="form-control"  name="story" rows="2" cols="33" id="PO2">
                                
                            
                            </td>
                       
                            <td>
                               
                                    <input class="form-control"  name="story" rows="2" cols="33" id="HCO3">
                                
                            
                               
                            </td>
                            <td>
                              
                                   <input class="form-control"  name="story" rows="2" cols="33" id="BE">
                                
                            
                            </td>
                            </td>
                            <td>
                                
                                   <input class="form-control"  name="story" rows="2" cols="33"  id="SaO2">
                                
                            
                               
                            </td>
                            <td>
                              
                             <input class="form-control"  name="story" rows="2" cols="33" id="O_percent">
                                
                            
                              
                            </td>
                            <td>
                              
                            <input class="form-control"  name="story" rows="2" cols="33" id="Via">
                                
                            
                             
                            </td>
                        </tr>
                        <tr>
                       <td>
                                            <input type="text" id="fromDate" required="true" value="<?= $Start_Date ?>" name="fromDate" style="width: 200px">
                            </td>
                            <td>
                               
                                      <input class="form-control"  name="story" rows="2" cols="33" id="PH_2">
                                
                            
                            </td>
                            <td>
                               
                             <input class="form-control"  name="story" rows="2" cols="33" id="PCO2_2">
                                
                            
                            </td>
                            <td>
                              
                                    <input class="form-control"  name="story" rows="2" cols="33" id="PO2_2">
                                
                            
                            </td>
                       
                            <td>
                               
                                    <input class="form-control"  name="story" rows="2" cols="33" id="HCO3_2">
                                
                            
                               
                            </td>
                            <td>
                              
                                   <input class="form-control"  name="story" rows="2" cols="33" id="BE_2">
                                
                            
                            </td>
                            </td>
                            <td>
                                
                                   <input class="form-control"  name="story" rows="2" cols="33"  id="SaO2_2">
                                
                            
                               
                            </td>
                            <td>
                              
                             <input class="form-control"  name="story" rows="2" cols="33" id="O_percent_2">
                                
                            
                              
                            </td>
                            <td>
                              
                            <input class="form-control"  name="story" rows="2" cols="33" id="Via_2">
                                
                            
                             
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
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="Subjective_Markers">
                                
                            

                        </td>
                        </tr>
                        
                        
                    </table>
                    <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%">
                        
                   
                    
                              <tr>
                            <td>
                                <b>Observation</b>
                            </td>
                            <td>
                               
                                <b> B.P</b><br>
                             
                            </td>
                            <td>
                               
                                  <b> H.R</b><br>
                            
                            </td>
                            <td>
                              
                                <b> Temp</b><br>
                           
                            </td>
                       
                            <td>
                               
                                 <b> SPO2</b><br>
                             
                               
                            </td>
                            <td>
                              
                               <b> FIO2</b><br>
                          
                            </td>
                            </td>
                            <td>
                                
                                <b> RR</b><br>
                              
                               
                            </td>
                         
                        </tr>
                        <tr>
                            <td>
                                            <input class="form-control"  name="story" rows="2" cols="33" id="Observation">
                                
                                     
                            </td>
                            <td>
                               
                                      <input class="form-control"  name="story" rows="2" cols="33" id="B_P">
                                
                            
                            </td>
                            <td>
                               
                             <input class="form-control"  name="story" rows="2" cols="33" id="H_R">
                                
                            
                            </td>
                            <td>
                              
                                    <input class="form-control"  name="story" rows="2" cols="33" id="Temp">
                                
                            
                            </td>
                       
                            <td>
                               
                                    <input class="form-control"  name="story" rows="2" cols="33" id="SPO2_new">
                                
                            
                               
                            </td>
                            <td>
                              
                                   <input class="form-control"  name="story" rows="2" cols="33" id="FIO2_new">
                                
                            
                            </td>
                            </td>
                            <td>
                                
                                   <input class="form-control"  name="story" rows="2" cols="33" id="RR_new">
                                
                            
                               
                            </td>
                       
                        </tr>
                    </table>
                    <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%">
                        
                   
                    
                              <tr>
                            <td>
                              
                            </td>
                            <td>
                               
                                <b> Yes</b><br>
                             
                            </td>
                            <td>
                               
                                  <b> No</b><br>
                            
                            </td>
                            <td>
                              
                                <b> Colour</b><br>
                           
                            </td>
                       
                            <td>
                               
                                 <b> Amount</b><br>
                             
                               
                            </td>
                            <td>
                              
                               <b> Type</b><br>
                          
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Cough</b>
                                
                                     
                            </td>
                            <td>
                               
                                      <input class="form-control"  name="story" rows="2" cols="33" id="yes_Cough">
                                
                            
                            </td>
                            <td>
                               
                             <input class="form-control"  name="story" rows="2" cols="33" id="No_Cough">
                                
                            
                            </td>
                            <td>
                              
                                    <input class="form-control"  name="story" rows="2" cols="33" id="color_Cough">
                                
                            
                            </td>
                       
                            <td>
                               
                                    <input class="form-control"  name="story" rows="2" cols="33" id="amount_Cough">
                                
                            
                               
                            </td>
                            <td>
                              
                                   <input class="form-control"  name="story" rows="2" cols="33" id="type_Cough">
                                
                            
                            </td>
                            </td>
                      
                       
                        </tr>
                        <tr>
                            <td>
                                <b>Productive</b>
                                
                                     
                            </td>
                        
                        <td>
                               
                                      <input class="form-control"  name="story" rows="2" cols="33" id="yes_Productive">
                                
                            
                            </td>
                            <td>
                               
                             <input class="form-control"  name="story" rows="2" cols="33" id="No_Productive">
                                
                            
                            </td>
                            <td>
                              
                                    <input class="form-control"  name="story" rows="2" cols="33" id="color_Productive">
                                
                            
                            </td>
                       
                            <td>
                               
                                    <input class="form-control"  name="story" rows="2" cols="33" id="amount_Productive">
                                
                            
                               
                            </td>
                            <td>
                              
                                   <input class="form-control" id="story" name="story" rows="2" cols="33" id="type_Productive">
                                
                            
                            </td>
                            </td>
                       
                        </tr>
                    </table>
                    
                             <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%">
                              <tr>
                                <td width="13%">
                                    <b>Objective</b> <br>
                                    <b>Auscultation</b> <br>
                                    <b>Palpation</b> <br>
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="Auscultation">
                                
                            

                        </td>
                        </tr>
                              <tr>
                                <td width="13%">
                                    <b>Problem List</b> <br>
                                 
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="Problem_List">
                                
                            

                        </td>
                        </tr>
                              <tr>
                                <td width="13%">
                                    <b>Treatment Plan Goals</b> <br>
                               
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="Treatment_Plan_Goals">
                                
                            

                        </td>
                        </tr>
                              <tr>
                                <td width="13%">
                                    <b>Timescale</b> <br>
                             
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33" id="Timescale">
                                
                            

                        </td>
                        </tr>
                           <tr>
                            <td colspan="4">
                                <input type="button" class="btn btn-primary" value="save information" style="float:right" onclick="respiratory_information_save()">
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
    
       function respiratory_information_save(){
             
               var treatment_consent = $('#treatment_consent').val();
               var interests_consent = $('#interests_consent').val();
               var medical_diagnosis = $('#medical_diagnosis').val();
               var medical_clerking = $('#medical_clerking').val();
               var inhalers_home = $('#inhalers_home').val();
               var specimen_no = $('#specimen_no').val();
               var specimen_yes = $('#specimen_yes').val();
               var specimen_na = $('#specimen_na').val();
               var chest_x_ray = $('#chest_x_ray').val();
               var invesiigations = $('#invesiigations').val();
               var fromDate = $('#fromDate').val();
               var PH = $('#PH').val();
               var PCO2 = $('#PCO2').val();
               var HCO3 = $('#HCO3').val();
               var BE = $('#BE').val();
               var SaO2 = $('#SaO2').val();
               var O_percent = $('#O_percent').val();
               var Via = $('#Via').val();
               var PH_2 = $('#PH_2').val();
               var PCO2_2 = $('#PCO2_2').val();
               var HCO3_2 = $('#HCO3_2').val();
               var BE_2 = $('#BE_2').val();
               var SaO2_2 = $('#SaO2_2').val();
               var O_percent_2 = $('#O_percent_2').val();
               var Via_2 = $('#Via_2').val();
               var Subjective_Markers = $('#Subjective_Markers').val();
               var Observation = $('#Observation').val();
               var B_P = $('#B_P').val();
               var H_R = $('#H_R').val();
               var Temp = $('#Temp').val();
               var SPO2_new = $('#SPO2_new').val();
               var FIO2_new = $('#FIO2_new').val();
               var RR_new = $('#RR_new').val();
               var yes_Cough = $('#yes_Cough').val();
               var No_Productive = $('#No_Productive').val();
               var color_Productive = $('#color_Productive').val();
               var amount_Productive = $('#amount_Productive').val();
               var type_Productive = $('#type_Productive').val();
               var Problem_List = $('#Problem_List').val();
               var Treatment_Plan_Goals = $('#Treatment_Plan_Goals').val();
               var Timescale = $('#Timescale').val();
               var Registration_ID = '<?= $Registration_ID ?>';
               
//                  alert("jojdoif");
         $.ajax({
         type:'POST', 
         url:'save_respiratory_assessment.php',
//         data:'action=viewAppointment&appointment='+appointment+'&fromDate='+fromDate+'&toDate='+toDate+'&from_procedure='+from_procedure+'&clinic='+clinic+'&doctor='+doctor,
         data:{Timescale:Timescale,Treatment_Plan_Goals:Treatment_Plan_Goals,Problem_List:Problem_List,type_Productive:type_Productive,amount_Productive:amount_Productive,color_Productive:color_Productive,No_Productive:No_Productive,
         yes_Cough:yes_Cough,RR_new:RR_new,FIO2_new:FIO2_new,SPO2_new:SPO2_new,Temp:Temp,H_R:H_R,B_P:B_P,Observation:Observation,Subjective_Markers:Subjective_Markers,Via_2:Via_2,O_percent_2:O_percent_2,SaO2_2:SaO2_2,BE_2:BE_2,
         HCO3_2:HCO3_2,PCO2_2:PCO2_2,PH_2:PH_2,Via:Via,O_percent:O_percent,SaO2:SaO2,BE:BE,HCO3:HCO3,PCO2:PCO2,PH:PH,fromDate:fromDate,invesiigations:invesiigations,chest_x_ray:chest_x_ray,specimen_na:specimen_na,specimen_yes:specimen_yes,specimen_no:specimen_no,
         inhalers_home:inhalers_home,medical_clerking:medical_clerking,medical_diagnosis:medical_diagnosis,interests_consent:interests_consent,treatment_consent:treatment_consent,Registration_ID:Registration_ID},
         cache:false,
         success:function(html){
             
             alert(html);
       
           }
     
         });
           
           
       }
//    $('#fromDate').datetimepicker({
//    dayOfWeekStart : 1,
//    lang:'en',:PH,
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

