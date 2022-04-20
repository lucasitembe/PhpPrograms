<?php
	
	 include("./includes/connection.php");
	include "libchart/libchart/classes/libchart.php";
	session_start();

	$chart = new VerticalBarChart(700, 500);
	    $dataSet = new XYSeriesDataSet();
		
		$program = new XYDataSet();
		$subject = new XYDataSet();
		
	//Fetch data from database
        $currentDate=date('Y-m-d');
	$qr = "SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                (
                SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci  
                WHERE pr.Registration_Date=ci.Check_In_Date AND 
                    pr.Registration_ID=ci.Registration_ID AND
                    pr.Registration_Date < '$currentDate' AND
                    pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
                ) as male
            FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC";
	
	//$qr1 = "SELECT name,value FROM subject";
	
	$result = mysqli_query($conn,$qr);
	
	while($row = mysqli_fetch_assoc($result)){
	$total_Male=0;
	$name = $row['Guarantor_Name'];
	$value = $row['Sponsor_ID'];
	$male=$row['male'];
	$total_Male=$total_Male + $male;
	
	$program->addPoint(new Point("$name", $total_Male));
	
	
	$qr1 = "SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                    (
                    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci    
                    WHERE pr.Registration_Date=ci.Check_In_Date AND 
                    pr.Registration_ID=ci.Registration_ID AND
                    pr.Registration_Date < '$currentDate' AND
                    pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
                    ) as female
                FROM tbl_sponsor sp  WHERE sp.Sponsor_ID='$value' ORDER BY sp.Sponsor_ID ASC ";
	
	
	$results = mysqli_query($conn,$qr1);
		$total_Female=0;
		$res=mysqli_num_rows($results);
		for($i=0;$i<$res;$i++){
		    $row=mysqli_fetch_array($results);
		    //return rows
		    $sponsorID=$row['Sponsor_ID'];
		    $sponsorName=$row['Guarantor_Name'];
		    $female=$row['female'];
		    
		    $total_Male=$total_Male + $male;
		    $total_Female=$total_Female + $female;
		    $total=$male+$female;
	
		    $subject->addPoint(new Point("$sponsorName", $total_Female));	
	}	
	
	}

	$dataSet->addSerie("MALE", $program);
	$dataSet->addSerie("FEMALE", $subject);
	$chart->setDataSet($dataSet);

	$chart->setTitle("PREVIOUS FIRST TIME VISITED MALE AND FEMALE GRAPH");
	
	 //render as an image and store under "generated" folder
		$image_name = "generated/chart_".$_SESSION['userinfo']['Employee_ID'].".png";
		$chart->render($image_name);
    
        //pull the generated chart where it was stored
        echo "<img alt='Pie chart'  src='$image_name' style='border: 1px solid gray;margin-left:22%;'/>";
	?>
	<br>
	
	<?php require('visitPreviousGraphListInclude.php');?>