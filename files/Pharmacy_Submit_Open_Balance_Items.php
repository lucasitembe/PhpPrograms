<?php
    session_start();
    include("./includes/connection.php");
     include_once("./functions/items.php");

    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $employee_name = $_SESSION['userinfo']['Employee_Name'];

    function getItemName($Item_ID){
        $result = mysqli_query($conn,"Select Product_Name from tbl_items where Item_ID='$Item_ID'") or die(mysqli_error($conn));
        $data = mysql_fetch_object($result);
        return $data->Product_Name;
    }
    
    //get store need (Based on storage session name)
    if(isset($_SESSION['Pharmacy_ID'])){
        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
        $Sub_Department_ID = 0;
    }
    
    //get supervisor authentication id
    if(isset($_SESSION['Open_Balance_Supervisor_ID'])){
        $Supervisor_ID = $_SESSION['Open_Balance_Supervisor_ID'];
    }else{
        $Supervisor_ID = 0;
    }
    
    //get Grn Open Balance ID from the session
    if(isset($_SESSION['Pharmacy_Grn_Open_Balance_ID'])){
        $Grn_Open_Balance_ID = $_SESSION['Pharmacy_Grn_Open_Balance_ID'];
    }else{
        $Grn_Open_Balance_ID = 0;
    }
    
    if($Sub_Department_ID != '' && $Sub_Department_ID != null && $Supervisor_ID != 0 && $Supervisor_ID != '' && $Supervisor_ID != null && $Grn_Open_Balance_ID != '' && $Grn_Open_Balance_ID != null && $Grn_Open_Balance_ID != 0){
        //select all items from tbl_grn_open_balance_items based on Grn_Open_Balance_ID
        $sql_select = mysqli_query($conn,"select Item_ID, Item_Quantity, Buying_Price from tbl_grn_open_balance_items where
                                    Grn_Open_Balance_ID = '$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($sql_select);

        $HAS_ERROR = false;
        Start_Transaction();

        if($num >0){

            $Product_Array = array(); 

            while($row = mysqli_fetch_array($sql_select)){
                //get Item ID & Item Quantity
                $Item_ID = $row['Item_ID'];
                $Item_Quantity = $row['Item_Quantity'];
                $Buying_Price = $row['Buying_Price'];
                
                //$num .= $Item_ID.' - '.$Item_Quantity." , ";
                //get previous quantity item (Item Balance)
                $sql_quantity = mysqli_query($conn,"select Item_Balance from tbl_items_balance
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

                ////gaccounting data ///////
            $diff = $Item_Balance-$Item_Quantity;

            if($diff != 0){
                if($diff > 0){
                    //if ehms balance is greater than physical balance then DEBIT Transaction
                    $debit = 'Pharmacy-COGS'; $credit = 'Pharmacy-INVENTORY';
                    $qty = $diff;
                } else if($diff < 0){
                    //if ehms balance is greater than physical balance then CREDIT Transaction
                    $credit = 'Pharmacy-COGS'; $debit = 'Pharmacy-INVENTORY';
                    $qty = $diff;
                    $diff = $diff*(-1); //remove minus sign of neg diff
                    
                }

                $Product_Name_Array = array(
                    'ref_no' => $Grn_Open_Balance_ID,
                    'source_name' => 'ehms_physical_counting',
                    'comment' => getItemName($Item_ID) . ", " . $qty . " item(s) @ " . number_format($Buying_Price,2) . " Tsh.",
                    'debit_entry_ledger'=> $debit,
                    'credit_entry_ledger'=> $credit,
                    'sub_total' => $diff*$Buying_Price,
                    'source_id' => $Item_ID,
                    'Employee_Name' => $employee_name,
                    'Employee_ID' => $Employee_ID,
                );
                array_push($Product_Array, $Product_Name_Array);
            }
        

        /////// end of gaccounting data //////
                
                //insert Item Balance to tbl_items_balance_history table
                $insert = mysqli_query($conn,"insert into tbl_items_balance_history(Item_ID,Item_Balance,Grn_Open_Balance_ID,Sub_Department_ID)
                                        values('$Item_ID','$Item_Balance','$Grn_Open_Balance_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
                
                if($insert){
                    //update Item Balance
                    $update = mysqli_query($conn,"update tbl_items_balance set Item_Balance = '$Item_Quantity' where
                                            Sub_Department_ID = '$Sub_Department_ID' and
                                                Item_ID = '$Item_ID'") or die(mysqli_error($conn));

                    if($update){
                        //insert data into tbl_stock_ledger_controler for auditing
                        $insert_audit = mysqli_query($conn,"insert into tbl_stock_ledger_controler(
                                                        Item_ID, Sub_Department_ID, Movement_Type, 
                                                        Pre_Balance, Post_Balance, Movement_Date, Movement_Date_Time, Document_Number)

                                                        values('$Item_ID','$Sub_Department_ID','Open Balance',
                                                            '$Item_Balance','$Item_Quantity',(select now()),(select now()),'$Grn_Open_Balance_ID')") or die(mysqli_error($conn));
                        /*if($insert_audit){
                            //update stock value details
                            $get_items_details = mysqli_query($conn,"select Last_Buy_Price from tbl_items where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                            $num_items_details = mysqli_num_rows($get_items_details);
                            if($num_items_details > 0){
                                while ($dt_items = mysqli_fetch_array($get_items_details)) {
                                    $Last_Buy_Price = $dt_items['Last_Buy_Price'];
                                }
                            }else{
                                $Last_Buy_Price = 0;
                            }

                            //calculate stock value => formula ((QtyA * pA) + (QtyB * pB) / (QtyA + QtyB))
                            $Last_P = ((($Item_Balance * $Last_Buy_Price) + ($Item_Quantity * $Buying_Price)) / ($Item_Balance + $Item_Quantity));

                            //update Last_Buy_Price
                            $update_previous_rec = mysqli_query($conn,"update tbl_items set Last_Buy_Price = '$Last_P' where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                        }*/
                        if($Item_Quantity > 0){
                            //update item status to available
                            mysqli_query($conn,"update tbl_items set Status = 'Available' where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                        }
                    } else {
                        $HAS_ERROR = true;
                    }
                }  else {
                    $HAS_ERROR = true;
                }              
            }

            $endata = json_encode($Product_Array);
            
            $acc = gAccJournalEntry($endata);
        //    echo $acc;
        //    exit();
            if ($acc != "success") {
                $HAS_ERROR = true;
            }
            
            //update grn open balance table
            $result = mysqli_query($conn,"update tbl_grn_open_balance set Grn_Open_Balance_Status = 'saved',
                				    Supervisor_ID = '$Supervisor_ID',
                				    Saved_Date_Time = (select now())
                				    where Grn_Open_Balance_ID = '$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
            
            if($result){
                /*unset($_SESSION['Pharmacy_Grn_Open_Balance_ID']);
                echo 'yes';*/
                $HAS_ERROR = false;
            } else {
                $HAS_ERROR = true;
            }
        }
    }else{
        //echo 'no';
        $HAS_ERROR = true;
    }

    if(!$HAS_ERROR){
         Commit_Transaction();
        unset($_SESSION['Pharmacy_Grn_Open_Balance_ID']);
        echo 'yes';
    } else {
        Rollback_Transaction();
        echo 'no';
    }
?>