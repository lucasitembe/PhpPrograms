<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
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
    if(isset($_SESSION['Pharmacy_ID'])){
		$Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
		$Sub_Department_ID = 0;
    }
    if(isset($_GET['Sub_Department_ID'])){
       $Sub_Department_ID=$_GET['Sub_Department_ID'];
    }
    if(isset($_GET['Start_Date'])){
       $Start_Date=$_GET['Start_Date'];
    }
    if(isset($_GET['End_Date'])){
       $End_Date=$_GET['End_Date'];
    }
    
    
    if(isset($_GET['report_type'])){
        $report_type = mysqli_real_escape_string($conn,$_GET['report_type']);
    }else{
        $report_type = '';
    }
    
    $filter_report_type="";
    if($report_type!='All'){
       if($report_type!='Msamaha'){
           $filter_report_type=" AND Transaction_Type='$report_type'";
       } 
    }
    //get sub department name
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
		while($data = mysqli_fetch_array($select)){
		    $Sub_Department_Name = $data['Sub_Department_Name'];
		}
    }else{
		$Sub_Department_Name = '';
    }
?>

<?php if(isset($_SESSION['userinfo'])){ 
    if(isset($_GET['Sub_Department_ID'])){
       $Sub_Department_ID=$_GET['Sub_Department_ID'];
       ?>
    <a href='dispencing_n_profit_and_loss_report.php' class='art-button-green'>BACK</a>
<?php 
    }else{
    ?>
    <a href='pharmacyreportspage.php?PhrmacyReports=PharmacyReportsThisPage' class='art-button-green'>BACK</a>
<?php  }

    } ?>
<br/><br/>

<?php
	//getsponsor details
	$query = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
	$dataSponsor = '';
	$dataSponsor.='<option value="All">All Sponsors</option>';

	while ($row = mysqli_fetch_array($query)) {
	    $dataSponsor.= '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
	}
    //generate start date & end date
//    $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
//    while($date = mysqli_fetch_array($sql_date_time)){
//        $Current_Date_Time = $date['Date_Time'];
//    }
//    $Filter_Value = substr($Current_Date_Time,0,11);
//    $Start_Date = $Filter_Value.' 00:00';
//    $End_Date = $Current_Date_Time;

    $Title = '<tr><td colspan="4"><hr></td></tr>
			<tr>
			    <td width=7%><b>SN</b></td>
			    <td width=51%><b>ITEM NAME</b></td>
			    <td><b>QUANTITY DISPENSED</b></td>
			    <td><b>BALANCE</b></td>
			</tr>
			<tr><td colspan="4"><hr></td></tr>';
?>
<fieldset>
	<center>
		<table width="100%">    
                    <tr class="hide">
				<td style='text-align: right;'><b class="hide">Start Date</b></td>
                                <td style="width:10%">
					<input type='text' id='date_From'class="hide" name='Start_Date' readonly='readonly' style='text-align: center;' placeholder='Select Start Date' size=20 value='<?php echo $Start_Date; ?>'>
				</td>
				<td style='text-align: right;' class="hide"><b>End Date</b></td>
				<td style="width:11%">
					<input type='text' id='date_To' class="hide" name='Start_Date' readonly='readonly' style='text-align: center;' placeholder='Select End Date' size=20 value='<?php echo $End_Date; ?>'>
				</td>
				<td>
                                    <select id="sponsorID" style='text-align: center;padding:5px; width: 100%;'>
                                        <?php echo $dataSponsor ?>
                                    </select>
				</td>
				<td style="text-align: center;" width="5%">
					<input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='Filter_Dispense_List()'>
				</td>
				<td width="30%">
				    <input type='text' id='Search_Value' name='Search_Value' style='text-align: center;' autocomplete='off' onkeyup='Filter_Dispense_List()' onkeypress='Filter_Dispense_List()' placeholder='~~~~~ Enter Item Name ~~~~~~'>
				</td>
                                <td>
                                    <select id="buying_selling_price" onchange="Filter_Dispense_List()">
                                        <option value="original_buying_price">Original Buying Price</option>
                                        <?php 
                                           // $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
                                            $sql_select_sponsor_result=mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_store_selling_price_setup slps INNER JOIN tbl_sponsor sp ON slps.Sponsor_ID=sp.Sponsor_ID WHERE Sub_Department_ID='$Sub_Department_ID'") or die(mysqli_error($conn));
                                              if(mysqli_num_rows($sql_select_sponsor_result)>0){
                                                 $Guarantor_Name=mysqli_fetch_assoc($sql_select_sponsor_result)['Guarantor_Name'];
                                                 echo "<option value='selling_as_a_buying_price' selected='selected'>Buying Price From $Guarantor_Name</option>";
                                              }  
                                        ?>
                                    </select>
                                </td>
                                </tr><tr>
                <td><input type='button' name='Pdf_Report' id='Pdf_Report' value='PREVIEW IN PDF' class='art-button-green' onclick='Generate_Pdf_Report()'></td>
                <td><input type='button' name='Excel_Report' id='Excel_Report' value='EXPORT TO EXCEL' class='art-button-green' onclick='Generate_Excel_Report()'></td>
			</tr>
		</table>	    
	</center>
