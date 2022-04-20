<?php 
include("./includes/connection.php");
include("./includes/header.php");
$controlforminput = '';
if (!isset($_SESSION['userinfo'])) {
@session_destroy();
header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo']['Setup_And_Configuration']) ||isset($_SESSION['userinfo']['Appointment_Works'])) {
if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'&&$_SESSION['userinfo']['Appointment_Works']!='yes') {
header("Location: ./index.php?InvalidPrivilege=yes");
}
}else {
@session_destroy();
header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['super_user_login'])&&$_SESSION['super_user_login']!="yes") {
header("Location: ./index.php?InvalidPrivilege=yes");
}
$control_Save_Button = 'active';
if (isset($_GET['Employee_ID'])) {
$Employee_ID = $_GET['Employee_ID'];
}else {
$Employee_ID = 0;
$Employee_Name = 'Undefined Employee Name';
$Employee_Title = 'Undefined Employee Title';
$control_Save_Button = 'inactive';
}
;echo '

';
if (isset($_SESSION['userinfo'])) {
if ($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'||$_SESSION['userinfo']['Appointment_Works']=='yes') {
;echo '        <a href="editemployee.php?Employee_ID=';echo  $Employee_ID ;echo '&EditEmployeePrivileges=No';echo (isset($_GET['HRWork']) &&$_GET['HRWork']=='true')?'&HRWork=true':'';echo '" class="art-button-green">BACK</a>
    ';
}
}
;echo '

