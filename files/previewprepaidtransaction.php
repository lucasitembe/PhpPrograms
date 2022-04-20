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
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $Employee_ID = 0;
        $Employee_Name = '';
    }

    if(isset($_GET['Patient_Payment_ID'])){
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    }else{
        $Patient_Payment_ID = 0;
    }

    if(isset($_GET['Patient_Payment_ID'])){
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    }else{
        $Patient_Payment_ID = 0;
    }

    if(isset($_GET['Section'])){
        $Section = $_GET['Section'];
    }else{
        $Section = '';
    }
	
	if(strtolower($Section) == 'visitor'){

	}		
	echo "<a href='prepaidpatientslist.php?Section=".$Section."&PrePaidPatientsList=PrePaidPatientsListThisPage' class='art-button-green'>BACK</a>";
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
    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age = '';
    }
?>

<?php
    $select_Patient = mysqli_query($conn,"select pr.Registration_ID, pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Member_Number, pr.Member_Card_Expire_Date, pr.Phone_Number,
    								sp.Guarantor_Name, sp.Sponsor_ID, pp.Billing_Type, pp.Folio_Number, pp.Payment_Date_And_Time, emp.Employee_Name, PP.Pre_Paid,
    								(select Employee_Name from tbl_employee em, tbl_patient_payments ppa where em.Employee_ID = ppa.Supervisor_ID and ppa.Patient_Payment_ID = '$Patient_Payment_ID') as Supervisor_Name
			    					from tbl_patient_registration pr, tbl_sponsor sp, tbl_patient_payments pp, tbl_employee emp where
			    					pp.Registration_ID = pr.Registration_ID and
			    					emp.Employee_ID = pp.Employee_ID and
                                    pr.Sponsor_ID = sp.Sponsor_ID and
                                    pp.Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
        	$Registration_ID = $row['Registration_ID'];
            $Patient_Name = ucwords(strtolower($row['Patient_Name']));
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            $Supervisor_Name = $row['Supervisor_Name'];
            $Billing_Type = $row['Billing_Type'];
            $Folio_Number = $row['Folio_Number'];
            $Claim_Form_Number = $row['Claim_Form_Number'];
            $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
            $Employee_Name = $row['Employee_Name'];
            $Pre_Paid = $row['Pre_Paid'];
        }
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";
    } else {
        $Registration_ID = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Guarantor_Name = '';
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
        $Registration_Date_And_Time = '';
        $Supervisor_Name = '';
        $Billing_Type = '';
        $Folio_Number = '';
        $Payment_Date_And_Time = '';
        $Employee_Name = '';
        $Pre_Paid = 0;
        $Claim_Form_Number = '';
    }
