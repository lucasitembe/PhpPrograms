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
			echo "<a href='Control_Return_Inward_Sessions.php?New_Return_Inward=True&Status=new' class='art-button-green'>NEW RETURN INWARD</a>";
		}
	}

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
            echo "<a href='pendingreturninwards.php?PendingReturnInward=PendingReturnInwardThisPage' class='art-button-green'>PENDING RETURN INWARDS</a>";
        }
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
            echo "<a href='previousreturninwards.php?PreviousReturnInward=PreviousReturnInwardThisPage' class='art-button-green'>PREVIOUS RETURN INWARDS</a>";
        }
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
            echo "<a href='returninwardoutwardworks.php?ReturnInwardOutward=ReturnInwardOutwardThisPage' class='art-button-green'>BACK</a>";
        }
    }

	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }

    if(isset($_SESSION['General_Inward_ID'])){
        $Inward_ID = $_SESSION['General_Inward_ID'];
    }else{
        $Inward_ID = 0;
    }

    //get store receiving if session exists
    $Store_Receiving_ID = Get_Store_Receiving_Or_Returning_Department($Inward_ID, false);

    //get returning department if session exists
    $Returning_Department_ID = Get_Store_Receiving_Or_Returning_Department($Inward_ID, true);

    function Get_Store_Receiving_Or_Returning_Department($Inward_ID,$For_Receiving){
        $Select_Store_Receiving_Or_Returning_Department_SQL = "SELECT
                                                                    sd.Sub_Department_ID
                                                                FROM
                                                                    tbl_sub_department sd, tbl_return_inward ri
                                                                WHERE ";
        if($For_Receiving) {
            $Select_Store_Receiving_Or_Returning_Department_SQL .= " sd.Sub_Department_ID = ri.Return_Sub_Department_ID AND ";
        } else {
            $Select_Store_Receiving_Or_Returning_Department_SQL .= " sd.Sub_Department_ID = ri.Store_Sub_Department_ID AND ";
        }

        $Select_Store_Receiving_Or_Returning_Department_SQL .= " ri.Inward_ID = ".$Inward_ID;

        $Select_Store_Receiving_Or_Returning_Department = mysqli_query($conn,$Select_Store_Receiving_Or_Returning_Department_SQL) or die(mysqli_error($conn));
        $Select_Store_Receiving_Or_Returning_Department_Rows = mysqli_num_rows($Select_Store_Receiving_Or_Returning_Department);
        if($Select_Store_Receiving_Or_Returning_Department_Rows > 0){
            while ($Select_Store_Receiving_Or_Returning_Department_Data = mysqli_fetch_array($Select_Store_Receiving_Or_Returning_Department)) {
                $Store_Receiving_Or_Returning_Department_ID = $Select_Store_Receiving_Or_Returning_Department_Data['Sub_Department_ID'];
            }
        }else{
            $Store_Receiving_Or_Returning_Department_ID = '';
        }
        return $Store_Receiving_Or_Returning_Department_ID;
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



<br/><br/>
<fieldset>
	<legend style="background-color:#006400;color:white" align='right'><b>RETURN INWARDS</b></legend>
<style>
	table,tr,td{
		border-collapse:collapse !important;
		border:none !important;
	}
</style> 
<table width="100%">
	<tr>
		<td style="text-align: right;">Store Receiving</td>
		<td style="text-align: left;" id="Store_Receiving_Area">
			<select name="Store_Receiving" id="Store_Receiving">
				<option selected='selected'></option>
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
                            <option value="<?php echo $data['Sub_Department_ID']; ?>"
                                <?php if($Store_Receiving_ID == $data['Sub_Department_ID']) { echo "selected='selected'";} ?>
                                ><?php echo $data['Sub_Department_Name']; ?></option>
                <?php
				}
			}
		?>
			</select>
		</td>

		<td style="text-align: right;">Returned From</td>
		<td style="text-align: left;" id="Returned_From_Area">
			<select name="Returned_From" id="Returned_From">
				<option selected='selected'></option>
                <?php
                    //select sub department
                    $select = mysqli_query($conn,"select Sub_Department_ID, Sub_Department_Name from
                                            tbl_department dep, tbl_sub_department sdep
                                            where dep.department_id = sdep.department_id and
                                            Department_Location <> 'Storage And Supply'") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($select);
                    if($num > 0){
                        while ($data = mysqli_fetch_array($select)) {
                ?>
                            <option value="<?php echo $data['Sub_Department_ID']; ?>"
                                <?php if($Returning_Department_ID == $data['Sub_Department_ID']) { echo "selected='selected'";} ?>
                                ><?php echo $data['Sub_Department_Name']; ?></option>
                <?php
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
        var Store_Receiving = document.getElementById("Store_Receiving").value;
        var Returned_From = document.getElementById("Returned_From").value;

		var Item_ID = document.getElementById("Item_ID").value;
		var Return_Quantity = document.getElementById("Return_Quantity").value;
        var Item_Remark = document.getElementById("Item_Remark").value;

		if (Item_ID != '' && Item_ID != null && Return_Quantity != '' && Return_Quantity != null
            && Store_Receiving != null && Store_Receiving != '' && Returned_From != null && Returned_From != '') {
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
                    Clear_Item_Fields();
                    Update_Store_Receiving();
                    Update_Returned_From();
                    Update_Submit_Button();
                }
            };

            my_Object_Get_Selected_Item.open('GET','Inwards_Add_Selected_Item.php?Item_ID='+Item_ID+'&Return_Quantity='+Return_Quantity+'&Item_Remark='+Item_Remark+'&Store_Receiving='+Store_Receiving+'&Returned_From='+Returned_From,true);
            my_Object_Get_Selected_Item.send();

		    
		}else if ((Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) &&
            Return_Quantity != '' && Return_Quantity != null){
			alertMessage();
		}else{
			if(Return_Quantity=='' || Return_Quantity == null){
				document.getElementById("Return_Quantity").focus();
				document.getElementById("Return_Quantity").style = 'border: 3px solid red';
			}else{
				document.getElementById("Return_Quantity").style = 'border: 3px';
			}
		}
	}

    function Update_Store_Receiving(){
        if(window.XMLHttpRequest) {
            myObject_Update_Store_Issue = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject_Update_Store_Issue = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject_Update_Store_Issue.overrideMimeType('text/xml');
        }

        myObject_Update_Store_Issue.onreadystatechange = function (){
            data980 = myObject_Update_Store_Issue.responseText;
            if (myObject_Update_Store_Issue.readyState == 4) {
                document.getElementById('Store_Receiving_Area').innerHTML = data980;
            }
        }; //specify name of function that will handle server response........
        myObject_Update_Store_Issue.open('GET','Return_Inward_Update_Store_Receiving.php',true);
        myObject_Update_Store_Issue.send();
    }

    function Update_Returned_From(){
        if(window.XMLHttpRequest) {
            myObject_Update_Supplier = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject_Update_Supplier = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject_Update_Supplier.overrideMimeType('text/xml');
        }

        myObject_Update_Supplier.onreadystatechange = function (){
            data990 = myObject_Update_Supplier.responseText;
            if (myObject_Update_Supplier.readyState == 4) {
                document.getElementById('Returned_From_Area').innerHTML = data990;
            }
        }; //specify name of function that will handle server response........
        myObject_Update_Supplier.open('GET','Return_Inward_Update_Returned_From.php',true);
        myObject_Update_Supplier.send();
    }

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
        myObject_Update_Submit_button.open('GET','Return_Inward_Update_Submit_Button.php',true);
        myObject_Update_Submit_button.send();
    }


	function alertMessage(){
		alert("Please Select Item First");
		document.getElementById("Return_Quantity").value = '';
	}


    function Update_Item_Remark(Inward_Item_ID, Item_Remark){

    }

	function Confirm_Remove_Item(Item_Name,Inward_Item_ID){
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
				
			My_Object_Remove_Item.open('GET','Return_Inward_Remove_Item_From_List.php?Inward_Item_ID='+Inward_Item_ID,true);
			My_Object_Remove_Item.send();
		}
	}
