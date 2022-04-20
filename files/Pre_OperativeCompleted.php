<?php
    include("./includes/header.php");
    include("./includes/connection.php");
     $temp=1;
	// $temp=++;
	 
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Admission_Works'])){
	    if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
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
		if(isset($_GET['From'])){
		?>
		<a href='clinicalnotes.php?<?php if(isset($_GET['Registration_ID'])){
		echo "Registration_ID=".$_GET['Registration_ID']."&";
		} ?><?php
		if(isset($_GET['Patient_Payment_ID'])){
		echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
		}
		if(isset($_GET['Patient_Payment_Item_List_ID'])){
		echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
		} ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
			BACK 
		</a>
		
	<?php

}
else{ ?>
<a href='searchPatients.php' class='art-button-green'>
         BACK
    </a>
	<?php
}

?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ 
		}
		}
?>
    
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ 
?>
    
<?php  } } ?>


	
	<!--new date function (Contain years, Months and days)--> 
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }

// end of the function -->


 if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            }else{
                $Employee_ID = 0;
            }
        }


		if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Employee_Name'])){
                $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
            }else{
                $Employee_Name = 'Unknown';
            }
        }

?>


<?php

//    select patient information to perform check in process
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID']; 
        $select_Patient = mysqli_query($conn,"select Registration_ID,
                                    Old_Registration_Number, Patient_Name,Title,
                                    Date_Of_Birth,Gender,pr.Region,pr.District,pr.Ward,pr.Sponsor_ID,Member_Number,Member_Card_Expire_Date,
									pr.Phone_Number,Email_Address,Occupation,Employee_Vote_Number,Emergence_Contact_Name,
									Emergence_Contact_Number,Company,Employee_ID,Registration_Date_And_Time,Patient_Picture,Guarantor_Name
                                      from tbl_patient_registration pr, tbl_sponsor sp
                                        
										
										where pr.Sponsor_ID = sp.Sponsor_ID  and
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);
        
        if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Patient_Name = $row['Patient_Name'];
                $Title = $row['Title'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
		        $Region = $row['Region'];
                $District = $row['District'];
                $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
                $Ward = $row['Ward'];
                $Sponsor_ID = $row['Sponsor_ID'];
                $Member_Number = $row['Member_Number'];
		        $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
                $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
                $Company = $row['Company'];
                $Employee_ID = $row['Employee_ID'];
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
                $Patient_Picture = $row['Patient_Picture'];
		        $Employee_Vote_Number = $row['Employee_Vote_Number'];
                $Occupation = $row['Occupation'];
                $Email_Address = $row['Email_Address'];
		        $Guarantor_Name = $row['Guarantor_Name'];
				 }
	 
	  $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
	 
	     
        }else{
                $Registration_ID = '';
                $Old_Registration_Number = '';
                $Patient_Name = '';
                $Title = '';
                $Date_Of_Birth = '';
                $Gender = '';
	            $Region = '';
                $District = '';
                $Member_Card_Expire_Date = '';
                $Ward = '';
                $Sponsor_ID = '';
                $Member_Number = '';
		        $Emergence_Contact_Name = '';
                $Emergence_Contact_Number = '';
                $Company = '';
                $Employee_ID = '';
                $Registration_Date_And_Time = '';
                $Patient_Picture = '';
		        $Employee_Vote_Number = '';
                $Occupation = '';
                $Email_Address='';
                $Guarantor_Name=''; 
             			
        }
    }else{
                $Registration_ID = '';
                $Old_Registration_Number = '';
                $Patient_Name = '';
                $Title = '';
                $Date_Of_Birth = '';
                $Gender = '';
		        $Region = '';
                $District = '';
                $Member_Card_Expire_Date = '';
                $Ward = '';
                $Sponsor_ID = '';
                $Member_Number = '';
		        $Emergence_Contact_Name = '';
                $Emergence_Contact_Number = '';
                $Company = '';
                $Employee_ID = '';
                $Registration_Date_And_Time = '';
                $Patient_Picture = '';
		        $Employee_Vote_Number = '';
                $Occupation = '';
                $Email_Address='';
		        $Guarantor_Name='';
				
        }
	
