<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Payment_Item_Cache_List_ID'])){
		$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
	}else{
		$Payment_Item_Cache_List_ID = 0;
	}
?>
<fieldset style='overflow-y: scroll; height: 305px;' id='Items_Fieldset'>
<?php
	$select = mysqli_query($conn,"select Upper_Point, OG_Junction, Hiatus_Hernia, Other_Lesson, Cardia, Fundus, Body, Antrum, Pyloms
							from tbl_git_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			if($data['Upper_Point'] != null && $data['Upper_Point'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : UPPER POINT</b></td></tr>
					<tr><td><?php echo $data['Upper_Point']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php
			}
			if($data['OG_Junction'] != null && $data['OG_Junction'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : OG JUNCTION</b></td></tr>
					<tr><td><?php echo $data['OG_Junction']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php
			}
			if($data['Hiatus_Hernia'] != null && $data['Hiatus_Hernia'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : HIATUS HERNIA</b></td></tr>
					<tr><td><?php echo $data['Hiatus_Hernia']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php
			}
			if($data['Other_Lesson'] != null && $data['Other_Lesson'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : OTHER LESSON</b></td></tr>
					<tr><td><?php echo $data['Other_Lesson']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php
			}
			if($data['Cardia'] != null && $data['Cardia'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : CARDIA</b></td></tr>
					<tr><td><?php echo $data['Cardia']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php
			}
			if($data['Fundus'] != null && $data['Fundus'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : FUNDUS</b></td></tr>
					<tr><td><?php echo $data['Fundus']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php
			}
			if($data['Body'] != null && $data['Body'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : BODY</b></td></tr>
					<tr><td><?php echo $data['Body']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php
			}
			if($data['Antrum'] != null && $data['Antrum'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : ANTRUM</b></td></tr>
					<tr><td><?php echo $data['Antrum']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php
			}
			if($data['Pyloms'] != null && $data['Pyloms'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : PYLOMS</b></td></tr>
					<tr><td><?php echo $data['Pyloms']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php
			}
                        echo "---------+------+--------____".$data['upper_git_normal'];
                        if($data['upper_git_normal'] != null && $data['upper_git_normal'] != ''){
                          ?>
				<table width="100%">
					<tr><td><b>TITLE : NORMAL</b></td></tr>
					<tr><td><?php echo $data['upper_git_normal']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php  
                        }
		}
	}
?>
</fieldset>
<span style="color: #037CB0;">
	<center>
		NOTE: Review display only filled titles
	</center>
</span>