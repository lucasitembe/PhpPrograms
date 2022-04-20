<link rel="stylesheet" href="table.css" media="screen">
<?php
  include("./includes/connection.php");
  session_start();
?>
<center>
		<table width=100%>
			    <tr id="thead">
				<td style='text-align: left;border: 1px #ccc solid; width: 3%'><b>SN</b></td>
				<td style='text-align: left;border: 1px #ccc solid;'><b>Date And Time</b></td>
                                <td style='text-align: left;border: 1px #ccc solid;'><b>Location</b></td>
                                <td style='text-align: left;border: 1px #ccc solid;'><b>Action</b></td>
			    </tr>
			    <?php
                                if($_GET['Registration_ID']){
                                    $Registration_ID=$_GET['Registration_ID'];
                                    $Check_In_Type=$_GET['section'];
                                }
                            
                            
                            
                                //run the query to select all information for the patient according to the department
                                $patient_file_info=mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_patient_payments pp,tbl_payment_cache pc,tbl_patient_payment_item_list ppl
                                                               WHERE pr.Registration_ID=pp.Registration_ID
                                                               AND pp.Patient_Payment_ID=ppl.Patient_Payment_ID
                                                               AND pp.Registration_ID='$Registration_ID'
                                                               AND ppl.Check_In_Type='$Check_In_Type'");
                                
                                $temp=1;
                                while($row = mysqli_fetch_array($patient_file_info)){
				  //return rows
				  $Receipt_Date=$row['Receipt_Date'];
				  $location=$row['Billing_Type'];
				  $Check_In_Type=$row['Check_In_Type'];
				  $Patient_Payment_ID=$row['Patient_Payment_ID'];
				  $action="View";
				  
				  echo "<tr>";
				  echo "<td>".$temp."</td>";
				  echo "<td>".$Receipt_Date."</td>";
				  echo "<td>".$location."</td>";
				  echo "<td style='text-align: left;border: 1px #ccc solid;'><a href='patient_file_departmental_result.php?Registration_ID=".$row['Registration_ID']."&Receipt_Date=".date('Y-m-d',strtotime($Receipt_Date))."&Check_In_Type=".$Check_In_Type."&Patient_Payment_ID=".$Patient_Payment_ID."&PatientFile=PatientFileThisForm' target='_parent' style='text-decoration: none;' class='art-button-green'>".$action."</a></td>";
				  $temp++;
				}
                            ?>
		</table>
	    </center>