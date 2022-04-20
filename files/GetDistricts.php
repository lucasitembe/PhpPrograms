<?php
include("./includes/connection.php");
    $Region_Name = "";
    if (isset($_GET['Region_Name']) && isset($_GET['district'])) {
        $Region_Name = $_GET['Region_Name'];
        $district = $_GET['district'];
    // die(print_r($Region_Name));

        $my_District_ID_select2 = mysqli_query($conn, "SELECT Region_ID FROM tbl_regions WHERE Region_Name LIKE '%$Region_Name%' LIMIT 1");
        $Region_ID = mysqli_fetch_assoc($my_District_ID_select2)['Region_ID'];
        $Select_District = "SELECT District_Name from tbl_district
                                        where Region_ID = '$Region_ID' AND District_Name <> '$district'";
        $result = mysqli_query($conn, $Select_District);

        // $html = "<option selected='selected' value=''>Select District</option>";
        // $html = "";

        while ($row = mysqli_fetch_array($result)) {

            $html .= "<option value='" . $row['District_Name'] . "'> " . $row['District_Name'] . " </option>";
        }
        echo $html;
    }
// else {
//     $Region_Name = 2;
// }

    if (isset($_GET['Region_Name'])) {
        $Region_Name = $_GET['Region_Name'];
        // die(print_r($Region_Name));

        $my_District_ID_select2 = mysqli_query($conn, "SELECT Region_ID FROM tbl_regions WHERE Region_Name LIKE '%$Region_Name%' LIMIT 1");
        $Region_ID = mysqli_fetch_assoc($my_District_ID_select2)['Region_ID'];
        $Select_District = "SELECT District_Name,District_ID from tbl_district
                                            where Region_ID = '$Region_ID'";
        $result = mysqli_query($conn, $Select_District);

        $html = "<option selected='selected' value=''>Select District</option>";
        // $html = "";

        while ($row = mysqli_fetch_array($result)) {

            $html .= "<option value='" . $row['District_ID'] . "'> " . $row['District_Name'] . " </option>";
        }
        echo $html;
    } 

    if(isset($_GET['District'])) {
        $District_Name = $_GET['District'];

        $my_District_ID_select = mysqli_query($conn, "SELECT District_ID FROM tbl_district WHERE District_Name LIKE '%$District_Name$' LIMIT 1");
        $District_ID = mysqli_fetch_assoc($my_District_ID_select)['District_ID'];
        $select_wards = mysqli_query($conn, "SELECT Ward_Name FROM tbl_ward WHERE District_ID = '$District_ID'"); 
        
        while ($row = mysqli_fetch_array($select_wards)) {

            $html .= "<option value='" . $row['Ward_Name'] . "'> " . $row['Ward_Name'] . " </option>";
        }
        echo $html;
    }

    if (isset($_GET['Ward'])) {
        $Ward = $_GET['Ward'];

        $str = $Ward;
        $pattern = "/1|2|3|4|5|6|7|8|9/i";
        $check = preg_match($pattern, $str);
        if ($check == 1) {
            $my_District_ID_select = mysqli_query($conn, "SELECT District_ID FROM tbl_ward WHERE Ward_ID = '$Ward' LIMIT 1");
            $District_ID = mysqli_fetch_assoc($my_District_ID_select)['District_ID'];
            $select_wards = mysqli_query($conn, "SELECT Ward_Name FROM tbl_ward WHERE District_ID = '$District_ID' AND Ward_ID <> '$Ward'");
        } else {
            $my_District_ID_select = mysqli_query($conn, "SELECT District_ID FROM tbl_ward WHERE Ward_Name LIKE '%$Ward%' LIMIT 1");
            $District_ID = mysqli_fetch_assoc($my_District_ID_select)['District_ID'];
            $select_wards = mysqli_query($conn, "SELECT Ward_Name FROM tbl_ward WHERE District_ID = '$District_ID'");
        }
        while ($row = mysqli_fetch_array($select_wards)) {

            $html .= "<option value='" . $row['Ward_Name'] . "'> " . $row['Ward_Name'] . " </option>";
        }
        echo $html;
    }

    

?>
