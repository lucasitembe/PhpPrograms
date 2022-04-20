



<?php
   
    include("./includes/connection.php");
   
  
  
  
  if(isSet($_POST['d']))
{
    
  $date1 = $_POST['d'];
  $date2 = $_POST['d2'];
  $selecttype = $_POST['sel'];
  
 
 
  
  
  
 if ($selecttype=='new'){
    
    $select = mysqli_query($conn,"select pr.Patient_Name as pn,pr.Date_Of_Birth,pr.Member_Number,vs.arv_therapy as the,fv.partiner_name,vs.curr_hiv_status as st,pr.Gender,fv.muda as f from tbl_patient_registration pr,tbl_hiv_first_visit fv,tbl_hiv_visits vs
    where pr.registration_id = fv.pr_r AND DATE(muda) >= '$date1' AND DATE(muda)<= '$date2'
    AND  fv.hiv_id = vs.first_v_id GROUP BY first_v_id
    
    ") or die(mysqli_error($conn));
							

  
    echo "<table>";
    echo "<tr><th>First</th><th>LastName</th><th>CURRENT HIV STATUS</th><th>ARV_THERAPY</th></tr>";
    while($row=mysqli_fetch_array($select)){
	
	
	
	
	$fname= explode(' ',$row['pn'])[0];
	$mname='';
	if(sizeof(explode(' ',$row['pn']))>= 3){
	$mname=explode(' ',$row['pn'])[sizeof(explode(' ',$row['pn'])) - 2];
			
		$lname=explode(' ',$row['pn'])[sizeof(explode(' ',$row['pn'])) - 1];}
		
		else{
		    
		$lname=explode(' ',$row['pn'])[1];
	    }
echo "<tr><td>$fname</td><td>$lname</td><td>$row[st]</td><td>$row[the]</td></tr>";
	}
    echo "</table>";
    
 }
 
 
 
 
 //if re
 
 else if ($selecttype=='re'){
    
    $select = mysqli_query($conn,"select pr.Patient_Name as pn,pr.Date_Of_Birth,pr.Member_Number,vs.arv_therapy as the,fv.partiner_name,vs.curr_hiv_status as st,pr.Gender,fv.muda as f from tbl_patient_registration pr,tbl_hiv_first_visit fv,tbl_hiv_visits vs
    where pr.registration_id = fv.pr_r AND DATE(muda_huu) >= '$date1' AND DATE(muda_huu)<= '$date2'
    AND  fv.hiv_id = vs.first_v_id GROUP BY first_v_id
    
    ") or die(mysqli_error($conn));
							

  
    echo "<table>";
    echo "<tr><th>FirstName</th><th>LastName</th><th>CURRENT HIV STATUS</th><th>ARV_THERAPY</th></tr>";
    while($row=mysqli_fetch_array($select)){
	
	
	
	
	$fname= explode(' ',$row['pn'])[0];
	$mname='';
	if(sizeof(explode(' ',$row['pn']))>= 3){
	$mname=explode(' ',$row['pn'])[sizeof(explode(' ',$row['pn'])) - 2];
			
		$lname=explode(' ',$row['pn'])[sizeof(explode(' ',$row['pn'])) - 1];}
		
		else{
		    
		$lname=explode(' ',$row['pn'])[1];
	    }
echo "<tr><td>$fname</td><td>$lname</td><td>$row[st]</td><td>$row[the]</td></tr>";
	}
    echo "</table>";
    
 }
 
 
 
 else if ($selecttype=='rev'){
    
    $select = mysqli_query($conn,"select COUNT(first_v_id) as co,pr.Patient_Name as pn,pr.Date_Of_Birth,pr.Member_Number,vs.arv_therapy as the,fv.partiner_name,vs.curr_hiv_status as st,pr.Gender,fv.muda as f from tbl_patient_registration pr,tbl_hiv_first_visit fv,tbl_hiv_visits vs
    where pr.registration_id = fv.pr_r AND DATE(muda_huu) >= '$date1' AND DATE(muda_huu)<= '$date2'
    AND  fv.hiv_id = vs.first_v_id  HAVING co > 1
    
    ") or die(mysqli_error($conn));
							

  
    echo "<table>";
    echo "<tr><th>FirstName</th><th>LastName</th><th>CURRENT HIV STATUS</th><th>ARV_THERAPY</th></tr>";
    while($row=mysqli_fetch_array($select)){
	
	
	
	
	$fname= explode(' ',$row['pn'])[0];
	$mname='';
	if(sizeof(explode(' ',$row['pn']))>= 3){
	$mname=explode(' ',$row['pn'])[sizeof(explode(' ',$row['pn'])) - 2];
			
		$lname=explode(' ',$row['pn'])[sizeof(explode(' ',$row['pn'])) - 1];}
		
		else{
		    
		$lname=explode(' ',$row['pn'])[1];
	    }
echo "<tr><td>$fname</td><td>$lname</td><td>$row[st]</td><td>$row[the]</td></tr>";
	}
    echo "</table>";
    
 }
 
 
 
 
 
 
 
 
 
 
 
    
  }  ?>