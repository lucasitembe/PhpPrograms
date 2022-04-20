<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $temp = 0;
    $Grand_Total = 0;
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    
    if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
            if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes' && $_SESSION['userinfo']['Msamaha_Works'] != 'yes' && $_SESSION['userinfo']['Reception_Works'] != 'yes'){
                header("Location: ./index.php?InvalidPrivilege=yes");
            } else {
                @session_start();
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

    $Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];
    $Supervisor_Name = $_SESSION['supervisor']['Employee_Name'];

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

    if(isset($_GET['Patient_Payment_ID'])){
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    }else{
        $Patient_Payment_ID = 0;
    }

    if(isset($_GET['Session'])){
        $Section_Link = "Section=".$_GET['Session']."&";
    }else{
        $Section_Link = "";
    }

    if(isset($_GET['Section'])){
        $Section = $_GET['Section'];
    }else{
        $Section = '';
    }
    //generate filter value
    if(strtolower($Section) == 'procedure'){
        $filter = "(ilc.Check_In_Type = 'Procedure' or ilc.Check_In_Type = 'Surgery') and";
    }else if(strtolower($Section) == 'optical'){
        $filter = "ilc.Check_In_Type = 'Optical' and";
    }else if(strtolower($Section) == 'reception'){
        $filter = '';
    }else if(strtolower($Section) == 'revenue'){
        $filter = '';
    }else{
        $filter = "(ilc.Check_In_Type = 'Laboratory' or ilc.Check_In_Type = 'Radiology' or ilc.Check_In_Type ='Pharmacy') and";
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
<a href="#" onclick="goBack()"class="art-button-green">BACK</a>    
<script>
    
    function goBack(){
        window.history.back();
    }
</script>
<?php
    // if(strtolower($Section) == 'procedure'){
    //     echo "<a href='procedurecredits.php?".$Section_Link."ProcedureCredits=ProcedureCreditsThisPage' class='art-button-green'>BACK</a>";
    // }else if(strtolower($Section) == 'optical'){
    //     echo "<a href='glassescredits.php?".$Section_Link."ProcedureCredits=ProcedureCreditsThisPage' class='art-button-green'>BACK</a>";
    // }else if(strtolower($Section) == 'reception'){
    //     echo "<a href='departmentpatientbillingpage.php?".$Section_Link."DepartmentPatientBilling=DepartmentPatientBillingThisPage' class='art-button-green'>BACK</a>";
    // }else if(strtolower($Section) == 'revenue'){
    //     echo "<a href='departmentpatientbillingpage.php?".$Section_Link."DepartmentPatientBilling=DepartmentPatientBillingThisPage' class='art-button-green'>BACK</a>";
    // }else{
    //     echo "<a href='investigationcredit.php?".$Section_Link."InvestigationCredit=InvestigationCreditThisPage' class='art-button-green'>BACK</a>";
    // }
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
    if (isset($_GET['Registration_ID'])) {
        $select_Patient = mysqli_query($conn,"select * from tbl_patient_registration pr, tbl_sponsor sp where
                                        pr.Sponsor_ID = sp.Sponsor_ID and
                                        pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
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
?>
    <br/>
    <br/>
    <fieldset>
        <legend align=right><b>APPROVED TRANSACTION REVIEW</b></legend>
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
                            <?php
                                //get billing_Type
                                $select = mysqli_query($conn,"select Billing_Type from tbl_patient_payments where Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
                                $nm = mysqli_num_rows($select);
                                if($nm > 0){
                                    while($td = mysqli_fetch_array($select)){
                                        $Billing_Type = $td['Billing_Type'];
                                    }
                                }else{
                                    $Billing_Type = 'Outpatient Credit';
                                }
                            ?>
                                <td>
                                    <select name='Billing_Type' id='Billing_Type'>
                                        <option selected='selected'><?php echo $Billing_Type; ?></option> 
                                    </select>
                                </td>
                                <td style="text-align:right;">Sponsor Name</td>
                                <td><input type='text' name='Guarantor_Name' readonly="readonly" id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                            <?php
                                $select_claim = mysqli_query($conn,"select Claim_Form_Number, Folio_Number from tbl_patient_payments where Patient_Payment_ID = '$Patient_Payment_ID' and Registration_ID = '$Registration_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                                $no = mysqli_num_rows($select_claim);
                                if ($no > 0) {
                                    while ($row = mysqli_fetch_array($select_claim)) {
                                        $Claim_Form_Number = $row['Claim_Form_Number'];
                                        $Folio_Number = $row['Folio_Number'];
                                    }
                                } else {
                                    $Claim_Form_Number = '';
                                    $Folio_Number = '';
                                }
                            ?>
                                <td style="text-align:right;" >Claim Form Number</td>
                                <td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number' value="<?php echo $Claim_Form_Number; ?>" readonly="readonly"></td>
                                <td style="text-align:right;">Folio Number</td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' readonly="readonly" value='<?php echo $Folio_Number; ?>' value="<?php echo $Folio_Number; ?>" readonly="readonly"></td>
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
                            <tr>
                            <?php
                                $slct = mysqli_query($conn,"select Employee_Name, ilc.Payment_Date_And_Time from tbl_employee emp, tbl_item_list_cache ilc where
                                                        emp.Employee_ID = ilc.Consultant_ID and
                                                        ilc.Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
                                $slct_no = mysqli_num_rows($slct);
                                if($slct > 0){
                                    while ($dt = mysqli_fetch_array($slct)) {
                                        $Consulting_Doctor = $dt['Employee_Name'];
                                        $Payment_Date_And_Time = $dt['Payment_Date_And_Time'];
                                    }
                                }else{
                                    $Consulting_Doctor = '';
                                    $Payment_Date_And_Time = '';
                                }
                            ?>
                                <td style="text-align:right;">Consulting Doctor</td>
                                <td><input type='text' name='Consulting_Doctor' id='Consulting_Doctor' readonly="readonly" value='<?php echo $Consulting_Doctor; ?>'></td>
                                <td style="text-align:right;">Invoice Number</td>
                                <td><input type='text' name='Patient_Payment_ID' id='Patient_Payment_ID' readonly="readonly" value='<?php echo $Patient_Payment_ID; ?>'></td>
                                <td style="text-align:right;">Invoice Date</td>
                                <td><input type='text' name='Payment_Date_And_Time' id='Payment_Date_And_Time' readonly="readonly" value='<?php echo $Payment_Date_And_Time; ?>'></td>
                                <td style="text-align:right;">Location</td>
                                <td><input type='text' name='Revenue Center' id='Revenue Center' readonly="readonly" value='Revenue Center'></td>
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
                    $q = mysqli_query($conn,"SELECT * FROM tbl_printer_settings") or die(mysqli_error($conn));
                    $row = mysqli_fetch_assoc($q);
                    $exist = mysqli_num_rows($q);
                    if ($exist > 0) {
                        $Paper_Type = $row['Paper_Type'];
                    }else{
                        $Paper_Type = 'Others( A4,A5,A6,...)';
                    }

                    if(strtolower($Paper_Type) == 'receipt'){
                        echo "<input type='button' value='PRINT DEBIT NOTE' class='art-button-green' onclick='Print_Receipt_Payment($Patient_Payment_ID)'>";
                    }else{
                ?>
                        <input type="button" name="Make_Payments" id="Make_Payments" value="PRINT CREDIT RECEIPT" class="art-button-green" onclick="Print_Receipt_Payment(<?php echo $Patient_Payment_ID; ?>)">
                <?php } ?>                    
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
                <td width="10%"><b>CHECK IN TYPE</b></td>
                <td style="text-align: right;" width="12%"><b>PRICE</b></td>
                <td style="text-align: right;" width="12%"><b>DISCOUNT</b></td>
                <td style="text-align: right;" width="12%"><b>QUANTITY</b></td>
                <td style="text-align: right;" width="12%"><b>SUB TOTAL</b></td>
            </tr>
            <tr><td colspan="8"><hr></td></tr>
<?php
    $select_items = mysqli_query($conn,"SELECT itm.Product_Name, ilc.Quantity, ilc.Edited_Quantity, ilc.Price, ilc.Discount, ilc.Payment_Item_Cache_List_ID, ilc.Check_In_Type, ilc.Payment_Item_Cache_List_ID, ilc.ePayment_Status   from tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_items itm where    ilc.Payment_Cache_ID = pc.Payment_Cache_ID and    ilc.Item_ID = itm.Item_ID and  ilc.Status = 'paid' and  (ilc.Transaction_Type = 'Credit' || ilc.Transaction_Type = 'Cash') and  ilc.Patient_Payment_ID = '$Patient_Payment_ID' and     pc.Registration_ID = '$Registration_ID' and  ilc.ePayment_Status = 'pending' order by ilc.Check_In_Type") or die(mysqli_error($conn));

    
   
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
?>
            <tr>
                <td><?php echo ++$temp; ?></td>
                <td><?php echo $data['Product_Name']; ?></td>
                <td><?php echo $data['Check_In_Type']; ?></td>
                <td style="text-align: right;"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($data['Price']) : number_format($data['Price'])); ?></td>
                <td style="text-align: right;"><?php echo number_format($data['Discount']); ?></td>
                <td style="text-align: right;"><?php if($data['Edited_Quantity'] != 0){ echo $data['Edited_Quantity']; }else{ echo $data['Quantity']; } ?></td>
                <td style="text-align: right;"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Total) : number_format($Total)) ?></td>
            </tr>            
<?php
        }
    }
?>
            <tr><td colspan="8"><hr></td></tr>
            <tr><td colspan="6"><b>GRAND TOTAL</b></td><td style="text-align: right;"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Grand_Total) : number_format($Grand_Total)); ?></td></tr>
        </table>
    </center>
