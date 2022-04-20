<?php 
    include("./includes/header.php");
    include("./includes/connection.php");
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
    } else {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $dispenser = $Interface->getDispenser();
    $sponsor = $Interface->getSponsors(null,"all");
?>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<a href="pharmacyreportspage.php?PhrmacyReports=PharmacyReportsThisPage" class="art-button-green" style="font-family: Arial, Helvetica, sans-serif;">BACK</a>
<br><br>

<fieldset style="padding: 10px;">
    <legend style='font-weight:500'><?=strtoupper($_SESSION['Pharmacy'])?></legend>
    <center>
        <table width=100%>
            <tr>
                <td style='text-align: right;' width="9%"><b>Bill Type</b></td>
                <td width="9%">
                    <select name='Bill_Type' id='Bill_Type' required='required' style='text-align: center;padding:5px; width:100%;display:inline'>
                        <option selected='selected'>All</option>
                        <option>Outpatient</option>
                        <option>Inpatient</option>
                    </select>
                </td>
                <td style='text-align: right;' width="9%"><b>Payment Mode</b></td>
                <td width="10%">
                    <select name='Payment_Mode' id='Payment_Mode' required='required' style='text-align: center;padding:5px; width:100%;display:inline'>
                        <option selected='selected'>All</option>
                        <option>Cash</option>
                        <option>Credit</option>
                    </select>
                </td>
                <td style='text-align: right;'><b>Start Date</b></td>
                <td>
                    <input type='text' name='Start_Date' id='dates_From' style='text-align: center;' placeholder='Start Date' readonly='readonly' value='<?php echo $Start_Date; ?>'>
                </td>
                <td style='text-align: right;'><b>End Date</b></td>
                <td>
                    <input type='text' name='Start_Date' id='dates_To' style='text-align: center;' placeholder='End Date' readonly='readonly' value='<?php echo $End_Date; ?>'>
                </td>
                <td style='text-align: center;' width=7%>
                    <input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='filter_list()'>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <input type='text' name='Search_Patient' id='Search_Patient' oninput='filter_list()' placeholder='Enter Patient Name' style='text-align: center;'>
                </td>
                <td style="text-align: right;"><b>Dispenser Name</b></td>
                <td>
                    <select id="employeeID" style='text-align: center;padding:5px; width:100%;display:inline'>
                        <option value="all">Select Dispenser</option>
                        <?php foreach($dispenser as $employee){  echo "<option value='{$employee['Employee_ID']}'>{$employee['Employee_Name']}</option>"; } ?>
                    </select>
                </td>
                <td style="text-align: right;"><b>Sponsor Name</b></td>
                <td >
                    <select id="sponsorID" style='text-align: center;padding:5px; width:100%;display:inline'>
                    <option value="all">Select Guarantor Name</option>
                        <?php foreach($sponsor as $Guarantee){  echo "<option value='{$Guarantee['Sponsor_ID']}'>{$Guarantee['Guarantor_Name']}</option>"; } ?>
                    </select>
                </td>

                <td style='text-align: center;'>
                    <input type='button' name='pdf' id='pdf' value='PREVIEW' class='art-button-green' onclick='preview_pdf()'>
                </td>
            </tr>
        </table>
    </center>
</fieldset>

<br>

<fieldset style="height: 550px;overflow-y:scroll" id="display-data"></fieldset>

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
    $(document).ready(() => {
        console.clear();
    });

    function preview_pdf(){
        var Start_Date = document.getElementById("dates_From").value;
        var End_Date = document.getElementById("dates_To").value;
        var Search_Patient = document.getElementById("Search_Patient").value;
        var Sponsor_ID = document.getElementById('sponsorID').value;
        var employeeID = document.getElementById('employeeID').value;
        var Bill_Type = document.getElementById("Bill_Type").value;
        var Payment_Mode = document.getElementById("Payment_Mode").value;

        if(Start_Date == "" || Start_Date == null){
            document.getElementById("dates_From").style.border = '1px solid red';
        }else if(End_Date == "" || End_Date == null){
            document.getElementById("dates_To").style.border = '1px solid red';
        }else{
            document.getElementById("dates_From").style.border = '1px solid #ccc';
            document.getElementById("dates_To").style.border = '1px solid #ccc';
            window.open('previewDispensedList.php?Start_Date=' + Start_Date +'&Sub_Department_ID='+<?=$_SESSION['Pharmacy_ID']?>+'&End_Date=' + End_Date + '&Search_Patient=' + Search_Patient + '&Sponsor=' + Sponsor_ID + '&employeeID=' + employeeID + '&Bill_Type=' + Bill_Type + '&Payment_Mode=' + Payment_Mode, '_blank');
        }
    }

    function filter_list(){
        var Start_Date = document.getElementById("dates_From").value;
        var End_Date = document.getElementById("dates_To").value;
        var Search_Patient = document.getElementById("Search_Patient").value;
        var Sponsor_ID = document.getElementById('sponsorID').value;
        var employeeID = document.getElementById('employeeID').value;
        var Bill_Type = document.getElementById("Bill_Type").value;
        var Payment_Mode = document.getElementById("Payment_Mode").value;

        if(Start_Date == "" || Start_Date == null){
            document.getElementById("dates_From").style.border = '1px solid red';
        }else if(End_Date == "" || End_Date == null){
            document.getElementById("dates_To").style.border = '1px solid red';
        }else{
            document.getElementById("dates_From").style.border = '1px solid #ccc';
            document.getElementById("dates_To").style.border = '1px solid #ccc';
            document.getElementById('display-data').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            $.get('pharmacy-repo/common.php',{
                filter_quantity_report:'filter_quantity_report',
                Start_Date:Start_Date,
                End_Date:End_Date,
                Search_Patient:Search_Patient,
                Employee_ID:employeeID,
                Sponsor_ID:Sponsor_ID,
                Bill_Type:Bill_Type,
                Payment_Mode:Payment_Mode,
                Sub_Department_ID : <?=$_SESSION['Pharmacy_ID']?>
            },(data) => {
                $('#display-data').html(data);
            });
        }
    }
</script>
<script src="js/select2.min.js"></script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<link rel="stylesheet" href="css/select2.min.css" media="screen">

<?php include './includes/footer.php'; ?>
