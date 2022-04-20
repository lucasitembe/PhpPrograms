<?php

include("./includes/connection.php");


  $first_name=  mysqli_real_escape_string($conn,$_POST['first_name']);
  $last_name= mysqli_real_escape_string($conn,$_POST['last_name']);
  $relative_phone_number= mysqli_real_escape_string($conn,$_POST['relative_phone_number']);
  $relative_Address= mysqli_real_escape_string($conn,$_POST['relative_Address']);
  $relationship_type= mysqli_real_escape_string($conn,$_POST['relationship_type']);
  $Guarantor_Name= mysqli_real_escape_string($conn,$_POST['Guarantor_Name']);
  $Date_Of_Birth = mysqli_real_escape_string($conn,$_POST['date2']);
  $Employee_ID = mysqli_real_escape_string($conn,$_POST['Employee_ID']);
  $gender = mysqli_real_escape_string($conn,$_POST['gender']);
  
  
 $data = mysqli_query($conn,"select now() as Registration_Date_And_Time");
        while ($row = mysqli_fetch_array($data)) {
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
        }
 
          $Patient_Name = $first_name . ' ' . $last_name;
         $Insert_Sql = "INSERT INTO tbl_patient_registration(Patient_Name,Date_Of_Birth,Sponsor_ID,Phone_Number,Emergence_Contact_Name,Emergence_Contact_Number,Employee_ID,Registration_Date_And_Time,Registration_Date,gender)
	    values('$Patient_Name','$Date_Of_Birth',(select Sponsor_ID from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'),'$relative_phone_number','$relationship_type','$relative_Address','$Employee_ID','$Registration_Date_And_Time',(select now()), '$gender')";
//         $Insert_Sql = "INSERT INTO tbl_patient_registration(Patient_Name) values('aidan')";
         
          $result =  mysqli_query($conn,$Insert_Sql) or die(mysqli_error($conn));
          
           if($result){
               
                $selectThisRecord = mysqli_query($conn,"select Registration_ID  from tbl_patient_registration where
			Patient_Name = '$Patient_Name' and
			    Emergence_Contact_Name = '$relationship_type' and
			    Registration_Date_And_Time = '$Registration_Date_And_Time' and
			    Date_Of_Birth = '$Date_Of_Birth'") or die(mysqli_error($conn));

            while ($row = mysqli_fetch_array($selectThisRecord)) {
                $Registration_ID = $row['Registration_ID'];
            }
//                  echo  $direction = "./admit_register_to_motuary.php?Registration_ID=" . $Registration_ID . "&Section=Reception&PostPaid=PostPaidThisForm";
            
                     echo $Registration_ID;
                     
               
           }else {
               echo "haman kitu";
           }
        
     
         
      
