<?php
include("./includes/header.php");
include("./includes/connection.php");
$temp = 1;
$total = 0;

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ./index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Quality_Assurance'])) {
        if ($_SESSION['userinfo']['Quality_Assurance'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ./index.php?InvalidPrivilege=yes");
}

//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = '';
}

$Date_From = '';
$Date_To = '';
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] == 'yes') {
        ?>
        <a href='./qualityassuarancework.php?QualityAssuranceWork=QualityAssuranceWorkThiPage' class='art-button-green'>
            BACK
        </a>
    <?php }
} ?>

<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
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
<center>
    <fieldset>
        <legend align='right'><b>REVENUE COLLECTION BY FOLIO REPORT</b></legend>
        <table width=100%>
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td width="8%" style="text-align: right;"><b>Start Date</b></td>
                            <td width="16%" style='text-align: center;'>
                                <input type='text' name='Date_From' id='date' style='text-align: center;' placeholder="~~~ ~~~ Enter Start Date ~~~ ~~~">
                            </td> 
                            <td width="8%" style="text-align: right;"><b>End Date</b></td>
                            <td width="16%" style='text-align: center;'>
                                <input type='text' name='Date_To' id='date2' style='text-align: center;' placeholder="~~~ ~~~ Enter End Date ~~~ ~~~">
                            </td>
                            <td width="8%" style="text-align: right;"><b>Sponsor Name</b></td>
                            <td style='text-align: left; color:black; border:2px solid #ccc;'>
                                <select name='Sponsor_ID' id='Sponsor_ID'>
                                    <option selected='selected'></option>
                                    <?php
                                    //$data = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor where Guarantor_Name <> 'cash' order by Guarantor_Name") or die(mysqli_error($conn));

                                    $data = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
                                    while ($row = mysqli_fetch_array($data)) {
                                        echo '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td width="7%" style="text-align: right;"><b>Patient Type</b></td>
                            <td>
                                <select name="Patient_Type" id="Patient_Type">
                                    <option selected="selected">All</option>
                                    <option>Outpatient</option>
                                    <option>Inpatient</option>
                                </select>
                            </td>
                            <td width="7%" style="text-align: right;"><b>Billing Type</b></td>
                            <td>
                                <select name="Billing_Type" id="Billing_Type">
                                    <option selected="selected">All</option>
                                    <option>Cash</option>
                                    <option>Credit</option>
                                </select>
                            </td>
                            <td colspan="2" width="16%" style='text-align: center; color:black; border:2px solid #ccc;'>
                                <input type='button' name='FILTER' id='FILTER' class='art-button-green' value='FILTER' onclick='Get_Transaction_List()'>
                                <input type='button' name='Preview_Report' id='Preview_Report' class='art-button-green' value='PREVIEW' onclick='Preview_Report()'>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td style="text-align: center;">
                                <input type="text" id="Search_Value" name="Search_Value" style="text-align: center;" placeholder=' ~~~ ~~~ ~~~ Enter Patient Name ~~~ ~~~ ~~~ ' autocomplete='off' oninput="Search_Patient_Filtered()" onkeyup="Search_Patient_Filtered()">
                            </td>
                            <td style="text-align: center;">
                                <input type="text" id="Patient_Number" name="Patient_Number" style="text-align: center;" placeholder=' ~~~ ~~~ ~~~ Enter Patient Number ~~~ ~~~ ~~~ ' autocomplete='off' oninput="Search_Patient_Filtered_Patient_Number()" onkeyup="Search_Patient_Filtered_Patient_Number()">
                            </td>
                            <td style="text-align: center;">
                                <input type="text" id="Patient_Folio_Number" name="Patient_Folio_Number" style="text-align: center;" placeholder=' ~~~ ~~~ ~~~ Enter Folio number ~~~ ~~~ ~~~ ' autocomplete='off' oninput="Search_Patient_Filtered_Folio_Number()" onkeyup="Search_Patient_Filtered_Folio_Number()">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
</center>
</fieldset>
<fieldset style='overflow-y: scroll; height: 380px; background: white;' id='Fieldset_List'>
</fieldset>


<div id="Invalid_Date_Alert" style="width:25%;">
    <center>
        <b>Start Date must be date before end date</b>
        <br/><br/>
        <input type="button" class="art-button-green" value="Ok" onclick="Redirect_Window()">
    </center>
</div>

<script type="text/javascript">
    function Redirect_Window() {
        $("#Invalid_Date_Alert").dialog("close");
    }
</script>

<script type="text/javascript">
    function Get_Transaction_List() {
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Patient_Type = document.getElementById("Patient_Type").value;
        var Billing_Type = document.getElementById("Billing_Type").value;

        document.getElementById("Search_Value").value = '';
        document.getElementById("Patient_Number").value = '';
        document.getElementById("Patient_Folio_Number").value = '';

        if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '' && Sponsor_ID != null && Sponsor_ID != '') {
            document.getElementById('Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            myObject.onreadystatechange = function () {
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    document.getElementById('Fieldset_List').innerHTML = data;
                }
            }; //specify name of function that will handle server response........

//            myObject.open('GET', 'Revenue_Collection_By_Folio_Report.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Sponsor_ID=' + Sponsor_ID + '&Patient_Type=' + Patient_Type + '&Billing_Type=' + Billing_Type, true);
            myObject.open('GET', 'Revenue_Collection_By_Folio_Report_Filtered.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Sponsor_ID=' + Sponsor_ID + '&Patient_Type=' + Patient_Type + '&Billing_Type=' + Billing_Type, true);
            myObject.send();
        } else {
            if (Sponsor_ID == null || Sponsor_ID == '') {
                document.getElementById("Sponsor_ID").style = 'border: 3px solid red;';
            } else {
                document.getElementById("Sponsor_ID").style = 'border: 3px solid white;';
            }

            if (Start_Date == null || Start_Date == '') {
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
            } else {
                document.getElementById("date").style = 'border: 3px solid white; text-align: center;';
            }

            if (End_Date == null || End_Date == '') {
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
            } else {
                document.getElementById("date2").style = 'border: 3px solid white; text-align: center;';
            }
        }
    }
