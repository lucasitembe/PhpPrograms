/*
************************** get afya card number ****************************
*/
card_details = [];
update_details =[];
function get_card_number(){
		card_details = [];
		$.ajax({
			url:'write_afya_card.php',
			type:'post',
			data:{operation:'get_card_number'},
			dataType:'json',
			success:function(result){
                                console.log(result);
                                console.log("====>result code====>"+result.code);
				if(result.code == 200){
					card_details.push(result.data);
					card_details.push(result.card_no);
					$('#card_number').val(result.card_no);
                                        console.log("\n card no---"+result.card_no+":====>"+result.data);
				}else if(result.code == 100){
					alert("Connection Fails");
				}
			}
		});
}
/*
*************** write personal infomation to the afya card ******************
*/
function write_afya_card(Registration_ID,operation,Employee_ID){
	//$("#issue_card_dialog").dialog('open');
        console.log("ndanin ndaini");
	if(Registration_ID != ''){
            console.log("imeingia ndani");
		data = 
{Registration_ID:Registration_ID,Employee_ID:Employee_ID,operation:operation,serial_no:card_details[0],card_no:card_details[1]};
		if(card_details.length == 2){
			$.ajax({
				url:'write_afya_card.php',
				type:'post',
				data:data,
				dataType:'json',
				success:function(result){
                                       // console.log("---result-->"+result);
                                        //console.log("---code-->"+result.code);
					//alert(result.code);
					if(result.code == 100){
                                                    alert("Connection Fails");
					}else if(result.code == 200){
						$("#issue_card_dialog").dialog('close');
						alert("SUCCESS");
						location.reload();
					}else if(result.code == 300){
						alert("CARD ALREADY TAKEN");
					}else if(result.code == 400){
						alert(result.message);
					}
				},error:function(x,y,z){
                                   console.log(x+y+z); 
                                }
			});
		}else{
			alert('GET CARD NO FIRST<-');
		}
	}else{
		alert('NO PATIENT SELECTED');
	}
}
/*
***************  **********************
*/
function update_afya_card(Registration_ID,operation,Employee_ID){
//        console.log("----YA KWENYE CARD::::::---->"+update_details[1]);
	if(Registration_ID != ''){
		data = 
			{operation:operation,update_details:update_details[1],card_serial:update_details[0]};
		if(update_details.length == 2){
			$.ajax({
				url:'write_afya_card.php',
				type:'post',
				data:data,
				dataType:'json',
				success:function(result){
					if(result.code == 200){
						$("#verify_card_dialog").dialog('close');
						alert("DETAILS UPDATED SUCCESSIFULLY");
					}else if(result.code == 300){
						alert(result.message);
					}else if(result.code == 400){
                                                    alert("CONNECTION FAILS");
					}
				}
			});
		}else{
			alert('GET CARD NO FIRST');
		}
	}else{
		alert('NO PATIENT SELECTED');
	}
}

/*
/*
********************* write checkup results to the afya card ************************
*/
function open_afya_card_dialog(Registration_ID,action){
        console.log(Registration_ID+"===>"+action);
	if(Registration_ID != ''){
		if(action == 'issue'){
			$("#issue_card_dialog").dialog("open");
		   }else if(action == 'verify'){
                            console.log("verify card --->ndani");
			   data ={operation:'verify',Registration_ID:Registration_ID};
			   $.ajax({
			   	url:'write_afya_card.php',
				type:'post',
				data:data,
//				dataType:'json',
				success:function(result){
                                        
                                        $("#detail_information").html(result);
                                        $("#verify_card_dialog").dialog("open");
//					if(result.code == 200){
//						
//						update_details = [];
//						update_details.push(result.card_serial);
//						update_details.push(result.updates);
//						var activate_button = false;
//						for(var i in result['system']){
//							var key = i;
//							$("#"+key+" td:nth-child(2)").html(result['card'][key]);
//							$("#"+key+" td:nth-child(3)").html(result['system'][key]);
//                                                        console.log(result['card'][key]+"===>"+result['system'][key]);
//							if(result['comp'][key]==1){
//								$("#"+key+" td:nth-child(4) img").attr("src","images/save_icon.png");
//							}else{
//								$("#"+key+" td:nth-child(4) img").attr("src","images/x.png");
//								activate_button= true;
//							}
//						}
//						if(activate_button == false){
//							$("#update_afya_card").hide();
//						}else{
//                                                   $("#update_afya_card").show(); 
//                                                }
//						$("#verify_card_dialog").dialog("open");
//					}else if(result.code == 300){
//							 alert("INVALID CARD");
//					}else if(result.code == 400){
//							 alert("CONNECTION FAILS");
//					}
//                                        console.log("success:==>");
//				},complete:function(){
                                    console.log("complete");
                                },error:function(x,y,z){
                                    console.log(x+y+z);
                                }
			   });
                           console.log("====--->excecuted--->");
		}
		
	}else{
		alert('NO PATIENT SELECTED');
	}
}

