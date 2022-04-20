<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes' && $_SESSION['userinfo']['Pharmacy'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>

<?php
    if(isset($_SESSION['userinfo'])){
	if(isset($_GET['Section']) && $_GET['Section'] == 'Pharmacy'){
?>
    <a href='pharmacyworks.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage' class='art-button-green'>
        BACK
    </a>
<?php  }else if(isset($_GET['Section']) && $_GET['Section'] == 'Storage'){  ?>
	<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>
        BACK
    </a>
<?php }else if(isset($_GET['Section']) && $_GET['Section'] == 'Laboratory'){ ?>
	<a href='laboratory_setup.php?LaboratorySetup=LaboratorySetupThisPage' class='art-button-green'>
        BACK
    </a>
<?php }else if(isset($_GET['Section']) && $_GET['Section'] == 'Radiology'){  ?>
    <a href='radiologyworkspage.php?RadiologyWorks=RadiologyWorksThisPage' class='art-button-green'>
        BACK
    </a>
<?php }else if(isset($_GET['Section']) && $_GET['Section'] == 'Doctor'){  ?>
    <a href='doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage' class='art-button-green'>
        BACK
    </a>
<?php }else if(strtolower($_SESSION['userinfo']['Setup_And_Configuration']) == 'yes'){  ?>
    <a href='setupandconfiguration.php?BackToSetupAndConfiguration=BackTosetupAndConfigurationThisPa' class='art-button-green'>
        BACK
    </a>
<?php } } 

 $message_fedbck="";
if(isset($_POST['login_btn'])){
    $username=mysqli_real_escape_string($conn,$_POST['username']);
    $password=mysqli_real_escape_string($conn,$_POST['password']);
    $password=MD5($password);
    //check for corect username and password
    $sql_check_for_correct_username_and_password_result=mysqli_query($conn,"SELECT Employee_ID FROM tbl_privileges WHERE Given_Username='$username' AND Given_Password='$password'") or die(mysqli_error($conn));
    $message_fedbck="";
    if(mysqli_num_rows($sql_check_for_correct_username_and_password_result)>0){
        $Employee_ID=mysqli_fetch_assoc($sql_check_for_correct_username_and_password_result)['Employee_ID'];
        //check if this account is active
        $sql_check_if_this_account_is_active_result=mysqli_query($conn,"SELECT Employee_ID FROM tbl_employee WHERE Employee_ID='$Employee_ID' AND Account_Status='active'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_check_if_this_account_is_active_result)>0){
            //logs the user who login
            //$Employee_ID=mysqli_fetch_assoc($sql_check_for_correct_username_and_password_result)['Employee_ID'];
            mysqli_query($conn,"INSERT INTO tbl_items_configuration_login_logs(Employee_ID,login_date) VALUES('$Employee_ID',NOW())") or die(mysqli_error($conn));
            header("location:itemsconfiguration.php");
        }else{
            $message_fedbck="Authentication fail!Wrong Username or password";
        }
    }else{
         $message_fedbck="Wrong Username or password";
    }
}
?>
<br/>
<br/>
<br/>
        <fieldset>
            <legend align="center" ><b>AUTHENTICATION FOR AUTHORIZED USERS</b></legend>
            <center> 
               <p><b>Note:All action done under this section are recorded</b></p>
               <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <form action="" method="POST" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4">Username</label>
                                <div class="col-md-8"><input type="text"required="" name="username" class="form-control" placeholder="Enter Username"/></div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Password</label>
                                <div class="col-md-8"><input type="password" name="password"required="" class="form-control" placeholder="Enter Username"/></div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12"><b style="color:red"><?= $message_fedbck ?></b><input type="submit" name="login_btn"value="LOGIN" class="art-button-green pull-right" /></div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4"></div>
                </div>
            </center>
        </fieldset>  
<?php
    include("./includes/footer.php");
?>