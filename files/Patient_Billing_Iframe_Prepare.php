<link rel="stylesheet" href="table.css" media="screen">
<?php
    @session_start();
    $temp = 1;
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    
    if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];
    }else{
	$Registration_ID = 0;
    }
    
    if(isset($_SESSION['userinfo']['Employee_ID'])){
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
	$Employee_ID = 0;
    }
    
    if(isset($_SESSION['Employee_ID_Reception_Transaction']) && isset($_SESSION['Registration_ID_Reception_Transaction'])){
	if($Registration_ID != $_SESSION['Registration_ID_Reception_Transaction']){
	    //if it is another patient we reset and change the session
	    $_SESSION['Registration_ID_Reception_Transaction'] = $Registration_ID;
	}
    }
    
    $total = 0;
    echo '<center><table width =100%>';
    echo "<tr id='thead'><td style='width: 3%;'>Sn</td><td style='width: 10%;'><b>Check in type</b></td>";
        echo '<td style="width: 20%;"><b>Location</b></td>
                <td style="width: 28%;"><b>Item description</b></td>
                    <td style="text-align:right; width: 8%;"><b>Price</b></td>
                        <td style="text-align:right; width: 8%;"><b>Discount</b></td>
                            <td style="text-align:right; width: 8%;"><b>Quantity</b></td>
                                <td style="text-align:right; width: 8%;"><b>Sub total</b></td><td width=4%>Remove</td></tr>';
    
    
    $select_Transaction_Items = mysqli_query($conn,
            "select Check_In_Type, Patient_Direction, Product_Name, Price, Discount, Quantity,Patient_Payment_Item_List_ID
	    from tbl_items t, tbl_patient_payments_cache ppc, tbl_patient_payment_item_list_cache ppi
                where ppc.Patient_Payment_Cache_ID = ppi.Patient_Payment_Cache_ID and
		    t.item_id = ppi.item_id and
			Employee_ID = '$Employee_ID' and
			    Registration_ID = '$Registration_ID' and
				Transaction_status = 'pending'") or die(mysqli_error($conn)); 

    
    while($row = mysqli_fetch_array($select_Transaction_Items)){
        echo "<tr><td>".$temp."</td><td>".$row['Check_In_Type']."</td>";
        echo "<td>".$row['Patient_Direction']."</td>";
        echo "<td>".$row['Product_Name']."</td>";
        echo "<td style='text-align:right;'>".number_format($row['Price'])."</td>";
        echo "<td style='text-align:right;'>".number_format($row['Discount'])."</td>";
        echo "<td style='text-align:right;'>".$row['Quantity']."</td>";
        echo "<td style='text-align:right;'>".number_format(($row['Price'] - $row['Discount'])*$row['Quantity'])."</td>";
        echo "<td style='text-align: center;'>
		<span style='color: red; font-size: 20px;'>
		    <a href='Patient_Billing_Remove_Item.php?Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Registration_ID=".$Registration_ID."' style='text-decoration: none; color: red;'><b>X</b></a>
		</span>
		</td>";
	
	
	$total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity']);
	$temp++;
    }   echo "</tr>";
        echo "<tr><td colspan=8 style='text-align: right;'> Total : ".number_format($total)."</td></tr>";
?></table></center>