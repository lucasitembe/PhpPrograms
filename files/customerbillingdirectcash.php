<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
///////////////////////check for system configuration//////////////////

$configResult = mysqli_query($conn,"SELECT * FROM tbl_config") or die(mysqli_error($conn));

                while($data = mysqli_fetch_assoc($configResult)){
                    $configname = $data['configname'];
                    $configvalue = $data['configvalue'];
                    $_SESSION['configData'][$configname] = strtolower($configvalue);
                }
///////////////////////////////////////////////////////////////////////////////////////
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
        if ($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            //@session_start();
            if (!isset($_SESSION['supervisor'])) {
                header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>

<?php
    
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes') {
        ?>
        <?php
        echo'<a href="othersources_revenue_report.php?CUSTOMERNO='.$_GET['CUSTOMERNO'].'&CUSTOMER_TYPE='.$_GET['CUSTOMER_TYPE'].'" class="art-button-green">
            REVENUE REPORT
        </a>'; ?>
        <a href='revenue_from_other_sources.php?SearchListRevenueFromOtherSources=SearchListRevenueFromOtherSourcesThisPage' class='art-button-green'>
            BACK
        </a>
    <?php }
}
?>


<!-- new date function (Contain years, Months and days)--> 
<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
?>
<!-- end of the function -->


<?php
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Employee';
}
?>

<?php
//    select customer information
if (isset($_GET['CUSTOMERNO']) && isset($_GET['CUSTOMER_TYPE'])) {
    $customerType=mysqli_real_escape_string($conn,$_GET['CUSTOMER_TYPE']);
    $customerno=mysqli_real_escape_string($conn,$_GET['CUSTOMERNO']);
    if($customerType=='CUSTOMER'){
        $select_customer=mysqli_query($conn,"SELECT Patient_Name AS customer_name, Registration_ID AS customerID,Phone_Number FROM tbl_patient_registration WHERE Registration_ID= $customerno");
    }
    if($customerType=='SPONSOR'){
        $select_customer=mysqli_query($conn,"SELECT Sponsor_ID AS customerID, Guarantor_Name AS customer_name,Phone_Number FROM tbl_sponsor WHERE Sponsor_ID=$customerno");
    }
    $no = mysqli_num_rows($select_customer);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_customer)) {
            $Registration_ID = $row['customerID'];
            $customer_name = $row['customer_name'];
            $Phone_Number = $row['Phone_Number'];
            $Email='';
        }

    } else {
      $Registration_ID = 0;
      $customer_name = '';
      $Phone_Number = '';
      $Email='';
    }
}

?>

