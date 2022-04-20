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
        
 $filter=" Registration_ID='$Registration_ID' AND consultation_ID='".$_GET['consultation_ID']."' AND DATE(date)=DATE(NOW()) ORDER BY date DESC";
 
 if(!empty($start_date) && !empty($end_date)){
     $filter=" Registration_ID='$Registration_ID' AND consultation_ID='".$consultation_ID."' AND date BETWEEN '$start_date' AND '$end_date' ORDER BY date DESC";
 }
 
 //echo $filter;
	

echo '<center><table width ="100%" class="vital" border="0" id="bloodtest">';
echo '<thead>
        <tr> 
          <th style="width:5%;"><b>SN</b></th>
          <th ><b>Days</b></th>
	  <th ><b>Date & Time </b></th>
	  <th ><b>Type</b></th>
	  <th ><b>Meal Time</b></th> 
	  <th ><b>Glucose</b></th>
	  <th ><b>Notes</b></th>
        </tr>
      </thead>';


	$testing_record = "SELECT * FROM tbl_testing_record  WHERE $filter";
	//die($testing_record);
		
	
    $select_testing_record = mysqli_query($conn,$testing_record) or die(mysqli_error($conn));  
    
    
    while($row = mysqli_fetch_array($select_testing_record)){
        echo "<tr >
               <td>".$temp."</td>";
        echo "<td >".$row['Days']."</td>";
        echo "<td >".$row['date']."</td>";
        echo "<td >".$row['type']."</td>";
        echo "<td >".$row['meal']."</td>";
        echo "<td >".$row['Glucose']."</td>";	
        echo "<td >".$row['notes']."</td>";
        echo "</tr>";
	 $temp++;
     }  
//echo"</tbody>";
?>
 </table></center>

