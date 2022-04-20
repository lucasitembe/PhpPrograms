 <script src='js/functions.js'></script>
<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    if(isset($_SESSION['userinfo'])){
        @session_start();
        if(!isset($_SESSION['Departmental_Open_Balance_Control'])){
            if(isset($_GET['SessionCategory'])){
                $SessionCategory = $_GET['SessionCategory'];
                $_SESSION['SessionCategory'] = $_GET['SessionCategory'];
            }else{
                header("Location: ./index.php?InvalidDepartmentalPrivilege=yes");
            }
            
            
            if(isset($_GET['SessionCategory'])){
                //check if he/she has only one department then proceed else follow other steps required
                $select_num_dept = mysqli_query($conn,"select sdep.Sub_Department_ID, Sub_Department_Name from
                                                    tbl_department dep, tbl_sub_department sdep,tbl_employee_sub_department ed
                                                        where dep.department_id = sdep.department_id and
                                                            ed.Employee_ID = '$Employee_ID' and
                                                                ed.Sub_Department_ID = sdep.Sub_Department_ID and
                                                                    Department_Location = '$SessionCategory'") or die(mysqli_error($conn));
                $num = mysqli_num_rows($select_num_dept);
                if($num == 0){
                    header("Location: ./index.php?InvalidDepartmentalPrivilege=yes");
                }elseif($num == 1){
                    while($data = mysqli_fetch_array($select_num_dept)){
                        $Sub_Department_ID = $data['Sub_Department_ID'];
                    }
                    $_SESSION['Departmental_Open_Balance_Control'] = $Sub_Department_ID;
                }else{
                    header("Location: ./openbalancedepartmentselect.php?SessionCategory=$SessionCategory&DepartmentOpenBalance=DepartmentOpenBalanceThisPage");
                }
            }else{
                header("Location: ./index.php?InvalidDepartmentalPrivilege=yes");
            }
        }else{
            //get required details including Sub_department_id & Sub_Department_Name
            $Sub_Department_ID = $_SESSION['Departmental_Open_Balance_Control'];
            $select = mysqli_query($conn,"Select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
            $no = mysqli_num_rows($select);
            if($no > 0){
                while($row = mysqli_fetch_array($select)){
                    $Sub_Department_Name = $row['Sub_Department_Name'];
                }
            }else{
                $Sub_Department_Name = '';
            }
        }
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    include("./includes/open_balance_menu.php");
?>

<fieldset>
    <legend align="right"><b><?php echo strtoupper($Sub_Department_Name); ?> GRN ~ OPEN BALANCE REAGENTS</b></legend>     
	<form action='#' method='post' name='myForm' id='myForm'>
	<fieldset>   
        <center> 
                <table width=100%>
			<tr>
				<td style='text-align: right; width: 10%;'>GRN Number</td>
				<td width=15%>
					<?php if(isset($_SESSION['Reagent_Grn_Open_Balance_ID'])){ ?>
						<input type='text' name='Grn_Number'  id='Grn_Number' value='<?php echo $_SESSION['Reagent_Grn_Open_Balance_ID']; ?> ' readonly='readonly'>
					<?php }else{ ?>
						<input type='text' name='Grn_Number'  id='Grn_Number' value='New' readonly='readonly'>
					<?php } ?>
				</td>
				<td style='text-align: right; width: 15%;'>Created Date & Time</td>
					<?php
                                            if(isset($_SESSION['Reagent_Grn_Open_Balance_ID'])){
                                                $Grn_Open_Balance_ID = $_SESSION['Reagent_Grn_Open_Balance_ID'];
                                                //get employee id
                                                if(isset($_SESSION['userinfo']['Employee_ID'])){
                                                        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                                                }else{
                                                        $Employee_ID = 0;
                                                }
                                                
                                                //get Created_Date_Time
                                                $select_Created_Date_Time = mysqli_query($conn,"select Created_Date_Time from tbl_reagent_grn_open_balance where
                                                                                                        Grn_Open_Balance_ID = '$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
                                                $num = mysqli_num_rows($select_Created_Date_Time);
                                                if($num > 0){
                                                        while($row = mysqli_fetch_array($select_Created_Date_Time)){
                                                                $Created_Date_Time = $row['Created_Date_Time'];
                                                        }
                                                        echo "<td><input type='text' name='Grn_Date' id='Grn_Date' placeholder='Created Date & Time' value='".$Created_Date_Time."' readonly='readonly'></td>";
                                                }else{
                                                        echo "<td><input type='text' name='Grn_Date' id='Grn_Date' placeholder='Created Date & Time' value='' readonly='readonly'></td>";
                                                }
                                            }else{
                                                    echo "<td><input type='text' name='Grn_Date' id='Grn_Date' placeholder='Created Date & Time' value='' readonly='readonly'></td>";
                                            }
					?>
				<!--<td width=15%><input type='text' name='Grn_Date'  id='Grn_Date' value='' readonly='readonly'></td>-->
				
				
				<td style='text-align: right; width: 15%;'>Location Name</td>
				<td style='width: 15%;'>
					<select name='Sub_Department_ID' id='Sub_Department_ID' required='Sub_Department_ID'>
					    <?php if($Sub_Department_Name != '' && $Sub_Department_Name != null){ ?>
						<option value='<?php echo $Sub_Department_ID; ?>'><?php echo $Sub_Department_Name; ?></option>
                                            <?php }else{ ?>
						<option value='Unknown sub department'>Unknown Sub Department</option>
					    <?php } ?>
					</select> 
				</td>
			</tr>
			<tr>
				<td style='text-align: right;'>Grn Description</td>
					<?php
						if(isset($_SESSION['Reagent_Grn_Open_Balance_ID'])){
							$Grn_Open_Balance_ID = $_SESSION['Reagent_Grn_Open_Balance_ID'];
							//get employee id
							if(isset($_SESSION['userinfo']['Employee_ID'])){
								$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
							}else{
								$Employee_ID = 0;
							}
							
							//get Grn_Open_Balance_Description
							$select_Grn_Open_Balance_Description = mysqli_query($conn,"select Grn_Open_Balance_Description from tbl_reagent_grn_open_balance where
														Grn_Open_Balance_ID = '$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
							$num = mysqli_num_rows($select_Grn_Open_Balance_Description);
							if($num > 0){
								while($row = mysqli_fetch_array($select_Grn_Open_Balance_Description)){
									$Grn_Open_Balance_Description = $row['Grn_Open_Balance_Description'];
								}
								echo "<td colspan=3><input type='text' name='Grn_Description' id='Grn_Description' placeholder='Grn Description' value='".$Grn_Open_Balance_Description."' onkeypress='update_Grn_Description()' onclick='update_Grn_Description()'></td>";
							}else{
								echo "<td colspan=3><input type='text' name='Grn_Description' id='Grn_Description' placeholder='Grn Description' value=''></td>";
							}
						}else{
							echo "<td colspan=3><input type='text' name='Grn_Description' id='Grn_Description' placeholder='Grn Description' value=''></td>";
						}
					?>
					
				<td style='text-align: right; width: 10%'>Prepared By</td>
					<?php
						//get Employee_Name
						if(isset($_SESSION['userinfo']['Employee_Name'])){
							$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
						}else{
							$Employee_Name = '';
						}
					?>
				
				<td>
					<input type='text' name='Employee_Name' id='Employee_Name' value='<?php echo $Employee_Name; ?>' readonly='readonly'>
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
            document.getElementById("Item_Remark").value = '';
            
            if(window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            }else if(window.ActiveXObject){ 
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            //alert(data);
        
            myObject.onreadystatechange = function (){
                        data4 = myObject.responseText;
                        if (myObject.readyState == 4) {
                            //document.getElementById('Approval').readonly = 'readonly';
                            document.getElementById('Items_Fieldset').innerHTML = data4;
                        }
                    }; //specify name of function that will handle server response........
            myObject.open('GET','Get_List_Of_Reagent_Items_List.php?Item_Category_ID='+Item_Category_ID,true);
            myObject.send();
	}
	    
	function getItemsListFiltered(Item_Name){
            document.getElementById("Item_Name").value = '';
            document.getElementById("Item_ID").value = ''; 
            document.getElementById("Item_Remark").value = '';
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
                        data3 = myObject.responseText;
                        if (myObject.readyState == 4) {
                            //document.getElementById('Approval').readonly = 'readonly';
                            document.getElementById('Items_Fieldset').innerHTML = data3;
                        }
                    }; //specify name of function that will handle server response........
            myObject.open('GET','Get_List_Of_Reagent_Filtered_Items.php?Item_Category_ID='+Item_Category_ID+'&Item_Name='+Item_Name,true);
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
				document.getElementById("Item_Name").style.color = 'white';
				
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
				
				document.getElementById("Buying_Price").style.backgroundColor = '#037CB0';
				document.getElementById("Buying_Price").value = '';
				document.getElementById("Buying_Price_Label").style.color = '#037CB0';
				document.getElementById("Buying_Price_Label").innerHTML = '<b>Buying Price</b>';
				document.getElementById("Buying_Price").setAttribute("ReadOnly","ReadOnly");
				
				document.getElementById("Item_Name_Label").style.color = '#037CB0';
				document.getElementById("Item_Name_Label").innerHTML = '<b>This Item Already Added. Change the quantity / remark from the list</b>';
				
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
				
				document.getElementById("Buying_Price").style.backgroundColor = 'white';
				document.getElementById("Buying_Price").value = '';
				document.getElementById("Buying_Price_Label").style.color = 'black';
				document.getElementById("Buying_Price_Label").innerHTML = 'Buying Price';
				document.getElementById("Buying_Price").removeAttribute("ReadOnly","ReadOnly");
				
				//change warning add button to add button
				document.getElementById("Add_Button_Area").innerHTML = "<input type='button' name='submit' id='submit' value='ADD' class='art-button-green' onclick='Get_Selected_Item()'>";
			    }
			}
		}; //specify name of function that will handle server response........
		myObjectGetItemName.open('GET','Reagent_Grn_Open_Balance_Check_Item_Selected.php?Item_ID='+Item_ID,true);
		myObjectGetItemName.send();
		
		
		//document.getElementById("Item_Name").value = Item_Name;
		//document.getElementById("Item_ID").value = Item_ID;
		//document.getElementById("Quantity").focus();
		
		document.getElementById("Item_Name").value = Item_Name;
		document.getElementById("Item_ID").value = Item_ID;
		document.getElementById("Quantity").value = ''; 
		document.getElementById("Item_Remark").value = ''; 
		document.getElementById("Quantity").focus();
		
		
		if (Item_ID != null && Item_ID != '') {
			Get_Balance();
		}
		
	}
</script>


<script>
    function updateGrnNumber(){
        var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
        if (Sub_Department_ID != '' && Sub_Department_ID != null) {
            if(window.XMLHttpRequest){
                    myObjectUpdateGrnNumber = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                    myObjectUpdateGrnNumber = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectUpdateGrnNumber.overrideMimeType('text/xml');
            }
            
            myObjectUpdateGrnNumber.onreadystatechange = function (){
                data2 = myObjectUpdateGrnNumber.responseText;
                if (myObjectUpdateGrnNumber.readyState == 4) {
                    document.getElementById('Grn_Number').value = data2;
                }
            }; //specify name of function that will handle server response........
            
            myObjectUpdateGrnNumber.open('GET','Reagent_Grn_Open_Balance_Control_Update.php?Sub_Department_ID='+Sub_Department_ID+'&GetGrnNumber=True',true);
            myObjectUpdateGrnNumber.send();
        }
    }
</script>


<script>
	function updateGrnCreatedDate(){
		var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
		if (Sub_Department_ID != '' && Sub_Department_ID != null) {
			if(window.XMLHttpRequest){
				myObjectGrnCreatedDate = new XMLHttpRequest();
			}else if(window.ActiveXObject){
				myObjectGrnCreatedDate = new ActiveXObject('Micrsoft.XMLHTTP');
				myObjectGrnCreatedDate.overrideMimeType('text/xml');
			}
			
			myObjectGrnCreatedDate.onreadystatechange = function (){
                            data1 = myObjectGrnCreatedDate.responseText;
                            if (myObjectGrnCreatedDate.readyState == 4) {
                                document.getElementById('Grn_Date').value = data1;
                            }
			}; //specify name of function that will handle server response........
			
			myObjectGrnCreatedDate.open('GET','Reagent_Grn_Open_Balance_Control_Update.php?Sub_Department_ID='+Sub_Department_ID+'&GetGrnCreatedDate=True',true);
			myObjectGrnCreatedDate.send();			
		}
	}
</script>


<script>
    function update_Report_Button_Area(){
        var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
        if (Sub_Department_ID != '' && Sub_Department_ID != null) {
                if(window.XMLHttpRequest){
                        myObjectUpdateReportButton = new XMLHttpRequest();
                }else if(window.ActiveXObject){
                        myObjectUpdateReportButton = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObjectUpdateReportButton.overrideMimeType('text/xml');
                }
                
                myObjectUpdateReportButton.onreadystatechange = function (){
                        data5 = myObjectUpdateReportButton.responseText;
                        if (myObjectUpdateReportButton.readyState == 4) {
                                document.getElementById('Report_Button_Area').innerHTML = data5;
                        }
                }; //specify name of function that will handle server response........
                
                myObjectUpdateReportButton.open('GET','Reagent_Grn_Open_Balance_Control_Update.php?Sub_Department_ID='+Sub_Department_ID+'&GetGrnPreviewButtons=True',true);
                myObjectUpdateReportButton.send();			
        }
    }
</script>



<script>
	function update_Grn_Description(){
		var Grn_Description = document.getElementById("Grn_Description").value;
		var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;

		if (Sub_Department_ID != '' && Sub_Department_ID != null) {
			if(window.XMLHttpRequest){
				myObjectUpdateDescription = new XMLHttpRequest();
			}else if(window.ActiveXObject){
				myObjectUpdateDescription = new ActiveXObject('Micrsoft.XMLHTTP');
				myObjectUpdateDescription.overrideMimeType('text/xml');
			}
			
			myObjectUpdateDescription.onreadystatechange = function (){
				data70 = myObjectUpdateDescription.responseText;
				if (myObjectUpdateDescription.readyState == 4) {
					//code.........
				}
			}; //specify name of function that will handle server response........
			
			myObjectUpdateDescription.open('GET','Grn_Open_Balance_Update_Description.php?Grn_Description='+Grn_Description,true);
			myObjectUpdateDescription.send();
		}
	}
</script>
 

<script>
	
	function Get_Selected_Item(){
		var Item_ID = document.getElementById("Item_ID").value;
		var Quantity = document.getElementById("Quantity").value;
		var Item_Remark = document.getElementById("Item_Remark").value;
		var Grn_Description = document.getElementById("Grn_Description").value;
		var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
		var Buying_Price = document.getElementById("Buying_Price").value;
		
		if (Item_ID != '' && Item_ID != null && Quantity != '' && Quantity != null && Sub_Department_ID != '' && Sub_Department_ID != null && Buying_Price != '' && Buying_Price != null) {
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
                                document.getElementById("Buying_Price").value = '';
                                alert("Item Added Successfully");
                                updateGrnNumber();
                                updateGrnCreatedDate();
                                update_Report_Button_Area();
                                //update_Grn_Description();
                            }
			}; //specify name of function that will handle server response........
			
			my_Object_Get_Selected_Item.open('GET','Reagent_Grn_Open_Balance_Add_Selected_Item.php?Item_ID='+Item_ID+'&Quantity='+Quantity+'&Item_Remark='+Item_Remark+'&Sub_Department_ID='+Sub_Department_ID+'&Grn_Description='+Grn_Description+'&Buying_Price='+Buying_Price,true);
			my_Object_Get_Selected_Item.send();
		    
		}else if ((Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) && ((Quantity != '' && Quantity != null) || (Buying_Price != '' && Buying_Price != null))){
			alertMessage();
		}else{
			if(Item_ID=='' || Item_ID == null){
				document.getElementById("Item_Name").style = 'border-color: red';
			}
			
			if(Quantity=='' || Quantity == null){
				document.getElementById("Quantity").focus();
				document.getElementById("Quantity").style = 'border-color: red';
			}
			
			if(Buying_Price=='' || Buying_Price == null){
				document.getElementById("Buying_Price").focus();
				document.getElementById("Buying_Price").style = 'border-color: red';
			}
		}
	}