<!-- get employee id-->
<?php
if(isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
?>

<fieldset>  
    <legend align="right"><b>DIRECT CASH ~ <?=$customerType;?>: REVENUE CENTER</b></legend>
    <center> 
        <table width="100%">
            <tr>
                <td width='10%' style="text-align:right;"><b><?=($customerType=='SPONSOR')?"Sponsor Name":"Customer Name";?></b></td>
                <td width='15%' ><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $customer_name; ?>'></td>
                <td width='11%'style="text-align:right;"><b><?=($customerType=='SPONSOR')?"Sponsor Number":"Customer Number";?></b></td>
                <td width='12%'><input type='text' name='Registration_Number' disabled='disabled' id='Registration_Number' value='<?php echo $Registration_ID; ?>'></td>
                <td style="text-align:right;"><b>Prepared By</b></td>
                <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Employee_Name; ?>'></td>
            
                <td style="text-align:right;"><b>Supervised By</b></td>
                <?php
                if(isset($_SESSION['supervisor'])){
                    if (isset($_SESSION['supervisor']['Session_Master_Priveleges'])) {
                        if($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes') {
                            $Supervisor = $_SESSION['supervisor']['Employee_Name'];
                        } else {
                            $Supervisor = "Unknown Supervisor";
                        }
                    } else {
                        $Supervisor = "Unknown Supervisor";
                    }
                } else {
                    $Supervisor = "Unknown Supervisor";
                }
                ?>
                <td><input type='text' name='Supervisor_Name' id='Supervisor_Name' disabled='disabled' value='<?php echo $Supervisor; ?>'></td>
            </tr>
            <tr><td><br></td></tr>
            <tr>
            <td style='text-align:right;'><b>Email</b></td><td><input type='text' name='Email' id='Email' disabled='disabled' value='<?php echo $Email; ?>'></td>
            <td style="text-align:right;"><b>Cheque Number</b></td>
            <td>
            <input type="text" name="cheque_no" id="cheque_no" placeholder="Enter Bank Cheque Number">
            </td>
            <td style="text-align:right;"><b>Narration</b></td>
            <td><textarea name="narration" id="narration" placeholder="Enter Narration"></textarea>
            </td>
            <td style="text-align:right;"><b>Attachment</b></td>
            <td><input type="file" name="cheque_attachment" id="cheque_attachment" class="art-button-green"></td>
            </tr>
            
        </table>
    </center>
</fieldset>
<center>
    <table width=100%>
        <tr>
            <td width=25%>
                <table width = 100%>
                    <tr>
                        <td style='text-align: center;'>
                            <br>
                            <!--select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                                <option selected='selected'></option>
                                <?php/*
                                $data = mysqli_query($conn,"select c.Item_Category_ID, c.Item_Category_Name from 
                                                                    tbl_item_category c, tbl_item_subcategory sc, tbl_items i where
                                                                    c.Item_Category_ID = sc.Item_Category_ID and
                                                                    sc.Item_Subcategory_ID = i.Item_Subcategory_ID and
                                                                    i.Visible_Status = 'Others' group by c.Item_Category_ID order by c.Item_Category_Name") or die(mysqli_error($conn));
                                while ($row = mysqli_fetch_array($data)) {
                                    echo '<option value="' . $row['Item_Category_ID'] . '">' . $row['Item_Category_Name'] . '</option>';
                                }*/
                                ?>   
                            </select-->
                        </td>
                    </tr>
                    <tr>
                        <td><input type='text' id='Search_Value' name='Search_Value' onkeyup='getItemsListFiltered(this.value)' placeholder='Enter Item Name' autocomplete="off"></td>
                    </tr>               
                    <tr>
                        <td>
                            <fieldset style='overflow-y: scroll; height: 250px;' id='Items_Fieldset'>
                                <table width=100%>
                                    <?php
                                    $result = mysqli_query($conn,"select Item_ID, Product_Name from tbl_items where Visible_Status = 'Others' order by Product_Name limit 200");
                                    while ($row = mysqli_fetch_array($result)){
                                        echo "<tr><td style='color:black; border:2px solid #ccc;text-align: left;'>";
                                        ?>
                                        <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);">
                                        <?php
                                        echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "<label></td></tr>";
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
                    <table width=100%>
                        <tr>
                            <td width="30%">Item Name</td>
                            <td width="40%">Item Description</td>
                            <td width="12%">Quantity</td>
                            <td width="12%">Amount</td>
                            <td width="6%"></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="Item_Name" id="Item_Name" readonly="readonly" placeholder='Item Name'>
                                <input type="hidden" id="Item_ID" name="Item_ID" value="">
                            </td>
                            <td> 
                                <input type='text' name='Item_Description' id='Item_Description' placeholder='Item Description' placeholder="off" autocomplete="off">
                            </td>
                            <td>
                                <input type='text' name='Quantity' class="numberonly" id='Quantity' placeholder='Quantity' autocomplete="off">
                            </td>
                            <td>
                                <input type='text' name='Amount' class="numberonly" id='Amount' placeholder='Amount' autocomplete="off">
                            </td>
                            <td style='text-align: center;'>
                                <input type='button' name='submit' id='submit' value='ADD' class='art-button-green' onclick='Get_Selected_Item()'>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <fieldset style='overflow-y: scroll; height: 215px;' id="Transaction_Area">
                                    <table width="100%">
                                        <tr>
                                            <td width="5%"><b>SN</b></td>
                                            <td><b>ITEM NAME</b></td>
                                            <td><b>ITEM DESCRIPTION</b></td>
                                            <td width="9%" style="text-align: right;"><b>QUANTITY</b></td>
                                            <td width="9%" style="text-align: right;"><b>AMOUNT</b></td>
                                            <td width="5%"></td>
                                        </tr>
                                        <?php
                                        //display items
                                        $Grand_Total = 0;
                                        $select = mysqli_query($conn,"select dcc.Cache_ID, dcc.Item_Description, dcc.Amount,dcc.Quantity, i.Product_Name
                                                                            from tbl_direct_cash_cache dcc, tbl_items i where 
                                                                            dcc.Registration_ID = '$Registration_ID' and
                                                                            dcc.Item_ID = i.Item_ID and
                                                                            Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                                        $num = mysqli_num_rows($select);
                                        if ($num > 0) {
                                            $temp = 0;
                                            while ($data = mysqli_fetch_array($select)) {
                                                ?>
                                                <tr>
                                                    <td><?php echo ++$temp; ?></td>
                                                    <td><?php echo ucwords(strtolower($data['Product_Name'])); ?></td>
                                                    <td><?php echo $data['Item_Description']; ?></td>
                                                    <td><?php echo (!empty($data['Quantity']) && $data['Quantity'] >0)? $data['Quantity']:1 ?></td>
                                                    <td style="text-align: right;"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($data['Amount']*$data['Quantity']), 2) : number_format(($data['Amount']*$data['Quantity']))); ?></td>
                                                    <td style="text-align: center;">
                                                        <input type="button" value="X" onclick="Remove_Item('<?php echo $data['Item_Description']; ?>',<?php echo $data['Cache_ID']; ?>);" style="color: red;">
                                                    </td>
                                                </tr>
                                                <?php
                                                $Grand_Total += $data['Quantity']*$data['Amount'];
                                            }
                                        }
                                        ?>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                             <input type="text" hidden="hidden" id="grand_total_txt" value="<?php echo $Grand_Total; ?>">
                                       
                            <td colspan="2" style="text-align: right;" id="Total_Area">
                                <?php
                                echo '<b>GRAND TOTAL  ' . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Grand_Total, 2) : number_format($Grand_Total)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . '</b>';
                                ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </td>
                            <td style="text-align: right;" id="Payment_Button_Area" colspan="3">
                                <div style="width:100%;text-align:center;">
                                <?php if ($num > 0) { ?>
                                    <div style="float:left;width: 40%">
                                        <?php 
                                            if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment') {
                                                if(($customerType=='CUSTOMER')){
                                             echo " <input type='button' value='Create ePayment Bill22' id='Pay_Via_Mobile' style='width:100%;float:left' name='Pay_Via_Mobile' class='art-button-green' onclick='Pay_Via_Mobile_Function()'>";  
                                            }
                                            }
                                        ?>
                                    </div>
                                    
                                    <div style="float:right;width: 40%">
                                    <?php  if(isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){ ?>
                                    <!--input type="button" name="Make_Payment" id="Make_Payment" value="MAKE PAYMENTS" style='width:100%;float:left' class="art-button-green" onclick="Make_Payments();"-->
                                    <?php } ?>
                                    </div>
                                        <?php } else { ?>
                                    <div style="float:left;width: 40%">
                                    <?php 
                                    
                                    if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && @$_SESSION['configData']['ShowCreateEpaymentBill']=='show') {
                                        if(($customerType=='CUSTOMER')){
                                      echo " <input type='button' value='Create ePayment Bill33' id='Pay_Via_Mobile' style='width:100px'  name='Pay_Via_Mobile' class='art-button-green' onclick='Make_Payment_Warning()'>";  
                                    }
                                    }
                                    ?>
                                    </div>
                                   
                                    <div style="float:right;width:40%">
                                    <?php  if(isset($_SESSION['configData']) && @$_SESSION['configData']['showMakePaymentButton']=='show'){ ?>
                                        <input type="button" name="Make_Payment" id="Make_Payment" style="width:100px" value="MAKE PAYMENTS" class="art-button-green" onclick="Make_Payment_Warning();">
                                      <?php } } ?>
                                    </div>
                                </div> 
                                <div>
                                    <input type="button" name="make_payment" id="make_payment" value="Make Payment" class="art-button-green" onclick="Sponsor_Make_Payment();"> 
                                </div>  
                            </td>
                        </tr>
                    </table> 
        </tr>
    </table>
