<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration']) || isset($_SESSION['userinfo']['Appointment_Works'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' &&  $_SESSION['userinfo']['Appointment_Works'] !='yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_GET['Employee_ID'])){
	$Employee_ID = $_GET['Employee_ID'];
    }else{
	$Employee_ID = 0;
    }
?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes' || $_SESSION['userinfo']['Appointment_Works']=='yes'){ 
		if(isset($_GET['HRWork']) && $_GET['HRWork']=='true'){
    		echo "<a href='employeeAssignBranch.php?ListOfEmployeesToAssignBranch=ListOfEmployeesToAssignBranchThisPage&HRWork=true' class='art-button-green'>BACK</a>";
    	}else{
    		echo "<a href='employeeAssignBranch.php?ListOfEmployeesToAssignBranch=ListOfEmployeesToAssignBranchThisPage' class='art-button-green'>BACK</a>";
    	}
  } } ?>

<!-- get sub department -->
<script type="text/javascript" language="javascript">
    function getSubDepartment(Department_ID) {
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    
	
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','GetSubDepartment.php?Department_ID='+Department_ID,true);
	    mm.send();
	}
    function AJAXP() {
	var data = mm.responseText; 
	document.getElementById('Sub_Department').innerHTML = data;	
    }
</script>


 <?php
    $Select_Assigned_Branches = mysqli_query($conn,"select * from tbl_Employee where employee_id = '$Employee_ID'");
    $no=mysqli_num_rows($Select_Assigned_Branches);
    if($no>0){
	while($row = mysqli_fetch_array($Select_Assigned_Branches)){
	    $Employee_Name = $row['Employee_Name'];
	    $Employee_Title = $row['Employee_Title'];
	}
    }else{
	$Employee_Name = 'Unknown Employee Name';
	$Employee_Title = 'Unknown Employee Title';
    }
?>
<center>
    <table width=70%>
	<tr><td>
	<fieldset>
            <legend align="center" ><b>EMPLOYEE PARTICULARS</b></legend><center>
	<table width=40%>
	    <tr>
	    <td><b>Employee Name</b></td><td><input type='text' disabled='disabled' size=30 value='<?php echo $Employee_Name; ?>'></td>
	</tr>
	<tr>
	    <td><b>Employee Title</b></td><td><input type='text' disabled='disabled' size=30 value='<?php echo $Employee_Title; ?>'></td>
	</tr>
	</center></table>
	</td>    
	</tr>
	
 
 
 <?php
	if(isset($_POST['submittedBranchForm'])){ 
                $Branch_ID = $_POST['Branch_ID'];
		
		$sql = "insert into tbl_branch_employee(Employee_ID,Branch_ID)
				values('$Employee_ID','$Branch_ID')";
		
		if(!mysqli_query($conn,$sql)){
		    $error = '1062yes';
		    if(mysql_errno()."yes" == $error){ 
			$controlforminput = "<b>Duplication! Employee Already Assigned To The Selected Branch </b>";
		    }else{
                        $controlforminput = "<b>Process Fail! Please Try Again</b>";
                    }
		}
		else {
		    $controlforminput = '<b>Process Successful</b>';
		}
	}
?>

 
 
<tr><td>
 <fieldset>
            <legend align="center" ><b>ASSIGNED BRANCH(ES)</b></legend>
<iframe width='100%' src='Assigned_Branch_iframe.php?Employee_ID=<?php echo $Employee_ID; ?>&AssignedClinics=AssignedClinicsThisPage' height=170px></iframe>
</center></td></tr>
 <tr><td style='text-align: center;'><span style='color: green; text-align: center;'><?php echo $controlforminput; ?></span></td></tr>
  <tr>
    <form action='#' method='post' name='myForm' id='myForm' enctype="multipart/form-data">
	<td style='text-align: center;'>
	    <table width=100%>
                <tr>
                    <td width=15%><b>Select Branch</b></td>
                    <td width=20%>
                        <?php if($Employee_Name == 'Unknown Employee Name' || $Employee_ID == 0){ ?>
                            <select name='Branch_ID' id='Branch_ID' disabled='disabled' required='required'>
                        <?php }else{ ?>
                            <select name='Branch_ID' id='Branch_ID' required='required'>
                        <?php } ?>
                            <option selected='selected'></option>
                            <?php
                                $data = mysqli_query($conn,"select Branch_ID, Branch_Name from tbl_branches");
                                while($row = mysqli_fetch_array($data)){
                                    echo '<option value='.$row['Branch_ID'].'>'.$row['Branch_Name'].'</option>';
                                }
                            ?>    
                        </select>
                    </td>
                    <td width=20%>
                        <?php if($Employee_Name == 'Unknown Employee Name' || $Employee_ID == 0){ ?>
                            <input type='submit' name='submit' id='submit' disabled='disabled' value='   ADD BRANCH   ' class='art-button-green'>
                        <?php }else{ ?>
                            <input type='submit' name='submit' id='submit' value='   ADD BRANCH   ' class='art-button-green'>
                        <?php } ?>
                        <input type='hidden' name='submittedBranchForm' value='true'/> 
                    </td>
                </tr>
            </table> 
	</td>
    </form>
</tr>
 </table></center> 

<?php
    include("./includes/footer.php");
?>