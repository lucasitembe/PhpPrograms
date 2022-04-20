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

//get requisition id
if (isset($_SESSION['Disposal_ID'])) {
    $Disposal_ID = $_SESSION['Disposal_ID'];
} else {
    $Disposal_ID = 0;
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
if (isset($_SESSION['IssueManual_ID'])) {
    $IssueManual_ID = $_SESSION['IssueManual_ID'];
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

<form action='#' method='post' name='myForm' id='issmanualform' >
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
                        echo "<input type='text' readonly='readonly' name='Issue_Date' id='Issue_Date' ";
                        echo "value='{$IssueManual['Issue_Date_And_Time']}'/>";
                    } else {
                        echo "<input type='text' readonly='readonly' name='Issue_Date' id='Issue_Date'/>";
                    }
                    ?>
                </td> 
                <td style='text-align: right;'>Issue By</td> 
                <td>
                    <input type='text' readonly='readonly'  value='<?php echo $Employee_Name; ?>'>
                </td>

            </tr>
            <tr>
                <td width='10%' style='text-align: right;'>Store Issuing</td>
                <td width='16%'>
                    <select name='storeissuign'  class="Issuevalue" id='storeissuign' style="width:100%" onchange="Get_Balance_2()">
                        <?php
                        if (!empty($Inward_ID)) {
                            $Sub_Department = Get_Sub_Department($IssueManual['Store_Issue']);
                            echo "<option value='{$Sub_Department['Sub_Department_ID']}'>";
                            echo "{$Sub_Department['Sub_Department_Name']}";
                            echo "</option>";
                        } else {
                            $Sub_Department_List = Get_Stock_Balance_Sub_Departments();
                            echo "<option value='" . $Current_Store_ID . "'>" . $Current_Store_Name . "</option>";
                            /* foreach($Sub_Department_List as $Sub_Department) {
                              if ($Sub_Department['Sub_Department_ID'] == $Current_Store_ID) {
                              echo "<option value='{$Sub_Department['Sub_Department_ID']}' selected >";
                              echo " {$Sub_Department['Sub_Department_Name']} ";
                              echo "</option>";
                              }
                              } */
                        }
                        ?>
                    </select>
                </td>
                <td width='13%' style='text-align: right;'> Cost Center </td>
                <td >
                    <select name='constcenter' class="Issuevalue" id='constcenter' style="width:100%">
                        <?php
                        if (!empty($IssueManual_ID)) {
                            $Sub_Department = Get_Sub_Department($IssueManual['Store_Need']);
                            echo "<option value='{$Sub_Department['Sub_Department_ID']}'>";
                            echo "{$Sub_Department['Sub_Department_Name']}";
                            echo "</option>";
                        } else {
                            echo "<option></option>";
                            $Sub_Department_List = Get_Storage_And_Pharmacy_Sub_Departments();
                            foreach ($Sub_Department_List as $Sub_Department) {
                                echo "<option value='{$Sub_Department['Sub_Department_ID']}'>";
                                echo "{$Sub_Department['Sub_Department_Name']}";
                                echo "</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
                <td style='text-align: right;'>Employee Requested</td> 
                <td>
                    <select name='employee_requested' class="Issuevalue" id='employee_requested' style="width:100%">
                        <?php
                        if (!empty($IssueManual_ID)) {
                            $Employee = Get_Employee($IssueManual['Employee_Receiving']);
                            echo "<option value='{$Employee['Employee_ID']}'>";
                            echo "{$Employee['Employee_Name']}";
                            echo "</option>";
                        } else {
                            echo "<option></option>";
                            $Employee_List = Get_Employee_All();
                            foreach ($Employee_List as $Employee) {
                                echo "<option value='{$Employee['Employee_ID']}'>";
                                echo "{$Employee['Employee_Name']}";
                                echo "</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width='12%' style='text-align: right;'>IV Number</td>
                <td width='5%'>
                    <?php if (!empty($IssueManual_ID)) { ?>
                        <input type='text' name='IV_Number' size='6' id='IV_Number'
                                value='<?php echo $IV_Number; ?>'>
                           <?php } else { ?>
                        <input type='text' name='IV_Number' size='6'  id='IV_Number' value=''>
                    <?php } ?>
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
                                        foreach ($Classification_List as $Classification) {
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
                                            foreach ($Item_Balance_List as $Item_Balance) {
                                                $Item_ID = $Item_Balance['Item_ID'];
                                                $Last_Buying_Price = Get_Last_Buy_Price($Item_ID);
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
                                                echo "<td><label class='labefor' for='{$Item_Balance['Item_ID']}'>{$Last_Buying_Price}</label></td>";
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
                                        echo "<td style='text-align: center; width: 8%;'>Store Balance</td>";
                                        echo "<td style='text-align: center; width: 8%;'>Quantity Required</td>";
                                        echo "<td style='text-align: center; width: 8%;'>Quantity Issued</td>";
                                        echo "<td style='text-align: center; width: 8%;'>Buying Price</td>";
                                        echo "<td style='text-align: center; width: 8%;'>Selling Price</td>";
                                        echo "<td style='text-align: center; width: 10%;'>Total</td>";
                                        echo "<td style='text-align: center; width: 5%;'>Remove</td>";
                                        echo "</tr>";

                                        $Issue_Manual_Item_List = Get_Issue_Note_Manual_Items($IssueManual_ID);
                                        $i = 1;
                                        $Grand_Total = 0;
                                        foreach ($Issue_Manual_Item_List as $Issue_Manual_Item) {
                                            echo "<tr>";

                                            echo "<td> <input type='text' value='{$i}'/>  </td>";

                                            echo "<td> <input type='text' readonly='readonly'
                                                            id='Product_Name_{$Issue_Manual_Item['Requisition_Item_ID']}'
                                                            value='{$Issue_Manual_Item['Product_Name']}'/>  </td>";

                                            echo "<td> <input type='text' readonly='readonly'
                                                            id='Store_Balance_{$Issue_Manual_Item['Requisition_Item_ID']}'
                                                            class='validate_issueing Store_Balance_$i' trg='$i'
                                                            value='{$Issue_Manual_Item['Store_Balance']}'/>  </td>";

                                            echo "<td> <input type='text'
                                                            id='Quantity_Required_{$Issue_Manual_Item['Requisition_Item_ID']}'
                                                            class='validate_issueing Quantity_Required_$i' trg='$i'    
                                                            value='{$Issue_Manual_Item['Quantity_Required']}'
                                                            onclick=\"removeZero(this)\"
                                                            onkeypress=\"numberOnly(this); Update_Quantity_Required(this.value,{$Issue_Manual_Item['Requisition_Item_ID']})\"
                                                            onkeyup=\"numberOnly(this); Update_Quantity_Required(this.value,{$Issue_Manual_Item['Requisition_Item_ID']})\"/>
                                                            </td>";

                                            echo "<td> <input type='text'
                                                            id='Quantity_Issued_{$Issue_Manual_Item['Requisition_Item_ID']}'
                                                            class='validate_issueing Quantity_Issued_$i' trg='$i' 
                                                            value='{$Issue_Manual_Item['Quantity_Issued']}'
                                                            onclick=\"removeZero(this); Update_Total(" . $Issue_Manual_Item['Requisition_Item_ID'] . "," . $Issue_Manual_Item['Item_ID'] . ")\"
                                                            onkeypress=\"numberOnly(this); Update_Quantity_Issued(this.value,{$Issue_Manual_Item['Requisition_Item_ID']}); Update_Total(" . $Issue_Manual_Item['Requisition_Item_ID'] . "," . $Issue_Manual_Item['Item_ID'] . ")\"
                                                            onkeyup=\"numberOnly(this); Update_Quantity_Issued(this.value,{$Issue_Manual_Item['Requisition_Item_ID']}); Update_Total(" . $Issue_Manual_Item['Requisition_Item_ID'] . "," . $Issue_Manual_Item['Item_ID'] . ")\"
                                                            oninput=\"Update_Total(" . $Issue_Manual_Item['Requisition_Item_ID'] . "," . $Issue_Manual_Item['Item_ID'] . ")\"/>
                                                            </td>";

                                            echo "<td> <input type='text' id='Buying_Price_{$Issue_Manual_Item['Requisition_Item_ID']}' style='text-align: right;' value='" . number_format($Issue_Manual_Item['Buying_Price']) . "' readonly='readonly'/></td>";
                                            echo "<td> <input type='text' id='Buying_Price_{$Issue_Manual_Item['Requisition_Item_ID']}' style='text-align: right;' value='" . number_format($Issue_Manual_Item['Selling_Price']) . "' readonly='readonly'/></td>";

                                            echo "<td> <input type='text' id='Total_{$Issue_Manual_Item['Requisition_Item_ID']}' style='text-align: right;' value='" . number_format($Issue_Manual_Item['Quantity_Issued'] * $Issue_Manual_Item['Buying_Price']) . "'  readonly='readonly'></td>";

                                            echo "<td> <input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'
                                                            onclick='Confirm_Remove_Item(\"{$Issue_Manual_Item['Product_Name']}\", {$Issue_Manual_Item['Requisition_Item_ID']})' />  </td>";

                                            echo "</tr>";

                                            $Grand_Total += ($Issue_Manual_Item['Quantity_Issued'] * $Issue_Manual_Item['Buying_Price']);
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
                                                <td width="25%" id="Grand_Total_Area" style='text-align: center;'>
                                                    <b>Grand Total : <?php echo number_format($Grand_Total); ?>
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

    <div id="Same_Location_Error">
        Cost Center should be different from Store Issuing. Please change Cost Center
    </div>

    <link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
    <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <link rel="stylesheet" href="css/select2.min.css" media="screen">

    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="script.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="script.responsive.js"></script>
    <script src="js/zebra_dialog.js"></script>
    <script src="js/ehms_zebra_dialog.js"></script>

    <script type='text/javascript'>
       $(document).ready(function () {
           $('select').select2();
           $("#Error_Message").dialog({autoOpen: false, width: '50%', height: 150, title: 'eHMS 2.0 ~ Error!', modal: true});
           $("#Same_Location_Error").dialog({autoOpen: false, width: '50%', height: 130, title: 'eHMS 2.0 ~ Error!', modal: true});
           addDatePicker($('#Issue_Date'));
       });

       function Error_Message(Error_Message) {
           $("#Error_Message").html(Error_Message);
           $("#Error_Message").dialog("open");
       }
    </script>

    <script>
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

        function Get_Item_Balance_By_Classification_Filtered(Item_Name) {
            Current_Store_ID = document.getElementById("storeissuign").value;
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

        function Add_To_Issue_Note(Item_ID) {
          var  IssueManual_ID = document.getElementById("IssueManual_ID").value;

            var  Store_Issuing_ID = document.getElementById("storeissuign").value;
            var  Employee_Issuing_ID = <?php echo $Employee_ID; ?>;
            var  Store_Requesting_ID = document.getElementById("constcenter").value;
            var  Employee_Requesting_ID = document.getElementById("employee_requested").value;
            var  Branch_ID = <?php echo $Branch_ID; ?>;
            var  Issue_Date = document.getElementById("Issue_Date").value;
            var  IV_Number = document.getElementById("IV_Number").value;
            

            if(Store_Issuing_ID != Store_Requesting_ID){
                if ((Store_Issuing_ID > 0 || Store_Issuing_ID != "") &&
                        (Store_Requesting_ID > 0 || Store_Requesting_ID != "") &&
                        (Employee_Requesting_ID > 0 || Employee_Requesting_ID != "") &&
                        (Issue_Date != "")) {

                    if (IssueManual_ID == "new") {
                        if (window.XMLHttpRequest) {
                            myIssueManualObject = new XMLHttpRequest();
                        } else if (window.ActiveXObject) {
                            myIssueManualObject = new ActiveXObject('Micrsoft.XMLHTTP');
                            myIssueManualObject.overrideMimeType('text/xml');
                        }

                        myIssueManualObject.onreadystatechange = function () {
                            data = myIssueManualObject.responseText;
                            if (myIssueManualObject.readyState == 4) {
                                IssueManual_ID = data;
                                if ($.isNumeric(IssueManual_ID)) {
                                    document.getElementById("IssueManual_ID").value = IssueManual_ID;
                                    Add_Issue_Note_Item(Item_ID);
                                } else {
                                    Error_Message(IssueManual_ID);
                                }
                            }
                        };
                        myIssueManualObject.open('GET', 'issuenotemanual_add_issue_note.php?IssueManual_ID=' + IssueManual_ID
                                + '&Store_Issuing_ID=' + Store_Issuing_ID + '&Employee_Issuing_ID=' + Employee_Issuing_ID
                                + '&Store_Requesting_ID=' + Store_Requesting_ID + '&Employee_Requesting_ID=' + Employee_Requesting_ID
                                + '&Branch_ID=' + Branch_ID + '&Issue_Date=' + Issue_Date
                                + '&Item_ID=' + Item_ID+'&IV_Number='+IV_Number, true);
                        myIssueManualObject.send();
                    } else {
                        if (window.XMLHttpRequest) {
                            myIssueManualObject = new XMLHttpRequest();
                        } else if (window.ActiveXObject) {
                            myIssueManualObject = new ActiveXObject('Micrsoft.XMLHTTP');
                            myIssueManualObject.overrideMimeType('text/xml');
                        }

                        myIssueManualObject.onreadystatechange = function () {
                            data = myIssueManualObject.responseText;
                            if (myIssueManualObject.readyState == 4) {
                                if (data == "yes") {
                                    Error_Message("Item Already Exists");
                                } else {
                                    Add_Issue_Note_Item(Item_ID);
                                }
                            }
                        };
                        myIssueManualObject.open('GET', 'issuenotemanual_check_item_exists.php?IssueManual_ID=' + IssueManual_ID
                                + '&Item_ID=' + Item_ID, true);
                        myIssueManualObject.send();
                    }
                } else {
                    Error_Message("PLEASE SELECT <b>ISSUE DATE</b>, <b>ISSUING STORE</b>, <b>COST CENTER</b>, <b>EMPLOYEE REQUESTED</b>  ,<b>IV Number</b> AND <b>SPONSOR</b> ABOVE TO CONTINUE");
                }
            }else{
                document.getElementById("storeissuign").style = 'border: 3px solid red';
                document.getElementById("constcenter").style = 'border: 3px solid red';
                $("#Same_Location_Error").dialog("open");
            }
        }


        function Add_Issue_Note_Item(Item_ID) {
            IssueManual_ID = document.getElementById("IssueManual_ID").value;
            constcenter = document.getElementById("constcenter").value;

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
                myIssueManualItemObject.open('GET', 'issuenotemanual_add_issue_note_item.php?IssueManual_ID=' + IssueManual_ID+'&constcenter='+constcenter
                        + '&Item_ID=' + Item_ID, true);
                myIssueManualItemObject.send();
            }
        }

        function Confirm_Remove_Item(Item_Name, Requisition_Item_ID) {
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
                        Update_Grand_Total();
                    }
                };
                myObject.open('GET', 'issuenotemanual_remove_item.php?Requisition_Item_ID=' + Requisition_Item_ID
                        + "&IssueManual_ID=" + IssueManual_ID, true);
                myObject.send();
            }
        }

        function Update_Quantity_Required(Quantity_Required, Requisition_Item_ID) {
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
                myObject.open('GET', 'issuenotemanual_update_quantity_required.php?Requisition_Item_ID=' + Requisition_Item_ID
                        + "&Quantity_Required=" + Quantity_Required, true);
                myObject.send();
            }
        }

        function Update_Quantity_Issued(Quantity_Issued, Requisition_Item_ID) {
            Store_Balance = parseInt(document.getElementById("Store_Balance_" + Requisition_Item_ID).value);
            if (Quantity_Issued == "") {
                Quantity_Issued = 0;
            }
            Quantity_Issued = parseInt(Quantity_Issued);

            /**if (Quantity_Issued > Store_Balance) { allow negative balance
             Error_Message("YOU DO NOT HAVE SUFFIECIENT BALANCE");
             Quantity_Issued = Store_Balance;
             document.getElementById("Quantity_Issued_"+Requisition_Item_ID).value = Store_Balance;
             }**/

            //if (Quantity_Issued >= 0) { allow negative balance
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
            myObject.open('GET', 'issuenotemanual_update_quantity_issued.php?Requisition_Item_ID=' + Requisition_Item_ID
                    + "&Quantity_Issued=" + Quantity_Issued, true);
            myObject.send();
            //} allow negative balance

            document.getElementById("Quantity_Issued_" + Requisition_Item_ID).value = Quantity_Issued;
        }

        function removeZero(element) {
            Element_Value = $(element).val();
            if (Element_Value == 0) {
                $(element).val("");
            }
        }

        function Update_Item_Remark(Item_Remark, Requisition_Item_ID) {
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
            myObject.open('GET', 'issuenotemanual_update_item_remark.php?Requisition_Item_ID=' + Requisition_Item_ID
                    + "&Item_Remark=" + Item_Remark, true);
            myObject.send();
        }

        function Get_Selected_Item_Warning() {
            var Item_Name = document.getElementById("Item_Name").value;
            if (Item_Name != '' && Item_Name != null) {
                alert("Process Fail!!\n" + Item_Name + " already available from the selected items list\nYou can edit quantity, edit item remark or remove selected item");
            } else {
                alert("Process Fail!!\nSelected item already available from the selected items list\nYou can edit quantity, edit item remark or remove selected item");
            }
        }

        function Get_Balance() {
            var Item_ID = document.getElementById("Item_ID").value;
            var storeissuign = document.getElementById("storeissuign").value;

            if (Item_ID == '' || Item_ID == null) {
                alert('Select Item before continuing');
                exit;
            } else if (storeissuign == '' || storeissuign == null) {
                alert('Select Issuing store');
                exit;
            }

            if (window.XMLHttpRequest) {
                myObjectGetBalance = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetBalance = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetBalance.overrideMimeType('text/xml');
            }

            myObjectGetBalance.onreadystatechange = function () {
                data80 = myObjectGetBalance.responseText;
                if (myObjectGetBalance.readyState == 4) {
                    document.getElementById('Balance').value = data80;
                }
            }; //specify name of function that will handle server response........

            myObjectGetBalance.open('GET', 'getbalanceBySubDepartment.php?Item_ID=' + Item_ID + '&storeissuign=' + storeissuign, true);
            myObjectGetBalance.send();
        }

        function Get_Balance_2() {
            var Item_ID = document.getElementById("Item_ID").value;
            var storeissuign = document.getElementById("storeissuign").value;

            if (Item_ID == '' || Item_ID == null) {
                //alert('Select Item before continuing');
                //exit;
            } else {

                if (window.XMLHttpRequest) {
                    myObjectGetBalance = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectGetBalance = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectGetBalance.overrideMimeType('text/xml');
                }

                myObjectGetBalance.onreadystatechange = function () {
                    data80 = myObjectGetBalance.responseText;
                    if (myObjectGetBalance.readyState == 4) {
                        document.getElementById('Balance').value = data80;
                    }
                }; //specify name of function that will handle server response........

                myObjectGetBalance.open('GET', 'getbalanceBySubDepartment.php?Item_ID=' + Item_ID + '&storeissuign=' + storeissuign, true);
                myObjectGetBalance.send();
            }
        }

        function updateDisposalNumber() {
            if (window.XMLHttpRequest) {
                myObjectUpdateDisposal = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectUpdateDisposal = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectUpdateDisposal.overrideMimeType('text/xml');
            }
            myObjectUpdateDisposal.onreadystatechange = function () {
                data25 = myObjectUpdateDisposal.responseText;
                if (myObjectUpdateDisposal.readyState == 4) {
                    document.getElementById('Disposal_Number').value = data25;
                }
            }; //specify name of function that will handle server response........

            myObjectUpdateDisposal.open('GET', 'Update_Disposal_Number.php', true);
            myObjectUpdateDisposal.send();
        }

        function Get_Selected_Item() {
            var Item_Name = document.getElementById("Item_Name").value;
            var Item_ID = document.getElementById("Item_ID").value;
            var Quantity_Required = parseInt(document.getElementById("Quantity_Required").value);
            var Item_Remark = document.getElementById("Item_Remark").value;
            var Quantity_Issued = document.getElementById("Quantity_Issued").value;
            var storeissuign = document.getElementById("storeissuign").value;
            var constcenter = document.getElementById("constcenter").value;
            var Balance = parseInt(document.getElementById("Balance").value);
            var employee_requested = document.getElementById("employee_requested").value;
            var IssueManual_ID = document.getElementById("IssueManual_ID").value;


            var datastring = 'Item_ID=' + Item_ID + '&Quantity_Required=' + Quantity_Required + '&Item_Remark=' + Item_Remark + '&Quantity_Issued=' + Quantity_Issued + '&storeissuign=' + storeissuign + '&constcenter=' + constcenter + '&Balance=' + Balance + '&employee_requested=' + employee_requested + '&IssueManual_ID=' + IssueManual_ID;

            var is_error = false;

            if (Item_ID != '' && Item_ID != null && Quantity_Required != '' && Quantity_Required != null && Quantity_Issued != '' && Quantity_Issued != null && storeissuign != '' && storeissuign != null && constcenter != '' && constcenter != null) {

                if (Balance < Quantity_Issued) {
                    alert("There is no enough balance for the quantity issued.");
                    $('#Quantity_Issued').css('border', '1px solid red');
                    exit;
                } else {
                    $('#Quantity_Issued').css('border', '1px solid #ccc');
                }

                if (Quantity_Required < Quantity_Issued) {
                    alert("Quantity issued is greater than the quantity required");
                    $('#Quantity_Issued').css('border', '1px solid red');
                    exit;
                } else {
                    $('#Quantity_Issued').css('border', '1px solid #ccc');
                }

                $.ajax({
                    type: 'POST',
                    url: 'add_issuemanual_note.php',
                    data: datastring,
                    cache: false,
                    success: function (result) {
                        if (result == 'exits') {
                            alert("The Item is already in a queu to process.Please process it before adding a new one (Pending Issue note)");
                        } else {
                            data = result.split('gpitgtendanisha');
                            $('#IssueManual_ID').val(data[0]);

                            if (data[1] != '') {
                                $('#Issue_Date').val(data[1]);
                            }
                            $('#Items_Fieldset_List').html(data[3]);

                            clearContent();

                            $(':radio').prop('checked', false);

                            if (data[2] == '1') {
                                showSaveButtons();
                            }
                        }
                    }
                });
            } else {

                if (storeissuign == '' || storeissuign == null) {
                    alert("Please select store issuing");
                    exit;
                }
                if (constcenter == '' || constcenter == null) {
                    alert("Please select cost center");
                    exit;
                }
                if (employee_requested == '' || employee_requested == null) {
                    alert("Please select employee requesting");
                    exit;
                }
                $(".Issuevalue").each(function () {
                    var status = $(this).val();

                    if (status == '') {
                        $(this).css('border', '1px solid red');
                        is_error = true;
                    } else {
                        $(this).css('border', '1px solid #ccc');
                    }
                });
            }

            if (is_error) {
                alert("All coloured fields are required.");
                exit;
            }
        }

        function checkItemPending(Item_ID, storeissuign) {
            var status = false;
            $.ajax({
                type: 'POST',
                url: 'checkItemIfInQue.php',
                data: 'Item_ID=' + Item_ID + '&storeissuign=' + storeissuign,
                cache: false,
                success: function (result) {

                    if (result == '1') {
                        status = true;
                    } else if (result == '0') {
                        return false;
                    } else {
                        alert('An error has occured.Please try again later!');
                        return;
                    }
                }
            });
        }

        function alertMessage() {
            alert("Please Select Item First");
            document.getElementById("Quantity_Disposed").value = '';
        }

        function showSaveButtons() {
            var Grand_Total = '<?php echo number_format($Grand_Total); ?>';
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
            thml += "<td width='25%' id='Grand_Total_Area' style='text-align: center;'><b>Grand Total : " + Grand_Total + "</td>";
            thml += "<td style='text-align: right;'>";
            thml += "<input type='button' class='art-button-green' value='SUBMIT' onclick='Confirm_Submit_Disposal()'>";
            thml += "</td>";
            thml += "</tr>";
            thml += " </table>";

            document.getElementById("Submit_Button_Area").innerHTML = thml;
            Update_Grand_Total();
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
            validate_issues();
            var IssueManual_ID = document.getElementById("IssueManual_ID").value;
            var Supervisor_Username = document.getElementById("Supervisor_Username").value;
            var Supervisor_Password = document.getElementById("Supervisor_Password").value;

            if (IssueManual_ID == null || IssueManual_ID == '') {
                alert('An error has occured. Click ok to reload page.');
                window.location = window.location.href;
            }

            if (Supervisor_Username != null && Supervisor_Username != '' && Supervisor_Password != null && Supervisor_Password != '') {

                $.ajax({
                    type: 'GET',
                    url: 'issuenotemanual_process.php',
                    data: 'Supervisor_Username=' + Supervisor_Username + '&Supervisor_Password=' + Supervisor_Password + '&IssueManual_ID=' + IssueManual_ID,
                    cache: false,
                    success: function (feedback) {
                         if (feedback == '1') {
                            alert('Issue Note (Manual) saved successifully');
                            window.open("issuenotemanualpreview.php?IssueManual_ID=" + IssueManual_ID + "&IssueNoteManualPreview=IssueNoteManualPreviewThisPage", "_parent");
                            /*var r = confirm("Do you want to preview?\n\nClick OK to preview");
                             if (r == true) {
                             preview_url = 'issuenotemanual_preview.php?IssueManual_ID='+IssueManual_ID;
                             preview_new_page = window.open(preview_url, '_blank');
                             preview_new_page.location;
                             }
                             window.location = 'issuenotemanual.php';*/
                        } else if (feedback == '2') {
                            Error_Message("Store Balance Might have changed. Please check again!!");
                            //location.reload();
                        } else if (feedback == '3') {
                            Error_Message("Some of the Quantity Issued might be more than Store Balance. Please check again!!");
                            //location.reload();
                        } else if (feedback == '4') {
                            Error_Message("Unable To Update Issue Note. Please check again!!");
                            //location.reload();
                        } else {
                            if (feedback == '0') {
                                alert("Invalid username or password");
                            } else {
                                alert("This Issue Note May Either Already Submitted or\n Contains No Items\n" + feedback);
                            }
                        }
                    }
                });
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

    <script type="text/javascript">
        function Update_Total(Requisition_Item_ID, Item_ID) {
            var Quantity_Issued = document.getElementById('Quantity_Issued_' + Requisition_Item_ID).value;

            if (window.XMLHttpRequest) {
                myObjectGetTotal = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetTotal = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetTotal.overrideMimeType('text/xml');
            }
            myObjectGetTotal.onreadystatechange = function () {
                dataTotal = myObjectGetTotal.responseText;
                if (myObjectGetTotal.readyState == 4) {
                    document.getElementById('Total_' + Requisition_Item_ID).value = dataTotal;
                    Update_Grand_Total(Requisition_Item_ID);
                }
            }; //specify name of function that will handle server response........

            myObjectGetTotal.open('GET', 'Issue_Note_Update_Total.php?Quantity_Issued=' + Quantity_Issued + '&Requisition_Item_ID=' + Requisition_Item_ID + '&Item_ID=' + Item_ID, true);
            myObjectGetTotal.send();
        }
    </script>

    <script type="text/javascript">
        function Update_Grand_Total() {
            var IssueManual_ID = document.getElementById("IssueManual_ID").value;
            if (window.XMLHttpRequest) {
                myObjectGetTotal = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetTotal = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetTotal.overrideMimeType('text/xml');
            }
            myObjectGetTotal.onreadystatechange = function () {
                dataGrandTotal = myObjectGetTotal.responseText;
                if (myObjectGetTotal.readyState == 4) {
                    document.getElementById("Grand_Total_Area").innerHTML = dataGrandTotal;
                }
            }; //specify name of function that will handle server response........

            myObjectGetTotal.open('GET', 'Issue_Note_Update_Grand_Total.php?IssueManual_ID=' + IssueManual_ID, true);
            myObjectGetTotal.send();
        }
    </script>
    <script>
        function validate_issues() {
            var has_error = false;
            var has_empty = false;
            $('.validate_issueing').each(function () {
                var trg = $(this).attr('trg');
                var Store_Balance = parseInt($('.Store_Balance_' + trg).val());
                var Quantity_Required = parseInt($('.Quantity_Required_' + trg).val());
                var Quantity_Issued = parseInt($('.Quantity_Issued_' + trg).val());

                if (isNaN(Quantity_Issued) || Quantity_Issued == 0 || isNaN(Quantity_Required) || Quantity_Required == 0) {
                    if (isNaN(Quantity_Issued) || Quantity_Issued == 0) {
                        $('.Quantity_Issued_' + trg).css('border', '2px solid red');
                    }
                    if (isNaN(Quantity_Required) || Quantity_Required == 0) {
                        $('.Quantity_Required_' + trg).css('border', '2px solid red');
                    }
                    has_empty = true;

                } else if (Quantity_Issued > Store_Balance) {
                    $('.Quantity_Issued_' + trg).css('border', '2px solid red');
                    has_error = true;
                } else {
                    $('.Quantity_Issued_' + trg).css('border', '1px solid #ccc');
                    $('.Quantity_Required_' + trg).css('border', '1px solid #ccc');
                }
            });
//        alert(has_error);
//       return false;

            if (has_empty) {
                alertMsgSimple("Empty or zero value fields not allowed. Please correct the coloured fields", "Need correction", "error", 0, false, 'Ok');
                exit;
            } else if (has_error) {
                alertMsgSimple("You can not issue what you don't have. Please correct the coloured fields", "Need correction", "error", 0, false, 'Ok');
                exit;
            } else {
                var Supervisor_Username = document.getElementById("Supervisor_Username").value;
                var Supervisor_Password = document.getElementById("Supervisor_Password").value;

                if (Supervisor_Username != null && Supervisor_Username != '' && Supervisor_Password != null && Supervisor_Password != '') {
                  var r = confirm("Are you sure you want to process this issue note?\n\nClick OK to proceed");
                    if (!r) {
                       exit; 
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
        }
    </script>
    <?php
    include("./includes/footer.php");
    ?>