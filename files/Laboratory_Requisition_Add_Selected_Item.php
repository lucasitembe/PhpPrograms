<?php
    @session_start();
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
    if(isset($_GET['Store_Issue'])){
        $Store_Issue = $_GET['Store_Issue'];
    }else{
        $Store_Issue = 0;
    }

    if(isset($_GET['Store_Need'])){
        $Store_Need = $_GET['Store_Need'];
    }else{
        $Store_Need = '';
    }

    if(isset($_GET['Requisition_Description'])){
        $Requisition_Description = $_GET['Requisition_Description'];
    }else{
        $Requisition_Description = '';
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


    if($Item_ID != 0 && $Item_ID != '' && $Store_Need != '' && $Quantity != 0 && $Store_Issue != 0){
        //check if there is pending requisition based on store need & store issue
        $select_details = mysqli_query($conn,"select * from tbl_requisition
                                        where Store_Need = (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Store_Need' limit 1)
                                            and Store_Issue = '$Store_Issue' and
                                                Employee_ID = '$Employee_ID' and
                                                    Requisition_Status = 'pending'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select_details);
            if($num > 0){
                while($row = mysqli_fetch_array($select_details)){
                    $Requisition_ID = $row['Requisition_ID'];
					$_SESSION['Laboratory_Requisition_ID'] = $Requisition_ID;
                }
                $_SESSION['Laboratory_Requisition_ID'] = $Requisition_ID;
                $insert_data2 = mysqli_query($conn,"insert into tbl_requisition_items(
                                                        Requisition_ID,Quantity_Required,Item_Remark,Item_ID)

                                                    values('$Requisition_ID','$Quantity','$Item_Remark','$Item_ID')") or die(mysqli_error($conn));
                        if($insert_data2){
                                echo '<center><table width = 100% border=0>';
                                echo '<tr><td width=4% style="text-align: center;"><b>Sn</b></td>
                                            <td><b>Item Name</b></td>
                                                <td width=7% style="text-align: center;"><b>Quantity</b></td>
                                                    <td width=25% style="text-align: center;"><b>Remark</b></td><td><b>Remove</b></td></tr>';


                                $select_Transaction_Items = mysqli_query($conn,"select itm.Product_Name, rqi.Quantity_Required, rqi.Item_Remark, rqi.Requisition_Item_ID
                                                                        from tbl_requisition_items rqi, tbl_items itm where
                                                                            itm.Item_ID = rqi.Item_ID and
                                                                                rqi.Requisition_ID ='$Requisition_ID'") or die(mysqli_error($conn));

                                $Temp=1;
                                while($row = mysqli_fetch_array($select_Transaction_Items)){
                                    echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                                    echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
                                    echo "<td><input type='text' value='".$row['Quantity_Required']."' style='text-align: center;'></td>";
                                    echo "<td><input type='text' value='".$row['Item_Remark']."'></td>";
                                ?>
                                    <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Requisition_Item_ID']; ?>)'></td>
                                <?php
                                    echo "</tr>";
                                    $Temp++;
                                }
                                echo '</table>';
                        }
            }else{
                //insert data as a new requisition
                $insert_data = mysqli_query($conn,"insert into tbl_requisition(
                                            Requisition_Description,Created_Date_Time,Created_Date,
                                                Store_Need,Store_Issue,Employee_ID,Branch_ID)

                                            values('$Requisition_Description',(select now()),(select now()),
                                                (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Store_Need' limit 1),
                                                    '$Store_Issue','$Employee_ID','$Branch_ID')") or die(mysqli_error($conn));

                if($insert_data){
                    //select the current requisition id
                    $select_Requisition_ID = mysqli_query($conn,"select Requisition_ID from tbl_requisition
                                                            where Employee_ID = '$Employee_ID' and
                                                                Store_Issue = '$Store_Issue' and
                                                                    Store_Need = (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Store_Need' limit 1) and
                                                                        Requisition_Status = 'pending' order by Requisition_ID desc limit 1") or die(mysqli_error($conn));
                    $no = mysqli_num_rows($select_Requisition_ID);
                    if($no > 0 ){
                        while($row = mysqli_fetch_array($select_Requisition_ID)){
                            $Requisition_ID = $row['Requisition_ID'];
                            $_SESSION['Laboratory_Requisition_ID'] = $row['Requisition_ID'];
                        }
                        $_SESSION['Laboratory_Requisition_ID'] = $Requisition_ID;
                    }else{
                        $Requisition_ID = 0;
                    }

                    if($Requisition_ID != 0){
                        //insert item into tbl_requisition_items table
                        $insert_data2 = mysqli_query($conn,"insert into tbl_requisition_items(
                                                        Requisition_ID,Quantity_Required,Item_Remark,Item_ID)

                                                    values('$Requisition_ID','$Quantity','$Item_Remark','$Item_ID')") or die(mysqli_error($conn));
                        if($insert_data2){
                                echo '<center><table width = 100% border=0>';
                                echo '<tr><td width=4% style="text-align: center;"><b>Sn</b></td>
                                            <td><b>Item Name</b></td>
                                                <td width=7% style="text-align: center;"><b>Quantity</b></td>
                                                        <td width=25% style="text-align: center;"><b>Remark</b></td><td><b>Remove</b></td></tr>';


                                $select_Transaction_Items = mysqli_query($conn,"select itm.Product_Name, rqi.Quantity_Required, rqi.Item_Remark, rqi.Requisition_Item_ID
                                                                        from tbl_requisition_items rqi, tbl_items itm where
                                                                            itm.Item_ID = rqi.Item_ID and
                                                                                rqi.Requisition_ID ='$Requisition_ID'") or die(mysqli_error($conn));

                                $Temp=1;
                                while($row = mysqli_fetch_array($select_Transaction_Items)){
                                    echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                                    echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
                                    echo "<td><input type='text' value='".$row['Quantity_Required']."' style='text-align: center;'></td>";
                                    echo "<td><input type='text' value='".$row['Item_Remark']."'></td>";
                                ?>
                                    <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Requisition_Item_ID']; ?>)'></td>
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