<?php
    include_once("./functions/returnoutward.php");
    include_once("./functions/department.php");
    include_once("./functions/supplier.php");
    include_once("./functions/employee.php");

    if (isset($_GET['Outward_ID'])) {
        $Outward_ID = $_GET['Outward_ID'];
    }

    $htm = "";

    if ($Outward_ID > 0) {
        $Return_Outward = Get_Return_Outward($Outward_ID);
        $Store_Receiving_Name = Get_Sub_Department_Name($Return_Outward['Sub_Department_ID']);
        $Supplier_Name = Get_Supplier($Return_Outward['Supplier_ID'])['Supplier_Name'];
        $Employee_Name = Get_Employee($Return_Outward['Employee_ID'])['Employee_Name'];

        $Return_Outward_Item_List = Get_Return_Outward_Items($Outward_ID);

        $htm .= "<table width ='100%' height = '30px'>";
        $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
        $htm .= "<tr><td>&nbsp;</td></tr>";
        $htm .= "<tr> <td style='text-align: center;'><h2>RETURN OUTWARD DOCUMENT</h2></td> </tr>";
        $htm .= "</table>";

        $htm .= "<br/>";

        $htm .= "<table width=100%>";
        $htm .= "<tr>";
        $htm .= "<td width=20%><b>Document N<u>o</u> </b></td>";
        $htm .= "<td width=20%> {$Return_Outward['Outward_ID']} </td>";
        $htm .= "<td width=20%><b>Transaction Date</b></td>";
        $htm .= "<td> {$Return_Outward['Outward_Date']} </td>";
        $htm .= "</tr>";
        $htm .= "<tr>";
        $htm .= "<td width=20%><b>Store</b></td>";
        $htm .= "<td width=20%> {$Store_Receiving_Name} </td>";
        $htm .= "<td width=20%><b>Returning TO</b></td>";
        $htm .= "<td width=20%> {$Supplier_Name} </td>";
        $htm .= "</tr>";
        $htm .= "<tr>";
        $htm .= "<td width=20%><b>Posted By</b></td>";
        $htm .= "<td width=20%> {$Employee_Name} </td>";
        $htm .= "<td width=20%></td>";
        $htm .= "<td width=20%></td>";
        $htm .= "</tr>";
        $htm .= "</table>";

        $htm .= "<br/><br/><br/><br/>";

        $htm .= "<table id='returnoutward_items' style='border-collapse: collapse;' cellspacing=0 cellpadding=0  width='100%'>";
        $htm .= "<tr>";
        $htm .= "<td width=3%><b>Sn</b></td>";
        $htm .= "<td width=40% style='text-align: left;'><b>Item Name</b></td>";
        $htm .= "<td width=20% style='text-align: right;'><b>Quantity Returned</b></td>";
        $htm .= "<td width=20% style='text-align: left;'><b>&nbsp;&nbsp;&nbsp;&nbsp;Remark</b></td>";
        $htm .= "</tr>";

        $i = 1;
        foreach($Return_Outward_Item_List as $Return_Outward_Item) {
            $htm .= "<tr>";
            $htm .= "<td> {$i} </td>";
            $htm .= "<td style='text-align: left;'> {$Return_Outward_Item['Product_Name']} </td>";
            $htm .= "<td style='text-align: right;'> {$Return_Outward_Item['Quantity_Returned']} </td>";
            $htm .= "<td style='text-align: left;'> {$Return_Outward_Item['Item_Remark']} </td>";
            $htm .= "</tr>";
            $i++;
        }

        $htm .= "</table>";
        $htm .= "<br/><br/><br/><br/><br/><br/><br/><br/>";

        $htm .= "<table>";
        $htm .= "<tr>";
        $htm .= "<td style='text-align:left;width:30%'><b>Issuing Officer Sign : </b></td>";
        $htm .= "<td style='text-align:left;width:15%'><b>________________________________</b></td>";
        $htm .= "<td style='text-align:right;width:10%'></td>";
        $htm .= "<td style='text-align:left;width:30%'><b>Receiving Officer Sign : </b></td>";
        $htm .= "<td style='text-align:left;width:15%'><b>________________________________</b></td>";
        $htm .= "</tr>";
        $htm .= "<tr>";
        $htm .= "<td style='text-align:left;width:30%'><b>Issuing Officer : </b></td>";
        $htm .= "<td style='text-align:left;width:15%'><b> {$Employee_Issuing_Name} </b></td>";
        $htm .= "<td style='text-align:right;width:10%'></td>";
        $htm .= "<td style='text-align:left;width:30%'><b>Receiving Officer : </b></td>";
        $htm .= "<td style='text-align:left;width:15%'><b> {$Employee_Receiving_Name} </b></td>";
        $htm .= "</tr>";
        $htm .= "</table>";

        $htm .= "<link rel='stylesheet' href='css/fonts/fonts.css' />";
        $htm .= "<style>";
        $htm .= "body { font-family: 'Raleway_Medium'; }";
        $htm .= "table#returnoutward_items tr td { border: 1px solid #555; }";
        $htm .= "</style>";
    }
    include("./functions/makepdf.php");
?>