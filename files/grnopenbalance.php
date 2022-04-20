<script src='js/functions.js'></script>
<?php
include("./includes/header.php");
include("./includes/connection.php");
include_once("./functions/department.php");
$requisit_officer = $_SESSION['userinfo']['Employee_Name'];

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
        if ($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['Storage_Supervisor'])) {
                header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

$canPakage = false;
$display = "style='display:none'";

if (isset($_SESSION['systeminfo']['enable_receive_by_package']) && $_SESSION['systeminfo']['enable_receive_by_package'] == 'yes') {
    $canPakage = true;
    $display = "";
}

if (!isset($_SESSION['Storage_Info'])) {
    header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
}


echo "<a href='Control_Grn_Open_Balance_Sessions.php?New_Grn_Open_Balance=True&Status=new' class='art-button-green'>NEW OPEN BALANCE</a>";
echo "<a href='penginggpnopenbalance.php?PendingGrnOpenBalance=PendingGrnOpenBalanceThisPage' class='art-button-green'>PENDING OPEN BALANCES</a>";
echo "<a href='Control_Grn_Open_Balance_Sessions.php?Previous_Grn_Open_Balance=True&Status=Previous' class='art-button-green'>PREVIOUS OPEN BALANCES</a>";
echo "<a href='grn_multiple_approval.php' class='art-button-green'>APPROVE GRN</a>";
echo "<a href='grn_reason_change_balance.php' class='art-button-green'>RESEASONS</a>";
echo "<a href='goodreceivednote.php?GoodReceivedNote=GoodReceivedNoteThisPage' class='art-button-green'>BACK</a>";
?>


<?php
if (isset($_SESSION['Storage_Info'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];

    //get Sub Department Name
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if ($num > 0) {
        while ($row = mysqli_fetch_array($select)) {
            $Sub_Department_Name = $row['Sub_Department_Name'];
        }
    } else {
        $Sub_Department_Name = '';
    }
}
?>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }

</style> 
   <div id="select_clinic" style="display:none;">
    <style type="text/css">
                #spu_lgn_tbl{
                    width:100%;
                    border:none!important;
                }
                #spu_lgn_tbl tr, #spu_lgn_tbl tr td{
                    border:none!important;
                    padding: 15px;
                    font-size: 14PX;
                }
    </style>
    <table  id="spu_lgn_tbl" style="width:100%">
                <tr id="select_clinic">
                    <td style="text-align:right">
                        Select Reasons
                    </td>
                    <td style="width:60%">
                        <select  style='width: 100%;height:30%'  name='reasons_ID' id='reasons_ID' value='<?php echo $Guarantor_Name; ?>'  required='required'>
                            <option selected='selected'></option>
                            <?php
                             $Select_Consultant = "select * from tbl_reasons_adjustment where Status = 'enabled'";
                            $result = mysqli_query($conn,$Select_Consultant);
                            ?>
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <option value="<?php echo $row['reason_id']; ?>"><?php echo $row['reasons']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                </tr>
           
             
        </tr>
    </table>
    </div>
