<?php
//
session_start();
include("../includes/connection.php");
$paramdescid1 = 0;
$paramname1 = '';
$paramid1 = 0;
$paramcomment1 = '';
$Registration_ID = '';
$Patient_Payment_Item_List_ID = '';
$Patient_Payment_ID = '';
$Status_From = '';
$Item_ID = '';

 //get all item details based on item id
    if(isset($_GET['RI'])){
	$Registration_ID = $_GET['RI'];
    }else{
	$Registration_ID = '';
    }
	if(isset($_GET['PPILI'])){
		$Patient_Payment_Item_List_ID = $_GET['PPILI'];
	}else{
		$Patient_Payment_Item_List_ID = '';
	}

	
if(isset($_GET['II'])){
	$Item_ID = $_GET['II'];
}else{
	$Item_ID = '';
}


$requisit_officer=$_SESSION['userinfo']['Employee_Name'];
  
    

    //get all item details based on item id
    if(isset($_GET['RI'])){
	$Registration_ID = $_GET['RI'];
    }else{
	$Registration_ID = '';
    }
    
$patient_details = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_sponsor
 sp WHERE Registration_ID = '$Registration_ID' AND pr.Sponsor_ID=sp.Sponsor_ID") or die (mysqli_error($conn));
    $rows_count = mysqli_num_rows($patient_details);
    if($rows_count > 0){
		while($row = mysqli_fetch_array($patient_details)){
			 $Patient_Name = $row['Patient_Name'];
			 $Gender = $row['Gender'];
			 $Date_Of_Birth = $row['Date_Of_Birth'];
			 $Guarantor_Name = $row['Guarantor_Name'];
			 
			 //calculate age
			 //$Date_Of_Birth = '1984-08-04';
			$date1 = new DateTime(date('Y-m-d'));
			$date2 = new DateTime($Date_Of_Birth);
			$diff = $date1 -> diff($date2);
			$age = $diff->y." Years, ";
			$age.= $diff->m." Months and ";
			$age.= $diff->d." Days ";
		}
    }
?> 
  
<?php 
	//Getting the Item Name
	$select_item = "SELECT Product_Name FROM tbl_items WHERE Item_ID = '$Item_ID'";
	$select_item_qry = mysqli_query($conn,$select_item) or die(mysqli_error($conn));
	while($theitem = mysqli_fetch_assoc($select_item_qry)){
		$item_name = $theitem['Product_Name'];
	}
?>  
<style type="text/css"> 
	table, tr ,td{
		border-collapse:collapse !important;
		border:none !important;
		backgroud:white;
	}
</style>  
  
  <?php 
 
  $data="<fieldset><center><table width='100%' cellspacing='0' cellpadding='0'>
           <tr>
             <td colspan='2' style='text-align:right;padding-left:10px;'>
				<a style='' href='RadiologyTests_Print.php?RI=".$Registration_ID."&II=".$Item_ID."&PPILI=".$Patient_Payment_Item_List_ID."' class='art-button-green' target='_blank'>P R I N T</a>
			</td>			
			</tr>
         </table><br/>    			
			";
						
						
		
		    $data .='
			<table width="100%" id="tableComment">';
			//Get all Parameters
			$select_allparams = "SELECT * FROM tbl_radiology_parameter";
			$select_allparams_qry = mysqli_query($conn,$select_allparams) or die(mysqli_error($conn));
			while($allparams = mysqli_fetch_assoc($select_allparams_qry)){
				$allparamid = $allparams['Parameter_ID'];
				$allparamname = $allparams['Parameter_Name'];
				
				//Getting the Old Comments
				$select_old = "
				SELECT * 
					FROM 
					tbl_radiology_discription rd,
					tbl_radiology_parameter rp
						WHERE
						rd.Parameter_ID = rp.Parameter_ID AND
						rd.Registration_ID = '$Registration_ID' AND
						rd.Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID' AND
						rd.Item_ID = '$Item_ID' AND
						rp.Parameter_ID = '$allparamid'
					";
					
					//DIE($select_old);
				$select_old_qry = mysqli_query($conn,$select_old) or die(mysqli_error($conn));
				while($theold1 = mysqli_fetch_assoc($select_old_qry)){
					$paramdescid1 = $theold1['Radiology_Description_ID'];
					$paramname1 = $theold1['Parameter_Name'];
					$paramid1 = $theold1['Parameter_ID'];
					$paramcomment1 = $theold1['comments'];
				}	
                 $attr='';
				
				 if($allparamid != $paramid1){

				      $attr= "style='display:none;' id='row".$allparamid."'";
				 }else{
					$parCom='';
					 if($allparamid == $paramid1){ 
						if(isset($paramcomment1)){ 
						  $parCom= $paramcomment1; 
						} 
					 }
					 
					 $data.='<tr '.$attr.'>
					<td width="13%" style="text-align:right;"> <strong>'. $allparamname . '</strong> </td>
					<td width="70%"> 
						<textarea style="width:97%;padding-left:10px;" id="i'.$allparamid.'"  readonly="readonly"> '.str_replace("<br/>", "\n", $parCom).'</textarea> 
						</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					
					';
				 }
				 
			}
			
			$data.="
			</table>
		</center>
	</fieldset>";
  echo $data;
?>
