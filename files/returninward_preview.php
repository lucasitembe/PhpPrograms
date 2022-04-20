<?php
    include_once("./functions/returninward.php");
    include_once("./functions/department.php");
    include_once("./functions/employee.php");

    if (isset($_GET['Inward_ID'])) {
        $Inward_ID = $_GET['Inward_ID'];
    }

    $htm = ""; 

    if ($Inward_ID > 0) {
        $Return_Inward = Get_Return_Inward($Inward_ID);
        $Store_Receiving_Name = Get_Sub_Department_Name($Return_Inward['Store_Sub_Department_ID']);
        $Store_Returning_Name = Get_Sub_Department_Name($Return_Inward['Return_Sub_Department_ID']);
        $Employee_Name = Get_Employee($Return_Inward['Employee_ID'])['Employee_Name'];

        $Return_Inward_Item_List = Get_Return_Inward_Items($Inward_ID);

        $htm .= "<table width ='100%' height = '30px'>";
        $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
        $htm .= "<tr><td>&nbsp;</td></tr>";
        $htm .= "<tr> <td style='text-align: center;'><h2>RETURN INWARD DOCUMENT</h2></td> </tr>";
        $htm .= "</table>";

        $htm .= "<br/>";

        $htm .= "<table width=100%>";
        $htm .= "<tr>";
        $htm .= "<td width=20%><b>Document N<u>o</u> </b></td>";
        $htm .= "<td width=20%> {$Return_Inward['Inward_ID']} </td>";
        $htm .= "<td width=20%><b>Transaction Date</b></td>";
        $htm .= "<td> {$Return_Inward['Inward_Date']} </td>";
        $htm .= "</tr>";
        $htm .= "<tr>";
        $htm .= "<td width=20%><b>Store Receiving</b></td>";
        $htm .= "<td width=20%> {$Store_Receiving_Name} </td>";
        $htm .= "<td width=20%><b>Returning From</b></td>";
        $htm .= "<td width=20%> {$Store_Returning_Name} </td>";
        $htm .= "</tr>";
        $htm .= "<tr>";
        $htm .= "<td width=20%><b>Posted By</b></td>";
        $htm .= "<td width=20%> {$Employee_Name} </td>";
        $htm .= "<td width=20%></td>";
        $htm .= "<td width=20%></td>";
        $htm .= "</tr>";
        $htm .= "</table>";

        $htm .= "<br/><br/><br/><br/>";

        $htm .= "<table id='returninward_items' style='border-collapse: collapse;' cellspacing=0 cellpadding=0  width='100%'>";
        $htm .= "<tr>";
        $htm .= "<td width=3%><b>Sn</b></td>";
        $htm .= "<td width=40% style='text-align: left;'><b>Item Name</b></td>";
        $htm .= "<td width=20% style='text-align: right;'><b>Quantity Returned</b></td>";
        $htm .= "<td width=20% style='text-align: left;'><b>&nbsp;&nbsp;&nbsp;&nbsp;Remark</b></td>";
        $htm .= "</tr>";

        $i = 1;
        foreach($Return_Inward_Item_List as $Return_Inward_Item) {
            $htm .= "<tr>";
            $htm .= "<td> {$i} </td>";
            $htm .= "<td style='text-align: left;'> {$Return_Inward_Item['Product_Name']} </td>";
            $htm .= "<td style='text-align: right;'> {$Return_Inward_Item['Quantity_Returned']} </td>";
            $htm .= "<td style='text-align: left;'> {$Return_Inward_Item['Item_Remark']} </td>";
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
        $htm .= "table#returninward_items tr td { border: 1px solid #555; }";
        $htm .= "</style>";
    }
    include("./functions/makepdf.php");
?>