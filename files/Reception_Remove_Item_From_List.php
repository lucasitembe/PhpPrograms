<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Reception_Works'])){
	    if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    } else{
		@session_start();
		if(!isset($_SESSION['supervisor'])){
                
                    //get patient registration id for future use
                    if(isset($_GET['Registration_ID'])){
                        $Registration_ID = $_GET['Registration_ID'];
                    }else{
                        $Registration_ID = '';
                    }
		    header("Location: ./receptionsupervisorauthentication.php?Registration_ID=$Registration_ID&InvalidSupervisorAuthentication=yes");
		}
	    } 
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_GET['Selected_Billing_Type'])){
	$Selected_Billing_Type = $_GET['Selected_Billing_Type'];
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
			echo '<tr><td colspan=9><hr></td></tr>';
                echo "<tr id='thead'><td style='width: 3%;'>Sn</td><td style='width: 10%;'>Check-in</td>";
                    echo '<td style="width: 20%;">Location</td>
                        <td style="width: 28%;">Item description</td>
                            <td style="text-align:right; width: 8%;">Price</td>
                                <td style="text-align:right; width: 8%;">Discount</td>
                                    <td style="text-align:right; width: 8%;">Quantity</td>
                                        <td style="text-align:right; width: 8%;">Sub total</td><td width=4%>Remove</td></tr>';
			echo '<tr><td colspan=9><hr></td></tr>';
                                        
            $select_Transaction_Items = mysqli_query($conn,
                "select Reception_List_Item_ID, Check_In_Type, Patient_Direction, Product_Name, Price, Discount, Quantity,Registration_ID
                    from tbl_items t, tbl_reception_items_list_cache alc
                        where alc.Item_ID = t.Item_ID and
                            alc.Employee_ID = '$Employee_ID' and
                                    Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
            
            $no_of_items = mysqli_num_rows($select_Transaction_Items);
                while($row = mysqli_fetch_array($select_Transaction_Items)){
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
                <td style='text-align: right; width: 20%;'><h4>Total : <?php echo number_format($total); ?></h4></td>
                            <td style='text-align: right; width:80%;'>
                            <?php
                                $slct = mysqli_query($conn,"select Prepaid_ID from tbl_prepaid_details where Registration_ID = '$Registration_ID' and Status = 'pending'") or die(mysqli_error($conn));
                                $nm = mysqli_num_rows($slct);
                                if(strtolower($Selected_Billing_Type) == 'outpatient cash'){
                                
                                    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                                            $sql_check_approve_bill_privilege_result=mysqli_query($conn,"SELECT Employee_ID FROM tbl_privileges WHERE Employee_ID='$Employee_ID' AND can_create_out_patient_bill='yes'") or die(mysqli_error($conn));
                                            if(mysqli_num_rows($sql_check_approve_bill_privilege_result)>0){ 
                                            echo "<input type='button' class='art-button-green' value='Create Outpatient Paid Bill' onclick='Create_Pre_Paid_Bill()'>";
                                            }else{
                                             echo "<input type='button' class='art-button-green' value='Create Outpatient Paid Bill' onclick='alert(\"Access Denied\")'>";
                                               
                                            }
                                }
                                if(strtolower($_SESSION['systeminfo']['Centralized_Collection']) == 'yes'){								
                                    if(strtolower($_SESSION['userinfo']['Cash_Transactions']) == 'yes'){
                                        if($Selected_Billing_Type == 'Outpatient Credit'){
                                            ?>
                                            <input type='button' value='Save Information' class='art-button-green' onclick='Patient_Billing_Reception_Generate_Receipt(<?php echo $Registration_ID; ?>)'>
                                            <?php
                                        }else{
                                            if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'){
                                                if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
                                            ?>
                                                                <input type="button"  value="Go to mobile/Card Payment" class="art-button-green" onclick="create_epayment_mobile_card_bill()">&nbsp;&nbsp;
                						<input type='button' value='Create ePayment Bill' id='Pay_Via_Mobile' name='Pay_Via_Mobile' class='art-button-green' onclick='Pay_Via_Mobile_Function()'>
                					<?php } 
                                            }if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){
                                                        ?>
                                                                <input type='button' value='Make Payment' class='art-button-green' onclick='Confirm_Payment_Credit_Cases()'>
                                            <?php } if(strtolower($_SESSION['systeminfo']['Display_Send_To_Cashier_Button']) == 'yes'){ ?>
                                                <input type='button' name='Send_To_Cashier_Button' class='art-button-green' value='Send To Cashier' onclick='send_cashier_new_payment_method()'>
                                        <?php } ?>
                                            <?php
                                        }
                                    }else{
                                        if($Selected_Billing_Type == 'Outpatient Credit'){
                                            ?>
                                            <input type='button' value='Save Information' class='art-button-green' onclick='Patient_Billing_Reception_Generate_Receipt(<?php echo $Registration_ID; ?>)'>
                                            <?php
                                        }else{
                                            if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'){
                                                if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
                                            ?>                  <input type="button"  value="Go to mobile/Card Payment" class="art-button-green" onclick="create_epayment_mobile_card_bill()">&nbsp;&nbsp;
                						<input type='button' value='Create ePayment Bill' id='Pay_Via_Mobile' name='Pay_Via_Mobile' class='art-button-green' onclick='Pay_Via_Mobile_Function()'>
                                            <?php }} if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){ ?>
                                        <?php if(strtolower($_SESSION['userinfo']['Revenue_Center_Works']) == 'yes'){ ?>
                                                                <input type='button' value='Make Payment' class='art-button-green' onclick='Confirm_Payment_Credit_Cases()'>
                                            <?php } } ?>
                                        <input type='button' name='Send_To_Cashier_Button' class='art-button-green' value='Send To Cashier' onclick='send_cashier_new_payment_method()'>
                                            <?php
                                        }
                                        ?>
                            <?php } }else{ 
                                    if($Selected_Billing_Type == 'Outpatient Credit'){
                                        ?>
                                            <input type='button' value='Save Information' class='art-button-green' onclick='Patient_Billing_Reception_Generate_Receipt(<?php echo $Registration_ID; ?>)'>
                                    <?php
                                        }else{
                                            if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'){
                                                if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
                                    ?>                  <input type="button"  value="Go to mobile/Card Payment" class="art-button-green" onclick="create_epayment_mobile_card_bill()">&nbsp;&nbsp;
					               <input type='button' value='Create ePayment Bill' id='Pay_Via_Mobile' name='Pay_Via_Mobile' class='art-button-green' onclick='Pay_Via_Mobile_Function()'>
                                            <?php } } if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){?>
                                <?php if(strtolower($_SESSION['userinfo']['Revenue_Center_Works']) == 'yes'){ ?>
                                                       <input type='button' value='Make Payment' class='art-button-green' onclick='Confirm_Payment_Credit_Cases()'>
                                            <?php } } ?>
                                    <input type='button' name='Send_To_Cashier_Button' class='art-button-green' value='Send To Cashier' onclick='send_cashier_new_payment_method()'>
                            <?php } } ?>
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
 <input type="text"hidden="hidden" id="total_txt" value="<?php echo $total; ?>"/>
 