?>

<?php

//    select patient status information to perform check in process
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID']; 
        $select_Task = mysqli_query($conn,"select chk.Pre_Operative_ID, Registration_ID,Theatre_Time,
		                            Special_Information,Task_Name,Task_Value,Remark 
		               from tbl_pre_operative_checklist chk,
		                             tbl_pre_operative_checklist_items itm
 
										where chk.Pre_Operative_ID = itm.Pre_Operative_ID  and
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Task);
        if($no>0){
            while($row = mysqli_fetch_array($select_Task)){
                $Registration_ID = $row['Registration_ID'];
				$Theatre_Time=$row['Theatre_Time'];
				$Special_Information=$row['Special_Information'];
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
                $Task_Name=$row['Task_Name'];
                $Pre_Operative_ID = $row['Pre_Operative_ID'];	
                
               }
	
	    
	   
	    
        }else{
		        $Registration_ID = '';
				$Theatre_Time='';
				$Special_Information='';
				$Task_Value='';
				$Remark= '';
                $Task_Name='';
				$Pre_Operative_ID = 0;
        }
    }else{
				$Registration_ID = '';
				$Theatre_Time='';
				$Special_Information='';
				$Task_Value='';
				$Remark= '';
                $Task_Name='';
				$Pre_Operative_ID = 0;
        }
	
?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

 
<br/>
<br/>


<?php
echo '<center>';

echo "<b>".ucwords(strtolower($Patient_Name))."</b>";
				echo " | ";
				echo "<b>".$Gender."</b>";
				echo " | ";
				echo $age;
				echo " | ";
				echo "<b>".$Guarantor_Name."</b>";

echo '</center>';
?>

