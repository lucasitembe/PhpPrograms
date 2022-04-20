<?php
        include("./includes/header.php");
        include("./includes/connection.php");
        
	//get employee name
	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Employee_Name = 'Unknown Purchase Officer';
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
		include("./includes/session.php");
	}else{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }
		/*if(isset($_SESSION['userinfo'])){
			if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
				echo "<a href='storageandsupply.php' class='art-button-green'>STORAGE AND SUPPLY</a>";
			}
		}  */          
                
        
        
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
			{ 
			echo "<a href='purchaseorder.php?status=new' class='art-button-green'>NEW ORDER</a>";
			}
	}
	if(!isset($_GET['status'])){
		if(isset($_SESSION['userinfo'])){
			if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
				//echo "<a href='purchase_process.php?action=send&ofcer=$requisit_officer&purchase_id=$purchase_id' class='art-button-green'>SEND ORDER</a>";
			}
		}
		if(isset($_SESSION['userinfo'])){
			if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') { 
				//echo "<a href='purchase_edit_list.php?action=edit&ofcer=$requisit_officer&purchase_id=$purchase_id' class='art-button-green'>EDIT ORDER</a>";
			}
		}
	}
        if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
			echo "<a href='purchase_list.php?purchase=list&lForm=saveData&page=purchase_list' class='art-button-green'>PENDING ORDERS</a>";
		}
	}
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
			echo "<a href='purchase_list.php?purchase=list&lForm=sentData&page=purchase_list' class='art-button-green'>PREVIOUS ORDERS</a>";
		}
	}
	if(isset($_GET['Purchase_Order_ID'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
			echo "<a href='submit_this_order.php?Purchase_Order_ID=".$_GET['Purchase_Order_ID']."' class='art-button-green'>SUBMIT THIS ORDER</a>";
		}
	}
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
			echo "<a href='#' class='art-button-green'>BACK</a>";
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
	if(isset($_GET['Purchase_Order_ID'])){
		$Purchase_Order_ID = $_GET['Purchase_Order_ID'];
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



<?php
	if(isset($_POST['Add_Purchase_Order_Form'])){
		if(isset($_GET['status']) && isset($_GET['NPO'])){
			$Order_Description = mysqli_real_escape_string($conn,$_POST['Order_Description']);
			$Supplier_ID = mysqli_real_escape_string($conn,$_POST['Supplier_ID']);
			$Sub_Department_Name = mysqli_real_escape_string($conn,$_POST['Sub_Department_Name']);
			$Item_ID = mysqli_real_escape_string($conn,$_POST['Item_ID']);
			$Quantity = mysqli_real_escape_string($conn,$_POST['Quantity']);
			$Price = mysqli_real_escape_string($conn,$_POST['Price']);
			$Remark = mysqli_real_escape_string($conn,$_POST['Remark']);
		}else{
			$Item_ID = mysqli_real_escape_string($conn,$_POST['Item_ID']);
			$Quantity = mysqli_real_escape_string($conn,$_POST['Quantity']);
			$Price = mysqli_real_escape_string($conn,$_POST['Price']);
			$Remark = mysqli_real_escape_string($conn,$_POST['Remark']);
		}
		
		//insert data to the tbl_purchase_order table
		
		if(!isset($_GET['Purchase_Order_ID'])){
			$Insert = "insert into tbl_purchase_order(
						Order_Description,Created_Date,
							Sub_Department_ID,
								Supplier_ID,Employee_ID,
									Order_Status,Branch_ID)
								
				 values('$Order_Description',(select now()),
						(select Sub_Department_ID from tbl_sub_Department where Sub_Department_Name = '$Sub_Department_Name'),
							'$Supplier_ID','$Employee_ID',
							'pending','$Branch_ID')";
			
			if(!mysqli_query($conn,$Insert)){
				//die(mysqli_error($conn));
				echo "<script type='text/javascript'>
					alert('Process Fail!. Try again');
					document.location = './departmentalpurchaseorder.php?Purchase_Order_ID=".$Purchase_Order_ID."&PurchaseOrder=PurchaseOrderThisPage';
					</script>"; 
			}else{
				//get purchase order id to use as a foreign key
				$Get_Purchase_ID = mysqli_query($conn,"select Purchase_Order_ID from tbl_purchase_order where
									Sub_Department_ID = (select Sub_Department_ID from tbl_sub_Department where Sub_Department_Name = '$Sub_Department_Name') and
										Supplier_ID = '$Supplier_ID' and
											Employee_ID = '$Employee_ID' and
												Branch_ID = '$Branch_ID' 
													order by Purchase_Order_ID desc limit 1") or die(mysqli_error($conn));
				$no = mysqli_num_rows($Get_Purchase_ID);
				if($no > 0){
					while($row = mysqli_fetch_array($Get_Purchase_ID)){
						$Purchase_Order_ID = $row['Purchase_Order_ID'];
					}
				}else{
					$Purchase_Order_ID = 0;
				}
				
				
				//insert  data to the tbl_purchase_order table
				$Insert = "insert into tbl_purchase_order_items(
						Purchase_Order_ID,Quantity_Required,Price,
							Remark,Item_ID)
						
					 values('$Purchase_Order_ID','$Quantity','$Price',
							'$Remark','$Item_ID')";
							
				$result = mysqli_query($conn,$Insert);
				
				if(!$result){
					//die(mysqli_error($conn));
					echo "<script type='text/javascript'>
					alert('Process Fail!. Try again');
					document.location = './departmentalpurchaseorder.php?Purchase_Order_ID=".$Purchase_Order_ID."&PurchaseOrder=PurchaseOrderThisPage';
					</script>"; 
				}else{
					echo "<script type='text/javascript'>
					alert('Item added successful');
					document.location = './departmentalpurchaseorder.php?Purchase_Order_ID=".$Purchase_Order_ID."&PurchaseOrder=PurchaseOrderThisPage';
					</script>"; 
				}
			}
		}else{
			$Purchase_Order_ID = $_GET['Purchase_Order_ID'];
			
			
			//insert  data to the tbl_purchase_order table
				$Insert = "insert into tbl_purchase_order_items(
						Purchase_Order_ID,Quantity_Required,Price,
							Remark,Item_ID)
						
					 values('$Purchase_Order_ID','$Quantity','$Price',
							'$Remark','$Item_ID')";
							
				$result = mysqli_query($conn,$Insert);
				
				if(!$result){
					//die(mysqli_error($conn));
					echo "<script type='text/javascript'>
					alert('Process Fail!. Try again');
					document.location = './departmentalpurchaseorder.php?Purchase_Order_ID=".$Purchase_Order_ID."&PurchaseOrder=PurchaseOrderThisPage';
					</script>"; 
				}else{
					echo "<script type='text/javascript'>
					alert('Item added successful');
					document.location = './departmentalpurchaseorder.php?Purchase_Order_ID=".$Purchase_Order_ID."&PurchaseOrder=PurchaseOrderThisPage';
					</script>"; 
				}
			
		}
		
	}
