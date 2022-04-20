<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    
    //get employee id 
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = '';
    }
    
    //get employee name
    if(isset($_SESSION['userinfo']['Employee_Name'])){
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $Employee_Name = '';
    }
	
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
	    if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        }else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
                echo "<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>BACK</a>";
        }
    }
    
?>
<a href="print_reorderlevelnotification.php" target="_blank" style="float: right;" class="art-button-green">PREVIEW</a>
<style>
		table,tr,td{
		border-collapse:collapse !important;
		border:none !important;
		
		}
	tr:hover{
	background-color:#eeeeee;
	cursor:pointer;
	}
</style> 

<br/><br/>
<fieldset style='overflow-y: scroll; height: 350px;' id='Previous_Fieldset_List'>
    <legend style="background-color:#006400;color:white;padding:8px;" align=right><b>List of items below re-order level ~ <?php if(isset($_SESSION['Storage'])){ echo $_SESSION['Storage']; }?></b></legend>
	<?php
	    $temp = 1;
		echo '<tr><td colspan="4"><hr></td></tr>';
		echo '<center><table width = 100% border=0>';
		echo "<tr id='thead'>
			<td width=5% style='text-align: left;'><b>Sn</b></td>
			<td width=30% style='text-align: left;'><b>Item Name</b></td>
			<td width=25%><b>Current Balance</b></td>
			<td width=20%><b>Re-Order Value</b></td>
		    </tr>";
			echo '<tr><td colspan="4"><hr></td></tr>';
	    
	    if(isset($_SESSION['Storage'])){
                $Sub_Department_Name = $_SESSION['Storage'];
                $sql_num = mysqli_query($conn,"select i.Product_Name, ib.Item_Balance, ib.Reorder_Level from tbl_items_balance ib, tbl_items i where
                                        i.Item_ID = ib.Item_ID and
                                        ib.Item_Balance < ib.Reorder_Level and
                                                Sub_Department_ID = (select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name' limit 1)") or die(mysqli_error($conn));
                $num = mysqli_num_rows($sql_num);
                if($num > 0){
                    while($row = mysqli_fetch_array($sql_num)){
        ?>
                    <tr>
                        <td><?php echo $temp; ?></td>
                        <td><?php echo $row['Product_Name']; ?></td>
                        <td><?php echo $row['Item_Balance']; ?></td>
                        <td><?php echo $row['Reorder_Level']; ?></td>
                    </tr>
        <?php
                        $temp++;
                    }
                }
	    }
	    echo '</table>';
	?>
</fieldset>

    <!--<iframe src='Previous_Requisitions_Iframe.php?Employee_ID=<?php //echo $Employee_ID; ?>&Date_From=<?php //echo $Date_From; ?>&Date_To=<?php //echo $Date_To; ?>' width=100% height=380px></iframe>-->
<table width=100%>
    <tr>
        <td style='text-align: right;'>
            <input type='button' name='Quick_Purchase_Order' id='Quick_Purchase_Order' class='art-button-green' value='CREATE QUICK PURCHASE ORDER' onclick='Confirm_Quick_Purchase_Order()'>
        </td>
    </tr>
</table>
<script>
    function Confirm_Quick_Purchase_Order() {
        var Confirm_Message = confirm("Are you sure you want to create quick purchase order?");
        if (Confirm_Message == true) {
            //document.location = 'Control_Purchase_Order_Sessions.php?Quick_Purchase_Order=True';
	    //Check if someone is processing the same process
	    
	    if(window.XMLHttpRequest) {
		myObjectConfirm = new XMLHttpRequest();
	    }else if(window.ActiveXObject){
		myObjectConfirm = new ActiveXObject('Micrsoft.XMLHTTP');
		myObjectConfirm.overrideMimeType('text/xml');
	    }
	    
	    myObjectConfirm.onreadystatechange = function (){
		data = myObjectConfirm.responseText;
		if (myObjectConfirm.readyState == 4) {
		    var Feedback = data;
		    if (Feedback == 'yes') {
			document.location = 'reorderlevelnotificationwarning.php?QuickPurchaseWarning=True';
		    }else if(Feedback =='yes2'){
			document.location = 'Control_Purchase_Order_Sessions.php?Self_Quick_Purchase_Order=True';
		    }else{
			document.location = 'Control_Purchase_Order_Sessions.php?Quick_Purchase_Order=True';
		    }
		}
	    }; //specify name of function that will handle server response........
	    myObjectConfirm.open('GET','Confirm_Quick_Purchase_Order.php',true);
	    myObjectConfirm.send();
        }
    }
</script>
<?php
    include('./includes/footer.php');
?>