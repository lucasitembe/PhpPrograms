<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_GET['Admision_ID'])){
		$Admision_ID = $_GET['Admision_ID'];
	}else{
		$Admision_ID = 0;
	}
?>
<table width="100%">
<?php
    //get previous info
    $select_records = mysqli_query($conn,"select Admision_ID from tbl_patient_progress where Admision_ID IS NULL and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no_rec = mysqli_num_rows($select_records);
?>
    <tr>
        <td style="text-align: right">
            <button class="art-button-green" onclick="Preview()">PREVIEW HISTORY
                &nbsp;&nbsp;
                <span style='background-color: red; border-radius: 10px; color: white; padding: 8px;'><?php echo $no_rec; ?></span>
                &nbsp;&nbsp;
            </button>
            <input type="button" name="Save" id="Save" value="SAVE" class="art-button-green" onclick="Confirm_Save_Information()">
        </td>
    </tr>
</table>