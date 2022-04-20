<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Reception_Works'])) {
        if ($_SESSION['userinfo']['Reception_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

?>

<script src="js/functions.js"></script>
<script src="pikaday.js"></script>

<?php


$Current_Username = $_SESSION['userinfo']['Given_Username'];

$sql_check_prevalage = "SELECT Edit_Patient_Information FROM tbl_privileges WHERE Edit_Patient_Information='yes' AND "
    . "Given_Username='$Current_Username'";

$sql_check_prevalage_result = mysqli_query($conn, $sql_check_prevalage);
if (!mysqli_num_rows($sql_check_prevalage_result) > 0) {
?>
    <script>
        var privalege = alert("You don't have the privelage to access this button")
        document.location = "./index.php?InvalidPrivilege=yes";
    </script>
<?php
}
?>

<?php
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Reception_Works'] == 'yes') {
?>
        <a href='searchvisitorsoutpatientlist.php?SearchVisitorsOutPatientList=SearchVisitorsOutPatientListThisPage' class='art-button-green'>
            VISITORS
        </a>
<?php }
} ?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Reception_Works'] == 'yes') {
?>
        <a href='visitorform.php?Registration_ID=<?php echo $Registration_ID; ?>&VisitorFormPatient=VisitorFormPatientThisPage' class='art-button-green'>
            BACK
        </a>
<?php }
} ?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

<script type="text/javascript">
    function readImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#Patient_Picture').attr('src', e.target.result).width('50%').height('70%');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearPatientPicture() {
        document.getElementById('Patient_Picture_td').innerHTML = "<img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' width=50% height=50%>"
    }
</script>

<script language="javascript" type="text/javascript">
    function searchEmployee(Employee_Name) {
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=100% src='viewpatientsIframe.php?Employee_Name=" + Employee_Name + "'></iframe>";
    }
</script>


<script type="text/javascript" language="javascript">
    function getDistricts() {
        var Region_Name = document.getElementById("region").value;
        console.log(Region_Name);
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'GetDistricts.php?Region_Name=' + Region_Name+'&From=GetDistricts', true);
        mm.send();
        $('#District').val("");
        $('#ward').val("");
        // $('#select-village').val("");
        // $("#ten_cell_leader_name").html("<option>Select Leader</option>");
        // $("#select-village").html("<option>Select Village</option>");
        $("#ward").html("<option>Select Ward</option>");
    }

    function AJAXP() {
        var data = mm.responseText;
        document.getElementById('District').innerHTML = data;
    }

    function getWards() {
      var District_ID = document.getElementById("District").value;
      $.ajax({
        url:'GetWards.php',
        type:'get',
        data:{From:'Get_Wards',District_Name:District_ID},
        success:function(results){
          $("#ward").html(results);
          $("#select-village").val("");
          $('#ten_cell_leader_name').val("");
          $("#ten_cell_leader_name").html("<option>Select Leader</option>");
          $("#select-village").html("<option>Select Village</option>");
        }
      });
  }

    //    function to verify NHIF STATUS
    function nhifVerify() {
        //code
    }
</script>


<script src="js/token.js"></script>
<br /><br />



<?php
//    select patient information to perform check in process
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn, "SELECT
                            Old_Registration_Number,Title,Patient_Name,
                                Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,Tribe,
                                                    Emergence_Contact_Number,Company,Registration_ID
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,
                                                        Registration_ID,Patient_Picture
                                      
                                      
                                      
                                      
                                      from tbl_patient_registration pr, tbl_sponsor sp 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                              Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Patient_Picture = $row['Patient_Picture'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = ucwords(strtolower($row['Patient_Name']));
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            $Tribe = $row['Tribe'];
        }
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Guarantor_Name = '';
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
        $Email_Address = '';
        $Occupation = '';
        $Employee_Vote_Number = '';
        $Emergence_Contact_Name = '';
        $Emergence_Contact_Number = '';
        $Company = '';
        $Employee_ID = '';
        $Registration_Date_And_Time = '';
        $Tribe = '';
    }
}
?>





