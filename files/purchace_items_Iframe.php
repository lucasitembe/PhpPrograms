<link rel="stylesheet" href="style.css" media="screen">
    <link rel="stylesheet" href="css_style.css" media="screen">
        
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    
    <link rel="stylesheet" href="style.responsive.css" media="all">
    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
 

    <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>
        <script src="js/tabcontent.js" type="text/javascript"></script>
    


<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
.art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
.ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
.ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }

</style>
<?php
    include("./includes/connection.php"); 
	if(isset($_GET['Purchase_Order_ID'])){
	    $Purchase_Order_ID = $_GET['Purchase_Order_ID'];
	}else{
	    $Purchase_Order_ID = 0;
	}
	
    echo '<center><table width = 100% border=0>';
    echo '<tr><td width=4% style="text-align: center;"><b>Sn</b></td>
                <td><b>Item Name</b></td>
                    <td width=7% style="text-align: center;"><b>Quantity</b></td>
			<td width=7% style="text-align: right;"><b>Price</b></td>
			    <td width=7% style="text-align: right;"><b>Amount</b></td>
			    <td width=20% style="text-align: center;"><b>Remark</b></td></tr>';
    
    
    $select_Transaction_Items = mysqli_query($conn,"select opi.Order_Item_ID, itm.Product_Name, opi.Quantity_Required, opi.Price, opi.Remark from tbl_purchase_order_items opi, tbl_items itm where
						itm.Item_ID = opi.Item_ID and
						    Purchase_Order_ID ='$Purchase_Order_ID'") or die(mysqli_error($conn)); 

    $no_of_items = mysqli_num_rows($select_Transaction_Items);
    $Temp=1; $GrandTotal = 0;
    while($row = mysqli_fetch_array($select_Transaction_Items)){
	$total=$row['Quantity_Required'] * $row['Price'];
	$GrandTotal = $GrandTotal + $total;
	echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
	echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
	echo "<td><input type='text' value='".$row['Quantity_Required']."' style='text-align: center;'></td>";
	echo "<td><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align: right;'></td>";
	echo "<td><input type='text' readonly='readonly' value='".number_format($total)."' style='text-align: right;'></td>";
	echo "<td><input type='text' value='".$row['Remark']."'></td>";
	if($no_of_items > 1){
	    echo "<td width=6%><a href='Remove_Order_Item.php?Order_Item_ID=".$row['Order_Item_ID']."&Purchase_Order_ID=".$Purchase_Order_ID."' class='art-button-green'>Remove</a></td>";
	}
	echo "</tr>";
	$Temp++;
    }
    echo "<tr><td colspan=5 style='text-align: right;'><b>Total : </b></td>
		<td><input type='text' readonly='readonly' value='".number_format($GrandTotal)."' style='text-align: center;'></td></tr>"
?></table></center>