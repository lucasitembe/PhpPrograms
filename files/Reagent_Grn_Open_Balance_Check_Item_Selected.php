 <?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['Reagent_Grn_Open_Balance_ID']) && isset($_GET['Item_ID'])){
        $Grn_Open_Balance_ID = $_SESSION['Reagent_Grn_Open_Balance_ID'];
        
        //get item id
        $Item_ID = $_GET['Item_ID'];
        
        //check if item selected is already selected
        $sql_select = mysqli_query($conn,"select Open_Balance_Item_ID from tbl_reagent_grn_open_balance_items where
                                        Grn_Open_Balance_ID = '$Grn_Open_Balance_ID' and
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