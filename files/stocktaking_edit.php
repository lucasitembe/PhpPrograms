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
    include_once("./functions/stocktaking.php");

    include_once("stocktaking_navigation.php");


    //get employee name
    if (isset($_SESSION['userinfo']['Employee_Name'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    } else {
        $Employee_Name = 'Unknown Officer';
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

    $Stock_Taking_ID = '';
    if (isset($_SESSION['Stock_Taking']['Stock_Taking_ID'])) {
        $Stock_Taking_ID = $_SESSION['Stock_Taking']['Stock_Taking_ID'];
    }

    if (isset($_GET['Stock_Taking_ID'])) {
        $Stock_Taking_ID = $_GET['Stock_Taking_ID'];
    }

    $Stock_Taking = array();
    if (!empty($Stock_Taking_ID)) {
        $Stock_Taking = Get_Stock_Taking($Stock_Taking_ID);
    }

    if (isset($_SESSION['Storage_Info'])) {
        $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        $Current_Store_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
    }
?>

<form action='#' method='post' name='myForm' id='myForm' >
    <fieldset>
        <legend align='right'><b>Stock Taking  ~ <?php echo $Current_Store_Name; ?></b></legend>
        <table width=100%>
            <tr>
                <td width='12%' style='text-align: right;'>Stock Taking Number</td>
                <td width='5%'>
                    <?php if (isset($Stock_Taking_ID) && !empty($Stock_Taking_ID)) { ?>
                        <input type='text' name='Stock_Taking_ID' size='6' id='Stock_Taking_ID'
                               readonly='readonly' value='<?php echo $Stock_Taking_ID; ?>'>
                    <?php } else { ?>
                        <input type='text' name='Stock_Taking_ID' size='6'  id='Stock_Taking_ID' value='new'>
                    <?php } ?>
                </td>

                <td width='12%' style='text-align: right;'>Stock Taking Date</td>
                <td width='16%'>
                    <?php
                        if (!empty($Stock_Taking_ID)) {
                            echo "<input type='text' readonly='readonly' name='Stock_Taking_Date' id='Stock_Taking_Date' ";
                            echo "value='{$Stock_Taking['Stock_Taking_Date']}'/>";
                        } else {
                            echo "<input type='text' readonly='readonly' name='Stock_Taking_Date' id='Stock_Taking_Date'/>";
                        }
                    ?>
                </td> 
                <td style='text-align: right;'>Stock Taking Officer</td>
                <td>
                    <input type='text' readonly='readonly'  value='<?php echo $Employee_Name; ?>'>
                </td>

            </tr>
            <tr>
                <td width='10%' style='text-align: right;'>Stock Taking Location</td>
                <td width='16%'>
                    <select name='Stock_Taking_Location'  class="Stock_Taking_Location" id='Stock_Taking_Location' style="width:100%">
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
                <td width='13%' style='text-align: right;'>Stock Taking Description</td>
                <td colspan="3">
                    <?php
                        if (!empty($Stock_Taking_ID)) {
                            echo "<input type='text' id='Stock_Taking_Description' name='Stock_Taking_Description' ";
                            echo "value='{$Stock_Taking['Stock_Taking_Description']}'/>";
                        } else {
                            echo "<input type='text' id='Stock_Taking_Description' name='Stock_Taking_Description' />";
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
                                                echo " onclick='Add_To_Stock_Taking(this.value)'";
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
                                            echo "<td style='text-align: center; width: 10%;'>Under Quantity</td>";
                                            echo "<td style='text-align: center; width: 10%;'>Over Quantity</td>";
                                            echo "<td style='text-align: center; width: 10%;'>Store Balance After Stock Taking</td>";
                                            echo "<td style='text-align: center; width: 10%;'>Remark</td>";
                                            echo "<td style='text-align: center; width: 7%;'>Remove</td>";
                                            echo "</tr>";

                                            $Stock_Taking_Items = Get_Stock_Taking_Items($Stock_Taking_ID);
                                            $_SESSION['Stock_Taking']['Items'] = $Stock_Taking_Items;

                                            $i = 1;
                                            foreach($Stock_Taking_Items as $Stock_Taking_Item) {
                                                echo "<tr>";

                                                echo "<td> <input type='text' value='{$i}'/>  </td>";

                                                echo "<td> <input type='text' readonly='readonly'
                                                            id='Product_Name_{$Stock_Taking_Item['Stock_Taking_Item_ID']}'
                                                            value='{$Stock_Taking_Item['Product_Name']}'/>  </td>";

                                                echo "<td> <input type='text' readonly='readonly'
                                                            id='Store_Balance_{$Stock_Taking_Item['Stock_Taking_Item_ID']}'
                                                            value='{$Stock_Taking_Item['Store_Balance']}'/>  </td>";

                                                echo "<td> <input type='text'
                                                            id='Under_Quantity_{$Stock_Taking_Item['Stock_Taking_Item_ID']}'
                                                            value='{$Stock_Taking_Item['Under_Quantity']}'
                                                            onclick=\"removeZero(this)\"
                                                            onkeypress=\"numberOnly(this); Update_Under_Quantity(this.value,{$Stock_Taking_Item['Stock_Taking_Item_ID']},{$Stock_Taking_Item['Item_ID']})\"
                                                            onkeyup=\"numberOnly(this); Update_Under_Quantity(this.value,{$Stock_Taking_Item['Stock_Taking_Item_ID']},{$Stock_Taking_Item['Item_ID']})\"/>
                                                            <input type='hidden' id='Previous_Under_Quantity_{$Stock_Taking_Item['Stock_Taking_Item_ID']}'
                                                                value='{$Stock_Taking_Item['Under_Quantity']}' />
                                                            </td>";

                                                echo "<td> <input type='text'
                                                            id='Over_Quantity_{$Stock_Taking_Item['Stock_Taking_Item_ID']}'
                                                            value='{$Stock_Taking_Item['Over_Quantity']}'
                                                            onclick=\"removeZero(this)\"
                                                            onkeypress=\"numberOnly(this); Update_Over_Quantity(this.value,{$Stock_Taking_Item['Stock_Taking_Item_ID']},{$Stock_Taking_Item['Item_ID']})\"
                                                            onkeyup=\"numberOnly(this); Update_Over_Quantity(this.value,{$Stock_Taking_Item['Stock_Taking_Item_ID']},{$Stock_Taking_Item['Item_ID']})\"/>
                                                            <input type='hidden' id='Previous_Over_Quantity_{$Stock_Taking_Item['Stock_Taking_Item_ID']}'
                                                                value='{$Stock_Taking_Item['Over_Quantity']}' />
                                                            </td>";

                                                $Balance_After_Disposal = $Stock_Taking_Item['Store_Balance'];

                                                /*$Over_Quantity = $Stock_Taking_Item['Over_Quantity'];
                                                if ($Over_Quantity > 0) {
                                                    $Balance_After_Disposal = $Stock_Taking_Item['Store_Balance'] + $Over_Quantity;
                                                }

                                                $Under_Quantity = $Stock_Taking_Item['Under_Quantity'];
                                                if ($Under_Quantity > 0) {
                                                    $Balance_After_Disposal = $Stock_Taking_Item['Store_Balance'] - $Under_Quantity;
                                                }*/

                                                echo "<td> <input type='text'
                                                            id='Balance_After_Stock_Taking_{$Stock_Taking_Item['Stock_Taking_Item_ID']}'
                                                            value='{$Balance_After_Disposal}' readonly />
                                                            </td>";

                                                echo "<td> <input type='text'
                                                            id='Item_Remark_{$Stock_Taking_Item['Stock_Taking_Item_ID']}'
                                                            value='{$Stock_Taking_Item['Item_Remark']}'
                                                            onkeypress=\"Update_Item_Remark(this.value,{$Stock_Taking_Item['Stock_Taking_Item_ID']},{$Stock_Taking_Item['Item_ID']})\"
                                                            onkeyup=\"Update_Item_Remark(this.value,{$Stock_Taking_Item['Stock_Taking_Item_ID']},{$Stock_Taking_Item['Item_ID']})\" />
                                                            </td>";

                                                echo "<td> <input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'
                                                            onclick='Confirm_Remove_Item(\"{$Stock_Taking_Item['Product_Name']}\", {$Stock_Taking_Item['Item_ID']})' />  </td>";

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
                                    <?php if (isset($Stock_Taking_ID) && $Stock_Taking_ID > 0) { ?>
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
                                                    <input type='button' class='art-button-green' value='SUBMIT'
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

        addDatePicker($('#Stock_Taking_Date'));
    });
</script>

<script>
    function Error_Message(Error_Message) {
        $("#Error_Message").html(Error_Message);
        $("#Error_Message").dialog("open");
    }
</script>

<script>
    function Get_Item_Balance_By_Classification(Classification) {
        document.getElementById("Search_Value").value = '';
        Current_Store_ID = document.getElementById("Stock_Taking_Location").value;

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
        myObject.open('GET', 'stocktaking_get_item_balance_by_classification.php?Classification=' + Classification +
            "&Current_Store_ID=" + Current_Store_ID, true);
        myObject.send();
    }
</script>

<script>
    function Get_Item_Balance_By_Classification_Filtered(Item_Name) {
        Current_Store_ID = document.getElementById("Stock_Taking_Location").value;
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
        myObject.open('GET', 'stocktaking_get_item_balance_by_classification.php?Classification=' + Classification +
            "&Current_Store_ID=" + Current_Store_ID + '&Item_Name=' + Item_Name, true);
        myObject.send();
    }
</script>

<script>
    function Add_To_Stock_Taking(Item_ID){
        Stock_Taking_ID = document.getElementById("Stock_Taking_ID").value;

        Stock_Taking_Location = document.getElementById("Stock_Taking_Location").value;
        Stock_Taking_Description = document.getElementById("Stock_Taking_Description").value;
        Stock_Taking_Officer_ID = <?php echo $Employee_ID; ?>;

        Branch_ID = <?php echo $Branch_ID; ?>;
        Stock_Taking_Date = document.getElementById("Stock_Taking_Date").value;

        if ((Stock_Taking_Location > 0 || Stock_Taking_Location != "") &&
            (Stock_Taking_Date != "")) {

            if (Stock_Taking_ID == "new") {
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
                        Stock_Taking_ID = data;
                        if ($.isNumeric(Stock_Taking_ID)) {
                            document.getElementById("Stock_Taking_ID").value = Stock_Taking_ID;
                            Add_To_Stock_Taking_Item(Item_ID);
                        } else {
                            Error_Message(Stock_Taking_ID);
                        }
                    }
                };
                myDisposalObject.open('GET', 'stocktaking_add_stocktaking.php?Stock_Taking_ID=' + Stock_Taking_ID
                    + '&Stock_Taking_Location=' + Stock_Taking_Location + '&Stock_Taking_Description=' + Stock_Taking_Description
                    + '&Stock_Taking_Officer_ID=' + Stock_Taking_Officer_ID
                    + '&Branch_ID=' + Branch_ID + '&Stock_Taking_Date=' + Stock_Taking_Date
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
                        if (data == "yes"){
                            Error_Message("Item Already Exists");
                        } else {
                            Add_To_Stock_Taking_Item(Item_ID);
                        }
                    }
                };
                myDisposalObject.open('GET', 'stocktaking_edit_check_item_exists.php?Stock_Taking_ID=' + Stock_Taking_ID
                    + '&Item_ID=' + Item_ID, true);
                myDisposalObject.send();
            }
        } else {
            Error_Message("PLEASE SELECT STOCK TAKING DATE AND STOCK TAKING LOCATION");
        }
    }
</script>

<script>
    function Add_To_Stock_Taking_Item(Item_ID){
        Stock_Taking_ID = document.getElementById("Stock_Taking_ID").value;

        if (Stock_Taking_ID > 0) {
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
            myItemsDisposalItemObject.open('GET', 'stocktaking_edit_stocktaking_item.php?Stock_Taking_ID=' + Stock_Taking_ID
                + '&Item_ID=' + Item_ID, true);
            myItemsDisposalItemObject.send();
        }
    }
</script>

<script>
    function Confirm_Remove_Item (Item_Name, Item_ID) {
        Stock_Taking_ID = document.getElementById("Stock_Taking_ID").value;

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
            myObject.open('GET', 'stocktaking_edit_remove_item.php?Item_ID=' + Item_ID
                + "&Stock_Taking_ID=" + Stock_Taking_ID, true);
            myObject.send();
        }
    }
