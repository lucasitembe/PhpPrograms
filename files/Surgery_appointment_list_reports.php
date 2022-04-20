<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Current_Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Sub_Department_Name = $_SESSION['Theater_Department_Name'];

    $display = "<option value='All' selected='selected'>All Departments</option>";
    
    $select_sub_departments = mysqli_query($conn, "SELECT finance_department_name,ilc.finance_department_id FROM tbl_assign_finance_department ilc, tbl_finance_department dep WHERE ilc.Employee_ID = '$Current_Employee_ID' AND dep.finance_department_id = ilc.finance_department_id AND enabled_disabled = 'enabled'");
    while($rows = mysqli_fetch_array($select_sub_departments)){
        $finance_department_name = $rows['finance_department_name'];
        $finance_department_id = $rows['finance_department_id'];

        $display .= "<option value='".$finance_department_id."'>".$finance_department_name."</option>";
    }
?>

    <input type="button" value="BACK" onclick="history.go(-1)" class="art-button-green">

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
                    '<option value="All" selected="selected">All Sponsors</option>
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
                <td width= '10%'>
                    <select name="Sub_Department_ID" id="Sub_Department_ID"  onchange='filterPatient()' style='width: 100%'>
                        <!-- <option>All</option> -->
                        <?php
                            echo $display;
                        ?>
                    </select>
                </td>
                <td>
                    <select id="Surgical_Type" onchange="filterPatient(), check_status()" style='width: 100%;'>
                        <option value='Completed'>Completed</option>
                        <option value='Rejected'>PostPoned</option>
                        <option value='Death'>Death</option>
                        <option value='Active'>Waiting Status</option>
                    </select>
                </td>
                <td width='23%'>
                    <input type='submit' name='Print_Filter' id='Print_Filter' onclick='filterPatient()' class='art-button-green' value='FILTER'>
                
                <input type='button' name='Print_Filter' id='Print_List' class='art-button-green' value='PREVIEW &amp; PRINT'>
                <input type='button'  onclick='check_assign()' class='art-button-green' value='PREVIEW EXCEL'>
                </td>
        </tr>
    </table>
<!-- <center><p style="margin-top:10px;color: #0079AE;font-weight:bold;"><i> Click Surgery Date in case you want to edit it </i></p></center> -->

</fieldset>
</center>
<fieldset>  
    <legend align=center><b>GENERAL SURGERY REPORT</b></legend>
    <div class="box box-primary" style="height: 540px;overflow-y: scroll;overflow-x: hidden">
        <table class="table table-collapse table-striped " style="border-collapse: collapse !important">
            <tr  style='background: #dedede; position: static !important;'>
                <td style="width:2%"><h5><b>S/No</b></h5></td>
                <td style="width:10%; "><h5><b>DOCTOR&#39;S NAME</b></h5></td>
                <td style="width:10%; "><h5><b>PATIENT NAME</b></h5></td>
                <td style="width:5%; "><h5><b>PATIENT #</b></h5></td>
                <td style="width:11%;"><h5><b>AGE</b></h5></td>
                <td style=" width: 4%;"><h5><b>GENDER</b></h5></td>
                <td style="width:7%;"><h5><b>PHONE #</b></h5></td>
                <td style="width:15%;"><h5><b>SURGERY NAME</b></h5></td>
                <td style="width:9%;"><h5><b>SURGERY DATE</b></h5></td>
                <td style="width:7%"><h5><b>LOCATION</b></h5></td>
                <td style="width:7%"><h5><b>ROOM</b></h5></td>
                <td style="width:9%;"><h5><b>CONSENT SIGNED</b></h5></td>
                <td style="width:9%;" class="Completed"><h5><b>REASON</b></h5></td>  
                <td style="width:7%;"><h5><b>STATUS</b></h5></td>     
            </tr>
            <tbody id='Search_Iframe'>
        </table>
    </div>
</fieldset>
<!-- <center><p style="margin-top:10px;color: #0079AE;font-weight:bold;"><i> Click Surgery Date in case you want to edit it </i></p></center>
<div id="Submit_data">
    <center id='Appointment_area'>
    </center>
</div> -->
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
            check_status();
	})
    function check_status(){
        Surgical_Type = $("#Surgical_Type").val();
        Completed = document.querySelector('.Completed')
            if(Surgical_Type == 'Completed' || Surgical_Type == 'Active'){
                Completed.style.display = 'none';
            }else{
                Completed.style.display = 'block';
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

        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><center><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></center></div>';

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

        myObjectPost.open('GET', 'Surgery_appointment_list_reports_filter.php?Current_Employee_ID='+Current_Employee_ID+'&Sub_Department_ID='+Sub_Department_ID+'&Patient_Number='+Patient_Number+'&Patient_Name='+Patient_Name+'&Sponsor_ID='+Sponsor_ID+'&date_To='+date_To+'&date_From='+date_From+'&Surgical_Type='+Surgical_Type, true);
        myObjectPost.send();
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