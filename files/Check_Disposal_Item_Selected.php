<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['Disposal_ID']) && isset($_GET['Item_ID'])){
        $Disposal_ID = $_SESSION['Disposal_ID'];
        
        //get item id
        $Item_ID = $_GET['Item_ID'];
        
        //check if item selected is already selected
        $sql_select = mysqli_query($conn,"select Disposal_Item_ID from tbl_Disposal_items where
                                        Disposal_ID = '$Disposal_ID' and
                                            Item_ID = '$Item_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($sql_select);
        
        if($num > 0){
            echo 'Yes';
        }else{
            echo 'No';
        }
        
    }else{
        echo 'No';
    }

?>