<!--  insert data from the form  -->

<?php
///decompression function
function compress_image($source_url, $destination_url, $quality)
{

    $info = getimagesize($source_url);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source_url);

    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source_url);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source_url);

    imagejpeg($image, $destination_url, $quality);
    return $destination_url;
}
////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['submittedEditPatientForm'])) {

    if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Employee_ID'])) {
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        } else {
            $Employee_ID = 0;
        }
    }

    $Old_Registration_Number = mysqli_real_escape_string($conn, $_POST['Old_Registration_Number']);
    $Patient_Title = mysqli_real_escape_string($conn, $_POST['Patient_Title']);
    $Patient_Name = mysqli_real_escape_string($conn, str_replace("'", "&#39;", $_POST['Patient_Name']));
    $Date_Of_Birth = mysqli_real_escape_string($conn, $_POST['Date_Of_Birth']);
    $Gender = mysqli_real_escape_string($conn, $_POST['Gender']);
    $region = mysqli_real_escape_string($conn, $_POST['region']);
    $District = mysqli_real_escape_string($conn, $_POST['District']);
    $Ward_new = mysqli_real_escape_string($conn, $_POST['ward']);
    $Phone_Number = mysqli_real_escape_string($conn, $_POST['Phone_Number']);
    $Email = mysqli_real_escape_string($conn, $_POST['Email']);
    $Guarantor_Name = mysqli_real_escape_string($conn, $_POST['Guarantor_Name']);
    $Member_Number = mysqli_real_escape_string($conn, $_POST['Member_Number']);
    $Tribe_new = mysqli_real_escape_string($conn, $_POST['Tribe']);
    $Member_Card_Expire_Date = mysqli_real_escape_string($conn, $_POST['Member_Card_Expire_Date']);
    $Occupation = mysqli_real_escape_string($conn, $_POST['Occupation']);
    $Employee_Vote_Number = mysqli_real_escape_string($conn, $_POST['Employee_Vote_Number']);
    $Emergence_Contact_Name = mysqli_real_escape_string($conn, str_replace("'", "&#39;", $_POST['Emergence_Contact_Name']));
    $Emergence_Contact_Number = mysqli_real_escape_string($conn, $_POST['Emergence_Contact_Number']);
    $Company = mysqli_real_escape_string($conn, $_POST['Company']);
    if (isset($_FILES['Patient_Picture']) && $_FILES['Patient_Picture']['name'] != "") {
        $Patient_Picture = md5($_FILES['Patient_Picture']['name'] . date('Ymdhms')) . ".png";
        $Patient_Picture_temp = $_FILES['Patient_Picture']['tmp_name'];
        $Patient_Picture_path = "./patientImages/" . $Patient_Picture;
    } else {
        $Patient_Picture = 'default.png';
    }
    if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Employee_ID'])) {
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        } else {
            $Employee_ID = 0;
        }
    }


    //select patient registration date and time
    $data = mysqli_query($conn, "select now() as Registration_Date_And_Time");
    while ($row = mysqli_fetch_array($data)) {
        $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
    }
    $image_update = "";
    if ($Patient_Picture != 'default.png') {
        $image_update = ",Patient_Picture='$Patient_Picture'";
    }
    $objs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * from tbl_patient_registration where Registration_ID = '$Registration_ID'"));
    $Sponsor_ID = $objs["Sponsor_ID"];
    $old_name = str_replace("'", "&#39;", preg_replace('/\s+/', ' ', $objs["Patient_Name"]));
    $Sponsor_new_ID = mysqli_fetch_assoc(mysqli_query($conn, "select Sponsor_ID from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'"))["Sponsor_ID"];
    if ($Sponsor_ID != $Sponsor_new_ID) {
        $sql2 = "INSERT INTO tbl_patient_edit(Registration_ID,Old_name,Sponsor_ID,Employee_ID,Old_sponsor)
         VALUES('$Registration_ID','$old_name','$Sponsor_new_ID','$Employee_ID','$Sponsor_ID')";
        $resultLOg = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
    } else {
        $sql2 = "INSERT INTO tbl_patient_edit(Registration_ID,Old_name,Sponsor_ID,Employee_ID,Old_sponsor)
         VALUES('$Registration_ID','$old_name','$Sponsor_new_ID','$Employee_ID','$Sponsor_ID')";
        $resultLOg = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
    }

    //    die($image_update);
    $Update_Sql = "UPDATE tbl_patient_registration set
                    Old_Registration_Number = '$Old_Registration_Number',Title = '$Patient_Title',Patient_Name = '$Patient_Name',
                        Date_Of_Birth = '$Date_Of_Birth',Gender = '$Gender',Region = '$region',District = '$District',Ward = '$Ward_new', 
				    Phone_Number = '$Phone_Number',Email_Address = '$Email',Occupation = '$Occupation', Tribe = '$Tribe_new',
					Sponsor_ID = (select Sponsor_ID from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'),
					    Member_Number = '$Member_Number',Member_Card_Expire_Date = '$Member_Card_Expire_Date',
					        Employee_Vote_Number = '$Employee_Vote_Number',Emergence_Contact_Name = '$Emergence_Contact_Name',
						    Emergence_Contact_Number = '$Emergence_Contact_Number',Company = '$Company' $image_update where Registration_ID = '$Registration_ID'";
    if (!mysqli_query($conn, $Update_Sql)) {
        die(mysqli_error($conn));
        die(mysqli_error($conn));

        echo "<script type='text/javascript'>
                    alert('Process Fail! Please Try Again');
                    </script>";
    } else {
        if ($Patient_Picture != 'default.png') {
            //            move_uploaded_file($Patient_Picture_temp, $Patient_Picture_path);
            $sql_select_image_quality_result = mysqli_query($conn, "SELECT image_quality_value FROM tbl_image_quality LIMIT 1") or die(mysqli_error($conn));
            if (mysqli_num_rows($sql_select_image_quality_result) > 0) {
                $image_quality = mysqli_fetch_assoc($sql_select_image_quality_result)['image_quality_value'];
            } else {
                $image_quality = 10;
            }
            compress_image($Patient_Picture_temp, $Patient_Picture_path, $image_quality);
        }
        echo "<script type='text/javascript'>
                    alert('PARTIENT EDITED SUCCESSFULLY');
                    document.location = 'visitorform.php?Registration_ID=" . $Registration_ID . "&VisitorFormPatient=VisitorFormPatientThisPage';
                    </script>";
        //call the function to log the action
        $Activity_Date_And_Time = date('Y-m-d H:i:s');
        activity_log($Employee_ID, $Activity_Date_And_Time, "Edited Patient Information.");
    }
}
?>

