<link rel="stylesheet" href="table.css" media="screen">
<center><b>List of Removed Rch Services </b></center><link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="style.css" media="screen">
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
    if(isset($_GET['Payment_Cache_ID'])){
        $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    }else{
	$Payment_Cache_ID = '';
    }
    if(isset($_GET['Payment_Cache_ID'])){
        $Transaction_Type = $_GET['Transaction_Type'];
    }else{
	$Transaction_Type = '';
    }
    
    if(isset($_SESSION['Rch'])){
	$Sub_Department_Name = $_SESSION['Rch'];
    }else{
	$Sub_Department_Name = '';
    }
    $total = 0;
    $temp = 1;
    echo '<center><table width =100%>';
    echo '<tr id="thead"><td><b>Sn</b></td>
                <td><b>Service Name</b></td>
                    <td style="text-align:right;" width=8%><b>Price</b></td>
                        <td style="text-align:right;" width=8%><b>Discount</b></td>
                            <td style="text-align: center;" width=8%><b>Quantity</b></td>
                                <td style="text-align:center;" width=8%><b>Sub Total</b></td>
				<td ></td></tr>';
    
    
    $select_Transaction_Items = mysqli_query($conn,
            "select ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Edited_Quantity, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID
		from tbl_item_list_cache ilc, tbl_Items its
		    where ilc.item_id = its.item_id and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Transaction_Type = '$Transaction_Type' and
				Sub_Department_ID = (select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name') and
				    ilc.status = 'removed'");

    while($row = mysqli_fetch_array($select_Transaction_Items)){
        echo "<tr><td>".$temp."</td>";
        echo "<td>".$row['Product_Name']."</td>";
        echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
        echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' readonly='readonly'></td>";
	
        ?>
	<td style='text-align:right;'>
	    <?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
		<input type='text' readonly='readonly' value='<?php echo $Quantity;?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" style='text-align: center;' size=13>
	    <?php }else{ $Quantity = $row['Edited_Quantity']; ?>
		<input type='text' readonly='readonly' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" style='text-align: center;' size=13>
	    <?php } ?>
	</td>
	<?php
	
	
        echo "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount'])*$row['Quantity'])."' style='text-align:right;'></td>";
	echo "<td style='text-align: right;' width=8%><a href='AddRchItems.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Re-Add</a></td>";
	$total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity']);
	$temp++;
    }   echo "</tr>";
        echo "<tr><td colspan=6 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td>
		    <td style='text-align: right;'>
			<a href='rchitems_Iframe.php?Transaction_Type=".$Transaction_Type."&Payment_Cache_ID=".$Payment_Cache_ID."' class = 'art-button'>Back To Service List</a></td></tr>";
    ?></table></center>