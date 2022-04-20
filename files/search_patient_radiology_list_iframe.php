<link rel="stylesheet" href="table.css" media="screen">
<?php
//get sub department id
if($_GET['Sub_Department_ID'] !=''){
    $Sub_Department_ID=$_GET['Sub_Department_ID'];
}else{
    $Sub_Department_ID='';
}
?>
<?php
    include("./includes/connection.php");
    $temp = 1;
  $select_now =mysqli_query($conn,"SELECT NOW() as DateGiven");
  $row5 = mysqli_fetch_array($select_now);


if(isset($_GET['Patient_Name']) and isset($_GET['DateGiven_From']) and isset($_GET['DateGiven_From'])){
 $Patient_Name = $_GET['Patient_Name'];
 if($_GET['DateGiven_To'] == ''){
 $DateGiven_To = date('Y-m-d',strtotime($row5['DateGiven']));
 }else{
 $DateGiven_To = $_GET['DateGiven_To'];
  }
  if($_GET['DateGiven_From'] == ''){
$DateGiven_From = date('Y-m-d',strtotime($row5['DateGiven']));
  }else{
  $DateGiven_From = $_GET['DateGiven_From'];
    } 

 }else{

   $Patient_Name = '';
   $DateGiven_From = date('Y-m-d',strtotime($row5['DateGiven']));
    $DateGiven_To = date('Y-m-d',strtotime($row5['DateGiven']));
                                          }

    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
       $original_Date = $row['today'];
       $new_Date = date("Y-m-d", strtotime($original_Date));
       $Today = $new_Date; 
    }
    echo '<center><table width =100% border=0><tr id="thead">';
    echo ' <td STYLE="width:3%"><b>SN</b></td>
	     <td><b>PATIENT NAME</b></td>
               <td><b>SPONSOR</b></td>
                    <td><b>AGE</b></td>
                        <td><b>GENDER</b></td>
                        <td><b>DIRECTED FROM</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                <td><b>REGISTRATION NUMBER</b></td>
                                <td><b>STATUS</b></td></tr>';
            $select_Filtered_Patients = mysqli_query($conn,"SELECT 'payment' as Status_From,pp.Billing_Type AS Transaction_Type,pr.Patient_Name as Patient_Name,
                          pr.Date_Of_Birth as Date_Of_Birth,pp.Patient_Payment_ID as payment_id,pr.Gender as Gender,pp.Sponsor_Name as Sponsor_Name,
                          pr.Registration_ID as Registration_ID,pr.Phone_Number as Phone_Number,pp.Receipt_Date as Required_Date,il.Item_ID,
                          il.Process_Status,il.Status,il.Patient_Payment_Item_List_ID as Patient_Payment_Item_List_ID,il.Patient_Payment_ID as Patient_Payment_ID,'Revenue Center' as Doctors_Name FROM tbl_patient_payment_item_list as il
                          join tbl_patient_payments as pp on pp.Patient_Payment_ID = il.Patient_Payment_ID
                          join tbl_patient_registration as pr on pr.Registration_ID =pp.Registration_ID
                          WHERE Check_In_Type ='Radiology' AND il.Status='active' AND Process_Status='not served'
                        union all

                        SELECT 'cache' as Status_From,il.Transaction_Type AS Transaction_Type,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,
                        pc.Payment_Cache_ID as payment_id,pr.Gender as Gender, pc.Sponsor_Name as Sponsor_Name,
                        pr.Registration_ID as Registration_ID,pr.Phone_Number as Phone_Number,pc.Receipt_Date as Required_Date,il.Item_ID,
                        il.Process_Status,il.Status,il.Payment_Item_Cache_List_ID Patient_Payment_Item_List_ID,il.Payment_Cache_ID as Patient_Payment_ID,il.Consultant as Doctors_Name FROM tbl_item_list_cache as il
                         JOIN tbl_items as i ON i.Item_ID = il.Item_ID
                         JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID
                         JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
                         WHERE Check_In_Type ='Radiology'
			 AND il.Status='active'
			 AND il.Process_Status='inactive'
			AND (il.Transaction_Type='credit' OR il.Transaction_Type='cash' AND il.Status='active' OR il.Status='paid')
				and il.Sub_Department_ID='$Sub_Department_ID'
				GROUP BY payment_id
			 ")
            or die(mysqli_error($conn));
                                                                                                 
    
//date manipulation to get the patient age
$Today_Date = mysqli_query($conn,"select now() as today");
 while($row = mysqli_fetch_array($Today_Date)){
 $original_Date = $row['today'];
 $new_Date = date("Y-m-d", strtotime($original_Date));
 $Today = $new_Date;
$age ='';
            }

            
while($row = mysqli_fetch_array($select_Filtered_Patients)){


 $Date_Of_Birth = $row['Date_Of_Birth'];
$age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
// if($age == 0){

$date1 = new DateTime($Today);
$date2 = new DateTime($Date_Of_Birth);
$diff = $date1 -> diff($date2);
$age = $diff->y." Years, ";
$Item_ID=$row['Item_ID'];
    //RUN THE QUERY TO SELECT ITEM NAME
    $select_product_name=mysqli_query($conn,"SELECT Product_Name FROM tbl_items WHERE Item_ID='$Item_ID'");
    $Product_Name=mysqli_fetch_array($select_product_name)['Product_Name'];


   $Date_Of_Birth = $row['Date_Of_Birth'];
  if(strtolower($Product_Name) !='registration and consultation Fee'){
      echo "<tr><td style='text-align: center;'>".$temp."</td>";

      echo "<td><a href='radiologyviewimage.php?Status_From=".$row['Status_From']."&Item_ID=".$row['Item_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Registration_ID=".$row['Registration_ID']."&RadiologyImageThisPage=ThisPage 'target='_parent' style='text-decoration:none;'>".$row['Patient_Name']."</a></td>";

      echo "<td><a href='radiologyviewimage.php?Status_From=".$row['Status_From']."&Item_ID=".$row['Item_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Registration_ID=".$row['Registration_ID']."&RadiologyImageThisPage=ThisPage ' target='_parent' style='text-decoration: none;'>".$row['Sponsor_Name']."</td>";

      echo "<td><a href='radiologyviewimage.php?Status_From=".$row['Status_From']."&Item_ID=".$row['Item_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Registration_ID=".$row['Registration_ID']."&RadiologyImageThisPage=ThisPage ' target='_parent' style='text-decoration: none;'>".$age."</td>";

      echo "<td><a href='radiologyviewimage.php?Status_From=".$row['Status_From']."&Item_ID=".$row['Item_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Registration_ID=".$row['Registration_ID']."&RadiologyImageThisPage=ThisPage 'target='_parent' style='text-decoration: none;'>".$row['Gender']."</td>";

      echo "<td><a href='radiologyviewimage.php?Status_From=".$row['Status_From']."&Item_ID=".$row['Item_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Registration_ID=".$row['Registration_ID']."&RadiologyImageThisPage=ThisPage ' target='_parent' style='text-decoration: none;'>".$row['Doctors_Name']."</td>";

      echo "<td><a href='radiologyviewimage.php?Status_From=".$row['Status_From']."&Item_ID=".$row['Item_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Registration_ID=".$row['Registration_ID']."&RadiologyImageThisPage=ThisPage 'target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</td>";

      echo "<td><a href='radiologyviewimage.php?Status_From=".$row['Status_From']."&Item_ID=".$row['Item_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Registration_ID=".$row['Registration_ID']."&RadiologyImageThisPage=ThisPage 'target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</td>";

      echo "<td>";?>
	<?php
	    if(strtolower($row['Transaction_Type'])=='outpatient credit' || strtolower($row['Transaction_Type'])=='outpatient cash'){
		//$Transaction_Type=$row['Transaction_Type'];
		$Transaction_Type=explode(' ',$row['Transaction_Type'])[1];
		if(strtolower($row['Status'])=='paid' && strtolower($Transaction_Type)=='cash'){
		    echo "PAID";
		}elseif(strtolower($row['Status'])=='active' && strtolower($Transaction_Type)=='cash'){
		    echo "NOT PAID";
		}elseif(strtolower($row['Status'])=='active' && strtolower($Transaction_Type)=='credit'){
		    echo "NOT BILLED";
		}else{
		    echo "NOT PROCESSED";
		}		
		
	    }else{
		if(strtolower($row['Status'])=='paid' && strtolower($row['Transaction_Type'])=='cash'){
		    echo "PAID";
		}elseif(strtolower($row['Status'])=='active' && strtolower($row['Transaction_Type'])=='cash'){
		    echo "NOT PAID";
		}elseif(strtolower($row['Status'])=='active' && strtolower($row['Transaction_Type'])=='credit'){
		    echo "NOT BILLED";
		}else{
		    echo "NOT PROCESSED";
		}
	    }
	?>
      
      <?php echo "</td>";

      $temp++;
      echo "</tr>";
  }
                                                                        }   
                                                                        
                                                                    ?>
                                </table>
                            </center>