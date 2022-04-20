<center><b>List of Prescribed Medication </b></center><link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
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
    <script type='text/javascript'>
        function pharmacyQuantityUpdate(Payment_Item_Cache_List_ID,Quantity) {
		if(window.XMLHttpRequest) {
		    mm = new XMLHttpRequest();
		}
		else if(window.ActiveXObject){ 
		    mm = new ActiveXObject('Micrsoft.XMLHTTP');
		    mm.overrideMimeType('text/xml');
		} 
		mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
		mm.open('GET','pharmacyQuantityUpdate.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Quantity='+Quantity,true);
		mm.send();
        }
        function AJAXP() {
	var data = mm.responseText;
            if(mm.readyState == 4){
            }
        }
    </script>
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
    
    if(isset($_SESSION['Pharmacy'])){
	$Sub_Department_Name = $_SESSION['Pharmacy'];
    }else{
	$Sub_Department_Name = '';
    }
    $total = 0;
    $temp = 1;
    $data='';
	$dataAmount='';
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td style="text-align: center;" width=5%><b>Sn</b></td>
                <td><b>Medication Name</b></td>
                    <td style="text-align: left;" width=15%><b>Dosage</b></td>
			<td style="text-align: right;" width=8%><b>Price</b></td>
			    <td style="text-align: right;" width=8%><b>Discount</b></td>
				<td style="text-align: center;" width=8%><b>Quantity</b></td>
				    <td style="text-align: center;" width=8%><b>Balance</b></td>
					<td style="text-align: center;" width=8%><b>Sub Total</b></td>
					    <td style="text-align: center;" width=6%><b>Action</b></td></tr>';

