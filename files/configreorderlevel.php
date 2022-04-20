<script src='js/functions.js'></script><!--<script src="jquery.js"></script>-->
<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>


<?php
    if(isset($_SESSION['userinfo'])){
?>
    <a href='./configreorderlevelreagent.php?ItemsConfigurationReagent=ItemsConfigurationReagentThisPage' class='art-button-green'>
        CONFIGURE REAGENTS
    </a>
<?php  } ?>


<?php
    if(isset($_SESSION['userinfo'])){
?>
    <a href='./itemsconfiguration.php?ItemsConfiguration=ItemsConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } 

// die("SELECT tsd.Sub_Department_ID, tsd.Sub_Department_Name from
// tbl_sub_department tsd, tbl_items_balance ib where
// tsd.Sub_Department_ID = ib.Department_ID AND tsd.Department_ID IN(SELECT Department_ID FROM tbl_department WHERE Department_Location IN('Pharmacy','Storage And Supply')) group by ib.Sub_Department_ID");

?>
<br/>
 <br/>
<center> 
        <center>
            <fieldset>
                    <legend align="center" ><b>RE-ORDER LEVEL CONFIGURATION PHARMACEUTICAL</b></legend>
		    <br/>
		    <fieldset>
			<legend align="right" ><b>GENERAL</b></legend>
<center><p style="color: #0079AE;font-weight:bold"><i> NB: Use this Panel Only if you are sure that you want to Update the SAME DEFAULT VALUE to All Items in a Selected Store/Pharmacy. </i></p></center>

			<table width=70%>
				<tr>
				    <td style='text-align: right; width: 25%;'>
					<b>Select Location : </b>
				    </td>
				    <td>
					<select name='Sub_Department_ID' id='Sub_Department_ID' required='required'>
					    <option selected='selected'></option>
					    <option value='0'>All</option>
					    <?php

						// die("SELECT tsd.Sub_Department_ID, tsd.Sub_Department_Name from
						// tbl_sub_department tsd, tbl_items_balance ib where
						// tsd.Sub_Department_ID = ib.Department_ID AND tsd.Department_ID IN(SELECT Department_ID FROM tbl_department WHERE Department_Location IN('Pharmacy','Storage And Supply')) group by ib.Sub_Department_ID");
						$sql_select = mysqli_query($conn,"SELECT tsd.Sub_Department_ID, tsd.Sub_Department_Name from
									    tbl_sub_department tsd, tbl_items_balance ib where
										tsd.Sub_Department_ID = ib.Sub_Department_ID AND tsd.Department_ID IN(SELECT Department_ID FROM tbl_department WHERE Department_Location IN('Pharmacy','Storage And Supply')) group by ib.Sub_Department_ID") or die(mysqli_error($conn));
						$num = mysqli_num_rows($sql_select);
						if($num > 0){
						    while($row = mysqli_fetch_array($sql_select)){
					    ?>
							<option value='<?php echo $row['Sub_Department_ID']; ?>'><?php echo ucfirst($row['Sub_Department_Name']); ?></option>
					    <?php
						    }
						}
					    ?>
					</select>
				    </td>
				    <td>
					<input type='checkbox' name='Filter_Option' id='Filter_Option' title='Changes will include all specific items'><b><span title='Changes will include all specific items'>Include Specific Items</span></b>
				    </td>
				</tr>
				<tr>
				    <td style='text-align: right;'><b>Re-Order Level Value : </b></td>
				    <td>
					<input type='text' autocomplete='off' name='Re_Order_Value' id='Re_Order_Value' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'>
				    </td>
				    <td>
				       <input type='button' name='Submit' id='Submit' value='UPDATE' class='art-button-green' onclick='Update_General_Re_Order_Level_Function()'>
				    </td>
				</tr><!--
				<tr>
				    <td id='General_Re_Order_Level_Status' colspan=3 style='text-align: center;'>
				    </td>
				</tr>-->
			</table>
		</fieldset>
		    <br/><br/>
		<fieldset>
		    <legend align="right" ><b>SPECIFIC</b></legend>
			<table width=100%>
			    <tr>
				    <td style='text-align: center;' width=40%>
					<b>Category : </b>
					    <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
						<option selected='selected'></option>
						<?php
						    $data = mysqli_query($conn,"SELECT Item_Category_Name, Item_Category_ID
							    from tbl_item_category WHERE Category_Type <> 'Service'
							    ") or die(mysqli_error($conn));
						    while($row = mysqli_fetch_array($data)){
							echo '<option value="'.$row['Item_Category_ID'].'">'.$row['Item_Category_Name'].'</option>';
						    }
						?>   
					    </select>
				    </td>
				</tr>
				<tr>
				    <td>
					    <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup='getItemsListFiltered(this.value)' placeholder='Enter Item Name'>
				    </td>
				</tr>			    
				<tr>
				    <td>
					<fieldset style='overflow-y: scroll; height: 270px;' id='Items_Fieldset'>
					    <table width=100%>
						<?php
						    $result = mysqli_query($conn,"SELECT * FROM tbl_items where Item_Type <> 'Service' AND Can_Be_Stocked = 'yes' order by Product_Name");
						    while($row = mysqli_fetch_array($result)){
							echo "<tr>
							    <td style='color:black; border:2px solid #ccc;text-align: left;' width=5%>"; ?>
								
								<input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);">
							   
							<?php
							    echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'>".$row['Product_Name']."</td></tr>";
						    }
						?> 
					    </table>
					</fieldset>
				    </td>
				    <td colspan=2>
					<center>
					    <table width=100%>
						<tr>
						    <td style='text-align: right; width: 20%;'>
							<b>Select Location </b>
						    </td>
						    <td style='text-align: left; width: 40%;'>
							<select name='Sub_Department_ID2' id='Sub_Department_ID2' required='required' onchange='Get_Previous_Value()'>
							    <option selected='selected'></option>
							    <option value='0'>All</option>
							    <?php
								$sql_select = mysqli_query($conn,"SELECT tsd.Sub_Department_ID, tsd.Sub_Department_Name from
								tbl_sub_department tsd, tbl_items_balance ib where
								tsd.Sub_Department_ID = ib.Sub_Department_ID AND tsd.Department_ID IN(SELECT Department_ID FROM tbl_department WHERE Department_Location IN('Pharmacy','Storage And Supply')) group by ib.Sub_Department_ID") or die(mysqli_error($conn));
								$num = mysqli_num_rows($sql_select);
								if($num > 0){
								    while($row = mysqli_fetch_array($sql_select)){
							    ?>
									<option value='<?php echo $row['Sub_Department_ID']; ?>'><?php echo ucfirst($row['Sub_Department_Name']); ?></option>
							    <?php
									
								    }
								}
							    ?>
							</select>
						    </td>
						</tr>
						<tr>
						    <td style='text-align: right;' width=32%><b>Item Name</b></td>
						    <td width=65%><input type='text' style='text-align: left;' size=30 name='Item_Name' id='Item_Name' size=20 placeholder='Item Name' readonly='readonly' required='required'>
						</tr>
						<tr>
						    <td style='text-align: right;' width=20%><b>Current Re-Order Level Value</b></td>
						    <td width=15%><input type='text' style='text-align: left;' size=30 name='Previous_Re_Order_Level_Value' id='Previous_Re_Order_Level_Value' size=20 placeholder='Previous Re-Order Level' readonly='readonly'></td>
						</tr>
						<tr>
						    <td style='text-align: right;' width=20%><b>New Re-Order Level Value</b></td>
						    <td width=15%><input type='text' style='text-align: left;' size=30 name='New_Re_Order_Level_Value' id='New_Re_Order_Level_Value' size=20 placeholder='New Re-Order Level' required='required'></td>
						</tr>
					    </table>
					    
					    <input type='hidden' name='Item_ID' id='Item_ID' value=''>
					    <br/>
					    <input type='button' value='UPDATE' class='art-button-green' onclick='Update_Specific_Re_Order_Level_Function()'>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<button name='Refresh_Button' id='Refresh_Button' class='art-button-green' onclick='refreshlist()'>REFRESH ITEMS LIST</button>
					</center>
				    </td>
				</tr>
			</table>    
		</fieldset>
	    </fieldset>
</center>
	
	
<script>
	function Get_Item_Name(Item_Name,Item_ID){
	    document.getElementById('New_Re_Order_Level_Value').value = '';
	    document.getElementById('New_Re_Order_Level_Value').focus();
	    var Temp = '';
	    if(window.XMLHttpRequest) {
		myObjectGetItemName = new XMLHttpRequest();
	    }else if(window.ActiveXObject){
		myObjectGetItemName = new ActiveXObject('Micrsoft.XMLHTTP');
		myObjectGetItemName.overrideMimeType('text/xml');
	    }
	    
	    document.getElementById("Item_Name").value = Item_Name;
	    document.getElementById("Item_ID").value = Item_ID; 
	    
	    if (Item_ID != null && Item_ID != '') {
		    Get_Previous_Value();
	    }
	}
</script>

<script>
    function getItemsListFiltered(Item_Name){
	document.getElementById("Item_Name").value = '';
	document.getElementById("Item_ID").value = '';
	
	document.getElementById('New_Re_Order_Level_Value').value = '';
	document.getElementById('Previous_Re_Order_Level_Value"').value = '';
	
	var Item_Category_ID = document.getElementById("Item_Category_ID").value;
	if (Item_Category_ID == '' || Item_Category_ID == null) {
	    Item_Category_ID = 'All';
	}
	
	if(window.XMLHttpRequest) {
	    myObject = new XMLHttpRequest();
	}else if(window.ActiveXObject){ 
	    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
	    myObject.overrideMimeType('text/xml');
	}
	
	myObject.onreadystatechange = function (){
		    data = myObject.responseText;
		    if (myObject.readyState == 4) {
			//document.getElementById('Approval').readonly = 'readonly';
			document.getElementById('Items_Fieldset').innerHTML = data;
		    }
		}; //specify name of function that will handle server response........
	myObject.open('GET','Get_List_Of_Requisition_Filtered_Items.php?Item_Category_ID='+Item_Category_ID+'&Item_Name='+Item_Name,true);
	myObject.send();
    }
	
	
</script>

<script>
    function getItemsList(Item_Category_ID){
	document.getElementById("Search_Value").value = ''; 
	document.getElementById("Item_Name").value = '';
	document.getElementById("Item_ID").value = '';
	document.getElementById('New_Re_Order_Level_Value').value = '';
	document.getElementById('Previous_Re_Order_Level_Value').value = '';
	if(window.XMLHttpRequest) {
	    myObject = new XMLHttpRequest();
	}else if(window.ActiveXObject){ 
	    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
	    myObject.overrideMimeType('text/xml');
	}
	//alert(data);
    
	myObject.onreadystatechange = function (){
		    data = myObject.responseText;
		    if (myObject.readyState == 4) {
			//document.getElementById('Approval').readonly = 'readonly';
			document.getElementById('Items_Fieldset').innerHTML = data;
		    }
		}; //specify name of function that will handle server response........
	myObject.open('GET','Get_List_Of_Requisition_Items_List.php?Item_Category_ID='+Item_Category_ID,true);
	myObject.send();
    }
</script>

<script>
    function getItemsListFiltered(Item_Name){
		document.getElementById("Item_Name").value = '';
		document.getElementById("Item_ID").value = '';
		var Item_Category_ID = document.getElementById("Item_Category_ID").value;
		if (Item_Category_ID == '' || Item_Category_ID == null) {
		    Item_Category_ID = 'All';
		}
		
		if(window.XMLHttpRequest) {
		    myObject = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObject.overrideMimeType('text/xml');
		}
	    
		myObject.onreadystatechange = function (){
			    data = myObject.responseText;
			    if (myObject.readyState == 4) {
				//document.getElementById('Approval').readonly = 'readonly';
				document.getElementById('Items_Fieldset').innerHTML = data;
			    }
			}; //specify name of function that will handle server response........
		myObject.open('GET','Get_List_Of_Requisition_Filtered_Items.php?Item_Category_ID='+Item_Category_ID+'&Item_Name='+Item_Name,true);
		myObject.send();
	}
</script>




<script>
    function Update_General_Re_Order_Level_Function(){
	var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
	var Re_Order_Value = document.getElementById("Re_Order_Value").value;
	var Filter_Option = document.getElementById("Filter_Option").checked;
	var Employee_ID = '<?= $Employee_logedin_id ?>';

	//reset the other side
	document.getElementById("New_Re_Order_Level_Value").style = 'border: 3px';
	document.getElementById("Sub_Department_ID2").style = 'border: 3px';
	
	if (Sub_Department_ID != '' && Sub_Department_ID != null && Re_Order_Value != null && Re_Order_Value != '') {
	    document.getElementById("Re_Order_Value").style = 'border: 3px';
	    document.getElementById("Sub_Department_ID").style = 'border: 3px';
	    
	    if (Filter_Option) {
		if (Sub_Department_ID == 0) {
		    var Confirm_Message = confirm("Are you sure you want to change re-order level including specific items for all locations?\nClick OK to proceed or CANCEL to stop process" );
		}else{
		    var Confirm_Message = confirm("Are you sure you want to change re-order level including specific items for selected location?\nClick OK to proceed or CANCEL to stop process" );
		}
	    }else{
		if (Sub_Department_ID == 0) {
		    var Confirm_Message = confirm("Are you sure you want to change re-order level for all locations?\nClick OK to proceed or CANCEL to stop process" );
		}else{
		    var Confirm_Message = confirm("Are you sure you want to change re-order level for selected location?\nClick OK to proceed or CANCEL to stop process" );
		}
	    }
	   
	    if (Confirm_Message == true){
		if(window.XMLHttpRequest) {
		    myObject = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObject.overrideMimeType('text/xml');
		}
		
		myObject.onreadystatechange = function (){
		    data40 = myObject.responseText;
		    if (myObject.readyState == 4) {
			//document.getElementById('Items_Fieldset').innerHTML = data40;
			var Taarifa = data40;
			if(Taarifa == 'yes'){
				alert("Re-Order Level has been updated Successfully");
			}
		    }
		}; //specify name of function that will handle server response........
		if (Filter_Option) {
		    myObject.open('GET','Update_General_Re_Order_Level.php?Sub_Department_ID='+Sub_Department_ID+'&Re_Order_Value='+Re_Order_Value+'&Employee_ID='+Employee_ID+'&Filter_Option=True',true);
		    myObject.send();
		}else{
		    myObject.open('GET','Update_General_Re_Order_Level.php?Sub_Department_ID='+Sub_Department_ID+'&Re_Order_Value='+Re_Order_Value+'&Employee_ID='+Employee_ID,true);
		    myObject.send();
		}
	    }
	}else{
		if(Sub_Department_ID=='' || Sub_Department_ID == null){
			document.getElementById("Sub_Department_ID").focus();
			document.getElementById("Sub_Department_ID").style = 'border: 3px solid red';
		}else{
		    document.getElementById("Sub_Department_ID").style = 'border: 3px';
		}
		
		if(Re_Order_Value=='' || Re_Order_Value == null){
		    document.getElementById("Re_Order_Value").focus();
		    document.getElementById("Re_Order_Value").style = 'border: 3px solid red';
		}else{
		    document.getElementById("Re_Order_Value").style = 'border: 3px';
		}
	}
    }
</script>

<script>
    function Get_Previous_Value() {
	var Sub_Department_ID2 = document.getElementById("Sub_Department_ID2").value;
	document.getElementById('New_Re_Order_Level_Value').value = '';
	document.getElementById('New_Re_Order_Level_Value').focus();
	var Item_ID = document.getElementById("Item_ID").value;
	//alert(Sub_Department_ID2+" , "+Item_ID);
	if (Sub_Department_ID2 != null && Sub_Department_ID2 != '' && Item_ID != null && Item_ID != '') {
	    document.getElementById("Sub_Department_ID2").style = 'border: 3px';
	    if (Sub_Department_ID2 == 0){
		document.getElementById("Previous_Re_Order_Level_Value").value = ' ';
	    }else{
		if(window.XMLHttpRequest) {
		    myObjectGetPreviousValue = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObjectGetPreviousValue = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectGetPreviousValue.overrideMimeType('text/xml');
		}
		
		myObjectGetPreviousValue.onreadystatechange = function (){
		    data40 = myObjectGetPreviousValue.responseText;
		    if (myObjectGetPreviousValue.readyState == 4) {
			document.getElementById('Previous_Re_Order_Level_Value').value = data40;
		    }
		}; //specify name of function that will handle server response........
		myObjectGetPreviousValue.open('GET','Get_Previous_Re_Order_Level_Value.php?Sub_Department_ID='+Sub_Department_ID2+'&Item_ID='+Item_ID,true);
		myObjectGetPreviousValue.send();
	    }
	}/*else{
	    if(Sub_Department_ID2 == '' || Sub_Department_ID2 == null){
		document.getElementById("Sub_Department_ID2").focus();
		document.getElementById("Sub_Department_ID2").style = 'border: 3px solid red';
	    }else{
		document.getElementById("Sub_Department_ID2").style = 'border: 3px';
	    }
	}*/
    }
</script>



<script>
    function Update_Specific_Re_Order_Level_Function(){
	var Sub_Department_ID2 = document.getElementById("Sub_Department_ID2").value;
	var New_Re_Order_Level_Value = document.getElementById("New_Re_Order_Level_Value").value;
	var Item_Name = document.getElementById("Item_Name").value;
	var Item_ID = document.getElementById("Item_ID").value;

	var Employee_ID = '<?= $Employee_logedin_id ?>';

	
	document.getElementById("Re_Order_Value").style = 'border: 3px';
	document.getElementById("Sub_Department_ID").style = 'border: 3px';
	
	if (Sub_Department_ID2 != '' && Sub_Department_ID2 != null && New_Re_Order_Level_Value != null && New_Re_Order_Level_Value != '' && Item_ID != null && Item_ID != '' && Item_Name != '' && Item_Name != null) {
	    document.getElementById("New_Re_Order_Level_Value").style = 'border: 3px';
	    document.getElementById("Sub_Department_ID2").style = 'border: 3px';
	    
	    if (Sub_Department_ID2 == 0) {
		var Confirm_Message = confirm("Are you sure you want to change "+Item_Name+" re-order level value to "+New_Re_Order_Level_Value+" for all Locations?");
	    }else{
		var Confirm_Message = confirm("Are you sure you want to change "+Item_Name+" re-order level value to "+New_Re_Order_Level_Value+" for selected location?");
	    }
	   
	    if (Confirm_Message == true){
		if(window.XMLHttpRequest) {
		    myObject = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObject.overrideMimeType('text/xml');
		}
		
		myObject.onreadystatechange = function (){
		    data41 = myObject.responseText;
		    if (myObject.readyState == 4) {
				var result = data41;
				if(result > 0){
					alert("Re-Order Level was Updated Successfully");
				}
			document.getElementById('Previous_Re_Order_Level_Value').value = data41;
			document.getElementById('New_Re_Order_Level_Value').value = '';
			document.getElementById('Previous_Re_Order_Level_Value').focus();
		    }
		}; //specify name of function that will handle server response........
		myObject.open('GET','Update_Specific_Re_Order_Level.php?Sub_Department_ID2='+Sub_Department_ID2+'&New_Re_Order_Level_Value='+New_Re_Order_Level_Value+'&Item_ID='+Item_ID+'&Employee_ID='+Employee_ID,true);
		myObject.send();
	    }
	}else{
	    if(Sub_Department_ID2 == '' || Sub_Department_ID2 == null){
		    document.getElementById("Sub_Department_ID2").focus();
		    document.getElementById("Sub_Department_ID2").style = 'border: 3px solid red';
	    }else{
		document.getElementById("Sub_Department_ID2").style = 'border: 3px';
	    }
	    
	    if(New_Re_Order_Level_Value =='' || New_Re_Order_Level_Value == null){
		document.getElementById("New_Re_Order_Level_Value").focus();
		document.getElementById("New_Re_Order_Level_Value").style = 'border: 3px solid red';
	    }else{
		document.getElementById("New_Re_Order_Level_Value").style = 'border: 3px';
	    }
	}
    }
</script>


<!-- hidden div -->
<div id="Refresh_Item_List" style="width:30%;" >
   <fieldset style='overflow-y: scroll; height: 150px;' id='Items_Fieldset'>
   <?php
		$select = mysqli_query($conn,"select Sub_Department_ID, Sub_Department_Name, Department_Location from 
								tbl_sub_department sd, tbl_department dep where 
									sd.Department_ID = dep.Department_ID order by sd.Sub_Department_Name") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
		echo '<table width = "100%" style="border:0 ">';
			while($row = mysqli_fetch_array($select)){
			    if($row['Department_Location'] == 'Storage And Supply'){
				$Sub_Department_Type = 'Storage';
			    }else{
				$Sub_Department_Type = 'Pharmacy';
			    }
	?>
		<tr>
		    <td>
			    <input type='checkbox' name='Refresh_Item_Object' id='Refresh_Item_Object' onclick="Update_Item_List(<?php echo $row['Sub_Department_ID']; ?>,'<?php echo $Sub_Department_Type; ?>')">
			    <b>Refresh <?php echo $row['Sub_Department_Name']; ?></b>
		    </td>
		</tr>
	<?php
			}
		echo '</table>';
		}
   ?>
   </fieldset>
</div>
<!-- end of hidden div-->

<script>
	$(document).ready(function(){
            $("#Refresh_Item_List").dialog({ autoOpen: false, width:600,height:300, title:'REFRESH ITEMS LIST',modal: true});
	});		
</script>
<script>
	function refreshlist(){
	   $("#Refresh_Item_List").dialog("open");
	}
</script>
<script>
    function Update_Item_List(Sub_Department_ID,Sub_Department_Type){
	    if(window.XMLHttpRequest) {
		myObjectUpdateItemList = new XMLHttpRequest();
	    }else if(window.ActiveXObject){
		myObjectUpdateItemList = new ActiveXObject('Micrsoft.XMLHTTP');
		myObjectUpdateItemList.overrideMimeType('text/xml');
	    }
	
	    myObjectUpdateItemList.onreadystatechange = function (){
			data79 = myObjectUpdateItemList.responseText;
			if (myObjectUpdateItemList.readyState == 4) {
			    alert(data79);
			    //document.getElementById(Sub_Department_ID).innerHTML = data79;
			}
		    }; //specify name of function that will handle server response........
	    myObjectUpdateItemList.open('GET','Refresh_Item_List.php?Sub_Department_ID='+Sub_Department_ID+'&Sub_Department_Type='+Sub_Department_Type,true);
	    myObjectUpdateItemList.send();
	}
</script>

<?php
    include("./includes/footer.php");
?>