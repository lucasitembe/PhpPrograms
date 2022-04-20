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
			echo "<a href='epaymentcollectionreports.php?ePaymentCollectionReports=ePaymentCollectionReportsThisForm' class='art-button-green'>BACK</a>";
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
<fieldset>
<center>
    <table width=100%>
        <tr>
        	<td style="text-align: right;">
        		<b>Category</b>
			</td>
			<td>
				<select name="Item_Category_ID" id="Item_Category_ID">
					<option selected="selected" value="0">All</option>
					<?php
						$select_details = mysqli_query($conn,"select Item_Category_ID, Item_Category_Name from tbl_item_category order by Item_Category_Name") or die(mysqli_error($conn));
		    				$num = mysqli_num_rows($select_details);
		    				if($num > 0){
		    					while ($data = mysqli_fetch_array($select_details)) {
					?>
									<option value="<?php echo $data['Item_Category_ID']; ?>"><?php echo ucwords(strtolower($data['Item_Category_Name'])); ?></option>

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
                <input type='text' name='Start_Date' id='Date_From' style='text-align: center;' placeholder='Start Date' readonly='readonly' value='<?php echo $Today; ?>'>
            </td>
            <td style='text-align: right;'><b>End Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='Date_To' style='text-align: center;' placeholder='End Date' readonly='readonly' value='<?php echo $Today; ?>'>
            </td>
            <td style='text-align: center;' width=10%>
                <input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='filter_list()'>
            </td>
        </tr>
    </table>
</center>
</fieldset>

<script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
    $('#Date_From').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:    'now'
    });
    $('#Date_From').datetimepicker({value:'',step:30});
    $('#Date_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:'now'
    });
    $('#Date_To').datetimepicker({value:'',step:30});
    </script>
    <!--End datetimepicker-->
   
<fieldset style='overflow-y: scroll; height: 440px;background-color:white;margin-top:20px;' id='Fieldset_List'>
            <!--<legend align='center' style="background-color:#006400;color:white;padding:5px;"><b>DISPENSED LIST <?php echo strtoupper($Page_Title); ?> </b></legend>-->
        <center>
            
		<?php
		    $temp = 0;
		    $total_cash = 0;
		    $total_credit = 0;
		    $total_cancelled = 0;
		   	$sub_total_cash = 0;
		    $sub_total_credit = 0;
		    $sub_total_cancelled = 0;
		    
		    $grand_total_cash = 0;
		    $grand_total_credit = 0;
		    $grand_total_cancelled = 0;
		    $general_grand_total = 0;
		    $Quantity = 0;
		    $Grand_Quantity = 0;
		   	
		   	$control = 'yes';

		    
		    //get all categories
		    $select_details = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from
                                                    tbl_item_category ic, tbl_item_subcategory isu, tbl_items i, tbl_patient_payment_item_list ppl where
                                                    ic.Item_Category_ID = isu.Item_Category_ID and
                                                    isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                                                    i.Item_ID = ppl.Item_ID group by ic.Item_Category_ID order by ic.Item_Category_Name") or die(mysqli_error($conn));
		    $num = mysqli_num_rows($select_details);
		    
		    if($num > 0){
		    	
			while($row = mysqli_fetch_array($select_details)){
				echo "<table width=100% border=1>";
				echo '<tr><td colspan="7" style="text-align: left;"><b>'.strtoupper($row['Item_Category_Name']).'</b></td></tr>';
				echo '<tr><td colspan="7"><hr></td></tr>';
			    //get sub categories
			    $Item_Category_ID = $row['Item_Category_ID'];
			    $select_sub = mysqli_query($conn,"select * from tbl_item_subcategory where Item_Category_ID = '$Item_Category_ID' order by Item_Subcategory_Name") or die(mysqli_error($conn));
			    $no = mysqli_num_rows($select_sub);
			    if($no > 0){
					while($data = mysqli_fetch_array($select_sub)){
						if($control == 'yes'){
							echo "<tr id='thead'>
								    <td width=5%><b>SN</b></td>
								    <td><b>SUBCATEGORY NAME</b></td>
								    <td width=5% style='text-align: right;'><b>QUANTITY</b></td>
								    <td style='text-align: right;' width='14%'><b>CASH</b></td>
								    <td style='text-align: right;' width='14%'><b>CREDIT</b></td>
								    <td style='text-align: right;' width='14%'><b>CANCELLED</b></td>
								    <td style='text-align: right;' width='14%'><b>TOTAL COLLECTED</b></td>
								</tr>";
								$control = 'no';
						}
						//get all transaction based on selected sub category
					    $Item_Subcategory_ID = $data['Item_Subcategory_ID'];
					    $Item_Subcategory_Name = $data['Item_Subcategory_Name'];

					     
					     
					    //displaying data...
					    	echo "<tr>
								    <td width=5%>".++$temp."</b></td>
								    <td>".ucwords(strtolower($Item_Subcategory_Name))."</td>
								    <td style='text-align: right;'>".$Quantity."</td>
								    <td style='text-align: right;'>".number_format($total_cash)."</td>
								    <td style='text-align: right;'>".number_format($total_credit)."</td>
								    <td style='text-align: right;'>".number_format($total_cancelled)."</td>
								    <td style='text-align: right;'>".number_format($total_cash + $total_credit)."</td>
								</tr>";
						$sub_total_cash = $sub_total_cash + $total_cash;
					    $sub_total_credit = $sub_total_credit + $total_credit;
					    $sub_total_cancelled = $sub_total_cancelled + $total_cancelled;
						$total_cash = 0;
						$total_credit = 0;
						$total_cancelled = 0;
						$Quantity = 0;
					}
					$temp = 0;
					$control = 'yes';
			    }
			    echo "<tr><td colspan='7'><hr></td></tr>";
			    echo "<tr>
					    <td colspan='2' style='text-align: left;'><b>TOTAL</b></td>
					    <td style='text-align: right;'>".$Grand_Quantity."</td>
					    <td style='text-align: right;'>".number_format($sub_total_cash)."</td>
					    <td style='text-align: right;'>".number_format($sub_total_credit)."</td>
					    <td style='text-align: right;'>".number_format($sub_total_cancelled)."</td>
					    <td style='text-align: right;'>".number_format($sub_total_cash + $sub_total_credit)."</td>
					</tr>";
			    echo "<tr><td colspan='7'><hr></td></tr>";
			    echo "</table><br/>";
			    $sub_total_cash = 0;
			    $sub_total_credit = 0;
			    $sub_total_cancelled = 0;
			    $Grand_Quantity = 0;
			    
			}
		    }
			echo "<table width='100%'>
					<tr><td colspan='7'><hr></td></tr>
            		<tr><td colspan='2' style='text-align: left;'><b>GRAND TOTAL</b></td>
                        <td style='text-align: right;' width='14%'><b>".number_format($grand_total_cash)."</b></td>
                        <td style='text-align: right;' width='14%'><b>".number_format($grand_total_credit)."</b></td>
                        <td style='text-align: right;' width='14%'><b>".number_format($grand_total_cancelled)."</b></td>
                        <td style='text-align: right;' width='14%'><b>".number_format($grand_total_cash + $grand_total_credit)."</b></td>
                    </tr>";
            echo "<tr><td colspan='7'><hr></td></tr>";
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
			<b>TOTAL CASH ~ <?php //echo number_format($grand_total_cash); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<b>TOTAL CREDIT ~ <?php //echo number_format($grand_total_credit); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<b>TOTAL CANCELLED ~ <?php //echo number_format($grand_total_cancelled); ?></b>
		</td>-->
		<td style="text-align: right;" id="Report_Button_Area">
			<a href="epaymentrevenuecollectiondetailsreport.php?Date_From=<?php echo $Today; ?>&Date_To=<?php echo $Today; ?>&Billing_Type=All&Item_Category_ID=0&Sponsor_ID=0&ePaymentRevenueCollectionDetails=ePaymentRevenueCollectionDetailsThisPage" class="art-button-green" target="_blank">
				PREVIEW REPORT
			</a>
		</td>
	</tr>
