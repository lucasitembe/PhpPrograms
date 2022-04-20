<script src='js/functions.js'></script>
<?php
    /**include("./includes/header.php");
    include("./includes/connection.php");
        
	//get employee name
	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Employee_Name = 'Unknown Officer';
	}
	
	
	//get employee id
	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}
	
	
	//get branch id
	if(isset($_SESSION['userinfo']['Branch_ID'])){
		$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
	}else{
		$Branch_ID = 0;
	}
	
	
	if(!isset($_SESSION['userinfo'])){
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
	}
	
	if(isset($_SESSION['userinfo'])) {
		if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
			if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
				header("Location: ./index.php?InvalidPrivilege=yes");
			}else{
				@session_start();
				if(!isset($_SESSION['Storage_Supervisor'])){ 
				    header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
				}
			}
		}else{
			header("Location: ./index.php?InvalidPrivilege=yes");
		}
	}else{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }
	
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
			echo "<a href='returninwardoutwardworks.php?ReturnInwardOutward=ReturnInwardOutwardThisPage' class='art-button-green'>PREVIOUS OUTWARDS</a>";
			echo "<a href='returninwardoutwardworks.php?ReturnInwardOutward=ReturnInwardOutwardThisPage' class='art-button-green'>BACK</a>";
		}
	}

	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }

    if(isset($_SESSION['General_Outward_ID'])){
    	$Outward_ID = $_SESSION['General_Outward_ID'];
    }else{
    	$Outward_ID = 0;
    }

    //get sub department if session exists
    $slct = mysqli_query($conn,"select sd.Sub_Department_ID, sd.Sub_Department_Name, sp.Supplier_ID, sp.Supplier_Name from tbl_sub_department sd, tbl_return_outward ro, tbl_supplier sp where
    						sd.Sub_Department_ID = ro.Sub_Department_ID and
    						sp.Supplier_ID = ro.Supplier_ID and
    						ro.Outward_ID = '$Outward_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($slct);
    if($nm > 0){
    	while ($dt = mysqli_fetch_array($slct)) {
    		$Store_Issued_Name = $dt['Sub_Department_Name'];
    		$Store_Issued_ID = $dt['Sub_Department_ID'];
    		$Supplier_Issued_Name = $dt['Supplier_Name'];
    		$Supplier_Issued_ID = $dt['Supplier_ID'];
    	}
    }else{
    	$Store_Issued_Name = '';
    	$Store_Issued_ID = '';
		$Supplier_Issued_Name = '';
		$Supplier_Issued_ID = '';
    }*/

?>
<style>
	table,tr,td{
		border-collapse:collapse !important;
		border:none !important;
	}
</style> 
<br/><br/>
<fieldset>
	<legend style="background-color:#006400;color:white" align='right'><b>RETURN OUTWARDS</b></legend>
