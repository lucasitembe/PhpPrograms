<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes' && $_SESSION['userinfo']['Pharmacy'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<a href="itemsmanagement.php?ItemManagement=ItemManagementThisPage" class="art-button-green">MANAGE CATEGORIES</a>
<a href="removesubcategorylist.php?RemoveSubItemCategory=RemoveSubItemCategoryThisForm" class="art-button-green">REMOVE SUB CATEGORIES</a>
<?php
	if(isset($_GET['Section']) && $_GET['Section'] == 'Pharmacy'){
		echo "<a href='itemsconfiguration.php?Section=Pharmacy&ItemsConfiguration=ItemsConfigurationThisPage' class='art-button-green'>BACK</a>";
	}else if(isset($_GET['Section']) && $_GET['Section'] == 'Storage'){
		echo "<a href='itemsconfiguration.php?Section=Storage&ItemsConfiguration=ItemConfigurationThisPage' class='art-button-green'>BACK</a>";
	}else if(isset($_GET['Section']) && $_GET['Section'] == 'Laboratory'){
		echo "<a href='itemsconfiguration.php?Section=Laboratory&ItemsConfiguration=ItemConfigurationThisPage' class='art-button-green'>BACK</a>";
	}else if(isset($_GET['Section']) && $_GET['Section'] == 'Radiology'){ 
    	echo "<a href='itemsconfiguration.php?Section=Radiology&ItemsConfiguration=ItemConfigurationThisPage' class='art-button-green'>BACK</a>";
	}else if(isset($_GET['Section']) && $_GET['Section'] == 'Doctor'){ 
    	echo "<a href='itemsconfiguration.php?Section=Doctor&ItemsConfiguration=ItemConfigurationThisPage' class='art-button-green'>BACK</a>";
	}else if(strtolower($_SESSION['userinfo']['Setup_And_Configuration']) == 'yes'){
		echo '<a href="itemsconfiguration.php?ItemsConfiguration=ItemsConfigurationThisPage" class="art-button-green">BACK</a>';
	}
?>
<br/><br/>

<fieldset>
	<center>
		<table width="50%">
			<tr>
				<td>
					<input type="text" name="Category_Name" id="Category_Name" placeholder="~~~~ ~~~~ ~~ Enter Category Name ~~ ~~~~ ~~~~" autocomplete="off" style="text-align: center;" oninput="Search_Category()" onkeypress="Search_Category()">
				</td>
			</tr>
		</table>
	</center>
</fieldset>
<fieldset style="overflow-y: scroll; height: 400px; background-color: white;" id="Categories_Area">
    <legend align="right"><b>REMOVE ITEMS CATEGORY</b></legend>
    <table width="100%">
		<tr><td colspan="3"><hr></td></tr>
		<tr><td width="9%"><b>SN</b>&nbsp;&nbsp;&nbsp;</td><td><b>CATEGORY NAME</b></td><td width="15%" style="text-align: center;"><b>ACTION</b></td></tr>
		<tr><td colspan="3"><hr></td></tr>
	<?php
		$select = mysqli_query($conn,"select Item_Category_ID, Item_Category_Name from tbl_item_category order by Item_Category_Name") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			$temp = 0;
			while ($data = mysqli_fetch_array($select)) {
	?>
				<tr id="sss">
					<td><?php echo ++$temp; ?></td>
					<td><?php echo strtoupper($data['Item_Category_Name']); ?></td>
					<td style="text-align: center;"><input type="button" value="REMOVE CATEGORY" class="art-button-green" onclick="Remove_Category_Verify(<?php echo $data['Item_Category_ID']; ?>)"></td>
				</tr>		
	<?php
			}
		}
	?>
	</table>
</fieldset>

<div id="Confirm_Message">
	Are you sure you want to remove selected category?<br/><br/>
	<table width="100%">
		<tr>
			<td style="text-align: right;" id="Button_Area">
				
			</td>
		</tr>
	</table>
</div>

<div id="Error_Report">
	Selected Category Contains Sub Categories.<br/>Please remove all sub categories belong to selected category first.
</div>