?>


<br/>
<br/>

<form action='#' method='post' name='myForm' id='myForm' >
<!--<br/>-->
<fieldset> <legend align='right'><b><?php if(isset($_SESSION['Sub_Department_Name'])){ echo $_SESSION['Sub_Department_Name']; }?></b></legend>  
        <table width=100%>
		<tr>
			<td width='10%'><b>Purchase Number</b></td>
			<td width='10%'>
				<?php if(isset($_GET['Purchase_Order_ID'])){ ?>
					<input type='text' name='Purchase_Number'  id='Purchase_Number' readonly='readonly' value='<?php echo $Purchase_Order_ID; ?>'>
				<?php }else{ ?>
					<input type='text' name='Purchase_Number'  id='Purchase_Number' value='new'>
				<?php } ?>
			</td>
			<td width='10%'>
			    <b>Order Description</b>
			</td>
			<td>
				<?php if(isset($_GET['Purchase_Order_ID'])){ ?>
					<input type='text' name='Order_Description' id='Order_Description' readonly='readonly' value='<?php echo $Order_Description; ?>'>
				<?php }else{ ?>
					<input type='text' name='Order_Description' id='Order_Description'>
				<?php } ?>
			</td> 
			<td width='10%'><b>Purchase Date</b></td>
			<td width='16%'>
				<?php if(isset($_GET['Purchase_Order_ID'])){ ?>
					<input type='text' readonly='readonly' name='Purchase_Date' id='Purchase_Date' value='<?php echo $Created_Date; ?>'>
				<?php }else{ ?>
					<input type='text' readonly='readonly' name='Purchase_Date' id='Purchase_Date'>
				<?php } ?>
			</td> 
		</tr> 
		<tr>
			<td width='10%'><b>Store Need</b></td>
			<td width='16%'>
				<select name='Sub_Department_Name' id='Sub_Department_Name'>
					<?php
						if(isset($_SESSION['Sub_Department_Name'])){
							echo '<option selected="selected">'.$_SESSION['Sub_Department_Name'].'</option>';
						}else{
							echo '<option selected="selected">Unknown Store Need</option></select>';
						}
					?>
				</select>
			</td>
		<td><b>Supplier</b></td>
		<td>
			
			<?php if(isset($_GET['Purchase_Order_ID'])){
				echo "<select name='Supplier_ID' id='Supplier_ID' required='required'>";
				echo "<option selected='selected'>".$Supplier_Name."</option>";
			}else{ ?>
				<select name='Supplier_ID' id='Supplier_ID' required='required'>
				<option selected='selected'></option>
				<?php
					$select_Supplier = mysqli_query($conn,"select * from tbl_supplier");
					while($row = mysqli_fetch_array($select_Supplier)){
						echo "<option value='".$row['Supplier_ID']."'>".$row['Supplier_Name']."</option>";
					}
			}
				?>
			</select>
			
		</td>
		    <td><b>Purchase Officer</b></td> 
			<td>
				<input type='text' readonly='readonly' value='<?php echo $Employee_Name; ?>'>
			</td>
		</tr>
        </table> 
