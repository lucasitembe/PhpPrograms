<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Payment_ID'])){
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    }
    $total = 0;
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td width = 3%>Sn</td><td><b>Check in type</b></td>
                <td><b>Location</b></td>
                <td><b>Item descript</b></td>
                    <td style="text-align: right;"><b>Price</b></td>
                        <td style="text-align: right;"><b>Discount</b></td>
                            <td style="text-align: right;"><b>Quantity</b></td>
                                <td style="text-align: right;"><b>Sub total</b></td></tr>';
    
    
    $select_Transaction_Items = mysqli_query($conn,
            "select Check_In_Type, Patient_Direction, Product_Name, Price, Discount, Quantity,Patient_Payment_Item_List_ID,pp.Patient_Payment_ID, Transaction_type, Item_Name
	    from tbl_items t, tbl_patient_payments pp, tbl_patient_payment_item_list ppi
                where pp.Patient_Payment_ID = ppi.Patient_Payment_ID and
		    t.item_id = ppi.item_id and
		        pp.Patient_Payment_ID = '$Patient_Payment_ID'
			and Patient_Payment_Item_List_ID <> '$Patient_Payment_Item_List_ID'"); 

		    
    while($row = mysqli_fetch_array($select_Transaction_Items)){
        $Transaction_type = $row['Transaction_type'];
        echo "<tr><td>".$temp."</td>";
        echo "<td>".$row['Check_In_Type']."</td>";
        echo "<td>".$row['Patient_Direction']."</td>";
        if(strtolower($Transaction_type) == 'direct cash'){
            echo "<td>".$row['Item_Name']."</td>";
        }else{
            echo "<td>".$row['Product_Name']."</td>";
        }
        echo "<td width=10% style='text-align: right;'>".number_format($row['Price'])."</td>";
        echo "<td width=10% style='text-align: right;'>".number_format($row['Discount'])."</td>";
        echo "<td width=10% style='text-align: right;'>".$row['Quantity']."</td>";
        echo "<td width=10% style='text-align: right;'>".number_format(($row['Price'] - $row['Discount'])*$row['Quantity'])."</td>";
        //echo "<td><a href='patientbillingedit.php?Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Selected=Selected&EditTransaction=EditTransactionThisForm' style='text-decoration: none;' target='_Parent'><b>EDIT</b></td>";
	$total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity']);
	$temp++;
    }   echo "</tr>";
        echo "<tr><td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>";
?></table></center>

