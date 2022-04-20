
<?php 

 include("./includes/connection.php");
  include("./includes/header_general.php");
   
	 if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this page yet.Try login again or contact administrator for support!<p>");

    }
    if(isset($_SESSION['userinfo'])){
       if(isset($_SESSION['userinfo']['Patient_Record_Works'])){
	    if($_SESSION['userinfo']['Patient_Record_Works'] != 'yes'){
		die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
	    }
	}else{
	    	die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this page yet.Try login again or contact administrator for support!<p>");

	}
      
    }else{
	@session_destroy();
	   	die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this page yet.Try login again or contact administrator for support!<p>");

    }
    
	$Registration_ID=$_GET['Registration_ID'];
	$bmi=$_GET['bmi'];
	
    echo "<a href='Patientfile_Record_Detail_General.php?Registration_ID=".$_GET['Registration_ID']."&Nurse_DateTime=".filter_input(INPUT_GET,'Nurse_DateTime')."&PatientFileRecordThisPage=ThisPage' class='art-button-green'>BACK</a>";

	
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
    echo "<fieldset class='vital' style='height:335px;overflow:scroll;'>";
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
<br>
<center>
<fieldset style="margin-bottom:4px;width:80%" >
<legend align=center><b>CHOOSE TYPE OF GRAPH BELOW</b></legend>
<a  href='bmichart_general.php?Registration_ID=<?php echo $Registration_ID;?>&bmi=<?php echo $bmi; ?>&Nurse_DateTime=<?php echo filter_input(INPUT_GET,'Nurse_DateTime'); ?>' style='text-decoration: none' > <button style='width:15%; height:40px'>Line CHART</button></a>
&nbsp;&nbsp;&nbsp;

<a style='text-decoration: none' href='bmichart1_general.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>&Nurse_DateTime=<?php echo filter_input(INPUT_GET,'Nurse_DateTime'); ?>' ><button style='width:15%; height: 40px'>Pie CHART</button></a>
&nbsp;&nbsp;&nbsp;

<a style='text-decoration: none' href='bmichart2_general.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>&Nurse_DateTime=<?php echo filter_input(INPUT_GET,'Nurse_DateTime'); ?>'><button style='width:25%; height: 40px'>Horizontal Bar CHART</button></a>
&nbsp;&nbsp;&nbsp;

<a style='text-decoration: none' href='bmichart3_general.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>&Nurse_DateTime=<?php echo filter_input(INPUT_GET,'Nurse_DateTime'); ?>'  ><button style='width:25%; height: 40px'>Vertical Bar CHART</button></a>
</fieldset>
    <a  href='nurseform_General.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>&Nurse_DateTime=<?php echo filter_input(INPUT_GET,'Nurse_DateTime'); ?>'  class='art-button-green'>BACK TO NURSE WORKS</a>
</center>
</center>

<?php
   //include("./includes/footer.php");
?>
