<?php
    include("../includes/connection.php");
    @session_start();
    if(isset($_GET['Consultation_Type'])){
	$Consultation_Type = $_GET['Consultation_Type'];
	
	if($Consultation_Type=='Surgery'){
	    //$Consultation_Type = 'Theater';
	    $Consultation_Type = 'Surgery';
	}
	if($Consultation_Type=='Treatment'){
	    $Consultation_Type = 'Pharmacy';
	}if(isset($_GET['Round_ID'])){
	    $Round_ID = $_GET['Round_ID'];
    }
	}
	
	
//Selecting Submitted Tests,Procedures, Drugs
	$select_payment_cache = "SELECT * FROM tbl_payment_cache pc,tbl_item_list_cache ilc,tbl_items i
				WHERE pc.Round_ID = $Round_ID
				AND pc.Payment_Cache_ID = ilc.Payment_Cache_ID
				AND i.Item_ID = ilc.Item_ID
				";
				
	 //die($select_payment_cache);
	$cache_result = mysqli_query($conn,$select_payment_cache) or die(mysqli_error($conn));
	$Radiology = '';
	$Laboratory = '';
	$Pharmacy = "";
	$Procedure = "";
	$Surgery = "";
	$Others = "";
	$Nuclearmedicine="";
	if(@mysqli_num_rows($cache_result)>0){
	    while($cache_row = mysqli_fetch_assoc($cache_result)){
	       if($cache_row['Check_In_Type']=='Radiology'){
		   $Radiology.= ' '.$cache_row['Product_Name'].';';
	       }
	       if($cache_row['Check_In_Type']=='Laboratory'){
		   $Laboratory.= ' '.$cache_row['Product_Name'].';';
	       }
	       if($cache_row['Check_In_Type']=='Pharmacy'){
				if ($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] == '1') {
					$Pharmacy.= ' '.$cache_row['Product_Name'].'[ '.$cache_row['Doctor_Comment'].' ]'.';   ';
				}else{
					$Pharmacy.= ' '.$cache_row['Product_Name'].'[ Dosage: '.$cache_row['Doctor_Comment'].' ]'.';   ';
				}
		   }
	       if($cache_row['Check_In_Type']=='Procedure'){
		   $Procedure.= ' '.$cache_row['Product_Name'].';';
	       }
	       if($cache_row['Check_In_Type']=='Surgery'){
		   $Surgery.= ' '.$cache_row['Product_Name'].';';
	       }
               
        	if($cache_row['Check_In_Type']=='Others'){
		   		$Others.= ' '.$cache_row['Product_Name'].';';
	       }
		   if ($cache_row['Check_In_Type'] == 'Nuclearmedicine') {
            $Nuclearmedicine.= ' ' . $cache_row['Product_Name'] . ';';
        }
	   }   
	}else{
	  die("Not found");
	}
	  
          
	echo 'Radiology<$$$&&&&>'.$Radiology.'tenganisha'.
		'Treatment<$$$&&&&>'.$Pharmacy.'tenganisha'.
		'Laboratory<$$$&&&&>'.$Laboratory.'tenganisha'.
		'Procedure<$$$&&&&>'.$Procedure.'tenganisha'.
		'Surgery<$$$&&&&>'.$Surgery.'tenganisha'.
		'Nuclearmedicine<$$$&&&&>' . $Nuclearmedicine . 'tenganisha' .
		'Others<$$$&&&&>'.$Others;
	