 <?php
    session_start();
    include("./includes/connection.php");
    
    //get store need (Based on storage session name)
    if(isset($_SESSION['Departmental_Open_Balance_Control'])){
       $Sub_Department_ID = $_SESSION['Departmental_Open_Balance_Control'];
    }else{
       $Sub_Department_ID = 0;
    }
    
    //get supervisor authentication id
    if(isset($_SESSION['Reagent_Open_Balance_Supervisor_ID'])){
        $Supervisor_ID = $_SESSION['Reagent_Open_Balance_Supervisor_ID'];
    }else{
        $Supervisor_ID = 0;
    }
    
    //get Grn Open Balance ID from the session
    if(isset($_SESSION['Reagent_Grn_Open_Balance_ID'])){
        $Grn_Open_Balance_ID = $_SESSION['Reagent_Grn_Open_Balance_ID'];
    }else{
        $Grn_Open_Balance_ID = 0;
    }
    
    
    if($Sub_Department_ID != '' && $Sub_Department_ID != null && $Supervisor_ID != 0 && $Supervisor_ID != '' && $Supervisor_ID != null && $Grn_Open_Balance_ID != '' && $Grn_Open_Balance_ID != null && $Grn_Open_Balance_ID != 0){
        //select all items from tbl_grn_open_balance_items based on Grn_Open_Balance_ID
        $sql_select = mysqli_query($conn,"select Item_ID, Item_Quantity from tbl_reagent_grn_open_balance_items where
                                    Grn_Open_Balance_ID = '$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($sql_select);
        
        if($num >0){
            while($row = mysqli_fetch_array($sql_select)){
                //get Item ID & Item Quantity
                $Item_ID = $row['Item_ID'];
                $Item_Quantity = $row['Item_Quantity'];
                
                //$num .= $Item_ID.' - '.$Item_Quantity." , ";
                //get previous quantity item (Item Balance)
                $sql_quantity = mysqli_query($conn,"select Item_Balance from tbl_reagent_items_balance
                                                where Sub_Department_ID = '$Sub_Department_ID' and
                                                    Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                $no = mysqli_num_rows($sql_quantity);
                if($no > 0){
                    while($data = mysqli_fetch_array($sql_quantity)){
                        $Item_Balance = $data['Item_Balance'];
                    }
                }else{
                    $Item_Balance = 0;
                }
                
                //insert Item Balance to tbl_items_balance_history table
                $insert = mysqli_query($conn,"insert into tbl_reagent_items_balance_history(Item_ID,Item_Balance,Grn_Open_Balance_ID,Sub_Department_ID)
                                        values('$Item_ID','$Item_Balance','$Grn_Open_Balance_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
                
                if($insert){
                    //update Item Balance
                    $update = mysqli_query($conn,"update tbl_reagent_items_balance set Item_Balance = '$Item_Quantity' where
                                            Sub_Department_ID = '$Sub_Department_ID' and
                                                Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                }                
            }
            
            //update grn open balance table
            mysqli_query($conn,"update tbl_reagent_grn_open_balance set Grn_Open_Balance_Status = 'saved',
                            Supervisor_ID = '$Supervisor_ID' where
                                Grn_Open_Balance_ID = '$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
            echo 'yes';
        }
    }else{
        echo 'no';
    }
?>