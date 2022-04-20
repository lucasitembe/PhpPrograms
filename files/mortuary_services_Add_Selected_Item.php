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
    
    if(isset($_GET['Quantity'])){
        $Quantity = $_GET['Quantity'];
    }else{
        $Quantity = 0;
    }
    if(isset($_GET['Clinic_ID'])){
        $Clinic_ID = $_GET['Clinic_ID'];
    }else{
        $Clinic_ID = 0;
    }
      if(isset($_GET['clinic_location_id'])){
        $clinic_location_id = $_GET['clinic_location_id'];
    }else{
        $clinic_location_id = 0;
    }
      if(isset($_GET['working_department'])){
        $working_department = $_GET['working_department'];
    }else{
        $working_department = 0;
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
        $Sponsor_ID_Sel = $_GET['Sponsor_ID'];
    }else{
        $Sponsor_ID_Sel = 0;
    }
    
    if(isset($_GET['Claim_Form_Number'])){
        $Claim_Form_Number = $_GET['Claim_Form_Number'];
    }else{
        $Claim_Form_Number = '';
    }
    
    if(isset($_GET['Transaction_Mode'])){
        $Transaction_Mode = $_GET['Transaction_Mode'];
    }else{
        $Transaction_Mode = '';
    }
    
    if(isset($_GET['Item_ID'])&&($_GET['Item_ID']!='') && isset($_GET['Guarantor_Name']) && ($_GET['Guarantor_Name'] != '')){
        $Item_ID = $_GET['Item_ID'];
	$Billing_Type = $_GET['Billing_Type'];
	$Guarantor_Name = $_GET['Guarantor_Name'];
	
    //$Sponsor_ID = 0;
    if(strtolower($Transaction_Mode) != 'fast track transaction'){

    if ($Billing_Type == 'Outpatient Credit' || $Billing_Type == 'Inpatient Credit') {
        $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='$Guarantor_Name'") or die(mysqli_error($conn));
        $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];
    } elseif ($Billing_Type == 'Outpatient Cash' || $Billing_Type == 'Inpatient Cash') {
        $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE LOWER(Guarantor_Name)='cash'") or die(mysqli_error($conn));
        $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];
    }
    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = $_GET['Sponsor_ID'];
    }
    

    $Select_Price = "SELECT Items_Price as price from tbl_item_price ip
                                    where ip.Item_ID = '$Item_ID' AND ip.Sponsor_ID = '$Sponsor_ID'";
    $itemSpecResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

     if (mysqli_num_rows($itemSpecResult) > 0) {
        $row = mysqli_fetch_assoc($itemSpecResult);
         $Price = $row['price'];
                if ($Price == 0) {
                    $Select_Price = "SELECT Items_Price as price from tbl_general_item_price ig
                                        where ig.Item_ID = '$Item_ID'";
                    $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

                    if (mysqli_num_rows($itemGenResult) > 0) {
                        $row = mysqli_fetch_assoc($itemGenResult);
                        $Price = $row['price'];
                    } else {
                         $Price = 0;
                    }
                }
                 //echo number_format($Price);
        //echo $Select_Price;
     } else {
        $Select_Price = "SELECT Items_Price as price from tbl_general_item_price ig
                                    where ig.Item_ID = '$Item_ID'";
        $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

        if (mysqli_num_rows($itemGenResult) > 0) {
            $row = mysqli_fetch_assoc($itemGenResult);
            $Price=$row['price'];
        } else {
            $Price= 0;
        }
        //echo $Select_Price;
     }
 }