</fieldset>
<fieldset style='overflow-y: scroll; height: 380px; background-color: white;' id='Items_Fieldset'>
    <legend align='right'><b>DISPENSE SUMMARY ~ <?php /*if(isset($_SESSION['Pharmacy_ID'])){*/ echo strtoupper($Sub_Department_Name); // }?></b></legend>
	<table width=100%>
		
        </table>
</fieldset>
<center><span style="color: #037CB0;"><b><i>NOTE : Click info to view details</i></b></span></span></center>
<div id="View_Detail" style="width:50%;" >
    <center id='Details_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </center>
</div>

<script type="text/javascript">
    function View_Details(Start_Date,End_Date,Item_ID,sponsorID) {
        var buying_selling_price=$("#buying_selling_price").val();
        var Sub_Department_ID='<?= $Sub_Department_ID ?>';
        if (window.XMLHttpRequest) {
            myObjectViewDetails = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectViewDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectViewDetails.overrideMimeType('text/xml');
        }

        myObjectViewDetails.onreadystatechange = function () {
            data = myObjectViewDetails.responseText;
            if (myObjectViewDetails.readyState == 4) {
                document.getElementById('Details_Area').innerHTML = data;
                $("#View_Detail").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectViewDetails.open('GET', 'dispensereportdetails.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Item_ID='+Item_ID+'&sponsorID='+sponsorID+'&buying_selling_price='+buying_selling_price+'&Sub_Department_ID='+Sub_Department_ID, true);
        myObjectViewDetails.send();
    }

</script>

<script>
	function Generate_Excel_Report(){
		var Start_Date = document.getElementById("date_From").value;
	    var End_Date = document.getElementById("date_To").value;
	    var sponsorID = document.getElementById('sponsorID').value;
	    var Search_Value = document.getElementById("Search_Value").value;
	    var buying_selling_price=$("#buying_selling_price").val();
	    if (Start_Date != null && Start_Date != '' && End_Date != '' && End_Date != null){
			window.location.href='print_dispense_exel_report.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&sponsorID='+sponsorID+'&buying_selling_price='+buying_selling_price;
	    }else{
			if (Start_Date == null || Start_Date == '') {
			    document.getElementById("date_From").style = 'border: 3px solid red';
			    document.getElementById("date_From").focus();
			}else{
			    document.getElementById("date_From").style = 'border: 3px';
			}
			if (End_Date == null || End_Date == '') {
			    document.getElementById("date_To").style = 'border: 3px solid red';
			    document.getElementById("date_To").focus();
			}else{
			    document.getElementById("date_To").style = 'border: 3px';
			}
	    }
	}
	function Generate_Pdf_Report(){
		var Start_Date = document.getElementById("date_From").value;
	    var End_Date = document.getElementById("date_To").value;
	    var sponsorID = document.getElementById('sponsorID').value;
	    var Search_Value = document.getElementById("Search_Value").value;
	    var buying_selling_price=$("#buying_selling_price").val();
	    if (Start_Date != null && Start_Date != '' && End_Date != '' && End_Date != null){
			window.open('preview_dispense_pdf_report.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&sponsorID='+sponsorID+'&buying_selling_price='+buying_selling_price);
	    }else{
			if (Start_Date == null || Start_Date == '') {
			    document.getElementById("date_From").style = 'border: 3px solid red';
			    document.getElementById("date_From").focus();
			}else{
			    document.getElementById("date_From").style = 'border: 3px';
			}
			if (End_Date == null || End_Date == '') {
			    document.getElementById("date_To").style = 'border: 3px solid red';
			    document.getElementById("date_To").focus();
			}else{
			    document.getElementById("date_To").style = 'border: 3px';
			}
	    }
	}

	function Patient_Excel_Details(Start_Date,End_Date,Item_ID,sponsorID,buying_selling_price,Sub_Department_ID) {
    	window.location.href="dispense_details_excel_report.php?Start_Date="+Start_Date+"&End_Date="+End_Date+"&Item_ID="+Item_ID+"&sponsorID="+sponsorID+"&buying_selling_price="+buying_selling_price+"&Sub_Department_ID="+Sub_Department_ID;
    }
    function Patient_Pdf_Details(Start_Date,End_Date,Item_ID,sponsorID,buying_selling_price,Sub_Department_ID) {
    	window.open("preview_dispense_details_pdf_report.php?Start_Date="+Start_Date+"&End_Date="+End_Date+"&Item_ID="+Item_ID+"&sponsorID="+sponsorID+"&buying_selling_price="+buying_selling_price+"&Sub_Department_ID="+Sub_Department_ID);
    }

	function Filter_Dispense_List(Sub_Department_ID) {
	    var Start_Date = document.getElementById("date_From").value;
	    var End_Date = document.getElementById("date_To").value;
	    var sponsorID = document.getElementById('sponsorID').value;
	    var Search_Value = document.getElementById("Search_Value").value;
	    var report_type = '<?= $report_type ?>';
	    var buying_selling_price=$("#buying_selling_price").val();
	    if (Start_Date != null && Start_Date != '' && End_Date != '' && End_Date != null){
		if(window.XMLHttpRequest) {
		    myObjectFilterDispensed = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    myObjectFilterDispensed = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectFilterDispensed.overrideMimeType('text/xml');
		}
		
		myObjectFilterDispensed.onreadystatechange = function (){
			data20 = myObjectFilterDispensed.responseText;
			if (myObjectFilterDispensed.readyState == 4) {
			    document.getElementById('Items_Fieldset').innerHTML = data20;
			}
		}; //specify name of function that will handle server response........
		if (Search_Value != '' && Search_Value != null) {
		    myObjectFilterDispensed.open('GET','Get_Filtered_Items_Dispensed_from_g_ledger.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Search_Value='+Search_Value+'&sponsorID='+sponsorID+'&buying_selling_price='+buying_selling_price+"&Sub_Department_ID="+Sub_Department_ID,true);
		}else{
		    myObjectFilterDispensed.open('GET','Get_Filtered_Items_Dispensed_from_g_ledger.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&sponsorID='+sponsorID+'&buying_selling_price='+buying_selling_price+"&Sub_Department_ID="+Sub_Department_ID,true);
		}
		myObjectFilterDispensed.send();
	    }else{
			if (Start_Date == null || Start_Date == '') {
			    document.getElementById("date_From").style = 'border: 3px solid red';
			    document.getElementById("date_From").focus();
			}else{
			    document.getElementById("date_From").style = 'border: 3px';
			}
			if (End_Date == null || End_Date == '') {
			    document.getElementById("date_To").style = 'border: 3px solid red';
			    document.getElementById("date_To").focus();
			}else{
			    document.getElementById("date_To").style = 'border: 3px';
			}
	    }
	}
</script>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#date_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_From').datetimepicker({value: '', step: 30});
    $('#date_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_To').datetimepicker({value: '', step: 30});
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>
    $(document).ready(function () {
        $("#View_Detail").dialog({autoOpen: false, width: '98%', height: 500, title: 'DISPENSED MEDICATION DETAILS', modal: true});
    });
</script>
<!--End datetimepicker-->
<script>

    $(document).ready(function () {
        $('select').select2({
        	'width': '250',
        });
        var Sub_Department_ID='<?= $Sub_Department_ID ?>';
        Filter_Dispense_List(Sub_Department_ID);
    })
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>

<?php
    include("./includes/footer.php");
?>