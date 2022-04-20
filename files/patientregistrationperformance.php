<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Reception_Works'])) {
        if ($_SESSION['userinfo']['Reception_Works'] != 'yes') {

            if (isset($_SESSION['userinfo']['Management_Works']) && $_SESSION['userinfo']['Management_Works'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        }
    } elseif (isset($_SESSION['userinfo']['Management_Works'])) {
        if ($_SESSION['userinfo']['Management_Works'] != 'yes') {

            if (isset($_SESSION['userinfo']['Reception_Works']) && $_SESSION['userinfo']['Reception_Works'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
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

//get today date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $Today = $original_Date;
}

if (isset($_GET['Section'])) {
    $Section = $_GET['Section'];
} else {
    $Section = '';
}


if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Reception_Works'] == 'yes') {
        echo "<a href='receptionReports.php?Section=".$Section."&ReceptionReportThisPage' class='art-button-green'>BACK</a>";
    }
}
?>


<!-- new date function--> 
<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $Today = $row['today'];
}
?>
<!-- end of the function -->




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



<script language="javascript" type="text/javascript">
    /* function searchPatient(Patient_Name){
     document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=370px src='Pharmacy_List_Iframe.php?Patient_Name="+Patient_Name+"&Registration_Number="+Registration_Number+"'></iframe>";
     }*/
</script>
<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name) {
        var Billing_Type = '<?php echo $Billing_Type; ?>';
        //document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=320px src='Pharmacy_List_Iframe.php?Patient_Name="+Patient_Name+"&Billing_Type="+Billing_Type+"'></iframe>";
    }
</script>

