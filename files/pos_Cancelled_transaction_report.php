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
    #Employee_ID{
        width: 100%;
        font-weight: bold;
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
    <table width=80%>
        <tr>
        	<td style="text-align: right;">
        		<b>Employee Name</b>
			</td>
			<td>
				<select name="Employee_ID" id="Employee_ID">
					<option selected value="0">All</option>
					<?php
						$select_details = mysqli_query($conn, "SELECT Employee_ID, Employee_Name FROM tbl_employee ORDER BY  Employee_Name ASC") or die(mysqli_error($conn));
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
            <td style='text-align: right;'><b>Start Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='Date_From' style='text-align: center;' placeholder='Start Date'>
            </td>
            <td style='text-align: right;'><b>End Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='Date_To' style='text-align: center;' placeholder='End Date'>
            </td>
            <td style='text-align: center;' width=10%>
                <input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='filter_list()'>
            </td>
            <td style="text-align: right; width: 6%;   " id="Report_Button_Area">
			<a href="generalperformanceallreport.php?Date_From=<?php echo $Today; ?>&Date_To=<?php echo $Today; ?>&Billing_Type=All&Employee_ID=0&Sponsor_ID=0" class="art-button-green" target="_blank">
				PREVIEW REPORT
			</a>
		</td>
        </tr>
        </table>
        </center>

<center>
        <table  class="hiv_table" style="width:100%; border; 2 solid #ccc;">
            <tr>
                <td>
                    <div style="width:100%; height:500px;overflow-x: hidden;overflow-y: auto;"  id="Search_Iframe">
                    </div>
                </td>
            </tr>
        </table>

    </center>
</fieldset>



<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<!--<script src="script.js"></script>-->
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<!--<script src="script.responsive.js"></script>-->

<!-- end popup window -->

<script>
    function filter_list(){
		var Date_From = document.getElementById("Date_From").value;
		var Date_To = document.getElementById("Date_To").value;
		var Employee_ID = document.getElementById("Employee_ID").value;

        if(Date_From =='' || Date_To==''){
            alert("FILL THE DATE RANGE");
        }else{
            $('#Search_Iframe').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>');
            $.ajax({
                url:'Pos_Cancelled_report_Filtered_All.php',
                type:'post',
                data:{filter:'yes',Date_From:Date_From,Date_To:Date_To,Employee_ID:Employee_ID},
                success:function(results){
                    $('#Search_Iframe').html(results);
                }
            });
        }
    }
</script>
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



<script>
    function Display_Report_Button(){
		var Date_From = document.getElementById("Date_From").value;
		var Date_To = document.getElementById("Date_To").value;
		var Billing_Type = document.getElementById("Bill_Type").value;
		var Employee_ID = document.getElementById("Employee_ID").value;
		var Sponsor_ID = document.getElementById("Sponsor_ID").value;
		var transaction_channel = document.getElementById("transaction_channel").value;
		
        document.getElementById("Report_Button_Area").innerHTML = '<a href="generalperformanceallreport.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Billing_Type='+Billing_Type+'&Employee_ID='+Employee_ID+'&Sponsor_ID='+Sponsor_ID+'&transaction_channel='+transaction_channel" class="art-button-green" target="_blank">PREVIEW REPORT</a>';
    }
</script>
<script>
    $(document).ready(function (e){
        $("#Employee_ID").select2();
    });
</script>

<?php
    include("./includes/footer.php");
?>