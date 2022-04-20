<?php
include("./includes/connection.php");
include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
        if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<a href='hosppitalsettingspage.php?hospitalconsultationConfigurations=hospitalconsultationConfigurationsThisForm' class='art-button-green'> 
    BACK
</a>

<br/><br/><br/><br/><br/><br/>

<?php
if (isset($_POST['submittedEditHospSettingForm'])) {
    $target_dir = "../images/image-slider-1.png";
    $imageFileType = pathinfo(basename($_FILES["banner"]["name"]), PATHINFO_EXTENSION);
//echo $imageFileType;
//exit;
    $target_file = $target_dir;
//echo $target_file;
//exit;
    $uploadOk = 1;

// Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["banner"]["tmp_name"]);
    if ($check !== false) {
//        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
//        echo "File is not an image.";
        $uploadOk = 0;
    }

// Check if file already exists
//if (file_exists($target_file)) {
//    echo "Sorry, file already exists.";
//    $uploadOk = 0;
//}
// Check file size
    if ($_FILES["banner"]["size"] > 500000) {
//        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
// Allow certain file formats
    if (strtolower($imageFileType) != "jpg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpeg" && strtolower($imageFileType) != "gif") {
//        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
//        echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["banner"]["tmp_name"], $target_file)) {
//            echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
        } else {
//            echo "Sorry, there was an error uploading your file.";
        }
    }

    //Upload logo to be used in reports and receipts.....................................................................................
    $target_dir_logo = "branchBanner/branchBanner.png";
    $target_dir_logo1 = "branchBanner/branchBanner1.png";
    $imageFileType_logo = pathinfo(basename($_FILES["logo"]["name"]), PATHINFO_EXTENSION);
    $target_file_logo = $target_dir_logo;
    $uploadOk = 1;

// Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["logo"]["tmp_name"]);
    if ($check !== false) {
//        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
//        echo "File is not an image.";
        $uploadOk = 0;
    }

// Check file size
    if ($_FILES["logo"]["size"] > 500000) {
//        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
// Allow certain file formats
    if (strtolower($imageFileType_logo) != "jpg" && strtolower($imageFileType_logo) != "png" && strtolower($imageFileType_logo) != "jpeg" && strtolower($imageFileType_logo) != "gif") {
//        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
//        echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
    } else {
        //move_uploaded_file($_FILES["logo"]["tmp_name"], $target_dir_logo1);
        if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file_logo)) {
            
//            echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
        } else {
//            echo "Sorry, there was an error uploading your file.";
        }
    }

    $hospital_Name = mysqli_real_escape_string($conn,trim($_POST['hospital_Name']));
    $box_Address = mysqli_real_escape_string($conn,trim($_POST['box_Address']));
    $tel_phone = mysqli_real_escape_string($conn,trim($_POST['tel_phone']));
    $cell_phone = mysqli_real_escape_string($conn,trim($_POST['cell_phone']));
    $fax = mysqli_real_escape_string($conn,trim($_POST['fax']));
    $tin = mysqli_real_escape_string($conn,trim($_POST['tin']));
    $hospital_id = mysqli_real_escape_string($conn,trim($_POST['hospital_id']));
    $nhif_username = mysqli_real_escape_string($conn,trim($_POST['nhif_username']));
    $facility_code = mysqli_real_escape_string($conn,trim($_POST['facility_code']));

    $nhif_base_url = mysqli_real_escape_string($conn,trim($_POST['nhif_base_url']));
    $private_key = mysqli_real_escape_string($conn,trim($_POST['private_key']));
    $public_key = mysqli_real_escape_string($conn,trim($_POST['public_key']));

    $sql = "UPDATE tbl_system_configuration SET "
            . "Hospital_Name='$hospital_Name',"
            . "Box_Address='$box_Address',Telephone='$tel_phone',"
            . "Cell_Phone='$cell_phone',Fax='$fax',"
            . "nhif_username='$nhif_username' , facility_code='$facility_code', "
            . "nhif_base_url='$nhif_base_url',private_key='$private_key',public_key='$public_key',"
            . "Tin='$tin',"
            . "hospital_id='$hospital_id' WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'";
    // echo $sql;
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if ($result) {
        ?>
        <script type="text/javascript">
            alert('INFORMATION UPDATED SUCCESSIFULLY');
            document.location = "edithospsettings.php";
        </script>
        <?php
        //header("location:addnewhospsettings.php");
    } else {
        ?>
        <script type="text/javascript">
            alert('AN ERROR HAS OCCURED DURING PROCESSING OF INFORMATION. PLEASE TRY AGAIN LATER.');
        </script>
        <?php
    }
}

$retrieve = mysqli_query($conn,"SELECT Hospital_Name,Box_Address,Telephone,Cell_Phone,Fax,Tin,hospital_id,nhif_base_url,private_key,public_key,facility_code,nhif_username FROM tbl_system_configuration WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'");
$data = mysqli_fetch_assoc($retrieve);

