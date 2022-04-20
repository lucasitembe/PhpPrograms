<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Current_Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Theater_Works'])){
	    if($_SESSION['userinfo']['Theater_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    $Current_Sub_Department_Name = $_SESSION['Theater_Department_Name'];

    $Current_Sub_Department_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Current_Sub_Department_Name' limit 1"))['Sub_Department_ID'];
    $display = "<option value='".$Current_Sub_Department_ID."' selected='selected'>".$Current_Sub_Department_Name."</option>";
    
    $select_sub_departments = mysqli_query($conn, "SELECT Sub_Department_Name,sdep.Sub_Department_ID FROM tbl_department dep, tbl_sub_department sdep, tbl_employee_sub_department ed WHERE dep.department_id = sdep.department_id AND sdep.Sub_Department_Status = 'active' AND ed.Employee_ID = '$Current_Employee_ID' AND  ed.Sub_Department_ID = sdep.Sub_Department_ID AND Department_Location = 'Surgery' AND Sub_Department_Name <> '$Current_Sub_Department_Name'");
    while($rows = mysqli_fetch_array($select_sub_departments)){
        $Sub_Department_Name = $rows['Sub_Department_Name'];
        $Sub_Department_ID = $rows['Sub_Department_ID'];

        $display .= "<option value='".$Sub_Department_ID."'>".$Sub_Department_Name."</option>";
    }
?>

    <a href='theaterworkspage.php?TheaterWorkPage=TheaterWorkPageThisPage' class='art-button-green'>
	BACK
    </a>
    <!-- <div style='float: right;'>
<span style='font-weight: bold; font-size: 18px;'>COLOR CODE KEY: <span>
<input type="button"  style='background: green; padding: 10px; color: #fff; border: none;' value='SIGNED CONSENT' name="" id="">
<input type="button"  style='background: #ff8080; padding: 10px; border: none;' value='SIGNED & AGREED AMPUTATION' name="" id="">
</div> -->
    <!-- <input type="button" value="BACK" onclick="history.go(-1)" class="art-button-green"> -->

    <style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
    tr:hover{
        background-color:#dedede !important;
        cursor:pointer;
    }
</style>
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $Age = $Today - $original_Date; 
    }
?>

<br/><br/>

<center>
<fieldset width=''>
    <table  class="table table-collapse" style="border-collapse: collapse;border:1px solid black; width: 100%">
        <tr>
            <td width="10%">
                <input type='text' name='Date_From' title='Incase You want to filter by Date, Fill these Date fields' placeholder='Start Date' id='date_From' style="text-align: center">    
            </td>
            <td width="10%">
                <input type='text' name='Date_To' title='Incase You want to filter by Date, Fill these Date fields' placeholder='End Date' id='date_To' style="text-align: center"></td>  
            <td width="10%"> 
                <input type='text' name='Patient_Name' title='Incase You want to filter by Name'  id='Patient_Name' style='text-align: center;' onkeyup='filterPatient()' placeholder='~~~~Search Patient Name~~~~~'>
            </td>
            <td width="10%"> 
                <input type='text' name='Patient_Number' title='Incase You want to filter by Registration Number'  id='Patient_Number' style='text-align: center;'  onkeyup='filterPatient()' placeholder='~~~Search Patient Number~~~'>
            </td>
            <td width="10%">
                    <select name='Sponsor_ID' id='Sponsor_ID' onchange='filterPatient()'  style='text-align: center;width:100%;display:inline'>
                        <option value="All">All Sponsors</option>
                        <?php
                        $qr = "SELECT * FROM tbl_sponsor";
                        $sponsor_results = mysqli_query($conn,$qr);
                        while ($sponsor_rows = mysqli_fetch_assoc($sponsor_results)) {
                            ?>
                            <option value='<?php echo $sponsor_rows['Sponsor_ID']; ?>'><?php echo $sponsor_rows['Guarantor_Name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
                
                <td width="10%">
                    <select name='Employee_ID' onchange='filterPatient()'  id='Employee_ID' style='text-align: center;width:100%;display:inline'>
                        <option value="All">All Doctors</option>
                        <?php
                        $qr = "SELECT * FROM tbl_employee WHERE Employee_Type LIKE '%Doctor%' AND Account_Status = 'active'";
                        $sponsor_results = mysqli_query($conn,$qr);
                        while ($sponsor_rows = mysqli_fetch_assoc($sponsor_results)) {
                            ?>
                            <option value='<?php echo $sponsor_rows['Employee_ID']; ?>'><?php echo $sponsor_rows['Employee_Name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td width= '15%'>
                    <select name="Sub_Department_ID" id="Sub_Department_ID"  onchange='filterPatient()' style='width: 100%'>
                        <option value="All" selected='selected'>All Department</option>
                        <?php
                            echo $display;
                        ?>
                    </select>
                </td>
                <td width= '5%'>
                    <select id="Surgical_Type" onchange="filterPatient()">
                        <option>All</option>
                        <option>Rejected</option>
                    </select>
                </td>
                <td width='15%'>
                    <input type='submit' name='Print_Filter' id='Print_Filter' onclick='filterPatient()' class='art-button-green' value='FILTER'>
                
                <input type='button' name='Print_Filter' id='Print_List' class='art-button-green' value='PRIVIEW &amp; PRINT'>
                <!-- <input type='button'  onclick='check_assign()' class='art-button-green' value='PREPARE LIST'> -->
                </td>
        </tr>
    </table>
<!-- <center><p style="margin-top:10px;color: #0079AE;font-weight:bold;"><i> Click Surgery Date in case you want to edit it </i></p></center> -->

</fieldset>
</center>
<fieldset>  
    <legend align=center><b>SURGERY PROGRESS MONITORING</b></legend>
    <div class="box box-primary" style="height: 540px;overflow-y: scroll;overflow-x: hidden">
        <table class="table table-collapse table-striped " style="border-collapse: collapse !important">
        <tr style='background: #dedede; position: static !important;'>
                <th>S/No</th>
                <th style='text-align: left;'>REGISTRATION #</th>
                <th style='text-align: left;'>PATIENT NAME</th>
                <th style='text-align: left;'>SURGERY NAME</th>
                <th style='text-align: left;'>SERVICE DATE</th>
                <th style='text-align: left;'>DEPARTMENT</th>
                <th style='text-align: left;'>SURGEON</th>
                <!-- <th>THEATER ROOM</th> -->
                <th>PRIORITY</th>
                <th>ACTION</th>
            </tr>
            <tbody id='Search_Iframe'>
        </table>
    </div>
</fieldset>
<center><p style="margin-top:10px;color: #0079AE;font-weight:bold;"><i> Click Surgery Date in case you want to edit it </i></p></center>
<div id="Submit_data">
    <center id='Appointment_area'>
    </center>
</div>
<div id="changeDateDiv" style="display: none">
     <input type='text' name='Date_From' id='date_Fromx' required='required' style="padding-left:5px; margin-bottom: 5px;" autocomplete="off">
     <input type='text' required='required' placeholder="Reason For Changing Date">
<input type='hidden' name='Date_From' id='date_From_val' value="">

      <br /><br />
      <center> <input type="button" value="Save Changes" class="SaveChangedDate"></center>
</div>
<div id="rejection_reason_panel" style="display: none;">
    <input type='text' required='required' id="rejection_reason" placeholder="Reason For Rejection">
<input type='hidden' name='Date_From' id='Payment_Item_Cache_List_ID' value="">
                    <br>
                    <br>
    <center> <input type="button" value="Save Changes" onclick='save_reason()' class="art-button-green"></center>
</div>

<script src="js/select2.min.js"></script>
<link rel="stylesheet" href="css/uploadfile.css" media="screen">
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />   
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script><script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery-ui.js"></script>
<script src="css/scripts.js"></script>
<script src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/ui.notify.css" media="screen">
<script src="js/jquery.notify.min.js"></script> 

<script language="javascript" type="text/javascript">
    $(document).ready(function () {
        $("#Submit_data").dialog({autoOpen: false, width: '85%', height: 650, title: 'PREPARE OPERATION LIST', modal: true});
    });
    $(document).ready(function(){
            filterPatient();
	})
    
    function Date_Time(Payment_Item_Cache_List_ID) {
        var Date_Time = "Date_Time"+Payment_Item_Cache_List_ID;
        var Current_Date = document.getElementById("Date_Time" + Payment_Item_Cache_List_ID).value;
        // alert(Payment_Item_Cache_List_ID);
        // exit();
        // document.getElementById("date_From").value = Payment_Item_Cache_List_ID;
        $("#date_From_val").val( Payment_Item_Cache_List_ID );

        $('#changeDateDiv').dialog({
            modal: true,
            width: '30%',
            resizable: true,
            draggable: true,
            title: 'Change Surgery Date'
        });
    }
    function add_reason(Payment_Item_Cache_List_ID) {
        assign = $("#assign"+Payment_Item_Cache_List_ID).val();
        // reason = $("#reason").val();
        // var Payment_Item_Cache_List_ID = document.getElementById("assign" + Payment_Item_Cache_List_ID).value;
        var Employee_ID = '<?= $Current_Employee_ID ?>';
        if(confirm("You are about to change Surgery Status, Do you want to Proceed?")){
            $.ajax({
                type: "GET",
                url: "ajax_set_Surgery_appointment.php",
                data: {Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,assign:assign,Employee_ID:Employee_ID},
                cache: false,
                success: function (response) {
                    alert(response);
                    filterPatient();
                }
            });
        }

    }
    function save_reason(){
        rejection_reason = $("#rejection_reason").val();
        Payment_Item_Cache_List_ID = $("#Payment_Item_Cache_List_ID").val();
        Employee_ID = '<?= $Current_Employee_ID ?>';

        if(Payment_Item_Cache_List_ID != "" && rejection_reason != undefined){
            if(confirm("Are sure you want to Reject This Surgery Appointment?")){
                $.ajax({
                    type: "GET",
                    url: "ajax_set_Surgery_appointment.php",
                    data: {Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,assign:"Reject",Employee_ID:Employee_ID,rejection_reason:rejection_reason},
                    cache: false,
                    success: function (response) {
                    $("#rejection_reason_panel").dialog("close");
                    }
                });
            }
        }
    }
    function filterPatient(){
		Patient_Number = document.getElementById('Patient_Number').value;
		Patient_Name = document.getElementById('Patient_Name').value;
		Sponsor_ID = document.getElementById('Sponsor_ID').value;
		Employee_ID = document.getElementById('Employee_ID').value;
		date_From = document.getElementById('date_From').value;
		date_To = document.getElementById('date_To').value;
		Sub_Department_ID = document.getElementById('Sub_Department_ID').value;
        Current_Employee_ID = '<?= $Current_Employee_ID?>';
        Surgical_Type = $("#Surgical_Type").val();

        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        
        // document.getElementById('Search_Iframe').innerHTML ="<iframe width='100%' height=380px src='Prepare_Surgery_Appointments_filter.php?date_To="+date_To+"&date_From="+date_From+"&Employee_ID="+Employee_ID+"&Sponsor_ID="+Sponsor_ID+"&Patient_Name="+Patient_Name+"&Sub_Department_ID="+Sub_Department_ID+"&Patient_Number="+Patient_Number+"&Current_Employee_ID="+Current_Employee_ID+"'></iframe>";


        // Sub_Department_ID = document.getElementById('Sub_Department_ID').value;
        // Current_Employee_ID = '<?= $Current_Employee_ID?>';

        if (window.XMLHttpRequest) {
            myObjectPost = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPost.overrideMimeType('text/xml');
        }

        myObjectPost.onreadystatechange = function () {
            dataPost = myObjectPost.responseText;
            if (myObjectPost.readyState == 4) {
                document.getElementById('Search_Iframe').innerHTML = dataPost;
                // $("#Submit_data").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectPost.open('GET', 'ajax_Surgery_Progress_Monitoring.php?Current_Employee_ID='+Current_Employee_ID+'&Sub_Department_ID='+Sub_Department_ID+'&Patient_Number='+Patient_Number+'&Patient_Name='+Patient_Name+'&Sponsor_ID='+Sponsor_ID+'&date_To='+date_To+'&date_From='+date_From+'&Surgical_Type='+Surgical_Type, true);
        myObjectPost.send();
    }
    function check_assign() {
		Sub_Department_ID = document.getElementById('Sub_Department_ID').value;
        Current_Employee_ID = '<?= $Current_Employee_ID?>';

        if (window.XMLHttpRequest) {
            myObjectPost = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPost.overrideMimeType('text/xml');
        }

        myObjectPost.onreadystatechange = function () {
            dataPost = myObjectPost.responseText;
            if (myObjectPost.readyState == 4) {
                document.getElementById('Appointment_area').innerHTML = dataPost;
                $("#Submit_data").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectPost.open('GET', 'ajax_Surgery_Progress_Monitoring.php?Current_Employee_ID='+Current_Employee_ID+'&Sub_Department_ID='+Sub_Department_ID, true);
        myObjectPost.send();
        // $("#Submit_data").dialog("open");
    }
    $('#Print_List').on('click',function(){
     var Patient_Number=$('#Patient_Number').val();
     var Patient_Name=$('#Patient_Name').val();
     var date_From=$('#date_From').val();
     var date_To=$('#date_To').val();
     var Employee_ID=$('#Employee_ID').val();
     var Sub_Department_ID=$('#Sub_Department_ID').val();

     var Sponsor_ID=$('#Sponsor_ID').val(); 
     
     window.open('Print_Doctor_Surgery_Schedule.php?action=filter&Sub_Department_ID='+Sub_Department_ID+'&date_From='+date_From+'&date_To='+date_To+'&Sponsor_ID='+Sponsor_ID+'&Employee_ID='+Employee_ID+'&Patient_Name='+Patient_Name+'&Patient_Number='+Patient_Number);
        
    });
        
    $('.SaveChangedDate').on('click',function(){
        var pay_ID=$('#date_From_val').val();
        var DateOfService=$('#date_Fromx').val();
        if(DateOfService=='' ||DateOfService=='NULL'){
            alert('Date cannot be empty,please select date');
            return false;
        }
        if(confirm('Are you sure you want change this date?')){
        $.ajax({
        type:'POST',
        url:'requests/Update_sugery_date.php',
        data:'action=update&pay_ID='+pay_ID+'&DateOfService='+DateOfService,
        cache:false,
        success:function(e){
            filterPatient();
            $("#changeDateDiv").dialog("close");
        
            // location.reload();
        }
        }); 
        }else{
            return false;
        }
    
     
  }); 

  function set_priority(Payment_Item_Cache_List_ID){
    // var priority = "priority"+Payment_Item_Cache_List_ID;
    var Set_priority = document.getElementById("priority" + Payment_Item_Cache_List_ID).value;

    if(Set_priority === 'Urgent'){
        if(confirm("Do you want to Process this Surgery as Emergency Case? This Surgery will go Directly to Theater")){
            $.ajax({
                type: "POST",
                url: "update_surgery_priority.php",
                data: {priority:Set_priority,Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID},
                casche: false,
                success: function (response) {
                    filterPatient();
                
                }
            });
        }
    }else{
        $.ajax({
            type: "POST",
            url: "update_surgery_priority.php",
            data: {priority:Set_priority,Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID},
            casche: false,
            success: function (response) {
                filterPatient();
            
            }
        });
    }
}
    // set_room(Payment_Item_Cache_List_ID)
//   }
//   function set_room(Payment_Item_Cache_List_ID) {
//         var Payment_Item_Cache_List_ID = Payment_Item_Cache_List_ID;
//         var id = "room_name_" + Payment_Item_Cache_List_ID;
//         var room_id = document.getElementById("room_name_" + Payment_Item_Cache_List_ID).value;

//         if (room_id == 0) {
//             alert("Please Select Theater Room fro This Surgery")

//         } else {

//             $.ajax({
//                 url: 'update_sugery_room.php',
//                 method: 'POST',
//                 dataType: 'text',
//                 data: {
//                     update_request: "update",
//                     Payment_Item_Cache_List_ID: Payment_Item_Cache_List_ID,
//                     room_id: room_id
//                 },
//                 success: function(response) {
//                     check_assign();
//                     // alert(response)
//                 }
//             });
//         }
//     }
    function save_surgery_list() {
        Employee_ID = '<?= $Current_Employee_ID ?>';
        Sub_Department_ID = $("#Sub_Department_ID").val();
        if(confirm("You're about to save Surgery Appointment List, Do you want to Proceed?")){
            $.ajax({
                type: "POST",
                url: "update_surgery_list.php",
                data: {Employee_ID:Employee_ID,Sub_Department_ID:Sub_Department_ID,Action:'Approve'},
                dataType: false,
                success: function (response) {
                    alert("Surgery List was Approved Successfully");
                    filterPatient();

                }
            });
        }
    }
</script>

<script>
    $('#date_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_From').datetimepicker({value: '', step: 1});
    $('#date_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_To').datetimepicker({value: '', step: 1});
    
         $('#date_Fromx').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_Fromx').datetimepicker({value: '', step: 1});

    $(document).ready(function (e){
        $("#Sponsor_ID").select2();
        $("#Employee_ID").select2();
        $("#Sub_Department_ID").select2();
        $("#Surgical_Type").select2();
        $(".room_select").select2();
    });
</script>

<?php
    include("./includes/footer.php");
?>