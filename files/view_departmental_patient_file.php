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
    <a href='./patientfileform.php?Registration_ID=<?php echo $_GET['Registration_ID']?>DoctorsPage=DoctorsThisPage' class='art-button-green'>
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



<!--check in process-->
<?php
	if(isset($_POST['submittedCheckInPatientForm'])){
            if($Registration_ID != ''){
                $Visit_Date = mysqli_real_escape_string($conn,$_POST['Visit_Date']);
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
                
                //check if patient already checked in today
                //(if checked in ignore the process if not checked in continue the process)
                
                $check_Patient = mysqli_query($conn,"select Registration_ID from tbl_check_in where Registration_ID = '$Registration_ID' and Visit_Date = '$Visit_Date' and branch_id = '$Branch_ID'") or die(mysqli_error($conn));
                $no = mysqli_num_rows($check_Patient);
                if($no > 0){
                    echo '<script>
                            alert("This Patient Already Checked In ('.$Visit_Date.')");
                            document.location = "./visitorform.php?Visitor=VisitorThisPage";
                        </script>';
                }else{
                    $Check_In_Process = "
                                insert into tbl_check_in(
                                    Registration_ID,Check_In_Date_And_Time,Visit_Date,
                                    Employee_ID,Branch_ID,Check_In_Date)                                
                                values(
                                    '$Registration_ID',(select now()),'$Visit_Date',
                                    '$Employee_ID','$Branch_ID',(select now())
                                )";
                                
                    if(!mysqli_query($conn,$Check_In_Process)){
                        die(mysqli_error($conn));
                    }
                    else {
                        if(strtolower($Guarantor_Name) != 'cash'){
                            echo '<script>
                                    alert("Check In Process Successful");
                                    document.location = "./patientbillingreception.php?Registration_ID='.$Registration_ID.'&NR=True&CreditPatientBilling=CreditPatientBillingThisPage";
                                    </script>';
                        }else{
                            echo '<script>
                                    alert("Check In Process Successful");
                                    document.location = "./visitorform.php?Visitor=VisitorThisPage";
                                    </script>';
                        }
                        
                        
                        
                        
                    }
                }
	}
        }
?>

<!--end of ceck in process-->

          
<fieldset>  
            <legend align=center>PATIENT FILE INFORMATION</legend>
        <center>
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">           
            <table width=100% style="border: 0">
                <tr> 
                    <td style="border: 1px #ccc solid;">
                        <table width=100%>
                            <tr>
                                <td style='text-align: right;border: 1px #ccc solid;'>Patient Name</td>
                                <td><input type='text' name='Patient_Name' id='Patient_Name' disabled='disabled' value='<?php echo $Patient_Name; ?>'></td>
                                <td style='text-align: right;border: 1px #ccc solid;'>Gender</td>
                                <td><input type='text' name='Gender' id='Gender' disabled='disabled' value='<?php echo $Gender; ?>'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right;border: 1px #ccc solid;'>Age</td>
                                <td><input type='text' name='Age' id='Age' disabled='disabled' value='<?php echo $age; ?>'></td>
                                <td style='text-align: right;border: 1px #ccc solid;'>Registration Number</td>
                                <td style='text-align: right;border: 1px #ccc solid;'><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td> 
                            </tr>
                            <tr>
                                <td style='text-align: right;border: 1px #ccc solid;'>Patient Number</td>
                                <td><input type='text' name='Patient_Number' disabled='disabled' id='Patient_Number' value='<?php echo $Registration_ID; ?>'></td>
                                <td style='text-align: right;border: 1px #ccc solid;'>Phone Number</td>
                                <td><input type='text' name='Phone_Number' disabled='disabled' id='Phone_Number' value='<?php echo $Phone_Number; ?>'></td> 
                            </tr>
                            <tr>
                                <td style='text-align: right;border: 1px #ccc solid;'>District</td>
                                <td><input type='text' name='District' id='District' disabled='disabled' value='<?php echo $District; ?>'></td>
                                <td style='text-align: right;border: 1px #ccc solid;'>Region</td>
                                <td><input type='text' name='Region' id='Region' disabled='disabled' value='<?php echo $Region; ?>'></td> 
                            </tr>
                            <tr>
                                <td style='text-align: right;border: 1px #ccc solid;'>Sponsor</td>
                                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                                <td style='text-align: right'>Email Address</td>
                                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Email_Address; ?>'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right;border: 1px #ccc solid;'>Emergence Contact Name</td>
                                <td><input type='text' name='Member_Number' disabled='disabled' id='Member_Number' value='<?php echo $Emergence_Contact_Name; ?>'></td>
                                <td style='text-align: right;border: 1px #ccc solid;'>Emergence Contact Number</td>
                                <td><input type='text' name='Ward' id='Ward' disabled='disabled' value='<?php echo $Emergence_Contact_Number; ?>'></td> 
                            </tr>
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
</fieldset><br/>

<fieldset> 
        <legend align=center>PATIENT FILE
	<?php
		
		if(strtolower($_GET['section']) == 'doctor room'){
		    echo "REVIEW";
		}else{
		    echo "FOR ".strtoupper($_GET['section'])." DEPARTMENT";
		}
	?></legend>
	    <iframe src="view_departmental_patient_file_Iframe.php?Registration_ID=<?php echo $Registration_ID?>&section=<?php echo $_GET['section']?>&Status_From=<?php echo $_GET['Status_From']?>" width="100%" height="200px"></iframe>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>