$hospital_Name = $data['Hospital_Name'];
$box_Address = $data['Box_Address'];
$tel_phone = $data['Telephone'];
$cell_phone = $data['Cell_Phone'];
$fax = $data['Fax'];
$tin = $data['Tin'];
$hospital_id = $data['hospital_id'];
//nhif consfiguration data private_key public_key
$nhif_base_url = $data['nhif_base_url'];
$private_key = $data['private_key'];
$public_key = $data['public_key'];
$facility_code = $data['facility_code'];
$nhif_username = $data['nhif_username'];
?>

<center>
    <table width=60%><tr><td>
        <center>
            <fieldset>
                <legend align="center" ><b>ADD NEW SETTINGS</b></legend>
                <table>
                    <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">

                        <tr>
                            <td width=40% style='text-align: right;'><b>Hospital Name</b></td>
                            <td width=80%><input type='text' name='hospital_Name' required='required' size=70 id='hospital_Name' value="<?php echo $hospital_Name ?>" placeholder='Enter Hospital Name'></td>
                        </tr>
                        <tr>
                            <td width=40% style='text-align: right;'><b>P.O Box</b></td>
                            <td width=80%>
                                <input type="text" name='box_Address' id='box_Address' value="<?php echo $box_Address ?>" placeholder='Enter Box Address' required="required" />
                            </td>
                        </tr>
                        <tr>
                            <td width=40% style='text-align: right;'><b>Telephone</b></td>
                            <td width=80%>
                                <input type="tel" name='tel_phone' id='tel_phone' value="<?php echo $tel_phone ?>" placeholder='Enter Telephone Number' />
                            </td>
                        </tr>
                        <tr>
                            <td width=40% style='text-align: right;'><b>Cell Phone</b></td>
                            <td width=80%>
                                <input type="tel" name='cell_phone' id='cell_phone' value="<?php echo $cell_phone ?>" placeholder='Enter Cell Phone Number' />
                            </td>
                        </tr>
                        <tr>
                            <td width=40% style='text-align: right;'><b>Fax</b></td>
                            <td width=80%>
                                <input type="text" name='fax' id='fax' value="<?php echo $fax ?>" placeholder='Enter Fax Number'  />
                            </td>
                        </tr>
                        <tr>
                            <td width=40% style='text-align: right;'><b>TIN</b></td>
                            <td width=80%>
                                <input type="text" name='tin' id='tin' value="<?php echo $tin ?>" placeholder='Enter Tin Number' />
                            </td>
                        </tr>
                        <tr>
                            <td width=40% style='text-align: right;'><b>Hospital Banner</b></td>
                            <td width=80%>
                                <input type="file" name='banner' id='banner'  />
                            </td>
                        </tr> 
                        <tr>
                            <td width=40% style='text-align: right;'><b>Hospital Logo</b></td>
                            <td width=80%>
                                <input type="file" name='logo' id='logo'  />
                            </td>
                        </tr>                     
                        <tr>
                            <td width=40% style='text-align: right;'><b>Hospital ID</b><i> &nbsp;&nbsp;( Given by GPITG )</i></td>
                            <td width=80%>
                                <input type="text" name='hospital_id' id='hospital_id' value="<?php echo $hospital_id ?>" placeholder='Enter Hospital Number' />
                            </td>
                        </tr>
                        <tr style="background:#cafcce;"> 
                            <td colspan="2" style="text-align:center;">
                                <b>NHIF CONFIGURATION</b>
                            </td>
                        </tr>
                        <tr>
                            <td width=40% style='text-align: right;'><b>NHIF Base URL</b></td>
                            <td width=80%>
                                <input type="text" name='nhif_base_url' id='nhif_base_url' value="<?php echo $nhif_base_url ?>" placeholder='Enter NHIF Base URL' />
                            </td>
                        </tr>
                        <tr>
                            <td width=40% style='text-align: right;'><b>Private Key</b></td>
                            <td width=80%>
                                <input type="text" name='private_key' id='private_key' value="<?php echo $private_key ?>" placeholder='Enter Private Key' />
                            </td>
                        </tr>
                        <tr>
                            <td width=40% style='text-align: right;'><b>Public Key</b></td>
                            <td width=80%>
                                <input type="text" name='public_key' id='public_key' value="<?php echo $public_key ?>" placeholder='Enter Public Key' />
                            </td>
                        </tr>
                        <tr>
                            <td width=40% style='text-align: right;'><b>User Name</b></td>
                            <td width=80%>
                                <input type="text" name='nhif_username' id='mhif_username' value="<?php echo $nhif_username ?>" placeholder='Enter NHIF Username' />
                            </td>
                        </tr>
                        <tr>
                            <td width=40% style='text-align: right;'><b>Facility Code</b></td>
                            <td width=80%>
                                <input type="text" name='facility_code' id='facility_code' value="<?php echo $facility_code ?>" placeholder='Enter Facility Code' />
                            </td>
                        </tr>
                        <tr>
                            <td colspan=2 style='text-align: center;'>
                                <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                <input type='hidden' name='submittedEditHospSettingForm' value='true'/> 
                            </td>
                        </tr>
                    </form></table>
            </fieldset>
        </center></td></tr>
    </table>
</center>


<?php
include("./includes/footer.php");
?>

