<?php
session_start();

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

include("includes/connection.php");
include("includes/header.php");
include("includes/All.Templates.Functions.php");

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$consultation_ID = (isset($_GET['consultation_ID'])) ? $_GET['consultation_ID'] : 0;
$Registration_ID = (isset($_GET['Registration_ID'])) ? $_GET['Registration_ID'] : 0;
$Payment_Item_Cache_List_ID = (isset($_GET['Payment_Item_Cache_List_ID'])) ? $_GET['Payment_Item_Cache_List_ID'] : 0;
$Patient_Payment_Item_List_ID = (isset($_GET['Patient_Payment_Item_List_ID'])) ? $_GET['Patient_Payment_Item_List_ID'] : 0;
$Admision_ID = (isset($_GET['Admision_ID'])) ? $_GET['Admision_ID'] : 0;
$Location = (isset($_GET['Location'])) ? $_GET['Location'] : NULL;

$Patients = json_decode(getPatientInfomations($conn,$Registration_ID),true);

if($Location == 'Clinical'){
    echo "<input type='button' value='BACK' class='art-button-green' onclick='history.go(-1)'/>";
}else{
    echo "<a href='Patientfile_Record_Detail.php?Registration_ID=160035&Patient_Payment_ID=&Patient_Payment_Item_List_ID=&PatientFile=PatientFileThisForm&position=out&this_page_from=patient_record' class='art-button-green'>BACK</a>";
}
?>

<br>
<fieldset>
    <legend align=center>ALL TEMPLATES RECORDS</legend>
        <div class="box box-primary" style="height: 110px;overflow-y: scroll;overflow-x: hidden">
            <table class="table" style="background: #FFFFFF; width: 100%">
                <tr>
                    <td><b>PATIENT NAME</b></td>
                    <td><b>REGISTRATION No.</b></td>
                    <td><b>AGE</b></td>
                    <td><b>GENDER</b></td>
                    <td><b>SPONSOR</b></td>
                    <td><b>ADDRESS</b></td>
                    <td><b>OCCUPATION</b></td>
                </tr>
                <?php
                        foreach($Patients as $details) :
                            $date1 = new DateTime($Today);
                            $date2 = new DateTime($details['Date_Of_Birth']);
                            $diff = $date1 -> diff($date2);
                            $age = $diff->y." Years, ";
                            $age .= $diff->m." Months, ";
                            $age .= $diff->d." Days";
                            $Occupation = $details['Occupation'];
                            echo "<tr>
                                        <td>".$details['Patient_Name']."</td>
                                        <td>".$Registration_ID."</td>
                                        <td>".$age."</td>
                                        <td>".$details['Gender']."</td>
                                        <td>".$details['Guarantor_Name']."</td>
                                        <td>".$details['Region']."/".$details['District']."</td>
                                        <td>".ucwords($Occupation)."</td>
                                        </tr>";
                        
                        endforeach;
                ?>
            </table>
            <center>
                <select name="" id="Select_Templates" class='Select_Templates' onchange='Select_Templates()' style='text-align: center;width:30%;height: 30px; display:inline'>
                        <option value="">PLEASE SELECT TEMPLATE</option>
                        <option value="Radiotherapy">Radiotherapy Records</option>
                        <option value="Brachytherapy">Brachytherapy Records</option>
                        <option value="ICU">Intensive Care Unit (ICU) </option>
                        <option value="Audiology">Audiology/Hearing Evaluation Preview</option>
                </select>
            </center>
        </div>
        <!-- <div class="box box-primary" style="height: 50px;overflow-y: hidden;overflow-x: scroll;"> -->
            <div class="box" id='load_dates' style="height: 55px;overflow-y: hidden;overflow-x: scroll; padding: 10px; margin-top: -20px">
            </div>
            <div class="box" id='load_icu_dates' style="height: 55px;overflow-y: hidden;overflow-x: scroll; padding: 10px; padding-bottom: -10px; display:none; margin-top: -20px;">
            </div>
        <!-- </div> -->
        <div class="box box-primary" style="height: 630px;overflow-y: scroll;overflow-x: hidden; margin-top: -16px;" id='view_patient_file'>

        </div>

