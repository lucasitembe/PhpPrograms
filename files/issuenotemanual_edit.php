<script src='js/functions.js'></script>
<style>
    /*.labefor{display:block;width: 100% }*/
    .labefor:hover{background-color: #a8d1ff;cursor: pointer}
    label.labefor { width: 100%; }
</style>

<?php
    include_once("./includes/header.php");
    include_once("./includes/connection.php");

    include_once("./functions/department.php");
    include_once("./functions/employee.php");
    include_once("./functions/items.php");
    include_once("./functions/issuenotemanual.php");

    include_once("issuenotemanual_navigation.php");


    //get employee name
    if (isset($_SESSION['userinfo']['Employee_Name'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    } else {
        $Employee_Name = 'Unknown Requisitioner Officer';
    }

    //get employee id
    if (isset($_SESSION['userinfo']['Employee_ID'])) {
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    } else {
        $Employee_ID = 0;
    }

    //get branch id
    if (isset($_SESSION['userinfo']['Branch_ID'])) {
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    } else {
        $Branch_ID = 0;
    }

    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $IssueManual_ID = '';
    if (isset($_SESSION['Issue_Note_Manual']['Issue_ID'])) {
        $IssueManual_ID = $_SESSION['Issue_Note_Manual']['Issue_ID'];
    }

    if (isset($_GET['IssueManual_ID'])) {
        $IssueManual_ID = $_GET['IssueManual_ID'];
    }

    $IssueManual = array();
    if (!empty($IssueManual_ID)) {
        $IssueManual = Get_Issue_Note_Manual($IssueManual_ID);
    }

    if (isset($_SESSION['Storage_Info'])) {
        $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        $Current_Store_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
    }
?>

<form action='#' method='post' name='myForm' id='myForm' >
    <fieldset>
        <legend align='right'><b>Issue Note ( Manual )  ~ <?php echo $Current_Store_Name; ?></b></legend>
        <table width=100%>
            <tr>
                <td width='12%' style='text-align: right;'>Issue Number</td>
                <td width='5%'>
                    <?php if (isset($IssueManual_ID) && !empty($IssueManual_ID)) { ?>
                        <input type='text' name='IssueManual_ID' size='6' id='IssueManual_ID'
                               readonly='readonly' value='<?php echo $IssueManual_ID; ?>'>
                    <?php } else { ?>
                        <input type='text' name='IssueManual_ID' size='6'  id='IssueManual_ID' value='new'>
                    <?php } ?>
                </td>

                <td width='12%' style='text-align: right;'>Issue Date</td>
                <td width='16%'>
                    <?php
                        if (!empty($IssueManual_ID)) {
                            echo "<input type='text' readonly='readonly' name='Issue_Date' id='Issue_Date'";
                            echo "value='{$IssueManual['Issue_Date_And_Time']}'/>";
                        } else {
                            echo "<input type='text' readonly='readonly' name='Issue_Date' id='Issue_Date'/>";
                        }
                    ?>
                </td> 
                <td style='text-align: right;'>Issue By</td> 
                <td>
                    <input type='text' readonly='readonly'
                           value='<?php echo Get_Employee($IssueManual['Employee_Issuing'])['Employee_Name']; ?>'>
                </td>

            </tr>
            <tr>
                <td width='10%' style='text-align: right;'>Store Issuing</td>
                <td width='16%'>
                    <select name='Store_Issue_ID'  class="Issuevalue" id='Store_Issue_ID' style="width:100%" >
                        <?php
                            if (!empty($Issue_ID)) {
                                $Sub_Department = Get_Sub_Department($IssueManual['Store_Issue']);
                                echo "<option value='{$Sub_Department['Sub_Department_ID']}'>";
                                echo "{$Sub_Department['Sub_Department_Name']}";
                                echo "</option>";
                            } else {
                                $Sub_Department_List = Get_Stock_Balance_Sub_Departments();
                                foreach($Sub_Department_List as $Sub_Department) {
                                    if ($Sub_Department['Sub_Department_ID'] == $Current_Store_ID) {
                                        echo "<option value='{$Sub_Department['Sub_Department_ID']}' selected >";
                                        echo " {$Sub_Department['Sub_Department_Name']} ";
                                        echo "</option>";
                                    }
                                }
                            }
                        ?>
                    </select>
                </td>
                <td width='13%' style='text-align: right;'> Cost Center </td>
                <td >
                    <select name='Store_Need_ID' id='Store_Need_ID' style="width:100%">
                        <?php
                            $Sub_Department_List = Get_Sub_Department_All();
                            foreach($Sub_Department_List as $Sub_Department) {
                                ($IssueManual['Store_Need'] == $Sub_Department['Sub_Department_ID']) ? $Selected = "selected" : $Selected = "";
                                echo "<option value='{$Sub_Department['Sub_Department_ID']}' {$Selected}>";
                                echo "{$Sub_Department['Sub_Department_Name']}";
                                echo "</option>";
                            }
                        ?>
                    </select>
                </td>
                <td style='text-align: right;'>Employee Requested</td> 
                <td>
                    <select name='Employee_Requested' class="Issuevalue" id='Employee_Requested' style="width:100%">
                        <?php
                            $Employee_List = Get_Employee_All();
                            foreach($Employee_List as $Employee) {
                                ($IssueManual['Employee_Receiving'] == $Employee['Employee_ID']) ? $Selected = "selected" : $Selected = "";
                                echo "<option value='{$Employee['Employee_ID']}' {$Selected}>";
                                echo "{$Employee['Employee_Name']}";
                                echo "</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
        </table> 
        </center>
    </fieldset>

    <fieldset>   
        <center>
            <table width=100%>
                <tr>
                    <td width=25%>
                        <table width=100%>
                            <tr>
                                <td style='text-align: center;'>
                                    <select name='Classification' id='Classification' style="width: 100%"
                                        onchange='Get_Item_Balance_By_Classification(this.value)'>
                                        <option selected='All'>All</option>
                                        <?php
                                            $Classification_List = Get_Item_Classification();
                                            foreach($Classification_List as $Classification) {
                                                echo "<option value='{$Classification['Name']}'> {$Classification['Description']} </option>";
                                            }
                                        ?>

                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type='text' id='Search_Value' name='Search_Value' autocomplete='off'
                                           onkeyup='Get_Item_Balance_By_Classification_Filtered(this.value)' placeholder='Enter Item Name'>
                                </td>
                            </tr>			    
                            <tr>
                                <td>
                                    <fieldset style='overflow-y: scroll; height: 270px;' id='Items_Fieldset'>
                                        <table width='100%' style="background-color:white">
                                        <?php
                                            echo "<tr>";
                                            echo "<td colspan=2><b>Items</b></td>";
                                            echo "<td><b>OUM</b></td>";
                                            echo "<td><b>Balance</b></td>";
                                            echo "<td><b>last buying price</b></td>";
                                            echo "</tr>";

                                            $Item_Balance_List = Get_Item_Balance_By_All_Classification($Current_Store_ID, "", 500);
                                            foreach($Item_Balance_List as $Item_Balance) {
                                                echo "<tr class='labefor' >";
                                                echo "<td style='color:black; border:2px solid #ccc;text-align: left;'>";
                                                echo "<input type='radio' name='selection' id='{$Item_Balance['Item_ID']}'";
                                                echo " value='{$Item_Balance['Item_ID']}'";
                                                echo " onclick='Add_To_Issue_Note(this.value)'";
                                                echo "/></td>";
                                                echo "<td style='color:black; border:2px solid #ccc;text-align: left;'>";
                                                echo "<label class='labefor' for='{$Item_Balance['Item_ID']}'>{$Item_Balance['Product_Name']}</label>";
                                                echo "</td>";
                                                echo "<td><label class='labefor' for='{$Item_Balance['Item_ID']}'>{$Item_Balance['Unit_Of_Measure']}</label></td>";
                                                echo "<td><label class='labefor' for='{$Item_Balance['Item_ID']}'>{$Item_Balance['Item_Balance']}</label></td>";
                                                echo "<td><label class='labefor' for='{$Item_Balance['Item_ID']}'>{$Item_Balance['Last_Buying_Price']}</label></td>";
                                                echo "</tr>";
                                            }
                                        ?>
                                        </table>

                                    </fieldset>		
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table width=100%>
                            <tr>
                                <td>
                                    <fieldset style='overflow-y: scroll; height: 290px;' id='Items_Fieldset_List'>
                                        <?php
                                            echo "<table width='100%' border='0'><tbody>";
                                            echo "<tr>";
                                            echo "<td style='text-align: center; width: 4%;'>Sn</td>";
                                            echo "<td style='text-align: center; width: 40%;'>Item Name</td>";
                                            echo "<td style='text-align: center; width: 10%;'>Store Balance</td>";
                                            echo "<td style='text-align: center; width: 10%;'>Quantity Required</td>";
                                            echo "<td style='text-align: center; width: 10%;'>Quantity Issued</td>";
                                            echo "<td style='text-align: center; width: 10%;'>Remark</td>";
                                            echo "<td style='text-align: center; width: 7%;'>Remove</td>";
                                            echo "</tr>";

                                            $Issue_Manual_Item_List = Get_Issue_Note_Manual_Items($IssueManual_ID);
                                            $_SESSION['Issue_Note_Manual']['Items'] = $Issue_Manual_Item_List;

                                            $i = 1;
                                            foreach($Issue_Manual_Item_List as $Issue_Manual_Item) {
                                                echo "<tr>";

                                                echo "<td> <input type='text' value='{$i}'/>  </td>";

                                                echo "<td> <input type='text' readonly='readonly'
                                                            id='Product_Name_{$Issue_Manual_Item['Requisition_Item_ID']}'
                                                            value='{$Issue_Manual_Item['Product_Name']}'/>  </td>";

                                                echo "<td> <input type='text' readonly='readonly'
                                                            id='Store_Balance_{$Issue_Manual_Item['Requisition_Item_ID']}'
                                                            value='{$Issue_Manual_Item['Store_Balance']}'/>  </td>";

                                                echo "<td> <input type='text'
                                                            id='Quantity_Required_{$Issue_Manual_Item['Requisition_Item_ID']}'
                                                            value='{$Issue_Manual_Item['Quantity_Required']}'
                                                            onclick=\"removeZero(this)\"
                                                            onkeypress=\"numberOnly(this); Update_Quantity_Required(this.value,{$Issue_Manual_Item['Requisition_Item_ID']},{$Issue_Manual_Item['Item_ID']})\"
                                                            onkeyup=\"numberOnly(this); Update_Quantity_Required(this.value,{$Issue_Manual_Item['Requisition_Item_ID']},{$Issue_Manual_Item['Item_ID']})\"/>
                                                            </td>";

                                                echo "<td> <input type='text'
                                                            id='Quantity_Issued_{$Issue_Manual_Item['Requisition_Item_ID']}'
                                                            value='{$Issue_Manual_Item['Quantity_Issued']}'
                                                            onclick=\"removeZero(this)\"
                                                            onkeypress=\"numberOnly(this); Update_Quantity_Issued(this.value,{$Issue_Manual_Item['Requisition_Item_ID']},{$Issue_Manual_Item['Item_ID']})\"
                                                            onkeyup=\"numberOnly(this); Update_Quantity_Issued(this.value,{$Issue_Manual_Item['Requisition_Item_ID']},{$Issue_Manual_Item['Item_ID']})\" />
                                                            <input type='hidden' id='Previous_Quantity_Issued_{$Issue_Manual_Item['Requisition_Item_ID']}'
                                                            value='{$Issue_Manual_Item['Quantity_Issued']}' />
                                                            </td>";

                                                echo "<td> <input type='text'
                                                            id='Item_Remark_{$Issue_Manual_Item['Requisition_Item_ID']}'
                                                            value='{$Issue_Manual_Item['Item_Remark']}'
                                                            onkeypress=\"Update_Item_Remark(this.value,{$Issue_Manual_Item['Requisition_Item_ID']},{$Issue_Manual_Item['Item_ID']})\"
                                                            onkeyup=\"Update_Item_Remark(this.value,{$Issue_Manual_Item['Requisition_Item_ID']},{$Issue_Manual_Item['Item_ID']})\" />
                                                            </td>";

                                                echo "<td> <input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'
                                                            onclick='Confirm_Remove_Item(\"{$Issue_Manual_Item['Product_Name']}\", {$Issue_Manual_Item['Item_ID']})' />  </td>";

                                                echo "</tr>";
                                                $i++;
                                            }

                                            echo "</tbody></table>";
                                        ?>
                                    </fieldset>
                                </td>
                            </tr>

                            <tr>
                                <td id='Submit_Button_Area' style='text-align: right;'>
                                    <?php if (isset($IssueManual_ID) && $IssueManual_ID > 0) { ?>
                                        <table width=100%>
                                            <tr>
                                                <td style='text-align: right;'>Supervisor Username</td>
                                                <td>
                                                    <input type='text' name='Supervisor_Username' title='Supervisor Username'
                                                           id='Supervisor_Username' autocomplete='off'
                                                           placeholder='Supervisor Username' required='required'>
                                                </td>
                                                <td style='text-align: right;'>Supervisor Password</td>
                                                <td>
                                                    <input type='password' title='Supervisor Password' name='Supervisor_Password'
                                                           id='Supervisor_Password' autocomplete='off'
                                                           placeholder='Supervisor Password' required='required'>
                                                </td>
                                                <td style='text-align: right;'>
                                                    <input type='button' class='art-button-green' value='SUBMIT DISPOSAL'
                                                           onclick='Confirm_Submit_Disposal()'>
                                                </td>
                                            </tr>
                                        </table>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>
            </table>
        </center>
    </fieldset>

    <div id="Error_Message"></div>

    <link rel="stylesheet" href="css/select2.min.css" media="screen">
    <script src="media/js/jquery.js" type="text/javascript"></script>
    <script src="css/jquery-ui.js"></script>
    <script src="js/select2.min.js"></script>

<script type='text/javascript'>
    $(document).ready(function () {
        $('select').select2();
        $("#Error_Message").dialog({ autoOpen: false, width:'25%',height:150, title:'eHMS 2.0 ~ Error!',modal: true});

        addDatePicker($('#Issue_Date'));
    });

</script>

<script type='text/javascript'>
    function Error_Message(Error_Message) {
        $("#Error_Message").html(Error_Message);
        $("#Error_Message").dialog("open");
    }
</script>

<script type='text/javascript'>
    function Get_Item_Balance_By_Classification(Classification) {
        document.getElementById("Search_Value").value = '';
        Current_Store_ID = document.getElementById("storeissuign").value;

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
        };
        myObject.open('GET', 'issuenotemanual_get_item_balance_by_classification.php?Classification=' + Classification +
            "&Current_Store_ID=" + Current_Store_ID, true);
        myObject.send();
    }
</script>

<script type='text/javascript'>
    function Get_Item_Balance_By_Classification_Filtered(Item_Name) {
        Current_Store_ID = document.getElementById("Store_Issue_ID").value;
        Classification = document.getElementById("Classification").value;

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
        };
        myObject.open('GET', 'issuenotemanual_get_item_balance_by_classification.php?Classification=' + Classification +
            "&Current_Store_ID=" + Current_Store_ID + '&Item_Name=' + Item_Name, true);
        myObject.send();
    }
</script>

<script>
    function Add_To_Issue_Note(Item_ID){
        IssueManual_ID = document.getElementById("IssueManual_ID").value;

        Store_Issuing_ID = document.getElementById("Store_Issue_ID").value;
        Employee_Issuing_ID = <?php echo $Employee_ID; ?>;
        Store_Requesting_ID = document.getElementById("Store_Need_ID").value;
        Employee_Requesting_ID = document.getElementById("Employee_Requested").value;
        Branch_ID = <?php echo $Branch_ID; ?>;
        Issue_Date = document.getElementById("Issue_Date").value;

        if ((Store_Issuing_ID > 0 || Store_Issuing_ID != "") &&
            (Store_Requesting_ID > 0 || Store_Requesting_ID != "") &&
            (Employee_Requesting_ID > 0 || Employee_Requesting_ID != "") &&
            (Issue_Date != "")) {

            if (window.XMLHttpRequest) {
                myIssueManualObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myIssueManualObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myIssueManualObject.overrideMimeType('text/xml');
            }

            myIssueManualObject.onreadystatechange = function () {
                data = myIssueManualObject.responseText;
                if (myIssueManualObject.readyState == 4) {
                    if (data.trim() == "yes"){
                        Error_Message("Item Already Exists");
                    } else {
                        Add_Issue_Note_Item(Item_ID);
                    }
                }
            };
            myIssueManualObject.open('GET', 'issuenotemanual_edit_check_item_exists.php?IssueManual_ID=' + IssueManual_ID
                + '&Item_ID=' + Item_ID, true);
            myIssueManualObject.send();
        } else {
            Error_Message("PLEASE SELECT ISSUE DATE, ISSUING STORE, COST CENTER AND EMPLOYEE REQUESTED");
        }
    }
</script>

<script>
    function Add_Issue_Note_Item(Item_ID){
        IssueManual_ID = document.getElementById("IssueManual_ID").value;

        if (IssueManual_ID > 0) {
            if (window.XMLHttpRequest) {
                myIssueManualItemObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myIssueManualItemObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myIssueManualItemObject.overrideMimeType('text/xml');
            }

            myIssueManualItemObject.onreadystatechange = function () {
                data = myIssueManualItemObject.responseText;
                if (myIssueManualItemObject.readyState == 4) {
                    document.getElementById('Items_Fieldset_List').innerHTML = data;
                    showSaveButtons();
                }
            };
            myIssueManualItemObject.open('GET', 'issuenotemanual_edit_issue_note_item.php?IssueManual_ID=' + IssueManual_ID
                + '&Item_ID=' + Item_ID, true);
            myIssueManualItemObject.send();
        }
    }
</script>

<script type='text/javascript'>
    function Confirm_Remove_Item (Item_Name, Requisition_Item_ID) {
        IssueManual_ID = document.getElementById("IssueManual_ID").value;

        var Confirm_Message = confirm("Are you sure you want to remove \n" + Item_Name);
        var status;
        if (Confirm_Message == true) {
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }

            myObject.onreadystatechange = function () {
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    document.getElementById('Items_Fieldset_List').innerHTML = data;
                }
            };
            myObject.open('GET', 'issuenotemanual_edit_remove_item.php?Item_ID=' + Requisition_Item_ID
                + "&IssueManual_ID=" + IssueManual_ID, true);
            myObject.send();
        }
    }
</script>

<script type='text/javascript'>
    function Update_Quantity_Required (Quantity_Required, Requisition_Item_ID, Item_ID) {
        if (Quantity_Required >= 0) {
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }

            myObject.onreadystatechange = function () {
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    //document.getElementById('Items_Fieldset_List').innerHTML = data;
                }
            };
            myObject.open('GET', 'issuenotemanual_edit_update_quantity_required.php?Item_ID=' + Item_ID
                + "&Quantity_Required=" + Quantity_Required, true);
            myObject.send();
        }
    }