</td>
</tr>
</table>
</center>
<div id="myDiaglog" style="display:none;">
    
    
</div>
<!-- ePayment pop up windows -->
<div id="ePayment_Window" style="width:50%;" >
    <span id='ePayment_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </span>
</div>

  <script>
        function Pay_Via_Mobile_Function() {
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            var Employee_ID = '<?php echo $Employee_ID; ?>';
            var customerType = '<?php echo $customerType; ?>';
            if (window.XMLHttpRequest) {
                myObjectGetDetails = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetDetails.overrideMimeType('text/xml');
            }

            myObjectGetDetails.onreadystatechange = function () {
                data29 = myObjectGetDetails.responseText;
                if (myObjectGetDetails.readyState == 4) {
                    document.getElementById('ePayment_Area').innerHTML = data29;
                    $("#ePayment_Window").dialog("open");
                }
            }; //specify name of function that will handle server response........

            myObjectGetDetails.open('GET','directCash_Customer_Details.php?Employee_ID=' + Employee_ID + '&Registration_ID=' + Registration_ID+'&customerType='+customerType, true);
            myObjectGetDetails.send();//tbl_reception_items_list_cache
        }
    </script>
    
     <script type="text/javascript">
                
                function Verify_Customer_ePayment_Bill() {
                var Registration_ID = '<?php echo $Registration_ID; ?>';
                var Employee_ID = '<?php echo $Employee_ID; ?>';
                
                if (window.XMLHttpRequest){
                myObjectVerify = new XMLHttpRequest();
                } else if (window.ActiveXObject){
                 myObjectVerify = new ActiveXObject('Micrsoft.XMLHTTP');
                 myObjectVerify.overrideMimeType('text/xml');
                }
                myObjectVerify.onreadystatechange = function (){
                data2912 = myObjectVerify.responseText;
                 if (myObjectVerify.readyState == 4) {
                var feedback = data2912;
                if (feedback == 'yes'){
                 Create_ePayment_Bill();
                } else{
                alert("You are not allowed to create transaction with zero price or zero quantity. Please remove those items to proceed");
                }
                }
                }; //specify name of function that will handle server response........
                myObjectVerify.open('GET', 'Verify_directCash_ePayment_Bill.php?P_Type=Credit_Patient&Registration_ID=' + Registration_ID + '&Employee_ID=' + Employee_ID, true);
                myObjectVerify.send();
                }
    </script>
    
    
    <script type="text/javascript">
        
      function Create_ePayment_Bill(){
        var Payment_Mode = document.getElementById("Payment_Mode").value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Amount = document.getElementById("Amount_Required").value;
        if (Amount <= 0 || Amount == null || Amount == '' || Amount == '0'){
        alert("Process Fail! You can not prepare a bill with zero amount");
        } else{
        if (Payment_Mode != null && Payment_Mode != ''){
        if (Payment_Mode == 'Bank_Payment'){
        var Confirm_Message = confirm("Are you sure you want to create Bank Payment BILL?");
        if (Confirm_Message == true) {
        document.location = 'directCash_Prepare_Customer_Bank_Payment_Transaction.php?src=dircashout&Section=departmental&Registration_ID=' + Registration_ID;
        }
        } else if (Payment_Mode == 'Mobile_Payemnt'){
        var Confirm_Message = confirm("Are you sure you want to create Mobile eBILL?");
                if (Confirm_Message == true) {
        document.location = "#";
        }
        }
        } else{
        document.getElementById("Payment_Mode").focus();
                document.getElementById("Payment_Mode").style = 'border: 3px solid red';
        }
        
        }
        }   
    </script>

    <script>
        $(document).ready(function () {
            $("#ePayment_Window").dialog({autoOpen: false, width: '55%', height: 250, title: 'Create ePayment Bill', modal: true});
        });
    </script>

