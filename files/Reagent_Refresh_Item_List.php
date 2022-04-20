 <?php
    include("./includes/connection.php");
    if(isset($_GET['Sub_Department_ID'])){
            $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
            $Sub_Department_ID = 0;
    }
    
    if(isset($_GET['Sub_Department_Type'])){
            $Sub_Department_Type = $_GET['Sub_Department_Type'];
    }else{
            $Sub_Department_Type = 0;
    }
    
    $counter = 0;
    if(isset($_GET['Sub_Department_ID']) && isset($_GET['Sub_Department_Type'])){
        $select = mysqli_query($conn,"select Item_ID from tbl_reagents_items") or die(mysqli_error($conn));
        while($row = mysqli_fetch_array($select)){
            $Item_ID = $row['Item_ID'];
            //check if selected item is available (Based on sub department id submitted)
            $select_details = mysqli_query($conn,"select Item_ID from tbl_reagent_items_balance where
                                            Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select_details);
            if($num == 0){
                    //update list by add selected item
                    $insert = mysqli_query($conn,"insert into tbl_reagent_items_balance(Item_ID,Sub_Department_ID,Sub_Department_Type)
                                            values('$Item_ID','$Sub_Department_ID','$Sub_Department_Type')") or die(mysqli_error($conn));
                    if($insert){
                            $counter = $counter + 1;
                    }
            }
        }
    }
    if($counter == 0){
        echo "no item updated";
    }elseif($counter > 1){
        echo $counter." items updated successfully";
    }else{
        echo $counter." item updated successfully";        
    }
?>