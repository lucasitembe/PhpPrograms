<?php
include("./includes/header.php");
include("./includes/connection.php");
$requisit_officer = $_SESSION['userinfo']['Employee_Name'];
$Temp = 0;

$canPakage = false;
$display = "style='display:none'";

if (isset($_SESSION['systeminfo']['enable_receive_by_package']) && $_SESSION['systeminfo']['enable_receive_by_package'] == 'yes') {
    $canPakage = true;
    $display = "";
}

if (isset($_SESSION['Grn_ID'])) {
    $Grn_ID = $_SESSION['Grn_ID'];
} else {
    $Grn_ID = 0;
}

$Insert_Status = 'fasle';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
        if ($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (!isset($_SESSION['Storage_Info'])) {
    header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='Session_Control_Grn_Without_Perchase_Order.php?Status=New' class='art-button-green'>NEW GRN</a>";
        echo "<a href='previousgrnwithoutpurchaseorder.php?PreviousGrnWithoutPurchaseOrder=PreviousGrnWithoutPurchaseOrderThisPage' class='art-button-green'>PREVIOUS GRN</a>";
    }
}
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='goodreceivednote.php?GoodReceivingNote=GoodReceivingNoteThisPage' class='art-button-green'>BACK</a>";
    }
}
?>

<?php
//get sub department id
if (isset($_SESSION['Storage_Info'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    $Sub_Department_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
}
?>


<?php
//get employee name and id
//employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

//employee name
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = '';
}
?>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
    td:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>

<br/><br/>

<?php
//get grn details
$select = mysqli_query($conn,"SELECT
                                gpo.Supervisor_ID, gpo.Grn_Date_And_Time, sd.Sub_Department_Name,gpo.Supplier_ID,
                                sp.Supplier_Name, gpo.Debit_Note_Number, gpo.Invoice_Number, gpo.Delivery_Date,gpo.lpo_number
						    FROM
                                tbl_grn_without_purchase_order gpo, tbl_supplier sp, tbl_sub_department sd
                            WHERE
                                gpo.Sub_Department_ID = sd.Sub_Department_ID AND
                                sp.Supplier_ID = gpo.Supplier_ID AND
                                Grn_ID = '$Grn_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Grn_Date_And_Time = $data['Grn_Date_And_Time'];
        $Supervisor_ID = $data['Supervisor_ID'];
        $Supplier_Name = $data['Supplier_Name'];
        $Sub_Department_Name = $data['Sub_Department_Name'];
        $Debit_Note_Number = $data['Debit_Note_Number'];
        $Invoice_Number = $data['Invoice_Number'];
        $Delivery_Date = $data['Delivery_Date'];
        $lpoNumber = $data['lpo_number'];
        // $Supplier_ID = $data['Supplier_ID'];
    }
} else {
    $Grn_Date_And_Time = '';
    $Supervisor_ID = '';
    $Supplier_Name = '';
    $Sub_Department_Name = '';
    $Debit_Note_Number = '';
    $Invoice_Number = '';
    $Delivery_Date = '';
}

