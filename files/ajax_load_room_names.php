<?php
$room_name_request = "";
include './includes/connection.php';

if (isset($_POST['room_name_request']) && $_POST['room_name_request'] != "" && $_POST['room_name_request'] != null) {
    $room_name_request = $_POST['room_name_request'];
}

if ($room_name_request == "update") {
    $result = "";
    $result .= "
        <tr  style='background-color: #CCC;'>
            <th width=5%>S/N</th>
            <th width=25%>Theater Room Name</th>
            <th width=10%>Room Status</th>
            <th width=20%>Action</th>
        </tr>
    ";
    $retrieve_query = "SELECT theater_room_id,theater_room_name,room_status FROM tbl_theater_rooms order by theater_room_name";
    $retrieve_query_run = mysqli_query($conn, $retrieve_query);
    $num_of_rows = mysqli_num_rows($retrieve_query_run);

    if ($num_of_rows > 0) {
        $sn = 1;
        while ($rows = mysqli_fetch_array($retrieve_query_run)) {
            $id = $rows['theater_room_id'];


            $result .= "<tr style='background-color: white;'>";
            // $result .= "<tdclass='hide'>" . $rows['pay_mode_id'] . "</td>";
            $result .= '<td style="text-align: center;">' . $sn . '</td>';
            $result .= "<td style='text-align: center;'>" . $rows['theater_room_name'] . "</td>";
            $result .= "<td style='text-align: center;'>" . $rows['room_status'] . "</td>";
            $result .= "<td style='text-align: center;'>
                            <input type='submit' name='submit_room_name3' onclick='edit_room_name($id)' id='submit_room_name3' value='Edit' class='art-button-green'>
                            <input type='submit' name='submit_room_name2' onclick='status_room_name($id)' id='submit_room_name2' value='Enable / Disable' class='art-button-green'>
                        </td>";
            $result .= "</tr>";
            $sn++;
        }
        echo $result;
    } else {
        $result .= '
        <tr style="background-color: white;">
            <td></td>
            <td style="text-align: center;">
                <h5 style="color: red;">No Theater Room Founds</h5>
            </td>
            <td></td>
        </tr>';
        echo $result;
    }
}

mysqli_close($conn);
