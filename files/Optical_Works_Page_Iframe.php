<script src='js/functions.js'></script><!--<script src="jquery.js"></script>-->
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
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
            	update_total('<?php echo $Payment_Cache_ID; ?>','<?php echo $Transaction_Type; ?>');
            }
        }
    </script>

<script type="text/javascript">
	function calculate_Sub_Total(myval){
		var Price = document.getElementById("P_"+myval).value;
		var Quantity = document.getElementById(myval).value;
		var Sub_Total = document.getElementById("Sub_"+myval).value;
		var Discount = document.getElementById("D_"+myval).value;

		Price = Price.replace(/,/g, '');
		var Total = (Price - Discount)*Quantity;

		Total = addCommas(Total);
		document.getElementById("Sub_"+myval).value = Total;
		update_total('<?php echo $Payment_Cache_ID; ?>','<?php echo $Transaction_Type; ?>');
	}
</script>
<script>
	 function addCommas(nStr) {
	    nStr += '';
	    x = nStr.split('.');
	    x1 = x[0];
	    x2 = x.length > 1 ? '.' + x[1] : '';
	    var rgx = /(\d+)(\d{3})/;
	    while (rgx.test(x1)) {
	            x1 = x1.replace(rgx, '$1' + ',' + '$2');
	    }
	    return x1 + x2;
	}
