<?php
 require_once('../../includes/connection.php');
//
//  isset($_GET['anatomical']) ? $anatomical = mysqli_real_escape_string($conn,$_GET['anatomical']) : $anatomical != '';

if (isset($_GET['anatomical'])) {
    $anatomical = mysqli_real_escape_string($conn,$_GET['anatomical']);
    $query="INSERT INTO tbl_anatomical_site(name) VALUES('$anatomical')";
    $insert=mysqli_query($conn,$query);

    if ($insert) { 
        $anatomical_sites = "";
        $select = mysqli_query($conn,"SELECT id,name FROM tbl_anatomical_site") or die(mysqli_error($conn));
         echo '<option value="All">~~~~~Select Anatomical Site~~~~~</option>';
        while ($row = mysqli_fetch_array($select)) {
         $anatomical_sites .='<option value="' . $row['id'] . '">' . $row['name'] . 
         '</option>';
        }
        echo $anatomical_sites;
        }
    } else {
        # code...
    }
    


 