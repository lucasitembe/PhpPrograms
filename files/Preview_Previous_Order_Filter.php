<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>
<?php
include("./includes/connection.php");
$temp = 1;
if (isset($_GET['Start_Date'])) {
    $Start_Date = $_GET['Start_Date'];
}

if (isset($_GET['End_Date'])) {
    $End_Date = $_GET['End_Date'];
}

if (isset($_GET['Supplier_ID'])) {
    $Supplier_ID = $_GET['Supplier_ID'];
}

if (isset($_GET['Order_No'])) {
    $Order_No = $_GET['Order_No'];
}
//get sub department id
 if(isset($_SESSION['Storage_Info'])){
        $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = 0;
    }

//get sub department name
if (isset($_SESSION['Procurement_ID'])) {
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if ($num > 0) {
        while ($data = mysqli_fetch_array($select)) {
            $Sub_Department_Name = $data['Sub_Department_Name'];
        }
    } else {
        $Sub_Department_Name = '';
    }
}
?>
<legend align=right><b><?php if (isset($_SESSION['Procurement_ID'])) {
    echo $Sub_Department_Name;
} ?> ~ Previous orders</b></legend>
<table width=100% border=0>
    <tr><td colspan="7"><hr></td></tr>
    <tr id='thead'>
        <td width=4% style='text-align: center;'><b>Sn</b></td>
        <td width=12% style='text-align: center;'><b>Order Number</b></td>
        <td width=12% style='text-align: center;'><b>Store Order Number</b></td>
        <td width=13%><b>Order Date & Time</b></td>
        <td width=10%><b>Store Need</b></td>
        <td width=15%><b>Supplier Name</b></td>
        <!--td width=20%><b>Order Description</b></td-->
        <td style='text-align: center;' width="10%"><b>Action</b></td>
    </tr>
    <tr><td colspan="7"><hr></td></tr>
    <?php
    $filter = " and sd.sub_department_id = '$Sub_Department_ID' ";

    if (!empty($Start_Date) && !empty($End_Date)) {
        $filter = " and po.Sent_Date between '$Start_Date' and '$End_Date' and sd.sub_department_id = '$Sub_Department_ID' ";
    }

    if (!empty($Order_No)) {
        $filter = " and po.Purchase_Order_ID = '$Order_No' ";
    }

    if (!empty($Supplier_ID)) {
        $filter .=" and po.supplier_id = '$Supplier_ID' ";
    }
    
    //get details
    $select_data = "SELECT po.Purchase_Order_ID, po.Store_Order_ID, po.Created_Date, sd.Sub_Department_Name,
                                   po.Order_Description, sp.Supplier_Name
                                FROM tbl_purchase_order po, tbl_sub_department sd, tbl_employee emp, tbl_supplier sp
                                WHERE po.sub_department_id = sd.sub_department_id AND
                                po.Supplier_ID = sp.Supplier_ID AND
                                emp.employee_id = po.employee_id AND
                                po.order_status IN ('submitted','Served')
                                $filter
                                ORDER BY po.Purchase_Order_ID DESC
                                limit 100";

    $result = mysqli_query($conn,$select_data) or die(mysqli_error($conn));
    while ($row = mysqli_fetch_array($result)) {
        echo "<tr><td style='text-align: center;'>" . $temp . "</td>";
        echo "<td style='text-align: center;'>" . $row['Purchase_Order_ID'] . "</td>";
        echo "<td style='text-align: center;'><a href='previousstoreorderreport.php?Store_Order_ID=";
        echo $row['Store_Order_ID'] . "&PreviousStoreOrder=PreviousStoreOrderThisPage' target='_blank'>";
        echo $row['Store_Order_ID'];
        echo "</a></td>";
        echo "<td>" . $row['Created_Date'] . "</td>";
        echo "<td>" . $row['Sub_Department_Name'] . "</td>";
        echo "<td>" . $row['Supplier_Name'] . "</td>";
        //echo "<td>".$row['Order_Description']."</td>";
        echo "<td width=4% style='text-align: center;'>
                        <input type='button' name='Display' id='Display' value='PREVIEW REPORT' class='art-button-green' onclick='Preview_Purchase_Order_Report(" . $row['Purchase_Order_ID'] . ")'>
                        </td></tr>";
        $temp++;
    }
    ?>
</table>