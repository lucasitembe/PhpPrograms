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
	if(isset($_POST['submittedAddNewClinicDepartmentForm'])){
		
	    $Department_Name = mysqli_real_escape_string($conn,$_POST['Department_Name']);
	    $Clinic_ID = mysqli_real_escape_string($conn,$_POST['Clinic_ID']);
	    $sql = "insert into tbl_clinic_department(Clinic_Department_Name,Clinic_ID)
		    values ('$Department_Name',$Clinic_ID)";
	    
	    if(!mysqli_query($conn,$sql)){
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){ 
				    echo '<script>
					    alert("Department Already Exist !");
					    </script>';	
				}else{
				    echo '<script>
					alert("Process Fail! Please Try Again");
					</script>';	
				}
		}
	    else {
		echo '<script>
		    alert("Department Created Successfully");
		    </script>';	
	    }
		
	}	
	    
	     
?>
<center>
    <table width=40%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>ADD NEW CLINIC DEPARTMENT</b></legend>
                    <table>
                <form action='#' method='post' name='myForm' id='myForm' enctype="multipart/form-data"> 
				<tr>
                <td width=30%><b>Clinic Name</b></td>
                <td width=70%>
                <select name='Clinic_ID' id='Clinic_ID' required='required' style='padding: 4px 0px 4px 0px;'>
                <option selected='selected'></option> 
                <?php
                    $select_clinic= mysqli_query($conn,"select * from tbl_clinic") or die(mysqli_error($conn));
                    while ($row=mysqli_fetch_array($select_clinic)) {
                        echo "<option value='".$row['Clinic_ID']."'>".$row['Clinic_Name']."</option>";
                }
                ?>
					</select>
                </td>
                </tr>
				<tr>
                <td width=30%><b>Department Name</b></td>
                <td width=70%>
                <input type='text' name='Department_Name' required='required' size=100 id='Department_Name' placeholder='Enter Clinic Department Name'>
                </td>
                </tr>
                <tr>
                <td colspan=2 style='text-align: right;'>
                <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                <input type='hidden' name='submittedAddNewClinicDepartmentForm' value='true'/> 
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
<script type="text/javascript">
	$("#submit").on('click',function(){
		var Department_Name=$('#Department_Name').val();

		if(Department_Name.trim()===''){
			alert("WRITE THE DEPARTMENT NAME ");
			return false;
		}
	});
</script>