</script>

<script>
    function Update_Under_Quantity (Under_Quantity, Stock_Taking_Item_ID, Item_ID) {
        Store_Balance = parseInt(document.getElementById("Store_Balance_"+Stock_Taking_Item_ID).value);
        Previous_Under_Quantity = parseInt(document.getElementById("Previous_Under_Quantity_"+Stock_Taking_Item_ID).value);
        Previous_Over_Quantity = parseInt(document.getElementById("Previous_Over_Quantity_"+Stock_Taking_Item_ID).value);
        if(Under_Quantity == "") { Under_Quantity = 0; }
        Under_Quantity = parseInt(Under_Quantity);

        if (Under_Quantity > (Store_Balance+Previous_Under_Quantity)) {
            Error_Message("THIS TRANSACTION IS NOT ALLOWED");
            Under_Quantity = (Store_Balance+Previous_Under_Quantity);
            document.getElementById("Under_Quantity_"+Stock_Taking_Item_ID).value = (Store_Balance+Previous_Under_Quantity);
        }

        if (Under_Quantity > 0 && Previous_Under_Quantity == 0) {
            Balance_After_Disposal = (Store_Balance+Previous_Under_Quantity) - (Under_Quantity+Previous_Over_Quantity);
            document.getElementById("Balance_After_Stock_Taking_"+Stock_Taking_Item_ID).value = Balance_After_Disposal;
        }

        if (Under_Quantity > 0 && Previous_Under_Quantity > 0) {
            Balance_After_Disposal = (Store_Balance+Previous_Under_Quantity) - (Under_Quantity+Previous_Over_Quantity);
            document.getElementById("Balance_After_Stock_Taking_"+Stock_Taking_Item_ID).value = Balance_After_Disposal;
        }

        if (Under_Quantity >= 0) {
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }

            myObject.onreadystatechange = function () {
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    if (Under_Quantity > 0) {
                        document.getElementById("Over_Quantity_"+Stock_Taking_Item_ID).value = 0;
                        Update_Over_Quantity (0, Stock_Taking_Item_ID)
                    }
                }
            };
            myObject.open('GET', 'stocktaking_edit_update_under_quantity.php?Item_ID=' + Item_ID
                + "&Under_Quantity=" + Under_Quantity, true);
            myObject.send();
        }

        //document.getElementById("Under_Quantity_"+Stock_Taking_Item_ID).value = Under_Quantity;
    }
