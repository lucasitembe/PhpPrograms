
     
     function examination_of_operated_eye_dialog(Registration_ID){            
            var consultation_ID=$("#consultation_ID").val();
            var Patient_Name=$("#Patient_Name").val();
            //alert(consultation_ID)
            $.ajax({
                type:'post',
                url: 'examination_of_operated_eye.php',
                data : {Registration_ID:Registration_ID, consultation_ID:consultation_ID
                },
                success : function(data){
                    $('#optical_inpatient_section').dialog({
                        autoOpen:true,
                        width:'60%',
                        position: ['center',200],
                        title:'Patient Name :  '+Patient_Name,
                        modal:true
                       
                    });  
                    $('#optical_inpatient_section').html(data);
                    $('#optical_inpatient_section').dialog('data');
                }
            })
        }
        function add_causes_of_death_reason(){
            var deceased_reason_ID = $("#deceased_reason_ID").val();
            
            if(deceased_reason_ID == ""){              
                alert("fill the space first");
                
            }else{
                $.ajax({
                type:'POST',
                url:'add_reason_deceased_patient.php',
                data:{deceased_reason_ID:deceased_reason_ID},
                success:function (data){
                    alert("successfully saved");
                },
                error:function (x,y,z){
                    console.log(z);
                }
            });
            } 
        }
        function search_disease_normal(search_value){
            $.ajax({
                type: 'GET',
                url: 'search_disease_c_death.php',
                data: {
                    search_value: search_value,
                    normal: ''
                },
                success: function(data) {
                    console.log(data);
                    $("#disease_normal_table_selection").html(data);
                },
                error: function(x, y, z) {
                    console.log(z);
                }
            });
        }


        function search_disease_c_death(search_value) {
            
            $.ajax({
                type: 'GET',
                url: 'search_disease_c_death.php',
                data: {
                    search_value: search_value
                },
                success: function(data) {
                    console.log(data);
                    $("#disease_suffred_table_selection").html(data);
                },
                error: function(x, y, z) {
                    console.log(z);
                }
            });
        }
    
 
     function remove_added_death_disease(disease_death_ID, Registration_ID) {
         $.ajax({
             type: 'GET',
             url: 'remove_death_reason_to_catch.php',
             data: {
                 disease_death_ID: disease_death_ID,
                 Registration_ID: Registration_ID
             },
             success: function(data) {
                 //console.log(data);
                 $("#disease_suffred_table").html(data);
             },
             error: function(x, y, z) {
                 console.log(z);
             }
         });
     }
 
     function refresh_death_reason() {
         var Registration_ID = $("#Registration_ID").val();
         $.ajax({
             type: 'GET',
             url: 'refresh_death_reason.php',
             data: {
                 Registration_ID: Registration_ID
             },
             success: function(data) {
                 //console.log(data);
                 $("#disease_suffred_table").html(data);
             },
             error: function(x, y, z) {
                 console.log(z);
             }
         });
     }
 
     function add_death_reason(disease_ID) {
         var Registration_ID =$("#Registration_ID").val(); 
         var consultation_ID=$("#consultation_ID").val();
         $.ajax({
             type: 'GET',
             url: 'add_death_reason_to_catch.php',
             data: {
                 disease_ID: disease_ID,
                 Registration_ID: Registration_ID,dischargestatusdeath:'', consultation_ID:consultation_ID,
             },
             success: function(data) {
                 console.log(data);
                 $("#disease_suffred_table").html(data);
                 search_disease_c_death();
             },
             error: function(x, y, z) {
                 console.log(z);
             }
         });
     }
     function add_normal_reason(disease_ID, dischargestatus) {
        var Registration_ID =$("#Registration_ID").val(); 
        var consultation_ID=$("#consultation_ID").val();
        var normalDischarge ='Normal';
        $.ajax({
            type: 'GET',
            url: 'add_death_reason_to_catch.php',
            data: {
                disease_ID: disease_ID,
                Registration_ID: Registration_ID,dischargestatus:dischargestatus, normalDischarge:normalDischarge, consultation_ID:consultation_ID,
            },
            success: function(data) {
                $("#disease_table_normal").html(data);
                search_disease_c_death();
            },
            error: function(x, y, z) {
                console.log(z);
            }
        });
    }

    function open_add_reason_dialogy(){        
            $("#add_death_course_dialogy").dialog({
                title: 'ADD CAUSE OF DEATH',
                width: '50%',
                height: 200,
                modal: true,
            }); 
    }   
     function opencourdeofDeathDialogy() {
        var Registration_ID =$("#Registration_ID").val(); 
        $.ajax({
            type: 'POST',
            url: 'add_death_reason_to_catch.php',
            data: {Registration_ID:Registration_ID, openDialog:''
            },
            success: function(data) {
                $("#store_death_discharged_info").dialog({
                    title: 'ADD CAUSE OF DEATH',
                    width: '80%',
                    height: 700,
                    modal: true,
                });
                $("#store_death_discharged_info").html(data);
            },
        });
        
     }
 
    function close_this_dialog(){
        var Admision_ID = $("#Admision_ID").val();   
        var Registration_ID =$("#Registration_ID").val();  
        var consultation_ID=$("#consultation_ID").val();    
        $.ajax({
            type: 'POST',
            url: 'add_death_reason_to_catch.php',
            data: {saveddeathDischarge:'',Admision_ID:Admision_ID,consultation_ID:consultation_ID,  Registration_ID:Registration_ID,
            },
            success: function(data) {
                $("#dischargeDiagnosis").html(data);
                forceadmit(Admision_ID);
                $("#store_death_discharged_info").dialog("close");
            },
            error: function(x, y, z) {
                console.log(z);
            }
        });
    }

    function close_this_discharge_dialog(){
    var consultation_ID=$("#consultation_ID").val();
    var savednormalDischarge ='Normal';
    $.ajax({
        type: 'POST',
        url: 'add_death_reason_to_catch.php',
        data: {savednormalDischarge:savednormalDischarge,  consultation_ID:consultation_ID,
        },
        success: function(data) {
            console.log(data);
            $("#dischargeDiagnosis").html(data);
            var Admision_ID = $("#Admision_ID").val();
            forceadmit(Admision_ID);
            $("#store_normal_discharged_info").dialog("close");
        },
        error: function(x, y, z) {
            console.log(z);
        }
    });
    }

     function check_if_dead_reason(rischarge_reason_id, Registration_ID, Admision_ID) {
         var Discharge_Reason_ID = $(rischarge_reason_id).val();
         var death_date_time = $(death_date_time).val();
         $("#Docto_confirm_death_name").val(" ");
         $("#death_date_time").val("");

         $.ajax({
             type: 'GET',
             url: 'check_discharge_reason.php',
             data: {
                 Discharge_Reason_ID: Discharge_Reason_ID
             },
             success: function(data) {
                 $("#Discharge_Reason_txt").val(data)
                 if (data == "dead") {
                     refresh_death_reason();
                     $("#store_death_discharged_info").dialog({
                         title: 'FILL DEATH INFOMATION',
                         width: '70%',
                         height: 550,
                         modal: true,
                     });
                 }else {
                    addeddischargediagnosis();
                     $("#store_normal_discharged_info").dialog({
                         title: 'FILL DISCHERGE DIAGNOSIS',
                         width: '70%',
                         height: 550,
                         modal: true,
                     });
                 }
             }
         });
     }

    //remove discharge diagnosis
    function remove_added_discharge_disease(Discharge_diagnosis_ID){
        var Registration_ID =$("#Registration_ID").val(); 
        var consultation_ID=$("#consultation_ID").val();
        $.ajax({
            type: 'POST',
            url: 'remove_death_reason_to_catch.php',
            data: {
                Discharge_diagnosis_ID: Discharge_diagnosis_ID,consultation_ID:consultation_ID,Registration_ID:Registration_ID, dischargeNormal:''
            },
            success: function(data) {
                $("#disease_table_normal").html(data);
            },
            error: function(x, y, z) {
                console.log(z);
            }
        });
    }

    //select discharge diagnosis
    function addeddischargediagnosis(){
        var Registration_ID =$("#Registration_ID").val(); 
        var consultation_ID=$("#consultation_ID").val();
        $.ajax({
            type: 'POST',
            url: 'remove_death_reason_to_catch.php',
            data: {
                consultation_ID:consultation_ID,Registration_ID:Registration_ID, dischargeNormal:''
            },
            success: function(data) {
                $("#disease_table_normal").html(data);
            },
            error: function(x, y, z) {
                console.log(z);
            }
        });
    }

    

    // RDTC PATCH TEST FUNCTION
    function open_rdtc_dialogy() {
        var patient_id = $('#patient_id').val();
        var employee_id = $('#employee_id').val();
        var patient_name = $('#patient_name').val();
        var patient_age = $('#patient_age').val();
        var patient_gender = $('#patient_gender').val();
        $.ajax({
            type: 'post',
            url: 'rdtc_record.php',
            data: {
                patient_id: patient_id,
                employee_id: employee_id,
                patient_name: patient_name,
                patient_age: patient_age,
                patient_gender: patient_gender
            },
            success: (data) => {
                $("#rdtc_dialogy").dialog({
                    title: 'EUROPEAN BASELINE SERIES S-1000 TEST',
                    width: '90%',
                    height: 700,
                    modal: true,
                });
                $("#rdtc_dialogy").html(data);
                $("#rdtc_dialogy").dialog("open");
            }
        });
    }
    // RDTC PATCH TEST 

    // Dose monitoring function
    var dose_button = document.getElementById('dose_button');
    dose_button.addEventListener('click', () => {

        var patient_name = $('#patient_name').val();
        var patient_gender = $('#patient_gender').val();
        var patient_age = $('#patient_age').val();
        var patient_id = $('#patient_id').val();
        var employee_id = $('#employee_id').val();
        var diagnosis = $('.final_diagnosis').val();

        $.ajax({
            type: 'post',
            url: 'dose_monitoring_dermatology.php',
            data: {
                patient_name: patient_name,
                patient_gender: patient_gender,
                patient_age: patient_age,
                patient_id: patient_id,
                employee_id: employee_id,
                diagnosis: diagnosis
            },
            success: (data) => {
                $('#dose_monitoring').dialog({
                    autoOpen: false,
                    width: '90%',
                    title: 'Dermatology Dose Monitoring ',
                    modal: true
                });
                $("#dose_monitoring").html(data);
                $("#dose_monitoring").dialog("open");
            }
        });
    });
    // Dose Monitoring function


    function doctor_confirm_death(Registration_ID){
        var Registration_ID =$("#Registration_ID").val(); 
        var death_date_time=$("#death_date_time").val();
        var course_of_death=$("#course_of_death").val();
        var Docto_confirm_death_name=$("#Docto_confirm_death_name").val();
        var relative_name=$("#relative_name").val();
        var relationship_type=$("#relationship_type").val();
        var relative_phone_number=$("#relative_phone_number").val();
        var relative_Address=$("#relative_Address").val();
        var place_of_death=$("#place_of_death").val();
        var dead_after_before=$("#dead_after_before").val();
        var send_notsend_to_morgue=$("input[name=send_notsend_to_morgue]:checked").val();

    
    if(course_of_death ==""){
        alert("fill the field course of death");
    }else if(Docto_confirm_death_name ==""){
        alert("fill the field doctor confirm death");
    } else if(relative_name ==""){
        alert("fill the field relative name");
    } else if( relationship_type =="" ){
        alert("fill the field relation type");
    } else if( relative_phone_number ==""){
        alert("fill the field relative phone number");
    } else if( relative_Address == ""){
        alert("fill the field relative address");

    } else if( place_of_death == "" ){
        alert("fill the field place of death");

    } else if( dead_after_before=="" ){
        alert("fill the field dead after before");
    } else if( send_notsend_to_morgue=="" ){
        alert("fill the field send to morgue");
    } else if( death_date_time==""){
        alert("fill the field death date");
    }else {
        $.ajax({ 
        type:'POST',
        url:'save_deceased_patients.php',
        data:{action:'save',relative_name:relative_name,relationship_type:relationship_type,relative_phone_number:relative_phone_number,relative_Address:relative_Address,Registration_ID:Registration_ID,place_of_death:place_of_death,death_date_time:death_date_time,course_of_death:course_of_death,Docto_confirm_death_name:Docto_confirm_death_name,dead_after_before:dead_after_before,send_notsend_to_morgue:send_notsend_to_morgue},
        success:function(data){
            console.log(data);
            alert(data);
            $("#store_death_discharged_info").dialog('close');
        },error:function(x,y,z){
            console.log(x+y+z)
        }
        
    });
        
    }
    

   }

        function ajax_procedure_dialog_open(){
            var consultation_ID = document.getElementById("consultation_ID").value;

            var Registration_ID =$("#Registration_ID").val();
            $.ajax({
                type:'POST',
                url:'psychatric_form_open.php',
                data:{Registration_ID:Registration_ID, consultation_ID:consultation_ID, procedure_dialog:''},
                success:function(responce){                
                    $("#procedure_list").dialog({
                        title: 'ORDER PROCEDURE DONE',
                        width: '75%',
                        height: 500,
                        modal: true,
                    });
                    $("#procedure_list").html(responce)
                    ajax_search_procedure()
                }
            });
        }


        function ajax_search_procedure(Product_Name){
            var Registration_ID = $("#Registration_ID").val();
            $.ajax({
                type:'POST',
                url:'psychatric_form_open.php',
                data:{Product_Name:Product_Name,Registration_ID:Registration_ID, search_procedure:''},
                success:function(responce){
                    $("#Items_Fieldset").html(responce);
                }
            });
        }

        function Get_Item_Price(Item_ID) {
            var Transaction_Type = document.getElementById("Transaction_Type").value;
            var Sponsor_ID = document.getElementById("Sponsor_ID").value;

            var Billing_Type = '';
            if(Transaction_Type == 'Credit'){
                Billing_Type = 'Outpatient Credit';
            }else{
                Billing_Type = 'Outpatient Cash';
            }
    
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            //document.location = "./Get_Items_Price.php?Item_Name="+Item_Name;
            myObject.onreadystatechange = function () {
                data = myObject.responseText;
    
                if (myObject.readyState == 4) {
                    document.getElementById('Price').value = data;
                }
            }; //specify name of function that will handle server response........
    
            myObject.open('GET', 'Get_Items_Price.php?Item_ID=' + Item_ID + '&Billing_Type=' + Billing_Type+'&Sponsor_ID='+Sponsor_ID, true);
            myObject.send();
        }

        function Get_Item_Name(Item_Name,Item_ID){
            var Transaction_Type = document.getElementById("Transaction_Type").value;
            var Sponsor_ID = document.getElementById("Sponsor_ID").value;
            document.getElementById("Quantity").value = 1;
            if(Transaction_Type == 'Credit'){
                if (window.XMLHttpRequest) {
                    My_Object_Verify_Item = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    My_Object_Verify_Item = new ActiveXObject('Micrsoft.XMLHTTP');
                    My_Object_Verify_Item.overrideMimeType('text/xml');
                }
    
                My_Object_Verify_Item.onreadystatechange = function () {
                    dataVerify = My_Object_Verify_Item.responseText;
                    if (My_Object_Verify_Item.readyState == 4) {
                        var feedback = dataVerify;
                        if(feedback == 'yes'){
                            Get_Details_items(Item_Name,Item_ID);
                        }else{
                            document.getElementById("Price").value = 0;
                            document.getElementById("Quantity").value = '';
                            document.getElementById("Item_Name").value = '';
                            document.getElementById("Price").value = '';
                            $("#Non_Supported_Item").dialog("open");
                        }
                    }
                }; //specify name of function that will handle server response........
                My_Object_Verify_Item.open('GET', 'Verify_Non_Supported_Item.php?Item_ID='+Item_ID+'&Sponsor_ID='+Sponsor_ID, true);
                My_Object_Verify_Item.send();
            }else{
                Get_Details_items(Item_Name,Item_ID);
            }
        }

        function Get_Details_items(Item_Name, Item_ID) {
            document.getElementById('Quantity').value = 1;
            document.getElementById('Comment').value = '';
            var Temp = '';
            if (window.XMLHttpRequest) {
                myObjectGetItemName = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetItemName = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetItemName.overrideMimeType('text/xml');
            }
    
            document.getElementById("Item_Name").value = Item_Name;
            document.getElementById("Item_ID").value = Item_ID;
        }

        function Get_Selected_Item(Check_in_type="Procedure"){
            var Transaction_Type = document.getElementById("Transaction_Type").value;
           
            var consultation_ID = document.getElementById("consultation_ID").value;
            var Billing_Type = '';
            if(Transaction_Type == 'Credit'){
                Billing_Type = 'Outpatient Credit';
            }else{
                Billing_Type = 'Outpatient Cash';
            }
    
            var Item_ID = document.getElementById("Item_ID").value;
            var Item_Name = document.getElementById("Item_Name").value;
            var Sponsor_ID = document.getElementById("Sponsor_ID").value;
            var Quantity = document.getElementById("Quantity").value;
            var Registration_ID = document.getElementById("Registration_ID").value;
            var Comment = document.getElementById("Comment").value;
            var Guarantor_Name = document.getElementById("Guarantor_Name").value;
            var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
    
            var Price = document.getElementById("Price").value;
            if (parseFloat(Price) > 0) {
    
            } else {
                if( Item_ID != null && Item_ID != ''){
                    alert('Selected Item missing price.');
                    exit;
                }
            }
    
            if (Registration_ID != '' && Registration_ID != null && Item_Name != '' && Item_Name != null && Item_ID != '' && Item_ID != null && Sub_Department_ID != null && Sub_Department_ID != '') {
                if (window.XMLHttpRequest) {
                    myObjectLabs = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectLabs = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectLabs.overrideMimeType('text/xml');
                }
                myObjectLabs.onreadystatechange = function () {
                    dataLab = myObjectLabs.responseText;
                    if (myObjectLabs.readyState == 4) {
                        document.getElementById('Selected_Investigation_Area').innerHTML = dataLab;
                        document.getElementById("Item_Name").value = '';
                        document.getElementById("Item_ID").value = '';
                        document.getElementById("Quantity").value = '';
                        document.getElementById("Price").value = '';
                        document.getElementById("Comment").value = '';
                        document.getElementById("Search_Value").focus();
                        Display_Pharmaceutical_And_Lab_Test_Given();
                    }
                }; //specify name of function that will handle server response........
    
                myObjectLabs.open('GET', 'psychatric_form_open.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Quantity=' + Quantity + '&Billing_Type=' + Billing_Type + '&Guarantor_Name=' + Guarantor_Name + '&Sponsor_ID=' + Sponsor_ID + '&Billing_Type=' + Billing_Type + '&Comment=' + Comment+'&Transaction_Type='+Transaction_Type+'&consultation_ID='+consultation_ID+'&Sub_Department_ID='+Sub_Department_ID+'&Check_in_type='+Check_in_type+'&Price='+Price+'&save_procedure=', true);
                myObjectLabs.send();
    
            } else if (Registration_ID != '' && Registration_ID != null && (Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) != '' && Quantity != '' && Quantity != null) {
                alertMessage();
            } else {
                if (Quantity == '' || Quantity == null) {
                    document.getElementById("Quantity").value = 1;
                }
    
                if(Sub_Department_ID == '' || Sub_Department_ID == null){
                    document.getElementById("Sub_Department_ID").focus();
                    document.getElementById("Sub_Department_ID").style = 'border: 3px solid red';
                }else{
                    document.getElementById("Sub_Department_ID").style = 'border: 2px solid black';
                }
            }
        }

        function Remove_Investigation(Payment_Item_Cache_List_ID){
            var consultation_ID = document.getElementById("consultation_ID").value;
           
            if(window.XMLHttpRequest) {
                myObjectlb = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectlb = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectlb.overrideMimeType('text/xml');
            }
    
            myObjectlb.onreadystatechange = function (){
                dataRemLab = myObjectlb.responseText;
                if (myObjectlb.readyState == 4) {
                    document.getElementById('Selected_Investigation_Area').innerHTML = dataRemLab;
                    Display_Pharmaceutical_And_Lab_Test_Given();
                }
            }; //specify name of function that will handle server response........
            myObjectlb.open('GET','psychatric_form_open.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&consultation_ID='+consultation_ID+'&removeorder=',true);
            myObjectlb.send();
        }

       
        function  closeDialog(){
            var consultation_ID = document.getElementById("consultation_ID").value;

            var Registration_ID =$("#Registration_ID").val();
            $.ajax({
                type:'POST',
                url:'psychatric_form_open.php',
                data:{Registration_ID:Registration_ID, consultation_ID:consultation_ID, display_procedure:''  },
                success:function(responce){
                    $("#proposed_procedure").html(responce);
                    $("#procedure_list").dialog("close")
                }
            });
        }

        function remove_anasthesia_procedure(Procedure_ID){
            $.ajax({
                type:'POST',
                url:'psychatric_form_open.php',
                data:{Procedure_ID:Procedure_ID, remove_procedure:''},
                success:function(responce){
                    display_selected_procedure()
                }
            });
        }
        function view_procedure_selected(){
            var Registration_ID =$("#Registration_ID").val(); 
            var Payment_Cache_ID=$("#Payment_Cache_ID").val();
        
            $.ajax({
                type:'POST',
                url:'psychatric_form_open.php',
                data:{Registration_ID:Registration_ID, Payment_Cache_ID:Payment_Cache_ID,view_procedure:''},
                success:function(responce){
                    $("#proposed_procedure").val(responce);
                    $("#procedure_list").dialog("close")
                }
            });
        }

        function procedureopen(){
            $.ajax({
                type:'POST',
                url:'psychatric_form_open.php',
                data:{patientprocedure:''},
                success:function(responce){
                    $("#patientprocedure").dialog({
                        title: "ORDER PROCEDURE",
                        width: '50%',
                        height: 250,
                        modal: true,
                    });
                    $("#patientprocedure").html(responce)
                }
            });
        }

   $('#death_date_time').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#death_date_time').datetimepicker({
        value: '',
        step: 1
    });
