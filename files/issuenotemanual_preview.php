<?php
    include_once("./functions/issuenotemanual.php");
    include_once("./functions/department.php");
    include_once("./functions/employee.php");

    if (isset($_GET['IssueManual_ID'])) {
        $IssueManual_ID = $_GET['IssueManual_ID'];
    }

    $htm = "";

    if ($IssueManual_ID > 0) {
        $IssueManual = Get_Issue_Note_Manual($IssueManual_ID);
        $Store_Issuing_Name = Get_Sub_Department_Name($IssueManual['Store_Issuing']);
        $Store_Need_Name = Get_Sub_Department_Name($IssueManual['Store_Need']);
        $Employee_Issuing_Name = Get_Employee($IssueManual['Employee_Issuing'])['Employee_Name'];
        $Employee_Receiving_Name = Get_Employee($IssueManual['Employee_Receiving'])['Employee_Name'];

        $IssueManualItem_List = Get_Issue_Note_Manual_Items($IssueManual_ID);

        $Created_Date_And_Time = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Created_Date_And_Time FROM tbl_issuesmanual WHERE Issue_ID = '$IssueManual_ID'"))['Created_Date_And_Time'];

        $htm .= "<table width ='100%' height = '30px'>";
        $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
        $htm .= "<tr> <td style='text-align: center;'><span style='font-size: small;'>ISSUE NOTE MANUAL</span></td></tr>";
        $htm .= "</table>";
        $htm .= "<br/>";

        $htm .= "<table width=100%>";
        $htm .= "<tr>";
        $htm .= "<td width=20%><b><span style='font-size: small;'>Issue N<u>o</u> </span></b></td>";
        $htm .= "<td width=20%> <span style='font-size: small;'>{$IssueManual['Issue_ID']} </span></td>";
        $htm .= "<td width=20%><span style='font-size: small;'><b>Issue Date</b></span></td>";
        $htm .= "<td><span style='font-size: small;'> {$IssueManual['Issue_Date_And_Time']} </span></td>";
        $htm .= "</tr>";
        $htm .= "<tr>";
        $htm .= "<td width=20%><b><span style='font-size: small;'>Store Issuing</b></span></td>";
        $htm .= "<td width=20%><span style='font-size: small;'> {$Store_Issuing_Name} </span></td>";
        $htm .= "<td width=20%><b><span style='font-size: small;'>Cost Center</span></b></td>";
        $htm .= "<td width=20%> <span style='font-size: small;'>{$Store_Need_Name} </span></td>";
        $htm .= "</tr>";
        $htm .= "<tr>";
        $htm .= "<td width=20%><b><span style='font-size: small;'>Issued By</span></b></td>";
        $htm .= "<td width=20%> <span style='font-size: small;'>{$Employee_Issuing_Name} </span></td>";
        $htm .= "<td width=20%><b><span style='font-size: small;'>Received By</span></b></td>";
        $htm .= "<td width=20%><span style='font-size: small;'> {$Employee_Receiving_Name}</span></td>";
        $htm .= "</tr>";
        $htm .= "<tr>";
        $htm .= "<td width=20%><b><span style='font-size: small;'>IV Number</span></b></td>";
        $htm .= "<td width=20%> <span style='font-size: small;'>{$IssueManual['IV_Number']}</span></td>";
        $htm .= "<td width=20%><b><span style='font-size: small;'>Documented Date</span></b></td>";
        $htm .= "<td width=20%> <span style='font-size: small;'>{$Created_Date_And_Time}</span></td>";
        $htm .="</tr>";
        $htm .= "</table><br/>";

        $htm .= "<table width='100%' border=1 style='border-collapse: collapse;'>";
        $htm .= "<thead><tr>";
        $htm .= "<td width=4%><b><span style='font-size: small;'>Sn</span></b></td>";
        $htm .= "<td width=40% style='text-align: left;'><b><span style='font-size: small;'>Item Name</span></b></td>";
        $htm .= "<td width=15% style='text-align: right;'><b><span style='font-size: small;'>Qty Required</span></b></td>";
        $htm .= "<td width=15% style='text-align: right;'><b><span style='font-size: small;'>Qty Issued</span></b></td>";
        $htm .= "<td width=15% style='text-align: right;'><b><span style='font-size: small;'>Buying Price</span></b></td>";
        $htm .= "<td width=15% style='text-align: right;'><b><span style='font-size: small;'>Total</span></b></td>";
        $htm .= "<td width=15% style='text-align: right;'><b><span style='font-size: small;'>Selling Price</span></b></td>";
        $htm .= "<td width=15% style='text-align: right;'><b><span style='font-size: small;'>Total</span></b></td>";
        $htm .= "<td width=15% style='text-align: right;'><b><span style='font-size: small;'>Profit/Loss</span></b></td>";
        $htm .= "</tr></thead>";

        $i = 1; $Grand_Total_buy = 0;$Grand_Total_sell = 0;
        foreach($IssueManualItem_List as $IssueManualItem) {
            $htm .= "<tr>";
            $htm .= "<td> <span style='font-size: small;'>{$i} </span></td>";
            $htm .= "<td style='text-align: left;'> <span style='font-size: small;'>{$IssueManualItem['Product_Name']} </span></td>";
            $htm .= "<td style='text-align: right;'> <span style='font-size: small;'>{$IssueManualItem['Quantity_Required']} </span></td>";
            $htm .= "<td style='text-align: right;'> <span style='font-size: small;'>{$IssueManualItem['Quantity_Issued']} </span></td>";
            $htm .= "<td style='text-align: right;'> <span style='font-size: small;'>".number_format($IssueManualItem['Buying_Price'])." </span></td>";
            $htm .= "<td style='text-align: right;'> <span style='font-size: small;'>".number_format($IssueManualItem['Buying_Price'] * $IssueManualItem['Quantity_Issued'])."</span></td>";
            $htm .= "<td style='text-align: right;'> <span style='font-size: small;'>".number_format($IssueManualItem['Selling_Price'])."</span></td>";
            $htm .= "<td style='text-align: right;'> <span style='font-size: small;'>".number_format($IssueManualItem['Selling_Price'] * $IssueManualItem['Quantity_Issued'])."</span></td>";
            $htm .= "<td style='text-align: right;'> <span style='font-size: small;'>".number_format(($IssueManualItem['Selling_Price'] * $IssueManualItem['Quantity_Issued'])-($IssueManualItem['Buying_Price'] * $IssueManualItem['Quantity_Issued']))."</span></td>";
            $htm .= "</tr>";
            $Grand_Total_buy += ($IssueManualItem['Buying_Price'] * $IssueManualItem['Quantity_Issued']);
            $Grand_Total_sell += ($IssueManualItem['Selling_Price'] * $IssueManualItem['Quantity_Issued']);
            $i++;
        }
        $htm .= "<tr><td colspan=5><b><span style='font-size: small;'>GRAND TOTAL</span></b></td>
                    <td style='text-align: right;'><b><span style='font-size: small;'>".number_format($Grand_Total_buy)."</span></b></td><td></td>
                    <td style='text-align: right;'><b><span style='font-size: small;'>".number_format($Grand_Total_sell)."</span></b></td>
                    <td style='text-align: right;'><b><span style='font-size: small;'>".number_format($Grand_Total_sell-$Grand_Total_buy)."</span></b></td>
                </tr>";
        $htm .= "</table><br/><br/>";

        $htm .= "<table width=100%>";
        $htm .= "<tr>";
        $htm .= "<td style='text-align:left;width:25%'><b><span style='font-size: small;'>Issuing Officer Sign : </span></b></td>";
        $htm .= "<td style='text-align:left;width:25%'>________________________________</td>";
        $htm .= "<td style='text-align:left;width:25%'><b><span style='font-size: small;'>Receiving Officer Sign : </span></b></td>";
        $htm .= "<td style='text-align:left;width:25%'>________________________________</td>";
        $htm .= "</tr>";
        $htm .= "<tr>";
        $htm .= "<td style='text-align:left;'><b><span style='font-size: small;'>Issuing Officer : </b></td>";
        $htm .= "<td style='text-align:left;'><b> <span style='font-size: small;'>{$Employee_Issuing_Name} </span></b></td>";
        $htm .= "<td style='text-align:left;'><b><span style='font-size: small;'>Receiving Officer : </b></td>";
        $htm .= "<td style='text-align:left;'><b> <span style='font-size: small;'>{$Employee_Receiving_Name} </span></b></td>";
        $htm .= "</tr>";
        $htm .= "</table>";
        
    }
    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','A4', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>