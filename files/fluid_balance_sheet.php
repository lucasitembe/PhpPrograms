<style>
    .rows_list{
    color: #328CAF!important;
        cursor: pointer;
    }
    .rows_list:active{
        color: #328CAF!important;
        font-weight:normal!important;
    }
    .rows_list:hover{
        color:#00416a;
        background: #dedede;
        font-weight:bold;
    }
</style>
<?php
    $indexPage = false;
    include("./includes/connection.php");
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
//    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
//	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
//	    header("Location: ./index.php?InvalidPrivilege=yes");
//	}
//    }else{
//	@session_destroy();
//	header("Location: ../index.php?InvalidPrivilege=yes");
//    }
    
    $can_broadcast=$_SESSION['userinfo']['can_broadcast'];
    $consultation_ID = $_GET['consultation_ID'];
    $Admision_ID = $_GET['Admision_ID'];
    $patient_id = $_GET['Registration_ID'];
    $employee_ID = $_SESSION['userinfo']['Employee_ID'];
?>
    <a href='./nursecommunicationpage.php?Registration_ID=<?php echo $patient_id ?>&consultation_ID=<?php echo $consultation_ID ?>&Admision_ID=<?php echo $Admision_ID ?>&consultation_ID=<?php echo $consultation_ID ?>' class='art-button-green'>
        BACK
    </a>
    <input class="art-button-green" onclick="open_balance_sheet(0,<?php echo $patient_id ?>)" type="button" value="Regular Fluid Balance Sheet">
<fieldset>
<legend align="center" ><b> FLUID BALANCE SHEET</b></legend> 
    <center>
        <table class="tabel table-bordered" style="background-color:#fff">
        <?php 
            $select_patient = mysqli_query($conn,"SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID = '$patient_id'") or die(mysqli_error($conn));
            while($patient_row = mysqli_fetch_assoc($select_patient)){
                $patient_name = $patient_row['Patient_Name'];
            }

            //die("SELECT Hospital_Ward_Name FROM tbl_admission as ad, tbl_hospital_ward as ward WHERE ad.Hospital_Ward_ID = ward.Hospital_Ward_ID AND Registration_ID = '$patient_id'");

            $select_patient_ward = mysqli_query($conn,"SELECT Hospital_Ward_Name FROM tbl_admission as ad, tbl_hospital_ward as ward WHERE ad.Hospital_Ward_ID = ward.Hospital_Ward_ID AND Registration_ID = '$patient_id'") or die(mysqli_error($conn));
            while($patient_ward_row = mysqli_fetch_assoc($select_patient_ward)){
                $Hospital_Ward_Name = $patient_ward_row['Hospital_Ward_Name'];
            }
        ?>
            <tr>
                <th>Hosp. Reg Number:</th>
                <td><?php echo $patient_id?></td>
                <th>Name:</th>
                <td><?php echo $patient_name?></td>
                <th>Ward</th>
                <td><?php echo $Hospital_Ward_Name?></td>
                <th>Date</th>
                <td><?php echo date("Y-M-d"); ?></td>
            </tr>
            <input type="text" id="carry_name" value="<?php echo $patient_name ?>" style="display:none">
        </table>
        <br>
        <?php

            $select_instrunction = mysqli_query($conn,"SELECT * FROM tbl_fluid_instruction WHERE patient_id = '$patient_id' ORDER BY instruction_id DESC") or die(mysqli_error($conn));
            //die("SELECT instrunction FROM tbl_fluid_balance WHERE patient_id = '$patient_id'");
            if((mysqli_num_rows($select_instrunction))>0){
                $count = 1;
                echo '
                <table class="table table-bordered" style="background-color:#fff; width:60%;">
                <tr><th>SN</th><th>Issued At</th><th>Issued By</th><th>Instrunctions</th></tr>
                ';
                while($instruction_row = mysqli_fetch_assoc($select_instrunction)){
                $instrunction = $instruction_row['instrunction'];
                $issued_at = $instruction_row['issued_at'];
                $doctor_id = $instruction_row['doctor_id'];
                $instruction_id = $instruction_row['instruction_id'];
                $doctor_name = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = $doctor_id") or die(mysqli_error($conn));
                $doctor_name_row = mysqli_fetch_assoc($doctor_name);
                $name = $doctor_name_row['Employee_Name'];
                if( $instrunction != ""){
                echo '
                    <tr onclick="open_balance_sheet('.$instruction_id.','.$patient_id.')" class="rows_list"><th>'.$count.'</th><td>'.$issued_at.'</td><td>'.$name.'</td><td>'.$instrunction.'</td></tr>
                ';
                }else{
                }
                $count++;
                }
                echo '</table>';
            }
            else{
                echo "No any instrunction";
            }        ?>
        <br>
        <div id="fluid_form" style="display:none">
        </div>
        <br>
