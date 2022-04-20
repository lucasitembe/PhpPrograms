<?php

require_once('includes/connection.php');

$Registration_ID = 0;
$Item_ID = 0;
$Parameter_ID = 0;
$Patient_Payment_Item_List_ID = 0;
$Comments = '';
$Radiology_Date = '';
$theid = '';

if (isset($_POST['RI']))
    $Registration_ID = $_POST['RI'];
if (isset($_POST['II']))
    $Item_ID = $_POST['II'];
if (isset($_POST['PPILI']))
    $Patient_Payment_Item_List_ID = $_POST['PPILI'];
if (isset($_POST['datastring']))
    $datastring = $_POST['datastring'];

if (isset($_POST['RD']))
    $Radiology_Date = $_POST['RD'];


if (isset($_POST['Employee_ID']))
    $Employee_ID = $_POST['Employee_ID'];
//echo $datastring;
//$Comments = ereg_replace( "\n",'|', $Comments);

$extract = explode('$$$$tenganisha###', $datastring);

foreach ($extract as $value) {
 $dt=  explode('uiytregwhs', $value);
  $Parameter_ID= trim($dt[0]);
  $Comments=trim($dt[1]);
  $Comments=mysqli_real_escape_string($conn,$Comments);
    $select_comments = "
		SELECT * 
			FROM
			tbl_radiology_discription
				WHERE
				Registration_ID = '$Registration_ID' AND
				Item_ID = '$Item_ID' AND
                                Patient_Payment_Item_List_ID='" . $Patient_Payment_Item_List_ID . "' AND
				Parameter_ID = '$Parameter_ID'
	";

    $insert_comment = "
		INSERT INTO 
        tbl_radiology_discription (Registration_ID, Item_ID, Parameter_ID, Patient_Payment_Item_List_ID, comments, Radiology_Date)
			VALUES ('$Registration_ID', '$Item_ID', '$Parameter_ID', '$Patient_Payment_Item_List_ID', '$Comments', NOW())
	";

    $update_comment = "
		UPDATE tbl_radiology_discription 
			SET comments = '$Comments'
				WHERE
				Registration_ID = '$Registration_ID' AND
				Item_ID = '$Item_ID' AND
                                Patient_Payment_Item_List_ID='" . $Patient_Payment_Item_List_ID . "' AND    
				Parameter_ID = '$Parameter_ID'					
	";

    $datas_update = mysqli_query($conn, "UPDATE tbl_radiology_patient_tests SET Radiologist_ID = '$Employee_ID' WHERE Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));

    $select_comments_qry = mysqli_query($conn,$select_comments) or die(mysqli_error($conn));
    if (mysqli_num_rows($select_comments_qry) > 0) {
        //Comment Exists
        $insert_comment_qry = mysqli_query($conn,$update_comment) or die(mysqli_error($conn));

        //Get The Row ID
        while ($rowid = mysqli_fetch_assoc($select_comments_qry)) {
            $theid = $rowid['Radiology_Description_ID'];
        }
    } else {
        //Comment Don't Exist
        $insert_comment_qry = mysqli_query($conn,$insert_comment) or die(mysqli_error($conn));
    }

 
}

   echo '1';
?>