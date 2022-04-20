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
<a href='departmentpage.php?Department=DepartmentThisPage' class='art-button-green'>
    BACK
</a>


<fieldset>  
    <legend align=center><b>DEPARTMENT CONFIGURATION</b></legend>
    <center>
        <table width = 50%>
            <tr>
                <td>
                  <a href='add_idara.php'> 
                        <button style='width: 100%; height: 100%'>
                            Add Idara
                        </button>
                    </a>
              </td>
            <tr>
              <td>
                  <a href='configuration_btn_for_idara.php'> 
                        <button style='width: 100%; height: 100%'>
                            Attach Idara To Clinic
                        </button>
                    </a>
              </td>
          </tr>
            <tr>
              <td>
                  <a href='configuration_btn_for_idara.php'> 
                        <button style='width: 100%; height: 100%'>
                            Attach Category To Idara
                        </button>
                    </a>
              </td>
          </tr>
        </table>
    </center>
</fieldset>