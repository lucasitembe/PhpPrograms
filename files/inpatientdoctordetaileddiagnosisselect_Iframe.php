<style>
    .itemhover{
        background-color:white;
       
    }   
 .itemhover:hover{
   cursor:pointer;
   color:#000; 
   background-color:#ccc
 }.itemhoverlabl:hover{
   cursor:pointer;
 }
 .itemhoverlabl{
   width:100%;
   display: block;
 }
</style>
<?php
    include("./includes/connection.php");
    if(isset($_GET['Payment_Cache_ID'])){
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    $payment_cache_ID = $Payment_Cache_ID;
    }else{
        $Payment_Cache_ID = 0;
    }
    if(isset($_GET['Consultation_Type'])){
        $Consultation_Type = $_GET['Consultation_Type'];
    }
    else{
        $Consultation_Type = 0;
    }
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }
    else{
        $Registration_ID = 0;
    }
    if(isset($_GET['Round_ID'])){
        $Round_ID = $_GET['Round_ID'];
    }
    //filter values
    if(isset($_GET['disease_name'])){
        $disease_name = $_GET['disease_name'];
    }
    else{
        $disease_name = '';
    }
    
     if(isset($_GET['disease_code'])){
        $disease_code = $_GET['disease_code'];
    }
    else{
        $disease_code = '';
    }
    
    if(isset($_GET['subcategory_ID'])){
        $subcategory_ID = $_GET['subcategory_ID'];
    }
    else{
        $subcategory_ID = 'ALL';
    }
    if(isset($_GET['disease_category_ID'])){
        $disease_category_ID = $_GET['disease_category_ID'];
    }
    else{
        $disease_category_ID = 'ALL';
    }
    
     $filter='';
    
    if(!empty($disease_category_ID) && $disease_category_ID !=='ALL'){
        $filter .=" AND dc.disease_category_ID='$disease_category_ID'";
    } if(!empty($subcategory_ID) && $subcategory_ID !=='ALL'){
        $filter .=" AND ds.subcategory_ID='$subcategory_ID'";
    }if(!empty($disease_name)){
        $filter .=" AND  disease_name LIKE '%$disease_name%'";
    } if(!empty($disease_code)){
        $filter .=" AND disease_code LIKE '%$disease_code%'";
    }
      $get_icd_9_or_10_result=mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='Icd_10OrIcd_9'") or die(mysql_error);
    if(mysqli_num_rows($get_icd_9_or_10_result)>0){
        $configvalue_icd10_9=mysqli_fetch_assoc($get_icd_9_or_10_result)['configvalue'];
    }
    $sql = "SELECT disease_ID,disease_name FROM tbl_disease d JOIN tbl_disease_subcategory ds ON d.subcategory_ID=ds.subcategory_ID JOIN tbl_disease_category dc ON dc.disease_category_ID=ds.disease_category_ID
		   WHERE ds.subcategory_description !='' AND disease_version='$configvalue_icd10_9' $filter LIMIT 30";
    
?>
    
<table width='100%'>
    <tr id='thead'>
    <td style='width:3%'><center><b>SELECT</b></center></td>
    <td><b>DISEASES</b></td>
    </tr>
    <?php
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    $i =1;
    while($row = mysqli_fetch_assoc($result)){
	$disease_ID = $row['disease_ID'];
	$check_selected = "SELECT disease_ID FROM tbl_ward_round_disease WHERE Round_ID = $Round_ID
	AND disease_ID=$disease_ID AND diagnosis_type='$Consultation_Type'";
	$checked;
	if(mysqli_num_rows(mysqli_query($conn,$check_selected))>0){
	$checked = true;
	}else{
	$checked = false;    
	}
        ?>
    <tr>
	<td><center><input type='checkbox' id='disease_<?php echo $disease_ID; ?>' onclick="validateDisease('<?php echo $disease_ID; ?>',this)"></center></td>
	<!--<td><center><b><?php //echo $i;?></b></center></td>-->
	<td  class="itemhover">
	     <label class="itemhoverlabl"  for="disease_<?php echo $disease_ID; ?>">
			   <?php echo $row['disease_name'];?>
             </label>
	</td>
    </tr>
        <?php
        $i++;
    }
    ?>
</table>