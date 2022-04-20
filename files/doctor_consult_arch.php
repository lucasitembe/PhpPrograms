<?php
include("./includes/connection.php");
include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
        if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes') {
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
<a href='hospitalconsultation.php?hospitalconsultationConfigurations=hospitalconsultationConfigurationsThisForm' class='art-button-green'> 
    BACK
</a>

<br/><br/>

<?php
if (isset($_POST['submittedhospconstypeForm'])) {
    $allow_display_prev = '0';
    if (isset($_POST['allow_display_prev'])) {
        $allow_display_prev = '1';
    }
    
    $set_pre_paid = '0';
    if (isset($_POST['set_pre_paid'])) {
        $set_pre_paid = '1';
    }
    
    $set_duplicate_bed_assign = '0';
    if (isset($_POST['set_duplicate_bed_assign'])) {
        $set_duplicate_bed_assign = '1';
    }
    
     $set_doctors_auto_save = '0';
    if (isset($_POST['set_doctors_auto_save'])) {
        $set_doctors_auto_save = '1';
    }//
    
     $enable_doct_date_chooser = '0';
    if (isset($_POST['enable_doct_date_chooser'])) {
        $enable_doct_date_chooser = '1';
    }
    //
    $enable_pat_medic_hist = '0';
    if (isset($_POST['enable_pat_medic_hist'])) {
        $enable_pat_medic_hist = '1';
    }
    
    $enable_clinic_not_scroll = '0';
    if (isset($_POST['enable_clinic_not_scroll'])) {
        $enable_clinic_not_scroll = '1';
    }
    
     $enable_spec_dosage = '0';
    if (isset($_POST['enable_spec_dosage'])) {
        $enable_spec_dosage = '1';
    }
    
     $enb_lab_wt_no_par = '1';
    if (isset($_POST['enb_lab_wt_no_par'])) {
        $enb_lab_wt_no_par = '1';
    }
    
     $req_op_prov_dign= '0';
    if (isset($_POST['req_op_prov_dign'])) {
        $req_op_prov_dign = '1';
    }
    //
    $req_ip_prov_dign = '0';
    if (isset($_POST['req_ip_prov_dign'])) {
        $req_ip_prov_dign = '1';
    }
    
    $req_op_final_dign = '0';
    if (isset($_POST['req_op_final_dign'])) {
        $req_op_final_dign = '1';
    }
    
    $en_const_per_day_count = '0';
    if (isset($_POST['en_const_per_day_count'])) {
        $en_const_per_day_count = '1';
    }
    
    $req_perf_by_signed_off = '0';
    if (isset($_POST['req_perf_by_signed_off'])) {
        $req_perf_by_signed_off = '1';
    }
    
    $en_item_status_pat_file = '0';
    if (isset($_POST['en_item_status_pat_file'])) {
        $en_item_status_pat_file = '1';
    }
    
    $en_inp_auto_bill = '0';
    if (isset($_POST['en_inp_auto_bill'])) {
        $en_inp_auto_bill = '1';
    }
    
	$mandatory_comments= '0';
	if (isset($_POST['mandatory_comments'])) {
        $mandatory_comments = '1';
    }
    
    $doctor_admits_patient='no';
    if (isset($_POST['doctor_admits_patient'])) {
        $doctor_admits_patient = 'yes';
    }
    
    $Enable_Save_And_Transfer_Button = '0';
    if (isset($_POST['Enable_Save_And_Transfer_Button'])) {
        $Enable_Save_And_Transfer_Button = '1';
    }
    $require_final_diagnosis_before_select_treatment = 'no';
    if (isset($_POST['require_final_diagnosis_before_select_treatment'])) {
        $require_final_diagnosis_before_select_treatment = 'yes';
    }

    $hospType = mysqli_real_escape_string($conn,$_POST['hospType']);
    $doctor_notice_display_max_time = $_POST['doctor_notice_display_max_time'];
    $consulted_patient_display_max_time = $_POST['consulted_patient_display_max_time'];

    $sql = "insert into tbl_hospital_consult_type(
                            consultation_Type,doctor_notice_display_max_time,consulted_patient_display_max_time,allow_display_prev,set_pre_paid,set_duplicate_bed_assign,set_doctors_auto_save,enable_doct_date_chooser,enable_pat_medic_hist,enable_clinic_not_scroll,enable_spec_dosage,enb_lab_wt_no_par,req_op_prov_dign,req_ip_prov_dign,req_op_final_dign,en_const_per_day_count,req_perf_by_signed_off,en_item_status_pat_file,en_inp_auto_bill,mandatory_comments,doctor_admits_patient,Branch_ID,
                            date_saved,Employee_ID,Enable_Save_And_Transfer_Button,require_final_diagnosis_before_select_treatment)

                            values('$hospType',$doctor_notice_display_max_time,$allow_display_prev,$set_pre_paid,$set_duplicate_bed_assign,$set_doctors_auto_save,$enable_doct_date_chooser,$enable_pat_medic_hist,$enable_clinic_not_scroll,$enable_spec_dosage,$enb_lab_wt_no_par,$req_op_prov_dign,$req_ip_prov_dign,$req_op_final_dign,$en_const_per_day_count,$req_perf_by_signed_off,$en_item_status_pat_file,$en_inp_auto_bill,$mandatory_comments,'$doctor_admits_patient','" . $_SESSION['userinfo']['Branch_ID'] . "',NOW(),
                                        '" . $_SESSION['userinfo']['Employee_ID'] . "','$Enable_Save_And_Transfer_Button','$require_final_diagnosis_before_select_treatment')";

    $check_if_any = mysqli_query($conn,"SELECT consultation_Type FROM tbl_hospital_consult_type WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'") or die(mysqli_error($conn));

    if (mysqli_num_rows($check_if_any) > 0) {
        $update="UPDATE tbl_hospital_consult_type SET doctor_notice_display_max_time='$doctor_notice_display_max_time',consulted_patient_display_max_time='$consulted_patient_display_max_time',require_final_diagnosis_before_select_treatment='$require_final_diagnosis_before_select_treatment',consultation_Type='$hospType',allow_display_prev=$allow_display_prev,set_pre_paid=$set_pre_paid,set_duplicate_bed_assign=$set_duplicate_bed_assign,set_doctors_auto_save=$set_doctors_auto_save,enable_doct_date_chooser=$enable_doct_date_chooser,enable_pat_medic_hist=$enable_pat_medic_hist,enable_clinic_not_scroll=$enable_clinic_not_scroll,enable_spec_dosage=$enable_spec_dosage,enb_lab_wt_no_par=$enb_lab_wt_no_par,req_op_prov_dign=$req_op_prov_dign,req_ip_prov_dign=$req_ip_prov_dign,req_op_final_dign=$req_op_final_dign,en_const_per_day_count=$en_const_per_day_count,req_perf_by_signed_off=$req_perf_by_signed_off,en_item_status_pat_file=$en_item_status_pat_file,en_inp_auto_bill=$en_inp_auto_bill,mandatory_comments=$mandatory_comments,doctor_admits_patient='$doctor_admits_patient',date_saved=NOW(),Employee_ID='" . $_SESSION['userinfo']['Employee_ID'] . "', Enable_Save_And_Transfer_Button = '$Enable_Save_And_Transfer_Button' WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'";
       
        $result = mysqli_query($conn,$update) or die(mysqli_error($conn));
        echo "<script type='text/javascript'>
                alert('HOSPITAL DOCTOR CONSULTATION TYPE UPDATED SUCCESSFUL');
                 window.location='doctor_consult_arch.php?setarchitecture=setarchitectureThisPage';
              </script>";
    } else {

        if (!mysqli_query($conn,$sql)) {
            $error = '1062yes';
            if (mysql_errno() . "yes" == $error) {
                ?>

                <script type='text/javascript'>
                    alert('HOSPITAL DOCTOR CONSULTATION TYPE ALREADY EXISTS! \nTRY ANOTHER NEW ONE');
                </script>

                <?php
            }
        } else {
            echo "<script type='text/javascript'>
			    alert('HOSPITAL DOCTOR CONSULTATION TYPE ADDED SUCCESSFUL');
                            window.location='doctor_consult_arch.php?setarchitecture=setarchitectureThisPage';
			    </script>";
        }
    }
}

