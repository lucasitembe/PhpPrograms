<?php
session_start();
include("../includes/connection.php");
//get all item details based on item id

if (isset($_GET['RI'])) {
    $Registration_ID = $_GET['RI'];
} else {
    $Registration_ID = '';
}
if (isset($_GET['PPILI'])) {
    $Patient_Payment_Item_List_ID = $_GET['PPILI'];
} else {
    $Patient_Payment_Item_List_ID = '';
}

if (isset($_GET['PPI'])) {
    $Patient_Payment_ID = $_GET['PPI'];
} else {
    $Patient_Payment_ID = '';
}
if (isset($_GET['Status_From'])) {
    $Status_From = $_GET['Status_From'];
} else {
    $Status_From = '';
}
if (isset($_GET['II'])) {
    $Item_ID = $_GET['II'];
} else {
    $Item_ID = '';
}

$consultation_query = "SELECT MAX(consultation_ID) as consultation_ID FROM tbl_consultation WHERE Registration_ID = '$Registration_ID'";
$consultation_query_result = mysqli_query($conn,$consultation_query) or die(mysqli_error($conn));

if (@mysqli_num_rows($consultation_query_result) > 0) {
    $row = mysqli_fetch_assoc($consultation_query_result);
    $consultation_ID = $row['consultation_ID'];
    if ($consultation_ID == NULL) {
        $consultation_ID = 0;
    }
} else {
    $consultation_ID = 0;
}

$data = '';
$status = '';

if (isset($_POST['submitted'])) {

    if (isset($_FILES['Radiology_Image']['name']) && $_FILES['Radiology_Image']['name'] != null && !empty($_FILES['Radiology_Image']['name'])) {
        error_reporting(E_ERROR | E_PARSE);
        $Registration_ID = $_GET['RI'];
        $path = $_POST['Radiology_Image'];
        $target = "../RadiologyImage/";
        $Upload_Date = date('Y-m-d H:i:s');
        $imagename = $Registration_ID . $Item_ID . $Patient_Payment_Item_List_ID . date('YmdHis') . $_FILES['Radiology_Image']['name'];
        $target = $target . $imagename;
        //die($target);
        $Patient_Payment_Item_List_ID = $_GET['PPILI'];
        $Patient_Payment_ID = $_GET['PPI'];

        if (move_uploaded_file($_FILES['Radiology_Image']['tmp_name'], $target)) {
            $sql = "
							INSERT INTO tbl_radiology_image(
								Registration_ID,
								Item_ID,
								Radiology_Image,
								Patient_Payment_Item_List_ID,
								Upload_Date,
                                                                consultation_ID
                                                                ) 
									VALUES(
									'$Registration_ID',
									'$Item_ID',
									'$imagename',
									'$Patient_Payment_Item_List_ID',
									'$Upload_Date',"
                    . "'$consultation_ID'"
                    . ")";
            $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
            if ($result) {

                $status = "Image Successfully Uploaded.";
            } else {
                $status = "Failed To Upload Image,Try again or contact the system admin";
            }
        } else {
            $status = "Failed to move the image to the specified directory.";
        }
    } else {
        $status = "No image selected for upload,Please choose one to proceed.";
    }
}

isset($_GET['listtype']) ? $listtype = $_GET['listtype'] : $listtype = '';
isset($_GET['PatientType']) ? $PatientType = $_GET['PatientType'] : $PatientType = '';
$href = "II=" . $Item_ID . "&PPILI=" . $Patient_Payment_Item_List_ID . "&PPI=" . $Patient_Payment_ID . '&RI=' . $Registration_ID . '&PatientType=' . $PatientType . '&listtype=' . $listtype;
$data .= '<form  action="requests/radiologyviewimage.php?' . $href . '" id="radimagingform" method="POST" enctype="multipart/form-data">		
	<center>
	<table width="100%" height="80%" border= align="center">
			<tr>
				<td><input type="file" name="Radiology_Image"  ></td>
				<td>
				<input type="submit" name="submit" onclick="return uploadImages()" value="UPLOAD" id="uploadImage" class="art-button-green">
				<input type="hidden" name="submitted"/>
				</td> 
				<td><input type="Reset" name="reset" value="CANCEL" class="art-button-green"></td>
			</tr>
			<tr>
				<td id="Search_Iframe" style="text-align:center;" colspan="3">
				<div style="width:100%; height:auto;oveflow-y:scroll;overflow-x:hidden;">
				   
				';

$photo = "SELECT Pic_ID,Radiology_Image,Product_Name FROM tbl_radiology_image r JOIN tbl_items i ON i.Item_ID=r.Item_ID  WHERE Registration_ID='$Registration_ID' AND r.Item_ID = '$Item_ID'";
$result = mysqli_query($conn,$photo) or die(mysqli_error($conn));
if (mysqli_num_rows($result) > 0) {
    $list = 0;
    $data .= '<table><tr>';
    while ($row = mysqli_fetch_array($result)) {
        $list++;

        $Radiology_Image = $row['Radiology_Image'];

        $file = getimagesize($Radiology_Image);

        $data .= '<h3 style="text-align: center;display:inline">';

        if (preg_match('/\.(jpg|jpeg|png|gif)(?:[\?\#].*)?$/i', $Radiology_Image)) {
            $data .= "<td><input type='checkbox' id='close_id' onclick='remove_img(" . $row['Pic_ID'] . ",this,\"" . $_SERVER['QUERY_STRING'] . "\",\"" . $row['Product_Name'] . "\")'></td><td><a class='fancyboxRadimg' href='RadiologyImage/" . $Radiology_Image . "' title='" . $test_name . "' target='_blank'><img height='height='20' alt=''  src='RadiologyImage/" . $Radiology_Image . "'  alt=''/></a></td>";
        } else {
            $data .= "<td><input type='checkbox' id='close_id' onclick='remove_img(" . $row['Pic_ID'] . ",this,\"" . $_SERVER['QUERY_STRING'] . "\",\"" . $row['Product_Name'] . "\")'></td><td><a href='RadiologyImage/" . $Radiology_Image . "' title='" . $test_name . "'  class='' target='_blank'><img height='height='20' alt='' src='images/attach_icon.png'  alt=''/></a></td>";
        }
    }
    $data .= '</tr></table>'; 
} else {
    $data .= "<center><b style='text-align: center;font-size: 14px;font-weight: bold;color:red'>No Radiology Images For This Patient.</b></center>";
}

$data .= '</div></td></tr></table></center></form>';

$dataToEncode = array(
    'statusMsg' => $status,
    'dataToDisplay' => $data
);
//$dataToEncode
//echo $status;		
echo $status . '<1$$##92>' . $data; //json_encode($dataToEncode);
?>
<script>
    function remove_img(Attachment_ID, instance, href, itemName) {
        if ($(instance).is(":checked")) {
            if (!confirm('Are sure you want to remove this item')) {
                $(instance).prop("checked", false);
                exit;
            }

            $.ajax({
                type: 'POST',
                url: 'requests/remove_attached_img.php',
                data: 'source=rad&Attachment_ID=' + Attachment_ID,
                beforeSend: function (xhr) {
                    $("#progressStatus").show();
                },
                success: function (result) {
                    if (result == '1') {
                        radiologyviewimage(href, itemName);
                    } else {
                        alert('Process failed');
                    }
                }, complete: function (jqXHR, textStatus) {
                    $("#progressStatus").hide();
                }
            });

        }
    }
</script>

