<?php
    session_start();
    include("./includes/connection.php");

    include_once("./functions/database.php");
    
    //get all submitted values
    if(isset($_GET['Item_Category_ID'])){
        $Item_Category_ID = mysqli_real_escape_string($conn,$_GET['Item_Category_ID']);
    }else{
        $Item_Category_ID = 0;
    }

    //get all submitted values
    if(isset($_GET['Classification'])){
        $Classification = mysqli_real_escape_string($conn,$_GET['Classification']);
    }else{
        $Classification = 0;
    }
    
    if(isset($_GET['Search_Value'])){
        $Search_Value = mysqli_real_escape_string($conn,$_GET['Search_Value']);
    }else{
        $Search_Value = '';
    }
    
    if(isset($_SESSION['Storage'])){
        $Sub_Department_Name = $_SESSION['Storage'];
    }else{
        $Sub_Department_Name = '';
    }
    if(isset($_SESSION['Storage_Info']['Sub_Department_ID'])){
        $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = '';
    }
    
    //echo $Item_Category_ID." , ".$Search_Value." , ".$Sub_Department_Name; 
    
    if(isset($_GET['FilterCategory'])){
        if(strtolower($Classification) == "all"){
            $sql_select = mysqli_query($conn,"select i.Last_Buy_Price, ib.Item_Balance, i.Product_Name
                                    from tbl_items i,tbl_items_balance ib
                                    where i.Item_ID = ib.Item_ID and
                                    ib.Sub_Department_ID = {$Sub_Department_ID} and
                                    i.Can_Be_Stocked = 'yes'
                                    order by Product_Name") or die(mysqli_error($conn));
        }else{
            $sql_select = mysqli_query($conn,"select i.Last_Buy_Price, ib.Item_Balance, i.Product_Name
                                    from tbl_items i,tbl_items_balance ib
                                    where i.Item_ID = ib.Item_ID and
                                    ib.Sub_Department_ID = {$Sub_Department_ID} and
                                    i.Can_Be_Stocked = 'yes' and
                                    i.Classification = '{$Classification}'
                                    order by Product_Name") or die(mysqli_error($conn));
        }
    }else{
        if(strtolower($Classification) == "all"){
            $Product_Name_Search = Prepare_For_Like_Operator($Search_Value);
            $sql_select = mysqli_query($conn,"select i.Last_Buy_Price, ib.Item_Balance, i.Product_Name
                                        from tbl_items i,tbl_items_balance ib
                                        where i.Item_ID = ib.Item_ID and
                                        ib.Sub_Department_ID = {$Sub_Department_ID} and
                                        i.Can_Be_Stocked = 'yes' and
                                        i.Product_Name like '{$Product_Name_Search}'
                                        order by Product_Name") or die(mysqli_error($conn));
        }else{
            $Product_Name_Search = Prepare_For_Like_Operator($Search_Value);
            $sql_select = mysqli_query($conn,"select i.Last_Buy_Price, ib.Item_Balance, i.Product_Name
                                        from tbl_items i,tbl_items_balance ib
                                        where i.Item_ID = ib.Item_ID and
                                        ib.Sub_Department_ID = {$Sub_Department_ID} and
                                        i.Can_Be_Stocked = 'yes' and
                                        i.Product_Name like '{$Product_Name_Search}' and
                                        i.Classification = '{$Classification}'
                                        order by Product_Name") or die(mysqli_error($conn));
        }
    }
?>
    <table width='100%'   border=0 id='Items_Fieldset'>
    <?php
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
        $temp = 1;
        while($row = mysqli_fetch_array($sql_select)){
            echo "<tr><td >".$temp."<b>.</b></td>";
            echo "<td >".$row['Product_Name']."</td>";
            echo "<td style='text-align: right;'>".number_format($row['Last_Buy_Price'])."&nbsp;&nbsp;&nbsp;</td>";
            echo "<td style='text-align: right;'>".number_format($row['Item_Balance'])."&nbsp;&nbsp;&nbsp;</td>";
            
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