<?php
include("./includes/header.php");
include("./includes/connection.php");
$requisit_officer = $_SESSION['userinfo']['Employee_Name'];

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
//    if (isset($_SESSION['userinfo']['Admission_Works'])) {
//        if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
//            header("Location: ./index.php?InvalidPrivilege=yes");
//        }
//    } else {
//        header("Location: ./index.php?InvalidPrivilege=yes");
//    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_GET['consultation_ID'])) {
    $consultation_ID = $_GET['consultation_ID'];
} else {
    $consultation_ID = 0;
}


if (isset($_GET['Admision_ID'])) {
    $Admision_ID = $_GET['Admision_ID'];
} else {
    $Admision_ID = 0;
}

//echo $Admision_ID;
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}
?>
<a href="#" class="art-button-green" onclick="show_dialog_previous_assessment(<?php echo $Registration_ID;?>)">PREVIOUS ASSESSMENT</a>
<a href="burn_unit.php?Registration_ID=<?=$Registration_ID?>&Admission_ID=<?=$Admision_ID?>" class="art-button-green">BACK</a>
<?php
if(isset($_GET['section']) && $_GET['section'] == "patient_record" ){
    echo "<a href='Patientfile_Record_Detail.php?Registration_ID=".$_GET['Registration_ID']."&Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage&position=in' class='art-button-green'>BACK</a>";
}

