<?php
    session_start();
    include('./includes/connection.php');
    
    //get employee name
    if(isset($_GET['Employee_Name'])){
        $Employee_Name = $_GET['Employee_Name'];
    }else{
        $Employee_Name = '';
    }

?>
<legend align='center'>LIST OF INCOMPLETE REGISTERED EMPLOYEES</legend>
    <table width=100%>
        <!-- select all registered employee with some problems during the registrations-->
        <?php
            //control variables
            $control_employee_validity = 'valid';
            $privileges_validity = 'valid';
            $branch_validity = 'valid';
            $temp = 0;
            $sql_select = mysqli_query($conn,"select * from tbl_employee where Employee_Name like '%$Employee_Name%' order by Employee_Name") or die(mysqli_error($conn));
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
                                echo '<a href="userprivileges.php?Employee_ID='.$row['Employee_ID'].'&SetupAndConfig=SetupAndConfigThisPage" class="art-button-green">Assign Access Privileges</a>';
                                echo '</td>';
                        }
                        if($branch_validity == 'invalid'){
                                echo '<td style="text-align: left;">';
                                echo '<h4 style = "color: #037CB0;">Missing Branch</h4>';
                                echo '</td>';
                                
                                echo '<td style="text-align: left;">';
                                echo '<a href="assignaccessbranch.php?Employee_ID='.$row['Employee_ID'].'&AssignAccessBranch=AssignAccessBranchThisPage" class="art-button-green">Assign Branch</a>';
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