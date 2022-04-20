<?php
include("./includes/connection.php");
include("./includes/header.php");
$controlforminput = '';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
    if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes') {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<a href="#" class="art-button-green" style="font-family: Arial, Helvetica, sans-serif;" id="edit_rooms">EDIT ROOMS</a>
<a href='admissionconfiguration.php?AdmisionWorks=AdmisionWorksThisPage' class='art-button-green'>Back</a>

<br/>
<br/>
<br/>
<br/>

<?php
//last modified on 13/02/2018 @Mfoy d_n 
if(isset($_POST['save_ward_btn'])){
    $Hospital_Ward_Name = mysqli_real_escape_string($conn,$_POST['ward_name']);
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    $ward_nature=$_POST['ward_nature'];
    $ward_type=$_POST['ward_type'];
    $sub_department = $_POST['sub_department'];
    
    $check_department = mysqli_query($conn,"SELECT tbl_sub_department_ward.sub_department_ward_id,Sub_Department_Name FROM tbl_sub_department_ward,tbl_sub_department WHERE tbl_sub_department_ward.Sub_Department_ID = tbl_sub_department.sub_department_id AND tbl_sub_department_ward.sub_department_id='$sub_department'");
    if (mysqli_num_rows($check_department)>0) {
        $department_data = mysqli_fetch_assoc($check_department);
        $department_name = $department_data['Sub_Department_Name'];
        $result = strtoupper($department_name);?>
        <script>
                var result = '<?php echo $result; ?>';
                alert("Sub-department "+ result +" already exist. One sub-department cannot have more than one ward.");
        </script>
    <?php }
    else{
    $insert_ward = "INSERT INTO tbl_hospital_ward(Hospital_Ward_Name, Branch_ID,ward_nature,ward_type)
                    VALUES('$Hospital_Ward_Name', '$Branch_ID','$ward_nature','$ward_type')";
    $insert_ward_result=mysqli_query($conn,$insert_ward) or die(mysqli_error($conn));
    if($insert_ward_result){
        $sub_department_id = $sub_department; //sub department id
        $ward_id = mysqli_insert_id($insert_ward_result);//last inserted ward id

        $merge_subdepartment_ward=mysqli_query($conn,"INSERT INTO `tbl_sub_department_ward`(`sub_department_id`, `ward_id`) VALUES ('$sub_department_id','$ward_id')");

        echo '<script>
	  alert("Ward Added Successfully");
	</script>';
    }else{
        echo '<script>
	  alert("fail to add ward");
	</script>';
    }
    }
}
//end modifications 13/02/2018 @Mfoy d_n 

if(isset($_POST['save_room_btn'])){
   $room_name=mysqli_real_escape_string($conn,$_POST['room_name']);
   $ward_id2=$_POST['ward_id2'];
   
   $sql_insert_ward_room="INSERT INTO tbl_ward_rooms(room_name,	ward_id) VALUES('$room_name','$ward_id2')";
   $sql_insert_ward_room_result=mysqli_query($conn,$sql_insert_ward_room) or die(mysqli_error($conn));
   if($sql_insert_ward_room_result){
        echo '<script>alert("Room Added Successfully");</script>';
    }else{
        echo '<script>alert("fail to add room");</script>';
    }
}

if (isset($_POST['save_no_of_bed_btn'])) {
    	$ward_room_id = mysqli_real_escape_string($conn,$_POST['ward_room_id']);
    	$Ward_ID = mysqli_real_escape_string($conn,$_POST['ward_id3']);
        $Number_Of_Beds = mysqli_real_escape_string($conn,$_POST['Number_Of_Beds']);
                
        $sql_update_number_of_room="UPDATE tbl_ward_rooms SET no_of_beds='$Number_Of_Beds' WHERE ward_room_id='$ward_room_id'";
        $sql_update_number_of_room_result=mysqli_query($conn,$sql_update_number_of_room) or die(mysqli_error($conn));
        $Bed_Number=1;
        ///delete first if beds founds
        //check if bed never used
        
        if($sql_update_number_of_room_result){
          while ($Bed_Number <= $Number_Of_Beds) {
            $Bed_Name = 'Bed No. ' . $Bed_Number;
            $Status = 'available';
            
            //skip if the bed existed
            $sql_select_bed_info="SELECT Bed_Name FROM tbl_beds WHERE Bed_Name='$Bed_Name' AND Ward_ID='$Ward_ID' AND ward_room_id='$ward_room_id'";
            $sql_select_bed_info_result=mysqli_query($conn,$sql_select_bed_info) or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_bed_info_result)>0){
                $Bed_Number++;
                continue;
            }
            
            $insert_beds = "INSERT INTO tbl_beds(Bed_Name, Status, Ward_ID,ward_room_id)
                                VALUES('$Bed_Name', '$Status', '$Ward_ID','$ward_room_id')";

            mysqli_query($conn,$insert_beds) or die(mysqli_error($conn));
            $Bed_Number++;
        }  
        echo '<script>
	  alert("Saved Successfully");
	</script>';
    }   
}

