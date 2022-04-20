<script src='js/functions.js'></script>
<style type="text/css">
    /*.labefor{display:block;width: 100% }*/
    .labefor:hover{background-color: #a8d1ff;cursor: pointer}
    label.labefor { width: 100%; 
    }
                #spu_lgn_tbl{
                    width:100%;
                    border:none!important;
                }
                #spu_lgn_tbl tr, #spu_lgn_tbl tr td{
                    border:none!important;
                    padding: 5px;
                    font-size: 14PX;
                }
</style>

<?php
    include_once("./includes/header.php");
    include_once("./includes/connection.php");

    if (isset($_SESSION['userinfo'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    } else {
        $Employee_Name = 'Unknown Officer';
        $Employee_ID = 0;
    }

    //get branch id
    if (isset($_SESSION['userinfo']['Branch_ID'])) {
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    } else {
        $Branch_ID = 0;
    }

    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    $Sub_Department_Name = $_SESSION['Admission'];
    
    $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Date_From = $Filter_Value.' 00:00';
    $Date_To = $Current_Date_Time;

?>

<a href="nurse_station_menu.php?section=Nurse&NurseWorks=NurseWorksThisPage" class='art-button-green'>BACK</a>
<a href="completed_opd_nurse_notes.php?NurseDuty=NurseDutyThisForm&frompage=addmission" style="background: #5df2f2; color: #000; font-weight: bold; border-radius: 2px;" class='art-button-green'>PREVIOUS DUTIES NOTES</a>
<br>
<center><p style="margin-top:10px;color: #0079AE;font-weight:bold"><i>Click ADD NURSE NOTES to write your notes and this Document will remain active </i></p></center>
<form action='#' method='POST' name='myForm' id='myForm' >
    <fieldset>
        <legend align='center'><b>OPD NURSING DUTY HANDLER</b></legend>
        <table  id="spu_lgn_tbl" width=100%>
            <tr  id="select_clinic">

                <td style='text-align: right;'>Nurse On Duty</td>
                <td>
                    <?php
                        echo "<input type='text' readonly='readonly' name='current_nurse' value='{$Employee_Name}'>";
                    ?>
                </td>
                <td style='text-align: right;'>Nurse Taking Over </td>
                <td>
                    <select name='duty_nurse' id='duty_nurse' style='text-align: center;width:100%;display:inline' required='required'>
                        <option value='' selected='selected'>Select Nurse to Take Over</option>
                            <?php
                            $qr = "SELECT * FROM tbl_employee WHERE employee_type='Nurse'";
                            $employee_results = mysqli_query($conn,$qr);
                            while ($employee_rows = mysqli_fetch_assoc($employee_results)) {
                                $Employee_selected_ID = $employee_rows['Employee_ID'];
                                $Employee_selected_Name = $employee_rows['Employee_Name'];
                                ?>
                                <option value='<?php echo $Employee_selected_ID; ?>'><?php echo $Employee_selected_Name; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                </td>
                <td width='10%' style='text-align: right;'>Working Clinic</td>
                <td width='20%'>
                <select name='duty_ward' id='ward_ID' style='text-align: center;width:100%;display:inline' required='required' onchange="Check_for_Notes_Set()">
                <option value="" selected='selected'>Select Working Clinic</option>

                            <?php
                            $qr = "SELECT Clinic_Name, Clinic_ID FROM tbl_clinic WHERE Clinic_Status = 'Available'";
                            $ward_results = mysqli_query($conn,$qr);
                            while ($ward_rows = mysqli_fetch_assoc($ward_results)) {
                                $Clinic_ID = $ward_rows['Clinic_ID'];
                                $Clinic_Name = $ward_rows['Clinic_Name'];
                                ?>
                                <option  value='<?php echo $Clinic_ID; ?>'><?php echo $Clinic_Name; ?></option>

                                <?php
                            }
                            ?>
                        </select>
                </td>

                <?php

                // die("SELECT duty_ID FROM tbl_nurse_duties WHERE Ward_Type = '$Ward_Type' AND select_round = '$select_round' AND duty_ward = '$Hospital_Ward_ID' AND Process_Status = 'pending' AND DATE(duty_handled) = CURDATE()");
                // mysqli_query($conn, "SELECT duty_ID FROM tbl_nurse_duties WHERE Ward_Type = '$Ward_Type' AND select_round = '$select_round' AND duty_ward = '$Hospital_Ward_ID' AND Process_Status = 'pending' AND DATE(duty_handled) = CURDATE()");

                ?>
                
            </tr>
            <tr   id="select_clinic">
                <td width='10%' style='text-align: right;'>Shift</td>
                <td width='20%'>
                <select name='select_round' id='select_round'  onchange='Check_for_Notes_Set()' style='text-align: center;width:100%; height: 32px; border-radius: 5px; font-size: 15px;' required='required'>
                <option value=''>Select Shift Attended</option>
                <option>Morning Shift</option>
                <option>Night Shift</option>
                        </select>
                </td>
                <td width='10%' style='text-align: right;'>Wing type</td>
                <td width='20%'>
                <select name='Ward_Type' id='Ward_Type' onchange='Check_for_Notes_Set()' style='text-align: center;width:100%; height: 32px; border-radius: 5px; font-size: 15px;' required='required'>
                <option value=''>Select Wing Type To continue</option>
                <option>Male</option>
                <option>Female</option>
                <option>Observation</option>
                <option>Triage</option>
                <option>Paediatric</option>
                        </select>
                </td>
            </tr>            
            <tr style='background: #ccc;'>
                <th colspan='6'>CURRENT DUTIES' DETAILS </th>                
            </tr>
            <tr>
                <td width='10%' style='text-align: right;'>Total Current Patients</td>
                <td><input type="number" style='width: 100%; padding: 5px;' name="current_inpatient" id='current_inpatient' title='Total Number of Patient' onkeyup='autosave_notes()' placeholder="Total Current Patients" required></td>
                <td width='10%' style='text-align: right;'>Total Checked In Patients</td>
                <td><input type="number" style='width: 100%; padding: 5px;' name="received_inpatient" id='received_inpatient' onkeyup='autosave_notes()' placeholder="Total Checked In Patients"></td>
                <td width='10%' style='text-align: right;'>Total Discharged Patients</td>
                <td><input type="number" style='width: 100%; padding: 5px;' name="discharged_inpatient" id='discharged_inpatient' onkeyup='autosave_notes()' placeholder="Total Discharged Patients"></td>
            </tr>
            <tr>
                <td style='text-align: right;'>Refugees</td>
                <td><input type='text' name='Refugees' id='Refugees' value='' onkeyup='autosave_notes()' placeholder='Refugees'></td>
                <td style='text-align: right;'>Total Deaths</td>
                <td><input type='text' name='death_inpatient' id='death_inpatient' value='' onkeyup='autosave_notes()' placeholder='Total Deaths' value="<?php echo $Total_death; ?>"></td>                <td width='10%' style='text-align: right;'>Prisoners</td>
                <td>
                    <input type="number" style='width: 100%; padding: 5px;' name="Prisoner" id='Prisoner' onkeyup='autosave_notes()' placeholder="Prisoners">

                </td>
            </tr>
            <tr>
                <td width='10%' style='text-align: right;'>Total Serious Patients</td>
                <td><input type="number" style='width: 100%; padding: 5px;' name="serious_inpatient" id='serious_inpatient' onkeyup='autosave_notes()' placeholder="Total Serious Patients"></td>
                <td width='10%' style='text-align: right;'>Lodgers</td>
                <td><input type="number" style='width: 100%; padding: 5px;' name="lodgers" id='lodgers' onkeyup='autosave_notes()' placeholder="Lodgers"></td>
                <td width='10%' style='text-align: right;'>Abscondees</td>
                <td><input type="number" style='width: 100%; padding: 5px;' name="Abscondees" id='Abscondees' onkeyup='autosave_notes()' placeholder="Abscondees"></td>
            </tr>
            <tr>
                <td width='10%' style='text-align: right;'>Total Refferal INs</td>
                <td><input type="number" style='width: 100%; padding: 5px;' name="transferIn" id='transferIn' onkeyup='autosave_notes()' placeholder="Total Refferal INs"></td>
                <td width='10%' style='text-align: right;'>Total Refferal OUTs</td>
                <td><input type="number" style='width: 100%; padding: 5px;' name="transferOut" id='transferOut' onkeyup='autosave_notes()' placeholder="Total Referral OUTs"></td>
            </tr>
            <tr>
                <td  style='text-align: right;'>Nurse Notes</td>
                <td colspan='5'>
                <textarea name='general_notes' id='general_notes' readonly='readonly' required style='height: 270px;text-align: left; padding: 30px;'></textarea></td>
            </tr>
            <tr>
                <td colspan='6'>
                    <center>
                    <input type="button" value="ADD NURSE NOTES" title="Click ADD NURSE NOTES if you want to add Individual Notes" style="background: #5df2f2; color: #000; font-weight: bold; padding: 10px; border-radius: 7px;" name='submit_form' id='submit_form' onclick='open_nurse()' class='art-button-green'>
                        <input type='button'  style="background: #5df2f2; color: #000; font-weight: bold; padding: 10px; border-radius: 7px;" title="Click SAVE & POST NURSE HANDLING NOTES only if you Submit the whole Ducument" name='submit_form' id='submit_form' onclick='save_notes_duty()' value='   SAVE & POST NURSE HANDLING NOTES   ' class='art-button-green'>
                    </center>
                </td>
        </table> 
        </center>
<center><p style="margin-top:10px;color: #0079AE;font-weight:bold"><i>Click ADD NURSE NOTES to write your notes and this Document will remain active, Click SAVE & POST NURSE HANDLING NOTES to Submit the whole Document </i></p></center>
    </fieldset>
    <div id="Submit_data">
    <table width="100%" border=0 style='border: none; padding: 10px;' id="spu_lgn_tbl">
        <tr>
            <th style='width: 10%; text-align: right;'>Nurse Notes </th>
            <td>
            <textarea name='nurse_notes' id='nurse_notes' style='height:150px;' required></textarea>
            </td>
        </tr>

        <tr >
            <td style="text-align: center;" colspan='2'>
                <input type="button" name="Submit_Information" id="Submit_Information" value="SAVE NOTES"  style="background: #5df2f2; color: #000; font-weight: bold; padding: 10px; border-radius: 7px;" class="art-button-green" onclick="update_Notes_duty()">
                <input type="button" name="Cancel" id="Cancel"  style="background: #5df2f2; color: #000; font-weight: bold; padding: 10px; border-radius: 7px;" value="CANCEL" class="art-button-green" onclick="Close_Submit_Dialog()">
            </td>
        </tr>
    </table>
</div>
    <script>
        function autosave_notes() {
            var select_round = $("#select_round").val();
            var Prisoner = $("#Prisoner").val();
            var transferOut = $("#transferOut").val();
            var transferIn = $("#transferIn").val();
            var Abscondees = $("#Abscondees").val();
            var lodgers = $("#lodgers").val();
            var serious_inpatient = $("#serious_inpatient").val();
            var death_inpatient = $("#death_inpatient").val();
            var Refugees = $("#Refugees").val();
            var discharged_inpatient = $("#discharged_inpatient").val();
            var received_inpatient = $("#received_inpatient").val();
            var current_inpatient = $("#current_inpatient").val();
            var duty_nurse = $("#duty_nurse").val();
            var Ward_Type = $("#Ward_Type").val();
            var duty_ward = $("#ward_ID").val();
            var nurse_notes = $("#nurse_notes").val();
            $.ajax({
                type: "POST",
                url: "autosave_opd_nurse_notes.php",
                data: {Ward_Type:Ward_Type,current_inpatient:current_inpatient,received_inpatient:received_inpatient,discharged_inpatient:discharged_inpatient,Refugees:Refugees,death_inpatient:death_inpatient,serious_inpatient:serious_inpatient,lodgers:lodgers,Abscondees:Abscondees,transferIn:transferIn,transferOut:transferOut,Prisoner:Prisoner,nurse_notes:nurse_notes,select_round:select_round,duty_nurse:duty_nurse,duty_ward:duty_ward},
                cache: false,
                success: function (response) {
                    if(response == 200){
                        Check_for_Notes_Set();
                    }else{
                        alert("Failed to save Notes, Please Contact Administrator for Further Assistance");
                        exit();
                    }
                }
            });
        }
        function update_Notes_duty() {
            var select_round = $("#select_round").val();
            var Prisoner = $("#Prisoner").val();
            var transferOut = $("#transferOut").val();
            var transferIn = $("#transferIn").val();
            var Abscondees = $("#Abscondees").val();
            var lodgers = $("#lodgers").val();
            var serious_inpatient = $("#serious_inpatient").val();
            var death_inpatient = $("#death_inpatient").val();
            var Refugees = $("#Refugees").val();
            var discharged_inpatient = $("#discharged_inpatient").val();
            var received_inpatient = $("#received_inpatient").val();
            var current_inpatient = $("#current_inpatient").val();
            var duty_nurse = $("#duty_nurse").val();
            var Ward_Type = $("#Ward_Type").val();
            var duty_ward = $("#ward_ID").val();
            var nurse_notes = $("#nurse_notes").val();


            if(Ward_Type == ''){
                $('select[name^="Ward_Type"]').focus();
                $("#Ward_Type").css("border","4px solid red");
                $("#Ward_Type").focus()
            }
            if(select_round == ''){
                $("#select_round").css("border","4px solid red");
                $("#select_round").focus()
                exit();

            }
            // if(nurse_notes == ''){
            //     $("#nurse_notes").css("border","2px solid red");
            //     $("#nurse_notes").focus()
            //     exit();
            // }
            $.ajax({
                type: "POST",
                url: "update_opd_nurse_notes.php",
                data: {Ward_Type:Ward_Type,current_inpatient:current_inpatient,received_inpatient:received_inpatient,discharged_inpatient:discharged_inpatient,Refugees:Refugees,death_inpatient:death_inpatient,serious_inpatient:serious_inpatient,lodgers:lodgers,Abscondees:Abscondees,transferIn:transferIn,transferOut:transferOut,Prisoner:Prisoner,nurse_notes:nurse_notes,select_round:select_round,duty_nurse:duty_nurse,duty_ward:duty_ward},
                cache: false,
                success: function (response) {
                    if(response == 200){
                        alert("Nurse Notes was Saved Successfully!");
                        $("#Submit_data").dialog("close");
                        Check_for_Notes_Set();
                    }else{
                        alert("Failed to save Notes, Please Contact Administrator for Further Assistance");
                    }
                }
            });
        }
        function Check_for_Notes_Set(){
            var ward_ID = $("#ward_ID").val();
            var select_round = $("#select_round").val();
            var Ward_Type = $("#Ward_Type").val();
            $.ajax({
                type:'GET',
                url:'ajax_check_opd_nursing_handle.php',
                data:{ward_ID:ward_ID, select_round:select_round, Ward_Type:Ward_Type},
                success:function(responce){
                    $("#general_notes").val(responce);
                    check_for_Current_Inpatient();
                    check_for_Current_Admitted();
                    check_for_Current_Discharged();
                    check_for_Current_Refugees();
                    check_for_Current_Serious();
                    check_for_Current_Lodgers();
                    check_for_Current_Death();
                    check_for_Current_Abscondeees();
                    check_for_Current_Prisoner();
                    check_for_Current_Ins();
                    check_for_Current_Outs();
                    // check_for_Duty_Nurse();
                }
            });
        }
        function check_for_Current_Inpatient(){
            var ward_ID = $("#ward_ID").val();
            var select_round = $("#select_round").val();
            var Ward_Type = $("#Ward_Type").val();
            $.ajax({
                type: "GET",
                url: "current_duty_outpatient.php",
                data: {ward_ID:ward_ID,select_round:select_round,Ward_Type:Ward_Type,current_inpatient:'current_inpatient'},
                success: function (response) {
                    var results = response;
               document.getElementById('current_inpatient').value = results;

                }
            });
        }
        function check_for_Current_Admitted(){
            var ward_ID = $("#ward_ID").val();
            var select_round = $("#select_round").val();
            var Ward_Type = $("#Ward_Type").val();
            $.ajax({
                type: "GET",
                url: "current_duty_outpatient.php",
                data: {ward_ID:ward_ID,select_round:select_round,Ward_Type:Ward_Type,received_inpatient:'received_inpatient'},
                success: function (response) {
                    var results = response;
                    document.getElementById('received_inpatient').value = results;
                }
            });
        }
        function check_for_Current_Discharged(){
            var ward_ID = $("#ward_ID").val();
            var select_round = $("#select_round").val();
            var Ward_Type = $("#Ward_Type").val();
            $.ajax({
                type: "GET",
                url: "current_duty_outpatient.php",
                data: {ward_ID:ward_ID,select_round:select_round,Ward_Type:Ward_Type,discharged_inpatient:'discharged_inpatient'},
                success: function (response) {
                    var results = response;
                    document.getElementById('discharged_inpatient').value = results;
                }
            });
        }
        function check_for_Current_Refugees(){
            var ward_ID = $("#ward_ID").val();
            var select_round = $("#select_round").val();
            var Ward_Type = $("#Ward_Type").val();
            $.ajax({
                type: "GET",
                url: "current_duty_outpatient.php",
                data: {ward_ID:ward_ID,select_round:select_round,Ward_Type:Ward_Type,Refugees:'Refugees'},
                success: function (response) {
                    var results = response;
                    document.getElementById('Refugees').value = results;
                }
            });
        }
        function check_for_Current_Serious(){
            var ward_ID = $("#ward_ID").val();
            var select_round = $("#select_round").val();
            var Ward_Type = $("#Ward_Type").val();
            $.ajax({
                type: "GET",
                url: "current_duty_outpatient.php",
                data: {ward_ID:ward_ID,select_round:select_round,Ward_Type:Ward_Type,serious_inpatient:'serious_inpatient'},
                success: function (response) {
                    var results = response;
                    document.getElementById('serious_inpatient').value = results;
                }
            });
        }
        function check_for_Current_Lodgers(){
            var ward_ID = $("#ward_ID").val();
            var select_round = $("#select_round").val();
            var Ward_Type = $("#Ward_Type").val();
            $.ajax({
                type: "GET",
                url: "current_duty_outpatient.php",
                data: {ward_ID:ward_ID,select_round:select_round,Ward_Type:Ward_Type,lodgers:'lodgers'},
                success: function (response) {
                    var results = response;
                    document.getElementById('lodgers').value = results;
                }
            });
        }

        function check_for_Current_Death(){
            var ward_ID = $("#ward_ID").val();
            var select_round = $("#select_round").val();
            var Ward_Type = $("#Ward_Type").val();
            $.ajax({
                type: "GET",
                url: "current_duty_outpatient.php",
                data: {ward_ID:ward_ID,select_round:select_round,Ward_Type:Ward_Type,death_inpatient:'death_inpatient'},
                success: function (response) {
                    var results = response;
                    document.getElementById('death_inpatient').value = results;
                }
            });
        }
        function check_for_Current_Abscondeees(){
            var ward_ID = $("#ward_ID").val();
            var select_round = $("#select_round").val();
            var Ward_Type = $("#Ward_Type").val();
            $.ajax({
                type: "GET",
                url: "current_duty_outpatient.php",
                data: {ward_ID:ward_ID,select_round:select_round,Ward_Type:Ward_Type,Abscondees:'Abscondees'},
                success: function (response) {
                    var results = response;
                    document.getElementById('Abscondees').value = results;
                }
            });
        }
        function check_for_Current_Prisoner(){
            var ward_ID = $("#ward_ID").val();
            var select_round = $("#select_round").val();
            var Ward_Type = $("#Ward_Type").val();
                $.ajax({
                    type: "GET",
                    url: "current_duty_outpatient.php",
                    data: {ward_ID:ward_ID,select_round:select_round,Ward_Type:Ward_Type,Prisoner:'Prisoner'},
                    success: function (response) {
                    var results = response;
                    document.getElementById('Prisoner').value = results;
                    }
                });
            }
            function check_for_Current_Ins(){
            var ward_ID = $("#ward_ID").val();
            var select_round = $("#select_round").val();
            var Ward_Type = $("#Ward_Type").val();
                $.ajax({
                    type: "GET",
                    url: "current_duty_outpatient.php",
                    data: {ward_ID:ward_ID,select_round:select_round,Ward_Type:Ward_Type,transferIn:'transferIn'},
                    success: function (response) {
                        var results = response;
                        document.getElementById('transferIn').value = results;
                    }
                });
            }
            function check_for_Current_Outs(){

            var ward_ID = $("#ward_ID").val();
            var select_round = $("#select_round").val();
            var Ward_Type = $("#Ward_Type").val();
                $.ajax({
                    type: "GET",
                    url: "current_duty_outpatient.php",
                    data: {ward_ID:ward_ID,select_round:select_round,Ward_Type:Ward_Type,transferOut:'transferOut'},
                    success: function (response) {
                    var results = response;
                    document.getElementById('transferOut').value = results;
                    }
                });
            }
        function Close_Submit_Dialog(){
            $("#Submit_data").dialog("close");
        }
        function save_notes_duty(){
            var select_round = $("#select_round").val();
            var duty_nurse = $("#duty_nurse").val();
            var Ward_Type = $("#Ward_Type").val();
            var duty_ward = $("#ward_ID").val();
            var general_notes = $("#general_notes").val();



            if(Ward_Type == ''){
                $('select[name^="Ward_Type"]').focus();
                $("#Ward_Type").css("border","4px solid red");
                $("#Ward_Type").focus()
            }
            if(select_round == ''){
                $("#select_round").css("border","4px solid red");
                $("#select_round").focus()
            }
            if(duty_nurse == ''){
                alert("Please Select Nurse taking over before Saving");
                $("#duty_nurse").css("border","2px solid red");
                $("#duty_nurse").focus()
                exit();
            }

            if(current_inpatient < 0 || transferOut < 0 || received_inpatient < 0 || transferIn < 0 || Prisoner < 0 || serious_inpatient < 0 || discharged_inpatient < 0 ){
                alert("Only Intergers and No Negatives Allowed");
            }
            
            if(general_notes == ''){
                $("#general_notes").css("border","2px solid red");
                $("#general_notes").focus()
                exit();
            }
            
            if(confirm("Bonyeza Cancel kama kuna marekebisho unahitaji kufanya, Otherwise bonyeza OK kukamilisha Nurse Handling Report")){
                $.ajax({
                    url: "save_opd_notes_duty.php",
                    type: "GET",
                    data: {Ward_Type:Ward_Type,select_round:select_round,duty_nurse:duty_nurse,duty_ward:duty_ward},
                    cache: false,
                    success: function(responce){
                        document.location = 'completed_opd_nurse_notes.php?Clinic_ID='+duty_ward;
                    }
                });

            }
        }

        function open_nurse(){
            var duty_ward = $("#ward_ID").val();

            if(duty_ward > 0){
                $("#Submit_data").dialog("open");
            }else{
                alert("Please Select your Working Current Clinic");
            }
        }
    </script>

<script>
    $(document).ready(function (e){
        $("#duty_nurse").select2();
        $("#working_department").select2();
        // $("#select_round").select2();
        $("#ward_ID").select2();
        // $("#Ward_Type").select2();
    });
    
</script>
<script>
    $(document).ready(function () {
        var Employee_Name = '<?= $Employee_Name ?>';
        $("#Submit_data").dialog({autoOpen: false, width: '60%', height: 285, title: 'NURSE HANDLING NOTES FILLED BY '+Employee_Name, modal: true});
    });
</script>
<link rel="stylesheet" href="css/uploadfile.css" media="screen">
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />   
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script><script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery-ui.js"></script>
<script src="css/scripts.js"></script>
<script src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/ui.notify.css" media="screen">
<script src="js/jquery.notify.min.js"></script> 

<?php include("./includes/footer.php"); ?>