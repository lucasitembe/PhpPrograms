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
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $Today = $row['today'];
    }
    $today_start_date=mysqli_query($conn,"select cast(current_date() as datetime)");
    while($start_dt_row=mysqli_fetch_assoc($today_start_date)){
        $today_start=$start_dt_row['cast(current_date() as datetime)'];
    }
?>
<a href="morguepageregistration.php?from=admittedList" class="art-button-green">RegisterNew</a>
<a href="morgueupdate_infomation.php?from=admittedList" class="art-button-green">update</a>
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
    <table  id="filter_tbl">
        <tr>
            <td>
                <input type="text" id="patient_name" onkeyup="filter_patient()" placeholder="Enter Patient Name">
            </td>
            <td>
                <input type="text" id="patient_number"  onkeyup="filter_patient()"  placeholder="Enter Patient Number">
            </td>
            
        </tr>
    </table>
    <center> <img id="ajax_loder" style="display: none"src="images/ajax-loader_1.gif" width="" style="border-color:white "></center>
</fieldset>
<fieldset>
    <legend align=center><b>LIST OF CUSTOMERS ADMITTED TO MORTUARY</b></legend>
    <div class="row">
        <div class="col-sm-12" id="patient_list">
            <table id="list_of_checked_in_n_discharged_tbl" class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width:50px">S/No.</th>
                        <th>PATIENT NAME</th>
                        <th>PATIENT NUMBER</th>
                        <th>WARD ADMITTED</th>
                        <th>CABINET</th>
                        <th>ADMITTED DATE</th>
                        <th>ADMITTED BY</th>
                        <th>RELATIVE NAME</th>
                    </tr>
                </thead>
                <tbody>
                       <?php 
                            $count=1;
                            $sql_select_patient="SELECT hw.Hospital_Ward_Name, ad.Admission_Date_Time, ad.Bed_Name, pr.Patient_Name, pr.Registration_ID, em.Employee_Name, ma.Corpse_Brought_By FROM tbl_mortuary_admission ma, tbl_hospital_ward hw, tbl_patient_registration pr, tbl_employee em, tbl_admission ad WHERE pr.Registration_ID = ad.Registration_ID AND em.Employee_ID = ad.Admission_Employee_ID AND ma.Admision_ID = ad.Admision_ID AND hw.ward_type = 'mortuary_ward' GROUP BY ad.Admision_ID ORDER BY ad.Admision_ID ASC LIMIT 20";
                            $sql_select_patient_result=mysqli_query($conn,$sql_select_patient) or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_patient_result)>0){
                                while($patient_rows=mysqli_fetch_assoc($sql_select_patient_result)){
                                    $Patient_Name=$patient_rows['Patient_Name'];
                                    $Registration_ID=$patient_rows['Registration_ID'];
                                    $Hospital_Ward_Name=$patient_rows['Hospital_Ward_Name'];
                                    $Admission_Date_Time=$patient_rows['Admission_Date_Time'];
                                    $Bed_Name=$patient_rows['Bed_Name'];
                                    $Employee_Name=$patient_rows['Employee_Name'];
                                    $Bed_Name=$patient_rows['Bed_Name'];
                                    $Corpse_Brought_By=$patient_rows['Corpse_Brought_By'];
                                    echo "<tr>
                                            <td><a href='add_mortuary_services.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$count</a></td>
                                            <td><a href='add_mortuary_services.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$Patient_Name</a></td>
                                            <td><a href='add_mortuary_services.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$Registration_ID</a></td>
                                            <td><a href='add_mortuary_services.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$Hospital_Ward_Name</a></td>
                                            <td><a href='add_mortuary_services.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$Bed_Name</a></td>
                                            <td><a href='add_mortuary_services.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$Admission_Date_Time</a></td>
                                            <td><a href='add_mortuary_services.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$Employee_Name</a></td>
                                            <td><a href='add_mortuary_services.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$Corpse_Brought_By</a></td>
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
     function filter_patient(){
        var patient_name=$("#patient_name").val();
        var patient_number=$("#patient_number").val();
        var Date_From=$("#Date_From").val();
        var Date_To=$("#Date_To").val();
        
         $("#ajax_loder").show();
         $.ajax({
             type:'GET',
             url:'mortuary_admitted_list_service.php',
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