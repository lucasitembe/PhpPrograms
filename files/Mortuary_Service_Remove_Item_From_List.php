<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    if(isset($_GET['Payment_Item_Cache_List_ID'])){
        $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
    }else{
        $Payment_Item_Cache_List_ID = '';
    }
    
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    if(isset($_GET['Payment_Item_Cache_List_ID']) && ($_GET['Payment_Item_Cache_List_ID']!='') && isset($_GET['Registration_ID']) && ($_GET['Registration_ID'] != '') && $Employee_ID != 0){
        $delete = mysqli_query($conn,"DELETE from tbl_item_list_cache where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    }
    

    $total = 0;
    $temp = 0;
   echo '<table width =100%>';
   echo '<tr><td colspan=8><hr></td><tr>';
   echo '<tr id="thead">
            <td style="text-align: center;" width=5%><b>Sn</b></td>
            <td><b>Medication Name</b></td>
            <td style="text-align: left;" width=25%><b>Remarks</b></td>
            <td style="text-align: right;" width=8%><b>Price</b></td>
            <td style="text-align: right;" width=8%><b>Discount</b></td>
            <td style="text-align: right;" width=8%><b>Quantity</b></td>
            <td style="text-align: right;" width=8%><b>Sub Total</b></td>
            <td style="text-align: center;" width=6%><b>Action</b></td></tr>';
    //echo '<table width =100%>';        
    echo '<tr><td colspan=8><hr></td><tr>';
	
	$select_Transaction_Items = mysqli_query($conn,
        "select Item_Cache_ID, Product_Name, Price, Quantity,Registration_ID,Dosage,Discount
            from tbl_items t, tbl_pharmacy_inpatient_items_list_cache alc
                where alc.Item_ID = t.Item_ID and
                    alc.Employee_ID = '$Employee_ID' and
                            Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    
    $no_of_items = mysqli_num_rows($select_Transaction_Items);
    while($row = mysqli_fetch_array($select_Transaction_Items)){
        echo "<tr><td>".++$temp."</td>";
        echo "<td>".$row['Product_Name']."</td>";
        echo "<td style='text-align:left;'>".$row['Dosage']."</td>";
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
        $total = $total + (($row['Price'] - $row['Discount']) * $row['Quantity']);
        echo "</tr>";
    }
    echo "</table>";
?>