<script>
    $(document).ready(function (e){
        Select_Templates();
        // $(".Select_Templates").select2();
        // $("#Employee_ID").select2();
    });


    function Select_Templates(){
        // alert("hiiiiiiiiii");
        Registration_ID = '<?= $Registration_ID ?>';
        Temp_Name = $("#Select_Templates").val();

        // if(Temp_Name == '' || Temp_Name == undefined){
        //     alert("PLEASE SELECT TEMPLATE NAME BEFORE PROCEEDING");
        //     exit();        // if(Temp_Name == '' || Temp_Name == undefined){
        //     alert("PLEASE SELECT TEMPLATE NAME BEFORE PROCEEDING");
        //     exit();
        // }
        // }
            $.ajax({
                    type: "GET",
                    url: "includes/ajax_get_templates_visits.php",
                    data: {
                        Temp_Name : Temp_Name,
                        Registration_ID : Registration_ID
                    },
                    cache: false,
                    success: function (response) {
                        $("#load_icu_dates").css("display","none");
                        document.getElementById('load_dates').innerHTML = response;
                        if(response.includes('NO')){
                            document.getElementById('view_patient_file').innerHTML = response;
                        }else if(response.includes('PLEASE')){
                            document.getElementById('view_patient_file').innerHTML = response;
                        }else{
                            datas = "<center><h3>PLEASE SELECT DATE TO GET STARTED</h3></center>";
                            document.getElementById('view_patient_file').innerHTML = datas;
                        }
                    }
                });
            }
    function Display_Branchy_notes(consultation_ID,Brachytherapy_ID){
        Temp_Name = $("#Select_Templates").val();
        Registration_ID = '<?= $Registration_ID ?>';
        $.ajax({
            type: "GET",
            url: "brachytherapy_pt_file.php",
            data: {
                consultation_ID : consultation_ID,
                Brachytherapy_ID : Brachytherapy_ID,
                Registration_ID:Registration_ID
            },
            cache: false,
            success: function (response) {
                $("#load_icu_dates").css("display","none");
                document.getElementById('view_patient_file').innerHTML = response;
            }
        });
    }


    function Display_Radiotherapy_notes(consultation_ID,Radiotherapy_ID){
        Temp_Name = $("#Select_Templates").val();
        Registration_ID = '<?= $Registration_ID ?>';
        $.ajax({
            type: "GET",
            url: "radiotherapy_pt_file.php",
            data: {
                consultation_ID : consultation_ID,
                Radiotherapy_ID : Radiotherapy_ID,
                Registration_ID:Registration_ID
            },
            cache: false,
            success: function (response) {
                $("#load_icu_dates").css("display","none");
                document.getElementById('view_patient_file').innerHTML = response;
            }
        });
    }

    function Display_ICU_Forms(Registration_ID,Form_Number){
        Temp_Name = 'ICU Dates';
        $.ajax({
            type: "GET",
            url: "includes/ajax_get_templates_visits.php",
            data: {
                Temp_Name : Temp_Name,
                Registration_ID : Registration_ID,
                Form_Number:Form_Number
            },
            cache: false,
            success: function (response) {            
                $("#load_icu_dates").css("display","block");
                document.getElementById('load_icu_dates').innerHTML = response;
            }
        });
    }


    function Display_ICU_notes(consultation_ID,Doc_ID,Registration_ID,Form_Number){
        if(Form_Number == 'tbl_icu_form_one'){
            $.ajax({
                type: "GET",
                url: "icu/form_one_file_preview.php",
                data: {
                    Temp_Name : Temp_Name,
                    Registration_ID : Registration_ID,
                    Form_Number:Form_Number
                },
                cache: false,
                success: function (response) {            
                    document.getElementById('view_patient_file').innerHTML = response;
                }
            });
        }else if(Form_Number == 'tbl_icu_form_two'){

        }else if(Form_Number == 'tbl_icu_form_three'){

        }else if(Form_Number == 'tbl_icu_form_four'){
            $.ajax({
                type: "GET",
                url: "icu/form_four_file_preview.php",
                data: {
                    Temp_Name : Temp_Name,
                    Registration_ID : Registration_ID,
                    Form_Number:Form_Number
                },
                cache: false,
                success: function (response) {            
                    document.getElementById('view_patient_file').innerHTML = response;
                }
            });
        }else if(Form_Number == 'tbl_icu_form_five'){

        }else if(Form_Number == 'tbl_icu_form_six'){

        }

        $("#load_icu_dates").css("display","block");

    }

    function audiology_form(payment_item_cache_list_id,Registration_ID){
        Temp_Name = $("#Select_Templates").val();
        // Registration_ID = '<?= $Registration_ID ?>';
        $.ajax({
            type: "GET",
            url: "audiology_pt_file.php",
            data: {
                payment_item_cache_list_id : payment_item_cache_list_id,
                Registration_ID:Registration_ID
            },
            cache: false,
            success: function (response) {
                $("#load_icu_dates").css("display","none");
                document.getElementById('view_patient_file').innerHTML = response;
            }
        });
    }

</script>

<?php
include("includes/footer.php");
?>