<script type="text/javascript">
    function Get_Item_Name(Item_Name, Item_ID){
        document.getElementById("Item_Name").value = Item_Name;
        document.getElementById("Item_ID").value = Item_ID;

        document.getElementById("Item_Description").value = '';
        document.getElementById("Amount").value = '0';
        document.getElementById("Quantity").value = '1';
        
         
        /*if(Item_Name == 'Direct Cash'){
            $('#Amount').attr('readonly',false);
            //$('#Amount').addClass('numberonly');
        }else{
             $('#Amount').attr('readonly',true);
            
             // $('#Amount').removeClass('numberonly');
        }*/
        
 
        document.getElementById("Item_Description").focus();
    }
</script>

<script type="text/javascript">
    function Get_Selected_Item() {
        var Item_ID = document.getElementById("Item_ID").value;
        var Item_Description = document.getElementById("Item_Description").value;
        var Amount = document.getElementById("Amount").value;
        var Item_Name = document.getElementById("Item_Name").value;
        var Quantity = document.getElementById("Quantity").value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        
        if (Quantity != null && Quantity != '' && Item_ID != null && Item_ID != '' && Item_Description != null && Item_Description != '' && Amount != null && Amount != '' && Amount != 0) {
            if (window.XMLHttpRequest) {
                myObjectGetItem = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetItem = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetItem.overrideMimeType('text/xml');
            }

            myObjectGetItem.onreadystatechange = function () {
                data29 = myObjectGetItem.responseText;
                if (myObjectGetItem.readyState == 4) {
                    document.getElementById('Transaction_Area').innerHTML = data29;
                    document.getElementById("Item_ID").value = '';
                    document.getElementById("Item_Description").value = '';
                    document.getElementById("Amount").value = '';
                    document.getElementById("Item_Name").value = '';
                    document.getElementById("Quantity").value='';
                    Update_Total();
                    //Update_Payment_Button();
                    //alert("Item added successfully");
                }
            }; //specify name of function that will handle server response........

            myObjectGetItem.open('GET', 'Direct_Cash_Customer_Get_Selected_Item.php?Item_ID=' + Item_ID + '&Item_Description=' + Item_Description + '&Amount=' + Amount + '&Registration_ID=' + Registration_ID + '&Quantity=' + Quantity, true);
            myObjectGetItem.send();
        } else {
            if (Item_Name == null || Item_Name == '') {
                alert("Please select Item first");
            } else {
                if (Item_Description == null || Item_Description == '') {
                    document.getElementById("Item_Description").style = 'border: 3px solid red';
                }

                if (Amount == null || Amount == '' || Amount == 0) {
                    document.getElementById("Amount").style = 'border: 3px solid red';
                }

                if (Quantity == null || Quantity == '') {
                    document.getElementById("Quantity").style = 'border: 3px solid red';
                }
            }
        }
    }
