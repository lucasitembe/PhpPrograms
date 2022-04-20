<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
   /* if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
    }else{
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }*/
     
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Appointment_Works']=='yes'){ 
?>
    <a href='index.php?Bashboard=BashboardThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>EMPLOYEE MANAGEMENT</b></legend>
        <center><table width = 80%>
            <tr>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <!--<a href='addnewitemcategory.php?AddNewItemCategory=AddNewItemCategoryThisPage'>-->
            <a href='addnewemployee.php?AddNewEmployee=AddNewEmployeeThisPage&HRWork=true'>
                <button style='width: 100%; height: 100%'>
                Add New Employee
                </button>
            </a>
                    </a>
                </td>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href='editemployeelist.php?EditEmployeeList=EditEmployeeListThisForm&HRWork=true'> 
            <button style='width: 100%; height: 100%'>
                Edit Employee
            </button>
            </a>
                </td>
        <td style='text-align: center; height: 40px; width: 25%;'>
            <a href='listofemployeetoassignclinic.php?EditEmployee=EditEmployeeThisForm&HRWork=true'> 
            <button style='width: 100%; height: 100%'>
                Assign Doctor To Clinic
            </button>
            </a>
        </td>
            </tr>
        <tr>
                <td colspan=1 style='text-align: center; height: 40px; width: 25%;'>
                    <!--<a href='assignemployeesubdepartment.php?AssingEmployeeSubDepartment=AssignEmployeeSubDepartmentThisPage'>-->
            <a href='listofemployeetoassigntosubdepartment.php?AssingEmployeeSubDepartment=AssignEmployeeSubDepartmentThisPage&HRWork=true'>
                        <button style='width: 100%; height: 100%'>
                            Assign Employee Sub Department
                        </button>
                    </a>
                </td>
        <td style='text-align: center; height: 40px; width: 25%;'>
            <a href='employeeAssignBranch.php?EditEmployee=EditEmployeeThisForm&HRWork=true'> 
            <button style='width: 100%; height: 100%'>
                Assign Branch To Employee
            </button>
            </a>
        </td>
        <td style='text-align: center; height: 40px; width: 25%;'>
            <a href='viewincompleteregistration.php?IncompleteRegistredEmployee=IncompleteRegistredEmployeeThisForm&HRWork=true'> 
            <button style='width: 100%; height: 100%'>
                View Incomplete Registered Employees
            </button>
            </a>
        </td>
            </tr> 
            
            <tr><td>
               <a href='employee_approval_configuration.php'> 
                    <button style='width: 100%; height: 100%'>
                        Approval Configuration 
                    </button>
                </a></td> 
                <td>
                    <?php 
                        $emp_id=$_SESSION['userinfo']['Employee_ID'];
                        $coc_url = "/index.php/auth/authenticate_user_from_ehms/".$emp_id;
                        $sql_select_hr_url_result=mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='HrpUrl'") or die(mysqli_error($conn));
                    if(mysqli_num_rows($sql_select_hr_url_result)>0){
                        $url_hr=mysqli_fetch_assoc($sql_select_hr_url_result)['configvalue'].$coc_url;
                    }else{
                        $url_hr='#';
                    }
                        ?>
                    <a href='<?= $url_hr ?>' target='_blank'> 
                         <button style='width: 100%; height: 100%'>
                             Human Resource Management
                         </button>
                     </a>
                </td>
                <td>
                    <a href='assign_employee_subdepartment_new.php'> 
                         <button style='width: 100%; height: 100%'>
                            Assign Employee Sub Department <b>New</b>  
                         </button>
                     </a>
                </td>
<!--                <td>
                     <a href='assign_nurse_pharmacy_automatic.php' target='_blank'> 
                         <button style='width: 100%; height: 100%'>
                             temporary assignment  
                         </button>
                     </a>
                </td>-->
            </tr>
        </table>
        </center>
</fieldset><br/>

<?php
    include("./includes/footer.php");
?>
