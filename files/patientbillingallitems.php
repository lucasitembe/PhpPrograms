<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $temp = 0;
    ///////////////////////check for system configuration//////////////////

$configResult = mysqli_query($conn,"SELECT * FROM tbl_config") or die(mysqli_error($conn));

				while($data = mysqli_fetch_assoc($configResult)){
					$configname = $data['configname'];
					$configvalue = $data['configvalue'];
					$_SESSION['configData'][$configname] = strtolower($configvalue);
				}
///////////////////////////////////////////////////////////////////////////////////////
    $Grand_Total = 0;
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
            if ($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            } else {
                @session_start();
                if (!isset($_SESSION['supervisor'])) {
                    header("Location: ./departmentalsupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
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
    }else{
        $Employee_ID = 0;
    }

    if (isset($_SESSION['userinfo']['Employee_Name'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $Employee_Name = 0;
    }
?>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<?php
    if (isset($_SESSION['userinfo'])) {
        if ($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes') {
            ?>
            <a href='paymentsallitems.php?PaymentsAllItems=PaymentsAllItemsThisForm' class='art-button-green'>
                BACK
            </a>
<?php   }
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
    if (isset($_GET['Payment_Cache_ID'])) {
        $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
        $select_Patient = mysqli_query($conn,"select * from tbl_payment_cache pc, tbl_patient_registration pr, tbl_employee emp, tbl_sponsor sp where
    					    pc.Registration_ID = pr.Registration_ID and
    						    pc.Employee_ID = emp.Employee_ID and
    							    pc.Sponsor_ID = sp.Sponsor_ID and
    								    pc.Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);

        if ($no > 0) {
            while ($row = mysqli_fetch_array($select_Patient)) {
                $Registration_ID = $row['Registration_ID'];
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Title = $row['Title'];
                $Patient_Name = ucwords(strtolower($row['Patient_Name']));
                $Sponsor_ID = $row['Sponsor_ID'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Region = $row['Region'];
                $District = $row['District'];
                $Ward = $row['Ward'];
                $Guarantor_Name = $row['Guarantor_Name'];
                $Member_Number = $row['Member_Number'];
                $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
                $Phone_Number = $row['Phone_Number'];
                $Email_Address = $row['Email_Address'];
                $Occupation = $row['Occupation'];
                $Employee_Vote_Number = $row['Employee_Vote_Number'];
                $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
                $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
                $Company = $row['Company'];
                $Employee_ID = $row['Employee_ID'];
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
                $Consultant = $row['Employee_Name'];
                $Folio_Number = $row['Folio_Number'];
                // echo $Ward."  ".$District."  ".$Ward; exit;
            }

            $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";

            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";

        } else {
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Patient_Name = '';
            $Sponsor_ID = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
            $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = '';
            $Consultant = '';
            $Folio_Number = '';
        }
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Guarantor_Name = '';
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
        $Email_Address = '';
        $Occupation = '';
        $Employee_Vote_Number = '';
        $Emergence_Contact_Name = '';
        $Emergence_Contact_Number = '';
        $Company = '';
        $Employee_ID = '';
        $Registration_Date_And_Time = '';
        $Consultant = '';
        $Folio_Number = '';
    }
?>

<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <br/>

    <fieldset>  
        <legend align=right><b>PATIENT PAYMENTS ~ LABORATORY</b></legend>
        <center>
            <table width=100%> 
                <tr> 
                    <td>
                        <table width=100%>
                            <tr>
                                <td width='10%' style="text-align:right;">Patient Name</td>
                                <td width='15%'><input type='text' name='Patient_Name' readonly="readonly" id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                                <td width='11%' style="text-align:right;">Gender</td>
                                <td width='12%'><input type='text' name='Receipt_Number' readonly="readonly" id='Receipt_Number' value='<?php echo $Gender; ?>'></td>
                                <td style="text-align:right;">Patient Age</td>
                                <td><input type='text' name='Patient_Age' id='Patient_Age'  readonly="readonly" value='<?php echo $age; ?>'></td>
                                <td style="text-align:right;">Registration Number</td>
                                <td><input type='text' name='Registration_Number' id='Registration_Number' readonly="readonly" value='<?php echo $Registration_ID; ?>'></td>
                            </tr> 
                            <tr>
                                <td style="text-align:right;">Billing Type</td> 
                                <td>
                                    <select name='Billing_Type' id='Billing_Type'>
                                        <option selected='selected'>Outpatient Cash</option> 
                                    </select>
                                </td>
                                <td style="text-align:right;">Sponsor Name</td>
                                <td><input type='text' name='Guarantor_Name' readonly="readonly" id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                                <td style="text-align:right;" >Claim Form Number</td>
                        <?php
                        $select_claim_status = mysqli_query($conn,"select Claim_Number_Status from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'");
                        $no = mysqli_num_rows($select_claim_status);
                        if ($no > 0) {
                            while ($row = mysqli_fetch_array($select_claim_status)) {
                                $Claim_Number_Status = $row['Claim_Number_Status'];
                            }
                        } else {
                            $Claim_Number_Status = '';
                        }
                        ?>
                                <?php if (strtolower($Claim_Number_Status) == 'mandatory') { ?>
                                    <td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' required='required' placeholder='Claim Form Number'></td>
                                <?php } else { ?>
                                    <td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number'></td>
                                <?php } ?>
                                <td style="text-align:right;">Folio Number</td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' readonly="readonly" value='<?php echo $Folio_Number; ?>'></td>
                            </tr>
                            <tr>
                            </tr>
                            <tr>    
                                <td style="text-align:right;">Member Number</td>
                                <td><input type='text' name='Supervised_By' id='Supervised_By' readonly="readonly" value='<?php echo $Member_Number; ?>'></td>
                                <td style="text-align:right;">Phone Number</td>
                                <td><input type='text' name='Phone_Number' id='Phone_Number' readonly="readonly" value='<?php echo $Phone_Number; ?>'></td>

                                <td style="text-align:right;">Prepared By</td>
                                <td><input type='text' name='Prepared_By' id='Prepared_By' readonly="readonly" value='<?php echo $Employee_Name; ?>'></td>
                                <td style="text-align:right;">Supervised By</td>
                                    <?php
                                    if (isset($_SESSION['supervisor'])) {
                                        if (isset($_SESSION['supervisor']['Session_Master_Priveleges'])) {
                                            if ($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes') {
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
                                <td><input type='text' name='Supervisor_ID' id='Supervisor_ID' readonly="readonly" value='<?php echo $Supervisor; ?>'></td>
                            </tr>
                        </table>
                    </td> 
                </tr>
            </table>
        </center>
    </fieldset>

    <fieldset>   
        <center>
            <table width=100%>
                <tr>
                    <td style='text-align: right;'>
                        <?php
                        if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
                  
                        ?>
                        <input type="button" name="ePayment_Bill" id="ePayment_Bill" value="Create ePayment Bill" class="art-button-green" onclick="Confirm_Create_ePayment_Bill()">
                        <?php }
                         if(isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){
                  
                        ?>
                        <input type="button" name="Make_Payments" id="Make_Payments" value="Make Payments" class="art-button-green" onclick="Validate_make_Payments()">
                         <?php } ?>
                    </td>
                </tr> 
            </table>
        </center>
    </fieldset>

</form>
<fieldset style='overflow-y: scroll; height: 240px;' id='Items_Fieldset_List'>
    <center>
        <table width=100%>
            <tr>
                <td><b>SN</b></td>
                <td><b>ITEM NAME</b></td>
                <td><b>CHECK IN TYPE</b></td>
                <td style="text-align: right;"><b>PRICE</b></td>
                <td style="text-align: right;"><b>DISCOUNT</b></td>
                <td style="text-align: right;"><b>QUANTITY</b></td>
                <td style="text-align: right;"><b>SUB TOTAL</b></td>
                <td width="5%" style="text-align: center;"><b>ACTION</b></td>
            </tr>
            <tr><td colspan="8"><hr></td></tr>
<?php
    $Removed_status = 'no';
    $select_items = mysqli_query($conn,"select itm.Product_Name, ilc.Quantity, ilc.Edited_Quantity, ilc.Price, ilc.Discount, ilc.Payment_Item_Cache_List_ID, ilc.Check_In_Type, ilc.Payment_Item_Cache_List_ID, ilc.ePayment_Status
                                from tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_items itm where
                                ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
                                ilc.Item_ID = itm.Item_ID and
                                (ilc.Status = 'active' or ilc.Status = 'approved') and
                                ilc.Transaction_Type = 'Cash' and
                                pc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                pc.Billing_Type = 'Outpatient Cash' and
                                ilc.ePayment_Status = 'pending' and Seen_On_Allpayments='yes' order by ilc.Check_In_Type") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_items);
    if($no > 0){
        while ($data = mysqli_fetch_array($select_items)) {
            //generate Quantity
            if($data['Edited_Quantity'] != 0){
                $Qty = $data['Edited_Quantity'];
            }else{
                $Qty = $data['Quantity'];
            }
            $Total = (($data['Price'] - $data['Discount']) * $Qty);
            $Grand_Total += $Total;
            if(strtolower($data['ePayment_Status']) == 'removed'){ $Removed_status = 'yes'; }
?>
            <tr>
                <td><?php echo ++$temp; ?></td>
                <td><?php echo $data['Product_Name']; ?></td>
                <td><?php echo $data['Check_In_Type']; ?></td>
                <td style="text-align: right;"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($data['Price'], 2) : number_format($data['Price'])); ?></td>
                <td style="text-align: right;"><?php echo number_format($data['Discount']); ?></td>
                <td style="text-align: right;"><?php if($data['Edited_Quantity'] != 0){ echo $data['Edited_Quantity']; }else{ echo $data['Quantity']; } ?></td>
                <td style="text-align: right;"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Total, 2) : number_format($Total)) ?></td>
                <?php if($no > 1){ ?>
                    <td style="text-align: center;"><input type="button" name="remove" id="remove" value="X" onclick="Remove_Item('<?php echo $data['Payment_Item_Cache_List_ID']; ?>','<?php echo $data['Product_Name']; ?>')"></td>
                <?php }else{ ?>
                    <td style="text-align: center;"><input type="button" name="remove" id="remove" value="X" onclick="Warning_Remove_Item()"></td>
                <?php } ?>
            </tr>            
<?php
        }
    }
?>
            <tr><td colspan="8"><hr></td></tr>
             <input type="text" id="total_txt"hidden="hidden" value="<?php echo $Grand_Total; ?>"/>
            <tr><td colspan="6"><b>GRAND TOTAL</b></td><td style="text-align: right;"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Grand_Total, 2) : number_format($Grand_Total)).'  '.$_SESSION['hospcurrency']['currency_code']; ?></td><td></td></tr>
        </table>
    </center>
</fieldset>
<?php 
    $select_items_removed = mysqli_query($conn,"select itm.Product_Name, ilc.Quantity, ilc.Edited_Quantity, ilc.Price, ilc.Discount, ilc.Payment_Item_Cache_List_ID, ilc.Check_In_Type, ilc.Payment_Item_Cache_List_ID, ilc.ePayment_Status
            from tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_items itm where
            ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
            ilc.Item_ID = itm.Item_ID and
            (ilc.Status = 'active' or ilc.Status = 'approved') and
            ilc.Transaction_Type = 'Cash' and
            pc.Payment_Cache_ID = '$Payment_Cache_ID' and
            pc.Billing_Type = 'Outpatient Cash' and
            ilc.ePayment_Status = 'removed' order by ilc.Check_In_Type") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select_items_removed);
?>
<table width="100%" style="background-color: white;" id="Removed_Area">
    <?php if($num > 0) { ?>
    <tr>
        <td style="text-align: right;">
            <input type="button" value="View Removed Items" class="art-button-green" onclick="View_Removed_Items('<?php echo $Payment_Cache_ID; ?>')">
        </td>
    </tr>
    <?php }else{ ?>
    <tr>
        <td style="text-align: right;">
            &nbsp;
        </td>
    </tr>
    <?php } ?>
</table>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

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

<div id="ePayment_Window_Removed" style="width:50%;" >
    <span id='ePayment_Area_Removed'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </span>
</div>


<script type="text/javascript">
    function Confirm_Create_ePayment_Bill(){
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectConfirm = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectConfirm = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectConfirm.overrideMimeType('text/xml');
        }
        myObjectConfirm.onreadystatechange = function (){
            data28 = myObjectConfirm.responseText;
            if (myObjectConfirm.readyState == 4) {
                var feedback = data28;
                if(feedback == 'yes'){
                    Pay_Via_ePayment_Function(Payment_Cache_ID);
                }else{
                    alert("No item found!");
                }
            }
        }; //specify name of function that will handle server response........
        
        myObjectConfirm.open('GET','Patient_Billing_All_Items_Check_Items.php?Payment_Cache_ID='+Payment_Cache_ID,true); //check if items available
        myObjectConfirm.send();
    }
</script>


<script>
    function View_Removed_Items(Payment_Cache_ID){
        if(window.XMLHttpRequest){
            myObjectGetRemoved = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetRemoved = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetRemoved.overrideMimeType('text/xml');
        }

        myObjectGetRemoved.onreadystatechange = function (){
            data295 = myObjectGetRemoved.responseText;
            if (myObjectGetRemoved.readyState == 4) {
                document.getElementById('ePayment_Area_Removed').innerHTML = data295;
                $("#ePayment_Window_Removed").dialog("open");
            }
        }; //specify name of function that will handle server response........
        
        myObjectGetRemoved.open('GET','ePayment_View_Removed.php?Section=All&Payment_Cache_ID='+Payment_Cache_ID,true);
        myObjectGetRemoved.send();
    }
</script>

<script>
    function Pay_Via_ePayment_Function(Payment_Cache_ID){
        if(window.XMLHttpRequest){
            myObjectGetDetails = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetDetails.overrideMimeType('text/xml');
        }

        myObjectGetDetails.onreadystatechange = function (){
            data29 = myObjectGetDetails.responseText;
            if (myObjectGetDetails.readyState == 4) {
                document.getElementById('ePayment_Area').innerHTML = data29;
                $("#ePayment_Window").dialog("open");
            }
        }; //specify name of function that will handle server response........
        
        myObjectGetDetails.open('GET','Patient_Billing_All_Details.php?Payment_Cache_ID='+Payment_Cache_ID,true);
        myObjectGetDetails.send();
    }
</script>

<script>
   $(document).ready(function(){
      $("#ePayment_Window").dialog({ autoOpen: false, width:'55%',height:250, title:'Create ePayment Bill',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#ePayment_Window_Removed").dialog({ autoOpen: false, width:'65%',height:300, title:'ePayment Bill ~ Removed Items',modal: true});
   });
</script>


<script type="text/javascript">
    function Create_ePayment_Bill(){
        var Payment_Mode = document.getElementById("Payment_Mode").value;
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        var Amount = document.getElementById("Amount_Required").value;
        if(Amount <= 0 || Amount == null || Amount == '' || Amount == '0'){
            alert("You are not allowed to create transaction with zero price or zero quantity. Please remove those items to proceed");
        }else{
            if(Payment_Mode != null && Payment_Mode != ''){
                if(Payment_Mode == 'Bank_Payment'){
                    var Confirm_Message = confirm("Are you sure you want to create Bank Payment BILL?");
                    if (Confirm_Message == true) {
                        document.location = 'Prepare_Bank_Payment_Transaction_All_Items.php?Payment_Cache_ID='+Payment_Cache_ID;
                    }
                }else if(Payment_Mode == 'Mobile_Payemnt'){
                    var Confirm_Message = confirm("Are you sure you want to create Mobile eBILL?");
                    if (Confirm_Message == true) {
                        document.location = "#";
                    }
                }
            }else{
                document.getElementById("Payment_Mode").focus();
                document.getElementById("Payment_Mode").style = 'border: 3px solid red';
            }
        }
    }
</script>

<script type="text/javascript">
    function Warning_Create_ePayment_Bill(){
        alert("You are not allowed to create transaction with zero price or zero quantity. Please remove those items to proceed");
    }
</script>

<script type="text/javascript">
    function Warning_Remove_Item(){
        alert("You are not allowed to remove all items from the same transaction you are trying to prepare. At least one item must remain");
    }
</script>

<script type="text/javascript">
    function Remove_Item(Payment_Item_Cache_List_ID,Product_Name){
        var Con_Message = confirm("Are you sure you want to remove "+Product_Name+"?");
        if (Con_Message == true) {
            if(window.XMLHttpRequest){
                myObjectRemove = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectRemove = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectRemove.overrideMimeType('text/xml');
            }

            myObjectRemove.onreadystatechange = function (){
                data290 = myObjectRemove.responseText;
                if (myObjectRemove.readyState == 4) {
                    document.getElementById('Items_Fieldset_List').innerHTML = data290;
                    Update_Removed_Button('<?php echo $Payment_Cache_ID; ?>');
                }
            }; //specify name of function that will handle server response........
            
            myObjectRemove.open('GET','Removal_Patient_Billing_All_Items.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID,true);
            myObjectRemove.send();
        }
    }
</script>

<script type="text/javascript">
    function Re_Add_Item(Product_Name,Payment_Item_Cache_List_ID){
        var Conf_Message = confirm("Are you sure you want to re-add "+Product_Name+"?");
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        if (Conf_Message == true) {
            if(window.XMLHttpRequest){
                myObjectReAdd = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectReAdd = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectReAdd.overrideMimeType('text/xml');
            }

            myObjectReAdd.onreadystatechange = function (){
                data2900 = myObjectReAdd.responseText;
                if (myObjectReAdd.readyState == 4) {
                    document.getElementById('Items_Fieldset_List').innerHTML = data2900;
                    View_Removed_Items(Payment_Cache_ID);
                    Update_Removed_Button(Payment_Cache_ID);
                }
            }; //specify name of function that will handle server response........
            
            myObjectReAdd.open('GET','Re_Add_Patient_Billing_All_Items.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID,true);
            myObjectReAdd.send();
        }
    }
</script>

<script type="text/javascript">
    function Validate_make_Payments(){
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectMakePayment = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectMakePayment = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectMakePayment.overrideMimeType('text/xml');
        }

        myObjectMakePayment.onreadystatechange = function (){
            data981 = myObjectMakePayment.responseText;
            if (myObjectMakePayment.readyState == 4) {
                var feedback = data981;
                if(feedback == 'yes'){
                    make_Payments(Payment_Cache_ID);
                }else{
                    alert("You are not allowed to make payment with zero price or zero quantity. Please remove those items to proceed");
                }
            }
        }; //specify name of function that will handle server response........
        
        myObjectMakePayment.open('GET','Validate_Make_Payments_All.php?Payment_Cache_ID='+Payment_Cache_ID,true);
        myObjectMakePayment.send();
    }
</script>
<div id="myDiaglog" style="display:none;">
    
    
</div>
<script type="text/javascript">
    
           
        function get_terminal_id(terminalid){
        if(terminalid.value!=''){
            $('#terminal_id').val(terminalid.value);
        } else {
            $('#terminal_id').val('');
        }
        
    }
    function get_terminals(trans_type){
         var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        
         $('#terminal_id').val('');
        var uri = '../epay/get_terminals.php';
        //alert(trans_type.value);
        if(trans_type.value=="Manual"){
            var result=confirm("Are you sure you want to make manual payment?");
            if(result){
                document.location = 'Make_Payments_All_Items.php?Payment_Cache_ID='+Payment_Cache_ID+'&manual_offline=manual'; 
            }
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
               
        var uri = '../epay/patientbillingallitemsOfflinePayment.php';
        
                 var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
                                
        //alert(trans_type.value);
        var comf = confirm("Are you sure you want to make MANUAL / OFFLINE Payments?");
        if(comf){
            
            $.ajax({
                type: 'GET',
                url: uri,
                data: {amount_required:amount_required,registration_id:reg_id,Payment_Cache_ID:Payment_Cache_ID},
                beforeSend: function (xhr) {
                    $('#offlineProgressStatus').show();
                },
                success: function(data){
                    //alert("dtat");
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
</script>
<script type="text/javascript">
    function make_Payments(Payment_Cache_ID){
      //  var Confirm_Message = confirm("Are you sure you want to make payment? Click OK to proceed or Cancel to stop process?");
     //   if (Confirm_Message == true) {
            var reg_id='<?php echo  $Registration_ID; ?>';
            var amount_required=document.getElementById("total_txt").value;
          offline_transaction(amount_required,reg_id);  
        //}
    }
</script>

<script type="text/javascript">
    function Update_Removed_Button(Payment_Cache_ID){
        if(window.XMLHttpRequest){
            myObjectRemoved = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRemoved = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRemoved.overrideMimeType('text/xml');
        }

        myObjectRemoved.onreadystatechange = function (){
            data2943 = myObjectRemoved.responseText;
            if (myObjectRemoved.readyState == 4) {
                document.getElementById('Removed_Area').innerHTML = data2943;
            }
        }; //specify name of function that will handle server response........
        
        myObjectRemoved.open('GET','Update_Removed_Button.php?Payment_Cache_ID='+Payment_Cache_ID,true);
        myObjectRemoved.send();
    }
</script>
<?php
    include("./includes/footer.php");
?>