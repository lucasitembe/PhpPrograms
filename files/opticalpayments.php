<?php
include("./includes/header.php");
include("./includes/connection.php");

$location = '';
if (isset($_GET['location']) && $_GET['location'] == 'otherdepartment') {
    $location = 'location=otherdepartment&';
}

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
        if ($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

   //get employee name
   if(isset($_SESSION['userinfo']['Employee_Name'])){
      $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
   }else{
      $Employee_Name = '';
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
    //get today date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }
?>

<a href='opticalpaymentlist.php?OpticalPaymentList=OpticalPaymentListThisPage' class='art-button-green'>BACK</a>

<!--Getting employee name -->
<?php
    if (isset($_SESSION['userinfo']['Employee_Name'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
        $Employee_ID= $_SESSION['userinfo']['Employee_ID'];
    } else {
        $Employee_Name = 'Unknown Employee';
    }

    $consultation_ID = 0;
?>


<?php
//select patient information
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"select * from tbl_patient_registration pr, tbl_sponsor sp where
				       sp.Sponsor_ID = pr.Sponsor_ID and
					  pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = $row['Patient_Name'];
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
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];




            // echo $Ward."  ".$District."  ".$Ward; exit;
        }

        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        // if($age == 0){

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
}

//get sponsor details
$select_dets = mysqli_query($conn,"select Claim_Number_Status, Folio_Number_Status from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
$nm = mysqli_num_rows($select_dets);
if ($nm > 0) {
    while ($data = mysqli_fetch_array($select_dets)) {
        $Claim_Number_Status = strtolower($data['Claim_Number_Status']);
        $Folio_Number_Status = strtolower($data['Folio_Number_Status']);
    }
} else {
    $Claim_Number_Status = '';
    $Folio_Number_Status = '';
}
?>

<?php
//get the last folio number
if (isset($_SESSION['systeminfo']['Direct_departmental_payments']) && strtolower($_SESSION['systeminfo']['Direct_departmental_payments']) != 'yes') {
    $get_folio = mysqli_query($conn,"select Folio_Number, Claim_Form_Number from tbl_patient_payments where
                                    Registration_ID = '$Registration_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
    $numrow = mysqli_num_rows($get_folio);
    if ($numrow > 0) {
        while ($data = mysqli_fetch_array($get_folio)) {
            $Folio_Number = $data['Folio_Number'];
            $Claim_Form_Number = $data['Claim_Form_Number'];
        }
    } else {
        $Folio_Number = '';
        $Claim_Form_Number = '';
    }
}
?>


<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
?>

<?php
$Folio_Number = '';
$Claim_Form_Number = '';
if (strtolower($Guarantor_Name) != 'cash') {
    //get the last folio number if available
    $get_folio = mysqli_query($conn,"select Folio_Number, Claim_Form_Number from tbl_patient_payments where
                                    Registration_ID = '$Registration_ID' and
                                    Sponsor_ID = '$Sponsor_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
    $numrow = mysqli_num_rows($get_folio);
    if ($numrow > 0) {
        while ($data = mysqli_fetch_array($get_folio)) {
            $Folio_Number = $data['Folio_Number'];
            $Claim_Form_Number = $data['Claim_Form_Number'];
        }
    } else {
        $Folio_Number = '';
        $Claim_Form_Number = '';
    }
}
?>

<!--get sub department name-->
<br/><br/>
<fieldset>  
    <legend align="right"><b>Revenue Center ~ Optical Payments</b></legend>
    <center>
        <table width=100%>
            <tr>
                <td width='10%' style='text-align: right;'>Patient Name</td>
                <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                <td style='text-align: right;'>Registration Number</td>
                <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td>
                <td style='text-align: right;'>Member Number</td>
                <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td>
                <td width='11%' style='text-align: right;'>Gender</td>
                <td width='12%'><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Gender; ?>'></td>
            </tr> 
            <tr>
            <?php
                if(isset($_GET['Consultant_ID'])){ $Doctor_ID = $_GET['Consultant_ID']; }else{ $Doctor_ID = 0; }
                $get_consultant = mysqli_query($conn,"select Billing_Type, Item_Cache_ID from tbl_optical_items_list_cache where
                                                Registration_ID = '$Registration_ID' and
                                                Consultant_ID = '$Doctor_ID' order by Item_Cache_ID desc limit 1") or die(mysqli_error($conn));
                $nmz = mysqli_num_rows($get_consultant);
                if($nmz > 0){
                    while($dts = mysqli_fetch_array($get_consultant)){
                        $Billing_Type = $dts['Billing_Type'];
                        $Item_Cache_ID = $dts['Item_Cache_ID'];
                    }
                }else{
                    $Billing_Type = 'Outpatient Cash';
                    $Item_Cache_ID = '0';
                }
            ?>
                <td style='text-align: right;'>Billing Type</td> 
                <td id="Billing_Type_Area">
                    <select name='Billing_Type' id='Billing_Type'>
                        <option selected='selected'><?php echo $Billing_Type; ?></option>
                    </select>
                    </td>
    				<td width='12%' style='text-align: right;'>Card Expire Date</td>
                    <td width='15%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td> 
                    <td style='text-align: right;'>Phone Number</td>
                    <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>
                    <td style='text-align: right;'>Folio Number</td>
                    <td>
                <?php if(isset($_SESSION['systeminfo']['Direct_departmental_payments']) && strtolower($_SESSION['systeminfo']['Direct_departmental_payments']) != 'yes'){ ?>
                        <input type='text' name='Folio_Number' id='Folio_Number' autocomplete='off' placeholder='Folio Number' readonly="readonly" value="<?php echo $Folio_Number; ?>">
                <?php }else{ ?>
                        <input type='text' name='Folio_Number' id='Folio_Number' autocomplete='off' placeholder='Folio Number' readonly="readonly" value="<?php echo $Folio_Number; ?>">
                <?php } ?>
                    </td>
				</tr>
                <tr>
                    <td style='text-align: right;'>Type Of Check In</td>
                    <td>  
                    <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required'>
                        <option>Optical</option>
                    </select>
                </td>
                <td style='text-align: right;'>Patient Age</td>
                <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                <td style='text-align: right;'>Registered Date</td>
                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>
                <td style='text-align: right;'>Claim Form Number</td>
                <td>
                    <?php if (isset($_SESSION['systeminfo']['Direct_departmental_payments']) && strtolower($_SESSION['systeminfo']['Direct_departmental_payments']) != 'yes') { ?>
                                <input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number' autocomplete='off' readonly="readonly" value="<?php echo $Claim_Form_Number; ?>">
                    <?php } else { ?>
                                <input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number' autocomplete='off' readonly="readonly" value="<?php echo $Claim_Form_Number; ?>">
                    <?php } ?>
                </td>
            </tr>
            <tr> 
                <td style='text-align: right;'>Consultant</td>
                <td>
                <?php
                    //get consultant
                    if(isset($_GET['Consultant_ID'])){ $Doctor_ID = $_GET['Consultant_ID']; }else{ $Doctor_ID = 0; }
                    $get_consultant = mysqli_query($conn,"select emp.Employee_Name, emp.Employee_ID
                                                    from tbl_optical_items_list_cache ic, tbl_employee emp where
                                                    ic.Registration_ID = '$Registration_ID' and
                                                    emp.Employee_ID = ic.Consultant_ID and
                                                    ic.Consultant_ID = '$Doctor_ID' limit 1") or die(mysqli_error($conn));
                    $nms = mysqli_num_rows($get_consultant);
                    if($nms > 0){
                        while ($dt = mysqli_fetch_array($get_consultant)) {
                            $Consultant_ID = $dt['Employee_ID'];
                            $Consultant_Name = $dt['Employee_Name'];
                        }
                    }else{
                        $Consultant_ID = '';
                        $Consultant_Name = '';
                    }
                ?>
                    <select name='Consultant_ID' id='Consultant_ID'>
                        <option selected='selected' value="<?php echo $Consultant_ID ?>"><?php echo $Consultant_Name; ?></option>
                    </select>
                </td>
                <td style='text-align: right;'>Sponsor Name</td>
                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>

            </tr>
        </table>
    </center>
</fieldset>

<fieldset>
    <table width="100%">
        <tr>
            <td style='text-align: right;'>
                <button class="art-button-green" type="button" onclick="Make_Payment();">MAKE PAYMENT</button>
            </td>
        </tr>
    </table>
</fieldset>

<fieldset>   
    <table width=100%>
		<tr>
		    <td>
                <fieldset id="Picked_Items_Fieldset"  style='overflow-y: scroll; height: 190px;'>
                    
                    <table width=100%>
                        <tr>
                            <td><b>SN</b></td>
                            <td><b>ITEM NAME</b></td>
                            <td width="10%"><b>CHECK IN TYPE</b></td>
                            <td style="text-align: right;" width="12%"><b>PRICE</b></td>
                            <td style="text-align: right;" width="12%"><b>DISCOUNT</b></td>
                            <td style="text-align: right;" width="12%"><b>QUANTITY</b></td>
                            <td style="text-align: right;" width="12%"><b>SUB TOTAL</b></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td colspan="8"><hr></td></tr>
                        <?php
                        $sql_select_payment_cache_id="SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE Registration_ID='$Registration_ID' ORDER BY Payment_Cache_ID DESC";
                        $sql_select_payment_cache_id_result=mysqli_query($conn,$sql_select_payment_cache_id) or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_payment_cache_id_result)){
                                    $payment_cache_id_row=mysqli_fetch_assoc($sql_select_payment_cache_id_result);
                                    $Payment_Cache_ID=$payment_cache_id_row['Payment_Cache_ID'];
                                }
                        $select_items = mysqli_query($conn,"select itm.Product_Name,ilc.Transaction_type, ilc.Quantity, ilc.Edited_Quantity, ilc.Price, ilc.Discount, ilc.Payment_Item_Cache_List_ID, ilc.Check_In_Type, ilc.Payment_Item_Cache_List_ID, ilc.ePayment_Status
                                                from tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_items itm where
                                                ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
                                                ilc.Item_ID = itm.Item_ID and
                                                ilc.Status = 'active' and

                                                ilc.Check_In_Type = 'Optical' and
                                                pc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                                pc.Registration_ID = '$Registration_ID' and
                                                ilc.ePayment_Status = 'pending' order by ilc.Check_In_Type") or die(mysqli_error($conn));
                        $no = mysqli_num_rows($select_items);
                        if ($no > 0) {
                            while ($data = mysqli_fetch_array($select_items)) {
                                //generate Quantity
                                if ($data['Edited_Quantity'] != 0) {
                                    $Qty = $data['Edited_Quantity'];
                                } else {
                                    $Qty = $data['Quantity'];
                                }
                                $Total = (($data['Price'] - $data['Discount']) * $Qty);
                                $Grand_Total += $Total;
                                $Payment_Item_Cache_List_ID=$data['Payment_Item_Cache_List_ID']; 
                                $Transaction_type=$data['Transaction_type']; 
                                $count_rows++;
                                ?>
                                <tr>
                                    <td><?php echo ++$temp; ?></td>
                                    <td><?php echo $data['Product_Name']; ?></td>
                                    <td><?php echo $data['Check_In_Type']; ?></td>
                                    <td style="text-align: right;"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($data['Price']) : number_format($data['Price'])); ?></td>
                                     <td style="text-align: right;" id="td1<?= $Payment_Item_Cache_List_ID ?>"><?php
                                        if($Transaction_type=="Cash"){
                                            ?>
                                         <input type="text"style="width:50%"readonly="readonly" onkeyup="update_discount_price('<?php echo $Payment_Item_Cache_List_ID ?>')" value="<?php echo $data['Discount']; ?>" id="<?= $Payment_Item_Cache_List_ID  ?>"/>
                                                <?php
                                        }else{
                                         echo number_format($data['Discount']);
                                        }
                                    ?></td>
                                      <td style="text-align: right;display: none" id="td2<?= $Payment_Item_Cache_List_ID ?>"><?php

                                         echo number_format($data['Discount']);

                                    ?></td>
                                     <td style="text-align: right;"><?php
                                        if ($data['Edited_Quantity'] != 0) {
                                            echo $data['Edited_Quantity'];
                                        } else {
                                            echo $data['Quantity'];
                                        }
                                        ?></td>
                                    <td style="text-align: right;" id="sub_total<?= $Payment_Item_Cache_List_ID ?>"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Total) : number_format($Total)) ?></td>
                                     <input type="text"class="sub_t_txt"hidden="hidden" value="<?= $Total ?>" id="sub_total_txt<?= $Payment_Item_Cache_List_ID ?>">

                                    <?php
                                    if ($no == 1) {
                                       // echo '<td>&nbsp;</td>';
                                    } else {
                                        //echo '<td><button type="button" class="removeItemFromCache art-button" onclick="removeitem(' . $data["Payment_Item_Cache_List_ID"] . ')">Remove</button></td>';
                                    }
                                     ?>
                                   
                                    <?php
                                }
                            }
                            ?>
                        </tr>  
                        <tr><td colspan="8"><hr></td></tr><input type="text"hidden="hidden" id="grand_total_txt" value="<?= $Grand_Total?>"/>
                        <tr><td colspan="6"><b>TOTAL</b></td><td style="text-align: right;" id="grand_total_td"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Grand_Total) : number_format($Grand_Total)); ?></td></tr>
                    </table>

                </fieldset>
            </td>
        </tr>
        <tr>
            <td style='text-align: right; width: 70%;' id='Total_Area'>
                <h4>Total : <?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Grand_Total, 2) : number_format($Grand_Total)).'  '.$_SESSION['hospcurrency']['currency_code']; ?></h4>
            </td>
        </tr>
    </table>
</fieldset>
<?php
    //Registration_ID&Consultant_ID
    if(isset($_GET['Registration_ID'])){
        $$Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }

    if(isset($Consultant_ID)){
        $Consultant_ID = $_GET['Consultant_ID'];
    }else{
        $Consultant_ID = 0;
    }
?>
<script type="text/javascript">
    function Make_Payment(){
        var sms = confirm("Are you sure you want to make transaction?");
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        if(consultation_ID == 0){
            consultation_ID = null;
        }
        var Consultant_ID = '<?php echo $Consultant_ID; ?>';
        if(sms == true){
            document.location = "Process_Optical_Transaction_Cash.php?Registration_ID="+Registration_ID+'&Consultant_ID='+Consultant_ID+'&consultation_ID='+consultation_ID;
        }
    }
</script>

<?php
    include("./includes/footer.php");
?>