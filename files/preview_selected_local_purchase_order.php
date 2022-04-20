<?php
    include("./includes/connection.php");
    @session_start();
    
    //get Purchase_Order_ID
    if(isset($_GET['purchase_requisition_id'])){
        $purchase_requisition_id = $_GET['purchase_requisition_id'];
    }else{
        $purchase_requisition_id = 0;
    }
    if(isset($_GET['local_purchase_order_id'])){
        $local_purchase_order_id = $_GET['local_purchase_order_id'];
    }else{
        $local_purchase_order_id = 0;
    }
    
    $htm = "<table style='font-size:12px' width ='100%' height = '20px'>
		        <tr><td> <img src='./branchBanner/branchBanner.png' width=100%> </td></tr>
                <tr><td style='text-align: center;font-family:arial'><h2>LOCAL PURCHASE ORDER</h2></td></tr>
            </table>";

    $htm .= "<table style='font-size:12px;font-family:arial' width=100%>";

             $sql_select_purchase_requisition_result=mysqli_query($conn,"SELECT lpo.local_purchase_order_id,lpo.purchase_requisition_id,lpo.Store_Order_ID,lpo.purchase_requisition_description,lpo.created_date_time,emp.Employee_Name,sup.Supplier_Name,sd.Sub_Department_Name,sd.Sub_Department_ID FROM tbl_local_purchase_order lpo,tbl_employee emp,tbl_supplier sup,tbl_sub_department sd WHERE lpo.employee_creating=emp.Employee_ID AND lpo.Supplier_ID=sup.Supplier_ID AND lpo.store_requesting=sd.Sub_Department_ID AND lpo.local_purchase_order_id='$local_purchase_order_id'ORDER BY purchase_requisition_id DESC") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_purchase_requisition_result)>0){
                $count=1;
                while($lpo_rows=mysqli_fetch_assoc($sql_select_purchase_requisition_result)){
                   $Store_Order_ID=$lpo_rows['Store_Order_ID'];
                   $purchase_requisition_description=$lpo_rows['purchase_requisition_description'];
                   $created_date_time=$lpo_rows['created_date_time'];
                   $Employee_Name=$lpo_rows['Employee_Name'];
                   $Supplier_Name=$lpo_rows['Supplier_Name'];
                   $purchase_requisition_id=$lpo_rows['purchase_requisition_id'];
                   $local_purchase_order_id=$lpo_rows['local_purchase_order_id'];
                   $Sub_Department_Name=$lpo_rows['Sub_Department_Name'];
                   $Sub_Department_ID=$lpo_rows['Sub_Department_ID'];
                   $htm .=  "<tr>
                                <td style='text-align:left;padding:2px' width='25%'><b>Local Purchase Order No. :</b></td><td width='25%' style='text-align:left;padding:2px'>$local_purchase_order_id</td>
                                <td style='text-align:left;padding:2px' width='25%'><b>Purchase Requisition No. :</b></td><td width='25%' style='text-align:left;padding:2px'>$purchase_requisition_id</td>
                            </tr>
                            <tr>
                                <td style='text-align:left;padding:2px'><b>Employee Created : </b></td><td style='text-align:left;padding:2px'>$Employee_Name</td>
                                <td style='text-align:left;padding:2px'><b>Store Requesting :</b> </td><td style='text-align:left;padding:2px'>".strtoupper($Sub_Department_Name)."</td>
                            </tr>
                            <tr>
                                <td style='text-align:left;padding:2px'><b>PR Description : </b></td><td style='text-align:left;padding:2px'>$purchase_requisition_description</td>
                                <td style='text-align:left;padding:2px'><b>Created Date :</b></td><td style='text-align:left;padding:2px'>$created_date_time</td>
                            </tr>
                            <tr>
                                <td style='text-align:left;padding:2px'><b>Order Requisition No. :</b> </td><td style='text-align:left;padding:2px'>$Store_Order_ID</td>
                                <td style='text-align:left;padding:2px'><b>Supplier : </b></td><td style='text-align:left;padding:2px'>$Supplier_Name</td>
                            </tr>
                           ";
                   $count++;
                }
            }
                    
    $htm .= "</table><br/>";
