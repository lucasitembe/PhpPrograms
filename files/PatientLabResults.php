<?php
include("../includes/connection.php");

$returnLastVisitID=mysqli_query($conn,"SELECT Folio_Number FROM tbl_payment_cache WHERE Registration_ID='".$Registration_ID."' ORDER BY Payment_Cache_ID DESC LIMIT 1");

$rs=mysqli_fetch_assoc($returnLastVisitID);

$Folio_Number=$rs['Folio_Number'];

//die($Registration_ID);

// $Query="SELECT * FROM tbl_item_list_cache 
         // INNER JOIN tbl_test_results ON Payment_Item_Cache_List_ID=payment_item_ID 
		 // INNER JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID 
		 // JOIN  tbl_items ON tbl_items.Item_ID=tbl_item_list_cache.Item_ID 
		 // JOIN tbl_tests_parameters_results AS tpr ON tpr.ref_test_result_ID=tbl_test_results.test_result_ID
		 // WHERE Registration_ID='".$Registration_ID."' AND Folio_Number='$Folio_Number'  AND Consultant_ID='".$_SESSION['userinfo']['Employee_ID']."' GROUP BY tpr.ref_test_result_ID";
	
$Query="SELECT * FROM tbl_item_list_cache 
        INNER JOIN tbl_test_results AS trs ON Payment_Item_Cache_List_ID=payment_item_ID 
		INNER JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID 
		JOIN  tbl_items ON tbl_items.Item_ID=tbl_item_list_cache.Item_ID   
		WHERE Registration_ID='".$Registration_ID."' AND Folio_Number='$Folio_Number'";
    	 
		 //die($Query);
    $QueryResults=  mysqli_query($conn,$Query) or die(mysqli_error($conn));
      echo "<center><table class='' style='width:100%'>";       
    echo "<tr style='background-color:rgb(200,200,200)'>
                <th width='1%'>S/N</th>
                <th >Test Name</th>
                <th width='25%'>Doctor's Notes</th>
                <th width='20%'>Lab Remarks</th>
                <th width='2%'>Attachment</th>
				<th width='5%'>Status</th>
                <th width='1%'>Results</th>
		</tr>";
$i=1; 

if(mysqli_num_rows($QueryResults)>0){
while ($row=mysqli_fetch_assoc($QueryResults)){
    $st='';
	
	$RS=mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID='".$row['test_result_ID']."'")or die(mysqli_error($conn));
     $rowSt=mysqli_fetch_assoc($RS);
    $Submitted=$rowSt['Submitted'];
	$Validated=$rowSt['Validated'];
	if($Validated=='Yes'){
	      $st='<span style="color:blue;text-align:center;font-size: 14px;font-weight: bold;">Done</span>';
    }
	else{
	  $st='<span style="text-align:center;color: red;font-size: 14px;font-weight: bold;">Provisional</span>';
	}
	
	//retrieve attachment info
	  $query=mysqli_query($conn,"select * from tbl_attachment where Registration_ID='".$Registration_ID."' AND item_list_cache_id='".$row['Payment_Item_Cache_List_ID']."'");
	  $attach=mysqli_fetch_assoc($query);
	  $image='';
	  if($attach['Attachment_Url'] !=''){
	    $image="<a href='patient_attachments/".$attach['Attachment_Url']."' class='fancybox' rel='gallery' target='_blank' title='".$row['Product_Name']."'><img src='patient_attachments/".$attach['Attachment_Url']."' width='40' height='20' /></a>";
		
	  }
	
	
    echo "<tr>";
    echo "<td>".$i++."</td>";
    echo "<td><input type='text' id='' readonly='true' value='".$row['Product_Name']."'></td>";
    echo "<td><input type='text' id='doctorNotes' value='".$row['Doctor_Comment']."'></td>";
    echo "<td><textarea rows='1' cols='5' style='height:18px'>".$attach['Description']."</textarea></td>";
    echo "<td style='text-align:center'>".$image."</td>";
	 echo "<td>".$st."</td>";
    echo "<td><input type='button' class='generalresltsdoctor' name='".$row['Product_Name']."' patientID='".$Registration_ID."' id='".$row['Item_ID']."' value='Results'></td>";
    echo "</tr>";
   
}
 }else{
     echo '<tr><td colspan="7" style="text-align:center;font-size:20px;color:red">You do not have result for this patient</td></tr>';
 }
