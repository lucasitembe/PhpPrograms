<?php
	session_start();
	include("./includes/connection.php");
	if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
    }else{
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }

	//get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    if(isset($_GET['Item_ID'])){
    	$Item_ID = $_GET['Item_ID'];
    }else{
    	$Item_ID = 0;
    }

    if(isset($_GET['Percentage'])){
        $Percentage = $_GET['Percentage'];
    }else{
        $Percentage = '';
    }
    
    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
        $Sponsor_ID = '';
    }

    if(isset($_GET['Status'])){
        $Status = $_GET['Status'];
    }else{
        $Status = '';
    }

    //add selected item
    if($Item_ID != 0){
    	//check if available
    	$select = mysqli_query($conn,"select Item_ID from tbl_edit_price_cache where Item_ID = '$Item_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
    	$num = mysqli_num_rows($select);
    	if($num < 1){
    		$insert = mysqli_query($conn,"insert into tbl_edit_price_cache(Item_ID, Employee_ID) values('$Item_ID','$Employee_ID')") or die(mysqli_error($conn));
    	}
    }

    $Title = '<tr><td colspan="5"><hr></td></tr>
	            <tr>
	                <td width="5%"><b>SN</b></td>
	                <td><b>ITEM NAME</b></td>
	                <td width="15%" style="text-align: right;"><b>CURRENT PRICE</b>&nbsp;&nbsp;&nbsp;</td>
	                <td width="15%" style="text-align: right;"><b>NEW PRICE</b>&nbsp;&nbsp;&nbsp;</td>
	                <td width="6%"><b>ACTION</b></td>
	            </tr>
	            <tr><td colspan="5"><hr></td></tr>';
?>
<table width="100%">
<?php
    echo $Title;
    $counter = 0;
    if($Sponsor_ID == 0){
    	$select = mysqli_query($conn,"select i.Item_ID, i.Product_Name, gp.Items_Price from tbl_items i, tbl_general_item_price gp, tbl_edit_price_cache epc where
                            i.Item_ID = gp.Item_ID and
                            i.Item_ID = epc.Item_ID and
                            i.Status = 'Available' and
                            epc.Employee_ID = '$Employee_ID' order by i.Product_Name") or die(mysqli_error($conn));
    }else{
         $select = mysqli_query($conn,"select i.Item_ID, i.Product_Name, ip.Items_Price from tbl_items i, tbl_item_price ip, tbl_edit_price_cache epc where
                                i.Item_ID = ip.Item_ID and
                                i.Item_ID = epc.Item_ID and
                                i.Status = 'Available' and
                                ip.Sponsor_ID = '$Sponsor_ID' and
                                epc.Employee_ID = '$Employee_ID' order by i.Product_Name") or die(mysqli_error($conn));
    }

    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($row = mysqli_fetch_array($select)) {
?>
        <tr>
            <td width="5%"><?php echo ++$counter; ?></td>
            <td><?php echo ucwords(strtolower($row['Product_Name'])); ?></td>
            <td width="15%" style="text-align: right;"><?php echo number_format($row['Items_Price']); ?>&nbsp;&nbsp;&nbsp;</td>
<?php
            if(isset($_GET['Percentage']) && $_GET['Percentage'] > 0){
                if(strtolower($Status) == 'increase'){
                    echo '<td width="15%" style="text-align: right;">'.number_format($row['Items_Price'] + ($row['Items_Price']*($Percentage/100))).'&nbsp;&nbsp;&nbsp;</td>';
                }else if(strtolower($Status) == 'decrease'){
                    echo '<td width="15%" style="text-align: right;">'.number_format($row['Items_Price'] - ($row['Items_Price']*($Percentage/100))).'&nbsp;&nbsp;&nbsp;</td>';
                }else{
                    echo '<td width="15%" style="text-align: right;"><i>Unspecified</i>&nbsp;&nbsp;&nbsp;</td>';
                }
            }else{
                echo '<td width="15%" style="text-align: right;"><i>Unspecified</i>&nbsp;&nbsp;&nbsp;</td>';
            }
?>
            <td width="6%">
                <input type="button" name="Remove" id="Remove" value="<< <<" onclick="Remove_Selected_Item(<?php echo $row['Item_ID']; ?>)">
            </td>
        </tr>
<?php
        if(($counter%51) == 0){
            echo $Title;
        }
        }
    }
?>
</table>