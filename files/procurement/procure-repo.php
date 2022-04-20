<?php
    include 'db-config.php';

    class ProcureRepo extends DBConfig{
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

        public function readItemForSelectLocalPurchaseOrder($Pr_ID){
            $query = $this->db_connect->query("SELECT ti.Unit_Of_Measure,ti.Product_Name,container_quantity,item_per_container,quantity_required,buying_price FROM tbl_purchase_requisition_items AS tpr, tbl_items AS ti WHERE purchase_requisition_id=$Pr_ID AND item_status='active' AND tpr.Item_ID = ti.Item_ID ");
            return $this->queryExecuter($query);
        }

        public function readProcurementOtherCharges($Pr_ID){
            $query = $this->db_connect->query("SELECT * FROM tbl_procurement_others_charges WHERE purchase_requisition_id = $Pr_ID");
            return $this->queryExecuter($query);
        }

        public function readItemOnOtherCharges(){
            $query = $this->db_connect->query("SELECT Item_ID,Product_Name FROM tbl_items WHERE Item_Type = 'Charges'");
            return $this->queryExecuter($query);
        }

        public function readPurchaseLocalPurchaseOrderDetails($Pr_ID){
            $query = $this->db_connect->query("SELECT pr.employee_creating,pr.Supplier_ID,pr.purchase_requisition_id,pr.Store_Order_ID,pr.purchase_requisition_description,pr.created_date_time,emp.Employee_Name,sup.Supplier_Name,sd.Sub_Department_Name,sd.Sub_Department_ID FROM tbl_purchase_requisition pr,tbl_employee emp,tbl_supplier sup,tbl_sub_department sd WHERE pr.employee_creating=emp.Employee_ID AND pr.Supplier_ID=sup.Supplier_ID AND pr.store_requesting=sd.Sub_Department_ID AND purchase_requisition_id='$Pr_ID' AND pr.pr_status='approved' ORDER BY purchase_requisition_id DESC");
            return $this->queryExecuter($query);
        }

        public function readAllLocalPurchaseOrderList($start_date,$end_date,$lpo_number,$store,$supplier,$status){
            $filter = "";
            $filter .= ($start_date == "") ? "" : " AND pr.created_date_time BETWEEN '$start_date' AND '$end_date' ";
            $filter .= ($lpo_number == "") ? "" : " AND pr.purchase_requisition_id = $lpo_number ";
            $filter .= ($supplier == "all") ? "" : " AND pr.Supplier_ID = $supplier ";
            $filter .= ($store == "all") ? "" : " AND pr.store_requesting = $store ";
            $filter .= ($status == "active") ? " AND pr.lpo_status = 'no' " : " AND pr.lpo_status = 'yes' ";

            $query = $this->db_connect->query("SELECT pr.purchase_requisition_id,pr.Store_Order_ID,pr.purchase_requisition_description,pr.created_date_time,emp.Employee_Name,sup.Supplier_Name,sd.Sub_Department_Name,sd.Sub_Department_ID FROM tbl_purchase_requisition pr,tbl_employee emp,tbl_supplier sup,tbl_sub_department sd WHERE pr.employee_creating=emp.Employee_ID AND pr.Supplier_ID=sup.Supplier_ID AND pr.store_requesting=sd.Sub_Department_ID $filter AND pr.pr_status='approved' ORDER BY purchase_requisition_id DESC");

            return $this->queryExecuter($query);
        }

        public function checkIfEmployeeApproved($Employee_ID,$Pr_ID){
            $query = $this->db_connect->query("SELECT date_time FROM tbl_employee emp,tbl_document_approval_control dac WHERE emp.Employee_ID=dac.approve_employee_id AND emp.Employee_ID = '$Employee_ID' AND dac.document_number='$Pr_ID' AND dac.document_type = 'purchase_order'") or die($this->db_connect->errno." :Failed to check approval");
            return $this->queryExecuter($query);
        }

        public function checkIfEmployeeIsAssignedToDocument($Employee_ID,$Document_Type){
            $Document_ID_ = "";
            $Get_Document_ID = $this->db_connect->query("SELECT document_approval_level_title_id FROM `tbl_document_approval_level` WHERE `document_type` = '$Document_Type'") or die("Fail to get document type");

            foreach($Get_Document_ID as $Document){$Document_ID_ .= $Document['document_approval_level_title_id'].","; }
            $Document_ID = rtrim($Document_ID_,",");

            $Get_Assign_Employee_Assigned = "SELECT Employee_Name,assigned_approval_level_id FROM tbl_employee emp,tbl_document_approval_level dal,tbl_employee_assigned_approval_level eal WHERE emp.Employee_ID=eal.assgned_Employee_ID AND dal.document_approval_level_id=eal.document_approval_level_id AND document_approval_level_title_id IN ($Document_ID) AND document_type='$Document_Type' AND emp.Employee_ID= $Employee_ID";

            $Get_Employees_Assigned_Document = $this->db_connect->query($Get_Assign_Employee_Assigned) or die($this->db_connect->errno.": Error fail to check."); 
            return $this->queryExecuter($Get_Employees_Assigned_Document);
        }

        public function countStoreOrders(){
            $query = $this->db_connect->query("SELECT so.Store_Order_ID, so.Approval_Date_Time, emp.Employee_Name,sd.Sub_Department_Name,so.Sub_Department_ID FROM tbl_store_orders so, tbl_employee emp, tbl_sub_department sd WHERE Order_Status = 'Approved' AND emp.Employee_ID = so.Employee_ID AND so.Sub_Department_ID = sd.Sub_Department_ID AND so.Store_Order_ID IN(SELECT Store_Order_ID FROM tbl_store_order_items WHERE Procurement_Status in ('active', 'selected'))") or die("Error while counting store orders rows");
            return $query->num_rows;
        }

        public function countPurchaseRequisition($status){
            $query = $this->db_connect->query("SELECT reference_document,pr.purchase_requisition_id,pr.Store_Order_ID,pr.purchase_requisition_description,pr.created_date_time,emp.Employee_Name,sup.Supplier_Name,sd.Sub_Department_Name,sd.Sub_Department_ID FROM tbl_purchase_requisition pr,tbl_employee emp,tbl_supplier sup,tbl_sub_department sd WHERE pr.employee_creating=emp.Employee_ID AND pr.Supplier_ID=sup.Supplier_ID AND pr.store_requesting=sd.Sub_Department_ID AND pr.pr_status='{$status}' ORDER BY purchase_requisition_id DESC");
            return $query->num_rows;
        }

        public function readAllSuppliers(){
            $query = $this->db_connect->query("SELECT * FROM `tbl_supplier` ORDER BY Supplier_Name ASC ") or die($this->db_connect->errno);
            return $this->queryExecuter($query);
        }

        public function readStoreByNature($nature){
            $query = $this->db_connect->query("SELECT tsd.Sub_Department_ID,tsd.Sub_Department_Name FROM `tbl_sub_department` tsd , tbl_department td WHERE td.Department_Location = '{$nature}' GROUP BY tsd.Sub_Department_ID ORDER BY tsd.Sub_Department_Name ") or die($this->db_connect->errno);
            return $this->queryExecuter($query);
        }

        public function readPurchaseOrderList($status,$start_date,$end_date,$order_requisition,$store,$supplier){
            $filter = "";
            $filter .= ($start_date == "") ? "" : " AND pr.created_date_time BETWEEN '$start_date' AND '$end_date' ";
            $filter .= ($order_requisition == "") ? "" : " AND pr.purchase_requisition_id = $order_requisition ";
            $filter .= ($supplier == "all") ? "" : " AND pr.Supplier_ID = $supplier ";
            $filter .= ($store == "all") ? "" : " AND pr.store_requesting = $store ";

            $query = $this->db_connect->query("SELECT reference_document,pr.purchase_requisition_id,pr.Store_Order_ID,pr.purchase_requisition_description,pr.created_date_time,emp.Employee_Name,sup.Supplier_Name,sd.Sub_Department_Name,sd.Sub_Department_ID FROM tbl_purchase_requisition pr,tbl_employee emp,tbl_supplier sup,tbl_sub_department sd WHERE pr.employee_creating=emp.Employee_ID AND pr.Supplier_ID=sup.Supplier_ID AND  pr.store_requesting=sd.Sub_Department_ID $filter AND pr.pr_status='{$status}' ORDER BY purchase_requisition_id DESC LIMIT 50") or die($this->db_connect->errno);
            return $this->queryExecuter($query);
        }

        public function readDocumentIfApproved($employee_id,$document_type,$document_number){
            $query = $this->db_connect->query("SELECT date_time FROM tbl_employee emp,tbl_document_approval_control dac WHERE emp.Employee_ID=dac.approve_employee_id AND emp.Employee_ID = '{$employee_id}' AND dac.document_number='{$document_number}' AND dac.document_type = '{$document_type}' LIMIT 1") or die($this->db_connect->errno);
            return $this->queryExecuter($query);
        }

        public function readCurrencies(){
            $query = $this->db_connect->query("SELECT * FROM `tbl_currency`") or die($this->db_connect->errno);
            return $this->queryExecuter($query);
        }

        public function cancelDocument($requisition_id,$Employee_ID,$reason_to_cancel){
            $query = $this->db_connect->query("UPDATE tbl_purchase_requisition SET cancel_reasons = '$reason_to_cancel',lpo_cancelled_by = '$Employee_ID',lpo_status = 'yes' WHERE purchase_requisition_id = $requisition_id") or die($this->db_connect->errno);
            return (!$query) ? 500 : 100;
        }

        public function cancelPurchaseRequisition($requisition_id,$Employee_ID,$reason_to_cancel){
            $query = $this->db_connect->query("UPDATE tbl_purchase_requisition SET pr_status = 'cancelled', cancel_reasons = '$reason_to_cancel',cancelled_by = '$Employee_ID' WHERE purchase_requisition_id = $requisition_id") or die($this->db_connect->errno);
            return (!$query) ? 500 : 100;
        }

        public function updateStoreOrderItems($items,$status,$Employee_ID,$order_id){
            $store_items = array();
            $store_items = explode(',',$items);
            foreach($store_items as $item){
                $query = $this->db_connect->query("UPDATE tbl_store_order_items SET Item_Status = '$status',removed_by = '$Employee_ID' WHERE Store_Order_ID = $order_id AND Item_ID = $item") or die($this->db_connect->errno);
            }
            return (!$query) ? 500 : 100;
        }

        public function rejectOrder($order_id){
            $query = $this->db_connect->query("UPDATE tbl_store_orders SET Order_Status = 'submitted' WHERE Store_Order_ID = '$order_id'") or die($this->db_connect->errno);
            return (!$query) ? 500 : 100;
        }

        public function fetchStoreOrders($start_date,$end_date){
            $filter = "";
            $filter .= ($start_date == "" || $end_date == NULL) ? "" : " so.Approval_Date_Time BETWEEN '$start_date' AND '$end_date' AND ";

            $query = $this->db_connect->query("SELECT so.Store_Order_ID,so.Order_Description,so.Approval_Date_Time, emp.Employee_Name,sd.Sub_Department_Name,so.Sub_Department_ID
                FROM tbl_store_orders so, tbl_employee emp, tbl_sub_department sd
                WHERE Order_Status = 'Approved' AND emp.Employee_ID = so.Employee_ID AND so.Sub_Department_ID = sd.Sub_Department_ID AND
                    $filter (SELECT count(*) FROM tbl_store_order_items soi WHERE soi.Store_Order_ID = so.Store_Order_ID AND Procurement_Status in ('active', 'selected') ) > 0
                ORDER BY Store_Order_ID DESC limit 50") or die($this->db_connect->error." Failed to fetch active store order");
            return $this->queryExecuter($query);
        }

        public function fetchApprovedRequisition($Details){
            $filter = "";
            $Start_Date = $Details['Start_Date'];
            $End_Date = $Details['End_Date'];
            $Requisition_No = $Details['Requisition_No'];
            $Supplier_ID = $Details['Supplier_ID'];
            $Purchase_Requisition_No = $Details['Purchase_Requisition_No'];
            $Requesting_Store_ID = $Details['Requesting_Store_ID'];
            
            $filter .= ($Start_Date != null || $Start_Date != "") ? " AND pr.created_date_time BETWEEN '{$Start_Date}' AND '{$End_Date}'" : "";
            $filter .= ($Requisition_No == null || $Requisition_No == "") ? "" : " AND pr.Store_Order_ID = '{$Requisition_No}'";
            $filter .= ($Supplier_ID == null || $Supplier_ID == "" || $Supplier_ID == "all") ? "" : " AND pr.Supplier_ID = '{$Supplier_ID}' ";
            $filter .= ($Purchase_Requisition_No == null || $Purchase_Requisition_No == "") ? "" : " AND pr.purchase_requisition_id = '{$Purchase_Requisition_No}' ";
            $filter .= ($Requesting_Store_ID == null || $Requesting_Store_ID == "" || $Requesting_Store_ID == "all") ? "" : " AND pr.store_requesting = {$Requesting_Store_ID} ";


            $Query = $this->db_connect->query("SELECT pr.purchase_requisition_id,pr.Store_Order_ID,pr.purchase_requisition_description,pr.created_date_time,emp.Employee_Name,sup.Supplier_Name,sd.Sub_Department_Name,sd.Sub_Department_ID FROM tbl_purchase_requisition pr,tbl_employee emp,tbl_supplier sup,tbl_sub_department sd WHERE pr.employee_creating=emp.Employee_ID AND pr.Supplier_ID=sup.Supplier_ID AND pr.store_requesting=sd.Sub_Department_ID AND pr.pr_status IN ('approved','approved_for_lpo') $filter ORDER BY purchase_requisition_id DESC LIMIT 25") or die($this->db_connect->error.": Failed to check approved requisition");
            return $this->queryExecuter($Query);
        }

        public function fetchApprovedLpo($Start_Date,$End_Date,$Requisition_No,$Purchase_Requisition_No,$Supplier_ID,$Requesting_Store_ID){
            $filter = "";
            $filter .= ($Start_Date != null || $Start_Date != "") ? " AND lpo.created_date_time BETWEEN '$Start_Date' AND '$End_Date' " : "";
            $filter .= ($Requisition_No == null || $Requisition_No == "") ? "" : " AND lpo.Store_Order_ID = '$Requisition_No' ";
            $filter .= ($Supplier_ID == null || $Supplier_ID == "" || $Supplier_ID == "all") ? "" : " AND lpo.Supplier_ID = '$Supplier_ID' ";
            $filter .= ($Purchase_Requisition_No == null || $Purchase_Requisition_No == "") ? "" : " AND lpo.purchase_requisition_id = '$Purchase_Requisition_No' ";
            $filter .= ($Requesting_Store_ID == null || $Requesting_Store_ID == "" || $Requesting_Store_ID == "all") ? "" : " AND lpo.store_requesting = '$Requesting_Store_ID' ";

            $Query = $this->db_connect->query("SELECT lpo.local_purchase_order_id,lpo.purchase_requisition_id,lpo.Store_Order_ID,lpo.purchase_requisition_description,lpo.created_date_time,emp.Employee_Name,sup.Supplier_Name,sd.Sub_Department_Name,sd.Sub_Department_ID FROM tbl_local_purchase_order lpo,tbl_employee emp,tbl_supplier sup,tbl_sub_department sd WHERE lpo.employee_creating=emp.Employee_ID AND lpo.Supplier_ID=sup.Supplier_ID AND lpo.store_requesting=sd.Sub_Department_ID $filter ORDER BY purchase_requisition_id DESC LIMIT 50") or die($this->db_connect->errno.": Failed to fetch approved lpo");
            return $this->queryExecuter($Query);
        }

        public function fetchSubDepartmentStore(){
            $read_store = $this->db_connect->query("SELECT Sub_Department_Name,Sub_Department_ID FROM tbl_sub_department AS tsd, tbl_department AS td WHERE td.Department_Location IN ('Storage And Supply','Pharmacy') AND tsd.Sub_Department_Status = 'active' GROUP BY tsd.Sub_Department_ID") or die($this->db_connect->error."ERROR WHILE FETCHING SUB DEPARTMENT");
            return $this->queryExecuter($read_store);
        }

        public function fetchServiceItem(){
            $read_service_items = $this->db_connect->query("SELECT * FROM tbl_items WHERE  Status = 'Available' LIMIT 25") or die($this->db_connect->errno."ERROR WHILE READING SERVICE ITEMS");
            return $this->queryExecuter(($read_service_items));
        }

        public function createNewLPOWithoutPrDocument($documentObject){
            $Supplier_ID = $documentObject['Supplier_ID'];
            $Account_Number = $documentObject['Account_Number'];
            $Employee_ID = $documentObject['Employee_ID'];
            $LPO_Description = $documentObject['LPO_Description'];
            $Created_Date = $documentObject['Created_Date'];
            $Sub_Department_ID = $documentObject['Sub_Department_ID'];

            $createDocument = $this->db_connect->query("INSERT INTO lpo_without_pr (Employee,Supplier_ID,Description,Created_AT,Account_Number,Store_ID) VALUES ('$Employee_ID','$Supplier_ID','$LPO_Description','$Created_Date','$Account_Number','$Sub_Department_ID')") or die($this->db_connect->errno."FAIL CREATE NEW DOCUMENT");
            return ($createDocument) ? $this->db_connect->insert_id : "Fail";
        }

        public function addItemItemToLPOWithoutPR($itemObject){
            $Doc_Number = $itemObject['Doc_Number'];
            $Item_ID = $itemObject['Item_ID'];

            $get_items = $this->db_connect->query("SELECT * FROM lpo_without_pr_items WHERE Item_ID = $Item_ID AND ID = $Doc_Number ") or die($this->db_connect->error."FAIL TO ITEMS");

            if($get_items->num_rows > 0){
                return 300;
            }else{
                $create_item = $this->db_connect->query("INSERT INTO lpo_without_pr_items (Item_ID,Document_Number) VALUES ('$Item_ID','$Doc_Number')") or die($this->db_connect->errno."FAIL TO CREATE ITEM");
                return ($create_item) ? 100 : 200;
            }
        }

        public function loadAlreadyAddedItem($Doc_Number){
            $fetch_item = $this->db_connect->query("SELECT * FROM lpo_without_pr_items AS lwpr,tbl_items AS ti WHERE lwpr.Item_ID = ti.Item_ID AND Document_Number = $Doc_Number ") or die($this->db_connect->error.": ERROR WHILE GETTING ITEMS");
            return $this->queryExecuter($fetch_item);
        }

        public function updateLPOWithoutQty($updateDetails){
            $item_qty_ = $updateDetails['item_qty_'];
            $buying_price = $updateDetails['buying_price'];
            $Item_ID = $updateDetails['Item_ID'];
            $Document_Number = $updateDetails['Document_Number'];

            $update_details = $this->db_connect->query("UPDATE lpo_without_pr_items SET Quantity = '$item_qty_', Buying_Price = '$buying_price' WHERE Item_ID = $Item_ID AND Document_Number = '$Document_Number'") or die("FAIL TO UPDATE ITEM DETAILS");

            return ($update_details) ? 100 : 200;
        }

        public function submitApprovalPurchaseOrder($Document_Number){
            $submit = $this->db_connect->query("UPDATE lpo_without_pr SET Status = 'submitted',Submitted_At = NOW() WHERE ID = '$Document_Number'") or die($this->db_connect->errno.": FAILED TO SUBMIT LPO WITHOUT PURCHASE");
            return ($submit) ? 100 : 200;
        }

        public function fetchAllSubmittedLPOWithoutPR(){
            $get_submitted_lpo_without_pr = $this->db_connect->query("SELECT * FROM lpo_without_pr as lwp,tbl_sub_department AS tsd,tbl_supplier AS ts WHERE lwp.Store_ID = tsd.sub_department_id AND lwp.Supplier_ID = ts.Supplier_ID") or die($this->db_connect->errno."FAIL TO LPO WITHOUT PR ");
            return $this->queryExecuter($get_submitted_lpo_without_pr);
        }

        public function fetchAllSubmittedLPOWithoutPRSingle($LPO_ID){
            $get_submitted_lpo_without_pr = $this->db_connect->query("SELECT * FROM lpo_without_pr as lwp,tbl_sub_department AS tsd,tbl_supplier AS ts,tbl_employee AS te WHERE lwp.Store_ID = tsd.sub_department_id AND lwp.Supplier_ID = ts.Supplier_ID AND lwp.ID = $LPO_ID AND lwp.Employee = te.Employee_ID") or die($this->db_connect->error."FAIL TO LPO WITHOUT PR ");
            return $this->queryExecuter($get_submitted_lpo_without_pr);
        }

        public function lpoWithoutPRApprovalFinal($LPO_ID){
            $final_approve = $this->db_connect->query("UPDATE lpo_without_pr SET Status = 'approved' WHERE ID = '$LPO_ID'");
            return (!$final_approve) ? 200 : 100;
        }
    }
?>