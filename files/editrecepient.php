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
 <a href='editemailrecepientlist.php?EditReceptient=EditEmailThisPage' class='art-button-green'> 
        BACK
    </a>

<br/><br/>

<?php
    $Recepient_ID = $_GET['Recepient_ID'];

	if(isset($_POST['submittedEditedRecepientForm'])){
		        $Recepient_Name = mysqli_real_escape_string($conn,$_POST['Recepient_Name']);
                $Recepient_Email_Address = mysqli_real_escape_string($conn,$_POST['Recepient_Email_Address']);
                
                
		$sql = "UPDATE tbl_email_recepients
                           SET Recepient_Name='$Recepient_Name',Recepient_Email='$Recepient_Email_Address',
                            Date_Created=NOW(),Created_By='".$_SESSION['userinfo']['Employee_ID']."' WHERE Recepient_ID= '$Recepient_ID'";
		if(mysqli_query($conn,$sql)){
		echo "<script type='text/javascript'>
			alert('RECEPIENT UPDATED SUCCESSFULLY');
			document.location = 'editemailrecepientlist.php?EditReceptient=EditEmailThisPage'
			</script>"; 
	    }
	    else {
		echo "<script type='text/javascript'>
			alert('THERE WAS A PROBLEM WHILE UPDATING');
			</script>";
	    }
	}
	
	if(isset($_GET['Recepient_ID'])){
	$Recepient_qr = "SELECT * FROM tbl_email_recepients WHERE Recepient_ID= '$Recepient_ID'";
	$Recepient_result = mysqli_query($conn,$Recepient_qr);
	$Recepient_row = mysqli_fetch_assoc($Recepient_result);
	$Recepient_Name = $Recepient_row['Recepient_Name'];
	$Recepient_Email_Address = $Recepient_row['Recepient_Email'];
	
    }else{
	$Recepient_Name ='';
	$Recepient_Email_Address ='';
    }
?>

<br/><br/>
<center>
    <table width=60%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>EDIT NEW RECEPIENT</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                           
                                <tr>
                                    <td width=40% style='text-align: right;'><b>Recepient Name</b></td>
                                    <td width=80%><input type='text' name='Recepient_Name' required='required' size=70 id='Recepient_Name' placeholder='Enter Recepient Name' value='<?php echo $Recepient_Name ?>'></td>
                                </tr>
                                <tr>
                                    <td width=40% style='text-align: right;'><b>Email Address</b></td>
                                    <td width=80%>
									<input type="email" name='Recepient_Email_Address' id='Recepient_Email_Address' placeholder='Enter Email Address' required="required" value='<?php echo $Recepient_Email_Address ?>'/>
									</td>
                                </tr>
								<tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedEditedRecepientForm' value='true'/> 
                                    </td>
                                </tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>


<?php
    include("./includes/footer.php");
?>