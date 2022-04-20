<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['eRAM_Works'])){
	    if($_SESSION['userinfo']['eRAM_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
?>
<!--START HERE-->
<?php
//get the current date
		$Today_Date = mysqli_query($conn,"select now() as today");
		while($row = mysqli_fetch_array($Today_Date)){
		    $original_Date = $row['today'];
		    $new_Date = date("Y-m-d", strtotime($original_Date));
		    $Today = $new_Date;
		}
//    select patient information
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
        $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID
                                      from tbl_patient_registration pr, tbl_sponsor sp
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);
        if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Title = $row['Title'];
                $Patient_Name = $row['Patient_Name'];
                $Sponsor_ID = $row['Sponsor_ID'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Region = $row['Region'];
                $District = $row['District'];
                $Ward = $row['Ward'];
                $Guarantor_Name = $row['Guarantor_Name'];
		$Claim_Number_Status = $row['Claim_Number_Status'];
                $Member_Number = $row['Member_Number'];
                $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
                $Phone_Number = $row['Phone_Number'];
                $Email_Address = $row['Email_Address'];
                $Occupation = $row['Occupation'];
                $Employee_Vote_Number = $row['Employee_Vote_Number'];
                $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
                $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
                $Company = $row['Company'];
                $Employee_ID = $row['Employee_ID'];
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
               // echo $Ward."  ".$District."  ".$Ward; exit;
            }
	    
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ".$diff->m." Months, ".$diff->d." Days, ".$diff->h." Hours";
        }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Patient_Name = '';
            $Sponsor_ID = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
            $Claim_Number_Status = '';
	    $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = '';
	    $age =0;
        }
    }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Sponsor_ID = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
	    $Claim_Number_Status = '';
            $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = '';
	    $age =0;
        }
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['eRAM_Works'] == 'yes'){
?>
    <!--<a href='doctorpatientlistfile.php?<?php if($Registration_ID!=''){echo "Registration_ID=$Registration_ID&"; } ?><?php
    if(isset($_GET['Patient_Payment_ID'])){
	echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
	} ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
        PATIENTS FILE LIST
    </a>-->
    <a href='eramworksattachmentpage.php?<?php if($Registration_ID!=''){echo "Registration_ID=$Registration_ID&"; } ?>Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<br/><br/>
<!-- get employee id-->
<?php
    if(isset($_SESSION['userinfo']['Employee_ID'])){
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }
?>
<!-- get id, date, Billing Type,Folio number and type of chech in -->

<!--Getting employee name -->
<?php
    if(isset($_SESSION['userinfo']['Employee_Name'])){
	$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
	$Employee_Name = 'Unknown Employee';
    }
?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<fieldset>
        <center>
	    <b>eRAM WORKS</b>
            <p>Patient Attachments</p>
	    <p><?php echo $Patient_Name.", ".$Gender.", ".$Guarantor_Name.", (".$age.")";?></p>
        </center>
</fieldset>


<fieldset>
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
    <form action='#' method='post' enctype="multipart/form-data">
        <table width='100%'>
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
                            Nature Of The File :
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
    </center>
</fieldset>


<!--END HERE-->
<?php
    include("./includes/footer.php");
?>