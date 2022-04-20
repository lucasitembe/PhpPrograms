<?php
include("./includes/header.php");
include("./includes/connection.php");
include './includes/cleaninput.php';
require_once './includes/ehms.function.inc.php';

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } 
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
// if (isset($_GET['consultation_ID'])) {
//     $consultation_ID = $_GET['consultation_ID'];
// } else {
//     header("Location: ./index.php?InvalidPrivilege=yes");
// }
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

if(isset($_GET['Registration_ID'])){
$Registration_ID = $_GET['Registration_ID'];

}else{
$Registration_ID = $_GET['Registration_ID'];

}
$Request_Consultation_ID = $_GET['Request_Consultation_ID'];
//$Admision_ID = $_GET['Admision_ID'];

$select_patien_details = mysqli_query($conn,"
SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
    FROM
        tbl_patient_registration pr,
        tbl_sponsor sp
    WHERE
        pr.Registration_ID = '" . $Registration_ID . "' AND
        sp.Sponsor_ID = pr.Sponsor_ID
        ") or die(mysqli_error($conn));
$no = mysqli_num_rows($select_patien_details);
if ($no > 0) {
while ($row = mysqli_fetch_array($select_patien_details)) {
    $Member_Number = $row['Member_Number'];
    $Patient_Name = $row['Patient_Name'];
    $Registration_ID = $row['Registration_ID'];
    $Gender = $row['Gender'];
    $Guarantor_Name  = $row['Guarantor_Name'];
    $Sponsor_ID = $row['Sponsor_ID'];
    $DOB = $row['Date_Of_Birth'];
}
} else {
$Guarantor_Name  = '';
$Member_Number = '';
$Patient_Name = '';
$Gender = '';
$Registration_ID = 0;
}

$age = date_diff(date_create($DOB), date_create('today'))->y;

$select_bed_result = mysqli_query($conn, "SELECT Bed_Name, ad.ward_room_id, ad.Hospital_Ward_ID, hw.Hospital_Ward_Name, wr.room_name,Admission_Date_Time,  ad.Registration_ID FROM tbl_admission ad ,tbl_patient_registration pr, tbl_hospital_ward hw, tbl_ward_rooms wr WHERE Admision_ID='$Admision_ID' AND ad.Registration_ID=$Registration_ID AND ad.ward_room_id = wr.ward_room_id AND ad.Hospital_Ward_ID =hw.Hospital_Ward_ID") or die(mysqli_error($conn));
     while($bed_row = mysqli_fetch_assoc($select_bed_result)){
         $ward = $bed_row['Hospital_Ward_Name'];
         $bed = $bed_row['Bed_Name'];
         $Admission_Date_Time = $bed_row['Admission_Date_Time'];
         $room = $bed_row['room_name'];
     }
?>
<a href="admittedpatientlist.php" class="art-button-green">
    BACK
</a>
<fieldset >
    <legend align="center" style='padding:10px; color:white; background-color:#2D8AAF; text-align:center'><b>
            <b>REQUEST FOR CONSULTATION</b><br/>
            <span style='color:yellow;'><?php echo "" . $Patient_Name . "  | " . $Gender . " | " . $age . " years | " . $Guarantor_Name  . ""; ?></span></b>
    </legend>
    
<div class="col-md-1"></div>
<div class="col-md-10">
    <div class="box box-primary"  style="height: 400px;overflow: auto">
        <div class="box-body">
        <?php 
        //if(isset($_POST['edit_form'||'Preview'])){
        $name =$_SESSION['userinfo']['Employee_Name'];
        $num=0;
       
        $select_requests = mysqli_query($conn, "SELECT Request_type,Diagnosis,Brief_case_summary,Question, Request_to,Request_from,Date_of_request, Request_Consultation_ID,Employee_Name FROM tbl_request_for_consultation rc, tbl_employee e WHERE e.Employee_ID=Request_to AND Registration_ID='$Registration_ID' AND Request_Consultation_ID='$Request_Consultation_ID'") or die(mysqli_error($conn));
        
        $Urgent="";
        $Routine ="";
        $Emergency ="";
        if(mysqli_num_rows($select_requests)>0){
            while($rq_rw = mysqli_fetch_assoc($select_requests)){
                $Request_Consultation_ID = $rq_rw['Request_Consultation_ID'];
                $Request_to = $rq_rw['Request_to'];
                //$Request_type = $rq_rw['Request_type'];
                $Request_from = $rq_rw['Request_from'];
                $Date_of_request = $rq_rw['Date_of_request'];
                $Employee_Name =$rq_rw['Employee_Name'];
                $Diagnosis = $rq_rw['Diagnosis'];
                $Question = $rq_rw['Question'];
                $Brief_case_summary = $rq_rw['Brief_case_summary'];
                $num++;
                $Request_type = explode(',', $rq_rw['Request_type']);
                foreach($Request_type as $type){
                    if($type == "Emergency"){
                        $Emergency ="checked='checked'";
                    }
                    if($type == "Urgent"){
                        $Urgent ="checked='checked'";
                    }
                     if($type == "Routine"){
                        $Routine ="checked='checked'";
                    }
                }
                ?>
                <div class="row">
                <div class="col-md-6">
                    <label for="" class="col-md-4">Request From</label>
                    <div class="col-md-8">
                        <input type="text" id="Request_from" value="<?php echo $Request_from;?>" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="" class="col-md-4">Request To:</label>
                    <div class="col-md-8">
                        <?php 
                        if($name==$Request_from){
                            ?>
                             <select name="" id="Request_to" class="select2-default" >
                            <option value="">~~~select Doctor~~~</option>
                            <?php
                            $sql_select_employee_result=mysqli_query($conn,"SELECT Employee_Name,Employee_ID FROM tbl_employee WHERE Account_Status='active' AND Employee_type='Doctor'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_employee_result)>0){
                                
                                while($employee_rows=mysqli_fetch_assoc($sql_select_employee_result)){
                                   $Employee_Name=$employee_rows['Employee_Name'];
                                   $Employee_ID=$employee_rows['Employee_ID'];
                                   echo "<option value='$Employee_ID'>$Employee_Name</option>
                                        ";
                                   
                                }
                            }else{
                                echo "No result found ====";
                            }
                            ?>
                        </select>
                      <?php  }else{
                          echo "<input value='$Employee_Name' type='text' disabled class='form-control' >";
                      }
                        ?>
                       
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <label for="" class="col-md-4">Type of Request</label>
                    <div class="col-md-8">
                        Emergency
                        <input type="checkbox" id="Emergency" name="Request_type" <?php echo $Emergency;?>>
                        Urgent
                        <input type="checkbox" id="Urgent" <?php echo $Urgent;?>>
                        Routine
                        <input type="checkbox" id="Routine" <?php echo $Routine; ?>>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="" class="col-md-4">Diagnosis</label>
                    <div class="col-md-8">
                        <textarea name="" id="Diagnosis"  rows="2" class="form-control"><?php echo $Diagnosis;?></textarea>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <label for="" class="col-md-4">Brief Case Summary:</label>
                    <div class="col-md-8">
                        <textarea type="text" id="Brief_case_summary" rows="2" class="form-control"><?php echo $Brief_case_summary;?></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="" class="col-md-4">Question:</label>
                    <div class="col-md-8">
                        <textarea type="text" id="Question" rows="2" class="form-control"><?php echo $Question;?></textarea>
                    </div>
                </div>
            </div>
            <br>
            <?php
             if($name==$Request_from){
                ?>
                <div class="row">
                <div class="col-md-offset-10 col-md-2">
                    <button class="art-button-green btn-xs" name="request_btn_update" type="button" onclick="update_consultation_request(<?php echo $Request_Consultation_ID; ?>)">Update</button>
                </div>
            </div>
            <h5 align="center">Replyies</h5>
            
                <?php
            } 
            echo "<table class='table' style='border:1px solid white important;'>
            <thead>
                <tr>
                    <th width='25%' class='text-center'>Date</th>
                    <th width='75%' class='text-center'>Reply</th>
                </tr>
            </thead>
            <tbody>";
            $select_replay = mysqli_query($conn, "SELECT * FROM tbl_consultation_request_replay WHERE Registration_ID='$Registration_ID' AND Request_Consultation_ID='$Request_Consultation_ID'") or die(mysqli_error($conn));
            $num = 0;
            if(mysqli_num_rows($select_replay)>0 ){
                while($replay_rw = mysqli_fetch_assoc($select_replay)){

                    $consultation_request_replay = $replay_rw['consultation_request_replay'];
                    $date_of_replay = $replay_rw['date_of_replay'];
                    $num++;
                    echo "<tr>
                            <td>$date_of_replay</td>
                            <td>$consultation_request_replay</td>
                            </tr>
                            ";
                }
            }else{
                echo "<tr><td colspan='2'class='text-center text-danger' >No any replay for this request</td></tr>";
            }
            echo "</tbody>
            </table>";
            if($Employee_ID == $Request_to){
            ?><br>
            <h5 align='center' ><b>Reply:</b></h5>
            <br>
            
            <div class="row">
                <div class="col-md-12">                    
                        <textarea type="text" id="consultation_request_replay" rows="3" class="form-control"></textarea>                   
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-center">
                    <button class="btn btn-primary btn-sm" style='width:10em;' name="replay_btn" type="button" onclick="Replay_consultation_request(<?php echo $Registration_ID;?>,<?php echo $Request_Consultation_ID; ?>)">REPLY</button>
                </div>
            </div>
<?php      }
            ?>
            
        <?php }
        }else{
            echo "No result found 99999";
        }
    //}
    ?>
        </div>
    </div>
</div>
<div class="col-md-1" id="request_dialog"></div>
</fieldset>
<?php
include("./includes/footer.php");
?>
<script>
    function Replay_consultation_request(Registration_ID,Request_Consultation_ID){
        var consultation_request_replay = $("#consultation_request_replay").val();
        $.ajax({
            type:'POST',
            url:'Ajax_save_burn_unit.php',
            data:{consultation_request_replay:consultation_request_replay,Registration_ID:Registration_ID,Request_Consultation_ID:Request_Consultation_ID,replay_btn:'' },
            success:function(success){
               
               $("#reply_body").html(responce);
            }
        })
    }
    function display_replay(){
        $.ajax({
            type:'POST',
            url:'Ajax_save_burn_unit.php',
            data:{},
            success:function(success){
                display_replay()
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
        $.ajax({
            type:'POST',
            url:'Ajax_save_burn_unit.php',
            data:{Request_type:Request_type,Request_Consultation_ID:Request_Consultation_ID, Request_to:Request_to, Diagnosis:Diagnosis,Brief_case_summary:Brief_case_summary,Question:Question,request_btn_update:''},
            success:function(responce){
                alert("Updated successful");
            }
        });
    }
    }
</script>