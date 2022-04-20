<?php
include("../includes/connection.php");

 if(isset($_POST['action'])){
    if($_POST['action']=='parameterhistory'){
     $paymentID=$_POST['paymentID'];
     
     $getSpecimen="SELECT * FROM tbl_specimen_results JOIN tbl_employee ON specimen_results_Employee_ID=Employee_ID JOIN tbl_laboratory_specimen
             ON tbl_laboratory_specimen.Specimen_ID=tbl_specimen_results.Specimen_ID WHERE payment_item_ID='".$paymentID."'";
             $mysqlQuery=  mysqli_query($conn,$getSpecimen);
                echo "<center><table class='' style='width:100%'>";       
                echo "<tr style='background-color:rgb(200,200,200)'>
                <th width='1%'>S/N</th>
                <th width=''>Specimen Collected</th>
                <th width=''>Specimen Collector</th>
                <th width=''>Time Collected</th>
                <th width=''>Bar Code</th>
        </tr>";
         $sn=1;       
            while ($row2=  mysqli_fetch_assoc($mysqlQuery)){
            echo '<tr>';
            echo '<td>'.$sn++.'</td>';
            echo '<td>'.$row2['Specimen_Name'].'</td>';
            echo '<td>'.$row2['Employee_Name'].'</td>';
            echo '<td>'.$row2['TimeCollected'].'</td>';
            echo '<td>'.$row2['BarCode'].'</td>';
            echo '</tr>';
          }
                
         echo '</table>';    

         }

     }

?>