?>
<center>
    <table width=60%><tr><td>
        <center><form action='#' method='post'>
                <fieldset>

                    <legend align="center" ><b>ADD NEW HOSPITAL WARD</b></legend>
                    <table width=100%>
                        <tr>
                            <td width=30%><b>Ward Name</b></td>
                            <td width=70%>
                               <!-- <input type='text' name='Hospital_Ward_Name' required='required' size='70' id='Hospital_Ward_Name' placeholder='Enter Ward Name'>-->
                                <select id="ward_id" name='ward_id3' style='width:100%' required="">
                                     <option value="" selected="selected"  disabled="disabled">--Select Ward --</option> 
                                    <?php
                                        $Query = mysqli_query($conn,"select * from tbl_hospital_ward") or die(mysqli_error($conn));
                                        while ($row = mysqli_fetch_assoc($Query)) {
                                            echo '<option value="' . $row['Hospital_Ward_ID'] . '">' . $row['Hospital_Ward_Name'].'~~>'.$row['ward_nature']. '</option>';
                                        }
                                        ?>
                                </select>
                               </td>
                               <td> <button type="button" class="art-button-green" onclick="add_ward()">ADD WARD</button>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Room</b></td>
                            <td>
                                <select id="ward_room"style='width:100%'name='ward_room_id' required="">
                                     <option value="" selected="selected"  disabled="disabled">--Select Room --</option> 
                                    <?php
                                        $Query = mysqli_query($conn,"select * from tbl_ward_rooms") or die(mysqli_error($conn));
                                        while ($row = mysqli_fetch_assoc($Query)) {
                                            echo '<option value="' . $row['ward_room_id'] . '">' . $row['room_name'] . '</option>';
                                        }
                                        ?>
                                </select>
                                </td>
                               <td> 
                                   <button type="button" class="art-button-green" onclick="add_room()">ADD ROOM</button>
                            </td>
                        </tr>
                        <tr>
                            <td width=30%><b>Number of Beds</b></td>
                            <td width=70% colspan="2">
                                <input type='text' name='Number_Of_Beds' class="numberOnly" required='required' size='50' id='Number_Of_Beds' placeholder='Enter Number of Beds'>
                            </td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align: right;'>
                                <input type='submit' name='save_no_of_bed_btn' id='submit' value='   SAVE /UPDATE  ' class='art-button-green'>
                                <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                <input type='hidden' name='submittedAddNewWardForm' value='true'/> 
                            </td>
                        </tr>
                    </table>
            </form>
            </fieldset>
        </center></td></tr></table>
</center>
<!---------------------------popup dialog for adds button------------------------------>
<div id="addward_dialog" style="display:none;">
    <style type="text/css">
                #addward_tbl{
                    width:100%;
                    border:none!important;
                }
                #addward_tbl tr, #addward_tbl tr td{
                    border:none!important;
                }
    </style>
    <form action='' method='POST'>
        <table  id="addward_tbl" cellpadding='5' class="table">
            <tr>
                <td><b>Ward Name</b></td>
                <td>
                    <input type="text" required="" name="ward_name" placeholder="Enter Ward Name"/>
                </td> 
            </tr>
            <tr>
                <td width=30%><b>Sub department</b></td>
                <td>
                    <select style="width:100%" name="sub_department" required="required" id="sub_department">
                        
                        <?php
                        $sql_result=mysqli_query($conn,"SELECT `Sub_Department_ID`, `Sub_Department_Name` FROM tbl_sub_department,tbl_department WHERE tbl_sub_department.Department_ID=tbl_department.Department_ID AND `Department_Location`='Admission' AND Sub_Department_Status='active'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_result)>0){?>
                                <option value="" disabled selected>~~~~~Select~~~~~</option>
                            <?php 
                            while($category_rows=mysqli_fetch_assoc($sql_result)){
                                    $Sub_Department_ID=$category_rows['Sub_Department_ID'];
                                        $Sub_Department_Name=$category_rows['Sub_Department_Name'];?>
                                        <option value="<?= $Sub_Department_ID ?>"><?= $Sub_Department_Name ?></option>
                        <?php }}else{?>
                            <option value="">No sub-department found!</option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width=30%><b>Ward Nature</b></td>
                    <td>
                        <select style="width:100%" name="ward_nature" id="ward_nature" required='required'>
                            <option value="" disabled selected>~~~~~Select~~~~~</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Both">Both</select>
                        </select>
                        <!--<input type='text' name='Number_Of_Beds' class="numberOnly" required='required' size='50' id='Number_Of_Beds'>-->
                    </td>
            </tr>
            <tr>
                <td width=30%><b>Ward Type</b></td>
                <td>
                    <select style="width:100%" name="ward_type" required="" id="ward_type">
                        <option value="" disabled selected>~~~~~Select~~~~~</option>
                        <option value="ordinary_ward">Ordinary Ward</option>
                        <option value="mortuary_ward">Mortuary Ward</option>
                    </select>
                </td>
            </tr>
            <tr>

                <td colspan="2" style="text-align:right">
                    <input type="submit" name='save_ward_btn' class="art-button-green" value="SAVE"/>
                </td>
            </tr> 
        </table>
    </form>
</div>
<div id="addroom_dialog" style="display:none;">
    <style type="text/css">
                #addroom_tbl{
                    width:100%;
                    border:none!important;
                }
                #addroom_tbl tr, #addroom_tbl tr td{
                    border:none!important;
                }
    </style>
    <form action='' method='POST'>
        <table  id="addroom_tbl" cellpadding='5' class="table">
            
            <tr>
                <td width=30%><b>Ward Name</b></td>
                <td width=70%>
                   <!-- <input type='text' name='Hospital_Ward_Name' required='required' size='70' id='Hospital_Ward_Name' placeholder='Enter Ward Name'>-->
                    <select id="ward_id2" style='width:100%'name='ward_id2' required="">
                         <option value="" selected="selected"  disabled="disabled">--Select Ward --</option> 
                        <?php
                            $Query = mysqli_query($conn,"select * from tbl_hospital_ward") or die(mysqli_error($conn));
                            while ($row = mysqli_fetch_assoc($Query)) {
                                echo '<option value="' . $row['Hospital_Ward_ID'] . '">' . $row['Hospital_Ward_Name'].'~~>'.$row['ward_nature']. '</option>';
                            }
                            ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><b>Room Name</b></td>
                <td>
                    <input type="text" required="" name="room_name" placeholder="Enter Room Name"/>
                </td> 
            </tr>
            <tr>

                <td colspan="2" style="text-align:right">
                    <input type="submit" name='save_room_btn' class="art-button-green" value="SAVE"/>
                </td>
            </tr> 
        </table>
    </form>
