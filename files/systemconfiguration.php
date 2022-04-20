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


<?php
    //select systemconfiguration based on branch
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }
    $select_system_configuration = mysqli_query($conn,"select * from tbl_system_configuration where Branch_ID = '$Branch_ID'");
    while($row = mysqli_fetch_array($select_system_configuration)){
        $Centralized_Collection = $row['Centralized_Collection'];
        $Departmental_Collection = $row['Departmental_Collection'];
    }
?>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>SYSTEM CONFIGURATION</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px;'>
                    <a href='revenuecenterconfigurationpage.php?RevenueCenterConfiguration=RevenueCenterConfigurationThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Revenue Center Configuration
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px;'>
                    <a href='receptionconfigurationpage.php?ReceptionConfiguration=ReceptionConfigurationThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Reception, Pharmacy & Revenue Center Configuration
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
              <td style='text-align: center; height: 40px;width: 25%;'>
                    <a href='multicurrencyconfiguration.php?MultiCurrencyConfiguration=MultiCurrencyConfigurationThisForm'> 
                    <button style='width: 100%; height: 100%'>
                        Multi Currency Configuration
                    </button>
                    </a>
         </td> <td style='text-align: center; height: 40px;width: 25%;' >
                    <a href='hospitalconsultation.php?hospitalconsultationConfigurations=hospitalconsultationConfigurationsThisForm'> 
                    <button style='width: 100%; height: 100%'>
                        Hospital Consultation setup
                    </button>
                    </a>
                </td>
            </tr> 
            <tr>
                <td style='text-align: center; height: 40px;width: 25%;' >
                    <a href='hosppitalsettingspage.php?hospitalConfigurations=hospitalConfigurationsThisForm'> 
                        <button style='width: 100%; height: 100%'>
                            Hospital Setting setup
                        </button>
                    </a>
                </td>
            </tr> 	
	    <tr>
              <td style='text-align: center; height: 40px;width: 25%;' >
                        <a href='AuthenticationConfigurationpage.php?ReceptionConfiguration=ReceptionConfigurationThisPage'>
                            <button style='width: 100%; height: 100%'>
                               Password (Authentication) Configuration
                            </button>
                        </a>
	     </td>
              <td style='text-align: center; height: 40px;width: 25%;' >
                  <?php 
                        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
                        $sql_check_privileges=mysqli_query($conn,"select can_change_system_parameters from tbl_privileges where Employee_ID='$Employee_ID' AND can_change_system_parameters='yes'") or die(mysqli_error($conn));
                         if(mysqli_num_rows($sql_check_privileges)>0){
                            ?>
                              <a href='systemparameter.php'>
                                <button style='width: 100%; height: 100%'>
                                    System Parameters
                                </button>
                            </a>   
                             <?php 
                         } else{
                             ?>
                                <a href='#' onclick="alert('Access Denied')">
                                    <button style='width: 100%; height: 100%'>
                                        System Parameters
                                    </button>
                                </a>
                             <?php
                         }
                          ?>
                  
	     </td>
            </tr>
            <tr>
                <td colspan="0">
                 <?php 
                        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
                        $sql_check_privileges=mysqli_query($conn,"select can_change_system_parameters from tbl_privileges where Employee_ID='$Employee_ID' AND can_change_system_parameters='yes'") or die(mysqli_error($conn));
                         if(mysqli_num_rows($sql_check_privileges)>0){
                            ?>
                              <a href='new_payment_method_configuration.php'>
                                <button style='width: 100%; height: 100%'>
                                   New Configuration Center
                                </button>
                            </a>   
                             <?php 
                         } else{
                             ?>
                                <a href='#' onclick="alert('Access Denied')">
                                    <button style='width: 100%; height: 100%'>
                                        New Configuration Center
                                    </button>
                                </a>
                             <?php
                         }
                          ?>
                   </td>
                   <td>
                       <a href='automatic_system_db_upgrade.php'>
                            <button style='width: 100%; height: 100%'>
                                Automatic System Database upgrade
                            </button>
                        </a>
                   </td>
            </tr>
	<tr>
	 <td style='text-align: center; height: 40px;width: 100%;' colspan='2'>
                  <?php 
                        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
                        $sql_check_privileges=mysqli_query($conn,"select can_change_system_parameters from tbl_privileges where Employee_ID='$Employee_ID' AND can_change_system_parameters='yes'") or die(mysqli_error($conn));
                         if(mysqli_num_rows($sql_check_privileges)>0){
                            ?>
                              <a href='api_configirations.php'>
                                <button style='width: 100%; height: 100%'>
                                    <b>API CONFIGURATIONS</b>
                                </button>
                            </a>   
                             <?php 
                         } else{
                             ?>
                                <a href='#' onclick="alert('Access Denied')">
                                    <button style='width: 100%; height: 100%'>
                                        <b>API CONFIGURATIONS</b>
                                    </button>
                                </a>
                             <?php
                         }

if(mysqli_num_rows($sql_check_privileges)>0){
                            ?>
                              <!-- <a href='hospital_bank_account.php'>
                                <button style='width: 100%; height: 100%'>
                                    <b>HOSPITAL BANK ACCOUNT</b>
                                </button>
                            </a>    -->
                             <?php 
                         } else{
                             ?>
                                <a href='#' onclick="alert('Access Denied')">
                                    <button style='width: 100%; height: 100%'>
                                        <b>HOSPITAL BANK ACCOUNT</b>
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
