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

// $filter=" Registration_ID='$Registration_ID' AND consultation_ID='".$_GET['consultation_ID']."' AND DATE(date)=DATE(NOW()) ORDER BY date DESC";


   //remove consultation id
 $filter=" Registration_ID='$Registration_ID' AND DATE(date)=DATE(NOW()) ORDER BY date DESC";

 if(!empty($start_date) && !empty($end_date)){
    // $filter=" Registration_ID='$Registration_ID' AND consultation_ID='".$consultation_ID."' AND date BETWEEN '$start_date' AND '$end_date' ORDER BY date DESC";

     //remove the consultation id
         $filter=" Registration_ID='$Registration_ID'AND date BETWEEN '$start_date' AND '$end_date' ORDER BY date DESC";

 }

        echo '<center><table width =100% border="0" id="nurse_obsv">';
        echo '<thead>
               <tr>
                <td style="width:5%;"><b>S/N</b></td>
                <td><b>DATE &amp; TIME</b></td>
                <td><b>TEMP(c)</b></td>
                <td><b>BP 1/2hr (mmhg)</b></td>
                <td><b>Pulse 1/2hr (bpm)</b></td>
                <td><b>Resp(bpm)</b></td>

                <td><b>FBG</b></td>
                <td><b>Drainage (ccc)</b></td>
                <td><b>RBG</b></td>

                <td><b>Oxygen Saturation</b></td>
                <td><b>Blood Transfusion</b></td>
                <td><b>Body Weight</b></td>

              </tr>
             </thead>
                ';

    //$Transaction_Items_Qry = "SELECT * FROM tbl_nursecommunication_observation WHERE observation_admission_Id='$Registration_ID'";

	$Transaction_Items_Qry = "SELECT * FROM tbl_nursecommunication_observation  WHERE $filter";


    $select_Transaction_Items = mysqli_query($conn,$Transaction_Items_Qry) or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_Transaction_Items)){
	        echo "<tr >";
                            echo "<td id='thead'>".$temp. ".</td>";
                            echo "<td>".$row['date']."</td>";
                            echo "<td>".$row['Temperature']."</td>";
                            echo "<td>".$row['Blood_Pressure']."</td>";
                            echo "<td>".$row['Pulse_Blood']. "</td>";
                            echo "<td>" .$row['Resp_Bpressure']. "</td>";
//                            echo "<td>" .$row['Fluid_Drug']. "</td>";
                            echo "<td>" .$row['fbg']. "</td>";
                            echo "<td>" .$row['Drainage']. "</td>";
                            echo "<td>" .$row['rbg']. "</td>";
//                            echo "<td>" .$row['Urine']. "</td>";
                            echo "<td>" .$row['oxygen_saturation']. "</td>";
                            echo "<td>" .$row['blood_transfusion']. "</td>";
                            echo "<td>" .$row['body_weight']. "</td>";
                echo "</tr>";

                 $temp++;
     }
        //}
     ?>
</table></center>