function write_check_up_results(Registration_ID){
	
	if(Registration_ID !== ''){
		$.ajax({
			url:'write_afya_card.php',
			type:'post',
			data:{Registration_ID:Registration_ID,operation:'save_check_out_results'},
			dataType:'json',
			success:function(result){
				alert(result);
			}
		});
	}else{
		alert('NO PATIENT SELECTED');
	}
}
function read_afya_card_infomation_and_process(){
    
    $.ajax({
            url:'write_afya_card.php',
            type:'post',
            data:{operation:'get_card_number'},
            dataType:'json',
            success:function(result){
//                    console.log("----result:"+result);
                    if(result.code == 200){
                          search_card_and_redirect(result.card_no)
                            console.log(result.code+"\n card no---"+result.card_no);
                    }else if(result.code == 100){
                            alert("Connection Fails");
                    }
            }
    });
}
function read_afya_card_infomation_and_process_patient(){
   // alert("imooooo");
    $.ajax({
            url:'write_afya_card.php',
            type:'post',
            data:{operation:'get_card_number'},
            dataType:'json',
            success:function(result){
//                    console.log("----result:"+result);
                    if(result.code == 200){
                          search_card_and_redirect_to_clinical_notes(result.card_no)
                            console.log(result.code+"\n card no---"+result.card_no);
                    }else if(result.code == 100){
                            alert("Connection Fails");
                    }
            }
    });
}
function read_afya_card_infomation_and_process_patient_ebill(){
    $.ajax({
            url:'write_afya_card.php',
            type:'post',
            data:{operation:'get_card_number'},
            dataType:'json',
            success:function(result){
                    if(result.code == 200){
                          search_card_and_redirect_to_ebill_search_list(result.card_no)
                          console.log(result.code+"\n card no---"+result.card_no);
                    }else if(result.code == 100){
                            alert("Connection Fails");
                    }
            }
    });
}
function search_card_and_redirect(card_no){
    $.ajax({
            url:'ajax_search_card_and_redirect.php',
            type:'post',
            data:{card_no:card_no},
            dataType:'json',
            success:function(result){
                    console.log(result.status_control);
                    if(result.status_control == 200){
                        document.location="visitorform.php?Registration_ID="+result.Registration_ID+"&PatientBilling=PatientBillingThisForm&from_afya_cardMember_Number="+result.Member_Number
                    }else if(result.status_control == 404){
                       read_card_information_for_registration()
                          
                    }
            }
    });
}
function search_card_and_redirect_to_clinical_notes(card_no){
    $.ajax({
            url:'ajax_search_card_and_redirect.php',
            type:'post',
            data:{card_no:card_no},
            dataType:'json',
            success:function(result){
                    console.log(result.status_control);
                    if(result.status_control == 200){
//                        document.location="visitorform.php?Registration_ID="+result.Registration_ID
                           $("#Search_Patient_Number").val(result.Registration_ID);
                           filterPatient();
                          /// alert(result.Registration_ID);
                    }else if(result.status_control == 404){
                        alert("Patient Not Found");
                    }
            }
    });
}
function search_card_and_redirect_to_ebill_search_list(card_no){
    $.ajax({
            url:'ajax_search_card_and_redirect.php',
            type:'post',
            data:{card_no:card_no},
            dataType:'json',
            success:function(result){
                    console.log(result.status_control);
                    if(result.status_control == 200){
                           $("#Patient_Number").val(result.Registration_ID);
                           Get_Transaction_List();

                    }else if(result.status_control == 404){
                        alert("Patient finger print is not valid");
                    }
            }
    });
}
function read_card_information_for_registration(){
    data ={operation:'read_card_information',Registration_ID:0};
    $.ajax({
        url:'write_afya_card.php',
        type:'post',
        data:data,
        dataType:'json',
        success:function(result){
                console.log("header data for registration===>"+result['card']);
                var card_header_data=result['card'];
                var patient_name=card_header_data[1];
                var date_of_birth=card_header_data[2];
                var gender=card_header_data[3];
                document.location="registerpatient.php?patient_name="+patient_name+"&date_of_birth="+date_of_birth+"&gender="+gender;
                console.log(patient_name+date_of_birth);
//                if(result.code == 200){  
//                    var registration_url_data="";
////                        for(var i in result['card']){
////                                var key = i;
////                                registration_url_data +=key+"="+result['card'][key]+"&";
////                                console.log("\n"+key+"----:"+result['card'][key]);
////                        }
//                        document.location="registerpatient.php?"+registration_url_data;  
//                        console.log("\n"+registration_url_data);
//                }else if(result.code == 300){
//                                 alert("INVALID CARD");
//                }else if(result.code == 400){
//                                 alert("CONNECTION FAILS");
//                }
                console.log("read card information:==>");
        },complete:function(){
            console.log("complete");
        },error:function(x,y,z){
            console.log(x+y+z);
        }
    });
}

function retrive_medical_record_data(card_no){
    $.ajax({
        type:'POST',
        url:'write_afya_card.php',
        data:{card_no:card_no,operation:"retrive_medical_record_data"},
        success:function(data){
            $("#demographic_data").hide();
            $("#medical_record_data").html(data);
        }
    });
}