//  else{
//     //Get Fast Track Price
//         $select = mysqli_query($conn,"SELECT Item_Price from tbl_Fast_Track_Price where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
//         $num = mysqli_num_rows($select);
//         if($num > 0){
//             $row = mysqli_fetch_assoc($select);
//             $Price = $row['Item_Price'];
//         }else{
//             mysqli_query($conn,"INSERT into tbl_Fast_Track_Price(Item_ID,Item_Price) values('$Item_ID','0')");
//             $Price = '0';
//         }
//  }
    }
    
    //8888888888888888888888888888888888888888888888888888
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    //validate data entered then proceed
    if($Employee_ID != 0 && $Registration_ID != 0 && $Item_ID != 0 && $Quantity != '' && $Employee_ID != 0 && $Billing_Type != ''){
        
        //check if there is another record based on selected employee and patient
        //if found we delete them before continue with selected patient
        $select = "SELECT * from tbl_payment_cache
                    where Employee_ID = '$Employee_ID' and Registration_ID = '$Registration_ID' AND ";
        $Transaction_Details = mysqli_query($conn,$select) or die(mysqli_error($conn));
        $no = mysqli_num_rows($Transaction_Details);

        if(strtolower($Transaction_Mode) == 'fast track transaction'){
            $Fast_Track = '1';
        }else{
            $Fast_Track = '0';
        }
        $insert_data = mysqli_query($conn,"INSERT INTO tbl_item_list_cache(
                                    Claim_Form_Number, Billing_Type, Sponsor_Name,
                                    Sponsor_ID, Item_ID, Price, Quantity, Discount,
                                    Consultant_ID, Employee_ID, Registration_ID,Dosage,Fast_Track,Clinic_ID,working_department,clinic_location_id)
                                                
                                values('$Claim_Form_Number','$Billing_Type','$Guarantor_Name',
                                    '$Sponsor_ID_Sel','$Item_ID','$Price','$Quantity','$Discount',
                                    '$Consultant_ID','$Employee_ID','$Registration_ID','$Dosage','$Fast_Track','$Clinic_ID','$working_department','$clinic_location_id')") or die(mysqli_error($conn));
    }
    
?>
<!--<fieldset style='overflow-y: scroll; height: 200px;'>-->
<?php
    $total = 0;
    $temp = 0;
    echo '<table width =100%>';
    echo "<tr><td colspan=8><hr></td></tr>";
    echo '<tr id="thead">
            <td style="text-align: left;" width=5%><b>Sn</b></td>
            <td><b>Service Name</b></td>
            <td style="text-align: right;" width=8%><b>Price</b></td>
            <td style="text-align: right;" width=8%><b>Discount</b></td>
            <td style="text-align: right;" width=8%><b>Quantity</b></td>
            <td style="text-align: right;" width=8%><b>Sub Total</b></td>
            <td style="text-align: center;" width=6%><b>Action</b></td></tr>';
    echo "<tr><td colspan=8><hr></td></tr>";      
    $select_Transaction_Items = mysqli_query($conn,
        "SELECT Item_Cache_ID, Product_Name, Price, Quantity, Registration_ID, Dosage, Discount
            from tbl_items t, tbl_pharmacy_inpatient_items_list_cache alc
                where alc.Item_ID = t.Item_ID and
                    alc.Employee_ID = '$Employee_ID' and
                            Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    
    $no_of_items = mysqli_num_rows($select_Transaction_Items);
    while($row = mysqli_fetch_array($select_Transaction_Items)){
        echo "<tr><td>".++$temp."</td>";
        echo "<td>".$row['Product_Name']."</td>";
        echo "<td>".$row['Dosage']."</td>";
        echo "<td style='text-align:right;'>";
            if($_SESSION['systeminfo']['price_precision'] == 'yes'){ echo number_format($row['Price'], 2); }else{ echo number_format($row['Price']); }
        echo "</td>";
        echo "<td style='text-align:right;'>".$row['Discount']."</td>";
        echo "<td style='text-align:right;'>".$row['Quantity']."</td>";
        echo "<td style='text-align:right;'>";
            if($_SESSION['systeminfo']['price_precision'] == 'yes'){ echo number_format(($row['Price'] - $row['Discount']) * $row['Quantity'], 2); }else{ echo number_format(($row['Price'] - $row['Discount']) * $row['Quantity']); }
        echo "</td>";
    ?>
        <td style='text-align: center;'> 
            <input type='button' style='color: red; font-size: 10px;' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Item_Cache_ID']; ?>,<?php echo $row['Registration_ID']; ?>)'>
        </td>
    <?php
        $total = $total + ($row['Price'] * $row['Quantity']);
    }echo "</tr></table>";
?>
<!--</fieldset>-->