<div id="Success_Message">
	Category Removed Successfully<br/>
	<table width="100%">
		<tr>
			<td style="text-align: right;" id="Button_Area">
				<input type="button" class="art-button-green" value="CLOSE" onclick="Close_Conf_Message()">
			</td>
		</tr>
	</table>
</div>

<script type="text/javascript">
	function Close_Confirm_Message(){
		$("#Confirm_Message").dialog("close");
	}
</script>

<script type="text/javascript">
	function Close_Conf_Message(){
		$("#Success_Message").dialog("close");
	}
</script>

<script type="text/javascript">
	function Remove_Category_Verify(Item_Category_ID){
		if(window.XMLHttpRequest){
		    myObjectRem = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObjectRem = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectRem.overrideMimeType('text/xml');
		}
		myObjectRem.onreadystatechange = function (){
		    dataRem = myObjectRem.responseText;
		    if (myObjectRem.readyState == 4) {
		    	var feedback = dataRem;
		    	if(feedback == 'yes'){
		    		document.getElementById("Button_Area").innerHTML = '<input type="button" class="art-button-green" value="YES" onclick="Remove_Category('+Item_Category_ID+')">&nbsp;<input type="button" class="art-button-green" value="CANCEL" onclick="Close_Confirm_Message()">';
		    		$("#Confirm_Message").dialog("open");
		    	}else{
		    		$("#Error_Report").dialog("open");
		    	}
		    }
		}; //specify name of function that will handle server response........
		
		myObjectRem.open('GET','Remove_Category_Verify.php?Item_Category_ID='+Item_Category_ID,true);
		myObjectRem.send();
	}
</script>


<script type="text/javascript">
	function Remove_Category(Item_Category_ID){
		if(window.XMLHttpRequest){
		    myObjectRemove = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObjectRemove = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectRemove.overrideMimeType('text/xml');
		}
		myObjectRemove.onreadystatechange = function (){
		    dataRemove = myObjectRemove.responseText;
		    if (myObjectRemove.readyState == 4) {
		    	Refresh_Categories();
		    	$("#Confirm_Message").dialog("close");
		    	$("#Success_Message").dialog("open");
		    }
		}; //specify name of function that will handle server response........
		
		myObjectRemove.open('GET','Remove_Category.php?Item_Category_ID='+Item_Category_ID,true);
		myObjectRemove.send();
	}
</script>



<script type="text/javascript">
	function Refresh_Categories(){
		if(window.XMLHttpRequest){
		    myObjectRefresh = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObjectRefresh = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectRefresh.overrideMimeType('text/xml');
		}
		myObjectRefresh.onreadystatechange = function (){
		    dataRefresh = myObjectRefresh.responseText;
		    if (myObjectRefresh.readyState == 4) {
		    	document.getElementById("Categories_Area").innerHTML = dataRefresh;
		    }
		}; //specify name of function that will handle server response........
		
		myObjectRefresh.open('GET','Refresh_Categories.php',true);
		myObjectRefresh.send();
	}
</script>

<script type="text/javascript">
	function Search_Category(){
		var Category_Name = document.getElementById("Category_Name").value;
		if(window.XMLHttpRequest){
		    myObjectSearch = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObjectSearch = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectSearch.overrideMimeType('text/xml');
		}
		myObjectSearch.onreadystatechange = function (){
		    dataRefresh = myObjectSearch.responseText;
		    if (myObjectSearch.readyState == 4) {
		    	document.getElementById("Categories_Area").innerHTML = dataRefresh;
		    }
		}; //specify name of function that will handle server response........
		
		myObjectSearch.open('GET','Search_Category.php?Category_Name='+Category_Name,true);
		myObjectSearch.send();
	}
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<script>
   $(document).ready(function(){
      $("#Confirm_Message").dialog({ autoOpen: false, width:'40%',height:150, title:'CONFIRMATION MESSAGE',modal: true});
      $("#Error_Report").dialog({ autoOpen: false, width:'40%',height:140, title:'eHMS 2.0 ~ Message',modal: true});
      $("#Success_Message").dialog({ autoOpen: false, width:'40%',height:140, title:'eHMS 2.0 ~ Success Message',modal: true});
	});
</script>
<?php
    include("./includes/footer.php");
?>