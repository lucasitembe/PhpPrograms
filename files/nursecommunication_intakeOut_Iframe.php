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
        
 $filter=" Registration_ID='$Registration_ID' AND consultation_ID='".$_GET['consultation_ID']."' AND DATE(Date)=DATE(NOW())  ORDER BY Date DESC";
 
 if(!empty($start_date) && !empty($end_date)){
     $filter=" Registration_ID='$Registration_ID' AND consultation_ID='".$consultation_ID."' AND Date BETWEEN '$start_date' AND '$end_date' ORDER BY Date DESC";
 }
 
// $getDates =  mysqli_query($conn,"SELECT DATE(Date) AS dateRange FROM tbl_input_output_nursecommunication WHERE $filter GROUP BY DATE(Date)") or die(mysqli_error($conn));
// $thisDate = date('F jS Y l', strtotime('2012-11-1')) ;
//
// while ($rowDate = mysqli_fetch_array($query)) {
//    $thisDate = date('F jS Y l', strtotime($rowDate['dateRange']));
// }
 
echo '<center><table width =100% border="0" id="intake_out">';       
        echo '<thead>
               <tr>
                 <td style="width:5%;"><b>S/N</b></td>
                 <td><b>DATE & TIME</b></td>
                 <td><b>TYPE OF FLUID</b></td>
                 <td><b>AMOUNT I/V</b></td>
                 <td><b>AMOUNT-ORAL</b></td>
                 <td><b>URINE</b></td>
                 <td><b>STOOL</b></td>
                 <td><b>VOMIT</b></td>
		 <td><b>REMARKS</b></td>
               </tr>
             </thead>   
                ';
							
							
$Transaction_Items_Qry = "SELECT * FROM tbl_input_output_nursecommunication WHERE $filter";


 $select_Transaction_Items = mysqli_query($conn,$Transaction_Items_Qry) or die(mysqli_error($conn)); 
 while($row = mysqli_fetch_array($select_Transaction_Items)){
	        echo "<tr ><td>".$temp. "</td>";
	        echo "<td>".$row['Date']."</td>";
		echo "<td>".$row['Day_Fluid']."</td>";
		echo "<td>".$row['Amount_Fluid']."</td>";
		echo "<td>".$row['Amount_Oral']."</td>";
		echo "<td>".$row['Urine']."</td>";
		echo "<td>".$row['Stool']."</td>";
		echo "<td>".$row['Vomit']."</td>";
		echo "<td >".$row['Remarks']."</td>";
		echo "</tr>";
		//echo "<td>".$row['Remarks']."</td>";
	 $temp++;
     }  
     
     echo "</table></center>";
?>
