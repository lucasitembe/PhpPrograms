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

if (isset($_SESSION['userinfo'])) {
    //if ($_SESSION['userinfo']['Admission_Works'] == 'yes') {
if($_SESSION['outpatient_nurse_com']=="no"){
        ?>
        <a href='searchpatientinward.php?Registration_ID=<?php echo filter_input(INPUT_GET, 'Registration_ID'); ?>&BackTonurseCommunication=BackTonurseCommunicationPage' class='art-button-green'>
            PATIENT LIST
        </a>
        <?php
    }else{
      ?>
       <a href='searchnurseform.php?section=Nurse&NurseWorks=NurseWorksThisPage' class='art-button-green'>
            PATIENT LIST
        </a>
      <?php
    }
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


if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}



if (isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != 0) {
    $select_patien_details = mysql_query("
		SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM
				tbl_patient_registration pr,
				tbl_sponsor sp
			WHERE
				pr.Registration_ID = '" . $Registration_ID . "' AND
				sp.Sponsor_ID = pr.Sponsor_ID
				") or die(mysql_error());
    $no = mysql_num_rows($select_patien_details);
    if ($no > 0) {
        while ($row = mysql_fetch_array($select_patien_details)) {
            $Member_Number = $row['Member_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $Guarantor_Name  = $row['Guarantor_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $DOB = $row['Date_Of_Birth'];
        }
    } else {
        $Guarantor_Name  = '';
        $Member_Number = '';
        $Patient_Name = '';
        $Gender = '';
        $Registration_ID = 0;
    }
} else {
    $Member_Number = '';
    $Patient_Name = '';
    $Gender = '';
    $Registration_ID = 0;
}

$age = date_diff(date_create($DOB), date_create('today'))->y;
$checkgender = '';
if (strtolower($Gender) == 'male') {
    $checkgender = "onclick='notifyUser(this)'";
}

$query2 = mysql_query("SELECT sb.Item_Subcategory_ID,sb.Item_Subcategory_Name FROM `tbl_item_subcategory` sb INNER JOIN tbl_items i ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` WHERE i.Consultation_Type='Radiology' GROUP BY i.`Item_Subcategory_ID` ORDER BY sb.Item_Subcategory_Name ") or die(mysql_error());
$dataSubCategory = '';
$dataSubCategory .= '<option value="All">All Subcategory</option>';

while ($row = mysql_fetch_array($query2)) {
    $dataSubCategory .= '<option value="' . $row['Item_Subcategory_ID'] . '">' . $row['Item_Subcategory_Name'] . '</option>';
}

$nav = '';

if (isset($_GET['discharged'])) {
    $nav = '&discharged=discharged';
}

//get last Patient_Bill_ID
$Check_In_ID = mysql_fetch_assoc(mysql_query("select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_Date_And_Time desc limit 1"))['Check_In_ID'];
//get last Patient_Bill_ID
$select = mysql_query("select Patient_Bill_ID, Sponsor_ID, Folio_Number from tbl_patient_payments where
							Registration_ID = '$Registration_ID' and
							Check_In_ID = '$Check_In_ID' order by Patient_Payment_ID desc limit 1") or die(mysql_error());
$num = mysql_num_rows($select);
if ($num > 0) {
    while ($data = mysql_fetch_array($select)) {
        $Patient_Bill_ID = $data['Patient_Bill_ID'];
        $Folio_Number = $data['Folio_Number'];
    }
} else {
    $Patient_Bill_ID = 0;
    $Folio_Number = 0;
}
$Transaction_type=mysql_fetch_assoc(mysql_query("SELECT Transaction_type FROM tbl_patient_payments WHERE Registration_ID = '$Registration_ID' AND Patient_Bill_ID = '$Patient_Bill_ID' AND Folio_Number = '$Folio_Number' LIMIT 1"))['Transaction_type'];
//echo $Transaction_type;
//exit();
?>

<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }

</script>

<script type='text/javascript'>
    function consulted_access_Denied() {
        alert("Access denied!");
    }
</script>

<br/><br/>

<fieldset>
    <!--<legend align="center"><b>NURSE COMMUNICATION</b></legend>-->
    <legend align="center" style='padding:10px; color:white; background-color:#2D8AAF; text-align:center'><b>
            <b>NURSE COMMUNICATION</b><br/>
            <span style='color:yellow;'><?php echo "" . $Patient_Name . "  | " . $Gender . " | " . $age . " years | " . $Guarantor_Name  . ""; ?></span></b>
    </legend>
    <center>
        <table width = 60%>



          <tr>
              <td style='text-align: center; height: 40px; width: 33%;'>
                  <a href="labour_atenal_neonatal_record.php?consultation_id=<?= $consultation_ID; ?>&patient_id=<?=$Registration_ID;?>&admission_id=<?=$Admision_ID?>&discharged=<?=$_GET['discharged']?>"><button style='width: 100%; height: 100%'>Labour Atenal Neonatal Record</button>
                  </a>

              </td>
              <td style='text-align: center; height: 40px;'>
                        <a href='./neonatal_record/neonatal_record.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Admision_ID=<?php echo $_GET['Admision_ID']; ?>&consultation_ID=<?php echo $_GET['consultation_ID']; ?><?php echo $nav ?>&LabourWardNurseNotes=LabourWardNurseNotesThisPage'>
                          <button style='width: 100%; height: 100%'>NEONATAL RECORD </button>
                        </a>
                      </td>
          </tr>
          <tr>

            <tr>

                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php
                        $sql_select_info= mysql_query("select Registration_ID from tbl_testing_record where Registration_ID = '$Registration_ID'") or die(mysql_error());
                        $blood_glucose_test=mysql_num_rows($sql_select_info);
                    ?>
                    <a href='nursecommunication_bloodtest.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Admision_ID=<?php echo $_GET['Admision_ID']; ?>&consultation_ID=<?php echo $_GET['consultation_ID']; ?><?php echo $nav ?>'><button style='width: 100%; height: 100%'>Blood Glucose Test Record   <span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $blood_glucose_test; ?></span></button></a>

                </td>

                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php
                        $sql_select_info= mysql_query("select Registration_ID from tbl_nursecommunication_observation where Registration_ID = '$Registration_ID'") or die(mysql_error());
                        $observation_chart=mysql_num_rows($sql_select_info);
                    ?>
                    <a href='nursecommunication_observation.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Admision_ID=<?php echo $_GET['Admision_ID']; ?>&consultation_ID=<?php echo $_GET['consultation_ID']; ?><?php echo $nav ?>'><button style='width: 100%; height: 100%'>Observation Chart <span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $observation_chart; ?></span></button></a>

                </td>


            </tr>
            <tr>
              <td style='text-align: center; height: 40px; width: 33%;'>
                  <?php
                      $sql_select_info= mysql_query("select Registration_ID from tbl_patient_nursecare where Registration_ID = '$Registration_ID'") or die(mysql_error());
                      $nurse_care_plan=mysql_num_rows($sql_select_info);
                  ?>
                  <a href='nursecommunication_nursecare.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Admision_ID=<?php echo $_GET['Admision_ID']; ?>&consultation_ID=<?php echo $_GET['consultation_ID']; ?><?php echo $nav ?>'><button style='width: 100%; height: 100%'>Nursing Care Plan Chart <span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $nurse_care_plan; ?></span></button></a>

              </td>

              <td style='text-align: center; height: 40px; width: 33%;'>
                  <?php
                      $sql_select_info= mysql_query("select Registration_ID from tbl_input_output_nursecommunication where Registration_ID = '$Registration_ID'") or die(mysql_error());
                      $intake_n_output=mysql_num_rows($sql_select_info);
                  ?>
                  <a href='nursecommunication_intakeOutput.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Admision_ID=<?php echo $_GET['Admision_ID']; ?>&consultation_ID=<?php echo $_GET['consultation_ID']; ?><?php echo $nav ?>'><button style='width: 100%; height: 100%'>Intake and Output Chart  <span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $intake_n_output; ?></span></button></a>

              </td>
            </tr>
            <tr>

                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php
                        $sql_select_info= mysql_query("select Registration_ID from tbl_pre_operative_checklist where Registration_ID = '$Registration_ID'") or die(mysql_error());
                        $pre_operative_checklist=mysql_num_rows($sql_select_info);
                    ?>
                    <?php
                        if($_SESSION['outpatient_nurse_com'] == 'yes'){
                          ?>       <a href='outpatient_nursecommunication_preOperative.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Admision_ID=<?php echo $_GET['Admision_ID']; ?>&consultation_ID=<?php echo $_GET['consultation_ID']; ?><?php echo $nav ?>'><button style='width: 100%; height: 100%'>Pre-Operative Check List <span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $pre_operative_checklist; ?></span></button></a>
                       <?php
                             }else{
                        ?>
                            <a href='nursecommunication_preOperative.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Admision_ID=<?php echo $_GET['Admision_ID']; ?>&consultation_ID=<?php echo $_GET['consultation_ID']; ?><?php echo $nav ?>'><button style='width: 100%; height: 100%'>Pre-Operative Check List  <span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $pre_operative_checklist; ?></span></button></a>
                        <?php } ?>
                </td>

                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php
                        $sql_select_info= mysql_query("select Registration_ID from tbl_patient_progress where Registration_ID = '$Registration_ID'") or die(mysql_error());
                        $patient_progress_report=mysql_num_rows($sql_select_info);
                    ?>
                    <a href='patientprogressreport.php?Registration_ID=<?php echo $Registration_ID; ?>&Consultation_ID=<?php echo $consultation_ID; ?>&Admision_ID=<?php echo $Admision_ID . $nav; ?>&PatientProgressReport=PatientProgressReportThisPage'><button style='width: 100%; height: 100%'>Patient Progress Report  <span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $patient_progress_report; ?></span></button></a>

                </td>
            </tr>
            <tr>

                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php
                        $sql_select_info= mysql_query("select Registration_ID from tbl_mulnutrition_observation where Registration_ID = '$Registration_ID'") or die(mysql_error());
                        $mulnutrition=mysql_num_rows($sql_select_info);
                    ?>
                    <a href='nursecommunication_mulnutrition.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Admision_ID=<?php echo $_GET['Admision_ID']; ?>&consultation_ID=<?php echo $_GET['consultation_ID']; ?><?php echo $nav ?>'><button style='width: 100%; height: 100%'>Malnutrition Observation Chart <span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $mulnutrition; ?></span></button></a>

                </td>

                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php
                        $sql_select_info= mysql_query("select Registration_ID from tbl_nursecommunication_paediatric where Registration_ID = '$Registration_ID'") or die(mysql_error());
                        $paediatric=mysql_num_rows($sql_select_info);
                    ?>
                    <a href='nursecommunication_paediatric.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Admision_ID=<?php echo $_GET['Admision_ID']; ?>&consultation_ID=<?php echo $_GET['consultation_ID']; ?><?php echo $nav ?>'><button style='width: 100%; height: 100%'>Paediatric ward-observation chart  <span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $paediatric; ?></span></button></a>

                </td>

            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                <?php
                
                $Registration_ID=$_GET['Registration_ID'];
                $consultation_ID=$_GET['consultation_ID'];
               
                ?>
               
                <a href="all_patient_file_link_station.php?Registration_ID=<?= $Registration_ID ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&this_page_from=nurse_communication&this_page_from=nurse_communication&consultation_ID=<?= $consultation_ID ?>" ><button style='width: 100%; height: 100%'>PATIENT FILE</button></a>

<!--                    <a href="Patientfile_Record_Detail.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>" target="_blank"><button style='width: 100%; height: 100%'>PATIENT FILE</button></a>-->
                </td>

                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href="#">
                      <button style='width: 100%; height: 100%'>ICU</button></a>
                </td>

            </tr>

        </table>
    </center>
</fieldset>
<div id="Something_Wrong">
    <center>Process fail!. Please try again</center>
</div>
<div id="No_Items_Found">
    <center>Process fail!. No items found</center>
</div>
<div id="Add_Items">
    <center id="Details_Area">

    </center>
</div>
<div id="Zero_Price_Or_Quantity_Alert">
    <center>Process fail!. Some items missing Price or Quantity.</center>
</div>
<div id="Successful_Dialogy">
    <center>Selected items added successfully</center>
</div>

<div id="Unsuccessful_Dialogy">
    <center>Process Fail! Please try again</center>
</div>

<fieldset>
    <legend align=center><b>NURSE CARE</b></legend>
    <center>
        <table width = 60%>

            <tr>
                <td style='text-align: center; height: 40px;display: none' width="20%">
                    <?php
                        $sql_select_info= mysql_query("select Registration_ID from tbl_inpatient_services_given where Registration_ID = '$Registration_ID'") or die(mysql_error());
                        $services=mysql_num_rows($sql_select_info);
                    ?>
                    <a href='InpatientNurseServices.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Admision_ID=<?php echo $_GET['Admision_ID']; ?>&consultation_ID=<?php echo $_GET['consultation_ID']; ?><?php echo $nav ?>'><button style='width: 100%; height: 100%'>Service  <span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $services; ?></span></button></a>
                </td>
                <td style='text-align: center; height: 40px;'colspan="2" width="20%">
                    <?php
                        $sql_select_info= mysql_query("select Registration_ID from tbl_nurse_notes where Registration_ID = '$Registration_ID'") or die(mysql_error());
                        $nurse_notes=mysql_num_rows($sql_select_info);
                    ?>
                    <?php
                        if($_SESSION['outpatient_nurse_com'] == 'yes'){
                          ?>
                            <a href='outpatient_nursenotes.php?Registration_ID=<?php echo $Registration_ID; ?>&Admision_ID=<?php echo $Admision_ID; ?>&consultation_ID=<?php echo $consultation_ID; ?>&NurseNotes=NurseNotesThisPage'><button style='width: 100%; height: 100%'>Nurse Notes <span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $nurse_notes; ?></button></a>
                      <?php }else{
                            ?>
                                  <a href='nursenotes.php?Registration_ID=<?php echo $Registration_ID; ?>&Admision_ID=<?php echo $Admision_ID; ?>&consultation_ID=<?php echo $consultation_ID; ?>&NurseNotes=NurseNotesThisPage'><button style='width: 100%; height: 100%'>Nurse Notes <span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $nurse_notes; ?></button></a>
                                <?php
                        } ?>
                </td>

                <td style='text-align: center; height: 40px;' width="20%">

                    <?php

                        $sql="SELECT pr.Registration_ID,pr.Old_Registration_Number,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,ppl.Transaction_Date_And_Time,ppl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID,sp.Guarantor_Name
                FROM  tbl_patient_payment_item_list ppl INNER JOIN tbl_patient_payments pp ON  pp.Patient_Payment_ID = ppl.Patient_Payment_ID
                JOIN tbl_patient_registration pr ON pp.Registration_ID = pr.Registration_ID
                JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                WHERE pr.Registration_ID='$Registration_ID'
                GROUP BY pp.Registration_ID ORDER BY ppl.Transaction_Date_And_Time
            ";

    //die($sql);

    $select_Patient = mysql_query($sql) or die(mysql_error());

    while($row = mysql_fetch_array($select_Patient)){
$Registration_ID=$row['Registration_ID'];
$Patient_Payment_Item_List_ID=$row['Patient_Payment_Item_List_ID'];


	}

                    ?>
                    <?php
                        $sql_select_info= mysql_query("select Registration_ID from tbl_nurse where Registration_ID = '$Registration_ID'") or die(mysql_error());
                        $vital_signs=mysql_num_rows($sql_select_info);
                    ?>
                    <a href="nurseregistration.php?Registration_ID=<?php echo $Registration_ID ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID ?>"><button style='width: 100%; height: 100%'>Vital Signs  <span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $vital_signs; ?></button></a>
                </td>
            </tr>
            <tr>
                <?php if (strtolower($Gender) == 'male') { ?>
                <td  colspan="3">
                    <table style="width:100%">
                        <tr>
                            <td style='text-align: center; height: 40px;'>
                                <?php
                                    $sql_select_info= mysql_query("select Registration_ID from tbl_inpatient_medicines_given where Registration_ID = '$Registration_ID'") or die(mysql_error());
                                    $medication=mysql_num_rows($sql_select_info);
                                ?>
                                <a href='Inpatient_Nurse_Medicine.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Admision_ID=<?php echo $_GET['Admision_ID']; ?>&consultation_ID=<?php echo $_GET['consultation_ID']; ?><?php echo $nav ?>'><button style='width: 100%; height: 100%'>Medication Administration  <span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $medication; ?></button></a>
                            </td>
                            <td style='text-align: center; height: 40px;'>
                                 <?php
                                       $Registration_ID=$_GET['Registration_ID'];
                                       $Admision_ID=$_GET['Admision_ID'];
                                       $consultation_ID=$_GET['consultation_ID'];
                                    $sql_select_checkin_id="SELECT Check_In_ID FROM tbl_check_in_details WHERE Registration_ID='$Registration_ID' AND Admission_ID='$Admision_ID' AND consultation_ID='$consultation_ID'";
                                     $sql_select_checkin_id_result=mysql_query($sql_select_checkin_id) or die(mysql_error());
                                     $rows_checkin=mysql_fetch_assoc($sql_select_checkin_id_result);
                                     $Check_In_ID=$rows_checkin['Check_In_ID'];

                                     if($_SESSION['outpatient_nurse_com'] == 'yes'){
                                             $sql_select_prepaid_id="SELECT Prepaid_ID FROM tbl_prepaid_details WHERE Registration_ID='$Registration_ID'";
                                             $sql_select_prepaid_id_result=mysql_query($sql_select_prepaid_id) or die(mysql_error());
                                             $prepaid_rows=mysql_fetch_assoc($sql_select_prepaid_id_result);
                                             $Prepaid_ID=$prepaid_rows['Prepaid_ID'];


                                             //check for the credit or cash patient
                                             $sql_check_for_credit_sponsor="SELECT payment_method FROM tbl_sponsor WHERE payment_method='credit' AND Sponsor_ID='$Sponsor_ID'";
                                             $sql_check_for_credit_sponsor_result=mysql_query($sql_check_for_credit_sponsor) or die(mysql_error());
                                           // if(mysql_num_rows($sql_check_for_credit_sponsor_result)==1){}else{
                                                $sql_select_check_in_id_result=mysql_query("SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1") or die(mysql_error());
                                                
                                                if(mysql_num_rows($sql_select_check_in_id_result)>0){
                                                    $check_in_id_row=mysql_fetch_assoc($sql_select_check_in_id_result);
                                                    $Check_In_ID=$check_in_id_row['Check_In_ID'];
                                                }
                                                ?>
                                  <a href="pendingbill.php?Registration_ID=<?php echo $Registration_ID; ?>&Prepaid_ID=<?php echo $Prepaid_ID; ?>&PostPaidRevenueCenter=PostPaidRevenueCenterThisForm&Check_In_ID=<?php echo $Check_In_ID; ?>"><button style='width: 100%; height: 100%'>Consumable</button></a>

                             <?php
                                            //}
                                            }else{
                                         ?>
                             <a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $Check_In_ID; ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage"><button style='width: 100%; height: 100%'>Consumable</button></a>
                                             <?php
                                     }
                                 ?>
                             </td>
                        </tr>
                    </table>
                </td>
                <?php } else { ?>
                <td colspan="3">
                    <table style="width:100%"><tr>
                    <td style='text-align: center; height: 40px;'>
                      <?php
                                    $sql_select_info= mysql_query("select Registration_ID from tbl_inpatient_medicines_given where Registration_ID = '$Registration_ID'") or die(mysql_error());
                                    $medication=mysql_num_rows($sql_select_info);
                                ?>
                                <a href='Inpatient_Nurse_Medicine.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Admision_ID=<?php echo $_GET['Admision_ID']; ?>&consultation_ID=<?php echo $_GET['consultation_ID']; ?><?php echo $nav ?>'><button style='width: 100%; height: 100%'>Medication Administration  <span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $medication; ?></button></a>

                    </td>

                    <td style='text-align: center; height: 40px;'>
                                <?php
                                       $Registration_ID=$_GET['Registration_ID'];
                                       $Admision_ID=$_GET['Admision_ID'];
                                       $consultation_ID=$_GET['consultation_ID'];
                                    $sql_select_checkin_id="SELECT Check_In_ID FROM tbl_check_in_details WHERE Registration_ID='$Registration_ID' AND Admission_ID='$Admision_ID' AND consultation_ID='$consultation_ID'";
                                    $sql_select_checkin_id_result=mysql_query($sql_select_checkin_id) or die(mysql_error());
                                    $rows_checkin=mysql_fetch_assoc($sql_select_checkin_id_result);
                                    $Check_In_ID=$rows_checkin['Check_In_ID'];

                                    if($_SESSION['outpatient_nurse_com'] == 'yes'){
                                            $sql_select_prepaid_id="SELECT Prepaid_ID FROM tbl_prepaid_details WHERE Registration_ID='$Registration_ID'";
                                            $sql_select_prepaid_id_result=mysql_query($sql_select_prepaid_id) or die(mysql_error());
                                            $prepaid_rows=mysql_fetch_assoc($sql_select_prepaid_id_result);
                                            $Prepaid_ID=$prepaid_rows['Prepaid_ID'];
                                             //check for the credit or cash patient
                                            $sql_check_for_credit_sponsor="SELECT payment_method FROM tbl_sponsor WHERE payment_method='credit' AND Sponsor_ID='$Sponsor_ID'";
                                            $sql_check_for_credit_sponsor_result=mysql_query($sql_check_for_credit_sponsor) or die(mysql_error());
                                        //   if(mysql_num_rows($sql_check_for_credit_sponsor_result)==1){}else{
                                             $sql_select_check_in_id_result=mysql_query("SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1") or die(mysql_error());
                                                
                                                if(mysql_num_rows($sql_select_check_in_id_result)>0){
                                                    $check_in_id_row=mysql_fetch_assoc($sql_select_check_in_id_result);
                                                    $Check_In_ID=$check_in_id_row['Check_In_ID'];
                                                }
                                        ?>
                                  <a href="pendingbill.php?Registration_ID=<?php echo $Registration_ID; ?>&Prepaid_ID=<?php echo $Prepaid_ID; ?>&PostPaidRevenueCenter=PostPaidRevenueCenterThisForm&Check_In_ID=<?php echo $Check_In_ID; ?>"><button style='width: 100%; height: 100%'>Consumable</button></a>
                            <?php
                                      //     }
                            }else{
                                        ?>
                            <a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $Check_In_ID; ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage"><button style='width: 100%; height: 100%'>Consumable</button></a>
                                            <?php
                                    }
                                ?>
                            </td>
                <?php } ?>
                        </tr>
                        <tr>
            <!--<td style='text-align: center; height: 40px;'>
                    <button style='width: 100%; height: 100%' name="Add_Item" id="Add_Item" onclick="Add_More_Items(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>,'nurse')">Nurse Charges</button>
                </td>-->
                <td style='text-align: center; height: 40px; width: 33%;' colspan="3" >

                    <a href='nurse_care_report.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Admision_ID=<?php echo $_GET['Admision_ID']; ?>&consultation_ID=<?php echo $_GET['consultation_ID']; ?><?php echo $nav ?>' target="_blank"><button style='width: 100%; height: 100%'>Nurse Care Reports</button></a>

                </td>

            </tr>
                    </table>
                     </td>
            </tr>



        </table>
    </center>
</fieldset>

<br/>
<script>
    $(document).ready(function () {
        $("#Add_Items").dialog({autoOpen: false, width: "90%", height: 500, title: 'ADD MORE ITEMS', modal: true});
    });
</script>
<script>
    $(document).ready(function () {
        $("#Zero_Price_Or_Quantity_Alert").dialog({autoOpen: false, width: "40%", height: 110, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>
<script>
    $(document).ready(function () {
        $("#No_Items_Found").dialog({autoOpen: false, width: "40%", height: 110, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>
<script>
    $(document).ready(function () {
        $("#Something_Wrong").dialog({autoOpen: false, width: "40%", height: 110, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>
<script>
    $(document).ready(function () {
        $("#Successful_Dialogy").dialog({autoOpen: false, width: "40%", height: 110, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#Unsuccessful_Dialogy").dialog({autoOpen: false, width: "40%", height: 110, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>
<script type="text/javascript">
    function Get_Item_Name(Item_Name, Item_ID) {
        document.getElementById("Item_Name").value = Item_Name;
        document.getElementById("Item_ID").value = Item_ID;
        document.getElementById("Quantity").value = '';
        document.getElementById("Quantity").focus();
    }
</script>
<script type="text/javascript">
    function Get_Item_Price(Item_ID, Guarantor_Name) {
        //        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Transaction_Type = "";

        if (window.XMLHttpRequest) {
            myObjectPrice = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPrice = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPrice.overrideMimeType('text/xml');
        }

        myObjectPrice.onreadystatechange = function () {
            data = myObjectPrice.responseText;

            if (myObjectPrice.readyState == 4) {
                document.getElementById('Price').value = data;
                document.getElementById("Quantity").value = 1;
            }
        }; //specify name of function that will handle server response........

        myObjectPrice.open('GET', 'Get_Items_Price_Inpatient.php?Item_ID=' + Item_ID + '&Guarantor_Name=' + Guarantor_Name + '&Transaction_Type=' + Transaction_Type, true);
        myObjectPrice.send();
    }
</script>
<script type="text/javascript" language="javascript">
    function getItemsList(Item_Category_ID) {
        document.getElementById("Search_Product").value = '';
        document.getElementById("Price").value = '';
        document.getElementById("Item_Name").value = '';
        document.getElementById("Quantity").value = '';
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        //alert(data);

        myObject.onreadystatechange = function () {
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                //document.getElementById('Approval').readonly = 'readonly';
                document.getElementById('Items_Fieldset').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Get_List_Of_Items.php?Item_Category_ID=' + Item_Category_ID + '&Guarantor_Name=' + Guarantor_Name, true);
        myObject.send();
    }
</script>

<script type="text/javascript">
    function getItemsListFiltered(Item_Name, Guarantor_Name) {
        document.getElementById("Price").value = '';
        document.getElementById("Item_Name").value = '';
        document.getElementById("Quantity").value = '';
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        if (Item_Category_ID == '' || Item_Category_ID == null) {
            Item_Category_ID = 'All';
        }

        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function () {
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                //document.getElementById('Approval').readonly = 'readonly';
                document.getElementById('Items_Fieldset').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Get_List_Of_Items_Filtered.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name + '&Guarantor_Name=' + Guarantor_Name, true);
        myObject.send();
    }
</script>

<script type="text/javascript">
    function Add_Selected_Item() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Transaction_Type = document.getElementById("Transaction_Type").value;
//        var Transaction_Type = "";
        var Item_ID = document.getElementById("Item_ID").value;
        var Quantity = document.getElementById("Quantity").value;
        var Discount = document.getElementById("Discount").value;
        var Check_In_Type = document.getElementById("Check_In_Type").value;
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Price = document.getElementById("Price").value;

        if (Price != 0 && Item_ID != null && Item_ID != '' && Check_In_Type != null && Check_In_Type != '' && Registration_ID != '' && Registration_ID != null && Quantity != null && Quantity != '' && Quantity != 0) {
            if (window.XMLHttpRequest) {
                myObjectAddSelectedItem = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectAddSelectedItem = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectAddSelectedItem.overrideMimeType('text/xml');
            }

            myObjectAddSelectedItem.onreadystatechange = function () {
                data921 = myObjectAddSelectedItem.responseText;
                if (myObjectAddSelectedItem.readyState == 4) {
                    document.getElementById("Cached_Items").innerHTML = data921
                    Calculate_Grand_Total();
                    document.getElementById("Price").value = '';
                    document.getElementById("Item_Name").value = '';
                    document.getElementById("Discount").value = '';
                    document.getElementById("Quantity").value = '';
                }
            }; //specify name of function that will handle server response........

            myObjectAddSelectedItem.open('GET', 'Inpatient_Add_More_Selected_Item.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Quantity=' + Quantity + '&Discount=' + Discount + '&Check_In_Type=' + Check_In_Type + '&Transaction_Type=' + Transaction_Type + '&Sponsor_ID=' + Sponsor_ID, true);
            myObjectAddSelectedItem.send();
        } else {
            if ((Price == null || Price == '' || Price == 0) && Item_ID != null && Item_ID != '') {
                $("#Zero_Price_Alert").dialog("open");
                return false
            }
            if (Item_ID == null || Item_ID == '') {
                document.getElementById("Item_Name").style = 'border: 3px solid red';
            } else {
                document.getElementById("Item_Name").style = 'border: 3px solid white';
            }

            if (Check_In_Type == null || Check_In_Type == '') {
                document.getElementById("Check_In_Type").style = 'border: 3px solid red';
            } else {
                document.getElementById("Check_In_Type").style = 'border: 3px solid white';
            }

            if (Quantity == null || Quantity == '' || Quantity == 0) {
                document.getElementById("Quantity").style = 'border: 3px solid red';
            } else {
                document.getElementById("Quantity").style = 'border: 3px solid white';
            }
        }
    }
</script>
<script type="text/javascript">
    function Calculate_Grand_Total() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectGrand = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGrand = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGrand.overrideMimeType('text/xml');
        }

        myObjectGrand.onreadystatechange = function () {
            dataGrandTotal = myObjectGrand.responseText;
            if (myObjectGrand.readyState == 4) {
                document.getElementById('Grand_Total_Area').innerHTML = dataGrandTotal;
            }
        }; //specify name of function that will handle server response........

        myObjectGrand.open('GET', 'Inpatient_Calculate_Grand_Total.php?Registration_ID=' + Registration_ID, true);
        myObjectGrand.send();
    }
</script>
<script type="text/javascript">
    function Remove_Item(Item_Cache_ID, Product_Name) {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var sms = confirm("Are you sure you want to remove " + Product_Name + "?");
        if (sms == true) {
            if (window.XMLHttpRequest) {
                myObjectRemove = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectRemove = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectRemove.overrideMimeType('text/xml');
            }

            myObjectRemove.onreadystatechange = function () {
                dataRemove = myObjectRemove.responseText;
                if (myObjectRemove.readyState == 4) {
                    document.getElementById('Cached_Items').innerHTML = dataRemove;
                    Calculate_Grand_Total();
                }
            }; //specify name of function that will handle server response........

            myObjectRemove.open('GET', 'Inpatient_Remove_Selected_Item.php?Item_Cache_ID=' + Item_Cache_ID + '&Registration_ID=' + Registration_ID, true);
            myObjectRemove.send();
        }
    }
</script>
<script type="text/javascript">
    function Save_Information_Verify() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectVerify = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectVerify = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectVerify.overrideMimeType('text/xml');
        }

        myObjectVerify.onreadystatechange = function () {
            dataVerify = myObjectVerify.responseText;
            if (myObjectVerify.readyState == 4) {
                var feedback = dataVerify;
                if (feedback == 'yes') {
                    Save_Information(Registration_ID);
                } else if (feedback == 'not') {
                    $("#Zero_Price_Or_Quantity_Alert").dialog("open");
                } else if (feedback == 'no') {
                    $("#No_Items_Found").dialog("open");
                } else {
                    $("#Something_Wrong").dialog("open");
                }
            }
        }; //specify name of function that will handle server response........

        myObjectVerify.open('GET', 'Inpatient_Verify_Information.php?Registration_ID=' + Registration_ID, true);
        myObjectVerify.send();
    }
</script>
<script type="text/javascript">
    function Add_More_Items(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID,From) {

//        var Transaction_Type = document.getElementById("Transaction_Type").value;

//        var Transaction_Type = "";
        if (window.XMLHttpRequest) {
            myObjectDisplay = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectDisplay = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectDisplay.overrideMimeType('text/xml');
        }
        myObjectDisplay.onreadystatechange = function () {
            mydata = myObjectDisplay.responseText;
            if (myObjectDisplay.readyState == 4) {
                document.getElementById('Details_Area').innerHTML = mydata;
                $("#Add_Items").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectDisplay.open('GET', 'Patient_Billing_Add_More_Items.php?Patient_Bill_ID=' + Patient_Bill_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Check_In_ID=' + Check_In_ID + '&Registration_ID=' + Registration_ID+'&From='+From, true);
        myObjectDisplay.send();
    }
</script>
<script type="text/javascript">
    function Save_Information(Registration_ID) {
        Patient_Bill_ID = '<?php echo $Patient_Bill_ID; ?>';
        Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        Folio_Number = '<?php echo $Folio_Number; ?>';
        Check_In_ID = '<?php echo $Check_In_ID; ?>';
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var sms = confirm("Are you sure you want to add selected items?");
        if (sms == true) {
            if (window.XMLHttpRequest) {
                myObjectSave = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectSave = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectSave.overrideMimeType('text/xml');
            }

            myObjectSave.onreadystatechange = function () {
                dataSave = myObjectSave.responseText;
                if (myObjectSave.readyState == 4) {
                    var feedbacks = dataSave;
                    if (feedbacks == 'yes') {
                        $("#Add_Items").dialog("close");
                        Sort_Mode(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID)
                        $("#Successful_Dialogy").dialog("open");
                    } else {
                        $("#Unsuccessful_Dialogy").dialog("open");
                    }
                }
            }; //specify name of function that will handle server response........

            myObjectSave.open('GET', 'Save_Information_Inpatient.php?Registration_ID=' + Registration_ID + '&Transaction_Type=' + Transaction_Type + '&Check_In_ID=' + Check_In_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Patient_Bill_ID=' + Patient_Bill_ID+'&from=nurse', true);
            myObjectSave.send();
        }
    }
</script>
<script type="text/javascript">
    function Sort_Mode(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID) {
        var Receipt_Mode = document.getElementById("Receipt_Mode").value;
        var Transaction_Type = document.getElementById("Transaction_Type").value;
//        var Receipt_Mode = "";
        var Transaction_Type = "";
        if (window.XMLHttpRequest) {
            myObjectMode = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectMode = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectMode.overrideMimeType('text/xml');
        }
        myObjectMode.onreadystatechange = function () {
            data288 = myObjectMode.responseText;
            if (myObjectMode.readyState == 4) {
                document.getElementById('Transaction_Items_Details').innerHTML = data288;
                Summary_Sort_Mode(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID);
            }
        }; //specify name of function that will handle server response........

        myObjectMode.open('GET', 'Sort_Mode_Display.php?Patient_Bill_ID=' + Patient_Bill_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Check_In_ID=' + Check_In_ID + '&Receipt_Mode=' + Receipt_Mode + '&Transaction_Type=' + Transaction_Type + '&Registration_ID=' + Registration_ID, true);
        myObjectMode.send();
    }
</script>
<script>
    function notifyUser($this) {
        if (confirm("Baby care chart are for women. Are you sure this is a women?")) {
            window.location = $($this).parent().attr('href');
        } else {
            $($this).parent().attr('href', '#');
            window.location = window.location.href;
        }
    }
</script>

<script>
    function Preview_Patient_File(Registration_ID) {
        // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
        var winClose = popupwindow('Patientfile_Record_Detail_General.php?Section=Doctor&Registration_ID=' + Registration_ID + '&PatientFile=PatientFileThisForm', 'Patient File', 1300, 700);
        //winClose.close();
        //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');

    }

    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);//'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        return mypopupWindow;
    }

</script>


<?php
include("./includes/footer.php");
?>
