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
    <center><legend align="center"><b>Knee Arthoscope Assessment Form</b><br><?php echo $Patient_Name ?><br><?php echo $Gender ?><br><?php echo $Guarantor_Name ?><br><?php echo $Registration_ID ?></legend></center>
                <div class="row" style="background-color: white; margin:0% 0% 0% 5%;width:95%">
                <div class="col-md-2"></div>
            <div class="box box-primary">
                <div class="box-header">
                    <center> <h4>Knee Arthoscope Assessment</h4></center>  
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
                                     <input type="checkbox" name="consent" id="treatment_id" value="yes"> 
                                     <p>Patient has capacity therefore consent received for assessment and discussed treatment</p>
                                     </div>
                                     <div class="form-inline">
                                    <input type="checkbox" name="consent" id="interests_id" value="yes">
                                    <p>Patient does not has capacity therefore acted in patients best interests</p>
                                    </div>
                                   
                                    
                        </td>
                        </tr>
                        <tr>
                                <td width="13%">
                                    <b>Operation Findings/<br>
                                        
                                        
                                    </b>  
                  
                        </td>
                                <td width="30%">
                                   
                                   <input class="form-control"  name="story" rows="2" cols="33"  id="operation_findings">
                                
                            

                        </td>
                        </tr>
                        <tr>
                                <td width="13%">
                                    <b>Post-operative instructions</b>  
                  
                        </td>
                                <td width="30%" height="10">
                                   
                                       <input class="form-control"  name="story" rows="2" cols="33" id="post_operative">
                                
                                       

                        </td>
                        </tr>
                        <tr>
                                <td width="13%">
                                    <b>PMH DH: as per clerking dated</b>  
                  
                        </td>
                                <td width="30%" height="10">
                                   
                                       <input class="form-control"  name="story" rows="2" cols="33" id="pmh_dh">
                                
                                       

                        </td>
                        </tr>
                        <tr >
                                <td width="13%" colspan="5">
                                    <b>Neurovascular ststus</b> 
                                    
                                     &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="consent" id="infact" value="Infact">
                                <b>Infact</b>
                                
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="altered" value="Altered">
                                <b>Altered</b>
                                
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="reason_causes" value="Reason Cause">
                                <b>Reason Cause</b>
                  
                        </td>
                        </tr>
                        <tr >
                                <td width="13%" colspan="5">
                                    <b>Observations:</b> 
                                    
                                     &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="consent" id="checked_stable" value="checked and stable">
                                <b>checked and stable</b>
                                
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="brace" value="Brace">
                                <b>Brace</b>
                                
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="raymed" value="Raymed">
                                <b>Raymed</b>
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="wool_crepe" value="Wool and crepe">
                                <b>Wool and crepe</b>
                  
                        </td>
                        </tr>
                        <tr >
                                <td width="13%" colspan="5">
                                    <b>Treatment Exercises:</b> <br>
                                    <b>Eplanation of surgery and post-operative instruction:</b> <br>
                                    <b>Advice re: AROM Ex ICE Elevation Passive knee ext as per arthroscopy leaflet PIL 418</b> <br>
                                    
                                     &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="consent" id="sq" value="SQ">
                                <b>SQ</b>
                                
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="sh" value="SH">
                                <b>SH</b>
                                
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="irq" value="IRQ">
                                <b>IRQ</b>
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="knee_flex" value="Knee flex">
                                <b>Knee flex</b>
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="slr" value="SLR">
                                <b>SLR</b>
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="any_lag" value="Any lag">
                                <b>Any lag</b>
                  
                        </td>
                        </tr>
                            <tr>
                                <td width="13%">
                                    <b>Addition comments</b>  
                  
                        </td>
                                <td width="30%" height="10">
                                   
                                       <input class="form-control"  name="story" rows="2" cols="33" id="addition_comments">
                                
                                       

                        </td>
                        </tr>
                        
                               <tr >
                                <td width="13%" colspan="5">
                                    <b>Gait Analysis Distance:</b> <br>
                                     &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="consent" id="mobile_aids" value="Mobile independently with no aids">
                                <b>Mobile independently with no aids</b>
                                
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="mobile_safety" value="Mobile safety with EC">
                                <b>Mobile safety with EC</b>
                                
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="sticks" value="Sticks">
                                <b>Sticks</b>
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="zimmer_frame" value="Zimmer frame">
                                <b>Zimmer frame</b>
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="relevant_supplied" value="Relevant leaflet supplied">
                                <b>Relevant leaflet supplied</b>
                              
                  
                        </td>
                        </tr>
                               <tr >
                                <td width="13%" colspan="5">
                                    <b>Stair Assessment:</b> <br>
                                    <b>Safe :</b> <br>
                                     &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="consent" id="yes" value="Yes">
                                <b>Yes</b>
                                
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="no" value="No">
                                <b>No</b>
                                
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="NA" value="NA">
                                <b>NA</b>
                             
                              
                  
                        </td>
                        </tr>
                               <tr >
                                <td width="13%" colspan="5">
                                 
                                    <b>Follow up:</b> <br>
                                     &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="consent" id="yes_follow" value="Yes">
                                <b>Yes</b>
                                
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="no_follow" value="No">
                                <b>No</b>
                                
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="na_follow" value="NA">
                                <b>NA</b>
                                    <b>Site:</b> <br>
                                     &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="consent" id="ugent" value="Ugent">
                                <b>Ugent</b>
                                
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="priority" value="Priority">
                                <b> Priority</b>
                                
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="routine" value="Routine">
                                 <b>Routine</b><br>
                                
                                         <b>Outcome discussed with patient:</b> 
                                     &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="consent" id="yes_routine" value=" Routine yes">
                                <b>Yes</b>
                                
                                 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="consent" id="no_routine" value="Routine NO">
                                <b>No</b>
                        </td>
                        </tr>
                            <tr>
                            <td colspan="4">
                                <input type="button" id="consent_interest" class="btn btn-primary" value="save information" style="float:right" onclick="knee_arthoscopy_assessment()">
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
   function knee_arthoscopy_assessment(){
         var treatment_id = $('#treatment_id').val();
         var interests_id = $('#interests_id').val();
         var operation_findings = $('#operation_findings').val();
         var pmh_dh = $('#pmh_dh').val();
         var infact = $('#infact').val();
         var altered = $('#altered').val();
         var reason_causes = $('#reason_causes').val();
         var checked_stable = $('#checked_stable').val();
         var brace = $('#brace').val();
         var raymed = $('#raymed').val();
         var wool_crepe = $('#wool_crepe').val();
         var sq = $('#sq').val();
         var irq = $('#irq').val();
         var knee_flex = $('#knee_flex').val();
         var slr = $('#slr').val();
         var any_lag = $('#any_lag').val();
         var addition_comments = $('#addition_comments').val();
         var mobile_aids = $('#mobile_aids').val();
         var mobile_safety = $('#mobile_safety').val();
         var sticks = $('#sticks').val();
         var zimmer_frame = $('#zimmer_frame').val();
         var relevant_supplied = $('#relevant_supplied').val();
         var safe = $('#yes').val();
         var no = $('#no').val();
         var NA = $('#NA').val();
         var yes_follow = $('#yes_follow').val();
         var no_follow = $('#no_follow').val();
         var na_follow = $('#na_follow').val();
         var ugent = $('#ugent').val();
         var priority = $('#priority').val();
         var routine = $('#routine').val();
         var yes_routine = $('#yes_routine').val();
         var no_routine = $('#no_routine').val();
         var Registration_ID = '<?= $Registration_ID ?>';
         
//           alert(operation_findings);
         
            $.ajax({
         type:'POST', 
      url:'save_arthoscopy_assessment.php',
//         data:'action=viewAppointment&appointment='+appointment+'&fromDate='+fromDate+'&toDate='+toDate+'&from_procedure='+from_procedure+'&clinic='+clinic+'&doctor='+doctor,
         data:{no_routine:no_routine,yes_routine:yes_routine,routine:routine,priority:priority,ugent:ugent,na_follow:na_follow,no_follow:no_follow,yes_follow:yes_follow,
              NA:NA,no:no,safe:safe,relevant_supplied:relevant_supplied,zimmer_frame:zimmer_frame,sticks:sticks,mobile_safety:mobile_safety,mobile_aids:mobile_aids,
              addition_comments:addition_comments,any_lag:any_lag,slr:slr,knee_flex:knee_flex,irq:irq,sq:sq,wool_crepe:wool_crepe,raymed:raymed,
              brace:brace,checked_stable:checked_stable,reason_causes:reason_causes,altered:altered,infact:infact,pmh_dh:pmh_dh,operation_findings:operation_findings,interests_id:interests_id,treatment_id:treatment_id,Registration_ID:Registration_ID},
         cache:false,
         success:function(html){
             alert("saved successfully");
       
           }
     
         });
       
       
   }

</script>