<br/><br/>
<center>
    <table width=100%>
        <tr>
            <td style="text-align: right;">
                <b>Employee Name</b>
            </td>
            <td>
                <select name="Employee_ID" id="Employee_ID">
                    <option selected="selected" value="0">All</option>
                    <?php
	                    //list of employees perform registration
                    	$selected = '';
	                    $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_patient_registration pr, tbl_employee emp where
											    pr.Employee_ID = emp.Employee_ID group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
	                    $num = mysqli_num_rows($select_details);
	                    if ($num > 0) {
	                        while ($data = mysqli_fetch_array($select_details)) {
	                        	$selected .= ','.$data['Employee_ID'];
	                            ?>
	                            <option value="<?php echo $data['Employee_ID']; ?>"><?php echo ucwords(strtolower($data['Employee_Name'])); ?></option>
					<?php
	                        }
	                    }
	                    if($selected != ''){ $selected = substr($selected, 1); }
	                    //remainders who perform check in but not register
	                    $select_r = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_check_in ci, tbl_employee emp where
											    ci.Employee_ID = emp.Employee_ID and 
											    emp.Employee_ID NOT IN($selected) group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
	                    $num2 = mysqli_num_rows($select_r);
	                    if($num2 > 0){
	                    	while ($data = mysqli_fetch_array($select_r)) {
	                        	$selected .= ','.$data['Employee_ID'];
	                            ?>
	                            <option value="<?php echo $data['Employee_ID']; ?>"><?php echo ucwords(strtolower($data['Employee_Name'])); ?></option>
					<?php
	                        }
	                    }
                    ?>
                </select>
            </td>
            <td style="text-align: right;"><b>Sponsor Name</b></td>
            <td>
                <select name="Sponsor_ID" id="Sponsor_ID">
                    <option selected="selected" value="0">All</option>
                    <?php
                    $select = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($select);
                    if ($num > 0) {
                        while ($data = mysqli_fetch_array($select)) {
                            ?>
                            <option value="<?php echo $data['Sponsor_ID']; ?>"><?php echo $data['Guarantor_Name']; ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </td>
            <td style='text-align: right;'><b>Start Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='Date_From' style='text-align: center;' placeholder='Start Date' readonly='readonly' value='<?php echo $Today; ?>'>
            </td>
            <td style='text-align: right;'><b>End Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='Date_To' style='text-align: center;' placeholder='End Date' readonly='readonly' value='<?php echo $Today; ?>'>
            </td>
            <td style='text-align: center;' width=10%>
                <input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='filter_list()'>
            </td>
        </tr>
    </table>
</center>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
                    $('#Date_From').datetimepicker({
                        dayOfWeekStart: 1,
                        lang: 'en',
//startDate:    'now'
                    });
                    $('#Date_From').datetimepicker({value: '', step: 01});
                    $('#Date_To').datetimepicker({
                        dayOfWeekStart: 1,
                        lang: 'en',
//startDate:'now'
                    });
                    $('#Date_To').datetimepicker({value: '', step: 01});
</script>
<!--End datetimepicker-->

<fieldset style='overflow-y: scroll; height: 350px;background-color:white;margin-top:20px;' id='Fieldset_List'>
    <legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>PATIENTS REGISTRATION PERFORMANCE</b></legend>
    <table width=100% border=1>
        <?php
        echo "<tr id='thead'>
                <td width=5%><b>SN</b></td>
                <td><b>EMPLOYEE NAME</b></td>
                <td style='text-align: center;'><b>REGISTERED</b></td>
                <td style='text-align: center;'><b>CHECKED-IN</b></td>
                <td style='text-align: center;'><b>NOT CHECKED-IN</b></td>
                <td style='text-align: center;'><b>CHECKED-IN OTHERS</b></td>
                <td style='text-align: center;'><b>TOTAL CHECKED-IN</b></td>
                <td style='text-align: center;'><b>CHECKED-IN & PAID</b></td>
                <td style='text-align: center;'><b>NOT PAID</b>&nbsp;&nbsp;&nbsp;</td>
            </tr>";
        echo '<tr><td colspan="9"><hr></td></tr>';
        ?>
        </td>
        </tr>
    </table>
</fieldset>
<table width="100%">
    <tr>
        <td style="text-align: right; width: 100% " id="Report_Button_Area">

        </td>
    </tr>
</table>

<!--popup window -->
<div id="Display_Details" style="width:50%;" >
    <center id='Details_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </center>
</div>

<!--popup window -->
<div id="Display_Exclusion_Details" style="width:50%;" >
    <center id='Excluded_Item_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </center>
</div>

<script>
    function Preview_Paid_Details(Sponsor_ID, Date_From, Date_To, Employee_ID) {
        if (window.XMLHttpRequest) {
            myObjectGetDetails = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetDetails.overrideMimeType('text/xml');
        }

        myObjectGetDetails.onreadystatechange = function () {
            data29 = myObjectGetDetails.responseText;
            if (myObjectGetDetails.readyState == 4) {
                document.getElementById('Details_Area').innerHTML = data29;
                $("#Display_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectGetDetails.open('GET', 'Parformance_Check_In_Details.php?Employee_ID=' + Employee_ID + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&Sponsor_ID=' + Sponsor_ID, true);
        myObjectGetDetails.send();
    }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<!--<script src="script.js"></script>-->
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<!--<script src="script.responsive.js"></script>-->

<script>
    $(document).ready(function () {
        $("#Display_Details").dialog({autoOpen: false, width: '70%', height: 500, title: 'PATIENTS REGISTRATION PERFORMANCE - CHECKED-IN PAID & UNPAID PATIENTS DETAILS', modal: true});
    });
</script>
<!-- end popup window -->

<script>
    function filter_list() {
        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;
        var Employee_ID = document.getElementById("Employee_ID").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        if (Date_From != null && Date_From != '' && Date_To != null && Date_To != '') {
            if (window.XMLHttpRequest) {
                myObject2 = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject2.overrideMimeType('text/xml');
            }

            myObject2.onreadystatechange = function () {
                data2 = myObject2.responseText;
                if (myObject2.readyState == 4) {
                    document.getElementById('Fieldset_List').innerHTML = data2;
                    Display_Report_Button();
                }
            }; //specify name of function that will handle server response........

            myObject2.open('GET', 'Patient_Registration_Performance.php?Date_From=' + Date_From + '&Date_To=' + Date_To + '&Employee_ID=' + Employee_ID + '&Sponsor_ID=' + Sponsor_ID, true);
            myObject2.send();

        } else {
            if (Date_From == '' || Date_From == null) {
                document.getElementById("Date_From").style = 'border: 3px solid red';
            }
            if (Date_To == '' || Date_To == null) {
                document.getElementById("Date_To").style = 'border: 3px solid red';
            }
        }
    }
</script>

<script>
    function Display_Report_Button() {
        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;
        var Employee_ID = document.getElementById("Employee_ID").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        if (window.XMLHttpRequest) {
            myObjectDisplayButton = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectDisplayButton = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectDisplayButton.overrideMimeType('text/xml');
        }

        myObjectDisplayButton.onreadystatechange = function () {
            data_Display = myObjectDisplayButton.responseText;
            if (myObjectDisplayButton.readyState == 4) {
                document.getElementById('Report_Button_Area').innerHTML = data_Display;
            }
        }; //specify name of function that will handle server response........

        myObjectDisplayButton.open('GET', 'Registration_Performance_Buttons.php?Employee_ID=' + Employee_ID + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&Sponsor_ID=' + Sponsor_ID, true);
        myObjectDisplayButton.send();
    }
</script>


<script>
    function Select_E_Item(Sponsor_ID, Date_From, Date_To, Employee_ID) {
        if (window.XMLHttpRequest) {
            myObjectGetDetails = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetDetails.overrideMimeType('text/xml');
        }

        myObjectGetDetails.onreadystatechange = function () {
            data29 = myObjectGetDetails.responseText;
            if (myObjectGetDetails.readyState == 4) {
                document.getElementById('Excluded_Item_Area').innerHTML = data29;
                $("#Display_Exclusion_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectGetDetails.open('GET', 'Select_Excluded_Item_Dialog.php?Employee_ID=' + Employee_ID + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&Sponsor_ID=' + Sponsor_ID, true);
        myObjectGetDetails.send();
    }
</script>

<script>
    $(document).ready(function () {
        $("#Display_Exclusion_Details").dialog({autoOpen: false, width: '20%', height: 400, title: 'SELECT ITEM TO EXCLUDE', modal: true});
    });
</script>


<script type="text/javascript">
    function getItemsList(Item_Category_ID) {
        document.getElementById("Search_Value").value = '';
        document.getElementById("Item_Name").value = '';
        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function () {
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                document.getElementById('Items_Fieldset').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Get_List_Of_Items.php?Item_Category_ID=' + Item_Category_ID, true);
        myObject.send();
    }
</script>

<script type="text/javascript">
    function Get_Item_Name(Item_Name, Item_ID) {
        document.getElementById("Item_Name").value = Item_Name;
        document.getElementById("Item_ID").value = Item_ID;
        document.getElementById("Exclusion_Title").innerHTML = 'selected Item : <i> (' + Item_Name + ') </i>';
        Update_Registration_Performance();
        $("#Display_Exclusion_Details").dialog("close");
    }
</script>

<script type="text/javascript">
    function getItemsListFiltered(Item_Name) {
        document.getElementById("Item_Name").value = '';
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        if (Item_Category_ID == '' || Item_Category_ID == null) {
            Item_Category_ID = 'All';
        }

        if (window.XMLHttpRequest) {
            myObjectFiltered = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectFiltered = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFiltered.overrideMimeType('text/xml');
        }
        //alert(data);

        myObjectFiltered.onreadystatechange = function () {
            dataaa = myObjectFiltered.responseText;
            if (myObjectFiltered.readyState == 4) {
                document.getElementById('Items_Fieldset').innerHTML = dataaa;
            }
        }; //specify name of function that will handle server response........
        myObjectFiltered.open('GET', 'Get_List_Of_Items_Filtered.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name, true);
        myObjectFiltered.send();
    }
</script>
<script type="text/javascript">
    function Update_Registration_Performance() {
        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;
        var Employee_ID = document.getElementById("Employee_ID").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Item_ID = document.getElementById("Item_ID").value;

        //document.getElementById("Search_Value").value = '';
        // document.getElementById("Item_Name").value = '';
        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function () {
            data77 = myObject.responseText;
            if (myObject.readyState == 4) {
                document.getElementById('Details_After_Excluded_Area').innerHTML = data77;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Update_Registration_Performance.php?Sponsor_ID=' + Sponsor_ID + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&Employee_ID=' + Employee_ID + '&Item_ID=' + Item_ID, true);
        myObject.send();
    }
</script>
<script type="text/javascript">
    function AccessDeny() {
        alert("No item selected to review payments");
    }
</script>
<?php
include("./includes/footer.php");
?>