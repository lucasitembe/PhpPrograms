<script src='js/functions.js'></script>
<?php
        include("./includes/header.php");
        include("./includes/connection.php");
        $counter = 0;
        
	//get employee id
	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}
	
	
	if(isset($_SESSION['Selected_Store_Order_ID'])){
	    $Selected_Store_Order_ID = $_SESSION['Selected_Store_Order_ID'];
	    include("./procurement_cache_store_order_multi_supplier.php");
	    //unset($_SESSION['Selected_Store_Order_ID']);

        $select = mysqli_query($conn,"SELECT sd.Sub_Department_Name, sd.Sub_Department_ID
                               FROM tbl_store_orders so, tbl_sub_department sd
                               WHERE so.Store_Order_ID = '$Selected_Store_Order_ID' AND
                               so.Sub_Department_ID = sd.Sub_Department_ID") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0){
            while($data = mysqli_fetch_array($select)){
                $Sub_Department_Name = $data['Sub_Department_Name'];
                $Sub_Department_ID = $data['Sub_Department_ID'];
            }
        }else{
            $Sub_Department_Name = '';
            $Sub_Department_ID = 0;
        }
	}
	
	if(isset($_SESSION['Purchase_Order_ID'])){
		unset($_SESSION['Purchase_Order_ID']);
	}

	//get employee name
	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Employee_Name = 'Unknown Employee';
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
		if(isset($_SESSION['userinfo']['Procurement_Works'])) {
			if($_SESSION['userinfo']['Procurement_Works'] != 'yes'){
				header("Location: ./index.php?InvalidPrivilege=yes");
			}else{
				@session_start();
				if(!isset($_SESSION['Procurement_Supervisor'])){ 
				    header("Location: ./deptsupervisorauthentication.php?SessionCategory=Procurement&InvalidSupervisorAuthentication=yes");
				}
			}
		}else{
			header("Location: ./index.php?InvalidPrivilege=yes");
		}
	}else{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }

	if(isset($_SESSION['userinfo']) && isset($_SESSION['Procurement_Autentication_Level']) && $_SESSION['Procurement_Autentication_Level'] == 1){
		if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){
			echo '<input type="button" name="Load_Store_Order" id="Load_Store_Order" class="art-button-green" onclick="Load_Store_Orders()" value="LOAD STORE ORDER">';
		}
	
		if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){ 
			echo "<a href='procurementpendingorders.php?ProcurementPendingOrders=ProcurementPendingOrdersThisPage' class='art-button-green'>PENDING ORDERS</a>";
		}
	}else{
		if(isset($_SESSION['Procurement_Autentication_Level']) && $_SESSION['Procurement_Autentication_Level'] != 100){
			//calculate pending orders based on assigned level
			$before_assigned_level = $_SESSION['Procurement_Autentication_Level'] - 1;
			$p_orders = mysqli_query($conn,"select po.Purchase_Order_ID from tbl_purchase_order po
            where po.Order_Status = 'submitted' and po.Approval_Level = '$before_assigned_level'
            AND (SELECT count(*) FROM tbl_purchase_order_items poi WHERE poi.Purchase_Order_ID = po.Purchase_Order_ID) > 0") or die(mysqli_error($conn));
			$p_num = mysqli_num_rows($p_orders);
			
			if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){
				echo "<a href='approvalsprocurementpendingorders.php?ProcurementPendingOrders=ProcurementPendingOrdersThisPage' class='art-button-green'>
						PENDING ORDERS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<span style='background-color: red; border-radius: 8px; color: white; padding: 6px;'>".$p_num."</span>
						</a>";
			}
		}
	}
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){ 
			echo "<a href='previousorders.php?PreviousOrder=PreviousOrderThisPage' class='art-button-green'>PREVIOUS ORDERS</a>";
		}
	}
	
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){ 
			echo "<a href='procurementworkspage.php?ProcurementWork=ProcurementWorkThisPage' class='art-button-green'>BACK</a>";
		}
	}    
?>

<script type="text/javascript">
    function Load_Store_Orders(){
        document.location = 'control_purchase_order_sessions.php?New_Purchase_Order=True&NPO=True&PurchaseOrder=PurchaseOrderThisPage';
    }
</script>
<script type="text/javascript" language="javascript">
	function getItemList(Item_Category_Name) {
		if(window.XMLHttpRequest) {
		    mm = new XMLHttpRequest();
		}
		else if(window.ActiveXObject){ 
		    mm = new ActiveXObject('Micrsoft.XMLHTTP');
		    mm.overrideMimeType('text/xml');
		}
		
		var ItemListType = document.getElementById('Type').value;
		getItemListType(ItemListType);
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

<?php
	//get order information if and only if inserted
	if(isset($_SESSION['Purchase_Order_ID'])){
		$Purchase_Order_ID = $_SESSION['Purchase_Order_ID'];
		$select_Order_Details = mysqli_query($conn,"
						    select * from tbl_purchase_order po, tbl_purchase_order_items poi,tbl_supplier sup where
							po.Purchase_Order_ID = poi.Purchase_Order_ID and
								sup.Supplier_ID = po.Supplier_ID and
								po.Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));
		$no = mysqli_num_rows($select_Order_Details);
		if($no > 0){
			while($row = mysqli_fetch_array($select_Order_Details)){
				$Purchase_Order_ID = $row['Purchase_Order_ID'];
				$Order_Description = $row['Order_Description'];
				$Created_Date = $row['Created_Date'];
				$Supplier_Name = $row['Supplier_Name'];
			}
		}else{
			$Purchase_Order_ID = '';
			$Order_Description = '';
			$Created_Date = '';
			$Supplier_Name = '';
		}
		
	}else{
		$Purchase_Order_ID = 0;
	}


?>

<script>
	function getItemsList(Item_Category_ID){
		document.getElementById("Search_Value").value = ''; 
		document.getElementById("Item_Name").value = '';
		document.getElementById("Item_ID").value = '';
		document.getElementById("Price").value = '';
		//document.getElementById("Item_Remark").value = '';
		
		if(window.XMLHttpRequest) {
		    myObjectGetItemList = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    myObjectGetItemList = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectGetItemList.overrideMimeType('text/xml');
		}
		//alert(data);
	    
		myObjectGetItemList.onreadystatechange = function (){
			    data = myObjectGetItemList.responseText;
			    if (myObjectGetItemList.readyState == 4) {
				//document.getElementById('Approval').readonly = 'readonly';
				document.getElementById('Items_Fieldset').innerHTML = data;
			    }
			}; //specify name of function that will handle server response........
		myObjectGetItemList.open('GET','Get_List_Of_Requisition_Items_List.php?Item_Category_ID='+Item_Category_ID,true);
		myObjectGetItemList.send();
	}
</script>
<script>    
	function getItemsListFiltered(Item_Name){
		document.getElementById("Item_Name").value = '';
		document.getElementById("Item_ID").value = '';
		document.getElementById("Price").value = '';
		//document.getElementById("Item_Remark").value = '';
		var Item_Category_ID = document.getElementById("Item_Category_ID").value;
		if (Item_Category_ID == '' || Item_Category_ID == null) {
		    Item_Category_ID = 'All';
		}
		
		if(window.XMLHttpRequest) {
		    myObjectGetItemListFiltered = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    myObjectGetItemListFiltered = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectGetItemListFiltered.overrideMimeType('text/xml');
		}
	    
		myObjectGetItemListFiltered.onreadystatechange = function (){
			    data = myObjectGetItemListFiltered.responseText;
			    if (myObjectGetItemListFiltered.readyState == 4) {
				//document.getElementById('Approval').readonly = 'readonly';
				document.getElementById('Items_Fieldset').innerHTML = data;
			    }
			}; //specify name of function that will handle server response........
		myObjectGetItemListFiltered.open('GET','Get_List_Of_Requisition_Filtered_Items.php?Item_Category_ID='+Item_Category_ID+'&Item_Name='+Item_Name,true);
		myObjectGetItemListFiltered.send();
	}