//get supervisor name
$select = mysqli_query($conn,"SELECT Employee_Name from tbl_employee where Employee_ID = '$Supervisor_ID'") or die(mysqli_error($conn));
$no = mysqli_num_rows($select);
if ($no > 0) {
    while ($row = mysqli_fetch_array($select)) {
        $Supervisor_Name = $row['Employee_Name'];
    }
} else {
    $Supervisor_Name = '';
}
?>
<fieldset>
    <legend style="background-color:#006400;color:white;padding:5px;" align="right"><?php if (isset($_SESSION['Storage_Info'])) {
    echo ucwords(strtolower($Sub_Department_Name));
} ?>, grn without purchase order</legend>
    <fieldset>
        <center>
            <table width=100%>
                <tr>
                    <td width='10%' style='text-align: right;'>Supplier Name</td>
                    <td width='26%'>
                        <input type="text" value="<?php echo $Supplier_Name; ?>" readonly="readonly">
                    </td>
                    <td width='10%' style='text-align: right;'>GRN Number</td>
                    <td width='15%'>
                        <input type='text' name='Grn_Number'  id='Grn_Number' readonly='readonly' value='<?php echo $Grn_ID; ?>'>
                    </td>
                    <td width='10%' style='text-align: right;'>GRN Date</td>
                    <td width='15%'>
                        <input type='text' name='Receiver_Name'  id='Receiver_Name' readonly='readonly' value='<?php echo $Grn_Date_And_Time; ?>'>
                    </td>
                </tr>
                <tr>
                    <td width='10%' style='text-align: right;'>Debit Note Number</td>
                    <td width='26%'>
                        <input type="text" value="<?php echo $Debit_Note_Number; ?>" readonly="readonly">
                    </td>
                    <td width='10%' style='text-align: right;'>Invoice Number</td>
                    <td width='15%'>
                        <input type="text" value="<?php echo $Invoice_Number; ?>" readonly="readonly">
                    </td>
                    <td width='10%' style='text-align: right;'>Delivery Date</td>
                    <td width='15%'>
                        <input type="text" value="<?php echo $Delivery_Date; ?>" readonly="readonly">
                    </td>
                </tr>
                <tr>
                  <td width='10%' style='text-align: right;'>LPO Number</td>
                  <td width='15%'>
                      <input type='text' name='Receiver_Name'  id='Receiver_Name' readonly='readonly' value='<?php echo ucwords(strtolower($lpoNumber)); ?>'>
                  </td>
                    <td width='10%' style='text-align: right;'>Receiver Name</td>
                    <td width='15%'>
                        <input type='text' name='Receiver_Name'  id='Receiver_Name' readonly='readonly' value='<?php echo ucwords(strtolower($Employee_Name)); ?>'>
                    </td>
                    <td width='10%' style='text-align: right;'>Supervisor Name</td>
                    <td width='15%'>
                        <input type='text' name='Receiver_Name'  id='Receiver_Name' readonly='readonly' value='<?php echo ucwords(strtolower($Supervisor_Name)); ?>'>
                    </td>
                    <td style='text-align: right;' colspan="2">
                        <a href="previewgrnwithoutpurchaseorderreport.php?Grn_ID=<?php echo $Grn_ID; ?>" target="_blank"  id="Preview_Report" class="art-button-green" >PREVIEW REPORT</a>
                    </td>
                </tr>
            </table>
        </center>
    </fieldset>

    <fieldset style='overflow-y: scroll; height: 400px;' id="Items_Fieldset_List">
        <center>
            <table width=100%>
                <tr><td colspan="10"><hr></td></tr>
                <tr>
                    <td width=4% style="text-align: center;">Sn</td>
                    <td>Item Name</td>
                    <td  <?php echo $display ?> width=7% style="text-align: center;">Containers</td>
                    <td  <?php echo $display ?> width=10% style="text-align: right;">Items per Container</td>
                    <td width=7% style="text-align: right;">Quantity</td>
                    <td width=7% style="text-align: right;">Price</td>
                    <td width=7% style="text-align: right;">Sub Total</td>
                    <td width=10% style="text-align: right;">Expire Date&nbsp;&nbsp;</td>
                </tr>
                <tr><td colspan="10"><hr></td></tr>

                <?php
                $select_order_items = mysqli_query($conn,"SELECT itm.Product_Name, gpo.Quantity_Required, gpo.Price, gpo.Container_Qty,
										gpo.Items_Per_Container, gpo.Expire_Date
										from tbl_grn_without_purchase_order_items gpo, tbl_items itm where
									    itm.Item_ID = gpo.Item_ID and 
										gpo.Grn_ID ='$Grn_ID'") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select_order_items);
                if ($no > 0) {
                    while ($row = mysqli_fetch_array($select_order_items)) {
                        ?>
                        <tr><td><input type='text' readonly='readonly' value='<?php echo ++$Temp; ?>' style='text-align: center;'></td>
                            <td><input type='text' readonly='readonly' value='<?php echo $row['Product_Name']; ?>' title='<?php echo $row['Product_Name']; ?>'></td>
                            <td  <?php echo $display ?> >
                                <input type='text' id='Container_<?php echo $row['Purchase_Cache_ID']; ?>' readonly='readonly' value='<?php echo $row['Container_Qty']; ?>' style='text-align: right;' oninput="Update_Quantity('<?php echo $row['Purchase_Cache_ID']; ?>')">
                            </td>
                            <td  <?php echo $display ?> >
                                <input type='text' id='Items_<?php echo $row['Purchase_Cache_ID']; ?>' readonly='readonly' value='<?php echo $row['Items_Per_Container']; ?>' style='text-align: right;' oninput="Update_Quantity('<?php echo $row['Purchase_Cache_ID']; ?>')">
                            </td>
                            <td>
                                <input type='text' id='QR<?php echo $row['Purchase_Cache_ID']; ?>' readonly='readonly' value='<?php echo $row['Quantity_Required']; ?>' style='text-align: right;' oninput="Update_Quantity2(this.value,<?php echo $row['Purchase_Cache_ID']; ?>)">
                            </td>
                            <td>
                                <input type='text' id='<?php echo $row['Purchase_Cache_ID']; ?>' readonly='readonly' name='<?php echo $row['Purchase_Cache_ID']; ?>' value='<?php echo $row['Price']; ?>' style='text-align: right;' oninput="Update_Price(this.value,<?php echo $row['Purchase_Cache_ID']; ?>)">
                            </td>
                            <td><input type='text' name='Sub_Total<?php echo $row['Purchase_Cache_ID']; ?>' readonly='readonly' id='Sub_Total<?php echo $row['Purchase_Cache_ID']; ?>' readonly='readonly' value='<?php echo number_format($row['Quantity_Required'] * $row['Price']); ?>' style='text-align: right;'></td>
                            <td><input type='text' value='<?php echo $row['Expire_Date']; ?>  ' readonly='readonly' style="text-align: right;"></td>
                                <?php
                            }
                        }
                        ?>
            </table>
            </form>
        </center>
    </fieldset>

    <script type="text/javascript">
        function Preview_Report(Grn_ID) {
            var winClose = popupwindow('previewgrnwithoutpurchaseorderreport.php?Grn_ID=' + Grn_ID, 'GRN DETAILS', 1200, 500);
        }

        function popupwindow(url, title, w, h) {
            var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
            var wTop = window.screenTop ? window.screenTop : window.screenY;

            var left = wLeft + (window.innerWidth / 2) - (w / 2);
            var top = wTop + (window.innerHeight / 2) - (h / 2);
            var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
            return mypopupWindow;
        }
    </script>


    <?php
    include("./includes/footer.php");
    ?>
