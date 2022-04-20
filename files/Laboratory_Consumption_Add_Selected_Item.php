<?php
    session_start();
    include("./includes/connection.php");

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if(isset($_GET['Quantity'])){
        $Quantity = $_GET['Quantity'];
    }else{
        $Quantity = 0;
    }

    if(isset($_GET['Item_Remark'])){
        $Item_Remark = $_GET['Item_Remark'];
    }else{
        $Item_Remark = '';
    }

    if(isset($_GET['Employee_Receive'])){
        $Employee_Receive = $_GET['Employee_Receive'];
    }else{
        $Employee_Receive = 0;
    }


    if(isset($_GET['Consumption_Description'])){
        $Consumption_Description = $_GET['Consumption_Description'];
    }else{
        $Consumption_Description = '';
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

    //get sub department id
    if(isset($_SESSION['Laboratory_ID'])){
        $Sub_Department_ID = $_SESSION['Laboratory_ID'];
    }else{
        $Sub_Department_ID = 0;
    }


    if($Item_ID != 0 && $Item_ID != '' && $Employee_ID != 0 && $Quantity != 0 && $Employee_Receive != 0){
        //check if there is pending consumption based on selected employee receiver
        $select_details = mysqli_query($conn,"select * from tbl_consumption
                                        where Employee_Need = '$Employee_Receive' and
                                        Employee_ID = '$Employee_ID' and
                                        Sub_Department_ID = '$Sub_Department_ID' and
                                        Consumption_Status = 'pending'") or die(mysqli_error($conn));

        $num = mysqli_num_rows($select_details);
            if($num > 0){
                while($row = mysqli_fetch_array($select_details)){
                    $Consumption_ID = $row['Consumption_ID'];
					$_SESSION['Laboratory_Consumption_ID'] = $Consumption_ID;
                }
                $_SESSION['Laboratory_Consumption_ID'] = $Consumption_ID;
                $insert_data2 = mysqli_query($conn,"insert into tbl_consumption_items(
                                                        Consumption_ID,Quantity,Item_Remark,Item_ID)

                                                    values('$Consumption_ID','$Quantity','$Item_Remark','$Item_ID')") or die(mysqli_error($conn));
                        if($insert_data2){
                                echo '<center><table width = 100% border=0>';
                                echo '<tr><td width=4% style="text-align: center;"><b>Sn</b></td>
                                            <td><b>Item Name</b></td>
                                                <td width=7% style="text-align: center;"><b>Quantity</b></td>
                                                    <td width=25% style="text-align: center;"><b>Remark</b></td><td><b>Remove</b></td></tr>';


                                $select_Transaction_Items = mysqli_query($conn,"select itm.Product_Name, ci.Quantity, ci.Item_Remark, ci.Consumption_Item_ID
                                                                        from tbl_consumption_items ci, tbl_items itm where
                                                                            itm.Item_ID = ci.Item_ID and
                                                                                ci.Consumption_ID ='$Consumption_ID'") or die(mysqli_error($conn));

                                $Temp=1;
                                while($row = mysqli_fetch_array($select_Transaction_Items)){
                                    echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                                    echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
                                    echo "<td><input type='text' value='".$row['Quantity']."' style='text-align: center;'></td>";
                                    echo "<td><input type='text' value='".$row['Item_Remark']."'></td>";
                                ?>
                                    <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Consumption_Item_ID']; ?>)'></td>
                                <?php
                                    echo "</tr>";
                                    $Temp++;
                                }
                                echo '</table>';
                        }
            }else{
                //insert data as a new consumption
                $insert_data = mysqli_query($conn,"insert into tbl_consumption(
                                            Consumption_Description, Created_Date_Time,Created_Date,
                                                Employee_Need, Sub_Department_ID, Employee_ID, Branch_ID)

                                            values('$Consumption_Description',(select now()),(select now()),
                                                    '$Employee_Receive','$Sub_Department_ID','$Employee_ID','$Branch_ID')") or die(mysqli_error($conn));

                if($insert_data){
                    //select the current consumption id
                    $select_consumption_id = mysqli_query($conn,"select Consumption_ID from tbl_consumption
                                                            where Employee_ID = '$Employee_ID' and
                                                                Employee_Need = '$Employee_Receive' and
                                                                    Sub_Department_ID = '$Sub_Department_ID' and
                                                                        Consumption_Status = 'pending' order by Consumption_ID desc limit 1") or die(mysqli_error($conn));
                    $no = mysqli_num_rows($select_consumption_id);
                    if($no > 0 ){
                        while($row = mysqli_fetch_array($select_consumption_id)){
                            $Consumption_ID = $row['Consumption_ID'];
                            $_SESSION['Laboratory_Consumption_ID'] = $row['Consumption_ID'];
                        }
                        $_SESSION['Laboratory_Consumption_ID'] = $Consumption_ID;
                    }else{
                        $Consumption_ID = 0;
                    }

                    if($Consumption_ID != 0){
                        //insert item into tbl_consumption_items table
                        $insert_data2 = mysqli_query($conn,"insert into tbl_consumption_items(
                                                        Consumption_ID,Quantity,Item_Remark,Item_ID)

                                                    values('$Consumption_ID','$Quantity','$Item_Remark','$Item_ID')") or die(mysqli_error($conn));
                        if($insert_data2){
                                echo '<center><table width = 100% border=0>';
                                echo '<tr><td width=4% style="text-align: center;"><b>Sn</b></td>
                                            <td><b>Item Name</b></td>
                                            <td width=7% style="text-align: center;"><b>Quantity</b></td>
                                            <td width=25% style="text-align: center;"><b>Remark</b></td>
                                            <td><b>Remove</b></td></tr>';


                                $select_Transaction_Items = mysqli_query($conn,"select itm.Product_Name, ci.Quantity, ci.Item_Remark, ci.Consumption_Item_ID
                                                                        from tbl_consumption_items ci, tbl_items itm where
                                                                            itm.Item_ID = ci.Item_ID and
                                                                                ci.Consumption_ID ='$Consumption_ID'") or die(mysqli_error($conn));

                                $Temp=1;
                                while($row = mysqli_fetch_array($select_Transaction_Items)){
                                    echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                                    echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
                                    echo "<td><input type='text' value='".$row['Quantity']."' style='text-align: center;'></td>";
                                    echo "<td><input type='text' value='".$row['Item_Remark']."'></td>";
                                ?>
                                    <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Consumption_Item_ID']; ?>)'></td>
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