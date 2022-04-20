<style>
 .itemhover{
   background-color:#fff;
 }
 .itemhover:hover{
   cursor:pointer;
   background-color:#ccc
 }
 .itemhoverlabl{
     width: 100%;
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
    if(isset($_GET['consultation_id'])){
        $consultation_id = $_GET['consultation_id'];
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
    
    if(isset($_GET['subcategory_ID']) && $_GET['subcategory_ID']!='Select Sub Category'){
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
    $sql = "SELECT disease_code,disease_ID,disease_name FROM tbl_disease d JOIN tbl_disease_subcategory ds ON d.subcategory_ID=ds.subcategory_ID JOIN tbl_disease_category dc ON dc.disease_category_ID=ds.disease_category_ID
		   WHERE ds.subcategory_description !='' AND disease_version='$configvalue_icd10_9' $filter LIMIT 30";
    
    //die($sql);
//    if(){
//        
//    }

?>
    <script type='text/javascript'>
	    function sendOrRemove(disease_ID,check_ID){
		    if (check_ID.checked==true){
			action = "ADD";
		    }else{
			action = "REMOVE";
		    }
		    
			    if(window.XMLHttpRequest) {
				myObject = new XMLHttpRequest();
			    }
			    else if(window.ActiveXObject){ 
				myObject = new ActiveXObject('Micrsoft.XMLHTTP');
				myObject.overrideMimeType('text/xml');
			    }
			    
			    myObject.onreadystatechange= sendOrRemove_AJAX; //specify name of function that will handle server response....
			    myObject.open('GET','sendOrRemoveDisease.php?action='+action+'&Consultation_Type=<?php echo $Consultation_Type; ?>'+'&consultation_id=<?php echo $consultation_id; ?>&disease_ID='+disease_ID,true);
			    myObject.send();
		}
	    function sendOrRemove_AJAX(){
		if (myObject.readyState == 4) {
		    parent.document.getElementById("disease").src="doctordiagnosis_Iframe.php?consultation_id=<?php echo $consultation_id;?>&Consultation_Type=<?php echo $Consultation_Type; ?>&Registration_ID=<?php echo $Registration_ID; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID;?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage"
		}
	    }
	</script>
    <script type='text/javascript'>
	function validateDisease2(disease_ID,check_ID){
	   //alert('my time');
		if (check_ID.checked==true) {
		//alert('ceked');
		    if(window.XMLHttpRequest) {
			validateObject = new XMLHttpRequest();
			}
			else if(window.ActiveXObject){ 
			    validateObject = new ActiveXObject('Micrsoft.XMLHTTP');
			    validateObject.overrideMimeType('text/xml');
			}
			
			validateObject.onreadystatechange= function (){
							    var resultData = (validateObject.responseText).trim();
							    
							    if (validateObject.readyState==4) {
								if (resultData != ''){
								    alert(resultData);
								    check_ID.checked = false;
								}else{
								    sendOrRemove(disease_ID,check_ID);
								}
							    }
							}; //specify name of function that will handle server response....
			
			validateObject.open('GET','validateDisease.php?disease_ID='+disease_ID+'&Registration_ID=<?php echo $Registration_ID; ?>',true);
			validateObject.send();
		}else{
		    sendOrRemove(disease_ID,check_ID);
		}
	    }
    </script>
<table width='100%'>
    <tr id='thead'>
    <td width='15%'><center><b>Select</b></center></td>
    <td width="85%"><center><b>Disease</b></center></td>
    </tr>
    <?php
//    if($subcategory_ID=='ALL'){
//	if($disease_category_ID=='ALL'){
//	    $qr_condition = '';
//	}else{
//	    $qr_condition = "subcategory_ID IN (SELECT dsc.subcategory_ID FROM tbl_disease_subcategory dsc WHERE dsc.disease_category_ID =$disease_category_ID ) AND ";
//	}
//    }else{
//	$qr_condition = "subcategory_ID = $subcategory_ID AND ";
//    }
//    $qr = "SELECT * FROM tbl_disease
//		    WHERE $qr_condition disease_name LIKE '%$disease_name%' LIMIT 30";
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    $i =1;
    while($row = mysqli_fetch_assoc($result)){
	$disease_ID = $row['disease_ID'];
	$disease_code = $row['disease_code'];
	$check_selected = "SELECT disease_ID FROM tbl_disease_consultation WHERE consultation_id = $consultation_id
	AND disease_ID=$disease_ID AND diagnosis_type='$Consultation_Type'";
	$checked;
	if(mysqli_num_rows(mysqli_query($conn,$check_selected))>0){
	$checked = true;
	}else{
	$checked = false;    
	}
        ?>
    <tr>
	<td><center><input type='checkbox' id="disease_<?php echo $disease_ID; ?>" onclick="validateDisease2('<?php echo $disease_ID; ?>',this)"></center></td>
	<!--<td><center><b><?php //echo $i;?></b></center></td>-->
	<td class="itemhover">
            <label class="itemhoverlabl" for="disease_<?php echo $disease_ID; ?>" >
	   <!--<input type='text'  value='' female='yes' readonly style='width: 100%'>-->
	<?php echo $row['disease_name']." ~($disease_code)";?>
         </label></td>
	<!--<td><input type='text' value='<?php //echo $row['disease_code'];?>' readonly style='width: 100%'></td>-->
    </tr>
        <?php
        $i++;
    }
    ?>
</table>