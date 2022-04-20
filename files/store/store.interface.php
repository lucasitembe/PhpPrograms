<?php 

    include 'store.repo.php';
    class StoreInterface extends StoreRepo {
        
        function getItems($Sub_Department_ID,$Product_Name,$classification){
            return json_decode($this->readItems($Sub_Department_ID,$Product_Name,$classification),true);
        }

        function getAdjustmentReasons($status){
            return json_decode($this->readAdjustmentReasons($status),true);
        }

        function createNewAdjustmentDocument($Sub_Department_ID,$Employee_ID,$Disposal_Description,$Branch_ID,$reason_for_adjustment){
            return $this->createNewAdjustment($Sub_Department_ID,$Employee_ID,$Disposal_Description,$Branch_ID,$reason_for_adjustment);
        }

        function addItemsToAdjust($Item_ID,$adjustment_number){
            return $this->addItemToAdjust($Item_ID,$adjustment_number);
        }

        function getAdjustedItems($adjustment_number,$Sub_Department_ID){
            return json_decode($this->readItemsToAdjust($adjustment_number,$Sub_Department_ID),true);
        }

        function removeItemFromAdjustList($adjustment_number,$Item_ID){
            return json_decode($this->removeItemsFromAdjust($adjustment_number,$Item_ID),true);
        }

        function updateAdjustmentQty($qty_adjusted,$Adjusted_ID){
            return $this->updateAdjustQty($qty_adjusted,$Adjusted_ID);
        }

        function saveItemRemarkForAdjustment($remark,$Adjusted_ID){
            return $this->saveRemarkForAdjustment($remark,$Adjusted_ID);
        }

        function saveItemBatchForAdjustment($batch,$Adjusted_ID){
            return $this->saveBatchForAdjustment($batch,$Adjusted_ID);
        }

        function saveItemExpireDateForAdjustment($expire_date,$Adjusted_ID){
            return $this->saveExpireDateForAdjustment($expire_date,$Adjusted_ID);
        }

        function SubmittedAdjustmentList($Item_IDs,$Item_ID_Qty,$adjustment_number,$Employee_ID,$Sub_Department_ID,$adjust,$Movement_Type){
            return $this->SubmittedAdjustment($Item_IDs,$Item_ID_Qty,$adjustment_number,$Employee_ID,$Sub_Department_ID,$adjust,$Movement_Type);
        }

        function getItemClassification(){
            return json_decode($this->readItemClassification(),true);
        }

        function getReadAdjustments($Sub_Department_ID,$Document_Number,$Status,$Start_Date,$End_Date){
            return json_decode($this->readAdjustments($Sub_Department_ID,$Document_Number,$Status,$Start_Date,$End_Date),true);
        }

        function getSingleEmployeeDetails($Employee_ID){
            return json_decode($this->readEmployeeDetails($Employee_ID),true);
        }

        function createAdjReason($name,$nature,$Employee_ID){
            return $this->createNewReasonForAdjustment($name,$nature,$Employee_ID);
        }

        function disableEnableAdjReasons($reason_id,$status){
            return $this->enableDisableAdjustmentReasons($reason_id,$status);
        }

        function deductItemQuantityByStoreDepartment($Item_Details){
            return $this->deductItemQuantity($Item_Details);
        }

        function createNewOrder($CreateNewOrderObject){
            return $this->createNewStoreOrder($CreateNewOrderObject);
        }

        function addNewItemInOrder_($addNewItemInOrder){
            return $this->addNewItemInOrder($addNewItemInOrder);
        }

        function getItemFromOrder($Order_Number,$Current_Store_ID){
            return json_decode($this->fetchItemsInOrder($Order_Number,$Current_Store_ID),true);
        }

        function quantityUpdateForStoreOrder($QuantityObject){
            return $this->updateOrderQuantityForItems($QuantityObject);
        }

        function submitOrderFromStore($Order_Number){
            return $this->submitStoreOrder($Order_Number);
        }

	function fetchStockDetails_($Start_Date,$End_Date,$Sub_Department_ID,$Item_ID){
            return json_decode($this->fetchStockDetails($Start_Date,$End_Date,$Sub_Department_ID,$Item_ID),true);
        }

        function getLedgerDetailsForDispensedMedication_($Document_Number,$Item_ID){
            return json_decode($this->getLedgerDetailsForDispensedMedication($Document_Number,$Item_ID),true);
        }

        function getLedgerDetailsForGRNWithoutPR_($Document_Number){
            return json_decode($this->getLedgerDetailsForGRNWithoutPR($Document_Number),true);
        }

        function getLedgerDetailsForIssueDetails_($Document_Number){
            return json_decode($this->getLedgerDetailsForIssueDetails($Document_Number),true);
        }

        function getLedgerDetailsForGRNAgainstIssueNote_($Document_Number){
            return json_decode($this->getLedgerDetailsForGRNAgainstIssueNote($Document_Number),true);
        }

        function getLedgerDetailsForStockTacking_($Document_Number,$Item_ID){
            return json_decode($this->getLedgerDetailsForStockTacking($Document_Number,$Item_ID),true);
        }

        function getLedgerDetailsForAdjustment_($Document_Number){
            return json_decode($this->getLedgerDetailsForAdjustment($Document_Number),true);
        }

	function getGRNWithoutPurchaseOrderDetails_($Grn_ID){
            return json_decode($this->getGRNWithoutPurchaseOrderDetails($Grn_ID),true);
        }

        function fetchGrnWithoutPRItems_($Grn_ID){
            return json_decode($this->fetchGrnWithoutPRItems($Grn_ID),true);
        }

	function removeItemFromOrder_($Remove_Object){
            return json_decode($this->removeItemFromOrder($Remove_Object));
        }

	function fetchApprovedStoreOrders_($Sub_Department_ID,$Filter_Object,$Order_Status_){
            return json_decode($this->fetchApprovedStoreOrders($Sub_Department_ID,$Filter_Object,$Order_Status_),true);
        }

	function fetchApprovalLevels_($Document_Name){
            return json_decode($this->fetchApprovalLevels($Document_Name),true);
        }

        function getIfEmployeeApprovalForDocument_($Document_Number,$Document_Title,$Document_Type){
            return json_decode($this->getIfEmployeeApprovalForDocument($Document_Number,$Document_Title,$Document_Type),true);
        }
    }
?>
