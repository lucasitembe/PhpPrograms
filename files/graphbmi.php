
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
	$Registration_ID=$_GET['Registration_ID'];
	$bmi=$_GET['bmi'];
	
	// script of puttin color to to table row
echo "<script type='text/javascript'>
$(document).ready(function(){
  $('tr:odd').css('background-color','#DEB887');
});
</script>";

	//end of script
 $query = "SELECT * FROM tbl_nurse n 
			WHERE n.Registration_ID=$Registration_ID";
			
	
	$result = mysqli_query($conn,$query);

    //get number of rows returned
    $num_results = mysqli_num_rows($result); //$result->num_rows;
 
    if( $result){
	echo "<center><fieldset style='margin-top:5px;width:80%;'>";
	echo "<h5><b>BMI History Report </b></h5>";
    echo "<fieldset class='vital' style='height:270px;overflow:scroll;'>";
	echo "<table   width='40%' 
	style='border-left:0px;border-right:0px;border-top:1px solid black;border-bottom:1px solid black;margin-bottom:20px;' >
<tr bgcolor='#D3D3D3'>
<th>VISITED DATE </th>
<th>VALUE</th>
</tr>";
        while($row = mysqli_fetch_assoc($result)) {
		$Nurse_DateTime=$row['Nurse_DateTime'];
		$Vital_Value=$row['bmi'];
		echo "<tr>";
		echo "<td>" .$row['Nurse_DateTime'] . "</td>";
		echo "<td>" .$row['bmi'] . "</td>";
		echo "</tr>";
}
echo "</table>";
echo "</fieldset>";
echo "</center>";
}

?>

<center>
<fieldset style="margin-bottom:4px;width:80%" >
<legend align=center><b>CHOOSE TYPE OF GRAPH BELOW</b></legend>
<a  href='bmilinechart.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>' style='text-decoration: none' > <button style='width:15%; height:40px'>Line CHART</button></a>
&nbsp;&nbsp;&nbsp;

<a style='text-decoration: none' href='bmipiechart.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>' ><button style='width:15%; height: 40px'>Pie CHART</button></a>
&nbsp;&nbsp;&nbsp;

<a style='text-decoration: none' href='bmihorizontalchart.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>'><button style='width:18%; height: 40px'>Horizontal Bar CHART</button></a>
&nbsp;&nbsp;&nbsp;

<a style='text-decoration: none' href='bmiverticalchart.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>'  ><button style='width:15%; height: 40px'>Vertical Bar CHART</button></a>
</fieldset>
<a  href='Pre_Operative_VitalSigns.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>'  class='art-button-green'>BACK TO NURSE WORKS</a>
</center>
</center>





<?php
    include("./includes/footer.php");
?>
