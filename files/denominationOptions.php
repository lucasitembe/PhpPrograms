<?php
include("./includes/connection.php");
$Religion_ID = $_GET['Religion_ID'];
echo '<option value = "" selected = "selected"> --Select Denomination--</option>';
$denomination = mysqli_query($conn,"SELECT * from tbl_denominations WHERE Religion_ID='$Religion_ID'");
while ($row = mysqli_fetch_array($denomination)) {
    echo " <option value='" . $row['Denomination_ID'] . "'>
        " . $row['Denomination_Name'] . "
    </option>";
}

