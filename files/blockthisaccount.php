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
    
    
    $control_Save_Button = 'active';
    if(isset($_GET['Employee_ID'])){
	$Employee_ID = $_GET['Employee_ID'];
    }else{
	$Employee_ID = 0;
	$Employee_Name = 'Undefined Employee Name'; 
	$Employee_Title = 'Undefined Employee Title';
	$control_Save_Button = 'inactive';
    }
?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
            if(isset($_GET['HRWork']) && $_GET['HRWork']=='true'){
                echo "<a href='editemployee.php?Employee_ID=".$Employee_ID."&EditEmployee=EditEmployeeThisForm&HRWork=true' class='art-button-green'>BACK</a>";
            }else{
                echo "<a href='editemployee.php?Employee_ID=".$Employee_ID."&EditEmployee=EditEmployeeThisForm' class='art-button-green'>BACK</a>";
            }
} } ?>



<?php

    //select employee information based on employee id
    
    $selectThisRecord = mysqli_query($conn,"select * from tbl_employee e, tbl_privileges p, tbl_department d
					where e.employee_id = p.employee_id and
					    d.department_id = e.department_id and
						e.employee_id = '$Employee_ID'");
    $numberOfRecord = mysqli_num_rows($selectThisRecord);
    if($numberOfRecord > 0){
    while($row = mysqli_fetch_array($selectThisRecord)){ 
	$Employee_Name = $row['Employee_Name']; 
	$Employee_Title = $row['Employee_Title'];
	$Employee_Type = $row['Employee_Type'];
	$Employee_Job_Code = $row['Employee_Job_Code'];
	$Account_Status = $row['Account_Status'];
	
	
	
		    
    }
    }else{
	$Employee_Name = 'Undefined Employee Name'; 
	$Employee_Title = 'Undefined Employee Title';
	$Employee_Type = '';
	$Employee_Job_Code = ''; 
	$Account_Status  = '';
	
    }
?>
<br/><br/><br/>
        <center>
            <fieldset>
                <legend align='right'><b>BLOCK EMPLOYEE ACCOUNT</b></legend>
                    <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">        
                    <table width = 50%>
                        <tr>
                            <td style="color:black; border:2px solid #ccc;text-align:right;">Employee Name</td>
                            <td style="color:black; border:2px solid #ccc;text-align:right;"><input type='text' name='Employee_Name' id='Employee_Name' readonly='readonly'value='<?php echo $Employee_Name; ?>'> </td>
                            <td style="color:black; border:2px solid #ccc;text-align:right;">Employee Type</td>
                            <td style="color:black; border:2px solid #ccc;text-align: left;"> 
                                <input type='text' value='<?php echo $Employee_Type; ?>' readonly='readonly'> 
                            </td>
                        </tr>
                        <tr>
                            <td style="color:black; border:2px solid #ccc; text-align: right;">Job Code</td>
                            <td style="color:black; border:2px solid #ccc; text-align: left;"> 
                                <input type='text' value='<?php echo $Employee_Job_Code; ?>' readonly='readonly'> 
                            </td>
                            <td style="color:black; border:2px solid #ccc;text-align:right;">Job Title</td>
                            <td style="color:black; border:2px solid #ccc;text-align:right;"><input type='text' name='Job_Title' id='Job_Title' readonly='readonly' value='<?php echo $Employee_Title; ?>'> </td>
                        </tr> 
                        <tr>
                            <td style="color:black; border:2px solid #ccc;text-align:right;">Admin Username</td>
                            <td style="color:black; border:2px solid #ccc;text-align:right;"><input type='text' name='Admin_Username' id='Admin_Username' required='required' placeholder='Enter Your Admin Username'></td>
                            <td style="color:black; border:2px solid #ccc;text-align:right;">Admin Password</td>
                            <td style="color:black; border:2px solid #ccc;text-align:right;"><input type='password' name='Admin_Password' id='Admin_Password' required='required' placeholder='Enter Your Admin Password'></td>
                        </tr>
                        
                        <tr>
                            <td style="color:black; border:2px solid #ccc;text-align:right;" colspan=4>
                                <input type='submit' name='submit' id='submit' value='BLOCK THIS ACCOUNT' class='art-button-green'>
                                <?php if(isset($_GET['HRWork']) && $_GET['HRWork']=='true'){?>
                                    <a href='editemployee.php?Employee_ID=<?php echo $Employee_ID; ?>&EditEmployee=EditEmployeeThisForm&HRWork=true' class='art-button-green'>CANCEL</a>
                                <?php }else{ ?>
                                    <a href='editemployee.php?Employee_ID=<?php echo $Employee_ID; ?>&EditEmployee=EditEmployeeThisForm' class='art-button-green'>CANCEL</a>
                                <?php }?>
                                <input type='hidden' name='submittedBlockEmployeeAccountForm' value='true'>
                            </td>
                        </tr>
                </table>
        </center>
    </fieldset>
</form>


  

<?php
	if(isset($_POST['submittedBlockEmployeeAccountForm'])){
	    
	    $Admin_Username = $_POST['Admin_Username'];
	    $Admin_Password = md5($_POST['Admin_Password']);
	    
	    $Current_Username = $_SESSION['userinfo']['Given_Username'];
	    $Current_Password = $_SESSION['userinfo']['Given_Password'];
	    
	    if($Current_Username == $Admin_Username && $Current_Password == $Admin_Password){
		
		$sql = "update tbl_employee set Account_Status = 'inactive' where employee_id = '$Employee_ID'";  
		   if(!mysqli_query($conn,$sql)){
		        
		   }else {
                if(isset($_GET['HRWork']) && $_GET['HRWork']=='true'){
                    echo "<script type='text/javascript'>
                        alert('ACCOUNT BLOCKED SUCCESSFULLY');
                        document.location = './editemployee.php?Employee_ID=".$Employee_ID."&EditEmployeePrivileges=EditEmployeePrivilegesThisPage&HRWork=true';
                    </script>";
                }else{
                    echo "<script type='text/javascript'>
                        alert('ACCOUNT BLOCKED SUCCESSFULLY');
                        document.location = './editemployee.php?Employee_ID=".$Employee_ID."&EditEmployeePrivileges=EditEmployeePrivilegesThisPage';
                    </script>";
                }
		          
		   }   
		       
	    }else{
            if(isset($_GET['HRWork']) && $_GET['HRWork']=='true'){
		          echo "<script type='text/javascript'>
			          alert('INVALID USERNAME OR PASSWORD. NO PRIVILEGE TO SAVE CHANGES');
			         document.location = './blockthisaccount.php?Employee_ID=".$Employee_ID."&InvalidPrivileges=InvalidPrivilegeThisPage&HRWork=true';
			     </script>";
               }else{
                    echo "<script type='text/javascript'>
                        alert('INVALID USERNAME OR PASSWORD. NO PRIVILEGE TO SAVE CHANGES');
                        document.location = './blockthisaccount.php?Employee_ID=".$Employee_ID."&InvalidPrivileges=InvalidPrivilegeThisPage';
                    </script>";
               }
	    }
	    
		    
	} 
?>




<?php
    include("./includes/footer.php");
?>