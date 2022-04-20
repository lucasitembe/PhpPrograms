 <?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Supervisor_Password'])){
        $Supervisor_Password = md5($_GET['Supervisor_Password']);
    }else{
        $Supervisor_Password = '';
    }
    
    if(isset($_GET['Supervisor_Username'])){
        $Supervisor_Username = $_GET['Supervisor_Username'];
    }else{
        $Supervisor_Username = '';
    }
    if($Supervisor_Password != '' && $Supervisor_Password != null && $Supervisor_Username != '' && $Supervisor_Username != null){
        $sql_authenticate = mysqli_query($conn,"select e.Employee_ID from tbl_branches b, tbl_branch_employee be, tbl_employee e, tbl_privileges p
                                            where b.branch_id = be.branch_id and
                                                e.employee_id = be.employee_id and
                                                    e.employee_id = p.employee_id and
                                                        p.Given_Username = '$Supervisor_Username' and
                                                            p.Given_Password  = '$Supervisor_Password' and
                                                                p.Session_Master_Priveleges = 'yes'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($sql_authenticate);
        if($num > 0){
            while($row = mysqli_fetch_array($sql_authenticate)){
                $_SESSION['Reagent_Open_Balance_Supervisor_ID'] = $row['Employee_ID'];
            }            
            $feedback = 'yes';
        }else{
            $feedback = 'no';
            unset($_SESSION['Reagent_Open_Balance_Supervisor_ID']);
        }        
    }
    echo $feedback;
    
?>