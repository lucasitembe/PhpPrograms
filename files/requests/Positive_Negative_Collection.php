

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


<?php
    include("../includes/connection.php");
       if(isset($_POST['action'])){
     if($_POST['action']=='patientList'){
       $Item_ID=$_POST['id'];
       $fromDate=$_POST['fromDate'];
       $date_To=$_POST['date_To'];
       $sn=1;
//       $query=  mysqli_query($conn,"SELECT * FROM tbl_patient_payment_item_list AS tppit INNER JOIN tbl_patient_payments tpp ON tppit.Patient_Payment_ID=tpp.Patient_Payment_ID INNER JOIN tbl_patient_registration tpr ON tpr.Registration_ID=tpp.Registration_ID INNER JOIN tbl_items ti ON
//                   ti.Item_ID=tppit.Item_ID JOIN tbl_test_results ttr ON ttr.payment_item_ID=tppit.Patient_Payment_Item_List_ID INNER JOIN tbl_tests_parameters_results ttpr ON test_result_ID=ref_test_result_ID WHERE ti.Consultation_Type='Laboratory' AND ttpr.TimeSubmitted BETWEEN '$fromDate' AND  '$date_To' AND tppit.Item_ID='$Item_ID' AND (result='POSITIVE' || result='NEGATIVE' || result='HIGH' || result='LOW' || result='NORMAL' || result='ABNORMAL') GROUP BY tppit.Item_ID");
//       
//       
       
       
       $query=  mysqli_query($conn,"SELECT * FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_tests_parameters_results ttpr ON ttpr.ref_test_result_ID=tr.test_result_ID JOIN tbl_patient_registration pr ON pp.Registration_ID=pr.Registration_ID  WHERE ilc.Check_In_Type='Laboratory' AND tr.TimeSubmitted BETWEEN '" . $fromDate . "' AND '" . $date_To . "' AND i.Item_ID='$Item_ID' GROUP BY test_result_ID");
       echo "<table class='display' id='revenuespecReportx' style='width:100%'>
           <thead>
                <tr>
                    <th style='text-align:center'>S/N</th>
                    <th style='text-align:left'>PATIENT NAME</th>
                    <th style='text-align:center'>GENDER</th>
                    <th style='text-align:center'>AGE</th>
                    <th style='text-align:center'>DATE TEST TAKEN</th>
                    <th style='text-align:center'>RESULTS</th>
                </tr>
           </thead>"; 
            $Today_Date = mysqli_query($conn,"select now() as today");
            
            while($row = mysqli_fetch_array($Today_Date)){
                $original_Date = $row['today'];
                $new_Date = date("Y-m-d", strtotime($original_Date));
                $Today = $new_Date;
                $age ='';
            }
       while ($row= mysqli_fetch_assoc($query)){
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1 -> diff($date2);
            $age = $diff->y." Years, ";
            $age.= $diff->m." Months";
           echo '<tr>';
           echo '<td style="text-align:center">'.$sn++.'</td>';
           echo '<td><p style="cursor:pointer">'.$row['Patient_Name'].'</p></td>';
           echo '<td style="text-align:center;">'.$row['Gender'].'</td>';
           echo '<td style="text-align:center;">'.$age.'</td>';
           echo '<td style="text-align:center;">'.$row['TimeSubmitted'].'</td>';
           echo '<td style="text-align:center;">'.$row['result'].'</td>';
           echo '</tr>'; 
           
           
       }
       echo "</table>";
       
    }            
 }
?>