<fieldset style='overflow-y: scroll; height:450px;'>
    <legend align=right><b>PRE OPERATIVE CHECKLIST</b></legend>
 <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <center>
    <table style="text-align:center; width:100%">
    
     <tr>
	<td style="text-align:center; width: 5%;" ><b>SN</b></td>
	<td style="text-align:center; width: 30%;" ><b>TASK</b></td>
	<td style="text-align:center; width: 15%;" ><b>REMARKS</b></td>
	<td style="text-align:center; width: 5%;" ><b>SN</b></td>
	<td style="text-align:center; width: 30%;" ><b>TASK</b></td>
	<td style="text-align:center; width: 15;" ><b>REMARKS</b></td>
    </tr>
                      
		      
		      
     <tr>
	 <td style="text-align:right;">1</td>
	 
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Patient identified name' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkbox1' name='checkbox1' <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Patient identified name?</td>
	<td><input type='text' id='remark1' name='remark1' value='<?php echo $Remark; ?>'></td>
		
        
	<td style="text-align:right;">14</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Identification bands present and correct' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkbox14' name='checkbox14'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Identification bands present and correct?</td>
	<td><input type='text' id='remark14' name='remark14' value='<?php echo $Remark; ?>'></td>
	

     </tr>
               
    
    
    <tr>
	<td style="text-align:right;">2</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Urine passed before promed action' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkbox2' name='checkbox2'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Urine passed before promed action?</td>
	<td><input type='text' name='remark2' id='remark2' value='<?php echo $Remark; ?>'></td>

	
	
	
	<td style="text-align:right;">15</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Loose teeth,crowns, and bridges' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	
	<td><input type='checkbox' id='checkbox15' name='checkbox15'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Loose teeth,crowns, and bridges?</td>
    <td><input type='text' name='remark15' id='remark15' value='<?php echo $Remark ?>'></td>
    </tr>
                
    
    
    <tr>
	
	
	<td style="text-align:right;">3</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Dentures removed' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkbox3' name='checkbox3' value='yes'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> >Dentures removed?</td>
	<td><input type='text' id='remark3' name='remark3' value='<?php echo $Remark ?>'></td>
	
	<td style="text-align:right;">16</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Hearing adis removed' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkbox16' name='checkbox16'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Hearing adis removed?</td>
    <td><input type='text' name='remark16' id='remark16' value='<?php echo $Remark?>'></td>
    </tr>
                
    <tr>
	<td style="text-align:right;">4</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Contact lenses removed' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkbox4' name='checkbox4'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Contact lenses removed?</td>
	<td><input type='text' id='remark4' name='remark4' value='<?php echo $Remark ?>'</td>
	
	<td style="text-align:right;">17</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Pre - operative skin preparation' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkbox17' name='checkbox17'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?>value='yes'>Pre - operative skin preparation?</td>
    <td><input type='text' id='remark17' name='remark17' value='<?php echo $Remark?>'></td>
    </tr>
                
   
   
    <tr>
	<td style="text-align:right;">5</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Jowerly removed and rings tapped' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkbox5' name='checkbox5'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Jowerly removed and rings tapped?</td>
    <td><input type='text' id='remark5' name='remark5' value='<?php echo $Remark ?>'</td>
	
	<td style="text-align:right;">18</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Valuable securely stored' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	
	<td><input type='checkbox' id='checkbox18' name='checkbox18'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Valuable securely stored?</td>
    <td><input type='text' id='remark18' name='remark18' value='<?php echo $Remark?>'</td>
    </tr>
                
	
	<tr>
	<td style="text-align:right;">6</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Cosmetic and Clothing Removed' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkbox6' name='checkbox6'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Cosmetic and Clothing Removed?</td>
	<td><input type='text' id='remark6' name='remark6' value='<?php echo $Remark?>' ></td>
	
	<td style="text-align:right;">19</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Theatre gowns and pants wom' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkbox19' name='checkbox19'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Theatre gowns and pants wom?</td>
    <td><input type='text' id='remark19' name='remark19' value='<?php echo $Remark?>'></td>
	</tr>
     <tr>
	<td style="text-align:right;">7</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Consent form signed' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkbox7' name='checkbox7'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Consent form signed?</td>
	<td><input type='text' id='remark7' name='remark7' value='<?php echo $Remark?>'></td>
	
	<td style="text-align:right;">20</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Care patient case notes and other relevant chart sheet present' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkbox20' name='checkbox20'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?>value='yes'>Care patient case notes and other relevant chart sheet present?</td>
    <td><input type='text' id='remark20' name='remark20' value='<?php echo $Remark?>'></td>
	</tr>
     <tr>
		<td style="text-align:right;">8</td>
		<?php
			$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
									where Task_Name='Is enema or laxative given' and 
										Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
				while($row = mysqli_fetch_array($data)){
					$Task_Value=$row['Task_Value'];
					$Remark= $row['Remark'];
				}
		?>
		<td ><input type='checkbox' id='checkbox8' name='checkbox8'   <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Is enema or laxative given?</td>
		<td><input type='text' id='remark8' name='remark8' value='<?php echo $Remark?>'></td>
		 
		<td style="text-align:right;">21</td>
		<?php
			$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
									where Task_Name='Oral hygiene given' and 
										Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
				while($row = mysqli_fetch_array($data)){
					$Task_Value=$row['Task_Value'];
					$Remark= $row['Remark'];
				}
		?>
		<td><input type='checkbox' id='checkbox21' name='checkbox21'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Oral hygiene given?</td>
		<td><input type='text' id='remark21' name='remark21' value='<?php echo $Remark?>'></td>
	</tr>
    <tr>
	<td style="text-align:right;">9</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Are other prosthesis (if any) removed' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkbox9' name='checkbox9'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Are other prosthesis (if any) removed?</td>
	<td><input  id='remark9' name='remark9' type='text' value='<?php echo $Remark?>'></td>
     
	<td style="text-align:right;">22</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Is catheter is situ' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkbox22' name='checkbox22'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Is catheter is situ?</td>
    <td><input type='text' id='remark22' name='remark22' value='<?php echo $Remark?>'></td>
	</tr>
	<tr>
	<td style="text-align:right;">10</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Is catheter is situ' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td ><input type='checkbox' id='checkbox10' name='checkbox10'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Are special order carried out, as Nasogastric tube?</td>
	<td><input type='text' id='remark10' name='remark10' value='<?php echo $Remark?>'></td>
     
	<td style="text-align:right;">23</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Is catheter is situ' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkbox23' name='checkbox23'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Is patient having an i.v  catheter cannula(as 16,18)?</td>
    <td><input type='text' id='remark23' name='remark23' value='<?php echo $Remark?>'></td>
	</tr>
    <tr>
	<td style="text-align:right;">11</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Operative site marked' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td ><input type='checkbox' id='checkbox11' name='checkbox11'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?>  value='yes'>Operative site marked?</td>
	<td><input type='text' id='remark11' name='remark11' value='<?php echo $Remark?>'></td>
     
	<td style="text-align:right;">24</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Check list complete' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkbox24' name='checkbox24'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Check list complete?</td>
    <td><input type='text' id='remark24' name='remark24' value='<?php echo $Remark?>'></td>
	</tr>
                
		
		
    <tr>
	<td style="text-align:right;">12</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Radiographs accompanying patient' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkbox12' name='checkbox12'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Radiographs accompanying patient?</td>
	<td><input type='text' id='remark12' name='remark12' value='<?php echo $Remark?>'></td>
	
	<td style="text-align:right;">25</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Test for VDRL' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkbox25' name='checkbox25'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Test for VDRL?</td>
	<td><input type='text' id='remark25' name='remark25' value='<?php echo $Remark?>'></td>
    </tr>
	
	
	<tr>
	<td style="text-align:right;">13</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Test for HIV' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkbox13' name='checkbox13'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Test for HIV?</td>
	<td><input type='text' id='remark13' name='remark13' value='<?php echo $Remark?>'></td>
	
	<td style="text-align:right;">26</td>
	<?php
		$data = mysqli_query($conn,"select * from tbl_pre_operative_checklist_items 
								where Task_Name='Test for Hopatitis' and 
									Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($data)){
				$Task_Value=$row['Task_Value'];
				$Remark= $row['Remark'];
			}
	?>
	<td><input type='checkbox' id='checkboX26' name='checkbox26'  <?php if(strtolower($Task_Value) == 'yes' ) { echo "checked='checked'"; } ?> value='yes'>Test for Hopatitis?</td>
	<td><input type='text' id='remark26' name='remark26' value='<?php echo $Remark?>'></td>
    </tr>
    <tr >
    <td colspan='2' style='text-align:left;'>Special Information(if any):</td>
    <td colspan='4'>
	
	<textarea  name='Special_Information' id='Special_Information' value='<?php echo $Special_Information; ?>' ></textarea></td>
    </tr>
    
    <tr>
	<td colspan='2'>To Theatre At:</td>
	<td colspan='4'><input type='text' id='Theatre_Time' name='Theatre_Time' required='required' value='<?php echo $Theatre_Time; ?>'></td>
    </tr>
    
    <tr>
    <td colspan='2'>Ward Nurse Signature:</td>
	<td colspan='2'>
	<select name='Ward_Nurse' id='Ward_Nurse'  >
		<option selected='selected' value=''></option>
						<?php
					$data = mysqli_query($conn,"select * from tbl_employee where Employee_Type='Doctor'");
						while($row = mysqli_fetch_array($data)){
					?>
					<option value='<?php echo $row['Employee_Name'];?>'>
					<?php echo $row['Employee_Name']; ?>
					</option>
					<?php
						}
					?>
	</select>
	</td>
	
    <td ><input type='text' id='barcode' name='barcode' placeholder='Barcode Number'></td>
	<td><input type='button' value='VERIFY' class='art-button-green'></td>
    </tr>
	    
		
	<tr>
	<td colspan='2'>Theatre Nurse Signature:</td>
	<!--<td colspan='4'><input type='text' id='Theatre_Signature' name='Theatre_Signature' disabled='disabled' ></td>-->
    <td colspan='2'>
	<select name='Theatre_Nurse' id='Theatre_Nurse'  >
		<option selected='selected' value=''></option>
						<?php
					$data = mysqli_query($conn,"select * from tbl_employee where Employee_Type='Doctor'");
						while($row = mysqli_fetch_array($data)){
					?>
					<option value='<?php echo $row['Employee_Name'];?>'>
					<?php echo $row['Employee_Name']; ?>
					</option>
					<?php
						}
					?>
	</select>
		</td>
		
		<td ><input type='text' id='barcode' name='barcode'  placeholder='Barcode Number'></td>
		<td><input type='button' value='VERIFY' class='art-button-green'></td>
    </tr>
   </table>
   </fieldset>
	
 </form>
   </center>
   
   <!--viatls link -->
   <span style="text-align:right;" >
	<table width="100%" style="text-align:right;">
		<tr><td style="text-align:center;width:20%;">
		<a href="checkedform.php?Registration_ID=<?php echo $Registration_ID?>&Nurse_DateTime=<?php echo $_GET['Nurse_DateTime'];?>" class="art-button-green"  >View Vital Signs</a>
		</td></tr>
	</table>	
	</span>
 <?php
