<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	$select = mysqli_query($conn,"select Fast_Track from tbl_reception_items_list_cache where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		$result = mysqli_fetch_assoc($select);
		$Fast_Track = $result['Fast_Track'];
		if($Fast_Track == '1'){
?>
			<select id="Transaction_Mode" name="Transaction_Mode" onchange="Validate_Transaction_Mode()">
                <option>Fast Track Transaction</option>
            </select>
<?php
		}else{
?>
			<select id="Transaction_Mode" name="Transaction_Mode" onchange="Validate_Transaction_Mode()">
                <option selected="selected">Normal Transaction</option>
            </select>
<?php
		}
	}else{
?>
		<select id="Transaction_Mode" name="Transaction_Mode" onchange="Validate_Transaction_Mode()">
            <option selected="selected">Normal Transaction</option>
            <option>Fast Track Transaction</option>
        </select>
<?php
	}
?>