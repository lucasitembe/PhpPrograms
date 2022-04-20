<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }
    
    if(isset($_GET['Selected_Billing_Type'])){
        $Selected_Billing_Type = $_GET['Selected_Billing_Type'];
    }else{
        $Selected_Billing_Type = 0;
    }
    
    if(isset($_GET['Type_Of_Check_In'])){
        $Type_Of_Check_In = $_GET['Type_Of_Check_In'];
    }else{
        $Type_Of_Check_In = '';
    }
    
    if(isset($_GET['direction'])){
        $direction = $_GET['direction'];
    }else{
        $direction = '';
    }
    
    if(isset($_GET['Quantity'])){
        $Quantity = $_GET['Quantity'];
    }else{
        $Quantity = 0;
    }
    
    if(isset($_GET['Consultant'])){
        $Consultant = $_GET['Consultant'];
    }else{
        $Consultant = '';
    }
    if(isset($_GET['Clinic_ID'])){
        $Clinic_ID = $_GET['Clinic_ID'];
    }else{
        $Clinic_ID = '';
    }
    if(isset($_GET['finance_department_id'])){
        $finance_department_id = $_GET['finance_department_id'];
    }else{
        $finance_department_id = '';
    }
    if(isset($_GET['clinic_location_id'])){
        $clinic_location_id = $_GET['clinic_location_id'];
    }else{
        $clinic_location_id = '';
    }
    
    if(isset($_GET['Discount'])){
        $Discount = $_GET['Discount'];
    }else{
        $Discount = 0;
    }
    
    if(isset($_GET['Billing_Type'])){
        $Billing_Type = $_GET['Billing_Type'];
    }else{
        $Billing_Type = '';
    }
    
    if(isset($_GET['Guarantor_Name'])){
        $Guarantor_Name = $_GET['Guarantor_Name'];
    }else{
        $Guarantor_Name = '';
    }
    
    if(isset($_GET['Sponsor_ID'])){
        $Selected_Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
        $Selected_Sponsor_ID = 0;
    }
    
    if(isset($_GET['Claim_Form_Number'])){
        $Claim_Form_Number = $_GET['Claim_Form_Number'];
    }else{
        $Claim_Form_Number = '';
    }

    if(isset($_GET['Transaction_Mode'])){
        $Transaction_Mode = $_GET['Transaction_Mode'];
    }else{
        $Transaction_Mode = 'Normal Transaction';
    }
    
    if(strtolower($Transaction_Mode) == 'fast track transaction'){
        $Fast_Track = '1';
    }else{
        $Fast_Track = '0';
    }

    if(strtolower($Billing_Type) == 'outpatient credit'){
        $Transaction_Mode = 'Normal Transaction';
        $Fast_Track = '0';
    }

//$show_make_payment="";
//$sql_select_make_payment_configuration="SELECT 	configname FROM tbl_config WHERE configvalue='show' AND configname='showMakePaymentButton'";
//$sql_select_make_payment_configuration_result=mysqli_query($conn,$sql_select_make_payment_configuration) or die(mysqli_error($conn));
//if(mysqli_num_rows($sql_select_make_payment_configuration_result)>0){
//    //show button
//}else{
// $show_make_payment="style='display:none'";   
//}

///////////////////////check for system configuration//////////////////

$configResult = mysqli_query($conn,"SELECT * FROM tbl_config") or die(mysqli_error($conn));

				while($data = mysqli_fetch_assoc($configResult)){
					$configname = $data['configname'];
					$configvalue = $data['configvalue'];
					$_SESSION['configData'][$configname] = strtolower($configvalue);
				}
