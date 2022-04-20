<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $temp = 0;
    $temp2 = 0;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
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
	//get section
	if(isset($_GET['section'])){
		$section = $_GET['section'];
	}else{
		$section = '';
	}
    if(isset($_SESSION['userinfo'])){
    	if(strtolower($section) == 'dhis'){
    		echo "<a href='mapdiseasegroup.php?section=DHIS&MapDiseaseGroup=MapDiseaseGroupThisPage' class='art-button-green'>BACK</a>";			
    	}else{
			echo "<a href='mapdiseasegroup.php?MapDiseaseGroup=MapDiseaseGroupThisPage' class='art-button-green'>BACK</a>";
    	}
    }
?>

<!--<script src="js/functions.js"></script>-->
<br/><br/>
<center>
	<table width="50%">
		<tr>
			<td width="60%">
				<input type="text" name="Search_Value" id="Search_Value" autocomplete='off' placeholder='Search Disease Group' style="text-align: center;" oninput='Search_Disease_Group()' onkeypress="Search_Disease_Group()" onkeyup="Search_Disease_Group()">
			</td>
			<td>
				<input type="text" name="Search_Disease_Value" id="Search_Disease_Value" autocomplete='off' placeholder='Search Disease' style="text-align: center;" oninput='Search_Disease()' onkeypress="Search_Disease()" onkeyup="Search_Disease()">
			</td>
		</tr>
	</table>
</center>
<fieldset style='overflow-y: scroll; height: 370px; background-color:white;' id='Disease_Group_Area'>
            <legend align="right" style="background-color:#006400;color:white;padding:5px;">LIST OF GROUPS AND ASSIGNED DISEASES</legend>
	<?php
		//get all disease group
		$select_disease_group = mysqli_query($conn,"select disease_group_name,disease_group_id from tbl_disease_group order by disease_group_name limit 200 ") or die(mysqli_error($conn));
		$num_rows = mysqli_num_rows($select_disease_group);
		if($num_rows > 0){
	?>
			
	<?php
			while ($data = mysqli_fetch_array($select_disease_group)) {
				$disease_group_id = $data['disease_group_id'];
				echo '<h5><b>'.++$temp.'. '.ucwords(strtolower($data['disease_group_name'])).'</b></h5>';
				//get all diseases assigned to selected group
				$select_diseases = mysqli_query($conn,"select disease_name, disease_code from 
												tbl_disease d, tbl_disease_group_mapping dgm, tbl_disease_group dg where
												d.disease_ID = dgm.disease_ID and
												dgm.disease_group_id = dg.disease_group_id and
												dgm.disease_group_id = '$disease_group_id' order by disease_name") or die(mysqli_error($conn));
				$num_diseases = mysqli_num_rows($select_diseases);
				if($num_diseases > 0){
				echo '<table width="100%">';
				echo "<tr id='thea'><td width='5%'>SN</td><td width='10%'>CODE</td><td width='35%'>DISEASE NAME</td></tr>";
				echo "<tr id='thea'><td colspan='4'><hr></td></tr>";
					while ($row = mysqli_fetch_array($select_diseases)) {
?>
						<tr>
							<td width="5%">
								<?php echo ++$temp2; ?>
							</td>
							<td>
								<?php echo $row['disease_code']; ?>
							</td>
							<td>
								<?php echo $row['disease_name']; ?>
							</td>
						</tr>	
<?php
					}
				echo "<tr id='thea'><td colspan='4'><hr></td></tr>";
					$temp2 = 0;
				echo '</table><br/>';
				}else{
					echo "<b><ul><li>No Disease Assigned</li></ul></b><br/>";
				}
			}
		}
	?>
			</fieldset>



<script type="text/javascript">
	function Search_Disease_Group(){
		document.getElementById("Search_Disease_Value").value = '';
		var Search_Value = document.getElementById("Search_Value").value;
		if(window.XMLHttpRequest) {
			myObjectSearchDiseaseGroup = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
			myObjectSearchDiseaseGroup = new ActiveXObject('Micrsoft.XMLHTTP');
			myObjectSearchDiseaseGroup.overrideMimeType('text/xml');
	    }
	    	
	    myObjectSearchDiseaseGroup.onreadystatechange= function(){
	    	var data204 = myObjectSearchDiseaseGroup.responseText;
	    	if(myObjectSearchDiseaseGroup.readyState == 4){
	    		document.getElementById('Disease_Group_Area').innerHTML = data204;
	    	}
	    }; //specify name of function that will handle server response....
	    myObjectSearchDiseaseGroup.open('GET','Search_Disease_Group.php?Search_Value='+Search_Value,true);
	    myObjectSearchDiseaseGroup.send();
	}
</script>



<script type="text/javascript">
	function Search_Disease(){
		document.getElementById("Search_Value").value = '';
		var Search_Disease_Value = document.getElementById("Search_Disease_Value").value;
		if(window.XMLHttpRequest) {
			myObjectSearchDiseaseOnly = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
			myObjectSearchDiseaseOnly = new ActiveXObject('Micrsoft.XMLHTTP');
			myObjectSearchDiseaseOnly.overrideMimeType('text/xml');
	    }
	    	
	    myObjectSearchDiseaseOnly.onreadystatechange= function(){
	    	var data204 = myObjectSearchDiseaseOnly.responseText;
	    	if(myObjectSearchDiseaseOnly.readyState == 4){
	    		document.getElementById('Disease_Group_Area').innerHTML = data204;
	    	}
	    }; //specify name of function that will handle server response....
	    myObjectSearchDiseaseOnly.open('GET','Search_Disease.php?Search_Disease_Value='+Search_Disease_Value,true);
	    myObjectSearchDiseaseOnly.send();
	}
</script>

<?php
    include("./includes/footer.php");
?>