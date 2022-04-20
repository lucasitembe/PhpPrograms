<link rel="stylesheet" href="table.css" media="screen">

<style>
    .linkstyle{
        color:#3EB1D3;
    }

    .linkstyle:hover{
        cursor:pointer;
    }
</style>
<?php
include("./includes/connection.php");
$Sponsor='';
$filter = '';
if (isset($_POST['action'])) {
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
    $Sponsor = $_POST['sponsorID'];
}

if ($Sponsor != 'All') {
    $filter =" AND tbl_sponsor.Sponsor_ID='$Sponsor'";
}

echo '<center><table width =100% border=0 id="specimencollected" class="display">';
echo "<thead>
        <tr>
            <th style='text-align: center;'>SN</th>
            <th style='text-align: left;width:40%'>TEST NAME</td>
            <th style='text-align: left;width:1%'>RESULT</td>
            <th style='text-align: center;'>&lt 1 Month <table style='width:100%;border:0'><tr><td style='width:49%;text-align:center'>M</td><td style='width:49%;text-align:center'>F</td></tr></table></th>
            <th style='text-align: center;'>1-11 Months <table style='width:100%;border:0'><tr><td style='width:49%;text-align:center'>M</td><td style='width:49%;text-align:center'>F</td></tr></table></th>
            <th style='text-align: center;'>12-59 Months <table style='width:100%;border:0'><tr><td style='width:49%;text-align:center'>M</td><td style='width:49%;text-align:center'>F</td></tr></table></th>
            <th style='text-align: center;'>&gt 5 Years <table style='width:100%;border:0'><tr><td style='width:49%;text-align:center'>M</td><td style='width:49%;text-align:center'>F</td></tr></table></th>
            <th style='text-align: center;'>Total<table style='width:100%;border:0'><tr><td style='width:;text-align:center'>M</td><td style='width:;text-align:center'>F</td></tr></table></th>
            <th style='text-align: center'>Total</th>
