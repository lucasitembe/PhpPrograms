<?php
    @session_start();
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	    if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    } else{
		@session_start();
		if(!isset($_SESSION['supervisor'])){
                
                    //get patient registration id for future use
                    if(isset($_GET['Registration_ID'])){
                        $Registration_ID = $_GET['Registration_ID'];
                    }else{
                        $Registration_ID = '';
                    }
		    header("Location: ./supervisorauthentication.php?Registration_ID=$Registration_ID&InvalidSupervisorAuthentication=yes");
                    //header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
		}
	    } 
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_GET['Patient_Payment_Item_List_ID'])){
        $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    }else{
        $Patient_Payment_Item_List_ID = 0;
    }
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    if($Patient_Payment_Item_List_ID != 0 && $Registration_ID != 0 && $Employee_ID != 0){
        //get item foreign key
        $get_Patient_Payment_Cache_ID = mysqli_query($conn,"select Patient_Payment_Cache_ID from tbl_patient_payment_item_list_cache
                                                        where Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
        while($row = mysqli_fetch_array($get_Patient_Payment_Cache_ID)){
            $Patient_Payment_Cache_ID = $row['Patient_Payment_Cache_ID'];
        }
        
        //delete item based on Patient_Payment_Item_List_ID
        $delete_item = mysqli_query($conn,"delete from tbl_patient_payment_item_list_cache where Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
        if($delete_item){
            
            //detete all details 
            
            
            echo '<table width =100%>';
                echo "<tr id='thead'><td style='width: 3%;'><b>Sn</b></td><td style='width: 10%;'><b>Check-In</b></td>";
                    echo '<td style="width: 20%;"><b>Location</b></td>
                            <td style="width: 28%;"><b>Item description</b></td>
                                <td style="text-align:right; width: 8%;"><b>Price</b></td>
                                    <td style="text-align:right; width: 8%;"><b>Discount</b></td>
                                        <td style="text-align:right; width: 8%;"><b>Qty</b></td>
                                            <td style="text-align:right; width: 10%;"><b>Sub total</b></td><td width=4%><b>Remove</b></td></tr>';
            $total = 0; $temp = 1;
            $select_Transaction_Items = mysqli_query($conn,
                "select Check_In_Type, Patient_Direction, Product_Name, Price, Discount, Quantity,Patient_Payment_Item_List_ID, ppc.Registration_ID
                    from tbl_items t, tbl_patient_payments_cache ppc, tbl_patient_payment_item_list_cache ppi
                        where ppc.Patient_Payment_Cache_ID = ppi.Patient_Payment_Cache_ID and
                            t.item_id = ppi.item_id and
                                ppi.Patient_Payment_Cache_ID='$Patient_Payment_Cache_ID'") or die(mysqli_error($conn));
            
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
                //<a href='Patient_Billing_Remove_Item.php?Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Registration_ID=".$Registration_ID."' style='text-decoration: none; color: red;'>
                
                $total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity']);
                $temp++;
            }echo "</tr>";
            echo "<tr><td colspan=8 style='text-align: right;'> <b>Total : ".number_format($total)."</b></td></tr></table>";
            
            
        }else{
            echo 'Errors';
        }
    }
    
    
?>