</script>

<script>
	function Get_Item_Name(Item_Name,Item_ID){
		var Temp = '';
		if(window.XMLHttpRequest) {
		    myObjectGetItemName = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObjectGetItemName = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectGetItemName.overrideMimeType('text/xml');
		}
		
		myObjectGetItemName.onreadystatechange = function (){
			data22 = myObjectGetItemName.responseText;
			if (myObjectGetItemName.readyState == 4) {
			    Temp = data22;
			    if (Temp == 'Yes'){
					document.getElementById("Item_Name").style.backgroundColor = '#037CB0';
					
					document.getElementById("Quantity").style.backgroundColor = '#037CB0';
					document.getElementById("Quantity").value = '';
					document.getElementById("Quantity_Label").style.color = '#037CB0';
					document.getElementById("Quantity_Label").innerHTML = '<b>Quantity</b>';
					document.getElementById("Quantity").setAttribute("ReadOnly","ReadOnly");
					
					
					/*document.getElementById("Item_Remark").style.backgroundColor = '#037CB0';
					document.getElementById("Item_Remark").value = '';
					document.getElementById("Remark_Label").style.color = '#037CB0';
					document.getElementById("Remark_Label").innerHTML = '<b>Item Remark</b>';
					document.getElementById("Item_Remark").setAttribute("ReadOnly","ReadOnly");*/
					
					document.getElementById("Price").style.backgroundColor = '#037CB0';
					document.getElementById("Price").value = '';
					document.getElementById("Price_Label").style.color = '#037CB0';
					document.getElementById("Price_Label").innerHTML = '<b>Price</b>';
					document.getElementById("Price").setAttribute("ReadOnly","ReadOnly");
					
					document.getElementById("Item_Name_Label").style.color = '#037CB0';
					document.getElementById("Item_Name_Label").innerHTML = '<b>This Item Already Added. Change the quantity / remark when needed</b>';
					
					//change add button to warning add button
					document.getElementById("Add_Button_Area").innerHTML = "<input type='button' name='submit' id='submit' value='ADD ITEM' class='art-button-green' onclick='Get_Selected_Item_Warning()'>";
			    }else{
					document.getElementById("Item_Name").style.backgroundColor = 'white';
					document.getElementById("Item_Name_Label").style.color = 'black';
					document.getElementById("Item_Name_Label").innerHTML = '';
					
					document.getElementById("Quantity").style.backgroundColor = 'white';
					document.getElementById("Quantity").value = '';
					//document.getElementById("Quantity").focus();
					document.getElementById("Quantity").removeAttribute("ReadOnly");
					document.getElementById("Quantity_Label").innerHTML = 'Quantity';
					document.getElementById("Quantity_Label").style.color = 'black';
					
					/*document.getElementById("Balance").style.backgroundColor = 'white';
					document.getElementById("Balance_Label").innerHTML = 'Balance';
					document.getElementById("Balance_Label").style.color = 'black';*/
					
					document.getElementById("Price").style.backgroundColor = 'white';
					document.getElementById("Price").removeAttribute("ReadOnly");
					document.getElementById("Price_Label").innerHTML = 'Price';
					document.getElementById("Price_Label").style.color = 'black';
					
					/*document.getElementById("Item_Remark").style.backgroundColor = 'white';
					document.getElementById("Item_Remark").value = '';
					document.getElementById("Item_Remark").removeAttribute("ReadOnly");
					document.getElementById("Remark_Label").innerHTML = 'Item Remark';
					document.getElementById("Remark_Label").style.color = 'black';*/
					
					//change warning add button to add button
					document.getElementById("Add_Button_Area").innerHTML = "<input type='button' name='submit' id='submit' value='ADD ITEM' class='art-button-green' onclick='Get_Selected_Item()'>";
			    }
			}
		}; //specify name of function that will handle server response........
		myObjectGetItemName.open('GET','Purchase_Order_Check_Item_Selected.php?Item_ID='+Item_ID,true);
		myObjectGetItemName.send();
		
		
		document.getElementById("Item_Name").value = Item_Name;
		document.getElementById("Item_ID").value = Item_ID;
		document.getElementById("Container").value = '';
		document.getElementById("Items_per_Container").value = '';
		//document.getElementById("Quantity").focus();
		
		if (Item_ID != null && Item_ID != '') {
			Get_Last_Buying_Price(Item_ID);
			Get_Balance();
		}
	}
</script>

