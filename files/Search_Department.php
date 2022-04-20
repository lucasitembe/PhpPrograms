<?php
	include("./includes/connection.php");

	if(isset($_GET['Department_Name'])){
		$Department_Name = str_replace(" ", "%", $_GET['Department_Name']);
	}else{
		$Department_Name = '';
	}	
?>
<legend align="left"><b>DEPARTMENTS LIST</b></legend>
<center>
    <table width = "80%">
        <tr>
            <td width="5%"><b>SN</b></td>
            <td><b>DEPARTMENT NAME</b></td>
            <td width="20%"><b>NATURE OF THE DEPARTMENT</b></td>
            <td width="15%" style="text-align: center;"><b>ACTION</b></td>
        </tr>
        <tr><td colspan="4"><hr></td></tr>
    <?php
        $select = mysqli_query($conn,"select * from tbl_department where Department_Name like '%$Department_Name%' order by Department_Name") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            $temp = 0;
            while ($data = mysqli_fetch_array($select)) {
    ?>
                <tr id="sss">
                    <td><?php echo ++$temp; ?><b>.</b></td>
                    <td><?php echo ucwords(strtolower($data['Department_Name'])); ?></td>
                    <td><?php echo ucwords(strtolower($data['Department_Location'])); ?></td>
                    <td style="text-align: center;">
        <?php if(strtolower($data['Department_Status']) == 'active'){ ?>
                        <input type="button" name="Remove_Button" id="Remove_Button" class="art-button-green" value="DISABLE DEPARTMENT" onclick="Remove_Department_Verify(<?php echo $data['Department_ID']; ?>)">
        <?php }else{ ?>
                        <input type="button" name="Activate_Button" id="Activate_Button" class="art-button-green" value="ACTIVATE DEPARTMENT" onclick="Activate_Department(<?php echo $data['Department_ID']; ?>)">
        <?php } ?>
                    </td>
                </tr>
    <?php
            }
        }
    ?>
    </table>
</center>