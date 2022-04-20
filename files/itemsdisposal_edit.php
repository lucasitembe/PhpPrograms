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
    include_once("./functions/itemsdisposal.php");

    include_once("itemsdisposal_navigation.php");


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

    $Disposal_ID = '';
    if (isset($_SESSION['Disposal_Edit']['Disposal_ID'])) {
        $Disposal_ID = $_SESSION['Disposal_Edit']['Disposal_ID'];
    }

    if (isset($_GET['Disposal_ID'])) {
        $Disposal_ID = $_GET['Disposal_ID'];
    }

    $Item_Disposal = array();
    if (!empty($Disposal_ID)) {
        $Item_Disposal = Get_Items_Disposal($Disposal_ID);
        $_SESSION['Disposal_Edit'] = $Item_Disposal;
    }

    if (isset($_SESSION['Storage_Info'])) {
        $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        $Current_Store_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
    }
?>

<form action='#' method='post' name='myForm' id='myForm' >
    <fieldset>
        <legend align='right'><b>Disposal  ~ <?php echo $Current_Store_Name; ?></b></legend>
        <table width=100%>
            <tr>
                <td width='12%' style='text-align: right;'>Disposal Number</td>
                <td width='5%'>
                    <?php if (isset($Disposal_ID) && !empty($Disposal_ID)) { ?>
                        <input type='text' name='Disposal_ID' size='6' id='Disposal_ID'
                               readonly='readonly' value='<?php echo $Disposal_ID; ?>'>
                    <?php } else { ?>
                        <input type='text' name='Disposal_ID' size='6'  id='Disposal_ID' value='new'>
                    <?php } ?>
                </td>

                <td width='12%' style='text-align: right;'>Disposal Date</td>
                <td width='16%'>
                    <?php
                        if (!empty($Disposal_ID)) {
                            echo "<input type='text' readonly='readonly' name='Disposal_Date' id='Disposal_Date' ";
                            echo "value='{$Item_Disposal['Disposed_Date']}'/>";
                        } else {
                            echo "<input type='text' readonly='readonly' name='Disposal_Date' id='Disposal_Date'/>";
                        }
                    ?>
                </td> 
                <td style='text-align: right;'>Disposing Officer</td>
                <td>
                    <input type='text' readonly='readonly'  value='<?php echo $Employee_Name; ?>'>
                </td>

            </tr>
            <tr>
                <td width='10%' style='text-align: right;'>Disposal Location</td>
                <td width='16%'>
                    <select name='Disposal_Location'  class="Disposal_Location" id='Disposal_Location' style="width:100%">
                        <option></option>
                        <?php
                            $Sub_Department_List = Get_Sub_Department_By_Department_Nature('Storage And Supply');
                            foreach($Sub_Department_List as $Sub_Department) {
                                ($Sub_Department['Sub_Department_ID'] == $Current_Store_ID) ? $Selected_Sub_Department = 'selected' : $Selected_Sub_Department = '';
                                echo "<option value='{$Sub_Department['Sub_Department_ID']}' {$Selected_Sub_Department}>";
                                echo "{$Sub_Department['Sub_Department_Name']}";
                                echo "</option>";
                            }
                        ?>
                    </select>
                </td>
                <td width='13%' style='text-align: right;'>Disposal Description</td>
                <td colspan="3">
                    <?php
                        if (!empty($Disposal_ID)) {
                            echo "<input type='text' id='Disposal_Description' name='Disposal_Description' ";
                            echo "value='{$Item_Disposal['Disposal_Description']}'/>";
                        } else {
                            echo "<input type='text' id='Disposal_Description' name='Disposal_Description' />";
                        }
                    ?>
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
                                            $Item_Classification_List = Get_Item_Classification();
                                            foreach($Item_Classification_List as $Item_Classification) {
                                                echo "<option value='{$Item_Classification['Name']}' > {$Item_Classification['Description']} </option>";
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
                                                echo " onclick='Add_To_Items_Disposal(this.value)'";
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
                                            echo "<td style='text-align: center; width: 10%;'>Quantity Disposed</td>";
                                            echo "<td style='text-align: center; width: 10%;'>Balance After Disposal</td>";
                                            echo "<td style='text-align: center; width: 10%;'>Remark</td>";
                                            echo "<td style='text-align: center; width: 7%;'>Remove</td>";
                                            echo "</tr>";

                                            $Items_Disposal_Item_List = Get_Items_Disposal_Items($Disposal_ID);
                                            $_SESSION['Disposal_Edit']['Items'] = $Items_Disposal_Item_List;

                                            $i = 1;
                                            foreach($Items_Disposal_Item_List as $Disposal_Item) {
                                                echo "<tr>";

                                                echo "<td> <input type='text' value='{$i}'/>  </td>";

                                                echo "<td> <input type='text' readonly='readonly'
                                                            id='Product_Name_{$Disposal_Item['Disposal_Item_ID']}'
                                                            value='{$Disposal_Item['Product_Name']}'/>  </td>";

                                                echo "<td> <input type='text' readonly='readonly'
                                                            id='Store_Balance_{$Disposal_Item['Disposal_Item_ID']}'
                                                            value='{$Disposal_Item['Store_Balance']}'/>  </td>";

                                                echo "<td> <input type='text'
                                                            id='Quantity_Disposed_{$Disposal_Item['Disposal_Item_ID']}'
                                                            value='{$Disposal_Item['Quantity_Disposed']}'
                                                            onclick=\"removeZero(this)\"
                                                            onkeypress=\"numberOnly(this); Update_Quantity_Disposed(this.value,{$Disposal_Item['Disposal_Item_ID']},{$Disposal_Item['Item_ID']})\"
                                                            onkeyup=\"numberOnly(this); Update_Quantity_Disposed(this.value,{$Disposal_Item['Disposal_Item_ID']},{$Disposal_Item['Item_ID']})\"/>
                                                            <input type='hidden' id='Previous_Quantity_Disposed_{$Disposal_Item['Disposal_Item_ID']}'
                                                            value='{$Disposal_Item['Quantity_Disposed']}' />
                                                            </td>";

                                                $Balance_After_Disposal = 0;//$Disposal_Item['Store_Balance'] - $Disposal_Item['Quantity_Disposed'];

                                                echo "<td> <input type='text'
                                                            id='Balance_After_Disposal_{$Disposal_Item['Disposal_Item_ID']}'
                                                            value='{$Balance_After_Disposal}' readonly />
                                                            </td>";

                                                echo "<td> <input type='text'
                                                            id='Item_Remark_{$Disposal_Item['Disposal_Item_ID']}'
                                                            value='{$Disposal_Item['Item_Remark']}'
                                                            onkeypress=\"Update_Item_Remark(this.value,{$Disposal_Item['Disposal_Item_ID']},{$Disposal_Item['Item_ID']})\"
                                                            onkeyup=\"Update_Item_Remark(this.value,{$Disposal_Item['Disposal_Item_ID']},{$Disposal_Item['Item_ID']})\" />
                                                            </td>";

                                                echo "<td> <input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'
                                                            onclick='Confirm_Remove_Item(\"{$Disposal_Item['Product_Name']}\", {$Disposal_Item['Item_ID']})' />  </td>";

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
                                    <?php if (isset($Disposal_ID) && $Disposal_ID > 0) { ?>
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

            addDatePicker($('#Disposal_Date'));
        });

        function Error_Message(Error_Message) {
            $("#Error_Message").html(Error_Message);
            $("#Error_Message").dialog("open");
        }
    </script>


    <script>
    function Get_Item_Balance_By_Classification(Classification) {
        document.getElementById("Search_Value").value = '';
        Current_Store_ID = document.getElementById("Disposal_Location").value;

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
        myObject.open('GET', 'itemsdisposal_get_item_balance_by_classification.php?Classification=' + Classification +
            "&Current_Store_ID=" + Current_Store_ID, true);
        myObject.send();
    }

    function Get_Item_Balance_By_Classification_Filtered(Item_Name) {
        Current_Store_ID = document.getElementById("Disposal_Location").value;
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
        myObject.open('GET', 'itemsdisposal_get_item_balance_by_classification.php?Classification=' + Classification +
            "&Current_Store_ID=" + Current_Store_ID + '&Item_Name=' + Item_Name, true);
        myObject.send();
    }

    function Add_To_Items_Disposal(Item_ID){
        Disposal_ID = document.getElementById("Disposal_ID").value;

        Disposal_Location = document.getElementById("Disposal_Location").value;
        Disposal_Description = document.getElementById("Disposal_Description").value;
        Disposing_Officer_ID = <?php echo $Employee_ID; ?>;

        Branch_ID = <?php echo $Branch_ID; ?>;
        Disposal_Date = document.getElementById("Disposal_Date").value;

        if ((Disposal_Location > 0 || Disposal_Location != "") &&
            (Disposal_Date != "")) {

            if (Disposal_ID == "new") {
                /***
                 * THIS CASE WILL NEVER HAPPEN
                 * /
                /*if (window.XMLHttpRequest) {
                    myDisposalObject = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myDisposalObject = new ActiveXObject('Micrsoft.XMLHTTP');
                    myDisposalObject.overrideMimeType('text/xml');
                }

                myDisposalObject.onreadystatechange = function () {
                    data = myDisposalObject.responseText;
                    if (myDisposalObject.readyState == 4) {
                        Disposal_ID = data;
                        if ($.isNumeric(Disposal_ID)) {
                            document.getElementById("Disposal_ID").value = Disposal_ID;
                            Add_To_Items_Disposal_Item(Item_ID);
                        } else {
                            Error_Message(Disposal_ID);
                        }
                    }
                };
                myDisposalObject.open('GET', 'itemsdisposal_add_items_disposal.php?Disposal_ID=' + Disposal_ID
                    + '&Disposal_Location=' + Disposal_Location + '&Disposal_Description=' + Disposal_Description
                    + '&Disposing_Officer_ID=' + Disposing_Officer_ID
                    + '&Branch_ID=' + Branch_ID + '&Disposal_Date=' + Disposal_Date
                    + '&Item_ID=' + Item_ID, true);
                myDisposalObject.send();*/
            } else {
                if (window.XMLHttpRequest) {
                    myDisposalObject = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myDisposalObject = new ActiveXObject('Micrsoft.XMLHTTP');
                    myDisposalObject.overrideMimeType('text/xml');
                }

                myDisposalObject.onreadystatechange = function () {
                    data = myDisposalObject.responseText;
                    if (myDisposalObject.readyState == 4) {
                        //alert(data);
                        if (data.trim() == "yes"){
                            Error_Message("Item Already Exists");
                        } else {
                            Add_To_Items_Disposal_Item(Item_ID);
                        }
                    }
                };
                myDisposalObject.open('GET', 'itemsdisposal_edit_check_item_exists.php?Disposal_ID=' + Disposal_ID
                    + '&Item_ID=' + Item_ID, true);
                myDisposalObject.send();
            }
        } else {
            Error_Message("PLEASE SELECT DISPOSAL DATE AND DISPOSAL LOCATION");
        }
    }

    function Add_To_Items_Disposal_Item(Item_ID){
        Disposal_ID = document.getElementById("Disposal_ID").value;

        if (Disposal_ID > 0) {
            if (window.XMLHttpRequest) {
                myItemsDisposalItemObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myItemsDisposalItemObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myItemsDisposalItemObject.overrideMimeType('text/xml');
            }

            myItemsDisposalItemObject.onreadystatechange = function () {
                data = myItemsDisposalItemObject.responseText;
                if (myItemsDisposalItemObject.readyState == 4) {
                    document.getElementById('Items_Fieldset_List').innerHTML = data;
                    showSaveButtons();
                }
            };
            myItemsDisposalItemObject.open('GET', 'itemsdisposal_edit_items_disposal_item.php?Disposal_ID=' + Disposal_ID
                + '&Item_ID=' + Item_ID, true);
            myItemsDisposalItemObject.send();
        }
    }

    function Confirm_Remove_Item (Item_Name, Disposal_Item_ID) {
        Disposal_ID = document.getElementById("Disposal_ID").value;

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
            myObject.open('GET', 'itemsdisposal_edit_remove_item.php?Item_ID=' + Disposal_Item_ID
                + "&Disposal_ID=" + Disposal_ID, true);
            myObject.send();
        }
    }

    function Update_Quantity_Disposed (Quantity_Disposed, Disposal_Item_ID, Item_ID) {
        Store_Balance = parseInt(document.getElementById("Store_Balance_"+Disposal_Item_ID).value);
        Previous_Quantity_Disposed = parseInt(document.getElementById("Previous_Quantity_Disposed_"+Disposal_Item_ID).value);
        if(Quantity_Disposed == "") { Quantity_Disposed = 0; }
        Quantity_Disposed = parseInt(Quantity_Disposed);

        if (Quantity_Disposed > (Store_Balance + Previous_Quantity_Disposed)) {
            Error_Message("YOU DO NOT HAVE SUFFIECIENT BALANCE");
            Quantity_Disposed = Store_Balance + Previous_Quantity_Disposed;
            document.getElementById("Quantity_Disposed_"+Disposal_Item_ID).value = Store_Balance + Previous_Quantity_Disposed;
        }

        Balance_After_Disposal = Store_Balance + ( Previous_Quantity_Disposed - Quantity_Disposed);
        document.getElementById("Balance_After_Disposal_"+Disposal_Item_ID).value = Balance_After_Disposal;

        if (Quantity_Disposed >= 0) {
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
            myObject.open('GET', 'itemsdisposal_edit_update_quantity_disposed.php?Item_ID=' + Item_ID
                + "&Quantity_Disposed=" + Quantity_Disposed, true);
            myObject.send();
        }

        document.getElementById("Quantity_Disposed_"+Disposal_Item_ID).value = Quantity_Disposed;
    }

    function removeZero(element){
        Element_Value = $(element).val();
        if (Element_Value == 0) {
            $(element).val("");
        }
    }

    function Update_Item_Remark(Item_Remark, Disposal_Item_ID, Item_ID) {
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
        myObject.open('GET', 'itemsdisposal_edit_update_item_remark.php?Item_ID=' + Item_ID
            + "&Item_Remark=" + Item_Remark, true);
        myObject.send();
    }

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

    function clearContent() {
        document.getElementById("Item_ID").value = '';
        document.getElementById("Item_Name").value = '';
        document.getElementById("Quantity_Required").value = '';
        document.getElementById("Item_Remark").value = '';
        document.getElementById("Quantity_Issued").value = '';
        document.getElementById("Balance").value = '';

        //$.fn.select2.defaults.reset();
        $('#storeissuign,#constcenter,#employee_requested').select2('val', '');
        //document.getElementById("employee_requested").value='';
    }

    function updateDisposalCreatedDate() {
        if (window.XMLHttpRequest) {
            myObjectUpdateCreatedDate = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdateCreatedDate = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateCreatedDate.overrideMimeType('text/xml');
        }
        myObjectUpdateCreatedDate.onreadystatechange = function () {
            data28 = myObjectUpdateCreatedDate.responseText;
            if (myObjectUpdateCreatedDate.readyState == 4) {
                document.getElementById('Disposal_Date').value = data28;
            }
        }; //specify name of function that will handle server response........

        myObjectUpdateCreatedDate.open('GET', 'Update_Disposal_Created_Date.php', true);
        myObjectUpdateCreatedDate.send();
    }

        function Validate_Disposal_Value() {
            var Quantity_Disposed = document.getElementById("Quantity_Disposed").value;
            var Balance = document.getElementById("Balance").value;
            if (Quantity_Disposed <= 0) {
                alert("Disposal Value Must Be Greater Than Zero");
                document.getElementById("Quantity_Disposed").value = '';
                document.getElementById("Quantity_Disposed").focus();
            } else if (Quantity_Disposed > Balance) {
                alert("Disposal Value Must Not Be Greater Than Balance");
                document.getElementById("Quantity_Disposed").value = '';
                document.getElementById("Quantity_Disposed").focus();
            }
        }

        function Process_Disposal_Function(Disposal_ID) {
            var Supervisor_Username = document.getElementById("Supervisor_Username").value;
            var Supervisor_Password = document.getElementById("Supervisor_Password").value;
            document.location = 'Process_Disposal.php?Disposal_ID=' + Disposal_ID + '&Supervisor_Username=' + Supervisor_Username + '&Supervisor_Password=' + Supervisor_Password;
        }

        function Confirm_Process_Disposal_Function(Disposal_ID) {
            var Supervisor_Username = document.getElementById("Supervisor_Username").value;
            var Supervisor_Password = document.getElementById("Supervisor_Password").value;

            if (Supervisor_Username != null && Supervisor_Username != '' && Supervisor_Password != null && Supervisor_Password != '') {
                if (window.XMLHttpRequest) {
                    myObjectConfirmDisposal = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectConfirmDisposal = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectConfirmDisposal.overrideMimeType('text/xml');
                }

                myObjectConfirmDisposal.onreadystatechange = function () {
                    mydata = myObjectConfirmDisposal.responseText;
                    if (myObjectConfirmDisposal.readyState == 4) {
                        var mrejesho = mydata;
                        if (mrejesho == 'Yes') {
                            Process_Disposal_Function(Disposal_ID);
                        } else {
                            alert("Invalid Supervisor Username Or Password");
                        }
                    }
                }; //specify name of function that will handle server response........

                myObjectConfirmDisposal.open('GET', 'Process_Disposal_Check_Supervisor_Authentication.php?Supervisor_Username=' + Supervisor_Username + '&Supervisor_Password=' + Supervisor_Password, true);
                myObjectConfirmDisposal.send();
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

        function Confirm_Submit_Disposal() {
            var Disposal_ID = document.getElementById("Disposal_ID").value;
            var Supervisor_Username = document.getElementById("Supervisor_Username").value;
            var Supervisor_Password = document.getElementById("Supervisor_Password").value;

            if (Disposal_ID == null || Disposal_ID == '') {
                alert('An error has occured. Click ok to reload page.');
                //window.location = window.location.href;
            }

            if (Supervisor_Username != null && Supervisor_Username != '' && Supervisor_Password != null && Supervisor_Password != '') {
                var r = confirm("Are you sure you want to process this issue note?\n\nClick OK to proceed");
                if (r == true) {
                    $.ajax({
                        type: 'GET',
                        url: 'itemsdisposal_edit_process.php',
                        data: 'Supervisor_Username=' + Supervisor_Username + '&Supervisor_Password=' + Supervisor_Password + '&Disposal_ID=' + Disposal_ID,
                        cache: false,
                        success: function (feedback) {
                            if (feedback == '1') {
                                alert('Disposal Edited Successfully');
                                window.location = 'itemsdisposal.php';
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