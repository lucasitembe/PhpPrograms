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
        $SMTP_Host = '';
        $SMTP_Security = '';
		$SMTP_Port = '';
        $Username = '';
		$Email_subject ='';
        $Email_Body = '';
	    $Carbon_Copy = '';
		$Blind_Carbon_Copy = '';
		
	if(isset($_POST['submittedAddNewSMTPsettingsForm'])){
	  //echo 'Posted';
		$SMTP_Host = mysqli_real_escape_string($conn,$_POST['SMTP_Host']);
        $SMTP_Security = mysqli_real_escape_string($conn,$_POST['SMTP_Security']);
		$SMTP_Port = mysqli_real_escape_string($conn,$_POST['SMTP_Port']);
        $Username = mysqli_real_escape_string($conn,$_POST['Username']);
		$Password = mysqli_real_escape_string($conn,$_POST['Password']);
        $Confirm_password = mysqli_real_escape_string($conn,$_POST['Confirm_password']);
		$Email_subject = mysqli_real_escape_string($conn,$_POST['Email_subject']);
        $Email_Body = mysqli_real_escape_string($conn,$_POST['Email_Body']);
	    $Carbon_Copy = mysqli_real_escape_string($conn,$_POST['Carbon_Copy']);
		$Blind_Carbon_Copy = mysqli_real_escape_string($conn,$_POST['Blind_Carbon_Copy']);
       
	   $q=mysqli_query($conn,"SELECT * FROM tbl_email_settings") or die(mysqli_error($conn));
      
      $exist=mysqli_num_rows($q);
	 if($exist){
	    echo "<script type='text/javascript'>
			    alert('SETTINGS ALREADY MADE.TO MAKE CHANGES CLICK OK');
				window.location='addnewsettings.php?Addsettings=AddsettingslThisPage';
			    </script>";
        exit;   
     }	 
        if( $Password != $Confirm_password ){
		   echo "<script type='text/javascript'>
			    alert('PASSWORD MISMATCH');
			    </script>";
            //exit;				
		}else{        
                
		$sql = "insert into tbl_email_settings(
                 SMTP_Host,SMTP_Security,SMTP_Port,Username,Password,Email_subject,Email_Body,Carbon_Copy,Blind_Carbon_Copy,Date_Set,Created_By)

                            values('$SMTP_Host','$SMTP_Security','$SMTP_Port','$Username','$Password','$Email_subject','$Email_Body','$Carbon_Copy','$Blind_Carbon_Copy',NOW(),
                                        '".$_SESSION['userinfo']['Employee_ID']."')";
		$q=mysqli_query($conn,$sql) or die(mysqli_error($conn));
		if($q){ 
			 
                    echo "<script type='text/javascript'>
			    alert('SMPT SETTINGS ADDED SUCCESSFUL');
			    </script>"; 
		}
		}
	}
	
	//Update
	
	if(isset($_POST['submittedEditedSMTPsettingsForm'])){
	  //echo 'Posted';
		$SMTP_Host = mysqli_real_escape_string($conn,$_POST['SMTP_Host']);
        $SMTP_Security = mysqli_real_escape_string($conn,$_POST['SMTP_Security']);
		$SMTP_Port = mysqli_real_escape_string($conn,$_POST['SMTP_Port']);
        $Username = mysqli_real_escape_string($conn,$_POST['Username']);
		$Password = mysqli_real_escape_string($conn,$_POST['Password']);
        $Confirm_password = mysqli_real_escape_string($conn,$_POST['Confirm_password']);
		$Email_subject = mysqli_real_escape_string($conn,$_POST['Email_subject']);
        $Email_Body = mysqli_real_escape_string($conn,$_POST['Email_Body']);
	    $Carbon_Copy = mysqli_real_escape_string($conn,$_POST['Carbon_Copy']);
		$Blind_Carbon_Copy = mysqli_real_escape_string($conn,$_POST['Blind_Carbon_Copy']);
       
        
        if( $Password != $Confirm_password ){
		   echo "<script type='text/javascript'>
			    alert('PASSWORD MISMATCH');
			    </script>";
            //exit;				
		}else{        
                
		$sql = "UPDATE tbl_email_settings SET
                 SMTP_Host='$SMTP_Host',SMTP_Security='$SMTP_Security',SMTP_Port='$SMTP_Port',Username='$Username',Password='$Password',Email_subject='$Email_subject',Email_Body='$Email_Body',Carbon_Copy='$Carbon_Copy',Blind_Carbon_Copy='$Blind_Carbon_Copy',Date_Set=NOW(),Created_By='".$_SESSION['userinfo']['Employee_ID']."'";
		$q=mysqli_query($conn,$sql) or die(mysqli_error($conn));
		if($q){ 
			 
                    echo "<script type='text/javascript'>
			    alert('SMPT SETTINGS UPDATED SUCCESSFUL');
			    </script>"; 
		}
		}
	}
	
	$q=mysqli_query($conn,"SELECT * FROM tbl_email_settings") or die(mysqli_error($conn));
	$row=mysqli_fetch_assoc($q);
	 $exist=mysqli_num_rows($q);
	 if ($exist >0){
	    $SMTP_Host = $row['SMTP_Host'];
        $SMTP_Security = $row['SMTP_Security'];
		$SMTP_Port = $row['SMTP_Port'];
        $Username = $row['Username'];
		$Email_subject = $row['Email_subject'];
        $Email_Body = $row['Email_Body'];
	    $Carbon_Copy = $row['Carbon_Copy'];
		$Blind_Carbon_Copy = $row['Blind_Carbon_Copy'];
	}
	
	
