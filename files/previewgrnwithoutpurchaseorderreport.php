<?php
@session_start();
include("./includes/connection.php");
$Temp = 0;
$Grand_Total = 0;
$htm2 = '';

#new
include 'store/store.interface.php';
$Interface = new StoreInterface();
$Grn_ID = (isset($_GET['Grn_ID'])) ? $_GET['Grn_ID'] : 0;
$Results = $Interface->getGRNWithoutPurchaseOrderDetails_($Grn_ID);
$Results_Items = $Interface->fetchGrnWithoutPRItems_($Grn_ID);
$count = 1;
$Grand_Total = 0;

if (isset($_SESSION['Storage_Info'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    $Sub_Department_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
}

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];

} else {
    $Employee_ID = 0;
    $Employee_Name = '';
}
?>

<?php
    $htm = "<table width ='100%'  style='font-family:arial' class='nobordertable'>
                <tr><td width=100%><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
                <tr><td style='text-align: center;'><b>GOOD RECEIVED NOTE (WITHOUT) REPORT</b></td></tr>
                </table><br/>";

    $htm .= "<table  width='100%' border='0' class='nobordertable' style='font-family:arial;font-size:14px'>
                <tr>
                    <td width='25%%'><b>Supplier Name </b></td> 
                    <td width='25%%'>".ucwords($Results[0]['Supplier_Name'])."</td>
                    <td width='25%'><b>GRN Number </b></td>
                    <td width='25%'>{$Grn_ID}</td>
                </tr>
                <tr>
                    <td><b>GRN Date </b></td>
                    <td>".$Results[0]['Grn_Date_And_Time']."</td>
                    <td><b>Receiver Name</b></td>
                    <td>".$Results[0]['Employee_Name']."</td>
                </tr>
                <tr>
                    <td ><b>Delivery Note Number</b></td>
                    <td >" . ucwords($Results[0]['Debit_Note_Number']) . "</td>
                    <td ><b>Invoice Number </td></tr>
                    <td >".ucwords($Results[0]['Invoice_Number'])."</td>
                </tr>

                <tr>
                    <td ><b>Deliver Date</b></td>
                    <td >".$Results[0]['Delivery_Date']."</td>
                    <td ><b>Store Received </b></td>
                    <td >".ucwords($Results[0]['Sub_Department_Name'])."</td>
                </tr>

                <tr>
                    <td><b>LPO Number</b></td>
                    <td>".$Results[0]['lpo_number']."</td>
                </tr>
            </table><br>";


    $htm .= "
        <table border=1 width='100%' style='font-size:10px;font-family:arial;border-collapse: collapse'>
            <tr style='background-color:#eee;'>
                <td style='padding:4px;' width='5%'><center>S/N</center></td>
                <td style='padding:4px'>ITEM NAME</td>
                <td style='padding:4px' width='7%'>UOM</td>
                <td style='padding:4px' width='7%'>PRODUCT CODE</td>
                <td style='padding:4px' width='7%'>FOLIO</td>
                <td style='padding:4px' width='7%'><center>UNIT</center></td>
                <td style='padding:4px' width='7%'><center>ITEM PER UNIT</center></td>
                <td style='padding:4px' width='7%'><center>QUANTITY</center></td>
                <td style='padding:4px' width='7%'>EXPIRE DATE</td>
                <td style='padding:4px;text-align:right' width='9.5%'>BUYING PRICE</td>
                <td style='padding:4px;text-align:right' width='9.5%'>AMOUNT</td>
            </tr>
    ";

    foreach($Results_Items as $Item){
        $Amount = 0;
        $Amount = $Item['Quantity_Required'] * $Item['Price'];
        $Grand_Total += $Amount;
        $htm .= "
            <tr>
                <td style='padding:5px'><center>".$count++."</center></td>
                <td style='padding:5px'>{$Item['Product_Name']}</td>
                <td style='padding:5px'>{$Item['Unit_Of_Measure']}</td>
                <td style='padding:5px'>{$Item['Product_Code']}</td>
                <td style='padding:5px'>{$Item['item_folio_number']}</td>
                <td style='padding:5px'><center>{$Item['Container_Qty']}</center></td>
                <td style='padding:5px'><center>{$Item['Items_Per_Container']}</center></td>
                <td style='padding:5px'><center>{$Item['Quantity_Required']}</center></td>
                <td style='padding:5px'><center>{$Item['Expire_Date']}</center></td>
                <td style='padding:5px;text-align:right'>".number_format($Item['Price'],2)."</td>
                <td style='padding:5px;text-align:right'>".number_format($Amount,2)."</td>
            </tr>
        ";
    }

    $htm .= "
        <tr style='background-color:#eee;'>
            <td colspan='10' style='padding:5px'>GRAND TOTAL</td>
            <td style='padding:5px;text-align:right'>".number_format($Grand_Total,2)."</td>
        </tr>
    ";

    $htm .= "</table> <h4 style='font-family:arial'>APPROVAL SUMMARY</h4><table style='font-family:arial'><tr>";

$sql_select_list_of_approver_result = mysqli_query($conn, "SELECT employee_signature,Employee_Name,dac.document_approval_level_title FROM tbl_document_approval_level_title dalt, tbl_document_approval_level dal,tbl_employee_assigned_approval_level eaal,tbl_document_approval_control dac,tbl_employee emp WHERE dalt.document_approval_level_title_id=dal.document_approval_level_title_id AND dal.document_approval_level_id=eaal.document_approval_level_id AND eaal.assgned_Employee_ID=dac.approve_employee_id AND dac.approve_employee_id=emp.Employee_ID AND document_number='$Grn_ID' AND dac.document_type='grn_without_purchases_order' GROUP BY eaal.assgned_Employee_ID") or die(mysqli_error($conn));
if (mysqli_num_rows($sql_select_list_of_approver_result) > 0) {
    while ($approver_rows = mysqli_fetch_assoc($sql_select_list_of_approver_result)) {
        
	$Employee_Name = $approver_rows['Employee_Name'];
        $document_approval_level_title = $approver_rows['document_approval_level_title'];
        $employee_signature = $Results[0]['employee_signature'];
        if ($employee_signature == "" || $employee_signature == null) {
            $signature = "________________________";
        } else {
            $signature = "<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
        }

        $htm .= "
                <td style='border:1px solid white'>
                    <table width='100%' style='border:1px solid white'>
                        <tr style='border:1px solid white'>
                            <td style='border:1px solid white'>$signature </td>
                        </tr>
                        <tr style='border:1px solid white'>
                            <td style='border:1px solid white'>".$Results[0]['Employee_Name']."</td>
                        </tr>
                        <tr style='border:1px solid white'>
                            <td style='border:1px solid white;text-align:left'><b>$document_approval_level_title $sign</b></td>
                        </tr>
                    </table>
                </td>
                ";
}
}
$htm .= "</tr></table>";

include("./MPDF/mpdf.php");
$mpdf=new mPDF('utf-8','A4', 0, '', 15,15,20,40,15,35, 'L');

$mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y} Powered by GPITG');
$mpdf->WriteHTML($htm);
$mpdf->Output();
