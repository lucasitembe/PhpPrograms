<link rel="stylesheet" href="table.css" media="screen">
<?php
  include("./includes/connection.php");
  session_start();
?>
<center>
<table width=100%>
	<?php
	    if($_GET['Registration_ID']){
		$Registration_ID=$_GET['Registration_ID'];
		$Check_In_Type=$_GET['section'];
		$Status_From=$_GET['Status_From'];
		
	    }
	
	
	    if($Check_In_Type == 'Rch'){
	      //run the query to select all information for the patient according to the department
	      $patient_file_info=mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_patient_payments pp,tbl_patient_payment_item_list ppl,tbl_rch rch
					WHERE pr.Registration_ID=pp.Registration_ID
					AND pp.Patient_Payment_ID=ppl.Patient_Payment_ID
					AND rch.Registration_ID=pr.Registration_ID 
					AND rch.Registration_ID=pp.Registration_ID  
					AND rch.Registration_ID='$Registration_ID'
					AND ppl.Check_In_Type='$Check_In_Type' ORDER BY pp.Receipt_Date DESC");
	    }
	    if($Check_In_Type == 'Laboratory'){
	      //run the query to select all information for the patient according to the department
	      $patient_file_info=mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_patient_payments pp,tbl_patient_payment_item_list ppl,tbl_patient_payment_results ppr
					WHERE pr.Registration_ID=pp.Registration_ID
					AND pp.Patient_Payment_ID=ppl.Patient_Payment_ID
					AND ppr.Patient_ID=pr.Registration_ID
					AND pp.Registration_ID='$Registration_ID'
					AND ppl.Check_In_Type='$Check_In_Type' GROUP BY pp.Receipt_Date ORDER BY pp.Receipt_Date DESC");
	    }
	    if($Check_In_Type == 'Cecap'){
	      //run the query to select all information for the patient according to the department
	      $patient_file_info=mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_patient_payments pp,tbl_patient_payment_item_list ppl,tbl_patient_payment_results ppr
					WHERE pr.Registration_ID=pp.Registration_ID
					AND pp.Patient_Payment_ID=ppl.Patient_Payment_ID
					AND ppr.Patient_ID=pr.Registration_ID
					AND pp.Registration_ID='$Registration_ID'
					AND ppl.Check_In_Type='$Check_In_Type' GROUP BY pp.Receipt_Date ORDER BY pp.Receipt_Date DESC");
	    }
	    if($Check_In_Type == 'Radiology'){
	      //run the query to select all information for the patient according to the department
	      $patient_file_info=mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_patient_payments pp,tbl_patient_payment_item_list ppl,tbl_patient_payment_results ppr
					WHERE pr.Registration_ID=pp.Registration_ID
					AND pp.Patient_Payment_ID=ppl.Patient_Payment_ID
					AND ppr.Patient_ID=pr.Registration_ID
					AND pp.Registration_ID='$Registration_ID'
					AND ppl.Check_In_Type='$Check_In_Type' GROUP BY pp.Receipt_Date ORDER BY pp.Receipt_Date DESC");
	    }
	    if($Check_In_Type == 'Dressing'){
	      //run the query to select all information for the patient according to the department
	      $patient_file_info=mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_patient_payments pp,tbl_patient_payment_item_list ppl,tbl_patient_payment_results ppr
					WHERE pr.Registration_ID=pp.Registration_ID
					AND pp.Patient_Payment_ID=ppl.Patient_Payment_ID
					AND ppr.Patient_ID=pr.Registration_ID
					AND pp.Registration_ID='$Registration_ID'
					AND ppl.Check_In_Type='$Check_In_Type' GROUP BY pp.Receipt_Date ORDER BY pp.Receipt_Date DESC");
	    }
	    if($Check_In_Type == 'Ear'){
	      //run the query to select all information for the patient according to the department
	      $patient_file_info=mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_patient_payments pp,tbl_patient_payment_item_list ppl,tbl_patient_payment_results ppr
					WHERE pr.Registration_ID=pp.Registration_ID
					AND pp.Patient_Payment_ID=ppl.Patient_Payment_ID
					AND ppr.Patient_ID=pr.Registration_ID
					AND pp.Registration_ID='$Registration_ID'
					AND ppl.Check_In_Type='$Check_In_Type' GROUP BY pp.Receipt_Date ORDER BY pp.Receipt_Date DESC");
	    }
	    if($Check_In_Type == 'Optical'){
	      //run the query to select all information for the patient according to the department
	      $patient_file_info=mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_patient_payments pp,tbl_patient_payment_item_list ppl
					WHERE pr.Registration_ID=pp.Registration_ID
					AND pp.Patient_Payment_ID=ppl.Patient_Payment_ID
					AND pp.Registration_ID='$Registration_ID'
					AND ppl.Check_In_Type='$Check_In_Type' GROUP BY pp.Receipt_Date ORDER BY pp.Receipt_Date DESC");
	    }
	    if($Check_In_Type == 'Physiotherapy'){
	      //run the query to select all information for the patient according to the department
	      $patient_file_info=mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_patient_payments pp,tbl_patient_payment_item_list ppl
					WHERE pr.Registration_ID=pp.Registration_ID
					AND pp.Patient_Payment_ID=ppl.Patient_Payment_ID
					AND pp.Registration_ID='$Registration_ID'
					AND ppl.Check_In_Type='$Check_In_Type' GROUP BY pp.Receipt_Date ORDER BY pp.Receipt_Date DESC");
	    }
	    
	    if($Check_In_Type == 'Laboratory'){
	      //run the query to select all information for the patient according to the department
	      $patient_file_info=mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_patient_payments pp,tbl_patient_payment_item_list ppl,tbl_patient_payment_results ppr
					WHERE pr.Registration_ID=pp.Registration_ID
					AND pp.Patient_Payment_ID=ppl.Patient_Payment_ID
					AND ppr.Patient_ID=pr.Registration_ID
					AND pp.Registration_ID='$Registration_ID'
					AND ppl.Check_In_Type='$Check_In_Type' GROUP BY pp.Receipt_Date ORDER BY pp.Receipt_Date DESC");
	    }
	    
	    if($Check_In_Type == 'Doctor Room'){ ?>
		      <?php
		      echo "<tr id='thead'>
			  <td style='text-align: left;border: 1px #ccc solid; width: 3%'><b>SN</b></td>
			  <td style='text-align: left;border: 1px #ccc solid;'><b>Date And Time</b></td>
			  <td style='text-align: left;border: 1px #ccc solid;'><b>Action</b></td>
		      </tr>";
		      
			$select_patients = "SELECT * FROM tbl_consultation c,tbl_patient_payment_item_list ppl,tbl_patient_payments pp,tbl_patient_registration pr
					    WHERE c.Patient_Payment_Item_List_ID=ppl.Patient_Payment_Item_List_ID
					    AND c.Registration_ID=pr.Registration_ID
					    AND ppl.Patient_Payment_ID=pp.Patient_Payment_ID
					    AND pp.Registration_ID=pr.Registration_ID
					    AND c.Registration_ID = $Registration_ID";
			$result = mysqli_query($conn,$select_patients);
			$i=1;
			while($row = mysqli_fetch_array($result)){
			  
			  $Patient_Payment_ID=$row['Patient_Payment_ID'];
			  $Patient_Payment_Item_List_ID=$row['Patient_Payment_Item_List_ID'];
			  
			    ?>
			    <tr>
				<td><?php echo $i.". "; ?></td>
				<td><?php echo $row['Consultation_Date_And_Time']; ?></td>
				<td>
				    <a href='doctorpatientreview.php?consultation_ID=<?php echo $row['consultation_ID'];?><?php if(isset($_GET['doctorsworkpage'])){ ?>&doctorsworkpage=yes<?php } ?>&Registration_ID=<?php echo $row['Registration_ID'];?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>' target='_parent' style='text-decoration: none' class='art-button-green'>
					<button style='width: 40%; height: 100%' class='art-button-green'><?php echo "REVIEW"; ?></button>
				    </a>
				</td>
			    </tr>
		    <?php
		    $i++;
		   }
	      ?>
	      
	    <?php }else{//section other than doctor room
	      echo "<tr id='thead'>
		      <td style='text-align: left;border: 1px #ccc solid; width: 3%'><b>SN</b></td>
		      <td style='text-align: left;border: 1px #ccc solid;'><b>Date And Time</b></td>
		      <td style='text-align: left;border: 1px #ccc solid;'><b>Patient Type</b></td>
		      <td style='text-align: left;border: 1px #ccc solid;'><b>Action</b></td>
		   </tr>";
		   
	      
	      $Result_Datetime='';
	    $temp=1;
	    while($row = mysqli_fetch_array($patient_file_info)){
	      //return rows
	      $Receipt_Date=$row['Receipt_Date'];
	      $location=$row['Billing_Type'];
	      $Check_In_Type=$row['Check_In_Type'];
	      $Patient_Payment_ID=$row['Patient_Payment_ID'];
	      //if check_in_type is not equal to lab
	      if($Check_In_Type != 'Laboratory'){
		$Result_Datetime='';
		$Transaction_Date=$row['Transaction_Date_And_Time'];
	      }else{
		$Result_Datetime=$row['Result_Datetime'];
	      }
	      
	      $action="View";
	      
	      echo "<tr>";
	      echo "<td>".$temp."</td>";
	      if($Result_Datetime == ""){?>
		<td><?php echo date('j F, Y',strtotime($Transaction_Date))?></td>
	      <?php }else{
		echo "<td>".date('j F, Y',strtotime($Result_Datetime))."</td>";
	      }
	      echo "<td>".$location."</td>";
	      if($Check_In_Type != 'Laboratory'){
		echo "<td style='text-align: left;border: 1px #ccc solid;'><a href='template_options.php?Registration_ID=".$row['Registration_ID']."&Check_In_Type=".$Check_In_Type."&Patient_Payment_ID=".$Patient_Payment_ID."&template=".$Check_In_Type."&Status_From=".$_GET['Status_From']."&PatientFile=PatientFileThisForm' target='_parent' style='text-decoration: none;' class='art-button-green'>".$action."</a></td>";
	      }else{
		echo "<td style='text-align: left;border: 1px #ccc solid;'><a href='template_options.php?Registration_ID=".$row['Registration_ID']."&Result_Datetime=".date('Y-m-d',strtotime($Result_Datetime))."&Check_In_Type=".$Check_In_Type."&Patient_Payment_ID=".$Patient_Payment_ID."&template=".$Check_In_Type."&Status_From=".$_GET['Status_From']."&PatientFile=PatientFileThisForm' target='_parent' style='text-decoration: none;' class='art-button-green'>".$action."</a></td>";
	      }
	      $temp++;
	    }
	      
	       
	    }
	    
	?>
</table>
</center>