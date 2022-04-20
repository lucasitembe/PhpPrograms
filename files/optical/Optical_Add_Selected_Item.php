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
    
    if(isset($_GET['Source'])){
        $Source = $_GET['Source'];
    }else{
        $Source = '';
    }

    if(isset($_GET['Comment'])){
        $Comment = $_GET['Comment'];
    }else{
        $Comment = '';
    }
    
    if(isset($_GET['Quantity'])){
        $Quantity = $_GET['Quantity'];
    }else{
        $Quantity = 0;
    }
    
    if(isset($_GET['Consultant_ID'])){
        $Consultant_ID = $_GET['Consultant_ID'];
    }else{
        $Consultant_ID = '';
    }
    
    
    if(isset($_GET['Billing_Type'])){
        $Billing_Type = $_GET['Billing_Type'];

        $select_sponsor_cash=mysqli_query($conn,"SELECT optical_sponsor_setup_ID, Sponsor_ID, Employee_ID FROM optical_sponsor_setup");
        $Sponsor_ID_setup=mysqli_fetch_array($select_sponsor_cash)['Sponsor_ID'];
        if($Billing_Type == 'Outpatient Cash'  || $Billing_Type == 'Inpatient Cash'){
            $select_sponsor_cash=mysqli_query($conn,"SELECT Sponsor_ID, Guarantor_Name, payment_method FROM tbl_sponsor WHERE payment_method='cash' and  Sponsor_ID='$Sponsor_ID_setup'");
            while($row=mysqli_fetch_array($select_sponsor_cash)){
                $Guarantor_Name=$row['Guarantor_Name'];
                $Sponsor_ID=$row['Sponsor_ID'];
            }
        }else{
            
            // $Sponsor_ID=$_GET['Sponsor_ID'];
            // $Guarantor_Name = $_GET['Guarantor_Name'];
            if(isset($_GET['Guarantor_Name'])){
                $Guarantor_Name = $_GET['Guarantor_Name'];
            }else{
                $Guarantor_Name = '';
            }
            
            if(isset($_GET['Sponsor_ID'])){
                $Sponsor_ID = $_GET['Sponsor_ID'];
            }else{
                $Sponsor_ID = 0;
            }
        }
    }else{
        $Billing_Type = '';
    }
    
    // if(isset($_GET['Guarantor_Name'])){
    //     $Guarantor_Name = $_GET['Guarantor_Name'];
    // }else{
    //     $Guarantor_Name = '';
    // }
    
    // if(isset($_GET['Sponsor_ID'])){
    //     $Sponsor_ID = $_GET['Sponsor_ID'];
    // }else{
    //     $Sponsor_ID = 0;
    // }
    
    if(isset($_GET['Clinic_ID'])){
        $Clinic_ID = $_GET['Clinic_ID'];
    }else{
        $Clinic_ID = '';
    }
    
    if(isset($_GET['Claim_Form_Number'])){
        $Claim_Form_Number = $_GET['Claim_Form_Number'];
    }else{
        $Claim_Form_Number = '';
    }

    if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = 0;
    }

    if(isset($_GET['Type_Of_Check_In'])){
        $Type_Of_Check_In = $_GET['Type_Of_Check_In'];
    }else{
        $Type_Of_Check_In = '';
    }
    
    
    
    if(isset($_GET['Item_ID'])&&($_GET['Item_ID']!='') && isset($_GET['Guarantor_Name']) && ($_GET['Guarantor_Name'] != '')){
        $Item_ID = $_GET['Item_ID'];
        $Billing_Type = $_GET['Billing_Type'];
        $Guarantor_Name = $_GET['Guarantor_Name'];
        $Price=0;
    
        if($Billing_Type=='Outpatient Credit'||$Billing_Type=='Inpatient Credit'){
            $sp=mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='$Guarantor_Name'") or die(mysqli_error($conn));
            $Sponsor_ID2=  mysqli_fetch_assoc($sp)['Sponsor_ID'];
        }elseif($Billing_Type=='Outpatient Cash'||$Billing_Type=='Inpatient Cash'){
            $sp=mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE LOWER(Guarantor_Name)='cash'") or die(mysqli_error($conn));
            $Sponsor_ID2=  mysqli_fetch_assoc($sp)['Sponsor_ID'];
        }
        
        $Sponsor_ID2=$Sponsor_ID;
        
        $Select_Price = "select Items_Price as price from tbl_item_price ip
                                    where ip.Item_ID = '$Item_ID' AND ip.Sponsor_ID = '$Sponsor_ID2'";
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
            }else{
                $Select_Price = "select Items_Price as price from tbl_general_item_price ig
                                    where ig.Item_ID = '$Item_ID'";
                $itemGenResult= mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));
                $row = mysqli_fetch_assoc($itemGenResult);
               $Price= $row['price'];
                //echo $Select_Price;
            }
    }else{
        $Price= '0';
    }


    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    //validate data entered then proceed
    if($Employee_ID != 0 && $Registration_ID != 0 && $Item_ID != 0 && $Quantity != '' && $Employee_ID != 0 && $Billing_Type != '' && $Sub_Department_ID != 0 && $Sub_Department_ID != null){
        //check if there is another record based on selected employee and patient
        //if found delete them before continue with selected patient
        $select = "select * from tbl_departmental_items_list_cache
                    where Employee_ID = '$Employee_ID' and Registration_ID <> '$Registration_ID'";
        $Transaction_Details = mysqli_query($conn,$select) or die(mysqli_error($conn));
        $no = mysqli_num_rows($Transaction_Details);
        if($no > 0){
            //delete them
            $delete_details = mysqli_query($conn,"delete from tbl_departmental_items_list_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
            if($delete_details){
                //insert data to tbl_departmental_items_list_cache
                $insert_data = mysqli_query($conn,"INSERT INTO tbl_departmental_items_list_cache(
                                                Claim_Form_Number, Billing_Type, Sponsor_Name,
                                                Sponsor_ID, Item_ID, Price,Quantity,
                                                Consultant_ID, Employee_ID, Registration_ID,Comment,Sub_Department_ID,Type_Of_Check_In,Clinic_ID)
                                                
                                            values('$Claim_Form_Number','$Billing_Type','$Guarantor_Name',
                                                '$Sponsor_ID','$Item_ID','$Price','$Quantity',
                                                '$Consultant_ID','$Employee_ID','$Registration_ID','$Comment','$Sub_Department_ID','$Type_Of_Check_In','$Clinic_ID')") or die(mysqli_error($conn));
            }
        }else{
            $insert_data = mysqli_query($conn,"INSERT INTO tbl_departmental_items_list_cache(
                                                Claim_Form_Number, Billing_Type, Sponsor_Name,
                                                Sponsor_ID, Item_ID, Price,Quantity,
                                                Consultant_ID, Employee_ID, Registration_ID,Comment,Sub_Department_ID,Type_Of_Check_In,Clinic_ID)
                                                
                                            values('$Claim_Form_Number','$Billing_Type','$Guarantor_Name',
                                                '$Sponsor_ID','$Item_ID','$Price','$Quantity',
                                                '$Consultant_ID','$Employee_ID','$Registration_ID','$Comment','$Sub_Department_ID','$Type_Of_Check_In','$Clinic_ID')") or die(mysqli_error($conn));
        }
    }
?>
<!--<fieldset style='overflow-y: scroll; height: 200px;'>-->
<!-- 79304 -->
<?php
    $total = 0;
    $temp = 0;
    echo '<table width =100%>';
    echo '<tr id="thead">
            <td style="text-align: center;" width=5%><b>Sn</b></td>
            <td><b>Item Name</b></td>
            <td width="12%"><b>Location</b></td>
            <td style="text-align: left;" width=17%><b>Comment</b></td>
            <td style="text-align: right;" width=8%><b>Price</b></td>
            <td style="text-align: right;" width=8%><b>Quantity</b></td>
            <td style="text-align: right;" width=8%><b>Sub Total</b></td>
            <td style="text-align: center;" width=6%><b>Action</b></td></tr>';
            
    $select_Transaction_Items = mysqli_query($conn,
        "select Item_Cache_ID, Product_Name, Price, Quantity, Registration_ID, Comment, Sub_Department_ID, Type_Of_Check_In 
            from tbl_items t, tbl_departmental_items_list_cache alc
                where alc.Item_ID = t.Item_ID and
                    alc.Employee_ID = '$Employee_ID' and
                            Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    
    $no_of_items = mysqli_num_rows($select_Transaction_Items);
    while($row = mysqli_fetch_array($select_Transaction_Items)){
        $Temp_Sub_Department_ID = $row['Sub_Department_ID'];
        //get sub department name
        $select_sub_department = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Temp_Sub_Department_ID'") or die(mysqli_error($conn));
        $my_num = mysqli_num_rows($select_sub_department);
        if($my_num > 0){
            while($rw = mysqli_fetch_array($select_sub_department)){
                $Sub_Department_Name = $rw['Sub_Department_Name'];
            }
        }else{
            $Sub_Department_Name = '';
        }
        echo "<tr><td>".++$temp."</td>";
        echo "<td>".$row['Product_Name']."</td>";
        echo "<td>".$Sub_Department_Name."</td>";
        echo "<td>".$row['Comment']."</td>";
        echo "<td style='text-align:right;'>". (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price']))."</td>";
        echo "<td style='text-align:right;'>".$row['Quantity']."</td>";
        echo "<td style='text-align:right;'>".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'] * $row['Quantity'], 2) : number_format($row['Price'] * $row['Quantity']))."</td>";
    ?>
        <td style='text-align: center;'> 
            <input type='button' style='color: red; font-size: 10px;' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Item_Cache_ID']; ?>,<?php echo $row['Registration_ID']; ?>)'>
        </td>
    <?php
        $total = $total + ($row['Price'] * $row['Quantity']);
    }echo "</tr></table>";
?>
<!--</fieldset>-->
