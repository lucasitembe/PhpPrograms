<?php
    include("./includes/header_general.php");
    include("./includes/connection.php");
	
	 if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this page yet.Try login again or contact administrator for support!<p>");

    }
    if(isset($_SESSION['userinfo'])){
       if(isset($_SESSION['userinfo']['Patient_Record_Works'])){
	    if($_SESSION['userinfo']['Patient_Record_Works'] != 'yes'){
		die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
	    }
	}else{
	    	die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this page yet.Try login again or contact administrator for support!<p>");

	}
      
    }else{
	@session_destroy();
	   	die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this page yet.Try login again or contact administrator for support!<p>");

    }
    
	
?>
<!-- new date function (Contain years, Months and days)--> 
<?php
//Error_reporting(E_ERROR|E_PARSE);
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$Age ='';
    }
	
	//get employee id
        if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            }else{
                $Employee_ID = 0;
            }
        }
		
	//get branch id
		if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Branch_ID'])){
                $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
            }else{
                $Branch_ID = 0;
            }
        }	
?>
<!-- end of the function -->

<?php
  
//$Registration_ID='313131';
		//to select patient into from patient table and sponsor
 if(isset($_GET['Registration_ID']) && isset($_GET['Nurse_DateTime'])  ){ 

		$Registration_ID = $_GET['Registration_ID']; 
        $Nurse_DateTime = $_GET['Nurse_DateTime']; 
	$select_Patients = mysqli_query($conn,"SELECT 
									Patient_Name,n.Registration_ID, pr.Registration_ID,
									Guarantor_Name,Gender,Date_Of_Birth,
									Allegies,Special_Condition,bmi,Patient_Direction, Consultant
								from 
										tbl_Patient_Registration pr,tbl_sponsor sp,tbl_nurse n
								where  
									  n.Registration_ID = pr.Registration_ID AND 
									  pr.sponsor_ID = sp.sponsor_ID AND 
									 Nurse_DateTime='$Nurse_DateTime' AND 
									  pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));							
	$no = mysqli_num_rows($select_Patients);
	
	 if($no>0){
	      //echo 'Found';exit;
            while($row = mysqli_fetch_array($select_Patients)){
                $Registration_ID = $row['Registration_ID'];
                $Patient_Name = $row['Patient_Name'];
				$Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Guarantor_Name = $row['Guarantor_Name'];
                $Allegies= $row['Allegies'];
				 $Special_Condition = $row['Special_Condition'];
				 $bmi = $row['bmi'];
				 $Patient_Direction = $row['Patient_Direction'];
				 $Consultant = $row['Consultant'];
	}
	 $Age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
	  if($Age == 0){
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$Age = $diff->m." Months";
	    }
	    if($Age == 0){
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$Age = $diff->d." Days";
	    }
	 
	}else{
            $Registration_ID = '';
            $Patient_Name = ''; 
            $Date_Of_Birth = '';
            $Gender = '';
			$Guarantor_Name = '';
			$Allegies = '';
			$Special_Condition = '';
			$bmi = '';
			$Patient_Direction = '';
			$Consultant = '';
		}
	}else{
            $Registration_ID = '';
            $Patient_Name = ''; 
            $Date_Of_Birth = '';
            $Gender = '';
			$Guarantor_Name = '';
			$Allegies = '';
			$Special_Condition = '';
			$bmi = '';
			$Patient_Direction = '';
			$Consultant = '';
	}

    echo "<a href='Patientfile_Record_Detail_General.php?Registration_ID=".$_GET['Registration_ID']."&PatientFileRecordThisPage=ThisPage' class='art-button-green'>BACK</a>";
	?>	
<br/><br/>
<center>
	<form action="#" method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
	<fieldset style="width:80%;">
						<legend align=right><b>NURSE STATION</b></legend>
				<center>
		<table  class='hiv_table'  style="width:100%;">
			<tr>
				<td width='8%' class="kulia"  style="text-align:right">Patient Name</td>
				<td width='20%' colspan="3"><input type="text" name='Patient_Name'  disabled='disabled' id='Patient_Name' value="<?php echo$Patient_Name; ?>" /> </td>
			
				<td width='8%' class="kulia"  style="text-align:right">Patient No</td>
				<td width='10%'><input type="text" name='Registration_ID'id="Registration_No" disabled='disabled'  value="<?php echo$Registration_ID; ?>" /> </td>
				
				<td width='6%' class="kulia"  style="text-align:right">Sponsor</td>
				<td width='10%' colspan="2"><input type="text" disabled='disabled' name="Guarantor_Name" id="Guarantor_Name" value="<?php echo$Guarantor_Name; ?>" ></td>
			</tr>
			<tr>
				<td width='8%' class="kulia"  style="text-align:right">Gender</td>
				<td><input type="text"  name="Gender" id="Gender"  value="<?php echo$Gender; ?>" disabled='disabled' ></td>
				
				<td width='8%' class="kulia"  style="text-align:right">Age</td>
				<td><input type="text" disabled='disabled' name="Age" id="Age" value="<?php echo$Age; ?>" ></td>
				
				<td width='8%' class="kulia"  style="text-align:right">Visit Date</td>
				<td width='6%'><input type="text" disabled='disabled'  name="Visit_Date" id="Visit_Date" value="<?php echo$Today; ?>" ></td>
				
			</tr>
	</table >
</center>
<hr>
			<table class='hiv_table' style="width:100%;text-align:right;margin-top:5px;" >
				<tr>
					<td rowspan='2'>
<fieldset class='vital' style="height:320px;overflow-y:scroll;">
<?php

	$result=mysqli_query($conn,"SELECT * FROM tbl_vital");
		
