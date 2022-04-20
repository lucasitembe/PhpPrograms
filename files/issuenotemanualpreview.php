<?php
    include_once("./includes/header.php");
    include_once("./functions/issuenotemanual.php");
    include_once("./functions/department.php");
    include_once("./functions/employee.php");

    if (isset($_GET['IssueManual_ID'])) {
        $IssueManual_ID = $_GET['IssueManual_ID'];
    }

    if ($IssueManual_ID > 0) {
        $IssueManual = Get_Issue_Note_Manual($IssueManual_ID);
        $Store_Issuing_Name = Get_Sub_Department_Name($IssueManual['Store_Issuing']);
        $Store_Need_Name = Get_Sub_Department_Name($IssueManual['Store_Need']);
        $Employee_Issuing_Name = Get_Employee($IssueManual['Employee_Issuing'])['Employee_Name'];
        $Employee_Receiving_Name = Get_Employee($IssueManual['Employee_Receiving'])['Employee_Name'];
        $IssueManualItem_List = Get_Issue_Note_Manual_Items($IssueManual_ID);
    }else{
        $IssueManual = '';
        $Store_Issuing_Name = '';
        $Store_Need_Name = '';
        $Employee_Issuing_Name = '';
        $Employee_Receiving_Name = '';
        $IssueManualItem_List = '';
    }
    include_once("issuenotemanual_navigation.php");
?>
<br/><br/>
<style>
    table,tr,td{
        
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<fieldset>
    <table width=100%>
        <tr>
            <td width=15% style="text-align: right;"><b>Issue Note N<u>o</u></b></td>
            <td><input type="text" readonly="readonly" value="<?php echo $IssueManual['Issue_ID']; ?>"></td>
            <td width=15% style="text-align: right;"><b>Issue Date</b></td>
            <td><input type="text" readonly="readonly" value="<?php echo $IssueManual['Issue_Date_And_Time']; ?>"></td>
            <td width=15% style="text-align: right;"><b>Store Issuing</b></td>
            <td><input type="text" readonly="readonly" value="<?php echo $Store_Issuing_Name; ?>"></td>
        </tr>
        <tr>
            <td style="text-align: right;"><b>Cost Center</b></td>
            <td><input type="text" readonly="readonly" value="<?php echo $Store_Need_Name; ?>"></td>
            <td style="text-align: right;"><b>Issued By</b></td>
            <td><input type="text" readonly="readonly" value="<?php echo $Employee_Issuing_Name; ?>"></td>
            <td style="text-align: right;"><b>Received By</b></td>
            <td><input type="text" readonly="readonly" value="<?php echo $Employee_Receiving_Name; ?>"></td>
        </tr>
        <tr>
            <td style="text-align: right;"><b>IV Number</b></td>
            <td><input type="text" readonly="readonly" value="<?php echo $IssueManual['IV_Number']; ?>"></td>
        </tr>
    </table>
</fieldset>
<fieldset>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <a href="issuenotemanual_preview.php?IssueManual_ID=<?php echo $IssueManual['Issue_ID']; ?>&IssueNoteManualPreview=IssueNoteManualPreviewThisPage" class="art-button-green" target="_blank">PREVIEW REPORT</a>
            </td>
        </tr>
    </table>
</fieldset>
<fieldset style='overflow-y: scroll; height: 350px; background-color: white;' id='Items_Fieldset'>
    <table width='100%'>
        <tr><td colspan="10"><hr></td></tr>
        <tr>
            <td width=3%><b>SN</b></td>
            <td width=40% style='text-align: left;'><b>ITEM NAME</b></td>
            <td width=10% style='text-align: right;'><b>QUANTITY REQUIRED</b></td>
            <td width=10% style='text-align: right;'><b>QUANTITY ISSUED</b></td>
            <td width=10% style='text-align: right;'><b>BUYING PRICE</b></td>
            <td width=10% style='text-align: right;'><b>TOTAL&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
            <td width=10% style='text-align: right;'><b>SELLING PRICE&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
            <td width=10% style='text-align: right;'><b>TOTAL&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
            <td width=10% style='text-align: right;'><b>PROFIT/LOSS&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
            <td style='text-align: left;'><b>REMARK</b></td>
        </tr>
        <tr><td colspan="10"><hr></td></tr>
<?php
    $i = 0; $Grand_Total_buy = 0;$Grand_Total_sell=0;
        foreach($IssueManualItem_List as $IssueManualItem) {
?>
            <tr>
                <td><?php echo ++$i; ?></td>
                <td style='text-align: left;'><?php echo $IssueManualItem['Product_Name']; ?></td>
                <td style='text-align: right;'><?php echo $IssueManualItem['Quantity_Required']; ?></td>
                <td style='text-align: right;'><?php echo $IssueManualItem['Quantity_Issued']; ?></td>
                <td style='text-align: right;'><?php echo number_format($IssueManualItem['Buying_Price']); ?></td>
                <td style='text-align: right;'><?php echo number_format($IssueManualItem['Buying_Price'] * $IssueManualItem['Quantity_Issued']); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td style='text-align: right;'><?php echo number_format($IssueManualItem['Selling_Price']); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td style='text-align: right;'><?php echo number_format($IssueManualItem['Selling_Price'] * $IssueManualItem['Quantity_Issued']); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td style='text-align: right;'><?php echo number_format(($IssueManualItem['Selling_Price'] * $IssueManualItem['Quantity_Issued'])-($IssueManualItem['Buying_Price'] * $IssueManualItem['Quantity_Issued'])); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td style='text-align: left;'><?php echo $IssueManualItem['Item_Remark']; ?></td>
            </tr>
<?php
            $Grand_Total_buy += ($IssueManualItem['Buying_Price'] * $IssueManualItem['Quantity_Issued']);
            $Grand_Total_sell += ($IssueManualItem['Selling_Price'] * $IssueManualItem['Quantity_Issued']);
        }
?>
        <tr><td colspan="10"><hr></td></tr>
        <tr><td colspan="5"><b>GRAND TOTAL</b>
            </td><td style="text-align: right;"><b><?php echo number_format($Grand_Total_buy); ?>&nbsp;&nbsp;&nbsp;&nbsp;</b></td><td></td>
            </td><td style="text-align: right;"><b><?php echo number_format($Grand_Total_sell); ?>&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
            </td><td style="text-align: right;"><b><?php echo number_format($Grand_Total_sell-$Grand_Total_buy); ?>&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
            <td></td></tr>
    </table>
</fieldset>
<?php
    include("./includes/footer.php");
?>