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
        $Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
        $Sponsor_ID = 0;
    }
    
    if(isset($_GET['Claim_Form_Number'])){
        $Claim_Form_Number = $_GET['Claim_Form_Number'];
    }else{
        $Claim_Form_Number = '';
    }
    
    /*//get item price
    $Select_Item_Price = "select Selling_Price_Cash as price from tbl_items i
                                    where i.Item_ID = '$Item_ID'";
    
    $result = @mysqli_query($conn,$Select_Item_Price);
    $row = @mysqli_fetch_assoc($result);
    $Price = $row['price'];
    */
    
    //888888888888888888888888888888888888888888888888888888
    
    
    if(isset($_GET['Item_ID'])&&($_GET['Item_ID']!='') && isset($_GET['Guarantor_Name']) && ($_GET['Guarantor_Name'] != '')){
        $Item_ID = $_GET['Item_ID'];
	$Billing_Type = $_GET['Billing_Type'];
	$Guarantor_Name = $_GET['Guarantor_Name'];
	
        if($Billing_Type=='Outpatient Credit'||$Billing_Type=='Inpatient Credit'){
            if(strtolower($Guarantor_Name)=='nhif'){
            $Select_Price = "select Selling_Price_NHIF as price from tbl_items i
                                    where i.Item_ID = '$Item_ID' ";
            }else{
                $Select_Price = "select Selling_Price_Credit as price from tbl_items i
                                    where i.Item_ID = '$Item_ID' ";
            }
        }elseif($Billing_Type=='Outpatient Cash'||$Billing_Type=='Inpatient Cash'){
            $Select_Price = "select Selling_Price_Cash as price from tbl_items i
                                    where i.Item_ID = '$Item_ID' ";
        }
        $result = @mysqli_query($conn,$Select_Price);
        $row = @mysqli_fetch_assoc($result);
        //echo number_format($row['price']);
        $Price = $row['price'];
    }
    
    
    //8888888888888888888888888888888888888888888888888888
    
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
    if($Employee_ID != 0 && $Registration_ID != 0 && $Item_ID != 0 && $Type_Of_Check_In != '' && $direction != '' && $Quantity != '' && $Branch_ID != 0 && $Employee_ID != 0 && $Billing_Type != ''){
        
        //check if there is another record based on selected employee and patient
        //if found we delete them before continue with selected patient
        $select = "select * from tbl_reception_items_list_cache_others
                    where Employee_ID = '$Employee_ID' and Registration_ID <> '$Registration_ID'";
        $Transaction_Details = mysqli_query($conn,$select) or die(mysqli_error($conn));
        $no = mysqli_num_rows($Transaction_Details);
        if($no > 0){
            //delete them
            $delete_details = mysqli_query($conn,"delete from tbl_reception_items_list_cache_others where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
            if($delete_details){
                //insert data to tbl_reception_items_list_cache_others
                
                if(strtolower($direction) == 'direct to doctor' || strtolower($direction) == 'direct to doctor via nurse station'){
                    $insert_data = mysqli_query($conn,"insert into tbl_reception_items_list_cache_others(
                                                Check_In_Type,Item_ID,Discount,
                                                    Price,Quantity,Patient_Direction,
                                                        Consultant,Consultant_ID,Employee_ID,Registration_ID,Billing_Type,
                                                        Sponsor_Name,Sponsor_ID,Claim_Form_Number)
                                            values('$Type_Of_Check_In','$Item_ID','$Discount',
                                                '$Price','$Quantity','$direction',
                                                    '$Consultant',(select Employee_ID from tbl_Employee where Employee_Name = '$Consultant'),'$Employee_ID','$Registration_ID',
                                                    '$Billing_Type','$Guarantor_Name','$Sponsor_ID','$Claim_Form_Number')") or die(mysqli_error($conn));
                }elseif(strtolower($direction) == 'direct to clinic' || strtolower($direction) == 'direct to clinic via nurse station'){
                    $insert_data = mysqli_query($conn,"insert into tbl_reception_items_list_cache_others(
                                                Check_In_Type,Item_ID,Discount,
                                                    Price,Quantity,Patient_Direction,
                                                        Consultant,Consultant_ID,Employee_ID,Registration_ID,Billing_Type,
                                                        Sponsor_Name,Sponsor_ID,Claim_Form_Number)
                                            values('$Type_Of_Check_In','$Item_ID','$Discount',
                                                '$Price','$Quantity','$direction',
                                                    '$Consultant',(select Clinic_ID from tbl_clinic where Clinic_Name = '$Consultant'),'$Employee_ID','$Registration_ID',
                                                    '$Billing_Type','$Guarantor_Name','$Sponsor_ID','$Claim_Form_Number')") or die(mysqli_error($conn));
                }
            }
        }else{
            if(strtolower($direction) == 'direct to doctor' || strtolower($direction) == 'direct to doctor via nurse station'){
                    $insert_data = mysqli_query($conn,"insert into tbl_reception_items_list_cache_others(
                                                Check_In_Type,Item_ID,Discount,
                                                    Price,Quantity,Patient_Direction,
                                                        Consultant,Consultant_ID,Employee_ID,Registration_ID,Billing_Type,
                                                        Sponsor_Name,Sponsor_ID,Claim_Form_Number)
                                            values('$Type_Of_Check_In','$Item_ID','$Discount',
                                                '$Price','$Quantity','$direction',
                                                    '$Consultant',(select Employee_ID from tbl_Employee where Employee_Name = '$Consultant'),'$Employee_ID','$Registration_ID',
                                                    '$Billing_Type','$Guarantor_Name','$Sponsor_ID','$Claim_Form_Number')") or die(mysqli_error($conn));
                }elseif(strtolower($direction) == 'direct to clinic' || strtolower($direction) == 'direct to clinic via nurse station'){
                    $insert_data = mysqli_query($conn,"insert into tbl_reception_items_list_cache_others(
                                                Check_In_Type,Item_ID,Discount,
                                                    Price,Quantity,Patient_Direction,
                                                        Consultant,Consultant_ID,Employee_ID,Registration_ID,Billing_Type,
                                                        Sponsor_Name,Sponsor_ID,Claim_Form_Number)
                                            values('$Type_Of_Check_In','$Item_ID','$Discount',
                                                '$Price','$Quantity','$direction',
                                                    '$Consultant',(select Clinic_ID from tbl_clinic where Clinic_Name = '$Consultant'),'$Employee_ID','$Registration_ID',
                                                    '$Billing_Type','$Guarantor_Name','$Sponsor_ID','$Claim_Form_Number')") or die(mysqli_error($conn));
                }
        }
    }
    ?>
    <fieldset style='overflow-y: scroll; height: 200px;'>
    <?php
    $total = 0;
    $temp = 1;
    echo '<table width =100%>';
        echo "<tr id='thead'><td style='width: 3%;'><b>Sn</b></td><td style='width: 10%;'><b>Check in type</b></td>";
            echo '<td style="width: 20%;"><b>Location</b></td>
                <td style="width: 28%;"><b>Item description</b></td>
                    <td style="text-align:right; width: 8%;"><b>Price</b></td>
                        <td style="text-align:right; width: 8%;"><b>Discount</b></td>
                            <td style="text-align:right; width: 8%;"><b>Quantity</b></td>
                                <td style="text-align:right; width: 8%;"><b>Sub total</b></td><td width=4%><b>Remove</b></td></tr>';
                                
    $select_Transaction_Items = mysqli_query($conn,
        "select Reception_List_Item_ID, Check_In_Type, Patient_Direction, Product_Name, Price, Discount, Quantity,Registration_ID
            from tbl_items t, tbl_reception_items_list_cache_others alc
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
                <td style='text-align: right; width: 70%;'><h4>Total : <?php echo number_format($total); ?></h4></td>
                <td style='text-align: right; width: 30%;'>
                    <input type='button' value='Save Information' class='art-button-green' onclick='Patient_Billing_Reception_Generate_Receipt(<?php echo $Registration_ID; ?>)'>
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