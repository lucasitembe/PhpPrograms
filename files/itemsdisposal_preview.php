<?php
    include_once("./functions/itemsdisposal.php");
    include_once("./functions/department.php");
    include_once("./functions/employee.php");

    if (isset($_GET['Disposal_ID'])) {
        $Disposal_ID = $_GET['Disposal_ID'];
    }

    $htm = "";

    if ($Disposal_ID > 0) {
        $Disposal = Get_Items_Disposal($Disposal_ID);
        $Disposal_Location = Get_Sub_Department_Name($Disposal['Sub_Department_ID']);
        $Disposal_Officer = Get_Employee($Disposal['Employee_ID'])['Employee_Name'];

        $DisposalItem_List = Get_Items_Disposal_Items($Disposal_ID);
        $get_adjustment_reason = mysqli_fetch_assoc(mysqli_query($conn,"SELECT name FROM tbl_disposal AS td, tbl_adjustment AS ta WHERE ta.id = td.reason_for_adjustment AND td.Disposal_ID = $Disposal_ID"))['name'];


        $htm .= "<table width ='100%' height = '30px' style='font-family:arial'>";
        $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
        $htm .= "<tr><td>&nbsp;</td></tr>";
        $htm .= "<tr> <td style='text-align: center;'><h4>ADJUSTMENT</h4></td> </tr>";
        $htm .= "</table>";

        $htm .= "<br/>";

        $htm .= "<table width=100% style='font-family:arial'>";
        $htm .= "<tr>";
        $htm .= "<td width=20%><b>Number</b></td>";
        $htm .= "<td width=20%> {$Disposal['Disposal_ID']} </td>";
        $htm .= "<td width=20%><b>Date</b></td>";
        $htm .= "<td> {$Disposal['Disposed_Date']} </td>";
        $htm .= "</tr>";
        $htm .= "<tr>";
        $htm .= "<td width=20%><b>Location</b></td>";
        $htm .= "<td width=20%> {$Disposal_Location} </td>";
        $htm .= "<td width=20%><b>Officer</b></td>";
        $htm .= "<td width=20%> {$Disposal_Officer} </td>";
        $htm .= "</tr>";
        $htm .= "<tr>";
        $htm .= "<td width=20%><b>Description</b></td>";
        $htm .= "<td width=20%> {$Disposal['Disposal_Description']} </td>";
        $htm .= "<td width=20%><b>Reason</b></td>";
        $htm .= "<td width=20%> {$get_adjustment_reason} </td>";
        $htm .= "</tr>";
        $htm .= "</table>";

        $htm .= "<br/><br/>";

        $htm .= "<table id='issuenotemanual_items' style='border-collapse: collapse;font-family:arial' cellspacing=0 cellpadding=0  width='100%'>";
        $htm .= "<tr>";
        $htm .= "<td width=5% style='text-align: left;padding:5px'><b>Sn</b></td>";
        $htm .= "<td width=50% style='text-align: left;padding:5px'><b>Item Name</b></td>";
        $htm .= "<td width=15% style='text-align: left;padding:5px'><b>Batch No</b></td>";
        $htm .= "<td width=15% style='text-align: center;padding:5px'><b>Expire Date</b></td>";
        $htm .= "<td width=10% style='text-align: center;padding:5px'><b>Qty</b></td>";
        $htm .= "<td width=10% style='text-align: left;padding:5px'><b>&nbsp;&nbsp;&nbsp;&nbsp;Remark</b></td>";
        $htm .= "</tr>";

        $i = 1;
        foreach($DisposalItem_List as $DisposalItem) {
            $htm .= "<tr>";
            $htm .= "<td width=5% style='text-align: left;padding:5px'> {$i} </td>";
            $htm .= "<td style='text-align: left;padding:5px'> {$DisposalItem['Product_Name']} </td>";
            $htm .= "<td style='text-align: left;padding:5px'> {$DisposalItem['batch_no']} </td>";
            $htm .= "<td style='text-align: center;padding:5px'> {$DisposalItem['expire_date']} </td>";
            $htm .= "<td style='text-align: center;padding:5px'> {$DisposalItem['Quantity_Disposed']} </td>";
            $htm .= "<td style='text-align: left;padding:5px'> {$DisposalItem['Item_Remark']} </td>";
            $htm .= "</tr>";
            $i++;
        }

        $htm .= "</table>";
        $htm .= "<br/><br/><br/><br/><br/><br/>";

        $htm .= "<table>";
        $htm .= "<tr>";
        $htm .= "<td style='text-align:left;width:30%'><b>Issue Officer Sign : </b></td>";
        $htm .= "<td style='text-align:left;width:15%'><b>________________________________</b></td>";
        $htm .= "<td style='text-align:right;width:10%'></td>";
        $htm .= "<td style='text-align:left;width:30%'></td>";
        $htm .= "<td style='text-align:left;width:15%'></td>";
        $htm .= "</tr>";
        $htm .= "<tr>";
        $htm .= "<td style='text-align:left;width:30%'><b>Issue Officer : </b></td>";
        $htm .= "<td style='text-align:left;width:15%'><b> {$Disposal_Officer} </b></td>";
        $htm .= "<td style='text-align:right;width:10%'></td>";
        $htm .= "<td style='text-align:left;width:30%'></td>";
        $htm .= "<td style='text-align:left;width:15%'></td>";
        $htm .= "</tr>";
        $htm .= "</table>";




        $htm .= "<link rel='stylesheet' href='css/fonts/fonts.css' />";
        $htm .= "<style>";
        $htm .= "body { font-family: 'arial'; }";
        $htm .= "table#issuenotemanual_items tr td { border: 1px solid #555; }";
        $htm .= "</style>";
    }
    include("./functions/makepdf.php");
?>