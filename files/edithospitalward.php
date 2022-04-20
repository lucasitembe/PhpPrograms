<?php
include("./includes/connection.php");
include("./includes/header.php");
$controlforminput = '';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
    if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes') {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<a href='edithospitalwardlist.php?AdmisionWorks=AdmisionWorksThisPage' class='art-button-green'>
    BACK
</a>
<br />
<br />
<br />
<br />
<br />
<?php
$Hospital_Ward_ID = mysqli_real_escape_string($conn, $_GET['Hospital_Ward_ID']);
if (isset($_POST['submittedAddNewWardForm'])) {

    $Hospital_Ward_Name = mysqli_real_escape_string($conn, $_POST['Hospital_Ward_Name']);
    $Number_Of_Beds = $_POST['Number_Of_Beds'];
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    $ward_nature = $_POST['ward_nature'];
    $ward_type = $_POST['ward_type'];
    $ward_status = $_POST['ward_status'];

    $insert_ward = "UPDATE tbl_hospital_ward SET ward_type='$ward_type',Hospital_Ward_Name='$Hospital_Ward_Name', Branch_ID='$Branch_ID',ward_nature='$ward_nature',ward_status='$ward_status' WHERE Hospital_Ward_ID='$Hospital_Ward_ID'";

    //get current ward beds no

    //    $wardbedsquery = mysqli_query($conn,"SELECT Number_of_Beds FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$Hospital_Ward_ID'") or die(mysqli_error($conn));
    //    $wardbeds = mysqli_fetch_assoc($wardbedsquery)['Number_of_Beds'];


    if (mysqli_query($conn, $insert_ward)) {
        //
        //        $Bed_Number = 1;
        //
        ////get current modified bed;
        //        $bed_ids = array();
        //
        //        $currents_bed_names = array();
        //        $beds_name_in_use = array();
        //
        //        while ($Bed_Number <= $Number_Of_Beds) {
        //            $beds = 'Bed No. ' . $Bed_Number;
        //            $currents_bed_names[] = $beds;
        //
        //            $query = mysqli_query($conn,"SELECT Bed_ID FROM tbl_beds WHERE Ward_ID='$Hospital_Ward_ID' AND Bed_Name ='$beds' AND Status='not available'") or die(mysqli_error($conn));
        //        
        //            if (mysqli_num_rows($query)) {
        //                $bed_ids[] = mysqli_fetch_assoc($query)['Bed_ID'];
        //                $beds_name_in_use[] = $beds;
        //                
        //             }
        //
        //            $Bed_Number++;
        //        }
        //
        //        if (count($bed_ids) == 0) {
        //            $bed_ids = array(-1);
        //        }
        //        
        //         $queryBedNotInList = mysqli_query($conn,"SELECT Bed_ID,Bed_Name FROM tbl_beds WHERE Ward_ID='$Hospital_Ward_ID'  AND Status='not available'") or die(mysqli_error($conn));
        //        
        //         while ($rowda = mysqli_fetch_array($queryBedNotInList)) {
        //                $beds_nameuse=$rowda['Bed_Name'];
        //                $Bed_ID=$rowda['Bed_ID'];
        //                 if (!in_array($beds_nameuse, $currents_bed_names)) {
        //                     mysqli_query($conn,"UPDATE tbl_admission SET Bed_ID =NULL WHERE Bed_ID='$Bed_ID' AND Hospital_Ward_ID='$Hospital_Ward_ID'") or die(mysqli_error($conn));
        //                 
        //                     //echo $Bed_ID;
        //                 }
        //             }
        //
        //        $query = mysqli_query($conn,"DELETE FROM tbl_beds WHERE Ward_ID='$Hospital_Ward_ID' AND Bed_ID NOT IN (" . implode(',', $bed_ids) . ")") or die(mysqli_error($conn));
        //
        //        if ($query) {
        //            $Ward_ID = $Hospital_Ward_ID;
        //
        //        if (count($bed_ids) == 0) {
        //            $bed_ids = array(-1);
        //        }
        //            $Bed_Number = 1;
        //
        //            while ($Bed_Number <= $Number_Of_Beds) {
        //                $Bed_Name = 'Bed No. ' . $Bed_Number;
        //                $Status = 'available';
        //                
        //                
        //
        //                if (!in_array($Bed_Name, $beds_name_in_use)) {
        //
        //                    $insert_beds = "INSERT INTO tbl_beds(Bed_Name, Status, Ward_ID)
        //						VALUES('$Bed_Name', '$Status', '$Ward_ID')";
        //
        //                    mysqli_query($conn,$insert_beds) or die(mysqli_error($conn));
        //                }
        //
        //                $Bed_Number++;
        //            }
        //        }
        echo '<script>
	  alert("Ward Updated Successfully");
          window.location="edithospitalward.php?Hospital_Ward_ID=' . $Hospital_Ward_ID . '&AdmisionWorks=AdmisionWorksThisPage";
	</script>';
    } else {
        echo '<script>
	  alert("An error has occured! Please try again later");
          window.location="edithospitalward.php?Hospital_Ward_ID=' . $Hospital_Ward_ID . '&AdmisionWorks=AdmisionWorksThisPage";
	</script>';
        // echo 'Error! Ward NOT Added';
        //header("location: addhospitalward.php?added=true&alladded=7&addhospitalward=true");
    }
}

