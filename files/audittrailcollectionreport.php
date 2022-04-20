<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $Title_Control = "False";
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ./index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Management_Works'])){
	    if($_SESSION['userinfo']['Management_Works'] != 'yes' && $_SESSION['userinfo']['General_Ledger'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ./index.php?InvalidPrivilege=yes");
    }
?>
<a href='./generalledgercenter.php?GeneralLedgerCenter=GeneralLedgerCenterThisPage' class='art-button-green'>BACK</a>
<br/><br/>
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
<center>
<fieldset>
    <table width="100%">
        <tr>
            <td style='text-align: center;'><b>Employee</b></td>
            <td style='text-align: center;'><b>Start Receipt</b></td>
            <td width="2%"></td>
            <td style='text-align: center;'><b>End Receipt</b></td>
            <td style='text-align: center;'><b>Sponsor Name</b></td>
            <td style="text-align: center;" width="10%"></td>
        </tr>
        <tr>
            <td width="25%" style="text-align: center;">
                <select name="Employee_ID" id="Employee_ID">
                    <option selected="selected" value="0">All</option>
                    <?php
                        $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from 
                            tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp where
                            ppl.Patient_Payment_ID = pp.Patient_Payment_ID and
                            pp.Employee_ID = emp.Employee_ID group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
                            $num = mysqli_num_rows($select_details);
                            if($num > 0){
                                while ($data = mysqli_fetch_array($select_details)) {
                    ?>
                                    <option value="<?php echo $data['Employee_ID']; ?>"><?php echo ucwords(strtolower($data['Employee_Name'])); ?></option>

                    <?php       }
                            }
                    ?>
                </select>
            </td>
            <td width="10%"><input type='text' name='Start_Receipt' id='Start_Receipt' placeholder="Start Receipt" style="text-align: center;" autocomplete="off"></td>
            <td width="2%"></td>
            <td width="10%"><input type='text' name='End_Receipt' id='End_Receipt' placeholder="End Receipt" style="text-align: center;" autocomplete="off"></td>
            <td style='text-align: center;'>
                <select name='Sponsor_ID' id='Sponsor_ID'>
                    <option selected='selected' value="0">All</option>
                    <?php
                        $data = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor") or die(mysqli_error($conn));
                        while($row = mysqli_fetch_array($data)){
                    ?>
                            <option value="<?php echo $row['Sponsor_ID']; ?>"><?php echo $row['Guarantor_Name']; ?></option>
                    <?php
                        }
                    ?>
                </select>
            </td>
            <td style='text-align: center;' width="7%"><input type='button' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER' onclick="Filter_Transactions()"></td>
            <td style='text-align: center;' width="7%"><input type='button' name='Preview_Button' id='Preview_Button' class='art-button-green' value='PREVIEW' onclick="Preview_Report()"></td>
        </tr>
    </table>
</center>
</fieldset>
<center id="Transactions_Area">
<fieldset style='overflow-y:scroll; height:410px; background-color: white;'>
    <table width="100%">
        <tr><td colspan="9"><hr></td></tr>
        <tr>
            <td width="3%"><b>SN</b></td>
            <td><b>PATIENT NAME</b></td>
            <td width="14%"><b>SPONSOR NAME</b></td>
            <td width="14%"><b>EMPLOYEE CREATED</b></td>
            <td width="10%"><b>RECEIPT #</b></td>
            <td width="12%"><b>RECEIPT DATE</b></td>
            <td width="10%"><b>BILLING TYPE</b></td>
            <td width="10%"><b>STATUS</b></td>
            <td width="10%" style="text-align: right;"><b>TOTAL</b>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr><td colspan="9"><hr></td></tr>
    </table>
</fieldset>
</center>
<script type="text/javascript">
    function Filter_Transactions(){
        var Employee_ID = document.getElementById("Employee_ID").value;
        var Start_Receipt = document.getElementById("Start_Receipt").value;
        var End_Receipt = document.getElementById("End_Receipt").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        if(Start_Receipt != null && Start_Receipt != '' &&  End_Receipt != null && End_Receipt != ''){
            document.getElementById('Transactions_Area').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            document.getElementById("Start_Receipt").style = 'border: 2px solid black; text-align: center;';
            document.getElementById("End_Receipt").style = 'border: 2px solid black; text-align: center;';
            if (window.XMLHttpRequest) {
                myObjectTransaction = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectTransaction = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectTransaction.overrideMimeType('text/xml');
            }

            myObjectTransaction.onreadystatechange = function () {
                dataTrans = myObjectTransaction.responseText;
                if (myObjectTransaction.readyState == 4) {
                    document.getElementById("Transactions_Area").innerHTML = dataTrans;
                }
            }; //specify name of function that will handle server response........

            myObjectTransaction.open('GET', 'Audit_Trail_Filter_Transaction.php?Employee_ID='+Employee_ID+'&Start_Receipt='+Start_Receipt+'&End_Receipt='+End_Receipt+'&Sponsor_ID='+Sponsor_ID, true);
            myObjectTransaction.send();
        }else{
            if(Start_Receipt=='' || Start_Receipt == null){
                document.getElementById("Start_Receipt").focus();
                document.getElementById("Start_Receipt").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Start_Receipt").style = 'border: 2px solid black; text-align: center;';
            }
            if(End_Receipt=='' || End_Receipt == null){
                document.getElementById("End_Receipt").focus();
                document.getElementById("End_Receipt").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("End_Receipt").style = 'border: 2px solid black; text-align: center;';
            }
        }
    }
</script>

<script type="text/javascript">
    function Preview_Report(){
        var Employee_ID = document.getElementById("Employee_ID").value;
        var Start_Receipt = document.getElementById("Start_Receipt").value;
        var End_Receipt = document.getElementById("End_Receipt").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        if(Start_Receipt != null && Start_Receipt != '' &&  End_Receipt != null && End_Receipt != ''){
            document.getElementById("Start_Receipt").style = 'border: 2px solid black; text-align: center;';
            document.getElementById("End_Receipt").style = 'border: 2px solid black; text-align: center;';
            window.open("audittrailcollection.php?Employee_ID="+Employee_ID+"&Start_Receipt="+Start_Receipt+"&End_Receipt="+End_Receipt+"&Sponsor_ID="+Sponsor_ID+"PreviewReport=PreviewReportThisPage","_blank");
        }else{
            if(Start_Receipt=='' || Start_Receipt == null){
                document.getElementById("Start_Receipt").focus();
                document.getElementById("Start_Receipt").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Start_Receipt").style = 'border: 2px solid black; text-align: center;';
            }
            if(End_Receipt=='' || End_Receipt == null){
                document.getElementById("End_Receipt").focus();
                document.getElementById("End_Receipt").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("End_Receipt").style = 'border: 2px solid black; text-align: center;';
            }
        }
    }
</script>

<?php
    include("./includes/footer.php");
?>    