';
if (isset($_POST['submit'])) {
$Employee_Name2 =mysqli_real_escape_string($conn,$_POST['Employee_Name']);
$Job_Title2 = $_POST['Job_Title'];
$Job_Code = $_POST['Job_Code'];
$Employee_Type = $_POST['Employee_Type'];
$Employee_Department_Name = $_POST['Employee_Department_Name'];
if ($Employee_Name2 == '') {
$Employee_Name2 = $Employee_Name;
}
if ($Job_Title2 == '') {
$Job_Title2 = $Employee_Title;
}
$edit_employee= "no";
$edit_transaction= "no";
$edit_diseases= "no";
$add_diseases= "no";
$Edit_Patient_Information= "no";
$edit_sponsor= "no";
$edit_items = "no";
$discount_for_excempted_sponsor="no";
$Msamaha_Works="no";
$can_load_item_from_excel='no';
$can_have_access_to_approve_bill_btn='no';
$can_edit_claim_bill='no';
$Final_Claim_Sender='no';
$can_create_out_patient_bill='no';
$can_have_access_to_grn_physical_counting='no';
$can_have_access_to_Authorize_Surgery_List = 'no';
$can_have_access_to_Approvery_Surgery_List = 'no';
$can_have_access_to_Certify_Surgery_List = 'no';
$can_change_system_parameters='no';
$Approve_Job='no';
$Certify_Job='no';
$Authorize_Job='no';
$assign_sponsor_to_patient_automatically='no';

if (isset($_POST['edit_employee'])) {
$edit_employee= 'yes';
}
if (isset($_POST['edit_transaction'])) {
$edit_transaction = 'yes';
}
if (isset($_POST['edit_diseases'])) {
$edit_diseases = 'yes';
}
if (isset($_POST['add_diseases'])) {
$add_diseases = 'yes';
}
if (isset($_POST['Edit_Patient_Information'])) {
$Edit_Patient_Information = 'yes';
}
if (isset($_POST['edit_sponsor'])) {
$edit_sponsor = 'yes';
}
if (isset($_POST['edit_items'])) {
$edit_items = 'yes';
}
if (isset($_POST['can_take_database_backup'])) {
$can_take_database_backup = 'yes';
}
if (isset($_POST['can_login_to_high_privileges_department'])) {
$can_login_to_high_privileges_department = 'yes';
}

if (isset($_POST['Can_View_All_Patient_Files'])){
    $Can_View_All_Patient_Files = 'yes';
}
if (isset($_POST['change_bill_type_transaction_type_for_excempted'])) {
$change_bill_type_transaction_type_for_excempted = 'yes';
}
if (isset($_POST['discount_for_excempted_sponsor'])) {
$discount_for_excempted_sponsor = 'yes';
}
if (isset($_POST['Msamaha_Works'])) {
$Msamaha_Works = 'yes';
}
if (isset($_POST['can_load_item_from_excel'])) {
$can_load_item_from_excel = 'yes';
}
if (isset($_POST['can_have_access_to_approve_bill_btn'])) {
$can_have_access_to_approve_bill_btn = 'yes';
}
if (isset($_POST['can_edit_claim_bill'])) {
$can_edit_claim_bill = 'yes';
}
if (isset($_POST['Final_Claim_Sender'])) {
$Final_Claim_Sender = 'yes';
}
if (isset($_POST['can_have_access_to_grn_physical_counting'])) {
$can_have_access_to_grn_physical_counting = 'yes';
}
if (isset($_POST['can_have_access_to_Authorize_Surgery_List'])) {
$can_have_access_to_Authorize_Surgery_List = 'yes';
}
if (isset($_POST['can_have_access_to_Approvery_Surgery_List'])) {
$can_have_access_to_Approvery_Surgery_List = 'yes';
}
if (isset($_POST['can_have_access_to_Certify_Surgery_List'])) {
    $can_have_access_to_Certify_Surgery_List = 'yes';
}
if (isset($_POST['can_create_out_patient_bill'])) {
$can_create_out_patient_bill = 'yes';
}
if (isset($_POST['assign_sponsor_to_patient_automatically'])) {
$assign_sponsor_to_patient_automatically = 'yes';
}
if (isset($_POST['can_change_system_parameters'])) {
$can_change_system_parameters = 'yes';
}
if (isset($_POST['Certify_Job'])) {
$Certify_Job = 'yes';
}
if (isset($_POST['Authorize_Job'])) {
$Authorize_Job = 'yes';
}
if (isset($_POST['Approve_Job'])) {
$Approve_Job = 'yes';
}

$sql = "UPDATE tbl_privileges set can_change_system_parameters='$can_change_system_parameters',assign_sponsor_to_patient_automatically='$assign_sponsor_to_patient_automatically',can_create_out_patient_bill='$can_create_out_patient_bill',can_load_item_from_excel='$can_load_item_from_excel',can_have_access_to_approve_bill_btn='$can_have_access_to_approve_bill_btn',can_edit_claim_bill='$can_edit_claim_bill',Final_Claim_Sender='$Final_Claim_Sender',can_have_access_to_grn_physical_counting='$can_have_access_to_grn_physical_counting', can_have_access_to_Certify_Surgery_List = '$can_have_access_to_Certify_Surgery_List', can_have_access_to_Approvery_Surgery_List = '$can_have_access_to_Approvery_Surgery_List', can_have_access_to_Authorize_Surgery_List = '$can_have_access_to_Authorize_Surgery_List', Msamaha_Works='$Msamaha_Works',discount_for_excempted_sponsor='$discount_for_excempted_sponsor',change_bill_type_transaction_type_for_excempted='$change_bill_type_transaction_type_for_excempted',edit_employee='$edit_employee',edit_transaction='$edit_transaction',can_take_database_backup='$can_take_database_backup',can_login_to_high_privileges_department='$can_login_to_high_privileges_department',edit_diseases='$edit_diseases', Can_View_All_Patient_Files = '$Can_View_All_Patient_Files', add_diseases='$add_diseases',Edit_Patient_Information='$Edit_Patient_Information',edit_sponsor='$edit_sponsor',edit_items='$edit_items'
                ,Approve_Job='$Approve_Job',Authorize_Job='$Authorize_Job',Certify_Job='$Certify_Job',last_modified_by='".$_SESSION['userinfo']['Employee_ID']."' where employee_id = '$Employee_ID'";
if (!mysqli_query($conn,$sql)) {
die(mysqli_error($conn));
}else {
echo "<script type='text/javascript'>
                   alert('CHANGES SAVED SUCCESSFUL');
        </script>";
}
}
;echo '



<script language="javascript" type="text/javascript">
    function searchEmployee(Employee_Name) {
        document.getElementById("Search_Iframe").innerHTML = "<iframe width="100%" height=125px src="editEmployeeIframe.php?Employee_Name=" + Employee_Name + ""></iframe>";
    }
