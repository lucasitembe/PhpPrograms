<?php
    include 'db-config.php';

    class StoreRepo extends DBConfig{
        public $db_connect;

        function __construct(){
            $this->db_connect = $this->connect();
        }

        private function queryExecuter($sql_query){
            $result = array();
            while($data = $sql_query->fetch_assoc()){ array_push($result,$data); }
            mysqli_free_result($sql_query);
            return json_encode($result);
        }

        public function readItemClassification() {
            return json_encode(array(
                array("Name" => "Pharmaceuticals","Description" => "Pharmaceuticals"),
                array("Name" => "Disposables","Description" => "Disposables"),
                array("Name" => "Dental Materials","Description" => "Dental Materials"),
                array("Name" => "Radiology Materials","Description" => "Radiology Materials"),
                array("Name" => "Laboratory Materials","Description" => "Laboratory Materials"),
                array("Name" => "Stationeries","Description" => "Stationeries")
            ));
        }


        public function readItems($Sub_Department_ID,$Product_Name,$classification){
            $filter = "";
            $filter .= (strlen($Product_Name) > 0) ? " AND ti.Product_Name LIKE '%$Product_Name%' " : "";
            $filter .= ($classification != "all") ? " AND ti.Classification = '$classification' " : "";
            $query = $this->db_connect->query("SELECT ti.Product_Name,ti.Unit_Of_Measure,tib.Item_Balance,ti.Item_ID FROM tbl_items AS ti,tbl_items_balance AS tib WHERE ti.Can_Be_Stocked = 'yes' AND tib.Sub_Department_ID = $Sub_Department_ID $filter AND ti.Item_ID = tib.Item_ID AND ti.Status = 'Available' LIMIT 30") or die($this->db_connect->errno);
            return $this->queryExecuter($query);
        }

        public function readAdjustmentReasons($status){
            $query = $this->db_connect->query("SELECT * FROM tbl_adjustment") or die($this->db_connect->errno);
            return $this->queryExecuter($query);
        }

        public function createNewAdjustment($Sub_Department_ID,$Employee_ID,$Disposal_Description,$Branch_ID,$reason_for_adjustment){
            $query = $this->db_connect->query("INSERT INTO tbl_disposal (Disposal_Status,Sub_Department_ID,Employee_ID,Created_Date,Created_Date_And_Time,Disposal_Description,Branch_ID,reason_for_adjustment) VALUES ('pending','$Sub_Department_ID',$Employee_ID,NOW(),NOW(),'$Disposal_Description','$Branch_ID','$reason_for_adjustment')") or die($this->db_connect->errno." -Failed to create new adjustment.");
            return ($query) ? $this->db_connect->insert_id : "Failed to create new adjustment.";
        }

        public function addItemToAdjust($Item_ID,$adjustment_number){
            $check_if_item_already_added = $this->db_connect->query("SELECT * FROM tbl_disposal_items WHERE Disposal_ID = '$adjustment_number' AND Item_ID = '$Item_ID'") or die($this->db_connect->errno." Failed to check if item exists");
            if($check_if_item_already_added->num_rows > 0){
                return 300;
            }else{
                $add_item = $this->db_connect->query("INSERT INTO tbl_disposal_items (Item_ID,Disposal_ID) VALUES ('$Item_ID','$adjustment_number')") or die($this->db_connect->errno." Failed to add item to adjust");
                return ($add_item) ? 100 : " -Failed to add item";
            }
        }

        public function readItemsToAdjust($adjustment_number,$Sub_Department_ID){
            $query = $this->db_connect->query("SELECT * FROM tbl_disposal_items AS tdi,tbl_items AS ti,tbl_items_balance AS tib WHERE tdi.Disposal_ID = '$adjustment_number' AND tdi.Item_ID = ti.Item_ID AND tdi.Item_ID = tib.Item_ID AND tib.Sub_Department_ID = '$Sub_Department_ID' ") or die($this->db_connect->errno." Failed to fetch items");
            return $this->queryExecuter($query);
        }

        public function removeItemsFromAdjust($adjustment_number,$Item_ID){
            $query = $this->db_connect->query("DELETE FROM tbl_disposal_items WHERE Disposal_ID = '$adjustment_number' AND Item_ID = '$Item_ID'") or die($this->db_connect->errno." Can remove item from adjusted list");
            return ($query) ? 100 : " -Failed to remove item from adjustment";
        }

        public function updateAdjustQty($qty_adjusted,$Adjusted_ID){
            $query = $this->db_connect->query("UPDATE tbl_disposal_items SET Quantity_Disposed = '$qty_adjusted' WHERE Disposal_Item_ID = '$Adjusted_ID'") or die($this->db_connect->errno." 1 Fail to update item from adjusted list");
            return ($query) ? 100 : " -Fail to update item from adjusted list";
        }

        public function saveRemarkForAdjustment($remark,$Adjusted_ID){
            $query = $this->db_connect->query("UPDATE tbl_disposal_items SET Item_Remark = '$remark' WHERE Disposal_Item_ID = '$Adjusted_ID'") or die($this->db_connect->errno." 1 Fail to update item remark");
            return ($query) ? 100 : " -Fail to update item remark";
        }

        public function saveBatchForAdjustment($batch,$Adjusted_ID){
            $query = $this->db_connect->query("UPDATE tbl_disposal_items SET batch_no = '$batch' WHERE Disposal_Item_ID = '$Adjusted_ID'") or die($this->db_connect->errno." 1 Fail to update item Batch");
            return ($query) ? 100 : " -Fail to update item Batch";
        }

        public function saveExpireDateForAdjustment($expire_date,$Adjusted_ID){
            $query = $this->db_connect->query("UPDATE tbl_disposal_items SET expire_date = '$expire_date' WHERE Disposal_Item_ID = '$Adjusted_ID'") or die($this->db_connect->errno." 1 Fail to update item Expire Date");
            return ($query) ? 100 : " -Fail to update item Expire Date";
        }

        public function readEmployeeDetails($Employee_ID){
            $query = $this->db_connect->query("SELECT * FROM tbl_employee WHERE Employee_ID=$Employee_ID") or die($this->db_connect->errno." - Failed to fetch patient details");
            return $this->queryExecuter($query);
        }

        # not yet tested
        public function readAdjustments($Sub_Department_ID,$Document_Number,$Status,$Start_Date,$End_Date){
            $filter = "";

            $filter .= ($Document_Number == "all") ? "" : " AND td.Disposal_ID = $Document_Number ";
            $filter .= ($Status == "all") ? "" : " AND td.Disposal_Status = '$Status' ";
            $filter .= ($Start_Date == "" || $Start_Date == NULL) ? "" : " AND Created_Date BETWEEN '$Start_Date' AND '$End_Date'";

            $query = $this->db_connect->query("SELECT * FROM tbl_disposal AS td,tbl_employee AS te, tbl_sub_department AS tsd,tbl_adjustment AS ta WHERE td.Sub_Department_ID = $Sub_Department_ID AND te.Employee_ID = td.Employee_ID AND ta.id = td.reason_for_adjustment AND tsd.Sub_Department_ID = $Sub_Department_ID $filter ") or die($this->db_connect->errno);
            return $this->queryExecuter($query);
        }

        # deduct purposes  
        public function updateQuantityByStore($Item_ID,$Quantity,$Sub_Department_ID,$Employee_ID,$Movement_Type,$Document_Number){
            $Item_ID_ = explode(',',$Item_ID);
            $Quantity_ = explode(',',$Quantity);

            for ($i=0; $i < sizeof($Item_ID_) ; $i++) { 
                $Item_ID__ = $Item_ID_[$i];
                $select_item_balance_ = $this->db_connect->query("SELECT Item_Balance FROM tbl_items_balance WHERE Sub_Department_ID = '$Sub_Department_ID' AND Item_ID = '$Item_ID__'") or die($this->db_connect->errno. "Error while fetching last balance");
                $current_item_balance = $select_item_balance_->fetch_assoc()['Item_Balance'];

                if($Quantity_[$i] <= $current_item_balance){
                    $new_balance = $current_item_balance - $Quantity_[$i];
                    $update_balance = $this->db_connect->query("UPDATE tbl_item_balance SET Item_Balance = $new_balance WHERE Sub_Department_ID = $Sub_Department_ID AND Item_ID = $Item_ID__ ") or die($this->db_connect->errno." Error while updating stock");
                    if($update_balance){
                        $get_last_transaction = $this->db_connect->query("SELECT Pre_Balance,Post_Balance FROM tbl_stock_ledger_controler WHERE Sub_Department_ID = '$Sub_Department_ID' AND Item_ID = $Item_ID__ ORDER BY Controler_ID DESC LIMIT 1 ") or die($this->db_connect->errno." - Failed to read items stock Control");
                        $Balance_After = $get_last_transaction->fetch_assoc()['Post_Balance'] - $Quantity_[$i];
                        $Post_Balance = $get_last_transaction->fetch_assoc()['Post_Balance'];

                        if($get_last_transaction->num_rows > 0){
                            $update_stock_ledger_control = $this->db_connect->query("INSERT INTO tbl_stock_ledger_controler (Item_ID,Movement_Type,Sub_Department_ID,Pre_Balance,Post_Balance,Document_Number,Movement_Date,Movement_Date_Time) VALUES ('$Item_ID_','$Movement_Type','$Sub_Department_ID','$Post_Balance','$Balance_After','$Document_Number',NOW(),NOW())") or die($this->db_connect->errno." - Failed to update stock ledger controls");
                        }
                    }
                }
            }
        }

        /** 
         * @Object 
         * @Sample
         *  Array ( [0] => Array ( [Sub_Department_ID] => 38 ) [1] => Array ( [Item_Details] => Array ( [0] => Array ( [Item_ID] => 670 [Quantity] => 1 ) [1] => Array ( [Item_ID] => 671 [Quantity] => 2 ) [2] => Array ( [Item_ID] => 672 [Quantity] => 3 ) [3] => Array ( [Item_ID] => 673 [Quantity] => 4 ) ) ) [2] => Array ( [Receipts] => Array ( [0] => 29519 [1] => 29520 [2] => 29521 [3] => 29522 ) ) [3] => Array ( [Movement_Type] => Dispense ) )
         * **/
        public function deductItemQuantity($Items_Details){
            $count = 0;
            $Sub_Department_ID = $Items_Details[0]['Sub_Department_ID'];
            $Registration_ID = isset($Items_Details[0]['Registration_ID']) ? $Items_Details[0]['Registration_ID'] : null;

            for($i = 0;$i < sizeof($Items_Details[1]['Item_Details']);$i++){
                $Select_Item_Balance = $this->db_connect->query("SELECT Item_Balance FROM tbl_items_balance WHERE Sub_Department_ID = $Sub_Department_ID AND Item_ID = ".$Items_Details[1]['Item_Details'][$i]['Item_ID']." ");
                $Store_Balance = $Select_Item_Balance->fetch_assoc()['Item_Balance'];
                if($Store_Balance >= $Items_Details[1]['Item_Details'][$i]['Quantity']){
                    $Update_Balance = (int)$Store_Balance - (int)$Items_Details[1]['Item_Details'][$i]['Quantity'];
                    $Update_Item_Balance_Query = $this->db_connect->query("UPDATE tbl_items_balance SET Item_Balance = $Update_Balance WHERE Sub_Department_ID = $Sub_Department_ID AND Item_ID = ".$Items_Details[1]['Item_Details'][$i]['Item_ID']." ") or die($this->db_connect->errno.": Fail to update item balance");
                    if($Update_Item_Balance_Query){
                        $Get_last_transaction = $this->db_connect->query("SELECT Pre_Balance,Post_Balance FROM tbl_stock_ledger_controler WHERE Sub_Department_ID = '$Sub_Department_ID' AND Item_ID = ".$Items_Details[1]['Item_Details'][$i]['Item_ID']." ORDER BY Controler_ID DESC LIMIT 1 ") or die($this->db_connect->errno." - Failed to read items stock Control");
                        $Post_Balance = $Get_last_transaction->fetch_assoc()['Post_Balance'];
                        $Balance_After = $Post_Balance - (int)$Items_Details[1]['Item_Details'][$i]['Quantity'];
                        if($Get_last_transaction->num_rows > 0){ 
                            $update_stock_balance_in_stock_ledger_control = $this->db_connect->query("INSERT INTO tbl_stock_ledger_controler (Item_ID,Movement_Type,Sub_Department_ID,Pre_Balance,Post_Balance,Document_Number,Movement_Date,Movement_Date_Time,Registration_ID) VALUES ('".$Items_Details[1]['Item_Details'][$i]['Item_ID']."','".$Items_Details[3]['Movement_Type']."','$Sub_Department_ID','$Post_Balance','$Balance_After','".$Items_Details[2]['Receipts'][$i]."',NOW(),NOW(),'$Registration_ID')") or die($this->db_connect->error);
                            if($update_stock_balance_in_stock_ledger_control){
                                $count++;
                            }
                        }
                    }
                }
            }
            return ($count > 0) ? 100 : 200;
        }

        public function SubmittedAdjustment($Item_IDs,$Item_ID_Qty,$adjustment_number,$Employee_ID,$Sub_Department_ID,$adjust,$Movement_Type){
            $Items_ID = explode(',',$Item_IDs);
            $Quantity = explode(',',$Item_ID_Qty);
            $hasError = FALSE;

            for($i = 0;$i < sizeof($Items_ID); $i++){
                $get_last_transaction = $this->db_connect->query("SELECT Pre_Balance,Post_Balance FROM tbl_stock_ledger_controler WHERE Sub_Department_ID = '$Sub_Department_ID' AND Item_ID = ".$Items_ID[$i]." ORDER BY Controler_ID DESC LIMIT 1 ") or die($this->db_connect->errno." - Failed to read items stock Control");

                if($get_last_transaction->num_rows > 0){
                    $values = $get_last_transaction->fetch_assoc();
                    $adjusted = $adjust == "minus" ? $values['Post_Balance'] - $Quantity[$i] : $values['Post_Balance'] + $Quantity[$i];

                    $get_details_tbl_items_balance = $this->db_connect->query("SELECT Item_Balance FROM tbl_items_balance WHERE Sub_Department_ID = '$Sub_Department_ID' AND Item_ID = ".$Items_ID[$i]."");
                    $current_balance = $get_details_tbl_items_balance->fetch_assoc()['Item_Balance'];

                    $balance_after_adjustment = $adjust == "minus" ? $current_balance - $Quantity[$i] : $current_balance + $Quantity[$i];
                    $update_balance = $this->db_connect->query("UPDATE tbl_items_balance SET Item_Balance = '$balance_after_adjustment' WHERE Sub_Department_ID = '$Sub_Department_ID' AND Item_ID = ".$Items_ID[$i]." ") or die($this->db_connect->errno." could not update balance");
                    if($update_balance){
                        $pre_balance=$values['Post_Balance'];
                        $Item_ID_ = $Items_ID[$i];
                        $update_stock_balance_in_stock_ledger_control = $this->db_connect->query("INSERT INTO tbl_stock_ledger_controler (Item_ID,Movement_Type,Sub_Department_ID,Pre_Balance,Post_Balance,Document_Number,Movement_Date,Movement_Date_Time) VALUES ('$Item_ID_','$Movement_Type','$Sub_Department_ID','$pre_balance','$adjusted','$adjustment_number',NOW(),NOW())") or die($this->db_connect->error);
                        if(!$update_stock_balance_in_stock_ledger_control){
                            $hasError = TRUE;
                        }else{
                            $this->db_connect->query("UPDATE tbl_disposal SET Disposal_Status = 'saved',Disposed_Date = NOW() WHERE Disposal_ID = '$adjustment_number'");
                        }
                    }

                }else{
                    $adjusted = $adjust == "minus" ? 0 - $Quantity[$i] : 0 + $Quantity[$i];

                    $get_details_tbl_items_balance = $this->db_connect->query("SELECT Item_Balance FROM tbl_items_balance WHERE Sub_Department_ID = '$Sub_Department_ID' AND Item_ID = ".$Items_ID[$i]."");
                    $current_balance = $get_details_tbl_items_balance->fetch_assoc()['Item_Balance'];

                    $balance_after_adjustment = $adjust == "minus" ? 0 - $Quantity[$i] : 0 + $Quantity[$i];
                    $update_balance = $this->db_connect->query("UPDATE tbl_items_balance SET Item_Balance = '$balance_after_adjustment' WHERE Sub_Department_ID = '$Sub_Department_ID' AND Item_ID = ".$Items_ID[$i]." ") or die($this->db_connect->errno." could not update balance");
                    if($update_balance){
                        $pre_balance= 0;
                        $Item_ID_ = $Items_ID[$i];
                        $update_stock_balance_in_stock_ledger_control = $this->db_connect->query("INSERT INTO tbl_stock_ledger_controler (Item_ID,Movement_Type,Sub_Department_ID,Pre_Balance,Post_Balance,Document_Number,Movement_Date,Movement_Date_Time) VALUES ('$Item_ID_','$Movement_Type','$Sub_Department_ID','$pre_balance','$adjusted','$adjustment_number',NOW(),NOW())") or die($this->db_connect->error);
                        if(!$update_stock_balance_in_stock_ledger_control){
                            $hasError = TRUE;
                        }else{
                            $this->db_connect->query("UPDATE tbl_disposal SET Disposal_Status = 'saved',Disposed_Date = NOW() WHERE Disposal_ID = '$adjustment_number'");
                        }
                    }
                }
            }

            return (!$hasError) ? 100 : "Something went wrong contact admin for support";
        }

        public function createNewReasonForAdjustment($name,$nature,$Employee_ID){
            $query = $this->db_connect->query("INSERT INTO tbl_adjustment (name,nature,enable_disable,added_by) VALUES ('$name','$nature','enable','$Employee_ID')") or die($this->db_connect->errno." Failed to add new adjustment reason");
            return ($query) ? 100 : 500;
        }

        public function enableDisableAdjustmentReasons($reason_id,$staus){
            $query = $this->db_connect->query("UPDATE tbl_adjustment SET enable_disable = '$staus' WHERE id='$reason_id'") or die($this->db_connect->errno." Failed to update adjustment reason");
            return ($query) ? 100 : 500;
        }

        public function createNewStoreOrder($CreateNewOrderObject){
            $Order_Description = $CreateNewOrderObject['Order_Description'];
            $Current_Store_ID = $CreateNewOrderObject['Current_Store_ID'];
            $Employee_ID = $CreateNewOrderObject['Employee_ID'];
            $Branch_ID = $CreateNewOrderObject['Branch_ID'];

            $createOrder = $this->db_connect->query("INSERT INTO tbl_store_orders (Order_Description,Created_Date_Time,Created_Date,Sub_Department_ID,Employee_ID,Supervisor_ID,Order_Status,Control_Status,Branch_ID,prepared) VALUES ('$Order_Description',NOW(),NOW(),'$Current_Store_ID','$Employee_ID','$Employee_ID','pending','available','$Branch_ID','normal')") or die($this->db_connect->error."::FAIL TO CREATE NEW STORE");
            return ($createOrder) ? $this->db_connect->insert_id : $this->db_connect->errno.": FAIL TO CREATE NEW STORE";
        }

        public function addNewItemInOrder($addNewItemInOrder){
            $Item_ID = $addNewItemInOrder['Item_ID'];
            $New_Order_Number = $addNewItemInOrder['New_Order_Number'];
            
            $check_if_order_contains_the_selected_items = $this->db_connect->query("SELECT Item_ID FROM tbl_store_order_items WHERE Store_Order_ID = '$New_Order_Number' AND Item_ID = $Item_ID ") or die($this->db_connect->error.": FAIL TO ADD SELECTED IN ORDER");
            if($check_if_order_contains_the_selected_items->num_rows > 0){
                return 300;
            }else{
                $add_item = $this->db_connect->query("INSERT INTO tbl_store_order_items (Store_Order_ID,Item_ID) VALUES ('$New_Order_Number','$Item_ID')") or die($this->db_connect->errno.": FAIL TO ADD");
                return ($add_item) ? 100 : 200;
            }
        }

        public function fetchItemsInOrder($Order_Number,$Current_Store_ID){
            $get_items = $this->db_connect->query("SELECT * FROM `tbl_store_order_items` AS tsodi,tbl_items AS ti, tbl_items_balance AS tib WHERE tsodi.`Store_Order_ID` = $Order_Number AND tsodi.`Item_ID` = ti.`Item_ID` AND tsodi.`Item_ID` = tib.`Item_ID` AND tib.Sub_Department_ID = $Current_Store_ID ") or die($this->db_connect->errno.": FAIL TO LOAD ITEMS");
            return $this->queryExecuter($get_items);
        }

        public function updateOrderQuantityForItems($QuantityObject){
            $Order_Number= $QuantityObject['Order_Number'];
            $item_per_unit_= $QuantityObject['item_per_unit_'];
            $item_unit_= $QuantityObject['item_unit_'];
            $total_unit= $QuantityObject['total_unit'];
            $Item_ID= $QuantityObject['Item_ID'];

            $update_store_order_items_qty = $this->db_connect->query("UPDATE tbl_store_order_items SET Quantity_Required = $total_unit,Container_Qty = $item_per_unit_,Items_Qty = $item_unit_ WHERE `Store_Order_ID` = $Order_Number AND Item_ID = $Item_ID") or die($this->db_connect->errno."FAIL TO UPDATE STORE ORDER QUANTITY");
            return ($update_store_order_items_qty) ? 100 : 200;
        }

        public function submitStoreOrder($Order_Number){
            $submit_order = $this->db_connect->query("UPDATE tbl_store_orders SET Sent_Date_Time = NOW(),Order_Status='Submitted' WHERE Store_Order_ID = $Order_Number") or die($this->db_connect->errno."FAIL TO SUBMIT ORDER");
            return ($submit_order) ? 100 : 200;
        }
    }
?>