$select_patien_details = mysqli_query($conn,"SELECT pr.Sponsor_ID,Member_Number,Patient_Name, relationship,pr.Phone_Number,Occupation,Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
    FROM  tbl_patient_registration pr, tbl_sponsor sp
    WHERE  pr.Registration_ID = '$Registration_ID' AND 
        sp.Sponsor_ID = pr.Sponsor_ID
        ") or die(mysqli_error($conn));
$no = mysqli_num_rows($select_patien_details);
if ($no > 0) {
while ($row = mysqli_fetch_array($select_patien_details)) {
    $Member_Number = $row['Member_Number'];
    $Patient_Name = $row['Patient_Name'];
    $Registration_ID = $row['Registration_ID'];
    $Phone_Number = $row['Phone_Number'];
    $Occupation = $row['Occupation'];
    $Gender = $row['Gender'];
    $Guarantor_Name  = $row['Guarantor_Name'];
    $Sponsor_ID = $row['Sponsor_ID'];
    $DOB = $row['Date_Of_Birth'];
    $relationship =$row['relationship']; 
    $Denomination_Name = $row['Denomination_Name'];
}
} else {
$Guarantor_Name  = '';
$Member_Number = '';
$Patient_Name = '';
$Gender = '';
$Registration_ID = 0;
}

$age = date_diff(date_create($DOB), date_create('today'))->y;

$select_bed_result = mysqli_query($conn, "SELECT Bed_Name, ad.ward_room_id,Kin_Name,Kin_Relationship, ad.Hospital_Ward_ID,Region,pr.District,ad.Ward, hw.Hospital_Ward_Name, wr.room_name,Admission_Date_Time,  ad.Registration_ID FROM tbl_admission ad ,tbl_patient_registration pr, tbl_hospital_ward hw, tbl_ward_rooms wr WHERE Admision_ID='$Admision_ID' AND ad.Registration_ID=$Registration_ID AND ad.ward_room_id = wr.ward_room_id AND ad.Hospital_Ward_ID =hw.Hospital_Ward_ID") or die(mysqli_error($conn));
     while($bed_row = mysqli_fetch_assoc($select_bed_result)){
         $ward = $bed_row['Hospital_Ward_Name'];
         $bed = $bed_row['Bed_Name'];
         $Admission_Date_Time = $bed_row['Admission_Date_Time'];
         $Kin_Relationship =$bed_row['Kin_Relationship'];
         $Kin_Name = $bed_row['Kin_Name'];
         $room = $bed_row['room_name'];
         $Region = $bed_row['Region'];
         $District =$bed_row['District'];
         $Ward = $bed_row['Ward'];
     }
     $address = $Region."  ".$district. " ".$Ward;
?>

<fieldset>
    <legend align="center" style='padding:10px; color:white; background-color:#2D8AAF; text-align:center'><b>
            <b>PATIENT ASSESSMENT FORM FOR NURSES</b><br/>
            <span style='color:yellow;'><?php echo "" . $Patient_Name . "  | " .$Registration_ID." | ". $Gender . " | " . $age . " years | " . $Guarantor_Name  . ""; ?></span></b>
    </legend>
<div class="box box-primary">
    <div class="box-body">
<table class="table table-condensed" width="90%">
    <!-- <tr><td>Hosp. No.</td>
        <th><?php echo $Registration_ID ?></th>
        <td>PATIENT NAME</td>
        <td><?php echo $Patient_Name; ?></td> 
        <td>SEX#</td>
        <td><?php echo $Gender; ?></td>     
        
    </tr> -->
    <tr>
        <th width="10%">Religion</th>
        <td width="15%"><?php echo $Denomination_Name; ?> </td>
        <th width="10%">Next of Kin</th>
        <td width="15%"><?php echo $Kin_Name;?></td>
        <th width="10%">Relationship</th>
        <td width="15%"><?php echo  $Kin_Relationship;?></td>
    </tr>
    <tr>
        <th>Address</th>
        <td><?php echo $address;?></td>
        <th>Tel:</th>
        <td><?php echo $Phone_Number;?></td>
        <th>Occupation</th>
        <td><?php echo $Occupation; ?></td>
    </tr>
    <?php
     
        echo' <tr>
            <th>Ward:</th>
            <td>'.$ward.'</td>
            <th>ROOM:</th>
            <td>'.$room.'</td>
            <th>Bed No:</th>
            <td>'.$bed.'</td>
        </tr>
    </table>
     ';
 ?>
 </div>
 <p id='updated_button'></p>
</div><div id="assessinformation" ></div>
<div id="displayAssessment" >
    
</div>

<input type="text" id="Registration_ID" value="<?php echo $Registration_ID;?>" style="display:none" class="form-control">
<input type="text" id="Admision_ID" value="<?php echo $Admision_ID;?>" style="display:none"  class="form-control">
<div id="assessinformation_previous"></div>
<div id="assessinformation_records"></div>
    <script>
        $(document).ready(function(){
            dispaly_assessment();
        });
     function save_patient_assessment(Registration_ID){
        var significant_life_criss = $("#significant_life_criss").val();
        var current_health_status = $("#current_health_status").val();
        var status = $("#status").val();
        var medication_information = $("#medication_information").val();
        var social_history = $("#social_history").val();
        var relatives = $("#relatives").val();
        var nursing_history = $("#nursing_history").val();

        if(significant_life_criss==""){
            $("#significant_life_criss").css("border", "1px solid red");
        }else if(current_health_status==""){
            $("#current_health_status").css("border", "1border solid  red");
        }else if(status==""){
            $("#status").css("border", "1px solid red");
        }else if(medication_information==""){
            $("#medication_information").css("border", "1px solid red");
        }else if(social_history==""){
            $("#social_history").css("border", "1px solid red");
        }else if(nursing_history==""){
            $("#nursing_history").css("border","1px solid red");
        }else{
            $("#significant_life_criss").css("border", "");
            $("#current_health_status").css("border", "1border solid  red");
            $("#status").css("border", "");
            $("#medication_information").css("border", "");
            $("#social_history").css("border", "");
            $("#nursing_history").css("border","");
            $.ajax({
                type:'POST',
                url:'Ajax_save_burn_unit.php',
                data:{significant_life_criss:significant_life_criss,current_health_status:current_health_status,status:status,medication_information:medication_information,social_history:social_history, nursing_history:nursing_history, Registration_ID:Registration_ID,relatives:relatives,btn_assessment:''},
                success:function(responce){
                    dispaly_assessment()
                }
            })
        }
     }
//Admision_ID:Admision_ID,
     function dispaly_assessment(){
        var Registration_ID = $("#Registration_ID").val();
        var Admision_ID = $("#Admission_ID").val();
        $.ajax({
            type:'POST',
            url:'add_type_burn.php',
            data:{Admision_ID:Admision_ID,Registration_ID:Registration_ID,displayAssessment:''},
            success:function(responce){
                $("#displayAssessment").html(responce);
            }
        })
     }

     function add_assessment_information(Registration_ID,Assessment_ID){         
        $.ajax({
            type:'POST',
            url:'add_type_burn.php',
            data:{Registration_ID:Registration_ID,Assessment_ID:Assessment_ID,btn_assessment_info:''},
            success:function(responce){
                $("#assessinformation").dialog({
                    title: 'ASSESSMENT INFORMATION PATIENT NUMBER '+Registration_ID,
                    width: '95%',
                    height: 600,
                    modal: true,
                });
                $("#assessinformation").html(responce)
                display_assessment_info()
            }
        }); 
    }

    function save_assessment_data(Registration_ID){
        var Registration_ID = $("#Registration_ID").val();
        var Assessment_ID = $("#Assessment_ID").val();
        var Assessment_data = "";
        if($("#Airway").is(":checked")){
            Assessment_data +="Airway"+','
           }
        if($("#Breathing").is(":checked")){
            Assessment_data +="Breathing"+','
           }
        if($("#Ciculation").is(":checked")){
            Assessment_data +="Ciculation"+','
           }
           if($("#Level_of_consciousness").is(":checked")){
            Assessment_data +="Level_of_consciousness"+','
           }
        if($("#Pain").is(":checked")){
            Assessment_data +="Pain"+','
           }
        if($("#Fluid_intake").is(":checked")){
            Assessment_data +="Fluid_intake"+','
           }
           if($("#Fluid_output").is(":checked")){
            Assessment_data +="Fluid_output"+','
           }
        if($("#Fluid_balance").is(":checked")){
            Assessment_data +="Fluid_balance"+','
           }
        if($("#Body_temperature").is(":checked")){
            Assessment_data +="Body_temperature"+','
           }
           if($("#Food_nutrition_electrolytes").is(":checked")){
            Assessment_data +="Food_nutrition_electrolytes"+','
           }
        if($("#Elimination").is(":checked")){
            Assessment_data +="Elimination"+','
           }
        if($("#Haygiene_body").is(":checked")){
            Assessment_data +="Haygiene_body"+','
           }
           if($("#Haygiene_Oral_eyes").is(":checked")){
            Assessment_data +="Haygiene_Oral_eyes"+','
           }
        if($("#Wounds").is(":checked")){
            Assessment_data +="Wounds"+','
           }
        if($("#Risk_of_pressure").is(":checked")){
            Assessment_data +="Risk_of_pressure"+','
           }
           if($("#Drains").is(":checked")){
            Assessment_data +="Drains"+','
           }
        if($("#Exercise").is(":checked")){
            Assessment_data +="Exercise"+','
           }
        if($("#Social_well_being").is(":checked")){
            Assessment_data +="Social_well_being"+','
           }
           if($("#Social_well_being").is(":checked")){
            Assessment_data +="Social_well_being"+','
           }
        if($("#Psychological_well_being").is(":checked")){
            Assessment_data +="Psychological_well_being"+','
           }
        if($("#Spiritual_well_being").is(":checked")){
            Assessment_data +="Spiritual_well_being"+','
           }
        if($("#Environment_equipment").is(":checked")){
            Assessment_data +="Environment_equipment"+','
           }
        if($("#Information_education").is(":checked")){
            Assessment_data +="Information_education"+','
           }
        if($("#tests").is(":checked")){
            Assessment_data +="tests"+','
           }
           if($("#Treatment").is(":checked")){
            Assessment_data +="Treatment"+','
           }
        if($("#Payments").is(":checked")){
            Assessment_data +="Payments"+','
           }
        if($("#Rest_sleep").is(":checked")){
            Assessment_data +="Rest_sleep"+','
           }
           if($("#Creative_activities").is(":checked")){
            Assessment_data +="Creative_activities"+','
           }
        if($("#Other_problems").is(":checked")){
            Assessment_data +="Other_problems"+','
           }
        //    alert(Assessment_ID +"=="+Registration_ID)
        console.log(Assessment_data);
        $.ajax({
            type:'POST', 
            url:'Ajax_save_burn_unit.php',
            data:{Assessment_data:Assessment_data,Assessment_ID:Assessment_ID,Registration_ID:Registration_ID,info_btn:''},
            success:function(responce){
                display_assessment_info();
            }
        })
    }

    function Update_assessment_data(Info_assessment_ID){
        var Assessment_data_update = "";
       
        if($("#Airway_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Airway"+','
           }
        if($("#Breathing_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Breathing"+','
           }
        if($("#Ciculation_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Ciculation"+','
           }
           if($("#Level_of_consciousness_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Level_of_consciousness"+','
           }
        if($("#Pain_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Pain"+','
           }
        if($("#Fluid_intake_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Fluid_intake"+','
           }
           if($("#Fluid_output_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Fluid_output"+','
           }
        if($("#Fluid_balance_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Fluid_balance"+','
           }
        if($("#Body_temperature_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Body_temperature"+','
           }
           if($("#Food_nutrition_electrolytes_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Food_nutrition_electrolytes"+','
           }
        if($("#Elimination_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Elimination"+','
           }
        if($("#Haygiene_body_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Haygiene_body"+','
           }
           if($("#Haygiene_Oral_eyes_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Haygiene_Oral_eyes"+','
           }
       
        if($("#Risk_of_pressure_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Risk_of_pressure"+','
           }
           if($("#Drains_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Drains"+','
           }
         if($("#Exercise_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Exercise"+','
           }
        if($("#Social_well_being_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Social_well_being"+','
           }
           if($("#Social_well_being_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Social_well_being"+','
          }
        if($("#Psychological_well_being_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Psychological_well_being"+','
           }
        if($("#Spiritual_well_being_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Spiritual_well_being"+','
           }
        if($("#Environment_equipment_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Environment_equipment"+','
           }
        if($("#Information_education_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Information_education"+','
           }
        if($("#tests_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="tests"+','
           }
           if($("#Treatment_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Treatment"+','
           }
        if($("#Payments_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Payments"+','
           }
        if($("#Rest_sleep_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Rest_sleep"+','
           }
           if($("#Creative_activities_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Creative_activities"+','
           }
        if($("#Other_problems_"+Info_assessment_ID).is(":checked")){
            Assessment_data_update +="Other_problems"+','
           }

          // alert(Assessment_data_update )
        console.log(Assessment_data_update);
        $.ajax({
            type:'POST', 
            url:'Ajax_save_burn_unit.php',
            data:{Assessment_data_update:Assessment_data_update,Info_assessment_ID:Info_assessment_ID,update_info_btn:''},
            success:function(responce){
                display_assessment_info();
            }
        })
    }

    function display_assessment_info(){
        var Assessment_ID = $("#Assessment_ID").val();
        $.ajax({
            type:'POST',
            url:'Ajax_display_burn.php',
            data:{Assessment_ID:Assessment_ID,infobody:''},
            success:function(responce){
                $("#infobody").html(responce);
            }
        })
    }

   function  show_dialog_previous_assessment(Registration_ID){
    $.ajax({
            type:'POST',
            url:'Ajax_display_burn.php',
            data:{Registration_ID:Registration_ID, previous_assessment_info:''},
            success:function(responce){
                $("#assessinformation_previous").dialog({
                    title: 'ASSESSMENT INFORMATION PATIENT NUMBER ',
                    width: '50%',
                    height: 500,
                    modal: true,
                });
                $("#assessinformation_previous").html(responce)
               
            }
        });
   }

   function preview_assessment_record(Assessment_ID){
    $.ajax({
            type:'POST',
            url:'Ajax_display_burn.php',
            data:{Assessment_ID:Assessment_ID, preview_record_assessment_info:''},
            success:function(responce){
                $("#assessinformation_records").dialog({
                    title: 'ASSESSMENT INFORMATION PATIENT NUMBER ',
                    width: '90%',
                    height: 600,
                    modal: true,
                });
                $("#assessinformation_records").html(responce)
               
            }
        });
   }
          // Set the date we're counting down to
        var time_create = $("#created_at_"+Info_assessment_ID);
       var countDownDate = new Date("Apr 26, 2020 15:37:25").getTime();

        // Update the count down every 1 second
        var x = setInterval(function() {

        // Get today's date and time
        var now = new Date().getTime();
            
        // Find the distance between now and the count down date
        var distance = countDownDate - now;
            
        // Time calculations for days, hours, minutes and seconds
        //  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
        // Output the result in an element with id="updated_button"
        document.getElementById("updated_button").innerHTML =  hours + "h "
        + minutes + "m " + seconds + "s ";
            
        // If the count down is over, write some text 
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("updated_button").innerHTML = "DONE";
        }
        }, 1000);
  
    </script>