</script>


<script type="text/javascript">
	function Clear_Quantity(){
        Return_Quantity = document.getElementById("Return_Quantity").value = document.getElementById("Return_Quantity").value;
	}
    function Clear_Item_Fields(){
        document.getElementById("Item_ID").value = '';
        document.getElementById("Item_Name").value = '';

        document.getElementById("Return_Quantity").value = '';
        document.getElementById("Item_Remark").value = '';

        document.getElementById("Unit_Of_Measure").value = '';
        document.getElementById("Store_Balance").value = '';
        document.getElementById("Issuing_Store_Balance").value = '';
        document.getElementById("Item_Price").value = '';
        document.getElementById("Item_Total_Price").value = '';
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
							<fieldset style='overflow-y: scroll; height: 305px;' id='Items_Fieldset'>

							<table width=100%>

							    <?php
								$result = mysqli_query($conn,"SELECT Product_Name, Item_ID, Unit_Of_Measure FROM tbl_items where Item_Type = 'Pharmacy' or Item_Type = 'Others' order by Product_Name limit 200");
								while($row = mysqli_fetch_array($result)){
								    echo "<tr>
									<td style='color:black; border:2px solid #ccc;text-align: left;'>"; ?>

									    <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>,'<?php echo $row['Unit_Of_Measure']; ?>');">

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
									<td id='UOM_Label' style="text-align: center;">UOM</td>
									<td id='Store_Balance_Label' style="text-align: center;">Store Balance</td>
                                    <td id='Issuing_Store_Balance_Label' style="text-align: center;">Issuing Store Balance</td>
                                    <td id='Quantity_Returned_Label' style="text-align: center;">Quantity Returned</td>
                                    <td id='Item_Price_Label' style="text-align: center;">Item Price</td>
                                    <td id='Total_Label' style="text-align: center;">Total</td>
									<td id='Remark_Label'>Remark</td> 
								</tr>
								<tr>
									<td>
										<input type='text' name='Item_Name' id='Item_Name' size=20 placeholder='Item Name' readonly='readonly' required='required'>
										<input type='hidden' name='Item_ID' id='Item_ID' value=''>
									</td>
									<td width="12%">
										<input type='text' style="text-align: center;" name='Unit_Of_Measure' id='Unit_Of_Measure' autocomplete='off' size=10 placeholder='OUM' onchange='numberOnly(this); Clear_Quantity();' onkeyup='numberOnly(this); Clear_Quantity();' onkeypress='numberOnly(this); Clear_Quantity();' oninput='numberOnly(this); Clear_Quantity();' onkeypress='numberOnly(this); Clear_Quantity();' required='required'>
									</td>
                                    <td width="10%">
                                        <input type='text' style="text-align: center;" name='Store_Balance' id='Store_Balance' size=10 placeholder='Store Balance' readonly='readonly'>
                                    </td>
                                    <td width="10%">
                                        <input type='text' style="text-align: center;" name='Issuing_Store_Balance' id='Issuing_Store_Balance' size=10 placeholder='Issuing Store Balance' readonly='readonly'>
                                    </td>
                                    <td width="10%">
                                        <input type='text' style="text-align: center;" name='Return_Quantity' id='Return_Quantity' size=10 placeholder='Quantity Returned' onchange='numberOnly(this); Get_Item_Price_Total();' onkeyup='numberOnly(this); Get_Item_Price_Total();' onkeypress='numberOnly(this); Get_Item_Price_Total();' oninput="numberOnly(this); Get_Item_Price_Total();">
                                    </td>
                                    <td width="10%">
                                        <input type='text' style="text-align: center;" name='Item_Price' id='Item_Price' size=10 placeholder='Item Price' readonly='readonly' >
                                    </td>
                                    <td width="10%">
                                        <input type='text' style="text-align: center;" name='Item_Total_Price' id='Item_Total_Price' size=10 placeholder='Total' readonly='readonly'>
                                    </td>
									<td width="10%">
										<input type='text' style="text-align: center;" name='Item_Remark' id='Item_Remark' size=30 placeholder='Item Remark'>
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
							<!--<iframe width='100%' src='requisition_items_Iframe.php?Store_Order_ID=<?php echo $Store_Order_ID; ?>' width='100%' height=250px></iframe>-->
							<fieldset style='overflow-y: scroll; height: 299px; background-color:silver' id='Items_Fieldset_List'>
								<?php
                                    echo '<center><table width = 100% border=0>';
                                    echo '<tr><td width=4% style="text-align: center; background-color:silver;color:black">Sn</td>
                                            <td style="background-color:silver;color:black">Item Name</td>
                                            <td width=10% style="text-align: center;">UoM</td>
                                            <td width=10% style="text-align: center;">Qty Returned</td>
                                            <td width=18% style="text-align: left;">Remark</td>
                                            <td style="text-align: center;" width="7%">Remove</td></tr>';


                                    $select_Transaction_Items = mysqli_query($conn,"SELECT
                                                                                rii.Inward_Item_ID, rii.Inward_ID, itm.Product_Name,
                                                                                rii.Quantity_Returned, rii.Item_Remark, itm.Unit_Of_Measure
                                                                             FROM
                                                                                tbl_return_inward_items rii, tbl_items itm
                                                                             WHERE
                                                                                itm.Item_ID = rii.Item_ID AND
                                                                                rii.Inward_ID ='$Inward_ID'") or die(mysqli_error($conn));
								    
									$Temp=1;
									while($row = mysqli_fetch_array($select_Transaction_Items)){
                                        echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                                        echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
                                        echo "<td><input type='text' readonly='readonly' value='".$row['Unit_Of_Measure']."' style='text-align: center;'></td>";
                                        echo "<td><input type='text' readonly='readonly' value='".$row['Quantity_Returned']."' style='text-align: center;'></td>";
                                        echo "<td><input type='text' id='Item_Remark_Saved' name='Item_Remark_Saved' value='".$row['Item_Remark'].
                                            "' onclick='Update_Item_Remark(".$row['Inward_Item_ID'].",this.value)' onkeyup='Update_Item_Remark("
                                            .$row['Inward_Item_ID'].",this.value)'></td>";
                                        ?>
                                        <td width=6%>
                                            <input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'
                                                onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Inward_Item_ID']; ?>)'>
                                        </td>
									<?php
									    echo "</tr>";
									    $Temp++;
									}
									echo '</table>';
								?>
							</fieldset>
						</td>
					</tr>

			<script type="text/javascript">
				function Get_Item_Name(Item_Name,Item_ID,Unit_Of_Measure){
                    var Store_Receiving = document.getElementById("Store_Receiving").value;
                    var Returned_From = document.getElementById("Returned_From").value;
                    if (Store_Receiving == '' || Returned_From == '') {
                        alert('Please make sure Store Receiving and Returned From are selected');
                    } else {
                        document.getElementById("Item_ID").value = Item_ID;
                        document.getElementById("Item_Name").value = Item_Name;
                        document.getElementById("Unit_Of_Measure").value = Unit_Of_Measure;

                        document.getElementById("Return_Quantity").value = '';

                        Get_Balance(Item_ID, Store_Receiving, Returned_From);
                        Get_Price(Item_ID);
                    }
				}

                function Get_Balance(Item_ID, Store_Receiving, Returned_From){
                    if(window.XMLHttpRequest) {
                        myObject_Get_Store_Receiving_Balance = new XMLHttpRequest();
                        myObject_Get_Returned_From_Balance = new XMLHttpRequest();
                    }else if(window.ActiveXObject){
                        myObject_Get_Store_Receiving_Balance = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObject_Get_Store_Receiving_Balance.overrideMimeType('text/xml');
                        myObject_Get_Returned_From_Balance = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObject_Get_Returned_From_Balance.overrideMimeType('text/xml');
                    }

                    myObject_Get_Store_Receiving_Balance.onreadystatechange = function (){
                        data98 = myObject_Get_Store_Receiving_Balance.responseText;
                        if (myObject_Get_Store_Receiving_Balance.readyState == 4) {
                            document.getElementById('Store_Balance').value = data98;
                        }
                    };
                    myObject_Get_Store_Receiving_Balance.open('GET','Return_Inward_Get_Balance.php?Item_ID='+Item_ID+'&Sub_Department_ID='+Store_Receiving,true);
                    myObject_Get_Store_Receiving_Balance.send();

                    myObject_Get_Returned_From_Balance.onreadystatechange = function (){
                        data98 = myObject_Get_Returned_From_Balance.responseText;
                        if (myObject_Get_Returned_From_Balance.readyState == 4) {
                            document.getElementById('Issuing_Store_Balance').value = data98;
                        }
                    };
                    myObject_Get_Returned_From_Balance.open('GET','Return_Inward_Get_Balance.php?Item_ID='+Item_ID+'&Sub_Department_ID='+Returned_From,true);
                    myObject_Get_Returned_From_Balance.send();
                }

                function Get_Price(Item_ID){
                    if(window.XMLHttpRequest) {
                        myObject_Get_Item_Price = new XMLHttpRequest();
                    }else if(window.ActiveXObject){
                        myObject_Get_Item_Price = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObject_Get_Item_Price.overrideMimeType('text/xml');
                    }

                    myObject_Get_Item_Price.onreadystatechange = function (){
                        data98 = myObject_Get_Item_Price.responseText;
                        if (myObject_Get_Item_Price.readyState == 4) {
                            document.getElementById('Item_Price').value = data98;
                        }
                    };
                    myObject_Get_Item_Price.open('GET','Return_Inward_Get_Item_Price.php?Item_ID='+Item_ID,true);
                    myObject_Get_Item_Price.send();
                }

                function Get_Item_Price_Total() {
                    var Item_ID = document.getElementById("Item_ID").value;
                    if (Item_ID != null && Item_ID != '') {
                        var Return_Quantity = document.getElementById("Return_Quantity").value;
                        var Item_Price = document.getElementById("Item_Price").value;

                        var Item_Total_Price = Return_Quantity * Item_Price;
                        document.getElementById("Item_Total_Price").value = Item_Total_Price;
                    }
                }

                function Submit_Store_Order_Function(Store_Order_ID){
                    document.location = 'Submit_Store_Order.php?Store_Order_ID='+Store_Order_ID;
                }
            </script>

            <script>
                function Confirm_Submit_Store_Order(){
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
                    //}
                }
            </script>

            <script>
                function Submit_Inward_Function(Inward_ID){
                    document.location = 'Submit_Return_Inward.php?Inward_ID='+Inward_ID;
                }

                function Confirm_Submit_Inward(){
                    var Inward_ID = '<?php echo $Inward_ID; ?>';
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
                                var r = confirm("Are you sure you want to submit this return inward?\n\nClick OK to proceed");
                                if(r == true){
                                    Submit_Inward_Function(Inward_ID);
                                }
                            }else{
                                alert("This return inward may either already submitted or contains no items\n");
                            }
                        }
                    };

                    myObjectCheckItemNumber.open('GET','Return_Inward_Check_Number_Of_Items.php',true);
                    myObjectCheckItemNumber.send();
                }
            </script>

					<tr>
						<td id='Submit_Button_Area' style='text-align: right;'>
							<?php if(isset($_SESSION['General_Inward_ID'])){ ?>
                                <input type='button' class='art-button-green' value='SUBMIT INWARD TRANSACTION'
                                    onclick='Confirm_Submit_Inward()'>
                            <?php } ?>
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
?>*/