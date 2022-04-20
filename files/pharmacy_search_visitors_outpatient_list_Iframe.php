<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    session_start();
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    
    # GET CURRENT TODAY TIME AND DATE
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age ='';
    }

    $Yest = new DateTime('yesterday');
    $Yesterday = $Yest->format('Y-m-d').substr($original_Date, 10);
?>

<!-- CUSTOM STYLES -->
<style>
    .head-section{
        /* font-family: Arial, Helvetica, sans-serif; */
        border-collapse: collapse;
    }
    .head-section td{
        padding: 8px !important;
        border: 1px solid #ddd;
        font-size: 14px !important;
    }
    .head-section tr{
        background-color: #fff;
    }
    .head-section a:hover{
        color: #ddd;
    }
    .theads{
        background-color: red;
    }
</style>
<!-- CUSTOM STYLES -->
	
<center>
    <table width =100% class="head-section" style="font-family: Arial;">
        <tr id="theads" style="background-color: #eee;">
            <td style="width:5%;" >S/N</td>
            <td>PATIENT NAME</td>
            <td width='13.7%'>PATIENT NUMBER</td>
            <td width='13.7%'>SPONSOR</td>
            <td width='13.7%'>AGE</td>
            <td width='13.7%'>GENDER</td>
            <td width='13.7%'>PHONE NUMBER</td>
            <td width='13.7%'>MEMBER NUMBER</td>
        </tr>
        <tbody style="background-color: #fff;">
        <?php
            
                $select_Filtered_Patients = mysqli_query($conn,"SELECT pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
                                                                FROM tbl_patient_registration pr, tbl_sponsor sp WHERE pr.sponsor_id = sp.sponsor_id 
                                                                ORDER BY Registration_ID Desc limit 20") or die(mysqli_error($conn));
            

            while($row = mysqli_fetch_array($select_Filtered_Patients)){
                $date1 = new DateTime($Today);
                $date2 = new DateTime($row['Date_Of_Birth']);
                $diff = $date1 -> diff($date2);
                $age = $diff->y." Years, ";
                $age .= $diff->m." Months, ";
                $age .= $diff->d." Days";
            
                echo "<tr><td width ='2%'>".$temp."<td><a href='new_pharmacy_othersworks_page.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
                echo "<td><a href='new_pharmacy_othersworks_page.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
                echo "<td><a href='new_pharmacy_othersworks_page.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
                echo "<td><a href='new_pharmacy_othersworks_page.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
                echo "<td><a href='new_pharmacy_othersworks_page.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
                echo "<td><a href='new_pharmacy_othersworks_page.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
                echo "<td><a href='new_pharmacy_othersworks_page.php?Registration_ID=".$row['Registration_ID']."&PharmacyPatientBilling=PharmacyPatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td></tr>";
                $temp++;
            }
        ?>
        </tbody>
    </table>
</center>