</script>

<script type="text/javascript">
    function Remove_Item(Product_Name, Cache_ID) {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Confirm_Message = confirm("Are you sure you want to remove \n" + Product_Name);

        if (Confirm_Message == true) {
            if (window.XMLHttpRequest) {
                myObjectRemove = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectRemove = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectRemove.overrideMimeType('text/xml');
            }

            myObjectRemove.onreadystatechange = function () {
                data390 = myObjectRemove.responseText;
                if (myObjectRemove.readyState == 4) {
                    document.getElementById('Transaction_Area').innerHTML = data390;
                    Update_Total();
                    //Update_Payment_Button();
                }
            }; //specify name of function that will handle server response........

            myObjectRemove.open('GET', 'Direct_Cash_Remove_Item.php?Cache_ID=' + Cache_ID + '&Registration_ID=' + Registration_ID, true);
            myObjectRemove.send();
        }
    }
</script>

<script type="text/javascript">
    function Update_Total() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectUpdate = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdate = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdate.overrideMimeType('text/xml');
        }

        myObjectUpdate.onreadystatechange = function () {
            data390 = myObjectUpdate.responseText;
            if (myObjectUpdate.readyState == 4) {
                //document.getElementById('Total_Area').innerHTML = data390;
                document.getElementById('Total_Area').innerHTML = "<b>GRAND TOTAL   "+data390+'   </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    document.getElementById('grand_total_txt').value = data390;
            }
        }; //specify name of function that will handle server response........

        myObjectUpdate.open('GET', 'Direct_Cash_Update_Total.php?Registration_ID=' + Registration_ID, true);
        myObjectUpdate.send();
    }
</script>

<script type="text/javascript">
    function Update_Payment_Button() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var customerType = '<?php echo $customerType; ?>';
        if (window.XMLHttpRequest) {
            myObjectUpdateButton = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdateButton = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateButton.overrideMimeType('text/xml');
        }

        myObjectUpdateButton.onreadystatechange = function () {
            data999 = myObjectUpdateButton.responseText;
            if (myObjectUpdateButton.readyState == 4) {
                document.getElementById('Payment_Button_Area').innerHTML = data999;
            }
        }; //specify name of function that will handle server response........

        myObjectUpdateButton.open('GET', 'Direct_Cash_Update_Customer_Payment_Button.php?Registration_ID=' + Registration_ID+'&customerType='+customerType, true);
        myObjectUpdateButton.send();
    }
</script>

