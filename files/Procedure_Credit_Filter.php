<legend align="right;">PROCEDURE & SURGERY TRANSACTIONS</legend>
<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}

	if(isset($_GET['Start_Date'])){
		$Start_Date = $_GET['Start_Date'];
	}else{
		$Start_Date = '';
	}


	if(isset($_GET['End_Date'])){
		$End_Date = $_GET['End_Date'];
	}else{
		$End_Date = '';
	}
    
    if(isset($_GET['Section'])){
        $Section_Link = "Section=".$_GET['Section']."&";
        $Section = $_GET['Section'];
    }else{
        $Section_Link = "";
        $Section = '';
    }

    //get today date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }
    $Title = '<tr><td colspan="8"><hr></td></tr>
    			<tr>
			        <td width="5%"><b>SN</b></td>
			        <td><b>PATIENT NAME</b></td>
			        <td width="10%"><b>PATIENT NUMBER</b></td>
			        <td width="15%"><b>SPONSOR NAME</b></td>
			        <td width="7%"><b>GENDER</b></td>
			        <td width="14%"><b>AGE</b></td>
			        <td width="10%"><b>TRANSACTION DATE</b></td>
			        <td width="10%"><b>MEMBER NUMBER</b></td>
			    </tr>
			    <tr><td colspan="8"><hr></td></tr>';
?>
<table width="100%">
    <?php
    	echo $Title;
        //select patients based on sponsor selected
        if($Sponsor_ID != 0){
            $filter = "pc.Sponsor_ID = '$Sponsor_ID' and ";
        }else{
            $filter = "";
        }
        $get_details = mysqli_query($conn,"select ilc.Transaction_Date_And_Time, pc.Payment_Cache_ID, pr.Registration_ID, sp.Guarantor_Name, pr.Phone_Number, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth from
                                tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_sponsor sp where
                                pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                pc.Registration_ID = pr.Registration_ID and
                                sp.Sponsor_ID = pr.Sponsor_ID and
                                sp.Require_Document_To_Sign_At_receiption = 'Mandatory' and
                                ilc.ePayment_Status = 'pending' and
                                ilc.Status = 'active' and
                                ilc.Transaction_Type = 'Credit' and
                                $filter
                                (ilc.Check_In_Type = 'Procedure' or ilc.Check_In_Type = 'Surgery') and
                                ilc.Transaction_Date_And_Time between '$Start_Date' and '$End_Date' group by pr.Registration_ID, pc.Payment_Cache_ID order by ilc.Payment_Item_Cache_List_ID desc limit 50") or die(mysqli_error($conn));
    	
        $nm = mysqli_num_rows($get_details);
        $temp = 0;
        if($nm > 0){
            while ($row = mysqli_fetch_array($get_details)) {
            	$Date_Of_Birth = $row['Date_Of_Birth'];
				$date1 = new DateTime($Today);
				$date2 = new DateTime($Date_Of_Birth);
				$diff = $date1 -> diff($date2);
				$age = $diff->y." Years, ";
				$age .= $diff->m." Months, ";
				$age .= $diff->d." Days";

            ?>
                <tr>
                    <td><a href="approveprocedure.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApproveInvestigation=ApproveInvestigationThisPage" style="text-decoration: none;"><?php echo ++$temp; ?></td>
                    <td><a href="approveprocedure.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApproveInvestigation=ApproveInvestigationThisPage" style="text-decoration: none;"><?php echo strtoupper($row['Patient_Name']); ?></a></td>
                    <td><a href="approveprocedure.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApproveInvestigation=ApproveInvestigationThisPage" style="text-decoration: none;"><?php echo $row['Registration_ID']; ?></a></td>
                    <td><a href="approveprocedure.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApproveInvestigation=ApproveInvestigationThisPage" style="text-decoration: none;"><?php echo $row['Guarantor_Name']; ?></a></td>
                    <td><a href="approveprocedure.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApproveInvestigation=ApproveInvestigationThisPage" style="text-decoration: none;"><?php echo strtoupper($row['Gender']); ?></a></td>
                    <td><a href="approveprocedure.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApproveInvestigation=ApproveInvestigationThisPage" style="text-decoration: none;"><?php echo $age; ?></a></td>
                    <td><a href="approveprocedure.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApproveInvestigation=ApproveInvestigationThisPage" style="text-decoration: none;"><?php echo $row['Transaction_Date_And_Time']; ?></a></td>
                    <td><a href="approveprocedure.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApproveInvestigation=ApproveInvestigationThisPage" style="text-decoration: none;"><?php echo $row['Member_Number']; ?></a></td>
                </tr>
            <?php
            	if($temp%21 == 0){
            		echo $Title;
            	}
            }
        }
    ?>
    </table>