</fieldset>
<fieldset>
    <table width="100%" id="Removed_Area">
        <tr>
            <td style="text-align: right;">
                <b>GRAND TOTAL : <?php echo number_format($Grand_Total); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
    </table>
</fieldset>


<script src="css/jquery.js"></script> 
<script src="css/jquery.datetimepicker.js"></script>
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
<script type="text/javascript">
    function Print_Payment_Code(Patient_Payment_ID,Registration_ID){
        var Section_Link = '<?php echo $Section_Link; ?>';
        window.open('InvestigationPreview.php?'+Section_Link+'Patient_Payment_ID='+Patient_Payment_ID+'&Registration_ID='+Registration_ID+'&InvestigationPreviewPreview=InvestigationPreviewPreviewThisPage','_blank');
    }
</script>


<script>
function Print_Receipt_Payment(Patient_Payment_ID){   
    if(checkForMaximmumReceiptrinting(Patient_Payment_ID) === 'true'){
      var winClose=popupwindow('individualpaymentreportindirect.php?Patient_Payment_ID='+Patient_Payment_ID+'&IndividualPaymentReport=IndividualPaymentReportThisPage', 'Receipt Patient', 530, 400);     
        if(winClose== true){
            $.ajax({
                type:"POST",
                url:"update_receipt_count.php",
                async:false,
                data:{payment_id:Patient_Payment_ID},
                success:function(result){
                    console.log(result)
                }
            })
        }else{
            console.log("Something went wrong")
        }

	}else{
		alert("You have exeded maximumu print count")
		return false;
	}
    
}
  

function checkForMaximmumReceiptrinting(theId){    
    var theCount = '';
    $.ajax({
		type:"POST",
		url:"compare_receipt_count.php",
		async:false,
		data:{payment_id:theId},
		success:function(result){
			// alert(result)
			theCount = result;
			console.log(theCount)                                                
		}
	})
return theCount;
}



function popupwindow(url, title, w, h) {
  var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
   var wTop = window.screenTop ? window.screenTop : window.screenY;

    var left = wLeft + (window.innerWidth / 2) - (w / 2);
    var top = wTop + (window.innerHeight / 2) - (h / 2);
    var mypopupWindow= window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
      
      return mypopupWindow;
}


</script>
<?php
    include("./includes/footer.php");
?>