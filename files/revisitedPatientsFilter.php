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
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='./revisitedPatients.php?Reception=ReceptionThisPage' class='art-button-green'>
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
<br/><br/>

  <style>
		table,tr,td{
		border-collapse:collapse !important;
		border:none !important;
		}
 
 </style>   
<fieldset style="background-color:white;">
    <?php
                $Date_From=date('Y-m-d H:i:s',strtotime($_POST['Date_From']));
                $Date_To=date('Y-m-d H:i:s',strtotime($_POST['Date_To']));
		$ageFrom=$_POST['ageFrom'];
		$ageTo=$_POST['ageTo'];
    ?>
            <legend align="center" style="background-color:#006400;color:white;padding:5px;"><b>SUMMARY ~ DEMOGRAPHIC REPORT (REVISIT/FOLLOW-UPS) AGED BETWEEN </b><b style="color: yellow;"><?php echo $ageFrom." Year(s) ";?></b><b>AND</b> <b style="color: yellow;"><?php echo $ageTo." Year(s)";?></b> <b>FROM</b> <b style='color: yellow;'><?php echo date('j F, Y H:i:s',strtotime($Date_From));?></b><b> TO </b><b style='color:yellow'><?php echo date('j F, Y H:i:s',strtotime($Date_To));?></b></legend>
        <center>
    <form action='revisitedPatientsFilter.php?VisitedPatientsFilterThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <table width=100%>
        <tr>
	    <td style="text-align: center"><b>BRANCH</b></td>
	    <td style="text-align: center"><b>REGION</b></td>
	    <td style="text-align: center"><b>DISTRICT</b></td>
	    <td style="text-align: center"><b>AGE</b></td>
            <td style="text-align: center"><b>FROM</b></td>
            <td style="text-align: center"><b>TO</b></td>
           
        </tr>
		<tr><td colspan="7"><hr></td></tr>
	<tr>
	    <td>
		<select name="branchID" id="branchID">
		    <option selected="selected" value="0">All</option>
		    <?php
			$select_branch=mysqli_query($conn,"SELECT * FROM tbl_branches");
			while($branchRow=mysqli_fetch_array($select_branch)){
			    ?>
			    <option value="<?php echo $branchRow['Branch_ID']?>"><?php echo $branchRow['Branch_Name']?></option>
			<?php }
			
		    ?>
		</select>
	    </td>
	    <td style='text-align: center; width: 15%'>
		<select name='Region_ID' id='Region_ID' onchange='getDistrictsList(this.value)'>
                                        <option selected='selected' value='0'>All</option>
					<?php
					    $data = mysqli_query($conn,"select * from tbl_regions");
					        while($row = mysqli_fetch_array($data)){
					    ?>
					    <option value='<?php echo $row['Region_ID'];?>'>
						<?php echo $row['Region_Name']; ?>
					    </option>
					<?php
					    }
					?>
                                    </select>	    
	    </td>
	    <td style='text-align: center; width: 15%'>
		<select name='District_ID' id='District_ID'>
		    <option selected='selected' value='0'>All</option>
					
                                    </select>	    
		</select>
	    </td>
	    <td>
		From<input type="text" name="ageFrom" id="ageFrom" style="width:30%;text-align: center;background-color:#eeeeee;" required="required"/>
		To<input type="text" name="ageTo" id="ageTo" style="width:30%;text-align:center;background-color:#eeeeee;" required="required"/>
	    </td>
	    <td style="text-align: center; border: 1px #ccc solid;width: 15%"><input type='text' name='Date_From' id='date_From' required='required' style="background-color:#eeeeee;" value='<?php echo date('Y-m-d H:i:s');?>'></td>
	    <td style="text-align: center; border: 1px #ccc solid;width: 15%"><input type='text' name='Date_To' id='date_To' required='required' style="background-color:#eeeeee;" value='<?php echo date('Y-m-d H:i:s');?>'></td>
		 <td style='text-align: center;'>
                <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
            </td>
	</tr>	
    </table>
    </form>    
</center>
</form>    
</center>
</form>
	<script src="css/jquery.js"></script>
	<script src="css/jquery.datetimepicker.js"></script>
	<script>
	$('#date_From').datetimepicker({
	dayOfWeekStart : 1,
	lang:'en',
	startDate:	'now'
	});
	$('#date_From').datetimepicker({value:'',step:30});
	$('#date_To').datetimepicker({
	dayOfWeekStart : 1,
	lang:'en',
	startDate:'now'
	});
	$('#date_To').datetimepicker({value:'',step:30});
	</script>
	<!--End datetimepicker-->
