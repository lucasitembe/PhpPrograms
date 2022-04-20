<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    session_start();
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'])){
	    if($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    if(isset($_GET['frompage']) && $_GET['frompage'] == "INPATIENT"){
    ?>
    <!-- <a href='admittedpatientlist.php?' class='art-button-green'>
        BACK
    </a>-->
    <?php }else if($_GET['pagefrom'] == "APPOINTMENT"){
        $Registration_ID =$_GET['Registration_ID'];
        ?> 
        <!-- <a href='appointmentPage.php?Registration_ID=<?= $Registration_ID ?>' class='art-button-green'>
            BACK
        </a>     -->
    <?php }else{?>
        <!-- <a href='clinicpatientlist.php?SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
        BACK -->
    </a>
    <?php }?> 
    <a href="#" onclick="goBack()"class="art-button-green">BACK</a>
    <script>
    function goBack() {
        window.history.back();
    }
    </script>
    <br><br>
    <fieldset>

    <div class="col-md-12">
            <div class="box box-primary" style="overflow: auto;" >
                <div class="box-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>PATIENT NAME (#REG.)</th>
                            <th>REQUESTED DATE</th>
                            <th>REQUESTED BY</th>                           
                            <th>ACTION</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php $num=0;
                             $finance_department_id = $_SESSION['finance_department_id'];
                             
                            $select_reqeust = mysqli_query($conn, "SELECT Date_of_request,rc.Registration_ID,Request_Consultation_ID,Patient_Name,Request_from FROM tbl_patient_registration pr, tbl_request_for_consultation rc WHERE Request_to='$finance_department_id'  AND pr.Registration_ID=rc.Registration_ID AND rc.Request_Consultation_ID NOT IN (SELECT Request_Consultation_ID FROM tbl_consultation_request_replay) ORDER BY Request_Consultation_ID DESC") or die(mysqli_error($select_reqeust));
                            if((mysqli_num_rows($select_reqeust))>0){
                            while($rq_rw =mysqli_fetch_assoc($select_reqeust)){
                                $Registration_ID = $rq_rw['Registration_ID'];
                                // $Employee_Name =$rq_rw['Employee_Name'];
                                $Patient_Name = $rq_rw['Patient_Name'];
                                $Request_from = $rq_rw['Request_from'];
                                $Date_of_request = $rq_rw['Date_of_request'];
                                $Request_Consultation_ID = $rq_rw['Request_Consultation_ID'];
                                $num++;
                                echo "<tr><input id='Patient_Name' value='$Patient_Name' type='text' style='display:none'>
                                <td>$num</td>
                                <td>$Patient_Name &nbsp; ( $Registration_ID)</td>

                                <td>$Date_of_request</td>
                                <td>$Request_from</td>                                
                                <td>
                                <div class='btn-group'>";
                                   // <span><a href='Preview_consultation_request_burn.php?Request_Consultation_ID=$Request_Consultation_ID&Registration_ID=$Registration_ID' class='btn btn-info btn-xs'>Preview</a>&nbsp;
                                  echo"  <a href='#' class='art-button-green' type='button' onclick='display_request_forms($Registration_ID)'> PREVIEW</a>

                                   </span>
                                </div>
                                </td>
                            </tr>";
                            }
                        }else{
                            echo "<tr>
                                    <td colspan='6' ><h4 class='text-center text-danger'>No any request</h4></td>
                                </tr>";
                        }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </fieldset>

    <div id="open_consultation_form_dialogy_div" style="display:none">
        <div class="col-md-6"> 
            <a class="btn btn-default btn-block" name="request_type" onclick="display_request_form(<?php echo $Registration_ID;?>)">WRITE REQUEST</a>
        </div>
        <!-- <div class="col-md-4"> 
            <a class="btn btn-default btn-block" >NEW REQUEST</a>
        </div> -->
        <div class="col-md-6"> 
            <a class="btn btn-default btn-block" name="pre_request" onclick="display_request_forms(<?php echo $Registration_ID;?>)">PREVIOUS REQUEST</a>
        </div>
        <div class="col-md-12"><hr> </div>
        <div class="col-md-12" style="height:70vh;overflow-y: scroll;" id="consultation_form_request_body"> 
            
        </div>
    </div>
<script>
 function display_request_form(Registration_ID){
        $.ajax({
            type:'POST',
            url:'ajax_display_request_form.php',
            data:{request_type:'', Registration_ID:Registration_ID},
            success:function(data){
                $("#consultation_form_request_body").html(data);
                $('select').select2();
            }
        });
    }
    function display_request_forms(Registration_ID){
        var Patient_Name = $("#Patient_Name").val();
        $.ajax({
            type:'POST',
            url:'ajax_display_request_form.php',
            data:{pre_request:'', Registration_ID:Registration_ID},
            success:function(responce){

            $("#open_consultation_form_dialogy_div").dialog({
                title: 'CONSULTATION FORM  '+Patient_Name+" "+Registration_ID,
                width: '90%',
                height: 600,
                modal: true,
            });     
                $("#consultation_form_request_body").html(responce);
               // $('select').select2();
            }
        });
    }
    function reply_request_consultation(Request_Consultation_ID, Registration_ID){
        $.ajax({
            type:'POST',
            url:'ajax_display_request_form.php',
            data:{preview_request:'', Registration_ID:Registration_ID,Request_Consultation_ID:Request_Consultation_ID},
            success:function(responce){
                $("#consultation_form_request_body").html(responce);
               // $('select').select2();
               display_replay()
            }
        });
    }
    function Replay_consultation_request(Registration_ID,Request_Consultation_ID){
        var consultation_request_replay = $("#consultation_request_replay").val();
        var Patient_Name = $("#Patient_Name").val();
        if(consultation_request_replay==""){
            $("#consultation_request_replay").css("border","2px solid red");
        }else{
            $("#consultation_request_replay").css("border","");
        $.ajax({
            type:'POST',
            url:'Ajax_save_burn_unit.php', 
            data:{consultation_request_replay:consultation_request_replay,Registration_ID:Registration_ID,Request_Consultation_ID:Request_Consultation_ID,replay_btn:'' },
            success:function(success){
                $("#open_consultation_form_dialogy_div").dialog({
                    title: 'CONSULTATION FORM  '+Patient_Name+" "+Registration_ID,
                    width: '90%',
                    height: 700,
                    modal: true,
                });
                display_replay()
                $("#consultation_request_replay").val();
               
            }
        })
        }
    }
    
    function display_replay(){
        var Request_Consultation_ID = $("#Request_Consultation_ID").val();
        var Registration_ID = $("#Registration_ID").val();
        
            $.ajax({
                type:'POST',
                url:'ajax_display_request_form.php',
                data:{Request_Consultation_ID:Request_Consultation_ID,Registration_ID:Registration_ID, reply_body:''},
                success:function(responce){
                    $("#reply_body").html(responce);
                }
            })
        
    }
    function update_consultation_request(Request_Consultation_ID){
        var Request_type = "";
        if($("#Emergency").is(":checked")){
            Request_type +="Emergency"+','
           }
        if($("#Urgent").is(":checked")){
            Request_type +="Urgent"+','
           }
        if($("#Routine").is(":checked")){
            Request_type +="Routine"+','
           }
           
        var Brief_case_summary =$("#Brief_case_summary").val();
        var Question = $("#Question").val();
       // var Request_from = $("#Request_from").val();
        var Request_to = $("#Request_to").val();
        var Diagnosis = $("#Diagnosis").val(); 
        if(Brief_case_summary==""){
            $("#Brief_case_summary").css("border","1px solid red");
        }else if(Diagnosis==""){
            $("#Diagnosis").css("border","1px solid red");
        }else   if(Request_to==""){
            $("#Request_to").css("border","1px solid red");
        
        }else{
            $("#Diagnosis").css("border","");
            $("#Request_to").css("border","");
            $("#Brief_case_summary").css("border","");
        if(confirm('Are you sure want to update this info??')){
            $.ajax({
                type:'POST',
                url:'Ajax_save_burn_unit.php',
                data:{Request_type:Request_type,Request_Consultation_ID:Request_Consultation_ID, Request_to:Request_to, Diagnosis:Diagnosis,Brief_case_summary:Brief_case_summary,Question:Question,request_btn_update:''},
                success:function(responce){
                    alert(responce);
                }
            });
        }
    }
    }
    function save_request_form_data(Registration_ID){
        var Request_type = "";
        if($("#Emergency").is(":checked")){
            Request_type +="Emergency"+','
           }
        if($("#Urgent").is(":checked")){
            Request_type +="Urgent"+','
           }
        if($("#Routine").is(":checked")){
            Request_type +="Routine"+','
           }
           
        var Brief_case_summary =$("#Brief_case_summary").val();
        var Question = $("#Question").val();
        var Request_from = $("#Request_from").val();
        var Request_to = $("#Request_to").val();
        var Diagnosis = $("#Diagnosis").val(); 
        if(Brief_case_summary==""){
            $("#Brief_case_summary").css("border","1px solid red");
        }else if(Diagnosis==""){
            $("#Diagnosis").css("border","1px solid red");
        }else if(Request_to==""){
            $("#Request_to").css("border","1px solid red");
        }else{
            $("#Diagnosis").css("border","");
            $("#Request_to").css("border","");
            $("#Brief_case_summary").css("border","");
        if(confirm('Are you sure want to save this info??')){
            $.ajax({
                type:'POST',
                url:'Ajax_save_burn_unit.php',
                data:{Registration_ID:Registration_ID,Request_type:Request_type,Request_from:Request_from,Request_to:Request_to, Diagnosis:Diagnosis,Brief_case_summary:Brief_case_summary,Question:Question,request_btn:''},
                success:function(responce){
                    alert("Saved successful");
                }
            });
        }
        }
    }
    function open_consultation_form_dialogy(Registration_ID){
        var Patient_Name = $("#Patient_Name").val();
         $("#open_consultation_form_dialogy_div").dialog({
            title: 'CONSULTATION FORM  '+Patient_Name+" "+Registration_ID,
            width: '90%',
            height: 600,
            modal: true,
        }); 
    }


</script>