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
    <legend align="right">DAILY COLLECTION REPORT</legend>
    <table width="100%">
        <tr>
            <td style='text-align: center;'><b>Employee</b></td>
            <td style='text-align: center;'><b>Start Date</b></td>
            <td width="3%"></td>
            <td style='text-align: center;'><b>End Date</b></td>
            <td style='text-align: center;'><b>Sponsor Name</b></td>
        </tr>
        <tr>
            <td width="25%" style="text-align: center;">
                <select name="Employee_ID" id="Employee_ID">
                    <option selected="selected" value="0">All</option>
                    <?php
                        $select_details = mysqli_query($conn,"SELECT emp.Employee_ID, emp.Employee_Name from tbl_patient_payments pp, tbl_employee emp where
                            pp.Employee_ID = emp.Employee_ID group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
                            $num = mysqli_num_rows($select_details);
                            if($num > 0){
                                while ($data = mysqli_fetch_array($select_details)) {
                    ?>
                                    <option value="<?php echo $data['Employee_ID']; ?>"><?php echo ucwords(strtolower($data['Employee_Name'])); ?></option>

                    <?php       
                                }
                            }
                    ?>
                </select>
            </td>
            <td width="15%"><input type="text" name="date" id="date" placeholder="~~~ ~~~ Start Date ~~~ ~~~" autocomplete="off" style="text-align: center;" readonly="readonly"></td>
            <td></td>
            <td width="15%"><input type="text" name="date2" id="date2" placeholder="~~~ ~~~ End Date ~~~ ~~~" autocomplete="off" style="text-align: center;" readonly="readonly"></td>
            <td style='text-align: center;'>
                <select name='Sponsor_ID' id='Sponsor_ID'>
                    <option selected='selected' value="0">All</option>
                    <?php
                        $data = mysqli_query($conn,"SELECT Sponsor_ID, Guarantor_Name from tbl_sponsor") or die(mysqli_error($conn));
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
            <td style='text-align: center;' width="7%"><input type='button' name='Preview_Excel_Button' id='Preview_Excel_Button' class='art-button-green' value='EXPORT TO EXCEL' onclick="Preview_Excel_Report()"></td>
        </tr>
    </table>
</center>
</fieldset>
<center id="Transactions_Area">
<fieldset style='overflow-y:scroll; height:360px; background-color: white;'>
    <table width="100%">
        <tr><td colspan="6"><hr></td></tr>
        <tr>
            <td width="3%"><b>SN</b></td>
            <td><b>TRANSACTIONS DATE</b></td>
            <td width="13%" style="text-align: right;"><b>CASH</b>&nbsp;&nbsp;&nbsp;</td>
            <td width="13%" style="text-align: right;"><b>CREDIT</b>&nbsp;&nbsp;&nbsp;</td>
            <td width="13%" style="text-align: right;"><b>CANCELLED</b>&nbsp;&nbsp;&nbsp;</td>
            <td width="13%" style="text-align: right;"><b>TOTAL</b>&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr><td colspan="6"><hr></td></tr>
    </table>
</fieldset>
</center>
<script type="text/javascript">
    function Filter_Transactions(){
        var Employee_ID = document.getElementById("Employee_ID").value;
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        if(Start_Date != null && Start_Date != '' &&  End_Date != null && End_Date != ''){
            document.getElementById('Transactions_Area').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            document.getElementById("date").style = 'border: 2px solid black; text-align: center;';
            document.getElementById("date2").style = 'border: 2px solid black; text-align: center;';
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

            myObjectTransaction.open('GET', 'Daily_Collection_Report.php?Employee_ID='+Employee_ID+'&Start_Date='+Start_Date+'&End_Date='+End_Date+'&Sponsor_ID='+Sponsor_ID, true);
            myObjectTransaction.send();
        }else{
            if(Start_Date=='' || Start_Date == null){
                document.getElementById("date").focus();
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date").style = 'border: 2px solid black; text-align: center;';
            }
            if(End_Date=='' || End_Date == null){
                document.getElementById("date2").focus();
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date2").style = 'border: 2px solid black; text-align: center;';
            }
        }
    }
</script>

<script type="text/javascript">
    function Preview_Report(){
        var Employee_ID = document.getElementById("Employee_ID").value;
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        if(Start_Date != null && Start_Date != '' &&  End_Date != null && End_Date != ''){
            document.getElementById("date").style = 'border: 2px solid black; text-align: center;';
            document.getElementById("date2").style = 'border: 2px solid black; text-align: center;';
            window.open("dailycollection.php?Employee_ID="+Employee_ID+"&Start_Date="+Start_Date+"&End_Date="+End_Date+"&Sponsor_ID="+Sponsor_ID+"PreviewReport=PreviewReportThisPage","_blank");
        }else{
            if(Start_Date=='' || Start_Date == null){
                document.getElementById("date").focus();
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date").style = 'border: 2px solid black; text-align: center;';
            }
            if(End_Date=='' || End_Date == null){
                document.getElementById("date2").focus();
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date2").style = 'border: 2px solid black; text-align: center;';
            }
        }
    }
    function Preview_Excel_Report(){
        var Employee_ID = document.getElementById("Employee_ID").value;
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        if(Start_Date != null && Start_Date != '' &&  End_Date != null && End_Date != ''){
            document.getElementById("date").style = 'border: 2px solid black; text-align: center;';
            document.getElementById("date2").style = 'border: 2px solid black; text-align: center;';
            window.location.href="dailycollectionexcelreport.php?Employee_ID="+Employee_ID+"&Start_Date="+Start_Date+"&End_Date="+End_Date+"&Sponsor_ID="+Sponsor_ID;
        }else{
            if(Start_Date=='' || Start_Date == null){
                document.getElementById("date").focus();
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date").style = 'border: 2px solid black; text-align: center;';
            }
            if(End_Date=='' || End_Date == null){
                document.getElementById("date2").focus();
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date2").style = 'border: 2px solid black; text-align: center;';
            }
        }
    }
</script>

<?php
    include("./includes/footer.php");
?>    