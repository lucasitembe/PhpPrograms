<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])){
	    if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes'){
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
        if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){ 
?>
    <a href='doctorsInandOutpatient.php' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>OPD WORKS</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){ ?>
                    <a href='doctorspageoutpatientwork.php?RevisitedPatient=RevisitedPatientThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Doctor's Works Page
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Doctor's Works Page
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
	    <?php if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){ ?>
                    <?php
			$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
			$permit=mysqli_query($conn,"SELECT * FROM tbl_employee WHERE Employee_ID = '$Employee_ID' ") or die(mysqli_error($conn));
			$row=mysqli_fetch_array($permit);
			$Employee_Type=$row['Employee_Type'];
			if($Employee_Type == 'Doctor' ){ ?>
			<tr>
			    <td style='text-align: center; height: 40px; width: 33%;'>
				<a href='individualdoctorsperformancesummary.php'>
				    <button style='width: 100%; height: 100%'>
					 My Work Performance Report
				    </button>
				</a>
			    </td>
			</tr>	
			<?php } ?>
			<?php }else{ ?>
			 <tr>
			    <td style='text-align: center; height: 40px; width: 33%;'>
				<button style='width: 100%; height: 100%' onclick="return access_Denied();">
				    My Work Performance Report
				</button>
			    </td>
			</tr>
			<?php } ?>
	    <!--Clinic Performance button-->
                    <?php if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){ ?>
                    <?php
			$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
			$permit=mysqli_query($conn,"SELECT * FROM tbl_employee WHERE Employee_ID = '$Employee_ID' ") or die(mysqli_error($conn));
			$row=mysqli_fetch_array($permit);
			$Employee_Type=$row['Employee_Type'];
			if($Employee_Type == 'Doctor' ){ ?>
			    <tr>
				<td style='text-align: center; height: 40px; width: 33%;'>
				    <a href='myclinicperformance.php?MyClinicalPerformanceThisPage=ThisPage'>
					<button style='width: 100%; height: 100%'>
					     My Clinic Performance Report
					</button>
				    </a>
				</td>
			    </tr>
			<?php } ?>
                    <?php }else{ ?>
			<tr>
				<td style='text-align: center; height: 40px; width: 33%;'>
				    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
					My Clinic Performance Report
				    </button>
			        </td>
			</tr>
                    <?php } ?>
			
				<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){ ?>
                    <a href='appointments_list.php?doc=<?php echo $Employee_ID;  ?>'>
                        <button style='width: 100%; height: 100%'>
                            My Appointments
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            My Appointments
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
			<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){ ?>
                    <a href='doctorspagetransfer.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Patient Transfer</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Patient Transfer</b>
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>	
                
        </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>reports