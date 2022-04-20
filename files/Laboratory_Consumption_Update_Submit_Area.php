<?php
    session_start();
    include("./includes/connection.php");


    if(isset($_SESSION['Laboratory_Consumption_ID'])){
        $Consumption_ID = $_SESSION['Laboratory_Consumption_ID'];
    }else{
        $Consumption_ID = 0;
    }

    if($Consumption_ID != 0 && $Consumption_ID != '' && $Consumption_ID != null){
        //check if there is at least one item
        $get_details = mysqli_query($conn,"select Consumption_Item_ID from tbl_consumption_items where
                                 Consumption_ID = '$Consumption_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($get_details);
        if($num > 0){
?>
            <input type='button' class='art-button-green' value='SUBMIT CONSUMPTION' onclick='Confirm_Submit_Consumption()'>
<?php
        }else{
            echo '';
        }
    }
?>