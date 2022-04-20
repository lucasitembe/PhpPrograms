<?php
include("../includes/connection.php");

$subCat='';
$data='';
$total='';

$filter='';
if(isset($_GET['subId'])){
  $subCat=$_GET['subId'];
  $testname=$_GET['testname'];
}

if(!empty($testname)){
   $filter=" AND Product_Name LIKE '%$testname%'"; 
}
$select_items= "SELECT * FROM tbl_items i, tbl_item_subcategory tis WHERE i.Item_Subcategory_ID= tis.Item_Subcategory_ID AND 	i.Item_Subcategory_ID = '$subCat' AND 
				i.Consultation_Type = 'Radiology'  AND enabled_disabled='enabled' $filter
			   ";
						
						$select_items_qry = mysqli_query($conn,$select_items) or die(mysqli_error($conn));
						$count = mysqli_num_rows($select_items_qry);
   $data.= '<table style="background-color:white;padding:20px 0 20px 0; width:99%" border="0"  >';
	
	$data.= '<tr style="font-weight:bold;font-size:12px;" >';	
		$data.= '<td style="width:5%;">SN</td>';	
		$data.= '<td>TEST NAME</td>';	
		$data.= '<td style="width:15%;">&nbsp;</td>';	
	$data.= '</tr>';
    $sn=1;
	$total='Total Item: '.$count;
	if(mysqli_num_rows($select_items_qry)){
   while($row=mysqli_fetch_array($select_items_qry)){
        $data.= '<tr>	
					 <td style="width:5%;">'.$row['Item_ID'].'</td>	
					 <td>'.$row['Product_Name'].'</td>	
					 <td style="width:25%;"><button style="width:82%;font-size:14px" type="button" class="art-button-green" onclick="manageParamenter(\''.$row['Item_ID'].'\',\''.$row['Item_Subcategory_ID'].'\',\''.$row['Product_Name'].'\');  RadioParameterList();">
					PARAMETER SETUP</button></td>	
				  </tr>';
	  
	  $sn++;
   }}else{
		$data.='<tr><td colspan="2" style="color:red;">No result found</td></tr>';
   }
   $data.= '</table>';
  echo $total.'#dttaot$'.$data;
