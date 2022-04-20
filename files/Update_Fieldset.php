<?php
    @session_start();
    include("./includes/connection.php");
    
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    if($Employee_ID != 0 && $Registration_ID != 0){
        //update fieldset
        echo '<table width =100%>';
            echo "<tr id='thead'><td style='width: 3%;'>Sn</td><td style='width: 10%;'>Check in type</td>";
                echo '<td style="width: 20%;">Location</td>
                        <td style="width: 28%;">Item description</td>
                            <td style="text-align:right; width: 8%;">Price</td>
                                <td style="text-align:right; width: 8%;">Discount</td>
                                    <td style="text-align:right; width: 8%;">Quantity</td>
                                        <td style="text-align:right; width: 8%;">Sub total</td><td width=4%>Remove</td></tr>';
                                        
        $total = 0; $temp = 1;
            $select_Transaction_Items = mysqli_query($conn,
                "select Check_In_Type, Patient_Direction, Product_Name, Price, Discount, Quantity,Patient_Payment_Item_List_ID, ppc.Registration_ID
                    from tbl_items t, tbl_patient_payments_cache ppc, tbl_patient_payment_item_list_cache ppi
                        where ppc.Patient_Payment_Cache_ID = ppi.Patient_Payment_Cache_ID and
                            t.item_id = ppi.item_id and
                                Employee_ID = '$Employee_ID' and
                                    Registration_ID = '$Registration_ID' and
                                        Transaction_status = 'pending'") or die(mysqli_error($conn));
            
        $num_of_items_selected = mysqli_num_rows($select_Transaction_Items);
            while($row = mysqli_fetch_array($select_Transaction_Items)){
                echo "<tr><td>".$temp."</td><td>".$row['Check_In_Type']."</td>";
                echo "<td>".$row['Patient_Direction']."</td>";
                echo "<td>".$row['Product_Name']."</td>";
                echo "<td style='text-align:right;'>".number_format($row['Price'])."</td>";
                echo "<td style='text-align:right;'>".number_format($row['Discount'])."</td>";
                echo "<td style='text-align:right;'>".$row['Quantity']."</td>";
                echo "<td style='text-align:right;'>".number_format(($row['Price'] - $row['Discount'])*$row['Quantity'])."</td>";
    ?>
                <td style='text-align: center;'> 
                    <input type='button' style='color: red; font-size: 10px;' value='X'  onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Patient_Payment_Item_List_ID']; ?>,<?php echo $row['Registration_ID']; ?>)'>
                </td>
    <?php 
                $total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity']);
                $temp++;
            }   echo "</tr>";
            echo "<tr><td colspan=8 style='text-align: right;'> <b>Total : ".number_format($total)."</b></td></tr></table>";
    }
?>