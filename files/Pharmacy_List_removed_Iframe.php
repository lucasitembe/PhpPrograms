<link rel="stylesheet" href="table.css" media="screen">
<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = str_replace(" ", "%", $_GET['Patient_Name']);   
    }else{
        $Patient_Name = '';
    }

    if(isset($_GET['Registration_Number'])){
    $Registration_Number = $_GET['Registration_Number'];
    }else{
    $Registration_Number = '';
    }

    if(isset($_GET['date_From'])){
        $date_From = $_GET['date_From'];
    }else{
        $date_From = '';
    }


    if(isset($_GET['date_To'])){
        $date_To = $_GET['date_To'];
    }else{
        $date_To = '';
    }

    if(isset($_GET['Patient_Number'])){
        $Patient_Number = $_GET['Patient_Number'];
    }else{
        $Patient_Number = '';
    }

    //Get Employee_ID
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    //get today's date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age ='';
    }


    //get billing type 
    if(isset($_GET['Billing_Type'])){
    $Billing_Type = $_GET['Billing_Type'];
    if($Billing_Type == 'OutpatientCash'){
        //$Temp_Billing_Type = 'Outpatient Cash';
        //$sql = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit') and ilc.Transaction_Type = 'cash'";
//            $sql = " (pc.billing_type = 'Outpatient Cash') and ilc.Transaction_Type = 'cash' AND (Check_In_Type='Pharmacy' OR Check_In_Type='Others')";
            $sql = "pc.billing_type = 'Outpatient Cash'";
            $Transaction_Type='cash';
//            $sql2 = " (pc.billing_type = 'Outpatient Cash') and ilc.Transaction_Type = 'cash' AND Check_In_Type='Others'";

            
        }elseif($Billing_Type == 'OutpatientCredit'){
        //$Temp_Billing_Type = 'Outpatient Credit';
       // $sql = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit') and ilc.Transaction_Type = 'credit'";
//            $sql = " (pc.billing_type = 'Outpatient Credit' or ilc.Transaction_Type = 'credit') AND (Check_In_Type='Pharmacy' OR Check_In_Type='Others')";
//            $sql2 = " (pc.billing_type = 'Outpatient Credit' or ilc.Transaction_Type = 'credit') AND Check_In_Type='Others'";
            $sql = "pc.billing_type = 'Outpatient Credit'";
            $Transaction_Type='credit';
    }elseif($Billing_Type == 'InpatientCash'){
        //$Temp_Billing_Type = 'Inpatient Cash';
            // $sql = " (pc.billing_type = 'inpatient Cash' or pc.billing_type = 'inpatient Credit') and ilc.Transaction_Type = 'cash'";

//            $sql = " (pc.billing_type = 'inpatient Cash') and ilc.Transaction_Type = 'cash' AND (Check_In_Type='Pharmacy' OR Check_In_Type='Others')";
//            $sql2 = " (pc.billing_type = 'inpatient Cash') and ilc.Transaction_Type = 'cash' AND Check_In_Type='Others'";
            
            $sql = "pc.billing_type = 'inpatient Cash'";
            $Transaction_Type='cash';
    }elseif($Billing_Type == 'InpatientCredit'){
        //$Temp_Billing_Type = 'Inpatient Credit';
            //$sql = " (pc.billing_type = 'inpatient Cash' or pc.billing_type = 'inpatient Credit') and ilc.Transaction_Type = 'credit'";
//            $sql = " (pc.billing_type = 'inpatient Credit') and ilc.Transaction_Type = 'credit' AND (Check_In_Type='Pharmacy' OR Check_In_Type='Others')";
//            $sql2 = " (pc.billing_type = 'inpatient Credit') and ilc.Transaction_Type = 'credit' AND Check_In_Type='Others'";
            $sql = "pc.billing_type = 'inpatient Credit'";
            $Transaction_Type='credit';
    }elseif($Billing_Type == 'PatientFromOutside'){
        //$Temp_Billing_Type = 'Patient From Outside';
//      $sql = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit' AND (Check_In_Type='Pharmacy' OR Check_In_Type='Others'))";
//      $sql2 = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit' AND Check_In_Type='Others')";
            $sql = "(pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit')";
            $Transaction_Type="(credit or cash)";
    }else{
        //$Temp_Billing_Type = '';
//      $sql = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit' AND (Check_In_Type='Pharmacy' OR Check_In_Type='Others'))";
//      $sql2 = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit' AND Check_In_Type='Others')";
            $sql = "(pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit')";
            $Transaction_Type="(credit or cash)";
    }
    }else{
        $Billing_Type = '';
        //$Temp_Billing_Type = '';
//  $sql = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit' AND (Check_In_Type='Pharmacy' OR Check_In_Type='Others'))";
//  $sql2 = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit' AND Check_In_Type='Others')";
        
        $sql = "(pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit')";
        $Transaction_Type="(credit or cash)";
    }

    if($date_To != null && $date_To != '' && $date_From != null && $date_From != ''){
        $sql = " pc.Payment_Date_And_Time between '$date_From' and '$date_To'";
    }

    if(isset($_SESSION['systeminfo']['Filtered_Pharmacy_Patient_List']) && strtolower($_SESSION['systeminfo']['Filtered_Pharmacy_Patient_List']) == 'no' and $date_From == '' and $date_To == '' and $date_From == null and $date_To == null){
        $Filter = 'limit 10';
        if(isset($_GET['Patient_Name'])){ $Filter = 'limit 10'; }
    }else{
        $Filter = 'limit 200';
    }
    
    
    ?>
    <?php   
    //get sub department id
    if(isset($_SESSION['Pharmacy_ID'])){
        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
        $Sub_Department_ID = 0;
    }

