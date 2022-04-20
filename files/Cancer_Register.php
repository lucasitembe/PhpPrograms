
<?php
include("./includes/header.php");
include("./includes/connection.php");
include './includes/cleaninput.php';


$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
if(isset($_GET['Registration_ID'])){
          $Registration_ID=$_GET['Registration_ID'];
      }

      include('patient_demograpric_data.php');

?>
 <a href="#" style="color: white;" class="btn btn-info" name="tumorboardform" onclick="tumorboar_registration_form(<?php echo $Registration_ID; ?>)">
    
    TUMORBOARD REGISTRATION FORM
    
    </a>
<a href="#" class="art-button-green" onclick="cancer_registration_previous( <?php echo $Registration_ID;?>)">PREVIOUS RECORDS</a>

<a href="#" class="art-button-green" onclick="goBack()">BACK</a>
<input type="text" id="Patient_Name" value="<?php echo  $Patient_Name ?>" style="display: none;">
<br>
<br>
      <fieldset>
       <div style="margin:2px;border:1px solid #000">
        <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
            <tr>
                <td style="width:10%;text-align:right "><b>Patient Name:</b></td><td colspan=""> <?php echo  $Patient_Name ?></td>
                <td style="width:10%;text-align:right "><b>Country:</b></td><td colspan=""><?php echo $Country  ?></td>
                <td style="width:10%;text-align:right "><b>Region:</b></td><td colspan=""><?php echo $Region  ?></td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right"><b>Registration #:</b></td><td><?php echo $Registration_ID  ?></td><td style="text-align:right"><b>Phone #:</b></td><td ><?php echo $Phone_Number ?></td><td style="text-align:right"><b>District:</b></td><td ><?php echo $District ?></td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right"><b>Date of Birth:</b></td><td ><?php echo date("j F, Y", strtotime($Date_Of_Birth)) ?></td><td style="text-align:right"><b>Gender:</b></td><td ><?php echo $Gender ?></td>
                <td style="text-align:right"><b>Sponsor</b></td><td ><?php echo $Guarantor_Name . $sponsoDetails ?></td>
            </tr>
           
        </table> 

            <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
     TUMOUR
    </div>
       <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
           <tr>
                <td style="width:40%;text-align:right "><b>Morphology</b><input type='text' style='width:60%;display:inline' name='Morphology' id='Morphology'></td>
                <td style="width:60%;text-align:right " colspan="2"><b>M-code:</b><input type='text' style='width:60%;display:inline' name='M_code' id='M_code'>(8000/3 if morphology unspecified)</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right "><b>Stage:T:</b><input type='text'  name='Stage_T' style='width:60%;display:inline' id='Stage_T'></td>
                <td style="text-align: right" ><b>N:</b>&nbsp;&nbsp;&nbsp;<input type='text' style='width:80%;display:inline; '  name='Stage_N' id='Stage_N'></td>
                <td style="text-align: right" ><b>M:</b>&nbsp;&nbsp;&nbsp;<input type='text' style='width:80%;display:inline' name='Stage_M' id='Stage_M'></td>
            </tr>
        </table>
    
<div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
     SOURCE OF INFORMATION
    </div>
            <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                    <tr>
                    <td style="width:10%;text-align:right "><b>Institution:</b></td><td colspan="" ><input type='text' style="width:90%;" name='Institution' id='Institution'></td>
                    <td colspan=""><b> Ward/Unit:</b> <input type="text" autocomplete="off" style='width:60%;display:inline' name='Ward_Unit' id='Ward_Unit' /></td>
                    <td colspan=""><b> Lab number:</b> <input type="text" autocomplete="off" style='width:60%;display:inline' name='Lab_number' id='Lab_number'/></td>
                    </tr>                
            </table>
                           
