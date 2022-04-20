<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_GET['Selected_Billing_Type'])){
	$Selected_Billing_Type = $_GET['Selected_Billing_Type'];
    }else{
	$Selected_Billing_Type = '';
    }
    
        
    if(isset($_GET['Reception_List_Item_ID'])){
        $Reception_List_Item_ID = $_GET['Reception_List_Item_ID'];
    }else{
        $Reception_List_Item_ID = 0;
    }
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    //get employee id
    
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
?>
<fieldset style='overflow-y: scroll; height: 200px;'>
<?php
    if($Reception_List_Item_ID != 0 && $Registration_ID != 0 && $Employee_ID != 0){
        //delete selected record
        $delete_details = mysqli_query($conn,"delete from tbl_reception_items_list_cache_others where Reception_List_Item_ID = '$Reception_List_Item_ID'") or die(mysqli_error($conn));
        
        if($delete_details){
            $total = 0;
            echo '<table width =100%>';
                echo "<tr id='thead'><td style='width: 3%;'>Sn</td><td style='width: 10%;'>Check in type</td>";
                    echo '<td style="width: 20%;">Location</td>
                        <td style="width: 28%;">Item description</td>
                            <td style="text-align:right; width: 8%;">Price</td>
                                <td style="text-align:right; width: 8%;">Discount</td>
                                    <td style="text-align:right; width: 8%;">Quantity</td>
                                        <td style="text-align:right; width: 8%;">Sub total</td><td width=4%>Remove</td></tr>';
                                        
            $select_Transaction_Items = mysqli_query($conn,
                "select Reception_List_Item_ID, Check_In_Type, Patient_Direction, alc.Item_Name, Price, Discount, Quantity,Registration_ID
                    from tbl_items t, tbl_reception_items_list_cache_others alc
                        where alc.Item_ID = t.Item_ID and
                            alc.Employee_ID = '$Employee_ID' and
                                    Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
            
            $no_of_items = mysqli_num_rows($select_Transaction_Items);
                while($row = mysqli_fetch_array($select_Transaction_Items)){
                    echo "<tr><td>".$temp."</td><td>".$row['Check_In_Type']."</td>";
                    echo "<td>".$row['Patient_Direction']."</td>";
                    echo "<td>".$row['Item_Name']."</td>";
                    echo "<td style='text-align:right;'>".number_format($row['Price'])."</td>";
                    echo "<td style='text-align:right;'>".number_format($row['Discount'])."</td>";
                    echo "<td style='text-align:right;'>".$row['Quantity']."</td>";
                    echo "<td style='text-align:right;'>".number_format(($row['Price'] - $row['Discount'])*$row['Quantity'])."</td>";
        ?>
            <td style='text-align: center;'> 
                <input type='button' style='color: red; font-size: 10px;' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Item_Name']); ?>",<?php echo $row['Reception_List_Item_ID']; ?>,<?php echo $row['Registration_ID']; ?>)'>
            </td>
        <?php
            $temp++;
            $total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity']);
        }echo "</tr>";
        echo "<tr><td colspan=8 style='text-align: right;'> Total : ".number_format($total)."</td></tr></table>";
        } 
    }
?>
</fieldset>
<table width='100%'>
    <tr>
	<?php
	    if($no_of_items > 0){
		
		?>
		<td style='text-align: right; width: 70%;'><h4>Total : <?php echo number_format($total); ?></h4></td>
		<td style='text-align: right; width: 30%;'>
		    <input type='button' value='Save Information' class='art-button-green' onclick='Patient_Billing_Reception_Generate_Receipt(<?php echo $Registration_ID; ?>)'>
		</td>
		<?php
	    }else{
		?>
		<td style='text-align: right; width: 70%;'><h4>Total : 0</h4></td>
			<td style='text-align: right; width: 30%;'>
			</td>
		<?php
	    }				
	?>
    </tr>
</table>