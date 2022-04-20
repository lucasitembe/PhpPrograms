<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }
    
    if(isset($_GET['Quantity_Disposed'])){
        $Quantity_Disposed = $_GET['Quantity_Disposed'];
    }else{
        $Quantity_Disposed = 0;
    }
    if(isset($_GET['Item_Remark'])){
        $Item_Remark = $_GET['Item_Remark'];
    }else{
        $Item_Remark = '';
    }
    
    if(isset($_GET['Disposed_Location'])){
        $Disposed_Location = $_GET['Disposed_Location'];
    }else{
        $Disposed_Location = 0;
    }
    
    if(isset($_GET['Disposal_Description'])){
        $Disposal_Description = $_GET['Disposal_Description'];
    }else{
        $Disposal_Description = '';
    }
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    //get branch id
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }
    
    if(isset($_SESSION['Storage_Info']['Sub_Department_ID'])){
        $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = 0;
    }
    
    //echo $Quantity_Disposed."  , ".$Item_ID; exit;
    if($Item_ID != 0 && $Item_ID != '' && $Sub_Department_ID != '' && $Quantity_Disposed != 0){
        //check if there is pending Disposal based on current location
        $select_details = mysqli_query($conn,"select * from tbl_disposal
                                        where Sub_Department_ID = '$Sub_Department_ID'
                                            and Disposal_Status = 'pending' and
                                                Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select_details);
            if($num > 0){
                while($row = mysqli_fetch_array($select_details)){
                    $Disposal_ID = $row['Disposal_ID'];
                }
                $_SESSION['Disposal_ID'] = $Disposal_ID;
                $insert_data2 = mysqli_query($conn,"insert into tbl_disposal_items(
                                                        Item_ID,Quantity_Disposed,Item_Remark,Disposal_ID)                                                            
                                                    values('$Item_ID','$Quantity_Disposed','$Item_Remark','$Disposal_ID')") or die(mysqli_error($conn));
                        if($insert_data2){
                                echo '<center><table width = 100% border=0>';
                                echo '<tr><td width=4% style="text-align: center;">Sn</td>
                                        <td width=40%>Item Name</td>
                                            <td width=13% style="text-align: center;">Quantity Disposed</td>
                                                    <td width=25% style="text-align: center;">Remark</td>
                                                            <td style="text-align: center; width: 7%;">Remove</td></tr>';
                                
                                
                                $select_Disposed_Items = mysqli_query($conn,"select di.Disposal_Item_ID, itm.Product_Name, di.Quantity_Disposed, di.Item_Remark
                                                                        from tbl_disposal_items di, tbl_items itm where
                                                                            itm.Item_ID = di.Item_ID and
                                                                                di.Disposal_ID = '$Disposal_ID'") or die(mysqli_error($conn)); 
                            
                                $Temp = 1;
                                while($row = mysqli_fetch_array($select_Disposed_Items)){ 
                                    echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                                    echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
                                    echo "<td><input type='text' value='".$row['Quantity_Disposed']."' style='text-align: center;'></td>";
                                    echo "<td><input type='text' value='".$row['Item_Remark']."'></td>";
                                ?>
                                    <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Disposal_Item_ID']; ?>)'></td>
                                <?php
                                    echo "</tr>";
                                    $Temp++;
                                }
                                echo '</table>';
                        }
            }else{
                //insert data as a new Disposal
                $insert_data = mysqli_query($conn,"insert into tbl_disposal(
                                            Sub_Department_ID,Employee_ID,Created_Date,Created_Date_And_Time,
                                                Disposed_Date,Disposal_Description,Branch_ID)
                                            
                                            values('$Sub_Department_ID','$Employee_ID',(select now()),(select now()),(select now()),
                                                '$Disposal_Description','$Branch_ID')") or die(mysqli_error($conn));
                
                if($insert_data){
                    //select the current Disposal id
                    $select_Disposal_ID = mysqli_query($conn,"select Disposal_ID from tbl_disposal
                                                            where Employee_ID = '$Employee_ID' and
                                                                Sub_Department_ID = '$Sub_Department_ID' and
                                                                    Disposal_Status = 'pending' Order by Disposal_ID desc limit 1") or die(mysqli_error($conn));
                    $no = mysqli_num_rows($select_Disposal_ID);
                    if($no > 0 ){
                        while($row = mysqli_fetch_array($select_Disposal_ID)){
                            $Disposal_ID = $row['Disposal_ID'];
                            $_SESSION['Disposal_ID'] = $row['Disposal_ID'];
                        }
                        $_SESSION['Disposal_ID'] = $Disposal_ID;
                    }else{
                        $Disposal_ID = 0;
                    }
                    
                    if($Disposal_ID != 0){
                        //insert item into tbl_disposal_items table
                        $insert_data2 = mysqli_query($conn,"insert into tbl_disposal_items(
                                                        Item_ID,Quantity_Disposed,Item_Remark,Disposal_ID)                                                            
                                                            values('$Item_ID','$Quantity_Disposed','$Item_Remark','$Disposal_ID')") or die(mysqli_error($conn));
                        if($insert_data2){
                                echo '<center><table width = 100% border=0>';
                                echo '<tr><td width=4% style="text-align: center;">Sn</td>
                                        <td width=40%>Item Name</td>
                                            <td width=13% style="text-align: center;">Quantity Disposed</td>
                                                    <td width=25% style="text-align: center;">Remark</td>
                                                            <td style="text-align: center; width: 7%;">Remove</td></tr>';
                                
                                
                                $select_Disposed_Items = mysqli_query($conn,"select di.Disposal_Item_ID, itm.Product_Name, di.Quantity_Disposed, di.Item_Remark
                                                                        from tbl_disposal_items di, tbl_items itm where
                                                                            itm.Item_ID = di.Item_ID and
                                                                                di.Disposal_ID = '$Disposal_ID'") or die(mysqli_error($conn)); 
                            
                                $Temp = 1;
                                while($row = mysqli_fetch_array($select_Disposed_Items)){ 
                                    echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                                    echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
                                    echo "<td><input type='text' value='".$row['Quantity_Disposed']."' style='text-align: center;'></td>";
                                    echo "<td><input type='text' value='".$row['Item_Remark']."'></td>";
                                ?>
                                    <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Disposal_Item_ID']; ?>)'></td>
                                <?php
                                    echo "</tr>";
                                    $Temp++;
                                }
                                echo '</table>';
                        }
                        
                    }
                }
            }
    }
?>