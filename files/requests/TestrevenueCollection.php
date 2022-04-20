<script>
    $('.totalItems').on('click',function(){
       var fromDate= $('#date_From').val();
       var toDate=$('#date_To').val();
       var sponsorName=$('#sponsor').val();
       var itemId=$(this).attr('id');
       var transaction=$(this).attr('trans');
       var itemName=$(this).text();
        $('#revenueitemsList').dialog({
            modal:true,
            width:'98%',
            height:650,
            resizable:true,
            draggable:true 
        });
       
        $.ajax({
            type:'POST', 
            url:'requests/revenueItemList.php',
            data:'revenueitemLists=lists&ItemID='+itemId+'&fromDate='+fromDate+'&toDate='+toDate+'&sponsorName='+sponsorName+'&transaction='+transaction,
            cache:false,
            success:function(html){
              $('#showrevenueitemList').html(html);
            }
        });
        $('#revenueitemsList').dialog('option','title',itemName); 
    });
</script>

<script type="text/javascript">   
    $('#revenuespecReport').dataTable({
    "bJQueryUI":true,
    "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // Remove the formatting to get integer data for summation
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };
        // Total over all pages
        total = api
            .column( 4 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            } );

        // Total over this page
        pageTotal = api
            .column( 4, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        // Update footer
    //            $( api.column( 3 ).footer() ).html(
    //                ''+pageTotal +' ( '+ total +' total)'
    //            );

        $('#sumValues').html( ''+addCommas(pageTotal) +' ( '+ addCommas(total) +' total)');
    }

    });
    
    function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
</script>




<!--Specific test revenue collection-->
<script type="text/javascript">   
    $('#specificrevenue').dataTable({
    "bJQueryUI":true,
    "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // Remove the formatting to get integer data for summation
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };
        // Total over all pages
        total = api
            .column( 4 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            } );

        // Total over this page
        pageTotal = api
            .column( 4, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        // Update footer
    //            $( api.column( 3 ).footer() ).html(
    //                ''+pageTotal +' ( '+ total +' total)'
    //            );

        $('#SpecificsumValues').html( ''+addCommas(pageTotal) +' ( '+ addCommas(total) +' total)');
    }

    });
    
    function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
</script>



