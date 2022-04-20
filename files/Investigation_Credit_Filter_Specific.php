<legend align="right;">INVESTIGATION TRANSACTIONS</legend>
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

    //create filter based on dates selected
    if($Start_Date != null && $Start_Date != '' && $End_Date != '' && $End_Date != ''){
        $Date_Filter = " AND ilc.Transaction_Date_And_Time between '".$Start_Date."' and '".$End_Date."'";
    }else{
        $Date_Filter = '';
    }

    if(isset($_GET['Patient_Name'])){
        $Patient_Name = str_replace(" ", "%", $_GET['Patient_Name']);
    }else{
        $Patient_Name = '';
    }
    

    // if(isset($_GET['Patient_Number'])){
    //     $Patient_Number = $_GET['Patient_Number'];
    // }else{
    //     $Patient_Number = 0;
    // }
    
    $patient_name_n_number_filter="";
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = str_replace(" ", "%", $_GET['Patient_Name']);
        $patient_name_n_number_filter.="AND pr.Patient_Name LIKE '%$Patient_Name%'";
    }else{
        $Patient_Name = '';
    }
    

    if(isset($_GET['Patient_Number'])){
        $Patient_Number = $_GET['Patient_Number'];
        $patient_name_n_number_filter.=" AND pr.Registration_ID = '$Patient_Number'";
    }else{
        $Patient_Number = 0;
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
//        if(isset($_GET['Patient_Name'])){
//        	if($Sponsor_ID == 0 && $_GET['Patient_Name'] != null && $_GET['Patient_Name'] != ''){
//    	        $get_details = mysqli_query($conn,"select ilc.Transaction_Date_And_Time, pc.Payment_Cache_ID, pr.Registration_ID, sp.Guarantor_Name, pr.Phone_Number, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth from
//                                        tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_sponsor sp where
//                                        pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
//                                        pc.Registration_ID = pr.Registration_ID and
//                                        sp.Sponsor_ID = pr.Sponsor_ID and
//                                        sp.Require_Document_To_Sign_At_receiption = 'Mandatory' and
//                                        ilc.ePayment_Status = 'pending' and
//                                        ilc.Status = 'active' and
//                                        ilc.Transaction_Type = 'Credit' and
//                                        pr.Patient_Name like '%$Patient_Name%' and
//                                        (ilc.Check_In_Type = 'Laboratory' or ilc.Check_In_Type = 'Radiology')
//                                        $Date_Filter group by pr.Registration_ID, pc.Payment_Cache_ID order by ilc.Payment_Item_Cache_List_ID desc limit 200") or die(mysqli_error($conn));
//        	}else if($Sponsor_ID != 0 && $_GET['Patient_Name'] != null && $_GET['Patient_Name'] != ''){
//    	        $get_details = mysqli_query($conn,"select ilc.Transaction_Date_And_Time, pc.Payment_Cache_ID, pr.Registration_ID, sp.Guarantor_Name, pr.Phone_Number, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth from
//                                        tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_sponsor sp where
//                                        pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
//                                        pc.Registration_ID = pr.Registration_ID and
//                                        sp.Sponsor_ID = pr.Sponsor_ID and
//                                        sp.Require_Document_To_Sign_At_receiption = 'Mandatory' and
//                                        ilc.ePayment_Status = 'pending' and
//                                        ilc.Status = 'active' and
//                                        ilc.Transaction_Type = 'Credit' and
//                                        pr.Patient_Name like '%$Patient_Name%' and
//                                        pc.Sponsor_ID = '$Sponsor_ID' and
//                                        (ilc.Check_In_Type = 'Laboratory' or ilc.Check_In_Type = 'Radiology')
//                                        $Date_Filter group by pr.Registration_ID, pc.Payment_Cache_ID order by ilc.Payment_Item_Cache_List_ID desc limit 200") or die(mysqli_error($conn));
//        	}else{
//                if($Sponsor_ID == 0){
//                    $get_details = mysqli_query($conn,"select ilc.Transaction_Date_And_Time, pc.Payment_Cache_ID, pr.Registration_ID, sp.Guarantor_Name, pr.Phone_Number, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth from
//                                        tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_sponsor sp where
//                                        pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
//                                        pc.Registration_ID = pr.Registration_ID and
//                                        sp.Sponsor_ID = pr.Sponsor_ID and
//                                        sp.Require_Document_To_Sign_At_receiption = 'Mandatory' and
//                                        ilc.ePayment_Status = 'pending' and
//                                        ilc.Status = 'active' and
//                                        ilc.Transaction_Type = 'Credit' and
//                                        (ilc.Check_In_Type = 'Laboratory' or ilc.Check_In_Type = 'Radiology')
//                                        $Date_Filter group by pr.Registration_ID, pc.Payment_Cache_ID order by ilc.Payment_Item_Cache_List_ID desc limit 200") or die(mysqli_error($conn));
//                }else{
//                    $get_details = mysqli_query($conn,"select ilc.Transaction_Date_And_Time, pc.Payment_Cache_ID, pr.Registration_ID, sp.Guarantor_Name, pr.Phone_Number, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth from
//                                        tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_sponsor sp where
//                                        pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
//                                        pc.Registration_ID = pr.Registration_ID and
//                                        sp.Sponsor_ID = pr.Sponsor_ID and
//                                        sp.Require_Document_To_Sign_At_receiption = 'Mandatory' and
//                                        ilc.ePayment_Status = 'pending' and
//                                        ilc.Status = 'active' and
//                                        ilc.Transaction_Type = 'Credit' and
//                                        pc.Sponsor_ID = '$Sponsor_ID' and
//                                        (ilc.Check_In_Type = 'Laboratory' or ilc.Check_In_Type = 'Radiology')
//                                        $Date_Filter group by pr.Registration_ID, pc.Payment_Cache_ID order by ilc.Payment_Item_Cache_List_ID desc limit 200") or die(mysqli_error($conn));
//                }
//            }
//        }else if(isset($_GET['Patient_Number'])){
//            if($Sponsor_ID == 0 && $_GET['Patient_Number'] != null && $_GET['Patient_Number'] != ''){
//                $get_details = mysqli_query($conn,"select ilc.Transaction_Date_And_Time, pc.Payment_Cache_ID, pr.Registration_ID, sp.Guarantor_Name, pr.Phone_Number, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth from
//                                        tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_sponsor sp where
//                                        pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
//                                        pc.Registration_ID = pr.Registration_ID and
//                                        sp.Sponsor_ID = pr.Sponsor_ID and
//                                        sp.Require_Document_To_Sign_At_receiption = 'Mandatory' and
//                                        ilc.ePayment_Status = 'pending' and
//                                        ilc.Status = 'active' and
//                                        ilc.Transaction_Type = 'Credit' and
//                                        pr.Registration_ID = '$Patient_Number' and
//                                        (ilc.Check_In_Type = 'Laboratory' or ilc.Check_In_Type = 'Radiology')
//                                        $Date_Filter group by pr.Registration_ID, pc.Payment_Cache_ID order by ilc.Payment_Item_Cache_List_ID desc limit 200") or die(mysqli_error($conn));
//            }else if($Sponsor_ID != 0 && $_GET['Patient_Number'] != null && $_GET['Patient_Number'] != ''){
//                $get_details = mysqli_query($conn,"select ilc.Transaction_Date_And_Time, pc.Payment_Cache_ID, pr.Registration_ID, sp.Guarantor_Name, pr.Phone_Number, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth from
//                                        tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_sponsor sp where
//                                        pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
//                                        pc.Registration_ID = pr.Registration_ID and
//                                        sp.Sponsor_ID = pr.Sponsor_ID and
//                                        sp.Require_Document_To_Sign_At_receiption = 'Mandatory' and
//                                        ilc.ePayment_Status = 'pending' and
//                                        ilc.Status = 'active' and
//                                        ilc.Transaction_Type = 'Credit' and
//                                        pr.Registration_ID = '$Patient_Number' and
//                                        pc.Sponsor_ID = '$Sponsor_ID' and
//                                        (ilc.Check_In_Type = 'Laboratory' or ilc.Check_In_Type = 'Radiology')
//                                        $Date_Filter group by pr.Registration_ID, pc.Payment_Cache_ID order by ilc.Payment_Item_Cache_List_ID desc limit 200") or die(mysqli_error($conn));
//            }else{
//                if($Sponsor_ID == 0){
//                    $get_details = mysqli_query($conn,"select ilc.Transaction_Date_And_Time, pc.Payment_Cache_ID, pr.Registration_ID, sp.Guarantor_Name, pr.Phone_Number, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth from
//                                        tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_sponsor sp where
//                                        pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
//                                        pc.Registration_ID = pr.Registration_ID and
//                                        sp.Sponsor_ID = pr.Sponsor_ID and
//                                        sp.Require_Document_To_Sign_At_receiption = 'Mandatory' and
//                                        ilc.ePayment_Status = 'pending' and
//                                        ilc.Status = 'active' and
//                                        ilc.Transaction_Type = 'Credit' and
//                                        (ilc.Check_In_Type = 'Laboratory' or ilc.Check_In_Type = 'Radiology')
//                                        $Date_Filter group by pr.Registration_ID, pc.Payment_Cache_ID order by ilc.Payment_Item_Cache_List_ID desc limit 200") or die(mysqli_error($conn));
//                }else{
//                    $get_details = mysqli_query($conn,"select ilc.Transaction_Date_And_Time, pc.Payment_Cache_ID, pr.Registration_ID, sp.Guarantor_Name, pr.Phone_Number, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth from
//                                        tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_sponsor sp where
//                                        pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
//                                        pc.Registration_ID = pr.Registration_ID and
//                                        sp.Sponsor_ID = pr.Sponsor_ID and
//                                        sp.Require_Document_To_Sign_At_receiption = 'Mandatory' and
//                                        ilc.ePayment_Status = 'pending' and
//                                        ilc.Status = 'active' and
//                                        ilc.Transaction_Type = 'Credit' and
//                                        pc.Sponsor_ID = '$Sponsor_ID' and
//                                        (ilc.Check_In_Type = 'Laboratory' or ilc.Check_In_Type = 'Radiology')
//                                        $Date_Filter group by pr.Registration_ID, pc.Payment_Cache_ID order by ilc.Payment_Item_Cache_List_ID desc limit 200") or die(mysqli_error($conn));
//                }
//            }
//        }else{
//            if($Sponsor_ID == 0){
//                $get_details = mysqli_query($conn,"select ilc.Transaction_Date_And_Time, pc.Payment_Cache_ID, pr.Registration_ID, sp.Guarantor_Name, pr.Phone_Number, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth from
//                                        tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_sponsor sp where
//                                        pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
//                                        pc.Registration_ID = pr.Registration_ID and
//                                        sp.Sponsor_ID = pr.Sponsor_ID and
//                                        sp.Require_Document_To_Sign_At_receiption = 'Mandatory' and
//                                        ilc.ePayment_Status = 'pending' and
//                                        ilc.Status = 'active' and
//                                        ilc.Transaction_Type = 'Credit' and
//                                        (ilc.Check_In_Type = 'Laboratory' or ilc.Check_In_Type = 'Radiology')
//                                        $Date_Filter group by pr.Registration_ID, pc.Payment_Cache_ID order by ilc.Payment_Item_Cache_List_ID desc limit 200") or die(mysqli_error($conn));
//            }else{
//                $get_details = mysqli_query($conn,"select ilc.Transaction_Date_And_Time, pc.Payment_Cache_ID, pr.Registration_ID, sp.Guarantor_Name, pr.Phone_Number, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth from
//                                        tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_sponsor sp where
//                                        pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
//                                        pc.Registration_ID = pr.Registration_ID and
//                                        sp.Sponsor_ID = pr.Sponsor_ID and
//                                        sp.Require_Document_To_Sign_At_receiption = 'Mandatory' and
//                                        ilc.ePayment_Status = 'pending' and
//                                        ilc.Status = 'active' and
//                                        ilc.Transaction_Type = 'Credit' and
//                                        pc.Sponsor_ID = '$Sponsor_ID' and
//                                        (ilc.Check_In_Type = 'Laboratory' or ilc.Check_In_Type = 'Radiology')
//                                        $Date_Filter group by pr.Registration_ID, pc.Payment_Cache_ID order by ilc.Payment_Item_Cache_List_ID desc limit 200") or die(mysqli_error($conn));
//            }
//        }
//
//        $nm = mysqli_num_rows($get_details);
//        $temp = 0;
//        if($nm > 0){
//            while ($row = mysqli_fetch_array($get_details)) {
//            	$Date_Of_Birth = $row['Date_Of_Birth'];
//				$date1 = new DateTime($Today);
//				$date2 = new DateTime($Date_Of_Birth);
//				$diff = $date1 -> diff($date2);
//				$age = $diff->y." Years, ";
//				$age .= $diff->m." Months, ";
//				$age .= $diff->d." Days";
//                                $Transaction_Date_And_Time=$row['Transaction_Date_And_Time'];
//
//            ?>
<!--                <tr>
                    <td><a href="approveinestigation.php?//<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApproveInvestigation=ApproveInvestigationThisPage&Transaction_Date_And_Time=<?= $Transaction_Date_And_Time ?>" style="text-decoration: none;"><?php echo ++$temp; ?></td>
                    <td><a href="approveinestigation.php?//<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApproveInvestigation=ApproveInvestigationThisPage&Transaction_Date_And_Time=<?= $Transaction_Date_And_Time ?>" style="text-decoration: none;"><?php echo strtoupper($row['Patient_Name']); ?></a></td>
                    <td><a href="approveinestigation.php?//<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApproveInvestigation=ApproveInvestigationThisPage&Transaction_Date_And_Time=<?= $Transaction_Date_And_Time ?>" style="text-decoration: none;"><?php echo $row['Registration_ID']; ?></a></td>
                    <td><a href="approveinestigation.php?//<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApproveInvestigation=ApproveInvestigationThisPage&Transaction_Date_And_Time=<?= $Transaction_Date_And_Time ?>" style="text-decoration: none;"><?php echo $row['Guarantor_Name']; ?></a></td>
                    <td><a href="approveinestigation.php?//<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApproveInvestigation=ApproveInvestigationThisPage&Transaction_Date_And_Time=<?= $Transaction_Date_And_Time ?>" style="text-decoration: none;"><?php echo strtoupper($row['Gender']); ?></a></td>
                    <td><a href="approveinestigation.php?//<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApproveInvestigation=ApproveInvestigationThisPage&Transaction_Date_And_Time=<?= $Transaction_Date_And_Time ?>" style="text-decoration: none;"><?php echo $age; ?></a></td>
                    <td><a href="approveinestigation.php?//<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApproveInvestigation=ApproveInvestigationThisPage&Transaction_Date_And_Time=<?= $Transaction_Date_And_Time ?>" style="text-decoration: none;"><?php echo $row['Transaction_Date_And_Time']; ?></a></td>
                    <td><a href="approveinestigation.php?//<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $row['Payment_Cache_ID']; ?>&ApproveInvestigation=ApproveInvestigationThisPage&Transaction_Date_And_Time=<?= $Transaction_Date_And_Time ?>" style="text-decoration: none;"><?php echo $row['Member_Number']; ?></a></td>
                </tr>-->
            <?php
//            	if($temp%21 == 0){
//            		echo $Title;
//            	}
//            }
//        }
            
            
                    $get_details = mysqli_query($conn,"SELECT pc.Payment_Date_And_Time,pr.Registration_ID,pr.Sponsor_ID,pc.Payment_Cache_ID,pr.Phone_Number, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth FROM tbl_payment_cache pc,tbl_patient_registration pr WHERE pc.Registration_ID = pr.Registration_ID  $patient_name_n_number_filter group by pc.Registration_ID, pc.Payment_Cache_ID ORDER BY Payment_Date_And_Time DESC LIMIT 50") or die(mysqli_error($conn));
        
        $nm = mysqli_num_rows($get_details);
        
        if($nm > 0){
            while ($row = mysqli_fetch_array($get_details)) {
                $Sponsor_ID = $row['Sponsor_ID'];
                $Payment_Cache_ID = $row['Payment_Cache_ID'];
//                AND Payment_Cache_ID IN (SELECT Payment_Cache_ID FROM tbl_item_list_cache WHERE (Check_In_Type = 'Laboratory' or Check_In_Type = 'Radiology') AND ePayment_Status = 'pending' AND Status = 'active')
                $Payment_Cache_ID=$row['Payment_Cache_ID'];
                
                $sql_select_item_detail_result=mysqli_query($conn,"SELECT Transaction_Date_And_Time FROM tbl_item_list_cache  WHERE ePayment_Status = 'pending' AND Status = 'active' AND (Check_In_Type = 'Laboratory' or Check_In_Type = 'Radiology') AND Payment_Cache_ID='$Payment_Cache_ID' GROUP BY Payment_Cache_ID LIMIT 50") or die(mysqli_error($conn));
               if(mysqli_num_rows($sql_select_item_detail_result)>0){
                    while($item_detail_rows=mysqli_fetch_assoc($sql_select_item_detail_result)){
                    $Transaction_Date_And_Time=$item_detail_rows['Transaction_Date_And_Time'];
                    
                    
                $sql_select_sponsor_name_result=mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID' AND Require_Document_To_Sign_At_receiption = 'Mandatory'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_sponsor_name_result)>0){
                    $Guarantor_Name=mysqli_fetch_assoc($sql_select_sponsor_name_result)['Guarantor_Name'];
                }else{
                    continue;
                }
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $date1 = new DateTime($Today);
                $date2 = new DateTime($Date_Of_Birth);
                $diff = $date1 -> diff($date2);
                $age = $diff->y." Years, ";
                $age .= $diff->m." Months, ";
                $age .= $diff->d." Days";
//               $Payment_Date_And_Time=$row['Payment_Date_And_Time'];
    ?>
        <tr>
            <td><a href="approveinestigation.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&ApproveInvestigation=ApproveInvestigationThisPage&Transaction_Date_And_Time=<?= $Payment_Date_And_Time ?>" style="text-decoration: none;"><?php echo ++$temp; ?></td>
            <td><a href="approveinestigation.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&ApproveInvestigation=ApproveInvestigationThisPage&Transaction_Date_And_Time=<?= $Payment_Date_And_Time ?>" style="text-decoration: none;"><?php echo strtoupper($row['Patient_Name']); ?></a></td>
            <td><a href="approveinestigation.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&ApproveInvestigation=ApproveInvestigationThisPage&Transaction_Date_And_Time=<?= $Payment_Date_And_Time ?>" style="text-decoration: none;"><?php echo $row['Registration_ID']; ?></a></td>
            <td><a href="approveinestigation.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&ApproveInvestigation=ApproveInvestigationThisPage&Transaction_Date_And_Time=<?= $Payment_Date_And_Time ?>" style="text-decoration: none;"><?php echo $Guarantor_Name; ?></a></td>
            <td><a href="approveinestigation.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&ApproveInvestigation=ApproveInvestigationThisPage&Transaction_Date_And_Time=<?= $Payment_Date_And_Time ?>" style="text-decoration: none;"><?php echo strtoupper($row['Gender']); ?></a></td>
            <td><a href="approveinestigation.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&ApproveInvestigation=ApproveInvestigationThisPage&Transaction_Date_And_Time=<?= $Payment_Date_And_Time ?>" style="text-decoration: none;"><?php echo $age; ?></a></td>
            <td><a href="approveinestigation.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&ApproveInvestigation=ApproveInvestigationThisPage&Transaction_Date_And_Time=<?= $Payment_Date_And_Time ?>" style="text-decoration: none;"><?php echo $Transaction_Date_And_Time; ?></a></td>
            <td><a href="approveinestigation.php?<?php echo $Section_Link; ?>Registration_ID=<?php echo $row['Registration_ID']; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&ApproveInvestigation=ApproveInvestigationThisPage&Transaction_Date_And_Time=<?= $Payment_Date_And_Time ?>" style="text-decoration: none;"><?php echo $row['Member_Number']; ?></a></td>
        </tr>
    <?php
        }}}
        }
    ?>
    
    
    </table>