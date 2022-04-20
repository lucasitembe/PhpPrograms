<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration']) || isset($_SESSION['userinfo']['Appointment_Works'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Appointment_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
     $Current_Username = $_SESSION['userinfo']['Given_Username'];
     
    $sql_check_prevalage="SELECT edit_employee FROM tbl_privileges WHERE edit_employee='yes' OR Appointment_Works='yes' AND "
            . "Given_Username='$Current_Username'";
    
   $sql_check_prevalage_result=mysqli_query($conn,$sql_check_prevalage);
    if(!mysqli_num_rows($sql_check_prevalage_result)>0){
        ?>
        <script>
            var privalege= alert("You don't have the privelage to access this button")
            document.location="./index.php?InvalidPrivilege=yes";
        </script>
        <?php
    }
    if(isset($_GET['HRWork']) && $_GET['HRWork']=='true'){
       echo "<a href='human_resource.php?HRWork=HRWorkThisPage' class='art-button-green'>BACK</a>";
    }else{
       echo "<a href='employeepage.php?EmployeeManagement=EmployeeManagementThisPage' class='art-button-green'>BACK</a>";
    }
?>

<script language="javascript" type="text/javascript">
    function searchEmployee(Employee_Name){
	document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=430px src='editEmployeeIframe.php?Employee_Name="+Employee_Name+"'></iframe>";
    }
</script>
<br/><br/>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    #sss:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>

