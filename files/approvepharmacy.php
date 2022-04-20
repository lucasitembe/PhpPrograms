<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $temp = 0;
    $Grand_Total = 0;
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_GET['Section'])){
        $Section_Link = "Section=".$_GET['Section']."&";
        $Section = $_GET['Section'];
    }else{
        $Section_Link = "";
        $Section = "";
    }

    if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
            if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes' && $_SESSION['userinfo']['Msamaha_Works'] != 'yes'){
                header("Location: ./index.php?InvalidPrivilege=yes");
            } else {
                @session_start();
                if (!isset($_SESSION['supervisor'])) {
                    header("Location: ./supervisorauthentication.php?{$Section_Link}InvalidSupervisorAuthentication=yes");
                }
            }
        } else {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['systeminfo']['Allow_Cashier_To_Approve_Pharmaceutical'])){
        if(strtolower($_SESSION['systeminfo']['Allow_Cashier_To_Approve_Pharmaceutical']) != 'yes'){
          //  header("Location: ./credittransactions.php?CreditTransactions=CreditTransactionsThisForm");
        }
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

    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
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
    echo "<a href='pharmacycredits.php?".$Section_Link."PharmacyCredits=PharmacyCreditsThisPage' class='art-button-green'>BACK</a>";
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
                $Billing_Type = $row['Billing_Type'];
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
            $Billing_Type = '';
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
        $Billing_Type = '';
    }

    //generate billing type
    $B_Type = strtolower(substr($Billing_Type, 0, 3));
    /*if($B_Type == 'inp'){
        $Billing_Type = 'Inpatient Credit';
    }else{
        $Billing_Type = 'Outpatient Credit';
    }*/
?>
    <br/>
    <br/>
    <fieldset>
        <legend align=right><b>PHARMACY APPROVAL</b></legend>
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
                                                    <?php
                        $select_bill_type = mysqli_query($conn,
                                "select payment_method from tbl_sponsor where Sponsor_ID='$Sponsor_ID' ") or die(mysqli_error($conn));

                        $no_of_items = mysqli_num_rows($select_bill_type);
                        if ($no_of_items > 0) {
                            while ($data = mysqli_fetch_array($select_bill_type)) {
                                $payment_method = $data['payment_method'];
                            }
                            if ($payment_method == 'cash') {
                                echo "<option selected='selected'>Outpatient Cash</option>";
                            } else {
                                echo "<option selected='selected'>Outpatient Credit</option> 
                                      <option>Outpatient Cash</option>";
    				}
                        }
    				       ?>
                                    </select>
                                </td>
                                <td style="text-align:right;">Sponsor Name</td>
                                <td><input type='text' name='Guarantor_Name' readonly="readonly" id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>' title="<?php echo $Guarantor_Name; ?>"></td>
                            <?php
                                //get consultation id
                                $select = mysqli_query($conn,"select consultation_id from tbl_payment_cache where
                                                        Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
                                $num = mysqli_num_rows($select);
                                if($num > 0){
                                    while ($data = mysqli_fetch_array($select)) {
                                        $consultation_id = $data['consultation_id'];
                                    }
                                }else{
                                    $consultation_id = 0;
                                }

                                //get required details
                                $select = mysqli_query($conn,"select Folio_Number, Check_In_ID, Patient_Bill_ID, Claim_Form_Number from 
                                                        tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_consultation c where
                                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                        ppl.Patient_Payment_Item_List_ID = c.Patient_Payment_Item_List_ID and
                                                        c.consultation_ID = '$consultation_id'") or die(mysqli_error($conn));
                                $number = mysqli_num_rows($select);
                                if($number > 0){
                                    while ($row = mysqli_fetch_array($select)) {
                                        $Folio_Number = $row['Folio_Number'];
                                        $Check_In_ID = $row['Check_In_ID'];
                                        $Patient_Bill_ID = $row['Patient_Bill_ID'];
                                        $Claim_Form_Number = $row['Claim_Form_Number'];
                                    }
                                }else{
                                    $Folio_Number = '';
                                    $Check_In_ID = '';
                                    $Patient_Bill_ID = '';
                                    $Claim_Form_Number = '';
                                }
                            ?>
                                <td style="text-align:right;" >Claim Form Number</td>
                                <td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number' value="<?php echo $Claim_Form_Number; ?>" readonly="readonly"></td>
                                <td style="text-align:right;">Folio Number</td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' readonly="readonly" value='<?php echo $Folio_Number; ?>' value="<?php echo $Folio_Number; ?>" readonly="readonly"></td>
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
                        
                            //only for excempted sponsor 
                        $sql_select_excempted_sponsor="SELECT Exemption FROM tbl_sponsor WHERE Exemption='yes' AND Sponsor_ID='$Sponsor_ID'";
                        $sql_select_excempted_sponsor_result=mysqli_query($conn,$sql_select_excempted_sponsor) or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_excempted_sponsor_result)>0){
                            /////////////////////////////////////////////////////////////////////////////////////////////////
                                    ?>
                              <input type="button" name="Make_Payments" id="Make_Payments" value="APPROVE PHARMACEUTICAL" class="art-button-green" onclick="excempted_make_Payments()">
                               <?php 
                            /////////////////////////////////////////////////////////////////////////////////////////
                        }else{
                           ?>
                              <input type="button" name="Make_Payments" id="Make_Payments" value="APPROVE PHARMACEUTICAL" class="art-button-green" onclick="Validate_make_Payments()">
                               <?php 
                        }
                        ?>
                   </td>
                </tr> 
            </table>
        </center>
    </fieldset>
