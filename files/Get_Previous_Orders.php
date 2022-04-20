<?php
    @session_start();
	include("./includes/connection.php");

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
    if(isset($_GET['Approval_Orders'])){
        $Approval_Orders = $_GET['Approval_Orders'];
    }else{
        $Approval_Orders = '';
    }
?>

<legend align="right"><b>SUBMITTED ORDERS</b></legend>
<table width="100%">
    <tr><td colspan="9"><hr></td></tr>
    <tr>
        <td width="5%"><b>SN</b></td>
        <td width="7%"><b>ORDER NO</b></td>
        <td><b>PREPARED BY</b></td>
        <td><b>SUB DEPARTMENT NAME</b></td>
        <td><b>CREATED DATE</b></td>
        <td><b>SUBMITTED DATE</b></td>
        <td colspan="2" width="20%" style="text-align: center;"><b>ACTIONS</b></td>
    </tr>
    <tr><td colspan="9"><hr></td></tr>
<?php
    $temp = 0;
    if(strtolower($Approval_Orders) == 'no'){
        $select = mysqli_query($conn,"SELECT Store_Order_ID, emp.Employee_Name, Created_Date_Time, sd.Sub_Department_Name, Sent_Date_Time
                                       FROM tbl_store_orders so, tbl_employee emp, tbl_sub_department sd
                                       WHERE Order_Status in ('Submitted', 'pending', 'saved') AND
                                            emp.Employee_ID = so.Employee_ID AND
                                            so.Sub_Department_ID = sd.Sub_Department_ID AND
                                            so.Created_Date_Time between '$Start_Date' and '$End_Date' AND
                                            (select count(*) from tbl_store_order_items soi
                                            where soi.Store_Order_ID = so.Store_Order_ID) > 0
                                       ORDER BY Store_Order_ID DESC limit 100") or die(mysqli_error($conn));
    }else{
        $select = mysqli_query($conn,"SELECT Store_Order_ID, emp.Employee_Name, Created_Date_Time, sd.Sub_Department_Name, Sent_Date_Time
                                       FROM tbl_store_orders so, tbl_employee emp, tbl_sub_department sd
                                       WHERE Order_Status = 'Submitted' AND
                                            emp.Employee_ID = so.Employee_ID AND
                                            so.Sub_Department_ID = sd.Sub_Department_ID AND
                                            so.Created_Date_Time between '$Start_Date' and '$End_Date' AND
                                            (select count(*) from tbl_store_order_items soi
                                            where soi.Store_Order_ID = so.Store_Order_ID) > 0
                                       ORDER BY Store_Order_ID DESC limit 100") or die(mysqli_error($conn));
    }
    /*$select = mysqli_query($conn,"select Store_Order_ID, emp.Employee_Name, Created_Date_Time, sd.Sub_Department_Name, Sent_Date_Time from tbl_store_orders so, tbl_employee emp, tbl_sub_department sd
                                where Order_Status = 'Submitted' and
                                emp.Employee_ID = so.Employee_ID and
                                so.Sent_Date_Time between '$Start_Date' and '$End_Date' and
                                so.Sub_Department_ID = sd.Sub_Department_ID order by Store_Order_ID desc") or die(mysqli_error($conn));*/
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
?>
    <tr>
        <td><?php echo ++$temp; ?></td>
        <td><?php echo $data['Store_Order_ID']; ?></td>
        <td><?php echo ucwords(strtolower($data['Employee_Name'])); ?></td>
        <td><?php echo $data['Sub_Department_Name']; ?></td>
        <td><?php echo $data['Created_Date_Time']; ?></td>
        <td><?php echo $data['Sent_Date_Time']; ?></td>
        <td width="10%" style="text-align: center;">
            <input type="button" name="View_Edit" id="View_Edit" class="art-button-green" value="EDIT">
        </td>
        <td width="10%" style="text-align: center;">
            <input type="button" name="Preview" id="Preview" class="art-button-green" value="PREVIEW">
        </td>
        <td width="10%" style="text-align: center;">
            <input type="button" name="Cancel" id="Cancel" class="art-button-green" value="CANCEL" onclick="Cancel_Order(<?php echo $data['Store_Order_ID']; ?>)">
        </td>
    </tr>
<?php                
        }
    }

?>
</table>