<?php
    include("../includes/connection.php");
       if(isset($_POST['action'])){
           if($_POST['action']=='testrevenuebyDate'){
              $fromDate=$_POST['date_From'];
              $toDate=$_POST['date_To'];
              $sponsorName=$_POST['sponsorName'];
              if($sponsorName=='All'){
               $numberSpecimen="SELECT * ,SUM(Price) AS TOTALAMOUNT,COUNT(Patient_Payment_Item_List_ID) AS TOTALITEMS FROM tbl_patient_payment_item_list AS tppit INNER JOIN tbl_patient_payments tpp ON tppit.Patient_Payment_ID=tpp.Patient_Payment_ID INNER JOIN tbl_items ti ON
                   ti.Item_ID=tppit.Item_ID WHERE ti.Consultation_Type='Laboratory' AND Payment_Date_And_Time BETWEEN '$fromDate' AND  '$toDate' GROUP BY tppit.Item_ID,Billing_Type";
                }  else {

                   $numberSpecimen="SELECT * ,SUM(Price) AS TOTALAMOUNT,COUNT(Patient_Payment_Item_List_ID) AS TOTALITEMS FROM tbl_patient_payment_item_list AS tppit INNER JOIN tbl_patient_payments tpp ON tppit.Patient_Payment_ID=tpp.Patient_Payment_ID INNER JOIN tbl_items ti ON
                   ti.Item_ID=tppit.Item_ID WHERE ti.Consultation_Type='Laboratory' AND tpp.Sponsor_ID='$sponsorName' AND Payment_Date_And_Time BETWEEN '$fromDate' AND  '$toDate' GROUP BY tppit.Item_ID,Billing_Type";

              }
             //echo $numberSpecimen;
             $totalrevenue= mysqli_query($conn,$numberSpecimen);
             echo "<table class='display' id='revenuespecReport'> 
             <thead>
                <tr>
                    <th style='text-align:left'>S/n</th>
                    <th style='text-align:left'>Test Name</th>
                    <th style='text-align:left'>Transaction Type</th>
                    <th style='text-align:left'>Quantity</th>
                    <th style='text-align:left'>Total Amount(Tsh)</th>
                </tr>
            </thead>";             
            $sn=1;  
            $grandTotal=0;
            while ($row2=  mysqli_fetch_assoc($totalrevenue)){
            $Total=$row2['TOTALITEMS']*$row2['Price'];
            $grandTotal=$grandTotal+$Total;
            echo '<tr>';
            echo '<td>'.$sn++.'</td>';
            echo '<td><p class="totalItems" id="'.$row2['Item_ID'].'" trans="'.$row2['Billing_Type'].'" style="cursor:pointer">'.$row2['Product_Name'].'</p></td>';
            echo '<td style="text-align:left;">'.$row2['Billing_Type'].'</td>';
            echo '<td style="text-align:left;">'.$row2['TOTALITEMS'].'</td>';
            echo '<td style="text-align:left;">'.number_format($row2['TOTALAMOUNT']).'</td>';//
            echo '</tr>';
          }
          echo '</table>'; 
          echo '<div id="sumValues" style="text-align:center"></div>'; 
          echo '<a href="TestrevenueCollection_excell.php?fromDate='.$fromDate.'&toDate='.$toDate.'&sponsorName='.$sponsorName.'" class="art-button-green">PRINT EXCELL</a>';
          
        //   $fromDate=$_GET['fromDate'];
        //   $toDate=$_GET['toDate'];
        //   $sponsorName=$_GET['sponsorName'];
            }elseif ($_POST['action']=='specifictestrevenue') {

              $fromDate=$_POST['from_Date'];
              $toDate=$_POST['to_Date'];
              $sponsorName=$_POST['sponsor'];
              $testName=$_POST['testName'];
              
              if($testName=='All'){
                 $test=''; 
              }  else {
                $test="AND tppit.Item_ID='$testName'"; 
              }
              
                if($sponsorName=='All'){
                  $numberSpecimen="SELECT * ,SUM(Price) AS TOTALAMOUNT,COUNT(Patient_Payment_Item_List_ID) AS TOTALITEMS FROM tbl_patient_payment_item_list AS tppit INNER JOIN tbl_patient_payments tpp ON tppit.Patient_Payment_ID=tpp.Patient_Payment_ID INNER JOIN tbl_items ti ON
                   ti.Item_ID=tppit.Item_ID WHERE ti.Consultation_Type='Laboratory' AND Payment_Date_And_Time BETWEEN '$fromDate' AND  '$toDate' $test GROUP BY tppit.Item_ID,Billing_Type";
                }  else {

                   $numberSpecimen="SELECT * ,SUM(Price) AS TOTALAMOUNT,COUNT(Patient_Payment_Item_List_ID) AS TOTALITEMS FROM tbl_patient_payment_item_list AS tppit INNER JOIN tbl_patient_payments tpp ON tppit.Patient_Payment_ID=tpp.Patient_Payment_ID INNER JOIN tbl_items ti ON
                   ti.Item_ID=tppit.Item_ID WHERE ti.Consultation_Type='Laboratory' AND tpp.Sponsor_ID='$sponsorName' AND Payment_Date_And_Time BETWEEN '$fromDate' AND  '$toDate' $test GROUP BY tppit.Item_ID,Billing_Type";

                }
//               echo $numberSpecimen;
//               
//               
               $totalrevenue= mysqli_query($conn,$numberSpecimen);
               echo "<table class='display' id='specificrevenue'> 
               <thead>
                  <tr>
                      <th style='text-align:left'>S/n</th>
                      <th style='text-align:left'>Test Name</th>
                      <th style='text-align:left'>Transaction Type</th>
                      <th style='text-align:left'>Quantity</th>
                      <th style='text-align:left'>Total Amount(Tsh)</th>
                  </tr>
              </thead>";             
              $sn=1;  
              $grandTotal=0;
              while ($row2=  mysqli_fetch_assoc($totalrevenue)){
              $Total=$row2['TOTALITEMS']*$row2['Price'];
              $grandTotal=$grandTotal+$Total;
              echo '<tr>';
              echo '<td>'.$sn++.'</td>';
              echo '<td><p class="totalItems" id="'.$row2['Item_ID'].'" style="cursor:pointer">'.$row2['Product_Name'].'</p></td>';
              echo '<td style="text-align:left;">'.$row2['Billing_Type'].'</td>';
              echo '<td style="text-align:left;">'.$row2['TOTALITEMS'].'</td>';
              echo '<td style="text-align:left;">'.number_format($row2['TOTALAMOUNT']).'</td>';//
              echo '</tr>';
            }
            echo '</table>';  
            echo '<div id="SpecificsumValues" style="text-align:center"></div>'; 
                                      
    }     
        //   echo "<input type>";  
 }
?>
<div id="revenueitemsList" style="display: none">
    <div id="showrevenueitemList">
      
        
    </div>
</div>


<script type="text/javascript">
    $('#date_From').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    startDate:  'now'
    });
    $('#date_From').datetimepicker({value:'',step:30});
    $('#date_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    startDate:'now'
    });
    $('#date_To').datetimepicker({value:'',step:30});
    
</script>


