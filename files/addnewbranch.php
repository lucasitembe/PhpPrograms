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

<a href="branchpage.php?BranchConfiguration=BranchConfigurationThisPage" class="art-button-green">BACK</a>

<br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>

<?php
	if(isset($_POST['submittedAddNewBranchForm'])){
	    $Branch_Name = mysqli_real_escape_string($conn,$_POST['Branch_Name']);  
            $Company_Name = mysqli_real_escape_string($conn,$_POST['Company_Name']);  
            
	    $sql = "insert into tbl_branches(Branch_Name,Company_ID)
		values ('$Branch_Name',(select Company_ID from tbl_company where company_Name = '$Company_Name'))";
	    if(mysqli_query($conn,$sql)){
		$select_Branch_ID = mysqli_query($conn,"select Branch_ID from tbl_branches where branch_name = '$Branch_Name' order by branch_id desc limit 1") or die(mysqli_error($conn));
		while($row = mysqli_fetch_array($select_Branch_ID)){
		    $Branch_ID = $row['Branch_ID'];
		}
		mysqli_query($conn,"insert into tbl_system_configuration(Branch_ID,Centralized_Collection,Departmental_Collection)
				    values('$Branch_ID','yes','no')");
		echo '<script>
			alert("Branch Added Successful");
			document.location = "./addnewbranch.php?AddNewBranch=AddNewBranchThisPage";
			</script>';
		
	    }else{
		$error = '1062yes';
		    if(mysql_errno()."yes" == $error){ 
			echo '<script>
				alert("Branch Name Already Exist! Try Another Name");
				</script>';	
		    }else{
			    echo '<script>
			    alert("Process Fail! Please Try Again");
			    document.location = "./addnewbranch.php?AddNewBranch=AddNewBranchThisPage";
		        </script>';	
		    }
		
	    } 
	}
?>
 


<center>
    <table width=50%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>ADD NEW BRANCH</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                            
                                <tr>
                                    <td><b>COMPANY NAME</b></td>
                                    <td>
                                        <select name='Company_Name' id='Company_Name'>
                                            <?php
                                                $data = mysqli_query($conn,"select * from tbl_company");
                                                while($row = mysqli_fetch_array($data)){
                                                    echo '<option>'.$row['Company_Name'].'</option>';
                                                }
                                            ?>    
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td width=30%><b>BRANCH NAME</b></td>
                                    <td width=70%><input type='text' name='Branch_Name' required='required' size=70 id='Branch_Name' placeholder='Enter Branch Name'></td>
                                </tr>
				<tr>
				    <td width=30%><b>SELECT BRANCH BUNNER</b></td>
				    <td width=70%>
					<input type="file" name="Bunner_Name" id='Bunner_Name'/> 
					<input type="hidden" name="MAX_FILE_SIZE" value="100000"  title='SELECT COMPANY BUNNER'/> 
				    </td>
				</tr>
                                <tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedAddNewBranchForm' value='true'/> 
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