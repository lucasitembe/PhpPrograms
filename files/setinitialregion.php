<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
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
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='setupandconfiguration.php?BackToSetupAndConfiguration=BackTosetupAndConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<br/><br/>
<center>
<fieldset>
<table width="60%">
	<tr>
		<td width="20%" style="text-align: right;"><b>SELECTED REGION&nbsp;&nbsp;&nbsp;:</b></td>
		<td style="text-align: left;" id="Selected_Area">
			<?php
				$select = mysqli_query($conn,"select * from tbl_regions where Region_Status = 'Selected'") or die(mysqli_error($conn));
				$num = mysqli_num_rows($select);
				if($num > 0){
					while ($data = mysqli_fetch_array($select)) {
						$Selected_Region = $data['Region_Name'];
					}
				}else{
					$Selected_Region = 'No Region Selected';
				}
			?>
			&nbsp;&nbsp;<?php echo ucwords(strtolower($Selected_Region)); ?>
		</td>
		<td width="40%">
			<input type="text" name="Region_Name" id="Region_Name" placeholder="~~~ ~~~ ~~~ Enter Region Name ~~~ ~~~ ~~~" autocomplete="off" style="text-align: center;" onkeypress="Search_Region()" oninput="Search_Region()">
		</td>
	</tr>
</table>
</fieldset>
<fieldset style='overflow-y: scroll; height: 400px; width: 45%; background-color: white;' id='Regions_Fieldset'>
	<legend align="center" ><b>DEFAULT REGION REGISTRATION SETTING</b></legend>
	<table width="100%">
		<tr>
			<td width="8%"><b>SN</b></td>
			<td><b>REGION NAME</b></td>
			<td width="15%" style="text-align: center;"><b>ACTION</b></td>
		</tr>
	<?php
		$temp = 0;
		$select = mysqli_query($conn,"select * from tbl_regions") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while ($data = mysqli_fetch_array($select)) {
	?>
		<tr>
			<td><?php echo ++$temp; ?></td>
			<td><?php echo ucwords(strtolower($data['Region_Name'])); ?></td>
			<td>
				<input type="button" name="Submit" id="Submit" value="SELECT" class="art-button-green" onclick="Get_Region(<?php echo $data['Region_ID']; ?>)">
			</td>
		</tr>
	<?php
			}
		}
	?>
	</table>
</fieldset>
</center>

<script type="text/javascript">
	function Get_Region(Region_ID){
		if(window.XMLHttpRequest){
		    myObjectUpdate = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObjectUpdate = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectUpdate.overrideMimeType('text/xml');
		}
		myObjectUpdate.onreadystatechange = function (){
		    data29 = myObjectUpdate.responseText;
		    if (myObjectUpdate.readyState == 4) {
			document.getElementById('Selected_Area').innerHTML = data29;
			
		    }
		}; //specify name of function that will handle server response........
		
		myObjectUpdate.open('GET','Get_Selected_Region.php?Region_ID='+Region_ID,true);
		myObjectUpdate.send();
	}
</script>

<script type="text/javascript">
	function Search_Region(){
		var Region_Name = document.getElementById("Region_Name").value;
		if(window.XMLHttpRequest){
		    myObjectSearch = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObjectSearch = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectSearch.overrideMimeType('text/xml');
		}
		myObjectSearch.onreadystatechange = function (){
		    data30 = myObjectSearch.responseText;
		    if (myObjectSearch.readyState == 4) {
			document.getElementById('Regions_Fieldset').innerHTML = data30;
			
		    }
		}; //specify name of function that will handle server response........
		
		myObjectSearch.open('GET','Initial_Setting_Search_Regions.php?Region_Name='+Region_Name,true);
		myObjectSearch.send();
	}
</script>
<?php
    include("./includes/footer.php");
?>