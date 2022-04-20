<?php
include("./includes/header.php");
include("./includes/connection.php");
$temp = 0;
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


if (isset($_GET['Section'])) {
    $Section = $_GET['Section'];
} else {
    $Section = '';
}



//get today date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $Today = $original_Date;
}

if (isset($_SESSION['userinfo'])) {
    ?>
    <input type="button" name="Edit_Items" id="Edit_Items" value="EDIT INITIAL ITEMS" class="art-button-green" onclick="Edit_Initial_Items()">      
    <?php
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
        //border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>

<br/><br/>
<center>
    <table width=100%>
        <tr>
            <td style="text-align: right;">
                <b>Item Name</b>
            </td>
            <td id="Product_Area">
                <select name="Item_ID" id="Item_ID">
                    <?php
                    $select = mysqli_query($conn,"select i.Product_Name, i.Item_ID from tbl_initial_items ii, tbl_items i where i.Item_ID = ii.Item_ID") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($select);
                    if ($num > 0) {
                        echo '<option selected="selected" value="0">All</option>';
                        while ($row = mysqli_fetch_array($select)) {
                            ?>
                            <option value="<?php echo $row['Item_ID']; ?>"><?php echo ucwords(strtolower($row['Product_Name'])); ?></option>
                            <?php
                        }
                    } else {
                        echo '<option selected="selected" value="">No Item Picked</option>';
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
            <td style='text-align: left;' width=10%>
                <input type='button' name='Filter' id='Filter' value='PREVIEW REPORT' class='art-button-green' onclick='Preview_list()'>
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
                    $('#Date_From').datetimepicker({value: '', step: 30});
                    $('#Date_To').datetimepicker({
                        dayOfWeekStart: 1,
                        lang: 'en',
//startDate:'now'
                    });
                    $('#Date_To').datetimepicker({value: '', step: 30});
</script>
<!--End datetimepicker-->

<fieldset style='overflow-y: scroll; height: 350px; background-color:white; margin-top:20px;' id='Fieldset_List'>
    <legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>INITIAL PAYMENTS REPORT</b></legend>
    <table width=100% border=1>
        <?php
        $select = mysqli_query($conn,"select i.Product_Name, i.Item_ID from tbl_initial_items ii, tbl_items i where i.Item_ID = ii.Item_ID order by Initial_ID") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if ($no > 0) {
            //display titles
            echo "<tr><td><b>SN</b></td><td><b>SPONSOR NAME</b></td>";
            while ($row = mysqli_fetch_array($select)) {
                echo '<td style="text-align: right;"><b>' . strtoupper($row['Product_Name']) . '</b></td>';
            }
            echo "<td style='text-align: right;'><b>UNPAID</b></td><td style='text-align: right;'><b>TOTAL</b>&nbsp;&nbsp;&nbsp;</td></tr>";
            echo "<tr><td colspan='" . ($no + 4) . "'><hr></td></tr>";
            $get_sponsor = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));

            $num_sp = mysqli_num_rows($get_sponsor);
            if ($num_sp > 0) {
                while ($data = mysqli_fetch_array($get_sponsor)) {
                    ?>
                    <tr>
                        <td><?php echo ++$temp; ?><b>.</b></td>
                        <td><?php echo $data['Guarantor_Name']; ?></td>

                        <?php
                        for ($i = -1; $i <= $no; $i++) {
                            if ($i == $no) {
                                echo "<td style='text-align: right;'>0&nbsp;&nbsp;&nbsp;</td>";
                            } else {
                                echo "<td style='text-align: right;'>0</td>";
                            }
                        }
                    }
                }
            }
            ?>
        <tr><td colspan="<?php echo $no + 4; ?>"><hr></td></tr>
        <tr><td colspan="2"><b>TOTAL</b></td>
            <?php
            for ($i = -1; $i <= $no; $i++) {
                if ($i == $no) {
                    echo "<td style='text-align: right;'>0&nbsp;&nbsp;&nbsp;</td>";
                } else {
                    echo "<td style='text-align: right;'>0</td>";
                }
            }
            ?>
        </tr>
        <tr><td colspan="<?php echo $no + 4; ?>"><hr></td></tr>
    </table>
</fieldset>

<center><span style='color: #037CB0;'><i><b>Click UNPAID data to view details</b></i></span></center>
<!-- <table width="100%">
        <tr>
                <td style="text-align: right; width: 100% " id="Report_Button_Area">
                         
                </td>
        </tr>
</table> -->

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
<div id="Add_Details" style="width:50%;" >
    <center id='New_Items_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </center>
</div>
<div id="Unpaid_Details" style="width:50%;" >
    <center id='Unpaid_Details_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </center>
</div>