</script>

<script type="text/javascript">
    function Search_Patient_Filtered() {
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Patient_Type = document.getElementById("Patient_Type").value;
        var Billing_Type = document.getElementById("Billing_Type").value;
        var Patient_Name = document.getElementById("Search_Value").value;

        document.getElementById("Patient_Folio_Number").value = '';
        document.getElementById("Patient_Number").value = '';

        if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '' && Sponsor_ID != null && Sponsor_ID != '') {
            document.getElementById('Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            myObject.onreadystatechange = function () {
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    document.getElementById('Fieldset_List').innerHTML = data;
                }
            }; //specify name of function that will handle server response........

            myObject.open('GET', 'Revenue_Collection_By_Folio_Report_Filtered.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Sponsor_ID=' + Sponsor_ID + '&Patient_Type=' + Patient_Type + '&Billing_Type=' + Billing_Type + '&Patient_Name=' + Patient_Name, true);
            myObject.send();
        } else {
            if (Sponsor_ID == null || Sponsor_ID == '') {
                document.getElementById("Sponsor_ID").style = 'border: 3px solid red;';
            } else {
                document.getElementById("Sponsor_ID").style = 'border: 3px solid white;';
            }

            if (Start_Date == null || Start_Date == '') {
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
            } else {
                document.getElementById("date").style = 'border: 3px solid white; text-align: center;';
            }

            if (End_Date == null || End_Date == '') {
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
            } else {
                document.getElementById("date2").style = 'border: 3px solid white; text-align: center;';
            }
        }
    }
</script>



<script type="text/javascript">
    function Search_Patient_Filtered_Patient_Number() {
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Patient_Type = document.getElementById("Patient_Type").value;
        var Billing_Type = document.getElementById("Billing_Type").value;
        var Patient_Number = document.getElementById("Patient_Number").value;

        document.getElementById("Search_Value").value = '';
        document.getElementById("Patient_Folio_Number").value = '';

        if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '' && Sponsor_ID != null && Sponsor_ID != '') {
            document.getElementById('Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            myObject.onreadystatechange = function () {
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    document.getElementById('Fieldset_List').innerHTML = data;
                }
            }; //specify name of function that will handle server response........

            myObject.open('GET', 'Revenue_Collection_By_Folio_Report_Filtered.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Sponsor_ID=' + Sponsor_ID + '&Patient_Type=' + Patient_Type + '&Billing_Type=' + Billing_Type + '&Patient_Number=' + Patient_Number, true);
            myObject.send();
        } else {
            if (Sponsor_ID == null || Sponsor_ID == '') {
                document.getElementById("Sponsor_ID").style = 'border: 3px solid red;';
            } else {
                document.getElementById("Sponsor_ID").style = 'border: 3px solid white;';
            }

            if (Start_Date == null || Start_Date == '') {
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
            } else {
                document.getElementById("date").style = 'border: 3px solid white; text-align: center;';
            }

            if (End_Date == null || End_Date == '') {
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
            } else {
                document.getElementById("date2").style = 'border: 3px solid white; text-align: center;';
            }
        }
    }
