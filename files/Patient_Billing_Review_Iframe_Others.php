<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php"); 
    if(isset($_GET['Patient_Payment_ID'])){
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    }
    
    
    $total = 0;
    echo '<center><table width =100%>';
    echo "<tr id='thead'><td><b>CHECK IN TYPE</b></td>";
            echo    '<td><b>LOCATION</b></td>
                <td><b>ITEM DESCRIPTION</b></td>
                    <td style="text-align:right;"><b>PRICE</b></td>
                        <td style="text-align:right;"><b>DISCOUNT</b></td>
                            <td style="text-align:right;"><b>QUANTITY</b></td>
                                <td style="text-align:right;"><b>SUB TOTAL</b></td></tr>';
    
    
    $select_Transaction_Items = mysqli_query($conn,
            "select Check_In_Type, Patient_Direction, ppi.Item_Name, Price, Discount, Quantity
	    from tbl_items t, tbl_patient_payments_others pp, tbl_patient_payment_item_list_others ppi
                where pp.Patient_Payment_ID = ppi.Patient_Payment_ID and
		    t.item_id = ppi.item_id and
		        pp.Patient_Payment_ID = '$Patient_Payment_ID'"); 

		    
    while($row = mysqli_fetch_array($select_Transaction_Items)){
        echo "<tr><td>".$row['Check_In_Type']."</td>";
        echo "<td>".$row['Patient_Direction']."</td>";
        echo "<td>".$row['Item_Name']."</td>";
        echo "<td style='text-align:right;'>".number_format($row['Price'])."</td>";
        echo "<td style='text-align:right;'>".number_format($row['Discount'])."</td>";
        echo "<td style='text-align:right;'>".$row['Quantity']."</td>";
        echo "<td style='text-align:right;'>".number_format(($row['Price'] - $row['Discount'])*$row['Quantity'])."</td>";
        $total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity']);
    }   echo "</tr>";
        echo "<tr><td colspan=7 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>";
?></table></center>

