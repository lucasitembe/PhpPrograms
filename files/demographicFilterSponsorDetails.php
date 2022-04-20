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
<style>
		table,tr,td{
		border-collapse:collapse !important;
		border:none !important;
		}
 
 </style> 

<br/><br/>
<center>
    <?php
    
	if(isset($_GET['sponsorID'])){
	    $sponsorID=$_GET['sponsorID'];
             $ageFrom=$_GET['ageFrom'];
             $ageTo=$_GET['ageTo'];
             $Date_From=date('Y-m-d',strtotime($_GET['Date_From']));
             $Date_To=date('Y-m-d',strtotime($_GET['Date_To']));
		 $District_ID=$_GET['District_ID'];
		 $Region_ID=$_GET['Region_ID'];
	$select_sponsor=mysqli_query($conn,"SELECT * FROM tbl_sponsor WHERE Sponsor_ID='$sponsorID'");
	$sponsorRow=mysqli_fetch_array($select_sponsor);
	$sponsorName=$sponsorRow['Guarantor_Name'];
	}else{
	    $sponsorID=0;
	    $select_sponsor=mysqli_query($conn,"SELECT * FROM tbl_sponsor WHERE Sponsor_ID='$sponsorID'");
	$sponsorRow=mysqli_fetch_array($select_sponsor);
	$sponsorName=$sponsorRow['Guarantor_Name'];
	}
    ?>
<fieldset style="background-color:white;">
<legend align="center" style="background-color:#006400;color:white;padding:5px;"><b>DETAILS ~ DEMOGRAPHIC REPORT (NEW VISITS) AGED BETWEEN </b><b style="color: yellow;"><?php echo $ageFrom." Year(s) ";?></b><b>AND</b> <b style="color: yellow;"><?php echo $ageTo." Year(s)";?></b> <b>FROM</b> <b style='color: yellow;'><?php echo date('j F, Y',strtotime($Date_From));?></b><b> TO </b><b style='color:yellow'><?php echo date('j F, Y',strtotime($Date_To));?></b><b> FOR <?php echo "<b style='color: yellow'>".strtoupper($sponsorName)."</b>"?> SPONSOR</b></legend>
<br>

    <form action='demographicFilterSponsorDetailsFilter.php?DemographicReportFilterThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <table width=100%>
        <tr>
	    <!--<td style="text-align: center"><b>Branch</b></td>-->
	    <td style="text-align: center"><b>REGION</b></td>
	    <td style="text-align: center"><b>DISTRICT</b></td>
	    <td style="text-align: center"><b>AGE</b></td>
            <td style="text-align:center"><b>FROM</b></td>
            <td style="text-align:center"><b>TO</b></td>
	    <td style="text-align: center"><b>GENDER</b></td>
           
        </tr>
		<tr><td colspan="7"><hr></td></tr>
	<tr>
	    <!--<td>
		<select name="branchID" id="branchID">
		    <option selected="selected" value="0">All</option>
		    <?php
			/*$select_branch=mysqli_query($conn,"SELECT * FROM tbl_branches");
			while($branchRow=mysqli_fetch_array($select_branch)){
			    ?>
			   <!-- <option value="<?php echo $branchRow['Branch_ID']?>"><?php echo $branchRow['Branch_Name']?></option>-->
			<?php }
			*/
		    ?>
		</select>
	    </td>-->
	    <td style='text-align: center; width: 15%'><b>Region</b>
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
	    <td style='text-align: center; width: 15%'><b>District</b>
		<select name='District_ID' id='District_ID'>
		    <option selected='selected' value='0'>All</option>
					
                                    </select>	    
		</select>
	    </td>
	    <td style="text-align: center">
		<b>From</b><input type="text" name="ageFrom" id="ageFrom" style="width: 100px;text-align:center;background-color:#eeeeee;" autocomplete="off" required="required"/>
		<b>To</b><input type="text" name="ageTo" id="ageTo" style="width: 100px;text-align: center;background-color:#eeeeee;" autocomplete="off" required="required"/>
	    </td>
	    <td><input type='text' name='Date_From' id='date_From' autocomplete="off" style="background-color:#eeeeee;" required='required'></td>
	    <td><input type='text' name='Date_To' id='date_To' autocomplete="off" style="background-color:#eeeeee;" required='required'>
                <input type='hidden' name='sponsorID' id='sponsorID' value="<?php echo $sponsorID; ?>">
	    </td>
	    <td>
		<select name="gender" id="gender">
		    <option selected="selected" value="All">All</option>
		    <option value="Male">Male</option>
		    <option value="Female">Female</option>
		</select>
	    </td>
		 <td style='text-align: center;'>
                <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
            </td>
	</tr>	
    </table>
	<br>
	<br>
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
    <?php
    
	if(isset($_GET['sponsorID'])){
	        $regionID=$_GET['Region_ID'];
			$district_ID=$_GET['District_ID'];
	        $sponsorID=$_GET['sponsorID'];
            $ageFrom=$_GET['ageFrom'];
            $ageTo=$_GET['ageTo'];
            $Date_From=date('Y-m-d',strtotime($_GET['Date_From']));
            $Date_To=date('Y-m-d',strtotime($_GET['Date_To']));
	$select_sponsor=mysqli_query($conn,"SELECT * FROM tbl_sponsor WHERE Sponsor_ID='$sponsorID'");
	$sponsorRow=mysqli_fetch_array($select_sponsor);
	$sponsorName=$sponsorRow['Guarantor_Name'];
	}else{
	    $sponsorID=0;
	    $select_sponsor=mysqli_query($conn,"SELECT * FROM tbl_sponsor WHERE Sponsor_ID='$sponsorID'");
	$sponsorRow=mysqli_fetch_array($select_sponsor);
	$sponsorName=$sponsorRow['Guarantor_Name'];
	}
      