$cons_query = mysqli_query($conn,"SELECT doctor_notice_display_max_time,consulted_patient_display_max_time,doctor_admits_patient,mandatory_comments,doctor_admits_patient,consultation_Type,allow_display_prev,set_pre_paid,set_duplicate_bed_assign,set_doctors_auto_save,enable_doct_date_chooser,enable_pat_medic_hist,enable_clinic_not_scroll,enable_spec_dosage,enb_lab_wt_no_par,req_op_prov_dign,req_ip_prov_dign,req_op_final_dign,en_const_per_day_count,req_perf_by_signed_off,en_item_status_pat_file,en_inp_auto_bill, Enable_Save_And_Transfer_Button,require_final_diagnosis_before_select_treatment FROM tbl_hospital_consult_type WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'") or die(mysqli_error($conn));
$cons= mysqli_fetch_array($cons_query);
$doctor_notice_display_max_time=$cons['doctor_notice_display_max_time']; //@mfoy dn
$consulted_patient_display_max_time=$cons['consulted_patient_display_max_time']; //@mfoy dn
$consultation_Type =$cons['consultation_Type'];
$allow_display_prev=$cons['allow_display_prev'];
$set_pre_paid=$cons['set_pre_paid'];
$set_duplicate_bed_assign=$cons['set_duplicate_bed_assign'];//
$set_doctors_auto_save=$cons['set_doctors_auto_save'];
$enable_doct_date_chooser=$cons['enable_doct_date_chooser'];
$enable_pat_medic_hist=$cons['enable_pat_medic_hist'];//
$enable_clinic_not_scroll=$cons['enable_clinic_not_scroll'];
$enable_spec_dosage=$cons['enable_spec_dosage'];//,
$enb_lab_wt_no_par=$cons['enb_lab_wt_no_par'];
$req_op_prov_dign=$cons['req_op_prov_dign'];//
$req_ip_prov_dign=$cons['req_ip_prov_dign'];
$req_op_final_dign=$cons['req_op_final_dign'];
$en_const_per_day_count=$cons['en_const_per_day_count'];
$req_perf_by_signed_off=$cons['req_perf_by_signed_off'];
$en_item_status_pat_file=$cons['en_item_status_pat_file'];
$en_inp_auto_bill=$cons['en_inp_auto_bill'];
$mandatory_comments=$cons['mandatory_comments'];
$doctor_admits_patient=$cons['doctor_admits_patient'];
$Enable_Save_And_Transfer_Button = $cons['Enable_Save_And_Transfer_Button'];
$require_final_diagnosis_before_select_treatment = $cons['require_final_diagnosis_before_select_treatment'];
?>

