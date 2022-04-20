<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
 <a href='emailpage.php?emailConfigurations=emailConfigurationsThisForm' class='art-button-green'> 
        BACK
    </a>

<br/><br/>

<?php
	if(isset($_POST['submittedAddNewEmailForm'])){
		        $Recepient_Name = mysqli_real_escape_string($conn,$_POST['Recepient_Name']);
                $Recepient_Email_Address = mysqli_real_escape_string($conn,$_POST['Recepient_Email_Address']);
                
                
		$sql = "insert into tbl_email_recepients(
                            Recepient_Name,Recepient_Email,
                            Date_Created,Created_By)

                            values('$Recepient_Name','$Recepient_Email_Address',NOW(),
                                        '".$_SESSION['userinfo']['Employee_ID']."')";
		
		if(!mysqli_query($conn,$sql)){ 
			$error = '1062yes';
			    if(mysql_errno()."yes" == $error){ 
                            ?>
                            
                            <script type='text/javascript'>
                                alert('RECEPIENT EMAIL ALREADY EXISTS! \nTRY ANOTHER NEW EMAIL');
                                </script>
                            
                        <?php
			}
		}
		else { 
                    echo "<script type='text/javascript'>
			    alert('RECEPIENT ADDED SUCCESSFUL');
			    </script>"; 
		}
	}
?>

<br/><br/>
<center>
    <table width=60%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>ADD NEW RECEPIENT</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                           
                                <tr>
                                    <td width=40% style='text-align: right;'><b>Recepient Name</b></td>
                                    <td width=80%><input type='text' name='Recepient_Name' required='required' size=70 id='Recepient_Name' placeholder='Enter Recepient Name'></td>
                                </tr>
                                <tr>
                                    <td width=40% style='text-align: right;'><b>Email Address</b></td>
                                    <td width=80%>
									<input type="email" name='Recepient_Email_Address' id='Recepient_Email_Address' placeholder='Enter Email Address' required="required" />
									</td>
                                </tr>
								<tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedAddNewEmailForm' value='true'/> 
                                    </td>
                                </tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>


<?php
    include("./includes/footer.php");
?>