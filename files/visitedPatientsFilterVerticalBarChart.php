<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Reception_Works'])){
	    if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
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
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ 
?>
    <a href='./visitedPatients.php?Reception=ReceptionThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<br><br>
<center>
<fieldset style="width: 93%">
     <legend align="right"><b>FIRST TIME VISITED MALE AND FEMALE VERTICAL BAR GRAPH</b></legend>
     <br>
<table width="93%" style="background-color: #fff">
	 <tr>
		  <td style="border: 1px #ccc solid">
			   <br>
    <?php
    include "libchart/libchart/classes/libchart.php";
//new pie chart instance
    $chart = new VerticalBarChart( 800, 400 );
 
    //data set instance
    $dataSet = new XYDataSet();
    
    $branchID=$_GET['branchID'];
    $regionID=$_GET['Region_ID'];
    $districtID=$_GET['District_ID'];
    $Date_From=date('Y-m-d',strtotime($_GET['Date_From']));
    $Date_To=date('Y-m-d',strtotime($_GET['Date_To']));
    $currentDate=date('Y-m-d');
    $ageFrom=$_GET['ageFrom'];
    $ageTo=$_GET['ageTo'];
    
    
    //The following are testing conditions to display the data according to filters
	    if($branchID == 0){//no branch is selected
		  if($regionID == 0){//if no region is selected  
			   $qr = "SELECT sp.Sponsor_ID,sp.Guarantor_Name,
				    (
				    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci  
				    WHERE 
					pr.Registration_ID=ci.Registration_ID
					AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
					AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
					AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
				    ) as male,
				    (
				    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci  
				    WHERE 
					pr.Registration_ID=ci.Registration_ID
					AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
					AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
					AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
				    ) as female
				FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC";
				$result = mysqli_query($conn,$qr);
			   //$qr1 = "SELECT name,value FROM subject";	             
		  }
		  else{//region is selected
		      if($districtID == 0){//if no district is selectd
			   $qr = "SELECT sp.Sponsor_ID,sp.Guarantor_Name,
				    (
				    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r
				    WHERE 
					pr.Registration_ID=ci.Registration_ID
					AND pr.District_ID=d.District_ID
					AND d.Region_ID=r.Region_ID
					AND d.Region_ID='$regionID'
					AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
					AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
					AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
				    ) as male,
				    (
				    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r
				    WHERE 
					pr.Registration_ID=ci.Registration_ID
					AND pr.District_ID=d.District_ID
					AND d.Region_ID=r.Region_ID
					AND d.Region_ID='$regionID'
					AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
					AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
					AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
				    ) as female
			         FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC";
				 $result = mysqli_query($conn,$qr);
			   //$qr1 = "SELECT name,value FROM subject";	 
		      }else{//district is selected
			   $qr = "SELECT sp.Sponsor_ID,sp.Guarantor_Name,
				    (
				    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r
				    WHERE 
					pr.Registration_ID=ci.Registration_ID
					AND pr.District_ID=d.District_ID
					AND d.Region_ID=r.Region_ID
					AND d.Region_ID='$regionID'
					AND pr.District_ID='$districtID'
					AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
					AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
					AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
				    ) as male,
				    (
				    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r
				    WHERE 
					pr.Registration_ID=ci.Registration_ID
					AND pr.District_ID=d.District_ID
					AND d.Region_ID=r.Region_ID
					AND d.Region_ID='$regionID'
					AND pr.District_ID='$districtID'
					AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
					AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
					AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
				    ) as female
			         FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC";
				 $result = mysqli_query($conn,$qr);
			   //$qr1 = "SELECT name,value FROM subject";			 
		      }
		  }    
}else{//branch is selected
        if($regionID == 0){//if no region is selected  
         $qr = "SELECT sp.Sponsor_ID,sp.Guarantor_Name,
				    (
				    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci  
                                                WHERE 
                                                    pr.Registration_ID=ci.Registration_ID
						    AND ci.Branch_ID=$branchID
                                                    AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
						    AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                    AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
                                                ) as male,
						(
						SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci  
                                                WHERE 
                                                    pr.Registration_ID=ci.Registration_ID
						    AND ci.Branch_ID=$branchID
                                                    AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
						    AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                    AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
                                                ) as female
				FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC";
				$result = mysqli_query($conn,$qr);
			   //$qr1 = "SELECT name,value FROM subject";	                 
    }
    else{//region is selected
        if($districtID == 0){//if no district is selectd
         $qr = "SELECT sp.Sponsor_ID,sp.Guarantor_Name,
				    (
				    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r
                                                WHERE 
                                                    pr.Registration_ID=ci.Registration_ID
                                                    AND pr.District_ID=d.District_ID
                                                    AND d.Region_ID=r.Region_ID
						    AND ci.Branch_ID='$branchID'
                                                    AND d.Region_ID='$regionID'
                                                    AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
						    AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                    AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
                                                ) as male,
						(
				    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r
                                                WHERE 
                                                    pr.Registration_ID=ci.Registration_ID
                                                    AND pr.District_ID=d.District_ID
                                                    AND d.Region_ID=r.Region_ID
						    AND ci.Branch_ID='$branchID'
                                                    AND d.Region_ID='$regionID'
                                                    AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
						    AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                    AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
                                                ) as female
				FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC";
				$result = mysqli_query($conn,$qr);
			   //$qr1 = "SELECT name,value FROM subject";		           
        }else{//district is selected
                  $qr = "SELECT sp.Sponsor_ID,sp.Guarantor_Name,
				    (
				    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r
                                                WHERE 
                                                    pr.Registration_ID=ci.Registration_ID
                                                    AND pr.District_ID=d.District_ID
                                                    AND d.Region_ID=r.Region_ID
						    AND ci.Branch_ID='$branchID'
                                                    AND d.Region_ID='$regionID'
                                                    AND pr.District_ID='$districtID'
                                                    AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
						    AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                    AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
                                                ) as male,
						(
				    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r
                                                WHERE 
                                                    pr.Registration_ID=ci.Registration_ID
                                                    AND pr.District_ID=d.District_ID
                                                    AND d.Region_ID=r.Region_ID
						    AND ci.Branch_ID='$branchID'
                                                    AND d.Region_ID='$regionID'
                                                    AND pr.District_ID='$districtID'
                                                    AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
						    AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                    AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
                                                ) as female
				FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC";
				$result = mysqli_query($conn,$qr);
			   //$qr1 = "SELECT name,value FROM subject";	         
        }//end else district is selected
     }//end else region is selected   
}//else if branch is selected
			
 
    $result = mysqli_query($conn,$qr);
	
	
    //get number of rows returned
    $num_results = mysqli_num_rows($result); //$result->num_rows;
    
    $total_Male=0;
    $total_Female=0;
    for($i=0;$i<$num_results;$i++){
	$row=mysqli_fetch_array($result);
	//return rows
	$sponsorID=$row['Sponsor_ID'];
	$sponsorName=$row['Guarantor_Name'];
	$male=$row['male'];
	$female=$row['female'];
       
	$total_Male=$total_Male + $male;
	$total_Female=$total_Female + $female;
	$total=$male+$female;
        $dataSet->addPoint(new Point("$sponsorName",$total));
    }//end for loop
    
    //finalize dataset
        $chart->setDataSet($dataSet);
 
        //set chart title
        $chart->setTitle("FIRST TIME TOTAL PATIENTS PIECHART");
        
        //render as an image and store under "generated" folder
		$image_name = "generated/chart_".$_SESSION['userinfo']['Employee_ID'].".png";
        $chart->render($image_name);
    
        //pull the generated chart where it was stored
        echo "<img alt='Pie chart'  src='$image_name' style='border: 1px solid gray;margin-left:1%;'/>";
        ?>
	<br>
	</td>
	 </tr>
