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
        
 $filter=" Registration_ID='$Registration_ID' AND consultation_ID='".$_GET['consultation_ID']."' AND DATE(Days)=DATE(NOW()) ORDER BY Days DESC";
 
 if(!empty($start_date) && !empty($end_date)){
     $filter=" Registration_ID='$Registration_ID' AND consultation_ID='".$consultation_ID."' AND Days BETWEEN '$start_date' AND '$end_date' ORDER BY Days DESC";
 }

echo '<center><table width =100% border="0" id="nurse_care">';       
        echo '<thead>
               <tr>
                <td style="width:5%;"><b>S/N</b></td>
                <td><b>DATE &amp; TIME</b></td>
                <td><b>NURSING DIAGNOSIS</b></td>
                <td><b>OBJECTIVES OF CARE</b></td>
                <td><b>EXPECTED OUTCOME</b></td>
                <td><b>INTERVENTION</b></td>
                <td><b>EVALUATION</b></td>
                <td><b>PREPARED BY</b></td>
              </tr>
             </thead>   
                ';
	//echo"<tbody style='position:absolute;top:40px;z-index:-1;width:100%;'>";
	
    $select_testing_Qry = "SELECT Days,Nurse_Care,Objective_Care,Expect_Outcome,Nursing_Intervate,Evaluation,Employee_Name FROM tbl_patient_nursecare n JOIN tbl_employee e ON e.Employee_ID=n.employee_ID  WHERE $filter";
	
    $select_testing_record = mysqli_query($conn,$select_testing_Qry) or die(mysqli_error($conn));   
    while($row = mysqli_fetch_array($select_testing_record)){
                echo "<tr ><td id='thead'>".$temp."</td>";
		echo "<td style='width:10%'>".$row['Days']."</td>";
		echo"<td >".$row['Nurse_Care']."</td>";
	        echo"<td >".$row['Objective_Care']."</td>";
	        echo"<td >".$row['Expect_Outcome']."</td>";
		echo"<td >".$row['Nursing_Intervate']."</td>";	
		echo"<td >".$row['Evaluation']."</td>";
                echo"<td >".$row['Employee_Name']."</td>";
                echo "</tr>";
	 $temp++;
     } 
      echo "</table></center>";
?>
 

