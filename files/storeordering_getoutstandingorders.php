<?php
    include_once("./includes/connection.php");
    include_once("./functions/storeorder.php");

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
?>

<legend align="right"><b>OUTSTANDING ORDERS</b></legend>
<table width="100%">
    <tr><td colspan="11"><hr></td></tr>
    <tr>
        <td width="5%"><b>SN</b></td>
        <td width="7%"><b>ORDER NO</b></td>
        <td><b>PREPARED BY</b></td>
        <td><b>APPROVED BY</b></td>
        <td><b>SUB DEPARTMENT NAME</b></td>
        <td><b>ORDERED ITEMS</b></td>
        <td><b>OUTSTANDING ITEMS</b></td>
        <td><b>CREATED DATE</b></td>
        <td><b>SUBMITTED DATE</b></td>
        <td><b>APPROVED DATE</b></td>
        <td width="7%" style="text-align: center;"></td>
    </tr>
    <tr><td colspan="11"><hr></td></tr>
<?php
    $temp = 0;

    $Start_Date = Get_Day_Beginning($Start_Date);
    $End_Date = Get_Day_Ending($End_Date);

    $Approved_Store_Order_SQL = mysqli_query($conn,"SELECT so.Store_Order_ID, so.Approval_Date_Time, emp.Employee_Name,
                                                sd.Sub_Department_Name, so.Supervisor_ID, so.Created_Date_Time, so.Sent_Date_Time,
                                                (SELECT count(*) FROM tbl_store_order_items soi
                                                 WHERE soi.Store_Order_ID = so.Store_Order_ID) as Ordered_Items,
                                                 (SELECT count(*) FROM tbl_store_order_items soi
                                                 WHERE soi.Store_Order_ID = so.Store_Order_ID AND
                                                 Procurement_Status in ('active', 'selected') ) as Outstanding_Items
                                           FROM tbl_store_orders so, tbl_employee emp, tbl_sub_department sd
                                           WHERE Order_Status = 'Approved' AND
                                                emp.Employee_ID = so.Employee_ID AND
                                                so.Sub_Department_ID = sd.Sub_Department_ID AND

                                                (SELECT count(*) FROM tbl_store_order_items soi
                                                 WHERE soi.Store_Order_ID = so.Store_Order_ID AND
                                                 Procurement_Status in ('active', 'selected') ) > 0 AND
                                                 so.Created_Date_Time between '$Start_Date' and '$End_Date'

                                           ORDER BY Store_Order_ID DESC limit 100") or die(mysqli_error($conn));
    $Approved_Store_Order_Num = mysqli_num_rows($Approved_Store_Order_SQL);
    if($Approved_Store_Order_Num > 0){
        while ($data = mysqli_fetch_array($Approved_Store_Order_SQL)) {
            //get supervisor id
            $Supervisor_ID = $data['Supervisor_ID'];
            $slct_supervisor = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Supervisor_ID'") or die(mysqli_error($conn));
            $slct_num = mysqli_num_rows($slct_supervisor);
            if($slct_num > 0){
                while ($dt = mysqli_fetch_array($slct_supervisor)) {
                    $Supervisor_Name = $dt['Employee_Name'];
                }
            }else{
                $Supervisor_Name = '';
            }
?>
    <tr>
        <td><?php echo ++$temp; ?></td>
        <td><?php echo $data['Store_Order_ID']; ?></td>
        <td><?php echo ucwords(strtolower($data['Employee_Name'])); ?></td>
        <td><?php echo ucwords(strtolower($Supervisor_Name)); ?></td>
        <td><?php echo $data['Sub_Department_Name']; ?></td>
        <td><?php echo $data['Ordered_Items']; ?></td>
        <td><?php echo $data['Outstanding_Items']; ?></td>
        <td><?php echo $data['Created_Date_Time']; ?></td>
        <td><?php echo $data['Sent_Date_Time']; ?></td>
        <td><?php echo $data['Approval_Date_Time']; ?></td>
        <td width="10%" style="text-align: center;">
            <input type="button" name="Preview" id="Preview" class="art-button-green" value="PREVIEW ORDER" onclick="Preview_Order(<?php echo $data['Store_Order_ID']; ?>)">
        </td>
    </tr>
<?php                
        }
    }

?>
</table>