<table width="100%">
	<tr>
		<td style="text-align: right;">Store Issued</td>
		<td style="text-align: left;" id="Store_Issue_Area">
			<select name="Sub_Department_ID" id="Sub_Department_ID">
		<?php if(isset($_SESSION['General_Outward_ID'])){ ?>
				<option selected='selected' value="<?php echo $Store_Issued_ID; ?>"><?php echo $Store_Issued_Name; ?></option>
		<?php }else{ ?>
				<option selected='selected' value=""></option>
		<?php
				//select sub department
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
		</td>
		<td style="text-align: right;">Receiving Supplier</td>
		<td style="text-align: left;" id="Receiver_Area">
			<select name="Supplier_ID" id="Supplier_ID">
				<?php if(isset($_SESSION['General_Outward_ID'])){ ?>
					<option selected='selected' value="<?php echo $Supplier_Issued_ID; ?>"><?php echo $Supplier_Issued_Name; ?></option>
				<?php }else{ ?>
					<option selected='selected' value=""></option>
				<?php
						$select = mysqli_query($conn,"select Supplier_Name, Supplier_ID from tbl_supplier order by Supplier_Name") or die(mysqli_error($conn));
						$num = mysqli_num_rows($select);
						if($num > 0){
							while ($data = mysqli_fetch_array($select)) {
				?>
							<option value="<?php echo $data['Supplier_ID']; ?>"><?php echo $data['Supplier_Name']; ?></option>
				<?php
							}
						}
					}
				?>
			</select>
		</td>
		<td style="text-align: right;">Transaction Date</td>
		<td><input type="text" name="Transaction_Type" id="Transaction_Type" readonly="readonly" value="<?php echo $Today; ?>"></td>
		<td style="text-align: right;">Posted By</td>
		<td><input type="text" name="Employee_Name" id="Employee_Name" readonly="readonly" value="<?php echo $Employee_Name; ?>"></td>
	</tr>
</table> 
</center>
</fieldset>

<script type="text/javascript">
	function Update_Store_Issued(){
		if(window.XMLHttpRequest) {
		    myObject_Update_Store_Issue = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObject_Update_Store_Issue = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObject_Update_Store_Issue.overrideMimeType('text/xml');
		}
	    
		myObject_Update_Store_Issue.onreadystatechange = function (){
		    data980 = myObject_Update_Store_Issue.responseText;
		    if (myObject_Update_Store_Issue.readyState == 4) {
				document.getElementById('Store_Issue_Area').innerHTML = data980;
		    }
		}; //specify name of function that will handle server response........
		myObject_Update_Store_Issue.open('GET','Return_Outward_Update_Store_Issue.php',true);
		myObject_Update_Store_Issue.send();
	}
</script>

<script type="text/javascript">
	function Update_Receiving_Supplier(){
		if(window.XMLHttpRequest) {
		    myObject_Update_Supplier = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObject_Update_Supplier = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObject_Update_Supplier.overrideMimeType('text/xml');
		}
	    
		myObject_Update_Supplier.onreadystatechange = function (){
		    data990 = myObject_Update_Supplier.responseText;
		    if (myObject_Update_Supplier.readyState == 4) {
				document.getElementById('Receiver_Area').innerHTML = data990;
		    }
		}; //specify name of function that will handle server response........
		myObject_Update_Supplier.open('GET','Return_Outward_Update_Supplier.php',true);
		myObject_Update_Supplier.send();
	}
</script>


<script type="text/javascript">
	function Update_Submit_Button(){
		if(window.XMLHttpRequest) {
		    myObject_Update_Submit_button = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObject_Update_Submit_button = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObject_Update_Submit_button.overrideMimeType('text/xml');
		}
	    
		myObject_Update_Submit_button.onreadystatechange = function (){
		    data9976 = myObject_Update_Submit_button.responseText;
		    if (myObject_Update_Submit_button.readyState == 4) {
				document.getElementById('Submit_Button_Area').innerHTML = data9976;
		    }
		}; //specify name of function that will handle server response........
		myObject_Update_Submit_button.open('GET','Return_Outward_Update_Submit_Button.php',true);
		myObject_Update_Submit_button.send();
	}
</script>

<script type="text/javascript">
	function Get_Item_Name(Item_Name,Item_ID){
		var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
		var Supplier_ID = document.getElementById("Supplier_ID").value;

		if(Sub_Department_ID != null && Sub_Department_ID != '' && Supplier_ID != null && Supplier_ID != ''){
			document.getElementById("Item_ID").value = Item_ID;
			document.getElementById("Item_Name").value = Item_Name;
			document.getElementById("Quantity").value = '';
			document.getElementById("Quantity").focus();
			if(window.XMLHttpRequest){
				myObjectGetItem = new XMLHttpRequest();
			}else if(window.ActiveXObject){
				myObjectGetItem = new ActiveXObject('Micrsoft.XMLHTTP');
				myObjectGetItem.overrideMimeType('text/xml');
			}
			
			myObjectGetItem.onreadystatechange = function (){
				data345 = myObjectGetItem.responseText;
				if (myObjectGetItem.readyState == 4) {
					
					/*var feedback = data345;
					if(feedback == 'yes'){

					}else{

					}*/
				}
			}; //specify name of function that will handle server response........
			
			myObjectGetItem.open('GET','Return_Inward_Check_Item.php?Item_ID='+Item_ID,true);
			myObjectGetItem.send();
		}else{
			if(Sub_Department_ID=='' || Sub_Department_ID == null){
				document.getElementById("Sub_Department_ID").focus();
				document.getElementById("Sub_Department_ID").style = 'border: 3px solid red';
			}else{
				document.getElementById("Sub_Department_ID").style = 'border: 3px';
			}
			if(Supplier_ID=='' || Supplier_ID == null){
				document.getElementById("Supplier_ID").focus();
				document.getElementById("Supplier_ID").style = 'border: 3px solid red';
			}else{
				document.getElementById("Supplier_ID").style = 'border: 3px';
			}
		}
		Get_Balance(Item_ID);
	}
</script>

<script type="text/javascript">
	function Get_Balance(Item_ID){
		var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
		if(window.XMLHttpRequest) {
		    myObject_Get_Balance = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObject_Get_Balance = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObject_Get_Balance.overrideMimeType('text/xml');
		}
	    
		myObject_Get_Balance.onreadystatechange = function (){
		    data98 = myObject_Get_Balance.responseText;
		    if (myObject_Get_Balance.readyState == 4) {
				document.getElementById('Balance').value = data98;
		    }
		}; //specify name of function that will handle server response........
		myObject_Get_Balance.open('GET','Return_Outward_Get_Balance.php?Item_ID='+Item_ID+'&Sub_Department_ID='+Sub_Department_ID,true);
		myObject_Get_Balance.send();
	}
</script>


<script type='text/javascript'>
	function Submit_Outward_Function(Outward_ID){				
	    document.location = 'Submit_Return_Outward.php?Outward_ID='+Outward_ID;
	}
</script>
<script>
	function Confirm_Submit_Outward(){
		var Outward_ID = '<?php echo $Outward_ID; ?>';
		if(window.XMLHttpRequest){
			myObjectCheckItemNumber = new XMLHttpRequest();
		}else if(window.ActiveXObject){
			myObjectCheckItemNumber = new ActiveXObject('Micrsoft.XMLHTTP');
			myObjectCheckItemNumber.overrideMimeType('text/xml');
		}
		
		myObjectCheckItemNumber.onreadystatechange = function (){
			data200 = myObjectCheckItemNumber.responseText;
			if (myObjectCheckItemNumber.readyState == 4) {
				var feedback = data200;
				if (feedback == 'Yes'){
					var r = confirm("Are you sure you want to submit this return outward?\n\nClick OK to proceed");
					if(r == true){
						Submit_Outward_Function(Outward_ID);
					}
				}else{
					alert("This return outward may either already submitted or contains no items\n");
				}
			}
		}; //specify name of function that will handle server response........
		
		myObjectCheckItemNumber.open('GET','Return_Outward_Check_Number_Of_Items.php',true);
		myObjectCheckItemNumber.send();
		//}
	}
</script>
<!--filtering services against categories-->
<script type="text/javascript" language="javascript">
    function getItemList(Item_Category_Name) {
      if(window.XMLHttpRequest) {
    mm = new XMLHttpRequest();
      }
      else if(window.ActiveXObject){ 
    mm = new ActiveXObject('Micrsoft.XMLHTTP');
    mm.overrideMimeType('text/xml');
      }
      //getPrice();
      var ItemListType = document.getElementById('Type').value;
      getItemListType(ItemListType);
     document.getElementById('BalanceNeeded').value =''; 
     document.getElementById('BalanceStoreIssued').value = '' ;
      mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
      mm.open('GET','GetItemList.php?Item_Category_Name='+Item_Category_Name,true);
      mm.send();
  }
    function AJAXP() {
  var data1 = mm.responseText; 
  document.getElementById('Item_Name').innerHTML = data1; 
    }
</script>

<script type="text/javascript" language="javascript">
    function getItemListType(Type) {
    var Item_Category_Name = document.getElementById('Item_Category').value;
     if(window.XMLHttpRequest) {
    mm = new XMLHttpRequest();
     }
       else if(window.ActiveXObject){ 
       mm = new ActiveXObject('Micrsoft.XMLHTTP');
       mm.overrideMimeType('text/xml');
     }
      
    //   //getPrice();
    document.getElementById('BalanceNeeded').value ='a'; 
    document.getElementById('BalanceStoreIssued').value = 'v' ;
    mm.onreadystatechange= AJAXP2; //specify name of function that will handle server response....
      mm.open('GET','GetItemListType.php?Item_Category_Name='+Item_Category_Name+'&Type='+Type,true);
      mm.send();
  }
    function AJAXP2() {
  var data2 = mm.responseText; 
  document.getElementById('Item_Name').innerHTML = data2; 
    }
</script>
<!-- end of filtering-->

<script type="text/javascript">
	function Get_Details(){
		var Return_Type = document.getElementById("Return_Type").value;
		if(window.XMLHttpRequest) {
		    myObject_Get_Details = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    myObject_Get_Details = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObject_Get_Details.overrideMimeType('text/xml');
		}
		//alert(data);
	    
		myObject_Get_Details.onreadystatechange = function (){
			    data99 = myObject_Get_Details.responseText;
			    if (myObject_Get_Details.readyState == 4) {
				//document.getElementById('Approval').readonly = 'readonly';
				document.getElementById('Receiver_Area').innerHTML = data99;
			    }
			}; //specify name of function that will handle server response........
		myObject_Get_Details.open('GET','Inward_Outward_Get_Details.php?Return_Type='+Return_Type,true);
		myObject_Get_Details.send();
	}
</script>
<script>
	function getItemsList(Item_Category_ID){
		document.getElementById("Search_Value").value = ''; 
		document.getElementById("Item_Name").value = '';
		document.getElementById("Item_ID").value = '';
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
	function Get_Selected_Item_Warning() {
		var Item_Name = document.getElementById("Item_Name").value;
		if (Item_Name != '' && Item_Name != null) {
			alert("Process Fail!!\n"+Item_Name+" already available from the selected items list\nYou can edit quantity, edit item remark or remove selected item");
		}else{
			alert("Process Fail!!\nSelected item already available from the selected items list\nYou can edit quantity, edit item remark or remove selected item");
		}
	}
	
</script>
  
<script>
	function updateStoreNeedMenu2() {
		var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
		if(window.XMLHttpRequest) {
		    myObjectGetStoreNeed = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    myObjectGetStoreNeed = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectGetStoreNeed.overrideMimeType('text/xml');
		}
	    
		myObjectGetStoreNeed.onreadystatechange = function (){
			data2990 = myObjectGetStoreNeed.responseText;
			if (myObjectGetStoreNeed.readyState == 4) {
			    document.getElementById('Sub_Department_ID_Area').innerHTML = data2990;
			}
		}; //specify name of function that will handle server response........
		myObjectGetStoreNeed.open('GET','Store_Ordering_Get_Sub_Department_ID.php?Sub_Department_ID='+Sub_Department_ID,true);
		myObjectGetStoreNeed.send();
	}
</script>
 

<script>	
	function Get_Selected_Item(){
		var Item_ID = document.getElementById("Item_ID").value;
		var Quantity = document.getElementById("Quantity").value;
		var Item_Remark = document.getElementById("Item_Remark").value;
		var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
		var Supplier_ID = document.getElementById("Supplier_ID").value;

		if (Item_ID != '' && Item_ID != null && Quantity != '' && Quantity != null && Sub_Department_ID != null && Sub_Department_ID != '' && Supplier_ID != null && Supplier_ID != '') {
			if(window.XMLHttpRequest){
				my_Object_Get_Selected_Item = new XMLHttpRequest();
			}else if(window.ActiveXObject){
				my_Object_Get_Selected_Item = new ActiveXObject('Micrsoft.XMLHTTP');
				my_Object_Get_Selected_Item.overrideMimeType('text/xml');
			}
			my_Object_Get_Selected_Item.onreadystatechange = function (){
				dataT = my_Object_Get_Selected_Item.responseText;
				if (my_Object_Get_Selected_Item.readyState == 4) {
					document.getElementById('Items_Fieldset_List').innerHTML = dataT;
					alert("Item Added Successfully");
					document.getElementById("Item_ID").value = '';
					document.getElementById("Item_Name").value = '';
					Update_Store_Issued();
					Update_Receiving_Supplier();
					Update_Submit_Button();
					Update_Transaction_Date();
				}
			}; //specify name of function that will handle server response........
			
			my_Object_Get_Selected_Item.open('GET','Outwards_Add_Selected_Item.php?Item_ID='+Item_ID+'&Quantity='+Quantity+'&Item_Remark='+Item_Remark+'&Sub_Department_ID='+Sub_Department_ID+'&Supplier_ID='+Supplier_ID,true);
			my_Object_Get_Selected_Item.send();
		    
		}else if ((Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) && Quantity != '' && Quantity != null){
			alertMessage();
		}else{
			if(Quantity=='' || Quantity == null){
				document.getElementById("Quantity").focus();
				document.getElementById("Quantity").style = 'border: 3px solid red';
			}else{
				document.getElementById("Quantity").style = 'border: 3px';
			}
			if(Sub_Department_ID=='' || Sub_Department_ID == null){
				document.getElementById("Sub_Department_ID").focus();
				document.getElementById("Sub_Department_ID").style = 'border: 3px solid red';
			}else{
				document.getElementById("Sub_Department_ID").style = 'border: 3px';
			}
			if(Supplier_ID=='' || Supplier_ID == null){
				document.getElementById("Supplier_ID").focus();
				document.getElementById("Supplier_ID").style = 'border: 3px solid red';
			}else{
				document.getElementById("Supplier_ID").style = 'border: 3px';
			}
		}
	}
</script>
<script>
	function alertMessage(){
		alert("Please Select Item First");
		document.getElementById("Quantity").value = '';
	}
</script>


<script>
	function Confirm_Remove_Item(Item_Name,Outward_Item_ID){
		var Confirm_Message = confirm("Are you sure you want to remove \n"+Item_Name);
		
		if (Confirm_Message == true) {
			if(window.XMLHttpRequest) {
				My_Object_Remove_Item = new XMLHttpRequest();
			}else if(window.ActiveXObject){ 
				My_Object_Remove_Item = new ActiveXObject('Micrsoft.XMLHTTP');
				My_Object_Remove_Item.overrideMimeType('text/xml');
			}
				
			My_Object_Remove_Item.onreadystatechange = function (){
				data6 = My_Object_Remove_Item.responseText;
				if (My_Object_Remove_Item.readyState == 4) {
					document.getElementById('Items_Fieldset_List').innerHTML = data6;
				}
			}; //specify name of function that will handle server response........
				
			My_Object_Remove_Item.open('GET','Store_Ordering_Remove_Item_From_List.php?Outward_Item_ID='+Outward_Item_ID,true);
			My_Object_Remove_Item.send();
		}
	}
</script>

<script type="text/javascript">
	function Calculate_Quantity(){
		var Items_Quantity = document.getElementById("Items_Quantity").value;
		var Cont_Quantity = document.getElementById("Cont_Quantity").value;
		var Quantity = document.getElementById("Quantity").value = '';

		if(Items_Quantity != null && Items_Quantity != '' && Cont_Quantity != null && Cont_Quantity != ''){
			document.getElementById("Quantity").value = (Items_Quantity * Cont_Quantity);
		}
	}
</script>


<script type="text/javascript">
	function Clear_Quantity(){
		Items_Quantity = document.getElementById("Items_Quantity").value = document.getElementById("Quantity").value;
		document.getElementById("Cont_Quantity").value = 1;
	}
</script>


<script type="text/javascript">
	function Get_Last_Price(Item_ID){
		if(window.XMLHttpRequest){
			myObjectGetPrice = new XMLHttpRequest();
		}else if(window.ActiveXObject){
			myObjectGetPrice = new ActiveXObject('Micrsoft.XMLHTTP');
			myObjectGetPrice.overrideMimeType('text/xml');
		}
		
		myObjectGetPrice.onreadystatechange = function (){
			data_Last_Price = myObjectGetPrice.responseText;
			if (myObjectGetPrice.readyState == 4) {
				document.getElementById("Last_Buying_Price").value = data_Last_Price;
			}
		}; //specify name of function that will handle server response........
		
		myObjectGetPrice.open('GET','Grn_Get_Last_Price.php?Item_ID='+Item_ID,true);
		myObjectGetPrice.send();
	}
</script>

<fieldset>   
        <center>
            <table width=100%>
                <tr>
			<td width=25%>
				<table width=100%>
					<tr>
						<td style='text-align: center;'>
							<select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
							    <option selected='selected'></option>
							    <?php
								$data = mysqli_query($conn,"select Item_Category_Name, Item_Category_ID
													from tbl_item_category WHERE Category_Type = 'Pharmacy'
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
							<fieldset style='overflow-y: scroll; height: 305px;' id='Items_Fieldset'>
							
							<table width=100%>
							    <?php
								$result = mysqli_query($conn,"SELECT Product_Name, Item_ID FROM tbl_items where Item_Type = 'Pharmacy' order by Product_Name limit 200");
								while($row = mysqli_fetch_array($result)){
								    echo "<tr>
									<td style='color:black; border:2px solid #ccc;text-align: left;'>"; ?>
									    
									    <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);">
								       
								       <?php
									echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='".$row['Item_ID']."'>".$row['Product_Name']."</label></td></tr>";
								}
							    ?> 
							</table>
							</fieldset>		
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table width=100%>
					<tr>
						<td>
							<table width = 100%>
								<tr>
									<td id='Item_Name_Label'>Item Name</td>
									<td id='UOM_Label' style="text-align: center;">UoM</td>
									<td id='Quantity_Issued_Label' style="text-align: center;">Quantity Issued</td>
									<td id='Balance_Label'>Balance</td> 
									<td id='Remark_Label'>Remark</td> 
								</tr>
								<tr>
									<td>
										<input type='text' name='Item_Name' id='Item_Name' size=20 placeholder='Item Name' readonly='readonly' required='required'>
										<input type='hidden' name='Item_ID' id='Item_ID' value=''>
									</td>
									<td width="12%">
										<input type='text' style="text-align: center;" name='UOM' id='UOM' autocomplete='off' readonly='readonly' size=10 placeholder='OoM' onchange='numberOnly(this); Clear_Quantity();' onkeyup='numberOnly(this); Clear_Quantity();' onkeypress='numberOnly(this); Clear_Quantity();' oninput='numberOnly(this); Clear_Quantity();' onkeypress='numberOnly(this); Clear_Quantity();' required='required'>
									</td>
									<td width="10%">
										<input type='text' name='Quantity' id='Quantity' size=10 placeholder='Quantity' autocomplete="off">
									</td>
									<td width="10%">
										<input type='text' name='Balance' id='Balance' size=10 placeholder='Balance' readonly='readonly'>
									</td>
									<td width="10%">
										<input type='text' name='Item_Remark' id='Item_Remark' size=30 placeholder='Item Remark'>
									</td>
									<td style="text-align: center; width: 5%;" id='Add_Button_Area'>
										<!--<input type='submit' name='submit' id='submit' value='Add' class='art-button-green'>-->
										<input type='button' name='submit' id='submit' value='ADD' class='art-button-green' onclick='Get_Selected_Item()'>
										<input type='hidden' name='Add_Requisition_Form' value='true'/>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<!--<iframe width='100%' src='requisition_items_Iframe.php?Outward_ID=<?php echo $Outward_ID; ?>' width='100%' height=250px></iframe>-->
							<fieldset style='overflow-y: scroll; height: 299px; background-color:silver' id='Items_Fieldset_List'>
								<?php
									echo '<center><table width = "100%">';
									echo '<tr><td width=4% style="text-align: center; background-color:silver;color:black">Sn</td>
										    <td style="background-color:silver;color:black">Item Name</td>
											<td width=10% style="text-align: center;">UoM</td>
											<td width=10% style="text-align: center;">Qty Returned</td>
											<td width=18% style="text-align: left;">Remark</td>
											<td style="text-align: center;" width="7%">Remove</td></tr>';
									
									
									$select_Transaction_Items = mysqli_query($conn,"select roi.Outward_Item_ID, roi.Outward_ID, itm.Product_Name, roi.Quantity_Returned, roi.Item_Remark, itm.Unit_Of_Measure
																				from tbl_return_outward_items roi, tbl_items itm where
																				itm.Item_ID = roi.Item_ID and
																				roi.Outward_ID ='$Outward_ID'") or die(mysqli_error($conn)); 
								    $nm = mysqli_num_rows($select_Transaction_Items);
								    if($nm > 0){
										$Temp=1;
										while($row = mysqli_fetch_array($select_Transaction_Items)){
											echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
											echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
											echo "<td><input type='text' value='".$row['Unit_Of_Measure']."' style='text-align: center;' readonly='readonly'></td>";
											echo "<td><input type='text' value='".$row['Quantity_Returned']."' style='text-align: center;'></td>";
											echo "<td><input type='text' id='Item_Remark_Seved' name='Item_Remark_Seved' value='".$row['Item_Remark']."' onclick='Update_Item_Remark(".$row['Outward_ID'].",this.value)' onkeyup='Update_Item_Remark(".$row['Outward_ID'].",this.value)'></td>";
										?>
											<td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Outward_Item_ID']; ?>)'></td>
										<?php
										    echo "</tr>";
										    $Temp++;
										}
									}
									echo '</table>';
								?>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td id='Submit_Button_Area' style='text-align: right;'>
							<?php
								if(isset($_SESSION['General_Outward_ID'])){
									?>
										<input type='button' class='art-button-green' value='SUBMIT OUTWARD TRANSACTION' onclick='Confirm_Submit_Outward()'>
									<?php
								}
							?>
						</td>
					</tr>
				</table>
				
			</td>
                </tr>
	    </table>
        </center>
</fieldset>
<?php
  include("./includes/footer.php");
?>