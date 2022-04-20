
<?php

    include("./includes/connection.php"); 
	 $temp = 1;
    if(isset($_GET['admission'])){
        $Registration_ID = $_GET['admission'];   
    }else{
        $Registration_ID = '';
    }
	
	if(isset($_GET['starDate'])){
		$start_date = $_GET['starDate'];
	} else {
		$start_date = '';
	}
	
	if(isset($_GET['endDate'])){
		$end_date = $_GET['endDate'];
	} else {
		$end_date = '';
	}
	
        $htm = "<center><img src='branchBanner/branchBanner1.png' width='100%' ></center>";
        $htm.='<center><table width =100%>';       
        $htm.='<tr id="thead"><td style="width:5%;"><b>S/N</b></td>
                <td><b>DATE &amp; TIME</b></td>
                <td><b>TEMP(c)</b></td>
                <td><b>PR</b></td>
                <td><b>RESP(bpm)</b></td>
                <td><b>SO2</b></td>
                <td><b>DAILY BWT</b></td>
                <td><b>AMOUNT OF FEEDS</b></td>
                <td><b>AMT. LEFT IN THE CUP</b></td>
                <td><b>AMT. TAKEN ORALLY</b></td>               
                <td><b>AMT. TAKEN BY NGT</b></td>
                <td><b>EST. VOMMITED MLS</b></td>
                <td><b>DIARRHOEA IF PRESENT</b></td>
                <td><b>REMARKS</b></td>
                ';
    
    $Transaction_Items_Qry = "SELECT * FROM tbl_mulnutrition_observation WHERE Admission_ID='$Registration_ID'";
	
	if($start_date!='' && $end_date!=''){
		$Transaction_Items_Qry = $Transaction_Items_Qry . " AND date_time BETWEEN '$start_date' AND '$end_date' ORDER BY date_time DESC";
    } else {
		$Transaction_Items_Qry = $Transaction_Items_Qry . " ORDER BY date_time DESC";		
	} 	
	
	
    $select_Transaction_Items = mysqli_query($conn,$Transaction_Items_Qry) or die(mysqli_error($conn)); 
    while($row = mysqli_fetch_array($select_Transaction_Items)){
	        $htm.="<tr ><td id='thead'>".$temp. ".</td>";
                $htm.="<td>".$row['date_time']."</td>";
		$htm.="<td>".$row['temp']."</td>";
		$htm.="<td>".$row['Pr']."</td>";
		$htm.="<td>".$row['Resp']. "</td>";
		$htm.="<td>" .$row['So']. "</td>";
		$htm.="<td>" .$row['daily_bwt']. "</td>";
		$htm.="<td>" .$row['feed_amount']. "</td>";
		$htm.="<td>" .$row['cup_Amount']. "</td>";
                $htm.="<td>" .$row['oral_taken']. "</td>";
                $htm.="<td>" .$row['ngt_taken']. "</td>";
                $htm.="<td>" .$row['vomitted_mls']. "</td>";
                $htm.="<td>" .$row['diarrhoea']. "</td>";
                $htm.="<td>" .$row['Remarks']. "</td>";
	 $temp++;
     }  $htm.="</tr>";

  $htm.="</table></center>";

        include("MPDF/mpdf.php");
        $mpdf=new mPDF(); 

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($htm);
        $mpdf->Output();
        exit; 

?>