
<?php
session_start();
include("../includes/connection.php");
if (isset ($_POST['generalResult'])){
    $id=trim($_POST['id']);
	$patientID=trim($_POST['patientID']);
	$ppil=$_POST['ppil'];
	// die($id.' '.$patientID);
	$sn=1;
   
	   $selectQuery="select * from tbl_items item,tbl_item_list_cache ilc,tbl_payment_cache as tpc, tbl_test_results tr, tbl_tests_parameters tp, tbl_parameters p, tbl_tests_parameters_results tpr where item.Item_ID=ilc.Item_ID AND 
	  ilc.Payment_Cache_ID=tpc.Payment_Cache_ID AND ilc.Payment_Item_Cache_List_ID = tr.payment_item_ID and ilc.Item_ID = tp.ref_item_ID and p.parameter_ID = tp.ref_parameter_ID and tpr.parameter = p.parameter_ID and tr.test_result_ID = tpr.ref_test_result_ID and ilc.Item_ID = '".$id."' AND tr.payment_item_ID='".$ppil."'  AND Registration_ID='".$patientID."' AND Validated = 'Yes'  GROUP BY PARAMETER_NAME ORDER BY PARAMETER_NAME";
              //"SELECT * FROM tbl_item_list_cache INNER JOIN tbl_test_results ON Payment_Item_Cache_List_ID=payment_item_ID INNER JOIN tbl_tests_parameters ON ref_item_ID=Item_ID JOIN tbl_parameters ON parameter_ID=ref_parameter_ID JOIN tbl_tests_parameters_results ON ref_test_result_ID=test_result_ID WHERE Item_ID='".$id."' GROUP BY PARAMETER_NAME";
      $GetResults=  mysql_query($selectQuery);
                echo "<center><table class='' style='width:100%'>";       
                echo "<tr style='background-color:rgb(200,200,200)'>
                <th width='1%'>S/N</th>
                <th width=''>Parameters</th>
                <th width=''>Results</th>
                <th width=''>UoM</th>
                <th width=''>M</th>
                <th width=''>V</th>
                <th width=''>S</th>
				<th width=''>Normal Value</th>
                <th width=''>Status</th>
                <th width=''>Previous results</th>
        </tr>";
		
		if(mysql_num_rows($GetResults)){
         while ($row2=  mysql_fetch_assoc($GetResults)){
         echo '<tr>';
         echo '<td>'.$sn++.'</td>';
         echo '<td>'.$row2['Parameter_Name'].'</td>';
         echo '<td><input type="text" readonly="true" class="Resultvalue Qualitative'.$row2['parameter_ID'].'" id="'.$row2['parameter_ID'].'" value="'.$row2['result'].'"></td>'; 
         echo '<td>'.$row2['unit_of_measure'].'</td>';
		 if($row2['modified']=="Yes"){
			 echo '<td><p class="modificationStatsdoctor" id="'.$row2['parameter_ID'].'" value="'.$row2['test_result_ID'].'">&#x2714;</p></td>';
		 }  else {
			 echo '<td></td>'; 
		 }
         
         if($row2['Validated']=="Yes"){
             echo '<td>&#x2714;</td>';
             $Validated=true;
         }  else {
             echo '<td></td>'; 
         }
         if($row2['Submitted']=="Yes"){
             echo '<td>&#x2714;</td>';
         }  else {
             echo '<td></td>';
         }
		 
		 echo '<td>'.$row2['lower_value'].' '.$row2['operator'].' '.$row2['higher_value'].'</td>';
		 	
         $lower=$row2['lower_value'];
         $upper=$row2['higher_value'];
         $rowResult=$row2['result'];
         $Saved=$row2['Saved'];
         $result_type=$row2['result_type'];
         if($result_type=="Quantitative"){
           if($rowResult>$upper){
            echo '<td><p style="color:rgb(255,0,0)">H</p></td>'; 
            }elseif ($rowResult<$lower) {
             echo '<td><p style="color:rgb(255,0,0)">L</p></td>';    
            }elseif (($rowResult>=$lower) && ($rowResult<=$upper)) {
              echo '<td><p style="color:rgb(0,128,0)">N</p></td>';  
            }  else {
              echo '<td><p style="color:rgb(0,128,0)"></p></td>';  
            }
            } else if($result_type=="Qualitative") {
                echo '<td><p style="color:rgb(0,0,128)"></p></td>';
            }  else {
                echo '<td><p style="color:rgb(0,0,128)"></p></td>';
            }
                      //Get previous test results
            $historyQ="SELECT * FROM tbl_tests_parameters_results as tpr,tbl_test_results as tr,tbl_item_list_cache as tilc,tbl_payment_cache as pc,tbl_patient_registration as pr WHERE tpr.ref_test_result_ID=tr.test_result_ID AND tr.payment_item_ID=tilc.Payment_Item_Cache_List_ID AND pc.Payment_Cache_ID=tilc.Payment_Cache_ID AND pr.Registration_ID=pc.Registration_ID AND pr.Registration_ID='$patientID' AND tpr.parameter='".$row2['parameter_ID']."' AND tilc.Item_ID='".$id."' AND tr.payment_item_ID<>'".$ppil."'";
            $Queryhistory=mysql_query($historyQ);
            $myrows=mysql_num_rows($Queryhistory);
            //$prev=$myrows-1;
            if($myrows>0){
              echo '<td>
                <p class="prevHistory" itemID="'.$id.'" name="'.$patientID.'" id="'.$row2['parameter_ID'].'" ppil="'.$ppil.'">'.
                  $myrows.' Previous result(s)'
               .'</p>
               
                </td>';  
            }  else {
             echo '<td>No previous results</td>';
            }
            

        echo '</tr>';
     }
	 }else{
	     echo '<tr><td colspan="10" style="text-align:center"><span style="text-align:center;color: red;font-size: 17px;font-weight: bold;">Provisional Results</span></td></tr>';
	 }
     
     echo '</table>';
     echo '<div></div>';
	 
	// die($selectQuery);
    
} 

