<?php
	@session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    if(isset($_GET['direction'])){
        $direction = $_GET['direction'];
    }else{
        $direction = '';
    }
    
    if(isset($_GET['Consultant'])){
        $Consultant = $_GET['Consultant'];
    }else{
        $Consultant = '';
    }
    
    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
        $Sponsor_ID = '';
    }
    
    if(isset($_GET['Guarantor_Name'])){
        $Guarantor_Name = $_GET['Guarantor_Name'];
    }else{
        $Guarantor_Name = '';
    }
    
    if(isset($_GET['direction'])){
        $direction = $_GET['direction'];
    }else{
        $direction = '';
    }

    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    //check if there is another record based on selected employee and patient
	//if found we delete them before continue with selected patient
    $Transaction_Details = mysqli_query($conn,"select * from tbl_reception_items_list_cache where Employee_ID = '$Employee_ID' and Registration_ID <> '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($Transaction_Details);
    if($no > 0){ //delete all
        $delete_details = mysqli_query($conn,"delete from tbl_reception_items_list_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
    }

    //get free consultation items
    $Billing_Type = 'Outpatient Credit';
    $select = mysqli_query($conn,"select Item_ID from tbl_items where Free_Consultation_Item = '1'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
    	while ($data = mysqli_fetch_array($select)) {
    		$Item_ID = $data['Item_ID'];
        	$Price = 0;

        	//Get Item Price
            $itemSpecResult = mysqli_query($conn,"select Items_Price as price from tbl_item_price ip where ip.Item_ID = '$Item_ID' AND ip.Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
            $nm = mysqli_num_rows($itemSpecResult);

            if($nm > 0){
				$row = mysqli_fetch_assoc($itemSpecResult);
				$Price = $row['price'];
                if ($Price == 0) {
                    $itemGenResult = mysqli_query($conn,"select Items_Price as price from tbl_general_item_price ig where ig.Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                    $nm2 = mysqli_num_rows($itemGenResult);
                    if ($nm2 > 0) {
                        $row = mysqli_fetch_assoc($itemGenResult);
                        $Price = $row['price'];
                    } else {
                        $Price = 0;
                    }
                }
            }else{
                $itemGenResult = mysqli_query($conn,"select Items_Price as price from tbl_general_item_price ig where ig.Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                $row = mysqli_fetch_assoc($itemGenResult);
				$Price = $row['price'];
            }

            //check if item not available
            $check = mysqli_query($conn,"select Item_ID from tbl_reception_items_list_cache where Item_ID = '$Item_ID' and Registration_ID = '$Registration_ID' and Employee_ID =' $Employee_ID'") or die(mysqli_error($conn));
            $num_check = mysqli_num_rows($check);
            if($num_check < 1){
            //insert items details
            	if(strtolower($direction) == 'direct to doctor' || strtolower($direction) == 'direct to doctor via nurse station'){
                    $insert_data = mysqli_query($conn,"insert into tbl_reception_items_list_cache(
                                                Check_In_Type,Item_ID,Discount,
                                                    Price,Quantity,Patient_Direction,
                                                        Consultant,Consultant_ID,Employee_ID,Registration_ID,Billing_Type,
                                                        Sponsor_Name,Sponsor_ID)
                                            values('Doctor Room','$Item_ID','0',
                                                '$Price','1','$direction',
                                                    '$Consultant',(select Employee_ID from tbl_employee where Employee_Name = '$Consultant' limit 1),'$Employee_ID','$Registration_ID',
                                                    '$Billing_Type','$Guarantor_Name','$Sponsor_ID')") or die(mysqli_error($conn));
                }elseif(strtolower($direction) == 'direct to clinic' || strtolower($direction) == 'direct to clinic via nurse station'){
                    $insert_data = mysqli_query($conn,"insert into tbl_reception_items_list_cache(
                                                Check_In_Type,Item_ID,Discount,
                                                    Price,Quantity,Patient_Direction,
                                                        Consultant,Consultant_ID,Employee_ID,Registration_ID,Billing_Type,
                                                        Sponsor_Name,Sponsor_ID)
                                            values('Doctor Room','$Item_ID','0',
                                                '$Price','1','$direction',
                                                    '$Consultant',(select Clinic_ID from tbl_clinic where Clinic_Name = '$Consultant' limit 1),'$Employee_ID','$Registration_ID',
                                                    '$Billing_Type','$Guarantor_Name','$Sponsor_ID')") or die(mysqli_error($conn));
                }elseif(strtolower($direction) == 'others'){
                    $insert_data = mysqli_query($conn,"insert into tbl_reception_items_list_cache(
                                                Check_In_Type,Item_ID,Discount,
                                                    Price,Quantity,Patient_Direction,
                                                        Consultant,Consultant_ID,Employee_ID,Registration_ID,Billing_Type,
                                                        Sponsor_Name,Sponsor_ID)
                                            values('Doctor Room','$Item_ID','0',
                                                '$Price','1','$direction',
                                                    '$Consultant','','$Employee_ID','$Registration_ID',
                                                    '$Billing_Type','$Guarantor_Name','$Sponsor_ID')") or die(mysqli_error($conn));
                }
            }
    	}
    }
?>
<fieldset style='overflow-y: scroll; height: 200px;'>
<?php
    $total = 0;
    $temp = 1;
	echo '<table width =100%>';
	echo '<tr><td colspan=9><hr></td></tr>';
	echo "<tr id='thead'><td style='width: 3%;'>Sn</td><td style='width: 10%;'>Check-in</td>";
	echo '<td style="width: 20%;">Location</td>
		<td style="width: 28%;">Item description</td>
		<td style="text-align:right; width: 8%;">Price</td>
		<td style="text-align:right; width: 8%;">Discount</td>
		<td style="text-align:right; width: 8%;">Quantity</td>
		<td style="text-align:right; width: 8%;">Sub total</td><td width=4%>Remove</td></tr>';
    echo '<tr><td colspan=9><hr></td></tr>';                           
    $select_Transaction_Items = mysqli_query($conn,"select Reception_List_Item_ID, Check_In_Type, Patient_Direction, Product_Name, Price, Discount, Quantity,Registration_ID
	            								from tbl_items t, tbl_reception_items_list_cache alc where alc.Item_ID = t.Item_ID and
	                    						alc.Employee_ID = '$Employee_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    
    $no_of_items = mysqli_num_rows($select_Transaction_Items);    
    while($row = mysqli_fetch_array($select_Transaction_Items)){
        echo "<tr><td>".$temp."</td><td>".$row['Check_In_Type']."</td>";
        echo "<td>".$row['Patient_Direction']."</td>";
        echo "<td>".$row['Product_Name']."</td>";
        echo "<td style='text-align:right;'>".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price']))."</td>";
        echo "<td style='text-align:right;'>".number_format($row['Discount'])."</td>";
        echo "<td style='text-align:right;'>".$row['Quantity']."</td>";
        echo "<td style='text-align:right;'>".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount'])*$row['Quantity'], 2) : number_format(($row['Price'] - $row['Discount'])*$row['Quantity']))."</td>";
    ?>
        <td style='text-align: center;'> 
            <input type='button' style='color: red; font-size: 10px;' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Reception_List_Item_ID']; ?>,<?php echo $row['Registration_ID']; ?>);'>
        </td>
    <?php
        $temp++;
        $total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity']);
    	echo "</tr>";
    }
    echo "<tr><td colspan=8 style='text-align: right;'> Total : ".number_format($total)."</td></tr></table>";
?>
</fieldset>
<table width='100%'>
    <tr>
        <?php if($no_of_items > 0){ ?>
                <td style='text-align: right; width: 40%;'><h4>Total : <?php echo number_format($total); ?></h4></td>
                <td style='text-align: right; width:60%;'>
					<input type='button' value='Save Information' class='art-button-green' onclick='Patient_Billing_Reception_Generate_Receipt(<?php echo $Registration_ID; ?>)'>
				</td>
		<?php }else{ ?>
                <td style='text-align: right; width: 70%;'><h4>Total : 0</h4></td><td style='text-align: right; width: 30%;'></td>
        <?php } ?>
    </tr>
</table>