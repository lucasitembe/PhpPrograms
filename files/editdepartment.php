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
    <a href='departmentpage.php?DepartmentPage=DepartmentPageThisPage' class='art-button-green'>
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
    //get department id
    if(isset($_GET['Department_ID'])){
        $Department_ID = $_GET['Department_ID'];
    }else{
        $Department_ID = 0;
    } 
    $select = mysqli_query($conn,"select * from tbl_department where department_id = '$Department_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if($no > 0){
        while($row = mysqli_fetch_array($select)){
            $Department_Name = $row['Department_Name'];
            $Department_Location = $row['Department_Location'];
        }
    }else{
            $Department_Name = '';
            $Department_Location = '';
    }

?>
<?php
	if(isset($_POST['submittedUpdateDepartmentForm'])){
		
	    $Department_Name = mysqli_real_escape_string($conn,str_replace("  ", " ", $_POST['Department_Name']));
            $Department_Location = mysqli_real_escape_string($conn,$_POST['Department_Location']);
	    $sql = "update tbl_department set Department_Name = '$Department_Name', Department_Location = '$Department_Location'
			    where department_id = '$Department_ID'"; 
	    
	    if(!mysqli_query($conn,$sql)){
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){ 
				    echo '<script>
					    alert("Department Name Already Exist! Try Another Name");
					    </script>';	
				}else{
				    echo '<script>
					alert("Process Fail! Please Try Again");
					</script>';	
				}
		}
	    else {
		echo '<script>
		    alert("Department Updated Successful");
		    document.location = "editdepartmentlist.php?EditDepartmentList=EditDepartmentListThisPage";
		    </script>';	
	    }
		
	}	
	    
	     
?>
<center>
    <table width=50%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>UPDATE DEPARTMENT</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm' enctype="multipart/form-data">
                           
				<tr>
				    <td><b>Nature Of The Department</b></td>
				    <td>
                                    <?php if($Department_Location == '') { ?>
					<select name='Department_Location' disabled='disabled' id='Department_Location' required='required'>
                                    <?php }else{ ?>
                                        <select name='Department_Location' id='Department_Location' required='required'>
                                    <?php } ?>
					    <option selected='selected'><?php echo $Department_Location; ?></option>
					    <option>Admission</option>
					    <option>Blood Bank</option>
					    <option>Cecap</option>
					    <option>Dental</option>
					    <option>Dialysis</option>
					    <option>Dressing</option>
					    <option>Eram</option>
					    <option>Ear</option>
					    <option>Eye</option>
					    <option>Family Planning</option>
					    <option>Finance</option>
					    <option>HIV</option>
					    <option>Laboratory</option>
					    <option>Management</option>
					    <option>Maternity</option> 
					    <option>Nurse Station</option>
					    <option>Physiotherapy</option>
					    <option>Pharmacy</option>
					    <option>Procedure</option>
					    <option>Procurement</option>
					    <option>Quality Assuarance</option>
					    <option>Rch</option>
					    <option>Radiology</option>
					    <option>Reception</option>
					    <option>Revenue Center</option>
					    <option>Setup And Configuration</option>
					    <option>Storage And Supply</option>
					    <option>Surgery</option>
					    <option>Theater</option>
					    <option>Clinic</option>
					    <option>Deposit</option>
					</select>
				    </td>
				</tr>
				<tr>
                                    <td width=30%><b>Department Name</b></td>
                                    <td width=70%>
				    <?php if($Department_Name == ''){ ?>
                                        <input type='text' name='Department_Name' required='required' disabled='disabled' size=70 id='Department_Name' >
				    <?php }else{ ?>
					<input type='text' name='Department_Name' required='required' value='<?php echo $Department_Name; ?>' size=70 id='Department_Name' placeholder='Enter Department Name'>
				    <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan=2 style='text-align: right;'>
				    <?php if($Department_Name == '' ){ ?>
                                        <input type='submit' name='submit' id='submit' value='   UPDATE   ' class='art-button-green' disabled='disabled'>
				    <?php }else{ ?>
                                        <input type='submit' name='submit' id='submit' value='   UPDATE   ' class='art-button-green'> 					
				    <?php } ?>
					<a href='departmentpage.php?DepartmentPage=DepartmentPageThisPage' class='art-button-green'>CANCEL</a>
                                        <input type='hidden' name='submittedUpdateDepartmentForm' value='true'/> 
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