$regionID = $_GET['Region_ID'];
$districtID = $_GET['District_ID'];
$ageFrom = $_GET['ageFrom'];
$ageTo = $_GET['ageTo'];
$Date_From = $_GET['Date_From'];
$Date_To = $_GET['Date_To'];
$sponsorID = $_GET['sponsorID'];
$currentDate = date('Y-m-d');

//declare all category variables.......
$Total_Age_Below_1_Month_Male = 0;
$Total_Age_Below_1_Month_Female = 0;
$Grand_Total_Age_Below_1_Month = 0;

$Total_Age_Between_1_Month_But_Below_1_Year_Male = 0;
$Total_Age_Between_1_Month_But_Below_1_Year_Female = 0;
$Grand_Total_Age_Between_1_Month_But_Below_1_Year = 0;

$Total_Age_Between_1_Year_But_Below_5_Year_Male = 0;
$Total_Age_Between_1_Year_But_Below_5_Year_Female = 0;
$Grand_Total_Age_Between_1_Year_But_Below_5_Year = 0;


$Total_Five_Years_Or_Below_Sixty_Years_Male = 0;
$Total_Five_Years_Or_Below_Sixty_Years_Female = 0;
$Grand_Total_Five_Years_Or_Below_Sixty_Years = 0;

$Total_Age_60_Years_And_Above_Male = 0;
$Total_Age_60_Years_And_Above_Female = 0;
$Grand_Total_Age_60_Years_And_Above = 0;

