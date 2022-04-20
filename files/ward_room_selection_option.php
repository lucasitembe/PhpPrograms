<?php include ("./includes/connection.php");
if (isset($_GET['ward_id'])) {
    $ward_id = $_GET['ward_id'];
};
echo '
 <option value="" selected="selected"  disabled="disabled">--Select Room --</option> 
';
$Query = mysqli_query($conn, "select * from tbl_ward_rooms WHERE ward_id='$ward_id'  AND Room_Status = 'available'") or die(mysqli_error($conn));
while ($row = mysqli_fetch_assoc($Query)) {
    echo '<option value="' . $row['ward_room_id'] . '">' . $row['room_name'] . '</option>';
};
echo '

';