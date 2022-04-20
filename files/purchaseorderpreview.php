<script src='js/functions.js'></script>
<?php
        include("./includes/header.php");
        include("./includes/connection.php");
        $counter = 0;
	
	//get employee id
	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}
	
	//get employee name
	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Employee_Name = 'Unknown Employee';
	}
	
	//get employee id
	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}
	
    
	//get sub department name
	if(isset($_SESSION['Procurement_ID'])){
            $Sub_Department_ID = $_SESSION['Procurement_ID'];
		$select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where sub_department_id = '$Sub_Department_ID'") or die(mysqli_error($conn));
		$no = mysqli_num_rows($select);
		if($no > 0){
			while($data = mysqli_fetch_array($select)){
				$Sub_Department_Name = $data['Sub_Department_Name'];
			}
		}else{
			$Sub_Department_Name = '';
		}
	}else{
		$Sub_Department_Name = '';
	}
	
	
	
	//get branch id
	if(isset($_SESSION['userinfo']['Branch_ID'])){
		$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
	}else{
		$Branch_ID = 0;
	}
	
	if(!isset($_SESSION['userinfo'])){
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
	}
        
	if(isset($_SESSION['userinfo'])) {
		if(isset($_SESSION['userinfo']['Procurement_Works'])) {
			if($_SESSION['userinfo']['Procurement_Works'] != 'yes'){
				header("Location: ./index.php?InvalidPrivilege=yes");
			}else{
				@session_start();
				if(!isset($_SESSION['Procurement_Supervisor'])){ 
				    header("Location: ./deptsupervisorauthentication.php?SessionCategory=Procurement&InvalidSupervisorAuthentication=yes");
				}
			}
		}else{
			header("Location: ./index.php?InvalidPrivilege=yes");
		}
	}else{
            @session_destroy();
            header("Location: ../index.php?InvalidPrivilege=yes");
        }

    if(isset($_SESSION['userinfo']) && isset($_SESSION['Procurement_Autentication_Level']) && $_SESSION['Procurement_Autentication_Level'] == 1){
        if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){
            echo '<input type="button" name="Load_Store_Order" id="Load_Store_Order" class="art-button-green" onclick="Load_Store_Orders()" value="LOAD STORE ORDER">';
        }

        if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){
            echo "<a href='procurementpendingorders.php?ProcurementPendingOrders=ProcurementPendingOrdersThisPage' class='art-button-green'>PENDING ORDERS</a>";
        }
    }else{
        if(isset($_SESSION['Procurement_Autentication_Level']) && $_SESSION['Procurement_Autentication_Level'] != 100){
            //calculate pending orders based on assigned level
            $before_assigned_level = $_SESSION['Procurement_Autentication_Level'] - 1;
            $p_orders = mysqli_query($conn,"select po.Purchase_Order_ID from tbl_purchase_order po
            where po.Order_Status = 'submitted' and po.Approval_Level = '$before_assigned_level'
            AND (SELECT count(*) FROM tbl_purchase_order_items poi WHERE poi.Purchase_Order_ID = po.Purchase_Order_ID) > 0") or die(mysqli_error($conn));
            $p_num = mysqli_num_rows($p_orders);

            if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){
                echo "<a href='approvalsprocurementpendingorders.php?ProcurementPendingOrders=ProcurementPendingOrdersThisPage' class='art-button-green'>
                            PENDING ORDERS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span style='background-color: red; border-radius: 8px; color: white; padding: 6px;'>".$p_num."</span>
                            </a>";
            }
        }
    }

	if(isset($_SESSION['userinfo'])){
            if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){ 
                    echo "<a href='previousorders.php?PreviousOrder=PreviousOrderThisPage' class='art-button-green'>PREVIOUS ORDERS</a>";
            }
	}
	
	if(isset($_SESSION['userinfo'])){
            if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){ 
                    echo "<a href='procurementworkspage.php?ProcurementWork=ProcurementWorkThisPage' class='art-button-green'>BACK</a>";
            }
	}    
?>

<?php
    //get order information if and only if inserted
    if(isset($_SESSION['Purchase_Order_ID'])){
        $Purchase_Order_ID = $_SESSION['Purchase_Order_ID'];
        $select_Order_Details = mysqli_query($conn,"
                                        select * from tbl_purchase_order po, tbl_purchase_order_items poi,tbl_supplier sup where
                                            po.Purchase_Order_ID = poi.Purchase_Order_ID and
                                                    sup.Supplier_ID = po.Supplier_ID and
                                                    po.Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Order_Details);
        if($no > 0){
            while($row = mysqli_fetch_array($select_Order_Details)){
                $Sub_Department_ID = $row['Sub_Department_ID'];
                $Purchase_Order_ID = $row['Purchase_Order_ID'];
                $Order_Description = $row['Order_Description'];
                $Created_Date = $row['Created_Date'];
                $Supplier_Name = $row['Supplier_Name'];
            }
        }else{
            $Sub_Department_ID = 0;
            $Purchase_Order_ID = '';
            $Order_Description = '';
            $Created_Date = '';
            $Supplier_Name = '';
        }
        
    }else{
        $Sub_Department_ID = 0;
        $Purchase_Order_ID = '';
        $Order_Description = '';
        $Created_Date = '';
        $Supplier_Name = '';
    }
    
    
    //get sub department name
    
    $get_dep = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($get_dep);
    if($no > 0){
        while($data = mysqli_fetch_array($get_dep)){
            $Sub_Department_Name = $data['Sub_Department_Name'];
        }
    }else{
        $Sub_Department_Name = '';
    }
?>