<div id="table_search" ></div>
<fieldset>
    <center>
        <table width=100%>
            <tr>
                <td width="15%" style="text-align: right;">Employee Department</td>
                <td width="15%">
                    <select name="Department_ID" id="Department_ID" onchange="Search_Employee()">
                        <option selected="selected" value="0">All Department</option>
                    <?php
                        $select = mysqli_query($conn,"select Department_ID, Department_Name from tbl_department order by Department_Name") or die(mysqli_error($conn));
                        $no = mysqli_num_rows($select);
                        if($no > 0){
                            while($data = mysqli_fetch_array($select)){
                    ?>
                                <option value="<?php echo $data['Department_ID']; ?>"><?php echo ucwords(strtolower($data['Department_Name'])); ?></option>
                    <?php
                            }
                        }
                    ?>                        
                    </select>
                </td>
                <td width="5%" style="text-align: right;">Privileges</td>
                <td width="5%">
                    <select name="Privileges" onchange="Search_Employee_privileges(this.value)">
                        <option value="all">--select--</option>
                        <option value="Reception_Works">Reception_Works</option>
                        <option value="Revenue_Center_Works">Revenue_Center_Works</option>
                        <option value="Patients_Billing_Works">Patients_Billing_Works</option>
                        <option value="Procurement_Works">Procurement_Works</option>
                        <option value="Mtuha_Reports">Mtuha_Reports</option>
                        <option value="General_Ledger">General_Ledger</option>
                        <option value="Management_Works">Management_Works</option>
                        <option value="Nurse_Station_Works">Nurse_Station_Works</option>
                        <option value="Admission_Works">Admission_Works</option>
                        <option value="Laboratory_Works">Laboratory_Works</option>
                        <option value="Radiology_Works">Radiology_Works</option>
                        <option value="Quality_Assurance">Quality_Assurance</option>
                        <option value="Dressing_Works">Dressing_Works</option>
                        <option value="Dialysis_Works">Dialysis_Works</option>
                        <option value="Theater_Works">Theater_Works</option>
                        <option value="Physiotherapy_Works">Physiotherapy_Works</option>
                        <option value="Maternity_Works">Maternity_Works</option>
                        <option value="Dental_Works">Dental_Works</option>
                        <option value="Eye_Works">Eye_Works</option>
                        <option value="Cecap_Works">Cecap_Works</option>
                        <option value="Modify_Cash_information">Modify_Cash_information</option>
                        <option value="Session_Master_Priveleges">Session_Master_Priveleges</option>
                        <option value="Cash_Transactions">Cash_Transactions</option>
                        <option value="Modify_Credit_Information">Modify_Credit_Information</option>
                        <option value="Setup_And_Configuration">Setup_And_Configuration</option>
                        <option value="Msamaha_Works">Msamaha_Works</option>
                        <option value="Doctors_Page_Outpatient_Work">Doctors_Page_Outpatient_Work</option>
                        <option value="Pharmacy">Pharmacy</option>
                        <option value="Storage_And_Supply_Work">Storage_And_Supply_Work</option>
                        <option value="Ear_Works">Ear_Works</option>
                        <option value="Storage_And_Supply_Work">Storage_And_Supply_Work</option>
                        <option value="Employee_Collection_Report">Employee_Collection_Report</option>
                        <option value="Appointment_Works">Appointment_Works</option>
                        <option value="Morgue_Works">Morgue_Works</option>
                        <option value="Patient_Record_Works">Patient_Record_Works</option>
                        <option value="Laboratory_Result_Modification">Laboratory_Result_Modification</option>
                        <option value="Laboratory_Result_Validation">Laboratory_Result_Validation</option>
                        <option value="Laboratory_consulted_patients">Laboratory_consulted_patients</option>
                        <option value="Doctors_Page_Inpatient_Work">Doctors_Page_Inpatient_Work</option>
                        <option value="Procedure_Works">Procedure_Works</option>
                        <option value="Patient_Privacy">Patient_Privacy</option>
                        <option value="Patient_Transfer">Patient_Transfer</option>
                        <option value="Approval_Orders">Approval_Orders</option>
                        <option value="Edit_Patient_Information">Edit_Patient_Information</option>
                        <option value="can_view_demographic_revenue">can_view_demographic_revenue</option>
                        <option value="can_do_offline_trans">can_do_offline_trans</option>
                        <option value="Last_Password_Change">Last_Password_Change</option>
                        <option value="Changed_first_login">Changed_first_login</option>
                        <option value="can_revk_bill_status">can_revk_bill_status</option>
                        <option value="super_user">super_user</option>
                        <option value="edit_employee">edit_employee</option>
                        <option value="edit_transaction">edit_transaction</option>
                        <option value="edit_diseases">edit_diseases</option>
                        <option value="add_diseases">add_diseases</option>
                        <option value="edit_sponsor">edit_sponsor</option>
                        <option value="edit_items">edit_items</option>
                        <option value="can_take_database_backup">can_take_database_backup</option>
                        <option value="change_bill_type_transaction_type_for_excempted">change_bill_type_transaction_type_for_excempted</option>
                        <option value="discount_for_excempted_sponsor">discount_for_excempted_sponsor</option>
                        <option value="can_load_item_from_excel">can_load_item_from_excel</option>
                        <option value="can_have_access_to_approve_bill_btn">can_have_access_to_approve_bill_btn</option>
                        <option value="can_have_access_to_grn_physical_counting">can_have_access_to_grn_physical_counting</option>
                        <option value="can_create_out_patient_bill">can_create_out_patient_bill</option>
                        <option value="assign_sponsor_to_patient_automatically">assign_sponsor_to_patient_automatically</option>
                        <option value="can_change_system_parameters">can_change_system_parameters</option>
                        <option value="can_view_perfomance_report">can_view_perfomance_report</option>
                        <option value="can_login_to_high_privileges_department">can_login_to_high_privileges_department</option>
                        <option value="can_edit_claim_bill">can_edit_claim_bill</option>
                        <option value="Free_Items_Works">Free_Items_Works</option>
                        <option value="can_create_inpatient_bill">can_create_inpatient_bill</option>
                                          
                    </select>
                </td>
                <td width='10%' style="text-align: right;">Select Designation</td>
                <td width='15%'><select name='Designation' id='Designation'  onchange="Search_Employee()">
                    <option>All Designation</option>
                    <?php
                        $select_designation=mysqli_query($conn,"SELECT * FROM tbl_designation");
                        while ($row=mysqli_fetch_assoc($select_designation)) {
                            extract($row);
                            echo "<option>{$designation}</option>";
                        }
                    ?>
                    <!--option>Accountant</option>
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
                    <option>Others</option--> 
                </select></td>
                <td width='15%'><input type='text' name='Employee_PFNO' id='Employee_PFNO' autocomplete="off"  oninput='Search_Employee()' onkeypress='Search_Employee()' placeholder='~~~Search PFNO~~~' style="text-align: center;"></td>
                <td width='20%'>
                    <input type='text' name='Employee_Name' id='Employee_Name' autocomplete="off"  oninput='Search_Employee()' onkeypress='Search_Employee()' placeholder='~~~Search Name~~~' style="text-align: center;">
                </td>
                <td width="5%" style="text-align: right;">Status</td>
                <td width="15%">
                    <select name="Account_Status" style="width:100%;" id="Account_Status" onchange="Search_Employee()">
                        <option  value="">Active and Inactive</option>
                        <option selected="selected" value="active">Active</option>
                        <option  value="inactive">Inactive</option>
                    </select>
                </td>
                <td>
                    <input type="button" value="EXCEL" onclick='exportInExcel()' class='art-button-green'>
                </td>
            </tr>            
        </table>
    </center>
