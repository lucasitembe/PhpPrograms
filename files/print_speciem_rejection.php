
<?php
session_start();
include("./includes/connection.php");
$Employee = $_SESSION['userinfo']['Employee_Name'];
$filter = '   WHERE DATE(sr.Date_Rejected) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())';

$Guarantor_Name="All";
$toDate='';
$fromDate='';
          if(isset($_GET['fromDate'])){
               $fromDate=$_GET['fromDate'];
              $toDate=$_GET['toDate'];
              $sponsorName=$_GET['Sponsor'];
              $filter = "  WHERE sr.Date_Rejected BETWEEN '".$fromDate."' AND '" . $toDate . "' AND sr.Rejection_Status='Rejected'";
              if ($sponsorName != 'All') {
                    $filter .=" AND pp.Sponsor_ID='$sponsorName'";
                    
                    $rs=  mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$sponsorName'") or die(mysqli_error($conn));
               
                    $Guarantor_Name=  mysqli_fetch_assoc($rs)['Guarantor_Name'];
              }
           }
           
$htm = "<center><img src='branchBanner/branchBanner.png' width='100%' ></center>";
$htm.="<p align='center'><b>REJECTED SPECIMEN REPORT <br/>FROM</b> <b style=''>" . $fromDate . "</b> <b>TO</b> <b style=''>" . $toDate . "</b>"
        . "<br/>"
        . "<b>Sponsor:</b>$Guarantor_Name"
        . "</p>";
$htm .= "<table class='display' id='numberTests' width='100%' border='1' style='border-collapse: collapse;'>
             <thead>
                <tr>
                    <th style='text-align:left;width:5%'>S/n</th>
                    <th style='text-align:left'>Patient Name</th>
                    <th style='text-align:left'>Test Name</th>
                    <th style='text-align:left'>Rejected specimen</th>
                    <th style='text-align:left'>Rejection reason</th>
                    <th style='text-align:left'>Date and Time</th>
                    <th style='text-align:left'>Rejected by</th>
                </tr>
            </thead>";
             
                  $number_of_item="SELECT * FROM tbl_specimen_results sr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=sr.payment_item_ID JOIN tbl_laboratory_specimen ls ON sr.Specimen_ID=ls.Specimen_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=pp.Registration_ID JOIN tbl_employee te ON te.Employee_ID=sr.rejected_by JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID JOIN tbl_items ti ON ti.Item_ID=ilc.Item_ID $filter";
                  
            // echo $number_of_item;exit;
             $number_of_item_results= mysqli_query($conn,$number_of_item) or die(mysqli_error($conn));
                    
            $sn=1;  
            $grandTotal=0;
            while ($row=  mysqli_fetch_assoc($number_of_item_results)){
                
                  $number_item_count="SELECT ls.Specimen_ID FROM tbl_specimen_results sr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=sr.payment_item_ID JOIN tbl_laboratory_specimen ls ON sr.Specimen_ID=ls.Specimen_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID $filter AND sr.Specimen_ID='".$row['Specimen_ID']."'";
                  
                  //die($number_item_count);
                  $number_of_item_count_results= mysqli_query($conn,$number_item_count) or die(mysqli_error($conn));
                  $itemCount=  mysqli_num_rows($number_of_item_count_results);
                  //$itemCount=  mysqli_fetch_assoc($number_of_item_count_results)['Number_Of_Item'];
                
                $htm .= '<tr>';
                $htm .='<td>'.$sn++.'.</td>';
                $htm .='<td>'.$row['Patient_Name'].'</td>';
                $htm .='<td>'.$row['Product_Name'].'</td>';
                $htm .='<td>'.$row['Specimen_Name'].'</td>';
                $htm .='<td>'.$row['rejected_reason'].'</td>';
                $htm .='<td>'.$row['Date_Rejected'].'</td>';
                $htm .='<td>'.$row['Employee_Name'].'</td>';
                $htm .= '</tr>';
            }
          $htm .= '</table>';  
         
        include("MPDF/mpdf.php");
        $mpdf=new mPDF(); 

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetFooter('{PAGENO}/{nb}|  Printed By '.$Employee.'                   {DATE d-m-Y H:m:s}');
        $mpdf->WriteHTML($htm);
        $mpdf->Output();
        exit; 

?>
