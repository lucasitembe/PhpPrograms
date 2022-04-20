<?php
include("../includes/connection.php");
if(isset ($_POST['revenueitemLists'])){
      if($_POST['revenueitemLists']=='lists'){
         $itemID=$_POST['ItemID'];
         $fromDate=$_POST['fromDate'];
         $toDate=$_POST['toDate'];
         $sponsorName=$_POST['sponsorName'];
         $transaction=$_POST['transaction'];
         echo "<table class='display' id='listItemsrevenue'> 
             <thead>
                <tr>
                    <th style='text-align:left'>S/n</th>
                    <th style='text-align:left'>Sponsor</th>
                    <th style='text-align:left'>Transaction Type</th>
                    <th style='text-align:left'>Amount Paid</th>
                    <th style='text-align:left'>Receipt Number</th>
                    <th style='text-align:left'>Date Taken</th>
                </tr>
            </thead>";  
         $sn=1;  

         if($sponsorName=='All'){ 
             $query="SELECT * FROM tbl_patient_payment_item_list AS tppit INNER JOIN tbl_patient_payments tpp ON tppit.Patient_Payment_ID=tpp.Patient_Payment_ID INNER JOIN tbl_items ti ON
                     ti.Item_ID=tppit.Item_ID WHERE tppit.Item_ID='$itemID' AND Payment_Date_And_Time BETWEEN '$fromDate' AND  '$toDate' AND Billing_Type='$transaction'";
             
             
         }  else {
              
           $query="SELECT * FROM tbl_patient_payment_item_list AS tppit INNER JOIN tbl_patient_payments tpp ON tppit.Patient_Payment_ID=tpp.Patient_Payment_ID INNER JOIN tbl_items ti ON
                   ti.Item_ID=tppit.Item_ID WHERE tpp.Sponsor_ID='$sponsorName' AND tppit.Item_ID='$itemID' AND Payment_Date_And_Time BETWEEN '$fromDate' AND  '$toDate' AND Billing_Type='$transaction'"; 
             
             
         }
         
         
          $GetResults= mysql_query($query);
           while ($row2=  mysql_fetch_assoc($GetResults)){
            echo "<tr>";
            echo "<td>".$sn++."</td>";
            echo "<td>".$row2['Sponsor_Name']."</td>";
            echo "<td style='text-align:left;'>".$row2['Billing_Type']."</td>";
            echo "<td style='text-align:left;'>".number_format($row2['Price'])."</td>";
            echo "<td style='text-align:left;'>".number_format($row2['Patient_Payment_ID'])."</td>";
            echo "<td style='text-align:left;'>".$row2['Payment_Date_And_Time']."</td>";
            echo "</tr>";
          }
          echo "</table>"; 
          echo '<div id="sumItemListValues" style="text-align:center"></div>';
      } 
} 
?>

<script>
$('#listItemsrevenue').dataTable({
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
          .column( 3 )
          .data()
          .reduce( function (a, b) {
              return intVal(a) + intVal(b);
          } );

      // Total over this page
      pageTotal = api
          .column( 3, { page: 'current'} )
          .data()
          .reduce( function (a, b) {
              return intVal(a) + intVal(b);
          }, 0 );

      $('#sumItemListValues').html( ''+addCommas(pageTotal)+' ( '+ addCommas(total) +' total)');
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