</table>
	<!--Include files-->
	<table width="93%">
	    <tr>
		<td style='text-align: center;border: 0; width: 30%;'>
		    <a href="visitedPatients.php"> 
			<input type='submit' name='back.php' id='back.php' class='art-button-green' value='HOME' style="width: 88%;"> 
		    </a>
		</td>
		<td style='text-align: center;border: 0; width: 30%;'>
		    <a href="visitedPatientsFilterLineGraph.php?&branchID=<?php echo $branchID?>&Region_ID=<?php echo $regionID?>&District_ID=<?php echo $districtID?>&ageFrom=<?php echo $ageFrom?>&ageTo=<?php echo $ageTo?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>">
			    <input type='submit' name='graph' id='praph' class='art-button-green' value='TOTAL PATIENTS LINE GRAPHS' style="width: 88%;">
			
		    </a>
		</td>
		<td style='text-align: center;border: 0; width: 30%;'>
		    <a href="visitedPatientsFilterPieChart.php?&branchID=<?php echo $branchID?>&Region_ID=<?php echo $regionID?>&District_ID=<?php echo $districtID?>&ageFrom=<?php echo $ageFrom?>&ageTo=<?php echo $ageTo?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>">
			    <input type='submit' name='pieChart' id='praph' class='art-button-green' value='TOTAL PATIENTS PIE CHART' style="width: 88%;">
			</a>
		</td>
	    </tr>
	    <tr>
		<td style='text-align: center;border: 0'>
		    <a href="visitedPatientsFilterHorizontalBarChart.php?&branchID=<?php echo $branchID?>&Region_ID=<?php echo $regionID?>&District_ID=<?php echo $districtID?>&ageFrom=<?php echo $ageFrom?>&ageTo=<?php echo $ageTo?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>">
			    <input type='submit' name='horizontalBarChart' id='horizontalBarChart' class='art-button-green' value='TOTAL PATIENTS HORIZONTAL BAR CHART' style="width: 88%;">
		 </a>
		</td>
		<td style='text-align: center;border: 0'>
		    <a href="visitedPatientsFilterVerticalBarChart.php?&branchID=<?php echo $branchID?>&Region_ID=<?php echo $regionID?>&District_ID=<?php echo $districtID?>&ageFrom=<?php echo $ageFrom?>&ageTo=<?php echo $ageTo?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>">
			
			    <input type='submit' name='verticalBarChart' id='verticalBarChart' class='art-button-green' value='TOTAL PATIENTS VERTICAL BAR CHART' style="width: 88%;">
			
		    </a>
		</td>
		<td style='text-align: center;border: 0'>
		    <a href="visitedPatientsFilterMultipleBarChart.php?&branchID=<?php echo $branchID?>&Region_ID=<?php echo $regionID?>&District_ID=<?php echo $districtID?>&ageFrom=<?php echo $ageFrom?>&ageTo=<?php echo $ageTo?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>">
			<input type='submit' name='multipleBarGraph.php' id='multipleBarGraph.php' class='art-button-green' value='MALE AND FEMALE MULTIPLE BAR CHART' style="width: 88%;">
		    </a>
		</td>
	    </tr>
				</table>   
</fieldset>
</center>
	<?php
    include("./includes/footer.php");
?>