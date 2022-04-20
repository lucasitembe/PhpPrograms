<?php
include("./includes/header.php");
include("./includes/connection.php");

//get employee id 
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = '';
}

//get employee name
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = '';
}

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

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='storageandsupplyreports.php?StorageAndSupplyReports=StorageAndSupplyReportsThisPage' class='art-button-green'>BACK</a>";
    }
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

<br/><br/>
<fieldset>
    <table width="100%">
        <tr>
            <td width="18%" style='text-align: center;'>
                <input type="text" name="Start_Date" id="date" placeholder="~~~ ~~~ Start Date ~~~ ~~~" style="text-align: center; width: 15%">
                <input type="text" name="End_Date" id="date2" placeholder="~~~ ~~~ End Date ~~~ ~~~" style="text-align: center;; width: 15%">
                <input type="button" name="Filter" id="Filter" value="FILTER" class="art-button-green" onclick="getItemsListFiltered('', '')">
                <a href="expiredItemsnotpreview.php" name="Preview" id="Preview" value="PREVIEW" class="art-button-green" target="_blank">PREVIEW</a>
                
                <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup="getItemsListFiltered(this.value, 'itm')" placeholder='~~~ ~~~ Enter Item Name ~~~ ~~~' style="text-align: center;width: 25%">
                <input type='text' id='Search_batch' name='Search_batch' autocomplete='off' onkeyup="getItemsListFiltered(this.value, 'grn')" placeholder='~~~  Enter Batch No (GRN) ~~~' style="text-align: center;width: 15%">
                
            </td>
        </tr>
    </table>
</fieldset>
<fieldset style='background-color:white;overflow-y: scroll; height: 380px;' id='Previous_Fieldset_List'>
    <legend style="background-color:#006400;color:white;padding:8px;" align=right><b>List of expired items ~ <?php
            if (isset($_SESSION['Storage'])) {
                echo $_SESSION['Storage'];
            }
            ?></b></legend>
    <?php
    include './expiredItemsnotification_frame.php';
    ?>
</fieldset>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>

<script type='text/javascript'>
                    $(document).ready(function () {
                        $('select').select2();
                    });
</script>
<script>
    function getItemsListFiltered(vl, filter) {
        var Start_Date = $('#date').val();
        var End_Date = $('#date2').val();
        var itemtype = $('#itemtype').val();
        var Search_Value = $('#Search_Value').val();
        var Search_batch = $('#Search_batch').val();

         if (filter == 'itm') {
            $('#Search_batch').val('');
            Search_Value=vl;
        }else if (filter == 'grn') {
            $('#Search_Value').val('');
            Search_batch=vl;
        }
        
        var datastring='Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Search_Value=' + Search_Value + '&Search_batch=' + Search_batch;
       
        $('#Preview').attr('href', 'expiredItemsnotpreview.php?'+datastring);

        $.ajax({
            type: 'GET',
            url: 'expiredItemsnotification_frame.php',
            data: datastring,
            beforeSend: function (xhr) {
                $("#progressStatus").show();
            },
            success: function (html) {
                $("#Previous_Fieldset_List").html(html);
            }, complete: function () {
                $("#progressStatus").hide();
            }, error: function (html, jjj) {
                alert(html);
            }
        });

    }
</script>

<?php
include('./includes/footer.php');
?>