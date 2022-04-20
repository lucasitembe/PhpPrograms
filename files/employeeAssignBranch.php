<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])  || isset($_SESSION['userinfo']['Appointment_Works'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' &&  $_SESSION['userinfo']['Appointment_Works']!='yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Appointment_Works'] =='yes'){ 
?>
    <a href="addnewemployee.php?AddNewEmployee=AddNewEmployeeThisPage<?=(isset($_GET['HRWork']) && $_GET['HRWork']=='true')?'&HRWork=true':''?>" class='art-button-green'>
        ADD EMPLOYEE
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' && !isset($_GET['HRWork'])){ 
		echo "<a href='employeepage.php?EmployeeManagement=EmployeeManagementThisPage' class='art-button-green'>BACK</a>";
	}
    $is_hr="";
    if(isset($_GET['HRWork']) && $_GET['HRWork']=='true'){ 
            echo "<a href='human_resource.php?HRWork=HRWorkThisPage' class='art-button-green'>BACK</a>";
            $is_hr='?HRWork=true';
        }
    }
?>
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $age = $Today - $original_Date; 
    }
?>

 

<script language="javascript" type="text/javascript">
    function searchEmployee(Employee_Name){
        var is_hr="<?php echo((isset($_GET['HRWork']) && $_GET['HRWork']=='true')?'&HRWork=true':''); ?>";
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=380px src='List_Of_Employee_To_Assign_To_Branch_Iframe.php?Employee_Name="+Employee_Name+is_hr+"'></iframe>";
    }
</script>
<br/><br/>
<center>
    <table width=40%>
        <tr>
            <td>
                <input type='text' name='Search_Employee' id='Search_Employee' onclick='searchEmployee(this.value)' onkeypress='searchEmployee(this.value)' onkeyup='searchEmployee(this.value)' placeholder='~~~~~~~~~~~~~~~~~~~Search Employee Name~~~~~~~~~~~~~~~~~~'>
            </td> 
        </tr>
        
    </table>
</center>
<fieldset>  
            <legend align=center><b>EMPLOYEES LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<?php echo "<iframe width='100%' height=380px src='List_Of_Employee_To_Assign_To_Branch_Iframe.php".$is_hr."'></iframe>";?>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>