<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php"); 
    if(isset($_GET['Patient_Payment_ID'])){
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    }
    $total = 0;
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td><b>PAYMENT DESCRIPTION</b></td>
                <td style="text-align: right;"><b>PRICE</b></td>
                <td style="text-align: right;"><b>DISCOUNT</b></td>
                    <td style="text-align: right;"><b>QUANTITY</b></td>  
                                <td style="text-align: right;"><b>SUB TOTAL</b></td></tr>';
    
    
    $select_Transaction_Items = mysqli_query($conn,
            "select * from tbl_patient_payments pp, tbl_patient_payment_item_list ppi
                where pp.Patient_Payment_ID = ppi.Patient_Payment_ID and
		    pp.Patient_Payment_ID = '$Patient_Payment_ID' and ppi.Check_In_Type='Direct Cash'"); 

		    
    while($row = mysqli_fetch_array($select_Transaction_Items)){
        echo "<tr>";
        echo "<td>".$row['Item_Name']."</td>";
        echo "<td style='text-align: right;'>".number_format($row['Price'])."</td>";
        echo "<td style='text-align: right;'>".number_format($row['Discount'])."</td>";
        echo "<td style='text-align: right;'>".$row['Quantity']."</td>"; 
        echo "<td style='text-align: right;'>".number_format(($row['Price'] - $row['Discount'])*$row['Quantity'])."</td>";
        $total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity']);
    }   echo "</tr>";
        echo "<tr><td colspan=7 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>";
?></table></center>

