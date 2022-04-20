/*
************** open the finger print taking dilaog*****************	
*/
function open_finger_print_dialog(){
    $("#finger_print_dialog").dialog("open");

  //  alert('Finger Print')
	return false;
}


/*
***************** load finger print module **************************
*/
var finger_prints = [];
var steps = [];
function load_finger_print_module(operation,step,e){
	//alert('capture finger');
	var image = document.createElement("img");
				image.style.width='100%';
				image.style.height='100%';
				
				e.style.padding = '10px';
	if(steps.includes(step) == false){
		data ={operation:operation}
		if(finger_prints.length ==0){
			data.instruction ='CAPTURE';
		}else if(finger_prints.length ==1){
			data.instruction ='CAPTURE2';
			data.sign1 = finger_prints[0];
		}else if(finger_prints.length ==2){
			data.instruction ='ENROLL';
			data.sign1 = finger_prints[0];
			data.sign2 = finger_prints[1];
		}
		$.ajax({
			url:"finger_print_engine.php",
			type:"post",
			data:data,
			dataType:'json',
			beforeSend:function(){
				image.src = 'images/fingerprint.gif';
				e.appendChild(image);
			},
			success:function(result){
				//alert(result.data);
				if(result.code === 200 ){
					
					steps.push(step);
					finger_prints.push(result.data);
					image.src = 'images/finger_pattern.jpeg';				
				}else if(result.code ===300){
					$('.finger_print').empty();
					alert('Process Fails');
				}else if(result.code ===400){
					//$('.finger_print').empty();
					e.removeChild(image);
					alert(result.data);
				}
			}	
		});
	}else{
		alert("ALREADY TAKEN");
	}
	
	return false;
} 

/*
******************** save the finger print details *********************
*/
function save_finger_print(){
    var finger_print_details=$("#finger_print_details").val();
	if(finger_prints.length == 3){
		$("#finger_print_details").val(finger_prints[2]);
		/*$.ajax({
			url:"finger_print_engine.php",
			type:"post",
			data:{operation:'save',finger_print_details:finger_print_details},
			success:function(result){
				alert(result);		
			}	
		});*/
                if(finger_print_details=="visitors"){
                   // alert("visitorst");
                  finger_print_details=$("#finger_print_details").val();
                 var Registration_ID=$("#Registration_ID").val();
                   $.ajax({
			url:"save_patient_finger_print_updated.php",
			type:"post",
			data:{finger_print_details:finger_print_details,Registration_ID:Registration_ID},
			success:function(result){
				alert(result);	
                                if(result=="Finger Print Saved"){
                                    location.reload();
                                }
			},error:function(x,y,z){
                            alert(x+y+z);
                        }	
		}); 
                }else{
                    alert("empty");
                }
		$("#finger_print_dialog").dialog("close");
                
	}else{
		alert("SOMETHING WRONG !!!");
	}
	return false;
}
/*
******************** reset the finger print details *********************
*/
function reset_finger_print(){
        //alert('save finger');
		$('.finger_print').empty();
		finger_prints = [];
		steps = [];
	return false;
}

/*
************ verify patient finger print ************
*/
function verify_finger_print(Registration_ID){
	//alert(Registration_ID);
	data ={Registration_ID:Registration_ID,operation:'verify'};
	$.ajax({
		url:'finger_print_engine.php',
		type:'post',
		data:data,
		dataType:'json',
		success:function(result){
			if(result.code == 200){
                            check_if_already_checked_in_today(Registration_ID);
				//alert('patient exists');
			}else if(result.code == 400){
				alert('patient does not exits');
			}else if(result.code == 300){
				alert('Connection fails');
			}
		}
	});
}
function verify_finger_print_for_clinic_patient(Registration_ID,Patient_Payment_ID,Patient_Payment_Item_List_ID){
	//alert(Registration_ID);
	data ={Registration_ID:Registration_ID,operation:'verify'};
	$.ajax({
		url:'finger_print_engine.php',
		type:'post',
		data:data,
		dataType:'json',
		success:function(result){
			if(result.code == 200){
                                window.location="doctorspageoutpatientwork.php?Registration_ID=" +Registration_ID+ "&Patient_Payment_ID=" + Patient_Payment_ID + "&Patient_Payment_Item_List_ID=" + Patient_Payment_Item_List_ID + "&NR=true&PatientBilling=PatientBillingThisForm";
				//alert('patient exists');
			}else if(result.code == 400){
                            $("#verify_finger_print_message").html("patient finger print is not valid");
                            ///alert('patient does not exits');
			}else if(result.code == 300){
				alert('Connection fails');
			}
		}
	});
}
function verify_finger_print_for_nhif_bill(Registration_ID,Folio_Number,Sponsor_ID,Patient_Bill_ID,Control_Quantity,Patient_Payment_ID,Sponsor_Name,Check_In_ID,AuthorizationNo,full_disease_data){
//	alert(Registration_ID);
	data ={Registration_ID:Registration_ID,operation:'verify'};
	$.ajax({
		url:'finger_print_engine.php',
		type:'post',
		data:data,
		dataType:'json',
		success:function(result){
			if(result.code == 200){
                              Confirm_Approval_Trnsaction(Registration_ID, Folio_Number, Sponsor_ID, Patient_Bill_ID, Control_Quantity, Patient_Payment_ID, Sponsor_Name, Check_In_ID, AuthorizationNo,full_disease_data);
			}else if(result.code == 400){
                            $("#verify_finger_print_message").html("patient finger print is not valid");
                            ///alert('patient does not exits');
			}else if(result.code == 300){
				alert('Connection fails');
			}
		}
	});
}

