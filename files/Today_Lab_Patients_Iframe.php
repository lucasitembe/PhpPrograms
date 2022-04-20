<!--<link rel="stylesheet" href="table.css" media="screen">-->

<?php
include("./includes/connection.php");
$temp = 1;
if(isset($_POST['action'])){
    if($_POST['action']=='filter'){
     $txt=$_POST['patientlist'];
     $fromDate=$_POST['date_From'];
     $toDate=$_POST['date_To'];
    }  else {
        $txt=$_POST['txt']; 
    }
}  else {
    $txt='';
}


echo '<center><table width =100%  class="display" id="labPatients">
     <thead>
        <tr><th>SN</th>
	    <th>PATIENT NAME</th>
                <th>PATIENT NUMBER</th>
		    <th>SPONSOR</th>
			<th>AGE</th>
			    <th>GENDER</th>
                            <th>REGION</th>
				<th>PHONE NUMBER</th>
                                <th>DIRECTED FROM</th>
				    </tr> 
                                     </thead>';
$Current_Date=date("Y-m-d");

  if(isset($_POST['action'])){
    if($_POST['action']=='filter'){
     $fromDate=$_POST['date_From'];
     $toDate=$_POST['date_To'];   
        
     if(($txt=="All Patients") || ($txt=="Chagua Orodha Ya Wagonjwa")){
      
    $select_Filtered_Patients ="SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID AND Transaction_Date_And_Time BETWEEN '$fromDate' AND '$toDate' GROUP BY tbl_patient_registration.Registration_ID ORDER BY test_result_ID ASC";  
        
    }  else {
    
    $select_Filtered_Patients ="SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID AND Transaction_Date_And_Time BETWEEN '$fromDate' AND '$toDate' GROUP BY tbl_patient_registration.Registration_ID AND Billing_Type='".$txt."' ORDER BY test_result_ID ASC"; //Billing_Type='".$txt."'
    }    
        
    
        
    }  else {
        
    if(($txt=="All Patients") || ($txt=="Chagua Orodha Ya Wagonjwa")){
      
    $select_Filtered_Patients ="SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID GROUP BY tbl_patient_registration.Registration_ID ORDER BY test_result_ID ASC";  
        
    }  else {
    
    $select_Filtered_Patients ="SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID GROUP BY tbl_patient_registration.Registration_ID AND Billing_Type='".$txt."' ORDER BY test_result_ID ASC"; //Billing_Type='".$txt."'
    } 
    
    } 

    }  else {
        
     $select_Filtered_Patients ="SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID GROUP BY tbl_patient_registration.Registration_ID ORDER BY test_result_ID ASC";
        
}

        $result=mysqli_query($conn,$select_Filtered_Patients)or die(mysqli_error($conn));
                                        //date manipulation to get the patient age
        $Today_Date = mysqli_query($conn,"select now() as today");
            while($row = mysqli_fetch_array($Today_Date)){
                $original_Date = $row['today'];
                $new_Date = date("Y-m-d", strtotime($original_Date));
                $Today = $new_Date;
                $age ='';
            }

            $num_rows=  mysqli_num_rows($result);
            if($num_rows>0){
                 while($row = mysqli_fetch_array($result)){
                  $Date_Of_Birth = $row['Date_Of_Birth'];
                  $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";

                    $date1 = new DateTime($Today);
                    $date2 = new DateTime($Date_Of_Birth);
                    
                    $diff = $date1 -> diff($date2);
                    
                    $age = $diff->y." Years, ";
                    $age .= $diff->m." Months, ";
                    $age .= $diff->d." Days";


                    if($row['Status'] =='Sample Collected'){
                        
                                echo "<tr bgcolor='lightgreen'>
                                <td>".$temp."</td>
                                <td>".$row['Patient_Name']."</td>";         
                                }else{
                                echo "<tr><td>".$temp."</td>
                                    
                                
                                <td>".$row['Patient_Name']."</td>";
                             
                                }
                                 echo "<td>".$row['Registration_ID']."</td>";
                                echo "<td>".$row['Sponsor_Name']."</td>";
                                echo "<td>".$age."</td>";
                                echo "<td>".$row['Gender']."</td>";
                                echo "<td>".$row['Consultant']."</td>";
                                echo "<td>".$row['Phone_Number']."</td>";
                                echo "<td>".$row['Consultant']."</td>";

                                $temp++;
                                        } 
            }  else {
                
                echo '<p style="color:red;font-weight:bold">No records found</p>';   
            }

?>

