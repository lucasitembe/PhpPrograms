<?php
	include("./includes/connection.php");
	include("./includes/header_general.php");
	
	//include the library
    include "libchart/libchart/classes/libchart.php";
	

	
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
	
	$section='';	
		if(isset($_GET['Section']) && $_GET['Section'] == 'Doctor'){

			$section="Section=Doctor&Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
		}elseif(isset($_GET['Section']) && $_GET['Section'] == 'DoctorLab'){
			$section="Section=DoctorLab&Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
		}elseif(isset($_GET['Section']) && $_GET['Section'] == 'DoctorRad'){
				$section="Section=DoctorRad&Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
		}elseif(isset($_GET['Section']) && $_GET['Section'] == 'DoctorsPerformancePateintSummary'){
			
			$section="Section=DoctorsPerformancePateintSummary&Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
		}
		 $Patient_Name='';
	
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
		if($Patient_Name ==''){
          $chart->setTitle("BMI ,Patient_No $Registration_ID ,$Age");
        }else{
		  $chart->setTitle("BMI ,$Patient_Name,Patient_No $Registration_ID ,$Age");
		}
        //render as an image and store under "generated" folder
		$image_name = "generated/chart_".$_SESSION['userinfo']['Employee_ID'].".png";
        $chart->render($image_name);
    
        //pull the generated chart where it was stored
       
	echo "<br>";
	echo "<br>";
	echo "<br>";
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
    <a  href='bmichart_general.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>&Nurse_DateTime=<?php echo filter_input(INPUT_GET,'Nurse_DateTime'); ?>' style='text-decoration: none' > <button style='width:15%; height:40px'>Line CHART</button></a>
&nbsp;&nbsp;&nbsp;

<a style='text-decoration: none' href='bmichart1_general.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>&Nurse_DateTime=<?php echo filter_input(INPUT_GET,'Nurse_DateTime'); ?>' ><button style='width:15%; height: 40px'>Pie CHART</button></a>
&nbsp;&nbsp;&nbsp;

<a style='text-decoration: none' href='bmichart2_general.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>&Nurse_DateTime=<?php echo filter_input(INPUT_GET,'Nurse_DateTime'); ?>'><button style='width:25%; height: 40px'>Horizontal Bar CHART</button></a>
&nbsp;&nbsp;&nbsp;

<a style='text-decoration: none' href='bmichart3_general.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>&Nurse_DateTime=<?php echo filter_input(INPUT_GET,'Nurse_DateTime'); ?>'  ><button style='width:25%; height: 40px'>Vertical Bar CHART</button></a>

</fieldset>
<hr>
<br>

<a  href='chartbmi_general.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>&Nurse_DateTime=<?php echo filter_input(INPUT_GET,'Nurse_DateTime'); ?>'  style='text-decoration:none;' class='art-button-green' >BACK</a>
<a  href='nurseform_General.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>&Nurse_DateTime=<?php echo filter_input(INPUT_GET,'Nurse_DateTime'); ?>'   class='art-button-green'>BACK TO NURSE WORKS</a>
</center>
<?php
    //include("./includes/footer.php");
?>