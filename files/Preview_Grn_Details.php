<?php
@session_start();
include("./includes/connection.php");
$temp = 0;
if (isset($_GET['Grn_Open_Balance_ID'])) {
    $Grn_Open_Balance_ID = $_GET['Grn_Open_Balance_ID'];
} else {
    $Grn_Open_Balance_ID = '';
}


$canPakage = false;
$display = "style='display:none'";

if (isset($_SESSION['systeminfo']['enable_receive_by_package']) && $_SESSION['systeminfo']['enable_receive_by_package'] == 'yes') {
    $canPakage = true;
    $display = "";
}


$sql_select = mysqli_query($conn,"select gob.Grn_Open_Balance_ID, emp.Employee_Name, gob.Saved_Date_Time, gob.Grn_Open_Balance_Description, gob.Employee_ID, sd.Sub_Department_Name
								from tbl_grn_open_balance gob, tbl_employee emp, tbl_sub_department sd where
								emp.Employee_ID = gob.Supervisor_ID and 
								sd.Sub_Department_ID = gob.Sub_Department_ID and
								gob.Grn_Open_Balance_ID = '$Grn_Open_Balance_ID' and
								gob.Grn_Open_Balance_Status = 'saved' order by Grn_Open_Balance_ID desc limit 100") or die(mysqli_error($conn));
$num = mysqli_num_rows($sql_select);
if ($num > 0) {
    while ($row = mysqli_fetch_array($sql_select)) {
        //get employee prepared
        $Prep_Employee = $row['Employee_ID'];
        $sel = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Prep_Employee'") or die(mysqli_error($conn));
        $Pre_no = mysqli_num_rows($sel);
        if ($Pre_no > 0) {
            while ($dt = mysqli_fetch_array($sel)) {
                $Created_By = $dt['Employee_Name'];
            }
        } else {
            $Created_By = '';
        }

        $Saved_Date_Time = $row['Saved_Date_Time'];
        $Grn_Open_Balance_Description = $row['Grn_Open_Balance_Description'];
        $Employee_Name = $row['Employee_Name']; //supervisor name
        $Sub_Department_Name = $row['Sub_Department_Name'];
    }
} else {
    $Saved_Date_Time = '';
    $Grn_Open_Balance_Description = '';
    $Employee_Name = ''; //supervisor name
    $Sub_Department_Name = '';
}
?>

<style>
    table,tr,td{
        //border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<table width = 100% border=0>
    <tr><td colspan="7"><hr></td></tr>
    <tr>
        <td width=10% style="text-align: right;"><b>Grn Number ~ </b></td><td><?php echo $Grn_Open_Balance_ID; ?></td>
        <td width=13% style="text-align: right;"><b>Date ~ </b></td><td width="15%"><?php echo $Saved_Date_Time; ?></td>
        <td style="text-align: right;"><b>Supervisor Name ~ </b></td><td><?php echo $Employee_Name; ?></td>
    </tr>
    <tr><td colspan="7"><hr></td></tr>
    <tr>
        <td style="text-align: right;"><b>Prepared By ~ </b></td><td width="20%"><?php echo $Created_By; ?></td>
        <td style="text-align: right;"><b>Location ~ </b></td><td width="20%"><?php echo $Sub_Department_Name; ?></td>
        <td width=13% style="text-align: right;"><b>Grn Description ~ </b></td><td><?php echo $Grn_Open_Balance_Description; ?></td>
    </tr>
    <tr><td colspan="7"><hr></td></tr>
    <tr><td colspan="7" style="text-align: right;">
            <a href="Preview_Grn_Details_Report.php?Grn_Open_Balance_ID=<?php echo $Grn_Open_Balance_ID; ?>" target="_blank"  class="art-button-green"  >PREVIEW REPORT</a>
        </td></tr>
</table><br/><br/>

<fieldset style='overflow-y: scroll; height: 320px; background-color: white;' id='Grn_Fieldset_List'>
    <table width="100%">
        <tr><td colspan="11"><hr></td></tr>
        <tr>
            <td width="5%"><b>SN</b></td>
            <td width="36%"><b>Item Name</b></td>
            <td width="9%"><b>UOM</b></td>
            <td width="10%" style="text-align: right;"><b>Previous Quantity</b></td>
            <td <?php echo $display ?>width="6%" style="text-align: right;"><b>Containers</b></td>
            <td <?php echo $display ?> width="8%" style="text-align: right;"><b>Items per Container</b></td>
            <td width="6%" style="text-align: right;"><b>Quantity</b></td>
            <td width="7%" style="text-align: right;"><b>Buying Price</b></td>
            <td width="9%" style="text-align: right;">&nbsp;&nbsp;&nbsp;<b>Manufacture Date</b></td>
            <td width="9%" style="text-align: right;"><b>Expire Date</b></td>
            <td width="15%" style="text-align: right;"><b>Amount</b></td>
        </tr>
        <tr><td colspan="11"><hr></td></tr>

        <?php
        $select = mysqli_query($conn,"select i.Product_Name, obi.Item_Quantity, obi.Buying_Price, obi.Manufacture_Date, obi.Expire_Date, ibh.Item_Balance, obi.Container_Qty, obi.Items_Per_Container, i.Unit_Of_Measure
							from tbl_grn_open_balance_items obi, tbl_items i, tbl_items_balance_history ibh where 
							i.Item_ID = obi.Item_ID and
							ibh.Grn_Open_Balance_ID = obi.Grn_Open_Balance_ID and
							ibh.Item_ID = obi.Item_ID and
							obi.Grn_Open_Balance_ID = '$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        $Amount = 0;
        $Grand_Total = 0;
        if ($num > 0) {
            while ($data = mysqli_fetch_array($select)) {
                $Amount = $data['Item_Quantity'] * $data['Buying_Price'];
                ?>
                <tr>
                    <td width="5%"><?php echo ++$temp; ?></td>
                    <td><?php echo $data['Product_Name']; ?></td>
                    <td><?php echo $data['Unit_Of_Measure']; ?></td>
                    <td style="text-align: right;"><?php echo $data['Item_Balance']; ?></td>
                    <td  <?php echo $display ?> style="text-align: right;"><?php echo $data['Container_Qty']; ?></td>
                    <td  <?php echo $display ?> style="text-align: right;"><?php echo $data['Items_Per_Container']; ?></td>
                    <td style="text-align: right;"><?php echo $data['Item_Quantity']; ?></td>
                    <td style="text-align: right;"><?php echo number_format($data['Buying_Price'],2); ?></td>
                    <td style="text-align: right;">&nbsp;&nbsp;&nbsp;<?php echo $data['Manufacture_Date']; ?></td>
                    <td style="text-align: right;"><?php echo $data['Expire_Date']; ?></td>
                    <td style="text-align: right;"><?php echo number_format($Amount,2); ?></td>
                </tr>
                <?php
                $Grand_Total = $Grand_Total + $Amount;
                $Amount = 0;
            }
        }
        ?>
        <tr><td colspan="11"><hr></td></tr>
     <tr><td colspan='10' style='text-align: right;'><b>Grand Total &nbsp;&nbsp;&nbsp;&nbsp;</b><b><?php echo number_format($Grand_Total) ?></b></td></tr>
    </table>
</fieldset>

