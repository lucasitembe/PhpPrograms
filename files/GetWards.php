<?php
include("./includes/connection.php");
    $District_ID;
    if (isset($_GET['District_ID'])) {
        $District_ID = $_GET['District_ID'];
    } else {
        $District_ID = 2;
    }
    $Select_District = "select Ward_ID,Ward_Name from tbl_ward w, tbl_district d
                                where d.District_ID = w.District_ID and w.District_ID = '$District_ID'";  
    $result = mysqli_query($conn, $Select_District) or die(mysqli_error($conn)); ?>

    <?php
    $htm .= "<select name='select-ward' id='select-ward'>";
    $htm .= "<option selected value=''> Select Ward </option>";
    while ($row = mysqli_fetch_array($result)) {
        $htm .=
            "<option value=" . $row['Ward_ID'] . ">" . $row['Ward_Name'] . "</option>";
    }
    $htm .= "</select>";
    echo $htm;
?>
