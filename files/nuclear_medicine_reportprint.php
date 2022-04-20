<?php
include './includes/connection.php';


$filter = '';
$filterSub = '';

// if (isset($_POST['action']) && $_POST['action'] == 'getItem') {
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
    $sponsorName = $_GET['Sponsor'];
    $Item_ID = $_GET['Item_ID'];
    $Itemstatus = $_GET['Itemstatus'];
    if ($sponsorName != 'All') {
        $filter .=" AND pc.Sponsor_ID='$sponsorName'";
    }

    if ($Item_ID != 'All') {
        $filterSub .=" AND ilc.Item_ID='$Item_ID'";
    }
    if($Itemstatus != 'All'){
        $filter .=" AND ilc.Status='$Itemstatus'";
    }
//    die($toDate);
    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $Surgery = $new_Date;
    } 
    
    $select_done = mysqli_query($conn, "SELECT ilc.Payment_Cache_ID, pc.Registration_ID, Date_of_Birth,Gender, Patient_Name, Product_Name, ilc.Status, ilc.Item_ID, Payment_Item_Cache_List_ID, Transaction_Date_And_Time FROM tbl_payment_cache  pc, tbl_patient_registration pr, tbl_items i, tbl_item_list_cache ilc WHERE  i.Item_ID = ilc.Item_ID  AND pc.Payment_cache_ID=ilc.Payment_cache_ID AND pc.Registration_ID=pr.Registration_ID AND Transaction_Date_And_Time BETWEEN DATE('$fromDate') AND DATE('$toDate')  AND ilc.Check_In_Type='Nuclearmedicine' $filterSub  $filter") or die(mysqli_error($conn));
    $htm = "<table width ='100%' height = '30px'>
            <tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
            <tr><td style='text-align: center;'><b><span style='font-size: x-small;'><u>NUCLEAR MEDICINE ".strtoupper($Itemstatus)." PATEINT  REPORT.</u></span></b></td></tr></br>
            <tr><td style='text-align: center;'><b><span style='font-size: x-small;'>FROM ".@date("d F Y H:i:s",strtotime($fromDate))." TO ".@date("d F Y H:i:s",strtotime($toDate))."</span></b></td></tr>
        </table>";
    $htm.= "<table class='numberTests' width='100%'> 
    <thead>
       <tr>
           <th style='text-align:left;width:5%'>S/n</th>
           <th style='text-align:left'>Patient Name</th>
           <th style='text-align:left'>Reg:#</th>
           <th style='text-align:left;width:7%'>Age</th>
           <th style='text-align:left;width:8%'>Gender</th>
           <th style='text-align:left'>SPonsor</th>
           <th style='text-align:left'>Test Name</th>
           <th style='text-align:left'>Order Date</th>
           <th>Test Date</th>
           <th style='text-align:left'>Test Report</th>
       </tr>
   </thead>
   "; 
   $sn = 1;
 if(mysqli_num_rows($select_done) >0 ){
    while ($row1 = mysqli_fetch_array($select_done)) {
        $Payment_Cache_ID = $row1['Payment_Cache_ID'];
        $Registration_ID = $row1['Registration_ID'];
        $Date_of_Birth = $row1['Date_of_Birth'];
        $Patient_Name = $row1['Patient_Name'];
        $Item_ID = $row1['Item_ID'];
        $created_at = $row1['created_at'];
        $Transaction_Date_And_Time = $row1['Transaction_Date_And_Time'];
        $Product_Name = $row1['Product_Name'];
        $Payment_Item_Cache_List_ID =$row1['Payment_Item_Cache_List_ID'];
        $Gender = $row1['Gender'];
        $Status = $row1['Status'];
        

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_of_Birth);
        $diff = $date1 -> diff($date2);
        $Age = $diff->y." Y, ";
        $Age .= $diff->m." M, ";
        $Age .= $diff->d." D";
        $Guarantor_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Guarantor_Name FROM tbl_payment_cache pc,  tbl_sponsor sp WHERE sp.Sponsor_ID=pc.Sponsor_ID AND Payment_Cache_ID='$Payment_Cache_ID'"))['Guarantor_Name'];

            $htm.= '<tr>';
            $htm.= '<td>' . $sn . '</td>';
            $htm.= "<td>$Patient_Name</td>";
            $htm.= "<td>$Registration_ID</td>";
            $htm.= "<td>$Age</td>";
            $htm.= "<td>$Gender</td>";
            $htm.= "<td>$Guarantor_Name</td>";
            
            if($Status =='served' || $Status =='paid' || $Status =='pending'){
            $select = mysqli_query($conn,"SELECT created_at, nmr.Payment_Item_Cache_List_ID from tbl_nuclear_medicine_report nmr, tbl_item_list_cache ilc where  nmr.Payment_Item_Cache_List_ID = ilc.Payment_Item_Cache_List_ID and  nmr.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
            $no = mysqli_num_rows($select);
            if($no > 0){
                while ($dt = mysqli_fetch_array($select)) {
                    $Payment_Item_Cache_List = $dt['Payment_Item_Cache_List_ID'];
                    $created_at = $dt['created_at'];
                    $resultform = '<a href="nuclear_medicinereport.php?Payment_Item_Cache_List_ID='.$Payment_Item_Cache_List.'&Payment_Cache_ID='.$Payment_Cache_ID.'&Registration_ID='.$Registration_ID.'" class="art-button-green" >FORMS</a>';
                }
            }else{
                $resultform = " ".ucwords($Status)." and Not processed";
                $created_at = 'Not set';
            }
        }else{
            $resultform = " ".ucwords($Status)." Not Done";
            $created_at = 'Not set';
        }
            $htm.= '<td>' . $Product_Name . '</td>';
            $htm.= "<td>$Transaction_Date_And_Time</td>";

           $htm.= ' <td>'.$created_at.'</td>';

            $htm.= "<td>$resultform</td>";
            $htm.= '</tr>';
        
         
       $sn++;
    }
 }else{
     $htm .="<tr><td colspan='10' style='color:red;'>No Result Found</td></tr>";
 }
   $htm.= '</table>';
// }


include("./MPDF/mpdf.php");
$mpdf=new mPDF('','A3', 0, '', 15,15,20,40,15,35, 'P');
$mpdf->SetFooter('|{PAGENO}/{nb}|{DATE d-m-Y}');
$mpdf->WriteHTML($htm);
$mpdf->Output();