</script>

<script>
    function Update_Over_Quantity (Over_Quantity, Stock_Taking_Item_ID, Item_ID) {
        Store_Balance = parseInt(document.getElementById("Store_Balance_"+Stock_Taking_Item_ID).value);
        Previous_Under_Quantity = parseInt(document.getElementById("Previous_Under_Quantity_"+Stock_Taking_Item_ID).value);
        Previous_Over_Quantity = parseInt(document.getElementById("Previous_Over_Quantity_"+Stock_Taking_Item_ID).value);
        if(Over_Quantity == "") { Over_Quantity = 0; }
        Over_Quantity = parseInt(Over_Quantity);

        if (Over_Quantity > 0 && Previous_Under_Quantity == 0) {
            Balance_After_Disposal = (Store_Balance + (Over_Quantity+Previous_Under_Quantity)) - Previous_Over_Quantity;
            document.getElementById("Balance_After_Stock_Taking_"+Stock_Taking_Item_ID).value = Balance_After_Disposal;
        }

        if (Over_Quantity > 0 && Previous_Under_Quantity > 0) {
            Balance_After_Disposal = (Store_Balance + (Over_Quantity+Previous_Under_Quantity)) - Previous_Over_Quantity;
            document.getElementById("Balance_After_Stock_Taking_"+Stock_Taking_Item_ID).value = Balance_After_Disposal;
        }

        if (Over_Quantity >= 0) {
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }

            myObject.onreadystatechange = function () {
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    if (Over_Quantity > 0) {
                        document.getElementById("Under_Quantity_"+Stock_Taking_Item_ID).value = 0;
                        Update_Under_Quantity (0, Stock_Taking_Item_ID)
                    }
                }
            };
            myObject.open('GET', 'stocktaking_edit_update_over_quantity.php?Item_ID=' + Item_ID
                + "&Over_Quantity=" + Over_Quantity, true);
            myObject.send();
        }

        //document.getElementById("Over_Quantity_"+Stock_Taking_Item_ID).value = Over_Quantity;
    }
