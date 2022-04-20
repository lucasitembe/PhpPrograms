/*
* javascript file By Nassor Nassor
*/
$(function(){
	$(".save").hide();
	$("#searchPatientBtn").click(function(){
		if($("#reg_no").val()==''){
		alert("Please enter Patient Registration Number");
		return;
	}
		//ajax search
		$.ajax({
			type: 'GET',
			url: 'functions/search_patient.php',
			data: {reg_no:$("#reg_no").val()} ,
			dataType: 'json' ,
			success: function(data){
				console.log(data);
				if(data.status == '200'){
					$(".description").html(data.Patient_Name+" <br> Reg # :"+ data.Registration_ID +", "+data.Sponsor);
					$(".save").show();
				} else if(data.status == '404'){
					alert(data.message);
				}
				
			},
			error: function(data){

			}
		});
	});

	//searchDoctorBtn
	$("#searchDoctorBtn").click(function(){
		if($("#reg_no").val()==''){
		alert("Please enter Patient Registration Number");
		return;
	}
		//ajax search
		$.ajax({
			type: 'GET',
			url: 'functions/search_doctor.php',
			data: {Employee_ID:$("#reg_no").val()} ,
			dataType: 'json' ,
			success: function(data){
				console.log(data);
				if(data.status == '200'){
					$(".description").html(data.Employee_Name+" <br> Reg # :"+ data.Employee_ID);
					$(".save").show();
				} else if(data.status == '404'){
					alert(data.message);
				}
				
			},
			error: function(data){

			}
		});
	});
});

function savePatientSignature(signature){
	if($("#reg_no").val()==''){
		alert("Please enter Patient Registration Number");
		return;
	}
	$.ajax({
			type: 'POST',
			url: 'functions/save_signature.php',
			data: {reg_no:$("#reg_no").val(),Check_In_ID:$("#Check_In_ID").val(),signature:signature,person_type:'patient'} ,
			dataType: 'json' ,
			success: function(data){
				console.log(data);
				if(data.status == '200'){
					alert("Success: "+data.message);
					//window.location = "signature.php";
				} else {
					alert("Error: "+data.message);
				}
				
			},
			error: function(data){

			}
		});
}

function saveEmployeeSignature(signature){
	if($("#reg_no").val()==''){
		alert("Please enter Employee Registration Number");
		return;
	}
	$.ajax({
			type: 'POST',
			url: 'functions/save_signature.php',
			data: {reg_no:$("#reg_no").val(),signature:signature,person_type:'employee'} ,
			dataType: 'json' ,
			success: function(data){
				console.log(data);
				if(data.status == '200'){
					alert("Success: "+data.message);
					//window.location = "signature.php";
				} else {
					alert("Error: "+data.message);
				}
				
			},
			error: function(data){

			}
		});
}


