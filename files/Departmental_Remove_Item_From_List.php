<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    if(isset($_GET['Item_Cache_ID'])){
        $Item_Cache_ID = $_GET['Item_Cache_ID'];
    }else{
        $Item_Cache_ID = '';
    }
    
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    if(isset($_GET['Item_Cache_ID']) && ($_GET['Item_Cache_ID']!='') && isset($_GET['Registration_ID']) && ($_GET['Registration_ID'] != '') && $Employee_ID != 0){
        $delete = mysqli_query($conn,"delete from tbl_departmental_items_list_cache where Item_Cache_ID = '$Item_Cache_ID'") or die(mysqli_error($conn));
    }
    

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
        "select Item_Cache_ID, Product_Name, Price, Quantity,Registration_ID,Comment,Sub_Department_ID
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
        echo "<td style='text-align:right;'>".number_format($row['Price'])."</td>";
        echo "<td style='text-align:right;'>".$row['Quantity']."</td>";
        echo "<td style='text-align:right;'>".number_format($row['Price'] * $row['Quantity'])."</td>";
    ?>
        <td style='text-align: center;'> 
            <input type='button' style='color: red; font-size: 10px;' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Item_Cache_ID']; ?>,<?php echo $row['Registration_ID']; ?>)'>
        </td>
    <?php
        $total = $total + ($row['Price'] * $row['Quantity']);
        echo "</tr>";
    }
    echo "</table>";
?>