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
    include_once("./functions/returninward.php");

    include_once("returninward_navigation.php");


    //get employee name
    if (isset($_SESSION['userinfo'])) {
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

    $Inward_ID = '';
    if (isset($_SESSION['Inward_ID'])) {
        $Inward_ID = $_SESSION['Inward_ID'];
    }

    if (isset($_GET['Inward_ID'])) {
        $Inward_ID = $_GET['Inward_ID'];
    }

    $Return_Inward = array();
    if (!empty($Inward_ID)) {
        $Return_Inward = Get_Return_Inward($Inward_ID);
    }

    if (isset($_SESSION['Storage_Info'])) {
        $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        $Current_Store_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
    }
?>

<form action='#' method='post' name='myForm' id='myForm' >
    <fieldset>
        <legend align='right'><b>Return Inward  ~ <?php echo $Current_Store_Name; ?></b></legend>
        <table width=100%>
            <tr>
                <td width='12%' style='text-align: right;'>Document Number</td>
                <td width='5%'>
                    <?php if (isset($Inward_ID) && !empty($Inward_ID)) { ?>
                        <input type='text' name='Inward_ID' size='6' id='Inward_ID'
                               readonly='readonly' value='<?php echo $Inward_ID; ?>'>
                    <?php } else { ?>
                        <input type='text' name='Inward_ID' size='6'  id='Inward_ID' value='new'>
                    <?php } ?>
                </td>

                <td width='12%' style='text-align: right;'>Transaction Date</td>
                <td width='16%'>
                    <?php
                        if (!empty($Inward_ID)) {
                            echo "<input type='text' readonly='readonly' name='Transaction_Date' id='Transaction_Date' ";
                            echo "value='{$Return_Inward['Sent_Date_Time']}'/>";
                        } else {
                            echo "<input type='text' readonly='readonly' name='Transaction_Date' id='Transaction_Date'/>";
                        }
                    ?>
                </td> 
                <td style='text-align: right;'>Posted By</td>
                <td>
                    <?php
                        echo "<input type='text' readonly='readonly'  value='{$Employee_Name}'>";
                    ?>
                </td>

            </tr>
            <tr>
                <td width='10%' style='text-align: right;'>Store Receiving</td>
                <td width='16%'>
                    <select name='storeissuign'  class="Issuevalue" id='Store_Sub_Department_ID' style="width:100%" >
                        <?php
                            if (!empty($Inward_ID)) {
                                $Sub_Department = Get_Sub_Department($Return_Inward['Store_Sub_Department_ID']);
                                echo "<option value='{$Sub_Department['Sub_Department_ID']}'>";
                                echo "{$Sub_Department['Sub_Department_Name']}";
                                echo "</option>";
                            } else {
                                $Sub_Department_List = Get_Storage_And_Pharmacy_Sub_Departments();
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
                <td width='13%' style='text-align: right;'> Returning From </td>
                <td >
                    <select name='constcenter' class="Issuevalue" id='Return_Sub_Department_ID' style="width:100%">
                        <?php
                            if (!empty($Inward_ID)) {
                                $Sub_Department = Get_Sub_Department($Return_Inward['Return_Sub_Department_ID']);
                                echo "<option value='{$Sub_Department['Sub_Department_ID']}'>";
                                echo "{$Sub_Department['Sub_Department_Name']}";
                                echo "</option>";
                            } else {
                                echo "<option></option>";
                                $Sub_Department_List = Get_Storage_And_Pharmacy_Sub_Departments();
                                foreach($Sub_Department_List as $Sub_Department) {
                                    echo "<option value='{$Sub_Department['Sub_Department_ID']}'>";
                                    echo "{$Sub_Department['Sub_Department_Name']}";
                                    echo "</option>";
                                }
                            }
                        ?>
                    </select>
                </td>
                <td style='text-align: right;'></td>
                <td></td>
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
                                                echo " onclick='Add_To_Document(this.value)'";
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
                                            include_once("./returninward_show_items.php");
                                        ?>
                                    </fieldset>
                                </td>
                            </tr>

                            <tr>
                                <td id='Submit_Button_Area' style='text-align: right;'>
                                    <?php if (isset($Inward_ID) && $Inward_ID > 0) { ?>
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
    <div id="Selection_Error">
        You can not create <b>Return Inward</b> from the same location.<br/> Please change <b>Store Receiving</b> or <b>Returning From</b> Location
    </div>
    <link rel="stylesheet" href="css/select2.min.css" media="screen">
    <script src="media/js/jquery.js" type="text/javascript"></script>
    <script src="css/jquery-ui.js"></script>
    <script src="js/select2.min.js"></script>

    <script type='text/javascript'>
        $(document).ready(function () {
            $('select').select2();
            $("#Error_Message").dialog({ autoOpen: false, width:'50%',height:150, title:'eHMS 2.0 ~ Error!',modal: true});
            $("#Selection_Error").dialog({ autoOpen: false, width:'50%',height:150, title:'eHMS 2.0 ~ Error!',modal: true});
            addDatePicker($('#Transaction_Date'));
        });

        function Error_Message(Error_Message) {
            $("#Error_Message").html(Error_Message);
            $("#Error_Message").dialog("open");
        }
    </script>


    <script>
        function Get_Item_Balance_By_Classification(Classification) {
            document.getElementById("Search_Value").value = '';
            Current_Store_ID = document.getElementById("Store_Sub_Department_ID").value;

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
            myObject.open('GET', 'returninward_get_item_balance_by_classification.php?Classification=' + Classification +
                "&Current_Store_ID=" + Current_Store_ID, true);
            myObject.send();
        }
    </script>

    <script type='text/javascript'>
        function Get_Item_Balance_By_Classification_Filtered(Item_Name) {
            Current_Store_ID = document.getElementById("Store_Sub_Department_ID").value;
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
            myObject.open('GET', 'returninward_get_item_balance_by_classification.php?Classification=' + Classification +
                "&Current_Store_ID=" + Current_Store_ID + '&Item_Name=' + Item_Name, true);
            myObject.send();
        }
    </script>

    <script type='text/javascript'>
        function Add_To_Document(Item_ID){
            Inward_ID = document.getElementById("Inward_ID").value;

            Store_Sub_Department_ID = document.getElementById("Store_Sub_Department_ID").value;
            Return_Sub_Department_ID = document.getElementById("Return_Sub_Department_ID").value;
            Transaction_Date = document.getElementById("Transaction_Date").value;

            Employee_ID = <?php echo $Employee_ID; ?>;
            Branch_ID = <?php echo $Branch_ID; ?>;
            if(Store_Sub_Department_ID == Return_Sub_Department_ID){
                $("#Selection_Error").dialog("open");
            } else if ((Store_Sub_Department_ID > 0 || Store_Sub_Department_ID != "") &&
                (Return_Sub_Department_ID > 0 || Return_Sub_Department_ID != "") &&
                (Employee_ID > 0 || Employee_ID != "") &&
                (Transaction_Date != "")) {

                if (Inward_ID == "new") {
                    if (window.XMLHttpRequest) {
                        myReturn_InwardObject = new XMLHttpRequest();
                    } else if (window.ActiveXObject) {
                        myReturn_InwardObject = new ActiveXObject('Micrsoft.XMLHTTP');
                        myReturn_InwardObject.overrideMimeType('text/xml');
                    }

                    myReturn_InwardObject.onreadystatechange = function () {
                        data = myReturn_InwardObject.responseText;
                        if (myReturn_InwardObject.readyState == 4) {
                            Inward_ID = data;
                            if ($.isNumeric(Inward_ID)) {
                                document.getElementById("Inward_ID").value = Inward_ID;
                                Add_Document_Item(Item_ID);
                            } else {
                                Error_Message(Inward_ID);
                            }
                        }
                    };
                    myReturn_InwardObject.open('GET', 'returninward_add_document.php?Inward_ID=' + Inward_ID
                        + '&Store_Sub_Department_ID=' + Store_Sub_Department_ID + '&Employee_ID=' + Employee_ID
                        + '&Return_Sub_Department_ID=' + Return_Sub_Department_ID
                        + '&Branch_ID=' + Branch_ID + '&Transaction_Date=' + Transaction_Date
                        + '&Item_ID=' + Item_ID, true);
                    myReturn_InwardObject.send();
                } else {
                    if (window.XMLHttpRequest) {
                        myReturn_InwardObject = new XMLHttpRequest();
                    } else if (window.ActiveXObject) {
                        myReturn_InwardObject = new ActiveXObject('Micrsoft.XMLHTTP');
                        myReturn_InwardObject.overrideMimeType('text/xml');
                    }

                    myReturn_InwardObject.onreadystatechange = function () {
                        data = myReturn_InwardObject.responseText;
                        if (myReturn_InwardObject.readyState == 4) {
                            if (data == "yes"){
                                Error_Message("Item Already Exists");
                            } else {
                                Add_Document_Item(Item_ID);
                            }
                        }
                    };
                    myReturn_InwardObject.open('GET', 'returninward_check_item_exists.php?Inward_ID=' + Inward_ID
                        + '&Item_ID=' + Item_ID, true);
                    myReturn_InwardObject.send();
                }
            } else {
                Error_Message("PLEASE SELECT <b>TRANSACTION DATE</b>, <b>STORE RECEIVING</b> AND <b>STORE RETURNING</b> ABOVE TO CONTINUE");
            }
        }
    </script>

    <script type='text/javascript'>
        function Add_Document_Item(Item_ID){
            Inward_ID = document.getElementById("Inward_ID").value;

            if (Inward_ID > 0) {
                if (window.XMLHttpRequest) {
                    myReturn_InwardItemObject = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myReturn_InwardItemObject = new ActiveXObject('Micrsoft.XMLHTTP');
                    myReturn_InwardItemObject.overrideMimeType('text/xml');
                }

                myReturn_InwardItemObject.onreadystatechange = function () {
                    data = myReturn_InwardItemObject.responseText;
                    if (myReturn_InwardItemObject.readyState == 4) {
                        document.getElementById('Items_Fieldset_List').innerHTML = data;
                        showSaveButtons();
                    }
                };
                myReturn_InwardItemObject.open('GET', 'returninward_add_document_item.php?Inward_ID=' + Inward_ID
                    + '&Item_ID=' + Item_ID, true);
                myReturn_InwardItemObject.send();
            }
        }
    </script>

    <script type='text/javascript'>
        function Confirm_Remove_Item (Item_Name, Inward_Item_ID) {
            Inward_ID = document.getElementById("Inward_ID").value;

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
                myObject.open('GET', 'returninward_remove_item.php?Inward_Item_ID=' + Inward_Item_ID
                    + "&Inward_ID=" + Inward_ID, true);
                myObject.send();
            }
        }
    </script>

    <script type='text/javascript'>
        function Update_Quantity_Returned (Quantity_Returned, Inward_Item_ID) {
            Store_Balance = parseInt(document.getElementById("Store_Balance_"+Inward_Item_ID).value);
            if(Quantity_Returned == "") { Quantity_Returned = 0; }
            Quantity_Returned = parseInt(Quantity_Returned);

            /**if (Quantity_Returned > Store_Balance) { allow negative balance
                Error_Message("YOU DO NOT HAVE SUFFIECIENT BALANCE");
                Quantity_Returned = Store_Balance;
                document.getElementById("Quantity_Returned_"+Inward_Item_ID).value = Store_Balance;
            }**/

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
                myObject.open('GET', 'returninward_update_quantity_returned.php?Inward_Item_ID=' + Inward_Item_ID
                    + "&Quantity_Returned=" + Quantity_Returned, true);
                myObject.send();
            }

            document.getElementById("Quantity_Returned_"+Inward_Item_ID).value = Quantity_Returned;
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
        function Update_Item_Remark(Item_Remark, Inward_Item_ID) {
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
            myObject.open('GET', 'returninward_update_item_remark.php?Inward_Item_ID=' + Inward_Item_ID
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
            thml += "<input type='button' class='art-button-green' value='SUBMIT' onclick='Confirm_Submit_Document()'>";
            thml += "</td>";
            thml += "</tr>";
            thml += " </table>";

            document.getElementById("Submit_Button_Area").innerHTML = thml;
        }
    </script>

    <script type='text/javascript'>
        function Confirm_Submit_Document() {
            var Inward_ID = document.getElementById("Inward_ID").value;
            var Supervisor_Username = document.getElementById("Supervisor_Username").value;
            var Supervisor_Password = document.getElementById("Supervisor_Password").value;

            if (Inward_ID == null || Inward_ID == '') {
                alert('An error has occured. Click ok to reload page.');
                window.location = window.location.href;
            }

            if (Supervisor_Username != null && Supervisor_Username != '' && Supervisor_Password != null && Supervisor_Password != '') {
                var r = confirm("Are you sure you want to process this document?\n\nClick OK to proceed");
                if (r == true) {
                    $.ajax({
                        type: 'GET',
                        url: 'returninward_process.php',
                        data: 'Supervisor_Username=' + Supervisor_Username + '&Supervisor_Password=' + Supervisor_Password + '&Inward_ID=' + Inward_ID,
                        cache: false,
                        success: function (feedback) {
                            if (feedback == '1') {
                                alert('Return Inward saved successifully');
                                var r = confirm("Do you want to preview?\n\nClick OK to preview");
                                if (r == true) {
                                    preview_url = 'returninward_preview.php?Inward_ID='+Inward_ID;
                                    preview_new_page = window.open(preview_url, '_blank');
                                    preview_new_page.location;
                                }
                                window.location = 'returninward.php';
                            } else if (feedback == '2') {
                                //Error_Message("Store Balance Might have changed. Please check again!!");
                                Error_Message("Process fail!! Returning store contains less balance compare to some of selected items");
                                //location.reload();
                            }  else if (feedback == '3') {
                                Error_Message("Some of the Quantity might be more than Store Balance. Please check again!!");
                                //location.reload();
                            } else if (feedback == '4') {
                                Error_Message("Unable To Update Docment. Please check again!!");
                                //location.reload();
                            } else {
                                if (feedback == '0') {
                                    alert("Invalid username or password");
                                } else {
                                    alert("This Document May Either Already Submitted or\n Contains No Items\n"+feedback);
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

<?php include("./includes/footer.php"); ?>