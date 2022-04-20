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
$consultation_ID = $_GET['consultation_ID']; 
$Admision_ID = $_GET['Admision_ID'];
// echo $Admision_ID;
?>
<!-- Registration_ID=<?=$Registration_ID?>&consultation_ID=<?=$consultation_ID?> -->
<a href='inpatientclinicalnotes.php?Registration_ID=<?=$Registration_ID?>&consultation_ID=<?=$consultation_ID?>' class='art-button-green'>BACK</a>
<br>
<?php
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
<style media="screen">
    #table {
        border:none !important;
    }

    .input{
        width:30% !important;
    }

    .label-input{
        width: 0% !important ;
        text-align: right !important;

    }
</style>
<fieldset>
    <legend align="center" style='padding:10px; color:white; background-color:#2D8AAF; text-align:center'><b>
            <b>RECEIVING NOTES</b><br/>
            <span style='color:yellow;'><?php echo "" . $Patient_Name . "  | " . $Gender . " | " . $age . " years | " . $Guarantor_Name  . ""; ?></span></b>
    </legend>
<table class="table">    
    
    <tr>
        <th>ADMISSION DATE</th>
        <td><?php echo $Admission_Date_Time; ?></td>
        <th>ADMITTED FROM</th>
        <td><?php ?></td>
        <th>TRANSFER IN DATE#</th>
        <td><?php ?></td>
        <th>TRANSFER FROM</th>
        <td><?php ?></td>
    </tr>
    <?php
     
        echo' <tr>
            <th>Ward:</th>
            <td>'.$ward.'</td>
            <th>ROOM:</th>
            <td>'.$room.'</td>
            <th >Bed No:</th>
            <td>'.$bed.'</td>
        </tr> 
    </table><br>
     ';
 ?>
</table>
</fieldset>
<fieldset>
    <center>
        <div class="col-md-12">
            <div class=" box box-primary">
                <div class="box-body" id="display_notes">
                   
                </div>
            </div>
        </div> 
    </center>
    <div id="responce_load"></div>
</fieldset>
<input type="text" value="<?php echo $Registration_ID; ?>" style="display: none" id="Registration_ID">
<input type="text" value="<?php echo $Admision_ID; ?>" style="display: none" id="Admision_ID">

<?php
        include("./includes/footer.php");
?>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $(document).ready(function(){
        display_receiving_notes();
        $('.date').datetimepicker({value: '', step: 2});
    });
    function save_receiving_note(Registration_ID,Admision_ID){
        var Burn_ID = $("#Burn_ID").val();
        var date_of_burn = $("#date_of_burn").val();
        // var burn_classfication = [];
        //   $(".Classfication_ID:checked").each(function() {
        //       burn_classfication.push($(this).val());
        //     });
            var Classfication_of_burn = "";
        if($(".Classfication_ID").is(":checked")){
            Classfication_of_burn += $(".Classfication_ID")+','
           }

        // while($(".Classfication_ID:checked").each(function() {
        //       burn_classfication.push($(this).val());
        //     })){

        //     }

       // alert(Admision_ID)
        var Condition_of_patient = $("#Condition_of_patient").val(); 
        var FBP ="";
        if($("#fbp_done ").is(":checked")){
            FBP = "Done";
        }
        if($("#fbp_not_done ").is(":checked")){
            FBP = "Not Done";
        }
        var electrolyte ="";
        if($("#electrolyte_done ").is(":checked")){
            electrolyte = "Done";
        }
        if($("#electrolyte_not_done ").is(":checked")){
            electrolyte = "Not Done";
        }
        var blood_grouping_x_matching ="";
        if($("#blood_grouping_x_matching_done ").is(":checked")){
            blood_grouping_x_matching = "Done";
        }
        if($("#blood_grouping_x_matching_not_done ").is(":checked")){
            blood_grouping_x_matching = "Not Done";
        }
        var other_investigation_done = $("#other_investigation_done").val();
        var management_given = $("#management_given").val();
        var tbsa = $("#tbsa").val();
        $.ajax({
            type:'POST',
            url:'Ajax_save_burn_unit.php',
            data:{Registration_ID:Registration_ID,Burn_ID:Burn_ID,date_of_burn:date_of_burn,Classfication_of_burn:Classfication_of_burn,Condition_of_patient:Condition_of_patient,FBP:FBP,electrolyte:electrolyte,Admision_ID:Admision_ID,blood_grouping_x_matching:blood_grouping_x_matching,other_investigation_done:other_investigation_done,management_given:management_given, tbsa:tbsa, receiving_notes:''},
            success:function(responce){
                // window.location.reload();
                display_receiving_notes()
            }
        })

    }

    function display_receiving_notes(){
           var Registration_ID = $("#Registration_ID").val(); 
           var Admision_ID = $("#Admision_ID").val();
        $.ajax({
            type:'POST',
            url:'Ajax_display_burn.php',
            data:{Admision_ID:Admision_ID,Registration_ID:Registration_ID, responce_load:''},
            success:function(responce){
                $("#display_notes").html(responce);
            }
        });
    }



    function update_receiving_note(Receiving_note_ID){
        var Burn_ID = $("#Burn_ID").val();
        var date_of_burn = $("#date_of_burn").val();
        
        var Classfication_of_burn = "";
        if($(".Classfication_ID").is(":checked")){
            Classfication_of_burn += $(".Classfication_ID")+','
           }

        
        var Condition_of_patient = $("#Condition_of_patient").val(); 
        var FBP ="";
        if($("#fbp_done ").is(":checked")){
            FBP = "Done";
        }
        if($("#fbp_not_done ").is(":checked")){
            FBP = "Not Done";
        }
        var electrolyte ="";
        if($("#electrolyte_done ").is(":checked")){
            electrolyte = "Done";
        }
        if($("#electrolyte_not_done ").is(":checked")){
            electrolyte = "Not Done";
        }
        var blood_grouping_x_matching ="";
        if($("#blood_grouping_x_matching_done ").is(":checked")){
            blood_grouping_x_matching = "Done";
        }
        if($("#blood_grouping_x_matching_not_done ").is(":checked")){
            blood_grouping_x_matching = "Not Done";
        }
        var other_investigation_done = $("#other_investigation_done").val();
        var management_given = $("#management_given").val();
        var tbsa = $("#tbsa").val();
        $.ajax({
            type:'POST',
            url:'Ajax_save_burn_unit.php',
            data:{Receiving_note_ID:Receiving_note_ID,Burn_ID:Burn_ID,date_of_burn:date_of_burn,Classfication_of_burn:Classfication_of_burn,Condition_of_patient:Condition_of_patient,FBP:FBP,electrolyte:electrolyte,blood_grouping_x_matching:blood_grouping_x_matching,other_investigation_done:other_investigation_done,management_given:management_given, tbsa:tbsa, receiving_notes_update:''},
            success:function(responce){
                // window.location.reload();
                display_receiving_notes()
            }
        })

    }
</script>