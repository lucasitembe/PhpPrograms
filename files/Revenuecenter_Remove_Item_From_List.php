<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_GET['Billing_Type'])){
	$Selected_Billing_Type = $_GET['Billing_Type'];
    }else{
	$Selected_Billing_Type = '';
    }
    
        
    if(isset($_GET['Reception_List_Item_ID'])){
        $Reception_List_Item_ID = $_GET['Reception_List_Item_ID'];
    }else{
        $Reception_List_Item_ID = 0;
    }
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    //get employee id
    
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
?>
<fieldset style='overflow-y: scroll; height: 200px;'>
<?php
    if($Reception_List_Item_ID != 0 && $Registration_ID != 0 && $Employee_ID != 0){
        //delete selected record
        $delete_details = mysqli_query($conn,"delete from tbl_reception_items_list_cache where Reception_List_Item_ID = '$Reception_List_Item_ID'") or die(mysqli_error($conn));
        
        if($delete_details){
            $total = 0;
            echo '<table width =100%>';
                echo "<tr id='thead'><td style='width: 3%;'>Sn</td><td style='width: 10%;'>Check in type</td>";
                    echo '<td style="width: 20%;">Location</td>
                        <td style="width: 28%;">Item description</td>
                            <td style="text-align:right; width: 8%;">Price</td>
                                <td style="text-align:right; width: 8%;">Discount</td>
                                    <td style="text-align:right; width: 8%;">Quantity</td>
                                        <td style="text-align:right; width: 8%;">Sub total</td><td width=4%>Remove</td></tr>';
                                        
            $select_Transaction_Items = mysqli_query($conn,
                "select Reception_List_Item_ID, Check_In_Type, Patient_Direction, Product_Name, Price, Discount, Quantity,Registration_ID,Billing_Type
                    from tbl_items t, tbl_reception_items_list_cache alc
                        where alc.Item_ID = t.Item_ID and
                            alc.Employee_ID = '$Employee_ID' and
                                    Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
            
            $no_of_items = mysqli_num_rows($select_Transaction_Items);
                while($row = mysqli_fetch_array($select_Transaction_Items)){
                    $Billing_Type = $row['Billing_Type'];
                    echo "<tr><td>".$temp."</td><td>".$row['Check_In_Type']."</td>";
                    echo "<td>".$row['Patient_Direction']."</td>";
                    echo "<td>".$row['Product_Name']."</td>";
                    echo "<td style='text-align:right;'>".number_format($row['Price'])."</td>";
                    echo "<td style='text-align:right;'>".number_format($row['Discount'])."</td>";
                    echo "<td style='text-align:right;'>".$row['Quantity']."</td>";
                    echo "<td style='text-align:right;'>".number_format(($row['Price'] - $row['Discount'])*$row['Quantity'])."</td>";
        ?>
            <td style='text-align: center;'> 
                <input type='button' style='color: red; font-size: 10px;' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Reception_List_Item_ID']; ?>,<?php echo $row['Registration_ID']; ?>)'>
            </td>
        <?php
            $temp++;
            $total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity']);
        }echo "</tr>";
        ?> <input type="text" hidden="hidden" id="total_txt" value="<?php echo $total; ?>"/> <?php
        echo "<tr><td colspan=8 style='text-align: right;'> Total : ".number_format($total)."</td></tr></table>";
        } 
    }
?>
</fieldset>
<table width='100%'>
    <tr>
	<?php
	    if($no_of_items > 0){
		
		?>
		<td style='text-align: right; width: 57%;'><h4>Total : <?php echo number_format($total); ?></h4></td>
		<td style='text-align: right; width: 43%;'>
        <?php
    $slct = mysqli_query($conn,"select Prepaid_ID from tbl_prepaid_details where Registration_ID = '$Registration_ID' and Status = 'pending'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($slct);
    if ($nm > 0 && strtolower($Selected_Billing_Type) == 'outpatient cash') {
        echo "<input type='button' class='art-button-green' value='Create Pre / Post Paid Bill' onclick='Create_Pre_Paid_Bill()'>";
    }
    if (strtolower($_SESSION['systeminfo']['Centralized_Collection']) == 'yes') {
        if (strtolower($_SESSION['userinfo']['Cash_Transactions']) == 'yes') {
            if ($Selected_Billing_Type == 'Outpatient Credit') {
                ?>
                            <input type='button' value='Save Information' class='art-button-green' onclick='Patient_Billing_Reception_Generate_Receipt(<?php echo $Registration_ID; ?>)'>
                            <?php
                        } else {
                            if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes') {
                                ?>
                                <input type='button' value='Create ePayment Bill' id='Pay_Via_Mobile' name='Pay_Via_Mobile' class='art-button-green' onclick='Pay_Via_Mobile_Function()'>
                            <?php }  if(isset($_SESSION['configData']) && $_SESSION['configData']['showMakePaymentButton']=='show'){ ?>

                            <input type='button' value='Make Payment' class='art-button-green' onclick='Confirm_Payment_Credit_Cases()'>

                            <?php } //end checking make payment button visibility
                             if (strtolower($_SESSION['systeminfo']['Display_Send_To_Cashier_Button']) == 'yes') { ?>
                                <input type='button' name='Send_To_Cashier_Button' class='art-button-green' value='Send To Cashier' onclick=' return Confirm_Send_To_Cashier(<?php echo $Registration_ID; ?>)'>
                            <?php } ?>
                            <?php
                        }
                    } else {
                        if ($Selected_Billing_Type == 'Outpatient Credit') {
                            ?>
                            <input type='button' value='Save Information' class='art-button-green' onclick='Patient_Billing_Reception_Generate_Receipt(<?php echo $Registration_ID; ?>)'>
                            <?php
                        } else {
                            if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes') {
                                ?>
                                <input type='button' value='Create ePayment Bill' id='Pay_Via_Mobile' name='Pay_Via_Mobile' class='art-button-green' onclick='Pay_Via_Mobile_Function()'>
                            <?php } ?>
                            <?php if (strtolower($_SESSION['userinfo']['Revenue_Center_Works']) == 'yes') {  if(isset($_SESSION['configData']) && $_SESSION['configData']['showMakePaymentButton']=='show'){ ?>
                                <input type='button' value='Make Payment' class='art-button-green' onclick='Confirm_Payment_Credit_Cases()'>
                            <?php } } ?>
                            <input type='button' name='Send_To_Cashier_Button' class='art-button-green' value='Send To Cashier' onclick=' return Confirm_Send_To_Cashier(<?php echo $Registration_ID; ?>)'>
                            <?php
                        }
                        ?>
                        <?php
                    }
                } else {
                   
                    if ($Selected_Billing_Type == 'Outpatient Credit') {
                        ?>
                        <input type='button' value='Save Information' class='art-button-green' onclick='Patient_Billing_Reception_Generate_Receipt(<?php echo $Registration_ID; ?>)'>
                        <?php
                    } else {
                        if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes') {
                            ?>
                            <input type='button' value='Create ePayment Bill' id='Pay_Via_Mobile' name='Pay_Via_Mobile' class='art-button-green' onclick='Pay_Via_Mobile_Function()'>
                        <?php } ?>
                        <?php if (strtolower($_SESSION['userinfo']['Revenue_Center_Works']) == 'yes') {  if(isset($_SESSION['configData']) && $_SESSION['configData']['showMakePaymentButton']=='show'){ ?>

                            <input type='button' value='Make Payment' class='art-button-green' onclick='Confirm_Payment_Credit_Cases()'>
                        <?php } } ?>
                        <input type='button' name='Send_To_Cashier_Button' class='art-button-green' value='Send To Cashier' onclick=' return Confirm_Send_To_Cashier(<?php echo $Registration_ID; ?>)'>
                        <?php
                    }
                }
                ?> 
                </td>
		<?php
	    }else{
		?>
		<td style='text-align: right; width: 70%;'><h4>Total : 0</h4></td>
			<td style='text-align: right; width: 30%;'>
			</td>
		<?php
	    }				
	?>
    </tr>
</table>