</script>



<script type="text/javascript">
    function Search_Patient_Filtered_Folio_Number() {
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Patient_Type = document.getElementById("Patient_Type").value;
        var Billing_Type = document.getElementById("Billing_Type").value;
        var Folio_Number = document.getElementById("Patient_Folio_Number").value;

        document.getElementById("Patient_Number").value = '';
        document.getElementById("Search_Value").value = '';

        if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '' && Sponsor_ID != null && Sponsor_ID != '') {
            document.getElementById('Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            myObject.onreadystatechange = function () {
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    document.getElementById('Fieldset_List').innerHTML = data;
                }
            }; //specify name of function that will handle server response........

            myObject.open('GET', 'Revenue_Collection_By_Folio_Report_Filtered.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Sponsor_ID=' + Sponsor_ID + '&Patient_Type=' + Patient_Type + '&Billing_Type=' + Billing_Type + '&Folio_Number=' + Folio_Number, true);
            myObject.send();
        } else {
            if (Sponsor_ID == null || Sponsor_ID == '') {
                document.getElementById("Sponsor_ID").style = 'border: 3px solid red;';
            } else {
                document.getElementById("Sponsor_ID").style = 'border: 3px solid white;';
            }

            if (Start_Date == null || Start_Date == '') {
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
            } else {
                document.getElementById("date").style = 'border: 3px solid white; text-align: center;';
            }

            if (End_Date == null || End_Date == '') {
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
            } else {
                document.getElementById("date2").style = 'border: 3px solid white; text-align: center;';
            }
        }
    }
</script>

<script type="text/javascript">
    function Preview_Report() {
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Patient_Type = document.getElementById("Patient_Type").value;
        var Billing_Type = document.getElementById("Billing_Type").value;
        var Patient_Name = document.getElementById("Search_Value").value;
        var Patient_Number = document.getElementById("Patient_Number").value;
        var Folio_Number = document.getElementById("Patient_Folio_Number").value;

        if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '' && Sponsor_ID != null && Sponsor_ID != '') {
            if (Start_Date > End_Date) {
                $("#Invalid_Date_Alert").dialog("open");
            } else {
                window.open('revenuebyfolioreport.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Sponsor_ID=' + Sponsor_ID + '&Billing_Type=' + Billing_Type + '&Patient_Type=' + Patient_Type + '&Patient_Name=' + Patient_Name + '&Patient_Number=' + Patient_Number + '&Folio_Number=' + Folio_Number, '_blank');
                //var winClose=popupwindow('revenuebyfolioreport.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Sponsor_ID='+Sponsor_ID, 'REVENUE COLLECTION DETAILS', 1200, 500);
            }
        } else {
            if (Sponsor_ID == null || Sponsor_ID == '') {
                document.getElementById("Sponsor_ID").style = 'border: 3px solid red;';
            } else {
                document.getElementById("Sponsor_ID").style = 'border: 3px solid white;';
            }

            if (Start_Date == null || Start_Date == '') {
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
            } else {
                document.getElementById("date").style = 'border: 3px solid white; text-align: center;';
            }

            if (End_Date == null || End_Date == '') {
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
            } else {
                document.getElementById("date2").style = 'border: 3px solid white; text-align: center;';
            }
        }
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

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>
    $(document).ready(function () {
        $("#Invalid_Date_Alert").dialog({autoOpen: false, width: '30%', height: 150, title: 'eHMS 2.0 ~ Alert Message!', modal: true});
        $('.ui-dialog-titlebar-close').click(function () {

        });
    });
</script>
<script>
    $(document).ready(function () {
        $('select').select2();
    })
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>


<?php
include("./includes/footer.php");
?>