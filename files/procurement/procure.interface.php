<?php 
    include 'procure-repo.php';

    class ProcureInterface extends ProcureRepo {
        function fetchLocalPurchaseOrderItems($Pr_ID){
            return json_decode($this->readItemForSelectLocalPurchaseOrder($Pr_ID),true);
        }

        function fetchProcurementOtherCharges($Pr_ID){
            return json_decode($this->readProcurementOtherCharges($Pr_ID),true);
        }

        function fetchOtherChargesItems(){
            return json_decode($this->readItemOnOtherCharges(),true);
        }

        function fetchPurchaseLocalOrderDetails($Pr_ID){
            return json_decode($this->readPurchaseLocalPurchaseOrderDetails($Pr_ID),true);
        }

        function fetchLocalPurchaseOrderList($start_date,$end_date,$lpo_number,$store,$supplier,$status){
            return json_decode($this->readAllLocalPurchaseOrderList($start_date,$end_date,$lpo_number,$store,$supplier,$status),true);
        }

        function checkIfApproved($Employee_ID,$Pr_ID){
            return json_decode($this->checkIfEmployeeApproved($Employee_ID,$Pr_ID),true);
        }

        function countApprovedOrders(){
            return $this->countStoreOrders();
        }

        function countCreatedPurchaseRequisition($status){
            return $this->countPurchaseRequisition($status);
        }

        function getAllSuppliers(){
            return json_decode($this->readAllSuppliers(),true);
        }

        function getStoreByNature($nature){
            return json_decode($this->readStoreByNature($nature),true);
        }

        function getPurchaseOrderList($status,$start_date,$end_date,$order_requisition,$store,$supplier){
            return json_decode($this->readPurchaseOrderList($status,$start_date,$end_date,$order_requisition,$store,$supplier),true);
        }

        function getDocumentIfApproved($employee_id,$document_type,$document_number){
            return json_decode($this->readDocumentIfApproved($employee_id,$document_type,$document_number),true);
        }

        function getCurrency(){
            return json_decode($this->readCurrencies(),true);
        }

        function cancelDoc($requisition_id,$Employee_ID,$reason_to_cancel){
            return $this->cancelDocument($requisition_id,$Employee_ID,$reason_to_cancel);
        }

        function cancelPr($requisition_id,$Employee_ID,$reason_to_cancel){
            return $this->cancelPurchaseRequisition($requisition_id,$Employee_ID,$reason_to_cancel);
        }

        function updateStoreOrderItem($items,$status,$Employee_ID,$order_id){
            return $this->updateStoreOrderItems($items,$status,$Employee_ID,$order_id);
        }

        function rejectOrders($order_id){
            return $this->rejectOrder($order_id);
        }

        function getStoreOrders($start_date,$end_date){
            return json_decode($this->fetchStoreOrders($start_date,$end_date),true);
        }

        function checkIfEmployeeIsAssigned($Employee_ID,$Document_Type){
            return json_decode($this->checkIfEmployeeIsAssignedToDocument($Employee_ID,$Document_Type));
        }

        function getApprovedRequisition(array $Details){
            return json_decode($this->fetchApprovedRequisition($Details),true);
        }

        function getApprovedLpo($Start_Date,$End_Date,$Requisition_No,$Purchase_Requisition_No,$Supplier_ID,$Requesting_Store_ID){
            return json_decode($this->fetchApprovedLpo($Start_Date,$End_Date,$Requisition_No,$Purchase_Requisition_No,$Supplier_ID,$Requesting_Store_ID),true);
        }

        function getStoreSubDepartments(){
            return json_decode($this->fetchSubDepartmentStore(),true);
        }

        function getServiceItems(){
            return json_decode($this->fetchServiceItem(),true);
        }

        function createLPOWithoutPr($documentObject){
            return $this->createNewLPOWithoutPrDocument($documentObject);
        }

        function addNewItemToLPOWithout($itemObject){
            return $this->addItemItemToLPOWithoutPR($itemObject);
        }

        function getAlreadyAddedItem($Doc_Number){
            return json_decode($this->loadAlreadyAddedItem($Doc_Number),true);
        }

        function updateLPOWithoutQty_($updateDetails){
            return $this->updateLPOWithoutQty($updateDetails);
        }

        function submitApprovalPurchaseOrder_($Document_Number){
            return $this->submitApprovalPurchaseOrder($Document_Number);
        }

        function fetchAllSubmittedLPOWithoutPR_(){
            return json_decode($this->fetchAllSubmittedLPOWithoutPR(),true);
        }

        function fetchAllSubmittedLPOWithoutPRSingle_($LPO_ID){
            return json_decode($this->fetchAllSubmittedLPOWithoutPRSingle($LPO_ID),true);
        }

        function lpoWithoutPRApprovalFinal_($LPO_ID){
            return $this->lpoWithoutPRApprovalFinal($LPO_ID);
        }
    }
?>