<?php
include("./includes/connection.php");
    $Region_Name = "";
    $data = array();
    if (isset($_GET['Region_Name']) && isset($_GET['district']) && isset($_GET['ward_id']) && isset($_GET['village_id']) && isset($_GET['reg_id'])) {
        $Registration_ID = $_GET['reg_id'];
        $select_Patient = mysqli_query($conn, "SELECT District,District_ID,ward_id,village,leader_id,ten_cell_leader_name,Region,Ward FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));

        while($row = mysqli_fetch_array($select_Patient)) {
            $District2 = $row['District'];
            $District_ID2 = $row['District_ID'];
            $ward_id2 = $row['ward_id'];
            $village2 = $row['village'];
            $leader_id2 = $row['leader_id'];
            $ten_cell_leader_name2 = $row['ten_cell_leader_name'];
            $Ward2 = $row['Ward'];
        }

        $Region_Name = $_GET['Region_Name'];
        $district = $_GET['district'];

        $my_District_ID_select2 = mysqli_query($conn, "SELECT Region_ID FROM tbl_regions WHERE Region_Name LIKE '%$Region_Name%' LIMIT 1");
        $Region_ID = mysqli_fetch_assoc($my_District_ID_select2)['Region_ID'];
        $Select_District = "SELECT District_Name,District_ID from tbl_district where Region_ID = '$Region_ID'";
        $result = mysqli_query($conn, $Select_District);


        $html = "";
        while ($row = mysqli_fetch_array($result)) {
            if ($row['District_ID'] == $District2) {
                $html .= "<option selected='selected' value='" . $row['District_ID'] . "'>" . $row['District_Name'] . "</option>";
            } else if ($row['District_Name'] == $District2) {
                $html .= "<option selected='selected' value='" . $row['District_ID'] . "'>" . $row['District_Name'] . "</option>";
            } else {
                $html .= "<option value='" . $row['District_ID'] . "'> " . $row['District_Name'] . " </option>";
            }
        }
        if(trim($District2) == "" || trim($District2) == NULL) {
                $html .= "<option selected='selected' value=''></option>";
        } 
        $data['html1'] = $html;

        $district_id = $_GET['district'];
        $ward_id = $_GET['ward_id'];

        $Select_District = "SELECT Ward_ID,Ward_Name from tbl_ward where District_ID = '$district_id' AND Ward_ID<>'$ward_id'";
        $result = mysqli_query($conn, $Select_District);

        $Select_District3 = "SELECT Ward_ID,Ward_Name from tbl_ward where Ward_ID='$ward_id'";
        $result3 = mysqli_query($conn, $Select_District3);

        $html2 = "";

        $no_of_ward = mysqli_num_rows($result3);
        if($no_of_ward > 0) {
            while ($row = mysqli_fetch_array($result3)) {

                if ($row['Ward_ID'] == $Ward2) {
                    $html2 .= "<option selected='selected' value='" . $row['Ward_ID'] . "'>" . $row['Ward_Name'] . "</option>";
                }
                else if ($row['Ward_Name'] == $Ward2) {
                    $html2 .= "<option required selected='selected' value='" . $row['Ward_ID'] . "'>" . $row['Ward_Name'] . "</option>";
                } 
                else {
                    $html2 .= "<option selected='selected' value='" . $Ward2 . "'>" . $Ward2 . "</option>";
                }
            }

        } else {
            $html2 .= "<option selected='selected' value='".$Ward2."'>".$Ward2."</option>";
        }

        while ($row = mysqli_fetch_array($result)) {
                $html2 .= "<option value='" . $row['Ward_ID'] . "'> " . $row['Ward_Name'] . " </option>";
        }
        $data['html2'] = $html2;


        $html3 = "";

        $village_id = $_GET['village_id'];
        $ward_id = $_GET['ward_id'];
        $str = $village_id;
        $pattern = "/^[1|2|3|4|5|6|7|8|9]/i";
        $check = preg_match($pattern, $str);
        if ($check == 1) {
            $select_village = "SELECT village_name from tbl_village where Ward_ID = '$ward_id' AND village_id = '$village_id'";
            $result = mysqli_query($conn, $select_village);
            $village_name = mysqli_fetch_assoc($result)['village_name'];
            $tafuta = mysqli_num_rows($result);
            if ($tafuta > 0) {
                $html3 .= "<option selected='selected' value='" . $village_id . "'>" . $village_name . "</option>";
            } else {
                $html3 .= "<option selected='selected' value=''></option>";
            }
            // echo $Ward;
        } else {
            $sql = mysqli_query($conn, "SELECT village_id FROM tbl_village WHERE village_name = '$village_id' AND Ward_ID = '$ward_id'");
            $id = mysqli_fetch_assoc($sql)['village_id'];
            $html3 .= "<option selected='selected' value='$id'>".$village_id."</option>";
        }

        // $Select_Ward = "SELECT village_id,village_name from tbl_village where Ward_ID = '$ward_id' AND village_id = '$village_id'";
        // $result = mysqli_query($conn, $Select_Ward);
        // $tafuta = mysqli_num_rows($result);

        // if($tafuta > 0) {
        //     while ($row = mysqli_fetch_array($result)) {
        //         if ($row['village_id'] == $village_id) {
        //             $html3 .= "<option selected='selected' value='" . $row['village_id'] . "'>" . $row['village_name'] . "</option>";
        //         } 
        //     }
        // } else {
        //     $html3 .= "<option selected='selected' value=''></option>";
        // }

        $Select_Ward2 = "SELECT DISTINCT village_id,village_name from tbl_village where Ward_ID = '$ward_id' AND village_id <> '$village_id'";
        $result2 = mysqli_query($conn, $Select_Ward2);

        
        while ($row2 = mysqli_fetch_array($result2)) {
            $html3 .= "<option value='" . $row2['village_id'] . "'> " . $row2['village_name'] . " </option>";
        }
        $data['html3'] = $html3;

        echo json_encode($data);
        
    }





    // if (isset($_GET['Region_Name']) && isset($_GET['district'])) {
    //     $Region_Name = $_GET['Region_Name'];
    //     $district = $_GET['district'];

    //     $my_District_ID_select2 = mysqli_query($conn, "SELECT Region_ID FROM tbl_regions WHERE Region_Name LIKE '%$Region_Name%' LIMIT 1");
    //     $Region_ID = mysqli_fetch_assoc($my_District_ID_select2)['Region_ID'];
    //     $Select_District = "SELECT District_Name,District_ID from tbl_district where Region_ID = '$Region_ID'";
    //     $result = mysqli_query($conn, $Select_District);

        
    //     $html = "";
    //     while ($row = mysqli_fetch_array($result)) {
    //         if($row['District_ID'] == $district) {
    //             $html .= "<option selected='selected' value='".$row['District_ID']."'>".$row['District_Name']."</option>";
    //         } else {
    //             $html .= "<option value='" . $row['District_ID'] . "'> " . $row['District_Name'] . " </option>";
    //         }

    //     }
    //     echo $html;
    // }

    // if (isset($_GET['district_id']) && isset($_GET['ward_id'])) {
    //     $district_id = $_GET['district_id'];
    //     $ward_id = $_GET['ward_id'];

    //     $Select_District = "SELECT Ward_ID,Ward_Name from tbl_ward where District_ID = '$district_id'";
    //     $result = mysqli_query($conn, $Select_District);

    //     $html = "";
    //     while ($row = mysqli_fetch_array($result)) {
            
    //         if ($row['Ward_ID'] == $ward_id) {
    //             $htmls = "<option selected='selected' value='" . $row['Ward_ID'] . "'>" . $row['Ward_Name'] . "</option>";
    //         } else {
    //                 $html .= "<option value='" . $row['Ward_ID'] . "'> " . $row['Ward_Name'] . " </option>";
                
    //         }
    //     }
    //     echo $htmls . $html;
    // }

    // if (isset($_GET['village_name']) && isset($_GET['ward_id'])) {
    //     $village_id = $_GET['village_name'];
    //     $ward_id = $_GET['ward_id'];

    //     $Select_Ward = "SELECT village_id,village_name from tbl_village where Ward_ID = '$ward_id' AND village_id = '$village_id'";
    //     $result = mysqli_query($conn, $Select_Ward);

    //     $Select_Ward2 = "SELECT DISTINCT village_id,village_name from tbl_village where Ward_ID = '$ward_id'";
    //     $result2 = mysqli_query($conn, $Select_Ward2);        

    //     $html = "";
    //     while ($row = mysqli_fetch_array($result)) {
    //         if ($row['village_id'] == $village_id) {
    //             // $html .= "<option selected='selected' value='" . $row['village_id'] . "'>" . $row['village_name'] . "</option>";
    //         } 
    //     }
    //     while ($row2 = mysqli_fetch_array($result2)) {
    //             $html .= "<option value='" . $row2['village_id'] . "'> " . $row2['village_name'] . " </option>";
    //     }
    //     echo $html;
    // }

    if (isset($_GET['village_name']) && isset($_GET['ten_cell_leader_id'])) {
        $village_name = $_GET['village_name'];
        $ten_cell_leader_id = $_GET['ten_cell_leader_id'];

        $select_leader = mysqli_query($conn, "SELECT v.village_id FROM tbl_village v, tbl_leaders l 
        WHERE v.village_id=l.village_id AND v.village_name LIKE '%$village_name%' LIMIT 1");
        $village_id = mysqli_fetch_assoc($select_leader)['village_id'];

        $Select_Ward = "SELECT leader_ID,leader_name from tbl_leaders where village_id = '$village_id'";
        $result = mysqli_query($conn, $Select_Ward);

        $count = 1;

        while ($row = mysqli_fetch_array($result)) {
            if ($row['leader_name'] == $ten_cell_leader_name) {
                $html = "<option selected='selected' value='" . $row['leader_ID'] . "'>" . $row['leader_name'] . "</option>";
            } else {
            if ($count == 1) {
                $html .= "<option selected='selected' value='" . $row['leader_ID'] . "'> " . $row['leader_name'] . " </option>";
                $count++;
            }else {
                $html .= "<option value='" . $row['leader_ID'] . "'> " . $row['leader_name'] . " </option>";
            }
                
            }
        }
        echo $html;
    }

    if (isset($_GET['Region_Name']) && isset($_GET['From'])) {
        $Region_Name = $_GET['Region_Name'];

        $my_District_ID_select2 = mysqli_query($conn, "SELECT Region_ID FROM tbl_regions WHERE Region_Name LIKE '%$Region_Name%' LIMIT 1");
        $Region_ID = mysqli_fetch_assoc($my_District_ID_select2)['Region_ID'];
        $Select_District = "SELECT District_Name,District_ID from tbl_district
                                            where Region_ID = '$Region_ID'";
        $result = mysqli_query($conn, $Select_District);

        $html = "<option selected='selected' value=''>Select District</option>";


        while ($row = mysqli_fetch_array($result)) {

            $html .= "<option value='" . $row['District_ID'] . "'> " . $row['District_Name'] . " </option>";
        }
        echo $html;
    }

    if (isset($_GET['Districts'])) {
        $District = $_GET['Districts'];
        $str = $District;
        $pattern = "/1|2|3|4|5|6|7|8|9/i";
        $check = preg_match($pattern, $str);
        if ($check == 1) {
            $Select_Ward = "SELECT Ward_ID,Ward_Name from tbl_ward where District_ID = '$District'";
            $result = mysqli_query($conn, $Select_Ward);
        } else {
            $Select_District = "SELECT District_ID from tbl_district where District_Name LIKE '%$District%' LIMIT 1";
            $result_district = mysqli_query($conn, $Select_District);
            $District_ID = mysqli_fetch_assoc($result_district)['District_ID'];

            $Select_Ward = "SELECT Ward_ID,Ward_Name from tbl_ward where District_ID = '$District_ID'";
            $result = mysqli_query($conn, $Select_Ward);
        }

        $html = "<option selected='selected' value='weee'>Select District</option>";


        while ($row = mysqli_fetch_array($result)) {

            $html .= "<option value='" . $row['Ward_ID'] . "'> " . $row['Ward_Name'] . " </option>";
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
