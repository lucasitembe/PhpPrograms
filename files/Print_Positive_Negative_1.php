<?php
session_start();
    include("includes/connection.php");
    $Employee = $_SESSION['userinfo']['Employee_Name'];
    if(isset($_GET['fromDate']) || isset($_GET['toDate'])){
        $fromDate=$_GET['fromDate'];
        $toDate=$_GET['toDate'];
        $subcategory_ID=$_GET['subcategory_ID'];
        if($subcategory_ID=='All'){
        $category_ID=''; 
        $department=''; 
        $department_Name='ALL';
        } else {
        
        $department='JOIN tbl_item_subcategory its ON its.Item_Subcategory_ID=i.Item_Subcategory_ID'; 
        $category_ID="AND i.Item_Subcategory_ID='$subcategory_ID'";
        $query_sub_cat = mysqli_query($conn,"SELECT its.Item_Subcategory_ID,its.Item_Subcategory_Name FROM `tbl_items` i JOIN tbl_item_subcategory its ON its.Item_Subcategory_ID=i.Item_Subcategory_ID WHERE its.Item_Subcategory_ID='$subcategory_ID'");
        $row = mysqli_fetch_array($query_sub_cat);
        $department_Name=$row['Item_Subcategory_Name'];
        }
    }else{
       $fromDate='';
        $toDate='';
        $subcategory_ID='';
        $category_ID=''; 
        $department=''; 
        $department_Name='';
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
		   <td style='text-align: center;'><b>FROM ".$fromDate." TO ".$toDate."</b></td>
		</tr>
                
                <tr>
		   <td style='text-align: center;'><b>DEPARTMENT: ".$department_Name."</b></td>
		</tr>
                <tr>
                    <td style='text-align: center;'><hr></td>
                </tr>
            </table>";
        $disp.='<center><table border="1" style="width:100%;border-collapse: collapse;">';
        $disp.="<thead>
            <tr>
            <th style='text-align: center;'>SN</th>
            <th style='text-align: left'>TEST NAME</td>
            <th style='text-align: center;'>POSITIVE</th>
            <th style='text-align: center;'>NEGATIVE</th>
             <th style='text-align: center;'>HIGH</th>
            <th style='text-align: center;'>LOW</th>
            <th style='text-align: center;'>NORMAL</th>
            <th style='text-align: center;'>ABNORMAL</th>
            <th style='text-align: center;'>TOTAL</th>
        </tr>
     </thead>";
   $count = 1;
    
   if (isset($_GET['fromDate']) || isset($_GET['toDate'])) {
     $numberSpecimen=mysqli_query($conn,"SELECT COUNT(tr.test_result_ID) AS items,i.Product_Name,i.Item_ID,test_result_ID FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_tests_parameters_results ttpr ON ttpr.ref_test_result_ID=tr.test_result_ID $department  WHERE ilc.Check_In_Type='Laboratory' AND tr.TimeSubmitted BETWEEN '" . $fromDate . "' AND '" . $toDate . "' $category_ID  GROUP BY ilc.Item_ID");
      $positive=0;
      $negative=0;
      $high=0;
      $low=0;
      $normal=0;
      $abnormal=0;
      $grandTotal=0;
      while ($row2=  mysqli_fetch_assoc($numberSpecimen)){
            //$Total=$row2['TOTALITEMS']*$row2['Price'];
            $disp.= '<tr>';
            $disp.= '<td style="text-align: center;">'.$count++.'</td>';
            $disp.= '<td><span class="totalItems" id="'.$row2['Item_ID'].'" pp="'.$row2['Product_Name'].'"  style="cursor:pointer">'.$row2['Product_Name'].'</span></td>';
            
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
          $disp.= '<td style="text-align: center;">'.$positive.'</td>';
          $disp.= '<td style="text-align: center;">'.$negative.'</td>';
          $disp.= '<td style="text-align: center;">'.$high.'</td>';
          $disp.= '<td style="text-align: center;">'.$low.'</td>';
          $disp.= '<td style="text-align: center;">'.$normal.'</td>';
          $disp.= '<td style="text-align: center;">'.$abnormal.'</td>';
          $disp.= '<td style="text-align:center;">'.$grandTotal.'</td>';
//          $positive=0;
//          $negative=0;
//          $high=0;
//          $low=0;
//          $normal=0;
//          $abnormal=0;
//          $grandTotal=0;
          $disp.= '</tr>';
          }        
}         

    $disp.= "</table>";
   
    include("MPDF/mpdf.php");
    $mpdf = new mPDF('c', 'Letter-L');
    $mpdf->SetFooter('{PAGENO}/{nb}|  Printed By '.$Employee.'                   {DATE d-m-Y H:m:s}');
    $mpdf->WriteHTML($disp);
    $mpdf->Output();
    exit;
?>