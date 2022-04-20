<?php
include("./includes/header.php");
include("./includes/connection.php");
include_once("./functions/items.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
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

# system audit trail
$current_id = $_SESSION['userinfo']['Employee_ID'];
include 'audit_trail_file.php';
$Audit = new Audit_Trail($current_id," Accessed ~ <b> Doctor Works Page </b>");
$Audit->perfomAuditActivities();
# system audit trail

//GET BRANCH ID
$Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];

$autoupdatenoshow = mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='AutoNoShow' and last_auto_update < CURDATE()") or die(mysqli_error($conn));

if (mysqli_num_rows($autoupdatenoshow) > 0) { //no show not updated today?
    //no show all according to hours
    $configvalue = trim(mysqli_fetch_assoc($autoupdatesignedoff)['configvalue']);

    if (!empty($configvalue) && is_numeric($configvalue) && $configvalue > 0) {
        //no show
        $has_error = false;
        Start_Transaction();

        $rs1 = mysqli_query($conn,"
                SELECT pl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID
                FROM  tbl_patient_payment_item_list pl INNER JOIN tbl_patient_payments pp ON  pp.Patient_Payment_ID = pl.Patient_Payment_ID
                JOIN tbl_patient_registration pr ON pp.Registration_ID = pr.Registration_ID

                WHERE pl.Process_Status= 'not served' AND
                      pl.Patient_Direction IN ('Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic','Direct To Clinic Via Nurse Station') AND
                      pp.Branch_ID = '$Folio_Branch_ID'  AND
                      pp.Transaction_status != 'cancelled' and TIMESTAMPDIFF(hour,Transaction_Date_And_Time,now()) >= $configvalue") or die(mysqli_error($conn));

        while ($rowUp = mysqli_fetch_array($rs1)) {
            $Patient_Payment_ID = $rowUp['Patient_Payment_ID'];
            $rs3 = mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Process_Status = 'no show' WHERE Patient_Payment_ID = $Patient_Payment_ID") or die(mysqli_error($conn));

            if (!$rs3) {
                $has_error = true;
            }

            $rs4 = mysqli_query($conn,"UPDATE tbl_patient_payments pp SET Transaction_status='cancelled' WHERE Patient_Payment_ID = $Patient_Payment_ID AND pp.Sponsor_ID != (SELECT Sponsor_ID FROM tbl_sponsor WHERE LOWER(Guarantor_Name) = 'cash')") or die(mysqli_error($conn));

            if (!$rs4) {
                $has_error = true;
            }

        }

        $rs5 = mysqli_query($conn,"UPDATE tbl_config pp SET last_auto_update = CURDATE() WHERE configname='autonoshow'") or die(mysqli_error($conn));

        if (!$rs5) {
            $has_error = true;
        }

        if (!$has_error) {
            Commit_Transaction();
        } else {
            Rollback_Transaction();
        }
    }
}



$autoupdatesignedoff = mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='AutoSignedOff' and last_auto_update < CURDATE()") or die(mysqli_error($conn));

if (mysqli_num_rows($autoupdatesignedoff) > 0) { //signedoff not updated today?
    //signed off all according to hours
    $configvalue = trim(mysqli_fetch_assoc($autoupdatesignedoff)['configvalue']);
    if (!empty($configvalue) && is_numeric($configvalue) && $configvalue > 0) {
        //signedoff
        $has_error = false;
        Start_Transaction();

        $rs1 = mysqli_query($conn,"
                SELECT pl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID
                FROM  tbl_patient_payment_item_list pl INNER JOIN tbl_patient_payments pp ON  pp.Patient_Payment_ID = pl.Patient_Payment_ID
                JOIN tbl_patient_registration pr ON pp.Registration_ID = pr.Registration_ID

                WHERE pl.Process_Status= 'served' AND
                      pl.Patient_Direction IN ('Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic','Direct To Clinic Via Nurse Station') AND
                      pp.Branch_ID = '$Folio_Branch_ID'  AND
                       TIMESTAMPDIFF(hour,Transaction_Date_And_Time,now()) >= $configvalue") or die(mysqli_error($conn));

        while ($rowUp = mysqli_fetch_array($rs1)) {
            $Patient_Payment_ID = $rowUp['Patient_Payment_ID'];
            $rs3 = mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Process_Status = 'signedoff' WHERE Patient_Payment_ID = $Patient_Payment_ID") or die(mysqli_error($conn));

            if (!$rs3) {
                $has_error = true;
            }

        }

        $rs5 = mysqli_query($conn,"UPDATE tbl_config pp SET last_auto_update = CURDATE() WHERE configname='autosigneoff'") or die(mysqli_error($conn));

        if (!$rs5) {
            $has_error = true;
        }

        if (!$has_error) {
            Commit_Transaction();
        } else {
            Rollback_Transaction();
        }
    }
}
////Remove  3 days
//
//if (isset($_SESSION['NO_SHOW_SESSION_UPDATE'])) {
//    if ($_SESSION['NO_SHOW_SESSION_UPDATE'] == 'No') {
//        $delete_qr = "UPDATE tbl_patient_payment_item_list SET Process_Status = 'no show' WHERE DATEDIFF(NOW(), Transaction_Date_And_Time) > 3 AND
//                Patient_Direction = 'Direct To Clinic' ";
//
//        if (!mysqli_query($conn,$delete_qr)) {
//            die(mysqli_error($conn));
//        } else {
//            $_SESSION['NO_SHOW_SESSION_UPDATE'] = 'Yes';
//        }
//    }
//}

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$permit = mysqli_query($conn,"SELECT Employee_Type FROM tbl_employee WHERE Employee_ID = '$Employee_ID' ") or die(mysqli_error($conn));
$row = mysqli_fetch_array($permit);
$Employee_Type = $row['Employee_Type'];
        
?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
        ?>
        <a href='index.php?Bashboard=BashboardThisPage' class='art-button-green'>
            BACK
        </a>
        <?php
    }
}
?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
    <div id="select_ward" style="display:none;">
    <style type="text/css">
                #spu_lgn_tbl{
                    width:100%;
                    border:none!important;
                }
                #spu_lgn_tbl tr, #spu_lgn_tbl tr td{
                    border:none!important;
                    padding: 15px;
                    font-size: 14PX;
                }
    </style>
    <table  id="spu_lgn_tbl">
                <tr>
                   <td style="text-align:right">
                        Select Your working Department
                   </td>
                   <td style="width:60%">
                       <select id="working_department_ipd" style="width:100%">
                        <option selected='selected'></option>
                            <?php 
                                $sql_select_working_department_result=mysqli_query($conn,"SELECT finance_department_code,finance_department_id,finance_department_name FROM tbl_finance_department WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_working_department_result)>0){
                                    while($finance_dep_rows=mysqli_fetch_assoc($sql_select_working_department_result)){
                                       $finance_department_id=$finance_dep_rows['finance_department_id'];
                                       $finance_department_name=$finance_dep_rows['finance_department_name'];
                                       $finance_department_code=$finance_dep_rows['finance_department_code'];
                                       echo "<option value='$finance_department_id'>$finance_department_name-->$finance_department_code</option>";
                                    }
                                }
                            ?>
                        </select>
                   </td>
                </tr>
                <tr id="select_ward">
                    <td style="text-align:right">
                        Select Your working Ward
                    </td>
                    <td>
                    <div id="options_list">
                        <select  name='Ward_ID' style='width: 100%;height:30%'  id="Ward_ID" onclick='clearFocus(this)' required='required'>
                            <option selected='selected'> Select Your working Department First </option>
                        </select>
                    </div>
                    </td>
                </tr>
                <td colspan="2" align="right">
                    <input type="button" onclick="post_ward_id()" class="art-button-green" value="Open"/>
                </td>
        </tr> 
    </table>
