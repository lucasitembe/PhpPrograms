<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration']) || isset($_SESSION['userinfo']['Appointment_Works'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Appointment_Works'] != 'yes'){
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        }else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    } 

 
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' && !isset($_GET['HRWork'])){ 
                echo "<a href='employeepage.php?EmployeeManagement=EmployeeManagementThisPage' class='art-button-green'>BACK</a>";
        }
        if(isset($_GET['HRWork']) && $_GET['HRWork']=='true'){ 
            echo "<a href='human_resource.php?HRWork=HRWorkThisPage' class='art-button-green'>BACK</a>";
        }
    }
?>
<br/><br/>
<center>
    <table width=40%> 
	<tr> 
	    <td width=30%>
		<input type='text' name='Employee_Name' id='Employee_Name' placeholder='~~~~~~~~~~~~~~~~~~~~~Search Employee Name~~~~~~~~~~~~~~~~~~' style='text-align: center;' onclick='Get_Filtered_List()' onkeypress='Get_Filtered_List()' onkeyup='Get_Filtered_List()'>
	    </td>
	</tr>
    </table>
</center>
<br/>
<fieldset style='overflow-y: scroll; height: 350px;' id='List_Of_Employees_Area'>
    <legend align='center'><b>LIST OF INCOMPLETE REGISTERED EMPLOYEES</b></legend>
        <br/>
	<table width=100%>
            <!-- select all registered employee with some problems during the registrations-->
            <?php
                //control variables
                $control_employee_validity = 'valid';
                $privileges_validity = 'valid';
                $branch_validity = 'valid';
                $temp = 0;
                $sql_select = mysqli_query($conn,"select * from tbl_employee order by Employee_Name") or die(mysqli_error($conn));
                $Employee_Number = mysqli_num_rows($sql_select);
                if($Employee_Number > 0){
                    while($row = mysqli_fetch_array($sql_select)){
                        $Employee_ID = $row['Employee_ID'];
                        
                        //check for priveleges
                        $verify_privileges = mysqli_query($conn,"select Employee_ID from tbl_privileges where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                        $privileges_num = mysqli_num_rows($verify_privileges);
                        if($privileges_num <= 0){
                            $control_employee_validity = 'invalid';
                            $privileges_validity = 'invalid';
                        }
                        
                        //check for branches
                        $verify_branch = mysqli_query($conn,"select Employee_ID from tbl_branch_employee where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                        $branch_num = mysqli_num_rows($verify_branch);
                        if($branch_num <= 0){
                            $control_employee_validity = 'invalid';
                            $branch_validity = 'invalid';
                        }
                        
                        if($privileges_validity == 'invalid' && $branch_validity == 'invalid'){
                            $Problem_Title = '<H4>Problems</H4>';
                        }else{
                            $Problem_Title = '<H4>Problem</H4>';
                        }
                        
                        if($control_employee_validity == 'invalid'){
                            $temp++;
                            echo '<tr>';
                                echo '<td width=6% style="text-align: right;"><b>'.$temp.'.</b></td><td width=30%><b>Employee Name : </b>'.$row['Employee_Name'].'</td>';
                                echo '<td width=15%><b>Job Code : </b>'.$row['Employee_Job_Code'].'</td>';
                                echo '<td><b>Employee Type : </b>'.$row['Employee_Type'].'</td>';
                                echo '<td><b>Employee Title : </b>'.$row['Employee_Title'].'</td>';
                            echo '</tr><tr><td>'.$Problem_Title.'</td>';
                            
                            if($privileges_validity == 'invalid'){
                                    echo '<td style="text-align: left;">';
                                    echo '<h4 style = "color: #037CB0;">Missing System Access Privileges</h4>';
                                    echo '</td>';
                                    
                                    echo '<td style="text-align: letf;">';
                                    echo '<a href="userprivileges.php?Employee_ID='.$row['Employee_ID'].'&SetupAndConfig=SetupAndConfigThisPage'.((isset($_GET['HRWork']) && $_GET['HRWork']=='true')?'&HRWork=true':'').'" class="art-button-green">Assign Access Privileges</a>';
                                    echo '</td>';
                            }
                            if($branch_validity == 'invalid'){
                                    echo '<td style="text-align: left;">';
                                    echo '<h4 style = "color: #037CB0;">Missing Branch</h4>';
                                    echo '</td>';
                                    
                                    echo '<td style="text-align: left;">';
                                    echo '<a href="assignaccessbranch.php?Employee_ID='.$row['Employee_ID'].'&AssignAccessBranch=AssignAccessBranchThisPage'.((isset($_GET['HRWork']) && $_GET['HRWork']=='true')?'&HRWork=true':'').'" class="art-button-green">Assign Branch</a>';
                                    echo '</td>';
                            }
                            echo '</tr>';
                            echo '<tr><td colspan=5><hr></tr>';
                        }
                        
                        //reset all control variables
                        $control_employee_validity = 'valid';
                        $privileges_validity = 'valid';
                        $branch_validity = 'valid';
                    }
                }else{
                    echo "<h3>NO INCOMPLETE REGISTERED EMPLOYEES</h3>";
                }
            ?>
        </table>
</fieldset>

<script>
    function Get_Filtered_List() {
	var Employee_Name = document.getElementById("Employee_Name").value;
        if(window.XMLHttpRequest) {
                muObjectGetFilteredList = new XMLHttpRequest();
        }else if(window.ActiveXObject){ 
                muObjectGetFilteredList = new ActiveXObject('Micrsoft.XMLHTTP');
                muObjectGetFilteredList.overrideMimeType('text/xml');
        }
                
        muObjectGetFilteredList.onreadystatechange = function (){
                data = muObjectGetFilteredList.responseText;
                if (muObjectGetFilteredList.readyState == 4) {
                    document.getElementById('List_Of_Employees_Area').innerHTML = data;
                }
        }; //specify name of function that will handle server response........
                
        muObjectGetFilteredList.open('GET','Get_List_Of_Incomplete_Registered_Employees.php?Employee_Name='+Employee_Name,true);
        muObjectGetFilteredList.send();
    }
</script>
<?php
    include('./includes/footer.php');
?>