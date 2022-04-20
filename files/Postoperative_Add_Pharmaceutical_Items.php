<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['consultation_ID'])){
		$consultation_ID = $_GET['consultation_ID'];
	}else{
		$consultation_ID = '';
	}
	
	if(isset($_GET['Section'])){
		$Section = $_GET['Section'];
	}else{
		$Section = '';
	}
	
	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	//get sponsor id & sponsor name
	$select = mysqli_query($conn,"SELECT sp.Sponsor_ID, sp.Guarantor_Name, sp.payment_method from tbl_patient_registration pr, tbl_sponsor sp where
							pr.Sponsor_ID = sp.Sponsor_ID and
							pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Sponsor_ID = $data['Sponsor_ID'];
			$Guarantor_Name = $data['Guarantor_Name'];
            $payment_method = $data['payment_method'];
		}
	}else{
		$Sponsor_ID = '';
		$Guarantor_Name = '';
	}

?>

<table width=100% style='border-style: none;'>
    <tr>
        <td width=40%>
            <table width=100% style='border-style: none;'>
                <tr>
                    <td>
                        <b>Category : </b>
                        <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                            <option selected='selected' value="0">All</option>
							<?php
							$data = mysqli_query($conn,"SELECT cat.Item_Category_Name, cat.Item_Category_ID
												    from tbl_item_category cat, tbl_item_subcategory isub, tbl_items i 
												    WHERE cat.Item_category_ID = isub.Item_category_ID and
												    isub.Item_Subcategory_ID = i.Item_Subcategory_ID and
												    i.Item_Type = 'Pharmacy' and
                                                    i.Can_Be_Sold = 'yes'
												    group by cat.Item_Category_ID") or die(mysqli_error($conn));
							while ($row = mysqli_fetch_array($data)) {
							    echo '<option value="' . $row['Item_Category_ID'] . '">' . $row['Item_Category_Name'] . '</option>';
							}
							?>   
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup='getItemsListFiltered(this.value)' placeholder='Enter Item Name'>
                    </td>
                </tr>
                <tr>
                    <td>
                        <fieldset style='overflow-y: scroll; height: 330px;' id='Items_Fieldset'>
                            <table width=100%>
								<?php
									$result = mysqli_query($conn,"select Product_Name, Item_ID from tbl_items where Item_Type = 'Pharmacy' and Status = 'Available' and Can_Be_Sold = 'yes' order by Product_Name limit 150");
									while ($row = mysqli_fetch_array($result)) {
								    	echo "<tr><td style='color:black; border:2px solid #ccc;text-align: left;' width=5%>";
								?>
	                                    <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>); Get_Item_Price(<?php echo $row['Item_ID']; ?>, '<?php echo $Guarantor_Name; ?>');">
								<?php
								    	echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</label></td></tr>";
									}
								?>
                            </table>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </td>
        <td>
            <table width="100%">
                <tr>
                    <td style='text-align: right;' width="30%">Item Name</td>
                    <td width="70%">
                        <input type='text' name='Item_Name' id='Item_Name' readonly='readonly' placeholder='Item Name'>
                        <input type='hidden' name='Item_ID' id='Item_ID' value=''>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Price</td>
                    <td>
                        <input type='text' name='Price' id='Price' readonly='readonly' placeholder='Price'>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Quantity</td>
                    <td>
                        <input type='text' name='Quantity' id='Quantity' autocomplete='off' placeholder='Quantity'>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Location</td>
                    <td>
                        <select name='Sub_Department_ID' id='Sub_Department_ID'>
                            <?php
                                $select = mysqli_query($conn,"SELECT Sub_Department_Name, Sub_Department_ID from tbl_department dep, tbl_sub_department sdep
														where dep.Department_ID = sdep.Department_ID and
														Department_Location = 'Pharmacy'") or die(mysqli_error($conn));
                                $num = mysqli_num_rows($select);
                                if($num > 1){
                                	echo "<option selected='selected'></option>";
                                }
                                while($row = mysqli_fetch_array($select)){
                                    echo "<option value='".$row['Sub_Department_ID']."'>".$row['Sub_Department_Name']."</option>";
                                }
                            
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan=2>
                        <textarea name='Dosage' id='Dosage' placeholder='Dosage'></textarea>
                    </td>
                </tr>
                <tr>
                	<td style='text-align: right;'>Bill Type
                		<select id="Transaction_Type" name="Transaction_Type">
	                	<?php
	                		if(strtolower($payment_method) == 'cash'){
	                			echo "<option>Cash</option>";
	                		}elseif($payment_method == 'credit'){
	                			echo "<option >Credit</option>";
	                			echo "<option>Cash</option>";
	                		}
	                	?>
                		</select>
                	</td>
                    <td style='text-align: right;'>
                        <input type='button' name='Submit' id='Submit' class='art-button-green' value='ADD MEDICATION' onclick='Get_Selected_Item()'>
                    </td>
                </tr>
            </table><br/>
            <fieldset style='overflow-y: scroll; height: 180px;' id='Selected_Medications_Area'>
            <?php
                $select = mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache where consultation_ID = '$consultation_ID' and Order_Type = 'post operative'") or die(mysqli_error($conn));
                $num = mysqli_num_rows($select);
                if($num > 0){
                    while ($data = mysqli_fetch_array($select)) {
                        $Payment_Cache_ID = $data['Payment_Cache_ID'];
                    }
                }else{
                    $Payment_Cache_ID = 0;
                }

                //display medications ordered
                $select = mysqli_query($conn,"select ilc.Payment_Item_Cache_List_ID, i.Product_Name, ilc.Transaction_Type, ilc.Price, ilc.Quantity, ilc.Status from tbl_item_list_cache ilc, tbl_items i where
                                        i.Item_ID = ilc.Item_ID and
                                        ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                        Check_In_Type = 'Pharmacy'") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select);
                if($no > 0){
                    $temp = 0;
            ?>
                <table width="100%">
                    <tr>
                        <td width="5%"><b>SN</b></td>
                        <td><b>MEDICATION NAME</b></td>
                        <td width="10%"><b>TYPE</b></td>
                        <td width="10%"><b>PRICE</b></td>
                        <td width="10%"><b>QTY</b></td>
                        <td width="4%"></td>
                    </tr>
            <?php
                    while ($row = mysqli_fetch_array($select)) {
            ?>
                    <tr>
                        <td><?php echo ++$temp; ?></td>
                        <td><?php echo $row['Product_Name']; ?></td>
                        <td><?php echo $row['Transaction_Type']; ?></td>
                        <td><?php echo $row['Price']; ?></td>
                        <td><?php echo $row['Quantity']; ?></td>
            <?php
                if(strtolower($row['Status']) != 'active'){
                    echo '<td><input type="button" name="Remove_Medication" id="Remove_Medication" value="X" onclick="Remove_Medication_Warning('.$row['Payment_Item_Cache_List_ID'].')" ></td>';
                }else{
                    echo '<td><input type="button" name="Remove_Medication" id="Remove_Medication" value="X" onclick="Remove_Medication('.$row['Payment_Item_Cache_List_ID'].')"></td>';
                }
            ?>
                    </tr>
            <?php
                    }
                }else{
                    echo "<br/><br/><br/><br/><center><h3>NO MEDICATION ORDERED</h3></center>";
                }
            ?>
            </fieldset>
        </td>
    </tr>
</table>