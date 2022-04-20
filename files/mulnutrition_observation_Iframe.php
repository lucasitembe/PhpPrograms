<?php
    include("./includes/connection.php"); 
	$temp = 1;
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];   
    }if(isset($_GET['start'])){
		$start_date = $_GET['start'];
    }
    if(isset($_GET['end'])){
            $end_date = $_GET['end'];
    } if(isset($_GET['consultation_ID'])){
            $consultation_ID = $_GET['consultation_ID'];
    }
        
 $filter=" Registration_ID='$Registration_ID' AND consultation_ID='".$_GET['consultation_ID']."' AND DATE(date_time)=DATE(NOW()) ORDER BY date_time DESC";
 
 if(!empty($start_date) && !empty($end_date)){
     $filter=" Registration_ID='$Registration_ID' AND consultation_ID='".$consultation_ID."' AND date_time BETWEEN '$start_date' AND '$end_date' ORDER BY date_time DESC";
 }

	
       
            
        echo '<center><table width =100% border="0" id="nurse_mulnitrution">';       
        echo '<thead>
                 <tr>
                    <td style="width:5%;"><b>S/N</b></td>
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
                 </tr>
                </thead>  
                ';
    
    $Transaction_Items_Qry = "SELECT * FROM tbl_mulnutrition_observation WHERE $filter";
	
	
    $select_Transaction_Items = mysqli_query($conn,$Transaction_Items_Qry) or die(mysqli_error($conn)); 
    while($row = mysqli_fetch_array($select_Transaction_Items)){
	        echo "<tr ><td id='thead'>".$temp. ".</td>";
                echo "<td>".$row['date_time']."</td>";
		echo "<td>".$row['temp']."</td>";
		echo "<td>".$row['Pr']."</td>";
		echo "<td>".$row['Resp']. "</td>";
//		echo "<td>" .$row['Resp']. "</td>";
		echo "<td>" .$row['So']. "</td>";
		echo "<td>" .$row['daily_bwt']. "</td>";
		echo "<td>" .$row['feed_amount']. "</td>";
		echo "<td>" .$row['cup_Amount']. "</td>";
                echo "<td>" .$row['oral_taken']. "</td>";
                echo "<td>" .$row['ngt_taken']. "</td>";
                echo "<td>" .$row['vomitted_mls']. "</td>";
                echo "<td>" .$row['diarrhoea']. "</td>";
                echo "<td>" .$row['Remarks']. "</td>";
                echo "</tr>";
                
                 $temp++;
        }
     ?>
</table></center>
