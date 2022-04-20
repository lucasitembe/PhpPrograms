<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
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
        //$new_Date = date("Y-m-d", strtotime($original_Date));
        //$Today = $new_Date;
        $Today = $original_Date;
    }
   
    if(isset($_SESSION['Location'])){
	$Location = $_SESSION['Location'];
    }else{
	$Location = '';
    }
    
    if(isset($_SESSION['userinfo'])){
	if(strtolower($_SESSION['Location']) == 'radiology'){
	    echo "<a href='radiologyworkspage.php?RadiologyWorks=RadiologyWorksThisPage' class='art-button-green'>BACK</a>";
	}elseif(strtolower($_SESSION['Location']) == 'procedure'){
	    echo "<a href='Procedure.php?ProcurementWorkPage=ProcurementWorkPageThisPage' class='art-button-green'>BACK</a>";
	}elseif(strtolower($_SESSION['Location']) == 'pharmacy'){
	    echo "<a href='pharmacyreportspage.php?PhrmacyReports=PharmacyReportsThisPage' class='art-button-green'>BACK</a>";
	}elseif(strtolower($_SESSION['Location']) == 'laboratory'){
	    echo "<a href='Laboratory_Reports.php?LaboratoryResultsThisPage=ThisPage' class='art-button-green'>BACK</a>";
	}elseif(strtolower($_SESSION['Location']) == 'surgery'){
	    echo "<a href='theaterworkspage.php?TheaterWorkPage=TheaterWorkPageThisPage' class='art-button-green'>BACK</a>";
	}else{
	    echo "<a href='pharmacyreportspage.php?PhrmacyReports=PharmacyReportsThisPage' class='art-button-green'>BACK</a>";
	}	
    }
?>


<!-- new date function (Contain years, Months and days)--> 
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $Today = $row['today'];
    }
?>
<!-- end of the function -->



<?php
    if(isset($_GET['Billing_Type'])){
        $Billing_Type = $_GET['Billing_Type'];
        if($Billing_Type == 'OutpatientCash'){
            $Page_Title = ' - Outpatient Cash';
        }elseif($Billing_Type == 'OutpatientCredit'){
            $Page_Title = ' - Outpatient Credit';
        }elseif($Billing_Type == 'InpatientCash'){
            $Page_Title = ' - Inpatient Cash';
        }elseif($Billing_Type == 'InpatientCredit'){
            $Page_Title = ' - Inpatient Credit';
        }elseif($Billing_Type == 'PatientFromOutside'){
            $Page_Title = ' - Patient From Outside';
        }else{
            $Page_Title = '';
        }
    }else{
        $Billing_Type = '';
        $Page_Title = '';
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



<script language="javascript" type="text/javascript">
   /* function searchPatient(Patient_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=370px src='Pharmacy_List_Iframe.php?Patient_Name="+Patient_Name+"&Registration_Number="+Registration_Number+"'></iframe>";
    }*/
</script>
<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
	var Billing_Type = '<?php echo $Billing_Type; ?>';
        //document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=320px src='Pharmacy_List_Iframe.php?Patient_Name="+Patient_Name+"&Billing_Type="+Billing_Type+"'></iframe>";
    }
</script>

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
						$select_details = mysqli_query($conn,"SELECT emp.Employee_ID, emp.Employee_Name from 
							tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, tbl_items i where
							ppl.Patient_Payment_ID = pp.Patient_Payment_ID and
							pp.Employee_ID = emp.Employee_ID and
							i.Item_ID = ppl.Item_ID and
							i.Consultation_Type = '$Location' group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
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
            <td>
                <input type='text' name='Start_Date' id='date' style='text-align: center;' placeholder='Start Date' readonly='readonly' value='<?php echo $Today; ?>'>
            </td>
            <td style='text-align: right;'><b>End Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='date2' style='text-align: center;' placeholder='End Date' readonly='readonly' value='<?php echo $Today; ?>'>
            </td>
            <td style='text-align: center;' width=10%>
                <input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='filter_list()'>
            </td>
        </tr>
    </table>
</center>

<script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
    $('#date').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:    'now'
    });
    $('#date').datetimepicker({value:'',step:30});
    $('#date2').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:'now'
    });
    $('#date2').datetimepicker({value:'',step:30});
    </script>
    <!--End datetimepicker-->
   
