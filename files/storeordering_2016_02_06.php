<script src='js/functions.js'></script>
<?php
    include("./includes/header.php");
    include("./includes/connection.php");

    include("./storeordering_navigation.php");

    //redirect to list of submitted order if and only if current employee is chief pharmacist
    if(isset($_SESSION['userinfo']['Approval_Orders']) && strtolower($_SESSION['userinfo']['Approval_Orders']) == 'yes'){
    	header("Location: ./storesubmittedorders.php?PendingOrders=PendingOrdersThisPage");
    }
    
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

    if (isset($_GET['status']) && $_GET['status'] == "edit") {
        if (isset($_GET['Store_Order_ID'])) {
            $_SESSION['General_Order_ID'] = $_GET['Store_Order_ID'];
            mysqli_query($conn,"UPDATE tbl_store_orders
                           SET Control_Status = 'editing'
                           WHERE Store_Order_ID = '".$_SESSION['General_Order_ID']."'") or die(mysqli_error($conn));

            $_SESSION['Last_Store_Order_ID'] = $_GET['Store_Order_ID'];
        }
    }

?>

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
<form action='#' method='post' name='myForm' id='myForm' >
<br/>
<fieldset> <legend style="background-color:#006400;color:white" align='right'><b>Store Order</b></legend>

 <style>
		table,tr,td{ border-collapse:collapse !important; /*border:none !important;*/ }
</style>

	<table style="margin-top:5" width=100%>
		<tr>

			<td width='12%' style='text-align: right;'>Order Number</td>
			<td width=5%>
				<?php if(isset($_SESSION['General_Order_ID'])){ ?>
					<input type='text' name='Store_Order_ID' size=6 id='Store_Order_ID' readonly='readonly' value='<?php echo $_SESSION['General_Order_ID']; ?>'>
				<?php }else{ ?>
					<input type='text' name='Store_Order_ID' size=6  id='Store_Order_ID' value='new'>
				<?php } ?>
			</td>
			<td width='12%' style='text-align: right;'>Order Date</td>
			<td width='16%'>
				<?php if(isset($_SESSION['General_Order_ID'])){
					//get requisition date
					$Store_Order_ID = $_SESSION['General_Order_ID'];
					$get_details = mysqli_query($conn,"select Created_Date_Time from tbl_store_orders where Store_Order_ID = '$Store_Order_ID'") or die(mysqli_error($conn));
					$num = mysqli_num_rows($get_details);
					if($num > 0){
					    while($row = mysqli_fetch_array($get_details)){
						$Created_Date_Time = $row['Created_Date_Time'];
					    }
					}else{
					    $Created_Date_Time = '';
					}
				?>
					<input type='text' readonly='readonly' name='Order_Date' id='Order_Date' value='<?php echo $Created_Date_Time; ?>'>
				<?php }else{ ?>
					<input type='text' readonly='readonly' name='Order_Date' id='Order_Date'>
				<?php } ?>
			</td>
		    <td style='text-align: right;'>Prepared By</td>
			<td>
				<input type='text' readonly='readonly' value='<?php echo $Employee_Name; ?>'>
			</td>
		</tr>
		<tr>
            <td width='10%' style='text-align: right;'>Store Ordering</td>
            <td width='16%' id="Sub_Department_ID_Area">
                <select name='Sub_Department_ID' id='Sub_Department_ID' required='required' style="width: 100%;">
                    <?php
                    	//check pending Store Requestion
                    	if(isset($_SESSION['General_Order_ID'])){
                    		$Store_Order_ID = $_SESSION['General_Order_ID'];
                    		$Get_Store_Request = mysqli_query($conn,"select Sub_Department_Name, sd.Sub_Department_ID from tbl_sub_department sd, tbl_store_orders rq where
                    		 									sd.Sub_Department_ID = rq.Sub_Department_ID and
                    		 									rq.Store_Order_ID = '$Store_Order_ID'") or die(mysqli_error($conn));
                    		$nms = mysqli_num_rows($Get_Store_Request);
                    		if($nms > 0){
                    			while ($dt = mysqli_fetch_array($Get_Store_Request)) {
                    				echo "<option value='".$dt['Sub_Department_ID']."'>".ucwords(strtolower($dt['Sub_Department_Name']))."</option>";
                                    $Sub_Department_ID = $dt['Sub_Department_ID'];
                    			}
                    		}
                    	}else{
                    ?>
                        <!--option selected="selected" value=""></option-->
                    <?php
                        	//display sub department name
                      		$select = mysqli_query($conn,"SELECT
                      						            sd.Sub_Department_Name, sd.Sub_Department_ID
                                                   FROM
                      						            tbl_sub_department sd, tbl_employee_sub_department esd, tbl_department d
                      						       WHERE
                      							        esd.Sub_Department_ID = sd.Sub_Department_ID AND
                      								    esd.Employee_ID = '$Employee_ID' AND
                      								    sd.Department_ID = d.Department_ID AND
                      								    d.Department_Location = 'Storage And Supply'
                                                   ORDER BY Sub_Department_Name") or die(mysqli_error($conn));
                      		$nm = mysqli_num_rows($select);
                      		if($nm > 0){
                      			while ($data = mysqli_fetch_array($select)) {
                    ?>
                      			<option value="<?php echo $data['Sub_Department_ID']; ?>">
                                    <?php echo ucwords(strtolower($data['Sub_Department_Name'])); ?>
                                </option>
                    <?php
                                    $Sub_Department_ID = $data['Sub_Department_ID'];
                      			}
                      		}
                      	}
                    ?>
                </select>
			</td>
			<td width='13%' style='text-align: right;'>
			    Order Description
			</td>
			<td colspan="4">
				<?php if(isset($_SESSION['General_Order_ID'])){
					//get store description
					$Store_Order_ID = $_SESSION['General_Order_ID'];
					$get_details = mysqli_query($conn,"select Order_Description from tbl_store_orders where Store_Order_ID = '$Store_Order_ID'") or die(mysqli_error($conn));
					$num = mysqli_num_rows($get_details);
					if($num > 0){
					    while($row = mysqli_fetch_array($get_details)){
						$Order_Description = $row['Order_Description'];
					    }
					}else{
					    $Order_Description = '';
					}
				?>
				<input type='text' name='Order_Description' id='Order_Description' value='<?php echo $Order_Description; ?>' onclick='updateRequisitionDesc()' onkeyup='updateRequisitionDesc()'>
				<?php }else{ ?>
					<input type='text' name='Order_Description' id='Order_Description'>
				<?php } ?>
			</td>
		</tr>
        </table>
</center>
</fieldset>

<script>
	function getItemsList(Item_Category_ID){
		document.getElementById("Search_Value").value = '';
		/*document.getElementById("Item_Name").value = '';*/
		/*document.getElementById("Item_ID").value = '';*/

        Sub_Department_ID = document.getElementById("Sub_Department_ID").value;

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
		myObject.open('GET','Get_Store_Order_List_Of_Items.php?Item_Category_ID='+Item_Category_ID+'&Sub_Department_ID='+Sub_Department_ID,true);
		myObject.send();
	}

	function getItemsListFiltered(Item_Name){
		//document.getElementById("Item_Name").value = '';
		//document.getElementById("Item_ID").value = '';

        Sub_Department_ID = document.getElementById("Sub_Department_ID").value;

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
		myObject.open('GET','Get_Store_Order_List_Of_Filtered_Items.php?Item_Category_ID='+Item_Category_ID+'&Item_Name='+Item_Name+'&Sub_Department_ID='+Sub_Department_ID,true);
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
	function Get_Item_Name(Item_Name,Item_ID){
        //alert("Selected Item : "+Item_Name+" of ID: "+Item_ID);

        var Quantity = '0';
        var Item_Remark = '';
        var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
        var Cont_Quantity = '0';
        var Items_Quantity = '0';
        var Order_Description = document.getElementById("Order_Description").value;

        if(window.XMLHttpRequest) {
            myObjectGetItemName = new XMLHttpRequest();
            my_Object_Get_Selected_Item = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetItemName = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetItemName.overrideMimeType('text/xml');
            my_Object_Get_Selected_Item = new ActiveXObject('Micrsoft.XMLHTTP');
            my_Object_Get_Selected_Item.overrideMimeType('text/xml');
        }

        myObjectGetItemName.onreadystatechange = function (){
            data22 = myObjectGetItemName.responseText;
            if (myObjectGetItemName.readyState == 4) {
                Temp = data22;
                if (Temp == 'Yes'){
                    alert("Item Already Added");
                }else{
                    my_Object_Get_Selected_Item.onreadystatechange = function (){
                        data = my_Object_Get_Selected_Item.responseText;
                        if (my_Object_Get_Selected_Item.readyState == 4) {
                            document.getElementById('Items_Fieldset_List').innerHTML = data;
                            /*document.getElementById("Item_Name").value = '';
                            document.getElementById("Quantity").value = '';
                            document.getElementById("Item_ID").value = '';
                            document.getElementById("Cont_Quantity").value = '';
                            document.getElementById("Items_Quantity").value = '';
                            document.getElementById("Item_Remark").value = '';*/
                            //alert("Item Added Successfully");
                            //updateStoreIssueMenu2();
                            //updateStoreNeedMenu2();
                            update_Store_Order_ID();
                            updateOrderCreatedDate();
                            updateRequisitionDescription();
                            updateSubmitArea();
                        }
                    };

                    my_Object_Get_Selected_Item.open('GET','Store_Ordering_Add_Selected_Item_V2.php?Item_ID='+Item_ID+'&Quantity='+Quantity+'&Item_Remark='+Item_Remark+'&Sub_Department_ID='+Sub_Department_ID+'&Order_Description='+Order_Description+'&Cont_Quantity='+Cont_Quantity+'&Items_Quantity='+Items_Quantity,true);
                    my_Object_Get_Selected_Item.send();

                    update_Store_Order_ID();
                    updateOrderCreatedDate();
                    updateRequisitionDescription();
                    updateSubmitArea();
                }
            }
        };
        myObjectGetItemName.open('GET','Store_Order_Check_Item_Selected.php?Item_ID='+Item_ID+'&Sub_Department_ID='+Sub_Department_ID,true);
        myObjectGetItemName.send();




		/*var Temp = '';
		var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
		document.getElementById("Last_Buying_Price").value = '';
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

				document.getElementById("Items_Quantity").style.backgroundColor = '#037CB0';
				document.getElementById("Items_Quantity").value = '';
				document.getElementById("Items_Label").style.color = '#037CB0';
				document.getElementById("Items_Label").innerHTML = '<b>Item Per Units</b>';
				document.getElementById("Items_Quantity").setAttribute("ReadOnly","ReadOnly");

				document.getElementById("Cont_Quantity").style.backgroundColor = '#037CB0';
				document.getElementById("Cont_Quantity").value = '';
				document.getElementById("Container_Label").style.color = '#037CB0';
				document.getElementById("Container_Label").innerHTML = '<b>Units</b>';
				document.getElementById("Cont_Quantity").setAttribute("ReadOnly","ReadOnly");

				document.getElementById("Quantity").style.backgroundColor = '#037CB0';
				document.getElementById("Quantity").value = '';
				document.getElementById("Quantity_Label").style.color = '#037CB0';
				document.getElementById("Quantity_Label").innerHTML = '<b>Quantity</b>';
				document.getElementById("Quantity").setAttribute("ReadOnly","ReadOnly");

				document.getElementById("Balance").style.backgroundColor = '#037CB0';
				document.getElementById("Balance").style.backgroundColor = '#037CB0';
				document.getElementById("Balance_Label").style.color = '#037CB0';
				document.getElementById("Balance_Label").innerHTML = '<b>Balance</b>';

				document.getElementById("Item_Remark").style.backgroundColor = '#037CB0';
				document.getElementById("Item_Remark").value = '';
				document.getElementById("Remark_Label").style.color = '#037CB0';
				document.getElementById("Remark_Label").innerHTML = '<b>Item Remark</b>';
				document.getElementById("Item_Remark").setAttribute("ReadOnly","ReadOnly");

				document.getElementById("Item_Name_Label").style.color = '#037CB0';
				document.getElementById("Item_Name_Label").innerHTML = '<b>This Item Already Added. Change the quantity / remark when needed</b>';

				//change add button to warning add button
				document.getElementById("Add_Button_Area").innerHTML = "<input type='button' name='submit' id='submit' value='ADD' class='art-button-green' onclick='Get_Selected_Item_Warning()'>";
			    }else{
				document.getElementById("Item_Name").style.backgroundColor = 'white';
				document.getElementById("Item_Name_Label").style.color = 'black';
				document.getElementById("Item_Name_Label").innerHTML = 'Item Name';

				document.getElementById("Items_Quantity").style.backgroundColor = 'white';
				document.getElementById("Items_Quantity").value = '';
				//document.getElementById("Items_Quantity").focus();
				document.getElementById("Items_Quantity").removeAttribute("ReadOnly");
				document.getElementById("Items_Label").innerHTML = 'Items Per Units';
				document.getElementById("Items_Label").style.color = 'black';

				document.getElementById("Cont_Quantity").style.backgroundColor = 'white';
				document.getElementById("Cont_Quantity").value = '';
				//document.getElementById("Cont_Quantity").focus();
				document.getElementById("Cont_Quantity").removeAttribute("ReadOnly");
				document.getElementById("Container_Label").innerHTML = 'Units';
				document.getElementById("Container_Label").style.color = 'black';

				document.getElementById("Quantity").style.backgroundColor = 'white';
				document.getElementById("Quantity").value = '';
				//document.getElementById("Quantity").focus();
				document.getElementById("Quantity").removeAttribute("ReadOnly");
				document.getElementById("Quantity_Label").innerHTML = 'Quantity';
				document.getElementById("Quantity_Label").style.color = 'black';

				document.getElementById("Balance").style.backgroundColor = 'white';
				document.getElementById("Balance_Label").innerHTML = 'Balance';
				document.getElementById("Balance_Label").style.color = 'black';

				document.getElementById("Item_Remark").style.backgroundColor = 'white';
				document.getElementById("Item_Remark").value = '';
				document.getElementById("Item_Remark").removeAttribute("ReadOnly");
				document.getElementById("Remark_Label").innerHTML = 'Item Remark';
				document.getElementById("Remark_Label").style.color = 'black';

				//change warning add button to add button
				document.getElementById("Add_Button_Area").innerHTML = "<input type='button' name='submit' id='submit' value='ADD' class='art-button-green' onclick='Get_Selected_Item()'>";
			    }
			}
		}; //specify name of function that will handle server response........
		myObjectGetItemName.open('GET','Store_Order_Check_Item_Selected.php?Item_ID='+Item_ID+'&Sub_Department_ID='+Sub_Department_ID,true);
		myObjectGetItemName.send();



		if (Item_ID != null && Item_ID != '' && Sub_Department_ID != null && Sub_Department_ID != '') {
			document.getElementById("Item_Name").value = Item_Name;
			document.getElementById("Item_ID").value = Item_ID;
            document.getElementById('Quantity').value = '0';
			document.getElementById("Quantity").focus();
			Items_Quantity = document.getElementById("Items_Quantity").value = '';
			Cont_Quantity = document.getElementById("Cont_Quantity").value = '';
			Get_Balance();
			Get_Last_Price(Item_ID);
		}else{
			if(Sub_Department_ID == null || Sub_Department_ID == ''){
				document.getElementById("Sub_Department_ID").style = 'border: 3px solid red';
			}
		}*/
	}
</script>


<script>
	function Get_Balance(){
		var Item_ID = document.getElementById("Item_ID").value;
		var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;

		if(window.XMLHttpRequest) {
			myObjectGetBalance = new XMLHttpRequest();
		}else if(window.ActiveXObject){
			myObjectGetBalance = new ActiveXObject('Micrsoft.XMLHTTP');
			myObjectGetBalance.overrideMimeType('text/xml');
		}

		myObjectGetBalance.onreadystatechange = function (){
			data80 = myObjectGetBalance.responseText;
			if (myObjectGetBalance.readyState == 4) {
				document.getElementById('Balance').value = data80;
			}
		}; //specify name of function that will handle server response........

		myObjectGetBalance.open('GET','Store_Ordering_Get_Item_Expected_Balance.php?Item_ID='+Item_ID+'&Sub_Department_ID='+Sub_Department_ID,true);
		myObjectGetBalance.send();
	}
</script>

<script>
	function updateStoreIssueMenu2() {
		var Store_Issue = document.getElementById("Store_Issue").value;
		if(window.XMLHttpRequest) {
		    myRequisitionObject = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myRequisitionObject = new ActiveXObject('Micrsoft.XMLHTTP');
		    myRequisitionObject.overrideMimeType('text/xml');
		}

		myRequisitionObject.onreadystatechange = function (){
			data20 = myRequisitionObject.responseText;
			if (myRequisitionObject.readyState == 4) {
			    document.getElementById('Store_Issue_Area').innerHTML = data20;
			}
		}; //specify name of function that will handle server response........
		myRequisitionObject.open('GET','General_Get_Store_Issued.php?Store_Issue='+Store_Issue,true);
		myRequisitionObject.send();
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
	function updateStoreIssueMenu() {
	   /*	var Store_Order_ID = '<?php echo $_SESSION['General_Order_ID']; ?>';
		alert(Store_Order_ID);

		if(window.XMLHttpRequest) {
		    myObject123 = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObject123 = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObject123.overrideMimeType('text/xml');
		}

		myObject123.onreadystatechange = function (){
		    data12 = myObject123.responseText;
		    if (myObject123.readyState == 4) {
			document.getElementById('Store_Issue_Area').innerHTML = data12;
		    }
		};//specify name of function that will handle server response........

		myObject123.open('GET','Get_Store_Issued.php?Store_Order_ID='+Store_Order_ID,true);
		myObject123.send();*/
	}
</script>
<?php
	if(isset($_SESSION['General_Order_ID'])){
		$Store_Order_ID = $_SESSION['General_Order_ID'];
	}else{
		$Store_Order_ID = 0;
	}
?>
<script>
	function update_Store_Order_ID(){
		if(window.XMLHttpRequest){
			myObjectUpdateRequisition = new XMLHttpRequest();
		}else if(window.ActiveXObject){
			myObjectUpdateRequisition = new ActiveXObject('Micrsoft.XMLHTTP');
			myObjectUpdateRequisition.overrideMimeType('text/xml');
		}
		myObjectUpdateRequisition.onreadystatechange = function (){
			data25 = myObjectUpdateRequisition.responseText;
			if (myObjectUpdateRequisition.readyState == 4) {
				document.getElementById('Store_Order_ID').value = data25;
			}
		}; //specify name of function that will handle server response........

		myObjectUpdateRequisition.open('GET','Store_Ordering_Update_Store_Order_ID.php',true);
		myObjectUpdateRequisition.send();
	}
</script>

<script>
	function updateRequisitionDesc(){
		var Order_Description = document.getElementById("Order_Description").value;

		if(window.XMLHttpRequest){
			myObjectUpdateDescription = new XMLHttpRequest();
		}else if(window.ActiveXObject){
			myObjectUpdateDescription = new ActiveXObject('Micrsoft.XMLHTTP');
			myObjectUpdateDescription.overrideMimeType('text/xml');
		}
		myObjectUpdateDescription.onreadystatechange = function (){
			data27 = myObjectUpdateDescription.responseText;
			if (myObjectUpdateDescription.readyState == 4) {
				//document.getElementById('Order_Description').value = data27;
			}
		}; //specify name of function that will handle server response........

		myObjectUpdateDescription.open('GET','General_Change_Requisition_Description.php?Order_Description='+Order_Description,true);
		myObjectUpdateDescription.send();
	}
</script>

<script>
	function Update_Item_Remark(Store_Order_ID,Item_Remark){
		if(window.XMLHttpRequest){
			myObjectUpdateItemRemark = new XMLHttpRequest();
		}else if(window.ActiveXObject){
			myObjectUpdateItemRemark = new ActiveXObject('Micrsoft.XMLHTTP');
			myObjectUpdateItemRemark.overrideMimeType('text/xml');
		}
		myObjectUpdateItemRemark.onreadystatechange = function (){
			data35 = myObjectUpdateItemRemark.responseText;
			if (myObjectUpdateItemRemark.readyState == 4) {
				//alert(Store_Order_ID);
			}
		}; //specify name of function that will handle server response........

		myObjectUpdateItemRemark.open('GET','General_Update_Item_Remark.php?Store_Order_ID='+Store_Order_ID+'&Item_Remark='+Item_Remark,true);
		myObjectUpdateItemRemark.send();
	}
</script>

<script>
	function updateRequisitionDescription(){
		if(window.XMLHttpRequest){
			myObjectUpdateDescription = new XMLHttpRequest();
		}else if(window.ActiveXObject){
			myObjectUpdateDescription = new ActiveXObject('Micrsoft.XMLHTTP');
			myObjectUpdateDescription.overrideMimeType('text/xml');
		}
		myObjectUpdateDescription.onreadystatechange = function (){
			data26 = myObjectUpdateDescription.responseText;
			if (myObjectUpdateDescription.readyState == 4) {
				document.getElementById('Order_Description').value = data26;
			}
		}; //specify name of function that will handle server response........

		myObjectUpdateDescription.open('GET','General_Update_Requisition_Description.php',true);
		myObjectUpdateDescription.send();
	}
</script>
<script>
	function updateOrderCreatedDate(){
		if(window.XMLHttpRequest){
			myObjectCreatedDate = new XMLHttpRequest();
		}else if(window.ActiveXObject){
			myObjectCreatedDate = new ActiveXObject('Micrsoft.XMLHTTP');
			myObjectCreatedDate.overrideMimeType('text/xml');
		}
		myObjectCreatedDate.onreadystatechange = function (){
			data28 = myObjectCreatedDate.responseText;
			if (myObjectCreatedDate.readyState == 4) {
				document.getElementById('Order_Date').value = data28;
			}
		}; //specify name of function that will handle server response........

		myObjectCreatedDate.open('GET','Update_Store_Created_Date.php',true);
		myObjectCreatedDate.send();
	}
</script>


<script>
	function updateSubmitArea(){
		if(window.XMLHttpRequest){
			myObjectUpdateSubmitArea = new XMLHttpRequest();
		}else if(window.ActiveXObject){
			myObjectUpdateSubmitArea = new ActiveXObject('Micrsoft.XMLHTTP');
			myObjectUpdateSubmitArea.overrideMimeType('text/xml');
		}
		myObjectUpdateSubmitArea.onreadystatechange = function (){
			data29 = myObjectUpdateSubmitArea.responseText;
			if (myObjectUpdateSubmitArea.readyState == 4) {
				document.getElementById('Submit_Button_Area').innerHTML = data29;
			}
		}; //specify name of function that will handle server response........

		myObjectUpdateSubmitArea.open('GET','Store_Order_Update_Submit_Area.php',true);
		myObjectUpdateSubmitArea.send();
	}
</script>

<script>
	function Get_Selected_Item(){
		var Item_ID = document.getElementById("Item_ID").value;
		var Quantity = document.getElementById("Quantity").value;
		var Item_Remark = document.getElementById("Item_Remark").value;
		//var Store_Issue = document.getElementById("Store_Issue").value;
		var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
		var Cont_Quantity = document.getElementById("Cont_Quantity").value;
		var Items_Quantity = document.getElementById("Items_Quantity").value;
		var Order_Description = document.getElementById("Order_Description").value;
		var Last_Buying_Price = document.getElementById("Last_Buying_Price").value;

		if (Item_ID != '' && Item_ID != null && Quantity != '' && Quantity != null) {
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
					document.getElementById("Cont_Quantity").value = '';
					document.getElementById("Items_Quantity").value = '';
					document.getElementById("Item_Remark").value = '';
					alert("Item Added Successfully");
					//updateStoreIssueMenu2();
					updateStoreNeedMenu2();
					update_Store_Order_ID();
					updateOrderCreatedDate();
					updateRequisitionDescription();
					updateSubmitArea();
				}
			}; //specify name of function that will handle server response........

			my_Object_Get_Selected_Item.open('GET','Store_Ordering_Add_Selected_Item.php?Item_ID='+Item_ID+'&Quantity='+Quantity+'&Item_Remark='+Item_Remark+'&Sub_Department_ID='+Sub_Department_ID+'&Order_Description='+Order_Description+'&Cont_Quantity='+Cont_Quantity+'&Items_Quantity='+Items_Quantity+'&Last_Buying_Price='+Last_Buying_Price,true);
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
	function Confirm_Remove_Item(Item_Name,Order_Item_ID){
		var Confirm_Message = confirm("Are you sure you want to remove \n"+Item_Name);

		if (Confirm_Message == true) {
            Sub_Department_ID = document.getElementById('Sub_Department_ID').value;

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

			My_Object_Remove_Item.open('GET','Store_Ordering_Remove_Item_From_List.php?Order_Item_ID='+Order_Item_ID+'&Sub_Department_ID='+Sub_Department_ID,true);
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
			<td width=40%>
				<table width=100%>
					<tr>
						<td style='text-align: center;'>
                            Classification
							<select name='Item_Category_ID' id='Item_Category_ID' style="width: 100%;"
                                    onchange='getItemsList(this.value)' >
							    <option selected='selected' value="All"> All </option>
                                <option value="Pharmaceuticals">Pharmaceuticals</option>
                                <option value="Disposables">Disposables</option>
                                <option value="Dental Materials">Dental Materials</option>
                                <option value="Radiology Materials">Radiology Materials</option>
                                <option value="Laboratory Materials">Laboratory Materials</option>
                                <option value="Stationaries">Stationaries</option>
                                <?php
                                    /*$data = mysqli_query($conn,"
                                        SELECT Item_Category_Name, Item_Category_ID
                                        FROM tbl_item_category
                                        WHERE Category_Type = 'Pharmacy'
                                        ") or die(mysqli_error($conn));
                                    while($row = mysqli_fetch_array($data)){
                                        echo '<option value="'.$row['Item_Category_ID'].'">'.$row['Item_Category_Name'].'</option>';
                                    }*/
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
								    $result = mysqli_query($conn,"SELECT Product_Name, t.Item_ID, t.Unit_Of_Measure, ib.Item_Balance,
								                           IFNULL((SELECT Buying_Price FROM tbl_purchase_order_items poi WHERE t.Item_ID = poi.Item_ID AND Grn_Status = 'RECEIVED' ORDER BY Order_Item_ID DESC LIMIT 1), '0') as Last_Buying_Price
								                           FROM tbl_items t, tbl_items_balance ib
								                           WHERE Classification in ('Pharmaceuticals', 'Dental Materials',
								                                            'Disposables', 'Laboratory Materials',
								                                            'Radiology Materials', 'Stationaries') AND
                                                           t.Item_ID = ib.Item_ID AND
                                                           ib.Sub_Department_ID = '$Sub_Department_ID'
								                           ORDER BY Product_Name
								                           LIMIT 500") or die(mysqli_error($conn));

                                    echo "<tr>";
                                    echo "<td colspan=2><b>Items</b></td>";
                                    echo "<td><b>OUM</b></td>";
                                    echo "<td><b>Balance</b></td>";
                                    echo "<td><b>last buying price</b></td>";
                                    echo "</tr>";
                                    while($row = mysqli_fetch_array($result)){
                                        echo "<tr>";
                                        echo "<td style='color:black; border:2px solid #ccc;text-align: left;'>";
                                ?>

                                              <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>'
                                                     value='<?php echo $row['Product_Name']; ?>'
                                                     onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);">

                                <?php
                                        echo "</td>";
                                        echo "<td style='color:black; border:2px solid #ccc;text-align: left;'>";
                                        echo "<label for='".$row['Item_ID']."'>".$row['Product_Name']."</label>";
                                        echo "</td>";
                                        echo "<td><label for='".$row['Item_ID']."'>".$row['Unit_Of_Measure']."</label></td>";
                                        echo "<td><label for='".$row['Item_ID']."'>".$row['Item_Balance']."</label></td>";
                                        echo "<td><label for='".$row['Item_ID']."'>".$row['Last_Buying_Price']."</label></td>";
                                        echo "</tr>";
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
							<!--table width = 100%>
								<tr>
									<td id='Item_Name_Label'>Item Name</td>
									<td id='Container_Label' style="text-align: center;">Units</td>
									<td id='Items_Label' style="text-align: center;">Items Per Unit</td>
									<td  valign="bottom" id='Quantity_Label' style="text-align: center;">Quantity</td>
									<td id='Last_Buying_Label'>Last Buying Price</td>
									<td id='Balance_Label'>Balance</td>
									<td id='Remark_Label'>Remark</td>
								</tr>
								<tr>
									<td>
										<input type='text' name='Item_Name' id='Item_Name' size=20 placeholder='Item Name' readonly='readonly' required='required'>
										<input type='hidden' name='Item_ID' id='Item_ID' value=''>
									</td>
									<td width="7%">
										<input type='text' style="text-align: center;" name='Cont_Quantity' id='Cont_Quantity' autocomplete='off' size=10 placeholder='Units' onchange='numberOnly(this); Calculate_Quantity();' onkeyup='numberOnly(this); Calculate_Quantity();' onkeypress='numberOnly(this); Calculate_Quantity();' oninput='numberOnly(this); Calculate_Quantity();' onkeypress='numberOnly(this); Calculate_Quantity();'>
									</td>
									<td width="7%">
										<input type='text' style="text-align: center;" name='Items_Quantity' id='Items_Quantity' autocomplete='off' size=10 placeholder='Units' onchange='numberOnly(this); Calculate_Quantity();' onkeyup='numberOnly(this); Calculate_Quantity();' onkeypress='numberOnly(this); Calculate_Quantity();' oninput='numberOnly(this); Calculate_Quantity();' onkeypress='numberOnly(this); Calculate_Quantity();'>
									</td>
									<td width="7%">
										<input type='text' style="text-align: center;" name='Quantity' id='Quantity' autocomplete='off' size=10 placeholder='Qty' onchange='numberOnly(this); Clear_Quantity();' onkeyup='numberOnly(this); Clear_Quantity();' onkeypress='numberOnly(this); Clear_Quantity();' oninput='numberOnly(this); Clear_Quantity();' onkeypress='numberOnly(this); Clear_Quantity();' required='required'>
									</td>
									<td width="10%">
										<input type='text' name='Last_Buying_Price' id='Last_Buying_Price' size=10 placeholder='Last Price' readonly='readonly'>
									</td>
									<td width="10%">
										<input type='text' name='Balance' id='Balance' size=10 placeholder='Balance' readonly='readonly'>
									</td>
									<td width="10%">
										<input type='text' name='Item_Remark' id='Item_Remark' size=30 placeholder='Item Remark'>
									</td>
									<td style="text-align: center; width: 5%;" id='Add_Button_Area'>
										<input type='button' name='submit' id='submit' value='ADD' class='art-button-green' onclick='Get_Selected_Item()'>
										<input type='hidden' name='Add_Requisition_Form' value='true'/>
									</td>
								</tr>
							</table-->
						</td>
					</tr>
					<tr>
						<td>
							<!--<iframe width='100%' src='requisition_items_Iframe.php?Store_Order_ID=<?php echo $Store_Order_ID; ?>' width='100%' height=250px></iframe>-->
							<fieldset style='overflow-y: scroll; height: 299px; background-color:silver' id='Items_Fieldset_List'>
								<?php
									echo '<center><table width = 100% border=0>';
									echo '<tr><td width=4% style="text-align: center; background-color:silver;color:black">Sn</td>
										    <td style="background-color:silver;color:black">Item Name</td>
											<td width=1% style="display:none;text-align: center; background-color:silver;color:black">Units</td>
											<td width=1% style="display:none;text-align: center; background-color:silver;color:black">Items</td>
											<td width=7% style="text-align: center; background-color:silver;color:black">Quantity</td>
                                    		<td width=9% style="text-align: center;"><b>Store Balance</b></td>
                                    		<td width=9% style="text-align: center;"><b>Last Buying Price</b></td>
											<td width=14% style="text-align: center; background-color:silver;color:black">Remark</td>
											<td style="text-align: center; background-color:silver;color:black">Remove</td></tr>';


									$select_Transaction_Items = mysqli_query($conn,"SELECT soi.Order_Item_ID, soi.Last_Buying_Price,
                                                                                    soi.Store_Order_ID, itm.Product_Name,
                                                                                    soi.Quantity_Required, soi.Item_Remark,
                                                                                    soi.Store_Order_ID, soi.Container_Qty,
                                                                                    soi.Items_Qty, ib.Item_Balance
                                                                             FROM tbl_store_order_items soi, tbl_items itm, tbl_items_balance ib
                                                                             WHERE itm.Item_ID = soi.Item_ID AND
                                                                                   itm.Item_ID = ib.Item_ID AND
                                                                                   ib.Sub_Department_ID = '$Sub_Department_ID' AND
																				   soi.Store_Order_ID ='$Store_Order_ID'") or die(mysqli_error($conn));

									$Temp=1;
									while($row = mysqli_fetch_array($select_Transaction_Items)){
										echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
										echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";

                                        echo "<td style='display:none;'><input type='text' id='Container_Qty_".$row['Order_Item_ID']."'
										          value='".$row['Container_Qty']."' style='text-align: center;'
										          onchange='numberOnly(this);Update_Store_Order_Item(".$row['Order_Item_ID'].");'
										          onkeyup='numberOnly(this);Change_Container_And_Items(".$row['Order_Item_ID'].");'
										          onkeypress='numberOnly(this);Change_Container_And_Items(".$row['Order_Item_ID'].");'></td>";

                                        echo "<td style='display:none;'><input type='text' id='Items_Qty_".$row['Order_Item_ID']."'
                                                  value='".$row['Items_Qty']."' style='text-align: center;'
                                                  onchange='numberOnly(this);Update_Store_Order_Item(".$row['Order_Item_ID'].");'
                                                  onkeyup='numberOnly(this);Change_Container_And_Items(".$row['Order_Item_ID'].");'
                                                  onkeypress='numberOnly(this);Change_Container_And_Items(".$row['Order_Item_ID'].");'></td>";

                                        echo "<td><input type='text' class='Quantity_Required_' id='Quantity_Required_".$row['Order_Item_ID']."'
										          value='".$row['Quantity_Required']."' style='text-align: center;'
                                                  onchange='numberOnly(this);Update_Store_Order_Item(".$row['Order_Item_ID'].");'
                                                  onkeyup='numberOnly(this);Change_Quantity_Required(".$row['Order_Item_ID'].");'
                                                  onkeypress='numberOnly(this);Change_Quantity_Required(".$row['Order_Item_ID'].");'></td>";

                                        echo "<td><input type='text' readonly value='".number_format($row['Item_Balance'])."'></td>";
                                        echo "<td><input type='text' readonly value='".number_format($row['Last_Buying_Price'])."'></td>";
										echo "<td><input type='text' id='Item_Remark_Seved' name='Item_Remark_Seved' value='".$row['Item_Remark']."'
										            onchange='Update_Store_Order_Item_Remark(".$row['Order_Item_ID'].",this.value)'></td>";
									?>
										<td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Order_Item_ID']; ?>)'></td>
									<?php
									    echo "</tr>";
									    $Temp++;
									}
									echo '</table>';
								?>
							</fieldset>
						</td>
					</tr>
					<script type='text/javascript'>
						function Submit_Store_Order_Function(Store_Order_ID){
						    document.location = 'Submit_Store_Order.php?Store_Order_ID='+Store_Order_ID;
						}

                        function Change_Container_And_Items(Order_Item_ID){
                            Container_Qty = document.getElementById('Container_Qty_'+Order_Item_ID).value;
                            if (Container_Qty < 1 || Container_Qty =='') {
                                document.getElementById('Container_Qty_'+Order_Item_ID).value = 1; Container_Qty = 1;
                            }
                            Items_Qty = document.getElementById('Items_Qty_'+Order_Item_ID).value;
                            if (Items_Qty =='') {
                                document.getElementById('Items_Qty_'+Order_Item_ID).value = 0; Items_Qty = 0;
                            }
                            Quantity_Required = Container_Qty * Items_Qty;
                            document.getElementById('Quantity_Required_'+Order_Item_ID).value = Quantity_Required;
                        }

                        function Change_Quantity_Required(Order_Item_ID){
                            document.getElementById('Container_Qty_'+Order_Item_ID).value = 1;
                            Quantity_Required = document.getElementById('Quantity_Required_'+Order_Item_ID).value;
                            document.getElementById('Items_Qty_'+Order_Item_ID).value = Quantity_Required;
                        }

                        function Update_Store_Order_Item(Order_Item_ID){
                            Container_Qty = document.getElementById('Container_Qty_'+Order_Item_ID).value;
                            Items_Qty = document.getElementById('Items_Qty_'+Order_Item_ID).value;
                            Quantity_Required = document.getElementById('Quantity_Required_'+Order_Item_ID).value;

                            if(window.XMLHttpRequest){
                                myObjectUpdateStoreOrderItem = new XMLHttpRequest();
                            }else if(window.ActiveXObject){
                                myObjectUpdateStoreOrderItem = new ActiveXObject('Micrsoft.XMLHTTP');
                                myObjectUpdateStoreOrderItem.overrideMimeType('text/xml');
                            }

                            myObjectUpdateStoreOrderItem.onreadystatechange = function (){
                                data200 = myObjectUpdateStoreOrderItem.responseText;
                                if (myObjectUpdateStoreOrderItem.readyState == 4) {
                                    var feedback = data200;
                                    if (feedback == 'Yes'){
                                        //Updated
                                    }
                                }
                            };

                            myObjectUpdateStoreOrderItem.open('GET','Store_Ordering_Update_Store_Order_Item.php?Order_Item_ID='+Order_Item_ID+'&Container_Qty='+Container_Qty+'&Items_Qty='+Items_Qty+'&Quantity_Required='+Quantity_Required,true);
                            myObjectUpdateStoreOrderItem.send();
                        }

                        function Update_Store_Order_Item_Remark(Order_Item_ID, Item_Remark){
                            if(window.XMLHttpRequest){
                                myObjectUpdateStoreOrderItemRemark = new XMLHttpRequest();
                            }else if(window.ActiveXObject){
                                myObjectUpdateStoreOrderItemRemark = new ActiveXObject('Micrsoft.XMLHTTP');
                                myObjectUpdateStoreOrderItemRemark.overrideMimeType('text/xml');
                            }

                            myObjectUpdateStoreOrderItemRemark.onreadystatechange = function (){
                                data200 = myObjectUpdateStoreOrderItemRemark.responseText;
                                if (myObjectUpdateStoreOrderItemRemark.readyState == 4) {
                                    var feedback = data200;
                                    if (feedback == 'Yes'){
                                        //Updated
                                    }
                                }
                            };

                            myObjectUpdateStoreOrderItemRemark.open('GET','Store_Ordering_Update_Store_Order_Item_Remark.php?Order_Item_ID='+Order_Item_ID+'&Item_Remark='+Item_Remark,true);
                            myObjectUpdateStoreOrderItemRemark.send();
                        }

					</script>
					<script>
						function Confirm_Submit_Store_Order(){
                            var hasError = false;
                            $.each($(".Quantity_Required_"), function(i, Quantity_Required) {
                                if (parseInt($(Quantity_Required).val()) < 1) {
                                    $(Quantity_Required).attr("style", "border: 3px solid red");
                                    hasError = true;
                                } else {
                                    $(Quantity_Required).attr("style", "border: 1px solid #B9B59D");
                                }
                            });

                            if (hasError) {
                                alert("Some items have Zero (0) Quantity!!");
                            } else {
                                var Store_Order_ID = '<?php echo $Store_Order_ID; ?>';
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
                                            var r = confirm("Are you sure you want to submit this store order?\n\nClick OK to proceed");
                                            if(r == true){
                                                Submit_Store_Order_Function(Store_Order_ID);
                                            }
                                        }else{
                                            alert("This Store Order may either already submitted or\n Store order contains no items\n");
                                        }
                                    }
                                }; //specify name of function that will handle server response........

                                myObjectCheckItemNumber.open('GET','Store_Order_Check_Number_Of_Items.php',true);
                                myObjectCheckItemNumber.send();
                            }
						}
                    </script>
                    <script>
                        function Clear_Store_Order_Items(Store_Order_ID){
                            var check = confirm("Are you sure you want to clear this store order?\n\nClick OK to proceed");
                            if (check) {
                                if(window.XMLHttpRequest){
                                    myObjectClearStoreOrder = new XMLHttpRequest();
                                }else if(window.ActiveXObject){
                                    myObjectClearStoreOrder = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObjectClearStoreOrder.overrideMimeType('text/xml');
                                }

                                myObjectClearStoreOrder.onreadystatechange = function (){
                                    data200 = myObjectClearStoreOrder.responseText;
                                    if (myObjectClearStoreOrder.readyState == 4) {
                                        var feedback = data200;
                                        if (feedback == 'Yes'){
                                            location.reload();
                                        }
                                    }
                                }

                                myObjectClearStoreOrder.open('GET','Store_Order_Clear_Items.php?Store_Order_ID='+Store_Order_ID,true);
                                myObjectClearStoreOrder.send();
                            }
                        }
					</script>
					<tr>
						<td id='Submit_Button_Area' style='text-align: right;'>
							<?php
								if(isset($_SESSION['General_Order_ID'])){
									?>
                                        <input type='button' class='art-button-green' value='CLEAR' onclick='Clear_Store_Order_Items(<?php echo $Store_Order_ID; ?>);'/>
                                        <input type='button' class='art-button-green' value='SUBMIT STORE ORDER' onclick='Confirm_Submit_Store_Order();'/>
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


<script>

    $(document).ready(function(){
        <?php if (isset($_GET['status']) && $_GET['status'] == "new") {?>
            Check_If_Existing_Pending_Store_Order();
        <?php } ?>
    });

    function Check_If_Existing_Pending_Store_Order(){
        Sub_Department_ID = document.getElementById('Sub_Department_ID').value;

        if(window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function (){
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                if(data != "") {
                    Store_Order_ID = data;
                    var r = confirm("Are you sure you want to start a new store order?\n\nClick OK to start NEW store order \n\n Cancel to proceed with the existing store order");
                    if(r == true){
                        if(window.XMLHttpRequest) {
                            mySavePointObject = new XMLHttpRequest();
                        }else if(window.ActiveXObject){
                            mySavePointObject = new ActiveXObject('Micrsoft.XMLHTTP');
                            mySavePointObject.overrideMimeType('text/xml');
                        }

                        mySavePointObject.onreadystatechange = function (){
                            data = mySavePointObject.responseText;
                            if (mySavePointObject.readyState == 4) {
                                if(data == "1") {
                                    alert("Your order has been saved! You can now find it under VIEW/EDIT page!");
                                }
                            }
                        }; //specify name of function that will handle server response........
                        mySavePointObject.open('GET','storeordering_savepointstoreorder.php?Store_Order_ID='+Store_Order_ID,true);
                        mySavePointObject.send();
                    } else {
                        window.location = "storeordering.php?status=edit&Store_Order_ID=<?php echo $_SESSION['Last_Store_Order_ID']; ?>&NPO=True&StoreOrder=StoreOrderThisPage";
                    }
                }
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET','storeordering_pendingstoreorderid.php?Sub_Department_ID='+Sub_Department_ID,true);
        myObject.send();
    }
</script>

<?php
  include("./includes/footer.php");
?>