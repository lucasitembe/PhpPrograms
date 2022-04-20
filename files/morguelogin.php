<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Morgue_Works'])){
	if($_SESSION['userinfo']['Morgue_Works'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['Morgue_Admission_ID'])){
          header("Location:morguepage.php?MorgueSupervisorsPage=MorguePanelPage");
    }
    if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];    
    }else{
	$Registration_ID = '';
    }
    if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

if(isset($_POST['submit'])){
    $Supervisor_Username = mysqli_real_escape_string($conn,$_POST['Supervisor_Username']);
    $Supervisor_Password = mysqli_real_escape_string($conn,md5($_POST['Supervisor_Password']));
    $Sub_Department_ID = $_POST['Sub_Department_ID'];
    
    
     $query = "select * from tbl_branches b, tbl_branch_employee be, tbl_employee e, tbl_privileges p
                where b.branch_id = be.branch_id and be.employee_id = e.employee_id
                and e.employee_id = p.employee_id and p.Given_Username = '{$Supervisor_Username}' and
		p.Given_Password  = '{$Supervisor_Password}' and p.Session_Master_Priveleges = 'yes';
            ";

    //DML excution select from..
    $result = mysqli_query($conn,$query);
    $no = mysqli_num_rows($result);
    
    if($no>0){
            $row = mysqli_fetch_assoc($result);
            $_SESSION['Admission_Supervisor'] = $row;
          

            //get sub department
            $sub_dep_result = mysqli_query($conn,"SELECT Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
            $row = mysqli_fetch_array($sub_dep_result);
            
            $Sub_Department_Name= $row['Sub_Department_Name'];
            $_SESSION['Morgue_Admission_Department_Name'] = $Sub_Department_Name;
            $_SESSION['Morgue_Admission_ID'] = $Sub_Department_ID;
            
            $Branch_ID = $_SESSION['Admission_Supervisor']['Branch_ID'];
            $_SESSION['Morgue_Admission'] = $Sub_Department_Name;
            $_SESSION['Morgue_Admission_Sub_Department_Name'] = $Sub_Department_Name;
            $_SESSION['Morgue_Admission_Sub_Department_ID'] = $Sub_Department_ID;

           //$Authentication_Date_And_Time = date('Y-m-d H:i:s');
          //  authentication($_SESSION['Admission_Supervisor']['Employee_ID'], $Authentication_Date_And_Time, $Sub_Department_Name);
          header("Location:morguepage.php?MorgueSupervisorsPage=MorguePanelPage");
           
    }else{
        echo "<script type='text/javascript'>
                                alert('INVALID INFORMATION OR NO PRIVILEGES TO SUPPORT');
                            </script>";
    }
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



<center>
    <table width=100%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b> MORGUE SUPERVISOR</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                           
                                <tr>
                                    <td width=40% style= text-align:right;><b>Username</b></td>
                                    <td width=70%>
                                        <input type='text' name='Supervisor_Username' required='required' size=70 id='Supervisor_Username' placeholder='Supervisor Username'>
                                    </td>
                                </tr> 
                                <tr>
                                    <td style='text-align:right;'><b>Password</b></td>
                                    <td width=70%>
                                        <input type='password' name='Supervisor_Password' required='required' size=70 id='Supervisor_Password' placeholder='Supervisor Password'>
                                    </td> 
                                </tr> 
								<tr>
			
									
                               
                                 <td    style='text-align:right;'><b>Sub-Department</b></td>
									
                               <td>
                                 <select name='Sub_Department_ID' required="">
                                
					  <?php 
                                           
                                        $select_sub_departments = mysqli_query($conn,"SELECT Sub_Department_Name,sdep.Sub_Department_ID FROM
															tbl_department dep, tbl_sub_department sdep,tbl_employee_sub_department ed
															WHERE dep.department_id = sdep.department_id AND
															sdep.Sub_Department_Status = 'active' AND
															ed.Employee_ID = '$Employee_ID' AND
															ed.Sub_Department_ID = sdep.Sub_Department_ID AND
															Department_Location = 'Admission'
                                                            -- section
                                                            --  AND NOT IN sdep.sub_Department_location = ''
															");
                                    
                                    $num = mysqli_num_rows($select_sub_departments);
                                    if ($num != 1) {
                                        echo "<option selected='selected'></option>";
                                    }
                                    while ($row = mysqli_fetch_array($select_sub_departments)) {
                                        $Sub_Department_ID=$row['Sub_Department_ID'];
                                        echo "<option value='$Sub_Department_ID'>" . $row['Sub_Department_Name'] . "</option>";
                                    }
                                          ?>
                                    </select>
									</td>
                            </tr>
                                <tr>
                                    <td colspan=2 style='text-align: center;'>
                                        <input type='submit' name='submit' id='submit' value='LOGIN' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value='CLEAR' class='art-button-green'> 
                                        <a style="display: none" href='morguepage.php?MorgueSupervisorsPage=MorguePanelPage' class='art-button-green'>

                                        <b>enter</b>
					</a>
                    
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