<center>

		<center> 
            <?php
		echo '<center><table width =100% border=0>';
    echo "<tr>
                <td width=3%><b>SN</b></td>
                <td style=''><b>&nbsp;&nbsp;&nbsp;&nbsp;SPONSOR NAME</b></td>
                <td style='text-align: right;' width=10%><b>MALE</b></td>
                <td style='text-align: right;' width=10%><b>FEMALE</b></td>
		<td style='text-align: right;' width=10%><b>TOTAL</b></td>
         </tr>";
		  echo "<br>";
	 echo "<hr>";
    echo "<tr>
                <td colspan=5 style='border: 1px #ccc solid'><hr></td></tr>";
		$branchID=$_POST['branchID'];
		$regionID=$_POST['Region_ID'];
		$districtID=$_POST['District_ID'];
		$Date_From=date('Y-m-d H:i:s',strtotime($_POST['Date_From']));
		$Date_To=date('Y-m-d H:i:s',strtotime($_POST['Date_To']));
		$currentDate=date('Y-m-d H:i:s');
                $ageFrom=$_POST['ageFrom'];
		$ageTo=$_POST['ageTo'];
                
            //The following are testing conditions to display the data according to filters
	    if($branchID == 0){//no branch is selected
		if($regionID == 0){//if no region is selected  
                    $select_demograph = mysqli_query($conn,"SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                                                        (
                                                        SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci  
                                                        WHERE pr.Registration_Date <> ci.Check_In_Date AND 
                                                            pr.Registration_ID=ci.Registration_ID
                                                            AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
                                                            AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                            AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
                                                        ) as male,
                                                        (
                                                        SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci    
                                                        WHERE pr.Registration_Date <> ci.Check_In_Date AND 
                                                            pr.Registration_ID=ci.Registration_ID
                                                             AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
                                                             AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                             AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
                                                        ) as female
                                                    FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC"
                            ) or die(mysqli_error($conn));               
            }
        else{//region is selected
            if($districtID == 0){//if no district is selectd
                $select_demograph = mysqli_query($conn,"SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                                                    (
                                                    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r
                                                    WHERE pr.Registration_Date <> ci.Check_In_Date AND 
                                                        pr.Registration_ID=ci.Registration_ID
                                                        AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
                                                        AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                        AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
                                                    ) as male,
                                                    (
                                                    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r    
                                                    WHERE pr.Registration_Date <> ci.Check_In_Date AND 
                                                        pr.Registration_ID=ci.Registration_ID
                                                        AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
                                                         AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
							 AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                         AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
                                                    ) as female
                                                FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC"
                        ) or die(mysqli_error($conn));               
            }else{//district is selected
                $select_demograph = mysqli_query($conn,"SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                                                    (
                                                    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r
                                                    WHERE pr.Registration_Date <> ci.Check_In_Date AND 
                                                        pr.Registration_ID=ci.Registration_ID
                                                        AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
                                                        AND pr.District_ID='$districtID'
                                                        AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                        AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
                                                    ) as male,
                                                    (
                                                    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r    
                                                    WHERE pr.Registration_Date <> ci.Check_In_Date AND 
                                                        pr.Registration_ID=ci.Registration_ID
                                                        AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
                                                        AND pr.District_ID='$districtID'
                                                         AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
							 AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                         AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
                                                    ) as female
                                                FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC"
                        ) or die(mysqli_error($conn));               
            }
        }    
}else{//branch is selected
        if($regionID == 0){//if no region is selected  
            $select_demograph = mysqli_query($conn,"SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                                                (
                                                SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci  
                                                WHERE pr.Registration_Date <> ci.Check_In_Date AND 
                                                    pr.Registration_ID=ci.Registration_ID
						    AND ci.Branch_ID=$branchID
                                                    AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
						    AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                    AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
                                                ) as male,
                                                (
                                                SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci    
                                                WHERE pr.Registration_Date <> ci.Check_In_Date AND 
                                                    pr.Registration_ID=ci.Registration_ID
						    AND ci.Branch_ID=$branchID
                                                    AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
						    AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                    AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
                                                ) as female
                                            FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC"
                    ) or die(mysqli_error($conn));               
    }
    else{//region is selected
        if($districtID == 0){//if no district is selectd
            $select_demograph = mysqli_query($conn,"SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                                                (
                                                SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r
                                                WHERE pr.Registration_Date <> ci.Check_In_Date AND 
                                                    pr.Registration_ID=ci.Registration_ID
                                                    AND pr.District_ID=d.District_ID
                                                    AND d.Region_ID=r.Region_ID
						    AND ci.Branch_ID='$branchID'
                                                    AND d.Region_ID='$regionID'
                                                    AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
						    AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                    AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
                                                ) as male,
                                                (
                                                SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r    
                                                WHERE pr.Registration_Date <> ci.Check_In_Date AND 
                                                    pr.Registration_ID=ci.Registration_ID
                                                    AND pr.District_ID=d.District_ID
                                                    AND d.Region_ID=r.Region_ID
						    AND ci.Branch_ID='$branchID'
                                                    AND d.Region_ID='$regionID'
                                                     AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
						     AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                     AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
                                                ) as female
                                            FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC"
                    ) or die(mysqli_error($conn));               
        }else{//district is selected
            $select_demograph = mysqli_query($conn,"SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                                                (
                                                SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r
                                                WHERE pr.Registration_Date <> ci.Check_In_Date AND 
                                                    pr.Registration_ID=ci.Registration_ID
                                                    AND pr.District_ID=d.District_ID
                                                    AND d.Region_ID=r.Region_ID
						    AND ci.Branch_ID='$branchID'
                                                    AND d.Region_ID='$regionID'
                                                    AND pr.District_ID='$districtID'
                                                    AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
						    AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                    AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
                                                ) as male,
                                                (
                                                SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r    
                                                WHERE pr.Registration_Date <> ci.Check_In_Date AND 
                                                    pr.Registration_ID=ci.Registration_ID
                                                    AND pr.District_ID=d.District_ID
                                                    AND d.Region_ID=r.Region_ID
						    AND ci.Branch_ID='$branchID'
                                                    AND d.Region_ID='$regionID'
                                                    AND pr.District_ID='$districtID'
                                                     AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
						     AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                     AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
                                                ) as female
                                            FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC"
                    ) or die(mysqli_error($conn));               
        }//end else district is selected
     }//end else region is selected   
}//else if branch is selected
            
            
    $total_Male=0;
    $total_Female=0;
    $res=mysqli_num_rows($select_demograph);
    for($i=0;$i<$res;$i++){
	$row=mysqli_fetch_array($select_demograph);
	//return rows
	$sponsorID=$row['Sponsor_ID'];
	$sponsorName=$row['Guarantor_Name'];
	$male=$row['male'];
	$female=$row['female'];
        echo "<tr><td>".($i+1)."</td>";
        echo "<td><a href='revisitedPatientsFilterDetails.php?sponsorID=$sponsorID&Date_From=$Date_From&Date_To=$Date_To&ageFrom=$ageFrom&ageTo=$ageTo&branchID=$branchID&Region_ID=$regionID&District_ID=$districtID&SponsorDetails=SponsorDetailsThisPage'>".$row['Guarantor_Name']."</a></td>";
	$total_Male=$total_Male + $male;
	echo "<td style='text-align:right;'>".number_format($male)."</td>";
	$total_Female=$total_Female + $female;
	echo "<td style='text-align:right;'>".number_format($female)."</td>";
	$total=$male+$female;
	echo "<td style='text-align:right;'>".number_format($total)."</td>";
    }//end for loop
	echo "<tr><td colspan=5 style='border: 1px #ccc solid'><hr></td></tr>";
    echo "<tr><td colspan=2 style='text-align: right;'><b>&nbsp;&nbsp;TOTAL</b></td>";
    echo "<td style='text-align: right;'><b>".number_format($total_Male)."</b></td>";
    echo "<td style='text-align: right;'><b>".number_format($total_Female)."</b></td>";
    $total_Male_Female=$total_Male+$total_Female;
    echo "<td style='text-align: right;'><b>".number_format($total_Male_Female)."</b></td></tr>";
	    ?>
            
            <tr>
                <td>
                    <table>
                        <tr>
                
                <td>
                    <!--<form action="sponsorPreview.php?DemographicPage=ThisPage" method="POST">
                <table>
                    <tr>
                         <td style='text-align: center; border: 0'>
                            <input type='submit' name='preview' id='preview' class='art-button-green' value='PREVIEW ALL'>
                        </td>
                    </tr>
                </table>
                    </form>-->
                </td>
            </tr>
                    </table>
                </td>
            </tr>
            
            </table></center>
        </center>
</fieldset>
 <table width="100%">
		<tr>
			<td style='text-align:right;widht:100%;'>
				<a href="revisitedPatientsFilterMultipleBarChart.php?&branchID=<?php echo $branchID?>&Region_ID=<?php echo $regionID?>&District_ID=<?php echo $districtID?>&ageFrom=<?php echo $ageFrom?>&ageTo=<?php echo $ageTo?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>">
				
						<input type='submit' name='graph' id='praph' class='art-button-green' value='GRAPHS'>
					
				</a>
			</td>
		</tr>
     </table>
<?php
    include("./includes/footer.php");
?>