$Total_Male = 0;
$Total_Female = 0;
$Grand_Total_Male = 0;
$Grand_Total_Female = 0;
$select_OPD = '';
?>
<table width="100%">
    <tr><td colspan=8><hr></td></tr>
    <tr>
        <td width="5%" style="text-align: center;"></td>
        <td style="text-align: center;"></td>
        <td width="13%" style="text-align: center;">
            <table width="100%">
                <tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Umri chini ya mwezi 1</span></td></tr>
            </table>
        </td>
        <td width="13%" style="text-align: center;">
            <table width="100%">
                <tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Umri mwezi 1 hadi umri chini ya mwaka 1</span></td></tr>
            </table>
        </td>
        <td width="13%" style="text-align: center;">
            <table width="100%">
                <tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Umri mwaka 1 hadi umri chini ya miaka 5</span></td></tr>
            </table>
        </td>
        <td width="13%" style="text-align: center;">
            <table width="100%">
                <tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Umri miaka 5 hadi miaka chini ya 60</span></td></tr>
            </table>
        </td>
        <td width="13%" style="text-align: center;">
            <table width="100%">
                <tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Umri miaka 60 na kuendelea</span></td></tr>
            </table>
        </td>
        <td width="13%" style="text-align: center;">
            <table width="100%">
                <tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Jumla Kuu</span></td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="5%" style="text-align: center;"><span style="font-size: x-small;">Na</span></td>
        <td style="text-align: center;"><span style="font-size: x-small;">Maelezo</span></td>
        <td width="13%" style="text-align: center;">
            <table width="100%">
                <tr>
                    <td style="text-align: center;"><span style="font-size: x-small;">ME</span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;">KE</span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;">Jumla</span></td>
                </tr>
            </table>
        </td>
        <td width="13%" style="text-align: center;">
            <table width="100%">
                <tr>
                    <td style="text-align: center;"><span style="font-size: x-small;">ME</span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;">KE</span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;">Jumla</span></td>
                </tr>
            </table>
        </td>
        <td width="13%" style="text-align: center;">
            <table width="100%">
                <tr>
                    <td style="text-align: center;"><span style="font-size: x-small;">ME</span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;">KE</span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;">Jumla</span></td>
                </tr>
            </table>
        </td>
        <td width="13%" style="text-align: center;">
            <table width="100%">
                <tr>
                    <td style="text-align: center;"><span style="font-size: x-small;">ME</span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;">KE</span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;">Jumla</span></td>
                </tr>
            </table>
        </td>
        <td width="13%" style="text-align: center;">
            <table width="100%">
                <tr>
                    <td style="text-align: center;"><span style="font-size: x-small;">ME</span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;">KE</span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;">Jumla</span></td>
                </tr>
            </table>
        </td>
        <td>
            <table width="100%">
                <tr>
                    <td style="text-align: center;"><span style="font-size: x-small;">ME</span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;">KE</span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;">Jumla</span></td>
                </tr>
            </table>
        </td></tr>
    <tr><td colspan=8><hr></td></tr>      
<?php
//get total mahudhurio ya OPD
$Total_OPD_Below_1_Month_Male = 0;
$Total_OPD_Below_1_Month_Female = 0;
$Grand_Total_OPD_Below_1_Month = 0;

$Total_OPD_Between_1_Month_But_Below_1_Year_Male = 0;
$Total_OPD_Between_1_Month_But_Below_1_Year_Female = 0;
$Grand_Total_OPD_Between_1_Month_But_Below_1_Year = 0;

$Total_OPD_Between_1_Year_But_Below_5_Year_Male = 0;
$Total_OPD_Between_1_Year_But_Below_5_Year_Female = 0;
$Grand_Total_OPD_Between_1_Year_But_Below_5_Year = 0;


$Total_OPD_Five_Years_Or_Below_Sixty_Years_Male = 0;
$Total_OPD_Five_Years_Or_Below_Sixty_Years_Female = 0;
$Grand_Total__OPDFive_Years_Or_Below_Sixty_Years = 0;

$Total_OPD_60_Years_And_Above_Male = 0;
$Total_OPD_60_Years_And_Above_Female = 0;
$Grand_Total_OPD_60_Years_And_Above = 0;

$Total_OPD_Male = 0;
$Total_OPD_Female = 0;
$Grand_Total_OPD_Male = 0;
$Grand_Total_OPD_Female = 0;



