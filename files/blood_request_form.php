<?php
   include("./includes/connection.php");
   include("./includes/header.php");
   

   session_start();
    if (isset($_GET['Date_From'])) {
        $Date_From = $_GET['Date_From'];
    } else {
        $Date_From = '';
    }
    
    
    if (isset($_GET['Date_To'])) {
        $Date_To = $_GET['Date_To'];
    } else {
        $Date_To = '';
    }


    $Registration_ID = $_GET['Registration_ID'];
    $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
    // $Employee_ID = $_GET['Employee_ID'];
    $consultation_ID = $_GET['consultation_id'];
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    
    
    $Consent_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Consent_ID FROM tbl_consert_blood_forms_details WHERE  consultation_ID = '$consultation_ID' AND Registration_ID = '$Registration_ID'"))['Consent_ID'];
    
?>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    th{
        text-align:right;
    }


   
</style>
    <input type="button" value="BACK" onclick="history.go(-1)" class="art-button-green">
    <a href='printrtheater_blood_consent.php?Registration_ID=<?= $Registration_ID ?>&Consent_ID=<?= $Consent_ID ?>' class="art-button-green" target='_blank'>BLOOD CONSENT FORM</a>
<br/>
<br/>
<br/>
<center>
<fieldset>
<legend align=center><b>BLOOD TRANSFUSION REQUEST FORM</b></legend>
    <table  width="40%" class="table" style="background: #FFFFFF;">
        <caption><b>PATIENT DETAILS</b></caption>
        <tr>
            <td><b>PATIENT NAME</b></td>
            <td style='text-align:right'><b>REGISTRATION No.</b></td>
            <td><b>WARD</b></td>
            <td><b>DOCTOR </b></td>
            <td><b>AGE</b></td>
            <td><b>GENDER</b></td>
            
        </tr>
        <?php 
            $Patient_Name ="";
            $Date_Of_Birth =$pat_details_rows['Date_Of_Birth'];
            $Region ="";
            $District ="";
            $Ward ="";
            $village ="";
            $sql_select_patient_information_result = mysqli_query($conn,"SELECT Patient_Name,Date_Of_Birth,Region,District,Ward,village,Gender FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_patient_information_result)>0){
                while($pat_details_rows=mysqli_fetch_assoc($sql_select_patient_information_result)){
                    $Patient_Name =$pat_details_rows['Patient_Name'];
                    $Date_Of_Birth =$pat_details_rows['Date_Of_Birth'];
                    $Region =$pat_details_rows['Region'];
                    $District =$pat_details_rows['District'];
                    $Ward =$pat_details_rows['Ward'];
                    $village =$pat_details_rows['village'];
                    $Gender =$pat_details_rows['Gender'];
                }
            }
             //today function
            $Today_Date = mysqli_query($conn,"select now() as today");
            while($row = mysqli_fetch_array($Today_Date)){
                $original_Date = $row['today'];
                $new_Date = date("Y-m-d", strtotime($original_Date));
                $Today = $new_Date;
                $age ='';
            }
                $date1 = new DateTime($Today);
                $date2 = new DateTime($Date_Of_Birth);
                $diff = $date1 -> diff($date2);
                $age = $diff->y." Years, ";
                $age .= $diff->m." Months, ";
                $age .= $diff->d." Days";
                //select doctor name
                $doctor_id=mysqli_query($conn,"SELECT consultant_ID, Payment_Date_And_Time FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID= '$Payment_Item_Cache_List_ID '") or die(mysqli_error($conn));
                while($row = mysqli_fetch_array($doctor_id)){
                    $doctor_id2 = $row['consultant_ID'];
                }


                $doctor_name1 = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID= '$doctor_id2'") or die(mysqli_error($conn));
                while($row = mysqli_fetch_array($doctor_name1)){
                    $doctor_name = $row['Employee_Name'];
                }
                
                //select admission ward 
                $Hospital_Ward_Name="";
                $sql_select_admission_ward_result=mysqli_query($conn,"SELECT Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID=(SELECT Hospital_Ward_ID FROM tbl_admission WHERE Registration_ID='$Registration_ID' AND Admission_Status<>'Discharged' ORDER BY Admision_ID DESC LIMIT 1)") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_admission_ward_result)>0){
                    $Hospital_Ward_Name=mysqli_fetch_assoc($sql_select_admission_ward_result)['Hospital_Ward_Name'];
                }else{
                    $Hospital_Ward_Name = '<b>NOT ADMITTED</b>';
                }
                echo "<tr>
                    <td>$Patient_Name</td>
                    <td style='text-align:right'>$Registration_ID</td>
                    <td>$Hospital_Ward_Name</td>
                    <td>$Employee_Name</td>
                    <td>$age</td>
                    <td>$Gender </td>
                  </tr>";
                  $select_blood_tranfusion = mysqli_query($conn, "SELECT em.Employee_Name, bt.Blood_Transfusion_ID, bt.Priority, bt.hour_days, bt.Specimen_Details, bt.Within, bt.to_be_given, bt.operation_on, bt.amount_blood, bt.dr_group, bt.previous_transfusion, bt.Clinical_History FROM tbl_blood_transfusion_requests bt, tbl_employee em WHERE em.Employee_ID = bt.Employee_ID AND Consent_ID= '$Consent_ID '");
                
                  if(mysqli_num_rows($select_blood_tranfusion) > 0){

                                while($rows = mysqli_fetch_array($select_blood_tranfusion)){
                                    $Blood_Transfusion_ID = $rows['Blood_Transfusion_ID'];
                                    $Priority = $rows['Priority'];
                                    $hour_days = $rows['hour_days'];
                                    $Specimen_Details = $rows['Specimen_Details'];
                                    $to_be_given = $rows['to_be_given'];
                                    $Within = $rows['Within'];
                                    $operation_on = $rows['operation_on'];
                                    $Doctor_collected = $rows['Employee_ID'];
                                    $amount_blood = $rows['amount_blood'];
                                    $dr_group = $rows['dr_group'];
                                    $previous_transfusion = $rows['previous_transfusion'];
                                    $Clinical_History = $rows['Clinical_History'];
                                    $Site_Biopsy = $rows['Site_Biopsy'];
                                    $Previous_Laboratory = $rows['Previous_Laboratory'];
                                    $Duration_Condition = $rows['Duration_Condition'];
                                    $Comments = $rows['Comments'];
                                    $Referred_From = $rows['Referred_From'];
                                    
                                    echo $Laboratory_Number;
                                    $Doctor_collected_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Doctor_collected'"))['Employee_Name'];

                                    
                                }

                        $select_biopsy_data = mysqli_query($conn, "SELECT specimen_results_Employee_ID, TimeCollected, time_received, Employee_ID_receive, sample_quality FROM tbl_specimen_results WHERE payment_item_ID='$Payment_Item_Cache_List_ID'");
                            while($data = mysqli_fetch_array($select_biopsy_data)){
                                $specimen_results_Employee_ID = $data['specimen_results_Employee_ID'];
                                $TimeCollected = $data['TimeCollected'];
                                $time_received = $data['time_received'];
                                $Employee_ID_receive = $data['Employee_ID_receive'];
                                $sample_quality = $data['sample_quality'];

                                
                                if($sample_quality == 'Suitable'){
                                    $quality = 'Satisfactory';
                                }else{
                                    $quality = 'Unsatisfactory';
                                }

                            $Employee_collected_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$specimen_results_Employee_ID'"))['Employee_Name'];
                            $Employee_received_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Employee_ID_receive'"))['Employee_Name'];

                            }
                  }
        ?>
    </table>
  
    <!--div for adding clinical information -->
    <form action="" id="clinical">
        <table class="table" >

        <!-- <caption style="font-size: 16px;"><b>BIOPSY/HISTOLOGICAL EXAMINATION REQUESTING FORM</b></caption> -->
        
                <tbody >
                    <tr>
                        <th style='text-align: right; width: 20%;'>PRIORITY:</th>
                        <th colspan='2' style='text-align: left;'>
                        
                            <input type="radio" name='Priority' id='Priority' onchange='update_save_biopsy()' value='Urgent' <?php if($Priority2 == "Urgent") { echo "checked"; } ?> >&nbsp;Urgent &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name='Priority' id='Priority' onchange='update_save_biopsy()' value='Routine' <?php if($Priority2 == "Routine") { echo "checked"; } ?> >&nbsp;Routine
                        </th>
                        <th style='text-align: right;'>Specimen Details</th>
                        <th style='text-align: right;'><input name="aortic" class="form_group" id="Specimen_Details" placeholder='Specimen Details' title='Please Fill Specimen'type="text" class="inp"  value="<?php echo $Specimen_Details; ?>"></th>
                    </tr>
                    <tr>
                        <th style='text-align: right;'>Clinical History</th>
                        <td colspan="5">
                            <textarea name="Clinical_History" id="Clinical_History" cols="30" rows="3"></textarea>
                        </td>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th style='text-align: right;'>TRANSFUSION TO BE GIVEN</th>
                        <th style='text-align: left; width: 18%'>
                            <input type="radio" name="to_be_given" id="to_be_given" value="Immediately">&nbsp;&nbsp;Immediately &nbsp;&nbsp;&nbsp;
			                <input type="radio" name="to_be_given" id="Within" value="Within">&nbsp;&nbsp;Today/Within
                        </th>
                        <th style='text-align: left; width: 20%''>
                            <input type="text" placeholder="Specify the time to be given" name="time_to_be_given" id="time_to_be_given" class="instruction" style="display:none; width: 60%;">&nbsp;&nbsp;
                            <input type="radio" name="hour_days" id="hour_days" class="instruction2" style="display:none;" value=" Hours">&nbsp;&nbsp;Hours &nbsp;&nbsp;&nbsp;
			                <input type="radio" name="hour_days" id="hour_days" class="instruction3" style="display:none;" value=" Days">&nbsp;&nbsp;Days
                        </th>
                        <th style='text-align: right;'>Operation On</th>
                        <th style='text-align: right;'><input name="aortic1" class="form_group" id="operation_on"  placeholder='Operation On' type="text" class="form-control ehms_date_time3"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th style='text-align: right;'>AMOUNT OF BLOOD REQUIRED (BOTTLES/UNITS</th>
                        <th style='text-align: right;' colspan='2'><input name="aortic" class="form_group" id="amount_blood"  placeholder='Amount blood required (BOTTLES/UNITS)' type="text" class="inp" value="<?php echo $Employee_collected_name; ?>"></th>
                        <th style='text-align: right;'>Group **If known:</th>
                        <th style='text-align: right;' colspan='2'><input name="aortic1" class="form_group" id="dr_group" placeholder='Group **If known:' type="text" class="inp" value="<?php echo $TimeCollected; ?>"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th style='text-align: right;'>Previous Transfusion</th>
                        <th style='text-align: right;' colspan='2'><input name="aortic" class="form_group" id="previous_transfusion"  placeholder='Previous Transfusion' type="text" class="inp" value="<?php echo $Employee_received_name; ?>"></th>

                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th style='text-align: right;'>Reason For Transfusion</th>
                        <th style='text-align: right;' colspan='5'>
                            <textarea style="resize:none;padding-left:5px;" required="required" onkeyup='update_results_biopsy(<?= $Biopsy_ID ?>)' id="reason_for_transfusion"  name="reason_for_transfusion"><?php echo $relevant_clinical_data; ?></textarea>
                        </th>
                    </tr>
                    <tr>
                        <td colspan='5'>
                    <input type="text" name="Biopsy_ID" id="Biopsy_ID" class="art-button-green" style='display: none;' value='<?php echo $Biopsy_ID ?>'>
        <input type="button" id="clinical_btn" style="border-radius:0px" value="SAVE RESULTS" class="btn art-button pull-right" onclick='save_blood_request()'>          
                        </td>
                </tr>
                </tbody>
        </table>

    </form>
</fieldset>
<script>
    function save_blood_request(){
        var hour_days = $("input[name = 'hour_days']:checked").val();
        var Priority = $("input[name = 'Priority']:checked").val();
        var to_be_given = $("input[name = 'to_be_given']:checked").val();
        var time_to_be_given = $("#time_to_be_given").val();
        var Clinical_History = $("#Clinical_History").val();
        var reason_for_transfusion = $("#reason_for_transfusion").val();
        var previous_transfusion = $("#previous_transfusion").val();
        var dr_group = $("#dr_group").val();
        var amount_blood = $("#amount_blood").val();
        var operation_on = $("#operation_on").val();
        var Registration_ID = '<?= $Registration_ID; ?>';
        var Employee_ID = '<?= $Employee_ID; ?>';
        var consultation_ID = '<?= $consultation_ID ?>';
        var Consent_ID = '<?= $Consent_ID ?>';


        if(Registration_ID != '0' && amount_blood != null){
            if (confirm("You are about to Submit the blood Transfusion Request form, Do you want to Proceed?")) {
                $.ajax({
                    url: "ajax_save_blood_request_form.php",
                    type: "post",
                    data: {Registration_ID:Registration_ID,hour_days:hour_days,Employee_ID:Employee_ID,Priority:Priority,consultation_ID:consultation_ID,to_be_given:to_be_given,time_to_be_given:time_to_be_given,Clinical_History:Clinical_History,reason_for_transfusion:reason_for_transfusion,previous_transfusion:previous_transfusion,dr_group:dr_group,amount_blood:amount_blood,operation_on:operation_on,Consent_ID:Consent_ID},
                    cache: false,
                    success: function(responce){
                        alert(responce);
                        document.location = "bloodconcertform.php"; 
                    }
                });
            }
		}else{
            alert("Please, Fill the amount of blood you want to request before Saving");
            exit();
        }
	}
</script>

<script>
var radio = document.getElementById('Within');
var radio2 = document.getElementById('to_be_given');
radio.addEventListener('change',function(){
    var month = document.querySelector('.instruction');
    var month2 = document.querySelector('.instruction2');
    var month3 = document.querySelector('.instruction3');
    if(this.checked){
        month.style.display='inline';
        month2.style.display='inline';
        month3.style.display='inline';
    }else{
        month.style.display='none';
        month2.style.display='none';
        month3.style.display='none';
    }
})
radio2.addEventListener('change',function(){
    var month = document.querySelector('.instruction');
    var month2 = document.querySelector('.instruction2');
    var month3 = document.querySelector('.instruction3');
    if(this.checked){
        month.style.display='none';
        month2.style.display='none';
        month3.style.display='none';
    }else{
        month.style.display='inline';
        month2.style.display='inline';
        month3.style.display='inline';
    }
})
</script>

<script type="text/javascript">
        $(document).ready(function () {
            $('#patients-list').DataTable({
                "bJQueryUI": true
            });

            $('#operation_on').datetimepicker({
                dayOfWeekStart: 1,
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,
                lang: 'en',
                //startDate:    'now'
            });
            $('#operation_on').datetimepicker({value: '', step: 1});
            $('#end_date').datetimepicker({
                dayOfWeekStart: 1,
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,
                lang: 'en',
                //startDate:'now'
            });
            $('#end_date').datetimepicker({value: '', step: 1});
        });
    </script>
        <link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
    <link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
    <script src="media/js/jquery.js" type="text/javascript"></script>
    <script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
    <script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
    <script src="css/jquery-ui.js"></script>
<br/>
<br/>
<br/>
<br/>
<?php
include("includes/footer.php");
?>