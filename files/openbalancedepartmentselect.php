 <?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
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
    //get department location
    if(isset($_GET['SessionCategory'])){
	$SessionCategory = $_GET['SessionCategory'];
    }else{
	$SessionCategory = '';
    }
?>

<center>
    <table width=50%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b> SELECT LOCATION</b></legend>
                    <table width = '100%'>
                        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                                <tr>
                                    <td style='text-align: center;'><b>Select Department</b>
                                        <!--<select name='Sub_Department_ID' id='Sub_Department_ID'>-->
                                        <select name='Sub_Department_ID' id='Sub_Department_ID' required='required'>
                                            <option selected='selected'></option>
                                            <?php
                                                if(isset($_SESSION['userinfo']['Employee_ID'])){
                                                    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                                                }else{
						    $Employee_ID = 0;
						}
						
                                                $select_sub_departments = mysqli_query($conn,"select sdep.Sub_Department_ID, Sub_Department_Name from
                                                                                tbl_department dep, tbl_sub_department sdep,tbl_employee_sub_department ed
                                                                                    where dep.department_id = sdep.department_id and
                                                                                        ed.Employee_ID = '$Employee_ID' and
                                                                                            ed.Sub_Department_ID = sdep.Sub_Department_ID and
                                                                                            Department_Location = '".str_replace('_',' ',$SessionCategory)."'
                                                                                        ") or die(mysqli_error($conn));
                                                while($row = mysqli_fetch_array($select_sub_departments)){
					    ?>
                                                <option value='<?php echo $row['Sub_Department_ID']; ?>'><?php echo $row['Sub_Department_Name']; ?></option>
                                            <?php
						}
                                            ?>
                                        </select>
                                    </td>
                                </tr>
				
                                <tr>
                                    <td colspan=2 style='text-align: center;'>
                                        <input type='submit' name='submit' id='submit' value='CONTINUE' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value='CLEAR' class='art-button-green'>
                                        <input type='hidden' name='submittedDepartmentSelection' value='true'> 
                                    </td>
                                </tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>


<?php
    if(isset($_POST['submittedDepartmentSelection'])){
	$Sub_Department_ID = $_POST['Sub_Department_ID'];
	if($Sub_Department_ID != null && $Sub_Department_ID != ''){
	    $_SESSION['Departmental_Open_Balance_Control'] = $Sub_Department_ID;
	    header("Location: ./departmentalopenbalance.php?SessionCategory=$SessionCategory&ReagentOpenBalance=ReagentOpenBalanceThisPage");
	}
    }
?>
<?php
    include("./includes/footer.php");
?>