<br/><br/>
<center>
    <fieldset style="width:80% ">
        <legend align="center" ><b>SET CONSULTATION TYPE</b></legend>
        <table style="width:80% " border="0">
            <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">

                <tr>
                    <td  style='text-align: right;'><b>Hospital Doctor Consultation Type</b></td>
                    <td >
                        <select name="hospType" style="padding: 4px;font-size:18px">
                            <?php
                            if ($consultation_Type == 'One patient to one doctor') {
                                echo '<option selected="selected" style="padding:5px">One patient to one doctor</option>';
                                echo '<option style="padding:5px">One patient to many doctor</option>';
                            } elseif ($consultation_Type == 'One patient to many doctor') {
                                echo '<option style="padding:5px">One patient to one doctor</option>';
                                echo '<option selected="selected" style="padding:5px">One patient to many doctor</option>';
                            } else {
                                echo '<option selected="selected" style="padding:5px">One patient to one doctor</option>';
                                echo '<option style="padding:5px">One patient to many doctor</option>';
                            }
                            ?>
                        </select>
                    </td>
                      <td style='text-align: right;'><b>Allow  previous consultation display</b></td>

                    <td>
                         <?php
                      if($allow_display_prev =='1'){
                        echo '<input type="checkbox" name="allow_display_prev" checked />';
                      }else{
                         echo ' <input type="checkbox" name="allow_display_prev" />'; 
                      }
                    
                    ?>
                       
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;'><b>Set inpatient pre-paid service</b></td>

                    <td>
                         <?php
                      if($set_pre_paid =='1'){
                        echo '<input type="checkbox" name="set_pre_paid" checked />';
                      }else{
                         echo ' <input type="checkbox" name="set_pre_paid" />'; 
                      }
                    
                    ?>
                       
                    </td>
                     <td style='text-align: right;'><b>Set inpatient duplicate bed assignment</b></td>

                    <td>
                         <?php
                      if($set_duplicate_bed_assign =='1'){
                        echo '<input type="checkbox" name="set_duplicate_bed_assign" checked />';
                      }else{
                         echo ' <input type="checkbox" name="set_duplicate_bed_assign" />'; 
                      }
                    
                    ?>
                       
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;'><b>Enable Doctors Auto Save</b></td>

                    <td>
                         <?php
                      if($set_doctors_auto_save =='1'){
                        echo '<input type="checkbox" name="set_doctors_auto_save" checked />';
                      }else{
                         echo ' <input type="checkbox" name="set_doctors_auto_save" />'; 
                      }
                    
                    ?>
                       
                    </td>
                     <td style='text-align: right;'><b>Enable Doctor Date Chooser </b></td>

                    <td>
                         <?php
                      if($enable_doct_date_chooser =='1'){
                        echo '<input type="checkbox" name="enable_doct_date_chooser" checked />';
                      }else{
                         echo ' <input type="checkbox" name="enable_doct_date_chooser" />'; 
                      }
                    
                    ?>
                       
                </td>
                </tr>
                <tr>
                    <td style='text-align: right;'><b>Enable Patient Medical History </b></td>

                    <td>
                         <?php
                      if($enable_pat_medic_hist =='1'){
                        echo '<input type="checkbox" name="enable_pat_medic_hist" checked />';
                      }else{
                         echo ' <input type="checkbox" name="enable_pat_medic_hist" />'; 
                      }
                    ?>
                </td>
                <td style='text-align: right;'><b>Enable Clinical Notes Scroll </b></td>

                    <td>
                         <?php
                      if($enable_clinic_not_scroll =='1'){
                        echo '<input type="checkbox" name="enable_clinic_not_scroll" checked />';
                      }else{
                         echo ' <input type="checkbox" name="enable_clinic_not_scroll" />'; 
                      }
                    ?>
                </td>
                </tr>
                <tr>
                    <td style='text-align: right;'><b>Enable Special Dosage </b></td>

                    <td>
                         <?php
                      if($enable_spec_dosage =='1'){
                        echo '<input type="checkbox" name="enable_spec_dosage" checked />';
                      }else{
                         echo ' <input type="checkbox" name="enable_spec_dosage" />'; 
                      }
                    ?>
                </td>
                <td style='text-align: right;'><b>Enable Lab Result With No Parameter(s)</b></td>

                    <td>
                         <?php
                      if($enb_lab_wt_no_par =='1'){
                        echo '<input type="checkbox" name="enb_lab_wt_no_par" checked />';
                      }else{
                         echo ' <input type="checkbox" name="enb_lab_wt_no_par" />'; 
                      }
                    ?>
                </td>
                </tr>
                 <tr>
                      <td style='text-align: right;'><b>Require Opd Provisional Diagnosis</b></td>

                    <td>
                         <?php
                      if($req_op_prov_dign =='1'){
                        echo '<input type="checkbox" name="req_op_prov_dign" checked />';
                      }else{
                         echo ' <input type="checkbox" name="req_op_prov_dign" />'; 
                      }
                    ?>
                </td>
                    <td style='text-align: right;'><b>Require Ipd Provisional Diagnosis </b></td>

                    <td>
                         <?php
                      if($req_ip_prov_dign =='1'){
                        echo '<input type="checkbox" name="req_ip_prov_dign" checked />';
                      }else{
                         echo ' <input type="checkbox" name="req_ip_prov_dign" />'; 
                      }
                    ?>
                </td>
                </tr>
                <tr>
                    <td style='text-align: right;'><b>Require Opd Final Diagnosis</b></td>
                    <td>
                         <?php
                      if($req_op_final_dign =='1'){
                        echo '<input type="checkbox" name="req_op_final_dign" checked />';
                      }else{
                         echo ' <input type="checkbox" name="req_op_final_dign" />'; 
                      }
                    ?>
                </td>
                 <td style='text-align: right;'><b>Enable Consultation Per Day Count</b></td>
                    <td>
                         <?php
                      if($en_const_per_day_count =='1'){
                        echo '<input type="checkbox" name="en_const_per_day_count" checked />';
                      }else{
                         echo ' <input type="checkbox" name="en_const_per_day_count" />'; 
                      }
                    ?>
                </td>
                   
                </tr>
                <tr>
                <td style='text-align: right;'><b>Enable Performance By Signed Off</b></td>
                <td>
                         <?php
                      if($req_perf_by_signed_off =='1'){
                        echo '<input type="checkbox" name="req_perf_by_signed_off" checked />';
                      }else{
                         echo ' <input type="checkbox" name="req_perf_by_signed_off" />'; 
                      }
                    ?>
                </td>
                <td style='text-align: right;'><b>Enable Item Status For Patient File</b></td>
                <td>
                         <?php
                      if($en_item_status_pat_file =='1'){
                        echo '<input type="checkbox" name="en_item_status_pat_file" checked />';
                      }else{
                         echo ' <input type="checkbox" name="en_item_status_pat_file" />'; 
                      }
                    ?>
                </td>
				
                   
                </tr>
                 <tr>
                    <td style='text-align: right;'><b>Enable Inpatient Doctor Auto-Bill</b></td>
                    <td colspan="3">
                         <?php
                      if($en_inp_auto_bill =='1'){
                        echo '<input type="checkbox" name="en_inp_auto_bill" checked />';
                      }else{
                         echo ' <input type="checkbox" name="en_inp_auto_bill" />'; 
                      }
                    ?>
					</td>	 
                 </tr>
                 
                 <tr>
                     <td style='text-align: right;'><b>Mandatory Lab &amp; Radiology comments</b></td>
                    <td>
                         <?php
                      if($mandatory_comments =='1'){
                        echo '<input type="checkbox" name="mandatory_comments" checked />';
                      }else{
                         echo ' <input type="checkbox" name="mandatory_comments" />'; 
                      }
                    
                    ?>
                       
                    </td> 
                    
                    <td style='text-align: right;'><b>Doctor Admit Patient</b></td>
                     <td>
                         <?php
                      if($doctor_admits_patient =='yes'){
                        echo '<input type="checkbox" name="doctor_admits_patient" checked />';
                      }else{
                         echo ' <input type="checkbox" name="doctor_admits_patient" />'; 
                      }
                    
                    ?>
                       
                    </td>
                 </tr>
		<tr>
                    <td style='text-align: right;'><b><label for="Enable_Save_And_Transfer_Button">Enable Save And Transfer Button on doctor's clinical notes page</label></b></td>
                    <td>
                         <?php
                      if($Enable_Save_And_Transfer_Button =='1'){
                        echo '<input type="checkbox" name="Enable_Save_And_Transfer_Button"  id="Enable_Save_And_Transfer_Button" checked />';
                      }else{
                         echo ' <input type="checkbox" name="Enable_Save_And_Transfer_Button" id="Enable_Save_And_Transfer_Button"  />'; 
                      }
                    ?>
                    </td>
                    <td>
                        <b>Require Final Diagnosis before select Treatment</b>
                    </td>
                    <td>
                        <input type="checkbox" name="require_final_diagnosis_before_select_treatment" <?php if($require_final_diagnosis_before_select_treatment=="yes"){ echo "checked='checked'"; }?>/>
                    </td>
                </tr>	
                
                <!-- @Mfoy dn -->
                <tr>
                    <td colspan="4" style="text-align: inline;">
                        <div class="col-md-5">
                            <label for="">Max hours for Doctors to view clinical notes.(<i>In Hrs</i>)</label>
                        </div>
                        <div class="col-md-1">
                            <input type="text" class="form-control" name="doctor_notice_display_max_time" id="hrs" value="<?= $doctor_notice_display_max_time; ?>" style="text-align: center; max-width:50px;"> 
                        </div>
                        <div class="col-md-5">
                            <label for="">Max days to see patient in consulted list.(<i>In Days</i>)</label>
                        </div>
                        <div class="col-md-1">
                            <input type="text" class="form-control" name="consulted_patient_display_max_time" id="days" value="<?= $consulted_patient_display_max_time; ?>" style="text-align: center; max-width:50px;"> 
                        </div>
                    </td>
                </tr>
            <!-- // @Mfoy dn -->

                <tr>
                    <td colspan=4 style='text-align: center;padding-left:150px '>
                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                        <input type='hidden' name='submittedhospconstypeForm' value='true'/> 
                    </td>
                </tr>
            </form></table>
    </fieldset>

</center>


<?php
include("./includes/footer.php");
?> 