<script type="text/javascript">
	function Get_Last_Buying_Price(Item_ID){
		if(window.XMLHttpRequest) {
			myObjectGetLastPrice = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
			myObjectGetLastPrice = new ActiveXObject('Micrsoft.XMLHTTP');
			myObjectGetLastPrice.overrideMimeType('text/xml');
		}
			
		myObjectGetLastPrice.onreadystatechange = function (){
			data8080 = myObjectGetLastPrice.responseText;
			if (myObjectGetLastPrice.readyState == 4) {
				document.getElementById("Price").value = data8080
			}
		}; //specify name of function that will handle server response........
			
		myObjectGetLastPrice.open('GET','Get_Item_Last_Buying_Price.php?Item_ID='+Item_ID,true);
		myObjectGetLastPrice.send();
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
	function Get_Balance(){
		var Item_ID = document.getElementById("Item_ID").value;
		
		if(window.XMLHttpRequest) {
			myObjectGetBalance = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
			myObjectGetBalance = new ActiveXObject('Micrsoft.XMLHTTP');
			myObjectGetBalance.overrideMimeType('text/xml');
		}
			
		myObjectGetBalance.onreadystatechange = function (){
			data80 = myObjectGetBalance.responseText;
			if (myObjectGetBalance.readyState == 4) {
				//alert(data80)
				//document.getElementById('Balance').value = data80;
			}
		}; //specify name of function that will handle server response........
			
		myObjectGetBalance.open('GET','Get_Item_Expected_Balance.php?Item_ID='+Item_ID+'&ControlValue=Storage',true);
		myObjectGetBalance.send();
	}
</script>

<script>
	function alertMessage(){
		alert("Please Select Item First");
	}
</script>

<script>
	function Get_Item_Balance(Item_ID){
		var ControlValue = 'Revenue_Center';
		if(window.XMLHttpRequest) {
		    Get_Balance_My_Object = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    Get_Balance_My_Object = new ActiveXObject('Micrsoft.XMLHTTP');
		    Get_Balance_My_Object.overrideMimeType('text/xml');
		}
	    
		Get_Balance_My_Object.onreadystatechange = function (){
			Get_Balance_Data = Get_Balance_My_Object.responseText;
			if (Get_Balance_My_Object.readyState == 4) {
				//document.getElementById('Balance').value = Get_Balance_Data;
			}
		}; //specify name of function that will handle server response........
		Get_Balance_My_Object.open('GET','Get_Item_Balance.php?Item_ID='+Item_ID+'&ControlValue='+ControlValue,true);
		Get_Balance_My_Object.send();
	}
</script>

<script>
	function updateStoreIssueMenu() {
		if(window.XMLHttpRequest) {
		    myRequisitionObject = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myRequisitionObject = new ActiveXObject('Micrsoft.XMLHTTP');
		    myRequisitionObject.overrideMimeType('text/xml');
		}
		
		myRequisitionObject.onreadystatechange = function (){
			data20 = myRequisitionObject.responseText;
			if (myRequisitionObject.readyState == 4) {
			    document.getElementById('Supplier_ID_Area').innerHTML = data20;
			}
		}; //specify name of function that will handle server response........
		myRequisitionObject.open('GET','Get_Purchase_Order_Store_Issued.php',true);
		myRequisitionObject.send();
	}
</script>


<script>
	function updateStoreRequestingMenu() {
		if(window.XMLHttpRequest) {
		    myRequisitionObject = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myRequisitionObject = new ActiveXObject('Micrsoft.XMLHTTP');
		    myRequisitionObject.overrideMimeType('text/xml');
		}
		
		myRequisitionObject.onreadystatechange = function (){
			data20 = myRequisitionObject.responseText;
			if (myRequisitionObject.readyState == 4) {
			    document.getElementById('Store_Requesting_Area').innerHTML = data20;
			}
		}; //specify name of function that will handle server response........
		myRequisitionObject.open('GET','Update_Purchase_Order_Store_Requesting.php',true);
		myRequisitionObject.send();
	}
</script>

<script>	
	function Get_Selected_Item(){
		var Item_ID = document.getElementById("Item_ID").value;
		var Quantity = document.getElementById("Quantity").value;
		var Container = document.getElementById("Container").value;
		var Items_per_Container = document.getElementById("Items_per_Container").value;
		var Price = document.getElementById("Price").value;
		var Store_Need = document.getElementById("Store_Need").value;

		//var Item_Remark = document.getElementById("Item_Remark").value;
		
		if (Item_ID != '' && Item_ID != null && Quantity != '' && Quantity != null && Quantity!= '' && Price != null && Price!= '' && Store_Need != null && Store_Need != '') {
			if(window.XMLHttpRequest){
				my_Object_Get_Selected_Item = new XMLHttpRequest();
			}else if(window.ActiveXObject){
				my_Object_Get_Selected_Item = new ActiveXObject('Micrsoft.XMLHTTP');
				my_Object_Get_Selected_Item.overrideMimeType('text/xml');
			}
			
			my_Object_Get_Selected_Item.onreadystatechange = function (){
				data = my_Object_Get_Selected_Item.responseText;
				if (my_Object_Get_Selected_Item.readyState == 4) {
					document.getElementById('Items_Fieldset_List').innerHTML = data;
					document.getElementById("Item_Name").value = '';
					document.getElementById("Quantity").value = '';
					document.getElementById("Item_ID").value = '';
					document.getElementById("Price").value = '';
					//document.getElementById("Item_Remark").value = '';
					alert("Item Added Successfully");
					display_submit_button();
					updateStoreRequestingMenu();
					//updateStoreIssueMenu();
					//update_total();
					//update_Purchase_Number();
				}
			}; //specify name of function that will handle server response........
			
			my_Object_Get_Selected_Item.open('GET','Purchase_Order_Add_Selected_Item.php?Item_ID='+Item_ID+'&Quantity='+Quantity+'&Price='+Price+'&Container='+Container+'&Items_per_Container='+Items_per_Container+'&Store_Need='+Store_Need,true);
			my_Object_Get_Selected_Item.send();
		    
		}else if ((Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) && Quantity != '' && Quantity != null){
			alertMessage();
		}else{
			if(Item_ID=='' || Item_ID == null){
				document.getElementById("Item_Name").style = 'border: 3px solid red;';
			}

			if(Price=='' || Price == null){
				//document.getElementById("Price").focus();
				document.getElementById("Price").style = 'border: 3px solid red;';
			}

			if(Quantity=='' || Quantity == null){
				//document.getElementById("Quantity").focus();
				document.getElementById("Quantity").style = 'border: 3px solid red;';
			}

			if(Store_Need=='' || Store_Need == null){
				//document.getElementById("Store_Need").focus();
				document.getElementById("Store_Need").style = 'border: 3px solid red;';
			}
		}
	}
</script>

<script>
	function display_submit_button() {
		document.getElementById("Submit_Button_Area").innerHTML = "<input type='button' name='Submit_Purchase_Order' id='Submit_Purchase_Order' value='SUBMIT PURCHASE ORDER' class='art-button-green' onclick='Confirm_Submit_Order()'>";
	}
</script>


<script>
	function Confirm_Submit_Purchase_Order() {
		//check if order contain atleast one item
		if(window.XMLHttpRequest) {
			myObjectCheckItemNumber = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
			myObjectCheckItemNumber = new ActiveXObject('Micrsoft.XMLHTTP');
			myObjectCheckItemNumber.overrideMimeType('text/xml');
		}
			
		myObjectCheckItemNumber.onreadystatechange = function (){
			data66 = myObjectCheckItemNumber.responseText;
			if (myObjectCheckItemNumber.readyState == 4) {
				var feedback = data66;
				if (feedback == 'Yes') {
					var Confirm_Message = confirm("Are you sure you want to submit this order");
						if (Confirm_Message == true) {
							Submit_Order();
						}
				}else{
					alert("Process fail!!!\nOrder must contain at least one item");
				}
			}
		}; //specify name of function that will handle server response........
			
		myObjectCheckItemNumber.open('GET','Check_Purchase_Order_Item_Number.php',true);
		myObjectCheckItemNumber.send();
	}
</script>

<script>
	function Submit_Order(){
		var Order_Description = document.getElementById("Order_Description").value;
		var Store_Need = document.getElementById("Store_Need").value;
        var Store_Order_ID = '<?php echo $Selected_Store_Order_ID; ?>';
		document.location = 'submit_order_multi_supplier.php?Store_Order_ID='+Store_Order_ID+'&Store_Need='+Store_Need+'&Order_Description='+Order_Description;
	}
</script>
<script>
	function Confirm_Remove_Item(Item_Name,Purchase_Cache_ID){
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
					update_total();
					updateStoreRequestingMenu();
					//update_Billing_Type();
					//Update_Claim_Form_Number();
				}
			}; //specify name of function that will handle server response........
				
			My_Object_Remove_Item.open('GET','Purchase_Order_Remove_Item_From_List.php?Purchase_Cache_ID='+Purchase_Cache_ID,true);
			My_Object_Remove_Item.send();
		}
	}
</script>

<script>    
	function update_total(){
		if(window.XMLHttpRequest) {
		    myObjectUpdateTotal = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    myObjectUpdateTotal = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectUpdateTotal.overrideMimeType('text/xml');
		}
		myObjectUpdateTotal.onreadystatechange = function (){
			data51 = myObjectUpdateTotal.responseText;
			if (myObjectUpdateTotal.readyState == 4) {
			    document.getElementById('Total_Area').innerHTML = data51;
			}
		}; //specify name of function that will handle server response........
		myObjectUpdateTotal.open('GET','Purchase_Order_Update_Total.php',true);
		myObjectUpdateTotal.send();
	}
</script>