</fieldset>
<?php
    include("./includes/footer.php");
?>
<script>
    function open_balance_sheet(instrunction_id,patient_id){
        var patient_name = $('#carry_name').val();
        $.ajax({
            url: 'open_balance_sheet.php',
            type: 'POST',
            data: {instrunction_id:instrunction_id,patient_id:patient_id,patient_name:patient_name},
            success:function(data){
                $("#fluid_form").dialog({
                        title: 'FLUID BALANCE SHEAT',
                        width: '90%',
                        height: 600,
                        modal: true,
                    });
                    $("#fluid_form").html(data);
            }
        });
    }
    function save_fluid_balance_info(emp_id,patient_id){
        var prescription_fluid = $('#prescription_fluid').val();
        var oral_fluid = $('#oral_fluid').val();
        var oral_fluid_amount = $('#oral_fluid_amount').val();
        var other_fluid = $('#other_fluid').val();
        var other_amount = $('#other_amount').val();
        var time = $('#time').val();
        var prescription_intervenous_fluid = $('#prescription_intervenous_fluid').val();
        var prescription_intervenous_fluid_amount = $('#prescription_intervenous_fluid_amount').val();
        var intake_intervenous_fluid = $('#intake_intervenous_fluid').val();
        var intake_intervenous_fluid_amount = $('#intake_intervenous_fluid_amount').val();
        var intake_oral_fluid = $('#intake_oral_fluid').val();
        var intake_oral_fluid_amount = $('#intake_oral_fluid_amount').val();
        var intake_other_amount = $('#intake_other_amount').val();
        var urine_amount = $('#urine_amount').val();
        var gastr_amount = $('#gastr_amount').val();
        var faeces = $('#faeces').val();
        var other = $('#other').val();
        var patient_name = $('#carry_name').val();
        var instruction_id = $('#instrunction_id').val();
        if(time == ""){
            $("#time").css({ "background-color": "#ffe", "border": "1px solid red" });
            exit();
        }
        $.ajax({
            url: 'save_fluid_balance_sheet.php',
            type: 'POST',
            data: {
                prescription_fluid:prescription_fluid,
                oral_fluid:oral_fluid,
                oral_fluid_amount:oral_fluid_amount,
                other_fluid:other_fluid,
                other_amount:other_amount,
                time:time,
                prescription_intervenous_fluid:prescription_intervenous_fluid,
                prescription_intervenous_fluid_amount:prescription_intervenous_fluid_amount,
                intake_intervenous_fluid:intake_intervenous_fluid,
                intake_intervenous_fluid_amount:intake_intervenous_fluid_amount,
                intake_oral_fluid:intake_oral_fluid,
                intake_oral_fluid_amount:intake_oral_fluid_amount,
                intake_other_amount:intake_other_amount,
                urine_amount:urine_amount,
                gastr_amount:gastr_amount,
                faeces:faeces,
                other:other,
                instruction_id:instruction_id,
                patient_id:patient_id,
                patient_name:patient_name,
                emp_id:emp_id,
            },
            success:function(data){
                $("#formId")[0].reset()
                $('#fluid_balance_sheet').empty();
                $('#fluid_balance_sheet').html(data);
                alert("successful saved");
            }
        });
    }

</script>