///////////////////////////////////////////////////////////////////////////////////////

    //88888888888888888888888888888888888888888888888888888888888888
 
    if(isset($_GET['Item_ID'])&&($_GET['Item_ID']!='') && isset($_GET['Guarantor_Name']) && ($_GET['Guarantor_Name'] != '')){
        $Item_ID = $_GET['Item_ID'];
    	$Billing_Type = $_GET['Billing_Type'];
    	$Guarantor_Name = $_GET['Guarantor_Name'];
        $Price=0;
        
         $sp=mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='$Guarantor_Name'") or die(mysqli_error($conn));
                $Sponsor_ID=  mysqli_fetch_assoc($sp)['Sponsor_ID'];

        if(strtolower($Transaction_Mode) != 'fast track transaction' || $Billing_Type=='Outpatient Credit'|| $Billing_Type=='Inpatient Credit'){
//            if($Billing_Type=='Outpatient Credit'||$Billing_Type=='Inpatient Credit'){
//                $sp=mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='$Guarantor_Name'") or die(mysqli_error($conn));
//                $Sponsor_ID=  mysqli_fetch_assoc($sp)['Sponsor_ID'];
//            }elseif($Billing_Type=='Outpatient Cash'||$Billing_Type=='Inpatient Cash'){
//                $sp=mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE LOWER(Guarantor_Name)='cash'") or die(mysqli_error($conn));
//                $Sponsor_ID=  mysqli_fetch_assoc($sp)['Sponsor_ID'];
//            }
        
        
            $Select_Price = "select Items_Price as price from tbl_item_price ip
                                    where ip.Item_ID = '$Item_ID' AND ip.Sponsor_ID = '$Sponsor_ID'";
            $itemSpecResult= mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));
         
            if(mysqli_num_rows($itemSpecResult)>0){
               $row = mysqli_fetch_assoc($itemSpecResult);
              $Price= $row['price'];
                if ($Price == 0) {
                    $Select_Price = "select Items_Price as price from tbl_general_item_price ig
                                        where ig.Item_ID = '$Item_ID'";
                    $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

                    if (mysqli_num_rows($itemGenResult) > 0) {
                        $row = mysqli_fetch_assoc($itemGenResult);
                        $Price = $row['price'];
                    } else {
                         $Price = 0;
                    }
                }
              // echo $Select_Price;
            }else{
                $Select_Price = "select Items_Price as price from tbl_general_item_price ig
                                    where ig.Item_ID = '$Item_ID'";
                $itemGenResult= mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));
                $row = mysqli_fetch_assoc($itemGenResult);
               $Price= $row['price'];
                //echo $Select_Price;
            }
        }else{
            //Get Fast Track Price
            $select = mysqli_query($conn,"select Item_Price from tbl_Fast_Track_Price where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select);
            if($num > 0){
                $row = mysqli_fetch_assoc($select);
                $Price= $row['Item_Price'];
            }else{
                mysqli_query($conn,"insert into tbl_Fast_Track_Price(Item_ID,Item_Price) values('$Item_ID','0')");
                $Price= 0;
            }
        }
    }else{
	   $Price= '0';
    }
    
    
    //88888888888888888888888888888888888888888888888888888888888888
    //get branch id
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }
    
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    //validate data entered then proceed
    if($Employee_ID != 0 && $Registration_ID != 0 && $Item_ID != 0 && $Type_Of_Check_In != '' && $direction != '' && $Quantity != '' && $Branch_ID != 0 && $Employee_ID != 0 && $Billing_Type != '' && $Claim_Form_Number != ''){
        
        //check if there is another record based on selected employee and patient
        //if found we delete them before continue with selected patient
        $select = "select * from tbl_reception_items_list_cache
                    where Employee_ID = '$Employee_ID' and Registration_ID <> '$Registration_ID'";
        $Transaction_Details = mysqli_query($conn,$select) or die(mysqli_error($conn));
        $no = mysqli_num_rows($Transaction_Details);
        if($no > 0){
            //delete them
            $delete_details = mysqli_query($conn,"delete from tbl_reception_items_list_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
            if($delete_details){
                //insert data to tbl_reception_items_list_cache
                
                if(strtolower($direction) == 'direct to doctor' || strtolower($direction) == 'direct to doctor via nurse station'){
                    $insert_data = mysqli_query($conn,"insert into tbl_reception_items_list_cache(
                                                Check_In_Type,Item_ID,Discount,
                                                    Price,Quantity,Patient_Direction,
                                                        Consultant,Consultant_ID,Employee_ID,Registration_ID,Billing_Type,
                                                        Sponsor_Name,Sponsor_ID,Claim_Form_Number,Fast_Track,Clinic_ID,finance_department_id,clinic_location_id)
                                            values('$Type_Of_Check_In','$Item_ID','$Discount',
                                                '$Price','$Quantity','$direction',
                                                    '$Consultant',(select Employee_ID from tbl_employee where Employee_Name = '$Consultant' and Employee_Type='Doctor' limit 1),'$Employee_ID','$Registration_ID',
                                                    '$Billing_Type','$Guarantor_Name','$Selected_Sponsor_ID','$Claim_Form_Number','$Fast_Track','$Clinic_ID','$finance_department_id','$clinic_location_id')") or die(mysqli_error($conn));
                }elseif(strtolower($direction) == 'direct to clinic' || strtolower($direction) == 'direct to clinic via nurse station'){
                    $insert_data = mysqli_query($conn,"insert into tbl_reception_items_list_cache(
                                                Check_In_Type,Item_ID,Discount,
                                                    Price,Quantity,Patient_Direction,
                                                        Consultant,Consultant_ID,Employee_ID,Registration_ID,Billing_Type,
                                                        Sponsor_Name,Sponsor_ID,Claim_Form_Number,Fast_Track,finance_department_id,clinic_location_id)
                                            values('$Type_Of_Check_In','$Item_ID','$Discount',
                                                '$Price','$Quantity','$direction',
                                                    '$Consultant',(select Clinic_ID from tbl_clinic where Clinic_Name = '$Consultant' limit 1),'$Employee_ID','$Registration_ID',
                                                    '$Billing_Type','$Guarantor_Name','$Selected_Sponsor_ID','$Claim_Form_Number','$Fast_Track','$finance_department_id','$clinic_location_id')") or die(mysqli_error($conn));
                }elseif(strtolower($direction) == 'others'){
                    $insert_data = mysqli_query($conn,"insert into tbl_reception_items_list_cache(
                                                Check_In_Type,Item_ID,Discount,
                                                    Price,Quantity,Patient_Direction,
                                                        Consultant,Consultant_ID,Employee_ID,Registration_ID,Billing_Type,
                                                        Sponsor_Name,Sponsor_ID,Claim_Form_Number,Fast_Track,finance_department_id,clinic_location_id)
                                            values('$Type_Of_Check_In','$Item_ID','$Discount',
                                                '$Price','$Quantity','$direction',
                                                    '$Consultant','','$Employee_ID','$Registration_ID',
                                                    '$Billing_Type','$Guarantor_Name','$Selected_Sponsor_ID','$Claim_Form_Number','$Fast_Track','$finance_department_id','$clinic_location_id')") or die(mysqli_error($conn));
                }
            }
        }else{
            if(strtolower($direction) == 'direct to doctor' || strtolower($direction) == 'direct to doctor via nurse station'){
                    $insert_data = mysqli_query($conn,"insert into tbl_reception_items_list_cache(
                                                Check_In_Type,Item_ID,Discount,
                                                    Price,Quantity,Patient_Direction,
                                                        Consultant,Consultant_ID,Employee_ID,Registration_ID,Billing_Type,
                                                        Sponsor_Name,Sponsor_ID,Claim_Form_Number,Fast_Track,Clinic_ID,finance_department_id,clinic_location_id)
                                            values('$Type_Of_Check_In','$Item_ID','$Discount',
                                                '$Price','$Quantity','$direction',
                                                    '$Consultant',(select Employee_ID from tbl_employee where Employee_Name = '$Consultant' and Employee_Type='Doctor'),'$Employee_ID','$Registration_ID',
                                                    '$Billing_Type','$Guarantor_Name','$Selected_Sponsor_ID','$Claim_Form_Number','$Fast_Track','$Clinic_ID','$finance_department_id','$clinic_location_id')") or die(mysqli_error($conn));
                }elseif(strtolower($direction) == 'direct to clinic' || strtolower($direction) == 'direct to clinic via nurse station'){
                  // echo "<script>console.log('an error has occcc')</script>";
                    $insert_data = mysqli_query($conn,"insert into tbl_reception_items_list_cache(
                                                Check_In_Type,Item_ID,Discount,
                                                    Price,Quantity,Patient_Direction,
                                                        Consultant,Consultant_ID,Employee_ID,Registration_ID,Billing_Type,
                                                        Sponsor_Name,Sponsor_ID,Claim_Form_Number,Fast_Track,finance_department_id,clinic_location_id)
                                            values('$Type_Of_Check_In','$Item_ID','$Discount',
                                                '$Price','$Quantity','$direction',
                                                    '$Consultant',(select Clinic_ID from tbl_clinic where Clinic_Name = '$Consultant'),'$Employee_ID','$Registration_ID',
                                                    '$Billing_Type','$Guarantor_Name','$Selected_Sponsor_ID','$Claim_Form_Number','$Fast_Track','$finance_department_id','$clinic_location_id')") or die(mysqli_error($conn));
                }elseif(strtolower($direction) == 'others'){
                    $insert_data = mysqli_query($conn,"insert into tbl_reception_items_list_cache(
                                                Check_In_Type,Item_ID,Discount,
                                                    Price,Quantity,Patient_Direction,
                                                        Consultant,Consultant_ID,Employee_ID,Registration_ID,Billing_Type,
                                                        Sponsor_Name,Sponsor_ID,Claim_Form_Number,Fast_Track,finance_department_id,clinic_location_id)
                                            values('$Type_Of_Check_In','$Item_ID','$Discount',
                                                '$Price','$Quantity','$direction',
                                                    '$Consultant','','$Employee_ID','$Registration_ID',
                                                    '$Billing_Type','$Guarantor_Name','$Selected_Sponsor_ID','$Claim_Form_Number','$Fast_Track','$finance_department_id','$clinic_location_id')") or die(mysqli_error($conn));
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
    }echo "</tr>";
    echo "<tr><td colspan=8 style='text-align: right;'> Total : ".number_format($total)."</td></tr></table>";
    
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
                                                ?>          
                                            <input type="button"  value="Go to mobile/Card Payment" class="art-button-green" onclick="create_epayment_mobile_card_bill()">&nbsp;&nbsp;
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
                                    ?>
					    <input type="button"  value="Go to mobile/Card Payment" class="art-button-green" onclick="create_epayment_mobile_card_bill()">&nbsp;&nbsp;           
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
 
