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
    include_once("./functions/supplier.php");
    include_once("./functions/employee.php");
    include_once("./functions/items.php");
    include_once("./functions/returnoutward.php");

    include_once("returnoutward_navigation.php");


    //get employee name
    if (isset($_SESSION['userinfo']['Employee_Name'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    } else {
        $Employee_Name = 'Unknown Requisitioner Officer';
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

    $Outward_ID = '';
    if (isset($_SESSION['Return_Outward']['Outward_ID'])) {
        $Outward_ID = $_SESSION['Return_Outward']['Outward_ID'];
    }

    if (isset($_GET['Outward_ID'])) {
        $Outward_ID = $_GET['Outward_ID'];
    }

    $Return_Outward = array();
    if (!empty($Outward_ID)) {
        $Return_Outward = Get_Return_Outward($Outward_ID);
    }

    if (isset($_SESSION['Storage_Info'])) {
        $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        $Current_Store_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
    }
?>

<form action='#' method='post' name='myForm' id='myForm' >
    <fieldset>
        <legend align='right'><b>Return Outward (Edit)  ~ <?php echo $Current_Store_Name; ?></b></legend>
        <table width=100%>
            <tr>
                <td width='12%' style='text-align: right;'>Document Number</td>
                <td width='5%'>
                    <?php if (isset($Outward_ID) && !empty($Outward_ID)) { ?>
                        <input type='text' name='Outward_ID' size='6' id='Outward_ID'
                               readonly='readonly' value='<?php echo $Outward_ID; ?>'>
                    <?php } else { ?>
                        <input type='text' name='Outward_ID' size='6'  id='Outward_ID' value='new'>
                    <?php } ?>
                </td>

                <td width='12%' style='text-align: right;'>Transaction Date</td>
                <td width='16%'>
                    <?php
                        if (!empty($Outward_ID)) {
                            echo "<input type='text' readonly='readonly' name='Transaction_Date' id='Transaction_Date'";
                            echo "value='{$Return_Outward['Outward_Date']}'/>";
                        }
                    ?>
                </td> 
                <td style='text-align: right;'>Issue By</td> 
                <td>
                    <?php
                        if (!empty($Outward_ID)) {
                            $Posted_By = Get_Employee($Return_Outward['Employee_ID']);
                            echo "<input type='text' readonly='readonly' ";
                            echo "value='{$Posted_By['Employee_Name']}'/>";
                        }
                    ?>
                </td>

            </tr>
            <tr>
                <td width='10%' style='text-align: right;'>Store</td>
                <td width='16%'>
                    <select name='Sub_Department_ID'  class="Issuevalue" id='Sub_Department_ID' style="width:100%" >
                        <?php
                            if (!empty($Outward_ID)) {
                                $Sub_Department = Get_Sub_Department($Return_Outward['Sub_Department_ID']);
                                echo "<option value='{$Sub_Department['Sub_Department_ID']}'>";
                                echo "{$Sub_Department['Sub_Department_Name']}";
                                echo "</option>";
                            }
                        ?>
                    </select>
                </td>
                <td width='13%' style='text-align: right;'> Receiving Supplier </td>
                <td >
                    <select name='Supplier_ID' class="Issuevalue" id='Supplier_ID' style="width:100%">
                        <?php
                            if (!empty($Outward_ID)) {
                                $Supplier = Get_Supplier($Return_Outward['Supplier_ID']);
                                echo "<option value='{$Supplier['Supplier_ID']}'>";
                                echo "{$Supplier['Supplier_Name']}";
                                echo "</option>";
                            } 
                        ?>
                    </select>
                </td>
                <td> </td>
                <td> </td>
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
                                        <!-- This is where all the items will be genated -->
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
                                            echo "<td style='text-align: center; width: 10%;'>Quantity Returned</td>";
                                            echo "<td style='text-align: center; width: 10%;'>Remark</td>";
                                            echo "<td style='text-align: center; width: 7%;'>Remove</td>";
                                            echo "</tr>";

                                            $Return_Outward_Item_List = Get_Return_Outward_Items($Outward_ID);
                                            $_SESSION['Return_Outward']['Items'] = $Return_Outward_Item_List;

                                            $i = 1;
                                            foreach($Return_Outward_Item_List as $Return_Outward_Item) {

                                                echo "<tr>";

                                                echo "<td> <input type='text' value='{$i}'/>  </td>";

                                                echo "<td> <input type='text' readonly='readonly'
                                                                id='Product_Name_{$Return_Outward_Item['Outward_Item_ID']}'
                                                                value='{$Return_Outward_Item['Product_Name']}'/>  </td>";

                                                echo "<td> <input type='text' readonly='readonly'
                                                                id='Store_Balance_{$Return_Outward_Item['Outward_Item_ID']}'
                                                                value='{$Return_Outward_Item['Store_Balance']}'/>  </td>";

                                                echo "<td> <input type='text'
                                                            id='Quantity_Returned_{$Return_Outward_Item['Outward_Item_ID']}'
                                                            value='{$Return_Outward_Item['Quantity_Returned']}'
                                                            onclick=\"removeZero(this)\"
                                                            onkeypress=\"numberOnly(this); Update_Quantity_Returned(this.value,{$Return_Outward_Item['Outward_Item_ID']},{$Return_Outward_Item['Item_ID']})\"
                                                            onkeyup=\"numberOnly(this); Update_Quantity_Returned(this.value,{$Return_Outward_Item['Outward_Item_ID']},{$Return_Outward_Item['Item_ID']})\" />
                                                            <input type='hidden' id='Previous_Quantity_Returned_{$Return_Outward_Item['Outward_Item_ID']}'
                                                            value='{$Return_Outward_Item['Quantity_Returned']}' />
                                                    </td>";

                                                echo "<td> <input type='text'
                                                            id='Item_Remark_{$Return_Outward_Item['Outward_Item_ID']}'
                                                            value='{$Return_Outward_Item['Item_Remark']}'
                                                            onkeypress=\"Update_Item_Remark(this.value,{$Return_Outward_Item['Outward_Item_ID']},{$Return_Outward_Item['Item_ID']})\"
                                                            onkeyup=\"Update_Item_Remark(this.value,{$Return_Outward_Item['Outward_Item_ID']},{$Return_Outward_Item['Item_ID']})\" />
                                                            </td>";

                                                echo "<td> <input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'
                                                            onclick='Confirm_Remove_Item(\"{$Return_Outward_Item['Product_Name']}\", {$Return_Outward_Item['Item_ID']})' />  </td>";

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
                                    <?php if (isset($Outward_ID) && $Outward_ID > 0) { ?>
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
                                                           onclick='Confirm_Submit_Document()'>
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

        addDatePicker($('#Transaction_Date'));

        Get_Item_Balance_By_Classification("all");
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
        Current_Store_ID = document.getElementById("Sub_Department_ID").value;

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
        myObject.open('GET', 'returnoutward_get_item_balance_by_classification.php?Classification=' + Classification +
            "&Current_Store_ID=" + Current_Store_ID, true);
        myObject.send();
    }
</script>

<script type='text/javascript'>
    function Get_Item_Balance_By_Classification_Filtered(Item_Name) {
        Current_Store_ID = document.getElementById("Sub_Department_ID").value;
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
        myObject.open('GET', 'returnoutward_get_item_balance_by_classification.php?Classification=' + Classification +
            "&Current_Store_ID=" + Current_Store_ID + '&Item_Name=' + Item_Name, true);
        myObject.send();
    }
</script>

<script>
    function Add_To_Document(Item_ID){
        Outward_ID = document.getElementById("Outward_ID").value;

        Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
        Supplier_ID = document.getElementById("Supplier_ID").value;

        Employee_ID = <?php echo $Employee_ID; ?>;
        Branch_ID = <?php echo $Branch_ID; ?>;
        Transaction_Date = document.getElementById("Transaction_Date").value;

        if ((Sub_Department_ID > 0 || Sub_Department_ID != "") &&
            (Supplier_ID > 0 || Supplier_ID != "") &&
            (Transaction_Date != "")) {

            if (Outward_ID == "new") {
            } else {
                if (window.XMLHttpRequest) {
                    myReturn_OutwardObject = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myReturn_OutwardObject = new ActiveXObject('Micrsoft.XMLHTTP');
                    myReturn_OutwardObject.overrideMimeType('text/xml');
                }

                myReturn_OutwardObject.onreadystatechange = function () {
                    data = myReturn_OutwardObject.responseText;
                    if (myReturn_OutwardObject.readyState == 4) {
                        if (data.trim() == "yes"){
                            Error_Message("Item Already Exists");
                        } else {
                            Add_Document_Item(Item_ID);
                        }
                    }
                };
                myReturn_OutwardObject.open('GET', 'returnoutward_edit_check_item_exists.php?Outward_ID=' + Outward_ID
                    + '&Item_ID=' + Item_ID, true);
                myReturn_OutwardObject.send();
            }
        } else {
            Error_Message("PLEASE SELECT ISSUE DATE, ISSUING STORE, COST CENTER AND EMPLOYEE REQUESTED");
        }
    }
</script>

<script>
    function Add_Document_Item(Item_ID){
        Outward_ID = document.getElementById("Outward_ID").value;

        if (Outward_ID > 0) {
            if (window.XMLHttpRequest) {
                myReturn_OutwardItemObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myReturn_OutwardItemObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myReturn_OutwardItemObject.overrideMimeType('text/xml');
            }

            myReturn_OutwardItemObject.onreadystatechange = function () {
                data = myReturn_OutwardItemObject.responseText;
                if (myReturn_OutwardItemObject.readyState == 4) {
                    document.getElementById('Items_Fieldset_List').innerHTML = data;
                    showSaveButtons();
                }
            };
            myReturn_OutwardItemObject.open('GET', 'returnoutward_edit_document_item.php?Outward_ID=' + Outward_ID
                + '&Item_ID=' + Item_ID, true);
            myReturn_OutwardItemObject.send();
        }
    }
</script>

<script type='text/javascript'>
    function Confirm_Remove_Item (Item_Name, Outward_Item_ID) {
        Outward_ID = document.getElementById("Outward_ID").value;

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
            myObject.open('GET', 'returnoutward_edit_remove_item.php?Item_ID=' + Outward_Item_ID
                + "&Outward_ID=" + Outward_ID, true);
            myObject.send();
        }
    }
</script>

<script type='text/javascript'>
    function Update_Quantity_Returned (Quantity_Returned, Outward_Item_ID, Item_ID) {
        Store_Balance = parseInt(document.getElementById("Store_Balance_"+Outward_Item_ID).value);
        Previous_Quantity_Returned = parseInt(document.getElementById("Previous_Quantity_Returned_"+Outward_Item_ID).value);
        if(Quantity_Returned == "") { Quantity_Returned = 0; }
        Quantity_Returned = parseInt(Quantity_Returned);

        /*if (Quantity_Returned > (Store_Balance + Previous_Quantity_Returned)) {
            Error_Message("YOU DO NOT HAVE SUFFIECIENT BALANCE");
            Quantity_Returned = Store_Balance + Previous_Quantity_Returned;
            document.getElementById("Quantity_Returned_"+Outward_Item_ID).value = Store_Balance + Previous_Quantity_Returned;
        }*/

        if (Quantity_Returned > 0) {
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
            myObject.open('GET', 'returnoutward_edit_update_quantity_returned.php?Item_ID=' + Item_ID
                + "&Quantity_Returned=" + Quantity_Returned, true);
            myObject.send();
        }

        document.getElementById("Quantity_Returned_"+Outward_Item_ID).value = Quantity_Returned;
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
    function Update_Item_Remark(Item_Remark, Outward_Item_ID, Item_ID) {
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
        myObject.open('GET', 'returnoutward_edit_update_item_remark.php?Item_ID=' + Item_ID
            + "&Item_Remark=" + Item_Remark, true);
        myObject.send();
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
        thml += "<input type='button' class='art-button-green' value='SUBMIT DISPOSAL' onclick='Confirm_Submit_Document()'>";
        thml += "</td>";
        thml += "</tr>";
        thml += " </table>";

        document.getElementById("Submit_Button_Area").innerHTML = thml;
    }
</script>

<script type='text/javascript'>
    function Confirm_Submit_Document() {
        var Outward_ID = document.getElementById("Outward_ID").value;
        var Supervisor_Username = document.getElementById("Supervisor_Username").value;
        var Supervisor_Password = document.getElementById("Supervisor_Password").value;

        if (Outward_ID == null || Outward_ID == '') {
            alert('An error has occured. Click ok to reload page.');
            window.location = window.location.href;
        }

        if (Supervisor_Username != null && Supervisor_Username != '' && Supervisor_Password != null && Supervisor_Password != '') {
            var r = confirm("Are you sure you want to process this issue note?\n\nClick OK to proceed");
            if (r == true) {
                $.ajax({
                    type: 'GET',
                    url: 'returnoutward_edit_process.php',
                    data: 'Supervisor_Username=' + Supervisor_Username + '&Supervisor_Password=' + Supervisor_Password + '&Outward_ID=' + Outward_ID,
                    cache: false,
                    success: function (feedback) {
                        if (feedback == '1') {
                            alert('Issue Note (Manual) Edited successifully');
                            window.location = 'returnoutward.php';
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