<link rel="stylesheet" href="table.css" media="screen">
<?php
include("./includes/connection.php");
$temp = 1;

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
if(isset($_GET['Phone_Number'])){
    $Phone_Number = $_GET['Phone_Number'];
}else{
    $Phone_Number = '';
}

//today function
	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
	//end

//Find the current date to filter check in list

$Today_Date = mysqli_query($conn,"select now() as today");
while($row = mysqli_fetch_array($Today_Date)){
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

echo '<center><table width =100%>';
echo '<tr id="thead">
        <td style="width:5%;"><b>SN</b></td>
	        <td><b>PATIENT NAME</b></td>
			 <td><b>PATIENT NO</b></td>
	         <td><b>GENDER</b></td>
			<td><b>AGE</b></td>
	        <td><b>SPONSOR</b></td>
	         <td><b>DIRECTED FROM</b></td>
	         <td><b>PHONE NUMBER</b></td>
               </tr>';
$select_Filtered_Patients = mysqli_query($conn,"SELECT 'payment' as Status_From,pr.Patient_Name as Patient_Name,
		  pr.Date_Of_Birth as Date_Of_Birth,pp.Patient_Payment_ID as payment_id,pr.Gender as Gender,pp.Sponsor_Name as Sponsor_Name,
		  pr.Registration_ID as Registration_ID,pr.Phone_Number as Phone_Number,pp.Receipt_Date as Required_Date,il.Item_ID,
		  il.Process_Status,il.Status,il.Patient_Payment_Item_List_ID as Patient_Payment_Item_List_ID,il.Patient_Payment_ID as Patient_Payment_ID,'Revenue Center' as Doctors_Name
		  FROM 
		  tbl_patient_payment_item_list as il
		  join tbl_patient_payments as pp on pp.Patient_Payment_ID = il.Patient_Payment_ID
		  join tbl_patient_registration as pr on pr.Registration_ID =pp.Registration_ID
		  WHERE Check_In_Type ='Radiology' AND il.Status='result' AND Process_Status='image taken'
		union all

	SELECT 'cache' as Status_From,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,
	pc.Payment_Cache_ID as payment_id,pr.Gender as Gender, pc.Sponsor_Name as Sponsor_Name,
	pr.Registration_ID as Registration_ID,pr.Phone_Number as Phone_Number,pc.Receipt_Date as Required_Date,il.Item_ID,
	il.Process_Status,il.Status,il.Payment_Item_Cache_List_ID Patient_Payment_Item_List_ID,il.Payment_Cache_ID as Patient_Payment_ID,il.Consultant as Doctors_Name
	FROM 
	tbl_item_list_cache as il
	 JOIN tbl_items as i ON i.Item_ID = il.Item_ID
	 JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID
	 JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
	 WHERE Check_In_Type ='Radiology' AND il.Status='result' AND il.Process_Status='image taken' ")
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


    //$Date_Of_Birth = $row['Date_Of_Birth'];
    //AGE FUNCTION
	 $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
		
		$Item_ID=$row['Item_ID'];
		
    //RUN THE QUERY TO SELECT ITEM NAME
    $select_product_name=mysqli_query($conn,"SELECT Product_Name FROM tbl_items WHERE Item_ID='$Item_ID'");
    $Product_Name=mysqli_fetch_array($select_product_name)['Product_Name'];


    $Date_Of_Birth = $row['Date_Of_Birth'];
    if(strtolower($Product_Name) !='registration and consultation Fee'){
        echo "<tr><td style='text-align: center;' id='thead'>".$temp."</td>";

        echo "<td><a href='Doctor_View_Radiology_Results.php?Status_From=".$row['Status_From']."&Item_ID=".$row['Item_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Registration_ID=".$row['Registration_ID']."&RadiologyImageThisPage=ThisPage 'target='_parent' style='text-decoration:none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
		
		 echo "<td><a href='Doctor_View_Radiology_Results.php?Status_From=".$row['Status_From']."&Item_ID=".$row['Item_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Registration_ID=".$row['Registration_ID']."&RadiologyImageThisPage=ThisPage 'target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</td>";
		 
		 echo "<td><a href='Doctor_View_Radiology_Results.php?Status_From=".$row['Status_From']."&Item_ID=".$row['Item_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Registration_ID=".$row['Registration_ID']."&RadiologyImageThisPage=ThisPage 'target='_parent' style='text-decoration: none;'>".$row['Gender']."</td>";

        

        echo "<td><a href='Doctor_View_Radiology_Results.php?Status_From=".$row['Status_From']."&Item_ID=".$row['Item_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Registration_ID=".$row['Registration_ID']."&RadiologyImageThisPage=ThisPage ' target='_parent' style='text-decoration: none;'>".$age."</td>";

        
		
		echo "<td><a href='Doctor_View_Radiology_Results.php?Status_From=".$row['Status_From']."&Item_ID=".$row['Item_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Registration_ID=".$row['Registration_ID']."&RadiologyImageThisPage=ThisPage ' target='_parent' style='text-decoration: none;'>".$row['Sponsor_Name']."</td>";

        echo "<td><a href='Doctor_View_Radiology_Results.php?Status_From=".$row['Status_From']."&Item_ID=".$row['Item_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Registration_ID=".$row['Registration_ID']."&RadiologyImageThisPage=ThisPage ' target='_parent' style='text-decoration: none;'>".$row['Doctors_Name']."</td>";

        echo "<td><a href='Doctor_View_Radiology_Results.php?Status_From=".$row['Status_From']."&Item_ID=".$row['Item_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Registration_ID=".$row['Registration_ID']."&RadiologyImageThisPage=ThisPage 'target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</td>";

       

       // echo "<td><a href='radiologyviewimage.php?Status_From=".$row['Status_From']."&Item_ID=".$row['Item_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Registration_ID=".$row['Registration_ID']."&RadiologyImageThisPage=ThisPage  'target='_parent' style='text-decoration: none;'>".$row['Status_From']."</td>";

        $temp++;
        echo "</tr>";
    }
}     echo "</tr>";
?></table></center>