?>
    <br/>
    <br/>
    <fieldset>
        <legend align=right><b>PRE / POST PAID PAYMENTS PREVIEW</b></legend>
        <table width=100%>
            <tr>
                <td width='10%' style="text-align:right;">Patient Name</td>
                <td width='15%'><input type='text' name='Patient_Name' readonly="readonly" id='Patient_Name' value='<?php echo $Patient_Name; ?>' title="<?php echo $Patient_Name; ?>"></td>
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
                    <select>
                        <option selected='selected'><?php echo $Billing_Type; ?></option> 
                    </select>
                </td>
                <td style="text-align:right;">Sponsor Name</td>
                <td><input type='text' name='Guarantor_Name' readonly="readonly" id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                <td style="text-align:right;" >Claim Form Number</td>
                <td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number' value="<?php echo $Claim_Form_Number; ?>" readonly="readonly"></td>
                <td style="text-align:right;">Folio Number</td>
                <td><input type='text' name='Folio_Number' id='Folio_Number' readonly="readonly" value='<?php echo $Folio_Number; ?>' readonly="readonly"></td>
            </tr>
            <tr>    
                <td style="text-align:right;">Member Number</td>
                <td><input type='text' name='Supervised_By' id='Supervised_By' readonly="readonly" value='<?php echo $Member_Number; ?>'></td>
                <td style="text-align:right;">Phone Number</td>
                <td><input type='text' name='Phone_Number' id='Phone_Number' readonly="readonly" value='<?php echo $Phone_Number; ?>'></td>

                <td style="text-align:right;">Prepared By</td>
                <td><input type='text' name='Prepared_By' id='Prepared_By' readonly="readonly" value='<?php echo $Employee_Name; ?>'></td>
                <td style="text-align:right;">Supervised By</td>
                <td><input type='text' name='Supervisor_ID' id='Supervisor_ID' readonly="readonly" value='<?php echo $Supervisor_Name; ?>'></td>
            </tr>
            <tr>
            <?php
                $slct = mysqli_query($conn,"select Employee_Name from tbl_employee emp, tbl_item_list_cache ilc where
                                        emp.Employee_ID = ilc.Consultant_ID and
                                        ilc.Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
                $slct_no = mysqli_num_rows($slct);
                if($slct > 0){
                    while ($dt = mysqli_fetch_array($slct)) {
                        $Consulting_Doctor = $dt['Employee_Name'];
                    }
                }else{
                    $Consulting_Doctor = '';
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
    </fieldset>

    <fieldset>   
        <center>
            <table width=100%>
                <tr>
                	<td style="text-align: center;">
                		<?php if($Pre_Paid == 0){ echo "<span style='color: #037CB0;'><b>SELECTED TRANSACTION IS NOT PART OF PRE / POST PAID BILL</b></span>"; } ?>
                	</td>
                    <td style='text-align: right;' width="40%">
                <?php
                    $q = mysqli_query($conn,"SELECT * FROM tbl_printer_settings") or die(mysqli_error($conn));
                    $row = mysqli_fetch_assoc($q);
                    $exist = mysqli_num_rows($q);
                    if ($exist > 0) {
                        $Paper_Type = $row['Paper_Type'];
                    }else{
                        $Paper_Type = 'Others( A4,A5,A6,...)';
                    }

                    if(strtolower($Paper_Type) != 'receipt'){
                        echo '<input type="button" value="PRINT DEBIT NOTE" class="art-button-green" onclick="Print_Receipt_Payment()">';
                    }else{
                ?>
                        <input type="button" name="Make_Payments" id="Make_Payments" value="PRINT REPORT" class="art-button-green" onclick="Print_Payment_Code(<?php echo $Patient_Payment_ID; ?>,<?php echo $Registration_ID; ?>)">
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
                <td width="5%"><b>SN</b></td>
                <td><b>ITEM NAME</b></td>
                <td width="10%"><b>CHECK IN TYPE</b></td>
                <td style="text-align: right;" width="12%"><b>PRICE</b></td>
                <td style="text-align: right;" width="12%"><b>DISCOUNT</b></td>
                <td style="text-align: right;" width="12%"><b>QUANTITY</b></td>
                <td style="text-align: right;" width="12%"><b>SUB TOTAL</b></td>
            </tr>
            <tr><td colspan="8"><hr></td></tr>
<?php
    $select_items = mysqli_query($conn,"select itm.Product_Name, ilc.Quantity, ilc.Edited_Quantity, ilc.Price, ilc.Discount, ilc.Payment_Item_Cache_List_ID, ilc.Check_In_Type, ilc.Payment_Item_Cache_List_ID, ilc.ePayment_Status
                                    from tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_items itm where
                                    ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
                                    ilc.Item_ID = itm.Item_ID and
                                    ilc.Patient_Payment_ID = '$Patient_Payment_ID'
                                    order by ilc.Check_In_Type") or die(mysqli_error($conn));
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
    function Print_Receipt_Payment() {
        var winClose = popupwindow('individualpaymentreportindirect.php?Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&IndividualPaymentReport=IndividualPaymentReportThisPage', 'Receipt Patient', 530, 400);
    }

    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);

        return mypopupWindow;
    }
</script>
<?php
    include("./includes/footer.php");
?>