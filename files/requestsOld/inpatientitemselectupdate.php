<?php
    include("../includes/connection.php");
    if(isset($_GET['Consultation_Type'])){
	$Consultation_Type = $_GET['Consultation_Type'];
	
	if($Consultation_Type=='Surgery'){
	    //$Consultation_Type = 'Theater';
	    $Consultation_Type = 'Surgery';
	}
	if($Consultation_Type=='Treatment'){
	    $Consultation_Type = 'Pharmacy';
	}if(isset($_GET['consultation_ID'])){
	    $consultation_id = $_GET['consultation_ID'];
    }
	}
	
	//die($consultation_id);
//Selecting Submitted Tests,Procedures, Drugs
	$select_payment_cache = "SELECT * FROM tbl_payment_cache pc,tbl_item_list_cache ilc,tbl_items i
				WHERE pc.consultation_id = $consultation_id
				AND pc.Payment_Cache_ID = ilc.Payment_Cache_ID
				AND i.Item_ID = ilc.Item_ID
				";
				
	 //die($select_payment_cache);
	$cache_result = mysql_query($select_payment_cache) or die(mysql_error());
	$Radiology = '';
	$Laboratory = '';
	$Pharmacy = "";
	$Procedure = "";
	$Surgery = "";
	if(@mysql_num_rows($cache_result)>0){
	    while($cache_row = mysql_fetch_assoc($cache_result)){
	       if($cache_row['Check_In_Type']=='Radiology'){
		   $Radiology.= ' '.$cache_row['Product_Name'].';';
	       }
	       if($cache_row['Check_In_Type']=='Laboratory'){
		   $Laboratory.= ' '.$cache_row['Product_Name'].';';
	       }
	       if($cache_row['Check_In_Type']=='Pharmacy'){
		   $Pharmacy.= ' '.$cache_row['Product_Name'].'[ Dosage: '.$cache_row['Doctor_Comment'].' ]'.';   ';
	       }
	       if($cache_row['Check_In_Type']=='Procedure'){
		   $Procedure.= ' '.$cache_row['Product_Name'].';';
	       }
	       if($cache_row['Check_In_Type']=='Surgery'){
		   $Surgery.= ' '.$cache_row['Product_Name'].';';
	       }
	   }   
	}else{
	  die("Not found");
	}
	  //die($Consultation_Type);
	  if($Consultation_Type=='Radiology'){
	    echo 'Radiology<$$$&&&&>'.$Radiology;
	  }elseif($Consultation_Type=='Pharmacy'){
	    echo 'Treatment<$$$&&&&>'.$Pharmacy;
	  }elseif($Consultation_Type=='Laboratory'){
	    echo 'Laboratory<$$$&&&&>'.$Laboratory;
	  }elseif($Consultation_Type=='Procedure'){
	    echo 'Procedure<$$$&&&&>'.$Procedure;
	  }elseif($Consultation_Type=='Surgery'){
	    echo 'Surgery<$$$&&&&>'.$Surgery;
	  }
	  
	  
	