<div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
     FOLLOWUP (Registrar)
    </div>
            <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                      
                 
            <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                 <td colspan="" width="50%"><b> Date at last Contact:</b> <input type="text" autocomplete="off" style='text-align: center;width:40%;display:inline' name='Date_at_last_Contact' id='Date_at_last_Contact' class="date"/></td>
                <td colspan=""><b> Status at last contact:</b> Alive<input type="radio" autocomplete="off" style='text-align: center;width:10%;display:inline' name='last_status_contact' id='Alive' />
              Dead<input type="radio" autocomplete="off" style='text-align: center;width:10%;display:inline' name='last_status_contact' id='Dead' />Unknown<input type="radio" autocomplete="off" style='text-align: center;width:10%;display:inline' name='last_status_contact' id='Unknown_status' value='Unknown_status'/></td>
            </table>
            <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
               
                <td colspan=""><b> Cause of death if known:</b> This cancer<input type="checkbox" autocomplete="off" style='text-align: center;width:10%;display:inline' name='This_cancer' id='This_cancer' value='This cancer'/>
              Trauma<input type="checkbox" autocomplete="off" style='text-align: center;width:10%;display:inline' name='Trauma' id='Trauma' value='Trauma'/>Unknown<input type="checkbox" autocomplete="off" style='width:10%;display:inline' name='Unknown_causes' id='Unknown_causes' value='Unknown_causes'/></td>
                  <td colspan="" width="50%"><b> Other:</b> <input type="text" autocomplete="off" style='width:40%;display:inline' name='Other_causes' id='Other_causes'/></td>
                
            </table>
            </table>
    </div>
                                    <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
                             <div align="right" > <button class="art-button-green button_pro" style="width: 15em;" onclick='Save_Cancer_data()'>SAVE</button></div>
    </div>
    </fieldset>
 
<div id="patient_registration_detail"></div>
<div id="patient_patient_registration"></div>

<div id="open_tumorboard_form_dialogy_div">

</div>
<div id="open_tumorboard_form_dialogy_div_preview"></div>
<div id="open_tumorboard_form_dialogy_div_display"></div>
<?php
    include("./includes/footer.php");
?>


<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
<script>

