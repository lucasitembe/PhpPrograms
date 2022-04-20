<?php
   include("./includes/connection.php");
   include("./includes/header.php");
   

   session_start();
$Registration_ID = $_GET['Registration_ID'];
$Blood_Transfusion_ID = $_GET['BTID'];


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

<center>
<fieldset>
<legend align=center><b>BLOOD TRANSFUSION PROCESSING FORM</b></legend>
    <table  width="40%" class="table" style="background: #FFFFFF;">
        <caption><b>PATIENT DETAILS</b></caption>
        <tr>
            <td width='30%'><b>PATIENT NAME</b></td>
            <td><b>REGISTRATION No.</b></td>
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
                    <td>$Registration_ID</td>
                    <td>$Hospital_Ward_Name</td>
                    <td>$Employee_Name</td>
                    <td>$age</td>
                    <td>$Gender </td>
                  </tr>";

                  $select_blood_tranfusion = mysqli_query($conn, "SELECT em.Employee_Name, bt.Saved_date, bt.Process_Status, bt.time_to_be_given, bt.Blood_Transfusion_ID, bt.reason_for_transfusion, bt.Priority, bt.hour_days, bt.Specimen_Details, bt.Within, bt.to_be_given, bt.operation_on, bt.amount_blood, bt.dr_group, bt.previous_transfusion, bt.Clinical_History FROM tbl_blood_transfusion_requests bt, tbl_employee em WHERE em.Employee_ID = bt.Employee_ID AND bt.Blood_Transfusion_ID= '$Blood_Transfusion_ID'");
                
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
                                    $Employee_Name = $rows['Employee_Name'];
                                    $reason_for_transfusion = $rows['reason_for_transfusion'];
                                    $Saved_date = $rows['Saved_date'];
                                    $time_to_be_given = $rows['time_to_be_given'];
                                    $Process_Status = $rows['Process_Status'];

                                }
                                if($Blood_Transfusion_ID > 0){
                                    $select_previous = mysqli_query($conn, "SELECT btp.Submitted_By, btp.Rh1, btp.Rh2, btp.Rh3, btp.Rh4, btp.donor1, btp.donor2, btp.donor3, btp.donor4, btp.group1, btp.group2, btp.group3, btp.group4, btp.Quality, btp.Processed_Date_Time, btp.Pt_Hb, btp.pt_Group, btp.pt_Rh, btp.Blood_Transfusion_ID, btp.Comments, btp.Coombs, em.Employee_Name FROM tbl_employee em, tbl_blood_transfusion_processing btp WHERE btp.Blood_Transfusion_ID = '$Blood_Transfusion_ID' AND em.Employee_ID = btp.Submitted_By");

                                    while($data = mysqli_fetch_assoc($select_previous)){
                                        $Rh1 = $data['Rh1'];
                                        $Rh2 = $data['Rh2'];
                                        $Rh3 = $data['Rh3'];
                                        $Rh4 = $data['Rh4'];
                                        $donor1 = $data['donor1'];
                                        $donor2 = $data['donor2'];
                                        $donor3 = $data['donor3'];
                                        $donor4 = $data['donor4'];
                                        $group1 = $data['group1'];
                                        $group2 = $data['group2'];
                                        $group3 = $data['group3'];
                                        $group4 = $data['group4'];
                                        $Quality = $data['Quality'];
                                        $Pt_Hb = $data['Pt_Hb'];
                                        $pt_Group = $data['pt_Group'];
                                        $pt_Rh = $data['pt_Rh'];
                                        $This_Employee_ID = $data['Employee_Name'];
                                        $Blood_Transfusion_ID = $data['Blood_Transfusion_ID'];
                                        $Comments = $data['Comments'];
                                        $Coombs = $data['Coombs'];
                                        $Processed_Date_Time = $data['Processed_Date_Time'];
                                    }
                                }

                  }

        ?>
    </table>
  
    <!--div for adding clinical information -->
    <form action="" id="clinical">
        <table class="table" >

        
                <tbody >
                    <tr>
                        <th style='text-align: right; width: 15%;'>PRIORITY:</th>
                        <th colspan='2' style='text-align: left;'>
                        
                            <input type="radio" name='Priority' id='Priority' onchange='update_save_biopsy()' value='Urgent' <?php if($Priority == "Urgent") { echo "checked"; } ?> >&nbsp;Urgent &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name='Priority' id='Priority' onchange='update_save_biopsy()' value='Routine' <?php if($Priority == "Routine") { echo "checked"; } ?> >&nbsp;Routine <span style='float: right;'>Requested At: &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Saved_date;?></span>
                        </th>
                        <th style='text-align: right;'>Specimen Details</th>
                        <th style='text-align: right;'><input name="aortic" class="form_group" id="Specimen_Details" placeholder='Specimen Details' title='Please Fill Specimen'type="text" class="inp"  value="<?php echo $Specimen_Details; ?>"></th>
                    </tr>
                    <tr>
                        <th style='text-align: right;'>Clinical History</th>
                        <td colspan="5">
                            <textarea name="Clinical_History" id="Clinical_History" cols="30" rows="3" readonly='readonly'><?php echo $Clinical_History; ?></textarea>
                        </td>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th style='text-align: right;'>TRANSFUSION TO BE GIVEN</th>
                        <th style='text-align: left; width: 18%'>
                            <input type="radio" name="to_be_given" id="to_be_given" value="Immediately" <?php if($to_be_given == 'Immediately'){ echo "checked"; }?> >&nbsp;&nbsp;Immediately &nbsp;&nbsp;&nbsp;
			                <input type="radio" name="to_be_given" id="Within" value="Within" <?php if($to_be_given == 'Within'){ echo "checked"; }?> >&nbsp;&nbsp;Today/Within
                        </th>
                        <th style='text-align: left; width: 20%'>
                        <?php 
                            if($to_be_given == 'Within'){
                        ?>
                            <input type="text" placeholder="Specify the time to be given" name="time_to_be_given" id="time_to_be_given" class="instruction" value="<?php echo $time_to_be_given." ".$hour_days; ?>" style="font-weight: bold;" readonly='readonly'>&nbsp;&nbsp;
                            <?php
                            }
                            ?>
                        </th>
                        <th style='text-align: right;'>Operation On</th>
                        <th style='text-align: right;'><input name="aortic1" class="form_group" id="operation_on"  placeholder='Operation On' type="text" class="form-control ehms_date_time3"  value="<?php echo $operation_on; ?>" readonly='readonly'></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th style='text-align: right;'>AMOUNT OF BLOOD REQUIRED</th>
                        <th style='text-align: right;' colspan='2'><input name="aortic" class="form_group" id="amount_blood"  placeholder='Amount blood required (BOTTLES/UNITS)' type="text" class="inp" value="<?php echo $amount_blood; ?>" readonly='readonly'></th>
                        <th style='text-align: right;'>Group **If known:</th>
                        <th style='text-align: right;' colspan='2'><input name="aortic1" class="form_group" id="dr_group" placeholder='Group **If known:' type="text" class="inp" value="<?php echo $dr_group; ?>" readonly='readonly'></th>

                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th style='text-align: right;'>Previous Transfusion</th>
                        <th style='text-align: right;' colspan='2'><input name="aortic" class="form_group" id="previous_transfusion"  placeholder='Previous Transfusion' type="text" class="inp" value="<?php echo $previous_transfusion; ?>" readonly='readonly'></th>

                        <th style='text-align: right;'>Requested By</th>
                        <th style='text-align: left;'><?php echo $Employee_Name; ?></th>

                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th style='text-align: right;'>Reason For Transfusion</th>
                        <th style='text-align: right;' colspan='5'>
                            <textarea style="resize:none;padding-left:5px;" required="required" onkeyup='update_results_biopsy(<?= $Biopsy_ID ?>)' id="reason_for_transfusion"  name="reason_for_transfusion"  readonly='readonly'><?php echo $reason_for_transfusion; ?></textarea>
                        </th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th colspan='6' style='font-size: 16px; background: #dedede;'>
                            FOR LABORATORY USE
                        </th>
                    </tr>
                    <tr>
                        <th style='text-align: right;'>SPECIMEN QUALITY:</th>
                        <th style='text-align: left;'>
                            <input type="radio" name="Quality" id="Quality" value="Satisfactory" <?php if($Quality == 'Satisfactory'){ echo "checked"; }?> >&nbsp;&nbsp;Satisfactory &nbsp;&nbsp;&nbsp;
			                <input type="radio" name="Quality" id="Quality" value="Unsatisfactory" <?php if($Quality == 'Unsatisfactory'){ echo "checked"; }?> >&nbsp;&nbsp;Unsatisfactory
                        </th>
                        <th style='text-align: left;'>
                            <input type="radio" name="Coombs" id="Coombs" value="Direct Coombs" <?php if($Coombs == 'Direct Coombs'){ echo "checked"; }?> >&nbsp;&nbsp;Direct Coombs &nbsp;&nbsp;&nbsp;
			                <input type="radio" name="Coombs" id="Coombs" value="Indirect Coombs" <?php if($Coombs == 'Indirect Coombs'){ echo "checked"; }?> >&nbsp;&nbsp;Indirect Coombs
                        </th>
                        <th style='text-align: left;' colspan='2'>Comment: 
                            <input type="text" name="Comments" id="Comments" placeholder="Comments" onkeyup='Update_Blood_Request()' style="width: 90%;" value="<?php echo $Comments; ?>">
                        </th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th style='text-align: right;'>Case No</th>
                        <th><?php echo $Blood_Transfusion_ID; ?></th>
                        <td><b>Patient's HB &nbsp;&nbsp;</b><input type="text" placeholder="Specify the Patient HB" onkeyup="Update_Blood_Request()" name="Pt_Hb" id="Pt_Hb" class="instruction" value="<?php echo $Pt_Hb; ?>" style="width: 60%; text-align: center;" >&nbsp;&nbsp;<b>Gm/dl</b>
                        </td>
                        <th style='text-align: right;'>Patient's Group</th>
                        <td><input type="text" placeholder="Specify the Patient Group" onkeyup='Update_Blood_Request()' name="pt_Group" id="pt_Group" class="instruction" value="<?php echo $pt_Group; ?>" style="width: 40%; text-align: center;">&nbsp;&nbsp;<b>Rh</b><input type="text" onkeyup='Update_Blood_Request()' placeholder="Specify Patient Rh" name="pt_Rh" id="pt_Rh" class="instruction" value="<?php echo $pt_Rh; ?>" style="width: 40%; text-align: center;"></td>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th style='text-align: right;' rowspan="5">Compatible With</th>
                        <th style='background: #dedede;'>Donor Serial No</th>
                        <th style='background: #dedede;'>Group</th>
                        <th style='background: #dedede;'>Rh</th>
                    </tr>
                    <tr>
                        <th><input name="donor1" class="form_group" id="donor1" onkeyup="Update_Blood_Request()" placeholder='Donor Serial Number' type="text" class="donor1" value="<?php echo $donor1; ?>"></th>
                        <th><input name="group1" class="form_group" id="group1" onkeyup="Update_Blood_Request()" placeholder="Donor's Group" type="text" class="group1" value="<?php echo $group1; ?>"></th>
                        <th><input name="Rh1" class="form_group" id="Rh1" onkeyup="Update_Blood_Request()" placeholder='Rhesus Factor' type="text" value="<?php echo $Rh1; ?>"></th>
                        <td style='text-align: left;'>Lab. Technician: &nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $This_Employee_ID; ?></b></td>

                    </tr>
                    <tr>
                        <th><input name="donor2" class="form_group" id="donor2" onkeyup="Update_Blood_Request()" placeholder='Donor Serial Number' type="text" class="donor2" value="<?php echo $donor2; ?>"></th>
                        <th><input name="group2" class="form_group" id="group2" onkeyup="Update_Blood_Request()" placeholder="Donor's Group" type="text" class="group2" value="<?php echo $group2; ?>"></th>
                        <th><input name="Rh2" class="form_group" id="Rh2" onkeyup="Update_Blood_Request()" placeholder='Rhesus Factor' type="text" class="Rh2" value="<?php echo $Rh2; ?>"></th>
                        <td style='text-align: left;'>Signature: &nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $Employee_Name; ?></b></td>

                    </tr>
                    <tr>
                        <th><input name="donor3" class="form_group" id="donor3" onkeyup="Update_Blood_Request()" placeholder='Donor Serial Number' type="text" class="donor3" value="<?php echo $donor3; ?>"></th>
                        <th><input name="group3" class="form_group" id="group3" onkeyup="Update_Blood_Request()" placeholder="Donor's Group" type="text" class="group3" value="<?php echo $group3; ?>"></th>
                        <th><input name="Rh3" class="form_group" id="Rh3" onkeyup="Update_Blood_Request()" placeholder='Rhesus Factor' type="text" class="Rh3" value="<?php echo $Rh3; ?>"></th>
                        <td style='text-align: left;'>Time & Date: &nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $Processed_Date_Time; ?></b></td>
                    </tr>
                    <tr>
                        <th><input name="donor4" class="form_group" id="donor4" onkeyup="Update_Blood_Request()" placeholder='Donor Serial Number' type="text" class="donor4" value="<?php echo $donor4; ?>"></th>
                        <th><input name="group4" class="form_group" id="group4" onkeyup="Update_Blood_Request()" placeholder="Donor's Group" type="text" class="group4" value="<?php echo $group4; ?>"></th>
                        <th><input name="Rh4" class="form_group" id="Rh4" onkeyup="Update_Blood_Request()" placeholder='Rhesus Factor' type="text" class="Rh4" value="<?php echo $Rh4; ?>"></th>
                        <td colspan='5'>
                            <input type="text" name="Blood_Transfusion_ID" id="Blood_Transfusion_ID" class="art-button-green" style='display: none;' value='<?php echo $Blood_Transfusion_ID ?>'>
                        <?php 
                            if($Process_Status == 'active'){
                                echo '<input type="button" id="clinical_btn" style="border-radius:0px; border-radius: 10px; padding: 25px; font-size: 15px; font-weight: bold;" value="PROCESS REQUEST" title="Please Review the Details given before Processing the Request" class="btn art-button pull-right" onclick="Submit_data()">';
                            }else{
                                echo '<a href="preview_processed_blood_requests.php?Blood_Transfusion_ID='.$Blood_Transfusion_ID.'&Registration_ID='.$Registration_ID.'" style="border-radius:0px; border-radius: 10px; padding: 25px; font-size: 15px; font-weight: bold;" title="Please Review the Details given before Processing the Request" class="btn art-button pull-right" target="_blank">PREVIEW PROCESSED REQUEST</a>';
                            }
                        
                        ?>
                                      
                        </td>
                    </tr>
                </tbody>

        </table>
        <center><p style="margin-top:10px;color: #0079AE;font-weight:bold"><i> This Form is Autosave activated, If you Click ***PROCESS REQUEST*** Button Means All the Details given are correct </i></p></center>

    </form>
