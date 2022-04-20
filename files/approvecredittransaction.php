<?php
include("./includes/header.php");
include("./includes/connection.php");
$temp = 0;
$Grand_Total = 0;
if (isset($_GET['Section'])) {
    $Section_Link = "Section=" . $_GET['Section'] . "&";
    $Section = $_GET['Section'];
} else {
    $Section_Link = "";
    $Section = "";
}
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
        if ($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes' && $_SESSION['userinfo']['Msamaha_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            
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

if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 0;
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
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
echo "<a href='credittransactionsapproval.php?" . $Section_Link . "credittransactionsapproval=credittransactionsapprovalThisPage' class='art-button-green'>BACK</a>";
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
} 
?>
<br/>
<br/>
<fieldset>
    <legend align=center><b> APPROVE CREDIT TRANSACTION</b></legend>
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
                                    <option selected='selected'><?php echo $Billing_Type; ?></option> 
                                </select>
                            </td>
                            <td style="text-align:right;">Sponsor Name</td>
                            <td><input type='text' name='Guarantor_Name' readonly="readonly" id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                            <?php
                            //get consultation id
                            $select = mysqli_query($conn,"select consultation_id from tbl_payment_cache where
                                                        Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
                            $num = mysqli_num_rows($select);
                            if ($num > 0) {
                                while ($data = mysqli_fetch_array($select)) {
                                    $consultation_id = $data['consultation_id'];
                                }
                            } else {
                                $consultation_id = 0;
                            }

                            //get required details
                            $select = mysqli_query($conn,"select Folio_Number, Check_In_ID, Patient_Bill_ID, Claim_Form_Number from 
                                                        tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_consultation c where
                                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                        ppl.Patient_Payment_Item_List_ID = c.Patient_Payment_Item_List_ID and
                                                        c.consultation_ID = '$consultation_id'") or die(mysqli_error($conn));
                            $number = mysqli_num_rows($select);
                            if ($number > 0) {
                                while ($row = mysqli_fetch_array($select)) {
                                    $Folio_Number = $row['Folio_Number'];
                                    $Check_In_ID = $row['Check_In_ID'];
                                    $Patient_Bill_ID = $row['Patient_Bill_ID'];
                                    $Claim_Form_Number = $row['Claim_Form_Number'];
                                }
                            } else {
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
            <td><label >Select Department</label></td>
            <td>                
                <select style='width:100%!important;' id='Check_In_Type' onchange="filter_items_by_department()">
                    <option value="All">All Departments</option>
                    <option value="Laboratory">Laboratory</option>
                    <option value="Radiology">Radiology</option>
                    <option value="Procedure">Procedure</option>
                    <option value="Surgery">Surgery</option>                    
                    <option value="Pharmacy" >Pharmacy</option>
                    <option value="Doctor Room" >Consultation</option>
                    <option value="Others">Others</option>
                    <option value="Nuclearmedicine">Nuclear Medicine</option>
                </select>
    
            </td>
            <?php 
                $Sponsor_exemption =  mysqli_query($conn, "SELECT pr.Sponsor_ID, Exemption FROM tbl_sponsor sp, tbl_patient_registration pr WHERE sp.Sponsor_ID=pr.Sponsor_ID AND Registration_ID='$Registration_ID' AND Exemption='yes'") or die(mysqli_error($conn));
                $nno =mysqli_num_rows($Sponsor_exemption);

                if($nno>0){
                ?>
                <input type="text" id="exemption" value="yes" style="display:none;">
                <td style="text-align: right;"><b>Aina Ya msamaha</b></td>
                <td>
                    <select name="Aina_ya_msamaha" id="msamaha_Items" style="padding:4px;width:99%; text-align:center; " required>
                        <option value="">~~~ Select Aina ya Msamaha ~~~</option>
                        <?php
                            $Query= mysqli_query($conn,"SELECT * FROM tbl_msamaha_items");

                            while ($row=  mysqli_fetch_assoc($Query)){
                                $msamaha_Items = $row['msamaha_Items'];
                                $msamaha_aina = $row['msamaha_aina'];
                                echo "<option value='$msamaha_Items'>$msamaha_aina</option>"; 

                            }
                        ?>

                    </select>
                </td>
                <?php 
                }else{
                    echo '<input type="text" id="exemption" value="no" style="display:none;">';
                } ?>
                <td style='text-align: right;'>
                    <input type="button" name="Make_Payments" id="Make_Payments" value="APPROVE" class="art-button-green" onclick="Validate_make_Payments()">
                </td>
            </tr> 
        </table>
    </center>
</fieldset>
<fieldset style='overflow-y: scroll; height: 320px;' id='Items_Fieldset_List'>
    <center>
        <table width=100%>
            <thead>
                <tr>
                    <td><b>SN</b></td>
                    <td width='20%'><b>ITEM NAME</b></td>
                    <td width="7%"><b>CHECK IN TYPE</b></td>
                    <td width="10%"><b>DATE SENT</b></td>
                    <td style="text-align: right;" width="7%"><b>PRICE</b></td>
                    <td style="text-align: right;" width="5%"><b>DISCOUNT</b></td>
                    <td style="text-align: right;" width="5%"><b>QUANTITY</b></td>
                    <td style="text-align: right;" width="10%"><b>SUB TOTAL</b></td>
                    <td style="text-align: center;" width="10%"><b>TRANSACTION TYPE</b></td>
                    
                    <td style="text-align: center;" width="20%">
                    <label><input type="checkbox"  name="check" id="mark_all">Select All</label>
                    </td>
                </tr>
            </thead>
            <tbody id='show_item_to_approve'>
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

            
            
            
            ?>
            </tbody>
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
if ($nm > 0) {
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
            <td>
                <?php 
                    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                    $Patient_Payment_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Patient_Payment_ID  FROM tbl_payment_cache pc, tbl_item_list_cache ilc WHERE ilc.Payment_Cache_ID = pc.Payment_Cache_ID and pc.Registration_ID ='$Registration_ID'  AND  ilc.Payment_cache_ID='$Payment_Cache_ID' AND Patient_Payment_ID !='' ORDER BY Patient_Payment_ID DESC LIMIT 1"))['Patient_Payment_ID'];
                    if($Patient_Payment_ID != 0){
                        echo "<a class='btn btn-danger btn-sm' href='previewapprovedtransaction.php?{$Section_Link}Patient_Payment_ID=$Patient_Payment_ID&Registration_ID=$Registration_ID&PreviewApprovedTransaction=PreviewApprovedTransactionThisPage' style='color:white;'> PREVIEW RECEIPT</a>";
                    }
                ?>
            </td>
            <td style="text-align: right;" id="all_grand_total_td">
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

<script>
    function create(template, vars, opts) {
      return $container.notify("create", template, vars, opts);
    }


    /*
     save the nhif authorization number to for a pertient to get restricted treatment 321128301894
    */
    function save_authorization_number(Item_ID,Registration_ID,Payment_ID){
        var treatment_authorization_no = $("#treatmentauthorizationno"+Payment_ID).val();
        var Employee_ID = "<?=$Employee_ID?>";
        // alert(treatment_authorization_no+'=======');exit;

        if(treatment_authorization_no.trim() != ''){
            $.ajax({
            url:'save_insurance_authorization_number.php',
            type:'post',
            data:{Employee_ID:Employee_ID,Item_ID:Item_ID,Registration_ID:Registration_ID,Payment_ID:Payment_ID,treatment_authorization_no:treatment_authorization_no, from:'failed_claim'},
            dataType:'json',
            cache: false,
            success:function(results){
                if(results.code == 200){

                alert("Authorization Number "+results.number+" Added Successifully ");
                $("#treatment_authorization_no"+Payment_ID).val(results.number);
                $("#treatment_authorization_no"+Payment_ID).attr('readonly',true);
                $("#authorize_"+Payment_ID).hide();
                $("#save_"+Payment_ID).hide();
                }
            }
            });
        }else{
            alert("NO TREATMENT AUTHORIZATION NUMBER");
        }
    }


 function verify_service(Item_ID,Registration_ID,Payment_ID){
      var treatment_authorization_no = $("#treatmentauthorizationno"+Payment_ID).val();
     
        if(treatment_authorization_no==''){
            $("#treatmentauthorizationno"+Payment_ID).css("border", "2px solid red");exit();
        }
      if(treatment_authorization_no.trim() != ''){
        $.ajax({
            url:'ajax_service_verication.php',
            type:'post',
            data:{Item_ID:Item_ID,Registration_ID:Registration_ID,treatment_authorization_no:treatment_authorization_no},
            dataType:'json',
            cache: false,
            beforeSend: function(xhr) {
                $('#verifyprogress_'+Payment_ID).show();
            },
            success:function(results){
                $('#verifyprogress_'+Payment_ID).hide();
                if(results.code == 200){
                    save_authorization_number(Item_ID,Registration_ID,Payment_ID);
                }else{
                    alert("INVALID APROVAL NUMBER");
                }
            }
        });
      }else{
        alert("NO TREATMENT AUTHORIZATION NUMBER");
      }
    }
</script>

<script>
   
    function filter_items_by_department(){
        var Check_In_Type = $("#Check_In_Type").val();
        var Payment_Cache_ID = '<?= $Payment_Cache_ID ?>';
        var Registration_ID = '<?= $Registration_ID ?>';
        $.ajax({
                type:'POST',
                url:'Ajax_credittransactionsapproval.php',                
                data:{Check_In_Type:Check_In_Type,Registration_ID:Registration_ID, Payment_Cache_ID:Payment_Cache_ID, item_to_approve:'' },
                cache: false,
                success:function(responce){
                    $("#show_item_to_approve").html(responce);
                }
            });
    }
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
           cache: false,
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
           url:"change_discount_price_for_approval_proc_surg.php",
           data:{discount_price:discount_price,Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,Payment_Cache_ID:Payment_Cache_ID,Registration_ID:Registration_ID,Billing_Type:Billing_Type},
           cache: false,
           success:function(data){
               $("#sub_total"+Payment_Item_Cache_List_ID).html(data);
               $("#sub_total_txt"+Payment_Item_Cache_List_ID).val(data);
               $(".sub_t_txt").each(function() {
                    //alert($(this).val());
                    grand_total=grand_total+parseInt($(this).val());
                   //alert(grand_total)
                });
               $("#grand_total_td").html(grand_total);
             var amount_spent_txt= $("#amount_spent_txt").val();
             var all_grand_total_td=parseInt(amount_spent_txt)+parseInt(grand_total);
               $("#all_grand_total_td").html("GRAND TOTAL :"+all_grand_total_td); 
           },
           complete:function(){
               // alert("complete")
           },
           error:function(error,errormessage){
               alert(error.errormessage)
           }
       });
    }
</script>
<script>
        $(document).ready(function () {
            
            $("#ePayment_Window").dialog({autoOpen: false, width: '55%', height: 250, title: 'Create ePayment Bill', modal: true});
            $("#ePayment_Window_Removed").dialog({autoOpen: false, width: '65%', height: 300, title: 'ePayment Bill ~ Removed Items', modal: true});
            $("#Preview_Previous_Transaction").dialog({autoOpen: false, width: '80%', height: 480, title: 'PREVIOUS TRANSACTIONS', modal: true});
        });
        $(document).ready(function(){
            filter_items_by_department(Check_In_Type);
        })
</script>

<script type="text/javascript">
    function Preview_Previous_Transaction(Registration_ID, Check_In_ID, Folio_Number, Patient_Bill_ID) {
        var Claim_Form_Number = '<?php echo $Claim_Form_Number; ?>';
        if (window.XMLHttpRequest) {
            myObjectPreview = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPreview = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPreview.overrideMimeType('text/xml');
        }

        myObjectPreview.onreadystatechange = function () {
            data999 = myObjectPreview.responseText;
            if (myObjectPreview.readyState == 4) {
                document.getElementById('Preview_Previous_Transaction_Area').innerHTML = data999;
                $("#Preview_Previous_Transaction").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectPreview.open('GET', 'Preview_Previous_Transaction.php?Registration_ID=' + Registration_ID + '&Check_In_ID=' + Check_In_ID + '&Folio_Number=' + Folio_Number + '&Patient_Bill_ID=' + Patient_Bill_ID + '&Claim_Form_Number=' + Claim_Form_Number, true);
        myObjectPreview.send();
    }
</script>

<script type="text/javascript">
    function Validate_make_Payments() {
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectMakePayment = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectMakePayment = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectMakePayment.overrideMimeType('text/xml');
        }

        myObjectMakePayment.onreadystatechange = function () {
            data981 = myObjectMakePayment.responseText;
            if (myObjectMakePayment.readyState == 4) {
                var feedback = data981;
                if (feedback == 'yes') {
                    make_Payments(Payment_Cache_ID);
                } else {
                    alert("You are not allowed to make payment with zero price or zero quantity. Please remove those items to proceed");
                }
            }
        }; //specify name of function that will handle server response........

        myObjectMakePayment.open('GET', 'Validate_Make_Payments_All.php?Payment_Cache_ID=' + Payment_Cache_ID, true);
        myObjectMakePayment.send();
    }
</script>

<script type="text/javascript">
    function make_Payments(Payment_Cache_ID) {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Section_Link = '<?php echo $Section_Link; ?>';
        var items = [];
        $('.mark').each(function() {
            if(this.checked){
                var item_id = $(this).attr("id");
                items.push(item_id);                
            }
        });
        
        if(items.length == 0){
            alert("Select items first");
            return false;
        }
        var exemption = $("#exemption").val();
        var Check_In_Type = $("#Check_In_Type").val();
        var msamaha_Items = $("#msamaha_Items").val();
        if(exemption=='yes'){
            if(msamaha_Items==''){
                alert("Tafadhari Chagua aina ya Msamaha")
                $("#msamaha_Items").css("border", "2px red solid");
                exit();
                
            }
        }
        if(items.length == 0){
            alert("Select items first");
            return false;
        }
        var Confirm_Message = confirm("Are you sure you want to approve selected transaction? \nClick OK to proceed or Cancel to stop process?");
        if (Confirm_Message == true) {
            $.ajax({
               type:'GET',
               url:'Approve_Selected_Transaction.php',
               data:{Section_Link:Section_Link,Payment_Cache_ID:Payment_Cache_ID,Check_In_Type:Check_In_Type, msamaha_Items:msamaha_Items, Registration_ID:Registration_ID, items:items},
               success:function(responce){
               location.reload()
               } 
            })           
        }
       
    }
</script>
<script>
    function removeitem(item) {
        //alert(item);
        var check = confirm("This cannot be undone.Are you sure you want to remove this quantity");
        if (check) {
            $.ajax({
                type: 'POST',
                url: "remove_item_from_cache.php",
                data: "id=" + item,
                cache: false,
                success: function (data) {
                    window.location = window.location.href;
                }, error: function (jqXHR, textStatus, errorThrown) {
                }
            });
        }
    }
</script>
<script>
    $("#mark_all").change(function(e){
        e.preventDefault();
        if(this.checked) {
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true;
            
        });
    }
    else {
        $(':checkbox').each(function() {
            this.checked = false;
        });
    }

    });
</script>
<?php
include("./includes/footer.php");
?>