<br><br>
<fieldset>
    <legend align="right"><b>GRN ~ OPEN BALANCE</b></legend>     
    <form action='#' method='post' name='myForm' id='myForm'>
        <fieldset>   
            <center> 
                <table width=100%>
                    <tr>
                        <td style='text-align: right; width: 10%;'>GRN Number</td>
                        <td width=15%>
                            <?php if (isset($_SESSION['Grn_Open_Balance_ID'])) { ?>
                                <input type='text' name='Grn_Number'  id='Grn_Number' value='<?php echo $_SESSION['Grn_Open_Balance_ID']; ?> ' readonly='readonly'>
                            <?php } else { ?>
                                <input type='text' name='Grn_Number'  id='Grn_Number' value='New' readonly='readonly'>
                        <?php } ?>
                        </td>
                        <td style='text-align: right; width: 15%;'>Created Date</td>
                        <?php
                        if (isset($_SESSION['Grn_Open_Balance_ID'])) {
                            $Grn_Open_Balance_ID = $_SESSION['Grn_Open_Balance_ID'];
                            //get employee id
                            if (isset($_SESSION['userinfo']['Employee_ID'])) {
                                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                            } else {
                                $Employee_ID = 0;
                            }

                            //get Created_Date_Time
                            $select_Created_Date_Time = mysqli_query($conn,"select Created_Date_Time from tbl_grn_open_balance where
														Grn_Open_Balance_ID = '$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
                            $num = mysqli_num_rows($select_Created_Date_Time);
                            if ($num > 0) {
                                while ($row = mysqli_fetch_array($select_Created_Date_Time)) {
                                    $Created_Date_Time = $row['Created_Date_Time'];
                                }
                                echo "<td><input type='text' name='Grn_Date' id='Grn_Date' placeholder='Created Date' value='" . $Created_Date_Time . "' readonly='readonly'></td>";
                            } else {
                                echo "<td><input type='text' name='Grn_Date' id='Grn_Date' placeholder='Created Date' value='' readonly='readonly'></td>";
                            }
                        } else {
                            echo "<td><input type='text' name='Grn_Date' id='Grn_Date' placeholder='Created Date' value='' readonly='readonly'></td>";
                        }
                        ?>
                        <!--<td width=15%><input type='text' name='Grn_Date'  id='Grn_Date' value='' readonly='readonly'></td>-->


                        <td style='text-align: right; width: 15%;'>Store Name</td>
                        <td style='width: 15%;' id="Store_ID_Area">
                            <select name='Store_ID' id='Store_ID' required='required'>
                                <?php
                                //get store need
                                if (isset($_SESSION['Storage'])) {
                                    if (isset($_SESSION['Grn_Open_Balance_ID'])) {
                                        //get selected su department
                                        $Grn_Open_Balance_ID = $_SESSION['Grn_Open_Balance_ID'];
                                        $slck = mysqli_query($conn,"select Sub_Department_Name, gop.Sub_Department_ID from tbl_sub_department sd, tbl_grn_open_balance gop where
															sd.Sub_Department_ID = gop.Sub_Department_ID and
															gop.Grn_Open_Balance_ID = '$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
                                        $nm = mysqli_num_rows($slck);
                                        if ($nm > 0) {
                                            while ($dat = mysqli_fetch_array($slck)) {
                                                ?>
                                                <option value='<?php echo $dat['Sub_Department_ID']; ?>' ><?php echo $dat['Sub_Department_Name']; ?></option>
                                                <?php
                                            }
                                        }
                                    } else {
                                        echo "<option value=''></option>";
                                        $Sub_Department_List = Get_Stock_Ledge_Sub_Departments($Employee_ID);
                                        foreach($Sub_Department_List as $Sub_Department) {
                                            if($Sub_Department_ID=="{$Sub_Department['Sub_Department_ID']}")$selected="selected"; else $selected="";
                                            echo "<option value='{$Sub_Department['Sub_Department_ID']}' $selected> {$Sub_Department['Sub_Department_Name']} </option>";
                                        }
                                    }
                                } else {
                                    echo "<option value='' selected='selected'></option>";
                                    echo "<option value='Unknown sub department'>Unknown Sub Department</option>";
                                }
                                ?>
                            </select> 
                        </td>
                    </tr>
                    <tr>
                        <td style='text-align: right;'>Grn Description</td>
                        <?php
                        if (isset($_SESSION['Grn_Open_Balance_ID'])) {
                            $Grn_Open_Balance_ID = $_SESSION['Grn_Open_Balance_ID'];
                            //get employee id
                            if (isset($_SESSION['userinfo']['Employee_ID'])) {
                                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                            } else {
                                $Employee_ID = 0;
                            }

                            //get Grn_Open_Balance_Description
                            $select_Grn_Open_Balance_Description = mysqli_query($conn,"select Grn_Open_Balance_Description from tbl_grn_open_balance where
														Grn_Open_Balance_ID = '$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
                            $num = mysqli_num_rows($select_Grn_Open_Balance_Description);
                            if ($num > 0) {
                                while ($row = mysqli_fetch_array($select_Grn_Open_Balance_Description)) {
                                    $Grn_Open_Balance_Description = $row['Grn_Open_Balance_Description'];
                                }
                                echo "<td colspan=3><input type='text' name='Grn_Description' id='Grn_Description' placeholder='Grn Description' value='" . $Grn_Open_Balance_Description . "' onkeypress='update_Grn_Description()' onclick='update_Grn_Description()'></td>";
                            } else {
                                echo "<td colspan=3><input type='text' name='Grn_Description' id='Grn_Description' placeholder='Grn Description' value=''></td>";
                            }
                        } else {
                            echo "<td colspan=3><input type='text' name='Grn_Description' id='Grn_Description' placeholder='Grn Description' value=''></td>";
                        }
                        ?>

                        <td style='text-align: right; width: 10%'>Prepared By</td>
                        <?php
                        //get Employee_Name
                        if (isset($_SESSION['userinfo']['Employee_Name'])) {
                            $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
                        } else {
                            $Employee_Name = '';
                        }
                        ?>

                        <td>
                            <input type='text' name='Employee_Name' id='Employee_Name' value='<?php echo $Employee_Name; ?>' readonly='readonly'>
                        </td>
                    </tr> 
                </table>
            </center>
        </fieldset>




        <script>
            function getItemsList(Item_Category_ID) {
                document.getElementById("Search_Value").value = '';
                document.getElementById("Item_Name").value = '';
                document.getElementById("Item_ID").value = '';
                ////document.getElementById("Item_Remark").value = '';

                if (window.XMLHttpRequest) {
                    myObjectList = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectList = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectList.overrideMimeType('text/xml');
                }
                //alert(data);

                myObjectList.onreadystatechange = function () {
                    data4 = myObjectList.responseText;
                    if (myObjectList.readyState == 4) {
                        //document.getElementById('Approval').readonly = 'readonly';
                        document.getElementById('Items_Fieldset').innerHTML = data4;
                    }
                }; //specify name of function that will handle server response........
                myObjectList.open('GET', 'Get_List_Of_Requisition_Items_List.php?Item_Category_ID=' + Item_Category_ID, true);
                myObjectList.send();
            }

            function getItemsListFiltered(Item_Name) {
                document.getElementById("Item_Name").value = '';
                document.getElementById("Item_ID").value = '';
                ////document.getElementById("Item_Remark").value = '';
                var Item_Category_ID = document.getElementById("Item_Category_ID").value;
                if (Item_Category_ID == '' || Item_Category_ID == null) {
                    Item_Category_ID = 'All';
                }

                if (window.XMLHttpRequest) {
                    myObject = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObject.overrideMimeType('text/xml');
                }

                myObject.onreadystatechange = function () {
                    data3 = myObject.responseText;
                    if (myObject.readyState == 4) {
                        //document.getElementById('Approval').readonly = 'readonly';
                        document.getElementById('Items_Fieldset').innerHTML = data3;
                    }
                }; //specify name of function that will handle server response........
                myObject.open('GET', 'Get_List_Of_Requisition_Filtered_Items.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name, true);
                myObject.send();
            }
        </script>

        <script>
            function Get_Selected_Item_Warning() {
                var Item_Name = document.getElementById("Item_Name").value;
                if (Item_Name != '' && Item_Name != null) {
                    alert("Process Fail!!\n" + Item_Name + " already available from the selected items list");
                } else {
                    alert("Process Fail!!\nSelected item already available from the selected items list");
                }
            }

        </script>

        <script>
            function Get_Item_Name(Item_Name, Item_ID) {
                var Temp = '';
                var Store_ID = document.getElementById("Store_ID").value;

                if (Store_ID != null && Store_ID != '') {
                    if (window.XMLHttpRequest) {
                        myObjectGetItemName = new XMLHttpRequest();
                    } else if (window.ActiveXObject) {
                        myObjectGetItemName = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObjectGetItemName.overrideMimeType('text/xml');
                    }

                    myObjectGetItemName.onreadystatechange = function () {
                        data22 = myObjectGetItemName.responseText;
                        if (myObjectGetItemName.readyState == 4) {
                            Temp = data22;
                            if (Temp == 'Yes') {
                                document.getElementById("Item_Name").style.backgroundColor = '#037CB0';
                                document.getElementById("Item_Name").style.color = 'white';

                                document.getElementById("Unit_Of_Measure").style.backgroundColor = '#037CB0';
                                document.getElementById("Unit_Of_Measure").style.color = 'white';
                                document.getElementById("Unit_Of_Measure_Label").style.color = '#037CB0';
                                document.getElementById("Unit_Of_Measure_Label").innerHTML = '<b>UOM</b>';

                                document.getElementById("Quantity").style.backgroundColor = '#037CB0';
                                document.getElementById("Quantity").value = '';
                                document.getElementById("Quantity_Label").style.color = '#037CB0';
                                document.getElementById("Quantity_Label").innerHTML = '<b>Quantity</b>';
                                document.getElementById("Quantity").setAttribute("ReadOnly", "ReadOnly");

                                document.getElementById("Balance").style.backgroundColor = '#037CB0';
                                document.getElementById("Balance").style.backgroundColor = '#037CB0';
                                document.getElementById("Balance_Label").style.color = '#037CB0';
                                document.getElementById("Balance_Label").innerHTML = '<b>Balance</b>';
                                
                                document.getElementById("batch_no").style.backgroundColor = '#037CB0';
                                document.getElementById("batch_no").style.backgroundColor = '#037CB0';
                                document.getElementById("Batch_no_Label").style.color = '#037CB0';
                                document.getElementById("Batch_no_Label").innerHTML = '<b>Batch No.</b>';

                                document.getElementById("Container").style.backgroundColor = '#037CB0';
                                //document.getElementById("Container").value = '';
                                document.getElementById("Container_Label").style.color = '#037CB0';
                                document.getElementById("Container_Label").innerHTML = '<b>Units</b>';
                                document.getElementById("Container").setAttribute("ReadOnly", "ReadOnly");

                                document.getElementById("Items_Per_Container").style.backgroundColor = '#037CB0';
                                //document.getElementById("Items_Per_Container").value = '';
                                document.getElementById("Items_Per_Container_Label").style.color = '#037CB0';
                                document.getElementById("Items_Per_Container_Label").innerHTML = '<b>Items Per Unit</b>';
                                document.getElementById("Items_Per_Container").setAttribute("ReadOnly", "ReadOnly");

                                document.getElementById("Buying_Price").style.backgroundColor = '#037CB0';
                                document.getElementById("Buying_Price").value = '';
                                document.getElementById("Buying_Price_Label").style.color = '#037CB0';
                                document.getElementById("Buying_Price_Label").innerHTML = '<b>Buying Price</b>';
                                document.getElementById("Buying_Price").setAttribute("ReadOnly", "ReadOnly");

                                document.getElementById("date").style.backgroundColor = '#037CB0';
                                document.getElementById("date").value = '';
                                document.getElementById("Manufacture_Date_Label").style.color = '#037CB0';
                                document.getElementById("Manufacture_Date_Label").innerHTML = '<b>Manuf Date</b>';
                                document.getElementById("date").setAttribute("ReadOnly", "ReadOnly");

                                document.getElementById("date2").style.backgroundColor = '#037CB0';
                                document.getElementById("date2").value = '';
                                document.getElementById("Expire_Date_Label").style.color = '#037CB0';
                                document.getElementById("Expire_Date_Label").innerHTML = '<b>Expire Date</b>';
                                document.getElementById("date2").setAttribute("ReadOnly", "ReadOnly");

                                document.getElementById("Item_Name_Label").style.color = '#037CB0';
                                document.getElementById("Item_Name_Label").innerHTML = '<b>This Item Already Added.</b>';

                                //change add button to warning add button
                                document.getElementById("Add_Button_Area").innerHTML = "<input type='button' name='submit' id='submit' value='ADD' class='art-button-green' onclick='Get_Selected_Item_Warning()'>";
                            } else {
                                document.getElementById("Item_Name").style.backgroundColor = 'white';
                                document.getElementById("Item_Name_Label").style.color = 'black';
                                document.getElementById("Item_Name_Label").innerHTML = 'Item Name';

                                document.getElementById("Unit_Of_Measure").style.backgroundColor = 'white';
                                document.getElementById("Unit_Of_Measure_Label").innerHTML = '<b>UOM</b>';
                                document.getElementById("Unit_Of_Measure_Label").style.color = 'black';


                                document.getElementById("batch_no").style.backgroundColor = '#FFFFFF';
                                document.getElementById("Batch_no_Label").style.color = '#000000';
                                document.getElementById("Batch_no_Label").innerHTML = '<b>Batch No.</b>'
                                
                                
                                document.getElementById("Quantity").style.backgroundColor = 'white';
                                document.getElementById("Quantity").value = '';
                                document.getElementById("Quantity").focus();
                                document.getElementById("Quantity").removeAttribute("ReadOnly");
                                document.getElementById("Quantity_Label").innerHTML = 'Quantity';
                                document.getElementById("Quantity_Label").style.color = 'black';

                                document.getElementById("Balance").style.backgroundColor = 'white';
                                document.getElementById("Balance_Label").innerHTML = 'Balance';
                                document.getElementById("Balance_Label").style.color = 'black';

                                document.getElementById("Container").style.backgroundColor = 'white';
                                //document.getElementById("Container").value = '';
                                document.getElementById("Container").removeAttribute("ReadOnly");
                                document.getElementById("Container_Label").innerHTML = 'Container';
                                document.getElementById("Container_Label").style.color = 'black';

                                document.getElementById("Items_Per_Container").style.backgroundColor = 'white';
                                //document.getElementById("Items_Per_Container").value = '';
                                document.getElementById("Items_Per_Container").removeAttribute("ReadOnly");
                                document.getElementById("Items_Per_Container_Label").innerHTML = 'Items Per Container';
                                document.getElementById("Items_Per_Container_Label").style.color = 'black';

                                document.getElementById("date").style.backgroundColor = 'white';
                                document.getElementById("date").value = '';
                                document.getElementById("Manufacture_Date_Label").style.color = 'black';
                                document.getElementById("Manufacture_Date_Label").innerHTML = 'Manuf Date';
                                document.getElementById("date").removeAttribute("ReadOnly", "ReadOnly");

                                document.getElementById("date2").style.backgroundColor = 'white';
                                document.getElementById("date2").value = '';
                                document.getElementById("Expire_Date_Label").style.color = 'black';
                                document.getElementById("Expire_Date_Label").innerHTML = 'Expire Date';
                                //document.getElementById("date2").removeAttribute("ReadOnly", "ReadOnly");

                                document.getElementById("Buying_Price").style.backgroundColor = 'white';
                                document.getElementById("Buying_Price").value = '';
                                document.getElementById("Buying_Price_Label").style.color = 'black';
                                document.getElementById("Buying_Price_Label").innerHTML = 'Buying Price';
                                document.getElementById("Buying_Price").removeAttribute("ReadOnly", "ReadOnly");

                                //change warning add button to add button
                                document.getElementById("Add_Button_Area").innerHTML = "<input type='button' name='submit' id='submit' value='ADD' class='art-button-green' onclick='Get_Selected_Item()'>";
                            }

                            if (Item_ID != null && Item_ID != '') {
                                Get_Balance();
                                Get_Last_Buyying_Price();
                                Get_Unit_Of_Measure();
                            }
                        }
                    }; //specify name of function that will handle server response........
                    myObjectGetItemName.open('GET', 'Grn_Open_Balance_Check_Item_Selected.php?Item_ID=' + Item_ID, true);
                    myObjectGetItemName.send();

                    document.getElementById("Item_Name").value = Item_Name;
                    document.getElementById("Item_ID").value = Item_ID;
                    document.getElementById("Quantity").value = '';
                    document.getElementById("Container").value = '';
                    document.getElementById("Items_Per_Container").value = '';
                    //document.getElementById("Item_Remark").value = ''; 
                    document.getElementById("Quantity").focus();
                } else {
                    document.getElementById("Store_ID").style = 'border: 2px solid red';
                    document.getElementById("Store_ID").focus();
                }
            }
        </script>

        <script type="text/javascript">
            function Get_Unit_Of_Measure(){
                var Item_ID = document.getElementById("Item_ID").value;
                if (window.XMLHttpRequest) {
                    myObject_UOM = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObject_UOM = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObject_UOM.overrideMimeType('text/xml');
                }

                myObject_UOM.onreadystatechange = function () {
                    dataUOM = myObject_UOM.responseText;
                    if (myObject_UOM.readyState == 4) {
                        document.getElementById('Unit_Of_Measure').value = dataUOM;
                    }
                }; //specify name of function that will handle server response........
                myObject_UOM.open('GET', 'Get_Unit_Of_Measure.php?Item_ID='+Item_ID,true);
                myObject_UOM.send();
            }
        </script>

        <script>
            function Get_Last_Buyying_Price() {
                var Item_ID = document.getElementById("Item_ID").value;

                var myObjectGetBuyingPrice;

                if (window.XMLHttpRequest) {
                    myObjectGetBuyingPrice = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectGetBuyingPrice = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectGetBuyingPrice.overrideMimeType('text/xml');
                }

                myObjectGetBuyingPrice.onreadystatechange = function () {
                    var data2 = myObjectGetBuyingPrice.responseText;
                    if (myObjectGetBuyingPrice.readyState == 4 && myObjectGetBuyingPrice.status == 200) {
                        document.getElementById('Buying_Price').value = data2;
                    }
                }; //specify name of function that will handle server response........

                myObjectGetBuyingPrice.open('GET', 'Get_Last_Buyying_Price.php?Item_ID=' + Item_ID, true);
                myObjectGetBuyingPrice.send();

            }
        </script>

        <script>
            function updateGrnNumber() {
                var Sub_Department_ID = document.getElementById("Store_ID").value;
                if (Sub_Department_ID != '' && Sub_Department_ID != null) {
                    if (window.XMLHttpRequest) {
                        myObjectUpdateGrnNumber = new XMLHttpRequest();
                    } else if (window.ActiveXObject) {
                        myObjectUpdateGrnNumber = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObjectUpdateGrnNumber.overrideMimeType('text/xml');
                    }

                    myObjectUpdateGrnNumber.onreadystatechange = function () {
                        data2 = myObjectUpdateGrnNumber.responseText;
                        if (myObjectUpdateGrnNumber.readyState == 4) {
                            document.getElementById('Grn_Number').value = data2;
                        }
                    }; //specify name of function that will handle server response........

                    myObjectUpdateGrnNumber.open('GET', 'Grn_Open_Balance_Control_Update.php?Sub_Department_ID=' + Sub_Department_ID + '&GetGrnNumber=True', true);
                    myObjectUpdateGrnNumber.send();
                }
            }
        </script>


        <script>
            function updateGrnCreatedDate() {
                var Sub_Department_ID = document.getElementById("Store_ID").value;
                if (Sub_Department_ID != '' && Sub_Department_ID != null) {
                    if (window.XMLHttpRequest) {
                        myObjectGrnCreatedDate = new XMLHttpRequest();
                    } else if (window.ActiveXObject) {
                        myObjectGrnCreatedDate = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObjectGrnCreatedDate.overrideMimeType('text/xml');
                    }

                    myObjectGrnCreatedDate.onreadystatechange = function () {
                        data1 = myObjectGrnCreatedDate.responseText;
                        if (myObjectGrnCreatedDate.readyState == 4) {
                            document.getElementById('Grn_Date').value = data1;
                        }
                    }; //specify name of function that will handle server response........

                    myObjectGrnCreatedDate.open('GET', 'Grn_Open_Balance_Control_Update.php?Sub_Department_ID=' + Sub_Department_ID + '&GetGrnCreatedDate=True', true);
                    myObjectGrnCreatedDate.send();
                }
            }
        </script>


        <script>
            function update_Report_Button_Area() {
                var Sub_Department_ID = document.getElementById("Store_ID").value;
                if (Sub_Department_ID != '' && Sub_Department_ID != null) {
                    if (window.XMLHttpRequest) {
                        myObjectUpdateReportButton = new XMLHttpRequest();
                    } else if (window.ActiveXObject) {
                        myObjectUpdateReportButton = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObjectUpdateReportButton.overrideMimeType('text/xml');
                    }

                    myObjectUpdateReportButton.onreadystatechange = function () {
                        data5 = myObjectUpdateReportButton.responseText;
                        if (myObjectUpdateReportButton.readyState == 4) {
                            document.getElementById('Report_Button_Area').innerHTML = data5;
                        }
                    }; //specify name of function that will handle server response........

                    myObjectUpdateReportButton.open('GET', 'Grn_Open_Balance_Control_Update.php?Sub_Department_ID=' + Sub_Department_ID + '&GetGrnPreviewButtons=True', true);
                    myObjectUpdateReportButton.send();
                }
            }
        </script>



        <script>
            function update_Grn_Description() {
                var Grn_Description = document.getElementById("Grn_Description").value;
                var Sub_Department_ID = document.getElementById("Store_ID").value;

                if (Sub_Department_ID != '' && Sub_Department_ID != null) {
                    if (window.XMLHttpRequest) {
                        myObjectUpdateDescription = new XMLHttpRequest();
                    } else if (window.ActiveXObject) {
                        myObjectUpdateDescription = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObjectUpdateDescription.overrideMimeType('text/xml');
                    }

                    myObjectUpdateDescription.onreadystatechange = function () {
                        data70 = myObjectUpdateDescription.responseText;
                        if (myObjectUpdateDescription.readyState == 4) {
                            //code.........
                        }
                    }; //specify name of function that will handle server response........

                    myObjectUpdateDescription.open('GET', 'Grn_Open_Balance_Update_Description.php?Grn_Description=' + Grn_Description, true);
                    myObjectUpdateDescription.send();
                }
            }
        </script>


        <script>

            function Get_Selected_Item() {
                var Item_ID = document.getElementById("Item_ID").value;
                var Quantity = document.getElementById("Quantity").value;
                //var Item_Remark = document.getElementById("Item_Remark").value;
                var Grn_Description = document.getElementById("Grn_Description").value;
                var Sub_Department_ID = document.getElementById("Store_ID").value;
                var Buying_Price = document.getElementById("Buying_Price").value;
                var Expire_Date = document.getElementById("date2").value;
                var Manufacture_Date = document.getElementById("date").value;
                var Container = document.getElementById("Container").value;
                var Items_Per_Container = document.getElementById("Items_Per_Container").value;
                var batch_no = document.getElementById("batch_no").value; 
                var reasons_ID = document.getElementById("reasons_ID").value;
                var Today = new Date(); //current date
                var Initial_Date = new Date("1990, 01, 01");


                /*if((Manufacture_Date < Initial_Date || Manufacture_Date > Today) && Manufacture_Date != null && Manufacture_Date != ''){
                 alert("Invalid manufacture date");
                 document.getElementById("date").style = 'border: 2px solid red';
                 return false;
                 }
                 
                 if((Expire_Date > Today) && Expire_Date != null && Expire_Date != ''){
                 alert("Invalid expire date or item already expired");
                 document.getElementById("date2").style = 'border: 2px solid red';
                 return false;
                 }*/

                if (reasons_ID != '' && Item_ID != '' && Item_ID != null && Quantity != '' && Quantity != null && Sub_Department_ID != '' && Sub_Department_ID != null && Buying_Price != '' && Buying_Price != null && Buying_Price != 0 && batch_no!=null &&batch_no!=''&&Expire_Date!=''&&Expire_Date!=null) {
                    if (Manufacture_Date != null && Manufacture_Date != '' && Expire_Date != null && Expire_Date != '' && Manufacture_Date > Expire_Date) {
                        alert("Manufacture date should not be after expire date");
                        document.getElementById("date2").style = 'border: 2px solid red';
                        document.getElementById("date").style = 'border: 2px solid red';
                    } else {
                        if (window.XMLHttpRequest) {
                            my_Object_Get_Selected_Item = new XMLHttpRequest();
                        } else if (window.ActiveXObject) {
                            my_Object_Get_Selected_Item = new ActiveXObject('Micrsoft.XMLHTTP');
                            my_Object_Get_Selected_Item.overrideMimeType('text/xml');
                        }
                        my_Object_Get_Selected_Item.onreadystatechange = function () {
                            data = my_Object_Get_Selected_Item.responseText;
                            if (my_Object_Get_Selected_Item.readyState == 4 && my_Object_Get_Selected_Item.status==200) {
                                document.getElementById('Items_Fieldset_List').innerHTML = data;
                                document.getElementById("Item_Name").value = '';
                                document.getElementById("Quantity").value = '';
                                document.getElementById("Item_ID").value = '';
                                document.getElementById("Container").value = '';
                                document.getElementById("Balance").value = '';
                                document.getElementById("Items_Per_Container").value = '';
                                document.getElementById("Buying_Price").value = '';
                                document.getElementById("Unit_Of_Measure").value = '';
                                document.getElementById("date").value = '';
                                document.getElementById("date2").value = '';
                                document.getElementById("batch_no").value='';
                                document.getElementById("reasons_ID").value='';
                                //alert("Item Added Successfully");
                                update_Store_Name(Sub_Department_ID);
                                updateGrnNumber();
                                updateGrnCreatedDate();
                                update_Report_Button_Area();
                                //update_Grn_Description();
                            }
                        }; //specify name of function that will handle server response........
                            console.log("batch no"+batch_no);
                        my_Object_Get_Selected_Item.open('GET', 'Grn_Open_Balance_Add_Selected_Item.php?Item_ID=' + Item_ID + '&Quantity=' + Quantity + '&Sub_Department_ID=' + Sub_Department_ID + '&Grn_Description=' + Grn_Description + '&Buying_Price=' + Buying_Price + '&Expire_Date=' + Expire_Date + '&Manufacture_Date=' + Manufacture_Date + '&Container=' + Container + '&Items_Per_Container=' + Items_Per_Container+'&batch_no='+batch_no+'&reasons_ID='+reasons_ID, true);
                        my_Object_Get_Selected_Item.send();
                    }

                } else if ((Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) && ((Quantity != '' && Quantity != null) || (Buying_Price != '' && Buying_Price != null && Buying_Price != 0))) {
                    alertMessage();
                } else {
                    if (Item_ID == '' || Item_ID == null) {
                        document.getElementById("Item_Name").style = 'border: 2px solid red';
                    } else {
                        document.getElementById("Item_Name").style = 'border: 1px solid #27251C';
                    }

                    if (Quantity == '' || Quantity == null) {
                        document.getElementById("Quantity").focus();
                        document.getElementById("Quantity").style = 'border: 2px solid red';
                    } else {
                        document.getElementById("Quantity").style = 'border: 1px solid #27251C';
                    }
                    if (batch_no == '' || batch_no == null) {
                        document.getElementById("batch_no").focus();
                        document.getElementById("batch_no").style = 'border: 2px solid red';
                    } else {
                        document.getElementById("batch_no").style = 'border: 1px solid #27251C';
                    }
                    if (Expire_Date == '' || Expire_Date == null) {
                        document.getElementById("date2").focus();
                        document.getElementById("date2").style = 'border: 2px solid red';
                    } else {
                        document.getElementById("date2").style = 'border: 1px solid #27251C';
                    }
                    if (reasons_ID == '' || reasons_ID == null) {
                       alert(" Fill Reason To Change Balance");
                    } 

                    if (Buying_Price == '' || Buying_Price == null || Buying_Price == 0) {
                        document.getElementById("Buying_Price").value = '';
                        document.getElementById("Buying_Price").focus();
                        document.getElementById("Buying_Price").style = 'border: 2px solid red';
                    } else {
                        document.getElementById("Buying_Price").style = 'border: 1px solid #27251C';
                    }

                    //			if(Expire_Date=='' || Expire_Date == null){
                    //				document.getElementById("date2").style = 'border: 2px solid red';
                    //				document.getElementById("date2").focus();
                    //			}else{
                    //				document.getElementById("date2").style = 'border: 1px solid #27251C';
                    //			}

                    if (Sub_Department_ID == '' || Sub_Department_ID == null) {
                        document.getElementById("Store_ID").style = 'border: 2px solid red';
                        document.getElementById("Store_ID").focus();
                    } else {
                        document.getElementById("Store_ID").style = 'border: 1px solid #27251C';
                    }
                }
            }
        </script>

        <script>
            function Validate_Date() {
                var Today = new Date(); //current date
                var Date_Of_Birth = new Date(document.getElementById("date2").value);
                var Initial_Date = new Date("1900, 01, 01");

                if (Date_Of_Birth < Initial_Date || Date_Of_Birth > Today) {
                    alert("Invalid Date");
                    document.getElementById("date2").value = '';
                }
            }
        </script>
        <script>
            function alertMessage() {
                alert("Please Select Item First");
            }
        </script>

        <script>
            function Confirm_Remove_Item(Item_Name, Open_Balance_Item_ID) {
                var Confirm_Message = confirm("Are you sure you want to remove \n" + Item_Name);

                if (Confirm_Message == true) {
                    if (window.XMLHttpRequest) {
                        My_Object_Remove_Item = new XMLHttpRequest();
                    } else if (window.ActiveXObject) {
                        My_Object_Remove_Item = new ActiveXObject('Micrsoft.XMLHTTP');
                        My_Object_Remove_Item.overrideMimeType('text/xml');
                    }

                    My_Object_Remove_Item.onreadystatechange = function () {
                        data6 = My_Object_Remove_Item.responseText;
                        if (My_Object_Remove_Item.readyState == 4) {
                            document.getElementById('Items_Fieldset_List').innerHTML = data6;
                            //update_total(Registration_ID);
                            //update_Billing_Type();
                            //Update_Claim_Form_Number();
                        }
                    }; //specify name of function that will handle server response........

                    My_Object_Remove_Item.open('GET', 'Open_Balance_Remove_Item_From_List.php?Open_Balance_Item_ID=' + Open_Balance_Item_ID, true);
                    My_Object_Remove_Item.send();
                }
            }
        </script>

        <script>
            function Get_Balance() {
                var Item_ID = document.getElementById("Item_ID").value;
                var Sub_Department_ID = document.getElementById("Store_ID").value;

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

                myObjectGetBalance.open('GET', 'General_Get_Item_Actual_Balance.php?Item_ID=' + Item_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&ControlValue=Storage', true);
                myObjectGetBalance.send();
            }
        </script>

        <script type="text/javascript">
            function update_Store_Name(Sub_Department_ID) {
                if (window.XMLHttpRequest) {
                    myObjectUpdateStore = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectUpdateStore = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectUpdateStore.overrideMimeType('text/xml');
                }

                myObjectUpdateStore.onreadystatechange = function () {
                    data8099 = myObjectUpdateStore.responseText;
                    if (myObjectUpdateStore.readyState == 4) {
                        document.getElementById('Store_ID_Area').innerHTML = data8099;
                    }
                }; //specify name of function that will handle server response........

                myObjectUpdateStore.open('GET', 'update_Store_Name.php?Sub_Department_ID=' + Sub_Department_ID, true);
                myObjectUpdateStore.send();
            }
        </script>

        <script>
            function Confirm_Grn_Open_Balance_Submission(Grn_Open_Balance_ID) {
                var Confirm_Message = confirm("Are you sure you want to process this GRN?");
                if (Confirm_Message == true) {
                    document.location = 'grnopenbalanceconfirm.php?ConfirmGrn=ConfirmGrnThisPage';
                }
            }
        </script>


        <script type="text/javascript">
            function Calculate_Quantity() {
                var Items_Per_Container = document.getElementById("Items_Per_Container").value;
                var Container = document.getElementById("Container").value;
                var Quantity = document.getElementById("Quantity").value = '';

                if (Items_Per_Container != null && Items_Per_Container != '' && Container != null && Container != '') {
                    document.getElementById("Quantity").value = (Items_Per_Container * Container);
                }
            }
        </script>


        <script type="text/javascript">
            function Clear_Quantity() {
                Items_Per_Container = document.getElementById("Items_Per_Container").value = document.getElementById("Quantity").value;
                Container = document.getElementById("Container").value = 1;
            }
            
              function select_clinic_dialog(){
          $("#select_clinic").dialog({
                        title: 'REASONS',
                        width: '30%',
                        height: 200,
                        modal: true,
                    });
    }
        </script>
<?php
$categories='';
 $sqlCat="SELECT Item_Category_Name, ca.Item_Category_ID FROM tbl_items i 
                                                                        JOIN tbl_item_subcategory isc ON isc.Item_Subcategory_ID=i.Item_Subcategory_ID
                                                                        JOIN tbl_item_category ca ON ca.Item_Category_ID=isc.Item_Category_ID
                                                                        WHERE i.Item_Type IN ('Pharmacy','Others') GROUP BY ca.Item_Category_ID
                                                                        ";
                                                            $data=  mysqli_query($conn,$sqlCat)or die(mysqli_error($conn));
                                                            
								while($row = mysqli_fetch_array($data)){
								    $categories .= '<option value="'.$row['Item_Category_ID'].'">'.$row['Item_Category_Name'].'</option>';
								}
?>
        <table width=100%>
            <tr>
                <td width=25%>
                    <table width=100%>
                        <tr>
                            <td style='text-align: center;'>
                                <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                                    <option selected='selected'></option>
                                    <?php
                                    echo $categories;
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup='getItemsListFiltered(this.value)' placeholder='Enter Item Name'>
                            </td>
                        </tr>			    
                        <tr>
                            <td>
                                <fieldset style='overflow-y: scroll; height: 270px;' id='Items_Fieldset'>
                                    <table width=100%>
                                        <?php
                                        $result = mysqli_query($conn,"SELECT * FROM tbl_items where Item_Type IN ('Pharmacy','Others')  AND Item_ID IN (SELECT Item_ID FROM tbl_item_price WHERE Items_Price != '0') order by Product_Name limit 100");
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo "<tr>
									<td style='color:black; border:2px solid #ccc;text-align: left;'>";
                                            ?>

                                            <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);">

                                            <?php
                                            echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</label></td></tr>";
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
                                <table width = 100%>
                                    <tr>
                                        <td id='Item_Name_Label' width="19%"style="text-align: center;">Item Name</td>
                                        <td id='Unit_Of_Measure_Label' width="6%" style="text-align: center;"><b>UOM</b></td>
                                        <td  <?php echo $display ?> id='Container_Label' width="6%" style="text-align: center;">Units</td>
                                        <td  <?php echo $display ?> id='Items_Per_Container_Label' width="4%" style="text-align: center;">Items Per Unit</td>
                                        <td id='Quantity_Label' width="4%" style="text-align: center;">Quantity</td>
                                        <td id='Balance_Label' width="4%" style="text-align: center;">Balance</td>
                                        <td id='Buying_Price_Label' style="text-align: center;" width="6%">Buying Price</td>
                                        <td id='Batch_no_Label' style="text-align: center;" width="6%">Batch No.</td>
                                        <td id='Manufacture_Date_Label' style="text-align: center;display: none">Manuf Date</td>
                                        <td id='Expire_Date_Label' style="text-align: center;width: 6%">Expire Date</td>
                                        <td id='Expire_Date_Label' style="text-align: center;width: 6%">Reasons</td>
                                        <!-- <td id='Remark_Label'>Item Remark</td>  -->
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type='text' name='Item_Name' id='Item_Name' size=20 placeholder='Item Name' readonly='readonly' required='required'>
                                            <input type='hidden' name='Item_ID' id='Item_ID' value=''>
                                        </td>
                                        <td><input type="text" name="Unit_Of_Measure" id="Unit_Of_Measure" value="" readonly="readonly" style="text-align: center;"></td>
                                        <td   <?php echo $display ?>>
										<input type='text' name='Container' id='Container' autocomplete='off' size=10 placeholder='Units' onchange='numberOnly(this); Calculate_Quantity();' onkeyup='numberOnly(this); Calculate_Quantity();' onkeypress='numberOnly(this); Calculate_Quantity();' oninput="numberOnly(this); Calculate_Quantity();" autocomplete='off' required='required' style="text-align: center;">
                                        </td>
                                        <td  <?php echo $display ?>>
										<input type='text' name='Items_Per_Container' id='Items_Per_Container' autocomplete='off' size=10 placeholder='Items' onchange='numberOnly(this); Calculate_Quantity();' onkeyup='numberOnly(this); Calculate_Quantity();' onkeypress='numberOnly(this); Calculate_Quantity();' oninput="numberOnly(this); Calculate_Quantity();" autocomplete='off' required='required' style="text-align: center;">
                                        </td>
                                        <td>
										<input type='text' name='Quantity' id='Quantity' autocomplete='off' size=10 placeholder='Quantity' onchange='numberOnly(this); Clear_Quantity();' onkeyup='numberOnly(this); Clear_Quantity();' onkeypress='numberOnly(this); Clear_Quantity();' oninput="numberOnly(this); Clear_Quantity();" autocomplete='off' required='required' style="text-align: center;">
                                        </td>
                                        <td>
                                            <input type='text' name='Balance' id='Balance' autocomplete='off' size=10 placeholder='Balance' readonly='readonly' style="text-align: center;">
                                        </td>
                                        <td>
                                            <input type='text' name='Buying_Price' id='Buying_Price' size=10 placeholder='Buying Price' style="text-align: center;">
                                        </td>
                                        <td>
                                            <input type='text' name='Buying_Price' id='batch_no' size=10 placeholder='Batch No.' style="text-align: center;">
                                        </td>
                                        <td style="display: none">
                                            <input type='text' name='Manufacture_Date' id='date' size=10 placeholder='Manufacture Date' style="text-align: center;">
                                        </td>
                                        <td>
                                            <input type='text' name='Expire_Date' readonly="readonly" id='date2' size=10 placeholder='Expire Date' style="text-align: center;">
                                        </td>
                                        <!-- <td width=10%>
                                                <input type='text' name='Item_Remark' id='Item_Remark' size=30 placeholder='Item Remark' autocomplete='off'>
                                        </td> -->
                                        <td style="text-align: center; width: 2%;"><input type='button' class='art-button-green' onclick="select_clinic_dialog()" value='R'></td>
                                        <td style="text-align: center; width: 5%;" id='Add_Button_Area'>
                                            
                                            <input type='button' name='submit' id='submit' value='ADD' class='art-button-green' onclick='Get_Selected_Item()'>
                                            <input type='hidden' name='Add_Grn_Open_Balance' value='true'/>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                    <!--<iframe width='100%' src='requisition_items_Iframe.php?Grn_Open_Balance_ID=<?php //echo $Grn_Open_Balance_ID; ?>' width='100%' height=250px></iframe>-->
                                <fieldset style='overflow-y: scroll; height: 260px;' id='Items_Fieldset_List'>
                                    <?php
                                    echo '<center><table width = 100% border=0>';
                                    echo '<tr><td colspan="11"><hr></td></tr>';
                                    echo '<tr><td width=4% style="text-align: center;">Sn</td>
                                            <td width=20%>Item Name</td>
											<td width=7% style="text-align: center;">UOM</td>
											<td '.$display.' width=9% style="text-align: center;">Units</td>
											<td '.$display.' width=9% style="text-align: center;">Items Per unit</td>
											<td width=9% style="text-align: center;">Quantity</td>
											<td width=9% style="text-align: right;">Buying Price</td>
											<td width=6% style="text-align: right;">Batch No</td>
											<td width=7% style="text-align: center;">Sub Total</td>
											<td width=7% style="text-align: center;display:none">Manuf Date</td>
											<td width=7% style="text-align: center;">Expire Date</td>
											
											<td width=5%>Remove</td></tr>';
                                    echo '<tr><td colspan="11"><hr></td></tr>';

                                    if (isset($_SESSION['Grn_Open_Balance_ID'])) {
                                        $Grn_Open_Balance_ID = $_SESSION['Grn_Open_Balance_ID'];
                                    } else {
                                        $Grn_Open_Balance_ID = 0;
                                    }
                                    $select_Open_Balance_Items = mysqli_query($conn,"select ads.reasons,obi.batch_no,obi.Open_Balance_Item_ID, itm.Product_Name, obi.Item_Quantity, obi.Item_Remark, obi.Buying_Price,
													obi.Manufacture_Date, obi.Expire_Date, obi.Container_Qty, obi.Items_Per_Container, itm.Unit_Of_Measure
													from tbl_grn_open_balance_items obi, tbl_grn_open_balance gop, tbl_items itm,tbl_reasons_adjustment ads where
													gop.Grn_Open_Balance_ID = obi.Grn_Open_Balance_ID and 
													itm.Item_ID = obi.Item_ID and obi.reason_id=ads.reason_id and
													obi.Grn_Open_Balance_ID ='$Grn_Open_Balance_ID' and
													Grn_Open_Balance_Status = 'pending' order by obi.Open_Balance_Item_ID desc") or die(mysqli_error($conn));

                                    $Temp = 1;
                                    $num_rows = mysqli_num_rows($select_Open_Balance_Items);
                                    while ($row = mysqli_fetch_array($select_Open_Balance_Items)) {
                                        echo "<tr><td><input type='text' readonly='readonly' value='" . $Temp . "' style='text-align: center;'></td>";
                                        echo "<td><input type='text' readonly='readonly' value='" . $row['Product_Name'] . "'></td>";
                                        echo "<td><input type='text' readonly='readonly' value='" . $row['Unit_Of_Measure'] . "' style='text-align: center;'></td>";
                                        echo "<td  $display ><input type='text' value='" . $row['Container_Qty'] . "' style='text-align: center;'></td>";
                                        echo "<td  $display ><input type='text' value='" . $row['Items_Per_Container'] . "' style='text-align: center;'></td>";
                                        echo "<td><input type='text' value='" . $row['Item_Quantity'] . "' style='text-align: center;'></td>";
                                        echo "<td style='text-align: right;'><input type='text' value='" . number_format($row['Buying_Price'],2) . "' style='text-align: right;'></td>";
                                        echo "<td style='text-align: right;'><input type='text' readonly='readonly'value='" . $row['batch_no'] . "' style='text-align: right;'></td>";
                                        echo "<td style='text-align: right;'><input type='text' value='" . number_format($row['Item_Quantity'] * $row['Buying_Price'],2) . "' style='text-align: right;'></td>";
                                        echo "<td style='display:none'><input type='text' readonly='readonly' value='" . $row['Manufacture_Date'] . "'></td>";
                                        echo "<td><input type='text' readonly='readonly' value='" . $row['Expire_Date'] . "'></td>";
                                        
                                        //echo "<td><input type='text' value='".$row['Item_Remark']."'></td>";
                                        ?>
                                    
                                    <!--<a href='#' onclick="select_clinic_dialog()">-->
                                    <!--<td width=6%><input type='button' class='art-button-green' onclick="select_clinic_dialog()" value='R'></td>-->
                                    <!--</a>-->
                                        
                                        <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' class='art-button-green' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'", "", $row['Product_Name']); ?>",<?php echo $row['Open_Balance_Item_ID']; ?>)'></td>
    <?php
    echo "</tr>";
    echo "<tr><td></td><td><input type='text' readonly='readonly' value='" . $row['reasons'] . "'></td></tr>";
    $Temp++;
}
echo '</table>';
?>
                                </fieldset>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
            <tr>
            <table width=100%>
                <tr>
                    <td width=100% style='text-align: right;' id='Report_Button_Area'>
<?php
if (isset($_SESSION['Grn_Open_Balance_ID'])) {
    $Temp_Grn_Open_Balance_ID = $_SESSION['Grn_Open_Balance_ID'];
    if ($num_rows > 0) {
        ?>
                                <input type='button' name='Confirm_Grn' id='Confirm_Grn' onclick='Confirm_Grn_Open_Balance_Submission(<?php echo $Temp_Grn_Open_Balance_ID; ?>)' value='PROCESS GRN' class='art-button-green'>
                                <!--<a href='purchaseorderpreview.php?Grn_Open_Balance_ID=<?php //echo $Temp_Grn_Open_Balance_ID; ?>&PurchaseOrderPreview=PurchaseOrderPreviewThisPage' class='art-button-green' target='_Blank'>PREVIEW GRN</a>-->
                <?php
            }
        }
        ?>
                    </td> 
                </tr>
            </table>
            </tr>
        </table>

        <?php
        echo '<center>';
        if (isset($_SESSION['userinfo'])) {
            if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
                //echo "<a href='departmentalopenbalance.php?SessionCategory=Storage And Supply&Status=new' class='art-button-green'>NEW OPEN BALANCE ~ REAGENTS</a>";
                //echo "<a href='Reagent_Control_Grn_Open_Balance_Sessions.php?Reagent_New_Grn_Open_Balance=True&Status=new' class='art-button-green'>NEW OPEN BALANCE ~ REAGENTS</a>";
            }
        }
        echo '</center>';
        ?>
<?php
include("./includes/footer.php");
?>