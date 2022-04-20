<link rel="stylesheet" href="style.css" media="screen">
        
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    
    <link rel="stylesheet" href="style.responsive.css" media="all">
 

    <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>


<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
.art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
.ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
.ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
</style>
<?php
	@session_start();
	include("./includes/connection.php");
	//get Purchase_Order_ID
	if(isset($_GET['Purchase_Order_ID'])){
		$Purchase_Order_ID = $_GET['Purchase_Order_ID'];
	}else{
		$Purchase_Order_ID = 0;
	}
?> 
	    <table width=100%>
		<tr>
		    <td width=3% style='text-align: center;'><b>Sn</b></td>
		    <td><b>Particular</b></td>
		    <td width=5%><b>Qty Required</b></td>
		    <td width=5%><b>Qty Received</b></td>
		    <td width=5%><b>Buying Price</b></td> 
		</tr>
		<?php
		//get list of item ordered
		$select_items = mysqli_query($conn,"select * from tbl_purchase_order po, tbl_purchase_order_items poi, tbl_items itm where
						po.Purchase_Order_ID = poi.Purchase_Order_ID and
							poi.item_id = itm.item_id and
								po.purchase_order_id = '$Purchase_Order_ID'") or die(mysqli_error($conn));
		$no2 = mysqli_num_rows($select_items);
		$temp = 1;
		if($no2 > 0){
			while($row = mysqli_fetch_array($select_items)){
				echo "<tr><td><input type='text' value='".$temp."'  readonly='readonly' style='text-align: center;'></td>";
				echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
				echo "<td><input type='text' value='".$row['Quantity_Required']."' readonly='readonly'></td>";
				echo "<td><input type='text' readonly='readonly'></td>";
				echo "<td><input type='text' readonly='readonly'></td></tr>";
				$temp++;
			}
		}
		?>
		</table>
		 