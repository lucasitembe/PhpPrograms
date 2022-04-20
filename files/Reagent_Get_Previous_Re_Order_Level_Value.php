 <?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = '';
    }
    
    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = '';
    }
    
    if($Sub_Department_ID != null && $Sub_Department_ID != '' && $Item_ID != '' && $Item_ID != null){
        $sql_select = mysqli_query($conn,"select Reorder_Level from tbl_reagent_items_balance where
                                    Sub_Department_ID = '$Sub_Department_ID' and
                                        Item_ID = '$Item_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($sql_select);
        if($num > 0){
            while($row = mysqli_fetch_array($sql_select)){
                $Reorder_Level = $row['Reorder_Level'];
            }
        }else{
            $Reorder_Level = '';
        }
    }else{
        $Reorder_Level = '';
    }
    echo $Reorder_Level;
?>