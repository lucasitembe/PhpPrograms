<?php
session_start();
    include("includes/connection.php");
    $Employee = $_SESSION['userinfo']['Employee_Name'];
    if(isset($_GET['fromDate']) || isset($_GET['toDate'])){
        $fromDate=$_GET['fromDate'];
        $toDate=$_GET['toDate'];
        
    }else{
       $fromDate='';
        $toDate='';
    }
    $disp= "<table width ='100%' height = '30px'>
		<tr>
		    <td>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td>
		</tr>
		<tr>
		   <td style='text-align: center;'><b>NEGATIVE &amp; POSITIVE TEST RESULTS</b></td>
		</tr>
                <tr>
		   <td style='text-align: center;'><b>FROM ".$fromDate." TO ".$toDate."</b></td>
		</tr>
                <tr>
                    <td style='text-align: center;'><hr></td>
                </tr>
            </table>";
    
        $disp.='<center><table width =100% border=1 style="border-collapse: collapse;">';
        $disp.="<thead>
            <tr >
                <th style='text-align: center;'>SN</th>
                <th style='text-align: left;width:50%'>TEST NAME</td>
                <th style='text-align: left'>RESULT</td>
                <th style='text-align: center;'>< 1 Month <br /> <span> <span style='text-align:center'>M</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='text-align:center'>F</span> </span></th>
                <th style='text-align: center;'>1-11 Months <br /> <span> <span style='text-align:center'>M</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='text-align:center'>F</span> </span></th>
                <th style='text-align: center;'>12-59 Months <br /> <span> <span style='text-align:center'>M</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='text-align:center'>F</span> </span></th>
                <th style='text-align: center;'>> 5 Years <br /> <span > <span style='text-align:center'>M</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='text-align:center'>F</span> </span></th>
                <th style='text-align: center;'>Total <br /> <span > <span style='text-align:center'>M</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='text-align:center'>F</span> </span></th>
                <th style='text-align: center;'><span>Total</span></th>
            </tr>
     </thead>";
   $count = 1;
   $grandTotal=0; 
   if (isset($_GET['fromDate']) || isset($_GET['toDate'])) {
      $numberSpecimen=mysqli_query($conn,"SELECT COUNT(ilc.Item_ID) AS items,i.Product_Name,i.Item_ID,test_result_ID FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_tests_parameters_results ttpr ON ttpr.ref_test_result_ID=tr.test_result_ID WHERE ilc.Check_In_Type='Laboratory' AND tr.TimeSubmitted BETWEEN '" . $fromDate . "' AND '" . $toDate . "' GROUP BY ilc.Item_ID");
      $under1monthMalepos=0;
      $under1monthMaleneg=0;
      $totalunder1monthMale=0;
      $under1monthFemalepos=0;
      $under1monthFemaleneg=0;
      $totalmonthFemale=0;
      $month11Malepos=0;
      $month11Maleneg=0;
      $total11Male=0;
      $month11Femalepos=0;
      $month11Femaleneg=0;
      $total11Female=0;
      $month12Malepos=0;
      $month12Maleneg=0;
      $total12Male=0;
      $month12Femalepos=0;
      $month12Femaleneg=0;
      $total12Female=0;
      $Year5Malepos=0;
      $Year5Maleneg=0;
      $total5Male=0;
      $Year5Femalepos=0;
      $Year5Femaleneg=0;
      $total5Female=0;
      
      $TotalMalepos=0;
      $TotalMaleneg=0;
      $TotalFemalepos=0;
      $TotalFemaleneg=0;
      $GrandTotalpos=0;
      $GrandTotalneg=0;
     
       $Today_Date = mysqli_query($conn,"select now() as today");
            
            while($row = mysqli_fetch_array($Today_Date)){
                $original_Date = $row['today'];
                $new_Date = date("Y-m-d", strtotime($original_Date));
                $Today = $new_Date;
                $age ='';
            }
            
      while ($row2=  mysqli_fetch_assoc($numberSpecimen)){
           $disp.= '<tr>';
           $disp.= '<td style="text-align: center;">'.$count++.'</td>';
           $disp.= '<td><span class="totalItems" id="'.$row2['Item_ID'].'" pp="'.$row2['Product_Name'].'"  style="cursor:pointer">'.$row2['Product_Name'].'</span></td>';
          
            
            $getresult= mysqli_query($conn,"SELECT * FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_tests_parameters_results ttpr ON ttpr.ref_test_result_ID=tr.test_result_ID JOIN tbl_patient_registration tpr ON tpr.Registration_ID=pp.Registration_ID JOIN tbl_patient_registration pr ON pp.Registration_ID=pr.Registration_ID  WHERE ilc.Check_In_Type='Laboratory' AND tr.TimeSubmitted BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND i.Item_ID='".$row2['Item_ID']."' AND (result='POSITIVE' || result='NEGATIVE') GROUP BY test_result_ID");
            
            while ($result=  mysqli_fetch_assoc($getresult)){
                            $gender=$result['Gender'];
            $test_res=  strtoupper($result['result']);
           // echo $test_res.'<br />';
            $Date_Of_Birth = $result['Date_Of_Birth'];
            $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1 -> diff($date2);
            $years = $diff->y;
            $months= $diff->m;
            if($test_res=='POSITIVE'){
             
            if($years<1){
                 $age2=$months;
                 if($age2<1 && $gender=='Male'){
                    $under1monthMalepos++; 
                    $totalunder1monthMale++;
                 }  elseif($age2<1 && $gender=='Female') {
                    $under1monthFemalepos++;
                    $totalmonthFemale++;
                 }elseif (($age2>=1 && $age2<=11) && $gender=='Male') {
                    $month11Malepos++;
                    $total11Male++;
                 }elseif (($age2>=1 && $age2<=11) && $gender=='Female'){
                    $month11Femalepos++;
                    $total11Female++;
                 }
                 
                 
             }  elseif(($years>=1 && $years<=5) && ($gender=='Male')) {
                 $month12Malepos++;
                 $total12Male++;
             }elseif(($years>=1 && $years<=5) && ($gender=='Female')) {
                $month12Femalepos++;
                $total12Female++;
             }elseif(($years>5) && ($gender=='Male')) {
                $Year5Malepos++;
                $total5Male++;
             }elseif(($years>5) && ($gender=='Female')) {
                $Year5Femalepos++;
                $total5Female++;
             }
                  
             
                
            }elseif ($test_res=='NEGATIVE') {
                
                if($years<1){
                 $age2=$months;
                 if($age2<1 && $gender=='Male'){
                    $under1monthMaleneg++; 
                    $totalunder1monthMale++;
                 }  elseif($age2<1 && $gender=='Female') {
                    $under1monthFemaleneg++;
                    $totalmonthFemale++;
                 }elseif (($age2>=1 && $age2<=11) && $gender=='Male') {
                    $month11Maleneg++;
                    $total11Male++;
                 }elseif (($age2>=1 && $age2<=11) && $gender=='Female'){
                    $month11Femaleneg++;
                    $total11Female++;
                 }
                 
                 
             }  elseif(($years>=1 && $years<=5) && ($gender=='Male')) {
                 $month12Maleneg++;
                 $total12Male++;
             }elseif(($years>=1 && $years<=5) && ($gender=='Female')) {
                $month12Femaleneg++;
                $total12Female++;
             }elseif(($years>5) && ($gender=='Male')) {
                $Year5Maleneg++;
                $total5Male++;
             }elseif(($years>5) && ($gender=='Female')) {
                $Year5Femaleneg++;
                $total5Female++;
             }
                    
            }


            }
            
                        
      $TotalMalepos=$under1monthMalepos+$month11Malepos+$month12Malepos+$Year5Malepos;
      $TotalMaleneg=$under1monthMaleneg+$month11Maleneg+$month12Maleneg+$Year5Maleneg;
      $TotalFemalepos=$under1monthFemalepos+$month11Femalepos+$month12Femalepos+$Year5Femalepos;
      $TotalFemaleneg=$under1monthFemaleneg+$month11Femaleneg+$month12Femaleneg+$Year5Femaleneg;
      $GrandTotalpos=$TotalMalepos+$TotalFemalepos;
      $GrandTotalneg=$TotalMaleneg+$TotalFemaleneg;
            
             $disp.= '<td style="text-align: center;">
                    <span> 
                        <span>Positive</span>
                        <br />
                        <span>Negative</span>
                    </span>

                 </td>'; 
      
      
            $disp.= '<td style="text-align: center;">
                 <span>  
                  <span style="text-align:center">'.$under1monthMalepos.'</span> &nbsp;&nbsp;&nbsp;  <span style="text-align:center">'.$under1monthFemalepos.'</span> <br />
                   <span style="text-align:center">'.$under1monthMaleneg.'</span> &nbsp;&nbsp;&nbsp;  <span style="text-align:center">'.$under1monthFemaleneg.'</span> 
                 </span>
                 </td>';
           
            
            $disp.= '<td style="text-align: center;">
                 <span>  
                  <span style="text-align:center">'.$month11Malepos.'</span> &nbsp;&nbsp;&nbsp;  <span style="text-align:center">'.$month11Femalepos.'</span> <br />
                   <span style="text-align:center">'.$month11Maleneg.'</span> &nbsp;&nbsp;&nbsp;  <span style="text-align:center">'.$month11Femaleneg.'</span> 
                 </span>
                 </td>';
           
            
            $disp.= '<td style="text-align: center;">
                 <span>  
                  <span style="text-align:center">'.$month12Malepos.'</span> &nbsp;&nbsp;&nbsp;  <span style="text-align:center">'.$month12Femalepos.'</span> <br />
                   <span style="text-align:center">'.$month12Maleneg.'</span> &nbsp;&nbsp;&nbsp;  <span style="text-align:center">'.$month12Femaleneg.'</span> 
                 </span>
                 </td>';
           
            $disp.= '<td style="text-align: center;">
                 <span>  
                  <span style="text-align:center">'.$Year5Malepos.'</span> &nbsp;&nbsp;&nbsp;  <span style="text-align:center">'.$Year5Femalepos.'</span> <br />
                   <span style="text-align:center">'.$Year5Maleneg.'</span> &nbsp;&nbsp;&nbsp;  <span style="text-align:center">'.$Year5Femaleneg.'</span> 
                 </span>
                 </td>';
           
            
            $disp.= '<td style="text-align: center;">
                 <span>  
                  <span style="text-align:center">'.$TotalMalepos.'</span> &nbsp;&nbsp;&nbsp;  <span style="text-align:center">'.$TotalFemalepos.'</span> <br />
                   <span style="text-align:center">'.$TotalMaleneg.'</span> &nbsp;&nbsp;&nbsp;  <span style="text-align:center">'.$TotalFemaleneg.'</span> 
                 </span>
                 </td>';
           
             $disp.= '<td style="text-align: center;">
                 <span>  
                  <span style="text-align:center">'.$GrandTotalpos.'</span> &nbsp;&nbsp;&nbsp; <br />
                   <span style="text-align:center">'.$GrandTotalneg.'</span> &nbsp;&nbsp;&nbsp; 
                 </span>
                 </td>';

      $under1monthMalepos=0;
      $under1monthMaleneg=0;
      $under1monthFemalepos=0;
      $under1monthFemaleneg=0;
      $month11Malepos=0;
      $month11Maleneg=0;
      $month11Femalepos=0;
      $month11Femaleneg=0;
      $month12Malepos=0;
      $month12Maleneg=0;
      $month12Femalepos=0;
      $month12Femaleneg=0;
      $Year5Malepos=0;
      $Year5Maleneg=0;
      $Year5Femalepos=0;
      $Year5Femaleneg=0;
      $TotalMalepos=0;
      $TotalMaleneg=0;
      $TotalFemalepos=0;
      $TotalFemaleneg=0;
      $GrandTotalpos=0;
      $GrandTotalneg=0;

    $disp.= '</tr>';
          }
          
          $FinalMale=$totalunder1monthMale+$total11Male+$total12Male+$total5Male;
          $FinalFemale=$totalmonthFemale+$total11Female+$total12Female+$total5Female;
          $TotalFinal=$FinalMale+$FinalFemale;
          
        // $disp.='<tr><td></td><td style="font-weight:bold;font-size:14px"><b>TOTAL</b></td> <td style="text-align:center"><span> <span><b>'.$totalunder1monthMale.'</b></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span><b>'.$totalmonthFemale.'</b></span> </span></td>  <td style="text-align:center"><span> <span><b>'.$total11Male.'</b></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span><b>'.$total11Female.'</b></span> </span></td> <td style="text-align:center"><span> <span><b>'.$total12Male.'</b></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span><b>'.$total12Female.'</b></span> </span></td> <td style="text-align:center"><span> <span><b>'.$total5Male.'</b></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span><b>'.$total5Female.'</b></span> </span></td></tr>';
         
         
        $disp.= '<tr>
                
                 <td></td><td style="font-weight:bold;font-size:14px"><b>TOTAL</b></td> 
                 <td></td>
                 
                 <td style="text-align: center;"> 
                  <span style="text-align:center"><b>'.$totalunder1monthMale.'</b></span> &nbsp;&nbsp;&nbsp;  <span style="text-align:center"><b>'.$totalmonthFemale.'</b></span>
                
                 </td>
               
                 <td style="text-align: center;"> 
                  <span style="text-align:center"><b>'.$total11Male.'</b></span> &nbsp;&nbsp;&nbsp;  <span style="text-align:center"><b>'.$total11Female.'</b></span>
                
                 </td>
                 <td style="text-align: center;"> 
                  <span style="text-align:center"><b>'.$total12Male.'</b></span> &nbsp;&nbsp;&nbsp;  <span style="text-align:center"><b>'.$total12Female.'</b></span>
                
                 </td>

                 <td style="text-align: center;"> 
                  <span style="text-align:center"><b>'.$total5Male.'</b></span> &nbsp;&nbsp;&nbsp;  <span style="text-align:center"><b>'.$total5Female.'</b></span>
                
                 </td>

                 <td style="text-align: center;"> 
                  <span style="text-align:center"><b>'.$FinalMale.'</b></span> &nbsp;&nbsp;&nbsp;  <span style="text-align:center"><b>'.$FinalFemale.'</b></span>
                
                 </td>

                <td style="text-align: center;"> 
                  <span style="text-align:center"><b>'.$TotalFinal.'</b></span> &nbsp;&nbsp;&nbsp;
                
                 </td>

                </tr>'; 
         
         
         $totalunder1monthMale=0;
         $totalmonthFemale=0;
         $total11Male=0;
         $total11Female=0;
         $total12Male=0;
         $total12Female=0;
         $total5Male=0;
         $total5Female=0;
          
          
}         

    $disp.= "</table>";
   
    include("MPDF/mpdf.php");
    $mpdf = new mPDF('c');
    $mpdf->SetFooter('{PAGENO}/{nb}|  Printed By '.$Employee.'                   {DATE d-m-Y H:m:s}');
    $mpdf->WriteHTML($disp);
    $mpdf->Output();
    exit;
?>