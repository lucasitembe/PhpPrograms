<?php
include("./includes/header.php");
    include("./includes/connection.php");
?>

<a href="credittransactions.php?Section=Msamaha&CreditTransactions=CreditTransactionsThisForm" class="art-button-green">BACK</a>
<br></br>
<center>
	
	<style type="text/css">
	*{
		box-sizing: border-box;
	}
		.input_date{
			width: 100%;
			height: 3.5rem;
			text-align: center;
		}

		#content{
			/*height: 350px;*/
			background-color:#fff;
		}

		#table{
			border-collapse: collapse;
			width: 100%;
		}

		.btn{
			height: 3.5rem !important;
		}
		#body{
			height: 560px !important;
			overflow-y: scroll;
		}
	</style>
	<fieldset>
	<table width="60%;" class="table">
		<tr>
			<td width="20%;">
			<input id="patient_number" class="input_date" type="text" name="patient_number" placeholder="Search Patient Number">
			</td>
			<td width="30%;">
			<select class="input_date" class="form-control" name="" id="search_by_approver">
				<option value="">
					Select Approver
				</option>
				<?php

					$sql = "SELECT e.Employee_ID,e.Employee_Name FROM tbl_employee e JOIN  tbl_privileges p ON e.Employee_ID = p.Employee_ID WHERE p.Revenue_Center_Works='yes'";
					$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
					$data = [];
					while($row=mysqli_fetch_assoc($result)){
						extract($row);
						$data['employee_name'] = $Employee_Name;
						$data['employee_id'] = $Employee_ID;

						echo "<option value='".$Employee_ID."'>".$Employee_Name."</option>";
					}
				?>
			</select>
			</td>
			<?php 
                $Today_Date = mysqli_query($conn,"select now() as today");
                while($row = mysqli_fetch_array($Today_Date)){
                    $Today = $row['today'];
                }
                $today_start_date=mysqli_query($conn,"select cast(current_date() as datetime)");
                while($start_dt_row=mysqli_fetch_assoc($today_start_date)){
                    $today_start=$start_dt_row['cast(current_date() as datetime)'];
                }
            ?>
			<td width="20%;"><input id="Start_Date" class="input_date" value="<?= $today_start ?>" name=""  placeholder="Date From"></td>
			<td width="20%;"><input id="End_Date" class="input_date" value="<?= $Today ?>" name="" placeholder="Date To"></td>
			<td width="10%;"><button class="art-button-green btn" width="100%;" onclick="filter_report_data()">FILTER</button></td>
			
		</tr>
	</table>
	</fieldset>
	<br>

	<fieldset style='height: 65vh;'>
			<legend style='font-weight: bold;' align='center'>PATIENT APPROVAL REPORT</legend>
		<div id="content"></div>
		<table id="table">
			<tr style="height:40px; width:1px solid grey">
				<td style=" padding:4px;text-align:center;width:3%;"><b>SN</b></td>
				<td style=" padding:4px; width: 15%;"><b>PATIENT NAME</b></td>
				<td style=" padding:4px;text-align:center;width: 9%;"><b>REG#</b></td>
				<td style=" padding:4px;width: 10%;"><b>APPROVED TIME</b></td>
				<td style=" padding:4px;width:30%"><b>ITEMS APPROVED</b></td>
				<td style=" padding:4px;text-align:right; width:20%"><b>TOTAL AMOUNT</b></td>
				<td style=" padding:4px;text-align:center; width:20%"><b>APPROVED BY</b></td>
				</tr>
		</table>
			<div id="body" style=' height: 53vh;'>
				<?php 
					//include("today_approval.php");
				?>
			</div>
		
	</fieldset>
</center>



 <script src="css/jquery.js"></script> 
<script src="css/jquery.datetimepicker.js"></script>
<script>

	// $("#search_by_approver").select2()
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

<script type="text/javascript">
	
	// $("#filter_report").click(function(e){
	// 	e.preventDefault();
	$(document).ready(function(){
		filter_report_data();
	})
	function filter_report_data(){
		var date_from = $("#Start_Date").val();
		var date_to = $("#End_Date").val();
		if(date_from ==="" ){
			$("#Start_Date").css("border","1px solid red");
			return false;
		}else if(date_to === ""){
			$("#End_Date").css("border","1px solid red");
			return false;
		}
		var data = {
			start_date:date_from,
			end_date:date_to, filter_by_date:''			
		}
		document.getElementById('body').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
		$.ajax({
			method:"POST",
			url:"today_approval.php",
			data:data,
			success:function(data){
				console.log(data)
				$("#body").html(data);
			}
		});

	}


	$("#search_by_approver").change(function(e){
		e.preventDefault();
		var Employee_ID = $(this).val();	
		var date_from = $("#Start_Date").val();
		var date_to = $("#End_Date").val();
		if(date_from ==="" ){
			$("#Start_Date").css("border","1px solid red");
			return false;
		}else if(date_to === ""){
			$("#End_Date").css("border","1px solid red");
			return false;
		}	
		var data={Employee_ID:Employee_ID,start_date:date_from,
			end_date:date_to, filter_date:''
		}
		$.ajax({
			method:"POST",
			url:"today_approval.php",
			data:data, 
			success:function(data){
				$("#body").html("");
				$("#body").html(data);
			}
		})
	})

$("#patient_number").keyup(function(e){
		e.preventDefault();
	var date_from = $("#Start_Date").val();
	var date_to = $("#End_Date").val();
	var patient_id = $(this).val();
	var data = {
		patient_id:patient_id,
		start_date:date_from,
		end_date:date_to,filter_by_reg_no:''
	}
	$.ajax({
		method:"POST",
		url:"today_approval.php",
		data:data,
		success:function(data){
			$("#body").html("");
			$("#body").html(data);
		}
	})
})
</script>
<?php
include("./includes/footer.php");
?>
<script>
    $('select').select2();
</script>