<fieldset style='overflow-y: scroll; height: 350px;background-color:white;margin-top:20px;' id='Fieldset_List'>
            <legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>General Performance ~ <?php if(isset($_SESSION['Location'])){ echo ucwords(strtolower($_SESSION['Location'])); } ?> </b></legend>
        <center>
            <table width=100% border=1>
                <?php
		    if(isset($_SESSION['Pharmacy'])){
				$Sub_Department_Name = $_SESSION['Pharmacy'];
				$select_sub_department = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'");
				while($row = mysqli_fetch_array($select_sub_department)){
				    $Sub_Department_ID = $row['Sub_Department_ID'];
				}
		    }else{
				$Sub_Department_ID = '';
		    }
		?>
		<?php
		    $temp = 1;
		    $total_cash = 0;
		    $total_credit = 0;
		    $total_cancelled = 0;
		    
		    $grand_total_cash = 0;
		    $grand_total_credit = 0;
		    $grand_total_cancelled = 0;
		    
		    echo "<tr id='thead'>
			    <td width=5%><b>SN</b></td>
			    <td><b>EMPLOYEE NAME</b></td>
			    <td width='12%'style='text-align: right;'><b>CASH</b></td>
			    <td width='12%'style='text-align: right;'><b>CREDIT</b></td>
			    <td width='12%'style='text-align: right;'><b>CANCELED</b></td>
			    <td width='12%'style='text-align: right;'><b>TOTAL COLLECTED</b></td>
			</tr>";
		    echo '<tr><td colspan="6"><hr></td></tr>';
		    
		    /*get employee details (Cashiers)
		    $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp, tbl_items i where
						    pp.Employee_ID = emp.Employee_ID and
						    pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
						    i.Item_ID = ppl.Item_ID and
						    pp.Payment_Date_And_Time between '$Today' and '$Today' and
						    i.Consultation_Type = 'Pharmacy' group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
		    $num = mysqli_num_rows($select_details);
		    
		    if($num > 0){
			/*while($row = mysqli_fetch_array($select_details)){
			    $Employee_ID = $row['Employee_ID']; //cashier id
			    $Employee_Name = ucwords(strtolower($row['Employee_Name'])); //cashier name
			    
			    //filter all transactions based on selected cashier
			    $select = mysqli_query($conn,"select pp.Billing_Type, pp.Transaction_status, ppl.Price, ppl.Quantity, ppl.Discount
						    from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_items i where
						    pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
						    i.Item_ID = ppl.Item_ID and
						    pp.Payment_Date_And_Time between '$Today' and '$Today' and
						    i.Consultation_Type = 'Pharmacy' and
						    pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
			    
			    $num_rows = mysqli_num_rows($select);
			    
			    
			    if($num_rows > 0){
				while($data = mysqli_fetch_array($select)){
				    //calculate total
				    $Billing_Type = $data['Billing_Type'];
				    
				    $Transaction_status = $data['Transaction_status'];
				    $Price = $data['Price'];
				    $Quantity = $data['Quantity'];
				    $Discount = $data['Discount'];
				    
				    $total = (($Price - $Discount) * $Quantity);
				    
				    if(strtolower($Transaction_status) == 'cancelled'){
						$total_cancelled = $total_cancelled + $total;
						//$grand_total_cancelled = $grand_total_cancelled + $total_cancelled;
				    }else{
						if(strtolower($Billing_Type) == 'outpatient cash' or strtolower($Billing_Type) == 'inpatient cash'){
						    $total_cash = $total_cash + $total;
						    //$grand_total_cash = $grand_total_cash + $total_cash;
						}else if(strtolower($Billing_Type) == 'outpatient credit' or strtolower($Billing_Type) == 'inpatient credit'){
						    $total_credit = $total_credit + $total;
						    //$grand_total_credit = $grand_total_credit + $total_credit;
						}
				    }
				}
			    }
		?>
			<tr>
			    <td><?php echo $temp; ?></td>
			    <td><b><?php echo $Employee_Name; ?></b></td>
			    <td style='text-align: right;'><b><?php echo number_format($total_cash); ?></b></td>
			    <td style='text-align: right;'><b><?php echo number_format($total_credit); ?></b></td>
			    <td style='text-align: right;'><b><?php echo number_format($total_cancelled); ?></b></td>
			    <td style='text-align: right;'><b><?php echo number_format($total_cash + $total_credit); ?></b></td>
			</tr>
		    <?php
			    $temp++;

			    $grand_total_cancelled = $grand_total_cancelled + $total_cancelled;
			    $grand_total_cash = $grand_total_cash + $total_cash;
			    $grand_total_credit = $grand_total_credit + $total_credit;

			    $total_cash = 0;
			    $total_credit = 0;
			    $total_cancelled = 0;
			}
		    }*/
		    echo '</table>';
		?>
            </td>
        </tr>
            </table>
        </center>
