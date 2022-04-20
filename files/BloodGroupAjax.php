<?php
    include("./includes/connection.php");
	
	if(isset($_GET['Blood_ID'])){
        $Blood_ID = $_GET['Blood_ID'];
    }else{
        $Blood_ID = "";
    }
	
	if(isset($_GET['Blood_Batch'])){
        $Blood_Batch = $_GET['Blood_Batch'];
    }else{
        $Blood_Batch = "";
    }
	
	
$select_blood=mysqli_query($conn,"SELECT Blood_Group FROM tbl_patient_blood_data as d 
where d.Blood_ID='$Blood_ID' ") or die (mysqli_error($conn));

//$bloodtotal=mysqli_num_rows($select_blood);
//if($bloodtotal>0){
while($row=mysqli_fetch_array($select_blood)){
$Blood_Group=$row['Blood_Group'];

//Blood_Group='$Blood_Group' 
}
 //}
 


echo $Blood_Group;