</div>
<script>
    function post_ward_id(){
       var Ward_ID=$("#Ward_ID").val();
       var working_department=$("#working_department_ipd").val();
       if(Ward_ID==''||Ward_ID==null){
          alert("select ward first") 
          exit 
       }
       if(working_department==''||working_department==null){
          alert("select your working department first") 
          exit 
       }
       document.location="inpatientdoctorspage_select_ward.php?Ward_ID="+Ward_ID+'&finance_department_id='+working_department;
    }
    function select_ward_dialog(){
          $("#select_ward").dialog({
                        title: 'SELECT YOUR WARKING DEPARTMENT AND WARD',
                        width: '50%',
                        height: 250,
                        modal: true,
                    });
    }
</script>
<br/><br/>
<fieldset>
    <legend align="center" ><b>DOCTOR`S WORKS</b></legend>
    <br/><br/>

    <div id="select_clinic" style="display:none;">
    <style type="text/css">
                #spu_lgn_tbl{
                    width:100%;
                    border:none!important;
                }
                #spu_lgn_tbl tr, #spu_lgn_tbl tr td{
                    border:none!important;
                    padding: 15px;
                    font-size: 14PX;
                }
    </style>
    <table  id="spu_lgn_tbl" style="width:100%">
                <tr id="select_clinic">
                    <td style="text-align:right">
                        Select Your working Clinic
                    </td>
                    <td style="width:60%">
                        <select  style='width: 100%;height:30%'  name='Clinic_ID' id='Clinic_ID' value='<?php echo $Guarantor_Name; ?>'  required='required'>
                            <option selected='selected'></option>
                            <?php

                            /*
                                UNCOMMENT THIS CODE IF YOU WANT TO ALL CLINICS TO APPREAR TO EACH DOCTOR,
                                MAKE SURE YOU UNCOMMENT THE BELOW CODE
                            */
                            

                             $Select_Consultant = "select * from tbl_clinic where Clinic_Status = 'Available'";
                            $result = mysqli_query($conn,$Select_Consultant);
                            ?>
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <option value="<?php echo $row['Clinic_ID']; ?>"><?php echo $row['Clinic_Name']; ?></option>
                                <?php
                            }

                            

                            /*############################## END OF THE FIRST CODE ##################################*/

                            /*================ According to finance dipartment =====================*/
                            // $Select_Consultant = "SELECT * from tbl_clinic c, tbl_clinic_employee ce where c.Clinic_ID = ce.Clinic_ID AND ce.Employee_ID = '$Employee_ID' AND c.Clinic_Status = 'Available'";
                            // $result = mysqli_query($conn,$Select_Consultant);
                            ?>
                            <?php
                            //while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <!-- <option value="<?php echo $row['Clinic_ID']; ?>"><?php echo $row['Clinic_Name']; ?></option> -->
                                <?php
                           // }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right">
                        Select Clinic Location
                    </td>
                    <td style="width:60%">
                        <select  style='width: 100%;height:30%'  name='clinic_location_id' id='clinic_location_id' required='required'>
                            <option selected='selected'></option>
                            <?php
                             $Select_Consultant = "SELECT sub.Sub_Department_ID,sub.Sub_Department_Name from tbl_sub_department sub,tbl_department de WHERE sub.Department_ID= de.Department_ID AND Department_Location='Clinic'";
                            $result = mysqli_query($conn,$Select_Consultant);
                            ?>
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <option value="<?php echo $row['Sub_Department_ID']; ?>"><?php echo $row['Sub_Department_Name']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                   <td style="text-align:right">
                        Select Your working Department 
                   </td>
                   <td style="width:60%">
                       <select id="working_department" style="width:100%">
                            <option value=""></option>
                            <?php
                                /*UNCOMMENT THIS CODES IF YOU YOU WANT ALL DEPARTMENTS TO APPEAR,
                                    MAKE SURE YOU UNCOMMENT THE BELOW CODE
                                */
                                
                                $sql_select_working_department_result=mysqli_query($conn,"SELECT finance_department_code,finance_department_id,finance_department_name FROM tbl_finance_department WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_working_department_result)>0){
                                    while($finance_dep_rows=mysqli_fetch_assoc($sql_select_working_department_result)){
                                       $finance_department_id=$finance_dep_rows['finance_department_id'];
                                       $finance_department_name=$finance_dep_rows['finance_department_name'];
                                       $finance_department_code=$finance_dep_rows['finance_department_code'];
                                       echo "<option value='$finance_department_id'>$finance_department_name-->$finance_department_code</option>";
                                    }
                                }
                                /*################# END OF COMMENT ################*/

                                /*
                                    COMMENT THIS CODE IF YOU WANT ALL FINANCIAL DEPARTMENT TO APPEAR TO EACH DOCTOR,
                                    MAKE SURE YOU UNCOMMENT THE ABOVE CODE
                                 */

                                 /*============ Will appear According to finance dipartment assigned to employee ==================*/
                                // $sql_select_working_department_result=mysqli_query($conn,"SELECT fd.finance_department_code, fd.finance_department_id,fd.finance_department_name FROM tbl_finance_department fd, tbl_assign_finance_department afd WHERE fd.finance_department_id = afd.finance_department_id AND afd.Employee_ID = '$Employee_ID' AND enabled_disabled='enabled'") or die(mysqli_error($conn));
                                // if(mysqli_num_rows($sql_select_working_department_result)>0){
                                //     while($finance_dep_rows=mysqli_fetch_assoc($sql_select_working_department_result)){
                                //        $finance_department_id=$finance_dep_rows['finance_department_id'];
                                //        $finance_department_name=$finance_dep_rows['finance_department_name'];
                                //        $finance_department_code=$finance_dep_rows['finance_department_code'];
                                //        echo "<option value='$finance_department_id'>$finance_department_name-->$finance_department_code</option>";
                                //     }
                                // }

                                /*################## END OF THE CODE ###########################*/
                                
                                
                            ?>
                        </select>
                   </td>
                </tr>
                <td colspan="2" align="right">
                    <input type="button" onclick="post_clinic_id()" class="art-button-green" value="Open"/>
                </td>
        </tr>
    </table>
</div>
<script>
    function post_clinic_id(){
       var Clinic_ID=$("#Clinic_ID").val();
       var working_department=$("#working_department").val();
       var clinic_location_id=$("#clinic_location_id").val();
       if(Clinic_ID==''||Clinic_ID==null){
          alert("select clinic first")
          exit
       }
       if(clinic_location_id==''||clinic_location_id==null){
          alert("select clinic location")
          exit
       }
       if(working_department==''||working_department==null){
          alert("select your working department first")
          exit
       }
       document.location="doctorspage_select_clinic.php?Clinic_ID="+Clinic_ID+'&finance_department_id='+working_department;
    }
    function select_clinic_dialog(){
          $("#select_clinic").dialog({
                        title: 'SELECT CLINIC',
                        width: '50%',
                        height: 300,
                        modal: true,
                    });
    }
</script>



    <center><div class="col-md-3"></div><div class="col-md-6"><table width = 60% class="table">
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {

                       $sql_check_if_has_one_clinic_result=mysqli_query($conn,"select * from tbl_clinic where Clinic_Status = 'Available'") or die(mysqli_error($conn));
                       if(mysqli_num_rows($sql_check_if_has_one_clinic_result)==1){
                           $Clinic_ID=mysqli_fetch_assoc($sql_check_if_has_one_clinic_result)['Clinic_ID'];
                            ?>
                                <a href="doctorspage_select_clinic.php?Clinic_ID=<?= $Clinic_ID ?>">
                                    <button style='width: 100%; height: 100%'>
                                        Doctor's Works Outpatient
                                    </button>
                                </a>
                            <?php
                       }else{
                         ?>
                        <a href='#' onclick="select_clinic_dialog()">
                            <button style='width: 100%; height: 100%'>
                                Doctor's Works Outpatient
                            </button>
                        </a>
                            <?php
                        }
                    } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Doctor's Works Page
                        </button>

                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] == 'yes'){ ?>
                    <a href='#' onclick="select_ward_dialog()">
                        <button style='width: 100%; height: 100%'>
                            Doctor's Works Inpatient
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Doctor's Works Inpatient
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
            <tr>
            <?php if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') { ?>
                <?php //if ($Employee_Type == 'Doctor') { ?>
                        <td style='text-align: center; height: 40px; width: 33%;'>
                            <!--<a href='individualdoctorsperformancesummary.php?DoctorsPerformanceSummary=DoctorsPerformanceSummaryThisPage'>--->
                            <a href='doctorsPerformanceSummaryFilter.php?DoctorsPerformanceReportThisPage=ThisPage&from_doctors_page=yes&this_page_from=doctor_performance'>
                                <button style='width: 100%; height: 100%'>
                                    My Work Performance Report
                                </button>
                            </a>
                        </td>
                <?php // } ?>
            <?php } else { ?>
                    <td style='text-align: center; height: 40px; width: 33%;' >
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            My Work Performance Report
                        </button>
                    </td>
            <?php } ?>
            <?php if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') { ?>
                <?php //if ($Employee_Type == 'Doctor') { ?>
                        <td style='text-align: center; height: 40px; width: 33%;'>
                            <a href='surgery_performance_report.php?loc=doctor'>
                                <button style='width: 100%; height: 100%'>
                                    My Surgery Performance Report
                                </button>
                            </a>
                        </td>
                    <?php
                //}
            }
            ?>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') { ?>
                        <a href='searchappointmentPatient.php?doc=<?php echo $Employee_ID; ?>'>
                            <button style='width: 100%; height: 100%'>
                                My Appointments
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            My Appointments
                        </button>

                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') { ?>
                        <a href='transferdoctor.php?TranserDoctor=TranserDoctorThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Patient Transfer
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patient Transfer
                        </button>

                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') { ?>
                        <a href='patientsnoshowreport.php?Section=Doctor&ItemsConfiguration=ItemConfigurationThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Patients List No Show Report
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patients List No Show Report
                        </button>

                    <?php } ?>
                </td>

            <!--<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') { ?>
                        <a href='itemsconfiguration.php?Section=Doctor&ItemsConfiguration=ItemConfigurationThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Items Configuration
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Items Configuration
                        </button>

                    <?php } ?>
                </td>
            </tr>

            ----->

                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') { ?>
                        <a href='Surgery_Appointments.php?Section=Doctor&ItemsConfiguration=ItemConfigurationThisPage&Section=Doctor'>
                            <button style='width: 100%; height: 100%'>
                                Surgery Appointments
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Surgery Appointments
                        </button>

                    <?php } ?>
                </td>
            </tr>



            <tr>
                <td style='text-align: center; height: 40px; width: 33%;' colspan="2">
                    <?php if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') { ?>
                        <a href='Surgery_Patient_List.php?Section=Doctor&ItemsConfiguration=ItemConfigurationThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Surgery Patient List
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Surgery Patient List
                        </button>

                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;' class="hide">
                    <?php if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') { ?>
                        <a href='searchnurseform.php?section=Nurse&NurseWorks=NurseWorksThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Nurse Station Works
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Nurse Station Works
                        </button>

                    <?php } ?>
                </td>
            </tr>
            <?php if($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] == 'yes'){ ?>
                    <?php
			$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
			$permit=mysqli_query($conn,"SELECT * FROM tbl_employee WHERE Employee_ID = '$Employee_ID' ") or die(mysqli_error($conn));
			$row=mysqli_fetch_array($permit);
			$Employee_Type=$row['Employee_Type'];
			if($Employee_Type == 'Doctor' ){ ?>
			<tr>
			    <td style='text-align: center; height: 40px; width: 33%;'>
                                <a href='doctorsindivinpatientperform.php'>
				    <button style='width: 100%; height: 100%'>
					 My Round Performance Report
				    </button>
				</a>
			    </td>
			<?php } ?>
			<?php }else{ ?>
			 <tr>
			    <td style='text-align: center; height: 40px; width: 33%;'>
				<button style='width: 100%; height: 100%' onclick="return access_Denied();">
				    My Round Performance Report
				</button>
			    </td>
			</tr>
			<?php } ?>
            <?php if ($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] == 'yes') { ?>
                        <?php if ($Employee_Type == 'Doctor') { ?>
                                    <td style='text-align: center; height: 40px; width: 33%;'>
                                        <a href='Surgery_Appointments.php?Section=Doctor&ItemsConfiguration=ItemConfigurationThisPage&Inpatient=yes'>
                                            <button style='width: 100%; height: 100%'>
                                                Surgery Appointment List
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                    <?php }

                     } ?>
                               <tr>
				<td style='text-align: center; height: 40px; width: 33%;' colspan="2" class="hide">
				    <a href='searchpatientinward.php?section=Admission&AdmisionWorks=AdmisionWorksThisPage'>
					<button style='width: 100%; height: 100%'>
					     Nurse Comunication / Documentation
					</button>
				    </a>
				</td>
			    </tr>
        </table></div>
    </center><br/><br/><br/><br/>
</fieldset><br/>
<input type="hidden" id="employee_id" value="<?=$Employee_ID?>">
<div id="rdtc_dialogy"></div>
<script>
    $(document).ready(function(e) {
        $("#working_department_ipd").select2();
        $("#Ward_ID").select2();
    });
</script>

<script>
    $(document).ready(function() {
        $("#working_department_ipd").change(function(){
            var depertment_id=$('#working_department_ipd option:selected').val();
            $.ajax({
                type:'POST',
                url:'ajax_get_wards.php',
                data:{depertment_id:depertment_id},
                success:function(data){
                    $("#options_list").html(data); 
                    $("#Ward_ID").select2();
                }
                // error:function{alert('error')}
            });
        });
    });

    function open_rdtc_setup_dialogy() {
        var employee_id = '<?=$Employee_ID?>';
        console.log("clicked");
        $.ajax({
            type: 'post',
            url: 'rdtc_setup.php',
            data: {
                employee_id: employee_id
            },
            success: (data) => {

                $("#rdtc_dialogy").dialog({
                    title: 'EUROPEAN BASELINE SERIES S-1000',
                    width: '90%',
                    height: 800,
                    modal: true,
                });
                $("#rdtc_dialogy").html(data);
                $("#rdtc_dialogy").dialog("open");
            }
        });
    }
</script>

</script>
<script>
    $(document).ready(function (e){
        $("#clinic_location_id").select2();
        $("#working_department").select2();
        $("#Clinic_ID").select2();
    });
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<?php
include("./includes/footer.php");
?>
