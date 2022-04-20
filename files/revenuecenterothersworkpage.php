<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='otherpaymentspatientlist.php?OtherPayments=OtherPaymentsThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>


<br/><br/>
<br/><br/>

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
    if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];
    }else{
	$Registration_ID = 0;
    }
    
    if($Registration_ID != 0){
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
<br/><br/>
<fieldset>  
    <legend align=center><b>REVENUE CENTER (OTHERS)</b></legend>
        <center>
	    <br/><br/>

	    <table width=100%>
		<tr>
		    <td style='text-align: right; width: 15%;'>Patient Name</td>
		    <td><input type='text' readonly='readonly' value='<?php echo $Patient_Name; ?>'></td>
		    <td style='text-align: right; width: 15%;'>Member Number</td>
		    <td><input type='text' readonly='readonly' value='<?php echo $Registration_ID; ?>'></td>
		    <td style='text-align: right; width: 15%;'>Age</td>
		    <td><input type='text' readonly='readonly' value='<?php echo $age; ?>'></td>
		</tr>
		<tr>
		    <td style='text-align: right; width: 15%;'>Gender</td>
		    <td><input type='text' readonly='readonly' value='<?php echo $Gender; ?>'></td>
		    <td style='text-align: right; width: 15%;'>Phone Number</td>
		    <td><input type='text' readonly='readonly' value='<?php echo $Phone_Number; ?>'></td>
		    <td style='text-align: right; width: 15%;'>Registered Date</td>
		    <td><input type='text' readonly='readonly' value='<?php echo $Registration_Date_And_Time; ?>'></td>
		</tr> 
	    </table>
	    <br/><br/>
	    <table width=100%>
                <tr>
                    <td style='text-align: right;'>
                        <a href='directcashothers.php?Registration_ID=<?php echo $Registration_ID; ?>&DirectCashOtherPayments=DirectCashOtherPaymentsThisPage' class='art-button-green'>
                            DIRECT CASH
                        </a>
                        <a href='normalpaymentsothers.php?Registration_ID=<?php echo $Registration_ID; ?>&DirectCashOtherPayments=DirectCashOtherPaymentsThisPage' class='art-button-green'> 
                            NORMAL PAYMENTS
                        </a>
                    </td> 
                </tr>
            </table>
        </center>
</fieldset><br/>

<?php
    include("./includes/footer.php");
?>