<?php
include("./includes/header.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Admission_Works'])) {
        if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
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

<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Admission_Works'] == 'yes') {
        ?>
        <a href='./searchlistofoutpatientadmission.php?section=Admission&ContinuePatientBilling=ContinuePatientBillingThisPage' class='art-button-green'>
            BACK
        </a>
        <?php
    }
}
?>

<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $Today = $row['today'];
    $Start = date("Y-m-d", strtotime($Today));
    $Start_Date = $Start . ' 00:00:00';
}
?>


<br/><br/>
<center>
    <table width=100% style="background-color:white;">
        <tr>
            <td>
                <input type='text' id='Search_Value' name='Search_Value' style='text-align: center;' placeholder='Enter Patient Name' autocomplete='off' oninput='Get_Filtered_Patients_Filter()'>
            </td>
            <td>
                <input type='text' id='Search_Value_by_number' name='Search_Value_by_number' style='text-align: center;' placeholder='Enter Patient Number' autocomplete='off' oninput='Get_Filtered_Patients_Filter()'>
            </td>
            <td style='text-align: right;'>Sponsor</td>
            <td>
                <select name="Sponsor_ID" id="Sponsor_ID">
                    <option selected="selected" value="All">All</option>
                    <?php
                    $select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($select);
                    if ($num > 0) {
                        while ($data = mysqli_fetch_array($select)) {
                            ?>
                            <option value="<?php echo $data['Sponsor_ID']; ?>"><?php echo $data['Guarantor_Name']; ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </td>
            <td style='text-align: right;'><b>From</b></td>
            <td>
                <input type='text' name='Start_Date' id='Date_From' style='text-align: center;' placeholder='Start Date' readonly='readonly' value='<?php echo $Start_Date; ?>'>
            </td>
            <td style='text-align: right;'><b>To</b></td>
            <td>
                <input type='text' name='Start_Date' id='Date_To' style='text-align: center;' placeholder='End Date' readonly='readonly' value='<?php echo $Today; ?>'>
            </td>
            <td style='text-align: center;'><input name='Filter' type='button' value='FILTER' class='art-button-green' onclick='Get_Filtered_Patients()'></td>
        </tr>
    </table>
</center>
<div id="admitpatientDiv" style="width:500px;overflow:hidden;" >
</div>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
                $('#Date_From').datetimepicker({
                    dayOfWeekStart: 1,
                    lang: 'en',
//startDate:    'now'
                });
                $('#Date_From').datetimepicker({value: '', step: 2});
                $('#Date_To').datetimepicker({
                    dayOfWeekStart: 1,
                    lang: 'en',
//startDate:'now'
                });
                $('#Date_To').datetimepicker({value: '', step: 2});
</script>
<!--End datetimepicker-->
<br/>
<style>
    /*		table,tr,td{
                    border-collapse:collapse !important;
                    border:none !important;
                    
                    }
            tr:hover{
            background-color:#eeeeee;
            cursor:pointer;
            }*/

    .linkstyle{
        color:#0F3948;
        font-size: 14px;
    }

    .linkstyle:hover{
        cursor:pointer;
    }
</style>
</style> 
<fieldset style='overflow-y:scroll; height:400px; background-color:white;' id='Patients_Fieldset_List';> 

    <legend  align="right" style="background-color:#006400;color:white;padding:5px;">LIST OF CHECKED IN PATIENTS</legend>	
    <table width=100%>
        <tr><td colspan="9"><hr></td></tr>
        <tr>
            <td width=5%><b>SN</b></td>
            <td><b>PATIENT NAME</b></td>
            <td width=10%><b>PATIENT#</b></td>
            <td width=10%><b>SPONSOR</b></td>
            <td width=10%><b>PHONE#</b></td>
            <td width=15%><b>CHECK-IN TYPE</b></td>
            <td width=20%><b>CHECKED-IN DATE</b></td>
            <td><b>EMPLOYEE NAME</b></td>
        </tr>
        <tr><td colspan="9"><hr></td></tr>	

    </table>
</fieldset>

<!-- dialog to show the patient details from NHIF after Authorization -->
<div id="verification_dialog" style="width:100%;overflow:hidden;display: none;font-size: 20px;" >
  <div class="form-group">
    <strong>Patient Name : </strong><strong><span id="ver_patient_name"></span></strong>
  </div>
  <div class="form-group">
    <strong>Card Number : </strong><strong><span id="ver_card_no"></span></strong>
  </div>
  <div class="form-group">
    <strong>Status : </strong><strong><span id="ver_status"></span></strong>
  </div>
  <div class="form-group">
    <strong>Expire Date : </strong><strong><span id="ver_expire_date"></span></strong>
  </div>
  <div class="form-group">
    <strong>Authorization No : </strong><strong><span id="ver_authorization_no"></span></strong>
  </div>
  <div class="form-group">
    <strong>Package : </strong><strong><span id="ver_package"></span></strong>
  </div>

<div class="form-group">
    <strong>Scheme Name : </strong><strong><span id="ver_scheme_name"></span></strong>
  </div>
</div>

<script src="js/token.js"></script>
<script>
    function Get_Filtered_Patients() {

        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;
        var Search_Value = document.getElementById("Search_Value").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        document.getElementById('Patients_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';


        if (window.XMLHttpRequest) {
            My_Object_Filter_Patient = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            My_Object_Filter_Patient = new ActiveXObject('Micrsoft.XMLHTTP');
            My_Object_Filter_Patient.overrideMimeType('text/xml');
        }

        My_Object_Filter_Patient.onreadystatechange = function () {
            data6 = My_Object_Filter_Patient.responseText;
            if (My_Object_Filter_Patient.readyState == 4 && My_Object_Filter_Patient.status == 200) {
                document.getElementById('Patients_Fieldset_List').innerHTML = data6;
            }
        }; //specify name of function that will handle server response........


        My_Object_Filter_Patient.open('GET', 'Get_Checked_Patients_List_inpatient.php?Date_From=' + Date_From + '&Date_To=' + Date_To + '&Sponsor_ID=' + Sponsor_ID + '&Search_Value=' + Search_Value, true);

        My_Object_Filter_Patient.send();
    }
</script>


<script>
    function Get_Filtered_Patients_Filter() {

        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;
        var Search_Value = document.getElementById("Search_Value").value;
        var Search_Value_by_number = document.getElementById("Search_Value_by_number").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var data6, My_Object_Filter_Patient;

        document.getElementById('Patients_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

        if (window.XMLHttpRequest) {
            My_Object_Filter_Patient = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            My_Object_Filter_Patient = new ActiveXObject('Micrsoft.XMLHTTP');
            My_Object_Filter_Patient.overrideMimeType('text/xml');
        }

        My_Object_Filter_Patient.onreadystatechange = function () {
            data6 = My_Object_Filter_Patient.responseText;
            if (My_Object_Filter_Patient.readyState == 4 && My_Object_Filter_Patient.status == 200) {
                document.getElementById('Patients_Fieldset_List').innerHTML = data6;
            }
        }; //specify name of function that will handle server response........

        My_Object_Filter_Patient.open('GET', 'Get_Checked_Patients_List_inpatient.php?Date_From=' + Date_From + '&Date_To=' + Date_To + '&Search_Value=' + Search_Value + '&Sponsor_ID=' + Sponsor_ID+"&Search_Value_by_number="+Search_Value_by_number, true);
        My_Object_Filter_Patient.send();
    }
</script>
<script type='text/javascript'>

    // $(document).ready(function () {
    //     $("#admitpatientDiv").dialog({autoOpen: false, width: '60%', title: 'Admit a patient', modal: true});
    // });
</script>       
<script>
    function Show_Admit_Patient(Registration_ID, Patient_Name, sponsor, has_claim, claim_form_number) {
        $("#Registration_ID").val(Registration_ID)
        clear();
        if (sponsor != 'cash') {
            $('#claim_form_number').removeAttr('readonly');
            $('#claim_form_number').attr('required', true);
            $('#need_claim').val('yes');

            if (has_claim == '1') {
                $('#claim_form_number').val(claim_form_number);
            }
        } else {
            $('#claim_form_number').removeAttr('required');
            $('#claim_form_number').attr('readonly', true);
            $('#need_claim').val('no');
        }
        $.ajax({
            type:'POST',
            url:'Ajax_patient_debt.php',
            data:{Registration_ID:Registration_ID,directadmitPT:''},
            success:function(responce){
                $("#admitpatientDiv").dialog({
                    title: "ADMIT "+Patient_Name,
                    width: '60%',
                    height: 450,
                    modal: true,
                });
                $("#admitpatientDiv").html(responce)
            }
        })


        // document.getElementById("patient_id").value = Registration_ID;
        // $("#admitpatientDiv").dialog('option', 'title', Patient_Name + '  ' + '#.' + Registration_ID);
        // alert(Registration_ID + ' ' + Patient_Name);
        // $("#admitpatientDiv").dialog('open');
    }
</script>
<script>
    function clear() {
        $('#claim_form_number').val('');
        $('#ToBeAdmitted').val('');
        $('#ToBeAdmittedReason').val('');
        $('#remark').val('');
        $('#patient_id').val('');

    }
</script> 
<input type="text" id="Registration_ID" hidden="hidden" value=""/>
<script>
       function check_if_admited_or_in_admit_list(Authorization_No, Registration_ID){
        
        // var Registration_ID = $("#Registration_ID").val();
        if(Authorization_No =='Mandatory'){
            var AuthorizationNo =document.getElementById("AuthorizationNo").value;
               if(AuthorizationNo ==''){
                   alert("Please Enter authorization Number");
                   exit;
               } 
        }
       $.ajax({
           type:'GET',
           url:'check_if_admited_or_in_admit_list.php',
           data:{Registration_ID:Registration_ID, Registration_ID:Registration_ID},
           success:function (data){
               console.log(data)
               if(data=="admit_list"){
                   alert("The Patient Is on PATIENTS TO ADMIT LIST \n Please select patient from the list and then admit to your Ward");
               }else if(data=="free_to_admit"){
                   admitPatient(Authorization_No, Registration_ID);
               }else{
                   alert("Patient Arleady Admitted to ~~"+data.toUpperCase());
               }
           },
           error:function(x,y,z){
              console.log(z); 
           }
       });
    }
    function admitPatient(Authorization_No, Registration_ID) {
       
        var myobj, data;

		var ToBeAdmitted = document.getElementById("ToBeAdmitted").value;
		var ToBeAdmittedReason = document.getElementById("ToBeAdmittedReason").value;
		var remark = document.getElementById("remark").value;
		// var Registration_ID = document.getElementById("patient_id").value;
		var claim_form_number = document.getElementById("claim_form_number").value;
		var need_claim = document.getElementById("need_claim").value;
        // var AuthorizationNo =document.getElementById("AuthorizationNo").value;
        var AuthorizationNo ='';
        var select_package='';
        if(Authorization_No =='Mandatory'){
            var AuthorizationNo =document.getElementById("AuthorizationNo").value;
            var select_package = document.getElementById("select_package").value;
               if(AuthorizationNo ==''){
                   
                   alert("Please Enter authorization Number");
                   $("#AuthorizationNo").css("border", "2px red solid");
                   exit;
               } 
        }

        if (confirm("Are you sure you want to add a patient to admission list?")) {

            if (window.XMLHttpRequest) {
                myobj = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myobj = new ActiveXObject('Micrsoft.XMLHTTP');
                myobj.overrideMimeType('text/xml');
            }

            myobj.onreadystatechange = function () {
                data = myobj.responseText;
                if (myobj.readyState == 4) {
                    // alert(data);
                    if (data == '1') {
                        alert("Patient ready to be admitted!");

                        window.location = 'admit.php?Registration_ID='+Registration_ID;
                    } else {
                        alert('An error has occured try again or contact system administrator');
                    }
                }
            };

            myobj.open('GET', 'emergencyAdmission.php?ToBeAdmitted=' + ToBeAdmitted + '&ToBeAdmittedReason=' + ToBeAdmittedReason + '&remark=' + remark + '&Registration_ID=' + Registration_ID + '&claim_form_number=' + claim_form_number+'&AuthorizationNo='+AuthorizationNo+'&package_id='+select_package, true);
            myobj.send();
        }
    }
</script>

<link rel="stylesheet" href="style.responsive.css" media="all">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script> <!--<script src="js/jquery-ui-1.10.1.custom.min.js"></script>-->
<script src="script.responsive.js"></script>
<?php
include("./includes/footer.php");
?>