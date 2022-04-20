<?php
    include("./includes/connection.php");
    include("./includes/header.php");

    include_once("./functions/items.php");

    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
	if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
?>
    <a href='pharmacyreportspage.php?PhrmacyReports=PharmacyReportsThisPage' class='art-button-green'>
        BACK
    </a>
<?php } } ?>


<?php
    //get sub department id
    if(isset($_SESSION['Pharmacy_ID'])){
        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
        $Sub_Department_ID = '';
    }
    
    //get sub department name
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
		while($row = mysqli_fetch_array($select)){
		    $Sub_Department_Name = $row['Sub_Department_Name'];
		}
    }else{
		$Sub_Department_Name = '';
    }
?>
<br/><br/>
<style>
	table,tr,td{
		border-collapse:collapse !important;
		/*border:none !important;*/
	}
	tr:hover{
		background-color:#eeeeee;
		cursor:pointer;
	}
 </style> 
<center>

<fieldset>
	<table width="100%">
		<tr>
			<td width="10%" style="text-align: right;"><b>Classification</b></td>
			<td style='text-align: left;'>
			    <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)'>
                    <option selected='All'>All</option>
                    <?php
                        $Classification_List = Get_Item_Classification();
                        foreach($Classification_List as $Classification) {
                            echo "<option value='{$Classification['Name']}'> {$Classification['Description']} </option>";
                        }
                    ?>
			    </select>
			</td>
			<td>
			    <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup='getItemsListFiltered(this.value)' placeholder='~~~~ ~~~~ ~~~~ ~~~~ Enter Item Name ~~~~ ~~~~ ~~~~ ~~~~' style="text-align: center;">
			</td>
			<!-- <td width="10%" style="text-align: right;">
				<input type="button" name="Preview" id="Preview" value="PREVIEW" class="art-button-green">
			</td> -->
		</tr>
	</table>
</fieldset>
<fieldset style="background-color:white; height:430px; overflow-y: scroll;">
	<legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>STOCK SUMMARY ~ <?php if(isset($_SESSION['Storage_Info'])){ echo strtoupper($Sub_Department_Name); } ?></b></legend>
        <table width="100%" id='Items_Fieldset'>
			<?php
				$Grand_Stock = 0;
				$Title = '<tr><td colspan="5"><hr></td></tr>
					<tr>
					    <td width="5%"><b>SN</b></td>
					    <td ><b>ITEM NAME</b></td>
					    <td width="10%" style="text-align: right;"><b>AVERAGE PRICE</b>&nbsp;&nbsp;&nbsp;</td>
					    <td width="10%" style="text-align: right;"><b>BALANCE</b>&nbsp;&nbsp;&nbsp;</td>
					    <td width="10%" style="text-align: right;"><b>STOCK VALUE</b>&nbsp;&nbsp;&nbsp;</td>
					</tr>
					<tr><td colspan="5"><hr></td></tr>';
				echo $Title;
			    $temp = 1;
			    $result = mysqli_query($conn,"select Last_Buy_Price, Item_Balance, Product_Name from tbl_items i,tbl_items_balance ib
									    where i.Item_ID = ib.Item_ID and
										ib.Sub_Department_ID = '$Sub_Department_ID' and
										i.Item_Type = 'Pharmacy' order by Product_Name limit 500") or die(mysqli_error($conn));
			    while($row = mysqli_fetch_array($result)){
					echo "<tr><td >".$temp."<b>.</b></td>";
					echo "<td >".$row['Product_Name']."</td>";
					echo "<td style='text-align: right;'>".number_format($row['Last_Buy_Price'])."&nbsp;&nbsp;&nbsp;</td>";
					if($row['Item_Balance'] > 0){
						echo "<td style='text-align: right;'>".$row['Item_Balance']."&nbsp;&nbsp;&nbsp;</td>";
					}else{
						echo "<td style='text-align: right;'>0&nbsp;&nbsp;&nbsp;</td>";
					}
					
					$Stock_Value = ($row['Item_Balance'] * $row['Last_Buy_Price']);
					if($Stock_Value > 0){
						echo "<td style='text-align: right;'>".number_format($Stock_Value)."&nbsp;&nbsp;&nbsp;</td></tr>";
						$Grand_Stock += $Stock_Value;
					}else{
						echo "<td style='text-align: right;'>0&nbsp;&nbsp;&nbsp;</td></tr>";						
					}
					$temp++;
					if(($temp%51) == 0){
						echo $Title;
					}
			    }
			?>
				</td>
			</tr>
			<tr><td colspan="5"><hr></td></tr>
			<tr><td colspan="4" style="text-align: right;"><b>ESTIMATED GRAND TOTAL</b></td><td style="text-align: right;"><b><?php echo number_format($Grand_Stock); ?></b>&nbsp;&nbsp;&nbsp;</td></tr>
			<tr><td colspan="5"><hr></td></tr>
        </table>
</fieldset>
</center>

<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>

<script type='text/javascript'>
    $(document).ready(function () {
        $('select').select2();
    });
</script>
<style> .select2 { width: 100% !important; } </style>

<script>
    function getItemsListFiltered(Search_Value){
	
	//var Search_Value = document.getElementById("Search_Value").value;
	var Item_Category_ID = document.getElementById("Item_Category_ID").value;
	var Sub_Department_Name = '<?php echo $Sub_Department_Name; ?>';
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
	myObject.open('GET','Get_List_Of_Stock_Summary_Items.php?Classification='+Item_Category_ID+'&Search_Value='+Search_Value,true);
	myObject.send();
    }
</script>

<script>
    function getItemsList(Item_Category_ID){
	document.getElementById("Search_Value").value = '';
	
	if(window.XMLHttpRequest) {
	    myObject2 = new XMLHttpRequest();
	}else if(window.ActiveXObject){ 
	    myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
	    myObject2.overrideMimeType('text/xml');
	}
	
	myObject2.onreadystatechange = function (){
		    data2 = myObject2.responseText;
		    if (myObject2.readyState == 4) {
			//document.getElementById('Approval').readonly = 'readonly';
			document.getElementById('Items_Fieldset').innerHTML = data2;
		    }
		}; //specify name of function that will handle server response........
	myObject2.open('GET','Get_List_Of_Stock_Summary_Items.php?Classification='+Item_Category_ID+'&FilterCategory=True',true);
	myObject2.send();
    }
</script>

 <?php
    include("./includes/footer.php");
?>