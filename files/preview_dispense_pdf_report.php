<?php session_start();
include ("./includes/connection.php");
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
function get_item_buying_price($Item_ID, $Dispense_Date_Time) {
    global $conn;
    $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    $select_list_of_buying_price_result = mysqli_query($conn, "SELECT Approval_Date_Time,Selling_Price,Last_Buying_Price FROM tbl_requisition req INNER JOIN tbl_requisition_items reqi ON req.Requisition_ID=reqi.Requisition_ID WHERE Store_Need='$Sub_Department_ID' AND Item_ID='$Item_ID' AND req.Requisition_Status='Received' ORDER BY Approval_Date_Time DESC") or die(mysqli_error($conn));
    if (mysqli_num_rows($select_list_of_buying_price_result) > 0) {
        while ($buying_price_rows = mysqli_fetch_assoc($select_list_of_buying_price_result)) {
            $Approval_Date_Time = $buying_price_rows['Approval_Date_Time'];
            $Selling_Price = $buying_price_rows['Selling_Price'];
            $Last_Buying_Price = $buying_price_rows['Last_Buying_Price'];
            if ($Dispense_Date_Time < $Approval_Date_Time) {
            } else {
                if ($Selling_Price == 0) {
                    return $Last_Buying_Price;
                }
                if (isset($_GET['buying_selling_price']) && $_GET['buying_selling_price'] == "original_buying_price") {
                    return $Last_Buying_Price;
                } else {
                    return $Selling_Price;
                }
            }
        }
    }
    return "not_seted";
}
$select = mysqli_query($conn, "select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Sub_Department_Name = $data['Sub_Department_Name'];
    }
} else {
    $Sub_Department_Name = '';
}
$htm = "<table width ='100%' height = '30px'>";
$htm.= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
$htm.= "<tr><td style='text-align:center;'>Despensed Summery Report From {$Start_Date} To {$End_Date}</td></tr>";
$htm.= "<tr><td><b>Location:</b> {$Sub_Department_Name}</td></tr>";
$htm.= "</table><br/>";
$htm.= "<table width ='100%'  border='1' style='border-collapse: collapse; ' cellpadding=5 cellspacing=10>";
$htm.= "<tr>";
$htm.= "<td>SN</td>";
$htm.= "<td>ITEM CODE</td>";
$htm.= "<td style='text-align:center;'>ITEM NAME</td>";
$htm.= "<td>QUANTITY DISPENSED</td>";
$htm.= "<td>BALANCE</td>";
$htm.= "<td>TOTAL BUYING PRICE</td>";
$htm.= "<td>TOTAL SELLING PRICE</td>";
$htm.= "<td>PROFIT/LOSS</td>";
$htm.= "<td>TOTAL STOCK VALUE</td>";
$htm.= "</tr>";
$temp = 1;
$worksheetTitle = '';
if (isset($_GET['Search_Value'])) {
    $temp = 1;
    $total_items = 0;
    if (isset($_SESSION['Pharmacy'])) {
        $Sub_Department_Name = $_SESSION['Pharmacy'];
    } else {
        $Sub_Department_Name = '';
    }
    if (isset($sponsorID) && $sponsorID != 'All') {
        $result = mysqli_query($conn, "select ilc.Dispense_Date_Time,i.Item_ID, i.Product_Name,ilc.price,Last_Buy_Price,i.Product_Code From tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc left join tbl_items i on i.Item_ID=ilc.Item_ID  where pp.Patient_Payment_ID = ilc.Patient_Payment_ID and pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = pp.Registration_ID and ilc.Check_In_Type = 'Pharmacy' and ilc.status = 'dispensed' and pr.Sponsor_ID='$sponsorID' and i.Product_Name like '%$Search_Value%' and  ilc.Sub_Department_ID = '$Sub_Department_ID' group by i.Item_ID order by i.Product_Name ") or die(mysqli_error($conn));
    } else {
        $result = mysqli_query($conn, "select ilc.Dispense_Date_Time,i.Item_ID, i.Product_Name,ilc.price,Last_Buy_Price,i.Product_Code FROM tbl_items i,tbl_item_list_cache ilc
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
            $Individual_Details = mysqli_query($conn, "select ilc.Dispense_Date_Time,ilc.Price,i.Product_Name, ilc.Quantity, ilc.Edited_Quantity From tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc left join tbl_items i on i.Item_ID=ilc.Item_ID  where pp.Patient_Payment_ID = ilc.Patient_Payment_ID and pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = pp.Registration_ID and 
                            ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and ilc.Check_In_Type = 'Pharmacy' and ilc.status = 'dispensed' and pr.Sponsor_ID='$sponsorID' and ilc.Item_ID = '$Item_ID' and ilc.Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        } else {
            $Individual_Details = mysqli_query($conn, "select ilc.Dispense_Date_Time,ilc.Price,i.Product_Name, ilc.Quantity, ilc.Edited_Quantity
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
            $Last_Buy_Price = get_item_buying_price($Item_ID, $Dispense_Date_Time);
            if ($Last_Buy_Price == "not_seted") {
                $Last_Buy_Price = $row2['Last_Buy_Price'];
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
        }
        $htm.= "<tr><td>{$temp}</td><td>{$row['Product_Code']}</td><td style='text-align:right;'>{$Product_Name}</td><td style='text-align:center;'>{$total_items}</td><td style='text-align:right;'>" . number_format(floor($Item_Balance)) . "</td><td style='text-align:right;'>" . number_format(floor($total_buying_price)) . "</td><td style='text-align:right;'>" . number_format(floor($total_selling_price)) . "</td><td style='text-align:right;'>" . number_format(floor(($total_selling_price - $total_buying_price))) . "</td><td style='text-align:right;'>" . number_format(floor(($Item_Balance * $total_buying_price))) . "</td></tr>";
        $grand_total_buying_price+= ($total_buying_price);
        $grand_total_selling_price+= $total_selling_price;
        $grand_total_profit_or_loss+= ($total_selling_price - $total_buying_price);
        $grand_total_total_stock_value+= ($Item_Balance * $total_buying_price);
        $temp++;
        $Edited_Quantity = 0;
        $Quantity = 0;
        $total_items = 0;
        $categoryRow++;
        $i++;
    }
    $htm.= "<tr><td colspan='5'>GRAND TOTAL</td><td style='text-align:right;'>" . number_format(floor($grand_total_buying_price)) . "</td><td style='text-align:right;'>" . number_format(floor($grand_total_selling_price)) . "</td><td style='text-align:right;'>" . number_format(floor($grand_total_profit_or_loss)) . "</td><td style='text-align:right;'>" . number_format(floor($grand_total_total_stock_value)) . "</td></tr>";
} else {
    $temp = 1;
    $total_items = 0;
    if (isset($_SESSION['Pharmacy'])) {
        $Sub_Department_Name = $_SESSION['Pharmacy'];
    } else {
        $Sub_Department_Name = '';
    }
    if (isset($sponsorID) && $sponsorID != 'All') {
        $result = mysqli_query($conn, "select ilc.Dispense_Date_Time,i.Item_ID, i.Product_Name,ilc.price,Last_Buy_Price,i.Product_Code From tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc left join tbl_items i on i.Item_ID=ilc.Item_ID  where pp.Patient_Payment_ID = ilc.Patient_Payment_ID and pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = pp.Registration_ID and ilc.Check_In_Type = 'Pharmacy' and ilc.status = 'dispensed' and pr.Sponsor_ID='$sponsorID' and ilc.Sub_Department_ID = '$Sub_Department_ID' group by i.Item_ID order by i.Product_Name ") or die(mysqli_error($conn));
    } else {
        $result = mysqli_query($conn, "select ilc.Dispense_Date_Time,i.Item_ID, i.Product_Name,ilc.price,Last_Buy_Price,i.Product_Code FROM tbl_items i,tbl_item_list_cache ilc
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
            $Individual_Details = mysqli_query($conn, "select ilc.Dispense_Date_Time,ilc.Price,i.Product_Name, ilc.Quantity, ilc.Edited_Quantity From tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc left join tbl_items i on i.Item_ID=ilc.Item_ID  where pp.Patient_Payment_ID = ilc.Patient_Payment_ID and pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = pp.Registration_ID and 
                            ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and ilc.Check_In_Type = 'Pharmacy' and ilc.status = 'dispensed' and pr.Sponsor_ID='$sponsorID' and ilc.Item_ID = '$Item_ID' and ilc.Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        } else {
            $Individual_Details = mysqli_query($conn, "select ilc.Dispense_Date_Time,ilc.Price,i.Product_Name, ilc.Quantity, ilc.Edited_Quantity
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
            $Last_Buy_Price = get_item_buying_price($Item_ID, $Dispense_Date_Time);
            if ($Last_Buy_Price == "not_seted") {
                $Last_Buy_Price = @$row2['Last_Buy_Price'];
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
        }
        $htm.= "<tr><td>{$temp}</td><td>{$row['Product_Code']}</td><td style='text-align:right;'>{$Product_Name}</td><td style='text-align:center;'>" . number_format(floor($total_items)) . "</td><td style='text-align:right;'>" . number_format(floor($Item_Balance)) . "</td><td style='text-align:right;'>" . number_format(floor($total_buying_price)) . "</td><td style='text-align:right;'>" . number_format(floor($total_selling_price)) . "</td><td style='text-align:right;'>" . number_format(floor(($total_selling_price - $total_buying_price))) . "</td><td style='text-align:right;'>" . number_format(floor(($Item_Balance * $total_buying_price))) . "</td></tr>";
        $grand_total_buying_price+= ($total_buying_price);
        $grand_total_selling_price+= $total_selling_price;
        $grand_total_profit_or_loss+= ($total_selling_price - $total_buying_price);
        $grand_total_total_stock_value+= ($Item_Balance * $total_buying_price);
        $temp++;
        $Edited_Quantity = 0;
        $Quantity = 0;
        $total_items = 0;
    }
    $htm.= "<tr><td colspan='5'>GRAND TOTAL</td><td style='text-align:right;'>" . number_format(floor($grand_total_buying_price)) . "</td><td style='text-align:right;'>" . number_format(floor($grand_total_selling_price)) . "</td><td style='text-align:right;'>" . number_format(floor($grand_total_profit_or_loss)) . "</td><td style='text-align:right;'>" . number_format(floor($grand_total_total_stock_value)) . "</td></tr>";
}
$htm.= "</table>";
$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
include ("MPDF/mpdf.php");
$mpdf = new mPDF('s', 'A4-L');
$mpdf->SetFooter('Printed By ' . ucwords(strtolower($Employee_Name)) . '  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
$mpdf->WriteHTML($stylesheet, 1);
$mpdf->WriteHTML($htm);
$mpdf->Output('mpdf.pdf', 'I');