<script src='js/functions.js'></script>
<?php
    include("./includes/header.php");
    include("./includes/connection.php");
        
	//get employee name
	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Employee_Name = 'Unknown Requisitioner Officer';
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
	
	
	//get requisition id
	if(isset($_SESSION['General_Requisition_ID'])){
		$Requisition_ID = $_SESSION['General_Requisition_ID'];
	}else{
		$Requisition_ID = 0;
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
			echo "<a href='Control_Requisition_Sessions.php?New_Requisition_General=True' class='art-button-green'>NEW REQUISITION</a>";
		}
	}
	
        if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
			echo "<a href='pendingrequisitions.php?PendingRequisitions=PendingRequisitionsThisPage' class='art-button-green'>PENDING REQUISITIONS</a>";
		}
	}
	
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
			echo "<a href='previousrequisitions.php?PreviousRequisitions=PreviousRequisitionsThisPage' class='art-button-green'>PREVIOUS REQUISITIONS</a>";
		}
	}
	
	if(isset($_GET['Requisition_ID'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
			echo "<a href='submit_this_requisition.php?Requisition_ID=".$_GET['Requisition_ID']."' class='art-button-green'>SUBMIT THIS REQUISITION</a>";
		}
	}
	
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
			echo "<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>BACK</a>";
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

<?php
  //get order information if and only if inserted
  if(isset($_GET['Requisition_ID'])){
          $Requisition_ID = $_GET['Requisition_ID'];
          $select_Requisition_Details = mysqli_query($conn,"
            select rq.Requisition_ID, rq.Requisition_Description, sdep.sub_department_name as Store_Issue, rq.Created_Date_Time 
	    from tbl_requisition rq, tbl_requisition_items rqi, tbl_sub_department sdep where
                rq.Requisition_ID = rqi.Requisition_ID and
                  rq.Store_Issue = sdep.Sub_department_ID and
                      rq.Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
          $no = mysqli_num_rows($select_Requisition_Details);
          if($no > 0){
            while($row = mysqli_fetch_array($select_Requisition_Details)){
              $Requisition_ID = $row['Requisition_ID'];
              $Requisition_Description = $row['Requisition_Description'];
              $Created_Date_Time = $row['Created_Date_Time'];
              $Store_Issue = $row['Store_Issue'];
            }
          }else{
            $Requisition_ID = '';
            $Requisition_Description = '';
            $Created_Date_Time = '';
            $Store_Issue = '';
          }
          
  }else{
          $Requisition_ID = 0;
  }


?>



<form action='#' method='post' name='myForm' id='myForm' >
<br/>
<fieldset> <legend style="background-color:#006400;color:white" align='right'><b>General Requisition</b></legend>  

 <style>
		table,tr,td{
		border-collapse:collapse !important;
		border:none !important;
		}
</style> 
	<table style="margin-top:5" width=100%>
		<tr>
		
			<td width='12%' style='text-align: right;'>Requisition Number</td>
			<td width=5%>
				<?php if(isset($_SESSION['General_Requisition_ID'])){ ?>
					<input type='text' name='Requisition_Number' size=6 id='Requisition_Number' readonly='readonly' value='<?php echo $_SESSION['General_Requisition_ID']; ?>'>
				<?php }else{ ?>
					<input type='text' name='Requisition_Number' size=6  id='Requisition_Number' value='new'>
				<?php } ?>
			</td>
			<td width='13%' style='text-align: right;'>
			    Requisition Description
			</td>
			<td>
				<?php if(isset($_SESSION['General_Requisition_ID'])){
					//get requisition description
					$Requisition_ID = $_SESSION['General_Requisition_ID'];
					$get_details = mysqli_query($conn,"select Requisition_Description from tbl_requisition where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
					$num = mysqli_num_rows($get_details);
					if($num > 0){
					    while($row = mysqli_fetch_array($get_details)){
						$Requisition_Description = $row['Requisition_Description'];
					    }
					}else{
					    $Requisition_Description = '';
					}
				?>
				<input type='text' name='Requisition_Description' id='Requisition_Description' value='<?php echo $Requisition_Description; ?>' onclick='updateRequisitionDesc()' onkeyup='updateRequisitionDesc()'>
				<?php }else{ ?>
					<input type='text' name='Requisition_Description' id='Requisition_Description'>
				<?php } ?>
			</td>
			<td width='12%' style='text-align: right;'>Requisition Date</td>
			<td width='16%'>
				<?php if(isset($_SESSION['General_Requisition_ID'])){
					//get requisition date
					$Requisition_ID = $_SESSION['General_Requisition_ID'];
					$get_details = mysqli_query($conn,"select Created_Date_Time from tbl_requisition where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
					$num = mysqli_num_rows($get_details);
					if($num > 0){
					    while($row = mysqli_fetch_array($get_details)){
						$Created_Date_Time = $row['Created_Date_Time'];
					    }
					}else{
					    $Created_Date_Time = '';
					}
				?>
					<input type='text' readonly='readonly' name='Requisition_Date' id='Requisition_Date' value='<?php echo $Created_Date_Time; ?>'>
				<?php }else{ ?>
					<input type='text' readonly='readonly' name='Requisition_Date' id='Requisition_Date'>
				<?php } ?>
			</td>
		</tr>
		<tr>
                  <td width='10%' style='text-align: right;'>Store Requesting</td>
                  <td width='16%' id="Store_Need_Area">
                    <select name='Store_Need' id='Store_Need' required='required'>
                    <?php
                    	//check pending Store Requestion
                    	if(isset($_SESSION['General_Requisition_ID'])){
                    		$Requisition_ID = $_SESSION['General_Requisition_ID'];
                    		$Get_Store_Request = mysqli_query($conn,"select Sub_Department_Name, Sub_Department_ID from tbl_sub_department sd, tbl_requisition rq where
                    		 									Sub_Department_ID = rq.Store_Need and
                    		 									rq.Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
                    		$nms = mysqli_num_rows($Get_Store_Request);
                    		if($nms > 0){
                    			while ($dt = mysqli_fetch_array($Get_Store_Request)) {
                    				echo "<option value='".$dt['Sub_Department_ID']."'>".ucwords(strtolower($dt['Sub_Department_Name']))."</option>";
                    			}
                    		}
                    	}else{
                    ?>
                    	<option selected="selected" value=""></option>
                      	<?php
                        	//display sub department name
                      		$select = mysqli_query($conn,"select sd.Sub_Department_Name, sd.Sub_Department_ID from 
                      								tbl_sub_department sd, tbl_employee_sub_department esd where
                      								esd.Sub_Department_ID = sd.Sub_Department_ID and
                      								esd.Employee_ID = '$Employee_ID' order by Sub_Department_Name") or die(mysqli_error($conn));
                      		$nm = mysqli_num_rows($select);
                      		if($nm > 0){
                      			while ($data = mysqli_fetch_array($select)) {
                      	?>
                      			<option value="<?php echo $data['Sub_Department_ID']; ?>"><?php echo ucwords(strtolower($data['Sub_Department_Name'])); ?></option>
                      	<?php
                      			}
                      		}
                      	}
                        ?>
                    </select>
			</td>
		<td style='text-align: right;'>Store Issue</td>
		<td id='Store_Issue_Area'>			
			<?php
				if(isset($_SESSION['General_Requisition_ID']) && $_SESSION['General_Requisition_ID'] != '' && $_SESSION['General_Requisition_ID'] != null){
					$Requisition_ID = $_SESSION['General_Requisition_ID'];
					//select store issue via session requisition id
					$select_store_issue = mysqli_query($conn,"select Store_Issue from tbl_requisition where
										Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
					$no = mysqli_num_rows($select_store_issue);
					if($no > 0){
						while($row = mysqli_fetch_array($select_store_issue)){
							$Store_Issue = $row['Store_Issue'];
						}
					}else{
						$Store_Issue = 0;
					}
					
					if($Store_Issue != 0){
						//get sub department name
						$select_sub_department = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where
											Sub_Department_ID = '$Store_Issue'") or die(mysqli_error($conn));
						$no = mysqli_num_rows($select_sub_department);
						if($no > 0){
							while($row = mysqli_fetch_array($select_sub_department)){
								$Sub_Department_Name = $row['Sub_Department_Name'];
							}
						}else{
							$Sub_Department_Name = '';
						}
					}else{
						$Sub_Department_Name = '';
					}
					?>
						<select name='Store_Issue' id='Store_Issue' required='required'>
							<option selected='selected' value='<?php echo $Store_Issue; ?>'><?php echo ucfirst($Sub_Department_Name); ?></option>
						</select>
					<?php
				}else{
					$Current_Sub_Department = $_SESSION['Storage'];
					$select_Supplier = mysqli_query($conn,"select * from tbl_sub_department order by Sub_Department_Name");
					echo "<select name='Store_Issue' id='Store_Issue' required='required'>";
					echo "<option selected='selected'></option>";
					while($row = mysqli_fetch_array($select_Supplier)){
						echo "<option value='".$row['Sub_Department_ID']."'>".ucwords(strtolower($row['Sub_Department_Name']))."</option>";
					}
					echo "</select>";
				}
			?>
		</td>
		    <td style='text-align: right;'>Requisitioner Officer</td> 
			<td>
				<input type='text' readonly='readonly' value='<?php echo $Employee_Name; ?>'>
			</td>
		</tr>
        </table> 
</center>
</fieldset>

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
	function Get_Item_Name(Item_Name,Item_ID){
		var Temp = '';
		var Store_Need = document.getElementById("Store_Need").value;

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
				document.getElementById("Items_Label").innerHTML = '<b>Items per Unit</b>';
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
				document.getElementById("Items_Label").innerHTML = 'Items per Container';
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
				//document.getElementById("Quantity").removeAttribute("ReadOnly");
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
		myObjectGetItemName.open('GET','General_Check_Item_Selected.php?Item_ID='+Item_ID+'&Store_Need='+Store_Need,true);
		myObjectGetItemName.send();
		
		
		
		if (Item_ID != null && Item_ID != '' && Store_Need != null && Store_Need != '') {
			document.getElementById("Item_Name").value = Item_Name;
			document.getElementById("Item_ID").value = Item_ID;
			document.getElementById("Quantity").focus();
			Items_Quantity = document.getElementById("Items_Quantity").value = '';
			Cont_Quantity = document.getElementById("Cont_Quantity").value = '';
			Get_Balance();
		}else{
			if(Store_Need == null || Store_Need == ''){
				document.getElementById("Store_Need").style = 'border: 3px solid red';
			}
		}
	}
</script>


<script>
	function Get_Balance(){
		var Item_ID = document.getElementById("Item_ID").value;
		var Store_Need = document.getElementById("Store_Need").value;

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
			
		myObjectGetBalance.open('GET','General_Get_Item_Expected_Balance.php?Item_ID='+Item_ID+'&Store_Need='+Store_Need,true);
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
		var Store_Need = document.getElementById("Store_Need").value;
		if(window.XMLHttpRequest) {
		    myObjectGetStoreNeed = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    myObjectGetStoreNeed = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectGetStoreNeed.overrideMimeType('text/xml');
		}
	    
		myObjectGetStoreNeed.onreadystatechange = function (){
			data2990 = myObjectGetStoreNeed.responseText;
			if (myObjectGetStoreNeed.readyState == 4) {
			    document.getElementById('Store_Need_Area').innerHTML = data2990;
			}
		}; //specify name of function that will handle server response........
		myObjectGetStoreNeed.open('GET','General_Get_Store_Need.php?Store_Need='+Store_Need,true);
		myObjectGetStoreNeed.send();
	}
</script>

<script>
	function updateStoreIssueMenu() {
	   /*	var Requisition_ID = '<?php echo $_SESSION['General_Requisition_ID']; ?>';
		alert(Requisition_ID);

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
		
		myObject123.open('GET','Get_Store_Issued.php?Requisition_ID='+Requisition_ID,true);
		myObject123.send();*/
	}
</script>
<?php
	if(isset($_SESSION['General_Requisition_ID'])){
		$Requisition_ID = $_SESSION['General_Requisition_ID'];
	}else{
		$Requisition_ID = 0;
	}
?>
<script>
	function updateRequisitionNumber(){
		if(window.XMLHttpRequest){
			myObjectUpdateRequisition = new XMLHttpRequest();
		}else if(window.ActiveXObject){
			myObjectUpdateRequisition = new ActiveXObject('Micrsoft.XMLHTTP');
			myObjectUpdateRequisition.overrideMimeType('text/xml');
		}
		myObjectUpdateRequisition.onreadystatechange = function (){
			data25 = myObjectUpdateRequisition.responseText;
			if (myObjectUpdateRequisition.readyState == 4) {
				document.getElementById('Requisition_Number').value = data25;
			}
		}; //specify name of function that will handle server response........
		
		myObjectUpdateRequisition.open('GET','General_Update_Requisition_Number.php',true);
		myObjectUpdateRequisition.send();
	}
</script>

<script>
	function updateRequisitionDesc(){
		var Requisition_Description = document.getElementById("Requisition_Description").value;
		
		if(window.XMLHttpRequest){
			myObjectUpdateDescription = new XMLHttpRequest();
		}else if(window.ActiveXObject){
			myObjectUpdateDescription = new ActiveXObject('Micrsoft.XMLHTTP');
			myObjectUpdateDescription.overrideMimeType('text/xml');
		}
		myObjectUpdateDescription.onreadystatechange = function (){
			data27 = myObjectUpdateDescription.responseText;
			if (myObjectUpdateDescription.readyState == 4) {
				//document.getElementById('Requisition_Description').value = data27;
			}
		}; //specify name of function that will handle server response........
		
		myObjectUpdateDescription.open('GET','General_Change_Requisition_Description.php?Requisition_Description='+Requisition_Description,true);
		myObjectUpdateDescription.send();		
	}
</script>

<script>
	function Update_Item_Remark(Requisition_Item_ID,Item_Remark){
		if(window.XMLHttpRequest){
			myObjectUpdateItemRemark = new XMLHttpRequest();
		}else if(window.ActiveXObject){
			myObjectUpdateItemRemark = new ActiveXObject('Micrsoft.XMLHTTP');
			myObjectUpdateItemRemark.overrideMimeType('text/xml');
		}
		myObjectUpdateItemRemark.onreadystatechange = function (){
			data35 = myObjectUpdateItemRemark.responseText;
			if (myObjectUpdateItemRemark.readyState == 4) {
				//alert(Requisition_Item_ID);
			}
		}; //specify name of function that will handle server response........
		
		myObjectUpdateItemRemark.open('GET','General_Update_Item_Remark.php?Requisition_Item_ID='+Requisition_Item_ID+'&Item_Remark='+Item_Remark,true);
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
				document.getElementById('Requisition_Description').value = data26;
			}
		}; //specify name of function that will handle server response........
		
		myObjectUpdateDescription.open('GET','General_Update_Requisition_Description.php',true);
		myObjectUpdateDescription.send();
	}
</script>
<script>
	function updateRequisitionCreatedDate(){
		if(window.XMLHttpRequest){
			myObjectUpdateCreatedDate = new XMLHttpRequest();
		}else if(window.ActiveXObject){
			myObjectUpdateCreatedDate = new ActiveXObject('Micrsoft.XMLHTTP');
			myObjectUpdateCreatedDate.overrideMimeType('text/xml');
		}
		myObjectUpdateCreatedDate.onreadystatechange = function (){
			data28 = myObjectUpdateCreatedDate.responseText;
			if (myObjectUpdateCreatedDate.readyState == 4) {
				document.getElementById('Requisition_Date').value = data28;
			}
		}; //specify name of function that will handle server response........
		
		myObjectUpdateCreatedDate.open('GET','General_Update_Requisition_Created_Date.php',true);
		myObjectUpdateCreatedDate.send();		
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
		
		myObjectUpdateSubmitArea.open('GET','General_Update_Submit_Area.php',true);
		myObjectUpdateSubmitArea.send();		
	}
</script>

<script>	
	function Get_Selected_Item(){
		var Item_ID = document.getElementById("Item_ID").value;
		var Quantity = document.getElementById("Quantity").value;
		var Item_Remark = document.getElementById("Item_Remark").value;
		var Store_Issue = document.getElementById("Store_Issue").value;
		var Store_Need = document.getElementById("Store_Need").value;
		var Cont_Quantity = document.getElementById("Cont_Quantity").value;
		var Items_Quantity = document.getElementById("Items_Quantity").value;
		var Requisition_Description = document.getElementById("Requisition_Description").value;
		if (Item_ID != '' && Item_ID != null && Quantity != '' && Quantity != null && Quantity!= '' && Store_Issue != '' && Store_Issue != null && (Store_Need != Store_Issue)) {
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
					updateStoreIssueMenu2();
					updateStoreNeedMenu2();
					updateRequisitionNumber();
					updateRequisitionDescription();
					updateRequisitionCreatedDate();
					updateSubmitArea();
				}
			}; //specify name of function that will handle server response........
			
			my_Object_Get_Selected_Item.open('GET','General_Requisition_Add_Selected_Item.php?Item_ID='+Item_ID+'&Quantity='+Quantity+'&Item_Remark='+Item_Remark+'&Store_Issue='+Store_Issue+'&Store_Need='+Store_Need+'&Requisition_Description='+Requisition_Description+'&Cont_Quantity='+Cont_Quantity+'&Items_Quantity='+Items_Quantity,true);
			my_Object_Get_Selected_Item.send();
		    
		}else if ((Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) && Quantity != '' && Quantity != null){
			alertMessage();
		}else if(Store_Need == Store_Issue){
			alert("Store Requesting and store issue must be different locations");
			document.getElementById("Store_Need").value = '';
			document.getElementById("Store_Issue").value = '';
		}else{
			if(Store_Issue=='' || Store_Issue == null){
				document.getElementById("Store_Issue").focus();
				document.getElementById("Store_Issue").style = 'border: 3px solid red';
			}else{
				document.getElementById("Store_Issue").style = 'border: 3px';
			}
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
	function Confirm_Remove_Item(Item_Name,Requisition_Item_ID){ 
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
					//update_total(Registration_ID);
					//update_Billing_Type();
					//Update_Claim_Form_Number();
				}
			}; //specify name of function that will handle server response........
				
			My_Object_Remove_Item.open('GET','General_Requisition_Remove_Item_From_List.php?Requisition_Item_ID='+Requisition_Item_ID,true);
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
<?php
$categories='';
 $sqlCat="SELECT Item_Category_Name, ca.Item_Category_ID FROM tbl_items i 
                                                                        JOIN tbl_item_subcategory isc ON isc.Item_Subcategory_ID=i.Item_Subcategory_ID
                                                                        JOIN tbl_item_category ca ON ca.Item_Category_ID=isc.Item_Category_ID
                                                                        WHERE i.Item_Type IN ('Pharmacy','Others') GROUP BY ca.Item_Category_ID
                                                                        ";
                                                            $data=  mysqli_query($conn,$sqlCat)or die(mysqli_error($conn));
                                                            
								while($row = mysqli_fetch_array($data)){
								    $categories .= '<option value="'.$row['Item_Category_ID'].'">'.$row['Item_Category_Name'].'</option>';
								}
?>
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
                                                             echo $categories;
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
								$result = mysqli_query($conn,"SELECT Product_Name, Item_ID FROM tbl_items where Item_Type IN ('Pharmacy','Others') order by Product_Name limit 200");
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
									<td id='Container_Label' style="text-align: center;">Units</td>
									<td id='Items_Label' style="text-align: center;">Items per Unit</td>
									<td  valign="bottom" id='Quantity_Label' style="text-align: center;">Quantity</td>
									<td id='Balance_Label'>Balance</td> 
									<td id='Remark_Label'>Remark</td> 
								</tr>
								<tr>
									<td>
										<input type='text' name='Item_Name' id='Item_Name' size=20 placeholder='Item Name' readonly='readonly' required='required'>
										<input type='hidden' name='Item_ID' id='Item_ID' value=''>
									</td>
									<td width="7%">
										<input type='text' style="text-align: center;" name='Cont_Quantity' id='Cont_Quantity' autocomplete='off' size=10 placeholder='Cont Qty' onchange='numberOnly(this); Calculate_Quantity();' onkeyup='numberOnly(this); Calculate_Quantity();' onkeypress='numberOnly(this); Calculate_Quantity();' oninput='numberOnly(this); Calculate_Quantity();' onkeypress='numberOnly(this); Calculate_Quantity();'>
									</td>
									<td width="7%">
										<input type='text' style="text-align: center;" name='Items_Quantity' id='Items_Quantity' autocomplete='off' size=10 placeholder='Items' onchange='numberOnly(this); Calculate_Quantity();' onkeyup='numberOnly(this); Calculate_Quantity();' onkeypress='numberOnly(this); Calculate_Quantity();' oninput='numberOnly(this); Calculate_Quantity();' onkeypress='numberOnly(this); Calculate_Quantity();'>
									</td>
									<td width="7%">
										<input type='text' style="text-align: center;" name='Quantity' id='Quantity' autocomplete='off' size=10 placeholder='Qty' onchange='numberOnly(this); Clear_Quantity();' onkeyup='numberOnly(this); Clear_Quantity();' onkeypress='numberOnly(this); Clear_Quantity();' oninput='numberOnly(this); Clear_Quantity();' onkeypress='numberOnly(this); Clear_Quantity();' required='required'>
									</td>
									<td width="10%">
										<input type='text' name='Balance' id='Balance' size=10 placeholder='Balance' readonly='readonly'>
									</td> 
									<td width="20%">
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
							<!--<iframe width='100%' src='requisition_items_Iframe.php?Requisition_ID=<?php echo $Requisition_ID; ?>' width='100%' height=250px></iframe>-->
							<fieldset style='overflow-y: scroll; height: 299px; background-color:silver' id='Items_Fieldset_List'>
								<?php
									echo '<center><table width = 100% border=0>';
									echo '<tr><td width=4% style="text-align: center; background-color:silver;color:black">Sn</td>
										    <td style="background-color:silver;color:black">Item Name</td>
											<td width=7% style="text-align: center; background-color:silver;color:black">Units</td>
											<td width=7% style="text-align: center; background-color:silver;color:black">Items</td>
											<td width=7% style="text-align: center; background-color:silver;color:black">Quantity</td>
											<td width=25% style="text-align: center; background-color:silver;color:black">Remark</td>
											<td style="text-align: center; background-color:silver;color:black">Remove</td></tr>';
									
									
									$select_Transaction_Items = mysqli_query($conn,"select rqi.Requisition_Item_ID, itm.Product_Name, rqi.Quantity_Required, rqi.Item_Remark, rqi.Requisition_Item_ID, rqi.Container_Qty, rqi.Items_Qty
														from tbl_requisition_items rqi, tbl_items itm where
														    itm.Item_ID = rqi.Item_ID and
															rqi.Requisition_ID ='$Requisition_ID'") or die(mysqli_error($conn)); 
								    
									$Temp=1;
									while($row = mysqli_fetch_array($select_Transaction_Items)){ 
										echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
										echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
										echo "<td><input type='text' value='".$row['Container_Qty']."' style='text-align: center;'  onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
										echo "<td><input type='text' value='".$row['Items_Qty']."' style='text-align: center;'  onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
										echo "<td><input type='text' value='".$row['Quantity_Required']."' style='text-align: center;'  onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
										echo "<td><input type='text' id='Item_Remark_Seved' name='Item_Remark_Seved' value='".$row['Item_Remark']."' onclick='Update_Item_Remark(".$row['Requisition_Item_ID'].",this.value)' onkeyup='Update_Item_Remark(".$row['Requisition_Item_ID'].",this.value)'></td>";
									?>
										<td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Requisition_Item_ID']; ?>)'></td>
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
						function Submit_Requisition_Function(Requisition_ID){				
						    document.location = 'General_Submit_Requisition.php?Requisition_ID='+Requisition_ID;
						}
					</script>
					<script>
						function Confirm_Submit_Requisition(){
							var Requisition_ID = '<?php echo $Requisition_ID; ?>';
							//var r = confirm("Are you sure you want to submit this requisition?\n\nClick OK to proceed");
							//if(r == true){
							//check if requisition contain at least one item then submit
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
										var r = confirm("Are you sure you want to submit this requisition?\n\nClick OK to proceed");
										if(r == true){
											Submit_Requisition_Function(Requisition_ID);
										}
									}else{
										alert("This Requisition May Either Already Submitted or\n Requisition Contains No Items\n");
									}
								}
							}; //specify name of function that will handle server response........
							
							myObjectCheckItemNumber.open('GET','General_Requisition_Check_Number_Of_Items.php',true);
							myObjectCheckItemNumber.send();
							//}
						}
					</script>
					<tr>
						<td id='Submit_Button_Area' style='text-align: right;'>
							<?php
								if(isset($_SESSION['General_Requisition_ID'])){
									?>
										<input type='button' class='art-button-green' value='SUBMIT REQUISITION' onclick='Confirm_Submit_Requisition()'>
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