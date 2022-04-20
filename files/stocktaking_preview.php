<?php
    include_once("./functions/stocktaking.php");
    include_once("./functions/department.php");
    include_once("./functions/employee.php");

    if (isset($_GET['Stock_Taking_ID'])) {
        $Stock_Taking_ID = $_GET['Stock_Taking_ID'];
    }

    $htm = "";

    if ($Stock_Taking_ID > 0) {
        $Stock_Taking = Get_Stock_Taking($Stock_Taking_ID);
        $Stock_Taking_Location = Get_Sub_Department_Name($Stock_Taking['Sub_Department_ID']);
        $Stock_Taking_Officer = Get_Employee($Stock_Taking['Employee_ID'])['Employee_Name'];

        $Stock_Taking_List = Get_Stock_Taking_Items($Stock_Taking_ID);

        $htm .= "<table width ='100%' height = '30px'>";
        $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
        $htm .= "<tr><td>&nbsp;</td></tr>";
        $htm .= "<tr> <td style='text-align: center;'><h2>STOCK TAKING NOTE</h2></td> </tr>";
        $htm .= "</table>";

        $htm .= "<br/>";

        $htm .= "<table width=100%>";
        $htm .= "<tr>";
        $htm .= "<td width=20%><b>Stock Taking Number</b></td>";
        $htm .= "<td width=20%> {$Stock_Taking['Stock_Taking_ID']} </td>";
        $htm .= "<td width=20%><b>Stock Taking Date</b></td>";
        $htm .= "<td> {$Stock_Taking['Stock_Taking_Date']} </td>";
        $htm .= "</tr>";
        $htm .= "<tr>";
        $htm .= "<td width=20%><b>Stock Taking Location</b></td>";
        $htm .= "<td width=20%> {$Stock_Taking_Location} </td>";
        $htm .= "<td width=20%><b>Stock Taking Officer</b></td>";
        $htm .= "<td width=20%> {$Stock_Taking_Officer} </td>";
        $htm .= "</tr>";
        $htm .= "<tr>";
        $htm .= "<td width=20%><b>Stock Taking Description</b></td>";
        $htm .= "<td colspan=3> {$Stock_Taking['Stock_Taking_Description']} </td>";
        $htm .= "</tr>";
        $htm .= "</table>";

        $htm .= "<br/><br/><br/><br/>";

        $htm .= "<table id='stocktaking_items' style='border-collapse: collapse;' cellspacing=0 cellpadding=0  width='100%'>";
        $htm .= "<tr>";
        $htm .= "<td width=3%><b>Sn</b></td>";
        $htm .= "<td width=60% style='text-align: left;'><b>Item Name</b></td>";
        $htm .= "<td width=20% style='text-align: right;'><b>Under Quantity</b></td>";
        $htm .= "<td width=20% style='text-align: right;'><b>Over Quantity</b></td>";
        $htm .= "<td width=20% style='text-align: left;'><b>&nbsp;&nbsp;&nbsp;&nbsp;Remark</b></td>";
        $htm .= "</tr>";

        $i = 1;
        foreach($Stock_Taking_List as $Stock_Taking) {
            $htm .= "<tr>";
            $htm .= "<td> {$i} </td>";
            $htm .= "<td style='text-align: left;'> {$Stock_Taking['Product_Name']} </td>";
            $htm .= "<td style='text-align: right;'> {$Stock_Taking['Under_Quantity']} </td>";
            $htm .= "<td style='text-align: right;'> {$Stock_Taking['Over_Quantity']} </td>";
            $htm .= "<td style='text-align: left;'> {$Stock_Taking['Item_Remark']} </td>";
            $htm .= "</tr>";
            $i++;
        }

        $htm .= "</table>";
        $htm .= "<br/><br/><br/><br/><br/><br/><br/><br/>";

        $htm .= "<table>";
        $htm .= "<tr>";
        $htm .= "<td style='text-align:left;width:30%'><b>Stock Taking Officer Sign : </b></td>";
        $htm .= "<td style='text-align:left;width:15%'><b>________________________________</b></td>";
        $htm .= "<td style='text-align:right;width:10%'></td>";
        $htm .= "<td style='text-align:left;width:30%'></td>";
        $htm .= "<td style='text-align:left;width:15%'></td>";
        $htm .= "</tr>";
        $htm .= "<tr>";
        $htm .= "<td style='text-align:left;width:30%'><b>Stock Taking Officer : </b></td>";
        $htm .= "<td style='text-align:left;width:15%'><b> {$Stock_Taking_Officer} </b></td>";
        $htm .= "<td style='text-align:right;width:10%'></td>";
        $htm .= "<td style='text-align:left;width:30%'></td>";
        $htm .= "<td style='text-align:left;width:15%'></td>";
        $htm .= "</tr>";
        $htm .= "</table>";

        $htm .= "<link rel='stylesheet' href='css/fonts/fonts.css' />";
        $htm .= "<style>";
        $htm .= "body { font-family: 'Raleway_Medium'; }";
        $htm .= "table#stocktaking_items tr td { border: 1px solid #555; }";
        $htm .= "</style>";
    }
    include("./functions/makepdf.php");
?>