<?php
	include("./includes/connection.php");

	if(isset($_GET['Exemption_Category_ID'])){
		$Exemption_Category_ID = $_GET['Exemption_Category_ID'];
	}else{
		$Exemption_Category_ID = 0;
	}
	mysqli_query($conn,"delete from tbl_exemption_categories where Exemption_Category_ID = '$Exemption_Category_ID'");
?>