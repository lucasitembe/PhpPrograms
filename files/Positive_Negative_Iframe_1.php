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
    $subcategory_ID=$_POST['subcategory_ID'];
    if($subcategory_ID=='All'){
      $category_ID=''; 
      $department='';  
    }else{
        
      $department='JOIN tbl_item_subcategory its ON its.Item_Subcategory_ID=i.Item_Subcategory_ID'; 
      $category_ID="AND i.Item_Subcategory_ID='$subcategory_ID'";
    }
}

echo '<center><table width =100% border=0 id="specimencollected" class="display">';
echo "<thead>
        <tr>
            <th style='text-align: center;'>SN</th>
            <th style='text-align: left'>TEST NAME</td>
            <th style='text-align: center;'><table style='border:0'><tr><th style='width:33%;text-align:center'>POSITIVE</th></tr></table></th>
            <th style='text-align: center;'><table style='border:0'><tr><th style='width:33%;text-align:center'>NEGATIVE</th> </tr></table></th>
            <th style='text-align: center;'><table style='border:0'><tr><th style='width:33%;text-align:center'>HIGH</th></tr></table></th>
            <th style='text-align: center;'><table style='border:0'><tr><th style='width:33%;text-align:center'>LOW</th> </tr></table></th>
            <th style='text-align: center;'><table style='border:0'><tr><th style='width:33%;text-align:center'>NORMAL</th></tr></table></th>
            <th style='text-align: center;'><table style='border:0'><tr><th style='width:33%;text-align:center'>ABNORMAL</th> </tr></table></th>
            <th style='text-align: center;'>TOTAL</th>
</tr>
     </thead>
     ";

    $count = 1;
   
    if (isset($_POST['action'])) {
     $numberSpecimen=mysqli_query($conn,"SELECT COUNT(ilc.Item_ID) AS items,i.Product_Name,i.Item_ID,test_result_ID FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_tests_parameters_results ttpr ON ttpr.ref_test_result_ID=tr.test_result_ID $department  WHERE ilc.Check_In_Type='Laboratory' AND tr.TimeSubmitted BETWEEN '" . $fromDate . "' AND '" . $toDate . "' $category_ID  GROUP BY ilc.Item_ID");
     $positive=0;
      $negative=0;
      $high=0;
      $low=0;
      $normal=0;
      $abnormal=0;
       $grandTotal=0; 
      while ($row2=  mysqli_fetch_assoc($numberSpecimen)){
            echo '<tr>';
            echo '<td style="text-align: center;">'.$count++.'</td>';
            echo '<td><span class="totalItems" id="'.$row2['Item_ID'].'" pp="'.$row2['Product_Name'].'"  style="cursor:pointer">'.$row2['Product_Name'].'</span></td>';
           
            $getresult= mysqli_query($conn,"SELECT * FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_tests_parameters_results ttpr ON ttpr.ref_test_result_ID=tr.test_result_ID JOIN tbl_patient_registration pr ON pp.Registration_ID=pr.Registration_ID  WHERE ilc.Check_In_Type='Laboratory' AND tr.TimeSubmitted BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND i.Item_ID='".$row2['Item_ID']."' AND result='POSITIVE' GROUP BY test_result_ID");
            $positive=  mysqli_num_rows($getresult);
           
            
            $getresult= mysqli_query($conn,"SELECT * FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_tests_parameters_results ttpr ON ttpr.ref_test_result_ID=tr.test_result_ID JOIN tbl_patient_registration pr ON pp.Registration_ID=pr.Registration_ID  WHERE ilc.Check_In_Type='Laboratory' AND tr.TimeSubmitted BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND i.Item_ID='".$row2['Item_ID']."' AND result='NEGATIVE' GROUP BY test_result_ID");
            $negative=  mysqli_num_rows($getresult);
           

            $getresult= mysqli_query($conn,"SELECT * FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_tests_parameters_results ttpr ON ttpr.ref_test_result_ID=tr.test_result_ID JOIN tbl_patient_registration pr ON pp.Registration_ID=pr.Registration_ID  WHERE ilc.Check_In_Type='Laboratory' AND tr.TimeSubmitted BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND i.Item_ID='".$row2['Item_ID']."' AND result='HIGH' GROUP BY test_result_ID");
            $high=  mysqli_num_rows($getresult);
           
            
            $getresult= mysqli_query($conn,"SELECT * FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_tests_parameters_results ttpr ON ttpr.ref_test_result_ID=tr.test_result_ID JOIN tbl_patient_registration pr ON pp.Registration_ID=pr.Registration_ID  WHERE ilc.Check_In_Type='Laboratory' AND tr.TimeSubmitted BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND i.Item_ID='".$row2['Item_ID']."' AND result='LOW' GROUP BY test_result_ID");
            $low=  mysqli_num_rows($getresult);
           
            
            $getresult= mysqli_query($conn,"SELECT * FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_tests_parameters_results ttpr ON ttpr.ref_test_result_ID=tr.test_result_ID JOIN tbl_patient_registration pr ON pp.Registration_ID=pr.Registration_ID  WHERE ilc.Check_In_Type='Laboratory' AND tr.TimeSubmitted BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND i.Item_ID='".$row2['Item_ID']."' AND result='NORMAL' GROUP BY test_result_ID");
            $normal=  mysqli_num_rows($getresult);
           
            
            $getresult= mysqli_query($conn,"SELECT * FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_tests_parameters_results ttpr ON ttpr.ref_test_result_ID=tr.test_result_ID JOIN tbl_patient_registration pr ON pp.Registration_ID=pr.Registration_ID  WHERE ilc.Check_In_Type='Laboratory' AND tr.TimeSubmitted BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND i.Item_ID='".$row2['Item_ID']."' AND result='ABNORMAL' GROUP BY test_result_ID");
            $abnormal=  mysqli_num_rows($getresult);
           
          $grandTotal= $positive+$negative+ $high+ $low+ $normal+$abnormal;
          echo '<td style="text-align: center;">'.$positive.'</td>';
          echo '<td style="text-align: center;">'.$negative.'</td>';
          echo '<td style="text-align: center;">'.$high.'</td>';
          echo '<td style="text-align: center;">'.$low.'</td>';
          echo '<td style="text-align: center;">'.$normal.'</td>';
          echo '<td style="text-align: center;">'.$abnormal.'</td>';
          echo '<td style="text-align:center;">'.$grandTotal.'</td>';
//            $positive=0;
//            $negative=0;
//            $high=0;
//            $low=0;
//            $normal=0;
//            $abnormal=0;
//            $grandTotal=0;
          echo '</tr>';
          }
          
}
echo "</table></center>";
?>

<div id="showAll" style="display: none;min-height: 300px">
    <div id="AllpatientsList" style="height:350px;overflow-y: auto">
        
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
       var Item_ID=$('#Item_ID').val();
       var Item_Name=$('#Item_Name').val();
       var fromDate=$('#date_From').val();
       var date_To=$('#date_To').val();
       window.open('Print_Positive_Negative_Patient_List.php?Item_ID='+Item_ID+'&Item_Name='+Item_Name+'&fromDate='+fromDate+'&date_To='+date_To);
    });
</script>

<script>
 $('#specimencollected').DataTable({
     "bJQueryUI": true
 });
</script>

