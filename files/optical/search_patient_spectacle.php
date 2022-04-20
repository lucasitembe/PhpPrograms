
<?php
    include("./includes/connection.php");
        $Sub_Department_ID = $_POST['Sub_Department_ID'];
    
    
   // if(isset($_POST['Search_group_name'])){

        $Patient_Name = $_POST['Patient_Name'];
        //echo $Patient_Name;
      
        $search_result= mysqli_query($conn,"SELECT pc.Registration_ID,it.Product_Name, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, sp.Guarantor_Name, sd.Sub_Department_Name,
        preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
        preg.Member_Number, ilc.Transaction_Type,ilc.Dispense_Date_Time,emp.Employee_Name from
        tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sponsor sp, tbl_sub_department sd,tbl_items it,tbl_employee emp where
        pc.payment_cache_id = ilc.payment_cache_id and
        sd.Sub_Department_ID = ilc.Sub_Department_ID and
        preg.registration_id = pc.registration_id and
        sp.Sponsor_ID = preg.Sponsor_ID and emp.Employee_ID=ilc.Dispensor and
        ilc.status = 'dispensed' and it.Item_ID=ilc.Item_ID  and 
        ilc.Check_In_Type = 'Optical' and preg.Patient_Name LIKE '%$Patient_Name%' and
        ilc.Sub_Department_ID = '$Sub_Department_ID' and (Billing_Type = 'Outpatient Cash' or Billing_Type = 'Outpatient Credit' or Billing_Type = 'Inpatient Cash' or Billing_Type = 'Inpatient Credit') and (ilc.Transaction_Type = 'Cash' or ilc.Transaction_Type = 'Credit')
        group by pc.payment_cache_id order by pc.Payment_Cache_ID desc limit 100") or die(mysqli_error($conn));
        $temp = 0;
        if(mysqli_num_rows($search_result)>0){            
        while($data = mysqli_fetch_assoc($search_result)){
                $temp++;
                $date1 = new DateTime($Today);
                $date2 = new DateTime($data['Date_Of_Birth']);
                $diff = $date1 -> diff($date2);
                $age = $diff->y." Years, ";
                $age .= $diff->m." Months, ";
                $age .= $diff->d." Days";
                $Registration_ID = $data['Registration_ID'];
                    $Dispense_date = $data['Dispense_Date_Time'];
                    $Payment_Cache_ID = $data['Payment_Cache_ID'];
                // $product_name.=$data['Product_Name'].",";
               // echo "<tbody id='search_result'>";
               $select_item = mysqli_query($conn,"SELECT it.Product_Name from
                    tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_sub_department sd,tbl_items it where
                    pc.payment_cache_id = ilc.payment_cache_id and  sd.Sub_Department_ID = ilc.Sub_Department_ID and ilc.status = 'dispensed' and it.Item_ID=ilc.Item_ID and  ilc.Check_In_Type = 'Optical' and 
                    ilc.Sub_Department_ID = '$Sub_Department_ID' AND pc.Registration_ID='$Registration_ID' AND DATE(Dispense_Date_Time)=DATE('$Dispense_date') AND ilc.Payment_Cache_ID='$Payment_Cache_ID'") or die(mysqli_error($conn));
                    
                   $product ="";
                   while($row_item=mysqli_fetch_assoc($select_item)){

                        $pname=$row_item['Product_Name'];
                        $product.=$pname."</br> ";
                    }
                    echo "<tr>";
                    echo "<td style='text-align:left'>".++$temp."</td>";
                    echo "<td>".ucwords(strtolower($data['Patient_Name']))."</td>";
                    echo "<td>".$data['Registration_ID']."</td>";
                    echo "<td>".$data['Guarantor_Name']."</td>";
                    echo "<td>".$age."</td>";
                    echo "<td>".$data['Gender']."</td>";
                    echo "<td>".$data['Phone_Number']."</td>";
                    echo "<td>".$data['Sub_Department_Name']."</td>";
                    echo "<td>".$product."</td>";
                    echo "<td>".$data['Dispense_Date_Time']."</td>";
                    echo "<td>".$data['Employee_Name']."</td>";
                    echo "</tr>";
              //  echo "</tbody id='body'>";
       
        }
    } else{
        echo "<tr><td colspan='11'>No result found</td></tr>";
    }
//}
?>