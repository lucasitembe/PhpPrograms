<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    $total = 0;
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    if(isset($_SESSION['userinfo']['Employee_Name'])){
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $Employee_Name = 0;
    }

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
    
    
    if(isset($_GET['Guarantor_Name'])){
        $Guarantor_Name = $_GET['Guarantor_Name'];
    }else{
        $Guarantor_Name = '';
    }
    
    if(isset($_GET['Payment_Cache_ID'])){
        $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    }else{
        $Payment_Cache_ID = '';
    }

    if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = 0;
    }

    $Billing_Type = 'Outpatient Cash';

    //get some imprtant previous details 
    $get_details = mysqli_query($conn,"select Consultant, Consultant_ID from tbl_item_list_cache where 
                                Payment_Cache_ID = '$Payment_Cache_ID' and
                                Check_In_Type = 'Laboratory' and
                                Status = 'active'") or die(mysqli_error($conn));

    $num = mysqli_num_rows($get_details);
    
    if($num > 0){
        while ($data = mysqli_fetch_array($get_details)) {
            $Consultant = $data['Consultant'];
            $Consultant_ID = $data['Consultant_ID'];
        }
    }else{
        $Consultant = $Employee_Name;
        $Consultant_ID = $Employee_ID;
    }

    if(isset($_GET['Item_ID'])&&($_GET['Item_ID']!='') && isset($_GET['Guarantor_Name']) && ($_GET['Guarantor_Name'] != '')){
        $Item_ID = $_GET['Item_ID'];
        $Guarantor_Name = $_GET['Guarantor_Name'];
        $Price=0;
    
        if($Billing_Type=='Outpatient Credit'||$Billing_Type=='Inpatient Credit'){
            $sp=mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='$Guarantor_Name'") or die(mysqli_error($conn));
            $Sponsor_ID2=  mysqli_fetch_assoc($sp)['Sponsor_ID'];
        }elseif($Billing_Type=='Outpatient Cash'||$Billing_Type=='Inpatient Cash'){
            $sp=mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE LOWER(Guarantor_Name)='cash'") or die(mysqli_error($conn));
            $Sponsor_ID2=  mysqli_fetch_assoc($sp)['Sponsor_ID'];
        }
        
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
    if($Employee_ID != 0 && $Registration_ID != 0 && $Item_ID != 0 && $Quantity != '' && $Employee_ID != 0 && $Billing_Type != '' ){
        //add selected item
        $insert = mysqli_query($conn,"insert into tbl_item_list_cache(
                                Check_In_Type, Item_ID, Price, 
                                Quantity, Patient_Direction, Consultant, Consultant_ID, 
                                Status, Payment_Cache_ID, Transaction_Date_And_Time, Doctor_Comment,
                                Sub_Department_ID, Transaction_Type, Service_Date_And_Time
                                ) 
                            values('Laboratory','$Item_ID','$Price',
                                '$Quantity','Others','$Consultant','$Consultant_ID',
                                'active','$Payment_Cache_ID',(select now()),'$Comment',
                                '$Sub_Department_ID','Cash',(select now()))") or die(mysqli_error($conn));

    }
?>
<table width="100%">
        <tr>
            <td style="text-align: center;" width="5%"><b>SN</b></td>
            <td><b>ITEM NAME</b></td>
            <td style="text-align:right;" width=8%><b>PRICE</b></td>
            <td style="text-align: center;" width=8%><b>QUANTITY</b></td>
            <td style="text-align: right  ;" width=8%><b>SUB TOTAL</b></td>
        </tr>
<?php
     $select_Transaction_Items_Active = mysqli_query($conn,"select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, 
                                                    ilc.Payment_Cache_ID, ilc.Edited_Quantity
                                                    from tbl_item_list_cache ilc, tbl_Items its
                                                    where ilc.item_id = its.item_id and
                                                    ilc.status = 'active' and
                                                    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                                    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                                    ilc.Transaction_Type = 'Cash' and
                                                    ilc.Check_In_Type = 'Laboratory'") or die(mysqli_error($conn));

    $no = mysqli_num_rows($select_Transaction_Items_Active);

    if($no > 0){
        while($row = mysqli_fetch_array($select_Transaction_Items_Active)){
            if($row['Edited_Quantity'] == 0){  
                $Quantity = $row['Quantity'];
            }else{
                $Quantity = $row['Edited_Quantity'];
            }
            if($Quantity < 1){ $Control = 'no'; }
            if($row['Price'] <= 0){ $Control = 'no'; }

            $status = $row['status'];
            echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
            echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
            echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
            //echo "<td><input type='hidden' name='item_list_id[]' value='".$row['Payment_Item_Cache_List_ID']."' /><input type='text' name='discount[]' style='text-align:right;' value='".number_format($row['Discount'])."'></td>";
        
            echo "<td style='text-align:right;'>";
            echo "<input type='text' readonly='readonly' value='$Quantity' onkeyup='pharmacyQuantityUpdate2(".$row['Payment_Item_Cache_List_ID'].",this.value)' style='text-align: center;' size=13>";            
            echo'</td>';
            echo "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";    
            if($no > 1){
        ?>
            <td style="text-align: center;" width="4%"><input type="button" value="X" type="button" onclick="removeitem('<?php echo $row['Product_Name']; ?>',<?php echo $row["Payment_Item_Cache_List_ID"]; ?>)"></td>
        <?php
            }
            $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
            $temp++;
            echo "</tr>";
        }
    }
?>
    <tr>
        <td colspan="4" style="text-align: right;"><b>GRAND TOTAL</b></td>
        <td><input type="text" readonly="readonly" style="text-align: right;" value="<?php echo number_format($total); ?>"></td>
    </tr>
</table>