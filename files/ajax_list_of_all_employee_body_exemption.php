<?php
    
    include("./includes/connection.php");
    
    session_start();

    if(isset($_POST['Employee_Name'])){
        $Employee_Name = mysqli_real_escape_string($conn, $_POST['Employee_Name']);

        echo "<table class='table' id='list_of_all_employee'>
            <tr> 
                <td style='width:50px'><b>S/No</b></td>
                <td style='width:80%'><b>Employee Name</b></td> 
                <td style='width:50px'><b>Action</b></td>
            </tr>";
        
                $sql_select_employee_result=mysqli_query($conn,"SELECT Employee_Name,Employee_ID FROM tbl_employee WHERE Account_Status='active' AND Employee_Name LIKE '%$Employee_Name%' LIMIT 50") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_employee_result)>0){
                    $count=1;
                    while($employee_rows=mysqli_fetch_assoc($sql_select_employee_result)){
                    $Employee_Name=$employee_rows['Employee_Name'];
                    $Employee_ID=$employee_rows['Employee_ID'];
                    echo "
                        <tr>
                                <td>$count</td>
                                <td>$Employee_Name</td>
                                <td>
                                    <input type='button' class='art-button-green' onclick='select_employee_for_exemption(\"$Employee_ID\")' value='SELECT'/>
                                </td>
                            </tr>
                            ";
                    $count++;
                    }
                }
          
        echo "</table>";

    }

    if(isset($_POST['Employee_ID'])){
        $Employee_ID= mysqli_real_escape_string($conn,$_POST['Employee_ID']);
        $saved_by=$_SESSION['userinfo']['Employee_ID'];
        $sql_check_if_exist_result=mysqli_query($conn,"SELECT * FROM `tbl_exemption_report_employee` WHERE Employee_ID='$Employee_ID'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_check_if_exist_result)<=0){
            $sql_insert_employee_result=mysqli_query($conn,"INSERT INTO `tbl_exemption_report_employee`( `Employee_ID`, `saved_by`) VALUES('$Employee_ID','$saved_by')") or die(mysqli_error($conn));
        }
    }

    if(isset($_POST['exem_rep'])){
        echo "<table class='table' id='list_of_all_employee'>
        <tr>
            <td style='width:50px'><b>S/No</b></td>
            <td style='width:80%'><b>Employee Name</b></td>
            <td style='width:50px'><b>Action</b></td>
        </tr>";
        
            $sql_select_employee_result=mysqli_query($conn,"SELECT Employee_Name,exemption_report_employee_id FROM tbl_employee emp,tbl_exemption_report_employee mtemp WHERE emp.Employee_ID=mtemp.Employee_ID") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_employee_result)>0){
                $count=1;
                while($employee_rows=mysqli_fetch_assoc($sql_select_employee_result)){
                   $Employee_Name=$employee_rows['Employee_Name'];
                   $exemption_report_employee_id=$employee_rows['exemption_report_employee_id'];
                   echo "
                       <tr>
                            <td>$count</td>
                            <td>$Employee_Name</td>
                            <td>
                                <input type='button' class='art-button btn-danger' onclick='remove_employee_for_exemption(\"$exemption_report_employee_id\")' value='X'/>
                            </td>
                        </tr>
                        ";
                   $count++;
                }
            }
         
    echo "</table>";
    }

    if(isset($_POST['exemption_report_employee_id'])){
        $exemption_report_employee_id= mysqli_real_escape_string($conn,$_POST['exemption_report_employee_id']);
        mysqli_query($conn,"DELETE FROM `tbl_exemption_report_employee` WHERE exemption_report_employee_id='$exemption_report_employee_id'") or die(mysqli_error($conn));
    }