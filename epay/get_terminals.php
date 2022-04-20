<?php
include '../includes/connection.php';



if(isset($_GET['trans_type']) && $_GET['trans_type']=='Offline'){
	$query = "Select * from tbl_epay_offline_terminals_config";
	$result = mysqli_query($conn,$query) or die(mysqli_error($conn));

	echo '<option value="">select terminal</option>';
	while($dt = mysqli_fetch_assoc($result)){ ?>
		
        <option value="<?= $dt['terminal_id'] ?>"><?= $dt['terminal_name'] ?></option>
	<?php	}
} else if(isset($_GET['trans_type']) && $_GET['trans_type']=='Manual'){

	echo '<option value="">select terminal</option>';
	echo '<option value="manual">Integrated Terminal</option>';

}