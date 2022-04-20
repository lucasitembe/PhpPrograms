<?php
include("./includes/connection.php");
include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Setup_And_Configuration']) || isset($_SESSION['userinfo']['Appointment_Works'])) {
        if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes') {
            if ($_SESSION['userinfo']['Appointment_Works'] != 'yes')
                header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' && !isset($_GET['HRWork'])) {
        echo "<a href='employeepage.php?EmployeeManagement=EmployeeManagementThisPage' class='art-button-green'>BACK</a>";
    }
    if (isset($_GET['HRWork']) && $_GET['HRWork'] == 'true') {
        echo "<a href='human_resource.php?HRWork=HRWorkThisPage' class='art-button-green'>BACK</a>";
    }
}
?>
<br/><br/><br/><br/><br/><br/>

<center>
    <table width=50%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>ADD NEW EMPLOYEE</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                           
                                <tr>
                                    <td width=30% style="text-align:right;"><b>Employee Name</b></td>
                                    <td width=70%><input type='text' name='Employee_Name' required='required' size=70 id='Employee_Name' placeholder='Enter Employee Name'></td>
                                </tr>
                                <!--tr>
                                    <td width=30% style="text-align:right;"><b>Gender</b></td>
                                    <td width=70%>
                                        <input type='radio' name='gender' id='gender' value="male" checked><label for="male">Male</label>
                                        <input type='radio' name='gender' id='gender' value="female" style="margin-left:15px;"><label for="female">Female</label>
                                    </td>
                                </tr-->
                                <tr>
                                    <td width=30% style="text-align:right;"><b>Designation</b></td>
                                    <td width=70%>
                                        <select name='Employee_Type' id='Employee_Type' required='required' onChage="addDoctorLicense()">
                                            <option selected='selected'></option>
                                            <?php
                                            $select_designation = mysqli_query($conn,"SELECT * FROM tbl_designation");
                                            while ($row = mysqli_fetch_assoc($select_designation)) {
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
                                        </select>
                                    <input type="submit" name="add_desination" value="Add New" style="float:right;" class="art-button-green" id="add_desination">
                                </td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;"><b>Select Kada</b></td>
                                    <td>
                                        <select name="kada" required="required">
                                            <option value=""></option>
                                            <option value="general_practitioner">General Practitioner</option>
                                            <option value="specialist">Specialist</option>
                                            <option value="super_specialist">Super Specialist</option>
                                            <option value="others">Others</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;"><b>Employee PFNO</b></td>
                                    <td><input type='text' name='Employee_Number'  id='Employee_Number' placeholder='Enter Employee PFNO'></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;"><b>Employee Check Number</b></td>
                                    <td><input type='text' name='Employee_Check_Number'  id='Employee_Check_Number' placeholder='Enter Employee Check Number'></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;"><b> Phone Number</b></td>
                                    <td><input type='text' name='Phone_Number'  id='Phone_Number' required="" placeholder='Enter Phone Number'></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;"><b>Job Title</b></td>
                                    <td>
                                        <input type='text' name='Employee_Title' id='Employee_Title'>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;"><b>Job Code</b></td>
                                    <td>
                                        <select name='Job_Code' id='Job_Code'>
                                            <option>Anaesthesiologist</option>
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
                                        <!--input type="submit" name="add_job_code" id="add_job_code" value="Add New" style="float:right;" class="art-button-green"-->
                                    </td>

                                </tr>
                                <tr id="license">
                                <td style='text-align:right; font-weight:bold'>Doctor License</td><td><input  style='' name='license' id='doctor-license' type='text' placeholder='doctor License'/></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;"><b>Department Name</b></td>
                                    <td>
					<select name='Employee_Department_Name' id='Employee_Department_Name' required='required'>
					    <option selected='selected'></option>
					    <?php
        $Select_Department = mysqli_query($conn,"select Department_Name from tbl_department");
        while ($row = mysqli_fetch_array($Select_Department)) {
            echo "<option>" . $row['Department_Name'] . "</option>";
        }
        ?>
					</select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;"><b>Branch Name</b></td>
                                    <td><select name='Employee_Branch_Name' id='Employee_Branch_Name'>
                                        <?php
                                        $data = mysqli_query($conn,"select * from tbl_branches");
                                        while ($row = mysqli_fetch_array($data)) {
                                            echo '<option>' . $row['Branch_Name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedAddNewEmployeeForm' value='true'/> 
                                    
                                        <span>
                                    <a href='../esign/employee_signature.php?Employee_ID=<?php echo $Employee_ID; ?>&ChangeUserPassword=ChangeUserPasswordThisPage' class='art-button-green'>TAKE EMPLOYEE SIGNATURE</a>
                                    </span>
                                    
                                    </td>
                                      
                                </tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>


<?php
if (isset($_POST['submittedAddNewEmployeeForm'])) {
    $Employee_Name = mysqli_real_escape_string($conn,str_replace("'", "&#39;",$_POST['Employee_Name']));  
		        //$gender = mysqli_real_escape_string($conn,$_POST['gender']);  
    $Employee_Type = mysqli_real_escape_string($conn,$_POST['Employee_Type']);
    $Employee_Title = mysqli_real_escape_string($conn,$_POST['Employee_Title']);
    $Employee_Number = mysqli_real_escape_string($conn,$_POST['Employee_Number']);
    $Job_Code = mysqli_real_escape_string($conn,$_POST['Job_Code']);
    $license = mysqli_real_escape_string($conn,trim($_POST['license']));
    $Phone_Number = mysqli_real_escape_string($conn,$_POST['Phone_Number']);
    $Employee_Check_Number = mysqli_real_escape_string($conn,$_POST['Employee_Check_Number']);
    $Employee_Branch_Name = mysqli_real_escape_string($conn,$_POST['Employee_Branch_Name']);
    $Employee_Department_Name = mysqli_real_escape_string($conn,$_POST['Employee_Department_Name']);
    $kada = mysqli_real_escape_string($conn,$_POST['kada']);
    $Employee_Creator = $_SESSION['userinfo']['Employee_ID'];

    $sql = "INSERT into tbl_employee(
						Employee_Name,Employee_Type,Employee_Number,Employee_Check_Number,
						    Employee_Title,Employee_Job_Code,
						    Employee_Branch_Name,Department_id,kada,Phone_Number,doctor_license,created_at,created_by)
				    values('$Employee_Name','$Employee_Type','$Employee_Number','$Employee_Check_Number',
					    '$Employee_Title','$Job_Code','$Employee_Branch_Name',
					    (select department_id from tbl_department where department_name = '$Employee_Department_Name'),'$kada','$Phone_Number','$license',(SELECT NOW()),$Employee_Creator)";
    if (!mysqli_query($conn,$sql)) {
        $error = '1062yes';
        if (mysql_errno() . "yes" == $error) {
            $controlforminput = 'not valid';
        }
    } else {
        $selectThisRecord = mysqli_query($conn,"SELECT Employee_ID from tbl_employee where
					Employee_Name = '$Employee_Name' and
					Employee_Type = '$Employee_Type' and
					Employee_Number = '$Employee_Number' and
					Employee_Title = '$Employee_Title' and
					Employee_Job_Code = '$Job_Code' and
					Employee_Branch_Name = '$Employee_Branch_Name'");

        while ($row = mysqli_fetch_array($selectThisRecord)) {
            $Employee_ID = $row['Employee_ID'];
        }
        echo "<script type='text/javascript'>
			    alert('EMPLOYEE ADDED SUCCESSFUL');
			    document.location = './userprivileges.php?Employee_ID=" . $Employee_ID . "&SetupAndConfig=SetupAndConfigThisPage&HRWork=true';
			    </script>";

    }
}
?>
<div id="addDesignation" style="display:none;">
    <form action="" method="post" border='0'>
        <table width="100%;">
            <tr>
            <td style="text-align:right;"><b>Designation: </b></td>
            <td><input type="text" name="newDesignation" id="newDesignation" placeholder="Enter New Designation"></td>
            </tr>
            <tr><td colspan="2"><br></td></tr>


            <tr><td colspan="2" style="text-align:center;"><input type="submit" name="btn_save_designation" id="btn_save_designation" class="art-button-green" value="Save"><input type="submit" name="btn_cancel" value="Cancel" class="art-button-green">
            </td></tr>
        </table>
    </form>
</div>
<div id="addJobCode" style="display:none">
    <form action="" method="post" border='0'>
        <table width="100%;">
            <tr><td style="text-align:right;"><b>Job Code: </b></td><td><input type="text" name="newJobCode" id="newJobCode" placeholder="Enter New Job Code"></td></tr>
            <tr><td colspan="2"><br></td></tr>
            <tr><td colspan="2" style="text-align:center;"><input type="submit" name="btn_save_job_code" id="btn_save_job_code" class="art-button-green" value="Save"><input type="submit" name="btn_cancel" value="Cancel" class="art-button-green">
            </td></tr>
        </table>
    </form>
</div>
<?php
if (isset($_POST['btn_save_designation'])) {
    $designation = mysqli_real_escape_string($conn,$_POST['newDesignation']);
    $selectDesignation = mysqli_query($conn,"SELECT * FROM tbl_designation WHERE designation='$designation'");
    if (mysqli_num_rows($selectDesignation) > 0) {
        echo "<script>alert('Designation Already Exists');</script>";
    } else {
        $query = mysqli_query($conn,"INSERT INTO tbl_designation(designation) VALUES('$designation')");
        if ($query) {
            echo "<script>alert('Designation Added Successifully');</script>";
        } else {
            echo "<script>alert('Process Fails');</script>";
        }
    }
}
if (isset($_POST['btn_save_job_code'])) {
    $jobCode = mysqli_real_escape_string($conn,$_POST['newJobCode']);
    $selectJobCode = mysqli_query($conn,"SELECT * FROM tbl_job_code WHERE job_code_name='$jobCode'");
    if (mysqli_num_rows($selectJobCode) > 0) {
        echo "<script>alert('Job Code Already Exists');</script>";
    } else {
        $query = mysqli_query($conn,"INSERT INTO tbl_job_code(job_code_name) VALUES('$jobCode')");
        if ($query) {
            echo "<script>alert('Job Code Added Successifully');</script>";
        } else {
            echo "<script>alert('Process Fails');</script>";
        }
    }
}
?>
    <script>
        $(document).ready(function () {
            $("#addDesignation").dialog({autoOpen: false, width: '55%', height: 250, title: 'Add New Designation', modal: true});
            $("#addJobCode").dialog({autoOpen: false, width: '55%', height: 250, title: 'Add New Job Code', modal: true});



       

        });
    </script>
    <script type="text/javascript">
        $("#add_desination").on("click",function(){
            $("#addDesignation").dialog("open")});
        $("#add_job_code").on("click",function(){
            $("#addJobCode").dialog("open");
        });



        $("#Employee_Type").change(function(){
            var employeeType = $("#Employee_Type").val()
            if(employeeType === "Doctor")
            {
                $("#license").append("")
            }

        })

    </script>
<?php
include("./includes/footer.php");
?>