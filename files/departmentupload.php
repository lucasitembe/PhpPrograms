<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo'])){
	    if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){
	    }
	    elseif($_SESSION['userinfo']['Radiology_Works'] == 'yes'){
	    }
	    else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_GET['section'])){
	$section = $_GET['section'];
    }
    if($section=='Admission'){
        echo "<a href='admissionworkspage.php?section=".$section."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            ADMISSION MAIN WORKPAGE
        </a>";
    }
?>
<?php
if(isset($_SESSION['userinfo'])){ 
        $Registration_ID = $_GET['Registration_ID'];
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	$section= $_GET['section'];
?> 
    
    
    <?php if($section == 'Admission'){ ?>
	<a href='departmentworkspage.php?section=<?php echo $section;?>&Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
	    PATIENT CARE
	</a>
    <?php }else{ ?>
	<a href='departmentworkspage.php?section=<?php echo $section;?>&Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
	    WORKPAGE
	</a>
    <?php } ?>
    
    
    <?php
    }
    ?>
    <br/><br/>
    <center>
    <?php
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $Select_Patients = "select Patient_Name from tbl_patient_registration where registration_id = '$Registration_ID'";
        $result = mysqli_query($conn,$Select_Patients);
        $row = mysqli_fetch_array($result);
    ?>
    <?php
	if(isset($_POST['department_upload_submitted'])){
	    $checkin_type = $_POST['department'];
	    $description = $_POST['description'];
	    $section = $_POST['section'];
	    $Registration_ID = $_POST['Registration_ID'];
	    $employee_id = $_SESSION['userinfo']['Employee_ID'];
	    
	    $date=date('Y-m-d H:i:s');
	    $date_=date('Y-m-d_H_i_s_');
	    $file_name = "".$Registration_ID."_".$date_.$_FILES['attachment']['name'];
	    
	    $insert_query = "INSERT INTO tbl_attachment
					    (Registration_ID,Employee_ID,Description,Check_In_Type,Attachment_Url,Attachment_Date)
					     VALUES('$Registration_ID','$employee_id','$description','$checkin_type','$file_name','$date')";
	    $file_name = "./patient_attachments/".$file_name;
		    if(!move_uploaded_file("".$_FILES['attachment']['tmp_name'],$file_name)){
			    $status='not sent';
			}
		    else{
			if(mysqli_query($conn,$insert_query)){
			    $status='sent';
			}else{
			    $status='not sent';
			}
		    }
	}
    ?>
	<br><br>
	<br><br>
	<fieldset>
			<legend align=right><b>UPLOAD PATIENT FILE</b></legend>
    <form action='#' method='post' enctype="multipart/form-data">
        <table width='100%'>
            <tr>
                <td>
                    <center>
                        <b>Patient Name : <?php echo $row['Patient_Name']; ?></b>
                    </center>
                </td>
            </tr>
			<tr><td><br></td></tr>
            <tr>
                <td>
                <center>
                <table>
                    <tr>
                        <td>
                            Description :
                        </td>
                        <td>
                            <input type='text' id='description' name='description'/>
			    <input type='hidden' id='Registration_ID' name='Registration_ID' value='<?php echo $Registration_ID;?>'/>
                            <input type='hidden' id='Patient_Payment_ID' name='Patient_Payment_ID' value='<?php echo $Patient_Payment_ID;?>'/>
			    <input type='hidden' id='section' name='section' value='<?php echo $section;?>'/>
			    <input type='hidden' id='department_upload_submitted' name='department_upload_submitted' value='true'>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Checkin Type :
                        </td>
                        <td>
                            <select id='department' name='department' required='required'>
                                <option selected='selected'>
                                    <option>Radiology</option>
                                    <option>Dialysis</option>
                                    <option>Physiotherapy</option>
                                    <option>Optical</option>
                                    <option>Doctor Room</option>
                                    <option>Dressing</option>
                                    <option>Matenity</option>
                                    <option>Cecap</option>
                                    <option>Laboratory</option>
                                    <option>Theater</option>
                                    <option>Dental</option>
                                    <option>Ear</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Attachment :
                        </td>
                        <td>
                            <input type='file' id='attachment' name='attachment'/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2>
                            <input type='submit' id='send' name='send' value='SEND' class='art-button-green'/>
                            <input type='reset' class='art-button-green' value='CLEAR'/>
                        </td>
                    </tr>
		    <tr>
			<td colspan=2>
			    <?php
			    if(isset($status)){
				if($status=='sent'){
				?>
				<sub style='color: green'>File Attached</sub>
				<?php
				}
			    }else{
				?>
				<sub style='color: red'>File Not Attached</sub>
				<?php
			    }
			    ?>
			</td>
		    </tr>
                </table>   
                    
                </center>    
                    
                </td>
            </tr>
        </table>
    </form>
	
	</fieldset>
    </center>
<?php
    include("./includes/footer.php");
?>