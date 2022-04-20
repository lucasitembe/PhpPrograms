<?php 
    include("./includes/header.php");
    include "store/store.interface.php";

    if (!isset($_SESSION['userinfo'])) {
        @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if (isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
        if ($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes') { header("Location: ./index.php?InvalidPrivilege=yes"); }
    } else {
        @session_destroy();header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Store = new StoreInterface();
    $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    $Current_Store_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
?>
<a href="adjustment.php" class="art-button-green">BACK</a>

<br><br>
<input type="hidden" id="Sub_Department_ID" value="<?=$Current_Store_ID?>">
<input type="hidden" id="Current_Store_Name" value="<?=$Current_Store_Name?>">
<fieldset style="text-align: center;">
    <center>
        <table width='60%'>
            <tr>
                <td width='31.6%'><input type="text" id='Start_Date' placeholder="Start Date" style="padding: 6px;text-align:center"></td>
                <td width='31.6%'><input type="text" id='End_Date' placeholder="End_Date" style="padding: 6px;text-align:center"></td>
                <td width='31.6%'>
                    <select id="document_status" onchange="loadAdjustmentDocument()" style="padding: 6px;width:100%">
                        <option value="all">All Document</option>
                        <option value="saved">Saved</option>
                        <option value="pending">Pending</option>
                    </select>
                </td>
                <td width='5%'>
                    <a href="#" onclick="loadAdjustmentDocument()" class="art-button-green">FILTER</a>
                </td>
            </tr>
        </table>
    <center>
</fieldset>

<fieldset style="height: 600px;overflow-y:scroll">
    <legend style="text-align: right;">ADJUSTMENT ~ <?=strtoupper($Current_Store_Name)?></legend>
    <table width='100%'>
        <tr style="background-color: #ddd;">
            <td style="padding: 10px;font-weight:500;text-align:center" width='5%'>S/N</td>
            <td style="padding: 10px;font-weight:500;text-align:center" width='19%'>ADJUSTMENT NUMBER</td>
            <td style="padding: 10px;font-weight:500;" width='19%'>ADJUSTMENT DATE</td>
            <td style="padding: 10px;font-weight:500;" width='19%'>ADJUSTMENT OFFICER</td>
            <td style="padding: 10px;font-weight:500;" width='19%'>ADJUSTMENT LOCATION</td>
            <td style="padding: 10px;font-weight:500;text-align:center" width='19%'>ACTION</td>
        </tr>

        <tbody id="display_data"></tbody>
    </table>
</fieldset>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#Start_Date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
    });
    $('#Start_Date').datetimepicker({value: '', step: 01});

    $('#End_Date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
    });
    $('#End_Date').datetimepicker({value: '', step: 01});
</script>

<script>
    $(document).ready(() => {
        loadAdjustmentDocument();
    });

    function loadAdjustmentDocument() {  
        var Sub_Department_ID = $('#Sub_Department_ID').val();
        var Current_Store_Name = $('#Current_Store_Name').val();
        var document_status = $('#document_status').val();
        var Start_Date = $('#Start_Date').val();
        var End_Date = $('#End_Date').val();

        $.ajax({
            type: "GET",
            url: "store/store.common.php",
            data: {
                Sub_Department_ID:Sub_Department_ID,
                Current_Store_Name:Current_Store_Name,
                Start_Date:Start_Date,
                document_status:document_status,
                End_Date:End_Date,
                load_adjustment_document_:'load_adjustment_document_'
            },
            success: (response) => {
                $('#display_data').html(response);
            }
        });
    }
</script>

<?php include("./includes/footer.php"); ?>