</div>
<script>
    // added on 21-02-2019 @Mfoy dn
    $('#sub_department').on('select2:select', function (e) {
       var sub_department= $('#sub_department').val();
       $.ajax({
            type:'POST',
            url:'ajax_check_if_sub_department_exist.php',
            data:{sub_department:sub_department},
            success:function(result){
                var myJSONObj = JSON.parse(result);
                if(myJSONObj.code=="200"){
                    var alert_var = myJSONObj.Sub_Department_Name+' sub-department is already assigned to '+myJSONObj.Hospital_Ward_Name+' ward';
                    alert(alert_var);
                }
            }
        });
    });
    //END added on 21-02-2019 @Mfoy dn

    function fill_room_selection(){
        var ward_id=$("#ward_id").val();
        $.ajax({
            type:'GET',
            url:'ward_room_selection_option.php',
            data:{ward_id:ward_id},
            success:function(data){
                $("#ward_room").html(data)
               // $("#country").select2({data: data});
            }
        });
    }
    $('#ward_id').on('select2:select', function (e) {
         fill_room_selection()
    });
    function add_room(){
          $("#addroom_dialog").dialog({
                        title: 'ADD ROOM',
                        width: '35%',
                        height: 250,
                        modal: true,
                    });
    }
    function add_ward(){
          $("#addward_dialog").dialog({
                        title: 'ADD WARD',
                        width: '50%',
                        height: 300,
                        modal: true,
                    });
    }
</script>
<!------------------------------------------------------------------------------------->
<script type="text/javascript">
    $(document).ready(function() {
        $('#ward_room').select2();
        $('#ward_id').select2();
        $('#ward_id2').select2();
        $('#sub_department').select2();
        $('#ward_nature').select2();
        $('#ward_type').select2();
    });
                        
</script>
<script>
    $(".numberOnly").bind("keydown", function (event) {
//alert(event.keyCode);  
        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
                // Allow: Ctrl+A
                        (event.keyCode == 65 && event.ctrlKey === true) ||
                        // Allow: Ctrl+C
                                (event.keyCode == 67 && event.ctrlKey === true) ||
                                // Allow: Ctrl+V
                                        (event.keyCode == 86 && event.ctrlKey === true) ||
                                        // Allow: home, end, left, right
                                                (event.keyCode >= 35 && event.keyCode <= 39)) {
                                    // let it happen, don't do anything
                                    return;
                                } else {
                                    // Ensure that it is a number and stop the keypress
                                    if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                                        event.preventDefault();
                                    }
                                }
                            });

</script>      

<div id="rooms_sections"></div>

<!-- Edit Rooms -->
<script>
    $(document).ready(() => {
        $('#edit_rooms').click(() => {
            $.post(
                'edit_rooms.php',{},(response)=>{
                    $("#rooms_sections").dialog({
                        title: "Edit Rooms Setup",
                        width: "40%",
                        height: 700,
                        modal: true
                    });
                    $("#rooms_sections").html(response);
                    $("#rooms_sections").dialog("open");
                }
            );

        })
    })
</script>
<!-- Edit Rooms -->


<?php
include("./includes/footer.php");
?>