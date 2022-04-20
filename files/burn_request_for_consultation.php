<?php
// include("./includes/header.php");
// include("./includes/connection.php");
// include './includes/cleaninput.php';
// require_once './includes/ehms.function.inc.php';

// if (!isset($_SESSION['userinfo'])) {
//     @session_destroy();
//     header("Location: ../index.php?InvalidPrivilege=yes");
// }
// if (isset($_SESSION['userinfo'])) {
//     if (isset($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'])) {
//         if ($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes') {
//             header("Location: ./index.php?InvalidPrivilege=yes");
//         } 
//     } else {
//         header("Location: ./index.php?InvalidPrivilege=yes");
//     }
// } else {
//     @session_destroy();
//     header("Location: ../index.php?InvalidPrivilege=yes");
// }
// if (isset($_GET['consultation_ID'])) {
//     $consultation_ID = $_GET['consultation_ID'];
// } else {
//     header("Location: ./index.php?InvalidPrivilege=yes");
// }
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

// if(isset($_GET['Registration_ID'])){
// $Registration_ID = $_GET['Registration_ID'];

// }else{
// $Registration_ID = $_GET['Registration_ID'];

// }
// $consultation_ID = $_GET['consultation_ID'];
// $Admision_ID = $_GET['Admision_ID'];
// ?>
<!-- // <a hrdef='Previous_consultation_request.php?Registration_ID=<?=$Registration_ID?>' name="pre_request" class='art-button-green' onclick="previous_consultation_request(<?php echo $Registration_ID;?>)">PREVIOUS REQUEST</a>
// <a href='doctorspageinpatientwork.php?Registration_ID=<?=$Registration_ID?>&consultation_ID=<?=$consultation_ID?>' class='art-button-green'>BACK</a>
// <br> -->
<?php
    include("./includes/connection.php");

 if(isset($_POST['consultation_form'])){
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];

    $Registration_ID = $_POST['Registration_ID'];
// $select_patien_details = mysqli_query($conn,"
// SELECT pr.Sponsor_ID,Member_Number,Patient_Name, pr.Ward, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
//     FROM
//         tbl_patient_registration pr,
//         tbl_sponsor sp
//     WHERE
//         pr.Registration_ID = '" . $Registration_ID . "' AND
//         sp.Sponsor_ID = pr.Sponsor_ID
//         ") or die(mysqli_error($conn));
// $no = mysqli_num_rows($select_patien_details);
// if ($no > 0) {
// while ($row = mysqli_fetch_array($select_patien_details)) {
//     $Member_Number = $row['Member_Number'];
//     $Patient_Name = $row['Patient_Name'];
//     $Registration_ID = $row['Registration_ID'];
//     $Gender = $row['Gender'];
//     $Guarantor_Name  = $row['Guarantor_Name'];
//     $Sponsor_ID = $row['Sponsor_ID'];
//     $DOB = $row['Date_Of_Birth'];
// }
// } else {
// $Guarantor_Name  = '';
// $Member_Number = '';
// $Patient_Name = '';
// $Gender = '';
// $Registration_ID = 0;
// }

// $age = date_diff(date_create($DOB), date_create('today'))->y;

// $select_bed_result = mysqli_query($conn, "SELECT Bed_Name, ad.ward_room_id, ad.Hospital_Ward_ID, hw.Hospital_Ward_Name, wr.room_name,Admission_Date_Time,  ad.Registration_ID FROM tbl_admission ad ,tbl_patient_registration pr, tbl_hospital_ward hw, tbl_ward_rooms wr WHERE Admision_ID='$Admision_ID' AND ad.Registration_ID=$Registration_ID AND ad.ward_room_id = wr.ward_room_id AND ad.Hospital_Ward_ID =hw.Hospital_Ward_ID") or die(mysqli_error($conn));
//      while($bed_row = mysqli_fetch_assoc($select_bed_result)){
//          $ward = $bed_row['Hospital_Ward_Name'];
//          $bed = $bed_row['Bed_Name'];
//          $Admission_Date_Time = $bed_row['Admission_Date_Time'];
//          $room = $bed_row['room_name'];
//      }

?>
<!-- <fieldset> -->
    <!-- <legend align="center" style='padding:10px; color:white; background-color:#2D8AAF; text-align:center'><b>
            <b>RECEIVING NOTES</b><br/>
            <span style='color:yellow;'><?php // echo "" . $Patient_Name . "  | " . $Gender . " | " . $age . " years | " . $Guarantor_Name  . ""; ?></span><br>
            <span><?php //echo "Hospital Ward: <u>".$ward."</u>" ?></span>
        </b>
    </legend> -->

