<?php
	session_start();
	include("./includes/connection.php");
	
	if(isset($_GET['Region_ID'])){
		$Region_ID = $_GET['Region_ID'];
	}else{
		$Region_ID = 0;
	}

	if(isset($_GET['District_ID'])){
		$District_ID = $_GET['District_ID'];
	}else{
		$District_ID = 0;
	}

	if(isset($_GET['Date_From'])){
		$Date_From = $_GET['Date_From'];
	}else{
		$Date_From = '';
	}

	if(isset($_GET['Date_To'])){
		$Date_To = $_GET['Date_To'];
	}else{
		$Date_To = '';
	}

	if(isset($_GET['ageFrom'])){
		$Age_From = $_GET['ageFrom'];
	}else{
		$Age_From = 0;
	}
	if(isset($_GET['ageTo'])){
		$Age_To = $_GET['ageTo'];
	}else{
		$Age_To = 0;
	}

	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }

	//generate 
    $select = mysqli_query($conn,"select min(Check_In_ID) as Check_In_ID from tbl_check_in where Check_In_Date_And_Time between '$Date_From' and '$Date_To'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
    	while ($data = mysqli_fetch_array($select)) {
    		$Initial_Check_In_ID = $data['Check_In_ID'];
    	}
    }else{
    	$Initial_Check_In_ID = 0;
    }

    $htm = "<table width ='100%' height = '30px'>
			    <tr><td>
				<img src='./branchBanner/branchBanner.png'>
			    </td></tr>
		    </table>";

	$htm .= "<table width=100%><tr>
		    	<td style='text-align: left;'>Demographic Report (New Visits)</td>
		    </tr>
		    <tr><td style='text-align: left;'>Age From ".$Age_From." To ".$Age_To."</td></tr>
		    <tr><td style='text-align: left;'>Start Date ".$Date_From."</td></tr>
		    <tr><td style='text-align: left;'>End Date ".$Date_To."</td></tr>
		   </table><br/>";

	$htm .= "<table width =100% style='border: 0'>
		<tr>
            <td style='text-align:left; width:3%;border: 1px #ccc solid;'><b>SN</b></td>
            <td style='text-align:left; width:3%;border: 1px #ccc solid;'><b>SPONSOR NAME</b></td>
            <td style='text-align:right; width:3%;border: 1px #ccc solid;'><b>MALE</b></td>
            <td style='text-align:right; width:3%;border: 1px #ccc solid;'><b>FEMALE</b></td>
			<td style='text-align:right; width:3%;border: 1px #ccc solid;'><b>TOTAL</b></td>
		</tr>
		<tr><td colspan=5 style='border: 1px #ccc solid'><hr></td></tr>";

			$temp = 0;
			$Total_Male = 0;
			$Total_Female = 0;
			$Grand_Total = 0;
			$Grand_Total_Male = 0;
			$Grand_Total_Female = 0;

			$select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
			$num = mysqli_num_rows($select);
			if($num > 0){
				while ($data = mysqli_fetch_array($select)) {
					//get all new visits based on selected sponsor
					$Sponsor_ID = $data['Sponsor_ID'];					
					if($District_ID == 0){
						$get_patients = mysqli_query($conn,"select Date_Of_Birth, Gender, pr.Registration_ID from tbl_check_in ci, tbl_patient_registration pr where
													ci.Registration_ID = pr.Registration_ID and
													pr.Sponsor_ID = '$Sponsor_ID' and
													ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To'") or die(mysqli_error($conn));
					}else{
						$get_patients = mysqli_query($conn,"select Date_Of_Birth, Gender, pr.Registration_ID from tbl_check_in ci, tbl_patient_registration pr where
													ci.Registration_ID = pr.Registration_ID and
													pr.Sponsor_ID = '$Sponsor_ID' and
													pr.District_ID = '$District_ID' and
													ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To'") or die(mysqli_error($conn));

						/*
							there is a problem here. those patients registrered before are contradicting the number
						*/
					}

					$no = mysqli_num_rows($get_patients);
					
					if($no > 0){

						while ($row = mysqli_fetch_array($get_patients)) {
							$Date_Of_Birth = $row['Date_Of_Birth'];
							//generate age
							$date1 = new DateTime($Today);
							$date2 = new DateTime($Date_Of_Birth);
							$diff = $date1 -> diff($date2);
							$age = $diff->y;

							$Gender = $row['Gender'];
							$Registration_ID = $row['Registration_ID'];

							$verify = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Check_In_Date_And_Time between '$Date_From' and '$Date_To' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
							$num_verify = mysqli_num_rows($verify);

							if($num_verify == 1){
								//check if no any check in before
								$verify_again = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Check_In_ID < '$Initial_Check_In_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
								$num_verify_again = mysqli_num_rows($verify_again);
								if($num_verify_again == 0){
									if($age >= $Age_From && $age <= $Age_To){
										if(strtolower($Gender) == 'male'){
											$Total_Male += 1;
											$Grand_Total_Male += 1;
										}else if(strtolower($Gender) == 'female'){
											$Total_Female += 1;
											$Grand_Total_Female += 1;
										}
									}
								}
							}													
						}
					}

				$htm .=	"<tr>
						<td>".++$temp."</td>
						<td>".$data['Guarantor_Name']."</td>
						<td style='text-align: right;'>".$Total_Male."</td>
						<td style='text-align: right;'>".$Total_Female."</td>
						<td style='text-align: right;'>".($Total_Male+$Total_Female)."</td>
					</tr>";

					$Total_Male = 0;
					$Total_Female = 0;
					$Grand_Total = 0;
				}

			$htm .=	"<tr><td colspan=5 style='border: 1px #ccc solid'><hr></td></tr>
				<tr>
					<td colspan='2' style='text-align: left;'><b>TOTAL</b></td>
					<td style='text-align: right;'>".$Grand_Total_Male."</td>
					<td style='text-align: right;'>".$Grand_Total_Female."</td>
					<td style='text-align: right;'>".($Grand_Total_Male + $Grand_Total_Female)."</td>
				</tr>
				<tr><td colspan=5 style='border: 1px #ccc solid'><hr></td></tr>";
		
			}
	$htm .= "</table>";

?>


<?php
    //echo $htm;
    //$htm .= "</table>";
    include("MPDF/mpdf.php");

    $mpdf=new mPDF('','Letter',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    exit;
?>