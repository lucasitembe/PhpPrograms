<?php
    @session_start();
    include("./includes/connection.php");
    
    
    if(isset($_SESSION['Pharmacy_Requisition_ID'])){
        $Requisition_ID = $_SESSION['Pharmacy_Requisition_ID'];
    }else{
        $Requisition_ID = 0;
    }
    
    if($Requisition_ID != 0){
        $select_store_issue = mysqli_query($conn,"select Store_Issue from tbl_requisition where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select_store_issue);
        
        if($num > 0){
            while($row = mysqli_fetch_array($select_store_issue)){
                $Store_Issue = $row['Store_Issue'];
            }
        }else{
            $Store_Issue = 0;
        }
    }else{
        $Store_Issue = 0;
    }
    
    //get sub department
    $select_sub_department = mysqli_query($conn,"select * from tbl_sub_department where Sub_Department_ID = '$Store_Issue'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_sub_department);
    if($no > 0){
        echo "<select name='Store_Issue' id='Store_Issue' required='required'>";
        $Current_Sub_Department = $_SESSION['Storage'];
            $select_Supplier = mysqli_query($conn,"select * from tbl_sub_department where
                                            Sub_Department_Name <> '$Current_Sub_Department' and
                                                Sub_Department_ID = '$Store_Issue'") or die(mysqli_error($conn));
                    while($row = mysqli_fetch_array($select_Supplier)){
                            echo "<option value='".$row['Sub_Department_ID']."'>".$row['Sub_Department_Name']."</option>";
                    }
            echo '</select>';
    }
?>