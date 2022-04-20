<?php
session_start();
include("../includes/connection.php");
  $itemID=$_POST['itemID'];
  $values=$_POST['testresults'];
  if($values=="undefined"){
       
   }else{
     $results=  explode('$>', $values);
    foreach ($results as $value) {
        $data=  explode("#@", $value) ;
        $id=$data[0];
        $val=$data[1];
      $update="UPDATE tbl_tests_parameters_results SET Submitted='Yes' WHERE ref_test_result_ID='".$val."' AND parameter='".$id."'";
      $runQuery=  mysql_query($update);
    }  
   }

    $sn=1;
    $selectQuery=" select * from tbl_item_list_cache ilc, tbl_test_results tr, tbl_tests_parameters tp, tbl_parameters p, tbl_tests_parameters_results tpr where ilc.Payment_Item_Cache_List_ID = tr.payment_item_ID and ilc.Item_ID = tp.ref_item_ID and p.parameter_ID = tp.ref_parameter_ID and tpr.parameter = p.parameter_ID and tr.test_result_ID = tpr.ref_test_result_ID and ilc.Item_ID = '".$itemID."' GROUP BY PARAMETER_NAME ";
     //$selectQuery="SELECT * FROM tbl_item_list_cache INNER JOIN tbl_test_results ON Payment_Item_Cache_List_ID=payment_item_ID INNER JOIN tbl_tests_parameters ON ref_item_ID=Item_ID JOIN tbl_parameters ON parameter_ID=ref_parameter_ID JOIN tbl_tests_parameters_results ON ref_test_result_ID=test_result_ID WHERE Item_ID='".$productID."' GROUP BY PARAMETER_NAME";
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
                <th width=''>Status</th>
                <th width=''>History</th>
                <th width=''>Submit</th>
        </tr>";
         while ($row2=  mysql_fetch_assoc($GetResults)){
         echo '<tr>';
         echo '<td>'.$sn++.'</td>';
         echo '<td>'.$row2['Parameter_Name'].'</td>';
         echo '<td><input type="text" class="Resultvalue" id="'.$row2['parameter_ID'].'" value="'.$row2['result'].'"></td>';   
         echo '<input type="hidden" class="paymentID" value="'.$row2['test_result_ID'].'">';
         echo '<input type="hidden" class="productsID" value="'.$itemID.'">';
         echo '<td>'.$row2['unit_of_measure'].'</td>';
        if($row2['modified']=="Yes"){
           echo '<td><p class="modificationStats" id="'.$row2['parameter_ID'].'" value="'.$row2['test_result_ID'].'">&#x2714;</p></td>';
         }  else {
             echo '<td></td>'; 
         }
        if($row2['Validated']=="Yes"){
           echo '<td>&#x2714;</td>';
         }  else {
             echo '<td></td>'; 
         }
         if($row2['Submitted']=="Yes"){
             echo '<td>&#x2714;</td>';
         }  else {
             echo '<td></td>';
         }
         $lower=$row2['lower_value'];
         $upper=$row2['higher_value'];
         $rowResult=$row2['result'];
         $result_type=$row2['result_type'];
         if($result_type=="Quantative"){
           if($rowResult>$upper){
            echo '<td><p style="color:rgb(255,0,0)">H</p></td>'; 
            }elseif ($rowResult<$lower) {
             echo '<td><p style="color:rgb(255,0,0)">L</p></td>';    
            }elseif (($rowResult>=$lower) && ($rowResult<=$upper)) {
              echo '<td><p style="color:rgb(0,128,0)">N</p></td>';  
            }  else {
              echo '<td><p style="color:rgb(0,128,0)">N</p></td>';  
            }
            } else if($result_type=="Qualitative") {
                echo '<td><p style="color:rgb(0,0,128)">-</p></td>';
            }  else {
                echo '<td><p style="color:rgb(0,0,128)"></p></td>';
            }
         echo '<td><p class="prevHistory" id="'.$row2['parameter_ID'].'">History</p></td>';
         if($row2['Submitted']=='Yes'){
            echo '<td><input type="checkbox" class="yesvalidated" id="'.$row2['parameter_ID'].'" value="'.$row2['test_result_ID'].'" checked="true" disabled="true">Submitted</td>';  
         }  else {
           echo '<td><input type="checkbox" class="Submitted" id="'.$row2['parameter_ID'].'" value="'.$row2['test_result_ID'].'"></td>'; 
         }
         echo '</tr>';
     }
     echo '<div id="testName">'.$row2['Product_Name'].'</div>';
     echo '</table>';
     echo '<div><input type="button" class="SubmitvalidatedResult" id="'.$row2['test_result_ID'].'" value="Submit"></div>'; 


?>

<script type="text/javascript">
$('.SubmitvalidatedResult').click(function(){
    var itemID=$('.productsID').val();
    var parameterID,testID;
        var i=1;
        var datastring;
       $('.Submitted').each(function(){
         parameterID=$(this).attr('id'); 
         testID=$(this).val();
          var x=$(this).is(':checked');
          if(x){
                if($(this).val() !==''){
                if(i==1){
                   datastring=parameterID+'#@'+testID;
                }else{
                    datastring+="$>"+parameterID+'#@'+testID;
                }
                }
               i++;
            }else{
             
            }
       });
        $.ajax({
        type:'POST', 
        url:'requests/submitTestResults.php',
        data:'SavegeneralResult=getGeneral&testresults='+datastring+'&itemID='+itemID,
        cache:false,
        success:function(html){
//               alert(html);
            $('#showGeneral').html(html);
        }
     });
});



    //Results modifications
    $('.modificationStats').click(function(){
      $('#labGeneral').fadeOut();
      var parameter=$(this).attr('id');
      var paymentID=$('.paymentID').val();
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
      height:600,
      resizable:false,
      draggable:false,
      title:'Results modification history'
   });

    });
    
    
    $('#historyResults1').on("dialogclose", function( ) {
        
        $('#labGeneral').fadeIn(1000);
    });
    
    $('.prevHistory').click(function(){
      $('#historyResults1').dialog({
      modal:true, 
      width:600,
      minHeight:450,
      resizable:false,
      draggable:false,
      title:'Results modification history'
    }); 
    
    $('#historyResults1').html('View history here');
    });
</script>