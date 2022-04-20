<?php
include("./includes/header.php");

include 'pharmacy-repo/interface.php';

$Interface = new PharmacyInterface();

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Pharmacy'])) {
        if ($_SESSION['userinfo']['Pharmacy'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else { @session_destroy();header("Location: ../index.php?InvalidPrivilege=yes"); }


$sponsor = $Interface->getSponsors(null,"all");

?>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<a href="pharmacyreportspage.php?PhrmacyReports=PharmacyReportsThisPage" class="art-button-green" style="font-family: Arial, Helvetica, sans-serif;">BACK</a>

<br><br>
<fieldset>
    <table width='100%'>
        <tr>
            <td style="padding: 5px;text-align:end;font-weight:600" width='7%'>Start Date</td>
			<td style="padding: 5px;" ><input type='text' id='dates_From' name='Start_Date' readonly='readonly' style='text-align: center;padding:6px' placeholder='Start Date'></td>
            <td style="padding: 5px;text-align:end;font-weight:600" width='7%'>End Date</td>
            <td style="padding: 5px;"><input type='text' id='dates_To' name='End_Date' readonly='readonly' style='text-align: center;padding:6px' placeholder='Select End Date'></td>
            <td style="padding: 5px;" width='20%'><input type='text' id='search_item' onkeyup="filter_data()" name='search_item' style='text-align: center;padding:6px' placeholder='Search Item'></td>
            <td width='20%' style="padding: 5px;">
                <select name="" style="padding: 6px;width: 100%;" id="Sponsor_ID">
                    <option value="all">All Guarantor</option>
                        <?php foreach($sponsor as $Guarantee){  echo "<option value='{$Guarantee['Sponsor_ID']}'>{$Guarantee['Guarantor_Name']}</option>"; } ?>
                    </select>
                </select>
            </td>
            <td style="padding: 5px;" width='5%'>
                <center><a href="#" class="art-button-green" onclick="filter_data()">FILTER</a></center>
            </td>
            <td style="padding: 5px;" width='5%'>
            <a href="#" class="art-button-green" onclick="export_pdf()">PDF</a>
            </td>
        </tr>
    </table>
</fieldset>
<br>
<fieldset style="height: 600px;overflow-x:scroll">
    <table width='100%'>
        <thead>
            <tr style="background-color: #ddd;">
                <td style="padding: 8px;font-weight:600" width='5%'><center>S/N</center></td>
                <td style="padding: 8px;font-weight:600" width='12%'>ITEM CODE</td>
                <td style="padding: 8px;font-weight:600">ITEM NAME</td>
                <td style="padding: 8px;font-weight:600" width='12%'><center>QTY DISPENSED</center></td>
                <td style="padding: 8px;font-weight:600" width='12%'><center>BALANCE</center></td>
                <td style="padding: 8px;font-weight:600;text-align:right" width='12%'>STOCK DISPENSED VALUE</td>
                <td style="padding: 8px;font-weight:600" width='12%'><center>ACTION</center></td>
            </tr>
        </thead>

        <tbody id='display-data'>
            <tr style="background-color: #fff;">
                <td colspan="8" style="text-align: center;font-weight:600;padding:10px">Enter Option To Filter Data</td>
            </tr>
        </tbody>
    </table>
</fieldset>

<div id="patient_details"></div>

<script type="text/javascript">
    $('#dates_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#dates_From').datetimepicker({value: '', step: 01});
    $('#dates_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#dates_To').datetimepicker({value: '', step: 01});
</script>

<script>
    function filter_data(){
        var dates_From = $('#dates_From').val();
        var dates_To = $('#dates_To').val();
        var Sponsor_ID = $('#Sponsor_ID').val();
        var search_item = $('#search_item').val();

        if(dates_From == "" || dates_To == ""){
            alert('Please Enter Date Ranges');
            exit();
        }

        document.getElementById('display-data').innerHTML = '<tr><td colspan="7"><center><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></center></td></tr>';
        $.get('pharmacy-repo/common.php',{
            filter_quantity_given:'filter_quantity_given',
            dates_From:dates_From,
            dates_To:dates_To,
            Sponsor_ID:Sponsor_ID,
            search_item:search_item,
            sub_department_id:'<?=$_SESSION['Pharmacy_ID']?>'
        },(data) => {
            $('#display-data').html(data);
        });
    }

    function open_details(item_id){
        var dates_From = $('#dates_From').val();
        var dates_To = $('#dates_To').val();
        var Sponsor_ID = $('#Sponsor_ID').val();
        var search_item = $('#search_item').val();
        $.get('patient_dispense_qty_report.php',{
            sub_department_id:'<?=$_SESSION['Pharmacy_ID']?>',
            filter_quantity_given:'filter_quantity_given',
            dates_From:dates_From,
            dates_To:dates_To,
            Sponsor_ID:Sponsor_ID,
            item_id:item_id
        },(response) => {
            $("#patient_details").dialog({
                autoOpen: false,
                width: '80%',
                height: 600,
                title: 'DISPENSE MEDICATION DETAILS',
                modal: true
            });
            $("#patient_details").html(response);
            $("#patient_details").dialog("open");
        })
    }

    function export_pdf(){
        var dates_From = $('#dates_From').val();
        var dates_To = $('#dates_To').val();
        var Sponsor_ID = $('#Sponsor_ID').val();
        var search_item = $('#search_item').val();
        window.open('preview_dispense_pdf_report.php?dates_From='+dates_From+'&dates_To='+dates_To+'&Sponsor_ID='+Sponsor_ID+'&search_item='+search_item, '_blank');
    }
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>

<?php include("./includes/footer.php"); ?>
