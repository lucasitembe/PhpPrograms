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
    if(isset($_GET['Clinic_Nature_ID'])){
        $Clinic_Nature_ID = $_GET['Clinic_Nature_ID'];
    }else{
        $Clinic_Nature_ID = 0;
    }
    
    //select clinic details
    $select_clinic = "select * from tbl_clinic_nature where Clinic_Nature_ID= $Clinic_Nature_ID";
    $result = mysqli_query($conn,$select_clinic);
    
    while($row = mysqli_fetch_array($result)){
        $Clinic_Nature_Name = $row['Clinic_Nature_Name'];
        $Clinic_Nature_Description = $row['Clinic_Nature_Description'];
    }

?>
<?php
	if(isset($_POST['submittedAddNewClinicForm'])){
		
        $Clinic_Nature_Name = trim(mysqli_real_escape_string($conn,$_POST['Clinic_Nature_Name']));
	    $Clinic_Nature_Description = trim(mysqli_real_escape_string($conn,$_POST['Clinic_Nature_Description']));
        if(empty($Clinic_Nature_Name) || empty($Clinic_Nature_Description)){
            echo '<script>
                    alert("Nature and Description must be filled!");
                    </script>'; 
        }else{
            $sql = "update tbl_clinic_nature set Clinic_Nature_Name = '$Clinic_Nature_Name',Clinic_Nature_Description='$Clinic_Nature_Description' where Clinic_Nature_ID = $Clinic_Nature_ID";
        
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
            alert("Clinic Nature Updated Successfully");
            document.location = "editclinicnaturelist.php?EditClinicNature=EditClinicNatureThisForm";
            </script>'; 
        }
        }
	    
		
	}
?>
<center>
    <table width=70%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>UPDATE CLINIC NATURE</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm' enctype="multipart/form-data"> 
				<tr>
                                    <td width=30% style='text-align: right;'><b>Clinic Nature Name</b></td>
                                    <td width=70%>
                                        <input type='text' name='Clinic_Nature_Name' required='required' autocomplete='off' value='<?php echo htmlspecialchars($Clinic_Nature_Name,ENT_QUOTES); ?>' size=70 id='Clinic_Nature_Name' placeholder='Enter Clinic Nature Name'>
                                    </td>
                                    <!--td width="15%">
                                        <select name="Clinic_Status" id="Clinic_Status">
                                            <option <?php if($Clinic_Status == 'Available'){ echo "selected='selected'"; } ?>>Available</option>
                                            <option <?php if($Clinic_Status == 'Not Available'){ echo "selected='selected'"; } ?>>Not Available</option>
                                        </select>
                                    </td-->
                                </tr>
                                <tr>
                                    <td width=30% style='text-align: right;'><b>Clinic Nature Description</b></td>
                                    <td width=70%>
                                        <textarea name='Clinic_Nature_Description' required='required' id='Clinic_Nature_Description' placeholder='Enter Clinic Nature Description'><?php echo $Clinic_Nature_Description; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan=3 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   UPDATE   ' class='art-button-green'>
					<a href='editclinicnaturelist.php?EditClinicNature=EditClinicNatureThisForm' class='art-button-green'>CANCEL</a>
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