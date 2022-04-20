<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    $Controler = 'Normal';

    if(isset($_GET['Patient_Type']) && $_GET['Patient_Type'] == 'Outpatient'){ 
        echo '<legend style="background-color:#006400;color:white" align="right"><b>'.strtoupper('Others Payments ~ outpatient cash').'</b></legend>';
    }else if(isset($_GET['Patient_Type']) && $_GET['Patient_Type'] == 'Inpatient'){
        echo '<legend style="background-color:#006400;color:white" align="right"><b>'.strtoupper('Others Payments ~ inpatient cash').'</b></legend>';
    }else {
        echo '<legend style="background-color:#006400;color:white" align="right"><b>'.strtoupper('Others Payments').'</b></legend>';
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


    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    

    if(isset($_GET['Patient_Number'])){
        $Patient_Number = $_GET['Patient_Number'];   
    }else{
        $Patient_Number = '';
    }
    
    if(isset($_GET['Patient_Type'])){
        $Patient_Type = $_GET['Patient_Type'];
    }else{
        $Patient_Type = '';
    }
    
    //get today's date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	   $age ='';
    }

    //generate filter value
    if(isset($_GET['Patient_Name']) && $_GET['Patient_Name'] != null && $_GET['Patient_Name'] != ''){
        $filter = "preg.patient_name like '%$Patient_Name%' and";
    }else if(isset($_GET['Patient_Number']) && $_GET['Patient_Number'] != null && $_GET['Patient_Number'] != ''){
        $filter = "preg.Registration_ID = '$Patient_Number' and";
    }else{
        $filter = "";
    }

    if(isset($_GET['Billing_Type'])){
        $Billing_Type2 = $_GET['Billing_Type'];
    }else{
        $Billing_Type2 = '';
    }
    
    //overide if and only if inpatient prepaid is selected
    if(strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) =='yes'){
        if(isset($_GET['Patient_Type']) && $_GET['Patient_Type'] == 'Inpatient'){
            $Billing_Type2 = 'InpatientCash';
        }
    }    
    

    if(isset($_GET['Billing_Type'])){
	   $Billing_Type = $_GET['Billing_Type']; 
	if($Billing_Type == 'Outpatient Cash'){
	    //$Temp_Billing_Type = 'Outpatient Cash';
	    $sql = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit') and ilc.Transaction_Type = 'cash'";
	}elseif($Billing_Type == 'Outpatient Credit'){
	    //$Temp_Billing_Type = 'Outpatient Credit';
	    $sql = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit') and ilc.Transaction_Type = 'credit'";
	}elseif($Billing_Type == 'Inpatient Cash'){
	    //$Temp_Billing_Type = 'Inpatient Cash';
	    $sql = " (pc.billing_type = 'inpatient Cash' or pc.billing_type = 'inpatient Credit') and ilc.Transaction_Type = 'cash'";
	}elseif($Billing_Type == 'Inpatient Credit'){
	    //$Temp_Billing_Type = 'Inpatient Credit';
	    $sql = " (pc.billing_type = 'inpatient Cash' or pc.billing_type = 'inpatient Credit') and ilc.Transaction_Type = 'credit'";
	}elseif($Billing_Type == 'Patient From Outside'){
	    //$Temp_Billing_Type = 'Patient From Outside';
	    $sql = " (pc.billing_type = 'Outpatient Cashs' or pc.billing_type = 'Inpatient Cashs')";
	}else{
	    //$Temp_Billing_Type = '';
	    $sql = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit')";
	}
    }else{
        $Billing_Type = '';
        //$Temp_Billing_Type = '';
	$sql = " pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit'";
    }
    

    echo '<center><table width =100% border=0>';
    echo "<tr><td colspan='9'><hr></tr>";
    echo '<tr id="thead" style="width:5%;"><td><b>SN</b></td>
                <td width><b>STATUS</b></td>
                <td><b>PATIENT NAME</b></td>
                <td><b>PATIENT NUMBER</b></td>
                <td><b>SPONSOR</b></td>
                <td><b>AGE</b></td>
                <td><b>GENDER</b></td>
                <td><b>SUB DEPARTMENT</b></td>
                <td><b>MEMBER NUMBER</b></td>
            </tr>';
	echo "<tr><td colspan='9'><hr></tr>";
        
      $sql_new=mysqli_query($conn,"SELECT preg.Member_Number,preg.Patient_Name,preg.Date_Of_Birth,preg.Gender,preg.Phone_Number,pc.Registration_ID,pc.Transaction_Status,pc.Payment_Cache_ID,pc.Sponsor_Name FROM tbl_payment_cache pc,tbl_patient_registration preg "
                . "WHERE preg.Registration_ID = pc.Registration_ID AND $filter (pc.billing_type = 'Outpatient Cash' or pc.Billing_Type = 'Outpatient Credit') group by pc.payment_cache_id order by pc.payment_cache_id desc limit 100");
        
              while($row_one = mysqli_fetch_assoc($sql_new)){
                               $Member_Number =$row_one['Member_Number'];
                               $Patient_Name=$row_one['Patient_Name'];
                               $Date_Of_Birth =$row_one['Date_Of_Birth'];
                               $Gender =$row_one['Gender'];
                               $Phone_Number =$row_one['Phone_Number'];
                               $Registration_ID =$row_one['Registration_ID'];
                               $Transaction_Status =$row_one['Transaction_Status'];
                               $Payment_Cache_ID =$row_one['Payment_Cache_ID'];
                               $Sponsor_Name =$row_one['Sponsor_Name'];
      
                               
                 $sql_new2=mysqli_query($conn,"SELECT Transaction_Type,status,Sub_Department_ID FROM tbl_item_list_cache WHERE (status = 'active' OR status = 'approved') AND payment_cache_id='$Payment_Cache_ID' AND Check_In_Type = 'Others' group by sub_department_id");
//                   $value=mysqli_num_rows($sql_new2);
//                   ech $value;
               
                   while($row_two = mysqli_fetch_assoc($sql_new2)){
//                        echo"kjold";
                                         $Transaction_Type=$row_two['Transaction_Type'];
                                         $status=$row_two['status'];
                                        $Sub_Department_ID_new=$row_two['Sub_Department_ID'];
//                                       echo"kjold";
                                         
                    $sub_department_name =mysqli_fetch_assoc(mysqli_query($conn,"SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID='$Sub_Department_ID'"))['Sub_Department_Name'];
                     
                
        if(strtolower($status) == 'approved'){
            $change_b_color="style='background:green;color:#FFFFFF'";
            $change_color="color:#FFFFFF";
        }else{
            $change_color="";
            $change_b_color="";
        }
        echo "<tr $change_b_color><td id='thead' style='width:5%;$change_color' >".$temp."</td>";
        if(strtolower($status) == 'active'){
            echo "<td><b>Not Paid</b></td>";
        }else if(strtolower($status) == 'approved'){
            echo "<td><b>Approved</b></td>";
        }else{
            echo "<td> <b>Not Approved</b></td>";  
        } 
    
        //GENERATE PATIENT YEARS, MONTHS AND DAYS
        $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";       
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1 -> diff($date2);
        $age = $diff->y." Years, ";
        $age .= $diff->m." Months, ";
        $age .= $diff->d." Days";
    
        
        echo $Sub_Department_ID_new;
        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$Registration_ID."&Transaction_Type=".$Transaction_Type."&Payment_Cache_ID=".$Payment_Cache_ID."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID_new."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".ucwords(strtolower($Patient_Name))."</a></td>";   
        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$Registration_ID."&Transaction_Type=".$Transaction_Type."&Payment_Cache_ID=".$Payment_Cache_ID."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID_new."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".$Registration_ID."</a></td>"; 
        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$Registration_ID."&Transaction_Type=".$Transaction_Type."&Payment_Cache_ID=".$Payment_Cache_ID."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID_new."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".$Sponsor_Name."</a></td>";        
        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$Registration_ID."&Transaction_Type=".$Transaction_Type."&Payment_Cache_ID=".$Payment_Cache_ID."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID_new."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".$age."</a></td>";        
        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$Registration_ID."&Transaction_Type=".$Transaction_Type."&Payment_Cache_ID=".$Payment_Cache_ID."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID_new."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".$Gender."</a></td>";      
        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$Registration_ID."&Transaction_Type=".$Transaction_Type."&Payment_Cache_ID=".$Payment_Cache_ID."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID_new."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".$Sub_Department_Name."</a></td>";     
        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$Registration_ID."&Transaction_Type=".$Transaction_Type."&Payment_Cache_ID=".$Payment_Cache_ID."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID_new."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".$Member_Number."</a></td>";
        echo "</tr>"; 
        $temp++;
   
                       
                   }
                              
                  
              }
//    }
?>
</table>
</center>