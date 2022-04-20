<?php
    session_start();
    include("./includes/connection.php");
    
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
        $Classification = "";
    }
    
    if(isset($_GET['Search_Value'])){
        $Search_Value = mysqli_real_escape_string($conn,$_GET['Search_Value']);
    }else{
        $Search_Value = '';
    }
    
    //get sub department id
    if(isset($_SESSION['Storage_Info']['Sub_Department_ID'])){
        $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
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
        $sql_select = mysqli_query($conn,"select * from tbl_items i,tbl_items_balance ib
                                    where i.Item_ID = ib.Item_ID and
                                    ib.Sub_Department_ID = '{$Sub_Department_ID}' and
                                    i.Can_Be_Stocked = 'yes' and
                                    i.Classification = '$Classification' order by Product_Name") or die(mysqli_error($conn));
    }else{
        if(strtolower($Classification) == strtolower("all")){
            $sql_select = mysqli_query($conn,"select * from tbl_items i,tbl_items_balance ib
                                        where i.Item_ID = ib.Item_ID and
                                        ib.Sub_Department_ID = '{$Sub_Department_ID}' and
                                        i.Product_Name like '%$Search_Value%' and
                                        i.Can_Be_Stocked = 'yes'
                                        order by Product_Name") or die(mysqli_error($conn));
        }else{
            $sql_select = mysqli_query($conn,"select * from tbl_items i,tbl_items_balance ib
                                        where i.Item_ID = ib.Item_ID and
                                        ib.Sub_Department_ID = '{$Sub_Department_ID}' and
                                        i.Product_Name like '%$Search_Value%' and
                                        i.Can_Be_Stocked = 'yes' and
                                        i.Classification = '$Classification' order by Product_Name") or die(mysqli_error($conn));

        }
    }
    $Title = '<tr><td colspan="3"><hr></td></tr>
                <tr>
                    <td width="5%"><b>SN</b></td>
                    <td ><b>ITEM NAME</b></td>
                    <td width="10%" style="text-align: right;"><b>BALANCE&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
                </tr>
                <tr><td colspan="3"><hr></td></tr>';

    $temp = 0;
    echo $Title;
    while($row = mysqli_fetch_array($sql_select)){
        $Item_Balance = $row['Item_Balance'];

        echo "<tr><td>";
        echo "<input type='radio' id='{$row['Item_ID']}' name='Item' onclick='Get_Selected_Details(\"{$row['Product_Name']}\",{$row['Item_ID']})'></td>";
        echo "<td><label for='".$row['Item_ID']."'>".$row['Product_Name']."</label></td>";
        echo "<td style='text-align: right;'><label for='".$row['Item_ID']."'>".$Item_Balance."&nbsp;&nbsp;&nbsp;&nbsp;</label></td></tr>";
        $temp++;
        if(($temp%25) == 0){
            echo $Title;
        }
    }
?> 