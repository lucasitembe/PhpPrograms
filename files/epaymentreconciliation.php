<?php
include("./includes/header.php");
include("./includes/connection.php");
$Title_Control = "False";
$Link_Control = 'False';
$Title = '';
$Transaction_Type = '';
$Date_From = '';
$Date_To = '';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ./index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    echo "<a href='epaymentcollectionreports.php?ePaymentCollectionReports=ePaymentCollectionReportsThisForm' class='art-button-green'>BACK</a>";
}
?>


<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>

<!-- get current date-->
<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
?>

<!-- get employee details-->
<?php
$select_Employee_Details = mysqli_query($conn,"select Employee_Name, Employee_ID from tbl_employee where Employee_Name = 'CRDB'") or die(mysqli_error($conn));
$nm = mysqli_num_rows($select_Employee_Details);
if ($nm > 0) {
    while ($row = mysqli_fetch_array($select_Employee_Details)) {
        $Employee_Name = $row['Employee_Name'];
        $Employee_ID = $row['Employee_ID'];
    }
} else {
    $Employee_Name = '';
    $Employee_ID = '';
}

$cashiers = '';
$select_cashier = mysqli_query($conn,"select DISTINCT(e.Employee_ID), Employee_Name from tbl_employee e JOIN tbl_bank_transaction_cache b ON e.Employee_ID=b.Employee_ID") or die(mysqli_error($conn));
$nm = mysqli_num_rows($select_cashier);
while ($row = mysqli_fetch_array($select_cashier)) {
    $Employee_Name = $row['Employee_Name'];
    $Employee_ID = $row['Employee_ID'];

    $cashiers .="<option value='" . $Employee_ID . "'>$Employee_Name</option>";
}
?><br/><br/>
<center>
    <fieldset>  
        <table width="100%">
            <tr>
                <td style="text-align:center">
                    <input type="text" autocomplete="off" value='' style='text-align: center;width:15%;display:inline' id="Date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" value=''  style='text-align: center;width:15%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                    <select id='Terminal_ID' class="select2-default" style='text-align: center;width:15%;display:inline'>
                        <option selected value="all">All Terminals</option>
                        <?php
                        $selectDoctor = mysqli_query($conn,"SELECT DISTINCT(Terminal_ID)  FROM tbl_bank_api_payments_details ORDER BY Terminal_ID ASC
                                ") or die(mysqli_error($conn));

                        while ($data = mysqli_fetch_array($selectDoctor)) {
                            ?>
                            <option value="<?php echo $data['Terminal_ID']; ?>"><?php echo $data['Terminal_ID']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <select id="employee_id" style='text-align: center;width:12%;display:inline'>
                        <option  value="all">All Cashiers</option>
                        <?php echo $cashiers; ?>
                    </select>
                    <input type="button" value="Filter" class="art-button-green" onclick="getpayments()">
                    <a href="#" id="preview" target="_blank" class="art-button-green">Preview</a>
                </td>
            </tr>

        </table>
    </fieldset>  
</center>
 <div align="center" style="display:none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
   
<fieldset >
    <legend align="right" style="background-color:#006400;color:white;padding:5px;"><b>ePayment Revenue Collections</b></legend>
    <div style='overflow-y: scroll;overflow-x: hidden; height: 380px; background-color: white;' id='Fieldset_Details'></div>
</fieldset>

<script>
    function getpayments() {
        var start_date = $('#Date_From').val();
        var end_date = $('#Date_To').val();
        var Terminal_ID = $('#Terminal_ID').val();
        var employee_id = $('#employee_id').val();

        if ((start_date != '' && end_date == '') || (end_date != '' && start_date == '')) {
            alert('Please enter both dates');
            exit;
        }
        
        var datastring = 'start_date=' + start_date + '&end_date=' + end_date + '&Terminal_ID=' + Terminal_ID + '&employee_id=' + employee_id;

        $('#preview').attr('href','epaymentreconciliation_print.php?'+datastring);
        $.ajax({
            method: "GET",
            url: "epaymentreconciliation_frame.php",
            data:datastring,
            beforeSend: function (xhr) {
                $('#progressStatus').show();
            },
            success: function (data) {
                $("#Fieldset_Details").html(data);
            }, complete: function (jqXHR, textStatus) {
                $('#progressStatus').hide();
            }, error: function (jqXHR, textStatus, errorThrown) {
                $('#progressStatus').hide();
            }
        });
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#myPatients').DataTable({
            "bJQueryUI": true

        });

        $('#Date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#Date_From').datetimepicker({value: '', step: 30});
        $('#Date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#Date_To').datetimepicker({value: '', step: 30});

        $('select').select2();

        $('#Search_Patient_number').keydown(function (e) {
            -1 !== $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) || /65|67|86|88/.test(e.keyCode) && (!0 === e.ctrlKey || !0 === e.metaKey) || 35 <= e.keyCode && 40 >= e.keyCode || (e.shiftKey || 48 > e.keyCode || 57 < e.keyCode) && (96 > e.keyCode || 105 < e.keyCode) && e.preventDefault()
        });
    });

</script>



<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="js/select2.min.js"></script>
<script src="css/jquery-ui.js"></script>
<?php
include("./includes/footer.php");
?>