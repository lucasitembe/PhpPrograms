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
if(isset($_GET['Admission_ID'])){
    $Admission_ID = $_GET['Admission_ID'];
}else{
    $Admission_ID=0;
}
if(isset($_GET['consultation_ID'])){
    $consultation_ID = $_GET['consultation_ID'];
}else{
    $consultation_ID = 0;
}
$select_Patient = mysqli_query($conn,"select
    Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
            Gender,pr.Region,pr.District,pr.Ward,
                Member_Number,Member_Card_Expire_Date,
                    pr.Phone_Number,Email_Address,Occupation,
                        Employee_Vote_Number,Emergence_Contact_Name,
                            Emergence_Contact_Number,Company,Registration_ID,
                                Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                Registration_ID
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
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Guarantor_Name = $row['Guarantor_Name'];
        }
    }
?>  
    <?php 
        if(isset($_GET['INPATIENT'])=='INPATIENT'){
            echo "<a href='inpatientclinicalnotes.php?Registration_ID=$Registration_ID&consultation_ID=$consultation_ID&Admission_ID=$Admission_ID' class='art-button-green'>BACK</a>";
        }else if(isset($_GET['Registration']) =='Registration'){
            echo "<a href='radiotherapy_treatment.php?section=radiotherapy_treatment=radiotherapy_treatment' class='art-button-green'>BACK</a>";
        }else{
            echo "<a href='clinicalnotes.php?Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID ' class='art-button-green'>
                    BACK
                </a>";
        }
    ?>
    
<br/><br/><br/><br/><br/>
<fieldset>  
            <legend align="center"><b>CANCER PATIENT</b></legend>
        <center><table width = 60%>
				<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
  
                    <a href='Cancer_Registration.php?Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?php echo $from_consulted; ?>'>
                        <button style='width: 100%; height: 100%'>
                            REGISTRATION
                        </button>
                    </a>
  
                </td>
				</tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                      
                        <a href='list_of_cancer_type.php?Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?php echo $from_consulted; ?>'>
                            <button style='width: 100%; height: 100%'>
                               ASSIGN/PRESCIBE PROTOCAL
                            </button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
              
                        <a href='cancer_setup.php?Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?php echo $from_consulted; ?>'>
                            <button style='width: 100%; height: 100%'>
                              CANCER PROTOCAL SETUP
                            </button>
                        </a>
                    </td>
                    
                </tr>
                <input type="text" style="display: none" id="Patient_Name" value="<?php echo $Patient_Name;?>">
                <!-- <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='#'>
                            <button style='width: 100%; height: 100%' name="tumorboardform" onclick="tumorboar_registration_form(<?php echo $Registration_ID; ?>)">
                            TUMORBOARD REGISTRATION FORM
                            </button>
                        </a>
                    </td>
                </tr> -->
		  </table>
        </center>
</fieldset><br/>
        <div id="open_tumorboard_form_dialogy_div" style="display:none">
            
            <div class="col-md-12"><hr> </div>
            <div class="col-md-12" style="height:70vh;overflow-y: scroll;" id="consultation_form_request_body"> 
                <input type="text" id="Registration_ID" value="<?php echo $Registration_ID;?>">
            </div>
        </div>

<div id="open_tumorboard_form_didalogy_div">

</div>
<div id="open_tumorboard_form_dialogy_div_preview"></div>
<div id="open_tumorboard_form_dialogy_div_display"></div>
<?php
    include("./includes/footer.php");
?>
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
</script>