// Search in removed list
    $search = "";
    if (isset($_GET['Patient_Name'])) {
        $patientName = $_GET['Patient_Name'];

        $search  = " AND Patient_Name LIKE '%$patientName%'";
    }

    if (isset($_GET['Patient_Number'])) {
        $patientNumber = $_GET['Patient_Number'];
        $search_by_patient_number=" AND pc.Registration_ID LIKE '%$patientNumber%'";
        $search  = " AND Registration_ID LIKE '%$patientNumber%'";
    }else{
        $search_by_patient_number=" ";
    }

// select all patients with removed items
    echo '<center><table width =100% border=0>';
    $Title = '<tr id="thead">
    <td style="width:4%;"><b>SN</b></td>';
    $Title .= '<td ><b>STATUS</b></td>
                <td><b>COSTUMER NAME</b></td>
                <td><b>COSTUMER NO</b></td>
                <td><b>SPONSOR</b></td>
                <td><b>GENDER</b></td>
                <td><b>TRANSACTION DATE</b></td>
                <td><b>MEMBER NUMBER</b></td></tr>';
                echo $Title;
    if(isset($_SESSION['systeminfo']['Allow_Pharmacy_To_Dispense_Multiple_Patients']) && strtolower($_SESSION['systeminfo']['Allow_Pharmacy_To_Dispense_Multiple_Patients']) == 'yes'){
        // $Title .= '<td width="3%" style="text-align: center;"><b></b></td>';
    }

    // retrive removed list 
    


    $select_pre = mysqli_query($conn,"SELECT itc.Transaction_Date_And_Time,itc.Status,itc.Transaction_Type,itc.Payment_Cache_ID,itc.Check_In_Type,pc.Registration_ID,itc.Payment_Item_Cache_List_ID FROM  tbl_payment_cache pc JOIN tbl_item_list_cache itc ON itc.Payment_Cache_ID = pc.Payment_Cache_ID WHERE itc.Status = 'removed' $search_by_patient_number  ORDER BY itc.Transaction_Date_And_Time DESC LIMIT 200") or die(mysqli_error($conn));

    $sn = 1;
    while ($row_pre = mysqli_fetch_assoc($select_pre)) {
        $Transaction_Type = $row_pre['Transaction_Type'];
        $Transaction_Date_And_Time = $row_pre['Transaction_Date_And_Time'];
        $Payment_Item_Cache_List_ID = $row_pre['Payment_Item_Cache_List_ID'];
        $Payment_Cache_ID = $row_pre['Payment_Cache_ID'];
        $registrationNumber = $row_pre['Registration_ID']; 
        $checkInType = $row_pre['Check_In_Type'];
        $status = $row_pre['Status'];

    $select_removed_patient_items_list = "SELECT pr.Patient_Name,pr.Registration_ID,sp.Guarantor_Name,pr.Gender,pr.Member_Number FROM tbl_patient_registration pr JOIN tbl_sponsor sp ON pr.Sponsor_ID = sp.Sponsor_ID  WHERE pr.Registration_ID='$registrationNumber' $search  LIMIT 50";

    
    $removalResult = mysqli_query($conn,$select_removed_patient_items_list) or die(mysqli_error($conn));
    
    while($removal_row = mysqli_fetch_assoc($removalResult)) {
        
        ?>
        <tr>
        <td> <?=$sn?></td>
        <td > <?=$status?></td>

        <td><a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=<?=$removal_row['Registration_ID']?>&Transaction_Type=<?=$Transaction_Type?>&Payment_Cache_ID=<?=$Payment_Cache_ID?>&Status=<?=$status?>&Check_In_Type=<?=$removal_row['Check_In_Type']?>&NR=True&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type=<?=$checkInType?>' target='_parent' style='text-decoration: none;$change_color2'><?=$removal_row["Patient_Name"]?></a></td>
        <td> <a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=<?=$removal_row['Registration_ID']?>&Transaction_Type=<?=$Transaction_Type?>&Payment_Cache_ID=<?=$Payment_Cache_ID?>&Status=<?=$status?>&Check_In_Type=<?=$removal_row['Check_In_Type']?>&NR=True&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type=<?=$checkInType?>' target='_parent' style='text-decoration: none;$change_color2'><?=$removal_row["Registration_ID"]?></a></td>
        <td> <a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=<?=$removal_row['Registration_ID']?>&Transaction_Type=<?=$Transaction_Type?>&Payment_Cache_ID=<?=$Payment_Cache_ID?>&Status=<?=$status?>&Check_In_Type=<?=$removal_row['Check_In_Type']?>&NR=True&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type=<?=$checkInType?>' target='_parent' style='text-decoration: none;$change_color2'><?=$removal_row["Guarantor_Name"]?></a></td>
        <td> <a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=<?=$removal_row['Registration_ID']?>&Transaction_Type=<?=$Transaction_Type?>&Payment_Cache_ID=<?=$Payment_Cache_ID?>&Status=<?=$status?>&Check_In_Type=<?=$removal_row['Check_In_Type']?>&NR=True&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type=<?=$checkInType?>' target='_parent' style='text-decoration: none;$change_color2'><?=$removal_row["Gender"]?></a></td>
        <td> <a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=<?=$removal_row['Registration_ID']?>&Transaction_Type=<?=$Transaction_Type?>&Payment_Cache_ID=<?=$Payment_Cache_ID?>&Status=<?=$status?>&Check_In_Type=<?=$removal_row['Check_In_Type']?>&NR=True&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type=<?=$checkInType?>' target='_parent' style='text-decoration: none;$change_color2'><?=$Transaction_Date_And_Time?></a></td>
        <td> <a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=<?=$removal_row['Registration_ID']?>&Transaction_Type=<?=$Transaction_Type?>&Payment_Cache_ID=<?=$Payment_Cache_ID?>&Status=<?=$status?>&Check_In_Type=<?=$removal_row['Check_In_Type']?>&NR=True&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type=<?=$checkInType?>' target='_parent' style='text-decoration: none;$change_color2'><?=$removal_row["Member_Number"]?></a></td>
        <td> <a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=<?=$removal_row['Registration_ID']?>&Transaction_Type=<?=$removal_row['Transaction_Type']?>&Payment_Cache_ID=<?=$removal_row['Payment_Cache_ID']?>&Status=<?=$removal_row['Status']?>&Check_In_Type=<?=$removal_row['Check_In_Type']?>&NR=True&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type=<?=$removal_row['Check_In_Type']?>' target='_parent' style='text-decoration: none;$change_color2'><?=$Transaction_Type?></a></td>
        

        </tr>
        <?php
        $sn++;
    }
}

    