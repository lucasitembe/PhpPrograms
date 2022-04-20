<?php if (session_status() == PHP_SESSION_NONE) { session_start();
} ?>
<?php
include("./includes/connection.php");
$temp = 1;

if(isset($_GET['Start_Date'])){
$Start_Date = $_GET['Start_Date'];
}else{
$Start_Date = '';
}

if(isset($_GET['End_Date'])){
$End_Date = $_GET['End_Date'];
}else{
$End_Date = '';
}

if(isset($_GET['Search_Value'])){
$Search_Value = $_GET['Search_Value'];
}else{
$Search_Value = null;
}

if(isset($_GET['Supplier_ID'])){
$Supplier_ID = $_GET['Supplier_ID'];
}else{
$Supplier_ID = null;
}

//get sub department id
if(isset($_SESSION['Procurement_ID'])){
$Sub_Department_ID = $_SESSION['Procurement_ID'];
}else{
$Sub_Department_ID = 0;
}

//get sub department name
if(isset($_SESSION['Procurement_ID'])){
$select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if($num > 0){
while ($data = mysqli_fetch_array($select)) {
$Sub_Department_Name = $data['Sub_Department_Name'];
}
}else{
$Sub_Department_Name = '';
}
}

$filter = " AND  LOWER(poi.Grn_Status) = 'outstanding'";

if(!empty($Start_Date) &&!empty($End_Date)){
$filter = " AND LOWER(poi.Grn_Status) = 'outstanding' AND po.Sent_Date BETWEEN '$Start_Date' AND '$End_Date' ";
}

if (!is_null($Supplier_ID) != null &&!empty($Supplier_ID) && empty($Search_Value)) {
$filter .= " AND po.Supplier_ID = '$Supplier_ID'";
}

if(!empty($Search_Value)){
$filter .= " AND po.Purchase_Order_ID LIKE '%{$Search_Value}%'";
}
?>

<legend align=right><b><?php if(isset($_SESSION['Procurement_ID'])){ echo $Sub_Department_Name;
} ?> ~ Outstanding Orders</b></legend>
<table width=100% border=0>
    <tr><td colspan="8"><hr></td></tr>
    <tr id='thead'>
        <td width=4% style='text-align: center;'><b>Sn</b></td>
        <td width=10% style='text-align: center;'><b>Order #</b></td>
        <td width=15% style='text-align: center;'><b>Store Order #</b></td>
        <td width=15%><b>Order Date & Time</b></td>
        <td width=10%><b>Store Need</b></td>
        <td width=15%><b>Supplier Name</b></td>
        <!--td width=20%><b>Order Description</b></td-->
        <td style='text-align: center;' width="30%"><b>Action</b></td>
    </tr>
    <tr><td colspan="8"><hr></td></tr>
    <?php
    //get details
    $select_data = "SELECT po.Purchase_Order_ID, po.Store_Order_ID, po.Created_Date, sd.Sub_Department_Name,
                                po.Order_Description, sp.Supplier_Name
                                FROM tbl_purchase_order po, tbl_sub_department sd, tbl_employee emp, tbl_supplier sp ,tbl_purchase_order_items poi
                                WHERE po.sub_department_id = sd.sub_department_id AND
                                po.Purchase_Order_ID = poi.Purchase_Order_ID AND
                                po.Supplier_ID = sp.Supplier_ID AND
                                emp.employee_id = po.employee_id  
                                $filter
                                group by poi.Purchase_Order_ID
                                ORDER BY po.Purchase_Order_ID DESC  LIMIT 100";

    $result = mysqli_query($conn,$select_data) or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($result)){
    echo "<tr><td style='text-align: center;'>".$temp."</td>";
    echo "<td style='text-align: center;'>".$row['Purchase_Order_ID']."</td>";
    echo "<td style='text-align: center;'><a href='previousstoreorderreport.php?Store_Order_ID=";
    echo $row['Store_Order_ID']."&PreviousStoreOrder=PreviousStoreOrderThisPage' target='_blank'>";
    echo $row['Store_Order_ID'];
    echo "</a></td>";
    echo "<td>".$row['Created_Date']."</td>";
    echo "<td>".$row['Sub_Department_Name']."</td>";
    echo "<td>".$row['Supplier_Name']."</td>";
    //echo "<td>".$row['Order_Description']."</td>";
    echo "<td style='text-align: center;'>";


    echo "<a href='purchaseordereprocess.php?Purchase_Order_ID=".$row['Purchase_Order_ID']."' class='art-button-green'>Re-process</a>";

    echo "<a href='prevoutstandingpurorderrprt.php?Purchase_Order_ID=".$row['Purchase_Order_ID']."' class='art-button-green' target='_blank'>Preview</a>";
    
    echo "<input type='button' name='Remove_Item' id='Remove_Item' value='Discard' class='art-button-green' onclick='Confirm_Discard_Order(".$row['Purchase_Order_ID'].")'>";

   echo " </td></tr>";
        $temp++;
    }
    ?>
</table>