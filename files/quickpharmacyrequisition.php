<script src='js/functions.js'></script><!--<script src="jquery.js"></script>-->
<?php
        include("./includes/header.php");
        include("./includes/connection.php");
		include_once("./functions/items.php");

        
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
	if(isset($_SESSION['Pharmacy_Requisition_ID'])){
		$Requisition_ID = $_SESSION['Pharmacy_Requisition_ID'];
	}else{
		$Requisition_ID = 0;
	}
	
	
	if(!isset($_SESSION['userinfo'])){
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
	}
	
	if(!isset($_SESSION['userinfo'])) {
	    @session_destroy();
            header("Location: ../index.php?InvalidPrivilege=yes");
        }
	
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Pharmacy'] == 'yes'){ 
			echo "<a href='Pharmacy_Control_Requisition_Sessions.php?New_Requisition=True' class='art-button-green'>NEW REQUISITION</a>";
		}
	}
	
        if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Pharmacy'] == 'yes'){
			echo "<a href='pharmacypendingrequisitions.php?PendingRequisitions=PendingRequisitionsThisPage' class='art-button-green'>PENDING REQUISITIONS</a>";
		}
	}
	
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Pharmacy'] == 'yes'){
			echo "<a href='pharmacypreviousrequisitions.php?PreviousRequisitions=PreviousRequisitionsThisPage' class='art-button-green'>PREVIOUS REQUISITIONS</a>";
		}
	}
	
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Pharmacy'] == 'yes'){ 
			if(isset($_GET['Quick'])){
				
			 echo "<a href='pharmacyreorderlevelnotification.php?ReorderLevel=ReorderLevelthisPage' class='art-button-green'>BACK</a>";
			
			}else{
			  echo "<a href='requisitionworks.php?RequisitionWorks=RequisitionWorksThisPage#' class='art-button-green'>BACK</a>";
			}
			
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

<?php
	if(isset($_SESSION['Pharmacy_Requisition_ID'])){
		$Requisition_ID = $_SESSION['Pharmacy_Requisition_ID'];
	}else{
		$Requisition_ID = 0;
	}
	
?>



