<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }     
?>

<?php
    if(isset($_SESSION['userinfo'])){  
?>
    <a href='visitorform.php?VisitorForm=VisitorFormThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } ?>

<br/><br/><br/>



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


 

 

<!--Getting employee name -->
<?php
    if(isset($_SESSION['userinfo']['Employee_Name'])){
	$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
	$Employee_Name = 'Unknown Employee';
    }
?>



 



<?php
//    select patient information
    if(isset($_GET['Registration_ID'])){ 
        $Registration_ID = $_GET['Registration_ID']; 
        $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,
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
                $Sponsor_ID = $row['Sponsor_ID'];
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
            $Sponsor_ID = '';
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
            $Sponsor_ID = '';
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




<!-- transaction details-->
<?php
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    if(isset($_GET['Payment_Code'])){
        $Payment_Code = $_GET['Payment_Code'];
    }else{
        $Payment_Code = '';
    }
    
    if(isset($_GET['Total'])){
        $Total = $_GET['Total'];
    }else{
        $Total = '0';
    }
?>


<fieldset>  
            <legend align=center><b>eHMS MOBILE PAYMENTS - PATIENT DETAILS</b></legend>
<!--            GET PATIENT DETAILS-->
            
        <center><table width = 60%>
	    <tr>
                <td style='text-align: right;'><b>Patient Name</b></td>
                <td><?php echo $Patient_Name; ?></td>
                <td style='text-align: right;'><b>Patient Number</b></td>
                <td><?php echo $Registration_ID; ?></td>
            </tr> 
	    <tr>
                <td style='text-align: right;'><b>Gender</b></td>
                <td><?php echo $Gender; ?></td>
                <td style='text-align: right;'><b>Sponsor</b></td>
                <td><?php echo $Guarantor_Name; ?></td>
            </tr> 
	    <tr>
                <td style='text-align: right;'><b>Age</b></td>
                <td><?php echo $age; ?></td>
                <td style='text-align: right;'><b>Phone Number</b></td>
                <td><?php echo $Phone_Number; ?></td>
            </tr> 
        </table>
        </center>
</fieldset><br/><br/><br/>


<fieldset>  
            <legend align=center><b>TRANSACTION DETAILS</b></legend>
<!--            GET PATIENT DETAILS-->
            
        <center><table width = 60%>
	    <tr>
                <td style='text-align: right;'><b><h4>Payment Code : </h4></b></td>
                <td><h4><?php echo $Payment_Code; ?></h4></td>
                <td style='text-align: right;'><b><h4>Total Amount Needed : </h4></b></td>
                <td><h4><?php echo number_format($Total); ?></h4></td>
            </tr>  
        </table>
            <br/><br/><br/>
			<form method="POST" >
				<?php 
					$thePhone1 = $Phone_Number;
					//$thePhone1 = '255754894777';
					$Ourmsg = "Ndugu ".$Patient_Name.", Namba yako ya malipo ni ".$Payment_Code.". Piga *150*45# Kulipia huduma kwa eHMS Mobile. Jumla ya gharama ni Tshs.".number_format($Total)."";
					
				?>
				<input type="hidden" name="Ourmessage" value="<?php echo $Ourmsg; ?>" />
				<input type="hidden" name="thePhone" value="<?php echo $thePhone1; ?>" />
				<input type='submit' name="sendMsg" value='SEND PAYMENT CODE TO PATIENT MOBILE' class='art-button-green' />
			</form>
        </center>
</fieldset><br/>
			<?php 
				if(isset($_POST['sendMsg'])){
					require_once('sms/sms.php');
					$theMsg = $_POST['Ourmessage'];
					$receiver = $_POST['thePhone'];
					
					$send = SendSMS($receiver, $theMsg);
					
					//$respond = substr($response, 0, 6);
					
					if($send){
						echo "<center> SMS not sent, try again later. </center>";
					} else {
						echo "<center>The SMS was Successfully sent to ".$Patient_Name.".</center>";
					}
					
				}
			?>

<?php
    include("./includes/footer.php");
?>