$sql = "SELECT * FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$Hospital_Ward_ID'";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$row = mysqli_fetch_assoc($result);
?>
<center>
    <table width=50%>
        <tr>
            <td>
                <center>
                    <form action='#' method='post'>
                        <fieldset>

                            <legend align="center"><b>EDIT HOSPITAL WARD</b></legend>
                            <table width=100%>
                                <tr>
                                    <td width=30%><b>Ward Name</b></td>
                                    <td width=70%>
                                        <input type='text' name='Hospital_Ward_Name' required='required' value="<?php echo $row['Hospital_Ward_Name'] ?>" size='70' id='Hospital_Ward_Name' placeholder='Enter Ward Name'>
                                    </td>
                                </tr>
                                <tr style="display: none">
                                    <td width=30%><b>Number of Beds</b></td>
                                    <td width=70%>
                                        <input type='text' class="numberOnly" value="<?php echo $row['Number_of_Beds'] ?>" name='Number_Of_Beds' required='required' size='50' id='Number_Of_Beds' placeholder='Enter Number of Beds'>
                                    </td>
                                </tr>

                                <tr>
                                    <td width=30%><b>Ward Nature</b></td>
                                    <td width=70%>
                                        <select name="ward_nature" style="width:100%" required="required">
                                            <?php
                                            $male = '';
                                            $female = '';
                                            $both = '';


                                            if ($row['ward_nature'] == 'Male') {
                                                $male = "selected";
                                            }

                                            if ($row['ward_nature'] == 'Female') {
                                                $female = "selected";
                                            }

                                            if ($row['ward_nature'] == 'Both') {
                                                $both = "selected";
                                            }

                                            ?>

                                            <option value="Male" <?php echo $male; ?>>Male</option>
                                            <option value="Female" <?php echo $female; ?>>Female</option>
                                            <option value="Both" <?php echo $both; ?>>Both</option>

                                        </select>

                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Ward Type</b></td>
                                    <td>
                                        <select name="ward_type" style="width:100%" required="" id="ward_type">
                                            <option value=""></option>
                                            <?php
                                            $ward_type_name = "";
                                            $ward_type = $row['ward_type'];
                                            if (!empty($ward_type) || $ward_type != "" || $ward_type != null) {
                                                if ($ward_type == 'ordinary_ward') $ward_type_name = "Ordinary Ward";
                                                else $ward_type_name = "Mortuary Ward";
                                                echo "<option selected='selected' value='$ward_type'>$ward_type_name</option>";
                                            }
                                            ?>
                                            <option value="ordinary_ward">Ordinary Ward</option>
                                            <option value="mortuary_ward">Mortuary Ward</option>
                                            <option value="icu_ward">ICU Ward</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Ward Status</b></td>
                                    <td>
                                        <select name="ward_status" style="width:100%" required="required" id="ward_status">
                                            <option value=""></option>
                                            <?php
                                            $ward_type_name = "";
                                            $ward_status = $row['ward_status'];
                                            if (!empty($ward_status) || $ward_status != "" || $ward_status != null) {
                                                if ($ward_status == 'active') {
                                                    $ward_status_name = "Active";
                                                    echo "<option selected='selected' value='$ward_status'>$ward_status_name</option>";
                                                    echo "<option value='not active'>Not Active</option>";
                                                } else {
                                                    $ward_status_name = "Not Active";
                                                    echo "<option selected='selected' value='$ward_status'>$ward_status_name</option>";
                                                    echo "<option value='active'>Active</option>";
                                                }
                                            }
                                            ?>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedAddNewWardForm' value='true' />
                                    </td>
                                </tr>
                            </table>
                    </form>
                    </fieldset>
                </center>
            </td>
        </tr>
    </table>
</center>
<br />
<br />
<br />
<script>
    $(".numberOnly").bind("keydown", function(event) {
        //alert(event.keyCode);  
        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
            // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) ||
            // Allow: Ctrl+C
            (event.keyCode == 67 && event.ctrlKey === true) ||
            // Allow: Ctrl+V
            (event.keyCode == 86 && event.ctrlKey === true) ||
            // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        } else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                event.preventDefault();
            }
        }
    });
</script>
<?php
include("./includes/footer.php");
?>