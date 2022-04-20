
<?php
    include("./includes/connection.php");
        $Patient_Name = $_POST['Patient_Name'];
        $Product_Name = $_POST['Product_Name'];
        $Start_Date = $_POST['Start_Date'];
        $End_Date = $_POST['End_Date'];
        $Item_ID = $_POST['Item_ID'];
        //echo $Patient_Name;
       
      
        $search_result= mysqli_query($conn,"SELECT i.Item_ID, i.Product_Name,preg.Patient_Name,pp.Registration_ID,preg.Gender, preg.Phone_Number,emp.Employee_Name,ilc.Dispense_Date_Time
        from tbl_items i, tbl_patient_payments pp,tbl_patient_payment_item_list ppl, tbl_patient_registration preg,tbl_item_list_cache ilc,tbl_employee emp  where
        i.Item_ID = ppl.Item_ID and ilc.Patient_Payment_ID=pp.Patient_Payment_ID and 
        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and emp.Employee_ID=ilc.Dispensor and
        i.Consultation_Type = 'Optical' and pp.Registration_ID=preg.Registration_ID and
        pp.Transaction_status <> 'cancelled' and ilc.Patient_Payment_ID=pp.Patient_Payment_ID and ilc.Status='dispensed' and i.Item_ID='$Item_ID' and preg.Patient_Name like '%$Patient_Name%' and
        pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date' group by ilc.Dispense_Date_Time") or die(mysqli_error($conn));
        $temp = 0;
        if(mysqli_num_rows($search_result)>0){            
        while($data = mysqli_fetch_assoc($search_result)){
            $date1 = new DateTime($Today);
            $date2 = new DateTime($data['Date_Of_Birth']);
            $diff = $date1 -> diff($date2);
            $age = $diff->y." Years, ";
            $age .= $diff->m." Months, ";
            $age .= $diff->d." Days";
            $product_name.=$data['Product_Name'].",";
                echo "<tr>";
                echo "<td style='text-align:left'>".++$temp."</td>";
                echo "<td>".ucwords(strtolower($data['Patient_Name']))."</td>";
                echo "<td>".$data['Registration_ID']."</td>";
                // echo "<td>".$data['Product_Name']."</td>";
                echo "<td>".$data['Gender']."</td>";
                echo "<td>".$data['Phone_Number']."</td>";
                echo "<td>".$data['Dispense_Date_Time']."</td>";
                echo "<td>".$data['Employee_Name']."</td>";
                echo "</tr>";
       
        }
    } else{
        echo "<tr><td colspan='11'>No result found</td></tr>";
    }
//}
?>