<script type="text/javascript">
    function getItemsListFiltered(Item_Name) {
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        if (window.XMLHttpRequest) {
            myObjectGetItem = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetItem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetItem.overrideMimeType('text/xml');
        }

        myObjectGetItem.onreadystatechange = function () {
            data9909 = myObjectGetItem.responseText;
            if (myObjectGetItem.readyState == 4) {
                document.getElementById('Items_Fieldset').innerHTML = data9909;
            }
        }; //specify name of function that will handle server response........

        if (Item_Category_ID != null && Item_Category_ID != '') {
            myObjectGetItem.open('GET', 'Direct_Cash_Get_List_Of_Items.php?Item_Name=' + Item_Name + '&Item_Category_ID=' + Item_Category_ID, true);
        } else {
            myObjectGetItem.open('GET', 'Direct_Cash_Get_List_Of_Items.php?Item_Name=' + Item_Name, true);
        }

        myObjectGetItem.send();
    }
</script>

<script type="text/javascript">
    
           
        function get_terminal_id(terminalid){
        if(terminalid.value!=''){
            $('#terminal_id').val(terminalid.value);
        } else {
            $('#terminal_id').val('');
        }
        
    }
    function get_terminals(trans_type){
        var registration_id = '<?php echo $Registration_ID; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        
         $('#terminal_id').val('');
        var uri = '../epay/get_terminals.php';
        //alert(trans_type.value);
        if(trans_type.value=="Manual"){
            var result=confirm("Are you sure you want to make manual payment?");
            if(result)
          //   document.location = 'Direct_Cash_Make_Payment_Inpatient.php?Registration_ID='+registration_id+'&Sponsor_ID='+Sponsor_ID;
            document.location = 'Direct_Cash_Make_Payment.php?Registration_ID=' + registration_id + '&Sponsor_ID=' + Sponsor_ID+'&manual_offline=manual';
           
        }else{
                $.ajax({
                type: 'GET',
                url: uri,
                data: {trans_type : trans_type.value},
                success: function(data){
                    $("#terminal_name").html(data);
                },
                error: function(){

                }
            });
        }
    }
    
    
    function offline_transaction(amount_required,reg_id){
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var uri = '../epay/outpatientofflinepaymentnewehmsonly.php';
        //alert(trans_type.value);
        var comf = confirm("Are you sure you want to make MANUAL / OFFLINE Payments?");
        if(comf){
            $.ajax({
                type: 'GET',
                url: uri,
                data: {amount_required:amount_required,registration_id:reg_id,Sponsor_ID:Sponsor_ID},
                beforeSend: function (xhr) {
                    $('#offlineProgressStatus').show();
                },
                success: function(data){
                 //   alert("dtat");
                    $("#myDiaglog").dialog({
                        title: 'Manual / Offline Transaction Form',
                        width: '35%',
                        height: 380,
                        modal: true,
                    }).html(data);
                },
                complete: function(){
                    $('#offlineProgressStatus').hide();
                },
                error: function(){
                     $('#offlineProgressStatus').hide();
                }
            });
        } 
    }
    function Make_Payments() {
       

        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
           
        var  amount_required = document.getElementById('grand_total_txt').value;

            //var Confirm_Message = confirm("Are you sure you want to make payment?");
           // if(Confirm_Message == true){
                //alert(amount_required)
                offline_transaction(amount_required,Registration_ID);


      //  var Confirm_Message = confirm("Are you sure you want to make payment?");
       // if (Confirm_Message == true) {
       //     document.location = 'Direct_Cash_Make_Payment.php?Registration_ID=' + Registration_ID + '&Sponsor_ID=' + Sponsor_ID;
       // }

        /*if(window.XMLHttpRequest){
         myObjectMakePayment = new XMLHttpRequest();
         }else if(window.ActiveXObject){
         myObjectMakePayment = new ActiveXObject('Micrsoft.XMLHTTP');
         myObjectMakePayment.overrideMimeType('text/xml');
         }
         
         myObjectMakePayment.onreadystatechange = function (){
         data123 = myObjectMakePayment.responseText;
         if (myObjectMakePayment.readyState == 4) {
         alert(data123)
         }
         }; //specify name of function that will handle server response........
         
         myObjectMakePayment.open('GET','Direct_Cash_Make_Payment.php?Registration_ID='+Registration_ID+'&Sponsor_ID='+Sponsor_ID,true);
         myObjectMakePayment.send();*/
    }
</script>

<script type="text/javascript">
    function Make_Payment_Warning() {
        alert("No Items found")
    }