</fieldset>
<div id="Submit_data">
    <center><b>You are about to process this Blood Tranfusion Request, Are you Sure you've filled the Correct Infomation and You want to Process it?</b></center><br/>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <input type="button" name="Submit_Information" id="Submit_Information" value="YES" class="art-button-green" onclick="Submit_Information()">
                <input type="button" name="Cancel" id="Cancel" value="CANCEL" class="art-button-green" onclick="Close_Submit_Dialog()">
            </td>
        </tr>
    </table>
</div>
<script>
    function Submit_Information(){
       $("#Submit_data").dialog("close");
        var Employee_ID = '<?= $Employee_ID; ?>';
        var Blood_Transfusion_ID = '<?= $Blood_Transfusion_ID ?>';


        if(Employee_ID != '0' && Blood_Transfusion_ID != null){
                $.ajax({
                    url: "ajax_process_blood.php",
                    type: "post",
                    data: {Employee_ID:Employee_ID,Blood_Transfusion_ID:Blood_Transfusion_ID},
                    cache: false,
                    success: function(responce){
                        alert(responce);
                        location.reload(); 
                    }
                });
		}else{
            alert("Please, Fill The Blood Request Form In order to Perform this Request");
            exit();
        }
	}
</script>
<script type="text/javascript">
    function Close_Submit_Dialog(){
        $("#Submit_data").dialog("close");
    }
