<?php
    include("./includes/connection.php"); 
    
    if(isset($_GET['Patient_Payment_ID'])){
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    }
    $total = 0;
    echo '<center><table width =100% border=1>';
    echo '<tr><td><b>CHECK IN TYPE</b></td>
                <td><b>DIRECTION</b></td>
                <td><b>ITEM DESCRIPTION</b></td>
                    <td><b>PRICE</b></td>
                        <td><b>DISCOUNT</b></td>
                            <td><b>QUANTITY</b></td>
                                <td><b>SUB TOTAL</b></td>
				    <td><b>Action</b></td></tr>';
    
    
    $select_Transaction_Items = mysqli_query($conn,
            "select Check_In_Type, Patient_Direction, Product_Name, Price, Discount, Item_Name, Quantity,Patient_Payment_Item_List_ID,pp.Patient_Payment_ID
	    from tbl_items t, tbl_patient_payments pp, tbl_patient_payment_item_list ppi
                where pp.Patient_Payment_ID = ppi.Patient_Payment_ID and
		    t.item_id = ppi.item_id and
		        pp.Patient_Payment_ID = '$Patient_Payment_ID'
			and Patient_Payment_Item_List_ID <> '$Patient_Payment_Item_List_ID'"); 

		    
    while($row = mysqli_fetch_array($select_Transaction_Items)){
        echo "<tr><td>".$row['Check_In_Type']."</td>";
        echo "<td>".$row['Patient_Direction']."</td>";
        echo "<td>".$row['Item_Name']."</td>";
        echo "<td>".$row['Price']."</td>";
        echo "<td>".$row['Discount']."</td>";
        echo "<td>".$row['Quantity']."</td>";
        echo "<td>".number_format(($row['Price'] - $row['Discount'])*$row['Quantity'])."</td>";
//        echo "<td><a href='patientbillingeditcashtransaction.php?Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Selected=Selected&EditTransaction=EditTransactionThisForm' style='text-decoration: none;' target='_Parent'><b>EDIT</b></td>";
        echo "<td><input type='button' id='".$row['Patient_Payment_Item_List_ID']."' class='art-button editbtn' value='Edit'></td>";
	$total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity']);
    }   echo "</tr>";
        echo "<tr><td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>";
?></table></center>

