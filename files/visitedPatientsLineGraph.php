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
<fieldset style="width: 93%">
     <legend align="right"><b>FIRST TIME TOTAL PATIENTS LINE GRAPH</b></legend>
     <br>
<table width="93%" style="background-color: #fff">
	 <tr>
		  <td style="border: 1px #ccc solid">
			   <br>
			   <?php
			   include "libchart/libchart/classes/libchart.php";
//new pie chart instance
    $chart = new LineChart( 800, 400 );
 
    //data set instance
    $dataSet = new XYDataSet();
    
    //query all records from the database
    $currentDate=date('Y-m-d');
    $query = "SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                (
                SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci  
                WHERE pr.Registration_Date=ci.Check_In_Date AND 
                    pr.Registration_ID=ci.Registration_ID AND
                   ci.Check_In_Date <= '$currentDate' AND
                    pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'
                ) as male,
                (
                SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci    
                    WHERE pr.Registration_Date=ci.Check_In_Date AND 
                    pr.Registration_ID=ci.Registration_ID AND
                    ci.Check_In_Date <= '$currentDate' AND
                    pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
                ) as female
            FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC";
			
 
    $result = mysqli_query($conn,$query);
	
	
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
        $chart->setTitle("FIRST TIME TOTAL PATIENTS LINE GRAPH");
        
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
	<?php require('visitedPatientsGraphInclude.php');?>

<?php
    include("./includes/footer.php");
?>