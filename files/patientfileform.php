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
    <a href='./patientfile.php?DoctorsPage=DoctorsThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>



<!-- new date function (Contain years, Months and days)--> 
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
?>
<!-- end of the function -->

<?php

//    select patient information to perform check in process
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID']; 
        $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,
                                Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,
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
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Region = $row['Region'];
                $District = $row['District'];
                $Ward = $row['Ward'];
                $Guarantor_Name = $row['Guarantor_Name'];
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
            /*$age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
	    if($age == 0){
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->m." Months";
	    }
	    if($age == 0){
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->d." Days";
	    }*/
	    
	     $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
		
	    /*}
	    if($age == 0){
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->d." Days";
	    }*/
	    
        }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Patient_Name = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
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
        }
    }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Patient_Name = ''; 
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
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
        }
?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

 
<br/><br/>



<!--end of ceck in process-->

          
<fieldset>  
            <legend align=center>PATIENT FILE INFORMATION</legend>
        <center>
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">           
            <table width=100% style="border: 0" >
                <tr> 
                    <td style="border: 0px #ccc solid;">
                        <table width=100% style="border: 0px;">
			    <tr><td colspan="4" style="border: 1px #ccc solid"><hr></td></tr>
                            <tr>
                                <td style='text-align: right;border: 1px #ccc solid;'><b>Patient Name</b></td>
                                <td style='text-align: right;border: 1px #ccc solid;'><input type='text' name='Patient_Name' id='Patient_Name' disabled='disabled' value='<?php echo $Patient_Name; ?>'></td>
                                <td style='text-align: right;border: 1px #ccc solid;'><b>Gender</b></td>
                                <td style='text-align: right;border: 1px #ccc solid;'><input type='text' name='Gender' id='Gender' disabled='disabled' value='<?php echo $Gender; ?>'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right;border: 1px #ccc solid;'><b>Age</b></td>
                                <td style='text-align: right;border: 1px #ccc solid;'><input type='text' name='Age' id='Age' disabled='disabled' value='<?php echo $age; ?>'></td>
                                <td style='text-align: right;border: 1px #ccc solid;'><b>Registration Number</b></td>
                                <td style='text-align: right;border: 1px #ccc solid;'><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td> 
                            </tr>
                            <tr>
                                <td style='text-align: right;border: 1px #ccc solid;'><b>Patient Number</b></td>
                                <td style='text-align: right;border: 1px #ccc solid;'><input type='text' name='Patient_Number' disabled='disabled' id='Patient_Number' value='<?php echo $Registration_ID; ?>'></td>
                                <td style='text-align: right;border: 1px #ccc solid;'><b>Phone Number</b></td>
                                <td style='text-align: right;border: 1px #ccc solid;'><input type='text' name='Phone_Number' disabled='disabled' id='Phone_Number' value='<?php echo $Phone_Number; ?>'></td> 
                            </tr>
                            <tr>
                                <td style='text-align: right;border: 1px #ccc solid;'><b>District</b></td>
                                <td style='text-align: right;border: 1px #ccc solid;'><input type='text' name='District' id='District' disabled='disabled' value='<?php echo $District; ?>'></td>
                                <td style='text-align: right;border: 1px #ccc solid;'><b>Region</b></td>
                                <td style='text-align: right;border: 1px #ccc solid;'><input type='text' name='Region' id='Region' disabled='disabled' value='<?php echo $Region; ?>'></td> 
                            </tr>
                            <tr>
                                <td style='text-align: right;border: 1px #ccc solid;'><b>Sponsor</b></td>
                                <td style='text-align: right;border: 1px #ccc solid;'><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                                <td style='text-align: right'><b>Email Address</b></td>
                                <td style='text-align: right;border: 1px #ccc solid;'><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Email_Address; ?>'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right;border: 1px #ccc solid;'><b>Emergence Contact Name</b></td>
                                <td style='text-align: right;border: 1px #ccc solid;'><input type='text' name='Member_Number' disabled='disabled' id='Member_Number' value='<?php echo $Emergence_Contact_Name; ?>'></td>
                                <td style='text-align: right;border: 1px #ccc solid;'><b>Emergence Contact Number</b></td>
                                <td style='text-align: right;border: 1px #ccc solid;'><input type='text' name='Ward' id='Ward' disabled='disabled' value='<?php echo $Emergence_Contact_Number; ?>'></td> 
                            </tr>
			    <tr><td colspan="4" style="border: 0px;"><hr></td></tr>
                        </table>
                    </td>
                    <td style='text-align: right;border: 1px #ccc solid;'>
                        <table width=100% style="border: 0;">
                            <tr>
                                <td style='text-align: right;border: 1px #ccc solid;'>
                                    <img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' width=90% height=90%>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
