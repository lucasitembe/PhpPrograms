<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = 0;
    }

    //get sub department
    $select_sub_department = mysqli_query($conn,"select * from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_sub_department);
    if($no > 0){
        echo "<select name='Sub_Department_ID' id='Sub_Department_ID' required='required'>";
            $select_Supplier = mysqli_query($conn,"select * from tbl_sub_department where
                                                Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                    while($row = mysqli_fetch_array($select_Supplier)){
                        echo "<option value='".$row['Sub_Department_ID']."'>".$row['Sub_Department_Name']."</option>";
                    }
        echo '</select>';
    }
?>