<link rel="stylesheet" href="table.css" media="screen">
<?php
@session_start();
    include("./includes/connection.php"); 
    if(isset($_GET['Patient_Payment_ID'])){
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    }
    
//    die ("select Check_In_Type, Patient_Direction, Product_Name, Price, Discount, Quantity, 
//                                                pp.Transaction_type, ppi.Item_Name, ppi.Patient_Payment_Item_List_ID
//                                                from tbl_items t, tbl_patient_payments pp, tbl_patient_payment_item_list ppi
//                                                where pp.Patient_Payment_ID = ppi.Patient_Payment_ID and
//                                                t.item_id = ppi.item_id and
//                                                pp.Patient_Payment_ID = '$Patient_Payment_ID'");
    
    
    $total = 0;
    echo '<center><table width =100%>';
    echo "<tr id='thead'><td><b>CHECK IN</b></td>";
            echo    '<td><b>LOCATION</b></td>
                        <td><b>ITEM DESCRIPTION</b></td>
                        <td style="text-align:right;"><b>PRICE</b></td>
                        <td style="text-align:right;"><b>DISCOUNT</b></td>
                        <td style="text-align:right;"><b>QUANTITY</b></td>
                        <td style="text-align:right;"><b>SUB TOTAL</b></td></tr>
                        ';
    
    
    $select_Transaction_Items = mysqli_query($conn,"select Check_In_Type, Patient_Direction, Product_Name, Price, Discount, Quantity, 
                                                pp.Transaction_type, ppi.Item_Name, ppi.Patient_Payment_Item_List_ID
                                                from tbl_items t, tbl_patient_payments pp, tbl_patient_payment_item_list ppi
                                                where pp.Patient_Payment_ID = ppi.Patient_Payment_ID and
                                                t.item_id = ppi.item_id and
                                                pp.Patient_Payment_ID = '$Patient_Payment_ID'") OR die(mysqli_error($conn)); 

		    
    while($row = mysqli_fetch_array($select_Transaction_Items)){
        $Transaction_type = $row['Transaction_type'];

        echo "<tr><td>".$row['Check_In_Type']."</td>";
        echo "<td>".$row['Patient_Direction']."</td>";
        if(strtolower($Transaction_type) == 'direct cash'){
            echo "<td>".$row['Product_Name']." ~ ".$row['Item_Name']."</td>";
        }else{
            echo "<td>".$row['Product_Name']."</td>";
        }
        echo "<td style='text-align:right;'>".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price']))."</td>";
        echo "<td style='text-align:right;'>".number_format($row['Discount'])."</td>";
        echo "<td style='text-align:right;'>".$row['Quantity']."</td>";
        echo "<td style='text-align:right;'>".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount'])*$row['Quantity'], 2) : number_format(($row['Price'] - $row['Discount'])*$row['Quantity']))."</td>";
		// echo "<td style='text-align:center;'>
		      // <button type='button' class='art-button-green' onClick='removeReceptionTransaction(".$row['Patient_Payment_Item_List_ID'].",$Patient_Payment_ID)'>Remove</button>
			  // <button type='button' class='art-button-green' onClick='editReceptionTransaction(".$row['Patient_Payment_Item_List_ID'].",$Patient_Payment_ID)'>Edit</button>
			 // </td>";
		
        $total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity']);
		echo "</tr>";
    }  
        echo "<tr><td colspan=7 style='text-align: right;'><b> TOTAL : ".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)).'  '.$_SESSION['hospcurrency']['currency_code']."</b></td></tr>";
?></table></center>

