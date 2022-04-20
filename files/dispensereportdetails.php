<?php session_start();
include ("./includes/connection.php");
include ("calculate_buying_price.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
$temp = 1;
$Start_Date = '';
$End_Date;
$Item_ID = 0;
$sponsor = '';
$Last_Buy_Price = 0;
$sql_date_time = mysqli_query($conn, "select now() as Date_Time ") or die(mysqli_error($conn));
while ($date = mysqli_fetch_array($sql_date_time)) {
    $Current_Date_Time = $date['Date_Time'];
}
$Filter_Value = substr($Current_Date_Time, 0, 11);
if (isset($_GET['Start_Date'])) {
    $Start_Date = $_GET['Start_Date'];
} else {
    $Start_Date = $Filter_Value;
}
if (isset($_GET['End_Date'])) {
    $End_Date = $_GET['End_Date'];
} else {
    $End_Date = $Filter_Value;
}
if (isset($_SESSION['Storage'])) {
    $Sub_Department_Name = $_SESSION['Storage'];
} else {
    $Sub_Department_Name = '';
}
if (isset($_GET['Item_ID'])) {
    $Item_ID = $_GET['Item_ID'];
} else {
    $Item_ID = 0;
}

if (isset($_GET['average_price'])) {
    $Last_Buy_Price = $_GET['average_price'];
}
$buying_selling_price = $_GET['buying_selling_price'];
$sql_get_item = mysqli_query($conn, "SELECT Product_Name from tbl_items where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
$no = mysqli_num_rows($sql_get_item);
if ($no > 0) {
    while ($r = mysqli_fetch_array($sql_get_item)) {
        $Item_Name = $r['Product_Name'];
    }
} else {
    $Item_Name = '';
}
$filter = '';
if (isset($_GET['Sponsor']) && $_GET['Sponsor'] != '' && $_GET['Sponsor'] != 'All') {
    $Sponsor = $_GET['Sponsor'];
    $filter.= " and pc.Sponsor_ID='$Sponsor'";
};
echo '
<style>
		table,tr,td{
		border-collapse:collapse !important;
		border:none !important;
		
		}
	tr:hover{
	background-color:#eeeeee;
	cursor:pointer;
	}
 </style> 
    <center>
        ';
if (isset($_GET['Item_ID']) && isset($_GET['Start_Date']) && isset($_GET['End_Date'])) {;
    echo '        <table width=100%>
            <tr>
                <td style=\'text-align: right;\' width="8%"><b>Item Name : </b></td>
                <td>';
    echo $Item_Name;;
    echo '</b></td>
                <td width="10%" style=\'text-align: right;\'><b>Start Date : </b></td>
                <td><b>';
    if (isset($_GET['Start_Date'])) {
        echo $_GET['Start_Date'];
    };
    echo '</b></td>
                <td width="10%" style=\'text-align: right;\'><b>End Date : </b></td>
                <td><b>';
    if (isset($_GET['End_Date'])) {
        echo $_GET['End_Date'];
    };
    echo '</b></td>
            </tr>
        </table>
        ';
};
echo "</center>
    <fieldset  style='overflow-y: scroll; height: 670px;' id='Items_Fieldset'>
        <table width='100%'>
            <tr><td colspan='13'><hr></td></tr>
			<tr style='background-color:#006400;color:white'>
                <td width=3%><b>SN</b></td>
                <td width=12%><b>PATIENT NAME</b></td>
		<td width=7%><b>PATIENT NUMBER</b></td>
                <td width=7%><b>SPONSOR</b></td>
                <td width=10%><b>PHONE NUMBER</b></td>
                <td width=10%><b>DISPENSED DATE & TIME</b></td>
                <td width=6% style='text-align: right;'><b>QUANTITY</b></td>
                <td width=7% style='text-align: right;'><b> BUYING PRICE</b></td>
                <td width=10% style='text-align: right;'><b>TOTAL BUYING PRICE</b></td>
                <td width=7% style='text-align: right;'><b>SELLING PRICE</b></td>
                <td width=10% style='text-align: right;'><b>TOTAL SELLING PRICE</b></td>
                <td width=10% style='text-align: right;'><b>PROFIT / LOSS</b></td>
                <td width=10% style='text-align: right;'><b>RECEIPT NUMBER</u></b></td>
            </tr>
            <tr><td colspan='13'><hr></td></tr>";

$Sub_Department_ID = $_SESSION['Pharmacy_ID'];
if (isset($_GET['Sub_Department_ID'])) {
    $Sub_Department_ID = $_GET['Sub_Department_ID'];
}


$sql_select = mysqli_query($conn, "SELECT ilc.Dispense_Date_Time,ilc.price,Last_Buy_Price,i.Product_Name, pr.Phone_Number,pc.Billing_Type, ts.Guarantor_Name, pr.Registration_ID, ilc.Dispense_Date_Time, pr.Patient_Name, ilc.Patient_Payment_ID, ilc.Quantity, ilc.Edited_Quantity from
                                            tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr,tbl_sponsor ts, tbl_items i where pr.Sponsor_ID = ts.Sponsor_ID and
                                            pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                            i.Item_ID = ilc.Item_ID and
                                            pr.Registration_ID = pc.Registration_ID and
                                            ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and
                                            ilc.Status = 'dispensed' and
                                            ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                            i.Item_ID = '$Item_ID' $filter") or die(mysqli_error($conn));
// $num_rows = mysqli_num_rows($sql_select);
$grand_total_buying_price = 0;
$grand_total_selling_price = 0;
$grand_total_profit_or_loss = 0;
$total_buying_price = 0;
$total_seling_price = 0;

    while ($row = mysqli_fetch_array($sql_select)) {
        $price = $row['price'];
        $Dispense_Date_Time = $row['Dispense_Date_Time'];
        // $Last_Buy_Price = get_item_buying_price($Item_ID, $Dispense_Date_Time, $Sub_Department_ID);
        // if ($Last_Buy_Price == "not_seted") {
        //     $Last_Buy_Price = $row['Last_Buy_Price'];
        // }
        $dispenced_quantity = 0;
        if ($row['Edited_Quantity'] != 0 && $row['Edited_Quantity'] != null && $row['Edited_Quantity'] != '') {
            $dispenced_quantity = $row['Edited_Quantity'];
        } else {
            $dispenced_quantity = $row['Quantity'];
        };


        if ($row['Edited_Quantity'] != 0 && $row['Edited_Quantity'] != null && $row['Edited_Quantity'] != '') {
            $quantity = $row['Edited_Quantity'];
        } else {
            $quantity = $row['Quantity'];
        }
        // echo $Dispense_Date_Time;
        echo "<tr>
                <td>".$temp."</td>
                <td>".$row['Patient_Name']."</td>
                <td>".$row['Registration_ID']."</td>
                <td>".$row['Guarantor_Name']."</td>
                <td>".$row['Phone_Number']."</td>
                <td>".$row['Dispense_Date_Time']."</td>
                <td style='text-align: right;'>".$quantity."</td>
                <td style='text-align: right;'>".number_format($Last_Buy_Price)."</td>
                <td style='text-align: right;'>".number_format($Last_Buy_Price * $dispenced_quantity)."</td>
                <td style='text-align: right;'>".number_format($price)."</td>
                <td style='text-align: right;'>".number_format($price * $dispenced_quantity)."</td>
                <td style='text-align: right;'>".number_format(($price * $dispenced_quantity) - ($Last_Buy_Price * $dispenced_quantity))."</td>
                <td style='text-align: right;'><a href='individualsummaryreport.php?Patient_Payment_ID=".$row['Patient_Payment_ID']."&IndividualSummaryReport=IndividualSummaryReportThisForm' target='_Blank' style='text-decoration: none;'><span style='color: #037CB0;'><b>".$row['Patient_Payment_ID']."</b></span></a></td>
            </tr>";
        $total_quantity+=$quantity;
        $total_buying_price+= $Last_Buy_Price;
        $total_seling_price+= $price;
        $grand_total_buying_price+= ($Last_Buy_Price * $dispenced_quantity);
        $grand_total_selling_price+= ($price * $dispenced_quantity);
        $grand_total_profit_or_loss+= (($price * $dispenced_quantity) - ($Last_Buy_Price * $dispenced_quantity));
        $temp++;
    }

echo '            <tr><td colspan="13"><hr></td></tr>
            <tr>
                <td colspan="6"><b>GRAND TOTAL</b></td>
                <td style=\'text-align: right;\'><b>';
                echo number_format($total_quantity);
                echo '</b></td>
                <td style=\'text-align: right;\'><b>';
echo number_format($total_buying_price);
echo '</b></td>
                <td style=\'text-align: right;\'><b>';
echo number_format($grand_total_buying_price);
echo '</b></td>
                <td style=\'text-align: right;\'><b>';
echo number_format($total_seling_price);
echo '</b></td>
                <td style=\'text-align: right;\'><b>';
echo number_format($grand_total_selling_price);
echo '</b></td>
                <td style=\'text-align: right;\'><b>';
echo number_format($grand_total_profit_or_loss);
echo '</b></td>
            </tr> 
    </table>
</fieldset>
';
$Sub_Department_ID = $_SESSION['Pharmacy_ID'];
$Sub_Department_ID = $_SESSION['Pharmacy_ID'];
if (isset($_GET['Sub_Department_ID'])) {
    $Sub_Department_ID = $_GET['Sub_Department_ID'];
}
echo "<input type='button' class='art-button-green' value='PREVIEW IN PDF' onclick='Patient_Pdf_Details(\"$Start_Date\",\"$End_Date\",\"$Item_ID\",\"$sponsorID\",\"$buying_selling_price\",\"$Sub_Department_ID\");'>";
echo "<input type='button' class='art-button-green' value='EXPORT TO EXCEL' onclick='Patient_Excel_Details(\"$Start_Date\",\"$End_Date\",\"$Item_ID\",\"$sponsorID\",\"$buying_selling_price\",\"$Sub_Department_ID\");'>";