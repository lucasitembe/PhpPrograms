<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['Pharmacy'])){
        $Sub_Department_Name = $_SESSION['Pharmacy'];
    }else{
        $Sub_Department_Name = '';
    }
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
            $Employee_ID = 0;
    }
    
    if($Sub_Department_Name != null && $Sub_Department_Name != ''){
        $sql_select = mysqli_query($conn,"select Sub_Department_ID from tbl_quick_requisition where
                                    sub_department_id = (select sub_department_id from tbl_sub_department where sub_department_name = '$Sub_Department_Name' limit 1)") or die(mysqli_error($conn));
        $num = mysqli_num_rows($sql_select);
        if($num > 0){
            $sql_select2 = mysqli_query($conn,"select Sub_Department_ID from tbl_quick_requisition where
                                        sub_department_id = (select sub_department_id from tbl_sub_department where sub_department_name = '$Sub_Department_Name' limit 1) and
                                            Employee_ID <> '$Employee_ID'") or die(mysqli_error($conn));
            $no = mysqli_num_rows($sql_select2);
            if($no > 0){
                echo 'yes';
            }else{
                echo 'yes2';
            }
        }else{
            echo 'no';
        }
    }else{
        echo 'no';
    }
    
?>