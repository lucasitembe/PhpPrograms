<?php session_start();
include ("./includes/connection.php");
include ("calculate_buying_price.php");
$filter = '';
if (isset($_GET['Start_Date'])) {
    $Start_Date = mysqli_real_escape_string($conn, $_GET['Start_Date']);
} else {
    $Start_Date = '';
}
if (isset($_GET['End_Date'])) {
    $End_Date = mysqli_real_escape_string($conn, $_GET['End_Date']);
} else {
    $End_Date = '';
}
if (isset($_GET['Search_Value'])) {
    $Search_Value = mysqli_real_escape_string($conn, $_GET['Search_Value']);
} else {
    $Search_Value = '';
}
if (isset($_SESSION['Pharmacy_ID'])) {
    $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
} else {
    $Sub_Department_ID = 0;
}
if (isset($_GET['sponsorID']) && strtolower($_GET['sponsorID']) != 'all') {
    $sponsorID = $_GET['sponsorID'];
} else {
    $sponsorID = null;
}
$select = mysqli_query($conn, "SELECT Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Sub_Department_Name = $data['Sub_Department_Name'];
    }
} else {
    $Sub_Department_Name = '';
}
$Title = '<tr><td colspan="9"><hr></td></tr>
                <tr>
                    <td width=3%><b>SN</b></td>
                    <td width=4%><b>ITEM CODE</b></td>
                    <td width=40%><b>ITEM NAME</b></td>
                    <td width=6%><b>QUANTITY DISPENSED</b></td>
                    <td width=6%><b>BALANCE</b></td>
                    <td width=8.5%><b>TOTAL BUYING PRICE</b></td>
                    <td width=8.5%><b>TOTAL SELLING PRICE</b></td>
                    <td width=6%><b>PROFIT/LOSS</b></td>
                    <td width=7%><b>TOTAL STOCK VALUE</b></td>
                </tr>
                <tr><td colspan="9"><hr></td></tr>';;
