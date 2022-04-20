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
    
    if(isset($_GET['Discount'])){
        $Discount = $_GET['Discount'];
    }else{
        $Discount = 0;
    }
    
    
    //get item price
    $Select_Item_Price = "select Items_Price as price from tbl_item_price ip
                            where ip.Item_ID = '$Item_ID' AND ip.Sponsor_ID = 
                            (select Sponsor_ID from tbl_sponsor where Guarantor_Name = 'CASH' limit 1)";


    $itemSpecResult = mysqli_query($conn,$Select_Item_Price) or die(mysqli_error($conn));

    if (mysqli_num_rows($itemSpecResult) > 0) {
        $row = mysqli_fetch_assoc($itemSpecResult);
        $Price = $row['price'];
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
    } else {
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
    
    //validate data entered then proceeeeeeeeeeeeeeeeeeeeeeed
    if($Employee_ID != 0 && $Registration_ID != 0 && $Item_ID != 0 && $Type_Of_Check_In != '' && $direction != '' && $Quantity != '' && $Branch_ID != 0){
        //check if there is another record based on selected employee and patient
        
        $select = "select * from tbl_patient_payments_cache
                    where Registration_ID = '$Registration_ID' and
                        Employee_ID = '$Employee_ID' and
                            Transaction_status = 'pending' limit 1";
        $Transaction_Details = mysqli_query($conn,$select) or die(mysqli_error($conn));
        $no = mysqli_num_rows($Transaction_Details);
        if($no > 0){
            //echo $Consultant."  ".$_SESSION['Registration_ID_Reception_Transaction']." ".$direction;
            $_SESSION['Employee_ID_Reception_Transaction'] = $Employee_ID;
            $_SESSION['Registration_ID_Reception_Transaction'] = $Registration_ID;            
            while($row2 = mysqli_fetch_array($Transaction_Details)){
                $_SESSION['Patient_Payment_Cache_ID'] = $row2['Patient_Payment_Cache_ID'];
                $Patient_Payment_Cache_ID = $row2['Patient_Payment_Cache_ID'];
            }           
                $Patient_Payment_Cache_ID = $_SESSION['Patient_Payment_Cache_ID'];
            //insert item details
            
            
            if(strtolower($direction) == 'direct to doctor' || strtolower($direction) == 'direct to doctor via nurse station'){
                $insert_item_details =
                    mysqli_query($conn,"insert into tbl_patient_payment_item_list_cache
                        (Check_In_Type,Item_ID,Discount,
                            Price,Quantity,Patient_Direction,
                                Consultant,Consultant_ID,Patient_Payment_Cache_ID)
                                
                    values('$Type_Of_Check_In','$Item_ID','$Discount',
                                '$Price','$Quantity','$direction',
                                    '$Consultant',(select Employee_ID from tbl_employee where Employee_Name = '$Consultant' limit 1),
                                        '$Patient_Payment_Cache_ID')") or die(mysqli_error($conn));
                                    
            }elseif(strtolower($direction) == 'direct to clinic' || strtolower($direction) == 'direct to clinic via nurse station'){
                $insert_item_details =
                    mysqli_query($conn,"insert into tbl_patient_payment_item_list_cache
                        (Check_In_Type,Item_ID,Discount,
                            Price,Quantity,Patient_Direction,
                                Consultant,Consultant_ID,Patient_Payment_Cache_ID)
                                
                    values('$Type_Of_Check_In','$Item_ID','$Discount',
                                '$Price','$Quantity','$direction',
                                    '$Consultant',(select clinic_ID from tbl_clinic where clinic_name = '$Consultant' limit 1),
                                        '$Patient_Payment_Cache_ID')") or die(mysqli_error($conn));
                
            }
        }else{
            //insert transaction session control
            $_SESSION['Employee_ID_Reception_Transaction'] = $Employee_ID;
            $_SESSION['Registration_ID_Reception_Transaction'] = $Registration_ID;
            
            //select the last check in id based on patient id
            $select_check_in_id = mysqli_query($conn,"select Check_In_ID from tbl_check_in where
                                                Registration_ID = '$Registration_ID' order by check_in_id desc limit 1") or die(mysqli_query($conn,));
            while($check_id_data = mysqli_fetch_array($select_check_in_id)){
                $Check_In_ID = $check_id_data['Check_In_ID'];
            }
            
            
            
            //insert the record from the begin
            $insert_details = mysqli_query($conn,"insert into tbl_patient_payments_cache(
                            Registration_ID,Employee_ID,branch_id,
                                Payment_Date_And_Time,Sponsor_ID,Sponsor_Name,
                                    Billing_Type,Receipt_Date,check_in_id)
                            
                            values('$Registration_ID','$Employee_ID','$Branch_ID',
                                (select now()),(select Sponsor_ID from tbl_sponsor where Guarantor_Name = 'cash' limit 1),'CASH',
                                    'Outpatient Cash',(select now()),'$Check_In_ID')") or die(mysqli_error($conn));
            
            
            //get curent Patient_Payment_Cache_ID
            $select_Patient_Payment_Cache_ID = mysqli_query($conn,"select Patient_Payment_Cache_ID from tbl_patient_payments_cache
                                                  where Employee_ID = '$Employee_ID' and
                                                    Registration_ID = '$Registration_ID' order by Patient_Payment_Cache_ID DESC") or die(mysqli_error($conn));
            while($row = mysqli_fetch_array($select_Patient_Payment_Cache_ID)){
                $Patient_Payment_Cache_ID = $row['Patient_Payment_Cache_ID'];
            }
            
            
            if(strtolower($direction) == 'direct to doctor' || strtolower($direction) == 'direct to doctor via nurse station'){
                $insert_item_details =
                    mysqli_query($conn,"insert into tbl_patient_payment_item_list_cache
                        (Check_In_Type,Item_ID,Discount,
                            Price,Quantity,Patient_Direction,
                                Consultant,Consultant_ID,Patient_Payment_Cache_ID)
                                
                    values('$Type_Of_Check_In','$Item_ID','$Discount',
                                '$Price','$Quantity','$direction',
                                    '$Consultant',(select Employee_ID from tbl_employee where Employee_Name = '$Consultant' limit 1),
                                        '$Patient_Payment_Cache_ID')") or die(mysqli_error($conn));
                                    
            }elseif(strtolower($direction) == 'direct to clinic' || strtolower($direction) == 'direct to clinic via nurse station'){
                $insert_item_details =
                    mysqli_query($conn,"insert into tbl_patient_payment_item_list_cache
                        (Check_In_Type,Item_ID,Discount,
                            Price,Quantity,Patient_Direction,
                                Consultant,Consultant_ID,Patient_Payment_Cache_ID)
                                
                    values('$Type_Of_Check_In','$Item_ID','$Discount',
                                '$Price','$Quantity','$direction',
                                    '$Consultant',(select clinic_ID from tbl_clinic where clinic_name = '$Consultant' limit 1),
                                        '$Patient_Payment_Cache_ID')") or die(mysqli_error($conn));
                
            }
        }
        
        
        
        //update fieldset
        echo '<table width =100%>';
            echo "<tr id='thead'><td style='width: 3%;'>Sn</td><td style='width: 10%;'>Check in type</td>";
                echo '<td style="width: 20%;">Location</td>
                        <td style="width: 28%;">Item description</td>
                            <td style="text-align:right; width: 8%;">Price</td>
                                <td style="text-align:right; width: 8%;">Discount</td>
                                    <td style="text-align:right; width: 8%;">Quantity</td>
                                        <td style="text-align:right; width: 8%;">Sub total</td><td width=4%>Remove</td></tr>';
                                        
        $total = 0; $temp = 1;
            $select_Transaction_Items = mysqli_query($conn,
                "select Check_In_Type, Patient_Direction, Product_Name, Price, Discount, Quantity,Patient_Payment_Item_List_ID, ppc.Registration_ID
                    from tbl_items t, tbl_patient_payments_cache ppc, tbl_patient_payment_item_list_cache ppi
                        where ppc.Patient_Payment_Cache_ID = ppi.Patient_Payment_Cache_ID and
                            t.item_id = ppi.item_id and
                                Employee_ID = '$Employee_ID' and
                                    Registration_ID = '$Registration_ID' and
                                        Transaction_status = 'pending'") or die(mysqli_error($conn));
            
        $num_of_items_selected = mysqli_num_rows($select_Transaction_Items);
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
                    <input type='button' style='color: red; font-size: 10px;' value='X'  onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Patient_Payment_Item_List_ID']; ?>,<?php echo $row['Registration_ID']; ?>)'>
                </td>
    <?php 
                $total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity']);
                $temp++;
            }   echo "</tr>";
            echo "<tr><td colspan=8 style='text-align: right;'> <b>Total : ".number_format($total)."</b></td></tr></table>";
    }
    
?>