</script>
<script type="text/javascript">
	function update_total(Payment_Cache_ID,Transaction_Type){
        if(window.XMLHttpRequest) {
            myObjectUpdateTotal = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectUpdateTotal = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateTotal.overrideMimeType('text/xml');
        }

        myObjectUpdateTotal.onreadystatechange = function (){
            data6 = myObjectUpdateTotal.responseText;
            if (myObjectUpdateTotal.readyState == 4) {
                document.getElementById('totalAmounts').innerHTML = data6;
            }
        }; //specify name of function that will handle server response........

        myObjectUpdateTotal.open('GET','Pharmacy_Get_Grand_Total.php?Payment_Cache_ID='+Payment_Cache_ID+'&Transaction_Type='+Transaction_Type,true);
        myObjectUpdateTotal.send();
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

    if(isset($_SESSION['Pharmacy_ID'])){
    	$Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
    	$Sub_Department_ID = 0;
    }

    $total = 0;
    $temp = 1;
    $data='';
	$dataAmount='';
	echo '<center><table width =100% border=0>';
	echo '<tr><td colspan=9><hr></tr></td>';
    echo '<tr id="thead"><td style="text-align: center;" width=5%><b>Sn</b></td>
                <td><b>Medication Name</b></td>
                    <td style="text-align: left;" width=15%><b>Dosage</b></td>
			<td style="text-align: right;" width=8%><b>Price</b></td>
			    <td style="text-align: right;" width=8%><b>Discount</b></td>
				<td style="text-align: center;" width=8%><b>Quantity</b></td>
				    <td style="text-align: center;" width=8%><b>Balance</b></td>
					<td style="text-align: center;" width=8%><b>Sub Total</b></td>
					    <td style="text-align: center;" width=6%><b>Action</b></td></tr>';
						echo '<tr><td colspan=9><hr></tr></td>';

$select_Transaction_Items_Dispensed = mysqli_query($conn,
            "select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Doctor_Comment, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type' and
			    Sub_Department_ID = '$Sub_Department_ID' and
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
			    Sub_Department_ID = '$Sub_Department_ID' and
				ilc.status = 'active'"); 

    $no = mysqli_num_rows($select_Transaction_Items_Active);
    
    //display all medications that not approved
    if($no > 0){
	  while($row = mysqli_fetch_array($select_Transaction_Items_Active)){
	    echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
	    echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
	    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
	    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' id='P_".$row['Payment_Item_Cache_List_ID']."'  style='text-align:right;'></td>";
	    echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' id='D_".$row['Payment_Item_Cache_List_ID']."'  readonly='readonly'></td>";
	    ?>
	<td style='text-align:right;'>
	    <?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
		<input type='text' value='<?php echo $Quantity;?>' onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
	    <?php }else{ $Quantity = $row['Edited_Quantity']; ?>
		<input type='text' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
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
						    '$Sub_Department_ID'") or die(mysqli_error($conn));
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
	  
	    echo "<td><input type='text' name='Sub_Total' id='Sub_".$row["Payment_Item_Cache_List_ID"]."' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";
         if($no==1){
	     echo '<td style="text-align: right;" width=8%>
                  &nbsp;
               </td>';
		 }else{	 
			echo '<td style="text-align: right;" width=8%>
						  <button type="button" class="removeItemFromCache art-button" onclick="removeitem('.$row["Payment_Item_Cache_List_ID"].')">Remove</button>
						</td>';
		 }		
	   
	    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
	    $temp++;
	}   echo "</tr>";
	
	//CHECK IF THERE IS REMOVED ITEM TO DISPLAY THE BUTTON THAT VIEW REMOVED ITEM(S) WHEN NEEDED
	$Check_Items = "select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type' and
			    Sub_Department_ID = '$Sub_Department_ID' and
				ilc.status = 'removed'";
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
				    Sub_Department_ID = '$Sub_Department_ID' and
					ilc.status = 'paid'");
					$no = mysqli_num_rows($select_Transaction_Items_Paid);
		if($no > 0){
		
		   while($row = mysqli_fetch_array($select_Transaction_Items_Paid)){
			echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
			echo "<td><input type='text' value='".$row['Product_Name']."'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' id='P_".$row['Payment_Item_Cache_List_ID']."'  style='text-align:right;'></td>";
			echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' id='D_".$row['Payment_Item_Cache_List_ID']."'  readonly='readonly'></td>";
			?>
			<td style='text-align:right;'>
			    <?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
				<input type='text' readonly='readonly' value='<?php echo $Quantity;?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
			    <?php }else{ $Quantity = $row['Edited_Quantity']; ?>
				<input type='text' readonly='readonly' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
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
								    '$Sub_Department_ID'") or die(mysqli_error($conn));
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
			    echo "<td><input type='text' name='Sub_Total' id='Sub_".$row["Payment_Item_Cache_List_ID"]."' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
			   // echo "<td style='text-align: right;' width=8%><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
			    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
			    $temp++;
			}   echo "</tr>";
		}else{
    while($row = mysqli_fetch_array($select_Transaction_Items_Dispensed)){
	    echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
	    echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
	    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
	    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' id='P_".$row['Payment_Item_Cache_List_ID']."'  style='text-align:right;'></td>";
	    echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' id='D_".$row['Payment_Item_Cache_List_ID']."'  readonly='readonly'></td>";
	    ?>
	<td style='text-align:right;'>
	    <?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
		<input type='text' value='<?php echo $Quantity;?>' readonly='readonly onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
	    <?php }else{ $Quantity = $row['Edited_Quantity']; ?>
		<input type='text' value='<?php echo $Quantity; ?>' readonly='readonly onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
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
						    '$Sub_Department_ID'") or die(mysqli_error($conn));
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
	    echo "<td><input type='text' name='Sub_Total' id='Sub_".$row["Payment_Item_Cache_List_ID"]."' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
	    //echo "<td style='text-align: right;' width='8%'><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
	    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
	    $temp++;
	}   echo "</tr>";
	}
	echo "<tr><td id='Total_Area' colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>";
	}
}else{
    $select_Transaction_Items_Active = mysqli_query($conn,
            "select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type' and
			    Sub_Department_ID = '$Sub_Department_ID' and
				ilc.status = 'active'"); 

    $no = mysqli_num_rows($select_Transaction_Items_Active);
    
    //display all medications that not approved
    if($no > 0){
	while($row = mysqli_fetch_array($select_Transaction_Items_Active)){
	    echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
	    echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
	    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
	    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' id='P_".$row['Payment_Item_Cache_List_ID']."'  style='text-align:right;'></td>";
	    echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' id='D_".$row['Payment_Item_Cache_List_ID']."'  readonly='readonly'></td>";
	    ?>
	<td style='text-align:right;'>
	    <?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
		<input type='text' value='<?php echo $Quantity;?>' onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
	    <?php }else{ $Quantity = $row['Edited_Quantity']; ?>
		<input type='text' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
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
						    '$Sub_Department_ID'") or die(mysqli_error($conn));
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
	    echo "<td><input type='text' name='Sub_Total' id='Sub_".$row["Payment_Item_Cache_List_ID"]."' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	

     if($no==1){
	     echo '<td style="text-align: right;" width=8%>
                  &nbsp;
               </td>';
     }else{	 
	    echo '<td style="text-align: right;" width=8%>
                      <button type="button" class="removeItemFromCache art-button" onclick="removeitem('.$row["Payment_Item_Cache_List_ID"].')">Remove</button>
                    </td>';
	}
	    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
	    $temp++;
	}   echo "</tr>";
	
	//CHECK IF THERE IS REMOVED ITEM TO DISPLAY THE BUTTON THAT VIEW REMOVED ITEM(S) WHEN NEEDED
	$Check_Items = $Check_Items = "select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type' and
			    Sub_Department_ID = '$Sub_Department_ID' and
				ilc.status = 'removed'";
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
				Sub_Department_ID = '$Sub_Department_ID' and
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
				Sub_Department_ID = '$Sub_Department_ID' and
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
				    Sub_Department_ID = '$Sub_Department_ID' and
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
				    Sub_Department_ID = '$Sub_Department_ID' and
					ilc.status = 'dispensed'");
		
		    $no = mysqli_num_rows($select_Transaction_Items_Dispensed);
		    if($no > 0){
			while($row = mysqli_fetch_array($select_Transaction_Items_Dispensed)){
			echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
			echo "<td><input type='text' value='".$row['Product_Name']."'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' id='P_".$row['Payment_Item_Cache_List_ID']."'  style='text-align:right;'></td>";
			echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' id='D_".$row['Payment_Item_Cache_List_ID']."'  readonly='readonly'></td>";
			?>
			<td style='text-align:right;'>
			    <?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
				<input type='text' readonly='readonly' value='<?php echo $Quantity;?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
			    <?php }else{ $Quantity = $row['Edited_Quantity']; ?>
				<input type='text' readonly='readonly' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
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
								    '$Sub_Department_ID'") or die(mysqli_error($conn));
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
			    echo "<td><input type='text' name='Sub_Total' id='Sub_".$row["Payment_Item_Cache_List_ID"]."' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
			   // echo "<td style='text-align: right;' width=8%><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
			    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
			    $temp++;
			}   echo "</tr>";
			echo "<tr><td id='Total_Area' colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>";    
		    }else{
			while($row = mysqli_fetch_array($select_Transaction_Items_Paid)){
			echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
			echo "<td><input type='text' value='".$row['Product_Name']."'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' id='P_".$row['Payment_Item_Cache_List_ID']."'  style='text-align:right;'></td>";
			echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' id='D_".$row['Payment_Item_Cache_List_ID']."'  readonly='readonly'></td>";
			?>
			<td style='text-align:right;'>
			    <?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
				<input type='text' readonly='readonly' value='<?php echo $Quantity;?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
			    <?php }else{ $Quantity = $row['Edited_Quantity']; ?>
				<input type='text' readonly='readonly' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
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
								    '$Sub_Department_ID'") or die(mysqli_error($conn));
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
			    echo "<td><input type='text' name='Sub_Total' id='Sub_".$row["Payment_Item_Cache_List_ID"]."' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
			   // echo "<td style='text-align: right;' width=8%><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
			    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
			    $temp++;
			}   echo "</tr>";
			echo "<tr><td id='Total_Area' colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>";    
		    }
		}else{     
		    while($row = mysqli_fetch_array($select_Transaction_Items_Approved)){
		    echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
		    echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
		    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
		    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' id='P_".$row['Payment_Item_Cache_List_ID']."'  style='text-align:right;'></td>";
		    echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' id='D_".$row['Payment_Item_Cache_List_ID']."'  readonly='readonly'></td>";
		    ?>
		    <td style='text-align:right;'>
			<?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
			    <input type='text' value='<?php echo $Quantity;?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
			<?php }else{ $Quantity = $row['Edited_Quantity']; ?>
			    <input type='text' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
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
								'$Sub_Department_ID'") or die(mysqli_error($conn));
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
			echo "<td><input type='text' name='Sub_Total' id='Sub_".$row["Payment_Item_Cache_List_ID"]."' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
		      
			$total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
			$temp++;
		    }   echo "</tr>";
		    echo "<tr><td id='Total_Area' colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>";  
		}
		 
	    }else{
		while($row = mysqli_fetch_array($select_Transaction_Items_Approved)){
		echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
		echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
		echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
		echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' id='P_".$row['Payment_Item_Cache_List_ID']."'  style='text-align:right;'></td>";
		echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' id='D_".$row['Payment_Item_Cache_List_ID']."'  readonly='readonly'></td>";
		?>
		<td style='text-align:right;'>
		    <?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
			<input type='text' value='<?php echo $Quantity;?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
		    <?php }else{ $Quantity = $row['Edited_Quantity']; ?>
			<input type='text' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
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
							    '$Sub_Department_ID'") or die(mysqli_error($conn));
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
		    echo "<td><input type='text' name='Sub_Total' id='Sub_".$row["Payment_Item_Cache_List_ID"]."' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
		  
		    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
		    $temp++;
		}   echo "</tr>";
		
		//CHECK IF THERE IS REMOVED ITEM TO DISPLAY THE BUTTON THAT VIEW REMOVED ITEM(S) WHEN NEEDED
		$Check_Items =  "select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type' and
			    Sub_Department_ID = '$Sub_Department_ID' and
				ilc.status = 'removed'";
		$Check_Items_Results = mysqli_query($conn,$Check_Items);
		$No_Of_Items = mysqli_num_rows($Check_Items_Results);
		if($No_Of_Items > 0){
		    echo "<tr><td colspan=5 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td><td colspan=2 style='text-align: right;'>
			    <a href='Pharmacy_Works_Page_Iframe2.php?Transaction_Type=".$Transaction_Type."&Payment_Cache_ID=".$Payment_Cache_ID."' class = 'art-button'>View Removed Medication</a>
				</td></tr>";
		}else{
		    echo "<tr><td id='Total_Area' colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>";
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
				Sub_Department_ID = '$Sub_Department_ID' and
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
				    Sub_Department_ID = '$Sub_Department_ID' and
					ilc.status = 'paid'");
		$no = mysqli_num_rows($select_Transaction_Items_Paid);
		if($no > 0){ 
		    while($row = mysqli_fetch_array($select_Transaction_Items_Paid)){
		    echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
		    echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
		    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
		    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' id='P_".$row['Payment_Item_Cache_List_ID']."'  style='text-align:right;'></td>";
		    echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' id='D_".$row['Payment_Item_Cache_List_ID']."'  readonly='readonly'></td>";
		    ?>
		    <td style='text-align:right;'>
			<?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
			    <input type='text' readonly='readonly' value='<?php echo $Quantity;?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
			<?php }else{ $Quantity = $row['Edited_Quantity']; ?>
			    <input type='text' readonly='readonly' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
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
								'$Sub_Department_ID'") or die(mysqli_error($conn));
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
			echo "<td><input type='text' name='Sub_Total' id='Sub_".$row["Payment_Item_Cache_List_ID"]."' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
		       
			$total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
			$temp++;
		    }   echo "</tr>";
		    echo "<tr><td id='Total_Area' colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>"; 
		}else{
		    //get back to approved
		    while($row = mysqli_fetch_array($select_Transaction_Items_Approved)){
			echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
			echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' id='P_".$row['Payment_Item_Cache_List_ID']."'  style='text-align:right;'></td>";
			echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' id='D_".$row['Payment_Item_Cache_List_ID']."'  readonly='readonly'></td>";
			?>
			<td style='text-align:right;'>
			    <?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
				<input type='text' value='<?php echo $Quantity;?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
			    <?php }else{ $Quantity = $row['Edited_Quantity']; ?>
				<input type='text' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
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
								    '$Sub_Department_ID'") or die(mysqli_error($conn));
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
			    echo "<td><input type='text' name='Sub_Total' id='Sub_".$row["Payment_Item_Cache_List_ID"]."' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
			   
			    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
			    $temp++;
			}   echo "</tr>";
			echo "<tr><td id='Total_Area' colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>"; 
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
				    Sub_Department_ID = '$Sub_Department_ID' and
					ilc.status = 'paid'");
		$no = mysqli_num_rows($select_Transaction_Items_Paid);
		if($no > 0){
			while($row = mysqli_fetch_array($select_Transaction_Items_Paid)){
			echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
			echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".$row['Doctor_Comment']."' style='text-align:left;'></td>";
			echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' id='P_".$row['Payment_Item_Cache_List_ID']."'  style='text-align:right;'></td>";
			echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."' id='D_".$row['Payment_Item_Cache_List_ID']."'  readonly='readonly'></td>";
			?>
			<td style='text-align:right;'>
			    <?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
				<input type='text' readonly='readonly' value='<?php echo $Quantity;?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
			    <?php }else{ $Quantity = $row['Edited_Quantity']; ?>
				<input type='text' readonly='readonly' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"];?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
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
								    '$Sub_Department_ID'") or die(mysqli_error($conn));
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
			    echo "<td><input type='text' name='Sub_Total' id='Sub_".$row["Payment_Item_Cache_List_ID"]."' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
			  
			    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
			    $temp++;
			}   echo "</tr>";
			echo "<tr><td id='Total_Area' colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>"; 
		}
	    }
	}
    }
}
    ?></table></center>