</fieldset>
<fieldset style="overflow-y: scroll; height: 380px; background-color: white;" id="Employee_Area">
    <legend align="left"><b>LIST OF EMPLOYEES</b></legend>
    <table width="100%">
    <?php
        $temp = 0;
        $Title = '<tr><td colspan="10"><hr></td></tr>
        <tr>
            <td width ="5%"><b>SN</b></td>
            <td width="20%"><b>EMPLOYEE NAME</b></td>
            <td width="10%"><b>PHONE NUMBER</b></td>
            <td><b>EMPLOYEE PFNO</b></td>
            <td><b>DOCTOR LICENCE</b></td>
            <td width="15%"><b>DESIGNATION</b></td>
            <td width="15%"><b>EMPLOYEE TITLE</b></td> 
            <td width="15%"><b>JOB CODE</b></td>
            <td width="15%"><b>DEPARTMENT</b></td>
            <td width="10%"><b>SIGNTURE</b></td>
        </tr>
        <tr><td colspan="10"><hr></td></tr>';

        echo $Title;
        $select = mysqli_query($conn,"select * from tbl_employee emp, tbl_department dep WHERE
                                emp.department_id = dep.department_id AND Account_Status='active' AND
                                emp.Employee_Name <> 'crdb' order by Employee_Name limit 500") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0){
            if(isset($_GET['HRWork']) && $_GET['HRWork']=='true'){
            while ($row = mysqli_fetch_array($select)) {
                $employee_signature = $row['employee_signature'];
                if(($employee_signature) != NULL && ($employee_signature) !=''){
                    $signature='Taken';
                }else{
                    $signature='Not taken';
                }
                $Phone_Number = $row['Phone_Number'];
                echo "<tr id='sss'><td>".++$temp."<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm&HRWork=true' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Name'])."</td><td>$Phone_Number</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm&HRWork=true' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Number'])."</td><td>".$row['doctor_license']."</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm&HRWork=true' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Type'])."</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm&HRWork=true' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Title'])."</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm&HRWork=true' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Job_Code'])."</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm&HRWork=true' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Department_Name'])."</td>";
                echo "<td>$signature</td>";
                if($temp%20 == 0){
                    echo $Title;
                }
            }}else{

                while ($row = mysqli_fetch_array($select)) {
                    $employee_signature = $row['employee_signature'];
                    if(($employee_signature) != NULL && ($employee_signature) !=''){
                        $signature='Taken';
                    }else{
                        $signature='Not taken';
                    }
                    $Phone_Number = $row['Phone_Number'];
                echo "<tr id='sss'><td>".++$temp."<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Name'])."</td><td>$Phone_Number</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Type'])."</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Number'])."</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Title'])."</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Job_Code'])."</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Department_Name'])."</td>";
                echo "<td>$signature</td>";
                if($temp%20 == 0){
                    echo $Title;
                }
            }
            }
        }
    ?>
    </table>
        <!-- <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
        <iframe width='100%' height=430px src='edit_Employee_Pre_Iframe.php'></iframe>
            </td>
        </tr>
            </table>
        </center> -->
</fieldset>

<script type="text/javascript">
    
    function Search_Employee_privileges(privirage){
          
//          alert(privirage);
        
          $.ajax({
          type:'POST',
          url:'Search_Employee_privileges_all.php',
          data:{privirage:privirage},
          success:function(data){
             $("#Employee_Area").html(data);
          },
          error:function(x,y,z){
              console.log(z);
          }
      });
        
    }
    function Search_Employee(){
        var Employee_Name = document.getElementById("Employee_Name").value;
        var Department_ID = document.getElementById("Department_ID").value;
        var Account_Status = document.getElementById("Account_Status").value;
        var Designation = document.getElementById("Designation").value;
        var Employee_PFNO = document.getElementById("Employee_PFNO").value;
        var HRWork="<?php echo (isset($_GET['HRWork']) && $_GET['HRWork']=='true')?'&HRWork=true':''; ?>";
        if(window.XMLHttpRequest){
            myObject = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        myObject.onreadystatechange = function (){
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                document.getElementById("Employee_Area").innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        
        myObject.open('GET','Search_Employee.php?Employee_Name='+Employee_Name+'&Department_ID='+Department_ID+'&Account_Status='+Account_Status+'&Designation='+Designation+'&Employee_PFNO='+Employee_PFNO+HRWork,true);
        myObject.send();
    }
    function exportInExcel(){
        var Employee_Name = document.getElementById("Employee_Name").value;
        var Department_ID = document.getElementById("Department_ID").value;
        var Account_Status = document.getElementById("Account_Status").value;
        var Designation = document.getElementById("Designation").value;
        var Employee_PFNO = document.getElementById("Employee_PFNO").value;
        window.open('exportInExcel.php?Employee_Name='+Employee_Name+'&Department_ID='+Department_ID+'&Account_Status='+Account_Status+'&Designation='+Designation+'&Employee_PFNO='+Employee_PFNO,'_blank');
    }
</script>
<script>
    $(document).ready(function () {
        $('select').select2();
    })
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<?php
    include("./includes/footer.php");
?>