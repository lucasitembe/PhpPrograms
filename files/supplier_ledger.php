<?php
    include("./includes/connection.php");
    include("./includes/header.php");

    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
	if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo'])){
        if(isset($_GET['From'])){
            $From = mysqli_real_escape_string($conn,$_GET['From']);
        }else{
            $From = "";
        }

        echo "<a href='generalledgercenter.php' class='art-button-green'>BACK</a>";
         }

    include_once("./functions/department.php");
    include_once("./functions/items.php");
    include_once("./functions/scripts.php");
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
?>

<br/><br/>

<style>
	/*table,tr,td{
		border-collapse:collapse !important;
		border:none !important;
	}
	tr:hover{
		background-color:#eeeeee;
		cursor:pointer;
	}*/
</style>
<fieldset>
    <legend>Supplier Ledger</legend>
<div style='background-color:#fff;'>
    <center>
        <div>
            <table>
                <tr><td><b>Start Date:</b></td><td><input type='text' name='Start_Date' id='date' required='required' style='text-align: center;' placeholder='Start Date' readonly='readonly'</td><td><b>End Date: </b></td><td><input type='text' name='End_Date' id='date2' required='required' style='text-align: center;' placeholder='End Date' readonly='readonly'></td><td><b>Supplier: </b></td><td><select name='Supplier_ID' id='Supplier_ID' required='required'>
                            <option selected='selected'></option>
                            <?php
                            $sql = mysqli_query($conn,"select Supplier_Name, Supplier_ID from tbl_supplier") or die(mysqli_error($conn));
                            $num = mysqli_num_rows($sql);
                            if ($num > 0) {
                                while ($row = mysqli_fetch_array($sql)) {
                                    ?>
                                    <option value='<?php echo $row['Supplier_ID']; ?>'><?php echo $row['Supplier_Name']; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select></td><td><input type='submit' name='filter' value='FILTER' class='art-button-green' onclick="Filter_Ledger_Details();"></td></tr>
            </table>
        </div>
        <div id='ledger_items_display'>
        <table width='90%'>
        <?php
            $brought_forward=0;
            $today=date('Y-m-d');
            //balance without purchase order
            $withoutp_bf_balance=mysqli_query($conn,"SELECT SUM(gpoi.Container_Qty * gpoi.Items_Per_Container * gpoi.Price)AS total_amount FROM tbl_grn_without_purchase_order_items gpoi, tbl_grn_without_purchase_order gpo WHERE gpoi.Grn_ID=gpo.Grn_ID AND date(gpo.Grn_Date_And_Time) < date('$today') ") or die(mysqli_error($conn));
            //balance with(against) purchase order
            $againstp_bf_balance=mysqli_query($conn,"SELECT SUM(poi.Quantity_Received * poi.Price) as total_amount FROM tbl_purchase_order_items poi, tbl_purchase_order po WHERE po.Purchase_Order_ID=poi.Purchase_Order_ID AND poi.Grn_Status='RECEIVED' AND date(po.Sent_Date) < date('$today')") or die(mysqli_error($conn));
            $total_voucher_amount=mysqli_query($conn,"SELECT SUM(v.amount) total_amount FROM  tbl_voucher v WHERE v.voucher_date < '$today' AND payee_type='supplier'") or die(mysqli_error($conn));

            if((mysqli_num_rows($withoutp_bf_balance) > 0 ) && (mysqli_num_rows($total_voucher_amount) > 0) && (mysqli_num_rows($againstp_bf_balance) > 0)){
                $brought_forward=(mysqli_fetch_assoc($total_voucher_amount)['total_amount']-(mysqli_fetch_assoc($withoutp_bf_balance)['total_amount'] + mysqli_fetch_assoc($againstp_bf_balance)['total_amount']));
            }

        ?>
        <tr style='background-color:gray;color:white;'><th colspan='8' style='text-align:right;'>B/F: <?=number_format($brought_forward)?></th></tr>
        <tr style='background-color:gray;color:white;'><th>SN</th><th>GRN No/Voucher No</th><th>Supplier</th><th>Prepared Date</th><th>Narration</th><th>Debt</th><th>Payments</th><th>Balance</th></tr>
        <?php
            $count=1;
            $results=mysqli_query($conn,"SELECT v.voucher_ID AS voucher_order_ID,v.transaction_time AS time, v.Supplier_ID AS Supplier, date(v.transaction_time) AS trans_date, v.amount, v.narration, 'voucher' as selected_from FROM tbl_voucher v WHERE v.voucher_date = '$today' AND  payee_type='supplier' UNION ALL SELECT gpo.Grn_Purchase_Order_ID AS voucher_order_ID,gpo.Created_Date_Time AS time, gpo.supplier_id AS Supplier, gpo.Created_Date AS trans_date, 'not_found' AS amount,'any_notes' AS narration, 'against_order' as selected_from FROM tbl_grn_purchase_order gpo, tbl_purchase_order po WHERE gpo.Purchase_Order_ID=po.Purchase_Order_ID and gpo.Created_Date = '$today' UNION ALL SELECT gpo.Grn_ID AS voucher_order_ID,Grn_Date_And_Time AS time, gpo.Supplier_ID AS Supplier, date(gpo.Grn_Date_And_Time) AS trans_date, 'not_found' AS amount, 'any_notes' AS narration,'without_order' AS selected_from FROM tbl_grn_without_purchase_order gpo WHERE date(gpo.Grn_Date_And_Time) = '$today' ORDER BY time ASC ")or die(mysqli_error($conn));
            $debit_amount=0;
            $credit_amount=0;
        if(mysqli_num_rows($results) > 0){
            while($row=mysqli_fetch_assoc($results)){
                extract($row);
                $Supplier=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Supplier_Name FROM tbl_supplier WHERE Supplier_ID=$Supplier"))['Supplier_Name'];
                $report_type="";
                if($selected_from == 'voucher'){
                    $brought_forward+=$amount;
                    $credit_amount+=$amount;
                    $report_type="voucher";
                }else if($selected_from == 'without_order'){
                    $selected_amount=mysqli_query($conn,"SELECT SUM(gpoi.Container_Qty * gpoi.Items_Per_Container * gpoi.Price)AS total_amount FROM tbl_grn_without_purchase_order_items gpoi, tbl_grn_without_purchase_order gpo WHERE gpoi.Grn_ID=gpo.Grn_ID AND gpo.Grn_ID = $voucher_order_ID AND date(gpo.Grn_Date_And_Time) = date('$today') ") or die(mysqli_error($conn));
                    $amount=mysqli_fetch_assoc($selected_amount)['total_amount'];
                    $brought_forward-=$amount;
                    $debit_amount+=$amount;
                    $report_type="without_order";
                }else if($selected_from == 'against_order'){
                    $selected_amount=mysqli_query($conn,"SELECT SUM(poi.Quantity_Received * poi.Price) as total_amount FROM tbl_purchase_order_items poi, tbl_purchase_order po WHERE po.Purchase_Order_ID=poi.Purchase_Order_ID AND poi.Grn_Status='RECEIVED' AND po.Grn_Purchase_Order_ID = $voucher_order_ID AND date(po.Sent_Date) = date('$today')") or die(mysqli_error($conn));
                    $amount=mysqli_fetch_assoc($selected_amount)['total_amount'];
                    $brought_forward-=$amount;
                    $debit_amount+=$amount;
                    $report_type="against_order";
                }
                echo "<tr><td>".$count."</td><td onclick='view_invoice(\"{$voucher_order_ID}\",\"{$report_type}\")'><a href='#' style='display:block;'>".$voucher_order_ID."</a></td><td>{$Supplier}</td><td style='text-align:center;'>".$trans_date."</td><td>".$narration."</td><td  style='text-align: right;'>".number_format((($selected_from !='voucher')?$amount:0))."</td><td  style='text-align: right;'>".number_format((($selected_from=='voucher')?$amount:0))."</td><td  style='text-align: right;'>".number_format(($brought_forward))."</td><tr>";
                $count++;
            }
            echo "<tr><td colspan='5' style='text-align:center;'><b>Total Amount</b></td><td  style='text-align: right;'><b>".number_format($debit_amount)."</b></td><td  style='text-align: right;'><b>".number_format($credit_amount)."</b></td><td  style='text-align: right;'><b>".number_format(($brought_forward))."</b></td><tr>";
        }else{
            echo "<tr><td colspan='8' style='text-align:center;height:100px; line-height:50px; font-size:18px;'>No Data Found</td></tr>";
        }

        ?>
    </table>
    <br>
    </div>
    <input type="submit" name="preview" value="Preview" style="float:right;" onclick='Preview_Ledger_Details();' class='art-button-green'>
<div id="show_invoice" style='background-color:#fff;font-size:180x;'>

</div>
</center>
</div>
</fieldset>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
<script type="text/javascript">
    function view_invoice(Invoice_ID,report_type){
        $('#invoice_no').text(Invoice_ID);
        $.ajax({
            url:'ajax_display_voucher_details.php',
            type:'post',
            data:{Invoice_ID:Invoice_ID,report_type:report_type},
            success:function(results){
                $('#show_invoice').html(results);
                $("#show_invoice").dialog('open');
            }
        });
    }

    function Filter_Ledger_Details(){
        var Start_Date=$('#date').val();
        var End_Date=$('#date2').val();
        var Supplier_ID=$('#Supplier_ID').val();
        if(Start_Date.trim()==='' ||  End_Date.trim()===''){
            alert('Enter Start Date and End Date');
            return false;
        }

        $.ajax({
            url:'supplier_fetch_ledger_details.php',
            type:'post',
            data:{Start_Date:Start_Date,End_Date:End_Date,Supplier_ID:Supplier_ID},
            success:function(results){
                //alert(results);
                $('#ledger_items_display').html(results);
            }
        });
    }
    function Preview_Voucher(Voucher_ID){
        window.open('preview_voucher.php?Voucher_ID='+Voucher_ID+'&From=supplier_ledger');
    }
    function Preview_Ledger_Details(){
        var Start_Date=$('#date').val();
        var End_Date=$('#date2').val();
        var Supplier_ID=$('#Supplier_ID').val();
        if(Start_Date.trim()==='' ||  End_Date.trim()===''){
            alert('Enter Start Date and End Date');
            return false;
        }
        window.open('preview_supplier_ledger_details.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Supplier_ID='+Supplier_ID);
    }
</script>
<script type="text/javascript">
$(document).ready(function () {
        $("#show_invoice").dialog({autoOpen: false, width: '70%',height:'500', title: 'Voucher Details', modal: true, position: 'middle'});
        /*$('.numberTests').dataTable({
            "bJQueryUI": true
        });*/
    });
</script>
<script>
    $(document).ready(function () {
        $('select').select2();
    })
</script>
<?php
include("./includes/footer.php");
?>
