<?php
    session_start();
    include("./includes/connection.php");
    
    //get all submitted values
    if(isset($_GET['Item_Category_ID'])){
        $Item_Category_ID = mysqli_real_escape_string($conn,$_GET['Item_Category_ID']);
    }else{
        $Item_Category_ID = 0;
    }
    
    if(isset($_GET['Search_Value'])){
        $Search_Value = mysqli_real_escape_string($conn,$_GET['Search_Value']);
    }else{
        $Search_Value = '';
    }

    //get sub department id
    if(isset($_SESSION['Pharmacy_ID'])){
	$Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
	$Sub_Department_ID = '';
    }
    
    
    //get sub department name
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
	while($row = mysqli_fetch_array($select)){
	    $Sub_Department_Name = $row['Sub_Department_Name'];
	}
    }else{
	$Sub_Department_Name = '';
    }

    
    //echo $Item_Category_ID." , ".$Search_Value." , ".$Sub_Department_Name; 
    
    if(isset($_GET['FilterCategory'])){
        if($Item_Category_ID == 0){
            $sql_select = mysqli_query($conn,"select Product_Name, Item_Balance, Last_Buy_Price from tbl_items i,tbl_items_balance ib, tbl_item_subcategory s, tbl_item_category c
                                    where i.Item_ID = ib.Item_ID and
                                    i.Item_Subcategory_ID = s.Item_Subcategory_ID and
                                    s.Item_Category_ID = c.Item_Category_ID and
                                    ib.Sub_Department_ID = '$Sub_Department_ID' and
                                    (i.Item_Type = 'Pharmacy' or i.Item_Type = 'Others') order by Product_Name") or die(mysqli_error($conn));
        }else{
            $sql_select = mysqli_query($conn,"select Product_Name, Item_Balance, Last_Buy_Price from tbl_items i,tbl_items_balance ib, tbl_item_subcategory s, tbl_item_category c
                                    where i.Item_ID = ib.Item_ID and
                                    i.Item_Subcategory_ID = s.Item_Subcategory_ID and
                                    s.Item_Category_ID = c.Item_Category_ID and
                                    ib.Sub_Department_ID = '$Sub_Department_ID' and
                                    (i.Item_Type = 'Pharmacy' or i.Item_Type = 'Others') and
                                    c.Item_Category_ID = '$Item_Category_ID' order by Product_Name") or die(mysqli_error($conn));
        }
    }else{
        if($Item_Category_ID == 0){
            if($Search_Value != '' && $Search_Value != null){
                $sql_select = mysqli_query($conn,"select Product_Name, Item_Balance, Last_Buy_Price from tbl_items i,tbl_items_balance ib, tbl_item_subcategory s, tbl_item_category c
                                            where i.Item_ID = ib.Item_ID and
                                            i.Item_Subcategory_ID = s.Item_Subcategory_ID and
                                            s.Item_Category_ID = c.Item_Category_ID and
                                            ib.Sub_Department_ID = '$Sub_Department_ID' and
                                            (i.Item_Type = 'Pharmacy' or i.Item_Type = 'Others') and
                                            i.Product_Name like '%$Search_Value%' order by i.Product_Name") or die(mysqli_error($conn));
            }else{
               $sql_select = mysqli_query($conn,"select Product_Name, Item_Balance, Last_Buy_Price from tbl_items i,tbl_items_balance ib
                                            where i.Item_ID = ib.Item_ID and
                                            ib.Sub_Department_ID = '$Sub_Department_ID' and
                                            (i.Item_Type = 'Pharmacy' or i.Item_Type = 'Others') order by Product_Name") or die(mysqli_error($conn));
            }
        }else{
            $sql_select = mysqli_query($conn,"select Product_Name, Item_Balance, Last_Buy_Price from tbl_items i,tbl_items_balance ib, tbl_item_subcategory s, tbl_item_category c
                                        where i.Item_ID = ib.Item_ID and
                                        i.Item_Subcategory_ID = s.Item_Subcategory_ID and
                                        s.Item_Category_ID = c.Item_Category_ID and
                                        ib.Sub_Department_ID = '$Sub_Department_ID' and
                                        (i.Item_Type = 'Pharmacy' or i.Item_Type = 'Others') and
                                        i.Product_Name like '%$Search_Value%' and 
                                        c.Item_Category_ID = '$Item_Category_ID' order by Product_Name") or die(mysqli_error($conn));
            
        }
    }
?>
<table width='100%'   border=0 id='Items_Fieldset'>
    <?php
        $temp = 1;
        $Grand_Stock = 0;
        $Title = '<tr><td colspan="5"><hr></td></tr>
                    <tr>
                        <td width="5%"><b>SN</b></td>
                        <td ><b>ITEM NAME</b></td>
                        <td width="10%" style="text-align: right;"><b>AVERAGE PRICE</b>&nbsp;&nbsp;&nbsp;</td>
                        <td width="10%" style="text-align: right;"><b>BALANCE</b>&nbsp;&nbsp;&nbsp;</td>
                        <td width="10%" style="text-align: right;"><b>STOCK VALUE</b>&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                    <tr><td colspan="5"><hr></td></tr>';
        echo $Title;
        while($row = mysqli_fetch_array($sql_select)){
            echo "<tr><td >".$temp."<b>.</b></td>";
            echo "<td >".$row['Product_Name']."</td>";
            echo "<td style='text-align: right;'>".number_format($row['Last_Buy_Price'])."&nbsp;&nbsp;&nbsp;</td>";
            if($row['Item_Balance'] > 0){
                echo "<td style='text-align: right;'>".$row['Item_Balance']."&nbsp;&nbsp;&nbsp;</td>";
            }else{
                echo "<td style='text-align: right;'>0&nbsp;&nbsp;&nbsp;</td>";
            }
            $Stock_Value = ($row['Item_Balance'] * $row['Last_Buy_Price']);
            if($Stock_Value > 0){
                echo "<td style='text-align: right;'>".number_format($Stock_Value)."&nbsp;&nbsp;&nbsp;</td></tr>";
                $Grand_Stock += $Stock_Value;
            }else{
                echo "<td style='text-align: right;'>0&nbsp;&nbsp;&nbsp;</td></tr>";                        
            }
            $temp++;
            if(($temp%51) == 0){
                echo $Title;
            }
        }
    ?>
    <tr><td colspan="5"><hr></td></tr>
    <tr><td colspan="4" style="text-align: right;"><b>ESTIMATED GRAND TOTAL</b></td><td style="text-align: right;"><b><?php echo number_format($Grand_Stock); ?></b>&nbsp;&nbsp;&nbsp;</td></tr>
    <tr><td colspan="5"><hr></td></tr>
</table>