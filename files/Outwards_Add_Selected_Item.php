<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = '';
	}


	if(isset($_GET['Quantity'])){
		$Quantity = $_GET['Quantity'];
	}else{
		$Quantity = '';
	}


	if(isset($_GET['Item_Remark'])){
		$Item_Remark = $_GET['Item_Remark'];
	}else{
		$Item_Remark = '';
	}


	if(isset($_GET['Sub_Department_ID'])){
		$Sub_Department_ID = $_GET['Sub_Department_ID'];
	}else{
		$Sub_Department_ID = '';
	}


	if(isset($_GET['Supplier_ID'])){
		$Supplier_ID = $_GET['Supplier_ID'];
	}else{
		$Supplier_ID = '';
	}

	//get employee id
	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}
	
	if($Item_ID != 0 && $Item_ID != '' && $Sub_Department_ID != '' && $Quantity != 0){
        //check if there is pending outward based on sub department id selected
        $select_details = mysqli_query($conn,"select * from tbl_return_outward
                                        where Sub_Department_ID = '$Sub_Department_ID' and
                                        Employee_ID = '$Employee_ID' and
                                        Outward_Status = 'pending'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select_details);
            if($num > 0){
                while($row = mysqli_fetch_array($select_details)){
                    $Outward_ID = $row['Outward_ID'];
                }
                $_SESSION['General_Outward_ID'] = $Outward_ID;
                $insert_data2 = mysqli_query($conn,"insert into tbl_return_outward_items(
                                                Outward_ID,Quantity_Returned,Item_Remark,Item_ID)
                                                values('$Outward_ID','$Quantity','$Item_Remark','$Item_ID')") or die(mysqli_error($conn));
                        if($insert_data2){
                                echo '<center><table width = 100% border=0>';
                                echo '<tr><td width=4% style="text-align: center; background-color:silver;color:black">Sn</td>
										    <td style="background-color:silver;color:black">Item Name</td>
											<td width=10% style="text-align: center;">UoM</td>
											<td width=10% style="text-align: center;">Qty Returned</td>
											<td width=25% style="text-align: center;">Remark</td>
											<td style="text-align: center;" width="7%">Remove</td></tr>';
                                
                                
                                $select_Transaction_Items = mysqli_query($conn,"select roi.Outward_Item_ID, roi.Outward_ID, itm.Product_Name, roi.Quantity_Returned, roi.Item_Remark, itm.Unit_Of_Measure
																			from tbl_return_outward_items roi, tbl_items itm where
																			itm.Item_ID = roi.Item_ID and
																			roi.Outward_ID ='$Outward_ID'") or die(mysqli_error($conn)); 
                            	$nmz = mysqli_num_rows($select_Transaction_Items);
                            	if($nmz > 0){
	                                $Temp=1;
	                                while($row = mysqli_fetch_array($select_Transaction_Items)){
	                                    echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
	                                    echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
	                                    echo "<td><input type='text' value='".$row['Unit_Of_Measure']."' style='text-align: center;'></td>";
	                                    echo "<td><input type='text' value='".$row['Quantity_Returned']."' style='text-align: center;'></td>";
	                                    echo "<td><input type='text' value='".$row['Item_Remark']."' readonly='readonly'></td>";
	                                ?>
	                                    <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Outward_Item_ID']; ?>)'></td>
	                                <?php
	                                    echo "</tr>";
	                                    $Temp++;
	                                }
	                            }
                                echo '</table>';
                        }
            }else{
                //insert data as a new outward
                $insert_data = mysqli_query($conn,"insert into tbl_return_outward(Outward_Date,Sub_Department_ID,Supplier_ID,Employee_ID)
                                            values((select now()),'$Sub_Department_ID','$Supplier_ID','$Employee_ID')") or die(mysqli_error($conn));
                
                if($insert_data){
                    //select the current outward id
                    $select_Outward_ID = mysqli_query($conn,"select Outward_ID from tbl_return_outward
                                                            where Employee_ID = '$Employee_ID' and
                                                            Sub_Department_ID = '$Sub_Department_ID' and
                                                            Outward_Status = 'pending' order by Outward_ID desc limit 1") or die(mysqli_error($conn));
                    $no = mysqli_num_rows($select_Outward_ID);
                    if($no > 0 ){
                        while($row = mysqli_fetch_array($select_Outward_ID)){
                            $Outward_ID = $row['Outward_ID'];
                        }
                        $_SESSION['General_Outward_ID'] = $Outward_ID;
                    }else{
                        $Outward_ID = 0;
                    }
                    
                    if($Outward_ID != 0){
                        //insert item into tbl_return_outward_items table
                        $insert_data2 = mysqli_query($conn,"insert into tbl_return_outward_items(
	                                                Outward_ID,Quantity_Returned,Item_Remark,Item_ID)
	                                                values('$Outward_ID','$Quantity','$Item_Remark','$Item_ID')") or die(mysqli_error($conn));
                        if($insert_data2){
                                echo '<center><table width = 100% border=0>';
                                echo '<tr><td width=4% style="text-align: center; background-color:silver;color:black">Sn</td>
										    <td style="background-color:silver;color:black">Item Name</td>
											<td width=10% style="text-align: center;">UoM</td>
											<td width=10% style="text-align: center;">Qty Returned</td>
											<td width=18% style="text-align: left;">Remark</td>
											<td style="text-align: center;" width="7%">Remove</td></tr>';

                                
                                $select_Transaction_Items = mysqli_query($conn,"select roi.Outward_Item_ID, roi.Outward_ID, itm.Product_Name, roi.Quantity_Returned, roi.Item_Remark, itm.Unit_Of_Measure
																				from tbl_return_outward_items roi, tbl_items itm where
																				itm.Item_ID = roi.Item_ID and
																				roi.Outward_ID ='$Outward_ID'") or die(mysqli_error($conn)); 
								$nm = mysqli_num_rows($select_Transaction_Items);
								if($nm > 0){
	                                $Temp=1;
	                                while($row = mysqli_fetch_array($select_Transaction_Items)){
	                                    echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
											echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
											echo "<td><input type='text' value='".$row['Unit_Of_Measure']."' style='text-align: center;' readonly='readonly'></td>";
											echo "<td><input type='text' value='".$row['Quantity_Returned']."' style='text-align: center;'></td>";
											echo "<td><input type='text' id='Item_Remark_Seved' name='Item_Remark_Seved' value='".$row['Item_Remark']."' onclick='Update_Item_Remark(".$row['Outward_ID'].",this.value)' onkeyup='Update_Item_Remark(".$row['Outward_ID'].",this.value)'></td>";
										?>
											<td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Outward_Item_ID']; ?>)'></td>
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