<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = '';
	}

	if(isset($_GET['Return_Quantity'])){
        $Return_Quantity = $_GET['Return_Quantity'];
	}else{
        $Return_Quantity = '';
	}

	if(isset($_GET['Item_Remark'])){
		$Item_Remark = $_GET['Item_Remark'];
	}else{
		$Item_Remark = '';
	}


	if(isset($_GET['Store_Receiving'])){
        $Store_Receiving = $_GET['Store_Receiving'];
	}else{
        $Store_Receiving = '';
	}


	if(isset($_GET['Returned_From'])){
        $Returned_From = $_GET['Returned_From'];
	}else{
        $Returned_From = '';
	}

	//get employee id
	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}
	
	if($Item_ID != 0 && $Item_ID != '' && $Store_Receiving != '' && $Returned_From != '' && $Return_Quantity != 0){
        //check if there is pending inward based on sub department id selected
        $select_details = mysqli_query($conn," SELECT * FROM tbl_return_inward
                                        WHERE Store_Sub_Department_ID = '$Store_Receiving' AND
                                        Return_Sub_Department_ID = '$Returned_From' AND
                                        Employee_ID = '$Employee_ID' AND
                                        Inward_Status = 'pending'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select_details);
        if($num > 0){
            while($row = mysqli_fetch_array($select_details)){
                $Inward_ID = $row['Inward_ID'];
            }
            $_SESSION['General_Inward_ID'] = $Inward_ID;

            $insert_data2 = mysqli_query($conn,"INSERT INTO
                                            tbl_return_inward_items(Inward_ID,Quantity_Returned,Item_Remark,Item_ID)
                                         VALUES
                                            ('$Inward_ID','$Return_Quantity','$Item_Remark','$Item_ID')") or die(mysqli_error($conn));
            if($insert_data2){
                echo '<center><table width = 100% border=0>';
                echo '<tr><td width=4% style="text-align: center; background-color:silver;color:black">Sn</td>
                            <td style="background-color:silver;color:black">Item Name</td>
                            <td width=10% style="text-align: center;">UoM</td>
                            <td width=10% style="text-align: center;">Qty Returned</td>
                            <td width=25% style="text-align: center;">Remark</td>
                            <td style="text-align: center;" width="7%">Remove</td></tr>';


                $select_Transaction_Items = mysqli_query($conn,"SELECT
                                                            rii.Inward_Item_ID, rii.Inward_ID, itm.Product_Name,
                                                            rii.Quantity_Returned, rii.Item_Remark, itm.Unit_Of_Measure
                                                         FROM
                                                            tbl_return_inward_items rii, tbl_items itm
                                                         WHERE
                                                            itm.Item_ID = rii.Item_ID AND
                                                            rii.Inward_ID ='$Inward_ID'") or die(mysqli_error($conn));
                $nmz = mysqli_num_rows($select_Transaction_Items);
                if($nmz > 0){
                    $Temp=1;
                    while($row = mysqli_fetch_array($select_Transaction_Items)){
                        echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                        echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
                        echo "<td><input type='text' readonly='readonly' value='".$row['Unit_Of_Measure']."' style='text-align: center;'></td>";
                        echo "<td><input type='text' readonly='readonly' value='".$row['Quantity_Returned']."' style='text-align: center;'></td>";
                        echo "<td><input type='text' id='Item_Remark_Saved' name='Item_Remark_Saved' value='".$row['Item_Remark'].
                            "' onclick='Update_Item_Remark(".$row['Inward_ID'].",this.value)' onkeyup='Update_Item_Remark("
                            .$row['Inward_ID'].",this.value)'></td>";
                        ?>
                        <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'
                                            onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Inward_Item_ID']; ?>)'>
                        </td>
                    <?php
                        echo "</tr>";
                        $Temp++;
                    }
                }
                echo '</table>';
            }
        }else{
            //insert data as a new outward
            $insert_data = mysqli_query($conn,"INSERT INTO
                                            tbl_return_inward(Inward_Date,Store_Sub_Department_ID,Return_Sub_Department_ID,Employee_ID)
                                        VALUES
                                            ((SELECT now()), '$Store_Receiving', '$Returned_From', '$Employee_ID')") or die(mysqli_error($conn));

            if($insert_data){
                //select the current outward id
                $select_Inward_ID = mysqli_query($conn,"SELECT
                                                      Inward_ID
                                                  FROM
                                                      tbl_return_inward
                                                  WHERE
                                                      Store_Sub_Department_ID = '$Store_Receiving' AND
                                                      Return_Sub_Department_ID = '$Returned_From' AND
                                                      Employee_ID = '$Employee_ID' AND
                                                      Inward_Status = 'pending'
                                                  ORDER BY Inward_ID DESC
                                                  LIMIT 1") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select_Inward_ID);
                if($no > 0 ){
                    while($row = mysqli_fetch_array($select_Inward_ID)){
                        $Inward_ID = $row['Inward_ID'];
                    }
                    $_SESSION['General_Inward_ID'] = $Inward_ID;
                }else{
                    $Inward_ID = 0;
                }

                if($Inward_ID != 0){
                    //insert item into tbl_return_inward_items table
                    $insert_data2 = mysqli_query($conn,"INSERT INTO
                                                    tbl_return_inward_items(Inward_ID,Quantity_Returned,Item_Remark,Item_ID)
                                                 VALUES
                                                    ('$Inward_ID','$Return_Quantity','$Item_Remark','$Item_ID')") or die(mysqli_error($conn));
                    if($insert_data2){
                            echo '<center><table width = 100% border=0>';
                            echo '<tr><td width=4% style="text-align: center; background-color:silver;color:black">Sn</td>
                                        <td style="background-color:silver;color:black">Item Name</td>
                                        <td width=10% style="text-align: center;">UoM</td>
                                        <td width=10% style="text-align: center;">Qty Returned</td>
                                        <td width=18% style="text-align: left;">Remark</td>
                                        <td style="text-align: center;" width="7%">Remove</td></tr>';


                            $select_Transaction_Items = mysqli_query($conn,"SELECT
                                                                        rii.Inward_Item_ID, rii.Inward_ID, itm.Product_Name,
                                                                        rii.Quantity_Returned, rii.Item_Remark, itm.Unit_Of_Measure
                                                                     FROM
                                                                        tbl_return_inward_items rii, tbl_items itm
                                                                     WHERE
                                                                        itm.Item_ID = rii.Item_ID AND
                                                                        rii.Inward_ID ='$Inward_ID'") or die(mysqli_error($conn));
                            $nm = mysqli_num_rows($select_Transaction_Items);
                            if($nm > 0){
                                $Temp=1;
                                while($row = mysqli_fetch_array($select_Transaction_Items)){
                                    echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                                        echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
                                        echo "<td><input type='text' readonly='readonly' value='".$row['Unit_Of_Measure']."' style='text-align: center;'></td>";
                                        echo "<td><input type='text' readonly='readonly' value='".$row['Quantity_Returned']."' style='text-align: center;'></td>";
                                        echo "<td><input type='text' id='Item_Remark_Saved' name='Item_Remark_Saved' value='".$row['Item_Remark'].
                                             "' onclick='Update_Item_Remark(".$row['Inward_ID'].",this.value)' onkeyup='Update_Item_Remark("
                                             .$row['Inward_ID'].",this.value)'></td>";
                                    ?>
                                        <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'
                                                onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Inward_Item_ID']; ?>)'>
                                        </td>
                                    <?php
                                        echo "</tr>";
                                        $Temp++;
                                }
                            }
                            echo '</table>';
                    }

                }
            }

        }
    }

?>