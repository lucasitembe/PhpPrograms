<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	    if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes' && $_SESSION['userinfo']['Msamaha_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['systeminfo']['Allow_Cashier_To_Approve_Pharmaceutical'])){
        if(strtolower($_SESSION['systeminfo']['Allow_Cashier_To_Approve_Pharmaceutical']) != 'yes'){
          //  header("Location: ./credittransactions.php?CreditTransactions=CreditTransactionsThisForm");
        }
    }

    //get today date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $End_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $Start_Date = $new_Date.' 00:00:00';
    }
    if(isset($_GET['Section'])){
        $Section_Link = "Section=".$_GET['Section']."&";
        $Section = $_GET['Section'];
    }else{
        $Section_Link = "";
        $Section = '';
    }
    echo "<a href='credittransactions.php?".$Section_Link."CreditTransactions=CreditTransactionsThisForm' class='art-button-green'>BACK</a>";
?>

<br/><br/>

<style>
    table,tr,td{
        
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
     .approve_credit_trsns_out_p_bill_tbl table, .approve_credit_trsns_out_p_bill_tbl tr, .approve_credit_trsns_out_p_bill_tbl td{
       border:1px solid #CCCCCC!important; 
    }
</style>
<fieldset>
    <table width=100%>
        <tr>
            <td width="20%">
                <input type="text" name="Patient_Name" id="Patient_Name" autocomplete="off" oninput="Search_Patient()" onkeypress="Search_Patient()" style="text-align: center;" placeholder="~~~ ~~~ Enter Patient Name ~~~ ~~~">
            </td>
            <td width="15%">
                <input type="text" name="Patient_Number" id="Patient_Number" autocomplete="off" oninput="Search_Patient_Via_Number()" onkeypress="Search_Patient_Via_Number()" style="text-align: center;" placeholder="~~~ Enter Patient Number ~~~">
            </td>
            <td style="text-align: right;"><b>Sponsor Name</b></td>
            <td>
                <select name="Sponsor_ID" id="Sponsor_ID">
                    <option selected="selected" value="0">~~~ Select Sponsor ~~~</option>
                    <?php
                    $select = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_sponsor where Require_Document_To_Sign_At_receiption = 'Mandatory' order by Guarantor_Name") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($select);
                    if ($num > 0) {
                        while ($data = mysqli_fetch_array($select)) {
                            ?>
                            <option value="<?php echo $data['Sponsor_ID']; ?>"><?php echo $data['Guarantor_Name']; ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </td>
            <td style='text-align: right;'><b>Start Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='Start_Date' style='text-align: center;' placeholder='Start Date' readonly='readonly' value=''>
            </td>
            <td style='text-align: right;'><b>End Date</b></td>
            <td>
                <input type='text' name='End_Date' id='End_Date' style='text-align: center;' placeholder='End Date' readonly='readonly' value=''>
            </td>
            <td style='text-align: center;' width=7%>
                <input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='Filter_Patient()'>
            </td>           
        </tr>
        <tr>
            <td colspan="9">
                <center>
                    <table style="background:#FFFFFF;" class="approve_credit_trsns_out_p_bill_tbl">
                        <tr>
                            <td style="background:green;color:#FFFFFF;font-size: 15px">
                                <label>Approve Credit Transaction<input type="radio" onclick="clear_search_box(),Search_Patient()" name="outpatient_bill_credit_trns_approval"class="outpatient_bill_credit_trns_approval" value="approve_credit_transaction" checked="checked"></label>
                            </td>
                            <td style="background:#2D8AAF;color:#FFFFFF;font-size: 15px">
                                <label>Approve Outpatient Bill Transaction<input type="radio" onclick="clear_search_box(),Search_Patient()"  name="outpatient_bill_credit_trns_approval" class="outpatient_bill_credit_trns_approval" value="approve_outpatient_bill_transaction"></label>
                            </td>
                        </tr>
                    </table>
                </center>
            </td>
        </tr>
    </table>
</fieldset>

<fieldset style='overflow-y: scroll; height: 380px; background-color: white;' id='Patient_List_Area'>
    <legend align="right;">PHARMACY TRANSACTIONS</legend>
    <table width="100%">
        <tr><td colspan="8"><hr></td></tr>
        <tr>
            <td width="5%"><b>SN</b></td>
            <td><b>PATIENT NAME</b></td>
            <td width="10%"><b>PATIENT NUMBER</b></td>
            <td width="15%"><b>SPONSOR NAME</b></td>
            <td width="7%"><b>GENDER</b></td>
            <td width="14%"><b>AGE</b></td>
            <td width="10%"><b>TRANSACTION DATE</b></td>
            <td width="10%"><b>MEMBER NUMBER</b></td>
        </tr>
        <tr><td colspan="8"><hr></td></tr>
    <?php
        $temp = 0;
        $get_details = mysqli_query($conn,"select ilc.Transaction_Date_And_Time, pc.Payment_Cache_ID, pr.Registration_ID, sp.Guarantor_Name, pr.Phone_Number, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth from
                                    tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_sponsor sp where
                                    pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                    pc.Registration_ID = pr.Registration_ID and
                                    sp.Sponsor_ID = pr.Sponsor_ID and
                                    sp.Require_Document_To_Sign_At_receiption = 'Mandatory' and
                                    ilc.ePayment_Status = 'pending' and
                                    (ilc.Status = 'active' OR (ilc.Status = 'approved' AND Exemption='yes')) and
                                    
                                    ilc.Check_In_Type = 'Pharmacy'
                                    group by pr.Registration_ID, pc.Payment_Cache_ID order by ilc.Payment_Item_Cache_List_ID desc limit 200") or die(mysqli_error($conn));
        $nm = mysqli_num_rows($get_details);
        if($nm > 0){
            while ($row = mysqli_fetch_array($get_details)) {
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $date1 = new DateTime($Today);
                $date2 = new DateTime($Date_Of_Birth);
                $diff = $date1 -> diff($date2);
                $age = $diff->y." Years, ";
                $age .= $diff->m." Months, ";
                $age .= $diff->d." Days";
    ?>
        <tr>
            <td><a href="approvepharmacy.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApprovePharmacy=ApprovePharmacyThisPage" style="text-decoration: none;"><?php echo ++$temp; ?></td>
            <td><a href="approvepharmacy.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApprovePharmacy=ApprovePharmacyThisPage" style="text-decoration: none;"><?php echo strtoupper($row['Patient_Name']); ?></a></td>
            <td><a href="approvepharmacy.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApprovePharmacy=ApprovePharmacyThisPage" style="text-decoration: none;"><?php echo $row['Registration_ID']; ?></a></td>
            <td><a href="approvepharmacy.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApprovePharmacy=ApprovePharmacyThisPage" style="text-decoration: none;"><?php echo $row['Guarantor_Name']; ?></a></td>
            <td><a href="approvepharmacy.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApprovePharmacy=ApprovePharmacyThisPage" style="text-decoration: none;"><?php echo strtoupper($row['Gender']); ?></a></td>
            <td><a href="approvepharmacy.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApprovePharmacy=ApprovePharmacyThisPage" style="text-decoration: none;"><?php echo $age; ?></a></td>
            <td><a href="approvepharmacy.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApprovePharmacy=ApprovePharmacyThisPage" style="text-decoration: none;"><?php echo $row['Transaction_Date_And_Time']; ?></a></td>
            <td><a href="approvepharmacy.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApprovePharmacy=ApprovePharmacyThisPage" style="text-decoration: none;"><?php echo $row['Member_Number']; ?></a></td>
        </tr>
    <?php
            }
        }
    ?>
    </table>
</fieldset>
<script type="text/javascript">
     function clear_search_box(){
        $("#Patient_Name").val("");
        $("#Patient_Number").val("");
    }
    function Filter_Patient(){
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Start_Date = document.getElementById("Start_Date").value;
        var End_Date = document.getElementById("End_Date").value;
        var Section = '<?php echo $Section; ?>';

        if(Start_Date != null && Start_Date != '' && End_Date != null && End_Date != ''){
            document.getElementById("Patient_Name").value = '';
            document.getElementById("Patient_Number").value = '';
            if (window.XMLHttpRequest) {
                myObjectFilter = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectFilter.overrideMimeType('text/xml');
            }

            myObjectFilter.onreadystatechange = function () {
                dataFilter = myObjectFilter.responseText;
                if (myObjectFilter.readyState == 4) {
                    document.getElementById('Patient_List_Area').innerHTML = dataFilter;
                }
            }; //specify name of function that will handle server response........

            myObjectFilter.open('GET','Pharmacy_Credit_Filter.php?Section='+Section+'&Sponsor_ID='+Sponsor_ID+'&Start_Date='+Start_Date+'&End_Date='+End_Date, true);
            myObjectFilter.send();
        }else{
            if(Start_Date == null || Start_Date == ''){
                document.getElementById("Start_Date").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Start_Date").style = 'border: 2px solid black; text-align: center;';
            }

            if(End_Date == null || End_Date == ''){
                document.getElementById("End_Date").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("End_Date").style = 'border: 2px solid black; text-align: center;';
            }
        }
    }
</script>

<script type="text/javascript">
    function Search_Patient(){
        
        var outpatient_bill_credit_trns_approval=$(".outpatient_bill_credit_trns_approval:checked").val();
        //alert(outpatient_bill_credit_trns_approval)
        var search_url;
        if(outpatient_bill_credit_trns_approval=="approve_outpatient_bill_transaction"){
            search_url="phamacy_list_outpatient_bill.php"; 
        }else{
            search_url="Pharmacy_Credit_Filter_Specific.php";
        }
        
        document.getElementById("Patient_Number").value = '';
        var Patient_Name = document.getElementById("Patient_Name").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Start_Date = document.getElementById("Start_Date").value;
        var End_Date = document.getElementById("End_Date").value;
        var Section = '<?php echo $Section; ?>';

        if (window.XMLHttpRequest) {
            myObjectFilter = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilter.overrideMimeType('text/xml');
        }

        myObjectFilter.onreadystatechange = function () {
            dataFilter = myObjectFilter.responseText;
            if (myObjectFilter.readyState == 4) {
                document.getElementById('Patient_List_Area').innerHTML = dataFilter;
            }
        }; //specify name of function that will handle server response........

        myObjectFilter.open('GET',search_url+'?Section='+Section+'&Sponsor_ID='+Sponsor_ID+'&Start_Date='+Start_Date+'&End_Date='+End_Date+'&Patient_Name='+Patient_Name, true);
        myObjectFilter.send();
    }
</script>

<script type="text/javascript">
    function Search_Patient_Via_Number(){
        document.getElementById("Patient_Name").value = '';
        var Patient_Number = document.getElementById("Patient_Number").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Start_Date = document.getElementById("Start_Date").value;
        var End_Date = document.getElementById("End_Date").value;
        var Section = '<?php echo $Section; ?>';
        
        if (window.XMLHttpRequest) {
            myObjectFilter = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilter.overrideMimeType('text/xml');
        }

        myObjectFilter.onreadystatechange = function () {
            dataFilter = myObjectFilter.responseText;
            if (myObjectFilter.readyState == 4) {
                document.getElementById('Patient_List_Area').innerHTML = dataFilter;
            }
        }; //specify name of function that will handle server response........

        myObjectFilter.open('GET','Pharmacy_Credit_Filter_Specific.php?Section='+Section+'&Sponsor_ID='+Sponsor_ID+'&Start_Date='+Start_Date+'&End_Date='+End_Date+'&Patient_Number='+Patient_Number, true);
        myObjectFilter.send();
    }
</script>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#Start_Date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en', //startDate:    'now'
    });
    $('#Start_Date').datetimepicker({value: '', step: 01});
    $('#End_Date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en', //startDate:'now'
    });
    $('#End_Date').datetimepicker({value: '', step: 01});
</script>
<!--End datetimepicker-->

<?php
    include("./includes/footer.php");
?>