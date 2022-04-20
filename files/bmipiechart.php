<?php
	include("./includes/connection.php");
	include("./includes/header.php");
	
	//include the library
    include "libchart/libchart/classes/libchart.php";
	

	
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
	
	
	//new date function (Contain years, Months and days)

 $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$Age ='';
    }

//end of the function 
 
    //new pie chart instance
    $chart = new PieChart(500,300);
 
    //data set instance
    $dataSet = new XYDataSet();
    
    //query all records from the database
    $query = "SELECT * FROM tbl_nurse n,tbl_Patient_Registration pr
			WHERE pr.Registration_ID=n.Registration_ID
			AND n.Registration_ID=$Registration_ID";
 
    $result = mysqli_query($conn,$query);
	
	
    //get number of rows returned
    $num_results = mysqli_num_rows($result); //$result->num_rows;
 
    if( $result){
    
        while($row = mysqli_fetch_assoc($result)) {
		$Nurse_DateTime=$row['Nurse_DateTime'];
		$Patient_Name=$row['Patient_Name'];
		$bmi=$row['bmi'];
		$Date_Of_Birth = $row['Date_Of_Birth'];
	  $Age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
		
		//		   extract($row);
            $dataSet->addPoint(new Point("$Nurse_DateTime",$bmi));
        }
    
        //finalize dataset
        $chart->setDataSet($dataSet);
 
        //set chart title
        $chart->setTitle("BMI ,$Patient_Name,Patient_No $Registration_ID ,$Age");
        
        //render as an image and store under "generated" folder
		$image_name = "generated/chart_".$_SESSION['userinfo']['Employee_ID'].".png";
        $chart->render($image_name);
    
        //pull the generated chart where it was stored
       
    
	echo"<center><table style='border-left:0px;border-right:0px;border-top:0px;border-bottom:0px;background-color:white;'><tr><td>";
        echo "<img alt='Pie chart'  src='$image_name' style='border: 1px solid gray;margin-left:22%;'/>";
		echo "</td></tr></table></center>";
    }
	else
	{
        echo "No records  found in the database.";
    }
?>
<br>
<br>
<center>
<fieldset style="width:80%;">
<a  href='bmilinechart.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>' style='text-decoration: none' > <button style='width:15%; height:40px'>Line CHART</button></a>
&nbsp;&nbsp;&nbsp;

<a style='text-decoration: none' href='bmihorizontalchart.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>'><button style='width:18%; height: 40px'>Horizontal Bar CHART</button></a>
&nbsp;&nbsp;&nbsp;

<a style='text-decoration: none' href='bmiverticalchart.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>'  ><button style='width:18%; height: 40px'>Vertical Bar CHART</button></a>

</fieldset>
<hr>


<a  href='graphbmi.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>'  style='text-decoration:none;' class='art-button-green' >BACK</a>
<a  href='Pre_Operative_VitalSigns.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>'   class='art-button-green'>BACK TO NURSE WORKS</a>
</center>
<?php
    include("./includes/footer.php");
?>