<script>    
	function update_Purchase_Number(){
		if(window.XMLHttpRequest) {
		    myObjectUpdatePurchase = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    myObjectUpdatePurchase = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectUpdatePurchase.overrideMimeType('text/xml');
		}
		myObjectUpdatePurchase.onreadystatechange = function (){
			data151 = myObjectUpdatePurchase.responseText;
			if (myObjectUpdatePurchase.readyState == 4) {
			    document.getElementById('Purchase_Number').value = data151;
			}
		}; //specify name of function that will handle server response........
		myObjectUpdatePurchase.open('GET','Update_Purchase_Number.php',true);
		myObjectUpdatePurchase.send();
	}
</script>


<script>
	function updateOrder_Description(){
		var Order_Description = document.getElementById("Order_Description").value;
		
		if(window.XMLHttpRequest){
			myObjectUpdateDescription = new XMLHttpRequest();
		}else if(window.ActiveXObject){
			myObjectUpdateDescription = new ActiveXObject('Micrsoft.XMLHTTP');
			myObjectUpdateDescription.overrideMimeType('text/xml');
		}
		myObjectUpdateDescription.onreadystatechange = function (){
			data50 = myObjectUpdateDescription.responseText;
			if (myObjectUpdateDescription.readyState == 4) {
				//document.getElementById('Order_Description').value = data50;
			}
		}; //specify name of function that will handle server response........
		
		myObjectUpdateDescription.open('GET','Change_Order_Description.php?Order_Description='+Order_Description,true);
		myObjectUpdateDescription.send();		
	}
</script>



<form action='#' method='post' name='myForm' id='myForm' >
<br/>

<style>
		table,tr,td{
		border-collapse:collapse !important;
		border:none !important;
	}
	
</style> 

