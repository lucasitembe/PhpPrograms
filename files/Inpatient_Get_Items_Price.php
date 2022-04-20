<?php

    include("./includes/connection.php");
    $Select_Price='';
    if(isset($_GET['Item_ID'])&&($_GET['Item_ID']!='') && isset($_GET['Guarantor_Name']) && ($_GET['Guarantor_Name'] != '')){
        $Item_ID = $_GET['Item_ID'];
	$Billing_Type = $_GET['Billing_Type'];
	$Guarantor_Name = $_GET['Guarantor_Name'];
	
        if($Billing_Type=='Outpatient Credit'||$Billing_Type=='Inpatient Credit'){
            if(strtolower($Guarantor_Name)=='nhif'){
            $Select_Price = "select Selling_Price_NHIF as price from tbl_items i
                                    where i.Item_ID = '$Item_ID' ";
            }else{
                $Select_Price = "select Selling_Price_Credit as price from tbl_items i
                                    where i.Item_ID = '$Item_ID' ";
            }
        }elseif($Billing_Type=='Outpatient Cash'||$Billing_Type=='Inpatient Cash'){
            $Select_Price = "select Selling_Price_Cash as price from tbl_items i
                                    where i.Item_ID = '$Item_ID' ";
        }
        $result = @mysqli_query($conn,$Select_Price);
        $row = @mysqli_fetch_assoc($result);
        echo number_format($row['price']);
    }else{
	echo '0';
    }
    
    
?>