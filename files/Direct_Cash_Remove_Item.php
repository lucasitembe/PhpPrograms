<?php
	session_start();
	include("./includes/connection.php");

	if (!isset($_SESSION['userinfo'])) {
	    @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
	}
	if (isset($_SESSION['userinfo'])) {
	    if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
	        if ($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes') {
	            header("Location: ./index.php?InvalidPrivilege=yes");
	        } else {
	            //@session_start();
	            if (!isset($_SESSION['supervisor'])) {
	                header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
	            }
	        }
	    } else {
	        header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	} else {
	    @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
	}

	//get employee id
	if (isset($_SESSION['userinfo']['Employee_ID'])) {
	    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	} else {
	    $Employee_ID = '0';
	}

	if(isset($_GET['Cache_ID'])){
		$Cache_ID = $_GET['Cache_ID'];
	}else{
		$Cache_ID = '';
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	$delete = mysqli_query($conn,"delete from tbl_direct_cash_cache where Cache_ID = '$Cache_ID'") or die(mysqli_error($conn));
?>

	<table width="100%">
	   <tr>
            <td width="5%"><b>SN</b></td>
            <td><b>ITEM NAME</b></td>
            <td><b>ITEM DESCRIPTION</b></td>
            <td width="9%" style="text-align: right;"><b>AMOUNT</b></td>
            <td width="5%"></td>
        </tr>
<?php
	$select = mysqli_query($conn,"select dcc.Cache_ID, dcc.Item_Description, dcc.Amount, i.Product_Name
							from tbl_direct_cash_cache dcc, tbl_items i where 
							dcc.Registration_ID = '$Registration_ID' and
							dcc.Item_ID = i.Item_ID and
							Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		$temp = 0;
		while ($data = mysqli_fetch_array($select)) {
?>
			<tr>
	            <td><?php echo ++$temp; ?></td>
	            <td><?php echo ucwords(strtolower($data['Product_Name'])); ?></td>
	            <td><?php echo $data['Item_Description']; ?></td>
	            <td style="text-align: right;"><?php echo number_format($data['Amount']); ?></td>
	            <td style="text-align: center;">
	            	<input type="button" value="X" onclick="Remove_Item('<?php echo $data['Item_Description']; ?>',<?php echo $data['Cache_ID']; ?>);" style="color: red;">
	            </td>
	        </tr>
<?php
		}
	}
?>
</table>