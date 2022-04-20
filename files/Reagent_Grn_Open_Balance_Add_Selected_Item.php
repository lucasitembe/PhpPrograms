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
    
    if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = 0;
    }
    
    
    if(isset($_GET['Grn_Description'])){
        $Grn_Description = $_GET['Grn_Description'];
    }else{
        $Grn_Description = '';
    }
    
    if(isset($_GET['Buying_Price'])){
        $Buying_Price = $_GET['Buying_Price'];
    }else{
        $Buying_Price = '';
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
    
    if($Item_ID != 0 && $Item_ID != '' && $Sub_Department_ID != '' && $Quantity != 0 && $Quantity != '' && $Sub_Department_ID != 0 && $Buying_Price != 0 && $Buying_Price != ''){
        
        
        //check if there is pending Grn Open Balance
        $select_details = mysqli_query($conn,"select * from tbl_reagent_grn_open_balance
                                        where Sub_Department_ID = '$Sub_Department_ID' and
                                            Employee_ID = '$Employee_ID' and
                                                Grn_Open_Balance_Status = 'pending'") or die('One'.mysqli_error($conn));
        $num = mysqli_num_rows($select_details);
            if($num > 0){
                while($row = mysqli_fetch_array($select_details)){
                    $Grn_Open_Balance_ID = $row['Grn_Open_Balance_ID'];
                }
                $_SESSION['Reagent_Grn_Open_Balance_ID'] = $Grn_Open_Balance_ID;
                
                //insert data into tbl_parchase_order_items table
                $insert_data2 = mysqli_query($conn,"insert into tbl_reagent_grn_open_balance_items(
							Grn_Open_Balance_ID,Item_ID,Item_Quantity,
							    Buying_Price,Item_Remark)
							
						 values('$Grn_Open_Balance_ID','$Item_ID','$Quantity',
							'$Buying_Price','$Item_Remark')") or die('Two'.mysqli_error($conn));
		
                        if($insert_data2){
                            echo '<center><table width = 100% border=0>';
                            echo '<tr><td width=4% style="text-align: center;">Sn</td>
                                        <td width=30%>Item Name</td>
                                            <td width=7% style="text-align: center;">Quantity</td>
                                                    <td width=7% style="text-align: right;">Buying Price</td>
                                                    <td width=7% style="text-align: center;">Sub Total</td>
                                                    <td width=25% style="text-align: center;">Remark</td>
                                                    <td width=5%>Remove</td></tr>';
                            
                            
                            $select_Open_Balance_Items = mysqli_query($conn,"select itm.Product_Name, obi.Item_Quantity, obi.Item_Remark, obi.Buying_Price, obi.Open_Balance_Item_ID
                                                                    from tbl_reagent_grn_open_balance_items obi, tbl_reagents_items itm where
                                                                        itm.Item_ID = obi.Item_ID and
                                                                            obi.Grn_Open_Balance_ID ='$Grn_Open_Balance_ID' order by itm.Product_Name") or die('Three'.mysqli_error($conn)); 
                        
                            $Temp=1;
                            while($row = mysqli_fetch_array($select_Open_Balance_Items)){ 
                                echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                                echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
                                echo "<td><input type='text' value='".$row['Item_Quantity']."' style='text-align: center;'></td>";
                                echo "<td style='text-align: right;'><input type='text' value='".$row['Buying_Price']."' style='text-align: right;'></td>";
                                echo "<td style='text-align: right;'><input type='text' value='".($row['Item_Quantity'] * $row['Buying_Price'])."' style='text-align: right;'></td>";
                                echo "<td><input type='text' value='".$row['Item_Remark']."'></td>";
                                ?>
					<td width=6%><input type='button' name='Remove_Item' id='Remove_Item' class='art-button-green' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Open_Balance_Item_ID']; ?>)'></td>
				<?php
				    echo "</tr>";
				    $Temp++;
				}
                            echo '</table>';
                        }
            }else{
                //insert data as a new Grn Open Balance
                $insert_data = mysqli_query($conn,"insert into tbl_reagent_grn_open_balance(
						Employee_ID,Branch_ID,
						    Created_Date_Time,Sub_Department_ID,
							Grn_Open_Balance_Description)
									
					 values('$Employee_ID','$Branch_ID',
                                            (select now()),'$Sub_Department_ID',
						    '$Grn_Description')") or die('Four'.mysqli_error($conn));
                
                if($insert_data){
                    //select the current Grn Open Balance ID
                   $select_Grn_Open_Balance_ID = mysqli_query($conn,"select * from tbl_reagent_grn_open_balance
								where Sub_Department_ID = '$Sub_Department_ID' and
								    Employee_ID = '$Employee_ID' and
									Grn_Open_Balance_Status = 'pending'") or die('Five'.mysqli_error($conn));
                    $no = mysqli_num_rows($select_Grn_Open_Balance_ID);
                    if($no > 0 ){
                        while($row = mysqli_fetch_array($select_Grn_Open_Balance_ID)){
                            $Grn_Open_Balance_ID = $row['Grn_Open_Balance_ID'];
                            $_SESSION['Reagent_Grn_Open_Balance_ID'] = $row['Grn_Open_Balance_ID'];
                        }
                        $_SESSION['Reagent_Grn_Open_Balance_ID'] = $Grn_Open_Balance_ID;
                    }else{
                        $Grn_Open_Balance_ID = 0;
                    }
                    
                    if($Grn_Open_Balance_ID != 0){
                        //insert item into tbl_reagent_grn_open_balance_items table
                        $insert_data2 = mysqli_query($conn,"insert into tbl_reagent_grn_open_balance_items(
							Grn_Open_Balance_ID,Item_ID,Item_Quantity,
							    Buying_Price,Item_Remark)
							
						 values('$Grn_Open_Balance_ID','$Item_ID','$Quantity',
							'$Buying_Price','$Item_Remark')") or die('six'.mysqli_error($conn));
                        if($insert_data2){
                            echo '<center><table width = 100% border=0>';
                            echo '<tr><td width=4% style="text-align: center;">Sn</td>
                                        <td width=30%>Item Name</td>
                                            <td width=7% style="text-align: center;">Quantity</td>
                                                    <td width=7% style="text-align: right;">Buying Price</td>
                                                    <td width=7% style="text-align: center;">Sub Total</td>
                                                    <td width=25% style="text-align: center;">Remark</td>
                                                    <td width=5%>Remove</td></tr>';
                            
                            
                            $select_Open_Balance_Items = mysqli_query($conn,"select itm.Product_Name, obi.Item_Quantity, obi.Item_Remark, obi.Buying_Price
                                                                    from tbl_reagent_grn_open_balance_items obi, tbl_reagents_items itm where
                                                                        itm.Item_ID = obi.Item_ID and
                                                                            obi.Grn_Open_Balance_ID ='$Grn_Open_Balance_ID'") or die('Seven'.mysqli_error($conn)); 
                        
                            $Temp=1;
                            while($row = mysqli_fetch_array($select_Open_Balance_Items)){ 
                                echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                                echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
                                echo "<td><input type='text' value='".$row['Item_Quantity']."' style='text-align: center;'></td>";
                                echo "<td style='text-align: right;'><input type='text' value='".$row['Buying_Price']."' style='text-align: right;'></td>";
                                echo "<td style='text-align: right;'><input type='text' value='".($row['Item_Quantity'] * $row['Buying_Price'])."' style='text-align: right;'></td>";
                                echo "<td><input type='text' value='".$row['Item_Remark']."'></td>";
                                ?>
					<td width=6%><input type='button' name='Remove_Item' id='Remove_Item' class='art-button-green' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Open_Balance_Item_ID']; ?>)'></td>
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