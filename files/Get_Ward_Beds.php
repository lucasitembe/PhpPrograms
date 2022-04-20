<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_GET['Hospital_Ward_ID'])){
		$Hospital_Ward_ID = $_GET['Hospital_Ward_ID'];
	}else{
		$Hospital_Ward_ID = 0;
	}   
        
  $set_duplicate_bed_assign = $_SESSION['hospitalConsultaioninfo']['set_duplicate_bed_assign'];
  
  
  $bedStat="";      
  if ($set_duplicate_bed_assign == '0') {
      $bedStat=" AND Status = 'available'";
  }
?>

<?php
 $select='<option selected></option>';
	//get all available beds based on hospital ward id
	$not_admitted_beds = "SELECT Bed_ID,Bed_Name,Status FROM tbl_beds WHERE Ward_ID = $Hospital_Ward_ID $bedStat";
        
	$not_taken_beds = mysqli_query($conn,$not_admitted_beds) or die(mysqli_error($conn));	
	while($rowb = mysqli_fetch_assoc($not_taken_beds)){
		$not_admitted_bed_id = $rowb['Bed_ID'];
                $bedStatus=$rowb['Status'];
		$not_admitted_bed_name = $rowb['Bed_Name'];
                
                if ($set_duplicate_bed_assign == '1') {
                   if($bedStatus=='not available'){
                     $select .= "<option value=".$not_admitted_bed_id." class='col-red'>".$not_admitted_bed_name."</option>";   
                   }else{
                     $select .= "<option value=".$not_admitted_bed_id.">".$not_admitted_bed_name."</option>";   
                   } 
                   
                }  else {
                   $select .= "<option value=".$not_admitted_bed_id.">".$not_admitted_bed_name."</option>"; 
                }
                
		
	}
 
         
        $not_available_beds = "SELECT count(Bed_ID) as No_bed_not_Availlable FROM tbl_beds WHERE Ward_ID = $Hospital_Ward_ID AND Status = 'not available'";
	$not_taken_beds ="<br/>Beds Tkn. <span id='alert_here' style='background-color:red; padding:1px 4px 1px 4px; color:#fff; font-size:13px; border-radius:9px;'> ". mysqli_fetch_assoc(mysqli_query($conn,$not_available_beds))['No_bed_not_Availlable'] ." </span>" ;
        
        echo $select.'#$%^$##%$&&'.$not_taken_beds;
	
?>
