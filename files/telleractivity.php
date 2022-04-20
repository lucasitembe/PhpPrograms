<?php
    include("./includes/header.php");
    include("./includes/connection.php");
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
    if (isset($_GET['Registration_ID'])) {
        $Registration_ID = $_GET['Registration_ID'];
    }
?>

<!-- new date function (Contain years, Months and days)--> 
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $Today = $row['today'];
    }

    if(isset($_SESSION['userinfo'])){
        echo "<a href='pharmacyreportspage.php?PhrmacyReports=PharmacyReportsThisPage' class='art-button-green'>BACK</a>";
    }


    $Get_Sponsors = mysqli_query($conn,"SELECT Sponsor_ID, Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
    $dataSponsor = '';
    $dataSponsor .='<option value="All">All Sponsors</option>';

    while ($row = mysqli_fetch_array($Get_Sponsors)) {
        $dataSponsor .= '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
    }

    $Get_Employees = mysqli_query($conn,"SELECT Employee_ID, Employee_Name FROM tbl_employee") or die(mysqli_error($conn));
    $dataEmployee = '';
    $dataEmployee .='<option value="All">All Dispensors</option>';

    while ($row = mysqli_fetch_array($Get_Employees)) {
        $dataEmployee .= '<option value="' . $row['Employee_ID'] . '">' . $row['Employee_Name'] . '</option>';
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
<?php
    //get Sub_Department_ID & Name
    if(isset($_SESSION['Pharmacy_ID'])){
        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
        $Sub_Department_ID = 0;
    }

    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($select);
    if($nm > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Sub_Department_Name = $data['Sub_Department_Name'];
        }
    }
?>


<fieldset>
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
                <input type='text' name='Start_Date' id='dates_From' style='text-align: center;' placeholder='Start Date' readonly='readonly' value='<?php echo $Today; ?>'>
            </td>
            <td style='text-align: right;'><b>End Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='dates_To' style='text-align: center;' placeholder='End Date' readonly='readonly' value='<?php echo $Today; ?>'>
            </td>
            <td style='text-align: right;' width=7%>
                <input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='filter_list()'>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <input type='text' name='Search_Patient' id='Search_Patient' onclick='filter_list()' oninput='filter_list()' placeholder='Enter Patient Name' style='text-align: center;'>
            </td>
            <td style="text-align: right;"><b>Dispensor Name</b></td>
            <td>
                <select id="employeeID" style='text-align: center;padding:5px; width:100%;display:inline'>
                    <?php echo $dataEmployee ?>
                </select>
            </td>
            <td style="text-align: right;"><b>Sponsor Name</b></td>
            <td>
                <select id="sponsorID" style='text-align: center;padding:5px; width:100%;display:inline'>
                    <?php echo $dataSponsor ?>
                </select>
            </td>
            <td style="text-align:right;"><input type="button" class="art-button-green" value='PREVIEW REPORT' onclick="Preview_Report()"></td>
        </tr>
    </table>
</center>
</fieldset>

<fieldset style='overflow-y: scroll; height: 410px;background-color:white;margin-top:20px;' id='Dispensed_List_Area'>
    <legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>TELLERS ACTIVITIES REPORT ~ <?php echo strtoupper($Sub_Department_Name); ?></b></legend>
        <table width = "100%">

        </table>
    </center>
</fieldset>

<script type="text/javascript">
    function Preview_Report(){
        var Start_Date = document.getElementById("dates_From").value;
        var End_Date = document.getElementById("dates_To").value;
        var Search_Patient = document.getElementById("Search_Patient").value;
        var Sponsor = document.getElementById('sponsorID').value;
        var employeeID = document.getElementById('employeeID').value;
        var Bill_Type = document.getElementById("Bill_Type").value;
        var Payment_Mode = document.getElementById("Payment_Mode").value;
        window.open('tellersactivityreport.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Search_Patient='+Search_Patient+'&Sponsor='+Sponsor+'&employeeID='+employeeID+'&Bill_Type='+Bill_Type+'&Payment_Mode='+Payment_Mode,'_blank');
   }
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
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

<script type="text/javascript">
    function filter_list() {
        var Start_Date = document.getElementById("dates_From").value;
        var End_Date = document.getElementById("dates_To").value;
        var Search_Patient = document.getElementById("Search_Patient").value;
        var Sponsor = document.getElementById('sponsorID').value;
        var employeeID = document.getElementById('employeeID').value;
        var Bill_Type = document.getElementById("Bill_Type").value;
        var Payment_Mode = document.getElementById("Payment_Mode").value;
        document.getElementById('Dispensed_List_Area').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

        if (window.XMLHttpRequest) {
            myObjectFilterList = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectFilterList = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilterList.overrideMimeType('text/xml');
        }

        myObjectFilterList.onreadystatechange = function () {
            data100 = myObjectFilterList.responseText;
            if (myObjectFilterList.readyState == 4 && myObjectFilterList.status == 200) {
                document.getElementById('Dispensed_List_Area').innerHTML = data100;
            }
        }; //specify name of function that will handle server response........

        myObjectFilterList.open('GET', 'Teller_Activity_Report.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Search_Patient=' + Search_Patient + '&Sponsor=' + Sponsor + '&employeeID=' + employeeID+'&Bill_Type='+Bill_Type+'&Payment_Mode='+Payment_Mode, true);
        myObjectFilterList.send();
    }
</script>
<script>

 $(document).ready(function (){
       $('select').select2();
 })
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<?php
include("./includes/footer.php");
?>
