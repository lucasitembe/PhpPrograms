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
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='systemconfiguration.php?SystemConfiguration=SystemConfigurationThisPage' class='art-button-green'>
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
    $select_system_configuration = mysqli_query($conn,"select Expire_Password_Days,Change_password_first_login,Allow_login_failure_Count,minimum_password_length,alphanumeric_password from tbl_system_configuration where Branch_ID = '$Branch_ID'");
    while($row = mysqli_fetch_array($select_system_configuration)){
        $Expire_Password_Days = $row['Expire_Password_Days'];
        $Allow_login_failure_Count = $row['Allow_login_failure_Count'];
        $minimum_password_length=$row['minimum_password_length'];
        $alphanumeric_password=$row['alphanumeric_password'];
        $Change_password_first=$row['Change_password_first_login'];
    }
?>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>AUTHENTICATION CONFIGURATION</b></legend>
        <center><br/><table width = "100%">
            <tr>
                <td style='text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <label>Password expires after how many days</label>
                    <input type="text" id="numberofDays" style="width: 150px;padding-left: 5px" value="<?php echo $Expire_Password_Days;?>">
                   
                </td>
                
                
                <td style='text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <?php echo 'Allow account deactivation after login fails more than 3 times'; ?>
                    
                    <?php
                     if($Allow_login_failure_Count=='yes'){
                        echo "<input type='checkbox' name='Reception_Configuration' id='account_deactivation' checked=true>";  
                     }  else {
                        
                        echo "<input type='checkbox' name='Reception_Configuration' id='account_deactivation'>"; 
                     }
                    ?>
                    
                   
                </td>

            
                <td style='text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <label>Minimum password length</label>
                    <input type="text" id="minimumpassword" style="width: 150px;padding-left: 5px" value="<?php echo $minimum_password_length;?>">
                   
                </td>
                
                 <td style='text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <?php echo 'Password must be alphanumeric'; ?>
                    
                    <?php
                     if($alphanumeric_password=='yes'){
                        echo "<input type='checkbox' name='alphanumeric' id='alphanumeric' checked=true>";  
                     }  else {
                        
                        echo "<input type='checkbox' name='alphanumeric' id='alphanumeric'>"; 
                     }
                    ?>
                    
                   
                </td>
                
                 <td style='text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <?php echo 'Change password for first login'; ?>
                    
                    <?php
                     if($Change_password_first=='yes'){
                        echo "<input type='checkbox' name='firstLoginChange' id='firstLoginChange' checked=true>";  
                     }  else {
                        
                        echo "<input type='checkbox' name='firstLoginChange' id='firstLoginChange'>"; 
                     }
                    ?>
                    
                   
                </td>
                
            <td style='text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <input type="hidden" value="<?php echo $Branch_ID;?>" id="branch">
                    <input type="button" value="Save Changes" id="SaveUpdates" class="art-button-green">
            </td>
            
            </tr>
        </table>
        </center>
</fieldset><br/>

<?php
    include("./includes/footer.php");
?>


<script>
    $("#numberofDays").keydown(function(event) {
		// Allow only backspace and delete
		if ( event.keyCode == 46 || event.keyCode == 8 ) {
			// let it happen, don't do anything
		}
		else {
			// Ensure that it is a number and stop the keypress
			if (event.keyCode < 48 || event.keyCode > 57 ) {
				event.preventDefault();	
			}	
		}
	});
    
    $('#SaveUpdates').on('click',function(){
      var minimumpassword=$('#minimumpassword').val();
      var branch=$('#branch').val();
      var numberofDays=$('#numberofDays').val();
      var alphanumeric;
      var account_deactivation;
      var firstLoginChange;
       if($('#account_deactivation').is(':checked')){
           account_deactivation='yes';
       }else{
           account_deactivation='no';
       }
       
       if($('#alphanumeric').is(':checked')){
           alphanumeric='yes';
       }else{
           alphanumeric='no';
       }
       
       if($('#firstLoginChange').is(':checked')){
           firstLoginChange='yes';
       }else{
           firstLoginChange='no';
       }
	   
       $.ajax({
        type:'POST',
        url:'requests/Update_authentication_setup.php',
        data:'action=update&branch='+branch+'&numberofDays='+numberofDays+'&account_deactivation='+account_deactivation+'&minimumpassword='+minimumpassword+'&alphanumeric='+alphanumeric+'&firstLoginChange='+firstLoginChange,
        cache:false,
        success:function(e){
            alert(e);
        }
        });
      
    });
</script>