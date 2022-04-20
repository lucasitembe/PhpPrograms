<?php 
    include './includes/connection.php';
    include './includes/header.php';
    
    if (isset($_SESSION['Storage_Info'])) {
        $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        $Current_Store_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
    }
?>

<style>
    input{
        padding:6px !important
    }
</style>

<a href="storageandsupplyreports.php?StorageAndSupplyReports=StorageAndSupplyReportsThisPage" class='art-button-green'>BACK</a>

<br><br>

<fieldset>
    <table width='100%'>
        <tr>
            <td width='25%' style="padding:6px"><input type="text" placeholder="Start Date" id="date" name="Date_From" style="text-align: center;"></td>
            <td width='25%' style="padding:6px"><input type="text" placeholder="End Date" id="date2" style="text-align: center;"></td>
            <td width='25%' style="padding:6px"><input type="text" placeholder="Search Item" id="search_name" onkeyup="filterAdjustmentReport()" style="text-align: center;"></td>
            <td width='25%' style="padding:6px">
                <select name="" id="reason_id" style="width: 100%;padding:8px">
                    <option value="all">Select Reason</option>
                    <?php 
                        $output = "";
                        $query_adjustment_reasons = mysqli_query($conn,"SELECT * FROM tbl_adjustment") or die(mysqli_errno($conn));
                        while($details = mysqli_fetch_assoc($query_adjustment_reasons)){
                            $output .= "<option value='".$details['id']."'>".$details['name']."</option>";
                        }
                        echo $output;
                    ?>
                </select>
            </td>
            <td style="padding:8px">
                <a href="#" class='art-button-green' onclick="filterAdjustmentReport()">FILTER</a>
            </td>
        </tr>
    </table>
</fieldset>

<fieldset style="height: 500px;overflow:scroll">
    <legend>ADJUSTMENT REPORT</legend>

    <table width='100%'>
        <thead>
            <tr style="background-color: #ddd;">
                <td width='10%' style="padding: 6px;"><center>S/N</center></td>
                <td width='30%' style="padding: 6px;">ITEM NAME</td>
                <td width='12%' style="padding: 6px;">TRANSACTION DATE</td>
                <td width='12%' style="padding: 6px;">ADJUSTMENT TYPE</td>
                <td width='12%' style="padding: 6px;"><center>QUANTITY</center></td>
                <td width='12%' style="padding: 6px;">EMPLOYEE</td>
                <td width='12%' style="padding: 6px;">DESCRIPTION</td>
            </tr>
        </thead>

        <tbody id="display-data">
            <tr style="background-color: #fff;">
                <td style="padding: 8px;" colspan="8"><center>Enter Date Range</center></td>
            </tr>
        </tbody>
    </table>
</fieldset>

<script>
    function filterAdjustmentReport() { 
        var start_date = $('#date').val();
        var end_date = $('#date2').val();
        var search_name = $('#search_name').val();
        var reason_id = $('#reason_id').val();

        if(start_date == "" || start_date == null || end_date == "" || end_date == null){
            alert('Please fill in date range first');
        }else{
            $('#display-data').html('<tr><td style="text-align: center;font-size:15px;color:red;padding:10px" colspan="8"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></td></tr>');
            $.get('adjustment_reasons_core.php',{
                filter_by_items:'filter_by_items',
                start_date : start_date,
                end_date:end_date,
                search_name:search_name,
                Current_Store_ID:'<?=$Current_Store_ID?>',
                reason_id:reason_id
            },(data) => {
                $('#display-data').html(data);
            });
        }
    }
</script>

<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>

<?php 
    include './includes/footer.php';
?>