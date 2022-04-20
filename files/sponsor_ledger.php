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
<legend>Sponsor Ledger</legend>
<div style='background-color:#fff;'>
    <center>
        <div>
            <table>
                <tr><td><b>Start Date:</b></td><td><input type='text' name='Start_Date' id='date' required='required' style='text-align: center;' placeholder='Start Date' readonly='readonly'</td><td><b>End Date: </b></td><td><input type='text' name='End_Date' id='date2' required='required' style='text-align: center;' placeholder='End Date' readonly='readonly'></td><td><b>Sponsor: </b></td><td><select name='Sponsor_ID' id='Sponsor_ID' required='required'>
                            <option selected='selected'></option>
                            <?php
                            $sql = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_Sponsor
                                                                where Guarantor_Name <> 'CASH'") or die(mysqli_error($conn));
                            $num = mysqli_num_rows($sql);
                            if ($num > 0) {
                                while ($row = mysqli_fetch_array($sql)) {
                                    ?>
                                    <option value='<?php echo $row['Sponsor_ID']; ?>'><?php echo $row['Guarantor_Name']; ?></option>
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
            $select_bf_balance=mysqli_query($conn,"SELECT SUM(ospi.Price * ospi.Quantity) total_amount FROM tbl_other_sources_payment_item_list ospi, tbl_other_sources_payments osp WHERE osp.Payment_ID=ospi.Payment_ID AND ospi.remarks='voucher_payment' AND osp.Receipt_Date < '$today' ") or die(mysqli_error($conn));
            $total_invoice_amount=mysqli_query($conn,"SELECT SUM(inv.amount) total_amount FROM  tbl_invoice inv WHERE inv.invoice_date < '$today' ") or die(mysqli_error($conn));

            if((mysqli_num_rows($select_bf_balance) > 0 ) && (mysqli_num_rows($total_invoice_amount) > 0)){
                $brought_forward=(mysqli_fetch_assoc($select_bf_balance)['total_amount']-mysqli_fetch_assoc($total_invoice_amount)['total_amount']);
            }

        ?>
        <tr style='background-color:gray;color:white;'><th colspan='8' style='text-align:right;'>B/F: <?=number_format($brought_forward)?></th></tr>
        <tr style='background-color:gray;color:white;'><th>SN</th><th>Invoice No/Cheque No</th><th>Sponsor</th><th>Prepared Date</th><th>Narration</th><th>Debt</th><th>Payments</th><th>Balance</th></tr>
        <?php
            $count=1;
            $results=mysqli_query($conn,"SELECT inv.Invoice_ID, inv.sponsor_id AS Sponsor, inv.invoice_date, inv.narration, inv.amount, inv.trans_datetime as time, 'invoice' as selected_from FROM `tbl_invoice` inv WHERE inv.invoice_date = '$today' UNION ALL SELECT osp.Payment_ID as Invoice_ID, osp.Customer_ID AS Sponsor, osp.Receipt_Date as invoice_date, osp.narration, ospi.Price * ospi.Quantity as amount, ospi.Transaction_Date_And_Time as time, 'payments' as selected_from FROM tbl_other_sources_payment_item_list ospi, tbl_other_sources_payments osp where ospi.remarks='voucher_payment' and osp.Payment_ID=ospi.Payment_ID AND osp.Receipt_Date = '$today'  order by time asc ")or die(mysqli_error($conn));
            $debit_amount=0;
            $credit_amount=0;
        if(mysqli_num_rows($results) > 0){    
            while($row=mysqli_fetch_assoc($results)){
                extract($row);
                $Sponsor=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID=$Sponsor"))['Guarantor_Name'];
                $report_type="";
                if($selected_from == 'invoice'){
                    $brought_forward-=$amount;
                    $debit_amount+=$amount;
                    $report_type="invoice";
                }else if($selected_from == 'payments'){
                    $brought_forward+=$amount;
                    $credit_amount+=$amount;
                    $report_type="voucher";
                }
                echo "<tr><td>".$count."</td><td onclick='view_invoice(\"{$Invoice_ID}\",\"{$report_type}\")'><a href='#' style='display:block;'>".$Invoice_ID."</a></td><td>{$Sponsor}</td><td style='text-align:center;'>".$invoice_date."</td><td>".$narration."</td><td  style='text-align: right;'>".number_format((($selected_from=='invoice')?$amount:0))."</td><td  style='text-align: right;'>".number_format((($selected_from=='payments')?$amount:0))."</td><td  style='text-align: right;'>".number_format(($brought_forward))."</td><tr>";
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
            url:'ajax_display_invoice_details.php',
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
        var Sponsor_ID=$('#Sponsor_ID').val();
        if(Start_Date.trim()==='' ||  End_Date.trim()===''){
            alert('Enter Start Date and End Date');
            return false;
        }
        
        $.ajax({
            url:'fetch_ledger_details.php',
            type:'post',
            data:{Start_Date:Start_Date,End_Date:End_Date,Sponsor_ID:Sponsor_ID},
            success:function(results){
                //alert(results);
                $('#ledger_items_display').html(results);
            }
        });
    }
    function Preview_Invoice(Invoice_ID){
        window.open('preview_invoice.php?Invoice_ID='+Invoice_ID);
    }
    function Preview_Ledger_Details(){
        var Start_Date=$('#date').val();
        var End_Date=$('#date2').val();
        var Sponsor_ID=$('#Sponsor_ID').val();
        if(Start_Date.trim()==='' ||  End_Date.trim()===''){
            alert('Enter Start Date and End Date');
            return false;
        }
        window.open('preview_ledger_details.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Sponsor_ID='+Sponsor_ID);
    }
</script>
<script type="text/javascript">
$(document).ready(function () {
        $("#show_invoice").dialog({autoOpen: false, width: '70%',height:'500', title: 'Invoice Details', modal: true, position: 'middle'});
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