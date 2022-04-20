<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['General_Ledger'])){
	    if($_SESSION['userinfo']['General_Ledger'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }
    
    //get today date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $Today = $original_Date;
    }
    
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['General_Ledger'] == 'yes'){
			echo "<a href='generalledgercenter.php?GeneralLegdgerCenter=GeneralLegdgerCenterThisPage' class='art-button-green'>BACK</a>";
		}
	}
?>

<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $Today = $row['today'];
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

<br/><br/>
<center>
    <table width=100%>
        <tr>
        	<td style="text-align: right;">
        		<b>Employee Name</b>
			</td>
			<td>
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

					<?php 		}
							}
					?>
				</select>
			</td>
			<td style="text-align: right;"><b>Sponsor Name</b></td>
			<td>
				<select name="Sponsor_ID" id="Sponsor_ID">
					<option selected="selected" value="0">All</option>
					<?php
						$select = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
						$num = mysqli_num_rows($select);
						if($num > 0){
							while ($data = mysqli_fetch_array($select)) {
					?>
								<option value="<?php echo $data['Sponsor_ID']; ?>"><?php echo $data['Guarantor_Name']; ?></option>
					<?php
							}
						}
					?>
				</select>
			</td>
            <td style='text-align: right;'><b>Bill Type</b></td>
            <td>
                <select name='Bill_Type' id='Bill_Type' required='required'>
                    <option selected='selected'>All</option>
                    <option>Outpatient</option>
                    <option>Inpatient</option>
                </select>
            </td>
            <td style='text-align: right;'><b>Start Date</b></td>
            <td><input type='text' name='Start_Date' id='Date_From' style='text-align: center;' placeholder='~~~ Start Date ~~~' readonly='readonly'></td>
            <td style='text-align: right;'><b>End Date</b></td>
            <td><input type='text' name='Start_Date' id='Date_To' style='text-align: center;' placeholder='~~~ End Date ~~~' readonly='readonly'></td>
            <td style='text-align: center;' width=10%><input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='filter_list()'></td>
        </tr>
    </table>
</center>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#Date_From').datetimepicker({
	    dayOfWeekStart : 1,
	    lang:'en',
    });
    $('#Date_From').datetimepicker({value:'',step:01});

    $('#Date_To').datetimepicker({
	    dayOfWeekStart : 1,
	    lang:'en',
    });
    $('#Date_To').datetimepicker({value:'',step:01});
</script>
   
<fieldset style='overflow-y: scroll; height: 400px;background-color:white;margin-top:20px;' id='Fieldset_List'>
	<legend><b>REVENUE SUMMARY PATIENT CONTRIBUTIONS</b></legend>
    <table width="100%">
		<tr>
			<td colspan='6'><hr></td></tr><tr>
		    <td width=5%><b>SN</b></td>
		    <td><b>PARTICULAR TYPE</b></td>
		    <td style='text-align: right;' width="15%"><b>CASH</b></td>
		    <td style='text-align: right;' width="15%"><b>CREDIT</b></td>
		    <td style='text-align: right;' width="15%"><b>TOTAL</b>&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<tr><td colspan='6'><hr></td></tr>
    </table>
</fieldset>
<fieldset>
	<table width="100%">
		<tr>
			<td style="text-align: right;" id="Report_Button_Area">
				<input type="button" name="Report_Button" id="Report_Button" class="art-button-green" value="PREVIEW REPORT" onclick="Preview_Report()">
			</td>
		</tr>
	</table>
</fieldset>

<script>
    function filter_list(){
		var Date_From = document.getElementById("Date_From").value;
		var Date_To = document.getElementById("Date_To").value;
		var Billing_Type = document.getElementById("Bill_Type").value;
		var Employee_ID = document.getElementById("Employee_ID").value;
		var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        if (Date_From != null && Date_From != '' && Date_To != null && Date_To != ''){
        	document.getElementById('Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
			document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';

            if(window.XMLHttpRequest) {
                myObject2 = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject2.overrideMimeType('text/xml');
            }
            
            myObject2.onreadystatechange = function (){
                data2 = myObject2.responseText;
                if (myObject2.readyState == 4) {
                    document.getElementById('Fieldset_List').innerHTML = data2;
                }
		    }; //specify name of function that will handle server response........
	        myObject2.open('GET','Revenue_Summary_Contributions.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Billing_Type='+Billing_Type+'&Employee_ID='+Employee_ID+'&Sponsor_ID='+Sponsor_ID,true);
	        myObject2.send();
        }else{
            if(Date_From == '' || Date_From == null){
                document.getElementById("Date_From").style = 'border: 3px solid red; text-align: center;';
            }else{
            	document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            }
            if(Date_To =='' || Date_To == null){
                document.getElementById("Date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
            	document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            }
        }
    }
</script>

<script type="text/javascript">
    function Preview_Report(){
        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;
        var Billing_Type = document.getElementById("Bill_Type").value;
        var Employee_ID = document.getElementById("Employee_ID").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        if (Date_From != null && Date_From != '' && Date_To != null && Date_To != ''){
            document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            window.open("revenuesummarycontributionsreport.php?Start_Date="+Date_From+"&End_Date="+Date_To+"&Billing_Type="+Billing_Type+"&Employee_ID="+Employee_ID+"&Sponsor_ID="+Sponsor_ID+"&RevenueCollectionBySponsorReport=RevenueCollectionBySponsorReportThisPage","_blank");
        }else{
            if(Date_From == '' || Date_From == null){
                document.getElementById("Date_From").style = 'border: 3px solid red; text-align: center;';
            } else {
                document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            }
            if(Date_To =='' || Date_To == null){
                document.getElementById("Date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            }
        }
    }
</script>

<script>
    $(document).ready(function () {
        $('select').select2();
    });
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<?php
    include("./includes/footer.php");
?>