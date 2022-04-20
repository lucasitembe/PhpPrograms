

<?php 




function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

 include("./includes/connection.php");
if(isSet($_POST['cur_stat']))
{
	
	
	
	// data:comment="+com+"&pri="+priid,
    //success: function(data){
	
	
	$prid=test_input($_POST['pri']);
	
$curstatus=test_input($_POST['cur_stat']);


$prev_test=test_input($_POST['prev_tes']);

$pre_test_cou=test_input($_POST['pre_test_cou']);

$postresult=test_input($_POST['postresult']);

$therapy=test_input($_POST['therapy']);

$prevresult=test_input($_POST['prevresult']);

$dec=test_input($_POST['dec']);

$didtest=test_input($_POST['partiner_test']);

$feed=test_input($_POST['feed']);
$review=test_input($_POST['revie']);

$date_of_prev=test_input($_POST['date_of_prev']);
$partnernam=test_input($_POST['partnernam']);

$mother=test_input($_POST['moth']);
$comment=test_input($_POST['comment']);
//$pri=test_input($_POST['pri']);

$medic=test_input($_POST['medic']);
$yrs=test_input($_POST['yrs']);
$months=test_input($_POST['month']);

$days=test_input($_POST['day']);


$checkifyupo = "SELECT pr_r,status FROM tbl_hiv_first_visit WHERE pr_r= '$prid' AND status='active'";

if(mysqli_num_rows($qresult=mysqli_query($conn,$checkifyupo))>0) {echo "Patient is already registered in RCH LIST Please Continue with updates";
exit;
}
else{
	
	

$sql="INSERT INTO tbl_hiv_first_visit(pr_r,yearslong,monthlong, daylong, partiner_name, mother_name) VALUES ('$prid','$yrs','$months','$days','$partnernam','$mother')";

if(mysqli_query($conn,$sql)) {


$last = mysql_insert_id();
//ngono
$sql3 = "INSERT INTO tbl_hiv_visits(first_v_id, curr_hiv_status, did_hetake_prev_test_, did_pre_test_councel,did_post_result_councel,recommended_date_status_review,arv_therapy,arv_type_medication,dateofprev_test,result_ofprev_test,hiv_declaration,partiner_or_mother_tested,feeding_info,comments)

VALUES ('$last','$curstatus','$prev_test','$pre_test_cou','$postresult','$review','$therapy','$medic','$date_of_prev','$prevresult','$dec','$didtest','$feed','$comment')";

if(!mysqli_query($conn,$sql3)) { echo mysqli_error($conn);  }



echo "Successfully saved";
}

 else { echo mysqli_error($conn);}

}



}

?>
