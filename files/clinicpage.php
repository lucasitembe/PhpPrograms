<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='setupandconfiguration.php?BackToSetupAndConfiguration=BackTosetupAndConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>CLINIC CONFIGURATION</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px;' width=50%>
                    <a href='addclinicknature.php?AddNewClinic=AddNewClinicThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Add New Clinic Nature
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px;'>
                    <a href='editclinicnaturelist.php?EditClinicNature=EditClinicNatureThisForm'> 
                        <button style='width: 100%; height: 100%'>
                            Edit Clinic Nature
                        </button>
                    </a>
                </td> 
            </tr>
            <tr>
                <td style='text-align: center; height: 40px;' width=50%>
                    <a href='addclinic.php?AddNewClinic=AddNewClinicThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Add New Clinic
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px;'>
                    <a href='editcliniclist.php?EditClinic=EditClinicThisForm'> 
                        <button style='width: 100%; height: 100%'>
                            Edit Clinic
                        </button>
                    </a>
                </td> 
            </tr>
            <tr>
                <td style='text-align: center; height: 40px;' width=50%>
                    <a href='addclinicDepartment.php?AddNewClinicDepartment=AddNewClinicDepartmentThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Add New Clinic Department
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px;'>
                    <a href='editclinicdepartmentlist.php?EditClinicDepartment=EditClinicDepartmentThisForm'> 
                        <button style='width: 100%; height: 100%'>
                            Edit Clinic Department
                        </button>
                    </a>
                </td> 
            </tr>
            <tr class='hide'>
                <td style='text-align: center; height: 40px;' width=50% colspan="2">
                    <a href='add_new_clinic_location.php'>
                        <button style='width: 100%; height: 100%'>
                            Add New Clinic Location
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px;' width=50% colspan="2">
                    <a href='clinic_appointment_setup.php'>
                        <button style='width: 100%; height: 100%'>
                            Clinic Appointment Mandate Setup
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