<form action='#' method='post' name='myForm' id='myForm' >
<br/>
<fieldset >
    <legend align='right'><b>Purchase Order ~ <?php if(isset($_SESSION['Procurement_ID'])){ echo $Sub_Department_Name; }?></b></legend>  
        <table width=100%>
	    <tr>
                <td width='10%' style='text-align: right;'>Purchase Number</td>
                <td width='10%' id='Purchase_Number_Area'>
                    <input type='text' name='Purchase_Number'  id='Purchase_Number' readonly='readonly' value='<?php echo $Purchase_Order_ID; ?>'>
                </td>
                
                <td width='10%' style='text-align: right;'>Order Description</td>
                <td>
                    <input type='text' name='Order_Description' id='Order_Description' value='<?php echo $Order_Description; ?>' onclick='updateOrder_Description()' onkeyup='updateOrder_Description()'>
                </td> 
                
                <td width='10%' style='text-align: right;'>Purchase Date</td>
		<td width='16%'>
                    <input type='text' readonly='readonly' name='Purchase_Date' id='Purchase_Date' value='<?php echo $Created_Date; ?>'>
		</td> 
		</tr>
		<tr>
                    <td width='10%' style='text-align: right;'>Store Requesting</td>
                    <td width='16%' id='Store_Requesting_Area'>
                        <select name='Store_Need' id='Store_Need'>
                            <option><?php echo $Sub_Department_Name; ?></option>
                        </select>
                    </td>
		<td style='text-align: right;'>Supplier</td>
		<td id='Supplier_ID_Area'>
                    <select name='Store_Need' id='Store_Need'>
                        <option><?php echo $Supplier_Name; ?></option>
                    </select>
		</td>
                <td style='text-align: right;'>Prepared By&nbsp;</td> 
                <td>
                    <input type='text' readonly='readonly' value='<?php echo $Employee_Name; ?>'>
		</td>
	    </tr>
        </table> 
</center>
</fieldset>
<fieldset>
    <table width=100%>
        <td style='text-align: right;'>
            <a href="previouspurchaseorderreport.php?Purchase_Order_ID=<?php echo $Purchase_Order_ID; ?>" target="_blank" class="art-button-green">PREVIEW</a>
		</td>
    </table>
</fieldset>
<?php
	$select_order_items = mysqli_query($conn,"select itm.Product_Name, poi.Quantity_Required, poi.Remark, poi.Price, poi.Containers_Required ,poi.Items_Per_Container_Required
						from tbl_purchase_order pc, tbl_purchase_order_items poi, tbl_items itm where
						    poi.Item_ID = itm.Item_ID and
                                                        pc.Purchase_Order_ID = poi.Purchase_Order_ID and
                                                            pc.Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn)); 
	$no = mysqli_num_rows($select_order_items);
?>

<!--<iframe width='100%' src='requisition_items_Iframe.php?Purchase_Order_ID=<?php echo $Purchase_Order_ID; ?>' width='100%' height=250px></iframe>-->
<fieldset style='overflow-y: scroll; height: 280px;' id='Items_Fieldset_List'>
	<?php
		$Grand_Total = 0;
		echo '<center><table width = 100% border=0>';
		echo '<tr><td width=4% style="text-align: center;">Sn</td>
			    <td>Item Name</td>
				<td width=7% style="text-align: center;">Containers</td>
				<td width=10% style="text-align: right;">Items per Container</td>
				<td width=7% style="text-align: right;">Quantity</td>
				<td width=8% style="text-align: right;">Price</td>
				<td width=10% style="text-align: right;">Sub Total</td></tr>';
		
		
		$Temp=1; $total = 0;
		while($row = mysqli_fetch_array($select_order_items)){ 
		    echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
		    echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
		    echo "<td><input type='text' id='QR' readonly='readonly' value='".$row['Containers_Required']."' style='text-align: right;'></td>";
		    echo "<td><input type='text' id='QR' readonly='readonly' value='".$row['Items_Per_Container_Required']."' style='text-align: right;'></td>";
		    echo "<td><input type='text' id='QR' readonly='readonly' value='".$row['Quantity_Required']."' style='text-align: right;'></td>";
	?>  
		    <td>
			<input type='text' id='' name='' value='<?php echo $row['Price']; ?>' style='text-align: right;' readonly='readonly'>
		    </td>
	<?php	    
		    echo "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format($row['Quantity_Required'] * $row['Price'])."' style='text-align: right;'></td>";
		    $Grand_Total += ($row['Quantity_Required'] * $row['Price']);
		    //echo "<td><input type='text' readonly='readonly' value='".$row['Remark']."'></td>";
		
		    echo "</tr>";
		    $Temp++;
		}
		echo '</table>';
	?>
</fieldset>
<table width="100%">
	<tr>
		<td style="text-align: right">
			<b>GRAND TOTAL : <?php echo number_format($Grand_Total); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
		</td>
	</tr>
</table>

<script type="text/javascript">
    function Preview_Purchase_Order_Report(Purchase_Order_ID){
        var winClose=popupwindow('previouspurchaseorderreport.php?Purchase_Order_ID='+Purchase_Order_ID+'&PreviousOrderReport=PreviousOrderReportThisPage', 'PURCHASE ORDER DETAILS', 1200, 500);
    }

    function popupwindow(url, title, w, h) {
        var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }
    function Load_Store_Orders(){
        document.location = 'control_purchase_order_sessions.php?New_Purchase_Order=True&NPO=True&PurchaseOrder=PurchaseOrderThisPage';
    }
</script>
<?php
	include("./includes/footer.php");
?>