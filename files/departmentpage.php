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

<br/><br/><br/><br/><br/><br/><br/>
<fieldset>  
    <legend align=center><b>DEPARTMENT CONFIGURATION</b></legend>
    <center>
        <table width = 80%>
            <tr>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href='adddepartment.php?AddNewDepartment=AddNewDepartmentThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Add New Department
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <!--<a href='editdepartment.php?EditDepartment=EditDepartmentThisPage'>-->
                    <a href='editdepartmentlist.php?EditDepartmentList=EditDepartmentListThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Edit Department
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href='removedepartmentlist.php?RemoveDepartmentList=RemoveDepartmentListThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Disable / Enable Department
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href='addnewsubdepartment.php?AddNewSubDepartment=AddNewSubDepartmentThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Add New Sub Department
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href='editsubdepartmentlist.php?EditSubDepartmentList=EditSubDepartmentListThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Edit / Disable / Enable Sub Department
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px; width: 25%;'>
                  <a href='add_idara.php'> 
                        <button style='width: 100%; height: 100%'>
                            Add New Main Department
                        </button>
                    </a>
                </td>
            </tr> 
            <tr>
              <td>
                  <a href='main_department_category_item_attachment_configuration.php'> 
                        <button style='width: 100%; height: 100%'>
                            Attachment Configuration
                        </button>
                    </a>
              </td>
              <td>
                  <a href='privileges_sub_department_configuration.php'> 
                        <button style='width: 100%; height: 100%'>
                            Privileges Sub Department
                        </button>
                    </a>
              </td>
              <td>
                  <a href='add_finance_department.php'> 
                        <button style='width: 100%; height: 100%'>
                            Add Finance Department
                        </button>
                    </a>
              </td>
          </tr>
        </table>
    </center>
</fieldset>
<?php
    include("./includes/footer.php");
?>