?>
<style>
    .modificationStatsdoctor:hover{
        text-decoration: underline;
		cursor:pointer;
        color: rgb(145,0,0);
    }
    
    .prevHistory:hover{
       text-decoration: underline;
       color: rgb(145,0,0); 
    }
</style>

<script>
 
    
    //Results modifications
    $('.modificationStatsdoctor').click(function(){
	 
      $('#labGeneral').fadeOut();
      var parameter=$(this).attr('id');
      var paymentID=$(this).attr('value');;
	  //alert(paymentID);
        $.ajax({
        type:'POST', 
        url:'requests/resultModification.php',
        data:'parameter='+parameter+'&paymentID='+paymentID,
        cache:false,
        success:function(html){
            $('#historyResults1').html(html);
        }
     });
     
     $('#historyResults1').dialog({
      modal:true, 
      width:600,
      minHeight:400,
      resizable:true,
      draggable:true,
      title:'Results modification history'
   });

    });
    
    
    $('#historyResults1').on("dialogclose", function( ) {
        
        $('#labGeneral').fadeIn(1000);
    });
    
    
    $('.prevHistory').click(function(){
        var itemID=$(this).attr('itemID');
        var patientID=$(this).attr('name');
        var parameterID=$(this).attr('id');
        var parameterName=$('.parameterName').val();
		var ppil=$(this).attr('ppil');
		//alert(ppil);
      $.ajax({
        type:'POST', 
        url:'requests/resultModification.php',
        data:'action=history&itemID='+itemID+'&patientID='+patientID+'&parameterID='+parameterID+'&ppil='+ppil,
        cache:false,
        success:function(html){
            $('#historyResults1').html(html);
        }
     });  
        
      $('#historyResults1').dialog({
      modal:true, 
      width:600,
      minHeight:400,
      resizable:true,
      draggable:true
      });
    $("#historyResults1").dialog('option', 'title', parameterName);
    });
    
    
</script>