if ($regionID == 0) {//if no region is selected  
  
    $select_All = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr, tbl_check_in ci,tbl_sponsor sp 
                                WHERE 
                                pr.Sponsor_ID=sp.Sponsor_ID
                                AND pr.Registration_ID=ci.Registration_ID
                                AND pr.Sponsor_ID='$sponsorID'
                                AND pr.Registration_Date_AND_Time BETWEEN '$Date_From' AND '$Date_To'
                                AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)
										") or die(mysqli_error($conn));
    
   
    
} else {//region is selected
    if ($districtID == 0) {//if no district is selectd
        $select_All = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_district d,tbl_regions r, tbl_check_in ci ,tbl_sponsor sp 
									WHERE pr.Sponsor_ID=sp.Sponsor_ID
                                                                        AND pr.Registration_ID=ci.Registration_ID
                                                                        AND pr.Sponsor_ID='$sponsorID'
										AND pr.District_ID=d.District_ID
										AND d.Region_ID=r.Region_ID
										AND d.Region_ID='$regionID'
										AND pr.Registration_Date_AND_Time BETWEEN '$Date_From' AND '$Date_To'
                                   AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)      
									") or die(mysqli_error($conn));
    } else {//district is selected
        $select_All = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_district d,tbl_regions r, tbl_check_in ci ,tbl_sponsor sp 
                                        WHERE pr.Sponsor_ID=sp.Sponsor_ID
                                        AND pr.Registration_ID=ci.Registration_ID
                                        AND pr.District_ID=d.District_ID
                                        AND d.Region_ID=r.Region_ID
                                        AND d.Region_ID='$regionID'
                                        AND d.District_ID='$districtID'
                                        AND pr.Sponsor_ID='$sponsorID'
                                        AND pr.Registration_Date_AND_Time BETWEEN '$Date_From' AND '$Date_To'
                                        AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)      
									 ") or die(mysqli_error($conn));
        
        
    }
}