</tr>
       
     </thead>";
    $count = 1;
    $grandTotal=0; 
    if (isset($_POST['action'])) {
     
    $numberSpecimen=mysqli_query($conn,"SELECT COUNT(ilc.Item_ID) AS items,i.Product_Name,i.Item_ID,test_result_ID FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_tests_parameters_results ttpr ON ttpr.ref_test_result_ID=tr.test_result_ID WHERE ilc.Check_In_Type='Laboratory' AND tr.TimeSubmitted BETWEEN '" . $fromDate . "' AND '" . $toDate . "' GROUP BY ilc.Item_ID");
 
      $under1monthMalepos=0;
      $under1monthMaleneg=0;
      $totalunder1monthMale=0;
      $under1monthFemalepos=0;
      $under1monthFemaleneg=0;
      $totalmonthFemale=0;
      $month11Malepos=0;
      $month11Maleneg=0;
      $total11Male=0;
      $month11Femalepos=0;
      $month11Femaleneg=0;
      $total11Female=0;
      $month12Malepos=0;
      $month12Maleneg=0;
      $total12Male=0;
      $month12Femalepos=0;
      $month12Femaleneg=0;
      $total12Female=0;
      $Year5Malepos=0;
      $Year5Maleneg=0;
      $total5Male=0;
      $Year5Femalepos=0;
      $Year5Femaleneg=0;
      $total5Female=0;
      
      $TotalMalepos=0;
      $TotalMaleneg=0;
      $TotalFemalepos=0;
      $TotalFemaleneg=0;
      $GrandTotalpos=0;
      $GrandTotalneg=0;
     
       $Today_Date = mysqli_query($conn,"select now() as today");
            
            while($row = mysqli_fetch_array($Today_Date)){
                $original_Date = $row['today'];
                $new_Date = date("Y-m-d", strtotime($original_Date));
                $Today = $new_Date;
                $age ='';
            }
      while ($row2=  mysqli_fetch_assoc($numberSpecimen)){
       
            echo '<tr>';
            echo '<td style="text-align: center;">'.$count++.'</td>';
            echo '<td><span class="totalItemsx" id="'.$row2['Item_ID'].'" pp="'.$row2['Product_Name'].'"  style="cursor:pointer">'.$row2['Product_Name'].'</span></td>';
          
            $getresult= mysqli_query($conn,"SELECT * FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_tests_parameters_results ttpr ON ttpr.ref_test_result_ID=tr.test_result_ID JOIN tbl_patient_registration tpr ON tpr.Registration_ID=pp.Registration_ID JOIN tbl_patient_registration pr ON pp.Registration_ID=pr.Registration_ID  WHERE ilc.Check_In_Type='Laboratory' AND tr.TimeSubmitted BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND i.Item_ID='".$row2['Item_ID']."' AND (result='POSITIVE' || result='NEGATIVE') GROUP BY test_result_ID");
            
            while ($result=  mysqli_fetch_assoc($getresult)){
            $gender=$result['Gender'];
            $test_res=  strtoupper($result['result']);
           // echo $test_res.'<br />';
            $Date_Of_Birth = $result['Date_Of_Birth'];
            $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1 -> diff($date2);
            $years = $diff->y;
            $months= $diff->m;
            if($test_res=='POSITIVE'){
             
            if($years<1){
                 $age2=$months;
                 if($age2<1 && $gender=='Male'){
                    $under1monthMalepos++; 
                    $totalunder1monthMale++;
                 }  elseif($age2<1 && $gender=='Female') {
                    $under1monthFemalepos++;
                    $totalmonthFemale++;
                 }elseif (($age2>=1 && $age2<=11) && $gender=='Male') {
                    $month11Malepos++;
                    $total11Male++;
                 }elseif (($age2>=1 && $age2<=11) && $gender=='Female'){
                    $month11Femalepos++;
                    $total11Female++;
                 }
                 
                 
             }  elseif(($years>=1 && $years<=5) && ($gender=='Male')) {
                 $month12Malepos++;
                 $total12Male++;
             }elseif(($years>=1 && $years<=5) && ($gender=='Female')) {
                $month12Femalepos++;
                $total12Female++;
             }elseif(($years>5) && ($gender=='Male')) {
                $Year5Malepos++;
                $total5Male++;
             }elseif(($years>5) && ($gender=='Female')) {
                $Year5Femalepos++;
                $total5Female++;
             }
                  
             
                
            }elseif ($test_res=='NEGATIVE') {
                
                if($years<1){
                 $age2=$months;
                 if($age2<1 && $gender=='Male'){
                    $under1monthMaleneg++; 
                    $totalunder1monthMale++;
                 }  elseif($age2<1 && $gender=='Female') {
                    $under1monthFemaleneg++;
                    $totalmonthFemale++;
                 }elseif (($age2>=1 && $age2<=11) && $gender=='Male') {
                    $month11Maleneg++;
                    $total11Male++;
                 }elseif (($age2>=1 && $age2<=11) && $gender=='Female'){
                    $month11Femaleneg++;
                    $total11Female++;
                 }
                 
                 
             }  elseif(($years>=1 && $years<=5) && ($gender=='Male')) {
                 $month12Maleneg++;
                 $total12Male++;
             }elseif(($years>=1 && $years<=5) && ($gender=='Female')) {
                $month12Femaleneg++;
                $total12Female++;
             }elseif(($years>5) && ($gender=='Male')) {
                $Year5Maleneg++;
                $total5Male++;
             }elseif(($years>5) && ($gender=='Female')) {
                $Year5Femaleneg++;
                $total5Female++;
             }
                    
            }

            }
            
      $TotalMalepos=$under1monthMalepos+$month11Malepos+$month12Malepos+$Year5Malepos;
      $TotalMaleneg=$under1monthMaleneg+$month11Maleneg+$month12Maleneg+$Year5Maleneg;
      $TotalFemalepos=$under1monthFemalepos+$month11Femalepos+$month12Femalepos+$Year5Femalepos;
      $TotalFemaleneg=$under1monthFemaleneg+$month11Femaleneg+$month12Femaleneg+$Year5Femaleneg;
      $GrandTotalpos=$TotalMalepos+$TotalFemalepos;
      $GrandTotalneg=$TotalMaleneg+$TotalFemaleneg;
            
            echo '<td style="text-align: center;">
                    <table> 
                        <tr><td>Positive</td></tr>
                        <tr><td>Negative</td></tr>
                    </table>

                 </td>';
            
            echo '<td style="text-align: center;">
               <table style="width:100%;border:0"> 
               <tr><td style="width:49%;text-align:center">'.$under1monthMalepos.'</td><td style="width:49%;text-align:center">'.$under1monthFemalepos.'</td></tr>
                   <tr><td style="width:49%;text-align:center">'.$under1monthMaleneg.'</td><td style="width:49%;text-align:center">'.$under1monthFemaleneg.'</td></tr>
               </table>
                </td>';
            
            
            echo '<td style="text-align: center;">
                    <table style="width:100%;border:0">
                    <tr><td style="width:49%;text-align:center">'.$month11Malepos.'</td><td style="width:49%;text-align:center">'.$month11Femalepos.'</td></tr>
                    <tr><td style="width:49%;text-align:center">'.$month11Maleneg.'</td><td style="width:49%;text-align:center">'.$month11Femaleneg.'</td></tr>
                 </table>
                 
                </td>';
            
            echo '<td style="text-align: center;">
                <table style="width:100%;border:0">
                <tr><td style="width:49%;text-align:center">'.$month12Malepos.'</td><td style="width:49%;text-align:center">'.$month12Femalepos.'</td></tr>
                <tr><td style="width:49%;text-align:center">'.$month12Maleneg.'</td><td style="width:49%;text-align:center">'.$month12Femaleneg.'</td></tr>
                </table></td>';
            
            echo '<td style="text-align: center;">
                 <table style="width:100%;border:0">
                 <tr><td style="width:49%;text-align:center">'.$Year5Malepos.'</td><td style="width:49%;text-align:center">'.$Year5Femalepos.'</td></tr>
                  <tr><td style="width:49%;text-align:center">'.$Year5Maleneg.'</td><td style="width:49%;text-align:center">'.$Year5Femaleneg.'</td></tr>
                 </table>
                 </td>';
            
             echo '<td style="text-align: center;">
                 <table style="width:100%;border:0">
                 <tr><td style="width:49%;text-align:center">'.$TotalMalepos.'</td><td style="width:49%;text-align:center">'.$TotalFemalepos.'</td></tr>
                 <tr><td style="width:49%;text-align:center">'.$TotalMaleneg.'</td><td style="width:49%;text-align:center">'.$TotalFemaleneg.'</td></tr>
                 
                </table>
                 </td>';
             echo '<td style="text-align: center;">
                 <table style="width:100%;border:0">
                 <tr><td style="width:49%;text-align:center">'.$GrandTotalpos.'</td></tr>
                 <tr><td style="width:49%;text-align:center">'.$GrandTotalneg.'</td></tr>
      
                </table>
                 </td>';
       
      $under1monthMalepos=0;
      $under1monthMaleneg=0;
      $under1monthFemalepos=0;
      $under1monthFemaleneg=0;
      $month11Malepos=0;
      $month11Maleneg=0;
      $month11Femalepos=0;
      $month11Femaleneg=0;
      $month12Malepos=0;
      $month12Maleneg=0;
      $month12Femalepos=0;
      $month12Femaleneg=0;
      $Year5Malepos=0;
      $Year5Maleneg=0;
      $Year5Femalepos=0;
      $Year5Femaleneg=0;
      $TotalMalepos=0;
      $TotalMaleneg=0;
      $TotalFemalepos=0;
      $TotalFemaleneg=0;
      $GrandTotalpos=0;
      $GrandTotalneg=0;

      echo '</tr>';
          }
          $FinalMale=$totalunder1monthMale+$total11Male+$total12Male+$total5Male;
          $FinalFemale=$totalmonthFemale+$total11Female+$total12Female+$total5Female;
          $TotalFinal=$FinalMale+$FinalFemale;
          echo '<tr><td></td><td style="font-weight:bold;font-size:14px"><b>TOTAL</b></td> 
                 <td></td>
                 <td style="text-align: center;"><table style="width:100%;border:0"><tr><td style="width:49%;text-align:center"><b>'.$totalunder1monthMale.'</b></td><td style="width:49%;text-align:center"><b>'.$totalmonthFemale.'</b></td></tr></table></td>
                 
                 <td style="text-align: center;"><table style="width:100%;border:0"><tr><td style="width:49%;text-align:center"><b>'.$total11Male.'</b></td><td style="width:49%;text-align:center"><b>'.$total11Female.'</b></td></tr></table></td>
                 <td style="text-align: center;"><table style="width:100%;border:0"><tr><td style="width:49%;text-align:center"><b>'.$total12Male.'</b></td><td style="width:49%;text-align:center"><b>'.$total12Female.'</b></td></tr></table></td>
                 <td style="text-align: center;"><table style="width:100%;border:0"><tr><td style="width:49%;text-align:center"><b>'.$total5Male.'</b></td><td style="width:49%;text-align:center"><b>'.$total5Female.'</b></td></tr></table></td>
                 <td style="text-align: center;"><table style="width:100%;border:0"><tr><td style="width:49%;text-align:center"><b>'.$FinalMale.'</b></td><td style="width:49%;text-align:center"><b>'.$FinalFemale.'</b></td></tr></table></td>
                 <td style="text-align: center;"><table style="width:100%;border:0"><tr><td style="width:49%;text-align:center"><b>'.$TotalFinal.'</b></td></tr></table></td>
                
                </tr>';
          
         $totalunder1monthMale=0;
         $totalmonthFemale=0;
         $total11Male=0;
         $total11Female=0;
         $total12Male=0;
         $total12Female=0;
         $total5Male=0;
         $total5Female=0;
}
echo "</table></center>";
?>

