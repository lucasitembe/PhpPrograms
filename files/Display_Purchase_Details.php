<?php
    include("./includes/connection.php");
    @session_start();
    
    //get Purchase_Order_ID
    if(isset($_GET['Purchase_Order_ID'])){
        $Purchase_Order_ID = $_GET['Purchase_Order_ID'];
    }else{
        $Purchase_Order_ID = 0;
    }

    //get all basic information
    $select_info = mysqli_query($conn,"select po.Purchase_Order_ID, sp.Supplier_Name, po.Created_Date, sd.Sub_Department_Name, emp.Employee_Name, po.Order_Description from 
    							tbl_purchase_order po, tbl_sub_department sd, tbl_supplier sp, tbl_employee emp where
                                po.Sub_Department_ID = sd.Sub_Department_ID and
                                po.Supplier_ID = sp.Supplier_ID and
                                po.Employee_ID = emp.EMployee_ID and
                                po.Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));
    echo "<table width='100%'>";
    while($row = mysqli_fetch_array($select_info)){
?>
        <tr>
        	<td width=20%><b>Purchase Order Number</b></td>
        	<td><?php echo $row['Purchase_Order_ID']; ?></td>
        	<td width=15%><b>Order Date  </b></td>
        	<td><?php echo $row['Created_Date']; ?></td>
        </tr>
        <tr>
        	<td><b>Store Need  </b></td>
        	<td style='text-align: left;'><?php echo $row['Sub_Department_Name']; ?></td>
        	<td width=15%><b>Supplier  </b></td>
        	<td><?php echo $row['Supplier_Name']; ?></td>
        </tr>
        <tr>
        	<td><b>Prepared By  </b></td>
        	<td><?php echo $row['Employee_Name']; ?></td>
        	<td width=15%><b>Order Description  </b></td>
        	<td><?php echo $row['Order_Description']; ?></td>
        </tr>
<?php
    }
    echo "</table><br/>";            
?>
<fieldset style='overflow-y: scroll; height: 250px;' id='Items_Fieldset'>
    <table width='100%'>
    	<tr><td colspan=7><hr></td></tr>
        <tr>
            <td width=5%><b>SN</b></td>
            <td><b>ITEM NAME</b></td>
            <td width=7% style='text-align: center;'><b>CONTAINERS</b></td>
            <td width=14% style='text-align: right;'><b>ITEMS PER CONTAINER</b></td>
            <td width=7% style='text-align: right;'><b>QUANTITY</b></td>
            <td width=14% style='text-align: right;'><b>UNIT PRICE</b></td>
            <td width=14% style='text-align: right;'><b>AMOUNT</b></td>
        </tr>
    <tr><td colspan=7><hr></td></tr>
<?php           
    //select data from the table tbl_purchase_order_items ,
    $temp = 1; $Amount = 0; $Grand_Total = 0;
    $select_data = mysqli_query($conn,"select * from tbl_purchase_order_items poi, tbl_items it where
                                poi.item_id = it.item_id and Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_data)){
?>
        <tr><td><b><?php echo $temp; ?></b></td><td><?php echo $row['Product_Name']; ?></td>
        <td style='text-align: right;'><?php echo $row['Containers_Required']; ?></td>
        <td style='text-align: right;'><?php echo $row['Items_Per_Container_Required']; ?></td>
        <td style='text-align: right;'><?php echo $row['Quantity_Required']; ?></td>
        <td style='text-align: right;'><?php echo number_format($row['Price']); ?></td>
<?php
        $Amount = $row['Quantity_Required'] * $row['Price'];
        $Grand_Total = $Grand_Total + $Amount;
?>
        <td style='text-align: right;'><?php echo number_format($Amount); ?></td></tr>
<?php
        $temp++;
    }
?>
    <tr><td colspan=7><hr></td></tr>
    <tr><td colspan=6 style='text-align: left;'><b>GRAND TOTAL </td><td style='text-align: right;'><b><?php echo number_format($Grand_Total); ?> </b></td></tr>
    <tr><td colspan=7><hr></td></tr>
    </table>
</fieldset>
<fieldset>
	<table width="100%">
		<tr>
			<td style="text-align: right;" width="10%"><b>USERNAME&nbsp;</b></td>
			<td width="15%">
				<input type="text" name="Username" id="Username" autocomplete="off" placeholder="~~~ Username ~~~" style="text-align: center;">
			</td>
			<td style="text-align: right;" width="10%"><b>PASSWORD&nbsp;</b></td>
			<td width="15%">
				<input type="password" name="Password" id="Password" autocomplete="off" placeholder="~~~ Password ~~~" style="text-align: center;">
			</td>
			<td width="15%" style="text-align: right;">
				<input type="button" name="button" id="button" value="APPROVE ORDER" class="art-button-green" onclick="Approve_Purchase_Order('<?php echo $Purchase_Order_ID; ?>');">
			</td>
			<td style="text-align: right;">
				<b>GRAND TOTAL : <?php echo number_format($Grand_Total); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
	</table>
</fieldset>

