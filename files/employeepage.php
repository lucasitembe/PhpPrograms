<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration']) || isset($_SESSION['userinfo']['Appointment_Works'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && isset($_SESSION['userinfo']['Appointment_Works'])!='yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || isset($_SESSION['userinfo']['Appointment_Works'])=='true'){ 
?>
    <a href='setupandconfiguration.php?BackToSetupAndConfiguration=BackTosetupAndConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>EMPLOYEE CONFIGURATION</b></legend>
        <center><table width = 80%>
            <tr>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <!--<a href='addnewitemcategory.php?AddNewItemCategory=AddNewItemCategoryThisPage'>-->
			<a href='addnewemployee.php?AddNewEmployee=AddNewEmployeeThisPage'>
			    <button style='width: 100%; height: 100%'>
				Add New Employee
			    </button>
			</a>
                    </a>
                </td>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href='editemployeelist.php?EditEmployeeList=EditEmployeeListThisForm'> 
			<button style='width: 100%; height: 100%'>
			    Edit Employee
			</button>
		    </a>
                </td>
		<td style='text-align: center; height: 40px; width: 25%;'>
		    <a href='listofemployeetoassignclinic.php?EditEmployee=EditEmployeeThisForm'> 
			<button style='width: 100%; height: 100%'>
			    Assign Doctor To Clinic
			</button>
		    </a>
		</td>
            </tr>
	    <tr>
                <td colspan=1 style='text-align: center; height: 40px; width: 25%;'>
                    <!--<a href='assignemployeesubdepartment.php?AssingEmployeeSubDepartment=AssignEmployeeSubDepartmentThisPage'>-->
		    <a href='listofemployeetoassigntosubdepartment.php?AssingEmployeeSubDepartment=AssignEmployeeSubDepartmentThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Assign Employee Sub Department
                        </button>
                    </a>
                </td>
		<td style='text-align: center; height: 40px; width: 25%;'>
			<a href='employeeAssignBranch.php?EditEmployee=EditEmployeeThisForm'> 
			<button style='width: 100%; height: 100%'>
				Assign Branch To Employee
			</button>
			</a>
		</td>
		<td style='text-align: center; height: 40px; width: 25%;'>
			<a href='viewincompleteregistration.php?IncompleteRegistredEmployee=IncompleteRegistredEmployeeThisForm'> 
			<button style='width: 100%; height: 100%'>
				View Incomplete Registered Employees
			</button>
			</a>
		</td>
            </tr> 
        </table>
        </center>
</fieldset><br/>

<?php
    include("./includes/footer.php");
?>