<?php
    include("includes/connection.php");
    if(isset($_GET['fromDate']) || isset($_GET['toDate'])){
        $fromDate=$_GET['fromDate'];
        $toDate=$_GET['date_To'];
        $itemName=$_GET['Item_Name'];
        $Item_ID=$_GET['Item_ID'];
    }else{
       $fromDate='';
        $toDate='';
        $itemName='';
        $Item_ID='';
    }
    
   
    $disp= "<table width ='100%' height = '30px'>
		<tr>
		    <td>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td>
		</tr>
		<tr>
		   <td style='text-align: center;'><b>LABORATORY TEST RESULTS</b></td>
		</tr>
                <tr>
		   <td style='text-align: center;'><b>TEST NAME: ".$itemName."</b></td>
		</tr>
                <tr>
		   <td style='text-align: center;'><b>FROM ".$fromDate." TO ".$toDate."</b></td>
		</tr>
                <tr>
                    <td style='text-align: center;'><hr></td>
                </tr>
            </table>";
    
       if(isset($_GET['fromDate']) || isset($_GET['date_To'])){
       $Item_ID=$_GET['Item_ID'];
       $fromDate=$_GET['fromDate'];
       $date_To=$_GET['date_To'];
       $sn=1;
       $query=  mysqli_query($conn,"SELECT * FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_tests_parameters_results ttpr ON ttpr.ref_test_result_ID=tr.test_result_ID JOIN tbl_patient_registration pr ON pp.Registration_ID=pr.Registration_ID  WHERE ilc.Check_In_Type='Laboratory' AND tr.TimeSubmitted BETWEEN '" . $fromDate . "' AND '" . $date_To . "' AND i.Item_ID='$Item_ID' GROUP BY tr.test_result_ID");
       
       
       $disp.= "<table style='width:100%;border-collapse: collapse;' border='1'>
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
           $disp.= '<tr>'; 
           $disp.='<td style="text-align:center">'.$sn++.'</td>';
           $disp.='<td><p style="cursor:pointer">'.$row['Patient_Name'].'</p></td>';
           $disp.='<td style="text-align:center;">'.$row['Gender'].'</td>';
           $disp.='<td style="text-align:center;">'.$age.'</td>';
           $disp.='<td style="text-align:center;">'.$row['TimeSubmitted'].'</td>';
           $disp.='<td style="text-align:center;">'.$row['result'].'</td>';
          $disp.= '</tr>'; 
           
           
       }
       
       $disp.="</table>";
    }
    

    include("MPDF/mpdf.php");
    $mpdf = new mPDF('c', 'Letter-L');
    $mpdf->SetFooter('{PAGENO}/{nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($disp);
    $mpdf->Output();
    exit;
?>