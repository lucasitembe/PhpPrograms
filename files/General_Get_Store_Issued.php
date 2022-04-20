<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Store_Issue'])){
        $Store_Issue = $_GET['Store_Issue'];
    }else{
        $Store_Issue = 0;
    }
    

    //get sub department
    $select_sub_department = mysqli_query($conn,"select * from tbl_sub_department where Sub_Department_ID = '$Store_Issue'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_sub_department);
    if($no > 0){
        echo "<select name='Store_Issue' id='Store_Issue' required='required'>";
            $select_Supplier = mysqli_query($conn,"select * from tbl_sub_department where
                                                Sub_Department_ID = '$Store_Issue'") or die(mysqli_error($conn));
                    while($row = mysqli_fetch_array($select_Supplier)){
                        echo "<option value='".$row['Sub_Department_ID']."'>".$row['Sub_Department_Name']."</option>";
                    }
        echo '</select>';
    }
?>