function tumorboar_registration_form(Registration_ID){ 
        var Patient_Name = $("#Patient_Name").val();

        $.ajax({
            type:'POST',
            url:'Ajax_tumorboard_registration.php',
            data:{Registration_ID:Registration_ID, tumorboardform:''},
            success:function(responce){
                $("#open_tumorboard_form_dialogy_div").dialog({
                    title: 'TUMORBOARD REGISTRATION FORM  '+Patient_Name+" "+Registration_ID,
                    width: '70%',
                    height: 750,
                    modal: true, 
                });
                $("#open_tumorboard_form_dialogy_div").html(responce);
            }
        });
    }
    function save_tumorboard_reg(Registration_ID){
        var Brief_history_findings =$("#Brief_history_findings").val();
        var Histology_FNAC = $("#Histology_FNAC").val();
        var TNM_classfication = $("#TNM_classfication").val();
        var Question_tumorboard = $("#Question_tumorboard").val();
        var Desicion_of_Tumorboard = $("#Desicion_of_Tumorboard").val();
        if(Brief_history_findings==''){
            $("#Brief_history_findings").css("border", "1px solid red");
        }else if(Histology_FNAC==''){
            $("#Histology_FNAC").css("border", "1px solid red");
        }else if(Desicion_of_Tumorboard==''){
            $("#Desicion_of_Tumorboard").css("border", "1px solid red");
        }else{
            $("#Histology_FNAC").css("border", "");
            $("#Brief_history_findings").css("border", "");
            $("#Desicion_of_Tumorboard").css("border", "");        
            $.ajax({
                type:'POST',
                url:'Ajax_save_tumorboard.php',
                data:{Brief_history_findings:Brief_history_findings,Histology_FNAC:Histology_FNAC,TNM_classfication:TNM_classfication,Question_tumorboard:Question_tumorboard,Desicion_of_Tumorboard:Desicion_of_Tumorboard,Registration_ID:Registration_ID,save_record:''},
                success:function(responce){
                    alert("Data saved successful");
                    $('#save_btn').hide();
                }
            });
        }
    }

    function display_previous_record(Registration_ID){
        var Patient_Name = $("#Patient_Name").val();
        $.ajax({
            type:'POST',
            url:'Ajax_tumorboard_registration.php',
            data:{Registration_ID:Registration_ID,previous_record:''},
            success:function(responce){    
                $("#open_tumorboard_form_dialogy_div_display").dialog({
                    title: 'TUMORBOARD RESULT BY DATE FOR   '+Patient_Name+" "+Registration_ID,
                    width: '70%',
                    height: 750,
                    modal: true,
                });            
                $("#open_tumorboard_form_dialogy_div_display").html(responce);
            }
        });
    }

    function preview_tumorboard_data(Created_at, Tumorboard_ID){
        var Patient_Name = $("#Patient_Name").val();
        var Registration_ID =<?php echo $Registration_ID;?>;
        $.ajax({
            type:'POST',
            url:'Ajax_tumorboard_registration.php',
            data:{Registration_ID:Registration_ID,Created_at:Created_at,Tumorboard_ID:Tumorboard_ID,tumorboardform2:''},
            success:function(responce){   
                $("#open_tumorboard_form_dialogy_div_preview").dialog({
                    title: 'TUMORBOARD REGISTRATION FORM RESULT '+Patient_Name+" "+Registration_ID,
                    width: '70%',
                    height: 750,
                    modal: true,
                });                
                $("#open_tumorboard_form_dialogy_div_preview").html(responce);
            }
        });
    }

    function tumorboard_reg_form(Registration_ID){
        $.ajax({
            type:'POST',
            url:'Ajax_tumorboard_registration.php',
            data:{Registration_ID:Registration_ID,tumorboardform:''},
            success:function(responce){                
                $("#open_tumorboard_form_dialogy_div").html(responce);
            }
        });
    }


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
    
    function cancer_registration_previous( Registration_ID){
        var Patient_Name = $("#Patient_Name").val();
        $.ajax({
            url:'Ajax_get_previous_cancer_data.php', 
            type:'POST', 
            data:{Registration_ID:Registration_ID,registration_data:''},
            success:function(responce){
                // console.log(responce);
                $("#patient_registration_detail").dialog({
                    title: 'PREVIOUS CANCER  REGISTRATION DETAILS FOR '+Registration_ID+"  --- "+Patient_Name,
                    width: '70%',
                    height: 700,
                    modal: true,
                });
                $("#patient_registration_detail").html(responce);
                
            }
        });
    }
    
    function preview_patient_cancer_registration_form(cancer_id, Registration_ID){
      
        var Patient_Name = $("#Patient_Name").val();
        $.ajax({
            url:'Ajax_get_previous_cancer_data.php', 
            type:'POST',
            data:{cancer_id:cancer_id,Registration_ID:Registration_ID,view_registration_data:''},
            success:function(responce){
                // console.log(responce);
                $("#patient_patient_registration").dialog({
                    title: 'CANCER PATIENT REGISTRATION DETAILS FOR -------- '+Registration_ID+"  --- "+Patient_Name,
                    width: '90%',
                    height: 900,
                    modal: true,
                });
                $("#patient_patient_registration").html(responce);
                
            }
        });
    }
        function Save_Cancer_data() {
            var Registration_ID= <?php echo $Registration_ID; ?>;           
            var  Date_diagnosis_HIV=$('#Date_diagnosis_HIV').val();
            var  Chronic_disease=$('#Chronic_disease').val();
          
            var  Date_of_Incidence=$('#Date_of_Incidence').val();
            var  symptoms_date=$('#symptoms_date').val();
            
            var  Other=$('#Other').val();
            var  Primary_site=$('#Primary_site').val();
            var  Secondary_Site=$('#Secondary_Site').val();
            var  Morphology=$('#Morphology').val();
            var  M_code=$('#M_code').val();
            var  Stage_T=$('#Stage_T').val();
            var  Stage_N=$('#Stage_N').val();
            var  Stage_M=$('#Stage_M').val();
            var  Gleason_score=$('#Gleason_score').val();
            var  Baseline_PSA=$('#Baseline_PSA').val();           
            var  Other_Staging=$('#Other_Staging').val();
            var  A_Regimen=$('#A_Regimen').val();
            var  A_Regimen_Start_Date=$('#A_Regimen_Start_Date').val();
            var  A_Regimen_End_Date=$('#A_Regimen_End_Date').val();
            var  A_Other_Condition=$('#A_Other_Condition').val();
            var  B_Regimen=$('#B_Regimen').val();
            var  B_Regimen_Start_Date=$('#B_Regimen_Start_Date').val();
            var  B_Regimen_End_Date=$('#B_Regimen_End_Date').val();
            
            var  B_Other_Condition=$('#B_Other_Condition').val();
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
            
            var  Unknown_status=$('#Unknown_status').val();
            var  Other_causes=$('#Other_causes').val();
            var  Surgery_Date=$('#Surgery_Date').val();
            var  Radiotherapy_Date=$('#Radiotherapy_Date').val();
            var  Immunotherapy_Date=$('#Immunotherapy_Date').val();
            var  Targeted_therapy_Date=$('#Targeted_therapy_Date').val();
            var  Chemotherapy_Date=$('#Chemotherapy_Date').val();
            var  Hormone_therapy_Date=$('#Hormone_therapy_Date').val();
            var  Other_chemo=$('#Other_chemo').val();


            var B_condition = "";
            if($('#B_Complete_remission').is(":checked")){
                B_condition += "B_Complete_remission"+","
            }
            if($('#B_Partial_remission').is(":checked")){
                B_condition += "B_Partial_remission"+","
            }
            if($('#B_Stable_disease').is(":checked")){
                B_condition += "B_Stable_disease"+","
            }
            if($('#B_Progression').is(":checked")){
                B_condition += "B_Progression"+","
            }

            var A_condition = "";
            if($('#A_Complete_remission').is(":checked")){
                A_condition += "A_Complete_remission"+","
            }
            if($('#B_Partial_remission').is(":checked")){
                A_condition += "A_Partial_remission"+","
            }
            if($('#A_Stable_disease').is(":checked")){
                A_condition += "A_Stable_disease"+","
            }
            if($('#A_Progression').is(":checked")){
                A_condition += "A_Progression"+","
            }

            var General_progress_of_desease = "";
            if($('#Cured').is(":checked")){
                General_progress_of_desease += "Cured"+","
            }
            if($('#Recurred').is(":checked")){
                General_progress_of_desease += "Recured"+","
            }
            if($('#Unknown2').is(":checked")){
                General_progress_of_desease += "Unknown2"+","
            }
            var Basis_of_diagnosis = "";
            if($('#Histology').is(":checked")){
                Basis_of_diagnosis += "Histology"+","
            }
            if($('#Clinical_Only').is(":checked")){
                Basis_of_diagnosis += "Clinical_Only"+","
            }
            if($('#Cytology_Hematology').is(":checked")){
                Basis_of_diagnosis += "Cytology_Hematology"+","
            }
            if($('#FNAC').is(":checked")){
                Basis_of_diagnosis += "FNAC"+","
            }
            if($('#Clinical_Investigation').is(":checked")){
                Basis_of_diagnosis += "Clinical_Investigation"+","
            }

            var C_Any_other_result = "";
            if($('#Treatment_completed').is(":checked")){
                C_Any_other_result += "Treatment_completed"+","
            }
            if($('#Lost_before_treatment').is(":checked")){
                C_Any_other_result += "Lost_before_treatment"+","
            }
            if($('#Lost_during_treatment').is(":checked")){
                C_Any_other_result += "Lost_during_treatment"+","
            }
            if($('#Referred').is(":checked")){
                C_Any_other_result += "Referred"+","
            }
            if($('#PCT').is(":checked")){
                C_Any_other_result += "PCT"+","
            }
            if($('#Treatment_ongoing').is(":checked")){
                C_Any_other_result += "Treatment_ongoing"+","
            }

            var HIV_sero_status="";
            if($('#SP_on_ART').is(":checked")){
                HIV_sero_status += "SP_on_ART"+","
            }
            if($('#SN').is(":checked")){
                HIV_sero_status += "SN"+","
            }
            if($('#Unknown3').is(":checked")){
                HIV_sero_status += "Unknown3"+","
            }
            if($('#SP_not_on_ART').is(":checked")){
                HIV_sero_status += "SP_not_on_ART"+","
            }

            var Intuition = ""
            if($('#Curative').is(":checked")){
                Intuition += "Curative"+","
            }
            if($('#Palliative').is(":checked")){
                Intuition += "Palliative"+","
            }
            if($('#Unknown_value').is(":checked")){
                Intuition += "Unknown_value"+","
            }
            var cause_of_death = ""
            if($('#This_cancer').is(":checked")){
                cause_of_death += "This_cancer"+","
            }
            if($('#Unknown_causes').is(":checked")){
                cause_of_death += "Unknown_causes"+","
            }
            if($('#Trauma').is(":checked")){
                cause_of_death += "Trauma"+","
            }
            var Chemo = ""
            if($('#Adjuvant').is(":checked")){
                Chemo += "Adjuvant"+","
            }
            if($('#neoadjuvant').is(":checked")){
                Chemo += "neoadjuvant"+","
            }
            if($('#chemo_radiation').is(":checked")){
                Chemo += "chemo_radiation"+","
            }
            if($('#Palliative_chemotherapy').is(":checked")){
                Chemo += "Palliative_chemotherapy"+","
            }

            var last_status_contact ="";
            if($("#Alive").is(":checked")){
                last_status_contact = "Alive";
            }
            if($("#Dead").is(":checked")){
                last_status_contact = "Dead";
            }
            if($("#Unknown_status").is(":checked")){
                last_status_contact = "Unknown";
            }
           
            var ER_Status ="";
            if($("#ER_Status_Negative ").is(":checked")){
                ER_Status = "Negative";
            }
            if($("#ER_Status_Positive ").is(":checked")){
                ER_Status = "Positive";
            }
            

            var Her_Status ="";
            if($("#Her_Status_Negative ").is(":checked")){
                Her_Status = "Negative";
            }
            if($("#Her_Status_Positive ").is(":checked")){
                Her_Status = "Positive";
            }
            var PR_Status ="";
            if($("#PR_Status_Negative ").is(":checked")){
                PR_Status = "Negative";
            }
            if($("#PR_Status_Positive ").is(":checked")){
                PR_Status = "Positive";
            }
            
            var Metastasis ="";
            if($("#Metastasis_Yes ").is(":checked")){
                Metastasis = "Yes";
            }
            if($("#Metastasis_No ").is(":checked")){
                Metastasis = "No";
            }
            var Non_Metastasis ="";
            if($("#Non_metastasis_High_risk ").is(":checked")){
                Non_Metastasis = "High";
            }
            if($("#Non_metastasis_Intermediate_risk ").is(":checked")){
                Non_Metastasis = "Intermediate";
            }
            if($("#Non_metastasis_Low_risk ").is(":checked")){
                Non_Metastasis = "Low";
            }
            var Surgery="";
            if($("#Surgery_Yes ").is(":checked")){
                Surgery = "Yes";
            }
            if($("#Surgery_No ").is(":checked")){
                Surgery = "No";
            }
            var Hormone_therapy="";
            if($("#Hormone_therapy_Yes ").is(":checked")){
                Hormone_therapy = "Yes";
            }
            if($("#Hormone_therapy_No ").is(":checked")){
                Hormone_therapy = "No";
            }
            var Chemotherapy="";
            if($("#Chemotherapy_Yes ").is(":checked")){
                Chemotherapy = "Yes";
            }
            if($("#Chemotherapy_No ").is(":checked")){
                Chemotherapy = "No";
            }
            var Targeted_therapy="";
            if($("#Targeted_therapy_Yes").is(":checked")){
                Targeted_therapy = "Yes";
            }
            if($("#Targeted_therapy_No ").is(":checked")){
                Targeted_therapy = "No";
            }
            var Immunotherapy="";
            if($("#Immunotherapy_Yes").is(":checked")){
                Immunotherapy = "Yes";
            }
            if($("#Immunotherapy_No ").is(":checked")){
                Immunotherapy = "No";
            }
            var Radiotherapy="";
            if($("#Radiotherapy_Yes").is(":checked")){
                Radiotherapy = "Yes";
            }
            if($("#Radiotherapy_No ").is(":checked")){
                Radiotherapy = "No"; 
            }
            if(Chronic_disease==""){
                $("#Chronic_disease").css("border", "1px solid red");
            }else{
                $("#Chronic_disease").css("border", "");
                $.ajax({
                    type: 'POST',
                    url: "Insert_cancer_data.php",
                    data: {Other_chemo:Other_chemo,Chemo:Chemo,Hormone_therapy_Date:Hormone_therapy_Date,Hormone_therapy:Hormone_therapy,Chemotherapy:Chemotherapy,Targeted_therapy:Targeted_therapy,
                    Immunotherapy:Immunotherapy,Radiotherapy:Radiotherapy,Surgery:Surgery,
                    Chemotherapy_Date:Chemotherapy_Date,Targeted_therapy_Date:Targeted_therapy_Date,
                    Immunotherapy_Date:Immunotherapy_Date,Radiotherapy_Date:Radiotherapy_Date,Surgery_Date:Surgery_Date,
                Intuition:Intuition,Other_causes:Other_causes,
                    cause_of_death:cause_of_death,last_status_contact:last_status_contact,Date_at_last_Contact:Date_at_last_Contact,
                    three_Line:three_Line,one_Line:one_Line,two_Line:two_Line,supportive_care:supportive_care,Adverse_events:Adverse_events,Lab_number:Lab_number,Ward_Unit:Ward_Unit,
                Institution:Institution,General_progress_of_desease:General_progress_of_desease,
                C_Any_other_result: C_Any_other_result,B_Other_Condition:B_Other_Condition,B_condition:B_condition,B_Regimen_End_Date:B_Regimen_End_Date,
                B_Regimen_Start_Date:B_Regimen_Start_Date,B_Regimen:B_Regimen,A_Other_Condition:A_Other_Condition,A_condition:A_condition,A_Regimen_End_Date:A_Regimen_End_Date,
                A_Regimen_Start_Date:A_Regimen_Start_Date,A_Regimen:A_Regimen,Other_Staging:Other_Staging,Non_Metastasis:Non_Metastasis,
                Metastasis:Metastasis,Baseline_PSA:Baseline_PSA,Gleason_score:Gleason_score,Stage_M:Stage_M,
                Stage_N:Stage_N,Stage_T:Stage_T,Morphology:Morphology,Secondary_Site:Secondary_Site,Primary_site:Primary_site,Her_Status:Her_Status,
                PR_Status:PR_Status,ER_Status:ER_Status,Other:Other,Basis_of_diagnosis:Basis_of_diagnosis,symptoms_date:symptoms_date,Date_of_Incidence:Date_of_Incidence,Chronic_disease:Chronic_disease,Date_diagnosis_HIV:Date_diagnosis_HIV,HIV_sero_status:HIV_sero_status,Registration_ID:Registration_ID,M_code:M_code},
                    success: function (html) {
                        alert(html);                      
                    
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        alert(textStatus);
                    }
                });
            }

    }
</script>