</table>

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
    function open_Dialog(Employee_ID,Date_From,Date_To,Billing_Type,Sponsor_ID){
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
	
	myObjectGetDetails.open('GET','generalperformancereportdetails.php?Employee_ID='+Employee_ID+'&Date_From='+Date_From+'&Date_To='+Date_To+'&Billing_Type='+Billing_Type+'&Sponsor_ID='+Sponsor_ID,true);
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
		var Item_Category_ID = document.getElementById("Item_Category_ID").value;
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
                myObject2.open('GET','ePayment_Revenue_Collection_Details.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Billing_Type='+Billing_Type+'&Item_Category_ID='+Item_Category_ID+'&Sponsor_ID='+Sponsor_ID,true);
                myObject2.send();
            }else{
                myObject2.open('GET','ePayment_Revenue_Collection_Details.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Item_Category_ID='+Item_Category_ID+'&Sponsor_ID='+Sponsor_ID,true);
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



<script>
    function Display_Report_Button(){
		var Date_From = document.getElementById("Date_From").value;
		var Date_To = document.getElementById("Date_To").value;
		var Billing_Type = document.getElementById("Bill_Type").value;
		var Item_Category_ID = document.getElementById("Item_Category_ID").value;
		var Sponsor_ID = document.getElementById("Sponsor_ID").value;
		
        document.getElementById("Report_Button_Area").innerHTML = '<a href="epaymentrevenuecollectiondetailsreport.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Billing_Type='+Billing_Type+'&Item_Category_ID='+Item_Category_ID+'&Sponsor_ID='+Sponsor_ID+'&ePaymentRevenueCollectionDetails=ePaymentRevenueCollectionDetailsThisPage" class="art-button-green" target="_blank">PREVIEW REPORT</a>';
    }
</script>

<?php
    include("./includes/footer.php");
?>