</script>

<script>
	function alertMessage(){
		alert("Please Select Item First");
	}
</script>

<script>
	function Confirm_Remove_Item(Item_Name,Open_Balance_Item_ID){ 
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
				
			My_Object_Remove_Item.open('GET','Reagent_Open_Balance_Remove_Item_From_List.php?Open_Balance_Item_ID='+Open_Balance_Item_ID,true);
			My_Object_Remove_Item.send();
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
			
		myObjectGetBalance.open('GET','Get_Item_Actual_Balance.php?Item_ID='+Item_ID+'&ControlValue=Storage',true);
		myObjectGetBalance.send();
	}
</script>

<script>
	function Confirm_Grn_Open_Balance_Submission(Grn_Open_Balance_ID){ 
		var Confirm_Message = confirm("Are you sure you want to process this GRN?");
		if (Confirm_Message == true) {
			document.location = 'reagentgrnopenbalanceconfirm.php?ConfirmGrn=ConfirmGrnThisPage';
		}
	}
</script>

    <table width=100%>
                <tr>
			<td width=25%>
				<table width=100%>
                                    <tr>
                                        <td style='text-align: center;'>
                                                <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                                                    <option selected='selected'></option>
                                                    <?php
                                                        $data = mysqli_query($conn,"select Reagent_Name, Reagent_Category_ID
                                                                from tbl_reagents_category") or die(mysqli_error($conn));
                                                        while($row = mysqli_fetch_array($data)){
                                                            echo '<option value="'.$row['Reagent_Category_ID'].'">'.$row['Reagent_Name'].'</option>';
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
                                                            $result = mysqli_query($conn,"SELECT * FROM tbl_reagents_items order by Product_Name");
                                                            while($row = mysqli_fetch_array($result)){
                                                                echo "<tr>
                                                                    <td style='color:black; border:2px solid #ccc;text-align: left;'>"; ?>
                                                                        
                                                                        <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);">
                                                                   
                                                                   <?php
                                                                    echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'>".$row['Product_Name']."</td></tr>";
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
									<td id='Quantity_Label'>Quantity</td>
									<td id='Balance_Label'>Balance</td>
									<td id='Buying_Price_Label'>Buying Price</td>
									<td id='Remark_Label'>Remark</td> 
								</tr>
								<tr>
									<td>
										<input type='text' name='Item_Name' id='Item_Name' size=20 placeholder='Item Name' readonly='readonly' required='required'>
										<input type='hidden' name='Item_ID' id='Item_ID' value=''>
									</td>
									<td width=13%>
										<input type='text' name='Quantity' id='Quantity' autocomplete='off' size=10 placeholder='Quantity' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)' autocomplete='off' required='required'>
									</td>
									<td width=13%>
										<input type='text' name='Balance' id='Balance' autocomplete='off' size=10 placeholder='Quantity' readonly='readonly'>
									</td>
									<td width=13%>
										<input type='text' name='Buying_Price' id='Buying_Price' size=10 placeholder='Buying Price'>
									</td>
									<td width=13%>
										<input type='text' name='Item_Remark' id='Item_Remark' size=30 placeholder='Item Remark' autocomplete='off'>
									</td>
									<td style="text-align: center; width: 5%;" id='Add_Button_Area'>
										<input type='button' name='submit' id='submit' value='ADD' class='art-button-green' onclick='Get_Selected_Item()'>
										<input type='hidden' name='Add_Grn_Open_Balance' value='true'/>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<!--<iframe width='100%' src='requisition_items_Iframe.php?Grn_Open_Balance_ID=<?php //echo $Grn_Open_Balance_ID; ?>' width='100%' height=250px></iframe>-->
							<fieldset style='overflow-y: scroll; height: 260px;' id='Items_Fieldset_List'>
								<?php
									echo '<center><table width = 100% border=0>';
										echo '<tr><td width=4% style="text-align: center;">Sn</td>
											    <td width=30%>Item Name</td>
												<td width=10% style="text-align: center;">Quantity</td>
													<td width=10% style="text-align: right;">Buying Price</td>
														<td width=10% style="text-align: center;">Sub Total</td>
															<td width=25% style="text-align: center;">Remark</td>
																<td width=5%>Remove</td></tr>';
									
									if(isset($_SESSION['Reagent_Grn_Open_Balance_ID'])){
										$Grn_Open_Balance_ID = $_SESSION['Reagent_Grn_Open_Balance_ID'];
									}else{
										$Grn_Open_Balance_ID = 0;
									}
									$select_Open_Balance_Items = mysqli_query($conn,"select obi.Open_Balance_Item_ID, itm.Product_Name, obi.Item_Quantity, obi.Item_Remark, obi.Buying_Price
													from tbl_reagent_grn_open_balance_items obi, tbl_reagent_grn_open_balance gop, tbl_reagents_items itm where
														gop.Grn_Open_Balance_ID = obi.Grn_Open_Balance_ID and 
															itm.Item_ID = obi.Item_ID and
															    obi.Grn_Open_Balance_ID ='$Grn_Open_Balance_ID' and
																    Grn_Open_Balance_Status = 'pending'") or die(mysqli_error($conn)); 
								    
									$Temp=1; $num_rows = mysqli_num_rows($select_Open_Balance_Items);
									while($row = mysqli_fetch_array($select_Open_Balance_Items)){ 
									    echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
									    echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
									    echo "<td><input type='text' value='".$row['Item_Quantity']."' style='text-align: center;'></td>";
									    echo "<td style='text-align: right;'><input type='text' value='".$row['Buying_Price']."' style='text-align: right;'></td>";
									    echo "<td style='text-align: right;'><input type='text' value='".($row['Item_Quantity'] * $row['Buying_Price'])."' style='text-align: right;'></td>";
									    echo "<td><input type='text' value='".$row['Item_Remark']."'></td>";
									?>
										<td width=6%><input type='button' name='Remove_Item' id='Remove_Item' class='art-button-green' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Open_Balance_Item_ID']; ?>)'></td>
									<?php
									    echo "</tr>";
									    $Temp++;
									}
									echo '</table>';
								?>
							</fieldset>
						</td>
					</tr>
				</table>
				
			</td>
                </tr>
		<tr>
			<table width=100%>
				<tr>
					<td width=100% style='text-align: right;' id='Report_Button_Area'>
						<?php
							if(isset($_SESSION['Reagent_Grn_Open_Balance_ID'])){
								$Temp_Grn_Open_Balance_ID = $_SESSION['Reagent_Grn_Open_Balance_ID'];
									if($num_rows > 0 ){
						?>
							<input type='button' name='Confirm_Grn' id='Confirm_Grn' onclick='Confirm_Grn_Open_Balance_Submission(<?php echo $Temp_Grn_Open_Balance_ID; ?>)' value='PROCESS GRN' class='art-button-green'>
							<!--<a href='purchaseorderpreview.php?Grn_Open_Balance_ID=<?php //echo $Temp_Grn_Open_Balance_ID; ?>&PurchaseOrderPreview=PurchaseOrderPreviewThisPage' class='art-button-green' target='_Blank'>PREVIEW GRN</a>-->
						<?php		
									}
							}
						?>
					</td> 
				</tr>
			</table>
		</tr>
	    </table>
<?php
    include("./includes/footer.php");
?>