<script>
    function filter_list() {
        var Item_ID = document.getElementById("Item_ID").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;

        if (window.XMLHttpRequest) {
            myObjectGetDetails = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetDetails.overrideMimeType('text/xml');
        }

        myObjectGetDetails.onreadystatechange = function () {
            data209 = myObjectGetDetails.responseText;
            if (myObjectGetDetails.readyState == 4) {
                document.getElementById('Fieldset_List').innerHTML = data209;
            }
        }; //specify name of function that will handle server response........

        myObjectGetDetails.open('GET', 'Initial_Payments_Report.php?Item_ID=' + Item_ID + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&Sponsor_ID=' + Sponsor_ID, true);
        myObjectGetDetails.send();
    }
</script>

<script type="text/javascript">
    function Preview_list() {
        var Item_ID = document.getElementById("Item_ID").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;

        window.open('initialpaymentspreview.php?Item_ID=' + Item_ID + '&Sponsor_ID=' + Sponsor_ID + '&Date_From=' + Date_From + '&Date_To=' + Date_To, '_blank');
    }
</script>


<script type="text/javascript">
    function Edit_Initial_Items() {
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

        myObjectGetDetails.open('GET', 'Edit_Initial_Items.php', true);
        myObjectGetDetails.send();
    }
</script>



<script type="text/javascript">
    function Update_Product_List() { //updating items list
        if (window.XMLHttpRequest) {
            myObjectUpdateItem = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdateItem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateItem.overrideMimeType('text/xml');
        }

        myObjectUpdateItem.onreadystatechange = function () {
            data729 = myObjectUpdateItem.responseText;
            if (myObjectUpdateItem.readyState == 4) {
                document.getElementById('Product_Area').innerHTML = data729;
            }
        }; //specify name of function that will handle server response........

        myObjectUpdateItem.open('GET', 'Update_Initial_Items_List.php', true);
        myObjectUpdateItem.send();
    }
</script>

<script type="text/javascript">
    function Add_Items() {
        if (window.XMLHttpRequest) {
            myObjectAddItem = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectAddItem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectAddItem.overrideMimeType('text/xml');
        }

        myObjectAddItem.onreadystatechange = function () {
            data2922 = myObjectAddItem.responseText;
            if (myObjectAddItem.readyState == 4) {
                document.getElementById('New_Items_Area').innerHTML = data2922;
                $("#Add_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectAddItem.open('GET', 'Add_Initial_Items.php', true);
        myObjectAddItem.send();
    }
</script>

<script type="text/javascript">
    function Preview_Unpaid_Details(Sponsor_ID, Date_From, Date_To) {
        if (window.XMLHttpRequest) {
            myObjectAddItem = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectAddItem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectAddItem.overrideMimeType('text/xml');
        }

        myObjectAddItem.onreadystatechange = function () {
            data2922 = myObjectAddItem.responseText;
            if (myObjectAddItem.readyState == 4) {
                document.getElementById('Unpaid_Details_Area').innerHTML = data2922;
                $("#Unpaid_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectAddItem.open('GET', 'Preview_Unpaid_Details.php?Sponsor_ID=' + Sponsor_ID + '&Date_From=' + Date_From + '&Date_To=' + Date_To, true);
        myObjectAddItem.send();
    }
</script>

<script type="text/javascript">
    function Remove_Item(Item_Name, Item_ID) {
        var Confirm_Message = confirm("Are you sure you want to remove \n" + Item_Name);
        if (Confirm_Message == true) {
            if (window.XMLHttpRequest) {
                myObjectRemove = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectRemove = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectRemove.overrideMimeType('text/xml');
            }

            myObjectRemove.onreadystatechange = function () {
                data290 = myObjectRemove.responseText;
                if (myObjectRemove.readyState == 4) {
                    document.getElementById('Details_Area').innerHTML = data290;
                    filter_list();
                    Update_Product_List();
                }
            }; //specify name of function that will handle server response........

            myObjectRemove.open('GET', 'Remove_Initial_Items.php?Item_ID=' + Item_ID, true);
            myObjectRemove.send();
        }
    }
</script>

<script type="text/javascript">
    function getItemsList(Item_Category_ID) {
        document.getElementById("Search_Value").value = '';
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
        myObject.open('GET', 'Get_List_Of_Items_initial.php?Item_Category_ID=' + Item_Category_ID, true);
        myObject.send();
    }
</script>


<script type="text/javascript">
    function getItemsListFiltered(Item_Name) {
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
        myObjectFiltered.open('GET', 'Get_List_Of_Items_Filtered_Initial.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name, true);
        myObjectFiltered.send();
    }
</script>

<script type="text/javascript">
    function Add_Selected_Item(Item_ID) {
        if (window.XMLHttpRequest) {
            myObjectSelectedItem = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSelectedItem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSelectedItem.overrideMimeType('text/xml');
        }

        myObjectSelectedItem.onreadystatechange = function () {
            data121 = myObjectSelectedItem.responseText;
            if (myObjectSelectedItem.readyState == 4) {
                var feedback = data121;
                if (feedback == 'yes') {
                    Edit_Initial_Items();
                    Update_Product_List();
                } else if (feedback == 'no') {
                    alert("Item already added");
                } else {
                    alert("Invalid item selected");
                }

            }
        }; //specify name of function that will handle server response........
        myObjectSelectedItem.open('GET', 'Add_Selected_Item_Initial.php?Item_ID=' + Item_ID, true);
        myObjectSelectedItem.send();
    }
</script>

<script type="text/javascript">
    function Preview_Unpaid(Sponsor_ID, Date_From, Date_To) {
        window.open('initialunpaidreport.php?Sponsor_ID=' + Sponsor_ID + '&Date_From=' + Date_From + '&Date_To=' + Date_To, '_blank');
    }
</script>


<script type="text/javascript">
    function Preview_All_Unpaid(Date_From, Date_To) {
        //window.open('initialallunpaidreport.php?Date_From='+Date_From+'&Date_To='+Date_To,'_blank');
    }
</script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<script>
    $(document).ready(function () {
        $("#Display_Details").dialog({autoOpen: false, width: '50%', height: 500, title: 'EDIT INITIAL ITEMS', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#Add_Details").dialog({autoOpen: false, width: '30%', height: 500, title: 'ADD ITEMS', modal: true});
    });
</script>


<script>
    $(document).ready(function () {
        $("#Unpaid_Details").dialog({autoOpen: false, width: '95%', height: 600, title: 'INITIALS UNPAID DETAILS', modal: true});
    });
</script>

<?php
include("./includes/footer.php");
?>