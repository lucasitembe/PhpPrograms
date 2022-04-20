<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_SESSION['General_Outward_ID'])){
		$Outward_ID = $_SESSION['General_Outward_ID'];
	}else{
		$Outward_ID = 0;
	}

	$slct = mysqli_query($conn,"select sd.Sub_Department_ID, sd.Sub_Department_Name from tbl_sub_department sd, tbl_return_outward ro where
    						sd.Sub_Department_ID = ro.Sub_Department_ID and
    						ro.Outward_ID = '$Outward_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($slct);
    if($nm > 0){
    	while ($dt = mysqli_fetch_array($slct)) {
    		$Store_Issued_Name = $dt['Sub_Department_Name'];
    		$Store_Issued_ID = $dt['Sub_Department_ID'];
    	}
    }else{
    	$Store_Issued_Name = '';
    	$Store_Issued_ID = '';
    }
?>
<select name="Sub_Department_ID" id="Sub_Department_ID">
<?php if($nm > 0){ ?>
	<option selected='selected' value="<?php echo $Store_Issued_ID; ?>"><?php echo $Store_Issued_Name; ?></option>
<?php 
    }else{ 
        echo "<option selected='selected' value=''></option>";
        $select = mysqli_query($conn,"select Sub_Department_ID, Sub_Department_Name from
                                tbl_department dep, tbl_sub_department sdep
                                where dep.department_id = sdep.department_id and
                                Department_Location = 'Storage And Supply'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            while ($data = mysqli_fetch_array($select)) {
?>
                <option value="<?php echo $data['Sub_Department_ID']; ?>"><?php echo $data['Sub_Department_Name']; ?></option>
<?php
            }
        }
    }
?>
</select>