</script>

<script type='text/javascript'>
    function Update_Quantity_Issued (Quantity_Issued, Requisition_Item_ID, Item_ID) {
        Store_Balance = parseInt(document.getElementById("Store_Balance_"+Requisition_Item_ID).value);
        Previous_Quantity_Issued = parseInt(document.getElementById("Previous_Quantity_Issued_"+Requisition_Item_ID).value);
        if(Quantity_Issued == "") { Quantity_Issued = 0; }
        Quantity_Issued = parseInt(Quantity_Issued);

        /*if (Quantity_Issued > (Store_Balance + Previous_Quantity_Issued)) { allow negative balance
            Error_Message("YOU DO NOT HAVE SUFFIECIENT BALANCE");
            Quantity_Issued = Store_Balance + Previous_Quantity_Issued;
            document.getElementById("Quantity_Issued_"+Requisition_Item_ID).value = Store_Balance + Previous_Quantity_Issued;
        }

        if (Quantity_Issued >= 0) {*/
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }

            myObject.onreadystatechange = function () {
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    //document.getElementById('Items_Fieldset_List').innerHTML = data;
                }
            };
            myObject.open('GET', 'issuenotemanual_edit_update_quantity_issued.php?Item_ID=' + Item_ID
                + "&Quantity_Issued=" + Quantity_Issued, true);
            myObject.send();
        //}

        document.getElementById("Quantity_Issued_"+Requisition_Item_ID).value = Quantity_Issued;
    }

