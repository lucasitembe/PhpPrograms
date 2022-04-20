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
    if(isset($_GET['Clinic_ID'])){
        $Clinic_ID = $_GET['Clinic_ID'];
    }else{
        $Clinic_ID = 0;
    }
    
    //select clinic details
    $select_clinic = "select * from tbl_Clinic where clinic_id = '$Clinic_ID'";
    $result = mysqli_query($conn,$select_clinic);
    
    while($row = mysqli_fetch_array($result)){
        $Clinic_Name = $row['Clinic_Name'];
        $Nature_Of_Clinic = $row['Nature_Of_Clinic'];
        $Clinic_Status = $row['Clinic_Status'];
    }
?>
<?php
	if(isset($_POST['submittedAddNewClinicForm'])){
		
        $Nature_Of_Clinic = mysqli_real_escape_string($conn,$_POST['Clinic_Nature_Name']);
        $Clinic_Name = mysqli_real_escape_string($conn,$_POST['Clinic_Name']);
	    $Clinic_Status = mysqli_real_escape_string($conn,$_POST['Clinic_Status']);
	    $sql = "update tbl_clinic set Nature_Of_Clinic='$Nature_Of_Clinic', Clinic_Name = '$Clinic_Name', Clinic_Status = '$Clinic_Status' where Clinic_ID = '$Clinic_ID'";
	    
	    if(!mysqli_query($conn,$sql)){
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){ 
				    echo '<script>
					    alert("Clinic Name Already Exist! Please Try Another Name");
					    </script>';	
				}else{
				    echo '<script>
					alert("Process Fail! Please Please Try Again");
					</script>';	
				}
		}
	    else {
		echo '<script>
		    alert("Clinic Updated Successful");
		    document.location = "editcliniclist.php?EditClinic=EditClinicThisForm";
		    </script>';	
	    }
		
	}
?>
<center>
    <table width=70%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>UPDATE CLINIC</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm' enctype="multipart/form-data"> 
                        <tr>
                            <td width=30% style='text-align: right;'><b>Clinic Nature</b></td>
                           
                                    <td width="15%">
                                   
                                        <select name="Clinic_Nature_Name" id="Clinic_Nature_Name">

                                        <?php //select clinic details
                                            echo "<option selected>".$Nature_Of_Clinic."</option>";
                                            $select_clinic_nature = "select * from tbl_clinic_nature";
                                            $nature_result = mysqli_query($conn,$select_clinic_nature);
                                            
                                            while($row = mysqli_fetch_array($nature_result)){
                                                echo "<option>".$row['Clinic_Nature_Name']."</option>";
                                            }
                                        ?>
                                        </select>
                                    </td>
                        </tr>
				<tr>
                                    <td width=30% style='text-align: right;'><b>Clinic Name</b></td>
                                    <td width=70%>
                                        <input type='text' name='Clinic_Name' required='required' autocomplete='off' value='<?php echo htmlspecialchars($Clinic_Name,ENT_QUOTES); ?>' size=70 id='Clinic_Name' placeholder='Enter Clinic Name'>
                                    </td>
                                    <td width="15%">
                                        <select name="Clinic_Status" id="Clinic_Status">
                                            <option <?php if($Clinic_Status == 'Available'){ echo "selected='selected'"; } ?>>Available</option>
                                            <option <?php if($Clinic_Status == 'Not Available'){ echo "selected='selected'"; } ?>>Not Available</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan=3 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   UPDATE   ' class='art-button-green'>
					<a href='editcliniclist.php?EditClinic=EditClinicThisForm' class='art-button-green'>CANCEL</a>
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