</center>
</fieldset>
<fieldset>   
        <center>
            <table width=100%>
                <tr>
			<td><b>Type</b></td>
			<td><b>Category</b></td>
			<td><b>Item Name</b></td>
			<td><b>Quantity</b></td>
			<td><b>Balance</b></td>
			<td><b>Price</b></td>
			<td><b>Remark</b></td> 
                </tr>
                <tr>
			<td> 
			<select name='Type' id='Type' onchange='getItemListType(this.value)' onchange='getPrice()' onkeypress='getPrice()'>
				<option selected='selected'>All Available</option><option>Service</option><option>Pharmacy</option> 
			</select>
			</td>
			<td>
            <select name='Item_Category' id='Item_Category' onchange='getItemList(this.value);getBalance()' >
              <option selected='selected'></option>
                <?php
                  $data = mysqli_query($conn,"select * from tbl_item_category");
                  while($row = mysqli_fetch_array($data)){
                    echo '<option>'.$row['Item_Category_Name'].'</option>';
                  }
                ?>   
            </select>
                    </td>
                    
		    <td> 
                      <select name='Item_ID' id='Item_Name' onchange='getBalance()' required='required'  style="width:100%" >
                      <option selected='selected'></option>
                        <?php
                          $data = mysqli_query($conn,"SELECT * FROM tbl_items");
                          while($row = mysqli_fetch_array($data)){
                            echo "<option  value='".$row['Item_ID']."'>".$row['Product_Name']."</option>";
                          }
                        ?> 
                      </select>
                    </td>
		    
		    
                    <td>
                        <input type='text' name='Quantity' id='Quantity' size=10 placeholder='Quantity'>
                    </td>
                    <td>
                        <input type='text' name='Balance' id='Balance' size=10 placeholder='Balance'>
                    </td>
                    <td>
                        <input type='text' name='Price' size=10 id='Price' placeholder='Price'>
                    </td>
                    <td>
                        <input type='text' name='Remark' id='Remark' size=30 placeholder='Remark'>
                    </td>
                    <td style='text-align: center;'>
                        <input type='submit' name='submit' id='submit' value='Add' class='art-button-green'>
                        <input type='hidden' name='Add_Purchase_Order_Form' value='true'/> 
                    </td>
                </tr>
            </table>   
        </center>
</fieldset>
<fieldset>   
        <center>
            <table width=100%>
                 <tr>
                     <td>
                         <iframe width='100%' src='purchace_items_Iframe.php?Purchase_Order_ID=<?php echo $Purchase_Order_ID; ?>' width='100%' height=250px></iframe>
                        </td>
                </tr>
		</tr> 
	    </table>
        </center>
</fieldset>


<?php
	include("./includes/footer.php");
?>

