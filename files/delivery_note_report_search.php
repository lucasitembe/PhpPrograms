<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>
<?php
include("./includes/connection.php");

include_once("./functions/database.php");
include_once("./functions/stockledger.php");
include_once("./functions/items.php");

$purchase_order=null;
$without_order_query=null;
$rv_number=null;
if (isset($_GET['Start_Date'])) {
        $Start_Date = mysqli_real_escape_string($conn,$_GET['Start_Date']);
    } else {
        $Start_Date = '';
    }

    if (isset($_GET['End_Date'])) {
        $End_Date = mysqli_real_escape_string($conn,$_GET['End_Date']);
    } else {
        $End_Date = '';
    }
    if (isset($_GET['Sub_Department_ID']) || isset($_POST['Sub_Department_ID'])) {
       // $Sub_Department_ID = mysqli_real_escape_string($conn,$_GET['Sub_Department_ID']);
        $Sub_Department_ID = (isset($_GET['Sub_Department_ID'])) ? mysqli_real_escape_string($conn,$_GET['Sub_Department_ID']): mysqli_real_escape_string($conn,$_POST['Sub_Department_ID']);
    } else {
        $Sub_Department_ID = 0;
    }

if(!isset($_GET['FilterCategory'])){
/*getting the delivery note report*/
$sql_select=mysqli_query($conn,"
SELECT gpo.created_date_time ,gpo.RV_Number, gpo.Grn_Purchase_Order_ID,gpo.Purchase_Order_ID ,gpo.Debit_Note_Number,gpo.Delivery_Date, gpo.Delivery_Person,sup.Supplier_Name, emp.Employee_Name,gpo.Invoice_Number FROM tbl_grn_purchase_order gpo, tbl_purchase_order pd ,tbl_employee emp,tbl_supplier sup WHERE emp.Employee_ID=gpo.Employee_ID and sup.Supplier_ID=gpo.supplier_id and gpo.Purchase_Order_ID=po.Purchase_Order_ID and po.Sub_Department_ID=$Sub_Department_ID and gpo.Created_Date_Time between '{$Start_Date}' and '{$End_Date}' ");
}

if(isset($_POST['rv_number'])){
    if(!empty($_POST['rv_number']) ){
    $rv_number=mysqli_real_escape_string($conn,$_POST['rv_number']);
    $Start_Date = mysqli_real_escape_string($conn,$_POST['Start_Date']);
    $End_Date = mysqli_real_escape_string($conn,$_POST['End_Date']);



    $sql_select=mysqli_query($conn,"
    SELECT gpo.created_date_time ,gpo.RV_Number, gpo.Grn_Purchase_Order_ID,gpo.Purchase_Order_ID ,gpo.Debit_Note_Number,gpo.Delivery_Date, gpo.Delivery_Person,sup.Supplier_Name, emp.Employee_Name,gpo.Invoice_Number FROM tbl_grn_purchase_order gpo, tbl_purchase_order po ,tbl_employee emp,tbl_supplier sup WHERE emp.Employee_ID=gpo.Employee_ID and sup.Supplier_ID=gpo.supplier_id and gpo.Purchase_Order_ID=po.Purchase_Order_ID and po.Sub_Department_ID=$Sub_Department_ID and gpo.RV_Number='{$rv_number}' ");
    $purchase_order=true;

    $no=mysqli_num_rows($sql_select);
    if($no<1){
        $purchase_order=false;
    } 

}else{
    /*getting the delivery note report*/
$sql_select=mysqli_query($conn,"
SELECT gpo.created_date_time ,gpo.RV_Number, gpo.Grn_Purchase_Order_ID, gpo.Purchase_Order_ID ,gpo.Debit_Note_Number,gpo.Delivery_Date, gpo.Delivery_Person,sup.Supplier_Name, emp.Employee_Name,gpo.Invoice_Number FROM tbl_grn_purchase_order gpo, tbl_purchase_order po ,tbl_employee emp,tbl_supplier sup WHERE emp.Employee_ID=gpo.Employee_ID and sup.Supplier_ID=gpo.supplier_id and gpo.Purchase_Order_ID=po.Purchase_Order_ID and po.Sub_Department_ID=$Sub_Department_ID and gpo.Created_Date_Time between '{$Start_Date}' and '{$End_Date}' ");
}
}else{


    $sql_select=mysqli_query($conn,"
SELECT gpo.created_date_time , gpo.RV_Number,gpo.Grn_Purchase_Order_ID,gpo.Purchase_Order_ID ,gpo.Debit_Note_Number,gpo.Delivery_Date, gpo.Delivery_Person,sup.Supplier_Name, emp.Employee_Name,gpo.Invoice_Number FROM tbl_grn_purchase_order gpo, tbl_purchase_order po ,tbl_employee emp,tbl_supplier sup WHERE emp.Employee_ID=gpo.Employee_ID and sup.Supplier_ID=gpo.supplier_id and  gpo.Purchase_Order_ID=po.Purchase_Order_ID and po.Sub_Department_ID=$Sub_Department_ID and  gpo.Created_Date_Time between '{$Start_Date}' and '{$End_Date}' ");

    $without_order_query=true;
}

?>
<table width='100%'   border=0 id='Items_Fieldset'>
    <?php
    $Grand_Stock = 0;
    $Title = '<tr><td colspan="11"><hr></td></tr>
                    <tr>
                        <td width="5%"><b>SN</b></td>
                        <td width="10%"><b>DELIVERY NOTE #</b></td>
                        <td width="7%"><b>GRN #</b></td>
                        <td width="5%"><b>RV #</b></td>
                        <td width="7%" style="text-align: right;"><b>GRN DATE</b></td>
                        <td width="16%" style="text-align: left;"><b>SUPPLIER</b></td>
                        <td width="10%" style="text-align: right;"><b>DELIVERY DATE</b></td>
                        <td width="10%" style="text-align: left;"><b>DELIVERY PERSON</b></td>
                        <td width="16%" style="text-align: left;"><b>RECEIVED BY</b></td>
                        <td width="7%" style="text-align: left;"><b>INVOICE #</b></td>
                        <td width="13%" style="text-align: right;"><b>TOTAL</b></td>
                    </tr>
                    <tr><td colspan="11"><hr></td></tr>';
    echo $Title;
    $temp = 1;
    $grn_total_amount=0;
    while ($row = mysqli_fetch_array($sql_select)) {
        $Grn_Note = $row['Debit_Note_Number'];
        $RV_Number = $row['RV_Number'];
        $Grn_Date = new DateTime($row['created_date_time']);
        $Supplier = $row['Supplier_Name'];
        $Delivery_Date = $row['Delivery_Date'];
        $Delivery_Person = $row['Delivery_Person'];
        $Received_by = $row['Employee_Name'];
        $Invoice_Number = $row['Invoice_Number'];
        $Grn_Number = $row['Grn_Purchase_Order_ID'];
        $Purchase_Order_ID=$row['Purchase_Order_ID'];
        
      
        /*selecting the grn total amount*/
        $sql_select_grn_amount=mysqli_query($conn,"SELECT SUM(Quantity_Received*Price) AS grn_amount FROM tbl_purchase_order_items WHERE Purchase_Order_ID='{$Purchase_Order_ID}'");
        $grn_results=mysqli_fetch_assoc($sql_select_grn_amount);
        $grn_amount=$grn_results['grn_amount'];

        $grn_total_amount +=floatval($grn_amount);

            echo "<tr title='CLICK RV NUMBER FOR A SPECIFIC REPORT'><td >" . $temp . "<b>.</b></td>";
            echo "<td >{$Grn_Note}</td>";
            echo "<td >{$Grn_Number}</td>";
            echo "<td ><a class='view_report' style='display:block;' href='#' onclick='View_Report(this,\"{$Purchase_Order_ID}\",\"with_order\");'>{$RV_Number}</a></td>";
            echo "<td style='text-align: right;'>".$Grn_Date->format('Y-m-d')."</td>";
            echo "<td style='text-align: left;'>{$Supplier}</td>";
            echo "<td style='text-align: right;'>{$Delivery_Date}</td>";
            echo "<td style='text-align: left;'>{$Delivery_Person}</td>";
            echo "<td style='text-align: left;'>{$Received_by}</td>";
            echo "<td style='text-align: left;'>{$Invoice_Number}</td>";
            echo "<td style='text-align: right;'>".number_format($grn_amount)."</td>";
            echo "</tr>";
           /* if ($Total_Balance > 0) {
                $Total = $Total_Balance;
            } else {
                $Total = 0;
            }
            echo "<td style='text-align: right;'>" . number_format($Total * $Total_Average_Price) . "</td>";
            echo "</tr>";*/

            $temp++;
            if (($temp % 25) == 0) {
                echo $Title;
            }
    }

        if($purchase_order==false || $without_order_query==true){

        $sql_select=mysqli_query($conn,"SELECT gpo.Grn_ID, gpo.Grn_Date_And_Time ,gpo.RV_Number ,gpo.Debit_Note_Number,gpo.Delivery_Date,sup.Supplier_Name, emp.Employee_Name,gpo.Invoice_Number , gpo.Sub_Department_ID FROM tbl_grn_without_purchase_order gpo ,tbl_employee emp,tbl_supplier sup WHERE emp.Employee_ID=gpo.Employee_ID and sup.Supplier_ID=gpo.supplier_id and gpo.RV_Number='{$rv_number}' and gpo.Sub_Department_ID=$Sub_Department_ID")or die(mysqli_error($conn));
        if($without_order_query==true){
            $sql_select=mysqli_query($conn,"SELECT gpo.Grn_ID, gpo.Grn_Date_And_Time ,gpo.RV_Number ,gpo.Debit_Note_Number,gpo.Delivery_Date,sup.Supplier_Name, emp.Employee_Name,gpo.Invoice_Number, gpo.Sub_Department_ID FROM tbl_grn_without_purchase_order gpo ,tbl_employee emp,tbl_supplier sup WHERE emp.Employee_ID=gpo.Employee_ID and sup.Supplier_ID=gpo.supplier_id and gpo.Grn_Date_And_Time between '{$Start_Date}' and '{$End_Date}' and gpo.Sub_Department_ID=$Sub_Department_ID")or die(mysqli_error($conn));            
        }
        

            while ($row = mysqli_fetch_array($sql_select)) {
            $Grn_ID = $row['Grn_ID'];
            $Grn_Note = $row['Debit_Note_Number'];
            $RV_Number = $row['RV_Number'];
            $Grn_Date = new DateTime($row['Grn_Date_And_Time']);
            $Supplier = $row['Supplier_Name'];
            $Delivery_Date = $row['Delivery_Date'];
            $Delivery_Person = '';
            $Received_by = $row['Employee_Name'];
            $Invoice_Number = $row['Invoice_Number'];
            $Grn_Number = '';
            $Purchase_Order_ID='';
        /*selecting the grn total amount*/
        $sql_select_grn_amount=mysqli_query($conn,"SELECT SUM(Quantity_Required*Price) AS grn_amount FROM tbl_grn_without_purchase_order_items WHERE Grn_ID={$Grn_ID}") or die(mysqli_error($conn));
        $grn_results=mysqli_fetch_assoc($sql_select_grn_amount);
        $grn_amount=$grn_results['grn_amount'];
        $grn_total_amount +=floatval($grn_amount);
            echo "<tr  title='CLICK RV NUMBER FOR A SPECIFIC REPORT'><td >" . $temp . "<b>.</b></td>";
            echo "<td >{$Grn_Note}</td>";
            echo "<td >{$Grn_ID}</td>";
            echo "<td ><a class='view_report' style='display:block;' href='#' onclick='View_Report(this,\"{$Grn_ID}\",\"without_order\");'>{$RV_Number}</a></td>";
            echo "<td style='text-align: right;'>".$Grn_Date->format('Y-m-d')."</td>";
            echo "<td style='text-align: left;'>{$Supplier}</td>";
            echo "<td style='text-align: right;'>{$Delivery_Date}</td>";
            echo "<td style='text-align: left;'>{$Delivery_Person}</td>";
            echo "<td style='text-align: left;'>{$Received_by}</td>";
            echo "<td style='text-align: left;'>{$Invoice_Number}</td>";
            echo "<td style='text-align: right;'>".number_format($grn_amount)."</td>";
            echo "</tr>";
           /* if ($Total_Balance > 0) {
                $Total = $Total_Balance;
            } else {
                $Total = 0;
            }
            echo "<td style='text-align: right;'>" . number_format($Total * $Total_Average_Price) . "</td>";
            echo "</tr>";*/

            $temp++;
            if (($temp % 25) == 0) {
                echo $Title;
            }
    }
    }
    ?>
<tr><td colspan="11"><hr></td></tr>
<tr><td colspan="10" style="text-align: center;"><b> GRN TOTAL AMOUNT</b></td><td style="text-align: right;"><b><?php echo number_format($grn_total_amount); ?></b></td></tr>
<tr><td colspan="11"><hr></td></tr>
</table>