<fieldset>
    <legend align=right><b>EDIT PATIENT</b></legend>
    <center>
        <table width=100%>
            <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                <tr>
                    <td width=35%>
                        <table width=100%>
                            <tr>
                                <td style="text-align:right;">New Regiatration Number</td>
                                <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Old Registration Number</td>
                                <td><input type='text' name='Old_Registration_Number' id='Old_Registration_Number' value='<?php echo $Old_Registration_Number; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Title</td>
                                <td>
                                    <select name='Patient_Title' id='Patient_Title'>
                                        <option selected='selected'><?php echo $Title; ?></option>
                                        <option>Mr</option>
                                        <option>Mrs</option>
                                        <option>Miss</option>
                                        <option>Master</option>
                                        <option>Dr</option>
                                        <option>Prof</option>
                                        <option>Ms</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Patient Name</td>
                                <td><input type='text' name='Patient_Name' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Date Of Birth</td>
                                <td><input type='text' name='Date_Of_Birth' id='date2' onchange='check_date()' readonly="readonly" required='required' value='<?php echo $Date_Of_Birth; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Gender</td>
                                <td>
                                    <select name='Gender' id='Gender'>
                                        <?php
                                        if (strtolower($Gender) == 'male') {
                                            echo "<option selected='selected'>Male</option>";
                                            echo "<option>Female</option>";
                                        } else {
                                            echo "<option selected='selected'>Female</option>";
                                            echo "<option>Male</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Occupation</td>
                                <td><input type='text' name='Occupation' id='Occupation' value='<?php echo $Occupation; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Employee Vote Number</td>
                                <td><input type='text' name='Employee_Vote_Number' id='Employee_Vote_Number' value='<?php echo $Employee_Vote_Number; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Company</td>
                                <td><input type='text' name='Company' id='Company' value='<?php echo $Company; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Patient Phone Number</td>
                                <td><input type='text' name='Phone_Number' id='Phone_Number' value='<?php echo $Phone_Number; ?>'></td>
                            </tr>
                        </table>
                    </td>
                    <td width=35%>
                        <table width=100%>
                            <tr>
                                <td style="text-align:right;">Region</td>
                                <td>
                                    <select name='region' id='region' onchange="getDistricts()">
                                        <option selected='selected' value='<?php echo $Region; ?>'><?php echo $Region; ?></option>
                                        <?php
                                        $data = mysqli_query($conn, "SELECT * from tbl_regions ORDER BY Region_Name ASC");
                                        while ($row = mysqli_fetch_array($data)) {
                                        ?>
                                            <option value='<?php echo $row['Region_Name']; ?>'>
                                                <?php echo $row['Region_Name']; ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">District</td>
                                <td>
                                    <select name='District' id='District' onchange="getWards()">

                                        <option selected='selected' value='<?php echo $District; ?>'><?php echo $District; ?></option>
                                        <?php
                                        $data = mysqli_query($conn, "SELECT District_Name from tbl_district WHERE Region_ID IN(SELECT Region_ID FROM tbl_regions WHERE Region_Status = 'Selected') ORDER BY District_Name ASC");
                                        while ($row = mysqli_fetch_array($data)) {
                                        ?>
                                            <option value='<?php echo $row['District_Name']; ?>'>
                                                <?php echo $row['District_Name']; ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Ward</td>
                                <td>
                                    <!-- <input type='text' name='Ward' id='Ward' value=''> -->
                                    <select id="ward" name="ward" style="width:60%;" required>
                                        <?php $ward_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_ward WHERE Ward_ID='$Ward'"))['Ward_Name']; ?>
                                        <option selected="selected" value="<?php echo $Ward; ?>"> <?php echo $ward_name; ?>
                                        <option>
                                            <?php
                                            $select_ward = mysqli_query($conn, "SELECT * FROM tbl_ward ") or die(mysqli_error($conn));
                                            while ($row = mysqli_fetch_assoc($select_ward)) {
                                                $ward_ID = $row['Ward_ID'];
                                                $Ward_Name = $row['Ward_Name'];
                                                echo "<option value='$ward_ID'>$Ward_Name</option>";
                                            }
                                            ?>
                                        <option value='others'>Others</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Sponsor</td>
                                <?php if (strtolower($_SESSION['userinfo']['Modify_Credit_Information']) == 'yes' && strtolower($_SESSION['userinfo']['Modify_Cash_information']) == 'yes' && strtolower($_SESSION['userinfo']['Session_Master_Priveleges']) == 'yes') { ?>
                                    <td>
                                        <select name='Guarantor_Name' id='Guarantor_Name' onchange='check_if_is_nhif()' required='required'>
                                            <option selected='selected'><?php echo $Guarantor_Name; ?></option>
                                            <?php
                                            $data = mysqli_query($conn, "select * from tbl_sponsor where Guarantor_Name <> '$Guarantor_Name'");
                                            while ($row = mysqli_fetch_array($data)) {
                                                echo '<option>' . $row['Guarantor_Name'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </td>
                                <?php } elseif (strtolower($_SESSION['userinfo']['Edit_Patient_Information']) == 'yes') { ?>
                                    <td>

                                        <?php

                                        echo "<select name='Guarantor_Name' id='Guarantor_Name' onchange='check_if_is_nhif()' required='required'><option selected='selected'>$Guarantor_Name</option>";
                                        $data = mysqli_query($conn, "select * from tbl_sponsor where Guarantor_Name <> '$Guarantor_Name'");
                                        while ($row = mysqli_fetch_array($data)) {
                                            echo '<option>' . $row['Guarantor_Name'] . '</option>';
                                        }

                                        echo "</select>";

                                        ?>


                                    </td>

                                <?php } else { ?>
                                    <td>
                                        <select name='Guarantor_Name' id='Guarantor_Name' required='required'>
                                            <option selected='selected'><?php echo $Guarantor_Name; ?></option>
                                        </select>
                                    </td>
                                <?php } ?>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Member Number</td>
                                <?php if (strtolower($_SESSION['userinfo']['Modify_Credit_Information']) == 'yes') { ?>
                                    <td><input type='text' name='Member_Number' onkeyup="wekaMemberNo(this)" id='Member_Number' <?= $required ?> value='<?php echo $Member_Number; ?>'></td>
                                <?php } elseif ($Member_Number == '' || $Member_Number == NULL) { ?>
                                    <?php
                                    if ($Guarantor_Name == 'CASH') {
                                        echo "<td><input type='text' disabled='true' onkeyup='wekaMemberNo(this)' name='Member_Number' id='Member_Number' value=''></td>";
                                    } else {

                                        echo " <td><input type='text' name='Member_Number' onkeyup='wekaMemberNo(this)'  id='Member_Number' value='$Member_Number'></td>";
                                    }
                                    ?>

                                <?php } elseif (strtolower($_SESSION['userinfo']['Edit_Patient_Information']) == 'yes') { ?>

                                    <?php
                                    if ($Guarantor_Name == 'CASH') {
                                        echo "<td><input type='text' disabled='true' name='Member_Number' onkeyup='wekaMemberNo(this)' id='Member_Number' value=''></td>";
                                    } else {
                                        echo " <td><input type='text' name='Member_Number' onkeyup='wekaMemberNo(this)' id='Member_Number' value='$Member_Number'></td>";
                                    }
                                    ?>

                                <?php } else { ?>
                                    <td>
                                        <input type='text' disabled='disabled' value='<?php echo $Member_Number; ?>'>
                                        <input type='hidden' name='Member_Number' onkeyup='wekaMemberNo(this)' id='Member_Number' value='<?php echo $Member_Number; ?>'>
                                    </td>
                                <?php } ?>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <input type="button" value="NHIF-eVerify" id='eVerify_btn' onclick="call_nhif();" class="art-button-green" style="text-align: right;visibility: hidden;" />
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Member Card Expire Date</td>
                                <?php if (strtolower($_SESSION['userinfo']['Modify_Credit_Information']) == 'yes') { ?>
                                    <td><input type='text' name='Member_Card_Expire_Date' id='date' value='<?php echo $Member_Card_Expire_Date; ?>'></td>
                                <?php } elseif (strtolower($_SESSION['userinfo']['Edit_Patient_Information']) == 'yes') { ?>

                                    <?php
                                    if ($Guarantor_Name == 'CASH') {
                                        echo "<td><input type='text' disabled='disabled' value=''></td>";
                                    } else {
                                        echo "  <td><input type='text' name='Member_Card_Expire_Date' id='date' value='$Member_Card_Expire_Date'></td></td>";
                                    }
                                    ?>

                                <?php } else { ?>
                                    <td>
                                        <input type='text' disabled='disabled' value='<?php echo $Member_Card_Expire_Date; ?>'>
                                        <input type='hidden' name='Member_Card_Expire_Date' id='date' value='<?php echo $Member_Card_Expire_Date; ?>'>
                                    </td>
                                <?php } ?>
                            </tr>


                            <tr>
                                <td style="text-align:right;">E Mail</td>
                                <td><input type='email' name='Email' id='Email' value='<?php echo $Email_Address; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Relative Contact Name</td>
                                <td><input type='text' name='Emergence_Contact_Name' id='Emergence_Contact_Name' value='<?php echo $Emergence_Contact_Name; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Relative Contact Number</td>
                                <td><input type='text' name='Emergence_Contact_Number' id='Emergence_Contact_Number' value='<?php echo $Emergence_Contact_Number; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Tribe</td>
                                <td>
                                    <select name="Tribe" id="Tribe">
                                        <?php
                                        $pat_Tribe = mysqli_fetch_assoc(mysqli_query($conn, "SELECT tribe_name FROM tbl_tribe WHERE tribe_id = '$Tribe'"))['tribe_name'];
                                        ?>
                                        <option value=<?php echo $Tribe; ?> selected><?php echo $pat_Tribe; ?></option>
                                        <?php
                                        $select_ward = mysqli_query($conn, "SELECT * FROM tbl_tribe ") or die(mysqli_error($conn));
                                        while ($row = mysqli_fetch_assoc($select_ward)) {
                                            $tribe_name = $row['tribe_name'];
                                            $tribe_id = $row['tribe_id'];

                                            echo "<option value='$tribe_id'>$tribe_name</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td id="labelUmustAuth" style='text-align: right; width:12%;'><span style="color: red; font-size: 22px;"><b></b></span></td>
                                <td width="17%">
                                    <table width="100%" cellpadding="0" cellspacing="0" style="margin:0px; border:0px;" height="80%">
                                        <tr>

                                            <?php
                                            $from_afya_cardMember_Number = "";
                                            if (isset($_GET['from_afya_cardMember_Number'])) {
                                                $from_afya_cardMember_Number = mysqli_real_escape_string($conn, $_GET['from_afya_cardMember_Number']);
                                            }
                                            ?>

                                            <?php
                                            //if(empty($from_afya_cardMember_Number)){
                                            ?>
                                            <!-- <td><input type='text' onkeyup='wekaMemberNo(this)' name='Member_Number' disabled='disabled' onkeyup='wekaMemberNo(this)' id='Member_Number' value='<?php echo $Member_Number; ?>'> -->
                                            <?php
                                            // }else{

                                            ?>

                                            <?php
                                            //}
                                            ?>





                                            <!--
                                        <td width="40">
                                            <?php
                                            if (isset($_GET['Registration_ID'])) {
                                                if ((strpos(strtolower($Guarantor_Name), 'nhif')) !== false) {
                                            ?>
                                                    <input type="button" value="NHIF-Authorize" onclick="authorizeNHIF('<?php echo $Member_Number; ?>');" class="art-button-green" />
                                                    <?php

                                                }
                                            }
                                                    ?>
                                        </td> -->
                                            <?php

                                            $nhif_server_configuration = mysqli_query($conn, "SELECT configvalue FROM tbl_config WHERE configname='NhifApiConfiguration'") or die(mysqli_error($conn));
                                            if (mysqli_num_rows($nhif_server_configuration) > 0) {
                                                $nhif_server_configuration = mysqli_fetch_assoc($nhif_server_configuration)['configvalue'];

                                                $sql_select_external_nhif_server_url_result = mysqli_query($conn, "SELECT configvalue FROM tbl_config WHERE configname='NhifExternalServerUrl'") or die(mysqli_error($conn));
                                                $extenal_nhif_server_url = mysqli_fetch_assoc($sql_select_external_nhif_server_url_result)['configvalue'];
                                                if ($nhif_server_configuration == "singleserver") {
                                                    $extenal_nhif_server_url = "";
                                                }
                                            }
                                            ?>
                                            <td width="20">
                                                <?php
                                                if (isset($_GET['Registration_ID'])) {
                                                ?>
                                                    <input type="button" id="NHIF_Authorize" value="NHIF-Authorize" onclick="iiteNHIF()" class="btn btn-primary btn-xs" />
                                                <?php
                                                    // }
                                                }
                                                ?>
                                            </td>

                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <?php
                            if (isset($_SESSION['userinfo']['Employee_Name'])) {
                                $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
                            } else {
                                $Employee_Name = "Unknown Employee";
                            }
                            ?>
                        </table>
                    </td>
                    <td width=30%>
                        <table width=100%>
                            <tr>
                                <td style='text-align: center;'>Patient Picture</td>
                            </tr>
                            <tr>
                                <td id='Patient_Picture_td' style='text-align: center;'>
                                    <img src='./patientImages/<?= $Patient_Picture ?>' id='Patient_Picture' name='Patient_Picture' width=50% height=50%>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    SELECT PICTURE
                                    <input type="file" name="Patient_Picture" id="file" onchange='readImage(this)' title='SELECT PATIENT PICTURE' />
                                </td>
                            </tr>
                            <tr>
                                <td colspan=2 style='text-align: right;'>
                                    <input type='submit' name='submit' id='submit' value='   UPDATE   ' class='art-button-green'>
                                    <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green' onclick='clearPatientPicture()'>
                                    <input type='hidden' name='submittedEditPatientForm' value='true' />
                                    <input type='hidden' name='hidden_nhif' value='' />
                                    <input type='hidden' name='hidden_card_number' id='hidden_card_number' value="0">
                                </td>
                                <!-- section 1 -->
                                <td>
                                    <input type="hidden" name="select_package2" id="select_package2" value="" />
                                </td>
                            </tr>
                        </table>
                    </td>
            </form>
            </tr>
        </table>
    </center>
</fieldset>

<fieldset>
    <legend align=center><b>PATIENT'S VERIFIED SPONSOR DETAILS</b></legend>
    <center>
        <div class="msg" style='color:red;font-weight:  bold'></div>

        <?php
        $sql = mysqli_query($conn, "SELECT configvalue FROM tbl_config WHERE configname='NhifAuthorization'");
        $write_auth_num = mysqli_fetch_assoc($sql)['configvalue'];
        $readyonly = "";
        if ($write_auth_num == 'Yes') {
            $readyonly = "readonly";
        }
        ?>
        <div align="center" style="display:none" id="verifyprogress"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
        <table width=90%>
            <tr>
                <td style="text-align: right;" width='14%'>Authorization Status</td>
                <td><input type='text' name='CardStatus' readonly="readonly" id='CardStatus' value=''></td>
                <td style='text-align: right'>Authorization Number</td>
                <td><input type='text' name='AuthorizationNo' id='AuthorizationNo' <?= $readyonly; ?>></td>
            </tr>
            <tr>
                <td style='text-align: right'>Remarks</td>
                <td colspan=3><textarea name="Remarks" rows="1" id="Remarks" readonly="readonly" style='resize: none;'></textarea></td>
            </tr>
        </table>
    </center>
</fieldset>
<?php
include("./includes/footer.php");
?>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script type="text/javascript" src="js/ecr_pmnt.js"></script>
<script>


    function check_date(){
        Date_Of_Birth = $("#date2").val();
        today = '<?= $Today ?>';
        // Leo = today.setHours(0,0,0,0);
        // alert(Date_Of_Birth);
        Data = '<?= $Date_Of_Birth ?>';
        if(Date_Of_Birth > today){
            alert("The Birth Date can not be Greater than Today");
            document.getElementById("date2").value = Data;
            $("#date2").css("border","2px solid red");
            // $("#date2").attr("placeholder","Please Enter Correct Birth Date");
            $("#date2").focus();
            // $("#date2").val() = Data;
        }
    }

    $('#Guarantor_Name').on('change', function() {
        var sponsor = $(this).val();
        if (sponsor == 'CASH') {
            $('#date,#Member_Number').prop('disabled', true);
        } else {
            $('#date,#Member_Number').prop('disabled', false);
        }
    });

    // section 1
    function check_if_is_nhif() {
        var Guarantor_Name = $("#Guarantor_Name").val();

        if (Guarantor_Name == "NHIF") {
            // alert("Itz okay..!");

            document.getElementById("Member_Number").focus();
            document.getElementById('eVerify_btn').style.visibility = "visible";
            // document.getElementById('NHIF_Authorize').style.visibility = "hidden";
            document.getElementById('submit').style.visibility = "hidden";
            $("#Member_Number").prop('disabled', false);
            $("#NHIF_Authorize").prop('disabled', true);
        } else {
            $("#NHIF_Authorize").prop('disabled', true);
        }
    }

    // section 2
    function iiteNHIF() {
        var Member_Number = $("#hidden_card_number").val();
        var extenal_nhif_server_url = "<?php echo $extenal_nhif_server_url; ?>";
        var Guarantor_Name = $("#Guarantor_Name").val();
        authorizeNHIF(Member_Number, extenal_nhif_server_url);

        setTimeout(function() {
            var CardStatus = $("#CardStatus").val();

            if (CardStatus === "ACCEPTED") {
                document.getElementById('submit').style.visibility = "";
                document.getElementById('verifyprogress').style.visibility = "hidden";

                var AuthorizationNo = $("#AuthorizationNo").val();
                var Registration_ID = "<?php echo $Registration_ID; ?>";
                // section 2
                var ProductCode = document.getElementById('select_package2').value;
                $.ajax({
                    type: 'POST',
                    url: 'update_authorization_no.php',
                    data: {
                        AuthorizationNo: AuthorizationNo,
                        Registration_ID: Registration_ID,
                        ProductCode: ProductCode, 
                        Guarantor_Name:Guarantor_Name
                        
                    },
                    success: function(response) {
                        if (response == 1) {
                            console.log("Today amekuwa checked in");
                        } else {
                            console.log("Today hajawa checked in");
                        }

                        document.getElementById('submit').style.visibility = "visible";
                    }
                });

            } else {
                alert("Error during Authorization..!");
                document.getElementById('verifyprogress').style.visibility = "hidden";
            }

        }, 7000);
    }

    // section 3
    function wekaMemberNo(element) {
        var Member_Number = $(element).val();
        // alert(Member_Number);
        document.getElementById("hidden_card_number").value = Member_Number;
    }

    // section 4
    function call_nhif() {
        var extenal_nhif_server_url = "<?php echo $extenal_nhif_server_url; ?>";
        verifyNHIF3(extenal_nhif_server_url);

        var Registration_ID = "<?php echo $Registration_ID; ?>";
        var Guarantor_Name = $("#Guarantor_Name").val();
        $.ajax({
            type: 'POST',
            url: 'update_authorization_no.php',
            data: {
                Registration_ID: Registration_ID, Guarantor_Name:Guarantor_Name
            },
            success: function(response) {
                if (response == 1) {
                    console.log("Today amekuwa checked in");
                    $("#NHIF_Authorize").prop('disabled', false);
                } else {
                    $("#NHIF_Authorize").prop('disabled', false);
                    $("#submit").prop('disabled', false);
                    document.getElementById('submit').style.visibility = "hidden";
                    console.log("Today hajawa checked in");
                }
            }
        });
    }

    $(document).ready(function() {
        // $("#Member_Number").prop('disabled', true);
        $("#NHIF_Authorize").prop('disabled', true);
        $("#District").select2();
        $("#region").select2();
        $("#ward").select2();
    });
</script>