echo "</table>";   


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
    //var options = { target: '#output'};
    $('#uploadAttachmentLab').submit(function() {
        $(this).ajaxSubmit({
         beforeSubmit: function() {
           //alert('submiting');
        },
        success: function(data) {
            if(parseInt(data)===1){
                alert("Saved successfully");
                $('#labResults').dialog("closed"); 
            }
        }
        
        });
        return false;
        
    });
    
    
    $('.generalresltsdoctor').click(function(){
        var name=$(this).attr('name');
		var patientID=$(this).attr('patientID');
		//alert('alert');
        $('#showGeneral').html();
       var id=$(this).attr('id');
        $.ajax({
           type:'POST', 
           url:'requests/LabResultsDoctorView.php',
           data:'generalResult=getGeneral&id='+id+'&patientID='+patientID,
           cache:false,
           success:function(html){
              // alert(html);
              $('#showGeneral').html(html);
           }
        });
   $('#labGeneral').dialog({
      modal:true, 
      width:'90%',
      minHeight:450,
      resizable:true,
      draggable:true,
      title:name
   }).dialog("widget")
      .next(".ui-widget-overlay")
      .css("background-color", "rgb(255,255,255)");
   
   //$("#labGeneral").dialog('option', 'title', testName);
   
     $('#labResults').fadeOut(100);
    });
    
    $('.validateResult').click(function(){
        var payment=$('.paymentID').val();
        var productID=$('.productID').val();
            var parId,result;
            var i=1;
            var datastring;
            var total=$('.Resultvalue').length;
            var temp=0;
           
             $('.Resultvalue').each(function(){
                parId=$(this).attr('id');
                result=$(this).val();
                
                if(result===''){
                   temp=temp+1; 
                    
                }
             });
             
             if(temp===total){
                 alert("Please add atleast one result");
                 exit();
             }
            
        $('.Resultvalue').each(function(){
           parId=$(this).attr('id');
           result=$(this).val();
           
          
           
        if($(this).val() !==''){
        if(i==1){
           datastring=parId+'#@'+result;
        }else{
            //paraID+=","+$(this).val();
            datastring+="$>"+parId+'#@'+result;
        }
        }
       i++;
       
       });
        $.ajax({
        type:'POST', 
        url:'requests/SaveTestResults.php',
        data:'SavegeneralResult=getGeneral&testresults='+datastring+'&payment='+payment+'&productID='+productID,
        cache:false,
        success:function(html){
            $('#showGeneral').html(html);
        }
     });
    });
    
    $('.validateSubmittedResult').on('click',function(){
      $('.validated').attr('checked',true);
        var itemID=$('.productID').val();
        var parameterID,testID;
        var i=1;
        var datastring;
       $('.validated').each(function(){
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
        url:'requests/SaveTestResults.php',
        data:'SavegeneralResult=Validation&testresults='+datastring+'&itemID='+itemID,
        cache:false,
        success:function(html){
            //alert(html);
            $('#showGeneral').html(html);
        }
     });
    });
    
    
    $('#labGeneral').on("dialogclose", function( ) {
        
        $('#labResults').fadeIn(1000);
    });
    
    //Results modifications
    $('.modificationStatsdoctor').click(function(){
	     //alert('testlab');
	
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
      $('#historyResults1').dialog({
      modal:true, 
      width:600,
      minHeight:400,
      resizable:true,
      draggable:true,
      title:'Results history'
    }); 
    
    $('#historyResults1').html('View history here');
    });
    
    
    
    $(".Quantative").bind("keydown", function (event) {
             
    if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
         // Allow: Ctrl+A
        (event.keyCode == 65 && event.ctrlKey === true) ||
         
        // Allow: Ctrl+C
        (event.keyCode == 67 && event.ctrlKey === true) ||
         
        // Allow: Ctrl+V
        (event.keyCode == 86 && event.ctrlKey === true) ||
         
        // Allow: home, end, left, right
        (event.keyCode >= 35 && event.keyCode <= 39)) {
          // let it happen, don't do anything
          return;
    } else {
        // Ensure that it is a number and stop the keypress
        if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
            event.preventDefault();
        }  
    }
});
</script>
