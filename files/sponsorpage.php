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
            <legend align=center><b>SPONSOR CONFIGURATION</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px;'>
                    <a href='addnewcustomer.php?AddNewCustomer=AddNewCustomerThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Add New Customer
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px;'>
                    <a href='editcustomerlist.php?EditCustomer=EditCustomerThisForm'>
                        <button style='width: 100%; height: 100%'>
                            Edit Customer
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px;'>
                    <a href='addnewsponsor.php?AddNewSponsor=AddNewSponsorThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Add New Sponsor
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px;'>
                    <a href='editsponsorlist.php?EditSponsor=EditSponsorThisForm'>
                        <button style='width: 100%; height: 100%'>
                            Edit Sponsor
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px;' colspan="2">
                    <a href='sponsor_package_settings.php?SponsorPackage=SponsorPackageThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Sponsor Package Settings
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td colspan="2">

                    <?php
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                $sql_check_privilege_result=mysqli_query($conn,"SELECT Employee_ID FROM tbl_privileges WHERE Employee_ID='$Employee_ID' AND assign_sponsor_to_patient_automatically='yes'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_check_privilege_result)>0){
                     ?>
      <a href='patient_auto_change_sponsor.php'>
                        <button style='width: 100%; height: 100%'>
                            Assign Sponsor to Patient Automatically
                        </button>
                    </a>
                         <?php
                }else{
                 ?>
                    <a href='#' onclick="alert('Access Denied')">
                        <button style='width: 100%; height: 100%'>
                            Assign Sponsor to Patient Automatically
                        </button>
                    </a>
                     <?php
                }
                                            ?>

                </td>
            </tr>
        </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>