</script>
<script type="text/javascript" language="javascript">
    function getItemsList(Item_Category_ID) {
        document.getElementById("Search_Value").value = '';
        document.getElementById("Amount").value = '';
        document.getElementById("Item_Name").value = '';
//        document.getElementById("Quantity").value = '';
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        //alert(data);

        myObject.onreadystatechange = function () {
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                //document.getElementById('Approval').readonly = 'readonly';
                document.getElementById('Items_Fieldset').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Get_List_Of_Items.php?src=directcash&Item_Category_ID=' + Item_Category_ID + '&Guarantor_Name=' + Guarantor_Name, true);
        myObject.send();
    }
</script>
<script type="text/javascript">
    function Sponsor_Make_Payment(){
        var customerID="<?=$Registration_ID?>";
        var Supervisor="<?=$Employee_ID?>";
        var cheque_no = $("#cheque_no").val();
        var cheque_attachment = $("#cheque_attachment").val();
        var narration=$('#narration').val();
        var customerType="<?=$customerType?>";
        var extension=cheque_attachment.substr(cheque_attachment.lastIndexOf('.'),cheque_attachment.length);
        if(cheque_attachment.trim()!=='' && cheque_no.trim()===''){
            alert('write the cheque number');
            $("#cheque_no").css('border',' 3px solid red');
            return false;
        }else{
            $("#cheque_no").css('border','');
        }
        if(cheque_attachment.trim()!=='' && extension.toLowerCase() !==".pdf"){
            alert('Attachment Should be in Pdf Format');
            return false;
        }
        if(customerType==='SPONSOR' && cheque_no.trim()===''){
            alert('write the cheque number');
            $("#cheque_no").css('border',' 3px solid red');
            return false;
        }else{
            $("#cheque_no").css('border','');
        }
        if(narration.trim()===''){
            alert('Write Narration');
            $("#narration").css('border',' 3px solid red');
            return false;
        }else{
            $("#narration").css('border','');
        }
        var url="make_sponsor_payments.php"
        $.ajax({
            url:url,
            type:"post",
            data:{customerID:customerID,Supervisor:Supervisor,datas:'yes',cheque_no:cheque_no,customerType:customerType,narration:narration},
            success:function(results){
                    if(!isNaN(results)){
                    print_receipt(results);
                }else{
                    alert(results);
                }
                location.reload();
            }
        });     
        
    }
    function print_receipt(payment_id){
		window.open("other_source_receipt_print.php?Payment_ID="+payment_id+"&Registration_ID=<?=$Registration_ID?>");
    }
    function upload_file(){
    var data2 = new FormData();
    jQuery.each(jQuery('#cheque_attachment')[0].files, function(i, file) {
    data2.append('file[]', file);
});
    console.log("writing");
    $.ajax({
    url: "make_sponsor_payments.php", // Upload Script
    data: data2,
    cache: false,
    contentType: false,
    processData: false,
    method: 'POST',
    type: 'POST', // For jQuery < 1.9
    success: function(results){
        alert(results);
    }
    });
    }
</script>
<script type='text/javascript'>
    function getItemListType() {
        var Item_Category_Name = document.getElementById("Item_Category").value;


        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        alert(Item_Category_Name);
        //document.location = 'Approval_Bill.php?Registration_ID='+Registration_ID+'&Insurance='+Insurance+'&Folio_Number='+Folio_Number;

        myObject.onreadystatechange = function () {
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                document.getElementById('Approval').disabled = 'disabled';
                document.getElementById('Approval_Comment').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Approval_Bill.php?Item_Category_Name=' + Item_Category_Name, true);
        myObject.send();
    }
</script>
<script>
    function Get_Item_Price(Item_ID, Guarantor_Name) {
        var Billing_Type = document.getElementById("Billing_Type").value;
        //alert(Billing_Type);
        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        //document.location = "./Get_Items_Price.php?Item_Name="+Item_Name;
        myObject.onreadystatechange = function () {
            data = myObject.responseText;

            if (myObject.readyState == 4) {
                document.getElementById('Amount').value = data;
                document.getElementById('Quantity').value = 1;
                //alert(data);
            }
        }; //specify name of function that will handle server response........

        myObject.open('GET', 'Get_Items_Price.php?Item_ID=' + Item_ID + '&Guarantor_Name=' + Guarantor_Name + '&Billing_Type=' + Billing_Type, true);
        myObject.send();
    }
</script>

<script src="js/functions.js"></script>
<script src="js/numeral/min/numeral.min.js"></script>
<?php
include("./includes/footer.php");
?>