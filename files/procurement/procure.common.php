<?php 
    include 'procure.interface.php';
    $Procure = new ProcureInterface();

    if($_GET['request'] == "filter_lpo"){
        $output = "";
        $result = array();
        $counter = 1;
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];
        $lpo_number = $_GET['lpo_number'];
        $store = $_GET['store'];
        $supplier = $_GET['supplier'];
        $status = $_GET['status'];
        $Employee_ID = $_GET['Employee_ID'];
        $button_words = ($_GET['status'] == "cancelled") ? "RETURN LPO" : "APPROVE LPO";

        $result = $Procure->fetchLocalPurchaseOrderList($start_date,$end_date,$lpo_number,$store,$supplier,$status);
        $Action_Column = (sizeof($Procure->checkIfEmployeeIsAssigned($Employee_ID,"purchase_requisition")) > 0) ? "APPROVED" : "display:none";

        if(sizeof($result) > 0 ) {
            foreach($result as $details) : 
                $display = sizeof($Procure->checkIfApproved($_SESSION['userinfo']['Employee_ID'],$details['purchase_requisition_id'])) > 0 ? " display:none " : "";
                $output .= "<tr style='background-color: #fff;'>
                    <td style='padding: 8px;'><center>".$counter++."</center></td>
                    <td style='padding: 8px;'><center>{$details['Store_Order_ID']}</center></td>
                    <td style='padding: 8px;'><center>{$details['purchase_requisition_id']}</center></td>
                    <td style='padding: 8px;'>{$details['created_date_time']}</td>
                    <td style='padding: 8px;'>".ucwords($details['Supplier_Name'])."</td>
                    <td style='padding: 8px;'>".ucwords($details['Sub_Department_Name'])."</td>
                    <td style='padding: 8px;'>".ucwords($details['Employee_Name'])."</td>
                    <td style='padding: 8px;'>{$details['purchase_requisition_description']}</td>
                    <td style='padding: 4px;{$display} {$Action_Column}'><a href='approve_selected_local_purchase_order.php?purchase_requisition_id={$details['purchase_requisition_id']}' class='art-button-green'>{$button_words}{$show}</a></td>
                </tr>";
            endforeach;
        }else{
            $output .= "<tr><td colspan='10' style='padding:8px;background:#fff;color:red;text-align:center'> NO LOCAL PURCHASE ORDER BETWEEN <b> {$start_date} - {$end_date}</b> </td></tr>";
        }
        echo $output;
    }

    if($_GET['request'] == "filter_purchase_requisition"){
        $output = "";
        $result = array();
        $counter = 1;
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];
        $order_requisition = $_GET['order_requisition'];
        $store = $_GET['store'];
        $supplier = $_GET['supplier'];
        $Employee_ID = $_GET['Employee_ID'];
        $status = $_GET['status'];
        $button_words = ($_GET['status'] == "cancelled") ? "RETURN PR" : "APPROVE PR";

        $result = $Procure->getPurchaseOrderList($status,$start_date,$end_date,$order_requisition,$store,$supplier);
        $Action_Column = (sizeof($Procure->checkIfEmployeeIsAssigned($Employee_ID,"purchase_requisition")) > 0) ? "<span>APPROVED</span>" : "display:none";


        if(sizeof($result) > 0 ) {
            foreach($result as $details) : 
                $display = ($details['reference_document'] == "" ? " <span style='color:green;font-weight:500'>No Reference Document</span> " : " <a href='attachment/{$details['reference_document']}' target='_blank' class='art-button-green'>PREVIEW</a> ");
                $display_1 = (sizeof($Procure->getDocumentIfApproved($Employee_ID,'purchase_requisition',$details['purchase_requisition_id'])) > 0) ? " Yes " : " <a href='approve_selected_purchase_requisition.php?purchase_requisition_id={$details['purchase_requisition_id']}&pr={$status}' class='art-button-green'>{$button_words}</a> ";
                $output .= "
                    <tr>
                    <td style='text-align: center;'>".$counter++ ."</td>
                    <td style='text-align: center;'>{$details['Store_Order_ID']}</td>
                    <td style='text-align: center;'>{$details['purchase_requisition_id']}</td>
                    <td >{$details['created_date_time']}</td>
                    <td >".ucwords($details['Supplier_Name'])."</td>
                    <td >".ucwords($details['Sub_Department_Name'])."</td>
                    <td >".ucwords($details['Employee_Name'])."</td>
                    <td >{$details['purchase_requisition_description']}</td>
                    <td >{$display}</td>
                    <td style='$Action_Column'>{$display_1}</td>
                </tr>";
            endforeach;
        }else{
            $output .= "<tr><td colspan='10' style='padding:8px;background:#fff;color:red;text-align:center'> NO PURCHASE REQUISITION <b> {$start_date} - {$end_date}</b> </td></tr>";
        }
        echo $output;
    }

    if($_POST['request'] == "lpo_cancellation"){
        $reason_for_cancellation = $_POST['reason_for_cancellation'];
        $Employee_ID = $_POST['Employee_ID'];
        $purchase_requisition_id = $_POST['purchase_requisition_id'];

        echo $Procure->cancelDoc($purchase_requisition_id,$Employee_ID,$reason_for_cancellation);
    }

    if($_POST['request'] == "cancel_purchase_requisition"){
        $reason_for_cancellation=$_POST['reason_for_cancellation'];
        $Employee_ID=$_POST['Employee_ID'];
        $purchase_requisition_id=$_POST['purchase_requisition_id'];

        echo $Procure->cancelPr($purchase_requisition_id,$Employee_ID,$reason_for_cancellation);
    }

    if($_POST['request'] == "reject_order"){
        $Order_No = $_POST['Order_No'];
        echo $Procure->rejectOrders($Order_No);
    }

    if($_GET['request'] == 'load_store_orders'){
        $output = "";
        $count = 1;
        $start_date = ($_GET['start_date'] == "") ? "" : $_GET['start_date'];
        $end_date = ($_GET['end_date'] == "") ? "" : $_GET['end_date'];
        $result = $Procure->getStoreOrders($start_date,$end_date);

        if(sizeof($result) > 0){
            foreach($result as $order){
                $output .= "
                    <tr>
                        <td style='text-align:center;padding:8px;'>".$count++."</td>
                        <td style='padding:8px;'>{$order['Employee_Name']}</td>
                        <td style='padding:8px;text-align:center'>{$order['Store_Order_ID']}</td>
                        <td style='padding:8px;'>{$order['Approval_Date_Time']}</td>
                        <td style='padding:8px;'>".ucwords($order['Sub_Department_Name'])."</td>
                        <td style='padding:8px;'>{$order['Order_Description']}</td>
                        <td style='text-align:center'>
                            <a href='create_purchase_requisition.php?Store_Order_ID=".$order['Store_Order_ID']."&Sub_Department_ID=".$order['Sub_Department_ID']."&Sub_Department_Name=".$order['Sub_Department_Name']."' class='art-button-green'>CREATE PURCHASE REQUISITION <b>(PR)</b></a>
                            <a href='previousstoreorderreport.php?Store_Order_ID=".$order['Store_Order_ID']."&PreviousStoreOrder=PreviousStoreOrderThisPage' class='art-button-green' target='_blank'>PREVIEW</a>
                        </td>
                    </tr>
                ";
            }
        }else{
            $output .= "<tr><td colspan='7' style='padding:8px'><center>NO ORDERS FOUND</center></td></tr>";
        }
        echo $output;
    }

    if($_GET['Filter_Approved_LPO']){
        $count = 1;
        $Result = $Procure->getApprovedRequisition($_GET['Filter_Approved_LPO_Report']);

        if(sizeof($Result)>0){
            foreach($Result as $Requisition) : 
                $Output .="<tr style='background-color: #fff;'>
                    <td><center>".$count++."</center></td>
                    <td><center>{$Requisition['Store_Order_ID']}</center></td>
                    <td><center>{$Requisition['purchase_requisition_id']}</center></td>
                    <td>{$Requisition['created_date_time']}</td>
                    <td>{$Requisition['Supplier_Name']}</td>
                    <td>".ucfirst($Requisition['Sub_Department_Name'])."</td>
                    <td>{$Requisition['Employee_Name']}</td>
                    <td>{$Requisition['purchase_requisition_description']}</td>
                    <td><a href='preview_selected_approved_purchase_requisition.php?purchase_requisition_id={$Requisition['purchase_requisition_id']}' class='art-button-green' target='_blank'>PREVIEW</a></td>
                </tr> ";
            endforeach;
        }else{
            $Output .= " <tr style='background-color:#fff'><td colspan='9' style='text-align:center;color:red;font-weight:500'>NO DATA FOUND</td></tr> ";
        }
        echo $Output;
    }

    if($_GET['filter_approve_lpo_report']){
        $Output = "";
        $count = 1;
        $Start_Date = $_GET['Start_Date'];
        $End_Date = $_GET['End_Date'];
        $Requisition_No = $_GET['Requisition_No'];
        $Purchase_Requisition_No = $_GET['Purchase_Requisition_No'];
        $Supplier_ID = $_GET['Supplier_ID'];
        $Requesting_Store_ID = $_GET['Requesting_Store_ID'];
        $Result = $Procure->getApprovedLpo($Start_Date,$End_Date,$Requisition_No,$Purchase_Requisition_No,$Supplier_ID,$Requesting_Store_ID);

        if(sizeof($Result)>0){
            foreach($Result as $LPO) : 
                $Output .="<tr style='background-color: #fff;'>
                <td style='padding: 8px;'><center>".$count++."</center></td>
                <td style='padding: 8px;'><center>{$LPO['Store_Order_ID']}</center></td>
                <td style='padding: 8px;'><center>{$LPO['purchase_requisition_id']}</center></td>
                <td style='padding: 8px;'><center>{$LPO['local_purchase_order_id']}</center></td>
                <td style='padding: 8px;'>{$LPO['created_date_time']}</td>
                <td style='padding: 8px;'>".ucfirst($LPO['Supplier_Name'])."</td>
                <td style='padding: 8px;'>".ucfirst($LPO['Sub_Department_Name'])."</td>
                <td style='padding: 8px;'>".ucfirst($LPO['Employee_Name'])."</td>
                <td style='padding: 8px;'>".ucfirst($LPO['purchase_requisition_description'])."</td>
                <td style='padding: 5px;'><a  href='preview_selected_local_purchase_order.php?purchase_requisition_id={$LPO['purchase_requisition_id']}&local_purchase_order_id={$LPO['local_purchase_order_id']}' class='art-button-green' target='_blank'class='art-button-green'>PREVIEW</a></td>
            </tr>";
            endforeach;
        }else{
            $Output .= " <tr style='background-color:#fff'><td colspan='10' style='text-align:center;color:red;font-weight:500'>NO DATA FOUND</td></tr> ";
        }
        echo $Output;
    }

    if($_POST['create_new_lpo_without_pr']){
        $documentObject = $_POST['documentObject'];
        echo $Procure->createLPOWithoutPr($documentObject);
    }

    if($_POST['add_item_lpo_without_pr']){
        $itemObject = $_POST['itemObject'];
        echo $Procure->addNewItemToLPOWithout($itemObject);
    }

    if($_GET['load_item_in_lpo_without_pr']){
        $output = "";
        $count = 1;
        $Doc_Number = $_GET['Document_Number'];
        $Items = $Procure->getAlreadyAddedItem($Doc_Number);

        if(sizeof($Items) > 0){
            foreach($Items as $Item){
                $output .= "
                    <tr style='background-color: #fff;'>
                        <td style='padding: 8px;'><center>".$count++."</center></td>
                        <td style='padding: 8px;'>{$Item['Product_Name']}</td>
                        <td style='padding: 8px;'><center>{$Item['Unit_Of_Measure']}</center></td>
                        <td style='padding: 2px;'><input type='text' style='padding: 5px;text-align:center' value='{$Item['Quantity']}' onkeyup='calculate({$Item['Item_ID']})' id='item_qty_{$Item['Item_ID']}'></td>
                        <td style='padding: 2px;'><input type='text' style='padding: 5px;text-align:end'  value='{$Item['Quantity']}' onkeyup='calculate({$Item['Item_ID']})' id='item_buying_price_{$Item['Item_ID']}' ></td>
                        <td style='padding: 2px;'><input type='text' style='padding: 5px;text-align:end' onkeyup='calculate({$Item['Item_ID']})' class='sub_total' id='item_sub_total_{$Item['Item_ID']}' ></td>
                        <td style='padding: 2px;'><center><a href='#' class='art-button-green'>REMOVE</a></center></td>
                    </tr>
                ";
            }
        }else{
            $output .= "<tr><td>NO ITEMS FOUND</td></tr>";
        }

        echo $output;
    }

    if($_POST['update_details']){
        $updateDetails = $_POST['updateDetails'];
        echo $Procure->updateLPOWithoutQty_($updateDetails);
    }

    if($_POST['submit_lpo_without_purchase_order']){
        $Document_Number = $_POST['Document_Number'];
        echo $Procure->submitApprovalPurchaseOrder_($Document_Number);
    }

    if($_POST['final_approval_lpo']){
        $ID = $_POST['ID'];
        echo $Procure->lpoWithoutPRApprovalFinal_($ID);
    }

?>