<div id="showAll" style="display: none;min-height: 300px">
    <div id="AllpatientsList">
        
    </div>
    <div style="padding-top:5px">
        <center>
            <input type="button" value="Priview and Print" id="print_patients" class="art-button-green">
            <input type="hidden" value="" id="Item_ID">
            <input type="hidden" value="" id="Item_Name">
        </center>
    </div>
</div>

<script>
    $('.totalItems').on('click',function(){
        var id=$(this).attr('id');
        var pp=$(this).attr('pp');
        var fromDate=$('#date_From').val();
        var date_To=$('#date_To').val();
        $('#Item_ID').val(id);
        $('#Item_Name').val(pp);
        $('#showAll').dialog({
            modal: true,
            width: '90%',
            resizable: true,
            draggable: true,
            title: pp,
            close: function (event, ui) {
               
            }
        });
        
         $.ajax({
            type: 'POST',
            url: 'requests/Positive_Negative_Collection.php',
            data: 'action=patientList&fromDate='+fromDate+'&date_To='+date_To+'&id='+id,
            cache: false,
            success: function (html) {
                $('#AllpatientsList').html(html);
            }
        });
    });
    
    $('#print_patients').on('click',function(){
       var Item_ID= $('#Item_ID').val();
       var Item_Name=$('#Item_Name').val();
       var fromDate=$('#date_From').val();
       var date_To=$('#date_To').val();
       window.open('Print_Positive_Negative_Patient_List.php?Item_ID='+Item_ID+'&Item_Name='+Item_Name+'&fromDate='+fromDate+'&date_To='+date_To);
    });
</script>