</script>


';
$selectThisRecord = mysqli_query($conn,"select * from tbl_employee e, tbl_privileges p, tbl_department d
                    where e.employee_id = p.employee_id and
                        d.department_id = e.department_id and
                        e.employee_id = '$Employee_ID'") or die(mysqli_error($conn));
$numberOfRecord = mysqli_num_rows($selectThisRecord);
if ($numberOfRecord >0) {
while ($row = mysqli_fetch_array($selectThisRecord)) {
$Employee_Name = htmlspecialchars($row['Employee_Name'],ENT_QUOTES);
$Employee_Title = $row['Employee_Title'];
$Employee_Type = $row['Employee_Type'];
$Employee_Branch_Name = $row['Employee_Branch_Name'];
$Department_Name = $row['Department_Name'];
$Employee_Job_Code = $row['Employee_Job_Code'];
$Account_Status = $row['Account_Status'];
$edit_employee= $row['edit_employee'];
$edit_transaction= $row['edit_transaction'];
$edit_diseases= $row['edit_diseases'];
$add_diseases= $row['add_diseases'];
$Edit_Patient_Information= $row['Edit_Patient_Information'];
$edit_sponsor= $row['edit_sponsor'];
$edit_items = $row['edit_items'];
$change_bill_type_transaction_type_for_excempted = $row['change_bill_type_transaction_type_for_excempted'];
$discount_for_excempted_sponsor = $row['discount_for_excempted_sponsor'];
$Msamaha_Works = $row['Msamaha_Works'];
$can_load_item_from_excel = $row['can_load_item_from_excel'];
$can_have_access_to_approve_bill_btn = $row['can_have_access_to_approve_bill_btn'];
$can_edit_claim_bill = $row['can_edit_claim_bill'];
$Final_Claim_Sender = $row['Final_Claim_Sender'];
$can_have_access_to_grn_physical_counting = $row['can_have_access_to_grn_physical_counting'];
$can_have_access_to_Authorize_Surgery_List = $row['can_have_access_to_Authorize_Surgery_List'];
$can_have_access_to_Approvery_Surgery_List = $row['can_have_access_to_Approvery_Surgery_List'];
$can_have_access_to_Certify_Surgery_List = $row['can_have_access_to_Certify_Surgery_List'];
$can_create_out_patient_bill = $row['can_create_out_patient_bill'];
$can_change_system_parameters = $row['can_change_system_parameters'];
$assign_sponsor_to_patient_automatically = $row['assign_sponsor_to_patient_automatically'];
$can_take_database_backup = $row['can_take_database_backup'];
$can_login_to_high_privileges_department = $row['can_login_to_high_privileges_department'];
$Can_View_All_Patient_Files = $row['Can_View_All_Patient_Files'];
$Approve_Job = $row['Approve_Job'];
$Authorize_Job = $row['Authorize_Job'];
$Certify_Job = $row['Certify_Job'];
}
}else {
$Employee_Name = 'Undefined Employee Name';
$Employee_Title = 'Undefined Employee Title';
$Employee_Type = '';
$Employee_Branch_Name = '';
$Department_Name = '';
$Employee_Job_Code = '';
$control_Save_Button = 'inactive';
$Account_Status = '';
$can_take_database_backup='';
$can_login_to_high_privileges_department='';
$Can_View_All_Patient_Files='';
$edit_employee= "";
$edit_transaction= "";
$edit_diseases= "";
$add_diseases= "";
$Edit_Patient_Information= "";
$edit_sponsor= "";
$edit_items = "";
$can_load_item_from_excel = "";
$change_bill_type_transaction_type_for_excempted = '';
$discount_for_excempted_sponsor = '';
$Msamaha_Works = '';
$can_have_access_to_approve_bill_btn = '';
$can_edit_claim_bill = '';
$Final_Claim_Sender = '';
$can_have_access_to_grn_physical_counting = '';
$can_have_access_to_Authorize_Surgery_List = '';
$can_have_access_to_Approvery_Surgery_List = '';
$can_have_access_to_Certify_Surgery_List = '';
$can_create_out_patient_bill = '';
$can_change_system_parameters = '';
$assign_sponsor_to_patient_automatically = '';
$Approve_Job = '';
$Authorize_Job = '';
$Certify_Job = '';
}
if(!empty($last_modified_by)){
$last_modified_by_qry=  mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$last_modified_by'") or die(mysqli_error($conn));
$modifer_name=  mysqli_fetch_assoc($last_modified_by_qry)['Employee_Name'];
$modifer_name_disp=",&nbsp;&nbsp;Last Modified By <span style='color: rgb(180, 246, 86)'>$modifer_name<span>";
}
;echo '
<center style="margin-top: 25px">
    <fieldset>
        <!--<legend align="center"></legend>-->
        <table width = 100%>
            <tr>
            <form action="#" method="post" name="myForm" id="myForm" enctype="multipart/form-data">
                <td colspan=2>

                    <fieldset>
                        <legend align="left"><b>Employee Details And Privileges';if ($Account_Status != 'active') {;echo '<span style="color: red;">  - THIS EMPLOYEE ACCOUNT HAS BEEN BLOCKED</b></span>';};echo '</legend>
                        <table width = 100%>
                            <tr>
                                <td>

                                    <table width=100%>
                                        <tr>
                                            <td width=10%>Employee Name</td>
                                            <td><input type="text" name="Employee_Name" id="Employee_Name" required="required" value="';echo $Employee_Name;;echo '"> </td>
                                        </tr>
                                        <tr>
                                            <td width=10%>Job Title</td>
                                            <td><input type="text" name="Job_Title" id="Job_Title" required="required" value="';echo $Employee_Title;;echo '"> </td>
                                        </tr>
                                        <tr>
                                            <td>Job Code</td>
                                            <td>
                                                <select name="Job_Code" id="Job_Code">
                                                    <option selected="selected">';echo $Employee_Job_Code;;echo '</option>
                                                    <option>Anaesthesiologist</option>
                                                    <option>Anaesthesia</option>
                                                    <option>Dentist</option>
                                                    <option>Gynecologist</option>
                                                    <option>Nurse</option>
                                                    <option>Scrub Nurse</option>
                                                    <option>Optician</option>
                                                    <option>Paedetrician</option>
                                                    <option>Radiographer</option>
                                                    <option>Radiologist</option>
                                                    <option>Sonographer</option>
                                                    <option>Surgeon</option>
                                                    <option>Sonographer/Radiographer</option>
                                                    <option>Sonographer/Radiolographer/Radiologist</option>
                                                    <option>Others</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width=5%>Employee Type</td>
                                            <td width=70%>
                                                <select name="Employee_Type" id="Employee_Type">
                                                    <option selected="selected">';echo $Employee_Type;;echo '</option>
                                                    <option>Accountant</option>
                                                    <option>Billing Personnel</option>
                                                    <option>Cashier</option>
                                                    <option>Doctor</option>
                                                    <option>Food Personnel</option>
                                                    <option>Hospital Admin</option>
                                                    <option>IT Personnel</option>
                                                    <option>Laboratory Technician</option>
                                                    <option>Laundry Personnel</option>
                                                    <option>Nurse</option>
                                                    <option>Pharmacist</option>
                                                    <option>Procurement</option>
                                                    <option>Radiologist</option>
                                                    <option>Receptionist</option>
                                                    <option>Record Personnel</option>
                                                    <option>Security Personnel</option>
                                                    <option>Storekeeper</option>
                                                    <option>Others</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Department</td>
                                            <td>
                                                <select name="Employee_Department_Name" id="Employee_Department_Name" required="required">
                                                    <option selected="selected">';echo $Department_Name;;echo '</option>
                                                    ';
$Select_Department = mysqli_query($conn,"select Department_Name from tbl_department");
while ($row = mysqli_fetch_array($Select_Department)) {
echo "<option>".$row['Department_Name'] ."</option>";
}
;echo '                                                </select>
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
                </tr>
                <tr>
                    <td colspan=2>
                        <fieldset>
                            <legend align="left"><b>Other Access Privileges ';echo $modifer_name_disp;;echo '</b> </legend>
                            <table width = 100%>
                                <tr>
                                    <td width=20%>
                                        <input type="checkbox" name="edit_employee" id="edit_employee" value="yes" ';
if (strtolower($edit_employee) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="Edit_employee">Edit Employee</label>
                                    </td>
                                    <td width=20%>
                                        <input type="checkbox" name="edit_items" id="edit_items" value="yes" ';
if (strtolower($edit_items) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="edit_items">Edit Items</label>
                                    </td>
                                    <td width=20%>
                                        <input type="checkbox" name="edit_sponsor" id="edit_sponsor" value="yes" ';
if (strtolower($edit_sponsor) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="edit_sponsor">Edit Sponsor</label>
                                    </td>
                                    <td width=20%>
                                        <input type="checkbox" name="Edit_Patient_Information" id="Edit_Patient_Information" value="yes" ';
if (strtolower($Edit_Patient_Information) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="edit_patients">Edit Patient Information</label>
                                    </td>
                                    <td width=20%>
                                        <input type="checkbox" name="add_diseases" id="add_diseases" value="yes" ';
if (strtolower($add_diseases) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="add_diseases">Add Diseases</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td width=20%>
                                        <input type="checkbox" name="edit_diseases" id="edit_diseases" value="yes" ';
if (strtolower($edit_diseases) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="edit_diseases">Edit Diseases</label>
                                    </td>
                                    <td width=20%>
                                        <input type="checkbox" name="edit_transaction" id="edit_transaction" value="yes" ';
if (strtolower($edit_transaction) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="edit_transaction">Edit Transaction</label>
                                    </td>
                                    <td width=20%>
                                        <input type="checkbox" name="can_take_database_backup" id="can_take_database_backup" value="yes" ';
if (strtolower($can_take_database_backup) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="can_take_database_backup">Can Take Database Backup</label>
                                    </td>
                                    <td width=20%>
                                        <input type="checkbox" name="discount_for_excempted_sponsor" id="discount_for_excempted_sponsor" value="yes" ';
if (strtolower($discount_for_excempted_sponsor) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="discount_for_excempted_sponsor">Can Discount Exempted Sponsor</label>
                                    </td>
                                    <td width=20%>
                                        <input type="checkbox" name="Msamaha_Works" id="Msamaha_Works" value="yes" ';
if (strtolower($Msamaha_Works) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="Msamaha_Works">Msamaha Works</label>
                                    </td>

                                </tr>
                                <tr>
                                    <td width=20% colspan="2">
                                        <input type="checkbox" name="change_bill_type_transaction_type_for_excempted" id="change_bill_type_transaction_type_for_excempted" value="yes" ';
if (strtolower($change_bill_type_transaction_type_for_excempted) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="change_bill_type_transaction_type_for_excempted">Can change Bill Type and Transaction Type  For Exempted Sponsor</label>
                                    </td>
                                    <td width=20%>
                                        <input type="checkbox" name="can_load_item_from_excel" id="can_load_item_from_excel" value="yes" ';
if (strtolower($can_load_item_from_excel) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="can_load_item_from_excel">Can Load Item From Excel</label>
                                    </td>
                                    <td width=20% colspan="1">
                                        <input type="checkbox" name="can_have_access_to_approve_bill_btn" id="can_have_access_to_approve_bill_btn" value="yes" ';
if (strtolower($can_have_access_to_approve_bill_btn) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="can_have_access_to_approve_bill_btn">Can Have Access to Approve bill button</label>
                                    </td>
                                    <td width=20% colspan="1">
                                        <input type="checkbox" name="can_edit_claim_bill" id="can_edit_claim_bill" value="yes" ';
if (strtolower($can_edit_claim_bill) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="can_edit_claim_bill">Can Edit Claim Bills</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td width=20%>
                                        <input type="checkbox" name="can_have_access_to_grn_physical_counting" id="can_have_access_to_grn_physical_counting" value="yes" ';
if (strtolower($can_have_access_to_grn_physical_counting) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="can_have_access_to_grn_physical_counting">Can Have Access to GRN/Physical counting</label>
                                    </td>
                                    <td width=20%>
                                    <input type="checkbox" name="can_have_access_to_Approvery_Surgery_List" id="can_have_access_to_Approvery_Surgery_List" value="yes" ';
if (strtolower($can_have_access_to_Approvery_Surgery_List) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                    <label for="can_have_access_to_Approvery_Surgery_List">Can Have Access to Approve Final Surgery List (DSS)</label>
                                </td>
                                <td width=20%>
                                    <input type="checkbox" name="can_have_access_to_Certify_Surgery_List" id="can_have_access_to_Certify_Surgery_List" value="yes" ';
if (strtolower($can_have_access_to_Certify_Surgery_List) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                    <label for="can_have_access_to_Certify_Surgery_List">Can Have Access to Certify Surgery List (HOD)</label>
                                </td>
                                    <td width=20%>
                                        <input type="checkbox" name="can_create_out_patient_bill" id="can_create_out_patient_bill" value="yes" ';
if (strtolower($can_create_out_patient_bill) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="can_creat_out_patient_bill">Can create Outpatient Bill</label>
                                    </td>
                                    <td width=20% colspan="2">
                                        <input type="checkbox" name="assign_sponsor_to_patient_automatically" id="assign_sponsor_to_patient_automatically" value="yes" ';
if (strtolower($can_create_out_patient_bill) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="assign_sponsor_to_patient_automatically">Assign Sponsor to Patient Automatically</label>
                                    </td>

                                </tr>
                                <tr>
                                    <td width=20%>
                                        <input type="checkbox" name="can_change_system_parameters" id="can_change_system_parameters" value="yes" ';
if (strtolower($can_change_system_parameters) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="can_change_system_parameters">Can change system parameters</label>
                                    </td>                                    
                                    <td width=20%>
                                    <input type="checkbox" name="can_have_access_to_Authorize_Surgery_List" id="can_have_access_to_Authorize_Surgery_List" value="yes" ';
if (strtolower($can_have_access_to_Authorize_Surgery_List) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                    <label for="can_have_access_to_Authorize_Surgery_List">Can Have Access to Authorize Surgery List (Anaesthesia)</label>
                                </td>
                                
                                    <td width=20%>
                                        <input type="checkbox" name="can_login_to_high_privileges_department" id="can_login_to_high_privileges_department" value="yes" ';
if (strtolower($can_login_to_high_privileges_department) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="can_login_to_high_privileges_department">Can login and have access to high privileges Department</label>
                                    </td>

                                    <td width=20%>
                                        <input type="checkbox" name="Can_View_All_Patient_Files" id="Can_View_All_Patient_Files" value="yes" ';
if (strtolower($Can_View_All_Patient_Files) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="Can_View_All_Patient_Files">Can View All Patient Files</label>
                                    </td>
                                    <td width=20% colspan="1">
                                        <input type="checkbox" name="Final_Claim_Sender" id="Final_Claim_Sender" value="yes" ';
if (strtolower($Final_Claim_Sender) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                        <label for="Final_Claim_Sender">Final Claim Sender</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td width=20%>
                                    <input type="checkbox" name="Certify_Job" id="Certify_Job" value="yes" ';
if (strtolower($Certify_Job) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                <label for="Certify_Job">Can Certify Job</label>
                            </td>
                                    <td width=20%>
                                    <input type="checkbox" name="Authorize_Job" id="Authorize_Job" value="yes" ';
if (strtolower($Authorize_Job) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                <label for="Authorize_Job">Can Authorize Job</label>
                            </td>
                                    <td width=20%>
                                    <input type="checkbox" name="Approve_Job" id="Approve_Job" value="yes" ';
if (strtolower($Approve_Job) == 'yes') {
echo "checked='checked'";
}
;echo '>
                                <label for="Approve_Job">Can Approve Jobcard</label>
                            </td>
                            </tr>
                            </table>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;"><br/>
                        <table width=100%>
                            <tr>
                                <td  style="text-align: right;">
                                    &nbsp;&nbsp;&nbsp;
                                        <input type="submit" name="submit" id="submit" value="   SAVE CHANGES   " class="art-button-green">&nbsp;&nbsp;&nbsp;
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>
        </table>
    </fieldset>
</center>
</form>


';
include("./includes/footer.php");
; ?>