$num_OPD = mysqli_num_rows($select_All);
if ($num_OPD > 0) {
    while ($pdata = mysqli_fetch_array($select_All)) {
        $Check_In_Date = $pdata['Check_In_Date'];
        $Date_Of_Birth = $pdata['Date_Of_Birth'];
        $Gender = $pdata['Gender'];
        $date1 = new DateTime($Check_In_Date);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $Years = $diff->y;
        $Months = $diff->m;
        $Days = $diff->d;


        //Chini Ya Mwezi mmoja
        if ($Years == 0 && $Months == 0 && strtolower($Gender) == 'male') {
            $Total_OPD_Below_1_Month_Male++;
            $Grand_Total_OPD_Below_1_Month++;
            $Total_OPD_Male++;
        }

        if ($Years == 0 && $Months == 0 && strtolower($Gender) == 'female') {
            $Total_OPD_Below_1_Month_Female++;
            $Grand_Total_OPD_Below_1_Month++;
            $Total_OPD_Female++;
        }

        //Mwezi mmoja hadi Chini Ya Mwaka mmoja
        if ($Years == 0 && $Months >= 1 && strtolower($Gender) == 'male') {
            $Total_OPD_Between_1_Month_But_Below_1_Year_Male++;
            $Grand_Total_OPD_Between_1_Month_But_Below_1_Year++;
            $Total_OPD_Male++;
        }

        if ($Years == 0 && $Months >= 1 && strtolower($Gender) == 'female') {
            $Total_OPD_Between_1_Month_But_Below_1_Year_Female++;
            $Grand_Total_OPD_Between_1_Month_But_Below_1_Year++;
            $Total_OPD_Female++;
        }

        //Mwaka mmoja hadi Chini Ya Miaka Mitano
        if (($Years >= 1 && $Years < 5) && strtolower($Gender) == 'male') {
            $Total_OPD_Between_1_Year_But_Below_5_Year_Male++;
            $Grand_Total_OPD_Between_1_Year_But_Below_5_Year++;
            $Total_OPD_Male++;
        }

        if (($Years >= 1 && $Years < 5) && strtolower($Gender) == 'female') {
            $Total_OPD_Between_1_Year_But_Below_5_Year_Female++;
            $Grand_Total_OPD_Between_1_Year_But_Below_5_Year++;
            $Total_OPD_Female++;
        }

        //Miaka 5 hadi chini ya miaka 60
        if (($Years >= 5 && $Years < 60) && strtolower($Gender) == 'male') {
            $Total_OPD_Five_Years_Or_Below_Sixty_Years_Male++;
            $Grand_Total__OPDFive_Years_Or_Below_Sixty_Years++;
            $Total_OPD_Male++;
        }

        if (($Years >= 5 && $Years < 60) && strtolower($Gender) == 'female') {
            $Total_OPD_Five_Years_Or_Below_Sixty_Years_Female++;
            $Grand_Total__OPDFive_Years_Or_Below_Sixty_Years++;
            $Total_OPD_Female++;
        }

        //Miaka 60 na kuendelea
        if (($Years >= 60) && strtolower($Gender) == 'male') {
            $Total_OPD_60_Years_And_Above_Male++;
            $Grand_Total_OPD_60_Years_And_Above++;
            $Total_OPD_Male++;
        }

        if (($Years >= 60) && strtolower($Gender) == 'female') {
            $Total_OPD_60_Years_And_Above_Female++;
            $Grand_Total_OPD_60_Years_And_Above++;
            $Total_OPD_Female++;
        }
    }
}
//display mahudhurio ya OPD
?>
    <tr>
        <td width="5%" style="text-align: center;"><span style="font-size: x-small;">1</span></td>
        <td><span style="font-size:large;">Mahuzurio wapya</span></td>
        <td width="13%" style="text-align: center;">
            <table width="100%">
                <tr>
                    <td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_OPD_Below_1_Month_Male; ?></span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_OPD_Below_1_Month_Female; ?></span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Grand_Total_OPD_Below_1_Month; ?></span></td>
                </tr>
            </table>
        </td>
        <td width="13%" style="text-align: center;">
            <table width="100%">
                <tr>
                    <td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_OPD_Between_1_Month_But_Below_1_Year_Male; ?></span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_OPD_Between_1_Month_But_Below_1_Year_Female; ?></span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Grand_Total_OPD_Between_1_Month_But_Below_1_Year; ?></span></td>
                </tr>
            </table>
        </td>
        <td width="13%" style="text-align: center;">
            <table width="100%">
                <tr>
                    <td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_OPD_Between_1_Year_But_Below_5_Year_Male; ?></span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_OPD_Between_1_Year_But_Below_5_Year_Female; ?></span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Grand_Total_OPD_Between_1_Year_But_Below_5_Year; ?></span></td>
                </tr>
            </table>
        </td>
        <td width="13%" style="text-align: center;">
            <table width="100%">
                <tr>
                    <td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_OPD_Five_Years_Or_Below_Sixty_Years_Male; ?></span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_OPD_Five_Years_Or_Below_Sixty_Years_Female; ?></span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Grand_Total__OPDFive_Years_Or_Below_Sixty_Years; ?></span></td>
                </tr>
            </table>
        </td>
        <td width="13%" style="text-align: center;">
            <table width="100%">
                <tr>
                    <td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_OPD_60_Years_And_Above_Male; ?></span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_OPD_60_Years_And_Above_Female; ?></span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Grand_Total_OPD_60_Years_And_Above; ?></span></td>
                </tr>
            </table>
        </td>
        <td width="13%" style="text-align: center;">
            <table width="100%">
                <tr>
                    <td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_OPD_Male; ?></span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_OPD_Female; ?></span></td>
                    <td style="text-align: center;"><span style="font-size: x-small;"><?php echo ($Total_OPD_Male + $Total_OPD_Female); ?></span></td>
                </tr>
            </table>
        </td>
    </tr>
</table></center>
</center>
</fieldset>
<table width="100%">
    <tr>
        <td style="text-align:right;width:30%;">
            <a href="printdemoPatientsVisitedPreviousDaysDetailsFilter.php?sponsorID=<?php echo $sponsorID ?>&branchID=<?php echo $branchID ?>&Region_ID=<?php echo $regionID ?>&District_ID=<?php echo $districtID ?>&ageFrom=<?php echo $ageFrom ?>&ageTo=<?php echo $ageTo ?>&Date_From=<?php echo $Date_From ?>&Date_To=<?php echo $Date_To ?>" target="_blank">
                <input type='submit' name='graph' id='Graph' class='art-button-green' value='PRINT'>
            </a>
        </td>
    </tr>
</table>   
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>