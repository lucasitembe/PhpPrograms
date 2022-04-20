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
    
    if(isset($_GET['Dosage'])){
        $Dosage = $_GET['Dosage'];
    }else{
        $Dosage = '';
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
    
    if(isset($_GET['Consultant_ID'])){
        $Consultant_ID = $_GET['Consultant_ID'];
    }else{
        $Consultant_ID = '';
    }
    
    if(isset($_GET['Billing_Type'])){
        $Billing_Type = $_GET['Billing_Type'];
    }else{
        $Billing_Type = '';
    }
    
    if(isset($_GET['Sponsor_ID_Sel'])){
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
    
    if(isset($_GET['Item_ID'])&&($_GET['Item_ID']!='') && isset($_GET['Sponsor_ID']) && ($_GET['Sponsor_ID'] != '')){
        // $Item_ID = $_GET['Item_ID'];
        //         $Billing_Type = $_GET['Billing_Type'];
        //         $Sponsor_ID_Sel = $_GET['Sponsor_ID_Sel'];
                
                //$Sponsor_ID = 0;
                if(strtolower($Transaction_Mode) != 'fast track transaction'){
                
                $Select_Price = "SELECT Items_Price as price from tbl_item_price ip
                                                where ip.Item_ID = '$Item_ID' AND ip.Sponsor_ID = '$Sponsor_ID_Sel'";
                $itemSpecResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

                if (mysqli_num_rows($itemSpecResult) > 0) {
                    $row = mysqli_fetch_assoc($itemSpecResult);
                    $Price = $row['price'];
                           
                            //echo number_format($Price);
                }
            }else{
                //Get Fast Track Price
                    $select = mysqli_query($conn,"SELECT Item_Price from tbl_Fast_Track_Price where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($select);
                    if($num > 0){
                        $row = mysqli_fetch_assoc($select);
                        $Price = $row['Item_Price'];
                    }else{
                        mysqli_query($conn,"insert into tbl_Fast_Track_Price(Item_ID,Item_Price) values('$Item_ID','0')");
                        $Price = '0';
                    }
            }
    }

    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    // die("SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE Registration_ID = '$Registration_ID' AND Receipt_Date = CURDATE()");
    
    $payment_before = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE Registration_ID = '$Registration_ID' AND Receipt_Date = CURDATE()"))['Payment_Cache_ID'];

    if ($payment_before > 0){
        $Payment_Cache_ID = $payment_before;

    }else{

        $insert_data_1 = mysqli_query($conn,"INSERT INTO tbl_payment_cache (
            Registration_ID, Billing_Type,
            Sponsor_ID, Employee_ID, Transaction_type, Transaction_status,Order_Type,Receipt_Date, Payment_Date_And_Time,branch_id)
                        
        values('$Registration_ID','Inpatient Cash',
            '$Sponsor_ID_Sel','$Employee_ID','indirect cash','active','normal',NOW(),NOW(),'1')") or die(mysqli_error($conn));
    
        $Payment_Cache_ID = mysqli_insert_id($conn);
    }

    // if($Payment_Cache_ID > 0){
    //     echo "OYOOOOOOOOOO";
    //     exit();
    // }

 

    if($Employee_ID != 0 && $Registration_ID != 0 && $Item_ID != 0 && $Quantity != '' && $Employee_ID != 0 && $Payment_Cache_ID != 0){

    
        if(strtolower($Transaction_Mode) == 'fast track transaction'){
            $Fast_Track = '1';
        }else{
            $Fast_Track = '0';
        }
        $insert_data = mysqli_query($conn,"INSERT INTO tbl_item_list_cache (
            Check_In_Type, Category, Item_ID, Discount, Price, Quantity, Patient_Direction, Status, Employee_Created, Created_Date_Time, Payment_Cache_ID, Transaction_Date_And_Time, Clinic_ID, Doctor_Comment, clinic_location_id,finance_department_id)
        values('Mortuary','Inpatient Cash','$Item_ID','$Discount','$Price','$Quantity','others','active','$Employee_ID',NOW(),'$Payment_Cache_ID', NOW(),'$Clinic_ID', '$Dosage', '$clinic_location_id','$working_department')") or die(mysqli_error($conn));        
        
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
            <td style="text-align: left;" width=25%><b>Remarks</b></td>
            <td style="text-align: right;" width=8%><b>Price</b></td>
            <td style="text-align: right;" width=8%><b>Discount</b></td>
            <td style="text-align: right;" width=8%><b>Quantity</b></td>
            <td style="text-align: right;" width=8%><b>Sub Total</b></td>
            <td style="text-align: center;" width=6%><b>Action</b></td></tr>';
    echo "<tr><td colspan=8><hr></td></tr>";


                        $select_Transaction_Items = mysqli_query($conn,
                        "SELECT ilc.Payment_Item_Cache_List_ID, t.Product_Name, ilc.Price, ilc.Quantity, ilc.Doctor_Comment, ilc.Discount
                            from tbl_items t, tbl_item_list_cache ilc 
                                where ilc.Item_ID = t.Item_ID and
                                    ilc.Employee_Created = '$Employee_ID' and ilc.Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
    
    $no_of_items = mysqli_num_rows($select_Transaction_Items);
    while($row = mysqli_fetch_array($select_Transaction_Items)){
        echo "<tr><td>".++$temp."</td>";
        echo "<td>".$row['Product_Name']."</td>";
        echo "<td>".$row['Doctor_Comment']."</td>";
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
            <input type='button' style='color: red; font-size: 10px;' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Payment_Item_Cache_List_ID']; ?>,<?php echo $row['Registration_ID']; ?>)'>
        </td>
    <?php
        $total = $total + ($row['Price'] * $row['Quantity']);
    }echo "</tr></table>";
?>
<!--</fieldset>-->
