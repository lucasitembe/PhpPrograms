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

<a href='editsubdepartmentlist.php?EditSubDepartmentList=EditSubDepartmentListThisPage' class='art-button-green'>BACK</a>

<?php
    //get sub department id
    if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = 0;
    }
    
    //select all records based on sub department id
    $select = mysqli_query($conn,"select * from tbl_department dept, tbl_sub_department sdep
                            where dept.department_id = sdep.department_id and
                            sdep.sub_department_id = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while($row = mysqli_fetch_array($select)){
            $Department_Name = $row['Department_Name'];
            $Sub_Department_Name = $row['Sub_Department_Name'];
            $Sub_Department_Status = strtolower($row['Sub_Department_Status']);
            $Department_ID = $row['Department_ID'];
        }
    }else{
        $Department_Name = '';
        $Sub_Department_Name = '';
        $Sub_Department_Status = '';
        $Department_ID = '';
    }
?>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>

<?php
	if(isset($_POST['submittedEditSubDepartmentForm'])){
		
	    $Department_ID = mysqli_real_escape_string($conn,$_POST['Department_ID']); 
        $Sub_Department_Name = mysqli_real_escape_string($conn,$_POST['Sub_Department_Name']);
        $Sub_Department_Status = mysqli_real_escape_string($conn,$_POST['Sub_Department_Status']);
	    $sql = "update tbl_sub_department set Department_ID = '$Department_ID',
                Sub_Department_Name = '$Sub_Department_Name',
                Sub_Department_Status = '$Sub_Department_Status'
                where Sub_Department_ID = '$Sub_Department_ID'";

	    if(!mysqli_query($conn,$sql)){
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){
				    echo '<script>
					    alert("Sub Department Name Already Exist! Try Another Name");
                            document.Location = "./editsubdepartment.php?Sub_Department_ID='.$Sub_Department_ID.'&EditSubDepartment=EditSubDepartmentThisForm";
					    </script>';	
				}else{
				    echo '<script>
                            alert("Process Fail! Please Try Again");
                            document.Location = "./editsubdepartment.php?Sub_Department_ID='.$Sub_Department_ID.'&EditSubDepartment=EditSubDepartmentThisForm";
					       </script>';	
				}
		}else {
    		echo '<script>
                    alert("Sub Department Edited Successful");
                    document.location = "editsubdepartmentlist.php?EditSubDepartmentList=EditSubDepartmentListThisPage";
    		      </script>';	
	    }
	}
?>
<center>
    <table width=50%><tr><td>
    <center>
        <fieldset>
            <legend align="center" ><b>EDIT SUB DEPARTMENT</b></legend>
            <table width=100%>
                <form action='#' method='post' name='myForm' id='myForm' enctype="multipart/form-data"> 
                    <tr>
                        <td style="text-align: right;" width=30%>Select Department</td>
                        <td width=70%>
                            <select name='Department_ID' id='Department_ID'>
                                <option selected='selected' value="<?php echo $Department_ID; ?>"><?php echo $Department_Name; ?></option>
                                <?php
                                    $select_Department = mysqli_query($conn,"select Department_ID, Department_Name from tbl_department where department_name <> '$Department_Name'");
                                    while($row = mysqli_fetch_array($select_Department)){
                                        echo "<option value='".$row['Department_ID']."'>".$row['Department_Name']."</option>";
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Sub Department Status</td>
                        <td>
                            <select name="Sub_Department_Status" id="Sub_Department_Status">
                                <option value="active" <?php if($Sub_Department_Status == 'active'){ echo "selected='selected'"; } ?>>Active</option>
                                <option value="not active" <?php if($Sub_Department_Status == 'not active'){ echo "selected='selected'"; } ?>>Not Active</option>
                            </select>
                        </td>
                    </tr>
        			<tr>
        			    <td style="text-align: right;"> Sub Department Name</td>
        			    <td>
                            <input type='text' name='Sub_Department_Name' id='Sub_Department_Name' value='<?php echo $Sub_Department_Name; ?>' required='required' placeholder='Enter Sub Department Name' autocomplete="off">
                        </td>
        			</tr>
                    <tr>
                        <td colspan=2 style='text-align: right;'>
                            <input type='submit' name='submit' id='submit' value='   UPDATE   ' class='art-button-green'>
                            <a href='departmentpage.php?Department=DepartmentThisPage' class='art-button-green'>CANCEL</a> 
                            <input type='hidden' name='submittedEditSubDepartmentForm' value='true'/> 
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