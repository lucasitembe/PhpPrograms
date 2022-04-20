<legend align="left"><b>PRE / POST PAID CLEARED BILLS</b></legend>
<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}

	if(isset($_GET['Patient_Name'])){
		$Patient_Name = str_replace(" ", "%", $_GET['Patient_Name']);
	}else{
		$Patient_Name = '';
	}

	if(isset($_GET['Patient_Number'])){
		$Patient_Number = $_GET['Patient_Number'];
	}else{
		$Patient_Number = '';
	}

    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }

	if(isset($_GET['section'])){
		$Section = $_GET['Section'];
	}else{
		$Section = '';
	}

	$Filter = '';
	if(isset($_GET['Patient_Name'])){
		$Filter .= " pr.Patient_Name like '%$Patient_Name%' and ";
	}

	if(isset($_GET['Patient_Number']) && $_GET['Patient_Number'] != null && $_GET['Patient_Number'] != ''){
		$Filter .= " pr.Registration_ID = '$Patient_Number' and "; 
	}

	if($Sponsor_ID != 0){
		$Filter .= " sp.Sponsor_ID = '$Sponsor_ID' and ";
	}
?>
<table width = "100%">
<?php
	$temp = 0;
    $Title = '<tr><td colspan="7"><hr></td></tr>
                <tr>
                    <td width="5%"><b>SN</b></td>
                    <td><b>PATIENT NAME</b></td>
                    <td width="14%"><b>PATIENT NUMBER</b></td>
                    <td width="14%"><b>SPONSOR NAME</b></td>
                    <td width="15%"><b>PATIENT AGE</b></td>
                    <td width="9%"><b>GENDER</b></td>
                    <td width="12%"><b>MEMBER NUMBER</b></td>
                </tr>
                <tr><td colspan="7"><hr></td></tr>';

    $select = mysqli_query($conn,"select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name, pd.Prepaid_ID
                            from tbl_patient_registration pr, tbl_sponsor sp, tbl_prepaid_details pd where
                            $Filter
                            pd.Registration_ID = pr.Registration_ID and pd.Status = 'cleared' and
                            pr.Sponsor_ID = sp.Sponsor_ID order by Registration_ID Desc limit 200") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $link = '<a href="clearedbill.php?Registration_ID='.$data['Registration_ID'].'&Prepaid_ID='.$data['Prepaid_ID'].'&PostPaidRevenueCenter=PostPaidRevenueCenterThisForm" style="text-decoration:none;">';
            if($temp%20 == 0){ echo $Title; }

            //Calculate age
            $date1 = new DateTime($Today);
            $date2 = new DateTime($data['Date_Of_Birth']);
            $diff = $date1 -> diff($date2);
            $age = $diff->y." Years, ";
            $age .= $diff->m." Months, ";
            $age .= $diff->d." Days";
?>
            <tr id="sss">
                <td><?php echo $link.(++$temp); ?></a></td>
                <td><?php echo $link.ucwords(strtolower($data['Patient_Name'])); ?></td>
                <td><?php echo $link.$data['Registration_ID']; ?></td>
                <td><?php echo $link.$data['Guarantor_Name']; ?></td>
                <td><?php echo $link.$age; ?></td>
                <td><?php echo $link.$data['Gender']; ?></td>
                <td><?php echo $link.$data['Member_Number']; ?></td>
            </tr>
<?php
        }
    }else{
    	echo $Title;
    }
?>
</table>