echo '<legend align=\'right\'><b>DISPENSE SUMMARY ~ ';
if (isset($_SESSION['Pharmacy_ID'])) {
    echo strtoupper($Sub_Department_Name);
};
echo '</b></legend>
';
if (isset($_GET['Search_Value'])) {
    echo '<table width=100%>';
    echo $Title;
    $temp = 1;
    $total_items = 0;
    if (isset($_SESSION['Pharmacy'])) {
        $Sub_Department_Name = $_SESSION['Pharmacy'];
    } else {
        $Sub_Department_Name = '';
    }
    if (isset($sponsorID) && $sponsorID != 'All') {
        $result = mysqli_query($conn, "SELECT ilc.Dispense_Date_Time,i.Item_ID, i.Product_Name,ilc.price,Last_Buy_Price,i.Product_Code From tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc left join tbl_items i on i.Item_ID=ilc.Item_ID  where pp.Patient_Payment_ID = ilc.Patient_Payment_ID and pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = pp.Registration_ID and ilc.Check_In_Type = 'Pharmacy' and ilc.status = 'dispensed' and pr.Sponsor_ID='$sponsorID' and i.Product_Name like '%$Search_Value%' and  ilc.Sub_Department_ID = '$Sub_Department_ID' group by i.Item_ID order by i.Product_Name ") or die(mysqli_error($conn));
    } else {
        $result = mysqli_query($conn, "SELECT ilc.Dispense_Date_Time,i.Item_ID, i.Product_Name,ilc.price,Last_Buy_Price,i.Product_Code FROM tbl_items i,tbl_item_list_cache ilc
                                        where i.Item_ID = ilc.Item_ID and 
                                        ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and
                                        ilc.Check_In_Type = 'pharmacy' and
                                        ilc.Status = 'dispensed' and
                                        i.Product_Name like '%$Search_Value%' and
                                        ilc.Sub_Department_ID = '$Sub_Department_ID'
                                        group by i.Item_ID order by i.Product_Name limit 500") or die(mysqli_error($conn));
    }
    $grand_total_buying_price = 0;
    $grand_total_selling_price = 0;
    $grand_total_profit_or_loss = 0;
    $grand_total_total_stock_value = 0;
    while ($row = mysqli_fetch_array($result)) {
        $Item_ID = $row['Item_ID'];
        $Product_Name = $row['Product_Name'];
        if (isset($sponsorID) && $sponsorID != 'All') {
            $Individual_Details = mysqli_query($conn, "SELECT ilc.Dispense_Date_Time,ilc.Price,i.Product_Name, ilc.Quantity, ilc.Edited_Quantity From tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc left join tbl_items i on i.Item_ID=ilc.Item_ID  where pp.Patient_Payment_ID = ilc.Patient_Payment_ID and pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = pp.Registration_ID and 
                            ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and ilc.Check_In_Type = 'Pharmacy' and ilc.status = 'dispensed' and pr.Sponsor_ID='$sponsorID' and ilc.Item_ID = '$Item_ID' and ilc.Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        } else {
            $Individual_Details = mysqli_query($conn, "SELECT ilc.Dispense_Date_Time,ilc.Price,i.Product_Name, ilc.Quantity, ilc.Edited_Quantity
                                                        FROM tbl_items i,tbl_item_list_cache ilc
                                                        where i.Item_ID = ilc.Item_ID and 
                                                        ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and
                                                        ilc.Check_In_Type = 'pharmacy' and
                                                        ilc.Status = 'dispensed' and ilc.Item_ID = '$Item_ID' and ilc.Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        }
        $total_selling_price = 0;
        $total_buying_price = 0;
        while ($row2 = mysqli_fetch_array($Individual_Details)) {
            $Quantity = $row2['Quantity'];
            $Edited_Quantity = $row2['Edited_Quantity'];
            $Price = $row2['Price'];
            $Dispense_Date_Time = $row2['Dispense_Date_Time'];
            $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
            // $Last_Buy_Price = get_item_buying_price($Item_ID, $Dispense_Date_Time, $Sub_Department_ID);

            $buying1=mysqli_query($conn,"SELECT Buying_Price FROM tbl_grn_open_balance_items grn, tbl_item_list_cache ilc WHERE Item_ID='$Item_ID' ORDER BY Open_Balance_Item_ID DESC LIMIT 1");
            $result_buy1= mysqli_fetch_assoc($buying1);
            $grn_price=$result_buy1['Buying_Price'];
            
            $buying2=mysqli_query($conn,"SELECT Price FROM tbl_grn_without_purchase_order_items grn, tbl_item_list_cache ilc WHERE Item_ID='$Item_ID' ORDER BY Purchase_Order_Item_ID DESC LIMIT 1");
            $result_buy2=  mysqli_fetch_assoc($buying2);
            $purchase_price=$result_buy2['Price'];
             
            if(($purchase_price > 0)){    
                $Last_Buy_Price=$purchase_price;

            }elseif (($purchase_price=='') || ($purchase_price==0)){
                
               $Last_Buy_Price=$grn_price;
    
            }
            $dispenced_quantity = 0;
            if ($Edited_Quantity != 0) {
                $total_items = $total_items + $Edited_Quantity;
                $dispenced_quantity = $Edited_Quantity;
            } else {
                $total_items = $total_items + $Quantity;
                $dispenced_quantity = $Quantity;
            }
            $total_buying_price+= ($Last_Buy_Price * $dispenced_quantity);
            $total_selling_price+= ($Price * $dispenced_quantity);
        }
        if ($total_items <= 0) {
            continue;
        }
        $sql_balance = mysqli_query($conn, "select Item_Balance from tbl_items_balance where
                                                Item_ID = '$Item_ID' and
                                                Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        $num_balance = mysqli_num_rows($sql_balance);
        if ($num_balance > 0) {
            while ($sd = mysqli_fetch_array($sql_balance)) {
                $Item_Balance = $sd['Item_Balance'];
            }
        } else {
            mysqli_query($conn, "insert into tbl_items_balance(Item_ID, Sub_Department_ID) values('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
            $Item_Balance = 0;
        }
        if ($Item_Balance < 0) {
            $Item_Balance = 0;
        };
        echo '                    <tr>
                        <td style=\'text-align: left;\'><label onclick="View_Details(\'';
        echo $Start_Date;;
        echo '\',\'';
        echo $End_Date;;
        echo '\',';
        echo $Item_ID;;
        echo ',\'';
        echo $sponsorID;
        echo '\')">';
        echo $temp;;
        echo '</label></td>
                        <td style=\'text-align: left;\'><label onclick="View_Details(\'';
        echo $Start_Date;;
        echo '\',\'';
        echo $End_Date;;
        echo '\',';
        echo $Item_ID;;
        echo ',\'';
        echo $sponsorID;
        echo '\')">';
        echo $row['Product_Code'];;
        echo '</label></td>
                        <td style=\'text-align: left;\'><label onclick="View_Details(\'';
        echo $Start_Date;;
        echo '\',\'';
        echo $End_Date;;
        echo '\',';
        echo $Item_ID;;
        echo ',\'';
        echo $sponsorID;
        echo '\')">';
        echo $Product_Name;;
        echo '</label></td>
                        <td style=\'text-align: left;\'><label onclick="View_Details(\'';
        echo $Start_Date;;
        echo '\',\'';
        echo $End_Date;;
        echo '\',';
        echo $Item_ID;;
        echo ',\'';
        echo $sponsorID;
        echo '\')">';
        echo $total_items;;
        echo '</label></td>
                        <td style=\'text-align: left;\'><label onclick="View_Details(\'';
        echo $Start_Date;;
        echo '\',\'';
        echo $End_Date;;
        echo '\',';
        echo $Item_ID;;
        echo ',\'';
        echo $sponsorID;
        echo '\')">';
        echo $Item_Balance;;
        echo '</label></td>
                        <td style=\'text-align: left;\'><label onclick="View_Details(\'';
        echo $Start_Date;;
        echo '\',\'';
        echo $End_Date;;
        echo '\',';
        echo $Item_ID;;
        echo ',\'';
        echo $sponsorID;
        echo '\')">';
        echo number_format($total_buying_price);;
        echo '</label></td>
                        <td style=\'text-align: left;\'><label onclick="View_Details(\'';
        echo $Start_Date;;
        echo '\',\'';
        echo $End_Date;;
        echo '\',';
        echo $Item_ID;;
        echo ',\'';
        echo $sponsorID;
        echo '\')">';
        echo number_format($total_selling_price);;
        echo '</label></td>
                        <td style=\'text-align: left;\'><label onclick="View_Details(\'';
        echo $Start_Date;;
        echo '\',\'';
        echo $End_Date;;
        echo '\',';
        echo $Item_ID;;
        echo ',\'';
        echo $sponsorID;
        echo '\')">';
        echo number_format($total_selling_price - $total_buying_price);;
        echo '</label></td>
                        <td style=\'text-align: left;\'><label onclick="View_Details(\'';
        echo $Start_Date;;
        echo '\',\'';
        echo $End_Date;;
        echo '\',';
        echo $Item_ID;;
        echo ',\'';
        echo $sponsorID;
        echo '\')">';
        echo number_format($Item_Balance * $total_buying_price);;
        echo '</label></td>
                    </tr>
                ';
        $grand_total_buying_price+= ($total_buying_price);
        $grand_total_selling_price+= $total_selling_price;
        $grand_total_profit_or_loss+= ($total_selling_price - $total_buying_price);
        $grand_total_total_stock_value+= ($Item_Balance * $total_buying_price);
        $temp++;
        $Edited_Quantity = 0;
        $Quantity = 0;
        $total_items = 0;
        if (($temp % 31) == 0) {
            echo $Title;
        }
    };
    echo '  <tr><td colspan="9"><hr></td></tr>
                    <tr>
                        <td colspan="5"><b>GRAND TOTAL</b></td>
                        <td><b>';
    echo number_format($grand_total_buying_price);
    echo '</b></td>
                        <td><b>';
    echo number_format($grand_total_selling_price);
    echo '</b></td>
                        <td><b>';
    echo number_format($grand_total_profit_or_loss);
    echo '</b></td>
                        <td><b>';
    echo number_format($grand_total_total_stock_value);
    echo '</b></td>
                    </tr>    
            ';
    echo '</table>';
} else {
    echo '<table width=100%>';
    echo $Title;
    $temp = 1;
    $total_items = 0;
    if (isset($_SESSION['Pharmacy'])) {
        $Sub_Department_Name = $_SESSION['Pharmacy'];
    } else {
        $Sub_Department_Name = '';
    }
    if (isset($sponsorID) && $sponsorID != 'All') {
        $result = mysqli_query($conn, "SELECT ilc.Dispense_Date_Time,i.Item_ID, i.Product_Name,ilc.price,Last_Buy_Price,i.Product_Code From tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc left join tbl_items i on i.Item_ID=ilc.Item_ID  where pp.Patient_Payment_ID = ilc.Patient_Payment_ID and pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = pp.Registration_ID and ilc.Check_In_Type = 'Pharmacy' and ilc.status = 'dispensed' and pr.Sponsor_ID='$sponsorID' and ilc.Sub_Department_ID = '$Sub_Department_ID' group by i.Item_ID order by i.Product_Name ") or die(mysqli_error($conn));
    } else {
        $result = mysqli_query($conn, "SELECT ilc.Dispense_Date_Time,i.Item_ID, i.Product_Name,ilc.price,Last_Buy_Price,i.Product_Code FROM tbl_items i,tbl_item_list_cache ilc
                                        where i.Item_ID = ilc.Item_ID and 
                                        ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and
                                        ilc.Check_In_Type = 'pharmacy' and
                                        ilc.Status = 'dispensed' and
                                        i.Product_Name like '%$Search_Value%' and
                                        ilc.Sub_Department_ID = '$Sub_Department_ID'
                                        group by i.Item_ID order by i.Product_Name limit 500") or die(mysqli_error($conn));
    }
    $grand_total_buying_price = 0;
    $grand_total_selling_price = 0;
    $grand_total_profit_or_loss = 0;
    $grand_total_total_stock_value = 0;
    while ($row = mysqli_fetch_array($result)) {
        $Item_ID = $row['Item_ID'];
        $Product_Name = $row['Product_Name'];
        if (isset($sponsorID) && $sponsorID != 'All') {
            $Individual_Details = mysqli_query($conn, "SELECT ilc.Dispense_Date_Time,ilc.Price,i.Product_Name, ilc.Quantity, ilc.Edited_Quantity From tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc left join tbl_items i on i.Item_ID=ilc.Item_ID  where pp.Patient_Payment_ID = ilc.Patient_Payment_ID and pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = pp.Registration_ID and 
                            ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and ilc.Check_In_Type = 'Pharmacy' and ilc.status = 'dispensed' and pr.Sponsor_ID='$sponsorID' and ilc.Item_ID = '$Item_ID' and ilc.Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        } else {
            $Individual_Details = mysqli_query($conn, "SELECT ilc.Dispense_Date_Time,ilc.Price,i.Product_Name, ilc.Quantity, ilc.Edited_Quantity
                                                        FROM tbl_items i,tbl_item_list_cache ilc
                                                        where i.Item_ID = ilc.Item_ID and 
                                                        ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and
                                                        ilc.Check_In_Type = 'pharmacy' and
                                                        ilc.Status = 'dispensed' and ilc.Item_ID = '$Item_ID' and ilc.Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        }
        $total_selling_price = 0;
        $total_buying_price = 0;
        while ($row2 = mysqli_fetch_array($Individual_Details)) {
            $Quantity = $row2['Quantity'];
            $Edited_Quantity = $row2['Edited_Quantity'];
            $Price = $row2['Price'];
            $Dispense_Date_Time = $row2['Dispense_Date_Time'];
            $Sub_Department_ID = $_SESSION['Pharmacy_ID'];


            $buying1=mysqli_query($conn,"SELECT Buying_Price FROM tbl_grn_open_balance_items WHERE Item_ID='$Item_ID' ORDER BY Open_Balance_Item_ID DESC LIMIT 1");
            $result_buy1= mysqli_fetch_assoc($buying1);
            $grn_price=$result_buy1['Buying_Price'];
            
            $buying2=mysqli_query($conn,"SELECT Price FROM tbl_grn_without_purchase_order_items WHERE Item_ID='$Item_ID' ORDER BY Purchase_Order_Item_ID DESC LIMIT 1");
            $result_buy2=  mysqli_fetch_assoc($buying2);
            $purchase_price=$result_buy2['Price'];
             
            if(($purchase_price > 0)){    
                $Last_Buy_Price=$purchase_price;

            }elseif (($purchase_price=='') || ($purchase_price==0)){
                
               $Last_Buy_Price=$grn_price;
    
            }

            $dispenced_quantity = 0;
            if ($Edited_Quantity != 0) {
                $total_items = $total_items + $Edited_Quantity;
                $dispenced_quantity = $Edited_Quantity;
            } else {
                $total_items = $total_items + $Quantity;
                $dispenced_quantity = $Quantity;
            }
            $total_buying_price+= ($Last_Buy_Price * $dispenced_quantity);
            $total_selling_price+= ($Price * $dispenced_quantity);
        }
        if ($total_items <= 0) {
            continue;
        }
        $sql_balance = mysqli_query($conn, "SELECT Item_Balance from tbl_items_balance where
                                                Item_ID = '$Item_ID' and
                                                Sub_Department_ID = (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1)") or die(mysqli_error($conn));
        $num_balance = mysqli_num_rows($sql_balance);
        if ($num_balance > 0) {
            while ($sd = mysqli_fetch_array($sql_balance)) {
                $Item_Balance = $sd['Item_Balance'];
            }
        } else {
            mysqli_query($conn, "insert into tbl_items_balance(Item_ID, Sub_Department_ID) values('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
            $Item_Balance = 0;
        }
        if ($Item_Balance < 0) {
            $Item_Balance = 0;
        };
        echo '                    <tr>
                        <td style=\'text-align: left;\'><label onclick="View_Details(\'';
        echo $Start_Date;;
        echo '\',\'';
        echo $End_Date;;
        echo '\',';
        echo $Item_ID;;
        echo ',\'';
        echo $sponsorID;
        echo '\')">';
        echo $temp;;
        echo '</label></td>
                        <td style=\'text-align: left;\'><label onclick="View_Details(\'';
        echo $Start_Date;;
        echo '\',\'';
        echo $End_Date;;
        echo '\',';
        echo $Item_ID;;
        echo ',\'';
        echo $sponsorID;
        echo '\')">';
        echo $row['Product_Code'];;
        echo '</label></td>
                        <td style=\'text-align: left;\'><label onclick="View_Details(\'';
        echo $Start_Date;;
        echo '\',\'';
        echo $End_Date;;
        echo '\',';
        echo $Item_ID;;
        echo ',\'';
        echo $sponsorID;
        echo '\')">';
        echo $Product_Name;;
        echo '</label></td>
                        <td style=\'text-align: left;\'><label onclick="View_Details(\'';
        echo $Start_Date;;
        echo '\',\'';
        echo $End_Date;;
        echo '\',';
        echo $Item_ID;;
        echo ',\'';
        echo $sponsorID;
        echo '\')">';
        echo $total_items;;
        echo '</label></td>
                        <td style=\'text-align: left;\'><label onclick="View_Details(\'';
        echo $Start_Date;;
        echo '\',\'';
        echo $End_Date;;
        echo '\',';
        echo $Item_ID;;
        echo ',\'';
        echo $sponsorID;
        echo '\')">';
        echo $Item_Balance;;
        echo '</label></td>
                        <td style=\'text-align: left;\'><label onclick="View_Details(\'';
        echo $Start_Date;;
        echo '\',\'';
        echo $End_Date;;
        echo '\',';
        echo $Item_ID;;
        echo ',\'';
        echo $sponsorID;
        echo '\')">';
        echo number_format($total_buying_price);;
        echo '</label></td>
                        <td style=\'text-align: left;\'><label onclick="View_Details(\'';
        echo $Start_Date;;
        echo '\',\'';
        echo $End_Date;;
        echo '\',';
        echo $Item_ID;;
        echo ',\'';
        echo $sponsorID;
        echo '\')">';
        echo number_format($total_selling_price);;
        echo '</label></td>
                        <td style=\'text-align: left;\'><label onclick="View_Details(\'';
        echo $Start_Date;;
        echo '\',\'';
        echo $End_Date;;
        echo '\',';
        echo $Item_ID;;
        echo ',\'';
        echo $sponsorID;
        echo '\')">';
        echo number_format($total_selling_price - $total_buying_price);;
        echo '</label></td>
                        <td style=\'text-align: left;\'><label onclick="View_Details(\'';
        echo $Start_Date;;
        echo '\',\'';
        echo $End_Date;;
        echo '\',';
        echo $Item_ID;;
        echo ',\'';
        echo $sponsorID;
        echo '\')">';
        echo number_format($Item_Balance * $total_buying_price);;
        echo '</label></td>
                    </tr>
                ';
        $grand_total_buying_price+= ($total_buying_price);
        $grand_total_selling_price+= $total_selling_price;
        $grand_total_profit_or_loss+= ($total_selling_price - $total_buying_price);
        $grand_total_total_stock_value+= ($Item_Balance * $total_buying_price);
        $temp++;
        $Edited_Quantity = 0;
        $Quantity = 0;
        $total_items = 0;
        if (($temp % 31) == 0) {
            echo $Title;
        }
    };
    echo '  <tr><td colspan="9"><hr></td></tr>
                    <tr>
                        <td colspan="5"><b>GRAND TOTAL</b></td>
                        <td><b>';
    echo number_format($grand_total_buying_price);
    echo '</b></td>
                        <td><b>';
    echo number_format($grand_total_selling_price);
    echo '</b></td>
                        <td><b>';
    echo number_format($grand_total_profit_or_loss);
    echo '</b></td>
                        <td><b>';
    echo number_format($grand_total_total_stock_value);
    echo '</b></td>
                    </tr>    
            ';
    echo '</table>';
};