</script>

<script>
    function removeZero(element){
        Element_Value = $(element).val();
        if (Element_Value == 0) {
            $(element).val("");
        }
    }
</script>

<script>
    function Update_Item_Remark(Item_Remark, Stock_Taking_Item_ID, Item_ID) {
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
        myObject.open('GET', 'stocktaking_edit_update_item_remark.php?Item_ID=' + Item_ID
            + "&Item_Remark=" + Item_Remark, true);
        myObject.send();
    }
</script>

<script>
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
        thml += "<input type='button' class='art-button-green' value='SUBMIT' onclick='Confirm_Submit_Disposal()'>";
        thml += "</td>";
        thml += "</tr>";
        thml += " </table>";

        document.getElementById("Submit_Button_Area").innerHTML = thml;
    }
</script>

<script>
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
</script>

<script>
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
                document.getElementById('Stock_Taking_Date').value = data28;
            }
        }; //specify name of function that will handle server response........

        myObjectUpdateCreatedDate.open('GET', 'Update_Disposal_Created_Date.php', true);
        myObjectUpdateCreatedDate.send();
    }
</script>

<script>
    function Validate_Disposal_Value() {
        var Quantity = document.getElementById("Quantity").value;
        var Balance = document.getElementById("Balance").value;
        if (Quantity <= 0) {
            alert("Disposal Value Must Be Greater Than Zero");
            document.getElementById("Quantity").value = '';
            document.getElementById("Quantity").focus();
        } else if (Quantity > Balance) {
            alert("Disposal Value Must Not Be Greater Than Balance");
            document.getElementById("Quantity").value = '';
            document.getElementById("Quantity").focus();
        }
    }