?>

<br/><br/>
<center>
    <table width=60%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>SMTP CONFIGURATIONS</b></legend>
					<?php
					  if($exist==0){
					?>
                    <table>
                        <form action='' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                           
                                <tr>
											<td width=40% style='text-align: right;'><b>SMTP Host</b></td>
											<td width=80%><input type='text' name='SMTP_Host' required='required' size=70 id='SMTP_Host' placeholder='smtp.gmail.com' value='<?php echo $SMTP_Host ?>'></td>
										</tr>
										<tr>
											<td width=40% style='text-align: right;'><b>SMTP Security</b></td>
											<td width=80%>
											<input type="text" name='SMTP_Security' id='SMTP_Security' placeholder='ssl , tsl' required="required" value='<?php echo $SMTP_Security ?>'/>
											</td>
										</tr>
										<tr>
											<td width=40% style='text-align: right;'><b>SMTP Port</b></td>
											<td width=80%><input type='text' name='SMTP_Port' required='required' size=70 id='SMTP_Port' placeholder='Enter SMTP Port' value='<?php echo $SMTP_Port ?>'></td>
										</tr>
										<tr>
											<td width=40% style='text-align: right;'><b>Username</b></td>
											<td width=80%>
											<input type="text" name='Username' id='Username' placeholder='Enter Username' required="required"  value='<?php echo $Username ?>'/>
											</td>
										</tr>
										<tr>
											<td width=40% style='text-align: right;'><b>Password</b></td>
											<td width=80%>
											<input type="password" name='Password' id='Password' placeholder='Enter Password' required="required" value='' />
											</td>
										</tr>
										<tr>
											<td width=40% style='text-align: right;'><b>Confirm password</b></td>
											<td width=80%>
											<input type="password" name='Confirm_password' id='Confirm_password' placeholder='Confirm password' required="required" value='' />
											</td>
										</tr>
										<tr>
											<td width=40% style='text-align: right;'><b>Email subject</b></td>
											<td width=80%>
											<input type="text" name='Email_subject' id='Email_subject' placeholder='Enter Email subject' required="required" value='<?php echo $Email_subject ?>' />
											</td>
										</tr>
										<tr>
											<td width=40% style='text-align: right;'><b>Email Body</b></td>
											<td width=80%>
											 <textarea name='Email_Body' id='Email_Body' placeholder='Enter Email Body' rows="5" cols="8"><?php echo $Email_Body ?></textarea>
											
											</td>
										</tr>
										<tr>
											<td width=40% style='text-align: right;'><b>Carbon Copy (CC)</b></td>
											<td width=80%>
											<input type="text" name='Carbon_Copy' id='Carbon_Copy' placeholder='ally@gmail.com;born@ymail.com;castro@live.com' value='<?php echo $Carbon_Copy ?>' />
											</td>
										</tr>
										<tr>
											<td width=40% style='text-align: right;'><b>Blind Carbon Copy (BCC)</b></td>
											<td width=80%>
											 <input type="text" name='Blind_Carbon_Copy' id='Blind_Carbon_Copy' placeholder='ally@gmail.com;born@ymail.com;castro@live.com' value='<?php echo $Blind_Carbon_Copy ?>'  />
											
											</td>
										</tr>
								<tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedAddNewSMTPsettingsForm' value='true'/> 
                                    </td>
                                </tr>
                        </form></table>
						<?php
						}
					    else{
						  ?>
						    <table>
								<form action='' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
								   
										<tr>
											<td width=40% style='text-align: right;'><b>SMTP Host</b></td>
											<td width=80%><input type='text' name='SMTP_Host' required='required' size=70 id='SMTP_Host' placeholder='smtp.gmail.com' value='<?php echo $SMTP_Host ?>'></td>
										</tr>
										<tr>
											<td width=40% style='text-align: right;'><b>SMTP Security</b></td>
											<td width=80%>
											<input type="text" name='SMTP_Security' id='SMTP_Security' placeholder='ssl , tsl' required="required" value='<?php echo $SMTP_Security ?>'/>
											</td>
										</tr>
										<tr>
											<td width=40% style='text-align: right;'><b>SMTP Port</b></td>
											<td width=80%><input type='text' name='SMTP_Port' required='required' size=70 id='SMTP_Port' placeholder='Enter SMTP Port' value='<?php echo $SMTP_Port ?>'></td>
										</tr>
										<tr>
											<td width=40% style='text-align: right;'><b>Username</b></td>
											<td width=80%>
											<input type="text" name='Username' id='Username' placeholder='Enter Username' required="required"  value='<?php echo $Username ?>'/>
											</td>
										</tr>
										<tr>
											<td width=40% style='text-align: right;'><b>Password</b></td>
											<td width=80%>
											<input type="password" name='Password' id='Password' placeholder='Enter Password' required="required" value='' />
											</td>
										</tr>
										<tr>
											<td width=40% style='text-align: right;'><b>Confirm password</b></td>
											<td width=80%>
											<input type="password" name='Confirm_password' id='Confirm_password' placeholder='Confirm password' required="required" value='' />
											</td>
										</tr>
										<tr>
											<td width=40% style='text-align: right;'><b>Email subject</b></td>
											<td width=80%>
											<input type="text" name='Email_subject' id='Email_subject' placeholder='Enter Email subject' required="required" value='<?php echo $Email_subject ?>' />
											</td>
										</tr>
										<tr>
											<td width=40% style='text-align: right;'><b>Email Body</b></td>
											<td width=80%>
											 <textarea name='Email_Body' id='Email_Body' placeholder='Enter Email Body' rows="5" cols="8"><?php echo $Email_Body ?></textarea>
											
											</td>
										</tr>
										<tr>
											<td width=40% style='text-align: right;'><b>Carbon Copy (CC)</b></td>
											<td width=80%>
											<input type="text" name='Carbon_Copy' id='Carbon_Copy' placeholder='ally@gmail.com;born@ymail.com;castro@live.com' value='<?php echo $Carbon_Copy ?>' />
											</td>
										</tr>
										<tr>
											<td width=40% style='text-align: right;'><b>Blind Carbon Copy (BCC)</b></td>
											<td width=80%>
											 <input type="text" name='Blind_Carbon_Copy' id='Blind_Carbon_Copy' placeholder='ally@gmail.com;born@ymail.com;castro@live.com' value='<?php echo $Blind_Carbon_Copy ?>'  />
											
											</td>
										</tr>
										<tr>
											<td colspan=2 style='text-align: right;'>
												<input type='submit' name='submit' id='submit' value='   UPDATE   ' class='art-button-green'>
												<input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
												<input type='hidden' name='submittedEditedSMTPsettingsForm' value='true'/> 
											</td>
										</tr>
								</form>
							</table>
						  <?php
						}
					?>
            </fieldset>
        </center></td></tr></table>
</center>


<?php
    include("./includes/footer.php");
?>