<div class="col-md-12">
    <div class="box box-primary" >
        <div class="box-body">
            <div class="row">
                    <div class="col-md-6">
                        <label for="" >Request To:</label>
                        <div >
                        <select name="" id="Request_to" class="form-control" >
                            <option value="">~~~select Doctor~~~</option>
                            <?php
                            $sql_select_employee_result=mysqli_query($conn,"SELECT Employee_Name,Employee_ID FROM tbl_employee WHERE Account_Status='active' AND Employee_type='Doctor'") or die(mysqli_error($conn));
                            if((mysqli_num_rows($sql_select_employee_result))>0){
                                
                                while($employee_rows=mysqli_fetch_assoc($sql_select_employee_result)){
                                   $Employee_Name=$employee_rows['Employee_Name'];
                                   $Employee_ID=$employee_rows['Employee_ID'];
                                   echo "<option value='$Employee_ID'>$Employee_Name opfoiopf</option>
                                        ";
                                   
                                }
                            }else{
                                echo "No result found";
                            }
                            
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="" >Type of Request</label>
                    <div >
                        Emergency
                        <input type="checkbox" id="Emergency" name="Request_type" >
                        Urgent
                        <input type="checkbox" id="Urgent" >
                        Routine
                        <input type="checkbox" id="Routine" >
                    </div>
                </div>
                
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <label for="" >Brief Case Summary:</label>
                    <div >
                        <textarea type="text" id="Brief_case_summary" rows="2" class="form-control"></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="" >Diagnosis</label>
                    <div >
                        <textarea name="" id="Diagnosis"  rows="2" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                
                <div class="col-md-12">
                    <label for="" >Question:</label>
                    <div >
                        <textarea type="text" id="Question" rows="2" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-offset-10 col-md-2">
                    <button class="art-button-green btn-xs" name="request_btn" type="button" onclick="save_consultation_request(<?php echo $Registration_ID;?>)">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <div class="col-md-1" id="request_dialog"></div> -->

<!-- </fieldset> -->
<?php
}
// include("./includes/footer.php");
?>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script> 
 
<script>
    $(document).ready(function(){
        $('select').select2();
    })
    // function save_consultation_request(Registration_ID){
    //     var Request_type = "";
    //     if($("#Emergency").is(":checked")){
    //         Request_type +="Emergency"+','
    //        }
    //     if($("#Urgent").is(":checked")){
    //         Request_type +="Urgent"+','
    //        }
    //     if($("#Routine").is(":checked")){
    //         Request_type +="Routine"+','
    //        }
           
    //     var Brief_case_summary =$("#Brief_case_summary").val();//alert(Brief_case_summary)
    //     var Question = $("#Question").val();
    //     var Request_from = $("#Request_from").val();
    //     var Request_to = $("#Request_to").val();
    //     var Diagnosis = $("#Diagnosis").val(); 
    //     if(Brief_case_summary==""){
    //         $("#Brief_case_summary").css("border","1px solid red");
    //     }else if(Diagnosis==""){
    //         $("#Diagnosis").css("border","1px solid red");
    //     }else if(Request_to==""){
    //         $("#Request_to").css("border","1px solid red");
    //     }else{
    //         $("#Diagnosis").css("border","");
    //         $("#Request_to").css("border","");
    //         $("#Brief_case_summary").css("border","");
    //     $.ajax({
    //         type:'POST',
    //         url:'Ajax_save_burn_unit.php',
    //         data:{Registration_ID:Registration_ID,Request_type:Request_type,Request_from:Request_from,Request_to:Request_to, Diagnosis:Diagnosis,Brief_case_summary:Brief_case_summary,Question:Question,request_btn:''},
    //         success:function(responce){
    //             alert("Saved successful");
    //         }
    //     });
    //     }
    // }
    function previous_consultation_request(Registration_ID){         
        $.ajax({
            type:'POST',
            url:'add_type_burn.php',
            data:{Registration_ID:Registration_ID,pre_request:''},
            success:function(responce){
                $("#request_dialog").dialog({
                    title: 'PREVIOUS CONSULTATION REQUEST FOR THIS PATIENT',
                    width: '70%',
                    height: 400,
                    modal: true,
                });
                $("#request_dialog").html(responce)
            }
        });
    }
</script>