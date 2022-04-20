<?php 
include("./includes/connection.php");
if(isset($_GET['ward_id'])){
   $ward_id= $_GET['ward_id'];
}
if(isset($_GET['ward_room_id'])){
   $ward_room_id= $_GET['ward_room_id'];
}
?>

 <option value="" selected="selected"  disabled="disabled">--Select Bed --</option> 
<?php
    $Query = mysqli_query($conn,"select * from tbl_beds WHERE Ward_ID='$ward_id' AND ward_room_id='$ward_room_id'") or die(mysqli_error($conn));
    while ($row = mysqli_fetch_assoc($Query)) {
        echo '<option value="' . $row['Bed_Name'] . '">' . $row['Bed_Name'] . '</option>';
    }
?>


