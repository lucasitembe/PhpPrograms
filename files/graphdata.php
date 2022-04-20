<?php 
include("./includes/connection.php");
include("./includes/header.php");
   
	if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Nurse_Station_Works'])){
	    if($_SESSION['userinfo']['Nurse_Station_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
	
	
	// script of puttin color to to table row
echo "<script type='text/javascript'>
$(document).ready(function(){
  $('tr:odd').css('background-color','#DEB887');
});
</script>";

	//end of script
	
	
	$Registration_ID=$_GET['Registration_ID'];
	$Vital_ID=$_GET['Vital_ID'];
    $Vital = $_GET['Vital'];
 
 $query = "SELECT * FROM tbl_nurse n,tbl_nurse_vital nv 
			WHERE nv.Nurse_ID = n.Nurse_ID AND 
			n.Registration_ID='$Registration_ID' AND 
			nv.Vital_ID='$Vital_ID' ";
	
	$result = mysqli_query($conn,$query);

    //get number of rows returned
    $num_results = mysqli_num_rows($result); //$result->num_rows;
 
    if( $result){
	echo "<center><br><fieldset style='margin-top:5px;width:80%;'>";
	echo "<h5><b>$Vital History Report </b></h5>";
    echo "<fieldset class='vital' style='height:270px;overflow-y:scroll;'>";
	echo "<table   width='40%' 
	style='border-left:0px;border-right:0px;border-top:1px solid black;border-bottom:1px solid black;margin-bottom:20px;' >
	
<tr bgcolor='#D3D3D3'>
<th>VISITED DATE </th>
<th>VALUE</th>
</tr>";
        while($row = mysqli_fetch_assoc($result)) {
		$Nurse_DateTime=$row['Nurse_DateTime'];
		$Vital_Value=$row['Vital_Value'];
		$Vital_ID=$row['Vital_ID'];

		echo "<tr>";
		echo "<td style='text-align:center;'>" .$row['Nurse_DateTime'] . "</td>";
		echo "<td style='text-align:center;'>" .$row['Vital_Value'] . "</td>";
		echo "</tr>";
}
echo "</table>";
echo "</fieldset>";
echo "</center>";
}

?>

<center>
<br/>
<fieldset style="margin-bottom:4px;width:80%" >
<legend align=center><b>CHOOSE TYPE OF GRAPH BELOW</b></legend>
<a  href='linechartpreoperative.php?Registration_ID=<?php echo $Registration_ID; ?>&Vital_ID=<?php echo $Vital_ID; ?>&Vital=<?php echo $Vital; ?>' style='text-decoration: none' > <button style='width:18%; height:40px'>Line CHART</button></a>
&nbsp;&nbsp;&nbsp;

<a style='text-decoration: none' href='piechartpre.php?Registration_ID=<?php echo $Registration_ID; ?>&Vital_ID=<?php echo $Vital_ID; ?>&Vital=<?php echo $Vital; ?>' ><button style='width:18%; height: 40px'>Pie CHART</button></a>
&nbsp;&nbsp;&nbsp;

<a style='text-decoration: none' href='horizontalchart.php?Registration_ID=<?php echo $Registration_ID; ?>&Vital_ID=<?php echo $Vital_ID; ?>&Vital=<?php echo $Vital; ?>'><button style='width:25%; height: 40px'>Horizontal Bar CHART</button></a>
&nbsp;&nbsp;&nbsp;

<a style='text-decoration: none' href='verticalchart.php?Registration_ID=<?php echo $Registration_ID; ?>&Vital_ID=<?php echo $Vital_ID; ?>&Vital=<?php echo $Vital; ?>'  ><button style='width:25%; height: 40px'>Vertical Bar CHART</button></a>
</fieldset>
<a  href='Pre_Operative_VitalSigns.php?Registration_ID=<?php echo $Registration_ID; ?>&Vital_ID=<?php echo $Vital_ID; ?>&Vital=<?php echo $Vital; ?>' class='art-button-green'>BACK TO NURSE WORKS</a>
</center>

<?php
    include("./includes/footer.php");
?>