<fieldset style='overflow-y: scroll; height: 200px;' id='Items_Fieldset_List'>
    <center>
        <table width=100%>
            <tr>
                <td><b>SN</b></td>
                <td><b>ITEM NAME</b></td>
                <td width="13%"><b>LOCATION</b></td>
                <td style="text-align: right;" width="12%"><b>PRICE</b></td>
                <td style="text-align: right;" width="12%"><b>PRICE DISCOUNT</b></td>
                <td style="text-align: right;" width="12%"><b>QUANTITY</b></td>
                <td style="text-align: right;" width="12%"><b>SUB TOTAL</b></td>
                <td style="text-align: right;" width="12%"><b>TRANSACTION TYPE</b></td>
            </tr>
            <tr><td colspan="8"><hr></td></tr>
<?php

 ///CHAKE FOR PRIVILAGES
                 
                                    $Given_Username=$_SESSION['userinfo']['Given_Username'];
                                    $sql_check_for_bakup_privilage="SELECT change_bill_type_transaction_type_for_excempted FROM tbl_privileges WHERE change_bill_type_transaction_type_for_excempted='yes' AND Given_Username='$Given_Username'";
                                    $sql_check_for_bakup_privilage_result=mysqli_query($conn, $sql_check_for_bakup_privilage) or die(mysqli_error($conn));
                                     if(mysqli_num_rows($sql_check_for_bakup_privilage_result)>0){
                                     
                                         $can_change_transaction_type='yes';
                                     }else{
                                         $can_change_transaction_type='no';
                                     }
            
                                    $Given_Username=$_SESSION['userinfo']['Given_Username'];
                                    $sql_check_for_bakup_privilage="SELECT discount_for_excempted_sponsor FROM tbl_privileges WHERE discount_for_excempted_sponsor='yes' AND Given_Username='$Given_Username'";
                                    $sql_check_for_bakup_privilage_result=mysqli_query($conn, $sql_check_for_bakup_privilage) or die(mysqli_error($conn));
                                     if(mysqli_num_rows($sql_check_for_bakup_privilage_result)>0){
                                     
                                         $discount='';
                                     }else{
                                         $discount="readonly='readonly'";
                                     }
            


    $select_items = mysqli_query($conn,"select sd.Sub_Department_Name,ilc.Transaction_type, itm.Product_Name, ilc.Quantity, ilc.Edited_Quantity, ilc.Price, ilc.Discount, ilc.Payment_Item_Cache_List_ID, ilc.Check_In_Type, ilc.Payment_Item_Cache_List_ID, ilc.ePayment_Status
                                    from tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_items itm, tbl_sub_department sd where
                                    ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
                                    ilc.Sub_Department_ID = sd.Sub_Department_ID and
                                    ilc.Item_ID = itm.Item_ID and
                                    (ilc.Status = 'active' OR ilc.Status = 'approved') and
                                    
                                    ilc.Check_In_Type = 'Pharmacy' and
                                    pc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                    pc.Registration_ID = '$Registration_ID' and
                                    ilc.ePayment_Status = 'pending' order by ilc.Check_In_Type") or die(mysqli_error($conn));
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
            $Payment_Item_Cache_List_ID=$data['Payment_Item_Cache_List_ID']; 
            $Transaction_type=$data['Transaction_type']; 
            $count_rows++;
?>
            <tr>
                <td><?php echo ++$temp; ?></td>
                <td><?php echo $data['Product_Name']; ?></td>
                <td><?php echo $data['Sub_Department_Name']; ?></td>
                <td style="text-align: right;"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($data['Price']) : number_format($data['Price'])); ?></td>
                <td style="text-align: right;" id="td1<?= $Payment_Item_Cache_List_ID ?>"><?php
                            if($Transaction_type=="Cash"){
                                ?>
                            <input type="text"style="width:50%"<?= $discount ?> onkeyup="update_discount_price('<?php echo $Payment_Item_Cache_List_ID ?>')" value="<?php echo $data['Discount']; ?>" id="<?= $Payment_Item_Cache_List_ID  ?>"/>
                                    <?php
                            }else{
                             echo number_format($data['Discount']);
                            }
                        ?></td>
                          <td style="text-align: right;display: none" id="td2<?= $Payment_Item_Cache_List_ID ?>"><?php
                            
                             echo number_format($data['Discount']);
                            
                        ?></td>
                <td style="text-align: right;"><?php if($data['Edited_Quantity'] != 0){ echo $data['Edited_Quantity']; }else{ echo $data['Quantity']; } ?></td>
                <td style="text-align: right;" id="sub_total<?= $Payment_Item_Cache_List_ID ?>"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Total) : number_format($Total)) ?></td>
                <input type="text"class="sub_t_txt"hidden="hidden" value="<?= $Total ?>" id="sub_total_txt<?= $Payment_Item_Cache_List_ID ?>">
                <td>
                    <select style="float:right"onchange="change_bill_type('<?= $Payment_Item_Cache_List_ID ?>')" id="billtype<?= $Payment_Item_Cache_List_ID ?>">
                        <?php if($Billing_Type=="Inpatient Credit"||$Billing_Type=="Outpatient Credit"){
                                    ?>
                                        <option selected="selected">Credit</option>
                                        <?php
                                }else{ ?>
                                <option <?php if($Transaction_type=="Cash"){echo 'selected="selected"'; } ?>>Cash</option>
                                <?php if($can_change_transaction_type=='yes'){?>
                                <option <?php if($Transaction_type=="Credit"){echo 'selected="selected"'; } ?>>Credit</option>
                                <?php } } ?>
                    </select>
                </td>
            </tr>            
