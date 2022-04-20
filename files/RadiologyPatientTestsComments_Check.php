<?php 
require_once('includes/connection.php');
	$Registration_ID = 0;
	$Item_ID = 0;
	$Parameter_ID = 0;
	$Patient_Payment_Item_List_ID = 0;
	$Comments = '';
	$Radiology_Date = '';
	
	if(isset($_GET['RI'])) $Registration_ID = $_GET['RI'];
	if(isset($_GET['II'])) $Item_ID = $_GET['II'];
	if(isset($_GET['PI'])) $Parameter_ID = $_GET['PI'];
	if(isset($_GET['PPILI'])) $Patient_Payment_Item_List_ID = $_GET['PPILI'];
	if(isset($_GET['C'])) $Comments = $_GET['C'];
	if(isset($_GET['RD'])) $Radiology_Date = $_GET['RD'];
	
	$select_comments = "
		SELECT * 
			FROM
			tbl_radiology_discription
				WHERE
				Registration_ID = '$Registration_ID' AND
				Patient_Payment_Item_List_ID='" . $Patient_Payment_Item_List_ID . "' AND Item_ID = '$Item_ID' AND
				Parameter_ID = '$Parameter_ID'
	";
	
	$select_comments_qry = mysqli_query($conn,$select_comments) or die(mysqli_error($conn));
	if(mysqli_num_rows($select_comments_qry) > 0){
		while($comment = mysqli_fetch_assoc($select_comments_qry)){
			$oldcomment = $comment['comments'];
		}
		$output = $oldcomment;
	} else {
		$output = '';
	} 
        
     $output=   str_replace("<br/>", "\n", $output);
        
	echo $output;
	
?>