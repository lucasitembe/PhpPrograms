<?php
include('includes/header.php');
include('includes/connection.php');

?>

<a href="new_payment_method.php?CreditTransactions=CreditTransactionsThisForm" class="art-button-green">BACK</a>
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
			height: 380px !important;
			overflow-y: scroll;
		}
	</style>
	<table width="60%;" style="border:none">
		<tr>
		<td>
		<input id="Receipt_Number" class="input_date" type="text" name="Receipt_Number" placeholder="Receipt_Number">
		</td>
	
<!--			<td><input id="Start_Date" class="input_date" type="" name=""  placeholder="Date From"></td>
			<td><input id="End_Date" class="input_date" type="" name="" placeholder="Date To"></td>-->
			<td><button class="art-button-green btn" id="filter_report">FILTER</button></td>
		</tr>
	</table>

	<br>
	<fieldset>
		<div id="content"></div>
		<table id="table">
			<tr style="height:35px; width:1px solid grey"><td style=" padding:4px;text-align:center;width:5%;"><b>SN</b></td><td style=" padding:4px;text-align:center;width: 7%;"><b>Receipt Number#</b></td><td style=" padding:4px;text-align:center;width: 15%;"><b>Print out Time</b></td><td style=" padding:4px;text-align:center;width:44%"><b>Print out By</b></td></tr>
			</table>
			<div id="body">
				
			</div>
		
	</fieldset>
</center>



<!-- <script src="css/jquery.js"></script> -->
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
	
	$("#filter_report").click(function(e){
		e.preventDefault();
		// alert("date start")
		//var date_from = $("#Start_Date").val();
		//var date_to = $("#End_Date").val();
		var Receipt_Number = $("#Receipt_Number").val();

		if(Receipt_Number == ""){
                    alert(" Fill Receipt Number ");
                }
		
		var data = {
						
			Receipt_Number:Receipt_Number,			
		}

		// alert(JSON.stringify(data))
		$.ajax({
			method:"GET",
			url:"print_out_receipt_cashiers.php",
			data:data,
			success:function(data){
				console.log(data)
				$("#body").html("");
				$("#body").html(data);
			}
		});

	})


	$("#search_by_approver").change(function(e){
		e.preventDefault();
		var id = $(this).val();
		// alert(id)

		var data={
			id:id,
		}
		$.ajax({
			method:"GET",
			url:"filter_by_approver.php",
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
		end_date:date_to,
	}

	$.ajax({
		method:"GET",
		url:"filter_by_patient_number.php",
		data:data,
		success:function(data){
			$("#body").html("");
			$("#body").html(data);
		}
	})
})
</script>