$htm .= '<center><table style="font-size:11px;font-family:arial;border-collapse: collapse" width=100%; border=1>';
		$htm .= '<tr>
                    <td width=4% style="text-align: left;background-color:#ddd"><center>SN</center></td>
                    <td style="padding:5px;background-color:#ddd">Item Name</td>
                    <td width=10% style="text-align: center;padding:5px;background-color:#ddd">UNIT</td>
                    <td width=13% style="text-align: center;padding:5px;background-color:#ddd">ITEM PER UNIT</td>
                    <td width=11%  style="text-align: center;padding:5px;background-color:#ddd">QUANTITY</td>
                    <td width=11%  style="text-align: right;padding:5px;background-color:#ddd">PRICE</td>
                    <td width=11%  style="text-align: right;padding:5px;background-color:#ddd">AMOUNT</td>
                </tr>';
		
		$Temp=1; $total = 0;$grand_total=0;
                $sql_select_purchase_requisition_items=mysqli_query($conn,"SELECT Item_ID,container_quantity,item_per_container,quantity_required,buying_price FROM tbl_purchase_requisition_items WHERE purchase_requisition_id='$purchase_requisition_id' AND item_status IN ('active' OR 'recieved')") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_purchase_requisition_items)>0){
                    while($pr_items_rows=mysqli_fetch_assoc($sql_select_purchase_requisition_items)){
                        $Item_ID=$pr_items_rows['Item_ID'];
                        $container_quantity=$pr_items_rows['container_quantity'];
                        $item_per_container=$pr_items_rows['item_per_container'];
                        $quantity_required=$pr_items_rows['quantity_required'];
                        $buying_price=$pr_items_rows['buying_price'];
                        
                        $sub_total=0;
                        $sql_select_item_detail_result=mysqli_query($conn,"SELECT Product_Name,item_folio_number FROM tbl_items WHERE Item_ID='$Item_ID'") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_item_detail_result)>0){
                            while($items_rows=mysqli_fetch_assoc($sql_select_item_detail_result)){
                                $Product_Name=$items_rows['Product_Name'];
                                $item_folio_number=$items_rows['item_folio_number'];
                            }
                        }
                        $sub_total=$buying_price*$item_per_container;
                        $htm .= "<tr>
                                    <td style='padding:5px;text-align:center'>$Temp</td>
                                    <td style='padding:5px;'>$Product_Name</td>
                                    <td style='padding:5px;text-align:center'>$container_quantity</td>
                                    <td style='padding:5px;text-align:center'>$item_per_container</td>
                                    <td style='padding:5px;text-align:center'>$quantity_required</td>
                                    <td style='padding:5px;text-align:right'>".number_format($buying_price,2)."</td>
                                    <td style='padding:5px;text-align:right'>".number_format($sub_total,2)."</td>
                                </tr>";

                            $Temp++;
                            $grand_total+=$sub_total;
                        }
                }
				$htm .= "
                        <tr>
                            <td colspan='6' style='padding:5px;'><b>SUBTOTAL </b></td>
                            <td id='grand_total_price' style='text-align:right;padding:5px'>".number_format($grand_total,2)."</td>
                        </tr>
                        <tr>
                            <td colspan='7' style='padding: 5px;font-weight:bold'>OTHER CHARGES</td>
                        </tr>
                        ";

                    $cost = array();
                    $summation_of_other_charges = 0;
                    $get_other_charges_costs = mysqli_query($conn,"SELECT * FROM tbl_procurement_others_charges WHERE purchase_requisition_id = '{$_GET['purchase_requisition_id']}'") or die(mysqli_errno($conn));
                    while($cost = mysqli_fetch_array($get_other_charges_costs)){
                        $discount = $cost['discount'];
                        $costs = explode(",",$cost['costs']);
                    }
                    $get_charges_items = mysqli_query($conn,"SELECT Item_ID,Product_Name FROM tbl_items WHERE Item_Type = 'Procurement Charges'");
                    $counts = 1;
                    $iterate = 0;
                    while($data = mysqli_fetch_assoc($get_charges_items)){
                        $htm .= "
                            <tr>
                                <td style='padding: 5px;'><center>{$counts}</center></td>
                                <td style='padding: 5px;' colspan='5'>{$data['Product_Name']}</td>
                                <td style='padding: 5px;text-align: right'>".number_format($costs[$iterate],2)."</td>
                            </tr>
                        ";
                        $summation_of_other_charges += $costs[$iterate];
                        $iterate++;
                        $counts++;
                    }

                    $htm .= "
                        <tr>
                            <td style='padding: 5px;'><center></center></td>
                            <td style='padding: 5px;' colspan='5'>Discount <b>in Percent (%) </b></td>
                            <td style='padding: 2px;text-align: right'>{$discount}</td>
                        </tr>
                    ";

                    $htm .= "
                    <tr>
                        <td colspan='6' style='padding:5px'><b>SUBTOTAL :</b></td>
                        <td style='padding: 2px;text-align: right'>".number_format($summation_of_other_charges,2)."</td>
                    </tr>";

                    $gTotal = ($discount / 100) * $grand_total;
                    $discount_value = $grand_total - $gTotal;
                    $total_cost = $discount_value +$summation_of_other_charges;

                    $htm .= "<tr>
                            <td colspan='6' style='padding:5px'><b>GRAND TOTAL :</b></td>
                            <td style='padding: 5px;text-align: right'p>".number_format($total_cost,2)."</td>
                        </tr>
                    </table>";
                
                    $htm .="
                    <h4 style='font-family:arial;line-height:0'>APPROVAL SUMMARY</h4>
                    <table border='1' width='100%' class='approval_table' style='font-size:11px;font-family:arial;border-collapse: collapse;'>
                        <thead>
                            <tr>
                                <td style='text-align: center;padding:5px;background-color:#ddd'>S/N</td>
                                <td style='padding:5px;background-color:#ddd'>Employee Name</td>
                                <td style='padding:5px;background-color:#ddd'>Approval Title</td>
                                <td style='padding:5px;background-color:#ddd'>Approval Date</td>
                                <td style='padding:5px;background-color:#ddd'>Approval Status</td>
                            </tr>";
                            $count = 1;
                            $check = "";
            
                            $queryEmp = mysqli_query($conn, "SELECT * FROM tbl_employee emp,tbl_document_approval_level dal,tbl_employee_assigned_approval_level eal WHERE emp.Employee_ID=eal.assgned_Employee_ID AND dal.document_approval_level_id=eal.document_approval_level_id AND document_type='purchase_order' GROUP BY eal.document_approval_level_id") or die(mysqli_error($conn));
            
                            if (mysqli_num_rows($queryEmp) > 0) {
                                while ($name = mysqli_fetch_assoc($queryEmp)) {
                                    $no = $name['assigned_approval_level_id'];
                                    $nu = $name['document_approval_level_title_id'];
                                    $id = $name['Employee_ID'];
            
                                    $sup = mysqli_fetch_assoc(mysqli_query($conn,"SELECT document_approval_level_title FROM tbl_document_approval_level_title WHERE document_approval_level_title_id = '$nu'"))['document_approval_level_title'];
                                    $sql_select_approver_result=mysqli_fetch_assoc(mysqli_query($conn,"SELECT date_time,Employee_Name FROM tbl_employee emp,tbl_document_approval_control dac WHERE emp.Employee_ID=dac.approve_employee_id AND emp.Employee_ID = '$id' AND dac.document_number='$purchase_requisition_id' AND dac.document_type = 'purchase_order'"));
                                    $check_status = (empty($sql_select_approver_result)) ? "<span style='background-color:red;color:white;padding:5px;font-wight:500'><b>Not Approved</b></span>" :"<span style='padding:5px'>Approved</span>";
                                    
            
                                    $htm .= "<tr>
                                            <td style='text-align: center;padding:5px'>" . $count++ . "</td>
                                            <td style='padding:5px'>".$sql_select_approver_result['Employee_Name']."</td>
                                            <td style='padding:5px'>".$sup . "</td>
                                            <td style='padding:5px'>".$sql_select_approver_result['date_time']."</td>
                                            <td style='padding:5px'>".$check_status."</td>
                                        </tr>";
            
                                    $check_status = "";
                                }
                            } else {
                                $htm .= "<tr><td colspan='4' style='text-align:center'><b>No Approval Found</b></td></tr>";
                            }
                        $htm .="</thead>
                    </table>
                ";
    
    $htm .= "<br/><table style='width:100%;border:1px solid white;font-size:11px;font-family:arial'><tr style='border:1px solid white'>
        </tr><tr style='border:1px solid white'>";
    //select list of approver