</form>
        </center>
</fieldset>

<fieldset> 
        <legend align=center></legend>
	    <center>
		<table width=60%>
			<tr><td colspan="4" style="border: 1px #ccc solid;"><hr></td></tr>
			    <tr>
				<td style='text-align: left;border: 1px #ccc solid;'><b>SN</b></td>
				<td style='text-align: left;border: 1px #ccc solid;'><b>Department</b></td>
                                <td style='text-align: left;border: 1px #ccc solid;'><b>Last Visit</b></td>
                                <td style='text-align: left;border: 1px #ccc solid;'><b>Action</b></td>
			    </tr>
			    <tr><td colspan="4" style="border: 1px #ccc solid;"><hr></td></tr>
			    <?php
				//run the query to select data that only associate to the selected data
				$select_patient_dept=mysqli_query($conn,"SELECT 'payment' as Status_From,  ppl.Check_In_Type, MAX(ppl.Transaction_Date_And_Time) AS Last_Visit FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp,tbl_patient_registration pr
								WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID
								AND pp.Registration_ID=pr.Registration_ID
								AND pr.Registration_ID='$Registration_ID' GROUP BY ppl.Check_In_Type ORDER BY Check_In_Type ASC

								 ") OR die();
				if(mysqli_num_rows($select_patient_dept) == 0){ ?>
				    <tr><td colspan='4' style='text-align: center;'><b>
				    <script>
					alert("No file associated with this patient.Click Ok to go back.");
					location.href="patientfile.php";
				    </script>
				    </b></td></tr>
				<?php }else{
					$sn=1;
					$Last_Visit=date("Y-m-d");
				    while($select_patient_dept_row=mysqli_fetch_array($select_patient_dept)){
					//return the rows
					$Check_In_Type=$select_patient_dept_row['Check_In_Type'];
					$Status_From=$select_patient_dept_row['Status_From'];
					$Last_Visit=$select_patient_dept_row['Last_Visit'];
					
					echo "<tr>
						<td style='text-align: left;border: 1px #ccc solid;width: 3%'>$sn</td>
						<td style='text-align: left;border: 1px #ccc solid;width:30%'>";
						 ?><?php
						    if($Check_In_Type == "Doctor Room"){
							echo "Doctor's Review";
						    }else{
							echo $Check_In_Type;
						    }
						 
						echo "</td>
						<td style='text-align: left;border: 1px #ccc solid; width: 30%'>".date('j F, Y',strtotime($Last_Visit))."</td>
						<td style='text-align: left;border: 1px #ccc solid;'><a href='view_departmental_patient_file.php?Registration_ID=$Registration_ID&Status_From=$Status_From&section=$Check_In_Type&ViewDepartimentalPatientFile=$Check_In_Type'PatientFileThisPage' class='art-button-green' style='width: 100px;'>View</a></td>
					    </tr>";
					    
					    $sn++;
				}
				}
			    ?>
			    <tr><td colspan="4" style="border: 0px"><hr></td></tr>
			    <br>
		</table>
	    </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>