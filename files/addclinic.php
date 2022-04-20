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
	if(isset($_POST['submittedAddNewClinicForm'])){
		
	    $Clinic_Name = mysqli_real_escape_string($conn,$_POST['Clinic_Name']);
	    $Nature_Of_Clinic = mysqli_real_escape_string($conn,$_POST['Nature_Of_Clinic']);
	    $sql = "insert into tbl_clinic(Clinic_Name,Nature_Of_Clinic)
		    values ('$Clinic_Name','$Nature_Of_Clinic')";
	    
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
	    
	     
?>
<center>
    <table width=40%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>ADD NEW CLINIC</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm' enctype="multipart/form-data"> 
				<tr>
                                    <td width=30%><b>Nature Of Clinic</b></td>
                                    <td width=70%>
                                        <select name='Nature_Of_Clinic' id='Nature_Of_Clinic' required='required'>
                                        <option selected='selected'></option> 
                                    <?php
                                    $select_clinic_natures = mysqli_query($conn,"select * from tbl_clinic_nature") or die(mysqli_error($conn));
                                    while ($row=mysqli_fetch_array($select_clinic_natures)) {
                                    	echo "<option>".$row['Clinic_Nature_Name']."</option>";
                                    }
                                    ?>
					    
					    <!--option>Cecap</option>
					    <option>Dental</option>
					    <option>Dialysis</option>
					    <option>Doctor's Room</option>
					    <option>Dressing</option>
					    <option>Ear</option>
					    <option>HIV</option>
					    <option>Laboratory</option>
					    <option>Matenity</option>
					    <option>Optical</option>
					    <option>Pharmacy</option>
					    <option>Physiotherapy</option>
					    <option>Radiology</option>
					    <option>Rch</option>
					    <option>Theater</option-->
					</select>
                                    </td>
                                </tr>
				<tr>
                                    <td width=30%><b>Clinic Name</b></td>
                                    <td width=70%>
                                        <input type='text' name='Clinic_Name' required='required' size=70 id='Clinic_Name' placeholder='Enter Clinic Name'>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedAddNewClinicForm' value='true'/> 
                                    </td>
                                </tr>
                        </form>
		    </table>
            </fieldset>
        </center></td></tr></table>
</center>
<?php
    include("./includes/footer.php");
?>