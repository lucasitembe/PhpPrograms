<?php 
    include("./includes/connection.php"); 
    $room_type = $_GET['room_type'];
    $ward_room_id = $_GET['ward_room_id'];
    $disableRoom = $_GET['disableRoom'];
    if(!empty($room_type)){
        $update_room = mysqli_query($conn, "UPDATE tbl_ward_rooms SET room_type = '$room_type' WHERE ward_room_id = '$ward_room_id'");
    }elseif(!empty($disableRoom) && $disableRoom == 'disableRoom'){
        $update_query = mysqli_query($conn,"UPDATE tbl_ward_rooms SET Room_Status = 'not available' WHERE ward_room_id = '$ward_room_id'") or die(mysqli_error($conn));
    }elseif(!empty($disableRoom) && $disableRoom == 'enableRoom'){
        $update_query = mysqli_query($conn,"UPDATE tbl_ward_rooms SET Room_Status = 'available' WHERE ward_room_id = '$ward_room_id'") or die(mysqli_error($conn));
    }

    if(isset($_POST['get_wards'])){
        $room_id = (isset($_POST['selected_id'])) ? $_POST['selected_id'] : 0;
        $count = 1;
        $Query = mysqli_query($conn,"SELECT * from tbl_ward_rooms WHERE ward_id = '$room_id'") or die(mysqli_error($conn));
        $Room_Type ='';
        while ($row = mysqli_fetch_assoc($Query)) {
            $Room_Type = $row['room_type'];
            $Room_Status = $row['Room_Status'];
            $ward_room_id = $row['ward_room_id'];
            echo '
                <tr>
                    <td style="text-align: center;">'.$count++.'</td>
                    <td><input style="width:100%" id="'.$row["ward_room_id"].'" value="'.$row["room_name"].'"/></td>
                    <td>
                        <select id="room_type'.$ward_room_id.'" onchange="change_room_type('.$row["ward_room_id"].')" style="text-align: center;width:100%; height: 32px; border-radius: 5px; font-size: 12px;">
                            <option value="">Select Room Type</option>
                            <option '; if ($Room_Type == 'General'){ echo "selected='selected'"; } echo'>General</option>
                            <option '; if ($Room_Type == 'HDU'){ echo "selected='selected'"; } echo'>HDU</option>
                            <option '; if ($Room_Type == 'ICU'){ echo "selected='selected'"; } echo'>ICU</option>
                            <option '; if ($Room_Type == 'VIP'){ echo "selected='selected'"; } echo'>VIP</option>
                            <option '; if ($Room_Type == 'PRIVATE'){ echo "selected='selected'"; } echo'>PRIVATE</option>
                        </select>
                    </td>
                    <td><a href="#" class="art-button-green" onclick="edit_room('. $row["ward_room_id"] .')" style="font-family: Arial, Helvetica, sans-serif;">UPDATE</a>';
                    if($Room_Status == 'available'){
                        echo '<a href="#" class="art-button-green" onclick="disableRoom('. $row["ward_room_id"] .')" style="font-family: Arial, Helvetica, sans-serif; background: red;">DISABLE</a></td>';
                    }else{
                        echo '
                        <a href="#" class="art-button-green" onclick="enableRoom('. $row["ward_room_id"] .')" style="font-family: Arial, Helvetica, sans-serif;">ENABLE</a></td>';
                    }
                echo '
                </tr>
            ';
        }
    }

    if(isset($_POST['room_edit_requets'])){
        $new_room_name = (isset($_POST['room_name'])) ? $_POST['room_name'] : "";
        $room_id = (isset($_POST['room_id'])) ? $_POST['room_id'] : 0;
        $update_query = mysqli_query($conn,"UPDATE tbl_ward_rooms SET room_name = '$new_room_name' WHERE ward_room_id = '$room_id'") or die(mysqli_error($conn));

        if($update_query){
            echo "Room Updated";
        }else{
            echo "Something went wrong try again";
        }
    } else {

    // if(isset($GET['disable_room'])){
    //     $room_id = (isset($GET['ward_room_id'])) ? $GET['ward_room_id'] : 0;

    //     die("UPDATE tbl_ward_rooms SET Room_Status = 'not available' WHERE ward_room_id = '$room_id'");
    //     $update_query = mysqli_query($conn,"UPDATE tbl_ward_rooms SET Room_Status = 'not available' WHERE ward_room_id = '$room_id'") or die(mysqli_error($conn));

    //     if($update_query){
    //         echo "Room Disabled";
    //     }else{
    //         echo "Something went wrong try again";
    //     }
    // }
?>
<script>
    function change_room_type(ward_room_id) {
        // var room_type = $("#room_type").val();
        var room = 'room_type'+ward_room_id;
        var room_type = $("#"+room).val();

        if(room_type == ''){
            alert("Please Select Room Type before Saving")
        }else{
            if(confirm("Are you sure you want to Save This Room as "+room_type+"?")){
                $.ajax({
                    type: "GET",
                    url: "rooms_pull.php",
                    data: {ward_room_id:ward_room_id,room_type:room_type},
                    success: function (response) {
                        
                    }
                });
            }
        }
    }
    function disableRoom(ward_room_id) {
        var room_type = $("#room_type").val();

        if(room_type == ''){
            alert("Please Select Room Type before Saving")
        }else{
            if(confirm("Are you sure you want to Disable This Room?")){
                $.ajax({
                    type: "GET",
                    url: "rooms_pull.php",
                    data: {ward_room_id:ward_room_id,disableRoom:'disableRoom'},
                    success: function (response) {
                    
                    }
                });
            }
        }
    }
    function enableRoom(ward_room_id) {
        var room_type = $("#room_type").val();

        if(room_type == ''){
            alert("Please Select Room Type before Saving")
        }else{
            if(confirm("Are you sure you want to Enable This Room?")){
                $.ajax({
                    type: "GET",
                    url: "rooms_pull.php",
                    data: {ward_room_id:ward_room_id,disableRoom:'enableRoom'},
                    success: function (response) {
                    
                        
                    }
                });
            }
        }
    }
</script>


<?php
    }
?>