echo "<table border='0'  bgcolor='white' >
		<tr>
			<th>SN</th>
			<th>Vital</th>
			<th width='25%'>Value</th>
			<th width='20%' colspan='2'>Evolution</th>
		</tr>";
	$i=1;
	$j=1;

		while($row=mysqli_fetch_array($result)){
		
		 if(isset($_GET['Nurse_DateTime'])){
			$Nurse_DateTime = $_GET['Nurse_DateTime'];
			$select_Patients = mysqli_query($conn,"SELECT Vital_Value 
											from 
												tbl_nurse n,tbl_nurse_vital nv
											where 
												n.Nurse_ID = nv.Nurse_ID AND 
												n.Registration_ID = '$Registration_ID' AND
									     		Nurse_DateTime='$Nurse_DateTime' AND									
												nv.Vital_ID ='".$row['Vital_ID']."' ") or die(mysqli_error($conn));							
			$nos = mysqli_num_rows($select_Patients);			
			
		 if($nos>0){
		 

				while($rows = mysqli_fetch_array($select_Patients)){
					$Vital_Value = $rows['Vital_Value'];

			echo "<tr>";
			echo "<td>" .$i . "</td>";
			echo "<td>" .$row['Vital'] . "</td>";
			echo "<td> <input type='text' name='Vital_Value[]' id='Vital_Value' value='$Vital_Value' readonly='readonly'> </td>";
	if (isset($_GET['Registration_ID'])){
?>
	<td>
		<div class="nurse_tabs" style="width:100px;height:30px;padding:0px;">
			<a href='chartgraph.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Vital_ID=<?php echo $row['Vital_ID']; ?>&
			Vital=<?php echo $row['Vital']; ?>&Nurse_DateTime=<?php echo filter_input(INPUT_GET,'Nurse_DateTime'); ?>' 
			class='art-button-green'>History</a>
		</div>
	</td>
	<?php
	} else {
?>
	<td>
		<a href="#" class='art-button-green'>History</a>	
	</td>
<?php
	}
	$i++;	
		}
	
	}else{
	$Vital_Value='';
	
	}
		}else{
			//$i=1;
		$Vital_Value='';
		echo "<tr>";
		echo "<td>" .$j . "</td>";
		echo "<td>" .$row['Vital'] . "</td>";
		echo "<td> <input type='text' name='Vital_Value[]' id='Vital_Value' value='$Vital_Value' readonly='readonly'> </td>";
		echo "<td><a href='#' class='art-button-green'>History</a></td>";
			
	echo "</tr>";
	
	$j++;
	}
}
?>	
</tr>
</table>	
 </fieldset >
<?php

 if(isset($_GET['Registration_ID'])){
?>
 <br/>
	<div><span style="font-size:14px;margin-left:4px;margin-right:2px;margin-top:5px;"><b>BMI</b></span>
			<span><input type="text" name="bmi" id="bmi" value="<?php echo$bmi; ?>" 
			disabled='disabled' style="width:100px">
				<span class="nurse_tabs" style="width:120px;height:30px;">	
					<a href='chartbmi_general.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&bmi=<?php echo $bmi; ?>&Nurse_DateTime=<?php echo filter_input(INPUT_GET,'Nurse_DateTime'); ?>'  class='art-button-green'>history</a>
				</span>
			</span>
	</div>
		<?php
	} else {
?>
	<br>
	<div><span style="font-size:14px;margin-left:4px;margin-right:2px;margin-top:10px;"><b>BMI</b></span>
		<span><input type="text" name="bmi" id="bmi" value="<?php echo$bmi; ?>" style="width:100px">
			<span class="nurse_tabs" style="width:100px;height:30px;">	
					<a href='#??Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $row['bmi']; ?>'  class='art-button-green'>history</a>
			</span>
		</span>
	</div>
	<?php
	}
	
?>	
	</td>
	<td>
		<fieldset>
		<table width='100%'>
			<tr>
				<td width="20%" class="kulia"  style="text-align:right">Allegies</td>
				<td>
					<textarea rows="4" disabled='disabled' name="Allegies"  id="Allegies" style="resize:none;"><?php echo$Allegies; ?></textarea>
				</td>
			</tr>
			<tr>
				<td class="kulia" width="21%"  style="text-align:right">Special Condition</td>
				<td>
					<textarea rows="4" disabled='disabled' name="Special_Condition" id="Special_Condition" style="resize:none;"><?php echo$Special_Condition; ?></textarea>
				</td>
			</tr>
		</table>
		<table border='1' width='100%' bgcolor='#D3D3D3' style="float:right">
			<tr>
				<td width="15%" ><b>Direction</b></td>
				<td width="35%" rowspan="1">
					<input type="text" disabled='disabled' name="Patient_Direction"  value="<?php echo$Patient_Direction; ?>">
				</td>
				
				<td rowspan=2>
			<fieldset>
					<table border="1">
							<caption><b>Procedure From </b></caption>
					<tr >
						<td >Doctor</td><td >Non Doctor</td> 
					</tr>
					<tr>
						<td ><input type="text" disabled='disabled' value="" /></td>
						<td ><input type="text" disabled='disabled' value="" /></td>
					</tr>
					</table>
			</fieldset>
				</td>
			</tr >
			<tr >
				<td ><b>Consultant</b></td>
				<td width="4%">
					<input type="text" name="Consultant" disabled='disabled' value="<?php echo$Consultant; ?>" >
				</td>
			</tr >
		</table>
		</fieldset>
		</td>
	</tr>
	</table>			
</fieldset>
</form>
	</center>
<?php
   // include("./includes/footer.php");
?>