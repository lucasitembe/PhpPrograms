<script src='js/functions.js'></script>
<style>
    .labefor:hover{background-color: #a8d1ff;cursor: pointer}
    label.labefor { width: 100%; }
</style>

<?php
    include_once("./includes/header.php");
    include_once("./includes/connection.php");

    include_once("./functions/department.php");
    include_once("./functions/employee.php");
    include_once("./functions/items.php");
    include_once("./functions/requisition.php");
    include_once("requisition_navigation.php");


    //get employee name
    if (isset($_SESSION['userinfo'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    } else {
        $Employee_Name = 'Unknown Officer';
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

    $Requisition_ID = '';
    if (isset($_SESSION['Requisition_ID'])) {
        $Requisition_ID = $_SESSION['Requisition_ID'];
    }

    if (isset($_GET['Requisition_ID'])) {
        $Requisition_ID = $_GET['Requisition_ID'];
    }

    $Requisition = array();
    if (!empty($Requisition_ID)) {
        $Requisition = Get_Requisition($Requisition_ID);
    }

    if (isset($_SESSION['Storage_Info'])) {
        $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        $Current_Store_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
    }

    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }
?>

<form action='#' method='post' name='myForm' id='myForm' >
    <fieldset>
        <legend align='right'><b>Requisition  ~ <?php echo $Current_Store_Name; ?></b></legend>
        <table width=100%>
            <tr>
                <td width='12%' style='text-align: right;'>Document Number</td>
                <td width='5%'>
                    <?php if (isset($Requisition_ID) && !empty($Requisition_ID)) { ?>
                        <input type='text' name='Requisition_ID' size='6' id='Requisition_ID'
                               readonly='readonly' value='<?php echo $Requisition_ID; ?>'>
                    <?php } else { ?>
                        <input type='text' name='Requisition_ID' size='6'  id='Requisition_ID' value='new'>
                    <?php } ?>
                </td>

                <td width='12%' style='text-align: right;'>Requisition Date</td>
                <td width='16%'>
                    <?php
                        if (!empty($Requisition_ID)) {
                            echo "<input type='text' readonly='readonly' name='Transaction_Date' id='Transaction_Date' ";
                            echo "value='{$Requisition['Created_Date']}'/>";
                        } else {
                            echo "<input type='text' readonly='readonly' name='Transaction_Date' id='Transaction_Date' value='".$Today."'>";
                        }
                    ?>
                </td> 
                <td style='text-align: right;'>Officer</td>
                <td>
                    <?php
                        echo "<input type='text' readonly='readonly'  value='{$Employee_Name}'>";
                    ?>
                </td>

            </tr>
            <tr>
                <td width='10%' style='text-align: right;'>Store Requesting</td>
                <td width='16%'>
                    <select name='Store_Need'  class="Issuevalue" id='Store_Need' style="width:100%" >
                        <?php
                            $Sub_Department = Get_Sub_Department($Current_Store_ID);
                                echo "<option value='$Current_Store_ID'>";
                                echo "$Current_Store_Name";
                                echo "</option>";
                        ?>
                    </select>
                </td>
                <td width='13%' style='text-align: right;'> Store Issuing </td>
                <td >
                    <select name='Store_Issue' class="Issuevalue" id='Store_Issue' style="width:100%">
                        <?php
                        
                          $can_login_to_high_privileges_department = $_SESSION['userinfo']['can_login_to_high_privileges_department'];
                                $filter_sub_d="";
                                if($can_login_to_high_privileges_department=='yes'){
                                    $filter_sub_d="and privileges='high'";
                                }
                                if($can_login_to_high_privileges_department!='yes'){
                                    $filter_sub_d="and privileges='normal'";
                                }
                                
                            if (!empty($Requisition_ID)) {
                                $Sub_Department = Get_Sub_Department($Requisition['Store_Issue']);
                                echo "<option value='{$Sub_Department['Sub_Department_ID']}'>";
                                echo "{$Sub_Department['Sub_Department_Name']}";
                                echo "</option>";
                            } else {
                                echo "<option></option>";
                                $Sub_Department_List = Get_Sub_Department_By_Department_Nature('Storage And Supply');
                                foreach($Sub_Department_List as $Sub_Department) {
                                    if ($Sub_Department['Sub_Department_ID'] != $Current_Store_ID) {
                                        echo "<option value='{$Sub_Department['Sub_Department_ID']}'>";
                                        echo "{$Sub_Department['Sub_Department_Name']}";
                                        echo "</option>";
                                    }
                                }
                            }
                        ?>
                    </select>
                </td>
                <td style='text-align: right;'>Description</td>
                <td>
                    <input type="text" id="description" name="description" onkeyup="save_description(<?=$Requisition_ID?>)">
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
                                        <?php include_once("./requisition_show_items.php"); ?>
                                    </fieldset>
                                </td>
                            </tr>

                            <tr>
                                <td id='Submit_Button_Area' style='text-align: right;'>
                                    <?php if (isset($Requisition_ID) && $Requisition_ID > 0) { ?>
                                        <table width=100%>
                                            <tr>
                                                <td style='text-align: right;'>Username</td>
                                                <td>
                                                    <input type='text' name='Supervisor_Username' title=' Username'
                                                           id='Supervisor_Username' autocomplete='off'
                                                           placeholder=' Username' required='required'>
                                                </td>
                                                <td style='text-align: right;'> Password</td>
                                                <td>
                                                    <input type='password' title=' Password' name='Supervisor_Password'
                                                           id='Supervisor_Password' autocomplete='off'
                                                           placeholder=' Password' required='required'>
                                                </td>
                                                <td style='text-align: right;'>
                                                    <input type='button' class='art-button-green' value='SUBMIT'
                                                           onclick='Confirm_Submit_Document()'>
                                                    <a href="#" onclick="cancel_document()" style="font-family: Arial, Helvetica, sans-serif;" class='art-button-green' >CANCEL</a>
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
    <?php 
        
            $sql_check_if_all_approve_result=mysqli_query($conn,"SELECT document_type FROM tbl_document_approval_control WHERE document_type='requisition' AND document_number='$Requisition_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_check_if_all_approve_result)>0){
                $value_proccessed="yes";
                $hidden="style='display:none'";
            }else{
                $hidden="";
                $value_proccessed="no";
            }
    ?>
    <input type="text" value="<?= $value_proccessed ?>" id="check_if_already_processed" hidden="hidden"/>
    <div id="Error_Message"></div>
    <div id="Selection_Error">
        You can not create <b>requisition</b> from the same store.<br/> Please change <b>Store requesting</b> or <b>Store issuing</b>
    </div>
    <link rel="stylesheet" href="css/select2.min.css" media="screen">
    <script src="media/js/jquery.js" type="text/javascript"></script>
    <script src="css/jquery-ui.js"></script>
    <script src="js/select2.min.js"></script>

    <div id="cancel_space"></div>

    <script type='text/javascript'>
        $(document).ready(function () {
            $('select').select2();
            $("#Error_Message").dialog({ autoOpen: false, width:'60%',height:150, title:'eHMS 2.0 ~ Error!',modal: true});
            $("#Selection_Error").dialog({ autoOpen: false, width:'60%',height:150, title:'eHMS 2.0 ~ Error!',modal: true});

            addDatePicker($('#Transaction_Date2'));
        });

        function Error_Message(Error_Message) {
            $("#Error_Message").html(Error_Message);
            $("#Error_Message").dialog("open");
        }
    </script>


    <script>
        function Get_Item_Balance_By_Classification(Classification) {
            document.getElementById("Search_Value").value = '';
            Current_Store_ID = document.getElementById("Store_Need").value;

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
            myObject.open('GET', 'requisition_get_item_balance_by_classification.php?Classification=' + Classification +
                "&Current_Store_ID=" + Current_Store_ID, true);
            myObject.send();
        }
    </script>

    <script type='text/javascript'>
        function Get_Item_Balance_By_Classification_Filtered(Item_Name) {
            Current_Store_ID = document.getElementById("Store_Need").value;
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
            myObject.open('GET', 'requisition_get_item_balance_by_classification.php?Classification=' + Classification +
                "&Current_Store_ID=" + Current_Store_ID + '&Item_Name=' + Item_Name, true);
            myObject.send();
        }
    </script>

    <script type='text/javascript'>
        function Add_To_Document(Item_ID){
            Requisition_ID = document.getElementById("Requisition_ID").value;

            Store_Need = document.getElementById("Store_Need").value;
            Store_Issue = document.getElementById("Store_Issue").value;
            Transaction_Date = document.getElementById("Transaction_Date").value;

            $

            Employee_ID = <?php echo $Employee_ID; ?>;
            Branch_ID = <?php echo $Branch_ID; ?>;
            if(Store_Need == Store_Issue && Store_Need != null && Store_Need != ''){
                $("#Selection_Error").dialog("open");
            } else if ((Store_Need > 0 || Store_Need != "") &&
                (Store_Issue > 0 || Store_Issue != "") &&
                (Employee_ID > 0 || Employee_ID != "") &&
                (Transaction_Date != "")) {

                if (Requisition_ID == "new") {
                    if (window.XMLHttpRequest) {
                        myRequisitionObject = new XMLHttpRequest();
                    } else if (window.ActiveXObject) {
                        myRequisitionObject = new ActiveXObject('Micrsoft.XMLHTTP');
                        myRequisitionObject.overrideMimeType('text/xml');
                    }

                    myRequisitionObject.onreadystatechange = function () {
                        data = myRequisitionObject.responseText;
                        if (myRequisitionObject.readyState == 4) {
                            Requisition_ID = data;
                            if ($.isNumeric(Requisition_ID)) {
                                document.getElementById("Requisition_ID").value = Requisition_ID;
                                Add_Document_Item(Item_ID);
                            } else {
                                Error_Message(Requisition_ID);
                            }
                        }
                    };
                    myRequisitionObject.open('GET', 'requisition_add_document.php?Requisition_ID=' + Requisition_ID
                        + '&Store_Need=' + Store_Need + '&Employee_ID=' + Employee_ID
                        + '&Store_Issue=' + Store_Issue
                        + '&Branch_ID=' + Branch_ID + '&Transaction_Date=' + Transaction_Date
                        + '&Item_ID=' + Item_ID, true);
                    myRequisitionObject.send();
                } else {
                    if (window.XMLHttpRequest) {
                        myRequisitionObject = new XMLHttpRequest();
                    } else if (window.ActiveXObject) {
                        myRequisitionObject = new ActiveXObject('Micrsoft.XMLHTTP');
                        myRequisitionObject.overrideMimeType('text/xml');
                    }

                    myRequisitionObject.onreadystatechange = function () {
                        data = myRequisitionObject.responseText;
                        if (myRequisitionObject.readyState == 4) {
                            if (data == "yes"){
                                Error_Message("Item Already Exists");
                            } else {
                                Add_Document_Item(Item_ID);
                            }
                        }
                    };
                    myRequisitionObject.open('GET', 'requisition_check_item_exists.php?Requisition_ID=' + Requisition_ID
                        + '&Item_ID=' + Item_ID, true);
                    myRequisitionObject.send();
                }
            } else {
                Error_Message("PLEASE SELECT <b>STORE REQUESTING</b> AND <b>STORE ISSUING</b> ABOVE TO CONTINUE");
            }
        }
    </script>

    <script type='text/javascript'>
        function Add_Document_Item(Item_ID){
            Requisition_ID = document.getElementById("Requisition_ID").value;
            var check_if_already_processed=$("#check_if_already_processed").val();
            if(check_if_already_processed=="yes"){
               alert("You can not add item to the already processed Requisition"); 
               exit(0);
            }
            if (Requisition_ID > 0) {
                if (window.XMLHttpRequest) {
                    myRequisitionItemObject = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myRequisitionItemObject = new ActiveXObject('Micrsoft.XMLHTTP');
                    myRequisitionItemObject.overrideMimeType('text/xml');
                }

                myRequisitionItemObject.onreadystatechange = function () {
                    data = myRequisitionItemObject.responseText;
                    if (myRequisitionItemObject.readyState == 4) {
                        document.getElementById('Items_Fieldset_List').innerHTML = data;
                        showSaveButtons();
                    }
                };
                myRequisitionItemObject.open('GET', 'requisition_add_document_item.php?Requisition_ID=' + Requisition_ID
                    + '&Item_ID=' + Item_ID, true);
                myRequisitionItemObject.send();
            }
        }
    </script>

    <script type='text/javascript'>
        function Confirm_Remove_Item (Item_Name, Requisition_Item_ID) {
            Requisition_ID = document.getElementById("Requisition_ID").value;

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
                myObject.open('GET', 'requisition_remove_item.php?Requisition_Item_ID=' + Requisition_Item_ID
                    + "&Requisition_ID=" + Requisition_ID, true);
                myObject.send();
            }
        }
    </script>

    <script type='text/javascript'>
        function Update_Quantity_Required (Quantity_Required, Requisition_Item_ID) {
            Store_Balance = parseInt(document.getElementById("Store_Balance_"+Requisition_Item_ID).value);
            if(Quantity_Required == "") { Quantity_Required = 0; }
            Quantity_Required = parseInt(Quantity_Required);

            if (Quantity_Required > 0) {
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
                myObject.open('GET', 'requisition_update_quantity_required.php?Requisition_Item_ID=' + Requisition_Item_ID
                    + "&Quantity_Required=" + Quantity_Required, true);
                myObject.send();
            }

            document.getElementById("Quantity_Required_"+Requisition_Item_ID).value = Quantity_Required;
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
            myObject.open('GET', 'requisition_update_item_remark.php?Requisition_Item_ID=' + Requisition_Item_ID
                + "&Item_Remark=" + Item_Remark, true);
            myObject.send();
        }
    </script>

    <script type='text/javascript'>
        function showSaveButtons() {
            var thml = ""
            thml = "<table width=100%>";
            thml += " <tr>";
            thml += "<td style='text-align: right;'>Username</td>";
            thml += "<td>";
            thml += " <input type='text' name='Supervisor_Username' title='Username' id='Supervisor_Username' autocomplete='off' placeholder='Username' required='required'>";
            thml += "</td>";
            thml += "<td style='text-align: right;'>Password</td>";
            thml += "<td>";
            thml += " <input type='password' title='Password' name='Supervisor_Password' id='Supervisor_Password' autocomplete='off' placeholder='Password' required='required'>";
            thml += "</td>";
            thml += "<td style='text-align: right;'>";
            thml += "<input type='button' class='art-button-green' value='APPROVE' onclick='Confirm_Submit_Document()'> ";
            thml += "</td>";
            thml += "</tr>";
            thml += " </table>";

            document.getElementById("Submit_Button_Area").innerHTML = thml;
        }
    </script>

    <!-- description autosave -->
    <script>
        function save_description(id){
            var id = id;
            var Requisition_ID = document.getElementById('Requisition_ID').value;
            var description = document.getElementById('description').value;
            $.post(
                'save_description.php',{
                    id:Requisition_ID,
                    description:description
                },(response) => {
                    console.log(response);
                }
            );
        }
    </script>
    <!-- description autosave -->

    <script type='text/javascript'>

        function cancel_document(){
            var Requisition_ID = document.getElementById("Requisition_ID").value;
            var Employee_ID = <?php echo $Employee_ID; ?>;
            if(confirm('Are you want to cancel the document')){
                $.post(
                    'cancel_doc.php',{Requisition_ID:Requisition_ID,Employee_ID:Employee_ID},(data) => {
                        $("#cancel_space").dialog({
                            title: "DOCUMENT NUMBER : " + Requisition_ID,
                            width: "30%",
                            height: 300,
                            modal: true
                        });
                        $('#cancel_space').html(data);
                        $('#cancel_space').dialog('open');
                    }
                )
            }
        }

        function Confirm_Submit_Document() {
            var Requisition_ID = document.getElementById("Requisition_ID").value;
            var description = document.getElementById("description").value;
            var Supervisor_Username = document.getElementById("Supervisor_Username").value;
            var Supervisor_Password = document.getElementById("Supervisor_Password").value;

            if (Requisition_ID == null || Requisition_ID == '') {
                alert('An error has occured. Click ok to reload page.');
                window.location = window.location.href;
            }

            if (Supervisor_Username != null && Supervisor_Username != '' && Supervisor_Password != null && Supervisor_Password != '') {
                var r = confirm("Are you sure you want to process this document?\n\nClick OK to proceed");
                if (r == true) {
                    $.ajax({ 
                        type: 'GET',
                        url: 'verify_approver_privileges_support.php',
                        data: 'Username=' + Supervisor_Username + '&Password=' + Supervisor_Password + '&document_number=' + Requisition_ID+"&document_type=requisition",
                        cache: false,
                        success: function (feedback) {
                            if (feedback == 'all_approve_success') {
                                $("#remove_button_column").hide();
                                final_approval_process_for_requisition(); 
                            }else if(feedback=="invalid_privileges"){
                                alert("Invalid Username or Password or you do not have enough privilage to approve this requisition");
                            }else if(feedback=="fail_to_approve"){
                                alert("Fail to approve..please try again");  
                            }else{
                                 $(".remove_button_column").hide();
                                 $(".Search_Value").prop("disabled","disabled");
                                 $("#check_if_already_processed").val("yes");
                                alert(feedback);
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
        
        function final_approval_process_for_requisition(){
            var Requisition_ID = document.getElementById("Requisition_ID").value;
            if (Requisition_ID == null || Requisition_ID == '') {
                alert('An error has occured. Click ok to reload page.');
                window.location = window.location.href;
            }
            $.ajax({
                type: 'GET',
                url: 'requisition_process.php',
                data: 'Requisition_ID=' + Requisition_ID,
                cache: false,
                success: function (feedback) {
                    if (feedback == 1) {
                        alert('Requisition saved successifully');
                        window.open("previewrequisition.php?Requisition_ID="+Requisition_ID+"&RequisitionPreview=RequisitionPreviewThisPage","_parent");
                    } else if (feedback == '2') {
                        Error_Message("Store Balance Might have changed. Please check again!!");
                        //location.reload();
                    }  else if (feedback == '3') {
                        Error_Message("Some of the Quantity might be more than Store Balance. Please check again!!");
                        //location.reload();
                    } else if (feedback == '4') {
                        Error_Message("Unable To Update Document. Please check again!!");
                        //location.reload();
                    } 
                }
            });         
        }
    </script>

<?php include("./includes/footer.php"); ?>