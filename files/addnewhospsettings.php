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

<br/><br/><br/><br/>
<?php
if (isset($_POST['submittedAddNewHospSettingForm'])) {
$target_dir = "../images/image-slider-1.png";
$imageFileType = pathinfo( basename($_FILES["banner"]["name"]),PATHINFO_EXTENSION);
//echo $imageFileType;
//exit;
$target_file = $target_dir;
//echo $target_file;
//exit;
$uploadOk = 1;

// Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["banner"]["tmp_name"]);
    if($check !== false) {
//        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
//        echo "File is not an image.";
        $uploadOk = 0;
    }
// Check file size
if ($_FILES["banner"]["size"] > 500000) {
//    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if(strtolower($imageFileType) != "jpg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpeg"
&& strtolower($imageFileType) != "gif" ) {
//    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
//    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["banner"]["tmp_name"], $target_file)) {
//        echo "The file ". basename( $_FILES["banner"]["name"]). " has been uploaded.";
    } else {
//        echo "Sorry, there was an error uploading your file.";
    }
}  
    
//Upload logo.....................................................................................
$target_dir_logo = "branchBanner/branchBanner1.png";
$imageFileType_logo = pathinfo( basename($_FILES["logo"]["name"]),PATHINFO_EXTENSION);
//echo $imageFileType;
//exit;
$target_file_logo = $target_dir_logo;
//echo $target_file;
//exit;
$uploadOk = 1;

// Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["logo"]["tmp_name"]);
    if($check !== false) {
//        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
//        echo "File is not an image.";
        $uploadOk = 0;
    }

// Check file size
if ($_FILES["banner"]["size"] > 500000) {
//    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if(strtolower($imageFileType_logo) != "jpg" && strtolower($imageFileType_logo) != "png" && strtolower($imageFileType_logo) != "jpeg"
&& strtolower($imageFileType_logo) != "gif" ) {
//    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
//    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file_logo)) {
//        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
//        echo "Sorry, there was an error uploading your file.";
    }
}  

    $hospital_Name = mysqli_real_escape_string($conn,trim($_POST['hospital_Name']));
    $box_Address = mysqli_real_escape_string($conn,trim($_POST['box_Address']));
    $tel_phone = mysqli_real_escape_string($conn,trim($_POST['tel_phone']));
    $cell_phone = mysqli_real_escape_string($conn,trim($_POST['cell_phone']));
    $fax = mysqli_real_escape_string($conn,trim($_POST['fax']));
    $tin = mysqli_real_escape_string($conn,trim($_POST['tin']));

    $check = mysqli_query($conn,"SELECT Branch_ID FROM tbl_system_configuration WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'");


    if (mysqli_num_rows($check) > 0) {
        $sql = "UPDATE tbl_system_configuration SET "
                . "Hospital_Name='$hospital_Name',"
                . "Box_Address='$box_Address',Telephone='$tel_phone',"
                . "Cell_Phone='$cell_phone',Fax='$fax',"
                . "Tin='$tin' WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'";
        // echo $sql;
        $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
       if ($result) {
            ?>
            <script type="text/javascript">
                alert('INFORMATION SAVED SUCCESSIFULLY');
                document.location="addnewhospsettings.php";
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
    } else {
        $sql = "INSERT INTO tbl_system_configuration "
                . "(Hospital_Name,Box_Address,Telephone,Cell_Phone,Fax,Tin)"
                . " VALUES('$hospital_Name','$box_Address','$tel_phone','$cell_phone','$fax','$tin')";
        $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
        if ($result) {
            ?>
            <script type="text/javascript">
                alert('INFORMATION SAVED SUCCESSIFULLY');
                document.location="addnewhospsettings.php";
            </script>
            <?php
            
        } else {
            ?>
            <script type="text/javascript">
                alert('AN ERROR HAS OCCURED DURING PROCESSING OF INFORMATION. PLEASE TRY AGAIN LATER.');
            </script>
            <?php
        }
    }
}
?>

<br/><br/>
<center>
    <table width=60%><tr><td>
        <center>
            <fieldset>
                <legend align="center" ><b>ADD NEW SETTINGS</b></legend>
                <table>
                    <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">

                        <tr>
                            <td width=40% style='text-align: right;'><b>Hospital Name</b></td>
                            <td width=80%><input type='text' name='hospital_Name' required='required' size=70 id='hospital_Name' placeholder='Enter Hospital Name'></td>
                        </tr>
                        <tr>
                            <td width=40% style='text-align: right;'><b>P.O Box</b></td>
                            <td width=80%>
                                <input type="text" name='box_Address' id='box_Address' placeholder='Enter Box Address' required="required" />
                            </td>
                        </tr>
                        <tr>
                            <td width=40% style='text-align: right;'><b>Telephone</b></td>
                            <td width=80%>
                                <input type="tel" name='tel_phone' id='tel_phone' placeholder='Enter Telephone Number' />
                            </td>
                        </tr>
                        <tr>
                            <td width=40% style='text-align: right;'><b>Cell Phone</b></td>
                            <td width=80%>
                                <input type="tel" name='cell_phone' id='cell_phone' placeholder='Enter Cell Phone Number' />
                            </td>
                        </tr>
                        <tr>
                            <td width=40% style='text-align: right;'><b>Fax</b></td>
                            <td width=80%>
                                <input type="text" name='fax' id='fax' placeholder='Enter Fax Number'  />
                            </td>
                        </tr>
                        <tr>
                            <td width=40% style='text-align: right;'><b>TIN</b></td>
                            <td width=80%>
                                <input type="text" name='tin' id='tin' placeholder='Enter Tin Number' />
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
			                            <td colspan=2 style='text-align: right;'>            
						                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                <input type='hidden' name='submittedAddNewHospSettingForm' value='true'/> 
                            </td>
                        </tr>
                    </form></table>
            </fieldset>
        </center></td></tr></table>
</center>


<?php
include("./includes/footer.php");
?>