$sql_select_list_of_approver_result=mysqli_query($conn,"SELECT employee_signature,Employee_Name,dac.document_approval_level_title FROM tbl_document_approval_level_title dalt, tbl_document_approval_level dal,tbl_employee_assigned_approval_level eaal,tbl_document_approval_control dac,tbl_employee emp WHERE dalt.document_approval_level_title_id=dal.document_approval_level_title_id AND dal.document_approval_level_id=eaal.document_approval_level_id AND eaal.assgned_Employee_ID=dac.approve_employee_id AND dac.approve_employee_id=emp.Employee_ID AND document_number='$purchase_requisition_id' AND dac.document_type='purchase_order' GROUP BY eaal.assgned_Employee_ID") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_list_of_approver_result)>0){
    while($approver_rows=mysqli_fetch_assoc($sql_select_list_of_approver_result)){
        $Employee_Name=$approver_rows['Employee_Name'];
        $document_approval_level_title=$approver_rows['document_approval_level_title'];
        $employee_signature=$approver_rows['employee_signature'];
        if($employee_signature==""||$employee_signature==null){
            $signature="________________________";
        }else{
            $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
        }
        $htm .="
                <td style='border:1px solid white'>
                    <table width='100%' style='border:1px solid white'>
                        <tr style='border:1px solid white'>
                            <td style='border:1px solid white'>$signature </td>
                        </tr>
                        <tr style='border:1px solid white'>
                            <td style='border:1px solid white'>$Employee_Name</td>
                        </tr>
                        <tr style='border:1px solid white'>
                            <td style='border:1px solid white;text-align:left'><b>$document_approval_level_title</b></td>
                        </tr>
                    </table>
                </td>
                ";
    }
}
$htm .="</tr></table>";

    include("./functions/makepdf.php");
?>
