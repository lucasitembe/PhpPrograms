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
    <a href='./demographicReport.php?Reception=ReceptionThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<script type="text/javascript" language="javascript">
    function getDistrictsList(Region_ID) {
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','getDistrictsList.php?Region_ID='+Region_ID,true);
	    mm.send();
	}
    function AJAXP() {
	var data = mm.responseText;
	document.getElementById('District_ID').innerHTML = data;	
    }
    
//    function to verify NHIF STATUS
    function nhifVerify(){
	//code
    }
</script>
<br><br>
<center>
<fieldset style="width: 90%">
     <legend align="right"><b>DEMOGRAPHIC TOAL  PATIENTS HORIZONTAL BAR CHART</b></legend>
     <br>
<table width="93%" style="background-color: #fff">
	 <tr>
		  <td style="border: 1px #ccc solid">
		    <br>
			       <?php
			       include "libchart/libchart/classes/libchart.php";
//new pie chart instance
    $chart = new HorizontalBarChart( 800, 500 );
 
    //data set instance
    $dataSet = new XYDataSet();
    
    
    $regionID=$_GET['Region_ID'];
    $districtID=$_GET['District_ID'];
    $Date_From=date('Y-m-d',strtotime($_GET['Date_From']));
    $Date_To=date('Y-m-d',strtotime($_GET['Date_To']));
    $currentDate=date('Y-m-d');
    $ageFrom=$_GET['ageFrom'];
    $ageTo=$_GET['ageTo'];
    
    
    //The following are testing conditions to display the data according to filters
	    if($regionID == 0){//if no region is selected  
			   $qr = "SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                                                        (							
							SELECT COUNT(Gender) FROM tbl_patient_registration pr  
							WHERE pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'
							    AND pr.Registration_Date BETWEEN '$Date_From' AND '$Date_To'
                                                            AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)  
                                                        ) as male,
                                                        (							
							SELECT COUNT(Gender) FROM tbl_patient_registration pr  
							WHERE pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'
							    AND pr.Registration_Date BETWEEN '$Date_From' AND '$Date_To'
                                                            AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)  
                                                        ) as female
                                                    FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC";
				$result = mysqli_query($conn,$qr);
			   //$qr1 = "SELECT name,value FROM subject";	             
		  }
		  else{//region is selected
		      if($districtID == 0){//if no district is selectd
			   $qr = "SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                                                    (
                                                    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_district d,tbl_regions r
                                                    WHERE pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'
                                                        AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
                                                        AND pr.Registration_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)      
                                                    ) as male,
                                                    (
                                                    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_district d,tbl_regions r
                                                    WHERE pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'
                                                        AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
                                                        AND pr.Registration_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)      
                                                    ) as female
                                                FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC";
				 $result = mysqli_query($conn,$qr);
			   //$qr1 = "SELECT name,value FROM subject";	 
		      }else{//district is selected
			   $qr = "SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                                                    (
                                                    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_district d,tbl_regions r
                                                    WHERE pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'
                                                        AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
							AND d.District_ID='$districtID'
                                                        AND pr.Registration_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)      
                                                    ) as male,
                                                    (
                                                    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_district d,tbl_regions r
                                                    WHERE pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'
                                                        AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
							AND d.District_ID='$districtID'
                                                        AND pr.Registration_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)      
                                                    ) as female
                                                FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC";
				 $result = mysqli_query($conn,$qr);
			   //$qr1 = "SELECT name,value FROM subject";			 
		      }
		  }
			
 
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
        $chart->setTitle("DEMOGRAPHIC TOTAL PATIENTS HORIZONTAL BAR CHART");
        
        //render as an image and store under "generated" folder
		$image_name = "generated/chart_".$_SESSION['userinfo']['Employee_ID'].".png";
        $chart->render($image_name);
    
        //pull the generated chart where it was stored
        echo "<img alt='Pie chart'  src='$image_name' style='border: 1px solid gray;margin-left:1%;'/>";
        ?>
		  </td>
	 </tr>
</table>	
	<br>


<table width="93%">
	    <tr>
		<td style='text-align: center;border: 0; width: 30%;'>
		    <a href="demographicReport.php"> 
			<input type='submit' name='back.php' id='back.php' class='art-button-green' value='HOME' style="width: 88%;"> 
		    </a>
		</td>
		<td style='text-align: center;border: 0; width: 30%;'>
		    <a href="demographicFilterLineGraph.php?&branchID=<?php echo $branchID?>&Region_ID=<?php echo $regionID?>&District_ID=<?php echo $districtID?>&ageFrom=<?php echo $ageFrom?>&ageTo=<?php echo $ageTo?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>">
			    <input type='submit' name='graph' id='praph' class='art-button-green' value='TOTAL PATIENTS LINE GRAPHS' style="width: 88%;">
			
		    </a>
		</td>
		<td style='text-align: center;border: 0; width: 30%;'>
		    <a href="demographicFilterPieChart.php?&branchID=<?php echo $branchID?>&Region_ID=<?php echo $regionID?>&District_ID=<?php echo $districtID?>&ageFrom=<?php echo $ageFrom?>&ageTo=<?php echo $ageTo?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>">
			    <input type='submit' name='graph' id='praph' class='art-button-green' value='TOTAL PATIENTS PIE CHART' style="width: 88%;">
			
		    </a>
		</td>
	    </tr>
	    <tr>
		<td style='text-align: center;border: 0'>
		    <a href="demographicFilterHorizontalBarChart.php?&branchID=<?php echo $branchID?>&Region_ID=<?php echo $regionID?>&District_ID=<?php echo $districtID?>&ageFrom=<?php echo $ageFrom?>&ageTo=<?php echo $ageTo?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>">
			    <input type='submit' name='horizontalBarChart' id='horizontalBarChart' class='art-button-green' value='TOTAL PATIENTS HORIZONTAL BAR CHART' style="width: 88%;">
		 </a>
		</td>
		<td style='text-align: center;border: 0'>
		    <a href="demographicFilterVerticalBarChart.php?&branchID=<?php echo $branchID?>&Region_ID=<?php echo $regionID?>&District_ID=<?php echo $districtID?>&ageFrom=<?php echo $ageFrom?>&ageTo=<?php echo $ageTo?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>">
			    <input type='submit' name='verticalBarChart' id='verticalBarChart' class='art-button-green' value='TOTAL PATIENTS VERTICAL BAR CHART' style="width: 88%;">
		     </a>
		</td>
		<td style='text-align: center;border: 0'>
		    <a href="demographicFilterMultipleBarChart.php?&branchID=<?php echo $branchID?>&Region_ID=<?php echo $regionID?>&District_ID=<?php echo $districtID?>&ageFrom=<?php echo $ageFrom?>&ageTo=<?php echo $ageTo?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>">
			<input type='submit' name='multipleBarGraph.php' id='multipleBarGraph.php' class='art-button-green' value='MALE AND FEMALE MULTIPLE BAR CHART' style="width: 88%;">
		    </a>
		</td>
	    </tr>
    </table> 

<?php
    include("./includes/footer.php");
?>