<?php
        }
    }
?>
            <tr><td colspan="8"><hr></td></tr><input type="text"hidden="hidden" id="grand_total_txt" value="<?= $Grand_Total?>"/>
            <tr><td colspan="6" style="text-align: right;"><b>TOTAL</b></td><td style="text-align: right;"id="grand_total_td"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Grand_Total) : number_format($Grand_Total)); ?></td></tr>
        </table>
    </center>
</fieldset>
<?php
    //calculate paid transactions
    $Total_Spent = 0;
    $slect = mysqli_query($conn,"select Price, Quantity, Discount from
                            tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                            pp.Check_In_ID = '$Check_In_ID' and
                            pp.Folio_Number = '$Folio_Number' and
                            pp.Patient_Bill_ID = '$Patient_Bill_ID' and
                            pp.Transaction_status <> 'cancelled' and
                            (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit' or (pp.Billing_Type = 'Inpatient Cash' and pp.payment_type = 'pre')) and
                            pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($slect);
    if($nm > 0){
        while ($dts = mysqli_fetch_array($slect)) {
            $Total_Spent += (($dts['Price'] - $dts['Discount']) * $dts['Quantity']);
        }
    }
?>
<fieldset>
    <table width="100%">
        <tr>
            <td style="text-align: left;">
                <input type="text" id="amount_spent_txt" hidden="hidden" value="<?= $Total_Spent ?>"/>
                <b>AMOUNT SPENT : <?php echo number_format($Total_Spent); ?></b>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="button" name="Previous_Transactions" id="Previous_Transactions" value="PREVIEW PREVIOUS TRANSACTIONS" class="art-button-green" onclick="Preview_Previous_Transaction(<?php echo $Registration_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Patient_Bill_ID; ?>)">
            </td>
            <td style="text-align: right;"id="all_grand_total_td">
                <b>GRAND TOTAL : <?php echo number_format($Grand_Total + $Total_Spent); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
    </table>
</fieldset>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

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

<div id="Preview_Previous_Transaction" style="width:50%;" >
    <span id='Preview_Previous_Transaction_Area'>

    </span>
</div>

<div id="Fail_Transaction">
    <center>Process Fail! Please try again</center>
</div>
<script>
    function change_bill_type(Payment_Item_Cache_List_ID){
        var td1=$("#td1"+Payment_Item_Cache_List_ID);
        var td2=$("#td2"+Payment_Item_Cache_List_ID);
        var Transaction_type=$("#billtype"+Payment_Item_Cache_List_ID).val();
        if(Transaction_type=="Cash"){
            td1.show();
            td2.hide();
        }else if(Transaction_type=="Credit"){
            td2.show();
            td1.hide();
        }
         $.ajax({
           type:"GET",
           url:"change_Transaction_type_for_approval_lab_rad.php",
           data:{Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,Transaction_type:Transaction_type},
           success:function(data){
              // alert(data)
           },
           complete:function(){
               // alert("complete")
           },
           error:function(error,errormessage,throunMessgae){
               alert(error.errormessage+error.throunMessgae)
           }
       });
    }
    function update_discount_price(Payment_Item_Cache_List_ID){
       var discount_price=$("#"+Payment_Item_Cache_List_ID).val();
       var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
       var Registration_ID='<?= $Registration_ID ?>';
       var Billing_Type='<?= $Billing_Type ?>';
       var number_of_rows=$("#number_of_rows").val();
       var grand_total=0;
       $.ajax({
           type:"GET",
           url:"change_discount_price_for_approval_phamacy.php",
           data:{discount_price:discount_price,Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,Payment_Cache_ID:Payment_Cache_ID,Registration_ID:Registration_ID,Billing_Type:Billing_Type},
           success:function(data){
               //grand_total_td
              //var grand_total_txt=$("#grand_total_txt").val();
             // grand_total_txt=grand_total_txt-discount_price;
               //alert(number_of_rows)
               $("#sub_total"+Payment_Item_Cache_List_ID).html(data);
               $("#sub_total_txt"+Payment_Item_Cache_List_ID).val(data);
               $(".sub_t_txt").each(function() {
                    //alert($(this).val());
                    grand_total=grand_total+parseInt($(this).val());
                   //alert(grand_total)
                });
               $("#grand_total_td").html("<b>"+grand_total+"</b>");
             var amount_spent_txt= $("#amount_spent_txt").val();
             var all_grand_total_td=parseInt(amount_spent_txt)+parseInt(grand_total);
               $("#all_grand_total_td").html("<b>GRAND TOTAL :"+all_grand_total_td+"</b>"); 
           },
           complete:function(){
               // alert("complete")
           },
           error:function(error,errormessage){
               alert(error.errormessage)
           }
       });
    }
    function excempted_make_Payments(){
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Billing_Type = '<?php echo $Billing_Type; ?>';
        var Section_Link = '<?php echo $Section_Link; ?>';
         var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        var Confirm_Message = confirm("Are you sure you want to approve selected transaction? \nClick OK to proceed or Cancel to stop process?");
        if (Confirm_Message == true) {
            document.location = 'Approve_Selected_Transaction_Phamacy_Excempted.php?' + Section_Link + 'Payment_Cache_ID=' + Payment_Cache_ID + '&Registration_ID=' + Registration_ID + '&Billing_Type=' + Billing_Type;
        }
    }
</script>
<script>
    $(document).ready(function(){
        $("#ePayment_Window").dialog({ autoOpen: false, width:'55%',height:250, title:'Create ePayment Bill',modal: true});
        $("#ePayment_Window_Removed").dialog({ autoOpen: false, width:'65%',height:300, title:'ePayment Bill ~ Removed Items',modal: true});
        $("#Preview_Previous_Transaction").dialog({ autoOpen: false, width:'80%',height:480, title:'PREVIOUS TRANSACTIONS',modal: true});
        $("#Fail_Transaction").dialog({ autoOpen: false, width:'30%',height:120, title:'eHMS 2.0',modal: true});
    });
</script>

<script type="text/javascript">
    function Preview_Previous_Transaction(Registration_ID,Check_In_ID,Folio_Number,Patient_Bill_ID){
        var Claim_Form_Number = '<?php echo $Claim_Form_Number; ?>';
        if(window.XMLHttpRequest){
            myObjectPreview = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectPreview = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPreview.overrideMimeType('text/xml');
        }

        myObjectPreview.onreadystatechange = function (){
            data999 = myObjectPreview.responseText;
            if (myObjectPreview.readyState == 4) {
                document.getElementById('Preview_Previous_Transaction_Area').innerHTML = data999;
                $("#Preview_Previous_Transaction").dialog("open");
            }
        }; //specify name of function that will handle server response........
        
        myObjectPreview.open('GET','Preview_Previous_Transaction.php?Registration_ID='+Registration_ID+'&Check_In_ID='+Check_In_ID+'&Folio_Number='+Folio_Number+'&Patient_Bill_ID='+Patient_Bill_ID+'&Claim_Form_Number='+Claim_Form_Number,true);
        myObjectPreview.send();
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

<script type="text/javascript">
    function make_Payments(Payment_Cache_ID){
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Section_Link = '<?php echo $Section_Link; ?>';
        var Sponsor_ID='<?php echo $Sponsor_ID; ?>';
        var Billing_Type = '<?php echo $Billing_Type; ?>';
        var Confirm_Message = confirm("Are you sure you want to approve selected transaction? \nClick OK to proceed or Cancel to stop process?");
        if (Confirm_Message == true) {
            if (window.XMLHttpRequest) {
                myObjectFilter = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectFilter.overrideMimeType('text/xml');
            }

            myObjectFilter.onreadystatechange = function () {
                dataFilter = myObjectFilter.responseText;
                if (myObjectFilter.readyState == 4) {
                    var feedback = dataFilter;
                    if(feedback == 'yes'){
                        alert("Transaction Approved Successfully");
                        window.open("pharmacycredits.php?"+Section_Link+"PharmacyCredits=PharmacyCreditsThisPage","_parent");
                    }else{
                        alert(feedback)
                        $("#Fail_Transaction").dialog("open");
                    }
                }
            }; //specify name of function that will handle server response........

            myObjectFilter.open('GET','Approve_Selected_Transaction_Pharmacy.php?'+Section_Link+'Payment_Cache_ID='+Payment_Cache_ID+'&Registration_ID='+Registration_ID+"&Sponsor_ID="+Sponsor_ID+ '&Billing_Type=' + Billing_Type, true);
            myObjectFilter.send();
        }
    }
</script>

<?php
    include("./includes/footer.php");
?>