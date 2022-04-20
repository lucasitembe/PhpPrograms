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
    <a href='clinicpage.php?ClinicConfiguration=ClinicConfigurationThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>

<?php
	if(isset($_POST['buttonAddClinicNature'])){
		
		$Clinic_Nature_Name=trim(mysqli_real_escape_string($conn,$_POST['Clinic_Nature_Name']));
		$Clinic_Nature_Description=trim(mysqli_real_escape_string($conn,$_POST['Clinic_Nature_Description']));

		if(empty($Clinic_Nature_Name) || empty($Clinic_Nature_Description)){
			echo '<script>
					    alert("Clinic Nature and Description must be filled!");
					    </script>';	
		}else{
			$sql = "insert into tbl_clinic_nature(Clinic_Nature_Name,Clinic_Nature_Description)
		    values ('$Clinic_Nature_Name','$Clinic_Nature_Description')";
	    
	    if(!mysqli_query($conn,$sql)){
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){ 
				    echo '<script>
					    alert("Clinic Name Already Exist! Please Try Another Name");
					    </script>';	
				}else{
				    echo '<script>
					alert("Process Fail! Please Try Again");
					</script>';	
				}
		}
	    else {
		echo '<script>
		    alert("Clinic Added Successful");
		    </script>';	
	    }
		}
	    
		
	}	
	    
	     
?>
<center>
    <table width=40%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>ADD NEW CLINIC NATURE</b></legend>
                    <form action='#' method='post' name='myForm' id='myForm' enctype="multipart/form-data"> 
						<table width='100%;'>
				
								<tr>
                                    <td width=30%><b>Clinic Nature Name</b></td>
                                    <td width=70%>
                                        <input type='text' name='Clinic_Nature_Name' required='required' size=70 id='Clinic_Name' placeholder='Enter Clinic Nature Name'>
                                    </td>
                                </tr>
                                <tr>
                                    <td width=30%><b>Clinic Nature Description</b></td>
                                    <td width=70%>
                                    <textarea required="required" name="Clinic_Nature_Description"  placeholder="Enter Clinic Nature Description"></textarea>
                                        <!--input type='text' name='Clinic_Nature_Name' required='required' size=70 id='Clinic_Name' placeholder='Enter Clinic Nature Name'-->
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='buttonAddClinicNature' value='true'/> 
                                    </td>
                                </tr>
						</table>
                    </form>
            </fieldset>
        </center></td></tr></table>
</center>
<?php
    include("./includes/footer.php");
?>