</script>
<script type="text/javascript">
    function Submit_data(){
    var donor1 = $("#donor1").val();
    var group1 = $("#group1").val();
    var Rh1 = $("#Rh1").val();
    var pt_Group = $("#pt_Group").val();
    var pt_Rh = $("#pt_Rh").val();

        if(donor1==""){
            alert("Please Specify Donnor Serial Number");
            $("#donor1").css("border","2px solid red");
            $("#donor1").focus();
            exit;
        }
        if(group1==""){
            alert("Please Specify Donnor Blood Group");
            $("#group1").css("border","2px solid red");
            $("#group1").focus();
            exit;
        }
        if(Rh1==""){
            alert("Please Specify Donnor Rhesus factor");
            $("#Rh1").css("border","2px solid red");
            $("#Rh1").focus();
            exit;
        }
        if(pt_Rh==""){
            $("#pt_Rh").css("border","2px solid red");
            $("#pt_Rh").focus();
            exit;
        }
        if(pt_Group==""){
            $("#pt_Group").css("border","2px solid red");
            $("#pt_Group").focus();
            exit;
        }
       $("#Submit_data").dialog("open");
    }
</script>
<script>
    function Update_Blood_Request() {
        var Quality = $("input[name = 'Quality']:checked").val();
        var Coombs = $("input[name = 'Coombs']:checked").val();
        var donor1 = $("#donor1").val();
        var donor2 = $("#donor2").val();
        var donor3 = $("#donor3").val();
        var donor4 = $("#donor4").val();
        var group1 = $("#group1").val();
        var group2 = $("#group2").val();
        var group3 = $("#group3").val();
        var group4 = $("#group4").val();
        var Rh1 = $("#Rh1").val();
        var Rh2 = $("#Rh2").val();
        var Rh3 = $("#Rh3").val();
        var Rh4 = $("#Rh4").val();
        var Comments = $("#Comments").val();
        var Pt_Hb = $("#Pt_Hb").val();
        var pt_Group = $("#pt_Group").val();
        var pt_Rh = $("#pt_Rh").val();
        var Employee_ID = '<?= $Employee_ID; ?>';
        var Blood_Transfusion_ID = '<?= $Blood_Transfusion_ID ?>';

        if(Employee_ID != '0' && Blood_Transfusion_ID != null){
                $.ajax({
                    url: "ajax_update_blood_process.php",
                    type: "post",
                    data: {Quality:Quality,Coombs:Coombs,Employee_ID:Employee_ID,donor1:donor1,donor2:donor2,donor3:donor3,donor4:donor4,group1:group1,group2:group2,group3:group3,group4:group4,Blood_Transfusion_ID:Blood_Transfusion_ID,Rh1:Rh1,Rh2:Rh2,Rh3:Rh3,Rh4:Rh4,Comments:Comments,Pt_Hb:Pt_Hb,pt_Group:pt_Group,pt_Rh:pt_Rh},
                    cache: false,
                    success: function(responce){
                    }
                });
		}else{
            alert("Please, Fill The Blood Request Form In order to Perform this Request");
            exit();
        }
    }
</script>
<script>
    $(document).ready(function () {
        $("#Submit_data").dialog({autoOpen: false, width: '60%', height: 150, title: 'BLOOD TRANSFUSION PROCESSING', modal: true});
    });
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

<!--<link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<script src="js/zebra_dialog.js"></script>
<script src="js/ehms_zebra_dialog.js"></script>-->

<link rel="stylesheet" href="css/ui.notify.css" media="screen">
<script src="js/jquery.notify.min.js"></script> 
<?php
include("includes/footer.php");
?>