if(isset($_POST['submittedProActiveCheckList'])){
     $checkbox1 = 'no';
     $checkbox2 = 'no';
     $checkbox3 = 'no';
     $checkbox4 = 'no';
     $checkbox5 = 'no'; 
	 $checkbox6 = 'no';
     $checkbox7 = 'no';
     $checkbox8 = 'no';
     $checkbox9 = 'no';
     $checkbox10 = 'no'; 
	 $checkbox11 = 'no';
     $checkbox12 = 'no';
     $checkbox13 = 'no';
     $checkbox14 = 'no';
     $checkbox15 = 'no'; 
	 $checkbox16 = 'no';
     $checkbox17 = 'no';
     $checkbox18 = 'no'; 
	 $checkbox19 = 'no';
     $checkbox20 = 'no';
     $checkbox21 = 'no';
     $checkbox22 = 'no';
     $checkbox23 = 'no'; 
	 $checkbox24 = 'no';
     $checkbox25 = 'no'; 
	 $checkbox26 = 'no';
   
	 
	 
	 if(isset($_POST['checkbox1'])) { $checkbox1= 'yes'; }
	 if(isset($_POST['checkbox2'])) { $checkbox2 = 'yes'; }
	 if(isset($_POST['checkbox3'])) { $checkbox3 = 'yes'; }
	 if(isset($_POST['checkbox4'])) { $checkbox4 = 'yes'; }
	 if(isset($_POST['checkbox5'])) { $checkbox5 = 'yes'; } 
	  if(isset($_POST['checkbox6'])) { $checkbox6= 'yes'; }
	 if(isset($_POST['checkbox7'])) { $checkbox7 = 'yes'; }
	 if(isset($_POST['checkbox8'])) { $checkbox8 = 'yes'; }
	 if(isset($_POST['checkbox9'])) { $checkbox9 = 'yes'; }
	 if(isset($_POST['checkbox10'])) { $checkbox10 = 'yes'; } 
	 if(isset($_POST['checkbox11'])) { $checkbox11= 'yes'; }
	 if(isset($_POST['checkbox12'])) { $checkbox12 = 'yes'; }
	 if(isset($_POST['checkbox13'])) { $checkbox13 = 'yes'; }
	 if(isset($_POST['checkbox14'])) { $checkbox14 = 'yes'; }
	 if(isset($_POST['checkbox15'])) { $checkbox15 = 'yes'; } 
     if(isset($_POST['checkbox16'])) { $checkbox16= 'yes'; } 
	 if(isset($_POST['checkbox17'])) { $checkbox17= 'yes'; }
	 if(isset($_POST['checkbox18'])) { $checkbox18 = 'yes'; }
	 if(isset($_POST['checkbox19'])) { $checkbox19= 'yes'; }
	 if(isset($_POST['checkbox20'])) { $checkbox20= 'yes'; }
	 if(isset($_POST['checkbox21'])) { $checkbox21 = 'yes'; } 
	 if(isset($_POST['checkbox22'])) { $checkbox22 = 'yes'; } 
	 if(isset($_POST['checkbox23'])) { $checkbox23= 'yes'; }
	 if(isset($_POST['checkbox24'])) { $checkbox24 = 'yes'; }
	 if(isset($_POST['checkbox25'])) { $checkbox25 = 'yes'; }
	 if(isset($_POST['checkbox26'])) { $checkbox26= 'yes'; }
	
	$checkboxvalue1 = 'Patient identified name';
	    $checkboxvalue2 = 'Urine passed before promed action';
	    $checkboxvalue3 = 'Dentures removed';
	    $checkboxvalue4 = 'Contact lenses removed';
	    $checkboxvalue5 = 'Jowerly removed and rings tapped'; 
	    $checkboxvalue6 ='Cosmetic and Clothing Removed';
	    $checkboxvalue7 = 'Consent form signed';
	    $checkboxvalue8 ='Is enema or laxative given';
	    $checkboxvalue9 ='Are other prosthesis (if any) removed';
	    $checkboxvalue10 ='Are special order carried out, as Nasogastric tube';
	    $checkboxvalue11 ='Operative site marked';
	    $checkboxvalue12='Radiographs accompanying patient';
	    $checkboxvalue13='Test for HIV'; 
	    $checkboxvalue14='Identification bands present and correct';
	    $checkboxvalue15='Loose teeth,crowns, and bridges';
	    $checkboxvalue16='Hearing adis removed';
	    $checkboxvalue17='Pre - operative skin preparation';
	    $checkboxvalue18='Valuable securely stored';
	    $checkboxvalue19='Theatre gowns and pants wom';
	    $checkboxvalue20='Care patient case notes and other relevant chart sheet present';
	    $checkboxvalue21='Oral hygiene given';
	    $checkboxvalue22 ='Is catheter is situ';
	    $checkboxvalue23 ='Is patient having an i.v  catheter cannula(as 16,18)';
	    $checkboxvalue24 = 'Check list complete';
	    $checkboxvalue25 = 'Test for VDRL';
	    $checkboxvalue26 ='Test for Hopatitis';
	
	 
	 
	 
	 $remark1 = mysqli_real_escape_string($conn,$_POST['remark1']);
	 $remark2 = mysqli_real_escape_string($conn,$_POST['remark2']);
	 $remark3 = mysqli_real_escape_string($conn,$_POST['remark3']);
	 $remark4 = mysqli_real_escape_string($conn,$_POST['remark4']);
	 $remark5 = mysqli_real_escape_string($conn,$_POST['remark5']);
	 $remark6 = mysqli_real_escape_string($conn,$_POST['remark6']);
	 $remark7 = mysqli_real_escape_string($conn,$_POST['remark7']);
	 $remark8 = mysqli_real_escape_string($conn,$_POST['remark8']);
	 $remark9 = mysqli_real_escape_string($conn,$_POST['remark9']);
	 $remark10 =mysqli_real_escape_string($conn,$_POST['remark10']);
	 $remark11 =mysqli_real_escape_string($conn,$_POST['remark11']);
	 $remark12 =mysqli_real_escape_string($conn,$_POST['remark12']);
	 $remark13 =mysqli_real_escape_string($conn,$_POST['remark13']);
	 $remark14 =mysqli_real_escape_string($conn,$_POST['remark14']);
	 $remark15 =mysqli_real_escape_string($conn,$_POST['remark15']);
	 $remark16 =mysqli_real_escape_string($conn,$_POST['remark16']);
	 $remark17 =mysqli_real_escape_string($conn,$_POST['remark17']);
	 $remark18 =mysqli_real_escape_string($conn,$_POST['remark18']);
	 $remark19 =mysqli_real_escape_string($conn,$_POST['remark19']);
	 $remark20 =mysqli_real_escape_string($conn,$_POST['remark20']);
	 $remark21 =mysqli_real_escape_string($conn,$_POST['remark21']);
	 $remark22 =mysqli_real_escape_string($conn,$_POST['remark22']);
	 $remark23 =mysqli_real_escape_string($conn,$_POST['remark23']);
	 $remark24 =mysqli_real_escape_string($conn,$_POST['remark24']);
	 $remark25 =mysqli_real_escape_string($conn,$_POST['remark25']);
	 $remark26 =mysqli_real_escape_string($conn,$_POST['remark26']);
	 $Special_Information = mysqli_real_escape_string($conn,$_POST['Special_Information']);
	$Theatre_Time = mysqli_real_escape_string($conn,$_POST['Theatre_Time']);
	$Ward_Nurse = mysqli_real_escape_string($conn,$_POST['Ward_Nurse']);
	$Theatre_Nurse = mysqli_real_escape_string($conn,$_POST['Theatre_Nurse']);
	 
	 
	
		//insert data into tbl_pre_operative_checklist
		$insert_sql = mysqli_query($conn,"insert into tbl_pre_operative_checklist(Employee_ID,Registration_ID,Theatre_Time,Ward_Signature,Theatre_Signature,Special_Information,Operative_Date_Time)
											values('$Employee_ID','$Registration_ID','$Theatre_Time','$Ward_Nurse','$Theatre_Nurse','$Special_Information',(select now()))") or die(mysqli_error($conn));
		
		//get the last 	Pre_Operative_ID based on employee and patient ids
		$select_ids = mysqli_query($conn,"select Pre_Operative_ID from tbl_pre_operative_checklist where
										Employee_ID = '$Employee_ID' and
											Registration_ID = '$Registration_ID' order by Pre_Operative_ID DESC LIMIT 1") or die(mysqli_error($conn));
											
			$no = mysqli_num_rows($select_ids);
			if($no > 0){
				while($row = mysqli_fetch_array($select_ids)){
					$Pre_Operative_ID = $row['Pre_Operative_ID'];
				}
			}else{
				$Pre_Operative_ID = 0;
			}
			
		//insert data into tbl_pre_operative_checklist_items
		$i = 1;
		$checkboxvalue = 'checkboxvalue';
		$checkbox = 'checkbox';
		$remark = 'remark';
		while($i < 26){
			$Temp_Name = ${$checkboxvalue.$i}; //$checkboxvalue1
			$Temp_Value = ${$checkbox.$i};
			$Temp_Remark = ${$remark.$i};
			$insert_sql = mysqli_query($conn,"insert into tbl_pre_operative_checklist_items(
								Task_Name,Task_Value,Remark,Pre_Operative_ID)
								values('$Temp_Name','$Temp_Value','$Temp_Remark','$Pre_Operative_ID')") or die(mysqli_error($conn));
			$i++;
		}
		if(!mysqli_query($conn,$insert_sql)){
				    
	?>
				<script type='text/javascript'>
					var save = confirm("Do you want to fill VitalSigns form?");
					if(save == true){
						document.location = './Pre_Operative_VitalSigns.php?Registration_ID=<?php echo $Registration_ID; ?>&Vital=NurseThisPage';
					}
				</script>
	<?php
	}
}					
	 
 
?>
   
   	
<?php
    include("./includes/footer.php");
?>