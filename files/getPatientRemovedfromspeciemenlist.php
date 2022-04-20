<!--<link rel="stylesheet" href="css/uploadfile.css" media="screen">-->
<!--<link rel="stylesheet" href="table.css" media="screen">-->
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<!--<script src="css/jquery.js"></script>-->
<script src="css/jquery-ui.js"></script>
<?php
include("./includes/connection.php");
if(isset($_POST['action'])){
    if($_POST['action']=='consultedPatients'){

    }elseif ($_POST['action']=='barcode') {
     
    $getBarcode=mysqli_query($conn,"SELECT * FROM  tbl_specimen_results WHERE BarCode LIKE '%".$_POST['value']."%'");
    $numrows=mysqli_num_rows($getBarcode);
    if($numrows>0){
    while ($rows = mysqli_fetch_array($getBarcode)) {
        $payingID= $rows['payment_item_ID']; 
        $select_Filtered_Patients = mysqli_query($conn,
            "SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID AND tbl_test_results.payment_item_ID=".$payingID." AND removed_status='No' GROUP BY tbl_patient_registration.Registration_ID ORDER BY test_result_ID ASC");
   
    }
    }  else {
        $select_Filtered_Patients="";
    }
           
    }
    
    
    } else {
   $select_Filtered_Patients = mysqli_query($conn,
   "SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID AND removed_status='Yes' GROUP BY tbl_patient_registration.Registration_ID ORDER BY test_result_ID ASC");
}

  $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age ='';
    }
  $htm="<center>
      <table width =100% id='resultsPatientList' class='display'>
        <thead>
            <tr>
                <th style='width:5%;text-align:left'><b>SN</b></th>
                <th style='text-align:left'><b>PATIENT NAME</b></th>
                <th style='text-align:left'><b>REG NO.</b></th>
                <th style='text-align:left'><b>SPONSOR</b></th>
                <th style='text-align:left'><b>AGE</b></th>
                <th style='text-align:left'><b>GENDER</b></th>
                <th style='text-align:left'><b>PHONE No.</b></th>
                <th style='text-align:left'><b>DOCTOR NAME</b></th>
                <th style='text-align:left'><b>DATE REMOVED</b></th>
                <th style='text-align:left'><b>REMOVED BY</b></th>
                <th style='text-align:left'><b>ACTION</b></th>
            </tr>
        </thead>";
    $temp = 1;
    while($row = @mysqli_fetch_array($select_Filtered_Patients)){
            //$Product_Name=$row['Product_Name'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
            
              $date1 = new DateTime($Today);
              $date2 = new DateTime($Date_Of_Birth);
              $diff = $date1 -> diff($date2);
              $age = $diff->y." Years, ";
              $age.= $diff->m." Months";
//              $age.= $diff->d." Days";
//              if(strtolower($Product_Name)=='registration and consultation fee'){
//              }else{
                $htm.="<tr>";
                $htm.="<td id='thead'>".$temp."<td>".ucwords(strtolower($row['Patient_Name']))."</td>";
                $htm.="<td>".$row['Registration_ID']."</td>";
                $htm.="<td>".$row['Sponsor_Name']."</td>";
                $htm.="<td>".$age."</td>";
                $htm.="<td>".$row['Gender']."</td>";
                $htm.="<td>".$row['Phone_Number']."</td>";
                $htm.="<td>".$row['Employee_Name']."</td>";
                $htm.="<td>".$row['date_removed']."</td>";
                $selectng=mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID=".$row['staff_removed']."");
                $names=  mysqli_fetch_assoc($selectng);
                $htm.="<td>".$names['Employee_Name']."</td>";
                if(isset($_POST['action'])){
                  $htm.="<td><input type='button' class='searchresults' name='".$row['Patient_Name']."' id='".$row['Registration_ID']."' value='Lab results' /> <input type='button' class='return' id='".$row['Payment_Item_Cache_List_ID']."' value='Return Patient' /></td>";
                 }  else {
                 $htm.="<td><input type='button' class='results' name='".$row['Patient_Name']."' id='".$row['Registration_ID']."' value='Lab results' style='width:93px'/> <input type='button' class='return' id='".$row['Payment_Item_Cache_List_ID']."' value='Return Patient' /></td>";
                }
                
                $htm.="</tr>";
                $temp++;  
                 }
                 $htm .="</table></center>";
                 echo $htm; 
    
?>

<script type="text/javascript">
    $('#resultsPatientList').DataTable({
        "bJQueryUI":true
    });
    
</script>

<script type="text/javascript">
  $(document).on('click','.searchresults',function(){
   var patient=$(this).attr('name');
   var id=$(this).attr('id');
   $.ajax({
      type:'POST', 
      url:'requests/testResults.php',
      data:'action=getResult&id='+id,
      cache:false,
      success:function(html){
      //  alert(html);
        $('#showLabResultsHere').html(html);
      }
   });
      $('#labResults').dialog({
       modal:true,
       width:1330,
       minHeight:450,
       resizable:false,
       draggable:false,
   }).dialog("widget")
     .next(".ui-widget-overlay")
     .css({
          background:"rgb(100,100,100)",
          opacity:1
    });
    $("#labResults").dialog('option', 'title', patient+'  '+'No.'+id);
 });
 
 $('#resultsProvidedList').click(function(){
     $('#resultsconsultationLablist').text('CONSULTED LAB PATIENTS LIST');
      $.ajax({
          type:'POST',
          url:'getPatientfromspeciemenlist.php',
          data:'action=consultedPatients&value=',
          success:function(html){
            $('#Search_Iframe').html(html);  
          }
      });
 });
 
 
 $('#performanceReport').click(function(){
     $('#resultsconsultationLablist').text('PERFORMANCE REPORT');
     $('#Search_Iframe').html('performance report here');
     
 });


   
 
 $(document).on('click','.return',function(){
   var payID=$(this).attr('id');
   if(confirm('Are you sure you want to return this patient to results list?')){
        $.ajax({
          type:'POST',
          url:'requests/removefromResultsList.php',
          data:'returnToList=returning&payID='+payID,
          success:function(html){
            window.location.href='patientremovedfromspeciemenlist.php';
            // alert(html);
           // $('#Search_Iframe').html(html);  
          }
      });    
   }   
 });
</script>
