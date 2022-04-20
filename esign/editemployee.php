<?php
include("./includes/connection.php");
include("./includes/header.php");
$controlforminput = '';

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
    if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes') {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}


$control_Save_Button = 'active';
if (isset($_GET['Employee_ID'])) {
    $Employee_ID = $_GET['Employee_ID'];
} else {
    $Employee_ID = 0;
    $Employee_Name = 'Undefined Employee Name';
    $Employee_Title = 'Undefined Employee Title';
    $control_Save_Button = 'inactive';
}
?>


<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes') {
        ?>
        <a href='editemployeelist.php?EditEmployeeList=EditEmployeeListThisForm' class='art-button-green'>
            BACK
        </a>
    <?php
    }
}
?>




<script language="javascript" type="text/javascript">
    function searchEmployee(Employee_Name) {
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=125px src='editEmployeeIframe.php?Employee_Name=" + Employee_Name + "'></iframe>";
    }
</script>


<?php
//select employee information based on employee id

$selectThisRecord = mysqli_query($conn,"select * from tbl_employee e, tbl_privileges p, tbl_department d
					where e.employee_id = p.employee_id and
					    d.department_id = e.department_id and
						e.employee_id = '$Employee_ID'") or die(mysqli_error($conn));
$numberOfRecord = mysqli_num_rows($selectThisRecord);
if ($numberOfRecord > 0) {
    while ($row = mysqli_fetch_array($selectThisRecord)) {
        $Employee_Name = $row['Employee_Name'];
        $Employee_Title = $row['Employee_Title'];
        $Employee_Type = $row['Employee_Type'];
        $Employee_Branch_Name = $row['Employee_Branch_Name'];
        $Department_Name = $row['Department_Name'];
        $Employee_Job_Code = $row['Employee_Job_Code'];
        $Account_Status = $row['Account_Status'];


        $Cash_Transactions = $row['Cash_Transactions'];
        $Modify_Cash_information = $row['Modify_Cash_information'];
        $Session_Master_Priveleges = $row['Session_Master_Priveleges'];
        $Modify_Credit_Information = $row['Modify_Credit_Information'];
        $Setup_And_Configuration = $row['Setup_And_Configuration'];
        $Employee_Collection_Report = $row['Employee_Collection_Report'];
        $Reception_Works = $row['Reception_Works'];
        $Revenue_Center_Works = $row['Revenue_Center_Works'];
        $Pharmacy = $row['Pharmacy'];
        $Patients_Billing_Works = $row['Patients_Billing_Works'];
        $Management_Works = $row['Management_Works'];
        $Procurement_Works = $row['Procurement_Works'];
        $Mtuha_Reports = $row['Mtuha_Reports'];
        $Nurse_Station_Works = $row['Nurse_Station_Works'];
        $General_Ledger = $row['General_Ledger'];
        $Dressing_Works = $row['Dressing_Works'];
        $Dialysis_Works = $row['Dialysis_Works'];
        $Theater_Works = $row['Theater_Works'];
        $Physiotherapy_Works = $row['Physiotherapy_Works'];
        $Maternity_Works = $row['Maternity_Works'];
        $Dental_Works = $row['Dental_Works'];
        $Eye_Works = $row['Eye_Works'];
        $Cecap_Works = $row['Cecap_Works'];
        $Doctors_Page_Outpatient_Work = $row['Doctors_Page_Outpatient_Work'];
        $Doctors_Page_Inpatient_Work = $row['Doctors_Page_Inpatient_Work'];
        $Admission_Works = $row['Admission_Works'];
        $Laboratory_Works = $row['Laboratory_Works'];
        $Radiology_Works = $row['Radiology_Works'];
        $Quality_Assurance = $row['Quality_Assurance'];
        $Storage_And_Supply_Work = $row['Storage_And_Supply_Work'];
        $Ear_Works = $row['Ear_Works'];
        $Rch_Works = $row['Rch_Works'];
        $Hiv_Works = $row['Hiv_Works'];
        $Family_Planning_Works = $row['Family_Planning_Works'];
        $Morgue_Works = $row['Morgue_Works'];
        $Blood_Bank_Works = $row['Blood_Bank_Works'];
        $Food_And_Laundry_Works = $row['Food_And_Laundry_Works'];
        $Appointment_Works = $row['Appointment_Works'];
        $Laboratory_Results_Validation = $row['Laboratory_Result_Validation'];
        $Patient_Transfer = $row['Patient_Transfer'];
        $Patient_Record_Works = $row['Patient_Record_Works'];
        $Laboratory_Consulted_Patients = $row['Laboratory_consulted_patients'];
        $Procedure_Works = $row['Procedure_Works'];
        $Approval_Orders = $row['Approval_Orders'];
        $Msamaha_Works = $row['Msamaha_Works'];
        $Edit_Patient_Information = $row['Edit_Patient_Information'];
        $can_create = $row['can_create'];
        $can_edit = $row['can_edit'];
        $can_view = $row['can_view'];
        $can_revk_bill_status= $row['can_revk_bill_status'];
        $last_modified_by = $row['last_modified_by'];//
        $can_view_demographic_revenue=$row['can_view_demographic_revenue'];//
        $can_broadcast=$row['can_broadcast'];
        $can_do_offline_trans=$row['can_do_offline_trans'];
        
    }
} else {
    $Employee_Name = 'Undefined Employee Name';
    $Employee_Title = 'Undefined Employee Title';
    $Employee_Type = '';
    $Employee_Branch_Name = '';
    $Department_Name = '';
    $Employee_Job_Code = '';
    $control_Save_Button = 'inactive';
    $Account_Status = '';

    $Cash_Transactions = '';
    $Modify_Cash_information = '';
    $Session_Master_Priveleges = '';
    $Modify_Credit_Information = '';
    $Setup_And_Configuration = '';
    $Employee_Collection_Report = '';
    $Reception_Works = '';
    $Revenue_Center_Works = '';
    $Pharmacy = '';
    $Patients_Billing_Works = '';
    $Management_Works = '';
    $Procurement_Works = '';
    $Mtuha_Reports = '';
    $Nurse_Station_Works = '';
    $General_Ledger = '';
    $Dressing_Works = '';
    $Dialysis_Works = '';
    $Theater_Works = '';
    $Physiotherapy_Works = '';
    $Maternity_Works = '';
    $Dental_Works = '';
    $Eye_Works = '';
    $Cecap_Works = '';
    $Cecap_Works = '';
    $Doctors_Page_Outpatient_Work = '';
    $Doctors_Page_Inpatient_Work = '';
    $Admission_Works = '';
    $Laboratory_Works = '';
    $Radiology_Works = '';
    $Quality_Assurance = '';
    $Storage_And_Supply_Work = '';
    $Ear_Works = '';
    $Rch_Works = '';
    $Hiv_Works = '';
    $Family_Planning_Works = '';
    $Account_Status = '';
    $Morgue_Works = '';
    $Blood_Bank_Works = '';
    $Food_And_Laundry_Works = '';
    $Appointment_Works = '';
    $Laboratory_Results_Validation = '';
    $Patient_Transfer = '';
    $Patient_Record_Works = '';
    $Laboratory_Consulted_Patients = '';
    $Procedure_Works = '';
    $Approval_Orders = '';
    $Msamaha_Works = '';
    $Edit_Patient_Information = '';
    $can_create = '';
    $can_edit = '';
    $can_view = '';
    $can_revk_bill_status='';
    $can_view_demographic_revenue='';
    $can_broadcast='';
     $can_do_offline_trans='';
}

if(!empty($last_modified_by)){
    $last_modified_by_qry=  mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$last_modified_by'") or die(mysqli_error($conn));
    $modifer_name=  mysqli_fetch_assoc($last_modified_by_qry)['Employee_Name'];
    $modifer_name_disp=",&nbsp;&nbsp;Last Modified By <span style='color: rgb(180, 246, 86)'>$modifer_name<span>";
}

//echo $modifer_name_disp;exit;
?>

<center>
    <fieldset>
        <!--<legend align="center"></legend>-->
        <table width = 100%> 
            <tr> 
            <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                <td colspan=2>

                    <fieldset>
                        <legend align="left"><b>Employee Details And Privileges<?php if ($Account_Status != 'active') { ?><span style='color: red;'>  - THIS EMPLOYEE ACCOUNT HAS BEEN BLOCKED</b></span><?php } ?></legend>
                        <table width = 100%>
                            <tr>
                                <td>

                                    <table width=100%>
                                        <tr>
                                            <td width=40%>Employee Name</td>
                                            <td><input type='text' name='Employee_Name' id='Employee_Name' required='required' value='<?php echo $Employee_Name; ?>'> </td>
                                        </tr>
                                        <tr>
                                            <td width=40%>Job Title</td>
                                            <td><input type='text' name='Job_Title' id='Job_Title' required='required' value='<?php echo $Employee_Title; ?>'> </td>
                                        </tr>
                                        <tr>
                                            <td>Job Code</td>
                                            <td>
                                                <select name='Job_Code' id='Job_Code'>
                                                    <option selected='selected'><?php echo $Employee_Job_Code; ?></option>
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
                                            <td width=30%>Employee Type</td>
                                            <td width=70%>
                                                <select name='Employee_Type' id='Employee_Type'>
                                                    <option selected='selected'><?php echo $Employee_Type; ?></option><option>Accountant</option>
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
                                                <select name='Employee_Department_Name' id='Employee_Department_Name' required='required'>
                                                    <option selected='selected'><?php echo $Department_Name; ?></option>
                                                    <?php
                                                    $Select_Department = mysqli_query($conn,"select Department_Name from tbl_department");
                                                    while ($row = mysqli_fetch_array($Select_Department)) {
                                                        echo "<option>" . $row['Department_Name'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    <table width=100%>
                                        <tr>
                                            <td>
                                                <input type='checkbox' name='Cash_Transaction' id='Cash_Transaction' value='yes' <?php
                                                if (strtolower($Cash_Transactions) == 'yes') {
                                                    echo "checked='checked'";
                                                }
                                                ?> ><label for='Cash_Transaction'>Cash Transactions</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type='checkbox' name='Modify_Cash_Information' id='Modify_Cash_Information' value='yes'  <?php
                                                if (strtolower($Modify_Cash_information) == 'yes') {
                                                    echo "checked='checked'";
                                                }
                                                ?>><label for='Modify_Cash_Information'>This User Can Modify Cash Information / Transaction</label>
                                            </td>
                                        </tr>
                                        <tr> 
                                            <td>
                                                <input type='checkbox' name='Session_Master_Privelege' id='Session_Master_Privelege' value='yes'  <?php
                                                if (strtolower($Session_Master_Priveleges) == 'yes') {
                                                    echo "checked='checked'";
                                                }
                                                ?>><label for='Session_Master_Privelege'>Session Master Privelege</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type='checkbox' name='Modify_Credit_Information' id='Modify_Credit_Information' value='yes'  <?php
                                                if (strtolower($Modify_Credit_Information) == 'yes') {
                                                    echo "checked='checked'";
                                                }
                                                ?>><label for='Modify_Credit_Information'>This User Can Modify Credit Information / Transaction</label>
                                            </td>
                                        </tr>
                                        <tr> 
                                            <td colspan=2>
                                                <input type='checkbox' name='Setup_And_Configuration' id='Setup_And_Configuration' value='yes'  <?php
                                                if (strtolower($Setup_And_Configuration) == 'yes') {
                                                    echo "checked='checked'";
                                                }
                                                ?>><label for='Setup_And_Configuration'>Setup And Configuration</label>
                                            </td> 
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type='checkbox' name='Employee_Collection_Report' id='Employee_Collection_Report' value='yes'  <?php
                                                if (strtolower($Employee_Collection_Report) == 'yes') {
                                                    echo "checked='checked'";
                                                }
                                                ?>><label for='Employee_Collection_Report'>Employee Collection Report</label>
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
                            <legend align="left"><b>Dashboard Access Privileges <?php echo $modifer_name_disp;?></b> </legend>
                            <table width = 100%>
                                <tr>
                                    <td width=20%>
                                        <input type='checkbox' name='Reception_Works' id='Reception_Works' value='yes' <?php
                                        if (strtolower($Reception_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Reception_Works'>Reception Works</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Revenue_Center_Works' id='Revenue_Center_Works' value='yes' <?php
                                        if (strtolower($Revenue_Center_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Revenue_Center_Works'>Revenue Center Works</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Pharmacy' id='Pharmacy' value='yes' <?php
                                        if (strtolower($Pharmacy) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Pharmacy'>Pharmacy Works</label>
                                    </td> 
                                    <td width=20%>
                                        <input type='checkbox' name='Patients_Billing_Works' id='Patients_Billing_Works' value='yes' <?php
                                        if (strtolower($Patients_Billing_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Patients_Billing_Works'>Patients Billing Works</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Management_Works' id='Management_Works' value='yes' <?php
                                        if (strtolower($Management_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Management_Works'>Management Works</label>
                                    </td>

                                </tr>
                                <tr>
                                    <td width=20%>
                                        <input type='checkbox' name='Procurement_Works' id='Procurement_Works' value='yes' <?php
                                        if (strtolower($Procurement_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Procurement_Works'>Procurement Works</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Mtuha_Reports' id='Mtuha_Reports' value='yes' <?php
                                        if (strtolower($Mtuha_Reports) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Mtuha_Reports'>Mtuha Reports</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Nurse_Station_Works' id='Nurse_Station_Works' value='yes' <?php
                                        if (strtolower($Nurse_Station_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Nurse_Station_Works'>Nurse Station Works</label>
                                    </td> 
                                    <td width=20%>
                                        <input type='checkbox' name='General_Ledger' id='General_Ledger' value='yes' <?php
                                        if (strtolower($General_Ledger) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='General_Ledger'>General Ledger</label>
                                    </td> 
                                    <td width=20%>
                                        <input type='checkbox' name='Dressing_Works' id='Dressing_Works' value='yes' <?php
                                        if (strtolower($Dressing_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Dressing_Works'>Dressing Works</label>
                                    </td> 

                                </tr>
                                <tr>
                                    <td width=20%>
                                        <input type='checkbox' name='Dialysis_Works' id='Dialysis_Works' value='yes' <?php
                                        if (strtolower($Dialysis_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Dialysis_Works'>Dialysis Works</label>
                                    </td> 
                                    <td width=20%>
                                        <input type='checkbox' name='Theater_Works' id='Theater_Works' value='yes' <?php
                                        if (strtolower($Theater_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Theater_Works'>Theater Works</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Physiotherapy_Works' id='Physiotherapy_Works' value='yes' <?php
                                        if (strtolower($Physiotherapy_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Physiotherapy_Works'>Physiotherapy Works</label>
                                    </td> 
                                    <td width=20%>
                                        <input type='checkbox' name='Maternity_Works' id='Maternity_Works' value='yes' <?php
                                        if (strtolower($Maternity_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Maternity_Works'>Maternity Works</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Dental_Works' id='Dental_Works' value='yes' <?php
                                        if (strtolower($Dental_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Dental_Works'>Dental Works</label>
                                    </td> 
                                </tr>
                                <tr>
                                    <td width=20%>
                                        <input type='checkbox' name='Eye_Works' id='Eye_Works' value='yes' <?php
                                        if (strtolower($Eye_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Eye_Works'>Optical Works</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Cecap_Works' id='Cecap_Works' value='yes' <?php
                                        if (strtolower($Cecap_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Cecap_Works'>Cecap Works</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Doctors_Page_Outpatient_Work' id='Doctors_Page_Outpatient_Work' value='yes' <?php
                                        if (strtolower($Doctors_Page_Outpatient_Work) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Doctors_Page_Outpatient_Work'>Doctors Page Outpatient Work</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Doctors_Page_Inpatient_Work' id='Doctors_Page_Inpatient_Work' value='yes' <?php
                                        if (strtolower($Doctors_Page_Inpatient_Work) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Doctors_Page_Inpatient_Work'>Doctors Page Inpatient Work</label>
                                    </td> 
                                    <td width=20%>
                                        <input type='checkbox' name='Admission_Works' id='Admission_Works' value='yes' <?php
                                        if (strtolower($Admission_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Admission_Works'>Admission Works</label>
                                    </td>
                                </tr>  
                                <tr>
                                    <td width=20%>
                                        <input type='checkbox' name='Laboratory' id='Laboratory' value='yes' <?php
                                        if (strtolower($Laboratory_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Laboratory'>Laboratory Works</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Radiology' id='Radiology' value='yes' <?php
                                        if (strtolower($Radiology_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Radiology'>Radiology Works</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Quality_Assurance' id='Quality_Assurance' value='yes' <?php
                                        if (strtolower($Quality_Assurance) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Quality_Assurance'>Quality Assurance</label>
                                    </td> 
                                    <td width=20%>
                                        <input type='checkbox' name='Storage_And_Supply_Work' id='Storage_And_Supply_Work' value='yes' <?php
                                        if (strtolower($Storage_And_Supply_Work) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Storage_And_Supply_Work'>Storage And Supply</label>
                                    </td>   
                                    <td width=20%>
                                        <input type='checkbox' name='Ear_Work' id='Ear_Work' value='yes' <?php
                                        if (strtolower($Ear_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Ear_Work'>Ear Work</label>
                                    </td>  

                                </tr> 
                                <tr>
                                    <td width=20%>
                                        <input type='checkbox' name='Rch_Works' id='Rch_Works' value='yes' <?php
                                        if (strtolower($Rch_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Rch_Works'>RCH Works</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Hiv_Works' id='Hiv_Works' value='yes' <?php
                                        if (strtolower($Hiv_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Hiv_Works'>HIV Works</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Family_Planning_Works' id='Family_Planning_Works' value='yes' <?php
                                        if (strtolower($Family_Planning_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Family_Planning_Works'>Family Planning Works</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Morgue_Works' id='Morgue_Works' value='yes' <?php
                                        if (strtolower($Morgue_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Morgue_Works'>Morgue Works</label>
                                    </td> 
                                    <td width=20%>
                                        <input type='checkbox' name='Blood_Bank_Works' id='Blood_Bank_Works' value='yes' <?php
                                        if (strtolower($Blood_Bank_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Blood_Bank_Works'>Blood Bank Works</label>
                                    </td>  
                                </tr>
                                <tr>
                                    <td width=20%>
                                        <input type='checkbox' name='Food_And_Laundry_Works' id='Food_And_Laundry_Works' value='yes' <?php
                                        if (strtolower($Food_And_Laundry_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Food_And_Laundry_Works'>Food And Laundry Works</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Appointment_Works' id='Appointment_Works' value='yes' <?php
                                        if (strtolower($Appointment_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Appointment_Works'>Patient Appointment Works</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Laboratory_Results_Validation' id='Laboratory_Results_Validation' value='yes' <?php
                                        if (strtolower($Laboratory_Results_Validation) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Laboratory_Results_Validation'>Laboratory Results Validation</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Patient_Transfer' id='Patient_Transfer' value='yes' <?php
                                        if (strtolower($Patient_Transfer) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Patient_Transfer'>Patient Transfer</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Patient_Record_Works' id='Patient_Record_Works' value='yes' <?php
                                        if (strtolower($Patient_Record_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Patient_Record_Works'>Patient Record Works</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td width=20%>
                                        <input type='checkbox' name='Laboratory_Consulted_Patients' id='Laboratory_Consulted_Patients' value='yes' <?php
                                        if (strtolower($Laboratory_Consulted_Patients) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Laboratory_Consulted_Patients'>Laboratory Consulted Patients</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Procedure_Works' id='Procedure_Works' value='yes' <?php
                                        if (strtolower($Procedure_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Procedure_Works'>Procedure Works</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Approval_Orders' id='Approval_Orders' value='yes' <?php
                                        if (strtolower($Approval_Orders) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Approval_Orders'>Approve Store Order</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Msamaha_Works' id='Msamaha_Works' value='yes' <?php
                                        if (strtolower($Msamaha_Works) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Msamaha_Works'>Msamaha Works</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='Edit_Patient_Information' id='Edit_Patient_Information' value='yes' <?php
                                        if (strtolower($Edit_Patient_Information) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='Edit_Patient_Information'>Edit Patient Information</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td width=20%>
                                        <input type='checkbox' name='can_create' id='can_create' value='yes' <?php
                                        if (strtolower($can_create) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='can_create'>Can Create</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='can_edit' id='can_edit' value='yes' <?php
                                        if (strtolower($can_edit) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='can_edit'>Can Edit</label>
                                    </td>
                                    <td width=20% >
                                        <input type='checkbox' name='can_view' id='can_view' value='yes' <?php
                                        if (strtolower($can_view) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='can_view'>Create View</label>
                                    </td>
                                    <td width=20%>
                                        <input type='checkbox' name='can_revk_bill_status' id='can_revk_bill_status' value='yes' <?php
                                        if (strtolower($can_revk_bill_status) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='can_revk_bill_status'>Can Revoke Bill Status</label>
                                    </td>
                                    <td width=20% >
                                        <input type='checkbox' name='can_view_demographic_revenue' id='can_view_demographic_revenue' value='yes' <?php
                                        if (strtolower($can_view_demographic_revenue) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='can_view'>Can View Demographic Report Revenue</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td width=20% >
                                        <input type='checkbox' name='can_broadcast' id='can_broadcast' value='yes' <?php
                                        if (strtolower($can_broadcast) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='can_broadcast'>Can Broadcast Message</label>
                                    </td>
                                    <td width=20% colspan="4">
                                        <input type='checkbox' name='can_do_offline_trans' id='can_do_offline_trans' value='yes' <?php
                                        if (strtolower($can_do_offline_trans) == 'yes') {
                                            echo "checked='checked'";
                                        }
                                        ?>>
                                        <label for='can_do_offline_trans'>Can Do Offline Transaction</label>
                                    </td>
                                </tr>
                            </table> 
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width=100%>
                            <tr>
                                <td>Admin Username</td>
                                <td><input type='text' name='Admin_Username' id='Admin_Username' autocomplete='off' required='required' placeholder='Enter Your System Username'></td>
                            </tr>
                            <tr>
                                <td>Admin Password</td>
                                <td><input type='password' name='Admin_Password' id='Admin_Password' required='required' placeholder='Enter Your System Password'></td>
                            </tr>
                        </table>

                    </td>
                    <td style='text-align: right;'><br/>
                        <table width=100%>
                            <tr>
                                <td  style='text-align: right;'>
                                    &nbsp;&nbsp;&nbsp;

<?php if ($control_Save_Button == 'active') { ?>
                                        <input type='submit' name='submit' id='submit' value='   SAVE CHANGES   ' class='art-button-green'>&nbsp;&nbsp;&nbsp;
                                        <a href='changeuserpassword.php?Employee_ID=<?php echo $Employee_ID; ?>&ChangeUserPassword=ChangeUserPasswordThisPage' class='art-button-green'>CHANGE EMPLOYEE USERNAME / PASSWORD</a>

                                        <a href='../esign/employee_signature.php?Employee_ID=<?php echo $Employee_ID; ?>&ChangeUserPassword=ChangeUserPasswordThisPage' class='art-button-green'>TAKE EMPLOYEE SIGNATURE</a>


                                        <?php if ($_SESSION['userinfo']['Employee_ID'] != $Employee_ID) { ?>

                                            <?php if ($Account_Status == 'active') { ?>
                                                <a href='blockthisaccount.php?Employee_ID=<?php echo $Employee_ID; ?>' class='art-button-green'>BLOCK THIS ACCOUNT</a>
                                            <?php } else { ?>
                                                <a href='activatethisaccount.php?Employee_ID=<?php echo $Employee_ID; ?>' class='art-button-green'>ACTIVATE THIS ACCOUNT</a>
                                            <?php } ?>

    <?php } ?>

                                        <a href='editemployeelist.php?EditEmployeeList=EditEmployeeListThisForm' class='art-button-green'>CANCEL</a>
                                        <input type='hidden' name='submittedEditUserPrivilegesForm' value='true'/>
                                    <?php } else { ?>
                                        <button class='art-button-green'>INACTIVE SAVE</button>  
<?php } ?>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>
        </table>
    </fieldset>
</center> 
</form>




<?php
if (isset($_POST['submittedEditUserPrivilegesForm'])) {

    $Admin_Username = $_POST['Admin_Username'];
    $Admin_Password = md5($_POST['Admin_Password']);

    $Current_Username = $_SESSION['userinfo']['Given_Username'];
    $Current_Password = $_SESSION['userinfo']['Given_Password'];

    if (strtolower($Current_Username) == strtolower($Admin_Username) && $Current_Password == $Admin_Password) {

        //get employee updated details(Particulars)
        $Employee_Name2 = $_POST['Employee_Name'];
        $Job_Title2 = $_POST['Job_Title'];
        $Job_Code = $_POST['Job_Code'];
        $Employee_Type = $_POST['Employee_Type'];
        $Employee_Department_Name = $_POST['Employee_Department_Name'];


        //Get previous value if null value is inserted
        if ($Employee_Name2 == '') {
            $Employee_Name2 = $Employee_Name;
        }
        if ($Job_Title2 == '') {
            $Job_Title2 = $Employee_Title;
        }



        //get employee updated details(Privileges)
        $Cash_Transaction = 'no';
        $Modify_Cash_Information = 'no';
        $Session_Master_Privelege = 'no';
        $Modify_Credit_Information = 'no';
        $Setup_And_Configuration = 'no';
        $Employee_Collection_Report = 'no';
        $Reception_Works = 'no';
        $Revenue_Center_Works = 'no';
        $Pharmacy = 'no';
        $Patients_Billing_Works = 'no';
        $Management_Works = 'no';
        $Procurement_Works = 'no';
        $Mtuha_Reports = 'no';
        $Nurse_Station_Works = 'no';
        $General_Ledger = 'no';
        $Dressing_Works = 'no';
        $Dialysis_Works = 'no';
        $Theater_Works = 'no';
        $Physiotherapy_Works = 'no';
        $Maternity_Works = 'no';
        $Dental_Works = 'no';
        $Eye_Works = 'no';
        $Cecap_Works = 'no';
        $Doctors_Page_Outpatient_Work = 'no';
        $Doctors_Page_Inpatient_Work = 'no';
        $Admission_Works = 'no';
        $Laboratory = 'no';
        $Radiology = 'no';
        $Quality_Assurance = 'no';
        $Storage_And_Supply_Work = 'no';
        $Ear_Works = 'no';
        $Rch_Works = 'no';
        $Hiv_Works = 'no';
        $Family_Planning_Works = 'no';
        $Morgue_Works = 'no';
        $Blood_Bank_Works = 'no';
        $Food_And_Laundry_Works = 'no';
        $Appointment_Works = 'no';
        $Laboratory_Results_Validation = 'no';
        $Patient_Transfer = 'no';
        $Patient_Record_Works = 'no';
        $Laboratory_Consulted_Patients = 'no';
        $Procedure_Works = 'no';
        $Approval_Orders = 'no';
        $Msamaha_Works = 'no';
        $Edit_Patient_Information = 'no';
        $can_create = 'no';
        $can_edit = 'no';
        $can_view = 'no';
        $can_view_demographic_revenue='no';  
        $can_revk_bill_status = 'no';
        $can_broadcast = 'no';
        $can_do_offline_trans='no';

        if (isset($_POST['Cash_Transaction'])) {
            $Cash_Transaction = 'yes';
        }
        if (isset($_POST['Modify_Cash_Information'])) {
            $Modify_Cash_Information = 'yes';
        }
        if (isset($_POST['Session_Master_Privelege'])) {
            $Session_Master_Privelege = 'yes';
        }
        if (isset($_POST['Modify_Credit_Information'])) {
            $Modify_Credit_Information = 'yes';
        }
        if (isset($_POST['Setup_And_Configuration'])) {
            $Setup_And_Configuration = 'yes';
        }
        if (isset($_POST['Employee_Collection_Report'])) {
            $Employee_Collection_Report = 'yes';
        }
        if (isset($_POST['Reception_Works'])) {
            $Reception_Works = 'yes';
        }
        if (isset($_POST['Revenue_Center_Works'])) {
            $Revenue_Center_Works = 'yes';
        }
        if (isset($_POST['Pharmacy'])) {
            $Pharmacy = 'yes';
        }
        if (isset($_POST['Patients_Billing_Works'])) {
            $Patients_Billing_Works = 'yes';
        }
        if (isset($_POST['Management_Works'])) {
            $Management_Works = 'yes';
        }
        if (isset($_POST['Procurement_Works'])) {
            $Procurement_Works = 'yes';
        }
        if (isset($_POST['Mtuha_Reports'])) {
            $Mtuha_Reports = 'yes';
        }
        if (isset($_POST['Nurse_Station_Works'])) {
            $Nurse_Station_Works = 'yes';
        }
        if (isset($_POST['General_Ledger'])) {
            $General_Ledger = 'yes';
        }
        if (isset($_POST['Dressing_Works'])) {
            $Dressing_Works = 'yes';
        }
        if (isset($_POST['Dialysis_Works'])) {
            $Dialysis_Works = 'yes';
        }
        if (isset($_POST['Theater_Works'])) {
            $Theater_Works = 'yes';
        }
        if (isset($_POST['Physiotherapy_Works'])) {
            $Physiotherapy_Works = 'yes';
        }
        if (isset($_POST['Maternity_Works'])) {
            $Maternity_Works = 'yes';
        }
        if (isset($_POST['Dental_Works'])) {
            $Dental_Works = 'yes';
        }
        if (isset($_POST['Eye_Works'])) {
            $Eye_Works = 'yes';
        }
        if (isset($_POST['Cecap_Works'])) {
            $Cecap_Works = 'yes';
        }
        if (isset($_POST['Doctors_Page_Outpatient_Work'])) {
            $Doctors_Page_Outpatient_Work = 'yes';
        }
        if (isset($_POST['Doctors_Page_Inpatient_Work'])) {
            $Doctors_Page_Inpatient_Work = 'yes';
        }
        if (isset($_POST['Admission_Works'])) {
            $Admission_Works = 'yes';
        }
        if (isset($_POST['Laboratory'])) {
            $Laboratory = 'yes';
        }
        if (isset($_POST['Radiology'])) {
            $Radiology = 'yes';
        }
        if (isset($_POST['Quality_Assurance'])) {
            $Quality_Assurance = 'yes';
        }
        if (isset($_POST['Storage_And_Supply_Work'])) {
            $Storage_And_Supply_Work = 'yes';
        }
        if (isset($_POST['Ear_Work'])) {
            $Ear_Works = 'yes';
        }
        if (isset($_POST['Rch_Works'])) {
            $Rch_Works = 'yes';
        }
        if (isset($_POST['Hiv_Works'])) {
            $Hiv_Works = 'yes';
        }
        if (isset($_POST['Family_Planning_Works'])) {
            $Family_Planning_Works = 'yes';
        }
        if (isset($_POST['Morgue_Works'])) {
            $Morgue_Works = 'yes';
        }
        if (isset($_POST['Blood_Bank_Works'])) {
            $Blood_Bank_Works = 'yes';
        }
        if (isset($_POST['Food_And_Laundry_Works'])) {
            $Food_And_Laundry_Works = 'yes';
        }
        if (isset($_POST['Appointment_Works'])) {
            $Appointment_Works = 'yes';
        }
        if (isset($_POST['Laboratory_Results_Validation'])) {
            $Laboratory_Results_Validation = 'yes';
        }
        if (isset($_POST['Patient_Transfer'])) {
            $Patient_Transfer = 'yes';
        }
        if (isset($_POST['Patient_Record_Works'])) {
            $Patient_Record_Works = 'yes';
        }
        if (isset($_POST['Laboratory_Consulted_Patients'])) {
            $Laboratory_Consulted_Patients = 'yes';
        }
        if (isset($_POST['Procedure_Works'])) {
            $Procedure_Works = 'yes';
        }
        if (isset($_POST['Approval_Orders'])) {
            $Approval_Orders = 'yes';
        }
        if (isset($_POST['Msamaha_Works'])) {
            $Msamaha_Works = 'yes';
        }
        if (isset($_POST['Edit_Patient_Information'])) {
            $Edit_Patient_Information = 'yes';
        }
        if (isset($_POST['can_create'])) {
            $can_create = 'yes';
        }
        if (isset($_POST['can_edit'])) {
            $can_edit = 'yes';
        }
        if (isset($_POST['can_view'])) {
            $can_view = 'yes';
        }
        if (isset($_POST['can_revk_bill_status'])) {
            $can_revk_bill_status = 'yes';
        }
        if (isset($_POST['can_view_demographic_revenue'])) {
            $can_view_demographic_revenue = 'yes';
        }
        if (isset($_POST['can_broadcast'])) {
            $can_broadcast = 'yes';
        }
         if (isset($_POST['can_do_offline_trans'])) {
            $can_do_offline_trans = 'yes';
        }

        $sql = "update tbl_privileges set 
				Reception_Works = '$Reception_Works',Revenue_Center_Works = '$Revenue_Center_Works',Patients_Billing_Works = '$Patients_Billing_Works',
				Procurement_Works = '$Procurement_Works',Mtuha_Reports = '$Mtuha_Reports',General_Ledger = '$General_Ledger',
				Management_Works = '$Management_Works',Nurse_Station_Works = '$Nurse_Station_Works',Admission_Works = '$Admission_Works',
				Laboratory_Works = '$Laboratory',Radiology_Works = '$Radiology',Quality_Assurance = '$Quality_Assurance',
				Dressing_Works = '$Dressing_Works',Dialysis_Works = '$Dialysis_Works',Theater_Works = '$Theater_Works',
				Physiotherapy_Works = '$Physiotherapy_Works',Maternity_Works = '$Maternity_Works',Dental_Works = '$Dental_Works',
				Eye_Works = '$Eye_Works',Cecap_Works = '$Cecap_Works',Modify_Cash_information = '$Modify_Cash_Information',
				Session_Master_Priveleges = '$Session_Master_Privelege',Cash_Transactions = '$Cash_Transaction',Modify_Credit_Information = '$Modify_Credit_Information',
				Setup_And_Configuration = '$Setup_And_Configuration',Doctors_Page_Inpatient_Work = '$Doctors_Page_Inpatient_Work',Doctors_Page_Outpatient_Work = '$Doctors_Page_Outpatient_Work',
				Pharmacy = '$Pharmacy',Storage_And_Supply_Work = '$Storage_And_Supply_Work',Ear_Works = '$Ear_Works',
				Employee_Collection_Report = '$Employee_Collection_Report',Rch_Works = '$Rch_Works',Morgue_Works = '$Morgue_Works',Blood_Bank_Works = '$Blood_Bank_Works',Food_And_Laundry_Works = '$Food_And_Laundry_Works',
				Appointment_Works='$Appointment_Works',Hiv_Works = '$Hiv_Works', Family_Planning_Works = '$Family_Planning_Works', Laboratory_Result_Validation = '$Laboratory_Results_Validation' ,Patient_Transfer = '$Patient_Transfer',
				Patient_Record_Works = '$Patient_Record_Works', Laboratory_Consulted_Patients = '$Laboratory_Consulted_Patients', Procedure_Works = '$Procedure_Works', Approval_Orders = '$Approval_Orders', Msamaha_Works = '$Msamaha_Works',Edit_Patient_Information='$Edit_Patient_Information',can_create='$can_create',can_edit='$can_edit',can_view='$can_view',can_revk_bill_status='$can_revk_bill_status',can_view_demographic_revenue='$can_view_demographic_revenue',can_broadcast='$can_broadcast',can_do_offline_trans='$can_do_offline_trans',last_modified_by='".$_SESSION['userinfo']['Employee_ID']."' where employee_id = '$Employee_ID'";

        if (!mysqli_query($conn,$sql)) {
            die(mysqli_error($conn));
        } else {
            mysqli_query($conn,"update tbl_employee set 
			    Employee_Name = '$Employee_Name2', Employee_Type = '$Employee_Type', 
				Employee_Title = '$Job_Title2', Employee_Job_Code = '$Job_Code',
					Department_id = (select Department_id from tbl_department where department_name = '$Employee_Department_Name') 
					    where employee_id = '$Employee_ID'");
            echo "<script type='text/javascript'>
			       alert('CHANGES SAVED SUCCESSFUL');
			       document.location = './editemployee.php?Employee_ID=" . $Employee_ID . "&EditEmployeePrivileges=EditEmployeePrivilegesThisPage';
			   </script>";
        }
    } else {
        echo "<script type='text/javascript'>
			       alert('INVALID USERNAME OR PASSWORD. NO PRIVILEGE TO SAVE CHANGES');
			       document.location = './editemployee.php?Employee_ID=" . $Employee_ID . "&EditEmployeePrivileges=No';
			   </script>";
    }
}
?>




<?php
include("./includes/footer.php");
?>