</fieldset>
<table width="100%">
	<tr>
		<!--<td id="Grand_Total_Area" width="80%" style="text-align: right;">
			<b>TOTAL CASH ~ <?php echo number_format($grand_total_cash); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<b>TOTAL CREDIT ~ <?php echo number_format($grand_total_credit); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<b>TOTAL CANCELLED ~ <?php echo number_format($grand_total_cancelled); ?></b>
		</td>-->
		<td style="text-align: right;" id="Report_Button_Area">
			<?php if(isset($_SESSION['Location'])){ 
				$Location = $_SESSION['Location'];

				?>
				<a href="generalperformancereport.php?Date_From=<?php echo $Today; ?>&Date_To=<?php echo $Today; ?>&Billing_Type=All&Employee_ID=0&Sponsor_ID=0" class="art-button-green" target="_blank">
					PREVIEW REPORT
				</a>
			<?php }?>
		</td>
	</tr>
</table>

<script>
    function filter_list(){
		var Date_From = document.getElementById("date").value;
		var Date_To = document.getElementById("date2").value;
		var Billing_Type = document.getElementById("Bill_Type").value;
		var Employee_ID = document.getElementById("Employee_ID").value;
		var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        if (Date_From != null && Date_From != '' && Date_To != null && Date_To != ''){
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
                    Display_Report_Button();
                }
	    }; //specify name of function that will handle server response........
            
	    if (Billing_Type != null && Billing_Type != '') {
                myObject2.open('GET','General_Performance_Filtered.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Billing_Type='+Billing_Type+'&Employee_ID='+Employee_ID+'&Sponsor_ID='+Sponsor_ID,true);
                myObject2.send();
            }else{
                myObject2.open('GET','General_Performance_Filtered.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Employee_ID='+Employee_ID+'&Sponsor_ID='+Sponsor_ID,true);
                myObject2.send();
            }
	    
        }else{
            if(Date_From == '' || Date_From == null){
                document.getElementById("date").style = 'border: 3px solid red';
            }
            if(Date_To =='' || Date_To == null){
                document.getElementById("date2").style = 'border: 3px solid red';
            }
        }
    }
	function print_excell(){
            var date_From = $('#date').val();
            var date_To = $('#date2').val();
            var Employee_ID=$('#Employee_ID').val();
            var Billing_Type = $("#Bill_Type").val();
			var Sponsor_ID = $("#Sponsor_ID").val()
            var Location = '<?= $Location ?>';

            // check dates 
            if(date_From == ""){
                alert("Enter Start Date");
                exit;
            }else if(date_To == ""){
                alert("Enter End Date");
                exit;
            }else{
                window.open("generalperformancereport_excell.php?date_From="+date_From+"&date_To="+date_To + "&Employee_ID=" + Employee_ID + '&Location=' + Location + '&Billing_Type=' + Billing_Type + '&Sponsor_ID=' + Sponsor_ID);
            }
        }
</script>



<script>
    function Display_Report_Button(){
		var Date_From = document.getElementById("date").value;
		var Date_To = document.getElementById("date2").value;
		var Billing_Type = document.getElementById("Bill_Type").value;
		var Employee_ID = document.getElementById("Employee_ID").value;
		var Sponsor_ID = document.getElementById("Sponsor_ID").value;
		
		/*if(window.XMLHttpRequest) {
                myObjectUpdateLink = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectUpdateLink = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectUpdateLink.overrideMimeType('text/xml');
            }
            
            myObjectUpdateLink.onreadystatechange = function (){
                data220 = myObjectUpdateLink.responseText;
                if (myObjectUpdateLink.readyState == 4) {
                    document.getElementById('Report_Button_Area').innerHTML = data220;
                    Display_Report_Button();
                }
	    }; //specify name of function that will handle server response........
        
        myObjectUpdateLink.open('GET','Display_Report_Button.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Billing_Type='+Billing_Type+'&Employee_ID='+Employee_ID+'&Sponsor_ID='+Sponsor_ID,true);
        myObjectUpdateLink.send();
        */
        document.getElementById("Report_Button_Area").innerHTML = '<a href="generalperformancereport.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Billing_Type='+Billing_Type+'&Employee_ID='+Employee_ID+'&Sponsor_ID='+Sponsor_ID+'" class="art-button-green" target="_blank">PREVIEW REPORT</a><input type="button" onclick="print_excell()" class="art-button-green" value="PRINT REPORT EXCEL">';
    }
</script>

<?php
    include("./includes/footer.php");
?>