<?php
include("./includes/connection.php");
include("./includes/header.php");
$controlforminput = '';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

//    if(isset($_SESSION['userinfo']['Pharmacy'])){
//	if($_SESSION['userinfo']['Pharmacy'] != 'yes'){
//	    header("Location: ./index.php?InvalidPrivilege=yes");
//	}
//    }else{
//	@session_destroy();
//	header("Location: ../index.php?InvalidPrivilege=yes");
//    }
?>
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<?php
  if(isset($_POST['submit'])){
      session_start();
      
//      $_SESSION['bill_Clinic_ID'] = $_GET['bill_Clinic_ID'];
//      $_SESSION['bill_clinic_location_id'] = $_GET['bill_clinic_location_id'];
      $_SESSION['bill_working_department'] = $_POST['bill_working_department'];
      
  }
//get department location
if (isset($_GET['SessionCategory'])) {
    $SessionCategory = $_GET['SessionCategory'];
    if (strtolower($SessionCategory) == 'procedure') {
        $SessionCat = "((dep.Department_Location='Dialysis') OR
		    (dep.Department_Location='Physiotherapy') OR (dep.Department_Location='Optical')OR
		    (dep.Department_Location='Dressing')OR(dep.Department_Location='Maternity')OR
		    (dep.Department_Location='Cecap')OR(dep.Department_Location='Dental')OR
		    (dep.Department_Location='Ear') OR(dep.Department_Location='Hiv') OR
		    (dep.Department_Location='Eye') OR(dep.Department_Location='Maternity') OR
		    (dep.Department_Location='Rch') OR(dep.Department_Location='Theater') OR
		    (dep.Department_Location='Family Planning')OR(dep.Department_Location='Surgery')
		    OR(dep.Department_Location='Procedure'))";
    }
} else {
    $SessionCategory = '';
}
?>
<?php
if (isset($_POST['submittedSupervisorInformationForm'])) {
    $Supervisor_Username = mysqli_real_escape_string($conn,$_POST['Supervisor_Username']);
    $Supervisor_Password = mysqli_real_escape_string($conn,md5($_POST['Supervisor_Password']));
    $Sub_Department_Name = $_POST['Sub_Department_Name'];


    $query = "SELECT * from tbl_branches b, tbl_branch_employee be, tbl_employee e, tbl_privileges p
                where b.branch_id = be.branch_id and be.employee_id = e.employee_id
                and e.employee_id = p.employee_id and p.Given_Username = '{$Supervisor_Username}' and
		p.Given_Password  = '{$Supervisor_Password}' and p.Session_Master_Priveleges = 'yes';
            ";

    //DML excution select from..
    $result = mysqli_query($conn,$query);
    $no = mysqli_num_rows($result);
    if ($no > 0) {
        $row = mysqli_fetch_assoc($result);
        @session_start();
        $Authentication_Date_And_Time = date('Y-m-d H:i:s'); //authentication time to be recorded
        if ($SessionCategory == 'Radiology') {
            $_SESSION['Radiology_Supervisor'] = $row;
            $_SESSION['Sub_Department_Name'] = $Sub_Department_Name;

            //get sub department
            $sub_dep_result = mysqli_query($conn,"SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
            $row = mysqli_fetch_array($sub_dep_result);
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $_SESSION['Radiology_Sub_Department_Name'] = $Sub_Department_Name;
            $_SESSION['Radiology_Sub_Department_ID'] = $Sub_Department_ID;

            $Branch_ID = $_SESSION['Radiology_Supervisor']['Branch_ID'];

            $_SESSION['Radiology'] = $Sub_Department_Name;
            $_SESSION['Radiology_Sub_Department_ID'] = $Sub_Department_ID;
            $Authentication_Date_And_Time = date('Y-m-d H:i:s');
            authentication($_SESSION['Radiology_Supervisor']['Employee_ID'], $Authentication_Date_And_Time, $Sub_Department_Name);
            //audit($_SESSION['Radiology_Supervisor']['Employee_ID'],'Authentication',$Sub_Department_ID,$_SESSION['userinfo']['Employee_ID'],$Branch_ID,$Login_Date_And_Time=null,$Logout_Date_And_Time=null,$Authentication_Date_And_Time,$Sub_Department_Name);
            header("Location:./radiologyworkspage.php?RadiologyWorkPage=RadiologyWorkPageThisPage");
        }

        if ($SessionCategory == 'Rch') {
            $_SESSION['Rch_Supervisor'] = $row;
            $_SESSION['Sub_Department_Name'] = $Sub_Department_Name;

            //get sub department
            $sub_dep_result = mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
            $row = mysqli_fetch_array($sub_dep_result);
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $_SESSION['Rch_Sub_Department_Name'] = $Sub_Department_Name;
            $_SESSION['Rch_Sub_Department_ID'] = $Sub_Department_ID;

            $Branch_ID = $_SESSION['Rch_Supervisor']['Branch_ID'];

            $_SESSION['Rch'] = $Sub_Department_Name;
            $Authentication_Date_And_Time = date('Y-m-d H:i:s');
            authentication($_SESSION['Rch_Supervisor']['Employee_ID'], $Authentication_Date_And_Time, $Sub_Department_Name);
            header("Location:./rchworkspace.php?RchWorkPage=RchWorkPageThisPage");
        }

        //Laboratory
        if ($SessionCategory == 'Laboratory') {
            $_SESSION['Laboratory_Supervisor'] = $row;
            $_SESSION['Sub_Department_Name'] = $Sub_Department_Name;

            //get sub department
            $sub_dep_result = mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
            $row = mysqli_fetch_array($sub_dep_result);
            $Sub_Department_ID = $row['Sub_Department_ID'];

            $_SESSION['Laboratory_ID'] = $Sub_Department_ID;
            $_SESSION['Laboratory_Sub_Department_Name'] = $Sub_Department_Name;
            $_SESSION['Laboratory_Sub_Department_ID'] = $Sub_Department_ID;

            $Branch_ID = $_SESSION['Laboratory_Supervisor']['Branch_ID'];

            $_SESSION['Laboratory'] = $Sub_Department_Name;
            $Authentication_Date_And_Time = date('Y-m-d H:i:s');
            authentication($_SESSION['Laboratory_Supervisor']['Employee_ID'], $Authentication_Date_And_Time, $Sub_Department_Name);
            header("Location:./laboratory.php?LaboratoryWorkPage=LaboratoryWorkPageThisPage");
        }




        if ($SessionCategory == 'Hiv') {
            $_SESSION['Hiv_Supervisor'] = $row;
            $_SESSION['Hiv_Department_Name'] = $Sub_Department_Name;

            //get sub department
            $sub_dep_result = mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
            $row = mysqli_fetch_array($sub_dep_result);
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $_SESSION['Hiv_Sub_Department_Name'] = $Sub_Department_Name;
            $_SESSION['Hiv_Sub_Department_ID'] = $Sub_Department_ID;

            $Branch_ID = $_SESSION['Hiv_Supervisor']['Branch_ID'];

            $_SESSION['Hiv'] = $Sub_Department_Name;
            $Authentication_Date_And_Time = date('Y-m-d H:i:s');
            authentication($_SESSION['Hiv_Supervisor']['Employee_ID'], $Authentication_Date_And_Time, $Sub_Department_Name);
            header("Location:./hivworkspage.php?RchWorkPage=RchWorkPageThisPage");
        }

        if ($SessionCategory == 'Family_Planning') {
            $_SESSION['Family_Planning_Supervisor'] = $row;
            $_SESSION['Family_Planning_Department_Name'] = $Sub_Department_Name;

            //get sub department
            $sub_dep_result = mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
            $row = mysqli_fetch_array($sub_dep_result);
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $_SESSION['Family_Planning_Sub_Department_Name'] = $Sub_Department_Name;
            $_SESSION['Family_Planning_Sub_Department_ID'] = $Sub_Department_ID;

            $Branch_ID = $_SESSION['Family_Planning_Supervisor']['Branch_ID'];

            $_SESSION['Family_Planning'] = $Sub_Department_Name;
            $Authentication_Date_And_Time = date('Y-m-d H:i:s');
            authentication($_SESSION['Family_Planning_Supervisor']['Employee_ID'], $Authentication_Date_And_Time, $Sub_Department_Name);
            header("Location:./family_planningworkspage.php?Family_PlanningWorkPage=Family_PlanningWorkPageThisPage");
        }

        if ($SessionCategory == 'Dental') {
            $_SESSION['Dental_Supervisor'] = $row;
            $_SESSION['Dental_Department_Name'] = $Sub_Department_Name;

            //get sub department
            $sub_dep_result = mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
            $row = mysqli_fetch_array($sub_dep_result);
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $_SESSION['Dental_Sub_Department_Name'] = $Sub_Department_Name;
            $_SESSION['Dental_Sub_Department_ID'] = $Sub_Department_ID;

            $Branch_ID = $_SESSION['Dental_Supervisor']['Branch_ID'];

            $_SESSION['Dental'] = $Sub_Department_Name;
            $Authentication_Date_And_Time = date('Y-m-d H:i:s');
            authentication($_SESSION['Dental_Supervisor']['Employee_ID'], $Authentication_Date_And_Time, $Sub_Department_Name);
            header("Location:./dentalworkspage.php?DentalWorkPage=RchWorkPageThisPage");
        }

        if ($SessionCategory == 'Ear') {
            $_SESSION['Ear_Supervisor'] = $row;
            $_SESSION['Ear_Department_Name'] = $Sub_Department_Name;

            //get sub department
            $sub_dep_result = mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
            $row = mysqli_fetch_array($sub_dep_result);
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $_SESSION['Ear_Sub_Department_Name'] = $Sub_Department_Name;
            $_SESSION['Ear_Sub_Department_ID'] = $Sub_Department_ID;

            $Branch_ID = $_SESSION['Ear_Supervisor']['Branch_ID'];

            $_SESSION['Ear'] = $Sub_Department_Name;
            $Authentication_Date_And_Time = date('Y-m-d H:i:s');
            authentication($_SESSION['Ear_Supervisor']['Employee_ID'], $Authentication_Date_And_Time, $Sub_Department_Name);
            header("Location:./earworkspage.php?EarWorkPage=EarWorkPageThisPage");
        }

        if ($SessionCategory == 'Admission') {
            $_SESSION['Admission_Supervisor'] = $row;
            $_SESSION['Admission_Department_Name'] = $Sub_Department_Name;

            //get sub department
            $sub_dep_result = mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
            $row = mysqli_fetch_array($sub_dep_result);
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $_SESSION['Admission_ID'] = $Sub_Department_ID;
            $Branch_ID = $_SESSION['Admission_Supervisor']['Branch_ID'];
            $_SESSION['Admission'] = $Sub_Department_Name;
            $_SESSION['Admission_Sub_Department_Name'] = $Sub_Department_Name;
            $_SESSION['Admission_Sub_Department_ID'] = $Sub_Department_ID;

            $Authentication_Date_And_Time = date('Y-m-d H:i:s');
            authentication($_SESSION['Admission_Supervisor']['Employee_ID'], $Authentication_Date_And_Time, $Sub_Department_Name);
            if (isset($_GET['fromDoctorPage']) && $_GET['fromDoctorPage'] == 'fromDoctorPage') {
                header("Location:./admissionworkspage.php?AdmissionWorkPage=AdmissionWorkPageThisPage&fromDoctorPage=fromDoctorPage");
            } else {
                header("Location:./admissionworkspage.php?AdmissionWorkPage=AdmissionWorkPageThisPage");
            }
        }
        if ($SessionCategory == 'billing_works') {
            $_SESSION['Admission_Supervisor'] = $row;
            $_SESSION['Admission_Department_Name'] = $Sub_Department_Name;

            //get sub department
            $sub_dep_result = mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
            $row = mysqli_fetch_array($sub_dep_result);
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $_SESSION['Admission_ID'] = $Sub_Department_ID;
            $Branch_ID = $_SESSION['Admission_Supervisor']['Branch_ID'];
            $_SESSION['Admission'] = $Sub_Department_Name;
            $_SESSION['Admission_Sub_Department_Name'] = $Sub_Department_Name;
            $_SESSION['Admission_Sub_Department_ID'] = $Sub_Department_ID;

            $Authentication_Date_And_Time = date('Y-m-d H:i:s');
            authentication($_SESSION['Admission_Supervisor']['Employee_ID'], $Authentication_Date_And_Time, $Sub_Department_Name);
            if (isset($_GET['fromDoctorPage']) && $_GET['fromDoctorPage'] == 'fromDoctorPage') {
                header("Location:./billingwork.php");
            } else {
                header("Location:./billingwork.php");
            }
        }

        //maternity supervisor authentication
        if ($SessionCategory == 'Maternity') {
            $_SESSION['Maternity_Supervisor'] = $row;
            $_SESSION['Maternity_Department_Name'] = $Sub_Department_Name;

            //get sub department
            $sub_dep_result = mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
            $row = mysqli_fetch_array($sub_dep_result);
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $_SESSION['Maternity_Sub_Department_Name'] = $Sub_Department_Name;
            $_SESSION['Maternity_Sub_Department_ID'] = $Sub_Department_ID;

            $Branch_ID = $_SESSION['Maternity_Supervisor']['Branch_ID'];

            $_SESSION['Maternity'] = $Sub_Department_Name;
            $Authentication_Date_And_Time = date('Y-m-d H:i:s');
            authentication($_SESSION['Maternity_Supervisor']['Employee_ID'], $Authentication_Date_And_Time, $Sub_Department_Name);
            header("Location:./maternityworkspage.php?MaternityWorkPage=MaternityWorkPageThisPage");
        }

        //Dialysis supervisor auth
        if ($SessionCategory == 'Dialysis') {
            $_SESSION['Dialysis_Supervisor'] = $row;
            $_SESSION['Sub_Department_Name'] = $Sub_Department_Name;

            //get sub department
            $sub_dep_result = mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
            $row = mysqli_fetch_array($sub_dep_result);
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $_SESSION['Dialysis_Sub_Department_Name'] = $Sub_Department_Name;
            $_SESSION['Dialysis_Sub_Department_ID'] = $Sub_Department_ID;

            $Branch_ID = $_SESSION['Dialysis_Supervisor']['Branch_ID'];

            $_SESSION['Dialysis'] = $Sub_Department_Name;
            $Authentication_Date_And_Time = date('Y-m-d H:i:s');
            authentication($_SESSION['Dialysis_Supervisor']['Employee_ID'], $Authentication_Date_And_Time, $Sub_Department_Name);
            header("Location:./dialysisworkspage.php?DialysisWorkPage=DialysisWorkPageThisPage");
        }

        //physiotherapy supervisor auth
        if ($SessionCategory == 'Physiotherapy') {
            $_SESSION['Physiotherapy_Supervisor'] = $row;
            $_SESSION['Sub_Department_Name'] = $Sub_Department_Name;

            //get sub department
            $sub_dep_result = mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
            $row = mysqli_fetch_array($sub_dep_result);
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $_SESSION['Physiotherapy_Sub_Department_Name'] = $Sub_Department_Name;
            $_SESSION['Physiotherapy_Sub_Department_ID'] = $Sub_Department_ID;

            $Branch_ID = $_SESSION['Physiotherapy_Supervisor']['Branch_ID'];

            $_SESSION['Physiotherapy'] = $Sub_Department_Name;
            $Authentication_Date_And_Time = date('Y-m-d H:i:s');
            authentication($_SESSION['Physiotherapy_Supervisor']['Employee_ID'], $Authentication_Date_And_Time, $Sub_Department_Name);
            header("Location:./physiotherapyworkspage.php?PhysiotherapyWorkPage=PhysiotherapyWorkPageThisPage");
        }

        //Optical supervisor auth
        if ($SessionCategory == 'Optical') {
            $_SESSION['Optical_Supervisor'] = $row;
            $_SESSION['Sub_Department_Name'] = $Sub_Department_Name;

            //get sub department
            $sub_dep_result = mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
            $row = mysqli_fetch_array($sub_dep_result);
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $_SESSION['Optical_info'] = $Sub_Department_ID;
            $Branch_ID = $_SESSION['Optical_Supervisor']['Branch_ID'];
            $_SESSION['Optical_Sub_Department_Name'] = $Sub_Department_Name;
            $_SESSION['Optical_Sub_Department_ID'] = $Sub_Department_ID;

            $Authentication_Date_And_Time = date('Y-m-d H:i:s');
            authentication($_SESSION['Optical_Supervisor']['Employee_ID'], $Authentication_Date_And_Time, $Sub_Department_Name);
            header("Location:./opticalworkspage.php?OpticalWorks=OpticalWorksThisPage");
        }

        //Dressing supervisor auth
        if ($SessionCategory == 'Dressing') {
            $_SESSION['Dressing_Supervisor'] = $row;
            $_SESSION['Sub_Department_Name'] = $Sub_Department_Name;

            //get sub department
            $sub_dep_result = mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
            $row = mysqli_fetch_array($sub_dep_result);
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $_SESSION['All_Sub_Department_ID'] = $Sub_Department_ID;

            $Branch_ID = $_SESSION['Dressing_Supervisor']['Branch_ID'];

            $_SESSION['Dressing'] = $Sub_Department_Name;
            $Authentication_Date_And_Time = date('Y-m-d H:i:s');
            authentication($_SESSION['Dressing_Supervisor']['Employee_ID'], $Authentication_Date_And_Time, $Sub_Department_Name);
            header("Location:./dressingworkspage.php?DressingWorkPage=DressingWorkPageThisPage");
        }
        //Theater supervisor auth
        if ($SessionCategory == 'Surgery') {
            $_SESSION['Theater_Supervisor'] = $row;
            $_SESSION['Theater_Department_Name'] = $Sub_Department_Name;

            //get sub department
            $sub_dep_result = mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
            $row = mysqli_fetch_array($sub_dep_result);
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $_SESSION['Theater_Sub_Department_Name'] = $Sub_Department_Name;
            $_SESSION['Theater_Sub_Department_ID'] = $Sub_Department_ID;

            $Branch_ID = $_SESSION['Theater_Supervisor']['Branch_ID'];

            $_SESSION['Theater'] = $Sub_Department_Name;
            $Authentication_Date_And_Time = date('Y-m-d H:i:s');
            authentication($_SESSION['Theater_Supervisor']['Employee_ID'], $Authentication_Date_And_Time, $Sub_Department_Name);
            header("Location:./theaterworkspage.php?TheaterWorkPage=TheaterWorkPageThisPage");
        }

        //Cecap supervisor auth
        if ($SessionCategory == 'Cecap') {
            $_SESSION['Cecap_Supervisor'] = $row;
            $_SESSION['Sub_Department_Name'] = $Sub_Department_Name;
           
            //get sub department
            $sub_dep_result = mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
            $row = mysqli_fetch_array($sub_dep_result);
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $_SESSION['Cecap_Sub_Department_Name'] = $Sub_Department_Name;
            $_SESSION['Cecap_Sub_Department_ID'] = $Sub_Department_ID;

            $Branch_ID = $_SESSION['Cecap_Supervisor']['Branch_ID'];

            $_SESSION['Cecap'] = $Sub_Department_Name;
            $Authentication_Date_And_Time = date('Y-m-d H:i:s');
            authentication($_SESSION['Cecap_Supervisor']['Employee_ID'], $Authentication_Date_And_Time, $Sub_Department_Name);
            header("Location:./cecapworkspage.php?CecapWorkPage=CecapWorkPageThisPage");
        }


        //Procurement auth
        if ($SessionCategory == 'Procurement') {
            $_SESSION['Procurement_Supervisor'] = $row;
            $_SESSION['Sub_Department_Name'] = $Sub_Department_Name;

            //get sub department
            $sub_dep_result = mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
            $row = mysqli_fetch_array($sub_dep_result);
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $_SESSION['Procurement_Sub_Department_Name'] = $Sub_Department_Name;
            $_SESSION['Procurement_Sub_Department_ID'] = $Sub_Department_ID;

            $_SESSION['Procurement'] = $Sub_Department_Name;
            $_SESSION['Procurement_ID'] = $Sub_Department_ID;

            //get procurement level
            $get_level = mysqli_query($conn, "SELECT Approval_ID from tbl_approval_employee where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
            $num_get_level = mysqli_num_rows($get_level);
            if ($num_get_level > 0) {
                while ($dt = mysqli_fetch_array($get_level)) {
                    $_SESSION['Procurement_Autentication_Level'] = $dt['Approval_ID'];
                }
            } else {
                $_SESSION['Procurement_Autentication_Level'] = 100;
            }
            $Authentication_Date_And_Time = date('Y-m-d H:i:s');
            authentication($_SESSION['Procurement_Supervisor']['Employee_ID'], $Authentication_Date_And_Time, $Sub_Department_Name);
            header("Location: ./procurementworkspage.php?ProcurementWorkPage=ProcurementWorkPageThisPage");
        }


        //Procedure auth
        if ($SessionCategory == 'Procedure') {
            $_SESSION['Procedure_Supervisor'] = $row;
            $_SESSION['Sub_Department_Name'] = $Sub_Department_Name;

            //get sub department
            $sub_dep_result = mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
            $row = mysqli_fetch_array($sub_dep_result);
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $_SESSION['Procedure_Sub_Department_Name'] = $Sub_Department_Name;
            $_SESSION['Procedure_Sub_Department_ID'] = $Sub_Department_ID;

            $_SESSION['Procedure'] = $Sub_Department_Name;
            $_SESSION['Procedure_Sub_Department_ID'] = $Sub_Department_ID;
            $Authentication_Date_And_Time = date('Y-m-d H:i:s');
            authentication($_SESSION['Procedure_Supervisor']['Employee_ID'], $Authentication_Date_And_Time, $Sub_Department_Name);
            header("Location: ./Procedure.php?ProcurementWorkPage=ProcurementWorkPageThisPage");
        }
    } else {
        echo "<script type='text/javascript'>
                                alert('INVALID INFORMATION OR NO PRIVILEGES TO SUPPORT');
                            </script>";
    }
}
?>



<center>
    <table width=60%>
        <tr>
            <td>
                <center>
                    <fieldset>
                        <legend align="center"><b><?php echo str_replace('_', ' ', strtoupper($SessionCategory)); ?>
                                SUPERVISOR AUTHENTICATION</b></legend>
                        <table width='100%'>
                            <form action='#' method='POST' name='myForm' id='myForm' onsubmit="return validateForm();"
                                enctype="multipart/form-data">
                                <tr>
                                    <td width=30% style="text-align:right;">Supervisor Username</td>
                                    <td width=70%>
                                        <input type='text' name='Supervisor_Username' required='required' size=70
                                            id='Supervisor_Username' placeholder='Supervisor Username'
                                            autocomplete='off'>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;">Supervisor Password</td>
                                    <td width=70%>
                                        <input type='password' name='Supervisor_Password' required='required' size=70
                                            id='Supervisor_Password' placeholder='Supervisor Password'>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;">
                                        <?php if(isset($_GET['SessionCategory'])&&$_GET['SessionCategory']=="Admission"||$_GET['SessionCategory']=="billing_works"){
                                    ?>
                                        Select Ward
                                        <?php
                                    }else{?>
                                        Sub Department
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <!--<select name='Sub_Department_ID' id='Sub_Department_ID'>-->
                                        <select name='Sub_Department_Name' id='Sub_Department_Name' required='required'
                                            style="width:50%">

                                            <?php
                                    if (isset($_SESSION['userinfo']['Employee_ID'])) {
                                        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                                    }
                                    if (strtolower($SessionCategory) == 'procedure') {
                                        $select_sub_departments = mysqli_query($conn, "SELECT Sub_Department_Name from
															tbl_department dep, tbl_sub_department sdep,tbl_employee_sub_department ed
															where dep.department_id = sdep.department_id and
															sdep.Sub_Department_Status = 'active' and
															ed.Employee_ID = '$Employee_ID' and
															ed.Sub_Department_ID = sdep.Sub_Department_ID and
															$SessionCat");
                                    } else {
                                        if($_GET['SessionCategory']=="billing_works")$SessionCategory="Admission";
                                        $select_sub_departments = mysqli_query($conn, "SELECT Sub_Department_Name,sdep.Sub_Department_ID from
															tbl_department dep, tbl_sub_department sdep,tbl_employee_sub_department ed
															where dep.department_id = sdep.department_id and
															sdep.Sub_Department_Status = 'active' and
															ed.Employee_ID = '$Employee_ID' and
															ed.Sub_Department_ID = sdep.Sub_Department_ID and
															Department_Location = '" . str_replace('_', ' ', $SessionCategory) . "'
															");
                                    }
                                    $num = mysqli_num_rows($select_sub_departments);
                                    if ($num != 1) {
                                        echo "<option selected='selected'></option>";
                                    }
                                    while ($row = mysqli_fetch_array($select_sub_departments)) {
                                            $_SESSION['Sub_Department_ID'] = $row['Sub_Department_ID'];
                                        echo "<option>" . $row['Sub_Department_Name'] . "</option>";
                                    }
                                    ?>
                                        </select>
                                    </td>
                                </tr>
                                <?php 
                                if($SessionCategory=="Admission"||$SessionCategory=="billing_works"){ ?>
                                <tr>
                                    <td style="text-align:right">
                                        Select Your working Department
                                    </td>
                                    <td style="width:60%">
                                        <select id="bill_working_department" name="bill_working_department"
                                            required='required' style="width:50%">
                                            <option value=""></option>
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
                                <?php } ?>

                                <tr>
                                    <td colspan=2 style='text-align: center;'>
                                        <input type='submit' name='submit' id='submit'
                                            value='<?php echo 'ALLOW ' . strtoupper($_SESSION['userinfo']['Employee_Name']); ?>'
                                            class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value='CLEAR' class='art-button-green'>
                                        <a href='./index.php?TransactionAccessDenied=TransactionAccessDeniedThisPage'
                                            class='art-button-green'>CANCEL</a>
                                        <input type='hidden' name='submittedSupervisorInformationForm' value='true' />
                                    </td>
                                </tr>
                            </form>
                        </table>
                    </fieldset>
                </center>
            </td>
        </tr>
    </table>
</center>



<?php
include("./includes/footer.php");
?>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    //        $("#displayPatientList").dialog({autoOpen: false, width: '90%',height:'550', title: 'PATIENTS LIST', modal: true, position: 'middle'});
    /*$('.numberTests').dataTable({
        "bJQueryUI": true
    });*/
    $('select').select2();
});
</script>