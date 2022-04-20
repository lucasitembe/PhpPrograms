<?php
session_start();
    include("includes/connection.php");
    $Employee = $_SESSION['userinfo']['Employee_Name'];
if(isset($_GET['action'])){
 $fromDate=$_GET['fromDate'];  
 
 $toDate=$_GET['toDate'];  
 $subcategory_ID=$_GET['subcategory_ID'];
     $disp= "<table width ='100%' height = '30px'>
		<tr>
		    <td>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td>
		</tr>
		<tr>
		   <td style='text-align: center;'><b>SPECIMEN COLLECTION WORK SHEET</b></td>
		</tr>
                <tr>
		   <td style='text-align: center;'><b>FROM ".$fromDate." TO ".$toDate."</b></td>
		</tr>
                <tr>
                    <td style='text-align: center;'><hr></td>
                </tr>
            </table>";
    
  $disp.='<table border="1" style="width:100%;border-collapse: collapse;">
       <tr>
        <td>
        <b> SN </b>
        </td>
        <td>
         <b>PATIENT NAME</b>
        </td>
        <td>
         <b>REG#</b>
        </td>
        <td>
        <b> SPONSOR </b>
        </td>
         <td>
        <b>AGE</b>
        </td>
        <td>
         <b>GENDER</b>
        </td>
        <td>
         <b>PHONE #</b>
        </td>
        <td>
         <b>ORDERED BY</b>
        </td>
       </tr>';
 $query_sub_cat = mysqli_query($conn,"SELECT its.Item_Subcategory_ID,its.Item_Subcategory_Name,i.Product_Name,i.Item_ID,tsr.collection_Status FROM `tbl_items` i JOIN tbl_item_subcategory its ON its.Item_Subcategory_ID=i.Item_Subcategory_ID JOIN tbl_item_list_cache il ON i.Item_ID = il.Item_ID JOIN tbl_specimen_results tsr ON tsr.payment_item_ID=il.Payment_Item_Cache_List_ID JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID JOIN tbl_patient_registration pr ON pr.Registration_ID =pc.Registration_ID  WHERE i.`Consultation_Type`='Laboratory' AND i.Item_Subcategory_ID='$subcategory_ID' AND collection_Status='collected' AND tsr.TimeCollected BETWEEN '$fromDate' AND '$toDate' GROUP BY i.Item_ID");
 $sn=1;
 while ($row=  mysqli_fetch_assoc($query_sub_cat)){
     $disp.= '<tr>
            <td>
            </td>
             <td>
               <span style="font-size:15px"><b>'.strtoupper($row['Product_Name']).'</b></span>
            </td>
           
          </tr>';
         
     $select_Filtered_Patients =mysqli_query($conn,"SELECT 'cache' as Status_From,te.Employee_Name,pr.Patient_Name,pc.Sponsor_Name,pr.Date_Of_Birth,tsr.TimeCollected,tsr.collection_Status,
                                           pr.Gender,pr.Phone_Number,pr.Registration_ID as registration_number,pc.Receipt_Date as Required_Date,
                                           pc.Payment_Cache_ID as payment_id,il.Process_Status as Status,il.Consultant as Doctors_Name,il.Transaction_Date_And_Time as Transaction_Date_And_Time
                                            FROM tbl_item_list_cache as il
                                            JOIN tbl_items as i ON i.Item_ID = il.Item_ID 
                                            JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID 
                                            JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
                                            JOIN tbl_sponsor AS sp ON sp.Sponsor_ID =pr.Sponsor_ID
                                            JOIN tbl_specimen_results tsr ON tsr.payment_item_ID=il.Payment_Item_Cache_List_ID
                                            JOIN tbl_employee te ON te.Employee_ID=il.Consultant_ID
                                            WHERE i.Item_ID='".$row['Item_ID']."' AND tsr.TimeCollected BETWEEN '$fromDate' AND '$toDate' AND tsr.collection_Status='collected'");    
     $Today_Date = mysqli_query($conn,"select now() as today");
            while($row = mysqli_fetch_array($Today_Date)){
                $original_Date = $row['today'];
                $new_Date = date("Y-m-d", strtotime($original_Date));
                $Today = $new_Date;
                $age ='';
            }
     while ($result=  mysqli_fetch_assoc($select_Filtered_Patients)){
         $Date_Of_Birth = $result['Date_Of_Birth'];
        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age.= $diff->m . " Months";
         
         
         $disp.= '<tr>
                <td>
                 '.$sn++.'
                </td>
                <td>
                    '.$result['Patient_Name'].'
                </td>
                <td>
                    '.$result['registration_number'].'
                </td>
                <td>
                    '.$result['Sponsor_Name'].'
                </td>
                <td>
                    '.$age.'
                </td>
                
                <td>
                    '.$result['Gender'].'
                </td>
                
                <td>
                    '.$result['Phone_Number'].'
                </td>
                 <td>
                    '.$result['Employee_Name'].'
                </td>
          </tr>'; 
     }
     $sn=1;
     
 }
 
 $disp.='</table>';
}
   
    include("MPDF/mpdf.php");
    $mpdf = new mPDF('c', 'Letter-L');
    $mpdf->SetFooter('{PAGENO}/{nb}|  Printed By '.$Employee.'                   {DATE d-m-Y H:m:s}');
    $mpdf->WriteHTML($disp);
    $mpdf->Output();
    exit;
?>