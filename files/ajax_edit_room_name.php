<?php
$edit_response = "";
$room_id = "";

include './includes/connection.php';

if (isset($_POST['edit_response']) && $_POST['edit_response'] != "" && $_POST['edit_response'] != null) {
    $edit_response = $_POST['edit_response'];
}
if (isset($_POST['my_id']) && $_POST['my_id'] != "" && $_POST['my_id'] != null) {
    $room_id = $_POST['my_id'];
}

if (isset($_POST['update_request']) && $_POST['update_request'] != "" && $_POST['update_request'] != null) {
    $update_request = $_POST['update_request'];
}
if (isset($_POST['my_id']) && $_POST['my_id'] != "" && $_POST['my_id'] != null) {
    $my_id = $_POST['my_id'];
}
if (isset($_POST['room_name']) && $_POST['room_name'] != "" && $_POST['room_name'] != null) {
    $room_name = $_POST['room_name'];
}
if (isset($_POST['sub_dept_id']) && $_POST['sub_dept_id'] != "" && $_POST['sub_dept_id'] != null) {
    $sub_dept_id = $_POST['sub_dept_id'];
}

$htm = "";
if ($edit_response == "edit") {
    $select_room = mysqli_query($conn, "SELECT theater_room_id,theater_room_name,Sub_Department_ID FROM tbl_theater_rooms WHERE theater_room_id = '$room_id'");
    $Sub_Department_ID_now = "";
    while($row = mysqli_fetch_array($select_room)) {
        $Sub_Department_ID_now = $row['Sub_Department_ID'];
        $theater_room_name = $row['theater_room_name'];
        $theater_room_id = $row['theater_room_id'];
    }

    // die($Sub_Department_ID_now);

    $htm .= "<tr>
                <td width=10% style='text-align: center; padding: 8px;'><b>Theater Room</b></td>
                <td width=45% style='text-align: center;' colspan='2'><input style='text-align: center;' id='room_name' name='room_name' type='text' value='". $theater_room_name ."'></td>
                <td width=10% style='text-align: center;'><span id='result2'></span></td>

            </tr>
            <tr>
                <td width=10% style='text-align: center; padding: 8px;'><b>Sub Department</b></td>
                <td width=45% style='text-align: center;' colspan='2'>
                    <select style='width: 100%;' name='sub_dept' id='sub_dept'>
                        <option value='0'>~~~ Select Sub Department ~~~</option>";


    $select_sub_dept = mysqli_query($conn, "SELECT sb.Sub_Department_ID,sb.Sub_Department_Name FROM tbl_department d, tbl_sub_department sb WHERE d.Department_ID=sb.Department_ID AND d.Department_Location IN('Procedure','Surgery')");
    while ($data = mysqli_fetch_array($select_sub_dept)) {
        if ($data['Sub_Department_ID'] == $Sub_Department_ID_now) {

            $htm .= "<option selected='selected' value='" . $data['Sub_Department_ID'] . "'>" . $data['Sub_Department_Name'] . "</option>";
        } else {
            
            $htm .= "<option value='" . $data['Sub_Department_ID'] . "'>" . $data['Sub_Department_Name'] ."</option>";

        }
    }

    $htm .= "</select>
                </td>
                <td width=5% style='text-align: center;'><input type='submit' name='btn_update' onclick='update_room_detaits(".$room_id.")' id='btn_update' value='UPDATE' class='art-button-green'></td>
            </tr>";
    $htm .= "
            <script>
                $(document).ready(function() {
                    $('select').select2();
                });
            </script>";
    echo $htm;
}

if($update_request == "update") {
    $update_data = mysqli_query($conn, "UPDATE tbl_theater_rooms SET theater_room_name = '$room_name', Sub_Department_ID = '$sub_dept_id' WHERE theater_room_id = '$my_id'");

    if($update_data) {
        echo "Updated Successfuly";
    } else {
        echo "Fail To Update";
    }
}
mysqli_close($conn);