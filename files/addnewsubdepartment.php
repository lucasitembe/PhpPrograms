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


<center>
    <table width=50%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>ADD NEW SUB DEPARTMENT</b></legend>
                    <table width=100%>
                        <form action='#' method='post' name='myForm' id='myForm' enctype="multipart/form-data"> 
                                <tr>
                                    <td width=30%><b>Select Department</b></td>
                                    <td width=70%>
                                        <select name='Department_Name' id='Department_Name'>
                                            <option selected='selected'></option>
                                            <?php
                                                $select_Department = mysqli_query($conn,"select Department_Name from tbl_department");
                                                while($row = mysqli_fetch_array($select_Department)){
                                                    echo "<option>".$row['Department_Name']."</option>";
                                                }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
				<tr>
				    <td><b>Sub Department Name</b></td>
				    <td>
                                        <input type='text' name='Sub_Department_Name' id='Sub_Department_Name' required='required' placeholder='Enter Sub Department Name'>
                                    </td>
				</tr>
                                <tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedAddNewSubDepartmentForm' value='true'/> 
                                    </td>
                                </tr>
                        </form>
                    </table>
            </fieldset>
        </center></td></tr></table>
</center>

<?php
    if(isset($_POST['submittedAddNewSubDepartmentForm'])){
	    
	$Department_Name = mysqli_real_escape_string($conn,$_POST['Department_Name']); 
	$Sub_Department_Name = mysqli_real_escape_string($conn,$_POST['Sub_Department_Name']);
	
	//get the nature of the department
	$select_nature = mysqli_query($conn,"select Department_Location from tbl_department where Department_Name = '$Department_Name'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select_nature);
	if($num > 0){
	    while($row = mysqli_fetch_array($select_nature)){
		$Department_Location = $row['Department_Location'];
	    }
	}else{
	    $Department_Location = '';
	}
	
	if($Department_Location != ''){
	    if(strtolower($Department_Location) == 'pharmacy'){
		$sql = "insert into tbl_sub_department(Department_ID,Sub_Department_Name)
			values ((select Department_ID from tbl_department where department_name = '$Department_Name'),'$Sub_Department_Name')";
		
		if(!mysqli_query($conn,$sql)){
				    $error = '1062yes';
				    if(mysql_errno()."yes" == $error){ 
					echo '<script>
						alert("Sub Department Name Already Exist! Try Another Name");
						document.Location = "./addnewsubdepartment.php?AddNewSubDepartment=AddNewSubDepartmentThisForm";
						</script>';	
				    }else{
					echo '<script>
					    alert("Process Fail! Please Try Again");
					    document.Location = "./addnewsubdepartment.php?AddNewSubDepartment=AddNewSubDepartmentThisForm";
					    </script>';	
				    }
		    }else {
			//get the last sub department
			$select_sub = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department order by Sub_Department_ID desc limit 1") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($select_sub)){
			    $Sub_Department_ID = $row['Sub_Department_ID'];
			}
			
			//insert items into tbl_item_balance
			$insert_items = mysqli_query($conn,"insert into tbl_items_balance(Item_ID,Item_Balance,Item_Temporary_Balance,Sub_Department_ID,Sub_Department_Type)
							select Item_ID,0,0,'$Sub_Department_ID','Pharmacy' from tbl_items") or die(mysqli_error($conn));
			if($insert_items){
			    echo '<script>
				alert("Sub Department Added Successful");
				document.Location = "./addnewsubdepartment.php?AddNewSubDepartment=AddNewSubDepartmentThisForm";
				</script>';
			}else{
			    echo '<script>
				alert("Process Fail! Please Try Again");
				document.Location = "./addnewsubdepartment.php?AddNewSubDepartment=AddNewSubDepartmentThisForm";
				</script>';
			}
		}		
	    }elseif(strtolower($Department_Location) == 'storage and supply'){
		$sql = "insert into tbl_sub_department(Department_ID,Sub_Department_Name)
			values ((select Department_ID from tbl_department where department_name = '$Department_Name'),'$Sub_Department_Name')";
		
		if(!mysqli_query($conn,$sql)){
				    $error = '1062yes';
				    if(mysql_errno()."yes" == $error){ 
					echo '<script>
						alert("Sub Department Name Already Exist! Try Another Name");
						document.Location = "./addnewsubdepartment.php?AddNewSubDepartment=AddNewSubDepartmentThisForm";
						</script>';	
				    }else{
					echo '<script>
					    alert("Process Fail! Please Try Again");
					    document.Location = "./addnewsubdepartment.php?AddNewSubDepartment=AddNewSubDepartmentThisForm";
					    </script>';	
				    }
		}else {
		    //get the last sub department
		    $select_sub = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department order by Sub_Department_ID desc limit 1") or die(mysqli_error($conn));
		    while($row = mysqli_fetch_array($select_sub)){
			$Sub_Department_ID = $row['Sub_Department_ID'];
		    }
		    
		    //insert items into tbl_item_balance
		    $insert_items = mysqli_query($conn,"insert into tbl_items_balance(Item_ID,Item_Balance,Item_Temporary_Balance,Sub_Department_ID,Sub_Department_Type)
						    select Item_ID,0,0,'$Sub_Department_ID','Storage' from tbl_items") or die(mysqli_error($conn));
		    if($insert_items){
			echo '<script>
			    alert("Sub Department Added Successful");
			    document.Location = "./addnewsubdepartment.php?AddNewSubDepartment=AddNewSubDepartmentThisForm";
			    </script>';
		    }else{
			echo '<script>
			    alert("Process Fail! Please Try Again");
			    document.Location = "./addnewsubdepartment.php?AddNewSubDepartment=AddNewSubDepartmentThisForm";
			    </script>';
		    }
		}
	    }else{
		$sql = "insert into tbl_sub_department(Department_ID,Sub_Department_Name)
			values ((select Department_ID from tbl_department where Department_Name = '$Department_Name'),'$Sub_Department_Name')";
		
		if(!mysqli_query($conn,$sql)){
				    $error = '1062yes';
				    if(mysql_errno()."yes" == $error){ 
					echo '<script>
						alert("Sub Department Name Already Exist! Try Another Name");
						document.Location = "./addnewsubdepartment.php?AddNewSubDepartment=AddNewSubDepartmentThisForm";
						</script>';	
				    }else{
					echo '<script>
					    alert("Process Fail! Please Try Again");
					    document.Location = "./addnewsubdepartment.php?AddNewSubDepartment=AddNewSubDepartmentThisForm";
					    </script>';	
				    }
		    }
		else {
		    echo '<script>
			alert("Sub Department Added Successful");
			document.Location = "./addnewsubdepartment.php?AddNewSubDepartment=AddNewSubDepartmentThisForm";
			</script>';	
		}
	    }
	}else{
	    echo '<script>
		    alert("Process Fail! Please Try Again");
		    document.Location = "./addnewsubdepartment.php?AddNewSubDepartment=AddNewSubDepartmentThisForm";
		    </script>';
	}
    }
?>

<?php
    include("./includes/footer.php");
?>