</script>

<script type='text/javascript'>
    function removeZero(element){
        Element_Value = $(element).val();
        if (Element_Value == 0) {
            $(element).val("");
        }
    }
</script>

<script type='text/javascript'>
    function Update_Item_Remark(Item_Remark, Requisition_Item_ID, Item_ID) {
        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function () {
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                //document.getElementById('Items_Fieldset_List').innerHTML = data;
            }
        };
        myObject.open('GET', 'issuenotemanual_edit_update_item_remark.php?Item_ID=' + Item_ID
            + "&Item_Remark=" + Item_Remark, true);
        myObject.send();
    }
</script>

<script type='text/javascript'>
    function Get_Selected_Item_Warning() {
            var Item_Name = document.getElementById("Item_Name").value;
        if (Item_Name != '' && Item_Name != null) {
            alert("Process Fail!!\n" + Item_Name + " already available from the selected items list\nYou can edit quantity, edit item remark or remove selected item");
        } else {
            alert("Process Fail!!\nSelected item already available from the selected items list\nYou can edit quantity, edit item remark or remove selected item");
        }
    }
</script>

<script type='text/javascript'>
    function showSaveButtons() {
        var thml = ""
        thml = "<table width=100%>";
        thml += " <tr>";
        thml += "<td style='text-align: right;'>Supervisor Username</td>";
        thml += "<td>";
        thml += " <input type='text' name='Supervisor_Username' title='Supervisor Username' id='Supervisor_Username' autocomplete='off' placeholder='Supervisor Username' required='required'>";
        thml += "</td>";
        thml += "<td style='text-align: right;'>Supervisor Password</td>";
        thml += "<td>";
        thml += " <input type='password' title='Supervisor Password' name='Supervisor_Password' id='Supervisor_Password' autocomplete='off' placeholder='Supervisor Password' required='required'>";
        thml += "</td>";
        thml += "<td style='text-align: right;'>";
        thml += "<input type='button' class='art-button-green' value='SUBMIT DISPOSAL' onclick='Confirm_Submit_Disposal()'>";
        thml += "</td>";
        thml += "</tr>";
        thml += " </table>";

        document.getElementById("Submit_Button_Area").innerHTML = thml;
    }
