<?php include("./includes/connection.php");
if (isset($_GET['Ward_ID'])) {
    $Ward_ID = $_GET['Ward_ID'];
} else {
    $Ward_ID = 0;
}
$Select_village = "select * from tbl_village where Ward_ID = '$Ward_ID'";
$result = mysqli_query($conn, $Select_village);;
echo '<option selected="selected" value=""></option>
    ';
while ($row = mysqli_fetch_array($result)) {;
    echo '        <option>';
    echo $row['village_name'];;
    echo '</option>
    ';
};
echo ' ';
