<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
	if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
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
	if(isset($_POST['submittedSupervisorInformationForm'])){
            $Supervisor_Username = mysqli_real_escape_string($conn,$_POST['Supervisor_Username']);
            $Supervisor_Password = mysqli_real_escape_string($conn,md5($_POST['Supervisor_Password']));
            $Sub_Department_Name = $_POST['Sub_Department_Name'];
            if($Supervisor_Username != '' && $Supervisor_Username != null && $Supervisor_Password != '' && $Supervisor_Password != null && $Sub_Department_Name != '' && $Sub_Department_Name != null){
		$query="select * from tbl_branches b, tbl_branch_employee be, tbl_employee e, tbl_privileges p
		    where b.branch_id = be.branch_id and e.employee_id = be.employee_id
		    and e.employee_id = p.employee_id and p.Given_Username = '{$Supervisor_Username}' and
		    p.Given_Password  = '{$Supervisor_Password}' and p.Session_Master_Priveleges = 'yes';
		";
		
		//DML excution select from..
		$result= mysqli_query($conn,$query);
		$no=mysqli_num_rows($result);
		if($no>0){
		    $row=mysqli_fetch_assoc($result);
		    @session_start();
		    $_SESSION['Storage_Supervisor'] = $row;
		    $_SESSION['Storage'] = $Sub_Department_Name;
		    
		    //get storage id
		    $select_id = mysqli_query($conn,"SELECT Sub_Department_ID, Sub_Department_Name from tbl_sub_department where sub_department_name = '$Sub_Department_Name'") or die(mysqli_error($conn));
		    $num_rows = mysqli_num_rows($select_id);
		    if($num_rows > 0){
			$row2 = mysqli_fetch_assoc($select_id);
			$_SESSION['Storage_Info'] = $row2;
		    }
		    header("Location:./assigned_requisition_engineering.php?StorageAndSupply=StorageAndSupplyThisPage");
		}else{ 
		    echo "<script type='text/javascript'>
				alert('INVALID INFORMATION OR NO PRIVILEGES TO SUPPORT');
			    </script>";
		}
	    }else{
		    echo "<script type='text/javascript'>
				alert('To Authenticate Employee You Must Provide Add Information. Please Try Again');
			    </script>";
	    }
	}
?>

<center>
    <table width=60%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>STORAGE AND ENGINEERING SUPERVISOR AUTHENTICATION</b></legend>
                    <table width = '100%'>
                        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                           
                                <tr>
                                    <td width=30% style="text-align:right;">Supervisor Username</td>
                                    <td width=70%>
                                        <input type='text' name='Supervisor_Username' autocomplete='off' required='required' size=70 id='Supervisor_Username' placeholder='Supervisor Username'>
                                    </td>
                                </tr> 
                                <tr>
                                    <td style="text-align:right;">Supervisor Password</td>
                                    <td width=70%>
                                        <input type='password' name='Supervisor_Password' required='required' size=70 id='Supervisor_Password' placeholder='Supervisor Password'>
                                    </td> 
                                </tr>
                                <tr>
                                    <td style="text-align:right;">Sub Department</td>
                                    <td>
                                        <!--<select name='Sub_Department_ID' id='Sub_Department_ID'>-->
                                        <select name='Sub_Department_Name' id='Sub_Department_Name' required='required'>
                                            <option selected='selected'></option>
                                            <?php
                                                if(isset($_SESSION['userinfo']['Employee_ID'])){
                                                    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                                                }
                                                
                                                $can_login_to_high_privileges_department = $_SESSION['userinfo']['can_login_to_high_privileges_department'];
                                $filter_sub_d="";
                                if($can_login_to_high_privileges_department=='yes'){
                                    $filter_sub_d="and sdep.privileges='high'";
                                }
                                if($can_login_to_high_privileges_department!='yes'){
                                    $filter_sub_d="and sdep.privileges='normal'";
                                }
                                                $select_sub_departments = mysqli_query($conn,"SELECT Sub_Department_Name from
                                                                                tbl_department dep, tbl_sub_department sdep,tbl_employee_sub_department ed
                                                                                    where dep.department_id = sdep.department_id and
                                                                                    sdep.Sub_Department_Status = 'active' and
                                                                                        ed.Employee_ID = '$Employee_ID' and
                                                                                            ed.Sub_Department_ID = sdep.Sub_Department_ID and
                                                                                            dep.Department_Location ='Storage And Supply' $filter_sub_d
                                                                                        ");
                                                while($row = mysqli_fetch_array($select_sub_departments)){
                                                    echo "<option>".$row['Sub_Department_Name']."</option>";
                                                }
                                            
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan=2 style='text-align: center;'>
                                        <input type='submit' name='submit' id='submit' value='<?php echo 'ALLOW '.strtoupper($_SESSION['userinfo']['Employee_Name']); ?>' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value='CLEAR' class='art-button-green'> 
                                        <a href='./index.php?TransactionAccessDenied=TransactionAccessDeniedThisPage' class='art-button-green'>CANCEL</a>
                                        <input type='hidden' name='submittedSupervisorInformationForm' value='true'/> 
                                    </td>
                                </tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>



<?php
    include("./includes/footer.php");
?>
<script>
    $(document).ready(function(){
       $('select').select2(); 
    });
</script>