<fieldset>
        <legend align='right'>
            <b>Purchase Order ~ <?php if(isset($_SESSION['Procurement_ID'])){ echo $Sub_Department_Name; }?> ~ Multi Supplier</b>
        </legend>
        <table width=100%>
		<tr>
			<td width='10%' style='text-align: right;'>Purchase Number</td>
			<td width='10%' id='Purchase_Number_Area'>
				<?php if(isset($_SESSION['Purchase_Order_ID'])){ ?>
					<input type='text' name='Purchase_Number'  id='Purchase_Number' readonly='readonly' value='<?php echo $_SESSION['Purchase_Order_ID']; ?>'>
				<?php }else{ ?>
					<input type='text' name='Purchase_Number'  id='Purchase_Number' value='new'>
				<?php } ?>
			</td>
			
			<td width='10%' style='text-align: right;'>Order Description</td>
			<td>
				<?php
					if(isset($_SESSION['Purchase_Order_ID'])){
						//get order description
						$Temp_Purchase_Order_ID = $_SESSION['Purchase_Order_ID'];
						$select_Order_Description = mysqli_query($conn,"select Order_Description from tbl_purchase_order
											where Purchase_Order_ID = '$Temp_Purchase_Order_ID'") or die(mysqli_error($conn));
						$num = mysqli_num_rows($select_Order_Description);
						if($num > 0){
							while($row = mysqli_fetch_array($select_Order_Description)){
								$Order_Description = $row['Order_Description'];
							}
						}else{
							$Order_Description = '';
						}
				?>
					<input type='text' name='Order_Description' id='Order_Description' value='<?php echo $Order_Description; ?>' onclick='updateOrder_Description()' onkeyup='updateOrder_Description()'>
				<?php }else{ ?>
					<input type='text' name='Order_Description' id='Order_Description'>
				<?php } ?>
			</td> 
			
			<td width='10%' style='text-align: right;'>Purchase Date</td>
			<td width='16%'>
				<?php if(isset($_SESSION['Purchase_Order_ID'])){
					//GET PURCHASE ORDER DATE
					$Temp_Purchase_Order_ID = $_SESSION['Purchase_Order_ID'];
					$select_date = mysqli_query($conn,"select Created_Date from tbl_purchase_Order where Purchase_Order_ID = '$Temp_Purchase_Order_ID'") or die(mysqli_error($conn));
					$num = mysqli_num_rows($select_date);
					if($num > 0){
						while($row = mysqli_fetch_array($select_date)){
							$Created_Date = $row['Created_Date'];
						}
					}else{
						$Created_Date = '';
					}
				?>
					<input type='text' readonly='readonly' name='Purchase_Date' id='Purchase_Date' value='<?php echo $Created_Date; ?>'>
				<?php }else{ ?>
					<input type='text' readonly='readonly' name='Purchase_Date' id='Purchase_Date'>
				<?php } ?>
			</td> 
		</tr>
		<tr>
			<td width='10%' style='text-align: right;'>Store Requesting</td>
			<td width='16%' id="Store_Requesting_Area">
				<select name='Store_Need' id='Store_Need'>
				<?php
					if(isset($_SESSION['Purchase_Order_ID'])){
						$Purchase_Order_ID = $_SESSION['Purchase_Order_ID'];

						//get current sub department
						$select = mysqli_query($conn,"select sd.Sub_Department_ID, sd.Sub_Department_Name from tbl_sub_department sd, tbl_purchase_order po where
									po.Sub_Department_ID = sd.Sub_Department_ID and
										po.Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));
						$no = mysqli_num_rows($select);
						if($no > 0){
							while($row = mysqli_fetch_array($select)){
								echo "<option value='".$row['Sub_Department_ID']."'>".$row['Sub_Department_Name']."</option>";
							}
						}else{
							//check if sub department id already selected
							$check_sub = mysqli_query($conn,"select Store_Need from tbl_purchase_cache where Employee_ID = '$Employee_ID' limit 1") or die(mysqli_error($conn));
							$num_rows = mysqli_num_rows($check_sub);
							if($num_rows > 0){
								while ($rw = mysqli_fetch_array($check_sub)) {
									$Store_Need = $rw['Store_Need'];
								}
								$get_sub_name = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Store_Need'") or die(mysqli_error($conn));
								$nmz = mysqli_num_rows($get_sub_name);
								if($nmz > 0){
									while ($rw2 = mysqli_fetch_array($get_sub_name)) {
										$Sub_Department_Name = $rw2['Sub_Department_Name'];
									}
								}else{
									$Sub_Department_Name = '';
								}
								echo "<option value='".$Store_Need."'>".$Sub_Department_Name."</option>";
							}else{
								//get list of stores
								$select = mysqli_query($conn,"select Sub_Department_Name, Sub_Department_ID from
										tbl_sub_department sdep, tbl_department dep where
											sdep.Department_ID = dep.Department_ID and
												dep.Department_Location = 'Storage And Supply' order by Sub_Department_Name") or die(mysqli_error($conn));
								
								$num = mysqli_num_rows($select);
								if($num > 0){
									echo "<option selected='selected'></option>";
									while($data = mysqli_fetch_array($select)){
										echo "<option value='".$data['Sub_Department_ID']."'>".$data['Sub_Department_Name']."</option>";
									}
								}
							}
						}
					}else{
						//check if sub department id already selected
						$check_sub = mysqli_query($conn,"select Store_Need from tbl_purchase_cache where Employee_ID = '$Employee_ID' limit 1") or die(mysqli_error($conn));
						$num_rows = mysqli_num_rows($check_sub);
						if($num_rows > 0){
							while ($rw = mysqli_fetch_array($check_sub)) {
								$Store_Need = $rw['Store_Need'];
							}
							$get_sub_name = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Store_Need'") or die(mysqli_error($conn));
							$nmz = mysqli_num_rows($get_sub_name);
							if($nmz > 0){
								while ($rw2 = mysqli_fetch_array($get_sub_name)) {
									$Sub_Department_Name = $rw2['Sub_Department_Name'];
								}
							}else{
								$Sub_Department_Name = '';
							}
							echo "<option value='".$Store_Need."'>".$Sub_Department_Name."</option>";
						}else{

							//get list of stores
							$select = mysqli_query($conn,"select Sub_Department_Name, Sub_Department_ID from
										tbl_sub_department sdep, tbl_department dep where
											sdep.Department_ID = dep.Department_ID and
												dep.Department_Location = 'Storage And Supply' order by Sub_Department_Name") or die(mysqli_error($conn));
							$num = mysqli_num_rows($select);
							if($num > 0){
								echo "<option selected='selected'></option>";
								while($data = mysqli_fetch_array($select)){
					?>
								<option value='<?php echo $data['Sub_Department_ID']; ?>'><?php echo $data['Sub_Department_Name']; ?></option>
					<?php
								}
							}
						}
					} //-------------
				?>
				</select>
			</td>
			
		<!--td style='text-align: right;'>Supplier</td>
		<td id='Supplier_ID_Area'-->
			
			<?php	
				/*if(isset($_SESSION['Purchase_Order_ID']) && $_SESSION['Purchase_Order_ID'] != '' && $_SESSION['Purchase_Order_ID'] != null){
					$Purchase_Order_ID = $_SESSION['Purchase_Order_ID'];
					//select Supplier_ID via session Purchase_Order_ID
					$select_purchase_id = mysqli_query($conn,"select Supplier_ID from tbl_purchase_order where
										Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));
					$no = mysqli_num_rows($select_purchase_id);
					if($no > 0){
						while($row = mysqli_fetch_array($select_purchase_id)){
							$Supplier_ID = $row['Supplier_ID'];
						}
					}else{
						$Supplier_ID = 0;
					}
					
					if($Supplier_ID != 0){
						//get supplier name
						$select_Supplier_Name = mysqli_query($conn,"select Supplier_Name from tbl_supplier where
											Supplier_ID = '$Supplier_ID'") or die(mysqli_error($conn));
						$no = mysqli_num_rows($select_Supplier_Name);
						if($no > 0){
							while($row = mysqli_fetch_array($select_Supplier_Name)){
								$Supplier_Name = ucfirst($row['Supplier_Name']);
							}
						}else{
							$Supplier_Name = '';
						}
					}else{
						$Supplier_Name = '';
					}
					?>
						<select name='Supplier_ID' id='Supplier_ID' required='required'>
							<option selected='selected' value='<?php echo $Supplier_ID; ?>'><?php echo ucfirst($Supplier_Name); ?></option>
						</select>
					<?php
				}else{
				}*/

                $select_Supplier = mysqli_query($conn,"select * from tbl_supplier") or die(mysqli_error($conn));
                $Supplier_Select_Option = "";
                while($row = mysqli_fetch_array($select_Supplier)){
                    $Supplier_Select_Option = $Supplier_Select_Option . "<option value='".$row['Supplier_ID']."'>".ucfirst($row['Supplier_Name'])."</option>";
                }
			?>
			
		<!--/td-->
		    <td style='text-align: right;'>Prepared By&nbsp;</td> 
			<td>
				<input type='text' readonly='readonly' value='<?php echo $Employee_Name; ?>'>
			</td>
		</tr>
        </table> 
</center>
</fieldset>
<?php
	$select_order_items = mysqli_query($conn,"SELECT itm.Product_Name, pc.Quantity_Required, pc.Purchase_Cache_ID, pc.Price,
                                              pc.Container_Qty, pc.Items_Per_Container, pc.Item_ID, pc.Store_Need,
                                              pc.Supplier_ID,
                                             (SELECT s.Supplier_Name FROM tbl_supplier s WHERE pc.Supplier_ID = s.Supplier_ID)
                                              as Supplier_Name
									   FROM tbl_purchase_cache pc, tbl_items itm
									   WHERE itm.Item_ID = pc.Item_ID AND
										     pc.Employee_ID ='$Employee_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select_order_items);
?>

<!--<iframe width='100%' src='requisition_items_Iframe.php?Purchase_Order_ID=<?php echo $Purchase_Order_ID; ?>' width='100%' height=250px></iframe>-->
<fieldset style='overflow-y: scroll; height: 260px;' id='Items_Fieldset_List'>
	<?php
         $sql_check_for_approval_process_result=mysqli_query($conn,"SELECT Supplier_ID FROM tbl_document_approval_control WHERE document_type='purchase_order' AND document_number='$Selected_Store_Order_ID'") or die(mysqli_error($conn));
                     if(mysqli_num_rows($sql_check_for_approval_process_result)>0){
                         $display_none="style='display:none'";                   
                       }else{
                           $display_none="";
                       }
		echo '<center><table width = 100% border=0>';
		echo '<tr>
                <td width=4% style="text-align: center;">Sn</td>
				<td>Item Name</td>
				<td width=14%>Supplier</td>
				<td width=7% style="text-align: center;">Containers</td>
				<td width=10% style="text-align: right;">Items per Container</td>
				<td width=7% style="text-align: right;">Quantity</td>
				<td width=7% style="text-align: right;">Balance</td>
				<td width=7% style="text-align: right;">Buying Price</td>
				<td width=7% style="text-align: right;">Sub Total</td>
				<td width=5%'.$display_none.'>Remove</td>
				<td width=5%>History</td></tr>';
		echo "<tr><td colspan='11'><hr></td></tr>";
		
		
		$Temp=1; $total = 0;
		while($row = mysqli_fetch_array($select_order_items)){ 
			$Item_ID = $row['Item_ID'];
			$Store_Need = $row['Store_Need'];

			//get item balance
			$get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Sub_Department_ID = '$Store_Need' and Item_ID = '$Item_ID'") or die(mysqli_error($conn));
			$n_get = mysqli_num_rows($get_balance);
			if($n_get > 0){
				while ($nget = mysqli_fetch_array($get_balance)) {
					$Item_Balance = $nget['Item_Balance'];
				}
			}else{
				$Item_Balance = 0;
			}

		    echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
		    echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
            //echo "<td><input type='text' readonly='readonly' value='".$row['Supplier_Name']."'></td>";
	?>		
			<td>
				<select id='Supplier_ID_<?php echo $row['Purchase_Cache_ID']; ?>'
                        onchange="Update_Supplier(this.value, '<?php echo $row['Purchase_Cache_ID']; ?>')">
                    <?php echo $Supplier_Select_Option; ?>
                </select>
                <script>
                    $(document).ready(function(){
                        $('#Supplier_ID_<?php echo $row['Purchase_Cache_ID']; ?>').val('<?php echo $row['Supplier_ID']; ?>');
                        Update_Supplier($('#Supplier_ID_<?php echo $row['Purchase_Cache_ID']; ?>').val(), '<?php echo $row['Purchase_Cache_ID']; ?>');
                    });
                </script>
			</td>
            <td>
				<input type='text' id='Container_<?php echo $row['Purchase_Cache_ID']; ?>' readonly="readonly" value='<?php echo $row['Container_Qty']; ?>' style='text-align: right;' oninput="Update_Quantity('<?php echo $row['Purchase_Cache_ID']; ?>')">
			</td>
			<td>
				<input type='text' id='Items_<?php echo $row['Purchase_Cache_ID']; ?>' readonly="readonly" value='<?php echo $row['Items_Per_Container']; ?>' style='text-align: right;' oninput="Update_Quantity('<?php echo $row['Purchase_Cache_ID']; ?>')">
			</td>		
			<td>
				<input type='text' id='QR<?php echo $row['Purchase_Cache_ID']; ?>' readonly="readonly" value='<?php echo $row['Quantity_Required']; ?>' style='text-align: right;' oninput="Update_Quantity2(this.value,<?php echo $row['Purchase_Cache_ID']; ?>)">
			</td>		
			<td>
				<input type='text' id='Balance_<?php echo $row['Purchase_Cache_ID']; ?>' value='<?php echo $Item_Balance; ?>' style='text-align: right;' readonly="readonly">
			</td>
			<td>
			    <input type='text' id='<?php echo $row['Purchase_Cache_ID']; ?>' name='<?php echo $row['Purchase_Cache_ID']; ?>' value='<?php echo $row['Price']; ?>' style='text-align: right;' oninput="Update_Price(this.value,<?php echo $row['Purchase_Cache_ID']; ?>)">
			</td>
	<?php
		    echo "<td><input type='text' name='Sub_Total".$row['Purchase_Cache_ID']."' id='Sub_Total".$row['Purchase_Cache_ID']."' readonly='readonly' value='".number_format($row['Quantity_Required'] * $row['Price'])."' style='text-align: right;'></td>";
		    //echo "<td><input type='text' value='".$row['Item_Remark']."'></td>";
		?>
                        <td width=6% <?= $display_none ?>>
				<input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green' onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Purchase_Cache_ID']; ?>)'>
			</td>
            <td width=6%>
                <input type='button' name='Item_Purchase_History' id='Item_Purchase_History' value='H' class='art-button-green'
                       onclick='Get_Item_Purchase_History("<?php echo $row['Item_ID']; ?>")'>
            </td>
		<?php	
			
		    echo "</tr>";
		    $Temp++;
		}
		echo '</table>';
	?>
</fieldset>
<!--
<fieldset>
	<table width=100%>
		<tr>
			<td style='text-align: left;' width="70%">&nbsp;
				<b>
					<?php
						if($counter == 1){
							echo "1 Item Added After Loading";
						}else if($counter > 1){
							echo $counter." Items Added After Loading";
						}
					?>
				</b>
			</td>
			<td width="15%" id="Submit_Button_Area">
				<?php if($no > 0){ ?>
					<input type='button' name='' id='' class='art-button-green' value='SUBMIT PURCHASE ORDER' onclick='Confirm_Submit_Order()'>
				<?php } ?>
			</td>
			<td style='text-align: right;' width=15%>
				<?php if(isset($_SESSION['Procurement_Autentication_Level']) && $_SESSION['Procurement_Autentication_Level'] == 1){?>
					input type='button' name='Add_Item' id='Add_Item' value='ADD ITEM' class='art-button-green' onclick="openItemDialog()"
				<?php } ?>
			</td>
		</tr>
	</table>
</fieldset>-->
<fieldset>
	<table width=100%>
		<tr>
			<td style='text-align: left;' width="17%">&nbsp;
				<b>
					<?php
						if($counter == 1){
							echo "1 Item Added After Loading";
						}else if($counter > 1){
							echo $counter." Items Added After Loading";
						}
					?>
				</b>
                            
			</td>
                        <td><b>Document approved by</b></td>
                        <td width="20%">
                            
                            <select style="width:100%">
                                <option></option>
                                <?php 
                                    $sql_select_approver_result=mysqli_query($conn,"SELECT document_approval_level_title,Employee_Name FROM tbl_employee emp,tbl_document_approval_control dac WHERE emp.Employee_ID=dac.approve_employee_id AND document_number='$Selected_Store_Order_ID' AND document_type='purchase_order'") or die(mysqli_error($conn));
                                    if(mysqli_num_rows($sql_select_approver_result)>0){
                                        while ($approver_rows=mysqli_fetch_assoc($sql_select_approver_result)) {
                                            $document_approval_level_title=$approver_rows['document_approval_level_title'];
                                            $Employee_Name=$approver_rows['Employee_Name'];
                                            echo "<option>$Employee_Name ~ $document_approval_level_title</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </td>
                        <td><input type="text" placeholder="Username" id="Username" style="text-align:center" class="form-control" /></td>
                        <td><input type="password" placeholder="Password" id="Password" style="text-align:center" class="form-control" /></td>
			<td width="15%" id="Submit_Button_Area">
				<?php if($no > 0){ ?>
					<input type='button' name='' id='' class='art-button-green' value='APPROVE PURCHASE ORDER' onclick='Confirm_Submit_Order()'>
				<?php } ?>
			</td>
			<td style='text-align: right;' width=1%>
				<?php if(isset($_SESSION['Procurement_Autentication_Level']) && $_SESSION['Procurement_Autentication_Level'] == 1){?>
					<!--input type='button' name='Add_Item' id='Add_Item' value='ADD ITEM' class='art-button-green' onclick="openItemDialog()"-->
				<?php } ?>
			</td>
		</tr>
	</table>
</fieldset>
<script>
function confirm_approval(){
        var Supervisor_Username = document.getElementById("Username").value;
        var Supervisor_Password = document.getElementById("Password").value;
        if(Supervisor_Username==""){
            $("#Username").css("border","2px solid red");
        }else if(Supervisor_Password==""){
            $("#Username").css("border","");
            $("#Password").css("border","2px solid red");
        }else{
            $("#Username").css("border","");
            $("#Password").css("border","");
           if(confirm("Are you sure you want to approve this Order?")){
            check_if_valid_user_to_approve_this_document();
        } 
      }   
    }
    function check_if_valid_user_to_approve_this_document(){
        var Supervisor_Username = document.getElementById("Username").value;
        var Supervisor_Password = document.getElementById("Password").value;
        var Store_Order_ID = '<?php echo $Selected_Store_Order_ID; ?>';
        var Supplier_ID=$("#Supplier_ID").val();
         $.ajax({
                        type: 'GET',
                        url: 'verify_approver_privileges_support.php',
                        data: 'Username=' + Supervisor_Username + '&Password=' + Supervisor_Password + '&document_number=' + Store_Order_ID+"&document_type=purchase_order&Supplier_ID="+Supplier_ID,
                        cache: false,
                        success: function (feedback) {
                            if (feedback == 'all_approve_success') {
                                $(".Remove_Item").hide();
                                Submit_Order();
                            }else if(feedback=="invalid_privileges"){
                                alert("Invalid Username or Password or you do not have enough privilage to approve this requisition");
                            }else if(feedback=="fail_to_approve"){
                                alert("Fail to approve..please try again");  
                            }else{
                                $(".Remove_Item").hide();
                                alert(feedback);
                            }
                        }
                    });
    }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="script.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="script.responsive.js"></script>

<link href="css/select2.min.css" rel="stylesheet" />
<script src="js/select2.min.js"></script>

<div id="Item_Purchase_History_Dialog" style="width:50%;" ></div>
<script>
    function Get_Item_Purchase_History(Item_ID){
        if(window.XMLHttpRequest) {
            myObjectGetItem = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetItem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetItem.overrideMimeType('text/xml');
        }

        myObjectGetItem.onreadystatechange = function (){
            data265 = myObjectGetItem.responseText;
            if (myObjectGetItem.readyState == 4) {
                $("#Item_Purchase_History_Dialog").html(data265)
                $("#Item_Purchase_History_Dialog").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectGetItem.open('GET','Get_Item_Purchase_History.php?Item_ID='+Item_ID,true);
        myObjectGetItem.send();

        $("#Item_Purchase_History_Dialog").html('');

    }
</script>

<script>
   $(document).ready(function(){
        $("#Add_Pharmacy_Items").dialog({ autoOpen: false, width:950,height:450, title:'ADD NEW ITEM',modal: true});
        $("#Item_Purchase_History_Dialog").dialog({ autoOpen: false, width:950,height:450, title:'ITEM PURCHASE HISTORY',modal: true});

        $('select').select2();
   });
</script>
<style>
    .select2.select2-container { width : 100% !important; }
</style>

<script>
   function openItemDialog(){
   		var Store_Need = document.getElementById("Store_Need").value;
   		if(Store_Need != '' && Store_Need != null){
			$("#Add_Pharmacy_Items").dialog("open");
   		}else{
   			document.getElementById("Store_Need").style = 'border: 3px solid red;';
   			document.getElementById("Store_Need").focus();
   		}
   }
</script>

<script>
   function getItemsList(Item_Category_ID){
      document.getElementById("Search_Value").value = ''; 
      document.getElementById("Item_Name").value = '';
      document.getElementById("Item_ID").value = '';
      var Guarantor_Name = '<?php //echo $Guarantor_Name; ?>';
      
      if(window.XMLHttpRequest) {
	  myObjectGetItem = new XMLHttpRequest();
      }else if(window.ActiveXObject){ 
	  myObjectGetItem = new ActiveXObject('Micrsoft.XMLHTTP');
	  myObjectGetItem.overrideMimeType('text/xml');
      }
      //alert(data);
  
      myObjectGetItem.onreadystatechange = function (){
		  data265 = myObjectGetItem.responseText;
		  if (myObjectGetItem.readyState == 4) {
		      //document.getElementById('Approval').readonly = 'readonly';
		      document.getElementById('Items_Fieldset').innerHTML = data265;
		  }
	      }; //specify name of function that will handle server response........
      myObjectGetItem.open('GET','Get_List_Of_Pharmacy_Items_List.php?Item_Category_ID='+Item_Category_ID+'&Guarantor_Name='+Guarantor_Name,true);
      myObjectGetItem.send();
   }
</script>


<script>
   function getItemsListFiltered(Item_Name){
      document.getElementById("Item_Name").value = '';
      document.getElementById("Item_ID").value = '';
      document.getElementById("Dosage").value = '';
      document.getElementById("Quantity").value = '';
      var Guarantor_Name = '<?php //echo $Guarantor_Name; ?>';
      
      var Item_Category_ID = document.getElementById("Item_Category_ID").value;
      if (Item_Category_ID == '' || Item_Category_ID == null) {
	  Item_Category_ID = 'All';
      }
      
      if(window.XMLHttpRequest) {
	  myObjectFiltered = new XMLHttpRequest();
      }else if(window.ActiveXObject){ 
	  myObjectFiltered = new ActiveXObject('Micrsoft.XMLHTTP');
	  myObjectFiltered.overrideMimeType('text/xml');
      }
  
      myObjectFiltered.onreadystatechange = function (){
	 data135 = myObjectFiltered.responseText;
	 if (myObjectFiltered.readyState == 4) {
	     //document.getElementById('Approval').readonly = 'readonly';
	     document.getElementById('Items_Fieldset').innerHTML = data135;
	 }
      }; //specify name of function that will handle server response........
      myObjectFiltered.open('GET','Get_List_Of_Pharmacy_Filtered_Items.php?Item_Category_ID='+Item_Category_ID+'&Item_Name='+Item_Name+'&Guarantor_Name='+Guarantor_Name,true);
      myObjectFiltered.send();
   }
</script>

<script>
	function Confirm_Submit_Order() {
		var Store_Need = document.getElementById("Store_Need").value;
		
		if (Store_Need != null && Store_Need != '') {
			
			if(window.XMLHttpRequest) {
				myObjectConfirm = new XMLHttpRequest();
			}else if(window.ActiveXObject){ 
				myObjectConfirm = new ActiveXObject('Micrsoft.XMLHTTP');
				myObjectConfirm.overrideMimeType('text/xml');
			}
			myObjectConfirm.onreadystatechange = function (){
				data1350 = myObjectConfirm.responseText;
				if (myObjectConfirm.readyState == 4) {
					var feedback = data1350;
					if (feedback == 'yes') {
						var Confirm_Message = confirm("Some items missing Price/Quantity value.\nTo proceed with this process, only items with PRICE & QUANTITY values will create PURCHASE ORDER \n\nTo proceed click OK\nOtherwise click CANCEL");
						if (Confirm_Message == true) {
							confirm_approval()
						}
					}else{
						var Confirm_Message = confirm("Are you sure you want to submit this Purchase Order?");
						if (Confirm_Message == true) {
							confirm_approval()
						}
					}
				}
			}; //specify name of function that will handle server response........
			myObjectConfirm.open('GET','Purchase_Order_Verify_Data.php',true);
			myObjectConfirm.send();
		}else{
			if(Store_Need =='' || Store_Need == null){
				document.getElementById("Store_Need").focus();
				document.getElementById("Store_Need").style = 'border: 3px solid red';
			}
		}
	}
</script>

<script>
    function Update_Supplier(Supplier_ID,Purchase_Cache_ID) {
        if (Supplier_ID != null && Supplier_ID != '' && Purchase_Cache_ID != null && Purchase_Cache_ID != '') {
            if(window.XMLHttpRequest) {
                myObjectUpdatePrice = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectUpdatePrice = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectUpdatePrice.overrideMimeType('text/xml');
            }

            myObjectUpdatePrice.onreadystatechange = function (){
                data2000 = myObjectUpdatePrice.responseText;
                if (myObjectUpdatePrice.readyState == 4) {

                }
            }; //specify name of function that will handle server response........
            myObjectUpdatePrice.open('GET','Update_Supplier_Purchase_Cache.php?Purchase_Cache_ID='+Purchase_Cache_ID+'&Supplier_ID='+Supplier_ID,true);
            myObjectUpdatePrice.send();
        }
    }

	function Update_Price(Price,Purchase_Cache_ID) {
		
		if (Price != null && Price != '' && Purchase_Cache_ID != null && Purchase_Cache_ID != '') {
			if(window.XMLHttpRequest) {
				myObjectUpdatePrice = new XMLHttpRequest();
			}else if(window.ActiveXObject){ 
				myObjectUpdatePrice = new ActiveXObject('Micrsoft.XMLHTTP');
				myObjectUpdatePrice.overrideMimeType('text/xml');
			}
	  
			myObjectUpdatePrice.onreadystatechange = function (){
				data2000 = myObjectUpdatePrice.responseText;
				if (myObjectUpdatePrice.readyState == 4) {
					//document.getElementById(Purchase_Cache_ID).value = data2000;
					update_sub_total(Purchase_Cache_ID);
				}
			}; //specify name of function that will handle server response........
			myObjectUpdatePrice.open('GET','Update_Price_Purchase.php?Purchase_Cache_ID='+Purchase_Cache_ID+'&Price='+Price,true);
			myObjectUpdatePrice.send();
		}
	}
</script>

<script>
	function Update_Quantity2(Quantity,Purchase_Cache_ID) {
		if (Quantity != '' && Quantity != null && Purchase_Cache_ID != null && Purchase_Cache_ID != '') {
			document.getElementById("Container_"+Purchase_Cache_ID).value = 1;
			document.getElementById("Items_"+Purchase_Cache_ID).value = Quantity;
			if(window.XMLHttpRequest) {
				myObjectUpdateQuantity = new XMLHttpRequest();
			}else if(window.ActiveXObject){ 
				myObjectUpdateQuantity = new ActiveXObject('Micrsoft.XMLHTTP');
				myObjectUpdateQuantity.overrideMimeType('text/xml');
			}
	  
			myObjectUpdateQuantity.onreadystatechange = function (){
				data2090 = myObjectUpdateQuantity.responseText;
				if (myObjectUpdateQuantity.readyState == 4) {
					document.getElementById("QR"+Purchase_Cache_ID).value = data2090;
					update_sub_total(Purchase_Cache_ID);
				}
			}; //specify name of function that will handle server response........
			myObjectUpdateQuantity.open('GET','Update_Quantity_Purchase2.php?Purchase_Cache_ID='+Purchase_Cache_ID+'&Quantity='+Quantity,true);
			myObjectUpdateQuantity.send();
		}
	}
</script>


<script>
	function Update_Quantity(Purchase_Cache_ID) {
		var Containers = document.getElementById("Container_"+Purchase_Cache_ID).value;
		var Items_Per_Container = document.getElementById("Items_"+Purchase_Cache_ID).value;
		if (Purchase_Cache_ID != null && Purchase_Cache_ID != '') {
			if(window.XMLHttpRequest) {
				myObjectUpdateQuantity = new XMLHttpRequest();
			}else if(window.ActiveXObject){ 
				myObjectUpdateQuantity = new ActiveXObject('Micrsoft.XMLHTTP');
				myObjectUpdateQuantity.overrideMimeType('text/xml');
			}
	  
			myObjectUpdateQuantity.onreadystatechange = function (){
				data2090 = myObjectUpdateQuantity.responseText;
				if (myObjectUpdateQuantity.readyState == 4) {
					document.getElementById("QR"+Purchase_Cache_ID).value = data2090;
					update_sub_total(Purchase_Cache_ID);
				}
			}; //specify name of function that will handle server response........
			myObjectUpdateQuantity.open('GET','Update_Quantity_Purchase.php?Purchase_Cache_ID='+Purchase_Cache_ID+'&Containers='+Containers+'&Items_Per_Container='+Items_Per_Container,true);
			myObjectUpdateQuantity.send();
		}
	}
</script>

<script>
	function update_sub_total(Purchase_Cache_ID) {
		var Price = document.getElementById(Purchase_Cache_ID).value;
		var Quantity = document.getElementById("QR"+Purchase_Cache_ID).value;
		document.getElementById("Sub_Total"+Purchase_Cache_ID).value = (Price * Quantity);
	}
</script>


<?php if(isset($_SESSION['Procurement_Autentication_Level']) && $_SESSION['Procurement_Autentication_Level'] == 1){ ?>
<div id="Add_Pharmacy_Items" style="width:50%;" >
   <table width=100% style='border-style: none;'>
      <tr>
	 <td width=40%>
	    <table width=100% style='border-style: none;'>
	       <tr>
		  <td>
		     <b>Category : </b>
		     <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
			 <option selected='selected'></option>
			 <?php
			     $data = mysqli_query($conn,"
				     select Item_Category_Name, Item_Category_ID
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
		     <fieldset style='overflow-y: scroll; height: 270px;' id='Items_Fieldset'>
			<table width=100%>
			<?php
			   $result = mysqli_query($conn,"SELECT * FROM tbl_items where Item_Type = 'Pharmacy' order by Product_Name limit 200");
			   while($row = mysqli_fetch_array($result)){
			       echo "<tr>
				   <td style='color:black; border:2px solid #ccc;text-align: left;' width=5%>"; ?>
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
		<center>
			<label id='Item_Name_Label'></label><br/>
		</center>
		
	    <table width=100% border=0>
		<tr>
			 <td style='text-align: right;' width=30%>Item Name</td>
			 <td>
			    <input type='text' name='Item_Name' id='Item_Name' readonly='readonly' placeholder='Item Name'>
			    <input type='hidden' name='Item_ID' id='Item_ID' value=''>
			 </td>
		</tr>
		<tr>
			 <td style='text-align: right;' id='Price_Label'>Price</td>
			 <td>
			    <input type='text' name='Price' id='Price' placeholder='Price' autocomplete='off'>
			 </td>
		</tr>
		<tr>
			 <td style='text-align: right;' id='Containers_Label'>Containers</td>
			 <td>
			    <input type='text' name='Container' id='Container' autocomplete='off' placeholder='Containers' onchange='numberOnly(this); Calculate_Quantity();' onkeyup='numberOnly(this); Calculate_Quantity();' onkeypress='numberOnly(this); Calculate_Quantity();' oninput='numberOnly(this); Calculate_Quantity();'>
			 </td>
		</tr>
		<tr>
			 <td style='text-align: right;' id='Items_Per_Container_Label'>Items per Container</td>
			 <td>
			    <input type='text' name='Items_per_Container' id='Items_per_Container' autocomplete='off' placeholder='Items per Container' onchange='numberOnly(this); Calculate_Quantity();' onkeyup='numberOnly(this); Calculate_Quantity();' onkeypress='numberOnly(this); Calculate_Quantity();' oninput='numberOnly(this); Calculate_Quantity();'>
			 </td>
		</tr>
		<tr>
			 <td style='text-align: right;' id='Quantity_Label'>Quantity</td>
			 <td>
			    <input type='text' name='Quantity' id='Quantity' placeholder='Quantity' autocomplete='off' oninput="Clear_Containers_Items();">
			 </td>
		</tr>
		<!-- <tr>
			 <td id='Remark_Label' style='text-align: right;'>
				Item Remark
			 </td>
			 <td>
			    <input type='text' name='Item_Remark' id='Item_Remark' placeholder='Item Remark'>
			 </td>
		</tr> -->
		<tr>
			 <td colspan=2 style='text-align: center;' id='Add_Button_Area'>
			    <input type='button' name='Submit' id='Submit' class='art-button-green' value='ADD ITEM' onclick='Get_Selected_Item()'>
			 </td>
		</tr>
	    </table>
	 </td>
      </tr>
   </table>
</div>
<?php } ?>

<script type="text/javascript">
	function Calculate_Quantity(){
		var Items_Quantity = document.getElementById("Items_per_Container").value;
		var Cont_Quantity = document.getElementById("Container").value;
		var Quantity = document.getElementById("Quantity").value = '';

		if(Items_Quantity != null && Items_Quantity != '' && Cont_Quantity != null && Cont_Quantity != ''){
			document.getElementById("Quantity").value = (Items_Quantity * Cont_Quantity);
		}else{
			document.getElementById("Quantity"). value = '';
		}
	}
</script>

<script type="text/javascript">
	/*function Clear_Containers_Items(){
		document.getElementById("Container").value = '';
		document.getElementById("Items_per_Container").value = '';
	}*/
</script>


<script type="text/javascript">
	function Clear_Containers_Items(){
		var Quantity = document.getElementById("Quantity").value;
		document.getElementById("Container").value = 1;
		document.getElementById("Items_per_Container").value = Quantity;
	}
</script>

<?php
	include("./includes/footer.php");
?>