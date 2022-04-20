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

<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<fieldset>
    <legend align=center><b>ATTACHMENT BUTTON</b></legend>
    <center>
        <table width = 40%>
            <tr>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href='attach_category_to_main_department.php'>
                        <button style='width: 100%; height: 100%'>
                            Attach Category To Main Department
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href='attach_sub_category_to_category.php'>
                        <button style='width: 100%; height: 100%'>
                            Attach Sub Category To Category
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
            <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href='attach_item_to_subcategory.php'>
                        <button style='width: 100%; height: 100%'>
                            Attach Item To Sub Category
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href='attach_clinic_location.php'>
                        <button style='width: 100%; height: 100%'>
                            Attach Clinic Location To Clinic
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
            <td  colspan=2 style='text-align: center; height: 40px; width: 25%;'>
                    <a href='atach_finance_department.php'>
                        <button style='width: 100%; height: 100%'>
                            Attach Finance Department To Clinic
                        </button>
                    </a>
                </td>
               
            </tr>
        </table>
    </center>
</fieldset>