</script>

<script type='text/javascript'>
    function Confirm_Submit_Disposal() {
        var IssueManual_ID = document.getElementById("IssueManual_ID").value;
        var Supervisor_Username = document.getElementById("Supervisor_Username").value;
        var Supervisor_Password = document.getElementById("Supervisor_Password").value;

        var Store_Need_ID = document.getElementById("Store_Need_ID").value;
        var Employee_Requested = document.getElementById("Employee_Requested").value;
        var Issue_Date = document.getElementById("Issue_Date").value;

        if (IssueManual_ID == null || IssueManual_ID == '') {
            alert('An error has occured. Click ok to reload page.');
            window.location = window.location.href;
        }

        if (Supervisor_Username != null && Supervisor_Username != '' && Supervisor_Password != null && Supervisor_Password != '') {
            var r = confirm("Are you sure you want to process this issue note?\n\nClick OK to proceed");
            if (r == true) {
                $.ajax({
                    type: 'GET',
                    url: 'issuenotemanual_edit_process.php',
                    data: 'Supervisor_Username=' + Supervisor_Username +
                            '&Supervisor_Password=' + Supervisor_Password + '&IssueManual_ID=' + IssueManual_ID +
                            '&Store_Need_ID=' + Store_Need_ID + '&Employee_Requested=' + Employee_Requested + '&Issue_Date=' + Issue_Date,
                    cache: false,
                    success: function (feedback) {
                        if (feedback == '1') {
                            alert('Issue Note (Manual) Edited successifully');
                            window.location = 'issuenotemanual.php';
                        } else if (feedback == '2') {
                            Error_Message("Store Balance Might have changed. Please check again!!");
                            location.reload();
                        }  else if (feedback == '3') {
                            Error_Message("Some of the Quantity Issued might be more than Store Balance. Please check again!!");
                            location.reload();
                        } else if (feedback == '4') {
                            Error_Message("Unable To Update Issue Note. Please check again!!");
                            location.reload();
                        } else {
                            if (feedback == '0') {
                                alert("Invalid username or password");
                            } else {
                                alert("This Issue Note May Either Already Submitted or\n Contains No Items\n"+feedback);
                            }
                        }
                    }
                });
            }
        } else {
            if (Supervisor_Username == '' || Supervisor_Username == null) {
                document.getElementById("Supervisor_Username").focus();
                document.getElementById("Supervisor_Username").style = 'border: 3px solid red';
            }

            if (Supervisor_Password == '' || Supervisor_Password == null) {
                document.getElementById("Supervisor_Password").focus();
                document.getElementById("Supervisor_Password").style = 'border: 3px solid red';
            }
        }
    }
</script>
<?php
include("./includes/footer.php");
?>