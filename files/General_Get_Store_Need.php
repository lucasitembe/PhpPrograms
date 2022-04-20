<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Store_Need'])){
        $Store_Need = $_GET['Store_Need'];
    }else{
        $Store_Need = 0;
    }

    //get sub department
    $select_sub_department = mysqli_query($conn,"select * from tbl_sub_department where Sub_Department_ID = '$Store_Need'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_sub_department);
    if($no > 0){
        echo "<select name='Store_Need' id='Store_Need' required='required'>";
            $select_Supplier = mysqli_query($conn,"select * from tbl_sub_department where
                                                Sub_Department_ID = '$Store_Need'") or die(mysqli_error($conn));
                    while($row = mysqli_fetch_array($select_Supplier)){
                        echo "<option value='".$row['Sub_Department_ID']."'>".$row['Sub_Department_Name']."</option>";
                    }
        echo '</select>';
    }
?>