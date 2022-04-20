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


<!-- new date function--> 
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $Today = $row['today'];
    }
?>
<!-- end of the function -->


   
  
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
						$select_details = mysqli_query($conn,"SELECT emp.Employee_ID, emp.Employee_Name from tbl_patient_payments pp, tbl_employee emp where
							emp.Employee_ID = pp.Employee_ID group by pp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));

		    					while ($data = mysqli_fetch_array($select_details)) {
					?>
									<option value="<?php echo $data['Employee_ID']; ?>"><?php echo ucwords(strtolower($data['Employee_Name'])); ?></option>

					<?php 		}
							
					?>
				</select>
			</td>
			<td style="text-align: right;"><b>Sponsor Name</b></td>
			<td>
				<select name="Sponsor_ID" id="Sponsor_ID">
					<option selected="selected" value="0">All</option>
					<?php
						$select = mysqli_query($conn,"SELECT Guarantor_Name, Sponsor_ID from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
							while ($data = mysqli_fetch_array($select)) {
					?>
								<option value="<?php echo $data['Sponsor_ID']; ?>"><?php echo $data['Guarantor_Name']; ?></option>
					<?php
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
			<td style='text-align: center;'><b>Transaction Channel</b></td>
            <td>
                            <select name='transaction_channel' id='transaction_channel' onchange='filter_list()'>
				    			<option value='All'selected='selected'>All</option>
                                <option value='crdb_pos'>CRDB</option>
                                <option value='to_crdb'>CRDB MOBILE</option>
                                <option value='to_nmb'>NMB</option>
                                <option value='manual'>MANUAL</option>
			    			</select>
                        </td>  
 
            <td style='text-align: right;'><b>Start Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='Date_From' style='text-align: center;' placeholder='Start Date' readonly='readonly' value='<?php echo $Today; ?>'>
            </td>
            <td style='text-align: right;'><b>End Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='Date_To' style='text-align: center;' placeholder='End Date' value='<?php echo $Today; ?>'>
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
    $('#Date_From').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:    'now'
    });
    $('#Date_From').datetimepicker({value:'',step:01});
    $('#Date_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:'now'
    });
    $('#Date_To').datetimepicker({value:'',step:01});
    </script>
    <!--End datetimepicker-->
   
<fieldset style='overflow-y: scroll; height: 350px;background-color:white;margin-top:20px;' id='Fieldset_List'>
            <!--<legend align='center' style="background-color:#006400;color:white;padding:5px;"><b>DISPENSED LIST <?php echo strtoupper($Page_Title); ?> </b></legend>-->
        <center>
            <table width=100% border=1>
            </td>
        </tr>
            </table>
        </center>
</fieldset>

<!--popup window -->
<div id="Display_Employee_Transactions_Details" style="width:50%;" >
    <center id='Details_Area'>
	<table width=100% style='border-style: none;'>
	    <tr>
		<td>
		    
		</td>
	    </tr>
	</table>
    </center>
</div>


<script>
    function open_Dialog(Employee_ID,Date_From,Date_To,Billing_Type,Sponsor_ID,transaction_channel){
	if(window.XMLHttpRequest){
	    myObjectGetDetails = new XMLHttpRequest();
	}else if(window.ActiveXObject){
	    myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
	    myObjectGetDetails.overrideMimeType('text/xml');
	}
	myObjectGetDetails.onreadystatechange = function (){
	    data29 = myObjectGetDetails.responseText;
	    if (myObjectGetDetails.readyState == 4) {
		document.getElementById('Details_Area').innerHTML = data29;
		$("#Display_Employee_Transactions_Details").dialog("open");
	    }
	}; //specify name of function that will handle server response........
	
	myObjectGetDetails.open('GET','generalperformancereportdetails.php?Employee_ID='+Employee_ID+'&Date_From='+Date_From+'&Date_To='+Date_To+'&Billing_Type='+Billing_Type+'&Sponsor_ID='+Sponsor_ID+'&transaction_channel='+transaction_channel,true);
	myObjectGetDetails.send();
    }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<!--<script src="script.js"></script>-->
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<!--<script src="script.responsive.js"></script>-->

<script>
   $(document).ready(function(){
      $("#Display_Employee_Transactions_Details").dialog({ autoOpen: false, width:'90%',height:500, title:'TRANSACTIONS DETAIL',modal: true});
      
//      $('.ui-dialog-titlebar-close').click(function(){
//	 Get_Transaction_List();
//      });
      
   });
</script>
<!-- end popup window -->

<script>
    function filter_list(){
		var Date_From = document.getElementById("Date_From").value;
		var Date_To = document.getElementById("Date_To").value;
		var Billing_Type = document.getElementById("Bill_Type").value;
		var Employee_ID = document.getElementById("Employee_ID").value;
		var Sponsor_ID = document.getElementById("Sponsor_ID").value;
		var transaction_channel = document.getElementById("transaction_channel").value;

        if (Date_From != null && Date_From != '' && Date_To != null && Date_To != ''){
        	document.getElementById('Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
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
                myObject2.open('GET','General_Performance_Filtered_All.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Billing_Type='+Billing_Type+'&Employee_ID='+Employee_ID+'&Sponsor_ID='+Sponsor_ID+'&transaction_channel='+transaction_channel,true);
                myObject2.send();
            }else{
                myObject2.open('GET','General_Performance_Filtered_All.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Employee_ID='+Employee_ID+'&Sponsor_ID='+Sponsor_ID+'&transaction_channel='+transaction_channel,true);
                myObject2.send();
            }
	    
        }else{
            if(Date_From == '' || Date_From == null){
                document.getElementById("Date_From").style = 'border: 3px solid red';
            }
            if(Date_To =='' || Date_To == null){
                document.getElementById("Date_To").style = 'border: 3px solid red';
            }
        }
    }
</script>




<?php
    include("./includes/footer.php");
?>