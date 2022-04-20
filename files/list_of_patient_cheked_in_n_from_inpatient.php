<?php
include("./includes/header.php");
include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<?php
    $Today="";
    $today_start="";
    $Today_Date = mysqli_query($conn, "select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $Today = $row['today'];
    }
    $today_start_date=mysqli_query($conn, "select cast(current_date() as datetime)");
    while($start_dt_row=mysqli_fetch_assoc($today_start_date)){
        $today_start=$start_dt_row['cast(current_date() as datetime)'];
    }
?>
<a href="morguepageregistration.php?from=registerBody" class="art-button-green">Morgue Register</a>
<a href="morgueupdate_infomation.php?from=registerBody" class="art-button-green">Update Admission</a>
<a href="morguepage.php" class="art-button-green">BACK</a>
<br>
<br>
<br>
<br>
<style>
        #filter_tbl,#filter_tbl tr,#filter_tbl tr td,#filter_tbl tr td table{
           border-collapse:collapse !important;
           border:none !important; 
        }
 </style> 
<fieldset>
    <center>
        <table  id="filter_tbl">
            <tr>
                <!-- filter_button -->
            <td style="text-align: center">
                    <input type="text" autocomplete="off" style='text-align: center;width:23%;display:inline' id="Date_From" placeholder="Start Date" value="<?php echo $_GET['Date_From'] ?>"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:23%;display:inline' id="Date_To" placeholder="End Date" value="<?php echo $_GET['Date_To'] ?>"/>&nbsp;
                    <input type="button" value="Filter" class="art-button-green" onclick="filter_patient()">
            </td>
                <td>
                    <input type="text" id="patient_name" onkeyup="filter_patient()" placeholder="Enter Deceased Patient Name">
                </td>
                <td>
                    <input type="text" id="patient_number"  onkeyup="filter_patient()"  placeholder="Enter Deceased Patient Number">
                </td>
                
            </tr>
        </table>
    </center>
    <center> <img id="ajax_loder" style="display: none"src="images/ajax-loader_1.gif" width="" style="border-color:white "></center>
</fieldset>
<fieldset>
    <legend align=center><b>LIST OF  DISCHARGED BODY FROM WARDS AND OUTSIDE TO BE ADMITTED IN MORTUARY</b></legend>
    <div class="row">
        <div class="col-sm-12" id="patient_list">
            <table id="list_of_checked_in_n_discharged_tbl" class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width:50px">S/No.</th>
                        <th>DECEASED PATIENT NAME</th>
                        <th>DECEASED PATIENT NUMBER</th>
                        <th>WARD FROM/DISTRIC/CASET</th>
                        <th>TIME OF DISCHARGE/CHECKED IN</th>
                        <th>DISCHARGED/CHECKED IN BY</th>
                    </tr>
                </thead>
                <tbody>
                       <?php 
                            $count=1;
                            $sql_select_patient="SELECT pr.Registration_ID,Patient_Name,pr.District as Hospital_Ward_Name,ci.Check_In_Date_And_Time as dis_check_time,emp.Employee_Name as dischareged_chckin_by FROM tbl_check_in ci INNER JOIN tbl_patient_registration pr ON ci.Registration_ID=pr.Registration_ID INNER JOIN tbl_employee emp ON ci.Employee_ID=emp.Employee_ID WHERE ci.Registration_ID NOT IN (SELECT Corpse_ID as Registration_ID FROM tbl_mortuary_admission) AND pr.Diseased='yes'AND pr.Registration_ID IN (SELECT Patient_ID FROM tbl_diceased_patients WHERE send_notsend_to_morgue='yes') UNION SELECT pr.Registration_ID,Patient_Name,hw.Hospital_Ward_Name,ad.pending_set_time as dis_check_time,emp.Employee_Name as dischareged_chckin_by FROM tbl_patient_registration pr INNER JOIN tbl_admission ad ON pr.Registration_ID=ad.Registration_ID INNER JOIN tbl_discharge_reason dr ON ad.Discharge_Reason_ID=dr.Discharge_Reason_ID INNER JOIN tbl_hospital_ward hw ON ad.Hospital_Ward_ID=hw.Hospital_Ward_ID INNER JOIN tbl_employee emp ON ad.pending_setter=emp.Employee_ID WHERE (ad.Admission_Status='Discharged' OR ad.Admission_Status='pending')AND ward_type<>'mortuary_ward'  AND ad.Registration_ID NOT IN (SELECT Corpse_ID as Registration_ID FROM tbl_mortuary_admission) AND discharge_condition='dead' ORDER BY dis_check_time asc limit 15";
                            $sql_select_patient_result=mysqli_query($conn, $sql_select_patient) or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_patient_result)>0){
                                while($patient_rows=mysqli_fetch_assoc($sql_select_patient_result)){
                                    $Patient_Name=$patient_rows['Patient_Name'];
                                    $Registration_ID=$patient_rows['Registration_ID'];
                                    $Hospital_Ward_Name=$patient_rows['Hospital_Ward_Name'];
                                    $dis_check_time=$patient_rows['dis_check_time'];
                                    $dischareged_chckin_by=$patient_rows['dischareged_chckin_by'];
                                    echo "<tr>
                                            <td><a href='motuary_admission.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$count</a></td>
                                            <td><a href='motuary_admission.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$Patient_Name</a></td>
                                            <td><a href='motuary_admission.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$Registration_ID</a></td>
                                            <td>$Hospital_Ward_Name</td>
                                            <td>$dis_check_time</td>
                                            <td>$dischareged_chckin_by</td>
                                          </tr>";
                                    $count++;
                                }
                            }
                        ?>
                </tbody>
            </table>
        </div>
    </div>
</fieldset>
 <script>

    // end here
     function filter_patient(){
        var patient_name=$("#patient_name").val();
        var patient_number=$("#patient_number").val();
        var Date_From=$("#Date_From").val();
        var Date_To=$("#Date_To").val();
        
         $("#ajax_loder").show();
         $.ajax({
             type:'GET',
             url:'filter_checked_in_n_discharged.php',
             data:{patient_name:patient_name,patient_number:patient_number,Date_From:Date_From,Date_To:Date_To},
             success:function (data){
                $("#patient_list").html(data); 
                $("#ajax_loder").hide();  
                $('#list_of_checked_in_n_discharged_tbl').DataTable({
                        "bJQueryUI": true
                    });
             }
         });
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
    $('#Date_From').datetimepicker({value:'',step:1});
    $('#Date_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:'now'
    });
    $('#Date_To').datetimepicker({value:'',step:1});
    </script>

<script  type="text/javascript">
    
     $(document).ready(function (){
        $('#list_of_checked_in_n_discharged_tbl').DataTable({
            "bJQueryUI": true
        });
     });
</script>


<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<?php
    include("./includes/footer.php");
?>