$select_Transaction_Items_Dispensed = mysqli_query($conn,
            "select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Doctor_Comment, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type' and
			    Sub_Department_ID = (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name') and
				ilc.status = 'dispensed'"); 
$no = mysqli_num_rows($select_Transaction_Items_Dispensed);
if($no > 0){
    //Check if there is active patient waiting
	
	$select_Transaction_Items_Active = mysqli_query($conn,
            "select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type' and
			    Sub_Department_ID = (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name') and
				ilc.status = 'active'"); 

    $no = mysqli_num_rows($select_Transaction_Items_Active);
    
    //display all medications that not approved
    if($no > 0){
	  while($row = mysqli_fetch_array($select_Transaction_Items_Active)){
	    echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
	    echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
	    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
	    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
	    echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' readonly='readonly'></td>";
	    ?>
	<td style='text-align:right;'>
	    <?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
		<input type='text' value='<?php echo $Quantity;?>' onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
	    <?php }else{ $Quantity = $row['Edited_Quantity']; ?>
		<input type='text' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
	    <?php } ?>
	</td>
	<!--calculate balance-->
	<?php
	    $Item_ID = $row['Item_ID'];
	    //get sub department id
	    if(isset($_SESSION['Pharmacy'])){
		$Sub_Department_Name = $_SESSION['Pharmacy'];
	    }else{
		$Sub_Department_Name = '';
	    }
	    
	    //get actual balance
	    $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
					    Item_ID = '$Item_ID' and
						Sub_Department_ID =
						    (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name')") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($sql_get_balance);
	    if($num > 0){
		while($dt = mysqli_fetch_array($sql_get_balance)){
		    $Item_Balance = $dt['Item_Balance'];
		}
	    }else{
		$Item_Balance = 0;
	    }
	?>
	<td style='text-align: right;' id='Balance' name='Balance'>
	    <input type='text' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
	</td>
	<?php
	    echo "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
	    echo '<td style="text-align: right;" width=8%>
                      <button type="button" class="removeItemFromCache art-button" onclick="removeitem('.$row["Payment_Item_Cache_List_ID"].')">Remove</button>
                    </td>';
	    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
	    $temp++;
	}   echo "</tr>";
	
	//CHECK IF THERE IS REMOVED ITEM TO DISPLAY THE BUTTON THAT VIEW REMOVED ITEM(S) WHEN NEEDED
	$Check_Items = "select status from tbl_item_list_cache where payment_Cache_ID = '$Payment_Cache_ID' and status = 'removed'";
	$Check_Items_Results = mysqli_query($conn,$Check_Items);
	$No_Of_Items = mysqli_num_rows($Check_Items_Results);
	if($No_Of_Items > 0){
	    $dataAmount= "<td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b>
		          <button type='button' class='removeItemFromCache art-button' onclick='vieweRemovedItem()'>View Removed Items</button>   
                      
                         </td>";
	}else{
	    $dataAmount= "<td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td>";
	}
	}else{
	  //echo 'approved';
		//check if there is any paid
		$select_Transaction_Items_Paid = mysqli_query($conn,
		"select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		    from tbl_item_list_cache ilc, tbl_items its
			where ilc.item_id = its.item_id and
			    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
				ilc.Transaction_Type = '$Transaction_Type' and
				    Sub_Department_ID = (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name') and
					ilc.status = 'paid'");
					$no = mysqli_num_rows($select_Transaction_Items_Paid);
		if($no > 0){
		
		   while($row = mysqli_fetch_array($select_Transaction_Items_Paid)){
			echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
			echo "<td><input type='text' value='".$row['Product_Name']."'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
			echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' readonly='readonly'></td>";
			?>
			<td style='text-align:right;'>
			    <?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
				<input type='text' readonly='readonly' value='<?php echo $Quantity;?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
			    <?php }else{ $Quantity = $row['Edited_Quantity']; ?>
				<input type='text' readonly='readonly' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
			    <?php } ?>
			</td>
			<!--calculate balance-->
			<?php
			    $Item_ID = $row['Item_ID'];
			    //get sub department id
			    if(isset($_SESSION['Pharmacy'])){
				$Sub_Department_Name = $_SESSION['Pharmacy'];
			    }else{
				$Sub_Department_Name = '';
			    }
			    
			    //get actual balance
			    $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
							    Item_ID = '$Item_ID' and
								Sub_Department_ID =
								    (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name')") or die(mysqli_error($conn));
			    $num = mysqli_num_rows($sql_get_balance);
			    if($num > 0){
				while($dt = mysqli_fetch_array($sql_get_balance)){
				    $Item_Balance = $dt['Item_Balance'];
				}
			    }else{
				$Item_Balance = 0;
			    }
			?>
			<td style='text-align: right;' id='Balance' name='Balance'>
			    <input type='text' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
			</td>
			<?php
			    echo "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
			   // echo "<td style='text-align: right;' width=8%><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
			    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
			    $temp++;
			}   echo "</tr>";
		}else{
    while($row = mysqli_fetch_array($select_Transaction_Items_Dispensed)){
	    echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
	    echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
	    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
	    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
	    echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' readonly='readonly'></td>";
	    ?>
	<td style='text-align:right;'>
	    <?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
		<input type='text' value='<?php echo $Quantity;?>' readonly='readonly onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
	    <?php }else{ $Quantity = $row['Edited_Quantity']; ?>
		<input type='text' value='<?php echo $Quantity; ?>' readonly='readonly onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
	    <?php } ?>
	</td>
	<!--calculate balance-->
	<?php
	    $Item_ID = $row['Item_ID'];
	    //get sub department id
	    if(isset($_SESSION['Pharmacy'])){
		$Sub_Department_Name = $_SESSION['Pharmacy'];
	    }else{
		$Sub_Department_Name = '';
	    }
	    
	    //get actual balance
	    $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
					    Item_ID = '$Item_ID' and
						Sub_Department_ID =
						    (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name')") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($sql_get_balance);
	    if($num > 0){
		while($dt = mysqli_fetch_array($sql_get_balance)){
		    $Item_Balance = $dt['Item_Balance'];
		}
	    }else{
		$Item_Balance = 0;
	    }
	?>
	<td style='text-align: right;' id='Balance' name='Balance'>
	    <input type='text' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
	</td>
	<?php
	    echo "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
	    //echo "<td style='text-align: right;' width='8%'><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
	    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
	    $temp++;
	}   echo "</tr>";
	}
	echo "<tr><td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>";
	}
}else{
    $select_Transaction_Items_Active = mysqli_query($conn,
            "select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type' and
			    Sub_Department_ID = (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name') and
				ilc.status = 'active'"); 

    $no = mysqli_num_rows($select_Transaction_Items_Active);
    
    //display all medications that not approved
    if($no > 0){
	while($row = mysqli_fetch_array($select_Transaction_Items_Active)){
	    echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
	    echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
	    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
	    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
	    echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' readonly='readonly'></td>";
	    ?>
	<td style='text-align:right;'>
	    <?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
		<input type='text' value='<?php echo $Quantity;?>' onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
	    <?php }else{ $Quantity = $row['Edited_Quantity']; ?>
		<input type='text' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
	    <?php } ?>
	</td>
	<!--calculate balance-->
	<?php
	    $Item_ID = $row['Item_ID'];
	    //get sub department id
	    if(isset($_SESSION['Pharmacy'])){
		$Sub_Department_Name = $_SESSION['Pharmacy'];
	    }else{
		$Sub_Department_Name = '';
	    }
	    
	    //get actual balance
	    $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
					    Item_ID = '$Item_ID' and
						Sub_Department_ID =
						    (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name')") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($sql_get_balance);
	    if($num > 0){
		while($dt = mysqli_fetch_array($sql_get_balance)){
		    $Item_Balance = $dt['Item_Balance'];
		}
	    }else{
		$Item_Balance = 0;
	    }
	?>
	<td style='text-align: right;' id='Balance' name='Balance'>
	    <input type='text' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
	</td>
	<?php
	    echo "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
	    echo '<td style="text-align: right;" width=8%>
                      <button type="button" class="removeItemFromCache art-button" onclick="removeitem('.$row["Payment_Item_Cache_List_ID"].')">Remove</button>
                    </td>';
	    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
	    $temp++;
	}   echo "</tr>";
	
	//CHECK IF THERE IS REMOVED ITEM TO DISPLAY THE BUTTON THAT VIEW REMOVED ITEM(S) WHEN NEEDED
	$Check_Items = "select status from tbl_item_list_cache where payment_Cache_ID = '$Payment_Cache_ID' and status = 'removed'";
	$Check_Items_Results = mysqli_query($conn,$Check_Items);
	$No_Of_Items = mysqli_num_rows($Check_Items_Results);
	if($No_Of_Items > 0){
	    $dataAmount= "<td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b>
		          <button type='button' class='removeItemFromCache art-button' onclick='vieweRemovedItem()'>View Removed Items</button>   
                      
                         </td>";
	}else{
	    $dataAmount= "<td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td>";
	}
    }else{
	//check if there is any removed medication but we make sure no any approved medication
	$select_Transaction_Items_Removed = mysqli_query($conn,
            "select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_items its
		    where ilc.item_id = its.item_id and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Transaction_Type = '$Transaction_Type' and
				Sub_Department_ID = (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name') and
				    ilc.status = 'removed'");
     	
	$no = mysqli_num_rows($select_Transaction_Items_Removed);
	if($no > 0){
	    //check if there is any approved madication
	    $select_Transaction_Items_Approved = mysqli_query($conn,
            "select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_items its
		    where ilc.item_id = its.item_id and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Transaction_Type = '$Transaction_Type' and
				Sub_Department_ID = (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name') and
				    ilc.status = 'approved'");
	    
	    $no = mysqli_num_rows($select_Transaction_Items_Approved);
	    if($no > 0){
		//echo 'approved';
		//check if there is any paid
		$select_Transaction_Items_Paid = mysqli_query($conn,
		"select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		    from tbl_item_list_cache ilc, tbl_items its
			where ilc.item_id = its.item_id and
			    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
				ilc.Transaction_Type = '$Transaction_Type' and
				    Sub_Department_ID = (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name') and
					ilc.status = 'paid'");
		
		$no = mysqli_num_rows($select_Transaction_Items_Paid);
		if($no > 0){
		    //Check if there is no any dispensed medication
		    $select_Transaction_Items_Dispensed = mysqli_query($conn,
		    "select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		    from tbl_item_list_cache ilc, tbl_items its
			where ilc.item_id = its.item_id and
			    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
				ilc.Transaction_Type = '$Transaction_Type' and
				    Sub_Department_ID = (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name') and
					ilc.status = 'dispensed'");
		
		    $no = mysqli_num_rows($select_Transaction_Items_Dispensed);
		    if($no > 0){
			while($row = mysqli_fetch_array($select_Transaction_Items_Dispensed)){
			echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
			echo "<td><input type='text' value='".$row['Product_Name']."'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
			echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' readonly='readonly'></td>";
			?>
			<td style='text-align:right;'>
			    <?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
				<input type='text' readonly='readonly' value='<?php echo $Quantity;?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
			    <?php }else{ $Quantity = $row['Edited_Quantity']; ?>
				<input type='text' readonly='readonly' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
			    <?php } ?>
			</td>
			<!--calculate balance-->
			<?php
			    $Item_ID = $row['Item_ID'];
			    //get sub department id
			    if(isset($_SESSION['Pharmacy'])){
				$Sub_Department_Name = $_SESSION['Pharmacy'];
			    }else{
				$Sub_Department_Name = '';
			    }
			    
			    //get actual balance
			    $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
							    Item_ID = '$Item_ID' and
								Sub_Department_ID =
								    (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name')") or die(mysqli_error($conn));
			    $num = mysqli_num_rows($sql_get_balance);
			    if($num > 0){
				while($dt = mysqli_fetch_array($sql_get_balance)){
				    $Item_Balance = $dt['Item_Balance'];
				}
			    }else{
				$Item_Balance = 0;
			    }
			?>
			<td style='text-align: right;' id='Balance' name='Balance'>
			    <input type='text' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
			</td>
			<?php
			    echo "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
			   // echo "<td style='text-align: right;' width=8%><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
			    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
			    $temp++;
			}   echo "</tr>";
			echo "<tr><td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>";    
		    }else{
			while($row = mysqli_fetch_array($select_Transaction_Items_Paid)){
			echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
			echo "<td><input type='text' value='".$row['Product_Name']."'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
			echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' readonly='readonly'></td>";
			?>
			<td style='text-align:right;'>
			    <?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
				<input type='text' readonly='readonly' value='<?php echo $Quantity;?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
			    <?php }else{ $Quantity = $row['Edited_Quantity']; ?>
				<input type='text' readonly='readonly' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
			    <?php } ?>
			</td>
			<!--calculate balance-->
			<?php
			    $Item_ID = $row['Item_ID'];
			    //get sub department id
			    if(isset($_SESSION['Pharmacy'])){
				$Sub_Department_Name = $_SESSION['Pharmacy'];
			    }else{
				$Sub_Department_Name = '';
			    }
			    
			    //get actual balance
			    $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
							    Item_ID = '$Item_ID' and
								Sub_Department_ID =
								    (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name')") or die(mysqli_error($conn));
			    $num = mysqli_num_rows($sql_get_balance);
			    if($num > 0){
				while($dt = mysqli_fetch_array($sql_get_balance)){
				    $Item_Balance = $dt['Item_Balance'];
				}
			    }else{
				$Item_Balance = 0;
			    }
			?>
			<td style='text-align: right;' id='Balance' name='Balance'>
			    <input type='text' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
			</td>
			<?php
			    echo "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
			   // echo "<td style='text-align: right;' width=8%><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
			    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
			    $temp++;
			}   echo "</tr>";
			echo "<tr><td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>";    
		    }
		}else{     
		    while($row = mysqli_fetch_array($select_Transaction_Items_Approved)){
		    echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
		    echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
		    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
		    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
		    echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' readonly='readonly'></td>";
		    ?>
		    <td style='text-align:right;'>
			<?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
			    <input type='text' value='<?php echo $Quantity;?>' onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
			<?php }else{ $Quantity = $row['Edited_Quantity']; ?>
			    <input type='text' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
			<?php } ?>
		    </td>
		    <!--calculate balance-->
		    <?php
			$Item_ID = $row['Item_ID'];
			//get sub department id
			if(isset($_SESSION['Pharmacy'])){
			    $Sub_Department_Name = $_SESSION['Pharmacy'];
			}else{
			    $Sub_Department_Name = '';
			}
			
			//get actual balance
			$sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
							Item_ID = '$Item_ID' and
							    Sub_Department_ID =
								(select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name')") or die(mysqli_error($conn));
			$num = mysqli_num_rows($sql_get_balance);
			if($num > 0){
			    while($dt = mysqli_fetch_array($sql_get_balance)){
				$Item_Balance = $dt['Item_Balance'];
			    }
			}else{
			    $Item_Balance = 0;
			}
		    ?>
		    <td style='text-align: right;' id='Balance' name='Balance'>
			<input type='text' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
		    </td>
		    <?php
			echo "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
		       // echo "<td style='text-align: right;' width=8%><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
			$total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
			$temp++;
		    }   echo "</tr>";
		    echo "<tr><td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>";  
		}
		 
	    }else{
		while($row = mysqli_fetch_array($select_Transaction_Items_Approved)){
		echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
		echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
		echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
		echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
		echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' readonly='readonly'></td>";
		?>
		<td style='text-align:right;'>
		    <?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
			<input type='text' value='<?php echo $Quantity;?>' onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
		    <?php }else{ $Quantity = $row['Edited_Quantity']; ?>
			<input type='text' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
		    <?php } ?>
		</td>
		<!--calculate balance-->
		<?php
		    $Item_ID = $row['Item_ID'];
		    //get sub department id
		    if(isset($_SESSION['Pharmacy'])){
			$Sub_Department_Name = $_SESSION['Pharmacy'];
		    }else{
			$Sub_Department_Name = '';
		    }
		    
		    //get actual balance
		    $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
						    Item_ID = '$Item_ID' and
							Sub_Department_ID =
							    (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name')") or die(mysqli_error($conn));
		    $num = mysqli_num_rows($sql_get_balance);
		    if($num > 0){
			while($dt = mysqli_fetch_array($sql_get_balance)){
			    $Item_Balance = $dt['Item_Balance'];
			}
		    }else{
			$Item_Balance = 0;
		    }
		?>
		<td style='text-align: right;' id='Balance' name='Balance'>
		    <input type='text' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
		</td>
		<?php
		    echo "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
		   // echo "<td style='text-align: right;' width=8%><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
		    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
		    $temp++;
		}   echo "</tr>";
		
		//CHECK IF THERE IS REMOVED ITEM TO DISPLAY THE BUTTON THAT VIEW REMOVED ITEM(S) WHEN NEEDED
		$Check_Items = "select status from tbl_item_list_cache where payment_Cache_ID = '$Payment_Cache_ID' and status = 'removed'";
		$Check_Items_Results = mysqli_query($conn,$Check_Items);
		$No_Of_Items = mysqli_num_rows($Check_Items_Results);
		if($No_Of_Items > 0){
		    echo "<tr><td colspan=5 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td><td colspan=2 style='text-align: right;'>
			    <a href='Pharmacy_Works_Page_Iframe2.php?Transaction_Type=".$Transaction_Type."&Payment_Cache_ID=".$Payment_Cache_ID."' class = 'art-button'>View Removed Medication</a>
				</td></tr>";
		}else{
		    echo "<tr><td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>";
		}  
	    } 
	}else{
	    //check if there is any approved medication but no any paid medication
	    $select_Transaction_Items_Approved = mysqli_query($conn,
            "select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_items its
		    where ilc.item_id = its.item_id and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Transaction_Type = '$Transaction_Type' and
				Sub_Department_ID = (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name') and
				    ilc.status = 'approved'");
	    
	    $no = mysqli_num_rows($select_Transaction_Items_Approved);
	    if($no > 0){
		//echo 'approved';
		//check if there is no paid medication
		$select_Transaction_Items_Paid = mysqli_query($conn,
		"select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		    from tbl_item_list_cache ilc, tbl_items its
			where ilc.item_id = its.item_id and
			    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
				ilc.Transaction_Type = '$Transaction_Type' and
				    Sub_Department_ID = (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name') and
					ilc.status = 'paid'");
		$no = mysqli_num_rows($select_Transaction_Items_Paid);
		if($no > 0){ 
		    while($row = mysqli_fetch_array($select_Transaction_Items_Paid)){
		    echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
		    echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
		    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
		    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
		    echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' readonly='readonly'></td>";
		    ?>
		    <td style='text-align:right;'>
			<?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
			    <input type='text' readonly='readonly' value='<?php echo $Quantity;?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
			<?php }else{ $Quantity = $row['Edited_Quantity']; ?>
			    <input type='text' readonly='readonly' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
			<?php } ?>
		    </td>
		    <!--calculate balance-->
		    <?php
			$Item_ID = $row['Item_ID'];
			//get sub department id
			if(isset($_SESSION['Pharmacy'])){
			    $Sub_Department_Name = $_SESSION['Pharmacy'];
			}else{
			    $Sub_Department_Name = '';
			}
			
			//get actual balance
			$sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
							Item_ID = '$Item_ID' and
							    Sub_Department_ID =
								(select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name')") or die(mysqli_error($conn));
			$num = mysqli_num_rows($sql_get_balance);
			if($num > 0){
			    while($dt = mysqli_fetch_array($sql_get_balance)){
				$Item_Balance = $dt['Item_Balance'];
			    }
			}else{
			    $Item_Balance = 0;
			}
		    ?>
		    <td style='text-align: right;' id='Balance' name='Balance'>
			<input type='text' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
		    </td>
		    <?php
			echo "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
		       // echo "<td style='text-align: right;' width=8%><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
			$total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
			$temp++;
		    }   echo "</tr>";
		    echo "<tr><td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>"; 
		}else{
		    //get back to approved
		    while($row = mysqli_fetch_array($select_Transaction_Items_Approved)){
			echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
			echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
			echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' readonly='readonly'></td>";
			?>
			<td style='text-align:right;'>
			    <?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
				<input type='text' value='<?php echo $Quantity;?>' onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
			    <?php }else{ $Quantity = $row['Edited_Quantity']; ?>
				<input type='text' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
			    <?php } ?>
			</td>
			<!--calculate balance-->
			<?php
			    $Item_ID = $row['Item_ID'];
			    //get sub department id
			    if(isset($_SESSION['Pharmacy'])){
				$Sub_Department_Name = $_SESSION['Pharmacy'];
			    }else{
				$Sub_Department_Name = '';
			    }
			    
			    //get actual balance
			    $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
							    Item_ID = '$Item_ID' and
								Sub_Department_ID =
								    (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name')") or die(mysqli_error($conn));
			    $num = mysqli_num_rows($sql_get_balance);
			    if($num > 0){
				while($dt = mysqli_fetch_array($sql_get_balance)){
				    $Item_Balance = $dt['Item_Balance'];
				}
			    }else{
				$Item_Balance = 0;
			    }
			?>
			<td style='text-align: right;' id='Balance' name='Balance'>
			    <input type='text' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
			</td>
			<?php
			    echo "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
			   // echo "<td style='text-align: right;' width=8%><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
			    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
			    $temp++;
			}   echo "</tr>";
			echo "<tr><td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>"; 
		}
	    }else{
		//serch for paid -final
		//check if there is no paid medication
		$select_Transaction_Items_Paid = mysqli_query($conn,
		"select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		    from tbl_item_list_cache ilc, tbl_items its
			where ilc.item_id = its.item_id and
			    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
				ilc.Transaction_Type = '$Transaction_Type' and
				    Sub_Department_ID = (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name') and
					ilc.status = 'paid'");
		$no = mysqli_num_rows($select_Transaction_Items_Paid);
		if($no > 0){
			while($row = mysqli_fetch_array($select_Transaction_Items_Paid)){
			echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
			echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
			echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' readonly='readonly'></td>";
			?>
			<td style='text-align:right;'>
			    <?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
				<input type='text' readonly='readonly' value='<?php echo $Quantity;?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
			    <?php }else{ $Quantity = $row['Edited_Quantity']; ?>
				<input type='text' readonly='readonly' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = 'Quantity' id='Quantity' style='text-align: center;' size=13>
			    <?php } ?>
			</td>
			<!--calculate balance-->
			<?php
			    $Item_ID = $row['Item_ID'];
			    //get sub department id
			    if(isset($_SESSION['Pharmacy'])){
				$Sub_Department_Name = $_SESSION['Pharmacy'];
			    }else{
				$Sub_Department_Name = '';
			    }
			    
			    //get actual balance
			    $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
							    Item_ID = '$Item_ID' and
								Sub_Department_ID =
								    (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name')") or die(mysqli_error($conn));
			    $num = mysqli_num_rows($sql_get_balance);
			    if($num > 0){
				while($dt = mysqli_fetch_array($sql_get_balance)){
				    $Item_Balance = $dt['Item_Balance'];
				}
			    }else{
				$Item_Balance = 0;
			    }
			?>
			<td style='text-align: right;' id='Balance' name='Balance'>
			    <input type='text' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
			</td>
			<?php
			    echo "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
			   // echo "<td style='text-align: right;' width=8%><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
			    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
			    $temp++;
			}   echo "</tr>";
			echo "<tr><td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>"; 
		}
	    }
	}
    }
}
    ?></table></center>