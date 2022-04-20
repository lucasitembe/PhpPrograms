<?php

@session_start();
include("./includes/connection.php");
//get Grn_Purchase_Order_ID
if (isset($_GET['Grn_Purchase_Order_ID'])) {
    $Grn_Purchase_Order_ID = $_GET['Grn_Purchase_Order_ID'];
} else {
    $Grn_Purchase_Order_ID = 0;
}

$canPakage = false;
$display = "style='display:none'";

if (isset($_SESSION['systeminfo']['enable_receive_by_package']) && $_SESSION['systeminfo']['enable_receive_by_package'] == 'yes') {
    $canPakage = true;
    $display = "";
}

$htm = "<table width ='100%'  class='nobordertable'>
		    <tr><td width=100%>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td></tr>
                    <tr><td style='text-align: center;'><b>GRN AGAINST PURCHASE ORDER REPORT</b></td></tr>
                    </table>";
 $htm .= '<table width ="100%" border="0"  class="nobordertable" >';
/////
 $sql_select_grn_purchase_order_result=mysqli_query($conn,"SELECT gpo.Grn_Purchase_Order_ID,gpo.Created_Date_Time,emp.Employee_Name,sup.Supplier_Name,gpo.Purchase_Order_ID,gpo.Debit_Note_Number,gpo.Invoice_Number,gpo.Delivery_Date,gpo.Delivery_Person,gpo.RV_Number FROM 
                    
            tbl_grn_purchase_order gpo,tbl_employee emp,tbl_supplier sup WHERE 
            
            gpo.Employee_ID=emp.Employee_ID AND gpo.supplier_id=sup.Supplier_ID AND Grn_Purchase_Order_ID='$Grn_Purchase_Order_ID' ORDER BY gpo.Grn_Purchase_Order_ID DESC
            ") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_grn_purchase_order_result)>0){
                $count=1;
                while($grn_purchase_rows=mysqli_fetch_assoc($sql_select_grn_purchase_order_result)){
                    $Grn_Purchase_Order_ID=$grn_purchase_rows['Grn_Purchase_Order_ID'];
                    $Created_Date_Time=$grn_purchase_rows['Created_Date_Time'];
                    $Employee_Name=$grn_purchase_rows['Employee_Name'];
                    $Supplier_Name=$grn_purchase_rows['Supplier_Name'];
                    $Purchase_Order_ID=$grn_purchase_rows['Purchase_Order_ID'];
                    $Debit_Note_Number=$grn_purchase_rows['Debit_Note_Number'];
                    $Invoice_Number=$grn_purchase_rows['Invoice_Number'];
                    $Delivery_Date=$grn_purchase_rows['Delivery_Date'];
                    $Delivery_Person=$grn_purchase_rows['Delivery_Person'];
                    $RV_Number=$grn_purchase_rows['RV_Number'];
                        $htm .= "<tr><td style='text-align: right;'><b>Local Purchase Order N<u>O</u></b></td><td style='text-align: right;'>" . $Purchase_Order_ID . "</td><td><b>GRN N<u>O</u></b></td><td>" . $Grn_Purchase_Order_ID . "</td></tr>";

                        $htm .= "<tr><td style='text-align: right;'><b>Created Date & Time</b></td><td style='text-align: right;'>" . $Created_Date_Time . "</td><td><b>Supplier Name</b></td><td>" . $Supplier_Name . "</td></tr>";
                        $htm .= "<tr><td style='text-align: right;'><b>Received By</b></td><td style='text-align: right;'>" . $Employee_Name . "</td><td><b>Delivery Note Number</b></td><td>" . $Debit_Note_Number . "</td></tr>";

                        $htm .= "<tr><td style='text-align: right;'><b>Invoice Number</b></td><td style='text-align: right;'>" . $Invoice_Number . "</td><td><b>Delivery Date</b></td><td>" . $Delivery_Date . "</td></tr>";

                        $htm .= "<tr><td style='text-align: right;'><b>Delivery Person</b></td><td style='text-align: left;' colspan='2'>" . $Delivery_Person . "</td></tr>";
       
                }
            }
//////
$htm.="</table>";


