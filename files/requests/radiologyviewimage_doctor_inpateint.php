<style>
/*	table{
      background-color:#eeeeee;	
	}
	tr:hover{
	background-color:#fff;
	cursor:pointer;
	}*/
</style>
<?php
  session_start();
  include("../includes/connection.php");
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

$data='';$status='';

  $data.='
	<table width="100%" height="80%" border= align="center">
			<tr>
				<td id="Search_Iframe" style="text-align:center;" colspan="3">
				<div style="width:100%; height:auto;oveflow-y:scroll;overflow-x:hidden;">
				   
				';
				
					 $photo="SELECT * FROM tbl_radiology_image WHERE Registration_ID='$Registration_ID' AND Item_ID = '$Item_ID' AND Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'";
					 
                                         
                                         $result=mysqli_query($conn,$photo) or die(mysqli_error($conn));
						 if(mysqli_num_rows($result) > 0){
							 $list=0;
							 while ($row = mysqli_fetch_array($result)){
								 $list++;
								// extract($row);
                                 $Radiology_Image=$row['Radiology_Image'];
								 $data.= '<h3 style="text-align: center;display:inline">';
								 $data.= "<img height='150' alt='' class='art-lightbox' src='RadiologyImage/".$Radiology_Image."' onclick='SelectViewer(\"$Radiology_Image\")' alt='".$Radiology_Image."'>";
								 $data.= '</h3>';
							 }
						 }else{
							 $data.= "<center><b style='text-align: center;font-size: 14px;font-weight: bold;color:red'>No Radiology Images For This Patient.</b></center>";
						 }
						 
			$data.='</div></td></tr></table></center>';
			
    // $dataToEncode=array(
	                // 'statusMsg'=>$status,
					// 'dataToDisplay'=>$data
	              // );
		//$dataToEncode
    //echo $status;		
    echo $data;//json_encode($dataToEncode);
?>