</script>

<script>
    function Process_Disposal_Function(Stock_Taking_ID) {
        var Supervisor_Username = document.getElementById("Supervisor_Username").value;
        var Supervisor_Password = document.getElementById("Supervisor_Password").value;
        document.location = 'Process_Disposal.php?Stock_Taking_ID=' + Stock_Taking_ID + '&Supervisor_Username=' + Supervisor_Username + '&Supervisor_Password=' + Supervisor_Password;
    }
</script>

<script>
    function Confirm_Process_Disposal_Function(Stock_Taking_ID) {
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
                        Process_Disposal_Function(Stock_Taking_ID);
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
</script>

<script>
    function Confirm_Submit_Disposal() {
        var Stock_Taking_ID = document.getElementById("Stock_Taking_ID").value;
        var Supervisor_Username = document.getElementById("Supervisor_Username").value;
        var Supervisor_Password = document.getElementById("Supervisor_Password").value;

        if (Stock_Taking_ID == null || Stock_Taking_ID == '') {
            alert('An error has occured. Click ok to reload page.');
            //window.location = window.location.href;
        }

        if (Supervisor_Username != null && Supervisor_Username != '' && Supervisor_Password != null && Supervisor_Password != '') {
            var r = confirm("Are you sure you want to process this issue note?\n\nClick OK to proceed");
            if (r == true) {
                $.ajax({
                    type: 'GET',
                    url: 'stocktaking_edit_process.php',
                    data: 'Supervisor_Username=' + Supervisor_Username + '&Supervisor_Password=' + Supervisor_Password + '&Stock_Taking_ID=' + Stock_Taking_ID,
                    cache: false,
                    success: function (feedback) {
                        if (feedback == '1') {
                            alert('Stock Taking saved successifully');
                            window.location = 'stocktaking.php';
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