//get list of items
$get_items = mysqli_query($conn,"select * 
                         from tbl_local_purchase_order_items poi, tbl_grn_purchase_order gpo,tbl_items its
                          where poi.Grn_Purchase_Order_ID = gpo.Grn_Purchase_Order_ID and
                            poi.Item_Status = 'active' and
                              its.item_id = poi.item_id and
                                gpo.Grn_Purchase_Order_ID = '$Grn_Purchase_Order_ID' AND Grn_Status IN ('RECEIVED','OUTSTANDING','PENDING') ") or die(mysqli_error($conn));


$htm .= '<table width ="100%" border="0" class="display" >';
$htm .= " <tr>
                          <td width='3%' style='text-align:left'><b>S/n</b></td>
                          <td style='text-align:center' width='40%'><b>Item Name</b></td>";
if ($canPakage) {
    $htm .= "<td style='text-align:right'><b>Units</b></td>
                          <td style='text-align:right'><b>Items Per Unit</b></td>";
}

$htm .= "<td style='text-align:right'><b>Quantity</b></td>
                          <td style='text-align:right'><b>Buyying Price</b></td> 
                          <td style='text-align:center'><b>Expire Date</b></td>
						  <td style='text-align:right'><b>VAT</b></td>
                          <td style='text-align:right'><b>Amount</b></td>
                          
                        </tr>";

$Amount = 0;
$temp = 1;
$Grand_Total = 0;$Total_VAT=0;
while ($row = mysqli_fetch_array($get_items)) {
    $Product_Name=$row['Product_Name'];
    $Containers_Received=$row['Containers_Received'];
    $Items_Per_Container=$row['Items_Per_Container'];
    $Quantity_Received=$row['Quantity_Received'];
    $Buying_Price=$row['Buying_Price'];
    $Expire_Date=$row['Expire_Date'];
    $htm .= " <tr>
                          <td width='3%' style='text-align:left'>$temp</td>
                          <td style='text-align:left'>" . $Product_Name . "</td>";
    if ($canPakage) {
        $htm .= "<td style='text-align:right'>" . number_format($Containers_Received) . "</td>
                          <td style='text-align:right'>" . number_format($Items_Per_Container) . "</td>";
    }

    $Amount = $Quantity_Received * $Buying_Price;

    $htm .= "<td style='text-align:right'>" . number_format($Quantity_Received) . "</td>";
                          $htm .= "<td style='text-align:right'>" . number_format($Buying_Price,2) . "</td>"; 
                          $htm .= "<td style='text-align: center;'>" . $Expire_Date . "</td>"; 
                       if($Tax=="taxable"){
						$vat=0.18*$Amount;
						$Total_VAT=$Total_VAT+$vat;
						$htm .= "<td style='text-align: right;'>".number_format($vat,2)."</td>";
						}else{
						$htm .= "<td style='text-align: right;'>---</td>";
						}				  
                          $htm .= "<td style='text-align:right'>" . number_format($Amount,2) . "</td>
                        </tr>
                         ";

    $Grand_Total = $Grand_Total + $Amount;
    $Amount = 0;
    $temp++;
}
$Total_iclusive=$Grand_Total+$Total_VAT; 
$htm .= "<tr><td colspan='9' style='text-align: right;'><b>Total(Excl) &nbsp;&nbsp;&nbsp;&nbsp;</b><b>" . number_format($Grand_Total,2) . "</b></td></tr>";
$htm .= "<tr><td colspan='9' style='text-align: right;'><b>Total VAT &nbsp;&nbsp;&nbsp;&nbsp;</b><b>" . number_format($Total_VAT,2) . "</b></td></tr>";
$htm .= "<tr><td colspan='9' style='text-align: right;'><b>Total(Incl) VAT &nbsp;&nbsp;&nbsp;&nbsp;</b><b>" . number_format($Total_iclusive,2) . "</b></td></tr>";
$htm.="</table><br/>";

//$htm .= "<table width ='100%'  class='nobordertable'>
//           <tr>
//             <td><b>REMARKS</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;................................................................</td><td style='text-align:right'><b>INSPECTED BY</b> ............................................................</td>
//           </tr>
//           <tr>
//             <td><b>RECEIVED BY</b>&nbsp;&nbsp;................................................................</td><td style='text-align:right'><b>STOREKEEPER</b> ............................................................</td>
//           </tr>
//            <tr>
//             <td><b>RECEIVED ON</b>&nbsp;................................................................</td><td style='text-align:right'><b>SUPPLIES OFFICER</b> ............................................................</td>
//           </tr>
//         </table>";


    $htm .= "</table>";
//    $htm .= "<style> body { font-size: 12px; } #approvalProgress { font-size: 8px; } </style>";
    
    
    $htm .= "<br/><table style='width:100%;border:1px solid white'><tr style='border:1px solid white'>
            <td colspan='3' style='border:1px solid white'><b>Approved By:</b></td>
        </tr><tr style='border:1px solid white'>";
    //select list of approver
$sql_select_list_of_approver_result=mysqli_query($conn,"SELECT employee_signature,Employee_Name,dac.document_approval_level_title FROM tbl_document_approval_level_title dalt, tbl_document_approval_level dal,tbl_employee_assigned_approval_level eaal,tbl_document_approval_control dac,tbl_employee emp WHERE dalt.document_approval_level_title_id=dal.document_approval_level_title_id AND dal.document_approval_level_id=eaal.document_approval_level_id AND eaal.assgned_Employee_ID=dac.approve_employee_id AND dac.approve_employee_id=emp.Employee_ID AND document_number='$Purchase_Order_ID' AND dac.document_type='grn_against_purchases_order' GROUP BY eaal.assgned_Employee_ID") or die(mysqli_error($conn));
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
                            <td style='border:1px solid white;text-align:left'><b><i>$document_approval_level_title </i></b></td>
                        </tr>
                    </table>
                </td>
                ";
    }
}
$htm .="</tr></table>";

//echo $htm;exit;
include("MPDF/mpdf.php");
$mpdf = new mPDF('s', 'A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y}');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($htm, 2);
$mpdf->Output();
