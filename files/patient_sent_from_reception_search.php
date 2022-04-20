<?php

include("./includes/connection.php");
if(isset($_POST['msamahasearch'])){
    $Patient_Number=$_POST['Patient_Number'];
    $Patient_Name=$_POST['Patient_Name'];
    $fitlter='';
    if($Patient_Name != ''){
        $fitlter .=" AND pr.Patient_Name LIKE  '$Patient_Name'";
    }

    if($Patient_Number != ''){
        $fitlter .=" AND ds.Registration_ID =  '$Patient_Number'";
    }
    $select2=mysqli_query($conn,"SELECT pr.Phone_Number,Debt_social_ID, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth,sent_date,Guarantor_Name, ds.Registration_ID FROM tbl_sponsor s, tbl_patient_registration pr, tbl_patient_debt_to_socialwalfare ds WHERE s.Sponsor_ID=pr.Sponsor_ID AND pr.Registration_ID=ds.Registration_ID $fitlter  AND Debt_social_ID NOT IN (SELECT Debt_social_ID FROM tbl_social_reduce_debt)") or die(mysqli_error($conn));
    while($row2=mysqli_fetch_assoc($select2)){
        
    $Date_Of_Birth = $row2['Date_Of_Birth'];
    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1 -> diff($date2);
    $age = $diff->y." Years, ";
    $age .= $diff->m." Months, ";
    $age .= $diff->d." Days";
    ?>
    <tr>
    <td><a href="approve_debit_bills.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Debt_social_ID=<?php echo $row2['Debt_social_ID']; ?>" style="text-decoration: none;"><?php echo ++$temp; ?></td>
    <td><a href="approve_debit_bills.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Debt_social_ID=<?php echo $row2['Debt_social_ID']; ?>" style="text-decoration: none;"><?php echo strtoupper($row2['Patient_Name']); ?></a></td>
    <td><a href="approve_debit_bills.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Debt_social_ID=<?php echo $row2['Debt_social_ID']; ?>" style="text-decoration: none;"><?php echo $row2['Registration_ID']; ?></a></td>
    <td><a href="approve_debit_bills.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Debt_social_ID=<?php echo $row2['Debt_social_ID']; ?>" style="text-decoration: none;"><?php echo $row2['Guarantor_Name']; ?></a></td>
    <td><a href="approve_debit_bills.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Debt_social_ID=<?php echo $row2['Debt_social_ID']; ?>" style="text-decoration: none;"><?php echo strtoupper($row2['Gender']); ?></a></td>
    <td><a href="approve_debit_bills.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Debt_social_ID=<?php echo $row2['Debt_social_ID']; ?>" style="text-decoration: none;"><?php echo $age; ?></a></td>
    <td><a href="approve_debit_bills.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Debt_social_ID=<?php echo $row2['Debt_social_ID']; ?>" style="text-decoration: none;"><?php echo $row2['sent_date']; ?></a></td>
    <td><a href="approve_debit_bills.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Debt_social_ID=<?php echo $row2['Debt_social_ID']; ?>" style="text-decoration: none;"><?php echo $row2['Member_Number']; ?></a></td>
    </tr>
    <?php
    }

}

    if(isset($_POST['cashdepositsearch'])){
        $Patient_Number=$_POST['Patient_Number'];
        $Patient_Name=$_POST['Patient_Name'];
        $fitlter='';
        if($Patient_Name != ''){
            $fitlter .=" AND pr.Patient_Name LIKE  '$Patient_Name'";
        }
    
        if($Patient_Number != ''){
            $fitlter .=" AND pd.Registration_ID =  '$Patient_Number'";
        }        
        $select2 = mysqli_query($conn,"SELECT pr.Phone_Number,ds.Debt_ID,Patient_debt_amount, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth,ds.created_at, Guarantor_Name, pd.Registration_ID, debit_status FROM tbl_sponsor s, tbl_patient_registration pr, tbl_debt_cash_deposit ds, tbl_patient_debt pd WHERE s.Sponsor_ID=pr.Sponsor_ID AND pr.Registration_ID=pd.Registration_ID AND ds.Debt_ID= pd.Debt_ID AND debit_status='Active' $fitlter ") or die(mysqli_error($conn));
        while($row2 = mysqli_fetch_assoc($select2)){
            $Pqfl = $row2['Patient_debt_amount'];
            $Date_Of_Birth = $row2['Date_Of_Birth'];
            $Registration_ID = $row2['Registration_ID'];
            $debit_status = $row2['debit_status'];
            $created_at = $row2['created_at'];
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1 -> diff($date2);
            $age = $diff->y." Years, ";
            $age .= $diff->m." Months, ";
            $age .= $diff->d." Days";

            
    ?>
        <tr>
            <td><a href="Cash_deposit_debit.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&robsq=<?php echo $row2['Debt_ID']; ?>&Pqfl=<?php echo $Pqfl; ?>" style="text-decoration: none;"><?php echo ++$temp; ?></td>
            <td><a href="Cash_deposit_debit.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&robsq=<?php echo $row2['Debt_ID']; ?>&Pqfl=<?php echo $Pqfl; ?>" style="text-decoration: none;"><?php echo strtoupper($row2['Patient_Name']); ?></a></td>
            <td><a href="Cash_deposit_debit.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&robsq=<?php echo $row2['Debt_ID']; ?>&Pqfl=<?php echo $Pqfl; ?>" style="text-decoration: none;"><?php echo $row2['Registration_ID']; ?></a></td>
            <td><a href="Cash_deposit_debit.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&robsq=<?php echo $row2['Debt_ID']; ?>&Pqfl=<?php echo $Pqfl; ?>" style="text-decoration: none;"><?php echo $row2['Guarantor_Name']; ?></a></td>
            <td><a href="Cash_deposit_debit.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&robsq=<?php echo $row2['Debt_ID']; ?>&Pqfl=<?php echo $Pqfl; ?>" style="text-decoration: none;"><?php echo strtoupper($row2['Gender']); ?></a></td>
            <td><a href="Cash_deposit_debit.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&robsq=<?php echo $row2['Debt_ID']; ?>&Pqfl=<?php echo $Pqfl; ?>" style="text-decoration: none;"><?php echo $age; ?></a></td>
            <td><a href="Cash_deposit_debit.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&robsq=<?php echo $row2['Debt_ID']; ?>&Pqfl=<?php echo $Pqfl; ?>" style="text-decoration: none;"><?php echo $row2['created_at']; ?></a></td>
            <td><a href="Cash_deposit_debit.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&robsq=<?php echo $row2['Debt_ID']; ?>&Pqfl=<?php echo $Pqfl; ?>" style="text-decoration: none;"><?php echo $debit_status; ?></a></td>
        </tr>
    <?php
        }
       
    }