<?php
if(isset($_POST['Quick_requisition'])){
	$Requisition_Number=$_POST['Requisition_Number'];
	$Requisition_Description=$_POST['Requisition_Description'];
	$Requisition_Date=$_POST['Requisition_Date'];
	$Store_Need=$_POST['Store_Need'];
	$Store_Issue=$_POST['Store_Issue'];
	$Quantity=$_POST['Quantity'];
	$Item_Remark=$_POST['Item_Remark'];
	$Item_ID=$_POST['Item_ID'];
	$Array_Size = $_POST['Array_Size'];
	$query=mysqli_query($conn,"SELECT Sub_Department_ID FROM tbl_sub_department WHERE Sub_Department_Name='$Store_Need' LIMIT 1");
	$res=mysqli_fetch_assoc($query);
	$Store_Need_ID=$res['Sub_Department_ID'];
	
	$insert_value ="insert into tbl_requisition(Created_Date_Time,Created_Date,Sent_Date_Time,Employee_ID,Branch_ID,Requisition_Description,Store_Need,Store_Issue,Requisition_Status)
						values((select now()),(select now()),(select now()),'$Employee_ID','$Branch_ID','$Requisition_Description','$Store_Need_ID','$Store_Issue','Not Approved')";

				
        $resultISID = true;
		
		$_SESSION['HAS_ERROR'] = false;
        Start_Transaction();
        $get_Requisition_ID = mysqli_query($conn,"select Requisition_ID from tbl_requisition where Employee_ID = '$Employee_ID' order by Requisition_ID desc limit 1") or die(mysqli_error($conn));

		if (mysqli_num_rows($get_Requisition_ID) > 0) {
            $data = mysqli_fetch_array($get_Requisition_ID);
            $Issue_ID = $data['Requisition_ID'];
        } else {
            $resultISID = mysqli_query($conn,$insert_value) or die(mysqli_error($conn));

            if (!$resultISID) {
                $_SESSION['HAS_ERROR'] = true;
            }

            $get_Requisition_ID = mysqli_query($conn,"SELECT Requisition_ID FROM tbl_requisition WHERE Employee_ID = '$Employee_ID' and
						Requisition_ID = '$Requisition_ID'
					        ORDER BY Requisition_ID DESC limit 1") or die(mysqli_error($conn));

            $data = mysqli_fetch_array($get_Requisition_ID);
            $Issue_ID = $data['Requisition_ID'];
        }

		 for ($i = 0; $i <= $Array_Size; $i++) {
		
            if ($Issue_ID != 0) {
                //update tbl_requisition_items table
                $update_items = "INSERT INTO tbl_requisition_items (Requisition_ID,Quantity_Required,Container_Qty,Items_Qty,Items_Per_Container,Item_Remark,Item_ID) VALUES ('$Issue_ID','".str_replace(',', '', $Quantity[$i])."','1','".str_replace(',', '', $Quantity[$i])."','".str_replace(',', '', $Quantity[$i])."','$Item_Remark[$i]','$Item_ID[$i]')";

                $result2 = mysqli_query($conn,$update_items) or die(mysqli_error($conn));
                if (!$result2) {
                    $_SESSION['HAS_ERROR'] = true;
                }
            } else {
                $_SESSION['HAS_ERROR'] = true;
            }
        }
		

        if (!$_SESSION['HAS_ERROR']) {
            Commit_Transaction();
            echo "<script>
                        alert('Requisition created Successfully');
                        document.location = 'pharmacyreorderlevelnotification.php?ReorderLevel=ReorderLevelthisPage';
                     </script>";
        } else {
            Rollback_Transaction();
            echo "<script>
                            alert('Process Fail! Please Try Again');
                            document.location = 'quickpharmacyrequisition.php?Status=QuickRequisition&QuickRequisition=QuickRequisitionThisPage&Quick=true';
                </script>";
        }
}


?>

<form action='' method='post' name='myForm' id='myForm' >
<!--<br/>-->
<fieldset> <legend align='right'><b>Requisition ~ <?php if(isset($_SESSION['Pharmacy'])){ echo $_SESSION['Pharmacy']; } ?></b></legend>  
        <table width=100%>
		<tr>
			<td width='12%' style='text-align: right;'>Requisition Number</td>
			<td width=5%>
				<?php if(isset($_SESSION['Pharmacy_Requisition_ID'])){ ?>
					<input type='text' name='Requisition_Number' size=6 id='Requisition_Number' readonly='readonly' value='<?php echo $_SESSION['Pharmacy_Requisition_ID']; ?>'>
				<?php }else{ ?>
					<input type='text' name='Requisition_Number' size=6  id='Requisition_Number' value='new'>
				<?php } ?>
			</td>
			<td width='13%' style='text-align: right;'>
			    Requisition Description
			</td>
			<td>
				<?php if(isset($_SESSION['Pharmacy_Requisition_ID'])){
					//get requisition description
					$Requisition_ID = $_SESSION['Pharmacy_Requisition_ID'];
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
				<?php if(isset($_SESSION['Pharmacy_Requisition_ID'])){
					//get requisition date
					$Requisition_ID = $_SESSION['Pharmacy_Requisition_ID'];
					$get_details = mysqli_query($conn,"select Created_Date_Time from tbl_requisition where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
					$num = mysqli_num_rows($get_details);
					if($num > 0){
					    while($row = mysqli_fetch_array($get_details)){
						$Created_Date_Time = $row['Created_Date_Time'];
					    }
					} else {
						$datenow=mysqli_query($conn,"SELECT NOW() AS TODAY");
						$row=mysqli_fetch_assoc($datenow);
						
					    $Created_Date_Time = $row['TODAY'];
					}
				?>
					<input type='text' readonly='readonly' name='Requisition_Date' id='Requisition_Date' value='<?php echo $Created_Date_Time; ?>'>
				<?php } else {
	                    $datenow=mysqli_query($conn,"SELECT NOW() AS TODAY");
						$row=mysqli_fetch_assoc($datenow);
						
					    $Created_Date_Time = $row['TODAY'];

				?>
			
					<input type='text' readonly='readonly' name='Requisition_Date' id='Requisition_Date' value='<?php echo $Created_Date_Time; ?>'>
				<?php } ?>
			</td> 
		</tr>
		<tr>
			<td width='10%' style='text-align: right;'>Department Requesting</td>
			<td width='16%'>
			    <select name='Store_Need' id='Store_Need'>
			      <?php
				if(isset($_SESSION['Pharmacy'])){
					echo '<option selected="selected">'.$_SESSION['Pharmacy'].'</option>';
				}else{
					echo '<option selected="selected">Unknown Store Need</option></select>';
				      }
				    ?>
			    </select>
			</td>
			<td style='text-align: right;'>Department Issue</td>
			<td id='Store_Issue_Area'>			
			<?php
				if(isset($_SESSION['Pharmacy_Requisition_ID']) && $_SESSION['Pharmacy_Requisition_ID'] != '' && $_SESSION['Pharmacy_Requisition_ID'] != null){
					$Requisition_ID = $_SESSION['Pharmacy_Requisition_ID'];
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
						$select_sub_department = mysqli_query($conn,"select Sub_Department_Name from tbl_Sub_Department where
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
					$Current_Sub_Department = $_SESSION['Pharmacy'];
					$select_Supplier = mysqli_query($conn,"select * from tbl_sub_department where Sub_Department_Name <> '$Current_Sub_Department'");
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
  
        <center>
	    <table width=100%>  
		<?php 
		    echo '<tr><td width=4% style="text-align: center;">Sn</td>
				<td>Item Name</td>
				    <td width=7% style="text-align: center;">Quantity</td>
					<td width=7% style="text-align: center;">Balance</td>
					    <td width=25% style="text-align: center;">Remark</td>
						    ';
		    
		    if(isset($_SESSION['Pharmacy'])){
		    $Sub_Department_Name = $_SESSION['Pharmacy'];
			$sql_select = mysqli_query($conn,"select i.Item_ID, i.Product_Name, (ib.Item_Balance - ib.Item_Temporary_Balance) as Item_Balance, ib.Reorder_Level from tbl_items_balance ib, tbl_Items i where
						i.Item_ID = ib.Item_ID and
						ib.Item_Balance < ib.Reorder_Level and
							Sub_Department_ID = (select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name' limit 1)") or die(mysqli_error($conn));
			$num = mysqli_num_rows($sql_select);
			
		    $Temp=1;
		    while($row = mysqli_fetch_array($sql_select)){ 
				echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
				echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
				echo "<td><input type='text' name = 'Quantity[]' required='required' style='text-align: center;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
				echo "<td><input type='text' style='text-align: center;' readonly='readonly' value='".$row['Item_Balance']."'></td>";
				echo "<td><input type='text' style='text-align: right;' name='Item_Remark[]'></td>";
				echo "<td><input type='hidden' readonly='readonly' name=Item_ID[] value='".$row['Item_ID']."'></td>";
				echo "<input type='hidden' name='Array_Size' id='Array_Size' value='".($num-1)."'>";
		    ?>	
			<!--<td width=6% style='text-align: center;'><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Requisition_Item_ID']; ?>)'></td>---->
		    <?php
			echo "</tr>";
			$Temp++;
		    }
		?>
			</td>
			
		    </tr>
			
			
		<?php } ?>
	    </table>
		<table>
		<tr>
			<center><td><input type='submit' name='Quick_requisition' value='Confirm Requisition' class='art-button-green'></td></center>
			</tr>
		</table>
        </center>
</fieldset>
</form>

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
				
				document.getElementById("Quantity").style.backgroundColor = 'white';
				document.getElementById("Quantity").value = '';
				document.getElementById("Quantity").focus();
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
		myObjectGetItemName.open('GET','Pharmacy_Check_Item_Selected.php?Item_ID='+Item_ID,true);
		myObjectGetItemName.send();
		
		
		document.getElementById("Item_Name").value = Item_Name;
		document.getElementById("Item_ID").value = Item_ID;
		document.getElementById("Quantity").focus();
		
		if (Item_ID != null && Item_ID != '') {
			Get_Balance();
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
				document.getElementById('Balance').value = data80;
			}
		}; //specify name of function that will handle server response........
			
		myObjectGetBalance.open('GET','Get_Item_Expected_Balance.php?Item_ID='+Item_ID+'&ControlValue=Storage',true);
		myObjectGetBalance.send();
	}
</script>

<script>
	function updateStoreIssueMenu2() {
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
		myRequisitionObject.open('GET','Pharmacy_Get_Store_Issued.php',true);
		myRequisitionObject.send();
	}
</script>

<script>	
	function updateStoreIssueMenu() {
		var Requisition_ID = '<?php echo $_SESSION['Pharmacy_Requisition_ID']; ?>';
		//alert(Requisition_ID);
		/*
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
		
		myObjectUpdateRequisition.open('GET','Pharmacy_Update_Requisition_Number.php',true);
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
		
		myObjectUpdateDescription.open('GET','Pharmacy_Change_Requisition_Description.php?Requisition_Description='+Requisition_Description,true);
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
		
		myObjectUpdateItemRemark.open('GET','Pharmacy_Update_Item_Remark.php?Requisition_Item_ID='+Requisition_Item_ID+'&Item_Remark='+Item_Remark,true);
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
		
		myObjectUpdateDescription.open('GET','Pharmacy_Update_Requisition_Description.php',true);
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
		
		myObjectUpdateCreatedDate.open('GET','Pharmacy_Update_Requisition_Created_Date.php',true);
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
		
		myObjectUpdateSubmitArea.open('GET','Pharmacy_Update_Submit_Area.php',true);
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
		var Requisition_Description = document.getElementById("Requisition_Description").value;
		if (Item_ID != '' && Item_ID != null && Quantity != '' && Quantity != null && Quantity!= '' && Store_Issue != '' && Store_Issue != null) {
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
					document.getElementById("Item_Remark").value = '';
					alert("Item Added Successfully");
					updateStoreIssueMenu2(); //1
					updateRequisitionNumber();
					updateRequisitionDescription();
					updateRequisitionCreatedDate();
					updateSubmitArea();
				}
			}; //specify name of function that will handle server response........
			
			my_Object_Get_Selected_Item.open('GET','Pharmacy_Requisition_Add_Selected_Item.php?Item_ID='+Item_ID+'&Quantity='+Quantity+'&Item_Remark='+Item_Remark+'&Store_Issue='+Store_Issue+'&Store_Need='+Store_Need+'&Requisition_Description='+Requisition_Description,true);
			my_Object_Get_Selected_Item.send();
		    
		}else if ((Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) && Quantity != '' && Quantity != null){
			alertMessage();
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
				
			My_Object_Remove_Item.open('GET','Pharmacy_Requisition_Remove_Item_From_List.php?Requisition_Item_ID='+Requisition_Item_ID,true);
			My_Object_Remove_Item.send();
		}
	}
</script>



<?php
  include("./includes/footer.php");
?>